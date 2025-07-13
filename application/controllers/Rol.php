<?php

/*------------------------------------------------
  ARCHIVO: Rol.php
  DESCRIPCION: Contiene los métodos relacionados con la Rol.
  FECHA DE CREACIÓN: 27/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Rol extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        //$this->load->Model("almacen_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "roles";
        $this->load->view("layout", $data);
    }
    /* CARGA DE DATOS AL DATATABLE */

}

?>