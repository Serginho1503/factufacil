<?php

/* ------------------------------------------------
  ARCHIVO: Empresa_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Empresa.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */

class Empresa_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* OBTENER TODOS LOS DATOS DE LA EMPRESA */
    public function emp_get() {
        $query = $this->db->query(" SELECT * FROM empresa;");
        $result = $query->result();
        return $result[0];
    }

    /* SE ACTUALIZAN LOS DATOS DE LA EMPRESA */
    public function emp_upd($id, $cod, $nom, $ruc, $rs, $ema, $tlf, $fax, $dir, $rep, $web, $regimen, $img, 
                            $token, $obligadocontabilidad){
        if ($obligadocontabilidad == '') { $obligadocontabilidad = 0; }
        $this->db->query("UPDATE empresa SET nom_emp = '$nom',
                                              cod_emp = '$cod',
                                              ruc_emp = '$ruc',
                                              raz_soc_emp = '$rs',
                                              ema_emp = '$ema',
                                              tlf_emp = '$tlf', 
                                              fax_emp = '$fax',
                                              dir_emp = '$dir', 
                                              rep_emp = '$rep',
                                              web_emp = '$web',
                                              regimen_emp = '$regimen',
                                              logo_path = '$img',
                                              tokenfirma = '$token',
                                              obligadocontabilidad = $obligadocontabilidad
                              WHERE id_emp = $id;");
    }

    public function emp_ins($cod, $nom, $ruc, $rs, $ema, $tlf, $fax, $dir, $rep, $web, $regimen, $img, $token, $obligadocontabilidad){
        if ($obligadocontabilidad == '') { $obligadocontabilidad = 0; }
        $this->db->query("INSERT INTO empresa (cod_emp,nom_emp,ruc_emp,raz_soc_emp,ema_emp,
                                               tlf_emp,fax_emp,dir_emp,rep_emp,web_emp, regimen_emp, logo_path, 
                                               tokenfirma, obligadocontabilidad)
                            VALUES ('$cod', '$nom', '$ruc', '$rs', '$ema', '$tlf', '$fax', '$dir', '$rep', '$web', '$regimen', '$img', 
                                    '$token', $obligadocontabilidad);");
    }

    public function lst_empresa(){
      $sql = $this->db->query("SELECT id_emp, cod_emp, nom_emp, ruc_emp, raz_soc_emp, logo_path, obligadocontabilidad 
                                 FROM empresa");
      $res = $sql->result();
      return $res;
    }

    public function sel_emp_id($idemp){
      $query = $this->db->query("SELECT e.*, IFNULL(t.contrasena,'') as contrasena
                                   FROM empresa e 
                                   LEFT JOIN tokenfirma t on t.nombrearchivo = e.tokenfirma
                                   WHERE id_emp = $idemp");
      $result = $query->result();
      return $result[0];
    }

   /* ELIMINAR EL REGISTRO SELECCIONADO */
    public function emp_del($id){
      $this->db->query("DELETE FROM empresa WHERE id_emp = $id");
    }

    public function existe_info_emp($idemp){
      $query = $this->db->query("SELECT count(*) as cant FROM sucursal WHERE id_empresa = $idemp
                                  UNION 
                                 SELECT count(*) as cant FROM venta WHERE id_empresa = $idemp");
      $result = $query->result();
      return $result[0]->cant;
    }

    public function guarda_tokenfirma($token, $password){
      $query = $this->db->query("SELECT count(*) as cant FROM tokenfirma WHERE nombrearchivo = '$token'");
      $result = $query->result();
      if ($result[0]->cant == 0){
        $this->db->query("INSERT INTO tokenfirma (nombrearchivo, contrasena) VALUES('$token', '$password')");
      }
      else{
        $this->db->query("UPDATE tokenfirma SET contrasena = '$password' WHERE nombrearchivo = '$token'");
      }
    }

    public function sel_contrasena_tokenfirma($token){
      $query = $this->db->query("SELECT contrasena 
                                   FROM tokenfirma WHERE nombrearchivo = '$token'");
      $result = $query->result();
      if ($result != null){
        return $result[0]->contrasena;
      }
      else {
        return '';
      }
    }

}
