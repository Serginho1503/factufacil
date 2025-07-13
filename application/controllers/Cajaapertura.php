<?php

/*------------------------------------------------
  ARCHIVO: Cajaapertura.php
  DESCRIPCION: Contiene los métodos relacionados con la Apertura de Caja.
  FECHA DE CREACIÓN: 05/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Cajaapertura extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("cajaapertura_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        
        $cajas = $this->cajaapertura_model->lst_cajaefectivo_noabierta();

        $data["cajas"] = $cajas;
        $data["content"] = "cajaapertura";
        $this->load->view("layout", $data);
    }

    public function existeapertura() {
        $caja = $this->cajaapertura_model->existeapertura();
        $arr['resu'] = $caja;
        print json_encode($arr);
    }

    /* Guardar la Apertura */
    public function guardar(){

        $id = $this->input->post('cmb_caja');
        $monto = $this->input->post('txt_monto');

        /* SE ACTUALIZA EL REGISTRO DEL USUARIO */
        $resu = $this->cajaapertura_model->insertar($id, $monto);

        print "<script language='JavaScript'>alert('Los Datos Fueron Actualizados');</script>";

        redirect('','refresh');
        /*$data["base_url"] = base_url();       
        $data["content"] = "inicio";
        $this->load->view("layout", $data);*/
    }
 

    public function existecajaefectivo_noabierta() {
        $caja = $this->cajaapertura_model->existecajaefectivo_noabierta();
        $arr['resu'] = $caja;
        print json_encode($arr);
    }


    public function cajaefectivo_estaabierta() {
        $id = $this->input->post('cmb_caja');
        $caja = $this->cajaapertura_model->cajaefectivo_abierta($id);       
        $arr['resu'] = $caja;
        print json_encode($arr);
    }
  
}

?>