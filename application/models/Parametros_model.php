<?php

/* ------------------------------------------------
  ARCHIVO: Parametros_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a Parametros Generales.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */

class Parametros_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* OBTENER IVA */
    public function iva_get() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=1;");
        $result = $query->result();
        return $result[0];
    }

    /* ACTUALIZAR IVA */
    public function iva_upd($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=1;");
    }

    /* OBTENER Impresora Precuenta */
    public function impresoraprecuenta_get() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=2;");
        $result = $query->result();
        return $result[0];
    }

    /* ACTUALIZAR Impresora Precuenta */
    public function impresoraprecuenta_upd($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=2;");
    }

    /* OBTENER Impresora Factura */
    public function impresorafactura_get() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=3;");
        $result = $query->result();
        return $result[0];
    }

    /* ACTUALIZAR Impresora Factura */
    public function impresorafactura_upd($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=3;");
    }


    /* OBTENER EL NUMERO CONSECUTIVO DE LA FACTURA */
    public function sel_nro_factura(){
        $sql = $this->db->query("SELECT (valor) AS nrofact FROM contador WHERE id_contador = 2");
        $resultado = $sql->result();
        $nro = $resultado[0]->nrofact;
        $long = strlen($nro);
            if($long == 1) { $cnt_fact = "00000000".$nro; }
        elseif($long == 2) { $cnt_fact = "0000000".$nro;  }
        elseif($long == 3) { $cnt_fact = "000000".$nro;   }
        elseif($long == 4) { $cnt_fact = "00000".$nro;    } 
        elseif($long == 5) { $cnt_fact = "0000".$nro;    }                 
        elseif($long == 6) { $cnt_fact = "000".$nro;    } 
        elseif($long == 7) { $cnt_fact = "00".$nro;    }                 
        elseif($long == 8) { $cnt_fact = "0".$nro;    } 
        elseif($long > 9)  { $cnt_fact = $nro;        }
        return $cnt_fact;
    }

    /* OBTENER EL NUMERO CONSECUTIVO DE NOTA DE VENTA */
    public function sel_nro_nronot(){
        $sql = $this->db->query("SELECT (valor) AS nronot FROM contador WHERE id_contador = 3");
        $resultado = $sql->result();
        $nro = $resultado[0]->nronot;
        $long = strlen($nro);
            if($long == 1) { $cont_nv = "00000000".$nro; }
        elseif($long == 2) { $cont_nv = "0000000".$nro;  }
        elseif($long == 3) { $cont_nv = "000000".$nro;   }
        elseif($long == 4) { $cont_nv = "00000".$nro;    } 
        elseif($long == 5) { $cont_nv = "0000".$nro;    }                 
        elseif($long == 6) { $cont_nv = "000".$nro;    } 
        elseif($long == 7) { $cont_nv = "00".$nro;    }                 
        elseif($long == 8) { $cont_nv = "0".$nro;    } 
        elseif($long > 9)  { $cont_nv = $nro;        }
        return $cont_nv;
    }  


    public function upd_factura($factura){
        $sql = $this->db->query("UPDATE contador SET valor = $factura WHERE id_contador = 2");
    }

    public function upd_notaventa($notaventa){
        $sql = $this->db->query("UPDATE contador SET valor = $notaventa WHERE id_contador = 3");
    }

    /* OBTENER CODIGO ESTABLECIMIENTO */
    public function sel_codigoestab() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=4;");
        $result = $query->result();
        return $result[0];
    }

    /* OBTENER CODIGO PUNTO EMISION */
    public function sel_codigopuntoemision() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=5;");
        $result = $query->result();
        return $result[0];
    }

    /* ACTUALIZAR CODIGO ESTABLECIMIENTO */
    public function upd_codigoestab($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=4;");
    }

    /* ACTUALIZAR CODIGO PUNTO EMISION */
    public function upd_codigopuntoemision($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=5;");
    }

    /* OBTENER CONFIG MOSTRAR VISTA PEDIDO */
    public function sel_pedidovista() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=6;");
        $result = $query->result();
        return $result[0];
    }

    /* ACTUALIZAR CONFIG MOSTRAR VISTA PEDIDO */
    public function upd_pedidovista($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=6;");
    }

    /* OBTENER CONFIG MOSTRAR CLIENTE EN PEDIDO */
    public function sel_pedidocliente() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=7;");
        $result = $query->result();
        return $result[0];
    }

    /* ACTUALIZAR CONFIG MOSTRAR CLIENTE EN PEDIDO */
    public function upd_pedidocliente($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=7;");
    }

    /* OBTENER CONFIG MOSTRAR MESERO EN PEDIDO */
    public function sel_pedidomesero() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=8;");
        $result = $query->result();
        return $result[0];
    }

    /* ACTUALIZAR CONFIG MOSTRAR MESERO EN PEDIDO */
    public function upd_pedidomesero($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=8;");
    }

    /* OBTENER CONFIG HABILITAR TIPOS DE PRECIOS */
    public function sel_tipoprecio() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=9;");
        $result = $query->result();
        return $result[0];
    }

    /* ACTUALIZAR CONFIG HABILITAR TIPOS DE PRECIOS */
    public function upd_tipoprecio($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=9;");
    }

    /* OBTENER CONFIG HABILITAR FACTURACION SIN EXISTENCIA */
    public function sel_facturasinexistencia() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=10;");
        $result = $query->result();
        return $result[0];
    }

    /* ACTUALIZAR CONFIG HABILITAR FACTURACION SIN EXISTENCIA */
    public function upd_facturasinexistencia($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=10;");
    }

    /* PARAMETRO PRECIOS */
    public function tipo_precio(){
        $sql = $this->db->query("SELECT valor FROM parametros WHERE id = 9");
        $resu = $sql->result();
        $res = $resu[0]->valor;
        return $res;
    }

    /* OBTENER EL NUMERO CONSECUTIVO DE NOTA DE VENTA */
    public function sel_nro_comprobpago(){
        $sql = $this->db->query("SELECT valor FROM contador WHERE id_contador = 7");
        $resultado = $sql->result();
        if ($resultado != NULL){
            return $resultado[0]->valor;
        } else {
            return 1;
        }
    }  


    public function upd_comprobpago($valor){
        $sql = $this->db->query("UPDATE contador SET valor = $valor WHERE id_contador = 7");
    }

    /*  FACTURACION EN PDF */
    public function sel_facturapdf() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=11;");
        $result = $query->result();
        $factpdf = $result[0]->valor;
        return $factpdf;
    }

    public function upd_facturapdf($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=11;");
    }

    /*  LIMITE DE PRODUCTOS EN FACTURA VENTA */
    public function sel_limiteprodventa() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=12;");
        $result = $query->result();
        $valor = $result[0]->valor;
        return $valor;
    }

    public function upd_limiteprodventa($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=12;");
    }

    /* OBTENER IMPUESTO ADICIONAL */
    public function sel_impuestoadicional() {
        $query = $this->db->query("SELECT valor, descripcion FROM parametros WHERE id=13;");
        $result = $query->result();
        return $result[0];
    }

    /* ACTUALIZAR IMPUESTO ADICIONAL */
    public function upd_impuestoadicional($descripcion, $valor){
        $query = $this->db->query("UPDATE parametros SET descripcion='$descripcion', valor='$valor' WHERE id=13;");
    }

    /* OBTENER EL NUMERO CONSECUTIVO DE LA RETENCION DE COMPRA */
    public function sel_nro_retencion(){
        $sql = $this->db->query("SELECT (valor) AS nrofact FROM contador WHERE id_contador = 10");
        $resultado = $sql->result();
        $nro = $resultado[0]->nrofact;
        $cnt_fact = str_pad($nro, 9, '0', STR_PAD_LEFT);
        return $cnt_fact;
    }

    public function upd_retencion($valor){
        $sql = $this->db->query("UPDATE contador SET valor = $valor WHERE id_contador = 10");
    }

    /* OBTENER CONFIG HABILITAR Numero Serie */
    public function sel_numeroserie() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=14;");
        $result = $query->result();
        return $result[0];
    }

    /* ACTUALIZAR CONFIG HABILITAR Numero Serie */
    public function upd_numeroserie($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=14;");
    }

    /*  IMPRIMIR COMANDA AL FACTURAR */
    public function sel_comandafactura() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=18;");
        $result = $query->result();
        $factpdf = $result[0]->valor;
        return $factpdf;
    }

    public function upd_comandafactura($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=18;");
    }

    /*  HABILITA NUMERO ORDEN */
    public function sel_habilitaorden() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=19;");
        $result = $query->result();
        $factpdf = $result[0]->valor;
        return $factpdf;
    }

    public function upd_habilitaorden($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=19;");
    }

    /*  FACTURA PRECIO CON IVA */
    public function sel_facturaprecioconiva() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=20;");
        $result = $query->result();
        $factpdf = $result[0]->valor;
        return $factpdf;
    }

    public function upd_facturaprecioconiva($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=20;");
    }

    /*  HABILITA Asociacion Automatica Cliente Vendedor */
    public function sel_clientevendedor() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=21;");
        $result = $query->result();
        $factpdf = $result[0]->valor;
        return $factpdf;
    }

    public function upd_clientevendedor($valor){
        $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=21;");
    }

    /*  Cuota Minima Venta Asociacion Automatica Cliente Vendedor */
    public function sel_cuotaclientevendedor() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=22;");
        $result = $query->result();
        $factpdf = $result[0]->valor;
        return $factpdf;
    }

    public function upd_cuotaclientevendedor($valor){
        $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=22;");
    }

    /*  HABILITA Codigo Cliente Venta */
    public function sel_codigocliente() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=23;");
        $result = $query->result();
        $factpdf = $result[0]->valor;
        return $factpdf;
    }

    public function upd_codigocliente($valor){
        $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=23;");
    }

    public function sel_descpro() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=24;");
        $result = $query->result();
        return $result[0]->valor;
    }

    public function upd_descpro($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=24;");
    }

    public function sel_impresionlocal() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=25;");
        $result = $query->result();
        return $result[0]->valor;
    }

    public function upd_impresionlocal($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=25;");
    }

    /*  HABILITA Variante */
    public function sel_habilitavariante() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=26;");
        $result = $query->result();
        $factpdf = $result[0]->valor;
        return $factpdf;
    }

    public function upd_habilitavariante($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=26;");
    }

    /*  HABILITA ubicacion venta */
    public function sel_ubicacionventa() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=27;");
        $result = $query->result();
        $factpdf = $result[0]->valor;
        return $factpdf;
    }

    public function upd_ubicacionventa($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=27;");
    }

    /*  HABILITA detalle totaliva venta */
    public function sel_detalletotalivaventa() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=28;");
        $result = $query->result();
        $factpdf = $result[0]->valor;
        return $factpdf;
    }

    public function upd_detalletotalivaventa($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=28;");
    }

    /*  HABILITA impresion grafica */
    public function sel_impresiongrafica() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=29;");
        $result = $query->result();
        return $result[0]->valor;
    }

    public function upd_impresiongrafica($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=29;");
    }

    /*  HABILITA impresion subsidio */
    public function sel_impresionsubsidio() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=30;");
        $result = $query->result();
        return $result[0]->valor;
    }

    public function upd_impresionsubsidio($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=30;");
    }

    /*  HABILITA promo en pedido */
    public function sel_pedidopromo() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=31;");
        $result = $query->result();
        return $result[0]->valor;
    }
    public function upd_pedidopromo($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=31;");
    }

    /*  Decimales en precio */
    public function sel_decimalesprecio() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=32;");
        $result = $query->result();
        return $result[0]->valor;
        //return 6;
    }
    public function upd_decimalesprecio($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=32;");
    }

    /*  Decimales en cantidad */
    public function sel_decimalescantidad() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=33;");
        $result = $query->result();
        return $result[0]->valor;
        //return 4;
    }
    public function upd_decimalescantidad($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=33;");
    }

    // nota de venta con iva
    public function sel_habilitanotaventaiva() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=34;");
        $result = $query->result();
        return $result[0]->valor;
        //return 4;
    }
    public function upd_habilitanotaventaiva($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=34;");
    }

    // enviar correo al autorizar comprobante sri
    public function sel_habilitacorreoautosri() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=35;");
        $result = $query->result();
        return $result[0]->valor;
        //return 4;
    }
    public function upd_habilitacorreoautosri($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=35;");
    }

    // habilitar cambio de precio solo admin
    public function sel_habilitacambioprecio() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=36;");
        $result = $query->result();
        return $result[0]->valor;
    }
    public function upd_habilitacambioprecio($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=36;");
    }

    // predeterminar pago en efectivo en venta
    public function sel_ventapagoefectivo() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=37;");
        $result = $query->result();
        return $result[0]->valor;
    }
    public function upd_ventapagoefectivo($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=37;");
    }

    // habilitar asociacion automatica cliente categoria venta
    public function sel_habilitaclientecategoria() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=38;");
        $result = $query->result();
        return $result[0]->valor;
    }
    public function upd_habilitaclientecategoria($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=38;");
    }

    // habilitar gestion petshop
    public function sel_habilitapetshop() {
        $query = $this->db->query("SELECT habilita_petshop FROM pet_config;");
        $result = $query->result();
        return $result[0]->habilita_petshop;
    }
    public function upd_habilitapetshop($valor){
        $query = $this->db->query("UPDATE pet_config SET habilita_petshop='$valor';");
    }

    // tipo descuento por producto en venta
    public function sel_tipodescuentoproducto() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=39;");
        $result = $query->result();
        return $result[0]->valor;
    }
    public function upd_tipodescuentoproducto($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=39;");
    }

    // etiqueta punto venta singular
    public function sel_ptoventasingular() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=40;");
        $result = $query->result();
        return $result[0]->valor;
    }
    public function upd_ptoventasingular($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=40;");
    }

    // etiqueta punto venta plural
    public function sel_ptoventaplural() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=41;");
        $result = $query->result();
        return $result[0]->valor;
    }
    public function upd_ptoventaplural($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=41;");
    }

    // estado del punto de venta al facturar
    public function sel_estadoptoventafacturar() {
        $query = $this->db->query("SELECT valor FROM parametros WHERE id=42;");
        $result = $query->result();
        return $result[0]->valor;
    }
    public function upd_estadoptoventafacturar($valor){
        $query = $this->db->query("UPDATE parametros SET valor='$valor' WHERE id=42;");
    }

}
