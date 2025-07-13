<?php

/* ------------------------------------------------
  ARCHIVO: Precio_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Precio.
  FECHA DE CREACIÓN: 12/07/2017
 * 
  ------------------------------------------------ */

class Precio_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* INSERTA EL REGISTRO DEL PRECIO*/
    public function pre_add($desc, $est, $color){
        $query = $this->db->query("INSERT INTO precios (desc_precios, esta_precios, color)VALUES('$desc','$est','$color');");
    }
    /* BUSQUEDA POR ID QUE PERMITE MOSTRAR EL PRECIO PARA SER MODIFICADO */
    public function sel_pre_id($id_pre){
      $query = $this->db->query("SELECT id_precios, desc_precios, esta_precios, color FROM precios WHERE id_precios = $id_pre");
      $result = $query->result();
      return $result[0];
    }
    /* MODIFICAR REGISTRO PERTENECIENTE AL PRECIO SELECCIONADO */
    public function pre_upd($idpre, $desc, $est, $color){
      $query = $this->db->query("UPDATE precios SET desc_precios = '$desc', esta_precios = '$est', color = '$color' 
                                   WHERE id_precios = $idpre");
    }
    /* ELIMINAR EL REGISTRO DE PRECIO SELECCIONADO */
    public function pre_del($idpre){
      $query = $this->db->query("DELETE FROM precios WHERE id_precios = $idpre");
    }

    /* CARGA LISTADO DE PRECIOS */
    public function precioslst(){
      $sql = $this->db->query("SELECT id_precios, desc_precios, esta_precios, color FROM precios");
      $result = $sql->result();
      return $result;
    }

    public function lst_porcientoprecioventa(){
        $this->db->query("INSERT INTO precio_compraventa (id_precio, porciento)
                            SELECT id_precios, 0 
                              FROM (SELECT id_precios FROM precios UNION SELECT 0 as id_precios) as tmprecio
                              WHERE NOT id_precios in (SELECT id_precio FROM precio_compraventa);");

        $sql = $this->db->query("SELECT id_precio, porciento, IFNULL(desc_precios, 'Tienda') as desc_precios 
                                   FROM precio_compraventa c
                                   LEFT JOIN precios p on p.id_precios = c.id_precio
                                   ORDER BY id_precio");
        $result = $sql->result();

        return $result;
    }

    public function upd_porcientoprecioventa($listaprecios){
      foreach($listaprecios as $precio){
        $this->db->update('precio_compraventa', 
                          array('porciento' => $precio->porciento), 
                          array('id_precio' => $precio->id_precio)
                          );
      }  
    }  

}
