<?php

/* ------------------------------------------------
  ARCHIVO: Producto_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Producto.
  FECHA DE CREACIÓN: 14/07/2017
 * 
  ------------------------------------------------ */

class Producto_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function deducible(){
      $query = $this->db->query("SELECT id_deducible, nom_deducible FROM deducible");
      $result = $query->result();
      return $result;
    }

    public function precio(){
      $query = $this->db->query("SELECT id_precios, desc_precios,
                                        IFNULL(porciento, 0) as porciento 
                                   FROM precios p
                                   LEFT JOIN precio_compraventa pc on pc.id_precio = p.id_precios
                                   WHERE esta_precios = 'A'");
      $result = $query->result();
      return $result;
    }

    public function pro_add($codbar, $codaux, $nompro, $despro, $img, $unipro, $maxpro, $minpro, $catpro, 
                            $serpro, $ivapro, $dedpro, $estpro, $precompro, $prevenpro, $escompra, $esventa, 
                            $esvar, $maxitem, $prodesvent, $comanda, $cantidad, $cla, $esing, $espre, 
                            $idretencion, $garpro, $ubicacion, $subsidio, $catcontable, $imgpath){
      if ($catpro == NULL || $catpro == ""){ $catpro = 0; }
      if ($dedpro == NULL || $dedpro == ""){ $dedpro = 0; }
      if ($comanda == NULL || $comanda == ""){ $comanda = 0; }
      if ($cantidad == NULL || $cantidad == ""){ $cantidad = 0; }
      if ($catcontable == NULL || $catcontable == "" || $catcontable == "0"){ $catcontable = 'NULL'; }
      if ($cla == NULL || $cla == ""){ $cla = 0; }
      $this->db->query("INSERT INTO producto (pro_codigobarra, pro_codigoauxiliar, pro_nombre, pro_descripcion, pro_preciocompra,
                                               pro_precioventa, pro_maximo, pro_minimo, pro_idcategoria, pro_iddeducible, pro_grabaiva,
                                               pro_esservicio, pro_estatus, pro_imagen, pro_idunidadmedida, pro_aplicompra, pro_apliventa,
                                               habilitavariante, maxitemvariante, productodescontarventa, comanda, cantidad, idcla,
                                               ingrediente, preparado, id_cto_retencion, pro_garantia, ubicacion,
                                               subsidio, idcategoriacontable, imagen_path)
                                        VALUES('$codbar', '$codaux', '$nompro', '$despro', $precompro,
                                                $prevenpro, $maxpro ,$minpro, $catpro, $dedpro, $ivapro,
                                                $serpro, '$estpro', '$img', $unipro, $escompra, $esventa,
                                                $esvar, $maxitem, $prodesvent, $comanda, $cantidad, $cla, 
                                                $esing, $espre, $idretencion, $garpro, '$ubicacion', $subsidio,
                                                $catcontable, '$imgpath');");
      $sqlid = $this->db->query("SELECT last_insert_id() AS idpro FROM producto");
      $varid = $sqlid->result();
      $idpro = $varid[0]->idpro;
      return $idpro;
/*
      $sql = $this->db->query("INSERT INTO almapro (id_pro, id_alm, existencia, id_unimed) 
                                            VALUES ($idpro, 2, 0, $unipro)");*/
    }

    public function sel_pro_id($idpro){
      $query = $this->db->query("SELECT pro_id, pro_codigobarra, pro_codigoauxiliar, pro_nombre, pro_descripcion, 
                                        pro_preciocompra, pro_precioventa, pro_maximo, pro_minimo, pro_idcategoria, 
                                        pro_iddeducible, pro_grabaiva, pro_esservicio, pro_estatus, pro_imagen, 
                                        pro_idunidadmedida, pro_aplicompra, pro_apliventa, habilitavariante, 
                                        maxitemvariante, productodescontarventa, comanda, cantidad, idcla,
                                        ingrediente, preparado, id_cto_retencion, pro_garantia, ubicacion, 
                                        subsidio, idcategoriacontable, imagen_path,
                                        IFNULL((SELECT porciento FROM precio_compraventa 
                                                  WHERE id_precio = 0), 0) as porciento_compraventa 
                                   FROM producto WHERE pro_id = $idpro");
      $result = $query->result();
      return $result[0];
    }



    public function pro_upd($idpro, $codbar, $codaux, $nompro, $despro, $img, $unipro, $maxpro, $minpro, $catpro, 
                            $serpro, $ivapro, $dedpro, $estpro, $precompro, $prevenpro, $escompra, $esventa, 
                            $esvar, $maxitem, $prodesvent, $comanda, $cantidad, $cla, $esing, $espre, 
                            $idretencion, $garpro, $ubicacion, $subsidio, $catcontable, $imgpath){
        if ($catpro == NULL || $catpro == ""){ $catpro = 0; }
        if ($dedpro == NULL || $dedpro == ""){ $dedpro = 0; }
        if ($comanda == NULL || $comanda == ""){ $comanda = 0; }
        if ($cantidad == NULL || $cantidad == ""){ $cantidad = 0; }
        if ($cla == NULL || $cla == ""){ $cla = 0; }
        if ($catcontable == NULL || $catcontable == "" || $catcontable == "0"){ $catcontable = 'NULL'; }

        if ($img == NULL || $img == ""){ $logo = ""; }
        else {$logo = "pro_imagen = '$img',"; }
      $query = $this->db->query("UPDATE producto SET  pro_codigobarra = '$codbar', 
                                                      pro_codigoauxiliar = '$codaux', 
                                                      pro_nombre = '$nompro', 
                                                      pro_descripcion = '$despro', 
                                                      pro_preciocompra = $precompro,
                                                      pro_precioventa = $prevenpro, 
                                                      pro_maximo = $maxpro, 
                                                      pro_minimo = $minpro, 
                                                      pro_idcategoria = $catpro, 
                                                      pro_iddeducible = $dedpro, 
                                                      pro_grabaiva = $ivapro,
                                                      pro_esservicio = $serpro, 
                                                      pro_estatus = '$estpro', 
                                                      ".$logo." 
                                                      pro_idunidadmedida = $unipro,
                                                      habilitavariante = $esvar, 
                                                      maxitemvariante = $maxitem, 
                                                      productodescontarventa = $prodesvent,
                                                      pro_aplicompra = $escompra, 
                                                      pro_apliventa = $esventa,
                                                      comanda = $comanda,
                                                      cantidad = $cantidad,
                                                      idcla = $cla,
                                                      ingrediente = $esing, 
                                                      preparado = case $espre when 0 then 0 else
                                                                    case when (Select count(*) from producto_ingrediente where id_pro=$idpro) > 0 then 1 else 0 end
                                                                  end,
                                                      id_cto_retencion = $idretencion,
                                                      pro_garantia = $garpro,
                                                      ubicacion = '$ubicacion',
                                                      subsidio = $subsidio,
                                                      idcategoriacontable = $catcontable,
                                                      imagen_path = '$imgpath'
                                                WHERE pro_id = $idpro");

    }

    public function prepro_upd($idpro, $arra){
      // $retorno = array();
      foreach ($arra as $ar) {
        list($campo,$valor)=explode("-",$ar);
        /* CONSULTAR SI EXISTE EL REGISTRO DEL PRECIO */
        $busc = $this->db->query("SELECT COUNT(*) as nro FROM prepro WHERE pro_id = $idpro AND id_precios = $campo");
        $result = $busc->result();
        $val = $result[0];
      //  $retorno[$campo] = $campo."-".$val->nro;

        if($val->nro > 0){
        /* SI EXISTE ACTUALIZA EL PRECIO */  
          $upd = $this->db->query("UPDATE prepro SET monto = $valor WHERE pro_id = $idpro AND id_precios = $campo");            
        }else{
        /* SI NO EXISTE INSERTAR EL REGISTRO EN LA TABLA */  
          if($idpro == 0){
            /* SI EL ID ES = 0 HAY QUE CONSULTAR EL ULTIMO REGISTRO QUE SE INTRODUJO EN LA TABLA PRODUCTO */
            $ult_id = $this->db->query("SELECT MAX(pro_id) as id FROM producto");
            $result = $ult_id->result();  
            $proid = $result[0]->id; 
            $add = $this->db->query("INSERT INTO prepro (pro_id, id_precios, monto) VALUES ($proid, $campo, $valor)");           

          }else{

            $add = $this->db->query("INSERT INTO prepro (pro_id, id_precios, monto) VALUES ($idpro, $campo, $valor)");
          }
                     
        }
      }
    }

    public function sel_pre($idpro){
      $query = $this->db->query("SELECT id_precios, monto FROM prepro WHERE pro_id = $idpro");
      $result = $query->result();
      return $result;
    }  

    /* VALIDAR SI EXISTE EL CODIGO DE BARRA */
    public function valida_codbar($codbar){
      $query = $this->db->query("SELECT COUNT(pro_codigobarra) as codbar FROM producto WHERE pro_codigobarra = '$codbar' ");
      $result = $query->result();  
      $val = $result[0]->codbar;
      return $val;
    }

    /* VALIDAR SI EXISTE EL CODIGO AUXILIAR */
    public function valida_codaux($codaux){
      $query = $this->db->query("SELECT COUNT(pro_codigoauxiliar) as codaux FROM producto WHERE pro_codigoauxiliar = '$codaux' ");
      $result = $query->result();  
      $val = $result[0]->codaux;
      return $val;
    }

    /* VALIDAR SI EXISTE EL NOMBRE DEL PRODUCTO */
    public function valida_nompro($nompro){
      $query = $this->db->query("SELECT COUNT(pro_nombre) AS pronom FROM producto WHERE pro_nombre = '$nompro' ");
      $result = $query->result();  
      $val = $result[0]->pronom;
      return $val;
    }

    /* ELIMINAR EL PRODUCTO */
    public function pro_del($idpro){
      $query = $this->db->query("DELETE FROM producto WHERE pro_id = $idpro");
      $del = $this->db->query("DELETE FROM prepro WHERE pro_id = $idpro");
    }

    /* PRODUCTOS QUE SEAN SOLO DE COMPRA */
    public function procomp(){
      $query = $this->db->query("SELECT pro_id, pro_nombre FROM producto WHERE pro_aplicompra = 1");
      $result = $query->result();  
      return $result;
    }

    /* LISTADO DE VARIANTES DE PRODUCTO POR ID */
    public function provar($idpro){
      $query = $this->db->query("SELECT descripcion FROM productovariante WHERE id_producto = $idpro");
      $sql = $query->result();
      return $sql;
    }

    /* SELECCIONA EL PRODUCTO VARIANTE POR ID */
    public function sel_provar_id($idprovar, $idpro){
      $query = $this->db->query("SELECT id_variante, descripcion, id_producto 
                                 FROM productovariante 
                                 WHERE id_variante = $idprovar 
                                 AND id_producto = $idpro");
      $sql = $query->result();
      return $sql[0];
    }

    /* SE ACTUALIZA EL REGISTRO DEL PRODUCTO VARIANTE */
    public function provar_upd($idpro, $idprovar, $desc_var){
      $query = $this->db->query(" UPDATE productovariante
                                  SET descripcion = '$desc_var'
                                  WHERE id_variante = $idprovar AND id_producto = $idpro");
    }

    public function provar_add($idpro, $desc_var){
      $query = $this->db->query("INSERT INTO productovariante (descripcion, id_producto) VALUES ('$desc_var', $idpro)");
    }

    public function provar_del($idpro, $idprovar){
      $query = $this->db->query("DELETE FROM productovariante WHERE id_variante = $idprovar AND id_producto = $idpro");
    }

    /* AÑADIR VARIANTE DE PRODUCTOS DESDE ARRAYS */
    public function add_var($idpro, $arravar){
      if($idpro == 0){
        /* SE SELECCIONA EL ID INSERTADO */
        $ult_id = $this->db->query("SELECT MAX(pro_id) as id FROM producto");
        $result = $ult_id->result();  
        $proid = $result[0]->id;
      }else{
        $proid = $idpro;
      }
      /* SE ELIMINAN TODAS LAS VARIANTES DE PRODUCTOS PARA REINGRESARLAS */  
      $delvar = $this->db->query("DELETE FROM productovariante WHERE id_producto = $proid");
      /* SE RECORRE EL ARREGLO PARA INSERTAR LA INFORMACION */
      if (empty($arravar)) { }else{
        foreach ($arravar as $ar=>$desc) {
          $sqlcont = $this->db->query("SELECT COUNT(*) AS nro FROM productovariante WHERE id_producto = $proid and descripcion = '$desc'");
          $sqlval = $sqlcont->result();
          $ver = $sqlval[0]->nro;
          if($ver != 0){}
          else{
            $addvar = $this->db->query("INSERT INTO productovariante (descripcion, id_producto) VALUES ('$desc', $proid)");            
          }

        }
      }
    }

    /* LISTADO DE COMANDAS */
    public function comanda(){
      $sql = $this->db->query("SELECT id_comanda, nom_comanda, impresora FROM comanda");
      $resu = $sql->result();
      return $resu;
    }

    /* LISTADO DE PRODUCTOS */
    public function lista_pro(){
	    $sql = $this->db->query("SELECT pro_id, pro_codigobarra, pro_codigoauxiliar, 
	                               pro_nombre, pro_descripcion, pro_preciocompra, pro_precioventa,
                                 (select sum(existencia) from almapro a where a.id_pro=producto.pro_id) as pro_existencia 
                              FROM producto");
	    $resu = $sql->result();
	    return $resu;
    }

  /* NOMBRE DE LA CLASIFICACION */    
  public function clasificacion(){
    $sql = $this->db->query("SELECT id_cla, nom_cla FROM clasificacion");
    $resu = $sql->result();
    return $resu;
  }

  /* LISTADO DE PRODUCTOS PARA MOSTRARLOS EN INGREDIENTES */
  public function lstpro_comp($idpro){
  /*  if ($idpro == 0){
      $usua = $this->session->userdata('usua');
      $idpro = $usua->id_usu * (-1);
    }          */
    $sql = $this->db->query(" SELECT  pro_id as id, pro_nombre as producto
                              FROM producto 
                              WHERE ingrediente = 1 and 
                                    not pro_id in (select id_proing from producto_ingrediente where id_pro = $idpro) 
                              ORDER BY pro_nombre ASC ");
    $resu = $sql->result();
    return $resu;
  }  

  /* INSERTAR PRODUCTOS INGREDIENTES */
  public function add_ing($idpro, $idproing){
   /* if ($idpro == 0){
      $usua = $this->session->userdata('usua');
      $idpro = $usua->id_usu * (-1);
    }*/  
    $sql = $this->db->query(" SELECT pro_id, pro_idunidadmedida FROM producto WHERE pro_id = $idproing ");
    $resu = $sql->result();
    $proing = $resu[0];
/*
    $sql = $this->db->query("SELECT count(*) as cant FROM producto_ingrediente 
                              WHERE id_pro = $idpro and id_proing=$proing->pro_id");
    $resu = $sql->result();
    $prod = $resu[0];
    if ($prod->cant == 0){*/
      
      $upd = $this->db->query(" INSERT INTO producto_ingrediente (id_pro, id_proing, unimed, cantidad) 
                                                        VALUES ($idpro, $proing->pro_id, $proing->pro_idunidadmedida, 0) ");

    /*}*/
    $sql = $this->db->query(" SELECT pi.id_pro, pi.id_proing, p.pro_nombre, pi.unimed, pi.cantidad, p.pro_preciocompra
                              FROM producto_ingrediente pi
                              INNER JOIN producto p ON p.pro_id = pi.id_proing
                              WHERE id_pro = $idpro ");
    $resu = $sql->result();

    return $resu;
  } 

  public function costo_ing($idpro){
   /* if ($idpro == 0){
      $usua = $this->session->userdata('usua');
      $idpro = $usua->id_usu * (-1);
    }*/  
    $sql = $this->db->query("SELECT sum(p.pro_preciocompra * i.cantidad) as costototal 
                                FROM producto p
                                inner join producto_ingrediente i on i.id_proing = p.pro_id
                                WHERE i.id_pro = $idpro");
    $resu = $sql->result();   
    return $resu[0];
  } 

  /* INSERTAR PRODUCTOS INGREDIENTES */
  public function upd_ing($idpro, $idproing, $unidad, $cantidad){
    /*if ($idpro == 0){
      $usua = $this->session->userdata('usua');
      $idpro = $usua->id_usu * (-1);
    } */ 
    $upd = $this->db->query("UPDATE producto_ingrediente 
                               SET unimed = $unidad, cantidad = $cantidad
                               WHERE id_pro = $idpro AND id_proing = $idproing");
  } 

  /* LISTADO DE PRODUCTOS INGREDIENTES CARGADOS A OTRO PRODUCTO */
  public function lstpro_ing($idpro){
    /*if ($idpro == 0){
      $usua = $this->session->userdata('usua');
      $idpro = $usua->id_usu * (-1);
    } */ 
    $sql = $this->db->query(" SELECT pi.id_pro, pi.id_proing, p.pro_nombre, pi.unimed, pi.cantidad, p.pro_preciocompra
                              FROM producto_ingrediente pi
                              INNER JOIN producto p ON p.pro_id = pi.id_proing
                              WHERE id_pro = $idpro ");
    $resu = $sql->result();
    return $resu;
  } 

  /* ELIMINAR PRODUCTOS INGREDIENTES CARGADOS A OTRO PRODUCTO */
  public function del_ing($idpro, $idproing){
    /*if ($idpro == 0){
      $usua = $this->session->userdata('usua');
      $idpro = $usua->id_usu * (-1);
    } */ 
    $sql = $this->db->query(" DELETE FROM producto_ingrediente WHERE id_pro = $idpro AND id_proing = $idproing");
  } 

  /* PARAMETRO PRECIOS */
  public function tipo_precio(){
    $sql = $this->db->query("SELECT valor FROM parametros WHERE id = 9");
    $resu = $sql->result();
    $res = $resu[0]->valor;
    return $res;
  }

  /* chequear si es posible cambiar unidad */
  public function val_cambiounidadmedida($idpro, $idunidad){
    $sql = $this->db->query("SELECT count(*) as valor FROM almapro WHERE id_pro = $idpro and existencia > 0");
    $resu = $sql->result();
    if ($resu[0]->valor > 0){
      $sql = $this->db->query("SELECT cantidadequivalente FROM producto p 
                                inner join unidadfactorconversion fd on fd.idunidad1 = $idunidad and fd.idunidadequivale = p.pro_idunidadmedida 
                                WHERE p.pro_id = $idpro
                               UNION
                               SELECT cantidadequivalente FROM producto p 
                                inner join unidadfactorconversion fi on fi.idunidad1 = p.pro_idunidadmedida and fi.idunidadequivale = $idunidad 
                                WHERE p.pro_id = $idpro");
      $resu = $sql->result();
      if ($resu != NULL){
        if ($resu[0]->cantidadequivalente > 0) $res = 1; else $res = 0;
      } else {
        $res = 0;
      }
    } else {
      $res = 1;      
    }

    return $res;
  }

  /* Lista existencia en almacen */
  public function lst_almapro($idpro){
    $sql = $this->db->query("SELECT * FROM almapro WHERE id_pro = $idpro");
    $resu = $sql->result();
    return $resu;
  }


  /* actualiza existencia en almacen */
  public function upd_almapro($idpro, $idalm, $nuevacantidad, $idunidad){
    if ($nuevacantidad == NULL || $nuevacantidad == ""){ $nuevacantidad = 0; }
    $sql = $this->db->query("UPDATE almapro SET 
                               existencia = $nuevacantidad,
                               id_unimed = $idunidad
                               WHERE id_pro = $idpro and id_alm = $idalm");
  }

  /* obtener cantidad equivalente en otra unidad */
  public function get_cantidadequivalente($idunidad1, $idunidad2){
      $res = 0;
      $sql = $this->db->query("SELECT cantidadequivalente FROM unidadfactorconversion 
                                WHERE idunidad1 = $idunidad1 and idunidadequivale = $idunidad2"); 
      $resu = $sql->result();
      if ($resu != NULL){
        if ($resu[0]->cantidadequivalente != NULL) $res = $resu[0]->cantidadequivalente;
      } else {
        $sql = $this->db->query("SELECT cantidadequivalente FROM unidadfactorconversion 
                                  WHERE idunidad1 = $idunidad2 and idunidadequivale = $idunidad1"); 
        $resu = $sql->result();
        if (($resu != NULL) and ($resu != 0)) {
          if ($resu[0]->cantidadequivalente != NULL) $res = 1/$resu[0]->cantidadequivalente;
        } 
      }

      return $res;
  }

  public function reportepro(){
    $rpt = $this->db->query("SELECT p.pro_id, p.pro_codigobarra, p.pro_codigoauxiliar, p.pro_nombre, 
                                    p.pro_descripcion, p.pro_preciocompra, p.pro_precioventa,
                                    cat.cat_descripcion, pro_estatus, u.descripcion, cla.nom_cla, com.nom_comanda, 
                                    IFNULL((SELECT SUM(alm.existencia) FROM almapro alm WHERE alm.id_pro = p.pro_id),0) as existencia,
                                   (CASE p.pro_grabaiva WHEN 1 THEN 'SI' ELSE 'NO' END) AS iva,
                                   (CASE p.preparado WHEN 1 THEN 'SI' ELSE 'NO' END) AS preparado,
                                   (CASE p.ingrediente WHEN 1 THEN 'SI' ELSE 'NO' END) AS ingrediente
                              FROM producto p
                              LEFT JOIN categorias cat ON cat.cat_id = p.pro_idcategoria
                              LEFT JOIN unidadmedida u ON u.id = p.pro_idunidadmedida
                              LEFT JOIN clasificacion cla ON cla.id_cla = p.idcla
                              LEFT JOIN comanda com ON com.id_comanda = p.comanda
                              ORDER BY p.pro_nombre ASC ");
    $resu = $rpt->result();
    return $resu;
  }

  public function agotado(){
    $sql = $this->db->query(" SELECT p.pro_id, p.pro_codigobarra, p.pro_nombre, p.pro_maximo, p.pro_minimo, ap.existencia  
                              FROM producto p
                              INNER JOIN almapro ap ON ap.id_pro = p.pro_id
                              WHERE ap.existencia <= p.pro_minimo
                              AND p.preparado = 0");
    $resu = $sql->result();
    return $resu;
  }

  public function lstproprecios(){
    $sql = $this->db->query("SELECT pc.pro_id, pc.id_precios, pc.desc_precios, IFNULL(pp.monto, 0) as monto 
                               FROM (SELECT pro_id, id_precios, desc_precios FROM producto, precios) as pc 
                               LEFT JOIN prepro pp ON pp.id_precios = pc.id_precios AND 
                                                      pp.pro_id = pc.pro_id");
    $resu = $sql->result();
    return $resu;
  }

  public function lstprepro(){
    $usua = $this->session->userdata('usua');
    $idusu = $usua->id_usu;    
    $sql = $this->db->query(" SELECT up.idpre, ifnull(p.desc_precios, 'TIENDA') AS precio, up.estatus 
                              FROM usuprecio up
                              LEFT JOIN precios p ON p.id_precios = up.idpre
                              WHERE up.idusu = $idusu AND up.estatus = 1 ORDER BY up.idpre ASC");
    $resu = $sql->result();
    return $resu;
  }

  public function lstprod(){
    $sql = $this->db->query(" SELECT pro.pro_id, pro.pro_codigobarra, pro.pro_codigoauxiliar, pro.pro_nombre, pro_precioventa
                              FROM producto pro");
    $resu = $sql->result();
    return $resu;
  }

  public function precpro(){
    $this->iniprecpro();
    $sql = $this->db->query("SELECT pro_id, id_precios, monto FROM prepro");
    $resu = $sql->result();
    return $resu;
  }

  public function updprepro($idpro, $idpre, $monto){
    if($idpre == 0){
      $sql = $this->db->query("UPDATE producto SET pro_precioventa = $monto WHERE pro_id = $idpro");
    }else{
      $sql = $this->db->query("UPDATE prepro SET monto = $monto WHERE pro_id = $idpro AND id_precios = $idpre");  
    }
    
  }

  public function iniprecpro(){
    $this->db->query("insert into prepro (pro_id, id_precios, monto)
                        select p.pro_id, r.id_precios, 0 from producto p, precios r
                          where not exists (select * from prepro pp where pp.pro_id=p.pro_id and pp.id_precios=r.id_precios)");
  }

  public function get_iva(){
    $iva = $this->db->query("SELECT valor FROM parametros WHERE id = 1");
    $res = $iva->result();
    return $res[0]->valor;
  }

  public function selexistencia($idpro){
    $sql = $this->db->query(" SELECT a.almacen_id, a.almacen_nombre, IFNULL(al.existencia,0) as existencia
                              FROM almacen a
                              LEFT JOIN almapro al ON al.id_alm = a.almacen_id AND al.id_pro = $idpro");
    $res = $sql->result();
    return $res;
  }

  public function add_alma($idpro, $alma){
    $ObjInv = &get_instance();
    $ObjInv->load->model("Inventario_model");

    foreach ($alma as $al) {
      list($alm,$valor)=explode("-",$al);

      $busc = $this->db->query("SELECT COUNT(*) AS nro FROM almapro WHERE id_pro = $idpro AND id_alm = $alm ");
      $result = $busc->result();
      $val = $result[0]->nro;

      if($val == 0){
        $this->db->query("INSERT INTO almapro (id_pro, id_alm, existencia, id_unimed) 
                            SELECT $idpro, $alm, $valor, pro_idunidadmedida
                              FROM producto WHERE pro_id = $idpro"); 
        //$this->db->query("INSERT INTO almapro (id_pro, id_alm, existencia, id_unimed) VALUES ($idpro, $alm, $valor, 0)"); 
      }
/*      if($val > 0){
        $upd = $this->db->query("UPDATE almapro SET existencia = $valor WHERE id_pro = $idpro AND id_alm = $alm"); 
      }else{
        $this->db->query("INSERT INTO almapro (id_pro, id_alm, existencia, id_unimed) VALUES ($idpro, $alm, $valor, 1)"); 
      }*/

      $ObjInv->Inventario_model->updproexist($idpro, $alm, $valor);

    }
  }

  /* BUSQUEDA POR NOMBRE */
  public function valida_nombre($nompro){
    $query = $this->db->query("SELECT concat(pro_codigobarra,' - ',pro_nombre) as pro_nombre 
                                 FROM producto 
                                 WHERE pro_nombre like '%$nompro%' OR 
                                       pro_codigobarra like '%$nompro%' OR 
                                       pro_codigoauxiliar like '%$nompro%' ");
    $result = $query->result();
    return $result;
  }

  public function sel_pro_nombre($nom){
    $query = $this->db->query("SELECT pro_id, pro_nombre, pro_codigobarra, pro_codigoauxiliar
                                 FROM producto
                                 WHERE pro_nombre = '$nom' ");
    $result = $query->result();
    if($result){
      return $result[0];
    }else{
      return NULL;
    }
  }

  public function lst_producto_series($idproducto, $serie){
    $sql = $this->db->query("SELECT s.id_serie, s.numeroserie, s.descripcion, s.fechaingreso, 
                                    s.id_almacen, s.id_estado, t.estado, a.almacen_nombre, p.pro_nombre
                              FROM producto_serie s
                              INNER JOIN producto p on p.pro_id = s.id_producto
                              INNER JOIN almacen a on a.almacen_id = s.id_almacen
                              INNER JOIN serie_tipomovimiento t on t.id = s.id_estado
                              WHERE s.id_producto = $idproducto AND
                                    s.numeroserie like '%$serie%'
                              ORDER BY numeroserie");
    $resu = $sql->result();
    return $resu;
  }

  public function lst_estadoserie(){
    $sql = $this->db->query("SELECT id, movimiento, estado FROM serie_tipomovimiento");
    $resu = $sql->result();
    return $resu;
  }

  public function producto_serie_actualizarestado($id, $idestado){
    $this->db->query("UPDATE producto_serie SET id_estado=$idestado WHERE id_serie = $id");
    $sql = $this->db->query("SELECT estado FROM serie_tipomovimiento WHERE id=$idestado");
    $resu = $sql->result();
    return $resu[0]->estado;
  }


  public function sel_pro_codigos($codigo){
      $query = $this->db->query("SELECT pro_id, pro_codigobarra, pro_codigoauxiliar, pro_nombre, pro_descripcion, pro_preciocompra,
                                        pro_precioventa, pro_maximo, pro_minimo, pro_idcategoria, pro_iddeducible, pro_grabaiva,
                                        pro_esservicio, pro_estatus, pro_imagen, pro_idunidadmedida, pro_aplicompra, pro_apliventa,
                                        habilitavariante, maxitemvariante, productodescontarventa, comanda, cantidad, idcla,
                                        ingrediente, preparado, id_cto_retencion, pro_garantia, ubicacion, subsidio, 
                                        idcategoriacontable, imagen_path 
                                   FROM producto WHERE pro_codigobarra = '$codigo' OR pro_codigoauxiliar = '$codigo'");
      $result = $query->result();
      if (count($result) > 0)
        return $result[0];
      else
        return null;
  }
  
  public function get_producto($id) {
    $query = $this->db->get_where('producto', ['pro_id' => $id]);
    return $query->row();
}

}
