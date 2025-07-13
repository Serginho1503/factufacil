<?php

/* ------------------------------------------------
  ARCHIVO: Almacen_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Almacen.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */

class Rol_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function rol_modulo($id){
      $query = $this->db->query(" SELECT m.desc_mod_det, a.evento, a.accion 
                                  FROM modulos_detalles m
                                  INNER JOIN acceso a ON a.id_mod_det = m.id_mod_det
                                  WHERE id_usu = $id AND a.evento = 'ver' ");
      $r = $query->result();
      return $r;
    }
}
