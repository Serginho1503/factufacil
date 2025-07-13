<?php

/* ------------------------------------------------
  ARCHIVO: InfoSRI_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al SRI.
  FECHA DE CREACIÓN: 15/08/2017
 * 
  ------------------------------------------------ */

class InfoSRI_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $query = $this->db->query("SET time_zone = '-5:00';");
    }
    //Factura de Venta
    public function datosfactura($id_factura){
        $sel_obj = $this->db->query("SELECT c.nom_cliente, 
                                            c.tipo_ident_cliente as tipo_ident, 
                                            c.ident_cliente as nro_ident, 
                                            c.direccion_cliente as dir_cliente, c.correo_cliente, v.id_empresa,
                                            substr(v.nro_factura,9,9) as nro_factura, v.fecha, 
                                            p.cod_establecimiento, p.cod_puntoemision,    
                                            s.dir_sucursal, i.codsri_venta, tc.nom_cancelacion,
                                            e.dir_emp, e.nom_emp, e.raz_soc_emp, e.ruc_emp, e.regimen_emp,
                                            v.valiva, v.subconiva, v.subsiniva, v.desc_monto,
                                            v.descsubconiva, v.descsubsiniva, v.montoiva,
                                            v.montototal, v.observaciones, c.placa_matricula,
                                            p.ambiente_factura, e.obligadocontabilidad,
                                            sri.claveacesso as claveacceso,
                                            IFNULL((SELECT SUM(vd.subsidio) 
                                                      FROM venta_detalle vd WHERE vd.id_venta = v.id_venta), 0) as totalsubsidio
                                      FROM  venta v
                                      INNER JOIN clientes c ON c.id_cliente = v.id_cliente
                                      INNER JOIN punto_emision p ON p.id_puntoemision = v.id_puntoemision
                                      INNER JOIN sucursal s ON s.id_sucursal = v.id_sucursal
                                      INNER JOIN empresa e ON e.id_emp = v.id_empresa
                                      INNER JOIN identificacion i ON i.cod_identificacion = c.tipo_ident_cliente
                                      INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = v.id_tipcancelacion
                                      LEFT JOIN facturainfoestadosri sri ON sri.idfactura = v.id_venta                              
                                    WHERE v.id_venta = $id_factura");
        $resultado = $sel_obj->result();
        return $resultado[0];
      }
  
      public function datosfacturadetalle($id_factura){
        $sql_sel = $this->db->query(" SELECT d.id_producto, p.pro_codigobarra, 
                                             d.descripcion as pro_nombre,
                                             p.pro_codigoauxiliar, d.cantidad, d.precio, d.subtotal, 
                                             d.iva, d.montoiva, d.descmonto, d.descsubtotal,
                                             d.subsidio 
                                      FROM venta_detalle d
                                      INNER JOIN producto p ON p.pro_id = d.id_producto
                                      WHERE d.id_venta = $id_factura ");
        $result = $sql_sel->result();
        return $result;
      } 

      public function datosfacturaformapago($id_factura){
        $sql_sel = $this->db->query(" SELECT d.monto, p.cod_formapago
                                      FROM venta_formapago d
                                      INNER JOIN formapago p ON p.id_formapago = d.id_formapago
                                      WHERE d.id_venta = $id_factura ");
        $result = $sql_sel->result();
        return $result;
      } 

      public function lst_venta_rango($desde, $hasta, $idusuario){
        $sql = $this->db->query("SELECT v.id_venta, v.fecha, v.nro_factura, 
                                        v.nom_cliente, v.nro_ident, v.fecharegistro, v.id_cliente,
                                        v.descsubconiva, v.descsubsiniva, v.montoiva, v.desc_monto, 
                                        v.montototal,
                                        case when s.idfactura is null then 'NO ENVIADA' 
                                           else case
                                                  when s.autorizado = 1 then 'AUTORIZADA'
                                                  when s.rechazado = 1 then 'RECHAZADO'
                                                  when s.autorizado = 0 AND enviadosri = 1 then 'ENVIADA'
                                                end
                                        end AS estado,
                                        s.claveacesso as claveacceso, s.fechaautorizacion, s.enviadoemail
                                    FROM venta v 
                                    INNER JOIN permiso_sucursal p ON p.id_sucursal = v.id_sucursal AND
                                                                     p.id_usuario = $idusuario        
                                    LEFT JOIN facturainfoestadosri s ON s.idfactura = v.id_venta                                                                                              
                                    WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.tipo_doc = 2 AND v.estatus != 3
                                    ORDER BY fecha,nro_factura");
        
        $resu = $sql->result();
        return $resu;
      }

      public function actualiza_enviadoVenta($id, $claveacceso){
        $sql_sel = $this->db->query("SELECT count(*) as cant FROM facturainfoestadosri WHERE idfactura = $id");
        $result = $sql_sel->result();
        if ($result[0]->cant == 0){
          $this->db->query("INSERT INTO facturainfoestadosri (idfactura, secuencial, claveacesso, enviadosri, 
                                                              autorizado, rechazado)
                              SELECT $id, nro_factura, '$claveacceso', 1, 0, 0 
                                FROM venta WHERE id_venta = $id");
        }
        else{
          $this->db->query("UPDATE facturainfoestadosri set
                                claveacesso = '$claveacceso', 
                                enviadosri = 1, 
                                autorizado = 0, 
                                rechazado = 0
                              WHERE idfactura = $id");
        }
      } 

      public function actualiza_autorizadoVenta($id, $fechaautorizo){
          $this->db->query("UPDATE facturainfoestadosri set
                                fechaautorizacion = '$fechaautorizo', 
                                numeroautorizacion = claveacesso,
                                enviadosri = 1, 
                                autorizado = 1, 
                                rechazado = 0
                              WHERE idfactura = $id");
      } 

      public function actualiza_rechazadoVenta($id, $claveacceso){
        $sql_sel = $this->db->query("SELECT count(*) as cant FROM facturainfoestadosri WHERE idfactura = $id");
        $result = $sql_sel->result();
        if ($result[0]->cant == 0){
          $this->db->query("INSERT INTO facturainfoestadosri (idfactura, secuencial, claveacesso, enviadosri, 
                                                              autorizado, rechazado)
                              SELECT $id, nro_factura, $claveacceso, 0, 0, 1 
                                FROM venta WHERE id_venta = $id");
        }
        else{
          $this->db->query("UPDATE facturainfoestadosri set
                                claveacesso = $claveacceso, 
                                enviadosri = 0, 
                                autorizado = 0, 
                                rechazado = 1
                              WHERE idfactura = $id");
        }
      } 

      public function sel_claveaccesoVenta($id){
        $sql_sel = $this->db->query("SELECT claveacesso
                                      FROM facturainfoestadosri
                                      WHERE idfactura = $id");
        $result = $sql_sel->result();
        if ($result)
          return $result[0]->claveacesso;
        else  
          return '';
      } 

      public function sel_logoempresaventa($id){
/*        $sql_sel = $this->db->query("SELECT logo_path 
                                       FROM empresa e 
                                       INNER JOIN venta v on v.id_empresa = e.id_emp
                                       WHERE id_venta = $id");*/
        $sql_sel = $this->db->query("SELECT logo_encab_path 
                                       FROM sucursal s 
                                       INNER JOIN venta v on v.id_sucursal = s.id_sucursal
                                       WHERE id_venta = $id");
        $result = $sql_sel->result();
        if ($result)
          return $result[0]->logo_encab_path;
        else  
          return '';
      } 

      public function actualiza_correoenviadoVenta($id){
          $strfecha = date("d/m/Y  H:i:s"); 
          $this->db->query("UPDATE facturainfoestadosri set
                                enviadoemail = '$strfecha'
                              WHERE idfactura = $id");
      } 

    public function lst_venta_datoadicional($idventa){
      $sql = $this->db->query("SELECT d.id_config, d.datoadicional, c.nombre_datoadicional
                                FROM venta_dato_adicional d 
                                INNER JOIN venta_config_adicional c on c.id_config = d.id_config
                                WHERE d.id_venta = $idventa AND c.activo = 1
                                ORDER BY c.nombre_datoadicional");      
      $resu = $sql->result();
      return $resu;
    }

    //Retencion de Compra
    public function datosRetencionCompra($id){
      $sel_obj = $this->db->query("SELECT p.nom_proveedor, p.tip_ide_proveedor, p.nro_ide_proveedor, 
                                          p.direccion_proveedor, p.correo_proveedor as correo_cliente, 
                                          s.id_empresa,
                                          substr(r.nro_retencion,9,9) as nro_retencion, r.fecha_retencion, 
                                          pe.cod_establecimiento, pe.cod_puntoemision,    
                                          s.dir_sucursal, i.codsri_venta, 
                                          e.dir_emp, e.nom_emp, e.raz_soc_emp, e.ruc_emp, 
                                          pe.ambiente_retencion,
                                          e.obligadocontabilidad
                                    FROM  compra_retencion r
                                    INNER JOIN punto_emision pe ON pe.id_puntoemision = r.id_puntoemision
                                    INNER JOIN compra c ON c.id_comp = r.id_compra
                                    INNER JOIN proveedor p ON p.id_proveedor = c.id_proveedor
                                    INNER JOIN sucursal s ON s.id_sucursal = c.id_sucursal
                                    INNER JOIN empresa e ON e.id_emp = s.id_empresa
                                    INNER JOIN identificacion i ON i.cod_identificacion = p.tip_ide_proveedor
                                  WHERE r.id_comp_ret = $id");
      $resultado = $sel_obj->result();
      return $resultado[0];
    }

    public function detalleRetencionIVA($id){
      $sel_obj = $this->db->query("SELECT r.porciento_retencion_iva, r.valor_retencion_iva, 
                                          r.base_retencion_iva, p.codigo, c.nro_factura, c.fecha
                                    FROM  compra_retencion_detiva r
                                    INNER JOIN compra_retencion cr on cr.id_comp_ret = r.id_comp_ret
                                    INNER JOIN compra c on c.id_comp = cr.id_compra
                                    INNER JOIN porcentaje_retencion_iva p on p.id_porc_ret_iva = r.id_porcentaje_retencion_iva
                                  WHERE r.id_comp_ret = $id");
      $resultado = $sel_obj->result();
      return $resultado;
    }

    public function detalleRetencionRenta($id){
      $sel_obj = $this->db->query("SELECT r.porciento_retencion_renta, r.valor_retencion_renta, 
                                          (r.base_noiva + r.base_iva) as base_retencion, 
                                          p.cod_cto_retencion, c.nro_factura, c.fecha
                                    FROM  compra_retencion_detrenta r
                                    INNER JOIN compra_retencion cr on cr.id_comp_ret = r.id_comp_ret
                                    INNER JOIN compra c on c.id_comp = cr.id_compra
                                    INNER JOIN concepto_retencion p on p.id_cto_retencion = r.id_concepto_retencion
                                  WHERE r.id_comp_ret = $id");
      $resultado = $sel_obj->result();
      return $resultado;
    }

    public function lst_retencioncompra_rango($desde, $hasta, $idusuario){
      $sql = $this->db->query("SELECT cr.id_comp_ret, cr.fecha_retencion, cr.nro_retencion, 
                                      1 as tipo,
                                      p.nom_proveedor, p.nro_ide_proveedor, 
                                      IFNULL((SELECT SUM(base_retencion_iva) FROM compra_retencion_detiva r 
                                                WHERE r.id_comp_ret = cr.id_comp_ret),0) as baseretiva,
                                      IFNULL((SELECT SUM(valor_retencion_iva) FROM compra_retencion_detiva r 
                                                WHERE r.id_comp_ret = cr.id_comp_ret),0) as retiva, 
                                      IFNULL((SELECT SUM(r.base_noiva + r.base_iva) FROM compra_retencion_detrenta r 
                                                WHERE r.id_comp_ret = cr.id_comp_ret),0) as baseretrenta,
                                      IFNULL((SELECT SUM(r.valor_retencion_renta) FROM compra_retencion_detrenta r 
                                                WHERE r.id_comp_ret = cr.id_comp_ret),0) as retrenta, 
                                      case when s.idretencion is null then 'NO ENVIADA' 
                                         else case
                                                when s.autorizado = 1 then 'AUTORIZADA'
                                                when s.rechazado = 1 then 'RECHAZADO'
                                                when s.autorizado = 0 AND enviadosri = 1 then 'ENVIADA'
                                              end
                                      end AS estado,
                                      IFNULL(c.nro_factura,'') as nro_factura,
                                      s.claveacesso as claveacceso, s.fechaautorizacion, s.enviadoemail
                                  FROM compra_retencion cr 
                                  INNER JOIN compra c on c.id_comp = cr.id_compra
                                  INNER JOIN proveedor p ON p.id_proveedor = c.id_proveedor
                                  INNER JOIN permiso_sucursal ps ON ps.id_sucursal = c.id_sucursal AND
                                                                    ps.id_usuario = $idusuario        
                                  LEFT JOIN retencioninfoestadosri s ON s.idretencion = cr.id_comp_ret                                                                                              
                                  WHERE cr.fecha_retencion BETWEEN '$desde' AND '$hasta' AND c.estatus != 3
                               UNION
                               SELECT g.id_gastos_ret as id_comp_ret, g.fecha_retencion, g.nro_retencion, 
                                      2 as tipo,
                                      p.nom_proveedor, p.nro_ide_proveedor, 
                                      IFNULL((SELECT SUM(base_retencion_iva) FROM gastos_retencion_detiva r 
                                                WHERE r.id_gastos_ret = g.id_gastos_ret),0) as baseretiva,
                                      IFNULL((SELECT SUM(valor_retencion_iva) FROM gastos_retencion_detiva r 
                                                WHERE r.id_gastos_ret = g.id_gastos_ret),0) as retiva, 
                                      IFNULL((SELECT SUM(r.base_noiva + r.base_iva) FROM gastos_retencion_detrenta r 
                                                WHERE r.id_gastos_ret = g.id_gastos_ret),0) as baseretrenta,
                                      IFNULL((SELECT SUM(r.valor_retencion_renta) FROM gastos_retencion_detrenta r 
                                                WHERE r.id_gastos_ret = g.id_gastos_ret),0) as retrenta, 
                                      case when s.idretencion is null then 'NO ENVIADA' 
                                         else case
                                                when s.autorizado = 1 then 'AUTORIZADA'
                                                when s.rechazado = 1 then 'RECHAZADO'
                                                when s.autorizado = 0 AND enviadosri = 1 then 'ENVIADA'
                                              end
                                      end AS estado,
                                      IFNULL(c.nro_factura,'') as nro_factura,
                                      s.claveacesso as claveacceso, s.fechaautorizacion, s.enviadoemail
                                  FROM gastos_retencion g 
                                  INNER JOIN gastos c on c.id_gastos = g.id_gastos
                                  INNER JOIN proveedor p ON p.id_proveedor = c.id_proveedor
                                  INNER JOIN permiso_sucursal ps ON ps.id_sucursal = c.id_sucursal AND
                                                                    ps.id_usuario = $idusuario        
                                  LEFT JOIN retenciongastoinfoestadosri s ON s.idretencion = g.id_gastos_ret                                                                                              
                                  WHERE g.fecha_retencion BETWEEN '$desde' AND '$hasta' AND c.estatus != 3                                  
                                  ORDER BY fecha_retencion,nro_retencion");
      
      $resu = $sql->result();
      return $resu;
    }

    public function actualiza_enviadoRetencion($id, $claveacceso){
      $sql_sel = $this->db->query("SELECT count(*) as cant FROM retencioninfoestadosri WHERE idretencion = $id");
      $result = $sql_sel->result();
      if ($result[0]->cant == 0){
        $this->db->query("INSERT INTO retencioninfoestadosri (idretencion, secuencial, claveacesso, enviadosri, 
                                                              autorizado, rechazado)
                            SELECT $id, nro_retencion, '$claveacceso', 1, 0, 0 
                              FROM compra_retencion WHERE id_comp_ret = $id");
      }
      else{
        $this->db->query("UPDATE retencioninfoestadosri set
                              claveacesso = '$claveacceso', 
                              enviadosri = 1, 
                              autorizado = 0, 
                              rechazado = 0
                            WHERE idretencion = $id");
      }
    } 

    public function actualiza_autorizadoRetencion($id, $fechaautorizo){
        $this->db->query("UPDATE retencioninfoestadosri set
                              fechaautorizacion = '$fechaautorizo', 
                              numeroautorizacion = claveacesso,
                              enviadosri = 1, 
                              autorizado = 1, 
                              rechazado = 0
                            WHERE idretencion = $id");
    } 

    public function actualiza_rechazadoRetencion($id, $claveacceso){
      $sql_sel = $this->db->query("SELECT count(*) as cant FROM retencioninfoestadosri WHERE idretencion = $id");
      $result = $sql_sel->result();
      if ($result[0]->cant == 0){
        $this->db->query("INSERT INTO retencioninfoestadosri (idretencion, secuencial, claveacesso, enviadosri, 
                                                              autorizado, rechazado)
                            SELECT $id, nro_retencion, $claveacceso, 0, 0, 1 
                              FROM compra_retencion WHERE id_comp_ret = $id");
      }
      else{
        $this->db->query("UPDATE retencioninfoestadosri set
                              claveacesso = $claveacceso, 
                              enviadosri = 0, 
                              autorizado = 0, 
                              rechazado = 1
                            WHERE idretencion = $id");
      }
    } 

    public function sel_claveaccesoRetencion($id){
      $sql_sel = $this->db->query("SELECT claveacesso
                                    FROM retencioninfoestadosri
                                    WHERE idretencion = $id");
      $result = $sql_sel->result();
      if ($result)
        return $result[0]->claveacesso;
      else  
        return '';
    } 

      public function sel_logoempresaretcompra($id){
/*        $sql_sel = $this->db->query("SELECT logo_path 
                                       FROM empresa e 
                                       INNER JOIN sucursal s ON s.id_empresa = e.id_emp
                                       INNER JOIN compra c ON c.id_sucursal = s.id_sucursal 
                                       INNER JOIN compra_retencion r on r.id_compra = c.id_comp
                                       WHERE r.id_comp_ret = $id");*/
        $sql_sel = $this->db->query("SELECT logo_encab_path 
                                       FROM sucursal s 
                                       INNER JOIN compra c ON c.id_sucursal = s.id_sucursal 
                                       INNER JOIN compra_retencion r on r.id_compra = c.id_comp
                                       WHERE r.id_comp_ret = $id");
        $result = $sql_sel->result();
        if ($result)
          return $result[0]->logo_encab_path;
        else  
          return '';
      } 

      public function actualiza_correoenviadoRetCompra($id){
          $strfecha = date("d/m/Y  H:i:s"); 
          $this->db->query("UPDATE retencioninfoestadosri set
                                enviadoemail = '$strfecha'
                              WHERE idretencion = $id");
      } 

    //Retencion de Gastos
    public function datosRetencionGastos($id){
      $sel_obj = $this->db->query("SELECT p.nom_proveedor, p.tip_ide_proveedor, p.nro_ide_proveedor, 
                                          p.direccion_proveedor, p.correo_proveedor as correo_cliente, 
                                          s.id_empresa,
                                          substr(r.nro_retencion,9,9) as nro_retencion, r.fecha_retencion, 
                                          pe.cod_establecimiento, pe.cod_puntoemision,    
                                          s.dir_sucursal, i.codsri_venta, 
                                          e.dir_emp, e.nom_emp, e.raz_soc_emp, e.ruc_emp,
                                          pe.ambiente_retencion,
                                          e.obligadocontabilidad
                                    FROM  gastos_retencion r
                                    INNER JOIN punto_emision pe ON pe.id_puntoemision = r.id_puntoemision
                                    INNER JOIN gastos c ON c.id_gastos = r.id_gastos
                                    INNER JOIN proveedor p ON p.id_proveedor = c.id_proveedor
                                    INNER JOIN sucursal s ON s.id_sucursal = c.id_sucursal
                                    INNER JOIN empresa e ON e.id_emp = s.id_empresa
                                    INNER JOIN identificacion i ON i.cod_identificacion = p.tip_ide_proveedor
                                  WHERE r.id_gastos_ret = $id");
      $resultado = $sel_obj->result();
      return $resultado[0];
    }

    public function detalleRetencionGastosIVA($id){
      $sel_obj = $this->db->query("SELECT r.porciento_retencion_iva, r.valor_retencion_iva, 
                                          r.base_retencion_iva, p.codigo, c.nro_factura, c.fecha
                                    FROM  gastos_retencion_detiva r
                                    INNER JOIN gastos_retencion cr on cr.id_gastos_ret = r.id_gastos_ret
                                    INNER JOIN gastos c on c.id_gastos = cr.id_gastos
                                    INNER JOIN porcentaje_retencion_iva p on p.id_porc_ret_iva = r.id_porcentaje_retencion_iva
                                  WHERE r.id_gastos_ret = $id");
      $resultado = $sel_obj->result();
      return $resultado;
    }

    public function detalleRetencionGastosRenta($id){
      $sel_obj = $this->db->query("SELECT r.porciento_retencion_renta, r.valor_retencion_renta, 
                                          (r.base_noiva + r.base_iva) as base_retencion, 
                                          p.cod_cto_retencion, c.nro_factura, c.fecha
                                    FROM  gastos_retencion_detrenta r
                                    INNER JOIN gastos_retencion cr on cr.id_gastos_ret = r.id_gastos_ret
                                    INNER JOIN gastos c on c.id_gastos = cr.id_gastos
                                    INNER JOIN concepto_retencion p on p.id_cto_retencion = r.id_concepto_retencion
                                  WHERE r.id_gastos_ret = $id");
      $resultado = $sel_obj->result();
      return $resultado;
    }

    public function actualiza_enviadoRetencionGastos($id, $claveacceso){
      $sql_sel = $this->db->query("SELECT count(*) as cant FROM retenciongastoinfoestadosri WHERE idretencion = $id");
      $result = $sql_sel->result();
      if ($result[0]->cant == 0){
        $this->db->query("INSERT INTO retenciongastoinfoestadosri (idretencion, secuencial, claveacesso, enviadosri, 
                                                              autorizado, rechazado)
                            SELECT $id, nro_retencion, '$claveacceso', 1, 0, 0 
                              FROM gastos_retencion WHERE id_gastos_ret = $id");
      }
      else{
        $this->db->query("UPDATE retenciongastoinfoestadosri set
                              claveacesso = '$claveacceso', 
                              enviadosri = 1, 
                              autorizado = 0, 
                              rechazado = 0
                            WHERE idretencion = $id");
      }
    } 

    public function actualiza_autorizadoRetencionGastos($id, $fechaautorizo){
        $this->db->query("UPDATE retenciongastoinfoestadosri set
                              fechaautorizacion = '$fechaautorizo', 
                              numeroautorizacion = claveacesso,
                              enviadosri = 1, 
                              autorizado = 1, 
                              rechazado = 0
                            WHERE idretencion = $id");
    } 

    public function actualiza_rechazadoRetencionGastos($id, $claveacceso){
      $sql_sel = $this->db->query("SELECT count(*) as cant FROM retenciongastoinfoestadosri WHERE idretencion = $id");
      $result = $sql_sel->result();
      if ($result[0]->cant == 0){
        $this->db->query("INSERT INTO retenciongastoinfoestadosri (idretencion, secuencial, claveacesso, enviadosri, 
                                                              autorizado, rechazado)
                            SELECT $id, nro_retencion, $claveacceso, 0, 0, 1 
                              FROM gastos_retencion WHERE id_gastos_ret = $id");
      }
      else{
        $this->db->query("UPDATE retenciongastoinfoestadosri set
                              claveacesso = $claveacceso, 
                              enviadosri = 0, 
                              autorizado = 0, 
                              rechazado = 1
                            WHERE idretencion = $id");
      }
    } 

    public function sel_claveaccesoRetencionGastos($id){
      $sql_sel = $this->db->query("SELECT claveacesso
                                    FROM retenciongastoinfoestadosri
                                    WHERE idretencion = $id");
      $result = $sql_sel->result();
      if ($result)
        return $result[0]->claveacesso;
      else  
        return '';
    } 

    public function sel_logoempresaretgasto($id){
/*      $sql_sel = $this->db->query("SELECT logo_path 
                                     FROM empresa e 
                                     INNER JOIN sucursal s ON s.id_empresa = e.id_emp
                                     INNER JOIN gastos c ON c.id_sucursal = s.id_sucursal 
                                     INNER JOIN gastos_retencion r ON r.id_gastos = c.id_gastos 
                                     WHERE r.id_gastos_ret = $id");*/
      $sql_sel = $this->db->query("SELECT logo_encab_path 
                                     FROM sucursal s 
                                     INNER JOIN gastos c ON c.id_sucursal = s.id_sucursal 
                                     INNER JOIN gastos_retencion r ON r.id_gastos = c.id_gastos 
                                     WHERE r.id_gastos_ret = $id");
      $result = $sql_sel->result();
      if ($result)
        return $result[0]->logo_encab_path;
      else  
        return '';
    } 

      public function actualiza_correoenviadoRetGasto($id){
          $strfecha = date("d/m/Y  H:i:s"); 
          $this->db->query("UPDATE retenciongastoinfoestadosri set
                                enviadoemail = '$strfecha'
                              WHERE idretencion = $id");
      } 

    //nOTA DE cREDITO
    public function lst_notacredito_rango($desde, $hasta, $idusuario){
      $sql = $this->db->query("SELECT v.id, v.fecha, v.nro_documento, v.tipodocmodificado,
                                      v.nro_docmodificado, v.fecha_docmodificado, v.motivo,
                                      c.nom_cliente, c.ident_cliente, 
                                      v.fecharegistro, v.id_cliente,
                                      v.descsubtotaliva, v.descsubtotalnoiva, v.montoiva, v.descuento, v.total,
                                      case when s.idnotacredito is null then 'NO ENVIADA' 
                                         else case
                                                when s.autorizado = 1 then 'AUTORIZADA'
                                                when s.rechazado = 1 then 'RECHAZADO'
                                                when s.autorizado = 0 AND enviadosri = 1 then 'ENVIADA'
                                              end
                                      end AS estado,
                                      s.claveacesso as claveacceso, s.fechaautorizacion, s.enviadoemail
                                  FROM notacredito v 
                                  INNER JOIN clientes c on c.id_cliente = v.id_cliente
                                  INNER JOIN permiso_sucursal p ON p.id_sucursal = v.id_sucursal AND
                                                                   p.id_usuario = $idusuario        
                                  LEFT JOIN notacreditoinfoestadosri s ON s.idnotacredito = v.id                                                                                              
                                  WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3
                                  ORDER BY fecha,nro_documento");
      
      $resu = $sql->result();
      return $resu;
    }

    public function datosNotaCredito($id){
      $sel_obj = $this->db->query("SELECT c.nom_cliente, c.tipo_ident_cliente, c.ident_cliente, 
                                          c.direccion_cliente, c.correo_cliente, c.telefonos_cliente,
                                          v.nro_documento as nro_factura, v.fecha, 
                                          p.cod_establecimiento, p.cod_puntoemision,    
                                          s.dir_sucursal, i.codsri_venta, 
                                          e.dir_emp, e.nom_emp, e.raz_soc_emp, e.ruc_emp,
                                          v.descuento, v.descsubtotaliva, v.descsubtotalnoiva, 
                                          v.montoiva, v.total,
                                          v.nro_docmodificado, v.fecha_docmodificado, v.motivo,
                                          s.id_empresa, p.ambiente_notacredito,
                                          e.obligadocontabilidad
                                    FROM notacredito v 
                                    INNER JOIN clientes c on c.id_cliente = v.id_cliente
                                    INNER JOIN punto_emision p ON p.id_puntoemision = v.id_puntoemision
                                    INNER JOIN sucursal s ON s.id_sucursal = v.id_sucursal
                                    INNER JOIN empresa e ON e.id_emp = s.id_empresa
                                    INNER JOIN identificacion i ON i.cod_identificacion = c.tipo_ident_cliente
                                    WHERE v.id = $id");
      $resultado = $sel_obj->result();
      return $resultado[0];
    }

    public function datosNotaCreditoResumenImpuesto($id){
      $sql_sel = $this->db->query(" SELECT i.codigotipoimpuesto, i.codigoporcentaje,
                                           sum(i.baseimponible) as baseimponible,
                                           sum(i.valor) as valor
                                    FROM notacredito_detalle d
                                    INNER JOIN notacredito_impuesto i ON i.id_detallenotacredito = d.id
                                    WHERE d.id_notacredito = $id 
                                    GROUP BY i.codigotipoimpuesto, i.codigoporcentaje");
      $result = $sql_sel->result();
      return $result;
    } 

    public function datosNotaCreditodetalle($id){
      $sql_sel = $this->db->query(" SELECT d.id, d.id_producto, p.pro_nombre, p.pro_codigobarra, 
                                           p.pro_codigoauxiliar, d.cantidad, d.precio, d.subtotal, 
                                           d.gravaiva, d.montoiva, d.descuento, d.descsubtotal 
                                    FROM notacredito_detalle d
                                    INNER JOIN producto p ON p.pro_id = d.id_producto
                                    WHERE d.id_notacredito = $id ");
      $result = $sql_sel->result();
      return $result;
    } 

    public function datosNotaCreditoDetalleImpuesto($id){
      $sql_sel = $this->db->query("SELECT codigotipoimpuesto, codigoporcentaje, tarifa, baseimponible, valor
                                    FROM notacredito_impuesto 
                                    WHERE id_detallenotacredito = $id");
      $result = $sql_sel->result();
      return $result;
    } 

    public function actualiza_enviadoNotaCredito($id, $claveacceso){
      $sql_sel = $this->db->query("SELECT count(*) as cant FROM notacreditoinfoestadosri WHERE idnotacredito = $id");
      $result = $sql_sel->result();
      if ($result[0]->cant == 0){
        $this->db->query("INSERT INTO notacreditoinfoestadosri (idnotacredito, secuencial, claveacesso, enviadosri, 
                                                            autorizado, rechazado)
                            SELECT $id, nro_documento, '$claveacceso', 1, 0, 0 
                              FROM notacredito WHERE id = $id");
      }
      else{
        $this->db->query("UPDATE notacreditoinfoestadosri set
                              claveacesso = '$claveacceso', 
                              enviadosri = 1, 
                              autorizado = 0, 
                              rechazado = 0
                            WHERE idnotacredito = $id");
      }
    } 

    public function actualiza_autorizadoNotaCredito($id, $fechaautorizo){
        $this->db->query("UPDATE notacreditoinfoestadosri set
                              fechaautorizacion = '$fechaautorizo', 
                              numeroautorizacion = claveacesso,
                              enviadosri = 1, 
                              autorizado = 1, 
                              rechazado = 0
                            WHERE idnotacredito = $id");
    } 

    public function actualiza_rechazadoNotaCredito($id, $claveacceso){
      $sql_sel = $this->db->query("SELECT count(*) as cant FROM notacreditoinfoestadosri WHERE idnotacredito = $id");
      $result = $sql_sel->result();
      if ($result[0]->cant == 0){
        $this->db->query("INSERT INTO notacreditoinfoestadosri (idnotacredito, secuencial, claveacesso, enviadosri, 
                                                            autorizado, rechazado)
                            SELECT $id, nro_documento, $claveacceso, 0, 0, 1 
                              FROM notacredito WHERE id = $id");
      }
      else{
        $this->db->query("UPDATE notacreditoinfoestadosri set
                              claveacesso = $claveacceso, 
                              enviadosri = 0, 
                              autorizado = 0, 
                              rechazado = 1
                            WHERE idnotacredito = $id");
      }
    } 

    public function sel_claveaccesoNotaCredito($id){
      $sql_sel = $this->db->query("SELECT claveacesso
                                    FROM notacreditoinfoestadosri
                                    WHERE idnotacredito = $id");
      $result = $sql_sel->result();
      if ($result)
        return $result[0]->claveacesso;
      else  
        return '';
    } 

    public function sel_logoempresanotacredito($id){
/*      $sql_sel = $this->db->query("SELECT logo_path 
                                     FROM empresa e 
                                     INNER JOIN sucursal s ON s.id_empresa = e.id_emp
                                     INNER JOIN notacredito c ON c.id_sucursal = s.id_sucursal 
                                     WHERE c.id = $id");*/
      $sql_sel = $this->db->query("SELECT logo_encab_path 
                                     FROM sucursal s 
                                     INNER JOIN notacredito c ON c.id_sucursal = s.id_sucursal 
                                     WHERE c.id = $id");
      $result = $sql_sel->result();
      if ($result)
        return $result[0]->logo_encab_path;
      else  
        return '';
    } 

      public function actualiza_correoenviadoNotacredito($id){
          $strfecha = date("d/m/Y  H:i:s"); 
          $this->db->query("UPDATE notacreditoinfoestadosri set
                                enviadoemail = '$strfecha'
                              WHERE idnotacredito = $id");
      } 


    // gUIA rEMISION
    public function lst_guiaremision_rango($desde, $hasta, $idusuario){
      $sql = $this->db->query("SELECT v.idguia, v.fechaemision, v.secuencial, 
                                      c.nom_cliente, c.ident_cliente, 
                                      v.fechaini, v.fechafin, d.numdocsustento,
                                      t.razonsocial as transportista,
                                      e.cod_establecimiento, e.cod_puntoemision, 
                                      case when s.idguia is null then 'NO ENVIADA' 
                                         else case
                                                when s.autorizado = 1 then 'AUTORIZADA'
                                                when s.rechazado = 1 then 'RECHAZADO'
                                                when s.autorizado = 0 AND enviadosri = 1 then 'ENVIADA'
                                              end
                                      end AS estado,
                                      s.claveacesso as claveacceso, s.fechaautorizacion, s.enviadoemail
                                  FROM sriguiaremisionencab v 
                                  INNER JOIN sriguiaremisiondestino d on d.idguia = v.idguia
                                  INNER JOIN clientes c ON c.id_cliente = d.iddestinatario
                                  INNER JOIN sritransportista t ON t.idtransportista = v.idtransportista
                                  INNER JOIN punto_emision e ON e.id_puntoemision = v.id_puntoemision 
                                  INNER JOIN permiso_sucursal p ON p.id_sucursal = e.id_sucursal AND
                                                                   p.id_usuario = $idusuario        
                                  LEFT JOIN guiaremisioninfoestadosri s ON s.idguia = v.idguia                                                                                              
                                  WHERE v.fechaemision BETWEEN '$desde' AND '$hasta' 
                                  ORDER BY v.fechaemision, v.secuencial");
      
      $resu = $sql->result();
      return $resu;
    }

    public function datosGuiaremision($id){
      $sel_obj = $this->db->query("SELECT c.nom_cliente, c.tipo_ident_cliente, c.ident_cliente, 
                                          c.direccion_cliente, c.correo_cliente, c.telefonos_cliente,
                                          v.secuencial, date(v.fechaemision) as fechaemision, 
                                          p.cod_establecimiento, p.cod_puntoemision, s.dir_sucursal, 
                                          t.razonsocial as razontransportista, t.cedula as ructransportista,
                                          it.codsri_venta as codidtransportista, 
                                          i.codsri_venta as codiddestinatario, 
                                          e.dir_emp, e.nom_emp, e.raz_soc_emp, e.ruc_emp,
                                          v.dirpartida, v.idtransportista,
                                          date(v.fechaini) as fechaini, date(v.fechafin) as fechafin, v.placa, 
                                          d.numdocsustento, d.iddestinatario, d.motivo, d.docaduanero, 
                                          d.codestabdestino, d.ruta, d.coddocsustento, d.numdocsustento, 
                                          d.numautdocsustento, d.fechaemidocsustento, d.dirllegada,
                                          s.id_empresa, p.ambiente_guia,
                                          e.obligadocontabilidad                                          
                                    FROM sriguiaremisionencab v 
                                    INNER JOIN sriguiaremisiondestino d on d.idguia = v.idguia
                                    INNER JOIN clientes c ON c.id_cliente = d.iddestinatario
                                    INNER JOIN punto_emision p ON p.id_puntoemision = v.id_puntoemision
                                    INNER JOIN sucursal s ON s.id_sucursal = p.id_sucursal
                                    INNER JOIN empresa e ON e.id_emp = s.id_empresa
                                    INNER JOIN identificacion i ON i.cod_identificacion = c.tipo_ident_cliente
                                    INNER JOIN sritransportista t ON t.idtransportista = v.idtransportista
                                    INNER JOIN identificacion it ON it.cod_identificacion = t.tipoid
                                    WHERE v.idguia = $id");
      $resultado = $sel_obj->result();
      return $resultado[0];
    }

    public function datosguiaremisiondetalle($id){
      $sql_sel = $this->db->query("SELECT CASE WHEN trim(codigointerno)='' THEN  
                                              CASE WHEN trim(pd.pro_codigoauxiliar)='' THEN pd.pro_codigobarra
                                                ELSE pd.pro_codigoauxiliar
                                              END        
                                            ELSE codigointerno 
                                          END as codigointerno, 
                                        CASE WHEN trim(codigoadicional)='' THEN  
                                          CASE WHEN trim(pd.pro_codigoauxiliar)='' THEN pd.pro_codigobarra
                                            ELSE pd.pro_codigoauxiliar
                                          END        
                                         ELSE codigoadicional 
                                        END as codigoadicional, 
                                        p.descripcion, p.cantidad 
                                    FROM sriguiaremisionproducto p
                                    INNER JOIN sriguiaremisiondestino d on d.iddestino = p.iddestino 
                                    INNER JOIN producto pd on pd.pro_id = p.idproducto
                                    WHERE idguia = $id");

      $result = $sql_sel->result();
      return $result;
    } 

    public function actualiza_enviadoGuiaremision($id, $claveacceso){
      $sql_sel = $this->db->query("SELECT count(*) as cant FROM guiaremisioninfoestadosri WHERE idguia = $id");
      $result = $sql_sel->result();
      if ($result[0]->cant == 0){
        $this->db->query("INSERT INTO guiaremisioninfoestadosri (idguia, secuencial, claveacesso, enviadosri, 
                                                            autorizado, rechazado)
                            SELECT $id, secuencial, '$claveacceso', 1, 0, 0 
                              FROM sriguiaremisionencab WHERE idguia = $id");
      }
      else{
        $this->db->query("UPDATE guiaremisioninfoestadosri set
                              claveacesso = '$claveacceso', 
                              enviadosri = 1, 
                              autorizado = 0, 
                              rechazado = 0
                            WHERE idguia = $id");
      }
    } 

    public function actualiza_autorizadoGuiaremision($id, $fechaautorizo){
        $this->db->query("UPDATE guiaremisioninfoestadosri set
                              fechaautorizacion = '$fechaautorizo', 
                              numeroautorizacion = claveacesso,
                              enviadosri = 1, 
                              autorizado = 1, 
                              rechazado = 0
                            WHERE idguia = $id");
    } 

    public function actualiza_rechazadoGuiaremision($id, $claveacceso){
      $sql_sel = $this->db->query("SELECT count(*) as cant FROM guiaremisioninfoestadosri WHERE idguia = $id");
      $result = $sql_sel->result();
      if ($result[0]->cant == 0){
        $this->db->query("INSERT INTO guiaremisioninfoestadosri (idguia, secuencial, claveacesso, enviadosri, 
                                                            autorizado, rechazado)
                            SELECT $id, secuencial, $claveacceso, 0, 0, 1 
                              FROM sriguiaremisionencab WHERE idguia = $id");
      }
      else{
        $this->db->query("UPDATE guiaremisioninfoestadosri set
                              claveacesso = $claveacceso, 
                              enviadosri = 0, 
                              autorizado = 0, 
                              rechazado = 1
                            WHERE idguia = $id");
      }
    } 

    public function sel_claveaccesoGuiaremision($id){
      $sql_sel = $this->db->query("SELECT claveacesso
                                    FROM guiaremisioninfoestadosri
                                    WHERE idguia = $id");
      $result = $sql_sel->result();
      if ($result)
        return $result[0]->claveacesso;
      else  
        return '';
    } 

    public function sel_logoempresaguia($id){
/*      $sql_sel = $this->db->query("SELECT logo_path 
                                     FROM empresa e 
                                     INNER JOIN sucursal s ON s.id_empresa = e.id_emp
                                     INNER JOIN punto_emision p ON p.id_sucursal = s.id_sucursal
                                     INNER JOIN sriguiaremisionencab c ON c.id_puntoemision = p.id_puntoemision 
                                     WHERE c.idguia = $id");*/
      $sql_sel = $this->db->query("SELECT logo_encab_path 
                                     FROM sucursal s 
                                     INNER JOIN punto_emision p ON p.id_sucursal = s.id_sucursal
                                     INNER JOIN sriguiaremisionencab c ON c.id_puntoemision = p.id_puntoemision 
                                     WHERE c.idguia = $id");
      $result = $sql_sel->result();
      if ($result)
        return $result[0]->logo_encab_path;
      else  
        return '';
    } 

      public function actualiza_correoenviadoGuia($id){
          $strfecha = date("d/m/Y  H:i:s"); 
          $this->db->query("UPDATE guiaremisioninfoestadosri set
                                enviadoemail = '$strfecha'
                              WHERE idguia = $id");
      } 

}