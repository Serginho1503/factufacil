<?php

/*------------------------------------------------
  ARCHIVO: Puntoemision.php
  DESCRIPCION: Contiene los métodos relacionados con la Puntoemision.
  FECHA DE CREACIÓN: 19/03/2018
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Puntoemision extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Puntoemision_model");
        $this->load->Model("Sucursal_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $idusu = $this->session->userdata("sess_id");
        $data["base_url"] = base_url();
        $data["content"] = "puntoemision";
        $this->load->view("layout", $data);
    }

    public function tmp_puntoemision() {
        $this->session->unset_userdata("tmp_ptoemi_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_ptoemi_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_ptoemi_id", $id);
        } else {
            $this->session->set_userdata("tmp_ptoemi_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function upd_puntoemision(){
        $id = $this->session->userdata("tmp_ptoemi_id");
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $data["sucursales"] = $sucursales;
        $data["base_url"] = base_url();
        $obj = $this->Puntoemision_model->sel_puntoemision_id($id);
        $data["obj"] = $obj;
        $this->load->view("puntoemision_add", $data);
    }

    public function agregar(){
        $id = $this->input->post('txt_id'); 
        $codestab = $this->input->post('txt_codestab'); 
        $codptoemi = $this->input->post('txt_codptoemi'); 
        $numfactura = $this->input->post('txt_numfactura');
        $numnotaventa = $this->input->post('txt_numnota');
        $numcompago = $this->input->post('txt_numcompago');
        $numnotacredito = $this->input->post('txt_numnotacredito');
        $retencioncompra = $this->input->post('txt_retencioncompra');       
        $numguia = $this->input->post('txt_numguia');       
        $sucursal = $this->input->post('cmb_sucursal');
        $ambiente_factura = $this->input->post('ambiente_factura');
        $ambiente_retencion = $this->input->post('ambiente_retencion');
        $ambiente_notacredito = $this->input->post('ambiente_notacredito');
        $ambiente_guia = $this->input->post('ambiente_guia');
        $activo = $this->input->post('chkactivo');
        if($activo == 'on'){ $activo = 1; } else { $activo = 0; }
        $enviosrifactura = $this->input->post('chk_enviosrifactura');
        if($enviosrifactura == 'on'){ $enviosrifactura = 1; } else { $enviosrifactura = 0; }
        if($id != 0){
            $resu = $this->Puntoemision_model->upd_puntoemision($id, $sucursal, $codestab, $codptoemi, $numfactura, 
                                                                $numnotaventa, $numcompago, $numnotacredito, $retencioncompra, 
                                                                $activo, $numguia, $ambiente_factura, $ambiente_retencion,
                                                                $ambiente_notacredito, $ambiente_guia,$enviosrifactura);
        } else {
            $resu = $this->Puntoemision_model->add_puntoemision($sucursal, $codestab, $codptoemi, $numfactura, $numnotaventa, 
                                                                $numcompago, $numnotacredito, $retencioncompra, $numguia, 
                                                                $ambiente_factura, $ambiente_retencion,
                                                                $ambiente_notacredito, $ambiente_guia,$enviosrifactura);
        }
        $arr['mens'] = $id;
        print json_encode($arr); 
    }

    public function add_puntoemision(){
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $data["sucursales"] = $sucursales;
        $data["base_url"] = base_url();
        $this->load->view("puntoemision_add", $data);
    } 

    public function del_puntoemision(){
        $id = $this->input->post('id'); 
        $resu = $this->Puntoemision_model->del_puntoemision($id);
        $arr['mens'] = $resu;
        print json_encode($arr); 
    }


    public function listadoPuntos() {
        $registro = $this->Puntoemision_model->sel_puntoemision();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Editar Punto Emision\" id=\"'.$row->id_puntoemision.'\" class=\"btn btn-success btn-xs btn-grad ret_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_puntoemision.'\" class=\"btn btn-danger btn-xs btn-grad ret_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{  "id":"' .$row->id_puntoemision. '",
                        "sucursal":"' .$row->nom_sucursal. '",
                        "cod_establecimiento":"' .$row->cod_establecimiento. '",
                        "cod_puntoemision":"' .$row->cod_puntoemision. '",
                        "consecutivo_factura":"' .$row->consecutivo_factura. '",
                        "consecutivo_notaventa":"' .$row->consecutivo_notaventa. '",
                        "consecutivo_comprobpago":"' .$row->consecutivo_comprobpago. '",
                        "consecutivo_notacredito":"' .$row->consecutivo_notacredito. '",
                        "consecutivo_retencion":"' .$row->consecutivo_retencioncompra. '",
                        "ver":"'.$ver.'"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }
}

?>