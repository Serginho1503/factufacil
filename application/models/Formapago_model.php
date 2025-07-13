<?php

/* ------------------------------------------------
  ARCHIVO: Formapago_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al Formapago.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */

class Formapago_model extends CI_Model {

  function __construct() { parent::__construct(); }

  /* SELECCIONAR LE Mesero POR ID */
  public function sel_formapago_id($idobj){
    $query = $this->db->query(" SELECT id_formapago, cod_formapago, nombre_formapago
                                FROM formapago
                                WHERE id_formapago = $idobj");
    $resultado = $query->result();
    return $resultado[0];
  }

  /* CARGAR formapago */
  public function sel_formapago(){
    $query = $this->db->query(" SELECT id_formapago, cod_formapago, nombre_formapago
                                FROM formapago");
    $resultado = $query->result();
    return $resultado;
  }

  /* ACTUALIZA DATOS DEL formapago */
  public function formapago_upd($idobj, $cod, $nombre){
    $query = $this->db->query("call formapago_upd($idobj,'$cod', '$nombre');");
    $resultado = $query->result();
    return $resultado;
  }

  /* AGREGAR DATOS DEL formapago */
  public function formapago_add($cod, $nombre){
    $query = $this->db->query("call formapago_ins('$cod', '$nombre');");
    $resultado = $query->result();
    return $resultado;
  }

  /* ELIMINAR formapago */
  public function formapago_del($idobj){
    $query = $this->db->query("call formapago_del($idobj);");
    $resultado = $query->result();
    return $resultado;
  }














}
