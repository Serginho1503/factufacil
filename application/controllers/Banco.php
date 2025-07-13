<?php
/*------------------------------------------------
  ARCHIVO: Banco.php
  DESCRIPCION: Contiene los métodos relacionados con la Banco.
  FECHA DE CREACIÓN: 28/11/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Banco extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Banco_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "banco_lst";
        $this->load->view("layout", $data);
    }

    public function listadoBanco() {
      $registro = $this->Banco_model->bancolst();
      $tabla = "";
      foreach ($registro as $row) {
        $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Editar Banco\" id=\"'.$row->id_banco.'\" class=\"btn btn-success btn-xs btn-grad edi_ban\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar Banco\" id=\"'.$row->id_banco.'\" class=\"btn btn-danger btn-xs btn-grad del_ban\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i></a>';
        $tabla.='{"id":"' . $row->id_banco . '",
                  "tipo":"' . $row->tipo . '",
                  "nombre":"' . $row->nombre . '",
                  "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    public function add_ban(){
      $tpban = $this->Banco_model->bancotipo();
      $data["tpban"] = $tpban;
      $data["base_url"] = base_url();
      $this->load->view("banco_add", $data);
    }     

    public function edi_ban(){
      $idban = $this->input->post("id");
      $ban = $this->Banco_model->selban($idban);
      $data["ban"] = $ban;      
      $tpban = $this->Banco_model->bancotipo();
      $data["tpban"] = $tpban;
      $data["base_url"] = base_url();
      $this->load->view("banco_add", $data);
    } 

    public function sav_ban(){
      $idban = $this->input->post("idban");
      $tipban = $this->input->post("tipban");
      $nomban = $this->input->post("nomban");
      if($idban == 0){
        $savban = $this->Banco_model->savban($tipban, $nomban); 
      }else{
        $updban = $this->Banco_model->updban($idban, $tipban, $nomban);
      }
      $arr = 1;
      print json_encode($arr);
    }

    public function del_ban(){
      $idban = $this->input->post("id");
      $delban = $this->Banco_model->delban($idban);
      $arr = 1;
      print json_encode($arr);      
    } 



}

?>