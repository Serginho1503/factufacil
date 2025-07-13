<?php

/* ------------------------------------------------
  ARCHIVO: Cliente_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al Cliente.
  FECHA DE CREACIÓN: 25/07/2017
 * 
  ------------------------------------------------ */

class Cliente_model extends CI_Model {

  function __construct() {
      parent::__construct();
  }

  /* CARGAR LISTADO DE PRECIOS */
  public function precios(){
    $query = $this->db->query("SELECT 0 as id_precios,'Tienda' as desc_precios 
                               UNION
                               SELECT id_precios, desc_precios FROM precios WHERE esta_precios = 'A'");
    $resultado = $query->result();
    return $resultado;
  }

  public function filtroprecios($idcli){
    $query = $this->db->query("SELECT p.id_precios, p.desc_precios
                                FROM (SELECT * FROM precios UNION SELECT 0, 'Precio de Venta', 'A','' ) p
                                LEFT JOIN cliente_tipoprecio c ON c.id_precio = p.id_precios AND c.id_cliente = $idcli
                                WHERE p.esta_precios = 'A' AND c.estatus <> 0");
    $resultado = $query->result();
    return $resultado;
  }

  /* INSERTA EL REGISTRO DEL CLIENTE */
  public function cli_add($tip_ide, $nro_ide, $niv, $nom, $correo, $telf, $ciu, $ref, $dir, $rel, $may, $pre, $ven, $codigo, 
                          $credito, $placa, $catcontable, $categventa){
    if ($categventa == '') { $categventa = 0; }
    $query = $this->db->query("call cliente_ins('$nom', '$tip_ide', '$nro_ide', '$niv', 
                                                '$ref', '$correo', '$ciu', $rel, '$dir',  
                                                '$telf', $may, $pre, $ven, '$codigo', $credito, '$placa', 
                                                $catcontable, $categventa);");
    $result = $query->result();
    $query->next_result(); 
    $query->free_result();     
    return $result[0]->idcli;
  }


  /* SELECCIONAR LE CLIENTE POR ID */
  public function sel_cli_id($idcli){
    $query = $this->db->query("SELECT id_cliente, nom_cliente, tipo_ident_cliente, ident_cliente, nivel_est_cliente, ref_cliente, 
                                      correo_cliente, ciudad_cliente, relacionado, direccion_cliente,  telefonos_cliente, 
                                      mayorista, tipo_precio, id_vendedor, codigo, credito, placa_matricula, 
                                      idcategoriacontable, id_categoriaventa
                                FROM clientes
                                WHERE id_cliente = $idcli");
    $resultado = $query->result();
    return $resultado[0];
  }

  /* CARGAR TIPOS DE IDENTIFICACION */
  public function identificacion(){
    $query = $this->db->query("SELECT cod_identificacion, desc_identificacion FROM identificacion");
    $resultado = $query->result();
    return $resultado;
  }

  /* ACTUALIZAR LOS REGISTROS DEL CLIENTE */
  public function cli_upd($idcli, $tip_ide, $nro_ide, $nivel, $nom, $correo, $telf, $ciu, $ref, $dir, $rel, $may, $pre, $ven, 
                          $codigo, $credito, $placa, $catcontable, $categventa){
    if ($categventa == '') { $categventa = 0; }
    $query = $this->db->query("call cliente_upd($idcli,'$nom', '$tip_ide', '$nro_ide', '$nivel',
                                                '$ref', '$correo', '$ciu', '$rel', '$dir', 
                                                '$telf', '$may', $pre, $ven, '$codigo', $credito, '$placa', 
                                                $catcontable, $categventa);");
    $result = $query->result();
    $query->next_result(); 
    $query->free_result();        
    return $result;
  }

  public function candel_cliente($id){
    $query = $this->db->query("SELECT count(*) as cant FROM venta WHERE id_cliente = $id");
    $result = $query->result();
    if ($result[0]->cant == 0){
      $query = $this->db->query("SELECT count(*) as cant FROM proforma WHERE id_cliente = $id");
      $result = $query->result();
    }
    if ($result[0]->cant == 0)
      { return 1; }
    else
      { return 0; }
  }

  public function cli_del($idcli){
    if ($this->candel_cliente($idcli) == 1){
      $query = $this->db->query("call cliente_del($idcli);");
      $result = $query->result();
      return $result;
    } else {
      return 0;
    }
  }

  /* ELIMINAR EL REGISTRO DEL ALMACEN SELECCIONADO */
  public function cli_del00($idcli){
    $query = $this->db->query("call cliente_del($idcli);");
    $result = $query->result();
    return $result;
    /*$query = $this->db->query("DELETE FROM clientes WHERE id_cliente = $idcli");*/
  }

  /* CARGAR CLIENTES */
  public function sel_cli(){
    $query = $this->db->query("SELECT id_cliente, tipo_ident_cliente, 
                                      TRIM(REPLACE(REPLACE(REPLACE(nom_cliente,'\t',''),'\n',''),'\r','')) as nom_cliente,                                       
                                      TRIM(REPLACE(REPLACE(REPLACE(REPLACE(ident_cliente,' ',''),'\t',''),'\n',''),'\r','')) as ident_cliente, 
                                      nivel_est_cliente, ref_cliente, correo_cliente, relacionado, 
                                      TRIM(REPLACE(REPLACE(REPLACE(REPLACE(ciudad_cliente,' ',''),'\t',''),'\n',''),'\r','')) as ciudad_cliente, 
                                      TRIM(REPLACE(REPLACE(REPLACE(REPLACE(direccion_cliente,' ',''),'\t',''),'\n',''),'\r','')) as direccion_cliente,
                                      telefonos_cliente, mayorista, tipo_precio, i.desc_identificacion
                                FROM clientes c
                                LEFT JOIN identificacion i on i.cod_identificacion = tipo_ident_cliente
                                WHERE id_cliente != 1 /* consumidor final */
                                order by nom_cliente ");
    $resultado = $query->result();
    return $resultado;
  }

  /* CARGAR CLIENTES */
  public function sel_clientecorreo($todos = 0){
    $query = $this->db->query("SELECT id_cliente, nom_cliente, tipo_ident_cliente, ident_cliente, nivel_est_cliente, ref_cliente, 
                                      correo_cliente, ciudad_cliente, relacionado, direccion_cliente,  telefonos_cliente, mayorista, tipo_precio
                                FROM clientes
                                WHERE (ifnull(mayorista,0) = 1) OR ($todos = 1) 
                                order by nom_cliente");
    $resultado = $query->result();
    return $resultado;
  }

  /* SELECCIONAR EL CLIENTE POR IDENTIF */
  public function existeIdentificacion($idcliente, $identificacion){
    $query = $this->db->query("SELECT count(*) as cant FROM clientes
                                 WHERE ident_cliente = '$identificacion' and id_cliente != $idcliente");
    $resultado = $query->result();
    return $resultado[0]->cant;
  }

  public function cli_precios($idcli){
    $sql = $this->db->query(" SELECT p.id_precios, p.desc_precios, IFNULL(c.estatus, 0) AS estatus
                              FROM (SELECT * FROM precios UNION SELECT 0, 'Precio de Venta', 'A', '' ) p
                              LEFT JOIN cliente_tipoprecio c ON c.id_precio = p.id_precios AND c.id_cliente = $idcli
                              WHERE p.esta_precios = 'A'
                              ORDER BY p.id_precios");
    $resu = $sql->result();
    return $resu;    
  }

  public function precio_cli($idcli, $arrpre){
    $this->db->query("DELETE FROM cliente_tipoprecio WHERE id_cliente = $idcli");
    foreach ($arrpre as $ar) {
      list($campo,$valor)=explode("-",$ar);
      $this->db->query("INSERT INTO cliente_tipoprecio (id_cliente, id_precio, estatus) VALUES ($idcli, $campo, $valor)");
    }
  }    

  public function vendedor(){
    $sql = $this->db->query(" SELECT id_usu, CONCAT(nom_usu,' ',ape_usu) AS vendedor FROM usu_sistemas 
                              WHERE perfil = 2 AND est_usu = 'A'");
    $res = $sql->result();
    return $res;
  }

  public function selprecios($idpre){

    $sql = $this->db->query("SELECT p.id_precios, p.desc_precios, IFNULL(c.estatus, 0) AS estatus
                              FROM (SELECT * FROM precios UNION SELECT 0, 'Precio de Venta', 'A','' ) p
                              LEFT JOIN cliente_tipoprecio c ON c.id_precio = p.id_precios AND c.id_cliente = 0
                              WHERE p.esta_precios = 'A' AND p.id_precios IN ($idpre)");
    $res = $sql->result();
    return $res;
  }

  public function selprecioproductos($idcliente){
    $sql = $this->db->query("SELECT p.pro_id, p.pro_codigobarra, p.pro_nombre, p.pro_precioventa, pp.id_precios, pp.monto,
                              (SELECT COUNT(*) FROM cliente_tipoprecio WHERE id_precio = 0 AND id_cliente = $idcliente AND estatus = 1) as habilitado_precioventa
                              FROM producto p
                              INNER JOIN prepro pp on pp.pro_id = p.pro_id
                              INNER JOIN precios ps on ps.id_precios = pp.id_precios
                              INNER JOIN cliente_tipoprecio c on c.id_precio = pp.id_precios
                              WHERE c.estatus = 1 AND ps.esta_precios = 'A' AND c.id_cliente = $idcliente
                              ORDER BY p.pro_nombre");
    $res = $sql->result();
    return $res;
  }

  /* SELECCIONAR LE CLIENTE CEDULA/RUC */
  public function sel_cli_identificacion($identificacion){
    $query = $this->db->query("SELECT id_cliente, nom_cliente, tipo_ident_cliente, ident_cliente, nivel_est_cliente, ref_cliente, 
                                      correo_cliente, ciudad_cliente, relacionado, direccion_cliente,  telefonos_cliente, 
                                      mayorista, tipo_precio, id_vendedor, codigo, credito, placa_matricula, idcategoriacontable,
                                      id_categoriaventa
                                FROM clientes
                                WHERE ident_cliente = '$identificacion'");
    $resultado = $query->result();
    if ($resultado)
      return $resultado[0];
    else
      return NULL;
  }

  public function sel_cli_nombre($nom){
    $query = $this->db->query("SELECT id_cliente, nom_cliente, tipo_ident_cliente, ident_cliente, nivel_est_cliente, ref_cliente, codigo,
                                    correo_cliente, ciudad_cliente, relacionado, direccion_cliente,  telefonos_cliente, mayorista, tipo_precio,
                                    codigo, credito, placa_matricula, idcategoriacontable
                              FROM  clientes
                              WHERE nom_cliente = '$nom' ");
    $result = $query->result();
    if($result){
      return $result[0];
    }else{
      return NULL;
    }
  }

  public function lst_categoria_venta(){
    $sql = $this->db->query("SELECT id, categoria, monto_minimo, icono_path FROM cliente_categoriaventa 
                               ORDER BY categoria");
    $resu = $sql->result();
    return $resu;
  }

  public function sel_categoria_venta_id($categoria){
    $sql = $this->db->query("SELECT id, categoria, monto_minimo, icono_path FROM cliente_categoriaventa 
                               WHERE id = $categoria");
    $resu = $sql->result();
    return $resu[0];
  }

  public function lst_categoriaventa_precios($categoria){
    $sql = $this->db->query("SELECT p.id_precios, desc_precios, 
                                    CASE WHEN t.id_precio IS NULL THEN 0 ELSE 1 END habilitado
                               FROM precios p
                               LEFT JOIN cliente_categoria_tipoprecio t ON t.id_precio = p.id_precios 
                                                                       AND t.id_categoria = $categoria
                               WHERE esta_precios = 'A'                                                                       
                               ORDER BY desc_precios");
    $resu = $sql->result();
    return $resu;
  }

  public function ins_categoriaventa($categoria, $precios){
    $this->db->insert('cliente_categoriaventa', array(
      'categoria'=> $categoria->categoria,
      'monto_minimo'=> $categoria->monto_minimo,
      'icono_path'=> $categoria->icono_path
    ));
    $this->db->select('max(id) as id');
    $this->db->from('cliente_categoriaventa');
    $query = $this->db->get();       
    $result = $query->result();
    if (count($result) > 0) {
      $id = $result[0]->id;
      $this->upd_categoriaventa_precios($id, $precios);
      return $id;
    }
    return 0;
  }  

  public function upd_categoriaventa($categoria, $precios){
    $this->db->update('cliente_categoriaventa', 
                      array('categoria' => $categoria->categoria,
                            'monto_minimo' => $categoria->monto_minimo,
                            'icono_path'=> $categoria->icono_path
                          ), 
                      array('id' => $categoria->id)
                      );
    $this->upd_categoriaventa_precios($categoria->id, $precios);
  }  

  public function upd_categoriaventa_precios($idcategoria, $precios){
    $this->db->delete('cliente_categoria_tipoprecio', array('id_categoria' => $idcategoria)); 
    foreach($precios as $precio){
      if ($precio->habilitado == 1){
        $this->db->insert('cliente_categoria_tipoprecio', array(
          'id_categoria'=> $idcategoria,
          'id_precio'=> $precio->id_precios
        ));
      }  
    }  
  }  

  public function candel_categoriaventa($id){
    $query = $this->db->query("SELECT count(*) as cant FROM clientes WHERE id_categoriaventa = $id");
    $result = $query->result();
    if ($result[0]->cant == 0)
      { return 1; }
    else
      { return 0; }
  }

  public function del_categoriaventa($id){
    if ($this->candel_categoriaventa($id) == 1){
      $this->db->delete('cliente_categoria_tipoprecio', array('id_categoria' => $id)); 
      $this->db->delete('cliente_categoriaventa', array('id' => $id)); 
      return 1;
    } else {
      return 0;
    }
  }

  public function sel_clientevendedor($vendedor = 0){
    $query = $this->db->query("SELECT c.id_cliente, c.nom_cliente, c.tipo_ident_cliente, c.ident_cliente, c.nivel_est_cliente, 
                                      c.ref_cliente, c.correo_cliente, c.ciudad_cliente, c.relacionado, c.direccion_cliente,  
                                      c.telefonos_cliente, c.mayorista, c.tipo_precio, c.id_vendedor, c.codigo, c.credito, 
                                      c.placa_matricula, c.idcategoriacontable, c.id_categoriaventa,
                                      cv.categoria as categoriaventa
                                FROM clientes c
                                INNER JOIN usu_sistemas u on u.id_usu = c.id_vendedor
                                LEFT JOIN cliente_categoriaventa cv on cv.id = c.id_categoriaventa
                                WHERE (u.id_mesero = $vendedor) 
                                order by c.nom_cliente");
    $resultado = $query->result();
    return $resultado;
  }

}
