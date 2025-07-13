<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth';
$route['404_override'] = 'errors/error_404'; // Modifcado JFRH
$route['translate_uri_dashes'] = FALSE;





//RUTAS DE CARLOS ZAMBRANO

//rutas para especialidad
$route['compra/list']['get']    = 'compra/list_destalle';
$route['compra/guardar_imei']['post'] = 'compra/guardar_masivo_imei';


//RUTAS PARA EL HOTEL
$route['hotel']['get']    = 'hotel/hotel_inicio_controller/index';
$route['hotel_reservas']['get']    = 'hotel/hotel_inicio_controller/index';
$route['hotel_admin']['get']    = 'hotel/hotel_inicio_controller/index';

//rutas de configuracion de parametros
$route['hotel_confi']['get']    = 'hotel/hotel_parametro_controller/index';
$route['hot_conf_show']['get']    = 'hotel/hotel_parametro_controller/list_parametro_control';
$route['hot_parametro_create']['post']    = 'hotel/hotel_parametro_controller/crea_parametro_control';
$route['hot_parametro_cod_padre']['get']    = 'hotel/hotel_parametro_controller/cod_padre';
$route['hot_parametro_search_cod_padre']['post']    = 'hotel/hotel_parametro_controller/cod_padre_search';
$route['hot_parametro_update']['post']    = 'hotel/hotel_parametro_controller/modi_parametro_control';


$route['hotel_cli']['get']    = 'hotel/hotel_inicio_controller/index';
$route['hotel_habi']['get']    = 'hotel/hotel_inicio_controller/index';


//ruta para carga de datos masivos en productos

$route['excel_producto']['post']    = 'excel/producto_excel_controller/import_data';
$route['excel_producto_guardar']['post']    = 'excel/producto_excel_controller/import_data_save';
$route['excel_producto_almacen']['get']    = 'Almacen/alamacen_producto_excel';


// rutas para devolucion de garantia
$route['garantia_devolucion_clientes']['get']    = 'Garantia/lst_clientes';
$route['garantia_devolucion_sucursales']['get']    = 'Garantia/lst_sucursales';
$route['garantia_devolucion_numero/(:any)']['get']    = 'Garantia/get_devolucionnumero/$1';
$route['garantia_devolucion_cliente_identificacion/(:any)']['get']    = 'Garantia/get_cliente_identificacion/$1';
$route['garantia_devolucion_cliente_productosgarantia/(:any)']['get']    = 'Garantia/get_cliente_productosgarantia/$1';
$route['garantia_devolucion_almacenes/(:any)']['get']    = 'Garantia/get_almacenes/$1';
$route['garantia_devolucion_cliente_nombre/(:any)']['get']    = 'Garantia/get_cliente_nombre/$1';
$route['garantia_devolucion_seriesdisponibles/(:any)']['get']    = 'Garantia/lst_seriesdisponibles/$1';
$route['garantia_devolucion_productos']['get']    = 'Garantia/lst_productos';
$route['garantia_devolucion_sel_serie_id/(:any)']['get']    = 'Garantia/sel_serie_id/$1';
$route['garantia_devolucion_guardar']['post']  = 'Garantia/guardar_devolucion';
$route['garantia_devolucion_documentos/(:any)']['get']    = 'Garantia/get_devolucion_documentos/$1/$2/$3';


// rutas para control de serie
$route['lst_estadoserie']['get']        = 'producto/lst_estadoserie';
$route['get_producto_nombre/(:any)']['get']        = 'producto/get_producto_nombre/$1';
$route['get_producto_series/(:any)']['get'] = 'producto/get_producto_series/$1/$2';
$route['producto_serie_actualizarestado']['post']  = 'producto/producto_serie_actualizarestado';


// RUTAS Contabilidad
$route['contab_plancuentas'] = 'contabilidad/contab_plancuentas';
$route['contab_ejercicios'] = 'contabilidad/contab_ejercicios';
$route['contab_comprobantes'] = 'contabilidad/contab_comprobante';
$route['contab_operaciones'] = 'contabilidad/contab_balance/operaciones';
$route['contab_balancesumasaldo'] = 'contabilidad/contab_balance/balancesumasaldo';
$route['contab_balancesituacion'] = 'contabilidad/contab_balance/balancesituacion';
$route['contab_categoriageneral'] = 'contabilidad/contab_categoria/general';
$route['contab_categoriaretencion'] = 'contabilidad/contab_categoria/retencion';
$route['contab_categoriacompraventa'] = 'contabilidad/contab_categoria/compraventa';
$route['contab_tipocomprobantes'] = 'contabilidad/contab_comprobante/tipocomprobantes';
$route['contab_configsucursal'] = 'contabilidad/contab_comprobante/configsucursal';
$route['contab_plancuentasimportar'] = 'contabilidad/contab_plancuentas/importar_cuentas';

// rutas para cliente categoria
$route['cliente_categoriasventa']['get']                   = 'cliente/lst_categoria_venta';
$route['cliente_categoriaventa_tipoprecios/(:any)']['get'] = 'cliente/lst_categoriaventa_precios/$1';
$route['cliente_categoriaventa_guardarimagen']['post']     = 'cliente/categoriaventa_guardarimagen';
$route['cliente_categoriaventa_guardar']['post']           = 'cliente/categoriaventa_guardar';
$route['cliente_categoriaventa_eliminar']['post']          = 'cliente/categoriaventa_eliminar';

// rutas para sucursales
$route['get_sucursales_usuario']['get']    = 'Sucursal/lst_sucursales_usuario';

// rutas para petshop
$route['petshop/get_sucursales_usuario']['get']    = 'Sucursal/lst_sucursales_usuario';
$route['petshop/pet_cargar_mascota/(:any)']['get']     = 'petshop/mascota/sel_mascota_id/$1';
$route['petshop/pet_cargar_historia/(:any)']['get']    = 'petshop/mascota/lst_historia_mascota/$1';
$route['petshop/pet_guardar_mascota_historiaclinica']['post'] = 'petshop/mascota/historiaclinica_guardar';
$route['petshop/pet_eliminar_mascota_historiaclinica']['post'] = 'petshop/mascota/historiaclinica_eliminar';

// rutas para precios
$route['precio_compraventa_lst']['get']         = 'precio/lst_porcientoprecioventa';
$route['precio_compraventa_actualizar']['post'] = 'precio/upd_porcientoprecioventa';
