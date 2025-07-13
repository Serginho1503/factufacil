<?php

/* ------------------------------------------------
  ARCHIVO: Garantia_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Garantia.
  FECHA DE CREACIÓN: 16/10/2018
 * 
  ------------------------------------------------ */

class Garantia_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function lst_garantiadevolucion($desde, $hasta, $cliente = 0){
      $sql = $this->db->query("SELECT g.id, g.fecha, g.nrodevolucion, g.idsucursal,
                                      c.nom_cliente, c.ident_cliente 
                                FROM devolucion_garantia g 
                                INNER JOIN clientes c ON c.id_cliente = g.idcliente
                                WHERE g.fecha BETWEEN '$desde' AND '$hasta' AND
                                      ($cliente = 0 OR g.idcliente = $cliente)
                                ORDER BY g.fecha desc, g.nrodevolucion");
      $resu = $sql->result();
      return $resu;
    }

    public function sel_numerodevoluciongarantia($sucursal = 0){
      $sql = $this->db->query("SELECT consecutivo_devoluciongarantia
                                FROM sucursal  
                                WHERE id_sucursal = $sucursal");
      $resu = $sql->result();
      if ($resu)
        return $resu[0]->consecutivo_devoluciongarantia;
      else
        return 1;
    }

    public function lst_productogarantiacliente($cliente = 0){
      $sql = $this->db->query("SELECT d.id_detalle, v.fecha, v.nro_factura, d.descripcion, d.precio, 
                                      s.id_serie, s.numeroserie, g.fec_desde, g.fec_hasta, g.dias_gar,
                                      s.id_detalleventa, d.id_producto
                                FROM venta_detalle d
                                INNER JOIN producto_serie s on s.id_detalleventa = d.id_detalle
                                INNER JOIN garantia g on g.idserie = s.id_serie AND g.estatus = 1
                                INNER JOIN venta v on v.id_venta = d.id_venta
                                WHERE v.id_cliente = $cliente AND g.fec_hasta >= date(now())
                                ORDER BY v.fecha desc, v.nro_factura, d.descripcion");
      $resu = $sql->result();
      return $resu;
    }

    public function lst_productogarantia($cliente, $garantia){
      if ($garantia == '') { $garantia = 0;}
      $sql = $this->db->query("SELECT d.id_detalle, v.fecha, v.nro_factura, d.descripcion, d.precio, 
                                      s.id_serie, s.numeroserie, g.fec_desde, g.fec_hasta, g.dias_gar,
                                      s.id_detalleventa, d.id_producto,
                                      c.nom_cliente, c.ident_cliente
                                FROM venta_detalle d
                                INNER JOIN producto_serie s on s.id_detalleventa = d.id_detalle
                                INNER JOIN garantia g on g.idserie = s.id_serie AND g.estatus = 1
                                INNER JOIN venta v on v.id_venta = d.id_venta
                                INNER JOIN clientes c on c.id_cliente = v.id_cliente
                                WHERE ($cliente = 0 OR v.id_cliente = $cliente) 
                                  AND ($garantia = 0 OR g.fec_hasta >= date(now()))
                                ORDER BY v.fecha desc, v.nro_factura, d.descripcion");
      $resu = $sql->result();
      return $resu;
    }

    public function lst_seriesdisponibles($producto = 0){
      $sql = $this->db->query("SELECT s.id_serie, s.id_producto, s.numeroserie, s.descripcion, s.fechaingreso, 
                                      p.pro_garantia, a.almacen_nombre
                                FROM producto_serie s
                                INNER JOIN producto p on p.pro_id = s.id_producto
                                INNER JOIN almacen a on a.almacen_id = s.id_almacen
                                WHERE s.id_estado in (1,6) AND p.pro_id = $producto AND s.id_detalleventa IS NULL
                                ORDER BY s.numeroserie");
      $resu = $sql->result();
      return $resu;
    }
    
    public function sel_serie_id($idserie){
      $sql = $this->db->query("SELECT s.id_serie, s.id_producto, s.numeroserie, s.descripcion, s.fechaingreso, p.pro_garantia
                                FROM producto_serie s
                                INNER JOIN producto p on p.pro_id = s.id_producto
                                WHERE s.id_serie = $idserie");
      $resu = $sql->result();
      if ($resu)
        return $resu[0];
      else
        return null;
    }

    public function ins_garantiadevolucion($fecha, $sucursal, $nrodevolucion, $idcliente, $idusuario, $listaserie){
      $this->db->query("INSERT INTO devolucion_garantia (fecha, idsucursal, nrodevolucion, idcliente, idusuario)
                          VALUES('$fecha', $sucursal, '$nrodevolucion', $idcliente, $idusuario)");
      $sql = $this->db->query("SELECT max(id) as maxid FROM devolucion_garantia");
      $resu = $sql->result();
      $maxid = 0;
      if ($resu){
        $maxid = $resu[0]->maxid;
        foreach($listaserie as $item){
          $serieentregada = $item->id_serieentregada;
          if ($serieentregada == '') { $serieentregada = 'NULL';}
          $this->db->query("INSERT INTO devolucion_garantia_detalle (iddevolucion, idventa, idserie, idalmacen, observaciones, 
                                                                     idserie_reposicion, iddetalleventa, diasgarantia)
                              SELECT $maxid, id_venta, $item->id_seriedevuelta, $item->id_almacen, '$item->observaciones', 
                                     $serieentregada, id_detalle, $item->diasgarantia
                                FROM venta_detalle WHERE id_detalle = $item->id_detalle");
          $this->db->query("UPDATE producto_serie 
                              SET id_detalleventa = null, 
                                  id_estado = 3, 
                                  id_almacen = $item->id_almacen
                              WHERE id_serie = $item->id_seriedevuelta;");
          $this->db->query("UPDATE garantia 
                              SET estatus = 0
                              WHERE idserie = $item->id_seriedevuelta;");

          $this->db->query("UPDATE sucursal 
                              SET consecutivo_devoluciongarantia = consecutivo_devoluciongarantia + 1
                              WHERE id_sucursal = $sucursal");

        }
      }
      return $maxid;
    }

    public function upd_garantiadevolucion_detalle($iddevolucion, $detalleventa, $seriedevuelta, $idmoventrada, $idmovsalida){
        $this->db->query("UPDATE devolucion_garantia_detalle 
                            SET iddoc_entradaalmacen = $idmoventrada,
                                iddoc_salidaalmacen = $idmovsalida
                            WHERE iddevolucion = $iddevolucion AND idserie = $seriedevuelta");
        $this->db->query("UPDATE producto_serie s
                            INNER JOIN devolucion_garantia_detalle d on d.idserie_reposicion = s.id_serie
                            SET id_detalleventa = $detalleventa, 
                                id_estado = 4
                              WHERE d.iddevolucion = $iddevolucion AND d.idserie = $seriedevuelta;");
        $this->db->query("DELETE FROM garantia 
                            WHERE idserie = (SELECT idserie_reposicion FROM devolucion_garantia_detalle WHERE idserie = $seriedevuelta) AND 
                                  idventa = (SELECT id_venta FROM venta_detalle WHERE id_detalle = $detalleventa)");
        $this->db->query("INSERT INTO garantia (idventa, idserie, fec_desde, fec_hasta, dias_gar, estatus)
                            SELECT d.id_venta, g.idserie_reposicion,
                                   CURDATE() AS desde, 
                                   DATE_ADD(CURDATE(), INTERVAL g.diasgarantia DAY) as hasta,
                                   g.diasgarantia as dias_gar, 1  
                              FROM venta_detalle d
                              INNER JOIN producto_serie s on s.id_detalleventa = d.id_detalle
                              INNER JOIN devolucion_garantia_detalle g on g.idserie_reposicion = s.id_serie
                              WHERE g.iddevolucion = $iddevolucion AND g.idserie = $seriedevuelta;");

    }


    public function sel_garantiadevolucion_id($id){
      $sql = $this->db->query("SELECT g.id, g.fecha, g.nrodevolucion, g.idsucursal,
                                      c.nom_cliente, c.ident_cliente, c.direccion_cliente,
                                      c.telefonos_cliente, c.correo_cliente 
                                FROM devolucion_garantia g 
                                INNER JOIN clientes c ON c.id_cliente = g.idcliente
                                WHERE g.id = $id
                                ORDER BY g.fecha desc, g.nrodevolucion");
      $resu = $sql->result();
      if ($resu)
        return $resu[0];
    }

    public function sel_garantiadevolucion_detalles($id){
      $sql = $this->db->query("SELECT d.id, d.idventa, d.idalmacen, d.observaciones, 
                                      v.fecha as fechafactura, v.nro_factura,
                                      (CASE WHEN vd.id_detalle IS NOT NULL THEN (vd.descsubtotal + vd.montoiva)
                                         ELSE pd.pro_precioventa
                                       END) as importe_productodevuelto,
                                      d.idserie, sd.numeroserie as seriedevuelta, pd.pro_nombre as productodevuelto,
                                      gd.fec_desde as desdedevuelta, gd.fec_hasta as hastadevuelta, gd.dias_gar as diasdevuelta,
                                      d.idserie_reposicion, sr.numeroserie as seriereposicion, pr.pro_nombre as productoreposicion,
                                      gr.fec_desde as desdereposicion, gr.fec_hasta as hastareposicion, gr.dias_gar as diasreposicion
                                FROM devolucion_garantia_detalle d 
                                INNER JOIN venta v on v.id_venta = d.idventa
                                INNER JOIN producto_serie sd on sd.id_serie = d.idserie
                                INNER JOIN producto pd on pd.pro_id = sd.id_producto
                                LEFT JOIN venta_detalle vd on vd.id_detalle = d.iddetalleventa 
                                LEFT JOIN garantia gd on gd.idserie = d.idserie AND gd.idventa = d.idventa AND gd.estatus = 0
                                LEFT JOIN producto_serie sr on sr.id_serie = d.idserie_reposicion
                                LEFT JOIN producto pr on pr.pro_id = sr.id_producto
                                LEFT JOIN garantia gr on gr.idserie = d.idserie_reposicion AND gr.idventa = d.idventa AND gr.estatus = 1
                                WHERE d.iddevolucion = $id 
                                ORDER BY sd.numeroserie");
      $resu = $sql->result();
      return $resu;
    }

/*--------------------------------
    public function lst_garantia($desde, $hasta){
      $sql = $this->db->query(" SELECT g.idventa, g.fecharegistro, g.nro_doc, CONCAT(v.tipo_ident,'-',v.nro_ident) AS cedula, v.nom_cliente  
                                FROM garantia g
                                INNER JOIN venta v ON v.id_venta = g.idventa
                                GROUP BY g.nro_doc");
    }
*/

}
