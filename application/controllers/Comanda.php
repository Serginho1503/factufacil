<?php
/*------------------------------------------------
  ARCHIVO: Comanda.php
  DESCRIPCION: Contiene los métodos relacionados con la Comanda.
  FECHA DE CREACIÓN: 13/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Comanda extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Comanda_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "comanda";
        $this->load->view("layout", $data);
    }
    /* CARGA DE DATOS AL DATATABLE */
    public function listadoDataComa() {
        $registro = $this->Comanda_model->lista_comanda();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Editar\" id=\"'.$row->id_comanda.'\" class=\"btn btn-success btn-xs btn-grad edit_com\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_comanda.'\" class=\"btn btn-danger btn-xs btn-grad eli_com\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' . $row->id_comanda . '",
                      "nombre":"' . $row->nom_comanda . '",
                      "impresora":"' .  addslashes($row->impresora) . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }


    /* ABRIR VENTANA PARA AGREGAR */
    public function add_comanda(){
        //$suc = $this->Comanda_model->lst_suc();
        //$data["suc"] = $suc;
        $data["base_url"] = base_url();
        $this->load->view("comanda_add", $data);
    } 

    /* SE GUARDA O SE MODIFICA EL REGISTRO DEL Comanda */
    public function guardar(){
        $idcom = $this->input->post('txt_idcom');
        $nomcom = $this->input->post('txt_nom');
        $impresora = addslashes($this->input->post('txt_imp'));
        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idcom != 0){
            /* SE ACTUALIZA EL REGISTRO DEL Comanda */
            $resu = $this->Comanda_model->com_upd($idcom, $nomcom, $impresora);
        } else {
            /* SE GUARDA EL REGISTRO DEL Comanda */
            $resu = $this->Comanda_model->com_add($nomcom, $impresora);
        }
        $arr['mens'] = $idcom ;
        print json_encode($arr); 
    }

    /* ABRIR VENTANA PARA MODIFICAR */
    public function upd_com(){
        $idcom = $this->session->userdata("tmp_com_id");
        $data["base_url"] = base_url();
        $com_det = $this->Comanda_model->sel_com_id($idcom);
        $data["com"] = $com_det;
        $this->load->view("comanda_add", $data);
    }

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_com() {
        $this->session->unset_userdata("tmp_com_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_com_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_com_id", $id);
        } else {
            $this->session->set_userdata("tmp_com_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }        
    
    /* ABRIR VENTANA PARA ELIMINAR */
    public function del_com(){
        $idcom = $this->session->userdata("tmp_com_id");
        $data["base_url"] = base_url();
        $com_det = $this->Comanda_model->sel_com_id($idcom);
        $data["com"] = $com_det;
        $this->load->view("comanda_del", $data);
    }

    /* SE ELIMINA EL REGISTRO SELECCIONADO */
    public function eliminar(){
        $idcom = $this->input->post('txt_idcom');  
        $resu = $this->Comanda_model->del_com($idcom);
        $arr['mens'] = $idcom ;
        print json_encode($arr); 

    }

}

?>