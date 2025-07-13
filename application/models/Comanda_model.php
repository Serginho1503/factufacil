<?php

/* ------------------------------------------------
  ARCHIVO: Comanda_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Comanda.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */

class Comanda_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function lista_comanda(){
      $sql = $this->db->query("SELECT id_comanda, nom_comanda, impresora FROM comanda");
      $res = $sql->result();
      return $res;
    }

    public function com_upd($idcom, $nomcom, $impresora){
      $sql_upd = $this->db->query("UPDATE comanda SET nom_comanda = '$nomcom', impresora = '$impresora' WHERE id_comanda = $idcom");
    }
   
    public function com_add($nomcom, $impresora){
      $sql_upd = $this->db->query("INSERT INTO comanda (nom_comanda, impresora) VALUES ('$nomcom', '$impresora')");
    }

    public function sel_com_id($idcom){
      $sql = $this->db->query("SELECT id_comanda, nom_comanda, impresora FROM comanda WHERE id_comanda = $idcom");
      $res = $sql->result();
      return $res[0];
    }

    public function del_com($idcom){
      $sql_upd = $this->db->query("DELETE FROM comanda WHERE id_comanda = $idcom");
    }


}
