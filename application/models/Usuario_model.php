<?php

/* ------------------------------------------------
  ARCHIVO: usuario_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a usuarios del sistema.
  FECHA DE CREACIÓN: 30/06/2017
 * 
  ------------------------------------------------ */

class Usuario_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $query = $this->db->query("SET time_zone = '-5:00';");
        $this->chequea_usuario_adm();
    }

    /* INSERTA EL REGISTRO DEL USUARIO*/
    public function usu_add($nom, $ape, $nac, $ide, $fec, $tlf, $ema, $dir, $usu, $pwd, $est, $fot, $perfil, $idmesero, $idpunto){
        if (strtoupper($usu) == 'FACTUFÁCIL') return 0;
        $this->db->query("INSERT INTO usu_sistemas (nom_usu, ape_usu, nac_usu, ide_usu, fec_usu, tlf_usu, ema_usu, dir_usu, log_usu, pwd_usu, est_usu, fot_usu, perfil, id_mesero, id_punto, ultimoacceso)
                                   VALUES('$nom', '$ape', '$nac', '$ide', '$fec', '$tlf', '$ema', '$dir', '$usu', MD5(sha1('$pwd')), '$est', '$fot', $perfil, $idmesero, $idpunto, now())");
        $sqlid = $this->db->query("SELECT last_insert_id() AS id FROM usu_sistemas");
        $varid = $sqlid->result();
        if ($varid)
          return $varid[0]->id;
        else
          return 0;
    }

  /* OBTENER TODOS LOS DATOS DEL USUARIO A TRAVÉS DE SU ID */
    public function usu_get($id_usu) {
        $query = $this->db->query(" SELECT * FROM usu_sistemas WHERE id_usu = $id_usu;");
        $result = $query->result();
        return $result[0];
    }

  /* OBTENER TODOS LOS DATOS DE LA FOTO DEL USUARIO A TRAVÉS DE SU ID */
    public function usu_get_fot($id_usu) {
        $query = $this->db->query(" SELECT fot_usu from usu_sistemas where id_usu = $id_usu;");
        $result = $query->result();
        return $result[0];
    }


    /* ACTUALIZA EL REGISTRO DEL USUARIO*/
    public function usu_upd($id, $nom, $ape, $nac, $ide, $fec, $tlf, $ema, $dir, $usu, $pwd, $est, $fot, $perfil, $idmesero, $idpunto){
        if (strtoupper($usu) == 'FACTUFÁCIL') return 0;
        if ($fot === NULL ){ $foto = "fot_usu = NULL,"; }
        if ($fot === ""){ $foto = ""; }
        else {$foto = "fot_usu = '$fot',"; }
        $query = $this->db->query("UPDATE usu_sistemas 
                                      SET nom_usu = '$nom', 
                                          ape_usu = '$ape', 
                                          nac_usu = '$nac', 
                                          ide_usu = '$ide', 
                                          fec_usu = '$fec', 
                                          tlf_usu = '$tlf', 
                                          ema_usu = '$ema', 
                                          dir_usu = '$dir', 
                                          log_usu = '$usu', 
                                          pwd_usu = MD5(sha1('$pwd')),
                                          ".$foto."
                                          est_usu = '$est',
                                          perfil = $perfil,
                                          id_mesero = $idmesero,
                                          id_punto = $idpunto                                          
                                    WHERE id_usu = $id");
                                    
    }

    public function prepro_usu($idusu, $arra){
      $this->db->query("DELETE FROM usuprecio WHERE idusu = $idusu");
      foreach ($arra as $ar) {
        list($campo,$valor)=explode("-",$ar);
        $this->db->query("INSERT INTO usuprecio (idusu, idpre, estatus) VALUES ($idusu, $campo, $valor)");
      }
    }    

    public function prepro_usu00($idusu, $arra){
      // $retorno = array();
      foreach ($arra as $ar) {
        list($campo,$valor)=explode("-",$ar);
        /* CONSULTAR SI EXISTE EL REGISTRO DEL PRECIO */
        $busc = $this->db->query("SELECT COUNT(*) as nro FROM usuprecio WHERE idusu = $idusu AND idpre = $campo");
        $result = $busc->result();
        $val = $result[0];
      //  $retorno[$campo] = $campo."-".$val->nro;

        if($result){/*($val->nro > 0)*/
        /* SI EXISTE ACTUALIZA EL PRECIO */  
          $upd = $this->db->query("UPDATE usuprecio SET estatus = $valor WHERE idusu = $idusu AND idpre = $campo");            
        }else{
        /* SI NO EXISTE INSERTAR EL REGISTRO EN LA TABLA */  
          if($idusu == 0){
            /* SI EL ID ES = 0 HAY QUE CONSULTAR EL ULTIMO REGISTRO QUE SE INTRODUJO EN LA TABLA  */
            $ult_id = $this->db->query("SELECT MAX(id_usu) as id FROM usu_sistemas");
            $result = $ult_id->result();  
            $usuid = $result[0]->id; 
            $add = $this->db->query("INSERT INTO usuprecio (idusu, idpre, estatus) VALUES ($usuid, $campo, $valor)");           

          }else{

            $add = $this->db->query("INSERT INTO usuprecio (idusu, idpre, estatus) VALUES ($idusu, $campo, $valor)");
          }
                     
        }
      }
    }    
    //OBTENER EL ID DEL USUARIO
    public function usua_get_id($log_usu, $pas_usu) {
      $sql = $this->db->query("CALL login('$log_usu', '$pas_usu')");
      $resu = $sql->result();
      $sql->next_result(); 
      $sql->free_result(); 
      return $resu[0];      
    }


  //OBTENER TODOS LOS DATOS DEL USUARIO A TRAVÉS DE SU ID

    public function usua_get_tod_log($id_usu) {
        $query = $this->db->query(" SELECT id_usu, nom_usu, ape_usu, ema_usu, log_usu, perfil
                                    FROM usu_sistemas
                                    WHERE id_usu = $id_usu");
        $result = $query->result();
        if (count($result) >= 1) {
            return $result[0];
        } else {
            return $result;
        }
    }

    //OBTENER LOS DATOS DE UN USUARIO A TRAVÉS DE SU ID (ESTA FUNCIÓN SE UTILIZA DESDE AUTH_LIBRARY)

    public function usua_get($id_usu) {
        $query = $this->db->query(" SELECT id_usu, nom_usu, ape_usu, log_usu, ide_usu, perfil
                                    FROM usu_sistemas
                                    WHERE id_usu = $id_usu");
        $result = $query->result();
        return $result[0];
    }

    /* OBTENER PERFIL DE ACCESO DEL USUARIO */
/*    public function perfil($id_usu){
        $query = $this->db->query(" SELECT m.desc_mod_det, a.evento, a.accion 
                                    FROM modulos_detalles m
                                    INNER JOIN acceso a ON a.id_mod_det = m.id_mod_det
                                    WHERE id_usu = $id_usu ");
    }*/

    /* Actualizar los accesos para Categorias */
    public function rol_usu_cat($id_usu, $catv, $cata, $catu, $cate, $catr){
      $query = $this->db->query("UPDATE acceso SET accion = $catv WHERE id_usu = $id_usu and id_mod_det = 1 and evento = 'ver'");
      $query = $this->db->query("UPDATE acceso SET accion = $cata WHERE id_usu = $id_usu and id_mod_det = 1 and evento = 'agrega'");
      $query = $this->db->query("UPDATE acceso SET accion = $catu WHERE id_usu = $id_usu and id_mod_det = 1 and evento = 'modifica'");
      $query = $this->db->query("UPDATE acceso SET accion = $cate WHERE id_usu = $id_usu and id_mod_det = 1 and evento = 'elimina'");
      $query = $this->db->query("UPDATE acceso SET accion = $catr WHERE id_usu = $id_usu and id_mod_det = 1 and evento = 'reporte'");

    }

    /* AGREGAR PERFIL DE USUARIO */
    public function perfil_lst(){
      $sql = $this->db->query("SELECT id_perfil, nom_perfil FROM perfil");
      $resu = $sql->result();
      return $resu;
    }

    /* LISTADO DE USUARIOS */
    public function lst_usu(){
      $sql = $this->db->query("SELECT id_usu, ide_usu, nom_usu, ape_usu, log_usu, est_usu 
                               FROM usu_sistemas WHERE UPPER(log_usu) != 'FACTUFÁCIL'");
      $resu = $sql->result();
      return $resu;
    }

    /* LISTADO DE MESEROS */
    public function meseros(){
      $sql = $this->db->query("SELECT id_mesero, nom_mesero FROM mesero WHERE estatus_mesero = 'A'");
      $resu = $sql->result();
      return $resu;
    }    

    /* LISTADO DE MESEROS */
    public function usua_del($id_usu){
      $sql = $this->db->query("DELETE FROM usu_sistemas WHERE id_usu = $id_usu");
      $sql = $this->db->query("DELETE FROM usuprecio WHERE idusu = $id_usu");
    } 

    /* LISTADO DE PUNTOS DE VENTAS */
    public function lst_puntos(){
      $punto = $this->db->query(" SELECT m.id_mesa, m.nom_mesa, a.nom_area FROM mesa m
                                  INNER JOIN area a ON a.id_area = m.id_area");
      $resu = $punto->result();
      return $resu;
    }
    public function usu_precios($id_usu){
      $sql = $this->db->query(" SELECT p.id_precios, p.desc_precios, IFNULL(u.estatus, 0) AS estatus
                                FROM (SELECT * FROM precios UNION SELECT 0, 'Precio de Venta', 'A', '#ff0000' ) p
                                LEFT JOIN usuprecio u ON u.idpre = p.id_precios AND u.idusu = $id_usu
                                WHERE p.esta_precios = 'A' ");
      $resu = $sql->result();
      return $resu;
    }

  /* ACTUALIZAR FECHA Y HORA DE ULTIMO ACCESO DE USUARIO */
    public function usu_upd_acceso() {
        $usua = $this->session->userdata('usua');
        $id = $usua->id_usu;
        $query = $this->db->query("CALL usuario_upd_acceso($id);");
    }

    public function lst_sucursal($id_usu){
      $sql = $this->db->query("SELECT s.id_sucursal, s.nom_sucursal, 
                                (SELECT COUNT(*) FROM permiso_sucursal WHERE id_usuario = $id_usu AND id_sucursal = s.id_sucursal) AS estatus
                                FROM sucursal s");
      $res = $sql->result();
      return $res;
    }

    public function lst_almacen($id_usu){
      $sql = $this->db->query("SELECT a.almacen_id, a.almacen_nombre, 
                                (SELECT COUNT(*) FROM permiso_almacen WHERE id_usuario = $id_usu AND id_almacen = a.almacen_id) AS estatus
                                FROM almacen a");
      $res = $sql->result();
      return $res;
    }

    public function lst_caja_efectivo($id_usu){
      $sql = $this->db->query(" SELECT c.id_caja, c.nom_caja, 
                                (SELECT COUNT(*) FROM permiso_cajaefectivo WHERE id_usuario = $id_usu AND id_caja = c.id_caja) AS estatus
                              FROM caja_efectivo c");
      $res = $sql->result();
      return $res;
    }

    public function sucursal_usu($idusu, $sucarr){
      $this->db->query("DELETE FROM permiso_sucursal WHERE id_usuario = $idusu");
      foreach ($sucarr as $ar) {
        list($campo,$valor)=explode("-",$ar);
        if($valor != 0){
          $this->db->query("INSERT INTO permiso_sucursal (id_usuario, id_sucursal) VALUES ($idusu, $campo)");
        }
      }
    } 

    public function almacen_usu($idusu, $almarr){
      $this->db->query("DELETE FROM permiso_almacen WHERE id_usuario = $idusu");
      foreach ($almarr as $ar) {
        list($campo,$valor)=explode("-",$ar);
        if($valor != 0){
          $this->db->query("INSERT INTO permiso_almacen (id_usuario, id_almacen) VALUES ($idusu, $campo)");
        }
      }
    } 

    public function caja_usu($idusu, $cajarr){
      $this->db->query("DELETE FROM permiso_cajaefectivo WHERE id_usuario = $idusu");
      foreach ($cajarr as $ar) {
        list($campo,$valor)=explode("-",$ar);
        if($valor != 0){
          $this->db->query("INSERT INTO permiso_cajaefectivo (id_usuario, id_caja) VALUES ($idusu, $campo)");
        }
      }
    } 

    public function get_usuario_vendedor($vendedor) {
        $query = $this->db->query("SELECT id_usu, nom_usu, ape_usu, log_usu, ide_usu, perfil
                                    FROM usu_sistemas
                                    WHERE id_mesero = $vendedor
                                    ORDER BY perfil");
        $result = $query->result();
        return $result;
    }

    public function chequea_usuario_adm(){
        $query = $this->db->query("SELECT id_usu, perfil  
                                     FROM usu_sistemas WHERE UPPER(log_usu) = 'FACTUFÁCIL'");
        $result = $query->result();
        if (count($result) == 0){         
          $this->db->query("INSERT INTO usu_sistemas 
                             (nom_usu, ape_usu, nac_usu, ide_usu, fec_usu, tlf_usu, ema_usu, dir_usu, log_usu, 
                              pwd_usu, est_usu, fot_usu, perfil, id_mesero, id_punto, ultimoacceso)
                              VALUES ('factufacil', '', '0', '', '2019-01-01', '', '', '', 'factufacil', 
                                      '8843028fefce50a6de50acdf064ded27', 'A', NULL, '1', '0', '0', 
                                      '2019-01-01 01:01:01');");
          $ult_id = $this->db->query("SELECT MAX(id_usu) as id FROM usu_sistemas");
          $result = $ult_id->result();  
          $usuid = $result[0]->id; 
        }
        else{
          $usuid = $result[0]->id_usu; 
        }
        $this->db->query("UPDATE usu_sistemas SET perfil = 1 WHERE id_usu = $usuid");
        $this->db->query("INSERT INTO usuprecio (idusu, idpre, estatus)
                            SELECT $usuid, id_precios, 1 FROM precios p
                              WHERE NOT EXISTS (SELECT * FROM usuprecio u
                                                  WHERE u.idusu = $usuid AND u.idpre = p.id_precios)");
        $this->db->query("INSERT INTO permiso_almacen (id_usuario, id_almacen)
                            SELECT $usuid, almacen_id FROM almacen a
                              WHERE NOT EXISTS (SELECT * FROM permiso_almacen p
                                                  WHERE p.id_usuario = $usuid AND p.id_almacen = a.almacen_id)");
        $this->db->query("INSERT INTO permiso_cajaefectivo (id_usuario, id_caja)
                            SELECT $usuid, id_caja FROM caja_efectivo a
                              WHERE NOT EXISTS (SELECT * FROM permiso_cajaefectivo p
                                                  WHERE p.id_usuario = $usuid AND p.id_caja = a.id_caja)");
        $this->db->query("INSERT INTO permiso_sucursal (id_usuario, id_sucursal)
                            SELECT $usuid, id_sucursal FROM sucursal a
                              WHERE NOT EXISTS (SELECT * FROM permiso_sucursal p
                                                  WHERE p.id_usuario = $usuid AND p.id_sucursal = a.id_sucursal)");
    }

/*

select *, TIME_TO_SEC(TIMEDIFF(now(),ultimoacceso)) as difseg from usu_sistemas
*/
}
