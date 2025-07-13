<?php
/*------------------------------------------------
  ARCHIVO: Proforma.php
  DESCRIPCION: Contiene los métodos relacionados con la Proforma.
  FECHA DE CREACIÓN: 17/11/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Proforma extends CI_Controller {

  public function __construct() {
      parent::__construct();
      $this->auth_library->sess_validate(true);
      $this->auth_library->mssg_get();
      $this->load->Model("Proforma_model");
      $this->load->Model("Facturar_model");
      $this->load->Model("Parametros_model");      
      $this->load->Model("Pedido_model");      
      $this->load->Model("Empresa_model");            
      $this->load->Model("Sucursal_model");            
  }

  public function index() {
      date_default_timezone_set("America/Guayaquil");
      
      $desde = date("Y-m-d");
      $hasta = date("Y-m-d");

      if ($desde != NULL) { $this->session->set_userdata("tmp_prof_desde", $desde); } 
      else { $this->session->set_userdata("tmp_prof_desde", date("Y-m-d")); }
      $this->session->set_userdata("tmp_prof_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmp_prof_hasta", $hasta); } 
      else { $this->session->set_userdata("tmp_prof_hasta", date("Y-m-d")); }

      $desde = $this->session->userdata("tmp_prof_desde");
      $hasta = $this->session->userdata("tmp_prof_hasta");

      $facturapdf = $this->Parametros_model->sel_facturapdf();
      $data["facturapdf"] = $facturapdf;

      $data["desde"] = $desde;
      $data["hasta"] = $hasta;
      $data["base_url"] = base_url();
      $data["content"] = "proforma_listar";
      $this->load->view("layout", $data);


  }


  public function indexOK() {
      date_default_timezone_set("America/Guayaquil");
      $desde = date("Y-m-d"); 
      $hasta = date("Y-m-d"); 
      $this->session->set_userdata("tmp_prof_desde", NULL);
      if ($desde != NULL) { $this->session->set_userdata("tmp_prof_desde", $desde); } 
      else { $this->session->set_userdata("tmp_prof_desde", NULL); }
      $this->session->set_userdata("tmp_prof_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmp_prof_hasta", $hasta); } 
      else { $this->session->set_userdata("tmp_prof_hasta", NULL); }
      $desde = $this->session->userdata("tmp_prof_desde");
      $hasta = $this->session->userdata("tmp_prof_hasta");   


      $data["base_url"] = base_url();
      $data["content"] = "proforma_listar";
      $this->load->view("layout", $data);
  }


    public function listadoProforma() {
        $desde = $this->session->userdata("tmp_prof_desde");
        $hasta = $this->session->userdata("tmp_prof_hasta");
        $registro = $this->Proforma_model->lst_proforma($desde, $hasta); 
        $tabla = "";

        $usua = $this->session->userdata('usua');

        foreach ($registro as $row) {

          
          @$fec = str_replace('-', '/', $row->fecha); @$fec = date("d/m/Y", strtotime(@$fec));
          if ($row->id_factura != null) {$factura = $row->id_factura;} else {$factura = 0;}
          if($factura != 0){
            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Imprimir Proforma\" id=\"'.$row->id_proforma.'\" name=\"'.  $factura .'\" class=\"btn bg-navy color-palette btn-xs btn-grad pro_imp\"><i class=\"fa fa-print\"></i></a> </div>';
          }else{
            if ($usua->perfil != 2){
              $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Editar Proforma\" id=\"'.$row->id_proforma.'\" class=\"btn btn-success btn-xs btn-grad edi_prof\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar Proforma\" id=\"'.$row->id_proforma.'\" name=\"'.  $factura .'\" class=\"btn btn-danger btn-xs btn-grad pro_del\"><i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i></a> <a href=\"#\" title=\"Imprimir Proforma\" id=\"'.$row->id_proforma.'\" name=\"'.  $factura .'\" class=\"btn bg-navy color-palette btn-xs btn-grad pro_imp\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Facturar Proforma\" id=\"'.$row->id_proforma.'\" name=\"'.  $factura .'\" class=\"btn bg-blue color-palette btn-xs btn-grad pro_fac\"><i class=\"fa fa-file-text\"></i></a> </div>';  
          }
            else{
              $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Editar Proforma\" id=\"'.$row->id_proforma.'\" class=\"btn btn-success btn-xs btn-grad edi_prof\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar Proforma\" id=\"'.$row->id_proforma.'\" name=\"'.  $factura .'\" class=\"btn btn-danger btn-xs btn-grad pro_del\"><i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i></a> <a href=\"#\" title=\"Imprimir Proforma\" id=\"'.$row->id_proforma.'\" name=\"'.  $factura .'\" class=\"btn bg-navy color-palette btn-xs btn-grad pro_imp\"><i class=\"fa fa-print\"></i></a>  </div>';  
              $ver = '<div class=\"text-center \">';
              if ($usua->id_usu == $row->id_vendedor){
                $ver.=' <a href=\"#\" title=\"Editar Proforma\" id=\"'.$row->id_proforma.'\" class=\"btn btn-success btn-xs btn-grad edi_prof\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar Proforma\" id=\"'.$row->id_proforma.'\" name=\"'.  $factura .'\" class=\"btn btn-danger btn-xs btn-grad pro_del\"><i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i></a> ';
              }
              $ver.=' <a href=\"#\" title=\"Imprimir Proforma\" id=\"'.$row->id_proforma.'\" name=\"'.  $factura .'\" class=\"btn bg-navy color-palette btn-xs btn-grad pro_imp\"><i class=\"fa fa-print\"></i></a>  </div>';  

            }
          }
          
          $tabla.='{"fecha":"' . $fec . '",
                    "proforma":"' . $row->nro_proforma . '",
                    "cliente":"' . addslashes($row->nom_cliente) . '",
                    "monto":"' . $row->montototal . '",   
                    "vendedor":"' . addslashes($row->vendedor) . '",
                    "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function agregar(){
      $idusu = $this->session->userdata("sess_id");      
      $sucursal = $this->Sucursal_model->lst_sucursales();
      $tipident = $this->Proforma_model->tip_ident();
      $proalma = $this->Facturar_model->lst_proalmacen();
      $almacenes = $this->Facturar_model->lst_almacenes();
      $cat = $this->Facturar_model->sel_cat();
      $tp = $this->Parametros_model->tipo_precio();
      $cliente = $this->Proforma_model->carga_cliente_proforma($idusu);
      $lstprecios = $this->Proforma_model->lstprecios();
      $pro = $this->Proforma_model->lstprof_pro();
      $lstpro = $this->Pedido_model->productos();
      $lstdetalle = $this->Proforma_model->lst_profdettmp($idusu);
      $nroproforma = $this->Proforma_model->sel_nro_proforma();
      $idcli = $cliente->id_cliente;
      $preciopro = $this->Proforma_model->precioprof($idcli);
      $cambioprecio = $this->Parametros_model->sel_habilitacambioprecio();

      $usua = $this->session->userdata('usua');
      $perfil = $usua->perfil;
      $data["perfil"] = $perfil;   
      
      $data["cambioprecio"] = $cambioprecio;      
      $data["sucursal"] = $sucursal;      
      $data["preciopro"] = $preciopro;      
      $data["cliente"] = $cliente;
      $data["nroproforma"] = $nroproforma;        
      $data["lstdetalle"] = $lstdetalle;   
      $data["lstpro"] = $lstpro;
      $data["pro"] = $pro; 
      $data["lstprecios"] = $lstprecios;
      $data["tp"] = $tp;
      $data["lstcat"] = $cat;       
      $data["proalma"] = $proalma;       
      $data["almacenes"] = $almacenes;       
      $data["tipident"] = $tipident;      
      $data["base_url"] = base_url();
      $this->load->view("proforma", $data);      
    }

    public function nuevo(){
      $idusu = $this->session->userdata("sess_id");
      $resu = $this->Proforma_model->limpiarproformatmp($idusu);   
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function upd_profcliente(){
      $idusu = $this->session->userdata("sess_id");
      $idc = $this->input->post("idc");      
      $nrocli = $this->input->post("nrocli");
      $idtp = $this->input->post("idtp");
      $nom = $this->input->post("nom");
      $tel = $this->input->post("tel");
      $cor = $this->input->post("cor");
      $dir = $this->input->post("dir");
      $ciu = $this->input->post("ciu");
      $obs = $this->input->post("obs");
      $suc = $this->input->post("suc");
      $resu = $this->Proforma_model->upd_profcliente($idusu, $nrocli, $idtp, $nom, $tel, $cor, $dir, $ciu, $idc, $obs, $suc);
      $arr['resu'] = $resu;
      print json_encode($arr);
    }

    public function ins_profdetalletmp(){
      $idusu = $this->session->userdata("sess_id");
      $idpro = $this->input->post("id");
      $idalm = $this->input->post("idalm");
      $resu = $this->Proforma_model->ins_profdettmp($idusu, $idpro, $idalm);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function ins_profdetalletmpcodbar(){
      $idusu = $this->session->userdata("sess_id");
      $codbar = $this->input->post("codbar");
      $idalm = $this->input->post("idalm");
      $resu = $this->Proforma_model->ins_profdettmpcodbar($idusu, $codbar, $idalm);
      $arr['resu'] = 1;
      print json_encode($arr);
    }


    public function actualiza_tablaproforma(){
        $idusu = $this->session->userdata("sess_id");
        $lstdetalle = $this->Proforma_model->lst_profdettmp($idusu);
        $lstprecios = $this->Facturar_model->lstprecios();
        $idcli = $this->Proforma_model->selcliproftmp($idusu); 
        $preciopro = $this->Proforma_model->precioprof($idcli);
        $tp = $this->Parametros_model->tipo_precio();
        $descpro = $this->Parametros_model->sel_descpro();
        $tipodescprod = $this->Parametros_model->sel_tipodescuentoproducto();   
        $cambioprecio = $this->Parametros_model->sel_habilitacambioprecio();

        $usua = $this->session->userdata('usua');
        $perfil = $usua->perfil;
        $data["perfil"] = $perfil;   

        $data["cambioprecio"] = $cambioprecio;        
        $data["tp"] = $tp;        
        $data["lstdetalle"] = $lstdetalle;
        $data["lstprecios"] = $lstprecios;
        $data["preciopro"] = $preciopro; 
        $data["descpro"] = $descpro;
        $data["tipodescprod"] = $tipodescprod;
        $data["base_url"] = base_url();
        $this->load->view("proforma_general_tabla", $data);            
    }

    public function del_profdettmp(){
      $iddetalle = $this->input->post("id");
      $resu = $this->Proforma_model->del_profdettmp($iddetalle);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function lst_subtotalesprofdettmp(){
      $idusu = $this->session->userdata("sess_id");
      $resu = $this->Proforma_model->lst_subtotalesprofdettmp($idusu);
      $arr['subtotaliva'] = $resu->subtotaliva;
      $arr['subtotalcero'] = $resu->subtotalcero;
      $arr['descsubtotaliva'] = $resu->descsubtotaliva;
      $arr['descsubtotalcero'] = $resu->descsubtotalcero;
      $arr['descuento'] = $resu->descuento;      
      $arr['montoiva'] = $resu->montoiva;
      print json_encode($arr);
    }

    public function upd_detalleproforma(){
      $iddetalle = $this->input->post("id");
      $cantidad = $this->input->post("cantidad");
      $precio = $this->input->post("precio");
      $descpro = $this->input->post("descpro");
      $valiva = $this->input->post("valiva");
      $subtotal = $this->input->post("subtotal");
      $tp = $this->input->post("tp");
      $resu = $this->Proforma_model->upd_detalleprof($iddetalle, $cantidad, $precio, $valiva, $subtotal, $tp, $descpro);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function upd_descuentoproformatmp(){
      $idusu = $this->session->userdata("sess_id");
      $descuento = $this->input->post("descuento");
      $resu = $this->Proforma_model->upd_descuentoproftmp($idusu, $descuento);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function edit_descripciondetalle(){
        $id = $this->input->post("id");
        //$descripcion = $this->input->post("descripcion");
        $descripcion = $this->Proforma_model->sel_descripciondetalle($id);
        $data["id"] = $id;
        $data["descripcion"] = $descripcion;
        $data["base_url"] = base_url();
        $this->load->view("proforma_detalle", $data);
    }

    public function udp_descripciondetalle(){
      $id = $this->input->post("id");
      $descripcion = $this->input->post("descripcion");
      $resu = $this->Proforma_model->udp_descripciondetalle($id, $descripcion);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function guarda_proforma(){
      $idusu = $this->session->userdata("sess_id");
      $idprof = $this->input->post("idprof");
      if($idprof == 0){
        $arr = $this->Proforma_model->proforma_guardar();
      }else{
        $arr = $this->Proforma_model->proforma_modificar($idusu, $idprof);
      }
      print json_encode($arr);
    }

    public function tmp_proforma(){
      $id = $this->input->post("id");
      $this->session->unset_userdata("tmp_prof"); 
      $this->session->set_userdata("tmp_prof", NULL);
      if ($id != NULL) { $this->session->set_userdata("tmp_prof", $id); } 
      else { $this->session->set_userdata("tmp_prof", NULL); }
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function tmp_prof_fecha() {
      $this->session->unset_userdata("tmp_prof_desde"); 
      $this->session->unset_userdata("tmp_prof_hasta");
      $fecdesde = $this->input->post("desde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      $fechasta = $this->input->post("hasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));

      $this->session->set_userdata("tmp_prof_desde", NULL);
      if ($desde != NULL) { $this->session->set_userdata("tmp_prof_desde", $desde); } 
      else { $this->session->set_userdata("tmp_prof_desde", NULL); }

      $this->session->set_userdata("tmp_prof_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmp_prof_hasta", $hasta); } 
      else { $this->session->set_userdata("tmp_prof_hasta", NULL); }
      $arr['resu'] = 1;
      print json_encode($arr);
    } 

    public function editar_proforma(){
      $idusu = $this->session->userdata("sess_id");
      $idproforma = $this->session->userdata("tmp_prof");
      $almacenes = $this->Facturar_model->lst_almacenes();
      $data["almacenes"] = $almacenes;             
      $tipident = $this->Proforma_model->tip_ident();
      $data["tipident"] = $tipident; 
      $proalma = $this->Facturar_model->lst_proalmacen();
      $data["proalma"] = $proalma;
      $cat = $this->Facturar_model->sel_cat();
      $data["lstcat"] = $cat;
      $tp = $this->Parametros_model->tipo_precio();
      $data["tp"] = $tp;
      $lstprecios = $this->Facturar_model->lstprecios();
      $data["lstprecios"] = $lstprecios;
      $pro = $this->Proforma_model->lstprof_pro();
      $data["pro"] = $pro; 
      $lstpro = $this->Pedido_model->productos();
      $data["lstpro"] = $lstpro;
      $selprof = $this->Proforma_model->selprofid($idusu, $idproforma);
      $data["idproforma"] = $idproforma;
      $lstdetalle = $this->Proforma_model->lst_profdettmp($idusu);
      $data["lstdetalle"] = $lstdetalle; 
      $cliente = $this->Proforma_model->carga_cliente_proforma($idusu);
      $data["cliente"] = $cliente;
      $idcli = $cliente->id_cliente;
      $preciopro = $this->Proforma_model->precioprof($idcli);
      $data["preciopro"] = $preciopro;  
      $sucursal = $this->Sucursal_model->lst_sucursales();
      $data["sucursal"] = $sucursal;  
      $data["base_url"] = base_url();
      $this->load->view("proforma", $data); 
    }  

   /* ABRIR VENTANA PARA Eliminar */
    public function del_pro(){
      $idproforma = $this->session->userdata("tmp_prof");
      $pro = $this->Proforma_model->sel_datoproformaid($idproforma);
      $data["pro"] = $pro;       
      $data["base_url"] = base_url();
      $this->load->view("proforma_del", $data);        
    }

    public function eliminar(){
      $pro = $this->input->post("txt_idpro");
      $resu = $this->Proforma_model->eliminar($pro);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function upd_fechaproformatmp(){
      $idusu = $this->session->userdata("sess_id");
      $fecha = $this->input->post("fecha");
      $fecha = str_replace('/', '-', $fecha); 
      $fecha = date("Y-m-d", strtotime($fecha));
      $resu = $this->Proforma_model->upd_fechaproftmp($idusu, $fecha);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function facturar(){
      $idusu = $this->session->userdata("sess_id");      
      $pro = $this->input->post("id");
      $objpro = $this->Proforma_model->sel_datoproformaid($pro);
      $lstcaja = $this->Facturar_model->lst_caja_sucursal($idusu, $objpro->id_sucursal);
      $caja = 0;
      $resu = 0;
      if (count($lstcaja) > 0) { 
        $caja = $lstcaja[0]->id_caja; 
        $resu = $this->Proforma_model->genera_factura($pro, $idusu, $caja);
      }
      $arr['resu'] = $resu;
      print json_encode($arr);
    }

    /* ABRIR VENTANA PARA Imprimir proforma */
    public function imprimirproforma(){
        $factoriva = 12;
        date_default_timezone_set("America/Guayaquil");
        
        $idfactura = $this->input->post('id');
        $row = $this->Proforma_model->sel_datoproformaid($idfactura);
        $emp = $this->Empresa_model->emp_get();
        $tabla = chr(32). chr(32) . "\r\n";
        $tabla.= chr(32). chr(32) . "\r\n";
        $tabla.="PROFORMA";
        $tabla.="\r\n";
        $tabla.=$emp->nom_emp . "\r\n";
        $tabla.="RUC:" . chr(32) . $emp->ruc_emp . "\r\n";
        $tabla.=$emp->dir_emp . "\r\n";
        $tabla.="Tel:" . chr(32) . $emp->tlf_emp . "\r\n";
        if (trim($row->nro_proforma) != ''){
          $tabla.="Nro. Proforma:" . chr(32) . $row->nro_proforma . "\r\n";  
        }
        //$strdate=//date("d/m/Y H:i");
        $strdate = str_replace('-', '/', $row->fecha); 
        $strdate = date("d/m/Y H:m", strtotime($strdate)); 
        $tabla.="Fecha:" . chr(32) . $strdate . "\r\n";
/*        if (trim($row->mesa) != ''){
          $tabla.="Punto:" . chr(32) . $row->mesa . "\r\n";  
        }*/
        if (trim($row->nom_mesero) != ''){
          $tabla.="Vendedor:" . chr(32) . $row->nom_mesero . "\r\n";  
        }
        $tabla.="Cliente:" . chr(32) . $row->nom_cliente . "\r\n";        
        $tabla.="Direccion:". chr(32) . $row->direccion_cliente . "\r\n";        
        $tabla.="CI/RUC:". chr(32) . $row->ident_cliente . "\r\n";        
        $tabla.="Telef.:". chr(32) . $row->telefonos_cliente . "\r\n";        

        $tabla.= "\r\n";
        $tabla.="PRODUCTO                             CANTIDAD  PRECIO.UNIT.  PRECIO TOTAL". "\r\n";        
        $registro = $this->Proforma_model->lst_profdetalle($idfactura);
        $subtotaliva=0;
        $subtotalcero=0;
        $subtotaldiva=0;
        $subtotaldcero=0;
        $montoiva=0;
        $descuento=0;
        foreach ($registro as $row) {
            $strnombre = substr($row->pro_nombre,0,37);
            $tabla.= $strnombre;
            $lcant = strlen($strnombre);
            while ($lcant < 37){
                $tabla.= chr(32);
                $lcant++;
            }
            $strcant = number_format($row->cantidad,2);
            $lcant = strlen($strcant);
            while ($lcant < 7){
                $tabla.= chr(32);
                $lcant++;
            }
            $tabla.= $strcant;
            $lcant = strlen($strcant);

            if ($row->pro_grabaiva == 1){
                $subtotaliva+= $row->subtotal;
                //$subtotaldiva+= $row->descsubtotal;
                $montoiva+= $row->montoiva;    
            }
            else{
                $subtotalcero+= $row->subtotal;
                //$subtotaldcero+= $row->descsubtotal;
            }
            //$descuento+=$row->descmonto;

            $strprecio=number_format($row->precio,2);
            $lcant = strlen($strprecio);
            while ($lcant < 11){
                $tabla.= chr(32);
                $lcant++;
            }
            $tabla.= $strprecio;

            $strprecio=number_format($row->subtotal,2);
            $lcant = strlen($strprecio);
            while ($lcant < 15){
                $tabla.= chr(32);
                $lcant++;
            }
            $tabla.= $strprecio;

            $tabla.= "\r\n";
        }
        /*$montoiva = round($subtotaliva * $factoriva / 100,2);*/
        $totalpagar = $subtotaliva + $subtotalcero + $montoiva;
        $tabla.= "\r\n";
        $tabla.= "\r\n";
        $tabla.= "   SUBTOTAL  IVA12:" . chr(32) . chr(32) . number_format($subtotaliva,2) . "\r\n";
        $tabla.= "   SUBTOTAL   IVA0:" . chr(32) . chr(32) . number_format($subtotalcero,2) . "\r\n";
        // $tabla.= "   DESCUENTO      :" . "\x1F \x1F" . number_format($descuento,2) . "\r\n";
        // $tabla.= "   SUBTOTDES IVA12:" . "\x1F \x1F" . number_format($subtotaldiva,2) . "\r\n";
        // $tabla.= "   SUBTOTDES  IVA0:" . "\x1F \x1F" . number_format($subtotaldcero,2) . "\r\n";
        $tabla.= "   MONTO IVA12    :" . chr(32) . chr(32) . number_format($montoiva,2) . "\r\n";
        $tabla.= "   TOTAL FACTURA  :" . chr(32) . chr(32) . number_format($totalpagar,2) . "\r\n";
/*
        for ($i=0; $i<2; $i++) {
            $tabla.= "  " . "\n";                        
        }        
        $tabla.= "------------------" . "\r\n";
        $tabla.= "Gracias por su compra." . "\r\n";

        for ($i=0; $i<2; $i++) {
            $tabla.= "  " . "\n";                        
        }        
*/
        for ($i=0; $i<5; $i++) {
            $tabla.= "  " . "\n";                        
        }        
        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("proforma_imprimir", $data);
    }

    /* ABRIR VENTANA PARA Imprimir proforma */
    public function imprimirproformaticket(){
        $factoriva = 12;
        date_default_timezone_set("America/Guayaquil");
        
        //$idfactura = $this->input->post('id');
        $idfactura = $this->session->userdata("idproforma_tmp");

        $encprof = $this->Proforma_model->sel_datoproformaid($idfactura);
        $emp = $this->Empresa_model->emp_get();
        $tabla = chr(32). chr(32) . "\r\n";
        $tabla.= chr(32). chr(32) . "\r\n";
        $tabla.="PROFORMA";
        $tabla.="\r\n";
        $tabla.=$emp->nom_emp . "\r\n";
        $tabla.="RUC:" . chr(32) . $emp->ruc_emp . "\r\n";
        $tabla.=$emp->dir_emp . "\r\n";
        $tabla.="Tel:" . chr(32) . $emp->tlf_emp . "\r\n";
        if (trim($encprof->nro_proforma) != ''){
          $tabla.="Nro. Proforma:" . chr(32) . $encprof->nro_proforma . "\r\n";  
        }
        //$strdate=//date("d/m/Y H:i");
        $strdate = str_replace('-', '/', $encprof->fecha); 
        $strdate = date("d/m/Y H:m", strtotime($strdate)); 
        $tabla.="Fecha:" . chr(32) . $strdate . "\r\n";
/*        if (trim($row->mesa) != ''){
          $tabla.="Punto:" . chr(32) . $row->mesa . "\r\n";  
        }*/
        /*if (trim($row->nom_mesero) != ''){
          $tabla.="Vendedor:" . chr(32) . $row->nom_mesero . "\r\n";  
        }*/
        $tabla.="Cliente:" . chr(32) . $encprof->nom_cliente . "\r\n";        
        $tabla.="Direccion:". chr(32) . $encprof->direccion_cliente . "\r\n";        
        $tabla.="CI/RUC:". chr(32) . $encprof->ident_cliente . "\r\n";        
        $tabla.="Telef.:". chr(32) . $encprof->telefonos_cliente . "\r\n";        

        $tabla.= "\r\n";
        $tabla.="PRODUCTO  Cant. P.Unit  P.Tot". "\r\n";        
//      $tabla.="PRODUCTO                             CANTIDAD  PRECIO.UNIT.  PRECIO TOTAL". "\r\n";        
        $registro = $this->Proforma_model->lst_profdetalle($idfactura);
        $subtotaliva=0;
        $subtotalcero=0;
        $subtotaldiva=0;
        $subtotaldcero=0;
        $montoiva=0;
        $descuento=0;
        foreach ($registro as $row) {
            $strnombre = substr($row->pro_nombre,0,10);
            $tabla.= $strnombre;
            $lcant = strlen($strnombre);
            while ($lcant < 10){
                $tabla.= chr(32);
                $lcant++;
            }
            $strcant = number_format($row->cantidad,2);
            $lcant = strlen($strcant);
            while ($lcant < 5){
                $tabla.= chr(32);
                $lcant++;
            }
            $tabla.= $strcant;
            $lcant = strlen($strcant);

            if ($row->pro_grabaiva == 1){
                $subtotaliva+= $row->subtotal;
                //$subtotaldiva+= $row->descsubtotal;
                $montoiva+= $row->montoiva;    
            }
            else{
                $subtotalcero+= $row->subtotal;
                //$subtotaldcero+= $row->descsubtotal;
            }
            //$descuento+=$row->descmonto;

            $strprecio=number_format($row->precio,2);
            $lcant = strlen($strprecio);
            while ($lcant < 7){
                $tabla.= chr(32);
                $lcant++;
            }
            $tabla.= $strprecio;

            $strprecio=number_format($row->subtotal,2);
            $lcant = strlen($strprecio);
            while ($lcant < 7){
                $tabla.= chr(32);
                $lcant++;
            }
            $tabla.= $strprecio;

            $tabla.= "\r\n";
        }
        $descuento = $encprof->subconiva + $encprof->subsiniva - $encprof->descsubconiva - $encprof->descsubsiniva;
        $tabla.= "\r\n";
        $tabla.= "\r\n";
        $tabla.= "   SUBTOTAL IVA12:" . chr(32) . chr(32) . number_format($encprof->subconiva,2) . "\r\n";
        $tabla.= "   SUBTOTAL  IVA0:" . chr(32) . chr(32) . number_format($encprof->subsiniva,2) . "\r\n";
        $tabla.= "   DESCUENTO     :" . "\x1F \x1F" . number_format($descuento,2) . "\r\n";
        $tabla.= "   SUBT/DES IVA12:" . "\x1F \x1F" . number_format($encprof->descsubconiva,2) . "\r\n";
        $tabla.= "   SUBT/DES  IVA0:" . "\x1F \x1F" . number_format($encprof->descsubsiniva,2) . "\r\n";
        $tabla.= "   MONTO IVA12   :" . chr(32) . chr(32) . number_format($encprof->montoiva,2) . "\r\n";
        $tabla.= "   TOTAL FACTURA :" . chr(32) . chr(32) . number_format($encprof->montototal,2) . "\r\n";
/*
        $totalpagar = $subtotaliva + $subtotalcero + $montoiva;
        $tabla.= "\r\n";
        $tabla.= "\r\n";
        $tabla.= "   SUBTOTAL IVA12:" . chr(32) . chr(32) . number_format($subtotaliva,2) . "\r\n";
        $tabla.= "   SUBTOTAL  IVA0:" . chr(32) . chr(32) . number_format($subtotalcero,2) . "\r\n";
        // $tabla.= "   DESCUENTO      :" . "\x1F \x1F" . number_format($descuento,2) . "\r\n";
        // $tabla.= "   SUBTOTDES IVA12:" . "\x1F \x1F" . number_format($subtotaldiva,2) . "\r\n";
        // $tabla.= "   SUBTOTDES  IVA0:" . "\x1F \x1F" . number_format($subtotaldcero,2) . "\r\n";
        $tabla.= "   MONTO IVA12   :" . chr(32) . chr(32) . number_format($montoiva,2) . "\r\n";
        $tabla.= "   TOTAL FACTURA :" . chr(32) . chr(32) . number_format($totalpagar,2) . "\r\n";
*/        
/*
        for ($i=0; $i<2; $i++) {
            $tabla.= "  " . "\n";                        
        }        
        $tabla.= "------------------" . "\r\n";
        $tabla.= "Gracias por su compra." . "\r\n";

        for ($i=0; $i<2; $i++) {
            $tabla.= "  " . "\n";                        
        }        
*/
        for ($i=0; $i<5; $i++) {
            $tabla.= "  " . "\n";                        
        }        
        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("proforma_imprimir", $data);
    }

     public function nroproforma_tmp() {
        $this->session->unset_userdata("idproforma_tmp"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("idproforma_tmp", NULL);
        if ($id != NULL) { $this->session->set_userdata("idproforma_tmp", $id); } 
        else { $this->session->set_userdata("idproforma_tmp", NULL); }
        $id_prof = $this->session->userdata("idproforma_tmp");
        $arr['resu'] = $id_prof;
        print json_encode($arr);
    }

    private function pagina_v() {
      $this->pdf_p->SetMargins('12', '7', '10');   #Margenes
      $this->pdf_p->AddPage('P', 'A4');        #Orientación y tamaño 
    }

    public function proformapdf(){

      $idproforma = $this->session->userdata("idproforma_tmp");
      $encprof = $this->Proforma_model->sel_datoproformaid($idproforma);
      $sucursal = $this->Sucursal_model->sel_suc_id($encprof->id_sucursal);      
      $empresa = $this->Empresa_model->sel_emp_id($sucursal->id_empresa);      
      $pieprof = $this->Proforma_model->lst_profdetalle($idproforma);
      $params['encprof'] = $encprof;
      $params['sucursal'] = $sucursal;
      $params['empresa'] = $empresa;
    //  $params['pieprof'] = $pieprof;
      /* ENCABEZADO DEL PDF */
      $this->load->library('pdf_p', $params);
      $this->pdf_p->fontpath = 'font/'; 
      $this->pdf_p->AliasNbPages();
      $this->pagina_v();
      $this->pdf_p->SetFillColor(139, 35, 35);
      $this->pdf_p->SetFont('Arial','B',8);
      $this->pdf_p->SetTextColor(0,0,0);

      // Imagen Detalle
      if ($sucursal->logo_detallepagina != NULL){

        $file_name = "detprof.jpg";
        $pic = base64_decode($sucursal->logo_detallepagina);
        imagejpeg(imagecreatefromstring ( $pic ), $file_name);
        $this->pdf_p->Image($file_name,10,100,190,0);
      }

      /* TITULO DE DETALLES */
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


      $this->pdf_p->Cell(20,4,utf8_decode("Cantidad"),1,0,'C');
      $this->pdf_p->Cell($tamanodescrip,4,utf8_decode("Descripcion"),1,0,'L');
      $this->pdf_p->Cell($tamanoprecio,4,'Precio Unitario',1,0,'C');
      if ($descpro == 1){
        $strtipodescuentoproducto = '%Descuento';
        if ($tipodescuentoproducto == 0) { $strtipodescuentoproducto = 'Descuento'; }
        $this->pdf_p->Cell($tamanodescuento,4,$strtipodescuentoproducto,1,0,'C');
      }  
      $this->pdf_p->Cell($tamanototal,4,'Subtotal',1,1,'R');
      /* CICLO DE DETALLES DE FACTURA */
      $registro = $this->Proforma_model->lst_profdetalle($idproforma);
      $subtotaliva=0;
      $subtotalcero=0;
      $subtotaldiva=0;
      $subtotaldcero=0;
      $montoiva=0;
      $descuento=0;
      foreach ($registro as $row) {
        $strnombre = $row->descripcion;
        $strcant = $row->cantidad;
        if ($row->pro_grabaiva == 1){
          $subtotaliva+= $row->subtotal;
          $montoiva+= $row->montoiva;    
        }
        else{
          $subtotalcero+= $row->subtotal;
        }
        $precio = number_format($row->precio,2);
        $descuento=number_format($row->porcdesc,2);
        $subtotal=number_format($row->descsubtotal,2);

        $tmpY = $this->pdf_p->GetY();

        $this->pdf_p->SetFont('Arial','',8);        
        $this->pdf_p->Cell(20,5,round($strcant,0),0,0,'C');
        $this->pdf_p->MultiCell($tamanodescrip,5,utf8_decode("$strnombre"));
        $tmpYdetalle = $this->pdf_p->GetY();

/*        $this->pdf_p->Cell(115,5,utf8_decode("$strnombre"),1,0,'L');*/
        $this->pdf_p->SetXY(30+$tamanodescrip,$tmpY);
        $this->pdf_p->Cell($tamanoprecio,5,'$'.$precio,0,0,'R');
        if ($descpro == 1){
          if ($tipodescuentoproducto == 0) { 
            $descuento=number_format($row->descmonto,2); 
          }
          else{
            $descuento = '%'.$descuento; 
          }
          $this->pdf_p->Cell($tamanodescuento,5,$descuento,0,0,'C');
        }  
        $this->pdf_p->Cell($tamanototal,5,'$'.$subtotal,0,1,'R'); 
        $this->pdf_p->SetY($tmpYdetalle);
      }

      $this->pdf_p->Ln(20);      
      if (trim($encprof->observaciones) != ''){
        $this->pdf_p->SetFont('Arial','B',10); 
        $this->pdf_p->Cell(50,4,utf8_decode("Observaciones"),0,1,'L');
        $this->pdf_p->SetFont('Arial','',10);        
        $this->pdf_p->MultiCell(185,5,utf8_decode($encprof->observaciones));   
      }  

/*
      $this->pdf_p->SetY(-80);
      // Arial italic 8
      $this->pdf_p->SetFont('Arial','I',8);
      // Número de página
      $this->pdf_p->Cell(0,10,'Page '.$this->pdf_p->PageNo().'/{nb}',0,0,'C');
*/


      $subtotaliva=0;
      $subtotalcero=0;
      $subtotaldiva=0;
      $subtotaldcero=0;
      $montoiva=0;
      $descuento = round($encprof->subconiva + $encprof->subsiniva - $encprof->descsubconiva - $encprof->descsubsiniva,2);
      foreach ($pieprof as $row) {
        $strnombre = $row->pro_nombre;
        $strcant = $row->cantidad;
          if ($row->pro_grabaiva == 1){
            $subtotaliva+= $row->subtotal;
            $montoiva+= $row->montoiva;    
          }
          else{
            $subtotalcero+= $row->subtotal;
          }
        }

      $total = $subtotaliva + $subtotalcero + $montoiva;

      $this->pdf_p->Ln(2);      
      $this->pdf_p->SetFont('Arial','',8);
      $this->pdf_p->MultiCell(0,4,utf8_decode($sucursal->pie1_texto));

      $this->pdf_p->SetY(-70);

      // Imagen Pie
      if ($sucursal->logo_piepagina != NULL){
        $file_name = "pieprof.jpg";
        $pic = base64_decode($sucursal->logo_piepagina);
        imagejpeg(imagecreatefromstring ( $pic ), $file_name);
        $this->pdf_p->Image($file_name,10,240,190,40);

      }


      $this->pdf_p->Line(12,219,60,219);
      $this->pdf_p->text(22, 223, utf8_decode('Firma Autorizada'));

/*      $this->pdf_p->text(12, 240, utf8_decode('NOTA:'));
      $this->pdf_p->text(12, 244, utf8_decode('La validez de la siguiente Proforma tiene 8 días'));*/

      $this->pdf_p->SetFont('Arial','B',10);
      $this->pdf_p->Cell(160,-4,utf8_decode("Total"),0,0,'R');
      $this->pdf_p->Cell(25,-4,utf8_decode('$'.$encprof->montototal/*$total*/),0,1,'R');

      $this->pdf_p->Cell(160,-4,utf8_decode("IVA (12%)"),0,0,'R');
      $this->pdf_p->Cell(25,-4,utf8_decode('$'.$encprof->montoiva/*$montoiva*/),0,1,'R');

      $this->pdf_p->Cell(160,-4,utf8_decode("Subtotal con Descuento IVA (0 %)"),0,0,'R');
      $this->pdf_p->Cell(25,-4,utf8_decode('$'.$encprof->descsubsiniva/*$subtotaldcero*/),0,1,'R');

      $this->pdf_p->Cell(160,-4,utf8_decode("Subtotal con Descuento IVA (12 %)"),0,0,'R');
      $this->pdf_p->Cell(25,-4,utf8_decode('$'.$encprof->descsubconiva/*$subtotaldiva*/),0,1,'R');

      $this->pdf_p->Cell(160,-4,utf8_decode("Descuento"),0,0,'R');
      $this->pdf_p->Cell(25,-4,utf8_decode('$'./*$encprof->desc_monto*/number_format($descuento,2)),0,1,'R');

      $this->pdf_p->Cell(160,-4,utf8_decode("Subtotal IVA (0 %)"),0,0,'R');
      $this->pdf_p->Cell(25,-4,utf8_decode('$'.$encprof->subsiniva/*$subtotalcero*/),0,1,'R');

      $this->pdf_p->Cell(160,-4,utf8_decode("Subtotal IVA (12 %)"),0,0,'R');
      $this->pdf_p->Cell(25,-4,utf8_decode('$ '.$encprof->subconiva/*$subtotaliva*/),0,1,'R');      

      /* NUEVA PAGINA */

/*
      $detproimg = $this->Proforma_model->lst_profimagen($idproforma);

      $this->load->library('pdf_p', $params);
      $this->pdf_p->fontpath = 'font/'; 
      $this->pdf_p->AliasNbPages();
      $this->pagina_v();
      $this->pdf_p->SetFillColor(139, 35, 35);
      $this->pdf_p->SetFont('Arial','B',8);
      $this->pdf_p->SetTextColor(0,0,0);
      $this->pdf_p->Line(12,40,196,40); 

      foreach ($detproimg as $dpi) {

        $nompro = utf8_decode($dpi->pro_nombre);
        $despro = utf8_decode($dpi->pro_descripcion);
        $imgpro = $dpi->pro_imagen;
        $pic = 'data://text/plain;base64,' .$imgpro;
        
        if($dpi->pro_imagen == null){
          $this->pdf_p->Cell(30,30, $this->pdf_p->Image(base_url().'public/img/quitoled.jpg', $this->pdf_p->GetX(), $this->pdf_p->GetY(), 30, 30, 'jpg'),1);
        } else{
          $this->pdf_p->Cell(30,30, $this->pdf_p->Image($pic, $this->pdf_p->GetX(), $this->pdf_p->GetY(), 30, 30, 'jpg'),1); 
        }     

        $this->pdf_p->Cell(100, 5,'Producto: '.$nompro, 0,1,'L'); 
        $this->pdf_p->Cell(30,0,'',0,0); 
        $this->pdf_p->MultiCell(150,5,'Descripcion: '.$despro,0);
        $this->pdf_p->Ln(22);


      }
*/
      /* FIN */   
      $this->pdf_p->Output('Proforma.pdf','I'); 

    }

    public function obtenerPreciosProf(){
      $idpro = $this->input->post('idpro');
      $idcliente = $this->input->post('idc');
      $propre = $this->Proforma_model->selprofprecio($idpro, $idcliente);
      print json_encode($propre);      
    }

    public function reporte(){
        $desde = $this->session->userdata("tmp_prof_desde");
        $hasta = $this->session->userdata("tmp_prof_hasta");
        $proforma = $this->Proforma_model->reporte($desde, $hasta);
        $data["base_url"] = base_url();
        $data["desde"] = $desde;
        $data["hasta"] = $hasta;
        $data["proforma"] = $proforma;
        $this->load->view("proforma_reporte", $data);         
    }

    public function valida_proforma(){
      $val = $this->Proforma_model->valpro();
      print json_encode($val);        
    }

    public function valmonto_proforma(){
      $monto = $this->Proforma_model->valmontopro();
      print json_encode($monto);        
    }

}

?>