<?php

/* ------------------------------------------------
  ARCHIVO: Clasificacion_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Clasificacion.
  FECHA DE CREACIÓN: 18/09/2017
 * 
  ------------------------------------------------ */

class Clasificacion_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* INSERTA EL REGISTRO DE LA CLASIFICACION */
    public function cla_add($cla){
        $query = $this->db->query("INSERT INTO clasificacion (nom_cla)VALUES('$cla');");
    }

    public function sel_upd_id($id_cla){
      $query = $this->db->query("SELECT id_cla, nom_cla FROM clasificacion WHERE id_cla = $id_cla");
      $result = $query->result();
      return $result[0];
    }

    public function cla_upd($idcla, $cla){
      $query = $this->db->query("UPDATE clasificacion SET nom_cla = '$cla' WHERE id_cla = $idcla");
    }

    public function cla_del($idcla){
      $query = $this->db->query("DELETE FROM clasificacion WHERE id_cla = $idcla");
    }

    public function sel_cla(){
      $query = $this->db->query("SELECT id_cla, nom_cla FROM clasificacion");
      $result = $query->result();
      return $result;
    
    }


}
