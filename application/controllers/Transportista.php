<?php

/*------------------------------------------------
  ARCHIVO: Transportista.php
  DESCRIPCION: Contiene los métodos relacionados con Transportista.
  FECHA DE CREACIÓN: 05/12/2018
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Transportista extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Transportista_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "transportista";
        $this->load->view("layout", $data);
    }

    /* CARGA DE DATO AL DATATABLE */
    public function listadoDataTrans() {

        $registro = $this->Transportista_model->sel_transportistas();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->idtransportista.'\" class=\"btn btn-success btn-xs btn-grad edi_tran\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->idtransportista.'\" class=\"btn btn-danger btn-xs btn-grad del_tran\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' . $row->idtransportista . '",
                      "ident":"' . $row->cedula . '",            
                      "nombre":"' . addslashes($row->razonsocial) . '",
                      "ciudad":"' . addslashes($row->ciudad) . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }   


    /* ABRIR VENTANA PARA AGREGAR */
    public function add_transportista(){
        $ident = $this->Transportista_model->identificacion();
        $data["ident"] = $ident;
        $data["base_url"] = base_url();
        $this->load->view("transportista_add", $data);
    } 

    public function guardar(){
        $idtran = $this->input->post('txt_idtran');
        $tip_ide = trim($this->input->post('cmb_tip_ide'));
        $nro_ide = $this->input->post('txt_nro_ident');
        $razonsocial = trim($this->input->post('txt_nom'));
        $correo = trim($this->input->post('txt_mail'));
        $telf = $this->input->post('txt_telf');
        $ciu = trim($this->input->post('txt_ciu'));
        $direccion = trim($this->input->post('txt_dir'));

        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idtran != 0){
            $this->Transportista_model->upd_transportista($idtran, $razonsocial, $direccion, $telf, $nro_ide, $correo, $tip_ide, $ciu);
        } else {
            $this->Transportista_model->add_transportista($razonsocial, $direccion, $telf, $nro_ide, $correo, $tip_ide, $ciu);
        }
       
        $arr['mens'] = 1;
        print json_encode($arr); 
    }

    public function edi_tran(){
        $idtran = $this->session->userdata("tmp_tran_id");
        $obj = $this->Transportista_model->sel_transportista_id($idtran);
        $ident = $this->Transportista_model->identificacion();
        $data["obj"] = $obj;
        $data["ident"] = $ident;
        $data["base_url"] = base_url();
        $this->load->view("transportista_add", $data);
    }

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_tran() {
        $this->session->unset_userdata("tmp_tran_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_tran_id", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_tran_id", $id); } 
        else { $this->session->set_userdata("tmp_tran_id", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    } 

    public function eliminar(){
        $id = $this->input->post('id');
        $del = $this->Transportista_model->del_transportista($id);
        $arr['mens'] = $del;
        print json_encode($arr);

    }

    public function existeIdentificacion(){
        $id = $this->input->post('id');
        $identificacion = $this->input->post('identificacion');
        $resu = $this->Transportista_model->existeIdentificacion($id, $identificacion);
        $arr['resu'] = $resu;
        print json_encode($arr);

    }



}

?>