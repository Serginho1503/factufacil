<?php

/* ------------------------------------------------
  ARCHIVO: Compraabono_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a los abonos de Compras.
  FECHA DE CREACIÓN: 30/08/2017
 * 
  ------------------------------------------------ */

class Compraabono_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* LISTADO DE Abonos */
    public function lista_abonos($idcompra){
      $sql = $this->db->query(" SELECT c.id_comp, c.nro_factura, c.montototal, c.dias, c.fecha_pago,
                                       a.id_abono, a.monto, a.fecha, a.id_formapago, 
                                       IFNULL(a.numerodocumento, '') as numerodocumento,
                                       f.nombre_formapago 
                                FROM compra_abonos a 
                                inner join formapago f on f.id_formapago = a.id_formapago
                                inner join compra c on c.id_comp = a.id_compra
                                Where c.formapago = 2 and c.id_comp=$idcompra");
      $resu = $sql->result();
      return $resu;
    }

    /* Busqueda de Abono */
    public function sel_abono($idabono){
      $sql = $this->db->query(" SELECT c.id_comp, c.nro_factura, c.montototal, c.dias, c.fecha_pago,
                                       a.id_abono, a.monto, a.fecha, a.id_formapago, a.iddocpago,
                                       IFNULL(a.numerodocumento, '') as numerodocumento,
                                       a.descripciondocumento, f.nombre_formapago 
                                FROM compra_abonos a 
                                inner join formapago f on f.id_formapago = a.id_formapago
                                inner join compra c on c.id_comp = a.id_compra
                                Where a.id_abono = $idabono");
      $result = $sql->result();
      return $result[0];
    }

    /* BUSQUEDA POR ID QUE PERMITE MOSTRAR EL GASTO PARA SER MODIFICADO */
    public function sel_compra_id($idcompra){
      $query = $this->db->query("SELECT c.id_comp, c.fecha, c.id_proveedor, c.nro_factura, c.nro_autorizacion, c.formapago, 
                                        c.valiva, c.montoiva, c.montototal, c.dias, c.fecha_pago, c.id_sucursal,
                                        p.nom_proveedor, p.nro_ide_proveedor, p.telf_proveedor, p.direccion_proveedor,
                                        ifnull((select sum(a.monto) FROM compra_abonos a 
                                                  where a.id_compra=$idcompra),0) as abonos, 
                                        ifnull((select sum(d.valor_retencion_iva) FROM compra_retencion r
                                                  inner join compra_retencion_detiva d on d.id_comp_ret = r.id_comp_ret
                                                  where r.id_compra=$idcompra),0) as retencion_iva, 
                                        ifnull((select sum(d.valor_retencion_renta) FROM compra_retencion r
                                                  inner join compra_retencion_detrenta d on d.id_comp_ret = r.id_comp_ret
                                                  where r.id_compra=$idcompra),0) as retencion_renta 
                                   FROM compra c
                                   LEFT JOIN proveedor p ON p.id_proveedor = c.id_proveedor
                                  WHERE c.id_comp = $idcompra");
      $result = $query->result();
      return $result[0];
    }

    /* Adicionar Abono */
    public function adicionar($idcompra, $formapago, $monto, $nrodoc, $descripcion){
        $this->db->query("INSERT INTO documento_pago (estado, numero, valor, observaciones) 
                            VALUES (1, '$nrodoc', $monto, '$descripcion')");
        $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM documento_pago");
        $varid = $sqlid->result();
        $idpago = $varid[0]->id;

        $this->db->query("INSERT INTO documento_pagodeposito (iddocumento, iddeposito) 
                            SELECT $idpago, d.id FROM compra c 
                              INNER JOIN deposito_efectivo d on d.idsucursal = c.id_sucursal
                              WHERE d.idtipo = 2 AND c.id_comp = $idcompra");

        $this->db->query("INSERT INTO compra_abonos (iddocpago, id_compra, id_formapago, monto, fecha, numerodocumento,   
                                                     descripciondocumento) 
                            VALUES ($idpago, $idcompra, $formapago, $monto, now(), '$nrodoc', '$descripcion')");
        $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM compra_abonos");
        $varid = $sqlid->result();
        $idabono = $varid[0]->id;
        $newcompra = $this->sel_compra_id($idcompra);
        if ($newcompra->montototal == $newcompra->abonos){
          $sql = $this->db->query("UPDATE compra SET estatus=1 WHERE id_comp = $idcompra");          
        }
        return $idabono;
    }  

    /* Eliminar Abono */
    public function eliminar($idabono, $idcompra){
        $sqlid = $this->db->query("SELECT iddocpago FROM compra_abonos a 
                                     INNER JOIN documento_pago p on p.id = a.iddocpago
                                     WHERE id_abono = $idabono");
        $varid = $sqlid->result();
        if ($varid){
          if (count($varid) == 1){
            $idpago = $varid[0]->iddocpago;
                        
            $this->db->query("DELETE FROM documento_pagodeposito WHERE iddocumento = $idpago");
            $this->db->query("DELETE FROM documento_pago WHERE id = $idpago");
          }
        }  
        $this->db->query("DELETE FROM compra_abonos WHERE id_abono = $idabono");
        $this->db->query("UPDATE compra SET estatus=2 WHERE id_comp = $idcompra");
    }  


    public function datosproveedor($id_compra){
      $sel_obj = $this->db->query("SELECT p.id_proveedor, p.nom_proveedor, p.tip_ide_proveedor, p.nro_ide_proveedor, p.razon_social, 
                                         p.telf_proveedor, p.correo_proveedor, p.ciudad_proveedor, p.direccion_proveedor, p.relacionada
                                  FROM  compra c
                                  LEFT JOIN proveedor p ON p.id_proveedor = c.id_proveedor
                                  WHERE c.id_comp = $id_compra");
      $resultado = $sel_obj->result();
      return $resultado;
    } 
















}
