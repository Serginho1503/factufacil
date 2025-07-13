<?php

/* ------------------------------------------------
  ARCHIVO: Sistema_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a Parametros de Sistema.
  FECHA DE CREACIÓN: 25/04/2019
 * 
  ------------------------------------------------ */

class Sistema_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* OBTENER datos Sistema */
    public function sel_sistema() {
        $query = $this->db->query("SELECT id, imagenfondo, id_colorheader, nombresistema, icon_pedido 
                                     FROM sistema;");
        $result = $query->result();
        return $result[0];
    }

    /* ACTUALIZAR Sistema */
    public function upd_sistema($imagenfondo, $colorheader){
        $query = $this->db->query("UPDATE sistema SET 
                                     imagenfondo = '$imagenfondo',
                                     id_colorheader = $colorheader");
    }

    /* OBTENER Colores kin Bootstrap */
    public function lst_colorskin() {
        $query = $this->db->query("SELECT id, skin, color FROM color_skin;");
        $result = $query->result();
        return $result;
    }

    public function sel_nombresistema() {
        $query = $this->db->query("SELECT nombresistema FROM sistema;");
        $result = $query->result();
        $nombresistema = 'FACTUFÁCIL';
        if (trim($result[0]->nombresistema != ''))
            $nombresistema = $result[0]->nombresistema;            
        return $nombresistema;
    }

    public function sel_iconosistema() {
        $query = $this->db->query("SELECT iconosistema FROM sistema;");
        $result = $query->result();
        $iconosistema = base_url() . 'public/img/log_ff_mod_web.png';
        if (trim($result[0]->iconosistema != ''))
            $iconosistema = base_url() . 'public/img/' . $result[0]->iconosistema;            
        return $iconosistema;
    }

    public function sel_imagenfondo() {
        $query = $this->db->query("SELECT id, imagenfondo, id_colorheader FROM sistema;");
        $result = $query->result();
        $imagenfondo = base_url() . 'public/img/home_app001.jpg';
        if (trim($result[0]->imagenfondo != ''))
            $imagenfondo = base_url() . 'public/img/' . $result[0]->imagenfondo;            
        return $imagenfondo;
    }

    public function sel_colorheader() {
        $query = $this->db->query("SELECT s.id, s.imagenfondo, s.id_colorheader, c.skin 
                                     FROM sistema s
                                     INNER JOIN color_skin c on c.id = s.id_colorheader;");
        $result = $query->result();
        $color = 'skin-red';
        if (count($result) > 0)
            $color = $result[0]->skin;            
        return $color;
    }

}
