<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* ------------------------------------------------
  ARCHIVO: errors.php
  DESCRIPCION: Contiene los métodos para gestionar las páginas de error.
  FECHA DE CREACIÓN: 27/06/2017
 * 
  ------------------------------------------------ */

class Errors extends CI_Controller {

    private $data = array();

    function __construct() {
        parent::__construct();
        $this->load->helper('html');
    }

    function error_404() {
        
        //llamamos a la vista que muestra el error 404 personalizado
        $this->load->view('errors/404');
    }

}

?>