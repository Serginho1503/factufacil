<?php

/* ------------------------------------------------
  ARCHIVO: Contab_balance_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a reportes.
 * 
  ------------------------------------------------ */

class Contab_balance_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }      

    public function sel_operaciones($sucursal, $cuenta, $desde, $hasta, $pendiente){
      $query = $this->db->query("call con_lista_operaciones($sucursal, $cuenta, '$desde', '$hasta', $pendiente);");

      $result = $query->result();

      $query->next_result(); 
      $query->free_result();

      return $result;
    }    
       
    public function sel_balancesumasaldo($sucursal, $desde, $hasta, $pendiente){
        $query = $this->db->query("call con_balance_sumasaldo($sucursal, '$desde', '$hasta', $pendiente);");
  
        $result = $query->result();
  
        $query->next_result(); 
        $query->free_result();
  
        return $result;
      }    

    public function sel_balancesituacion($sucursal, $hasta, $nivel, $pendiente){
      $query = $this->db->query("call con_balance_situacion($sucursal, $nivel, '$hasta', $pendiente);");

      $result = $query->result();

      $query->next_result(); 
      $query->free_result();

      return $result;
    }    

    public function sel_niveles(){
      $query = $this->db->query("SELECT DISTINCT nivel FROM con_plancuenta ORDER BY nivel;");
      $result = $query->result();
      return $result;
    }    
    
}
