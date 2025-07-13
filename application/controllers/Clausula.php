<?php

/*------------------------------------------------
  ARCHIVO: Area.php
  DESCRIPCION: Contiene los métodos relacionados con la Area.
  FECHA DE CREACIÓN: 04/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Clausula extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Clausula_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $clausula = $this->Clausula_model->sel_clausulas();
        $data["clausula"] = $clausula;
        $data["base_url"] = base_url();
        $data["content"] = "clausula";
        $this->load->view("layout", $data);
    }

    public function guardar_cla(){
        $clausulas = $this->input->post('clausulas'); 
        $this->Clausula_model->upd_clausula($clausulas);
        $id = 1;
        print json_encode($id); 
    }

}

?>