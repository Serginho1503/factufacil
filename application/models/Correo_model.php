<?php

/* ------------------------------------------------
  ARCHIVO: Correo_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Correo.
  FECHA DE CREACIÓN: 25/09/2017
 * 
  ------------------------------------------------ */

class Correo_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function sel_correo(){
      $query = $this->db->query("SELECT * FROM correo");
      $result = $query->result();
      return $result[0];
    }

    public function add_correo($smtp, $puerto, $user, $pwd){
      $query = $this->db->query("call correo_upd('$user', '$pwd', $puerto, '$smtp')");

    }

    public function env_correo(){
      $query = $this->db->query("SELECT *, (SELECT ema_emp FROM empresa LIMIT 1) AS empresa FROM correo");
      $result = $query->result();
      return $result[0];
    }
}
