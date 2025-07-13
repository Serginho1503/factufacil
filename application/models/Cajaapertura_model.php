<?php

/* ------------------------------------------------
  ARCHIVO: Cajaapertura_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la apertura de Caja.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */

class Cajaapertura_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* Determinar si existe Apertura */
    public function existeapertura() {
        $usua = $this->session->userdata('usua');
        $id = $usua->id_usu;
        /*$query = $this->db->query("SELECT count(*) as cantidad FROM caja_movimiento 
                                     where id_usuario=$id and estado=0");*/


        $query = $this->db->query("SELECT count(*) as cantidad
                                     FROM caja_efectivo e 
                                     WHERE not id_caja in (SELECT m.id_caja FROM caja_movimiento m WHERE m.estado=0)");


        $result = $query->result();
        $val = $result[0]->cantidad;
        if ($val > 0 ){
          return $res = 1; //SI LA CAJA ESTA APERTURADA
        }else{
          return $res = 0; // NO ESTA APERTURADA
        }
    }

    /* OBTENER TODOS LOS DATOS DE LA Apertura */
    public function datosapertura() {
        $usua = $this->session->userdata('usua');
        $id = $usua->id_usu;
        $query = $this->db->query("SELECT cm.fecha_apertura, cm.monto_apertura, 
                                     (select sum(montototal) from venta 
                                        where fecharegistro >= cm.fecha_apertura and
                                              idusu = ) as ingresoefectivo
                                     FROM caja_movimiento cm
                                     where cm.id_usuario=$id and estado=0");
        $result = $query->result();
        return $result;
    }

    /* SE ACTUALIZAN LOS DATOS DE LA EMPRESA */
    public function insertar($idcaja, $monto){
      $existe = $this->existecajaefectivo_noabierta($idcaja);
      if ($existe > 0){
        $this->db->query("UPDATE caja_efectivo SET nro_orden = 0");
        $usua = $this->session->userdata('usua');
        $idusuario = $usua->id_usu;
        $query = $this->db->query("call cajaapertura_ins($idusuario, $idcaja, $monto);");

        $query->next_result(); 
        $query->free_result();
      }
    }


    /* Determinar si existe Apertura */
    public function lst_cajaefectivo_noabierta() {
        $query = $this->db->query("SELECT id_caja, nom_caja
                                     FROM caja_efectivo e 
                                     WHERE not id_caja in (SELECT m.id_caja FROM caja_movimiento m WHERE m.estado=0)
                                     ORDER BY nom_caja");
        $result = $query->result();
        return $result; 
    }

    public function existecajaefectivo_noabierta($idcaja = 0) {
        $query = $this->db->query("SELECT count(*) as cantidad
                                     FROM caja_efectivo e 
                                     WHERE ($idcaja = 0 OR e.id_caja = $idcaja)
                                           AND not id_caja in (SELECT m.id_caja FROM caja_movimiento m WHERE m.estado=0)");


        $result = $query->result();
        $val = $result[0]->cantidad;
        if ($val > 0 ){
          return $res = 1; //SI LA CAJA ESTA APERTURADA
        }else{
          return $res = 0; // NO ESTA APERTURADA
        }
    }

    public function cajaefectivo_abierta($idcaja) {
        $query = $this->db->query("SELECT count(*) as cantidad
                                     FROM caja_movimiento 
                                     WHERE id_caja = $idcaja and estado=0");


        $result = $query->result();
        return $result[0]->cantidad;
    }



}