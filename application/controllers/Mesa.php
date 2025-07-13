<?php

/*------------------------------------------------
  ARCHIVO: Mesa.php
  DESCRIPCION: Contiene los métodos relacionados con la Mesa.
  FECHA DE CREACIÓN: 04/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Mesa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("mesa_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "mesa";
        $this->load->view("layout", $data);
    }

    /* CARGA EL DATATABLE (LISTADO) */
    public function listadoDataMesa() {
        $registro = $this->mesa_model->sel_mesa();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->id_mesa.'\" class=\"btn btn-success btn-xs btn-grad edi_mesa\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_mesa.'\" class=\"btn btn-danger btn-xs btn-grad del_mesa\"><i class=\"fa fa-trash-o\"></i></a></div>';
                    
            $tabla.='{"id":"' . $row->id_mesa . '",
                      "nombre":"' . $row->nom_mesa . '",
                      "capacidad":"' . $row->capacidad . '",
                      "area":"' . $row->nom_area . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    /* LEVANTAR VENTANA PARA AGREGAR DATOS */
    public function add_mesa(){
        $area = $this->mesa_model->sel_area();
        $imp = $this->mesa_model->lst_impresora();
        $data["area"] = $area;
        $data["imp"] = $imp;
        $data["base_url"] = base_url();
        $this->load->view("mesa_add", $data);
    }    

    /* LEVANTAR VENTANA PARA MODIFICAR DATOS */
    public function upd_mesa(){
        $idmesa = $this->session->userdata("tmp_mesa");
        $mesa = $this->mesa_model->sel_mesa_id($idmesa);
        $area = $this->mesa_model->sel_area();
        $imp = $this->mesa_model->lst_impresora();
        $data["imp"] = $imp;        
        $data["mesa"] = $mesa;
        $data["area"] = $area;
        $data["base_url"] = base_url();
        $this->load->view("mesa_add", $data);
    }  

    /* LEVANTAR VENTANA PARA ELIMINAR DATOS */
    public function del_mesa(){
        $idmesa = $this->session->userdata("tmp_mesa");
        $mesa = $this->mesa_model->sel_mesa_id($idmesa);
        $data["mesa"] = $mesa;
        $data["base_url"] = base_url();
        $this->load->view("mesa_del", $data);
    }

    /* FUNCION DE VARIABLE DE SESION */    
     public function tmp_mesa() {
        $this->session->unset_userdata("tmp_mesa"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_mesa", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_mesa", $id); } 
        else { $this->session->set_userdata("tmp_mesa", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    }
    
    /* SE GUARDA O MODIFICA EL REGISTRO DEL mesa */
    public function guardar(){
        $idmesa = $this->input->post('txt_idmesa'); 
        $nom = $this->input->post('txt_nommesa');
        $area = $this->input->post('cmb_area');
        $cap = $this->input->post("txt_capacidad");
        $imp = $this->input->post("cmb_imp");
        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idmesa != 0){
            /* SE ACTUALIZA EL REGISTRO DEL mesa */
            $resu = $this->mesa_model->upd_mesa($idmesa, $nom, $area, $cap, $imp);
        } else {
            /* SE GUARDA EL REGISTRO DEL mesa */
            $resu = $this->mesa_model->add_mesa($nom, $area, $cap, $imp);
        }
        $arr['mens'] = $idmesa ;
        print json_encode($arr); 
    }

    /* SE ELIMINA EL REGISTRO DEL mesa */
    public function eliminar(){
        $idmesa = $this->input->post('txt_idmesa'); 
        $resu = $this->mesa_model->del_mesa($idmesa);
        $arr['mens'] = $idmesa ;
        print json_encode($arr); 

    }

}

?>