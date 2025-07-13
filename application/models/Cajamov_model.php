<?php

/* ------------------------------------------------
  ARCHIVO: Cajamov_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al movimiento de caja. 
  FECHA DE CREACIÓN: 04/08/2017
 * 
  ------------------------------------------------ */

class Cajamov_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* BUSQUEDA DEL AREA POR ID */
/*    
    public function sel_cajamov_id($idmov){
      $query = $this->db->query("SELECT id_mov, fecha_apertura, monto_apertura, 
                                   fecha_cierre, pagos, ingresoefectivo, ingresotarjeta, 
                                   compras, existente, estado, observaciones,
                                   round(monto_apertura - pagos - compras + ingresoefectivo - ingresotarjeta, 2) as saldo,
                                   round(existente - (monto_apertura - pagos - compras + ingresoefectivo - ingresotarjeta), 2) as diferencia,
                                   salida, justificacion, c.nom_caja  
                                   FROM caja_movimiento m
                                   INNER JOIN caja_efectivo c on c.id_caja = m.id_caja WHERE id_mov = $idmov");
      $result = $query->result();
      return $result[0];
    }
*/
    public function sel_cajamov_id($idmov){
      $query = $this->db->query("SELECT *,(SELECT CONCAT(nom_usu,' ',ape_usu) FROM usu_sistemas WHERE id_usu = m.id_usuario) AS usuape,
                                          (SELECT CONCAT(nom_usu,' ',ape_usu) FROM usu_sistemas WHERE id_usu = m.idusu_cierre) AS usucie,
                                          nom_caja as caja, p.id_sucursal, s.id_empresa 
                                  FROM caja_movimiento m
                                  INNER JOIN caja_efectivo c on c.id_caja = m.id_caja
                                  INNER JOIN punto_emision p on p.id_puntoemision = c.id_puntoemision
                                  INNER JOIN sucursal s on s.id_sucursal = p.id_sucursal
                                  WHERE id_mov = $idmov");
      $result = $query->result();
      return $result[0];
    }

    /*
    public function sel_cajamov($desde, $hasta){
      $usua = $this->session->userdata('usua');
      $idusuario = $usua->id_usu;      
      $admin = $usua->perfil;
      $query = $this->db->query("SELECT id_mov, fecha_apertura, monto_apertura, 
                                   fecha_cierre, pagos, ingresoefectivo, ingresotarjeta, 
                                   compras, existente, estado,
                                   round(monto_apertura - pagos - compras + ingresoefectivo - ingresotarjeta,2) as saldo,
                                   round(monto_apertura - pagos - compras + ingresoefectivo - ingresotarjeta - existente,2) as diferencia,
                                   salida, justificacion 
                                   FROM caja_movimiento
                                   WHERE (($admin = 1) or (id_usuario = $idusuario)) AND 
                                         ((fecha_apertura BETWEEN '$desde' AND '$hasta') OR
                                          (IFNULL(fecha_cierre,'1990-01-01') BETWEEN '$desde' AND '$hasta'))
                                   ORDER BY fecha_apertura desc");
      $result = $query->result();
      return $result;
    }
*/
    public function sel_cajamov($desde, $hasta){
      $usua = $this->session->userdata('usua');
      $idusuario = $usua->id_usu;      
      $admin = $usua->perfil;
      $query = $this->db->query("SELECT m.id_mov, m.fecha_apertura, m.monto_apertura, m.fecha_cierre, m.ventastotales, 
                                        m.desefectivo, m.montonoefectivo, m.montoegreso, m.saldo, m.sobrante, m.faltante,
                                        c.nom_caja
                                   FROM caja_movimiento m
                                   INNER JOIN caja_efectivo c on c.id_caja = m.id_caja
                                   WHERE 
                                         ((m.fecha_apertura BETWEEN '$desde' AND '$hasta') OR
                                          (IFNULL(m.fecha_cierre,'1990-01-01') BETWEEN '$desde' AND '$hasta'))
                                   ORDER BY m.fecha_apertura desc");
      $result = $query->result();
      return $result;
    }
/*
(($admin = 1) or (id_usuario = $idusuario)) AND 
*/

    public function movegreso($idmov){
      $sql = $this->db->query(" SELECT nroegreso, log_usu AS usuario, emisor, descripcion, monto, receptor 
                                FROM caja_egreso c
                                INNER JOIN usu_sistemas u ON u.id_usu = c.id_usu
                                WHERE id_mov = $idmov");
      $res = $sql->result();
      return $res;
    }


    public function ultimomovimientocerrado(){
      $usua = $this->session->userdata('usua');
      $idusuario = $usua->id_usu;      
      $query = $this->db->query("SELECT id_mov
                                   FROM caja_movimiento WHERE id_usuario = $idusuario and estado = 1
                                   ORDER BY fecha_apertura desc limit 1");
      $result = $query->result();
      return $result[0];
    }

    public function lst_ventaanulada($idmovcaja){
      $query = $this->db->query("SELECT v.id_venta, v.fecharegistro, v.nro_factura, v.mesa, v.mesero, 
                                        v.montototal, v.nro_ident, v.nom_cliente, a.causa_anulacion
                                   FROM venta v
                                   LEFT JOIN venta_anulada a on a.idventa = v.id_venta
                                   INNER JOIN caja_movimiento c on v.fecharegistro between c.fecha_apertura and c.fecha_cierre 
                                   WHERE v.estatus=3 and id_mov = $idmovcaja
                                   ORDER BY v.fecharegistro");
      $result = $query->result();
      return $result;
    }

    public function lst_gastoanulado($idmovcaja){
      $query = $this->db->query("SELECT g.id_gastos, g.fecha, g.nro_factura, g.total, 
                                        p.nom_proveedor, p.nro_ide_proveedor, a.causa_anulacion
                                   FROM gastos g
                                   LEFT JOIN proveedor p ON p.id_proveedor = g.id_proveedor
                                   LEFT JOIN gasto_anulado a on a.idgasto = g.id_gastos
                                   INNER JOIN caja_movimiento c on g.fecha between date(c.fecha_apertura) and date(c.fecha_cierre) 
                                   WHERE g.estatus=3 and id_mov = $idmovcaja
                                   ORDER BY g.fecha");
      $result = $query->result();
      return $result;
    }

    public function lst_compraanulada($idmovcaja){
      $query = $this->db->query("SELECT g.id_comp, g.fecha, g.nro_factura, g.montototal, 
                                        p.nom_proveedor, p.nro_ide_proveedor, a.causa_anulacion
                                   FROM compra g
                                   LEFT JOIN proveedor p ON p.id_proveedor = g.id_proveedor
                                   LEFT JOIN compra_anulada a on a.idcompra = g.id_comp
                                   INNER JOIN caja_movimiento c on g.fecha between date(c.fecha_apertura) and date(c.fecha_cierre) 
                                   WHERE g.estatus=3 and id_mov = $idmovcaja
                                   ORDER BY g.fecha");
      $result = $query->result();
      return $result;
    }

    public function lst_mesalimpia($idmovcaja){
      $query = $this->db->query("SELECT m.id_mesa, l.observacion, l.fecha, m.nom_mesa
                                   FROM mesa_limpia l
                                   INNER JOIN mesa m on m.id_mesa = l.id_mesa
                                   INNER JOIN caja_movimiento c on l.fecha between c.fecha_apertura and c.fecha_cierre 
                                   WHERE id_mov = $idmovcaja
                                   ORDER BY l.fecha");
      $result = $query->result();
      return $result;
    }

    public function upd_cajamov_apertura($idmov, $monto){
      $query = $this->db->query("UPDATE caja_movimiento 
                                   SET monto_apertura = $monto  
                                   WHERE id_mov = $idmov");
    }




}
