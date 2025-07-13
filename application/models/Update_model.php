<?php

/* ------------------------------------------------
  ARCHIVO: Update_model.php
  DESCRIPCION: Manejo de consultas para la actualizacion de BD.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */
require_once(APPPATH.'models/Update_base_model.php');  

class Update_model extends Update_base_model {

    function __construct() {
        parent::__construct();
    }

    public function actualizabase(){

      /* version 3.0 */  

      $res = $this->existe_tabla('cliente_tipoprecio');
      if ($res != true) $this->crea_tabla_cliente_tipoprecio();

      $this->chequea_cliente_tipoprecio();      
      $this->chequea_cliente_consumidorfinal();

      $this->crea_tabla_version();
      $res = $this->get_version();

      if ($res < 3000) {

            $res = $this->existe_tabla('venta_tmp');
            if ($res != true) $this->crea_tabla_venta_tmp();
            $res = $this->existe_tabla('venta_detalle_tmp');
            if ($res != true) $this->crea_tabla_venta_detalle_tmp();

            $this->tabla_parametros_quitarautoinc();

            $this->tabla_parametros_inserta(6, "Pedido Mostrar Vista", "0");
            $this->tabla_parametros_inserta(7, "Pedido Mostrar Cliente", "0");
            $this->tabla_parametros_inserta(8, "Pedido Mostrar Mesero", "0");

            $this->tabla_parametros_inserta(9, "Habilita Tipo Precio", "0");
            $this->tabla_parametros_inserta(10, "Habilita Facturar Sin Existencia", "0");
            $this->tabla_parametros_inserta(11, "Facturacion PDF", "1");
            $this->tabla_parametros_inserta(12, "Limite Productos Factura", "0");
            $this->tabla_parametros_inserta(13, "Impuesto Adicional", "0");
            $this->tabla_parametros_inserta(14, "Habilita Numero de Serie", "0");

            $res = $this->existe_columna_tabla('venta_tmp','id_cliente');
            if ($res != true) $this->add_columna_tabla('venta_tmp','id_cliente', 'int', "");
            $res = $this->existe_columna_tabla('venta','id_cliente');
            if ($res != true) $this->add_columna_tabla('venta','id_cliente', 'int', "update venta set id_cliente=(select id_cliente from clientes where clientes.ident_cliente=venta.nro_ident limit 1)");
            $res = $this->existe_columna_tabla('venta_detalle_tmp','tipprecio');
            if ($res != true) $this->add_columna_tabla('venta_detalle_tmp','tipprecio', 'int', "");
            $res = $this->existe_columna_tabla('venta_detalle','tipprecio');
            if ($res != true) $this->add_columna_tabla('venta_detalle','tipprecio', 'int', "");
            $res = $this->existe_columna_tabla('clientes','credito');
            if ($res != true) $this->add_columna_tabla('clientes','credito', 'decimal(11,2)', "");      


            $this->tabla_formapago_quitarautoinc();
            $res = $this->existe_columna_tabla('formapago','esinstrumentobanco');
            if ($res != true) {
              $this->add_columna_tabla('formapago','esinstrumentobanco', 'int', "");
            }  
            $res = $this->existe_columna_tabla('formapago','estarjeta');
            if ($res != true) {
              $this->add_columna_tabla('formapago','estarjeta', 'int', "");
            }  
            $this->tabla_formapago_inserta(1, '01', 'EFECTIVO', 0, 0);
            $this->tabla_formapago_inserta(2, '20', 'CHEQUE', 1, 0);
            $this->tabla_formapago_inserta(3, '19', 'TARJETA DE CRÉDITO', 0, 1);
            $this->tabla_formapago_inserta(4, '16', 'TARJETA DE DEBITO', 0, 1);
            $this->tabla_formapago_inserta(5, '18', 'TARJETA PREPAGO', 0, 1);
            $this->tabla_formapago_inserta(6, '20', 'TRANSFERENCIA', 1, 0);
            $this->tabla_formapago_inserta(7, '17', 'DINERO ELECTRONICO', 0, 0);
            $this->tabla_formapago_inserta(8, '20', 'OTROS', 1, 0);

            $res = $this->existe_tabla('tipobanco');
            if ($res != true) $this->crea_tabla_tipobanco();
            $res = $this->existe_columna_tabla('bancos','id_tipo');
            if ($res != true) $this->add_columna_tabla('bancos','id_tipo', 'int', "update bancos set id_tipo=1");

            $res = $this->existe_columna_tabla('venta_formapago','id');
            if ($res != true) $this->tabla_ventaformapago_autoinc();

            $res = $this->existe_columna_tabla('venta_formapago','fecha');
            if ($res != true) {
              $this->add_columna_tabla('venta_formapago','fecha', 'date', "update venta_formapago set fecha=now()");
            }  

            $res = $this->existe_tabla('venta_formapagobanco');
            if ($res != true) $this->crea_tabla_venta_formapagobanco();

            $res = $this->existe_tabla('venta_formapagotarjeta');
            if ($res != true) $this->crea_tabla_venta_formapagotarjeta();

            $res = $this->existe_columna_tabla('venta','id_tipcancelacion');
            if ($res != true) $this->add_columna_tabla('venta','id_tipcancelacion', 'int', "update venta set id_tipcancelacion=1");

            $res = $this->existe_tabla('venta_estadocredito');
            if ($res != true) $this->crea_tabla_venta_estadocredito();
            $res = $this->existe_tabla('venta_credito');
            if ($res != true) $this->crea_tabla_venta_credito();
            $res = $this->existe_tabla('venta_creditoabonoinicial');
            if ($res != true) $this->crea_tabla_venta_creditoabonoinicial();
            $res = $this->existe_tabla('venta_creditocuota');
            if ($res != true) $this->crea_tabla_venta_creditocuota();
            $res = $this->existe_tabla('venta_abonocreditocuota');
            if ($res != true) $this->crea_tabla_venta_abonocreditocuota();

            $res = $this->existe_tabla('clausula');
            if ($res != true) $this->crea_tabla_clausula();


            $this->tabla_contador_quitarautoinc();

            $res = $this->existe_columna_tabla('contador','prefijo');
            if ($res != true) $this->add_columna_tabla('contador','prefijo', 'varchar(10)', "");

            $this->tabla_contador_inserta(6, 'Proforma', '1', 'PROF');
          
            $this->upd_columna_tabla('kardex', 'valorunitario', 'decimal(15,4)');
            $this->upd_columna_tabla('kardex', 'saldovalorunitario', 'decimal(15,4)');

            $res = $this->existe_tabla('formapago_tmp');
            if ($res != true) $this->crea_tabla_tmp_formapago();     

            $res = $this->existe_tabla('venta_credito_tmp');
            if ($res != true) $this->crea_tabla_venta_credito_tmp();

            $this->tabla_contador_inserta(7, 'Comprobante de Pago', '1', 'PAG');

            $res = $this->existe_columna_tabla('venta_formapago','nro_comprobante');
            if ($res != true) $this->add_columna_tabla('venta_formapago','nro_comprobante', 'int', "");

            $res = $this->existe_tabla('caja_egreso');
            if ($res != true) $this->crea_tabla_caja_egreso();

            $res = $this->existe_tabla('proforma');
            if ($res != true) $this->crea_tabla_proforma();

            $res = $this->existe_tabla('proforma_tmp');
            if ($res != true) $this->crea_tabla_proformatmp();

            $res = $this->existe_tabla('proforma_detalle');
            if ($res != true) $this->crea_tabla_proforma_detalle();

            $res = $this->existe_tabla('proforma_detalle_tmp');
            if ($res != true) $this->crea_tabla_proforma_detalletmp();

            $res = $this->existe_tabla('venta_creditocuota_tmp');
            if ($res != true) $this->crea_tabla_venta_creditocuota_tmp();

            $res = $this->existe_columna_tabla('categorias','menu');
            if ($res != true) $this->add_columna_tabla('categorias','menu', 'tinyint(4)', "");

            $res = $this->existe_tabla('kardex');
            if ($res != true) $this->crea_tabla_kardex();

            $res = $this->existe_tabla('inventariodocumento');
            if ($res != true) $this->crea_tabla_inventariodocumento();

            $res = $this->existe_tabla('inventariodocumento_detalle');
            if ($res != true) $this->crea_tabla_inventariodocumento_detalle();

            $res = $this->existe_tabla('tmp_movinv');
            if ($res != true) $this->crea_tabla_tmp_movinv();

            $res = $this->existe_columna_tabla('tmp_movinv','id_almdest');
            if ($res != true) $this->add_columna_tabla('tmp_movinv','id_almdest', 'int', "");

            $res = $this->existe_tabla('tmp_movinv_det');
            if ($res != true) $this->crea_tabla_tmp_movinv_det();

            $res = $this->existe_tabla('inventariodocumtransfer');
            if ($res != true) $this->crea_tabla_inventariodocumtransfer();

            $this->tabla_contador_inserta(4, 'Ingreso de Inventario', '1', 'ING');
            $this->tabla_contador_inserta(5, 'Egreso de Inventario', '1', 'EGRE');
            $this->tabla_contador_inserta(8, 'Transferencia Inventario', '1', 'TRAN');
            $this->tabla_contador_inserta(9, 'Egreso Caja', '1', 'EGRCAJ');

            $res = $this->existe_tabla('usuprecio');
            if ($res != true) $this->crea_tabla_usuprecio();

            $res = $this->existe_columna_tabla('usu_sistemas','ultimoacceso');
            if ($res != true) $this->add_columna_tabla('usu_sistemas','ultimoacceso', 'datetime', "update usu_sistemas set ultimoacceso=now()");

            $res = $this->existe_columna_tabla('venta','montoimpuestoadicional');
            if ($res != true) $this->add_columna_tabla('venta','montoimpuestoadicional', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('venta_tmp','id_proforma');
            if ($res != true) $this->add_columna_tabla('venta_tmp','id_proforma', 'int', "");

            $res = $this->existe_tabla('concepto_retencion');
            if ($res != true) $this->crea_tabla_concepto_retencion();

            $res = $this->existe_tabla('porcentaje_retencion_iva');
            if ($res != true) $this->crea_tabla_porcentaje_retencion_iva();

            $res = $this->existe_tabla('sri_sust_comprobante');
            if ($res != true) $this->crea_tabla_sri_sust_comprobante();

            $res = $this->existe_tabla('sri_tipo_doc');
            if ($res != true) $this->crea_tabla_sri_tipo_doc();

            $res = $this->existe_columna_tabla('tmp_compra','cod_sri_tipo_doc');
            if ($res != true) $this->add_columna_tabla('tmp_compra','cod_sri_tipo_doc', 'varchar(6)', "");

            $res = $this->existe_columna_tabla('tmp_compra','cod_sri_sust_comprobante');
            if ($res != true) $this->add_columna_tabla('tmp_compra','cod_sri_sust_comprobante', 'varchar(6)', "");

            $res = $this->existe_columna_tabla('compra','cod_sri_tipo_doc');
            if ($res != true) $this->add_columna_tabla('compra','cod_sri_tipo_doc', 'varchar(6)', "");

            $res = $this->existe_columna_tabla('compra','cod_sri_sust_comprobante');
            if ($res != true) $this->add_columna_tabla('compra','cod_sri_sust_comprobante', 'varchar(6)', "");

            $res = $this->existe_columna_tabla('compra','montoice');
            if ($res != true) $this->add_columna_tabla('compra','montoice', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('tmp_compra','montoice');
            if ($res != true) $this->add_columna_tabla('tmp_compra','montoice', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('producto','id_cto_retencion');
            if ($res != true) $this->add_columna_tabla('producto','id_cto_retencion', 'int', "");

            $res = $this->existe_tabla('compra_retencion');
            if ($res != true) $this->crea_tabla_compra_retencion();

            $res = $this->existe_tabla('compra_retencion_detrenta');
            if ($res != true) $this->crea_tabla_compra_retencion_detrenta();

            $res = $this->existe_tabla('compra_retencion_detrenta_tmp');
            if ($res != true) $this->crea_tabla_compra_retencion_detrenta_tmp();

            $res = $this->existe_tabla('compra_retencion_detiva');
            if ($res != true) $this->crea_tabla_compra_retencion_detiva();

            $this->tabla_contador_inserta(10, 'Retencion de Compra', '1', 'RETCOM');

            $res = $this->existe_columna_tabla('identificacion','codsri_compra');
            if ($res != true) $this->add_columna_tabla('identificacion','codsri_compra', 'varchar(2)', "");

            $res = $this->existe_columna_tabla('identificacion','codsri_venta');
            if ($res != true) $this->add_columna_tabla('identificacion','codsri_venta', 'varchar(2)', "");

            $this->actualiza_tabla_identificacion();      

            $res = $this->existe_columna_tabla('gastos','subtotalivacero');
            if ($res != true) $this->add_columna_tabla('gastos','subtotalivacero', 'decimal(11,2)', "update gastos set subtotalivacero = 0");

            $res = $this->existe_columna_tabla('gastos','subtotalivacerodesc');
            if ($res != true) $this->add_columna_tabla('gastos','subtotalivacerodesc', 'decimal(11,2)', "update gastos set 
              subtotalivacerodesc = 0");

            $res = $this->existe_columna_tabla('gastos','cod_sri_tipo_doc');
            if ($res != true) $this->add_columna_tabla('gastos','cod_sri_tipo_doc', 'varchar(6)', "");

            $res = $this->existe_columna_tabla('gastos','cod_sri_sust_comprobante');
            if ($res != true) $this->add_columna_tabla('gastos','cod_sri_sust_comprobante', 'varchar(6)', "");

            $res = $this->existe_columna_tabla('gastos','nro_autorizacion');
            if ($res != true) $this->add_columna_tabla('gastos','nro_autorizacion', 'varchar(255)', "update gastos set nro_autorizacion=''");

            $res = $this->existe_columna_tabla('gastos','id_sucursal');
            if ($res != true) $this->add_columna_tabla('gastos','id_sucursal','int',"update gastos set id_sucursal=ifnull((SELECT id_sucursal FROM sucursal LIMIT 1),1)");

            $res = $this->existe_tabla('gastos_retencion');
            if ($res != true) $this->crea_tabla_gastos_retencion();

            $res = $this->existe_tabla('gastos_retencion_detrenta');
            if ($res != true) $this->crea_tabla_gastos_retencion_detrenta();

            $res = $this->existe_tabla('gastos_retencion_detrenta_tmp');
            if ($res != true) $this->crea_tabla_gastos_retencion_detrenta_tmp();

            $res = $this->existe_tabla('gastos_retencion_detiva');
            if ($res != true) $this->crea_tabla_gastos_retencion_detiva();

            $res = $this->existe_tabla('venta_retencion');
            if ($res != true) $this->crea_tabla_venta_retencion();

            $res = $this->existe_tabla('venta_retencion_detrenta');
            if ($res != true) $this->crea_tabla_venta_retencion_detrenta();

            $res = $this->existe_tabla('venta_retencion_detrenta_tmp');
            if ($res != true) $this->crea_tabla_venta_retencion_detrenta_tmp();

            $res = $this->existe_tabla('venta_retencion_detiva');
            if ($res != true) $this->crea_tabla_venta_retencion_detiva();

            $res = $this->existe_columna_tabla('sucursal','id_empresa');
            if ($res != true) $this->add_columna_tabla('sucursal','id_empresa', 'int', "update sucursal set id_empresa=ifnull((SELECT id_emp FROM empresa LIMIT 1),1)");

            $res = $this->existe_columna_tabla('sucursal','activo');
            if ($res != true) $this->add_columna_tabla('sucursal','activo', 'int', "update sucursal set activo=1");

            $res = $this->existe_columna_tabla('sucursal','consecutivo_ordenservicio');
            if ($res != true) $this->add_columna_tabla('sucursal','consecutivo_ordenservicio', 'int', "update sucursal set consecutivo_ordenservicio=1");

            $res = $this->existe_tabla('permiso_sucursal');
            if ($res != true) $this->crea_tabla_permiso_sucursal();

            $res = $this->existe_tabla('permiso_almacen');
            if ($res != true) $this->crea_tabla_permiso_almacen();

            $res = $this->existe_tabla('punto_emision');
            if ($res != true) $this->crea_tabla_punto_emision();

            $res = $this->existe_tabla('caja_efectivo');
            if ($res != true) $this->crea_tabla_caja_efectivo();

            $res = $this->existe_tabla('permiso_cajaefectivo');
            if ($res != true) $this->crea_tabla_permiso_cajaefectivo();

            $res = $this->existe_columna_tabla('caja_movimiento','id_caja');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','id_caja', 'int', "update caja_movimiento set id_caja=1");

            $res = $this->existe_columna_tabla('venta','id_empresa');
            if ($res != true) $this->add_columna_tabla('venta','id_empresa', 'int', "update venta set id_empresa=ifnull((SELECT id_emp FROM empresa LIMIT 1),1)");

            $res = $this->existe_columna_tabla('venta','id_sucursal');
            if ($res != true) $this->add_columna_tabla('venta','id_sucursal', 'int', "update venta set id_sucursal=ifnull((SELECT id_sucursal FROM sucursal LIMIT 1),1)");

            $res = $this->existe_columna_tabla('venta','id_puntoemision');
            if ($res != true) $this->add_columna_tabla('venta','id_puntoemision', 'int', "update venta set id_puntoemision=ifnull((SELECT id_puntoemision FROM punto_emision LIMIT 1),1)");

            $res = $this->existe_columna_tabla('venta','id_caja');
            if ($res != true) $this->add_columna_tabla('venta','id_caja', 'int', "update venta set id_caja=ifnull((SELECT id_caja FROM caja_efectivo LIMIT 1),1)");

            $res = $this->existe_columna_tabla('venta_tmp','id_caja');
            if ($res != true) $this->add_columna_tabla('venta_tmp','id_caja', 'int', "update venta_tmp set id_caja=ifnull((SELECT id_caja FROM caja_efectivo LIMIT 1),1)");

            $res = $this->existe_columna_tabla('tmp_compra','id_sucursal');
            if ($res != true) $this->add_columna_tabla('tmp_compra','id_sucursal', 'int', "update tmp_compra set id_sucursal=ifnull((SELECT id_sucursal FROM sucursal LIMIT 1),1)");

            $res = $this->existe_columna_tabla('compra','id_sucursal');
            if ($res != true) $this->add_columna_tabla('compra','id_sucursal', 'int', "update compra set id_sucursal=ifnull((SELECT id_sucursal FROM sucursal LIMIT 1),1)");

            $res = $this->existe_columna_tabla('compra_det','id_detalle');
            if ($res != true) $this->compra_det_add_iddetalle();

            $res = $this->existe_columna_tabla('venta_detalle','id_detalle');
            if ($res != true) $this->venta_detalle_add_iddetalle();

            $res = $this->existe_columna_tabla('venta_detalle_tmp','id_detalle');
            if ($res != true) $this->venta_detalle_tmp_add_iddetalle();

            $res = $this->existe_tabla('producto_serie');
            if ($res != true) $this->crea_tabla_producto_serie();

            $res = $this->existe_tabla('producto_serie_tmp');
            if ($res != true) $this->crea_tabla_producto_serie_tmp();

            $this->chequea_version0_servicio();

            $res = $this->existe_tabla('servicio_config_general');
            if ($res != true) $this->crea_tabla_servicio_config_general();

            $res = $this->existe_tabla('servicio_estado');
            if ($res != true) $this->crea_tabla_servicio_estado();

            $res = $this->existe_tabla('servicio_config_detalle');
            if ($res != true) $this->crea_tabla_servicio_config_detalle();

            $res = $this->existe_tabla('servicio');
            if ($res != true) $this->crea_tabla_servicio();

            $res = $this->existe_tabla('servicio_detalle');
            if ($res != true) $this->crea_tabla_servicio_detalle();

            $res = $this->existe_tabla('servicio_subdetalle');
            if ($res != true) $this->crea_tabla_servicio_subdetalle();

            $res = $this->existe_tabla('servicio_producto');
            if ($res != true) $this->crea_tabla_servicio_producto();

            $res = $this->existe_columna_tabla('servicio_abono','monto');
            if ($res == true) {
                  $this->db->query("DROP TABLE IF EXISTS `servicio_abono`;");
            }
            $res = $this->existe_tabla('servicio_abono');
            if ($res != true) $this->crea_tabla_servicio_abono();

            $res = $this->existe_tabla('empleado');
            if ($res != true) $this->crea_tabla_empleado();

            $res = $this->existe_tabla('servicio_tmp');
            if ($res != true) $this->crea_tabla_servicio_tmp();

            $res = $this->existe_tabla('servicio_detalle_tmp');
            if ($res != true) $this->crea_tabla_servicio_detalle_tmp();

            $res = $this->existe_tabla('servicio_subdetalle_tmp');
            if ($res != true) $this->crea_tabla_servicio_subdetalle_tmp();

            $res = $this->existe_tabla('servicio_producto_tmp');
            if ($res != true) $this->crea_tabla_servicio_producto_tmp();

            $res = $this->existe_tabla('servicio_abono_tmp');
            if ($res != true) $this->crea_tabla_servicio_abono_tmp();

            $res = $this->existe_columna_tabla('venta_detalle_tmp','id_serie');
            if ($res != true) $this->add_columna_tabla('venta_detalle_tmp','id_serie', 'int', "");    

            $this->actualiza_procalm_cajaapertura_ins();

            $res = $this->existe_columna_tabla('sucursal','consecutivo_retencioncompra');
            if ($res != true) $this->add_columna_tabla('sucursal','consecutivo_retencioncompra', 'int', "update sucursal set consecutivo_retencioncompra=IFNULL((SELECT valor FROM contador WHERE id_contador=10),1)");

            $this->tabla_parametros_inserta(17, "Impuesto Adicional", "0");

            $this->tabla_parametros_inserta(18, "Imprimir Comanda al Facturar", "0");
            $this->tabla_parametros_inserta(19, "Habilita Numero Orden", "0");

            $res = $this->existe_columna_tabla('caja_efectivo','nro_orden');
            if ($res != true) $this->add_columna_tabla('caja_efectivo','nro_orden', 'int', "update caja_efectivo set nro_orden=1");

            $res = $this->existe_columna_tabla('pedido','nro_orden');
            if ($res != true) $this->add_columna_tabla('pedido','nro_orden','int',"");

            $res = $this->existe_columna_tabla('venta_tmp','nro_orden');
            if ($res != true) $this->add_columna_tabla('venta_tmp','nro_orden','int',"");

            $res = $this->existe_columna_tabla('venta','nro_orden');
            if ($res != true) $this->add_columna_tabla('venta','nro_orden','int',"");

            $res = $this->existe_columna_tabla('venta','cambio');
            if ($res != true) $this->add_columna_tabla('venta','cambio','decimal(11,2)',"update venta set cambio=0");

            $res = $this->existe_columna_tabla('pedido_detalle','est_comanda');
            if ($res != true) $this->add_columna_tabla('pedido_detalle','est_comanda', ' tinyint(1) DEFAULT 0', "");

            $res = $this->existe_tabla('notacredito');
            if ($res != true) $this->crea_tabla_notacredito();

            $res = $this->existe_tabla('notacredito_tmp');
            if ($res != true) $this->crea_tabla_notacredito_tmp();

            $res = $this->existe_tabla('notacredito_detalle');
            if ($res != true) $this->crea_tabla_notacredito_detalle();

            $res = $this->existe_tabla('notacredito_detalle_tmp');
            if ($res != true) $this->crea_tabla_notacredito_detalle_tmp();

            $res = $this->existe_tabla('notacredito_impuesto');
            if ($res != true) $this->crea_tabla_notacredito_impuesto();

            $res = $this->existe_columna_tabla('punto_emision','consecutivo_notacredito');
            if ($res != true) $this->add_columna_tabla('punto_emision','consecutivo_notacredito', 'int', "update punto_emision set consecutivo_notacredito=1");

            $res = $this->existe_columna_tabla('venta_tmp','id_vendedor');
            if ($res != true) $this->add_columna_tabla('venta_tmp','id_vendedor', 'int', "");

            $res = $this->existe_columna_tabla('venta','id_vendedor');
            if ($res != true) $this->add_columna_tabla('venta','id_vendedor', 'int', "");

            $res = $this->existe_columna_tabla('empresa','imagenlogo_emp');
            if ($res != true) $this->add_columna_tabla('empresa','imagenlogo_emp', 'longblob', "");

            $res = $this->existe_columna_tabla('clientes','id_vendedor');
            if ($res != true) $this->add_columna_tabla('clientes','id_vendedor', 'int', "");

            $this->tabla_parametros_inserta(20, "Factura Precio con IVA", "0");

            $res = $this->existe_columna_tabla('venta_detalle_tmp','precioconiva');
            if ($res != true) $this->add_columna_tabla('venta_detalle_tmp','precioconiva', ' decimal(15,4) DEFAULT 0', "");


            $this->tabla_parametros_inserta(21, "Habilita Asociacion Automatica Cliente Vendedor", "0");
            $this->tabla_parametros_inserta(22, "Cuota Minima Venta Asociacion Automatica Cliente Vendedor", "0");

            $this->actualiza_procalm_login();

            $res = $this->existe_columna_tabla('clientes','codigo');
            if ($res != true) $this->add_columna_tabla('clientes','codigo', 'varchar(255) DEFAULT NULL', "");

            $this->tabla_parametros_inserta(23, "Habilitar Codigo Cliente Venta", "0");

            $res = $this->existe_columna_tabla('empresa','logo_path');
            if ($res != true) $this->add_columna_tabla('empresa','logo_path', 'varchar(255)', "UPDATE empresa SET logo_path=''");

            $res = $this->existe_columna_tabla('venta_tmp','id_servicio');
            if ($res != true) $this->add_columna_tabla('venta_tmp','id_servicio', 'int', "");

            $res = $this->existe_columna_tabla('venta_formapago','fecha');
            if ($res == true) $this->upd_columna_tabla('venta_formapago','fecha', 'datetime');

            $res = $this->existe_columna_tabla('venta_formapago','id_cajapago');
            if ($res != true) $this->add_columna_tabla('venta_formapago','id_cajapago', 'int', "update venta_formapago set id_cajapago = (select id_caja from venta v where v.id_venta = venta_formapago.id_venta)");

            $res = $this->existe_columna_tabla('servicio_abono_tmp','id_cajapago');
            if ($res != true) $this->add_columna_tabla('servicio_abono_tmp','id_cajapago', 'int', "");

            $res = $this->existe_columna_tabla('servicio_abono_tmp','id_docpago');
            if ($res != true) $this->add_columna_tabla('servicio_abono_tmp','id_docpago', 'int', "");

            $res = $this->existe_columna_tabla('venta_tmp','comision_monto');
            if ($res != true) $this->add_columna_tabla('venta_tmp','comision_monto', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('venta_detalle_tmp','comision_monto');
            if ($res != true) $this->add_columna_tabla('venta_detalle_tmp','comision_monto', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('venta_detalle_tmp','precio_base');
            if ($res != true) $this->add_columna_tabla('venta_detalle_tmp','precio_base', 'decimal(15,4)', "");


            //molina
            $res = $this->existe_columna_tabla('proforma','id_sucursal');
            if ($res != true) $this->add_columna_tabla('proforma','id_sucursal', 'int', "update proforma set id_sucursal=ifnull((SELECT id_sucursal FROM sucursal WHERE activo = 1 LIMIT 1),1)");

            $res = $this->existe_columna_tabla('proforma_tmp','id_sucursal');
            if ($res != true) $this->add_columna_tabla('proforma_tmp','id_sucursal', 'int', "update proforma_tmp set id_sucursal=ifnull((SELECT id_sucursal FROM sucursal WHERE activo = 1 LIMIT 1),1)");

            $res = $this->existe_columna_tabla('servicio_producto_tmp','precio');
            if ($res != true) $this->add_columna_tabla('servicio_producto_tmp','precio', 'decimal(15,4)', "");

            $res = $this->existe_columna_tabla('servicio_producto','precio');
            if ($res != true) $this->add_columna_tabla('servicio_producto','precio', 'decimal(15,4)', "update servicio_producto set precio = ifnull((SELECT pro_precioventa FROM producto p WHERE p.pro_id = servicio_producto.id_producto),0)");

            $this->tabla_parametros_inserta(24, "Habilitar Descuento por Producto", "0");

            $res = $this->existe_columna_tabla('venta_detalle_tmp','porcdesc');
            if ($res != true) $this->add_columna_tabla('venta_detalle_tmp','porcdesc', 'decimal(15,2)', "update venta_detalle_tmp set porcdesc = 0;");

            $res = $this->existe_columna_tabla('venta_detalle','porcdesc');
            if ($res != true) $this->add_columna_tabla('venta_detalle','porcdesc', 'decimal(15,2)', "update venta_detalle set porcdesc = 0;");

            $res = $this->existe_columna_tabla('caja_movimiento','idusu_cierre');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','idusu_cierre', 'int', "update caja_movimiento set idusu_cierre=id_usuario");

            $res = $this->existe_columna_tabla('caja_movimiento','ventastotales');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','ventastotales', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','montonoefectivo');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','montonoefectivo', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','montoegreso');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','montoegreso', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','saldo');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','saldo', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','totalcaja');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','totalcaja', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','sobrante');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','sobrante', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','faltante');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','faltante', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','desefectivo');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','desefectivo', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','descheque');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','descheque', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','destarcre');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','destarcre', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','destardeb');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','destardeb', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','destarpre');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','destarpre', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','destransf');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','destransf', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','desdinele');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','desdinele', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','desotros');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','desotros', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('caja_movimiento','desvencre');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','desvencre', 'decimal(11,2)', "");

            // modificacion para editar detalle proforma factufácil
            $res = $this->existe_columna_tabla('proforma_detalle_tmp','descripcion');
            if ($res != true) $this->add_columna_tabla('proforma_detalle_tmp','descripcion', 'text', "update proforma_detalle_tmp set descripcion = (SELECT pro_nombre FROM producto WHERE pro_id = id_producto)");
            
            $res = $this->existe_columna_tabla('proforma_detalle','descripcion');
            if ($res != true) $this->add_columna_tabla('proforma_detalle','descripcion', 'text', "update proforma_detalle set descripcion = (SELECT pro_nombre FROM producto WHERE pro_id = id_producto)");

            $res = $this->existe_columna_tabla('sucursal','pie1_texto');
            if ($res != true) $this->add_columna_tabla('sucursal','pie1_texto', 'text', "update sucursal set pie1_texto = '';");

            $res = $this->existe_columna_tabla('sucursal','logo_detallepagina');
            if ($res != true) $this->add_columna_tabla('sucursal','logo_detallepagina', 'longblob', "");

            $res = $this->existe_columna_tabla('sucursal','logo_piepagina');
            if ($res != true) $this->add_columna_tabla('sucursal','logo_piepagina', 'longblob', "");

            $res = $this->existe_columna_tabla('producto','pro_garantia');
            if ($res != true) $this->add_columna_tabla('producto','pro_garantia', 'int', "update producto set pro_garantia = 0");

            $this->tabla_parametros_inserta(25, "Habilitar Impresion Local", "0");

            $this->actualiza_procalm_usuario_upd_acceso();

            $res = $this->existe_columna_tabla('venta_tmp','idmesa');
            if ($res != true) $this->add_columna_tabla('venta_tmp','idmesa', 'int', "");

            $res = $this->existe_tabla('garantia');
            if ($res != true) $this->crea_tabla_garantia();

            $res = $this->existe_tabla('producto_ventaserie_tmp');
            if ($res != true) $this->crea_tabla_producto_ventaserie_tmp();

            $res = $this->existe_columna_tabla('almacen','almacen_tipo');
            if ($res != true) $this->add_columna_tabla('almacen','almacen_tipo', 'tinyint(1)', "update almacen set almacen_tipo = 1");

            $this->actualiza_procalm_almacen_ins();
            $this->actualiza_procalm_almacen_upd();
            
            $res = $this->existe_indice_tabla('venta', 'index_ventafecha');
            if ($res != true) $this->add_indice_tabla('venta', 'index_ventafecha', 'fecha,estatus,id_cliente');

            $res = $this->existe_indice_tabla('venta_formapago', 'index_ventaformapago');
            if ($res != true) $this->add_indice_tabla('venta_formapago', 'index_ventaformapago', 'fecha,id_formapago,id_venta');

            $res = $this->existe_columna_tabla('proforma_detalle_tmp','porcdesc');
            if ($res != true) $this->add_columna_tabla('proforma_detalle_tmp','porcdesc', 'decimal(15,2)', "update proforma_detalle_tmp set porcdesc = 0;");

            $res = $this->existe_columna_tabla('proforma_detalle','porcdesc');
            if ($res != true) $this->add_columna_tabla('proforma_detalle','porcdesc', 'decimal(15,2)', "update proforma_detalle set porcdesc = 0;");

            $res = $this->existe_columna_tabla('venta','observaciones');
            if ($res != true) $this->add_columna_tabla('venta','observaciones', 'text', "");

            $res = $this->existe_columna_tabla('venta_tmp','observaciones');
            if ($res != true) $this->add_columna_tabla('venta_tmp','observaciones', 'text', "");

            $res = $this->existe_tabla('mesero');
            if ($res != true) $this->crea_tabla_mesero();

            $this->version_inserta(3000, 'Tratamiento de Garantia');

      } // Version 3000

      $res = $this->get_version();

      if ($res < 3001){

            $this->actualiza_procalm_proforma_facturar();

            $this->actualiza_procalm_proforma_sel_id();
            $this->actualiza_procalm_proforma_upd_id();
            $this->actualiza_procalm_proforma_ins();

            $res = $this->existe_columna_tabla('caja_movimiento','abonoservicio');
            if ($res != true) $this->add_columna_tabla('caja_movimiento','abonoservicio', 'decimal(11,2)', "update caja_movimiento set abonoservicio = 0;");

            //facturacion electronica
            $res = $this->existe_columna_tabla('compra_retencion','id_puntoemision');
            $strsql = "UPDATE compra_retencion SET 
                        id_puntoemision=IFNULL((SELECT id_puntoemision FROM punto_emision 
                                                  WHERE id_sucursal = (SELECT id_sucursal FROM compra 
                                                                         WHERE id_comp = compra_retencion.id_compra) 
                                                  LIMIT 1),1)";
            if ($res != true) $this->add_columna_tabla('compra_retencion','id_puntoemision', 'int', $strsql);

            $res = $this->existe_columna_tabla('punto_emision','consecutivo_retencioncompra');
            $strsql = "UPDATE punto_emision SET 
                        consecutivo_retencioncompra=IFNULL((SELECT consecutivo_retencioncompra FROM sucursal s 
                                                              WHERE s.id_sucursal = punto_emision.id_sucursal),1)";
            if ($res != true) $this->add_columna_tabla('punto_emision','consecutivo_retencioncompra', 'int', $strsql);

            $res = $this->existe_tabla('retenciongastoinfoestadosri');
            if ($res != true) $this->crea_tabla_retenciongastoinfoestadosri();

            $res = $this->existe_columna_tabla('gastos_retencion','id_puntoemision');
            $strsql = "UPDATE gastos_retencion SET 
                        id_puntoemision=IFNULL((SELECT id_puntoemision FROM punto_emision 
                                                  WHERE id_sucursal = (SELECT id_sucursal FROM gastos 
                                                                         WHERE id_gastos = gastos_retencion.id_gastos) 
                                                  LIMIT 1),1)";
            if ($res != true) $this->add_columna_tabla('gastos_retencion','id_puntoemision', 'int', $strsql);

            $res = $this->existe_tabla('sriguiaremisionencab');
            if ($res != true) $this->crea_tabla_sriguiaremisionencab();

            $res = $this->existe_tabla('sriguiaremisiondestino');
            if ($res != true) $this->crea_tabla_sriguiaremisiondestino();

            $res = $this->existe_tabla('sriguiaremisionproducto');
            if ($res != true) $this->crea_tabla_sriguiaremisionproducto();

            $res = $this->existe_columna_tabla('sriguiaremisionencab','id_puntoemision');
            $strsql = "UPDATE sriguiaremisionencab SET 
                        id_puntoemision=IFNULL((SELECT id_puntoemision FROM punto_emision LIMIT 1),1)";
            if ($res != true) $this->add_columna_tabla('sriguiaremisionencab','id_puntoemision', 'int', $strsql);

            $res = $this->existe_columna_tabla('punto_emision','consecutivo_guiaremision');
            if ($res != true) $this->add_columna_tabla('punto_emision','consecutivo_guiaremision', 'int', "update punto_emision set consecutivo_guiaremision=1");

            $res = $this->existe_tabla('tmp_guiaremisionproducto');
            if ($res != true) $this->crea_tabla_tmp_guiaremisionproducto();

            $res = $this->existe_tabla('facturainfoestadosri');
            if ($res != true) $this->crea_tabla_facturainfoestadosri();

            $res = $this->existe_tabla('guiaremisioninfoestadosri');
            if ($res != true) $this->crea_tabla_guiaremisioninfoestadosri();

            $res = $this->existe_tabla('notacreditoinfoestadosri');
            if ($res != true) $this->crea_tabla_notacreditoinfoestadosri();

            $res = $this->existe_tabla('retencioninfoestadosri');
            if ($res != true) $this->crea_tabla_retencioninfoestadosri();

            $res = $this->existe_columna_tabla('punto_emision','ambiente_factura');
            if ($res != true) $this->add_columna_tabla('punto_emision','ambiente_factura', 'int', "update punto_emision set ambiente_factura=1");

            $res = $this->existe_columna_tabla('punto_emision','ambiente_retencion');
            if ($res != true) $this->add_columna_tabla('punto_emision','ambiente_retencion', 'int', "update punto_emision set ambiente_retencion=1");

            $res = $this->existe_columna_tabla('punto_emision','ambiente_notacredito');
            if ($res != true) $this->add_columna_tabla('punto_emision','ambiente_notacredito', 'int', "update punto_emision set ambiente_notacredito=1");

            $res = $this->existe_columna_tabla('punto_emision','ambiente_guia');
            if ($res != true) $this->add_columna_tabla('punto_emision','ambiente_guia', 'int', "update punto_emision set ambiente_guia=1");

            $res = $this->existe_tabla('tokenfirma');
            if ($res != true) $this->crea_tabla_tokenfirma();

            $res = $this->existe_tabla('sritransportista');
            if ($res != true) $this->crea_tabla_sritransportista();
            
            $res = $this->existe_columna_tabla('empresa','tokenfirma');
            if ($res != true) $this->add_columna_tabla('empresa','tokenfirma', 'varchar(255)', "");

            $res = $this->existe_columna_tabla('venta_detalle','descripcion');
            if ($res != true) $this->add_columna_tabla('venta_detalle','descripcion', 'text', "update venta_detalle set descripcion = (SELECT pro_nombre FROM producto WHERE pro_id = id_producto)");

            $res = $this->existe_columna_tabla('venta_detalle_tmp','descripcion');
            if ($res != true) $this->add_columna_tabla('venta_detalle_tmp','descripcion', 'text', "update venta_detalle_tmp set descripcion = (SELECT pro_nombre FROM producto WHERE pro_id = id_producto)");

            $res = $this->existe_columna_tabla('punto_emision','enviosriguardar_factura');
            if ($res != true) $this->add_columna_tabla('punto_emision','enviosriguardar_factura', 'tinyint', "update punto_emision set enviosriguardar_factura=0");

            $res = $this->existe_columna_tabla('compra_abonos','numerodocumento');
            if ($res != true) $this->add_columna_tabla('compra_abonos','numerodocumento', 'varchar(100)', "");

            $res = $this->existe_columna_tabla('compra_abonos','descripciondocumento');
            if ($res != true) $this->add_columna_tabla('compra_abonos','descripciondocumento', 'varchar(100)', "");

            $res = $this->existe_columna_tabla('empresa','obligadocontabilidad');
            if ($res != true) $this->add_columna_tabla('empresa','obligadocontabilidad', 'tinyint', "update empresa set obligadocontabilidad = 0;");

            $this->version_inserta(3001, 'Facturacion Electronica');

      } // Version 3001


      $res = $this->get_version();

      if ($res < 3002){
            $res = $this->existe_columna_tabla('clientes','placa_matricula');
            if ($res != true) $this->add_columna_tabla('clientes','placa_matricula', 'varchar(25)', "");

            $res = $this->existe_columna_tabla('venta','placa_matricula');
            if ($res != true) $this->add_columna_tabla('venta','placa_matricula', 'varchar(25)', "");

            $res = $this->existe_columna_tabla('venta_tmp','placa_matricula');
            if ($res != true) $this->add_columna_tabla('venta_tmp','placa_matricula', 'varchar(25)', "");

            $this->tabla_parametros_inserta(26, "Habilita Variante Producto", "0");
            $this->tabla_parametros_inserta(27, "Habilita Ubicacion Detalle Venta", "0");
            $this->tabla_parametros_inserta(28, "Habilita Total IVA Detalle Venta", "0");
            $this->tabla_parametros_inserta(29, "Habilita Impresion Grafica", "0");
            $res = $this->existe_columna_tabla('producto','ubicacion');
            if ($res != true) $this->add_columna_tabla('producto','ubicacion', 'varchar(50)', "");
            $res = $this->existe_columna_tabla('producto','subsidio');
            if ($res != true) $this->add_columna_tabla('producto','subsidio', 'decimal(11,2)', "update producto set subsidio=0");
            $res = $this->existe_columna_tabla('venta_detalle','subsidio');
            if ($res != true) $this->add_columna_tabla('venta_detalle','subsidio', 'decimal(11,2)', "update venta_detalle set subsidio=0");
            $this->tabla_parametros_inserta(30, "Habilita Impresion Subsidio", "0");

            $this->tabla_parametros_inserta(31, "Habilita Promo Pedido", "0");
            $res = $this->existe_columna_tabla('pedido_detalle','promo');
            if ($res != true) $this->add_columna_tabla('pedido_detalle','promo', 'tinyint', "update pedido_detalle set promo=0");
            $res = $this->existe_columna_tabla('venta','idmesa');
            if ($res != true) $this->add_columna_tabla('venta','idmesa', 'int', "update venta set idmesa=0");
            $res = $this->existe_columna_tabla('pedido_detalle','id_almacen');
            if ($res != true) $this->add_columna_tabla('pedido_detalle','id_almacen', 'int', "");


            $res = $this->existe_tabla('devolucion_garantia');
            if ($res != true) $this->crea_tabla_devolucion_garantia();

            $res = $this->existe_tabla('devolucion_garantia_detalle');
            if ($res != true) $this->crea_tabla_devolucion_garantia_detalle();

            $res = $this->existe_columna_tabla('devolucion_garantia_detalle','iddetalleventa');
            if ($res != true) $this->add_columna_tabla('devolucion_garantia_detalle','iddetalleventa', 'int', "");

            $res = $this->existe_tabla('serie_tipomovimiento');
            if ($res != true) $this->crea_tabla_serie_tipomovimiento();

            $res = $this->existe_tabla('serie_productokardex');
            if ($res != true) $this->crea_tabla_serie_productokardex();

            $res = $this->existe_columna_tabla('sucursal','consecutivo_devoluciongarantia');
            if ($res != true) $this->add_columna_tabla('sucursal','consecutivo_devoluciongarantia', 'int', "update sucursal set consecutivo_devoluciongarantia=1");

            $res = $this->existe_columna_tabla('producto_serie','id_almacen');
            if ($res != true) $this->add_columna_tabla('producto_serie','id_almacen', 'int', '');

            $res = $this->existe_columna_tabla('producto_serie','id_estado');
            if ($res != true) $this->add_columna_tabla('producto_serie','id_estado', 'int', '');

            $res = $this->existe_columna_tabla('inventariodocumento_detalle','id_serie');
            if ($res != true) $this->add_columna_tabla('inventariodocumento_detalle','id_serie', 'int', '');

            $res = $this->existe_columna_tabla('tmp_movinv_det','id_serie');
            if ($res != true) $this->add_columna_tabla('tmp_movinv_det','id_serie', 'int', '');

            if ($this->producto_con_precio4decimal()){
                  $this->upd_columna_tabla('producto', 'pro_preciocompra', 'decimal(15,6)');
                  $this->upd_columna_tabla('producto', 'pro_precioventa', 'decimal(15,6)');

                  $query = $this->db->query("SELECT valor FROM parametros WHERE id=1;");
                  $result = $query->result();
                  $iva = $result[0]->valor + 1;

                  $this->db->query("UPDATE producto set 
                                      pro_preciocompra = round(round(pro_preciocompra * $iva, 4) / $iva, 6),
                                      pro_precioventa = round(round(pro_precioventa * $iva, 4) / $iva, 6)");

            }

            $this->upd_columna_tabla('venta_detalle', 'precio', 'decimal(15,6)');
            $this->upd_columna_tabla('venta_detalle_tmp', 'precio', 'decimal(15,6)');

            $this->tabla_parametros_inserta(32, "Cantidad Decimales Precio Venta", "2");
            $this->tabla_parametros_inserta(33, "Cantidad Decimales Cantidad Venta", "0");

            $this->upd_columna_tabla('prepro', 'monto', 'decimal(15,6)');
            $this->upd_columna_tabla('kardex', 'valorunitario', 'decimal(15,6)');

            $this->upd_columna_tabla('compra_det', 'precio_compra', 'decimal(15,6)');
            $this->upd_columna_tabla('tmp_compra_det', 'precio_compra', 'decimal(15,6)');

            $this->upd_columna_tabla('proforma_detalle', 'precio', 'decimal(15,6)');
            $this->upd_columna_tabla('proforma_detalle_tmp', 'precio', 'decimal(15,6)');

            $this->upd_columna_tabla('servicio_producto', 'precio', 'decimal(15,6)');
            $this->upd_columna_tabla('servicio_producto_tmp', 'precio', 'decimal(15,6)');

            $res = $this->existe_columna_tabla('precios','color');
            if ($res != true) $this->add_columna_tabla('precios','color', 'varchar(25)', '');

            $res = $this->existe_indice_tabla('clientes', 'codigo_producto');
            if ($res == true) $this->drop_indice_tabla('clientes', 'codigo_producto');

            $res = $this->existe_indice_tabla('cliente_tipoprecio', 'index_cliente_tipoprecio');
            if ($res != true) $this->add_indice_tabla('cliente_tipoprecio', 'index_cliente_tipoprecio', 'id_cliente, id_precio');


            $res = $this->existe_tabla('venta_detalle_serie_tmp');
            if ($res != true) $this->crea_tabla_venta_detalle_serie_tmp();

            $res = $this->existe_columna_tabla('devolucion_garantia_detalle','diasgarantia');
            if ($res != true) $this->add_columna_tabla('devolucion_garantia_detalle','diasgarantia', 'int', "UPDATE devolucion_garantia_detalle SET diasgarantia=0");

            $this->db->query("UPDATE serie_tipomovimiento SET estado = 'REPARADO/HABILITADO' WHERE id = 6");

            $res = $this->existe_columna_tabla('servicio_config_general','habilita_productofactura');
            if ($res != true) $this->add_columna_tabla('servicio_config_general','habilita_productofactura', 'tinyint(1)', "UPDATE servicio_config_general SET habilita_productofactura=1");

            $res = $this->existe_columna_tabla('servicio_producto_tmp','id_almacen');
            if ($res != true) $this->add_columna_tabla('servicio_producto_tmp','id_almacen', 'int', "UPDATE servicio_producto_tmp SET id_almacen=0");

            $res = $this->existe_columna_tabla('servicio_producto','id_almacen');
            if ($res != true) $this->add_columna_tabla('servicio_producto','id_almacen', 'int', "UPDATE servicio_producto SET id_almacen=0");

            $res = $this->existe_tabla('servicio_egresoinventario');
            if ($res != true) $this->crea_tabla_servicio_egresoinventario();

            $this->version_inserta(3002, 'Garantia');

      } // Version 3002


      $res = $this->get_version();

      if ($res < 3003){
            $res = $this->existe_columna_tabla('venta_detalle','costo_unitario');
            $strsql = "update venta_detalle set costo_unitario = (SELECT pro_preciocompra FROM producto WHERE pro_id=id_producto)";
            if ($res != true) $this->add_columna_tabla('venta_detalle','costo_unitario', ' decimal(11,4)', $strsql);

            $res = $this->existe_columna_tabla('venta_detalle','costo_total');
            $strsql = "update venta_detalle set costo_total = round(costo_unitario * cantidad, 2)";
            if ($res != true) $this->add_columna_tabla('venta_detalle','costo_total', ' decimal(11,2)', $strsql);

            $res = $this->existe_columna_tabla('sucursal','contabilizacion_automatica');
            if ($res != true) $this->add_columna_tabla('sucursal','contabilizacion_automatica', ' tinyint(1) DEFAULT 0', "UPDATE sucursal set contabilizacion_automatica=0");

            $this->actualiza_procalm_inventariomovimiento_guardar();

            $res = $this->existe_columna_tabla('compra_abonos','iddocpago');
            if ($res != true) $this->add_columna_tabla('compra_abonos','iddocpago', 'int(11)', "");

            $res = $this->existe_columna_tabla('gastos_abonos','iddocpago');
            if ($res != true) $this->add_columna_tabla('gastos_abonos','iddocpago', 'int(11)', "");

            $res = $this->existe_tabla('documento_pago');
            if ($res != true) $this->crea_tabla_documento_pago();

            // Depositos
            $res = $this->existe_tabla('deposito_tipo');
            if ($res != true) $this->crea_tabla_deposito_tipo();

            $res = $this->existe_tabla('deposito_efectivo');
            if ($res != true) $this->crea_tabla_deposito_efectivo();

            $res = $this->existe_columna_tabla('caja_chicaingreso','id_caja');
            if ($res != true) $this->add_columna_tabla('caja_chicaingreso','id_caja', 'int', "");
        
            $res = $this->existe_tabla('caja_chica_movimiento');
            if ($res != true) $this->crea_tabla_caja_chica_movimiento();

            $this->actualiza_procalm_cajachica_insapertura();
            $this->actualiza_procalm_cajachica_resumen();
            $this->actualiza_procalm_cajachica_cierre();
            $this->actualiza_procalm_cajachica_movimientos();

            $res = $this->existe_tabla('documento_pagodeposito');
            if ($res != true) $this->crea_tabla_documento_pagodeposito();
                  
            $this->actualiza_procalm_proveedor_ins();
            $this->actualiza_procalm_proveedor_upd();

            $this->tabla_parametros_inserta(34, "Habilita IVA en Nota de Venta", "0");

            $res = $this->existe_tabla('venta_credito_config');
            if ($res != true) $this->crea_tabla_venta_credito_config();

            $this->tabla_parametros_inserta(35, "Habilitar Envio de Correo al Autorizar Comprobante SRI", "0");
            $this->tabla_parametros_inserta(36, "Habilitar cambio de precio en Ventas solo a usuario administrador", "0");
            $this->tabla_parametros_inserta(37, "Predeterminar Pago en Efectivo en Venta", "1");

            $res = $this->existe_tabla('cliente_categoriaventa');
            if ($res != true) $this->crea_tabla_cliente_categoriaventa();

            $res = $this->existe_tabla('cliente_categoria_tipoprecio');
            if ($res != true) $this->crea_tabla_cliente_categoria_tipoprecio();

            $res = $this->existe_columna_tabla('clientes','id_categoriaventa');
            if ($res != true) $this->add_columna_tabla('clientes','id_categoriaventa', 'int(11)', "");

            $res = $this->existe_columna_tabla('servicio_config_detalle','activo');
            if ($res != true) $this->add_columna_tabla('servicio_config_detalle','activo', 'tinyint(1)', "update servicio_config_detalle set activo=1");
            
            $this->actualiza_procalm_cliente_ins();
            $this->actualiza_procalm_cliente_upd();

            $res = $this->existe_columna_tabla('cliente_categoriaventa','icono_path');
            if ($res != true) $this->add_columna_tabla('cliente_categoriaventa','icono_path', 'varchar(255)', "");

            $this->tabla_parametros_inserta(38, "Habilitar Asociacion Automatica de Categoria Venta Cliente", "0");

            $res = $this->existe_columna_tabla('tarjetas','comision_debito');
            if ($res != true) $this->add_columna_tabla('tarjetas','comision_debito', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('tarjetas','comision_credito');
            if ($res != true) $this->add_columna_tabla('tarjetas','comision_credito', 'decimal(11,2)', "");

            $res = $this->existe_columna_tabla('usu_sistemas','id_punto');
            if ($res != true) $this->add_columna_tabla('usu_sistemas','id_punto', 'int', "update usu_sistemas set id_punto=0");

            $res = $this->existe_columna_tabla('almacen','almacen_deposito');
            if ($res != true) $this->add_columna_tabla('almacen','almacen_deposito', 'int', "update almacen set almacen_deposito=0");

            $res = $this->existe_columna_tabla('almacen','almacen_idproducto');
            if ($res != true) $this->add_columna_tabla('almacen','almacen_idproducto', 'int', "");

            $res = $this->existe_columna_tabla('venta_detalle_tmp','id_almacen');
            if ($res != true) $this->add_columna_tabla('venta_detalle_tmp','id_almacen', 'int', "update venta_detalle_tmp set id_almacen=0");

            $res = $this->existe_columna_tabla('venta_detalle','id_almacen');
            if ($res != true) $this->add_columna_tabla('venta_detalle','id_almacen', 'int', "update venta_detalle set id_almacen=0");

            $res = $this->existe_columna_tabla('venta_tmp','correo_cliente');
            if ($res != true) $this->add_columna_tabla('venta_tmp','correo_cliente', 'varchar(255)', "");
            $res = $this->existe_columna_tabla('venta_tmp','ciu_cliente');
            if ($res != true) $this->add_columna_tabla('venta_tmp','ciu_cliente', 'varchar(255)', "");

            $res = $this->existe_columna_tabla('venta','correo_cliente');
            if ($res != true) $this->add_columna_tabla('venta','correo_cliente', 'varchar(255)', "");
            $res = $this->existe_columna_tabla('venta','ciu_cliente');
            if ($res != true) $this->add_columna_tabla('venta','ciu_cliente', 'varchar(255)', "");

            $this->actualiza_procalm_pedido_det_ins();

            $this->chequea_tabla_perfil();

            $res = $this->existe_columna_tabla('tmp_compra','id_almacen');
            if ($res != true) $this->add_columna_tabla('tmp_compra','id_almacen', 'int', "");

            $res = $this->existe_columna_tabla('tmp_compra','categoria');
            if ($res != true) $this->add_columna_tabla('tmp_compra','categoria', 'int', "");

            $res = $this->existe_columna_tabla('compra','id_almacen');
            $strupd = "SELECT id_almacen FROM almacen a INNER JOIN compra c on c.id_sucursal = a.sucursal_id LIMIT 1";
            if ($res != true) $this->add_columna_tabla('compra','id_almacen', 'int', $strupd);

            // Petshop
            $res = $this->existe_columna_tabla('clientes','fecha_nac');
            if ($res != true) $this->add_columna_tabla('clientes','fecha_nac', 'date', "");
            $res = $this->existe_columna_tabla('clientes','foto_cliente');
            if ($res != true) $this->add_columna_tabla('clientes','foto_cliente', 'varchar(255)', "");

            $res = $this->existe_columna_tabla('mesa','id_comanda');
            if ($res != true) $this->add_columna_tabla('mesa','id_comanda', 'int', "");

            $this->actualiza_procalm_mesa_ins();
            $this->actualiza_procalm_mesa_upd();

            $this->tabla_parametros_inserta(39, "Tipo Descuento por Producto en Venta", "1"); // 1-Porciento  0-Valor

            $res = $this->existe_columna_tabla('caja_movimiento','abonocredito');
            $strupd = "UPDATE caja_movimiento SET abonocredito = 0";
            if ($res != true) $this->add_columna_tabla('caja_movimiento','abonocredito', 'decimal(11,2)', $strupd);

            $this->actualiza_procalm_cajaapertura_upd();

            $res = $this->existe_columna_tabla('kardex','id_almacen');
            $strupd = "UPDATE kardex SET id_almacen = (SELECT almacen_id FROM almacen LIMIT 1)";
            if ($res != true) $this->add_columna_tabla('kardex','id_almacen', 'int', $strupd);

            $this->actualiza_procalm_kardexegreso_ins();
            $this->actualiza_procalm_kardexingreso_ins();

            $res = $this->existe_tabla('precio_compraventa');
            if ($res != true) $this->crea_tabla_precio_compraventa();

            $this->upd_columna_tabla('producto', 'subsidio', 'decimal(15,6)');
            $this->upd_columna_tabla('venta_detalle', 'subsidio', 'decimal(15,6)');
            
            $res = $this->existe_columna_tabla('servicio_config_detalle','mostrarenlistado');
            $strupd = "UPDATE servicio_config_detalle SET mostrarenlistado = 0";
            if ($res != true) 
               $this->add_columna_tabla('servicio_config_detalle','mostrarenlistado', 'tinyint(1)', $strupd);

            $this->actualiza_procalm_servicio_facturar();

            $res = $this->existe_tabla('color_skin');
            if ($res != true) $this->crea_tabla_color_skin();

            $res = $this->existe_tabla('sistema');
            if ($res != true) $this->crea_tabla_sistema();

            $this->version_inserta(3003, 'Configuracion Sistema');
      }      

      $res = $this->existe_columna_tabla('sucursal','logo_encab_path');
      if ($res != true) $this->actualiza_sucursal_logo();

      $res = $this->existe_tabla('venta_config_adicional');
      if ($res != true) $this->crea_tabla_venta_config_adicional();

      $res = $this->existe_tabla('venta_dato_adicional_tmp');
      if ($res != true) $this->crea_tabla_venta_dato_adicional_tmp();

      $res = $this->existe_tabla('venta_dato_adicional');
      if ($res != true) $this->crea_tabla_venta_dato_adicional();

      $this->actualiza_procalm_facturageneral_insnew();

      $res = $this->existe_columna_tabla('tmp_compra','doc_mod_cod_sri_tipo');
      if ($res != true) $this->add_columna_tabla('tmp_compra','doc_mod_cod_sri_tipo', ' varchar(3)', '');

      $res = $this->existe_columna_tabla('tmp_compra','doc_mod_numero');
      if ($res != true) $this->add_columna_tabla('tmp_compra','doc_mod_numero', ' varchar(25)', '');

      $res = $this->existe_columna_tabla('tmp_compra','doc_mod_autorizacion');
      if ($res != true) $this->add_columna_tabla('tmp_compra','doc_mod_autorizacion', ' varchar(255)', '');

      $res = $this->existe_columna_tabla('compra','doc_mod_cod_sri_tipo');
      if ($res != true) $this->add_columna_tabla('compra','doc_mod_cod_sri_tipo', ' varchar(3)', '');

      $res = $this->existe_columna_tabla('compra','doc_mod_numero');
      if ($res != true) $this->add_columna_tabla('compra','doc_mod_numero', ' varchar(25)', '');

      $res = $this->existe_columna_tabla('compra','doc_mod_autorizacion');
      if ($res != true) $this->add_columna_tabla('compra','doc_mod_autorizacion', ' varchar(255)', '');

      $res = $this->existe_columna_tabla('gastos','doc_mod_cod_sri_tipo');
      if ($res != true) $this->add_columna_tabla('gastos','doc_mod_cod_sri_tipo', ' varchar(3)', '');

      $res = $this->existe_columna_tabla('gastos','doc_mod_numero');
      if ($res != true) $this->add_columna_tabla('gastos','doc_mod_numero', ' varchar(25)', '');

      $res = $this->existe_columna_tabla('gastos','doc_mod_autorizacion');
      if ($res != true) $this->add_columna_tabla('gastos','doc_mod_autorizacion', ' varchar(255)', '');

      $this->actualiza_procalm_gastos_ins();
      $this->actualiza_procalm_gastos_upd();

      $res = $this->existe_columna_tabla('producto','imagen_path');
      if ($res != true) $this->actualiza_producto_imagen();

      $res = $this->existe_indice_tabla('clientes', 'index_cliente_nombre');
      if ($res != true) $this->add_indice_tabla('clientes', 'index_cliente_nombre', 'nom_cliente');

      $res = $this->existe_indice_tabla('producto', 'index_producto_nombre');
      if ($res != true) $this->add_indice_tabla('producto', 'index_producto_nombre', 'pro_nombre');

      $res = $this->existe_indice_tabla('venta_formapago', 'index_ventaformapago2');
      if ($res != true) $this->add_indice_tabla('venta_formapago', 'index_ventaformapago2', 'id_formapago,id_venta');

      $res = $this->existe_tabla('puntoventa_estado');
      if ($res != true) $this->crea_tabla_puntoventa_estado();

      $res = $this->existe_columna_tabla('mesa','id_estado');
      $strsql = "UPDATE mesa 
                   SET id_estado = CASE WHEN (SELECT COUNT(*) FROM pedido WHERE id_mesa = mesa.id_mesa) +
                                             (SELECT COUNT(*) FROM pedido_detalle WHERE id_mesa = mesa.id_mesa) > 0
                                     THEN 2 ELSE 1        
                                   END";
      if ($res != true) $this->add_columna_tabla('mesa','id_estado', ' tinyint(1)', $strsql);

      $this->tabla_parametros_inserta(40, "Etiqueta de Punto de Venta Singular", "Mesa");
      $this->tabla_parametros_inserta(41, "Etiqueta de Punto de Venta Singular", "Mesas");
      $this->tabla_parametros_inserta(42, "Estado de Punto Venta al Facturar", "1");

      $res = $this->existe_columna_tabla('pedido','observaciones');
      if ($res != true) $this->add_columna_tabla('pedido','observaciones', ' varchar(255)', '');

      $res = $this->existe_columna_tabla('sistema','icon_pedido');
      $strsql = "UPDATE sistema SET icon_pedido = 'fa-cutlery'"; //fa-bed para hotel
      if ($res != true) $this->add_columna_tabla('sistema','icon_pedido', ' varchar(255)', $strsql);

      $res = $this->existe_foreign_key('FK_caja_efectivo_deposito');
      if ($res == true) { $this->drop_foreign_key('caja_efectivo', 'FK_caja_efectivo_deposito'); }
      $this->upd_columna_tabla('caja_efectivo', 'id_caja', 'INT(11) NOT NULL');
      /*$this->add_foreign_key('caja_efectivo', 'FK_caja_efectivo_deposito', 
                             'id_caja', 'deposito_efectivo', 'id');*/
      return 1;
    }



    public function producto_con_precio4decimal(){
      $mydb = $this->db->database;
      $query = $this->db->query("select substr(COLUMN_TYPE,POSITION(',' in COLUMN_TYPE)+1,1) as cant
                                  from information_schema.columns
                                  where column_name = 'pro_precioventa'
                                    and table_name = 'producto'
                                    and table_schema = '$mydb'");
      $cant = 4;
      $r = $query->result();
      if ($r != null){
        $cant = $r[0]->cant;
      } 
      return $cant == 4;
    }

    public function crea_tabla_venta_tmp(){
      $query = $this->db->query("CREATE TABLE `venta_tmp` (
                                      `id_venta` int(11) NOT NULL AUTO_INCREMENT,
                                      `fecha` date DEFAULT NULL,
                                      `area` varchar(255) DEFAULT NULL,
                                      `mesa` varchar(255) DEFAULT NULL,
                                      `mesero` varchar(255) DEFAULT NULL,
                                      `tipo_doc` int(2) DEFAULT NULL,
                                      `nro_factura` varchar(255) DEFAULT NULL,
                                      `tipo_ident` varchar(255) DEFAULT NULL,
                                      `nro_ident` varchar(255) DEFAULT NULL,
                                      `nom_cliente` varchar(255) DEFAULT NULL,
                                      `telf_cliente` varchar(255) DEFAULT NULL,
                                      `dir_cliente` varchar(255) DEFAULT NULL,
                                      `correo_cliente` varchar(255) DEFAULT NULL,
                                      `ciu_cliente` varchar(255) DEFAULT NULL,
                                      `valiva` decimal(11,2) DEFAULT NULL,
                                      `subconiva` decimal(11,2) DEFAULT NULL,
                                      `subsiniva` decimal(11,2) DEFAULT NULL,
                                      `desc_monto` decimal(11,2) DEFAULT NULL,
                                      `descsubconiva` decimal(11,2) DEFAULT NULL,
                                      `descsubsiniva` decimal(11,2) DEFAULT NULL,
                                      `montoiva` decimal(11,2) DEFAULT NULL,
                                      `montototal` decimal(11,2) DEFAULT NULL,
                                      `fecharegistro` datetime DEFAULT CURRENT_TIMESTAMP,
                                      `idusu` int(11) DEFAULT NULL,
                                      `estatus` int(11) DEFAULT NULL,
                                      `idmesa` int(11) DEFAULT NULL,
                                      `id_cliente` int(11) DEFAULT NULL,
                                      `id_proforma` int(11) DEFAULT NULL,
                                      `id_caja` int(11) DEFAULT NULL,
                                      `nro_orden` int(11) DEFAULT NULL,
                                      `id_vendedor` int(11) DEFAULT NULL,
                                      `id_servicio` int(11) DEFAULT NULL,
                                      `comision_monto` decimal(11,2) DEFAULT NULL,
                                      `observaciones` text,
                                      `placa_matricula` varchar(25) DEFAULT NULL,
                                      PRIMARY KEY (`id_venta`) USING BTREE
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_venta_detalle_tmp(){
      $query = $this->db->query("CREATE TABLE `venta_detalle_tmp` (
                                      `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
                                      `id_venta` int(11) DEFAULT NULL,
                                      `id_producto` int(11) DEFAULT NULL,
                                      `cantidad` decimal(11,4) DEFAULT NULL,
                                      `precio` decimal(11,4) DEFAULT NULL,
                                      `subtotal` decimal(11,4) DEFAULT NULL,
                                      `iva` tinyint(1) DEFAULT NULL,
                                      `montoiva` decimal(11,2) DEFAULT NULL,
                                      `descmonto` decimal(11,2) DEFAULT NULL,
                                      `descsubtotal` decimal(11,4) DEFAULT NULL,
                                      `id_almacen` int(11) DEFAULT NULL,
                                      `tipprecio` int(11) DEFAULT NULL,
                                      `id_serie` int(11) DEFAULT NULL,
                                      `precioconiva` decimal(15,4) DEFAULT '0.0000',
                                      `comision_monto` decimal(11,2) DEFAULT NULL,
                                      `precio_base` decimal(15,4) DEFAULT NULL,
                                      `porcdesc` decimal(15,2) DEFAULT NULL,
                                      `descripcion` text,
                                      PRIMARY KEY (`id_detalle`) USING BTREE
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_tipobanco(){
      $query = $this->db->query("CREATE TABLE `tipobanco` (
                                  `id` int(11) NOT NULL,
                                  `nombre` varchar(100) DEFAULT NULL,
                                  PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $query = $this->db->query("INSERT INTO tipobanco (id, nombre) VALUES(1, 'Banco')");
        $query = $this->db->query("INSERT INTO tipobanco (id, nombre) VALUES(2, 'Cooperativa de Ahorro y Credito')");

    }

    public function crea_tabla_clausula(){
      $query = $this->db->query("CREATE TABLE `clausula` (`id_clausula` int(11) NOT NULL AUTO_INCREMENT, 
                                                          `desc_clausula` longtext, PRIMARY KEY (`id_clausula`)
                                                          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
      $this->db->query("INSERT INTO clausula (desc_clausula) VALUES('Clausulas');");

    }

    public function tabla_parametros_quitarautoinc(){
      $query = $this->db->query("ALTER TABLE parametros CHANGE COLUMN id id INT(11) NOT NULL");
    }

    public function tabla_parametros_inserta($id, $descripcion, $valor){
      $query = $this->db->query("SELECT count(*) as cant FROM parametros WHERE id = $id");
      $r = $query->result();
      if ($r[0]->cant == 0){
        $this->db->query("INSERT INTO parametros (id, descripcion, valor) VALUES($id, '$descripcion', '$valor')");
      } else {
        $this->db->query("UPDATE parametros set descripcion = '$descripcion' WHERE id = $id;");
      }
    }

    public function tabla_formapago_quitarautoinc(){
      $query = $this->db->query("ALTER TABLE formapago CHANGE COLUMN id_formapago id_formapago INT(11) NOT NULL");
    }


    public function tabla_formapago_inserta($id, $codigo, $nombre, $esinstrumentobanco, $estarjeta){
      $query = $this->db->query("SELECT count(*) as cant FROM formapago WHERE id_formapago = $id");
      $r = $query->result();
      if ($r[0]->cant == 0){
        $query = $this->db->query("INSERT INTO formapago (id_formapago, cod_formapago, nombre_formapago, esinstrumentobanco, estarjeta) 
                                     VALUES($id, '$codigo', '$nombre', $esinstrumentobanco, $estarjeta)");
      } else {
        $query = $this->db->query("UPDATE formapago set cod_formapago='$codigo', nombre_formapago='$nombre', 
                                                        esinstrumentobanco=$esinstrumentobanco, estarjeta=$estarjeta
                                     WHERE id_formapago=$id");
      }
    }

    public function tabla_ventaformapago_autoinc(){
      $query = $this->db->query("ALTER TABLE `venta_formapago` 
                                  ADD COLUMN `id` INT NOT NULL AUTO_INCREMENT FIRST,
                                  ADD PRIMARY KEY (`id`);");
    }

    public function crea_tabla_venta_formapagobanco(){
      $query = $this->db->query("CREATE TABLE `venta_formapagobanco` (
                                  `id_abono` int(11) NOT NULL,
                                  `id_banco` int(11) NOT NULL,
                                  `fechaemision` date DEFAULT NULL,
                                  `fechacobro` date DEFAULT NULL,
                                  `numerocuenta` varchar(100) DEFAULT NULL,
                                  `numerodocumento` varchar(100) DEFAULT NULL,
                                  `descripciondocumento` varchar(100) DEFAULT NULL,
                                  PRIMARY KEY (`id_abono`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_venta_formapagotarjeta(){
      $query = $this->db->query("CREATE TABLE `venta_formapagotarjeta` (
                                  `id_abono` int(11) NOT NULL,
                                  `id_tarjeta` int(11) NOT NULL,
                                  `id_banco` int(11) NOT NULL,
                                  `fechaemision` date DEFAULT NULL,
                                  `numerotarjeta` varchar(100) DEFAULT NULL,
                                  `numerodocumento` varchar(100) DEFAULT NULL,
                                  `descripciondocumento` varchar(100) DEFAULT NULL,
                                  PRIMARY KEY (`id_abono`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_venta_estadocredito(){
      $query = $this->db->query("CREATE TABLE `venta_estadocredito` (
                                  `id_estado` int(11) NOT NULL,
                                  `nombre_estado` varchar(100) DEFAULT NULL,
                                  PRIMARY KEY (`id_estado`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      $query = $this->db->query("INSERT INTO venta_estadocredito (id_estado, nombre_estado) VALUES(1, 'Pendiente')");
      $query = $this->db->query("INSERT INTO venta_estadocredito (id_estado, nombre_estado) VALUES(2, 'Cancelado')");
      $query = $this->db->query("INSERT INTO venta_estadocredito (id_estado, nombre_estado) VALUES(3, 'Mora')");
    }

    public function crea_tabla_venta_credito(){
      $query = $this->db->query("CREATE TABLE `venta_credito` (
                                  `id_venta` int(11) NOT NULL,
                                  `fechalimite` date DEFAULT NULL,
                                  `dias` int(11) DEFAULT NULL,
                                  `p100interes_credito` decimal(11,2) DEFAULT NULL,
                                  `p100interes_mora` decimal(11,2) DEFAULT NULL,
                                  `cantidadcuotas` int(11) NOT NULL,
                                  `abonoinicial` decimal(11,2) DEFAULT NULL,
                                  `montobasecredito` decimal(11,2) DEFAULT NULL,
                                  `montointerescredito` decimal(11,2) DEFAULT NULL,
                                  `montocredito` decimal(11,2) DEFAULT NULL,
                                  `montobasemora` decimal(11,2) DEFAULT NULL,
                                  `montointeresmora` decimal(11,2) DEFAULT NULL,
                                  `id_estado` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id_venta`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_venta_creditoabonoinicial(){
      $query = $this->db->query("CREATE TABLE `venta_creditoabonoinicial` (
                                  `id_abono` int(11) NOT NULL,
                                  PRIMARY KEY (`id_abono`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_venta_creditocuota(){
      $query = $this->db->query("CREATE TABLE `venta_creditocuota` (
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_venta` int(11) NOT NULL,
                                  `fechalimite` date DEFAULT NULL,
                                  `monto` decimal(11,2) DEFAULT NULL,
                                  `pagado` decimal(11,2) DEFAULT NULL,
                                  PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_venta_abonocreditocuota(){
      $query = $this->db->query("CREATE TABLE `venta_abonocreditocuota` (
                                  `id_cuota` int(11) NOT NULL,
                                  `id_abono` int(11) NOT NULL,
                                  `monto` decimal(11,2) DEFAULT NULL,
                                  PRIMARY KEY (`id_cuota`,`id_abono`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function tabla_contador_quitarautoinc(){
      $query = $this->db->query("ALTER TABLE contador CHANGE COLUMN id_contador id_contador INT(11) NOT NULL");
    }

    public function tabla_contador_inserta($id, $categoria, $valor, $prefijo){
      $query = $this->db->query("SELECT count(*) as cant FROM contador WHERE id_contador = $id");
      $r = $query->result();
      if ($r[0]->cant == 0){
        $query = $this->db->query("INSERT INTO contador (id_contador, categoria, valor, prefijo) VALUES($id, '$categoria', '$valor', '$prefijo')");
      } else {
        $query = $this->db->query("UPDATE contador SET prefijo='$prefijo' WHERE id_contador=$id");

      }
    }

    public function crea_tabla_tmp_formapago(){
      $query = $this->db->query("CREATE TABLE `formapago_tmp` (
                                  `idreg` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_venta` int(11) NOT NULL,
                                  `id_formapago` int(11) NOT NULL,
                                  `id_banco` int(11) NOT NULL,
                                  `numerocuenta` varchar(100) DEFAULT NULL,
                                  `id_tarjeta` int(11) NOT NULL,
                                  `numerotarjeta` varchar(100) DEFAULT NULL,
                                  `fechaemision` date DEFAULT NULL,
                                  `fechacobro` date DEFAULT NULL,
                                  `numerodocumento` varchar(100) DEFAULT NULL,
                                  `descripciondocumento` varchar(100) DEFAULT NULL,
                                  `monto` decimal(11,2) DEFAULT NULL,
                                  `id_tipcancelacion` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`idreg`)
                                ) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_venta_credito_tmp(){
      $query = $this->db->query("CREATE TABLE `venta_credito_tmp` (
                                  `id_venta` int(11) NOT NULL,
                                  `fechalimite` date DEFAULT NULL,
                                  `dias` int(11) DEFAULT NULL,
                                  `p100interes_credito` decimal(11,2) DEFAULT NULL,
                                  `p100interes_mora` decimal(11,2) DEFAULT NULL,
                                  `cantidadcuotas` int(11) NOT NULL,
                                  `abonoinicial` decimal(11,2) DEFAULT NULL,
                                  `montobasecredito` decimal(11,2) DEFAULT NULL,
                                  `montointerescredito` decimal(11,2) DEFAULT NULL,
                                  `montocredito` decimal(11,2) DEFAULT NULL,
                                  `montobasemora` decimal(11,2) DEFAULT NULL,
                                  `montointeresmora` decimal(11,2) DEFAULT NULL,
                                  `id_estado` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id_venta`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_caja_egreso(){
      $query = $this->db->query("CREATE TABLE `caja_egreso` (
                                  `idreg` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_mov` int(11) DEFAULT NULL,
                                  `id_usu` int(11) DEFAULT NULL,
                                  `descripcion` text,
                                  `monto` decimal(11,2) DEFAULT NULL,
                                  `emisor` varchar(255) DEFAULT NULL,
                                  `receptor` varchar(255) DEFAULT NULL,
                                  `nroegreso` int(11) DEFAULT NULL,
                                  `fecharegistro` datetime DEFAULT CURRENT_TIMESTAMP,
                                  PRIMARY KEY (`idreg`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_proforma(){
      $query = $this->db->query("CREATE TABLE `proforma` (
                                  `id_proforma` int(11) NOT NULL AUTO_INCREMENT,
                                  `fecha` date DEFAULT NULL,
                                  `nro_proforma` varchar(255) DEFAULT NULL,
                                  `id_cliente` int(11) DEFAULT NULL,
                                  `id_vendedor` int(11) DEFAULT NULL,
                                  `id_puntoventa` int(11) DEFAULT NULL,
                                  `valiva` decimal(11,2) DEFAULT NULL,
                                  `subconiva` decimal(11,2) DEFAULT NULL,
                                  `subsiniva` decimal(11,2) DEFAULT NULL,
                                  `desc_monto` decimal(11,2) DEFAULT NULL,
                                  `descsubconiva` decimal(11,2) DEFAULT NULL,
                                  `descsubsiniva` decimal(11,2) DEFAULT NULL,
                                  `montoiva` decimal(11,2) DEFAULT NULL,
                                  `montototal` decimal(11,2) DEFAULT NULL,
                                  `fecharegistro` datetime DEFAULT CURRENT_TIMESTAMP,
                                  `idusu` int(11) DEFAULT NULL,
                                  `id_factura` int(11) DEFAULT NULL,
                                  `observaciones` text,
                                  PRIMARY KEY (`id_proforma`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_proformatmp(){
      $query = $this->db->query("CREATE TABLE `proforma_tmp` (
                                  `id_proforma` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_proftmp` int(11) DEFAULT NULL,
                                  `fecha` date DEFAULT NULL,
                                  `nro_proforma` varchar(255) DEFAULT NULL,
                                  `id_cliente` int(11) DEFAULT NULL,
                                  `id_vendedor` int(11) DEFAULT NULL,
                                  `id_puntoventa` int(11) DEFAULT NULL,
                                  `valiva` decimal(11,2) DEFAULT NULL,
                                  `subconiva` decimal(11,2) DEFAULT NULL,
                                  `subsiniva` decimal(11,2) DEFAULT NULL,
                                  `desc_monto` decimal(11,2) DEFAULT NULL,
                                  `descsubconiva` decimal(11,2) DEFAULT NULL,
                                  `descsubsiniva` decimal(11,2) DEFAULT NULL,
                                  `montoiva` decimal(11,2) DEFAULT NULL,
                                  `montototal` decimal(11,2) DEFAULT NULL,
                                  `fecharegistro` datetime DEFAULT CURRENT_TIMESTAMP,
                                  `idusu` int(11) DEFAULT NULL,
                                  `id_factura` int(11) DEFAULT NULL,
                                  `observaciones` text,
                                  PRIMARY KEY (`id_proforma`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_proforma_detalle(){
      $query = $this->db->query("CREATE TABLE `proforma_detalle` (
                                  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_proforma` int(11) DEFAULT NULL,
                                  `id_producto` int(11) DEFAULT NULL,
                                  `cantidad` decimal(11,4) DEFAULT NULL,
                                  `precio` decimal(11,4) DEFAULT NULL,
                                  `subtotal` decimal(11,4) DEFAULT NULL,
                                  `iva` tinyint(1) DEFAULT NULL,
                                  `montoiva` decimal(11,2) DEFAULT NULL,
                                  `descmonto` decimal(11,2) DEFAULT NULL,
                                  `descsubtotal` decimal(11,4) DEFAULT NULL,
                                  `id_almacen` int(11) DEFAULT NULL,
                                  `tipprecio` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id_detalle`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_proforma_detalletmp(){
      $query = $this->db->query("CREATE TABLE `proforma_detalle_tmp` (
                                  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_proforma` int(11) DEFAULT NULL,
                                  `id_producto` int(11) DEFAULT NULL,
                                  `cantidad` decimal(11,4) DEFAULT NULL,
                                  `precio` decimal(11,4) DEFAULT NULL,
                                  `subtotal` decimal(11,4) DEFAULT NULL,
                                  `iva` tinyint(1) DEFAULT NULL,
                                  `montoiva` decimal(11,2) DEFAULT NULL,
                                  `descmonto` decimal(11,2) DEFAULT NULL,
                                  `descsubtotal` decimal(11,4) DEFAULT NULL,
                                  `id_almacen` int(11) DEFAULT NULL,
                                  `tipprecio` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id_detalle`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_venta_creditocuota_tmp(){
      $query = $this->db->query("CREATE TABLE `venta_creditocuota_tmp` (
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_venta` int(11) NOT NULL,
                                  `fechalimite` date DEFAULT NULL,
                                  `monto` decimal(11,2) DEFAULT NULL,
                                  `pagado` decimal(11,2) DEFAULT NULL,
                                  PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_kardex(){
      $query = $this->db->query("CREATE TABLE `kardex` (
                                  `id_kardex` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_producto` int(11) NOT NULL,
                                  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                  `documento` varchar(255) DEFAULT NULL,
                                  `detalle` varchar(255) DEFAULT NULL,
                                  `tipomovimiento` int(11) DEFAULT NULL,
                                  `cantidad` decimal(11,2) DEFAULT NULL,
                                  `valorunitario` decimal(15,4) DEFAULT NULL,
                                  `costototal` decimal(11,2) DEFAULT NULL,
                                  `saldocantidad` decimal(11,2) DEFAULT NULL,
                                  `saldovalorunitario` decimal(15,4) DEFAULT NULL,
                                  `saldocostototal` decimal(11,2) DEFAULT NULL,
                                  `idunidadstock` int(11) DEFAULT NULL,
                                  `idusuario` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id_kardex`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_inventariodocumento(){
      $query = $this->db->query("CREATE TABLE `inventariodocumento` (
                                  `id_documento` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_tipodoc` int(11) NOT NULL,
                                  `id_usu` int(11) DEFAULT NULL,
                                  `fecha` date DEFAULT NULL,
                                  `nro_documento` varchar(255) DEFAULT NULL,
                                  `descripcion` varchar(255) DEFAULT NULL,
                                  `total` decimal(11,2) DEFAULT NULL,
                                  `estatus` int(11) DEFAULT NULL,
                                  `fecharegistro` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                  `id_almacen` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id_documento`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }


    public function crea_tabla_inventariodocumento_detalle(){
      $query = $this->db->query("CREATE TABLE `inventariodocumento_detalle` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `id_documento` int(11) DEFAULT NULL,
                                    `id_pro` int(11) DEFAULT NULL,
                                    `precio_compra` decimal(11,4) DEFAULT NULL,
                                    `cantidad` decimal(11,2) DEFAULT NULL,
                                    `id_unimed` int(11) DEFAULT NULL,
                                    `montototal` decimal(11,2) DEFAULT NULL,
                                    PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_tmp_movinv(){
      $query = $this->db->query("CREATE TABLE `tmp_movinv` (
                                    `id_mov` int(11) NOT NULL AUTO_INCREMENT,
                                    `id_usu` int(11) DEFAULT NULL,
                                    `fecha` date DEFAULT NULL,
                                    `nro_documento` varchar(255) DEFAULT NULL,
                                    `descripcion` varchar(255) DEFAULT NULL,
                                    `montototal` decimal(11,2) DEFAULT NULL,
                                    `id_almacen` int(11) DEFAULT NULL,
                                    `id_tipodoc` int(11) DEFAULT NULL,
                                    PRIMARY KEY (`id_mov`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_tmp_movinv_det(){
      $query = $this->db->query("CREATE TABLE `tmp_movinv_det` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `id_mov` int(11) DEFAULT NULL,
                                    `id_pro` int(11) DEFAULT NULL,
                                    `precio_compra` decimal(11,4) DEFAULT NULL,
                                    `existencia` decimal(11,2) DEFAULT NULL,
                                    `cantidad` decimal(11,2) DEFAULT NULL,
                                    `id_unimed` int(11) DEFAULT NULL,
                                    `montototal` decimal(11,2) DEFAULT NULL,
                                    PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_inventariodocumtransfer(){
      $query = $this->db->query("CREATE TABLE `inventariodocumtransfer` (
                                  `id_doctrans` int(11) NOT NULL,
                                  `id_almacen` int(11) NOT NULL,
                                  `id_docingreso` int(11) NOT NULL,
                                  PRIMARY KEY (`id_doctrans`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_usuprecio(){
      $query = $this->db->query("CREATE TABLE `usuprecio` (
                                  `idpreusu` int(11) NOT NULL AUTO_INCREMENT,
                                  `idusu` int(11) NOT NULL,
                                  `idpre` int(11) NOT NULL,
                                  `estatus` tinyint(1) NOT NULL,
                                  PRIMARY KEY (`idpreusu`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_concepto_retencion(){
      $query = $this->db->query("CREATE TABLE `concepto_retencion` (
                                  `id_cto_retencion` int(11) NOT NULL AUTO_INCREMENT,
                                  `cod_cto_retencion` varchar(255) NOT NULL,
                                  `descripcion_retencion` varchar(255) DEFAULT NULL,
                                  `porciento_cto_retencion` decimal(11,2) DEFAULT NULL,
                                  `editablecompra` tinyint(1) DEFAULT NULL,
                                  PRIMARY KEY (`id_cto_retencion`,`cod_cto_retencion`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");

      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('1', '303', 'Honorarios profesionales y demás pagos por servicios relacionados con el título profesional', '10.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('2', '304', 'Servicios predomina el intelecto no relacionados con el título profesional', '8.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('3', '304A', 'Comisiones y demás pagos por servicios predomina intelecto no relacionados con el título profesional', '8.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('4', '304B', 'Pagos a notarios y registradores de la propiedad y mercantil por sus actividades ejercidas como tales', '8.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('5', '304C', 'Pagos a deportistas, entrenadores, árbitros, miembros del cuerpo técnico por sus actividades ejercidas como tales', '8.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('6', '304D', 'Pagos a artistas por sus actividades ejercidas como tales', '8.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('7', '304E', 'Honorarios y demás pagos por servicios de docencia', '8.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('8', '307', 'Servicios predomina la mano de obra', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('9', '308', 'Utilización o aprovechamiento de la imagen o renombre', '10.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('10', '309', 'Servicios prestados por medios de comunicación y agencias de publicidad', '1.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('11', '310', 'Servicio de transporte privado de pasajeros o transporte público o privado de carga', '1.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('12', '311', 'Por pagos a través de liquidación de compra (nivel cultural o rusticidad) **', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('13', '312', 'Transferencia de bienes muebles de naturaleza corporal', '1.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('14', '312A', 'Compra de bienes de origen agrícola, avícola, pecuario, apícola, cunícula, bioacuático, y forestal', '1.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('15', '314A', 'Regalías por concepto de franquicias de acuerdo a Ley de Propiedad Intelectual - pago a personas naturales', '8.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('16', '314B', 'Cánones, derechos de autor,  marcas, patentes y similares de acuerdo a Ley de Propiedad Intelectual – pago a personas naturales', '8.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('17', '314C', 'Regalías por concepto de franquicias de acuerdo a Ley de Propiedad Intelectual  - pago a sociedades', '8.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('18', '314D', 'Cánones, derechos de autor,  marcas, patentes y similares de acuerdo a Ley de Propiedad Intelectual – pago a sociedades', '8.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('19', '319', 'Cuotas de arrendamiento mercantil, inclusive la de opción de compra', '1.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('20', '320', 'Por arrendamiento bienes inmuebles', '8.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('21', '322', 'Seguros y reaseguros (primas y cesiones)', '1.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('22', '323', 'Por rendimientos financieros pagados a naturales y sociedades  (No a IFIs)', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('23', '323A', 'Por RF: depósitos Cta. Corriente', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('24', '323B1', 'Por RF:  depósitos Cta. Ahorros Sociedades', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('25', '323E', 'Por RF: depósito a plazo fijo  gravados', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('26', '323E2', 'Por RF: depósito a plazo fijo exentos ***', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('27', '323F', 'Por rendimientos financieros: operaciones de reporto - repos', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('28', '323G', 'Por RF: inversiones (captaciones) rendimientos distintos de aquellos pagados a IFIs', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('29', '323H', 'Por RF: obligaciones', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('30', '323I', 'Por RF: bonos convertible en acciones', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('31', '323 M', 'Por RF: Inversiones en títulos valores en renta fija gravados ', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('32', '323 N', 'Por RF: Inversiones en títulos valores en renta fija exentos', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('33', '323 O', 'Por RF: Intereses pagados a bancos y otras entidades sometidas al control de la Superintendencia de Bancos y de la Economía Popular y Solidaria', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('34', '323 P', 'Por RF: Intereses pagados por entidades del sector público a favor de sujetos pasivos', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('35', '323Q', 'Por RF: Otros intereses y rendimientos financieros gravados ', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('36', '323R', 'Por RF: Otros intereses y rendimientos financieros exentos', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('37', '324A', 'Por RF: Intereses y comisiones en operaciones de crédito entre instituciones del sistema financiero y entidades economía popular y solidaria.', '1.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('38', '324B', 'Por RF: Por inversiones entre instituciones del sistema financiero y entidades economía popular y solidaria, incluso cuando el BCE actúe como intermediario.', '1.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('39', '325', 'Anticipo dividendos a residentes o establecidos en el Ecuador', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('40', '325A', 'Dividendos anticipados préstamos accionistas, beneficiarios o partìcipes a residentes o establecidos en el Ecuador', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('41', '326', 'Dividendos distribuidos que correspondan al impuesto a la renta único establecido en el art. 27 de la LRTI (Tabla art. 36 menos crédito tributario pro dividendos: julio 2015)', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('42', '327', 'Dividendos distribuidos a personas naturales residentes', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('43', '328', 'Dividendos distribuidos a sociedades residentes', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('44', '329', 'Dividendos distribuidos a fideicomisos residentes', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('45', '330', 'Dividendos gravados distribuidos en acciones (reinversión de utilidades sin derecho a reducción tarifa IR)', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('46', '331', 'Dividendos exentos distribuidos en acciones (reinversión de utilidades con derecho a reducción tarifa IR) ', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('47', '332', 'Otras compras de bienes y servicios no sujetas a retención', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('48', '332A', 'Enajenación de derechos representativos de capital y otros derechos exentos (mayo 2016)', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('49', '332B', 'Compra de bienes inmuebles', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('50', '332C', 'Transporte público de pasajeros', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('51', '332D', 'Pagos en el país por transporte de pasajeros o transporte internacional de carga, a compañías nacionales o extranjeras de aviación o marítimas', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('52', '332E', 'Valores entregados por las cooperativas de transporte a sus socios', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('53', '332F', 'Compraventa de divisas distintas al dólar de los Estados Unidos de América', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('54', '332G', 'Pagos con tarjeta de crédito ', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('55', '332H', 'Pago al exterior tarjeta de crédito reportada por la Emisora de tarjeta de crédito, solo RECAP', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('56', '333', 'Enajenación de derechos representativos de capital y otros derechos cotizados en bolsa ecuatoriana', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('57', '334', 'Enajenación de derechos representativos de capital y otros derechos no cotizados en bolsa ecuatoriana', '1.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('58', '335', 'Por loterías, rifas, apuestas y similares', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('59', '336', 'Por venta de combustibles a comercializadoras', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('60', '337', 'Por venta de combustibles a distribuidores', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('61', '338', 'Compra local de banano a productor', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('62', '339', 'Liquidación impuesto único a la venta local de banano de producción propia', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('63', '340', 'Impuesto único a la exportación de banano de producción propia - componente 1', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('64', '341', 'Impuesto único a la exportación de banano de producción propia - componente 2', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('65', '342', 'Impuesto único a la exportación de banano producido por terceros', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('66', '343A', 'Por energía eléctrica', '1.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('67', '343B', 'Por actividades de construcción de obra material inmueble, urbanización, lotización o actividades similares', '1.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('68', '344', 'Otras retenciones aplicables el 2%', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('69', '344A', 'Pago local tarjeta de crédito reportada por la Emisora de tarjeta de crédito, solo RECAP', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('70', '346A', 'Ganancias de capital', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('71', '347', 'Donaciones en dinero -Impuesto a la donaciones ', '2.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('72', '348', 'Retención a cargo del propio sujeto pasivo por la exportación de concentrados y/o elementos metálicos', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('73', '349', 'Retención a cargo del propio sujeto pasivo por la comercialización de productos forestales', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('74', '500', 'Pago al exterior - Rentas Inmobiliarias', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('75', '501', 'Pago al exterior - Beneficios Empresariales', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('76', '502', 'Pago al exterior - Servicios Empresariales', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('77', '503', 'Pago al exterior - Navegación Marítima y/o aérea', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('78', '504', 'Pago al exterior- Dividendos distribuidos a personas naturales', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('79', '504A', 'Pago al exterior - Dividendos a sociedades', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('80', '504B', 'Pago al exterior - Anticipo dividendos (excepto paraísos fiscales o de régimen de menor imposición)', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('81', '504C', 'Pago al exterior - Dividendos anticipados préstamos accionistas, beneficiarios o partìcipes (paraisos fiscales o regímenes de menor imposición)', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('82', '504D', 'Pago al exterior - Dividendos a fideicomisos', '0.00', '1');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('83', '504F', 'Pago al exterior - Dividendos a sociedades  (paraísos fiscales)', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('84', '504G', 'Pago al exterior - Anticipo dividendos  (paraísos fiscales)', '0.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('85', '504H', 'Pago al exterior - Dividendos a fideicomisos  (paraísos fiscales)', '13.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('86', '505', 'Pago al exterior - Rendimientos financieros', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('87', '505A', 'Pago al exterior – Intereses de créditos de Instituciones Financieras del exterior', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('88', '505B', 'Pago al exterior – Intereses de créditos de gobierno a gobierno', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('89', '505C', 'Pago al exterior – Intereses de créditos de organismos multilaterales', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('90', '505D', 'Pago al exterior - Intereses por financiamiento de proveedores externos', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('91', '505E', 'Pago al exterior - Intereses de otros créditos externos', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('92', '505F', 'Pago al exterior - Otros Intereses y Rendimientos Financieros', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('93', '509', 'Pago al exterior - Cánones, derechos de autor,  marcas, patentes y similares', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('94', '509A', 'Pago al exterior - Regalías por concepto de franquicias', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('95', '510', 'Pago al exterior - Ganancias de capital', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('96', '511', 'Pago al exterior - Servicios profesionales independientes', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('97', '512', 'Pago al exterior - Servicios profesionales dependientes', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('98', '513', 'Pago al exterior - Artistas', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('99', '513A', 'Pago al exterior - Deportistas', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('100', '514', 'Pago al exterior - Participación de consejeros', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('101', '515', 'Pago al exterior - Entretenimiento Público', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('102', '516', 'Pago al exterior - Pensiones', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('103', '517', 'Pago al exterior - Reembolso de Gastos', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('104', '518', 'Pago al exterior - Funciones Públicas', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('105', '519', 'Pago al exterior - Estudiantes', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('106', '520', 'Pago al exterior - Otros conceptos de ingresos gravados', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('107', '520A', 'Pago al exterior - Pago a proveedores de servicios hoteleros y turísticos en el exterior', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('108', '520B', 'Pago al exterior - Arrendamientos mercantil internacional', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('109', '520D', 'Pago al exterior - Comisiones por exportaciones y por promoción de turismo receptivo', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('110', '520E', 'Pago al exterior - Por las empresas de transporte marítimo o aéreo y por empresas pesqueras de alta mar, por su actividad.', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('111', '520F', 'Pago al exterior - Por las agencias internacionales de prensa', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('112', '520G', 'Pago al exterior - Contratos de fletamento de naves para empresas de transporte aéreo o marítimo internacional', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('113', '521', 'Pago al exterior - Enajenación de derechos representativos de capital y otros derechos ', '5.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('114', '522A', 'Pago al exterior - Servicios técnicos, administrativos o de consultoría y regalías con convenio de doble tributación', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('115', '523A', 'Pago al exterior - Seguros y reaseguros (primas y cesiones)  con convenio de doble tributación', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('116', '524', 'Pago al exterior - Otros pagos al exterior no sujetos a retención ', '22.00', '0');");
      $this->db->query("INSERT INTO `concepto_retencion` VALUES ('117', '525', 'Pago al exterior - Donaciones en dinero -Impuesto a la donaciones', '0.00', '1');");

    }

    public function crea_tabla_porcentaje_retencion_iva(){
      $query = $this->db->query("CREATE TABLE `porcentaje_retencion_iva` (
                                  `id_porc_ret_iva` int(11) NOT NULL AUTO_INCREMENT,
                                  `codigo` varchar(255) DEFAULT NULL,
                                  `porcentaje` decimal(11,2) DEFAULT NULL,
                                  PRIMARY KEY (`id_porc_ret_iva`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
      $this->db->query("INSERT INTO porcentaje_retencion_iva (codigo, porcentaje) VALUES ('9',  10)");
      $this->db->query("INSERT INTO porcentaje_retencion_iva (codigo, porcentaje) VALUES ('10', 20)");
      $this->db->query("INSERT INTO porcentaje_retencion_iva (codigo, porcentaje) VALUES ('1',  30)");
      $this->db->query("INSERT INTO porcentaje_retencion_iva (codigo, porcentaje) VALUES ('11', 50)");
      $this->db->query("INSERT INTO porcentaje_retencion_iva (codigo, porcentaje) VALUES ('2',  70)");
      $this->db->query("INSERT INTO porcentaje_retencion_iva (codigo, porcentaje) VALUES ('3',  100)");
      $this->db->query("INSERT INTO porcentaje_retencion_iva (codigo, porcentaje) VALUES ('7',  0)");
    }

    public function crea_tabla_sri_sust_comprobante(){
      $query = $this->db->query("CREATE TABLE `sri_sust_comprobante` (
                                  `id_sri_sust_comprobante` int(11) NOT NULL AUTO_INCREMENT,
                                  `cod_sri_sust_comprobante` varchar(255) DEFAULT NULL,
                                  `desc_sri_sust_comprobante` varchar(255) DEFAULT NULL,
                                  PRIMARY KEY (`id_sri_sust_comprobante`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
      $this->db->query("INSERT INTO sri_sust_comprobante (cod_sri_sust_comprobante, desc_sri_sust_comprobante) 
                          VALUES ('01', 'Crédito Tributario para declaración de IVA')");
      $this->db->query("INSERT INTO sri_sust_comprobante (cod_sri_sust_comprobante, desc_sri_sust_comprobante) 
                          VALUES ('02', 'Costo o Gasto para declaración de imp. a la Renta')");
      $this->db->query("INSERT INTO sri_sust_comprobante (cod_sri_sust_comprobante, desc_sri_sust_comprobante) 
                          VALUES ('03', 'Activo Fijo - Crédito tributario para declaración de IVA')");
      $this->db->query("INSERT INTO sri_sust_comprobante (cod_sri_sust_comprobante, desc_sri_sust_comprobante) 
                          VALUES ('04', 'Activo Fijo - Costo o Gasto para declaración de imp. a la renta')");
      $this->db->query("INSERT INTO sri_sust_comprobante (cod_sri_sust_comprobante, desc_sri_sust_comprobante) 
                          VALUES ('05', 'Liquidación de gastos de viaje, hospedaje y alimentación')");
      $this->db->query("INSERT INTO sri_sust_comprobante (cod_sri_sust_comprobante, desc_sri_sust_comprobante) 
                          VALUES ('06', 'Inventario - Crédito Tributario para declaración de IVA')");
      $this->db->query("INSERT INTO sri_sust_comprobante (cod_sri_sust_comprobante, desc_sri_sust_comprobante) 
                          VALUES ('07', 'Inventario - Costo o Gasto para declaración de imp. a la Renta')");
      $this->db->query("INSERT INTO sri_sust_comprobante (cod_sri_sust_comprobante, desc_sri_sust_comprobante) 
                          VALUES ('08', 'Valor pagado para solicitar Reembolso de Gastos')");
      $this->db->query("INSERT INTO sri_sust_comprobante (cod_sri_sust_comprobante, desc_sri_sust_comprobante) 
                          VALUES ('09', 'Reembolso por siniestros')");
      $this->db->query("INSERT INTO sri_sust_comprobante (cod_sri_sust_comprobante, desc_sri_sust_comprobante) 
                          VALUES ('10', 'Distribución de Dividendos, Beneficios o Utilidades')");
      $this->db->query("INSERT INTO sri_sust_comprobante (cod_sri_sust_comprobante, desc_sri_sust_comprobante) 
                          VALUES ('11', 'Convenios de débito o recaudación para IFI')");
      $this->db->query("INSERT INTO sri_sust_comprobante (cod_sri_sust_comprobante, desc_sri_sust_comprobante) 
                          VALUES ('12', 'Impuestos y retenciones presuntivos')");
      $this->db->query("INSERT INTO sri_sust_comprobante (cod_sri_sust_comprobante, desc_sri_sust_comprobante) 
                          VALUES ('13', 'Valores reconocidos por entidades del sector público a favor de sujetos pasivos')");
    }

    public function crea_tabla_sri_tipo_doc(){
      $query = $this->db->query("CREATE TABLE `sri_tipo_doc` (
                                  `id_sri_tipo_doc` int(11) NOT NULL AUTO_INCREMENT,
                                  `cod_sri_tipo_doc` varchar(255) DEFAULT NULL,
                                  `desc_sri_tipo_doc` varchar(255) DEFAULT NULL,
                                  PRIMARY KEY (`id_sri_tipo_doc`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
      $this->db->query("INSERT INTO sri_tipo_doc (cod_sri_tipo_doc, desc_sri_tipo_doc) VALUES ('01', 'Factura')");
      $this->db->query("INSERT INTO sri_tipo_doc (cod_sri_tipo_doc, desc_sri_tipo_doc) VALUES ('02', 'Nota de Venta')");
      $this->db->query("INSERT INTO sri_tipo_doc (cod_sri_tipo_doc, desc_sri_tipo_doc) VALUES ('03', 'Liquidación de compra de bienes o prestación de servicios')");
      $this->db->query("INSERT INTO sri_tipo_doc (cod_sri_tipo_doc, desc_sri_tipo_doc) VALUES ('04', 'Notas de Crédito')");
      $this->db->query("INSERT INTO sri_tipo_doc (cod_sri_tipo_doc, desc_sri_tipo_doc) VALUES ('05', 'Notas de Débito')");
    }

    public function crea_tabla_compra_retencion(){
      $query = $this->db->query("CREATE TABLE `compra_retencion` (
                                  `id_comp_ret` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_compra` int(11) DEFAULT NULL,
                                  `nro_retencion` varchar(255) DEFAULT NULL,
                                  `nro_autorizacion` varchar(255) DEFAULT NULL,
                                  `fecha_retencion` date DEFAULT NULL,
                                  PRIMARY KEY (`id_comp_ret`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_compra_retencion_detrenta(){
      $query = $this->db->query("CREATE TABLE `compra_retencion_detrenta` (
                                  `id_detallerenta` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_comp_ret` int(11) DEFAULT NULL,
                                  `id_concepto_retencion` int(11) DEFAULT NULL,
                                  `base_noiva` decimal(11,2) DEFAULT NULL,
                                  `base_iva` decimal(11,2) DEFAULT NULL,
                                  `porciento_retencion_renta` decimal(11,2) DEFAULT NULL,
                                  `valor_retencion_renta` decimal(11,2) DEFAULT NULL,
                                  PRIMARY KEY (`id_detallerenta`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_compra_retencion_detrenta_tmp(){
      $query = $this->db->query("CREATE TABLE `compra_retencion_detrenta_tmp` (
                                  `id_detallerenta` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_concepto_retencion` int(11) DEFAULT NULL,
                                  `base_noiva` decimal(11,2) DEFAULT NULL,
                                  `base_iva` decimal(11,2) DEFAULT NULL,
                                  `porciento_retencion_renta` decimal(11,2) DEFAULT NULL,
                                  `valor_retencion_renta` decimal(11,2) DEFAULT NULL,
                                  `id_usu` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id_detallerenta`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_compra_retencion_detiva(){
      $query = $this->db->query("CREATE TABLE `compra_retencion_detiva` (
                                  `id_detalleiva` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_comp_ret` int(11) DEFAULT NULL,
                                  `id_porcentaje_retencion_iva` int(11) DEFAULT NULL,
                                  `base_retencion_iva` decimal(11,2) DEFAULT NULL,
                                  `porciento_retencion_iva` decimal(11,2) DEFAULT NULL,
                                  `valor_retencion_iva` decimal(11,2) DEFAULT NULL,
                                  PRIMARY KEY (`id_detalleiva`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function actualiza_tabla_identificacion(){
      $query = $this->db->query("UPDATE identificacion SET codsri_compra='01' WHERE cod_identificacion='R'");
      $query = $this->db->query("UPDATE identificacion SET codsri_compra='02' WHERE cod_identificacion='C'");
      $query = $this->db->query("UPDATE identificacion SET codsri_compra='03' WHERE cod_identificacion='P'");
      $query = $this->db->query("UPDATE identificacion SET codsri_venta='04' WHERE cod_identificacion='R'");
      $query = $this->db->query("UPDATE identificacion SET codsri_venta='05' WHERE cod_identificacion='C'");
      $query = $this->db->query("UPDATE identificacion SET codsri_venta='06' WHERE cod_identificacion='P'");
    }

    public function actualiza_procalm_gastos_ins(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `gastos_ins`;");
      $query = $this->db->query("
          CREATE PROCEDURE `gastos_ins`(
          vsucursal int,
          vfecha date, 
          vproveedor int,
          vfactura varchar(255), 
          vautorizacion varchar(255), 
          vdescripcion text,
          vtipocompra varchar(255), 
          vapiva tinyint(1),
          vsubtotal double, 
          vsubtotaliva0 double, 
          vdescuento double, 
          vsubtotaldesc double, 
          vsubtotaliva0desc double, 
          vmontoiva double,  
          vtotal double, 
          vidusu int,
          vestatus varchar(255),
          vdias int,
          vfechapago date,
          vcategoria int,
          vtipodoc varchar(6),
          vsustento varchar(6),
          vtipodocmod varchar(3),
          vnumdocmod varchar(25),
          vautodocmod varchar(255)
          )
          BEGIN
            declare vid int;                                                      
            DECLARE EXIT handler for sqlexception select 0; 

            INSERT INTO gastos (fecha, id_proveedor, nro_factura, nro_autorizacion, descripcion, 
                                tipo_compra, apiva, subtotal, subtotalivacero, descuento, subtotaldesc, 
                                subtotalivacerodesc, montoiva, total, id_usu, estatus, dias, fecha_pago, 
                                categoria, cod_sri_tipo_doc, cod_sri_sust_comprobante, id_sucursal,
                                doc_mod_cod_sri_tipo, doc_mod_numero, doc_mod_autorizacion)
               VALUES (vfecha, vproveedor, vfactura, vautorizacion, vdescripcion, vtipocompra, vapiva, 
                       vsubtotal, vsubtotaliva0, vdescuento, vsubtotaldesc, vsubtotaliva0desc, vmontoiva, 
                       vtotal, vidusu, vestatus, vdias, vfechapago, vcategoria, vtipodoc, vsustento, vsucursal,
                       vtipodocmod, vnumdocmod, vautodocmod); 

            set vid=(select last_insert_id());
            select vid;

          END");
    }

    public function actualiza_procalm_gastos_upd(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `gastos_upd`;");
      $query = $this->db->query("
          CREATE PROCEDURE `gastos_upd`(
          vsucursal int,
          vfecha date, 
          vproveedor int,
          vfactura varchar(255), 
          vautorizacion varchar(255), 
          vdescripcion text,
          vtipocompra varchar(255), 
          vapiva tinyint(1),
          vsubtotal double, 
          vsubtotaliva0 double, 
          vdescuento double, 
          vsubtotaldesc double, 
          vsubtotaliva0desc double, 
          vmontoiva double,  
          vtotal double, 
          vidusu int,
          vestatus varchar(255),
          vdias int,
          vfechapago date,
          vcategoria int,
          vtipodoc varchar(6),
          vsustento varchar(6),
          vidgastos int,
          vtipodocmod varchar(3),
          vnumdocmod varchar(25),
          vautodocmod varchar(255)
          )
          BEGIN                                                         
            DECLARE EXIT handler for sqlexception select 0; 

            UPDATE gastos SET fecha = vfecha, 
                    id_proveedor = vproveedor, 
                    id_sucursal = vsucursal, 
                    nro_factura = vfactura, 
                    nro_autorizacion = vautorizacion, 
                    descripcion = vdescripcion, 
                    tipo_compra = vtipocompra, 
                    apiva = vapiva, 
                    subtotal = vsubtotal, 
                    subtotalivacero = vsubtotaliva0, 
                    descuento = vdescuento, 
                    subtotaldesc = vsubtotaldesc, 
                    subtotalivacerodesc = vsubtotaliva0desc,
                    montoiva = vmontoiva, 
                    total = vtotal, 
                    id_usu = vidusu, 
                    estatus = vestatus, 
                    dias = vdias, 
                    fecha_pago = vfechapago, 
                    categoria = vcategoria,
                    cod_sri_tipo_doc = vtipodoc, 
                    cod_sri_sust_comprobante = vsustento,
                    doc_mod_cod_sri_tipo = vtipodocmod, 
                    doc_mod_numero = vnumdocmod, 
                    doc_mod_autorizacion = vautodocmod
                WHERE id_gastos = vidgastos; 

           select 1;

          END");
    }

    public function crea_tabla_gastos_retencion(){
      $query = $this->db->query("CREATE TABLE `gastos_retencion` (
                                  `id_gastos_ret` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_gastos` int(11) DEFAULT NULL,
                                  `nro_retencion` varchar(255) DEFAULT NULL,
                                  `nro_autorizacion` varchar(255) DEFAULT NULL,
                                  `fecha_retencion` date DEFAULT NULL,
                                  PRIMARY KEY (`id_gastos_ret`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_gastos_retencion_detrenta(){
      $query = $this->db->query("CREATE TABLE `gastos_retencion_detrenta` (
                                  `id_detallerenta` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_gastos_ret` int(11) DEFAULT NULL,
                                  `id_concepto_retencion` int(11) DEFAULT NULL,
                                  `base_noiva` decimal(11,2) DEFAULT NULL,
                                  `base_iva` decimal(11,2) DEFAULT NULL,
                                  `porciento_retencion_renta` decimal(11,2) DEFAULT NULL,
                                  `valor_retencion_renta` decimal(11,2) DEFAULT NULL,
                                  PRIMARY KEY (`id_detallerenta`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_gastos_retencion_detrenta_tmp(){
      $query = $this->db->query("CREATE TABLE `gastos_retencion_detrenta_tmp` (
                                  `id_detallerenta` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_concepto_retencion` int(11) DEFAULT NULL,
                                  `base_noiva` decimal(11,2) DEFAULT NULL,
                                  `base_iva` decimal(11,2) DEFAULT NULL,
                                  `porciento_retencion_renta` decimal(11,2) DEFAULT NULL,
                                  `valor_retencion_renta` decimal(11,2) DEFAULT NULL,
                                  `id_usu` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id_detallerenta`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_gastos_retencion_detiva(){
      $query = $this->db->query("CREATE TABLE `gastos_retencion_detiva` (
                                  `id_detalleiva` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_gastos_ret` int(11) DEFAULT NULL,
                                  `id_porcentaje_retencion_iva` int(11) DEFAULT NULL,
                                  `base_retencion_iva` decimal(11,2) DEFAULT NULL,
                                  `porciento_retencion_iva` decimal(11,2) DEFAULT NULL,
                                  `valor_retencion_iva` decimal(11,2) DEFAULT NULL,
                                  PRIMARY KEY (`id_detalleiva`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_venta_retencion(){
      $query = $this->db->query("CREATE TABLE `venta_retencion` (
                                  `id_venta_ret` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_venta` int(11) DEFAULT NULL,
                                  `nro_retencion` varchar(255) DEFAULT NULL,
                                  `nro_autorizacion` varchar(255) DEFAULT NULL,
                                  `fecha_retencion` date DEFAULT NULL,
                                  PRIMARY KEY (`id_venta_ret`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_venta_retencion_detrenta(){
      $query = $this->db->query("CREATE TABLE `venta_retencion_detrenta` (
                                  `id_detallerenta` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_venta_ret` int(11) DEFAULT NULL,
                                  `id_concepto_retencion` int(11) DEFAULT NULL,
                                  `base_noiva` decimal(11,2) DEFAULT NULL,
                                  `base_iva` decimal(11,2) DEFAULT NULL,
                                  `porciento_retencion_renta` decimal(11,2) DEFAULT NULL,
                                  `valor_retencion_renta` decimal(11,2) DEFAULT NULL,
                                  PRIMARY KEY (`id_detallerenta`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_venta_retencion_detrenta_tmp(){
      $query = $this->db->query("CREATE TABLE `venta_retencion_detrenta_tmp` (
                                  `id_detallerenta` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_concepto_retencion` int(11) DEFAULT NULL,
                                  `base_noiva` decimal(11,2) DEFAULT NULL,
                                  `base_iva` decimal(11,2) DEFAULT NULL,
                                  `porciento_retencion_renta` decimal(11,2) DEFAULT NULL,
                                  `valor_retencion_renta` decimal(11,2) DEFAULT NULL,
                                  `id_usu` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id_detallerenta`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_venta_retencion_detiva(){
      $query = $this->db->query("CREATE TABLE `venta_retencion_detiva` (
                                  `id_detalleiva` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_venta_ret` int(11) DEFAULT NULL,
                                  `id_porcentaje_retencion_iva` int(11) DEFAULT NULL,
                                  `base_retencion_iva` decimal(11,2) DEFAULT NULL,
                                  `porciento_retencion_iva` decimal(11,2) DEFAULT NULL,
                                  `valor_retencion_iva` decimal(11,2) DEFAULT NULL,
                                  PRIMARY KEY (`id_detalleiva`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_permiso_sucursal(){
      $query = $this->db->query("CREATE TABLE `permiso_sucursal` (
                                  `id_usuario` int(11) NOT NULL,
                                  `id_sucursal` int(11) NOT  NULL,
                                  PRIMARY KEY (`id_usuario`,`id_sucursal`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_permiso_almacen(){
      $query = $this->db->query("CREATE TABLE `permiso_almacen` (
                                  `id_usuario` int(11) NOT NULL,
                                  `id_almacen` int(11) NOT  NULL,
                                  PRIMARY KEY (`id_usuario`,`id_almacen`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_punto_emision(){
      $query = $this->db->query("CREATE TABLE `punto_emision` (
                                  `id_puntoemision` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_sucursal` int(11) DEFAULT NULL,
                                  `cod_establecimiento` varchar(3) DEFAULT NULL,
                                  `cod_puntoemision` varchar(3) DEFAULT NULL,
                                  `consecutivo_factura` int DEFAULT NULL,
                                  `consecutivo_notaventa` int DEFAULT NULL,
                                  `consecutivo_comprobpago` int DEFAULT NULL,
                                  `activo` int DEFAULT NULL,
                                  PRIMARY KEY (`id_puntoemision`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
      $query = $this->db->query("INSERT INTO punto_emision (id_sucursal, cod_establecimiento, cod_puntoemision,
                                                            consecutivo_factura,consecutivo_notaventa,consecutivo_comprobpago, activo) 
                                   SELECT id_sucursal, 
                                          IFNULL((SELECT valor FROM parametros WHERE id=4),'001'),
                                          IFNULL((SELECT valor FROM parametros WHERE id=5),'001'),  
                                          IFNULL((SELECT valor FROM contador WHERE id_contador=2),1),  
                                          IFNULL((SELECT valor FROM contador WHERE id_contador=3),1),  
                                          IFNULL((SELECT valor FROM contador WHERE id_contador=7),1),
                                          1  
                                     FROM sucursal LIMIT 1;");
    }

    public function crea_tabla_caja_efectivo(){
      $query = $this->db->query("CREATE TABLE `caja_efectivo` (
                                  `id_caja` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_puntoemision` int(11) DEFAULT NULL,
                                  `nom_caja` varchar(100) DEFAULT NULL,
                                  `activo` int DEFAULT NULL,
                                  PRIMARY KEY (`id_caja`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
      $query = $this->db->query("INSERT INTO caja_efectivo (id_puntoemision, nom_caja, activo) VALUES (1, 'CAJA 1', 1);");
    }

    public function crea_tabla_permiso_cajaefectivo(){
      $query = $this->db->query("CREATE TABLE `permiso_cajaefectivo` (
                                  `id_usuario` int(11) NOT NULL,
                                  `id_caja` int(11) NOT  NULL,
                                  PRIMARY KEY (`id_usuario`,`id_caja`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function actualiza_procalm_facturageneral_insnew(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `facturageneral_insnew`;");
      $query = $this->db->query("
          CREATE PROCEDURE `facturageneral_insnew`(
          vusu int,
          vtipodoc int,
          vtipcancelacion int
          )
          BEGIN

            declare vid int;
            declare vidprof int;
            declare vidreg int;
            declare vidabono int;
            declare done int;
            declare viddetalle int;
            declare vcambio decimal(11,2);
            declare vidservicio int;
            declare vidptoemi int;

            declare curdet cursor for 
             SELECT id_detalle
              FROM venta_detalle_tmp t
              INNER JOIN venta_tmp v on v.id_venta = t.id_venta
              WHERE v.idusu = vusu; 

           
            declare cur1 cursor for 
             SELECT idreg
              FROM formapago_tmp t
              INNER JOIN venta_tmp v on v.id_venta = t.id_venta
              WHERE v.idusu = vusu AND id_tipcancelacion = vtipcancelacion; 

            /*DECLARE EXIT handler for sqlexception select 0; */
            DECLARE EXIT handler for sqlexception
            begin 
              ROLLBACK;
            end;  
            declare continue handler for not found set done=1;                                      
            START TRANSACTION;
            
            SET vidprof = (SELECT id_proforma FROM venta_tmp WHERE idusu = vusu);

            SET vidservicio = (SELECT id_servicio FROM venta_tmp WHERE idusu = vusu);

            SET vidptoemi = IFNULL((SELECT id_puntoemision FROM caja_efectivo c
                                      INNER JOIN venta_tmp t on t.id_caja = c.id_caja
                                      WHERE idusu = vusu LIMIT 1),1);

            SET vcambio = IFNULL((select sum(monto) from formapago_tmp where id_tipcancelacion = vtipcancelacion AND id_venta=(SELECT id_venta FROM venta_tmp WHERE idusu = vusu)),0) -
                          (SELECT descsubconiva + descsubsiniva + montoiva + round((descsubconiva + descsubsiniva) * ifnull((select valor from parametros where id=13),0) / 100 ,2) 
                             FROM venta_tmp WHERE idusu = vusu);
            IF vcambio < 0 THEN
                  SET vcambio = 0;
            END IF;                          
            
            UPDATE punto_emision SET consecutivo_factura = consecutivo_factura 
              WHERE id_puntoemision = IFNULL((SELECT id_puntoemision FROM caja_efectivo c
                                               INNER JOIN venta_tmp t on t.id_caja = c.id_caja
                                               WHERE idusu = vusu),1);           

            insert into venta (fecha, area, mesa, mesero, tipo_doc, nro_factura, tipo_ident, nro_ident,    
                               nom_cliente, telf_cliente, dir_cliente, correo_cliente, ciu_cliente, valiva, 
                               subconiva, subsiniva, desc_monto, descsubconiva, descsubsiniva, montoiva, 
                               montototal, fecharegistro, idusu, estatus, idmesa, id_cliente, 
                               id_tipcancelacion, montoimpuestoadicional, id_empresa, id_sucursal, 
                               id_puntoemision, id_caja, cambio, nro_orden, id_vendedor,
                               observaciones, placa_matricula)
            SELECT t.fecha, area, mesa, mesero, vtipodoc, 
                (case vtipodoc when 2 
                     then concat(cod_establecimiento,'-',cod_puntoemision,'-',LPAD(consecutivo_factura, 9, '0')) 
                     else LPAD(p.consecutivo_notaventa, 9, '0') 
                 end), 
                 tipo_ident, nro_ident, nom_cliente, telf_cliente,
                 dir_cliente, correo_cliente, ciu_cliente, 
                 ifnull((select valor from parametros where id=1),0.12) as valiva, 
                 subconiva, subsiniva, desc_monto,
                 descsubconiva, descsubsiniva, montoiva, 
                 round(descsubconiva + descsubsiniva + montoiva + round((descsubconiva + descsubsiniva) * ifnull((select valor from parametros where id=13),0) / 100 ,2),2) as montototal, now(), 
                 idusu, 1, ifnull(idmesa,0) , id_cliente, vtipcancelacion,
                 round((descsubconiva + descsubsiniva) * ifnull((select valor from parametros where id=13),0) / 100 ,2) as impuestoadicional,
                 s.id_empresa, p.id_sucursal, c.id_puntoemision, t.id_caja, vcambio, t.nro_orden, 
                 ifnull(t.id_vendedor,idusu), t.observaciones, t.placa_matricula
            FROM venta_tmp t
            INNER JOIN caja_efectivo c on c.id_caja = t.id_caja
            INNER JOIN punto_emision p on p.id_puntoemision = c.id_puntoemision
            INNER JOIN sucursal s on s.id_sucursal = p.id_sucursal
            where idusu = vusu;
                                              
            set vid = (select last_insert_id());
/*
            IF vtipodoc = 2 then
              UPDATE punto_emision SET consecutivo_factura = consecutivo_factura + 1
                WHERE id_puntoemision = IFNULL((SELECT id_puntoemision FROM caja_efectivo c
                                                 INNER JOIN venta_tmp t on t.id_caja = c.id_caja
                                                 WHERE idusu = vusu),1);            
            ELSE                                                   
              UPDATE punto_emision SET consecutivo_notaventa = consecutivo_notaventa + 1 
                WHERE id_puntoemision = IFNULL((SELECT id_puntoemision FROM caja_efectivo c
                                                 INNER JOIN venta_tmp t on t.id_caja = c.id_caja
                                                 WHERE idusu = vusu),1);            
            END IF;                                                 
*/
            set done = 0;
            open curdet;
            detLoop: loop
                fetch curdet into viddetalle;
                if done = 1 then leave detLoop; end if;
            
                  INSERT INTO venta_detalle (id_venta, id_producto, cantidad, precio, subtotal, iva, montoiva, descmonto, 
                                             descsubtotal, id_almacen, tipprecio, porcdesc, descripcion, subsidio,
                                             costo_unitario, costo_total)
                    SELECT vid, d.id_producto, d.cantidad, d.precio, d.subtotal, d.iva, d.montoiva, d.descmonto, 
                           d.descsubtotal, d.id_almacen, d.tipprecio, d.porcdesc, d.descripcion,
                           ROUND(IFNULL(p.subsidio,0) * d.cantidad,2),
                           p.pro_preciocompra, round(p.pro_preciocompra * d.cantidad, 2) 
                      FROM venta_detalle_tmp d
                      INNER JOIN venta_tmp v on v.id_venta = d.id_venta
                      INNER JOIN producto p on p.pro_id = d.id_producto
                      WHERE v.idusu = vusu AND d.id_detalle = viddetalle;

                  set vidreg = (select last_insert_id());
                                               

                  UPDATE producto_serie s
                    INNER JOIN venta_detalle_serie_tmp d on d.id_serie = s.id_serie
                    SET s.id_detalleventa = vidreg, id_estado = 2
                    WHERE d.id_detalle = viddetalle;    
            end loop detLoop;
            close curdet;    

            set done = 0;
            open cur1;
            igmLoop: loop
                fetch cur1 into vidreg;
                if done = 1 then leave igmLoop; end if;

              INSERT INTO venta_formapago (id_venta, id_formapago, monto, fecha, nro_comprobante, id_cajapago) 
              SELECT vid, id_formapago, 
                         case when id_tipcancelacion=1 and id_formapago=1 
                           then monto - vcambio
                           else monto
                         end, 
                         now(), 
                         ifnull((select consecutivo_comprobpago from punto_emision e
                                      INNER JOIN caja_efectivo c on c.id_puntoemision = e.id_puntoemision
                                      INNER JOIN venta_tmp t on t.id_caja = c.id_caja
                                      WHERE idusu = vusu LIMIT 1),1),
                         (SELECT id_caja FROM venta_tmp WHERE idusu = vusu)                                      
              FROM formapago_tmp 
              WHERE idreg = vidreg;
                                
              set vidabono = (select last_insert_id());
                
              UPDATE punto_emision set consecutivo_comprobpago=ifnull(consecutivo_comprobpago,1)+1 
                  WHERE id_puntoemision = (SELECT id_puntoemision FROM caja_efectivo c
                                                 INNER JOIN venta_tmp t on t.id_caja = c.id_caja
                                                 WHERE idusu = vusu LIMIT 1);
                                
              INSERT INTO venta_formapagobanco (id_abono, id_banco, fechaemision, fechacobro, numerocuenta, 
                              numerodocumento, descripciondocumento) 
              SELECT vidabono, id_banco, fechaemision, fechacobro, numerocuenta, numerodocumento, descripciondocumento 
              FROM formapago_tmp t
                  INNER JOIN formapago f on f.id_formapago = t.id_formapago
              WHERE idreg = vidreg and esinstrumentobanco = 1;

              INSERT INTO venta_formapagotarjeta (id_abono, id_tarjeta, id_banco, fechaemision, numerotarjeta, 
                                numerodocumento, descripciondocumento) 
              SELECT vidabono, id_tarjeta, id_banco, fechaemision, numerotarjeta, numerodocumento, descripciondocumento 
              FROM formapago_tmp t
                  INNER JOIN formapago f on f.id_formapago = t.id_formapago
              WHERE idreg = vidreg and estarjeta = 1;

              if vtipcancelacion = 2 then 
                  INSERT INTO venta_creditoabonoinicial (id_abono) Values(vidabono); 
              end if;

            end loop igmLoop;
            close cur1;    
                                                  
            if vtipcancelacion = 2 then 
                  INSERT INTO venta_credito (id_venta, fechalimite, dias, p100interes_credito, p100interes_mora,
                                               cantidadcuotas, abonoinicial, montobasecredito, montointerescredito,
                                               montocredito, id_estado)
                      SELECT vid, fechalimite, dias, p100interes_credito, p100interes_mora,
                             cantidadcuotas, abonoinicial, montobasecredito, montointerescredito,
                             montocredito, 1
                        FROM venta_credito_tmp t                       
                        INNER JOIN venta_tmp v on v.id_venta = t.id_venta
                        where v.idusu = vusu;
                        
                  INSERT INTO venta_creditocuota (id_venta, fechalimite, monto)
                      SELECT vid, fechalimite, monto
                        FROM venta_creditocuota_tmp t                       
                        INNER JOIN venta_tmp v on v.id_venta = t.id_venta
                        where v.idusu = vusu;
                  
            end if;
            
            INSERT INTO venta_dato_adicional (id_venta, id_config, datoadicional)
              SELECT vid, id_config, datoadicional 
                FROM venta_dato_adicional_tmp t
                INNER JOIN venta_tmp v on v.id_venta = t.id_venta
                WHERE v.idusu = vusu;

            /*DELETE FROM pedido WHERE id_mesa = ifnull((SELECT idmesa FROM venta_tmp where idusu = vusu),0);
            DELETE FROM pedido_detalle WHERE id_mesa = ifnull((SELECT idmesa FROM venta_tmp where idusu = vusu),0);*/
            
            DELETE FROM formapago_tmp WHERE id_venta in (select id_venta from venta_tmp where idusu = vusu);
            DELETE FROM venta_dato_adicional_tmp 
              WHERE id_venta in (select id_venta from venta_tmp where idusu = vusu);
            DELETE FROM venta_creditocuota_tmp WHERE id_venta in (select id_venta from venta_tmp where idusu = vusu);
            DELETE FROM venta_credito_tmp WHERE id_venta in (select id_venta from venta_tmp where idusu = vusu);
            
            DELETE FROM venta_detalle_tmp WHERE id_venta in (select id_venta from venta_tmp where idusu = vusu);
            DELETE FROM venta_tmp where idusu = vusu;
            
            IF vidprof > 0 THEN
              UPDATE proforma SET id_factura = vid WHERE id_proforma = vidprof;
            END IF;  
            
            IF vidservicio > 0 THEN
              UPDATE servicio SET id_venta = vid, id_estado = 5 WHERE id_servicio = vidservicio;
            END IF;  

            IF vtipodoc = 2 then
              UPDATE punto_emision SET consecutivo_factura = consecutivo_factura + 1
                WHERE id_puntoemision = vidptoemi;            
            ELSE                                                   
              UPDATE punto_emision SET consecutivo_notaventa = consecutivo_notaventa + 1 
                WHERE id_puntoemision = vidptoemi;            
            END IF;                                                 


            COMMIT;

            SELECT vid;

          END");
    }

    public function compra_det_add_iddetalle(){
      $query = $this->db->query("ALTER TABLE `compra_det` 
                                    ADD COLUMN `id_detalle` INT NOT NULL AUTO_INCREMENT FIRST,
                                    ADD PRIMARY KEY (`id_detalle`);");
    }

    public function venta_detalle_add_iddetalle(){
      $query = $this->db->query("ALTER TABLE `venta_detalle` 
                                    ADD COLUMN `id_detalle` INT NOT NULL AUTO_INCREMENT FIRST,
                                    ADD PRIMARY KEY (`id_detalle`);");
    }

    public function venta_detalle_tmp_add_iddetalle(){
      $query = $this->db->query("ALTER TABLE `venta_detalle_tmp` 
                                    ADD COLUMN `id_detalle` INT NOT NULL AUTO_INCREMENT FIRST,
                                    ADD PRIMARY KEY (`id_detalle`);");
    }

    public function crea_tabla_producto_serie(){
      $query = $this->db->query("CREATE TABLE `producto_serie` (
                                  `id_serie` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_producto` int(11) NOT NULL,
                                  `numeroserie` varchar(255) DEFAULT NULL,
                                  `descripcion` varchar(255) DEFAULT NULL,
                                  `fechaingreso` datetime DEFAULT CURRENT_TIMESTAMP,
                                  `id_detallecompra` int(11) DEFAULT NULL,
                                  `id_detalleventa` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id_serie`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_producto_serie_tmp(){
      $query = $this->db->query("CREATE TABLE `producto_serie_tmp` (
                                      `id_serie` int(11) NOT NULL AUTO_INCREMENT,
                                      `id_producto` int(11) NOT NULL,
                                      `numeroserie` varchar(255) DEFAULT NULL,
                                      `descripcion` varchar(255) DEFAULT NULL,
                                      `fechaingreso` datetime DEFAULT CURRENT_TIMESTAMP,
                                      `id_detallecompra` int(11) DEFAULT NULL,
                                      `id_detalleventa` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id_serie`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_servicio_config_general(){
      $query = $this->db->query("CREATE TABLE `servicio_config_general` (
                                  `habilita_servicio` tinyint(1) NOT NULL,
                                  `habilita_serie` tinyint(1) NOT NULL,
                                  `habilita_detalle` tinyint(1) NOT NULL,
                                  `habilita_encargado` tinyint(1) NOT NULL,
                                  `habilita_productoutilizado` tinyint(1) NOT NULL,
                                  `producto_servicio_factura` int(11) NOT NULL,
                                  `habilita_abono` tinyint(1) NOT NULL
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
      $query = $this->db->query("INSERT INTO servicio_config_general 
                                   (habilita_servicio, habilita_serie, habilita_detalle, habilita_encargado, 
                                    habilita_productoutilizado, habilita_abono) 
                                   VALUES (0, 0, 0, 0, 0, 0);");
    }

    public function crea_tabla_servicio_estado(){
      $query = $this->db->query("CREATE TABLE `servicio_estado` (
                                  `id_estado` int(11) NOT NULL,
                                  `nombre_estado` varchar(255) DEFAULT NULL,
                                  PRIMARY KEY (`id_estado`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
      $query = $this->db->query("INSERT INTO servicio_estado (id_estado, nombre_estado) VALUES (1, 'INGRESADO');");
      $query = $this->db->query("INSERT INTO servicio_estado (id_estado, nombre_estado) VALUES (2, 'REVISADO');");
      $query = $this->db->query("INSERT INTO servicio_estado (id_estado, nombre_estado) VALUES (3, 'REALIZADO');");
      $query = $this->db->query("INSERT INTO servicio_estado (id_estado, nombre_estado) VALUES (4, 'ENTREGADO');");
      $query = $this->db->query("INSERT INTO servicio_estado (id_estado, nombre_estado) VALUES (5, 'FACTURADO');");
    }

    public function crea_tabla_servicio_config_detalle(){
      $query = $this->db->query("CREATE TABLE `servicio_config_detalle` (
                                  `id_config` int(11) NOT NULL AUTO_INCREMENT,
                                  `nombre_configdetalle` varchar(255) DEFAULT NULL,
                                  `activo` tinyint(1) NOT NULL,
                                  PRIMARY KEY (`id_config`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_servicio(){
      $query = $this->db->query("CREATE TABLE `servicio` (
                                  `id_servicio` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_sucursal` int(11) NOT NULL,
                                  `fecha_emision` datetime DEFAULT CURRENT_TIMESTAMP,
                                  `numero_orden` int(11) DEFAULT NULL,
                                  `id_cliente` int(11) DEFAULT NULL,
                                  `costo_estimado` decimal(11,2) NOT NULL,
                                  `descripcion` varchar(255) DEFAULT NULL,
                                  `id_estado` int(11) DEFAULT NULL,
                                  `id_venta` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id_servicio`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_servicio_tmp(){
      $query = $this->db->query("CREATE TABLE `servicio_tmp` (
                                  `id_usuario` int(11) NOT NULL,
                                  `id_sucursal` int(11) NOT NULL,
                                  `fecha_emision` datetime DEFAULT CURRENT_TIMESTAMP,
                                  `numero_orden` int(11) DEFAULT NULL,
                                  `id_cliente` int(11) DEFAULT NULL,
                                  `tipo_ident` varchar(1) DEFAULT NULL,
                                  `nro_ident` varchar(100) DEFAULT NULL,
                                  `nom_cliente` varchar(255) DEFAULT NULL,
                                  `telf_cliente` varchar(255) DEFAULT NULL,
                                  `dir_cliente` varchar(255) DEFAULT NULL,
                                  `correo_cliente` varchar(255) DEFAULT NULL,
                                  `ciu_cliente` varchar(255) DEFAULT NULL,
                                  `costo_estimado` decimal(11,2) NOT NULL,
                                  `descripcion` varchar(255) DEFAULT NULL,
                                  `id_estado` int(11) DEFAULT NULL,
                                  `id_servicio` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id_usuario`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_servicio_detalle(){
      $query = $this->db->query("CREATE TABLE `servicio_detalle` (
                                  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_servicio` int(11) NOT NULL,
                                  `id_serie` int(11) NOT NULL,
                                  `id_tecnico` int(11) NOT NULL,
                                  `descripcion` varchar(255) DEFAULT NULL,
                                  `id_estado` int(11) NOT NULL,
                                  `fecha_realizado` datetime DEFAULT NULL,
                                  `trabajo_realizado` varchar(255) DEFAULT NULL,
                                  `fecha_entregado` datetime DEFAULT NULL,
                                  PRIMARY KEY (`id_detalle`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_servicio_detalle_tmp(){
      $query = $this->db->query("CREATE TABLE `servicio_detalle_tmp` (
                                  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_usuario` int(11) NOT NULL,
                                  `id_serie` int(11) NOT NULL,
                                  `id_tecnico` int(11) NOT NULL,
                                  `descripcion` varchar(255) DEFAULT NULL,
                                  `id_estado` int(11) NOT NULL,
                                  `fecha_realizado` datetime DEFAULT NULL,
                                  `trabajo_realizado` varchar(255) DEFAULT NULL,
                                  `fecha_entregado` datetime DEFAULT NULL,
                                  PRIMARY KEY (`id_detalle`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_servicio_subdetalle(){
      $query = $this->db->query("CREATE TABLE `servicio_subdetalle` (
                                  `id_subdetalle` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_detalle` int(11) NOT NULL,
                                  `id_config` int(11) NOT NULL,
                                  `valor` varchar(255) DEFAULT NULL,
                                  PRIMARY KEY (`id_subdetalle`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_servicio_subdetalle_tmp(){
      $query = $this->db->query("CREATE TABLE `servicio_subdetalle_tmp` (
                                  `id_subdetalle` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_detalle` int(11) NOT NULL,
                                  `id_config` int(11) NOT NULL,
                                  `valor` varchar(255) DEFAULT NULL,
                                  PRIMARY KEY (`id_subdetalle`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_servicio_producto(){
      $query = $this->db->query("CREATE TABLE `servicio_producto` (
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_detalle` int(11) NOT NULL,
                                  `id_producto` int(11) NOT NULL,
                                  `cantidad` decimal(11,4) NOT NULL,
                                  PRIMARY KEY (`id`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_servicio_producto_tmp(){
      $query = $this->db->query("CREATE TABLE `servicio_producto_tmp` (
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_detalle` int(11) NOT NULL,
                                  `id_producto` int(11) NOT NULL,
                                  `cantidad` decimal(11,4) NOT NULL,
                                  PRIMARY KEY (`id`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_servicio_abono(){
      $query = $this->db->query("CREATE TABLE `servicio_abono` (
                                  `id_abono` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_servicio` int(11) NOT NULL,
                                  `id_docpago` int(11) NOT NULL,
                                  PRIMARY KEY (`id_abono`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_servicio_abono_tmp(){
      $query = $this->db->query("CREATE TABLE `servicio_abono_tmp` (
                                  `id_abono` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_usuario` int(11) NOT NULL,
                                  `id_formapago` tinyint(2) NOT NULL,
                                  `monto` decimal(11,2) NOT NULL,
                                  `fecha_emision` datetime DEFAULT NULL,
                                  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
                                  `nro_comprobante` varchar(255) DEFAULT NULL,
                                  `id_banco` int(11) NULL,
                                  `fecha_cobro` datetime DEFAULT NULL,
                                  `numerocuenta` varchar(255) DEFAULT NULL,
                                  `numerodocumento` varchar(255) DEFAULT NULL,
                                  `descripciondocumento` varchar(255) DEFAULT NULL,
                                  `id_tarjeta` int(11) NULL,
                                  `numerotarjeta` varchar(255) DEFAULT NULL,
                                  `id_cajapago` int(11) NULL,
                                  `id_docpago` int(11) NULL,
                                  PRIMARY KEY (`id_abono`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function crea_tabla_empleado(){
      $query = $this->db->query("CREATE TABLE `empleado` (
                                  `id_empleado` int(11) NOT NULL AUTO_INCREMENT,
                                  `nombre_empleado` varchar(255) DEFAULT NULL,
                                  `tipo_identificacion` int(11) NOT NULL,
                                  `nro_ident` varchar(100) DEFAULT NULL,
                                  `es_tecnico` tinyint(1) NOT NULL,
                                  `activo` tinyint(1) NOT NULL,
                                  PRIMARY KEY (`id_empleado`)
                                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                ");
    }

    public function actualiza_procalm_cajaapertura_ins(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `cajaapertura_ins`;");
      $query = $this->db->query("
            CREATE PROCEDURE `cajaapertura_ins`(
            vusuario int,
            vidcaja int,
            vmonto double
            )
            BEGIN
              DECLARE EXIT handler for sqlexception select 0; 

              INSERT INTO caja_movimiento(id_usuario, fecha_apertura, monto_apertura, estado, id_caja) 
                VALUES(vusuario,  NOW(), vmonto, 0, vidcaja);

              select last_insert_id();

            END");
    }

    public function actualiza_procalm_cajaapertura_upd(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `cajaapertura_upd`;");
      $query = $this->db->query("CREATE PROCEDURE `cajaapertura_upd`(
                                    vidmov int,
                                    vusuario int,
                                    vcompras double,
                                    vnotacierre text,
                                    vsalida decimal(11,2),
                                    vjusti text,
                                    vventastotales decimal(11,2),
                                    vabonoservicio decimal(11,2),
                                    vmontonoefectivo decimal(11,2),
                                    vmontoegreso decimal(11,2),
                                    vsaldo decimal(11,2),
                                    vtotalcaja decimal(11,2),
                                    vsobrante decimal(11,2),
                                    vfaltante decimal(11,2),
                                    vdesefectivo decimal(11,2),
                                    vdescheque decimal(11,2),
                                    vdestarcre decimal(11,2),
                                    vdestardeb decimal(11,2),
                                    vdestarpre decimal(11,2),
                                    vdestransf decimal(11,2),
                                    vdesdinele decimal(11,2),
                                    vdesotros decimal(11,2),
                                    vdesvencre decimal(11,2),
                                    vabonocredito decimal(11,2)
                                    )
                              BEGIN
                                    DECLARE EXIT handler for sqlexception select 0; 
                              UPDATE caja_movimiento SET
                                    fecha_cierre = Now(),
                                    estado = 1,   
                                    idusu_cierre = vusuario,
                                    compras = vcompras,     
                                    observaciones = vnotacierre,       
                                    salida = vsalida,     
                                    justificacion = vjusti,   
                                    ventastotales = vventastotales,
                                    abonoservicio = vabonoservicio,
                                    montonoefectivo = vmontonoefectivo,
                                    montoegreso = vmontoegreso,
                                    saldo = vsaldo,
                                    totalcaja = vtotalcaja,
                                    sobrante = vsobrante,
                                    faltante = vfaltante,
                                    desefectivo = vdesefectivo,
                                    descheque = vdescheque,
                                    destarcre = vdestarcre,
                                    destardeb = vdestardeb,
                                    destarpre = vdestarpre,
                                    destransf = vdestransf,
                                    desdinele = vdesdinele,
                                    desotros = vdesotros,
                                    desvencre = vdesvencre,
                                    abonocredito = vabonocredito
                              WHERE id_mov = vidmov and estado = 0;
                              SELECT 1;
                              END");
    }

    public function chequea_tabla_perfil(){
      $query = $this->db->query("SELECT count(*) as cant FROM perfil");
      $r = $query->result();
      if ($r[0]->cant != 4){
            $this->db->query("DROP TABLE IF EXISTS `perfil`;");
            $this->db->query("CREATE TABLE `perfil` (
                                `id_perfil` int(11) NOT NULL,
                                `nom_perfil` varchar(255) DEFAULT NULL,
                                PRIMARY KEY (`id_perfil`)
                              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

            $this->db->query("INSERT INTO perfil (id_perfil, nom_perfil) VALUES(1, 'Administrador')");
            $this->db->query("INSERT INTO perfil (id_perfil, nom_perfil) VALUES(2, 'Vendedor')");
            $this->db->query("INSERT INTO perfil (id_perfil, nom_perfil) VALUES(3, 'Cajero')");
            $this->db->query("INSERT INTO perfil (id_perfil, nom_perfil) VALUES(4, 'Bodeguero')");
      }        
    }

    public function crea_tabla_notacredito(){
      $query = $this->db->query("CREATE TABLE `notacredito` (
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_sucursal` int(11) DEFAULT NULL,
                                  `id_almacen` int(11) DEFAULT NULL,
                                  `id_cliente` int(11) DEFAULT NULL,
                                  `id_usu` int(11) DEFAULT NULL,
                                  `fecha` date DEFAULT NULL,
                                  `id_puntoemision` int(11) DEFAULT NULL,
                                  `nro_documento` varchar(255) DEFAULT NULL,
                                  `tipodocmodificado` int(11) NOT NULL,
                                  `id_docmodificado` int(11) DEFAULT NULL,
                                  `nro_docmodificado` varchar(255) DEFAULT NULL,
                                  `fecha_docmodificado` date DEFAULT NULL,
                                  `motivo` varchar(255) DEFAULT NULL,
                                  `subtotalnoiva` decimal(11,2) DEFAULT NULL,
                                  `subtotaliva` decimal(11,2) DEFAULT NULL,
                                  `descuento` decimal(11,2) DEFAULT NULL,
                                  `descsubtotalnoiva` decimal(11,2) DEFAULT NULL,
                                  `descsubtotaliva` decimal(11,2) DEFAULT NULL,
                                  `montoiva` decimal(11,2) DEFAULT NULL,
                                  `total` decimal(11,2) DEFAULT NULL,
                                  `estatus` int(11) DEFAULT NULL,
                                  `fecharegistro` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                  PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_notacredito_detalle(){
      $query = $this->db->query("CREATE TABLE `notacredito_detalle` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `id_notacredito` int(11) DEFAULT NULL,
                                    `id_producto` int(11) DEFAULT NULL,
                                    `cantidad` decimal(11,2) DEFAULT NULL,
                                    `precio` decimal(11,4) DEFAULT NULL,
                                    `gravaiva` tinyint(1) DEFAULT NULL,
                                    `subtotal` decimal(11,2) DEFAULT NULL,
                                    `descuento` decimal(11,2) DEFAULT NULL,
                                    `montoiva` decimal(11,4) DEFAULT NULL,
                                    `descsubtotal` decimal(11,2) DEFAULT NULL,
                                    PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_notacredito_impuesto(){
      $query = $this->db->query("CREATE TABLE `notacredito_impuesto` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `id_detallenotacredito` int(11) DEFAULT NULL,
                                    `codigotipoimpuesto` varchar(6) DEFAULT NULL,
                                    `codigoporcentaje` varchar(6) DEFAULT NULL,
                                    `tarifa` decimal(11,2) DEFAULT NULL,
                                    `baseimponible` decimal(11,2) DEFAULT NULL,
                                    `valor` decimal(11,2) DEFAULT NULL,
                                    PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_notacredito_tmp(){
      $query = $this->db->query("CREATE TABLE `notacredito_tmp` (
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `id_sucursal` int(11) DEFAULT NULL,
                                  `id_almacen` int(11) DEFAULT NULL,
                                  `id_cliente` int(11) DEFAULT NULL,
                                  `id_usu` int(11) DEFAULT NULL,
                                  `fecha` date DEFAULT NULL,
                                  `id_puntoemision` int(11) DEFAULT NULL,
                                  `nro_documento` varchar(255) DEFAULT NULL,
                                  `tipodocmodificado` int(11) NOT NULL,
                                  `id_docmodificado` int(11) DEFAULT NULL,
                                  `nro_docmodificado` varchar(255) DEFAULT NULL,
                                  `fecha_docmodificado` date DEFAULT NULL,
                                  `motivo` varchar(255) DEFAULT NULL,
                                  `subtotalnoiva` decimal(11,2) DEFAULT NULL,
                                  `subtotaliva` decimal(11,2) DEFAULT NULL,
                                  `descuento` decimal(11,2) DEFAULT NULL,
                                  `descsubtotalnoiva` decimal(11,2) DEFAULT NULL,
                                  `descsubtotaliva` decimal(11,2) DEFAULT NULL,
                                  `montoiva` decimal(11,2) DEFAULT NULL,
                                  `total` decimal(11,2) DEFAULT NULL,
                                  PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_notacredito_detalle_tmp(){
      $query = $this->db->query("CREATE TABLE `notacredito_detalle_tmp` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `id_notacredito` int(11) DEFAULT NULL,
                                    `id_producto` int(11) DEFAULT NULL,
                                    `cantidad` decimal(11,2) DEFAULT NULL,
                                    `precio` decimal(11,4) DEFAULT NULL,
                                    `gravaiva` tinyint(1) DEFAULT NULL,
                                    `subtotal` decimal(11,2) DEFAULT NULL,
                                    `descuento` decimal(11,2) DEFAULT NULL,
                                    `montoiva` decimal(11,4) DEFAULT NULL,
                                    `descsubtotal` decimal(11,2) DEFAULT NULL,
                                    PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function actualiza_procalm_proforma_facturar(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `proforma_facturar`;");
      $query = $this->db->query("
                  CREATE PROCEDURE `proforma_facturar`(idprof INT, vidusu INT, caja INT)
                  BEGIN
                        declare vid int;
                        DECLARE EXIT handler for sqlexception select 0 as vid; 
                      set vid = 999999;

                      DELETE FROM venta_tmp WHERE idusu = vidusu;

                      INSERT INTO venta_tmp (fecha,mesa,mesero,tipo_doc,nro_factura,tipo_ident,
                                             nro_ident,nom_cliente,telf_cliente,dir_cliente,correo_cliente,
                                             ciu_cliente,valiva,subconiva,subsiniva,desc_monto,descsubconiva,
                                             descsubsiniva,montoiva,montototal,fecharegistro,idusu,idmesa,id_cliente, 
                                             id_proforma, id_caja, id_vendedor, observaciones)
                          SELECT p.fecha, nom_mesa, nom_mesero, 2, '', c.tipo_ident_cliente, c.ident_cliente, c.nom_cliente, 
                                 c.telefonos_cliente, c.direccion_cliente, c.correo_cliente, c.ciudad_cliente,
                                   p.valiva, p.subconiva, p.subsiniva, p.desc_monto, p.descsubconiva, p.descsubsiniva, 
                                   p.montoiva, p.montototal, now(), vidusu, p.id_puntoventa, p.id_cliente, p.id_proforma, 
                                   caja, p.id_vendedor, p.observaciones
                        FROM proforma p
                        LEFT JOIN mesa m on m.id_mesa = p.id_puntoventa
                        LEFT JOIN mesero s on s.id_mesero = p.id_vendedor
                        LEFT JOIN clientes c on c.id_cliente = p.id_cliente
                          WHERE id_proforma = idprof; 
                                                                  
                      set vid = (select last_insert_id());                            
                                                  
                        INSERT INTO venta_detalle_tmp (id_venta, id_producto, cantidad, precio, subtotal, iva, montoiva, 
                                                       descmonto, descsubtotal, id_almacen, tipprecio, precio_base, porcdesc, 
                                                       descripcion)
                          SELECT  vid, id_producto, cantidad, precio, subtotal, iva, pd.montoiva, descmonto, 
                                      descsubtotal, id_almacen, tipprecio, precio, porcdesc, descripcion
                                FROM proforma_detalle pd
                                WHERE id_proforma = idprof;    
                                      

                    SELECT vid;

                  END");
    }

    public function crea_tabla_cliente_tipoprecio(){
      $query = $this->db->query("CREATE TABLE `cliente_tipoprecio` (
                                      `id_cliente_tipoprecio` int(11) NOT NULL AUTO_INCREMENT,
                                      `id_cliente` int(11) NOT NULL,
                                      `id_precio` int(11) NOT NULL,
                                      `estatus` tinyint(1) NOT NULL,
                                      PRIMARY KEY (`id_cliente_tipoprecio`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function actualiza_procalm_login(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `login`;");
      $query = $this->db->query("
                  CREATE PROCEDURE `login`(vlogusu varchar(255), vpasusu varchar(255))
                  BEGIN
                      DECLARE vidusu INT;
                      DECLARE vdias INT;
                      DECLARE vtiempo INT;
                        DECLARE EXIT handler for sqlexception select 0;
                      
                      SET vidusu = IFNULL((SELECT id_usu FROM usu_sistemas WHERE log_usu = vlogusu AND pwd_usu = MD5(vpasusu)), 0);
                      SET vdias = IFNULL((SELECT DATEDIFF(NOW(),ultimoacceso) FROM usu_sistemas WHERE id_usu = vidusu), 0);    
                      SET vtiempo = 0;
                      IF vdias <= 1 THEN
                              SET vtiempo = IFNULL((SELECT TIME_TO_SEC(TIMEDIFF(NOW(),ultimoacceso)) AS difseg FROM usu_sistemas WHERE id_usu = vidusu), 0);    
                      END IF;
                      IF vidusu <> 0 THEN 
                              IF (vdias > 1) OR (vtiempo > 12) THEN     
                                    SELECT *, 1 AS val FROM usu_sistemas WHERE log_usu = vlogusu AND pwd_usu = MD5(vpasusu); 
                              ELSE 
                                    SELECT 999999999 AS val;
                              END IF;
                        ELSE 
                              SELECT 0 AS val;
                        END IF;     
                  END");
    }

    public function chequea_cliente_consumidorfinal(){
      $query = $this->db->query("SELECT count(*) as cant FROM clientes WHERE id_cliente = 1");
      $r = $query->result();
      if ($r[0]->cant == 0){
            $this->db->query("INSERT INTO clientes (id_cliente, nom_cliente, ident_cliente, tipo_ident_cliente, relacionado,
                                                    correo_cliente, ciudad_cliente, direccion_cliente, telefonos_cliente,
                                                    mayorista, tipo_precio) 
                                VALUES(1, 'CONSUMIDOR FINAL', '9999999999999','R',0, '', '', '', '', 0, 0)");
      }        
      $this->db->query("INSERT INTO cliente_tipoprecio (id_cliente, id_precio, estatus) 
                          select 1, id_precios, 1 from (select 0 as id_precios union select id_precios from precios) as t
                            where not id_precios in (select id_precio from cliente_tipoprecio where id_cliente=1)");
    }

    public function chequea_cliente_tipoprecio(){
      $this->db->query("INSERT into cliente_tipoprecio (id_cliente, id_precio, estatus)
                          SELECT id_cliente, p.id_precios, 1 
                            FROM clientes c, precios p
                            WHERE not EXISTS (SELECT * FROM cliente_tipoprecio t
                                                WHERE t.id_cliente = c.id_cliente and t.id_precio = p.id_precios)");
    }

    public function actualiza_procalm_cliente_ins(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `cliente_ins`;");
      $query = $this->db->query("
                  CREATE PROCEDURE `cliente_ins`(
                        vnom varchar(255),
                        vtip_ide varchar(100),
                        vnro_ide varchar(100),
                        vniv varchar(255),
                        vref varchar(255),
                        vcorreo varchar(255),
                        vciu varchar(255),
                        vrel tinyint,
                        vdir longtext, 
                        vtelf varchar(255), 
                        vmay tinyint, 
                        vpre int,
                        vvend int,
                        vcod varchar(255),
                        vcre decimal(11,2),
                        vplaca varchar(25),
                        vcategcontable int,
                        vcategventa int
                  )
                  BEGIN
                    DECLARE EXIT handler for sqlexception select 0; 
                    insert into clientes(nom_cliente, tipo_ident_cliente, ident_cliente, nivel_est_cliente, 
                                         ref_cliente, correo_cliente, ciudad_cliente, relacionado, direccion_cliente, 
                                         telefonos_cliente, mayorista, tipo_precio, id_vendedor, codigo, credito, 
                                         placa_matricula, idcategoriacontable, id_categoriaventa)
                      VALUES(vnom, vtip_ide, vnro_ide, vniv, vref, vcorreo, vciu, vrel, 
                             vdir, vtelf, vmay, vpre, vvend, vcod, vcre, vplaca, vcategcontable, vcategventa);
                    select last_insert_id() AS idcli;
                  END");
    }

    public function actualiza_procalm_cliente_upd(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `cliente_upd`;");
      $query = $this->db->query("
                  CREATE PROCEDURE `cliente_upd`(
                        vid int,
                        vnom varchar(255),
                        vtip_ide varchar(100),
                        vnro_ide varchar(100),
                        vniv varchar(255),
                        vref varchar(255),
                        vcorreo varchar(255),
                        vciu varchar(255),
                        vrel tinyint,
                        vdir longtext, 
                        vtelf varchar(255), 
                        vmay tinyint, 
                        vpre int,
                        vvend int,
                        vcod varchar(255),
                        vcre decimal(11,2),
                        vplaca varchar(25),
                        vcategcontable int,
                        vcategventa int
                  )
                  BEGIN
                    DECLARE EXIT handler for sqlexception select 0; 
                    UPDATE clientes SET 
                      nom_cliente = vnom,
                        tipo_ident_cliente = vtip_ide,
                        ident_cliente = vnro_ide,
                        nivel_est_cliente = vniv,
                        ref_cliente = vref,
                        correo_cliente = vcorreo,
                        ciudad_cliente = vciu,
                        relacionado = vrel, 
                        direccion_cliente = vdir, 
                        telefonos_cliente = vtelf, 
                        mayorista = vmay, 
                        tipo_precio = vpre,                   
                        id_vendedor = vvend,
                        codigo = vcod,
                        credito = vcre,
                        placa_matricula = vplaca
                    WHERE id_cliente = vid;
                    IF vcategcontable != 0 THEN
                        UPDATE clientes SET idcategoriacontable = vcategcontable
                          WHERE id_cliente = vid;
                    ELSE
                        UPDATE clientes SET idcategoriacontable = NULL
                          WHERE id_cliente = vid;
                    END IF;      
                    IF vcategventa != 0 THEN
                        UPDATE clientes SET id_categoriaventa = vcategventa
                          WHERE id_cliente = vid;
                    ELSE
                        UPDATE clientes SET id_categoriaventa = NULL
                          WHERE id_cliente = vid;
                    END IF;      

                    select 1;
                  END");
    }

    public function actualiza_procalm_servicio_facturar(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `servicio_facturar`;");
      $query = $this->db->query("
                  CREATE PROCEDURE `servicio_facturar`(idservicio INT, vidusu INT, vcaja INT)
                  BEGIN
                      declare vid int;
                      declare vgrabaiva int;
                      declare factoriva decimal(11,2);
                      declare valprodiva decimal(11,2);
                      declare valprodnoiva decimal(11,2);
                      declare costoestimado decimal(11,2);
                      declare vdatoequipo varchar(255);     
                      declare vincluirproducto int;

                      DECLARE EXIT handler for sqlexception select 0 as vid; 
                      set vid = 999999;

                      SET vgrabaiva = IFNULL((SELECT p.pro_grabaiva FROM servicio_config_general g 
                                               LEFT JOIN producto p on p.pro_id = g.producto_servicio_factura LIMIT 1), 0);

                      SET vincluirproducto = IFNULL((SELECT habilita_productofactura FROM servicio_config_general LIMIT 1), 1);
                      
                      SET factoriva = ifnull((select valor from parametros where id=1),0.12);                                               

                      SET valprodiva = ROUND(IFNULL((SELECT SUM(p.pro_precioventa * s.cantidad) FROM servicio_producto s 
                                                 INNER JOIN servicio_detalle d on d.id_detalle = s.id_detalle
                                                 INNER JOIN producto p on p.pro_id = s.id_producto
                                                 WHERE d.id_servicio = idservicio AND p.pro_grabaiva = 1), 0),2);
                      SET valprodnoiva = ROUND(IFNULL((SELECT SUM(p.pro_precioventa * s.cantidad) FROM servicio_producto s 
                                                 INNER JOIN servicio_detalle d on d.id_detalle = s.id_detalle
                                                 INNER JOIN producto p on p.pro_id = s.id_producto
                                                 WHERE d.id_servicio = idservicio AND p.pro_grabaiva = 0), 0),2);

                      SET vdatoequipo = IFNULL((SELECT concat(c.nombre_configdetalle,' ',s.valor) 
                                                 FROM servicio_subdetalle s
                                                 INNER JOIN servicio_detalle d on d.id_detalle = s.id_detalle
                                                 INNER JOIN servicio_config_detalle c on c.id_config = s.id_config
                                                 WHERE d.id_servicio = idservicio
                                                 ORDER BY c.id_config
                                                 LIMIT 1),'');

                      SET costoestimado = IFNULL((SELECT costo_estimado FROM servicio WHERE id_servicio = idservicio), 0);

                      DELETE FROM venta_tmp WHERE idusu = vidusu; 
            
                      IF vincluirproducto = 1 THEN  
                        INSERT INTO venta_tmp (fecha,mesa,mesero,tipo_doc,nro_factura,tipo_ident,
                                             nro_ident,nom_cliente,telf_cliente,dir_cliente,correo_cliente,
                                             ciu_cliente,valiva,subconiva,subsiniva,desc_monto,descsubconiva,descsubsiniva,
                                             montoiva,montototal,fecharegistro,idusu,idmesa,id_cliente, id_caja, id_servicio,
                                             observaciones)
                          SELECT date(NOW()), '', '', 2, '', c.tipo_ident_cliente, c.ident_cliente, c.nom_cliente, c.telefonos_cliente,
                                 c.direccion_cliente, c.correo_cliente, c.ciudad_cliente,
                                 factoriva, 
                                 (vgrabaiva * s.costo_estimado + valprodiva) as subconiva, 
                                 ((1 - vgrabaiva) * s.costo_estimado + valprodnoiva) as subsiniva, 
                                 0, 
                                 (vgrabaiva * s.costo_estimado + valprodiva) as descsubconiva, 
                                 ((1 - vgrabaiva) * s.costo_estimado + valprodnoiva) as descsubsiniva, 
                                 round((vgrabaiva * s.costo_estimado + valprodiva) * factoriva,2) as montoiva, 
                                 s.costo_estimado + valprodiva + valprodnoiva + round((vgrabaiva * s.costo_estimado + valprodiva) * factoriva,2), 
                                 now(), vidusu, 0, s.id_cliente, vcaja, s.id_servicio,
                                 concat('Orden de Servicio: ',s.numero_orden,'   ',vdatoequipo) 
                        FROM servicio s
                        LEFT JOIN clientes c on c.id_cliente = s.id_cliente
                        LEFT JOIN servicio_config_general g on IFNULL(g.producto_servicio_factura,0) <> 0 
                        LEFT JOIN producto p on p.pro_id = g.producto_servicio_factura
                          WHERE id_servicio = idservicio; 
                      ELSE
                        INSERT INTO venta_tmp (fecha,mesa,mesero,tipo_doc,nro_factura,tipo_ident,
                                             nro_ident,nom_cliente,telf_cliente,dir_cliente,correo_cliente,
                                             ciu_cliente,valiva,subconiva,subsiniva,desc_monto,descsubconiva,descsubsiniva,
                                             montoiva,montototal,fecharegistro,idusu,idmesa,id_cliente, id_caja, id_servicio,
                                             observaciones)
                          SELECT date(NOW()), '', '', 2, '', c.tipo_ident_cliente, c.ident_cliente, c.nom_cliente, c.telefonos_cliente,
                                 c.direccion_cliente, c.correo_cliente, c.ciudad_cliente,
                                 factoriva, 
                                 (vgrabaiva * (s.costo_estimado + valprodiva + valprodnoiva)) as subconiva, 
                                 ((1 - vgrabaiva) * (s.costo_estimado + valprodiva + valprodnoiva)) as subsiniva, 
                                 0, 
                                 (vgrabaiva * (s.costo_estimado + valprodiva + valprodnoiva)) as descsubconiva, 
                                 ((1 - vgrabaiva) * (s.costo_estimado + valprodiva + valprodnoiva)) as descsubsiniva, 
                                 round((vgrabaiva * (s.costo_estimado + valprodiva + valprodnoiva)) * factoriva,2) as montoiva, 
                                 s.costo_estimado + valprodiva + valprodnoiva + round((vgrabaiva * (s.costo_estimado + valprodiva + valprodnoiva)) * factoriva,2), 
                                 now(), vidusu, 0, s.id_cliente, vcaja, s.id_servicio,
                                 concat('Orden de Servicio: ',s.numero_orden,'   ',vdatoequipo) 
                        FROM servicio s
                        LEFT JOIN clientes c on c.id_cliente = s.id_cliente
                        LEFT JOIN servicio_config_general g on IFNULL(g.producto_servicio_factura,0) <> 0 
                        LEFT JOIN producto p on p.pro_id = g.producto_servicio_factura
                          WHERE id_servicio = idservicio; 
                      END IF;    
                                                                  
                      set vid = (select last_insert_id());                            
                                                 
                      IF vincluirproducto = 1 THEN  
                        INSERT INTO venta_detalle_tmp (id_venta, id_producto, cantidad, precio, subtotal, iva, 
                                                       montoiva, descmonto, descsubtotal, id_almacen, tipprecio, 
                                                       descripcion, porcdesc)
                          SELECT  vid, s.producto_servicio_factura, 1, costoestimado, costoestimado,
                                  vgrabaiva, round(vgrabaiva * costoestimado * factoriva,2), 0, 
                                  costoestimado, 0, 0, pro_nombre, 0
                             FROM servicio_config_general s    
                             INNER JOIN producto p on p.pro_id = s.producto_servicio_factura;
                      ELSE
                        INSERT INTO venta_detalle_tmp (id_venta, id_producto, cantidad, precio, subtotal, iva, 
                                                       montoiva, descmonto, descsubtotal, id_almacen, tipprecio, 
                                                       descripcion, porcdesc)
                          SELECT  vid, s.producto_servicio_factura, 1, 
                                  (costoestimado + valprodiva + valprodnoiva),
                                  (costoestimado + valprodiva + valprodnoiva),
                                  vgrabaiva, round(vgrabaiva * (costoestimado + valprodiva + valprodnoiva) * factoriva,2), 0, 
                                  (costoestimado + valprodiva + valprodnoiva), 0, 0, pro_nombre, 0
                             FROM servicio_config_general s    
                             INNER JOIN producto p on p.pro_id = s.producto_servicio_factura;
                      END IF;       
                                      
                      IF vincluirproducto = 1 THEN  
                        INSERT INTO venta_detalle_tmp (id_venta, id_producto, cantidad, precio, subtotal, iva, 
                                                       montoiva, descmonto, descsubtotal, id_almacen, tipprecio, 
                                                       descripcion, porcdesc)
                          SELECT vid, s.id_producto, s.cantidad, s.precio, 
                                 round(s.cantidad * s.precio,2) as subtotal, p.pro_grabaiva,
                                 round(p.pro_grabaiva * s.cantidad * s.precio * factoriva,2) as montoiva,
                                 0, round(s.cantidad * s.precio,2) as descsubtotal,
                                 s.id_almacen, 0, pro_nombre, 0
                             FROM servicio_producto s   
                             INNER JOIN servicio_detalle d on d.id_detalle = s.id_detalle
                             INNER JOIN producto p on p.pro_id = s.id_producto
                             WHERE d.id_servicio = idservicio;
                      END IF;             

                      SELECT vid;

                  END");
    }

    public function chequea_version0_servicio(){
      $res = $this->existe_tabla('servicio_subdetalle');
      if ($res != true){
            $this->db->query("DROP TABLE IF EXISTS `servicio_detalle_tmp`;");
            $this->db->query("DROP TABLE IF EXISTS `servicio_tmp`;");
            $this->db->query("DROP TABLE IF EXISTS `servicio_abono`;");
            $this->db->query("DROP TABLE IF EXISTS `servicio_producto`;");
            $this->db->query("DROP TABLE IF EXISTS `servicio_detalle`;");
            $this->db->query("DROP TABLE IF EXISTS `servicio`;");
      }
    }

    public function actualiza_procalm_proforma_sel_id(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `proforma_sel_id`;");
      $query = $this->db->query("
                  CREATE PROCEDURE `proforma_sel_id`(vusu INT, idprof INT)
                  BEGIN
                        DECLARE vid INT;
                        DECLARE EXIT handler for sqlexception select 0; 
                    
                        DELETE FROM proforma_detalle_tmp WHERE id_proforma IN (SELECT id_proforma from proforma_tmp WHERE idusu = vusu);
                      DELETE FROM proforma_tmp WHERE idusu = vusu;      
                    
                        INSERT INTO proforma_tmp (id_proftmp, fecha, nro_proforma, id_cliente, id_vendedor, id_puntoventa, valiva, subconiva, 
                                                        subsiniva, desc_monto, descsubconiva, descsubsiniva, montoiva, montototal, 
                                                        fecharegistro, idusu, id_factura, observaciones, id_sucursal)
                        SELECT id_proforma, fecha, nro_proforma, id_cliente, id_vendedor, id_puntoventa, valiva, subconiva, 
                                           subsiniva, desc_monto, descsubconiva, descsubsiniva, montoiva, montototal, fecharegistro, 
                                           vusu, id_factura, observaciones, id_sucursal 
                        FROM proforma
                        WHERE id_proforma = idprof;
                      
                      SET vid = (SELECT last_insert_id());
                                                                        
                        INSERT INTO proforma_detalle_tmp (id_proforma, id_producto, cantidad, precio, subtotal, iva, montoiva, descmonto, 
                                                    descsubtotal, id_almacen, tipprecio, descripcion, porcdesc)
                                                    SELECT vid, id_producto, cantidad, precio, subtotal, iva, pd.montoiva, descmonto, 
                                                            descsubtotal, id_almacen, tipprecio, descripcion, porcdesc
                                                    FROM proforma_detalle pd
                                                    INNER JOIN proforma pt ON pt.id_proforma = pd.id_proforma
                                                    WHERE pt.id_proforma = idprof;    
                      
                      SELECT idprof;
                  END");
    }

    public function actualiza_procalm_proforma_upd_id(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `proforma_upd_id`;");
      $query = $this->db->query("
                  CREATE PROCEDURE `proforma_upd_id`(vusu INT, idprof INT)
                  BEGIN
                        DECLARE EXIT handler for sqlexception select 0; 
  
                        UPDATE proforma p
                        INNER JOIN proforma_tmp pt ON pt.idusu = vusu
                        SET p.fecha = pt.fecha,
                              p.id_cliente = pt.id_cliente, 
                              p.id_vendedor = pt.id_vendedor, 
                              p.id_puntoventa = pt.id_puntoventa, 
                              p.valiva = pt.valiva, 
                              p.subconiva = pt.subconiva, 
                              p.subsiniva = pt.subsiniva, 
                              p.desc_monto = pt.desc_monto, 
                              p.descsubconiva = pt.descsubconiva, 
                              p.descsubsiniva = pt.descsubsiniva, 
                              p.montoiva = pt.montoiva, 
                              p.montototal = pt.montototal, 
                              p.fecharegistro = pt.fecharegistro, 
                              p.id_sucursal = pt.id_sucursal, 
                              p.idusu = pt.idusu,
                          p.observaciones = pt.observaciones
                        WHERE p.id_proforma = idprof; 
                      
                      DELETE FROM proforma_detalle WHERE id_proforma = idprof;
                                                                        
                        INSERT INTO proforma_detalle (id_proforma, id_producto, cantidad, precio, subtotal, iva, montoiva, descmonto, 
                                                    descsubtotal, id_almacen, tipprecio, descripcion, porcdesc)
                                                    SELECT  pt.id_proftmp, id_producto, cantidad, precio, subtotal, iva, pd.montoiva, descmonto, 
                                                            descsubtotal, id_almacen, tipprecio, descripcion, porcdesc
                                                    FROM proforma_detalle_tmp pd
                                                    INNER JOIN proforma_tmp pt ON pt.id_proforma = pd.id_proforma
                                                    WHERE pt.idusu = vusu/*pt.id_proftmp = idprof*/;    
                                                    
                        DELETE FROM proforma_detalle_tmp WHERE id_proforma IN (SELECT id_proforma from proforma_tmp WHERE idusu = vusu);
                      DELETE FROM proforma_tmp WHERE idusu = vusu;                                                      
                      
                      SELECT idprof;
                  END");
    }

    public function actualiza_procalm_proforma_ins(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `proforma_ins`;");
      $query = $this->db->query("
                  CREATE PROCEDURE `proforma_ins`(vusu INT)
                  BEGIN
                    DECLARE vid INT;
                    DECLARE vcont INT;

                    DECLARE EXIT handler for sqlexception select 0; 
                    
                        SET vcont = (SELECT valor FROM contador WHERE id_contador = 6);
                    
                        INSERT INTO proforma (fecha, nro_proforma, id_cliente, id_vendedor, id_puntoventa, valiva, subconiva, 
                                                        subsiniva, desc_monto, descsubconiva, descsubsiniva, montoiva, montototal, 
                                                        fecharegistro, idusu, id_factura, observaciones, id_sucursal)
                        SELECT date(Now()), vcont, id_cliente, id_vendedor, id_puntoventa, valiva, subconiva, 
                                           subsiniva, desc_monto, descsubconiva, descsubsiniva, montoiva, montototal, fecharegistro, 
                                           idusu, id_factura, observaciones, id_sucursal 
                        FROM proforma_tmp
                        WHERE idusu = vusu;
                                                                        
                        SET vid = (SELECT last_insert_id());
                      
                        INSERT INTO proforma_detalle (id_proforma, id_producto, cantidad, precio, subtotal, iva, montoiva, descmonto, 
                                                    descsubtotal, id_almacen, tipprecio, descripcion, porcdesc)
                                                    SELECT  vid, id_producto, cantidad, precio, subtotal, iva, pd.montoiva, descmonto, 
                                                            descsubtotal, id_almacen, tipprecio, descripcion, porcdesc
                                                    FROM proforma_detalle_tmp pd
                                                    INNER JOIN proforma_tmp pt ON pt.id_proforma = pd.id_proforma
                                                    WHERE pt.idusu = vusu ;   
                       
                      UPDATE contador SET valor = valor + 1 WHERE id_contador = 6;   
                      
                        DELETE FROM proforma_detalle_tmp WHERE id_proforma IN (SELECT id_proforma from proforma_tmp WHERE idusu = vusu);
                      DELETE FROM proforma_tmp WHERE idusu = vusu;    
                      SELECT vid;
                 END");
    }

    public function actualiza_procalm_usuario_upd_acceso(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `usuario_upd_acceso`;");
      $query = $this->db->query("
                  CREATE PROCEDURE `usuario_upd_acceso`(vidusu int)
                  BEGIN
                      SET time_zone = '-5:00';
                      update usu_sistemas set ultimoacceso = now() where id_usu = vidusu;
                 END");
    }

    public function crea_tabla_garantia(){
      $query = $this->db->query("CREATE TABLE `garantia` (
                                      `id_garantia` int(11) NOT NULL AUTO_INCREMENT,
                                      `idventa` int(11) DEFAULT NULL,
                                      `idserie` int(11) DEFAULT NULL,
                                      `fec_desde` date DEFAULT NULL,
                                      `fec_hasta` date DEFAULT NULL,
                                      `dias_gar` int(11) DEFAULT NULL,
                                      `estatus` tinyint(1) DEFAULT NULL,
                                      PRIMARY KEY (`id_garantia`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_producto_ventaserie_tmp(){
      $query = $this->db->query("CREATE TABLE `producto_ventaserie_tmp` (
                                      `id_serie` int(11) NOT NULL,
                                      `id_producto` int(11) NOT NULL,
                                      `numeroserie` varchar(255) DEFAULT NULL,
                                      `descripcion` varchar(255) DEFAULT NULL,
                                      `fechaingreso` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                      `id_detalleventa` int(11) DEFAULT NULL,
                                      PRIMARY KEY (`id_serie`) 
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function actualiza_procalm_almacen_ins(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `almacen_ins`;");
      $query = $this->db->query("
                  CREATE PROCEDURE `almacen_ins`(vnomalm varchar(255),
                                                vdiralm varchar(255),
                                                vresalm varchar(255),
                                                vdesalm varchar(255),
                                                vsucalm int,
                                                vdepalm int,
                                                vprodalm int,
                                                vtipoalm tinyint(1))
                  BEGIN
                    DECLARE EXIT handler for sqlexception select 0; 

                    INSERT INTO almacen(almacen_nombre, almacen_direccion, almacen_responsable, 

                                        almacen_descripcion, sucursal_id, almacen_deposito, almacen_idproducto, almacen_tipo)

                      VALUES(vnomalm, vdiralm, vresalm, vdesalm, vsucalm, vdepalm, vprodalm, vtipoalm);

                    select last_insert_id() as id;
                 END");
    }

    public function actualiza_procalm_almacen_upd(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `almacen_upd`;");
      $query = $this->db->query("
                  CREATE PROCEDURE `almacen_upd`(vid int,
                                                vnomalm varchar(255),
                                                vdiralm varchar(255),
                                                vresalm varchar(255),
                                                vdesalm varchar(255),
                                                vsucalm int,
                                                vdepalm int,
                                                vprodalm int,
                                                vtipoalm tinyint(1))
                  BEGIN
                    DECLARE EXIT handler for sqlexception select 0; 
                    UPDATE almacen SET 
                      almacen_nombre = vnomalm, 
                      almacen_direccion = vdiralm, 
                      almacen_responsable = vresalm, 
                      almacen_descripcion = vdesalm, 
                      sucursal_id = vsucalm,
                      almacen_deposito = vdepalm,                     
                      almacen_idproducto = vprodalm,                     
                      almacen_tipo = vtipoalm                     
                    WHERE almacen_id = vid;

                    select 1;
                 END");
    }

    public function crea_tabla_mesero(){
      $query = $this->db->query("CREATE TABLE `mesero` (
                                      `id_mesero` int(11) NOT NULL AUTO_INCREMENT,
                                      `tipo_ident_mesero` varchar(100) NOT NULL,
                                      `ced_mesero` varchar(255) NOT NULL,
                                      `nom_mesero` varchar(255) NOT NULL,
                                      `telf_mesero` varchar(255) DEFAULT NULL,
                                      `correo_mesero` varchar(255) DEFAULT NULL,
                                      `direccion_mesero` varchar(255) DEFAULT NULL,
                                      `foto_mesero` longblob,
                                      `estatus_mesero` varchar(3) DEFAULT NULL,
                                      PRIMARY KEY (`id_mesero`) USING BTREE,
                                      UNIQUE KEY `ced_mesero_UNIQUE` (`ced_mesero`) USING BTREE
                                    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_facturainfoestadosri(){
      $this->db->query("CREATE TABLE `facturainfoestadosri` (
                          `Idfactura` int(11) DEFAULT NULL,
                          `secuencial` varchar(100) DEFAULT NULL,
                          `enviadoSRI` int(11) DEFAULT NULL,
                          `autorizado` int(11) DEFAULT NULL,
                          `rechazado` int(11) DEFAULT NULL,
                          `claveacesso` varchar(100) DEFAULT NULL,
                          `numeroautorizacion` varchar(100) DEFAULT NULL,
                          `fechaautorizacion` varchar(100) DEFAULT NULL,
                          `enviadoemail` varchar(100) DEFAULT NULL,
                          `email` varchar(100) DEFAULT NULL,
                          `anulado` int(11) DEFAULT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        ;");
    }

    public function crea_tabla_guiaremisioninfoestadosri(){
      $this->db->query("CREATE TABLE `guiaremisioninfoestadosri` (
                          `idguia` int(11) DEFAULT NULL,
                          `secuencial` varchar(100) DEFAULT NULL,
                          `enviadoSRI` int(11) DEFAULT NULL,
                          `autorizado` int(11) DEFAULT NULL,
                          `rechazado` int(11) DEFAULT NULL,
                          `claveacesso` varchar(100) DEFAULT NULL,
                          `numeroautorizacion` varchar(100) DEFAULT NULL,
                          `fechaautorizacion` varchar(100) DEFAULT NULL,
                          `enviadoemail` varchar(100) DEFAULT NULL,
                          `email` varchar(100) DEFAULT NULL,
                          `anulado` int(11) DEFAULT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        ;");
    }

    public function crea_tabla_notacreditoinfoestadosri(){
      $this->db->query("CREATE TABLE `notacreditoinfoestadosri` (
                          `idnotacredito` int(11) DEFAULT NULL,
                          `secuencial` varchar(100) DEFAULT NULL,
                          `enviadoSRI` int(11) DEFAULT NULL,
                          `autorizado` int(11) DEFAULT NULL,
                          `rechazado` int(11) DEFAULT NULL,
                          `claveacesso` varchar(100) DEFAULT NULL,
                          `numeroautorizacion` varchar(100) DEFAULT NULL,
                          `fechaautorizacion` varchar(100) DEFAULT NULL,
                          `enviadoemail` varchar(100) DEFAULT NULL,
                          `email` varchar(100) DEFAULT NULL,
                          `anulado` int(11) DEFAULT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        ;");
    }

    public function crea_tabla_retencioninfoestadosri(){
      $this->db->query("CREATE TABLE `retencioninfoestadosri` (
                          `idretencion` int(11) DEFAULT NULL,
                          `secuencial` varchar(100) DEFAULT NULL,
                          `enviadoSRI` int(11) DEFAULT NULL,
                          `autorizado` int(11) DEFAULT NULL,
                          `rechazado` int(11) DEFAULT NULL,
                          `claveacesso` varchar(100) DEFAULT NULL,
                          `numeroautorizacion` varchar(100) DEFAULT NULL,
                          `fechaautorizacion` varchar(100) DEFAULT NULL,
                          `enviadoemail` varchar(100) DEFAULT NULL,
                          `email` varchar(100) DEFAULT NULL,
                          `anulado` int(11) DEFAULT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        ;");
    }

    public function crea_tabla_retenciongastoinfoestadosri(){
      $query = $this->db->query("CREATE TABLE `retenciongastoinfoestadosri` (
                                  `idretencion` int(11) DEFAULT NULL,
                                  `secuencial` varchar(100) DEFAULT NULL,
                                  `enviadosri` int(11) DEFAULT NULL,
                                  `autorizado` int(11) DEFAULT NULL,
                                  `rechazado` int(11) DEFAULT NULL,
                                  `claveacesso` varchar(100) DEFAULT NULL,
                                  `numeroautorizacion` varchar(100) DEFAULT NULL,
                                  `fechaautorizacion` varchar(100) DEFAULT NULL,
                                  `enviadoemail` varchar(100) DEFAULT NULL,
                                  `email` varchar(100) DEFAULT NULL
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }
    
    public function crea_tabla_tmp_guiaremisionproducto(){
      $query = $this->db->query("CREATE TABLE `tmp_guiaremisionproducto` (
                                    `iddetalle` int(11) NOT NULL AUTO_INCREMENT,
                                    `id_usuario` int(11) DEFAULT NULL,
                                    `id_producto` int(11) DEFAULT NULL,
                                    `cantidad` decimal(11,2) DEFAULT NULL,
                                    `codigo` varchar(255) DEFAULT NULL,
                                    `descripcion` text,
                                    PRIMARY KEY (`iddetalle`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_sriguiaremisiondestino(){
      $this->db->query("CREATE TABLE `sriguiaremisiondestino` (
                          `iddestino` int(11) NOT NULL AUTO_INCREMENT,
                          `idguia` int(11) DEFAULT NULL,
                          `iddestinatario` int(11) DEFAULT NULL,
                          `motivo` varchar(255) DEFAULT NULL,
                          `docaduanero` varchar(20) DEFAULT NULL,
                          `codestabdestino` varchar(3) DEFAULT NULL,
                          `ruta` varchar(255) DEFAULT NULL,
                          `coddocsustento` varchar(2) DEFAULT NULL,
                          `numdocsustento` varchar(15) DEFAULT NULL,
                          `numautdocsustento` varchar(49) DEFAULT NULL,
                          `fechaemidocsustento` datetime DEFAULT NULL,
                          `dirllegada` varchar(200) DEFAULT NULL,
                          PRIMARY KEY (`iddestino`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_sriguiaremisionencab(){
      $this->db->query("CREATE TABLE `sriguiaremisionencab` (
                          `idguia` int(11) NOT NULL AUTO_INCREMENT,
                          `fechaemision` datetime DEFAULT NULL,
                          `dirpartida` varchar(200) DEFAULT NULL,
                          `idtransportista` int(11) DEFAULT NULL,
                          `fechaini` datetime DEFAULT NULL,
                          `fechafin` datetime DEFAULT NULL,
                          `placa` varchar(100) DEFAULT NULL,
                          `secuencial` varchar(9) DEFAULT NULL,
                          PRIMARY KEY (`idguia`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_sriguiaremisionproducto(){
      $this->db->query("CREATE TABLE `sriguiaremisionproducto` (
                          `iddetalle` int(11) NOT NULL AUTO_INCREMENT,
                          `iddestino` int(11) DEFAULT NULL,
                          `idproducto` int(11) DEFAULT NULL,
                          `codigointerno` varchar(25) DEFAULT NULL,
                          `codigoadicional` varchar(25) DEFAULT NULL,
                          `descripcion` varchar(255) DEFAULT NULL,
                          `cantidad` double DEFAULT NULL,
                          PRIMARY KEY (`iddetalle`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_tokenfirma(){
      $this->db->query("CREATE TABLE `tokenfirma` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `nombrearchivo` varchar(255) NOT NULL,
                          `contrasena` varchar(255) DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_sritransportista(){
      $this->db->query("CREATE TABLE `sritransportista` (
                          `idtransportista` int(11) NOT NULL AUTO_INCREMENT,
                          `iddestino` int(11) DEFAULT NULL,

                          `razonsocial` varchar(200) DEFAULT NULL,
                          `direccion` varchar(200) DEFAULT NULL,
                          `telefono` varchar(100) DEFAULT NULL,
                          `cedula` varchar(14) DEFAULT NULL,
                          `email` varchar(100) DEFAULT NULL,
                          `tipoid` varchar(2) DEFAULT NULL,
                          `ciudad` varchar(100) DEFAULT NULL,
                          PRIMARY KEY (`idtransportista`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_devolucion_garantia(){
      $this->db->query("CREATE TABLE `devolucion_garantia` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `fecha` datetime DEFAULT NULL,
                          `idsucursal` int(11) NOT NULL,
                          `nrodevolucion` varchar(9) DEFAULT NULL,
                          `idcliente` int(11) NOT NULL,
                          `idusuario` int(11) NOT NULL,
                          `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                          `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

      $res = $this->existe_foreign_key('FK_devolucion_garantia_sucursal');
      if ($res != true) $this->add_foreign_key('devolucion_garantia', 'FK_devolucion_garantia_sucursal', 
                                               'idsucursal', 'sucursal', 'id_sucursal');
      $this->chequea_engine('clientes');
      $res = $this->existe_foreign_key('FK_devolucion_garantia_cliente');
      if ($res != true) $this->add_foreign_key('devolucion_garantia', 'FK_devolucion_garantia_cliente', 
                                               'idcliente', 'clientes', 'id_cliente');
      $this->chequea_engine('usu_sistemas');
      $res = $this->existe_foreign_key('FK_devolucion_garantia_usuario');
      if ($res != true) $this->add_foreign_key('devolucion_garantia', 'FK_devolucion_garantia_usuario', 
                                               'idusuario', 'usu_sistemas', 'id_usu');

    }

    public function crea_tabla_devolucion_garantia_detalle(){
      $this->db->query("CREATE TABLE `devolucion_garantia_detalle` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `iddevolucion` int(11) NOT NULL,
                          `idventa` int(11) DEFAULT NULL,
                          `idserie` int(11) NOT NULL,
                          `idalmacen` int(11) NOT NULL,
                          `observaciones` text,
                          `iddoc_entradaalmacen` int(11) NULL,
                          `idserie_reposicion` int(11) NULL,
                          `iddoc_salidaalmacen` int(11) NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      $res = $this->existe_foreign_key('FK_devolucion_garantia_detalle_encab');
      if ($res != true) $this->add_foreign_key('devolucion_garantia_detalle', 'FK_devolucion_garantia_detalle_encab', 
                                               'iddevolucion', 'devolucion_garantia', 'id');
      $res = $this->existe_foreign_key('FK_devolucion_garantia_detalle_venta');
      if ($res != true) $this->add_foreign_key('devolucion_garantia_detalle', 'FK_devolucion_garantia_detalle_venta', 
                                               'idventa', 'venta', 'id_venta');
      $res = $this->existe_foreign_key('FK_devolucion_garantia_detalle_serie');
      if ($res != true) $this->add_foreign_key('devolucion_garantia_detalle', 'FK_devolucion_garantia_detalle_serie', 
                                               'idserie', 'producto_serie', 'id_serie');
      $res = $this->existe_foreign_key('FK_devolucion_garantia_detalle_almacen');
      if ($res != true) $this->add_foreign_key('devolucion_garantia_detalle', 'FK_devolucion_garantia_detalle_almacen', 
                                               'idalmacen', 'almacen', 'almacen_id');
      $res = $this->existe_foreign_key('FK_devolucion_garantia_detalle_docentrada');
      if ($res != true) $this->add_foreign_key('devolucion_garantia_detalle', 'FK_devolucion_garantia_detalle_docentrada', 
                                               'iddoc_entradaalmacen', 'inventariodocumento', 'id_documento');
    }

    public function crea_tabla_serie_tipomovimiento(){
      $this->db->query("CREATE TABLE `serie_tipomovimiento` (
                            `id` tinyint(2) NOT NULL,
                            `movimiento` varchar(25) ,
                            `estado` varchar(25) ,
                            PRIMARY KEY (`id`)
                           ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                          ");
      $this->db->query("INSERT INTO serie_tipomovimiento (id, movimiento, estado) VALUES (1, 'INGRESO', 'INGRESADO');");
      $this->db->query("INSERT INTO serie_tipomovimiento (id, movimiento, estado) VALUES (2, 'VENTA', 'VENDIDO');");
      $this->db->query("INSERT INTO serie_tipomovimiento (id, movimiento, estado) VALUES (3, 'DEVOLUCION', 'DEVUELTO');");
      $this->db->query("INSERT INTO serie_tipomovimiento (id, movimiento, estado) VALUES (4, 'REPOSICION', 'REPOSICION');");
      $this->db->query("INSERT INTO serie_tipomovimiento (id, movimiento, estado) VALUES (5, 'SERVICIO', 'SERVICIO');");
      $this->db->query("INSERT INTO serie_tipomovimiento (id, movimiento, estado) VALUES (6, 'REPARACION', 'REPARADO');");
      $this->db->query("INSERT INTO serie_tipomovimiento (id, movimiento, estado) VALUES (7, 'BAJA', 'BAJA');");
    }

    public function crea_tabla_serie_productokardex(){
      $this->db->query("CREATE TABLE `serie_productokardex` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `idserie` int(11) NOT NULL,
                          `idalmacen` int(11) NOT NULL,
                          `tipomovimiento` tinyint(2) NOT NULL,
                          `iddocumento` int(11) NOT NULL,
                          `nrodocumento` varchar(25) DEFAULT NULL,
                          `fechamovimiento` datetime DEFAULT CURRENT_TIMESTAMP,
                          `observaciones` text,
                          `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      $res = $this->existe_foreign_key('FK_serie_productokardex_serie');
      if ($res != true) $this->add_foreign_key('serie_productokardex', 'FK_serie_productokardex_serie', 
                                               'idserie', 'producto_serie', 'id_serie');
      $res = $this->existe_foreign_key('FK_serie_productokardex_almacen');
      if ($res != true) $this->add_foreign_key('serie_productokardex', 'FK_serie_productokardex_almacen', 
                                               'idalmacen', 'almacen', 'almacen_id');
      $res = $this->existe_foreign_key('FK_serie_productokardex_tipomovimiento');
      if ($res != true) $this->add_foreign_key('serie_productokardex', 'FK_serie_productokardex_tipomovimiento', 
                                               'tipomovimiento', 'serie_tipomovimiento', 'id');
    }

    public function actualiza_procalm_inventariomovimiento_guardar(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `inventariomovimiento_guardar`;");
      $query = $this->db->query("
          CREATE PROCEDURE `inventariomovimiento_guardar`(
          vidmov int,
          vfecha datetime,
          vtipodoc int)
          BEGIN
          
            declare vid int;                                                      
          
            DECLARE EXIT handler for sqlexception select 0; 
            
            UPDATE contador SET valor = valor WHERE id_contador = vtipodoc;
          
            INSERT INTO inventariodocumento (id_tipodoc, id_usu, fecha, nro_documento, descripcion, total, 
                                             estatus, fecharegistro, id_almacen, idcategoriacontable) 
              SELECT id_tipodoc, id_usu, vfecha, 
                     (select concat(prefijo,'-',LPAD(valor, 9, '0')) from contador where id_contador=vtipodoc) as nro,
                     descripcion, montototal, 1, now(), id_almacen, idcategoriacontable 
                FROM  tmp_movinv WHERE id_mov = vidmov;
          
            set vid=(select last_insert_id());
            
              UPDATE contador SET valor = valor + 1 WHERE id_contador = vtipodoc;
            
            INSERT INTO inventariodocumento_detalle (id_documento, id_pro, precio_compra, cantidad, id_unimed, montototal, id_serie)
              SELECT vid, id_pro, precio_compra, cantidad, id_unimed, montototal, id_serie
                FROM  tmp_movinv_det WHERE id_mov = vidmov;
                
            INSERT INTO almapro (id_pro, id_alm, existencia, id_unimed)
              select p.pro_id, t.id_almacen, 0, p.pro_idunidadmedida from producto p
                inner join tmp_movinv_det d on d.id_pro = p.pro_id
                inner join tmp_movinv t on t.id_mov = d.id_mov
                where t.id_mov = vidmov and
                      not exists (select * from almapro where id_pro = pro_id and id_alm = t.id_almacen);               
                     
            select vid;
          
          END");
    }


    public function actualiza_procalm_kardexegreso_ins(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `kardexegreso_ins`;");
      $query = $this->db->query("
          CREATE PROCEDURE `kardexegreso_ins`(
            vidpro int, 
            vdocumento varchar(255), 
            vdetalle varchar(255), 
            vcantidad decimal(11,2),
            vvalorunitario decimal(15,6), 
            vcostototal decimal(11,2), 
            vunidad int,
            vidusu int,
            vidalmacen int)
            BEGIN
              declare vsaldocant decimal(11,2);  
              declare vsaldovalorunit decimal(11,2);  
              declare vsaldocosto decimal(11,2);  
              declare vid int;                                                      

              DECLARE EXIT handler for sqlexception select 0; 

              if (vcantidad != 0) then
                set vvalorunitario = round(vcostototal / vcantidad, 6); 
              else  
                set vvalorunitario = 0;
              end if;
              
              set vsaldocant = 0;
              set vsaldovalorunit = 0;
              set vsaldocosto = 0;
              
              set @maxid = ifnull((select max(id_kardex) as maxid from kardex where id_producto = vidpro), 0);
              if (@maxid > 0) then
                set vsaldocant  = ifnull((select saldocantidad from kardex where id_kardex = @maxid),0);                                                      
                set vsaldovalorunit = ifnull((select saldovalorunitario from kardex where id_kardex = @maxid),0);                                                      
                set vsaldocosto  = ifnull((select saldocostototal from kardex where id_kardex = @maxid),0);                                                      
              end if;  

              set vsaldocant = vsaldocant - vcantidad;
              set vsaldocosto = vsaldocosto - vcostototal;
              if (vsaldocant > 0) then
                  set vsaldovalorunit = round(vsaldocosto / vsaldocant,2);
              else 
                  set vsaldocant = 0;
                  set vsaldovalorunit = 0;
                  set vsaldocosto = 0;
              end if;  
                
              INSERT INTO kardex (id_producto, documento, detalle, tipomovimiento, 
                                 cantidad, valorunitario, costototal, saldocantidad, 
                                 saldovalorunitario, saldocostototal, idunidadstock, 
                                 idusuario, id_almacen)
                  VALUE (vidpro, vdocumento, vdetalle, 0, vcantidad, 
                         vvalorunitario, vcostototal, vsaldocant, 
                         vsaldovalorunit, vsaldocosto, vunidad, 
                         vidusu, vidalmacen); 

              set vid=(select last_insert_id());

              select vid;

            END");
    }

    public function actualiza_procalm_kardexingreso_ins(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `kardexingreso_ins`;");
      $query = $this->db->query("
          CREATE PROCEDURE `kardexingreso_ins`(
            vidpro int, 
            vdocumento varchar(255), 
            vdetalle varchar(255), 
            vcantidad decimal(11,2),
            vvalorunitario decimal(15,6), 
            vcostototal decimal(11,2), 
            vunidad int,
            vidusu int,
            vidalmacen int)
            BEGIN
              declare vsaldocant decimal(11,2);  
              declare vsaldovalorunit decimal(11,2);  
              declare vsaldocosto decimal(11,2);  
              declare vid int;                                                      

              DECLARE EXIT handler for sqlexception select 0; 
              
              if (vcantidad != 0) then
                set vvalorunitario = round(vcostototal / vcantidad, 6); 
              else  
                set vvalorunitario = 0;
              end if;

              set vsaldocant = 0;
              set vsaldovalorunit = 0;
              set vsaldocosto = 0;
              
              set @maxid = ifnull((select max(id_kardex) as maxid from kardex  where id_producto = vidpro), 0);
              if (@maxid > 0) then
                set vsaldocant  = ifnull((select saldocantidad from kardex where id_kardex = @maxid),0);                                                      
                set vsaldovalorunit = ifnull((select saldovalorunitario from kardex where id_kardex = @maxid),0);                                                      
                set vsaldocosto  = ifnull((select saldocostototal from kardex where id_kardex = @maxid),0);                                                      
              end if;  

              set vsaldocant = vsaldocant + vcantidad;
              set vsaldocosto = vsaldocosto + vcostototal;
              set vsaldovalorunit = vsaldovalorunit + vvalorunitario;
              if (vsaldocant > 0) then
                  set vsaldovalorunit = round(vsaldocosto / vsaldocant,2);
              else 
                  set vsaldocant = 0;
                  set vsaldovalorunit = 0;
                  set vsaldocosto = 0;
              end if;  
                
              INSERT INTO kardex (id_producto, documento, detalle, tipomovimiento, 
                                 cantidad, valorunitario, costototal, saldocantidad, 
                                 saldovalorunitario, saldocostototal, idunidadstock, 
                                 idusuario, id_almacen)
                  VALUE (vidpro, vdocumento, vdetalle, 1, vcantidad, 
                         vvalorunitario, vcostototal, vsaldocant, 
                         vsaldovalorunit, vsaldocosto, vunidad, 
                         vidusu, vidalmacen); 

              set vid=(select last_insert_id());

              select vid;

            END");
    }


      public function crea_tabla_documento_pago(){
            $this->db->query("CREATE TABLE `documento_pago` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `estado` int(11) NOT NULL,
                            `numero` varchar(25) NOT NULL,
                            `valor` decimal(11,2) NOT NULL,
                            `observaciones` varchar(1000) NULL,
                            `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                            `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        ");
            $this->db->query("DROP TEMPORARY TABLE IF EXISTS tbldoc;");
            $this->db->query("CREATE TEMPORARY TABLE tbldoc (id int AUTO_INCREMENT, idabono int, PRIMARY KEY (`id`));");
            $this->db->query("INSERT INTO tbldoc (idabono) SELECT id_abono FROM compra_abonos;");
            $this->db->query("INSERT INTO documento_pago (id, estado, numero, valor, observaciones) 
                                SELECT id, 1, '', 0, '' FROM tbldoc;");
            $this->db->query("UPDATE compra_abonos c
                                INNER JOIN tbldoc t on t.idabono = c.id_abono
                                SET c.iddocpago = t.id;");

            $this->db->query("DELETE FROM tbldoc WHERE id != 0;");
            $this->db->query("INSERT INTO tbldoc (idabono) SELECT id_abono FROM gastos_abonos;");
            $this->db->query("INSERT INTO documento_pago (id, estado, numero, valor, observaciones) 
                                SELECT id, 1, '', 0, '' FROM tbldoc;");
            $this->db->query("UPDATE gastos_abonos c
                                INNER JOIN tbldoc t on t.idabono = c.id_abono
                                SET c.iddocpago = t.id;");

      }      

    public function crea_tabla_deposito_tipo(){
      $this->db->query("CREATE TABLE `deposito_tipo` (
                            `id` tinyint(2) NOT NULL,
                            `tipo` varchar(25) ,
                            PRIMARY KEY (`id`)
                           ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                          ");
      $this->db->query("INSERT INTO deposito_tipo (id, tipo) VALUES (1, 'CAJA EFECTIVO');");
      $this->db->query("INSERT INTO deposito_tipo (id, tipo) VALUES (2, 'CAJA CHICA');");
      $this->db->query("INSERT INTO deposito_tipo (id, tipo) VALUES (3, 'CUENTA BANCO');");
    }

    public function crea_tabla_deposito_efectivo(){
      $this->db->query("CREATE TABLE `deposito_efectivo` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `idtipo` tinyint(2) NOT NULL,
                          `idsucursal` int(11) NOT NULL,
                          `idcuentacontable` int(11) NULL,
                          `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      $res = $this->existe_foreign_key('FK_deposito_efectivo_tipo');
      if ($res != true) $this->add_foreign_key('deposito_efectivo', 'FK_deposito_efectivo_tipo', 
                                               'idtipo', 'deposito_tipo', 'id');
      $res = $this->existe_foreign_key('FK_deposito_efectivo_sucursal');
      if ($res != true) $this->add_foreign_key('deposito_efectivo', 'FK_deposito_efectivo_sucursal', 
                                              'idsucursal', 'sucursal', 'id_sucursal');
                                         
      $this->db->query("INSERT INTO deposito_efectivo (id, idtipo, idsucursal)
                          SELECT c.id_caja, 1, p.id_sucursal
                            FROM caja_efectivo c
                            INNER JOIN punto_emision p on p.id_puntoemision = c.id_puntoemision;");

      $res = $this->existe_foreign_key('FK_caja_efectivo_deposito');
      if ($res != true) $this->add_foreign_key('caja_efectivo', 'FK_caja_efectivo_deposito', 
                                               'id_caja', 'deposito_efectivo', 'id');
    }

    public function crea_tabla_caja_chica_movimiento(){
      $this->db->query("CREATE TABLE `caja_chica_movimiento` (
                          `id_mov` int(11) NOT NULL AUTO_INCREMENT,
                          `id_caja` int(11) NOT NULL,
                          `fechaapertura` date DEFAULT NULL,
                          `fecharegistroapertura` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                          `usuarioapertura` int(11) DEFAULT NULL,
                          `descripcion` varchar(255) DEFAULT NULL,
                          `montoapertura` decimal(11,2) DEFAULT NULL,
                          `estatus` int(11) DEFAULT NULL,
                          `fechacierre` date DEFAULT NULL,
                          `fecharegistrocierre` datetime DEFAULT NULL,
                          `usuariocierre` int(11) DEFAULT NULL,
                          `montocierre` decimal(11,2) DEFAULT NULL,
                          `obs` text,
                          PRIMARY KEY (`id_mov`) 
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tmpmovcaja;");
      $query = $this->db->query("CREATE TEMPORARY TABLE tmpmovcaja (
                                  `fechaapertura` date, `fecharegistroapertura` datetime,
                                  `usuarioapertura` int(11), `descripcion` varchar(255),
                                  `montoapertura` decimal(11,2), `estatus` int(11),
                                  `fechacierre` date, `fecharegistrocierre` datetime,
                                  `usuariocierre` int(11), `montocierre` decimal(11,2),
                                  `obs` text);");

      $res = $this->existe_tabla('caja_chica');
      if ($res == true){  
        $this->db->query("INSERT INTO tmpmovcaja 
                            SELECT fechaapertura, fecharegistroapertura, usuarioapertura, 
                                 descripcion, montoapertura, estatus, fechacierre,
                                 fecharegistrocierre, usuariocierre, montocierre, obs
                            FROM caja_chica;");
        
        $this->db->query("DROP TABLE IF EXISTS `caja_chica`;");                            

        $this->db->query("CREATE TABLE `caja_chica` (
          `id_caja` int(11) NOT NULL,
          `nom_caja` varchar(25) DEFAULT NULL,
          `activo` tinyint(1) NOT NULL,
          PRIMARY KEY (`id_caja`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $res = $this->existe_foreign_key('FK_caja_chica_deposito');
        if ($res != true) $this->add_foreign_key('caja_chica', 'FK_caja_chica_deposito', 
                                                'id_caja', 'deposito_efectivo', 'id');

        $this->db->query("INSERT INTO deposito_efectivo (idtipo, idsucursal)
                            SELECT 2, id_sucursal FROM sucursal;");

        $this->db->query("INSERT INTO caja_chica (id_caja, nom_caja, activo)
                            SELECT d.id, s.nom_sucursal, 1 
                            FROM deposito_efectivo d 
                            INNER JOIN sucursal s on s.id_sucursal = d.idsucursal
                            WHERE d.idtipo = 2;");

        $this->db->query("UPDATE caja_chicaingreso SET id_caja = (SELECT id_caja FROM caja_chica LIMIT 1)");

        $this->db->query("INSERT INTO caja_chica_movimiento (id_caja, fechaapertura, fecharegistroapertura, usuarioapertura, 
                                                            descripcion, montoapertura, estatus, fechacierre,
                                                            fecharegistrocierre, usuariocierre, montocierre, obs)
                            SELECT (SELECT id_caja FROM caja_chica LIMIT 1),
                                fechaapertura, fecharegistroapertura, usuarioapertura, 
                                descripcion, montoapertura, estatus, fechacierre,
                                fecharegistrocierre, usuariocierre, montocierre, obs
                            FROM tmpmovcaja;");

      }                      

      $res = $this->existe_foreign_key('FK_caja_chicaingreso_caja');
      if ($res != true) $this->add_foreign_key('caja_chicaingreso', 'FK_caja_chicaingreso_caja', 
                                               'id_caja', 'caja_chica', 'id_caja');
    }

    public function actualiza_procalm_cajachica_insapertura(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `cajachica_insapertura`;");
      $query = $this->db->query("
          CREATE PROCEDURE `cajachica_insapertura`(
            vusuario int,
            vcaja int,
            vfecha date,
            vmonto double,
            vdescripcion varchar(255)
            )
            BEGIN

              DECLARE EXIT handler for sqlexception select 0; 

              INSERT INTO caja_chica_movimiento(id_caja, fechaapertura, fecharegistroapertura, usuarioapertura, 
                                                descripcion, montoapertura, estatus) 
                VALUES(vcaja, vfecha, NOW(), vusuario, vdescripcion, vmonto, 0);

              select last_insert_id();

            END");
    }

    public function actualiza_procalm_cajachica_resumen(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `cajachica_resumen`;");
      $query = $this->db->query("
          CREATE PROCEDURE `cajachica_resumen`(
            vcaja int
            )
            BEGIN
              select fechaapertura, montoapertura, 
                   ifnull((select sum(montototal) from compra 
                     where cajachica = 1 and id_sucursal = d.idsucursal and 
                           fecha between fechaapertura and ifnull(fechacierre, now())),0) as compras,
                   ifnull((select sum(total) from gastos 
                     where id_sucursal = d.idsucursal and 
                           fecha between fechaapertura and ifnull(fechacierre, now())),0) as gastos,
                   ifnull((select sum(monto) from caja_chicaingreso
                     where id_caja = vcaja AND  fechaingreso between fechaapertura and ifnull(fechacierre, now())),0) as ingresos,
                   (montoapertura + ifnull((select sum(monto) from caja_chicaingreso
                     where id_caja = vcaja AND fechaingreso between fechaapertura and ifnull(fechacierre, now())),0) - 
                                    ifnull((select sum(montototal) from compra 
                                             where cajachica = 1 and id_sucursal = d.idsucursal and 
                                                   fecha between fechaapertura and ifnull(fechacierre, now())),0) -     
                                    ifnull((select sum(total) from gastos 
                                             where id_sucursal = d.idsucursal and fecha between fechaapertura and ifnull(fechacierre, now())),0)) as resumen         
              from caja_chica_movimiento m
              inner join deposito_efectivo d on d.id = m.id_caja
              where id_caja = vcaja AND m.estatus = 0;
            END");
    }

    public function actualiza_procalm_cajachica_cierre(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `cajachica_cierre`;");
      $query = $this->db->query("
          CREATE PROCEDURE `cajachica_cierre`(
            vcaja int,
            vidusuario int,
            vfecha date,
            vmonto decimal(11,2),
            vobs text
            )
            BEGIN
            update caja_chica_movimiento set 
              fechacierre = vfecha,
              fecharegistrocierre = now(),
              usuariocierre = vidusuario,
              montocierre = vmonto,
              estatus = 1,
              obs = vobs
              where id_caja = vcaja AND estatus=0;
            END");
    }

    public function actualiza_procalm_cajachica_movimientos(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `cajachica_movimientos`;");
      $query = $this->db->query("
          CREATE PROCEDURE `cajachica_movimientos`(
            vid int
            )
            BEGIN           
              declare vdesde datetime;
              declare vhasta datetime;
              
              set vdesde = (select fechaapertura from caja_chica_movimiento where id_mov = vid);
              set vhasta = (select fechacierre from caja_chica_movimiento where id_mov = vid);
              set vhasta = ifnull(vhasta, now());
              
              select fecha, nro_factura as numerodoc, montototal as valor, '' as descripcion, 'Egreso' as tipo  
                from compra 
                where cajachica = 1 and fecha between vdesde and vhasta
              union  
              select fecha, nro_factura as numerodoc, total as valor, descripcion, 'Egreso' as tipo  
                from gastos 
                where fecha between vdesde and vhasta
              union
              select fechaingreso as fecha, numeroingreso as numerodoc, monto as valor, descripcion, 'Ingreso' as tipo   
                from caja_chicaingreso
                where fechaingreso between vdesde and vhasta  
              order by fecha;
            END");
    }

    public function crea_tabla_documento_pagodeposito(){
      $this->db->query("CREATE TABLE `documento_pagodeposito` (
                      `iddocumento` int(11) NOT NULL ,
                      `iddeposito` int(11) NOT NULL ,
                      PRIMARY KEY (`iddocumento`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                  ");

      $this->db->query("INSERT INTO documento_pagodeposito (iddocumento, iddeposito) 
                          SELECT DISTINCT iddocpago, d.id 
                            FROM compra_abonos a
                            INNER JOIN compra c on c.id_comp = a.id_compra
                            INNER JOIN deposito_efectivo d on d.idsucursal = c.id_sucursal
                            WHERE d.idtipo = 2;");

      $this->db->query("INSERT INTO documento_pagodeposito (iddocumento, iddeposito) 
                          SELECT DISTINCT iddocpago, d.id 
                            FROM gastos_abonos a
                            INNER JOIN gastos c on c.id_gastos = a.id_gastos
                            INNER JOIN deposito_efectivo d on d.idsucursal = c.id_sucursal
                            WHERE d.idtipo = 2 AND
                                  NOT iddocpago IN (SELECT iddocumento FROM documento_pagodeposito);");
    }      

    public function crea_tabla_venta_detalle_serie_tmp(){
      $this->db->query("CREATE TABLE `venta_detalle_serie_tmp` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `id_detalle` int(11) NOT NULL,
                          `id_serie` int(11) NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      $res = $this->existe_foreign_key('FK_venta_detalle_serie_tmp_detalle');
      if ($res != true) $this->add_foreign_key('venta_detalle_serie_tmp', 'FK_venta_detalle_serie_tmp_detalle', 
                                               'id_detalle', 'venta_detalle_tmp', 'id_detalle');
      if ($res != true) $this->add_foreign_key('venta_detalle_serie_tmp', 'FK_venta_detalle_serie_tmp_serie', 
                                               'id_serie', 'producto_serie', 'id_serie');
    }

    public function crea_tabla_servicio_egresoinventario(){
      $this->db->query("CREATE TABLE `servicio_egresoinventario` (
                          `id_servicio` int(11) NOT NULL,
                          `id_documento` int(11) NOT NULL,
                          PRIMARY KEY (`id_servicio`,`id_documento`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      $res = $this->existe_foreign_key('FK_servicio_egresoinventario_servicio');
      if ($res != true) $this->add_foreign_key('servicio_egresoinventario', 'FK_servicio_egresoinventario_servicio', 
                                               'id_servicio', 'servicio', 'id_servicio');
      if ($res != true) $this->add_foreign_key('servicio_egresoinventario', 'FK_servicio_egresoinventario_documento', 
                                               'id_documento', 'inventariodocumento', 'id_documento');
    }

    public function actualiza_procalm_proveedor_ins(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `proveedor_ins`;");
      $query = $this->db->query("
          CREATE PROCEDURE `proveedor_ins`(
            vnombre varchar(255),
            vtipo varchar(3),
            videntificador varchar(255),
            vrazonsocial varchar(255),
            vtelefono varchar(255),
            vcorreo varchar(255),
            vciudad varchar(255),
            vdireccion longtext,
            vrelacionada tinyint,
            vcategoriacontable int
            )
            BEGIN
              DECLARE EXIT handler for sqlexception select 0; 

              insert into proveedor (nom_proveedor, tip_ide_proveedor, nro_ide_proveedor,
                                     razon_social, telf_proveedor, correo_proveedor,
                                     ciudad_proveedor, direccion_proveedor, relacionada,
                                     idcategoriacontable) 
                values (vnombre, vtipo, videntificador, vrazonsocial, vtelefono,
                        vcorreo, vciudad, vdireccion, vrelacionada, vcategoriacontable);

              select last_insert_id();
            END");
    }

    public function actualiza_procalm_proveedor_upd(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `proveedor_upd`;");
      $query = $this->db->query("
          CREATE PROCEDURE `proveedor_upd`(
            vid int,
            vnombre varchar(255),
            vtipo varchar(3),
            videntificador varchar(255),
            vrazonsocial varchar(255),
            vtelefono varchar(255),
            vcorreo varchar(255),
            vciudad varchar(255),
            vdireccion longtext,
            vrelacionada tinyint,
            vcategoriacontable int
            )
            BEGIN

              DECLARE EXIT handler for sqlexception select 0; 

              update proveedor set
                nom_proveedor=vnombre, 
                tip_ide_proveedor=vtipo, 
                nro_ide_proveedor=videntificador,
                razon_social=vrazonsocial, 
                telf_proveedor=vtelefono, 
                correo_proveedor=vcorreo,
                ciudad_proveedor=vciudad, 
                direccion_proveedor=vdireccion, 
                relacionada=vrelacionada
                where id_proveedor=vid;        

              IF vcategoriacontable != 0 THEN
                  update proveedor set
                        idcategoriacontable=vcategoriacontable 
                    where id_proveedor=vid;        
              ELSE
                  update proveedor set
                        idcategoriacontable=NULL 
                    where id_proveedor=vid;        
              END IF;    

              select 1;
            END");
    }

    public function crea_tabla_venta_credito_config(){
      $this->db->query("CREATE TABLE `venta_credito_config` (
                          `fecha_chequeo` date DEFAULT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      $this->db->query("INSERT INTO venta_credito_config (fecha_chequeo) VALUES('2001-01-01')");
    }

    public function crea_tabla_cliente_categoriaventa(){
      $this->db->query("CREATE TABLE `cliente_categoriaventa` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `categoria` varchar(255) DEFAULT NULL,
                          `monto_minimo` decimal(11,2) DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_cliente_categoria_tipoprecio(){
      $this->db->query("CREATE TABLE `cliente_categoria_tipoprecio` (
                          `id_categoria` int(11) NOT NULL,
                          `id_precio` int(11) NOT NULL,
                          CONSTRAINT FK_cliente_categoria_tipoprecio_categoria FOREIGN KEY (id_categoria)
                              REFERENCES cliente_categoriaventa(id),
                          CONSTRAINT FK_cliente_categoria_tipoprecio_precio FOREIGN KEY (id_precio)
                              REFERENCES precios(id_precios),
                          PRIMARY KEY (`id_categoria`, `id_precio`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function actualiza_procalm_pedido_det_ins(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `pedido_det_ins`;");
      $query = $this->db->query("
          CREATE PROCEDURE `pedido_det_ins`(
            vidpro int,
            vidalm int,
            vid_mesa int
            )
            BEGIN
              declare vid int;                                                      

              DECLARE EXIT handler for sqlexception select 0; 

              INSERT INTO pedido_detalle (id_mesa, id_producto, cantidad, precio, estatus, variante, id_almacen, promo)
                  SELECT vid_mesa, pro_id, 1, pro_precioventa, '0', habilitavariante, vidalm, 0 
                  FROM producto WHERE pro_id = vidpro ;

              set vid=(select last_insert_id());

              select vid;
            END");
    }

    public function actualiza_procalm_mesa_ins(){
      $query = $this->db->query("DROP PROCEDURE IF EXISTS `mesa_ins`;");
      $query = $this->db->query("
          CREATE PROCEDURE `mesa_ins`(
            vnom varchar(255),
            varea int,
            vcap varchar(255),
            vimp int
            )
            BEGIN
              DECLARE EXIT handler for sqlexception select 0; 

              INSERT INTO mesa (nom_mesa, id_area, capacidad, id_comanda)

                VALUES(vnom, varea, vcap, vimp);

              select last_insert_id();
            END");
    }

    public function actualiza_procalm_mesa_upd(){
      $this->db->query("DROP PROCEDURE IF EXISTS `mesa_upd`;");
      $this->db->query("
          CREATE PROCEDURE `mesa_upd`(
            vid int,
            vnom varchar(255),
            varea int,
            vcap varchar(255),
            vimp int
            )
            BEGIN
              DECLARE EXIT handler for sqlexception select 0; 

              update mesa set nom_mesa=vnom, id_area=varea, capacidad=vcap, id_comanda=vimp

                where id_mesa=vid;

              select 1;
            END");
    }

    public function crea_tabla_precio_compraventa(){
      $this->db->query("CREATE TABLE `precio_compraventa` (
                          `id_precio` int(11) NOT NULL,
                          `porciento` decimal(11,2) DEFAULT 0,
                          PRIMARY KEY (`id_precio`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_color_skin(){
      $this->db->query("CREATE TABLE `color_skin` (
                          `id` int(11) NOT NULL,
                          `skin` varchar(255) DEFAULT NULL,
                          `color` varchar(255) DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      $this->db->query("INSERT INTO color_skin (id, skin, color) VALUES(1, 'skin-blue', 'AZUL')");
      $this->db->query("INSERT INTO color_skin (id, skin, color) VALUES(2, 'skin-yellow', 'AMARILLO')");
      $this->db->query("INSERT INTO color_skin (id, skin, color) VALUES(3, 'skin-green', 'VERDE')");
      $this->db->query("INSERT INTO color_skin (id, skin, color) VALUES(4, 'skin-purple', 'PURPURA')");
      $this->db->query("INSERT INTO color_skin (id, skin, color) VALUES(5, 'skin-red', 'ROJO')");
      $this->db->query("INSERT INTO color_skin (id, skin, color) VALUES(6, 'skin-black', 'BLANCO')");
    }


    public function crea_tabla_sistema(){
      $this->db->query("CREATE TABLE `sistema` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `nombresistema` varchar(255) DEFAULT NULL,
                          `iconosistema` varchar(255) DEFAULT NULL,
                          `imagenfondo` varchar(255) DEFAULT NULL,
                          `id_colorheader` int(11) NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      $this->db->query("INSERT INTO sistema (nombresistema, iconosistema, imagenfondo, id_colorheader) 
                          VALUES('FACTUFÁCIL', '', '', 5)");
    }

    public function actualiza_sucursal_logo(){
        $this->add_columna_tabla('sucursal','logo_encab_path', 'varchar(255)', "");
        $sql_sel = $this->db->query("SELECT id_sucursal, logo_sucursal FROM sucursal");
        $result = $sql_sel->result();
        foreach ($result as $value) {            
          if ($value->logo_sucursal != null){  
            $mydate = date("dmY_His");
            $file_name = $mydate .'_'. $value->id_sucursal.'.jpg';
            $file_name_path = FCPATH.'/public/img/sucursal/'.$file_name;
            $pic = base64_decode($value->logo_sucursal);
            imagejpeg(imagecreatefromstring ( $pic ), $file_name_path);        
            $this->db->query("UPDATE sucursal SET logo_encab_path = '$file_name'
                                WHERE id_sucursal = $value->id_sucursal");
          }  
        }
    }

    public function crea_tabla_venta_config_adicional(){
      $this->db->query("CREATE TABLE `venta_config_adicional` (
                            `id_config` int(11) NOT NULL AUTO_INCREMENT,
                            `nombre_datoadicional` varchar(255) DEFAULT NULL,
                            `activo` tinyint(1) NOT NULL,
                            PRIMARY KEY (`id_config`)
                           ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                          ");
    }

    public function crea_tabla_venta_dato_adicional_tmp(){
      $this->db->query("CREATE TABLE `venta_dato_adicional_tmp` (
                            `id_venta` int(11) NOT NULL,
                            `id_config` int(11) NOT NULL,
                            `datoadicional` varchar(255) DEFAULT NULL,
                            CONSTRAINT FK_dato_adicional_tmp_config FOREIGN KEY (id_config)
                              REFERENCES venta_config_adicional(id_config),
                            PRIMARY KEY (`id_venta`,`id_config`)
                           ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                          ");
    }

    public function crea_tabla_venta_dato_adicional(){
      $this->db->query("CREATE TABLE `venta_dato_adicional` (
                            `id_venta` int(11) NOT NULL,
                            `id_config` int(11) NOT NULL,
                            `datoadicional` varchar(255) DEFAULT NULL,
                            CONSTRAINT FK_dato_adicional_venta FOREIGN KEY (id_venta)
                              REFERENCES venta(id_venta),
                            CONSTRAINT FK_dato_adicional_config FOREIGN KEY (id_config)
                              REFERENCES venta_config_adicional(id_config),
                            PRIMARY KEY (`id_venta`,`id_config`)
                           ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                          ");
    }

    public function actualiza_producto_imagen(){
        $this->add_columna_tabla('producto','imagen_path', 'varchar(255)', "");
        $sql_sel = $this->db->query("SELECT pro_id, pro_imagen FROM producto");
        $result = $sql_sel->result();
        foreach ($result as $value) {            
          if ($value->pro_imagen != null){  
            $mydate = date("dmY_His");
            $file_name = $mydate .'_'. $value->pro_id.'.jpg';
            $file_name_path = FCPATH.'/public/img/producto/'.$file_name;
            $pic = base64_decode($value->pro_imagen);
            imagejpeg(imagecreatefromstring ( $pic ), $file_name_path);        
            $this->db->query("UPDATE producto SET imagen_path = '$file_name'
                                WHERE pro_id = $value->pro_id");
          }  
        }
    }

    public function crea_tabla_puntoventa_estado(){
      $this->db->query("CREATE TABLE `puntoventa_estado` (
                            `id` int(11) NOT NULL,
                            `estado` varchar(25) NOT NULL,
                            PRIMARY KEY (`id`)
                           ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                          ");
      $this->db->query("INSERT INTO puntoventa_estado (id, estado) VALUES(1, 'LIBRE')");
      $this->db->query("INSERT INTO puntoventa_estado (id, estado) VALUES(2, 'SERVICIO')");
      $this->db->query("INSERT INTO puntoventa_estado (id, estado) VALUES(3, 'MANTENIMIENTO')");
    }

}
