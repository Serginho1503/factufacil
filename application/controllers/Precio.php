<?php

/*------------------------------------------------
  ARCHIVO: Precio.php
  DESCRIPCION: Contiene los métodos relacionados con la Precio.
  FECHA DE CREACIÓN: 12/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Precio extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Precio_model");
        $this->load->Model("Parametros_model");

        $this->request = json_decode(file_get_contents('php://input'));
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $tp = $this->Parametros_model->tipo_precio();
        if($tp == 1){
            $data["base_url"] = base_url();
            $data["content"] = "precio";
            $this->load->view("layout", $data);
        }else{
            redirect('inicio','refresh');
        }

    }

    public function listadoDataPre() {
        
        $tabla = "";
        $registro = $this->Precio_model->precioslst();

        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->id_precios.'\" class=\"btn btn-success btn-xs btn-grad pre_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_precios.'\" class=\"btn btn-danger btn-xs btn-grad pre_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' .$row->id_precios. '",
                      "descripcion":"' .$row->desc_precios. '",
                      "estatus":"' .$row->esta_precios. '",
                      "ver":"'.$ver.'"},';
        }

        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function add_pre(){
        
        $data["base_url"] = base_url();
        $this->load->view("pre_add", $data);
    } 

    public function agregar(){
        /* SE GUARDA EL REGISTRO DEL PRECIO */
        $idpre = $this->input->post('txt_idpre'); 
        $desc  = $this->input->post('txt_pre'); 
        $est   = $this->input->post('cmb_est'); 
        $color   = $this->input->post('txt_color'); 
        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idpre != 0){
            /* SE ACTUALIZA EL REGISTRO DEL USUARIO */
            $resu = $this->Precio_model->pre_upd($idpre, $desc, $est, $color);
        } else {
            /* SE GUARDA EL REGISTRO DEL USUARIO */
            $resu = $this->Precio_model->pre_add($desc, $est, $color);
        }
        
        $arr['mens'] = $idpre ;
        print json_encode($arr); 
    }

     public function tmp_pre() {
        $this->session->unset_userdata("tmp_pre_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_pre_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_pre_id", $id);
        } else {
            $this->session->set_userdata("tmp_pre_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function upd_pre(){
        $id_pre = $this->session->userdata("tmp_pre_id");
        $data["base_url"] = base_url();
        $pre_desc = $this->Precio_model->sel_pre_id($id_pre);
        $data["pre"] = $pre_desc;
        $this->load->view("pre_add", $data);
    }

    public function del_pre(){
        $id_pre = $this->session->userdata("tmp_pre_id");
        $data["base_url"] = base_url();
        $pre_desc = $this->Precio_model->sel_pre_id($id_pre);
        $data["pre"] = $pre_desc;
        $this->load->view("pre_del", $data);
    }

    public function eliminar(){
        $idpre = $this->input->post('txt_idpre');  
        $resu = $this->Precio_model->pre_del($idpre);
        $arr['mens'] = $idpre ;
        print json_encode($arr); 

    }

    public function lst_porcientoprecioventa(){
       $registros = $this->Precio_model->lst_porcientoprecioventa();
       echo json_encode($registros);       
    }

   public function upd_porcientoprecioventa(){
        $listaprecios = $this->request->listaprecios;
        $this->Precio_model->upd_porcientoprecioventa($listaprecios);
        echo json_encode(1);        
     }

}

?>