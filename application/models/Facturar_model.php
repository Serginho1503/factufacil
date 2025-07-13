<?php

/* ------------------------------------------------
  ARCHIVO: Facturar_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Facturar.
  FECHA DE CREACIÓN: 15/08/2017
 * 
  ------------------------------------------------ */

class Facturar_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $query = $this->db->query("SET time_zone = '-5:00';");
    }

    /* Listado de las Areas con sus Mesas*/
    public function listado_mesas(){
      $sql = $this->db->query(" SELECT m.id_mesa, m.nom_mesa, a.nom_area, CONCAT(a.nom_area,' - ',m.nom_mesa) AS mesas  
                                FROM mesa m
                                INNER JOIN area a ON m.id_area = a.id_area
                                ORDER BY a.nom_area ");
      $resultado = $sql->result();
      return $resultado;
    }    

    /* CONSULTA DE CLIENTE Y MESERO POR ID DE MESA */
    public function cliente_mesa($id_mesa){
      $sql = $this->db->query(" SELECT p.id_mesa, p.est_mesa, c.id_cliente, c.ident_cliente,
                                       c.nom_cliente, c.correo_cliente, c.direccion_cliente, 
                                       c.telefonos_cliente, m.nom_mesero, p.descuento,
                                       (SELECT SUM(cantidad * precio) FROM pedido_detalle WHERE id_mesa = $id_mesa) AS sumsub
                                FROM pedido p
                                INNER JOIN clientes c ON c.id_cliente = p.id_cliente
                                LEFT JOIN mesero m ON m.id_mesero = p.id_mesero
                                WHERE p.id_mesa = $id_mesa");
      $resultado = $sql->result();
      return $resultado[0];
    }

    /* CONSULTA DEL PEDIDO POR ID DE MESA */
    public function pedido_mesa($id_mesa){
      $sql = $this->db->query(" SELECT pd.id_mesa, pd.id_producto, p.pro_nombre, pd.cantidad, pd.precio, (pd.cantidad * pd.precio) AS total, p.pro_grabaiva AS ap_iva 
                                FROM pedido_detalle pd
                                INNER JOIN producto p ON p.pro_id = pd.id_producto
                                WHERE id_mesa = $id_mesa");
      $resultado = $sql->result();
      return $resultado;
    }

    /* Determinar si ya se incluyo Efectivo */
    public function existe_pagoefectivo($formapago){
      if($formapago == 'Contado') {$idcancel = 1;} else {$idcancel = 2;}
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sql = $this->db->query("SELECT count(*) as cant from formapago_tmp f
                                 inner join venta_tmp v on v.id_venta=f.id_venta
                                 where id_formapago=1 and id_tipcancelacion=$idcancel and v.idusu=$idusu");
      $resultado = $sql->result();
      return $resultado[0]->cant;
    }

    /* SELECCIONAR LISTA DE FORMAS DE PAGO */
    public function lista_formapago($efectivo=1){
      $strsql = "SELECT id_formapago, cod_formapago, nombre_formapago, esinstrumentobanco, estarjeta FROM formapago";
      if ($efectivo != 0){
        $strsql = "SELECT id_formapago, cod_formapago, nombre_formapago, esinstrumentobanco, estarjeta FROM formapago where id_formapago <> 1";
      }
      $sql = $this->db->query($strsql);
      $resultado = $sql->result();
      return $resultado;
    }

    /* LISTADO DE BANCOS */
    public function bancos(){
      $sql = $this->db->query("SELECT id_banco, nombre FROM bancos order by nombre");
      $resultado = $sql->result();
      return $resultado;
    }

    /* LISTADO DE TARJETAS */
    public function tarjetas(){
      $sql = $this->db->query("SELECT id_tarjeta, nombre FROM tarjetas");
      $resultado = $sql->result();
      return $resultado;
    }

    /* OBTENER EL ID DEL CLIENTE DE LA TABLA PEDIDO PARA REGISTRAR EL TIPO DE PAGO */
    public function obtieneid_cliente($id_mesa){
      $sql = $this->db->query("SELECT id_cliente FROM pedido WHERE id_mesa = $id_mesa");
      $resultado = $sql->result();
      return $resultado[0];
    }

    /* AÑADIR A PEDIDO FORMA PAGO */
    public function add_formapago($id_mesa, $id_cliente, $cod_tipopago, $fecha, $monto, $banco, $tarjeta, $trache, $titular){
      /* Se Evalua segun el codigo de forma de pago */
      switch ($cod_tipopago) {
          /* Efectivo */
          case "1":
              if($id_mesa != NULL || $id_cliente > 0 || $fecha != NULL || $monto > 0){
                $sql = $this->db->query("INSERT INTO pedido_formapago (id_mesa, id_cliente, id_formapago, fecha, monto) VALUES 
                                                                      ($id_mesa, $id_cliente, $cod_tipopago, '$fecha', $monto)");
              }
              break;
          /* Tarjeta de Debido */    
          case "2":
            //  echo "Your favorite color is blue!";
              break;
          /* Tarjeta de Crédito */    
          case "3":
            //  echo "Your favorite color is green!";
              break;
          /* Transferencia */    
          case "4":
            //  echo "Your favorite color is green!";
              break;  
          /* Cheque */    
          case "5":
            //  echo "Your favorite color is green!";
              break;                           
          default:
            //  echo "Your favorite color is neither red, blue, nor green!";
      }      
      
    }

  /* OBTENER EL NUMERO CONSECUTIVO DE LA FACTURA */
  public function sel_nro_factura(){
    $usua = $this->session->userdata('usua');
    $idusu = $usua->id_usu;
    $sql = $this->db->query("SELECT lpad(IFNULL((SELECT consecutivo_factura 
                                                   FROM punto_emision p
                                                   INNER JOIN caja_efectivo c on c.id_puntoemision = p.id_puntoemision),1),9,'0') as nrofact");
    $resultado = $sql->result();
    return $resultado[0]->nrofact;
  }
/*  public function sel_nro_factura(){
    $sql = $this->db->query("SELECT (valor) AS nrofact FROM contador WHERE id_contador = 2");
    $resultado = $sql->result();
    $nro = $resultado[0]->nrofact;
    $long = strlen($nro);
        if($long == 1) { $cnt_fact = "00000000".$nro; }
    elseif($long == 2) { $cnt_fact = "0000000".$nro;  }
    elseif($long == 3) { $cnt_fact = "000000".$nro;   }
    elseif($long == 4) { $cnt_fact = "00000".$nro;    } 
    elseif($long == 5) { $cnt_fact = "0000".$nro;    }                 
    elseif($long == 6) { $cnt_fact = "000".$nro;    } 
    elseif($long == 7) { $cnt_fact = "00".$nro;    }                 
    elseif($long == 8) { $cnt_fact = "0".$nro;    } 
    elseif($long > 9)  { $cnt_fact = $nro;        }
    return $cnt_fact;
  }*/

  /* OBTENER EL NUMERO CONSECUTIVO DE NOTA DE VENTA */
  public function sel_nro_nronot(){
    $sql = $this->db->query("SELECT (valor) AS nronot FROM contador WHERE id_contador = 3");
    $resultado = $sql->result();
    $nro = $resultado[0]->nronot;
    $long = strlen($nro);
        if($long == 1) { $cont_nv = "00000000".$nro; }
    elseif($long == 2) { $cont_nv = "0000000".$nro;  }
    elseif($long == 3) { $cont_nv = "000000".$nro;   }
    elseif($long == 4) { $cont_nv = "00000".$nro;    } 
    elseif($long == 5) { $cont_nv = "0000".$nro;    }                 
    elseif($long == 6) { $cont_nv = "000".$nro;    } 
    elseif($long == 7) { $cont_nv = "00".$nro;    }                 
    elseif($long == 8) { $cont_nv = "0".$nro;    } 
    elseif($long > 9)  { $cont_nv = $nro;        }
    return $cont_nv;
  }

    /* PROCESO DE DESCUENTO EN VENTA */
    public function descuento($id_mesa, $desc){
      $subtotal = 0;
      $descmonto = 0;
      $descsubtotal = 0;
      /* Obtener el valor del IVA */
      $sql_iva = $this->db->query("SELECT valor FROM parametros WHERE id = 1");
      $resuiva = $sql_iva->result();
      $iva = $resuiva[0]->valor;      
      //$iva = 0.12;
      $desc = str_replace(",", ".", $desc);
      /* Se escribe el monto del descuento en la tabla tmp_compra*/
      $sql = $this->db->query("UPDATE pedido SET descuento = $desc WHERE id_mesa = $id_mesa");
      


    }  

/* =============================================================================================================================================== */


    /* GUARDAR FACTURA */
    public function pagar_factura($fecha, $area, $idmesa, $mesa, $mesero, $nro_factura, $tipo_ident, $nro_ident, $nom_cliente, $cor_cliente, $telf_cliente, $dir_cliente, $subconiva, $subsiniva, $desc_monto, $descsubconiva, $descsubsiniva, $montoiva, $montototal, $idusu, $fp, $nro_notaventa, $efectivo, $tarjeta){

      $subconiva = str_replace(",", ".", $subconiva);
      $subsiniva = str_replace(",", ".", $subsiniva);
      $desc_monto = str_replace(",", ".", $desc_monto);
      $descsubconiva = str_replace(",", ".", $descsubconiva);      
      $descsubsiniva = str_replace(",", ".", $descsubsiniva);         
      $montoiva = str_replace(",", ".", $montoiva);
      $montototal = str_replace(",", ".", $montototal);
      $subconiva = trim(str_replace("$", "", $subconiva));
      $subsiniva = trim(str_replace("$", "", $subsiniva));
      $desc_monto = trim(str_replace("$", "", $desc_monto));
      $descsubconiva = trim(str_replace("$", "", $descsubconiva));      
      $descsubsiniva = trim(str_replace("$", "", $descsubsiniva));        
      $montoiva = trim(str_replace("$", "", $montoiva));
      $montototal = trim(str_replace("$", "", $montototal));

      $sumsub = $subconiva + $subsiniva;

      if($fp == 2){ 
        //$nro = $this->sel_nro_factura();
        //$consecutivo = "001-001-".$nro;
        $docpago = $nro_factura; 
        //$docpago = $consecutivo; 

      } else { $docpago = $nro_notaventa; } 

      /* Obtener el valor del IVA */
      $sql_iva = $this->db->query("SELECT valor FROM parametros WHERE id = 1");
      $resuiva = $sql_iva->result();
      $iva = $resuiva[0]->valor;
      $valiva = $iva;
      $desc = $desc_monto;      



        /* Se guarda la cabecera de la venta en la tabla venta */
        $sql_add = $this->db->query("call venta_ins ('$fecha', '$area', '$mesa', '$mesero', $fp, '$docpago', '$tipo_ident', '$nro_ident', '$nom_cliente', 
                                                      '$telf_cliente', '$dir_cliente', $valiva, $subconiva, $subsiniva, $desc_monto,
                                                       $descsubconiva, $descsubsiniva, $montoiva, $montototal, $idmesa, $idusu)");
        $resultado = $sql_add->result();
        $id = $resultado[0]->vid; /* Se obtiene el id del registro insertado en la tabla venta para relacionarlo con venta_detalle */

        $sql_add->next_result(); 
        $sql_add->free_result();        




      /* Guardar tipo de pago si es efectivo o tarjeta */    
      $sql_pago = $this->db->query("INSERT INTO venta_formapago (id_venta, id_formapago, monto) VALUES ($id, 1, $efectivo)");
      $sql_pago = $this->db->query("INSERT INTO venta_formapago (id_venta, id_formapago, monto) VALUES ($id, 2, $tarjeta)");


      /* Seleccionar los datos de pedido_detalle y aplicarle los cambios en la facturacion para guardarlos */
      $sql_pedido = $this->db->query("SELECT pd.id_producto, pd.cantidad, pd.precio, (pd.cantidad * pd.precio) AS subtotal, p.pro_grabaiva AS ap_iva 
                                      FROM pedido_detalle pd
                                      INNER JOIN producto p ON p.pro_id = pd.id_producto
                                      WHERE id_mesa = $idmesa");
      $resupedido = $sql_pedido->result();
      foreach ($resupedido as $rp) {
        $id_producto = $rp->id_producto;
        $cantidad = $rp->cantidad;
        $precio = $rp->precio;
        $prosub = $rp->subtotal;
        $apiva = $rp->ap_iva;
        $vmontoiva = 0;
        $vdescmonto = 0;
        $vdescsubtotal = 0;  
          if($desc > 0){
            $vdescmonto =  $prosub / $sumsub * $desc;
            $vdescsubtotal = $prosub - $vdescmonto;
          }else{
            $vdescsubtotal = $prosub;
          }
          if($apiva == 1){ $vmontoiva = $vdescsubtotal * $iva; }
          else{ $vmontoiva = 0; }

          $sql_det = $this->db->query("INSERT INTO venta_detalle (id_venta, id_producto, cantidad, precio, subtotal, iva, montoiva, descmonto, descsubtotal)
                                                         VALUES($id, $id_producto, $cantidad, $precio, $prosub, $apiva, $vmontoiva, $vdescmonto, $vdescsubtotal)");

      }

      $sql_del = $this->db->query("DELETE FROM pedido WHERE id_mesa = $idmesa");
      $sql_del = $this->db->query("DELETE FROM pedido_detalle WHERE id_mesa = $idmesa");
      $sql_del = $this->db->query("DELETE FROM pedido_detalle_variante WHERE id_mesa = $idmesa");

      /* ACTULIZA CONTADORES */
      $sql = $this->db->query("SELECT (valor + 1) AS nroval FROM contador WHERE id_contador = $fp");
      $resultado = $sql->result();
      $nro = $resultado[0]->nroval;  
      $sql_upd = $this->db->query("UPDATE contador SET valor = $nro WHERE id_contador = $fp");



      return $id;

    }


/* =============================================================================================================================================== */

    /* LISTADO DE VENTA */
    public function venta_lst(){
      $sql = $this->db->query(" SELECT id_venta, fecha, nro_factura, mesa, nom_cliente, montototal 
                                FROM venta WHERE fecha = CURDATE()");
      $resultado = $sql->result();
      return $resultado;
    }

    /* LISTADO DE VENTA REPORTE */
    public function venta_rpt($desde, $hasta){
      $sql = $this->db->query("SELECT id_venta, fecha, nro_factura, 
                                      CONCAT(mesa,' - ',area) AS area_mesa, 
                                      nom_cliente, nro_ident, dir_cliente, 
                                      (ifnull(descsubconiva,0)+ifnull(descsubsiniva,0)) as subtotal,
                                      montototal
                                 FROM venta WHERE fecha BETWEEN '$desde' AND '$hasta' ");
      $resultado = $sql->result();
      return $resultado;
    }


    /* Datos Generales Factura 
    public function datosfactura($id_factura){
      $sel_obj = $this->db->query("SELECT v.nom_cliente, v.tipo_ident, v.nro_ident, 
                                          v.telf_cliente, v.dir_cliente, v.mesa, v.mesero,
                                          v.nro_factura, v.fecha, v.estatus, v.tipo_doc, v.fecharegistro,
                                          v.montoimpuestoadicional, v.cambio, v.nro_orden, v.id_tipcancelacion
                                  FROM  venta v
                                  WHERE v.id_venta = $id_factura");
      $resultado = $sel_obj->result();
      return $resultado;
    }  
*/
    public function datosfactura($id_factura){
      $sel_obj = $this->db->query("SELECT v.nom_cliente, v.tipo_ident, v.nro_ident, 
                                          v.telf_cliente, v.dir_cliente, v.mesa, v.mesero,
                                          v.nro_factura, v.fecha, v.estatus, v.tipo_doc, v.fecharegistro,
                                          v.montoimpuestoadicional, v.cambio, v.nro_orden, v.id_tipcancelacion, 
                                          v.idusu, 
                                          CONCAT(u.nom_usu,' ',u.ape_usu) AS usuario,
                                          IFNULL(CONCAT(uv.nom_usu,' ',uv.ape_usu),'') AS vendedor,
                                          v.id_empresa, v.id_sucursal, v.id_puntoemision, v.id_caja,
                                          v.observaciones, v.placa_matricula,
                                          IFNULL((SELECT claveacesso FROM facturainfoestadosri 
                                             WHERE idfactura = $id_factura),'') as claveacceso
                                    FROM  venta v
                                    INNER JOIN usu_sistemas u ON u.id_usu = v.idusu
                                    LEFT JOIN usu_sistemas uv ON uv.id_usu = v.id_vendedor
                                  WHERE v.id_venta = $id_factura");
      $resultado = $sel_obj->result();
      return $resultado;
    }

    public function resfactura($id_factura){
      $sel_obj = $this->db->query(" SELECT id_venta, tipo_doc, subconiva, subsiniva, desc_monto,descsubconiva, descsubsiniva, montoiva, montototal  
                                    FROM venta WHERE id_venta = $id_factura");
      $resultado = $sel_obj->result();
      return $resultado[0];
    } 

    public function ventadetalle($id_factura){
      $sql_sel = $this->db->query(" SELECT d.id_producto, d.descripcion as pro_nombre,  
                                             sum(d.subsidio) as  subsidio,
                                             d.precio, p.pro_grabaiva, null as numeroserie,
                                             sum(d.cantidad) as cantidad, sum(d.descsubtotal) as subtotal, 
                                             sum(montoiva) as montoiva,
                                             d.porcdesc as porcdesc, d.descmonto
                                        FROM venta_detalle d
                                        INNER JOIN producto p ON p.pro_id = d.id_producto
                                        WHERE d.id_venta = $id_factura 
                                        GROUP BY d.id_producto, d.precio, p.pro_grabaiva");
      $result = $sql_sel->result();
      return $result;
    }    
    public function ventadetalle_hastamarzo22($id_factura){
      $sql_sel = $this->db->query(" SELECT count(*) as cant FROM venta_detalle d
                                    INNER JOIN producto_serie p ON p.id_detalleventa = d.id_detalle
                                    WHERE d.id_venta = $id_factura");
      $result = $sql_sel->result();
      if ($result[0]->cant > 0){
        $sql_sel = $this->db->query("SELECT d.id_producto, d.descripcion as pro_nombre, 
                                            d.precio, p.pro_grabaiva, d.subsidio,
                                            d.cantidad, d.descsubtotal as subtotal, d.montoiva, s.numeroserie, d.porcdesc
                                      FROM venta_detalle d
                                      INNER JOIN producto p ON p.pro_id = d.id_producto
                                      LEFT JOIN producto_serie s ON s.id_detalleventa = d.id_detalle
                                      WHERE d.id_venta = $id_factura");
      } else {
        $sql_sel = $this->db->query(" SELECT d.id_producto, d.descripcion as pro_nombre,  
                                             sum(d.subsidio) as  subsidio,
                                             d.precio, p.pro_grabaiva, null as numeroserie,
                                             sum(d.cantidad) as cantidad, sum(d.descsubtotal) as subtotal, 
                                             sum(montoiva) as montoiva,
                                             d.porcdesc as porcdesc
                                        FROM venta_detalle d
                                        INNER JOIN producto p ON p.pro_id = d.id_producto
                                        WHERE d.id_venta = $id_factura 
                                        GROUP BY d.id_producto, d.precio, p.pro_grabaiva");
      }  
      $result = $sql_sel->result();
      return $result;
    }    
    public function ventadetalle00($id_factura){
      $sql_sel = $this->db->query(" SELECT d.id_producto, p.pro_nombre, d.cantidad, d.precio, d.subtotal, p.pro_grabaiva
                                    FROM venta_detalle d
                                    INNER JOIN producto p ON p.pro_id = d.id_producto
                                    WHERE d.id_venta = $id_factura ");
      $result = $sql_sel->result();
      return $result;
    }    
  
    /* PRODUCTOS MAS VENDIDOS */
    public function productosmasvendidos(){
      $sql = $this->db->query("SELECT SUM(d.cantidad) AS cantidadtotal, d.id_producto, p.pro_nombre,
                                      p.pro_precioventa, c.cat_descripcion,
                                      p.pro_codigoauxiliar, p.pro_codigobarra  
                                  FROM venta_detalle d
                                  INNER JOIN producto p on p.pro_id = d.id_producto
                                  LEFT JOIN categorias c on c.cat_id = p.pro_idcategoria
                                  GROUP BY d.id_producto
                                  ORDER BY cantidadtotal desc");
      $resultado = $sql->result();
      return $resultado;
    }

    public function montomasvendidosrango($desde, $hasta){
      $sql = $this->db->query(" SELECT ROUND(SUM(d.descsubtotal + d.montoiva),2) AS total
                                FROM venta_detalle d
                                INNER JOIN venta v ON v.id_venta = d.id_venta
                                INNER JOIN producto p on p.pro_id = d.id_producto
                                LEFT JOIN categorias c on c.cat_id = p.pro_idcategoria
                                WHERE v.fecharegistro BETWEEN '$desde' AND '$hasta'
                                AND v.estatus != 3");
      $resultado = $sql->result();
      $monto = $resultado[0]->total;
      return $monto;
    }

    public function productosmasvendidosrango($desde, $hasta){
      $sql = $this->db->query("SELECT SUM(d.cantidad) AS cantidadtotal, d.id_producto, p.pro_nombre,
                                      p.pro_precioventa, c.cat_descripcion,p.pro_codigoauxiliar, 
                                      p.pro_codigobarra, ROUND(SUM(d.descsubtotal + d.montoiva),2) AS total
                                  FROM venta_detalle d
                                  INNER JOIN venta v ON v.id_venta = d.id_venta
                                  INNER JOIN producto p on p.pro_id = d.id_producto
                                  LEFT JOIN categorias c on c.cat_id = p.pro_idcategoria
                                  WHERE v.fecharegistro BETWEEN '$desde' AND '$hasta'
                                  AND v.estatus != 3
                                  GROUP BY d.id_producto
                                  ORDER BY cantidadtotal desc");
      $resultado = $sql->result();
      return $resultado;
    }

    
    /* DATOS DEL CLIENTE PARA FACTURAR */
    public  function data_cliente($nro_ident, $nom_cliente, $cor_cliente, $telf_cliente, $dir_cliente){
      /* verificar que exista el cliente */
      $sqlcli = $this->db->query("SELECT COUNT(*) AS nrocli FROM clientes WHERE ident_cliente = '$nro_ident' ");
      $resucli = $sqlcli->result();
      $valcli = $resucli[0]->nrocli;

      if($valcli > 0){
        /* actualiza los datos del cliente */
        $sql_updc = $this->db->query("UPDATE clientes 
                                          SET tipo_ident_cliente = 'C',
                                              nom_cliente = '$nom_cliente',
                                              ident_cliente = '$nro_ident',
                                              correo_cliente = '$cor_cliente',
                                              telefonos_cliente = '$telf_cliente',
                                              direccion_cliente = '$dir_cliente'
                                        WHERE id_cliente!=1 and ident_cliente = '$nro_ident' ");    

      }else{
        if($cor_cliente != NULL || $cor_cliente = ""){}else{$cor_cliente = " ";} 
        if($telf_cliente != NULL || $telf_cliente = ""){}else{$telf_cliente = " ";}
        if($dir_cliente != NULL || $dir_cliente = ""){}else{$dir_cliente = " ";}
        
        $sql_addc = $this->db->query("INSERT INTO clientes (tipo_ident_cliente, nom_cliente, ident_cliente, correo_cliente, telefonos_cliente, direccion_cliente) 
                                                    VALUES ('C', '$nom_cliente', '$nro_ident', '$cor_cliente',' $telf_cliente', '$dir_cliente')");
      }

      $sql = $this->db->query(" SELECT tipo_ident_cliente, ident_cliente, nom_cliente, correo_cliente,     
                                       telefonos_cliente, direccion_cliente, id_cliente 
                                FROM clientes
                                WHERE ident_cliente = $nro_ident");
      $resu = $sql->result();
      return $resu[0];      


    }


    /* ACTUALIZAR EL INVENTARIO */
    public function inventario($id_venta){
      $sql = $this->db->query("SELECT vd.id_venta, vd.id_producto, vd.cantidad, 
                                      p.cantidad AS cantpro, ifnull(a.existencia, 0) as existencia, vd.id_almacen,
                                      vd.cantidad AS resultado, preparado
                                      FROM venta_detalle vd
                                      INNER JOIN producto p ON p.pro_id = vd.id_producto
                                      LEFT JOIN almapro a ON a.id_pro = vd.id_producto and a.id_alm = vd.id_almacen
                                      WHERE vd.id_venta = $id_venta AND (IFNULL(p.productodescontarventa, 0) = 0)");
      $resu = $sql->result();

      foreach ($resu as $res) {
        $idpro = $res->id_producto;
        $idalm = $res->id_almacen;
        $dif = $res->resultado;
        if ($res->preparado == 0) {
          $sql = $this->db->query("UPDATE almapro set existencia = existencia - $dif where id_pro = $idpro and id_alm=$idalm");
        } else {
          $cant = $res->cantidad;
          $sql = $this->db->query("UPDATE almapro a 
                                    inner join producto_ingrediente i on i.id_proing = a.id_pro
                                    inner join producto p on p.pro_id = i.id_proing 
                                    left join unidadfactorconversion fd on fd.idunidad1 = i.unimed and fd.idunidadequivale = p.pro_idunidadmedida 
                                    left join unidadfactorconversion fi on fi.idunidad1 = p.pro_idunidadmedida and fi.idunidadequivale = i.unimed 
                                    set existencia = a.existencia - 
                                                     round(case when i.unimed = p.pro_idunidadmedida then 1
                                                                when ifnull(fd.idunidad1,0) != 0 then fd.cantidadequivalente
                                                                when ifnull(fi.idunidad1,0) != 0 then 1/fi.cantidadequivalente
                                                                else 0
                                                           end * i.cantidad * $cant,2)
                                    where i.id_pro = $idpro and id_alm=$idalm;");          
        }

      }

      $sql = $this->db->query("SELECT idmesa FROM venta WHERE id_venta = $id_venta");
      $resu = $sql->result();
      $idmesa = 0;
      if ($resu) { $idmesa = $resu[0]->idmesa; }
      if ($idmesa == ''){$idmesa = 0;}

      $sql = $this->db->query("SELECT vd.id_venta, p.productodescontarventa, vd.cantidad, 
                                      p.cantidad AS cantpro, ifnull(a.existencia, 0) as existencia, vd.id_almacen,
                                      (ifnull(a.existencia, 0) - (vd.cantidad * p.cantidad))  AS resultado,
                                      p.habilitavariante,
                                      ifnull((SELECT sum(pd.cantidad) FROM pedido_detalle_variante pd
                                        INNER JOIN pedido_detalle dd on dd.id_ped = pd.id_ped
                                        WHERE dd.id_mesa = $idmesa AND dd.id_almacen = vd.id_almacen AND
                                              vd.id_producto = dd.id_producto),0) * vd.cantidad as cantvariante
                                      FROM venta_detalle vd
                                      INNER JOIN producto p ON p.pro_id = vd.id_producto
                                      LEFT JOIN almapro a ON a.id_pro = p.productodescontarventa and a.id_alm = vd.id_almacen
                                      WHERE vd.id_venta = $id_venta AND (IFNULL(p.productodescontarventa, 0) != 0)");
      $resu = $sql->result();

      foreach ($resu as $res) {
        $idpro = $res->productodescontarventa;
        $idalm = $res->id_almacen;
        $dif = $res->resultado;
        if ($res->habilitavariante != 1){
          $sql = $this->db->query("UPDATE almapro set existencia = $dif where id_pro = $idpro and id_alm=$idalm");
        }
        else{
          $resvar = $res->existencia - $res->cantvariante;
          $sql = $this->db->query("UPDATE almapro set existencia = $resvar where id_pro = $idpro and id_alm=$idalm");
        }
      }

      $this->db->query("DELETE FROM pedido_detalle_variante WHERE id_mesa = $idmesa");
      $this->db->query("DELETE FROM pedido_detalle WHERE id_mesa = $idmesa");
      $this->db->query("DELETE FROM pedido WHERE id_mesa = $idmesa");
      $this->db->query("UPDATE mesa SET id_estado = IFNULL((SELECT valor FROM parametros WHERE id = 42), 1)
                          WHERE id_mesa = $idmesa");

    }

    /* MONTO ACTUAL DE LA VENTA */
    public function ventaactual(){
      $sqlmonto = $this->db->query("SELECT SUM(montototal) AS monto FROM venta WHERE fecha = CURDATE()");
      $res = $sqlmonto->result();
      $monto = $res[0]->monto;
      return $monto;
    }

    /* LISTADO DE VENTA POR RANGO DE FECHA */
    public function venta_rango($desde, $hasta, $vendedor = 0, $sucursal = 0, $tipofecha = 1){
      $usua = $this->session->userdata('usua');
      $idusuario = $usua->id_usu;
      if ($tipofecha == 1)
        $strtipofecha = "v.fecharegistro";
      else
        $strtipofecha = "v.fecha";
      $sql = $this->db->query(" SELECT v.id_venta, c.nom_caja, v.fecha, v.nro_factura, tc.nom_cancelacion, v.mesa, 
                                       TRIM(REPLACE(REPLACE(REPLACE(v.nom_cliente,'\t',''),'\n',''),'\r','')) as nom_cliente,                                       
                                       v.nro_ident, v.dir_cliente, v.id_cliente,
                                       $strtipofecha as fecharegistro,
                                       v.id_vendedor, v.observaciones,
                                       case WHEN u.id_usu IS NULL THEN '' ELSE concat(nom_usu,' ',ape_usu) end as vendedor,
                                (ifnull(v.descsubconiva,0)+ifnull(v.descsubsiniva,0)) as subtotal, v.montototal, v.estatus, 
                                (ifnull((SELECT SUM(monto) FROM venta_formapago WHERE id_venta = v.id_venta AND id_formapago = 1),0)) as efectivo, 
                                (ifnull((SELECT SUM(monto) FROM venta_formapago WHERE id_venta = v.id_venta AND id_formapago = 2),0)) as cheque,
                                (ifnull((SELECT SUM(monto) FROM venta_formapago WHERE id_venta = v.id_venta AND id_formapago = 3),0)) as tarjetac, 
                                (ifnull((SELECT SUM(monto) FROM venta_formapago WHERE id_venta = v.id_venta AND id_formapago = 4),0)) as tarjetad, 
                                (ifnull((SELECT SUM(monto) FROM venta_formapago WHERE id_venta = v.id_venta AND id_formapago = 5),0)) as tarjetap,
                                (ifnull((SELECT SUM(monto) FROM venta_formapago WHERE id_venta = v.id_venta AND id_formapago = 6),0)) as transferencia,
                                (ifnull((SELECT SUM(monto) FROM venta_formapago WHERE id_venta = v.id_venta AND id_formapago = 7),0)) as dinele,
                                (ifnull((SELECT SUM(monto) FROM venta_formapago WHERE id_venta = v.id_venta AND id_formapago = 8),0)) as otros,
                                IFNULL((SELECT sum(monto) FROM venta_formapago p 
                                   INNER JOIN servicio_abono a on a.id_docpago = p.id
                                   INNER JOIN servicio s on s.id_servicio = a.id_servicio
                                   WHERE s.id_venta = v.id_venta),0) as anticipo
                                FROM venta v 
                                LEFT JOIN caja_efectivo c ON c.id_caja = v.id_caja
                                INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = v.id_tipcancelacion
                                LEFT JOIN usu_sistemas u ON u.id_usu = v.id_vendedor
                                WHERE $strtipofecha BETWEEN '$desde' AND '$hasta' AND
                                      (($vendedor = 0) OR (v.id_vendedor = $vendedor)) AND
                                      (($sucursal = 0) OR (v.id_sucursal = $sucursal)) AND
                                      v.id_caja in (SELECT id_caja FROM permiso_cajaefectivo WHERE id_usuario = $idusuario)
                                ORDER BY fecha,nro_factura DESC ");
      
      $resu = $sql->result();
      return $resu;
    }

    public function venta_rango_usu($desde, $hasta, $idusu){

      $sql = $this->db->query(" SELECT v.id_venta, v.fecha, v.nro_factura, v.mesa, c.nom_cliente, v.montototal, v.estatus, v.fecharegistro  
                                FROM venta v
                                INNER JOIN clientes c ON c.ident_cliente = v.nro_ident
                                WHERE  idusu = $idusu
                                AND v.fecharegistro BETWEEN '$desde' AND '$hasta' 
                                ORDER BY v.id_venta DESC ");
      $resu = $sql->result();
      return $resu;
    }




   /* VENTAS TOTALES POR RANGO */
    public function ventas_total_rango($desde, $hasta, $vendedor = 0, $sucursal = 0, $tipofecha = 1){
      if ($tipofecha == 1)
        $strtipofecha = "fecharegistro";
      else
        $strtipofecha = "fecha";
      $sql = $this->db->query("SELECT SUM(montototal) AS total FROM venta 
                                 WHERE $strtipofecha BETWEEN '$desde' AND '$hasta' AND
                                      (($vendedor = 0) OR (id_vendedor = $vendedor)) AND
                                      (($sucursal = 0) OR (id_sucursal = $sucursal)) AND
                                      $strtipofecha BETWEEN '$desde' AND '$hasta' AND estatus != 3");
      $resu = $sql->result();
      $total = $resu[0]->total;
      return $total;
    }

   /* VENTAS TOTALES POR RANGO */
    public function ventas_total_rango_usu($desde, $hasta, $idusu){
      $sql = $this->db->query("SELECT SUM(montototal) AS total FROM venta 
                                 WHERE fecharegistro BETWEEN '$desde' AND '$hasta' AND estatus != 3  AND idusu = $idusu");
      $resu = $sql->result();
      $total = $resu[0]->total;
      return $total;
    }

    /* LISTADO DE VENTA POR RANGO DE FECHA */
    public function venta_detalles_rango($desde, $hasta, $vendedor = 0, $sucursal = 0){
      $sql = $this->db->query(" SELECT v.id_venta, v.fecha, v.nro_factura, v.nom_cliente, v.nro_ident, 
                                       v.id_vendedor, v.placa_matricula, v.estatus, v.fecharegistro,
                                       d.descripcion, d.cantidad, u.descripcion as unidadmedida,
                                       d.precio, d.descsubtotal, d.montoiva, 
                                       round(d.descsubtotal + d.montoiva, 2) as valortotal,
                                       round(d.precio + p.subsidio, 6) as preciosinsubsidio,
                                       round(d.descsubtotal + d.montoiva + (p.subsidio * d.cantidad), 2) as valorsinsubsidio,
                                       round(p.subsidio * d.cantidad, 2) as ahorroporsubsidio
                                FROM venta_detalle d 
                                INNER JOIN venta v on v.id_venta = d.id_venta
                                INNER JOIN producto p on p.pro_id = d.id_producto
                                LEFT JOIN unidadmedida u on u.id = p.pro_idunidadmedida
                                WHERE v.fecharegistro BETWEEN '$desde' AND '$hasta' AND
                                      (($vendedor = 0) OR (v.id_vendedor = $vendedor)) AND
                                      (($sucursal = 0) OR (v.id_sucursal = $sucursal))
                                ORDER BY fecha DESC,nro_factura DESC ");
      
      $resu = $sql->result();
      return $resu;
    }


    /* BUSQUEDA DE LA FACTURA QUE SE VA A ANULAR */  
    public function busca_factura($id_venta){
      $sql = $this->db->query(" SELECT  id_venta, fecharegistro, area, mesa, mesero, nro_factura,
                                        nro_ident, nom_cliente, telf_cliente, dir_cliente, 
                                        montototal, idusu
                                FROM venta WHERE id_venta = $id_venta");
      $resu = $sql->result();
      return $resu[0];
    }


    /*  ANULAR FACTURA  */  
    public function anular_factura($id_venta, $obs){
      $usua = $this->session->userdata('usua');
      $idusuario = $usua->id_usu;
      
      $query = $this->db->query("call venta_null($id_venta, $idusuario, '$obs');");

      $resu = $query->result();

      $query->next_result(); 
      $query->free_result();

      return $resu[0];
    }

    /* MODIFICACION DE FACTURA QUE IMPLICA ANULAR Y CREAR NUEVA FACTURA */  
    public function anularycrear_factura($id_venta, $causa, $fecha, $tipo_doc, $nro_factura, 
                                         $tipo_ident, $nro_ident, $nom_cliente, $telf_cliente, $dir_cliente){
      $usua = $this->session->userdata('usua');
      $idusuario = $usua->id_usu;
      
      $query = $this->db->query("call venta_newfromnull($id_venta, '$causa', '$fecha', $tipo_doc, 
                                                        '$nro_factura', '$tipo_ident', '$nro_ident', 
                                                        '$nom_cliente', '$telf_cliente', '$dir_cliente', $idusuario);");

      $resu = $query->result();

      $query->next_result(); 
      $query->free_result();

      return $resu[0];
    }


    /* BUSCAR FACTURA PARA EDITARLA */
    public function edit_factura($idfactura){
      $sql = $this->db->query(" SELECT id_venta, fecha, mesa, mesero, tipo_doc, nro_ident, nro_factura,
                                       subconiva, subsiniva, desc_monto, descsubconiva, descsubsiniva,
                                       montoiva, montototal   
                                FROM venta
                                WHERE id_venta = $idfactura");
      $resu = $sql->result();
      return $resu[0];
    }

    /* BUSCA AL CLIENTE POR NUMERO DE CEDULA */
    public function busca_cliente($nro_ident){
      $sql = $this->db->query("SELECT id_cliente, tipo_ident_cliente, ident_cliente, nom_cliente, 
                                      correo_cliente, telefonos_cliente, direccion_cliente, 
                                      placa_matricula, id_vendedor 
                                FROM clientes
                                WHERE ident_cliente = $nro_ident");
      $resu = $sql->result();
      return $resu[0];       
    }

    /* FACTURA DETALLE */
    public function det_factura($idfactura){
      $sql = $this->db->query(" SELECT vd.id_venta, vd.id_producto, p.pro_nombre, vd.cantidad, vd.precio, vd.subtotal, vd.descsubtotal 
                                FROM venta_detalle vd
                                INNER JOIN producto p ON p.pro_id = vd.id_producto
                                WHERE vd.id_venta = $idfactura");
      $resu = $sql->result();
      return $resu;
    }

/* ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ */
/* PROCESO DE FACTURACION GENERAL */
    
    public function tipo_factura(){
      $sql = $this->db->query("SELECT id_contador, categoria FROM contador WHERE id_contador in (2,3)");
      $resu = $sql->result();
      return $resu;
    }

    public function tipo_identificacion(){
      $sql = $this->db->query("SELECT cod_identificacion as cod, desc_identificacion as det FROM identificacion");
      $resu = $sql->result();
      return $resu;
    }

    public function selclitmp($idusu){
      $sql = $this->db->query("SELECT id_cliente FROM venta_tmp WHERE idusu = $idusu");
      $resu = $sql->result();
      $res = $resu[0]->id_cliente;
      return $res;
    }

    public function carga_cliente($idusu, $nrofact){
      $sql = $this->db->query("SELECT COUNT(*) AS val FROM venta_tmp WHERE idusu = $idusu");
      $cliven = $sql->result();
      $val = $cliven[0]->val;

      if($val == 0){
        $selcli = $this->db->query("SELECT id_cliente, tipo_ident_cliente, ident_cliente, nom_cliente FROM clientes WHERE id_cliente = 1");
        $clinvo = $selcli->result();
        $id_clid = $clinvo[0]->id_cliente;
        $tp_cli = "R"/*$clinvo[0]->tipo_ident_cliente*/;
        $id_cli = "9999999999999"/*$clinvo[0]->ident_cliente*/;
        $no_cli = "CONSUMIDOR FINAL"/*$clinvo[0]->nom_cliente*/; 
        $fecha = date('Y-m-d');

        $sqlusu = $this->db->query("SELECT u.id_usu, u.id_mesero, u.id_punto, m.nom_mesa, a.nom_area, me.nom_mesero 
                                    FROM usu_sistemas u
                                    INNER JOIN mesa m ON m.id_mesa = u.id_punto 
                                    INNER JOIN area a ON a.id_area = m.id_area
                                    INNER JOIN mesero me ON me.id_mesero = u.id_mesero
                                    WHERE u.id_usu = $idusu");
        $pto = $sqlusu->result(); 
        if(COUNT($pto) > 0){
          $area = $pto[0]->nom_area;
          $punto = $pto[0]->nom_mesa;
          $vendedor = $pto[0]->nom_mesero;          
        }else{
          $area = NULL;
          $punto = NULL;
          $vendedor = NULL;        
        }

        $this->db->query("INSERT INTO venta_tmp (fecha, area, mesa, mesero, tipo_doc, nro_factura, tipo_ident, 
                                                 nro_ident, nom_cliente, idusu, id_cliente, id_proforma, 
                                                 id_caja, id_vendedor)
                            SELECT '$fecha', '$area', '$punto', '$vendedor', 2,'$nrofact','$tp_cli','$id_cli',
                                   '$no_cli', $idusu,  $id_clid, 0,
                                   ifnull((SELECT c.id_caja FROM caja_efectivo c
                                             INNER JOIN punto_emision p on p.id_puntoemision = c.id_puntoemision
                                             INNER JOIN permiso_cajaefectivo pc on pc.id_caja = c.id_caja
                                             WHERE c.activo = 1 and p.activo = 1 and pc.id_usuario = $idusu
                                             ORDER BY nom_caja LIMIT 1),0) as id_caja,
                                   (SELECT id_usu FROM usu_sistemas  
                                      WHERE id_usu = $idusu AND IFNULL(id_mesero,0) != 0)");

        $this->db->query("DELETE FROM formapago_tmp 
                            WHERE id_venta in (SELECT id_venta FROM venta_tmp WHERE idusu = $idusu)");
        $this->db->query("DELETE FROM venta_credito_tmp 
                            WHERE id_venta in (SELECT id_venta FROM venta_tmp WHERE idusu = $idusu)");
        $this->db->query("DELETE FROM venta_dato_adicional_tmp 
                            WHERE id_venta in (SELECT id_venta FROM venta_tmp WHERE idusu = $idusu)");

        $this->db->query("INSERT INTO venta_credito_tmp (id_venta, fechalimite, dias, p100interes_credito, p100interes_mora, cantidadcuotas, abonoinicial) 
                            SELECT id_venta, now(), 1, 0, 0, 1, 0 FROM venta_tmp WHERE idusu = $idusu");
        $this->db->query("INSERT INTO venta_dato_adicional_tmp (id_venta, id_config, datoadicional) 
                            SELECT id_venta, id_config, '' FROM venta_tmp, venta_config_adicional 
                              WHERE idusu = $idusu AND activo = 1");

      }

      $sqlcli = $this->db->query("SELECT t.id_venta, t.fecha, t.tipo_doc, t.nro_factura, t.id_cliente, t.tipo_ident, 
                                         t.nro_ident, t.nom_cliente, t.telf_cliente, t.dir_cliente, t.correo_cliente, 
                                         t.ciu_cliente, t.idusu, t.id_caja, t.nro_orden, 
                                         IFNULL(c.codigo,'') as codigo, 
                                         t.id_vendedor, IFNULL(c.id_vendedor,0) as id_vendedorasociado,  
                                         t.observaciones, t.placa_matricula,
                                         IFNULL(c.id_categoriaventa,0) as id_categoriaventa,  
                                         IFNULL(cv.categoria,'') as categoriaventa,  
                                         IFNULL(cv.icono_path,'') as icono_path  
                                  FROM venta_tmp t
                                  LEFT JOIN clientes c on c.id_cliente = t.id_cliente
                                  LEFT JOIN cliente_categoriaventa cv on cv.id = c.id_categoriaventa
                                  WHERE idusu = $idusu");
      $cliver = $sqlcli->result();
      return $cliver[0];      
    }


    /* ACTUALIZAR REGISTRO DEL CLIENTE EN LA TABLA PEDIDO */
    public function upd_ventcliente($idusu, $idcli, $idtp, $nom, $tel, $cor, $dir, $ciu, $idc, $placa){
      $sql = $this->db->query("SELECT COUNT(*) AS val FROM venta_tmp WHERE idusu = $idusu");
      $cliven = $sql->result();
      $val = $cliven[0]->val;

      if($val > 0){

        $sqlcli = $this->db->query("SELECT id_cliente, COUNT(*) AS nrocli FROM clientes WHERE ident_cliente = '$idcli' ");
        $resucli = $sqlcli->result();
        $valcli = $resucli[0]->nrocli;

        if($valcli > 0){  

          $idc = $resucli[0]->id_cliente;      

          if ($idc == 1){
            $tel = '';
            $cor = '';
            $dir = '';
            $ciu = '';
            $placa = '';
          }
      
          $this->db->query(" UPDATE venta_tmp 
                                SET id_cliente = $idc,
                                    tipo_ident = '$idtp', 
                                    nro_ident = '$idcli', 
                                    nom_cliente = '$nom', 
                                    telf_cliente = '$tel', 
                                    dir_cliente = '$dir', 
                                    placa_matricula = '$placa', 
                                    correo_cliente = '$cor', 
                                    ciu_cliente = '$ciu'/*,
                                    tipo_doc = IFNULL((SELECT tipo_doc FROM venta WHERE id_cliente = $idc ORDER BY id_venta DESC LIMIT 1), tipo_doc)*/
                              WHERE idusu = $idusu");


          if ($idc != 1){
            $this->db->query("UPDATE clientes 
                                SET /*tipo_ident_cliente = '$idtp', 
                                    ident_cliente = '$idcli', */
                                    nom_cliente = '$nom', 
                                    telefonos_cliente = '$tel', 
                                    direccion_cliente = '$dir', 
                                    placa_matricula = '$placa', 
                                    correo_cliente = '$cor', 
                                    ciudad_cliente = '$ciu'
                                WHERE id_cliente = $idc ");
          }  

        $sqlcli = $this->db->query("SELECT IFNULL(tipo_doc,2) as tipo_doc FROM venta_tmp WHERE idusu = $idusu");
        $resucli = $sqlcli->result();
        $tipo_doc = $resucli[0]->tipo_doc;
        return $tipo_doc;
        }else{
          if($cor != NULL || $cor = ""){}else{$cor = " ";} 
          if($tel != NULL || $tel = ""){}else{$tel = " ";}
          if($dir != NULL || $dir = ""){}else{$dir = " ";}
          if($ciu != NULL || $ciu = ""){}else{$ciu = " ";}
          
          $this->db->query("INSERT INTO clientes (tipo_ident_cliente, nom_cliente, ident_cliente, placa_matricula) 
                                          VALUES ('$idtp', '$nom', '$idcli', '$placa')");
          $sqlcli = $this->db->query("SELECT max(id_cliente) as maxid FROM clientes");
          $resucli = $sqlcli->result();
          $newidcli = $resucli[0]->maxid;
          $this->db->query("INSERT into cliente_tipoprecio (id_cliente, id_precio, estatus)
                              select $newidcli, id_precios, 1 from precios");
          $this->db->query("UPDATE clientes SET idcategoriacontable = (SELECT id FROM con_categoria 
                                                                         WHERE idtipocategoria = 1 LIMIT 1)
                              WHERE id_cliente = $newidcli");

          $this->upd_ventcliente($idusu, $idcli, $idtp, $nom, $tel, $cor, $dir, $ciu, $idc, $placa);
        } 

      }
    }



    /* listado de detalle de venta para actualizar kardex */
    public function lst_detventaparakardex($id_venta){
      $sel_obj = $this->db->query("Select d.id_producto, p.pro_idunidadmedida, d.id_almacen,
                                          v.nro_factura, p.pro_preciocompra as precio, d.cantidad, 
                                          p.pro_preciocompra * d.cantidad as descsubtotal
                                       from venta_detalle d 
                                       inner join venta v on v.id_venta = d.id_venta
                                       inner join producto p on p.pro_id = d.id_producto 
                                       WHERE p.preparado = 0 and v.id_venta = $id_venta
                                   Union
                                   Select i.id_proing as id_producto, p.pro_idunidadmedida, d.id_almacen,
                                          v.nro_factura, p.pro_preciocompra as precio, 
                                          round(case when i.unimed = p.pro_idunidadmedida then 1
                                                      when ifnull(fd.idunidad1,0) != 0 then fd.cantidadequivalente
                                                      when ifnull(fi.idunidad1,0) != 0 then 1/fi.cantidadequivalente
                                                      else 0
                                                end * i.cantidad * d.cantidad,2) as cantidad,
                                          round(case when i.unimed = p.pro_idunidadmedida then 1
                                                      when ifnull(fd.idunidad1,0) != 0 then fd.cantidadequivalente
                                                      when ifnull(fi.idunidad1,0) != 0 then 1/fi.cantidadequivalente
                                                      else 0
                                                end * i.cantidad * d.cantidad,2) * p.pro_preciocompra as descsubtotal 
                                       from venta_detalle d 
                                       inner join producto p1 on p1.pro_id = d.id_producto 
                                       inner join venta v on v.id_venta = d.id_venta
                                       inner join producto_ingrediente i on i.id_pro = d.id_producto
                                       inner join producto p on p.pro_id = i.id_proing 
                                       left join unidadfactorconversion fd on fd.idunidad1 = i.unimed and fd.idunidadequivale = p.pro_idunidadmedida 
                                       left join unidadfactorconversion fi on fi.idunidad1 = p.pro_idunidadmedida and fi.idunidadequivale = i.unimed 
                                       WHERE p1.preparado = 1 and v.id_venta = $id_venta");
      $resultado = $sel_obj->result();
      return $resultado;
    }  

    /* listado de detalle de compras para actualizar kardex de serie */
    public function lst_detventaparakardexserie($id_venta){
      $sel_obj = $this->db->query("Select ps.id_serie, d.id_almacen, 2 as tipomovimiento, 
                                          v.nro_factura, v.fecha, d.descripcion
                                       from producto_serie ps
                                       inner join venta_detalle d on d.id_detalle = ps.id_detalleventa
                                       inner join venta v on v.id_venta = d.id_venta
                                       WHERE v.id_venta = $id_venta");
      $resultado = $sel_obj->result();
      return $resultado;
    }  


    public function sel_descripciondetalle($id){
      $sel_obj = $this->db->query("SELECT descripcion FROM venta_detalle_tmp WHERE id_detalle=$id"); 
      $result = $sel_obj->result();
      if ($result)
        return $result[0]->descripcion;
      else
        return '';
    }  

    public function udp_descripciondetalle($id, $descripcion){
      $sel_obj = $this->db->query("UPDATE venta_detalle_tmp SET descripcion = '$descripcion'
                                     WHERE id_detalle=$id"); 
    }  

    /* INSERTA PRODUCTO EN TABLA DETALLE VENTA_TMP */
    public function ins_detalleventatmp($idusu, $idpro, $idalm){
      $sel_obj = $this->db->query("select count(*) as cant from venta_tmp where idusu=$idusu and tipo_doc=2"); 
      $resultado = $sel_obj->result();
      /*$esfactura = $resultado[0]->cant;*/
      $esfactura = 1;
      if ($esfactura == 1){
        $this->db->query("insert into venta_detalle_tmp 
                            (id_venta,id_producto,cantidad,precio, iva, descmonto, id_almacen, tipprecio, 
                             descripcion, porcdesc)  
                            SELECT id_venta, pro_id, 1, 
                              case IFNULL(c.tipo_precio,0) when 0 then pro_precioventa 
                                 else IFNULL((SELECT monto FROM prepro pp 
                                                WHERE pp.id_precios=c.tipo_precio AND pp.pro_id=$idpro),0)
                              end as precio, 
                              pro_grabaiva, 0,
                              case producto.preparado when 0 then $idalm 
                                else (select id_alm from almapro a inner join producto_ingrediente i on i.id_proing = a.id_pro where i.id_pro = $idpro limit 1) 
                              end, 
                              IFNULL(c.tipo_precio,0),
                              pro_nombre, 0
                              FROM venta_tmp, producto, clientes c 
                              where idusu=$idusu and pro_id=$idpro and c.id_cliente=venta_tmp.id_cliente"); 
      } else {
        $this->db->query("insert into venta_detalle_tmp 
                            (id_venta,id_producto,cantidad,precio,iva,montoiva,descmonto, id_almacen, tipprecio, 
                             descripcion, porcdesc)  
                            SELECT id_venta, pro_id, 1, 
                              case pro_grabaiva when 1 then round((1 + prm.valor),4) else 1 end *
                                case IFNULL(c.tipo_precio,0) when 0 then pro_precioventa 
                                 else IFNULL((SELECT monto FROM prepro pp 
                                                WHERE pp.id_precios=c.tipo_precio AND pp.pro_id=$idpro),0)
                                 
                                end, 
                              0, 0, 0, 
                              case producto.preparado when 0 then $idalm 
                                else (select id_alm from almapro a inner join producto_ingrediente i on i.id_proing = a.id_pro where i.id_pro = $idpro limit 1) 
                              end, 
                              IFNULL(c.tipo_precio,0),
                              pro_nombre, 0
                              FROM venta_tmp, producto, parametros prm, clientes c
                              where prm.id=1 and idusu=$idusu and pro_id=$idpro and c.id_cliente=venta_tmp.id_cliente"); 
      }
      $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM venta_detalle_tmp");
      $varid = $sqlid->result();
      if ($varid){
        $iddet = $varid[0]->id;
        $this->db->query("UPDATE venta_detalle_tmp SET
                            subtotal = precio,
                            descsubtotal = precio,
                            precio_base = precio,
                            montoiva = case when (iva = 0) or ($esfactura = 0) then 0 else
                                          round(precio * (SELECT valor FROM parametros prm WHERE prm.id=1),2) 
                                       end,
                            precioconiva = round(precio * (1 + (SELECT valor FROM parametros prm WHERE prm.id=1)),4)           
                            WHERE id_detalle=$iddet");
      }  
    }  

    public function ins_detalleventatmpcodbar($idusu, $codbar, $idalm){
      $sel_obj = $this->db->query("select count(*) as cant from venta_tmp where idusu=$idusu and tipo_doc=2"); 
      $resultado = $sel_obj->result();
      /*$esfactura = $resultado[0]->cant;*/

      $sel_obj = $this->db->query("select id_detalle from venta_detalle_tmp 
                                     where id_producto = (select pro_id from producto where pro_codigobarra='$codbar' limit 1) 
                                       and id_venta = (select id_venta from venta_tmp where idusu=$idusu)"); 
      $resultado = $sel_obj->result();
      if ($resultado != NULL){
        $iddetalle = $resultado[0]->id_detalle;

        $this->db->query("update venta_detalle_tmp set cantidad = cantidad + 1
                            where id_detalle = $iddetalle"); 
        $this->db->query("update venta_detalle_tmp set subtotal = precio * cantidad
                            where id_detalle = $iddetalle"); 

      }
      else {  


        $esfactura = 1;
        if ($esfactura == 1){
          $this->db->query("insert into venta_detalle_tmp 
                              (id_venta,id_producto,cantidad,precio, iva, descmonto, id_almacen, tipprecio, 
                               descripcion, porcdesc)  
                              SELECT id_venta, pro_id, 1, 
                                case IFNULL(c.tipo_precio,0) when 0 then pro_precioventa 
                                   else IFNULL((SELECT monto FROM prepro pp 
                                                  WHERE pp.id_precios=c.tipo_precio AND pp.pro_id=producto.pro_id),0)
                                end as precio, 
                                pro_grabaiva, 0,
                                case producto.preparado when 0 then $idalm 
                                  else (select id_alm from almapro a inner join producto_ingrediente i on i.id_proing = a.id_pro where i.id_pro = pro_id limit 1) 
                                end, 
                                IFNULL(c.tipo_precio,0),
                                pro_nombre, 0
                                FROM venta_tmp, producto, clientes c 
                                where idusu=$idusu and pro_codigobarra='$codbar' and c.id_cliente=venta_tmp.id_cliente"); 
        } else {
          $this->db->query("insert into venta_detalle_tmp 
                              (id_venta,id_producto,cantidad,precio,iva,montoiva,descmonto, id_almacen, tipprecio, 
                               descripcion, porcdesc)  
                              SELECT id_venta, pro_id, 1, 
                                case pro_grabaiva when 1 then round((1 + prm.valor),4) else 1 end *
                                  case IFNULL(c.tipo_precio,0) when 0 then pro_precioventa 
                                   else IFNULL((SELECT monto FROM prepro pp 
                                                  WHERE pp.id_precios=c.tipo_precio AND pp.producto.pro_id),0)
                                   
                                  end, 
                                0, 0, 0, 
                                case producto.preparado when 0 then $idalm 
                                  else (select id_alm from almapro a inner join producto_ingrediente i on i.id_proing = a.id_pro where i.id_pro = pro_id limit 1) 
                                end, 
                                IFNULL(c.tipo_precio,0),
                                pro_nombre, 0
                                FROM venta_tmp, producto, parametros prm, clientes c
                                where prm.id=1 and idusu=$idusu and pro_codigobarra='$codbar' and c.id_cliente=venta_tmp.id_cliente"); 
        }
        $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM venta_detalle_tmp");
        $varid = $sqlid->result();
        if ($varid){
          $iddet = $varid[0]->id;
          $this->db->query("UPDATE venta_detalle_tmp SET
                              subtotal = precio,
                              descsubtotal = precio,
                              precio_base = precio,
                              montoiva = case when (iva = 0) or ($esfactura = 0) then 0 else
                                            round(precio * (SELECT valor FROM parametros prm WHERE prm.id=1),2) 
                                         end,
                              precioconiva = round(precio * (1 + (SELECT valor FROM parametros prm WHERE prm.id=1)),4)           
                              WHERE id_detalle=$iddet");
        }  
      }  
    }     


    public function ins_detalleventatmpcodbar00($idusu, $codbar, $idalm){
      $sel_obj = $this->db->query("select count(*) as cant from venta_tmp where idusu=$idusu and tipo_doc=2"); 
      $resultado = $sel_obj->result();
      $esfactura = $resultado[0]->cant;
      if ($esfactura == 1){
        $sel_obj = $this->db->query("insert into venta_detalle_tmp 
                                    (id_venta,id_producto,cantidad,precio,subtotal,iva,montoiva,descmonto,descsubtotal, id_almacen, tipprecio)  
                                    SELECT id_venta, pro_id, 1, pro_precioventa, round(pro_precioventa,2), pro_grabaiva,
                                      round(case pro_grabaiva when 1 then round(pro_precioventa * (1 + prm.valor),2) - round(pro_precioventa,2) else 0 end,2), 
                                      0, round(pro_precioventa,2), 
                                      case producto.preparado when 0 then $idalm else (select id_alm from almapro a inner join producto_ingrediente i on i.id_proing = a.id_pro where i.id_pro = pro_id limit 1) end, 0
                                      FROM venta_tmp, producto, parametros prm
                                      where prm.id=1 and idusu=$idusu and pro_codigobarra='$codbar'"); 
      } else {
        $sel_obj = $this->db->query("insert into venta_detalle_tmp 
                                    (id_venta,id_producto,cantidad,precio,subtotal,iva,montoiva,descmonto,descsubtotal, id_almacen, tipprecio)  
                                    SELECT id_venta, pro_id, 1, 
                                      case pro_grabaiva when 1 then round(pro_precioventa * (1 + prm.valor),4) else pro_precioventa end, 
                                      case pro_grabaiva when 1 then round(pro_precioventa * (1 + prm.valor),2) else pro_precioventa end, 
                                      0, 0, 0, 
                                      case pro_grabaiva when 1 then round(pro_precioventa * (1 + prm.valor),2) else pro_precioventa end, 
                                      case producto.preparado when 0 then $idalm else (select id_alm from almapro a inner join producto_ingrediente i on i.id_proing = a.id_pro where i.id_pro = pro_id limit 1) end, 0
                                      FROM venta_tmp, producto, parametros prm
                                      where prm.id=1 and idusu=$idusu and pro_codigobarra='$codbar'"); 
      }
    }     

    public function ins_detalleventagas($idusu, $idpro, $idalm, $cant, $monto){
/*      $this->db->query("INSERT INTO venta_detalle_tmp 
                            (id_venta,id_producto,cantidad,precio,subtotal,iva,montoiva,descmonto,
                             descsubtotal, id_almacen, precioconiva, precio_base, descripcion)  
                          SELECT id_venta, pro_id, $cant, pro_precioventa, round($monto / (1 + prm.valor),2), 
                                 pro_grabaiva,
                                 case pro_grabaiva when 1 then round($monto,2) - round($monto / (1 + prm.valor),2) else 0 end, 
                                 0, round($monto / (1 + prm.valor),2), $idalm,
                                 round(pro_precioventa * (1 + prm.valor),4), pro_precioventa, 
                                 pro_nombre
                            FROM venta_tmp, producto, parametros prm, almacen a
                            where prm.id=1 and idusu=$idusu and pro_id=a.almacen_idproducto and a.almacen_id=$idalm"); */
      $this->db->query("INSERT INTO venta_detalle_tmp 
                            (id_venta,id_producto,cantidad,precio,subtotal,iva,montoiva,descmonto,
                             descsubtotal, id_almacen, precioconiva, precio_base, descripcion, porcdesc)  
                          SELECT id_venta, pro_id, $cant, pro_precioventa, round(pro_precioventa * $cant,2), 
                                 pro_grabaiva,
                                 round(case pro_grabaiva when 1 then round($monto,2) - round(pro_precioventa * $cant,2) else 0 end,2), 
                                 0, pro_precioventa * $cant, $idalm,
                                 round(pro_precioventa * (1 + prm.valor),6), pro_precioventa, 
                                 pro_nombre, 0
                            FROM venta_tmp, producto, parametros prm, almacen a
                            where prm.id=1 and idusu=$idusu and pro_id=a.almacen_idproducto and a.almacen_id=$idalm"); 
    }




    /* LISTADO TMP DE DETALLE DE VENTA */
    public function lst_tmpventadetalle($id_usuario){
      $sql_sel = $this->db->query("SELECT valor FROM parametros WHERE id = 20");
      $result = $sql_sel->result();
      $precioconiva = 0;
      if ($result) { $precioconiva = $result[0]->valor; }     
      if ($precioconiva == 0)
        $sql_sel = $this->db->query(" SELECT d.id_detalle, d.id_producto, d.descripcion as pro_nombre, d.precio, 
                                           p.pro_grabaiva, d.cantidad, d.subtotal, d.descsubtotal, d.tipprecio, 
                                           d.id_serie, s.numeroserie, d.porcdesc, d.descmonto,
                                           (SELECT COUNT(*) FROM producto_serie WHERE id_producto = d.id_producto) AS estserie
                                    FROM venta_detalle_tmp d
                                    INNER JOIN venta_tmp v on v.id_venta = d.id_venta
                                    INNER JOIN producto p ON p.pro_id = d.id_producto
                                    LEFT JOIN producto_serie s on s.id_serie = d.id_serie
                                    WHERE v.idusu = $id_usuario");
      else
        $sql_sel = $this->db->query("SELECT d.id_detalle, d.id_producto, d.descripcion as pro_nombre, 
                                           d.precioconiva as precio, 
                                           p.pro_grabaiva, d.cantidad, 
                                           d.subtotal + d.montoiva as subtotal, 
                                           d.subtotal + d.montoiva - d.descmonto as descsubtotal, 
                                           d.tipprecio, d.id_serie, s.numeroserie, d.porcdesc, d.descmonto,
                                           (SELECT COUNT(*) FROM producto_serie WHERE id_producto = d.id_producto) AS estserie
                                    FROM venta_detalle_tmp d
                                    INNER JOIN venta_tmp v on v.id_venta = d.id_venta
                                    INNER JOIN producto p ON p.pro_id = d.id_producto
                                    LEFT JOIN producto_serie s on s.id_serie = d.id_serie
                                    WHERE v.idusu = $id_usuario");
      $result = $sql_sel->result();
      return $result;
    }    

    public function lst_tmpventadetalle00($id_usuario){
      $sql_sel = $this->db->query(" SELECT d.id_detalle, d.id_producto, p.pro_nombre, d.precio, 
                                           p.pro_grabaiva, d.cantidad, d.subtotal, d.descsubtotal, d.tipprecio, 
                                           d.id_serie, s.numeroserie, 
                                           (SELECT COUNT(*) FROM producto_serie WHERE id_producto = d.id_producto) AS estserie
                                    FROM venta_detalle_tmp d
                                    INNER JOIN venta_tmp v on v.id_venta = d.id_venta
                                    INNER JOIN producto p ON p.pro_id = d.id_producto
                                    LEFT JOIN producto_serie s on s.id_serie = d.id_serie
                                    WHERE v.idusu = $id_usuario");
      $result = $sql_sel->result();
      return $result;
    }    

    /* ELIMINA PRODUCTO EN TABLA DETALLE VENTA_TMP */
    public function del_detalleventatmp($iddetalle){
      $sel_obj = $this->db->query("delete from venta_detalle_tmp where id_detalle=$iddetalle"); 
    }  

    /* Actualiza PRODUCTO EN TABLA DETALLE VENTA_TMP */
    public function upd_detalleventa($iddetalle, $cantidad, $precio, $valiva, $subtotal, $tp, $porcpro){
      $parametro = &get_instance();
      $parametro->load->model("Parametros_model");
      $descuentoproducto = $parametro->Parametros_model->sel_descpro();   
      $tipodescuentoproducto = $parametro->Parametros_model->sel_tipodescuentoproducto();   

      if ($porcpro == '') { $porcpro = 0;}

      if (($descuentoproducto == 1) && ($tipodescuentoproducto == 0)){
        $this->db->query("UPDATE venta_detalle_tmp set
                            cantidad = $cantidad, precio = $precio, montoiva = $valiva,
                            subtotal = $subtotal, descsubtotal = $subtotal, tipprecio = $tp,
                            precio_base = $precio,
                            descmonto = $porcpro,
                            porcdesc = 0
                           where id_detalle=$iddetalle"); 
      }
      else{
        $this->db->query("UPDATE venta_detalle_tmp set
                            cantidad = $cantidad, precio = $precio, montoiva = $valiva,
                            subtotal = $subtotal, descsubtotal = $subtotal, tipprecio = $tp,
                            precio_base = $precio,
                            porcdesc = $porcpro
                           where id_detalle=$iddetalle"); 
      }  
    }  

    /* Actualiza Descuento EN TABLA VENTA_TMP */
    public function upd_descuentoventatmp($id_usuario, $descuento){
      $sel_obj = $this->db->query("update venta_tmp set
                                      desc_monto = $descuento
                                     where idusu=$id_usuario"); 
    }  

    /* LISTADO TMP DE DETALLE DE VENTA
    public function lst_subtotalesventatmp($id_usuario){
      $sql_sel = $this->db->query("SELECT sum(case when (d.id_venta is not null) and p.pro_grabaiva = 1 then d.subtotal else 0 end) as subtotaliva,
                                          sum(case when (d.id_venta is not null) and p.pro_grabaiva = 0 then d.subtotal else 0 end) as subtotalcero,
                                          ifnull(min(v.desc_monto),0) as descuento, v.id_venta
                                    FROM venta_tmp v
                                    LEFT JOIN venta_detalle_tmp d on d.id_venta = v.id_venta
                                    LEFT JOIN producto p ON p.pro_id = d.id_producto                                    
                                    WHERE v.idusu = $id_usuario
                                    GROUP BY v.id_venta");      
      $objresult = $sql_sel->result();
      $result = $objresult[0];
      if ($result){
        $id_venta = $result->id_venta;
        $subtotaliva = $result->subtotaliva;
        $subtotalcero = $result->subtotalcero;
        $descuento = $result->descuento;
        if ($descuento >= ($subtotaliva + $subtotalcero)){
          $descuento = 0;
        }
        if (($subtotaliva + $subtotalcero) > 0){
          $sql_sel = $this->db->query("update venta_detalle_tmp 
                                         set descmonto = round($descuento / ($subtotaliva + $subtotalcero) * subtotal, 2)
                                         WHERE id_venta = $id_venta");      
          $sql_sel = $this->db->query("update venta_detalle_tmp 
                                         set descsubtotal = subtotal - descmonto,
                                             montoiva = round(case iva when 1 then round((subtotal - descmonto) * (1 + IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)),2) - round(subtotal - descmonto,2) else 0 end,2)                                               
                                         WHERE id_venta = $id_venta");      

        }
        $sql_sel = $this->db->query("update venta_tmp 
                                       set desc_monto = $descuento,
                                       subconiva = ifnull((select sum(d.subtotal) from venta_detalle_tmp d where iva=1 and id_venta=$id_venta),0),    
                                       subsiniva = ifnull((select sum(d.subtotal) from venta_detalle_tmp d where iva=0 and id_venta=$id_venta),0),    
                                       descsubconiva = ifnull((select sum(d.descsubtotal) from venta_detalle_tmp d where iva=1 and id_venta=$id_venta),0),    
                                       descsubsiniva = ifnull((select sum(d.descsubtotal) from venta_detalle_tmp d where iva=0 and id_venta=$id_venta),0),    
                                       montoiva = ifnull((select sum(d.montoiva) from venta_detalle_tmp d where iva=1 and id_venta=$id_venta),0)    
                                      WHERE id_venta = $id_venta");      

        $sql_sel = $this->db->query("SELECT count(*) as cant, min(id_formapago) as forma FROM formapago_tmp t
                                        INNER JOIN venta_tmp v on v.id_venta = t.id_venta
                                        WHERE t.id_tipcancelacion=1 and v.idusu = $id_usuario");      
        $objresult = $sql_sel->result();
        if (($objresult[0]->cant == 1) && ($objresult[0]->forma == 1)){
            $sql_sel = $this->db->query("update formapago_tmp set monto = (select descsubconiva+descsubsiniva+montoiva+ round((descsubconiva + descsubsiniva) * ifnull((select valor from parametros where id=13),0) / 100 ,2) from venta_tmp vt where vt.id_venta = $id_venta)
                                          where id_tipcancelacion=1 and id_venta = $id_venta");      
        } else if ($objresult[0]->cant == 0) {
            $sql_sel = $this->db->query("insert into formapago_tmp (id_venta, id_formapago, descripciondocumento, id_tipcancelacion, monto)
                                           select $id_venta, 1, '', 1,
                                             ifnull((select descsubconiva+descsubsiniva+montoiva+ round((descsubconiva + descsubsiniva) * ifnull((select valor from parametros where id=13),0) / 100 ,2) from venta_tmp vt where vt.id_venta = $id_venta),0)");      
        }        

      }

      
      $sql_sel = $this->db->query("SELECT subconiva as subtotaliva,
                                          subsiniva as subtotalcero,
                                          descsubconiva as descsubtotaliva,
                                          descsubsiniva as descsubtotalcero,
                                          desc_monto as descuento, 
                                          montoiva,
                                          id_venta,
                                          (descsubconiva + descsubsiniva + montoiva) as monto,
                                          ifnull((select sum(monto) from formapago_tmp where id_tipcancelacion=1 and id_venta=venta_tmp.id_venta),0) as montopagado,
                                          ifnull((select sum(monto) from formapago_tmp where id_tipcancelacion=1 and id_formapago=1 and id_venta=venta_tmp.id_venta),0) as montopagadoefectivo
                                    FROM venta_tmp
                                    WHERE idusu = $id_usuario");      
      $result = $sql_sel->result();
      return $result[0];
    }    
 */
    public function lst_subtotalesventatmp($id_usuario){
      $sel_obj = $this->db->query("select count(*) as cant from venta_tmp 
                                     where idusu=$id_usuario and tipo_doc=2"); 
      $resu = $sel_obj->result();
      $esfactura = $resu[0]->cant;
     
      $parametro = &get_instance();
      $parametro->load->model("Parametros_model");
      $descuentoproducto = $parametro->Parametros_model->sel_descpro();   
      $tipodescuentoproducto = $parametro->Parametros_model->sel_tipodescuentoproducto();   

      $sql_sel = $this->db->query("SELECT sum(case when (d.id_venta is not null) and p.pro_grabaiva = 1 then d.subtotal else 0 end) as subtotaliva,
                                          sum(case when (d.id_venta is not null) and p.pro_grabaiva = 0 then d.subtotal else 0 end) as subtotalcero,
                                          ifnull(min(v.desc_monto),0) as descuento, v.id_venta,
                                          ifnull(min(v.comision_monto),0) as comision
                                    FROM venta_tmp v
                                    LEFT JOIN venta_detalle_tmp d on d.id_venta = v.id_venta
                                    LEFT JOIN producto p ON p.pro_id = d.id_producto                                    
                                    WHERE v.idusu = $id_usuario
                                    GROUP BY v.id_venta");      
      $objresult = $sql_sel->result();
      $result = $objresult[0];
      if ($result){
        $id_venta = $result->id_venta;
        $subtotaliva = $result->subtotaliva;
        $subtotalcero = $result->subtotalcero;
        $descuento = $result->descuento;
        if ($descuento >= ($subtotaliva + $subtotalcero)){
          $descuento = 0;
        }
        if (($subtotaliva + $subtotalcero) > 0){
          $sql_sel = $this->db->query("UPDATE venta_detalle_tmp 
                                         set subtotal = round(cantidad * precio, 2)
                                         WHERE id_venta = $id_venta");      

          if ($descuentoproducto == 0){
            $sql_sel = $this->db->query("UPDATE venta_detalle_tmp 
                                           set descmonto = CASE subtotal WHEN 0 THEN 0 
                                                             ELSE round($descuento / ($subtotaliva + $subtotalcero) * subtotal, 2)
                                                           END  
                                           WHERE id_venta = $id_venta");      
          }  
          else{
            if ($tipodescuentoproducto == 1){ // Descuento por Porciento
              $sql_sel = $this->db->query("UPDATE venta_detalle_tmp 
                                             set descmonto = round(subtotal * porcdesc / 100, 2)
                                             WHERE id_venta = $id_venta");  
            }  
          }

          $sql_sel = $this->db->query("UPDATE venta_detalle_tmp 
                                         set descsubtotal = subtotal - descmonto,
                                          /*  ok para gasolinera
                                           montoiva = round(case iva when 1 then round(((round(precio * (1 + IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)),6) * cantidad) - descmonto) ,2) - round(subtotal - descmonto,2) else 0 end,2) */                                              
                                             montoiva = round(case iva when 1 then round(((round((precio * cantidad - descmonto) * (1 + IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)),6) ) ) ,2) - round(subtotal - descmonto,2) else 0 end,2)                                               
                                          /*   montoiva = round(case iva when 1 then round((subtotal - descmonto) * (1 + IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)),2) - round(subtotal - descmonto,2) else 0 end,2)    */                                           
                                         WHERE id_venta = $id_venta ");      

        }

        $sel_obj = $this->db->query("select IFNULL((SELECT valor FROM parametros WHERE id = 34), 0) as notaventaiva"); 
        $resultado = $sel_obj->result();
        $notaventaiva = $resultado[0]->notaventaiva;

        if(($esfactura == 1) || ($notaventaiva == 1)){
          $sql_sel = $this->db->query("update venta_tmp 
                                       set desc_monto = $descuento,
                                       subconiva = ifnull((select sum(d.subtotal) from venta_detalle_tmp d where iva=1 and id_venta=$id_venta),0),    
                                       subsiniva = ifnull((select sum(d.subtotal) from venta_detalle_tmp d where iva=0 and id_venta=$id_venta),0),    
                                       descsubconiva = ifnull((select sum(d.descsubtotal) from venta_detalle_tmp d where iva=1 and id_venta=$id_venta),0),    
                                       descsubsiniva = ifnull((select sum(d.descsubtotal) from venta_detalle_tmp d where iva=0 and id_venta=$id_venta),0),    
                                       montoiva = ifnull((select sum(d.montoiva) from venta_detalle_tmp d where iva=1 and id_venta=$id_venta),0)    
                                      WHERE id_venta = $id_venta");      
        }else{
          $sql_sel = $this->db->query("update venta_tmp 
                                       set desc_monto = $descuento,
                                       subconiva = ifnull((select sum(d.subtotal) from venta_detalle_tmp d where iva=1 and id_venta=$id_venta),0),    
                                       subsiniva = ifnull((select sum(d.subtotal) from venta_detalle_tmp d where iva=0 and id_venta=$id_venta),0),    
                                       descsubconiva = ifnull((select sum(d.descsubtotal) from venta_detalle_tmp d where iva=1 and id_venta=$id_venta),0),    
                                       descsubsiniva = ifnull((select sum(d.descsubtotal) from venta_detalle_tmp d where iva=0 and id_venta=$id_venta),0),    
                                       montoiva = 0    
                                      WHERE id_venta = $id_venta");              
        }
        $anticipo = $this->sel_anticipo($id_usuario);
        $sql_sel = $this->db->query("SELECT count(*) as cant, min(id_formapago) as forma FROM formapago_tmp t
                                        INNER JOIN venta_tmp v on v.id_venta = t.id_venta
                                        WHERE t.id_tipcancelacion=1 and v.idusu = $id_usuario");      
        $objresult = $sql_sel->result();
        if (($objresult[0]->cant == 1) && ($objresult[0]->forma == 1)){

            $sql_sel = $this->db->query("update formapago_tmp 
                                            set monto = (select descsubconiva +descsubsiniva+montoiva + 
                                                                round((descsubconiva + descsubsiniva) * ifnull((select valor from parametros where id=13),0) / 100 ,2) 
                                                            from venta_tmp vt where vt.id_venta = $id_venta) - $anticipo
                                          where id_tipcancelacion=1 and id_venta = $id_venta");      
        } else if ($objresult[0]->cant == 0) {
            $parametro = &get_instance();
            $parametro->load->model("Parametros_model");
            $ventapagoefectivo = $parametro->Parametros_model->sel_ventapagoefectivo();   
            if ($ventapagoefectivo == 1){
              $sql_sel = $this->db->query("insert into formapago_tmp (id_venta, id_formapago, descripciondocumento, id_tipcancelacion, monto)
                                             select $id_venta, 1, '', 1,
                                               ifnull((select descsubconiva+descsubsiniva + montoiva + 
                                                            round((descsubconiva + descsubsiniva) * ifnull((select valor from parametros where id=13),0) / 100 ,2) 
                                                        from venta_tmp vt where vt.id_venta = $id_venta),0) - $anticipo");      
            }  
        }        

      }

      
      $sql_sel = $this->db->query("SELECT subconiva as subtotaliva,
                                          subsiniva as subtotalcero,
                                          descsubconiva as descsubtotaliva,
                                          descsubsiniva as descsubtotalcero,
                                          desc_monto as descuento, 
                                          montoiva,
                                          id_venta,
                                          (descsubconiva + descsubsiniva + montoiva) as monto,
                                          ifnull((select sum(monto) from formapago_tmp where id_tipcancelacion=1 and id_venta=venta_tmp.id_venta),0) as montopagado,
                                          ifnull((select sum(monto) from formapago_tmp where id_tipcancelacion=1 and id_formapago=1 and id_venta=venta_tmp.id_venta),0) as montopagadoefectivo
                                    FROM venta_tmp
                                    WHERE idusu = $id_usuario");      
      $result = $sql_sel->result();
      return $result[0];
    }  

        
    /* GUARDAR FACTURA GENERAL */
    public function pagar_facturageneral($idusu, $fp, $tipocancelacion){
      /* Consultar cliente en la tabla venta_tmp */
      $sqlc = $this->db->query("SELECT tipo_ident, nro_ident, nom_cliente, telf_cliente, dir_cliente, correo_cliente, ciu_cliente, id_cliente
                                FROM venta_tmp 
                                WHERE idusu = $idusu");
      $resc = $sqlc->result();
      $clitmp = $resc[0];
      $idcli = $resc[0]->id_cliente;
      $tipo_nro_ident = $resc[0]->tipo_ident;      
      $nro_ident = $resc[0]->nro_ident;
      $nom_cliente = $resc[0]->nom_cliente;
      $telf_cliente = $resc[0]->telf_cliente;
      $dir_cliente = $resc[0]->dir_cliente;
      $cor_cliente = $resc[0]->correo_cliente;
      $ciu_cliente = $resc[0]->ciu_cliente;

      /* se verifica que exista en la tabla de clientes */
      $sqlcli = $this->db->query("SELECT COUNT(*) AS nrocli FROM clientes WHERE ident_cliente = '$nro_ident' ");
      $resucli = $sqlcli->result();
      $valcli = $resucli[0]->nrocli;

      if($valcli > 0){
        /* actualiza los datos del cliente */
        $this->db->query("UPDATE clientes 
                            SET tipo_ident_cliente = '$tipo_nro_ident',
                                nom_cliente = '$nom_cliente',
                                correo_cliente = '$cor_cliente',
                                telefonos_cliente = '$telf_cliente',
                                direccion_cliente = '$dir_cliente',
                                ciudad_cliente = '$ciu_cliente'
                          WHERE id_cliente !=1 and ident_cliente = '$nro_ident' ");    
        if (substr($nro_ident,0,10) != '9999999999'){
          $this->db->query("UPDATE clientes 
                              SET tipo_precio = IFNULL((SELECT tipprecio FROM venta_detalle_tmp d INNER JOIN venta_tmp v
                                                          ON v.id_venta = d.id_venta WHERE v.idusu = $idusu 
                                                          ORDER BY d.id_detalle LIMIT 1),0)
                            WHERE ident_cliente = '$nro_ident' ");    
        }

      }else{
        if($cor_cliente != NULL || $cor_cliente = ""){}else{$cor_cliente = " ";} 
        if($telf_cliente != NULL || $telf_cliente = ""){}else{$telf_cliente = " ";}
        if($dir_cliente != NULL || $dir_cliente = ""){}else{$dir_cliente = " ";}
        if($ciu_cliente != NULL || $ciu_cliente = ""){}else{$ciu_cliente = " ";}
        
        $sql_addc = $this->db->query("INSERT INTO clientes (tipo_ident_cliente, nom_cliente, ident_cliente, correo_cliente, telefonos_cliente, direccion_cliente, ciudad_cliente) 
                                                    VALUES ('$tipo_nro_ident', '$nom_cliente', '$nro_ident', '$cor_cliente',' $telf_cliente', '$dir_cliente', '$ciu_cliente')");
      }      
      
      $query = $this->db->query("select * FROM punto_emision 
                                   WHERE id_puntoemision = IFNULL((SELECT id_puntoemision FROM caja_efectivo c
                                                            INNER JOIN venta_tmp t on t.id_caja = c.id_caja
                                                            WHERE idusu = $idusu),1)
                                   for update");
      $resupto = $query->result();

      // Se guarda la cabecera de la venta en la tabla venta 
      $query = $this->db->query("call facturageneral_insnew($idusu, $fp, $tipocancelacion);");

      $result = $query->result();
      $result = $result[0];

      $query->next_result(); 
      $query->free_result();

      return $result->vid;
    }



    public function pagar_facturageneral00($nro_factura, $idusu, $fp, $nro_notaventa, $efectivo, $tarjeta){

      if($fp == 2){ 
        $docpago = $nro_factura; 

      } else {
        $docpago = $nro_notaventa; 
      } 
      
      /* inicia transaccion*/
      $this->db->trans_begin();

      /* Se guarda la cabecera de la venta en la tabla venta */
      $sql_add = $this->db->query("insert into venta (fecha, area, mesa, mesero, tipo_doc, nro_factura, tipo_ident, nro_ident, nom_cliente, telf_cliente,
                                                     dir_cliente, correo_cliente, ciu_cliente, valiva, subconiva, subsiniva, desc_monto,
                                                     descsubconiva, descsubsiniva, montoiva, montototal, fecharegistro, idusu, estatus)
                                    SELECT fecha, area, mesa, mesero, $fp, 
                                            (select case $fp when 2 
                                                     then concat((select valor from parametros where id=4),'-',(select valor from parametros where id=5),'-',LPAD(valor, 9, '0')) 
                                                     else LPAD(valor, 9, '0') 
                                                    end 
                                              from contador where id_contador=$fp), 
                                           tipo_ident, nro_ident, nom_cliente, telf_cliente,
                                           dir_cliente, correo_cliente, ciu_cliente, valiva, subconiva, subsiniva, desc_monto,
                                           descsubconiva, descsubsiniva, montoiva, 
                                           round(descsubconiva + descsubsiniva + montoiva,2) as montototal, now(), idusu, 1 
                                    FROM venta_tmp
                                    where idusu = $idusu;");

      $sql_add = $this->db->query("select max(id_venta) as id from venta");
      $resultado = $sql_add->result();
      $id = $resultado[0]->id; /* Se obtiene el id del registro insertado en la tabla venta para relacionarlo con venta_detalle */

      /* Guardar tipo de pago si es efectivo o tarjeta */    
      $sql_pago = $this->db->query("INSERT INTO venta_formapago (id_venta, id_formapago, monto) VALUES ($id, 1, $efectivo)");
      $sql_pago = $this->db->query("INSERT INTO venta_formapago (id_venta, id_formapago, monto) VALUES ($id, 2, $tarjeta)");

      $sql_det = $this->db->query("INSERT INTO venta_detalle (id_venta, id_producto, cantidad, precio, subtotal, iva, montoiva, descmonto, descsubtotal, id_almacen)
                                    SELECT $id, d.id_producto, d.cantidad, d.precio, d.subtotal, d.iva, d.montoiva, d.descmonto, d.descsubtotal, d.id_almacen
                                      FROM venta_detalle_tmp d
                                      INNER JOIN venta_tmp v on v.id_venta = d.id_venta
                                      WHERE v.idusu = $idusu");

      $sql_del = $this->db->query("DELETE FROM venta_detalle_tmp WHERE id_venta in (select id_venta from venta_tmp where idusu = $idusu)");
      $sql_del = $this->db->query("DELETE FROM venta_tmp where idusu = $idusu");

      /* ACTULIZA CONTADORES */
      $sql = $this->db->query("SELECT (valor + 1) AS nroval FROM contador WHERE id_contador = $fp");
      $resultado = $sql->result();
      $nro = $resultado[0]->nroval;  
      $sql_upd = $this->db->query("UPDATE contador SET valor = $nro WHERE id_contador = $fp");

      if ($this->db->trans_status() === TRUE)
      {
          $this->db->trans_commit();
      }
      else
      {
          $this->db->trans_rollback();
      }

      return $id;

    }

    public function lst_proalmacen(){
      $sql = $this->db->query(" SELECT p.pro_id, p.pro_nombre, p.pro_precioventa, 
                                       null as pro_imagen, p.imagen_path,
                                       a.almacen_id, a.almacen_nombre, 
                                       ifnull(m.existencia, 0) as existencia, p.preparado
                                FROM producto p
                                INNER JOIN almacen a ON a.almacen_idproducto = p.pro_id
                                LEFT JOIN almapro m ON m.id_pro = p.pro_id and m.id_alm = a.almacen_id
                                WHERE p.pro_apliventa = 1");
      $resu = $sql->result();
      return $resu;
    }

    public function lst_almacenes(){
      $sql = $this->db->query("SELECT almacen_id, almacen_nombre FROM almacen");
      $resu = $sql->result();
      return $resu;
    }


    public function lst_pro(){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sqld = $this->db->query(" SELECT tipo_doc FROM venta_tmp WHERE idusu = $idusu");
      $resd = $sqld->result();
    /*  $tipodoc = $resd[0]->tipo_doc;*/
      $tipodoc = 2;
      $sql = $this->db->query(" SELECT p.pro_id, p.pro_codigobarra, p.pro_codigoauxiliar, p.pro_nombre, 
                                CASE $tipodoc WHEN 2 THEN p.pro_precioventa ELSE
                                  ROUND((IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)+1) * p.pro_precioventa, 6) END
                                AS pro_precioventa, p.ubicacion,
                                ap.id_alm, ap.existencia, a.almacen_nombre, p.preparado, p.pro_esservicio,
                                p.pro_maximo AS max, p.pro_minimo AS min
                                FROM producto p
                                INNER JOIN almapro ap ON ap.id_pro = p.pro_id 
                                INNER JOIN almacen a ON a.almacen_id = ap.id_alm
                                INNER JOIN permiso_almacen pa ON pa.id_almacen = ap.id_alm
                                WHERE pro_apliventa = 1 and p.preparado = 0 and 
                                      p.pro_esservicio = 0 and IFNULL(a.almacen_tipo,0) = 1 and
                                      pa.id_usuario = $idusu
                                
                                UNION  

                                SELECT p.pro_id, p.pro_codigobarra, p.pro_codigoauxiliar, p.pro_nombre, 
                                CASE $tipodoc WHEN 2 THEN p.pro_precioventa ELSE
                                ROUND((IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)+1) * p.pro_precioventa, 6) END
                                AS pro_precioventa, p.ubicacion,
                                0 AS id_alm, 0 AS existencia, '' AS almacen_nombre, p.preparado, p.pro_esservicio,
                                p.pro_maximo AS max, p.pro_minimo AS min
                                FROM producto p
                                WHERE p.preparado = 1 and p.pro_esservicio = 0

                                UNION

                                SELECT p.pro_id, p.pro_codigobarra, p.pro_codigoauxiliar, p.pro_nombre, 
                                CASE 2 WHEN 2 THEN p.pro_precioventa ELSE
                                ROUND((IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)+1) * p.pro_precioventa, 4) END
                                AS pro_precioventa, p.ubicacion,
                                0 AS id_alm, 0 AS existencia, '' AS almacen_nombre, p.preparado, p.pro_esservicio,
                                p.pro_maximo AS max, p.pro_minimo AS min
                                FROM producto p
                                WHERE p.pro_esservicio = 1 
                                                               
                                ORDER BY pro_nombre ASC");
      $resu = $sql->result();
      return $resu;
    }


    public function gaspro_pre($idalm){
      $sql = $this->db->query(" SELECT p.pro_id, p.pro_nombre, p.pro_precioventa, p.pro_imagen, a.almacen_id, 
                                       a.almacen_nombre, p.pro_idunidadmedida, u.nombrecorto, u.descripcion, 
                                       ((IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)+1) * p.pro_precioventa) AS precioiva
                                FROM producto p
                                INNER JOIN almacen a ON a.almacen_idproducto = p.pro_id
                                INNER JOIN unidadmedida u ON u.id = p.pro_idunidadmedida
                                WHERE p.pro_apliventa = 1 AND a.almacen_id = $idalm");
      $resu = $sql->result();
      return $resu[0];
    }

    public function limpiarventatmp($idusu){
      $sql = $this->db->query("delete from venta_credito_tmp
                                 WHERE id_venta in (select id_venta from venta_tmp where idusu = $idusu)");
      $sql = $this->db->query("delete from venta_creditocuota_tmp
                                 WHERE id_venta in (select id_venta from venta_tmp where idusu = $idusu)");                                     

      $sql = $this->db->query("delete from venta_detalle_tmp
                                 WHERE id_venta in (select id_venta from venta_tmp where idusu = $idusu)");
      $sql = $this->db->query("delete from venta_tmp where idusu = $idusu");
  
    }


    public function obtenerDisponibilidad($iddetalleventa){
      $disponible = 0;
      $sql = $this->db->query("SELECT existencia FROM almapro a
                                inner join venta_detalle_tmp d on d.id_producto=a.id_pro and d.id_almacen=a.id_alm
                                where d.id_detalle=$iddetalleventa");
      $resu = $sql->result();
      if ($resu){
        $resu = $resu[0];      
        $disponible = $resu->existencia;      

        $sql = $this->db->query("SELECT id_venta, id_producto, id_almacen  
                                  FROM venta_detalle_tmp where id_detalle=$iddetalleventa");
        $resu = $sql->result();
        if ($resu){
          $resu = $resu[0];      
          $tmpcantidad = 0; 
          $sql = $this->db->query("SELECT sum(cantidad) as cantidad FROM venta_detalle_tmp 
                                    where id_venta=$resu->id_venta and 
                                          id_producto=$resu->id_producto and 
                                          id_almacen=$resu->id_almacen and 
                                          id_detalle!=$iddetalleventa");
          $resu = $sql->result();
          if ($resu){
            $resu = $resu[0];                  
            $tmpcantidad = $resu->cantidad; 
          }

          $disponible-= $tmpcantidad;          
        }  

      }
      if ($disponible < 0) $disponible = 0;
      $sql = $this->db->query("SELECT preparado, pro_esservicio FROM producto p
                                 inner join venta_detalle_tmp t on t.id_producto = p.pro_id
                                 where t.id_detalle=$iddetalleventa");
      $resu = $sql->result();
      if ($resu != NULL){
        if (($resu[0]->preparado == 1) || ($resu[0]->pro_esservicio == 1))
          $disponible = 9999999;
      }
      return $disponible;
    }

    public function obtenerProductoDisponible($idprod, $idalm){
      $disponible = 0;
      $sql = $this->db->query("SELECT existencia FROM almapro 
                                where id_pro = $idprod and id_alm = $idalm");
      $resu = $sql->result();
      if ($resu){
        $resu = $resu[0];      
        $disponible = $resu->existencia;      

        $usua = $this->session->userdata('usua');
        $idusu = $usua->id_usu;
        
        $tmpcantidad = 0; 
        $sql = $this->db->query("SELECT sum(d.cantidad) as cantidad FROM venta_detalle_tmp d
                                    inner join venta_tmp v on v.id_venta = d.id_venta
                                    where v.idusu=$idusu and 
                                          d.id_producto=$idprod and 
                                          d.id_almacen=$idalm");
        $resu = $sql->result();
        if ($resu){
          $resu = $resu[0];                  
          $tmpcantidad = $resu->cantidad; 
        }

        $disponible-= $tmpcantidad;                  

      }
      if ($disponible <= 0 || $idalm==0){
        $sql = $this->db->query("SELECT preparado,pro_esservicio FROM producto where pro_id=$idprod");
        $resu = $sql->result();
        if (($resu[0]->preparado == 1) || ($resu[0]->pro_esservicio > 0)) 
          $disponible = 9999999;
      }
	  
	  
	  if ($disponible < 0) $disponible = 0;
      
	
      
      return $disponible;
    }


    public function sel_cat(){
      $query = $this->db->query("SELECT cat_id, cat_descripcion, ifnull(menu,0) as menu FROM categorias");
      $result = $query->result();
      return $result;
    
    }    

    public function productos(){
      $sql = $this->db->query(" SELECT  pro_id as id, 
                                        pro_nombre as producto, 
                                        pro_precioventa as precio, 
                                        pro_idcategoria as idcat 
                                   FROM producto ORDER BY pro_nombre ASC ");
      $resu = $sql->result();
      return $resu;
    }

    public function cargarfacturapedido($idmesa){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      
      $lstcaja = $this->facturar_model->lst_caja($idusu);
      $nrofactura = "";
      $nronv = "";
      $caja = 0;
      if ($lstcaja) { 
        $nrofactura = $lstcaja[0]->nrofactura; 
        $caja = $lstcaja[0]->id_caja; 
      }

      $resu = 0;
      if ($caja != 0){
        $this->db->query("DELETE FROM venta_detalle_tmp WHERE id_venta in (SELECT id_venta FROM venta_tmp WHERE idmesa = $idmesa  or idusu=$idusu)");
        $this->db->query("DELETE FROM venta_tmp WHERE idmesa = $idmesa or idusu=$idusu");

        $this->db->query("INSERT INTO venta_tmp (fecha, area, mesa, mesero, tipo_doc, nro_factura, tipo_ident, 
                                                 nro_ident, nom_cliente, idusu, idmesa, id_caja, nro_orden, 
                                                 id_cliente, id_vendedor, observaciones)
                            SELECT now(), nom_area, nom_mesa, me.nom_mesero, 2  as tipo_doc, 
                                   '$nrofactura' as nro_factura, ifnull(c.tipo_ident_cliente,'C'), 
                                   ifnull(c.ident_cliente,'9999999999'),
                                   ifnull(c.nom_cliente,'CONSUMIDOR FINAL'), 
                                   $idusu, $idmesa, $caja, nro_orden, p.id_cliente, 
                                   (SELECT id_usu FROM usu_sistemas u WHERE u.id_mesero = p.id_mesero LIMIT 1) as id_vendedor, 
                                   observaciones
                              FROM pedido p
                              INNER JOIN mesa m ON m.id_mesa = p.id_mesa 
                              INNER JOIN area a ON a.id_area = m.id_area
                              LEFT JOIN mesero me ON me.id_mesero = p.id_mesero
                              LEFT JOIN clientes c ON c.id_cliente = p.id_cliente
                              WHERE m.id_mesa = $idmesa");

        $this->db->query("INSERT INTO venta_detalle_tmp (id_venta,id_producto,cantidad,precio,subtotal,iva,montoiva,
                                                         descmonto,descsubtotal, id_almacen, tipprecio, precio_base, 
                                                         descripcion, porcdesc)  
                            SELECT id_venta, pro_id, pedido_detalle.cantidad, pedido_detalle.precio, 
                                   pedido_detalle.precio * pedido_detalle.cantidad, pro_grabaiva,
                                   round(case pro_grabaiva when 1 then pedido_detalle.precio * pedido_detalle.cantidad * prm.valor else 0 end,2), 
                                   0, pedido_detalle.precio * pedido_detalle.cantidad, pedido_detalle.id_almacen, 0, 
                                   pedido_detalle.precio, pro_nombre, 0
                              FROM venta_tmp, pedido_detalle , producto, parametros prm
                              where prm.id=1 and venta_tmp.idmesa=$idmesa and 
                                    pedido_detalle.id_mesa = venta_tmp.idmesa and
                                    producto.pro_id=pedido_detalle.id_producto");

        $this->lst_subtotalesventatmp($idusu);

        $sql = $this->db->query("SELECT id_venta FROM venta_tmp WHERE idmesa=$idmesa");
        $result = $sql->result();
        if ($result != null) {
          $resu = $result[0]->id_venta;
        } else {
          $resu = 0;
        }
      }  
      return $resu;
    }


    public function preciopro($idcliente=null){
      if (!$idcliente) $idcliente = 0;
      $idusu = $this->session->userdata("sess_id");
      $sel_obj = $this->db->query("select count(*) as cant from venta_tmp where idusu=$idusu and tipo_doc=2"); 
      $resultado = $sel_obj->result();
    /*  $esfactura = $resultado[0]->cant;*/
      $esfactura = 1;

      $sel_obj = $this->db->query("SELECT c.id_categoriaventa FROM venta_tmp t
                                     INNER JOIN clientes c on c.id_cliente = t.id_cliente
                                     INNER JOIN cliente_categoriaventa v on v.id = c.id_categoriaventa
                                     WHERE  idusu=$idusu"); 
      $resultado = $sel_obj->result();
      $categoriaventa = 0;
      if (count($resultado) > 0) {$categoriaventa = $resultado[0]->id_categoriaventa;}

      if ($categoriaventa == 0){
        if ($esfactura == 1) {
          $strsql = "SELECT t.* from (SELECT p.pro_id, p.pro_nombre, p.pro_precioventa as precio, 'Tienda' as desc_precios, 0 as idprepro
                      FROM producto p
                      INNER JOIN venta_detalle_tmp d on d.id_producto = p.pro_id
                      INNER JOIN venta_tmp v on v.id_venta = d.id_venta
                      WHERE v.idusu = $idusu
                      UNION
                      SELECT p.pro_id, p.pro_nombre, monto as precio, pc.desc_precios, pp.id_precios as idprepro
                      FROM producto p
                      INNER JOIN prepro pp ON pp.pro_id = p.pro_id
                      INNER JOIN precios pc ON pc.id_precios = pp.id_precios
                      INNER JOIN venta_detalle_tmp d on d.id_producto = p.pro_id
                      INNER JOIN venta_tmp v on v.id_venta = d.id_venta
                      LEFT JOIN cliente_tipoprecio ctp ON ctp.id_precio = pc.id_precios AND ctp.id_cliente = $idcliente
                      WHERE v.idusu = $idusu AND
                            ( ($idcliente = 1) OR (IFNULL(ctp.estatus,0) = 1) ) ) as t
                    inner join usuprecio p on p.idpre = t.idprepro
                    where p.estatus=1 and p.idusu=$idusu";
        } else {
          $strsql = "SELECT t.* from 
                    (SELECT p.pro_id, p.pro_nombre, 
                          case p.pro_grabaiva when 0 then p.pro_precioventa else
                               round(p.pro_precioventa * (IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)+1),6)
                          end as precio, 
                          'Tienda' as desc_precios, 0 as idprepro
                      FROM producto p
                      INNER JOIN venta_detalle_tmp d on d.id_producto = p.pro_id
                      INNER JOIN venta_tmp v on v.id_venta = d.id_venta
                      WHERE v.idusu = $idusu
                      UNION
                      SELECT p.pro_id, p.pro_nombre, 
                             case p.pro_grabaiva when 0 then monto else
                                   round(monto * (IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)+1),6)
                             end as precio, 
                             pc.desc_precios, pp.id_precios as idprepro
                      FROM producto p
                      INNER JOIN prepro pp ON pp.pro_id = p.pro_id
                      INNER JOIN precios pc ON pc.id_precios = pp.id_precios
                      INNER JOIN venta_detalle_tmp d on d.id_producto = p.pro_id
                      INNER JOIN venta_tmp v on v.id_venta = d.id_venta
                      LEFT JOIN cliente_tipoprecio ctp ON ctp.id_precio = pc.id_precios AND ctp.id_cliente = $idcliente
                      WHERE v.idusu = $idusu AND
                            ( ($idcliente = 1) OR (IFNULL(ctp.estatus,0) = 1) ) ) as t
                      inner join usuprecio p on p.idpre = t.idprepro
                    where p.estatus=1 and p.idusu=$idusu";
        }
      }
      else{
        if ($esfactura == 1) {
          $strsql = "SELECT p.pro_id, p.pro_nombre, monto as precio, pc.desc_precios, pp.id_precios as idprepro
                      FROM producto p
                      INNER JOIN prepro pp ON pp.pro_id = p.pro_id
                      INNER JOIN precios pc ON pc.id_precios = pp.id_precios
                      INNER JOIN venta_detalle_tmp d on d.id_producto = p.pro_id
                      INNER JOIN venta_tmp v on v.id_venta = d.id_venta
                      INNER JOIN clientes c on c.id_cliente = v.id_cliente
                      INNER JOIN cliente_categoria_tipoprecio ctp ON ctp.id_categoria = c.id_categoriaventa
                                                                 AND ctp.id_precio = pc.id_precios                            
                      INNER JOIN usuprecio up on up.idpre = pc.id_precios  
                    where v.idusu = $idusu AND up.estatus=1 and up.idusu=$idusu AND pc.esta_precios = 'A'";
        } else {

        }

      }  

      if ($idcliente == 0){
        $strsql .= " UNION SELECT pu.pro_id, pu.pro_nombre, 0 as precio, 'Ultimo precio' as desc_precios, 999999999 as idprepro FROM producto pu";
      }  else {
        $strsql .= " UNION 
                    SELECT pu.pro_id, pu.pro_nombre, 
                           IFNULL((SELECT precio FROM venta_detalle dc
                         INNER JOIN venta vc on vc.id_venta = dc.id_venta
                         INNER JOIN clientes c on c.ident_cliente = vc.nro_ident
                         WHERE c.id_cliente=$idcliente and dc.id_producto  = pu.pro_id
                             order by vc.fecha desc, vc.id_venta desc limit 1),0) as precio, 
                           'Ultimo precio' as desc_precios, 999999999 as idprepro 
                    FROM producto pu                  
                    INNER JOIN venta_detalle_tmp d on d.id_producto = pu.pro_id
                    INNER JOIN venta_tmp v on v.id_venta = d.id_venta
                    WHERE v.idusu = $idusu
                    ORDER BY idprepro";
      }      

      $sql = $this->db->query($strsql);
      $resu = $sql->result();
      return $resu;
    }

    public function selprecio($idpro, $idcliente=null){
      if (!$idcliente) $idcliente = 0;
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sqld = $this->db->query(" SELECT tipo_doc FROM venta_tmp WHERE idusu = $idusu");
      $resd = $sqld->result();
    /*  $tipodoc = $resd[0]->tipo_doc;*/
      $tipodoc = 2;
      $strsql = "SELECT p.pro_id, p.pro_nombre, 
                  CASE $tipodoc WHEN 2 THEN p.pro_precioventa ELSE
                    ROUND((IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)+1) * p.pro_precioventa, 4) END
                  AS precio, 0 as idpre, 'Tienda' as nompre, 0 as idprepro
                  FROM producto p
                  WHERE p.pro_id = $idpro
                  UNION
                  SELECT p.pro_id, p.pro_nombre, 
                  CASE $tipodoc WHEN 2 THEN monto ELSE
                    ROUND((IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)+1) * monto, 4) END
                  AS precio,
                  pp.id_precios, pc.desc_precios, pp.id_prepro as idprepro
                  FROM producto p
                  INNER JOIN prepro pp ON pp.pro_id = p.pro_id
                  INNER JOIN precios pc ON pc.id_precios = pp.id_precios
                  LEFT JOIN cliente_tipoprecio ctp ON ctp.id_precio = pc.id_precios AND ctp.id_cliente = $idcliente
                  WHERE p.pro_id = $idpro AND
                        ( ($idcliente = 1) OR (IFNULL(ctp.estatus,0) = 1) )";
      if ($idcliente == 0){
        $strsql .= " UNION SELECT  pu.pro_id, pu.pro_nombre, 0 as precio, 999999999, 'Ultimo precio' as desc_precios, 999999999 as idprepro FROM producto pu
                                WHERE pu.pro_id = $idpro";
      }  else {
        $query = $this->db->query("SELECT precio
                                     FROM venta_detalle d
                                     INNER JOIN venta v on v.id_venta = d.id_venta
                                     INNER JOIN clientes c on c.ident_cliente = v.nro_ident
                                     WHERE c.id_cliente=$idcliente 
                                     AND d.id_producto = $idpro
                                     ORDER BY v.fecha DESC, v.id_venta DESC LIMIT 1");
        $resu = $query->result();
        $ultvalor = 0;
        if ($resu != null){
          $ultvalor = $resu[0]->precio;  
        }
        $strsql .= " UNION SELECT  pu.pro_id, pu.pro_nombre, $ultvalor as precio, 999999999, 'Ultimo precio' as desc_precios, 999999999 as idprepro FROM producto pu
                                WHERE pu.pro_id = $idpro";
      }      

      $sql = $this->db->query($strsql);

      $resu = $sql->result();
      return $resu;
    }

    public function lstprecios(){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;      
      $pre = $this->db->query("Select  t.* from 
                                  (SELECT 0 as id,'Tienda' as nompre UNION
                                   SELECT id_precios, desc_precios FROM precios WHERE esta_precios = 'A') as t
                                   inner join usuprecio p on p.idpre = t.id
                                   inner join venta_tmp v on v.idusu = $idusu
                                   LEFT join cliente_tipoprecio ctp on ctp.id_cliente = v.id_cliente AND ctp.id_precio = t.id 
                                   where p.estatus=1 and p.idusu=$idusu and 
                                         ( (v.id_cliente = 1) OR (IFNULL(ctp.estatus,0) = 1) )
                                UNION 
                                SELECT 999999999 as id,'Ultimo precio' as nompre
                                Order by id");
      $resu = $pre->result();
      return $resu;
    }

    public function selforpago($idforpago){
      $sql = $this->db->query("SELECT esinstrumentobanco AS banco, estarjeta AS tarjeta FROM formapago WHERE id_formapago = $idforpago");
      $resu = $sql->result();
      return $resu[0];
    }

    public function addforpago($idventa, $fp, $monto, $fechat, $tiptarjeta, $nrotar, $banco, $tbanco, $nrodoc, $tnrodoc, $descdoc, $tdescdoc, $fechae, $fechac, $nrocta, $fpvtc){
      if($fechat == NULL || $fechat == ""){}else{ $fecha = str_replace('/', '-', $fechat); $fechat = date("Y-m-d", strtotime($fecha)); } 
      if($fechae == NULL || $fechae == ""){}else{ $fecha = str_replace('/', '-', $fechae); $fechae = date("Y-m-d", strtotime($fecha)); } 
      if($fechac == NULL || $fechac == ""){}else{ $fecha = str_replace('/', '-', $fechac); $fechac = date("Y-m-d", strtotime($fecha)); } 

      $tfp = $this->selforpago($fp);
      $bco = $tfp->banco;
      $tarjeta = $tfp->tarjeta;
      $tipo = "";
      if($tarjeta == 1 && $bco == 0){ $tipo = "Tarjeta"; }
      if($tarjeta == 0 && $bco == 1){ $tipo = "Banco"; }
      if($tarjeta == 0 && $bco == 0){ $tipo = "Efectivo"; }
      switch($tipo) {
        case 'Efectivo':
          $sqladd = $this->db->query("INSERT INTO formapago_tmp (id_venta, id_formapago, monto, id_tipcancelacion) VALUES ($idventa, $fp, $monto, $fpvtc)");
          return 1;
        break;
        case 'Tarjeta':
          $sqladd = $this->db->query("INSERT INTO formapago_tmp (id_venta, id_formapago, monto, id_banco, id_tarjeta, numerotarjeta,
                                                                fechaemision, numerodocumento, descripciondocumento, id_tipcancelacion) 
                                                         VALUES ($idventa, $fp, $monto, $tbanco, $tiptarjeta, '$nrotar', '$fechat',
                                                                 '$tnrodoc', '$tdescdoc', $fpvtc)");
          return 2;
        break;
        case 'Banco':
          $sqladd = $this->db->query("INSERT INTO formapago_tmp (id_venta, id_formapago, monto, id_banco, numerocuenta, fechaemision,
                                                                 fechacobro, numerodocumento, descripciondocumento, id_tipcancelacion) 
                                                         VALUES ($idventa, $fp, $monto, $banco, '$nrocta', '$fechae', '$fechac', '$nrodoc', '$descdoc', $fpvtc)");
          return 3;
        break;                  
        default:
      }      
    }

    public function updforpago($idreg, $idventa, $fp, $monto, $fechat, $tiptarjeta, $nrotar, $banco, $tbanco, $nrodoc, $tnrodoc, $descdoc, $tdescdoc, $fechae, $fechac, $nrocta){
      if($fechat == NULL || $fechat == ""){}else{ $fecha = str_replace('/', '-', $fechat); $fechat = date("Y-m-d", strtotime($fecha)); } 
      if($fechae == NULL || $fechae == ""){}else{ $fecha = str_replace('/', '-', $fechae); $fechae = date("Y-m-d", strtotime($fecha)); } 
      if($fechac == NULL || $fechac == ""){}else{ $fecha = str_replace('/', '-', $fechac); $fechac = date("Y-m-d", strtotime($fecha)); } 

      $tfp = $this->selforpago($fp);
      $banco = $tfp->banco;
      $tarjeta = $tfp->tarjeta;
      $tipo = "";
      if($tarjeta == 1 && $banco == 0){ $tipo = "Tarjeta"; }
      if($tarjeta == 0 && $banco == 1){ $tipo = "Banco"; }
      if($tarjeta == 0 && $banco == 0){ $tipo = "Efectivo"; }
      switch($tipo) {
        case 'Efectivo':
          $sqlupd = $this->db->query("UPDATE formapago_tmp SET id_formapago = $fp, 
                                                                      monto = $monto,
                                                                   id_banco = NULL, 
                                                                 id_tarjeta = NULL, 
                                                              numerotarjeta = NULL, 
                                                               numerocuenta = NULL,
                                                               fechaemision = NULL,
                                                                 fechacobro = NULL,
                                                            numerodocumento = NULL, 
                                                       descripciondocumento = NULL
                                          WHERE idreg = $idreg AND id_venta =  $idventa");
          return 1;
        break;
        case 'Tarjeta':
          $sqladd = $this->db->query("UPDATE formapago_tmp SET id_formapago = $fp, 
                                                                      monto = $monto,
                                                                   id_banco = $tbanco, 
                                                                 id_tarjeta = $tiptarjeta, 
                                                              numerotarjeta = '$nrotar', 
                                                               numerocuenta = NULL,
                                                               fechaemision = '$fechat', 
                                                                 fechacobro = NULL,
                                                            numerodocumento = '$tnrodoc', 
                                                       descripciondocumento = '$tdescdoc'
                                          WHERE idreg = $idreg AND id_venta =  $idventa");
          return 2;
        break;
        case 'Banco':
          $sqladd = $this->db->query("UPDATE formapago_tmp SET id_formapago = $fp, 
                                                                      monto = $monto,
                                                                   id_banco = $banco, 
                                                                 id_tarjeta = NULL, 
                                                              numerotarjeta = NULL,
                                                               numerocuenta = '$nrocta', 
                                                               fechaemision = '$fechae', 
                                                                 fechacobro = '$fechac',
                                                            numerodocumento = '$nrodoc', 
                                                       descripciondocumento = '$descdoc'
                                          WHERE idreg = $idreg AND id_venta =  $idventa");
          return 3;
        break;                  
        default:
      }      
    }

    public function selforpagovent($idventa){
      $sql = $this->db->query(" SELECT ft.idreg, ft.id_formapago, f.nombre_formapago AS nomfp, ft.monto, ft.id_tipcancelacion
                                FROM formapago_tmp ft
                                INNER JOIN formapago f ON f.id_formapago = ft.id_formapago
                                WHERE id_venta = $idventa");
      $resu = $sql->result();
      return $resu;      
    }

    public function obtidventatmp($idusu){
      $sql = $this->db->query("SELECT id_venta FROM venta_tmp WHERE idusu = $idusu");
      $resu = $sql->result();
      $idvent = $resu[0]->id_venta;
      return $idvent;
    }

    public function delforpagovent($idreg, $idfp, $idventa){
      $sql =$this->db->query("DELETE FROM formapago_tmp WHERE idreg = $idreg AND id_formapago = $idfp AND id_venta =  $idventa");
    }

    public function ediforpagovent($idreg, $idfp, $idventa){
      $sql = $this->db->query(" SELECT * FROM formapago_tmp WHERE idreg = $idreg AND id_formapago = $idfp AND id_venta =  $idventa");
      $resu = $sql->result();
      return $resu[0];      
    }

    public function add_creditotmp($idventa, $fplazo, $dias, $interes, $mora, $cuotas, $abono, $mbc, $mic, $mc, $ffactura){
      $sqlct = $this->db->query("SELECT COUNT(*) AS val FROM venta_credito_tmp WHERE id_venta = $idventa");
      $res = $sqlct->result();
      $val = $res[0]->val;
      if ($cuotas < 1) $cuotas = 1;
      if($val > 0){
        $sqlupdct = $this->db->query("UPDATE venta_credito_tmp SET  fechalimite = '$fplazo', 
                                                                    dias = $dias, 
                                                                    p100interes_credito = $interes, 
                                                                    p100interes_mora = $mora, 
                                                                    cantidadcuotas = $cuotas,
                                                                    abonoinicial = $abono,
                                                                    montobasecredito = $mbc,
                                                                    montointerescredito = $mic,
                                                                    montocredito = $mc
                                                              WHERE id_venta = $idventa");
        
        $delcuo = $this->db->query("DELETE FROM venta_creditocuota_tmp WHERE id_venta =  $idventa");

        $montoresto = $mc;
        $montocuota = round($mc / $cuotas,2);
        $diascuotas = floor($dias / $cuotas);

        for ($i=1; $i <= $cuotas; $i++) { 
          $ffactura = strtotime ( '+'.$diascuotas.' day' , strtotime ( $ffactura ) ) ;
          $ffactura = date ( 'Y-m-d' , $ffactura ); 
          if ($i == $cuotas){
            $ffactura = $fplazo;
            $montocuota = $montoresto;
          }  
          $sqladdcuo = $this->db->query("INSERT INTO venta_creditocuota_tmp (id_venta, fechalimite, monto) VALUES ($idventa,'$ffactura',$montocuota)");
          $montoresto-= $montocuota;
        }

      }else{
        $sqladdct = $this->db->query("INSERT INTO venta_credito_tmp (id_venta, fechalimite, dias, p100interes_credito, p100interes_mora, cantidadcuotas, abonoinicial) 
                                                             VALUES ($idventa, '$fplazo', $dias, $interes, $mora, $cuotas, $abono)");

        $this-> add_creditotmp($idventa, $fplazo, $dias, $interes, $mora, $cuotas, $abono, $mbc, $mic, $mc, $ffactura);
      }
    }

    public function sumforpago($idventa, $fptc){
      $selsql = $this->db->query("SELECT ifnull(SUM(monto),0) as monto, 
                                         ifnull(SUM(case id_formapago when 1 then monto else 0 end),0) as efectivo
                                    FROM formapago_tmp WHERE id_venta = $idventa AND id_tipcancelacion = $fptc ");
      $resu = $selsql->result();
      $monto = $resu[0];
      return $monto;
    }

    public function crecuotmp($idventa){
      $sql = $this->db->query(" SELECT id_venta, fechalimite, dias, p100interes_credito, p100interes_mora, cantidadcuotas 
                                FROM venta_credito_tmp
                                WHERE id_venta = $idventa");
      $resu = $sql->result();
      if ($resu != null){
        return $resu[0];
      }else{
        return 0;        
      }      
                                      
    }


    public function rptventatarjeta($desde, $hasta, $vendedor, $sucursal = 0){
      $sql = $this->db->query(" SELECT v.id_venta, v.fecha, v.nro_factura, tc.nom_cancelacion, v.nom_cliente, v.nro_ident, 
                                v.montototal as totalventa, v.estatus, vf.monto as montotarjeta, fp.nombre_formapago as tipo,
                                t.nombre as tarjeta, b.nombre as banco, vft.numerotarjeta, vft.fechaemision, 
                                vft.numerodocumento, vft.descripciondocumento
                                FROM venta v 
                                INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = v.id_tipcancelacion
                                INNER JOIN venta_formapago vf ON vf.id_venta = v.id_venta
                                INNER JOIN venta_formapagotarjeta vft ON vft.id_abono = vf.id
                                INNER JOIN tarjetas t ON t.id_tarjeta = vft.id_tarjeta
                                INNER JOIN formapago fp ON fp.id_formapago = vf.id_formapago
                                INNER JOIN bancos b ON b.id_banco = vft.id_banco
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND
                                      (($vendedor = 0) OR (v.id_vendedor = $vendedor)) AND
                                      (($sucursal = 0) OR (v.id_sucursal = $sucursal))
                                ORDER BY fecha,nro_factura DESC");
      $resu = $sql->result();
      return $resu;
    }


    public function upd_ventnrodoc($idforma, $idcaja, $nrodoc, $idusu, $observaciones, $fecha){
      $sql = $this->db->query("UPDATE venta_tmp SET 
                                  tipo_doc = $idforma, 
                                  nro_factura = '$nrodoc', 
                                  id_caja = $idcaja,
                                  observaciones = '$observaciones',
                                  fecha = '$fecha' 
                                 WHERE idusu = $idusu ");
      $sel_obj = $this->db->query("select IFNULL((SELECT valor FROM parametros WHERE id = 1), 0) as factoriva"); 
      $resultado = $sel_obj->result();
      $factoriva = $resultado[0]->factoriva;
      $sel_obj = $this->db->query("select count(*) as cant from venta_tmp where idusu=$idusu and tipo_doc=2"); 
      $resultado = $sel_obj->result();
      $esfactura = $resultado[0]->cant;

      $sel_obj = $this->db->query("select IFNULL((SELECT valor FROM parametros WHERE id = 34), 0) as notaventaiva"); 
      $resultado = $sel_obj->result();
      $notaventaiva = $resultado[0]->notaventaiva;

      if (($esfactura == 1) || ($notaventaiva == 1)){
        $sql = $this->db->query("UPDATE venta_detalle_tmp t
                                   INNER JOIN venta_tmp vt on vt.id_venta = t.id_venta
                                   INNER JOIN producto p on p.pro_id = t.id_producto
                                   LEFT JOIN prepro r on r.pro_id = t.id_producto and t.tipprecio = r.id_precios
                                   SET /*precio = case t.tipprecio 
                                                  when 0 then p.pro_precioventa 
                                                  when 999999999 then 
                                                    ifnull((SELECT precio FROM venta_detalle dc
                                                       INNER JOIN venta vc on vc.id_venta = dc.id_venta
                                                       WHERE vc.id_cliente=vt.id_cliente and dc.id_producto  = t.id_producto
                                                           order by vc.fecha desc, vc.id_venta desc limit 1),0) 
                                                  else r.monto 
                                                end,*/
                                       iva = p.pro_grabaiva
                                   WHERE vt.idusu = $idusu");
      } else {
        $sql = $this->db->query("UPDATE venta_detalle_tmp t
                                   INNER JOIN venta_tmp vt on vt.id_venta = t.id_venta
                                   INNER JOIN producto p on p.pro_id = t.id_producto
                                   LEFT JOIN prepro r on r.pro_id = t.id_producto and t.tipprecio = r.id_precios
                                   SET iva = 0
                                   WHERE vt.idusu = $idusu");
      }
      $sql = $this->db->query("UPDATE venta_detalle_tmp t
                                   INNER JOIN venta_tmp vt on vt.id_venta = t.id_venta
                                   SET subtotal = round(t.cantidad * precio,2)
                                   WHERE vt.idusu = $idusu");

      $this->lst_subtotalesventatmp($idusu);
   }    
 
  public function idtipodoc($idventa){
    $sql = $this->db->query("SELECT tipo_doc FROM venta WHERE id_venta = $idventa");
    $resu = $sql->result();
    return $resu[0]->tipo_doc;
  }

    public function selventaret($idventa){
      $sql = $this->db->query(" SELECT c.id_venta, r.id_venta_ret,
                                       CONCAT(u.nom_usu,' ',u.ape_usu) as usuario, p.nom_cliente, p.ident_cliente, 
                                       c.fecharegistro, c.nro_factura, '' as nro_autorizacion, tc.nom_cancelacion, 
                                       c.descsubconiva, c.descsubsiniva, c.montoiva,  c.montototal,
                                       ifnull((select sum(cr.base_noiva + cr.base_iva) 
                                                 from venta_retencion_detrenta cr where cr.id_venta_ret=r.id_venta_ret),0) as totalbaseretenido,  
                                       ifnull((select sum(cr.valor_retencion_renta) 
                                                 from venta_retencion_detrenta cr where cr.id_venta_ret=r.id_venta_ret),0) + 
                                       ifnull((select sum(cr.valor_retencion_iva) 
                                                 from venta_retencion_detiva cr where cr.id_venta_ret=r.id_venta_ret),0)as montoretenido, 
                                       ifnull(nro_retencion,'') as nro_retencion,          
                                       ifnull(r.nro_autorizacion,'') as nro_autorizacionret,          
                                       ifnull(fecha_retencion,date(now())) as fecha_retencion,
                                       ifnull((select sum(valor_retencion_iva) from venta_retencion_detiva 
                                                 where id_venta_ret = r.id_venta_ret and porciento_retencion_iva = 10),0) as retiva10,          
                                       ifnull((select sum(valor_retencion_iva) from venta_retencion_detiva 
                                                 where id_venta_ret = r.id_venta_ret and porciento_retencion_iva = 20),0) as retiva20,          
                                       ifnull((select sum(valor_retencion_iva) from venta_retencion_detiva 
                                                 where id_venta_ret = r.id_venta_ret and porciento_retencion_iva = 30),0) as retiva30,          
                                       ifnull((select sum(valor_retencion_iva) from venta_retencion_detiva 
                                                 where id_venta_ret = r.id_venta_ret and porciento_retencion_iva = 50),0) as retiva50,          
                                       ifnull((select sum(valor_retencion_iva) from venta_retencion_detiva 
                                                 where id_venta_ret = r.id_venta_ret and porciento_retencion_iva = 70),0) as retiva70,          
                                       ifnull((select sum(valor_retencion_iva) from venta_retencion_detiva 
                                                 where id_venta_ret = r.id_venta_ret and porciento_retencion_iva = 100),0) as retiva100          
                                FROM venta c
                                INNER JOIN clientes p ON p.id_cliente = c.id_cliente
                                INNER JOIN usu_sistemas u ON u.id_usu = c.idusu
                                INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = c.id_tipcancelacion
                                LEFT JOIN venta_retencion r on r.id_venta = c.id_venta
                                WHERE c.id_venta = $idventa");
      $resu = $sql->result();
      return $resu[0];
    }

    public function lst_caja($idusu){
      $query = $this->db->query("SELECT c.id_caja, c.nom_caja, c.id_puntoemision, p.consecutivo_notaventa,
                                        concat(p.cod_establecimiento,'-',p.cod_puntoemision,'-',lpad(p.consecutivo_factura,9,'0')) as nrofactura
                                   FROM caja_efectivo c
                                   INNER JOIN punto_emision p on p.id_puntoemision = c.id_puntoemision
                                   INNER JOIN permiso_cajaefectivo pc on pc.id_caja = c.id_caja
                                   WHERE c.activo = 1 and p.activo = 1 and 
                                         pc.id_usuario = $idusu
                                   ORDER BY nom_caja");
      $result = $query->result();
      return $result;
    
    }    

    public function ins_detalleventatmpserie($idusu, $serie, $idalm){
      $sel_obj = $this->db->query("select count(*) as cant from venta_tmp where idusu=$idusu and tipo_doc=2"); 
      $resultado = $sel_obj->result();
    /*  $esfactura = $resultado[0]->cant;*/
      $esfactura = 1;
      if ($esfactura == 1){
        $this->db->query("insert into venta_detalle_tmp 
                            (id_venta,id_producto,cantidad,precio, iva, descmonto, id_almacen, tipprecio, id_serie, 
                             descripcion, porcdesc)  
                            SELECT id_venta, pro_id, 1, 
                              case IFNULL(c.tipo_precio,0) when 0 then pro_precioventa 
                                 else IFNULL((SELECT monto FROM prepro pp 
                                                WHERE pp.id_precios=c.tipo_precio AND pp.pro_id=producto.pro_id),0)
                              end as precio, 
                              pro_grabaiva, 0,
                              case producto.preparado when 0 then $idalm 
                                else (select id_alm from almapro a inner join producto_ingrediente i on i.id_proing = a.id_pro where i.id_pro = producto.pro_id limit 1) 
                              end, 
                              IFNULL(c.tipo_precio,0),
                              s.id_serie,
                              pro_nombre, 0
                              FROM venta_tmp, producto, clientes c, producto_serie s 
                              where idusu=$idusu and c.id_cliente=venta_tmp.id_cliente and
                                    s.numeroserie='$serie' and 
                                    producto.pro_id = s.id_producto and
                                    s.id_detalleventa is null and 
                                    not exists (SELECT * FROM venta_detalle_tmp WHERE id_serie = s.id_serie)"); 
      } else {
        $this->db->query("insert into venta_detalle_tmp 
                            (id_venta,id_producto,cantidad,precio,iva,montoiva,descmonto, id_almacen, tipprecio, id_serie, 
                             descripcion, porcdesc)  
                            SELECT id_venta, pro_id, 1, 
                              case pro_grabaiva when 1 then round((1 + prm.valor),4) else 1 *
                                case IFNULL(c.tipo_precio,0) when 0 then pro_precioventa 
                                 else IFNULL((SELECT monto FROM prepro pp 
                                                WHERE pp.id_precios=c.tipo_precio AND pp.pro_id=producto.pro_id),0)
                                end 
                              end, 
                              0, 0, 0, 
                              case producto.preparado when 0 then $idalm 
                                else (select id_alm from almapro a inner join producto_ingrediente i on i.id_proing = a.id_pro where i.id_pro = producto.pro_id limit 1) 
                              end, 
                              IFNULL(c.tipo_precio,0),
                              s.id_serie,
                              pro_nombre, 0
                              FROM venta_tmp, producto, parametros prm, clientes c, producto_serie s 
                              where prm.id=1 and idusu=$idusu and pro_id=$idpro and 
                                    c.id_cliente=venta_tmp.id_cliente and
                                    s.numeroserie='$serie' and 
                                    producto.pro_id = s.id_producto and
                                    s.id_detalleventa is null and 
                                    not exists (SELECT * FROM venta_detalle_tmp WHERE id_serie = s.id_serie)"); 
      }
      $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM venta_detalle_tmp");
      $varid = $sqlid->result();
      if ($varid){
        $iddet = $varid[0]->id;
        $this->db->query("UPDATE venta_detalle_tmp SET
                          subtotal = precio,
                          descsubtotal = precio,
                          precio_base = precio,
                          montoiva = case when (iva = 0) or ($esfactura = 0) then 0 else
                                        round(precio * (SELECT valor FROM parametros prm WHERE prm.id=1),2) 
                                     end,
                          precioconiva = round(precio * (1 + (SELECT valor FROM parametros prm WHERE prm.id=1)),4)           
                         WHERE id_detalle=$iddet");
      }  
    } 

    public function chk_estadoserie($serie){
      $sel_obj = $this->db->query("select id_serie, id_detalleventa from producto_serie where numeroserie='$serie'"); 
      $resultado = $sel_obj->result();
      $estado = -1;
      if ($resultado){
        $estado = 0;
        $serie = $resultado[0]->id_serie;
        if ($resultado[0]->id_detalleventa){
          $estado = 1;
        } else {
          $sel_obj = $this->db->query("select count(*) as cant from venta_detalle_tmp where id_serie=$serie"); 
          $resultado = $sel_obj->result();        
          if ($resultado[0]->cant > 0){
            $estado = 2;
          }
        } 
      }
      return $estado;     
    } 

    public function valimeiserie($idpro){
      $sql = $this->db->query(" SELECT ps.id_serie, ps.numeroserie, ps.descripcion, ps.id_producto 
                                FROM producto_serie ps
                                WHERE ps.id_detalleventa IS NULL
                                AND ps.id_serie NOT IN (SELECT IFNULL(id_serie, 0) FROM venta_detalle_tmp)
                                AND ps.id_producto = $idpro");
      $res = $sql->result();
      return $res;
    }

    public function upd_imeiserietmp($iddet, $idserie){
      $this->db->query("UPDATE venta_detalle_tmp SET id_serie = $idserie WHERE id_detalle = $iddet");
    }

    public function idventa_formapago($idfactura){
      $sql = $this->db->query(" SELECT vf.id_venta, f.nombre_formapago, vf.monto, f.id_formapago 
                                FROM venta_formapago vf
                                INNER JOIN formapago f ON f.id_formapago = vf.id_formapago
                                WHERE id_venta = $idfactura");
      $res = $sql->result();
      return $res;
    }

    public function asociar_clientevendedor($idfactura, $cuotaminima){
      $sql = $this->db->query("SELECT v.id_cliente, v.id_vendedor 
                                  FROM venta v 
                                  INNER JOIN clientes c on c.id_cliente = v.id_cliente
                                  WHERE (c.id_vendedor is null) 
                                    and (v.id_vendedor is not null) 
                                    and (SUBSTR(c.ident_cliente,1,10)!='9999999999')
                                    and id_venta = $idfactura");
      $result = $sql->result();
      if ($result){
        $vend = $result[0]->id_vendedor;
        $cliente = $result[0]->id_cliente;
        $sql = $this->db->query("SELECT SUM(montototal) as monto FROM venta
                                  WHERE (id_vendedor = $vend) 
                                    and (id_cliente = $cliente)");
        $suma = $sql->result();
        if ($suma){
          if ($suma[0]->monto >= $cuotaminima){
            $this->db->query("UPDATE clientes SET id_vendedor = $vend
                                WHERE id_cliente = $cliente");
          }
        }
      }
    }

    public function lst_vendedor(){
      $sql = $this->db->query("SELECT id_usu, CONCAT(nom_usu,' ',ape_usu) AS vendedor FROM usu_sistemas 
                                WHERE est_usu = 'A' AND
                                      (perfil = 2 OR id_usu IN (SELECT DISTINCT id_vendedor FROM venta))");
      $res = $sql->result();
      return $res;
    }

    public function lst_vendedores(){
      $sql = $this->db->query("SELECT id_usu, CONCAT(nom_usu,' ',ape_usu) AS vendedor FROM usu_sistemas WHERE perfil <> 1");
      $res = $sql->result();
      return $res;
    }


    public function get_clienteporcodigo($codigocliente){
      $query = $this->db->query("SELECT id_cliente, nom_cliente, tipo_ident_cliente, ident_cliente, nivel_est_cliente, 
                                        ref_cliente, correo_cliente, ciudad_cliente, relacionado, direccion_cliente,  
                                        telefonos_cliente, mayorista, tipo_precio, codigo, placa_matricula
                                FROM clientes
                                WHERE codigo = '$codigocliente'");
      $result = $query->result();
      if($result == NULL){
        $result = NULL;
        return $result;
      }else{
        return $result;
      }
    }

    public function updv_vendedor($idvendedor, $idventa){
      $this->db->query("UPDATE venta_tmp SET id_vendedor = $idvendedor WHERE id_venta = $idventa");
    }

    public function upd_comisionventatmp($id_usuario, $comision){
      $this->db->query("UPDATE venta_tmp SET comision_monto = $comision WHERE idusu = $id_usuario"); 
      $sqliva = $this->db->query("SELECT IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12) AS iva");
      $resiva = $sqliva->result(); 
      $viva = $resiva[0]->iva;
      $sql = $this->db->query(" SELECT id_detalle, cantidad, precio, precio_base, montoiva, tipprecio, iva, id_producto, descsubtotal
                                FROM venta_detalle_tmp 
                                WHERE id_venta = (SELECT id_venta FROM venta_tmp WHERE idusu = $id_usuario)");
      $resu = $sql->result();
      if($resu){
        foreach ($resu as $row) {

          $precioant = $row->precio_base;            
/*          if($comision > 0){
            $precioant = $row->precio;            
          }else{

            if($row->tipprecio == 0){
              $sqlpre = $this->db->query("SELECT pro_precioventa FROM producto WHERE pro_id = $row->id_producto");
              $respre = $sqlpre->result(); 
              $precioant = $respre[0]->pro_precioventa;              
            }else{
              $sqlpre = $this->db->query("SELECT monto FROM prepro WHERE pro_id = $row->id_producto AND id_precios = $row->tipprecio");
              $respre = $sqlpre->result(); 
              $precioant = $respre[0]->pro_precioventa;  
            }
          }*/

          $iddetalle = $row->id_detalle;
          $iva = $row->iva;
          $cantidad = $row->cantidad;

          $montoc = round(($precioant) * $comision / 100, 4);
          $precio = round($montoc + $precioant, 4);
          $subtotal = $cantidad * $precio;          

          if($iva == 1){
            $montoiva = round(($subtotal) * (1 + $viva),4) - round($subtotal,4);
          }else{
            $montoiva = 0;
          }

          $this->db->query("UPDATE venta_detalle_tmp SET
                                   precio = $precio, 
                                   montoiva = $montoiva,
                                   comision_monto = $montoc,
                                   subtotal = $subtotal, 
                                   descsubtotal = $subtotal
                             WHERE id_venta = (SELECT id_venta FROM venta_tmp WHERE idusu = $id_usuario)
                               AND id_detalle = $iddetalle");

        }

                
      }  

    }     

    public function sel_anticipo($idusu){
      $query = $this->db->query("SELECT IFNULL((SELECT sum(monto) 
                                   FROM venta_formapago p 
                                   INNER JOIN servicio_abono a on a.id_docpago = p.id
                                   INNER JOIN venta_tmp t on t.id_servicio = a.id_servicio
                                   WHERE t.idusu = $idusu),0) as monto");
      $result = $query->result();
      if($result != NULL){
        return $result[0]->monto;
      }else{
        return 0;
      }
    }

    public function monto_factura($idventa){
      $sql = $this->db->query(" SELECT  subconiva as subtotaliva,
                                        subsiniva as subtotalcero,
                                        descsubconiva as descsubtotaliva,
                                        descsubsiniva as descsubtotalcero,
                                        desc_monto as descuento, 
                                        montoiva,
                                        (SELECT SUM(descmonto) FROM venta_detalle WHERE id_venta = $idventa)  as descmonto,                       
                                        (descsubconiva + descsubsiniva + montoiva) as monto
                                FROM venta
                                WHERE id_venta = $idventa ");
      $res = $sql->result();
      return $res[0];
    }

    public function lst_caja_sucursal($idusu, $sucursal){
      $query = $this->db->query("SELECT c.id_caja, c.nom_caja, c.id_puntoemision, p.consecutivo_notaventa,
                                        concat(p.cod_establecimiento,'-',p.cod_puntoemision,'-',lpad(p.consecutivo_factura,9,'0')) as nrofactura
                                   FROM caja_efectivo c
                                   INNER JOIN punto_emision p on p.id_puntoemision = c.id_puntoemision
                                   INNER JOIN permiso_cajaefectivo pc on pc.id_caja = c.id_caja
                                   WHERE c.activo = 1 and p.activo = 1 and 
                                         pc.id_usuario = $idusu and p.id_sucursal = $sucursal
                                   ORDER BY nom_caja");
      $result = $query->result();
      return $result;
    
    }   

    public function guardavimeiserie($idpro, $imei, $iddet){

      $this->db->query("DELETE FROM producto_ventaserie_tmp WHERE id_producto = $idpro AND id_detalleventa = $iddet");  

      foreach ($imei as $idimei) {
        $sql = $this->db->query("SELECT numeroserie, descripcion FROM producto_serie WHERE id_serie = $idimei");
        $res = $sql->result();
        $nroserie = $res[0]->numeroserie;
        $descripcion = $res[0]->descripcion;
        $this->db->query("INSERT INTO producto_ventaserie_tmp (id_serie, id_producto, numeroserie, descripcion, id_detalleventa)
                                                        VALUES($idimei, $idpro, '$nroserie', '$descripcion', $iddet) ");
      }

    }

    public function logo(){
      $sql = $this->db->query("SELECT id_sucursal, nom_sucursal, dir_sucursal, telf_sucursal, mail_sucursal, enca_sucursal, 
                                        logo_sucursal, id_empresa, consecutivo_retencioncompra 
                                 FROM sucursal WHERE id_sucursal = 1");
      $res = $sql->result();
      return $res[0];
    }

    public function gar_enca($idventa){
      $sql = $this->db->query("SELECT v.fecha, v.tipo_doc, t.categoria, v.nro_factura, v.id_cliente, 
                                      CONCAT(c.tipo_ident_cliente,'-',c.ident_cliente) AS cedula,
                                      c.telefonos_cliente, c.correo_cliente, c.nom_cliente,
                                      c.direccion_cliente, v.id_empresa, v.id_sucursal
                                 FROM venta v
                                 INNER JOIN clientes c ON c.id_cliente = v.id_cliente
                                 INNER JOIN contador t ON t.id_contador = v.tipo_doc
                                WHERE v.id_venta = $idventa");
      $res = $sql->result();
      return $res[0];
    }

    public function gardetalle($idventa){
      $sql = $this->db->query(" SELECT ps.id_producto, p.pro_nombre, g.dias_gar, ps.numeroserie, g.fec_desde, g.fec_hasta
                                FROM garantia g
                                INNER JOIN producto_serie ps ON ps.id_serie = g.idserie
                                INNER JOIN producto p ON p.pro_id = ps.id_producto
                                WHERE g.idventa = $idventa AND g.estatus = 1");
      $res = $sql->result();
      return $res;
    }

    public function montocredito($idcli = 0){
      $sql = $this->db->query("SELECT IFNULL(c.credito,0) as topecredito,
                                      sum(IFNULL(r.montocredito,0)) as total, 
                                      IFNULL(sum(r.montocredito - ifnull((select sum(monto) from venta_formapago p
                                       left join venta_creditoabonoinicial i on i.id_abono = p.id
                                       where p.id_venta = v.id_venta and (i.id_abono is null)),0)),0) as pendiente   
                                FROM clientes c
                                LEFT JOIN venta v on v.id_cliente = c.id_cliente AND v.estatus != 3
                                LEFT JOIN venta_credito r ON r.id_venta = v.id_venta  AND r.id_estado IN (1,3)
                                WHERE c.id_cliente = $idcli");    
      $res = $sql->result();
      if (count($res) > 0)
        return $res[0];                                                    
      else
        return null;                    
    }

    public function crea_garantia($id_venta){
      $sql = $this->db->query(" SELECT COUNT(*) AS val 
                                FROM venta v
                                INNER JOIN venta_detalle vd ON vd.id_venta = v.id_venta
                                INNER JOIN producto_serie ps ON ps.id_detalleventa = vd.id_detalle
                                INNER JOIN producto p ON p.pro_id = vd.id_producto
                                WHERE v.id_venta = $id_venta");
      $res = $sql->result();
      $val = $res[0]->val;

      if($val != 0){
        $this->db->query(" INSERT INTO garantia (idventa, idserie, fec_desde, fec_hasta, dias_gar, estatus)
                                SELECT v.id_venta, ps.id_serie ,CURDATE() AS desde, 
                                       DATE_ADD(CURDATE(), INTERVAL p.pro_garantia DAY) as hasta,
                                       p.pro_garantia as dias_gar, 1 
                                FROM venta v
                                INNER JOIN venta_detalle vd ON vd.id_venta = v.id_venta
                                INNER JOIN producto_serie ps ON ps.id_detalleventa = vd.id_detalle
                                INNER JOIN producto p ON p.pro_id = vd.id_producto
                                     WHERE v.id_venta = $id_venta");
      }
    }

    public function habilitado_enviosrifactura($idfactura){
      $sql = $this->db->query("SELECT enviosriguardar_factura 
                                FROM punto_emision p
                                INNER JOIN venta v ON v.id_puntoemision = p.id_puntoemision
                                WHERE v.id_venta = $idfactura");
      $res = $sql->result();
      if ($res != null)
        return $res[0]->enviosriguardar_factura;
      else
        return 0;
    }

    public function lst_ventasresumen_tipoprecio($sucursal, $desde, $hasta, $producto){
      $sql = $this->db->query("SELECT p.id_precios, p.desc_precios, p.color,
                                      IFNULL((SELECT sum(d.descsubtotal + d.montoiva)
                                                FROM venta_detalle d 
                                                INNER JOIN venta v on v.id_venta = d.id_venta
                                                WHERE d.tipprecio = p.id_precios AND 
                                                      v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3 AND
                                                      ($producto = 0 OR d.id_producto = $producto) AND
                                                      ($sucursal = 0 OR v.id_sucursal = $sucursal)), 0) as total
                                 FROM (SELECT 0 as id_precios, 'TIENDA' as desc_precios, '#ff0000' as color
                                       UNION
                                       SELECT id_precios, desc_precios, color FROM precios) p");
      $resultado = $sql->result();
      return $resultado;
    }

    public function lst_ventasdetalles_tipoprecio($sucursal, $desde, $hasta, $producto){
      $sql = $this->db->query("SELECT p.id_precios, p.desc_precios, p.color
                                 FROM (SELECT 0 as id_precios, 'TIENDA' as desc_precios, '#ff0000' as color
                                       UNION
                                       SELECT id_precios, desc_precios, color FROM precios) p");
      $objprecios = $sql->result();

      $sql = $this->db->query("SELECT DISTINCT fecha 
                                FROM venta v
                                INNER JOIN venta_detalle d on d.id_venta = v.id_venta
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3 AND
                                      ($producto = 0 OR d.id_producto = $producto) AND
                                      ($sucursal = 0 OR v.id_sucursal = $sucursal)");
      $objfechas = $sql->result();

      $precios = [];  
      foreach ($objprecios as $precio) {
        $valores = [];

        $tmpprecio = $precio->id_precios;
        $sql = $this->db->query("SELECT fecha, sum(d.descsubtotal + d.montoiva) as valor
                                  FROM venta_detalle d 
                                  INNER JOIN venta v on v.id_venta = d.id_venta
                                  WHERE d.tipprecio = $tmpprecio AND 
                                        v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3 AND
                                        ($producto = 0 OR d.id_producto = $producto) AND
                                        ($sucursal = 0 OR v.id_sucursal = $sucursal)
                                  GROUP BY fecha");
        $objvalores = $sql->result();

        foreach ($objfechas as $fecha) {
          $tmpvalor = 0;
          foreach ($objvalores as $valor) {
            if ($valor->fecha == $fecha->fecha){
              $tmpvalor = $valor->valor;
              break;
            }
          }  
          $valores[] = $tmpvalor;
        }

        $fechas = [];
        foreach ($objfechas as $fecha) {
          $fechas[] = $fecha->fecha;
        }  

        $precios[] = array(
          'label' => $precio->desc_precios,
          'backgroundColor' => $precio->color,
          'data' => $valores,
          'fill' => false
        );
      }

      return array('precios' => $precios, 'fechas' => $fechas);
    }

    public function lst_ventasdetalles_cliente($sucursal, $cliente, $desde, $hasta){
      $sql = $this->db->query("SELECT v.id_venta, v.fecha, v.nro_factura, v.nom_cliente, v.nro_ident, tc.nom_cancelacion,
                                      c.direccion_cliente, c.correo_cliente, c.telefonos_cliente,
                                      v.id_vendedor, v.placa_matricula, v.estatus, v.fecharegistro,
                                      d.descripcion, d.cantidad, u.descripcion as unidadmedida,
                                      d.precio, d.subtotal, d.montoiva, d.descmonto,
                                      round(d.descsubtotal + d.montoiva, 2) as valortotal
                                FROM venta_detalle d 
                                INNER JOIN venta v on v.id_venta = d.id_venta
                                INNER JOIN clientes c on c.id_cliente = v.id_cliente
                                INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = v.id_tipcancelacion
                                INNER JOIN producto p on p.pro_id = d.id_producto
                                LEFT JOIN unidadmedida u on u.id = p.pro_idunidadmedida
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3 AND
                                      v.id_cliente = $cliente AND v.id_sucursal = $sucursal
                                ORDER BY v.fecha DESC, v.nro_factura DESC, d.descripcion");
      
      $resu = $sql->result();
      return $resu;
    }

    public function lst_ventasdetalles_categoriacliente($sucursal, $categoria, $desde, $hasta){
      $sql = $this->db->query("SELECT v.id_venta, v.fecha, v.nro_factura, v.nom_cliente, v.nro_ident, tc.nom_cancelacion,
                                      c.direccion_cliente, c.correo_cliente, c.telefonos_cliente,
                                      v.id_vendedor, v.placa_matricula, v.estatus, v.fecharegistro,
                                      d.descripcion, d.cantidad, u.descripcion as unidadmedida,
                                      d.precio, d.subtotal, d.montoiva, d.descmonto,
                                      round(d.descsubtotal + d.montoiva, 2) as valortotal
                                FROM venta_detalle d 
                                INNER JOIN venta v on v.id_venta = d.id_venta
                                INNER JOIN clientes c on c.id_cliente = v.id_cliente
                                INNER JOIN cliente_categoriaventa cv on cv.id = c.id_categoriaventa
                                INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = v.id_tipcancelacion
                                INNER JOIN producto p on p.pro_id = d.id_producto
                                LEFT JOIN unidadmedida u on u.id = p.pro_idunidadmedida
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3 AND
                                      cv.id = $categoria AND v.id_sucursal = $sucursal
                                ORDER BY v.fecha DESC, v.nro_factura DESC, d.descripcion");
      
      $resu = $sql->result();
      return $resu;
    }

    public function lst_ventas_clienteproducto($sucursal, $cliente, $desde, $hasta){
      $sql = $this->db->query("SELECT d.id_producto, p.pro_nombre as descripcion,
                                      u.descripcion as unidadmedida,
                                      sum(d.cantidad) as cantidad,
                                      avg(d.precio) as precio, 
                                      sum(d.subtotal) as subtotal, 
                                      sum(d.montoiva) as montoiva, 
                                      sum(d.descmonto) as descmonto,
                                      round(sum(d.descsubtotal + d.montoiva), 2) as valortotal
                                FROM venta_detalle d 
                                INNER JOIN venta v on v.id_venta = d.id_venta
                                INNER JOIN producto p on p.pro_id = d.id_producto
                                LEFT JOIN unidadmedida u on u.id = p.pro_idunidadmedida
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3 AND
                                      v.id_cliente = $cliente AND v.id_sucursal = $sucursal
                                GROUP BY d.id_producto
                                ORDER BY valortotal DESC, p.pro_nombre");
      
      $resu = $sql->result();
      return $resu;
    }

    public function lst_ventas_categoriaproducto($sucursal, $categoria, $desde, $hasta){
      $sql = $this->db->query("SELECT d.id_producto, p.pro_nombre as descripcion,
                                      u.descripcion as unidadmedida,
                                      sum(d.cantidad) as cantidad,
                                      avg(d.precio) as precio, 
                                      sum(d.subtotal) as subtotal, 
                                      sum(d.montoiva) as montoiva, 
                                      sum(d.descmonto) as descmonto,
                                      round(sum(d.descsubtotal + d.montoiva), 2) as valortotal
                                FROM venta_detalle d 
                                INNER JOIN venta v on v.id_venta = d.id_venta
                                INNER JOIN clientes c on c.id_cliente = v.id_cliente
                                INNER JOIN cliente_categoriaventa cv on cv.id = c.id_categoriaventa
                                INNER JOIN producto p on p.pro_id = d.id_producto
                                LEFT JOIN unidadmedida u on u.id = p.pro_idunidadmedida
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3 AND
                                      cv.id = $categoria AND v.id_sucursal = $sucursal
                                GROUP BY d.id_producto
                                ORDER BY valortotal DESC, p.pro_nombre");
      
      $resu = $sql->result();
      return $resu;
    }


    public function lst_imeiserie_disponible($iddetalle, $idpro){
      $sql = $this->db->query("SELECT ps.id_serie, ps.numeroserie, ps.descripcion, ps.id_producto, 0 as seleccionado 
                                FROM producto_serie ps
                                WHERE ps.id_estado in (1,6) AND ps.id_detalleventa IS NULL
                                AND ps.id_serie NOT IN (SELECT IFNULL(st.id_serie, 0) 
                                                          FROM venta_detalle_serie_tmp st
                                                          INNER JOIN venta_detalle_tmp dt on dt.id_detalle = st.id_detalle
                                                          INNER JOIN venta_tmp vt on vt.id_venta = dt.id_venta)
                                AND ps.id_producto = $idpro
                                AND ps.id_almacen = (SELECT id_almacen FROM venta_detalle_tmp WHERE id_detalle = $iddetalle)
                               UNION 
                               SELECT ps.id_serie, ps.numeroserie, ps.descripcion, ps.id_producto, 1 as seleccionado 
                                FROM producto_serie ps
                                INNER JOIN venta_detalle_serie_tmp t on t.id_serie = ps.id_serie
                                WHERE t.id_detalle = $iddetalle
                                ORDER BY seleccionado DESC, numeroserie");
      $res = $sql->result();
      return $res;
    }

    public function actualiza_detalle_serie($iddet, $idserie, $inserta){
      $this->db->query("DELETE FROM venta_detalle_serie_tmp WHERE id_serie = $idserie");
      if ($inserta == 1){
        $this->db->query("INSERT INTO venta_detalle_serie_tmp (id_detalle, id_serie) VALUES ($iddet, $idserie)");
      }
      $sql = $this->db->query("SELECT count(*) as cant FROM venta_detalle_serie_tmp 
                                 WHERE id_detalle = $iddet");
      $res = $sql->result();
      $cant = $res[0]->cant;
      $this->db->query("UPDATE venta_detalle_tmp SET cantidad = $cant 
                          WHERE id_detalle = $iddet");
      
      return $cant;
    }

    public function lst_ventasdetalles_producto($sucursal, $producto, $desde, $hasta){
      $sql = $this->db->query("SELECT v.id_venta, v.fecha, v.nro_factura, tc.nom_cancelacion,
                                      TRIM(REPLACE(REPLACE(REPLACE(v.nom_cliente,'\t',''),'\n',''),'\r','')) as nom_cliente,
                                      v.nro_ident, c.direccion_cliente, c.correo_cliente, c.telefonos_cliente,
                                      v.id_vendedor, v.placa_matricula, v.estatus, v.fecharegistro,
                                      d.descripcion, d.cantidad, u.descripcion as unidadmedida,
                                      d.precio, d.subtotal, d.montoiva, d.descmonto,
                                      round(d.descsubtotal + d.montoiva, 2) as valortotal,
                                      IFNULL(tp.desc_precios, 'Tienda') as tipoprecio
                                FROM venta_detalle d 
                                INNER JOIN venta v on v.id_venta = d.id_venta
                                INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = v.id_tipcancelacion
                                INNER JOIN producto p on p.pro_id = d.id_producto
                                INNER JOIN clientes c on c.id_cliente = v.id_cliente
                                LEFT JOIN unidadmedida u on u.id = p.pro_idunidadmedida
                                LEFT JOIN precios tp on tp.id_precios = d.tipprecio
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3 AND
                                      v.id_sucursal = $sucursal AND d.id_producto = $producto
                                ORDER BY v.fecha DESC, v.nro_factura DESC, d.descripcion");
      
      $resu = $sql->result();
      return $resu;
    }

    public function lst_ventasdetalles_categoriaproducto($sucursal, $categoria, $desde, $hasta){
      $sql = $this->db->query("SELECT v.id_venta, v.fecha, v.nro_factura, tc.nom_cancelacion,
                                      TRIM(REPLACE(REPLACE(REPLACE(v.nom_cliente,'\t',''),'\n',''),'\r','')) as nom_cliente,
                                      v.nro_ident, c.direccion_cliente, c.correo_cliente, c.telefonos_cliente,
                                      v.id_vendedor, v.placa_matricula, v.estatus, v.fecharegistro,
                                      d.descripcion, d.cantidad, u.descripcion as unidadmedida,
                                      d.precio, d.subtotal, d.montoiva, d.descmonto,
                                      round(d.descsubtotal + d.montoiva, 2) as valortotal,
                                      IFNULL(tp.desc_precios, 'Tienda') as tipoprecio
                                FROM venta_detalle d 
                                INNER JOIN venta v on v.id_venta = d.id_venta
                                INNER JOIN tipo_cancelacion tc ON tc.id_tipcancelacion = v.id_tipcancelacion
                                INNER JOIN producto p on p.pro_id = d.id_producto
                                INNER JOIN clientes c on c.id_cliente = v.id_cliente
                                LEFT JOIN unidadmedida u on u.id = p.pro_idunidadmedida
                                LEFT JOIN precios tp on tp.id_precios = d.tipprecio
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3 AND
                                      v.id_sucursal = $sucursal AND p.pro_idcategoria = $categoria
                                ORDER BY v.fecha DESC, v.nro_factura DESC, d.descripcion");
      
      $resu = $sql->result();
      return $resu;
    }

    public function asignar_clientecategoria($idfactura){
      $sql = $this->db->query("SELECT v.id_cliente FROM venta v
                                  WHERE id_venta = $idfactura");
      $result = $sql->result();
      if ($result){
        $cliente = $result[0]->id_cliente;
        if ($cliente != 1){
          $sql = $this->db->query("SELECT SUM(montototal) as monto FROM venta
                                    WHERE (id_cliente = $cliente)");
          $result = $sql->result();
          if ($result){
            $suma = $result[0]->monto;
            $sql = $this->db->query("SELECT id FROM cliente_categoriaventa
                                        WHERE monto_minimo <= $suma
                                        ORDER by monto_minimo DESC LIMIT 1");
            $result = $sql->result();
            if (count($result) > 0){
              $idcateg = $result[0]->id;
              $this->db->query("UPDATE clientes SET id_categoriaventa = $idcateg
                                  WHERE id_cliente = $cliente");
            }
          }
        }  
      }
    }

    public function lst_ventasvendedor_resumencliente($sucursal, $vendedor, $desde, $hasta){
      $sql = $this->db->query("SELECT c.id_cliente, 
                                      TRIM(REPLACE(REPLACE(REPLACE(c.nom_cliente,'\t',''),'\n',''),'\r','')) as nom_cliente,                              
                                      c.ident_cliente, c.direccion_cliente, 
                                      c.correo_cliente, c.telefonos_cliente,
                                      count(*) as cantidadfacturas,
                                      sum(subsiniva) as subsiniva,
                                      sum(subconiva) as subconiva,
                                      sum(desc_monto) as descmonto,
                                      sum(montoiva) as montoiva,
                                      sum(montototal) as montototal,
                                      IFNULL(tp.desc_precios, 'Tienda') as tipoprecio
                                FROM venta v 
                                INNER JOIN clientes c on c.id_cliente = v.id_cliente
                                LEFT JOIN precios tp on tp.id_precios = c.tipo_precio
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3 AND
                                      v.id_sucursal = $sucursal AND v.id_vendedor = $vendedor
                                GROUP BY c.id_cliente      
                                ORDER BY c.nom_cliente");      
      $resu = $sql->result();
      return $resu;
    }

    public function lst_ventasvendedor_resumenproducto($sucursal, $vendedor, $desde, $hasta){
      $sql = $this->db->query("SELECT d.id_producto, p.pro_nombre as descripcion,
                                      u.descripcion as unidadmedida,
                                      d.tipprecio,
                                      sum(d.cantidad) as cantidad,
                                      avg(d.precio) as precio, 
                                      sum(d.subtotal) as subtotal, 
                                      sum(d.montoiva) as montoiva, 
                                      sum(d.descmonto) as descmonto,
                                      round(sum(d.descsubtotal + d.montoiva), 2) as valortotal,
                                      IFNULL(tp.desc_precios, 'Tienda') as tipoprecio
                                FROM venta_detalle d 
                                INNER JOIN venta v on v.id_venta = d.id_venta
                                INNER JOIN producto p on p.pro_id = d.id_producto
                                LEFT JOIN unidadmedida u on u.id = p.pro_idunidadmedida
                                LEFT JOIN precios tp on tp.id_precios = d.tipprecio
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3 AND
                                      v.id_vendedor = $vendedor AND v.id_sucursal = $sucursal
                                GROUP BY d.id_producto, d.tipprecio
                                ORDER BY valortotal DESC, p.pro_nombre");
      
      $resu = $sql->result();
      return $resu;
    }

    public function lst_ventasvendedor_resumen($sucursal, $desde, $hasta){
      $sql = $this->db->query("SELECT u.id_usu, u.nom_usu, u.ape_usu,
                                      count(*) as cantidadfacturas,
                                      sum(subsiniva) as subsiniva,
                                      sum(subconiva) as subconiva,
                                      sum(desc_monto) as descmonto,
                                      sum(montoiva) as montoiva,
                                      sum(montototal) as montototal,
                                      IFNULL(tp.desc_precios, 'Tienda') as tipoprecio
                                FROM venta v 
                                INNER JOIN usu_sistemas u on u.id_usu = v.id_vendedor
                                INNER JOIN clientes c on c.id_cliente = v.id_cliente
                                LEFT JOIN precios tp on tp.id_precios = c.tipo_precio
                                WHERE v.fecha BETWEEN '$desde' AND '$hasta' AND v.estatus != 3 AND
                                      v.id_sucursal = $sucursal 
                                GROUP BY u.id_usu      
                                ORDER BY u.nom_usu, u.ape_usu");      
      $resu = $sql->result();
      return $resu;
    }

    public function lst_venta_datoadicional_tmp($usuario){
      $sql = $this->db->query("SELECT d.id_config, d.datoadicional, c.nombre_datoadicional
                                FROM venta_dato_adicional_tmp d 
                                INNER JOIN venta_tmp v on v.id_venta = d.id_venta
                                INNER JOIN venta_config_adicional c on c.id_config = d.id_config
                                WHERE v.idusu = $usuario AND c.activo = 1
                                ORDER BY c.nombre_datoadicional");      
      $resu = $sql->result();
      return $resu;
    }

    public function upd_datoadicional_tmp($usuario, $idconfig, $dato){
      $this->db->query("UPDATE venta_dato_adicional_tmp  
                          SET datoadicional = '$dato'
                          WHERE id_config = $idconfig AND 
                                id_venta = (SELECT id_venta FROM venta_tmp WHERE idusu = $usuario)");      
    }

}