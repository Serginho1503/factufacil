<?php

/* ------------------------------------------------
  ARCHIVO: Contab_ejercicios_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a Ejercicios.
 * 
  ------------------------------------------------ */

class Contab_ejercicios_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* Ejercicios */
    public function sel_ejercicios(){
        $query = $this->db->query("SELECT id, inicio, fin, descripcion FROM con_ejercicio ORDER by inicio");
        $result = $query->result();
        return $result;     
    }

    public function sel_ejercicio_id($id){
        $query = $this->db->query("SELECT id, inicio, fin, descripcion FROM con_ejercicio
                                     WHERE id = $id");
        $resultado = $query->result();
        return $resultado[0];
      }
    
    public function add_ejercicio($inicio, $fin, $descripcion){
        $this->db->query("INSERT INTO con_ejercicio (inicio, fin, descripcion) 
                            Values('$inicio', '$fin', '$descripcion');");
    }

    public function upd_ejercicio($id, $inicio, $fin, $descripcion){
        $this->db->query("UPDATE con_ejercicio SET
                              inicio = '$inicio', 
                              fin = '$fin',
                              descripcion = '$descripcion',
                              fechamodificacion = now()
                            WHERE id = $id;");
    }

    public function candel_ejercicio($id){
        $query = $this->db->query("SELECT count(*) as cant FROM con_comprobante WHERE idejercicio = $id");
        $result = $query->result();
        if ($result[0]->cant == 0)
          { return 1; }
        else
          { return 0; }
      }
  
    public function del_ejercicio($id){
        if ($this->candel_ejercicio($id)){
            $this->db->query("DELETE FROM con_ejercicio WHERE id = $id;");
            return 1;                    
        }                        
        else{ 
            return 0; 
        }
    }

    public function sel_ejercicio_ultimafecha(){
        $query = $this->db->query("SELECT DATE(IFNULL((SELECT max(fin) FROM con_ejercicio),now())) as fin");
        $result = $query->result();
        $ultimafecha = $result[0]->fin;
        return $ultimafecha;
    }    

    public function fechas_enotroejercicio($id, $inicio, $fin){
        $query = $this->db->query("SELECT count(*) as cant FROM con_ejercicio
                                     WHERE id <> $id AND 
                                           ((date(inicio) BETWEEN '$inicio' AND '$fin') OR 
                                            (date(fin) BETWEEN '$inicio' AND '$fin'))");
        $resultado = $query->result();
        return $resultado[0]->cant;
      }
    
}
