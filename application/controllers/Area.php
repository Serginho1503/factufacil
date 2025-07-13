<?php

/*------------------------------------------------
  ARCHIVO: Area.php
  DESCRIPCION: Contiene los métodos relacionados con la Area.
  FECHA DE CREACIÓN: 04/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Area extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("area_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "area";
        $this->load->view("layout", $data);
    }

    /* CARGA EL DATATABLE (LISTADO) */
    public function listadoDataArea() {
        $registro = $this->area_model->sel_area();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->id_area.'\" class=\"btn btn-success btn-xs btn-grad edi_area\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_area.'\" class=\"btn btn-danger btn-xs btn-grad del_area\"><i class=\"fa fa-trash-o\"></i></a></div>';
                    
            $tabla.='{"id":"' . $row->id_area . '",
                      "nombre":"' . $row->nom_area . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    /* LEVANTAR VENTANA PARA AGREGAR DATOS */
    public function add_area(){
        $data["base_url"] = base_url();
        $this->load->view("area_add", $data);
    }    

    /* LEVANTAR VENTANA PARA MODIFICAR DATOS */
    public function upd_area(){
        $idarea = $this->session->userdata("tmp_area");
        $area = $this->area_model->sel_area_id($idarea);
        $data["area"] = $area;
        $data["base_url"] = base_url();
        $this->load->view("area_add", $data);
    }  

    /* LEVANTAR VENTANA PARA ELIMINAR DATOS */
    public function del_area(){
        $idarea = $this->session->userdata("tmp_area");
        $area = $this->area_model->sel_area_id($idarea);
        $data["area"] = $area;
        $data["base_url"] = base_url();
        $this->load->view("area_del", $data);
    }

    /* FUNCION DE VARIABLE DE SESION */    
     public function tmp_area() {
        $this->session->unset_userdata("tmp_area"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_area", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_area", $id); } 
        else { $this->session->set_userdata("tmp_area", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    }
    
    /* SE GUARDA O MODIFICA EL REGISTRO DEL AREA */
    public function guardar(){
        $idarea = $this->input->post('txt_idarea'); 
        $nom = $this->input->post('txt_nomarea'); 
        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idarea != 0){
            /* SE ACTUALIZA EL REGISTRO DEL AREA */
            $resu = $this->area_model->upd_area($idarea, $nom);
        } else {
            /* SE GUARDA EL REGISTRO DEL AREA */
            $resu = $this->area_model->add_area($nom);
        }
        $arr['mens'] = $idarea ;
        print json_encode($arr); 
    }

    /* SE ELIMINA EL REGISTRO DEL AREA */
    public function eliminar(){
        $idarea = $this->input->post('txt_idarea'); 
        $resu = $this->area_model->del_area($idarea);
        $arr['mens'] = $idarea ;
        print json_encode($arr); 

    }

}

?>