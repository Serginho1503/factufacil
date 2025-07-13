<?php

/* ------------------------------------------------
  ARCHIVO: Reporte_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Reporte.
  FECHA DE CREACIÓN: 18/12/2017
 * 
  ------------------------------------------------ */

class Reporte_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function rol_modulo($id){
      $query = $this->db->query(" SELECT m.desc_mod_det, a.evento, a.accion 
                                  FROM modulos_detalles m
                                  INNER JOIN acceso a ON a.id_mod_det = m.id_mod_det
                                  WHERE id_usu = $id AND a.evento = 'ver' ");
      $r = $query->result();
      return $r;
    }


    public function lstutilidad($desde, $hasta){

      $sql = $this->db->query("SELECT v.fecha, v.nro_factura, v.nro_ident, 
                                  TRIM(REPLACE(REPLACE(REPLACE(v.nom_cliente,'\t',''),'\n',''),'\r','')) as nom_cliente,
                                  TRIM(REPLACE(REPLACE(REPLACE(p.pro_nombre,'\t',''),'\n',''),'\r','')) as pro_nombre,
                                  vd.cantidad, c.categoria,
                                  p.pro_preciocompra AS costo, vd.precio, 
                                  ROUND((p.pro_preciocompra * vd.cantidad),4) AS costo_total,
                                  ROUND(vd.montoiva,2) AS montoiva, 
                                  ROUND(vd.descsubtotal + vd.montoiva,2) AS precioiva, 
                                  ROUND(vd.descmonto,2) AS descuento, ROUND(vd.descsubtotal,2) AS precio_total, 
                                  ROUND((vd.descsubtotal - (p.pro_preciocompra * vd.cantidad)),2) AS utilidad_bruta,
                                  ROUND(((vd.descsubtotal / (p.pro_preciocompra * vd.cantidad) - 1 )* 100),2) AS utilidad_porc,
                                  IFNULL((SELECT CASE f.id_formapago WHEN 3 THEN comision_credito ELSE comision_debito END 
                                            FROM tarjetas t
                                            INNER JOIN venta_formapagotarjeta ft on ft.id_tarjeta = t.id_tarjeta 
                                            INNER JOIN venta_formapago f on f.id = ft.id_abono 
                                            WHERE f.id_venta = v.id_venta 
                                            ORDER BY f.monto DESC LIMIT 1), 0) as porciento_comision
                                FROM venta_detalle vd
                                INNER JOIN venta v ON v.id_venta = vd.id_venta
                                INNER JOIN contador c ON c.id_contador = v.tipo_doc
                                INNER JOIN producto p ON p.pro_id = vd.id_producto
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3");
      $resu = $sql->result();
      return $resu;
    }
    public function lstutilidad00($desde, $hasta){
      $sql = $this->db->query(" SELECT v.fecha, v.nro_factura, v.nro_ident, v.nom_cliente, 
                                  p.pro_nombre, vd.cantidad, c.categoria,
                                  p.pro_preciocompra AS costo, vd.precio, 
                                  ROUND((p.pro_preciocompra * vd.cantidad),4) AS costo_total,
                                  ROUND(vd.montoiva,2) AS montoiva, 
                                  ROUND(vd.descsubtotal + vd.montoiva,2) AS precioiva, /*ROUND((vd.precio + vd.montoiva),4) AS precioiva, */
                                  ROUND(vd.descmonto,2) AS descuento, ROUND(vd.descsubtotal,2) AS precio_total, 
                                  ROUND((vd.descsubtotal - (p.pro_preciocompra * vd.cantidad)),2) AS utilidad_total,
                                  ROUND(((vd.descsubtotal / (p.pro_preciocompra * vd.cantidad) - 1 )* 100),2) AS utilidad_porc
                                FROM venta_detalle vd
                                INNER JOIN venta v ON v.id_venta = vd.id_venta
                                INNER JOIN contador c ON c.id_contador = v.tipo_doc
                                INNER JOIN producto p ON p.pro_id = vd.id_producto
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3");


/*
      $sql = $this->db->query(" SELECT v.fecha, v.nro_factura, v.nro_ident, v.nom_cliente, p.pro_nombre, vd.cantidad,
                                  p.pro_preciocompra AS costo, 
                                  (case v.tipo_doc when 2 then vd.precio else 
                                     ROUND(vd.precio * (case vd.iva when 0 then 1 else ifnull((select 1+valor from parametros where id=1),1.12) end),4)
                                   end) as precio, 
                                  ROUND((p.pro_preciocompra * vd.cantidad),4) AS costo_total,
                                  ROUND(vd.montoiva,2) AS montoiva, 
                                  ROUND(vd.precio * (case vd.iva when 0 then 1 else ifnull((select 1+valor from parametros where id=1),1.12) end),4) AS precioiva, 
                                  ROUND(vd.descmonto,2) AS descuento, 
                                  (case v.tipo_doc when 2 then vd.descsubtotal else 
                                     ROUND(vd.descsubtotal * (case vd.iva when 0 then 1 else ifnull((select 1+valor from parametros where id=1),1.12) end),4)
                                   end) as precio_total, 
                                  ROUND(((case v.tipo_doc when 2 then vd.descsubtotal else 
                                     ROUND(vd.descsubtotal * (case vd.iva when 0 then 1 else ifnull((select 1+valor from parametros where id=1),1.12) end),4)
                                   end) - (p.pro_preciocompra * vd.cantidad)),2) AS utilidad_total,
                                  case p.pro_preciocompra * vd.cantidad when 0 then 0 else
                                  ROUND((((case v.tipo_doc when 2 then vd.descsubtotal else 
                                     ROUND(vd.descsubtotal * (case vd.iva when 0 then 1 else ifnull((select 1+valor from parametros where id=1),1.12) end),4)
                                   end) / (p.pro_preciocompra * vd.cantidad) - 1 )* 100),2) end AS utilidad_porc
                                FROM venta_detalle vd
                                INNER JOIN venta v ON v.id_venta = vd.id_venta
                                INNER JOIN producto p ON p.pro_id = vd.id_producto
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3");
      */
      $resu = $sql->result();
      return $resu;
    }


    public function lst_ats_compra($idempresa, $desde, $hasta){

      $sql = $this->db->query("SELECT c.id_comp, sc.cod_sri_sust_comprobante, i.codsri_compra, c.fecha as fecha,
                                       p.nro_ide_proveedor, p.nom_proveedor, 
                                       case  p.relacionada when 1 then 'SI' else 'NO' end as relacionada,
                                       td.cod_sri_tipo_doc, 
                                       concat(lpad(day(c.fecha),2,'0'),'/',lpad(month(c.fecha),2,'0'),'/',lpad(year(c.fecha),4,'0')) as fecharegistro, 
                                       substr(c.nro_factura,1,3) as codestabfac, 
                                       substr(c.nro_factura,5,3) as codptoemifac, substr(c.nro_factura,9) as secuencialfac, 
                                       concat(lpad(day(c.fecha),2,'0'),'/',lpad(month(c.fecha),2,'0'),'/',lpad(year(c.fecha),4,'0')) as fechaemision, 
                                       c.nro_autorizacion, 0 as basenograbaiva, 
                                       c.descsubsiniva as baseimponible, (c.descsubconiva + ifnull(c.montoice, 0)) as baseimpgrav, 
                                       ifnull(c.montoice, 0) as montoice, c.montoiva,
                                       ifnull((SELECT i.valor_retencion_iva FROM compra_retencion_detiva i
                                                 WHERE i.id_comp_ret = r.id_comp_ret and i.porciento_retencion_iva = 10),0) as retiva10,
                                       ifnull((SELECT i.valor_retencion_iva FROM compra_retencion_detiva i
                                                 WHERE i.id_comp_ret = r.id_comp_ret and i.porciento_retencion_iva = 20),0) as retiva20,
                                       ifnull((SELECT i.valor_retencion_iva FROM compra_retencion_detiva i
                                                 WHERE i.id_comp_ret = r.id_comp_ret and i.porciento_retencion_iva = 30),0) as retiva30,
                                       ifnull((SELECT i.valor_retencion_iva FROM compra_retencion_detiva i
                                                 WHERE i.id_comp_ret = r.id_comp_ret and i.porciento_retencion_iva = 50),0) as retiva50,
                                       ifnull((SELECT i.valor_retencion_iva FROM compra_retencion_detiva i
                                                 WHERE i.id_comp_ret = r.id_comp_ret and i.porciento_retencion_iva = 70),0) as retiva70,
                                       ifnull((SELECT i.valor_retencion_iva FROM compra_retencion_detiva i
                                                 WHERE i.id_comp_ret = r.id_comp_ret and i.porciento_retencion_iva = 100),0) as retiva100,
                                       cr.cod_cto_retencion as codretrenta, (rr.base_iva + rr.base_noiva) as baseretrenta, 
                                       rr.porciento_retencion_renta, rr.valor_retencion_renta,
                                       substr(r.nro_retencion,1,3) as codestabret, substr(r.nro_retencion,5,3) as codptoemiret, 
                                       substr(r.nro_retencion,9) as secuencialret, 
                                       /*r.nro_autorizacion as autorizacionret, */
                                       ifnull(sric.numeroautorizacion, r.nro_autorizacion) as autorizacionret, 
                                       concat(lpad(day(r.fecha_retencion),2,'0'),'/',lpad(month(r.fecha_retencion),2,'0'),'/',lpad(year(r.fecha_retencion),4,'0')) as fecha_retencion,
                                       c.doc_mod_cod_sri_tipo, c.doc_mod_numero, c.doc_mod_autorizacion
                                  FROM compra c 
                                  INNER JOIN sucursal s on s.id_sucursal = c.id_sucursal
                                  INNER JOIN sri_tipo_doc td on td.id_sri_tipo_doc = c.cod_sri_tipo_doc
                                  INNER JOIN sri_sust_comprobante sc on sc.id_sri_sust_comprobante = c.cod_sri_sust_comprobante
                                  INNER JOIN proveedor p on p.id_proveedor = c.id_proveedor
                                  INNER JOIN identificacion i on i.cod_identificacion = p.tip_ide_proveedor
                                  LEFT JOIN compra_retencion r on r.id_compra = c.id_comp
                                  LEFT JOIN compra_retencion_detrenta rr on rr.id_comp_ret = r.id_comp_ret
                                  LEFT JOIN concepto_retencion cr on cr.id_cto_retencion = rr.id_concepto_retencion
                                  LEFT JOIN retencioninfoestadosri sric on sric.idretencion = r.id_comp_ret
                                  WHERE s.id_empresa = $idempresa AND c.fecha BETWEEN '$desde' AND '$hasta' AND c.estatus != 3
                                
                                UNION

                                SELECT g.id_gastos, sc.cod_sri_sust_comprobante, i.codsri_compra, g.fecha as fecha,
                                       p.nro_ide_proveedor, p.nom_proveedor, 
                                       case  p.relacionada when 1 then 'SI' else 'NO' end as relacionada,
                                       td.cod_sri_tipo_doc, 
                                       concat(lpad(day(g.fecha),2,'0'),'/',lpad(month(g.fecha),2,'0'),'/',lpad(year(g.fecha),4,'0')) as fecharegistro, 
                                       substr(g.nro_factura,1,3) as codestabfac, 
                                       substr(g.nro_factura,5,3) as codptoemifac, substr(g.nro_factura,9) as secuencialfac, 
                                       concat(lpad(day(g.fecha),2,'0'),'/',lpad(month(g.fecha),2,'0'),'/',lpad(year(g.fecha),4,'0')) as fechaemision, 
                                       g.nro_autorizacion, 0 as basenograbaiva, 
                                       g.subtotalivacerodesc as baseimponible, (g.subtotaldesc) as baseimpgrav, 
                                       0 as montoice, g.montoiva,
                                       ifnull((SELECT i.valor_retencion_iva FROM gastos_retencion_detiva i
                                                 WHERE i.id_gastos_ret = r.id_gastos_ret and i.porciento_retencion_iva = 10),0) as retiva10,
                                       ifnull((SELECT i.valor_retencion_iva FROM gastos_retencion_detiva i
                                                 WHERE i.id_gastos_ret = r.id_gastos_ret and i.porciento_retencion_iva = 20),0) as retiva20,
                                       ifnull((SELECT i.valor_retencion_iva FROM gastos_retencion_detiva i
                                                 WHERE i.id_gastos_ret = r.id_gastos_ret and i.porciento_retencion_iva = 30),0) as retiva30,
                                       ifnull((SELECT i.valor_retencion_iva FROM gastos_retencion_detiva i
                                                 WHERE i.id_gastos_ret = r.id_gastos_ret and i.porciento_retencion_iva = 50),0) as retiva50,
                                       ifnull((SELECT i.valor_retencion_iva FROM gastos_retencion_detiva i
                                                 WHERE i.id_gastos_ret = r.id_gastos_ret and i.porciento_retencion_iva = 70),0) as retiva70,
                                       ifnull((SELECT i.valor_retencion_iva FROM gastos_retencion_detiva i
                                                 WHERE i.id_gastos_ret = r.id_gastos_ret and i.porciento_retencion_iva = 100),0) as retiva100,
                                       cr.cod_cto_retencion as codretrenta, (rr.base_iva + rr.base_noiva) as baseretrenta, 
                                       rr.porciento_retencion_renta, rr.valor_retencion_renta,
                                       substr(r.nro_retencion,1,3) as codestabret, substr(r.nro_retencion,5,3) as codptoemiret, 
                                       substr(r.nro_retencion,9) as secuencialret, 
                                       /*r.nro_autorizacion as autorizacionret, */
                                       ifnull(srig.numeroautorizacion, r.nro_autorizacion) as autorizacionret, 
                                       concat(lpad(day(r.fecha_retencion),2,'0'),'/',lpad(month(r.fecha_retencion),2,'0'),'/',lpad(year(r.fecha_retencion),4,'0')) as fecha_retencion,
                                       g.doc_mod_cod_sri_tipo, g.doc_mod_numero, g.doc_mod_autorizacion
                                  FROM gastos g 
                                  INNER JOIN sucursal s1 on s1.id_sucursal = g.id_sucursal
                                  INNER JOIN sri_tipo_doc td on td.id_sri_tipo_doc = g.cod_sri_tipo_doc
                                  INNER JOIN sri_sust_comprobante sc on sc.id_sri_sust_comprobante = g.cod_sri_sust_comprobante
                                  INNER JOIN proveedor p on p.id_proveedor = g.id_proveedor
                                  INNER JOIN identificacion i on i.cod_identificacion = p.tip_ide_proveedor
                                  LEFT JOIN gastos_retencion r on r.id_gastos = g.id_gastos
                                  LEFT JOIN gastos_retencion_detrenta rr on rr.id_gastos_ret = r.id_gastos_ret
                                  LEFT JOIN concepto_retencion cr on cr.id_cto_retencion = rr.id_concepto_retencion
                                  LEFT JOIN retenciongastoinfoestadosri srig on srig.idretencion = r.id_gastos_ret
                                  WHERE s1.id_empresa = $idempresa AND g.fecha BETWEEN '$desde' AND '$hasta' AND g.estatus != 3

                                  ORDER BY fecha");


      $resu = $sql->result();
      return $resu;
    }

    public function lst_ats_venta($idempresa, $desde, $hasta){
      $sql = $this->db->query("SELECT i.codsri_venta, c.ident_cliente, c.nom_cliente, v.id_cliente,
                                     (case c.relacionado when 1 then 'SI' else 'NO' end) as parteRel, 
                                     18 as tipocomprobante, count(v.id_venta) as numeroComprobantes,
                                     0 as baseNoGraIva, sum(v.descsubsiniva) as baseImponible, 
                                     sum(v.descsubconiva) as baseImpGrav, sum(v.montoiva) as montoiva, 
                                     sum(ifnull((SELECT sum(dr.valor_retencion_renta) FROM venta_retencion_detrenta dr
                                              INNER JOIN venta_retencion r on r.id_venta_ret = dr.id_venta_ret
                                              WHERE r.id_venta = v.id_venta),0)) as valorRetRenta,
                                     sum(ifnull((SELECT sum(dr.valor_retencion_iva) FROM venta_retencion_detiva dr
                                              INNER JOIN venta_retencion r on r.id_venta_ret = dr.id_venta_ret
                                              WHERE r.id_venta = v.id_venta),0)) as valorRetIva 
                                FROM venta v
                                INNER JOIN clientes c on c.id_cliente = v.id_cliente
                                INNER JOIN identificacion i on i.cod_identificacion = c.tipo_ident_cliente
                                WHERE v.id_empresa = $idempresa AND v.fecha BETWEEN '$desde' AND '$hasta' 
                                  AND v.estatus != 3 AND v.tipo_doc = 2
                                GROUP BY i.codsri_venta, c.ident_cliente, c.nom_cliente
                                ORDER BY c.nom_cliente");

      $resu = $sql->result();
      return $resu;
    }

    public function lst_ats_anulados($idempresa, $desde, $hasta){
      $sql = $this->db->query("SELECT '01' as tipocomprobante, v.nro_factura, 
                                      ifnull(f.numeroautorizacion,'') as numeroautorizacion
                                FROM venta v
                                LEFT JOIN facturainfoestadosri f on f.idfactura = v.id_venta
                                WHERE v.id_empresa = $idempresa AND v.fecha BETWEEN '$desde' AND '$hasta' 
                                  AND v.estatus = 3 AND v.tipo_doc = 2
                                ORDER BY v.nro_factura");

      $resu = $sql->result();
      return $resu;
    }

    public function lst_ats_ventaformago($idempresa, $cliente, $desde, $hasta){
      $sql = $this->db->query("SELECT DISTINCT f.id_formapago, f.cod_formapago
                                FROM venta_formapago p
                                INNER JOIN venta v on v.id_venta = p.id_venta
                                INNER JOIN formapago f on f.id_formapago = p.id_formapago
                                WHERE v.id_empresa = $idempresa AND v.fecha BETWEEN '$desde' AND '$hasta' AND 
                                      v.estatus != 3 AND v.id_cliente = $cliente");
      $resu = $sql->result();
      return $resu;
    }

    public function lst_ats_general($idempresa, $desde, $hasta){
      $sql = $this->db->query("SELECT ruc_emp, raz_soc_emp, 
                                      year('$desde') as anio, month('$desde') as mes,
                                      /*(SELECT count(*) from sucursal WHERE id_empresa = $idempresa) as num_estab,*/
                                      (SELECT count(*) FROM
                                        (SELECT distinct cod_establecimiento 
                                          FROM venta v, punto_emision p, sucursal s
                                          WHERE s.id_empresa = $idempresa AND s.id_sucursal=p.id_sucursal AND
                                                v.id_puntoemision = p.id_puntoemision AND v.estatus != 3 AND 
                                                v.fecha BETWEEN '$desde' AND '$hasta'
                                         UNION
                                         SELECT distinct cod_establecimiento 
                                          FROM compra_retencion v, punto_emision p, sucursal s
                                          WHERE s.id_empresa = 1 AND s.id_sucursal=p.id_sucursal AND
                                                v.id_puntoemision = p.id_puntoemision AND 
                                                v.fecha_retencion BETWEEN '$desde' AND '$hasta'
                                         UNION
                                         SELECT distinct cod_establecimiento 
                                          FROM gastos_retencion v, punto_emision p, sucursal s
                                          WHERE s.id_empresa = 1 AND s.id_sucursal=p.id_sucursal AND
                                                v.id_puntoemision = p.id_puntoemision AND 
                                                v.fecha_retencion BETWEEN '$desde' AND '$hasta'
                                          ) as estab) as num_estab,
                                      (SELECT count(distinct cod_establecimiento) 
                                        FROM punto_emision p, sucursal s
                                        WHERE s.id_empresa = $idempresa AND 
                                              s.id_sucursal=p.id_sucursal) as num_estab00,
                                      ifnull((SELECT sum(descsubsiniva + descsubconiva) FROM venta 
                                                WHERE fecha BETWEEN '$desde' AND '$hasta' AND estatus != 3),0) as totalsinimpventas,
                                      ifnull((SELECT sum(montototal) FROM venta 
                                                WHERE fecha BETWEEN '$desde' AND '$hasta' AND estatus != 3),0) as totalventas,
                                      ifnull((select cod_establecimiento from punto_emision order by 1 limit 1),'001') as cod_estab                                                
                                 FROM empresa 
                                 WHERE id_emp = $idempresa;");
      $resu = $sql->result();
      return $resu[0];
    }

    public function lst_ats_ventaestab($idempresa, $desde, $hasta){
      $sql = $this->db->query("SELECT p.cod_establecimiento, sum(v.descsubsiniva + v.descsubconiva) as totalventas
                                FROM venta v
                                INNER JOIN punto_emision p on p.id_puntoemision = v.id_puntoemision
                                WHERE id_empresa = $idempresa AND estatus != 3 AND 
                                      fecha BETWEEN '$desde' AND '$hasta' 
                                GROUP BY p.cod_establecimiento
                                ORDER BY p.cod_establecimiento;");
      $resu = $sql->result();
      return $resu;
    }


// Cadillac
    /* LISTADO DE VENTAS POR COCINA */
    public function venta_cocina($desde, $hasta){
      $sql = $this->db->query(" SELECT ROUND(SUM(vd.descsubtotal + vd.montoiva),2) AS total  
                                FROM venta v
                                INNER JOIN venta_detalle vd ON vd.id_venta = v.id_venta
                                INNER JOIN producto p ON p.pro_id = vd.id_producto
                                INNER JOIN clasificacion c ON c.id_cla = p.idcla
                                WHERE p.idcla = 1 and v.estatus != 3
                                AND fecharegistro BETWEEN '$desde' AND '$hasta'");
      $result = $sql->result();
      $cocina = 0;
      if ($result[0]->total != null) $cocina = $result[0]->total;
      return $cocina;
    }

    /* LISTADO DE VENTAS POR BARRA */
    public function venta_barra($desde, $hasta){
      $sql = $this->db->query(" SELECT ROUND(SUM(vd.descsubtotal + vd.montoiva),2) AS total  
                                FROM venta v
                                INNER JOIN venta_detalle vd ON vd.id_venta = v.id_venta
                                INNER JOIN producto p ON p.pro_id = vd.id_producto
                                INNER JOIN clasificacion c ON c.id_cla = p.idcla
                                WHERE p.idcla = 2 and v.estatus != 3
                                AND fecharegistro BETWEEN '$desde' AND '$hasta'");
      $result = $sql->result();
      $barra = 0;
      if ($result[0]->total != null) $barra = $result[0]->total;
      return $barra;
    }

    /* LISTADO DE VENTAS POR BARRA */
    public function resumen_gastos($desde, $hasta){
      $sql = $this->db->query("call cierre_gastoscompras('$desde', '$hasta');");
      $resu = $sql->result();
      $resu = $resu[0];

      $sql->next_result(); 
      $sql->free_result();

      return $resu;
    }

    public function resumen_mantserv($desde, $hasta){
      $sql = $this->db->query("call cierre_mantserv('$desde', '$hasta');");
      $resu = $sql->result();
      $sql->next_result(); 
      $sql->free_result();

      return $resu;
    }

    /* LISTADO DE VENTAS POR BARRA */
    public function lst_socio(){
      $sql = $this->db->query(" SELECT id, nombre FROM socio");
      $resu = $sql->result();
      return $resu;
    }
/*
    public function lst_categorias(){
      $sql = $this->db->query("SELECT id_cat_gas, nom_cat_gas FROM gastos_categorias ORDER BY nom_cat_gas ASC");
      $resu = $sql->result();
      return $resu;
    }
*/
    public function lst_categorias(){
      $sql = $this->db->query(" SELECT id_cat_gas, nom_cat_gas 
                                FROM gastos_categorias 
                                WHERE id_cat_gas NOT IN (SELECT id_categoria FROM cierre_categorias)
                                ORDER BY nom_cat_gas ASC ");
      $resu = $sql->result();
      return $resu;
    }

    public function lstpc(){
      $sql = $this->db->query(" SELECT cc.id_parametro, gc.nom_cat_gas, cc.id_categoria
                                FROM cierre_categorias cc
                                INNER JOIN gastos_categorias gc ON gc.id_cat_gas = cc.id_categoria
                                ORDER BY cc.id_parametro ASC");
      $resu = $sql->result();
      return $resu;
    }

    public function catcadd($id, $cat){
      $sql = $this->db->query(" INSERT INTO cierre_categorias (id_parametro, id_categoria) VALUES ($id, $cat)");
    }

    public function delcadd($id, $cat){
      $sql = $this->db->query(" DELETE FROM cierre_categorias WHERE id_parametro = $id AND id_categoria = $cat ");
    }
    
}
