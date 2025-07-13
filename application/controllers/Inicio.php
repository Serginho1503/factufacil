<?php

/* ------------------------------------------------
  ARCHIVO: Inicio.php
  DESCRIPCION: Contiene los métodos relacionados con la autenticación del usuario en la aplicación.
  FECHA DE CREACIÓN: 27/06/2017
 * 
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("usuario_model");
        $this->load->Model("Parametros_model");
    }

    public function index() {

        $idusu = $this->session->userdata("sess_id");
        $usudat = $this->usuario_model->usua_get_tod_log($idusu);
        $perfil = $usudat->perfil;
        $peddat = $this->Parametros_model->sel_pedidovista();
        $pedido = $peddat->valor;
        $data["base_url"] = base_url();
        /*
        $data["content"] = "inicio";
        $this->load->view("layout", $data);
        */
        if(($perfil != 2) || ($pedido == 0)){
            $data["content"] = "inicio";
            $this->load->view("layout", $data);
        }else{
            header("location: " . base_url() . "servicio");
        }       
    }

    // Imprime una imagen que esté en tipo de dato BLOB. Ejemplo: Foto de perfil de usuario

    public function mostrar_img() {
        header("Content-type: image/jpeg");
        // Verificar la imagen del Usuario
        $data_t = $this->session->userdata("sess_fot");
        if ($data_t == TRUE) {
            $image = pg_unescape_bytea($data_t);
            print $image;
        } else {
            $image = imagecreatefromjpeg(base_url() . "public/img/perfil.jpeg");
            print imagejpeg($image);
            imagedestroy($image);
        }
    }
 

}

?>