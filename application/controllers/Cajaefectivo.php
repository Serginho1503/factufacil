<?php

/*------------------------------------------------
  ARCHIVO: Cajaefectivo.php
  DESCRIPCION: Contiene los métodos relacionados con la Cajaefectivo.
  FECHA DE CREACIÓN: 23/05/2018
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Cajaefectivo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Cajaefectivo_model");
        $this->load->Model("Puntoemision_model");
        $this->load->Model("Sucursal_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $idusu = $this->session->userdata("sess_id");
        $data["base_url"] = base_url();
        $data["content"] = "cajaefectivo";
        $this->load->view("layout", $data);
    }

    public function listadoCajas() {
        $registro = $this->Cajaefectivo_model->sel_cajaefectivo();
        $tabla = "";
        foreach ($registro as $row) {
            if($row->activo == 1){ $estatus = 'Activa'; }else{ $estatus = 'Desabilitada'; }
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Editar Caja\" id=\"'.$row->id_caja.'\" class=\"btn btn-success btn-xs btn-grad edi_caja\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar Caja\" id=\"'.$row->id_caja.'\" class=\"btn btn-danger btn-xs btn-grad del_caja\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{  "sucursal":"' .$row->nom_sucursal. '",
                        "codigo":"' .$row->codigo. '",
                        "caja":"' .$row->nom_caja. '",
                        "estatus":"' .$estatus. '",
                        "ver":"'.$ver.'"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function add_cajaefectivo(){
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $data["sucursales"] = $sucursales;
        $data["base_url"] = base_url();
        $this->load->view("cajaefectivo_add", $data);
    } 

    public function upd_puntoemision_id(){
        $idsuc = $this->input->post('idsuc');
        $res = $this->Cajaefectivo_model->sel_puntoemision_id($idsuc);
        print json_encode($res);
    }

    public function tmp_cajaefectivo() {
        $this->session->unset_userdata("tmp_caja_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_caja_id", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_caja_id", $id); } 
        else { $this->session->set_userdata("tmp_caja_id", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function guarda_cajaefectivo(){
        $idcaja = $this->input->post('txt_idce'); 
        $ptoemision = $this->input->post('cmb_puntoemision'); 
        $caja = $this->input->post('txt_caja');
        $estatus = $this->input->post('cmb_estatus');
        if($idcaja != 0){
            $resu = $this->Cajaefectivo_model->cajaefectivo_actualiza($idcaja, $ptoemision, $caja, $estatus);
        } else {
            $resu = $this->Cajaefectivo_model->cajaefectivo_guarda($ptoemision, $caja, $estatus);
        }
        print json_encode($idcaja); 
    }

    public function del_cajaefectivo(){
        $id = $this->input->post('id'); 
        $resu = $this->Cajaefectivo_model->cajaefectivo_eliminar($id);
        $arr['mens'] = $resu;
        print json_encode($arr); 
    }

    public function edi_cajaefectivo(){
        $idcaja = $this->session->userdata("tmp_caja_id");
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $data["sucursales"] = $sucursales;
        $caja = $this->Cajaefectivo_model->sel_cajaefectivo_id($idcaja);
        $data["caja"] = $caja;

        $data["base_url"] = base_url();
        $this->load->view("cajaefectivo_add", $data);
    } 




}

?>