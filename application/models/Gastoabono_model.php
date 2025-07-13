<?php

/* ------------------------------------------------
  ARCHIVO: Gastoabonos_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a los abonos de Gastos.
  FECHA DE CREACIÓN: 30/08/2017
 * 
  ------------------------------------------------ */

class Gastoabono_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* LISTADO DE GASTOS */
    public function lista_abonos($idgasto){
      $sql = $this->db->query(" SELECT g.id_gastos, g.nro_factura, g.total,
                                       a.id_abono, a.monto, a.fecha, a.id_formapago, f.nombre_formapago 
                                FROM gastos_abonos a 
                                inner join formapago f on f.id_formapago = a.id_formapago
                                inner join gastos g on g.id_gastos = a.id_gastos
                                Where g.tipo_compra = 2 and g.id_gastos=$idgasto");
      $resu = $sql->result();
      return $resu;
    }

    /* Busqueda de Abono */
    public function sel_abono($idabono){
      $sql = $this->db->query(" SELECT g.id_gastos, g.nro_factura, g.total,
                                       a.id_abono, a.monto, a.fecha, a.id_formapago, f.nombre_formapago 
                                FROM gastos_abonos a 
                                inner join formapago f on f.id_formapago = a.id_formapago
                                inner join gastos g on g.id_gastos = a.id_gastos
                                Where a.id_abono = $idabono");
      $result = $query->result();
      return $result[0];
    }

    /* BUSQUEDA POR ID QUE PERMITE MOSTRAR EL GASTO PARA SER MODIFICADO */
    public function sel_gas_id($idgas){
      $query = $this->db->query("SELECT id_gastos, fecha, id_proveedor, nro_factura, nro_autorización, descripcion, 
                                        apiva, subtotal, descuento, subtotaldesc, montoiva, total,
                                        (select sum(ag.monto) FROM gastos_abonos ag where ag.id_gastos=$idgas) as abonos 
                                   FROM gastos
                                  WHERE id_gastos = $idgas");
      $result = $query->result();
      return $result[0];
    }

    /* Adicionar Abono */
    public function adicionar($idgasto, $formapago, $monto){
        $this->db->query("INSERT INTO documento_pago (estado, numero, valor, observaciones) 
                            VALUES (1, '', $monto, '')");
        $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM documento_pago");
        $varid = $sqlid->result();
        $idpago = $varid[0]->id;
        $sql = $this->db->query("INSERT INTO gastos_abonos (iddocpago, id_gastos, id_formapago, monto, fecha) 
                                   VALUES ($idpago, $idgasto, $formapago, $monto, now())");
    }  

    /* Eliminar Abono */
    public function eliminar($idabono){
        $sqlid = $this->db->query("SELECT iddocpago FROM gastos_abonos a 
                                     INNER JOIN documento_pago p on p.id = a.iddocpago
                                     WHERE id_abono = $idabono");
        $varid = $sqlid->result();
        if ($varid){
          if (count($varid) == 1){
            $idpago = $varid[0]->id;
            $this->db->query("DELETE FROM documento_pago WHERE id = $idpago");
          }
        }  
        $this->db->query("DELETE FROM gastos_abonos WHERE id_abono = $idabono");
    }  


    public function datosproveedor($id_compra){
      $sel_obj = $this->db->query("SELECT p.id_proveedor, p.nom_proveedor, p.tip_ide_proveedor, p.nro_ide_proveedor, p.razon_social, 
                                         p.telf_proveedor, p.correo_proveedor, p.ciudad_proveedor, p.direccion_proveedor, p.relacionada
                                  FROM  gastos c
                                  LEFT JOIN proveedor p ON p.id_proveedor = c.id_proveedor
                                  WHERE c.id_gastos = $id_compra");
      $resultado = $sel_obj->result();
      return $resultado;
    } 
















}
