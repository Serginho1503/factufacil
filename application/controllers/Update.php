<?php
/*------------------------------------------------
  ARCHIVO: Update.php
  DESCRIPCION: Contiene los métodos relacionados con la actualizacion de Base de Datos.
  FECHA DE CREACIÓN: 10/11/2017

defined('BASEPATH') OR exit('No direct script access allowed');
*/
class Update extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
    /*    $this->load->Model("almacen_model"); */
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "update";
        $this->load->view("layout", $data);
    }

    public function actualizabase() {
        $res = $this->Update_model->actualizabase();
        $arr['res'] = $res;
        print json_encode($arr);
    }


}

?>