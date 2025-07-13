<?php

/* ------------------------------------------------
  ARCHIVO: Gastos_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Gastos.
  FECHA DE CREACIÓN: 30/08/2017
 * 
  ------------------------------------------------ */

class Gastos_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* LISTADO DE GASTOS */
    public function lista_gastos($sucursal, $desde, $hasta){
      $sql = $this->db->query("SELECT g.id_gastos, g.fecha, g.id_proveedor, p.nom_proveedor, g.nro_factura, 
                                      g.descripcion, gc.nom_cat_gas as categoria, g.total, g.nro_autorizacion, 
                                      g.estatus, p.nro_ide_proveedor, g.cod_sri_tipo_doc,
                                      g.doc_mod_cod_sri_tipo, g.doc_mod_numero, g.doc_mod_autorizacion 
                                FROM gastos g
                                LEFT JOIN proveedor p ON p.id_proveedor = g.id_proveedor
                                LEFT JOIN gastos_categorias gc ON gc.id_cat_gas = g.categoria
                                WHERE g.id_sucursal = $sucursal AND g.fecha BETWEEN '$desde' AND '$hasta'");
      $resu = $sql->result();
      return $resu;
    }

    /* GASTOS TOTALES DEL DIA */
    public function gastos_total($sucursal, $desde, $hasta){
      $sql = $this->db->query("SELECT SUM(total * CASE cod_sri_tipo_doc WHEN '04' THEN -1 ELSE 1 END) AS total 
                                 FROM gastos 
                                 WHERE id_sucursal = $sucursal AND fecha BETWEEN '$desde' AND '$hasta' 
                                   AND estatus != 3");
      $resu = $sql->result();
      $total = $resu[0]->total;
      return $total;
    }

    /* LISTADO DE GASTOS POR RANGO DE FECHA */
    public function venta_rango($sucursal, $desde, $hasta){

      $sql = $this->db->query("SELECT g.id_gastos, g.fecha, p.nom_proveedor, g.nro_factura, g.descripcion, 
                                      gc.nom_cat_gas as categoria, g.total, g.estatus, ed.desc_estatus,
                                      g.cod_sri_tipo_doc 
                                FROM gastos g
                                LEFT JOIN proveedor p ON p.id_proveedor = g.id_proveedor
                                LEFT JOIN gastos_categorias gc ON gc.id_cat_gas = g.categoria
                                LEFT JOIN estatus_documento ed ON ed.id_estatus = g.estatus
                                WHERE g.id_sucursal = $sucursal AND g.fecha BETWEEN '$desde' AND '$hasta'");
      $resu = $sql->result();
      return $resu;
    }

   /* GASTOS TOTALES POR RANGO */
    public function gastos_total_rago($sucursal, $desde, $hasta){
      $sql = $this->db->query("SELECT SUM(total * CASE cod_sri_tipo_doc WHEN '04' THEN -1 ELSE 1 END) AS total 
                                 FROM gastos 
                                 WHERE id_sucursal = $sucursal AND fecha BETWEEN '$desde' AND '$hasta' AND estatus != 3");
      $resu = $sql->result();
      $total = $resu[0]->total;
      return $total;
    }





    /* BUSQUEDA POR ID QUE PERMITE MOSTRAR EL GASTO PARA SER MODIFICADO */
    public function sel_gas_id($idgas){
      $query = $this->db->query("SELECT id_gastos, fecha, id_proveedor, nro_factura, nro_autorizacion, descripcion, 
                                        apiva, subtotal, subtotalivacero, descuento, subtotaldesc, subtotalivacero, montoiva, total, categoria 
                                   FROM gastos
                                  WHERE id_gastos = $idgas");
      $result = $query->result();
      return $result[0];
    }

    /* GUARDAR FACTURA */
    public function pagar_gastos($idusu, $sucursal, $fecha, $proveedor, $factura, $autorizacion, $descripcion, 
                                 $formapago, $efectivo, $tarjeta, $cambio, $dias, $subtotal, $subtotalivacero, 
                                 $descuento, $subtotaldesc, $subtotalivacerodesc, $iva, $montoiva, $total, 
                                 $categoria, $tipodoc, $sustributario, $tipodocmod, $numdocmod, $autodocmod){

      $efectivo = str_replace(",", ".", $efectivo);
      $tarjeta = str_replace(",", ".", $tarjeta);
      $cambio = str_replace(",", ".", $cambio);
      $subtotal = str_replace(",", ".", $subtotal);
      $subtotalivacero = str_replace(",", ".", $subtotalivacero);
      $descuento = str_replace(",", ".", $descuento);
      $subtotaldesc = str_replace(",", ".", $subtotaldesc);
      $subtotalivacerodesc = str_replace(",", ".", $subtotalivacerodesc);
      $montoiva = str_replace(",", ".", $montoiva);
      $total = str_replace(",", ".", $total);

      $efectivo = str_replace("$", "", $efectivo);
      $tarjeta = str_replace("$", "", $tarjeta);
      $cambio = str_replace("$", "", $cambio);
      $subtotal = str_replace("$", "", $subtotal);
      $subtotalivacero = str_replace("$", "", $subtotalivacero);
      $descuento = str_replace("$", "", $descuento);
      $subtotaldesc = str_replace("$", "", $subtotaldesc);
      $subtotalivacerodesc = str_replace("$", "", $subtotalivacerodesc);
      $montoiva = str_replace("$", "", $montoiva);
      $total = str_replace("$", "", $total);

      if($dias > 0){
        $fecha_pago = strtotime ( '+'.$dias.' day' , strtotime ( $fecha ) ) ;
        $fecha_pago = date ( 'Y-m-d' , $fecha_pago );
      }else{
        $fecha_pago = $fecha;
      }

      if($formapago == 'Contado'){ $estatus = "1"; $forpag = 1; $dias = 0;}
      else{ $estatus = "2"; $forpag = 2; }  

      /* Se guarda la cabecera de la venta en la tabla venta */
      $sql_add = $this->db->query("call gastos_ins ($sucursal, '$fecha', $proveedor, '$factura', '$autorizacion', 
                                                    '$descripcion', '$forpag', $iva, $subtotal, $subtotalivacero, 
                                                    $descuento, $subtotaldesc, $subtotalivacerodesc, $montoiva, 
                                                    $total, $idusu, '$estatus', $dias, '$fecha_pago', $categoria, 
                                                    '$tipodoc', '$sustributario', 
                                                    '$tipodocmod', '$numdocmod', '$autodocmod')");
      $resultado = $sql_add->result();
      $id = $resultado[0]->vid; /* Se obtiene el id del registro insertado en la tabla venta para relacionarlo con venta_detalle */

      $sql_add->next_result(); 
      $sql_add->free_result();

      if($formapago == 'Contado'){
        /* Guardar tipo de pago si es efectivo o tarjeta */    
        $this->db->query("INSERT INTO documento_pago (estado, numero, valor, observaciones) 
                            VALUES (1, '', $efectivo + $tarjeta, '')");
        $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM documento_pago");
        $varid = $sqlid->result();
        $idpago = $varid[0]->id;

        $this->db->query("INSERT INTO documento_pagodeposito (iddocumento, iddeposito) 
                            SELECT $idpago, d.id FROM gastos c 
                              INNER JOIN deposito_efectivo d on d.idsucursal = c.id_sucursal
                              WHERE d.idtipo = 2 AND c.id_gastos = $id");

        $this->db->query("INSERT INTO gastos_abonos (iddocpago, id_gastos, id_formapago, monto) 
                            VALUES ($idpago, $id, 1, $efectivo)");
        $this->db->query("INSERT INTO gastos_abonos (iddocpago, id_gastos, id_formapago, monto) 
                            VALUES ($idpago, $id, 2, $tarjeta)");
      }

      return $id;

    }

    /* ACTUALIZAR GASTOS */
    public function actualiza_gastos($idusu, $sucursal, $fecha, $proveedor, $factura, $autorizacion, $descripcion, 
                                     $formapago, $efectivo, $tarjeta, $cambio, $dias, $subtotal, $descuento, 
                                     $subtotaldesc, $iva, $montoiva, $total, $categoria, $tipodoc, $sustributario, 
                                     $idgastos, $tipodocmod, $numdocmod, $autodocmod){

      $efectivo = str_replace(",", ".", $efectivo);
      $tarjeta = str_replace(",", ".", $tarjeta);
      $cambio = str_replace(",", ".", $cambio);
      $subtotal = str_replace(",", ".", $subtotal);
      $descuento = str_replace(",", ".", $descuento);
      $subtotaldesc = str_replace(",", ".", $subtotaldesc);
      $montoiva = str_replace(",", ".", $montoiva);
      $total = str_replace(",", ".", $total);

      $efectivo = str_replace("$", "", $efectivo);
      $tarjeta = str_replace("$", "", $tarjeta);
      $cambio = str_replace("$", "", $cambio);
      $subtotal = str_replace("$", "", $subtotal);
      $descuento = str_replace("$", "", $descuento);
      $subtotaldesc = str_replace("$", "", $subtotaldesc);
      $montoiva = str_replace("$", "", $montoiva);
      $total = str_replace("$", "", $total);

      if($dias > 0){
        $fecha_pago = strtotime ( '+'.$dias.' day' , strtotime ( $fecha ) ) ;
        $fecha_pago = date ( 'Y-m-d' , $fecha_pago );
      }else{
        $fecha_pago = $fecha;
      }

      $formapago = 'Contado';
      $estatus = "1"; 
      $forpag = 1; 
      $dias = 0;

      /* Se guarda la cabecera de la venta en la tabla venta */
      $sql_add = $this->db->query("call gastos_upd ($sucursal, '$fecha', $proveedor, '$factura', '$autorizacion', 
                                                    '$descripcion', '$forpag', $iva, $subtotal, $descuento, 
                                                    $subtotaldesc, $montoiva, $total, $idusu, '$estatus', $dias, 
                                                    '$fecha_pago', $categoria, '$tipodoc', '$sustributario', 
                                                    $idgastos, $tipodocmod, $numdocmod, $autodocmod)");
   //   $resultado = $sql_add->result();
   //   $id = $resultado[0]->vid; /* Se obtiene el id del registro insertado en la tabla venta para relacionarlo con venta_detalle */

      $sql_add->next_result(); 
      $sql_add->free_result();


        /* Guardar tipo de pago si es efectivo o tarjeta */    
        $sql_pago = $this->db->query("UPDATE gastos_abonos SET monto = $efectivo WHERE id_gastos = $idgastos");
      //  $sql_pago = $this->db->query("INSERT INTO gastos_abonos (id_gastos, id_formapago, monto) VALUES ($id, 2, $tarjeta)");


      return $idgastos;

    }


    public function datosproveedor($id_compra){
      $sel_obj = $this->db->query("SELECT p.id_proveedor, p.nom_proveedor, p.tip_ide_proveedor, p.nro_ide_proveedor, p.razon_social, 
                                         p.telf_proveedor, p.correo_proveedor, p.ciudad_proveedor, p.direccion_proveedor, p.relacionada
                                  FROM  gastos c
                                  LEFT JOIN proveedor p ON p.id_proveedor = c.id_proveedor
                                  WHERE c.id_gastos = $id_compra");
      $resultado = $sel_obj->result();
      return $resultado;
    } 

    public function categorialst(){
      $query = $this->db->query("SELECT id_cat_gas, nom_cat_gas FROM gastos_categorias");
      $result = $query->result();
      return $result;
    }

    /* ELIMINAR GASTO DE LA TABLA */
    public function del_gas_id($idgas){
      $query = $this->db->query("DELETE FROM gastos WHERE id_gastos = $idgas");
    }

    /* BUSCAR Gasto */
    public function busca_gasto($idgasto){
      $sql = $this->db->query("SELECT g.id_gastos, g.fecha, g.id_proveedor, p.nom_proveedor, p.nro_ide_proveedor, p.telf_proveedor, 
                                      p.direccion_proveedor, g.nro_factura, g.descripcion, g.total, g.nro_autorizacion, g.estatus, 
                                      g.id_sucursal 
                                FROM gastos g
                                LEFT JOIN proveedor p ON p.id_proveedor = g.id_proveedor
                                WHERE id_gastos=$idgasto");
      $resultado = $sql->result();
      return $resultado[0];
    }

    /*  ANULAR Gasto  */  
    public function anular_gasto($id_gasto, $obs){
      $usua = $this->session->userdata('usua');
      $idusuario = $usua->id_usu;
      
      $query = $this->db->query("call gasto_null($id_gasto, $idusuario, '$obs');");

      $resu = $query->result();

      $query->next_result(); 
      $query->free_result();

      return $resu[0];
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

    public function selgastosret($idgastos){
      $sql = $this->db->query(" SELECT c.id_gastos, r.id_gastos_ret,
                                       CONCAT(u.nom_usu,' ',u.ape_usu) as usuario, p.nom_proveedor, p.nro_ide_proveedor, 
                                       c.fecharegistro, c.nro_factura, c.nro_autorizacion, /*tc.nom_cancelacion, */
                                       c.subtotaldesc, c.subtotalivacerodesc, c.montoiva,  c.total,
                                       ifnull((select sum(cr.base_noiva + cr.base_iva) 
                                                 from gastos_retencion_detrenta cr where cr.id_gastos_ret=r.id_gastos_ret),0) as totalbaseretenido,  
                                       ifnull((select sum(cr.valor_retencion_renta) 
                                                 from gastos_retencion_detrenta cr where cr.id_gastos_ret=r.id_gastos_ret),0) + 
                                       ifnull((select sum(cr.valor_retencion_iva) 
                                                 from gastos_retencion_detiva cr where cr.id_gastos_ret=r.id_gastos_ret),0)as montoretenido, 
                                       ifnull(nro_retencion,'') as nro_retencion,          
                                       ifnull(r.nro_autorizacion,'') as nro_autorizacionret,          
                                       ifnull(fecha_retencion,date(now())) as fecha_retencion,
                                       ifnull((select sum(valor_retencion_iva) from gastos_retencion_detiva 
                                                 where id_gastos_ret = r.id_gastos_ret and porciento_retencion_iva = 10),0) as retiva10,          
                                       ifnull((select sum(valor_retencion_iva) from gastos_retencion_detiva 
                                                 where id_gastos_ret = r.id_gastos_ret and porciento_retencion_iva = 20),0) as retiva20,          
                                       ifnull((select sum(valor_retencion_iva) from gastos_retencion_detiva 
                                                 where id_gastos_ret = r.id_gastos_ret and porciento_retencion_iva = 30),0) as retiva30,          
                                       ifnull((select sum(valor_retencion_iva) from gastos_retencion_detiva 
                                                 where id_gastos_ret = r.id_gastos_ret and porciento_retencion_iva = 50),0) as retiva50,          
                                       ifnull((select sum(valor_retencion_iva) from gastos_retencion_detiva 
                                                 where id_gastos_ret = r.id_gastos_ret and porciento_retencion_iva = 70),0) as retiva70,          
                                       ifnull((select sum(valor_retencion_iva) from gastos_retencion_detiva 
                                                 where id_gastos_ret = r.id_gastos_ret and porciento_retencion_iva = 100),0) as retiva100          
                                FROM gastos c
                                INNER JOIN proveedor p ON p.id_proveedor = c.id_proveedor
                                INNER JOIN usu_sistemas u ON u.id_usu = c.id_usu
                                /*INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = c.formapago*/
                                LEFT JOIN gastos_retencion r on r.id_gastos = c.id_gastos
                                WHERE c.id_gastos = $idgastos");
      $resu = $sql->result();
      return $resu[0];
    }


}
