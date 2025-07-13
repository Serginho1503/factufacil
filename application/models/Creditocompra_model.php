<?php

/* ------------------------------------------------
  ARCHIVO: Credito_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al Credito.
  FECHA DE CREACIÓN: 15/08/2017
 * 
  ------------------------------------------------ */

class Creditocompra_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    public function lst_creditos($empresa, $proveedor, $estado){
      $sql = $this->db->query(" SELECT  id_comp, p.nom_proveedor,fecha, nro_factura, montototal, dias, fecha_pago,
                                        e.desc_estatus as nombre_estado, co.estatus,
                                        case when fecha_pago < CURDATE() then 1 else 0 end as vencido,
                                        ifnull((select sum(monto) from compra_abonos p
                                          where p.id_compra = co.id_comp),0) as abonado                  
                                FROM compra co
                                INNER JOIN sucursal s on s.id_sucursal = co.id_sucursal
                                INNER JOIN estatus_documento e on e.id_estatus = co.estatus
                                INNER JOIN proveedor p ON p.id_proveedor = co.id_proveedor
                                WHERE formapago = 2
                                  AND (($empresa = 0) or (s.id_empresa = $empresa))
                                  AND (($proveedor = 0) or (co.id_proveedor = $proveedor))
                                  AND (($estado = 0) or (co.estatus = $estado))
                                ORDER BY co.fecha, co.nro_factura");
      $resultado = $sql->result();
      return $resultado;  
    }


    /* Listado de estados*/
    public function lst_estadocredito(){
      $sql = $this->db->query("SELECT id_estatus, desc_estatus  
                                FROM estatus_documento
                                ORDER BY id_estatus");
      $resultado = $sql->result();
      return $resultado;
    }    

    /* Listado de las Areas con sus Mesas*/
    public function lst_estadocredito_id($idestado){
      $sql = $this->db->query("SELECT id_estatus, desc_estatus  
                                FROM estatus_documento
                                WHERE id_estatus=$idestado");
      $resultado = $sql->result();
      return $resultado[0];
    }    

    public function total_creditos($empresa, $proveedor, $estado){
      $sql = $this->db->query("SELECT sum(co.montototal) as total, 
                                      sum(co.montototal - ifnull((select sum(monto) from compra_abonos p
                                          where p.id_compra = co.id_comp),0)) as pendiente   
                                FROM compra co
                                INNER JOIN sucursal s on s.id_sucursal = co.id_sucursal
                               WHERE formapago = 2
                                  AND (($empresa = 0) or (s.id_empresa = $empresa))
                                  AND (($proveedor = 0) or (co.id_proveedor = $proveedor))
                                  AND (($estado = 0) or (co.estatus = $estado))");
      $resu = $sql->result();
      return $resu;
    }


}