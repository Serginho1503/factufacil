<?php

/*------------------------------------------------
  ARCHIVO: Categoria.php
  DESCRIPCION: Contiene los métodos relacionados con la Categoria.
  FECHA DE CREACIÓN: 06/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Catgastos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Catgastos_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $idusu = $this->session->userdata("sess_id");
    //    $perfil = $this->Catgastos_model->perfil($idusu);
    //    $data["perfil"] = $perfil;
        $data["base_url"] = base_url();
        $data["content"] = "catgastos";
        $this->load->view("layout", $data);
    }

    public function agregar(){
        /* SE GUARDA EL REGISTRO DE LA CATEGORIA */
        $cat = $this->input->post('txt_cat'); 
        $idcat = $this->input->post('txt_idcat'); 
        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idcat != 0){
           /*  SE ACTUALIZA EL REGISTRO DEL USUARIO */
            $resu = $this->Catgastos_model->cat_upd($idcat, $cat);
        } else {
            /* SE GUARDA EL REGISTRO DEL USUARIO */
            $resu = $this->Catgastos_model->cat_add($cat);
        }
        
        $arr['mens'] = $cat ;
        print json_encode($arr); 
        

    }
    
    public function eliminar(){
        /* SE ELIMINA EL REGISTRO DE LA CATEGORIA */
        $idcat = $this->input->post('txt_idcat'); 
        $resu = $this->Catgastos_model->cat_del($idcat);
        $arr['mens'] = $idcat ;
        print json_encode($arr); 

    }

    public function add_cat(){
        
        $data["base_url"] = base_url();
        $this->load->view("cat_gas_add", $data);
    } 

    public function upd_catgas(){
        $id_cat = $this->session->userdata("tmp_catgas_id");
        $data["base_url"] = base_url();
        $cat_desc = $this->Catgastos_model->sel_upd_id($id_cat);
        $data["cat"] = $cat_desc;
        $this->load->view("cat_gas_add", $data);
    }

    public function del_cat(){
        $id_cat = $this->session->userdata("tmp_catgas_id");
        $data["base_url"] = base_url();
        $cat_desc = $this->Catgastos_model->sel_upd_id($id_cat);
        $data["cat"] = $cat_desc;
        $this->load->view("cat_gas_del", $data);
    }

    public function listadoDataCat() {

        $registro = $this->Catgastos_model->sel_cat();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->id_cat_gas.'\" class=\"btn btn-success btn-xs btn-grad cat_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_cat_gas.'\" class=\"btn btn-danger btn-xs btn-grad cat_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' .$row->id_cat_gas. '",
                      "nombre":"' .$row->nom_cat_gas. '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

     public function tmp_catgas() {
        $this->session->unset_userdata("tmp_catgas_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_catgas_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_catgas_id", $id);
        } else {
            $this->session->set_userdata("tmp_catgas_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }



}

?>