<?php

/*------------------------------------------------
  ARCHIVO: Contab_comprobantes.php
  DESCRIPCION: Contiene los métodos relacionados con comprobantes.
  
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class Contab_comprobante extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("contabilidad/Contab_comprobante_model");
        $this->load->Model("contabilidad/Contab_plancuentas_model");        
        $this->load->Model("Sucursal_model");
        $this->load->Model("Empresa_model");
        $this->load->Model("Facturar_model");
        $this->load->Model("Credito_model");
        $this->load->Model("Compra_model");
        $this->load->Model("Gastos_model");
        $this->load->Model("Inventario_model");
        $this->load->Model("Retencion_model");
        $this->load->Model("Compraabono_model");
        $this->load->Model("Almacen_model");       

        $this->Contab_comprobante_model->actualiza_tipocmp_sucursal();
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        date_default_timezone_set("America/Guayaquil");

        $desde = $this->session->userdata("tmp_cmp_desde");
        $hasta = $this->session->userdata("tmp_cmp_hasta");
        $empresa = $this->session->userdata("tmp_cmp_empresa");
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
  
        if (($desde == NULL) || ($hasta == NULL)){
          $desde = date("Y-m-d"); 
          $hasta = date("Y-m-d"); 
          $empresa = 0;
          $sucursal = 1;
          $this->session->set_userdata("tmp_cmp_desde", NULL);
          if ($desde != NULL) { $this->session->set_userdata("tmp_cmp_desde", $desde); } 
          else { $this->session->set_userdata("tmp_cmp_desde", NULL); }
          $this->session->set_userdata("tmp_cmp_hasta", NULL);
          if ($hasta != NULL) { $this->session->set_userdata("tmp_cmp_hasta", $hasta); } 
          else { $this->session->set_userdata("tmp_cmp_hasta", NULL); }
          $this->session->set_userdata("tmp_cmp_empresa", NULL);
          if ($empresa != NULL) { $this->session->set_userdata("tmp_cmp_empresa", $empresa); } 
          else { $this->session->set_userdata("tmp_cmp_empresa", 0); }
          $this->session->set_userdata("tmp_cmp_sucursal", NULL);
          if ($sucursal != NULL) { $this->session->set_userdata("tmp_cmp_sucursal", $sucursal); } 
          else { $this->session->set_userdata("tmp_cmp_sucursal", 0); }
        }  
        $data["tmpsucursal"] = $sucursal;
        $data["tmpdesde"] = $desde;
        $data["tmphasta"] = $hasta;

        $sucursales = $this->Sucursal_model->lst_sucursales();
        $data["sucursales"] = $sucursales;

        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_comprobantes";
        $this->load->view("layout", $data);
    }

    /*comprobante*/
    public function tmp_comprobante() {
        $this->session->unset_userdata("tmp_comprobante_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_comprobante_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_comprobante_id", $id);
        } else {
            $this->session->set_userdata("tmp_comprobante_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function tmp_cmp_sucursal() {
        $this->session->unset_userdata("tmp_cmp_sucursal"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_cmp_sucursal", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_cmp_sucursal", $id);
        } else {
            $this->session->set_userdata("tmp_cmp_sucursal", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function tmp_cmp_fecha() {
        $this->session->unset_userdata("tmp_cmp_desde"); 
        $this->session->unset_userdata("tmp_cmp_hasta");
        $this->session->unset_userdata("tmp_cmp_empresa");
        $this->session->unset_userdata("tmp_cmp_sucursal");
        $sucursal = $this->input->post("sucursal");
        $empresa = $this->input->post("empresa");
        $desde = $this->input->post("desde");
        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = $this->input->post("hasta");
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        $this->session->set_userdata("tmp_cmp_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_cmp_desde", $desde);} 
        else { $this->session->set_userdata("tmp_cmp_desde", NULL); }
        $this->session->set_userdata("tmp_cmp_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_cmp_hasta", $hasta);} 
        else { $this->session->set_userdata("tmp_cmp_hasta", NULL);}
        $this->session->set_userdata("tmp_cmp_empresa", NULL);
        if ($empresa != NULL) { $this->session->set_userdata("tmp_cmp_empresa", $empresa);} 
        else { $this->session->set_userdata("tmp_cmp_empresa", NULL);}
        $this->session->set_userdata("tmp_cmp_sucursal", NULL);
        if ($sucursal != NULL) { $this->session->set_userdata("tmp_cmp_sucursal", $sucursal);} 
        else { $this->session->set_userdata("tmp_cmp_sucursal", 1);}
        $arr['resu'] = 1;
        print json_encode($arr);
      } 
  
    public function listadoComprobantes() {
        $desde = $this->session->userdata("tmp_cmp_desde");
        $hasta = $this->session->userdata("tmp_cmp_hasta");
        $empresa = $this->session->userdata("tmp_cmp_empresa");
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        $registro = $this->Contab_comprobante_model->sel_comprobantes(0, $sucursal, $desde, $hasta);
        $tabla = "";
        foreach ($registro as $row) {
            $fecha = str_replace('-', '/', $row->fechaasiento); $fecha = date("d/m/Y", strtotime($fecha));

            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Imprimir\" id=\"'.$row->id.'\" class=\"btn btn-danger btn-xs btn-grad print_cmp\"><i class=\"fa fa-file-pdf-o\"></i></a> ';
            if ($row->idestado == 1){
                $ver .= '<a href=\"#\" title=\"Editar\" id=\"'.$row->id.'\" class=\"btn btn-success btn-xs btn-grad edi_cmp\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Confirmar\" id=\"'.$row->id.'\" class=\"btn btn-info btn-xs btn-grad conf_cmp\"><i class=\"fa fa-lock\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id.'\" class=\"btn btn-danger btn-xs btn-grad del_cmp\"><i class=\"fa fa-trash-o\"></i></a>';
            }    
            elseif ($row->idestado == 2){
                $ver .= '<a href=\"#\" title=\"Anular\" id=\"'.$row->id.'\" class=\"btn btn-danger btn-xs btn-grad null_cmp\"><i class=\"fa fa-ban\"></i></a> ';
            }
            else{

            }
            $ver .= '</div>';
            $tabla.='{  "id":"' .$row->id. '",
                        "fecha":"' .$fecha. '",
                        "numero":"' .$row->numero. '",
                        "referencia":"' .$row->referencia. '",
                        "monto":"' .$row->monto. '",
                        "estado":"' .$row->estado. '",
                        "nom_sucursal":"' .addslashes($row->nom_sucursal). '",
                        "tipocomprobante":"' .$row->tipocomprobante. '",
                        "descripcion":"' .addslashes($row->descripcion). '",
                        "ver":"'.$ver.'"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function add_comprobante(){
        $this->load->helper('string');
        $token = random_string('md5');

        $this->session->unset_userdata("tmp_cmp_token"); 
        $this->session->set_userdata("tmp_cmp_token", $token);

        $sucursales = $this->Sucursal_model->lst_sucursales();
        $data["sucursales"] = $sucursales;
        $tipoasiento = $this->Contab_comprobante_model->sel_tipoasiento();
        $data["tipoasiento"] = $tipoasiento;

        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_comprobantes_add";
        $this->load->view("layout", $data);
    } 

    public function edit_comprobante(){
        $this->load->helper('string');
        $token = random_string('md5');

        $this->session->unset_userdata("tmp_cmp_token"); 
        $this->session->set_userdata("tmp_cmp_token", $token);

        $sucursales = $this->Sucursal_model->lst_sucursales();
        $data["sucursales"] = $sucursales;
        $tipoasiento = $this->Contab_comprobante_model->sel_tipoasiento();
        $data["tipoasiento"] = $tipoasiento;

        $token = $this->session->userdata("tmp_cmp_token");
        $id = $this->session->userdata("tmp_comprobante_id");
        $total = $this->Contab_comprobante_model->cargar_tmpdetalle($id, $token);        
        if ($total != NULL){
            $data["totaldebito"] = $total->debito;
            $data["totalcredito"] = $total->credito;
        }

        $obj = $this->Contab_comprobante_model->sel_comprobante_id($id);        
        $data["obj"] = $obj;

        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_comprobantes_add";
        $this->load->view("layout", $data);
    } 

    public function listadoAsientos() {
        $token = $this->session->userdata("tmp_cmp_token");
        $registro = $this->Contab_comprobante_model->sel_asientostmp($token);
        $tabla = "";
        foreach ($registro as $row) {

            $codigocuenta = '<div id=\"divasiento'.$row->id.'\" class=\"divcuenta\" ><input type=\"text\" class=\"col-md-12 tdcuenta upd_cuenta autocomplete \" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.$row->codigocuenta.'\" data-source=\"'.base_url('contabilidad/contab_comprobante/valcuentacodigo?codigo=').'\"  ></div>';
            $descripcuenta = '<div ><label class=\"col-md-12 desc_cuenta\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" >'.addslashes($row->descripcion).'</label> </div>';
            if ($row->debitocredito == 1){            
                $debito = '<div ><input type=\"text\" class=\"col-md-12 tdvalor text-right upd_debito\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.$row->valor.'\" ></div>';
                $credito = '<div ><input type=\"text\" class=\"col-md-12 tdvalor text-right upd_credito\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"0.00\" ></div>';
            }
            else{
                $credito = '<div ><input type=\"text\" class=\"col-md-12 tdvalor text-right  upd_credito\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.$row->valor.'\" ></div>';
                $debito = '<div ><input type=\"text\" class=\"col-md-12 tdvalor text-right  upd_debito\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"0.00\" ></div>';
            }
            $concepto = '<div ><input type=\"text\" class=\"col-md-12 tdcuenta upd_concepto\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.addslashes($row->concepto).'\" ></div>';

            $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id.'\" class=\"btn btn-danger btn-xs btn-grad del_detalle\"><i class=\"fa fa-trash-o\"></i></a></div>';

            $tabla.='{  "id":"' .$row->id. '",
                        "codigocuenta":"' .$codigocuenta. '",
                        "descripcioncuenta":"' .$descripcuenta. '",
                        "concepto":"' .$concepto. '",
                        "debito":"' .$debito. '",
                        "credito":"' .$credito. '",
                        "ver":"'.$ver.'"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function add_detalle(){
        $token = $this->session->userdata("tmp_cmp_token");
        $id = $this->Contab_comprobante_model->add_detalle($token);
        $arr['resu'] = $id;
        print json_encode($arr); 
    }

    public function guardar(){
        $idusu = $this->session->userdata("sess_id");
        $token = $this->session->userdata("tmp_cmp_token");
        $id = $this->input->post('id'); 
        $sucursal = $this->input->post('sucursal');
        $tipo = $this->input->post('tipo');
        $fec = $this->input->post('fecha');
        $fec = str_replace('/', '-', $fec); 
        $fecha = date("Y-m-d", strtotime($fec));
        $monto = $this->input->post('monto');
        $referencia = $this->input->post('referencia');
        $descripcion = $this->input->post('descripcion');
        if($id != 0){
            $this->Contab_comprobante_model->upd_comprobante($id, $token, $sucursal, $tipo, $referencia, 
                                                             $fecha, $idusu, $monto, $descripcion);
        } else {
            $this->Contab_comprobante_model->add_comprobante($token, $sucursal, $tipo, $referencia, $fecha, 
                                                             $idusu, $monto, $descripcion);
        }
        $arr['mens'] = $id;
        print json_encode($arr); 
    }

    public function del_comprobante(){
        $id = $this->input->post('id'); 
        $resu = $this->Contab_comprobante_model->del_comprobante($id);
        $arr['mens'] = $resu;
        print json_encode($arr); 
    }

    public function validar_comprobante($id){
        $resu = $this->Contab_comprobante_model->validar_comprobante($id);
        $arr['mens'] = $resu;
        print json_encode($arr); 
    }
    
    public function confirmar_comprobante(){
        $id = $this->input->post('id'); 
        $res = $this->Contab_comprobante_model->validar_comprobante($id);
        if ($res == true) {
            $resu = $this->Contab_comprobante_model->confirmar_comprobante($id);
        }
        else{
            $resu = 0;
        }
        $arr['mens'] = $resu;
        print json_encode($arr); 
    }

    public function confirmar_cmp_rango(){
        $desde = $this->session->userdata("tmp_cmp_desde");
        $hasta = $this->session->userdata("tmp_cmp_hasta");
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        $listacmp = $this->Contab_comprobante_model->sel_comprobantes(0, $sucursal, $desde, $hasta);
        $tmpcmp = "";
        $res = false;
        foreach($listacmp as $cmp){    
            $res = $this->Contab_comprobante_model->validar_comprobante($cmp->id);
            if ($res == false) {
                $tmpcmp = "Número: " . $cmp->numero. "  Referencia: " .$cmp->referencia;
                break;
            }
        }    
        if ($res == true) {
            $resu = $this->Contab_comprobante_model->confirmar_cmp_rango($sucursal, $desde, $hasta);
        }
        else{
            $resu = 0;
            $arr['cmp'] = $tmpcmp;
        }
        $arr['mens'] = $resu;
        print json_encode($arr); 
    }

    public function anular_comprobante(){
        $id = $this->input->post('txt_id'); 
        $motivo = $this->input->post('txt_motivo'); 
        $resu = $this->Contab_comprobante_model->anular_comprobante($id, $motivo);
        $arr['mens'] = $resu;
        print json_encode($arr); 
    }
    
    public function valcuentacodigo(){
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        if ($sucursal == '') {$sucursal = 0;}
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc != NULL) {$empresa = $objsuc->id_empresa;} else {$empresa=0;}
        $tmpArray=array();
        $codigo = $this->input->get('codigo');
        $data = $this->Contab_plancuentas_model->lst_cuentacodigo($codigo, $empresa);
        foreach ($data as $row) {
            $tmpArray[] = $row->codigocuenta;
        }
        print json_encode($tmpArray);
    }    

    public function busca_cuenta(){
        $codcuenta = $this->input->post('codcuenta'); 
        $sucursal = $this->input->post('sucursal'); 
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        $resu = $this->Contab_plancuentas_model->sel_cuentacodigo($codcuenta, $objsuc->id_empresa);
        $arr['resu'] = $resu;
        print json_encode($arr); 
    }    

    public function actualiza_asiento_cuenta(){
        $asiento = $this->input->post('asiento'); 
        $idcuenta = $this->input->post('idcuenta'); 
        $codcuenta = $this->input->post('codcuenta'); 
        $this->Contab_comprobante_model->upd_tmpasiento_cuenta($asiento, $idcuenta, $codcuenta);
        $arr['resu'] = 1;
        print json_encode($arr); 
    }    

    public function actualiza_asiento_concepto(){
        $asiento = $this->input->post('asiento'); 
        $concepto = $this->input->post('concepto'); 
        $this->Contab_comprobante_model->upd_tmpasiento_concepto($asiento, $concepto);
        $arr['resu'] = 1;
        print json_encode($arr); 
    }    

    public function actualiza_asiento_valor(){
        $asiento = $this->input->post('asiento'); 
        $valor = $this->input->post('valor'); 
        $esdebito = $this->input->post('esdebito');        
        $this->Contab_comprobante_model->upd_tmpasiento_valor($asiento, $valor, $esdebito);
        $arr['resu'] = 1;
        print json_encode($arr); 
    }    

    public function del_asiento(){
        $id = $this->input->post('id'); 
        $resu = $this->Contab_comprobante_model->del_tmpasiento($id);
        $arr['mens'] = $resu;
        print json_encode($arr); 
    }

   
    public function get_sucursal_empresa(){
        $empresa = $this->input->post('empresa'); 
        $resu = $this->Sucursal_model->lst_sucursal_empresa($empresa);
        print json_encode($resu); 
    }

    public function ins_comprobante_venta(){
        $iddoc = $this->input->post('id'); 
        $sucursal = $this->input->post('sucursal'); 
        $desde = $this->input->post('desde'); 
        $hasta = $this->input->post('hasta'); 

        $idcmp = $this->guardar_comprobante_venta($iddoc, $sucursal, $desde, $hasta);        
        print json_encode($idcmp); 
    }

    public function comprobante_prueba(){
        $resu = $this->Contab_comprobante_model->sel_contabilizacion_cobro(0, 1, '2019-01-01', '2019-12-31');
        print json_encode($resu); 
    }    

    public function guardar_comprobante_venta($iddoc, $sucursal, $desde, $hasta){
        $resu = $this->Contab_comprobante_model->sel_contabilizacion_venta($iddoc, $sucursal, $desde, $hasta);
        if ($iddoc == 0){
            $referencia = "Ventas del " . $desde . " al " . $hasta;
        }
        else{
            $objdoc = $this->Facturar_model->datosfactura($iddoc);
            $referencia = "Factura ";
            if ($objdoc) { 
                $referencia .= $objdoc[0]->nro_factura; 
                $sucursal = $objdoc[0]->id_sucursal; 
                $desde = $objdoc[0]->fecha; 
                $hasta = $objdoc[0]->fecha; 
            }
        }
        $descripcion = $referencia;
        $idusu = $this->session->userdata("sess_id");
        $idtipocomprobante = 4;

        return $this->Contab_comprobante_model->ins_comprobante_contabilizacion($sucursal, $idtipocomprobante, $referencia, $hasta, $idusu, $descripcion, $resu['detalles'], $resu['documentos']);        
    }

    public function guardar_comprobante_cobro($listadoc, $sucursal, $desde, $hasta, $referencia, $descripcion){
        $resu = $this->Contab_comprobante_model->sel_contabilizacion_cobro($listadoc, $sucursal, $desde, $hasta);
        $idusu = $this->session->userdata("sess_id");
        $idtipocomprobante = 5;

        return $this->Contab_comprobante_model->ins_comprobante_contabilizacion($sucursal, $idtipocomprobante, $referencia, $hasta, $idusu, $descripcion, $resu['detalles'], $resu['documentos']);        
    }

    public function guardar_comprobante_pago($listadoc, $sucursal, $desde, $hasta, $referencia, $descripcion){
        $resu = $this->Contab_comprobante_model->sel_contabilizacion_pago($listadoc, $sucursal, $desde, $hasta);
        $idusu = $this->session->userdata("sess_id");
        $idtipocomprobante = 8;

        return $this->Contab_comprobante_model->ins_comprobante_contabilizacion($sucursal, $idtipocomprobante, $referencia, $hasta, $idusu, $descripcion, $resu['detalles'], $resu['documentos']);        
    }

    public function ins_comprobante_compra(){
        $iddoc = $this->input->post('id'); 
        $sucursal = $this->input->post('sucursal'); 
        $desde = $this->input->post('desde'); 
        $hasta = $this->input->post('hasta'); 

        $idcmp = $this->guardar_comprobante_compra($iddoc, $sucursal, $desde, $hasta);        
        print json_encode($idcmp); 
    }

    public function guardar_comprobante_compra($iddoc, $sucursal, $desde, $hasta){
        $resu = $this->Contab_comprobante_model->sel_contabilizacion_compra($iddoc, $sucursal, $desde, $hasta);
        if ($iddoc == 0){
            $referencia = "Compras del " . $desde . " al " . $hasta;
        }
        else{
            $objdoc = $this->Compra_model->busca_compra($iddoc);
            $referencia = "Factura de Compra ";
            if ($objdoc) { 
                $referencia .= $objdoc->nro_factura; 
                $sucursal = $objdoc->id_sucursal; 
                $desde = $objdoc->fecha; 
                $hasta = $objdoc->fecha; 
            }
        }
        $descripcion = $referencia;
        $idusu = $this->session->userdata("sess_id");
        $idtipocomprobante = 6;

        return $this->Contab_comprobante_model->ins_comprobante_contabilizacion($sucursal, $idtipocomprobante, $referencia, $hasta, $idusu, $descripcion, $resu['detalles'], $resu['documentos']);        
    }

    public function ins_comprobante_gasto(){
        $iddoc = $this->input->post('id'); 
        $sucursal = $this->input->post('sucursal'); 
        $desde = $this->input->post('desde'); 
        $hasta = $this->input->post('hasta'); 

        $idcmp = $this->guardar_comprobante_gasto($iddoc, $sucursal, $desde, $hasta);        
        print json_encode($idcmp); 
    }

    public function guardar_comprobante_gasto($iddoc, $sucursal, $desde, $hasta){
        $resu = $this->Contab_comprobante_model->sel_contabilizacion_gasto($iddoc, $sucursal, $desde, $hasta);
        if ($iddoc == 0){
            $referencia = "Gastos del " . $desde . " al " . $hasta;
        }
        else{
            $objdoc = $this->Gastos_model->busca_gasto($iddoc);
            $referencia = "Factura de Gasto ";
            if ($objdoc) { 
                $referencia .= $objdoc->nro_factura; 
                $sucursal = $objdoc->id_sucursal; 
                $desde = $objdoc->fecha; 
                $hasta = $objdoc->fecha; 
            }
        }
        $descripcion = $referencia;
        $idusu = $this->session->userdata("sess_id");
        $idtipocomprobante = 7;

        return $this->Contab_comprobante_model->ins_comprobante_contabilizacion($sucursal, $idtipocomprobante, $referencia, $hasta, $idusu, $descripcion, $resu['detalles'], $resu['documentos']);        
    }

    public function ins_comprobante_cobrodocventa(){
        $iddoc = $this->input->post('id'); 
        $sucursal = $this->input->post('sucursal'); 
        $desde = $this->input->post('desde'); 
        $hasta = $this->input->post('hasta'); 

        $doccobro = $this->Contab_comprobante_model->sel_documento_cobro_venta($iddoc);
        $objdoc = $this->Facturar_model->datosfactura($iddoc);
        $referencia = "Cobro de Factura ";
        if ($objdoc) { 
            $referencia .= $objdoc[0]->nro_factura; 
            $sucursal = $objdoc[0]->id_sucursal; 
            $desde = $objdoc[0]->fecha; 
            $hasta = $objdoc[0]->fecha; 
        }

        $idcmp = $this->guardar_comprobante_cobro($doccobro, $sucursal, $desde, $hasta, $referencia, $referencia);        
        print json_encode($idcmp); 
    }

    public function ins_comprobante_cobro(){
        $iddoc = $this->input->post('id'); 

        $idcmp = $this->insertar_comprobante_cobro($iddoc);
        print json_encode($idcmp); 
    }

    public function insertar_comprobante_cobro($iddoc){
        $sucursal = 0; 
        $desde = '';
        $hasta = '';

        $doccobro = [];
        $doccobro[] = $iddoc;
        $referencia = "Documento de Cobro  ";
        $objcobro = $this->Credito_model->sel_abono($iddoc);
        $descripcion = "Cobro de Factura ";
        if ($objcobro){
            $referencia .= $objcobro->nro_comprobante;
            $desde = $objcobro->fecha; 
            $hasta = $objcobro->fecha; 
            $objdoc = $this->Facturar_model->datosfactura($objcobro->id_venta);
            if ($objdoc) { 
                $descripcion .= $objdoc[0]->nro_factura; 
                $sucursal = $objdoc[0]->id_sucursal; 
            }   
        }

        $idcmp = $this->guardar_comprobante_cobro($doccobro, $sucursal, $desde, $hasta, $referencia, $descripcion);        
        return $idcmp; 
    }

    public function upd_comprobante_cobro(){
        $iddoc = $this->input->post('id'); 

        $objabono = $this->Credito_model->sel_abono($iddoc);
        $numero = $objabono->nro_comprobante; 

        $this->Contab_comprobante_model->anula_comprobante_documento($iddoc, $numero, 5);

        $this->insertar_comprobante_cobro($iddoc);
    }

    public function del_comprobante_cobro(){
        $iddoc = $this->input->post('id'); 
        $numero = $this->input->post('numero'); 

        $this->Contab_comprobante_model->elimina_documento_comprobante($iddoc, $numero, 5);
    }

    public function del_comprobante_pago(){
        $iddoc = $this->input->post('id'); 
        $numero = $this->input->post('numero'); 

        $this->Contab_comprobante_model->elimina_documento_comprobante($iddoc, $numero, 8);
    }

    public function ins_comprobante_pagodoccompra(){
        $iddoc = $this->input->post('id'); 
        $idcmp = 0;
        $docpago = $this->Contab_comprobante_model->sel_documento_pago_abonocompra($iddoc);
        $objpago = $this->Compraabono_model->sel_abono($iddoc);
        $objdoc = $this->Compra_model->busca_compra($objpago->id_comp);
        $referencia = "Pago de Factura ";
        if ($objdoc) { 
            $referencia .= $objdoc->nro_factura; 
            $sucursal = $objdoc->id_sucursal; 
            $desde = $objpago->fecha; 
            $hasta = $objpago->fecha; 
            $idcmp = $this->guardar_comprobante_pago($docpago, $sucursal, $desde, $hasta, $referencia, $referencia);        
        }
        print json_encode($idcmp); 
    }

    public function ins_comprobante_pagodocgasto(){
        $iddoc = $this->input->post('id'); 
        $idcmp = 0;
        $docpago = $this->Contab_comprobante_model->sel_documento_pago_gasto($iddoc);
        $objdoc = $this->Gastos_model->busca_gasto($iddoc);
        $referencia = "Pago de Factura ";
        if ($objdoc) { 
            $referencia .= $objdoc->nro_factura; 
            $sucursal = $objdoc->id_sucursal; 
            $desde = $objdoc->fecha; 
            $hasta = $objdoc->fecha; 
            $idcmp = $this->guardar_comprobante_pago($docpago, $sucursal, $desde, $hasta, $referencia, $referencia);        
        }
        print json_encode($idcmp); 
    }

    public function frm_anula_comprobante(){
        $idcmp = $this->input->post('id'); 
        $data["idcmp"] = $idcmp;

        $data["base_url"] = base_url();
        $this->load->view("contabilidad/contab_comprobantes_anular", $data);
    } 

    public function ins_comprobante_ingresoinv(){
        $iddoc = $this->input->post('id'); 
        $sucursal = $this->input->post('sucursal'); 
        $desde = $this->input->post('desde'); 
        $hasta = $this->input->post('hasta'); 

        $idcmp = $this->guardar_comprobante_ingresoinv($iddoc, $sucursal, $desde, $hasta);        
        print json_encode($idcmp); 
    }

    public function guardar_comprobante_ingresoinv($iddoc, $sucursal, $desde, $hasta){
        $resu = $this->Contab_comprobante_model->sel_contabilizacion_ingresoinv($iddoc, $sucursal, $desde, $hasta);
        if ($iddoc == 0){
            $referencia = "Ingresos de Inventario del " . $desde . " al " . $hasta;
        }
        else{
            $objdoc = $this->Inventario_model->encrecpdf($iddoc);
            $referencia = "Ingreso de Inventario ";
            if ($objdoc) { 
                $referencia .= $objdoc->nro_documento; 
                $sucursal = $objdoc->sucursal_id; 
                $desde = $objdoc->fecha; 
                $hasta = $objdoc->fecha; 
            }
        }
        $descripcion = $referencia;
        $idusu = $this->session->userdata("sess_id");
        $idtipocomprobante = 9;

        return $this->Contab_comprobante_model->ins_comprobante_contabilizacion($sucursal, $idtipocomprobante, $referencia, $hasta, $idusu, $descripcion, $resu['detalles'], $resu['documentos']);        
    }

    public function ins_comprobante_egresoinv(){
        $iddoc = $this->input->post('id'); 
        $sucursal = $this->input->post('sucursal'); 
        $desde = $this->input->post('desde'); 
        $hasta = $this->input->post('hasta'); 

        $idcmp = $this->guardar_comprobante_egresoinv($iddoc, $sucursal, $desde, $hasta);        
        print json_encode($idcmp); 
    }

    public function guardar_comprobante_egresoinv($iddoc, $sucursal, $desde, $hasta){
        $resu = $this->Contab_comprobante_model->sel_contabilizacion_egresoinv($iddoc, $sucursal, $desde, $hasta);
        if ($iddoc == 0){
            $referencia = "Egresos de Inventario del " . $desde . " al " . $hasta;
        }
        else{
            $objdoc = $this->Inventario_model->encrecpdf($iddoc);
            $referencia = "Egreso de Inventario ";
            if ($objdoc) { 
                $referencia .= $objdoc->nro_documento; 
                $sucursal = $objdoc->sucursal_id; 
                $desde = $objdoc->fecha; 
                $hasta = $objdoc->fecha; 
            }
        }
        $descripcion = $referencia;
        $idusu = $this->session->userdata("sess_id");
        $idtipocomprobante = 10;

        return $this->Contab_comprobante_model->ins_comprobante_contabilizacion($sucursal, $idtipocomprobante, $referencia, $hasta, $idusu, $descripcion, $resu['detalles'], $resu['documentos']);        
    }

    public function ins_comprobante_retencionventa(){
        $iddoc = $this->input->post('id'); 
        $sucursal = $this->input->post('sucursal'); 
        $desde = $this->input->post('desde'); 
        $hasta = $this->input->post('hasta'); 

        $idcmp = $this->guardar_comprobante_retencionventa($iddoc, $sucursal, $desde, $hasta);        
        print json_encode($idcmp); 
    }

    public function guardar_comprobante_retencionventa($iddoc, $sucursal, $desde, $hasta){
        $resu = $this->Contab_comprobante_model->sel_contabilizacion_retencionventa($iddoc, $sucursal, $desde, $hasta);
        if ($iddoc == 0){
            $referencia = "Retenciones de Venta del " . $desde . " al " . $hasta;
        }
        else{
            $objdoc = $this->Retencion_model->sel_retencionventa($iddoc);
            $referencia = "Retención de Venta ";
            if ($objdoc) { 
                $referencia .= $objdoc->nro_retencion; 
                $sucursal = $objdoc->id_sucursal; 
                $desde = $objdoc->fecha_retencion; 
                $hasta = $objdoc->fecha_retencion; 
            }
        }
        $descripcion = $referencia;
        $idusu = $this->session->userdata("sess_id");
        $idtipocomprobante = 11;

        return $this->Contab_comprobante_model->ins_comprobante_contabilizacion($sucursal, $idtipocomprobante, $referencia, $hasta, $idusu, $descripcion, $resu['detalles'], $resu['documentos']);        
    }

    public function ins_comprobante_retencioncompra(){
        $iddoc = $this->input->post('id'); 
        $sucursal = $this->input->post('sucursal'); 
        $desde = $this->input->post('desde'); 
        $hasta = $this->input->post('hasta'); 

        $idcmp = $this->guardar_comprobante_retencioncompra($iddoc, $sucursal, $desde, $hasta);        
        print json_encode($idcmp); 
    }

    public function guardar_comprobante_retencioncompra($iddoc, $sucursal, $desde, $hasta){
        $resu = $this->Contab_comprobante_model->sel_contabilizacion_retencioncompra($iddoc, $sucursal, $desde, $hasta);
        if ($iddoc == 0){
            $referencia = "Retenciones de Compra del " . $desde . " al " . $hasta;
        }
        else{
            $objdoc = $this->Retencion_model->sel_retencioncompra($iddoc);
            $referencia = "Retención de Compra ";
            if ($objdoc) { 
                $referencia .= $objdoc->nro_retencion; 
                $sucursal = $objdoc->id_sucursal; 
                $desde = $objdoc->fecha_retencion; 
                $hasta = $objdoc->fecha_retencion; 
            }
        }
        $descripcion = $referencia;
        $idusu = $this->session->userdata("sess_id");
        $idtipocomprobante = 12;

        return $this->Contab_comprobante_model->ins_comprobante_contabilizacion($sucursal, $idtipocomprobante, $referencia, $hasta, $idusu, $descripcion, $resu['detalles'], $resu['documentos']);        
    }

    public function ins_comprobante_retenciongasto(){
        $iddoc = $this->input->post('id'); 
        $sucursal = $this->input->post('sucursal'); 
        $desde = $this->input->post('desde'); 
        $hasta = $this->input->post('hasta'); 

        $idcmp = $this->guardar_comprobante_retenciongasto($iddoc, $sucursal, $desde, $hasta);        
        print json_encode($idcmp); 
    }

    public function guardar_comprobante_retenciongasto($iddoc, $sucursal, $desde, $hasta){
        $resu = $this->Contab_comprobante_model->sel_contabilizacion_retenciongasto($iddoc, $sucursal, $desde, $hasta);
        if ($iddoc == 0){
            $referencia = "Retenciones de Gasto del " . $desde . " al " . $hasta;
        }
        else{
            $objdoc = $this->Retencion_model->sel_retenciongasto($iddoc);
            $referencia = "Retención de Gasto ";
            if ($objdoc) { 
                $referencia .= $objdoc->nro_retencion; 
                $sucursal = $objdoc->id_sucursal; 
                $desde = $objdoc->fecha_retencion; 
                $hasta = $objdoc->fecha_retencion; 
            }
        }
        $descripcion = $referencia;
        $idusu = $this->session->userdata("sess_id");
        $idtipocomprobante = 13;

        return $this->Contab_comprobante_model->ins_comprobante_contabilizacion($sucursal, $idtipocomprobante, $referencia, $hasta, $idusu, $descripcion, $resu['detalles'], $resu['documentos']);        
    }

    public function frm_genera_comprobante(){
        $this->session->unset_userdata("tmp_cmp_tipocmp");
        $this->session->set_userdata("tmp_cmp_tipocmp", 0); 

        $desde = $this->session->userdata("tmp_cmp_desde");
        $data["desde"] = $desde;
        $hasta = $this->session->userdata("tmp_cmp_hasta");
        $data["hasta"] = $hasta;
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        $data["sucursal"] = $sucursal;
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $data["sucursales"] = $sucursales;
        $tipos = $this->Contab_comprobante_model->lst_tipocomprobantes();
        $data["tipos"] = $tipos;
        $data["base_url"] = base_url();
        $this->load->view("contabilidad/contab_comprobantes_generar", $data);
    } 

    public function tmp_cmp_fechagenerar() {
        $this->session->unset_userdata("tmp_cmp_desde"); 
        $this->session->unset_userdata("tmp_cmp_hasta");
        $this->session->unset_userdata("tmp_cmp_tipocmp");
        $this->session->unset_userdata("tmp_cmp_sucursal");
        $sucursal = $this->input->post("sucursal");
        $tipocmp = $this->input->post("tipocmp");
        $desde = $this->input->post("desde");
        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = $this->input->post("hasta");
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        $this->session->set_userdata("tmp_cmp_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_cmp_desde", $desde);} 
        else { $this->session->set_userdata("tmp_cmp_desde", NULL); }
        $this->session->set_userdata("tmp_cmp_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_cmp_hasta", $hasta);} 
        else { $this->session->set_userdata("tmp_cmp_hasta", NULL);}
        $this->session->set_userdata("tmp_cmp_tipocmp", NULL);
        if ($tipocmp != NULL) { $this->session->set_userdata("tmp_cmp_tipocmp", $tipocmp);} 
        else { $this->session->set_userdata("tmp_cmp_tipocmp", NULL);}
        $this->session->set_userdata("tmp_cmp_sucursal", NULL);
        if ($sucursal != NULL) { $this->session->set_userdata("tmp_cmp_sucursal", $sucursal);} 
        else { $this->session->set_userdata("tmp_cmp_sucursal", 1);}
        $arr['resu'] = 1;
        print json_encode($arr);
      } 

    public function listadoVistaprevia() {
        $desde = $this->session->userdata("tmp_cmp_desde");
        $hasta = $this->session->userdata("tmp_cmp_hasta");
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        $tipocmp = $this->session->userdata("tmp_cmp_tipocmp");
        $resu = null;
        if ($tipocmp == 4){
            $resu = $this->Contab_comprobante_model->sel_contabilizacion_venta(0, $sucursal, $desde, $hasta);
        }
        elseif (($tipocmp == 5)){
            $doccobro = $this->Contab_comprobante_model->sel_documento_cobro_fecha($sucursal, $desde, $hasta);
            $resu = $this->Contab_comprobante_model->sel_contabilizacion_cobro($doccobro, $sucursal, $desde, $hasta);
        }
        elseif (($tipocmp == 6)){
            $resu = $this->Contab_comprobante_model->sel_contabilizacion_compra(0, $sucursal, $desde, $hasta);
        }
        elseif (($tipocmp == 7)){
            $resu = $this->Contab_comprobante_model->sel_contabilizacion_gasto(0, $sucursal, $desde, $hasta);
        }
        elseif (($tipocmp == 8)){
            $docpago = $this->Contab_comprobante_model->sel_documento_pago_fecha($sucursal, $desde, $hasta);
            $resu = $this->Contab_comprobante_model->sel_contabilizacion_pago($docpago, $sucursal, $desde, $hasta);
        }
        elseif (($tipocmp == 9)){
            $resu = $this->Contab_comprobante_model->sel_contabilizacion_ingresoinv(0, $sucursal, $desde, $hasta);
        }
        elseif (($tipocmp == 10)){
            $resu = $this->Contab_comprobante_model->sel_contabilizacion_egresoinv(0, $sucursal, $desde, $hasta);
        }
        elseif (($tipocmp == 11)){
            $resu = $this->Contab_comprobante_model->sel_contabilizacion_retencionventa(0, $sucursal, $desde, $hasta);
        }
        elseif (($tipocmp == 12)){
            $resu = $this->Contab_comprobante_model->sel_contabilizacion_retencioncompra(0, $sucursal, $desde, $hasta);
        }
        elseif (($tipocmp == 13)){
            $resu = $this->Contab_comprobante_model->sel_contabilizacion_retenciongasto(0, $sucursal, $desde, $hasta);
        }

        $tabla = "";
        if ($resu != null){
            foreach ($resu['detalles'] as $row) {
                if ($row->idcuenta){
                    $objcuenta = $this->Contab_plancuentas_model->sel_cuenta_id($row->idcuenta);
                    $debito = 0;
                    $credito = 0;
                    if ($row->debito == 1){
                        $debito = $row->valor;
                    }
                    else{
                        $credito = $row->valor;
                    }
                    $debito = '<div ><label class=\"col-md-12 text-right upd_debito \" style=\"font-weight: normal;\" id=\"'.$row->idcuenta.'\">'.number_format($debito, 2).'</label> </div>';
                    $credito = '<div ><label class=\"col-md-12 text-right upd_credito \" style=\"font-weight: normal;\" id=\"'.$row->idcuenta.'\">'.number_format($credito, 2).'</label> </div>';

                    $tabla.='{  "id":"' .$row->idcuenta. '",
                                "cuenta":"' .$objcuenta->codigocuenta. '",
                                "debito":"' .$debito. '",
                                "credito":"' .$credito. '"
                            },';
                }            
            }
        }    
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function listadoTipodocumentos() {
        $registro = $this->Contab_comprobante_model->lst_tipocomprobantes();
        $tabla = "";
        foreach ($registro as $row) {
            $nombre = '<div ><label class=\"col-md-12 nombretipo\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" >'.addslashes($row->nombre).'</label> </div>';

            $tabla.='{  "id":"' .$row->id. '",
                        "nombre":"' .$nombre. '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function generar_comprobante(){
        $tipo = $this->input->post('tiposeleccionado'); 
        $sucursal = $this->input->post('g_sucursal'); 
        $desde = $this->input->post('gdesde'); 
        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = $this->input->post('ghasta'); 
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        $idcmp = 0;
        if ($tipo == 4){
            $idcmp = $this->guardar_comprobante_venta(0, $sucursal, $desde, $hasta);           
        }
        elseif ($tipo == 5){
            $referencia = "Cobros del " . $desde . " al " . $hasta;
            $doccobro = $this->Contab_comprobante_model->sel_documento_cobro_fecha($sucursal, $desde, $hasta);
            $idcmp = $this->guardar_comprobante_cobro($doccobro, $sucursal, $desde, $hasta, $referencia);           
        }
        elseif ($tipo == 6){
            $idcmp = $this->guardar_comprobante_compra(0, $sucursal, $desde, $hasta);           
        }
        elseif ($tipo == 7){
            $idcmp = $this->guardar_comprobante_gasto(0, $sucursal, $desde, $hasta);           
        }
        elseif ($tipo == 8){
            $referencia = "Pagos del " . $desde . " al " . $hasta;
            $docpago = $this->Contab_comprobante_model->sel_documento_pago_fecha($sucursal, $desde, $hasta);
            $idcmp = $this->guardar_comprobante_pago($docpago, $sucursal, $desde, $hasta, $referencia);           
        }
        elseif ($tipo == 9){
            $idcmp = $this->guardar_comprobante_ingresoinv(0, $sucursal, $desde, $hasta);           
        }
        elseif ($tipo == 10){
            $idcmp = $this->guardar_comprobante_egresoinv(0, $sucursal, $desde, $hasta);           
        }
        elseif ($tipo == 11){
            $idcmp = $this->guardar_comprobante_retencionventa(0, $sucursal, $desde, $hasta);           
        }
        elseif ($tipo == 12){
            $idcmp = $this->guardar_comprobante_retencioncompra(0, $sucursal, $desde, $hasta);           
        }
        elseif ($tipo == 13){
            $idcmp = $this->guardar_comprobante_retenciongasto(0, $sucursal, $desde, $hasta);           
        }
        print json_encode($idcmp); 
    } 

    public function cuentas_configuradas_venta(){
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_venta($empresa);
        print json_encode($resu); 
    }

    public function cuentas_configuradas_ventacaja(){
        $iddoc = $this->input->post('id'); 
        $objfac = $this->Facturar_model->datosfactura($iddoc);
        if ($objfac){
            $sucursal = $objfac[0]->id_sucursal;
            $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
            $empresa = $objsuc->id_empresa; 
            $resu = $this->Contab_comprobante_model->cuentas_configuradas_venta($empresa);
            print json_encode($resu);    
        }
        else
            print json_encode(false);    
    }

    public function cuentas_configuradas_cobro(){
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_cobro($empresa);
        print json_encode($resu); 
    }

    public function cuentas_configuradas_pago(){
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_pago($empresa);
        print json_encode($resu); 
    }

    public function cuentas_configuradas_compra(){
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_compra($empresa);
        print json_encode($resu); 
    }

    public function cuentas_configuradas_gasto(){
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_gasto($empresa);
        print json_encode($resu); 
    }

    public function factura_con_abonos(){
        $iddoc = $this->input->post('id'); 
        $resu = $this->Contab_comprobante_model->factura_con_abonos($iddoc);
        print json_encode($resu); 
    }
    
    public function cuentas_configuradas_cobrocaja(){
        $iddoc = $this->input->post('id'); 
        $objcobro = $this->Credito_model->sel_abono($iddoc);
        $objfac = $this->Facturar_model->datosfactura($objcobro->id_venta);
        if ($objfac){
            $sucursal = $objfac[0]->id_sucursal;
            $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
            $empresa = $objsuc->id_empresa; 
            $resu = $this->Contab_comprobante_model->cuentas_configuradas_cobro($empresa);
            print json_encode($resu);    
        }
        else
            print json_encode(false);    
    }

    public function cuentas_configuradas_cobrosucursal(){
        $sucursal = $this->input->post('sucursal'); 
        if ($sucursal){
            $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
            $empresa = $objsuc->id_empresa; 
            $resu = $this->Contab_comprobante_model->cuentas_configuradas_cobro($empresa);
            print json_encode($resu);    
        }
        else
            print json_encode(false);    
    }

    public function cuentas_configuradas_comprasucursal(){
        $sucursal = $this->input->post('sucursal'); 
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_compra($empresa);
        print json_encode($resu); 
    }

    public function cuentas_configuradas_gastosucursal(){
        $sucursal = $this->input->post('sucursal'); 
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_gasto($empresa);
        print json_encode($resu); 
    }

    public function cuentas_configuradas_pagosucursal(){
        $sucursal = $this->input->post('sucursal'); 
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_pago($empresa);
        print json_encode($resu);    
    }

    public function cuentas_configuradas_pagocompra(){
        $iddoc = $this->input->post('id'); 
        $objpago = $this->Compraabono_model->sel_abono($iddoc);
        $objfac = $this->Compra_model->busca_compra($objpago->id_comp);
        if ($objfac){
            $sucursal = $objfac->id_sucursal;
            $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
            $empresa = $objsuc->id_empresa; 
            $resu = $this->Contab_comprobante_model->cuentas_configuradas_pago($empresa);
            $arr['resu'] = $resu;
            $arr['iddocpago'] = $objpago->iddocpago;
            print json_encode($arr);    
        }
        else{
            $arr['resu'] = false;
            print json_encode($arr);    
        }
    }

    public function cuentas_configuradas_ingresoinv(){
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_ingresoinv($empresa);
        print json_encode($resu); 
    }

    public function cuentas_configuradas_ingresoinvdoc(){
        $almacen = $this->input->post('almacen'); 
        $objalm = $this->Almacen_model->sel_alm_id($almacen);
        $objsuc = $this->Sucursal_model->sel_suc_id($objalm->sucursal_id);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_ingresoinv($empresa);
        print json_encode($resu); 
    }

    public function cuentas_configuradas_egresoinv(){
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_egresoinv($empresa);
        print json_encode($resu); 
    }

    public function cuentas_configuradas_egresoinvdoc(){
        $almacen = $this->input->post('almacen'); 
        $objalm = $this->Almacen_model->sel_alm_id($almacen);
        $objsuc = $this->Sucursal_model->sel_suc_id($objalm->sucursal_id);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_egresoinv($empresa);
        print json_encode($resu); 
    }

    public function cuentas_configuradas_retencioncompra(){
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_retencioncompra($empresa);
        print json_encode($resu); 
    }

    public function cuentas_configuradas_retencionventa(){
        $sucursal = $this->session->userdata("tmp_cmp_sucursal");
        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        $empresa = $objsuc->id_empresa; 
        $resu = $this->Contab_comprobante_model->cuentas_configuradas_retencionventa($empresa);
        print json_encode($resu); 
    }

    /* SECCION DE FACTURACION EN PDF --------------------------------------------------------------------------------*/
    private function pagina_v() {
        $this->fpdf->SetMargins('12', '7', '10');   #Margenes
        $this->fpdf->AddPage('P', 'Letter');        #Orientación y tamaño 
      }
  
      private function pagina_h() {
        $this->fpdf->SetMargins('12', '4', '10');   #Margenes
        $this->fpdf->AddPage('L', 'Letter');        #Orientación y tamaño
      }    

      public function comprobantepdf(){
        $idcmp = $this->session->userdata("tmp_comprobante_id");
        $objcmp = $this->Contab_comprobante_model->sel_comprobante_id($idcmp);        
        $emp = $this->Empresa_model->emp_get();      
        $sucursal = $this->Sucursal_model->sel_suc_id($objcmp->idsucursal);      
        // ENCABEZADO DEL PDF 
        $this->load->library('fpdf');
        $this->fpdf->fontpath = 'font/'; 
        $this->fpdf->AliasNbPages();
        $this->pagina_v();
  
        if ($sucursal->logo_sucursal){    
          $file_name = "ppp.jpg";
          $pic = base64_decode($sucursal->logo_sucursal);
          imagejpeg(imagecreatefromstring ( $pic ), $file_name);
          
          $this->fpdf->Image($file_name,10,10,30,14);
        }  
        $this->fpdf->Line(12,25,196,25);
        $this->fpdf->SetFont('Arial','B',6);

        $this->fpdf->SetXY(100,8);
        $this->fpdf->Cell(20,10,utf8_decode($sucursal->dir_sucursal),0,0,'C');
        $this->fpdf->SetXY(100,13);
        $this->fpdf->Cell(20,10,utf8_decode($sucursal->telf_sucursal),0,0,'C');
        $this->fpdf->SetXY(100,18);
        $this->fpdf->Cell(20,10,utf8_decode($sucursal->mail_sucursal),0,0,'C');
  
        $this->fpdf->SetFont('Arial','B',8);        
        $this->fpdf->SetXY(175,20);
        $fecha = str_replace('-', '/', $objcmp->fechaasiento); $fecha = date("d/m/Y", strtotime($fecha));
        $this->fpdf->Cell(20,4,utf8_decode("Fecha : $fecha"),0,1,'R');
  
        //$this->pdf_f->Rect(165, 12, 30, 10, "D");
        $this->fpdf->SetFont('Arial','B',12);     
        $objtipo = $this->Contab_comprobante_model->sel_tipoasiento_id($objcmp->idtipocomprobante);   
        $this->fpdf->SetXY(10,24);
        if ($objcmp->idestado == 2){
            $this->fpdf->Cell(195,10,"ASIENTO CONTABLE",0,0,'C');
            $this->fpdf->ln(10);            
        }
        else{
            if ($objcmp->idestado == 3){
                $objanulado = $this->Contab_comprobante_model->sel_comprobante_anulacion($idcmp);   
                $fecha = str_replace('-', '/', $objanulado->fechaanulacion); $fecha = date("d/m/Y", strtotime($fecha));
                $this->fpdf->Cell(195,10,"ASIENTO CONTABLE  - " . $objcmp->estado . "  " . $fecha,0,0,'C');
                $this->fpdf->ln();            
                $this->fpdf->SetXY(10,28);
                $this->fpdf->SetFont('Arial','B',8);
                $this->fpdf->Cell(195,10,utf8_decode($objanulado->motivoanulacion),0,0,'C');
                $this->fpdf->ln(8);            
            }
            else{
                $this->fpdf->Cell(195,10,"ASIENTO CONTABLE  - " . $objcmp->estado,0,0,'C');
                $this->fpdf->ln(10);            
            }
        }
  
        $this->fpdf->SetFont('Arial','B',8);

        $this->fpdf->Cell(80,4,utf8_decode($objtipo->nombre.": ".$objcmp->numero),0,0,'L');
        $this->fpdf->Cell(105,4,utf8_decode("Referencia : $objcmp->referencia"),0,1,'R');
        $this->fpdf->MultiCell(185,5,utf8_decode("Descripción : $objcmp->descripcion"));   

        $this->fpdf->ln(6); 
  
        $this->fpdf->SetFillColor(139, 35, 35);
        $this->fpdf->SetFont('Arial','B',8);
        $this->fpdf->SetTextColor(0,0,0);
        // TITULO DE DETALLES  
        $this->fpdf->Cell(30,4,utf8_decode("Cuenta"),1,0,'C');
        $this->fpdf->Cell(60,4,utf8_decode("Descripcion"),1,0,'L');
        $this->fpdf->Cell(55,4,utf8_decode('Concepto'),1,0,'C');
        $this->fpdf->Cell(20,4,utf8_decode('Débito'),1,0,'R');
        $this->fpdf->Cell(20,4,utf8_decode('Crédito'),1,1,'R');
        // CICLO DE DETALLES  
        $objdetalle = $this->Contab_comprobante_model->sel_comprobantedetalle($idcmp);
        foreach ($objdetalle['detalles'] as $row) {
          $cuenta = $row->codigocuenta;
          $descripcion = $row->descripcion;
          $concepto = substr($row->concepto,0,50);
          $debito = number_format($row->debito,2);
          $credito = number_format($row->credito,2);
  
          $tmpY = $this->fpdf->GetY();
  
          $this->fpdf->SetFont('Arial','',8);        
          $this->fpdf->Cell(30,4,utf8_decode($cuenta),0,0,'L');
          $this->fpdf->MultiCell(60,4,utf8_decode($descripcion));
          $tmpYdetalle1 = $this->fpdf->GetY();
          $this->fpdf->SetXY(102,$tmpY);

          $this->fpdf->MultiCell(55,4,utf8_decode($concepto));
          $tmpYdetalle2 = $this->fpdf->GetY();
          $this->fpdf->SetXY(155,$tmpY);

          $this->fpdf->Cell(20,4,$debito,0,0,'R'); 
          $this->fpdf->Cell(20,4,$credito,0,1,'R'); 

          $this->fpdf->SetY(max($tmpYdetalle1, $tmpYdetalle2));
        }
  
        $this->fpdf->SetFont('Arial','B',10);
        $tmpy = $this->fpdf->GetY();
        $tmpy += 10;

        $debito = 0;
        $credito = 0;
        if ($objdetalle['suma']){
            $debito = number_format($objdetalle['suma'][0]->debito,2);
            $credito = number_format($objdetalle['suma'][0]->credito,2);
        }
        
        $this->fpdf->SetXY(100,$tmpy);
        $this->fpdf->Cell(55,4,utf8_decode("Total:"),0,0,'R');
        $this->fpdf->Cell(20,4,utf8_decode($debito),0,0,'R');
        $this->fpdf->Cell(20,4,utf8_decode($credito),0,1,'R');
 

        $this->fpdf->Output('Comprobante','I'); 
      } 
  
      
    public function tipocomprobantes() {
        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_tipocomprobantes";
        $this->load->view("layout", $data);
    }

    public function listadoTipocomprobantes() {
        $registro = $this->Contab_comprobante_model->sel_tipoasiento();
        $tabla = "";
        foreach ($registro as $row) {

            $prefijo = '<div ><input type=\"text\" style=\" width: 150px; \" class=\"col-md-12 upd_prefijo \" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.$row->prefijo.'\" ></div>';

            $tabla.='{  "id":"' .$row->id. '",
                        "nombre":"' .$row->nombre. '",
                        "abreviatura":"' .$row->abreviatura. '",
                        "prefijo":"' .$prefijo. '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function upd_tipoasiento_prefijo(){
        $id = $this->input->post('id'); 
        $prefijo = $this->input->post('prefijo'); 
        $resu = $this->Contab_comprobante_model->upd_tipoasiento_prefijo($id, $prefijo);
        print json_encode($resu);    
    }

    public function configsucursal() {
        $sucursal = $this->session->userdata("tmp_cfgcmp_sucursal");

        $sucursales = $this->Sucursal_model->lst_sucursales();
        $data["sucursales"] = $sucursales;
  
        if ($sucursal == NULL){
          $sucursal = 0;
          if (count($sucursales) > 0){
              $sucursal = $sucursales[0]->id_sucursal;
          }  
          $this->session->set_userdata("tmp_cfgcmp_sucursal", NULL);
          if ($sucursal != NULL) { $this->session->set_userdata("tmp_cfgcmp_sucursal", $sucursal); } 
          else { $this->session->set_userdata("tmp_cfgcmp_sucursal", 0); }
        }  
        $data["tmpsucursal"] = $sucursal;

        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_config_sucursal";
        $this->load->view("layout", $data);
    }

    public function tmp_cfgcmp_sucursal() {
        $this->session->unset_userdata("tmp_cfgcmp_sucursal"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_cfgcmp_sucursal", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_cfgcmp_sucursal", $id);
        } else {
            $this->session->set_userdata("tmp_cfgcmp_sucursal", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function listadoTipocmpsucursal() {
        $sucursal = $this->session->userdata("tmp_cfgcmp_sucursal");
        $registro = $this->Contab_comprobante_model->sel_tipocmp_sucursal($sucursal);
        $tabla = "";
        foreach ($registro as $row) {

            $contador = '<div ><input type=\"text\" style=\" width: 100px; \" class=\"col-md-12 upd_contador \" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.$row->contador.'\" ></div>';

            $tabla.='{  "id":"' .$row->id. '",
                        "nombre":"' .$row->nombre. '",
                        "abreviatura":"' .$row->abreviatura. '",
                        "prefijo":"' .$row->prefijo. '",
                        "contador":"' .$contador. '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function upd_tipocmp_sucursal(){
        $sucursal = $this->session->userdata("tmp_cfgcmp_sucursal");
        $lista = $this->input->post('lista'); 
        $automatico = $this->input->post('automatico'); 
        
        $resu = $this->Contab_comprobante_model->upd_tipocmp_sucursal($sucursal, $lista, $automatico);
        print json_encode($resu);    
    }

}

?>