<?php

/* ------------------------------------------------
  ARCHIVO: Cajachica_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Cajachica_model.
  FECHA DE CREACIÓN: 16/09/2017
 * 
  ------------------------------------------------ */

class Cajachica_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* LISTADO DE INGRESOS DE CAJA CHICA */
    public function lst_cajachica(){
      $sql = $this->db->query("SELECT id_caja, nom_caja, activo
                                FROM caja_chica
                                WHERE activo = 1");
      $resultado = $sql->result();
      return $resultado;
    }

    /* Determinar si existe Apertura */
    public function existeapertura() {
        $usua = $this->session->userdata('usua');
        $id = $usua->id_usu;
/*        $query = $this->db->query("SELECT count(*) as cantidad FROM caja_chica 
                                     where estatus=0");*/

        $query = $this->db->query("SELECT count(*) as cantidad
                                     FROM caja_chica c
                                     INNER JOIN deposito_efectivo d on d.id = c.id_caja  
                                     INNER JOIN permiso_sucursal p on p.id_sucursal = d.idsucursal 
                                     WHERE p.id_usuario = $id AND
                                           not c.id_caja in (SELECT m.id_caja FROM caja_chica_movimiento m WHERE m.estatus=0)");

        $result = $query->result();
        $val = $result[0]->cantidad;
        if ($val > 0 ){
          return $res = 1; //SI LA CAJA ESTA APERTURADA
        } else{
          return $res = 0; // NO ESTA APERTURADA
        }
    }


    /* GUARDAR APERTURA DE CAJA CHICA */
    public function guardar_apertura($caja, $fecha,$monto,$descripcion){
        $usua = $this->session->userdata('usua');
        $idusuario = $usua->id_usu;
        $query = $this->db->query("call cajachica_insapertura($idusuario, $caja,'$fecha',$monto,'$descripcion');");

        $query->next_result(); 
        $query->free_result();
    }

    /* LISTADO DE INGRESOS DE CAJA CHICA */
    public function lst_cajachica_movimiento($caja, $desde, $hasta){
      $sql = $this->db->query(" SELECT id_mov, id_caja, fechaapertura, fecharegistroapertura, usuarioapertura,
                                        descripcion, montoapertura, estatus, 
                                        fechacierre, fecharegistrocierre, usuariocierre, montocierre  
                                FROM caja_chica_movimiento
                                WHERE id_caja = $caja AND fechaapertura BETWEEN '$desde' AND '$hasta'");
      $resultado = $sql->result();
      return $resultado;
    }

    /* LISTADO DE INGRESOS DE CAJA CHICA */
    public function lst_cajachica_mov_id($id){
      $sql = $this->db->query(" SELECT id_mov, id_caja, fechaapertura, fecharegistroapertura, usuarioapertura,
                                        descripcion, montoapertura, estatus, 
                                        fechacierre, fecharegistrocierre, usuariocierre, montocierre  
                                FROM caja_chica_movimiento
                                WHERE id_mov=$id");
      $resultado = $sql->result();
      return $resultado[0];
    }

    /* LISTADO DE INGRESOS DE CAJA CHICA */
    public function lst_cajaingreso($caja, $desde, $hasta){
      $sql = $this->db->query(" SELECT id_ingreso, fechaingreso, fecharegistro, 
                                       monto, numeroingreso, descripcion, idusuario
                                FROM caja_chicaingreso
                                WHERE id_caja = $caja AND fechaingreso BETWEEN '$desde' AND '$hasta'");
      $resultado = $sql->result();
      return $resultado;
    }

  /* AGREGAR INGRESOS DE CAJA CHICA */
    public function add_ingreso($caja, $fecha, $nroingreso, $monto, $des, $idusu){
      $this->db->query("INSERT INTO caja_chicaingreso (id_caja, fechaingreso, numeroingreso, monto, descripcion, idusuario) 
                          VALUES ($caja, '$fecha', '$nroingreso', $monto, '$des', $idusu)");
    }

  /* AGREGAR INGRESOS DE CAJA CHICA */
    public function del_caja($iddoc){
      $this->db->query("DELETE FROM caja_chicaingreso WHERE id_ingreso = $iddoc");
    }
      

   /* LISTADO DE MOVIMIENTOS DE CAJA CHICA */
    public function reportemovimiento($id){
      $sql = $this->db->query("call cajachica_movimientos($id);");
      $resultado = $sql->result();      
      $sql->next_result(); 
      $sql->free_result();      
      return $resultado;
    }

    /* RESUMEN MOVIMIENTO DE CAJA CHICA DE CAJA CHICA */
    public function cajachica_resumen($caja){
      $sql = $this->db->query("call cajachica_resumen($caja)");
      $resultado = $sql->result();
      $sql->next_result(); 
      $sql->free_result(); 
      if($resultado == NULL){
        return $resultado;
      }else{
      return $resultado[0];
      }                
    }

    /* GUARDAR CIERRE DE CAJA CHICA */
    public function guardar_cierre($caja, $fecha,$monto,$obs){
        if($obs == "" or $obs == null){ $obs = ""; }
        $usua = $this->session->userdata('usua');
        $idusuario = $usua->id_usu;
        $query = $this->db->query("call cajachica_cierre($caja, $idusuario,'$fecha',$monto, '$obs');");

    }

    /* MONTO CAJA APERTURA + INGRESOS  */
    public function montocaja($caja, $mes){
      $sql = $this->db->query("SELECT ((SELECT montoapertura FROM caja_chica_movimiento 
                                          WHERE id_caja = $caja AND estatus = 0 AND MONTH(fechaapertura) = $mes )+(SELECT SUM(monto) FROM caja_chicaingreso WHERE id_caja = $caja AND MONTH(fechaingreso) = $mes)) AS montocaja");
      $resu = $sql->result();
      if($resu == NULL){
        $monto = NULL;
        return $monto;
      }else{
        $monto = $resu[0]->montocaja;
        return $monto;
      }
    }

    public function lst_cajachica_noabierta() {
        $usua = $this->session->userdata('usua');
        $id = $usua->id_usu;
        $query = $this->db->query("SELECT id_caja, nom_caja
                                     FROM caja_chica c 
                                     INNER JOIN deposito_efectivo d on d.id = c.id_caja  
                                     INNER JOIN permiso_sucursal p on p.id_sucursal = d.idsucursal 
                                     WHERE p.id_usuario = $id AND
                                           not id_caja in (SELECT m.id_caja FROM caja_chica_movimiento m WHERE m.estatus=0)
                                     ORDER BY nom_caja");
        $result = $query->result();
        return $result; 
    }

    public function lst_cajachica_abierta() {
        $usua = $this->session->userdata('usua');
        $id = $usua->id_usu;      
        $query = $this->db->query("SELECT id_caja, nom_caja
                                     FROM caja_chica e 
                                     INNER JOIN deposito_efectivo d on d.id = e.id_caja  
                                     INNER JOIN permiso_sucursal p on p.id_sucursal = d.idsucursal 
                                     WHERE id_caja in (SELECT m.id_caja FROM caja_chica_movimiento m WHERE m.estatus=0)
                                       AND id_usuario = $id
                                     ORDER BY nom_caja");
        $result = $query->result();
        return $result; 
    }

    public function lst_cajachica_sucursal($sucursal){
      $sql = $this->db->query("SELECT id_caja, nom_caja, c.activo
                                FROM caja_chica c
                                INNER JOIN deposito_efectivo d on d.id = c.id_caja
                                WHERE c.activo = 1 AND d.idsucursal = $sucursal");
      $resultado = $sql->result();
      return $resultado;
    }

    public function lst_cajachica_ultimafecha($caja){
      $sql = $this->db->query("SELECT max(fechacierre) as ultimafecha
                                FROM caja_chica_movimiento 
                                WHERE id_caja = $caja");
      $resultado = $sql->result();
      if ($resultado != NULL)                          
        return $resultado[0]->ultimafecha;
      else  
      return NULL;
    }

    public function lst_cajachica_abiertasucursal($sucursal) {
        $usua = $this->session->userdata('usua');
        $id = $usua->id_usu;      
        $query = $this->db->query("SELECT id_caja, nom_caja
                                     FROM caja_chica e 
                                     INNER JOIN deposito_efectivo d on d.id = e.id_caja  
                                     INNER JOIN permiso_sucursal p on p.id_sucursal = d.idsucursal 
                                     WHERE id_caja in (SELECT m.id_caja FROM caja_chica_movimiento m WHERE m.estatus=0)
                                       AND id_usuario = $id AND d.idsucursal = $sucursal
                                     ORDER BY nom_caja");
        $result = $query->result();
        return $result; 
    }

}
