<?php

/*------------------------------------------------
  ARCHIVO: Serviciotecnico.php
  DESCRIPCION: Contiene los métodos relacionados con Serviciotecnico.
  FECHA DE CREACIÓN: 19/03/2018
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/Fpdf.php');

class Serviciotecnico extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Serviciotecnico_model");
        $this->load->Model("Sucursal_model");
        $this->load->Model("Pedido_model");
        $this->load->Model("Empleado_model");
        $this->load->Model("Empresa_model");
        $this->load->Model("Facturar_model");
        $this->load->Model("Correo_model");
        $this->load->Model("Cajaefectivo_model");

    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
      /*  $desde = date("Y-m-d"); 
        $hasta = date("Y-m-d");         */
        $desde = $this->session->userdata("tmp_servicio_desde");
        $hasta = $this->session->userdata("tmp_servicio_hasta");        

        $this->session->set_userdata("tmp_servicio_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_servicio_desde", $desde); } 
        else { $this->session->set_userdata("tmp_servicio_desde", date("Y-m-d")); }
        $this->session->set_userdata("tmp_servicio_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_servicio_hasta", $hasta); } 
        else { $this->session->set_userdata("tmp_servicio_hasta", date("Y-m-d")); }

        $estado = $this->session->userdata("tmp_servicio_estado");
        $this->session->set_userdata("tmp_servicio_estado", NULL);
        if ($estado != NULL) { $this->session->set_userdata("tmp_servicio_estado", $estado); } 
        else { $this->session->set_userdata("tmp_servicio_estado", 0); }

        $tecnico = $this->session->userdata("tmp_servicio_tecnico");
        $this->session->set_userdata("tmp_servicio_tecnico", NULL);
        if ($tecnico != NULL) { $this->session->set_userdata("tmp_servicio_tecnico", $tecnico); } 
        else { $this->session->set_userdata("tmp_servicio_tecnico", 0); }

        $estados = $this->Serviciotecnico_model->lst_estadoservicio();
        $data["estados"] = $estados;
        $tecnicos = $this->Empleado_model->lst_empleadotecnico();
        $data["tecnicos"] = $tecnicos;
        $desde = $this->session->userdata("tmp_servicio_desde");
        $hasta = $this->session->userdata("tmp_servicio_hasta");        
        $data["desde"] = $desde;
        $data["hasta"] = $hasta;

        $objcfgdetalle = $this->Serviciotecnico_model->sel_configdetalle_mostrarenlistado();
        $mostrardetalle = 0;
        $strdetalle = "";
        if (count($objcfgdetalle) > 0){ 
          $mostrardetalle = 1;
          $strdetalle = $objcfgdetalle[0]->nombre_configdetalle; 
        }
        $data["mostrardetalle"] = $mostrardetalle;
        $data["strdetalle"] = $strdetalle;

        $data["base_url"] = base_url();
        $data["content"] = "serviciotecnico";
        $this->load->view("layout", $data);
    }

    public function tmp_serviciotecnico() {
        $this->session->unset_userdata("tmp_servicio_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_servicio_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_servicio_id", $id);
        } else {
            $this->session->set_userdata("tmp_servicio_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }


    public function tmp_clearserviciotecnico() {
        $idusu = $this->session->userdata("sess_id");
        $this->Serviciotecnico_model->tmp_clearserviciotecnico($idusu);

        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function tmp_serviciofecha(){
      /* fecha desde */
      $fecdesde = $this->input->post("desde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      /* fecha hasta */
      $fechasta = $this->input->post("hasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));

      $estado = $this->input->post("estado");

      $tecnico = $this->input->post("tecnico");

      $this->session->set_userdata("tmp_servicio_desde", NULL);
      if ($desde != NULL) {
          $this->session->set_userdata("tmp_servicio_desde", $desde);
      } else {
          $this->session->set_userdata("tmp_servicio_desde", NULL);
      }

      $this->session->set_userdata("tmp_servicio_hasta", NULL);
      if ($hasta != NULL) {
          $this->session->set_userdata("tmp_servicio_hasta", $hasta);
      } else {
          $this->session->set_userdata("tmp_servicio_hasta", NULL);
      }

      $this->session->set_userdata("tmp_servicio_estado", NULL);
      if ($estado != NULL) {
          $this->session->set_userdata("tmp_servicio_estado", $estado);
      } else {
          $this->session->set_userdata("tmp_servicio_estado", 0);
      }

      $this->session->set_userdata("tmp_servicio_tecnico", NULL);
      if ($tecnico != NULL) {
          $this->session->set_userdata("tmp_servicio_tecnico", $tecnico);
      } else {
          $this->session->set_userdata("tmp_servicio_tecnico", 0);
      }

      $arr['resu'] = 1;
      print json_encode($arr);
    }


    public function listadoServicios() {
        $desde = $this->session->userdata("tmp_servicio_desde");
        $hasta = $this->session->userdata("tmp_servicio_hasta");        
        $estado = $this->session->userdata("tmp_servicio_estado");        
        $tecnico = $this->session->userdata("tmp_servicio_tecnico");        

        $usua = $this->session->userdata('usua');

        $objcfgdetalle = $this->Serviciotecnico_model->sel_configdetalle_mostrarenlistado();
        $idconfig = 0;
        if (count($objcfgdetalle) > 0){ $idconfig = $objcfgdetalle[0]->id_config; }


        $registro = $this->Serviciotecnico_model->lst_servicio($desde, $hasta, $estado, $tecnico);
        $tabla = "";
        foreach ($registro as $row) {
            if (($row->id_venta != null) && ($row->estatus != 3)) {$factura = $row->id_venta;} else {$factura = 0;}
            if($factura != 0){
              $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Imprimir Servicio\" id=\"'.$row->id_servicio.'\" name=\"'.  $row->numero_orden .'\" class=\"btn bg-navy color-palette btn-xs btn-grad pro_imp\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Imprimir Etiqueta\" id=\"'.$row->id_servicio.'\" name=\"'.  $row->numero_orden .'\" class=\"btn bg-navy color-palette btn-xs btn-grad imp_etiqueta\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Enviar Correo\" id=\"'.$row->id_servicio.'\" class=\"btn bg-orange color-palette btn-xs btn-grad enviarcorreo\"><i class=\"fa fa-envelope-o\"></i></a> </div>';
            }else{
              if ($usua->perfil != 2){
                if (($row->id_estado == 3) || ($row->id_estado == 4)) /*entregada*/
                  $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Editar Servicio Tecnico\" id=\"'.$row->id_servicio.'\" class=\"btn btn-success btn-xs btn-grad ret_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_servicio.'\" name=\"'.$row->numero_orden.'\" class=\"btn btn-danger btn-xs btn-grad ret_del\"><i class=\"fa fa-trash-o\"></i></a> <a href=\"#\" title=\"Imprimir Servicio\" id=\"'.$row->id_servicio.'\" name=\"'.  $row->numero_orden .'\" class=\"btn bg-navy color-palette btn-xs btn-grad pro_imp\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Imprimir Etiqueta\" id=\"'.$row->id_servicio.'\" name=\"'.  $row->numero_orden .'\" class=\"btn bg-navy color-palette btn-xs btn-grad imp_etiqueta\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Enviar Correo\" id=\"'.$row->id_servicio.'\" class=\"btn bg-orange color-palette btn-xs btn-grad enviarcorreo\"><i class=\"fa fa-envelope-o\"></i></a> <a href=\"#\" title=\"Facturar Servicio\" id=\"'.$row->id_servicio.'\" name=\"'.  $row->numero_orden .'\" class=\"btn bg-blue color-palette btn-xs btn-grad pro_fac\"><i class=\"fa fa-file-text\"></i></a> </div>';
                else                
                  $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Editar Servicio Tecnico\" id=\"'.$row->id_servicio.'\" class=\"btn btn-success btn-xs btn-grad ret_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_servicio.'\" name=\"'.$row->numero_orden.'\" class=\"btn btn-danger btn-xs btn-grad ret_del\"><i class=\"fa fa-trash-o\"></i></a> <a href=\"#\" title=\"Imprimir Servicio\" id=\"'.$row->id_servicio.'\" name=\"'.  $row->numero_orden .'\" class=\"btn bg-navy color-palette btn-xs btn-grad pro_imp\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Imprimir Etiqueta\" id=\"'.$row->id_servicio.'\" name=\"'.  $row->numero_orden .'\" class=\"btn bg-navy color-palette btn-xs btn-grad imp_etiqueta\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Enviar Correo\" id=\"'.$row->id_servicio.'\" class=\"btn bg-orange color-palette btn-xs btn-grad enviarcorreo\"><i class=\"fa fa-envelope-o\"></i></a> </div>';
            }
              else{
                $ver = '<div class=\"text-center \">';
                if ($usua->id_usu == $row->id_vendedor){
                  $ver = '<a href=\"#\" title=\"Editar Servicio Tecnico\" id=\"'.$row->id_servicio.'\" class=\"btn btn-success btn-xs btn-grad ret_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_servicio.'\" name=\"'.$row->numero_orden.'\" class=\"btn btn-danger btn-xs btn-grad ret_del\"><i class=\"fa fa-trash-o\"></i></a>';
                }
                $ver.=' <a href=\"#\" title=\"Imprimir Servicio\" id=\"'.$row->id_servicio.'\" name=\"'.  $row->numero_orden .'\" class=\"btn bg-navy color-palette btn-xs btn-grad pro_imp\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Imprimir Etiqueta\" id=\"'.$row->id_servicio.'\" name=\"'.  $row->numero_orden .'\" class=\"btn bg-navy color-palette btn-xs btn-grad imp_etiqueta\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Enviar Correo\" id=\"'.$row->id_servicio.'\" class=\"btn bg-orange color-palette btn-xs btn-grad enviarcorreo\"><i class=\"fa fa-envelope-o\"></i></a> </div>';  

              }
            }

            $fec = str_replace('-', '/', $row->fecha_emision); $fec = date("d/m/Y", strtotime($fec)); 

            $estado = $row->nombre_estado;
            if ($factura != 0) { $estado = "FACTURADO"; }

            $detalleservicio = "";
            if ($idconfig > 0){
              $objdetalle = $this->Serviciotecnico_model->lst_detalle_servicio($row->id_servicio);
              if (count($objdetalle) > 0) {
                $iddetalle = $objdetalle[0]->id_detalle;
                $objsubdetalle = $this->Serviciotecnico_model->sel_subdetalle_servicio_idconfig($iddetalle, $idconfig);
                if (count($objsubdetalle) > 0){
                  $detalleservicio = $objsubdetalle->valor;
                }
              }
            }

            $tabla.='{  "id":"' .$row->id_servicio. '",
                        "sucursal":"' .$row->nom_sucursal. '",
                        "fecha":"' .$fec. '",
                        "numero_orden":"' .$row->numero_orden. '",
                        "cliente":"' .addslashes($row->nom_cliente). '",
                        "descripcion":"' .addslashes($row->descripcion). '",
                        "estado":"' .$estado. '",
                        "detalleservicio":"' .addslashes($detalleservicio). '",
                        "ver":"'.$ver.'"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function add_servicio(){
        $idusu = $this->session->userdata("sess_id");
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $idsurcursal = 0;
        if ($sucursales) { $idsurcursal = $sucursales[0]->id_sucursal; }
        $nuevaorden = $this->Serviciotecnico_model->get_proxnumeroorden($idsurcursal);
        $cliente = $this->Serviciotecnico_model->carga_cliente($idusu,$idsurcursal,$nuevaorden);
        $detalles = $this->Serviciotecnico_model->carga_detalle_tmp($idusu);
        $nombredetalle = $this->Serviciotecnico_model->carga_nombredetalle();
        $estados = $this->Serviciotecnico_model->lst_estadoservicio();
        $empleados = $this->Empleado_model->lst_empleadotecnico();
        $tipident = $this->Facturar_model->tipo_identificacion();
        $data["tipident"] = $tipident;
        $data["sucursales"] = $sucursales;
        $data["empleados"] = $empleados;
        $data["cliente"] = $cliente;
        $data["detalles"] = $detalles;
        $data["nombredetalle"] = $nombredetalle;
        $data["estados"] = $estados;
        $data["nuevaorden"] = $nuevaorden;
        $data["base_url"] = base_url();
        $data["content"] = "serviciotecnico_add";
        $this->load->view("layout", $data);
    } 

    public function detalle_add(){
        $estados = $this->Serviciotecnico_model->lst_estadoservicio();
        $empleados = $this->Empleado_model->lst_empleadotecnico();
        $configservicio = $this->Serviciotecnico_model->lst_configservicio();
        $detalles = $this->Serviciotecnico_model->carga_subdetalle_tmp();
        $data["detalles"] = $detalles;
        $data["empleados"] = $empleados;
        $data["estados"] = $estados;
        $data["configservicio"] = $configservicio;
        $data["base_url"] = base_url();
        $this->load->view("serviciotecnico_detalle_add", $data);
    } 

    public function tmp_serviciotecnico_detalle() {
        $this->session->unset_userdata("tmp_servicio_iddetalle"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_servicio_iddetalle", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_servicio_iddetalle", $id);
        } else {
            $this->session->set_userdata("tmp_servicio_iddetalle", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function detalle_edit(){
        $iddetalle = $this->session->userdata("tmp_servicio_iddetalle");
        $cliente = $this->Serviciotecnico_model->carga_detalletmp_id($iddetalle);
        $detalles = $this->Serviciotecnico_model->carga_subdetalle_tmp($iddetalle);
        $estados = $this->Serviciotecnico_model->lst_estadoservicio();
        $empleados = $this->Empleado_model->lst_empleadotecnico();
        $configservicio = $this->Serviciotecnico_model->lst_configservicio();
        $data["empleados"] = $empleados;
        $data["cliente"] = $cliente;
        $data["detalles"] = $detalles;
        $data["estados"] = $estados;
        $data["configservicio"] = $configservicio;
        $data["base_url"] = base_url();
        $this->load->view("serviciotecnico_detalle_add", $data);
    } 

    public function del_detalletmp(){
      $iddetalle = $this->input->post("id");      
      if (($iddetalle == '') || ($iddetalle == NULL)) { $iddetalle = 0; }

      $iddetalle = $this->Serviciotecnico_model->del_detalletmpservicio($iddetalle);        

      $arr['resu'] = $iddetalle;
      print json_encode($arr);
    }

    public function upd_detalletmp(){
      $idusu = $this->session->userdata("sess_id");
      $iddetalle = $this->session->userdata("tmp_servicio_iddetalle");
      if (($iddetalle == '') || ($iddetalle == NULL)) { $iddetalle = 0; }

      $idserie = $this->input->post("idserie");      
      if (($idserie == '') || ($idserie == NULL)) { $idserie = 0; }
      $tecnico = $this->input->post("cmb_encargado");
      if (($tecnico == '') || ($tecnico == NULL)) { $tecnico = 0; }
      $descripcion = $this->input->post("descripcion");
      $estado = $this->input->post("cmb_estado");
      $fec = $this->input->post("fecrealizado");
      $fec = str_replace('/', '-', $fec); 
      $fecrealizado = date("Y-m-d", strtotime($fec));
      $trabajorealizado = $this->input->post("trabajorealizado");
      $fec = $this->input->post("fecentregado");
      $fec = str_replace('/', '-', $fec); 
      $fecentregado = date("Y-m-d", strtotime($fec));
      $descripcion = $this->input->post("observaciones");

      $valcfg = array();
      foreach($this->input->post() as $nombre_campo => $valor){
          $campo = substr($nombre_campo, 0,12); 

          if($campo == "detallevalor"){
              $c = substr($nombre_campo, 12); 
              $valcfg[$c] = $valor;  
          }
      }

      if ($iddetalle == 0)  {
        $iddetalle = $this->Serviciotecnico_model->ins_detalletmpservicio($idusu, $idserie, $tecnico, $descripcion, $estado, $fecrealizado, $trabajorealizado, $fecentregado, $valcfg);        
      } else {
        $this->Serviciotecnico_model->upd_detalletmpservicio($iddetalle, $idserie, $tecnico, $descripcion, $estado, $fecrealizado, $trabajorealizado, $fecentregado, $valcfg);        
      }
      $arr['resu'] = $iddetalle;
      print json_encode($arr);
    }

    /* VALIDA CLIENTE */
    public function valcliente(){
        $idcliente = $this->input->post('idcliente');
        $resu = $this->Pedido_model->valida_cliente($idcliente);
        if(count($resu) > 0){ $mens = $resu[0];  }
        else { $mens = $resu[0]; }
        $arr['mens'] = $mens;
        print json_encode($arr);
    }

    public function busca_nombre(){
        $nom = $this->input->post('nom');
        $resu = $this->Pedido_model->busca_cliente($nom);
        if(count($resu) > 0){ $mens = $resu[0];  }
        else { $mens = $resu[0]; }
        $arr['mens'] = $mens;
        print json_encode($arr);
    }

    /* GUARDAR DATOS DEL CLIENTE EN TABLA Servicio_TMP */
    public function upd_cliente(){
      $idusu = $this->session->userdata("sess_id");

      $idc = $this->input->post("idc");      
      $idcli = $this->input->post("idcli");
      $idtp = $this->input->post("idtp");
      $nom = $this->input->post("nom");
      $tel = $this->input->post("tel");
      $cor = $this->input->post("cor");
      $dir = $this->input->post("dir");
      $ciu = $this->input->post("ciu");

      $resu = $this->Serviciotecnico_model->upd_cliente($idusu, $idcli, $idtp, $nom, $tel, $cor, $dir, $ciu, $idc);
      $arr['resu'] = $resu;
      print json_encode($arr);
    }

    /* GUARDAR DATOS GENRALES DEL Servicio EN TABLA Servicio_TMP */
    public function upd_tmpgenservicio(){
      $idusu = $this->session->userdata("sess_id");

      $idsuc = $this->input->post("cmb_sucursal");      

      $fec = $this->input->post('fecha');
      $fechaser = str_replace('/', '-', $fec); 
      $fechaser = date("Y-m-d", strtotime($fechaser));

      $desc = $this->input->post("txt_descripcion");      
      $idest = $this->input->post("cmb_estado");

      $costo_estimado = $this->input->post("costo_estimado");      

      $resu = $this->Serviciotecnico_model->upd_tmpgenservicio($idusu, $idsuc, $fechaser, $desc, $idest, 
                                                               $costo_estimado);
      $arr['resu'] = $resu;
      print json_encode($arr);
    }



    /* FUNCIONES ESPECIALES DE BUSQUEDA */
    public function valproductoserie(){
        $tmpArray=array();
        $serie = $this->input->get('serie');
        $data = $this->Serviciotecnico_model->valida_serie($serie);
        foreach ($data as $row) {
            $tmpArray[] = $row->numeroserie;
        }
        print json_encode($tmpArray);
    }

    public function busca_serie(){
        $serie = $this->input->post('serie');
        $resu = $this->Serviciotecnico_model->busca_serie($serie);
        if(count($resu) > 0){ $mens = $resu[0];  }
        else { $mens = $resu[0]; }
        $arr['mens'] = $mens;
        print json_encode($arr);
    }

    /* GUARDAR Detalles Servicio EN TABLA Servicio_TMP */
    public function upd_detalletmpservicio(){
      $idusu = $this->session->userdata("sess_id");

      $idcfg = $this->input->post("idcfg");      
      $valcfg = $this->input->post("valcfg");      

      $resu = $this->Serviciotecnico_model->upd_detalletmpservicio($idusu, $idcfg, $valcfg);
      $arr['resu'] = $resu;
      print json_encode($arr);
    }

    public function existe_numerorden(){
      $idusu = $this->session->userdata("sess_id");
      $idsucursal = $this->input->post('sucursal'); 
      $numerorden = $this->input->post('numerorden'); 
      $resu = $this->Serviciotecnico_model->existe_numerorden($idusu, $idsucursal, $numerorden);
      $arr['resu'] = $resu;
      print json_encode($arr); 
    }


    /* Grabar SErvicio */
    public function guardar(){
        $idusu = $this->session->userdata("sess_id");
        $id = $this->input->post('txt_id'); 
        if($id == 0){
            $id = $this->Serviciotecnico_model->ins_servicio($idusu);
        } else {
            $this->Serviciotecnico_model->upd_servicio($id, $idusu);
        }
        $arr['mens'] = $id;
        print json_encode($arr); 
    }

    /* Abrir Vista Servicio para Modificar */
    public function actualiza_servicio(){
        $id = $this->session->userdata("tmp_servicio_id");
        $idusu = $this->session->userdata("sess_id");
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $idsurcursal = 0;
        $cliente = $this->Serviciotecnico_model->carga_tmpgenservicio($id, $idusu);

        $tipident = $this->Facturar_model->tipo_identificacion();
        $data["tipident"] = $tipident;

        if ($cliente) { $idsurcursal = $cliente->id_sucursal; }
        $nombredetalle = $this->Serviciotecnico_model->carga_nombredetalle();        
        $detalles = $this->Serviciotecnico_model->carga_detalle_tmp($idusu);
        if ($detalles){
          $this->session->unset_userdata("tmp_servicio_iddetalle"); 
          $this->session->set_userdata("tmp_servicio_iddetalle", $detalles[0]->id_detalle);

          $totalprod = $this->Serviciotecnico_model->valortotal_produtil_tmp($detalles[0]->id_detalle);
          $data["totalprod"] = $totalprod;
        }
        $estados = $this->Serviciotecnico_model->lst_estadoservicio();
        $empleados = $this->Empleado_model->lst_empleadotecnico();
        $data["sucursales"] = $sucursales;
        $data["empleados"] = $empleados;
        $data["cliente"] = $cliente;
        $data["detalles"] = $detalles;        
        $data["nombredetalle"] = $nombredetalle;
        $data["estados"] = $estados;
        $data["base_url"] = base_url();
        $data["content"] = "serviciotecnico_add";
        $this->load->view("layout", $data);
    }

    public function del_servicio(){
        $id = $this->input->post('id'); 
        $resu = $this->Serviciotecnico_model->del_servicio($id);
        $arr['mens'] = $id;
        print json_encode($arr); 
    }

    /* ABRIR VENTANA PARA Imprimir  */
    public function imprimirservicio(){
        $factoriva = 12;
        date_default_timezone_set("America/Guayaquil");
        
        $id = $this->input->post('id');

        $servicio = $this->Serviciotecnico_model->get_servicio_id($id);
        $sucursal = $this->Sucursal_model->sel_suc_id($servicio->id_sucursal);

        $tabla ="\r\n" . "SERVICIO TECNICO". "\r\n";
        $tabla.= "\r\n";
        $emp = $this->Empresa_model->sel_emp_id($sucursal->id_empresa);
/*        $emp = $this->Empresa_model->emp_get();*/
        $tabla.=$sucursal->nom_sucursal . "\r\n";
        $tabla.="RUC:" . chr(32) . $emp->ruc_emp . "\r\n";
        $tabla.=$sucursal->dir_sucursal . "\r\n";
        $tabla.=$sucursal->telf_sucursal . "\r\n";
/*        $tabla.=$emp->nom_emp . "\r\n";
        $tabla.="RUC:" . chr(32) . $emp->ruc_emp . "\r\n";
        $tabla.=$emp->dir_emp . "\r\n";*/
        $tabla.= "\r\n";

        $configservicio = $this->Serviciotecnico_model->lst_configservicio();
/*        $mostrarsecc_serie = $configservicio->habilita_serie;
        $mostrarsecc_detalle = $configservicio->habilita_detalle;
        $mostrarsecc_produtil = $configservicio->habilita_productoutilizado;
        $mostrarsecc_abono = $configservicio->habilita_abono;
        $mostrar_encargado = $configservicio->habilita_encargado;*/



        $tabla.="ORDEN:" . "\x1F \x1F" . $servicio->numero_orden . "\r\n";        
        $strdate = date("d/m/Y",strtotime($servicio->fecha_emision));
        $tabla.="FECHA:" . "\x1F \x1F" . $strdate . "\r\n";
        $tabla.="CLIENTE:" . "\x1F \x1F" . $servicio->nom_cliente . "\r\n";        
        $tabla.="DIRECCION:". "\x1F \x1F" . $servicio->direccion_cliente . "\r\n";        
        $tabla.="CI/RUC:". "\x1F \x1F" . $servicio->ident_cliente . "\r\n";        
        $tabla.="TELEF.:". "\x1F \x1F" . $servicio->telefonos_cliente . "\r\n";        
        /*
        if ($row->id_responsable){
          $tabla.="TECNICO:". "\x1F \x1F" . $row->nombre_empleado . "\r\n";        
        }
        $tabla.="ESTADO:". "\x1F \x1F" . $row->nombre_estado . "\r\n";        
        if ($row->fecha_realizado){
          $strdate = date("d/m/Y",strtotime($row->fecha_realizado));
          $tabla.="FECHA REALIZADO:". "\x1F \x1F" . $strdate . "\r\n";        
          if ($row->trabajo_realizado){
            if (strlen($row->trabajo_realizado) > 0){
              $tabla.="TRABAJO REALIZADO:". "\x1F \x1F" . $row->trabajo_realizado . "\r\n";        
            }
          }
          if ($row->fecha_entregado){
            $strdate = date("d/m/Y",strtotime($row->fecha_entregado));
            $tabla.="ENTREGADO:". "\x1F \x1F" . $strdate . "\r\n";        
          }
        }
        if ($row->id_serie){
          $tabla.= "\r\n";
          $tabla.="#SERIE:". "\x1F \x1F" . $row->numeroserie . "\r\n";        
        } 
        */   
        $tabla.= "\r\n";
        $tabla.="DESCRIPCION:". "\x1F \x1F" . $servicio->descripcion . "\r\n";        
        $tabla.= "\r\n";

        $nombredetalle = $this->Serviciotecnico_model->carga_nombredetalle();
        $registro = $this->Serviciotecnico_model->lst_detalle_servicio($id);
        if (count($registro) > 0){
          $arrsize=array();
          $tabla.="DETALLES:". "\r\n";        
          $tabla.= "\r\n";
          $tabla.="#     SERIE     ";        
          foreach ($nombredetalle as $row) {
              $tabla.= strtoupper($row->nombre_configdetalle) . "     ";
              $arrsize[$row->id_config] = strlen($row->nombre_configdetalle);
          }
          $tabla.= "\r\n";
          $registro = $this->Serviciotecnico_model->lst_detalle_servicio($id);
          $iddetant = 0;
          $num = 0;
          $numitem = 0;
          foreach ($registro as $row) {
            $trabajorealizado = "";
            if ($iddetant != $row->id_detalle){
              $trabajorealizado.= $row->trabajo_realizado. "\r\n";        
              $num++;
              $tabla.= $num;
              $tmpcant = strlen($num);
              $top = 6;
              while ($tmpcant < $top){
                  $tabla.= "\x1F \x1F";
                  $tmpcant++;
              }
              $tabla.= $row->numeroserie;
              $tmpcant = strlen($row->numeroserie);
              $top = 10;
              while ($tmpcant < $top){
                  $tabla.= "\x1F \x1F";
                  $tmpcant++;
              }
              $iddetant = $row->id_detalle;
              $numitem = 1;
            }
            $top = 5;
            if (count($arrsize) > 0) { $top += $arrsize[$row->id_config]; }
            $tmpstr = substr(trim($row->valor),0,$top);
            $tabla.= $tmpstr;
            /*$tabla.= $row->valor . "     ";*/
            $tmpcant = strlen($tmpstr);

            while ($tmpcant < $top){
                $tabla.= "\x1F \x1F";
                $tmpcant++;
            }

            if ($numitem == count($nombredetalle)){
              $tabla.= "\r\n";
            }
            $numitem++;

            $tabla.= "\r\n";
            if (trim($trabajorealizado) != ''){
              $tabla.="INFORME TECNICO:". "\r\n";        
              $tabla.= "\r\n";
              $tabla.= $trabajorealizado. "\r\n";                
              $tabla.= "\r\n";
            }  
            
          }  

        }

        $tabla.= "\r\n";

        $productos = $this->Serviciotecnico_model->lst_producto_servicio($id);
        if (count($productos) > 0){
          $tabla.="PRODUCTOS UTILIZADOS:". "\r\n";        
          $tabla.="CANTIDAD   PRODUCTO". "\r\n";        
          foreach ($productos as $row) {
            $tabla.= $row->cantidad;
            $tmpcant = strlen(trim($row->cantidad));
            while ($tmpcant < 11){
                $tabla.= "\x1F \x1F";
                $tmpcant++;
            }
            $tabla.= $row->pro_nombre;
            $tabla.= "\r\n";
          }
          $tabla.= "\r\n";
        }


        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("serviciotecnico_imprimir", $data);

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

    public function facturar(){
      $idusu = $this->session->userdata("sess_id");      
      $idservicio = $this->input->post("id");
      $servicio = $this->Serviciotecnico_model->get_servicio_id($idservicio);

//      $lstcaja = $this->Facturar_model->lst_caja($idusu);
      $lstcaja = $this->Cajaefectivo_model->lst_caja_sucursal($servicio->id_sucursal);
      $caja = 0;
      if ($lstcaja) { $caja = $lstcaja[0]->id_caja; }
      $resu = $this->Serviciotecnico_model->genera_factura($idservicio, $idusu, $caja);
      $arr['resu'] = $resu;
      print json_encode($arr);
    }

    public function listadoProdUtil() {
        $iddetalle = $this->session->userdata("tmp_servicio_iddetalle");
        if (($iddetalle == NULL) || ($iddetalle == '')) { $iddetalle = 0; }

        $registro = $this->Serviciotecnico_model->lst_produtil_tmp($iddetalle);
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id.'\" name=\"'.addslashes($row->pro_nombre).'\" class=\"btn btn-danger btn-xs btn-grad produtil_del\"><i class=\"fa fa-trash-o\"></i></a> </div>';

            $cant = '<div ><input type=\"text\" class=\"col-md-12 tdvalor upd_prodcant\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.$row->cantidad.'\" ></div>';

            $precio = '<div ><input type=\"text\" class=\"col-md-12 tdvalor upd_prodprecio\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.$row->precio.'\" ></div>';

            $tabla.='{  "id":"' .$row->id. '",
                        "codigo":"' .addslashes($row->pro_codigobarra). '",
                        "nombre":"' .addslashes($row->pro_nombre). '",
                        "cantidad":"' .$cant. '",
                        "precio":"' .$precio. '",
                        "subtotal":"' .number_format($row->precio * $row->cantidad,2). '",
                        "ver":"'.$ver.'"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function edit_producto(){
        $data["base_url"] = base_url();
        $this->load->view("serviciotecnico_producto_add", $data);
    } 

    /* CARGA DE DATO AL DATATABLE */
    public function lstProducto() {
      $registro = $this->Serviciotecnico_model->lst_producto();
      $tabla = "";
      foreach ($registro as $row) {
          $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Añadir\" id=\"'.$row->pro_id.'\" name=\"'.$row->id_alm.'\" class=\"btn btn-success btn-xs btn-grad addproservicio\"><i class=\"fa fa-cart-plus \"></i></a>  </div>';
          $tabla.='{"codbarra":"' . addslashes($row->pro_codigobarra) . '",
                    "codauxiliar":"' . addslashes($row->pro_codigoauxiliar) . '",
                    "nombre":"' . addslashes(substr($row->pro_nombre,0,40)) . '",
                    "preciocompra":"' . $row->pro_preciocompra . '",
                    "existencia":"' . $row->existencia . '",   
                    "nombrecorto":"' . addslashes($row->nombrecorto) . '",                                                               
                    "almacen":"' . addslashes($row->almacen_nombre) . '",                                                               
                    "ver":"'.$ver.'"},';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';
    }

    public function add_producto(){
      $iddetalle = $this->session->userdata("tmp_servicio_iddetalle");
      $pro = $this->input->post("id");
      $almacen = $this->input->post("almacen");
      $this->Serviciotecnico_model->add_producto($iddetalle, $pro, $almacen);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function upd_producto(){
      $id = $this->input->post("id");
      $cant = $this->input->post("cant");
      $this->Serviciotecnico_model->upd_producto($id, $cant);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function upd_productoprecio(){
      $id = $this->input->post("id");
      $precio = $this->input->post("precio");
      $this->Serviciotecnico_model->upd_productoprecio($id, $precio);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function del_producto(){
      $id = $this->input->post("id");
      $this->Serviciotecnico_model->del_producto($id);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function listadoAbonos() {
        $idusu = $this->session->userdata("sess_id");      

        $registro = $this->Serviciotecnico_model->lst_abonos_tmp($idusu);
        $tabla = "";
        foreach ($registro as $row) {
            $fec = str_replace('-', '/', $row->fecha_registro); $fec = date("d/m/Y H:i", strtotime($fec)); 
            $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Editar\" id=\"'.$row->id_abono.'\" class=\"btn btn-success btn-xs btn-grad abono_upd\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_abono.'\" name=\"'.$row->nro_comprobante.'\" class=\"btn btn-danger btn-xs btn-grad abono_del\"><i class=\"fa fa-trash-o\"></i></a> </div>';

            $tabla.='{  "id":"' .$row->id_abono. '",
                        "documento":"' .$row->numerodocumento. '",
                        "tipo":"' .$row->nombre_formapago. '",
                        "fecha":"' .$fec. '",
                        "valor":"' .$row->monto. '",
                        "ver":"'.$ver.'"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function add_abono(){
      $formapago = "Abono de Servicio";
      /* Cargar Listado de Formas de Pago */
      $forpago = $this->Facturar_model->lista_formapago(0);
      /* Cargar Listado de Bancos */
      $banco = $this->Facturar_model->bancos();
      /* Cargar Listado de Tarjetas */
      $tarjeta = $this->Facturar_model->tarjetas();

      $idusu = $this->session->userdata("sess_id");
      $lstcaja = $this->Facturar_model->lst_caja($idusu);
      
      $maxvalor = $this->input->post("montopendiente");

      $data["maxvalor"] = $maxvalor;

      $data["formapago"] = $formapago;
      $data["forpago"] = $forpago;
      $data["bancos"] = $banco;
      $data["tarjetas"] = $tarjeta;
      $data["lstcaja"] = $lstcaja;
      $data["base_url"] = base_url();
      $this->load->view("facturar_tipopago", $data);   
    } 

    public function edit_abono(){
      $idreg = $this->input->post("idreg");
      $idfp = $this->input->post("idfp");
      $edifp = $this->Serviciotecnico_model->ediforpagoserv($idreg);  
      $idforpago = $edifp->id_formapago;
      $tipofp = $this->Facturar_model->selforpago($idforpago); 
      $formapago = "Credito";
      $forpago = $this->Facturar_model->lista_formapago(0);
      $banco = $this->Facturar_model->bancos();
      $tarjeta = $this->Facturar_model->tarjetas();

      $idusu = $this->session->userdata("sess_id");
      $lstcaja = $this->Facturar_model->lst_caja($idusu);

      $maxvalor = $this->input->post("montopendiente");
      $data["maxvalor"] = $maxvalor + $edifp->monto;

      $data["edifp"] = $edifp;
      $data["tipofp"] = $tipofp;
      $data["formapago"] = $formapago;
      $data["forpago"] = $forpago;
      $data["bancos"] = $banco;
      $data["tarjetas"] = $tarjeta;
      $data["lstcaja"] = $lstcaja;
      $data["base_url"] = base_url();
      $this->load->view("facturar_tipopago", $data);
    }

    public function guardar_abono(){
      $idreg = $this->input->post("idreg");
      $idusu = $this->session->userdata("sess_id");      
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
      $idcaja = $this->input->post("idcaja");      
      if($idreg == 0){
        $addfp = $this->Serviciotecnico_model->add_abonoservicio($idusu, $fp, $monto, $fechat, $tiptarjeta, $nrotar, $banco, $tbanco, $nrodoc, $tnrodoc, $descdoc, $tdescdoc, $fechae, $fechac, $nrocta, $idcaja);
      }else{
        $updfp = $this->Serviciotecnico_model->upd_abonoservicio($idreg, $idusu, $fp, $monto, $fechat, $tiptarjeta, $nrotar, $banco, $tbanco, $nrodoc, $tnrodoc, $descdoc, $tdescdoc, $fechae, $fechac, $nrocta, $idcaja);
      }
      /*$selfp = $this->facturar_model->sumforpago($idventa, $fpvtc);*/
      $abonos = $this->Serviciotecnico_model->sel_tmpabonos($idusu);
      $arr['resu'] = $abonos;
      print json_encode($arr);               
    }

    public function del_abono() {
        $id = $this->input->post("id");
        $this->Serviciotecnico_model->del_abono($id);
        $idusu = $this->session->userdata("sess_id");      
        $abonos = $this->Serviciotecnico_model->sel_tmpabonos($idusu);
        $arr['resu'] = $abonos;
        print json_encode($arr);               
    }

    public function serviciotecnico_config() {
        $data["base_url"] = base_url();
        $data["content"] = "serviciotecnico_config";
        $this->load->view("layout", $data);
    }

    public function listadoConfigDetalle() {
        $registro = $this->Serviciotecnico_model->lst_nombredetalle();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Editar\" id=\"'.$row->id_config.'\" class=\"btn btn-success btn-xs btn-grad det_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_config.'\" name=\"'.addslashes($row->nombre_configdetalle).'\" class=\"btn btn-danger btn-xs btn-grad det_del\"><i class=\"fa fa-trash-o\"></i></a> </div>';

            $estado = ( $row->activo == 1 ) ? 'SI' : 'NO';

            $tabla.='{  "id":"' .$row->id_config. '",
                        "nombre":"' .addslashes($row->nombre_configdetalle). '",
                        "estado":"' .$estado. '",
                        "ver":"'.$ver.'"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function tmp_detcfg() {
        $this->session->unset_userdata("tmp_detcfg_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_detcfg_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_detcfg_id", $id);
        } else {
            $this->session->set_userdata("tmp_detcfg_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function upd_configdetalle(){
        $id = $this->session->userdata("tmp_detcfg_id");
        $data["base_url"] = base_url();
        $obj = $this->Serviciotecnico_model->sel_configdetalle_id($id);
        $data["obj"] = $obj;
        $this->load->view("serviciotecnico_config_add", $data);
    }

    public function guardar_cfg_detalle(){
        $id = $this->input->post('txt_id'); 
        $nombre = $this->input->post('txt_nombre');
        $activo = $this->input->post('chkactivo');
        $mostrar = $this->input->post('chkmostrar');
        if($activo == 'on'){ $activo = 1; } else { $activo = 0; }
        if($mostrar == 'on'){ $mostrar = 1; } else { $mostrar = 0; }
        if($id != 0){
            $resu = $this->Serviciotecnico_model->upd_configdetalle($id, $nombre, $activo, $mostrar);
        } else {
            $resu = $this->Serviciotecnico_model->add_configdetalle($nombre, $activo, $mostrar);
        }
        $arr['mens'] = $id;
        print json_encode($arr); 
    }

    public function add_configdetalle(){
        $data["base_url"] = base_url();
        $this->load->view("serviciotecnico_config_add", $data);
    } 

    public function del_configdetalle(){
      $id = $this->input->post("id");
      $res = $this->Serviciotecnico_model->del_configdetalle($id);
      $arr['mens'] = $res;
      print json_encode($arr);
    }

    /* Exportar listadoservicio a Excel */
    public function xls_listadoservicio(){
        $desde = $this->session->userdata("tmp_servicio_desde");
        $hasta = $this->session->userdata("tmp_servicio_hasta");        
        $estado = $this->session->userdata("tmp_servicio_estado");        
        $tecnico = $this->session->userdata("tmp_servicio_tecnico");        

        $currencyFormat = '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';
        $percentFormat = '_( #,##0_);_( (#,##0);_( "-"??_);_(@_)';
        $textFormat='@';//'General','0.00','@'

        $listaservicio = $this->Serviciotecnico_model->lst_servicio($desde, $hasta, $estado, $tecnico);
        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ListadoServicios');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Listado de Servicios');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Sucursal');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Fecha Ingreso');
        $this->excel->getActiveSheet()->setCellValue('C3', '#Orden');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Cliente');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Técnico');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Descripción');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Estado');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Servicio');
        $this->excel->getActiveSheet()->setCellValue('I3', 'Mercancia');
        $this->excel->getActiveSheet()->setCellValue('J3', 'Fecha Realizado');
        $this->excel->getActiveSheet()->setCellValue('K3', 'Trabajo Realizado');
        $this->excel->getActiveSheet()->setCellValue('L3', 'Fecha Entregado');

        $this->excel->getActiveSheet()->getStyle('A3:L3')->getFont()->setBold(true);

        $nombres = $this->Serviciotecnico_model->carga_nombredetalle();
        $col = 13;
        $row = 3;
        foreach ($nombres as $nombre) {
          $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nombre->nombre_configdetalle);  
          $this->excel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
          $col++;       
        }  
        

        $fila = 4;          
        $filaini = $fila;          

        foreach ($listaservicio as $ser) {

            $fec = str_replace('-', '/', $ser->fecha_emision); $fec = date("d/m/Y", strtotime($fec)); 
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, addslashes($ser->nom_sucursal));
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $fec);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ser->numero_orden);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, addslashes($ser->nom_cliente));            

            $detalles = $this->Serviciotecnico_model->lst_detalleservicio($ser->id_servicio);
            foreach ($detalles as $det) {
              $this->excel->getActiveSheet()->setCellValue('E'.$fila, addslashes($det->nombre_empleado));
              $this->excel->getActiveSheet()->setCellValue('F'.$fila, addslashes($det->descripcion));
              $this->excel->getActiveSheet()->setCellValue('G'.$fila, addslashes($det->nombre_estado));

              $this->excel->getActiveSheet()->getStyle('H' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
              $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($det->montoservicio,2));
              $this->excel->getActiveSheet()->getStyle('I' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
              $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($det->montomercancia,2));

              $fec = '';
              if ($det->id_estado >= 3){
                $fec = str_replace('-', '/', $det->fecha_realizado); $fec = date("d/m/Y", strtotime($fec)); 
              }  
              $this->excel->getActiveSheet()->setCellValue('J'.$fila, $fec);
              $this->excel->getActiveSheet()->setCellValue('K'.$fila, addslashes($det->trabajo_realizado));
              $fec = '';
              if ($det->id_estado >= 4){
                $fec = str_replace('-', '/', $det->fecha_entregado); $fec = date("d/m/Y", strtotime($fec)); 
              }  
              $this->excel->getActiveSheet()->setCellValue('L'.$fila, $fec);

              $detallesconfig = $this->Serviciotecnico_model->lst_subdetalle_servicio($det->id_detalle);
              $col = 13;
              foreach ($detallesconfig as $subdet) {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, utf8_decode($subdet->valor));
                $col++;
              }  

              $fila++;          
            }

        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('G'.$fila, "TOTAL");
        $this->excel->getActiveSheet()->getStyle('H' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('H' . $fila, '=SUM(H'.($filaini).':H'.($fila-2).')');
        $this->excel->getActiveSheet()->getStyle('I' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('I' . $fila, '=SUM(I'.($filaini).':I'.($fila-2).')');

        $this->excel->getActiveSheet()->freezePane('A4');

        for ($col = 13; $col <= PHPExcel_Cell::columnIndexFromString($this->excel->getActiveSheet()->getHighestDataColumn()); $col++) {
            $this->excel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
        }        
      
        $filename='listadoservicio.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');        
    }  

    /* Exportar serviciorealizado a Excel */
    public function xls_serviciorealizado(){
        $desde = $this->session->userdata("tmp_servicio_desde");
        $hasta = $this->session->userdata("tmp_servicio_hasta");        
        $estado = $this->session->userdata("tmp_servicio_estado");        
        $tecnico = $this->session->userdata("tmp_servicio_tecnico");        

        $currencyFormat = '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';
        $percentFormat = '_( #,##0_);_( (#,##0);_( "-"??_);_(@_)';
        $textFormat='@';//'General','0.00','@'

        $listaservicio = $this->Serviciotecnico_model->lst_serviciorealizado($desde, $hasta, $estado, $tecnico);
        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ListadoServicios');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Servicios Realizados');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Sucursal');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Fecha Ingreso');
        $this->excel->getActiveSheet()->setCellValue('C3', '#Orden');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Cliente');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Técnico');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Descripción');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Estado');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Servicio');
        $this->excel->getActiveSheet()->setCellValue('I3', 'Mercancia');
        $this->excel->getActiveSheet()->setCellValue('J3', 'Fecha Realizado');
        $this->excel->getActiveSheet()->setCellValue('K3', 'Trabajo Realizado');
        $this->excel->getActiveSheet()->setCellValue('L3', 'Fecha Entregado');

        $this->excel->getActiveSheet()->getStyle('A3:L3')->getFont()->setBold(true);

        $nombres = $this->Serviciotecnico_model->carga_nombredetalle();
        $col = 13;
        $row = 3;
        foreach ($nombres as $nombre) {
          $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nombre->nombre_configdetalle);  
          $this->excel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
          $col++;       
        }  

        $fila = 4;          
        $filaini = $fila;          

        foreach ($listaservicio as $ser) {

            $fec = str_replace('-', '/', $ser->fecha_emision); $fec = date("d/m/Y", strtotime($fec)); 
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, addslashes($ser->nom_sucursal));
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $fec);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ser->numero_orden);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, addslashes($ser->nom_cliente));            

            $detalles = $this->Serviciotecnico_model->lst_detalleservicio($ser->id_servicio);
            foreach ($detalles as $det) {
              $this->excel->getActiveSheet()->setCellValue('E'.$fila, addslashes($det->nombre_empleado));
              $this->excel->getActiveSheet()->setCellValue('F'.$fila, addslashes($det->descripcion));
              $this->excel->getActiveSheet()->setCellValue('G'.$fila, addslashes($det->nombre_estado));

              $this->excel->getActiveSheet()->getStyle('H' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
              $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($det->montoservicio,2));
              $this->excel->getActiveSheet()->getStyle('I' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
              $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($det->montomercancia,2));

              $fec = '';
              if ($det->id_estado >= 3){
                $fec = str_replace('-', '/', $det->fecha_realizado); $fec = date("d/m/Y", strtotime($fec)); 
              }  
              $this->excel->getActiveSheet()->setCellValue('J'.$fila, $fec);
              $this->excel->getActiveSheet()->setCellValue('K'.$fila, addslashes($det->trabajo_realizado));
              $fec = '';
              if ($det->id_estado >= 4){
                $fec = str_replace('-', '/', $det->fecha_entregado); $fec = date("d/m/Y", strtotime($fec)); 
              }  
              $this->excel->getActiveSheet()->setCellValue('L'.$fila, $fec);

              $detallesconfig = $this->Serviciotecnico_model->lst_subdetalle_servicio($det->id_detalle);
              $col = 13;
              foreach ($detallesconfig as $subdet) {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, utf8_decode($subdet->valor));
                $col++;
              }  

              $fila++;          
            }

        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('G'.$fila, "TOTAL");
        $this->excel->getActiveSheet()->getStyle('H' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('H' . $fila, '=SUM(H'.($filaini).':H'.($fila-2).')');
        $this->excel->getActiveSheet()->getStyle('I' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('I' . $fila, '=SUM(I'.($filaini).':I'.($fila-2).')');

        $this->excel->getActiveSheet()->freezePane('A4');

        for ($col = 13; $col <= PHPExcel_Cell::columnIndexFromString($this->excel->getActiveSheet()->getHighestDataColumn()); $col++) {
            $this->excel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
        }        
      
        $filename='listadoservicio.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');        
    }  

    /* Exportar listadoservicio a Excel */
    public function xls_listadoservicioproducto(){
        $desde = $this->session->userdata("tmp_servicio_desde");
        $hasta = $this->session->userdata("tmp_servicio_hasta");        
        $estado = $this->session->userdata("tmp_servicio_estado");        
        $tecnico = $this->session->userdata("tmp_servicio_tecnico");        

        $currencyFormat = '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';
        $percentFormat = '_( #,##0_);_( (#,##0);_( "-"??_);_(@_)';
        $textFormat='@';//'General','0.00','@'

        $listaservicio = $this->Serviciotecnico_model->lst_serviciorealizado($desde, $hasta, $estado, $tecnico);
        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ListadoServicios');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Productos Utilizados');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Sucursal');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Fecha Ingreso');
        $this->excel->getActiveSheet()->setCellValue('C3', '#Orden');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Cliente');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Técnico');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Descripción');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Estado');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Servicio');
        $this->excel->getActiveSheet()->setCellValue('I3', 'Mercancia');
        $this->excel->getActiveSheet()->setCellValue('J3', 'Fecha Realizado');
        $this->excel->getActiveSheet()->setCellValue('K3', 'Trabajo Realizado');
        $this->excel->getActiveSheet()->setCellValue('L3', 'Fecha Entregado');
        $this->excel->getActiveSheet()->setCellValue('N3', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('O3', 'Codigo');
        $this->excel->getActiveSheet()->setCellValue('P3', 'Cantidad');
        $this->excel->getActiveSheet()->setCellValue('Q3', 'Precio');
        $this->excel->getActiveSheet()->setCellValue('R3', 'Subtotal');

        $this->excel->getActiveSheet()->getStyle('A3:R3')->getFont()->setBold(true);      

        $fila = 4;          
        $filaini = $fila;          

        foreach ($listaservicio as $ser) {
          if ($ser->id_estado >= 3){
            $fec = str_replace('-', '/', $ser->fecha_emision); $fec = date("d/m/Y", strtotime($fec)); 
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, addslashes($ser->nom_sucursal));
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $fec);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ser->numero_orden);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, addslashes($ser->nom_cliente));            

            $detalles = $this->Serviciotecnico_model->lst_detalleservicio($ser->id_servicio);
            foreach ($detalles as $det) {
              $this->excel->getActiveSheet()->setCellValue('E'.$fila, addslashes($det->nombre_empleado));
              $this->excel->getActiveSheet()->setCellValue('F'.$fila, addslashes($det->descripcion));
              $this->excel->getActiveSheet()->setCellValue('G'.$fila, addslashes($det->nombre_estado));

              $this->excel->getActiveSheet()->getStyle('H' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
              $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($det->montoservicio,2));
              $this->excel->getActiveSheet()->getStyle('I' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
              $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($det->montomercancia,2));

              $fec = '';
              if ($det->id_estado >= 3){
                $fec = str_replace('-', '/', $det->fecha_realizado); $fec = date("d/m/Y", strtotime($fec)); 
              }  
              $this->excel->getActiveSheet()->setCellValue('J'.$fila, $fec);
              $this->excel->getActiveSheet()->setCellValue('K'.$fila, addslashes($det->trabajo_realizado));
              $fec = '';
              if ($det->id_estado >= 4){
                $fec = str_replace('-', '/', $det->fecha_entregado); $fec = date("d/m/Y", strtotime($fec)); 
              }  
              $this->excel->getActiveSheet()->setCellValue('L'.$fila, $fec);

              $detallesconfig = $this->Serviciotecnico_model->lst_produtil_servicio($det->id_detalle);
              foreach ($detallesconfig as $subdet) {
                if ($subdet->pro_esservicio == 0) {
                  $col = 13;
                  $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, $fila, utf8_decode($subdet->pro_nombre));
                  $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, $fila, utf8_decode($subdet->pro_codigobarra));
                  $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, $fila, $subdet->cantidad);
                  $this->excel->getActiveSheet()->getStyleByColumnAndRow($col, $fila)->getNumberFormat()->setFormatCode($currencyFormat);
                  $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $subdet->precio);$col++;
                  $this->excel->getActiveSheet()->getStyleByColumnAndRow($col, $fila)->getNumberFormat()->setFormatCode($currencyFormat);
                  $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, number_format($subdet->cantidad * $subdet->precio,2));
                  $fila++;          
                }  
              }  
              if (count($detallesconfig) == 0){
                $fila++;          
              }  
            }
          }
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('G'.$fila, "TOTAL");
        $this->excel->getActiveSheet()->getStyle('H' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('H' . $fila, '=SUM(H'.($filaini).':H'.($fila-2).')');
        $this->excel->getActiveSheet()->getStyle('I' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('I' . $fila, '=SUM(I'.($filaini).':I'.($fila-2).')');

        $this->excel->getActiveSheet()->freezePane('D4');

        for ($col = 13; $col <= PHPExcel_Cell::columnIndexFromString($this->excel->getActiveSheet()->getHighestDataColumn()); $col++) {
            $this->excel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
        }        
      
        $filename='listadoservicio.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');        
    }  
    
    /* Exportar serviciorealizado a Excel */
    public function xls_serviciomecanico(){
        $desde = $this->session->userdata("tmp_servicio_desde");
        $hasta = $this->session->userdata("tmp_servicio_hasta");        
        $estado = $this->session->userdata("tmp_servicio_estado");        
        $tecnico = $this->session->userdata("tmp_servicio_tecnico");        

        $currencyFormat = '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';
        $percentFormat = '_( #,##0_);_( (#,##0);_( "-"??_);_(@_)';
        $textFormat='@';//'General','0.00','@'

        $listaservicio = $this->Serviciotecnico_model->lst_serviciorealizado($desde, $hasta, $estado, $tecnico);
        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ListadoServicios');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Servicios Realizados por Mecánicos');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        

        $this->excel->getActiveSheet()->setCellValue('A3', 'Sucursal');
        $this->excel->getActiveSheet()->setCellValue('B3', '#Orden');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Técnico');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Descripción');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Servicio');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Fecha Realizado');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Trabajo Realizado');

        $this->excel->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true);

        $nombres = $this->Serviciotecnico_model->carga_nombredetalle();
        $col = 7;
        $row = 3;
        foreach ($nombres as $nombre) {
          $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nombre->nombre_configdetalle);  
          $this->excel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
          $col++;       
        }  

        $fila = 4;          
        $filaini = $fila;          

        foreach ($listaservicio as $ser) {

            $fec = str_replace('-', '/', $ser->fecha_emision); $fec = date("d/m/Y", strtotime($fec)); 
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, addslashes($ser->nom_sucursal));
            
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ser->numero_orden);
                        

            $detalles = $this->Serviciotecnico_model->lst_detalleservicio($ser->id_servicio);
            foreach ($detalles as $det) {
              $this->excel->getActiveSheet()->setCellValue('C'.$fila, addslashes($det->nombre_empleado));
              $this->excel->getActiveSheet()->setCellValue('D'.$fila, addslashes($det->descripcion));
              

              $this->excel->getActiveSheet()->getStyle('E' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
              $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($det->montoservicio * 1.12,2));
              
              $fec = '';
              if ($det->id_estado >= 3){
                $fec = str_replace('-', '/', $det->fecha_realizado); $fec = date("d/m/Y", strtotime($fec)); 
              }  
              $this->excel->getActiveSheet()->setCellValue('F'.$fila, $fec);
              $this->excel->getActiveSheet()->setCellValue('G'.$fila, addslashes($det->trabajo_realizado));
              $fec = '';
              if ($det->id_estado >= 4){
                $fec = str_replace('-', '/', $det->fecha_entregado); $fec = date("d/m/Y", strtotime($fec)); 
              }  
              

              $detallesconfig = $this->Serviciotecnico_model->lst_subdetalle_servicio($det->id_detalle);
              $col = 7;
              foreach ($detallesconfig as $subdet) {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, utf8_decode($subdet->valor));
                $col++;
              }  

              $fila++;          
            }

        }    
        $fila++;      
        
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, "TOTAL");
        $this->excel->getActiveSheet()->getStyle('E' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('E' . $fila, '=SUM(E'.($filaini).':E'.($fila-2).')');
        
        $fila++; 
        
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, "DESCTO.TALLER");
        $this->excel->getActiveSheet()->getStyle('E' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('E' . $fila, '=SUM(E'.($filaini).':E'.($fila-3).')*30/100');

        $fila++; 
        
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, "LIQ. A RECIBIR");
        $this->excel->getActiveSheet()->getStyle('D'.$fila, "LIQ. A RECIBIR")->getFont()->setBold(true)->setSize(12);
        $this->excel->getActiveSheet()->getStyle('E' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->getStyle('E' . $fila)->getFont()->setBold(true)->setSize(12);
        $this->excel->getActiveSheet()->setCellValue('E' . $fila, '=SUM(E'.($filaini).':E'.($fila-4).')*70/100');

        $this->excel->getActiveSheet()->freezePane('A4');
        

        for ($col = 7; $col <= PHPExcel_Cell::columnIndexFromString($this->excel->getActiveSheet()->getHighestDataColumn()); $col++) {
            $this->excel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
        }        
      
        $filename='listadoservicio.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');        
    }  



    public function print_pdf_servicio_tmp(){

        $idusu = $this->session->userdata("sess_id");

        $servicio = $this->Serviciotecnico_model->get_servicio_tmp($idusu);
        if ($servicio == NULL) return;
        $sucursal = $this->Sucursal_model->sel_suc_id($servicio->id_sucursal);

        $cfg = $this->Empresa_model->sel_emp_id($sucursal->id_empresa);

        $pdf = new FPDF();
        $pdf->AliasNbPages();

        $pdf->AddPage();

        $pdf->SetXY(10,1);
        $pdf->SetFont('Arial','B',12);
/*
        $imgpro = $sucursal->logo_sucursal;
        if ($imgpro != null){
          $pic = 'data://text/plain;base64,' .$imgpro;
          $pdf->Cell(20,20, $pdf->Image($pic, $pdf->GetX(), $pdf->GetY(), 40, 20, 'png'),0); 
        }  
*/
        $file_name = "servtectmp.jpg";
        if ($sucursal->logo_sucursal){    
          $pic = base64_decode($sucursal->logo_sucursal);
          imagejpeg(imagecreatefromstring ( $pic ), $file_name);

          $pdf->Image($file_name,10,1,40,20);
        }

        $pdf->SetXY(80,1);
        $pdf->Cell(30,10,utf8_decode($sucursal->nom_sucursal),0,0,'C');

        $coord_y = 7;
        $pdf->SetXY(80,$coord_y);
        $pdf->Cell(30,10,utf8_decode($sucursal->dir_sucursal),0,0,'C');

        $coord_y += 6;
        $pdf->SetXY(80,$coord_y);
        $pdf->Cell(30,10,utf8_decode($sucursal->telf_sucursal),0,0,'C');
 
        $pdf->SetFont('Arial','B',11);

        $coord_y = 20;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'SERVICIO TECNICO');

/*        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->MultiCell(0,7,$sucursal->dir_sucursal,0,'L',false);*/
        /*$pdf->Cell(20,10, $sucursal->dir_sucursal);*/

        $coord_y = $pdf->GetY();
        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'ORDEN: '.$servicio->numero_orden);

        $pdf->SetXY(150,$coord_y);
        $fecha = str_replace('-', '/', $servicio->fecha_emision); 
        $fecha = date("d/m/Y", strtotime($fecha));
        $pdf->Cell(20,10, "FECHA: " . $fecha);

        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'CLIENTE: '. $servicio->nom_cliente);

        $pdf->SetXY(150,$coord_y);
        $pdf->Cell(20,10,'CI/RUC: ' .$servicio->ident_cliente);

        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'DIRECCION: '. $servicio->direccion_cliente);

        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'TELEFONO: ' .$servicio->telefonos_cliente);

        $coord_y = $pdf->GetY();
        $coord_y += 6;
        $pdf->SetXY(10,$coord_y);
        $pdf->MultiCell(0,7,'DESCRIPCION: ' .$servicio->descripcion,0,'L',false);

        $pdf->SetFont('Arial','B',11);
        $coord_y = $pdf->GetY();
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'DETALLES: ');

        $coord_y += 10;
        $pdf->SetXY(10,$coord_y);

        $pdf->SetFontSize(8);
        $pdf->SetFillColor(128,128,128);//gris
        $registro = $this->Serviciotecnico_model->lst_detalle_serviciotmp($idusu);
        foreach ($registro as $det) {
          $pdf->SetXY(10,$coord_y);
          $pdf->SetFillColor(128,128,128);
          $pdf->Cell(80,7,'ENCARGADO',1,0,'L',true);
          $pdf->SetFillColor(128,128,128);
          $pdf->Cell(40,7,'ESTADO',1,0,'L',true);
          $pdf->SetFillColor(128,128,128);
          $pdf->Cell(35,7,'REALIZADO',1,0,'L',true);
          $pdf->SetFillColor(128,128,128);
          $pdf->Cell(35,7,'ENTREGADO',1,0,'L',true);
          $pdf->Ln();

          $pdf->Cell(80,7,$det->nombre_empleado,1);
          $pdf->Cell(40,7,$det->nombre_estado,1);
          if ($det->id_estado >= 3){
            $fecha = str_replace('-', '/', $det->fecha_realizado); 
            $fecha = date("d/m/Y", strtotime($fecha));
          }
          else { $fecha = ''; }
          $pdf->Cell(35,7,$fecha,1);
          if ($det->id_estado >= 4){
            $fecha = str_replace('-', '/', $det->fecha_entregado); 
            $fecha = date("d/m/Y", strtotime($fecha));
          }  
          else { $fecha = ''; }
          $pdf->Cell(35,7,$fecha,1);
          $pdf->Ln();

          $pdf->MultiCell(0,7,'Observaciones: '.$det->descripcion,1);
          $pdf->MultiCell(0,7,'Trabajo Realizado: '.$det->trabajo_realizado,1);

          $subdetalles = $this->Serviciotecnico_model->lst_subdetalle_servicio_tmp($det->id_detalle);
          foreach ($subdetalles as $sub) {
            $pdf->SetX(10);
            $pdf->MultiCell(0,7,$sub->nombre_configdetalle.': '.$sub->valor,1);
          }  
          
          $coord_y = $pdf->GetY();
          $coord_y += 5;
        }

        $pdf->SetFontSize(11);
        $coord_y = $pdf->GetY();
        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'PRODUCTOS UTILIZADOS:');
        $coord_y += 10;
        $pdf->SetXY(10,$coord_y);
        
        $productos = $this->Serviciotecnico_model->lst_producto_serviciotmp($idusu);
        if (count($productos) > 0){
            $pdf->SetXY(10,$coord_y);
            $pdf->SetFillColor(128,128,128);
            $pdf->Cell(115,7,'PRODUCTO',1,0,'L',true);
            $pdf->SetFillColor(128,128,128);
            $pdf->Cell(25,7,'CANTIDAD',1,0,'L',true);
            $pdf->SetFillColor(128,128,128);
            $pdf->Cell(25,7,'PRECIO',1,0,'L',true);
            $pdf->SetFillColor(128,128,128);
            $pdf->Cell(25,7,'SUBTOTAL',1,0,'L',true);
            $pdf->Ln();
            foreach ($productos as $row) {
              /*$coord_y += 10;*/
              $pdf->SetX(10);
              $pdf->Cell(115,7,addslashes($row->pro_nombre),1);
              $pdf->Cell(25,7,$row->cantidad,1);
              $pdf->Cell(25,7,$row->precio,1);
              $pdf->Cell(25,7,round($row->precio * $row->cantidad,2),1);
              $pdf->Ln();
            }  
        }  

        $pdf->Output('Servicio Tecnico','I');
    }

    public function print_pdf_servicio_cliente(){

        $id = $this->session->userdata("tmp_servicio_id");

        $servicio = $this->Serviciotecnico_model->get_servicio_id($id);
        if ($servicio == NULL) return;
        $sucursal = $this->Sucursal_model->sel_suc_id($servicio->id_sucursal);

        $cfg = $this->Empresa_model->sel_emp_id($sucursal->id_empresa);

        $pdf = new FPDF();
        $pdf->AliasNbPages();

        $pdf->AddPage();

        $pdf->SetXY(10,1);
        $pdf->SetFont('Arial','B',12);

/*
        $imgpro = $sucursal->logo_sucursal;
        if ($imgpro != null){
          $pic = 'data://text/plain;base64,' .$imgpro;
          $pdf->Cell(20,20, $pdf->Image($pic, $pdf->GetX(), $pdf->GetY(), 40, 20, 'png'),0); 
        }  
*/

        $file_name = "servtectmp.jpg";
        if ($sucursal->logo_sucursal){    
          $pic = base64_decode($sucursal->logo_sucursal);
          imagejpeg(imagecreatefromstring ( $pic ), $file_name);

          $pdf->Image($file_name,10,1,40,20);
        }

        $coord_y = $pdf->GetY();
        $coord_y = $pdf->GetY();
        $pdf->SetXY(70,1);
        //$pdf->Cell(30,10,utf8_decode($sucursal->nom_sucursal),0,0,'C');
        $pdf->MultiCell(0,6,utf8_decode($sucursal->nom_sucursal),0,'L',false);

        $coord_y = 7;
        $pdf->SetXY(70,$coord_y);
        $pdf->MultiCell(0,6,utf8_decode($sucursal->dir_sucursal),0,'L',false);
        //$pdf->Cell(30,10,utf8_decode($sucursal->dir_sucursal),0,0,'C');

        $coord_y = $pdf->GetY();
        //$coord_y += 6;
        $pdf->SetXY(70,$coord_y);
        $pdf->MultiCell(0,6,utf8_decode($sucursal->telf_sucursal),0,'L',false);
        //$pdf->Cell(30,10,utf8_decode($sucursal->telf_sucursal),0,0,'C');
 
        $pdf->SetFont('Arial','B',11);

        $coord_y = 20;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'SERVICIO TECNICO');
/*
        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->MultiCell(0,7,$sucursal->dir_sucursal,0,'L',false);
        */
        /*$pdf->Cell(20,10, $sucursal->dir_sucursal);*/

        $coord_y = $pdf->GetY();
        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'ORDEN: '.$servicio->numero_orden);

        $pdf->SetXY(150,$coord_y);
        $fecha = str_replace('-', '/', $servicio->fecha_emision); 
        $fecha = date("d/m/Y", strtotime($fecha));
        $pdf->Cell(20,10, "FECHA: " . $fecha);

        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'CLIENTE: '. utf8_decode($servicio->nom_cliente));

        $pdf->SetXY(150,$coord_y);
        $pdf->Cell(20,10,'CI/RUC: ' .$servicio->ident_cliente);

        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'DIRECCION: '. utf8_decode($servicio->direccion_cliente));

        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'TELEFONO: ' .$servicio->telefonos_cliente);

        $coord_y = $pdf->GetY();
        $coord_y += 6;
        $pdf->SetXY(10,$coord_y);
        $pdf->MultiCell(0,7,'DESCRIPCION: ' .utf8_decode($servicio->descripcion),0,'L',false);

        $pdf->SetFont('Arial','B',11);
        $coord_y = $pdf->GetY();
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'DETALLES: ');

        $coord_y += 10;
        $pdf->SetXY(10,$coord_y);

        $pdf->SetFontSize(8);
        $pdf->SetFillColor(128,128,128);//gris
        $registro = $this->Serviciotecnico_model->lst_detalleresumen_servicio($id);
        foreach ($registro as $det) {
          $pdf->SetXY(10,$coord_y);
          $pdf->SetFillColor(128,128,128);
          $pdf->Cell(80,7,'ENCARGADO',1,0,'L',true);
          $pdf->SetFillColor(128,128,128);
          $pdf->Cell(40,7,'ESTADO',1,0,'L',true);
          $pdf->SetFillColor(128,128,128);
          $pdf->Cell(35,7,'REALIZADO',1,0,'L',true);
          $pdf->SetFillColor(128,128,128);
          $pdf->Cell(35,7,'ENTREGADO',1,0,'L',true);
          $pdf->Ln();

          $pdf->Cell(80,7,$det->nombre_empleado,1);
          $pdf->Cell(40,7,$det->nombre_estado,1);
          if ($det->id_estado >= 3){
            $fecha = str_replace('-', '/', $det->fecha_realizado); 
            $fecha = date("d/m/Y", strtotime($fecha));
          }
          else { $fecha = ''; }
          $pdf->Cell(35,7,$fecha,1);
          if ($det->id_estado >= 4){
            $fecha = str_replace('-', '/', $det->fecha_entregado); 
            $fecha = date("d/m/Y", strtotime($fecha));
          }  
          else { $fecha = ''; }
          $pdf->Cell(35,7,$fecha,1);
          $pdf->Ln();

          $pdf->MultiCell(0,7,'Observaciones: '.utf8_decode($det->descripcion),1);
          $pdf->MultiCell(0,7,'Trabajo Realizado: '.utf8_decode($det->trabajo_realizado),1);


          $subdetalles = $this->Serviciotecnico_model->lst_subdetalle_servicio($det->id_detalle);
          foreach ($subdetalles as $sub) {
            $pdf->SetX(10);
            $pdf->MultiCell(0,7,utf8_decode($sub->nombre_configdetalle).': '.utf8_decode($sub->valor),1);
          }  
          $coord_y = $pdf->GetY();
          $coord_y += 5;
        }

        $pdf->SetFontSize(11);
        $coord_y = $pdf->GetY();
        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'PRODUCTOS UTILIZADOS:');
        $coord_y += 10;
        $pdf->SetXY(10,$coord_y);
        
        $productos = $this->Serviciotecnico_model->lst_producto_servicio($id);
        if (count($productos) > 0){
            $pdf->SetXY(10,$coord_y);
            $pdf->SetFillColor(128,128,128);
            $pdf->Cell(115,7,'PRODUCTO',1,0,'L',true);
            $pdf->SetFillColor(128,128,128);
            $pdf->Cell(25,7,'CANTIDAD',1,0,'L',true);
            $pdf->SetFillColor(128,128,128);
            $pdf->Cell(25,7,'V. UNITARIO',1,0,'L',true);
            $pdf->SetFillColor(128,128,128);
            $pdf->Cell(25,7,'V. TOTAL',1,0,'L',true);
            $pdf->Ln();
            $totalpro = 0;
            foreach ($productos as $row) {
              /*$coord_y += 10;*/
              $pdf->SetX(10);
              $pdf->Cell(115,7,addslashes($row->pro_nombre),1);
              $pdf->Cell(25,7,$row->cantidad,1,0,'R',false);
              $pdf->Cell(25,7,$row->precio,1,0,'R',false);
              $subtotal = $row->precio * $row->cantidad;
              $totalpro += $subtotal;
              $pdf->Cell(25,7,round($subtotal,2),1,0,'R',false);
              $pdf->Ln();
            }  
            $pdf->SetX(10);
            $pdf->Cell(115,7,'',0);
            $pdf->Cell(25,7,'',0);
            $pdf->Cell(25,7,'SUBTOTAL',1);
            $pdf->Cell(25,7,round($totalpro,2),1,0,'R',false);
            $pdf->Ln();

            $pdf->SetX(10);
            $pdf->Cell(115,7,'',0);
            $pdf->Cell(25,7,'',0);
            $pdf->Cell(25,7,'IVA 12%',1);
            $pdf->Cell(25,7,round($totalpro*0.12,2),1,0,'R',false);
            $pdf->Ln();

             $pdf->SetX(10);
            $pdf->Cell(115,7,'',0);
            $pdf->Cell(25,7,'',0);
            $pdf->Cell(25,7,'TOTAL',1);
            $pdf->Cell(25,7,round($totalpro*1.12,2),1,0,'R',false);
            $pdf->Ln();
        }  

        $pdf->Output('Servicio Tecnico','I');
    }

    public function genera_pdf_servicio_cliente(){

        $id = $this->input->post('id'); 

        $servicio = $this->Serviciotecnico_model->get_servicio_id($id);
        if ($servicio == NULL) return;
        $sucursal = $this->Sucursal_model->sel_suc_id($servicio->id_sucursal);

        $cfg = $this->Empresa_model->sel_emp_id($sucursal->id_empresa);

        $pdf = new FPDF();
        $pdf->AliasNbPages();

        $pdf->AddPage();

        $pdf->SetXY(10,1);
        $pdf->SetFont('Arial','B',12);
/*
        $imgpro = $sucursal->logo_sucursal;
        if ($imgpro != null){
          $pic = 'data://text/plain;base64,' .$imgpro;
          $pdf->Cell(20,20, $pdf->Image($pic, $pdf->GetX(), $pdf->GetY(), 40, 20, 'png'),0); 
        }  
*/
        $file_name = "servtectmp.jpg";
        if ($sucursal->logo_sucursal){    
          $pic = base64_decode($sucursal->logo_sucursal);
          imagejpeg(imagecreatefromstring ( $pic ), $file_name);

          $pdf->Image($file_name,10,1,40,20);
        }

  //      $pdf->SetXY(80,1);
  //      $pdf->Cell(30,10,utf8_decode($sucursal->nom_sucursal),0,0,'C');
  $pdf->SetXY(80,1);
  $pdf->Cell(30,10,utf8_decode($sucursal->nom_sucursal),0,0,'C');

  $coord_y = 7;
  $pdf->SetXY(80,$coord_y);
  $pdf->Cell(30,10,utf8_decode($sucursal->dir_sucursal),0,0,'C');

  $coord_y += 6;
  $pdf->SetXY(80,$coord_y);
  $pdf->Cell(30,10,utf8_decode($sucursal->telf_sucursal),0,0,'C');

        $pdf->SetFont('Arial','B',11);

        $coord_y = 20;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'SERVICIO TECNICO');

/*        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->MultiCell(0,7,utf8_decode($sucursal->dir_sucursal),0,'L',false);*/
        /*$pdf->Cell(20,10, $sucursal->dir_sucursal);*/

        $coord_y = $pdf->GetY();
        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'ORDEN: '.$servicio->numero_orden);

        $pdf->SetXY(150,$coord_y);
        $fecha = str_replace('-', '/', $servicio->fecha_emision); 
        $fecha = date("d/m/Y", strtotime($fecha));
        $pdf->Cell(20,10, "FECHA: " . $fecha);

        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'CLIENTE: '. utf8_decode($servicio->nom_cliente));

        $pdf->SetXY(150,$coord_y);
        $pdf->Cell(20,10,'CI/RUC: ' .$servicio->ident_cliente);

        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'DIRECCION: '. utf8_decode($servicio->direccion_cliente));

        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'TELEFONO: ' .$servicio->telefonos_cliente);

        $coord_y = $pdf->GetY();
        $coord_y += 6;
        $pdf->SetXY(10,$coord_y);
        $pdf->MultiCell(0,7,'DESCRIPCION: ' .utf8_decode($servicio->descripcion),0,'L',false);

        $pdf->SetFont('Arial','B',11);
        $coord_y = $pdf->GetY();
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'DETALLES: ');

        $coord_y += 10;
        $pdf->SetXY(10,$coord_y);

        $pdf->SetFontSize(8);
        $pdf->SetFillColor(128,128,128);//gris
        $registro = $this->Serviciotecnico_model->lst_detalleresumen_servicio($id);
        foreach ($registro as $det) {
          $pdf->SetXY(10,$coord_y);
          $pdf->SetFillColor(128,128,128);
          $pdf->Cell(80,7,'ENCARGADO',1,0,'L',true);
          $pdf->SetFillColor(128,128,128);
          $pdf->Cell(40,7,'ESTADO',1,0,'L',true);
          $pdf->SetFillColor(128,128,128);
          $pdf->Cell(35,7,'REALIZADO',1,0,'L',true);
          $pdf->SetFillColor(128,128,128);
          $pdf->Cell(35,7,'ENTREGADO',1,0,'L',true);
          $pdf->Ln();

          $pdf->Cell(80,7,$det->nombre_empleado,1);
          $pdf->Cell(40,7,$det->nombre_estado,1);
          if ($det->id_estado >= 3){
            $fecha = str_replace('-', '/', $det->fecha_realizado); 
            $fecha = date("d/m/Y", strtotime($fecha));
          }
          else { $fecha = ''; }
          $pdf->Cell(35,7,$fecha,1);
          if ($det->id_estado >= 4){
            $fecha = str_replace('-', '/', $det->fecha_entregado); 
            $fecha = date("d/m/Y", strtotime($fecha));
          }  
          else { $fecha = ''; }
          $pdf->Cell(35,7,$fecha,1);
          $pdf->Ln();

          $pdf->MultiCell(0,7,'Observaciones: '.utf8_decode($det->descripcion),1);
          $pdf->MultiCell(0,7,'Trabajo Realizado: '.utf8_decode($det->trabajo_realizado),1);

          $subdetalles = $this->Serviciotecnico_model->lst_subdetalle_servicio($det->id_detalle);
          foreach ($subdetalles as $sub) {
            $pdf->SetX(10);
            $pdf->MultiCell(0,7,utf8_decode($sub->nombre_configdetalle).': '.utf8_decode($sub->valor),1);
          }  
          $coord_y = $pdf->GetY();
          $coord_y += 5;
        }

        $pdf->SetFontSize(11);
        $coord_y = $pdf->GetY();
        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'PRODUCTOS UTILIZADOS:');
        $coord_y += 10;
        $pdf->SetXY(10,$coord_y);
        
       $productos = $this->Serviciotecnico_model->lst_producto_servicio($id);
        if (count($productos) > 0){
            $pdf->SetXY(10,$coord_y);
            $pdf->SetFillColor(128,128,128);
            $pdf->Cell(115,7,'PRODUCTO',1,0,'L',true);
            $pdf->SetFillColor(128,128,128);
            $pdf->Cell(25,7,'CANTIDAD',1,0,'L',true);
            $pdf->SetFillColor(128,128,128);
            $pdf->Cell(25,7,'V. UNITARIO',1,0,'L',true);
            $pdf->SetFillColor(128,128,128);
            $pdf->Cell(25,7,'V. TOTAL',1,0,'L',true);
            $pdf->Ln();
            $totalpro = 0;
            foreach ($productos as $row) {
              /*$coord_y += 10;*/
              $pdf->SetX(10);
              $pdf->Cell(115,7,addslashes($row->pro_nombre),1);
              $pdf->Cell(25,7,$row->cantidad,1,0,'R',false);
              $pdf->Cell(25,7,$row->precio,1,0,'R',false);
              $subtotal = $row->precio * $row->cantidad;
              $totalpro += $subtotal;
              $pdf->Cell(25,7,round($subtotal,2),1,0,'R',false);
              $pdf->Ln();
            }  
            $pdf->SetX(10);
            $pdf->Cell(115,7,'',0);
            $pdf->Cell(25,7,'',0);
            $pdf->Cell(25,7,'SUBTOTAL',1);
            $pdf->Cell(25,7,round($totalpro,2),1,0,'R',false);
            $pdf->Ln();

            $pdf->SetX(10);
            $pdf->Cell(115,7,'',0);
            $pdf->Cell(25,7,'',0);
            $pdf->Cell(25,7,'IVA 12%',1);
            $pdf->Cell(25,7,round($totalpro*0.12,2),1,0,'R',false);
            $pdf->Ln();

             $pdf->SetX(10);
            $pdf->Cell(115,7,'',0);
            $pdf->Cell(25,7,'',0);
            $pdf->Cell(25,7,'TOTAL',1);
            $pdf->Cell(25,7,round($totalpro*1.12,2),1,0,'R',false);
            $pdf->Ln();
        }  
        $archivo = FCPATH.'doc/servicio'.$servicio->numero_orden.'.pdf';
        if (file_exists($archivo)) unlink($archivo);
        $pdf->Output($archivo, 'F');
        
        $arr['ruta'] = $archivo;
        $arr['correo'] = $servicio->correo_cliente;
        $arr['cliente'] = $servicio->nom_cliente;
        $arr['sucursal'] = $sucursal->nom_sucursal;
        $arr['idsucursal'] = $sucursal->id_sucursal;
        $arr['orden'] = $servicio->numero_orden;

        print json_encode($arr);
    }

    public function correoenviar() {
      $archivo = $this->input->post('ruta'); 
      $cliente = $this->input->post('cliente'); 
      $email = $this->input->post('correo'); 
      $sucursal = $this->input->post('sucursal'); 
      $idsucursal = $this->input->post('idsucursal'); 
      $orden = $this->input->post('orden'); 

      $objsuc = $this->Sucursal_model->sel_suc_id($idsucursal);
      $empresa = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);

      $correo = $this->Correo_model->env_correo();

      $config = array(
        'protocol' => 'smtp',
        'smtp_host' => $correo->smtp,
        'smtp_user' => $correo->usuario,
        'smtp_pass' => $correo->clave, 
        'smtp_port' => $correo->puerto,
        'smtp_crypto' => 'ssl',
        'smtp_timeout' => '30',
        'mailtype' => 'html',
        'wordwrap' => TRUE,
        'charset' => 'utf-8',
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )        
      );

      $email = str_replace(';', ',', $email);
      $email = str_replace(' ', '', $email);

      $this->load->library('email', $config);
      $this->email->set_newline("\r\n");
      $this->email->from($correo->usuario);
      $this->email->subject('Servicio Técnico');
      $this->email->attach($archivo);
      $imgwhatsapp = FCPATH.'public/img/whatsapp.png';
      $this->email->attach($imgwhatsapp);
      $idimgwhatsapp = $this->email->attachment_cid($imgwhatsapp);
      $str = 'Estimado(a) cliente ' . $cliente. '.' . "\r\n" . "\r\n";
      $str.= 'Le adjuntamos el informe de Servicio Técnico con número de orden ' . $orden;
      $strhtml = $this->get_html_msg($empresa, $cliente, $orden, $idimgwhatsapp);
      $this->email->message($strhtml);
      $this->email->to($email);
      if($this->email->send(FALSE)){
        $res = 1;
        // echo "enviado<br/>";
        // echo $this->email->print_debugger(array('headers'));
      }else {
        $res = 0;
        // echo "fallo <br/>";
        // echo "error: ".$this->email->print_debugger(array('headers'));
      }
      print json_encode($res);
    }     

    public function get_html_msg($empresa, $cliente, $orden, $idimgwhatsapp) {
        $msg = "<!DOCTYPE html>
                <html lang='es'>
                <head>
                    <meta charset='UTF-8'>
                </head>
                <body>
                <div style='margin: 0 auto; background-color: white; width:800px; overflow-y:auto;'>
                    <h4>ORDEN DE SERVICIO TECNICO:  </h4>
                    <hr>
                    <p>
                        <strong>Estimado(a) cliente $cliente </strong>
                        <br/>
                        <strong>Le adjuntamos el informe de Servicio Técnico con número de orden : $orden </strong> 
                        <br/>
                    </p>
                    <hr>
                    <table width='1000' height='200' border='0' align='center'  bgcolor='white' style='margin-top:-20px;'>
                    <tr>
                        <td align='left' style='border: 0px transparent; font-size:10px!important; font-family:Helvetica, Arial; text-align: left;'>
                              EMPRESA: $empresa->nom_emp;
                              <br/>
                              RUC: $empresa->ruc_emp;
                              <br/>
                              DIRECCION: $empresa->dir_emp;
                              <br/>
                              <a href='https://api.whatsapp.com/send?phone=$empresa->tlf_emp'>
                                <img border='0' alt='Contacto' src='cid:$idimgwhatsapp' width='30' height='30'/>                                  
                              </a>
                              TELEFONO: $empresa->tlf_emp
                        </td>
                     </tr>
                    </table>
                </div>
            </body>
         </html>";
        return $msg;
    }  

    public function get_totalproducto_detalle(){
      $iddetalle = $this->session->userdata("tmp_servicio_iddetalle");
      $total = $this->Serviciotecnico_model->valortotal_produtil_tmp($iddetalle);
      $arr['resu'] = $total;
      print json_encode($arr);
    }

    public function print_pdf_tmp_servicio_etiqueta(){
        $idusu = $this->session->userdata("sess_id");

        $servicio = $this->Serviciotecnico_model->get_servicio_tmp($idusu);
        if ($servicio == NULL) return;
        $sucursal = $this->Sucursal_model->sel_suc_id($servicio->id_sucursal);

        $cfg = $this->Empresa_model->sel_emp_id($sucursal->id_empresa);

        $pdf = new FPDF();
        $pdf->AliasNbPages();

        //$pdf->AddPage();
        $pdf->AddPage('P', array(90,90));        #Orientación y tamaño 


        $pdf->SetXY(10,1);
        $pdf->SetFont('Arial','B',14);

        $file_name = "servtectmp.jpg";
        if ($sucursal->logo_sucursal){    
          $pic = base64_decode($sucursal->logo_sucursal);
          imagejpeg(imagecreatefromstring ( $pic ), $file_name);

          $pdf->Image($file_name,25,1,40,18);
        }

        $coord_y = $pdf->GetY();
        $coord_y += 20;

        $pdf->SetXY(1,$coord_y);
        //$pdf->Cell(30,10,utf8_decode($sucursal->nom_sucursal),0,0,'C');
        $pdf->MultiCell(0,6,'OT: '.utf8_decode($servicio->numero_orden),0,'C',false);

        $pdf->SetFont('Arial','B',10);

        $coord_y += 6;
        $pdf->SetXY(1,$coord_y);
        $pdf->MultiCell(0,6,utf8_decode($sucursal->telf_sucursal),0,'C',false);
        //$pdf->Cell(30,10,utf8_decode($sucursal->dir_sucursal),0,0,'C');

        $pdf->SetFont('Arial','B',8);
        //$coord_y = $pdf->GetY();
        $coord_y += 4;
        $pdf->SetXY(1,$coord_y);
        $pdf->Cell(30,10,utf8_decode($cfg->web_emp),0,0,'L');

        $pdf->Cell(30,10,utf8_decode($cfg->ema_emp),0,0,'C');

        $file_name = "servtectmp.jpg";
        if ($sucursal->logo_piepagina){    
          $pic = base64_decode($sucursal->logo_piepagina);
          imagejpeg(imagecreatefromstring ( $pic ), $file_name);

          $pdf->Image($file_name,70,$coord_y,10,8);
        }

 

        $pdf->Output('Servicio Tecnico','I');
    }

    public function print_pdf_servicio_etiqueta(){

        $id = $this->session->userdata("tmp_servicio_id");

        $servicio = $this->Serviciotecnico_model->get_servicio_id($id);
        if ($servicio == NULL) return;
        $sucursal = $this->Sucursal_model->sel_suc_id($servicio->id_sucursal);

        $cfg = $this->Empresa_model->sel_emp_id($sucursal->id_empresa);

        $pdf = new FPDF();
        $pdf->AliasNbPages();

        //$pdf->AddPage();
        $pdf->AddPage('P', array(90,90));        #Orientación y tamaño 


        $pdf->SetXY(10,1);
        $pdf->SetFont('Arial','B',14);

        $file_name = "servtectmp.jpg";
        if ($sucursal->logo_sucursal){    
          $pic = base64_decode($sucursal->logo_sucursal);
          imagejpeg(imagecreatefromstring ( $pic ), $file_name);

          $pdf->Image($file_name,25,1,40,18);
        }

        $coord_y = $pdf->GetY();
        $coord_y += 20;

        $pdf->SetXY(1,$coord_y);
        //$pdf->Cell(30,10,utf8_decode($sucursal->nom_sucursal),0,0,'C');
        $pdf->MultiCell(0,6,'OT: '.utf8_decode($servicio->numero_orden),0,'C',false);

        $pdf->SetFont('Arial','B',10);

        $coord_y += 6;
        $pdf->SetXY(1,$coord_y);
        $pdf->MultiCell(0,6,utf8_decode($sucursal->telf_sucursal),0,'C',false);
        //$pdf->Cell(30,10,utf8_decode($sucursal->dir_sucursal),0,0,'C');

        $pdf->SetFont('Arial','B',8);
        //$coord_y = $pdf->GetY();
        $coord_y += 4;
        $pdf->SetXY(1,$coord_y);
        $pdf->Cell(30,10,utf8_decode($cfg->web_emp),0,0,'L');

        $pdf->Cell(30,10,utf8_decode($cfg->ema_emp),0,0,'C');

        $file_name = "servtectmp.jpg";
        if ($sucursal->logo_piepagina){    
          $pic = base64_decode($sucursal->logo_piepagina);
          imagejpeg(imagecreatefromstring ( $pic ), $file_name);

          $pdf->Image($file_name,70,$coord_y,10,8);
        }

 

        $pdf->Output('Servicio Tecnico','I');
    }

}

?>