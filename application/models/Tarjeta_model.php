<?php

/* ------------------------------------------------
  ARCHIVO: Tarjeta_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Tarjeta.
  FECHA DE CREACIÓN: 28/11/2017
 * 
  ------------------------------------------------ */

class Tarjeta_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function tarjetalst(){
    	$sql = $this->db->query("SELECT id_tarjeta, nombre, comision_debito, comision_credito 
                                 FROM tarjetas");
    	$resu = $sql->result();
    	return $resu;
    }

    public function seltar($idtar){
      $sql = $this->db->query("SELECT id_tarjeta, nombre, comision_debito, comision_credito 
                                 FROM tarjetas WHERE id_tarjeta = $idtar ");
      $resu = $sql->result();
      return $resu[0];
    }    

    public function savtar($nomtar, $comision_debito = 0, $comision_credito = 0){
      $sql = $this->db->query("INSERT INTO tarjetas (nombre, comision_debito, comision_credito) 
                                 VALUES ('$nomtar', $comision_debito, $comision_credito) ");
    } 

    public function updtar($idtar, $nomtar, $comision_debito = 0, $comision_credito = 0){
      $sql = $this->db->query("UPDATE tarjetas SET 
                                  nombre = '$nomtar',
                                  comision_debito = $comision_debito, 
                                  comision_credito = $comision_credito
                                 WHERE id_tarjeta = $idtar ");
    } 

    public function deltar($idtar){
      $sql = $this->db->query("DELETE FROM tarjetas WHERE id_tarjeta = $idtar ");
    }    


}
