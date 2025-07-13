<?php

/* ------------------------------------------------
  ARCHIVO: Update_base_model.php
  DESCRIPCION: Clase base para actualizaciones de BD.
  FECHA DE CREACIÓN: 16/02/2019
 * 
  ------------------------------------------------ */

class Update_base_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function actualizabase(){

    }

    public function existe_tabla($tabla){
      $mydb = $this->db->database;
      $query = $this->db->query("SELECT count(*) as cant FROM information_schema.tables
                                   WHERE table_schema = '$mydb' and table_name = '$tabla'");
      $r = $query->result();
      if ($r != null){
        $myresult = ($r[0]->cant > 0);
      } else {
        $myresult = false;      
      } 
      return $myresult;
    }

    public function existe_columna_tabla($tabla, $columna){
        $mydb = $this->db->database;
        $query = $this->db->query("select count(*) as cant
                                    from information_schema.columns
                                    where column_name = '$columna'
                                      and table_name = '$tabla'
                                    and table_schema = '$mydb'");
        $r = $query->result();
        if ($r != null){
          $myresult = ($r[0]->cant > 0);
        } else {
          $myresult = false;      
        } 
        return $myresult;
    }
  
    public function add_columna_tabla($tabla, $columna, $tipodato, $consulta){
        $query = $this->db->query("ALTER TABLE $tabla ADD $columna $tipodato");
        if (trim($consulta) != ""){
          $query = $this->db->query($consulta);
        }        
    }    

    public function drop_columna_tabla($tabla, $columna){
        $query = $this->db->query("ALTER TABLE $tabla DROP COLUMN $columna");
        if (trim($consulta) != ""){
          $query = $this->db->query($consulta);
        } 
    }
  
    public function upd_columna_tabla($tabla, $columna, $tipodato){
        $query = $this->db->query("ALTER TABLE $tabla MODIFY COLUMN $columna $tipodato");
    }    
    
    public function existe_indice_tabla($tabla, $indice){
      $mydb = $this->db->database;
      $query = $this->db->query("SELECT COUNT(*) as cant FROM INFORMATION_SCHEMA.STATISTICS
                                    WHERE table_schema='$mydb' AND 
                                          table_name='$tabla' AND 
                                          index_name='$indice';");
      $r = $query->result();
      if ($r != null){
        $myresult = ($r[0]->cant > 0);
      } else {
        $myresult = false;      
      } 
      return $myresult;
    }

    public function add_indice_tabla($tabla, $indice, $columnas){
      $this->db->query("CREATE INDEX $indice ON $tabla($columnas);");
    }

    public function drop_indice_tabla($tabla, $indice){
      $this->db->query("ALTER TABLE $tabla DROP index $indice;");
    }

    public function existe_foreign_key($theforeignkey){
      $mydb = $this->db->database;
      $query = $this->db->query("SELECT COUNT(*) as cant FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS 
                                   WHERE CONSTRAINT_SCHEMA = '$mydb'
                                     AND CONSTRAINT_NAME ='$theforeignkey';");
      $r = $query->result();
      if ($r != null){
        $myresult = ($r[0]->cant > 0);
      } else {
        $myresult = false;      
      } 
      return $myresult;
    }

    public function add_foreign_key($tabla, $theforeignkey, $columna, $tablaref, $columnaref){
      $this->db->query("ALTER TABLE $tabla ADD constraint $theforeignkey
                          foreign key ($columna) references $tablaref($columnaref)");
    }

    public function drop_foreign_key($tabla, $theforeignkey){
      $this->db->query("ALTER TABLE $tabla DROP FOREIGN KEY $theforeignkey");
    }

    public function chequea_foreign_key(){
      $res = $this->existe_foreign_key('FK_almacen_almapro');
      if ($res != true) $this->add_foreign_key('almapro','id_cliente', 'int', "");

    }

    public function chequea_engine($tabla){
      $mydb = $this->db->database;
      $query = $this->db->query("SELECT count(*) as cant 
                                  FROM information_schema.TABLES
                                  WHERE TABLE_SCHEMA = '$mydb' AND TABLE_NAME = '$tabla' AND ENGINE = 'InnoDB';");
      $r = $query->result();
      if ($r != null){
        $myresult = ($r[0]->cant > 0);
      } else {
        $myresult = false;      
      } 
      if ($myresult == false) {
            $this->db->query("ALTER TABLE $tabla ENGINE = InnoDB;");            
      }
    }


    public function crea_tabla_version(){
      $res = $this->existe_tabla('versionsistema');
      if ($res != true) {
            $query = $this->db->query("CREATE TABLE `versionsistema` (
                                        `version` int(11) NOT NULL,
                                        `descripcion` text,
                                        PRIMARY KEY (`version`)
                                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      }      
    }

    public function get_version(){
      $query = $this->db->query("SELECT max(version) as version FROM versionsistema");
      $r = $query->result();
      if ($r != null){
        return $r[0]->version;
      } else {
        return 0;    
      }
    }

    public function version_inserta($version, $descripcion){
      $query = $this->db->query("SELECT count(*) as cant FROM versionsistema WHERE version = $version");
      $r = $query->result();
      if ($r[0]->cant == 0){
        $this->db->query("INSERT INTO versionsistema (version, descripcion) VALUES($version, '$descripcion')");
      } 
    }
	
}
