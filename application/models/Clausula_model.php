<?php

/* ------------------------------------------------
  ARCHIVO: Area_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Area. 
  FECHA DE CREACIÓN: 04/08/2017
 * 
  ------------------------------------------------ */

class Clausula_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function upd_clausula($clausulas){
      $this->db->query("UPDATE clausula SET desc_clausula = '$clausulas'");
    }

    public function sel_clausulas(){
      $sql = $this->db->query("SELECT desc_clausula FROM clausula");
      $res = $sql->result();
      return $res[0]->desc_clausula;
    }


}
