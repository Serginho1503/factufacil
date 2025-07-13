<?php

/* ------------------------------------------------
  ARCHIVO: Area_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Area. 
  FECHA DE CREACIÓN: 04/08/2017
 * 
  ------------------------------------------------ */

class Area_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* BUSQUEDA DEL AREA POR ID */
    public function sel_area_id($idarea){
      $query = $this->db->query("SELECT id_area, nom_area FROM area WHERE id_area = $idarea");
      $result = $query->result();
      return $result[0];
    }
    /* CARGAR AREAS */
    public function sel_area(){
      $query = $this->db->query("SELECT id_area, nom_area FROM area");
      $result = $query->result();
      return $result;
    }

    /* ACTUALIZAR NOMBRE DEL AREA POR ID */
    public function upd_area($idarea, $nom){
      $query = $this->db->query("call area_upd($idarea,'$nom');");
      $result = $query->result();
      return $result;
    }

    /* INSERTA EL REGISTRO DEL AREA */
    public function add_area($nom){
      $query = $this->db->query("call area_ins('$nom');");
      $result = $query->result();
      return $result;
    }

    /* ELIMINA EL REGISTRO DEL AREA */
    public function del_area($idarea){
      $query = $this->db->query("call area_del($idarea);");
      $result = $query->result();
      return $result;
    }

}
