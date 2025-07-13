<?php

/* ------------------------------------------------
  ARCHIVO: Mesero_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al Mesero.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */

class Mesero_model extends CI_Model {

  function __construct() { parent::__construct(); }

  /* CARGAR TIPOS DE IDENTIFICACION */
  public function identificacion(){
    $query = $this->db->query("SELECT cod_identificacion, desc_identificacion FROM identificacion");
    $resultado = $query->result();
    return $resultado;
  }  

  /* SELECCIONAR LE Mesero POR ID */
  public function sel_mesero_id($idmese){
    $query = $this->db->query(" SELECT id_mesero, tipo_ident_mesero, ced_mesero, nom_mesero, telf_mesero, correo_mesero, direccion_mesero, foto_mesero, estatus_mesero 
                                FROM mesero
                                WHERE id_mesero = $idmese");
    $resultado = $query->result();
    return $resultado[0];
  }

  /* CARGAR MESEROS */
  public function sel_mesero(){
    $query = $this->db->query("SELECT id_mesero, ced_mesero, nom_mesero, 
                                      case estatus_mesero when 'A' then 'Activo' else 'Inactivo'
                                      end as estatus_mesero 
                                FROM mesero");
    $resultado = $query->result();
    return $resultado;
  }

  /* ACTUALIZA DATOS DEL MESERO */
  public function mese_upd($idmese, $tipide, $nroide, $nombre, $correo, $telf, $dir, $est, $fot){
    $query = $this->db->query("call mesero_upd($idmese,'$tipide', '$nroide', '$nombre', 
                                               '$telf', '$correo', '$dir', '$fot', '$est');");
    $resultado = $query->result();
    return $resultado;
  }

  /* AGREGAR DATOS DEL MESERO */
  public function mese_add($tipide, $nroide, $nombre, $correo, $telf, $dir, $est, $fot){
    $query = $this->db->query("call mesero_ins('$tipide', '$nroide', '$nombre', '$telf', 
                                               '$correo', '$dir', '$fot', '$est');");
    $resultado = $query->result();
    return $resultado;
  }

  /* ELIMINAR MESERO */
  public function mese_del($idmes){
    $query = $this->db->query("call mesero_del($idmes);");
    $resultado = $query->result();
    return $resultado;
  }














}
