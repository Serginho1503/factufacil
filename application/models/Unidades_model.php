<?php

/* ------------------------------------------------
  ARCHIVO: Unidades_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Unidades de Medida.
  FECHA DE CREACIÓN: 10/07/2017
 * 
  ------------------------------------------------ */

class Unidades_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* INSERTA EL REGISTRO DE LA UNIDAD DE MEDIDA*/
    public function uni_add($desc, $nomc){
        $query = $this->db->query("INSERT INTO unidadmedida (descripcion, nombrecorto)VALUES('$desc','$nomc');");
    }

    public function sel_upd_id($id_uni){
      $query = $this->db->query("SELECT id, descripcion, nombrecorto FROM unidadmedida WHERE id = $id_uni");
      $result = $query->result();
      return $result[0];
    }

    public function uni_upd($id_uni, $desc, $nomc){
      $query = $this->db->query("UPDATE unidadmedida SET descripcion = '$desc', nombrecorto = '$nomc' WHERE id = $id_uni");
    }

    public function uni_del($id_uni){
      $query = $this->db->query("DELETE FROM unidadmedida WHERE id = $id_uni");
    }

    public function fact_conv_lst($id_uni){
      $query = $this->db->query(" SELECT f.*, u.nombrecorto, u.descripcion FROM unidadfactorconversion f
                                  INNER JOIN unidadmedida u on u.id=f.idunidadequivale
                                  WHERE idunidad1 = $id_uni
                                  ORDER BY nombrecorto" );
      $result = $query->result();
      return $result;
    }

    public function lst_uni($id_uni){
       $query = $this->db->query("SELECT * FROM unidadmedida 
                                  WHERE id != $id_uni AND
                                  NOT id IN (SELECT idunidadequivale 
                                             FROM unidadfactorconversion 
                                             WHERE idunidad1 = $id_uni)");
       $r = $query->result();
       RETURN $r;
    }

    public function add_fact_conv($id_uni, $uni_conv, $cant){
      $query = $this->db->query("INSERT INTO unidadfactorconversion (idunidad1, idunidadequivale, cantidadequivalente)VALUES($id_uni, $uni_conv, $cant)");
    }

    public function sel_uni_conv($id, $uni){
      $query = $this->db->query(" SELECT uf.idunidad1, uf.idunidadequivale, u.descripcion, uf.cantidadequivalente
                                  FROM unidadfactorconversion uf, unidadmedida u
                                  WHERE uf.idunidadequivale = u.id
                                  AND idunidad1 = $id AND idunidadequivale = $uni");
      $result = $query->result();
      return $result[0];
    }

    public function del_fact_conv($id, $uni){
      $query = $this->db->query("DELETE FROM unidadfactorconversion WHERE idunidad1 = $id AND idunidadequivale = $uni");
    }

    public function upd_fact_conv($id_uni, $id_fact, $uni_conv, $cant){
      $query = $this->db->query("UPDATE unidadfactorconversion SET idunidadequivale = $uni_conv, cantidadequivalente = $cant 
                                 WHERE idunidad1 = $id_uni AND idunidadequivale = $id_fact");
    }

    public function sel_unidad(){
      $query = $this->db->query("SELECT id, descripcion, nombrecorto FROM unidadmedida");
      $result = $query->result();
      return $result;
    }

    public function sel_unidadprod($idpro){
      $query = $this->db->query("SELECT id, descripcion, nombrecorto 
                                  FROM unidadmedida  
                                  where id in (select pro_idunidadmedida from producto where pro_id = $idpro
                                               union
                                               select fd.idunidadequivale from producto p
                                                 inner join unidadfactorconversion fd on fd.idunidad1 = p.pro_idunidadmedida 
                                                 where p.pro_id = $idpro
                                               union
                                               select fd.idunidad1 from producto p
                                                 inner join unidadfactorconversion fd on fd.idunidadequivale = p.pro_idunidadmedida 
                                                 where p.pro_id = $idpro)
                                  order by descripcion");
      $result = $query->result();
      return $result;
    }

}
