<?php
/*------------------------------------------------
  ARCHIVO: Tarjeta.php
  DESCRIPCION: Contiene los métodos relacionados con la Tarjeta.
  FECHA DE CREACIÓN: 28/11/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Tarjeta extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Tarjeta_model");
    }

    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "tarjeta_lst";
        $this->load->view("layout", $data);
    }

    public function listadoTarjeta() {
      $registro = $this->Tarjeta_model->tarjetalst();
      $tabla = "";
      foreach ($registro as $row) {
        $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Editar Tarjeta\" id=\"'.$row->id_tarjeta.'\" class=\"btn btn-success btn-xs btn-grad edi_tar\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar Tarjeta\" id=\"'.$row->id_tarjeta.'\" class=\"btn btn-danger btn-xs btn-grad del_tar\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i></a>';
        $tabla.='{"id":"' . $row->id_tarjeta . '",
                  "nombre":"' . $row->nombre . '",
                  "debito":"' . number_format($row->comision_debito, 2) . '",
                  "credito":"' . number_format($row->comision_credito, 2) . '",
                  "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function add_tar(){
      $data["base_url"] = base_url();
      $this->load->view("tarjeta_add", $data);
    }     

    public function edi_tar(){
      $idtar = $this->input->post("id");
      $tar = $this->Tarjeta_model->seltar($idtar);
      $data["tar"] = $tar;      
      $data["base_url"] = base_url();
      $this->load->view("tarjeta_add", $data);
    } 

    public function sav_tar(){
      $idtar = $this->input->post("idtar");
      $nomtar = $this->input->post("nomtar");
      $comdebito = $this->input->post("comdebito");
      $comcredito = $this->input->post("comcredito");
      if($idtar == 0){
        $savtar = $this->Tarjeta_model->savtar($nomtar, $comdebito, $comcredito); 
      }else{
        $updtar = $this->Tarjeta_model->updtar($idtar, $nomtar, $comdebito, $comcredito);
      }
      $arr = 1;
      print json_encode($arr);
    }

    public function del_tar(){
      $idtar = $this->input->post("id");
      $deltar = $this->Tarjeta_model->deltar($idtar);
      $arr = 1;
      print json_encode($arr);      
    } 



}

?>