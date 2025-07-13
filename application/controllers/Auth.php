<?php

/* ------------------------------------------------
  ARCHIVO: Auth.php
  DESCRIPCION: Contiene los métodos relacionados con la autenticación del usuario en la aplicación.
  FECHA DE CREACIÓN: 04/12/2016
 * 
  ------------------------------------------------ */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth extends CI_Controller {
    /*
     * El constructor de este controlador funciona de manera diferente al resto.
     * Si la sesión es válida, no mostrará la pantalla de inicio de sesión,
     * sino que redirigirá al controlador inicio
     */

    public function __construct() {
        parent::__construct();

        $this->load->Model("Update_model");
        $this->Update_model->actualizabase();

        $this->load->Model("contabilidad/Contab_update_model");
        $this->Contab_update_model->actualizabase();

        $this->load->Model("petshop/Petshop_update_model");
        $this->Petshop_update_model->actualizabase();

        if ($this->auth_library->sess_validate() == TRUE) {
            header("location: " . base_url() . "inicio");
        }
    }

    public function index() {
        $data["base_url"] = base_url();
        $this->load->view("auth", $data);
    }

    //Este método autentica al usuario
    public function login() {
        $logi = $this->input->post('logi');
        $pass = $this->input->post('pass');
        $usua = $this->usuario_model->usua_get_id($logi, $pass);
        $condicion = $usua->val;

        if($condicion == 1){
            $this->session->set_userdata('sess_id', $usua->id_usu);
            $this->session->set_userdata('sess_na', $usua->nom_usu . " " . $usua->ape_usu);
            $this->session->set_userdata('sess_log', $usua->log_usu);
            $resu = 1;                
        } elseif ($condicion == 0) {
            $resu = 0;
        }elseif ($condicion == 999999999) {
            $resu = 2;
        }

       // $arr['data'] = $resu;
        print json_encode($resu);
    }
    
    // Recuperar datos de acceso
    public function recovery() {
        $data["base_url"] = base_url();
        $this->load->view("auth_recovery", $data);
    }

    //Este método cierra la sesión
    public function logout() {
        $this->session->sess_destroy();
        header("location: " . base_url() . "auth");
    }


}

?>