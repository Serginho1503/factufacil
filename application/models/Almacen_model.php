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
    public function alm_add($nomalm, $resalm, $diralm, $desalm, $sucalm, $depalm, $prodalm, $tipoalma){
      $query = $this->db->query("call almacen_ins('$nomalm','$diralm','$resalm','$desalm','$sucalm', $depalm, $prodalm, $tipoalma);");
      $result = $query->result();

      $query->next_result(); 
      $query->free_result();

      if ($prodalm != 0){
        $alm = $result[0];
        $idalm = $alm->id;
        $det = $this->db->query("insert into almapro (id_pro, id_alm, existencia, id_unimed)
                                  select p.pro_id, $idalm, 0, p.pro_idunidadmedida from producto p
                                    where p.pro_id = $prodalm and
                                          not exists (select * from almapro where id_pro = p.pro_id and id_alm = $idalm);");
      }

      return $result;
    }

    /* BUSQUEDA POR ID QUE PERMITE MOSTRAR EL ALMACEN PARA SER MODIFICADO */
    public function sel_alm_id($idalm){
      $query = $this->db->query("SELECT a.almacen_id, a.almacen_nombre, a.almacen_direccion, 
                                        a.almacen_responsable, a.almacen_descripcion, 
                                        a.sucursal_id, a.almacen_deposito, a.almacen_idproducto, a.almacen_tipo,
                                        s.contabilizacion_automatica
                                 FROM almacen a
                                 INNER JOIN sucursal s on s.id_sucursal = a.sucursal_id
                                 WHERE almacen_id = $idalm");
      $result = $query->result();
      return $result[0];
    }

    /* MODIFICAR REGISTRO PERTENECIENTE AL ALMACEN SELECCIONADO */
    public function alm_upd($idalm, $nomalm, $resalm, $diralm, $desalm, $sucalm, $depalm, $prodalm, $tipoalma){
      $query = $this->db->query("call almacen_upd($idalm, '$nomalm', '$diralm', '$resalm', 
                                                  '$desalm', '$sucalm', $depalm, $prodalm, $tipoalma);");
      $result = $query->result();

      $query->next_result(); 
      $query->free_result();

      if ($prodalm != 0){
        $det = $this->db->query("insert into almapro (id_pro, id_alm, existencia, id_unimed)
                                  select p.pro_id, $idalm, 0, p.pro_idunidadmedida from producto p
                                    where p.pro_id = $prodalm and
                                          not exists (select * from almapro where id_pro = p.pro_id and id_alm = $idalm);");
      }
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

    public function lst_producto(){
      $query = $this->db->query("SELECT pro_id, pro_nombre FROM producto ORDER BY pro_nombre");
      $r = $query->result();
      return $r;
    }

    public function lst_almacensucursal($sucursal){
      $query = $this->db->query("SELECT almacen_id, almacen_nombre FROM almacen WHERE sucursal_id=$sucursal ORDER BY almacen_nombre");
      $r = $query->result();
      return $r;
    }

}
