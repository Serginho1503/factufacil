<?php

/* ------------------------------------------------
  ARCHIVO: Puntoemision_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al Punto de emision.
  FECHA DE CREACIÓN: 19/03/2018
 * 
  ------------------------------------------------ */

class Puntoemision_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function sel_puntoemision(){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $query = $this->db->query("SELECT p.id_puntoemision, p.id_sucursal, p.cod_establecimiento, 
                                        p.cod_puntoemision, p.consecutivo_factura, p.consecutivo_notaventa, 
                                        p.consecutivo_comprobpago, p.activo, s.nom_sucursal, 
                                        p.consecutivo_notacredito, p.enviosriguardar_factura,
                                        p.consecutivo_retencioncompra, p.consecutivo_guiaremision,
                                        p.ambiente_factura, p.ambiente_retencion, p.ambiente_notacredito, 
                                        p.ambiente_guia,
                                        concat(p.cod_establecimiento,'-',p.cod_puntoemision) as cod_punto
                                  FROM punto_emision p
                                  INNER JOIN sucursal s on s.id_sucursal = p.id_sucursal
                                  INNER JOIN permiso_sucursal ps on ps.id_sucursal = s.id_sucursal
                                  WHERE ps.id_usuario = $idusu
                                  ORDER BY p.cod_establecimiento, p.cod_puntoemision");
      $result = $query->result();
      return $result;
    }

    public function sel_puntoemision_id($puntoemision){
      $query = $this->db->query(" SELECT id_puntoemision, id_sucursal, cod_establecimiento, cod_puntoemision,
                                         consecutivo_factura, consecutivo_notaventa, consecutivo_comprobpago, 
                                         consecutivo_notacredito, activo, enviosriguardar_factura,
                                         consecutivo_retencioncompra, consecutivo_guiaremision,
                                         ambiente_factura, ambiente_retencion, ambiente_notacredito, ambiente_guia
                                  FROM punto_emision WHERE id_puntoemision = $puntoemision");
      $result = $query->result();
      return $result[0];
    }

    public function upd_puntoemision($puntoemision, $sucursal, $codestab, $codptoemi, $consecfactura, $consecnotaventa, 
                                     $consecpago, $consecnotacredito, $consecretencioncompra, $activo, $consecguia,
                                     $ambiente_factura, $ambiente_retencion, $ambiente_notacredito, $ambiente_guia,
                                     $enviosrifactura){
      $this->db->query(" UPDATE punto_emision SET 
                                id_sucursal = $sucursal, 
                                cod_establecimiento = '$codestab', 
                                cod_puntoemision = '$codptoemi', 
                                consecutivo_factura = $consecfactura,
                                consecutivo_notaventa = $consecnotaventa,
                                consecutivo_comprobpago = $consecpago,
                                consecutivo_notacredito = $consecnotacredito,
                                consecutivo_retencioncompra = $consecretencioncompra,
                                consecutivo_guiaremision = $consecguia,
                                ambiente_factura = $ambiente_factura, 
                                ambiente_retencion = $ambiente_retencion, 
                                ambiente_notacredito = $ambiente_notacredito, 
                                ambiente_guia = $ambiente_guia,
                                activo = $activo,
                                enviosriguardar_factura = $enviosrifactura
                               WHERE id_puntoemision = $puntoemision");

      if ($ambiente_factura == 2){
        $this->del_facturasriprueba($puntoemision);
      }
      if ($ambiente_retencion == 2){
        $this->del_retencionsriprueba($puntoemision);
      }
      if ($ambiente_notacredito == 2){
        $this->del_notacreditosriprueba($puntoemision);
      }
      if ($ambiente_guia == 2){
        $this->del_guiaremisionsriprueba($puntoemision);
      }

    }

    public function add_puntoemision($sucursal, $codestab, $codptoemi, $consecfactura, $consecnotaventa, $consecpago, 
                                     $consecnotacredito, $consecretencioncompra, $consecguia,
                                     $ambiente_factura, $ambiente_retencion, $ambiente_notacredito, $ambiente_guia,
                                     $enviosrifactura){
        $this->db->query("INSERT INTO punto_emision (id_sucursal, cod_establecimiento, cod_puntoemision, consecutivo_factura, 
                                                     consecutivo_notaventa, consecutivo_comprobpago, consecutivo_notacredito, 
                                                     consecutivo_retencioncompra, consecutivo_guiaremision,
                                                     ambiente_factura, ambiente_retencion, ambiente_notacredito,
                                                     ambiente_guia, activo, enviosriguardar_factura)
                                      VALUES($sucursal, '$codestab', '$codptoemi', $consecfactura, $consecnotaventa, 
                                             $consecpago, $consecnotacredito, $consecretencioncompra, $consecguia,
                                             $ambiente_factura, $ambiente_retencion, $ambiente_notacredito, $ambiente_guia, 
                                             1, $enviosrifactura);");
    }

    public function candel_puntoemision($puntoemision){
      $query = $this->db->query("SELECT count(*) as cant FROM venta WHERE id_puntoemision = $puntoemision");
      $result = $query->result();
      if ($result[0]->cant == 0){
        $query = $this->db->query("SELECT count(*) as cant FROM caja_efectivo WHERE id_puntoemision = $puntoemision");
        $result = $query->result();
      }
      if ($result[0]->cant == 0)
        { return 1; }
      else
        { return 0; }
    }

    public function del_puntoemision($puntoemision){
      if ($this->candel_puntoemision($puntoemision) == 1){
        $query = $this->db->query("DELETE FROM punto_emision WHERE id_puntoemision = $puntoemision");
        return 1;
      } else {
        return 0;
      }
    }

    public function lst_puntoemisionsucursal($sucursal){
      $query = $this->db->query("SELECT p.id_puntoemision, p.cod_establecimiento, p.cod_puntoemision,       
                                        p.consecutivo_factura, p.consecutivo_notaventa, p.consecutivo_comprobpago, 
                                        p.consecutivo_retencioncompra, p.consecutivo_guiaremision, p.enviosriguardar_factura,
                                        p.ambiente_factura, p.ambiente_retencion, p.ambiente_notacredito, p.ambiente_guia,
                                        concat(p.cod_establecimiento,'-',p.cod_puntoemision) as cod_punto,
                                        s.nom_sucursal       
                                  FROM punto_emision p
                                  INNER jOIN sucursal s on s.id_sucursal = p.id_sucursal
                                  WHERE p.id_sucursal=$sucursal AND p.activo = 1
                                  ORDER BY cod_establecimiento, cod_puntoemision");
      $r = $query->result();
      return $r;
    }

    public function del_facturasriprueba($puntoemision){
        $this->db->query("DELETE FROM facturainfoestadosri 
                            WHERE substr(claveacesso,24,1)='1' AND autorizado=1 AND
                                  idfactura IN (SELECT id_venta FROM venta WHERE id_puntoemision = $puntoemision)");
    }

    public function del_retencionsriprueba($puntoemision){
        $this->db->query("DELETE FROM retencioninfoestadosri 
                            WHERE substr(claveacesso,24,1)='1' AND autorizado=1 AND
                                  idretencion IN (SELECT id_comp_ret FROM compra_retencion WHERE id_puntoemision = $puntoemision)");
        $this->db->query("DELETE FROM retenciongastoinfoestadosri 
                            WHERE substr(claveacesso,24,1)='1' AND autorizado=1 AND
                                  idretencion IN (SELECT id_gastos_ret FROM gastos_retencion WHERE id_puntoemision = $puntoemision)");
    }

    public function del_notacreditosriprueba($puntoemision){
        $this->db->query("DELETE FROM notacreditoinfoestadosri 
                            WHERE substr(claveacesso,24,1)='1' AND autorizado=1 AND
                                  idnotacredito IN (SELECT id FROM notacredito WHERE id_puntoemision = $puntoemision)");
    }

    public function del_guiaremisionsriprueba($puntoemision){
        $this->db->query("DELETE FROM guiaremisioninfoestadosri 
                            WHERE substr(claveacesso,24,1)='1' AND autorizado=1 AND
                                  idguia IN (SELECT idguia FROM sriguiaremisionencab WHERE id_puntoemision = $puntoemision)");
    }

}
