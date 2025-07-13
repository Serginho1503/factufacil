<?php

/* ------------------------------------------------
  ARCHIVO: Banco_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Banco.
  FECHA DE CREACIÓN: 28/11/2017
 * 
  ------------------------------------------------ */

class Banco_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function bancolst(){
    	$sql = $this->db->query(" SELECT b.id_banco, tp.nombre as tipo, b.nombre 
                                FROM bancos b
                                INNER JOIN tipobanco tp ON tp.id = b.id_tipo
                                ORDER BY tp.id, b.nombre ASC");
    	$resu = $sql->result();
    	return $resu;
    }

    public function bancotipo(){
      $sql = $this->db->query("SELECT id, nombre FROM tipobanco");
      $resu = $sql->result();
      return $resu;
    }

    public function selban($idban){
      $sql = $this->db->query("SELECT id_banco, tipo, nombre, id_tipo FROM bancos WHERE id_banco = $idban ");
      $resu = $sql->result();
      return $resu[0];
    }    

    public function savban($tipban, $nomban){
      $sql = $this->db->query("INSERT INTO bancos (nombre, id_tipo) VALUES ('$nomban', $tipban) ");
    } 

    public function updban($idban, $tipban, $nomban){
      $sql = $this->db->query("UPDATE bancos SET nombre = '$nomban', id_tipo = $tipban WHERE id_banco = $idban ");
    } 

    public function delban($idban){
      $sql = $this->db->query("DELETE FROM bancos WHERE id_banco = $idban ");
    }

}
