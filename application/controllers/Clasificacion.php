<?php

/*------------------------------------------------
  ARCHIVO: claegoria.php
  DESCRIPCION: Contiene los métodos relacionados con la claegoria.
  FECHA DE CREACIÓN: 06/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Clasificacion extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Clasificacion_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $idusu = $this->session->userdata("sess_id");
        $data["base_url"] = base_url();
        $data["content"] = "clasificacion";
        $this->load->view("layout", $data);
    }

    public function listadoDataCla() {

        $registro = $this->Clasificacion_model->sel_cla();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->id_cla.'\" class=\"btn btn-success btn-xs btn-grad cla_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_cla.'\" class=\"btn btn-danger btn-xs btn-grad cla_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' .$row->id_cla. '",
                      "nombre":"' .$row->nom_cla. '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

     public function tmp_cla() {
        $this->session->unset_userdata("tmp_cla_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_cla_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_cla_id", $id);
        } else {
            $this->session->set_userdata("tmp_cla_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function upd_cla(){
        $id_cla = $this->session->userdata("tmp_cla_id");
        $data["base_url"] = base_url();
        $cla_desc = $this->Clasificacion_model->sel_upd_id($id_cla);
        $data["cla"] = $cla_desc;
        $this->load->view("cla_add", $data);
    }

    public function agregar(){
        /* SE GUARDA EL REGISTRO DE LA claEGORIA */
        $cla = $this->input->post('txt_cla'); 
        $idcla = $this->input->post('txt_idcla'); 
        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idcla != 0){
            /* SE ACTUALIZA EL REGISTRO DEL USUARIO */
            $resu = $this->Clasificacion_model->cla_upd($idcla, $cla);
        } else {
            /* SE GUARDA EL REGISTRO DEL USUARIO */
            $resu = $this->Clasificacion_model->cla_add($cla);
        }
        
        $arr['mens'] = $cla ;
        print json_encode($arr); 
        

    }

    public function add_cla(){
        
        $data["base_url"] = base_url();
        $this->load->view("cla_add", $data);
    } 

    
    public function eliminar(){
        /* SE ELIMINA EL REGISTRO DE LA claEGORIA */
        $idcla = $this->input->post('txt_idcla'); 
        $resu = $this->Clasificacion_model->cla_del($idcla);
        $arr['mens'] = $idcla ;
        print json_encode($arr); 

    }



    public function del_cla(){
        $id_cla = $this->session->userdata("tmp_cla_id");
        $data["base_url"] = base_url();
        $cla_desc = $this->Clasificacion_model->sel_upd_id($id_cla);
        $data["cla"] = $cla_desc;
        $this->load->view("cla_del", $data);
    }






}

?>