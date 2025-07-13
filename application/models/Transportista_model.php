<?php

/* ------------------------------------------------
  ARCHIVO: Transportista_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al Transportista.
  FECHA DE CREACIÓN: 25/07/2017
 * 
  ------------------------------------------------ */

class Transportista_model extends CI_Model {

  function __construct() {
      parent::__construct();
  }

  public function sel_transportistas(){
    $query = $this->db->query("SELECT idtransportista, razonsocial, direccion, telefono, cedula, email, tipoid, ciudad
                                 FROM sritransportista
                                 order by razonsocial");
    $resultado = $query->result();
    return $resultado;
  }

    public function sel_transportista_id($id){
        $query = $this->db->query("SELECT idtransportista, razonsocial, direccion, telefono, cedula, email, tipoid, ciudad
                                     FROM sritransportista
                                     WHERE idtransportista = $id");
        $resultado = $query->result();
        return $resultado[0];
    }
    
  /* INSERTA EL REGISTRO DEL CLIENTE */
  public function add_transportista($razonsocial, $direccion, $telefono, $cedula, $email, $tipoid, $ciudad){
    $this->db->query("INSERT into sritransportista (razonsocial, direccion, telefono, cedula, email, tipoid, ciudad)
                        Values('$razonsocial', '$direccion', '$telefono', '$cedula', '$email', '$tipoid', '$ciudad')");
  }


  /* CARGAR TIPOS DE IDENTIFICACION */
  public function identificacion(){
    $query = $this->db->query("SELECT cod_identificacion, desc_identificacion FROM identificacion");
    $resultado = $query->result();
    return $resultado;
  }

  public function upd_transportista($id, $razonsocial, $direccion, $telefono, $cedula, $email, $tipoid, $ciudad){
    $this->db->query("UPDATE sritransportista SET 
                          razonsocial = '$razonsocial', 
                          direccion = '$direccion', 
                          telefono = '$telefono', 
                          cedula = '$cedula', 
                          email = '$email', 
                          tipoid = '$tipoid', 
                          ciudad = '$ciudad'
                        WHERE idtransportista = $id");
  }

  public function candel_transportista($idtrans){
    $query = $this->db->query("SELECT count(*) as cant FROM sriguiaremisionencab WHERE idtransportista = $idtrans");
    $result = $query->result();
    if ($result[0]->cant == 0)
      { return 1; }
    else
      { return 0; }
  }

  public function del_transportista($idtrans){
    if ($this->candel_transportista($idtrans) == 1){
      $query = $this->db->query("DELETE FROM sritransportista WHERE idtransportista = $idtrans");
      return 1;
    } else {
      return 0;
    }
  }

  public function existeIdentificacion($idtrans, $identificacion){
    $query = $this->db->query("SELECT count(*) as cant FROM sritransportista
                                 WHERE cedula = '$identificacion' and idtransportista != $idtrans");
    $resultado = $query->result();
    return $resultado[0]->cant;
  }


}
