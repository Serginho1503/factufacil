<?php

/* ------------------------------------------------
  ARCHIVO: guiaremision_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Guia remision.
  FECHA DE CREACIÓN: 15/08/2017
 * 
  ------------------------------------------------ */

class Guiaremision_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $query = $this->db->query("SET time_zone = '-5:00';");
    }


   /* VENTAS TOTALES POR RANGO */
    public function lst_guiaremision($sucursal, $desde, $hasta){
      $sql = $this->db->query("SELECT g.idguia, g.fechaemision, g.dirpartida, g.idtransportista,
                                       g.fechaini, g.fechafin, g.placa, g.secuencial, d.numdocsustento,
                                       p.cod_establecimiento, p.cod_puntoemision, 
                                       d.iddestinatario, c.nom_cliente, c.ident_cliente, 
                                       t.razonsocial as transportista, 
                                       IFNULL(s.autorizado, 0) as autorizada
                                FROM sriguiaremisionencab g 
                                INNER JOIN sriguiaremisiondestino d on d.idguia = g.idguia
                                INNER JOIN clientes c ON c.id_cliente = d.iddestinatario
                                INNER JOIN sritransportista t ON t.idtransportista = g.idtransportista
                                INNER JOIN punto_emision p on p.id_puntoemision = g.id_puntoemision
                                LEFT JOIN guiaremisioninfoestadosri s on s.idguia = g.idguia
                                WHERE g.fechaemision BETWEEN '$desde' AND '$hasta' AND
                                      (($sucursal = 0) or (p.id_sucursal = $sucursal))
                                ORDER BY g.fechaemision desc,g.secuencial");
      $resu = $sql->result();
      return $resu;
    }

    public function sel_guiaremision($id){
      $sql = $this->db->query("SELECT g.idguia, g.fechaemision, g.dirpartida, g.idtransportista,
                                      g.fechaini, g.fechafin, g.placa, g.secuencial, g.id_puntoemision,
                                      d.numdocsustento, d.iddestinatario, d.motivo, d.docaduanero, 
                                      d.codestabdestino, d.ruta, d.coddocsustento, 
                                      d.numautdocsustento, d.fechaemidocsustento, d.dirllegada,
                                      concat(p.cod_establecimiento,'-',p.cod_puntoemision) as puntoemision,
                                      t.razonsocial as transportistanombre, t.cedula as transportistacedula,
                                      c.nom_cliente, c.ident_cliente, c.telefonos_cliente, p.id_sucursal                                                                           
                                FROM sriguiaremisionencab g 
                                INNER JOIN sriguiaremisiondestino d on d.idguia = g.idguia
                                INNER JOIN punto_emision p on p.id_puntoemision = g.id_puntoemision
                                INNER JOIN sritransportista t on t.idtransportista = g.idtransportista
                                INNER JOIN clientes c on c.id_cliente = d.iddestinatario
                                WHERE g.idguia = $id");
      $resu = $sql->result();
      if ($resu)
        return $resu[0];
      else  
        return null;
    }
    
    public function sel_nroguia_ptoemi($idpunto){
      $sql = $this->db->query("SELECT consecutivo_guiaremision FROM punto_emision
                                 WHERE id_puntoemision = $idpunto");
      $resu = $sql->result();
      if ($resu != null)
        $nroguia = str_pad($resu[0]->consecutivo_guiaremision,9,"0",STR_PAD_LEFT);
      else  
        $nroguia =  str_pad("1",9,"0",STR_PAD_LEFT);
      return $nroguia; 
    }
      
    public function lst_transportista(){
        $sql = $this->db->query("SELECT idtransportista, razonsocial FROM sritransportista");
        $resu = $sql->result();
        return $resu;
      }
  
    public function monto_rango($desde, $hasta){
      $sql = $this->db->query("SELECT SUM(total) AS total FROM guiaremision 
                                 WHERE fecha BETWEEN '$desde' AND '$hasta' AND estatus = 1");
      $total = 0;
      $resu = $sql->result();
      if ($resu) { $total = $resu[0]->total; }
      return $total;
    }

    /* MOSTRAR PRODUCTOS DE LA TABLA TEMPORAL */
    public function lst_guiatmp_detalle($idusu){
        $sql_sel = $this->db->query("SELECT iddetalle, id_producto, codigo, descripcion, cantidad 
                                       FROM tmp_guiaremisionproducto t 
                                       WHERE id_usuario = $idusu 
                                       ORDER BY iddetalle ASC");
        $result = $sql_sel->result();
        return $result;
    }

    public function lst_guia_detalle($idguia){
        $sql_sel = $this->db->query("SELECT iddetalle, idproducto, codigointerno, codigoadicional, descripcion, cantidad 
                                       FROM sriguiaremisionproducto p
                                       INNER JOIN sriguiaremisiondestino d on d.iddestino = p.iddestino
                                       WHERE d.idguia = $idguia 
                                       ORDER BY iddetalle ASC");
        $result = $sql_sel->result();
        return $result;
    }
  
      public function tipo_comprobventa(){
        $sql = $this->db->query("SELECT id_sri_tipo_doc, desc_sri_tipo_doc, cod_sri_tipo_doc 
                                   FROM sri_tipo_doc WHERE id_sri_tipo_doc in (1)");
        $resu = $sql->result();
        return $resu;
      }
  


    /* CREAR ID PARA TABLA TEMPORAL  */
    public function ini_temp($idguia, $idusu){
      $this->db->query("DELETE FROM tmp_guiaremisionproducto WHERE id_usuario = $idusu");
      $sql = $this->db->query("INSERT INTO tmp_guiaremisionproducto (id_usuario, id_producto, codigo, descripcion, cantidad)
                                 SELECT $idusu, idproducto, codigointerno, descripcion, cantidad 
                                   FROM sriguiaremisionproducto p
                                   INNER JOIN sriguiaremisiondestino d on d.iddestino = p.iddestino 
                                   WHERE idguia = $idguia");
    }

    public function lst_factura_cliente($idcliente){
      $sql = $this->db->query("SELECT v.id_venta, v.fecha, v.nro_factura, v.montototal 
                                FROM venta v WHERE v.id_cliente = $idcliente AND v.estatus = 1 
                                ORDER BY v.fecha DESC, v.nro_factura");
      $resultado = $sql->result();
      return $resultado;
    }

    public function upd_docmodificado($idusu, $iddoc){
      $sql = $this->db->query("SELECT v.id_venta, v.fecha, v.nro_factura FROM venta v WHERE v.id_venta = $iddoc");
      $resultado = $sql->result();
      if ($resultado){
        $strnro = $resultado[0]->nro_factura;
        $tmpfecha = $resultado[0]->fecha;
        $this->db->query("UPDATE guiaremision_tmp 
                            SET id_docmodificado = $iddoc,
                                nro_docmodificado = '$strnro', 
                                fecha_docmodificado = '$tmpfecha' 
                            WHERE id_usu = $idusu");

        $this->db->query("DELETE FROM guiaremision_detalle_tmp 
                            WHERE id_guiaremision = (SELECT id FROM guiaremision_tmp WHERE id_usu = $idusu)");

        $this->db->query("INSERT INTO guiaremision_detalle_tmp (id_guiaremision, id_producto, cantidad, precio, gravaiva,
                                                               subtotal, descuento, montoiva, descsubtotal)
                           SELECT (SELECT id FROM guiaremision_tmp WHERE id_usu = $idusu),
                                  id_producto, cantidad, precio, iva, subtotal, descmonto, montoiva, descsubtotal
                             FROM venta_detalle
                             WHERE id_venta = $iddoc");

        $myres = $resultado[0];
      } else {
        $myres = NULL;
      } 
      $this->actualiza_tmptotales($idusu);
      return $myres;
    }

    /* OBTENER LISTADO DE PRODUCTOS PARA LA COMPRA */
    public function lst_productoguia(){
      $sql = $this->db->query(" SELECT pro.pro_id, pro.pro_codigobarra, pro.pro_codigoauxiliar, pro.pro_nombre, 
                                      pro.pro_precioventa
                                FROM producto pro 
                                WHERE pro_apliventa=1
                                ORDER BY pro.pro_nombre");

      $result = $sql->result();
      return $result;      
    }

    public function ins_producto($idusu, $idpro){
      $this->db->query("INSERT INTO tmp_guiaremisionproducto (id_usuario, id_producto, cantidad, codigo, descripcion)
                           SELECT $idusu, pro_id, 1, pro_codigoauxiliar, pro_nombre
                             FROM producto
                             WHERE pro_id = $idpro");
    }

    public function upd_guiadetalle($iddetalle, $cantidad){
        $this->db->query("UPDATE tmp_guiaremisionproducto 
                            SET cantidad = $cantidad
                            WHERE iddetalle = $iddetalle");
    }


    public function del_detalle($iddetalle){
        $this->db->query("DELETE FROM tmp_guiaremisionproducto WHERE iddetalle = $iddetalle");
    }


    public function ins_guiaremision($idusu, $fechaemision, $dirpartida, $idtransportista, $fechaini, $fechafin, 
                                     $placa, $puntoemision, $iddestinatario, $motivo, $docaduanero,
                                     $codestabdestino, $ruta, $coddocsustento, $numdocsustento, $numautdocsustento,
                                     $fechaemidocsustento, $dirllegada){
      $secuencial = $this->sel_nroguia_ptoemi($puntoemision);                                       
      $this->db->query("INSERT INTO sriguiaremisionencab (fechaemision, dirpartida, idtransportista,
                                                          fechaini, fechafin, placa, secuencial, id_puntoemision)
                          VALUES('$fechaemision', '$dirpartida', $idtransportista, '$fechaini', '$fechafin', 
                                 '$placa', '$secuencial', $puntoemision)");

      $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM sriguiaremisionencab");
      $varid = $sqlid->result();
      $idcomp = $varid[0]->id;

      $this->db->query("INSERT INTO sriguiaremisiondestino (idguia, iddestinatario, motivo, docaduanero, 
                                                            codestabdestino, ruta, coddocsustento, numdocsustento,
                                                            numautdocsustento, fechaemidocsustento, dirllegada)
                          VALUES($idcomp, $iddestinatario, '$motivo', '$docaduanero', '$codestabdestino', '$ruta', 
                                 '$coddocsustento', '$numdocsustento', '$numautdocsustento', '$fechaemidocsustento', 
                                 '$dirllegada')");

      $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM sriguiaremisiondestino");
      $varid = $sqlid->result();
      $iddestino = $varid[0]->id;

      $this->db->query("INSERT INTO sriguiaremisionproducto (iddestino, idproducto, codigointerno, codigoadicional,
                                                             descripcion, cantidad)
                          SELECT $iddestino, id_producto, codigo, codigo, descripcion, cantidad
                            FROM tmp_guiaremisionproducto WHERE id_usuario = $idusu");

      $del_comp = $this->db->query("DELETE FROM tmp_guiaremisionproducto WHERE id_usuario = $idusu");

      $this->db->query("UPDATE punto_emision SET consecutivo_guiaremision = consecutivo_guiaremision + 1 
                          WHERE id_puntoemision = $puntoemision");

      return $idcomp;
    }

    public function upd_guiaremision($idusu, $id, $secuencial, $fechaemision, $dirpartida, $idtransportista, 
                                     $fechaini, $fechafin, 
                                     $placa, $puntoemision, $iddestinatario, $motivo, $docaduanero,
                                     $codestabdestino, $ruta, $coddocsustento, $numdocsustento, $numautdocsustento,
                                     $fechaemidocsustento, $dirllegada){
      $sqlobj = $this->db->query("SELECT count(*) as cant FROM sriguiaremisionencab 
                                    WHERE idguia = $id AND id_puntoemision = $puntoemision");
      $result = $sqlobj->result();
      $mismopuntoemision = $result[0]->cant;
      if ($mismopuntoemision == 0){ //cambio punto de emision
        $secuencial = $this->sel_nroguia_ptoemi($puntoemision);                                       
      } 
      $this->db->query("UPDATE sriguiaremisionencab SET
                          fechaemision = '$fechaemision', 
                          dirpartida = '$dirpartida', 
                          idtransportista = $idtransportista,
                          fechaini = '$fechaini', 
                          fechafin = '$fechafin', 
                          placa = '$placa', 
                          secuencial = '$secuencial', 
                          id_puntoemision = $puntoemision
                        WHERE idguia = $id");

      $this->db->query("DELETE FROM sriguiaremisiondestino WHERE idguia = $id");

      $this->db->query("INSERT INTO sriguiaremisiondestino (idguia, iddestinatario, motivo, docaduanero, 
                                                            codestabdestino, ruta, coddocsustento, numdocsustento,
                                                            numautdocsustento, fechaemidocsustento, dirllegada)
                          VALUES($id, $iddestinatario, '$motivo', '$docaduanero', '$codestabdestino', '$ruta', 
                                 '$coddocsustento', '$numdocsustento', '$numautdocsustento', '$fechaemidocsustento', 
                                 '$dirllegada')");

      $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM sriguiaremisiondestino");
      $varid = $sqlid->result();
      $iddestino = $varid[0]->id;

      $this->db->query("DELETE FROM sriguiaremisionproducto 
                          WHERE iddestino = (SELECT iddestino FROM sriguiaremisiondestino WHERE idguia = $id LIMIT 1)");

      $this->db->query("INSERT INTO sriguiaremisionproducto (iddestino, idproducto, codigointerno, codigoadicional,
                                                             descripcion, cantidad)
                          SELECT $iddestino, id_producto, codigo, codigo, descripcion, cantidad
                            FROM tmp_guiaremisionproducto WHERE id_usuario = $idusu");

      $this->db->query("DELETE FROM tmp_guiaremisionproducto WHERE id_usuario = $idusu");

      if ($mismopuntoemision == 0){ //cambio punto de emision
        $this->db->query("UPDATE punto_emision SET consecutivo_guiaremision = consecutivo_guiaremision + 1 
                            WHERE id_puntoemision = $puntoemision");
      }                          
    }

    public function del_guiaremision($id){
      $this->db->query("DELETE FROM sriguiaremisionproducto 
                          WHERE iddestino = (SELECT iddestino FROM sriguiaremisiondestino WHERE idguia = $id LIMIT 1)");

      $this->db->query("DELETE FROM sriguiaremisiondestino WHERE idguia = $id");

      $this->db->query("DELETE FROM sriguiaremisionencab WHERE idguia = $id");
    }

}