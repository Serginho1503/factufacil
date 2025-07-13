<?php

/* ------------------------------------------------
  ARCHIVO: Catgastos_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Catgastos_model.
  FECHA DE CREACIÓN: 07/07/2017
 * 
  ------------------------------------------------ */

class Catgastos_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* INSERTA EL REGISTRO DE LA CATEGORIA */
    public function cat_add($cat){
        $query = $this->db->query("INSERT INTO gastos_categorias (nom_cat_gas) VALUES ('$cat');");
    }

    public function sel_upd_id($id_cat){
      $query = $this->db->query("SELECT id_cat_gas, nom_cat_gas FROM gastos_categorias WHERE id_cat_gas = $id_cat");
      $result = $query->result();
      return $result[0];
    }

   
    public function cat_upd($idcat, $cat){
      $query = $this->db->query("UPDATE gastos_categorias SET nom_cat_gas = '$cat' WHERE id_cat_gas = $idcat");
    }

   
    public function cat_del($idcat){
      $query = $this->db->query("DELETE FROM gastos_categorias WHERE id_cat_gas = $idcat");
    }

    
    public function sel_cat(){
      $query = $this->db->query("SELECT id_cat_gas, nom_cat_gas FROM gastos_categorias");
      $result = $query->result();
      return $result;
    
    }
 

    public function categorialst(){
      $query = $this->db->query("SELECT id_cat_gas, nom_cat_gas FROM gastos_categorias");
      $result = $query->result();
      return $result;
    }

}
