<?php

/* ------------------------------------------------
  ARCHIVO: Mesa_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Mesa. 
  FECHA DE CREACIÓN: 04/08/2017
 * 
  ------------------------------------------------ */

class Mesa_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* BUSQUEDA DEL MESA POR ID */
    public function sel_mesa_id($idmesa){
      $query = $this->db->query("SELECT id_mesa, nom_mesa, id_area, capacidad, id_comanda FROM mesa WHERE id_mesa = $idmesa");
      $result = $query->result();
      return $result[0];
    }
    /* CARGAR MESAS */
    public function sel_mesa(){
      $query = $this->db->query("call mesa_sel;");
      $result = $query->result();
      return $result;
    }

    /* ACTUALIZAR NOMBRE DEL MESA POR ID */
    public function upd_mesa($idmesa, $nom, $area, $cap, $imp){
      $query = $this->db->query("call mesa_upd($idmesa, '$nom', $area, '$cap', $imp);");
      $result = $query->result();
      return $result;
    }

    /* INSERTA EL REGISTRO DEL MESA */
    public function add_mesa($nom, $area, $cap, $imp){
      $query = $this->db->query("call mesa_ins('$nom', $area, '$cap', $imp);");
      $result = $query->result();
      return $result;
    }

    /* ELIMINA EL REGISTRO DEL MESA */
    public function del_mesa($idmesa){
      $query = $this->db->query("call mesa_del($idmesa);");
      $result = $query->result();
      return $result;
    }

    /* SE CARGAN TODAS LAS AREAS */
    public function sel_area(){
      $query = $this->db->query("SELECT id_area, nom_area FROM area");
      $result = $query->result();
      return $result;
    }    

    /* LISTADO DE IMPRESORAS */
    public function lst_impresora(){
      $sql = $this->db->query("SELECT id_comanda, nom_comanda, impresora FROM comanda");
      $result = $sql->result();
      return $result;      
    }

    /* BUSQUEDA DEL MESA POR ID */
    public function lst_estadoptoventa($incluirservicio = true){
      $strestadoservicio = '';
      if ($incluirservicio == false){
        $strestadoservicio = ' WHERE id != 2';
      }
      $query = $this->db->query("SELECT id, estado FROM puntoventa_estado $strestadoservicio");
      $result = $query->result();
      return $result;
    }

}
