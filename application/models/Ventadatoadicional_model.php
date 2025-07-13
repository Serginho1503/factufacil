<?php

/* ------------------------------------------------
  ARCHIVO: Ventadatoadicional_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al Datos adicionales de venta.
  FECHA DE CREACIÓN: 19/03/2018
 * 
  ------------------------------------------------ */

class Ventadatoadicional_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function sel_datoadicional(){
      $query = $this->db->query("SELECT id_config, nombre_datoadicional, activo
                                  FROM venta_config_adicional
                                  ORDER BY nombre_datoadicional");
      $result = $query->result();
      return $result;
    }

    public function sel_datoadicional_id($id){
      $query = $this->db->query("SELECT id_config, nombre_datoadicional, activo
                                  FROM venta_config_adicional WHERE id_config = $id");
      $result = $query->result();
      return $result[0];
    }

    public function existe_datoadicional($id, $datoadicional){
      $existe = 0;
      $arreglo = ['DireccionCliente', 'CorreoCliente', 'TipoCancelacion', 'Subsidio', 
                  'Placa/Matrícula', 'Observaciones'];
      if (in_array( $datoadicional , $arreglo ) == true){
        $existe = 1;
      }
      if ($existe == 0){
        $query = $this->db->query("SELECT count(*) as cant FROM venta_config_adicional 
                                     WHERE nombre_datoadicional = '$datoadicional' AND id_config != $id");
        $result = $query->result();
        $existe = $result[0]->cant;
      }  
      return $existe;
    }

    public function upd_datoadicional($id, $nombre_datoadicional, $activo){
      $this->db->query("UPDATE venta_config_adicional SET 
                                nombre_datoadicional = '$nombre_datoadicional', 
                                activo = $activo
                               WHERE id_config = $id");
    }

    public function add_datoadicional($nombre_datoadicional, $activo){
        $this->db->query("INSERT INTO venta_config_adicional (nombre_datoadicional, activo)
                            VALUES('$nombre_datoadicional', $activo);");
    }

    public function candel_datoadicional($idconfig){
      return 1;
    /*  $query = $this->db->query("SELECT count(*) as cant FROM venta_dato_adicional
                                   WHERE id_config = $idconfig");
      $result = $query->result();
      if ($result[0]->cant == 0)
        { return 1; }
      else
        { return 0; }*/
    }

    public function del_datoadicional($id){
      if ($this->candel_datoadicional($id) == 1){
        $query = $this->db->query("DELETE FROM venta_config_adicional WHERE id_config = $id");
        return 1;
      } else {
        return 0;
      }
    }


}
