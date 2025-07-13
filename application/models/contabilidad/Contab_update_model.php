<?php

/* ------------------------------------------------
  ARCHIVO: Contab_update_model.php
  DESCRIPCION: Manejo de consultas para la actualizacion de BD Modulo Contable.
  FECHA DE CREACIÓN: 16/02/2019
 * 
  ------------------------------------------------ */
  
require_once(APPPATH.'models/Update_base_model.php');  

class Contab_update_model extends Update_base_model {

    function __construct() {
        parent::__construct();
    }

    public function actualizabase(){

        $res = $this->get_version();

        //if ($res > 3002) return 1;        

        $res = $this->existe_tabla('con_grupocuenta');
        if ($res != true) $this->crea_tabla_con_grupocuenta();
  
        $res = $this->existe_tabla('con_plancuenta');
        if ($res != true) $this->crea_tabla_con_plancuenta();
  
        $res = $this->existe_tabla('con_plancuentaplantilla');
        if ($res != true) $this->crea_tabla_con_plancuentaplantilla();
  
        $res = $this->existe_tabla('con_plancuentainactivo');
        if ($res != true) $this->crea_tabla_con_plancuentainactivo();
  
        $res = $this->existe_tabla('con_tipocategoria');
        if ($res != true) $this->crea_tabla_con_tipocategoria();
  
        $res = $this->existe_tabla('con_categoriaventa');
        if ($res != true) $this->crea_tabla_con_categoriaventa();
  
        $res = $this->existe_tabla('con_categoriacompra');
        if ($res != true) $this->crea_tabla_con_categoriacompra();

        $res = $this->existe_columna_tabla('concepto_retencion','idcategoriacompra');
        if ($res != true) $this->add_columna_tabla('concepto_retencion','idcategoriacompra', ' int(11)', '');

        $res = $this->existe_columna_tabla('concepto_retencion','idcategoriaventa');
        if ($res != true) $this->add_columna_tabla('concepto_retencion','idcategoriaventa', ' int(11)', '');

        $res = $this->existe_columna_tabla('porcentaje_retencion_iva','idcategoriacompra');
        if ($res != true) $this->add_columna_tabla('porcentaje_retencion_iva','idcategoriacompra', ' int(11)', '');

        $res = $this->existe_columna_tabla('porcentaje_retencion_iva','idcategoriaventa');
        if ($res != true) $this->add_columna_tabla('porcentaje_retencion_iva','idcategoriaventa', ' int(11)', '');
              
        $res = $this->existe_tabla('con_categoria');
        if ($res != true) $this->crea_tabla_con_categoria();
  
        $res = $this->existe_tabla('con_configuracioncategoria');
        if ($res != true) $this->crea_tabla_con_configuracioncategoria();
  
        $res = $this->existe_tabla('con_cuentaclienteformapago');
        if ($res != true) $this->crea_tabla_con_cuentaclienteformapago();
  
        $res = $this->existe_tabla('con_cuentaclientetarjeta');
        if ($res != true) $this->crea_tabla_con_cuentaclientetarjeta();
  
        $res = $this->existe_tabla('con_cuentaproveedorformapago');
        if ($res != true) $this->crea_tabla_con_cuentaproveedorformapago();
  
        $res = $this->existe_tabla('con_cuentaproveedortarjeta');
        if ($res != true) $this->crea_tabla_con_cuentaproveedortarjeta();

        $res = $this->existe_tabla('con_cuentacategoriagasto');
        if ($res != true) $this->crea_tabla_con_cuentacategoriagasto();
  
        $res = $this->existe_tabla('con_ejercicio');
        if ($res != true) $this->crea_tabla_con_ejercicio();
  
        $res = $this->existe_tabla('con_estadocomprobante');
        if ($res != true) $this->crea_tabla_con_estadocomprobante();
  
        $res = $this->existe_tabla('con_tipocomprobante');
        if ($res != true) $this->crea_tabla_con_tipocomprobante();

        $res = $this->existe_tabla('con_tipocomprobante_sucursal');
        if ($res != true) $this->crea_tabla_con_tipocomprobante_sucursal();
        
        $res = $this->existe_tabla('con_comprobante');
        if ($res != true) $this->crea_tabla_con_comprobante();
  
        $res = $this->existe_tabla('con_comprobantedocumento');
        if ($res != true) $this->crea_tabla_con_comprobantedocumento();      
  
        $res = $this->existe_tabla('con_comprobanteanulado');
        if ($res != true) $this->crea_tabla_con_comprobanteanulado();
  
        $res = $this->existe_tabla('con_comprobantedetalle');
        if ($res != true) $this->crea_tabla_con_comprobantedetalle();
  
        $res = $this->existe_tabla('con_tmpcomprobantedetalle');
        if ($res != true) $this->crea_tabla_con_tmpcomprobantedetalle();
  
        $res = $this->existe_tabla('con_comprobantedetalle_cliente');
        if ($res != true) $this->crea_tabla_con_comprobantedetalle_cliente();
  
        $res = $this->existe_tabla('con_comprobantedetalle_proveedor');
        if ($res != true) $this->crea_tabla_con_comprobantedetalle_proveedor();
  
        $res = $this->existe_columna_tabla('clientes','idcategoriacontable');
        $strsql = "update clientes set idcategoriacontable = (SELECT id FROM con_categoria WHERE idtipocategoria=1 LIMIT 1)";
        if ($res != true) $this->add_columna_tabla('clientes','idcategoriacontable', ' int(11)', $strsql);
  
        $res = $this->existe_columna_tabla('proveedor','idcategoriacontable');
        $strsql = "update proveedor set idcategoriacontable = (SELECT id FROM con_categoria WHERE idtipocategoria=2 LIMIT 1)";
        if ($res != true) $this->add_columna_tabla('proveedor','idcategoriacontable', ' int(11)', $strsql);
  
        $res = $this->existe_columna_tabla('producto','idcategoriacontable');
        $strsql = "update producto set idcategoriacontable = (SELECT id FROM con_categoria WHERE idtipocategoria=3 LIMIT 1)";
        if ($res != true) $this->add_columna_tabla('producto','idcategoriacontable', ' int(11)', $strsql);
  
        $res = $this->existe_tabla('con_saldo');
        if ($res != true) $this->crea_tabla_con_saldo();
  
        $this->actualiza_procalm_con_lista_operaciones();
        $this->actualiza_procalm_con_balance_sumasaldo();     
        $this->actualiza_procalm_con_balance_situacion();

        $res = $this->existe_columna_tabla('inventariodocumento','idcategoriacontable');
        $strsql = "UPDATE inventariodocumento 
                    SET idcategoriacontable = CASE WHEN id_tipodoc in (5,8) 
                                                THEN (SELECT id FROM con_categoria WHERE idtipocategoria=12 LIMIT 1)
                                                ELSE (SELECT id FROM con_categoria WHERE idtipocategoria=11 LIMIT 1)
                                              END;";
        if ($res != true) $this->add_columna_tabla('inventariodocumento','idcategoriacontable', ' int(11)', $strsql);

        $res = $this->existe_columna_tabla('tmp_movinv','idcategoriacontable');
        if ($res != true) $this->add_columna_tabla('tmp_movinv','idcategoriacontable', ' int(11)', '');
        $res = $this->existe_columna_tabla('tmp_movinv','idcategoriacontabledestino');
        if ($res != true) $this->add_columna_tabla('tmp_movinv','idcategoriacontabledestino', ' int(11)', '');

        $res = $this->existe_columna_tabla('con_tipocomprobante','prefijo');
        if ($res != true) $this->add_columna_tabla('con_tipocomprobante','prefijo', ' varchar(10)', '');

        return 1;
    }    


    public function crea_tabla_con_grupocuenta(){
        $this->db->query("CREATE TABLE `con_grupocuenta` (
                                `id` int(11) NOT NULL,
                                `descripcion` varchar(255) DEFAULT NULL,
                                PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
        $this->db->query("INSERT INTO con_grupocuenta (id, descripcion) VALUES(1, 'ACTIVO')");
        $this->db->query("INSERT INTO con_grupocuenta (id, descripcion) VALUES(2, 'PASIVO')");
        $this->db->query("INSERT INTO con_grupocuenta (id, descripcion) VALUES(3, 'PATRIMONIO NETO')");
        $this->db->query("INSERT INTO con_grupocuenta (id, descripcion) VALUES(4, 'INGRESO')");
        $this->db->query("INSERT INTO con_grupocuenta (id, descripcion) VALUES(5, 'COSTO Y GASTO')");
        $this->db->query("INSERT INTO con_grupocuenta (id, descripcion) VALUES(6, 'GANANCIA')");
        $this->db->query("INSERT INTO con_grupocuenta (id, descripcion) VALUES(7, 'RESULTADO')");
    }

    public function crea_tabla_con_plancuenta(){
        $this->db->query("CREATE TABLE `con_plancuenta` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `idempresa` int(11) NOT NULL,
                                `idcuentasuperior` int(11) NULL,
                                `idgrupocuenta` int(11) NULL,
                                `codigonivel` varchar(20) NOT NULL,
                                `codigocuenta` varchar(255) NOT NULL,
                                `descripcion` varchar(255) NOT NULL,
                                `nivel` tinyint(2) NOT NULL,
                                `esmovimiento` tinyint(1) NOT NULL,
                                `naturaleza` tinyint(1) NOT NULL,
                                `activo` tinyint(1) NOT NULL,
                                `idusuariocreacion` int(11) NOT NULL,
                                `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }

    public function crea_tabla_con_plancuentaplantilla(){
        $this->db->query("CREATE TABLE `con_plancuentaplantilla` (
                                `id` int(11) NOT NULL,
                                `idcuentasuperior` int(11) NULL,
                                `idgrupocuenta` int(11) NULL,
                                `codigonivel` varchar(20) NOT NULL,
                                `codigocuenta` varchar(255) NOT NULL,
                                `descripcion` varchar(255) NOT NULL,
                                `nivel` tinyint(2) NOT NULL,
                                `esmovimiento` tinyint(1) NOT NULL,
                                `naturaleza` tinyint(1) NOT NULL,
                                PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }

    public function crea_tabla_con_plancuentainactivo(){
        $this->db->query("CREATE TABLE `con_plancuentainactivo` (
                                `idcuenta` int(11) NOT NULL,
                                `idempresa` int(11) NOT NULL,
                                `idusuarioinactivacion` int(11) NOT NULL,
                                `fechainactivacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                `descripcion` varchar(255) NOT NULL,
                                PRIMARY KEY (`idcuenta`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }

    public function crea_tabla_con_tipocategoria(){
        $this->db->query("CREATE TABLE `con_tipocategoria` (
                                `id` int(11) NOT NULL,
                                `descripcion` varchar(255) NOT NULL,
                                `rangogrupocuenta` varchar(20) NOT NULL,
                                PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
        $this->db->query("INSERT INTO con_tipocategoria (id, descripcion, rangogrupocuenta) VALUES(1, 'CUENTAS POR COBRAR A CLIENTES','1')");
        $this->db->query("INSERT INTO con_tipocategoria (id, descripcion, rangogrupocuenta) VALUES(2, 'CUENTAS POR PAGAR A PROVEEDORES','2')");
        $this->db->query("INSERT INTO con_tipocategoria (id, descripcion, rangogrupocuenta) VALUES(3, 'INVENTARIO DE PRODUCTOS','1')");
        $this->db->query("INSERT INTO con_tipocategoria (id, descripcion, rangogrupocuenta) VALUES(4, 'RETENCION DE RENTA EN VENTAS','')");
        $this->db->query("INSERT INTO con_tipocategoria (id, descripcion, rangogrupocuenta) VALUES(5, 'RETENCION DE RENTA EN COMPRAS','')");
        $this->db->query("INSERT INTO con_tipocategoria (id, descripcion, rangogrupocuenta) VALUES(6, 'RETENCION DE IVA EN VENTAS','')");
        $this->db->query("INSERT INTO con_tipocategoria (id, descripcion, rangogrupocuenta) VALUES(7, 'RETENCION DE IVA EN COMPRAS','')");
        $this->db->query("INSERT INTO con_tipocategoria (id, descripcion, rangogrupocuenta) VALUES(8, 'CONFIGURACION DE VENTAS','')");
        $this->db->query("INSERT INTO con_tipocategoria (id, descripcion, rangogrupocuenta) VALUES(9, 'CONFIGURACION DE COMPRAS','')");
        $this->db->query("INSERT INTO con_tipocategoria (id, descripcion, rangogrupocuenta) VALUES(10, 'CONCEPTOS DE GASTO','')");/*no se usa*/
        $this->db->query("INSERT INTO con_tipocategoria (id, descripcion, rangogrupocuenta) VALUES(11, 'CONCEPTOS DE INGRESO DE INVENTARIO','')");
        $this->db->query("INSERT INTO con_tipocategoria (id, descripcion, rangogrupocuenta) VALUES(12, 'CONCEPTOS DE EGRESO DE INVENTARIO','')");
    }

    public function crea_tabla_con_categoriaventa(){
    $this->db->query("CREATE TABLE `con_categoriaventa` (
                            `id` int(11) NOT NULL,
                            `ingreso_bienes_ivanocero` int(11) NULL,
                            `ingreso_bienes_ivacero` int(11) NULL,
                            `ingreso_servicios_ivanocero` int(11) NULL,
                            `ingreso_servicios_ivacero` int(11) NULL,
                            `monto_iva` int(11) NULL,
                            `costo_ivanocero` int(11) NULL,
                            `costo_ivacero` int(11) NULL,
                            PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        ");
    $this->db->query("INSERT INTO con_categoriaventa (id) VALUES(1);");
    }                      

    public function crea_tabla_con_categoriacompra(){
    $this->db->query("CREATE TABLE `con_categoriacompra` (
                            `id` int(11) NOT NULL,
                            `monto_iva_compra` int(11) NULL,
                            `monto_iva_gasto` int(11) NULL,
                            PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        ");
    $this->db->query("INSERT INTO con_categoriacompra (id) VALUES(1);");
    }                      

    public function crea_tabla_con_categoria(){
        $this->db->query("CREATE TABLE `con_categoria` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `idtipocategoria` int(11) NOT NULL,
                                `categoria` varchar(255) NOT NULL,
                                PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(1, 'CLIENTES GENERALES')");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(2, 'PROVEEDORES GENERALES')");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(3, 'PRODUCTOS GENERALES')");

        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(4, 'RETENCION DE RENTA 1% EN VENTAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 1");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(4, 'RETENCION DE RENTA 2% EN VENTAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 2");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(4, 'RETENCION DE RENTA 5% EN VENTAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 5");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(4, 'RETENCION DE RENTA 8% EN VENTAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 8");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(4, 'RETENCION DE RENTA 10% EN VENTAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 10");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(4, 'RETENCION DE RENTA 13% EN VENTAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 13");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(4, 'RETENCION DE RENTA 22% EN VENTAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 22");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(4, 'RETENCION DE RENTA EN VENTAS (OTROS)')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE not porciento_cto_retencion in (1,2,5,8,10,13,22)");

        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(5, 'RETENCION DE RENTA 1% EN COMPRAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 1");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(5, 'RETENCION DE RENTA 2% EN COMPRAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 2");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(5, 'RETENCION DE RENTA 5% EN COMPRAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 5");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(5, 'RETENCION DE RENTA 8% EN COMPRAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 8");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(5, 'RETENCION DE RENTA 10% EN COMPRAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 10");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(5, 'RETENCION DE RENTA 13% EN COMPRAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 13");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(5, 'RETENCION DE RENTA 22% EN COMPRAS')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porciento_cto_retencion = 22");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(5, 'RETENCION DE RENTA EN COMPRAS (OTROS)')");
        $this->db->query("UPDATE concepto_retencion SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE not porciento_cto_retencion in (1,2,5,8,10,13,22)");

        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(6, 'RETENCION DE IVA 10% EN VENTAS')");
        $this->db->query("UPDATE porcentaje_retencion_iva SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porcentaje = 10");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(6, 'RETENCION DE IVA 20% EN VENTAS')");
        $this->db->query("UPDATE porcentaje_retencion_iva SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porcentaje = 20");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(6, 'RETENCION DE IVA 30% EN VENTAS')");
        $this->db->query("UPDATE porcentaje_retencion_iva SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porcentaje = 30");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(6, 'RETENCION DE IVA 50% EN VENTAS')");
        $this->db->query("UPDATE porcentaje_retencion_iva SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porcentaje = 50");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(6, 'RETENCION DE IVA 70% EN VENTAS')");
        $this->db->query("UPDATE porcentaje_retencion_iva SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porcentaje = 70");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(6, 'RETENCION DE IVA 100% EN VENTAS')");
        $this->db->query("UPDATE porcentaje_retencion_iva SET idcategoriaventa = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porcentaje = 100");

        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(7, 'RETENCION DE IVA 10% EN COMPRAS')");
        $this->db->query("UPDATE porcentaje_retencion_iva SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porcentaje = 10");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(7, 'RETENCION DE IVA 20% EN COMPRAS')");
        $this->db->query("UPDATE porcentaje_retencion_iva SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porcentaje = 20");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(7, 'RETENCION DE IVA 30% EN COMPRAS')");
        $this->db->query("UPDATE porcentaje_retencion_iva SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porcentaje = 30");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(7, 'RETENCION DE IVA 50% EN COMPRAS')");
        $this->db->query("UPDATE porcentaje_retencion_iva SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porcentaje = 50");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(7, 'RETENCION DE IVA 70% EN COMPRAS')");
        $this->db->query("UPDATE porcentaje_retencion_iva SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porcentaje = 70");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(7, 'RETENCION DE IVA 100% EN COMPRAS')");
        $this->db->query("UPDATE porcentaje_retencion_iva SET idcategoriacompra = (SELECT MAX(id) FROM con_categoria) 
                            WHERE porcentaje = 100");

        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(8, 'INGRESO BIENES IMP.%')");
        $this->db->query("UPDATE con_categoriaventa SET ingreso_bienes_ivanocero = (SELECT MAX(id) FROM con_categoria)");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(8, 'INGRESO BIENES 0%')");
        $this->db->query("UPDATE con_categoriaventa SET ingreso_bienes_ivacero = (SELECT MAX(id) FROM con_categoria)");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(8, 'INGRESO SERVICIOS IMP.%')");
        $this->db->query("UPDATE con_categoriaventa SET ingreso_servicios_ivanocero = (SELECT MAX(id) FROM con_categoria)");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(8, 'INGRESO SERVICIOS 0%')");
        $this->db->query("UPDATE con_categoriaventa SET ingreso_servicios_ivacero = (SELECT MAX(id) FROM con_categoria)");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(8, 'IVA EN VENTAS')");
        $this->db->query("UPDATE con_categoriaventa SET monto_iva = (SELECT MAX(id) FROM con_categoria)");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(8, 'COSTO VENTAS IMP.%')");
        $this->db->query("UPDATE con_categoriaventa SET costo_ivanocero = (SELECT MAX(id) FROM con_categoria)");
        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(8, 'COSTO VENTAS 0%')");
        $this->db->query("UPDATE con_categoriaventa SET costo_ivacero = (SELECT MAX(id) FROM con_categoria)");

        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(9, 'IVA EN COMPRAS')");
        $this->db->query("UPDATE con_categoriacompra SET monto_iva_compra = (SELECT MAX(id) FROM con_categoria)");

        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(9, 'IVA EN GASTOS')");
        $this->db->query("UPDATE con_categoriacompra SET monto_iva_gasto = (SELECT MAX(id) FROM con_categoria)");

        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(10, 'GASTOS GENERALES')");

        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(11, 'INGRESO DE INVENTARIO')");

        $this->db->query("INSERT INTO con_categoria (idtipocategoria, categoria) VALUES(12, 'EGRESO DE INVENTARIO')");
    }

    public function crea_tabla_con_configuracioncategoria(){
        $this->db->query("CREATE TABLE `con_configuracioncategoria` (
                                `idcategoria` int(11) NOT NULL,
                                `idempresa` int(11) NOT NULL,
                                `idcuenta` int(11) NOT NULL,
                                `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`idcategoria`,`idempresa`,`idcuenta`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_cuentaclienteformapago(){
        $this->db->query("CREATE TABLE `con_cuentaclienteformapago` (
                                `idformapago` int(11) NOT NULL,
                                `idempresa` int(11) NOT NULL,
                                `idcuenta` int(11) NOT NULL,
                                `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`idformapago`,`idempresa`,`idcuenta`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_cuentaclientetarjeta(){
        $this->db->query("CREATE TABLE `con_cuentaclientetarjeta` (
                                `idformapago` int(11) NOT NULL,
                                `idempresa` int(11) NOT NULL,
                                `idtarjeta` int(11) NOT NULL,
                                `idcuenta` int(11) NOT NULL,
                                `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`idformapago`,`idempresa`,`idtarjeta`,`idcuenta`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_cuentacategoriagasto(){
        $this->db->query("CREATE TABLE `con_cuentacategoriagasto` (
                                `idcategoria` int(11) NOT NULL,
                                `idempresa` int(11) NOT NULL,
                                `idcuenta` int(11) NOT NULL,
                                `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`idcategoria`,`idempresa`,`idcuenta`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_cuentaproveedorformapago(){
        $this->db->query("CREATE TABLE `con_cuentaproveedorformapago` (
                                `idformapago` int(11) NOT NULL,
                                `idempresa` int(11) NOT NULL,
                                `idcuenta` int(11) NOT NULL,
                                `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`idformapago`,`idempresa`,`idcuenta`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_cuentaproveedortarjeta(){
        $this->db->query("CREATE TABLE `con_cuentaproveedortarjeta` (
                                `idformapago` int(11) NOT NULL,
                                `idempresa` int(11) NOT NULL,
                                `idtarjeta` int(11) NOT NULL,
                                `idcuenta` int(11) NOT NULL,
                                `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`idformapago`,`idempresa`,`idtarjeta`,`idcuenta`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_ejercicio(){
        $this->db->query("CREATE TABLE `con_ejercicio` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `inicio` datetime DEFAULT NULL,
                                `fin` datetime DEFAULT NULL,
                                `descripcion` varchar(255) NOT NULL,
                                `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_estadocomprobante(){
        $this->db->query("CREATE TABLE `con_estadocomprobante` (
                                `id` tinyint(1) NOT NULL,
                                `estado` varchar(255) NOT NULL,
                                PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
        $this->db->query("INSERT INTO con_estadocomprobante (id, estado) VALUES(1, 'Pendiente')");
        $this->db->query("INSERT INTO con_estadocomprobante (id, estado) VALUES(2, 'Confirmado')");
        $this->db->query("INSERT INTO con_estadocomprobante (id, estado) VALUES(3, 'Anulado')");
    }      

    public function crea_tabla_con_tipocomprobante(){
        $this->db->query("CREATE TABLE `con_tipocomprobante` (
                                `id` tinyint(1) NOT NULL,
                                `nombre` varchar(255) NOT NULL,
                                `abreviatura` varchar(10) NOT NULL,
                                `prefijo` varchar(10) NOT NULL,
                                PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
        $this->db->query("INSERT INTO con_tipocomprobante (id, nombre, abreviatura, prefijo) VALUES(1, 'Diario', 'DIARIO', 'DIA')");
        $this->db->query("INSERT INTO con_tipocomprobante (id, nombre, abreviatura, prefijo) VALUES(2, 'Apertura de Ejercicio', 'APERT', 'APE')");
        $this->db->query("INSERT INTO con_tipocomprobante (id, nombre, abreviatura, prefijo) VALUES(3, 'Cierre de Ejercicio', 'CIERRE', 'CIE')");
        $this->db->query("INSERT INTO con_tipocomprobante (id, nombre, abreviatura, prefijo) VALUES(4, 'Documento de Venta', 'VENTA', 'VTA')");
        $this->db->query("INSERT INTO con_tipocomprobante (id, nombre, abreviatura, prefijo) VALUES(5, 'Documento de Cobro', 'COBRO', 'COB')");
        $this->db->query("INSERT INTO con_tipocomprobante (id, nombre, abreviatura, prefijo) VALUES(6, 'Documento de Compra', 'COMPRA', 'COM')");
        $this->db->query("INSERT INTO con_tipocomprobante (id, nombre, abreviatura, prefijo) VALUES(7, 'Documento de Gasto', 'GASTO', 'GAS')");
        $this->db->query("INSERT INTO con_tipocomprobante (id, nombre, abreviatura, prefijo) VALUES(8, 'Documento de Pago', 'PAGO', 'PAG')");
        $this->db->query("INSERT INTO con_tipocomprobante (id, nombre, abreviatura, prefijo) VALUES(9, 'Ingreso de Inventario', 'ING-INV', 'INGI')");
        $this->db->query("INSERT INTO con_tipocomprobante (id, nombre, abreviatura, prefijo) VALUES(10, 'Egreso de Inventario', 'EGRE-INV', 'EGRI')");
        $this->db->query("INSERT INTO con_tipocomprobante (id, nombre, abreviatura, prefijo) VALUES(11, 'Retencion de Venta', 'RET-VENTA', 'RVTA')");
        $this->db->query("INSERT INTO con_tipocomprobante (id, nombre, abreviatura, prefijo) VALUES(12, 'Retencion de Compra', 'RET-COMP', 'RCOM')");
        $this->db->query("INSERT INTO con_tipocomprobante (id, nombre, abreviatura, prefijo) VALUES(13, 'Retencion de Gasto', 'RET-GAST', 'RGAS')");
    }      

    public function crea_tabla_con_tipocomprobante_sucursal(){
        $this->db->query("CREATE TABLE `con_tipocomprobante_sucursal` (
                                `idtipo` tinyint(1) NOT NULL,
                                `idsucursal` int(11) NOT NULL,
                                `contador` int(11) NOT NULL,
                                PRIMARY KEY (`idtipo`,`idsucursal`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_comprobante(){
        $this->db->query("CREATE TABLE `con_comprobante` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `idempresa` int(11) NOT NULL,
                                `idsucursal` int(11) NOT NULL,
                                `idtipocomprobante` int(11) NOT NULL,
                                `idejercicio` int(11) NOT NULL,
                                `numero` int(11) NOT NULL,
                                `referencia` varchar(255) NOT NULL,
                                `fechaasiento` datetime NULL,
                                `idusuarioregistro` int(11) NOT NULL,
                                `idestado` tinyint(1) NOT NULL,
                                `monto` decimal(11,2) NOT NULL,
                                `descripcion` varchar(1000) NOT NULL,
                                `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_comprobantedocumento(){
    $this->db->query("CREATE TABLE `con_comprobantedocumento` (
                            `idcomprobante` int(11) NOT NULL,
                            `iddocreferencia` int(11) NOT NULL,
                            `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                            `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY (`idcomprobante`,`iddocreferencia`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        ");
    }      

    public function crea_tabla_con_comprobanteanulado(){
        $this->db->query("CREATE TABLE `con_comprobanteanulado` (
                                `idcomprobante` int(11) NOT NULL,
                                `idusuario` int(11) NOT NULL,
                                `fechaanulacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                `motivoanulacion` varchar(1000) NOT NULL,
                                PRIMARY KEY (`idcomprobante`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_comprobantemodificacion(){
    $this->db->query("CREATE TABLE `con_comprobantemodificacion` (
                            `idcomprobante` int(11) NOT NULL,
                            `idusuario` int(11) NOT NULL,
                            `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                            `motivomodificacion` varchar(1000) NOT NULL,
                            PRIMARY KEY (`idcomprobante`, `fechamodificacion`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        ");
    }      

    public function crea_tabla_con_comprobantereversion(){
        $this->db->query("CREATE TABLE `con_comprobantereversion` (
                                `idcomprobantereversion` int(11) NOT NULL,
                                `idcomprobanteoriginal` int(11) NOT NULL,
                                PRIMARY KEY (`idcomprobantereversion`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_comprobantedetalle(){
        $this->db->query("CREATE TABLE `con_comprobantedetalle` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `idcomprobante` int(11) NOT NULL,
                                `idcuenta` int(11) NOT NULL,
                                `debitocredito` tinyint(1) NOT NULL,
                                `valor` decimal(11,2) NOT NULL,
                                `concepto` varchar(1000) NOT NULL,
                                `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_tmpcomprobantedetalle(){
    $this->db->query("CREATE TABLE `con_tmpcomprobantedetalle` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `token` varchar(32) NOT NULL,
                            `idcuenta` int(11) NOT NULL,
                            `codigocuenta` varchar(255) NOT NULL,
                            `debitocredito` tinyint(1) NOT NULL,
                            `valor` decimal(11,2) NOT NULL,
                            `concepto` varchar(1000) NOT NULL,
                            `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                            `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        ");
    }      

    public function crea_tabla_con_comprobantedetalle_cliente(){
        $this->db->query("CREATE TABLE `con_comprobantedetalle_cliente` (
                                `iddetallecomprobante` int(11) NOT NULL,
                                `idcliente` int(11) NOT NULL,
                                PRIMARY KEY (`iddetallecomprobante`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_comprobantedetalle_proveedor(){
        $this->db->query("CREATE TABLE `con_comprobantedetalle_proveedor` (
                                `iddetallecomprobante` int(11) NOT NULL,
                                `idproveedor` int(11) NOT NULL,
                                PRIMARY KEY (`iddetallecomprobante`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function crea_tabla_con_saldo(){
        $this->db->query("CREATE TABLE `con_saldo` (
                                `idsucursal` int(11) NOT NULL,
                                `idcuenta` int(11) NOT NULL,
                                `saldo` decimal(11,2) NOT NULL,
                                `fechacreacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                `fechamodificacion` datetime DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`idsucursal`,`idcuenta`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            ");
    }      

    public function actualiza_procalm_con_lista_operaciones(){
    $query = $this->db->query("DROP PROCEDURE IF EXISTS `con_lista_operaciones`;");
    $query = $this->db->query("
        CREATE PROCEDURE `con_lista_operaciones`(
            vsucursalid int,
            vaccountid int,
            vdatefrom date,
            vdateto date,
            vpendiente int
            )
            BEGIN
            SET SQL_SAFE_UPDATES = 0;
            set @codigocuenta = (SELECT codigocuenta FROM con_plancuenta WHERE id = vaccountid);
            DROP TEMPORARY TABLE IF EXISTS tblcuenta;
            CREATE TEMPORARY TABLE tblcuenta AS 
                SELECT id FROM con_plancuenta
                WHERE codigocuenta like concat(@codigocuenta,'%');
            set @vsaldo = ifnull((select sum(v.valor * (case v.debitocredito when 1 then 1 else -1 end)) 
                                    from con_comprobantedetalle v
                                    inner join con_comprobante t on t.id = v.idcomprobante
                                    inner join tblcuenta c on c.id = v.idcuenta
                                    where ((vpendiente = 1 AND t.idestado <= 2)  OR (t.idestado = 2)) 
                                        and (vsucursalid = 0 or t.idsucursal = vsucursalid)
                                        and t.fechaasiento < vdatefrom),0); 
            DROP TEMPORARY TABLE IF EXISTS tblSaldo;
            CREATE TEMPORARY TABLE tblSaldo AS 
                SELECT t.id, date(fechaasiento) as fecha, numero, descripcion, referencia,
                    case when v.debitocredito = 1 then v.valor else 0 end as debito,
                    case when v.debitocredito = 0 then abs(v.valor) else 0 end as credito,
                    cast(0 as decimal(11,2)) as saldo
                from con_comprobante t 
                inner join con_comprobantedetalle v on v.idcomprobante=t.id
                inner join tblcuenta c on c.id = v.idcuenta
                where ((vpendiente = 1 AND t.idestado <= 2)  OR (t.idestado = 2)) 
                and (vsucursalid = 0 or t.idsucursal = vsucursalid)
                and fechaasiento between vdatefrom and vdateto                    
                order by fecha, numero;
                update tblSaldo set saldo=(@vsaldo:=@vsaldo + debito - credito);   
                select * from tblSaldo order by fecha, numero;   
            END");
    }

    public function actualiza_procalm_con_balance_sumasaldo(){
    $query = $this->db->query("DROP PROCEDURE IF EXISTS `con_balance_sumasaldo`;");
    $query = $this->db->query("
        CREATE PROCEDURE `con_balance_sumasaldo`(
            vsucursalid int,
            vdatefrom date,
            vdateto date,
            vpendiente int
            )
            BEGIN
            SET SQL_SAFE_UPDATES = 0;
            DROP TEMPORARY TABLE IF EXISTS tblcuenta;
            CREATE TEMPORARY TABLE tblcuenta AS 
                SELECT c.id, c.codigocuenta, c.descripcion,
                        cast(0 as decimal(11,2)) as debito, 
                        cast(0 as decimal(11,2)) as credito, 
                        cast(0 as decimal(11,2)) as saldo,  
                        IFNULL((SELECT SUM(d.valor * (case d.debitocredito when 1 then 1 else -1 end))
                                  FROM con_comprobantedetalle d 
                                  INNER JOIN con_comprobante a on a.id = d.idcomprobante                                         
                                  WHERE d.idcuenta = c.id and
                                        (vsucursalid = 0 or a.idsucursal = vsucursalid) and
                                        a.fechaasiento < vdatefrom and 
                                        ((vpendiente = 1 AND a.idestado <= 2)  OR (a.idestado = 2)) ), 0) as saldoanterior                          
                FROM con_plancuenta c
                WHERE c.esmovimiento = 1                          
                GROUP BY c.id, c.codigocuenta, c.descripcion;

            UPDATE tblcuenta
                SET debito = IFNULL((SELECT SUM(d.valor) 
                                        FROM con_comprobantedetalle d
                                        INNER JOIN con_comprobante c on c.id = d.idcomprobante
                                        WHERE d.debitocredito = 1 
                                        and ((vpendiente = 1 AND c.idestado <= 2)  OR (c.idestado = 2)) 
                                        and d.idcuenta = tblcuenta.id 
                                        and (vsucursalid = 0 or c.idsucursal = vsucursalid)
                                        and c.fechaasiento between vdatefrom and vdateto), 0),
                    credito = IFNULL((SELECT SUM(d.valor) 
                                        FROM con_comprobantedetalle d
                                        INNER JOIN con_comprobante c on c.id = d.idcomprobante
                                        WHERE d.debitocredito = 0 
                                            and ((vpendiente = 1 AND c.idestado <= 2)  OR (c.idestado = 2))  
                                            and d.idcuenta = tblcuenta.id 
                                            and (vsucursalid = 0 or c.idsucursal = vsucursalid)
                                            and c.fechaasiento between vdatefrom and vdateto), 0);

            UPDATE tblcuenta SET saldo = saldoanterior + debito - credito;

            DELETE FROM tblcuenta 
                WHERE saldo = 0 and saldoanterior = 0 and debito = 0 and credito = 0;

            SELECT * FROM tblcuenta ORDER BY codigocuenta;   
            END");
    }

    public function actualiza_procalm_con_balance_situacion(){
        $query = $this->db->query("DROP PROCEDURE IF EXISTS `con_balance_situacion`;");
        $query = $this->db->query("
            CREATE PROCEDURE `con_balance_situacion`(
                vsucursalid int,
                vnivel int,
                vdateto date,
                vpendiente int
                )
            BEGIN
                  SET SQL_SAFE_UPDATES = 0;
                  DROP TEMPORARY TABLE IF EXISTS tblsaldo;
                  CREATE TEMPORARY TABLE tblsaldo AS 
                    SELECT c.id, c.codigocuenta, 
                           IFNULL((SELECT SUM(d.valor * (case d.debitocredito when 1 then 1 else -1 end))
                                     FROM con_comprobantedetalle d 
                                        INNER JOIN con_comprobante a on a.id = d.idcomprobante                                         
                                     WHERE d.idcuenta = c.id and
                                           (vsucursalid = 0 or a.idsucursal = vsucursalid) and
                                           a.fechaasiento <= vdateto and 
                                           ((vpendiente = 1 AND a.idestado <= 2)  OR (a.idestado = 2))), 0) as valor                          
                      FROM con_plancuenta c
                      WHERE c.esmovimiento = 1;                          
  
                  DROP TEMPORARY TABLE IF EXISTS tblcuenta;
                  CREATE TEMPORARY TABLE tblcuenta AS 
                    SELECT c.id, c.codigocuenta, g.descripcion AS grupo, c.nivel, c.descripcion,
                           (SELECT SUM(s.valor) FROM tblsaldo s WHERE s.codigocuenta like concat(c.codigocuenta,'%')) as valor
                      FROM con_plancuenta c
                      INNER JOIN con_grupocuenta g on g.id = c.idgrupocuenta
                      WHERE c.nivel <= vnivel;                         
  
                  DELETE FROM tblcuenta WHERE IFNULL(valor, 0) = 0;
  
                  SELECT * FROM tblcuenta ORDER BY codigocuenta;   
            END");
        }
    

}    