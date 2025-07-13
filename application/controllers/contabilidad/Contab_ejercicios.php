<?php

/*------------------------------------------------
  ARCHIVO: Contab_ejercicios.php
  DESCRIPCION: Contiene los métodos relacionados con Ejercicios.
  
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Contab_ejercicios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("contabilidad/Contab_ejercicios_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_ejercicios";
        $this->load->view("layout", $data);
    }

    /*Ejercicio*/
    public function tmp_ejercicio() {
        $this->session->unset_userdata("tmp_ejercicio_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_ejercicio_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_ejercicio_id", $id);
        } else {
            $this->session->set_userdata("tmp_ejercicio_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function listadoEjercicios() {
        $registro = $this->Contab_ejercicios_model->sel_ejercicios();
        $tabla = "";
        foreach ($registro as $row) {
            $inicio = str_replace('-', '/', $row->inicio); $inicio = date("d/m/Y", strtotime($inicio));
            $fin = str_replace('-', '/', $row->fin); $fin = date("d/m/Y", strtotime($fin));

            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Editar\" id=\"'.$row->id.'\" class=\"btn btn-success btn-xs btn-grad edi_ejer\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id.'\" class=\"btn btn-danger btn-xs btn-grad del_ejer\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{  "id":"' .$row->id. '",
                        "inicio":"' .$inicio. '",
                        "fin":"' .$fin. '",
                        "descripcion":"' .$row->descripcion. '",
                        "ver":"'.$ver.'"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function add_ejercicio(){
        $data["base_url"] = base_url();
        $ultimafecha = $this->Contab_ejercicios_model->sel_ejercicio_ultimafecha();
        $nuevoinicio = strtotime ( '+1 day' , strtotime ( $ultimafecha ) );
        $nuevoinicio = date ( 'Y-m-d' , $nuevoinicio );
        $arrfecha = explode("-", $nuevoinicio);
        $nuevofin = $arrfecha[0].'-12-31';
        $data["nuevoinicio"] = $nuevoinicio;
        $data["nuevofin"] = $nuevofin;
        $this->load->view("contabilidad/contab_ejercicios_add", $data);
    } 

    public function upd_ejercicio(){
        $id = $this->session->userdata("tmp_ejercicio_id");
        $data["base_url"] = base_url();
        $obj = $this->Contab_ejercicios_model->sel_ejercicio_id($id);
        $data["obj"] = $obj;
        $this->load->view("contabilidad/contab_ejercicios_add", $data);
    }

    public function guardar(){
        $id = $this->input->post('id'); 
        $fec = $this->input->post('inicio');
        $fec = str_replace('/', '-', $fec); 
        $inicio = date("Y-m-d", strtotime($fec));
        $fec = $this->input->post('fin');
        $fec = str_replace('/', '-', $fec); 
        $fin = date("Y-m-d", strtotime($fec));
        $descripcion = $this->input->post('descripcion');
        if($id != 0){
            $resu = $this->Contab_ejercicios_model->upd_ejercicio($id, $inicio, $fin, $descripcion);
        } else {
            $resu = $this->Contab_ejercicios_model->add_ejercicio($inicio, $fin, $descripcion);
        }
        $arr['mens'] = $id;
        print json_encode($arr); 
    }

    public function del_ejercicio(){
        $id = $this->input->post('id'); 
        $resu = $this->Contab_ejercicios_model->del_ejercicio($id);
        $arr['mens'] = $resu;
        print json_encode($arr); 
    }

    public function fechas_enotroejercicio(){
        $id = $this->input->post('id'); 
        $fec = $this->input->post('inicio'); 
        $fec = str_replace('/', '-', $fec); 
        $inicio = date("Y-m-d", strtotime($fec));
        $fec = $this->input->post('fin'); 
        $fec = str_replace('/', '-', $fec); 
        $fin = date("Y-m-d", strtotime($fec));
        //print_r("id ".$id." inicio ".$inicio." fin ".$fin);die;
        $resu = $this->Contab_ejercicios_model->fechas_enotroejercicio($id, $inicio, $fin);

        $arr['resu'] = $resu;
        print json_encode($arr); 
    }
    
}

?>