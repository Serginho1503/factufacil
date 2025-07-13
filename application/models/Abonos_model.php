<?php

/* ------------------------------------------------
  ARCHIVO: Almacen_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Almacen.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */

class Almacen_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* INSERTA EL REGISTRO DEL ALMACEN*/
    public function alm_add($nomalm, $resalm, $diralm, $desalm, $sucalm){
      $query = $this->db->query("call almacen_ins('$nomalm','$diralm','$resalm','$desalm','$sucalm');");
      $result = $query->result();
      return $result;
    }

    /* BUSQUEDA POR ID QUE PERMITE MOSTRAR EL ALMACEN PARA SER MODIFICADO */
    public function sel_alm_id($idalm){
      $query = $this->db->query("SELECT almacen_id, almacen_nombre, almacen_direccion, almacen_responsable, almacen_descripcion, sucursal_id 
                                 FROM almacen WHERE almacen_id = $idalm");
      $result = $query->result();
      return $result[0];
    }

    /* MODIFICAR REGISTRO PERTENECIENTE AL ALMACEN SELECCIONADO */
    public function alm_upd($idalm, $nomalm, $resalm, $diralm, $desalm, $sucalm){
      $query = $this->db->query("call almacen_upd($idalm, '$nomalm', '$diralm', '$resalm', 
                                                  '$desalm', '$sucalm');");
      $result = $query->result();
      return $result;
    }

    /* ELIMINAR EL REGISTRO DEL ALMACEN SELECCIONADO */
    public function alm_del($idalm){
      $query = $this->db->query("call almacen_del($idalm);");
      $result = $query->result();
      return $result;
    }

    public function lst_suc(){
      $query = $this->db->query("SELECT id_sucursal, nom_sucursal FROM sucursal");
      $r = $query->result();
      return $r;
    }

    /* cargar almacenes */
    public function sel_alm(){
      $query = $this->db->query("call almacen_sel;");
      $result = $query->result();
      return $result;
    }

}
