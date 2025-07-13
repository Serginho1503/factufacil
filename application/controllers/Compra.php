<?php
/*------------------------------------------------
  ARCHIVO: Compra.php
  DESCRIPCION: Contiene los métodos relacionados con la Compra.
  FECHA DE CREACIÓN: 13/07/2017
  ------------------------------------------------ */


defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class Compra extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Compra_model");
        $this->load->Model("Unidades_model");
        $this->load->Model("Parametros_model");
        $this->load->Model("Comanda_model");
        $this->load->Model("Cajachica_model");
        $this->load->Model("Inventario_model");
        $this->load->Model("Retencion_model");
        $this->load->Model("Sucursal_model");
        $this->load->Model("Puntoemision_model");
        $this->load->Model("Empresa_model");
        $this->load->Model("Proveedor_model");
        $this->load->Model("Producto_model");

        $this->request = json_decode(file_get_contents('php://input'));
    }

    public function agregar(){
        $idusu = $this->session->userdata("sess_id");
        $tmpcomp = $this->Compra_model->ini_temp($idusu, 1);
        $idtmp_comp = $tmpcomp->id_tmp_comp;
        /* SE CREA UNA VARIABLE DE SESION PARA PROCESOS POSTERIORES A LA COMPRA */
        $this->session->unset_userdata("tmp_compra"); 
        $this->session->set_userdata("tmp_compra", NULL);
        if ($idtmp_comp != NULL) { $this->session->set_userdata("tmp_compra", $idtmp_comp); } 
        else { $this->session->set_userdata("tmp_compra", NULL); }
        /* ========================================================================== */
        $detcomp = $this->Compra_model->compra_det($idtmp_comp);
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $proveedor = $this->Compra_model->lst_proveedor();
        $unimed = $this->Unidades_model->sel_unidad();
        $precompra = $this->Compra_model->actualiza_montos($idtmp_comp);

        $catgastos = $this->Compra_model->categorialst();
        $tmpidsuc = 0;
        if ($tmpcomp->id_sucursal) { $tmpidsuc = $tmpcomp->id_sucursal; }
        $almacenes = $this->Compra_model->lst_almacen($tmpidsuc);        

        $lstcaja = $this->Cajachica_model->lst_cajachica_sucursal($tmpidsuc);
        $caja = 0;
        if (count($lstcaja) > 0) {$caja = $lstcaja[0]->id_caja; }
        $cajac = $this->Cajachica_model->cajachica_resumen($caja);
        if ($cajac){
            $dis_caja = $cajac->resumen;
        } else {
            $dis_caja = 0;
        }

        $sritc = $this->Compra_model->sel_sri_tipo_doc();
        $data["sritc"] = $sritc;

        $srist = $this->Compra_model->sel_sri_sust_trib();
        $data["srist"] = $srist; 

        $data["caja"] = $dis_caja;               

        $data["decimalesprecio"] = $this->Parametros_model->sel_decimalesprecio();    
        $data["decimalescantidad"] = $this->Parametros_model->sel_decimalescantidad();    

        $data["modificacion"] = 0;

        $data["tmpcomp"] = $tmpcomp;
        $data["sucursales"] = $sucursales;
        $data["proveedor"] = $proveedor;
        $data["unimed"] = $unimed;
        $data["detcomp"] = $detcomp;
        $data["precompra"] = $precompra;
        $data["catgastos"] = $catgastos;
        $data["almacenes"] = $almacenes;
        $data["base_url"] = base_url();
        $data["content"] = "compra_add";
        $this->load->view("layout", $data);        

    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {

        date_default_timezone_set("America/Guayaquil");

        $desde = $this->session->userdata("tmp_comp_desde");
        $hasta = $this->session->userdata("tmp_comp_hasta");        
        $emp = $this->session->userdata("tmp_comp_emp");        
        $suc = $this->session->userdata("tmp_comp_suc");        

        //$desde = $this->session->userdata("tmp_comp_desdepro");
        //$hasta = $this->session->userdata("tmp_comp_hastapro");        
        $sucpro = $this->session->userdata("tmp_comp_sucpro");        
        $prov = $this->session->userdata("tmp_comp_prov");        
        $prod = 0;//$this->session->userdata("tmp_comp_prod");        

        if ($desde == ''){
            $desde = date("Y-m-d"); 
            $hasta = date("Y-m-d"); 
            $emp = 0;
            $suc = 0;

            $sucpro = 0;
            $prov = 0;
            $prod = 0;
        }    


        $totalg = $this->Compra_model->compra_total_rango($desde, $hasta, $emp);

        $this->session->set_userdata("tmp_comp_desde", NULL);
        if ($desde != NULL) {
            $this->session->set_userdata("tmp_comp_desde", $desde);
        } else {
            $this->session->set_userdata("tmp_comp_desde", NULL);
        }

        $this->session->set_userdata("tmp_comp_hasta", NULL);
        if ($hasta != NULL) {
            $this->session->set_userdata("tmp_comp_hasta", $hasta);
        } else {
            $this->session->set_userdata("tmp_comp_hasta", NULL);
        }

        $this->session->set_userdata("tmp_comp_emp", NULL);
        if ($emp != NULL) {
            $this->session->set_userdata("tmp_comp_emp", $emp);
        } else {
            $this->session->set_userdata("tmp_comp_emp", NULL);
        }
        $this->session->set_userdata("tmp_comp_suc", NULL);
        if ($suc != NULL) {
            $this->session->set_userdata("tmp_comp_suc", $suc);
        } else {
            $this->session->set_userdata("tmp_comp_suc", NULL);
        }

        $this->session->set_userdata("tmp_comp_desdepro", NULL);
        if ($desde != NULL) {
            $this->session->set_userdata("tmp_comp_desdepro", $desde);
        } else {
            $this->session->set_userdata("tmp_comp_desdepro", NULL);
        }

        $this->session->set_userdata("tmp_comp_hastapro", NULL);
        if ($hasta != NULL) {
            $this->session->set_userdata("tmp_comp_hastapro", $hasta);
        } else {
            $this->session->set_userdata("tmp_comp_hastapro", NULL);
        }
        $this->session->set_userdata("tmp_comp_sucpro", NULL);
        if ($sucpro != NULL) {
            $this->session->set_userdata("tmp_comp_sucpro", $sucpro);
        } else {
            $this->session->set_userdata("tmp_comp_sucpro", NULL);
        }
        $this->session->set_userdata("tmp_comp_prov", NULL);
        if ($prov != NULL) {
            $this->session->set_userdata("tmp_comp_prov", $prov);
        } else {
            $this->session->set_userdata("tmp_comp_prov", NULL);
        }
        $this->session->set_userdata("tmp_comp_prod", NULL);
        if ($prod != NULL) {
            $this->session->set_userdata("tmp_comp_prod", $prod);
        } else {
            $this->session->set_userdata("tmp_comp_prod", NULL);
        }

        $empresas = $this->Empresa_model->lst_empresa(); 
        $data["empresas"] = $empresas;
        $sucursales = $this->Sucursal_model->lst_sucursales(); 
        $data["sucursales"] = $sucursales;
        $proveedores = $this->Proveedor_model->sel_prov(); 
        $data["proveedores"] = $proveedores;

        $data["desde"] = $desde;
        $data["hasta"] = $hasta;

        
        $data["base_url"] = base_url();
        $data["content"] = "compra";
        $data["totalg"] = $totalg;
        $this->load->view("layout", $data);
    }

    /* MOSTRAR VENTANA DE PRODUCTOS */
    public function add_procompra() {
        $data["base_url"] = base_url();
        $this->load->view("compra_producto", $data);        
    }

    /* CARGA DE DATO AL DATATABLE */
    public function lstProCompra() {
      $idusu = $this->session->userdata("sess_id");
      $tmpcomp = $this->Compra_model->ini_temp($idusu);
      $almacen = 0;
      if ($tmpcomp){
          if ($tmpcomp->id_almacen) { $almacen = $tmpcomp->id_almacen; }        
      }  

      $registro = $this->Compra_model->lst_producto($almacen);
      $tabla = "";
      foreach ($registro as $row) {
          $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Añadir\" id=\"'.$row->id_pro.'\" class=\"btn btn-success btn-xs btn-grad addprocompra\"><i class=\"fa fa-cart-plus\"></i></a>  </div>';
          $tabla.='{"codbarra":"' . addslashes($row->pro_codigobarra) . '",
                    "codauxiliar":"' . addslashes($row->pro_codigoauxiliar) . '",
                    "nombre":"' . addslashes($row->pro_nombre) . '",
                    "preciocompra":"' . $row->pro_preciocompra . '",
                    "existencia":"' . $row->existencia . '",   
                    "nombrecorto":"' . addslashes($row->nombrecorto) . '",                                                               
                    "ver":"'.$ver.'"},';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';

    }

    /* SESION TEMPORAL PARA CARGAR COMPRA PRODUCTO */
     public function tmp_procomp() {
        $this->session->unset_userdata("tmp_compra_pro"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_compra_pro", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_compra_pro", $id); } 
        else { $this->session->set_userdata("tmp_compra_pro", NULL); }
        $arr['resu'] = $id;
        print json_encode($arr);
    }

    /* AGREGA Y OBTIENE LOS DATOS DEL PRODUCTO Y LOS CARGA A LA TABLA  */
    public function actualiza_compra(){
        $idpro = $this->session->userdata("tmp_compra_pro");
        $idtmp_comp = $this->session->userdata("tmp_compra");

        $data["decimalesprecio"] = $this->Parametros_model->sel_decimalesprecio();    
        $data["decimalescantidad"] = $this->Parametros_model->sel_decimalescantidad();    

        $habserie = $this->Parametros_model->sel_numeroserie();
        $data["habilitaserie"] = $habserie->valor;


        if($idpro != ""){
            /* Busqueda e insercion del producto en la tabla pedido detalle */
            $unimed = $this->Unidades_model->sel_unidad();
            $data["unimed"] = $unimed;
            $detcomp = $this->Compra_model->compra_detalle($idpro, $idtmp_comp);
            $this->session->unset_userdata("tmp_compra_pro");
            $data["detcomp"] = $detcomp;
            $data["base_url"] = base_url();
            $this->load->view("compra_tabla", $data); 
    
          
        }else{
            die;
        }

    }

    /* OBTIENE LOS DATOS DEL PRODUCTO Y LOS CARGA A LA TABLA */
    public function actualiza_tabla_compra(){
        $data["decimalesprecio"] = $this->Parametros_model->sel_decimalesprecio();    
        $data["decimalescantidad"] = $this->Parametros_model->sel_decimalescantidad();

        $habserie = $this->Parametros_model->sel_numeroserie();
        $data["habilitaserie"] = $habserie->valor;


        $idtmp_comp = $this->session->userdata("tmp_compra");
        $idpro = $this->session->userdata("tmp_compra_pro");
        //$unimed = $this->Unidades_model->sel_unidadprod($idpro);
        if($idpro != ""){ 
          $detcomp = $this->Compra_model->compra_detalle($idpro, $idtmp_comp);
          $this->session->unset_userdata("tmp_compra_pro");
        }
        $tmpcomp = $this->Compra_model->valdescmonto($idtmp_comp);
        $detcomp = $this->Compra_model->compra_det($idtmp_comp);
        //$data["unimed"] = $unimed; 
        $data["tmpcomp"] = $tmpcomp;   
        $data["detcomp"] = $detcomp;
        $data["base_url"] = base_url();
        $this->load->view("compra_tabla", $data);            
    }

    /* ACTUALIZA EL PRECIO DE COMPRA EN LA TABLA TEMPORAL */
    public function upd_preciocompra(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $iddet = $this->input->post("id");
        $precio = $this->input->post("precio");
        $precio = str_replace(',', '', $precio); 
        $subtotal = $this->input->post("subtotal");
        $subtotal = str_replace(',', '', $subtotal); 
        $montoiva = $this->input->post("montoiva");
        $montoiva = str_replace(',', '', $montoiva); 
      //  if($precio > 0){
           $upd = $this->Compra_model->upd_preciocompra($idtmp_comp, $iddet, $precio, $montoiva, $subtotal);
      //  }
        //$arr['resu'] = $upd;
        $arr['val'] = $upd;
        print json_encode($arr);
    }

    /* ACTUALIZA LA CANTIDAD DE COMPRA EN LA TABLA TEMPORAL */
    public function upd_cantidad(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $iddet = $this->input->post("id");
        $cantidad = $this->input->post("cantidad");
        $subtotal = $this->input->post("subtotal");
        $subtotal = str_replace(',', '', $subtotal); 
        $montoiva = $this->input->post("montoiva");
    //    if($cantidad > 0){
           $upd = $this->Compra_model->upd_cantidad($idtmp_comp, $iddet, $cantidad, $montoiva, $subtotal);
    //    }
          // print_r($upd); die;
        $arr['resu'] = $upd;
        print json_encode($arr);
    }


    /* ACTUALIZA LA UNIDAD MEDIDA DE COMPRA EN LA TABLA TEMPORAL */
    public function upd_unidadmedida(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $iddet = $this->input->post("id");
        $unidadmedida = $this->input->post("unidadmedida");
        $this->Compra_model->upd_unidadmedida($idtmp_comp, $iddet, $unidadmedida);
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    /* ACTUALIZA EL IVA DE COMPRA EN LA TABLA TEMPORAL */
    public function upd_iva(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $iddet = $this->input->post("id");
        $esiva = $this->input->post("esiva");
        $subtotal = $this->input->post("subtotal");
        $montoiva = $this->input->post("montoiva");
        $upd = $this->Compra_model->upd_iva($idtmp_comp, $iddet, $esiva, $montoiva, $subtotal);
        $arr['resu'] = $upd;
        print json_encode($arr);
    }

    /* ELIMINA EL PRODUCTO DE COMPRA EN LA TABLA TEMPORAL */
    public function del_compra(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $iddet = $this->input->post("id");
        $delprocompra = $this->Compra_model->quitar_procompra($idtmp_comp, $iddet);
        $arr['resu'] = 1;
        print json_encode($arr);

    }    

    /* ACTUALIZA EL MONTO DE LA COMPRA DESDE LA BASE DE DATOS */
    public function upd_pcompra(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $precompra = $this->Compra_model->actualiza_montos($idtmp_comp);
        $arr['resu'] = $precompra;
        print json_encode($arr);
    }    

    /* APLICAR DESCUENTO A PRODUCTOS DE COMPRA */
    public function upd_descuento(){
      $idtmp_comp = $this->session->userdata("tmp_compra");
      $desc = $this->input->post("descuento");
      $montoice = $this->input->post("montoice");
      $sql = $this->Compra_model->descuento($idtmp_comp, $desc, $montoice);
      $arr['dat'] = 1;
      print json_encode($arr);

    }

    /* CALCULO CON DESCUENTO */
    public function desc_compra(){
      $idtmp_comp = $this->session->userdata("tmp_compra");
      $this->Compra_model->cal_desc($idtmp_comp);
      $val = $this->Compra_model->actualiza_montos($idtmp_comp);
      $arr['res'] = $val;
      print json_encode($arr);
    }

    /* ACTUALIZAR PROVEEDOR EN LA TABLA TEMPORAL DE COMPRA*/
    public function upd_proveedor(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $idproveedor = $this->input->post('idproveedor');
        $provee = $this->Compra_model->upd_proveedor($idproveedor, $idtmp_comp);
        $arr['val'] = $idtmp_comp;
        print json_encode($arr);
    } 

    public function upd_tipodoc(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $codtipodoc = $this->input->post('codtipodoc');
        $provee = $this->Compra_model->upd_codtipodoc($codtipodoc, $idtmp_comp);
        $arr['val'] = $idtmp_comp;
        print json_encode($arr);
    } 

    public function upd_sustributario(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $codsustributario = $this->input->post('codsustributario');
        $provee = $this->Compra_model->upd_codsustributario($codsustributario, $idtmp_comp);
        $arr['val'] = $idtmp_comp;
        print json_encode($arr);
    } 


    /* ACTUALIZAR FACTURA EN LA TABLA TEMPORAL DE COMPRA*/
    public function upd_factura(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $factura = $this->input->post('factura');
        $provee = $this->Compra_model->upd_factura($factura, $idtmp_comp);
        $arr['val'] = $idtmp_comp;
        print json_encode($arr);
    } 

    /* ACTUALIZAR AUTORIZACION EN LA TABLA TEMPORAL DE COMPRA*/
    public function upd_autorizacion(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $autorizacion = $this->input->post('autorizacion');
        $provee = $this->Compra_model->upd_autorizacion($autorizacion, $idtmp_comp);
        $arr['val'] = $idtmp_comp;
        print json_encode($arr);
    } 

    /* ACTUALIZAR FORMA DE PAGO EN LA TABLA TEMPORAL DE COMPRA*/
    public function upd_formapago(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $formapago = $this->input->post('formapago');
        $provee = $this->Compra_model->upd_formapago($formapago, $idtmp_comp);
        $arr['val'] = $idtmp_comp;
        print json_encode($arr);
    } 


    /* ACTUALIZAR Datos Docum Modificado EN LA TABLA TEMPORAL DE COMPRA*/
    public function upd_documento_modificado(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $tipodoc = $this->input->post('tipodoc');
        $numdocmod = $this->input->post('numdocmod');
        $autodocmod = $this->input->post('autodocmod');
        $provee = $this->Compra_model->upd_documento_modificado($idtmp_comp, $tipodoc, $numdocmod, $autodocmod);
        $arr['val'] = $idtmp_comp;
        print json_encode($arr);
    } 

    /* GUARDAR COMPRA */
    public function add_compra(){
        date_default_timezone_set("America/Guayaquil");
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $fec = $this->input->post("fecha");
        $fecha = str_replace('/', '-', $fec); 
        $fecha = date("Y-m-d", strtotime($fecha));
        $formapago = $this->input->post("formapago");
        $efectivo = $this->input->post("efectivo");
        $tarjeta = $this->input->post("tarjeta");
        $cambio  = $this->input->post("cambio");
        $dias = $this->input->post("dias");
        $cajachica = $this->input->post("cajachica");
        $categoria = $this->input->post("categoria");
        $almacen = $this->input->post("almacen");
        
        if($formapago == 'Contado'){
          $dif = $efectivo - $cambio;
          if($dif < 0){
            $efectivo = 0;
            $tarjeta = $tarjeta - $dif;
          }else{
            $efectivo = $dif;
          }
        }else{
            $efectivo = 0;            
            $tarjeta = 0;            
        }        
        $idcompra = $this->Compra_model->guardar_compra($idtmp_comp, $fecha, $formapago, $efectivo, $tarjeta, $cambio, $dias, $cajachica, $categoria, $almacen);
        if ($idcompra != 0){
            $varcompra = $this->Compra_model->lst_detcompraparakardex($idcompra);    
            foreach ($varcompra as $cp) {
                $this->Inventario_model->ins_kardexingreso($cp->id_pro, $cp->nro_factura, 'FACTURA DE COMPRA', 
                                                           $cp->cantidadcompra, $cp->precio_compra, $cp->descsubtotal, 
                                                           $cp->pro_idunidadmedida, $cp->id_almacen);
            }    
            $varserie = $this->Compra_model->lst_detcompraparakardexserie($idcompra);
            foreach ($varserie as $det) {
                $this->Inventario_model->ins_seriekardexingreso($det->id_serie, $det->id_almacen, $det->tipomovimiento, 
                                                                $idcompra, $det->nro_factura, $det->fecha, $det->descripcion);                
            }    

            $objfac = $this->Compra_model->busca_compra($idcompra);
            $objsuc = $this->Sucursal_model->sel_suc_id($objfac->id_sucursal);
            $contabilizar = $objsuc->contabilizacion_automatica; 
            $arr['contabilizar'] = $contabilizar;
            $arr['nuevoid'] = $idcompra;

        }
        $arr['val'] = $idtmp_comp;       
        print json_encode($arr);        

    }


/* ====================================================================================== */
    public function listadoDataComp() {

        $desde = $this->session->userdata("tmp_comp_desde");
        $hasta = $this->session->userdata("tmp_comp_hasta");
        $empresa = $this->session->userdata("tmp_comp_emp");
        $sucursal = $this->session->userdata("tmp_comp_suc");
        if ($empresa == '') {$empresa = 0;}
        if ($sucursal == '') {$sucursal = 0;}
        $registro = $this->Compra_model->compra_rpt($desde, $hasta, $sucursal);
        $tabla = "";
        foreach ($registro as $row) {

          $consumidorfinal = (($row->id_proveedor == 1) || (substr($row->nro_ide_proveedor,0,10) == '9999999999')) ? '1' : '0';

          if($row->formapago == "2" && $row->estatus <> 3){ /* compra a creditos habilitar abonos */
                $tipcomp = '<a href=\"#\" title=\"Abonos\" id=\"'.$row->id_comp.'\" class=\"btn btn-warning btn-xs btn-grad edit_abono\"><i class=\"fa fa-plus\"></i></a>';
          } else { $tipcomp = ""; }

          if( $row->estatus <> 3 ){ /* edicion */
                $tipcomp .= '<a href=\"#\" title=\"Modificar\" id=\"'.$row->id_comp.'\" class=\"btn btn-success btn-xs btn-grad edit_compra\"><i class=\"fa fa-edit\"></i></a>';
          } 

          if($row->estatus == 3){ /*esta anulada*/
            $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Imprimir Compra\" id=\"'.$row->id_comp.'\" class=\"btn bg-navy color-palette btn-xs btn-grad comp_print\"><i class=\"fa fa-print\"></i></a> '.  $tipcomp .' </div>';
          } else {
            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Anular\" id=\"'.$row->id_comp.'\" class=\"btn btn-danger btn-xs btn-grad anu_fact\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i></a> <a href=\"#\" title=\"Imprimir Compra\" id=\"'.$row->id_comp.'\" class=\"btn bg-navy color-palette btn-xs btn-grad comp_print\"><i class=\"fa fa-print\"></i></a> '.  $tipcomp .' <a href=\"#\" title=\"Retención Compra\" id=\"'.$row->id_comp.'\" name=\"'.$consumidorfinal.'\" class=\"btn bg-green color-palette btn-xs btn-grad ret_comp\"><i class=\"fa fa-registered\"></i></a> </div>';
          }    
               
          $tabla.='{"estatus":"' . $row->estatus . '",
                      "id":"' . $row->id_comp . '",
                      "proveedor":"' . $row->nom_proveedor . '",
                      "fecha":"' . $row->fecha . '",
                      "factura":"' . $row->nro_factura . '",
                      "monto":"' . $row->montototal . '",   
                      "formapago":"' . $row->nom_cancelacion . '",                                                               
                      "categoria":"' . $row->nom_cla . '",                                                               
                      "estado":"' . $row->estado . '",                                                               
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }  

    public function imprimir() {

        $strprint = $this->input->post('txt_imprimir');

        $printer="";
        $objprinter = $this->Parametros_model->impresorafactura_get();
        if ($objprinter != null){
            $objcom = $this->Comanda_model->sel_com_id($objprinter->valor);

            $printer= $objcom->impresora;//"EPSONT50";

            $enlace=printer_open($printer);

            printer_write($enlace, $strprint);

            printer_close($enlace);
        }
        
    }


    /* ABRIR VENTANA PARA Imprimir Compra */
    public function imprimircompra(){
        $factoriva = 12;
        date_default_timezone_set("America/Guayaquil");
        
        $idcompra = $this->input->post('id');

        $tabla ="\r\n" . "FACTURA DE COMPRA". "\r\n";
        $strdate=date("d/m/Y H:i");
        $tabla.="Fecha:" . "\x1F \x1F" . $strdate . "\r\n";
        $registro = $this->Compra_model->datosproveedor($idcompra);
        foreach ($registro as $row) {
            $tabla.="Proveedor:" . "\x1F \x1F" . $row->nom_proveedor . "\r\n";        
            $tabla.="Direccion:". "\x1F \x1F" . $row->direccion_proveedor . "\r\n";        
            $tabla.="CI/RUC:". "\x1F \x1F" . $row->nro_ide_proveedor . "\r\n";        
            $tabla.="Telef.:". "\x1F \x1F" . $row->telf_proveedor . "\r\n";        
        }    
        $tabla.= "\r\n";
        $tabla.="CAN   DESCRIPCION               P.COMPRA  SUBTOTAL  DESCUENTO  SUBTOTAL/DESC". "\r\n";        
        $registro = $this->Compra_model->compradetalle($idcompra);
        $subtotaliva=0;
        $subtotalcero=0;
        $subtotaldiva=0;
        $subtotaldcero=0;
        $montoiva=0;
        $descuento=0;
        $montoice=0;
        foreach ($registro as $row) {
            $montoice=$row->montoice;
            $strcant = $row->cantidad;
            $tabla.= $strcant;
            $lcant = strlen($strcant);
            while ($lcant < 6){
                $tabla.= "\x1F \x1F";
                $lcant++;
            }
            $strnombre = substr($row->pro_nombre,0,25);
            $tabla.= $strnombre;
            $lcant = strlen($strnombre);
            while ($lcant < 26){
                $tabla.= "\x1F \x1F";
                $lcant++;
            }
            if ($row->iva == 1){
                $subtotaliva+= $row->subtotal;
                $subtotaldiva+= $row->descsubtotal;
                $montoiva+= $row->montoiva;    
            }
            else{
                $subtotalcero+= $row->subtotal;
                $subtotaldcero+= $row->descsubtotal;
            }
            $descuento+=$row->descmonto;

            $strprecio=number_format($row->precio_compra,2);
            $tabla.= $strprecio;
            $lcant = strlen($strprecio);
            while ($lcant < 10){
                $tabla.= "\x1F \x1F";
                $lcant++;
            }

            $strprecio=number_format($row->subtotal,2);
            $tabla.= $strprecio;
            $lcant = strlen($strprecio);
            while ($lcant < 10){
                $tabla.= "\x1F \x1F";
                $lcant++;
            }

            $strprecio=number_format($row->descmonto,2);
            $tabla.= $strprecio;
            $lcant = strlen($strprecio);
            while ($lcant < 11){
                $tabla.= "\x1F \x1F";
                $lcant++;
            }

            $tabla.= number_format($row->descsubtotal,2) . "\r\n";
        }
        $montoiva += round($montoice * 0.12, 2);
        $totalpagar = $subtotaldiva + $subtotaldcero + $montoiva + $montoice;
        $tabla.= "\r\n";
        $tabla.= "\r\n";
        $tabla.= "   SUBTOTAL  IVA12:" . "\x1F \x1F" . number_format($subtotaliva,2) . "\r\n";
        $tabla.= "   SUBTOTAL   IVA0:" . "\x1F \x1F" . number_format($subtotalcero,2) . "\r\n";
        $tabla.= "   DESCUENTO      :" . "\x1F \x1F" . number_format($descuento,2) . "\r\n";
        $tabla.= "   SUBTOTDES IVA12:" . "\x1F \x1F" . number_format($subtotaldiva,2) . "\r\n";
        $tabla.= "   SUBTOTDES  IVA0:" . "\x1F \x1F" . number_format($subtotaldcero,2) . "\r\n";
        $tabla.= "   MONTO ICE      :" . "\x1F \x1F" . number_format($montoice,2) . "\r\n";
        $tabla.= "   MONTO IVA12    :" . "\x1F \x1F" . number_format($montoiva,2) . "\r\n";
        $tabla.= "   TOTAL FACTURA  :" . "\x1F \x1F" . number_format($totalpagar,2) . "\r\n";
        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("compra_imprimir", $data);

    }

    /* ELIMINA LOS PRODUCTOS DE LA MESA */
    public function elim_compra(){
        $idusu = $this->session->userdata("sess_id"); 
        $delcomp = $this->Compra_model->delprocomp($idusu);
        $arr['resu'] = $idusu;
        print json_encode($arr);
    }

    /* Actrualiza cantidad de detalle segun numeros de serie que tenga */
    public function actualiza_cantidad_serie(){
        $iddetalle = $this->input->post('id'); 
        $this->Compra_model->actualiza_cantidad_serie($iddetalle);
        $arr['resu'] = 1;
        print json_encode($arr);
    }



    /* Exportar Compra a Excel */
    public function reportecompraXLS(){
        $desde = $this->session->userdata("tmp_comp_desde");
        $hasta = $this->session->userdata("tmp_comp_hasta");
        $suc = $this->session->userdata("tmp_comp_suc");
        $compra = $this->Compra_model->compra_rpt($desde, $hasta, $suc);
        $objsuc = $this->Sucursal_model->sel_suc_id($suc);
        $emp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        $stremp = "";
        if ($emp != null){
            $stremp = '     Empresa: '.$emp->nom_emp;
        }

        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteCompra');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Compra (' . $desde . ' - ' . $hasta . ')'.$stremp);
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:G1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Factura');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Proveedor');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Categoria');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Forma Pago');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Estado');
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
        foreach ($compra as $det) {
          if($det->estatus != 3){
            if ($det->cod_sri_tipo_doc != '04')
              $total = $total  + $det->montototal;
            else
              $total = $total  - $det->montototal;
          }  
          

          $fec = str_replace('-', '/', $det->fecha); 
          $fec = date("d/m/Y", strtotime($fec));  
          $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
          $this->excel->getActiveSheet()->setCellValue('B'.$fila, $det->nro_factura);
          $this->excel->getActiveSheet()->setCellValue('C'.$fila, $det->nom_proveedor);
          $this->excel->getActiveSheet()->setCellValue('D'.$fila, $det->nom_cla);
          $this->excel->getActiveSheet()->setCellValue('E'.$fila, $det->nom_cancelacion);
          $this->excel->getActiveSheet()->setCellValue('F'.$fila, $det->estado);
          if ($det->cod_sri_tipo_doc != '04')
              $razon = 1;
          else
              $razon = -1;
          $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($det->montototal * $razon,2));

          $fila++;          
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('F'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($total,2));
        $this->excel->getActiveSheet()->getStyle('F'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G'.$fila)->getFont()->setBold(true);

         
        $filename='reportecompra.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        ob_end_clean();
        $objWriter->save('php://output');        
    }  


    public function tmp_reporte(){
      /* fecha desde */
      $fecdesde = $this->input->post("desde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      /* fecha hasta */
      $fechasta = $this->input->post("hasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));

      $empresa = $this->input->post("empresa");
      $sucursal = $this->input->post("sucursal");
      if ($empresa == '') {$empresa = 0;}
      if ($sucursal == '') {$sucursal = 0;}

        $this->session->set_userdata("tmp_comp_desde", NULL);
        if ($desde != NULL) {
            $this->session->set_userdata("tmp_comp_desde", $desde);
        } else {
            $this->session->set_userdata("tmp_comp_desde", NULL);
        }

        $this->session->set_userdata("tmp_comp_hasta", NULL);
        if ($hasta != NULL) {
            $this->session->set_userdata("tmp_comp_hasta", $hasta);
        } else {
            $this->session->set_userdata("tmp_comp_hasta", NULL);
        }

        $this->session->set_userdata("tmp_comp_emp", NULL);
        if ($empresa != NULL) {
            $this->session->set_userdata("tmp_comp_emp", $empresa);
        } else {
            $this->session->set_userdata("tmp_comp_emp", NULL);
        }
        $this->session->set_userdata("tmp_comp_suc", NULL);
        if ($sucursal != NULL) {
            $this->session->set_userdata("tmp_comp_suc", $sucursal);
        } else {
            $this->session->set_userdata("tmp_comp_suc", NULL);
        }

      // /* Consulta la modelo pasando los parametros */
      // $compra = $this->Compra_model->compra_rpt($desde, $hasta);
      // /* Variable de sesion con la consulta*/
      // $this->session->unset_userdata("tmp_rpt_compra"); 
      // $this->session->set_userdata("tmp_rpt_compra", NULL);
      // if ($compra != NULL) { $this->session->set_userdata("tmp_rpt_compra", $compra); } 
      // else { $this->session->set_userdata("tmp_rpt_compra", NULL); }

      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function reporte(){
        $desde = $this->session->userdata("tmp_comp_desde");
        $hasta = $this->session->userdata("tmp_comp_hasta");
        $emp = $this->session->userdata("tmp_comp_emp");
        $suc = $this->session->userdata("tmp_comp_suc");
        $compra = $this->Compra_model->compra_rpt($desde, $hasta, $suc);
        $data["base_url"] = base_url();
        $data["desde"] = $desde;
        $data["hasta"] = $hasta;
        $data["compra"] = $compra;
        $this->load->view("compra_reporte", $data);     
    }    

    public function upd_compra_total(){
        $vard = $this->session->userdata("tmp_comp_desde");
        $varh = $this->session->userdata("tmp_comp_hasta");
        $suc = $this->session->userdata("tmp_comp_suc");
        /* Tratamiento de Fecha Desde */
        $fec_a = str_replace('-', '/', $vard); 
        $desde = date("Y-m-d", strtotime($fec_a)); 
        /* Tratamiento de Fecha Hasta */
        $fec_h = str_replace('-', '/', $varh); 
        $hasta = date("Y-m-d", strtotime($fec_h));   

        $totalg = $this->Compra_model->compra_total_rango($desde, $hasta, $suc);
        
        $arr['resu'] = $totalg;
        print json_encode($arr);               
    }

    /* CARGAR NOTA DE DETALLE DE COMPRA */
    public function nota_compra(){
        $id = $this->input->post("id");
        $compdet = $this->Compra_model->busca_detalle_compra($id);
        $data["compdet"] = $compdet;
        $proimei = $this->Compra_model->actualiza_imeiserie();
        $data["proimei"] = $proimei;        
        $data["base_url"] = base_url();
        $this->load->view("compra_nota", $data);
    }

    /* GURDAR NOTA */
    public function guardar_nota(){
        $iddet = $this->input->post("txt_iddet");
        $nota_pro = $this->input->post("txt_nota");
        $sql = $this->Compra_model->updpro_nota($iddet, $nota_pro);
        $arr['val'] = $iddet;
        print json_encode($arr);
    }

    public function confirmar_anulacion(){
        $id_compra = $this->input->post("id");
        $fact = $this->Compra_model->busca_compra($id_compra);

        $varcompra = $this->Compra_model->lst_detcompraparakardex($id_compra);    
        foreach ($varcompra as $cp) {
            $this->Inventario_model->ins_kardexegreso($cp->id_pro, $cp->nro_factura, 
                                                      'ANULACION FACTURA DE COMPRA', $cp->cantidadcompra, 
                                                      $cp->precio_compra, $cp->descsubtotal, 
                                                      $cp->pro_idunidadmedida, $cp->id_almacen);
        }

        $data["factura"] = $fact;
        $data["base_url"] = base_url();
        $this->load->view("compra_anular", $data);      
    }

    public function anular(){

        $id_compra = $this->input->post("txt_idcompra");
        $obs = $this->input->post("txt_obs");
        $resu = $this->Compra_model->anular_compra($id_compra, $obs);
        $arr = 1;
        print json_encode($arr); 
    }

    /* ACTUALIZAR CATEGORIA EN LA TABLA TEMPORAL DE COMPRA*/
    public function upd_categoria(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $categoria = $this->input->post('categoria');
        $provee = $this->Compra_model->upd_categoria($categoria, $idtmp_comp);
        $arr['val'] = $idtmp_comp;
        print json_encode($arr);
    } 

    /* ACTUALIZAR CATEGORIA EN LA TABLA TEMPORAL DE COMPRA*/
    public function upd_almacen(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $almacen = $this->input->post('almacen');
        $provee = $this->Compra_model->upd_almacen($almacen, $idtmp_comp);
        $arr['val'] = $idtmp_comp;
        print json_encode($arr);
    } 

    /* ACTUALIZAR sucursal EN LA TABLA TEMPORAL DE COMPRA*/
    public function upd_sucursal(){
        $idtmp_comp = $this->session->userdata("tmp_compra");
        $sucursal = $this->input->post('sucursal');
        $this->Compra_model->upd_sucursal($sucursal, $idtmp_comp);
        $almacenes = $this->Compra_model->lst_almacen($sucursal);        
        $arr['almacenes'] = $almacenes;
        print json_encode($arr);
    } 

    /* TEMPORAL PARA LAS RETENCIONES DE LAS COMPRAS */
    public function temp_compret() {
        $this->session->unset_userdata("temp_idcomp"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("temp_idcomp", NULL);
        if ($id != NULL) { $this->session->set_userdata("temp_idcomp", $id); } 
        else { $this->session->set_userdata("temp_idcomp", NULL); }
        $arr['resu'] = $id;
        print json_encode($arr);
    }    

    public function compra_retencion(){
        $idcomp = $this->session->userdata("temp_idcomp");
        $objcompra = $this->Compra_model->busca_compra($idcomp);
        if ($objcompra){
            $puntoemision = $this->Puntoemision_model->lst_puntoemisionsucursal($objcompra->id_sucursal);
            $data["puntoemision"] = $puntoemision;
        }    
        $this->Retencion_model->retencioncompra_defaultadd($idcomp);
        $this->Retencion_model->retencioncompra_cargardetalletmp($idcomp);
        $comp = $this->Compra_model->selcompret($idcomp);
        $data["comp"] = $comp;
        $data["base_url"] = base_url();
        $data["content"] = "compra_retencion";
        $this->load->view("layout", $data);           
    }

    public function add_retencion(){
        $idcomp = $this->session->userdata("temp_idcomp");
        $lstret = $this->Retencion_model->ret_lst_comp();
        $lstretiva = $this->Retencion_model->retiva_lst_porciento();
        $subtotalconiva = $this->input->post("subtotalconiva");
        $subtotalsiniva = $this->input->post("subtotalsiniva");
        $lst_subtotaldisp = $this->Retencion_model->lst_subtotalretdisp_compra(0);
        if ($lst_subtotaldisp){
            $subtotalconiva -= $lst_subtotaldisp[0]->baseiva_disp;
            $subtotalsiniva -= $lst_subtotaldisp[0]->basenoiva_disp;
        }              
        /*
        $sucursal = $this->Compra_model->busca_compra($idcomp);       
        $proxnumret = $this->Retencion_model->get_proxnumeroretencion($sucursal);       
        */
        $data["lstret"] = $lstret;        
        $data["lstretiva"] = $lstretiva; 
        /*$data["proxnumret"] = $proxnumret; */
        $data["subtotalconiva"] = $subtotalconiva;        
        $data["subtotalsiniva"] = $subtotalsiniva;        
        $data["base_url"] = base_url();
        $this->load->view("compra_retencion_add", $data);
    }

    public function listadoRet() {
        $idcomp = $this->session->userdata("temp_idcomp");
        $registro = $this->Retencion_model->lst_retenciondettmp_compra();
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

    public function editar_retencion(){
        $idcomp = $this->session->userdata("temp_idcomp");
        $id = $this->input->post("id");
        $subtotalconiva = $this->input->post("subtotalconiva");
        $subtotalsiniva = $this->input->post("subtotalsiniva");
        $lst_subtotaldisp = $this->Retencion_model->lst_subtotalretdisp_compra($id);
        if ($lst_subtotaldisp){
            $subtotalconiva -= $lst_subtotaldisp[0]->baseiva_disp;
            $subtotalsiniva -= $lst_subtotaldisp[0]->basenoiva_disp;
        }
        $lstret = $this->Retencion_model->ret_lst_comp();
        $lstretiva = $this->Retencion_model->retiva_lst_porciento();
        $retencion = $this->Retencion_model->sel_detalleretencioncompra($id);
        $data["lstret"] = $lstret;        
        $data["lstretiva"] = $lstretiva;        
        $data["retencion"] = $retencion;        
        $data["subtotalconiva"] = $subtotalconiva;        
        $data["subtotalsiniva"] = $subtotalsiniva;        
        $data["base_url"] = base_url();
        $this->load->view("compra_retencion_add", $data);
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
            $this->Retencion_model->retencionrentacompra_add($concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta);
        } else{
            $this->Retencion_model->retencionrentacompra_upd($id_ret, $concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta);
        }
        
        $tmpretenido = $this->Retencion_model->retencionrentacompra_tmpretenido();

        print json_encode($tmpretenido);
    } 

    /* GUARDAR RETENCION DE COMPRA*/
    public function guardar_retencion(){
        
        /*$idcompra = $this->session->userdata("temp_idcomp");*/
        $idretcompra = $this->input->post('txt_id_comp_ret');
        /*$id_ret = $this->input->post('txt_idret');*/
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

        $ptoemision = $this->input->post('cmb_punto');
        $nroretencion = $this->input->post('txt_factura');

/*        $concepto = $this->input->post('cmb_tip_ide');
        $basenoiva = $this->input->post('txt_basenoiva');
        $baseiva = $this->input->post('txt_baseiva');
        $montoiva = $this->input->post('txt_montoiva');
        $por100retrenta = $this->input->post('txt_p100retrenta');
        $valorretrenta = $this->input->post('txt_valorrenta');*/
/*        $p100retiva = $this->input->post('txt_p100retiva');
        $valorretiva = $this->input->post('txt_valoriva');
        $idretiva = $this->input->post('cmb_retiva');
*/
        $this->Retencion_model->retencioncompra_guardar($idretcompra, $autorizacion, $fecha, $retiva10, $retiva20, $retiva30, $retiva50, $retiva70, $retiva100, $ptoemision, $nroretencion);
        
        print json_encode(1);
    } 

    public function eliminar_retencion(){
        $id = $this->input->post("id");
        $this->Retencion_model->retencioncompra_del($id);
        print json_encode(1);
    }

    public function eliminar_retencionrenta(){
        $id = $this->input->post("id");
        $this->Retencion_model->retencionrentacompra_del($id);

        $tmpretenido = $this->Retencion_model->retencionrentacompra_tmpretenido();

        print json_encode($tmpretenido);
    }

    public function guardaimeiserie(){
        $this->session->unset_userdata("tmp_idpro"); 
        $idpro = $this->input->post('idprodet');
        $this->session->set_userdata("tmp_idpro", NULL);
        if ($idpro != NULL) { $this->session->set_userdata("tmp_idpro", $idpro); } 
        else { $this->session->set_userdata("tmp_idpro", NULL); }
        $imei = $this->input->post('imei');
        $desc = $this->input->post('desc');
        $idcom = $this->input->post('idcom');
        $iddet = $this->input->post('iddet');

        $resu = $this->Compra_model->guardaimeiserie($imei, $desc, $idcom, $iddet, $idpro);
        print json_encode($resu);                             
    }    

    public function actualiza_imeiserie(){
        $idpro = $this->session->userdata("tmp_idpro");
        $proimei = $this->Compra_model->actualiza_imeiserie();
        $data["proimei"] = $proimei;
        $data["idpro"] = $idpro;
        $this->load->view("compra_tabla_serie", $data); 
    }

    public function eliminaimeiserie(){
        $idserie = $this->input->post("id");
        $this->Compra_model->eliminaimeiserie($idserie);
        $this->session->unset_userdata("tmp_idpro"); 
        $idpro = $this->input->post('idprodet');
        $this->session->set_userdata("tmp_idpro", NULL);
        if ($idpro != NULL) { $this->session->set_userdata("tmp_idpro", $idpro); } 
        else { $this->session->set_userdata("tmp_idpro", NULL); }        
        print json_encode($idserie);   
    }

    public function sel_nroret_ptoemi(){
        $punto = $this->input->post("punto");
        $nroretencion = $this->Retencion_model->get_proxnumeroretencion($punto);      
        $arr['nroretencion'] = str_pad($nroretencion, 9, "0", STR_PAD_LEFT);
        print json_encode($arr);               
    }

    public function guardar_masivo_imei()
    {

        $this->session->unset_userdata("tmp_idpro"); 
        $idpro =  $this->request->txt_idprodet;
        $this->session->set_userdata("tmp_idpro", NULL);
        if ($idpro != NULL) { $this->session->set_userdata("tmp_idpro", $idpro); } 
        else { $this->session->set_userdata("tmp_idpro", NULL); }

        $imei = $this->request->imei;
        $desc = $this->request->descripcion;
        $idcom = $this->request->txt_idcom;
        $iddet = $this->request->txt_iddet;



        $resu = $this->Compra_model->guardaimeiserie($imei, $desc, $idcom, $iddet, $idpro);
        print json_encode($resu); 

    }

    public function list_destalle()
    {
        echo "hola";
    }

    public function tmp_reporte_prov(){
      /* fecha desde */
      $fecdesde = $this->input->post("desde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      /* fecha hasta */
      $fechasta = $this->input->post("hasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));

      $sucursal = $this->input->post("sucursal");
      if ($sucursal == '') {$sucursal = 0;}
      $proveedor = $this->input->post("proveedor");
      if ($proveedor == '') {$proveedor = 0;}
      $producto = $this->input->post("producto");
      if ($producto == '') {$producto = 0;}

        $this->session->set_userdata("tmp_comp_desdepro", NULL);
        if ($desde != NULL) {
            $this->session->set_userdata("tmp_comp_desdepro", $desde);
        } else {
            $this->session->set_userdata("tmp_comp_desdepro", NULL);
        }

        $this->session->set_userdata("tmp_comp_hastapro", NULL);
        if ($hasta != NULL) {
            $this->session->set_userdata("tmp_comp_hastapro", $hasta);
        } else {
            $this->session->set_userdata("tmp_comp_hastapro", NULL);
        }

        $this->session->set_userdata("tmp_comp_sucpro", NULL);
        if ($sucursal != NULL) {
            $this->session->set_userdata("tmp_comp_sucpro", $sucursal);
        } else {
            $this->session->set_userdata("tmp_comp_sucpro", NULL);
        }

        $this->session->set_userdata("tmp_comp_prov", NULL);
        if ($proveedor != NULL) {
            $this->session->set_userdata("tmp_comp_prov", $proveedor);
        } else {
            $this->session->set_userdata("tmp_comp_prov", NULL);
        }

        $this->session->set_userdata("tmp_comp_prod", NULL);
        if ($producto != NULL) {
            $this->session->set_userdata("tmp_comp_prod", $producto);
        } else {
            $this->session->set_userdata("tmp_comp_prod", NULL);
        }

      $arr['resu'] = 1;
      print json_encode($arr);
    }

    /* CARGA DE DATO AL DATATABLE */
    public function lstCompraProv_Detalle() {
      $desde = $this->session->userdata("tmp_comp_desdepro");
      $hasta = $this->session->userdata("tmp_comp_hastapro");        
      $sucursal = $this->session->userdata("tmp_comp_sucpro");        
      $proveedor = $this->session->userdata("tmp_comp_prov");        
      $producto = $this->session->userdata("tmp_comp_prod");        

      if ($sucursal == '') { $sucursal = 0; }
      if ($proveedor == '') { $proveedor = 0; }
      if ($producto == '') { $producto = 0; }

      $registro = $this->Compra_model->lst_compradetalles_proveedor($sucursal, $proveedor, $producto, $desde, $hasta);
      $tabla = "";
      foreach ($registro as $row) {
        $fecha = $row->fecha;    $fecha = str_replace('/', '-', $fecha);   $fecha = date("Y-m-d", strtotime($fecha));

        $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Imprimir Compra\" id=\"'.$row->id_comp.'\" class=\"btn bg-navy color-palette btn-xs btn-grad comp_print\"><i class=\"fa fa-print\"></i></a> </div>';

        $tabla.='{"ver":"'.$ver . '",
                  "fecha":"' . $fecha . '",
                  "factura":"' . addslashes($row->nro_factura) . '",
                  "tipopago":"' . addslashes($row->nom_cancelacion) . '",
                  "producto":"' . addslashes($row->pro_nombre) . '",
                  "cantidad":"' . $row->cantidad . '",
                  "unidadmedida":"' . addslashes($row->unidadmedida) . '",
                  "preciocompra":"' . $row->precio_compra . '",
                  "subtotal":"' . $row->subtotal . '",   
                  "descmonto":"' . $row->descmonto . '",                                                               
                  "montoiva":"' . $row->montoiva . '",                                                               
                  "valortotal":"' . $row->valortotal . '"},';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';

    }

    public function lstCompraProv_ResumenProducto() {
      $desde = $this->session->userdata("tmp_comp_desdepro");
      $hasta = $this->session->userdata("tmp_comp_hastapro");        
      $sucursal = $this->session->userdata("tmp_comp_sucpro");        
      $proveedor = $this->session->userdata("tmp_comp_prov");        
      $producto = $this->session->userdata("tmp_comp_prod");        

      if ($sucursal == '') { $sucursal = 0; }
      if ($proveedor == '') { $proveedor = 0; }
      if ($producto == '') { $producto = 0; }

      $registro = $this->Compra_model->lst_compra_resumenproducto($sucursal, $proveedor, $producto, $desde, $hasta);
      $tabla = "";
      foreach ($registro as $row) {
        $precio = 0;
        if ($row->cantidad != 0){
          $precio = round($row->subtotal / $row->cantidad, 6);
        }

        $tabla.='{"producto":"' . addslashes($row->pro_nombre) . '",
                  "cantidad":"' . $row->cantidad . '",
                  "unidadmedida":"' . addslashes($row->unidadmedida) . '",
                  "preciocompra":"' . $precio . '",
                  "subtotal":"' . $row->subtotal . '",   
                  "descmonto":"' . $row->descmonto . '",                                                               
                  "montoiva":"' . $row->montoiva . '",                                                               
                  "valortotal":"' . $row->valortotal . '"},';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';

    }

    public function lstCompraProv_ResumenProveedor() {
      $desde = $this->session->userdata("tmp_comp_desdepro");
      $hasta = $this->session->userdata("tmp_comp_hastapro");        
      $sucursal = $this->session->userdata("tmp_comp_sucpro");        
      $proveedor = $this->session->userdata("tmp_comp_prov");        
      $producto = $this->session->userdata("tmp_comp_prod");        

      if ($sucursal == '') { $sucursal = 0; }
      if ($proveedor == '') { $proveedor = 0; }
      if ($producto == '') { $producto = 0; }

      $registro = $this->Compra_model->lst_compra_resumenproveedor($sucursal, $producto, $desde, $hasta);
      $tabla = "";
      foreach ($registro as $row) {
        $precio = 0;
        if ($row->cantidad != 0){
          $precio = round($row->subtotal / $row->cantidad, 6);
        }

        $tabla.='{"proveedor":"' . addslashes($row->nom_proveedor) . '",
                  "cantfacturas":"' . $row->cantfacturas . '",
                  "cantidad":"' . $row->cantidad . '",
                  "unidadmedida":"' . addslashes($row->unidadmedida) . '",
                  "preciocompra":"' . $precio . '",
                  "subtotal":"' . $row->subtotal . '",   
                  "descmonto":"' . $row->descmonto . '",                                                               
                  "montoiva":"' . $row->montoiva . '",                                                               
                  "valortotal":"' . $row->valortotal . '",
                  "telefono":"' . addslashes($row->telf_proveedor) . '",
                  "correo":"' . addslashes($row->correo_proveedor) . '",
                  "direccion":"' . addslashes($row->direccion_proveedor) . '"
              },';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';

    }

    public function reportecompra_detallefacturaXLS(){
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $desde = $this->session->userdata("tmp_comp_desdepro");
        $hasta = $this->session->userdata("tmp_comp_hastapro");        
        $sucursal = $this->session->userdata("tmp_comp_sucpro");        
        $proveedor = $this->session->userdata("tmp_comp_prov");        
        $producto = $this->session->userdata("tmp_comp_prod");        

        if ($sucursal == '') { $sucursal = 0; }
        if ($proveedor == '') { $proveedor = 0; }
        if ($producto == '') { $producto = 0; }

        $venta = $this->Compra_model->lst_compradetalles_proveedor($sucursal, $proveedor, $producto, $desde, $hasta);


        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        }

        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteCompraProveedor');
        //set cell A1 content with some text
        $empresa = "";
        if ($objemp){
          $empresa = "  - EMPRESA: " . $objemp->nom_emp . "    RUC: " . $objemp->ruc_emp;
        }
        $objprov = $this->Proveedor_model->sel_provee_id($proveedor);
        $strcli = 'Proveedor "'. $objprov->nom_proveedor . '"';
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte Detalle de Compras al ' . $strcli . 
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
        $this->excel->getActiveSheet()->setCellValue('C3', 'Cancelación');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Cantidad');
        $this->excel->getActiveSheet()->setCellValue('F3', 'U.M.');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Precio');        
        $this->excel->getActiveSheet()->setCellValue('H3', 'Subtotal');  
        $this->excel->getActiveSheet()->setCellValue('I3', 'Descuento');
        $this->excel->getActiveSheet()->setCellValue('J3', 'Monto IVA');
        $this->excel->getActiveSheet()->setCellValue('K3', 'Total');

        $this->excel->getActiveSheet()->getStyle('A3:K3')->getFont()->setBold(true);

        $total = 0;
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

            $fecha = $ven->fecha;    $fecha = str_replace('/', '-', $fecha);   $fecha = date("Y-m-d", strtotime($fecha));

            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fecha);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->nro_factura);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ven->nom_cancelacion);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $ven->pro_nombre);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($ven->cantidad,$decimalescantidad));
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, $ven->unidadmedida);
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($ven->precio_compra,$decimalesprecio));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($ven->subtotal,2));
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($ven->descmonto,2));
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, number_format($ven->montoiva,2));
            $this->excel->getActiveSheet()->setCellValue('K'.$fila, number_format($ven->valortotal,2));

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
        $this->excel->getActiveSheet()->getStyle('G'.$filaini.':G'.$fila)->getNumberFormat()->setFormatCode($priceFormat);
        $this->excel->getActiveSheet()->getStyle('H'.$filaini.':K'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);

        $this->excel->getActiveSheet()->getStyle('G'.$fila.':K'.$fila)->getFont()->setBold(true);



        foreach(range('A','K') as $columnID)
        {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }


        $this->excel->getActiveSheet()->freezePane('A4');

        
        $filename='reportedetallecompra.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    public function reportecompra_resumenproductoXLS(){
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $desde = $this->session->userdata("tmp_comp_desdepro");
        $hasta = $this->session->userdata("tmp_comp_hastapro");        
        $sucursal = $this->session->userdata("tmp_comp_sucpro");        
        $proveedor = $this->session->userdata("tmp_comp_prov");        
        $producto = $this->session->userdata("tmp_comp_prod");        

        if ($sucursal == '') { $sucursal = 0; }
        if ($proveedor == '') { $proveedor = 0; }
        if ($producto == '') { $producto = 0; }

        $venta = $this->Compra_model->lst_compra_resumenproducto($sucursal, $proveedor, $producto, $desde, $hasta);


        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        }

        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteCompraProveedor');
        //set cell A1 content with some text
        $empresa = "";
        if ($objemp){
          $empresa = "EMPRESA: " . $objemp->nom_emp . "    RUC: " . $objemp->ruc_emp;
        }
        $objprov = $this->Proveedor_model->sel_provee_id($proveedor);
        $strcli = 'Proveedor "'. $objprov->nom_proveedor . '"';
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte Detalle de Compras al ' . $strcli . 
                                                           ' del ' . substr($desde,0,10) . ' al ' . substr($hasta,0,10) );
        $this->excel->getActiveSheet()->setCellValue('A2', $empresa);
        
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1:A2')->getFont()->setSize(14);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1:A2')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:L1');
        $this->excel->getActiveSheet()->mergeCells('A2:L2');
        //set aligment to center for that merged cell (A1 to D1)
        //$this->excel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Cantidad');
        $this->excel->getActiveSheet()->setCellValue('C3', 'U.M.');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Precio');        
        $this->excel->getActiveSheet()->setCellValue('E3', 'Subtotal');  
        $this->excel->getActiveSheet()->setCellValue('F3', 'Descuento');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Monto IVA');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Total');

        $this->excel->getActiveSheet()->getStyle('A3:H3')->getFont()->setBold(true);

        $total = 0;
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

            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $ven->pro_nombre);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, number_format($ven->cantidad,$decimalescantidad));
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ven->unidadmedida);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, number_format($precio,$decimalesprecio));
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($ven->subtotal,2));
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($ven->descmonto,2));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($ven->montoiva,2));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($ven->valortotal,2));

            $fila++;          
          }  
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('D'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($subtotal,2));
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($descuento,2));
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($montoiva,2));
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($valortotal,2));

        $currencyFormat = '_(#,##0.00_);_( (#,##0.00);_( "-"??_);_(@_)';
        $priceFormat = '_(#,##0.000000_);_( (#,##0.000000);_( "-"??_);_(@_)';
        $this->excel->getActiveSheet()->getStyle('D'.$filaini.':D'.$fila)->getNumberFormat()->setFormatCode($priceFormat);
        $this->excel->getActiveSheet()->getStyle('E'.$filaini.':H'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);

        $this->excel->getActiveSheet()->getStyle('D'.$fila.':H'.$fila)->getFont()->setBold(true);



        foreach(range('A','H') as $columnID)
        {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }


        $this->excel->getActiveSheet()->freezePane('A4');

        
        $filename='reportecompraresumenprod.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    public function reportecompra_resumenproveedorXLS(){
        date_default_timezone_set("America/Guayaquil");

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();    
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();    

        $desde = $this->session->userdata("tmp_comp_desdepro");
        $hasta = $this->session->userdata("tmp_comp_hastapro");        
        $sucursal = $this->session->userdata("tmp_comp_sucpro");        
        $proveedor = $this->session->userdata("tmp_comp_prov");        
        $producto = $this->session->userdata("tmp_comp_prod");        

        if ($sucursal == '') { $sucursal = 0; }
        if ($proveedor == '') { $proveedor = 0; }
        if ($producto == '') { $producto = 0; }

        $venta = $this->Compra_model->lst_compra_resumenproveedor($sucursal, $producto, $desde, $hasta);


        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        }

        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteCompraProveedor');
        //set cell A1 content with some text
        $empresa = "";
        if ($objemp){
          $empresa = "EMPRESA: " . $objemp->nom_emp . "    RUC: " . $objemp->ruc_emp;
        }
        $strpro = "";
        $objpro = $this->Producto_model->sel_pro_id($producto);
        if (count($objpro) > 0){
            $strpro = '"' . $objpro->pro_nombre . '"';
        }
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte resumen de compras por proveedores del producto ' . $strpro . 
                                                           ' del ' . substr($desde,0,10) . ' al ' . substr($hasta,0,10) );
        $this->excel->getActiveSheet()->setCellValue('A2', $empresa);
        
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1:A2')->getFont()->setSize(14);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1:A2')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:L1');
        $this->excel->getActiveSheet()->mergeCells('A2:L2');
        //set aligment to center for that merged cell (A1 to D1)
        //$this->excel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Proveedor');
        $this->excel->getActiveSheet()->setCellValue('B3', '#Facturas');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Cantidad');
        $this->excel->getActiveSheet()->setCellValue('D3', 'U.M.');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Precio');        
        $this->excel->getActiveSheet()->setCellValue('F3', 'Subtotal');  
        $this->excel->getActiveSheet()->setCellValue('G3', 'Descuento');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Monto IVA');
        $this->excel->getActiveSheet()->setCellValue('I3', 'Total');

        $this->excel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);

        $total = 0;
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

            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $ven->nom_proveedor);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->cantfacturas);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, number_format($ven->cantidad,$decimalescantidad));
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $ven->unidadmedida);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($precio,$decimalesprecio));
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
        $this->excel->getActiveSheet()->getStyle('E'.$filaini.':E'.$fila)->getNumberFormat()->setFormatCode($priceFormat);
        $this->excel->getActiveSheet()->getStyle('F'.$filaini.':I'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);

        $this->excel->getActiveSheet()->getStyle('E'.$fila.':I'.$fila)->getFont()->setBold(true);



        foreach(range('A','I') as $columnID)
        {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }


        $this->excel->getActiveSheet()->freezePane('A4');

        
        $filename='reportecompraresumenprov.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    public function modificar(){
        $idcomp = $this->session->userdata("temp_idcomp");
        $idusu = $this->session->userdata("sess_id");
        $tmpcomp = $this->Compra_model->cargar_compra_edicion($idcomp);
        $idtmp_comp = $tmpcomp->id_tmp_comp;
        /* SE CREA UNA VARIABLE DE SESION PARA PROCESOS POSTERIORES A LA COMPRA */
        $this->session->unset_userdata("tmp_compra"); 
        $this->session->set_userdata("tmp_compra", NULL);
        if ($idtmp_comp != NULL) { $this->session->set_userdata("tmp_compra", $idtmp_comp); } 
        else { $this->session->set_userdata("tmp_compra", NULL); }
        /* ========================================================================== */
        $detcomp = $this->Compra_model->compra_det($idtmp_comp);
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $proveedor = $this->Compra_model->lst_proveedor();
        $unimed = $this->Unidades_model->sel_unidad();
        $precompra = $this->Compra_model->actualiza_montos($idtmp_comp);

        $catgastos = $this->Compra_model->categorialst();
        $tmpidsuc = 0;
        if ($tmpcomp->id_sucursal) { $tmpidsuc = $tmpcomp->id_sucursal; }
        $almacenes = $this->Compra_model->lst_almacen($tmpidsuc);        

        $lstcaja = $this->Cajachica_model->lst_cajachica_sucursal($tmpidsuc);
        $caja = 0;
        if (count($lstcaja) > 0) {$caja = $lstcaja[0]->id_caja; }
        $cajac = $this->Cajachica_model->cajachica_resumen($caja);
        if ($cajac){
            $dis_caja = $cajac->resumen;
        } else {
            $dis_caja = 0;
        }

        $sritc = $this->Compra_model->sel_sri_tipo_doc();
        $data["sritc"] = $sritc;

        $srist = $this->Compra_model->sel_sri_sust_trib();
        $data["srist"] = $srist; 

        $data["caja"] = $dis_caja;               

        $data["decimalesprecio"] = $this->Parametros_model->sel_decimalesprecio();    
        $data["decimalescantidad"] = $this->Parametros_model->sel_decimalescantidad();    

        $data["tmpcomp"] = $tmpcomp;
        $data["sucursales"] = $sucursales;
        $data["proveedor"] = $proveedor;
        $data["unimed"] = $unimed;
        $data["detcomp"] = $detcomp;
        $data["precompra"] = $precompra;
        $data["catgastos"] = $catgastos;
        $data["almacenes"] = $almacenes;

        $data["modificacion"] = 1;

        $data["base_url"] = base_url();
        $data["content"] = "compra_add";
        $this->load->view("layout", $data);        
    }

    /* modificar compra */
    public function modificar_compra(){
        date_default_timezone_set("America/Guayaquil");
        $idcomp = $this->session->userdata("temp_idcomp");
        $idusu = $this->session->userdata("sess_id");
        $fec = $this->input->post("fecha");
        $fecha = str_replace('/', '-', $fec); 
        $fecha = date("Y-m-d", strtotime($fecha));
        
        $this->Compra_model->modificar_compra($idusu, $idcomp, $fecha);
        $arr['val'] = $idcomp;       
        print json_encode($arr);        
    }

}

?>

