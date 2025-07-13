<?php

/*------------------------------------------------
  ARCHIVO: Categoria.php
  DESCRIPCION: Contiene los métodos relacionados con la Categoria.
  FECHA DE CREACIÓN: 06/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Categoria extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("categoria_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $idusu = $this->session->userdata("sess_id");
        $data["base_url"] = base_url();
        $data["content"] = "categoria";
        $this->load->view("layout", $data);
    }

    public function agregar(){
        /* SE GUARDA EL REGISTRO DE LA CATEGORIA */
        $cat = $this->input->post('txt_cat'); 
        $idcat = $this->input->post('txt_idcat'); 
        $menu = $this->input->post('chkmenu');
        if($menu == 'on'){ $cmenu = 1; } else { $cmenu = 0; }
        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idcat != 0){
            /* SE ACTUALIZA EL REGISTRO DEL USUARIO */
            $resu = $this->categoria_model->cat_upd($idcat, $cat, $cmenu);
        } else {
            /* SE GUARDA EL REGISTRO DEL USUARIO */
            $resu = $this->categoria_model->cat_add($cat, $cmenu);
        }
        
        $arr['mens'] = $cat ;
        print json_encode($arr); 
        

    }
    
    public function eliminar(){
        /* SE ELIMINA EL REGISTRO DE LA CATEGORIA */
        $idcat = $this->input->post('txt_idcat'); 
        $resu = $this->categoria_model->cat_del($idcat);
        $arr['mens'] = $idcat ;
        print json_encode($arr); 

    }

    public function add_cat(){
        
        $data["base_url"] = base_url();
        $this->load->view("cat_add", $data);
    } 

    public function upd_cat(){
        $id_cat = $this->session->userdata("tmp_cat_id");

        //print $id; die;
        $data["base_url"] = base_url();
        $cat_desc = $this->categoria_model->sel_upd_id($id_cat);
        $data["cat"] = $cat_desc;
        $this->load->view("cat_add", $data);
    }
    public function del_cat(){
        $id_cat = $this->session->userdata("tmp_cat_id");
        $data["base_url"] = base_url();
        $cat_desc = $this->categoria_model->sel_upd_id($id_cat);
        $data["cat"] = $cat_desc;
        $this->load->view("cat_del", $data);
    }
    public function listadoDataCat() {

        $registro = $this->categoria_model->sel_cat();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->cat_id.'\" class=\"btn btn-success btn-xs btn-grad cat_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->cat_id.'\" class=\"btn btn-danger btn-xs btn-grad cat_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' .$row->cat_id. '",
                      "nombre":"' .$row->cat_descripcion. '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

     public function tmp_cat() {
        $this->session->unset_userdata("tmp_cat_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_cat_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_cat_id", $id);
        } else {
            $this->session->set_userdata("tmp_cat_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }



}

?>