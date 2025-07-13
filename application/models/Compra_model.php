<?php

/* ------------------------------------------------
  ARCHIVO: Compra_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Compra.
  FECHA DE CREACIÓN: 18/08/2017
 * 
  ------------------------------------------------ */

class Compra_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* CREAR ID PARA TABLA TEMPORAL DE COMPRA */
    public function ini_temp($idusu, $eliminatmp = 0){
      if ($eliminatmp == 1){
        $this->db->query("DELETE FROM tmp_compra_det 
                            WHERE id_tmp_comp = (SELECT id_tmp_comp FROM tmp_compra WHERE id_usu = $idusu)");
        $this->db->query("DELETE FROM tmp_compra WHERE id_usu = $idusu");
      }

      date_default_timezone_set("America/Guayaquil");
      $verifica = $this->db->query("SELECT COUNT(*) AS valor FROM tmp_compra WHERE id_usu = $idusu");
      $valver = $verifica->result();
      $valor = $valver[0]->valor;
      if($valor == 0){
        $fecha = date("Y-m-d");
        $sql = $this->db->query("INSERT INTO tmp_compra (id_usu, fecha, id_sucursal)
                                   SELECT $idusu, '$fecha', (SELECT id_sucursal FROM sucursal 
                                    ORDER BY nom_sucursal LIMIT 1);");
      }
      $sql_compra = $this->db->query("SELECT id_tmp_comp, id_usu, id_proveedor, fecha, nro_factura, 
                                             nro_autorizacion, formapago, valiva, subconiva, subsiniva, 
                                             desc_monto, descsubconiva, descsubsiniva, montoiva, montototal, 
                                             id_almacen, categoria, montoice, id_sucursal,
                                             cod_sri_tipo_doc, cod_sri_sust_comprobante,
                                             doc_mod_cod_sri_tipo, doc_mod_numero, doc_mod_autorizacion
                                        FROM  tmp_compra WHERE id_usu = $idusu");
      $resultado = $sql_compra->result();
      return $resultado[0];
    }

    public function valdescmonto($idtmp_comp){
      $sql_compra = $this->db->query("SELECT desc_monto FROM  tmp_compra WHERE id_tmp_comp = $idtmp_comp");
      $resultado = $sql_compra->result();
      return $resultado[0];
    }

    /* OBTENER EL LISTADO DE LOS PROVEEDORES */
    public function lst_proveedor(){
      $sql = $this->db->query("SELECT id_proveedor, nom_proveedor FROM proveedor");
      $result = $sql->result();
      return $result;
    }

    /* OBTENER LISTADO DE PRODUCTOS PARA LA COMPRA */
    public function lst_producto($almacen = 0){
      $usu = $this->session->userdata('usua');
      $idusu = $usu->id_usu;
      $sql = $this->db->query("SELECT pro.pro_id as id_pro, pro.pro_codigobarra, pro.pro_codigoauxiliar, 
                                      REPLACE(REPLACE(pro.pro_nombre, '\r', ''), '\n', '') as pro_nombre,  
                                      pro.pro_preciocompra, 
                                      ifnull(ap.existencia,0) as existencia, 
                                     pro.pro_idunidadmedida as id_unimed, 
                                      um.descripcion, um.nombrecorto
                                FROM producto pro 
                                INNER JOIN unidadmedida um ON um.id = pro.pro_idunidadmedida 
                                LEFT JOIN almapro ap on ap.id_pro = pro.pro_id 
                                WHERE pro_aplicompra=1 and pro_esservicio=0 and ap.id_alm=$almacen");

      $result = $sql->result();
      return $result;      
    }

    /* CONTADOR DE REGISTROS DE COMPRAS */ 
    public function contcomp(){
      $sql = $this->db->query("SELECT valor FROM contador WHERE categoria = 'compra'");
      $result = $sql->result();
      return $result[0]->valor;      
    }

    /* AGREGAR PRODUCTOS A LA TABLA TEMPORAL Y MOSTRARLOS */
    public function compra_detalle($idpro, $idtmp_comp){
      $usu = $this->session->userdata('usua');
      $idusu = $usu->id_usu;
      $sql_add = $this->db->query(" INSERT INTO tmp_compra_det (id_tmp_comp, id_pro, precio_compra, existencia, cantidad, id_unimed, iva, montoiva, subtotal, descmonto, descsubtotal)
                                    SELECT $idtmp_comp, pro.pro_id, pro.pro_preciocompra, ifnull(ap.existencia,0), 0, pro.pro_idunidadmedida, pro.pro_grabaiva, 0, 0.00, 0.00, 0.00
                                      FROM producto pro
                                      LEFT JOIN almapro ap ON  ap.id_pro = pro.pro_id AND 
                                                               ap.id_alm = IFNULL((SELECT id_almacen FROM tmp_compra WHERE id_tmp_comp = $idtmp_comp),0)
                                      where pro.pro_id = $idpro");
    }

    /* MOSTRAR PRODUCTOS DE LA TABLA TEMPORAL */
    public function compra_det($idtmp_comp){
      $sql_sel = $this->db->query(" SELECT pro.pro_id, pro.pro_codigobarra, pro.pro_codigoauxiliar, pro.pro_nombre, tcd.id_tmp_comp,
                                           tcd.precio_compra, 
                                           ifnull(ap.existencia,0) as existencia, 
                                           tcd.cantidad, tcd.id_unimed, tcd.iva, tcd.montoiva, tcd.subtotal,
                                           tcd.descmonto, tcd.descsubtotal, tcd.id,
                                           (SELECT count(*) FROM producto_serie_tmp 
                                              WHERE id_detallecompra = tcd.id) as cantidadserie
                                    FROM tmp_compra_det tcd
                                    INNER JOIN tmp_compra c ON c.id_tmp_comp = tcd.id_tmp_comp
                                    INNER JOIN producto pro ON pro.pro_id = tcd.id_pro
                                    LEFT JOIN almapro ap ON tcd.id_pro = ap.id_pro and ap.id_alm = c.id_almacen
                                    WHERE tcd.id_tmp_comp = $idtmp_comp ORDER BY tcd.id ASC");
      $result = $sql_sel->result();
      return $result;
    }

    /* ACTUALIZA EL PRECIO EN LA TABLA TEMPORAL */
    public function upd_preciocompra($idtmp_comp, $iddet, $precio, $montoiva, $subtotal){
      $query = $this->db->query("UPDATE tmp_compra_det 
                                    SET precio_compra = $precio, 
                                        montoiva = $montoiva, 
                                        subtotal = $subtotal,
                                        descsubtotal = $subtotal  
                                  WHERE id_tmp_comp = $idtmp_comp AND id = $iddet");
      /* ---- */
        $this->cal_desc($idtmp_comp);
      /* ---- */
      $query = $this->db->query("SELECT descsubtotal FROM tmp_compra_det WHERE id_tmp_comp = $idtmp_comp AND id = $iddet");
      $resultado = $query->result();
      return $resultado[0]->descsubtotal;
    }

    /* ACTUALIZA LA CANTIDAD EN LA TABLA TEMPORAL */
    public function upd_cantidad($idtmp_comp, $iddet, $cantidad, $montoiva, $subtotal){
      $query = $this->db->query("UPDATE tmp_compra_det SET cantidad = $cantidad, 
                                                           montoiva = $montoiva, 
                                                           subtotal = $subtotal, 
                                                           descsubtotal = $subtotal
                                                     WHERE id_tmp_comp = $idtmp_comp AND id = $iddet");
      $sql_total = $this->db->query("SELECT (SELECT SUM(subtotal) as subtotaliva FROM tmp_compra_det WHERE iva = 1 AND id_tmp_comp = $idtmp_comp) AS subconiva,
                                            (SELECT SUM(subtotal) as subtotaliva FROM tmp_compra_det WHERE iva = 0 AND id_tmp_comp = $idtmp_comp) AS subsiniva,
                                             SUM(montoiva) as montoiva,
                                            (SUM(subtotal) + SUM(montoiva)) as total
                                       FROM tmp_compra_det WHERE id_tmp_comp = $idtmp_comp");
      $total = $sql_total->result();
      return $total[0];
    }

    /* ACTUALIZA LA UNIDAD MEDIDA EN LA TABLA TEMPORAL */
    public function upd_unidadmedida($idtmp_comp, $iddet, $unidadmedida){
      $query = $this->db->query("UPDATE tmp_compra_det SET id_unimed = $unidadmedida 
                                   WHERE id_tmp_comp = $idtmp_comp AND id = $iddet");
    }

    /* ACTUALIZA EL IVA EN LA TABLA TEMPORAL */
    public function upd_iva($idtmp_comp, $iddet, $esiva, $montoiva, $subtotal){
      $query = $this->db->query("UPDATE tmp_compra_det SET iva = $esiva, 
                                                           montoiva = $montoiva, 
                                                           subtotal = $subtotal, 
                                                           descsubtotal = $subtotal 
                                                     WHERE id_tmp_comp = $idtmp_comp AND id = $iddet");
      $sql_total = $this->db->query("SELECT (SELECT SUM(subtotal) as subtotaliva FROM tmp_compra_det WHERE iva = 1 AND id_tmp_comp = $idtmp_comp) AS subconiva,
                                            (SELECT SUM(subtotal) as subtotaliva FROM tmp_compra_det WHERE iva = 0 AND id_tmp_comp = $idtmp_comp) AS subsiniva,
                                             SUM(montoiva) as montoiva,
                                            (SUM(subtotal) + SUM(montoiva)) as total
                                       FROM tmp_compra_det WHERE id_tmp_comp = $idtmp_comp");
      $total = $sql_total->result();
      return $total[0];
    }

    /* ELIMINA EL PRODUCTO DE LA TABLA TEMPORAL DE COMPRA */
    public function quitar_procompra($idtmp_comp, $iddet){
      $query = $this->db->query("DELETE FROM tmp_compra_det WHERE id_tmp_comp = $idtmp_comp AND id = $iddet");
    }

    /* ACTUALIZAR LOS MONTOS DESDE AGREGAR O ELIMINAR PRODUCTOS EN LA COMPRA */
    public function actualiza_montos($idtmp_comp){
      $sql_total = $this->db->query("SELECT (SELECT SUM(subtotal) FROM tmp_compra_det WHERE iva = 1 AND id_tmp_comp = $idtmp_comp) AS subconiva,
                                            (SELECT SUM(subtotal) FROM tmp_compra_det WHERE iva = 0 AND id_tmp_comp = $idtmp_comp) AS subsiniva,
                                             SUM(montoiva) as montoiva,
                                            (SUM(subtotal) + SUM(montoiva)) as total,
                                            (SELECT SUM(descsubtotal) FROM tmp_compra_det WHERE iva = 1 AND id_tmp_comp = $idtmp_comp) AS descsubconiva,
                                            (SELECT SUM(descsubtotal) FROM tmp_compra_det WHERE iva = 0 AND id_tmp_comp = $idtmp_comp) AS descsubsiniva,
                                            (SELECT SUM(
                                                (SELECT SUM(descsubtotal) FROM tmp_compra_det WHERE iva = 1 AND id_tmp_comp = $idtmp_comp) +
                                                (SELECT SUM(descsubtotal) FROM tmp_compra_det WHERE iva = 0 AND id_tmp_comp = $idtmp_comp)
                                                        )) AS totaldesc,
                                            IFNULL((SELECT montoice FROM tmp_compra WHERE id_tmp_comp = $idtmp_comp),0) AS montoice,
                                            (SUM(descsubtotal) + SUM(montoiva)) as ttotal 
                                       FROM tmp_compra_det WHERE id_tmp_comp = $idtmp_comp");
      $total = $sql_total->result();
      /* variables para actualizar tmp_compra */
      $subconiva = $total[0]->subconiva;
      if($subconiva == ""){$subconiva = 0;}
      $subsiniva = $total[0]->subsiniva;
      if($subsiniva == ""){$subsiniva = 0;}
      $descsubconiva = $total[0]->descsubconiva;
      if($descsubconiva == ""){$descsubconiva = 0;}
      $descsubsiniva = $total[0]->descsubsiniva;
      if($descsubsiniva == ""){$descsubsiniva = 0;}
      $montoiva = $total[0]->montoiva;
      if($montoiva == ""){$montoiva = 0;}
      $montototal = $total[0]->ttotal;
      if($montototal == ""){$montototal = 0;}

      $montoice = $total[0]->montoice;

      /* Se Obtiene el valor del iva */
      $sqliva = $this->db->query("SELECT valor FROM parametros WHERE id = 1");
      $valor = $sqliva->result();
      $monto_iva = $valor[0]->valor; 

      $montoiva += round($montoice * $monto_iva,2);

      $montototal = $descsubconiva + $descsubsiniva + $montoice + $montoiva;

      /* Actualizar la tabla tmp_compra con los montos */
      $sql_updcompra = $this->db->query("UPDATE tmp_compra SET  valiva = $monto_iva,
                                                                subconiva = $subconiva,
                                                                subsiniva = $subsiniva,
                                                                descsubconiva = $descsubconiva,
                                                                descsubsiniva = $descsubsiniva,
                                                                montoiva = $montoiva,
                                                                montototal = $montototal
                                         WHERE id_tmp_comp = $idtmp_comp");

      $sql_total = $this->db->query("SELECT subconiva,
                                            subsiniva,
                                            montoiva,
                                            (subconiva + subsiniva + montoiva) as total,
                                            descsubconiva,
                                            descsubsiniva,
                                            (subconiva + subsiniva) AS totaldesc,
                                            montoice,
                                            montototal as ttotal 
                                       FROM tmp_compra WHERE id_tmp_comp = $idtmp_comp");
      $total = $sql_total->result();
      return $total[0];
    }

    /* PROCESO DE DESCUENTO EN TABLA TEMPORAL DE PRODUCTOS */
    public function descuento($idtmp_comp, $desc, $montoice){
      $subtotal = 0;
      $descmonto = 0;
      $descsubtotal = 0;
      $iva = 0.12;
      $desc = str_replace(",", ".", $desc);
      //$desc = number_format(@$total,2,",",".")
      /* Se escribe el monto del descuento en la tabla tmp_compra*/
      $sql = $this->db->query("UPDATE tmp_compra SET desc_monto = $desc, montoice = $montoice 
                                 WHERE id_tmp_comp = $idtmp_comp");
      /* Se obtiene el subtotal de la compra */
      $sqlsub = $this->db->query("SELECT SUM(subtotal) as subtotal FROM tmp_compra_det WHERE id_tmp_comp = $idtmp_comp");
      $resub = $sqlsub->result();
      $subtotal = $resub[0]->subtotal;

      /* Se sonsultan los registros de la compra para actualizar los descuentos */
      $sqlcomp = $this->db->query("SELECT id, id_pro, precio_compra, cantidad, iva, montoiva, subtotal, descmonto, descsubtotal FROM tmp_compra_det WHERE id_tmp_comp = $idtmp_comp");
      $rescomp = $sqlcomp->result();

      foreach ($rescomp as $cp) {
        $prosub = $cp->subtotal;
        $descmonto =  $prosub / $subtotal * $desc;
        $descsubtotal = $prosub - $descmonto;

        if($cp->iva == 1){
          $valiva = $descsubtotal * $iva;
        //  $subiva = $descsubtotal + $valiva;
        }else{
          $valiva = 0;
        }

        $updesc = $this->db->query("UPDATE tmp_compra_det SET descmonto = $descmonto, 
                                                              descsubtotal = $descsubtotal,
                                                              montoiva = $valiva
                                    WHERE id_tmp_comp = $idtmp_comp AND id = $cp->id");

      }


    }

    /* BORRAR TODOS LOS DATOS DE LA TABLA TEMPORAL DE DETALLES DE COMPRA 
    public function borra_tmp_compra_detalle(){
      $sqldel = $this->db->query("DELETE FROM tmp_compra_det WHERE id_tmp_comp = ") ;
    }*/

    /* CALCULO PARA APLICAR DESCUENTO */
    public function cal_desc($idtmp_comp){
      $subtotal = 0;
      $descmonto = 0;
      $descsubtotal = 0;
      $iva = 0.12;
      $sqlmdesc = $this->db->query("SELECT desc_monto AS desmonto FROM tmp_compra WHERE id_tmp_comp = $idtmp_comp");
      $vardesc = $sqlmdesc->result();
      $desc = $vardesc[0]->desmonto;
      /* Se Evalua si el monto del descuento existe y es mayor que 0 */
      if($desc > 0){
        /* Se obtiene el subtotal de la compra */
        $sqlsub = $this->db->query("SELECT SUM(subtotal) as subtotal FROM tmp_compra_det WHERE id_tmp_comp = $idtmp_comp");
        $resub = $sqlsub->result();
        $subtotal = $resub[0]->subtotal;
        /* Se sonsultan los registros de la compra para actualizar los descuentos */
        $sqlcomp = $this->db->query("SELECT id, id_pro, precio_compra, cantidad, iva, montoiva, subtotal, descmonto, descsubtotal FROM tmp_compra_det WHERE id_tmp_comp = $idtmp_comp");
        $rescomp = $sqlcomp->result();

        foreach ($rescomp as $cp) {
          $prosub = $cp->subtotal;
          $descmonto =  $prosub / $subtotal * $desc;
          $descsubtotal = $prosub - $descmonto;
          if($cp->iva == 1){
            $miva = $iva * $descsubtotal;
          }else{
            $miva = 0;
          }
          

          //$updesc = $this->db->query("UPDATE tmp_compra_det SET descmonto = $descmonto, descsubtotal = $descsubtotal WHERE id_tmp_comp = $idtmp_comp AND id_pro = $cp->id_pro");
          $updesc = $this->db->query("UPDATE tmp_compra_det 
                                      SET montoiva = $miva, 
                                          descmonto = $descmonto, 
                                          descsubtotal = $descsubtotal 
                                      WHERE id_tmp_comp = $idtmp_comp AND id = $cp->id");
        }

      }

    }

    /* ACTUALIZAR REGISTRO DEL PROVEEDOR EN LA TABLA TEMPRAL DE COMPRA */
    public function upd_proveedor($idproveedor, $idtmp_comp){
      $sqlprovee = $this->db->query(" UPDATE tmp_compra SET id_proveedor = $idproveedor WHERE id_tmp_comp = $idtmp_comp ");
    }

    public function upd_codtipodoc($codtipodoc, $idtmp_comp){
      $sqlprovee = $this->db->query(" UPDATE tmp_compra SET cod_sri_tipo_doc = '$codtipodoc' WHERE id_tmp_comp = $idtmp_comp ");
    }

    public function upd_codsustributario($codsustributario, $idtmp_comp){
      $sqlprovee = $this->db->query(" UPDATE tmp_compra SET cod_sri_sust_comprobante = '$codsustributario' WHERE id_tmp_comp = $idtmp_comp ");
    }

    /* ACTUALIZAR REGISTRO DE LA FACTURA EN LA TABLA TEMPRAL DE COMPRA */
    public function upd_factura($factura, $idtmp_comp){
      $sqlprovee = $this->db->query(" UPDATE tmp_compra SET nro_factura = '$factura' WHERE id_tmp_comp = $idtmp_comp ");
    }

    /* ACTUALIZAR REGISTRO DE LA AUTORIZACION EN LA TABLA TEMPRAL DE COMPRA */
    public function upd_autorizacion($autorizacion, $idtmp_comp){
      $sqlprovee = $this->db->query(" UPDATE tmp_compra SET nro_autorizacion = '$autorizacion' WHERE id_tmp_comp = $idtmp_comp ");
    }

    /* ACTUALIZAR REGISTRO DE LA AUTORIZACION EN LA TABLA TEMPRAL DE COMPRA */
    public function upd_formapago($formapago, $idtmp_comp){
      $sqlprovee = $this->db->query(" UPDATE tmp_compra SET formapago = '$formapago' WHERE id_tmp_comp = $idtmp_comp ");
    }

    /* ACTUALIZAR Datos Docum Modificado EN LA TABLA TEMPRAL DE COMPRA */
    public function upd_documento_modificado($idtmp_comp, $tipodoc, $numdocmod, $autodocmod){
      $this->db->query("UPDATE tmp_compra SET 
                            doc_mod_cod_sri_tipo = '$tipodoc', 
                            doc_mod_numero = '$numdocmod', 
                            doc_mod_autorizacion = '$autodocmod' 
                          WHERE id_tmp_comp = $idtmp_comp ");
    }

    /* LISTADO DE COMPRAS */
    public function lst_compra(){
      $sql = $this->db->query(" SELECT  id_comp, p.nom_proveedor,fecha, nro_factura, montototal, tc.nom_cancelacion
                                FROM compra co
                                INNER JOIN proveedor p ON p.id_proveedor = co.id_proveedor
                                INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = co.formapago");
      $resultado = $sql->result();
      return $resultado;
    }

    /* REPORTE DE COMPRAS */
    public function compra_rpt($desde, $hasta, $sucursal = 0/*, $empresa = 0*/){
      $sql = $this->db->query("SELECT  id_comp, p.nom_proveedor,fecha, nro_factura, montototal, 
                                       tc.nom_cancelacion, co.estatus, co.formapago, nom_cla, co.estatus,
                                       p.id_proveedor, p.nro_ide_proveedor, co.cod_sri_tipo_doc,
                                       (case co.estatus when 2 then 
                                          case when fecha_pago > now() then 'Pendiente' else 'Vencido' end 
                                          else e.desc_estatus 
                                        end) as estado

                                FROM compra co
                                INNER JOIN proveedor p ON p.id_proveedor = co.id_proveedor
                                INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = co.formapago
                                LEFT JOIN clasificacion f ON f.id_cla = co.categoria                                
                                LEFT JOIN estatus_documento e ON e.id_estatus = co.estatus
                                WHERE (fecha BETWEEN '$desde' AND '$hasta') AND 
                                      ($sucursal = 0 OR co.id_sucursal = $sucursal)");      
/*      $sql = $this->db->query(" SELECT  id_comp, p.nom_proveedor,fecha, nro_factura, montototal, tc.nom_cancelacion, 
                                        co.estatus, co.formapago, nom_cla, co.estatus,p.id_proveedor,p.nro_ide_proveedor,
                                        (case co.estatus when 2 then case when fecha_pago > now() then 'Pendiente' else 'Vencido' end else e.desc_estatus end) as estado
                                FROM compra co
                                INNER JOIN proveedor p ON p.id_proveedor = co.id_proveedor
                                INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = co.formapago
                                INNER JOIN sucursal s ON s.id_sucursal = co.id_sucursal
                                LEFT JOIN clasificacion f ON f.id_cla = co.categoria                                
                                LEFT JOIN estatus_documento e ON e.id_estatus = co.estatus
                                WHERE (fecha BETWEEN '$desde' AND '$hasta') AND 
                                      ($empresa = 0 OR s.id_empresa = $empresa)");*/
      $resultado = $sql->result();
      return $resultado;
    }

    public function valiva(){
      $sql = $this->db->query("SELECT valor FROM parametros WHERE id = 1");
      $valor = $sql->result();
      return $valor[0]->valor;
    }

    public function guardar_compra($idtmp_comp, $fecha, $formapago, $efectivo, $tarjeta, $cambio, $dias, $cajachica, $categoria, $almacen){
      $this->db->query("INSERT INTO compra (id_usu, id_proveedor, fecha, nro_factura, nro_autorizacion, formapago, 
                                            valiva, subconiva, subsiniva, desc_monto, descsubconiva, descsubsiniva, 
                                            montoiva, montototal, cajachica, categoria, id_almacen,
                                            cod_sri_tipo_doc, cod_sri_sust_comprobante, montoice, id_sucursal,
                                            doc_mod_cod_sri_tipo, doc_mod_numero, doc_mod_autorizacion)
                          SELECT id_usu, id_proveedor, '$fecha', nro_factura, nro_autorizacion, formapago, 
                                 valiva, subconiva, subsiniva, desc_monto, descsubconiva, descsubsiniva, 
                                 montoiva, montototal, $cajachica, $categoria, $almacen,
                                 cod_sri_tipo_doc, cod_sri_sust_comprobante, montoice, id_sucursal,
                                 doc_mod_cod_sri_tipo, doc_mod_numero, doc_mod_autorizacion
                            FROM tmp_compra
                           WHERE id_tmp_comp =  $idtmp_comp");

      $sqlid = $this->db->query("SELECT last_insert_id() AS idcomp FROM compra");
      $varid = $sqlid->result();
      $idcomp = $varid[0]->idcomp;
    
      /* Se trata la Fecha para calcular fechas de pago cuando es Credito */
      if($dias > 0){
        $fecha_pago = strtotime ( '+'.$dias.' day' , strtotime ( $fecha ) ) ;
        $fecha_pago = date ( 'Y-m-d' , $fecha_pago );
      }else{
        $fecha_pago = $fecha;
      }

      /* Se evalua forma pago para definir variables */
      if($formapago == 'Contado'){ 
        $sqlid = $this->db->query("SELECT montototal FROM compra WHERE id_comp = $idcomp");
        $varid = $sqlid->result();
        $monto = $varid[0]->montototal;
  
        $estatus = "1"; $forpag = 1; $dias = 0;
        /* Guardar tipo de pago si es efectivo o tarjeta */    
        $this->db->query("INSERT INTO documento_pago (estado, numero, valor, observaciones) 
                            VALUES (1, '', $efectivo + $tarjeta, '')");
        $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM documento_pago");
        $varid = $sqlid->result();
        $idpago = $varid[0]->id;
        $this->db->query("INSERT INTO documento_pagodeposito (iddocumento, iddeposito) 
                            SELECT $idpago, d.id FROM compra c 
                              INNER JOIN deposito_efectivo d on d.idsucursal = c.id_sucursal
                              WHERE d.idtipo = 2 AND c.id_comp = $idcomp");
        $this->db->query("INSERT INTO compra_abonos (iddocpago, id_compra, id_formapago, monto) 
                            VALUES ($idpago, $idcomp, 1, $monto/*$efectivo*/)");
        $this->db->query("INSERT INTO compra_abonos (iddocpago, id_compra, id_formapago, monto) 
                            VALUES ($idpago, $idcomp, 2, 0/*$tarjeta*/)");        
      }
      else{ 
        $estatus = "2"; $forpag = 2; 
      }  

      /* Se actualizan los registros de forma de pago despues de añadir la compra */
      $sql_comp_upd = $this->db->query("UPDATE compra SET formapago = $forpag , estatus = $estatus, dias = $dias, fecha_pago = '$fecha_pago'
                                        WHERE id_comp = $idcomp");

 

      $det = $this->db->query("INSERT INTO compra_det (id_comp, id_pro, precio_compra, cantidad, id_unimed, iva, montoiva, subtotal, descmonto, descsubtotal, nota)
                                    SELECT $idcomp, id_pro, precio_compra, cantidad, id_unimed, iva, montoiva, subtotal, descmonto, descsubtotal, nota
                                      FROM tmp_compra_det
                                     WHERE id_tmp_comp = $idtmp_comp");

      $det = $this->db->query("INSERT INTO almapro (id_pro, id_alm, existencia, id_unimed)
                                select distinct p.pro_id, $almacen, 0, p.pro_idunidadmedida from producto p
                                  inner join tmp_compra_det d on d.id_pro = p.pro_id
                                  where d.id_tmp_comp = $idtmp_comp and
                                        not exists (select * from almapro where id_pro = pro_id and id_alm = $almacen);");

      $this->db->query("INSERT INTO producto_serie (id_producto, numeroserie, descripcion, id_detallecompra, id_almacen, id_estado) 
                          SELECT id_producto, numeroserie, descripcion, $idcomp, $almacen, 1 
                            FROM producto_serie_tmp WHERE id_detalleventa = $idtmp_comp");

      $this->db->query(" DELETE FROM producto_serie_tmp WHERE id_detalleventa = $idtmp_comp");

      $sqlid = $this->db->query("SELECT cod_sri_tipo_doc FROM compra WHERE id_comp = $idcomp");
      $varid = $sqlid->result();
      if ($varid[0]->cod_sri_tipo_doc != '04')
        $tmprazon = 1;
      else
        $tmprazon = -1;

      $this->db->query("UPDATE almapro 
                          SET existencia = almapro.existencia + $tmprazon *
                                           (SELECT round(sum(
                                              case when c.id_unimed = p.pro_idunidadmedida then 1
                                                   when ifnull(fd.idunidad1,0) != 0 then fd.cantidadequivalente
                                                   when ifnull(fi.idunidad1,0) != 0 then 1/fi.cantidadequivalente
                                                   else 0
                                              end * c.cantidad),2) 
                                             FROM tmp_compra_det c 
                                             inner join producto p on p.pro_id = c.id_pro 
                                             left join unidadfactorconversion fd on fd.idunidad1 = c.id_unimed and fd.idunidadequivale = p.pro_idunidadmedida 
                                             left join unidadfactorconversion fi on fi.idunidad1 = p.pro_idunidadmedida and fi.idunidadequivale = c.id_unimed 
                                             WHERE id_tmp_comp = $idtmp_comp AND c.id_pro = almapro.id_pro)
                        WHERE id_alm=$almacen and id_pro IN (SELECT distinct id_pro FROM tmp_compra_det c WHERE id_tmp_comp = $idtmp_comp)");

      //$sql_precio = $this->db->query("Select id_pro, precio_compra from compra_det WHERE id_comp = $idcomp");
      $sql_precio = $this->db->query("Select c.id_pro, 
                                         (case when c.id_unimed = p.pro_idunidadmedida then 1
                                              when ifnull(fd.idunidad1,0) != 0 then 1/fd.cantidadequivalente
                                              when ifnull(fi.idunidad1,0) != 0 then fi.cantidadequivalente
                                              else 0
                                          end * c.precio_compra) as precio_compra,
                                         (case when c.id_unimed = p.pro_idunidadmedida then 1
                                              when ifnull(fd.idunidad1,0) != 0 then fd.cantidadequivalente
                                              when ifnull(fi.idunidad1,0) != 0 then 1/fi.cantidadequivalente
                                              else 0
                                          end * c.cantidad) as cantidad,
                                         ifnull((select sum(existencia) from almapro where id_pro=p.pro_id),0) as existenciafinal 
                                       from compra_det c 
                                       inner join producto p on p.pro_id = c.id_pro 
                                       left join unidadfactorconversion fd on fd.idunidad1 = c.id_unimed and fd.idunidadequivale = p.pro_idunidadmedida 
                                       left join unidadfactorconversion fi on fi.idunidad1 = p.pro_idunidadmedida and fi.idunidadequivale = c.id_unimed 
                                       WHERE id_comp = $idcomp");
      $resuprecio = $sql_precio->result();

      foreach ($resuprecio as $rp) {
        $upd_precio = $this->db->query("UPDATE producto 
                                          SET pro_preciocompra = case $rp->existenciafinal when 0 then pro_preciocompra
                                                                   else round((pro_preciocompra * ($rp->existenciafinal - $rp->cantidad) + 
                                                                               $rp->precio_compra * $rp->cantidad) / $rp->existenciafinal, 6)
                                                                 end      
                                          WHERE pro_id = $rp->id_pro");
      }
      

      $del_comp = $this->db->query("DELETE FROM tmp_compra WHERE id_tmp_comp =  $idtmp_comp");
      $del_comp_det = $this->db->query("DELETE FROM tmp_compra_det WHERE id_tmp_comp = $idtmp_comp");

      return $idcomp;
    }

    public function datosproveedor($id_compra){
      $sel_obj = $this->db->query("SELECT p.id_proveedor, p.nom_proveedor, p.tip_ide_proveedor, p.nro_ide_proveedor, p.razon_social, 
                                         p.telf_proveedor, p.correo_proveedor, p.ciudad_proveedor, p.direccion_proveedor, p.relacionada
                                  FROM  compra c
                                  LEFT JOIN proveedor p ON p.id_proveedor = c.id_proveedor
                                  WHERE c.id_comp = $id_compra");
      $resultado = $sel_obj->result();
      return $resultado;
    }  

    public function compradetalle($id_compra){
      $sql_sel = $this->db->query(" SELECT d.id_pro, p.pro_nombre, d.cantidad, d.precio_compra, d.iva,
                                           d.subtotal, d.descmonto, d.descsubtotal,d.montoiva, 
                                           ifnull(c.montoice, 0) as montoice   
                                    FROM compra_det d
                                    INNER JOIN compra c ON c.id_comp = d.id_comp
                                    INNER JOIN producto p ON p.pro_id = d.id_pro
                                    WHERE d.id_comp = $id_compra ");
      $result = $sql_sel->result();
      return $result;
    }    

    public function delprocomp($idusu){
      $sql = $this->db->query("DELETE FROM tmp_compra_det WHERE id_tmp_comp = (SELECT id_tmp_comp FROM tmp_compra WHERE id_usu = $idusu)");
      $upd = $this->db->query("UPDATE tmp_compra SET desc_monto = 0 WHERE id_usu = $idusu");
    }

   /* compra TOTALES POR RANGO */
    public function compra_total_rango($desde, $hasta, $sucursal = 0){
      if ($sucursal == '') {$sucursal = 0;}
//      if ($empresa == '') {$empresa = 0;}
      $sql = $this->db->query("SELECT SUM(montototal * CASE cod_sri_tipo_doc WHEN '04' THEN -1 ELSE 1 END) AS total 
                                 FROM compra c
                                 WHERE (fecha BETWEEN '$desde' AND '$hasta' AND estatus != 3) AND
                                       ($sucursal = 0 OR c.id_sucursal = $sucursal)");
/*
      $sql = $this->db->query("SELECT SUM(montototal) AS total 
                                 FROM compra c
                                 INNER JOIN sucursal s ON s.id_sucursal = c.id_sucursal
                                 WHERE (fecha BETWEEN '$desde' AND '$hasta' AND estatus != 3) AND
                                       ($empresa = 0 OR s.id_empresa = $empresa)");
*/
      $resu = $sql->result();
      $total = $resu[0]->total;
      return $total;
      
    }

    public function categorialst(){
      $query = $this->db->query("SELECT id_cat_gas, nom_cat_gas FROM gastos_categorias");
      $result = $query->result();
      return $result;
    }

    /* AÑADIR NOTA AL PEDIDO id_ped*/
    public function updpro_nota($iddet, $nota_pro){
      $sql = $this->db->query("UPDATE tmp_compra_det SET nota = '$nota_pro' WHERE id = $iddet");
    }

    /* BUSCAR NOTA */
    public function busca_detalle_compra($iddet){
      $sql = $this->db->query("SELECT id, id_tmp_comp, id_pro FROM tmp_compra_det WHERE id = $iddet");
      $resultado = $sql->result();
      return $resultado[0];
    }

    /* BUSCAR COMPRA */
    public function busca_compra($idcompra){
      $sql = $this->db->query("SELECT co.id_comp, co.fecha, co.nro_factura, co.montototal, co.id_sucursal,
                                      p.nom_proveedor, p.nro_ide_proveedor, p.telf_proveedor, p.direccion_proveedor,
                                      tc.nom_cancelacion, co.dias, co.fecha_pago
                                FROM compra co
                                INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = co.formapago
                                LEFT JOIN proveedor p ON p.id_proveedor = co.id_proveedor
                                WHERE id_comp=$idcompra");
      $resultado = $sql->result();
      return $resultado[0];
    }

    /*  ANULAR COMPRAS  */  
    public function anular_compra($id_compra, $obs){
      $usua = $this->session->userdata('usua');
      $idusuario = $usua->id_usu;
      
      $query = $this->db->query("call compra_null($id_compra, $idusuario, '$obs');");

      $resu = $query->result();

      $query->next_result(); 
      $query->free_result();

      return $resu[0];
    }

    /* listado de detalle de compras para actualizar kardex general */
    public function lst_detcompraparakardex($id_compra){
      $sel_obj = $this->db->query("Select c.id_pro, descsubtotal, p.pro_idunidadmedida, 
                                          cp.nro_factura, cp.id_almacen,
                                         (case when c.id_unimed = p.pro_idunidadmedida then 1
                                              when ifnull(fd.idunidad1,0) != 0 then 1/fd.cantidadequivalente
                                              when ifnull(fi.idunidad1,0) != 0 then fi.cantidadequivalente
                                              else 0
                                          end * c.precio_compra) as precio_compra,
                                          round((case when c.id_unimed = p.pro_idunidadmedida then 1
                                               when ifnull(fd.idunidad1,0) != 0 then fd.cantidadequivalente
                                               when ifnull(fi.idunidad1,0) != 0 then 1/fi.cantidadequivalente
                                               else 0
                                          end * c.cantidad),2) as cantidadcompra

                                       from compra_det c 
                                       inner join compra cp on cp.id_comp = c.id_comp
                                       inner join producto p on p.pro_id = c.id_pro 
                                       left join unidadfactorconversion fd on fd.idunidad1 = c.id_unimed and fd.idunidadequivale = p.pro_idunidadmedida 
                                       left join unidadfactorconversion fi on fi.idunidad1 = p.pro_idunidadmedida and fi.idunidadequivale = c.id_unimed 
                                       WHERE c.id_comp = $id_compra");
      $resultado = $sel_obj->result();
      return $resultado;
    }  

    /* listado de detalle de compras para actualizar kardex de serie */
    public function lst_detcompraparakardexserie($id_compra){
      $sel_obj = $this->db->query("Select ps.id_serie, cp.id_almacen, 1 as tipomovimiento, 
                                          cp.nro_factura, cp.fecha, ps.descripcion
                                       from producto_serie ps
                                       inner join compra cp on cp.id_comp = ps.id_detallecompra
                                       WHERE cp.id_comp = $id_compra");
      $resultado = $sel_obj->result();
      return $resultado;
    }  

    public function lst_almacen($sucursal = 0){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $query = $this->db->query("SELECT a.almacen_id, a.almacen_nombre, a.almacen_direccion,
                                        a.almacen_responsable, a.almacen_descripcion, a.sucursal_id 
                                  FROM almacen a
                                  INNER JOIN permiso_almacen p on p.id_almacen = a.almacen_id
                                  WHERE (($sucursal = 0) or (sucursal_id = $sucursal))
                                    AND p.id_usuario = $idusu
                                  ORDER BY almacen_nombre");
      $result = $query->result();
      return $result;
    }

    /* ACTUALIZAR ALMACEN EN LA TABLA TEMPRAL DE COMPRA */
    public function upd_categoria($categoria, $idtmp_comp){
      $sqlprovee = $this->db->query(" UPDATE tmp_compra SET categoria = $categoria WHERE id_tmp_comp = $idtmp_comp");
    }

    /* ACTUALIZAR ALMACEN EN LA TABLA TEMPRAL DE COMPRA */
    public function upd_almacen($almacen, $idtmp_comp){
      $sqlprovee = $this->db->query(" UPDATE tmp_compra SET id_almacen = $almacen WHERE id_tmp_comp = $idtmp_comp");
    }

    /* ACTUALIZAR sucursal EN LA TABLA TEMPRAL DE COMPRA */
    public function upd_sucursal($sucursal, $idtmp_comp){
      $sqlprovee = $this->db->query(" UPDATE tmp_compra SET id_sucursal = $sucursal WHERE id_tmp_comp = $idtmp_comp");
    }

    public function sel_sri_tipo_doc(){
      $sql = $this->db->query("SELECT cod_sri_tipo_doc, desc_sri_tipo_doc FROM sri_tipo_doc");
      $resu = $sql->result();
      return $resu;
    }

    public function sel_sri_sust_trib(){
      $sql = $this->db->query("SELECT cod_sri_sust_comprobante, desc_sri_sust_comprobante FROM sri_sust_comprobante");
      $resu = $sql->result();
      return $resu;
    }

    public function selcompret($idcomp){
      $sql = $this->db->query(" SELECT c.id_comp, r.id_comp_ret, r.id_puntoemision,
                                       CONCAT(u.nom_usu,' ',u.ape_usu) as usuario, p.nom_proveedor, p.nro_ide_proveedor, 
                                       c.fecharegistro, c.nro_factura, c.nro_autorizacion, tc.nom_cancelacion, 
                                       (c.descsubconiva + ifnull(c.montoice,0)) as descsubconiva, c.fecha,
                                       c.descsubsiniva, c.montoiva,  c.montototal,
                                       ifnull((select sum(cr.base_noiva + cr.base_iva) 
                                                 from compra_retencion_detrenta cr where cr.id_comp_ret=r.id_comp_ret),0) as totalbaseretenido,  
                                       ifnull((select sum(cr.valor_retencion_renta) 
                                                 from compra_retencion_detrenta cr where cr.id_comp_ret=r.id_comp_ret),0) + 
                                       ifnull((select sum(cr.valor_retencion_iva) 
                                                 from compra_retencion_detiva cr where cr.id_comp_ret=r.id_comp_ret),0)as montoretenido, 
                                       ifnull(nro_retencion,'') as nro_retencion,          
                                       ifnull(r.nro_autorizacion,'') as nro_autorizacionret,          
                                       ifnull(fecha_retencion,date(now())) as fecha_retencion,
                                       ifnull((select sum(valor_retencion_iva) from compra_retencion_detiva 
                                                 where id_comp_ret = r.id_comp_ret and porciento_retencion_iva = 10),0) as retiva10,          
                                       ifnull((select sum(valor_retencion_iva) from compra_retencion_detiva 
                                                 where id_comp_ret = r.id_comp_ret and porciento_retencion_iva = 20),0) as retiva20,          
                                       ifnull((select sum(valor_retencion_iva) from compra_retencion_detiva 
                                                 where id_comp_ret = r.id_comp_ret and porciento_retencion_iva = 30),0) as retiva30,          
                                       ifnull((select sum(valor_retencion_iva) from compra_retencion_detiva 
                                                 where id_comp_ret = r.id_comp_ret and porciento_retencion_iva = 50),0) as retiva50,          
                                       ifnull((select sum(valor_retencion_iva) from compra_retencion_detiva 
                                                 where id_comp_ret = r.id_comp_ret and porciento_retencion_iva = 70),0) as retiva70,          
                                       ifnull((select sum(valor_retencion_iva) from compra_retencion_detiva 
                                                 where id_comp_ret = r.id_comp_ret and porciento_retencion_iva = 100),0) as retiva100          
                                FROM compra c
                                INNER JOIN proveedor p ON p.id_proveedor = c.id_proveedor
                                INNER JOIN usu_sistemas u ON u.id_usu = c.id_usu
                                INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = c.formapago
                                LEFT JOIN compra_retencion r on r.id_compra = c.id_comp
                                WHERE c.id_comp = $idcomp");
      $resu = $sql->result();
      return $resu[0];
    }

    public function guardaimeiserie($imei, $desc, $idcom, $iddet, $idpro){
      $sqlpstmp = $this->db->query("SELECT COUNT(*) AS val FROM producto_serie_tmp WHERE id_producto = $idpro AND numeroserie = '$imei'");
      $restmp = $sqlpstmp->result();
      $valtmp = $restmp[0]->val;
      if($valtmp > 0){ return 1; }

      $sqlps = $this->db->query("SELECT COUNT(*) AS val FROM producto_serie WHERE id_producto = $idpro AND numeroserie = '$imei'");
      $resps = $sqlps->result();
      $valps = $resps[0]->val;
      if($valps > 0){ return 1; }

      $this->db->query("INSERT INTO producto_serie_tmp (id_producto, numeroserie, descripcion, id_detallecompra,  id_detalleventa) 
                                                VALUES ($idpro, '$imei', '$desc', $iddet, $idcom)");
      return 0;
    }

    public function actualiza_imeiserie(){
      $idusu = $this->session->userdata("sess_id");
      $sql = $this->db->query("SELECT * FROM producto_serie_tmp WHERE id_detalleventa = (SELECT id_tmp_comp FROM tmp_compra WHERE id_usu = $idusu)");
      $res = $sql->result();
      return $res;
    }

    public function eliminaimeiserie($idserie){
      $this->db->query("DELETE FROM producto_serie_tmp WHERE id_serie = $idserie");
    }

    public function actualiza_cantidad_serie($iddetalle){
      $this->db->query("UPDATE tmp_compra_det 
                          SET cantidad = (SELECT count(*) FROM producto_serie_tmp WHERE id_detallecompra = $iddetalle)
                          WHERE id = $iddetalle");

      $this->db->query("UPDATE tmp_compra_det SET 
                          montoiva = CASE iva WHEN 0 THEN 0 ELSE 
                                       round(precio_compra * cantidad * IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12), 2)  
                                     END, 
                          subtotal = round(precio_compra * cantidad, 2), 
                          descsubtotal = round(precio_compra * cantidad, 2)
                         WHERE id = $iddetalle");

      return 1;
    }

    public function lst_compradetalles_proveedor($sucursal, $proveedor, $producto, $desde, $hasta){
      $sql = $this->db->query("SELECT v.id_comp, v.fecha, v.nro_factura, tc.nom_cancelacion, v.id_proveedor,
                                      v.estatus, v.fecharegistro, v.fecha_pago, v.dias,
                                      p.pro_nombre, d.cantidad, u.descripcion as unidadmedida,
                                      d.precio_compra, d.subtotal, d.montoiva, d.descmonto,
                                      round(d.descsubtotal + d.montoiva, 2) as valortotal
                                FROM compra_det d 
                                INNER JOIN compra v on v.id_comp = d.id_comp
                                INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = v.formapago
                                INNER JOIN producto p on p.pro_id = d.id_pro
                                LEFT JOIN unidadmedida u on u.id = d.id_unimed
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND 
                                      v.estatus != 3 AND v.id_sucursal = $sucursal AND
                                      ($proveedor = 0 OR v.id_proveedor = $proveedor) AND
                                      ($producto = 0 OR d.id_pro = $producto) 
                                ORDER BY v.fecha DESC, v.nro_factura DESC, p.pro_nombre");
      
      $resu = $sql->result();
      return $resu;
    }

    public function lst_compra_resumenproducto($sucursal, $proveedor, $producto, $desde, $hasta){
      $sql = $this->db->query("SELECT d.id_pro, p.pro_nombre,
                                      u.descripcion as unidadmedida,
                                      sum(d.cantidad) as cantidad,
                                      sum(d.subtotal) as subtotal, 
                                      sum(d.montoiva) as montoiva, 
                                      sum(d.descmonto) as descmonto,
                                      round(sum(d.descsubtotal + d.montoiva), 2) as valortotal
                                FROM compra_det d 
                                INNER JOIN compra v on v.id_comp = d.id_comp
                                INNER JOIN producto p on p.pro_id = d.id_pro
                                LEFT JOIN unidadmedida u on u.id = d.id_unimed
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND 
                                      v.estatus != 3 AND v.id_sucursal = $sucursal AND
                                      ($proveedor = 0 OR v.id_proveedor = $proveedor) AND
                                      ($producto = 0 OR d.id_pro = $producto) 
                                GROUP BY d.id_pro, d.id_unimed
                                ORDER BY valortotal DESC, p.pro_nombre, u.descripcion");
      
      $resu = $sql->result();
      return $resu;
    }

    public function lst_compra_resumenproveedor($sucursal, $producto, $desde, $hasta){
      $sql = $this->db->query("SELECT v.id_proveedor, d.id_unimed, 
                                      pv.nom_proveedor, pv.telf_proveedor, 
                                      pv.correo_proveedor, pv.direccion_proveedor,
                                      u.descripcion as unidadmedida,
                                      count(*) as cantfacturas,
                                      sum(d.cantidad) as cantidad,
                                      sum(d.subtotal) as subtotal, 
                                      sum(d.montoiva) as montoiva, 
                                      sum(d.descmonto) as descmonto,
                                      round(sum(d.descsubtotal + d.montoiva), 2) as valortotal
                                FROM compra_det d 
                                INNER JOIN compra v on v.id_comp = d.id_comp
                                INNER JOIN proveedor pv on pv.id_proveedor = v.id_proveedor
                                LEFT JOIN unidadmedida u on u.id = d.id_unimed
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND 
                                      v.estatus != 3 AND v.id_sucursal = $sucursal AND
                                      d.id_pro = $producto 
                                GROUP BY v.id_proveedor, d.id_unimed
                                ORDER BY valortotal DESC, pv.nom_proveedor, u.descripcion");
      
      $resu = $sql->result();
      return $resu;
    }

    public function cargar_compra_edicion($idcompra){
      $usua = $this->session->userdata('usua');
      $id_usu = $usua->id_usu;

      $this->db->query("DELETE FROM tmp_compra_det 
                          WHERE id_tmp_comp IN (SELECT id_tmp_comp FROM tmp_compra WHERE id_usu =  $id_usu)");
      $this->db->query("DELETE FROM tmp_compra WHERE id_usu =  $id_usu");

      $this->db->query("INSERT INTO tmp_compra (id_usu, id_proveedor, fecha, nro_factura, nro_autorizacion, 
                                                formapago, valiva, subconiva, subsiniva, desc_monto, 
                                                descsubconiva, descsubsiniva, montoiva, montototal, categoria, 
                                                id_almacen, cod_sri_tipo_doc, cod_sri_sust_comprobante, 
                                                montoice, id_sucursal, doc_mod_cod_sri_tipo, doc_mod_numero, 
                                                doc_mod_autorizacion)
                          SELECT $id_usu, id_proveedor, fecha, nro_factura, nro_autorizacion, formapago, 
                                 valiva, subconiva, subsiniva, desc_monto, descsubconiva, descsubsiniva, 
                                 montoiva, montototal, categoria, id_almacen, cod_sri_tipo_doc, 
                                 cod_sri_sust_comprobante, montoice, id_sucursal, doc_mod_cod_sri_tipo, 
                                 doc_mod_numero, doc_mod_autorizacion
                            FROM compra
                           WHERE id_comp =  $idcompra");


      $sqlid = $this->db->query("SELECT last_insert_id() AS idcomp FROM tmp_compra");
      $varid = $sqlid->result();
      $idcomp = $varid[0]->idcomp;
    
      $det = $this->db->query("INSERT INTO tmp_compra_det (id_tmp_comp, id_pro, precio_compra, cantidad, id_unimed, iva, montoiva, subtotal, descmonto, descsubtotal, nota)
                                    SELECT $idcomp, id_pro, precio_compra, cantidad, id_unimed, iva, montoiva, subtotal, descmonto, descsubtotal, nota
                                      FROM compra_det
                                     WHERE id_comp = $idcompra");

      $sql = $this->db->query("SELECT id_tmp_comp, id_usu, id_proveedor, fecha, nro_factura, nro_autorizacion, 
                                      formapago, valiva, subconiva, subsiniva, desc_monto, 
                                      descsubconiva, descsubsiniva, montoiva, montototal, categoria, 
                                      id_almacen, cod_sri_tipo_doc, cod_sri_sust_comprobante, 
                                      montoice, id_sucursal, doc_mod_cod_sri_tipo, doc_mod_numero, 
                                      doc_mod_autorizacion,
                                      (SELECT dias FROM compra WHERE id_comp = $idcompra) as dias
                                FROM tmp_compra                
                                WHERE id_tmp_comp = $idcomp");
      $resu = $sql->result();
      return $resu[0];
    }

    public function modificar_compra($idusu, $idcomp, $fecha){
      $this->db->query("UPDATE compra c 
                          INNER JOIN tmp_compra t on t.id_usu = $idusu
                          SET c.fecha = '$fecha',
                              c.id_usu = $idusu,
                              c.id_proveedor = t.id_proveedor,
                              c.nro_factura = t.nro_factura, 
                              c.nro_autorizacion = t.nro_autorizacion,
                              c.categoria = t.categoria,
                              c.cod_sri_tipo_doc = t.cod_sri_tipo_doc, 
                              c.cod_sri_sust_comprobante = t.cod_sri_sust_comprobante,
                              c.doc_mod_cod_sri_tipo = t.doc_mod_cod_sri_tipo, 
                              c.doc_mod_numero = t.doc_mod_numero, 
                              c.doc_mod_autorizacion = t.doc_mod_autorizacion
                          WHERE c.id_comp = $idcomp");
      
      $this->db->query("DELETE FROM tmp_compra_det 
                          WHERE id_tmp_comp = (SELECT id_tmp_comp FROM tmp_compra WHERE id_usu = $idusu)");
      $this->db->query("DELETE FROM tmp_compra WHERE id_usu = $idusu");

      return $idcomp;
    }

}
