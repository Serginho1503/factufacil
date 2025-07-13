<?php

/*------------------------------------------------
  ARCHIVO: Utiles.php
  DESCRIPCION: Contiene los métodos varios.
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'helpers/ValidarIdentificacion.php');

class Utiles extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
    }

    public function validarIdentificacion(){
      $tipo = $this->input->post("tipo");
      $identificacion = $this->input->post("identificacion");
      $Validator = new ValidarIdentificacion();
      switch ($tipo) {
        case 'P':
          $resu = "1";
          break;
        case 'C':
          $resu = $Validator->validarCedula($identificacion);
          break;       
        default:
          $resu = $Validator->validarRucPersonaNatural($identificacion);
          if ($resu == ""){
            $resu = $Validator->validarRucSociedadPrivada($identificacion);
          }
          break;
      }
      if (substr($identificacion,0,10) == '9999999999') {$resu = "1";}
      $arr['resu'] = $resu;
      print json_encode($arr);
    }

}

?>