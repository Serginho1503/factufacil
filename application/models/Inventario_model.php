<?php

/* ------------------------------------------------
  ARCHIVO: Inventario_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la actualizacion de Inventario.
  FECHA DE CREACIÓN: 14/07/2017
 * 
  ------------------------------------------------ */

class Inventario_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* LISTADO DE Kardex */
    public function lst_inventario($desde, $hasta){
      $sql = $this->db->query("SELECT k.id_kardex, k.fecha, k.documento, k.detalle, 
                                      k.tipomovimiento, k.cantidad, k.valorunitario, k.costototal,
                                      k.saldocantidad, k.saldovalorunitario,  
                                      k.saldocostototal, k.idunidadstock, 
                                      REPLACE(REPLACE(p.pro_nombre, '\r', ''), '\n', '') as pro_nombre, 
                                      p.pro_codigoauxiliar, p.pro_codigobarra,
                                      case k.tipomovimiento when 1 then 'Ingreso' else 'Egreso'
                                      end as tipo
                                FROM kardex k INNER JOIN producto p on p.pro_id = k.id_producto
                                WHERE k.fecha BETWEEN '$desde' AND '$hasta'
                                ORDER BY k.fecha, p.pro_nombre");
      $resu = $sql->result();
      return $resu;
    }
 
    public function ins_kardexingreso($idpro, $documento, $detalle, $cantidad, $valunit, $costototal, $unidad,
                                      $id_almacen){
      $usua = $this->session->userdata('usua');
      $idusuario = $usua->id_usu;
      $sqlid = $this->db->query("call kardexingreso_ins($idpro, '$documento', '$detalle', 
                                                        $cantidad, $valunit, $costototal, 
                                                        $unidad, $idusuario, $id_almacen);");
      $varobj = $sqlid->result();
      $varid = $varobj[0];
      $id = $varid->vid;

      $sqlid->next_result(); 
      $sqlid->free_result();  

      return $id;      
    }

    public function ins_kardexegreso($idpro, $documento, $detalle, $cantidad, $valunit, $costototal, $unidad,
                                     $id_almacen){
      $usua = $this->session->userdata('usua');
      $idusuario = $usua->id_usu;
      $sqlid = $this->db->query("call kardexegreso_ins($idpro, '$documento', '$detalle', 
                                                        $cantidad, $valunit, $costototal, 
                                                        $unidad, $idusuario, $id_almacen);");
      $varobj = $sqlid->result();
      $varid = $varobj[0];
      $id = $varid->vid;

      $sqlid->next_result(); 
      $sqlid->free_result();  

      return $id;      
    }
   
    /* LISTADO DE Documentos de Inventario */
    public function lst_documentoinventario($desde, $hasta){
      $sql = $this->db->query("SELECT id_documento, id_tipodoc, d.id_usu, fecha, nro_documento, descripcion,              
                                      total, estatus, fecharegistro, id_almacen, nom_usu, almacen_nombre, c.categoria
                                FROM inventariodocumento d
                                INNER JOIN usu_sistemas u on u.id_usu = d.id_usu
                                INNER JOIN almacen a on a.almacen_id = d.id_almacen
                                INNER JOIN contador c on c.id_contador = d.id_tipodoc
                                WHERE fecha BETWEEN '$desde' AND '$hasta'
                                ORDER BY fecha");
      $resu = $sql->result();
      return $resu;
    }

    /* CREAR ID PARA TABLA TEMPORAL DE MOVIMIENTO */
    public function ini_temp($idusu){
      date_default_timezone_set("America/Guayaquil");
      $verifica = $this->db->query("SELECT COUNT(*) AS valor FROM tmp_movinv WHERE id_usu = $idusu");
      $valver = $verifica->result();
      $valor = $valver[0]->valor;
      if($valor == 0){
        $fecha = date("Y-m-d");
        $sql = $this->db->query("INSERT INTO tmp_movinv (id_usu, fecha) VALUES($idusu, '$fecha')");
      }
      $sql_compra = $this->db->query("SELECT id_mov, id_usu, fecha, nro_documento, descripcion, 
                                             id_tipodoc, montototal, id_almacen, id_almdest,
                                             idcategoriacontable, idcategoriacontabledestino 
                                            FROM  tmp_movinv WHERE id_usu = $idusu");
      $resultado = $sql_compra->result();
      return $resultado[0];
    }

    public function lst_almacen(){
      $query = $this->db->query("SELECT almacen_id, almacen_nombre, almacen_direccion,
                                        almacen_responsable, almacen_descripcion, sucursal_id 
                                  FROM almacen ORDER BY almacen_nombre");
      $result = $query->result();
      return $result;
    }

    /* MOSTRAR PRODUCTOS DE LA TABLA TEMPORAL */
    public function det_movimiento($idmov){
      $sql_sel = $this->db->query(" SELECT pro.pro_id, pro.pro_codigobarra, pro.pro_codigoauxiliar, pro.pro_nombre, 
                                           ifnull(ap.existencia,0) as existencia, tcd.cantidad, tcd.id_unimed, 
                                           tcd.id, tcd.precio_compra, tcd.montototal
                                    FROM tmp_movinv_det tcd
                                    INNER JOIN tmp_movinv c ON c.id_mov = tcd.id_mov
                                    INNER JOIN producto pro ON pro.pro_id = tcd.id_pro
                                    left JOIN almapro ap ON tcd.id_pro = ap.id_pro and ap.id_alm = c.id_almacen
                                    WHERE tcd.id_mov = $idmov ORDER BY tcd.id ASC");
      $result = $sql_sel->result();
      return $result;
    }

    public function lst_tipomovimiento(){
      $usua = $this->session->userdata('usua');
      $perfil = $usua->perfil;
      if(($perfil == 1) || ($perfil == 4)){
        $query = $this->db->query("SELECT id_contador, categoria FROM contador where id_contador in (4,5,8)");
        $result = $query->result();        
      }     
      else{ 
        if($perfil <= 3){
          $query = $this->db->query("SELECT id_contador, categoria FROM contador where id_contador in (4)");
          $result = $query->result();        
        } 
      }  

      return $result;

    }

    /* OBTENER LISTADO DE PRODUCTOS PARA LA COMPRA */
    public function lst_producto(){
      $usu = $this->session->userdata('usua');
      $idusu = $usu->id_usu;
      $sql = $this->db->query("SELECT pro.pro_id, pro.pro_codigobarra, pro.pro_codigoauxiliar, 
                                      REPLACE(REPLACE(pro.pro_nombre, '\r', ''), '\n', '') as pro_nombre,  
                                      pro.pro_preciocompra, IFNULL(ap.existencia,0) as existencia, 
                                      pro.pro_idunidadmedida as id_unimed, um.descripcion, um.nombrecorto
                                FROM producto pro
                                INNER JOIN unidadmedida um ON um.id = pro.pro_idunidadmedida 
                                LEFT JOIN almapro ap ON ap.id_pro = pro.pro_id and ap.id_alm=ifnull((select id_almacen from tmp_movinv where id_usu=$idusu),0)
                                WHERE ifnull(preparado,0)=0");
      $result = $sql->result();
      return $result;      
    }

    /* ACTUALIZAR ALMACEN EN LA TABLA TEMPRAL DE COMPRA */
    public function upd_tmp_movinv($idtmp, $factura, $descripcion, $almacen, $tipomov, $categoria){
      $this->db->query("UPDATE tmp_movinv SET nro_documento = '$factura', descripcion = '$descripcion',
                                              id_almacen = $almacen, id_tipodoc = $tipomov,
                                              idcategoriacontable = $categoria
                            WHERE id_mov = $idtmp");
    }

    /* ACTUALIZAR ALMACEN EN LA TABLA TEMPRAL DE COMPRA */
    public function upd_tmp_movinvdest($idtmp, $almacendest, $categoria){
      $this->db->query("UPDATE tmp_movinv SET id_almdest = $almacendest, 
                                              idcategoriacontabledestino = $categoria
                          WHERE id_mov = $idtmp");
    }

    /* ACTUALIZAR ALMACEN EN LA TABLA TEMPRAL DE COMPRA */
    public function ins_tmpmovprod($idprod, $idtmpmov){
      $sqlprovee = $this->db->query("INSERT INTO tmp_movinv_det (id_mov,id_pro,precio_compra,existencia,cantidad,id_unimed,montototal)
                                       SELECT $idtmpmov,$idprod,p.pro_preciocompra,ifnull(a.existencia,0) as existencia,0,p.pro_idunidadmedida,0
                                         FROM producto p                                         
                                         left join almapro a on a.id_pro=p.pro_id and a.id_alm=(select t.id_almacen from tmp_movinv t where t.id_mov=$idtmpmov)                                          
                                         where p.pro_id=$idprod");
    }

    /* ELIMINA EL PRODUCTO DE LA TABLA TEMPORAL  */
    public function del_producto($iddet){
      $query = $this->db->query("DELETE FROM tmp_movinv_det WHERE id = $iddet");
    }

    /* ACTUALIZA PRODUCTO EN LA TABLA TEMPORAL */
    public function upd_producto($idmov, $iddet, $cantidad, $unidadmedida, $precio){
      $query = $this->db->query("UPDATE tmp_movinv_det SET cantidad = $cantidad, id_unimed = $unidadmedida,
                                                           precio_compra = $precio,
                                                           montototal = round($cantidad * $precio,2)
                                   WHERE id = $iddet");
      $result = $this->get_totalmovimiento($idmov);
      return $result;
    }

    /* Calcular Total de LA TABLA TEMPORAL */
    public function get_totalmovimiento($idmov){
      $sql_sel = $this->db->query(" SELECT sum(montototal) as montototal
                                    FROM tmp_movinv_det 
                                    WHERE id_mov = $idmov");
      $result = $sql_sel->result();
      $resval = 0;
      if ($result) $resval = $result[0]->montototal;
      $query = $this->db->query("UPDATE tmp_movinv SET montototal = $resval WHERE id_mov = $idmov");
      return $resval;
    }

    /* ELIMINA TODO PRODUCTO DE LA TABLA TEMPORAL  */
    public function del_todoproducto($idmov){
      $query = $this->db->query("DELETE FROM tmp_movinv_det WHERE id_mov = $idmov");
      $result = $this->get_totalmovimiento($idmov);
      return $result;
    }


    /* ELIMINA TODO PRODUCTO DE LA TABLA TEMPORAL  */
    public function guardar($idmov, $fecha){

        $newid = 0;
        $ingresoegreso = 1;
        $categoria = "";
        $tipodoc = 4;
        $idmovtrans = 0;
        $sql_sel = $this->db->query("SELECT case id_tipodoc when 4 then 1 else -1 end as ingresoegreso, categoria, id_tipodoc, id_almdest
                                       FROM tmp_movinv t
                                       INNER JOIN contador c on c.id_contador=t.id_tipodoc
                                       WHERE id_mov =$idmov");
        $result = $sql_sel->result();
        if ($result){
          $ingresoegreso = $result[0]->ingresoegreso;
          $categoria = $result[0]->categoria;
          $tipodoc = $result[0]->id_tipodoc;
          $idalmacen = $result[0]->id_almdest;
          if ($tipodoc == 8){/*Transferencia*/
            $idmovtrans = $this->generatmpentradatransfer($idmov);        
          }
        } 

        $query = $this->db->query("call inventariomovimiento_guardar($idmov, '$fecha', $tipodoc);");
        $result = $query->result();
        $query->next_result(); 
        $query->free_result();

        $newid = $result[0]->vid;



        $nro_documento = "";  
        $sql_sel = $this->db->query("SELECT nro_documento FROM inventariodocumento
                                       WHERE id_documento =$newid");
        $result = $sql_sel->result();
        if ($result){
          $nro_documento = $result[0]->nro_documento;
        } 

        $sql_sel = $this->db->query(" SELECT id_almacen, id_pro,
                                       (SELECT round(
                                          case when c.id_unimed = p.pro_idunidadmedida then 1
                                             when ifnull(fd.idunidad1,0) != 0 then fd.cantidadequivalente
                                             when ifnull(fi.idunidad1,0) != 0 then 1/fi.cantidadequivalente
                                             else 0
                                          end * c.cantidad,2)) as totalmovimiento,
                                        (select case when c.id_unimed = p.pro_idunidadmedida then 1
                                              when ifnull(fd.idunidad1,0) != 0 then 1/fd.cantidadequivalente
                                              when ifnull(fi.idunidad1,0) != 0 then fi.cantidadequivalente
                                              else 0
                                          end * c.precio_compra) as precio_compra,
                                        ifnull((select sum(existencia) from almapro where id_pro=p.pro_id),0) as existencia,
                                        p.pro_idunidadmedida 
                                        FROM tmp_movinv_det c
                                        INNER JOIN tmp_movinv t on t.id_mov = c.id_mov
                                        inner join producto p on p.pro_id = c.id_pro 
                                               left join unidadfactorconversion fd on fd.idunidad1 = c.id_unimed and fd.idunidadequivale = p.pro_idunidadmedida 
                                               left join unidadfactorconversion fi on fi.idunidad1 = p.pro_idunidadmedida and fi.idunidadequivale = c.id_unimed 
                                               WHERE c.id_mov = $idmov");
        $result = $sql_sel->result();
        foreach ($result as $rp) {
          $query = $this->db->query("UPDATE almapro set existencia = existencia +  $rp->totalmovimiento * $ingresoegreso
                                       WHERE id_alm = $rp->id_almacen and id_pro = $rp->id_pro");
          if ($ingresoegreso > 0){
            $upd_precio = $this->db->query("UPDATE producto 
                                              SET pro_preciocompra = case ($rp->existencia + $rp->totalmovimiento) when 0 then pro_preciocompra
                                                                       else round((pro_preciocompra * $rp->existencia + 
                                                                                   $rp->precio_compra * $rp->totalmovimiento) / ($rp->existencia + $rp->totalmovimiento), 6)
                                                                     end      
                                              WHERE pro_id = $rp->id_pro");            
          }

          $costototal = round($rp->precio_compra * $rp->totalmovimiento,2);    
          if ($ingresoegreso > 0){
            $this->ins_kardexingreso($rp->id_pro, $nro_documento, $categoria, $rp->totalmovimiento, 
                                     $rp->precio_compra, $costototal, $rp->pro_idunidadmedida,
                                     $rp->id_almacen);            
          } else {
            $this->ins_kardexegreso($rp->id_pro, $nro_documento, $categoria, $rp->totalmovimiento, 
                                    $rp->precio_compra, $costototal, $rp->pro_idunidadmedida,
                                    $rp->id_almacen);             
          }
        }
        
        $query = $this->db->query("DELETE FROM tmp_movinv_det WHERE id_mov = $idmov");
        $query = $this->db->query("DELETE FROM tmp_movinv WHERE id_mov = $idmov");

        if ($tipodoc == 8){
          $iddocing = $this->guardar($idmovtrans, $fecha);
          $sql = $this->db->query("INSERT INTO inventariodocumtransfer (id_doctrans, id_almacen, id_docingreso) 
                                                                values ($newid, $idalmacen, $iddocing)");
        }

        return $newid;
    }

    public function generatmpentradatransfer($idmov){
        /*generando ingreso en almacen destino*/
        $sql_sel = $this->db->query("INSERT INTO tmp_movinv (id_tipodoc, id_usu, fecha, nro_documento, 
                                                             descripcion, montototal, id_almacen, 
                                                             idcategoriacontable) 
                                       SELECT 4, id_usu, fecha, nro_documento, descripcion, montototal, 
                                              id_almdest, idcategoriacontabledestino
                                         FROM tmp_movinv WHERE id_mov = $idmov;");
        $sql_sel = $this->db->query("SELECT max(id_mov) as maxid FROM tmp_movinv");
        $tmpmaxid = 0;
        $result = $sql_sel->result();
        if ($result){
          $tmpmaxid = $result[0]->maxid;
        } 

        $sql_sel = $this->db->query("INSERT INTO tmp_movinv_det (id_mov, id_pro, precio_compra, cantidad, id_unimed, montototal)
                                       SELECT $tmpmaxid, id_pro, precio_compra, cantidad, id_unimed, montototal
                                         FROM tmp_movinv_det WHERE id_mov = $idmov;");
        return $tmpmaxid;
    }

    public function lstproalma($idalm){
      if($idalm == 0){
        $alm = "";
      }else{
        $alm = "AND al.almacen_id = ".$idalm;
      }

      $sql = $this->db->query("SELECT CONCAT(p.pro_id,'-',a.id_alm) AS pro_id, p.pro_codigobarra, p.pro_codigoauxiliar, 
                                      REPLACE(REPLACE(p.pro_nombre, '\r', ''), '\n', '') as pro_nombre,  
                                      al.almacen_nombre, p.pro_preciocompra, p.pro_precioventa, a.existencia, a.id_alm
                                FROM producto p
                                INNER JOIN almapro a ON a.id_pro = p.pro_id
                                INNER JOIN almacen al ON al.almacen_id = a.id_alm
                                WHERE p.preparado = 0 $alm");
      $resu = $sql->result();
      return $resu;
    }

    public function updproexist($idpro, $idalm, $cant){
      $pro = $this->db->query(" SELECT p.pro_id, p.pro_preciocompra, p.pro_idunidadmedida, ap.existencia 
                                FROM producto p
                                INNER JOIN almapro ap ON ap.id_pro = p.pro_id
                                WHERE p.pro_id = $idpro AND ap.id_alm=$idalm");
      $res = $pro->result();
      $respro = $res[0];
      $valunit = $respro->pro_preciocompra;
      $unidad = $respro->pro_idunidadmedida;
      $exist = $respro->existencia;

      /*print("idpro ".$idpro);    print("idalm ".$idalm);  print("cant ".$cant); print("exist ".$exist);
      die();*/

      if($cant > $exist){
        $documento = "Ajuste Ingreso";
        $detalle = "Ajuste Ingreso";
        $cantidad = $cant - $exist;
        $costototal = $valunit * $cantidad;
        $this->ins_kardexingreso($idpro, $documento, $detalle, $cantidad, $valunit, $costototal, 
                                 $unidad, $idalm);
      }else
      if($cant < $exist){
        $documento = "Ajuste Egreso";
        $detalle = "Ajuste Egreso";
        $cantidad = $exist - $cant;
        $costototal = $valunit * $cantidad;
        $this->ins_kardexegreso($idpro, $documento, $detalle, $cantidad, $valunit, $costototal, 
                                $unidad, $idalm);
      }
      if ($cant == NULL || $cant == ""){ $cant = 0; }
      $sql = $this->db->query("UPDATE almapro SET existencia = $cant WHERE id_pro = $idpro AND id_alm = $idalm");
    }

    public function encrecpdf($idmovinv){
      $sql = $this->db->query(" SELECT i.id_documento, c.categoria, CONCAT(u.nom_usu,' ',u.ape_usu) AS usuario,
                                       i.fecha, i.nro_documento, i.descripcion, a.almacen_nombre AS almaorigen,
                                       a.sucursal_id, al.almacen_nombre AS almadestino, 
                                       id.nro_documento AS docdestino, i.id_tipodoc, t.id_docingreso
                                FROM inventariodocumento i
                                INNER JOIN contador c ON c.id_contador = i.id_tipodoc
                                INNER JOIN usu_sistemas u ON u.id_usu = i.id_usu
                                INNER JOIN almacen a ON a.almacen_id = i.id_almacen
                                LEFT JOIN inventariodocumtransfer t ON t.id_doctrans = i.id_documento
                                LEFT JOIN inventariodocumento id ON id.id_documento = t.id_docingreso
                                LEFT JOIN almacen al ON al.almacen_id = id.id_almacen
                                WHERE i.id_documento = $idmovinv ");
      $resu = $sql->result();
      return $resu[0];
    }

    public function detrecpdf($idmovinv){
      $sql = $this->db->query(" SELECT id.id_documento, id.id_pro, p.pro_codigobarra, p.pro_nombre, 
                                       id.precio_compra, id.cantidad, id.id_unimed, um.descripcion, id.montototal 
                                FROM inventariodocumento_detalle id
                                INNER JOIN producto p ON p.pro_id = id.id_pro
                                INNER JOIN unidadmedida um ON um.id = id.id_unimed
                                WHERE id.id_documento = $idmovinv ");
      $resu = $sql->result();
      return $resu;
    }

    public function nro_moving(){
      $sql = $this->db->query("SELECT prefijo, valor FROM contador WHERE id_contador = 4");
      $res = $sql->result();
      $nro = $res[0]->valor+1;
      $pre = $res[0]->prefijo;
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
      $nromoving = $pre."-".$cont_nv;
      return $nromoving;
    }

    public function nro_movegr(){
      $sql = $this->db->query("SELECT prefijo, valor FROM contador WHERE id_contador = 5");
      $res = $sql->result();
      $nro = $res[0]->valor+1;
      $pre = $res[0]->prefijo;
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
      $nromovegr = $pre."-".$cont_nv;
      return $nromovegr;
    }

    public function nro_movtra(){
      $sql = $this->db->query("SELECT prefijo, valor FROM contador WHERE id_contador = 8");
      $res = $sql->result();
      $nro = $res[0]->valor+1;
      $pre = $res[0]->prefijo;
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
      $nromovtra = $pre."-".$cont_nv;
      return $nromovtra;
    }

    public function ins_seriekardexingreso($idserie, $idalmacen, $tipomovimiento, $iddocumento, $nrodocumento,
                                           $fechamovimiento, $observaciones){
      $this->db->query("INSERT INTO serie_productokardex (idserie, idalmacen, tipomovimiento, iddocumento, nrodocumento, 
                                                          fechamovimiento, observaciones)
                          VALUES($idserie, $idalmacen, $tipomovimiento, $iddocumento, '$nrodocumento', '$fechamovimiento', 
                                 '$observaciones')");
    }

    public function ins_movimtmp_auto($idusu, $idserie, $idalmacen, $tipodoc){
      date_default_timezone_set("America/Guayaquil");
      $this->db->query("DELETE FROM tmp_movinv WHERE id_usu = $idusu");
      $fecha = date("Y-m-d");
      $sql = $this->db->query("INSERT INTO tmp_movinv (id_usu, fecha, descripcion, id_almacen, id_tipodoc) 
                                 SELECT $idusu, '$fecha', numeroserie, $idalmacen, $tipodoc
                                   FROM producto_serie
                                   WHERE id_serie = $idserie");
      $sql = $this->db->query("SELECT id_mov FROM tmp_movinv WHERE id_usu = $idusu");
      $resu = $sql->result();
      $idtmpmov = 0;
      if ($resu){
        $idtmpmov = $resu[0]->id_mov;
        $this->db->query("INSERT INTO tmp_movinv_det (id_mov,id_pro,precio_compra,existencia,cantidad,id_unimed,
                                                      montototal, id_serie)
                            SELECT $idtmpmov, s.id_producto, p.pro_preciocompra, ifnull(a.existencia,0), 1,
                                   p.pro_idunidadmedida,p.pro_preciocompra, $idserie
                             FROM producto_serie s
                             INNER JOIN producto p on p.pro_id = s.id_producto
                             left join almapro a on a.id_pro=p.pro_id and a.id_alm=$idalmacen
                             where s.id_serie=$idserie");

      }
      return $idtmpmov;
    }  

    public function sel_serie_id($idserie){
      $sql = $this->db->query("SELECT id_serie, id_producto, numeroserie, descripcion, fechaingreso,
                                      id_detallecompra, id_detalleventa, id_almacen, id_estado
                                 FROM producto_serie WHERE id_serie = $idserie");
      $resu = $sql->result();
      if ($resu)
        return $resu[0];
      else
        return null;
    }

    public function sel_documentoinventario_id($id){
      $sql = $this->db->query("SELECT id_documento, id_tipodoc, id_usu, fecha, nro_documento, descripcion,              
                                      total, estatus, fecharegistro, id_almacen
                                FROM inventariodocumento 
                                WHERE id_documento = $id");
      $resu = $sql->result();
      if ($resu)
        return $resu[0];
      else
        return null;
    }

    public function ins_movimtmp_egreso_servicio($idusu, $idventa){
      $tipodoc = 5;
      $arrmov = [];
      $sql = $this->db->query("SELECT DISTINCT s.id_almacen
                                 FROM servicio_producto s
                                 INNER JOIN servicio_detalle d on d.id_detalle = s.id_detalle
                                 INNER JOIN servicio v on v.id_servicio = d.id_servicio
                                 where v.id_venta = $idventa");
      $resu = $sql->result();
      foreach ($resu as $item) {
        $almacen = $item->id_almacen;
        $this->db->query("DELETE FROM tmp_movinv WHERE id_usu = $idusu");
        $this->db->query("INSERT INTO tmp_movinv (id_usu, fecha, descripcion, id_almacen, id_tipodoc) 
                             SELECT $idusu, date(fecha_emision), 
                                    concat('EGRESO DE PRODUCTOS - ALMACEN - ',a.almacen_nombre,' - ORDEN SERVICIO - ', numero_orden), 
                                    id_almacen, $tipodoc
                               FROM servicio_producto s
                               INNER JOIN servicio_detalle d on d.id_detalle = s.id_detalle
                               INNER JOIN servicio v on v.id_servicio = d.id_servicio
                               INNER JOIN almacen a on a.almacen_id = s.id_almacen
                               WHERE v.id_venta = $idventa AND s.id_almacen = $almacen");
        $sql = $this->db->query("SELECT id_mov FROM tmp_movinv WHERE id_usu = $idusu");
        $resu = $sql->result();
        $idtmpmov = 0;
        if ($resu){
          $idtmpmov = $resu[0]->id_mov;
          $arrmov[] = $idtmpmov;
          $this->db->query("INSERT INTO tmp_movinv_det (id_mov, id_pro, precio_compra, existencia, cantidad,
                                                        id_unimed, montototal)
                              SELECT $idtmpmov, s.id_producto, s.precio, ifnull(a.existencia,0), 
                                     s.cantidad, p.pro_idunidadmedida, round(s.cantidad * s.precio, 2)
                               FROM servicio_producto s
                               INNER JOIN servicio_detalle d on d.id_detalle = s.id_detalle
                               INNER JOIN servicio v on v.id_servicio = d.id_servicio
                               INNER JOIN producto p on p.pro_id = s.id_producto
                               left join almapro a on a.id_pro = p.pro_id and a.id_alm = s.id_almacen
                               where v.id_venta = $idventa AND s.id_almacen = $almacen");

        }

      }

      return $arrmov;
    }  

    public function lst_existenciaproducto_almacen($almacen){
      $sql = $this->db->query("SELECT pro.pro_id, pro.pro_codigobarra, pro.pro_codigoauxiliar, 
                                      REPLACE(REPLACE(pro.pro_nombre, '\r', ''), '\n', '') as pro_nombre,  
                                      pro.pro_preciocompra, 
                                      CASE $almacen WHEN 0 THEN 0 ELSE IFNULL(ap.existencia,0) 
                                      END as existencia, 
                                      pro.pro_idunidadmedida as id_unimed, um.descripcion, um.nombrecorto
                                FROM producto pro
                                INNER JOIN unidadmedida um ON um.id = pro.pro_idunidadmedida 
                                LEFT JOIN almapro ap ON ap.id_pro = pro.pro_id and ap.id_alm=$almacen
                                WHERE ifnull(preparado,0)=0");
      $result = $sql->result();
      return $result;      
    }


}
