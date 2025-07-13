<?php

/*------------------------------------------------
  ARCHIVO: Ventadatoadicional.php
  DESCRIPCION: Contiene los métodos relacionados con la Ventadatoadicional.
  FECHA DE CREACIÓN: 30/04/2019
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Ventadatoadicional extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Ventadatoadicional_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $idusu = $this->session->userdata("sess_id");
        $data["base_url"] = base_url();
        $data["content"] = "venta_datoadicional";
        $this->load->view("layout", $data);
    }

    public function tmp_datoadicional() {
        $this->session->unset_userdata("tmp_datoadic_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_datoadic_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_datoadic_id", $id);
        } else {
            $this->session->set_userdata("tmp_datoadic_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function upd_datoadicional(){
        $id = $this->session->userdata("tmp_datoadic_id");
        $data["base_url"] = base_url();
        $obj = $this->Ventadatoadicional_model->sel_datoadicional_id($id);
        $data["obj"] = $obj;
        $this->load->view("venta_datoadicional_add", $data);
    }

    public function existe_datoadicional(){
        $id = $this->input->post('id'); 
        $datoadicional = $this->input->post('datoadicional'); 
        $resu = $this->Ventadatoadicional_model->existe_datoadicional($id, $datoadicional);
        $arr['mens'] = $resu;
        print json_encode($arr); 
    }

    public function guardar_datoadicional(){
        $id = $this->input->post('txt_id'); 
        $datoadicional = $this->input->post('txt_datoadicional'); 
        $activo = $this->input->post('chkactivo');
        if($activo == true){ $activo = 1; } else { $activo = 0; }
        if($id != 0){
            $resu = $this->Ventadatoadicional_model->upd_datoadicional($id, $datoadicional, $activo);
        } else {
            $resu = $this->Ventadatoadicional_model->add_datoadicional($datoadicional, $activo);
        }
        $arr['mens'] = $id;
        print json_encode($arr); 
    }

    public function add_datoadicional(){
        $data["base_url"] = base_url();
        $this->load->view("venta_datoadicional_add", $data);
    } 

    public function del_datoadicional(){
        $id = $this->input->post('id'); 
        $resu = $this->Ventadatoadicional_model->del_datoadicional($id);
        $arr['mens'] = $resu;
        print json_encode($arr); 
    }


    public function listadoDatoadicional() {
        $registro = $this->Ventadatoadicional_model->sel_datoadicional();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Editar Dato Adicional\" id=\"'.$row->id_config.'\" class=\"btn btn-success btn-xs btn-grad det_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_config.'\" class=\"btn btn-danger btn-xs btn-grad det_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $stractivo = ($row->activo == 1) ? 'Activo' : 'Inactivo';
            $tabla.='{  "id":"' .$row->id_config. '",
                        "nombre":"' .$row->nombre_datoadicional. '",
                        "estado":"' .$stractivo. '",
                        "ver":"'.$ver.'"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }
}

?>