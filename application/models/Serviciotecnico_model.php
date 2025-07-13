<?php

/* ------------------------------------------------
  ARCHIVO: Serviciotecnico_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a Servicio Tecnico.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */

class Serviciotecnico_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* lista de servicios */
    public function lst_servicio($desde, $hasta, $estado, $tecnico = 0) {
        $query = $this->db->query("SELECT s.id_servicio, s.id_sucursal, s.fecha_emision, s.numero_orden, s.id_cliente,
                                          s.descripcion, s.id_estado, s.id_venta, s.costo_estimado, 
                                          sc.nom_sucursal, e.nombre_estado, c.nom_cliente, c.ident_cliente,
                                          v.estatus
                                     FROM servicio s
                                     INNER JOIN sucursal sc on sc.id_sucursal = s.id_sucursal
                                     INNER JOIN servicio_estado e on e.id_estado = s.id_estado
                                     INNER JOIN clientes c on c.id_cliente = s.id_cliente
                                     LEFT JOIN venta v on v.id_venta = s.id_venta
                                     WHERE date(s.fecha_emision) BETWEEN '$desde' AND '$hasta' AND
                                           (($estado = 0) OR (s.id_estado = $estado)) AND
                                           (($tecnico = 0) OR (SELECT count(*) FROM servicio_detalle d 
                                                                 WHERE d.id_servicio = s.id_servicio AND id_tecnico = $tecnico) > 0)
                                     ORDER BY s.fecha_emision, s.numero_orden;");
        $result = $query->result();
        return $result;
    }

    public function lst_serviciorealizado($desde, $hasta, $estado, $tecnico = 0) {
        $query = $this->db->query("SELECT s.id_servicio, s.id_sucursal, s.fecha_emision, s.numero_orden, s.id_cliente,
                                          s.descripcion, s.id_estado, s.id_venta, s.costo_estimado, 
                                          sc.nom_sucursal, c.nom_cliente, c.ident_cliente, v.estatus, 
                                          CASE WHEN v.id_venta IS NOT NULL then 'FACTURADO'
                                                                           else e.nombre_estado
                                          END as nombre_estado
                                     FROM servicio s
                                     INNER JOIN sucursal sc on sc.id_sucursal = s.id_sucursal
                                     INNER JOIN servicio_estado e on e.id_estado = s.id_estado
                                     INNER JOIN clientes c on c.id_cliente = s.id_cliente
                                     LEFT JOIN venta v on v.id_venta = s.id_venta
                                     WHERE ((s.id_estado >= 3) AND (s.id_estado >= $estado)) AND
                                           (SELECT count(*) FROM servicio_detalle d 
                                              WHERE d.id_servicio = s.id_servicio AND 
                                                    d.fecha_realizado BETWEEN '$desde' AND '$hasta') > 0 AND
                                           (($tecnico = 0) OR (SELECT count(*) FROM servicio_detalle d 
                                                                 WHERE d.id_servicio = s.id_servicio AND id_tecnico = $tecnico) > 0)
                                     ORDER BY s.numero_orden;");
        $result = $query->result();
        return $result;
    }
    
        public function lst_serviciomecanico($desde, $hasta, $estado, $tecnico = 0) {
        $query = $this->db->query("SELECT s.id_servicio, s.id_sucursal, s.fecha_emision, s.numero_orden, s.id_cliente,
                                          s.descripcion, s.id_estado, s.id_venta, s.costo_estimado, 
                                          sc.nom_sucursal, c.nom_cliente, c.ident_cliente, v.estatus, 
                                          CASE WHEN v.id_venta IS NOT NULL then 'FACTURADO'
                                                                           else e.nombre_estado
                                          END as nombre_estado
                                     FROM servicio s
                                     INNER JOIN sucursal sc on sc.id_sucursal = s.id_sucursal
                                     INNER JOIN servicio_estado e on e.id_estado = s.id_estado
                                     INNER JOIN clientes c on c.id_cliente = s.id_cliente
                                     LEFT JOIN venta v on v.id_venta = s.id_venta
                                     WHERE ((s.id_estado >= 3) AND (s.id_estado >= $estado)) AND
                                           (SELECT count(*) FROM servicio_detalle d 
                                              WHERE d.id_servicio = s.id_servicio AND 
                                                    d.fecha_realizado BETWEEN '$desde' AND '$hasta') > 0 AND
                                           (($tecnico = 0) OR (SELECT count(*) FROM servicio_detalle d 
                                                                 WHERE d.id_servicio = s.id_servicio AND id_tecnico = $tecnico) > 0)
                                     ORDER BY s.numero_orden;");
        $result = $query->result();
        return $result;
    }

    public function lst_detalleservicio($servicio) {
        $query = $this->db->query("SELECT d.id_detalle, d.id_servicio, d.id_tecnico, d.descripcion, d.id_estado, 
                                          p.nombre_empleado, d.fecha_realizado, d.trabajo_realizado, d.fecha_entregado,
                                          CASE WHEN s.id_venta IS NOT NULL then 'FACTURADO'
                                                                           else e.nombre_estado
                                          END as nombre_estado,
                                          IFNULL((SELECT SUM(Round(dp.cantidad * dp.precio,2)) FROM servicio_producto dp
                                                    INNER JOIN producto pp on pp.pro_id = dp.id_producto
                                                    WHERE pp.pro_esservicio = 1 AND dp.id_detalle = d.id_detalle),0) as montoservicio,
                                          IFNULL((SELECT SUM(Round(dp.cantidad * dp.precio,2)) FROM servicio_producto dp
                                                    INNER JOIN producto pp on pp.pro_id = dp.id_producto
                                                    WHERE pp.pro_esservicio = 0 AND dp.id_detalle = d.id_detalle),0) as montomercancia
                                     FROM servicio_detalle d
                                     INNER JOIN servicio s on s.id_servicio = d.id_servicio
                                     LEFT JOIN empleado p on p.id_empleado = d.id_tecnico
                                     INNER JOIN servicio_estado e on e.id_estado = d.id_estado
                                     WHERE d.id_servicio = $servicio
                                     ORDER BY d.id_detalle;");
        $result = $query->result();
        return $result;
    }

    public function lst_configservicio() {
        $query = $this->db->query("SELECT habilita_servicio, habilita_serie, habilita_detalle, 
                                          habilita_encargado, habilita_productoutilizado, 
                                          producto_servicio_factura, habilita_abono,
                                          habilita_productofactura
                                     FROM servicio_config_general;");
        $result = $query->result();
        return $result[0];
    }

    public function upd_configservicio($servicio, $serie, $detalle, $encargado, $productoutilizado, $idservicio, $abono, $productofactura) {
        $query = $this->db->query("UPDATE servicio_config_general SET 
                                          habilita_servicio = $servicio, 
                                          habilita_serie = $serie,
                                          habilita_detalle = $detalle, 
                                          habilita_encargado = $encargado, 
                                          habilita_productoutilizado = $productoutilizado, 
                                          producto_servicio_factura = $idservicio,
                                          habilita_abono = $abono,
                                          habilita_productofactura = $productofactura;");
    }

    public function sel_pro_servicio(){
      $query = $this->db->query("SELECT pro_id, pro_codigobarra, pro_codigoauxiliar, pro_nombre, pro_descripcion, pro_preciocompra,
                                        pro_precioventa, pro_maximo, pro_minimo, pro_idcategoria, pro_iddeducible, pro_grabaiva,
                                        pro_estatus, pro_imagen, pro_idunidadmedida, pro_aplicompra, pro_apliventa,
                                        habilitavariante, maxitemvariante, productodescontarventa, comanda, cantidad, idcla,
                                        ingrediente, preparado, id_cto_retencion 
                                   FROM producto WHERE pro_esservicio = 1");
      $result = $query->result();
      return $result;
    }

    
    public function tmp_clearserviciotecnico($idusu){
      $sql = $this->db->query("DELETE FROM servicio_subdetalle_tmp 
                                 WHERE id_detalle in (SELECT id_detalle FROM servicio_detalle_tmp WHERE id_usuario = $idusu)");
      $sql = $this->db->query("DELETE FROM servicio_detalle_tmp WHERE id_usuario = $idusu");
      $sql = $this->db->query("DELETE FROM servicio_tmp WHERE id_usuario = $idusu");
    }  

    public function carga_cliente($idusu, $idsucursal, $orden){

/*      $sql = $this->db->query("DELETE FROM servicio_detalle_tmp WHERE id_usuario = $idusu");
      $sql = $this->db->query("DELETE FROM servicio_tmp WHERE id_usuario = $idusu");
      $val = 0;*/
      $sql = $this->db->query("SELECT COUNT(*) AS val FROM servicio_tmp WHERE id_usuario = $idusu");
      $cliven = $sql->result();
      $val = $cliven[0]->val;

      if($val == 0){
        $selcli = $this->db->query("SELECT id_cliente, tipo_ident_cliente, ident_cliente, nom_cliente FROM clientes WHERE id_cliente = 1");
        $clinvo = $selcli->result();
        $id_clid = $clinvo[0]->id_cliente;
        $tp_cli = $clinvo[0]->tipo_ident_cliente;
        $id_cli = $clinvo[0]->ident_cliente;
        $no_cli = $clinvo[0]->nom_cliente; 
        $fecha = date('Y-m-d');

        $addcli = $this->db->query("INSERT INTO servicio_tmp (id_sucursal, fecha_emision, numero_orden, tipo_ident, nro_ident, nom_cliente, id_usuario, id_cliente, id_estado, costo_estimado)
                                      SELECT $idsucursal, '$fecha', $orden, '$tp_cli', '$id_cli','$no_cli',$idusu,  $id_clid, 1, 0");
      }

      $sqlcli = $this->db->query("SELECT id_usuario, id_sucursal, fecha_emision, numero_orden, descripcion, id_estado,
                                         id_servicio, costo_estimado,
                                         id_cliente, tipo_ident, nro_ident, 
                                         nom_cliente, telf_cliente, dir_cliente, correo_cliente, ciu_cliente
                                  FROM servicio_tmp t
                                  WHERE id_usuario = $idusu");
      $cliver = $sqlcli->result();
      return $cliver[0];      
    }

    public function carga_tmpgenservicio($idservicio, $idusu){
      $sql = $this->db->query("DELETE FROM servicio_abono_tmp WHERE id_usuario = $idusu");
      $sql = $this->db->query("DELETE FROM servicio_producto_tmp WHERE id_detalle in (SELECT id_detalle FROM servicio_detalle_tmp WHERE id_usuario = $idusu)");
      $sql = $this->db->query("DELETE FROM servicio_subdetalle_tmp WHERE id_detalle in (SELECT id_detalle FROM servicio_detalle_tmp WHERE id_usuario = $idusu)");
      $sql = $this->db->query("DELETE FROM servicio_detalle_tmp WHERE id_usuario = $idusu");
      $sql = $this->db->query("DELETE FROM servicio_tmp WHERE id_usuario = $idusu");

      $addcli = $this->db->query("INSERT INTO servicio_tmp (id_usuario, id_sucursal, fecha_emision, numero_orden, 
                                                            tipo_ident, nro_ident, nom_cliente, telf_cliente, 
                                                            dir_cliente, correo_cliente, ciu_cliente,
                                                            id_cliente, id_estado, descripcion,
                                                            id_servicio, costo_estimado)
                                      SELECT $idusu, s.id_sucursal, s.fecha_emision, s.numero_orden, 
                                             c.tipo_ident_cliente, c.ident_cliente, c.nom_cliente, c.telefonos_cliente,
                                             c.direccion_cliente, c.correo_cliente, c.ciudad_cliente,
                                             s.id_cliente, s.id_estado, s.descripcion,
                                             s.id_servicio, s.costo_estimado
                                        FROM servicio s 
                                        INNER JOIN clientes c on c.id_cliente = s.id_cliente
                                        WHERE s.id_servicio = $idservicio");

      $sqlcli = $this->db->query("SELECT id_detalle, id_serie, id_tecnico, descripcion, id_estado,
                                         fecha_realizado, trabajo_realizado, fecha_entregado
                                    FROM servicio_detalle WHERE id_servicio = $idservicio;");
      $result = $sqlcli->result();
      foreach ($result as $obj) {       
        $iddet = $obj->id_detalle;
        $serie = $obj->id_serie;
        $id_tecnico = $obj->id_tecnico;
        $descripcion = $obj->descripcion;
        $id_estado = $obj->id_estado;
        $fecha_realizado = $obj->fecha_realizado;
        $trabajo_realizado = $obj->trabajo_realizado;
        $fecha_entregado = $obj->fecha_entregado;

        $this->db->query("INSERT INTO servicio_detalle_tmp (id_usuario, id_serie, id_tecnico, descripcion, id_estado,
                                                          fecha_realizado, trabajo_realizado, fecha_entregado)
                            VALUES($idusu, $serie, $id_tecnico, '$descripcion', $id_estado,
                                   '$fecha_realizado', '$trabajo_realizado', '$fecha_entregado')");

        $sqlcli = $this->db->query("SELECT max(id_detalle) as maxid FROM servicio_detalle_tmp;");
        $res = $sqlcli->result();
        $newid = $res[0]->maxid;

        $this->db->query("INSERT INTO servicio_subdetalle_tmp (id_detalle, id_config, valor)
                            SELECT $newid, id_config, valor
                              FROM servicio_subdetalle 
                              WHERE id_detalle = $iddet;");

        $this->db->query("INSERT INTO servicio_producto_tmp (id_detalle, id_producto, cantidad, precio, id_almacen)
                            SELECT $newid, id_producto, cantidad, precio, id_almacen
                              FROM servicio_producto 
                              WHERE id_detalle = $iddet;");
      }  

      $this->db->query("INSERT INTO servicio_abono_tmp (id_usuario, id_formapago, monto, fecha_registro, nro_comprobante, 
                                                        fecha_emision, numerocuenta, fecha_cobro, id_banco, numerodocumento, 
                                                        descripciondocumento, id_tarjeta, numerotarjeta, id_cajapago, id_docpago)
                          SELECT $idusu, f.id_formapago, f.monto, f.fecha, f.nro_comprobante,
                                 case when b.id_abono is not null then b.fechaemision
                                                 else case when t.id_abono is not null then t.fechaemision
                                                       else null
                                                      end  
                                 end as fechaemision,  
                                 numerocuenta, fechacobro, 
                                 case when b.id_abono is not null then b.id_banco
                                   else case when t.id_abono is not null then t.id_banco else null end  
                                 end as id_banco, 
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
                                 id_tarjeta, numerotarjeta, id_cajapago, f.id
                            FROM servicio_abono a
                            INNER JOIN venta_formapago f on f.id = a.id_docpago
                            LEFT JOIN venta_formapagobanco b on b.id_abono = f.id
                            LEFT JOIN venta_formapagotarjeta t on t.id_abono = f.id
                            WHERE id_servicio = $idservicio");



      $sqlcli = $this->db->query("SELECT id_usuario, id_sucursal, fecha_emision, numero_orden, descripcion, id_estado,
                                         id_servicio, costo_estimado,
                                         id_cliente, tipo_ident, nro_ident, 
                                         nom_cliente, telf_cliente, dir_cliente, correo_cliente, ciu_cliente                                         
                                  FROM servicio_tmp t
                                  WHERE id_usuario = $idusu");
      $cliver = $sqlcli->result();
      return $cliver[0];      
    }

    /* ACTUALIZAR REGISTRO DEL CLIENTE EN LA TABLA Servicio_tmp */
    public function upd_cliente($idusu, $idcli, $idtp, $nom, $tel, $cor, $dir, $ciu, $idc){
      $sql = $this->db->query("SELECT COUNT(*) AS val FROM servicio_tmp WHERE id_usuario = $idusu");
      $cliven = $sql->result();
      $val = $cliven[0]->val;

      if($val > 0){

        $sqlcli = $this->db->query("SELECT id_cliente, COUNT(*) AS nrocli FROM clientes WHERE ident_cliente = '$idcli' ");
        $resucli = $sqlcli->result();
        $valcli = $resucli[0]->nrocli;

        if($valcli > 0){  

          $idc = $resucli[0]->id_cliente;      
      
          $updrescli = $this->db->query(" UPDATE servicio_tmp 
                                            SET id_cliente = $idc,
                                                tipo_ident = '$idtp', 
                                                nro_ident = '$idcli', 
                                                nom_cliente = '$nom', 
                                                telf_cliente = '$tel', 
                                                dir_cliente = '$dir', 
                                                correo_cliente = '$cor', 
                                                ciu_cliente = '$ciu'
                                          WHERE id_usuario = $idusu");

          $updrescli = $this->db->query(" UPDATE clientes 
                                            SET tipo_ident_cliente = '$idtp', 
                                                ident_cliente = '$idcli', 
                                                nom_cliente = '$nom', 
                                                telefonos_cliente = '$tel', 
                                                direccion_cliente = '$dir', 
                                                correo_cliente = '$cor', 
                                                ciudad_cliente = '$ciu'
                                          WHERE id_cliente != 1 AND id_cliente = $idc");

          return 0;
        }else{
          if($cor != NULL || $cor = ""){}else{$cor = " ";} 
          if($tel != NULL || $tel = ""){}else{$tel = " ";}
          if($dir != NULL || $dir = ""){}else{$dir = " ";}
          if($ciu != NULL || $ciu = ""){}else{$ciu = " ";}
          
          $sql_addc = $this->db->query("INSERT INTO clientes (tipo_ident_cliente, nom_cliente, ident_cliente) 
                                                      VALUES ('$idtp', '$nom', '$idcli')");
          $this->upd_cliente($idusu, $idcli, $idtp, $nom, $tel, $cor, $dir, $ciu, $idc);
        } 

      }
    }

    /* ACTUALIZAR Datos Generales EN LA TABLA Servicio_tmp */
    public function upd_tmpgenservicio($idusu, $idsuc, $fechaser, $desc, $idest, $costo_estimado){
      $query = $this->db->query("SELECT id_sucursal FROM servicio_tmp
                                   WHERE id_usuario = $idusu");
      $res = $query->result();
      $tmpsuc = 0;
      if ($res) { $tmpsuc =  $res[0]->id_sucursal;}
      $this->db->query("UPDATE servicio_tmp 
                          SET id_sucursal = $idsuc,
                              fecha_emision = '$fechaser', 
                              descripcion = '$desc', 
                              id_estado = 1, /*OJO Arreglar esto*/
                              costo_estimado = $costo_estimado,
                              numero_orden = CASE WHEN (id_servicio IS NOT NULL) AND ($tmpsuc = $idsuc) 
                                                THEN numero_orden
                                                ELSE IFNULL((SELECT consecutivo_ordenservicio 
                                                               FROM sucursal where id_sucursal=$idsuc), 1)
                                              END
                          WHERE id_usuario = $idusu");

      $query = $this->db->query("SELECT numero_orden FROM servicio_tmp
                                   WHERE id_usuario = $idusu");
      $resret = $query->result();
      if ($resret) 
        return $resret[0]->numero_orden;
      else
        return '';
    }

    /* ACTUALIZAR Datos Generales EN LA TABLA Servicio_tmp */
    public function upd_tmpgenservicio00($idusu, $idsuc, $fechaser, $desc, $idest, $fecharealiza, $trabrealizado, $fechaentrega, $esproductoserie, $idserie, $costo_estimado){
     
        if (!$encargado) { $encargado = 'NULL'; }
        if (($encargado == '') || ($encargado == '0')) { $encargado = 'NULL'; }
        if ($idest < 4) { $fechaentrega = ''; }
        if ($idest < 3) { $fecharealiza = ''; }
        $this->db->query("UPDATE servicio_tmp 
                              SET id_sucursal = $idsuc,
                                  fecha_emision = '$fechaser', 
                                  descripcion = '$desc', 
                                  id_responsable = $encargado, 
                                  id_estado = $idest, 
                                  fecha_realizado = case when ('$fecharealiza' <> '') then '$fecharealiza' else NULL end, 
                                  trabajo_realizado = '$trabrealizado', 
                                  fecha_entregado = case when ('$fechaentrega' <> '') then '$fechaentrega' else NULL end,
                                  es_productoserie = $esproductoserie,
                                  id_serie = case when ($idserie > 0) then $idserie else NULL end,
                                  costo_estimado = $costo_estimado
                            WHERE id_usuario = $idusu");
    }

    public function lst_estadoservicio() {
        $query = $this->db->query("SELECT id_estado, nombre_estado FROM servicio_estado ORDER BY id_estado;");
        $result = $query->result();
        return $result;
    }

    public function carga_nombredetalle(){
      $sqlcli = $this->db->query("SELECT id_config, nombre_configdetalle
                                    FROM servicio_config_detalle
                                    WHERE activo = 1");
      $cliver = $sqlcli->result();
      return $cliver;      
    }

    public function lst_nombredetalle(){
      $sqlcli = $this->db->query("SELECT id_config, nombre_configdetalle, activo
                                    FROM servicio_config_detalle");
      $cliver = $sqlcli->result();
      return $cliver;      
    }

    public function carga_detalle_tmp($idusu){
      $sqlcli = $this->db->query("SELECT t.id_usuario, t.id_detalle, t.id_serie, t.id_tecnico,
                                         t.descripcion, t.id_estado, t.fecha_realizado, 
                                         t.trabajo_realizado, t.fecha_entregado, 
                                         s.id_config, s.valor, p.numeroserie, e.nombre_empleado,
                                         se.nombre_estado
                                    FROM servicio_detalle_tmp t
                                    LEFT JOIN servicio_subdetalle_tmp s on s.id_detalle = t.id_detalle
                                    LEFT JOIN empleado e on e.id_empleado = t.id_tecnico
                                    LEFT JOIN producto_serie p on p.id_serie = t.id_serie
                                    LEFT JOIN servicio_estado se on se.id_estado = t.id_estado
                                    WHERE t.id_usuario = $idusu
                                    ORDER BY t.id_detalle, s.id_config");
      $cliver = $sqlcli->result();
      return $cliver;      
    }

    public function carga_detalletmp_id($iddetalle){
      $sqlcli = $this->db->query("SELECT t.id_usuario, t.id_detalle, t.id_serie, t.id_tecnico,
                                         t.descripcion, t.id_estado, t.fecha_realizado, 
                                         t.trabajo_realizado, t.fecha_entregado,
                                         ifnull(p.numeroserie,'') as numeroserie, 
                                         ifnull(pp.pro_nombre,'') as productoserie
                                    FROM servicio_detalle_tmp t
                                    LEFT JOIN producto_serie p on p.id_serie = t.id_serie
                                    LEFT JOIN producto pp on pp.pro_id = p.id_producto
                                    WHERE t.id_detalle = $iddetalle");
      $cliver = $sqlcli->result();
      return $cliver[0];      
    }

    public function carga_subdetalle_tmp($iddetalle=0){
    /*  $sqlcli = $this->db->query("SELECT t.id_subdetalle, t.id_config, t.valor, c.nombre_configdetalle
                                    FROM servicio_subdetalle_tmp t
                                    INNER JOIN servicio_config_detalle c on c.id_config = t.id_config
                                    WHERE c.activo = 1 AND t.id_detalle = $iddetalle");*/
      $sqlcli = $this->db->query("SELECT IFNULL(t.id_subdetalle,0) as id_subdetalle, IFNULL(t.valor,'') as valor,
                                    c.id_config, c.nombre_configdetalle
                                    FROM servicio_config_detalle c
                                    LEFT JOIN servicio_subdetalle_tmp t on t.id_config = c.id_config AND t.id_detalle = $iddetalle
                                    WHERE c.activo = 1 ");
      $cliver = $sqlcli->result();
      return $cliver;    
    }      

    public function get_proxnumeroorden($idsucursal){
      $query = $this->db->query("select consecutivo_ordenservicio from sucursal where id_sucursal=$idsucursal");
      $resret = $query->result();
      if ($resret) 
        return $resret[0]->consecutivo_ordenservicio;
      else
        return "";
    }

    /* BUSQUEDA POR SERIE */
    public function valida_serie($serie){
      $query = $this->db->query("SELECT numeroserie FROM producto_serie WHERE numeroserie like '%$serie%'");
      $result = $query->result();
      return $result;
    }

    public function busca_serie($serie){
      $query = $this->db->query("SELECT s.id_serie, s.id_producto, s.numeroserie, s.descripcion, s.fechaingreso,
                                        p.pro_nombre
                                   FROM  producto_serie s
                                   INNER JOIN producto p on p.pro_id = s.id_producto
                                   WHERE numeroserie = '$serie' ");
      $result = $query->result();
      if($result == NULL){
        $result = NULL;
        return $result;
      }else{
        return $result;
      }
    }

    /* ACTUALIZAR Detalle Servicio tmp */
    public function ins_detalletmpservicio($idusu, $idserie, $tecnico, $descripcion, $estado, $fecrealizado, $trabajorealizado, $fecentregado, $valcfg){   
        $this->db->query("INSERT INTO servicio_detalle_tmp (id_usuario, id_serie, id_tecnico, descripcion, id_estado,
                                                            fecha_realizado, trabajo_realizado, fecha_entregado)
                            Values ($idusu, $idserie, $tecnico, '$descripcion', $estado, '$fecrealizado', '$trabajorealizado','$fecentregado');");
        $query = $this->db->query("SELECT max(id_detalle) as max FROM servicio_detalle_tmp");
        $resu = $query->result();
        $id = $resu[0]->max;
        foreach ($valcfg as $clave => $valor) {
          $this->db->query("INSERT INTO servicio_subdetalle_tmp (id_detalle, id_config, valor)
                              VALUES($id, $clave, '$valor')");
        }
        return $id;
    }

    /* ACTUALIZAR Detalle Servicio tmp */
    public function del_detalletmpservicio($iddetalle){   
        $this->db->query("DELETE FROM servicio_subdetalle_tmp WHERE id_detalle = $iddetalle");
        $this->db->query("DELETE FROM servicio_detalle_tmp WHERE id_detalle = $iddetalle");
        return $iddetalle;
    }

    /* ACTUALIZAR Detalle Servicio tmp */
    public function upd_detalletmpservicio($iddetalle, $idserie, $tecnico, $descripcion, $estado, $fecrealizado, $trabajorealizado, $fecentregado, $valcfg){   
        $this->db->query("UPDATE servicio_detalle_tmp 
                            SET id_serie = $idserie,
                                id_tecnico = $tecnico,
                                descripcion = '$descripcion',
                                id_estado = $estado,
                                fecha_realizado = '$fecrealizado',
                                trabajo_realizado = '$trabajorealizado',
                                fecha_entregado = '$fecentregado'
                            WHERE id_detalle = $iddetalle");
        $this->db->query("DELETE FROM servicio_subdetalle_tmp WHERE id_detalle = $iddetalle");
        foreach ($valcfg as $clave => $valor) {
          $this->db->query("INSERT INTO servicio_subdetalle_tmp (id_detalle, id_config, valor)
                              VALUES($iddetalle, $clave, '$valor')");
        }
    }


    /* ACTUALIZAR Detalle Servicio tmp */
    public function ins_servicio($idusu){   
        $this->db->query("INSERT INTO servicio (id_sucursal, fecha_emision, numero_orden, id_cliente, 
                                                costo_estimado, descripcion, id_estado)
                            SELECT id_sucursal, fecha_emision, numero_orden, id_cliente, 
                                   costo_estimado, descripcion, 
                                   IFNULL((SELECT min(id_estado) FROM servicio_detalle_tmp WHERE id_usuario = $idusu),1) as id_estado
                              FROM servicio_tmp WHERE id_usuario = $idusu");
        $query = $this->db->query("SELECT max(id_servicio) as max FROM servicio");
        $resu = $query->result();
        $id = $resu[0]->max;
        $query = $this->db->query("SELECT id_detalle, id_serie, id_tecnico, descripcion, id_estado,
                                          fecha_realizado, trabajo_realizado, fecha_entregado
                                    FROM servicio_detalle_tmp WHERE id_usuario = $idusu");
        $resu = $query->result();
        foreach ($resu as $det) {
          $this->db->query("INSERT INTO servicio_detalle (id_servicio, id_serie, id_tecnico, descripcion, id_estado,
                                                          fecha_realizado, trabajo_realizado, fecha_entregado)
                              SELECT $id, " . $det->id_serie . "," . $det->id_tecnico . ",'" . $det->descripcion . "'," . 
                                     $det->id_estado . ",'" . $det->fecha_realizado . "','" . $det->trabajo_realizado . "','" .
                                     $det->fecha_entregado . "'");
          $querydet = $this->db->query("SELECT max(id_detalle) as max FROM servicio_detalle");
          $resudet = $querydet->result();
          $iddet = $resudet[0]->max;
          $this->db->query("INSERT INTO servicio_subdetalle (id_detalle, id_config, valor)
                              SELECT $iddet, id_config, valor
                                FROM servicio_subdetalle_tmp 
                                WHERE id_detalle = " . $det->id_detalle);
          $this->db->query("INSERT INTO servicio_producto (id_detalle, id_producto, cantidad, precio, id_almacen)
                              SELECT $iddet, id_producto, cantidad, precio, id_almacen
                                FROM servicio_producto_tmp 
                                WHERE id_detalle = " . $det->id_detalle);
        }

        $query = $this->db->query("SELECT id_abono, id_docpago, id_formapago FROM servicio_abono_tmp WHERE id_usuario = $idusu");
        $resu = $query->result();
        foreach ($resu as $det) {
          $fp = $det->id_formapago;
          $tfp = $this->selforpago($fp);
          $bco = $tfp->banco;
          $tarj = $tfp->tarjeta;
          $tipo = "";
          if($tarj == 1 && $bco == 0){ $tipo = "Tarjeta"; }
          if($tarj == 0 && $bco == 1){ $tipo = "Banco"; }
          if($tarj == 0 && $bco == 0){ $tipo = "Efectivo"; }

          $idabonotmp = $det->id_abono;
          $iddoc = $det->id_docpago;
          if (!$iddoc){
            $this->db->query("INSERT INTO venta_formapago (id_formapago, monto, fecha, id_cajapago, nro_comprobante) 
                                SELECT $fp, monto, now(), id_cajapago,
                                       ifnull((select valor from contador WHERE id_contador = 7),1)
                                  FROM servicio_abono_tmp WHERE id_abono = $idabonotmp;");
            $sql = $this->db->query("select max(id) as id from venta_formapago;");
            $resu = $sql->result();
            $iddoc = $resu[0]->id;
            $this->db->query("UPDATE contador set valor=ifnull(valor,1)+1 WHERE id_contador = 7;");
            $this->db->query("INSERT INTO servicio_abono (id_servicio, id_docpago) VALUES ($id, $iddoc)");
          }
          switch($tipo) {
            case 'Tarjeta':
              $this->db->query("INSERT INTO venta_formapagotarjeta (id_abono, id_banco, id_tarjeta, numerotarjeta,
                                                                    fechaemision, numerodocumento, descripciondocumento) 
                                  SELECT $iddoc, id_banco, id_tarjeta, numerotarjeta,
                                         fechaemision, numerodocumento, descripciondocumento
                                    FROM servicio_abono_tmp WHERE id_abono = $idabonotmp");
            break;
            case 'Banco':
              $this->db->query("INSERT INTO venta_formapagobanco (id_abono, id_banco, numerocuenta, fechaemision,
                                                                  fechacobro, numerodocumento, descripciondocumento) 
                                  SELECT $iddoc, id_banco, numerocuenta, fechaemision,
                                         fechacobro, numerodocumento, descripciondocumento
                                    FROM servicio_abono_tmp WHERE id_abono = $idabonotmp");
            break;                  
            default:
          }      
        }  

        $this->db->query("UPDATE sucursal set consecutivo_ordenservicio = consecutivo_ordenservicio + 1
                            WHERE id_sucursal=(SELECT id_sucursal FROM servicio_tmp WHERE id_usuario = $idusu)");

        $this->db->query("DELETE FROM servicio_abono_tmp WHERE id_usuario = $idusu");
        $this->db->query("DELETE FROM servicio_producto_tmp 
                            WHERE id_detalle in (SELECT id_detalle FROM servicio_detalle_tmp WHERE id_usuario = $idusu)");
        $this->db->query("DELETE FROM servicio_subdetalle_tmp 
                            WHERE id_detalle in (SELECT id_detalle FROM servicio_detalle_tmp WHERE id_usuario = $idusu)");
        $this->db->query("DELETE FROM servicio_detalle_tmp WHERE id_usuario = $idusu");
        $this->db->query("DELETE FROM servicio_tmp WHERE id_usuario = $idusu");

        return $id;
    }

    public function upd_servicio($idservicio, $idusu){   
        $this->db->query("UPDATE servicio s
                            INNER JOIN servicio_tmp t on t.id_servicio = s.id_servicio
                            SET s.id_sucursal = t.id_sucursal, 
                                s.numero_orden = t.numero_orden,
                                s.fecha_emision = t.fecha_emision, 
                                s.id_cliente = t.id_cliente, 
                                s.descripcion = t.descripcion, 
                                s.id_estado = IFNULL((SELECT min(id_estado) FROM servicio_detalle_tmp WHERE id_usuario = $idusu),1), 
                                s.costo_estimado = t.costo_estimado
                            WHERE s.id_servicio = $idservicio and t.id_usuario = $idusu");

        $this->db->query("DELETE FROM servicio_producto
                            WHERE id_detalle in (SELECT id_detalle FROM servicio_detalle WHERE id_servicio = $idservicio)");
        $this->db->query("DELETE FROM servicio_subdetalle
                            WHERE id_detalle in (SELECT id_detalle FROM servicio_detalle WHERE id_servicio = $idservicio)");
        $this->db->query("DELETE FROM servicio_detalle WHERE id_servicio = $idservicio");

        $query = $this->db->query("SELECT id_detalle, id_serie, id_tecnico, descripcion, id_estado,
                                          fecha_realizado, trabajo_realizado, fecha_entregado
                                    FROM servicio_detalle_tmp WHERE id_usuario = $idusu");
        $resu = $query->result();
        foreach ($resu as $det) {
          $this->db->query("INSERT INTO servicio_detalle (id_servicio, id_serie, id_tecnico, descripcion, id_estado,
                                                          fecha_realizado, trabajo_realizado, fecha_entregado)
                              SELECT $idservicio, " . $det->id_serie . "," . $det->id_tecnico . ",'" . $det->descripcion . "'," . 
                                     $det->id_estado . ",'" . $det->fecha_realizado . "','" . $det->trabajo_realizado . "','" .
                                     $det->fecha_entregado . "'");
          $querydet = $this->db->query("SELECT max(id_detalle) as max FROM servicio_detalle");
          $resudet = $querydet->result();
          $iddet = $resudet[0]->max;
          $this->db->query("INSERT INTO servicio_subdetalle (id_detalle, id_config, valor)
                              SELECT $iddet, id_config, valor
                                FROM servicio_subdetalle_tmp 
                                WHERE id_detalle = " . $det->id_detalle);
          $this->db->query("INSERT INTO servicio_producto (id_detalle, id_producto, cantidad, precio, id_almacen)
                              SELECT $iddet, id_producto, cantidad, precio, id_almacen
                                FROM servicio_producto_tmp 
                                WHERE id_detalle = " . $det->id_detalle);
        }

        $query = $this->db->query("SELECT id_docpago FROM servicio_abono 
                                     WHERE id_servicio = $idservicio AND
                                     NOT EXISTS (SELECT id_docpago FROM servicio_abono_tmp 
                                                   WHERE id_usuario = $idusu AND id_docpago = servicio_abono.id_docpago)");
        $resu = $query->result();
        foreach ($resu as $det) {
          $iddoc = $det->id_docpago;
          $this->db->query("DELETE FROM venta_formapagobanco WHERE id_abono = $iddoc");
          $this->db->query("DELETE FROM venta_formapagotarjeta WHERE id_abono = $iddoc");
          $this->db->query("DELETE FROM venta_formapago WHERE id = $iddoc");
          $this->db->query("DELETE FROM servicio_abono WHERE id_docpago = $iddoc");
        }  

        $query = $this->db->query("SELECT id_abono, id_docpago, id_formapago FROM servicio_abono_tmp WHERE id_usuario = $idusu");
        $resu = $query->result();
        foreach ($resu as $det) {
          $fp = $det->id_formapago;
          $tfp = $this->selforpago($fp);
          $bco = $tfp->banco;
          $tarj = $tfp->tarjeta;
          $tipo = "";
          if($tarj == 1 && $bco == 0){ $tipo = "Tarjeta"; }
          if($tarj == 0 && $bco == 1){ $tipo = "Banco"; }
          if($tarj == 0 && $bco == 0){ $tipo = "Efectivo"; }

          $idabonotmp = $det->id_abono;
          $iddoc = $det->id_docpago;
          if (!$iddoc){
            $this->db->query("INSERT INTO venta_formapago (id_formapago, monto, fecha, id_cajapago, nro_comprobante) 
                                SELECT $fp, monto, now(), id_cajapago,
                                       ifnull((select valor from contador WHERE id_contador = 7),1)
                                  FROM servicio_abono_tmp WHERE id_abono = $idabonotmp;");
            $sql = $this->db->query("select max(id) as id from venta_formapago;");
            $resu = $sql->result();
            $iddoc = $resu[0]->id;
            $this->db->query("UPDATE contador set valor=ifnull(valor,1)+1 WHERE id_contador = 7;");
            $this->db->query("INSERT INTO servicio_abono (id_servicio, id_docpago) VALUES ($idservicio, $iddoc)");
          }
          else {
            $this->db->query("DELETE FROM venta_formapagobanco WHERE id_abono = $iddoc;");
            $this->db->query("DELETE FROM venta_formapagotarjeta WHERE id_abono = $iddoc;");
            $this->db->query("UPDATE venta_formapago v
                                INNER JOIN servicio_abono_tmp t on t.id_docpago = v.id
                                SET v.id_formapago = t.id_formapago, 
                                    v.monto = t.monto, 
                                    v.id_cajapago = t.id_cajapago
                                WHERE id = $iddoc;");
          }
          switch($tipo) {
            case 'Tarjeta':
              $this->db->query("INSERT INTO venta_formapagotarjeta (id_abono, id_banco, id_tarjeta, numerotarjeta,
                                                                    fechaemision, numerodocumento, descripciondocumento) 
                                  SELECT $iddoc, id_banco, id_tarjeta, numerotarjeta,
                                         fecha_emision, numerodocumento, descripciondocumento
                                    FROM servicio_abono_tmp WHERE id_abono = $idabonotmp");
            break;
            case 'Banco':
              $this->db->query("INSERT INTO venta_formapagobanco (id_abono, id_banco, numerocuenta, fechaemision,
                                                                  fechacobro, numerodocumento, descripciondocumento) 
                                  SELECT $iddoc, id_banco, numerocuenta, fecha_emision,
                                         fecha_cobro, numerodocumento, descripciondocumento
                                    FROM servicio_abono_tmp WHERE id_abono = $idabonotmp");
            break;                  
            default:
          }      
        }  

        $this->db->query("DELETE FROM servicio_abono_tmp WHERE id_usuario = $idusu");
        $this->db->query("DELETE FROM servicio_producto_tmp 
                            WHERE id_detalle in (SELECT id_detalle FROM servicio_detalle_tmp WHERE id_usuario = $idusu)");
        $this->db->query("DELETE FROM servicio_subdetalle_tmp 
                            WHERE id_detalle in (SELECT id_detalle FROM servicio_detalle_tmp WHERE id_usuario = $idusu)");
        $this->db->query("DELETE FROM servicio_detalle_tmp WHERE id_usuario = $idusu");
        $this->db->query("DELETE FROM servicio_tmp WHERE id_usuario = $idusu");
    }

    public function del_servicio($idservicio){   
        $this->db->query("DELETE FROM servicio_subdetalle
                            WHERE id_detalle in (SELECT id_detalle FROM servicio_detalle WHERE id_servicio = $idservicio)");
        $this->db->query("DELETE FROM servicio_detalle WHERE id_servicio = $idservicio");
        $this->db->query("DELETE FROM servicio WHERE id_servicio = $idservicio");
    }

    public function get_servicio_tmp($idusuario) {
        $query = $this->db->query("SELECT s.id_servicio, s.id_sucursal, s.fecha_emision, s.numero_orden, 
                                          s.id_cliente, s.descripcion, s.id_estado, s.costo_estimado,
                                          sc.nom_sucursal, e.nombre_estado, c.nom_cliente, c.ident_cliente,
                                          c.direccion_cliente, c.telefonos_cliente, c.correo_cliente
                                     FROM servicio_tmp s
                                     INNER JOIN sucursal sc on sc.id_sucursal = s.id_sucursal
                                     INNER JOIN servicio_estado e on e.id_estado = s.id_estado
                                     INNER JOIN clientes c on c.id_cliente = s.id_cliente
                                     WHERE s.id_usuario = $idusuario;");
        $result = $query->result();
        return $result[0];
    }

    public function get_servicio_id($idservicio) {
        $query = $this->db->query("SELECT s.id_servicio, s.id_sucursal, s.fecha_emision, s.numero_orden, 
                                          s.id_cliente, s.descripcion, s.id_estado, s.costo_estimado,
                                          s.id_venta, sc.nom_sucursal, e.nombre_estado, 
                                          c.nom_cliente, c.ident_cliente,
                                          c.direccion_cliente, c.telefonos_cliente, c.correo_cliente
                                     FROM servicio s
                                     INNER JOIN sucursal sc on sc.id_sucursal = s.id_sucursal
                                     INNER JOIN servicio_estado e on e.id_estado = s.id_estado
                                     INNER JOIN clientes c on c.id_cliente = s.id_cliente
                                     WHERE s.id_servicio = $idservicio;");
        $result = $query->result();
        return $result[0];
    }

    public function lst_detalle_servicio($idservicio){
      $sqlcli = $this->db->query("SELECT t.id_detalle, t.id_serie, t.id_tecnico,
                                         t.descripcion, t.id_estado, t.fecha_realizado, 
                                         t.trabajo_realizado, t.fecha_entregado, 
                                         s.id_config, s.valor, p.numeroserie, e.nombre_empleado,
                                         se.nombre_estado
                                    FROM servicio_detalle t
                                    LEFT JOIN servicio_subdetalle s on s.id_detalle = t.id_detalle
                                    LEFT JOIN empleado e on e.id_empleado = t.id_tecnico
                                    LEFT JOIN producto_serie p on p.id_serie = t.id_serie
                                    LEFT JOIN servicio_estado se on se.id_estado = t.id_estado
                                    WHERE t.id_servicio = $idservicio
                                    ORDER BY t.id_detalle, s.id_config");
      $cliver = $sqlcli->result();
      return $cliver;      
    }

    public function lst_detalle_serviciotmp($idusuario){
      $sqlcli = $this->db->query("SELECT t.id_detalle, t.id_serie, t.id_tecnico,
                                         t.descripcion, t.id_estado, t.fecha_realizado, 
                                         t.trabajo_realizado, t.fecha_entregado, 
                                         p.numeroserie, e.nombre_empleado, se.nombre_estado
                                    FROM servicio_detalle_tmp t
                                    LEFT JOIN empleado e on e.id_empleado = t.id_tecnico
                                    LEFT JOIN producto_serie p on p.id_serie = t.id_serie
                                    LEFT JOIN servicio_estado se on se.id_estado = t.id_estado
                                    WHERE t.id_usuario = $idusuario
                                    ORDER BY t.id_detalle");
      $cliver = $sqlcli->result();
      return $cliver;      
    }

    public function lst_detalleresumen_servicio($idservicio){
      $sqlcli = $this->db->query("SELECT t.id_detalle, t.id_serie, t.id_tecnico,
                                         t.descripcion, t.id_estado, t.fecha_realizado, 
                                         t.trabajo_realizado, t.fecha_entregado, 
                                         p.numeroserie, e.nombre_empleado, se.nombre_estado
                                    FROM servicio_detalle t
                                    LEFT JOIN empleado e on e.id_empleado = t.id_tecnico
                                    LEFT JOIN producto_serie p on p.id_serie = t.id_serie
                                    LEFT JOIN servicio_estado se on se.id_estado = t.id_estado
                                    WHERE t.id_servicio = $idservicio
                                    ORDER BY t.id_detalle");
      $cliver = $sqlcli->result();
      return $cliver;      
    }

    public function lst_subdetalle_servicio($iddetalle){
      $sqlcli = $this->db->query("SELECT s.id_subdetalle, s.id_config, s.valor, c.nombre_configdetalle
                                    FROM servicio_subdetalle s 
                                    INNER JOIN servicio_config_detalle c on c.id_config = s.id_config
                                    WHERE s.id_detalle = $iddetalle AND c.activo = 1
                                    ORDER BY s.id_config");
      $cliver = $sqlcli->result();
      return $cliver;      
    }

    public function sel_subdetalle_servicio_idconfig($iddetalle, $idconfig){
      $sqlcli = $this->db->query("SELECT s.id_subdetalle, s.id_config, s.valor, c.nombre_configdetalle
                                    FROM servicio_subdetalle s 
                                    INNER JOIN servicio_config_detalle c on c.id_config = s.id_config
                                    WHERE s.id_detalle = $iddetalle AND s.id_config = $idconfig 
                                      AND c.activo = 1");
      $cliver = $sqlcli->result();
      if (count($cliver) > 0)
        return $cliver[0];      
      else
        return NULL;
    }

    public function lst_subdetalle_servicio_tmp($iddetalle){
      $sqlcli = $this->db->query("SELECT s.id_subdetalle, s.id_config, s.valor, c.nombre_configdetalle
                                    FROM servicio_subdetalle_tmp s 
                                    INNER JOIN servicio_config_detalle c on c.id_config = s.id_config
                                    WHERE s.id_detalle = $iddetalle AND c.activo = 1
                                    ORDER BY s.id_config");
      $cliver = $sqlcli->result();
      return $cliver;      
    }

    public function genera_factura($idservicio, $idusu, $caja){
      $sqldel = $this->db->query("DELETE FROM venta_detalle_tmp where id_venta IN (select id_venta from venta_tmp where idusu = $idusu)");
      $sqldel = $this->db->query("DELETE from venta_tmp where idusu = $idusu");
      $sql_edi = $this->db->query("CALL servicio_facturar($idservicio, $idusu, $caja)");
      $resultado = $sql_edi->result();
      $id = $resultado[0]->vid; 
      $sql_edi->next_result(); 
      $sql_edi->free_result();   
      return $id;
    }   

    public function lst_produtil_tmp($iddetalle){
      $sqlcli = $this->db->query("SELECT t.id, t.id_detalle, t.id_producto, t.cantidad, 
                                         p.pro_nombre, p.pro_codigobarra, t.precio, t.id_almacen
                                    FROM servicio_producto_tmp t
                                    INNER JOIN producto p on p.pro_id = t.id_producto
                                    WHERE t.id_detalle = $iddetalle
                                    ORDER BY t.id");
      $cliver = $sqlcli->result();
      return $cliver;      
    }

    public function valortotal_produtil_tmp($iddetalle){
      $sqlcli = $this->db->query("SELECT SUM( ROUND(t.cantidad * t.precio, 2)) as total 
                                    FROM servicio_producto_tmp t
                                    WHERE t.id_detalle = $iddetalle");
      $cliver = $sqlcli->result();
      if ($cliver != null)
        return $cliver[0]->total;      
      else
        return 0;
    }

    public function lst_produtil_servicio($iddetalle){
      $sqlcli = $this->db->query("SELECT t.id, t.id_detalle, t.id_producto, t.cantidad, t.id_almacen,
                                         p.pro_nombre, p.pro_codigobarra, t.precio, p.pro_esservicio
                                    FROM servicio_producto t
                                    INNER JOIN producto p on p.pro_id = t.id_producto
                                    WHERE t.id_detalle = $iddetalle 
                                    ORDER BY t.id");
      $cliver = $sqlcli->result();
      return $cliver;      
    }

    /* OBTENER LISTADO DE PRODUCTOS  */
    public function lst_producto(){
      $sql = $this->db->query(" SELECT pro.pro_id, pro.pro_codigobarra, pro.pro_codigoauxiliar, pro.pro_nombre, 
                                      pro.pro_preciocompra, ifnull(ap.existencia,0) as existencia, 
                                      pro.pro_idunidadmedida as id_unimed, um.descripcion, um.nombrecorto,
                                      ap.id_alm, a.almacen_nombre
                                FROM producto pro 
                                INNER JOIN unidadmedida um ON um.id = pro.pro_idunidadmedida 
                                LEFT JOIN almapro ap on ap.id_pro = pro.pro_id
                                LEFT JOIN almacen a on a.almacen_id = ap.id_alm");

      $result = $sql->result();
      return $result;      
    }

    public function add_producto($iddetalle, $idpro, $idalmacen){
      $this->db->query("INSERT INTO servicio_producto_tmp (id_detalle, id_producto, cantidad, precio, id_almacen)
                          SELECT $iddetalle, $idpro, 1, pro_precioventa, $idalmacen
                            FROM producto WHERE pro_id = $idpro");
    }

    public function upd_producto($id, $cant){
      $this->db->query("UPDATE servicio_producto_tmp set cantidad = $cant WHERE id = $id");
    }

    public function upd_productoprecio($id, $precio){
      $this->db->query("UPDATE servicio_producto_tmp set precio = $precio WHERE id = $id");
    }

    public function del_producto($id){
      $this->db->query("DELETE FROM servicio_producto_tmp WHERE id = $id");
    }

    public function lst_producto_serviciotmp($idusuario){
      $sqlcli = $this->db->query("SELECT t.id_detalle, s.id_producto, s.cantidad, p.pro_nombre, s.precio, s.id_almacen
                                    FROM servicio_detalle_tmp t
                                    INNER JOIN servicio_producto_tmp s on s.id_detalle = t.id_detalle
                                    LEFT JOIN producto p on p.pro_id = s.id_producto
                                    WHERE t.id_usuario = $idusuario
                                    ORDER BY p.pro_nombre");
      $cliver = $sqlcli->result();
      return $cliver;      
    }

    public function lst_producto_servicio($idservicio){
      $sqlcli = $this->db->query("SELECT t.id_detalle, s.id_producto, s.cantidad, p.pro_nombre, s.precio, s.id_almacen
                                    FROM servicio_detalle t
                                    INNER JOIN servicio_producto s on s.id_detalle = t.id_detalle
                                    LEFT JOIN producto p on p.pro_id = s.id_producto
                                    WHERE t.id_servicio = $idservicio
                                    ORDER BY p.pro_nombre");
      $cliver = $sqlcli->result();
      return $cliver;      
    }

    public function lst_abonos_tmp($idusuario){
      $sqlcli = $this->db->query("SELECT t.id_abono, t.id_formapago, t.monto, t.fecha_emision, t.nro_comprobante,
                                         t.id_banco, t.fecha_cobro, t.numerocuenta, t.numerodocumento,
                                         t.descripciondocumento, t.id_tarjeta, t.numerotarjeta, 
                                         p.nombre_formapago,
                                         t.fecha_registro
                                    FROM servicio_abono_tmp t
                                    INNER JOIN formapago p on p.id_formapago = t.id_formapago
                                    WHERE t.id_usuario = $idusuario
                                    ORDER BY t.id_abono");
      $cliver = $sqlcli->result();
      return $cliver;      
    }

    public function selforpago($idforpago){
      $sql = $this->db->query("SELECT esinstrumentobanco AS banco, estarjeta AS tarjeta FROM formapago WHERE id_formapago = $idforpago");
      $resu = $sql->result();
      return $resu[0];
    }

    public function add_abonoservicio($idusu, $fp, $monto, $fechat, $tiptarjeta, $nrotar, $banco, $tbanco, $nrodoc, $tnrodoc, $descdoc, $tdescdoc, $fechae, $fechac, $nrocta, $idcaja){
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

      $strbanco = $banco;
      if ($strbanco == '') { $strbanco = $tbanco; }
      if ($strbanco == '') { $strbanco = 'NULL'; }

      $strfecha = $fechae;
      if ($strfecha == '') { $strfecha = $fechat; }
      if ($strfecha == '') { $strfecha = 'NULL'; }

      if ($tiptarjeta == '') { $tiptarjeta = 'NULL'; }

      $strnrodoc = $nrodoc;
      if ($strnrodoc == '') { $strnrodoc = $tnrodoc; }

      $strdescdoc = $descdoc;
      if ($strdescdoc == '') { $strdescdoc = $tdescdoc; }

      $sql = $this->db->query("INSERT INTO servicio_abono_tmp (id_usuario, id_formapago, monto, fecha_emision, fecha_registro, 
                                                               nro_comprobante, id_banco, fecha_cobro, numerocuenta,
                                                               numerodocumento, descripciondocumento, id_tarjeta, numerotarjeta,
                                                               id_cajapago)  
                                 SELECT $idusu, $fp, $monto, 
                                        case when ('$strfecha' <> '') then '$strfecha' else NULL end, 
                                        now(), 
                                        ifnull((select valor from contador WHERE id_contador = 7),1), 
                                        $strbanco, 
                                        case when ('$fechac' <> '') then '$fechac' else NULL end, 
                                        '$nrocta', '$strnrodoc', '$strdescdoc', $tiptarjeta, '$nrotar',
                                        $idcaja;");
    }

    public function upd_abonoservicio($idreg, $idusu, $fp, $monto, $fechat, $tiptarjeta, $nrotar, $banco, $tbanco, $nrodoc, $tnrodoc, $descdoc, $tdescdoc, $fechae, $fechac, $nrocta, $idcaja){
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

      $strbanco = $banco;
      if ($strbanco == '') { $strbanco = $tbanco; }
      if ($strbanco == '') { $strbanco = 'NULL'; }

      $strfecha = $fechae;
      if ($strfecha == '') { $strfecha = $fechat; }
      if ($strfecha == '') { $strfecha = 'NULL'; }

      if ($tiptarjeta == '') { $tiptarjeta = 'NULL'; }

      $strnrodoc = '';
      if ($bco == 1) { $strnrodoc = $nrodoc; }
      if ($tarj == 1) { $strnrodoc = $tnrodoc; }

      $strdescdoc = $descdoc;
      if ($strdescdoc == '') { $strdescdoc = $tdescdoc; }

      $this->db->query("UPDATE servicio_abono_tmp SET 
                            id_formapago = $fp, 
                            monto = $monto, 
                            fecha_emision = case when ('$strfecha' <> '') then '$strfecha' else NULL end, 
                            fecha_registro = now(), 
                            id_banco = $strbanco, 
                            fecha_cobro = case when ('$fechac' <> '') then '$fechac' else NULL end, 
                            numerocuenta = '$nrocta',
                            numerodocumento = '$strnrodoc', 
                            descripciondocumento = '$strdescdoc', 
                            id_tarjeta = $tiptarjeta, 
                            numerotarjeta = '$nrotar',
                            id_cajapago = $idcaja
                          WHERE id_abono = $idreg");
    }

    public function ediforpagoserv($idreg){
      $sql = $this->db->query("SELECT id_abono as idreg, id_formapago, monto, fecha_emision as fechaemision, 
                                      nro_comprobante, numerocuenta, fecha_cobro as fechacobro, 
                                      id_banco, numerodocumento, descripciondocumento,
                                      id_tarjeta, numerotarjeta, id_cajapago
                                  FROM servicio_abono_tmp WHERE id_abono = $idreg");
      $resu = $sql->result();
      return $resu[0];      
    }

    public function sel_tmpabonos($idusu){
      $sqlcli = $this->db->query("SELECT sum(monto) as monto FROM servicio_abono_tmp 
                                    WHERE id_usuario = $idusu");
      $cliver = $sqlcli->result();
      if (!$cliver != NULL){
        return $cliver[0]->monto;      
      }  
      else {
        return 0;      
      } 
    }

    /* Eliminar Abono */
    public function del_abono($idabono){
        $this->db->query("DELETE FROM servicio_abono_tmp WHERE id_abono = $idabono");
    }  

   /* Config Detalle */
    public function sel_configdetalle_id($idconfig){
      $query = $this->db->query("SELECT id_config, nombre_configdetalle, activo, mostrarenlistado
                                    FROM servicio_config_detalle
                                    WHERE id_config = $idconfig");
      $result = $query->result();
      return $result[0];
    }

    public function upd_configdetalle($idconfig, $nombre, $activo, $mostrar){
      $query = $this->db->query(" UPDATE servicio_config_detalle SET 
                                    nombre_configdetalle = '$nombre', 
                                    activo = $activo
                                   WHERE id_config = $idconfig");
      $this->upd_configdetalle_mostrar($idconfig, $mostrar);
    }

    public function add_configdetalle($nombre, $activo, $mostrar){
      $this->db->query("INSERT INTO servicio_config_detalle (nombre_configdetalle, activo)
                            VALUES('$nombre', $activo);");
      $query = $this->db->query("SELECT max(id_config) as id_config FROM servicio_config_detalle");
      $result = $query->result();
      $newid = $result[0];
      $this->upd_configdetalle_mostrar($newid, $mostrar);
    }

    public function upd_configdetalle_mostrar($idconfig, $mostrar){
        $this->db->query("UPDATE servicio_config_detalle SET mostrarenlistado = 0");
        if ($mostrar == 1)
          $this->db->query("UPDATE servicio_config_detalle SET mostrarenlistado = 1
                              WHERE id_config = $idconfig");
    }

    public function sel_configdetalle_mostrarenlistado(){
      $query = $this->db->query("SELECT id_config, nombre_configdetalle, activo, mostrarenlistado
                                    FROM servicio_config_detalle
                                    WHERE mostrarenlistado = 1");
      $result = $query->result();
      return $result;
    }

    public function candel_configdetalle($idconfig){
      $query = $this->db->query("SELECT count(*) as cant FROM servicio_subdetalle WHERE id_config = $idconfig");
      $result = $query->result();
/*      if ($result[0]->cant == 0){
        $query = $this->db->query("SELECT count(*) as cant FROM caja_efectivo WHERE id_puntoemision = $puntoemision");
        $result = $query->result();
      }*/
      if ($result[0]->cant == 0)
        { return 1; }
      else
        { return 0; }
    }

    public function del_configdetalle($idconfig){
      if ($this->candel_configdetalle($idconfig) == 1){
        $query = $this->db->query("DELETE FROM servicio_config_detalle WHERE id_config = $idconfig");
        return 1;
      } else {
        return 0;
      }
    }

    public function ins_servicio_egreso_inventario($idventa, $iddocinv){
      $this->db->query("INSERT INTO servicio_egresoinventario 
                          SELECT id_servicio, $iddocinv 
                            FROM venta v
                            INNER JOIN servicio s on s.id_venta = v.id_venta
                            WHERE v.id_venta = $idventa");
    }

    public function existe_numerorden($idusu, $idsucursal, $numerorden){
      $query = $this->db->query("SELECT id_servicio FROM servicio_tmp
                                   WHERE id_usuario = $idusu");
      $res = $query->result();
      $id_servicio = 0;
      if (count($res) > 0) { $id_servicio =  $res[0]->id_servicio;}
      if ($id_servicio == '') {$id_servicio = 0;}

      $query = $this->db->query("SELECT count(*) AS cant FROM servicio
                                   WHERE id_servicio != $id_servicio AND 
                                         id_sucursal = $idsucursal AND
                                         numero_orden = $numerorden");
      $resret = $query->result();
      return $resret[0]->cant;
    }

}
