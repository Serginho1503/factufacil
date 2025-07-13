<?php
/*------------------------------------------------
  ARCHIVO: Venta.php
  DESCRIPCION: Contiene los métodos relacionados con la Venta.
  FECHA DE CREACIÓN: 15/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class Facturar extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("facturar_model");
        $this->load->Model("Gastos_model");
        $this->load->Model("Pedido_model");        
        $this->load->Model("Parametros_model");
        $this->load->Model("Comanda_model");
        $this->load->Model("Inventario_model");
        $this->load->Model("Cajaapertura_model");
        $this->load->Model("Cajacierre_model");
        $this->load->Model("Usuario_model");
        $this->load->Model("Mesa_model");
        $this->load->Model("Empresa_model");
        $this->load->Model("Cliente_model");
        $this->load->Model("Retencion_model");
        $this->load->Model("Sucursal_model");
        $this->load->Model("Clausula_model");
        $this->load->Model("Producto_model");
        $this->load->Model("Serviciotecnico_model");       
        $this->load->Model("cajaefectivo_model");
        $this->load->Model("contabilidad/Contab_comprobante_model");
        $this->load->Model("Categoria_model");
        $this->load->Model("Almacen_model");
                        
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        /* Listado de las areas junto con las mesas */
        $areamesa = $this->facturar_model->listado_mesas();
        $data["areamesa"] = $areamesa;
        $data["base_url"] = base_url();
        $data["content"] = "facturar";
        $this->load->view("layout", $data);
    }

    /* SESION TEMPORAL PARA CONOCER LA MESA A FACTURAR */
     public function tmp_mesa_factura() {
        $this->session->unset_userdata("tmp_idmesa_factura"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_idmesa_factura", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_idmesa_factura", $id); } 
        else { $this->session->set_userdata("tmp_idmesa_factura", NULL); }
        $id_mesa = $this->session->userdata("tmp_idmesa_factura");
        $arr['resu'] = $id_mesa;
        print json_encode($arr);
    }

     public function nrofactura_tmp() {
        $this->session->unset_userdata("idfactura_tmp"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("idfactura_tmp", NULL);
        if ($id != NULL) { $this->session->set_userdata("idfactura_tmp", $id); } 
        else { $this->session->set_userdata("idfactura_tmp", NULL); }
        $id_fac = $this->session->userdata("idfactura_tmp");
        $arr['resu'] = $id_fac;
        print json_encode($arr);
    }

     public function tmp_forpago() {
        $this->session->unset_userdata("tmp_forpago"); 
        $id = $this->input->post("formapago");
        $idventa = $this->input->post("idventa");
        $this->session->set_userdata("tmp_forpago", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_forpago", $id); } 
        else { $this->session->set_userdata("tmp_forpago", NULL); }
        $idfp = $this->session->userdata("tmp_forpago");
        /*$arr['resu'] = $idfp;*/
        if($id == 'Contado') {$fpvtc = 1;} else {$fpvtc = 2;}
        $selfp = $this->facturar_model->sumforpago($idventa, $fpvtc);
        $arr['monto'] = $selfp->monto;
        $arr['efectivo'] = $selfp->efectivo;
        print json_encode($arr);
    }

    /* REDIRECCIONA DESDE DISTRIBUIDOR A FACTURAR */
    public function pedido_factura() {
        $id_mesa = $this->session->userdata("tmp_idmesa_factura");
        $id_mesa = $this->input->post("id");

        if($id_mesa == NULL){
            redirect('pedido','refresh');
        }
        /* Cargar Cliente Segun ID de Mesa */
        $climesa = $this->facturar_model->cliente_mesa($id_mesa);
        if($climesa == NULL){
            redirect('pedido','refresh');
        }                

        /* Listado de las areas junto con las mesas */
        $id_venta = $this->facturar_model->cargarfacturapedido($id_mesa);
        /* Cargar Listado de Pedido Segun ID de Mesa */
        
        $arr['resu'] = $id_venta;
        print json_encode($arr);
    }

    /* REDIRECCIONA DESDE DISTRIBUIDOR A FACTURAR */
    public function pedido_facturacadillac() {
        $id_mesa = $this->session->userdata("tmp_idmesa_factura");
        if($id_mesa == NULL){
            redirect('pedido','refresh');
        }
        /* Cargar Cliente Segun ID de Mesa */
        $climesa = $this->facturar_model->cliente_mesa($id_mesa);
        if($climesa == NULL){
            redirect('pedido','refresh');
        }                
        /* Listado de las areas junto con las mesas */
        $areamesa = $this->facturar_model->listado_mesas();
        /* Cargar Listado de Pedido Segun ID de Mesa */
        $pedmesa = $this->facturar_model->pedido_mesa($id_mesa);
        /* Obtiene el Numero de Factura */
        $nrofact = $this->facturar_model->sel_nro_factura();
        /* Obtiene el Numero de Nota de Venta */
        $nronv = $this->facturar_model->sel_nro_nronot(); 
        $nrofactura = "001-001-".$nrofact;
        $data["areamesa"] = $areamesa;
        $data["climesa"] = $climesa;
        $data["pedmesa"] = $pedmesa;
        $data["nrofactura"] = $nrofactura;
        $data["nronv"] = $nronv;
        $data["base_url"] = base_url();
        $data["content"] = "facturar";
        $this->load->view("layout", $data);
    }

    /* ABRE LA VENTANA PARA SELECCIONAR EL TIPO DE PAGO Y EL MONTO */
    public function tipopago(){
      $formapago = $this->input->post("forpago");
      $montopendiente = $this->input->post("montopendiente");

      $pago_rapido = $this->input->post("pago_rapido");
      if ($pago_rapido != ''){
        $data["pago_rapido"] = $pago_rapido;
      }

      if($formapago == 'Contado'){
      //  print $forpgo;
      }else{
      //   print $forpgo;
      }      
      /* Cargar Listado de Formas de Pago */
      $existe_pagoefectivo = $this->facturar_model->existe_pagoefectivo($formapago);
      $forpago = $this->facturar_model->lista_formapago($existe_pagoefectivo);
      /* Cargar Listado de Bancos */
      $banco = $this->facturar_model->bancos();
      /* Cargar Listado de Tarjetas */
      $tarjeta = $this->facturar_model->tarjetas();
      $data["maxvalor"] = round($montopendiente,2);
      $data["formapago"] = $formapago;
      $data["forpago"] = $forpago;
      $data["bancos"] = $banco;
      $data["tarjetas"] = $tarjeta;
      $data["base_url"] = base_url();
      $this->load->view("facturar_tipopago", $data);
    }

    public function editipopago(){
      $idreg = $this->input->post("idreg");
      $idfp = $this->input->post("idfp");
      $idventa = $this->input->post("idventa");
      $edifp = $this->facturar_model->ediforpagovent($idreg, $idfp, $idventa);  
      $idforpago = $edifp->id_formapago;
      $tipofp = $this->facturar_model->selforpago($idforpago); 
      $formapago = $this->input->post("forpago");
      if($formapago == 'Contado'){
      //  print $forpgo;
      }else{
      //   print $forpgo;
      }      

      $maxvalor = $this->input->post("montopendiente");
      $data["maxvalor"] = $maxvalor /*+ $edifp->monto*/;

      if ($idforpago == 1){
        $existe_pagoefectivo = 0;  
      } else {
        $existe_pagoefectivo = $this->facturar_model->existe_pagoefectivo($formapago);
      }  
      
      $forpago = $this->facturar_model->lista_formapago($existe_pagoefectivo);
      $banco = $this->facturar_model->bancos();
      $tarjeta = $this->facturar_model->tarjetas();
      $data["edifp"] = $edifp;
      $data["tipofp"] = $tipofp;
      $data["formapago"] = $formapago;
      $data["forpago"] = $forpago;
      $data["bancos"] = $banco;
      $data["tarjetas"] = $tarjeta;
      $data["base_url"] = base_url();
      $this->load->view("facturar_tipopago", $data);
    }

    public function selfp(){
      $idforpago = $this->input->post("idfp");
      $arr = $this->facturar_model->selforpago($idforpago);
      print json_encode($arr);      
    }

    public function guardar_formapago(){
      $id_mesa = $this->session->userdata("tmp_idmesa_factura");
      $cli = $this->facturar_model->obtieneid_cliente($id_mesa);
      $id_cliente = $cli->id_cliente;
      $fecha = date("Y-m-d");



      $cod_tipopago = $this->input->post("cmb_forpago");
      $monto = $this->input->post("txt_monto");
      $banco = $this->input->post("cmb_banco");
      $tarjeta = $this->input->post("cmb_tarjeta");
      $trache = $this->input->post("txt_trache");
      $titular = $this->input->post("txt_titular");

      if($monto > 0){
        $graba = $this->facturar_model->add_formapago($id_mesa, $id_cliente, $cod_tipopago, $fecha, $monto, $banco, $tarjeta, $trache, $titular);
      }

      $arr['resu'] = $cod_tipopago;
      print json_encode($arr);

    }

    /* APLICAR DESCUENTO A PRODUCTOS DE VENTA */
    public function upd_descuento(){
      $id_mesa = $this->session->userdata("tmp_idmesa_factura");
      $desc = $this->input->post("descuento");
      $sql = $this->facturar_model->descuento($id_mesa, $desc);
      $arr['dat'] = 1;
      print json_encode($arr);

    }

    /* OBTIENE LOS DATOS DEL PRODUCTO Y LOS CARGA A LA TABLA */
    public function actualiza_tabla_factura(){
        $id_mesa = $this->session->userdata("tmp_idmesa_factura");
        /* Cargar Cliente Segun ID de Mesa */
        $climesa = $this->facturar_model->cliente_mesa($id_mesa);
        /* Cargar Listado de Pedido Segun ID de Mesa */
        $pedmesa = $this->facturar_model->pedido_mesa($id_mesa);
        $data["climesa"] = $climesa;
        $data["pedmesa"] = $pedmesa;
        $data["base_url"] = base_url();
        $this->load->view("factura_tabla", $data);            
    }

    /* OBTIENE LOS DATOS DEL PRODUCTO Y LOS CARGA A LA TABLA PARA MONTOS */
    public function actualiza_montos_factura(){
        $id_mesa = $this->session->userdata("tmp_idmesa_factura");
        /* Cargar Cliente Segun ID de Mesa */
        $climesa = $this->facturar_model->cliente_mesa($id_mesa);
        /* Cargar Listado de Pedido Segun ID de Mesa */
        $pedmesa = $this->facturar_model->pedido_mesa($id_mesa);
        $data["climesa"] = $climesa;
        $data["pedmesa"] = $pedmesa;
        $data["base_url"] = base_url();
        $this->load->view("factura_monto", $data);            
    }

    /* FACTURAR */
    public function pagar(){
      $dif = 0;
      $idusu = $this->session->userdata("sess_id");
      $fec = $this->input->post("fecha");
      $fecha = str_replace('/', '-', $fec); 
      $fecha = date("Y-m-d", strtotime($fecha));
      $idmesa = $this->input->post("idmesa");
      $varmesa = $this->input->post("mesa");
      $mesero = $this->input->post("mesero");
      $fp = $this->input->post("cmb_forma");
      $nro_notaventa = $this->input->post("nro_notaventa");
      $nro_factura = $this->input->post("nro_factura");

      $nro_ident = $this->input->post("nro_ident");
      $nom_cliente = $this->input->post("nom_cliente");
      $cor_cliente = $this->input->post("cor_cliente");
      $telf_cliente = $this->input->post("telf_cliente");
      $dir_cliente = $this->input->post("dir_cliente");

      $subconiva = $this->input->post("subconiva");
      $subsiniva = $this->input->post("subsiniva");
      $desc_monto = $this->input->post("desc_monto");
      $descsubconiva = $this->input->post("descsubconiva");
      $descsubsiniva = $this->input->post("descsubsiniva");
      $montoiva = $this->input->post("montoiva");
      $montototal = $this->input->post("montototal");

      $efectivo = $this->input->post("efectivo");
      $tarjeta = $this->input->post("tarjeta");
      $cambio = $this->input->post("cambio");


      $clipago = $this->facturar_model->data_cliente($nro_ident, $nom_cliente, $cor_cliente, $telf_cliente, $dir_cliente);
      $tipo_ident = $clipago->tipo_ident_cliente;
      $nro_ident = $clipago->ident_cliente;
      $nom_cliente = $clipago->nom_cliente;
      $telf_cliente = $clipago->telefonos_cliente; 
      $dir_cliente = $clipago->direccion_cliente;
      $cor_cliente = $clipago->correo_cliente;

    //  if($tipo_ident != NULL){}else{$tipo_ident = "";}
    //  if($nro_ident != NULL){}else{$nro_ident = "";}
    //  if($nom_cliente != NULL){}else{$nom_cliente = "";}
      if($telf_cliente != NULL){}else{$telf_cliente = "";}
      if($dir_cliente != NULL){}else{$dir_cliente = "";}

      /* Se le resta al efectivo la diferencia del vuelto */
      $dif = $efectivo - $cambio;
      if($dif < 0){
        $efectivo = 0;
        $tarjeta = $tarjeta - $dif;
      }else{
        $efectivo = $dif;
      }

    //  list($area,$mesa)=explode("-",$varmesa);

      $area = $varmesa;
      $mesa = $varmesa;

      $id_venta = $this->facturar_model->pagar_factura($fecha, $area, $idmesa, $mesa, $mesero, $nro_factura, $tipo_ident, $nro_ident, $nom_cliente, $cor_cliente, $telf_cliente, $dir_cliente, $subconiva, $subsiniva, $desc_monto, $descsubconiva, $descsubsiniva, $montoiva, $montototal, $idusu, $fp, $nro_notaventa, $efectivo, $tarjeta);

      /* Aqui se actualiza el inventario */
      $updinv = $this->facturar_model->inventario($id_venta);

      if ($id_venta != 0){
          $varventa = $this->facturar_model->lst_detventaparakardex($id_venta);    
          foreach ($varventa as $obj) {
              $this->Inventario_model->ins_kardexegreso($obj->id_producto, $obj->nro_factura, 'FACTURA DE VENTA', 
                                                        $obj->cantidad, $obj->precio, $obj->descsubtotal, 
                                                        $obj->pro_idunidadmedida);
          }    
      }

      $arr['dat'] = $id_venta;
      print json_encode($arr);
    }

    /* VENTAS */
    public function ventas(){
      date_default_timezone_set("America/Guayaquil");

      $desde = $this->session->userdata("tmp_ven_desde");
      $hasta = $this->session->userdata("tmp_ven_hasta");
      $vendedor = $this->session->userdata("tmp_ven_vendedor");
      $sucursal = $this->session->userdata("tmp_ven_sucursal");
      $tipofecha = $this->session->userdata("tmp_ven_tipofecha");

      $cliente = $this->session->userdata("tmp_vencli_cliente");             
      $this->session->set_userdata("tmp_venpro_sucursal", 0);  
      $this->session->set_userdata("tmp_venpro_producto", 0);  
      $this->session->set_userdata("tmp_venpro_categoria", 0);  
      $this->session->set_userdata("tmp_venpro_todos", false);  

      if (($desde == NULL) || ($hasta == NULL)){
        $fdesde = date("Y-m-d"); 
        $fhasta = date("Y-m-d"); 
        $varhd = '00:00:00';
        $varhh = '23:59:59';
        $desde = $fdesde." ".$varhd;
        $hasta = $fhasta." ".$varhh;
        $this->session->set_userdata("tmp_ven_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_ven_desde", $desde); } 
        else { $this->session->set_userdata("tmp_ven_desde", NULL); }
        $this->session->set_userdata("tmp_ven_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_ven_hasta", $hasta); } 
        else { $this->session->set_userdata("tmp_ven_hasta", NULL); }
        $this->session->set_userdata("tmp_ven_vendedor", NULL);
        if ($vendedor != NULL) { $this->session->set_userdata("tmp_ven_vendedor", $vendedor); } 
        else { $this->session->set_userdata("tmp_ven_vendedor", 0); }
        $this->session->set_userdata("tmp_ven_sucursal", NULL);
        if ($sucursal != NULL) { $this->session->set_userdata("tmp_ven_sucursal", $sucursal); } 
        else { $this->session->set_userdata("tmp_ven_sucursal", 0); }
        $this->session->set_userdata("tmp_ven_tipofecha", NULL);
        if ($tipofecha != NULL) { $this->session->set_userdata("tmp_ven_tipofecha", $tipofecha); } 
        else { $this->session->set_userdata("tmp_ven_tipofecha", 1); }

        $this->session->set_userdata("tmp_vencli_cliente", NULL);
        if ($cliente != NULL) { $this->session->set_userdata("tmp_vencli_cliente", $cliente); } 
        else { $this->session->set_userdata("tmp_vencli_cliente", 0); }

        $this->session->set_userdata("tmp_vencli_desde", $fdesde);
        $this->session->set_userdata("tmp_vencli_hasta", $fhasta);

        $this->session->set_userdata("tmp_venpro_desde", $fdesde);
        $this->session->set_userdata("tmp_venpro_hasta", $fhasta);

        $this->session->set_userdata("tmp_venven_desde", $fdesde);
        $this->session->set_userdata("tmp_venven_hasta", $fhasta);

      }  

      $vendedores = $this->facturar_model->lst_vendedor();

      $sucursales = $this->Sucursal_model->lst_sucursales();

      $monto = $this->facturar_model->ventas_total_rango($desde, $hasta);
      $facturapdf = $this->Parametros_model->sel_facturapdf();

      $categorias = $this->Categoria_model->sel_cat();

      $categcliente = $this->Cliente_model->lst_categoria_venta();

      $data["desde"] = $desde;
      $data["hasta"] = $hasta;
      $data["sucursal"] = $sucursal;
      $data["tipofecha"] = $tipofecha;
      $data["cliente"] = $cliente;
      $data["vendedores"] = $vendedores;
      $data["sucursales"] = $sucursales;
      $data["categorias"] = $categorias;
      $data["categcliente"] = $categcliente;
      $data["monto"] = $monto;
      $data["facturapdf"] = $facturapdf;
      $data["base_url"] = base_url();
      $data["content"] = "venta_listar";
      $this->load->view("layout", $data);
    }

    public function ventasOK(){
      date_default_timezone_set("America/Guayaquil");
      $fdesde = date("Y-m-d"); 
      $fhasta = date("Y-m-d"); 
      $varhd = '00:00:00';
      $varhh = '23:59:59';
      $desde = $fdesde." ".$varhd;
      $hasta = $fhasta." ".$varhh;
      $this->session->set_userdata("tmp_ven_desde", NULL);
      if ($desde != NULL) { $this->session->set_userdata("tmp_ven_desde", $desde); } 
      else { $this->session->set_userdata("tmp_ven_desde", NULL); }
      $this->session->set_userdata("tmp_ven_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmp_ven_hasta", $hasta); } 
      else { $this->session->set_userdata("tmp_ven_hasta", NULL); }
      $desde = $this->session->userdata("tmp_ven_desde");
      $hasta = $this->session->userdata("tmp_ven_hasta");
      $monto = $this->facturar_model->ventas_total_rango($desde, $hasta);
      $facturapdf = $this->Parametros_model->sel_facturapdf();
      $data["monto"] = $monto;
      $data["facturapdf"] = $facturapdf;
      $data["base_url"] = base_url();
      $data["content"] = "venta_listar";
      $this->load->view("layout", $data);
    }

    /* CARGA DE DATO AL DATATABLE 
    public function lstVenta() {
      $registro = $this->facturar_model->venta_lst();
      $tabla = "";
      foreach ($registro as $row) {
          @$fec = str_replace('-', '/', $row->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); 
          
          $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Imprimir Venta\" id=\"'.$row->id_venta.'\" class=\"btn bg-navy color-palette btn-xs btn-grad venta_print\"><i class=\"fa fa-print\"></i></a> </div>';
          $tabla.='{"fecha":"' . $fec . '",
                    "nro_factura":"' . $row->nro_factura . '",
                    "area_mesa":"' . $row->area_mesa . '",
                    "nom_cliente":"' . $row->nom_cliente . '",
                    "montototal":"' . $row->montototal . '",   
                    "ver":"'.$ver.'"},';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';

    }*/

    public function tmp_reporte(){
      /* fecha desde */
      $fecdesde = $this->input->post("desde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      /* fecha hasta */
      $fechasta = $this->input->post("hasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));

      $venta = $this->facturar_model->venta_rpt($desde, $hasta);

      $this->session->unset_userdata("tmp_rpt_venta"); 
      $this->session->set_userdata("tmp_rpt_venta", NULL);
      if ($venta != NULL) { $this->session->set_userdata("tmp_rpt_venta", $venta); } 
      else { $this->session->set_userdata("tmp_rpt_venta", NULL); }
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function reporte(){

        $desde = $this->session->userdata("tmp_ven_desde");
        $hasta = $this->session->userdata("tmp_ven_hasta");
        $vendedor = $this->session->userdata("tmp_ven_vendedor");
        $sucursal = $this->session->userdata("tmp_ven_sucursal");
        $tipofecha = $this->session->userdata("tmp_ven_tipofecha");

        $venta = $this->facturar_model->venta_rango($desde, $hasta, $vendedor, $sucursal, $tipofecha);
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $data["objsuc"] = $objsuc;
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
          $data["objemp"] = $objemp;
        }


        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);

        $data["base_url"] = base_url();
        $data["desde"] = $desde;
        $data["hasta"] = $hasta;
        $data["venta"] = $venta;
        $data["objsuc"] = $objsuc;
        $this->load->view("venta_reporte", $data);   
      
    }

        
    public function reportetarjeta(){
      $desde = $this->session->userdata("tmp_ven_desde");
      $hasta = $this->session->userdata("tmp_ven_hasta");
      $vendedor = $this->session->userdata("tmp_ven_vendedor");
      $sucursal = $this->session->userdata("tmp_ven_sucursal");
      $venta = $this->facturar_model->rptventatarjeta($desde, $hasta, $vendedor, $sucursal);
      $data["base_url"] = base_url();
      $data["desde"] = $desde;
      $data["hasta"] = $hasta;
      $data["venta"] = $venta;
      $this->load->view("venta_reporte_tarjeta", $data);   
    }

    public function reportedetalle(){

        $desde = $this->session->userdata("tmp_ven_desde");
        $hasta = $this->session->userdata("tmp_ven_hasta");
        $vendedor = $this->session->userdata("tmp_ven_vendedor");
        $sucursal = $this->session->userdata("tmp_ven_sucursal");

        $venta = $this->facturar_model->venta_detalles_rango($desde, $hasta, $vendedor, $sucursal);
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $data["objsuc"] = $objsuc;
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
          $data["objemp"] = $objemp;
        }

        $data["base_url"] = base_url();
        $data["desde"] = $desde;
        $data["hasta"] = $hasta;
        $data["venta"] = $venta;
        $this->load->view("ventadetalle_reporte", $data);   
      
    }

    public function imprimir() {

        $strprint = "";
        for ($i=0; $i<6; $i++) {
            $strprint.= "  " . "\n";                        
        }        
        $strprint.= "  ";

        $strprint.= $this->input->post('txt_imprimir');

        $printer="";
/*         $idusu = $this->session->userdata("sess_id");
        $objusuario = $this->Usuario_model->usu_get($idusu);
        $id_punto = $objusuario->id_punto;
       $objmesa = $this->Mesa_model->sel_mesa_id($id_punto);

        if ($objmesa != null){  
            $objcom = $this->Comanda_model->sel_com_id($objmesa->id_comanda);*/
        $objprinter = $this->Parametros_model->impresorafactura_get();
        if ($objprinter != null){
            $objcom = $this->Comanda_model->sel_com_id($objprinter->valor);

            $printer= $objcom->impresora;
            try {  
              $enlace=printer_open($printer);

              printer_write($enlace, $strprint);

              printer_close($enlace);
              
              $this->session->unset_userdata("tmp_idmesa_factura");
              $arr['resu'] = 1;
              print json_encode($arr);
            }
            catch(Exception $e) {
              $arr['resu'] = 'Mensaje: ' .$e->getMessage();
              print json_encode($arr);
            }
        }
    }

    public function imprimirticket() {
        $strprint = $this->input->post('txt_imprimir');
        $idfactura = $this->input->post('idfactura');

        $impresiongrafica = $this->Parametros_model->sel_impresiongrafica();
        if ($impresiongrafica == 1){
          $this->imprimirticket_grafico($strprint, $idfactura);
        }
        else{         
          $this->imprimirticket_basico($strprint, $idfactura);
        }
    }    

    public function imprimirticket_basico($strprint, $idfactura) {

        $printer="";

        $objfac = $this->facturar_model->datosfactura($idfactura);
        $idusu = $objfac[0]->idusu;
        $objusuario = $this->Usuario_model->usu_get($idusu);
        $id_punto = $objusuario->id_punto;
        if (($id_punto != null) && ($id_punto != 0)){
          $objmesa = $this->Mesa_model->sel_mesa_id($id_punto); 
          if ($objmesa != null){  
              //var_dump("impresora de usuario");
              $objcom = $this->Comanda_model->sel_com_id($objmesa->id_comanda);   
          }    
        }
        if ($objcom == null){
          $objprinter = $this->Parametros_model->impresorafactura_get();
          if ($objprinter != null){
              $objcom = $this->Comanda_model->sel_com_id($objprinter->valor);
          }
        }    

        if ($objcom != null){
            $printer= $objcom->impresora;
           
            try {  
              $enlace=printer_open($printer);

              printer_set_option($enlace, PRINTER_MODE, "RAW");

              printer_write($enlace, $strprint);

              printer_close($enlace);
              
              $this->session->unset_userdata("tmp_idmesa_factura");
              $arr['resu'] = 1;
              print json_encode($arr);
            }
            catch(Exception $e) {
              $arr['resu'] = 'Mensaje: ' .$e->getMessage();
              print json_encode($arr);
            }
        }
    }

    public function imprimirticket_grafico($strprint, $idfactura) {
        //$strprint = $this->input->post('txt_imprimir');
        $printer="";

        $objfac = $this->facturar_model->datosfactura($idfactura);
        $idusu = $objfac[0]->idusu;
        $objusuario = $this->Usuario_model->usu_get($idusu);
        $id_punto = $objusuario->id_punto;
        if (($id_punto != null) && ($id_punto != 0)){
          $objmesa = $this->Mesa_model->sel_mesa_id($id_punto); 
          if ($objmesa != null){  
              //var_dump("impresora de usuario");
              $objcom = $this->Comanda_model->sel_com_id($objmesa->id_comanda);   
          }    
        }
        if ($objcom == null){
          $objprinter = $this->Parametros_model->impresorafactura_get();
          if ($objprinter != null){
              $objcom = $this->Comanda_model->sel_com_id($objprinter->valor);
          }
        }    

        if ($objcom != null){
            $printer= $objcom->impresora;
            try {  
              $emp = $this->Empresa_model->emp_get();

              $handle = printer_open($printer);
              printer_start_doc($handle, "My Document");
              printer_start_page($handle);
              
              $file = FCPATH."public\img\empresa\\".$emp->logo_path;
              if (file_exists($file)){
                printer_draw_bmp($handle, $file, 150, 1, 200, 200);  
                //printer_draw_bmp($handle, "\public\img\micholi.bmp", 150, 1, 200, 200);  
              }
              
              $p = 230;
              $font = printer_create_font("Arial", 40, 20, PRINTER_FW_ULTRABOLD, false, false, false, 0);
              printer_select_font($handle, $font);    

              printer_draw_text($handle, $emp->nom_emp. "\r\n", 30, $p);
              $font = printer_create_font("Arial", 30, 12, PRINTER_FW_NORMAL, false, false, false, 0);
              printer_select_font($handle, $font);
              $p+= 50;


              $lines = explode(PHP_EOL, $strprint);

              foreach ($lines as $ln => $value) {
                  printer_draw_text($handle, $value. "\r\n", 1, $p);
                  $p+= 26;
              }

              $pen = printer_create_pen(PRINTER_PEN_SOLID, 1, "000000");
              printer_select_pen($handle, $pen);
              printer_draw_line($handle, 1, $p, 500, $p);
              printer_delete_pen($pen);            
              printer_delete_font($font);
              printer_end_page($handle);
              printer_end_doc($handle);
              printer_close($handle);

              $this->session->unset_userdata("tmp_idmesa_factura");
              $arr['resu'] = 1;
              print json_encode($arr);
            }
            catch(Exception $e) {
              $arr['resu'] = 'Mensaje: ' .$e->getMessage();
              print json_encode($arr);
            }
        }
    }


    public function imprimirventaticket(){
        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();   
        $habilitanotaventaiva = $this->Parametros_model->sel_habilitanotaventaiva(); 

        $factoriva = 15;
        date_default_timezone_set("America/Guayaquil");
        $idfactura = $this->input->post('id');
        $registro = $this->facturar_model->datosfactura($idfactura);
        $regfp = $this->facturar_model->idventa_formapago($idfactura); 
        $resfact = $this->facturar_model->monto_factura($idfactura);       
        $emp = $this->Empresa_model->emp_get();
        $tabla = chr(32). chr(32) . "\r\n";
        //$tabla.= chr(32). chr(32) . "\r\n";
        $montoimpuestoadicional = 0;
        $cambio = 0;
        $tipopago = 1;
        foreach ($registro as $row) {
            $tipopago = $row->id_tipcancelacion;
            $tipodoc = $row->tipo_doc;
            if ($row->tipo_doc == 2){
              $ff = "FACTURA DE VENTA";
            } else  {
              $ff = "NOTA DE VENTA";
            }  
            //$tabla.=$emp->nom_emp . "\r\n";//
            $nombre_empresa = $emp->nom_emp;
            $nombre_empresa_centrado = '<div style="text-align: center;"><b><span style="font-size: 16px;">' . $nombre_empresa . '</span></b></div>';
            $tabla .= $nombre_empresa_centrado . "\r\n";
            $tabla.=$emp->raz_soc_emp . "\r\n";
            $tabla.=" " . "\r\n";
            $tabla.="RUC:" . chr(32) . $emp->ruc_emp . "\r\n";
            $tabla.=$emp->dir_emp . "\r\n";
            $tabla.="TEL:" . $emp->tlf_emp . "\r\n";
            if (trim($row->nro_factura) != ''){
            $tabla.="Nro. Docum.:" . chr(32) . $row->nro_factura . "\r\n";  
            }
            /*if (trim($row->claveacceso) != ''){
              $tabla.="Clave Acceso SRI:" . "\r\n";  
              $tabla.= $row->claveacceso . "\r\n";  
              $tabla.="\r\n";
            }*/
            /*$nro_orden = $row->nro_orden;
            if (!$nro_orden) { $nro_orden = ''; }
            if (trim($nro_orden) != ''){
              $tabla.="Nro. Orden.:" . chr(32) . $nro_orden . "\r\n";  
            }*/              
          //  $tabla .=$ff;
            /*if ($row->estatus == 3) $tabla.= " - ANULADA";
            $tabla.="\r\n";*/
            $strdate = str_replace('-', '/', $row->fecharegistro); 
            $strdate = date("d/m/Y H:i", strtotime($strdate)); 
            $tabla.="Fecha:" . chr(32) . $strdate . "\r\n";
            /*
            if (trim($row->mesa) != ''){
              $tabla.="Punto:" . chr(32) . $row->mesa . "\r\n";  
            }
            */
            /*if (trim($row->mesero) != ''){*/
            $tabla.="Vendedor:" . chr(32) . $row->vendedor . "\r\n";
            /*}*/
            $tabla.="Cliente:" . chr(32) . $row->nom_cliente . "\r\n";        
            $tabla.="Direccion:". chr(32) . $row->dir_cliente . "\r\n";        
            $tabla.="CI/RUC:". chr(32) . $row->nro_ident . "\r\n";        
            $tabla.="Telef.:". chr(32) . $row->telf_cliente . "\r\n";  
            if (trim($row->placa_matricula) != ''){
              $tabla.="Placa:". chr(32) . $row->placa_matricula . "\r\n";              
            }

            if (trim($row->observaciones) != ''){
              $tabla.="\r\n";
              $tabla.="Observaciones:". chr(32) . $row->observaciones . "\r\n";              
            }

            if ($row->montoimpuestoadicional != NULL) $montoimpuestoadicional = $row->montoimpuestoadicional;      
            $cambio = $row->cambio;
        }    
        $tabla.= "\r\n";

        $tam_prod = 36;
        $tam_cant = 5;
        $tam_precio = 8;
        $tam_subtot = 7;

        /*  CONFIGURACION ITI      
        $tam_prod = 20;
        $tam_cant = 4;
        $tam_precio = 6;
        $tam_subtot = 6;
        */
/*
        $tabla.="Producto";   $lcant = 8;
        while ($lcant < $tam_prod){
            $tabla.= chr(32);
            $lcant++;
        }
        
        $lcant = 4;
        while ($lcant < ($tam_cant + 1)){
            $tabla.= chr(32);
            $lcant++;
        }
        $tabla.="Cant";
        
        $lcant = 6;
        while ($lcant < ($tam_precio + 1)){
            $tabla.= chr(32);
            $lcant++;
        }
        $tabla.="P.Unit";
        
        $lcant = 5;
        while ($lcant < ($tam_subtot + 1)){
            $tabla.= chr(32);
            $lcant++;
        }
        $tabla.="P.Tot"; 
        $tabla.= "\r\n";  */      

        $tabla.="Producto     Cant.    P.Unit   P.Tot". "\r\n"; 
        $tabla.="------------------------------------". "\r\n"; 
        $registro = $this->facturar_model->ventadetalle($idfactura);
        $subtotaliva=0;
        $subtotalcero=0;
        $subtotaldiva=0;
        $subtotaldcero=0;
        $montoiva=0;
        $descuento=0;
        $subsidio=0;
        foreach ($registro as $row) {
            $subsidio += $row->subsidio;

            $strnombre = substr($row->pro_nombre,0,$tam_prod);
            $tabla.= $strnombre;
            $lcant = strlen($strnombre);
            while ($lcant < ($tam_prod + 1)){
                $tabla.= chr(32);
                $lcant++;
            }

            $tabla.= "\r\n";
            $tabla.= "            ";
            $strcant = number_format($row->cantidad,$decimalescantidad);
            $lcant = strlen($strcant);
            while ($lcant < $tam_cant){
                $tabla.= chr(32);
                $lcant++;
            }
            $tabla.= $strcant;
            $tabla.= "  ";
            if ($row->pro_grabaiva == 1){
                $subtotaliva+= $row->subtotal;
                $montoiva+= $row->montoiva;    
            }
            else{
                $subtotalcero+= $row->subtotal;
            }
            $strprecio=number_format($row->precio,4);
            $lcant = strlen($strprecio);
            while ($lcant < ($tam_precio + 1)){
                $tabla.= chr(32);
                $lcant++;
            }
            $tabla.= $strprecio;

            $strprecio=number_format($row->subtotal,2);
            $lcant = strlen($strprecio);
            while ($lcant < ($tam_subtot + 1)){
                $tabla.= chr(32);
                $lcant++;
            }
            $tabla.= $strprecio;
            $tabla.= "\r\n";

           /* $tmpnombre = substr($row->pro_nombre,$tam_prod);
            while (trim($tmpnombre) != ''){
              $strnombre = substr($tmpnombre,0,$tam_prod);
              $tabla.= $strnombre;
              $tabla.= "\r\n";
              $tmpnombre = substr($tmpnombre,$tam_prod);
            } */ 

        }

        $cantidadproducto=5;
        $limiteprodventa = $this->Parametros_model->sel_limiteprodventa();
        $venta = $this->facturar_model->ventadetalle($idfactura);

        if (($tipodoc == 2) || ($habilitanotaventaiva == 1)){
          $totalpagar = $subtotaliva + $subtotalcero + $montoiva;

          $tabla .= "\r\n";
          $tabla .= "\r\n";
          $tabla .= "SUBTOTAL IVA 15:" . str_pad(number_format($resfact->subtotaliva, 2), 20, ' ', STR_PAD_LEFT) . "\r\n";
          $tabla .= "SUBTOTAL   IVA0:" . str_pad(number_format($resfact->subtotalcero, 2), 20, ' ', STR_PAD_LEFT) . "\r\n";
          $tabla .= "DESCUENTO      :" . str_pad(number_format($resfact->descmonto, 2), 20, ' ', STR_PAD_LEFT) . "\r\n";
          $tabla .= "MONTO IVA 15   :" . str_pad(number_format($resfact->montoiva, 2), 20, ' ', STR_PAD_LEFT) . "\r\n";

        }else{
          $totalpagar = $subtotaliva + $subtotalcero;
          $tabla.= "\r\n";
          $tabla.= "\r\n";
          $tabla .= "SUBTOTAL       :" . str_pad(number_format($resfact->subtotaliva + $resfact->subtotalcero, 2), 20, ' ', STR_PAD_LEFT) . "\r\n";
          $tabla .= "DESCUENTO      :" . str_pad(number_format($resfact->descmonto, 2), 20, ' ', STR_PAD_LEFT) . "\r\n";

        }
        $impuesto = $this->Parametros_model->sel_impuestoadicional();
        $impuestoespecial = $impuesto->valor;
        $descripcionimpuestoespecial = $impuesto->descripcion;

        /*$habilitaimpuestoespecial = true;*/
        if ($impuesto->valor > 0){
        /*  $tabla.= "   TOTAL PARCIAL  :" . chr(32) . chr(32) . number_format($totalpagar,2) . "\r\n";*/
          $tabla.= "   " . $descripcionimpuestoespecial . "   :" . chr(32) . chr(32) . number_format($montoimpuestoadicional,2) . "\r\n";
          $totalpagar+= $montoimpuestoadicional;
        }  


          $tabla .= "TOTAL FACTURA  :" . str_pad(number_format($resfact->monto, 2), 20, ' ', STR_PAD_LEFT) . "\r\n";

        /*$impresionsubsidio = $this->Parametros_model->sel_impresionsubsidio();
        if ($impresionsubsidio == 1){
          $tabla.= "  " . "\n";
          $tabla.= " TOTAL SIN SUBSIDIO:" . chr(32) . chr(32) . number_format($resfact->monto + $subsidio,2) . "\r\n";
          $tabla.= "AHORRO POR SUBSIDIO:" . chr(32) . chr(32) . number_format($subsidio,2) . "\r\n";
        }*/
        
        for ($i=0; $i<1; $i++) {
            $tabla.= "  " . "\n";                        
        }        

        $tipocancelacion = ($tipopago == 1) ? 'Contado' : 'Crédito';
        $tabla.= "Tipo Cancelación: $tipocancelacion" . "\r\n";       
        $tabla.= " " . "\r\n";

        /*if (count($regfp) > 0){
          $tabla.= "Forma de Pago" . "\r\n";
          foreach ($regfp as $fp) {
            $fpmonto = $fp->monto;
            if ($fp->id_formapago == 1) { $fpmonto+= $cambio; }
            $tabla.= $fp->nombre_formapago.":". chr(32) . chr(32) . number_format($fpmonto,2) . "\r\n";
          }
          $tabla.= " " . "\r\n";
        }*/  

        /*if($tipopago == 1){
          $tabla.= "Cambio:" . chr(32) . $cambio . "\r\n";
          $tabla.= " " . "\r\n";
        }*/


        $tabla.= "---------------------------------" . "\r\n";
        $tabla.= "     GRACIAS POR PREFERIRNOS     " . "\r\n";

        for ($i=0; $i<2; $i++) {
            $tabla.= "  " . "\n";                        
        }        
        for ($i=0; $i<5; $i++) {
            $tabla.= "  " . "\n";                        
        }        
        $data["idfactura"] = $idfactura; 
        $data["strcomanda"] = $tabla; 
        $impresionlocal = $this->Parametros_model->sel_impresionlocal();
        $data["impresionlocal"] = $impresionlocal; 
        
        $data["base_url"] = base_url();
        $this->load->view("factura_imprimirticket_web", $data);

    }


    /* ABRIR VENTANA PARA Imprimir Venta ticket */
/*    
    public function imprimirventaticket(){
        $factoriva = 15;
        date_default_timezone_set("America/Guayaquil");
        $idfactura = $this->input->post('id');
        $registro = $this->facturar_model->datosfactura($idfactura);
        $regfp = $this->facturar_model->idventa_formapago($idfactura);        
        $emp = $this->Empresa_model->emp_get();
        $tabla = chr(32). chr(32) . "\r\n";
        $tabla.= chr(32). chr(32) . "\r\n";
        $montoimpuestoadicional = 0;
        $cambio = 0;
        foreach ($registro as $row) {
            if ($row->tipo_doc == 2){
              $ff = "FACTURA DE VENTA";
            } else  {
              $ff = "NOTA DE VENTA";
            }
            $tabla .=$ff;
            if ($row->estatus == 3) $tabla.= " - ANULADA";
            $tabla.="\r\n";
            $tabla.=$emp->nom_emp . "\r\n";
            $tabla.="RUC:" . chr(32) . $emp->ruc_emp . "\r\n";
            $tabla.=$emp->dir_emp . "\r\n";
            if (trim($row->nro_factura) != ''){
              $tabla.="Nro. Docum.:" . chr(32) . $row->nro_factura . "\r\n";  
            }
            $nro_orden = $row->nro_orden;
            if (!$nro_orden) { $nro_orden = ''; }
            if (trim($nro_orden) != ''){
              $tabla.="Nro. Orden.:" . chr(32) . $nro_orden . "\r\n";  
            }
            $strdate = str_replace('-', '/', $row->fecharegistro); 
            $strdate = date("d/m/Y H:i", strtotime($strdate)); 
            $tabla.="Fecha:" . chr(32) . $strdate . "\r\n";
            if (trim($row->mesa) != ''){
              $tabla.="Punto:" . chr(32) . $row->mesa . "\r\n";  
            }
            if (trim($row->mesero) != ''){
              $tabla.="Vendedor:" . chr(32) . $row->mesero . "\r\n";  
            }
            $tabla.="Cliente:" . chr(32) . $row->nom_cliente . "\r\n";        
            $tabla.="Direccion:". chr(32) . $row->dir_cliente . "\r\n";        
            $tabla.="CI/RUC:". chr(32) . $row->nro_ident . "\r\n";        
            $tabla.="Telef.:". chr(32) . $row->telf_cliente . "\r\n";  

            if ($row->montoimpuestoadicional != NULL) $montoimpuestoadicional = $row->montoimpuestoadicional;      
            $cambio = $row->cambio;
        }    
        $tabla.= "\r\n";
        $tabla.="PRODUCTO  CANT. P.Unit P.Tot". "\r\n";        
        $registro = $this->facturar_model->ventadetalle($idfactura);
        $subtotaliva=0;
        $subtotalcero=0;
        $subtotaldiva=0;
        $subtotaldcero=0;
        $montoiva=0;
        $descuento=0;
        foreach ($registro as $row) {
            $strnombre = substr($row->pro_nombre,0,9);
            $tabla.= $strnombre;
            $lcant = strlen($strnombre);
            while ($lcant < 10){
                $tabla.= chr(32);
                $lcant++;
            }
            $strcant = number_format($row->cantidad,2);
            $tabla.= $strcant;
            $lcant = strlen($strcant);
            while ($lcant < 6){
                $tabla.= chr(32);
                $lcant++;
            }
            if ($row->pro_grabaiva == 1){
                $subtotaliva+= $row->subtotal;
                $montoiva+= $row->montoiva;    
            }
            else{
                $subtotalcero+= $row->subtotal;
            }
            $strprecio=number_format($row->precio,2);
            $tabla.= $strprecio;
            $lcant = strlen($strprecio);
            while ($lcant < 7){
                $tabla.= chr(32);
                $lcant++;
            }
            $strprecio=number_format($row->subtotal,2);
            $tabla.= $strprecio;
            $tabla.= "\r\n";
        }
        $totalpagar = $subtotaliva + $subtotalcero + $montoiva;
        $tabla.= "\r\n";
        $tabla.= "\r\n";
        $tabla.= "   SUBTOTAL  IVA15:" . chr(32) . chr(32) . number_format($subtotaliva,2) . "\r\n";
        $tabla.= "   SUBTOTAL   IVA0:" . chr(32) . chr(32) . number_format($subtotalcero,2) . "\r\n";
        $tabla.= "   MONTO IVA15    :" . chr(32) . chr(32) . number_format($montoiva,2) . "\r\n";

        $impuesto = $this->Parametros_model->sel_impuestoadicional();
        $impuestoespecial = $impuesto->valor;
        $descripcionimpuestoespecial = $impuesto->descripcion;

        //$habilitaimpuestoespecial = true;
        if ($impuesto->valor > 0){
        //  $tabla.= "   TOTAL PARCIAL  :" . chr(32) . chr(32) . number_format($totalpagar,2) . "\r\n";
          $tabla.= "   " . $descripcionimpuestoespecial . " :" . chr(32) . chr(32) . number_format($montoimpuestoadicional,2) . "\r\n";
          $totalpagar+= $montoimpuestoadicional;
        }  


        $tabla.= "   TOTAL FACTURA  :" . chr(32) . chr(32) . number_format($totalpagar,2) . "\r\n";
        for ($i=0; $i<2; $i++) {
            $tabla.= "  " . "\n";                        
        }        

        $tabla.= "Forma de Pago" . "\r\n";
        foreach ($regfp as $fp) {
          $fpmonto = $fp->monto;
          if ($fp->id_formapago == 1) { $fpmonto+= $cambio; }
          $tabla.= $fp->nombre_formapago.":". chr(32) . chr(32) . number_format($fpmonto,2) . "\r\n";
        }
        $tabla.= " " . "\r\n";

        $tabla.= "Cambio:" . chr(32) . $cambio . "\r\n";
        $tabla.= " " . "\r\n";

        $tabla.= "----------------------" . "\r\n";
        $tabla.= "Gracias por su compra." . "\r\n";
        for ($i=0; $i<2; $i++) {
            $tabla.= "  " . "\n";                        
        }        
        for ($i=0; $i<5; $i++) {
            $tabla.= "  " . "\n";                        
        }        
        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("factura_imprimirticket", $data);

    }


*/








/*
    public function imprimirventa(){
        $factoriva = 15;
        date_default_timezone_set("America/Guayaquil");
        $idfactura = $this->input->post('id');
        $registro = $this->facturar_model->datosfactura($idfactura);
        $emp = $this->Empresa_model->emp_get();
        $tabla = "";
        $tabla.= " ". "\r\n";
        foreach ($registro as $row) {
          $strdate = str_replace('-', '/', $row->fecharegistro); 
          $strdate = date("d/m/Y H:m", strtotime($strdate)); 
          $tabla.=" Cliente:" . chr(32) . $row->nom_cliente . "\r\n";        
          $tabla.=" Direccion:". chr(32) . $row->dir_cliente . "\r\n";        
          $tabla.=" CI/RUC:". chr(32) . $row->nro_ident . "\r\n";        
          $tabla.=" Telef.:". chr(32) . $row->telf_cliente;
          
          for ($i=0; $i <= 46; $i++) { 
            $tabla.= chr(32);
          }

          $tabla.="Fecha:" . chr(32) . $strdate . "\r\n";
    
        }   
        
        $tabla.= "\r\n";
        $tabla.= "Cantidad";
          for ($i=0; $i <= 5; $i++) { 
            $tabla.= chr(32);
          }
        $tabla.= "Descripcion";
          for ($i=0; $i <= 35; $i++) { 
            $tabla.= chr(32);
          }
        $tabla.= "P. Unitario";
          for ($i=0; $i <= 10; $i++) { 
            $tabla.= chr(32);
          }
        $tabla.= "Subtotal". "\r\n";
 
        $cantidadproducto=0;
        $limiteprodventa = $this->Parametros_model->sel_limiteprodventa();
        $venta = $this->facturar_model->ventadetalle($idfactura);

        foreach ($venta as $item) {
            $cantidadproducto++;
            $lcant = strlen(number_format($item->cantidad,2));
            for ($i=1; $i <= 8-$lcant; $i++) { 
              $tabla.= chr(32);
            }
            $tabla.= number_format($item->cantidad,2);
            for ($i=0; $i <= 5; $i++) { 
              $tabla.= chr(32);
            }

            // PRODUCTO 
            $lpro = 0;
            $dife = 0;
            $lcant = 46;
            $producto = strtoupper($item->pro_nombre);
            $pro = substr($producto,0,41);
            $lpro = strlen($pro);
            $dife = $lcant - $lpro;
            $tabla.= $pro;
              for ($i=0; $i <= $dife; $i++) { 
                $tabla.= chr(32);
              } 

            // PRECIO 
            $lpre = strlen(number_format($item->precio,2));
            $dife = 0;
            if($lpre < 9){
              $dife = 9 - $lpre;
              for ($i=0; $i <= $dife; $i++) { 
                $tabla.= chr(32);
              }               
            }
            $tabla.= number_format($item->precio,2);
            for ($i=0; $i <= 7; $i++) { 
              $tabla.= chr(32);
            }             

            // SUBTOTAL 
            $lsub = strlen(number_format($item->subtotal,2));
            $difs = 0;
            if($lsub < 10){
              $difs = 10 - $lsub;
              for ($i=0; $i <= $difs; $i++) { 
                $tabla.= chr(32);
              }               
            }
            $tabla.= number_format($item->subtotal,2);
            $tabla.= " ". "\r\n";
        }
        if(($limiteprodventa > 0) & ($cantidadproducto < $limiteprodventa)){
          for ($i=1; $i <= ($limiteprodventa - $cantidadproducto); $i++) { $tabla.= " ". "\r\n"; }
        }
        
        

        $detfact = $this->facturar_model->resfactura($idfactura);
        $tipodoc = $detfact->tipo_doc;
        $subtotaliva = $detfact->subconiva;
        $subtotalcero = $detfact->subsiniva;
        $subtotaldiva = $detfact->descsubconiva;
        $subtotaldcero = $detfact->descsubsiniva;
        $montoiva = $detfact->montoiva;
        $descuento = $detfact->desc_monto;
        $total = $detfact->montototal;
        $tipodoc = $detfact->tipo_doc;
        $subtotal = $subtotalcero + $subtotaliva;        

        $tabla.= " ". "\r\n";
        if($tipodoc == 2){
          $ltop = strlen(number_format($subtotaliva,2));
          if (strlen(number_format($subtotalcero,2)) > $ltop) $ltop = strlen(number_format($subtotalcero,2));
          if (strlen(number_format($total,2)) > $ltop) $ltop = strlen(number_format($total,2));

          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          }  
          // Subtotal IVA (15 %) 
          $vsubiva = "       Subtotal IVA (15%):";
          $tabla.= $vsubiva;

          $ltop = $ltop + 1;

          $lsi= strlen(number_format($subtotaliva,2));
          $dife = 0;
          if($lsi < $ltop){
            $dife = $ltop - $lsi;
            for ($i=0; $i <= $dife; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($subtotaliva,2). "\r\n";

          // Subtotal IVA (0 %) 
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "        Subtotal IVA (0%):";
          $lsc = strlen(number_format($subtotalcero,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($subtotalcero,2). "\r\n";

          // DESCUENTO
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "                Descuento:";
          $lsc= strlen(number_format($descuento,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($descuento,2). "\r\n";

          // Subtotal c/Desc IVA (15%) 
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "Subtotal c/Desc IVA (15%):";
          $lsc= strlen(number_format($subtotaldiva,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($subtotaldiva,2). "\r\n";

          // Subtotal c/Desc IVA (0%) 
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= " Subtotal c/Desc IVA (0%):";
          $lsc= strlen(number_format($subtotaldcero,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($subtotaldcero,2). "\r\n";

          // IVA (15%) 
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "                IVA (15%):";
          $lsc= strlen(number_format($montoiva,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($montoiva,2). "\r\n";

          // Total 
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "                    Total:";
          $lsc= strlen(number_format($total,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($total,2). "\r\n";


        }else{
          $ltop = strlen(number_format($subtotaliva,2));
          if (strlen(number_format($subtotalcero,2)) > $ltop) $ltop = strlen(number_format($subtotalcero,2));
          if (strlen(number_format($total,2)) > $ltop) $ltop = strlen(number_format($total,2));          
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          }  
          // Subtotal IVA (15 %) 
          $vsubiva = "                Subtotal:";
          $tabla.= $vsubiva;

          $ltop = $ltop + 1;

          $lsi= strlen(number_format($subtotaliva,2));
          $dife = 0;
          if($lsi < $ltop){
            $dife = $ltop - $lsi;
            for ($i=0; $i <= $dife; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($subtotaliva,2). "\r\n";


          // DESCUENTO
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "                Descuento:";
          $lsc= strlen(number_format($descuento,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($descuento,2). "\r\n";

          // Subtotal c/Desc IVA (15%) 
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 




          // Total 
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "                    Total:";
          $lsc= strlen(number_format($total,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($total,2). "\r\n";
        }


        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("factura_imprimir", $data);

    }
*/
    public function filtro(){
        $venta = $this->facturar_model->venta_lst();
        $data["venta"] = $venta;        
        $data["base_url"] = base_url();
        $data["content"] = "filtro";
        $this->load->view("layout", $data);        
    }

    /* Exportar Venta a Excel */
    public function reporteventaXLS(){
        $desde = $this->session->userdata("tmp_ven_desde");
        $hasta = $this->session->userdata("tmp_ven_hasta");
        $vendedor = $this->session->userdata("tmp_ven_vendedor");

        $sucursal = $this->session->userdata("tmp_ven_sucursal");
        $tipofecha = $this->session->userdata("tmp_ven_tipofecha");

        $venta = $this->facturar_model->venta_rango($desde, $hasta, $vendedor, $sucursal, $tipofecha);
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        }


        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteVenta');
        //set cell A1 content with some text

        $empresa = "";
        if ($objemp){
          $empresa = "  - EMPRESA: " . $objemp->nom_emp . "    RUC: " . $objemp->ruc_emp;
        }
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Venta  de ' . 
                                                     substr($desde,0,10) . ' al ' . substr($hasta,0,10) . $empresa);

        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:L1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(10);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Factura');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Tipo');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Punto');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Cliente');
        $this->excel->getActiveSheet()->setCellValue('F3', 'C.I./R.U.C');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Vendedor');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Monto');        
        $this->excel->getActiveSheet()->setCellValue('I3', 'Efectivo');  
        $this->excel->getActiveSheet()->setCellValue('J3', 'Cheque');
        $this->excel->getActiveSheet()->setCellValue('K3', 'Tar.Credito');
        $this->excel->getActiveSheet()->setCellValue('L3', 'Tar.Debito');
        $this->excel->getActiveSheet()->setCellValue('M3', 'Tar.Prepago');
        $this->excel->getActiveSheet()->setCellValue('N3', 'Transferencia');
        $this->excel->getActiveSheet()->setCellValue('O3', 'Din.Electronico');
        $this->excel->getActiveSheet()->setCellValue('P3', 'Otros');
        $this->excel->getActiveSheet()->setCellValue('Q3', 'Anticipo');
        $this->excel->getActiveSheet()->setCellValue('R3', 'Observaciones');

        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('I3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('J3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('K3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('L3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('M3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('N3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('O3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('P3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('Q3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('R3')->getFont()->setBold(true);

        $total = 0;
        $efectivo = 0;
        $cheque = 0;
        $tarjetac = 0;
        $tarjetad = 0;
        $tarjetap = 0;
        $transferencia = 0;
        $dinele = 0;
        $otros = 0;
        $anticipo = 0;
        $fila = 4;
        foreach ($venta as $ven) {
          if($ven->estatus != 3){
            $total += $ven->montototal;
            $efectivo += $ven->efectivo;
            $cheque += $ven->cheque;
            $tarjetac += $ven->tarjetac;
            $tarjetad += $ven->tarjetad;
            $tarjetap += $ven->tarjetap;
            $transferencia += $ven->transferencia;
            $dinele += $ven->dinele;
            $otros += $ven->otros;
            $anticipo += $ven->anticipo;

            @$fec = str_replace('-', '/', $ven->fecharegistro); @$fec = date("d/m/Y  H:i:s", strtotime(@$fec)); 
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->nro_factura);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ven->nom_cancelacion);            
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $ven->mesa);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, $ven->nom_cliente);
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, $ven->nro_ident);
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, $ven->vendedor);
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($ven->montototal,2));
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($ven->efectivo,2));
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, number_format($ven->cheque,2));
            $this->excel->getActiveSheet()->setCellValue('K'.$fila, number_format($ven->tarjetac,2));
            $this->excel->getActiveSheet()->setCellValue('L'.$fila, number_format($ven->tarjetad,2));
            $this->excel->getActiveSheet()->setCellValue('M'.$fila, number_format($ven->tarjetap,2));
            $this->excel->getActiveSheet()->setCellValue('N'.$fila, number_format($ven->transferencia,2));
            $this->excel->getActiveSheet()->setCellValue('O'.$fila, number_format($ven->dinele,2));
            $this->excel->getActiveSheet()->setCellValue('P'.$fila, number_format($ven->otros,2));
            $this->excel->getActiveSheet()->setCellValue('Q'.$fila, number_format($ven->anticipo,2));
            $this->excel->getActiveSheet()->setCellValue('R'.$fila, utf8_decode($ven->observaciones));

            $fila++;          
          }  
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('G'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($total,2));
        $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($efectivo,2));
        $this->excel->getActiveSheet()->setCellValue('J'.$fila, number_format($cheque,2));
        $this->excel->getActiveSheet()->setCellValue('K'.$fila, number_format($tarjetac,2));
        $this->excel->getActiveSheet()->setCellValue('L'.$fila, number_format($tarjetad,2));
        $this->excel->getActiveSheet()->setCellValue('M'.$fila, number_format($tarjetap,2));
        $this->excel->getActiveSheet()->setCellValue('N'.$fila, number_format($transferencia,2));
        $this->excel->getActiveSheet()->setCellValue('O'.$fila, number_format($dinele,2));
        $this->excel->getActiveSheet()->setCellValue('P'.$fila, number_format($otros,2));
        $this->excel->getActiveSheet()->setCellValue('Q'.$fila, number_format($anticipo,2));
        $this->excel->getActiveSheet()->getStyle('F'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('I'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('J'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('K'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('L'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('M'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('N'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('O'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('P'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('Q'.$fila)->getFont()->setBold(true);

        
        $filename='reporteventa.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    /* Exportar Detall de Venta a Excel */
    public function reporteventadetalleXLS(){
        $desde = $this->session->userdata("tmp_ven_desde");
        $hasta = $this->session->userdata("tmp_ven_hasta");
        $vendedor = $this->session->userdata("tmp_ven_vendedor");

        $sucursal = $this->session->userdata("tmp_ven_sucursal");

        $venta = $this->facturar_model->venta_detalles_rango($desde, $hasta, $vendedor, $sucursal);
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        }

        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteDetalleVenta');
        //set cell A1 content with some text
        $empresa = "";
        if ($objemp){
          $empresa = "  - EMPRESA: " . $objemp->nom_emp . "    RUC: " . $objemp->ruc_emp;
        }
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Detalles de Venta  de ' . 
                                                     substr($desde,0,10) . ' al ' . substr($hasta,0,10) . $empresa);
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:L1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(10);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'C.I./R.U.C');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Cliente');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Placa');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Factura');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Cantidad');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Uni.Med.');        
        $this->excel->getActiveSheet()->setCellValue('I3', 'Precio Unit');  
        $this->excel->getActiveSheet()->setCellValue('J3', 'Base Imp.');
        $this->excel->getActiveSheet()->setCellValue('K3', 'Impuesto');
        $this->excel->getActiveSheet()->setCellValue('L3', 'Valor Total');
        $this->excel->getActiveSheet()->setCellValue('M3', 'Precio s/Subsidio');
        $this->excel->getActiveSheet()->setCellValue('N3', 'Total s/Subsidio');
        $this->excel->getActiveSheet()->setCellValue('O3', 'Ahorro p/Subsidio');

        $this->excel->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);

        $montoiva = 0;
        $valortotal = 0;
        $totalsinsubsidio = 0;
        $ahorroporsubsidio = 0;
        $fila = 4;
        $filaini = $fila;
        foreach ($venta as $ven) {
          if($ven->estatus != 3){
            $montoiva += $ven->montoiva;
            $valortotal += $ven->valortotal;
            $totalsinsubsidio += $ven->valorsinsubsidio;
            $ahorroporsubsidio += $ven->ahorroporsubsidio;

            @$fec = str_replace('-', '/', $ven->fecharegistro); @$fec = date("d/m/Y  H:i:s", strtotime(@$fec)); 
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->nro_ident);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ven->nom_cliente);            
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $ven->placa_matricula);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, $ven->nro_factura);
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, $ven->descripcion);
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($ven->cantidad,2));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, $ven->unidadmedida);
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($ven->precio,6));
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, number_format($ven->descsubtotal,2));
            $this->excel->getActiveSheet()->setCellValue('K'.$fila, number_format($ven->montoiva,2));
            $this->excel->getActiveSheet()->setCellValue('L'.$fila, number_format($ven->valortotal,2));
            $this->excel->getActiveSheet()->setCellValue('M'.$fila, number_format($ven->preciosinsubsidio,6));
            $this->excel->getActiveSheet()->setCellValue('N'.$fila, number_format($ven->valorsinsubsidio,2));
            $this->excel->getActiveSheet()->setCellValue('O'.$fila, number_format($ven->ahorroporsubsidio,2));

            $fila++;          
          }  
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('J'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('K'.$fila, number_format($montoiva,2));
        $this->excel->getActiveSheet()->setCellValue('L'.$fila, number_format($valortotal,2));
        $this->excel->getActiveSheet()->setCellValue('N'.$fila, number_format($totalsinsubsidio,2));
        $this->excel->getActiveSheet()->setCellValue('O'.$fila, number_format($ahorroporsubsidio,2));

        $currencyFormat = '_(#,##0.00_);_( (#,##0.00);_( "-"??_);_(@_)';
        $priceFormat = '_(#,##0.000000_);_( (#,##0.000000);_( "-"??_);_(@_)';
        $this->excel->getActiveSheet()->getStyle('I'.$filaini.':I'.$fila)->getNumberFormat()->setFormatCode($priceFormat);
        $this->excel->getActiveSheet()->getStyle('J'.$filaini.':L'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->getStyle('M'.$filaini.':M'.$fila)->getNumberFormat()->setFormatCode($priceFormat);
        $this->excel->getActiveSheet()->getStyle('N'.$filaini.':O'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->getStyle('J'.$fila.':O'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);

        $this->excel->getActiveSheet()->getStyle('J'.$fila.':O'.$fila)->getFont()->setBold(true);

        $this->excel->getActiveSheet()->freezePane('A4');

        
        $filename='reportedetalleventa.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  


    /* Reporte de Productos mas Vendidos */
    public function reporteprodmasvendido(){
      date_default_timezone_set("America/Guayaquil");
      $fdesde = date("Y-m-d"); 
      $fhasta = date("Y-m-d"); 
      $varhd = '00:00:00';
      $varhh = '23:59:59';
      $desde = $fdesde." ".$varhd;
      $hasta = $fhasta." ".$varhh;


      $this->session->unset_userdata("tmp_mven_desde"); 
      $this->session->unset_userdata("tmp_mven_hasta");

      $this->session->set_userdata("tmp_mven_desde", NULL);
      if ($desde != NULL) { $this->session->set_userdata("tmp_mven_desde", $desde);} 
      else { $this->session->set_userdata("tmp_mven_desde", NULL); }

      $this->session->set_userdata("tmp_mven_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmp_ven_hasta", $hasta); } 
      else { $this->session->set_userdata("tmp_mven_hasta", NULL); }

      $monto = $this->facturar_model->montomasvendidosrango($desde, $hasta);
      $data["monto"] = $monto;
      $data["base_url"] = base_url();
      $data["content"] = "venta_mayor";
      $this->load->view("layout", $data);

      /*
      $venta = $this->facturar_model->productosmasvendidos();
      $data["base_url"] = base_url();
      $data["venta"] = $venta;
      $this->load->view("venta_prodmasvendido", $data);      
      */

    }

 
    /* Exportar Productos mas Vendidos a Excel */
    public function reporteprodmasvendidoXLS(){
        date_default_timezone_set("America/Guayaquil");
        $desde = $this->session->userdata("tmp_mven_desde");
        $hasta = $this->session->userdata("tmp_mven_hasta");

        $venta = $this->facturar_model->productosmasvendidosrango($desde, $hasta);

        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ProductoMasVendido');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Productos Mas Vendidos');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Cod.Barra');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Cod.Auxiliar');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Precio');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Cantidad');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Total');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Categoria');

        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);

        $total = 0;
        $fila = 4;
        foreach ($venta as $det) {
          $total += $det->total;

          $this->excel->getActiveSheet()->setCellValue('A'.$fila, $det->pro_codigoauxiliar);
          $this->excel->getActiveSheet()->setCellValue('B'.$fila, $det->pro_codigobarra);
          $this->excel->getActiveSheet()->setCellValue('C'.$fila, $det->pro_nombre);
          $this->excel->getActiveSheet()->setCellValue('D'.$fila, number_format($det->pro_precioventa,2));
          $this->excel->getActiveSheet()->setCellValue('E'.$fila, $det->cantidadtotal);
          $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($det->total,2));
          $this->excel->getActiveSheet()->setCellValue('G'.$fila, $det->cat_descripcion);

          $fila++;          
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('E'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($total,2));
        $this->excel->getActiveSheet()->getStyle('E'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F'.$fila)->getFont()->setBold(true);

         
        $filename='prodmasvendido.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    public function reporteventatarjetaXLS(){
      $desde = $this->session->userdata("tmp_ven_desde");
      $hasta = $this->session->userdata("tmp_ven_hasta");
      $vendedor = $this->session->userdata("tmp_ven_vendedor");
      $venta = $this->facturar_model->rptventatarjeta($desde, $hasta, $vendedor);
        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Reporte de Venta con Tarjetas');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Venta con Tarjetas');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(10);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Factura');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Tipo');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Cliente');
        $this->excel->getActiveSheet()->setCellValue('E3', 'C.I./R.U.C.');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Total Venta ');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Monto Tarjeta');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Tipo Tarjeta');
        $this->excel->getActiveSheet()->setCellValue('I3', 'Nombre Tarjeta');
        $this->excel->getActiveSheet()->setCellValue('J3', 'Banco');
        $this->excel->getActiveSheet()->setCellValue('K3', 'Nro Tarjeta');
        $this->excel->getActiveSheet()->setCellValue('L3', 'Fec Emisión');
        $this->excel->getActiveSheet()->setCellValue('M3', 'Nro Documento');
        $this->excel->getActiveSheet()->setCellValue('N3', 'Desc Documento');

        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('I3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('J3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('K3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('L3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('M3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('N3')->getFont()->setBold(true);

        $total = 0;
        $fila = 4;
        foreach ($venta as $ven) {
          if($ven->estatus != 3){
            $total += $ven->montotarjeta;

            $fec = str_replace('-', '/', $ven->fecha); $fec = date("d/m/Y", strtotime($fec));  
            $fech = str_replace('-', '/', $ven->fecha); $fech = date("d/m/Y", strtotime($fech));
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->nro_factura);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ven->nom_cancelacion);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $ven->nom_cliente);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, $ven->nro_ident);
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($ven->totalventa,2));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($ven->montotarjeta,2));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, $ven->tipo);
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, $ven->tarjeta);
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, $ven->banco);
            $this->excel->getActiveSheet()->setCellValue('K'.$fila, $ven->numerotarjeta);
            $this->excel->getActiveSheet()->setCellValue('L'.$fila, $ven->fechaemision);
            $this->excel->getActiveSheet()->setCellValue('M'.$fila, $ven->numerodocumento);
            $this->excel->getActiveSheet()->setCellValue('N'.$fila, $ven->descripciondocumento);            

            $fila++;          
          }  
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('F'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($total,2));
        $this->excel->getActiveSheet()->getStyle('F'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G'.$fila)->getFont()->setBold(true);


        
        $filename='reporteventatarjeta.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    public function upd_venta_total_usu(){
        $desde = $this->session->userdata("tmp_ven_desde");
        $hasta = $this->session->userdata("tmp_ven_hasta");
        $idusu = $this->session->userdata("sess_id");
        $monto_ventas = $this->facturar_model->ventas_total_rango_usu($desde, $hasta, $idusu);
        $arr = $monto_ventas;
        print json_encode($arr);               

    }        

    public function busca_nombre(){
        $nom = $this->input->post('nom');
        $resu = $this->Pedido_model->busca_cliente($nom);
        if(count($resu) > 0){ $mens = $resu[0];  }
        else { $mens = $resu[0]; }
        $arr['mens'] = $mens;

        $idcli = 0;
        if ($resu) { $idcli = $resu[0]->id_cliente;}
        $credito = $this->facturar_model->montocredito($idcli);        
        $arr['credito'] = $credito;

        print json_encode($arr);
    }

    /* VALIDA CLIENTE */
    public function valcliente(){
        $idcliente = $this->input->post('idcliente');
        $resu = $this->Pedido_model->valida_cliente($idcliente);
        $credito = null;        
        if(count($resu) > 0){ 
          $mens = $resu[0];  
          if ($mens != null){
            $credito = $this->facturar_model->montocredito($mens->id_cliente);        
          }
        }
        else { $mens = $resu[0]; }
        $arr['mens'] = $mens;

        $arr['credito'] = $credito;

        print json_encode($arr);
    }

    /* FUNCIONES ESPECIALES DE BUSQUEDA */
    public function valclientenombre(){
        $tmpArray=array();
        $nomcli = $this->input->get('nombre');
        $data = $this->Pedido_model->valida_nombre($nomcli);
        foreach ($data as $row) {
            $tmpArray[] = $row->nombre;
        }
        print json_encode($tmpArray);
    }

    /* ELIMINA EL CLIENTE DE LA MESA */
    public function elim_cliente(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");  
        $delcli = $this->Pedido_model->delmesacli($id_mesa);
        $arr['resu'] = $id_mesa;
        print json_encode($arr);
    }

    /* GUARDAR DATOS DEL CLIENTE EN TABLA PEDIDO */
    public function reg_cliente(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $identcliente = $this->input->post('idcli');
      //  $identcliente = 0;
      //  $idmesero = $this->input->post('idmesero');
      //  if($idmesero == ""){ $idmesero = 0;}
        $resu = $this->Pedido_model->upd_cliente($id_mesa, $identcliente);
        $arr['resu'] = $resu;
        print json_encode($arr);
    }

    /* VERIFICAR SI EXISTE EL MESERO PARA EMITIR FACTURA */
    public function fact_mesero(){
        $id_mesa = $this->input->post("id");
        $mesero = $this->Pedido_model->verifica_mesero($id_mesa);

        if($mesero > 0){
          $this->session->unset_userdata("tmp_idmesa_factura"); 
          $this->session->set_userdata("tmp_idmesa_factura", NULL);
          if ($id_mesa != NULL) { $this->session->set_userdata("tmp_idmesa_factura", $id_mesa); } 
          else { $this->session->set_userdata("tmp_idmesa_factura", NULL); }
          $id_mesa = $this->session->userdata("tmp_idmesa_factura");
        }

        $arr['resu'] = $mesero;
        print json_encode($arr);     
        
    }

    public function reportegastosXLS(){

        $desde = $this->session->userdata("tmp_gas_desde");
        $hasta = $this->session->userdata("tmp_gas_hasta");
        $sucursal = $this->session->userdata("tmp_gas_sucursal");
        /* Tratamiento de Fecha Desde 
        $fec_a = str_replace('-', '/', $vard); 
        $desde = date("Y-d-m", strtotime($fec_a)); */
        /* Tratamiento de Fecha Hasta 
        $fec_h = str_replace('-', '/', $varh); 
        $hasta = date("Y-d-m", strtotime($fec_h)); */
        /* Se consulta el rago de Fecha */
        $gasto = $this->Gastos_model->venta_rango($sucursal, $desde, $hasta);    

      //  print_r($gasto);  die;

        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteGastos');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Gastos');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Proveedor');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Factura');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Descripcion');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Categoria');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Estatus');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Total');

        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);

        $total = 0;
        $fila = 4;
        foreach ($gasto as $det) {
          if($det->estatus != 3){
            if ($det->cod_sri_tipo_doc != '04')
              $total = $total  + $det->total;
            else
              $total = $total  - $det->total;
          }


          $fec = str_replace('-', '/', $det->fecha); 
          $fec = date("d/m/Y", strtotime($fec));  
          $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
          $this->excel->getActiveSheet()->setCellValue('B'.$fila, $det->nom_proveedor);
          $this->excel->getActiveSheet()->setCellValue('C'.$fila, $det->nro_factura);
          $this->excel->getActiveSheet()->setCellValue('D'.$fila, $det->descripcion);
          $this->excel->getActiveSheet()->setCellValue('E'.$fila, $det->categoria);
          $this->excel->getActiveSheet()->setCellValue('F'.$fila, $det->desc_estatus);          
          if ($det->cod_sri_tipo_doc != '04')
              $razon = 1;
          else
              $razon = -1;
          $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($det->total * $razon,2));

          $fila++;          
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('F'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($total,2));
        $this->excel->getActiveSheet()->getStyle('F'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G'.$fila)->getFont()->setBold(true);

         
        $filename='reportegasto.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007'); 
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_venta_fecha() {
      $this->session->unset_userdata("tmp_ven_desde"); 
      $this->session->unset_userdata("tmp_ven_hasta");
      $this->session->unset_userdata("tmp_ven_vendedor");
      $this->session->unset_userdata("tmp_ven_sucursal");
      $this->session->unset_userdata("tmp_ven_tipofecha");
      $vendedor = $this->input->post("vendedor");
      $sucursal = $this->input->post("sucursal");
      $tipofecha = $this->input->post("tipofecha");
      $horad = $this->input->post("horad");
      $horah = $this->input->post("horah");
      $fecdesde = $this->input->post("fdesde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      $fechasta = $this->input->post("fhasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));
      $vdesde = $desde." ".$horad;
      $vhasta = $hasta." ".$horah;        
      $this->session->set_userdata("tmp_ven_desde", NULL);
      if ($vdesde != NULL) { $this->session->set_userdata("tmp_ven_desde", $vdesde);} 
      else { $this->session->set_userdata("tmp_ven_desde", NULL); }
      $this->session->set_userdata("tmp_ven_hasta", NULL);
      if ($vhasta != NULL) { $this->session->set_userdata("tmp_ven_hasta", $vhasta);} 
      else { $this->session->set_userdata("tmp_ven_hasta", NULL);}
      $this->session->set_userdata("tmp_ven_vendedor", NULL);
      if ($vendedor != NULL) { $this->session->set_userdata("tmp_ven_vendedor", $vendedor);} 
      else { $this->session->set_userdata("tmp_ven_vendedor", NULL);}
      if ($sucursal != NULL) { $this->session->set_userdata("tmp_ven_sucursal", $sucursal);} 
      else { $this->session->set_userdata("tmp_ven_sucursal", NULL);}
      if ($tipofecha != NULL) { $this->session->set_userdata("tmp_ven_tipofecha", $tipofecha);} 
      else { $this->session->set_userdata("tmp_ven_tipofecha", 1);}
      $arr['resu'] = 1;
      print json_encode($arr);
    } 


    public function upd_listado(){
        $vard = $this->session->userdata("tmp_ven_desde");
        $varh = $this->session->userdata("tmp_ven_hasta");
        /* Tratamiento de Fecha Desde */
        $fec_a = str_replace('-', '/', $vard); 
        $desde = date("Y-d-m", strtotime($fec_a)); 
        /* Tratamiento de Fecha Hasta */
        $fec_h = str_replace('-', '/', $varh); 
        $hasta = date("Y-d-m", strtotime($fec_h)); 
        /* Se consulta el rago de Fecha */
        $lst_venta = $this->facturar_model->venta_rango($desde, $hasta);

        $data["lst_venta"] = $lst_venta;
        $data["base_url"] = base_url();
        $this->load->view("venta_tabla", $data);

    }  

    public function upd_venta_total(){
        $desde = $this->session->userdata("tmp_ven_desde");
        $hasta = $this->session->userdata("tmp_ven_hasta");
        $vendedor = $this->session->userdata("tmp_ven_vendedor");
        $sucursal = $this->session->userdata("tmp_ven_sucursal");
        $tipofecha = $this->session->userdata("tmp_ven_tipofecha");

        $monto_ventas = $this->facturar_model->ventas_total_rango($desde, $hasta, $vendedor, 
                                                                  $sucursal, $tipofecha);

        $arr = $monto_ventas;
        print json_encode($arr);               

    }

    public function anular_factura(){
        $id_venta = $this->input->post("id");
        $fact = $this->facturar_model->busca_factura($id_venta);
        $data["factura"] = $fact;
        $data["base_url"] = base_url();
        $this->load->view("venta_anular", $data);      
    }

    public function anular(){

        $id_venta = $this->input->post("txt_idventa");
        $obs = $this->input->post("txt_obs");

        if ($id_venta != 0){
            $resu = $this->facturar_model->anular_factura($id_venta, $obs);

            $varventa = $this->facturar_model->lst_detventaparakardex($id_venta);    
            foreach ($varventa as $obj) {
              $this->Inventario_model->ins_kardexingreso($obj->id_producto, $obj->nro_factura, 
                                                         'ANULACION DE FACTURA DE VENTA', $obj->cantidad, 
                                                         $obj->precio, $obj->descsubtotal, 
                                                         $obj->pro_idunidadmedida, $obj->id_almacen);
            }    
        }

        $arr = 1;
        print json_encode($arr); 
    }


    public function listadoDataVent() {
        date_default_timezone_set("America/Guayaquil");
        $desde = $this->session->userdata("tmp_ven_desde");
        $hasta = $this->session->userdata("tmp_ven_hasta");
        $vendedor = $this->session->userdata("tmp_ven_vendedor");
        $sucursal = $this->session->userdata("tmp_ven_sucursal");
        $tipofecha = $this->session->userdata("tmp_ven_tipofecha");
        if (!$vendedor) $vendedor = 0;
        if (!$sucursal) $sucursal = 0;
        if (!$tipofecha) $tipofecha = 1;
        $registro = $this->facturar_model->venta_rango($desde, $hasta, $vendedor, $sucursal, $tipofecha); 
        $tabla = "";

        $usua = $this->session->userdata('usua');
        $perfilusuario = $usua->perfil;   

        foreach ($registro as $row) {

          $consumidorfinal = (($row->id_cliente == 1) || (substr($row->nro_ident,0,10) == '9999999999')) ? '1' : '0';

          @$fec = str_replace('-', '/', $row->fecharegistro); @$fec = date("d/m/Y  H:i:s", strtotime(@$fec));
          if($row->estatus != 1){
            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Imprimir Venta\" id=\"'.$row->id_venta.'\" class=\"btn bg-navy color-palette btn-xs btn-grad venta_print\"><i class=\"fa fa-print\"></i></a>';
          }else{
            if ($perfilusuario == 1){
              $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Anular\" id=\"'.$row->id_venta.'\" class=\"btn btn-danger btn-xs btn-grad anu_fact\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i></a> <a href=\"#\" title=\"Imprimir RIDE\" id=\"'.$row->id_venta.'\" class=\"btn bg-blue color-palette btn-xs btn-grad venta_pdf\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Imprimir Venta\" id=\"'.$row->id_venta.'\" class=\"btn bg-navy color-palette btn-xs btn-grad venta_print\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Retención Venta\" id=\"'.$row->id_venta.'\" name=\"'.$consumidorfinal.'\" class=\"btn bg-green color-palette btn-xs btn-grad ret_comp\"><i class=\"fa fa-registered\"></i></a> <a href=\"#\" title=\"Garantia\" id=\"'.$row->id_venta.'\" class=\"btn btn-danger btn-xs btn-grad pdf_garantia\"><i class=\"fa fa-file-pdf-o\"></i></a> </div>';
            }
            else{
              $ver = '<div class=\"text-center \">  <a href=\"#\" title=\"Imprimir Venta\" id=\"'.$row->id_venta.'\" class=\"btn bg-navy color-palette btn-xs btn-grad venta_print\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Retención Venta\" id=\"'.$row->id_venta.'\" name=\"'.$consumidorfinal.'\" class=\"btn bg-green color-palette btn-xs btn-grad ret_comp\"><i class=\"fa fa-registered\"></i></a> <a href=\"#\" title=\"Garantia\" id=\"'.$row->id_venta.'\" class=\"btn btn-danger btn-xs btn-grad pdf_garantia\"><i class=\"fa fa-file-pdf-o\"></i></a> </div>';
            }  
          }          

/*
<a href=\"#\" title=\"Editar\" id=\"'.$row->id_venta.'\" class=\"btn btn-success btn-xs btn-grad edi_fact\"><i class=\"fa fa-pencil-square-o\"></i></a>
*/          
           
            $tabla.='{"estatus":"' . $row->estatus . '",
                      "fecha":"' . $fec . '",
                      "caja":"' . addslashes($row->nom_caja) . '",
                      "factura":"' . $row->nro_factura . '",
                      "forpago":"' . addslashes($row->nom_cancelacion) . '",
                      "mesa":"' . addslashes($row->mesa) . '",
                      "cliente":"' . addslashes($row->nom_cliente) . '",
                      "vendedor":"' . addslashes($row->vendedor) . '",
                      "efectivo":"'.$row->efectivo.'",
                      "cheque":"'.$row->cheque.'",
                      "tarjetac":"'.$row->tarjetac.'",
                      "tarjetad":"'.$row->tarjetad.'",
                      "tarjetap":"'.$row->tarjetap.'",
                      "transferencia":"'.$row->transferencia.'",
                      "dinele":"'.$row->dinele.'",
                      "otros":"'.$row->otros.'",
                      "anticipo":"'.$row->anticipo.'",
                      "monto":"' . $row->montototal . '",   
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    /* SESION TEMPORAL PARA EDITAR LA FACTURAR */
     public function tmp_factura() {
        $this->session->unset_userdata("tmp_idfactura"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_idfactura", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_idfactura", $id); } 
        else { $this->session->set_userdata("tmp_idfactura", NULL); }
        $arr['resu'] = $this->session->userdata("tmp_idfactura");;
        print json_encode($arr);
    }

    public function editar_factura(){
      $idfactura = $this->session->userdata("tmp_idfactura");
      $factura = $this->facturar_model->edit_factura($idfactura);
      $nro_ident = $factura->nro_ident;
      $cliente = $this->facturar_model->busca_cliente($nro_ident);
      $facturadet = $this->facturar_model->det_factura($idfactura);
      /* Obtiene el Numero de Factura */
      $nrofact = $this->facturar_model->sel_nro_factura();
      /* Obtiene el Numero de Nota de Venta */
      $nronv = $this->facturar_model->sel_nro_nronot(); 
      $nrofactura = "001-001-".$nrofact;
      $data["nrofactura"] = $nrofactura;
      $data["nronv"] = $nronv;      
      $data["factura"] = $factura;
      $data["facturadet"] = $facturadet;      
      $data["cliente"] = $cliente;
      $data["base_url"] = base_url();
      $data["content"] = "factura_edit";
      $this->load->view("layout", $data);
    }



 public function guardarmodificacion(){
      $dif = 0;
      $idusu = $this->session->userdata("sess_id");
      $fec = $this->input->post("fecha");
      $fecha = str_replace('/', '-', $fec); 
      $fecha = date("Y-m-d", strtotime($fecha));
      $idventaanular = $this->input->post("idventaanular");
      $idmesa = $this->input->post("idmesa");
      $varmesa = $this->input->post("mesa");
      $mesero = $this->input->post("mesero");
      $fp = $this->input->post("cmb_forma");

      if ($fp == 2){
        $nro_factura = $this->input->post("nro_factura");     
      } else {
        $nro_factura = $this->input->post("nro_notaventa");
      }

      $nro_ident = $this->input->post("nro_ident");
      $nom_cliente = $this->input->post("nom_cliente");
      $cor_cliente = $this->input->post("cor_cliente");
      $telf_cliente = $this->input->post("telf_cliente");
      $dir_cliente = $this->input->post("dir_cliente");

      $clipago = $this->facturar_model->data_cliente($nro_ident, $nom_cliente, $cor_cliente, $telf_cliente, $dir_cliente);
      $tipo_ident = $clipago->tipo_ident_cliente;
      $nro_ident = $clipago->ident_cliente;
      $nom_cliente = $clipago->nom_cliente;
      $telf_cliente = $clipago->telefonos_cliente; 
      $dir_cliente = $clipago->direccion_cliente;
      $cor_cliente = $clipago->correo_cliente;

      if($telf_cliente != NULL){}else{$telf_cliente = "";}
      if($dir_cliente != NULL){}else{$dir_cliente = "";}

      $area = $varmesa;
      $mesa = $varmesa;

      $id_venta = $this->facturar_model->anularycrear_factura($idventaanular, 'Modificacion de Factura', $fecha, $fp, $nro_factura, $tipo_ident, $nro_ident, $nom_cliente, $telf_cliente, $dir_cliente);

      $arr['dat'] = $id_venta->vid;
      print json_encode($arr);
    }

/* ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ */
/* PROCESO DE FACTURACION GENERAL */

    public function factura_general(){
      $idusu = $this->session->userdata("sess_id");
      $nrofact = $this->facturar_model->sel_nro_factura();
      $nrofactura = "001-001-".$nrofact;       
      $nronv = $this->facturar_model->sel_nro_nronot(); 
      $cliente = $this->facturar_model->carga_cliente($idusu, $nrofactura);
      $pro = $this->facturar_model->lst_pro();
      $tipfact = $this->facturar_model->tipo_factura();
      $tipident = $this->facturar_model->tipo_identificacion();
      $cat = $this->facturar_model->cat_menu();

      $lstdetalle = $this->facturar_model->lst_tmpventadetalle($idusu);
      
      $nrofactura = "001-001-".$nrofact;    
      $data["nrofactura"] = $nrofactura;
      $data["tipfact"] = $tipfact;        
      $data["nronv"] = $nronv;        
      $data["pro"] = $pro; 
      $data["cat"] = $cat; 
      $data["tipident"] = $tipident; 
      $data["cliente"] = $cliente;    
      $data["lstdetalle"] = $lstdetalle;    
      $data["base_url"] = base_url();
      $this->load->view("factura_general", $data);      
    }

    /* GUARDAR DATOS DEL CLIENTE EN TABLA VENTA_TMP */
    public function upd_ventcliente(){
      $idusu = $this->session->userdata("sess_id");
      $idc = $this->input->post("idc");      
      $idcli = $this->input->post("idcli");
      $idtp = $this->input->post("idtp");
      $nom = $this->input->post("nom");
      $tel = $this->input->post("tel");
      $cor = $this->input->post("cor");
      $dir = $this->input->post("dir");
      $ciu = $this->input->post("ciu");
      $placa = $this->input->post("placa");

      $resu = $this->facturar_model->upd_ventcliente($idusu, $idcli, $idtp, $nom, $tel, $cor, $dir, $ciu, $idc, $placa);
      $arr['resu'] = $resu;
      $objcategoria = $this->Cliente_model->sel_cli_identificacion($idcli);
      $categoriacliente = 0;
      $nombrecategoria = "";
      $logocategoria = "";
      if ($objcategoria->id_categoriaventa != null) { 
        $categoriacliente = $objcategoria->id_categoriaventa;
        $objcat = $this->Cliente_model->sel_categoria_venta_id($categoriacliente);
        $nombrecategoria = $objcat->categoria;
        $logocategoria = $objcat->icono_path;
      }
      $arr['categoriacliente'] = $categoriacliente;
      $arr['nombrecategoria'] = $nombrecategoria;
      $arr['logocategoria'] = $logocategoria;

      print json_encode($arr);
    }

    public function edit_descripciondetalle(){
        $id = $this->input->post("id");
        //$descripcion = $this->input->post("descripcion");
        $descripcion = $this->facturar_model->sel_descripciondetalle($id);
        $data["id"] = $id;
        $data["descripcion"] = $descripcion;
        $data["base_url"] = base_url();
        $this->load->view("factura_detalle", $data);
    }

    public function udp_descripciondetalle(){
      $id = $this->input->post("id");
      $descripcion = $this->input->post("descripcion");
      $resu = $this->facturar_model->udp_descripciondetalle($id, $descripcion);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

  /* INSERTA PRODUCTO EN TABLA DETALLE VENTA_TMP */
    public function ins_detalleventatmp(){
      $idusu = $this->session->userdata("sess_id");
      $idpro = $this->input->post("id");
      $idalm = $this->input->post("idalm");

      $resu = $this->facturar_model->ins_detalleventatmp($idusu, $idpro, $idalm);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function ins_detalleventatmpcodbar(){
      $idusu = $this->session->userdata("sess_id");
      $codbar = $this->input->post("codbar");
      $idalm = $this->input->post("idalm");
      $resu = $this->facturar_model->ins_detalleventatmpcodbar($idusu, $codbar, $idalm);
      $arr['resu'] = 1;
      print json_encode($arr);
    }


    /* ACTUALIZA TABLA DETALLE PRODUCTO */
    public function actualiza_tablageneral(){
        $idusu = $this->session->userdata("sess_id");
        $lstdetalle = $this->facturar_model->lst_tmpventadetalle($idusu);
        $lstprecios = $this->facturar_model->lstprecios();
        $idcli = $this->facturar_model->selclitmp($idusu);  
        $preciopro = $this->facturar_model->preciopro($idcli);
        $tp = $this->Parametros_model->tipo_precio();
        $descpro = $this->Parametros_model->sel_descpro();
        $tipodescprod = $this->Parametros_model->sel_tipodescuentoproducto();   
        $tarifaiva = $this->Parametros_model->iva_get();
        $habilitadetalletotaliva = $this->Parametros_model->sel_detalletotalivaventa();

        $usua = $this->session->userdata('usua');
        $perfilusuario = $usua->perfil;   
        $data["perfilusuario"] = $perfilusuario; 

        $data["decimalesprecio"] = $this->Parametros_model->sel_decimalesprecio();    
        $data["decimalescantidad"] = $this->Parametros_model->sel_decimalescantidad();    

        $habserie = $this->Parametros_model->sel_numeroserie();
        $data["habilitaserie"] = $habserie->valor;      

        $data["cambioprecio"] = $this->Parametros_model->sel_habilitacambioprecio();    

        $usua = $this->session->userdata('usua');
        $perfil = $usua->perfil;
        $data["perfil"] = $perfil;   

        $data["tp"] = $tp;        
        $data["lstdetalle"] = $lstdetalle;
        $data["lstprecios"] = $lstprecios;
        $data["preciopro"] = $preciopro; 
        $data["descpro"] = $descpro;
        $data["tipodescprod"] = $tipodescprod;
        $data["habilitadetalletotaliva"] = $habilitadetalletotaliva;
        $data["tarifaiva"] = $tarifaiva->valor;
        $data["base_url"] = base_url();
        $this->load->view("factura_general_tabla", $data);            
    }



  /* ELIMINA PRODUCTO EN TABLA DETALLE VENTA_TMP */
    public function del_detalleventatmp(){
      $iddetalle = $this->input->post("id");

      $resu = $this->facturar_model->del_detalleventatmp($iddetalle);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

  /* Actualiza PRODUCTO EN TABLA DETALLE VENTA_TMP */
    public function upd_detalleventa(){
      $iddetalle = $this->input->post("id");
      $cantidad = $this->input->post("cantidad");
      $precio = $this->input->post("precio");
      $valiva = $this->input->post("valiva");
      $subtotal = $this->input->post("subtotal");
      $tp = $this->input->post("tp");
      $porcpro = $this->input->post("porcpro");

      $resu = $this->facturar_model->upd_detalleventa($iddetalle, $cantidad, $precio, $valiva, $subtotal, $tp, $porcpro);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

  /* Actualiza Descuento EN TABLA VENTA_TMP */
    public function upd_descuentoventatmp(){
      $idusu = $this->session->userdata("sess_id");
      $descuento = $this->input->post("descuento");

      $resu = $this->facturar_model->upd_descuentoventatmp($idusu, $descuento);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

  /* Actualiza Subtotales EN TABLA VENTA_TMP */
    public function lst_subtotalesventatmp(){
      $idusu = $this->session->userdata("sess_id");
      $resu = $this->facturar_model->lst_subtotalesventatmp($idusu);
      $arr['subtotaliva'] = $resu->subtotaliva;
      $arr['subtotalcero'] = $resu->subtotalcero;
      $arr['descsubtotaliva'] = $resu->descsubtotaliva;
      $arr['descsubtotalcero'] = $resu->descsubtotalcero;
      $arr['descuento'] = $resu->descuento;      
      $arr['montoiva'] = $resu->montoiva;
      $arr['monto'] = $resu->monto;
      $arr['montopagado'] = $resu->montopagado;
      $arr['montopagadoefectivo'] = $resu->montopagadoefectivo;
      print json_encode($arr);
    }

    /* FACTURAR GENERAL */
    public function pagar_facturageneral(){
      $idusu = $this->session->userdata("sess_id");
      $fp = $this->input->post("cmb_forma");

      $tipocancelacion = $this->input->post("forpago");
      if ($tipocancelacion == "Contado") {$idtipocancelacion = 1;} else {$idtipocancelacion = 2;}
      

      $id_venta = $this->facturar_model->pagar_facturageneral($idusu, $fp, $idtipocancelacion);

      $this->facturar_model->crea_garantia($id_venta);


      if ($id_venta != 0){
          /* Aqui se actualiza el inventario */
          $this->facturar_model->inventario($id_venta);

          $varventa = $this->facturar_model->lst_detventaparakardex($id_venta);    
          foreach ($varventa as $obj) {
              $this->Inventario_model->ins_kardexegreso($obj->id_producto, $obj->nro_factura, 'FACTURA DE VENTA', 
                                                        $obj->cantidad, $obj->precio, $obj->descsubtotal, 
                                                        $obj->pro_idunidadmedida, $obj->id_almacen);
          }    

          $varserie = $this->facturar_model->lst_detventaparakardexserie($id_venta);          
          foreach ($varserie as $det) {
              $this->Inventario_model->ins_seriekardexingreso($det->id_serie, $det->id_almacen, $det->tipomovimiento, 
                                                              $id_venta, $det->nro_factura, $det->fecha, $det->descripcion);                
          }    

          $habilitaclientevendedor = $this->Parametros_model->sel_clientevendedor();
          if ($habilitaclientevendedor == 1){
            $cuotaminima = $this->Parametros_model->sel_cuotaclientevendedor();
            $this->facturar_model->asociar_clientevendedor($id_venta, $cuotaminima);
          }

          // Factura de Servicio - Si no se incluyen productos en la factura
          $cfgser = $this->Serviciotecnico_model->lst_configservicio();
          if (($cfgser->habilita_servicio == 1) && ($cfgser->habilita_productofactura == 0)){
            $listamov = $this->Inventario_model->ins_movimtmp_egreso_servicio($idusu, $id_venta);          
            $fecha = date("Y-m-d");
            if ($listamov){
              foreach ($listamov as $tmpidmov){
                $iddocinv = $this->Inventario_model->guardar($tmpidmov, $fecha);
                $this->Serviciotecnico_model->ins_servicio_egreso_inventario($id_venta, $iddocinv);
              }
            }
          }

          $habilitaclientecategoria = $this->Parametros_model->sel_habilitaclientecategoria();
          if ($habilitaclientecategoria == 1){
            $this->facturar_model->asignar_clientecategoria($id_venta);
          }

      }
      $enviosrifactura = $this->facturar_model->habilitado_enviosrifactura($id_venta);
      $arr['enviosrifactura'] = $enviosrifactura;

      $arr['dat'] = $id_venta;
      print json_encode($arr);
    }


/* ===== FACTURACION DEPOSITO =============================================================================================================================*/
    public function factura_deposito(){
      $fpvtc = 0;
      $idusu = $this->session->userdata("sess_id");
      $fptc = $this->session->userdata("tmp_forpago");
      if ($fptc == NULL || $fptc == "") { $fpvtc = 1; $fptc = "Contado"; $this->session->set_userdata("tmp_forpago", $fptc); } 
      if($fptc == "Contado"){ $fpvtc = 1; }
      if($fptc == "Credito"){ $fpvtc = 2; }    
      $tp = $this->Parametros_model->tipo_precio();
      $factsexis = $this->Parametros_model->sel_facturasinexistencia();
      $codestab = $this->Parametros_model->sel_codigoestab();
      $codptoemi = $this->Parametros_model->sel_codigopuntoemision();
      $facturapdf = $this->Parametros_model->sel_facturapdf();
      $vendedor = $this->facturar_model->lst_vendedores();
      $data["vendedor"] = $vendedor;      
      /*$nrofact = $this->facturar_model->sel_nro_factura();
      $nrofactura = $codestab->valor . "-" . $codptoemi->valor . "-" . $nrofact;       */
      /*$nronv = $this->facturar_model->sel_nro_nronot(); */
      $lstcaja = $this->facturar_model->lst_caja($idusu);
      $nrofactura = "";
      $nronv = "";
      if ($lstcaja) { 
        $nrofactura = $lstcaja[0]->nrofactura; 
      }
      $cliente = $this->facturar_model->carga_cliente($idusu, $nrofactura);
      $pro = $this->facturar_model->lst_pro();
      $tipfact = $this->facturar_model->tipo_factura();
      $tipident = $this->facturar_model->tipo_identificacion();
      $proalma = $this->facturar_model->lst_proalmacen();
      $almacenes = $this->facturar_model->lst_almacenes();
      $lstprecios = $this->facturar_model->lstprecios();
      $data["lstprecios"] = $lstprecios;
      $idcli = $cliente->id_cliente;
      $mcredito = $this->facturar_model->montocredito($idcli);
      $data["mcredito"] = $mcredito; 
      $idventa = $cliente->id_venta;
      $abono = $this->facturar_model->sumforpago($idventa, $fpvtc);
      $data["abono"] = $abono->monto;  
      $data["efectivo"] = $abono->efectivo;  
      $crecuo = $this->facturar_model->crecuotmp($idventa);
      $data["crecuo"] = $crecuo;           
      $preciopro = $this->facturar_model->preciopro($idcli);
      $data["preciopro"] = $preciopro;  
      $lstforpago =  $this->facturar_model->selforpagovent($idventa);
      $data["lstforpago"] = $lstforpago;       
      $lstpro = $this->Pedido_model->productos();
      $data["lstpro"] = $lstpro;      
      $cat = $this->facturar_model->sel_cat();
      $data["lstcat"] = $cat;      

      $anticipo = $this->facturar_model->sel_anticipo($idusu);
      $data["anticipo"] = $anticipo;      

/*      $data["nrofactura"] = $nrofactura;*/
      $data["tipfact"] = $tipfact;        
/*      $data["nronv"] = $nronv;        */
      $data["pro"] = $pro; 
      $data["facturapdf"] = $facturapdf; 
      $data["proalma"] = $proalma; 
      $data["almacenes"] = $almacenes; 
      $data["tipident"] = $tipident; 
      $data["cliente"] = $cliente;    
      $data["idusu"] = $idusu;    
      $data["tp"] = $tp;
      $data["factsexis"] = $factsexis->valor;
      $lstdetalle = $this->facturar_model->lst_tmpventadetalle($idusu);
      $data["lstdetalle"] = $lstdetalle; 

      $data["lstcaja"] = $lstcaja; 

      $data["decimalesprecio"] = $this->Parametros_model->sel_decimalesprecio();    
      $data["decimalescantidad"] = $this->Parametros_model->sel_decimalescantidad();   

      $data["cambioprecio"] = $this->Parametros_model->sel_habilitacambioprecio();   

      $usua = $this->session->userdata('usua');
      $perfil = $usua->perfil;
      $data["perfil"] = $perfil;   
      
      if ($cliente != NULL){  
        if (($cliente->id_caja != NULL) && ($cliente->id_caja != 0)){
          $objcaja = $this->cajaefectivo_model->sel_cajaefectivo_id($cliente->id_caja);

          $contabiliza = $objcaja->contabilizacion_automatica; 
          $data['contabiliza'] = $contabiliza;

          $cuentasconfig = $this->Contab_comprobante_model->cuentas_configuradas_venta($objcaja->id_empresa);
          $data["cuentasconfig"] = $cuentasconfig;   

          $cuentasconfigcobro = $this->Contab_comprobante_model->cuentas_configuradas_cobro($objcaja->id_empresa);
          $data["cuentasconfigcobro"] = $cuentasconfigcobro;   
        }
      }

      $lstdatoadicional = $this->facturar_model->lst_venta_datoadicional_tmp($idusu);
      $data["lstdatoadicional"] = $lstdatoadicional;   

      $this->session->unset_userdata("tmp_almexis_factura"); 
      $this->session->set_userdata("tmp_almexis_factura", 0);


      $data["base_url"] = base_url();
      $this->load->view("factura_deposito", $data);      
    }

    public function tmp_gas(){
      $id = $this->input->post("id");
      $this->session->unset_userdata("tmp_gas"); 
      $this->session->set_userdata("tmp_gas", NULL);
      if ($id != NULL) { $this->session->set_userdata("tmp_gas", $id); } 
      else { $this->session->set_userdata("tmp_gas", NULL); }
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function montogas(){
      $idalm = $this->session->userdata("tmp_gas");    
      $progas = $this->facturar_model->gaspro_pre($idalm);
      $data["progas"] = $progas;    
      $data["cantdecimales"] = $this->Parametros_model->sel_decimalesprecio();    
      $data["base_url"] = base_url();        
      $this->load->view("factura_deposito_preciogas", $data);       
    }

    public function addgas(){
      $idpro = $this->session->userdata("tmp_gas"); 
      $cant = $this->input->post("txt_cant");      
      $monto = $this->input->post("txt_monto");
      $idalm = $this->input->post("txt_alm");
      $idusu = $this->session->userdata("sess_id");
      $resu = $this->facturar_model->ins_detalleventagas($idusu, $idpro, $idalm, $cant, $monto);   
      
     // redirect('facturar/factura_deposito','refresh');   


      $arr['resu'] = 1;
      print json_encode($arr);
     
    }

    public function nuevo(){
      $idusu = $this->session->userdata("sess_id");
      $idfp = "Contado";
      $this->session->unset_userdata("tmp_forpago");
      $this->session->set_userdata("tmp_forpago", $idfp);
      $resu = $this->facturar_model->limpiarventatmp($idusu);   
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function aperturacaja() {
        $data["base_url"] = base_url();
        $caja = $this->Cajaapertura_model->existeapertura();

        $data["caja"] = $caja;
        $data["base_url"] = base_url();
        $this->load->view("factura_cajaapertura", $data);
    }

    /* Guardar la Apertura */
    public function guardaraperturacaja(){
        $monto = $this->input->post('txt_monto');

        $resu = $this->Cajaapertura_model->insertar($monto);

        print "<script language='JavaScript'>alert('Los Datos Fueron Actualizados');</script>";

        redirect('facturar/factura_deposito','refresh');   
    }

    public function cierrecaja() {
        $data["base_url"] = base_url();
        $caja = $this->Cajacierre_model->datosapertura();
        
        $data["caja"] = $caja;
        $data["base_url"] = base_url();
        $this->load->view("factura_cajacierre", $data);
    }

    /* Guardar Cierre */
    public function guardarcierrecaja(){

        $efectivo = $this->input->post('txt_efectivo');
        $tarjeta = $this->input->post('txt_tarjeta');

        $salida = $this->input->post('txt_salida');
        $justi = $this->input->post('txt_justi');

        $egreso = 0;
        $compras = 0;

        $totalcaja = $this->input->post('txt_totalcaja');
        $notacierre = $this->input->post('txt_nota');

        /* SE ACTUALIZA EL REGISTRO DEL USUARIO */
        $resu = $this->Cajacierre_model->guardar($efectivo, $tarjeta, $egreso, $compras, $totalcaja, $notacierre, $salida, $justi);

        print "<script language='JavaScript'>alert('Los Datos Fueron Actualizados');</script>";

        redirect('facturar/factura_deposito','refresh');   

/*        $this->correoCierrecaja();*/

    }


    public function facturar_ventas(){

      date_default_timezone_set("America/Guayaquil");
      $facturapdf = $this->Parametros_model->sel_facturapdf();

      $fdesde = date("Y-m-d"); 
      $fhasta = date("Y-m-d"); 
      $varhd = '00:00:00';
      $varhh = '23:59:59';

      $desde = $fdesde." ".$varhd;
      $hasta = $fhasta." ".$varhh;
      $this->session->set_userdata("tmp_ven_desde", NULL);
      if ($desde != NULL) {
          $this->session->set_userdata("tmp_ven_desde", $desde);
      } else {
          $this->session->set_userdata("tmp_ven_desde", NULL);
      }
      $this->session->set_userdata("tmp_ven_hasta", NULL);
      if ($hasta != NULL) {
          $this->session->set_userdata("tmp_ven_hasta", $hasta);
      } else {
          $this->session->set_userdata("tmp_ven_hasta", NULL);
      }
      $desde = $this->session->userdata("tmp_ven_desde");
      $hasta = $this->session->userdata("tmp_ven_hasta");

      $idusu = $this->session->userdata("sess_id");
      $monto = $this->facturar_model->ventas_total_rango_usu($desde, $hasta, $idusu);

      $data["monto"] = $monto;
      $data["facturapdf"] = $facturapdf;
      $data["base_url"] = base_url();
     
      $this->load->view("facturar_ventas", $data); 
    }

    public function listadoVend() {
        date_default_timezone_set("America/Guayaquil");
        $idusu = $this->session->userdata("sess_id");
        $desde = $this->session->userdata("tmp_ven_desde");
        $hasta = $this->session->userdata("tmp_ven_hasta");
        $registro = $this->facturar_model->venta_rango_usu($desde, $hasta, $idusu); /* bg-orange-active color-palette */

        $usua = $this->session->userdata('usua');

        $tabla = "";
        foreach ($registro as $row) {

          @$fec = str_replace('-', '/', $row->fecharegistro); @$fec = date("d/m/Y  H:i:s", strtotime(@$fec));
          if(($row->estatus == 3) || ($usua->perfil != 1)){
            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Imprimir Venta\" id=\"'.$row->id_venta.'\" class=\"btn bg-navy color-palette btn-xs btn-grad venta_print\"><i class=\"fa fa-print\"></i></a>';
          }else{
            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Anular\" id=\"'.$row->id_venta.'\" class=\"btn btn-danger btn-xs btn-grad anu_fact\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i></a> <a href=\"#\" title=\"Imprimir Venta\" id=\"'.$row->id_venta.'\" class=\"btn bg-navy color-palette btn-xs btn-grad venta_print\"><i class=\"fa fa-print\"></i></a>';
          }          
          
           
            $tabla.='{"estatus":"' . $row->estatus . '",
                      "fecha":"' . $fec . '",
                      "factura":"' . $row->nro_factura . '",
                      "mesa":"' . $row->mesa . '",
                      "cliente":"' . $row->nom_cliente . '",
                      "monto":"' . $row->montototal . '",   
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    public function obtenerDisponibilidad(){
      $iddetalleventa = $this->input->post("id");
      $disponible = $this->facturar_model->obtenerDisponibilidad($iddetalleventa);
      $arr['disponible'] = $disponible;
      print json_encode($arr);
    }

    public function obtenerProductoDisponible(){
      $idprod = $this->input->post("id");
      $idalm = $this->input->post("idalm");
      $disponible = $this->facturar_model->obtenerProductoDisponible($idprod, $idalm);
      $arr['disponible'] = $disponible;
      print json_encode($arr);
    }

    public function obtenerPrecios(){
      $idpro = $this->input->post('idpro');
      $idcliente = $this->input->post('idc');
      $propre = $this->facturar_model->selprecio($idpro, $idcliente);
      print json_encode($propre);      
    }

    public function addfp(){
      $fptc = $this->session->userdata("tmp_forpago");
      $fpvtc = 1;
      if($fptc == "Contado"){ $fpvtc = 1; }
      if($fptc == "Credito"){ $fpvtc = 2; }
      $idreg = $this->input->post("idreg");
      $idventa = $this->input->post("idventa");      
      $fp = $this->input->post("fp");
      $monto = $this->input->post("monto");
      $fechat = $this->input->post("fechat");
      $tiptarjeta = $this->input->post("tiptarjeta");
      $nrotar = $this->input->post("nrotar");
      $banco = $this->input->post("bco");
      $tbanco = $this->input->post("tbco");
      $nrodoc = $this->input->post("nrodoc");
      $tnrodoc = $this->input->post("tnrodoc");
      $descdoc = $this->input->post("descdoc");
      $tdescdoc = $this->input->post("tdescdoc");
      $fechae = $this->input->post("fechae");
      $fechac = $this->input->post("fechac");
      $nrocta = $this->input->post("nrocta");
      if($idreg == 0){
        $addfp = $this->facturar_model->addforpago($idventa, $fp, $monto, $fechat, $tiptarjeta, $nrotar, $banco, $tbanco, $nrodoc, $tnrodoc, $descdoc, $tdescdoc, $fechae, $fechac, $nrocta, $fpvtc);
      }else{
        $updfp = $this->facturar_model->updforpago($idreg, $idventa, $fp, $monto, $fechat, $tiptarjeta, $nrotar, $banco, $tbanco, $nrodoc, $tnrodoc, $descdoc, $tdescdoc, $fechae, $fechac, $nrocta, $fpvtc);
      }
      $selfp = $this->facturar_model->sumforpago($idventa, $fpvtc);
      $arr['monto'] = $selfp->monto;
      $arr['efectivo'] = $selfp->efectivo;
      print json_encode($arr);
    }

    public function actualiza_tablafp(){
      $fptc = $this->session->userdata("tmp_forpago");
      if($fptc == "Contado"){ $fpvtc = 1; }
      if($fptc == "Credito"){ $fpvtc = 2; }      
      $idusu = $this->session->userdata("sess_id");
      $idventa = $this->facturar_model->obtidventatmp($idusu);
      $lstforpago =  $this->facturar_model->selforpagovent($idventa);
      $data["lstforpago"] = $lstforpago;       
      $data["base_url"] = base_url();
      if($fpvtc == 1){
        $this->load->view("factura_formapago_tabla", $data);
      }else{
        $this->load->view("factura_formapagoc_tabla", $data);
      }
                  
    }

    public function delfp(){
      $fptc = $this->session->userdata("tmp_forpago");
      if($fptc == "Contado"){ $fpvtc = 1; }
      if($fptc == "Credito"){ $fpvtc = 2; }      
      $idreg = $this->input->post("idreg");
      $idfp = $this->input->post("idfp");
      $idventa = $this->input->post("idventa");
      $this->facturar_model->delforpagovent($idreg, $idfp, $idventa);
      $selfp = $this->facturar_model->sumforpago($idventa, $fpvtc);
      $arr['monto'] = $selfp->monto;
      $arr['efectivo'] = $selfp->efectivo;
      print json_encode($arr);
    }

    public function add_creditotmp(){
      $idventa = $this->input->post("idventa");
      $fplazo = $this->input->post("fplazo");
      $dias = $this->input->post("dias");
      $interes = $this->input->post("interes");
      $mora = $this->input->post("mora");
      $cuotas = $this->input->post("cuotas");
      $abono = $this->input->post("abono");
      $mbc = $this->input->post("montobasecredito"); 
    //  print $mbc;
      $mic = $this->input->post("montointerescredito");
      $mc = $this->input->post("montocredito");
      $ffactura = $this->input->post("ffactura");
      $forpago = $this->input->post("forpago");
      $resu = $this->facturar_model->add_creditotmp($idventa, $fplazo, $dias, $interes, $mora, $cuotas, $abono, $mbc, $mic, $mc, $ffactura);


      if($forpago == "Contado"){ $fpvtc = 1; }
      if($forpago == "Credito"){ $fpvtc = 2; }      
      $selfp = $this->facturar_model->sumforpago($idventa, $fpvtc);
      $arr['monto'] = $selfp->monto;
      $arr['efectivo'] = $selfp->efectivo;
      print json_encode($arr);
    }

    /* SECCION DE FACTURACION EN PDF --------------------------------------------------------------------------------*/

    private function pagina_v() {
      $this->pdf_f->SetMargins('12', '7', '10');   #Margenes
      $this->pdf_f->AddPage('P', 'Letter');        #Orientación y tamaño 
    }

    private function pagina_h() {
      $this->pdf_f->SetMargins('12', '4', '10');   #Margenes
      $this->pdf_f->AddPage('L', 'Letter');        #Orientación y tamaño
    }
/*
    public function facturapdf(){
      $idfactura = $this->session->userdata("idfactura_tmp");;
      $cabfact = $this->facturar_model->datosfactura($idfactura);
      $piefact = $this->facturar_model->resfactura($idfactura);
      $emp = $this->Empresa_model->emp_get();      
      $params['cabfact'] = $cabfact;
      $params['piefact'] = $piefact;        
      // ENCABEZADO DEL PDF 
      $this->load->library('pdf_f', $params);
      $this->pdf_f->fontpath = 'font/'; 
      $this->pdf_f->AliasNbPages();
      $this->pagina_v();
      $this->pdf_f->SetFillColor(139, 35, 35);
      $this->pdf_f->SetFont('Arial','B',8);
      $this->pdf_f->SetTextColor(0,0,0);
      // TITULO DE DETALLES 
      $this->pdf_f->Line(12,110,196,110);
      $this->pdf_f->Cell(20,4,utf8_decode("Cantidad"),1,0,'C');
      $this->pdf_f->Cell(115,4,utf8_decode("Descripcion"),1,0,'L');
      $this->pdf_f->Cell(25,4,'Precio Unitario',1,0,'C');
      $this->pdf_f->Cell(25,4,'Subtotal',1,1,'R');
      // CICLO DE DETALLES DE FACTURA 
      $registro = $this->facturar_model->ventadetalle($idfactura);
      foreach ($registro as $row) {
        $strnombre = substr($row->pro_nombre,0,50);
        $strcant = number_format($row->cantidad,2);
        $precio=number_format($row->precio,2);
        $subtotal=number_format($row->subtotal,2);
        $this->pdf_f->SetFont('Arial','',8);        
        $this->pdf_f->Cell(20,5,utf8_decode("$strcant"),0,0,'C');
        $this->pdf_f->Cell(115,5,utf8_decode("$strnombre"),0,0,'L');
        $this->pdf_f->Cell(25,5,'$'.$precio,0,0,'C');
        $this->pdf_f->Cell(25,5,'$'.$subtotal,0,1,'R'); 
      }
      $this->pdf_f->Output('Factura','I'); 
    }   
*/

    public function facturapdf(){
      $habilitanotaventaiva = $this->Parametros_model->sel_habilitanotaventaiva(); 

      $idfactura = $this->session->userdata("idfactura_tmp");;
      $cabfact = $this->facturar_model->datosfactura($idfactura);
      $piefact = $this->facturar_model->resfactura($idfactura);
      $emp = $this->Empresa_model->emp_get();      
      $sucursal = $this->Sucursal_model->sel_suc_id($cabfact[0]->id_sucursal);      
      $params['cabfact'] = $cabfact;
      $params['piefact'] = $piefact;        
      // ENCABEZADO DEL PDF 
      $this->load->library('pdf_f', $params);
      $this->pdf_f->fontpath = 'font/'; 
      $this->pdf_f->AliasNbPages();
      $this->pagina_v();

      if ($sucursal->logo_sucursal){    
        $file_name = "ppp.jpg";
        $pic = base64_decode($sucursal->logo_sucursal);
        imagejpeg(imagecreatefromstring ( $pic ), $file_name);
        
        $this->pdf_f->Image($file_name,10,10,30,14);
      }  
      $this->pdf_f->Line(12,25,196,25);
      $this->pdf_f->SetFont('Arial','B',6);

      $this->pdf_f->SetXY(100,5);
      $this->pdf_f->Cell(20,10,utf8_decode($sucursal->nom_sucursal),0,0,'C');
      $this->pdf_f->SetXY(100,9);
      $this->pdf_f->Cell(20,10,utf8_decode($sucursal->dir_sucursal),0,0,'C');
      $this->pdf_f->SetXY(100,13);
      $this->pdf_f->Cell(20,10,utf8_decode($sucursal->telf_sucursal),0,0,'C');
      $this->pdf_f->SetXY(100,17);
      $this->pdf_f->Cell(20,10,utf8_decode($sucursal->mail_sucursal),0,0,'C');

/*
      $this->pdf_f->SetXY(100,8);
      $this->pdf_f->Cell(20,10,utf8_decode($sucursal->dir_sucursal),0,0,'C');
      $this->pdf_f->SetXY(100,13);
      $this->pdf_f->Cell(20,10,utf8_decode($sucursal->telf_sucursal),0,0,'C');
      $this->pdf_f->SetXY(100,18);
      $this->pdf_f->Cell(20,10,utf8_decode($sucursal->mail_sucursal),0,0,'C');
*/

      /*$this->pdf_f->Rect(165, 12, 30, 10, "D");*/
      $this->pdf_f->SetFont('Arial','B',12);        
      $tmptipo = ( $cabfact[0]->tipo_doc == 2) ? 'FACTURA' : 'NOTA DE VENTA';

      $this->pdf_f->SetXY(170,10);
      $this->pdf_f->Cell(20,10,$tmptipo);

/*      $this->pdf_f->text(167, 16, $tmptipo);*/
      $this->pdf_f->SetFont('Arial','B',9);
      $nrofact = $cabfact[0]->nro_factura;
      $this->pdf_f->text(170, 21, utf8_decode('Nº '.$nrofact));

      $this->pdf_f->SetFont('Arial','B',6);        

      $cliente = $cabfact[0]->nom_cliente;
      $idcliente = $cabfact[0]->nro_ident;
      $direccion = $cabfact[0]->dir_cliente; 
      $telf = $cabfact[0]->telf_cliente;
      $fec = $cabfact[0]->fecha;
      $fecha = str_replace('-', '/', $fec); 
      $fechaf = date("d/m/Y", strtotime($fecha));

      $this->pdf_f->SetFont('Arial','B',8);
      $this->pdf_f->ln(20); 
      $this->pdf_f->Cell(100,4,utf8_decode("Cliente: $cliente"),0,0,'L');
      $this->pdf_f->Cell(85,4,"Fecha: $fechaf",0,1,'R');
      if ($cabfact[0]->claveacceso == ''){
        $this->pdf_f->Cell(185,4,utf8_decode("C.I./R.U.C.: $idcliente"),0,1,'L');
      }
      else{  
        $this->pdf_f->Cell(70,4,utf8_decode("C.I./R.U.C.: $idcliente"),0,0,'L');
        $this->pdf_f->Cell(115,4,"Clave Acceso SRI: ".$cabfact[0]->claveacceso,0,1,'R');
      }
      $this->pdf_f->Cell(185,4,utf8_decode("Dirección: $direccion"),0,1,'L');
      $this->pdf_f->Cell(100,4,utf8_decode("Telefono: $telf"),0,1,'L');

      if (trim($cabfact[0]->observaciones) != ""){
        $this->pdf_f->ln(3); 
        $this->pdf_f->Cell(50,4,utf8_decode("Observaciones: "),0,1,'L');
        $this->pdf_f->SetFont('Arial','',8);        
        $this->pdf_f->MultiCell(185,5,utf8_decode($cabfact[0]->observaciones));   
      }  

      $this->pdf_f->ln(6); 

      $this->pdf_f->SetFillColor(139, 35, 35);
      $this->pdf_f->SetFont('Arial','B',8);
      $this->pdf_f->SetTextColor(0,0,0);
      // TITULO DE DETALLES 
      $descpro = $this->Parametros_model->sel_descpro();
      $tipodescuentoproducto = $this->Parametros_model->sel_tipodescuentoproducto();
      $tamanodescrip = 115;
      $tamanoprecio = 25;
      $tamanototal = 25;
      $tamanodescuento = 20;
      if ($descpro == 1){
        $tamanodescrip = 105;
        $tamanoprecio = 20;
        $tamanototal = 20;
      }

      $this->pdf_f->Cell(20,4,utf8_decode("Cantidad"),1,0,'C');
      $this->pdf_f->Cell($tamanodescrip,4,utf8_decode("Descripcion"),1,0,'L');
      $this->pdf_f->Cell($tamanoprecio,4,'Precio Unitario',1,0,'C');
      if ($descpro == 1){
        $strtipodescuentoproducto = '%Descuento';
        if ($tipodescuentoproducto == 0) { $strtipodescuentoproducto = 'Descuento'; }
        $this->pdf_f->Cell($tamanodescuento,4,$strtipodescuentoproducto,1,0,'C');
      }  
      $this->pdf_f->Cell($tamanototal,4,'Subtotal',1,1,'R');
      // CICLO DE DETALLES DE FACTURA 
      $registro = $this->facturar_model->ventadetalle($idfactura);
      foreach ($registro as $row) {
        $strnombre = $row->pro_nombre;//substr($row->pro_nombre,0,50);
        $strcant = number_format($row->cantidad,2);
        $precio=number_format($row->precio,2);
        $descuento=number_format($row->porcdesc,2);
        $subtotal=number_format($row->subtotal,2);

        $tmpY = $this->pdf_f->GetY();

        $this->pdf_f->SetFont('Arial','',8);        
        $this->pdf_f->Cell(20,5,utf8_decode("$strcant"),0,0,'C');
        //$this->pdf_f->Cell($tamanodescrip,5,utf8_decode("$strnombre"),0,0,'L');
        $this->pdf_f->MultiCell($tamanodescrip,5,utf8_decode("$strnombre"));
        $tmpYdetalle = $this->pdf_f->GetY();
        $this->pdf_f->SetXY(30+$tamanodescrip,$tmpY);

        $this->pdf_f->Cell($tamanoprecio,5,'$'.$precio,0,0,'C');
        if ($descpro == 1){
          if ($tipodescuentoproducto == 0) { 
            $descuento=number_format($row->descmonto,2); 
          }
          else{
            $descuento = '%'.$descuento; 
          }
          $this->pdf_f->Cell($tamanodescuento,5,$descuento,0,0,'C');
        }  
        $this->pdf_f->Cell($tamanototal,5,'$'.$subtotal,0,1,'R'); 
        $this->pdf_f->SetY($tmpYdetalle);
      }

      $subtotaliva = $piefact->subconiva;
      $subtotalcero = $piefact->subsiniva;
      $subtotaldiva = $piefact->descsubconiva;
      $subtotaldcero = $piefact->descsubsiniva;
      $montoiva = $piefact->montoiva;
      $descuento = $piefact->subconiva + $piefact->subsiniva - $piefact->descsubconiva - $piefact->descsubsiniva;
/*      $descuento = $piefact->desc_monto;*/
      $total = $piefact->montototal;
      $tipodoc = $piefact->tipo_doc;
      $subtotal = $subtotalcero + $subtotaliva;

    //  $this->pdf_f->SetY(-0.1);
      $this->pdf_f->SetFont('Arial','B',8);
      $tmpy = $this->pdf_f->GetY();
      $tmpy += 10;
      $tmpysubtotal = $tmpy;
      if(($tipodoc == 2) || ($habilitanotaventaiva == 1)){
        
        $this->pdf_f->SetXY(101,$tmpy);
        $this->pdf_f->Cell(70,-4,utf8_decode("Subtotal IVA (0 %)"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$'.$subtotalcero),0,1,'R');

        $tmpy +=5;
        $this->pdf_f->SetXY(1,$tmpy);
        $this->pdf_f->Cell(170,-4,utf8_decode("Subtotal IVA (15 %)"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$ '.$subtotaliva),0,1,'R');

        $tmpy +=5;
        $this->pdf_f->SetXY(1,$tmpy);
        $this->pdf_f->Cell(170,-4,utf8_decode("Descuento"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$'.$descuento),0,1,'R');

        $tmpy +=5;
        $this->pdf_f->SetXY(1,$tmpy);
        $this->pdf_f->Cell(170,-4,utf8_decode("Subtotal con Descuento IVA (0 %)"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$'.$subtotaldcero),0,1,'R');

        $tmpy +=5;
        $this->pdf_f->SetXY(1,$tmpy);
        $this->pdf_f->Cell(170,-4,utf8_decode("Subtotal con Descuento IVA (15 %)"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$'.$subtotaldiva),0,1,'R');

        $tmpy +=5;
        $this->pdf_f->SetXY(1,$tmpy);
        $this->pdf_f->Cell(170,-4,utf8_decode("IVA (15%)"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$'.$montoiva),0,1,'R');

        $tmpy +=5;
        $this->pdf_f->SetXY(1,$tmpy);
        $this->pdf_f->Cell(170,-4,utf8_decode("Total"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$'.$total),0,1,'R');

        if ($cabfact[0]->id_tipcancelacion != null){
          $this->pdf_f->SetXY(10,$tmpysubtotal);
          $tipocancelacion = ($cabfact[0]->id_tipcancelacion == 1) ? 'Contado' : 'Crédito';
          $this->pdf_f->Cell(70,4,utf8_decode("Tipo Cancelación: $tipocancelacion"),0,0,'L');
        }      
        $formapago = $this->facturar_model->idventa_formapago($idfactura);
        if (count($formapago) > 0){
          $tmpysubtotal +=10;
          $this->pdf_f->SetXY(10,$tmpysubtotal);
          $this->pdf_f->Cell(70,4,utf8_decode("Forma de Pago:"),0,0,'L');
          foreach ($formapago as $pago) {
            $tmpysubtotal +=5;
            $this->pdf_f->SetXY(10,$tmpysubtotal);
            $this->pdf_f->Cell(40,4,utf8_decode($pago->nombre_formapago),1,0,'L');
            $this->pdf_f->Cell(20,4,utf8_decode('$'.$pago->monto),1,0,'R');
          }
        }  
      }else{
        $this->pdf_f->Ln(3);
        $this->pdf_f->Cell(160,4,utf8_decode("Subtotal"),0,0,'R');
        $this->pdf_f->Cell(25,4,utf8_decode('$'.number_format($subtotal,2)),0,1,'R');

        $this->pdf_f->Cell(160,4,utf8_decode("Descuento"),0,0,'R');
        $this->pdf_f->Cell(25,4,utf8_decode('$'.number_format($descuento,2)),0,1,'R');

        $this->pdf_f->Cell(160,4,utf8_decode("Total"),0,0,'R');
        $this->pdf_f->Cell(25,4,utf8_decode('$'.number_format($total,2)),0,1,'R');

      }

 


      $this->pdf_f->Output('Factura','I'); 
    } 


    

    public function facturapdf_Quitoled(){
      $idfactura = $this->session->userdata("idfactura_tmp");;
      $cabfact = $this->facturar_model->datosfactura($idfactura);
      $piefact = $this->facturar_model->resfactura($idfactura);
      $emp = $this->Empresa_model->emp_get();      
      $params['cabfact'] = $cabfact;
      $params['piefact'] = $piefact;        
      // ENCABEZADO DEL PDF 
      $this->load->library('pdf_f', $params);
      $this->pdf_f->fontpath = 'font/'; 
      $this->pdf_f->AliasNbPages();
      $this->pagina_v();

      $this->pdf_f->Image('public/img/quitoledbn.jpg',10,10,50,14);
      $this->pdf_f->Line(12,25,196,25);
      $this->pdf_f->SetFont('Arial','B',6);

      $this->pdf_f->text(80, 15, utf8_decode('AV. COLÓN OEE1-80 Y 10 DE AGOSTO'));
      $this->pdf_f->text(75, 18, utf8_decode('Telfs: 02 2565 354 - 0990 046 742 * Quito - Ecuador'));
      $this->pdf_f->text(89, 21, utf8_decode('quitoled@hotmail.com'));
      $this->pdf_f->text(92, 24, utf8_decode('www.quitoled.ec'));

      $this->pdf_f->Rect(165, 12, 30, 10, "D");
      $this->pdf_f->SetFont('Arial','B',12);        
      $this->pdf_f->text(167, 16, 'NOTA VENTA');
      $this->pdf_f->SetFont('Arial','B',9);
      $nrofact = $cabfact[0]->nro_factura;
      $this->pdf_f->text(170, 21, utf8_decode('Nº '.$nrofact));

      $this->pdf_f->SetFont('Arial','B',6);        

      $cliente = $cabfact[0]->nom_cliente;
      $idcliente = $cabfact[0]->nro_ident;
      $direccion = $cabfact[0]->dir_cliente; 
      $telf = $cabfact[0]->telf_cliente;
      $fec = $cabfact[0]->fecha;
      $fecha = str_replace('-', '/', $fec); 
      $fechaf = date("d/m/Y", strtotime($fecha));

      $this->pdf_f->SetFont('Arial','B',8);
      $this->pdf_f->ln(20); 
      $this->pdf_f->Cell(100,4,utf8_decode("Cliente: $cliente"),0,0,'L');
      $this->pdf_f->Cell(85,4,"Fecha: $fechaf",0,1,'R');
      $this->pdf_f->Cell(185,4,utf8_decode("C.I./R.U.C.: $idcliente"),0,1,'L');
      $this->pdf_f->Cell(185,4,utf8_decode("Dirección: $direccion"),0,1,'L');
      $this->pdf_f->Cell(100,4,utf8_decode("Telefono: $telf"),0,1,'L');

      $this->pdf_f->ln(6); 

      $this->pdf_f->SetFillColor(139, 35, 35);
      $this->pdf_f->SetFont('Arial','B',8);
      $this->pdf_f->SetTextColor(0,0,0);
      // TITULO DE DETALLES 
      $this->pdf_f->Cell(20,4,utf8_decode("Cantidad"),1,0,'C');
      $this->pdf_f->Cell(115,4,utf8_decode("Descripcion"),1,0,'L');
      $this->pdf_f->Cell(25,4,'Precio Unitario',1,0,'C');
      $this->pdf_f->Cell(25,4,'Subtotal',1,1,'R');
      // CICLO DE DETALLES DE FACTURA 
      $registro = $this->facturar_model->ventadetalle($idfactura);
      foreach ($registro as $row) {
        $strnombre = substr($row->pro_nombre,0,50);
        $strcant = number_format($row->cantidad,2);
        $precio=number_format($row->precio,2);
        $subtotal=number_format($row->subtotal,2);
        $this->pdf_f->SetFont('Arial','',8);        
        $this->pdf_f->Cell(20,5,utf8_decode("$strcant"),0,0,'C');
        $this->pdf_f->Cell(115,5,utf8_decode("$strnombre"),0,0,'L');
        $this->pdf_f->Cell(25,5,'$'.$precio,0,0,'C');
        $this->pdf_f->Cell(25,5,'$'.$subtotal,0,1,'R'); 
      }

      $subtotaliva = $piefact->subconiva;
      $subtotalcero = $piefact->subsiniva;
      $subtotaldiva = $piefact->descsubconiva;
      $subtotaldcero = $piefact->descsubsiniva;
      $montoiva = $piefact->montoiva;
      $descuento = $piefact->desc_monto;
      $total = $piefact->montototal;
      $tipodoc = $piefact->tipo_doc;
      $subtotal = $subtotalcero + $subtotaliva;

    //  $this->pdf_f->SetY(-0.1);
      $this->pdf_f->SetFont('Arial','B',8);

      if($tipodoc == 2){
        /*
        $this->pdf_f->Ln(-10);
        $this->pdf_f->Cell(160,-4,utf8_decode("Total"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$'.$total),0,1,'R');

        $this->pdf_f->Cell(160,-4,utf8_decode("IVA (15%)"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$'.$montoiva),0,1,'R');

        $this->pdf_f->Cell(160,-4,utf8_decode("Subtotal con Descuento IVA (0 %)"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$'.$subtotaldcero),0,1,'R');

        $this->pdf_f->Cell(160,-4,utf8_decode("Subtotal con Descuento IVA (15 %)"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$'.$subtotaldiva),0,1,'R');

        $this->pdf_f->Cell(160,-4,utf8_decode("Descuento"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$'.$descuento),0,1,'R');

        $this->pdf_f->Cell(160,-4,utf8_decode("Subtotal IVA (0 %)"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$'.$subtotalcero),0,1,'R');

        $this->pdf_f->Cell(160,-4,utf8_decode("Subtotal IVA (15 %)"),0,0,'R');
        $this->pdf_f->Cell(25,-4,utf8_decode('$ '.$subtotaliva),0,1,'R');
        */
      }else{
        $this->pdf_f->Ln(10);
        $this->pdf_f->Cell(160,4,utf8_decode("Subtotal"),0,0,'R');
        $this->pdf_f->Cell(25,4,utf8_decode('$'.number_format($subtotal,2)),0,1,'R');

        $this->pdf_f->Cell(160,4,utf8_decode("Descuento"),0,0,'R');
        $this->pdf_f->Cell(25,4,utf8_decode('$'.$descuento),0,1,'R');

        $this->pdf_f->Cell(160,4,utf8_decode("Total"),0,0,'R');
        $this->pdf_f->Cell(25,4,utf8_decode('$'.$total),0,1,'R');

      }

      $this->pdf_f->ln(10); 

      $this->pdf_f->Cell(50,0,'',1,0,'L');
      $this->pdf_f->Cell(80,0,'',0,0,'L');
      $this->pdf_f->Cell(50,0,'',1,1,'L'); 

      $this->pdf_f->Cell(50,4,utf8_decode("Entrega: "),0,0,'L');
      $this->pdf_f->Cell(80,0,'',0,0,'L');        
      $this->pdf_f->Cell(50,4,utf8_decode("Recibe: "),0,1,'L');       

      $this->pdf_f->Line(12,140,196,140);
      $this->pdf_f->Image('public/img/quitoledbn.jpg',10,145,50,14);
      $this->pdf_f->SetFont('Arial','B',6);
      $this->pdf_f->text(80, 150, utf8_decode('AV. COLÓN OEE1-80 Y 10 DE AGOSTO'));
      $this->pdf_f->text(75, 153, utf8_decode('Telfs: 02 2565 354 - 0990 046 742 * Quito - Ecuador'));
      $this->pdf_f->text(89, 156, utf8_decode('quitoled@hotmail.com'));
      $this->pdf_f->text(92, 159, utf8_decode('www.quitoled.ec'));

      $this->pdf_f->Rect(165, 147, 30, 10, "D");
      $this->pdf_f->SetFont('Arial','B',12);        
      $this->pdf_f->text(167, 151, 'NOTA VENTA');
      $this->pdf_f->SetFont('Arial','B',9);
      $nrofact = $cabfact[0]->nro_factura;
      $this->pdf_f->text(170, 156, utf8_decode('Nº '.$nrofact));
      $this->pdf_f->Line(12,160,196,160);
      $this->pdf_f->SetFont('Arial','B',6);        

      $cliente = $cabfact[0]->nom_cliente;
      $idcliente = $cabfact[0]->nro_ident;
      $direccion = $cabfact[0]->dir_cliente; 
      $telf = $cabfact[0]->telf_cliente;
      $fec = $cabfact[0]->fecha;
      $fecha = str_replace('-', '/', $fec); 
      $fechaf = date("d/m/Y", strtotime($fecha));
/*
        if($dpi->pro_imagen == null){
          $this->pdf_p->Cell(30,30, $this->pdf_p->Image(base_url().'public/img/quitoled.jpg', $this->pdf_p->GetX(), $this->pdf_p->GetY(), 30, 30, 'jpg'),1);
        } else{
          $this->pdf_p->Cell(30,30, $this->pdf_p->Image($pic, $this->pdf_p->GetX(), $this->pdf_p->GetY(), 30, 30, 'jpg'),1); 
        } 
*/
      $this->pdf_f->SetY(165);


      $this->pdf_f->SetFont('Arial','B',8);
  //    $this->pdf_f->ln(80); 
      $this->pdf_f->Cell(100,4,utf8_decode("Cliente: $cliente"),0,0,'L');
      $this->pdf_f->Cell(85,4,"Fecha: $fechaf",0,1,'R');
      $this->pdf_f->Cell(185,4,utf8_decode("C.I./R.U.C.: $idcliente"),0,1,'L');
      $this->pdf_f->Cell(185,4,utf8_decode("Dirección: $direccion"),0,1,'L');
      $this->pdf_f->Cell(100,4,utf8_decode("Telefono: $telf"),0,1,'L');

      $this->pdf_f->ln(6); 

   $this->pdf_f->SetFillColor(139, 35, 35);
      $this->pdf_f->SetFont('Arial','B',8);
      $this->pdf_f->SetTextColor(0,0,0);
      // TITULO DE DETALLES 
      $this->pdf_f->Cell(20,4,utf8_decode("Cantidad"),1,0,'C');
      $this->pdf_f->Cell(115,4,utf8_decode("Descripcion"),1,0,'L');
      $this->pdf_f->Cell(25,4,'Precio Unitario',1,0,'C');
      $this->pdf_f->Cell(25,4,'Subtotal',1,1,'R');
      // CICLO DE DETALLES DE FACTURA 
      $registro = $this->facturar_model->ventadetalle($idfactura);
      foreach ($registro as $row) {
        $strnombre = substr($row->pro_nombre,0,50);
        $strcant = number_format($row->cantidad,2);
        $precio=number_format($row->precio,2);
        $subtotal=number_format($row->subtotal,2);
        $this->pdf_f->SetFont('Arial','',8);        
        $this->pdf_f->Cell(20,5,utf8_decode("$strcant"),0,0,'C');
        $this->pdf_f->Cell(115,5,utf8_decode("$strnombre"),0,0,'L');
        $this->pdf_f->Cell(25,5,'$'.$precio,0,0,'C');
        $this->pdf_f->Cell(25,5,'$'.$subtotal,0,1,'R'); 
      }

      $subtotaliva = $piefact->subconiva;
      $subtotalcero = $piefact->subsiniva;
      $subtotaldiva = $piefact->descsubconiva;
      $subtotaldcero = $piefact->descsubsiniva;
      $montoiva = $piefact->montoiva;
      $descuento = $piefact->desc_monto;
      $total = $piefact->montototal;
      $tipodoc = $piefact->tipo_doc;
      $subtotal = $subtotalcero + $subtotaliva;

    //  $this->pdf_f->SetY(-0.1);
      $this->pdf_f->SetFont('Arial','B',8);


      $this->pdf_f->Ln(15);
      $this->pdf_f->Cell(160,4,utf8_decode("Subtotal"),0,0,'R');
      $this->pdf_f->Cell(25,4,utf8_decode('$'.number_format($subtotal,2)),0,1,'R');

      $this->pdf_f->Cell(160,4,utf8_decode("Descuento"),0,0,'R');
      $this->pdf_f->Cell(25,4,utf8_decode('$'.$descuento),0,1,'R');

      $this->pdf_f->Cell(160,4,utf8_decode("Total"),0,0,'R');
      $this->pdf_f->Cell(25,4,utf8_decode('$'.$total),0,1,'R');

      $this->pdf_f->ln(10); 

      $this->pdf_f->Cell(50,0,'',1,0,'L');
      $this->pdf_f->Cell(80,0,'',0,0,'L');
      $this->pdf_f->Cell(50,0,'',1,1,'L'); 

      $this->pdf_f->Cell(50,4,utf8_decode("Entrega: "),0,0,'L');
      $this->pdf_f->Cell(80,0,'',0,0,'L');        
      $this->pdf_f->Cell(50,4,utf8_decode("Recibe: "),0,1,'L');         







      $this->pdf_f->Output('Factura','I'); 
    } 









    public function upd_ventnrodoc(){
      $idforma = $this->input->post('idforma');
      $idcaja = $this->input->post('idcaja');
      $nrodoc = $this->input->post('nrodoc');
      $observaciones = $this->input->post('observaciones');
      $fecha = $this->input->post('fecha');
      $fecha = str_replace('/', '-', $fecha); 
      $fecha = date("Y-m-d", strtotime($fecha));
      $idusu = $this->session->userdata("sess_id");
      $this->facturar_model->upd_ventnrodoc($idforma, $idcaja, $nrodoc, $idusu, $observaciones, $fecha);
      print json_encode($idforma);

    }

    public function tipdoc(){
      $idventa = $this->input->post('id');
      $res = $this->facturar_model->idtipodoc($idventa);
      print json_encode($res);      
    }


    /* TEMPORAL PARA LAS RETENCIONES DE LAS VEntas */
    public function temp_ventaret() {
        $this->session->unset_userdata("temp_idventa"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("temp_idventa", NULL);
        if ($id != NULL) { $this->session->set_userdata("temp_idventa", $id); } 
        else { $this->session->set_userdata("temp_idventa", NULL); }
        $arr['resu'] = $id;
        print json_encode($arr);
    }    

    public function venta_retencion(){
        $idventa = $this->session->userdata("temp_idventa");
        $this->Retencion_model->retencionventa_defaultadd($idventa);
        $this->Retencion_model->retencionventa_cargardetalletmp($idventa);
        $comp = $this->facturar_model->selventaret($idventa);
        $data["comp"] = $comp;
        $data["base_url"] = base_url();
        $data["content"] = "venta_retencion";
        $this->load->view("layout", $data);           
    }
          
    public function listadoRet() {
        $idcomp = $this->session->userdata("temp_idventa");
        $registro = $this->Retencion_model->lst_retenciondettmp_venta();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Editar Retencion\" id=\"'.$row->id_detallerenta.'\" class=\"btn btn-success btn-xs btn-grad ret_upd\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_detallerenta.'\" name=\"'.substr(addslashes($row->concepto), 0, 40).'\" class=\"btn btn-danger btn-xs btn-grad ret_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{  "concepto":"' .substr(addslashes($row->concepto), 0, 40). '",
                        "basenoiva":"' .$row->base_noiva. '",
                        "baseiva":"' .$row->base_iva. '",
                        "por100retrenta":"' .$row->porciento_retencion_renta. '",
                        "valorretrenta":"' .$row->valor_retencion_renta. '",
                        "ver":"'.$ver.'"
                    },';

        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function add_retencion(){
        $idcomp = $this->session->userdata("temp_idventa");
        $lstret = $this->Retencion_model->ret_lst_comp();
        $lstretiva = $this->Retencion_model->retiva_lst_porciento();
        $subtotalconiva = $this->input->post("subtotalconiva");
        $subtotalsiniva = $this->input->post("subtotalsiniva");
        $lst_subtotaldisp = $this->Retencion_model->lst_subtotalretdisp_venta(0);
        if ($lst_subtotaldisp){
            $subtotalconiva -= $lst_subtotaldisp[0]->baseiva_disp;
            $subtotalsiniva -= $lst_subtotaldisp[0]->basenoiva_disp;
        }
      
        $data["lstret"] = $lstret;        
        $data["lstretiva"] = $lstretiva; 
        $data["subtotalconiva"] = $subtotalconiva;        
        $data["subtotalsiniva"] = $subtotalsiniva;        
        $data["base_url"] = base_url();
        $this->load->view("venta_retencion_add", $data);
    }

    public function editar_retencion(){
        $idcomp = $this->session->userdata("temp_idventa");
        $id = $this->input->post("id");
        $subtotalconiva = $this->input->post("subtotalconiva");
        $subtotalsiniva = $this->input->post("subtotalsiniva");
        $lst_subtotaldisp = $this->Retencion_model->lst_subtotalretdisp_venta($id);
        if ($lst_subtotaldisp){
            $subtotalconiva -= $lst_subtotaldisp[0]->baseiva_disp;
            $subtotalsiniva -= $lst_subtotaldisp[0]->basenoiva_disp;
        }
        $lstret = $this->Retencion_model->ret_lst_comp();
        $lstretiva = $this->Retencion_model->retiva_lst_porciento();
        $retencion = $this->Retencion_model->sel_detalleretencionventa($id);
        $data["lstret"] = $lstret;        
        $data["lstretiva"] = $lstretiva;        
        $data["retencion"] = $retencion;        
        $data["subtotalconiva"] = $subtotalconiva;        
        $data["subtotalsiniva"] = $subtotalsiniva;        
        $data["base_url"] = base_url();
        $this->load->view("venta_retencion_add", $data);
    }

    /* GUARDAR RETENCION DE COMPRA*/
    public function guardar_detalleretencion(){       
        /*$idretcompra = $this->session->userdata("txt_id_comp_ret");*/
        $id_ret = $this->input->post('txt_idret');        
        $concepto = $this->input->post('cmb_tip_ide');
        $basenoiva = $this->input->post('txt_basenoiva');
        $baseiva = $this->input->post('txt_baseiva');
        $por100retrenta = $this->input->post('txt_p100retrenta');
        $valorretrenta = $this->input->post('txt_valorrenta');

        if ($id_ret == 0){
            $this->Retencion_model->retencionrentaventa_add($concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta);
        } else{
            $this->Retencion_model->retencionrentaventa_upd($id_ret, $concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta);
        }
        
        $tmpretenido = $this->Retencion_model->retencionrentaventa_tmpretenido();

        print json_encode($tmpretenido);
    } 

    /* GUARDAR RETENCION DE COMPRA*/
    public function guardar_retencion(){
        
        /*$idcompra = $this->session->userdata("temp_idcomp");*/
        $idretventa = $this->input->post('txt_id_comp_ret');
        /*$id_ret = $this->input->post('txt_idret');*/
        $nroretencion = $this->input->post('txt_factura');
        $autorizacion = $this->input->post('txt_autorizacion');

        $fec = $this->input->post('fecha_ret');
        $fecha = str_replace('/', '-', $fec); 
        $fecha = date("Y-m-d", strtotime($fecha));

        $retiva10 = $this->input->post('txt_retiva10');
        $retiva20 = $this->input->post('txt_retiva20');
        $retiva30 = $this->input->post('txt_retiva30');
        $retiva50 = $this->input->post('txt_retiva50');
        $retiva70 = $this->input->post('txt_retiva70');
        $retiva100 = $this->input->post('txt_retiva100');

        $this->Retencion_model->retencionventa_guardar($idretventa, $nroretencion, $autorizacion, $fecha, $retiva10, $retiva20, $retiva30, $retiva50, $retiva70, $retiva100);
        
        print json_encode(1);
    } 

    public function eliminar_retencion(){
        $id = $this->input->post("id");
        $this->Retencion_model->retencionventa_del($id);
        print json_encode(1);
    }

    public function eliminar_retencionrenta(){
        $id = $this->input->post("id");
        $this->Retencion_model->retencionrentaventa_del($id);

        $tmpretenido = $this->Retencion_model->retencionrentaventa_tmpretenido();

        print json_encode($tmpretenido);
    }

    public function ins_detalleventatmpserie(){
      $idusu = $this->session->userdata("sess_id");
      $serie = $this->input->post("serie");
      $idalm = $this->input->post("idalm");
      $resu = $this->facturar_model->ins_detalleventatmpserie($idusu, $serie, $idalm);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function chk_estadoserie(){
      $serie = $this->input->post("serie");
      $resu = $this->facturar_model->chk_estadoserie($serie);
      $arr['resu'] = $resu;
      print json_encode($arr);
    }

    public function select_serieimei(){
        $idpro = $this->input->post("id");
        $iddet = $this->input->post("iddet");
        $arr['idpro'] = $idpro;
        $arr['iddet'] = $iddet;        
        $this->session->unset_userdata("tmp_serieimei"); 
        $this->session->set_userdata("tmp_serieimei", NULL);
        if ($arr != NULL) { $this->session->set_userdata("tmp_serieimei", $arr); } 
        else { $this->session->set_userdata("tmp_serieimei", NULL); }        
        $data["base_url"] = base_url();
        $this->load->view("facturar_serieimei_select", $data);
    }


    public function add_serieimei(){
        $idpro = $this->input->post("id");
        $iddet = $this->input->post("iddet");
        $arr['idpro'] = $idpro;
        $arr['iddet'] = $iddet;        
        $this->session->unset_userdata("tmp_serieimei"); 
        $this->session->set_userdata("tmp_serieimei", NULL);
        if ($arr != NULL) { $this->session->set_userdata("tmp_serieimei", $arr); } 
        else { $this->session->set_userdata("tmp_serieimei", NULL); }        
        $data["base_url"] = base_url();
        $this->load->view("facturar_serieimei", $data);
    }

    public function lstProSerieImeiDisponible() {
      $arr = $this->session->userdata("tmp_serieimei");
      $iddetalle = $arr['iddet'];
      $idpro = $arr['idpro'];
      $registro = $this->facturar_model->lst_imeiserie_disponible($iddetalle, $idpro);
      $tabla = "";
      foreach ($registro as $row) {
          $tmpserie = ($row->seleccionado == 1) ? 'checked' : '';
          $ver = '<div ><input type=\"checkbox\" class=\"chk_serie\" name=\"'.$row->id_serie.'\" id=\"'.$row->id_serie.'\" value=\"1\" '. $tmpserie .' ></div>';

          $tabla.='{"numeroserie":"' . $row->numeroserie . '",
                    "descripcion":"' . $row->descripcion . '",
                    "ver":"'.$ver.'"},';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';

    } 

    public function actualiza_detalle_serie(){
      $arr = $this->session->userdata("tmp_serieimei");
      $iddet = $arr['iddet'];
      $idserie = $this->input->post("id");
      $inserta = $this->input->post("inserta");
      $arr = $this->facturar_model->actualiza_detalle_serie($iddet, $idserie, $inserta);
      print json_encode($arr);
    }

    public function lstProSerieImei() {
      $arr = $this->session->userdata("tmp_serieimei");
      $idpro = $arr['idpro'];
      $registro = $this->facturar_model->valimeiserie($idpro);
      $tabla = "";
      foreach ($registro as $row) {
          $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Añadir\" id=\"'.$row->id_serie.'\" class=\"btn btn-success btn-xs btn-grad addimaiserie\"><i class=\"fa fa-plus\"></i></a>  </div>';
          $tabla.='{"numeroserie":"' . $row->numeroserie . '",
                    "descripcion":"' . $row->descripcion . '",
                    "ver":"'.$ver.'"},';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';

    } 

    public function add_imeiserietmp(){
      $arr = $this->session->userdata("tmp_serieimei");
      $iddet = $arr['iddet'];
      $idserie = $this->input->post("idserie");
      $this->facturar_model->upd_imeiserietmp($iddet, $idserie);
      $arr = 1;
      print json_encode($arr);
    }

/*
    public function imprimirventa(){
        $factoriva = 15;
        date_default_timezone_set("America/Guayaquil");
        $idfactura = $this->input->post('id');
        $registro = $this->facturar_model->datosfactura($idfactura);
        $emp = $this->Empresa_model->emp_get();
        $tabla = "";
        $tabla.= " ". "\r\n"."\r\n"."\r\n"."\r\n"."\r\n"."\r\n";
        foreach ($registro as $row) {
          $strdate = str_replace('-', '/', $row->fecharegistro); 
          $strdate = date("d/m/Y H:i", strtotime($strdate)); 
          for ($i=0; $i <= 24; $i++) { 
            $tabla.= chr(32);
          }
          $tabla.= $row->nom_cliente . "\r\n";    

          for ($i=0; $i <= 14; $i++) { 
            $tabla.= chr(32);
          }
          $tabla.= $row->nro_ident; 

          $tmpsize = strlen($row->nro_ident);
          if($tmpsize < 55){
            $dife = 55 - $tmpsize;
            for ($i=0; $i <= $dife; $i++) { 
              $tabla.= chr(32);
            }               
          }          
          $tabla.= $row->telf_cliente . "\r\n";


          for ($i=0; $i <= 12; $i++) { 
            $tabla.= chr(32);
          }
          $tabla.= substr($row->dir_cliente,0,60);   

          $tmpsize = strlen($row->dir_cliente);
          if($tmpsize < 60){
            $dife = 60 - $tmpsize;
            for ($i=0; $i <= $dife; $i++) { 
              $tabla.= chr(32);
            }               
          }          
          $tabla.= $strdate . "\r\n";
        }   
        
        $tabla.= "\r\n";
        $cantidadproducto=0;
        $limiteprodventa = $this->Parametros_model->sel_limiteprodventa();
        $venta = $this->facturar_model->ventadetalle($idfactura);

        foreach ($venta as $item) {
            $cantidadproducto++;
            $lcant = strlen(number_format($item->cantidad,2));
            for ($i=1; $i <= 10-$lcant; $i++) { 
              $tabla.= chr(32);
            }
            $tabla.= number_format($item->cantidad,2);
            for ($i=0; $i <= 5; $i++) { 
              $tabla.= chr(32);
            }

            // PRODUCTO 
            $lpro = 0;
            $dife = 0;
            $lcant = 50;
            $producto = strtoupper($item->pro_nombre);
            $pro = substr($producto,0,41);
            $lpro = strlen($pro);
            $dife = $lcant - $lpro;
            $tabla.= $pro;
              for ($i=0; $i <= $dife; $i++) { 
                $tabla.= chr(32);
              } 

            // PRECIO 
            $lpre = strlen(number_format($item->precio,2));
            $dife = 0;
            if($lpre < 7){
              $dife = 7 - $lpre;
              for ($i=0; $i <= $dife; $i++) { 
                $tabla.= chr(32);
              }               
            }
            $tabla.= number_format($item->precio,2);

            // SUBTOTAL 
            $lsub = strlen(number_format($item->subtotal,2));
            $difs = 0;
            if($lsub < 9){
              $difs = 9 - $lsub;
              for ($i=0; $i <= $difs; $i++) { 
                $tabla.= chr(32);
              }               
            }
            $tabla.= number_format($item->subtotal,2);
            $tabla.= " ". "\r\n";
            
            if($item->numeroserie != null){
              $serie = strtoupper($item->numeroserie);
              $esp = 15;
              for ($i=0; $i <= $esp; $i++) { 
                  $tabla.= chr(32);
                } 
              $tabla.= "Serial/Imei: ".$serie." ". "\r\n";              
            }


        }

        if(($limiteprodventa > 0) & ($cantidadproducto < $limiteprodventa)){
          for ($i=1; $i <= ($limiteprodventa - $cantidadproducto); $i++) { $tabla.= " ". "\r\n"; }
        }
        
        

        $detfact = $this->facturar_model->resfactura($idfactura);
        $tipodoc = $detfact->tipo_doc;
        $subtotaliva = $detfact->subconiva;
        $subtotalcero = $detfact->subsiniva;
        $subtotaldiva = $detfact->descsubconiva;
        $subtotaldcero = $detfact->descsubsiniva;
        $montoiva = $detfact->montoiva;
        $descuento = $detfact->desc_monto;
        $total = $detfact->montototal;
        $tipodoc = $detfact->tipo_doc;
        $subtotal = $subtotalcero + $subtotaliva;        

        $tabla.= " ". "\r\n";

        $ltop = strlen(number_format($subtotaliva,2));
        if (strlen(number_format($subtotalcero,2)) > $ltop) $ltop = strlen(number_format($subtotalcero,2));
        if (strlen(number_format($total,2)) > $ltop) $ltop = strlen(number_format($total,2));

        for ($i=0; $i <= 75; $i++) { 
          $tabla.= chr(32);
        }  
        // Subtotal IVA (15 %) 
        $vsubiva = "       Subtotal IVA (15%):";
       

        $ltop = $ltop + 1;
        
        $lsi= strlen(number_format($subtotaldiva + $subtotaldcero,2));
        $dife = 0;
        if($lsi < $ltop){
          $dife = $ltop - $lsi;
          for ($i=0; $i <= $dife; $i++) { 
            $tabla.= chr(32);
          }               
        }
        $tabla.= number_format($subtotaldiva + $subtotaldcero,2). "\r\n";

        // IVA (15%) 
        for ($i=0; $i <= 75; $i++) { 
          $tabla.= chr(32);
        } 

        $lsc= strlen(number_format($montoiva,2));
        $di = 0;
        if($lsc < $ltop){
          $di = $ltop - $lsc;
          for ($i=0; $i <= $di; $i++) { 
            $tabla.= chr(32);
          }               
        }
        $tabla.= number_format($montoiva,2). "\r\n";

        // Total 
        for ($i=0; $i <= 75; $i++) { 
          $tabla.= chr(32);
        } 

        $lsc= strlen(number_format($total,2));
        $di = 0;
        if($lsc < $ltop){
          $di = $ltop - $lsc;
          for ($i=0; $i <= $di; $i++) { 
            $tabla.= chr(32);
          }               
        }
        $tabla.= number_format($total,2). "\r\n";


        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("factura_imprimir", $data);

    }
*/
    public function listadoDataProVent() {
        date_default_timezone_set("America/Guayaquil");
        $desde = $this->session->userdata("tmp_mven_desde");
        $hasta = $this->session->userdata("tmp_mven_hasta");

        $registro = $this->facturar_model->productosmasvendidosrango($desde, $hasta); /* bg-orange-active color-palette */

        $tabla = "";
        foreach ($registro as $row) {
          $tabla.='{"codbar":"' . $row->pro_codigobarra . '",
                    "codaux":"' . $row->pro_codigoauxiliar . '",
                    "producto":"' . $row->pro_nombre . '",
                    "precio":"' . $row->pro_precioventa . '",
                    "cantidad":"' . $row->cantidadtotal . '",
                    "total":"' . $row->total . '",   
                    "categoria":"'.$row->cat_descripcion.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    public function tmp_mventa_fecha() {
      $this->session->unset_userdata("tmp_mven_desde"); 
      $this->session->unset_userdata("tmp_mven_hasta");
      $horad = $this->input->post("horad");
      $horah = $this->input->post("horah");
      $fecdesde = $this->input->post("fdesde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      $fechasta = $this->input->post("fhasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));
      $vdesde = $desde." ".$horad;
      $vhasta = $hasta." ".$horah;        
      $this->session->set_userdata("tmp_mven_desde", NULL);
      if ($vdesde != NULL) { $this->session->set_userdata("tmp_mven_desde", $vdesde);} 
      else { $this->session->set_userdata("tmp_mven_desde", NULL); }
      $this->session->set_userdata("tmp_mven_hasta", NULL);
      if ($vhasta != NULL) { $this->session->set_userdata("tmp_mven_hasta", $vhasta);} 
      else { $this->session->set_userdata("tmp_mven_hasta", NULL);}
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function upd_monto(){
      $desde = $this->session->userdata("tmp_mven_desde");
      $hasta = $this->session->userdata("tmp_mven_hasta");
      $monto = $this->facturar_model->montomasvendidosrango($desde, $hasta);;
      $arr = $monto;
      print json_encode($arr);               
    }

    public function imprimirventa(){
        $factoriva = 15;
        date_default_timezone_set("America/Guayaquil");
        $idfactura = $this->input->post('id');
        $registro = $this->facturar_model->datosfactura($idfactura);
        $emp = $this->Empresa_model->emp_get();
        $tabla = "";
        $tabla.= " ". "\r\n";
        foreach ($registro as $row) {
          $strdate = str_replace('-', '/', $row->fecharegistro); 
          $strdate = date("d/m/Y H:m", strtotime($strdate)); 
          $tabla.=" Cliente:" . chr(32) . $row->nom_cliente . "\r\n";        
          $tabla.=" Direccion:". chr(32) . $row->dir_cliente . "\r\n";        
          $tabla.=" CI/RUC:". chr(32) . $row->nro_ident . "\r\n";        
          $tabla.=" Telef.:". chr(32) . $row->telf_cliente;
          
          for ($i=0; $i <= 46; $i++) { 
            $tabla.= chr(32);
          }

          $tabla.="Fecha:" . chr(32) . $strdate . "\r\n";
    
        }   
        
        $tabla.= "\r\n";
        $tabla.= "Cantidad";
          for ($i=0; $i <= 5; $i++) { 
            $tabla.= chr(32);
          }
        $tabla.= "Descripcion";
          for ($i=0; $i <= 35; $i++) { 
            $tabla.= chr(32);
          }
        $tabla.= "P. Unitario";
          for ($i=0; $i <= 10; $i++) { 
            $tabla.= chr(32);
          }
        $tabla.= "Subtotal". "\r\n";
// 
// $venta = $this->facturar_model->resfactura($id_factura)
// 
        $cantidadproducto=0;
        $limiteprodventa = $this->Parametros_model->sel_limiteprodventa();
        $venta = $this->facturar_model->ventadetalle($idfactura);

        /// print_r($venta); die; 

        foreach ($venta as $item) {
            $cantidadproducto++;
            $lcant = strlen(number_format($item->cantidad,2));
            for ($i=1; $i <= 8-$lcant; $i++) { 
              $tabla.= chr(32);
            }
            $tabla.= number_format($item->cantidad,2);
            for ($i=0; $i <= 5; $i++) { 
              $tabla.= chr(32);
            }

            /* PRODUCTO */
            $lpro = 0;
            $dife = 0;
            $lcant = 46;
            $producto = strtoupper($item->pro_nombre);
            $pro = substr($producto,0,41);
            $lpro = strlen($pro);
            $dife = $lcant - $lpro;
            $tabla.= $pro;
              for ($i=0; $i <= $dife; $i++) { 
                $tabla.= chr(32);
              } 

            /* PRECIO */
            $lpre = strlen(number_format($item->precio,2));
            $dife = 0;
            if($lpre < 9){
              $dife = 9 - $lpre;
              for ($i=0; $i <= $dife; $i++) { 
                $tabla.= chr(32);
              }               
            }
            $tabla.= number_format($item->precio,2);
            for ($i=0; $i <= 7; $i++) { 
              $tabla.= chr(32);
            }             

            /* SUBTOTAL */
            $lsub = strlen(number_format($item->subtotal,2));
            $difs = 0;
            if($lsub < 10){
              $difs = 10 - $lsub;
              for ($i=0; $i <= $difs; $i++) { 
                $tabla.= chr(32);
              }               
            }
            $tabla.= number_format($item->subtotal,2);
            $tabla.= " ". "\r\n";
        }
        if(($limiteprodventa > 0) & ($cantidadproducto < $limiteprodventa)){
          for ($i=1; $i <= ($limiteprodventa - $cantidadproducto); $i++) { $tabla.= " ". "\r\n"; }
        }
        
        

        $detfact = $this->facturar_model->resfactura($idfactura);
        $tipodoc = $detfact->tipo_doc;
        $subtotaliva = $detfact->subconiva;
        $subtotalcero = $detfact->subsiniva;
        $subtotaldiva = $detfact->descsubconiva;
        $subtotaldcero = $detfact->descsubsiniva;
        $montoiva = $detfact->montoiva;
        $descuento = $detfact->desc_monto;
        $total = $detfact->montototal;
        $tipodoc = $detfact->tipo_doc;
        $subtotal = $subtotalcero + $subtotaliva;        

        $tabla.= " ". "\r\n";
        if($tipodoc == 2){
          $ltop = strlen(number_format($subtotaliva,2));
          if (strlen(number_format($subtotalcero,2)) > $ltop) $ltop = strlen(number_format($subtotalcero,2));
          if (strlen(number_format($total,2)) > $ltop) $ltop = strlen(number_format($total,2));

          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          }  
          /* Subtotal IVA (15 %) */
          $vsubiva = "       Subtotal IVA (15%):";
          $tabla.= $vsubiva;

          $ltop = $ltop + 1;
          /*for ($i=0; $i <= 5; $i++) { $tabla.= chr(32); }*/
          $lsi= strlen(number_format($subtotaliva,2));
          $dife = 0;
          if($lsi < $ltop){
            $dife = $ltop - $lsi;
            for ($i=0; $i <= $dife; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($subtotaliva,2). "\r\n";

          /* Subtotal IVA (0 %) */
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "        Subtotal IVA (0%):";
          $lsc = strlen(number_format($subtotalcero,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($subtotalcero,2). "\r\n";

          /* DESCUENTO*/
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "                Descuento:";
          $lsc= strlen(number_format($descuento,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($descuento,2). "\r\n";

          /* Subtotal c/Desc IVA (15%) */
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "Subtotal c/Desc IVA (15%):";
          $lsc= strlen(number_format($subtotaldiva,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($subtotaldiva,2). "\r\n";

          /* Subtotal c/Desc IVA (0%) */
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= " Subtotal c/Desc IVA (0%):";
          $lsc= strlen(number_format($subtotaldcero,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($subtotaldcero,2). "\r\n";

          /* IVA (15%) */
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "                IVA (15%):";
          $lsc= strlen(number_format($montoiva,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($montoiva,2). "\r\n";

          /* Total */
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "                    Total:";
          $lsc= strlen(number_format($total,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($total,2). "\r\n";


        }else{
          $ltop = strlen(number_format($subtotaliva,2));
          if (strlen(number_format($subtotalcero,2)) > $ltop) $ltop = strlen(number_format($subtotalcero,2));
          if (strlen(number_format($total,2)) > $ltop) $ltop = strlen(number_format($total,2));          
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          }  
          /* Subtotal IVA (15 %) */
          $vsubiva = "                Subtotal:";
          $tabla.= $vsubiva;

          $ltop = $ltop + 1;
          /*for ($i=0; $i <= 5; $i++) { $tabla.= chr(32); }*/
          $lsi= strlen(number_format($subtotaliva,2));
          $dife = 0;
          if($lsi < $ltop){
            $dife = $ltop - $lsi;
            for ($i=0; $i <= $dife; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($subtotaliva,2). "\r\n";


          /* DESCUENTO*/
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "                Descuento:";
          $lsc= strlen(number_format($descuento,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($descuento,2). "\r\n";

          /* Subtotal c/Desc IVA (15%) */
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 




          /* Total */
          for ($i=0; $i <= 55; $i++) { 
            $tabla.= chr(32);
          } 
          $tabla.= "                    Total:";
          $lsc= strlen(number_format($total,2));
          $di = 0;
          if($lsc < $ltop){
            $di = $ltop - $lsc;
            for ($i=0; $i <= $di; $i++) { 
              $tabla.= chr(32);
            }               
          }
          $tabla.= number_format($total,2). "\r\n";
        }


        $data["strcomanda"] = $tabla; 
        $impresionlocal = $this->Parametros_model->sel_impresionlocal();
        $data["impresionlocal"] = $impresionlocal; 
        
        $data["base_url"] = base_url();
        $this->load->view("factura_imprimir", $data);

    }

    /* VALIDA CLIENTE */
    public function get_clientecodigo(){
        $codigo = $this->input->post('codigo');
        $resu = $this->facturar_model->get_clienteporcodigo($codigo);
        if($resu == 1){ $mens = 1; }
        else { $mens = $resu[0];}
        $arr['mens'] = $mens;
        print json_encode($arr);
    }

    public function upd_vendedor(){
      $idvendedor = $this->input->post('idvendedor');
      $idventa = $this->input->post('idventa');   
      $this->facturar_model->updv_vendedor($idvendedor, $idventa);
    }

    public function upd_comisionventatmp(){
      $idusu = $this->session->userdata("sess_id");
      $comision = $this->input->post("comision");
      $resu = $this->facturar_model->upd_comisionventatmp($idusu, $comision);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    // Garantia
    public function tmp_garfactura() {
        $this->session->unset_userdata("temp_garfactura"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("temp_garfactura", NULL);
        if ($id != NULL) { $this->session->set_userdata("temp_garfactura", $id); } 
        else { $this->session->set_userdata("temp_garfactura", NULL); }
        print json_encode($id);
    }  

    private function pag_v() {
      $this->pdf_g->SetMargins('12', '7', '10');   #Margenes
      $this->pdf_g->AddPage('P', 'A4');        #Orientación y tamaño 
    }

    public function garantiapdf(){
      $sucursal = $this->facturar_model->logo();  
      $params['sucursal'] = $sucursal;      
      $idventa = $this->session->userdata("temp_garfactura");
      $garenca = $this->facturar_model->gar_enca($idventa);
      $params['garenca'] = $garenca;
      $gardet = $this->facturar_model->gardetalle($idventa);


      $this->load->library('pdf_g', $params);
      $this->pdf_g->fontpath = 'font/'; 
      $this->pdf_g->AliasNbPages();
      $this->pag_v();
      $this->pdf_g->SetFillColor(139, 35, 35);
      $this->pdf_g->SetFont('Arial','B',8);
      $this->pdf_g->SetTextColor(0,0,0);

      $this->pdf_g->Cell(70,4,utf8_decode("Producto "),1,0,'L');
      $this->pdf_g->Cell(35,4,'Nro Serie',1,0,'L');
      $this->pdf_g->Cell(25,4,'Desde',1,0,'C');
      $this->pdf_g->Cell(25,4,'Hasta',1,0,'C');
      $this->pdf_g->Cell(25,4,'Periodo',1,1,'C');   
      $this->pdf_g->ln(1); 
      foreach ($gardet as $gd) {
        $this->pdf_g->Cell(70,4,utf8_decode($gd->pro_nombre),0,0,'L');
        $this->pdf_g->Cell(35,4,$gd->numeroserie,0,0,'L');

        $fecd = $gd->fec_desde;
        $fechd = str_replace('-', '/', $fecd); 
        $fechad = date("d/m/Y", strtotime($fechd));  
        $this->pdf_g->Cell(25,4,$fechad,0,0,'C');

        $fech = $gd->fec_hasta;
        $fechh = str_replace('-', '/', $fech); 
        $fechah = date("d/m/Y", strtotime($fechh));  
        $this->pdf_g->Cell(25,4,$fechah,0,0,'C');
        $this->pdf_g->Cell(25,4,$gd->dias_gar,0,1,'C');             
      }   


      $this->pdf_g->SetY(215);


      $this->pdf_g->Cell(50,4,utf8_decode("Entrega: ________________________________ "),0,0,'L');
      $this->pdf_g->Cell(70,0,'',0,0,'L');        
      $this->pdf_g->Cell(50,4,utf8_decode("Recibe: ________________________________"),0,1,'L'); 

      $this->pdf_g->ln(3);




      $clausula = $this->Clausula_model->sel_clausulas();

      $this->pdf_g->MultiCell(0,5, utf8_decode($clausula));
     

      $this->pdf_g->Output('Garantia.pdf','I'); 

    }

    public function garantiatxt(){
      //$idventa = $this->session->userdata("temp_garfactura");
      $idventa = $this->input->post("id");

      $garenca = $this->facturar_model->gar_enca($idventa);
      $gardet = $this->facturar_model->gardetalle($idventa);
      $objsuc = $this->Sucursal_model->sel_suc_id($garenca->id_sucursal);
      $objemp = $this->Empresa_model->sel_emp_id($garenca->id_empresa);

      $tabla = chr(32). chr(32) . "\r\n";
      $tabla.="CERTIFICADO DE GARANTIA" . "\r\n";
      $tabla.=$objsuc->nom_sucursal . "\r\n";
      $tabla.="RUC:" . chr(32) . $objemp->ruc_emp . "\r\n";
      $tabla.=$objemp->dir_emp . "\r\n";
      $tabla.="TEL:" . $objemp->tlf_emp . "\r\n";
      $tabla.= "\r\n";

      if (trim($garenca->nro_factura) != ''){
        $tabla.="Nro. Docum.:" . chr(32) . $garenca->nro_factura . "\r\n";  
      }
      $strdate = str_replace('-', '/', $garenca->fecha); 
      $strdate = date("d/m/Y", strtotime($strdate)); 
      $tabla.="Fecha:" . chr(32) . $strdate . "\r\n";

      $tabla.="Cliente:" . chr(32) . $garenca->nom_cliente . "\r\n";        
      $tabla.="Direccion:". chr(32) . $garenca->direccion_cliente . "\r\n";        
      $tabla.="CI/RUC:". chr(32) . $garenca->cedula . "\r\n";        
      $tabla.="Telef.:". chr(32) . $garenca->telefonos_cliente . "\r\n";  

      $tabla.= "\r\n";
      $tabla.="Producto   NroSerie  Desde    Hasta". "\r\n";        

      foreach ($gardet as $row) {
        $strnombre = substr($row->pro_nombre,0,9);
        $tabla.= $strnombre;
        $lcant = strlen($strnombre);
        while ($lcant < 10){
            $tabla.= chr(32);
            $lcant++;
        }
        $strnombre = substr($row->numeroserie,0,8);
        $tabla.= $strnombre;
        $lcant = strlen($strnombre);
        while ($lcant < 9){
            $tabla.= chr(32);
            $lcant++;
        }
        $fecd = $row->fec_desde;
        $fechd = str_replace('-', '/', $fecd); 
        $strnombre = date("d/m/Y", strtotime($fechd));  
        $tabla.= $strnombre;
        $lcant = strlen($strnombre);
        while ($lcant < 11){
            $tabla.= chr(32);
            $lcant++;
        }
        $fech = $row->fec_hasta;
        $fechh = str_replace('-', '/', $fech); 
        $strnombre = date("d/m/Y", strtotime($fechh));  
        $tabla.= $strnombre;
        $tabla.= "\r\n";
      }   
      $tabla.= "\r\n";

      $tabla.= "Entrega:";
      $tabla.= "\r\n";
      $tabla.= "\r\n";
      $tabla.= "Recibe:";
      $tabla.= "\r\n";
      $tabla.= "\r\n";

      $clausula = $this->Clausula_model->sel_clausulas();
      $tabla.= utf8_decode($clausula);

      $data["idfactura"] = $idventa; 
      $data["strcomanda"] = $tabla; 
      $impresionlocal = $this->Parametros_model->sel_impresionlocal();
      $data["impresionlocal"] = $impresionlocal; 

      $data["base_url"] = base_url();
      $this->load->view("factura_imprimir_garantia", $data);
    }

    public function ventas_analisis(){
      $desde = $this->session->userdata("tmp_analisis_desde");
      $hasta = $this->session->userdata("tmp_analisis_hasta");        
      $sucursal = $this->session->userdata("tmp_analisis_sucursal");        
      $producto = $this->session->userdata("tmp_analisis_producto");        

      $this->session->set_userdata("tmp_analisis_desde", NULL);
      if ($desde != NULL) { $this->session->set_userdata("tmp_analisis_desde", $desde); } 
      else { $this->session->set_userdata("tmp_analisis_desde", date("Y-m-d")); }
      $this->session->set_userdata("tmp_analisis_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmp_analisis_hasta", $hasta); } 
      else { $this->session->set_userdata("tmp_analisis_hasta", date("Y-m-d")); }
      $this->session->set_userdata("tmp_analisis_sucursal", NULL);
      if ($sucursal != NULL) { $this->session->set_userdata("tmp_analisis_sucursal", $sucursal); } 
      else { $this->session->set_userdata("tmp_analisis_sucursal", 0); }
      $this->session->set_userdata("tmp_analisis_producto", NULL);
      if ($producto != NULL) { $this->session->set_userdata("tmp_analisis_producto", $producto); } 
      else { $this->session->set_userdata("tmp_analisis_producto", 0); }

      $desde = $this->session->userdata("tmp_analisis_desde");
      $hasta = $this->session->userdata("tmp_analisis_hasta");        
      $sucursal = $this->session->userdata("tmp_analisis_sucursal");        
      $producto = $this->session->userdata("tmp_analisis_producto");        

      $sucursales = $this->Sucursal_model->lst_sucursales();
      $productos = $this->Producto_model->lstprod();

      $data["desde"] = $desde;
      $data["hasta"] = $hasta;
      $data["sucursal"] = $sucursal;
      $data["sucursales"] = $sucursales;
      $data["producto"] = $producto;
      $data["productos"] = $productos;

      $data["base_url"] = base_url();
      $data["content"] = "venta_analisis";
      $this->load->view("layout", $data);
    }

    public function tmp_analisis(){
      /* fecha desde */
      $fecdesde = $this->input->post("desde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      /* fecha hasta */
      $fechasta = $this->input->post("hasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));

      $sucursal = $this->input->post("sucursal");
      $producto = $this->input->post("producto");

      $this->session->set_userdata("tmp_analisis_desde", NULL);
      if ($desde != NULL) {
          $this->session->set_userdata("tmp_analisis_desde", $desde);
      } else {
          $this->session->set_userdata("tmp_analisis_desde", NULL);
      }

      $this->session->set_userdata("tmp_analisis_hasta", NULL);
      if ($hasta != NULL) {
          $this->session->set_userdata("tmp_analisis_hasta", $hasta);
      } else {
          $this->session->set_userdata("tmp_analisis_hasta", NULL);
      }

      $this->session->set_userdata("tmp_analisis_sucursal", NULL);
      if ($sucursal != NULL) {
          $this->session->set_userdata("tmp_analisis_sucursal", $sucursal);
      } else {
          $this->session->set_userdata("tmp_analisis_sucursal", 0);
      }

      $this->session->set_userdata("tmp_analisis_producto", NULL);
      if ($producto != NULL) {
          $this->session->set_userdata("tmp_analisis_producto", $producto);
      } else {
          $this->session->set_userdata("tmp_analisis_producto", 0);
      }

      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function lst_ventasresumen_tipoprecio(){
      $desde = $this->session->userdata("tmp_analisis_desde");
      $hasta = $this->session->userdata("tmp_analisis_hasta");        
      $sucursal = $this->session->userdata("tmp_analisis_sucursal");        
      $producto = $this->session->userdata("tmp_analisis_producto");        

      $desde = str_replace('/', '-', $desde); 
      $desde = date("Y-m-d", strtotime($desde));
      $hasta = str_replace('/', '-', $hasta); 
      $hasta = date("Y-m-d", strtotime($hasta));

      $registros = $this->facturar_model->lst_ventasresumen_tipoprecio($sucursal, $desde, $hasta, $producto);
      print json_encode($registros);
    }

    public function lst_ventasdetalles_tipoprecio(){
      $desde = $this->session->userdata("tmp_analisis_desde");
      $hasta = $this->session->userdata("tmp_analisis_hasta");        
      $sucursal = $this->session->userdata("tmp_analisis_sucursal");        
      $producto = $this->session->userdata("tmp_analisis_producto");        

      $desde = str_replace('/', '-', $desde); 
      $desde = date("Y-m-d", strtotime($desde));
      $hasta = str_replace('/', '-', $hasta); 
      $hasta = date("Y-m-d", strtotime($hasta));

      $registros = $this->facturar_model->lst_ventasdetalles_tipoprecio($sucursal, $desde, $hasta, $producto);
      print json_encode($registros);
    }

    public function tmp_ventacli_fecha() {
      $this->session->unset_userdata("tmp_vencli_desde"); 
      $this->session->unset_userdata("tmp_vencli_hasta");
      $this->session->unset_userdata("tmp_vencli_cliente");
      $this->session->unset_userdata("tmp_vencli_sucursal");
      $this->session->unset_userdata("tmp_vencli_categoria");
      $cliente = $this->input->post("cliente");
      $sucursal = $this->input->post("sucursal");
      $categoria = $this->input->post("categoria");
      $desde = $this->input->post("desde");
      $desde = str_replace('/', '-', $desde); 
      $desde = date("Y-m-d", strtotime($desde));
      $hasta = $this->input->post("hasta");
      $hasta = str_replace('/', '-', $hasta); 
      $hasta = date("Y-m-d", strtotime($hasta));
      $this->session->set_userdata("tmp_vencli_desde", NULL);
      if ($desde != NULL) { $this->session->set_userdata("tmp_vencli_desde", $desde);} 
      else { $this->session->set_userdata("tmp_vencli_desde", NULL); }
      $this->session->set_userdata("tmp_vencli_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmp_vencli_hasta", $hasta);} 
      else { $this->session->set_userdata("tmp_vencli_hasta", NULL);}
      $this->session->set_userdata("tmp_vencli_cliente", NULL);
      if ($cliente != NULL) { $this->session->set_userdata("tmp_vencli_cliente", $cliente);} 
      else { $this->session->set_userdata("tmp_vencli_cliente", NULL);}
      $this->session->set_userdata("tmp_vencli_sucursal", NULL);
      if ($sucursal != NULL) { $this->session->set_userdata("tmp_vencli_sucursal", $sucursal);} 
      else { $this->session->set_userdata("tmp_vencli_sucursal", NULL);}
      $this->session->set_userdata("tmp_vencli_categoria", NULL);
      if ($categoria != NULL) { $this->session->set_userdata("tmp_vencli_categoria", $categoria);} 
      else { $this->session->set_userdata("tmp_vencli_categoria", NULL);}
      $arr['resu'] = 1;
      print json_encode($arr);
    } 

    public function listadoDataventacliente() {
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $desde = $this->session->userdata("tmp_vencli_desde");
        $hasta = $this->session->userdata("tmp_vencli_hasta");        
        $cliente = $this->session->userdata("tmp_vencli_cliente");        
        $sucursal = $this->session->userdata("tmp_vencli_sucursal");        
        $categoria = $this->session->userdata("tmp_vencli_categoria");        

        if (($desde == '') || ($hasta == '') || ($cliente == '')){
          $desde = date("Y-m-d");
          $hasta = date("Y-m-d");
          $cliente = 0;
          $sucursal = 0;
          $categoria = 0;
        }

        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        if ($categoria == 0){
          $registros = $this->facturar_model->lst_ventasdetalles_cliente($sucursal, $cliente, $desde, $hasta);
        }
        else{
          $registros = $this->facturar_model->lst_ventasdetalles_categoriacliente($sucursal, $categoria, $desde, $hasta);
        }
        $tabla = "";
        foreach ($registros as $row) {

          @$fec = str_replace('-', '/', $row->fecha); @$fec = date("d/m/Y", strtotime(@$fec));
          $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Imprimir Venta\" id=\"'.$row->id_venta.'\" class=\"btn bg-navy color-palette btn-xs btn-grad venta_print\"><i class=\"fa fa-print\"></i></a>';
         
          $tabla.=  '{"ver":"' . $ver . '",
                      "estatus":"' . $row->estatus . '",
                      "fecha":"' . $fec . '",
                      "factura":"' . $row->nro_factura . '",
                      "cliente":"' . addslashes($row->nom_cliente) . '",
                      "direccion":"' . addslashes($row->direccion_cliente) . '",
                      "correo":"' . addslashes($row->correo_cliente) . '",
                      "telefono":"' . addslashes($row->telefonos_cliente) . '",
                      "producto":"' . addslashes($row->descripcion) . '",
                      "tipocancel":"'.$row->nom_cancelacion.'",
                      "cantidad":"'.number_format($row->cantidad, $decimalescantidad).'",
                      "precio":"'.number_format($row->precio, $decimalesprecio).'",
                      "descsubtotal":"'.number_format($row->subtotal,2).'",
                      "descmonto":"'.$row->descmonto.'",
                      "montoiva":"'.$row->montoiva.'",
                      "valortotal":"'.$row->valortotal.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    public function listadoDataclienteproducto() {
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $desde = $this->session->userdata("tmp_vencli_desde");
        $hasta = $this->session->userdata("tmp_vencli_hasta");        
        $cliente = $this->session->userdata("tmp_vencli_cliente");        
        $sucursal = $this->session->userdata("tmp_vencli_sucursal");        
        $categoria = $this->session->userdata("tmp_vencli_categoria");        

        if (($desde == '') || ($hasta == '') || ($cliente == '')){
          $desde = date("Y-m-d");
          $hasta = date("Y-m-d");
          $cliente = 0;
          $sucursal = 0;
          $categoria = 0;
        }

        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        if ($categoria == 0){
          $registros = $this->facturar_model->lst_ventas_clienteproducto($sucursal, $cliente, $desde, $hasta);
        }
        else{
          $registros = $this->facturar_model->lst_ventas_categoriaproducto($sucursal, $categoria, $desde, $hasta);
        }
        $tabla = "";
        foreach ($registros as $row) {

          $precio = 0;
          if ($row->cantidad != 0){
            $precio = round($row->subtotal / $row->cantidad, 6);
          }
        
          $tabla.=  '{"producto":"' . addslashes($row->descripcion) . '",
                      "cantidad":"'.number_format($row->cantidad, $decimalescantidad).'",
                      "precio":"'.number_format($precio, $decimalesprecio).'",
                      "descsubtotal":"'.number_format($row->subtotal,2).'",
                      "descmonto":"'.$row->descmonto.'",
                      "montoiva":"'.$row->montoiva.'",
                      "valortotal":"'.$row->valortotal.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    public function lst_ventasdetalles_cliente(){
      $desde = $this->session->userdata("tmp_vencli_desde");
      $hasta = $this->session->userdata("tmp_vencli_hasta");        
      $cliente = $this->session->userdata("tmp_vencli_cliente");        

      if (($desde == '') || ($hasta == '') || ($cliente == '')){
        $desde = date("Y-m-d");
        $hasta = date("Y-m-d");
        $cliente = 0;
      }

      $desde = str_replace('/', '-', $desde); 
      $desde = date("Y-m-d", strtotime($desde));
      $hasta = str_replace('/', '-', $hasta); 
      $hasta = date("Y-m-d", strtotime($hasta));

      $registros = $this->facturar_model->lst_ventasdetalles_cliente($cliente, $desde, $hasta);
      print json_encode($registros);
    }

    public function tmp_ventapro_fecha() {
      $this->session->unset_userdata("tmp_venpro_sucursal"); 
      $this->session->unset_userdata("tmp_venpro_desde"); 
      $this->session->unset_userdata("tmp_venpro_hasta");
      $this->session->unset_userdata("tmp_venpro_producto");
      $sucursal = $this->input->post("sucursal");
      $producto = $this->input->post("producto");
      $categoria = $this->input->post("categoria");
      $todos = $this->input->post("todos");
      $desde = $this->input->post("desde");
      $desde = str_replace('/', '-', $desde); 
      $desde = date("Y-m-d", strtotime($desde));
      $hasta = $this->input->post("hasta");
      $hasta = str_replace('/', '-', $hasta); 
      $hasta = date("Y-m-d", strtotime($hasta));
      $this->session->set_userdata("tmp_venpro_sucursal", NULL);
      if ($sucursal != NULL) { $this->session->set_userdata("tmp_venpro_sucursal", $sucursal);} 
      else { $this->session->set_userdata("tmp_venpro_sucursal", NULL); }
      $this->session->set_userdata("tmp_venpro_desde", NULL);
      if ($desde != NULL) { $this->session->set_userdata("tmp_venpro_desde", $desde);} 
      else { $this->session->set_userdata("tmp_venpro_desde", NULL); }
      $this->session->set_userdata("tmp_venpro_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmp_venpro_hasta", $hasta);} 
      else { $this->session->set_userdata("tmp_venpro_hasta", NULL);}
      $this->session->set_userdata("tmp_venpro_producto", NULL);
      if ($producto != NULL) { $this->session->set_userdata("tmp_venpro_producto", $producto);} 
      else { $this->session->set_userdata("tmp_venpro_producto", NULL);}
      $this->session->set_userdata("tmp_venpro_categoria", NULL);
      if ($categoria != NULL) { $this->session->set_userdata("tmp_venpro_categoria", $categoria);} 
      else { $this->session->set_userdata("tmp_venpro_categoria", NULL);}
      $this->session->set_userdata("tmp_venpro_todos", NULL);
      if ($todos != NULL) { $this->session->set_userdata("tmp_venpro_todos", $todos);} 
      else { $this->session->set_userdata("tmp_venpro_todos", NULL);}

      $arr['resu'] = 1;
      print json_encode($arr);
    } 

    public function listadoDataventaproducto() {
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $sucursal = $this->session->userdata("tmp_venpro_sucursal");
        $desde = $this->session->userdata("tmp_venpro_desde");
        $hasta = $this->session->userdata("tmp_venpro_hasta");        
        $producto = $this->session->userdata("tmp_venpro_producto");        
        $categoria = $this->session->userdata("tmp_venpro_categoria");        
        $todos = $this->session->userdata("tmp_venpro_todos");        

        if (($desde == '') || ($hasta == '') || ($producto == '') || ($producto == NULL)){
          $desde = date("Y-m-d");
          $hasta = date("Y-m-d");
          $producto = 0;
        }

        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        if ($todos != 1){
          $registros = $this->facturar_model->lst_ventasdetalles_producto($sucursal, $producto, $desde, $hasta);
        }
        else{
          $registros = $this->facturar_model->lst_ventasdetalles_categoriaproducto($sucursal, $categoria, $desde, $hasta);
        }
        $tabla = "";
        foreach ($registros as $row) {

          @$fec = str_replace('-', '/', $row->fecha); @$fec = date("d/m/Y", strtotime(@$fec));
          $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Imprimir Venta\" id=\"'.$row->id_venta.'\" class=\"btn bg-navy color-palette btn-xs btn-grad venta_print\"><i class=\"fa fa-print\"></i></a>';
         
          $tabla.=  '{"ver":"' . $ver . '",
                      "estatus":"' . $row->estatus . '",
                      "fecha":"' . $fec . '",
                      "factura":"' . $row->nro_factura . '",
                      "cliente":"' . addslashes($row->nom_cliente) . '",
                      "direccion":"' . addslashes($row->direccion_cliente) . '",
                      "correo":"' . addslashes($row->correo_cliente) . '",
                      "telefono":"' . addslashes($row->telefonos_cliente) . '",
                      "producto":"' . addslashes($row->descripcion) . '",
                      "tipocancel":"'.$row->nom_cancelacion.'",
                      "cantidad":"'.number_format($row->cantidad, $decimalescantidad).'",
                      "precio":"'.number_format($row->precio, $decimalesprecio).'",
                      "tipoprecio":"'.addslashes($row->tipoprecio).'",
                      "descsubtotal":"'.number_format($row->subtotal,2).'",
                      "descmonto":"'.$row->descmonto.'",
                      "montoiva":"'.$row->montoiva.'",
                      "valortotal":"'.$row->valortotal.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    /* Exportar Detall de Venta a Excel */
    public function reporteventaproductoXLS(){
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $desde = $this->session->userdata("tmp_venpro_desde");
        $hasta = $this->session->userdata("tmp_venpro_hasta");        
        $producto = $this->session->userdata("tmp_venpro_producto");        
        $categoria = $this->session->userdata("tmp_venpro_categoria");        
        $todos = $this->session->userdata("tmp_venpro_todos");        

        if (($desde == '') || ($hasta == '') || ($producto == '') || ($producto == NULL)){
          $desde = date("Y-m-d");
          $hasta = date("Y-m-d");
          $producto = 0;
        }

        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        $sucursal = $this->session->userdata("tmp_ven_sucursal");
        if ($todos != 1){
          $venta = $this->facturar_model->lst_ventasdetalles_producto($sucursal, $producto, $desde, $hasta);
        }
        else{
          $venta = $this->facturar_model->lst_ventasdetalles_categoriaproducto($sucursal, $categoria, $desde, $hasta);
        }


        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        }

        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteVentaProducto');
        //set cell A1 content with some text
        $empresa = "";
        if ($objemp){
          $empresa = "  - EMPRESA: " . $objemp->nom_emp . "    RUC: " . $objemp->ruc_emp;
        }
        if ($todos != 1) { 
          $objprod = $this->Producto_model->sel_pro_id($producto);
          $strpro = 'Producto '. $objprod->pro_nombre;
        }
        else{
          $objprod = $this->Categoria_model->sel_upd_id($categoria);
          $strpro = 'Categoría ' . $objprod->cat_descripcion;         
        }
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Detalles de Venta de ' . $strpro . 
                                                           ' del ' . substr($desde,0,10) . ' al ' . substr($hasta,0,10) . $empresa);
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:L1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Factura');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Cancela');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Cantidad');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Precio');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Tipo');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Subtotal');        
        $this->excel->getActiveSheet()->setCellValue('I3', 'Descuento');  
        $this->excel->getActiveSheet()->setCellValue('J3', 'Monto IVA');
        $this->excel->getActiveSheet()->setCellValue('K3', 'Valor Total');
        $this->excel->getActiveSheet()->setCellValue('L3', 'Cliente');
        $this->excel->getActiveSheet()->setCellValue('M3', 'Teléfono');
        $this->excel->getActiveSheet()->setCellValue('N3', 'Correo');
        $this->excel->getActiveSheet()->setCellValue('O3', 'Dirección');

        $this->excel->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);

        $montoiva = 0;
        $valortotal = 0;
        $subtotal = 0;
        $descuento = 0;
        $fila = 4;
        $filaini = $fila;
        foreach ($venta as $ven) {
          if($ven->estatus != 3){
            $subtotal += $ven->subtotal;
            $montoiva += $ven->montoiva;
            $valortotal += $ven->valortotal;
            $descuento += $ven->descmonto;

            @$fec = str_replace('-', '/', $ven->fecharegistro); @$fec = date("d/m/Y  H:i:s", strtotime(@$fec)); 
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->nro_factura);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ven->nom_cancelacion);            
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $ven->descripcion);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($ven->cantidad,2));
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($ven->precio,6));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, $ven->tipoprecio);
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($ven->subtotal,2));
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($ven->descmonto,2));
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, number_format($ven->montoiva,2));
            $this->excel->getActiveSheet()->setCellValue('K'.$fila, number_format($ven->valortotal,2));
            $this->excel->getActiveSheet()->setCellValue('L'.$fila, $ven->nom_cliente);
            $this->excel->getActiveSheet()->setCellValue('M'.$fila, $ven->telefonos_cliente);
            $this->excel->getActiveSheet()->setCellValue('N'.$fila, $ven->correo_cliente);
            $this->excel->getActiveSheet()->setCellValue('O'.$fila, $ven->direccion_cliente);

            $fila++;          
          }  
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('G'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($subtotal,2));
        $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($descuento,2));
        $this->excel->getActiveSheet()->setCellValue('J'.$fila, number_format($montoiva,2));
        $this->excel->getActiveSheet()->setCellValue('K'.$fila, number_format($valortotal,2));

        $currencyFormat = '_(#,##0.00_);_( (#,##0.00);_( "-"??_);_(@_)';
        $priceFormat = '_(#,##0.000000_);_( (#,##0.000000);_( "-"??_);_(@_)';
        $this->excel->getActiveSheet()->getStyle('F'.$filaini.':F'.$fila)->getNumberFormat()->setFormatCode($priceFormat);
        $this->excel->getActiveSheet()->getStyle('H'.$filaini.':K'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);

        $this->excel->getActiveSheet()->getStyle('H'.$fila.':K'.$fila)->getFont()->setBold(true);



        foreach(range('A','O') as $columnID)
        {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }


        $this->excel->getActiveSheet()->freezePane('A4');

        
        $filename='reportedetalleventa.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    public function reporteventaclienteXLS(){
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $desde = $this->session->userdata("tmp_vencli_desde");
        $hasta = $this->session->userdata("tmp_vencli_hasta");        
        $cliente = $this->session->userdata("tmp_vencli_cliente");        
        $sucursal = $this->session->userdata("tmp_vencli_sucursal");        
        $categoria = $this->session->userdata("tmp_vencli_categoria");        

        if (($desde == '') || ($hasta == '') || ($cliente == '')){
          $desde = date("Y-m-d");
          $hasta = date("Y-m-d");
          $cliente = 0;
          $sucursal = 0;
          $categoria = 0;
        }

        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        if ($categoria == 0){
          $venta = $this->facturar_model->lst_ventasdetalles_cliente($sucursal, $cliente, $desde, $hasta);
        }
        else{
          $venta = $this->facturar_model->lst_ventasdetalles_categoriacliente($sucursal, $categoria, $desde, $hasta);
        }


        //$sucursal = $this->session->userdata("tmp_ven_sucursal");

        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        }

        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteVentaCliente');
        //set cell A1 content with some text
        $empresa = "";
        if ($objemp){
          $empresa = "  - EMPRESA: " . $objemp->nom_emp . "    RUC: " . $objemp->ruc_emp;
        }
        $objcli = $this->Cliente_model->sel_cli_id($cliente);
        $strcli = 'Cliente '. $objcli->nom_cliente;
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Detalles de Venta de ' . $strcli . 
                                                           ' del ' . substr($desde,0,10) . ' al ' . substr($hasta,0,10) . $empresa);
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:L1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Factura');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Cancela');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Cantidad');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Precio');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Subtotal');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Descuento');        
        $this->excel->getActiveSheet()->setCellValue('I3', 'Monto IVA');  
        $this->excel->getActiveSheet()->setCellValue('J3', 'Valor Total');
        $this->excel->getActiveSheet()->setCellValue('K3', 'Cliente');
        $this->excel->getActiveSheet()->setCellValue('L3', 'Dirección');
        $this->excel->getActiveSheet()->setCellValue('M3', 'Correo');
        $this->excel->getActiveSheet()->setCellValue('N3', 'Teléfono');

        $this->excel->getActiveSheet()->getStyle('A3:N3')->getFont()->setBold(true);

        $montoiva = 0;
        $valortotal = 0;
        $subtotal = 0;
        $descuento = 0;
        $fila = 4;
        $filaini = $fila;
        foreach ($venta as $ven) {
          if($ven->estatus != 3){
            $subtotal += $ven->subtotal;
            $montoiva += $ven->montoiva;
            $valortotal += $ven->valortotal;
            $descuento += $ven->descmonto;

            @$fec = str_replace('-', '/', $ven->fecharegistro); @$fec = date("d/m/Y  H:i:s", strtotime(@$fec)); 
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->nro_factura);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ven->nom_cancelacion);            
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $ven->descripcion);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($ven->cantidad,2));
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($ven->precio,6));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($ven->subtotal,2));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($ven->descmonto,2));
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($ven->montoiva,2));
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, number_format($ven->valortotal,2));
            $this->excel->getActiveSheet()->setCellValue('K'.$fila, $ven->nom_cliente);
            $this->excel->getActiveSheet()->setCellValue('L'.$fila, $ven->telefonos_cliente);
            $this->excel->getActiveSheet()->setCellValue('M'.$fila, $ven->correo_cliente);
            $this->excel->getActiveSheet()->setCellValue('N'.$fila, $ven->direccion_cliente);

            $fila++;          
          }  
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('F'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($subtotal,2));
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($descuento,2));
        $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($montoiva,2));
        $this->excel->getActiveSheet()->setCellValue('J'.$fila, number_format($valortotal,2));

        $currencyFormat = '_(#,##0.00_);_( (#,##0.00);_( "-"??_);_(@_)';
        $priceFormat = '_(#,##0.000000_);_( (#,##0.000000);_( "-"??_);_(@_)';
        $this->excel->getActiveSheet()->getStyle('F'.$filaini.':F'.$fila)->getNumberFormat()->setFormatCode($priceFormat);
        $this->excel->getActiveSheet()->getStyle('G'.$filaini.':J'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);

        $this->excel->getActiveSheet()->getStyle('F'.$fila.':J'.$fila)->getFont()->setBold(true);



        foreach(range('A','N') as $columnID)
        {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }


        $this->excel->getActiveSheet()->freezePane('A4');

        
        $filename='reportedetalleventa.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    public function reportecliente_resumenproductoXLS(){
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $desde = $this->session->userdata("tmp_vencli_desde");
        $hasta = $this->session->userdata("tmp_vencli_hasta");        
        $cliente = $this->session->userdata("tmp_vencli_cliente");        
        $sucursal = $this->session->userdata("tmp_vencli_sucursal");        
        $categoria = $this->session->userdata("tmp_vencli_categoria");        

        if (($desde == '') || ($hasta == '') || ($cliente == '')){
          $desde = date("Y-m-d");
          $hasta = date("Y-m-d");
          $cliente = 0;
          $sucursal = 0;
          $categoria = 0;
        }

        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        if ($categoria == 0){
          $venta = $this->facturar_model->lst_ventas_clienteproducto($sucursal, $cliente, $desde, $hasta);
        }
        else{
          $venta = $this->facturar_model->lst_ventas_categoriaproducto($sucursal, $categoria, $desde, $hasta);
        }


        //$sucursal = $this->session->userdata("tmp_ven_sucursal");

        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        }

        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteVentaCliente');
        //set cell A1 content with some text
        $empresa = "";
        if ($objemp){
          $empresa = "  - EMPRESA: " . $objemp->nom_emp . "    RUC: " . $objemp->ruc_emp;
        }
        $objcli = $this->Cliente_model->sel_cli_id($cliente);
        $strcli = 'Cliente '. $objcli->nom_cliente;
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Resumen de Productos Vendidos de ' . $strcli . 
                                                           ' del ' . substr($desde,0,10) . ' al ' . substr($hasta,0,10) . $empresa);
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:L1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Cantidad');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Precio');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Subtotal');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Descuento');        
        $this->excel->getActiveSheet()->setCellValue('F3', 'Monto IVA');  
        $this->excel->getActiveSheet()->setCellValue('G3', 'Valor Total');

        $this->excel->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true);

        $montoiva = 0;
        $valortotal = 0;
        $subtotal = 0;
        $descuento = 0;
        $fila = 4;
        $filaini = $fila;
        foreach ($venta as $ven) {
          if($ven->estatus != 3){
            $subtotal += $ven->subtotal;
            $montoiva += $ven->montoiva;
            $valortotal += $ven->valortotal;
            $descuento += $ven->descmonto;

            $precio = 0;
            if ($ven->cantidad != 0){
              $precio = round($ven->subtotal / $ven->cantidad, 6);
            }

            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $ven->descripcion);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, number_format($ven->cantidad,2));
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, number_format($precio,6));
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, number_format($ven->subtotal,2));
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($ven->descmonto,2));
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($ven->montoiva,2));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($ven->valortotal,2));

            $fila++;          
          }  
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('A'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, number_format($subtotal,2));
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($descuento,2));
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($montoiva,2));
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($valortotal,2));

        $currencyFormat = '_(#,##0.00_);_( (#,##0.00);_( "-"??_);_(@_)';
        $priceFormat = '_(#,##0.000000_);_( (#,##0.000000);_( "-"??_);_(@_)';
        $this->excel->getActiveSheet()->getStyle('C'.$filaini.':C'.$fila)->getNumberFormat()->setFormatCode($priceFormat);
        $this->excel->getActiveSheet()->getStyle('D'.$filaini.':G'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);

        $this->excel->getActiveSheet()->getStyle('C'.$fila.':G'.$fila)->getFont()->setBold(true);



        foreach(range('A','G') as $columnID)
        {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }


        $this->excel->getActiveSheet()->freezePane('A4');

        
        $filename='reporteresumenventa.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    public function tmp_ventaven_fecha() {
      $this->session->unset_userdata("tmp_venven_sucursal"); 
      $this->session->unset_userdata("tmp_venven_desde"); 
      $this->session->unset_userdata("tmp_venven_hasta");
      $this->session->unset_userdata("tmp_venven_vendedor");
      $sucursal = $this->input->post("sucursal");
      $vendedor = $this->input->post("vendedor");
      $desde = $this->input->post("desde");
      $desde = str_replace('/', '-', $desde); 
      $desde = date("Y-m-d", strtotime($desde));
      $hasta = $this->input->post("hasta");
      $hasta = str_replace('/', '-', $hasta); 
      $hasta = date("Y-m-d", strtotime($hasta));
      $this->session->set_userdata("tmp_venven_sucursal", NULL);
      if ($sucursal != NULL) { $this->session->set_userdata("tmp_venven_sucursal", $sucursal);} 
      else { $this->session->set_userdata("tmp_venven_sucursal", NULL); }
      $this->session->set_userdata("tmp_venven_desde", NULL);
      if ($desde != NULL) { $this->session->set_userdata("tmp_venven_desde", $desde);} 
      else { $this->session->set_userdata("tmp_venven_desde", NULL); }
      $this->session->set_userdata("tmp_venven_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmp_venven_hasta", $hasta);} 
      else { $this->session->set_userdata("tmp_venven_hasta", NULL);}
      $this->session->set_userdata("tmp_venven_vendedor", NULL);
      if ($vendedor != NULL) { $this->session->set_userdata("tmp_venven_vendedor", $vendedor);} 
      else { $this->session->set_userdata("tmp_venven_vendedor", NULL);}

      $arr['resu'] = 1;
      print json_encode($arr);
    } 

    public function listadoDataVendedorResumenCliente() {
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $sucursal = $this->session->userdata("tmp_venven_sucursal");
        $desde = $this->session->userdata("tmp_venven_desde");
        $hasta = $this->session->userdata("tmp_venven_hasta");        
        $vendedor = $this->session->userdata("tmp_venven_vendedor");        

        if (($desde == '') || ($hasta == '') || ($vendedor == '') || ($vendedor == NULL)){
          $desde = date("Y-m-d");
          $hasta = date("Y-m-d");
          $sucursal = 0;
          $vendedor = 0;
        }

        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));

        $registros = $this->facturar_model->lst_ventasvendedor_resumencliente($sucursal, $vendedor, $desde, $hasta);
        $tabla = "";
        foreach ($registros as $row) {
        
          $tabla.=  '{"cliente":"' . addslashes($row->nom_cliente) . '",
                      "facturas":"' . $row->cantidadfacturas . '",
                      "subsiniva":"'.number_format($row->subsiniva,2).'",
                      "subconiva":"'.number_format($row->subconiva,2).'",
                      "descmonto":"'.number_format($row->descmonto,2).'",
                      "montoiva":"'.number_format($row->montoiva,2).'",
                      "montototal":"'.number_format($row->montototal,2).'",
                      "tipoprecio":"' . addslashes($row->tipoprecio) . '",
                      "direccion":"' . addslashes($row->direccion_cliente) . '",
                      "correo":"' . addslashes($row->correo_cliente) . '",
                      "telefono":"' . addslashes($row->telefonos_cliente) . '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    public function listadoDataVendedorResumenProducto() {
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $sucursal = $this->session->userdata("tmp_venven_sucursal");
        $desde = $this->session->userdata("tmp_venven_desde");
        $hasta = $this->session->userdata("tmp_venven_hasta");        
        $vendedor = $this->session->userdata("tmp_venven_vendedor");        

        if (($desde == '') || ($hasta == '') || ($vendedor == '') || ($vendedor == NULL)){
          $desde = date("Y-m-d");
          $hasta = date("Y-m-d");
          $sucursal = 0;
          $vendedor = 0;
        }

        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));

        $registros = $this->facturar_model->lst_ventasvendedor_resumenproducto($sucursal, $vendedor, $desde, $hasta);
        $tabla = "";
        foreach ($registros as $row) {

          $precio = 0;
          if ($row->cantidad != 0){
            $precio = round($row->subtotal / $row->cantidad, 6);
          }
        
          $tabla.=  '{"producto":"' . addslashes($row->descripcion) . '",
                      "unidadmedida":"' . addslashes($row->unidadmedida) . '",
                      "cantidad":"' . number_format($row->cantidad, $decimalescantidad) . '",
                      "precio":"'.number_format($precio,$decimalesprecio).'",
                      "tipoprecio":"' . addslashes($row->tipoprecio) . '",
                      "descsubtotal":"'.number_format($row->subtotal,2).'",
                      "descmonto":"'.number_format($row->descmonto,2).'",
                      "montoiva":"'.number_format($row->montoiva,2).'",
                      "valortotal":"'.number_format($row->valortotal,2).'"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    public function listadoDataVendedorResumenVendedor() {
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $sucursal = $this->session->userdata("tmp_venven_sucursal");
        $desde = $this->session->userdata("tmp_venven_desde");
        $hasta = $this->session->userdata("tmp_venven_hasta");        

        if (($desde == '') || ($hasta == '') || ($sucursal == '') ){
          $desde = date("Y-m-d");
          $hasta = date("Y-m-d");
          $sucursal = 0;
        }

        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));

        $registros = $this->facturar_model->lst_ventasvendedor_resumen($sucursal, $desde, $hasta);
        $tabla = "";
        foreach ($registros as $row) {
        
          $tabla.=  '{"vendedor":"' . addslashes($row->nom_usu . ' ' . $row->ape_usu) . '",
                      "facturas":"' . $row->cantidadfacturas . '",
                      "subsiniva":"'.number_format($row->subsiniva,2).'",
                      "subconiva":"'.number_format($row->subconiva,2).'",
                      "descmonto":"'.number_format($row->descmonto,2).'",
                      "montoiva":"'.number_format($row->montoiva,2).'",
                      "montototal":"'.number_format($row->montototal,2).'",
                      "tipoprecio":"' . addslashes($row->tipoprecio) . '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }


    public function reportevendedorclienteXLS(){
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $desde = $this->session->userdata("tmp_venven_desde");
        $hasta = $this->session->userdata("tmp_venven_hasta");        
        $sucursal = $this->session->userdata("tmp_venven_sucursal");        
        $vendedor = $this->session->userdata("tmp_venven_vendedor");        

        if (($desde == '') || ($hasta == '') || ($vendedor == '')){
          $desde = date("Y-m-d");
          $hasta = date("Y-m-d");
          $sucursal = 0;
          $vendedor = 0;
        }

        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        $venta = $this->facturar_model->lst_ventasvendedor_resumencliente($sucursal, $vendedor, $desde, $hasta);


        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        }

        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteVentaCliente');
        //set cell A1 content with some text
        $empresa = "";
        if ($objemp){
          $empresa = "  - EMPRESA: " . $objemp->nom_emp . "    RUC: " . $objemp->ruc_emp;
        }
        $objven = $this->Usuario_model->usu_get($vendedor);
        $strcli = 'Vendedor '. $objven->nom_usu;
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte Resumen de Venta de ' . $strcli . 
                                                           ' del ' . substr($desde,0,10) . ' al ' . substr($hasta,0,10) . $empresa);
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:L1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Cliente');
        $this->excel->getActiveSheet()->setCellValue('B3', '#Facturas');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Subtotal 0%');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Subtotal <>0%');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Descuento');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Monto IVA');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Total');        
        $this->excel->getActiveSheet()->setCellValue('H3', 'Tipo Precio');  
        $this->excel->getActiveSheet()->setCellValue('I3', 'Teléfono');
        $this->excel->getActiveSheet()->setCellValue('J3', 'Correo');
        $this->excel->getActiveSheet()->setCellValue('K3', 'Dirección');

        $this->excel->getActiveSheet()->getStyle('A3:K3')->getFont()->setBold(true);

        $total = 0;
        $montoiva = 0;
        $valortotal = 0;
        $subtotalcero = 0;
        $subtotaliva = 0;
        $descuento = 0;
        $fila = 4;
        $filaini = $fila;
        foreach ($venta as $ven) {
          if($ven->estatus != 3){
            $total += $ven->cantidadfacturas;
            $subtotalcero += $ven->subsiniva;
            $subtotaliva += $ven->subconiva;
            $montoiva += $ven->montoiva;
            $valortotal += $ven->montototal;
            $descuento += $ven->descmonto;

            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $ven->nom_cliente);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->cantidadfacturas);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, number_format($ven->subsiniva,2));
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, number_format($ven->subconiva,2));
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($ven->descmonto,2));
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($ven->montoiva,2));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($ven->montototal,2));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, $ven->tipoprecio);
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, $ven->telefonos_cliente);
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, $ven->correo_cliente);
            $this->excel->getActiveSheet()->setCellValue('K'.$fila, $ven->direccion_cliente);

            $fila++;          
          }  
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('A'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $total);
        $this->excel->getActiveSheet()->setCellValue('C'.$fila, number_format($subtotalcero,2));
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, number_format($subtotaliva,2));
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($descuento,2));
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($montoiva,2));
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($valortotal,2));

        $currencyFormat = '_(#,##0.00_);_( (#,##0.00);_( "-"??_);_(@_)';
        $priceFormat = '_(#,##0.000000_);_( (#,##0.000000);_( "-"??_);_(@_)';
        //$this->excel->getActiveSheet()->getStyle('F'.$filaini.':F'.$fila)->getNumberFormat()->setFormatCode($priceFormat);
        $this->excel->getActiveSheet()->getStyle('C'.$filaini.':G'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);

        $this->excel->getActiveSheet()->getStyle('C'.$fila.':G'.$fila)->getFont()->setBold(true);



        foreach(range('A','K') as $columnID)
        {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }


        $this->excel->getActiveSheet()->freezePane('A4');

        
        $filename='reportedetalleventa.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    public function reportevendedorproductoXLS(){
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $desde = $this->session->userdata("tmp_venven_desde");
        $hasta = $this->session->userdata("tmp_venven_hasta");        
        $sucursal = $this->session->userdata("tmp_venven_sucursal");        
        $vendedor = $this->session->userdata("tmp_venven_vendedor");        

        if (($desde == '') || ($hasta == '') || ($vendedor == '')){
          $desde = date("Y-m-d");
          $hasta = date("Y-m-d");
          $sucursal = 0;
          $vendedor = 0;
        }

        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        $venta = $this->facturar_model->lst_ventasvendedor_resumenproducto($sucursal, $vendedor, $desde, $hasta);


        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        }

        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteVentaProducto');
        //set cell A1 content with some text
        $empresa = "";
        if ($objemp){
          $empresa = "  - EMPRESA: " . $objemp->nom_emp . "    RUC: " . $objemp->ruc_emp;
        }
        $objven = $this->Usuario_model->usu_get($vendedor);
        $strcli = 'Vendedor '. $objven->nom_usu;
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte Resumen de Venta de ' . $strcli . 
                                                           ' del ' . substr($desde,0,10) . ' al ' . substr($hasta,0,10) . $empresa);
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:L1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('B3', 'U.M.');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Cantidad');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Precio');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Tipo');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Subtotal');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Descuento');        
        $this->excel->getActiveSheet()->setCellValue('H3', 'Monto IVA');  
        $this->excel->getActiveSheet()->setCellValue('I3', 'Total');

        $this->excel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);

        $montoiva = 0;
        $valortotal = 0;
        $subtotal = 0;
        $descuento = 0;
        $fila = 4;
        $filaini = $fila;
        foreach ($venta as $ven) {
          if($ven->estatus != 3){
            $subtotal += $ven->subtotal;
            $montoiva += $ven->montoiva;
            $valortotal += $ven->valortotal;
            $descuento += $ven->descmonto;

            $precio = 0;
            if ($row->cantidad != 0){
              $precio = round($row->subtotal / $row->cantidad, 6);
            }

            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $ven->descripcion);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->unidadmedida);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, number_format($ven->cantidad,$decimalescantidad));
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, number_format($precio,$decimalesprecio));
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, $ven->tipoprecio);
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($ven->subtotal,2));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($ven->descmonto,2));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($ven->montoiva,2));
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($ven->valortotal,2));

            $fila++;          
          }  
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('E'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($subtotal,2));
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($descuento,2));
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($montoiva,2));
        $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($valortotal,2));

        $currencyFormat = '_(#,##0.00_);_( (#,##0.00);_( "-"??_);_(@_)';
        $priceFormat = '_(#,##0.000000_);_( (#,##0.000000);_( "-"??_);_(@_)';
        $this->excel->getActiveSheet()->getStyle('C'.$filaini.':C'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->getStyle('D'.$filaini.':D'.$fila)->getNumberFormat()->setFormatCode($priceFormat);
        $this->excel->getActiveSheet()->getStyle('F'.$filaini.':I'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);

        $this->excel->getActiveSheet()->getStyle('C'.$fila.':I'.$fila)->getFont()->setBold(true);



        foreach(range('A','I') as $columnID)
        {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }


        $this->excel->getActiveSheet()->freezePane('A4');

        
        $filename='reportedetalleventa.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    public function reportevendedorresumenXLS(){
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $desde = $this->session->userdata("tmp_venven_desde");
        $hasta = $this->session->userdata("tmp_venven_hasta");        
        $sucursal = $this->session->userdata("tmp_venven_sucursal");        

        if (($desde == '') || ($hasta == '') || ($sucursal == '')){
          $desde = date("Y-m-d");
          $hasta = date("Y-m-d");
          $sucursal = 0;
        }

        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        $venta = $this->facturar_model->lst_ventasvendedor_resumen($sucursal, $desde, $hasta);


        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        }

        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteVentaCliente');
        //set cell A1 content with some text
        $empresa = "";
        if ($objemp){
          $empresa = "  - EMPRESA: " . $objemp->nom_emp . "    RUC: " . $objemp->ruc_emp;
        }
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte Resumen de Venta de Vendedores' . 
                                                           ' del ' . substr($desde,0,10) . ' al ' . substr($hasta,0,10) . $empresa);
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:L1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Vendedor');
        $this->excel->getActiveSheet()->setCellValue('B3', '#Facturas');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Subtotal 0%');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Subtotal <>0%');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Descuento');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Monto IVA');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Total');        
        $this->excel->getActiveSheet()->setCellValue('H3', 'Tipo Precio');  

        $this->excel->getActiveSheet()->getStyle('A3:H3')->getFont()->setBold(true);

        $total = 0;
        $montoiva = 0;
        $valortotal = 0;
        $subtotalcero = 0;
        $subtotaliva = 0;
        $descuento = 0;
        $fila = 4;
        $filaini = $fila;
        foreach ($venta as $ven) {
          if($ven->estatus != 3){
            $total += $ven->cantidadfacturas;
            $subtotalcero += $ven->subsiniva;
            $subtotaliva += $ven->subconiva;
            $montoiva += $ven->montoiva;
            $valortotal += $ven->montototal;
            $descuento += $ven->descmonto;

            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $ven->nom_usu . ' ' . $ven->ape_usu);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->cantidadfacturas);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, number_format($ven->subsiniva,2));
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, number_format($ven->subconiva,2));
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($ven->descmonto,2));
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($ven->montoiva,2));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($ven->montototal,2));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, $ven->tipoprecio);

            $fila++;          
          }  
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('A'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $total);
        $this->excel->getActiveSheet()->setCellValue('C'.$fila, number_format($subtotalcero,2));
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, number_format($subtotaliva,2));
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($descuento,2));
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($montoiva,2));
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($valortotal,2));

        $currencyFormat = '_(#,##0.00_);_( (#,##0.00);_( "-"??_);_(@_)';
        $priceFormat = '_(#,##0.000000_);_( (#,##0.000000);_( "-"??_);_(@_)';
        //$this->excel->getActiveSheet()->getStyle('F'.$filaini.':F'.$fila)->getNumberFormat()->setFormatCode($priceFormat);
        $this->excel->getActiveSheet()->getStyle('C'.$filaini.':G'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);

        $this->excel->getActiveSheet()->getStyle('C'.$fila.':G'.$fila)->getFont()->setBold(true);



        foreach(range('A','H') as $columnID)
        {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }


        $this->excel->getActiveSheet()->freezePane('A4');

        
        $filename='reportevendedorresumen.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    public function mostrar_existenciaproducto() {
        $almacenes = $this->Almacen_model->sel_alm(); 
        $data["almacenes"] = $almacenes;
        $data["base_url"] = base_url();
        $this->load->view("factura_existenciaproducto", $data);        
    }    

    public function tmp_almexis_factura() {
        $this->session->unset_userdata("tmp_almexis_factura"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_almexis_factura", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_almexis_factura", $id); } 
        $arr['resu'] = $id;
        print json_encode($arr);
    }

    /* CARGA DE DATO AL DATATABLE */
    public function lst_ExistenciaProducto() {
      $idalmacen = $this->session->userdata("tmp_almexis_factura");
      if ($idalmacen == '') { $idalmacen = 0; }
      $registro = $this->Inventario_model->lst_existenciaproducto_almacen($idalmacen);
      $tabla = "";
      foreach ($registro as $row) {
          $tabla.='{"codbarra":"' . addslashes($row->pro_codigobarra) . '",
                    "codauxiliar":"' . addslashes($row->pro_codigoauxiliar) . '",
                    "nombre":"' . addslashes($row->pro_nombre) . '",
                    "preciocompra":"' . $row->pro_preciocompra . '",
                    "existencia":"' . $row->existencia . '",   
                    "nombrecorto":"' . addslashes($row->nombrecorto) . '"},';
                    
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';

    }

    public function mostrar_datos_adicionales(){

      $data["base_url"] = base_url();
     
      $this->load->view("venta_datoadicional_edit", $data); 
    }

    public function lstDatoadicional() {
      $idusu = $this->session->userdata("sess_id");
      $registro = $this->facturar_model->lst_venta_datoadicional_tmp($idusu);
      $tabla = "";
      foreach ($registro as $row) {
          $dato = '<div ><input type=\"text\" style=\"width: 300px;\" class=\"col-md-12 text-left form-control upd_datoadic\" id=\"'.$row->id_config.'\" value=\"'.addslashes($row->datoadicional).'\" ></div>';

          $tabla.='{
                    "dato":"' . $dato . '",
                    "nombre":"' . addslashes($row->nombre_datoadicional) . '"
                    },';
                    
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';

    }

    public function upd_datoadicional_tmp() {
        $idusu = $this->session->userdata("sess_id");
        $id = $this->input->post("id");
        $valor = $this->input->post("valor");
        $this->facturar_model->upd_datoadicional_tmp($idusu, $id, $valor);
        $arr['resu'] = 1;
        print json_encode($arr);
    }

}
?>