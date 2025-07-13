<?php

/* ------------------------------------------------
  ARCHIVO: Notacredito_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Nota de credito.
  FECHA DE CREACIÓN: 15/08/2017
 * 
  ------------------------------------------------ */

class Notacredito_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $query = $this->db->query("SET time_zone = '-5:00';");
    }


   /* VENTAS TOTALES POR RANGO */
    public function lst_notacredito($desde, $hasta){
      $sql = $this->db->query(" SELECT n.id, n.fecha, 
                                       concat(cod_establecimiento,'-',cod_puntoemision,'-',n.nro_documento) as nro_documento, 
                                       n.id_cliente, c.nom_cliente, c.ident_cliente, 
                                       n.fecharegistro, (ifnull(n.subtotalnoiva,0)+ifnull(n.subtotaliva,0)) as subtotal, 
                                       n.descuento, n.montoiva, n.total, n.estatus 
                                FROM notacredito n 
                                INNER JOIN clientes c ON c.id_cliente = n.id_cliente
                                INNER JOIN punto_emision p on p.id_puntoemision = n.id_puntoemision
                                WHERE n.fecha BETWEEN '$desde' AND '$hasta' 
                                ORDER BY n.fecha desc,nro_documento");
      $resu = $sql->result();
      return $resu;
    }

    public function monto_rango($desde, $hasta){
      $sql = $this->db->query("SELECT SUM(total) AS total FROM notacredito 
                                 WHERE fecha BETWEEN '$desde' AND '$hasta' AND estatus = 1");
      $total = 0;
      $resu = $sql->result();
      if ($resu) { $total = $resu[0]->total; }
      return $total;
    }

    /* CREAR ID PARA TABLA TEMPORAL  */
    public function ini_temp($idusu){
      date_default_timezone_set("America/Guayaquil");
      $verifica = $this->db->query("SELECT COUNT(*) AS valor FROM notacredito_tmp WHERE id_usu = $idusu");
      $valver = $verifica->result();
      $valor = $valver[0]->valor;
      if($valor == 0){
        $this->db->query("INSERT INTO notacredito_tmp (id_usu, fecha, id_sucursal)
                            SELECT $idusu, date(now()), (SELECT id_sucursal FROM sucursal ORDER BY nom_sucursal LIMIT 1);");
      }
      $sql = $this->db->query("SELECT id, id_usu, id_cliente, fecha, 
                                      LPAD(IFNULL((SELECT consecutivo_notacredito FROM punto_emision WHERE id_puntoemision = t.id_puntoemision),''),9,'0') as nro_documento, 
                                      tipodocmodificado,id_puntoemision,
                                      id_docmodificado, nro_docmodificado, fecha_docmodificado, motivo, subtotalnoiva, subtotaliva, 
                                      descsubtotalnoiva, descsubtotaliva, descuento, montoiva, total, id_sucursal, id_almacen
                                  FROM notacredito_tmp t WHERE id_usu = $idusu");
      $resultado = $sql->result();
      return $resultado[0];
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
        $this->db->query("UPDATE notacredito_tmp 
                            SET id_docmodificado = $iddoc,
                                nro_docmodificado = '$strnro', 
                                fecha_docmodificado = '$tmpfecha' 
                            WHERE id_usu = $idusu");

        $this->db->query("DELETE FROM notacredito_detalle_tmp 
                            WHERE id_notacredito = (SELECT id FROM notacredito_tmp WHERE id_usu = $idusu)");

        $this->db->query("INSERT INTO notacredito_detalle_tmp (id_notacredito, id_producto, cantidad, precio, gravaiva,
                                                               subtotal, descuento, montoiva, descsubtotal)
                           SELECT (SELECT id FROM notacredito_tmp WHERE id_usu = $idusu),
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
    public function lst_productonota(){
      $sql = $this->db->query(" SELECT pro.pro_id, pro.pro_codigobarra, pro.pro_codigoauxiliar, pro.pro_nombre, 
                                      pro.pro_precioventa
                                FROM producto pro 
                                WHERE pro_apliventa=1
                                ORDER BY pro.pro_nombre");

      $result = $sql->result();
      return $result;      
    }

    public function ins_producto($idusu, $idpro){
      $this->db->query("INSERT INTO notacredito_detalle_tmp (id_notacredito, id_producto, cantidad, precio, gravaiva,
                                                             subtotal, descuento, montoiva, descsubtotal)
                           SELECT (SELECT id FROM notacredito_tmp WHERE id_usu = $idusu),
                                  pro_id, 1, pro_precioventa, pro_grabaiva, pro_precioventa, 0, 
                                  round(pro_precioventa * IFNULL((SELECT valor FROM parametros WHERE id = 1),0.12),2), 
                                  pro_precioventa
                             FROM producto
                             WHERE pro_id = $idpro");
      $this->actualiza_tmptotales($idusu);
    }

    /* MOSTRAR PRODUCTOS DE LA TABLA TEMPORAL */
    public function lst_notatmp_detalle($idusu){
      $sql_sel = $this->db->query("SELECT pro.pro_id, pro.pro_codigobarra, pro.pro_codigoauxiliar, pro.pro_nombre, tcd.id_notacredito,
                                           tcd.precio, tcd.cantidad, tcd.gravaiva, tcd.montoiva, tcd.subtotal,
                                           tcd.descuento, tcd.descsubtotal, tcd.id
                                    FROM notacredito_detalle_tmp tcd
                                    INNER JOIN notacredito_tmp c ON c.id = tcd.id_notacredito
                                    INNER JOIN producto pro ON pro.pro_id = tcd.id_producto
                                    WHERE c.id_usu = $idusu ORDER BY tcd.id ASC");
      $result = $sql_sel->result();
      return $result;
    }

    public function upd_datosnota($idusu, $sucursal, $almacen, $cliente, $fechanota, $puntoemision, $nronota, $iddocmod, $nrodocmod, $fechadocmod, $motivo){
        $this->db->query("UPDATE notacredito_tmp 
                            SET id_sucursal = $sucursal,
                                id_almacen = $almacen,
                                id_cliente = $cliente,
                                fecha = '$fechanota',
                                id_puntoemision = $puntoemision,
                                nro_documento = LPAD(IFNULL((SELECT consecutivo_notacredito FROM punto_emision WHERE id_puntoemision = $puntoemision),1),9,'0'),
                                id_docmodificado = $iddocmod,
                                nro_docmodificado = '$nrodocmod', 
                                fecha_docmodificado = '$fechadocmod',
                                motivo = '$motivo'
                            WHERE id_usu = $idusu");
    }

    public function upd_notadetalle($iddetalle, $precio, $cantidad){
        $this->db->query("UPDATE notacredito_detalle_tmp 
                            SET precio = $precio,
                                cantidad = $cantidad,
                                subtotal = round($precio * $cantidad, 2),
                                descsubtotal = round($precio * $cantidad, 2),
                                montoiva = case gravaiva when 0 then 0 else
                                             round($precio * $cantidad * IFNULL((SELECT valor FROM parametros WHERE id = 1),0.12),2)
                                           end
                            WHERE id = $iddetalle");
        $usua = $this->session->userdata('usua');
        $idusu = $usua->id_usu;
        $this->actualiza_tmptotales($idusu);
    }

    /* PROCESO DE DESCUENTO EN TABLA TEMPORAL DE PRODUCTOS */
    public function actualiza_descuento($idusu, $descuento){
      $subtotal = 0;
      $descsubtotal = 0;
      $iva = 0.12;
      $sqlsub = $this->db->query("SELECT valor FROM parametros WHERE id = 1");
      $resub = $sqlsub->result();
      if ($resub) { $iva = $resub[0]->valor; }
      $sql = $this->db->query("UPDATE notacredito_tmp SET descuento = $descuento WHERE id_usu = $idusu");
      /* Se obtiene el subtotal de la compra */
      $sqlsub = $this->db->query("SELECT SUM(subtotal) as subtotal FROM notacredito_detalle_tmp 
                                    WHERE id_notacredito = (SELECT id FROM notacredito_tmp WHERE id_usu = $idusu)");
      $resub = $sqlsub->result();
      $subtotal = $resub[0]->subtotal;

      /* Se sonsultan los registros de la compra para actualizar los descuentos */
      $sqlcomp = $this->db->query("SELECT id, gravaiva, subtotal FROM notacredito_detalle_tmp 
                                     WHERE id_notacredito = (SELECT id FROM notacredito_tmp WHERE id_usu = $idusu)");
      $rescomp = $sqlcomp->result();

      foreach ($rescomp as $cp) {
        $prosub = $cp->subtotal;
        $descmonto =  $prosub / $subtotal * $descuento;
        $descsubtotal = $prosub - $descmonto;

        if($cp->gravaiva == 1){
          $valiva = round($descsubtotal * $iva,2);
        //  $subiva = $descsubtotal + $valiva;
        }else{
          $valiva = 0;
        }

        $updesc = $this->db->query("UPDATE notacredito_detalle_tmp 
                                      SET descuento = $descmonto, 
                                          descsubtotal = $descsubtotal,
                                          montoiva = $valiva
                                    WHERE id = $cp->id");
      }
      $this->actualiza_tmptotales($idusu);
    }

    public function del_detalle($iddetalle){
        $this->db->query("DELETE FROM notacredito_detalle_tmp WHERE id = $iddetalle");
        $usua = $this->session->userdata('usua');
        $idusu = $usua->id_usu;
        $this->actualiza_tmptotales($idusu);
    }

    public function del_productos($idusu){
        $this->db->query("DELETE FROM notacredito_detalle_tmp 
                            WHERE id_notacredito = (SELECT id FROM notacredito_tmp WHERE id_usu = $idusu)");
        $this->db->query("UPDATE notacredito_tmp 
                            SET subtotalnoiva = 0,
                                subtotaliva = 0,
                                descuento = 0,
                                montoiva = 0,
                                descsubtotalnoiva = 0,
                                descsubtotaliva = 0
                            WHERE id_usu = $idusu");
    }

    public function actualiza_tmptotales($idusu){
        $sql = $this->db->query("SELECT IFNULL(SUM(case gravaiva WHEN 0 then subtotal ELSE 0 end),0) as subsiniva, 
                                     IFNULL(SUM(case gravaiva WHEN 1 then subtotal ELSE 0 end),0) as subconiva,
                                     IFNULL(SUM(case gravaiva WHEN 0 then descsubtotal ELSE 0 end),0) as descsubsiniva, 
                                     IFNULL(SUM(case gravaiva WHEN 1 then descsubtotal ELSE 0 end),0) as descsubconiva,
                                     IFNULL(SUM(montoiva),0) as montoiva,
                                     IFNULL(SUM(descuento),0) as descuento
                                FROM notacredito_detalle_tmp 
                                WHERE id_notacredito = (SELECT id FROM notacredito_tmp WHERE id_usu = $idusu)");        
        $subsiniva = 0;
        $subconiva = 0;
        $descsubsiniva = 0;
        $descsubconiva = 0;
        $montoiva = 0;
        $descuento = 0;
        $res = $sql->result();
        if ($res){
          $subsiniva = $res[0]->subsiniva;
          $subconiva = $res[0]->subconiva;
          $descsubsiniva = $res[0]->descsubsiniva;
          $descsubconiva = $res[0]->descsubconiva;
          $montoiva = $res[0]->montoiva;
          $descuento = $res[0]->descuento;          
        }
        $this->db->query("UPDATE notacredito_tmp 
                            SET subtotalnoiva = $subsiniva,
                                subtotaliva = $subconiva,
                                descuento = $descuento,
                                montoiva = $montoiva,
                                descsubtotalnoiva = $descsubsiniva,
                                descsubtotaliva = $descsubconiva,
                                total = $descsubsiniva + $descsubconiva + $montoiva
                            WHERE id_usu = $idusu");
    }

    public function guardar_nota($idusu){
      $sql = $this->db->query("INSERT INTO notacredito (id_sucursal, id_almacen, id_cliente, id_usu, fecha, 
                                                        id_puntoemision, nro_documento, tipodocmodificado,
                                                        id_docmodificado, nro_docmodificado, fecha_docmodificado, motivo,
                                                        subtotalnoiva, subtotaliva, descuento, descsubtotalnoiva, descsubtotaliva, 
                                                        montoiva, total, estatus, fecharegistro)
                                SELECT id_sucursal, id_almacen, id_cliente, id_usu, fecha, id_puntoemision, nro_documento, 1,
                                       id_docmodificado, nro_docmodificado, fecha_docmodificado, motivo,
                                       subtotalnoiva, subtotaliva, descuento, descsubtotalnoiva, descsubtotaliva,
                                       montoiva, total, 1, now()
                                  FROM notacredito_tmp t WHERE id_usu = $idusu");

      $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM notacredito");
      $varid = $sqlid->result();
      $idcomp = $varid[0]->id;

      $det = $this->db->query("INSERT INTO notacredito_detalle (id_notacredito, id_producto, cantidad, precio, 
                                                           gravaiva,subtotal, descuento, montoiva, descsubtotal)
                                  SELECT $idcomp, id_producto, cantidad, precio, gravaiva,
                                         subtotal, descuento, montoiva, descsubtotal
                                    FROM notacredito_detalle_tmp
                                    WHERE id_notacredito = (SELECT id FROM notacredito_tmp WHERE id_usu = $idusu)");

      $upd_cant = $this->db->query("UPDATE almapro 
                                      SET existencia = almapro.existencia +
                                                       (SELECT sum(cantidad) FROM notacredito_detalle_tmp c 
                                                         WHERE id_notacredito = (SELECT id FROM notacredito_tmp WHERE id_usu = $idusu) 
                                                           AND c.id_producto = almapro.id_pro)
                                    WHERE id_alm = (SELECT id_almacen FROM notacredito_tmp WHERE id_usu = $idusu) 
                                      and id_pro IN (SELECT distinct id_producto FROM notacredito_detalle_tmp c 
                                                                           WHERE id_notacredito = (SELECT id FROM notacredito_tmp WHERE id_usu = $idusu))");     

      $this->db->query("INSERT INTO notacredito_impuesto (id_detallenotacredito, codigotipoimpuesto, 
                                                          codigoporcentaje, tarifa, baseimponible, valor)
                          SELECT id, 2 as codigotipoimpuesto, 
                                 case gravaiva when 0 then 0 else 2 end as codigoporcentaje,  
                                 case gravaiva when 0 then 0 else IFNULL((SELECT valor FROM parametros WHERE id = 1)*100,12) end as tarifa,  
                                 descsubtotal, montoiva 
                            FROM notacredito_detalle
                            WHERE id_notacredito = $idcomp");

      $this->db->query("DELETE FROM notacredito_detalle_tmp WHERE id_notacredito = (SELECT id FROM notacredito_tmp WHERE id_usu = $idusu)");
      $this->db->query("DELETE FROM notacredito_tmp WHERE id_usu = $idusu");

      $this->db->query("UPDATE punto_emision SET consecutivo_notacredito = consecutivo_notacredito + 1 
                          WHERE id_puntoemision = (SELECT id_puntoemision FROM notacredito WHERE id = $idcomp)");


      return $idcomp;
    }

    /* CREAR ID PARA TABLA TEMPORAL  */
    public function get_nota_id($id){
      date_default_timezone_set("America/Guayaquil");
      $sql = $this->db->query("SELECT t.id, id_usu, t.id_cliente, fecha, 
                                      c.nom_cliente, c.ident_cliente, c.direccion_cliente, c.telefonos_cliente,
                                      concat(cod_establecimiento,'-',cod_puntoemision) as puntoemision, 
                                      nro_documento, tipodocmodificado,t.id_puntoemision,
                                      id_docmodificado, nro_docmodificado, fecha_docmodificado, motivo, subtotalnoiva, subtotaliva, 
                                      descsubtotalnoiva, descsubtotaliva, descuento, montoiva, total, t.id_sucursal, id_almacen, 
                                      id_usu, estatus
                                  FROM notacredito t 
                                  INNER JOIN clientes c on c.id_cliente = t.id_cliente
                                  INNER JOIN punto_emision p on p.id_puntoemision = t.id_puntoemision
                                  WHERE t.id = $id");
      $resultado = $sql->result();
      return $resultado[0];
    }
 
    public function lst_nota_detalle($id){
      $sql_sel = $this->db->query("SELECT pro.pro_id, pro.pro_codigobarra, pro.pro_codigoauxiliar, pro.pro_nombre, tcd.id_notacredito,
                                          tcd.precio, tcd.cantidad, tcd.gravaiva, tcd.montoiva, tcd.subtotal,
                                          tcd.descuento, tcd.descsubtotal, tcd.id
                                    FROM notacredito_detalle tcd
                                    INNER JOIN notacredito c ON c.id = tcd.id_notacredito
                                    INNER JOIN producto pro ON pro.pro_id = tcd.id_producto
                                    WHERE c.id = $id ORDER BY tcd.id ASC");
      $result = $sql_sel->result();
      return $result;
    }

    public function anular_nota($id){
        $this->db->query("UPDATE notacredito SET estatus = 3 WHERE id = $id");
    }

    public function lst_detnotaparakardex($id_nota){
      $sel_obj = $this->db->query("Select d.id_producto, p.pro_idunidadmedida, v.id_almacen,
                                          v.nro_documento, p.pro_preciocompra as precio, d.cantidad, 
                                          p.pro_preciocompra * d.cantidad as descsubtotal
                                       from notacredito_detalle d 
                                       inner join notacredito v on v.id = d.id_notacredito
                                       inner join producto p on p.pro_id = d.id_producto 
                                       WHERE p.preparado = 0 and v.id = $id_nota
                                   Union
                                   Select i.id_proing as id_producto, p.pro_idunidadmedida, v.id_almacen,
                                          v.nro_documento, p.pro_preciocompra as precio, 
                                          round(case when i.unimed = p.pro_idunidadmedida then 1
                                                      when ifnull(fd.idunidad1,0) != 0 then fd.cantidadequivalente
                                                      when ifnull(fi.idunidad1,0) != 0 then 1/fi.cantidadequivalente
                                                      else 0
                                                end * i.cantidad * d.cantidad,2) as cantidad,
                                          round(case when i.unimed = p.pro_idunidadmedida then 1
                                                      when ifnull(fd.idunidad1,0) != 0 then fd.cantidadequivalente
                                                      when ifnull(fi.idunidad1,0) != 0 then 1/fi.cantidadequivalente
                                                      else 0
                                                end * i.cantidad * d.cantidad,2) * p.pro_preciocompra as descsubtotal 
                                       from notacredito_detalle d 
                                       inner join producto p1 on p1.pro_id = d.id_producto 
                                       inner join notacredito v on v.id = d.id_notacredito
                                       inner join producto_ingrediente i on i.id_pro = d.id_producto
                                       inner join producto p on p.pro_id = i.id_proing 
                                       left join unidadfactorconversion fd on fd.idunidad1 = i.unimed and fd.idunidadequivale = p.pro_idunidadmedida 
                                       left join unidadfactorconversion fi on fi.idunidad1 = p.pro_idunidadmedida and fi.idunidadequivale = i.unimed 
                                       WHERE p1.preparado = 1 and v.id = $id_nota");
      $resultado = $sel_obj->result();
      return $resultado;
    }  

}