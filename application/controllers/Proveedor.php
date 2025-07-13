<?php

/*------------------------------------------------
  ARCHIVO: Proveedor.php
  DESCRIPCION: Contiene los métodos relacionados con la Proveedor.
  FECHA DE CREACIÓN: 25/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Proveedor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("proveedor_model");
        $this->load->Model("contabilidad/Contab_categoria_model");

    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "proveedores";
        $this->load->view("layout", $data);
    }

    /* CARGA DE DATO AL DATATABLE */
    public function listadoDataProvee() {

        $registro=$this->proveedor_model->sel_prov();
        $tabla = "";

        foreach ($registro as $row) {    
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->id_proveedor.'\" class=\"btn btn-success btn-xs btn-grad edi_provee\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_proveedor.'\" class=\"btn btn-danger btn-xs btn-grad del_provee\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' . $row->id_proveedor . '",
                      "nombre":"' . addslashes($row->nom_proveedor) . '",
                      "razsoc":"' . addslashes($row->razon_social) . '",
                      "telf":"' . $row->telf_proveedor . '",            
                      "correo":"' . addslashes($row->correo_proveedor) . '",
                      "ciudad":"' . addslashes($row->ciudad_proveedor) . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }   

    /* ABRIR VENTANA PARA AGREGAR */
    public function add_provee(){
        $lstcatcontable = $this->Contab_categoria_model->lst_categoriaproveedor();
        $data["lstcatcontable"] = $lstcatcontable;
        $ident = $this->proveedor_model->identificacion();
        $data["ident"] = $ident;
        $data["base_url"] = base_url();
        $this->load->view("provee_add", $data);
    } 

    /* SE GUARDA O SE MODIFICA EL REGISTRO DEL CLIENTE */
    public function guardar(){
        $idprovee = $this->input->post('txt_idprovee');
        $tip_ide = trim($this->input->post('cmb_tip_ide'));
        $nro_ide = $this->input->post('txt_nro_ident');
        $nom = trim($this->input->post('txt_nom'));
        $razsoc = trim($this->input->post('txt_razsoc'));
        if($razsoc == ''){ $razsoc = NULL; }
        $correo = trim($this->input->post('txt_mail'));
        if($correo == ''){ $correo = NULL; }
        $telf = $this->input->post('txt_telf');
        if($telf == ''){ $telf = NULL; }
        $ciu = trim($this->input->post('txt_ciu'));
        if($ciu == ''){ $ciu = NULL; }
        $dir = trim($this->input->post('txt_dir'));
        if($dir == ''){ $dir = NULL; }
        $chk_rel = $this->input->post('chk_rel');
        if($chk_rel == 'on'){ $rel = 1; } else { $rel = 0; }
        $catcontable = trim($this->input->post('cmb_catcontable'));

        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idprovee != 0){
            /* SE ACTUALIZA EL REGISTRO DEL PROVEEDOR */
            $resu = $this->proveedor_model->provee_upd($idprovee, $tip_ide, $nro_ide, $nom, $razsoc, $correo, $telf, $ciu, 
                                                       $dir, $rel, $catcontable);
        } else {
            /* SE GUARDA EL REGISTRO DEL PROVEEDOR */
            $resu = $this->proveedor_model->provee_add($tip_ide, $nro_ide, $nom, $razsoc, $correo, $telf, $ciu, $dir, $rel,
                                                       $catcontable);
        }

        $arr['mens'] = $idprovee ;
        print json_encode($arr); 
    }

    /* ABRIR VENTANA PARA EDITAR PROVEEDOR */
    public function edi_provee(){
        $idprovee = $this->session->userdata("tmp_provee_id");
        $provee = $this->proveedor_model->sel_provee_id($idprovee);
        $ident = $this->proveedor_model->identificacion();
        $lstcatcontable = $this->Contab_categoria_model->lst_categoriaproveedor();
        $data["lstcatcontable"] = $lstcatcontable;
        $data["provee"] = $provee;
        $data["ident"] = $ident;
        $data["base_url"] = base_url();
        $this->load->view("provee_add", $data);
    }

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_provee() {
        $this->session->unset_userdata("tmp_provee_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_provee_id", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_provee_id", $id); } 
        else { $this->session->set_userdata("tmp_provee_id", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    } 

    /* ABRIR VENTANA PARA ELIMINAR PROVEEDOR */
    public function del_provee(){
        $idprovee = $this->session->userdata("tmp_provee_id");
        $provee = $this->proveedor_model->sel_provee_id($idprovee);
        $data["provee"] = $provee;
        $data["base_url"] = base_url();
        $this->load->view("provee_del", $data);
    }

   /* ELIMINAR PROVEEDOR DE LA BASE DE DATOS */
    public function eliminar(){
        $idprovee = $this->input->post('txt_idprovee');
        $del = $this->proveedor_model->provee_del($idprovee);
        $arr['mens'] = $idprovee;
        print json_encode($arr);

    }

}

?>