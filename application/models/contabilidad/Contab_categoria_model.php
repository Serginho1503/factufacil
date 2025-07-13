<?php

/* ------------------------------------------------
  ARCHIVO: Contab_categoria_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a categorias contables.
 * 
  ------------------------------------------------ */

class Contab_categoria_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }      

    public function sel_categoriageneral($empresa, $tipo){
      $query = $this->db->query("SELECT c.id, c.categoria, f.idcuenta, 
                                        p.codigocuenta, p.descripcion
                                    FROM con_categoria c
                                    LEFT JOIN con_configuracioncategoria f on f.idcategoria = c.id and
                                                                              f.idempresa = $empresa
                                    LEFT JOIN con_plancuenta p on p.id = f.idcuenta                                                                              
                                    WHERE c.idtipocategoria = $tipo 
                                    ORDER by c.categoria;");

      $result = $query->result();
      return $result;     
    }    

    public function ins_categoria($tipo){
        $this->db->query("INSERT INTO con_categoria (idtipocategoria) VALUES($tipo);");
    }      

    public function upd_categoria_cuenta($id, $empresa, $idcuenta){
        $this->db->query("DELETE FROM con_configuracioncategoria 
                            WHERE idcategoria = $id AND idempresa = $empresa;");
        if (($idcuenta != '') && ($idcuenta != 0)){                            
            $this->db->query("INSERT INTO con_configuracioncategoria (idcategoria, idempresa, idcuenta)
                                VALUES($id, $empresa, $idcuenta);");
        }
    }      
    
    public function upd_categoria_nombre($id, $categoria){
        $this->db->query("UPDATE con_categoria SET categoria = '$categoria'
                            WHERE id = $id;");
    }      

    public function candel_categoriacliente($id){
        $query = $this->db->query("SELECT count(*) as cant FROM clientes WHERE idcategoriacontable = $id");
        $result = $query->result();
        if ($result[0]->cant == 0)
          { return 1; }
        else
          { return 0; }
      }
    public function candel_categoriaproveedor($id){
        $query = $this->db->query("SELECT count(*) as cant FROM proveedor WHERE idcategoriacontable = $id");
        $result = $query->result();
        if ($result[0]->cant == 0)
          { return 1; }
        else
          { return 0; }
    }
    public function candel_categoriaproducto($id){
        $query = $this->db->query("SELECT count(*) as cant FROM producto WHERE idcategoriacontable = $id");
        $result = $query->result();
        if ($result[0]->cant == 0)
          { return 1; }
        else
          { return 0; }
    }
    public function candel_categoriagasto($id){
      $query = $this->db->query("SELECT count(*) as cant FROM gastos WHERE categoria = $id");
      $result = $query->result();
      if ($result[0]->cant == 0)
        { return 1; }
      else
        { return 0; }
  }

    public function del_categoria_cuenta($id, $tipo){
        $res = 0;
        if ($tipo == 1) { $res = $this->candel_categoriacliente($id); }
        if ($tipo == 2) { $res = $this->candel_categoriaproveedor($id); }
        if ($tipo == 3) { $res = $this->candel_categoriaproducto($id); }
        if ($tipo == 10) { $res = $this->candel_categoriagasto($id); }
        if ($res == 1){
            $this->db->query("DELETE FROM con_configuracioncategoria WHERE idcategoria = $id;");
            $this->db->query("DELETE FROM con_categoria WHERE id = $id;");
            return 1;
        } else {
          return 0;
        }
    }
  
    public function sel_categoriafactura($empresa){
        $query = $this->db->query("SELECT c.id, c.categoria, f.idcuenta, 
                                          p.codigocuenta, p.descripcion
                                      FROM con_categoria c
                                      LEFT JOIN con_configuracioncategoria f on f.idcategoria = c.id and
                                                                                f.idempresa = $empresa
                                      LEFT JOIN con_plancuenta p on p.id = f.idcuenta                                                                              
                                      WHERE c.idtipocategoria  in (8,9) 
                                      ORDER by c.categoria;");
  
        $result = $query->result();
        return $result;     
      }    

      public function sel_categoriagasto00($empresa){
        $query = $this->db->query("SELECT c.id, c.categoria, f.idcuenta, 
                                          p.codigocuenta, p.descripcion,
                                          case c.categoria when '' then 1 else 0 end as tienecategoria
                                      FROM con_categoria c
                                      LEFT JOIN con_configuracioncategoria f on f.idcategoria = c.id and
                                                                                f.idempresa = $empresa
                                      LEFT JOIN con_plancuenta p on p.id = f.idcuenta                                                                              
                                      WHERE c.idtipocategoria  in (10) 
                                      ORDER by tienecategoria, c.categoria;");
  
        $result = $query->result();
        return $result;     
      }    
      
      public function sel_categoriaformapagocli($empresa){
        $query = $this->db->query("SELECT f.id_formapago, f.nombre_formapago, c.idcuenta,
                                          p.codigocuenta, p.descripcion
                                      FROM formapago f
                                      LEFT JOIN con_cuentaclienteformapago c on c.idformapago = f.id_formapago and
                                                                                c.idempresa = $empresa
                                      LEFT JOIN con_plancuenta p on p.id = c.idcuenta                                                                              
                                      /*WHERE f.id_formapago != 1 */
                                      ORDER by f.nombre_formapago;");
  
        $result = $query->result();
        return $result;     
      }    

      public function sel_categoriaformapagopro($empresa){
        $query = $this->db->query("SELECT f.id_formapago, f.nombre_formapago, c.idcuenta,
                                          p.codigocuenta, p.descripcion
                                      FROM formapago f
                                      LEFT JOIN con_cuentaproveedorformapago c on c.idformapago = f.id_formapago and
                                                                                c.idempresa = $empresa
                                      LEFT JOIN con_plancuenta p on p.id = c.idcuenta                                                                              
                                      /*WHERE f.id_formapago != 1 */
                                      ORDER by f.nombre_formapago;");
  
        $result = $query->result();
        return $result;     
      }    

      public function sel_categoriadeposito($empresa){
        $query = $this->db->query("SELECT f.id, f.idcuentacontable as idcuenta,
                                          p.codigocuenta, p.descripcion, t.tipo,
                                          CASE 
                                            WHEN ce.id_caja IS NOT NULL THEN ce.nom_caja
                                            WHEN cc.id_caja IS NOT NULL THEN cc.nom_caja
                                            ELSE ''
                                          END as nombre
                                      FROM deposito_efectivo f
                                      INNER JOIN deposito_tipo t on t.id = f.idtipo
                                      INNER JOIN sucursal s on s.id_sucursal = f.idsucursal AND s.id_empresa = $empresa
                                      LEFT JOIN caja_efectivo ce on ce.id_caja = f.id                                                                              
                                      LEFT JOIN caja_chica cc on cc.id_caja = f.id                                                                              
                                      LEFT JOIN con_plancuenta p on p.id = f.idcuentacontable                                                                              
                                      ORDER by nombre;");
  
        $result = $query->result();
        return $result;     
      }    

      public function sel_categoriadocuminv($tipocmp){
        $query = $this->db->query("SELECT c.id, c.categoria
                                      FROM con_categoria c
                                      WHERE c.idtipocategoria in ($tipocmp) 
                                      ORDER by c.categoria;");
  
        $result = $query->result();
        return $result;     
      }    

      public function upd_categoria_formapagocli_cuenta($id, $empresa, $idcuenta){
        $this->db->query("DELETE FROM con_cuentaclienteformapago 
                            WHERE idformapago = $id AND idempresa = $empresa;");
        if (($idcuenta != '') && ($idcuenta != 0)){                            
            $this->db->query("INSERT INTO con_cuentaclienteformapago (idformapago, idempresa, idcuenta)
                                VALUES($id, $empresa, $idcuenta);");
        }
      }      

      public function upd_categoria_formapagopro_cuenta($id, $empresa, $idcuenta){
        $this->db->query("DELETE FROM con_cuentaproveedorformapago 
                            WHERE idformapago = $id AND idempresa = $empresa;");
        if (($idcuenta != '') && ($idcuenta != 0)){                            
            $this->db->query("INSERT INTO con_cuentaproveedorformapago (idformapago, idempresa, idcuenta)
                                VALUES($id, $empresa, $idcuenta);");
        }
      }      

      public function upd_categoria_deposito_cuenta($id, $empresa, $idcuenta){
        if (($idcuenta == '') || ($idcuenta == 0)) {$idcuenta = 'NULL';}                            
        $this->db->query("UPDATE deposito_efectivo
                            SET idcuentacontable = $idcuenta 
                            WHERE id = $id;");
      }      

      public function sel_categoriatipotarjeta($empresa){
        $query = $this->db->query("SELECT t.id_tarjeta, t.nombre, c.idcuenta,
                                          p.codigocuenta, p.descripcion
                                      FROM tarjetas t
                                      LEFT JOIN con_cuentaclientetarjeta c on c.idtarjeta = t.id_tarjeta and
                                                                              c.idempresa = $empresa and c.idformapago = 3/* tarjeta credito */ 
                                      LEFT JOIN con_plancuenta p on p.id = c.idcuenta                                                                              
                                      ORDER by t.nombre;");
  
        $result = $query->result();
        return $result;     
      }    

      public function upd_categoria_tarjeta_cuenta($id, $empresa, $idcuenta){
        $this->db->query("DELETE FROM con_cuentaclientetarjeta 
                            WHERE idtarjeta = $id AND idempresa = $empresa;");
        if (($idcuenta != '') && ($idcuenta != 0)){                            
            $this->db->query("INSERT INTO con_cuentaclientetarjeta (idformapago, idtarjeta, idempresa, idcuenta)
                                VALUES(3, $id, $empresa, $idcuenta);");
        }
      }      

      public function sel_categoriagasto($empresa){
        $query = $this->db->query("SELECT t.id_cat_gas as id, t.nom_cat_gas as categoria, c.idcuenta,
                                          p.codigocuenta, p.descripcion
                                      FROM gastos_categorias t
                                      LEFT JOIN con_cuentacategoriagasto c on c.idcategoria = t.id_cat_gas and
                                                                              c.idempresa = $empresa 
                                      LEFT JOIN con_plancuenta p on p.id = c.idcuenta                                                                              
                                      ORDER by t.nom_cat_gas;");
  
        $result = $query->result();
        return $result;     
      }    

      public function upd_categoria_gasto_cuenta($id, $empresa, $idcuenta){
        $this->db->query("DELETE FROM con_cuentacategoriagasto 
                            WHERE idcategoria = $id AND idempresa = $empresa;");
        if (($idcuenta != '') && ($idcuenta != 0)){                            
            $this->db->query("INSERT INTO con_cuentacategoriagasto (idcategoria, idempresa, idcuenta)
                                VALUES($id, $empresa, $idcuenta);");
        }
      }      

      public function limpia_categoria_nombre(){
        $this->db->query("DELETE FROM con_categoria 
                            WHERE trim(categoria) = '';");

      }      

    public function lst_categoriacliente(){
        $query = $this->db->query("SELECT c.id, c.categoria
                                      FROM con_categoria c
                                      WHERE c.idtipocategoria  in (1) 
                                      ORDER by c.categoria;");
  
        $result = $query->result();
        return $result;     
     }    

    public function lst_categoriaproveedor(){
        $query = $this->db->query("SELECT c.id, c.categoria
                                      FROM con_categoria c
                                      WHERE c.idtipocategoria  in (2) 
                                      ORDER by c.categoria;");
  
        $result = $query->result();
        return $result;     
     }    

    public function lst_categoriaproducto(){
        $query = $this->db->query("SELECT c.id, c.categoria
                                      FROM con_categoria c
                                      WHERE c.idtipocategoria  in (3) 
                                      ORDER by c.categoria;");
  
        $result = $query->result();
        return $result;     
     }    

}
