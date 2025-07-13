<?php

/* ------------------------------------------------
  ARCHIVO: Proforma_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Proforma.
  FECHA DE CREACIÓN: 17/11/2017
 * 
  ------------------------------------------------ */

class Proforma_model extends CI_Model {

  function __construct() {
      parent::__construct();
  }

  public function lst_proforma($desde, $hasta){
    $usua = $this->session->userdata('usua');
    $idusu = $usua->id_usu;
    $sql = $this->db->query(" SELECT p.id_proforma, p.fecha, p.nro_proforma, CONCAT(u.nom_usu,' ',u.ape_usu) as vendedor, c.nom_cliente, p.montototal, p.id_factura, p.id_vendedor
                              FROM proforma p
                              INNER JOIN permiso_sucursal ps on ps.id_sucursal = p.id_sucursal
                              LEFT JOIN usu_sistemas u ON u.id_usu = p.id_vendedor /*idusu*/
                              INNER JOIN clientes c ON c.id_cliente = p.id_cliente
                              WHERE p.fecha BETWEEN '$desde' AND '$hasta' AND ps.id_usuario = $idusu
                              ORDER BY p.id_proforma DESC");
    $resu = $sql->result();
    return $resu;
  }

  public function tip_ident(){
    $sql = $this->db->query("SELECT cod_identificacion as cod, desc_identificacion as det FROM identificacion");
    $resu = $sql->result();
    return $resu;
  }

  public function sel_nro_proforma(){
    $sql = $this->db->query("SELECT (valor) AS nrofact FROM contador WHERE id_contador = 6");
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
  }

  public function limpiarproformatmp($idusu){
    $sql = $this->db->query("DELETE FROM proforma_detalle_tmp WHERE id_proforma IN (SELECT id_proforma from proforma_tmp WHERE idusu = $idusu)");
    $sql = $this->db->query("DELETE FROM proforma_tmp WHERE idusu = $idusu");
  }

  public function carga_cliente_proforma($idusu){

    $query = $this->db->query("SELECT valor FROM parametros WHERE id=1;");
    $result = $query->result();
    $valiva =  $result[0]->valor;    

    $sql = $this->db->query("SELECT COUNT(*) AS val FROM proforma_tmp WHERE idusu = $idusu");
    $cliven = $sql->result();
    $val = $cliven[0]->val;

    if($val == 0){
      $selcli = $this->db->query("SELECT id_cliente FROM clientes WHERE id_cliente = 1");
      $clinvo = $selcli->result();
      $id_cli = $clinvo[0]->id_cliente;
      $fecha = date('Y-m-d');
/*
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
*/

      $this->db->query("INSERT INTO proforma_tmp (fecha, id_cliente, valiva, id_vendedor, id_puntoventa, idusu, id_sucursal)
                          SELECT '$fecha', $id_cli, $valiva, $idusu, 1, $idusu,
                                 (SELECT id_sucursal FROM sucursal WHERE activo = 1 LIMIT 1)");
    }

    $sqlcli = $this->db->query("SELECT p.id_proforma, p.nro_proforma, p.fecha, p.id_cliente, c.ident_cliente, c.nom_cliente, 
                                       c.tipo_ident_cliente, c.correo_cliente, c.ciudad_cliente, c.direccion_cliente, 
                                       c.telefonos_cliente, p.observaciones, c.codigo, p.id_sucursal
                                FROM proforma_tmp p
                                INNER JOIN clientes c ON c.id_cliente = p.id_cliente
                                WHERE idusu = $idusu");
    $cliver = $sqlcli->result();
    return $cliver[0];      
  }

/*
    public function upd_profcliente($idusu, $nrocli, $idtp, $nom, $tel, $cor, $dir, $ciu, $idc, $obs){
      $correo = ""; $telf = ""; $ciud = ""; $dire = ""; $observ = "";
      if($obs == NULL || $obs == ""){}else{$observ = ",observaciones = '$obs'"; }
      if($idc == 0){ 
        $sqlvercli = $this->db->query("SELECT COUNT(*) AS val FROM clientes WHERE ident_cliente = '$nrocli'");
        $cliver = $sqlvercli->result();
        $ver = $cliver[0]->val;
        if($ver == 0){
          $query = $this->db->query("INSERT INTO clientes (tipo_ident_cliente, ident_cliente, nom_cliente) 
                                                   VALUES ('$idtp', '$nrocli', '$nom');");   
           $this->upd_profcliente($idusu, $nrocli, $idtp, $nom, $tel, $cor, $dir, $ciu, $idc, $obs);                                                
        }
        $sqlcli = $this->db->query("SELECT id_cliente FROM clientes WHERE ident_cliente = '$nrocli'");
        $rescli = $sqlcli->result();
        $idcli = $rescli[0]->id_cliente;

        $sql = $this->db->query("SELECT COUNT(*) AS val FROM proforma_tmp WHERE idusu = $idusu");
        $cliven = $sql->result();
        $val = $cliven[0]->val;
        if($val > 0){ $updrescli = $this->db->query(" UPDATE proforma_tmp SET id_cliente = $idcli $observ WHERE idusu = $idusu");
          return 0;
        }        
      }else{ 
        if($cor == NULL || $cor == ""){}else{$correo = ",correo_cliente = '$cor'"; }
        if($tel == NULL || $tel == ""){}else{$telf = ",telefonos_cliente = '$tel'";}
        if($ciu == NULL || $ciu == ""){}else{$ciud = ",ciudad_cliente = '$ciu'";} 
        if($dir == NULL || $dir == ""){}else{$dire = ",direccion_cliente = '$dir'";}

        $query = $this->db->query(" UPDATE clientes SET nom_cliente = '$nom',
                                                        tipo_ident_cliente = '$idtp',
                                                        ident_cliente = '$nrocli'
                                                        $correo
                                                        $telf
                                                        $ciud
                                                        $dire
                                                    WHERE id_cliente = $idc"); 
 
        $sql = $this->db->query("SELECT COUNT(*) AS val FROM proforma_tmp WHERE idusu = $idusu");
        $cliven = $sql->result();
        $val = $cliven[0]->val;

        if($val > 0){
          $updrescli = $this->db->query(" UPDATE proforma_tmp SET id_cliente = $idc $observ WHERE idusu = $idusu");
          return 0;
        }        
      }


    }
*/

    public function upd_profcliente($idusu, $nrocli, $idtp, $nom, $tel, $cor, $dir, $ciu, $idc, $obs, $suc){
      $query = "";
      $correo = ""; $telf = ""; $ciud = ""; $dire = ""; $observ = "";
      if($obs == NULL || $obs == ""){}else{$observ = ",observaciones = '$obs'"; }
      if($suc == NULL || $suc == ""){ $suc = 0; }
      $sql = $this->db->query("SELECT COUNT(*) AS val FROM proforma_tmp WHERE idusu = $idusu");
      $clipro = $sql->result();
      $val = $clipro[0]->val;
      if($val > 0){
        $sqlcli = $this->db->query("SELECT id_cliente, COUNT(*) AS nrocli FROM clientes WHERE ident_cliente = '$nrocli' ");
        $resucli = $sqlcli->result();
        $valcli = $resucli[0]->nrocli;
        $idcli = $resucli[0]->id_cliente;   

             
        if($idcli > 0 ){  
          
          if($cor == NULL || $cor == ""){}else{$correo = ",correo_cliente = '$cor'"; }
          if($tel == NULL || $tel == ""){}else{$telf = ",telefonos_cliente = '$tel'";}
          if($ciu == NULL || $ciu == ""){}else{$ciud = ",ciudad_cliente = '$ciu'";}
          if($dir == NULL || $dir == ""){}else{$dire = ",direccion_cliente = '$dir'";}
          if ($idcli <> 1) {
            
            $this->db->query(" UPDATE clientes SET nom_cliente = '$nom',
                                                  tipo_ident_cliente = '$idtp',
                                                  ident_cliente = '$nrocli'
                                                  $correo
                                                  $telf
                                                  $ciud
                                                  $dire
                                              WHERE id_cliente!=1 AND id_cliente = $idcli"); 

          }           
          $this->db->query(" UPDATE proforma_tmp 
                              SET id_cliente = $idcli $observ,
                                  id_sucursal = $suc,
                                  id_vendedor = IFNULL((SELECT id_vendedor FROM clientes WHERE id_cliente = $idcli LIMIT 1),idusu) 
                              WHERE idusu = $idusu");  
    

        }else{
          $sql_addc = $this->db->query("INSERT INTO clientes (tipo_ident_cliente, nom_cliente, ident_cliente) VALUES ('$idtp', '$nom', '$nrocli')");
          $sqlcli = $this->db->query("SELECT id_cliente FROM clientes WHERE ident_cliente = '$nrocli' ");
          $resucli = $sqlcli->result();
          $idcli = $resucli[0]->id_cliente; 
          $this->db->query("INSERT into cliente_tipoprecio (id_cliente, id_precio, estatus)
                              select $idcli, id_precios, 1 from precios");
          
          $updrescli = $this->db->query(" UPDATE proforma_tmp SET id_cliente = $idcli $observ WHERE idusu = $idusu");
          $this->upd_profcliente($idusu, $nrocli, $idtp, $nom, $tel, $cor, $dir, $ciu, $idc, $obs, $suc);
        } 
      }
      return $idcli;
    }


    public function ins_profdettmp($idusu, $idpro, $idalm){
      $sel_obj = $this->db->query(" INSERT INTO proforma_detalle_tmp 
                                    (id_proforma,id_producto,cantidad,precio,iva,descmonto, id_almacen, tipprecio, descripcion, porcdesc)  
                                    SELECT id_proforma, pro_id, 1, 
                                      case IFNULL(c.tipo_precio,0) when 0 then pro_precioventa 
                                        else IFNULL((SELECT monto FROM prepro pp 
                                                WHERE pp.id_precios=c.tipo_precio AND pp.pro_id=$idpro),0)
                                      end as precio,  
                                      pro_grabaiva, 0, 
                                      CASE producto.preparado WHEN 0 THEN $idalm ELSE (SELECT id_alm FROM almapro a INNER JOIN producto_ingrediente i ON i.id_proing = a.id_pro WHERE i.id_pro = $idpro LIMIT 1) 
                                      END, IFNULL(c.tipo_precio,0), pro_nombre, 0
                                      FROM proforma_tmp, producto, parametros prm, clientes c
                                      WHERE prm.id=1 AND idusu=$idusu AND pro_id=$idpro 
                                        and c.id_cliente=proforma_tmp.id_cliente"); 

      $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM proforma_detalle_tmp");
      $varid = $sqlid->result();
      if ($varid){
        $iddet = $varid[0]->id;
        $this->db->query("UPDATE proforma_detalle_tmp SET
                            subtotal = precio,
                            descsubtotal = precio,
                            montoiva = case iva when 0 then 0 else
                                          round(precio * (SELECT valor FROM parametros prm WHERE prm.id=1),2) 
                                       end
                            WHERE id_detalle=$iddet");
      }  
    }  

    public function ins_profdettmp0($idusu, $idpro, $idalm){
      $sel_obj = $this->db->query(" INSERT INTO proforma_detalle_tmp 
                                    (id_proforma,id_producto,cantidad,precio,subtotal,iva,montoiva,descmonto,descsubtotal, id_almacen, tipprecio)  
                                    SELECT id_proforma, pro_id, 1, pro_precioventa, round(pro_precioventa,2), pro_grabaiva,
                                      round(CASE pro_grabaiva WHEN 1 THEN round(pro_precioventa * (1 + prm.valor),2) - round(pro_precioventa,2) ELSE 0 END,2), 
                                      0, round(pro_precioventa,2), 
                                      CASE producto.preparado WHEN 0 THEN $idalm ELSE (SELECT id_alm FROM almapro a INNER JOIN producto_ingrediente i ON i.id_proing = a.id_pro WHERE i.id_pro = $idpro LIMIT 1) END, 0
                                      FROM proforma_tmp, producto, parametros prm
                                      WHERE prm.id=1 AND idusu=$idusu AND pro_id=$idpro"); 

    }  

    public function ins_profdettmpcodbar($idusu, $codbar, $idalm){
      $sel_obj = $this->db->query(" INSERT INTO proforma_detalle_tmp 
                                    (id_proforma,id_producto,cantidad,precio,subtotal,iva,montoiva,descmonto,descsubtotal, id_almacen, tipprecio, descripcion, porcdesc)  
                                    SELECT id_proforma, pro_id, 1, pro_precioventa, round(pro_precioventa,2), pro_grabaiva,
                                      round(CASE pro_grabaiva WHEN 1 THEN round(pro_precioventa * (1 + prm.valor),2) - round(pro_precioventa,2) ELSE 0 END,2), 
                                      0, round(pro_precioventa,2),
                                      CASE producto.preparado WHEN 0 THEN $idalm ELSE (SELECT id_alm FROM almapro a INNER JOIN producto_ingrediente i ON i.id_proing = a.id_pro WHERE i.id_pro = producto.pro_id LIMIT 1) END, 0, pro_nombre, 0
                                      FROM proforma_tmp, producto, parametros prm
                                      WHERE prm.id=1 AND idusu=$idusu AND pro_codigobarra='$codbar'"); 

    } 




    public function lst_profdettmp($id_usuario){
      $sql_sel = $this->db->query("SELECT pd.id_detalle, pd.id_producto, p.pro_nombre, pd.precio, pd.descripcion,
                                          p.pro_grabaiva, pd.cantidad, pd.subtotal, pd.descsubtotal, pd.tipprecio, 
                                          pd.porcdesc, pd.descmonto
                                    FROM proforma_detalle_tmp pd
                                    INNER JOIN proforma_tmp pt on pt.id_proforma = pd.id_proforma
                                    INNER JOIN producto p ON p.pro_id = pd.id_producto
                                    WHERE pt.idusu = $id_usuario");
      $result = $sql_sel->result();
      return $result;
    }

    public function selcliproftmp($idusu){
      $sql = $this->db->query("SELECT id_cliente FROM proforma_tmp WHERE idusu = $idusu");
      $resu = $sql->result();
      $res = $resu[0]->id_cliente;
      return $res;
    }

    public function precioprof($idcliente=null){
      if (!$idcliente) $idcliente = 0;
      $idusu = $this->session->userdata("sess_id");
      $strsql = "SELECT p.pro_id, p.pro_nombre, p.pro_precioventa as precio, 'Tienda', 0 as idprepro
                  FROM producto p
                  INNER JOIN proforma_detalle_tmp pd on pd.id_producto = p.pro_id
                  INNER JOIN proforma_tmp pt on pt.id_proforma = pd.id_proforma
                  WHERE pt.idusu = $idusu
                  UNION
                  SELECT p.pro_id, p.pro_nombre, monto as precio, pc.desc_precios, pp.id_precios as idprepro
                  FROM producto p
                  INNER JOIN prepro pp ON pp.pro_id = p.pro_id
                  INNER JOIN precios pc ON pc.id_precios = pp.id_precios
                  INNER JOIN proforma_detalle_tmp pd on pd.id_producto = p.pro_id
                  INNER JOIN proforma_tmp pt on pt.id_proforma = pd.id_proforma
                  LEFT JOIN cliente_tipoprecio ctp ON ctp.id_precio = pc.id_precios AND ctp.id_cliente = $idcliente
                  WHERE pt.idusu = $idusu  AND
                        ( ($idcliente = 1) OR (IFNULL(ctp.estatus,0) = 1) )";

      if ($idcliente == 0){
        $strsql .= " UNION SELECT pu.pro_id, pu.pro_nombre, 0 as precio, 'Ultimo precio' as desc_precios, 999999999 as idprepro FROM producto pu";
      }  else {
        $query = $this->db->query("SELECT precio FROM venta_detalle d
                                     INNER JOIN venta v on v.id_venta = d.id_venta
                                     INNER JOIN clientes c on c.ident_cliente = v.nro_ident
                                     WHERE c.id_cliente=$idcliente");
        $resu = $query->result();
        $ultvalor = 0;
        if ($resu != null){
          $ultvalor = $resu[0]->precio;  
        }
        $strsql .= " UNION 
                    SELECT pu.pro_id, pu.pro_nombre, 
                           (SELECT precio FROM venta_detalle dc
                         INNER JOIN venta vc on vc.id_venta = dc.id_venta
                         INNER JOIN clientes c on c.ident_cliente = vc.nro_ident
                         WHERE c.id_cliente=$idcliente and dc.id_producto  = pu.pro_id
                             order by vc.fecha desc, vc.id_venta desc limit 1) as precio, 
                           'Ultimo precio' as desc_precios, 999999999 as idprepro 
                    FROM producto pu                  
                    INNER JOIN proforma_detalle_tmp pd on pd.id_producto = pu.pro_id
                    INNER JOIN proforma_tmp pt on pt.id_proforma = pd.id_proforma
                    WHERE pt.idusu = $idusu";
      }      

      $sql = $this->db->query($strsql);
      $resu = $sql->result();
      return $resu;
    }

    public function del_profdettmp($iddetalle){
      $sel_obj = $this->db->query("delete from proforma_detalle_tmp where id_detalle=$iddetalle"); 
    }  

    public function lst_subtotalesprofdettmp($id_usuario){
      $parametro = &get_instance();
      $parametro->load->model("Parametros_model");
      $descuentoproducto = $parametro->Parametros_model->sel_descpro();   
      $tipodescuentoproducto = $parametro->Parametros_model->sel_tipodescuentoproducto();   

      $montototal = 0;
      $sql_sel = $this->db->query("SELECT sum(case when (pd.id_proforma is not null) and p.pro_grabaiva = 1 then pd.subtotal else 0 end) as subtotaliva,
                                          sum(case when (pd.id_proforma is not null) and p.pro_grabaiva = 0 then pd.subtotal else 0 end) as subtotalcero,
                                          ifnull(min(pt.desc_monto),0) as descuento, pt.id_proforma
                                    FROM proforma_tmp pt
                                    LEFT JOIN proforma_detalle_tmp pd on pd.id_proforma = pt.id_proforma
                                    LEFT JOIN producto p ON p.pro_id = pd.id_producto                                    
                                    WHERE pt.idusu = $id_usuario
                                    GROUP BY pt.id_proforma");      
      $objresult = $sql_sel->result();
      $result = $objresult[0];
      if ($result){
        $id_proforma = $result->id_proforma;
        $subtotaliva = $result->subtotaliva;
        $subtotalcero = $result->subtotalcero;
        $descuento = $result->descuento;
        if ($descuento >= ($subtotaliva + $subtotalcero)){
          $descuento = 0;
        }
        if (($subtotaliva + $subtotalcero) > 0){

          if ($descuentoproducto == 0){
            $sql_sel = $this->db->query("UPDATE proforma_detalle_tmp 
                                           set descmonto = round($descuento / ($subtotaliva + $subtotalcero) * subtotal, 2)
                                           WHERE id_proforma = $id_proforma");      
          }
          else{
            if ($tipodescuentoproducto == 1){ // Descuento por Porciento
              $sql_sel = $this->db->query("UPDATE proforma_detalle_tmp 
                                             set descmonto = round(subtotal * porcdesc / 100, 2)
                                             WHERE id_proforma = $id_proforma");      
            }
          }    

          $sql_sel = $this->db->query("UPDATE proforma_detalle_tmp 
                                         set descsubtotal = subtotal - descmonto,
                                             montoiva = round(case iva when 1 then round((subtotal - descmonto) * (1 + IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)),2) - round(subtotal - descmonto,2) else 0 end,2)                                                                                       
                                         WHERE id_proforma = $id_proforma");      
        }
        $sql_sel = $this->db->query("update proforma_tmp 
                                       set desc_monto = $descuento,
                                       subconiva = ifnull((select sum(d.subtotal) from proforma_detalle_tmp d where iva=1 and id_proforma=$id_proforma),0),    
                                       subsiniva = ifnull((select sum(d.subtotal) from proforma_detalle_tmp d where iva=0 and id_proforma=$id_proforma),0),    
                                       descsubconiva = ifnull((select sum(d.descsubtotal) from proforma_detalle_tmp d where iva=1 and id_proforma=$id_proforma),0),    
                                       descsubsiniva = ifnull((select sum(d.descsubtotal) from proforma_detalle_tmp d where iva=0 and id_proforma=$id_proforma),0),    
                                       montoiva = ifnull((select sum(d.montoiva) from proforma_detalle_tmp d where iva=1 and id_proforma=$id_proforma),0)    
                                      WHERE id_proforma = $id_proforma");      
        
      }
      $sql_sel = $this->db->query("SELECT subconiva as subtotaliva,
                                          subsiniva as subtotalcero,
                                          descsubconiva as descsubtotaliva,
                                          descsubsiniva as descsubtotalcero,
                                          desc_monto as descuento, 
                                          montoiva,
                                          id_proforma
                                    FROM proforma_tmp
                                    WHERE idusu = $id_usuario");      
      $result = $sql_sel->result();
      $descsubconiva = $result[0]->descsubtotaliva;
      $descsubsiniva = $result[0]->descsubtotalcero;
      $montoiva = $result[0]->montoiva;
      $montototal = $descsubconiva + $descsubsiniva + $montoiva;
      $sql_upd = $this->db->query("UPDATE proforma_tmp SET montototal = $montototal WHERE id_proforma = $id_proforma"); 
      return $result[0];
    }  

    public function upd_detalleprof($iddetalle, $cantidad, $precio, $valiva, $subtotal, $tp, $descpro){
      if ($descpro == '') { $descpro = 0; }
      $parametro = &get_instance();
      $parametro->load->model("Parametros_model");
      $descuentoproducto = $parametro->Parametros_model->sel_descpro();   
      $tipodescuentoproducto = $parametro->Parametros_model->sel_tipodescuentoproducto();   

      if (($descuentoproducto == 1) && ($tipodescuentoproducto == 0)){
        $sel_obj = $this->db->query("UPDATE proforma_detalle_tmp SET
                                      cantidad = $cantidad, precio = $precio, montoiva = $valiva,
                                      subtotal = $subtotal, descsubtotal = $subtotal, tipprecio = $tp,
                                      descmonto = $descpro,
                                      porcdesc = 0
                                     WHERE id_detalle=$iddetalle"); 
      }
      else{
        $sel_obj = $this->db->query("UPDATE proforma_detalle_tmp SET
                                      cantidad = $cantidad, precio = $precio, montoiva = $valiva,
                                      subtotal = $subtotal, descsubtotal = $subtotal, tipprecio = $tp,
                                      porcdesc = $descpro
                                     WHERE id_detalle=$iddetalle"); 
      }  
    }  

    public function upd_descuentoproftmp($id_usuario, $descuento){
      $sel_obj = $this->db->query("UPDATE proforma_tmp SET desc_monto = $descuento WHERE idusu = $id_usuario"); 
    } 

    public function sel_descripciondetalle($id){
      $sel_obj = $this->db->query("SELECT descripcion FROM proforma_detalle_tmp WHERE id_detalle=$id"); 
      $result = $sel_obj->result();
      if ($result)
        return $result[0]->descripcion;
      else
        return '';
    }  

    public function udp_descripciondetalle($id, $descripcion){
      $sel_obj = $this->db->query("UPDATE proforma_detalle_tmp SET descripcion = '$descripcion'
                                     WHERE id_detalle=$id"); 
    }  

    public function proforma_guardar(){
      $idusu = $this->session->userdata("sess_id");
      $sql_add = $this->db->query("call proforma_ins ($idusu)");
      $resultado = $sql_add->result();
      $id = $resultado[0]->vid; 
      $sql_add->next_result(); 
      $sql_add->free_result();   
      return $id;
    }

    public function proforma_modificar($idusu, $idproforma){
      $idusu = $this->session->userdata("sess_id");
      $sql_upd = $this->db->query("call proforma_upd_id($idusu, $idproforma)");
      $resultado = $sql_upd->result();
      $id = $resultado[0]->idprof; 
      $sql_upd->next_result(); 
      $sql_upd->free_result();   
      return $id;
    }

    public function selprofid($idusu, $idproforma){
      $sql_edi = $this->db->query("CALL proforma_sel_id($idusu, $idproforma)");
      $resultado = $sql_edi->result();
      $id = $resultado[0]->idprof; 
      $sql_edi->next_result(); 
      $sql_edi->free_result();   
      return $id;
    }    

    public function sel_datoproformaid($idproforma){
      $sql = $this->db->query("SELECT p.id_proforma, p.fecha, p.nro_proforma, 
                                      CONCAT(u.nom_usu,' ',u.ape_usu) as vendedor, 
                                      p.subsiniva, p.subconiva, p.desc_monto, p.descsubconiva, 
                                      p.descsubsiniva, p.montoiva, p.montototal,
                                      p.montototal, p.observaciones, p.id_sucursal,
                                      c.nom_cliente, c.ident_cliente, c.direccion_cliente, c.telefonos_cliente
                                FROM proforma p
                                INNER JOIN usu_sistemas u ON u.id_usu = p.idusu
                                INNER JOIN clientes c ON c.id_cliente = p.id_cliente
                                WHERE id_proforma = $idproforma");
      $resu = $sql->result();
      return $resu[0];
    }

    public function eliminar($idproforma){
      $sql_edi = $this->db->query("CALL proforma_del($idproforma)");
      $resultado = $sql_edi->result();
      $id = $resultado[0]->res; 
      $sql_edi->next_result(); 
      $sql_edi->free_result();   
      return $id;
    }    

    public function upd_fechaproftmp($id_usuario, $fecha){
      $sel_obj = $this->db->query("UPDATE proforma_tmp SET fecha = '$fecha' WHERE idusu = $id_usuario"); 
    } 

    public function genera_factura($idproforma, $idusu, $caja){
      $sqldel = $this->db->query("DELETE FROM venta_detalle_tmp where id_venta IN (select id_venta from venta_tmp where idusu = $idusu)");
      $sqldel = $this->db->query("DELETE from venta_tmp where idusu = $idusu");
      $sql_edi = $this->db->query("CALL proforma_facturar($idproforma, $idusu, $caja)");
      $resultado = $sql_edi->result();
      $id = $resultado[0]->vid; 
      $sql_edi->next_result(); 
      $sql_edi->free_result();   
      return $id;
    }   

    public function lst_profdetalle($idproforma){
      $sql_sel = $this->db->query(" SELECT pd.id_detalle, pd.id_producto, p.pro_nombre, pd.precio, pd.montoiva,
                                           p.pro_grabaiva, pd.cantidad, pd.subtotal, pd.descsubtotal, 
                                           pd.tipprecio, pd.descripcion, pd.porcdesc, pd.descmonto
                                    FROM proforma_detalle pd
                                    INNER JOIN producto p ON p.pro_id = pd.id_producto
                                    WHERE pd.id_proforma = $idproforma");
      $result = $sql_sel->result();
      return $result;
    }

    public function lst_profimagen($idproforma){
      $sql_sel = $this->db->query(" SELECT pd.id_detalle, pd.id_producto, p.pro_nombre, p.pro_descripcion, p.pro_imagen
                                    FROM proforma_detalle pd
                                    INNER JOIN producto p ON p.pro_id = pd.id_producto
                                    WHERE pd.id_proforma = $idproforma");
      $result = $sql_sel->result();
      return $result;
    }


    public function cabproforma($idproforma) {
      $sql = $this->db->query(" SELECT p.id_cliente, c.id_cliente, c.nom_cliente, c.ident_cliente, c.direccion_cliente, 
                                      c.telefonos_cliente, p.fecha, p.subconiva, p.subsiniva, p.desc_monto, p.descsubconiva, 
                                      p.descsubsiniva, p.montoiva, p.montototal
                                FROM proforma p
                                INNER JOIN clientes c ON c.id_cliente = p.id_cliente 
                                WHERE p.id_proforma = $idproforma");
      $resu = $sql->result();
      return $resu[0];
    }

    public function almacenes(){
      $sql = $this->db->query("SELECT almacen_id, almacen_nombre FROM almacen");
      $resu = $sql->result();
      return $resu;
    }

    public function lstprof_pro(){

      $tipodoc = 2;

      $sql = $this->db->query(" SELECT p.pro_id, p.pro_codigobarra, p.pro_codigoauxiliar, p.pro_nombre, 
                                CASE $tipodoc WHEN 2 THEN p.pro_precioventa ELSE
                                  ROUND((IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)+1) * p.pro_precioventa, 6) END
                                AS pro_precioventa,
                                ap.id_alm, ap.existencia, a.almacen_nombre, p.preparado,
                                p.pro_maximo AS max, p.pro_minimo AS min
                                FROM producto p
                                INNER JOIN almapro ap ON ap.id_pro = p.pro_id 
                                INNER JOIN almacen a ON a.almacen_id = ap.id_alm
                                WHERE pro_apliventa = 1 
                                
                                UNION  

                                SELECT p.pro_id, p.pro_codigobarra, p.pro_codigoauxiliar, p.pro_nombre, 
                                CASE $tipodoc WHEN 2 THEN p.pro_precioventa ELSE
                                ROUND((IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)+1) * p.pro_precioventa, 6) END
                                AS pro_precioventa, 
                                0 AS id_alm, 0 AS existencia, '' AS almacen_nombre, p.preparado,
                                p.pro_maximo AS max, p.pro_minimo AS min
                                FROM producto p
                                WHERE p.preparado = 1
                                ORDER BY pro_nombre ASC");
      $resu = $sql->result();
      return $resu;
    }


    public function selprofprecio($idpro, $idcliente=null){
      if (!$idcliente) $idcliente = 0;
      $tipodoc = 2;

      $strsql = "SELECT p.pro_id, p.pro_nombre, 
                  CASE $tipodoc WHEN 2 THEN p.pro_precioventa ELSE
                    ROUND((IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)+1) * p.pro_precioventa, 6) END
                  AS precio, 0 as idpre, 'Tienda' as nompre, 0 as idprepro
                  FROM producto p
                  WHERE p.pro_id = $idpro
                  UNION
                  SELECT p.pro_id, p.pro_nombre, 
                  CASE $tipodoc WHEN 2 THEN monto ELSE
                    ROUND((IFNULL((SELECT valor FROM parametros WHERE id = 1), 0.12)+1) * monto, 6) END
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

    public function reporte($desde, $hasta){
      $desde = $desde." 00:00:00";
      $hasta = $hasta." 23:59:59";
      $sql = $this->db->query(" SELECT p.nro_proforma, p.fecharegistro, c.nom_cliente, c.ident_cliente, 
                                       p.montototal, CONCAT(u.nom_usu,' ',u.ape_usu) AS vendedor, 
                                       p.id_factura, t.categoria, v.fecharegistro AS fecventa, v.nro_factura
                                FROM proforma p
                                INNER JOIN clientes c ON c.id_cliente = p.id_cliente
                                INNER JOIN usu_sistemas u ON u.id_usu = p.idusu
                                LEFT JOIN venta v ON v.id_venta = p.id_factura
                                LEFT JOIN contador t ON t.id_contador = v.tipo_doc
                                WHERE p.fecharegistro BETWEEN '$desde' AND '$hasta' ");
      $resu = $sql->result();
      return $resu;
    }

    public function valpro(){
      $idusu = $this->session->userdata("sess_id");
      $sql = $this->db->query("SELECT COUNT(*) AS val FROM proforma_detalle_tmp WHERE id_proforma = (SELECT id_proforma FROM proforma_tmp WHERE idusu = $idusu)");
      $resu = $sql->result();
      $val = $resu[0]->val;
      return $val;
    }

    public function valmontopro(){
      $idusu = $this->session->userdata("sess_id");
      $sql = $this->db->query("SELECT montototal FROM proforma_tmp WHERE idusu = $idusu");
      $resu = $sql->result();
      $total = $resu[0]->montototal;
      return $total;
    }

    public function lstprecios(){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;      
      $pre = $this->db->query("Select  t.* from 
                                  (SELECT 0 as id,'Tienda' as nompre UNION
                                   SELECT id_precios, desc_precios FROM precios WHERE esta_precios = 'A') as t
                                   inner join usuprecio p on p.idpre = t.id
                                   inner join proforma_tmp v on v.idusu = $idusu
                                   LEFT join cliente_tipoprecio ctp on ctp.id_cliente = v.id_cliente AND ctp.id_precio = t.id 
                                   where p.estatus=1 and p.idusu=$idusu and 
                                         ( (v.id_cliente = 1) OR (IFNULL(ctp.estatus,0) = 1) )
                                UNION 
                                SELECT 999999999 as id,'Ultimo precio' as nompre
                                Order by id");
      $resu = $pre->result();
      return $resu;
    }


}