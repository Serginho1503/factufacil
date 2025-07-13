<?php

/* ------------------------------------------------
  ARCHIVO: Categoria_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Categoria.
  FECHA DE CREACIÓN: 07/07/2017
 * 
  ------------------------------------------------ */

class Categoria_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* INSERTA EL REGISTRO DE LA CATEGORIA*/
    public function cat_add($cat, $cmenu){
        $query = $this->db->query("INSERT INTO categorias (cat_descripcion, menu)VALUES('$cat', '$cmenu');");
    }

    public function sel_upd_id($id_cat){
      $query = $this->db->query("SELECT cat_id, cat_descripcion, menu FROM categorias WHERE cat_id = $id_cat");
      $result = $query->result();
      return $result[0];
    }

    public function cat_upd($idcat, $cat, $cmenu){
      $query = $this->db->query("UPDATE categorias SET cat_descripcion = '$cat', menu = '$cmenu' WHERE cat_id = $idcat");
    }

    public function cat_del($idcat){
      $query = $this->db->query("DELETE FROM categorias WHERE cat_id = $idcat");
    }

    public function sel_cat(){
      $query = $this->db->query("SELECT cat_id, cat_descripcion FROM categorias");
      $result = $query->result();
      return $result;
    
    }

}
