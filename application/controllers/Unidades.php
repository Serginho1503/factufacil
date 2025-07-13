<?php

/*------------------------------------------------
  ARCHIVO: Unidades de Medida.php
  DESCRIPCION: Contiene los métodos relacionados con la unidades de medida.
  FECHA DE CREACIÓN: 06/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Unidades extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("unidades_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "unidades";
        $this->load->view("layout", $data);
    }

    public function agregar(){
        /* SE GUARDA EL REGISTRO DE LA UNIDAD DE MEDIDA */
        $id_uni = $this->input->post('txt_iduni');
        $desc = $this->input->post('txt_uni');
        $nomc = $this->input->post('txt_nom_cor');
        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($id_uni != 0){
            /* SE ACTUALIZA EL REGISTRO DE LA UNIDAD DE MEDIDA */
            $resu = $this->unidades_model->uni_upd($id_uni, $desc, $nomc);
        } else {
            /* SE GUARDA EL REGISTRO DE LA UNIDAD DE MEDIDA */
            $resu = $this->unidades_model->uni_add($desc, $nomc);
        }
        
        $arr['mens'] = $id_uni;
        print json_encode($arr); 
        

    }
    
    public function eliminar(){
        /* SE ELIMINA EL REGISTRO DE LA UNIDAD DE MEDIDA */
        $id_uni = $this->input->post('txt_iduni'); 
        $resu = $this->unidades_model->uni_del($id_uni);
        $arr['mens'] = $id_uni ;
        print json_encode($arr); 

    }

    public function add_uni(){
        
        $data["base_url"] = base_url();
        $this->load->view("uni_add", $data);
    } 

    public function upd_uni(){
        $id_uni = $this->session->userdata("tmp_uni_id");
        $data["base_url"] = base_url();
        $uni_desc = $this->unidades_model->sel_upd_id($id_uni);
        $data["uni"] = $uni_desc;
        $this->load->view("uni_add", $data);
    }

    public function del_uni(){
        $id_uni = $this->session->userdata("tmp_uni_id");
        $data["base_url"] = base_url();
        $uni_desc = $this->unidades_model->sel_upd_id($id_uni);
        $data["uni"] = $uni_desc;
        $this->load->view("uni_del", $data);
    }

    public function listadoDataUni() {

        $registro = $this->unidades_model->sel_unidad();
        $tabla = "";

        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Añadir Factor de Conversión\" id=\"'. $row->id .'\" class=\"btn btn-warning btn-xs btn-grad uni_conv\"><i class=\"fa fa-plus\"></i></a> <a href=\"#\" title=\"Ver\" id=\"'. $row->id .'\" class=\"btn btn-success btn-xs btn-grad uni_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'. $row->id .'\" class=\"btn btn-danger btn-xs btn-grad uni_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' . $row->id . '",
                      "descripcion":"' . $row->descripcion . '",
                      "nombre":"' . $row->nombrecorto . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }
    public function listadoDataUni1() {

        $conexion = mysqli_connect(MYSERVER, MYUSER, MYPWD, MYDB);  
        $consulta = "SELECT id, descripcion, nombrecorto FROM unidadmedida";
        $registro = mysqli_query($conexion, $consulta);
        
        $i = 0;
        $tabla = "";

        while ($row = mysqli_fetch_array($registro)) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Añadir Factor de Conversión\" id=\"'.$row['id'].'\" class=\"btn btn-warning btn-xs btn-grad uni_conv\"><i class=\"fa fa-plus\"></i></a> <a href=\"#\" title=\"Ver\" id=\"'.$row['id'].'\" class=\"btn btn-success btn-xs btn-grad uni_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row['id'].'\" class=\"btn btn-danger btn-xs btn-grad uni_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' . $row['id'] . '",
                      "descripcion":"' . $row['descripcion'] . '",
                      "nombre":"' . $row['nombrecorto'] . '",
                      "ver":"'.$ver.'"},';
            $i++;
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    public function tmp_uni() {
        $this->session->unset_userdata("tmp_uni_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_uni_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_uni_id", $id);
        } else {
            $this->session->set_userdata("tmp_uni_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function tmp_fact() {
        $this->session->unset_userdata("tmp_fact_id"); 
        $this->session->unset_userdata("tmp_fact_uni"); 
        $id = $this->input->post("id");
        $uni = $this->input->post("uni");

        $this->session->set_userdata("tmp_fact_id", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_fact_id", $id); } 
        else { $this->session->set_userdata("tmp_fact_id", NULL); }

        $this->session->set_userdata("tmp_fact_uni", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_fact_uni", $uni); } 
        else { $this->session->set_userdata("tmp_fact_uni", NULL); }

        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function uni_conv(){
        $id_uni = $this->session->userdata("tmp_uni_id");
        $data["base_url"] = base_url();
        $uni_desc = $this->unidades_model->sel_upd_id($id_uni);
        $fact_conv_lst = $this->unidades_model->fact_conv_lst($id_uni);
        $uni_lst = $this->unidades_model->lst_uni($id_uni);
        $data["uni_lst"] = $uni_lst;
        $data["fcl"] = $fact_conv_lst;
        $data["uni"] = $uni_desc;
        $data["content"] = "uni_conv";
        $this->load->view("layout", $data);

    }

/*  FACTOR DE CONVERSION */
    public function add_fac_conv(){
        $id_uni = $this->session->userdata("tmp_fact_id");
        $data["base_url"] = base_url();
        $uni_lst = $this->unidades_model->lst_uni($id_uni);
        $data["uni_lst"] = $uni_lst;
        $data["uni"] = $id_uni;
        $this->load->view("uni_fact_conv", $data);
    }

    public function edi_fac_conv(){
        $id = $this->session->userdata("tmp_fact_id");
        $uni = $this->session->userdata("tmp_fact_uni");
        $var_uni = $this->unidades_model->sel_uni_conv($id, $uni);
        $uni_lst = $this->unidades_model->lst_uni($id);
        $data["base_url"] = base_url();
        $data["uni_lst"] = $uni_lst;
        $data["fact_edi"] = $var_uni;
        $this->load->view("uni_fact_conv", $data);
    }

    public function gua_fac_conv(){
        /* SE GUARDA EL REGISTRO DE LA UNIDAD DE MEDIDA */
        $id_uni = $this->input->post('txt_iduni');
        $id_fact = $this->input->post('txt_idfact');
        $uni_conv = $this->input->post('cmb_uni');
        $cant = $this->input->post('txt_cant');
        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($id_fact != 0){
            /* SE ACTUALIZA EL REGISTRO DE LA UNIDAD DE MEDIDA */
            $var = $this->unidades_model->upd_fact_conv($id_uni, $id_fact, $uni_conv, $cant);
        } else {
            /* SE GUARDA EL REGISTRO DE LA UNIDAD DE MEDIDA */
            $var = $this->unidades_model->add_fact_conv($id_uni, $uni_conv, $cant);
        }        
        
        $arr['mens'] = $id_uni;
        print json_encode($arr); 
    }     

    public function del_fac_conv(){
        /* MOSTRAR VENTANA EMERGENTE PARA MOSTRAR DATOS A ELIMINAR */
        $id = $this->session->userdata("tmp_fact_id");
        $uni = $this->session->userdata("tmp_fact_uni");
        $var_uni = $this->unidades_model->sel_uni_conv($id, $uni);
        $data["base_url"] = base_url();
        $data["uni"] = $var_uni;
        $this->load->view("uni_fact_conv_del", $data);
    }

    public function del_fac(){
        /*  ELIMINAR EL FACTOR DE CONVERISON DE LA UNIDAD DE MEDIDA */
        $id = $this->input->post('txt_iduni');
        $uni = $this->input->post('txt_idfact');
        $eli_fac = $this->unidades_model->del_fact_conv($id, $uni);
        $arr['mens'] = $eli_fac ;
        print json_encode($arr);
       
    }



}

?>