<?php

/* ------------------------------------------------
  ARCHIVO: Credito_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al Credito.
  FECHA DE CREACIÓN: 15/08/2017
 * 
  ------------------------------------------------ */

class Credito_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->db->query("SET time_zone = '-5:00';");
        $this->cheque_estado_creditos();
    }


    public function lst_creditos($sucursal, $cliente, $estado, $rango, $desde, $hasta){
      if ($desde == '') { $desde = 'NULL';}
      if ($hasta == '') { $hasta = 'NULL';}

      $sql = $this->db->query(" SELECT v.id_venta, v.fecha, v.nro_factura, v.montototal, 
                                       TRIM(REPLACE(REPLACE(REPLACE(c.nom_cliente,'\t',''),'\n',''),'\r','')) as nom_cliente,                                       
                                       TRIM(REPLACE(REPLACE(REPLACE(c.ident_cliente,'\t',''),'\n',''),'\r','')) as ident_cliente, 
                                       TRIM(REPLACE(REPLACE(REPLACE(c.direccion_cliente,'\t',''),'\n',''),'\r','')) as direccion_cliente, 
                                       c.correo_cliente, c.telefonos_cliente,
                                       r.id_estado, e.nombre_estado, r.fechalimite, r.dias, r.abonoinicial, 
                                       r.montointerescredito, r.montocredito, 
                                       IFNULL((SELECT SUM(valor_retencion_iva) FROM venta_retencion_detiva di 
                                                 INNER JOIN venta_retencion vr on vr.id_venta_ret = di.id_venta_ret
                                                 WHERE vr.id_venta = v.id_venta),0) +
                                       IFNULL((SELECT SUM(valor_retencion_renta) FROM venta_retencion_detrenta dr 
                                                 INNER JOIN venta_retencion vr on vr.id_venta_ret = dr.id_venta_ret
                                                 WHERE vr.id_venta = v.id_venta),0) as retencion,
                                       case when r.fechalimite < CURDATE() then 1 else 0 end as vencido,
                                       ((select sum(monto) from venta_formapago p
                                          where p.id_venta = v.id_venta) - r.abonoinicial) as abonado   
                                FROM venta v
                                INNER JOIN venta_credito r ON r.id_venta = v.id_venta
                                INNER JOIN clientes c ON c.id_cliente = v.id_cliente
                                INNER JOIN venta_estadocredito e on e.id_estado = r.id_estado
                                WHERE (($cliente = 0) or (v.id_cliente = $cliente))
                                  AND (($estado = 0) or (r.id_estado = $estado))
                                  AND (($sucursal = 0) or (v.id_sucursal = $sucursal))
                                  AND (($rango = 0) or (date(v.fecha) BETWEEN '$desde' AND '$hasta'))
                                ORDER BY v.fecha, v.nro_factura");
      $resu = $sql->result();
      return $resu;
    }

    /* Listado de las Areas con sus Mesas*/
    public function lst_estadocredito(){
      $sql = $this->db->query("SELECT id_estado, nombre_estado  
                                FROM venta_estadocredito
                                ORDER BY id_estado");
      $resultado = $sql->result();
      return $resultado;
    }    

    /* Listado de las Areas con sus Mesas*/
    public function lst_estadocredito_id($idestado){
      $sql = $this->db->query("SELECT id_estado, nombre_estado  
                                FROM venta_estadocredito
                                WHERE id_estado=$idestado");
      $resultado = $sql->result();
      return $resultado[0];
    }    

    public function total_creditos($sucursal, $cliente, $estado, $rango, $desde, $hasta){
      if ($desde == '') { $desde = 'NULL';}
      if ($hasta == '') { $hasta = 'NULL';}
      $sql = $this->db->query("SELECT sum(r.montocredito) as total, 
                                      sum(r.montocredito - ifnull((select sum(monto) from venta_formapago p
                                          left join venta_creditoabonoinicial i on i.id_abono = p.id
                                          where p.id_venta = v.id_venta and (i.id_abono is null)),0)) -
                                       IFNULL((SELECT SUM(valor_retencion_iva) FROM venta_retencion_detiva di 
                                                 INNER JOIN venta_retencion vr on vr.id_venta_ret = di.id_venta_ret
                                                 WHERE vr.id_venta = v.id_venta),0) -
                                       IFNULL((SELECT SUM(valor_retencion_renta) FROM venta_retencion_detrenta dr 
                                                 INNER JOIN venta_retencion vr on vr.id_venta_ret = dr.id_venta_ret
                                                 WHERE vr.id_venta = v.id_venta),0)
                                          as pendiente   
                                FROM venta v
                                INNER JOIN venta_credito r ON r.id_venta = v.id_venta
                                INNER JOIN clientes c ON c.id_cliente = v.id_cliente
                                INNER JOIN venta_estadocredito e on e.id_estado = r.id_estado
                                WHERE (($cliente = 0) or (v.id_cliente = $cliente))
                                  AND (($estado = 0) or (r.id_estado = $estado))
                                  AND (($sucursal = 0) or (v.id_sucursal = $sucursal))
                                  AND (($rango = 0) or (date(v.fecha) BETWEEN '$desde' AND '$hasta'))");
      $resu = $sql->result();
      return $resu;
    }

    /* LISTADO DE Abonos */
    public function lista_abonos($idventa){
      $sql = $this->db->query(" SELECT a.id, a.monto, a.fecha, a.id_formapago, 
                                       ifnull(a.nro_comprobante, '') as numero,
                                       f.nombre_formapago, i.id_abono as abonoinicial
                                FROM venta_formapago a 
                                inner join formapago f on f.id_formapago = a.id_formapago
                                left join venta_creditoabonoinicial i on i.id_abono = a.id
                                Where a.id_venta=$idventa");
      $resu = $sql->result();
      return $resu;
    }

    /* Busqueda de Abono */
    public function sel_abono($idabono){
      $sql = $this->db->query("SELECT a.id, a.id_venta, a.monto, a.fecha, a.id_formapago, 
                                      a.nro_comprobante, f.nombre_formapago 
                                FROM venta_formapago a 
                                inner join formapago f on f.id_formapago = a.id_formapago
                                Where a.id=$idabono");
      $result = $sql->result();
      return $result[0];
    }

    /* BUSQUEDA POR ID QUE PERMITE MOSTRAR EL GASTO PARA SER MODIFICADO */
    public function actualiza_estado_credito($idventa){
      $this->db->query("UPDATE venta_credito 
                          SET id_estado = case WHEN ifnull((select sum(a.monto) FROM venta_formapago a 
                                                              left join venta_creditoabonoinicial i on i.id_abono = a.id
                                                              where (i.id_abono is NULL) and a.id_venta=$idventa),0) +
                                                    IFNULL((SELECT SUM(valor_retencion_iva) FROM venta_retencion_detiva di 
                                                             INNER JOIN venta_retencion vr on vr.id_venta_ret = di.id_venta_ret
                                                             WHERE vr.id_venta = venta_credito.id_venta),0) +
                                                    IFNULL((SELECT SUM(valor_retencion_renta) FROM venta_retencion_detrenta dr 
                                                             INNER JOIN venta_retencion vr on vr.id_venta_ret = dr.id_venta_ret
                                                             WHERE vr.id_venta = venta_credito.id_venta),0) < montocredito
                                            then case when fechalimite <= CURDATE() then 3 else 1 end 
                                            else 2 
                                          end
                          WHERE id_venta = $idventa");
    }  

    /* BUSQUEDA POR ID QUE PERMITE MOSTRAR EL GASTO PARA SER MODIFICADO */
    public function sel_credito_id($idventa){
      $this->actualiza_estado_credito($idventa);
      $query = $this->db->query("SELECT v.id_venta, v.fecha, v.id_cliente, v.nro_factura, v.nom_cliente,
                                        v.dir_cliente, v.nro_ident, v.telf_cliente, v.id_sucursal,
                                        v.valiva, v.montoiva, v.montototal, c.dias, c.fechalimite, v.mesa, v.mesero,
                                        c.montocredito, c.abonoinicial, c.montointerescredito, 
                                        ifnull((select sum(a.monto) FROM venta_formapago a where a.id_venta=$idventa),0) as abonos,
                                        CONCAT(us.nom_usu,' ',us.ape_usu) AS cajero,
                                        IFNULL((SELECT SUM(valor_retencion_iva) FROM venta_retencion_detiva di 
                                                 INNER JOIN venta_retencion vr on vr.id_venta_ret = di.id_venta_ret
                                                 WHERE vr.id_venta = v.id_venta),0) +
                                        IFNULL((SELECT SUM(valor_retencion_renta) FROM venta_retencion_detrenta dr 
                                                 INNER JOIN venta_retencion vr on vr.id_venta_ret = dr.id_venta_ret
                                                 WHERE vr.id_venta = v.id_venta),0) as retencion                                         
                                   FROM venta_credito c
                                   INNER JOIN venta v on v.id_venta = c.id_venta
                                   INNER JOIN usu_sistemas us ON us.id_usu = v.idusu
                                  WHERE v.id_venta = $idventa");
      $result = $query->result();
      return $result[0];
    }

    /* Adicionar Abono */
    public function add_abonocredito0($idventa, $formapago, $monto){
        $sql = $this->db->query("INSERT INTO venta_formapago (id_venta, id_formapago, monto, fecha) 
                                   VALUES ($idventa, $formapago, $monto, now())");
        $venta = $this->sel_credito_id($idcompra);
        if ($venta->montocredito == $venta->abonos){
          $sql = $this->db->query("UPDATE venta_credito SET id_estado=2 WHERE id_venta = $idventa");          
        }
    }  

    /* Eliminar Abono */
    public function del_abonocredito($idabono, $idventa){
        $this->del_abonocuota($idabono);
        $sql = $this->db->query("DELETE FROM venta_formapagotarjeta WHERE id_abono = $idabono");
        $sql = $this->db->query("DELETE FROM venta_formapagobanco WHERE id_abono = $idabono");
        $sql = $this->db->query("DELETE FROM venta_formapago WHERE id = $idabono");
        $sql = $this->db->query("UPDATE venta_credito SET id_estado=1 WHERE id_venta = $idventa");
    }  

    /* LISTADO DE Cuotas */
    public function lista_cuotas($idventa){
      $sql = $this->db->query(" SELECT id, fechalimite, monto, pagado
                                FROM venta_creditocuota
                                Where id_venta=$idventa
                                order by fechalimite");
      $resu = $sql->result();
      return $resu;
    }

    public function selforpago($idforpago){
      $sql = $this->db->query("SELECT esinstrumentobanco AS banco, estarjeta AS tarjeta FROM formapago WHERE id_formapago = $idforpago");
      $resu = $sql->result();
      return $resu[0];
    }

    public function add_abonocredito($idventa, $fp, $monto, $fechat, $tiptarjeta, $nrotar, $banco, $tbanco, $nrodoc, $tnrodoc, $descdoc, $tdescdoc, $fechae, $fechac, $nrocta, $idcaja){
      if($fechat == NULL || $fechat == ""){}else{ $fecha = str_replace('/', '-', $fechat); $fechat = date("Y-m-d", strtotime($fecha)); } 
      if($fechae == NULL || $fechae == ""){}else{ $fecha = str_replace('/', '-', $fechae); $fechae = date("Y-m-d", strtotime($fecha)); } 
      if($fechac == NULL || $fechac == ""){}else{ $fecha = str_replace('/', '-', $fechac); $fechac = date("Y-m-d", strtotime($fecha)); } 

      $tfp = $this->selforpago($fp);
      $bco = $tfp->banco;
      $tarj = $tfp->tarjeta;
      $tipo = "";
      if($tarj == 1 && $bco == 0){ $tipo = "Tarjeta"; }
      if($tarj == 0 && $bco == 1){ $tipo = "Banco"; }
      if($tarj == 0 && $bco == 0){ $tipo = "Efectivo"; }

      $sql = $this->db->query("INSERT INTO venta_formapago (id_venta, id_formapago, monto, fecha, id_cajapago, nro_comprobante) 
                                 SELECT $idventa, $fp, $monto, now(), $idcaja,
                                        IFNULL((SELECT consecutivo_comprobpago FROM punto_emision p
                                                  INNER JOIN caja_efectivo c on c.id_puntoemision = p.id_puntoemision
                                                  WHERE c.id_caja = $idcaja), 1)");
      $sql = $this->db->query("select max(id) as id from venta_formapago;");
      $resu = $sql->result();
      $idabono = $resu[0]->id;
      $sql = $this->db->query("UPDATE punto_emision set consecutivo_comprobpago=ifnull(consecutivo_comprobpago,1)+1 
                                 WHERE id_puntoemision = (SELECT id_puntoemision FROM caja_efectivo WHERE id_caja = $idcaja);");

      switch($tipo) {
        case 'Tarjeta':
          $sqladd = $this->db->query("INSERT INTO venta_formapagotarjeta (id_abono, id_banco, id_tarjeta, numerotarjeta,
                                                                fechaemision, numerodocumento, descripciondocumento) 
                                                         VALUES ($idabono, $tbanco, $tiptarjeta, '$nrotar', '$fechat',
                                                                 '$tnrodoc', '$tdescdoc')");
        break;
        case 'Banco':
          $sqladd = $this->db->query("INSERT INTO venta_formapagobanco (id_abono, id_banco, numerocuenta, fechaemision,
                                                                 fechacobro, numerodocumento, descripciondocumento) 
                                                         VALUES ($idabono, $banco, '$nrocta', '$fechae', '$fechac', '$nrodoc', '$descdoc')");
        break;                  
        default:
      }      

      $venta = $this->sel_credito_id($idventa);
      if ($venta->montocredito == $venta->abonos){
        $sql = $this->db->query("UPDATE venta_credito SET id_estado=2 WHERE id_venta = $idventa");          
      }
      $this->add_abonocuota($idventa, $idabono, $monto);
      return $idabono;
    }

    public function upd_abonocredito($idreg, $idventa, $fp, $monto, $fechat, $tiptarjeta, $nrotar, $banco, $tbanco, $nrodoc, $tnrodoc, $descdoc, $tdescdoc, $fechae, $fechac, $nrocta, $idcaja){
      if($fechat == NULL || $fechat == ""){}else{ $fecha = str_replace('/', '-', $fechat); $fechat = date("Y-m-d", strtotime($fecha)); } 
      if($fechae == NULL || $fechae == ""){}else{ $fecha = str_replace('/', '-', $fechae); $fechae = date("Y-m-d", strtotime($fecha)); } 
      if($fechac == NULL || $fechac == ""){}else{ $fecha = str_replace('/', '-', $fechac); $fechac = date("Y-m-d", strtotime($fecha)); } 

      $tfp = $this->selforpago($fp);
      $bco = $tfp->banco;
      $tarj = $tfp->tarjeta;
      $tipo = "";
      if($tarj == 1 && $bco == 0){ $tipo = "Tarjeta"; }
      if($tarj == 0 && $bco == 1){ $tipo = "Banco"; }
      if($tarj == 0 && $bco == 0){ $tipo = "Efectivo"; }

      $this->del_abonocuota($idreg);

      $sqlupd = $this->db->query("UPDATE venta_formapago SET id_formapago = $fp, monto = $monto, id_cajapago = $idcaja
                                    WHERE id = $idreg");

      switch($tipo) {
        case 'Tarjeta':
          $sqladd = $this->db->query("UPDATE venta_formapagotarjeta SET
                                        id_banco = $tbanco, id_tarjeta = $tiptarjeta, 
                                        numerotarjeta = '$nrotar', fechaemision = '$fechat', 
                                        numerodocumento = '$tnrodoc', descripciondocumento = '$tdescdoc'
                                        WHERE id_abono = $idreg");
        break;
        case 'Banco':
          $sqladd = $this->db->query("UPDATE venta_formapagobanco SET 
                                        id_banco = $banco, numerocuenta = '$nrocta', 
                                        fechaemision = '$fechae', fechacobro = '$fechac',
                                        numerodocumento = '$nrodoc', descripciondocumento = '$descdoc'
                                          WHERE id_abono = $idreg");
        break;                  
        default:
      }      

      $venta = $this->sel_credito_id($idventa);
      if ($venta->montocredito == $venta->abonos){
        $sql = $this->db->query("UPDATE venta_credito SET id_estado=2 WHERE id_venta = $idventa");          
      }
      $this->add_abonocuota($idventa, $idreg, $monto);

    }


    public function ediforpagovent($idreg){
      $sql = $this->db->query("SELECT f.id as idreg, f.id_formapago, f.monto, f.fecha, f.nro_comprobante,
                                       numerocuenta, fechacobro, 
                                       case when b.id_abono is not null then b.id_banco
                                                       else case when t.id_abono is not null then t.id_banco
                                                             else null
                                                            end  
                                       end as id_banco, 
                                       case when b.id_abono is not null then b.fechaemision
                                                       else case when t.id_abono is not null then t.fechaemision
                                                             else null
                                                            end  
                                       end as fechaemision,  
                                       case when b.id_abono is not null then b.numerodocumento
                                                       else case when t.id_abono is not null then t.numerodocumento
                                                             else null
                                                            end  
                                       end as numerodocumento, 
                                       case when b.id_abono is not null then b.descripciondocumento
                                                               else case when t.id_abono is not null then t.descripciondocumento
                                                                     else null
                                                                    end  
                                       end as descripciondocumento,
                                       id_tarjeta, numerotarjeta, id_cajapago
                                  FROM venta_formapago f
                                  LEFT JOIN venta_formapagobanco b on b.id_abono = f.id
                                  LEFT JOIN venta_formapagotarjeta t on t.id_abono = f.id
                                  WHERE f.id = $idreg");
      $resu = $sql->result();
      return $resu[0];      
    }

    /* Adicionar Abono Cuota */
    public function add_abonocuota($idventa, $idabono, $monto){
      $sql = $this->db->query("SELECT id, (monto - ifnull(pagado,0)) as pendiente FROM venta_creditocuota 
                                  WHERE ifnull(pagado,0) <> monto AND id_venta = $idventa ORDER BY id");
      $resu = $sql->result();
      foreach ($resu as $row) {
        if ($monto > 0){
          $montoabonocuota = $monto;
          if ($montoabonocuota > $row->pendiente) $montoabonocuota = $row->pendiente;
          $sql = $this->db->query("UPDATE venta_creditocuota set pagado = ifnull(pagado,0) + $montoabonocuota
                                     WHERE id_venta = $idventa AND id = $row->id");
          $sql = $this->db->query("INSERT INTO venta_abonocreditocuota (id_cuota, id_abono, monto)
                                     VALUES($row->id, $idabono, $montoabonocuota)");          
          $monto-= $montoabonocuota;
        }
      }
    }  

    /* Adicionar Abono Cuota */
    public function del_abonocuota($idabono){
      $sql = $this->db->query("SELECT id_cuota, monto FROM venta_abonocreditocuota 
                                WHERE id_abono = $idabono");
      $resu = $sql->result();
      foreach ($resu as $row) {
          $sql = $this->db->query("UPDATE venta_creditocuota set pagado = ifnull(pagado,0) - $row->monto
                                     WHERE id = $row->id_cuota");
      }
      $sql = $this->db->query("DELETE FROM venta_abonocreditocuota WHERE id_abono = $idabono");          
    }  

    public function reciboabono($idabono){
      $sql = $this->db->query(" SELECT vc.fechalimite, vc.monto as montototal, va.id_cuota, va.id_abono, va.monto as montopago, (vc.monto - vc.pagado) as pendiente, vf.fecha as fechapago 
                                FROM venta_abonocreditocuota va
                                INNER JOIN venta_formapago vf ON vf.id = va.id_abono
                                INNER JOIN venta_creditocuota vc ON vc.id = va.id_cuota
                                WHERE va.id_abono = $idabono");
      $resu = $sql->result();
      return $resu;
    }

    public function lista_abonos_factura($idventa){
      $sql = $this->db->query(" SELECT v.nro_factura, date(v.fecha) as fechafactura, v.id_venta,
                                       v.id_cliente, v.observaciones, v.montototal, 
                                       a.id, a.monto, date(a.fecha) as fecha, a.id_formapago, 
                                       ifnull(a.nro_comprobante, '') as numero,
                                       f.nombre_formapago, i.id_abono as abonoinicial,
                                       IFNULL((SELECT SUM(pa.monto) FROM venta_formapago pa 
                                                 WHERE pa.id_venta = v.id_venta), 0) as pagado
                                FROM venta v 
                                LEFT JOIN venta_formapago a on a.id_venta = v.id_venta
                                LEFT JOIN formapago f on f.id_formapago = a.id_formapago
                                LEFT JOIN venta_creditoabonoinicial i on i.id_abono = a.id
                                Where v.id_venta=$idventa
                                order by v.fecha, a.fecha");
      $resu = $sql->result();
      return $resu;
    }

    public function lista_abonos_cliente_rango($idsucursal, $idcliente, $estado, $rango, $desde, $hasta){
      $sql = $this->db->query(" SELECT v.nro_factura, date(v.fecha) as fechafactura, v.id_venta,
                                       v.id_cliente, v.observaciones, v.montototal, v.id_sucursal,
                                       a.id, a.monto, date(a.fecha) as fecha, a.id_formapago, 
                                       ifnull(a.nro_comprobante, '') as numero,
                                       f.nombre_formapago, i.id_abono as abonoinicial,
                                       IFNULL((SELECT SUM(pa.monto) FROM venta_formapago pa 
                                                 WHERE pa.id_venta = v.id_venta), 0) as pagado
                                FROM venta v 
                                INNER JOIN venta_credito vc on vc.id_venta = v.id_venta
                                LEFT JOIN venta_formapago a on a.id_venta = v.id_venta
                                LEFT JOIN formapago f on f.id_formapago = a.id_formapago
                                LEFT JOIN venta_creditoabonoinicial i on i.id_abono = a.id
                                Where v.id_cliente = $idcliente 
                                  AND (($idsucursal = 0) or (v.id_sucursal = $idsucursal))
                                  AND (($estado = 0) or (vc.id_estado = $estado))
                                  AND (($rango = 0) or (date(v.fecha) BETWEEN '$desde' AND '$hasta'))
                                order by v.fecha, a.fecha");
      $resu = $sql->result();
      return $resu;
    }

    public function sel_creditos_fechamin($sucursal, $cliente, $estado){
      $sql = $this->db->query(" SELECT MIN(v.fecha) as fecha
                                FROM venta v
                                 INNER JOIN venta_credito r ON r.id_venta = v.id_venta
                                 WHERE (($cliente = 0) or (v.id_cliente = $cliente))
                                  AND (($estado = 0) or (r.id_estado = $estado))
                                  AND (($sucursal = 0) or (v.id_sucursal = $sucursal))");
      $resu = $sql->result();
      return $resu;
    }

    public function cheque_estado_creditos(){
      $sql = $this->db->query("SELECT count(*) as cant FROM venta_credito_config
                                 WHERE fecha_chequeo < CURDATE()");
      $resu = $sql->result();
      if ($resu[0]->cant > 0){
        $this->db->query("UPDATE venta_credito_config SET fecha_chequeo = CURDATE()");
        $this->db->query("UPDATE venta_credito 
                            SET id_estado = case when fechalimite <= CURDATE() then 3 else 1 end 
                            WHERE id_estado = 1");

      }
    }

}