<?php

/* ------------------------------------------------
  ARCHIVO: Proveedor_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al Proveedor.
  FECHA DE CREACIÓN: 26/07/2017
 * 
  ------------------------------------------------ */

class Proveedor_model extends CI_Model {

  function __construct() {
      parent::__construct();
  }

  /* CARGAR TIPOS DE IDENTIFICACION */
  public function identificacion(){
    $query = $this->db->query("SELECT cod_identificacion, desc_identificacion FROM identificacion");
    $resultado = $query->result();
    return $resultado;
  }

  /* INSERTA EL REGISTRO DEL PROVEEDOR */
  public function provee_add($tip_ide, $nro_ide, $nom, $razsoc, $correo, $telf, $ciu, $dir, $rel, $idcategoriacontable){
    $query = $this->db->query("call proveedor_ins('$nom', '$tip_ide', '$nro_ide', '$razsoc', 
                                                   '$telf', '$correo','$ciu', '$dir', '$rel', $idcategoriacontable);");
    $result = $query->result();
    return $result;
  }

  /* SELECCIONAR LE PROVEEDOR POR ID */
  public function sel_provee_id($idprovee){
    $query = $this->db->query("SELECT id_proveedor, nom_proveedor, tip_ide_proveedor, nro_ide_proveedor, razon_social, 
                                      telf_proveedor, correo_proveedor, ciudad_proveedor, direccion_proveedor, relacionada, 
                                      idcategoriacontable 
                                 FROM proveedor
                                WHERE id_proveedor = $idprovee");
    $resultado = $query->result();
    return $resultado[0];
  }

  /* MODIFICAR LOS DATOS DEL PROVEEDOR */
  public function provee_upd($idprovee, $tip_ide, $nro_ide, $nom, $razsoc, $correo, $telf, $ciu, $dir, $rel, $idcategoriacontable){
    $query = $this->db->query("call proveedor_upd($idprovee, '$nom', '$tip_ide', '$nro_ide', 
                                                  '$razsoc', '$telf', '$correo', 
                                                  '$ciu', '$dir', '$rel', $idcategoriacontable);");
    $result = $query->result();
    return $result;
  }

  /* ELIMINAR DE LA BASE DE DATOS AL PROVEEDOR */
  public function provee_del($idprovee){
    $query = $this->db->query("call proveedor_del($idprovee);");
    $result = $query->result();
    return $result;
    /*$query = $this->db->query("DELETE FROM proveedor WHERE id_proveedor = $idprovee");*/
  }

  /* OBTENER LISTADO DE PROVEEDORES  */
  public function sel_prov(){
      $query = $this->db->query("SELECT id_proveedor, nom_proveedor, razon_social, 
                                        telf_proveedor, correo_proveedor, ciudad_proveedor 
                                   FROM proveedor 
                                   WHERE id_proveedor != 1");
      $result = $query->result();
      return $result;
    
  }
}
