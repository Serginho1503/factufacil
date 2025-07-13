<?php

/* ------------------------------------------------
  ARCHIVO: Petshop_update_model.php
  DESCRIPCION: Manejo de consultas para la actualizacion de BD.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */

require_once(APPPATH.'models/Update_base_model.php');  

class Petshop_update_model extends Update_base_model {

    function __construct() {
        parent::__construct();
    }

    public function actualizabase(){

      $res = $this->existe_tabla('pet_config');
      if ($res != true) $this->crea_tabla_pet_config();

      $res = $this->existe_tabla('pet_mascotas');
      if ($res != true) $this->crea_tabla_pet_mascotas();

      $res = $this->existe_tabla('pet_mascota_historiaclinica');
      if ($res != true) $this->crea_tabla_pet_mascota_historiaclinica();     

      return 1;
    }

    public function crea_tabla_pet_config(){
      $this->db->query("CREATE TABLE `pet_config` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `habilita_petshop` tinyint(1) NOT NULL,
                                PRIMARY KEY (`id`)
                          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

      $this->db->insert('pet_config', array('habilita_petshop'=> 0));

    }

    public function crea_tabla_pet_mascotas(){
      $this->db->query("CREATE TABLE `pet_mascotas` (
                          `id_mascota` int(11) NOT NULL AUTO_INCREMENT,
                          `id_cliente` int(11) DEFAULT NULL,
                          `nombre` varchar(255) DEFAULT NULL,
                          `codigo` varchar(255) DEFAULT NULL,
                          `raza` varchar(255) DEFAULT NULL,
                          `color` varchar(255) DEFAULT NULL,
                          `sexo` varchar(255) DEFAULT NULL,
                          `fec_nac` date DEFAULT NULL,
                          `caracteristicas` text,
                          `veterinario` varchar(255) DEFAULT NULL,
                          `telf_veterinario` varchar(255) DEFAULT NULL,
                          `foto_mascota` varchar(255) DEFAULT NULL,
                          CONSTRAINT FK_pet_mascotas_cliente FOREIGN KEY (id_cliente)
                              REFERENCES clientes(id_cliente),
                          PRIMARY KEY (`id_mascota`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function crea_tabla_pet_mascota_historiaclinica(){
      $this->db->query("CREATE TABLE `pet_mascota_historiaclinica` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `id_mascota` int(11) NOT NULL ,
                          `id_sucursal` int(11) NOT NULL ,
                          `fecha` date DEFAULT NULL,
                          `observaciones` text NULL,
                          CONSTRAINT FK_pet_mascota_historiaclinica_mascota FOREIGN KEY (id_mascota)
                              REFERENCES pet_mascotas(id_mascota),
                          CONSTRAINT FK_pet_mascota_historiaclinica_sucursal FOREIGN KEY (id_sucursal)
                              REFERENCES sucursal(id_sucursal),
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

}

