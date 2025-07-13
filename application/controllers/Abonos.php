<?php
/*------------------------------------------------
  ARCHIVO: Almacen.php
  DESCRIPCION: Contiene los métodos relacionados con la Almacen.
  FECHA DE CREACIÓN: 13/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Almacen extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("almacen_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "almacen";
        $this->load->view("layout", $data);
    }
    /* CARGA DE DATOS AL DATATABLE */
    public function listadoDataAlm() {
        $registro = $this->almacen_model->sel_alm();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->almacen_id.'\" class=\"btn btn-success btn-xs btn-grad alm_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->almacen_id.'\" class=\"btn btn-danger btn-xs btn-grad alm_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' . $row->almacen_id . '",
                      "nombre":"' . $row->almacen_nombre . '",
                      "direccion":"' . $row->almacen_direccion . '",
                      "responsable":"' . $row->almacen_responsable . '",
                      "descripcion":"' . $row->almacen_descripcion . '",   
                      "sucursal":"' . $row->nom_sucursal . '",                                                               
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }


    /* ABRIR VENTANA PARA AGREGAR */
    public function add_alm(){
        $suc = $this->almacen_model->lst_suc();
        $data["suc"] = $suc;
        $data["base_url"] = base_url();
        $this->load->view("alm_add", $data);
    } 

    /* SE GUARDA O SE MODIFICA EL REGISTRO DEL ALMACEN */
    public function guardar(){
        $idalm = $this->input->post('txt_idalm');
        $nomalm = $this->input->post('txt_nom');
        $resalm = $this->input->post('txt_res');
        $diralm = $this->input->post('txt_dir');
        $desalm = $this->input->post('txt_des');
        $sucalm = $this->input->post('cmb_suc');
        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idalm != 0){
            /* SE ACTUALIZA EL REGISTRO DEL ALMACEN */
            $resu = $this->almacen_model->alm_upd($idalm, $nomalm, $resalm, $diralm, $desalm, $sucalm);
        } else {
            /* SE GUARDA EL REGISTRO DEL ALMACEN */
            $resu = $this->almacen_model->alm_add($nomalm, $resalm, $diralm, $desalm, $sucalm);
        }
        
        $arr['mens'] = $idalm ;
        print json_encode($arr); 
    }

    /* ABRIR VENTANA PARA MODIFICAR */
    public function upd_alm(){
        $idalm = $this->session->userdata("tmp_alm_id");
        $data["base_url"] = base_url();
        $alm_det = $this->almacen_model->sel_alm_id($idalm);
        $suc = $this->almacen_model->lst_suc();
        $data["suc"] = $suc;
        $data["alm"] = $alm_det;
        $this->load->view("alm_add", $data);
    }

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_alm() {
        $this->session->unset_userdata("tmp_alm_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_alm_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_alm_id", $id);
        } else {
            $this->session->set_userdata("tmp_alm_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }        
    
    /* ABRIR VENTANA PARA ELIMINAR */
    public function del_alm(){
        $idalm = $this->session->userdata("tmp_alm_id");
        $data["base_url"] = base_url();
        $alm_det = $this->almacen_model->sel_alm_id($idalm);
        $data["alm"] = $alm_det;
        $this->load->view("alm_del", $data);
    }
    /* SE ELIMINA EL REGISTRO SELECCIONADO */
    public function eliminar(){
        $idalm = $this->input->post('txt_idalm');  
        $resu = $this->almacen_model->alm_del($idalm);
        $arr['mens'] = $idalm ;
        print json_encode($arr); 

    }

}

?>