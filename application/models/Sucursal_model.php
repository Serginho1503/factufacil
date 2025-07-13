<?php

/* ------------------------------------------------
  ARCHIVO: Sucursal_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Sucursal.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */

class Sucursal_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* INSERTA EL REGISTRO DEL ALMACEN*/
    public function suc_add($nomsuc, $encasuc, $tlfsuc, $emasuc, $dirsuc, $img, $empresa, $ordenservicio, 
                            $pie1proforma, $imgdetalle, $imgpie, $imgpath){
        $this->db->query("INSERT INTO sucursal (nom_sucursal, dir_sucursal, telf_sucursal, mail_sucursal, 
                                                enca_sucursal, logo_sucursal, id_empresa, activo, 
                                                pie1_texto, logo_detallepagina, logo_piepagina,
                                                consecutivo_ordenservicio, logo_encab_path)
                            VALUES('$nomsuc','$dirsuc','$tlfsuc','$emasuc','$encasuc','$img', $empresa, 1, 
                                    '$pie1proforma', '$imgdetalle', '$imgpie', $ordenservicio, '$imgpath');");

        $query = $this->db->query("select last_insert_id() as newid");
        $result = $query->result();
        $newidsuc = $result[0]->newid;

        $this->db->query("INSERT INTO deposito_efectivo (idtipo, idsucursal)
                            VALUES(2, $newidsuc);");

        $query = $this->db->query("select last_insert_id() as newid");
        $result = $query->result();
        $newiddep = $result[0]->newid;

        $this->db->query("INSERT INTO caja_chica (id_caja, nom_caja, activo)
                            VALUES($newiddep, '$nomsuc', 1)");

    }

    /* BUSQUEDA POR ID QUE PERMITE MOSTRAR EL ALMACEN PARA SER MODIFICADO */
    public function sel_suc_id($idsuc){
      $query = $this->db->query("SELECT id_sucursal, nom_sucursal, dir_sucursal, telf_sucursal, mail_sucursal, 
                                        enca_sucursal, consecutivo_ordenservicio,
                                        logo_sucursal, id_empresa, consecutivo_retencioncompra, pie1_texto,
                                        logo_detallepagina, logo_piepagina, contabilizacion_automatica,
                                        logo_encab_path 
                                 FROM sucursal WHERE id_sucursal = $idsuc");
      $result = $query->result();
      return $result[0];
    }

    /* MODIFICAR REGISTRO PERTENECIENTE AL ALMACEN SELECCIONADO */
    public function suc_upd($idsuc, $nomsuc, $encasuc, $tlfsuc, $emasuc, $dirsuc, $img, $empresa, 
                            $ordenservicio, $pie1proforma, $imgdetalle, $imgpie, $imgpath){
      if ($img === NULL){ $logo = "logo_sucursal = NULL,"; }
        else if ($img === ""){ $logo = ""; }
             else {$logo = "logo_sucursal = '$img',"; }
      if ($imgdetalle === NULL){ $logodetalle = "logo_detallepagina = NULL,"; }
        else if ($imgdetalle === "") { $logodetalle = ""; }
             else {$logodetalle = "logo_detallepagina = '$imgdetalle',"; }
      if ($imgpie === NULL){ $logopie = "logo_piepagina = NULL,"; }
        else if ($imgpie === ""){ $logopie = ""; }
             else {$logopie = "logo_piepagina = '$imgpie',"; }
      $this->db->query("UPDATE sucursal SET nom_sucursal = '$nomsuc', dir_sucursal = '$dirsuc', 
                                            telf_sucursal = '$tlfsuc', 
                                            mail_sucursal = '$emasuc', ".$logo." ".$logodetalle." ".$logopie." 
                                            enca_sucursal = '$encasuc', pie1_texto = '$pie1proforma',
                                            id_empresa = $empresa,
                                            consecutivo_ordenservicio = $ordenservicio,
                                            logo_encab_path = '$imgpath'                                                                                                           
                          WHERE id_sucursal = $idsuc");
    }

    public function candel_sucursal($id){
      $query = $this->db->query("SELECT count(*) as cant FROM punto_emision WHERE id_sucursal = $id");
      $result = $query->result();
      if ($result[0]->cant == 0){
        $query = $this->db->query("SELECT count(*) as cant FROM almacen WHERE sucursal_id = $id");
        $result = $query->result();
        if ($result[0]->cant == 0){
          $query = $this->db->query("SELECT count(*) as cant FROM caja_chica_movimiento c
                                       INNER JOIN deposito_efectivo d on d.id = c.id_caja
                                       WHERE d.idsucursal = $id");
          $result = $query->result();
        }
      }
      if ($result[0]->cant == 0)
        { return 1; }
      else
        { return 0; }
    }

    public function suc_del($idsuc){
      if ($this->candel_sucursal($idsuc) == 1){
        $this->db->query("DELETE FROM caja_chica 
                            WHERE id_caja in (SELECT id FROM deposito_efectivo WHERE idsucursal = $idsuc)");
        $this->db->query("DELETE FROM deposito_efectivo WHERE idsucursal = $idsuc");
        $this->db->query("DELETE FROM sucursal WHERE id_sucursal = $idsuc");
        return 1;
      } else {
        return 0;
      }
    }

    /* BUSQUEDA POR ID QUE PERMITE MOSTRAR EL ALMACEN PARA SER MODIFICADO */
    public function lst_sucursales(){
      $query = $this->db->query("SELECT id_sucursal, nom_sucursal, dir_sucursal, telf_sucursal, 
                                        mail_sucursal,  enca_sucursal, logo_sucursal, id_empresa, 
                                        contabilizacion_automatica, logo_encab_path 
                                 FROM sucursal WHERE activo = 1 /*ORDER BY nom_sucursal*/");
      $result = $query->result();
      return $result;
    }

    public function lst_empresas(){
      $query = $this->db->query("SELECT id_emp, nom_emp FROM empresa ORDER BY nom_emp");
      $result = $query->result();
      return $result;
    }

    public function get_proxnumeronotacredito($sucursal){
      $query = $this->db->query("SELECT consecutivo_notacredito FROM sucursal
                                   WHERE id_sucursal = $sucursal");
      $resret = $query->result();
      if ($resret) 
        return $resret[0]->consecutivo_notacredito;
      else
        return 0;
    }

    public function lst_sucursal_empresa($empresa){
      $query = $this->db->query("SELECT id_sucursal, nom_sucursal, dir_sucursal, telf_sucursal, mail_sucursal, 
                                        enca_sucursal, logo_sucursal, id_empresa, 
                                        contabilizacion_automatica, logo_encab_path 
                                 FROM sucursal 
                                 WHERE activo = 1 AND id_empresa = $empresa
                                 ORDER BY nom_sucursal");
      $result = $query->result();
      return $result;
    }

    public function lst_sucursal_usuario(){
      $usua = $this->session->userdata('usua');
      $id_usu = $usua->id_usu;

      $sql = $this->db
              ->select('sucursal.id_sucursal, nom_sucursal, dir_sucursal, telf_sucursal, mail_sucursal, 
                        enca_sucursal, logo_sucursal, id_empresa, contabilizacion_automatica, 
                        logo_encab_path')
              ->from('sucursal')
              ->join('permiso_sucursal', 'permiso_sucursal.id_sucursal = sucursal.id_sucursal')
              ->where(array('activo' => 1, 'permiso_sucursal.id_usuario' => $id_usu))
              ->get()
              ->result();
      return $sql;
    }

}
