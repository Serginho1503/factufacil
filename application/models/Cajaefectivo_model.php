<?php
/* ------------------------------------------------
  ARCHIVO: Cajaefectivo_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al Caja efectivo.
  FECHA DE CREACIÓN: 23/05/2018
 * 
  ------------------------------------------------ */
  class Cajaefectivo_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function sel_cajaefectivo(){
      $query = $this->db->query("SELECT s.nom_sucursal, 
                                        CONCAT(p.cod_establecimiento,'-',p.cod_puntoemision) AS codigo, 
                                        c.id_caja, c.nom_caja, c.activo
                                  FROM caja_efectivo c
                                  INNER JOIN punto_emision p ON p.id_puntoemision = c.id_puntoemision
                                  INNER JOIN sucursal s ON s.id_sucursal = p.id_sucursal");
      $result = $query->result();
      return $result;
    }

    public function sel_puntoemision_id($idsuc){
      $query = $this->db->query("SELECT id_puntoemision AS id, 
                                        CONCAT(cod_establecimiento,'-',cod_puntoemision) AS codigo
                                  FROM punto_emision
                                  WHERE id_sucursal = $idsuc");
      $result = $query->result();
      return $result;
    }

    public function cajaefectivo_guarda($ptoemision, $caja, $estatus){
      $this->db->query("INSERT INTO deposito_efectivo (idtipo, idsucursal)
                          SELECT 1, id_sucursal FROM punto_emision WHERE id_puntoemision = $ptoemision;");

      $query = $this->db->query("select last_insert_id() as newid");
      $result = $query->result();
      $newiddep = $result[0]->newid;

      $this->db->query("INSERT INTO caja_efectivo (id_caja, id_puntoemision, nom_caja, activo, nro_orden) 
                          VALUES ($newiddep, $ptoemision, '$caja', $estatus, 1)");
    }

    public function candel_cajaefectivo($caja){
      $query = $this->db->query("SELECT count(*) as cant FROM venta WHERE id_caja = $caja");
      $result = $query->result();
      if ($result[0]->cant == 0){
        $query = $this->db->query("SELECT count(*) as cant FROM caja_movimiento WHERE id_caja = $caja");
        $result = $query->result();
      }
      if ($result[0]->cant == 0)
        { return 1; }
      else
        { return 0; }
    }

    public function cajaefectivo_eliminar($id){
      if ($this->candel_cajaefectivo($id) == 1){
        $query = $this->db->query("DELETE FROM caja_efectivo WHERE id_caja = $id");
        return 1;
      } else {
        return 0;
      }
    }

    public function sel_cajaefectivo_id($idcaja){
      $query = $this->db->query(" SELECT c.*, p.id_sucursal AS idsuc, 
                                         s.id_empresa, s.contabilizacion_automatica
                                  FROM caja_efectivo c 
                                  INNER JOIN punto_emision p on p.id_puntoemision = c.id_puntoemision
                                  INNER JOIN sucursal s on s.id_sucursal = p.id_sucursal
                                  WHERE c.id_caja = $idcaja");
/*      $query = $this->db->query(" SELECT c.*,(SELECT p.id_sucursal FROM punto_emision p WHERE p.id_puntoemision = c.id_puntoemision) AS idsuc
                                  FROM caja_efectivo c WHERE c.id_caja = $idcaja");*/
      $result = $query->result();
      return $result[0];
    }

    public function cajaefectivo_actualiza($idcaja, $ptoemision, $caja, $estatus){
      $this->db->query(" UPDATE caja_efectivo SET id_puntoemision = $ptoemision, 
                                                  nom_caja = '$caja', 
                                                  activo = $estatus
                                            WHERE id_caja = $idcaja");
    }

    public function lst_caja_sucursal($sucursal){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $query = $this->db->query("SELECT c.id_caja, c.nom_caja, c.id_puntoemision, p.consecutivo_notaventa,
                                        concat(p.cod_establecimiento,'-',p.cod_puntoemision,'-',lpad(p.consecutivo_factura,9,'0')) as nrofactura
                                   FROM caja_efectivo c
                                   INNER JOIN punto_emision p on p.id_puntoemision = c.id_puntoemision
                                   INNER JOIN permiso_cajaefectivo pc on pc.id_caja = c.id_caja
                                   WHERE c.activo = 1 and p.activo = 1 and 
                                         p.id_sucursal = $sucursal and pc.id_usuario = $idusu
                                   ORDER BY nom_caja");
      $result = $query->result();
      return $result;
    
    }    

}
