<?php

/* ------------------------------------------------
  ARCHIVO: Pedido_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Pedido.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */

class Pedido_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    /* OBTENER TODAS LAS AREAS */
    public function lst_area(){
      $query = $this->db->query("SELECT id_area, nom_area FROM area");
      $r = $query->result();
      return $r;
    }

    /* OBTENER TODAS LAS MESAS DE ACUERDO A SUS AREAS */
    public function lst_mesa(){
      $query = $this->db->query("SELECT m.id_mesa, m.nom_mesa, m.id_area, m.capacidad, 
                                (SELECT COUNT(*) as cliente FROM pedido WHERE id_mesa = m.id_mesa) AS cliente,
                                (SELECT COUNT(*) as cliente FROM pedido_detalle WHERE id_mesa = m.id_mesa) AS pedido,
                                (SELECT id_cliente FROM pedido WHERE id_mesa = m.id_mesa) AS id_cliente,
                                id_estado
                                FROM mesa m");
      $r = $query->result();
      return $r;
    }

    /* SELECCIONAR NOMBRE DE LA MESA Y NOMBRE DEL AREA PARA EL ENCABEZADO DEL PEDIDO */
    public function mesa_area($id_mesa){
      $sql_ma = $this->db->query("SELECT m.id_mesa, m.nom_mesa, a.nom_area 
                                  FROM mesa m
                                  INNER JOIN area a ON a.id_area = m.id_area
                                  WHERE id_mesa = $id_mesa");
      $resultado = $sql_ma->result();
      return $resultado[0];
    }

    /* BUSQUEDA DE MESA POR ID PARA REALIZAR PEDIDO */
    public function mesa_pedido_id(){
      $query = $this->db->query(" SELECT m.id_mesa, m.nom_mesa, m.capacidad, a.nom_area 
                                  FROM mesa m
                                  INNER JOIN area a ON a.id_area = m.id_area");
    }

    public function valida_cliente($idcliente){
      $query = $this->db->query("SELECT id_cliente, nom_cliente, tipo_ident_cliente, ident_cliente, 
                                        nivel_est_cliente, ref_cliente, codigo,
                                        correo_cliente, ciudad_cliente, relacionado, direccion_cliente,  
                                        telefonos_cliente, mayorista, tipo_precio, credito, 
                                        placa_matricula, id_vendedor
                                FROM clientes
                                WHERE ident_cliente = '$idcliente'");
      $result = $query->result();
      if($result == NULL){
        $result = NULL;
        return $result;
      }else{
        return $result;
      }
    }

    public function valida_cliente_nombre(){
      $query = $this->db->query("SELECT nom_cliente FROM clientes");
      $result = $query->result();
//      if($result == NULL){
//        return 1;
//      }else{
        return $result;
//      }
    }

    /* BUSQUEDA POR NOMBRE */
    public function valida_nombre($nomcli){
      $query = $this->db->query("SELECT nom_cliente as nombre FROM clientes WHERE nom_cliente like '%$nomcli%'");
      $result = $query->result();
      return $result;
    }

    public function busca_cliente($nom){
      $query = $this->db->query("SELECT id_cliente, nom_cliente, tipo_ident_cliente, ident_cliente,  
                                        nivel_est_cliente, ref_cliente, codigo,
                                        correo_cliente, ciudad_cliente, relacionado, direccion_cliente,  
                                        telefonos_cliente, mayorista, tipo_precio,
                                        codigo, credito, placa_matricula, id_vendedor
                                FROM  clientes
                                WHERE nom_cliente = '$nom' ");
      $result = $query->result();
      if($result == NULL){
        $result = NULL;
        return $result;
      }else{
        return $result;
      }
    }

    public function pedido_cliente_detalle($idpro, $idalm, $id_mesa){

      $sql_add = $this->db->query("call pedido_det_ins($idpro, $idalm, $id_mesa)");
      $resultado = $sql_add->result();
      $idped = $resultado[0]->vid;

      $sql_add->next_result(); 
      $sql_add->free_result();      
      /*
      $sql_add = $this->db->query(" INSERT INTO pedido_detalle (id_mesa, id_producto, cantidad, precio, estatus, variante)
                                    SELECT $id_mesa, pro_id, 1, pro_precioventa, '0', habilitavariante FROM producto WHERE pro_id = $idpro");
      */
      $sql_var = $this->db->query("INSERT INTO pedido_detalle_variante (id_ped, id_mesa, id_producto, descripcion, cantidad)           
                                    SELECT $idped, $id_mesa, id_producto, descripcion, NULL 
                                      FROM productovariante WHERE id_producto = $idpro ");

      if ($idped > 0){
        $this->db->query("UPDATE mesa SET id_estado = 2 WHERE id_mesa = $id_mesa");
      }      

      $sql_sel = $this->db->query(" SELECT pd.id_ped, pd.id_mesa, pd.id_producto, p.pro_nombre, pd.cantidad, pd.precio, pd.estatus, pd.nota, pd.variante, p.pro_grabaiva, pd.est_comanda    
                                    FROM pedido_detalle pd
                                    INNER JOIN producto p ON p.pro_id = pd.id_producto
                                    WHERE pd.id_mesa = $id_mesa");
      $result = $sql_sel->result();
      return $result;
    }

    public function lista_pedido_cliente_detalle($id_mesa){

      $sql_sel = $this->db->query(" SELECT pd.id_ped, pd.id_mesa, pd.id_producto, p.pro_nombre, pd.cantidad, pd.precio, 
                                           pd.estatus, pd.nota, pd.variante, p.pro_grabaiva, p.comanda, pd.est_comanda, 
                                           p.pro_precioventa, pd.promo, p.maxitemvariante  
                                    FROM pedido_detalle pd
                                    INNER JOIN producto p ON p.pro_id = pd.id_producto
                                    WHERE pd.id_mesa = $id_mesa");
      $result = $sql_sel->result();
      return $result;
    }


    public function pedido_upd_detalle($id_mesa){
      $sql_sel = $this->db->query(" SELECT pd.id_ped, pd.id_mesa, pd.id_producto, p.pro_nombre, pd.cantidad, pd.precio, 
                                           pd.estatus, pd.nota, pd.variante, p.pro_grabaiva, p.comanda, pd.est_comanda, 
                                           p.pro_precioventa, pd.promo, p.maxitemvariante  
                                    FROM pedido_detalle pd
                                    INNER JOIN producto p ON p.pro_id = pd.id_producto
                                    WHERE pd.id_mesa = $id_mesa");
      $result = $sql_sel->result();
      return $result;
    }



    public function pedido_mesa_detalle($id_mesa){
      $sql_sel = $this->db->query(" SELECT pd.id_ped, pd.id_mesa, pd.id_producto, p.pro_nombre, pd.cantidad, pd.precio, 
                                           pd.estatus, pd.nota, pd.variante, p.pro_grabaiva, p.comanda, pd.est_comanda, 
                                           p.pro_precioventa, pd.promo, p.maxitemvariante  
                                    FROM pedido_detalle pd
                                    INNER JOIN producto p ON p.pro_id = pd.id_producto
                                    WHERE pd.id_mesa = $id_mesa ");

      $result = $sql_sel->result();
      return $result;
    }

    public function pedido_mesa_detallesum($id_mesa){
      $sql_sel = $this->db->query("SELECT pd.id_ped, pd.id_mesa, pd.id_producto, 
                                          p.pro_nombre, pd.estatus, pd.nota, pd.precio, 
                                          pd.variante, p.pro_grabaiva, p.comanda, pd.cantidad 
                                    FROM pedido_detalle pd
                                    INNER JOIN producto p ON p.pro_id = pd.id_producto
                                    WHERE pd.id_mesa = $id_mesa and pd.variante=1
                                   UNION 
                                   SELECT min(pd.id_ped) as id_ped, pd.id_mesa, pd.id_producto, 
                                          p.pro_nombre, pd.estatus, pd.nota, pd.precio,
                                          pd.variante, p.pro_grabaiva, p.comanda, 
                                          sum(pd.cantidad) as cantidad 
                                    FROM pedido_detalle pd
                                    INNER JOIN producto p ON p.pro_id = pd.id_producto
                                    WHERE pd.id_mesa = $id_mesa and pd.variante=0
                                    GROUP BY pd.id_mesa, pd.id_producto, 
                                           p.pro_nombre, pd.estatus, pd.nota, pd.precio,
                                           pd.variante, p.pro_grabaiva, p.comanda");
      $result = $sql_sel->result();
      return $result;
    }

    public function quitar_pedido($idreg, $id_mesa){
      $del = $this->db->query(" DELETE FROM pedido_detalle WHERE id_mesa = $id_mesa AND id_ped = $idreg ");

      $delvar = $this->db->query(" DELETE FROM pedido_detalle_variante WHERE id_mesa = $id_mesa AND id_ped = $idreg");

    }

    public function upd_precio($idreg, $idpro, $id_mesa, $cant, $precio, $promo){
      $query = $this->db->query("UPDATE pedido_detalle SET cantidad = $cant,  precio = $precio, promo = $promo
                                   WHERE id_mesa = $id_mesa AND id_producto = $idpro AND id_ped = $idreg");
      $sql_total = $this->db->query("SELECT  sum(cantidad * precio) as total FROM pedido_detalle WHERE id_mesa = $id_mesa");
      $total = $sql_total->result();
      return $total[0]->total;
    }

    public function upd_monto($id_mesa){
      $sql_total = $this->db->query("SELECT  sum(cantidad * precio) as total FROM pedido_detalle WHERE id_mesa = $id_mesa");
      $total = $sql_total->result();
      return $total[0];
    }

    /* MOSTRAR DATOS DEL CLIENTE EN EL PEDIDO CUANDO SE CARGA LA PAGINA */   
    public function mese_mesa($id_mesa){
      $sel_mesero = $this->db->query("SELECT id_mesero, nro_orden FROM pedido WHERE id_mesa = $id_mesa");
      $resultado = $sel_mesero->result();
      if($resultado == NULL){
        return 0;
      }else{
        return $resultado[0];
      }
    }

    /* CARGAR COMBO MESERO EN LA VISTA PEDIDO */
    public function mesero_lst(){
      $sql_mesero = $this->db->query("SELECT id_mesero, nom_mesero FROM mesero WHERE estatus_mesero = 'A'");
      $resultado = $sql_mesero->result();
      return $resultado;
    }

    /* GUARDAR O ACTUALIZAR REGISTRO DEL CLIENTE EN LA TABLA PEDIDO */
    public function reg_cli_mese($id_mesa, $identcliente, $idmesero){
      $selcli = $this->db->query("SELECT COUNT(*) AS valor FROM pedido WHERE id_mesa = $id_mesa");
      $resu_selcli = $selcli->result();
      $rescli = $resu_selcli[0]->valor;

      $this->db->query("UPDATE mesa SET id_estado = 2 WHERE id_mesa = $id_mesa");
      
      if($rescli == 0){
        $addrescli = $this->db->query("INSERT INTO pedido (id_mesa, id_cliente, id_mesero) VALUES ($id_mesa, $identcliente, $idmesero)");
        return 0;
      }else{
        $updrescli = $this->db->query(" UPDATE pedido SET id_cliente = $identcliente, id_mesero = $idmesero WHERE id_mesa = $id_mesa");
        return 1;
      }

    }

    /* GUARDAR O ACTUALIZAR REGISTRO DEL CLIENTE EN LA TABLA PEDIDO */
    public function reg_cliente($id_mesa, $identcliente){
      $selcli = $this->db->query("SELECT COUNT(*) AS valor FROM pedido WHERE id_mesa = $id_mesa");
      $resu_selcli = $selcli->result();
      $rescli = $resu_selcli[0]->valor;
      $idmesero = 0;
      
      if($rescli == 0){
        $addrescli = $this->db->query("INSERT INTO pedido (id_mesa, id_cliente, id_mesero) VALUES ($id_mesa, $identcliente, $idmesero)");
        $this->upd_mesero($id_mesa, $idmesero);

        $this->db->query("UPDATE mesa SET id_estado = 2 WHERE id_mesa = $id_mesa");

        return 0;
      }

    }

    /* GUARDAR O ACTUALIZAR REGISTRO DEL CLIENTE EN LA TABLA PEDIDO */
    public function upd_cliente($id_mesa, $identcliente){
      $selcli = $this->db->query("SELECT COUNT(*) AS valor FROM pedido WHERE id_mesa = $id_mesa");
      $resu_selcli = $selcli->result();
      $rescli = $resu_selcli[0]->valor;
      
      if($rescli > 0){
        $this->db->query(" UPDATE pedido SET id_cliente = $identcliente WHERE id_mesa = $id_mesa");
        $this->db->query("UPDATE mesa SET id_estado = 2 WHERE id_mesa = $id_mesa");

        return 0;
      }

    }

    /* ELIMINA EL REGISTRO DEL CLIENTE EN LA TABLA PEDIDO POR ID DE MESA */
    public function delmesacli($id_mesa){
      $sqldel = $this->db->query("DELETE FROM pedido WHERE id_mesa = $id_mesa");
    }

    /* ELIMINA EL REGISTRO DE LOS PRODUCTOS DE LA TABLA PEDIDO DETALLE POR ID DE MESA */
    public function delproped($id_mesa){
      $sql = $this->db->query("DELETE FROM pedido_detalle WHERE id_mesa = $id_mesa");

      $delvar = $this->db->query(" DELETE FROM pedido_detalle_variante WHERE id_mesa = $id_mesa");

    }

    /* ACTUALIZAR EN LA TABLA PEDIDO DETALLE EL NOMBRE DEL MESERO */
    public function upd_mesero($id_mesa, $idmesero){
      $sql = $this->db->query("SELECT COUNT(*) as cliente FROM pedido WHERE id_mesa = $id_mesa");
      $valor = $sql->result();
      $vercli = $valor[0]->cliente;
      if($vercli > 0){
        if($idmesero > 0){
          $meso = $this->db->query("UPDATE pedido SET id_mesero = $idmesero WHERE id_mesa = $id_mesa");
        }else{
          $usua = $this->session->userdata('usua');
          $id = $usua->id_usu;
          $meso = $this->db->query("UPDATE pedido SET id_mesero = IFNULL((SELECT id_mesero FROM usu_sistemas
                                                                            WHERE id_usu = $id), 0) 
                                      WHERE id_mesa = $id_mesa");
        }
        $sqlmese = $this->db->query("SELECT COUNT(id_mesero) AS mesero FROM pedido WHERE id_mesa = $id_mesa");
        $resu = $sqlmese->result();
        $val = $resu[0]->mesero;
        return $val;
      }
    }

    /* ACTUALIZAR EN LA TABLA PEDIDO DETALLE Las observaciones */
    public function upd_observaciones($id_mesa, $obs){
      $this->db->query("UPDATE pedido SET observaciones = '$obs' WHERE id_mesa = $id_mesa");
      return 1;
    }

    /* BUSQUEDA DE LOS PRODUCTOS VARIANTE PARA MOSTRARLOS EN VENTANA */
    public function selprovar($idreg, $id_mesa){
      $provar = $this->db->query("SELECT pdv.id_ped, pdv.id_producto, p.pro_nombre, pdv.descripcion, pdv.cantidad, 
                                         p.maxitemvariante * pd.cantidad as maxitemvariante
                                  FROM pedido_detalle_variante pdv
                                  INNER JOIN pedido_detalle pd on pd.id_ped=pdv.id_ped AND pd.id_mesa=pdv.id_mesa 
                                  INNER JOIN producto p ON pdv.id_producto = p.pro_id
                                  WHERE pdv.id_ped = $idreg AND pdv.id_mesa = $id_mesa");
/*      $provar = $this->db->query("SELECT pdv.id_ped, pdv.id_producto, p.pro_nombre, pdv.descripcion, pdv.cantidad 
                                  FROM pedido_detalle_variante pdv
                                  INNER JOIN producto p ON pdv.id_producto = p.pro_id
                                  WHERE pdv.id_ped = $idreg AND pdv.id_mesa = $id_mesa");*/
      $resultado = $provar->result();
      return $resultado;
    }

    public function updvar_cantidad($idreg, $id_pro, $id_mesa, $desc, $cant){

      $sql = $this->db->query(" UPDATE pedido_detalle_variante SET cantidad = $cant
                                WHERE id_ped = $idreg
                                AND id_producto = $id_pro
                                AND id_mesa = $id_mesa
                                AND descripcion = '$desc'");
    }

    /* AÑADIR NOTA AL PEDIDO id_ped*/
    public function updpro_nota($id_pro, $id_mesa, $nota_pro, $idped){
      $sql = $this->db->query("UPDATE pedido_detalle SET nota = '$nota_pro' WHERE id_mesa = $id_mesa AND id_producto = $id_pro AND id_ped = $idped ");
    }

    /* BUSCAR NOTA */
    public function busca_nota($id_pro, $id_mesa, $idped){
      $sql = $this->db->query("SELECT nota FROM pedido_detalle WHERE id_mesa = $id_mesa AND id_producto = $id_pro AND id_ped = $idped ");
      $resultado = $sql->result();
      $nota = $resultado[0]->nota;
      return $nota;
    }

    /* ACTUALIZA EL ESTADOS DEL PEDIDO */
    public function upd_est($id_pro, $idped, $id_mesa, $est){
      $sql = $this->db->query("UPDATE pedido_detalle SET estatus = '$est' WHERE id_mesa = $id_mesa AND id_producto = $id_pro AND id_ped = $idped");
    }

    /* MOSTRAR MESA-MESERO EN EL PEDIDO */   
    public function mesero_mesa($id_mesa){
      $sel_dato = $this->db->query("SELECT mesa.nom_mesa, s.nom_mesero, p.nro_orden
                                  FROM  pedido p
                                  LEFT JOIN mesa ON mesa.id_mesa = p.id_mesa
                                  LEFT JOIN mesero s ON s.id_mesero = p.id_mesero
                                  WHERE p.id_mesa = $id_mesa");
      $resultado = $sel_dato->result();
      return $resultado;
    }
    
    public function datocliente_mesa($id_mesa){
      $sel_cliente = $this->db->query("SELECT cl.id_cliente, cl.nom_cliente, cl.tipo_ident_cliente, cl.ident_cliente, cl.nivel_est_cliente, cl.ref_cliente, p.id_mesero, 
                                         cl.correo_cliente, cl.ciudad_cliente, cl.relacionado, cl.direccion_cliente,  cl.telefonos_cliente, cl.mayorista, cl.tipo_precio
                                  FROM  pedido p
                                  LEFT JOIN clientes cl ON p.id_cliente = cl.id_cliente
                                  WHERE p.id_mesa = $id_mesa");
      $resultado = $sel_cliente->result();
      return $resultado;
    }   

    /* LISTADO DE PRODUCTOS */
    public function lst_pro(){
    	$sql = $this->db->query("SELECT pro_id, pro_codigobarra, pro_codigoauxiliar, pro_nombre, pro_precioventa,
                                      ifnull(existencia,0) as existencia, ifnull(m.id_alm,0) as id_alm, 
                                      ifnull(almacen_nombre, '') as almacen_nombre, ifnull(preparado, 0) as preparado,
                                      ifnull(pro_esservicio, 0) as esservicio, habilitavariante                                      
                               FROM producto p
                               LEFT JOIN almapro m on m.id_pro = p.pro_id
                               LEFT JOIN almacen a on a.almacen_id = m.id_alm
                               WHERE pro_apliventa = 1
                               ORDER BY pro_nombre ASC");
    	$resu = $sql->result();
    	return $resu;
    }

    public function destinoimpresion_comanda($id_mesa){
      $sel_obj = $this->db->query("SELECT distinct c.id_comanda, c.impresora
                                  FROM  pedido_detalle d
                                  inner JOIN producto p on p.pro_id = d.id_producto
                                  inner JOIN comanda c on c.id_comanda = p.comanda
                                  WHERE d.estatus = 0 and d.id_mesa = $id_mesa");
      $resultado = $sel_obj->result();
      return $resultado;
    }   

    /* ACTUALIZA EL ESTADOS DEL PEDIDO 
    public function upd_est($id_pro, $id_mesa, $est){
      $sql = $this->db->query("UPDATE pedido_detalle SET estatus = '$est' WHERE id_mesa = $id_mesa AND id_producto = $id_pro");
    }
*/
    public function bus_mesero($id_mesa){
      $sql = $this->db->query("SELECT id_mesero, id_cliente FROM pedido where id_mesa = $id_mesa");
      $resu = $sql->result();
      if($resu  != NULL){
        return $resu[0];        
      }else{
        return $resu;
      }

    }

    public function iva(){
      $sql = $this->db->query("SELECT valor FROM parametros WHERE id = 1");
      $resu = $sql->result();
      return $resu[0];
    }

    /* OBTENER MESAS Ocupadas */
    public function lst_mesaocupada(){
      $query = $this->db->query("SELECT distinct p.id_mesa, CONCAT(a.nom_area,' - ',m.nom_mesa) AS areamesa
                                  FROM pedido p
                                  INNER JOIN mesa m ON m.id_mesa = p.id_mesa
                                  INNER JOIN area a ON a.id_area = m.id_area
                                  order by a.nom_area, m.nom_mesa");
      $r = $query->result();
      return $r;
    }

    /* OBTENER MESAS Libres */
    public function lst_mesalibre(){
      $query = $this->db->query("SELECT distinct m.id_mesa, CONCAT(a.nom_area,' - ',m.nom_mesa) AS areamesa
                                  FROM mesa m 
                                  INNER JOIN area a ON a.id_area = m.id_area
                                  where not id_mesa in (select distinct id_mesa from pedido)
                                  order by a.nom_area, m.nom_mesa");
      $r = $query->result();
      return $r;
    }


    /* CAMBIAR MESA */
    public function cambiarmesa($id_ocupada, $id_libre){
      $this->db->query("UPDATE pedido SET id_mesa = $id_libre where id_mesa = $id_ocupada;");
      $this->db->query("UPDATE pedido_detalle SET id_mesa = $id_libre where id_mesa = $id_ocupada;");
      $this->db->query("UPDATE pedido_detalle_variante SET id_mesa = $id_libre where id_mesa = $id_ocupada;");
      $this->db->query("UPDATE pedido_formapago SET id_mesa = $id_libre where id_mesa = $id_ocupada;");
      $this->db->query("UPDATE mesa SET id_estado = 1 WHERE id_mesa = $id_ocupada");
    }
       
    /* CAMBIAR ESTADO MESA */
    public function cambiar_estadomesa($mesa, $estado){
      if ($estado == 1) { $estado = 3; } else { $estado = 1; }
      $this->db->query("UPDATE mesa SET id_estado = $estado WHERE id_mesa = $mesa");
      return $estado;
    }

    /* LIMPIAR MESA */
    public function limpia_mesa($id_mesa, $obs){
      $this->db->query("INSERT INTO mesa_limpia (id_mesa, observacion)VALUES ($id_mesa, '$obs')");
      $this->db->query("DELETE FROM pedido WHERE id_mesa = $id_mesa");
      $this->db->query("DELETE FROM pedido_detalle WHERE id_mesa = $id_mesa");
      $this->db->query("DELETE FROM pedido_detalle_variante WHERE id_mesa = $id_mesa");
      $this->db->query("UPDATE mesa SET id_estado = 1 WHERE id_mesa = $id_mesa");
    }


    /* VERIFICAR MESERO ANTES DE FACTURAR */
    public function verifica_mesero($id_mesa){
        $sqlmese = $this->db->query("SELECT COUNT(id_mesero) AS mesero FROM pedido WHERE id_mesa = $id_mesa");
        $resu = $sqlmese->result();
        $val = $resu[0]->mesero;
        return $val;      
    }

    /* NOMBRE DEL MESERO A MESA */ 
    public function elmese(){
      $sql = $this->db->query(" SELECT m.id_mesero, m.nom_mesero, p.id_mesa  
                                FROM pedido p
                                INNER JOIN mesero m ON p.id_mesero = m.id_mesero ");
      $resu = $sql->result();
      return $resu;
    }

    /* LISTADO DE PRODUCTOS PARA PEDIDOS */
    public function productos(){
      $sql = $this->db->query(" SELECT  pro_id as id, 
                                        null as pro_imagen, p.imagen_path,
                                        pro_nombre as producto, 
                                        pro_precioventa as precio, 
                                        pro_idcategoria as idcat, 
                                        ifnull(existencia,0) as existencia, ifnull(m.id_alm,0) as id_alm, 
                                        ifnull(almacen_nombre, '') as almacen_nombre, ifnull(preparado, 0) as preparado,
                                        ifnull(pro_esservicio, 0) as esservicio, habilitavariante 
                                   FROM producto p
                                   LEFT JOIN almapro m on m.id_pro = p.pro_id
                                   LEFT JOIN almacen a on a.almacen_id = m.id_alm
                                   WHERE pro_apliventa = 1 AND a.almacen_tipo = 1
                                   ORDER BY pro_nombre ASC ");
      $resu = $sql->result();
      return $resu;
    }


    /* OBTENER EL ID MESERO A PARTIR DEL ID USUARIO */
    public function obtmesero($idusu){
      $sql = $this->db->query("SELECT id_mesero FROM usu_sistemas WHERE perfil = 2 AND id_usu = $idusu");
      $resu = $sql->result();
      return $resu[0]->id_mesero;
    }

    /* GUARDAR O ACTUALIZAR REGISTRO DEL CLIENTE EN LA TABLA PEDIDO DESDE SER */
    public function regis_cliente($id_mesa, $identcliente, $idmesero){
      $selcli = $this->db->query("SELECT COUNT(*) AS valor FROM pedido WHERE id_mesa = $id_mesa");
      $resu_selcli = $selcli->result();
      $rescli = $resu_selcli[0]->valor;
      
      if($rescli == 0){
        $addrescli = $this->db->query("INSERT INTO pedido (id_mesa, id_cliente, id_mesero) VALUES ($id_mesa, $identcliente, $idmesero)");
      //  $this->upd_mesero($id_mesa, $idmesero);
        return 0;
      }

    }    

    public function carga_cliente($idmesa){
      $sql = $this->db->query("SELECT COUNT(*) AS val FROM pedido WHERE id_mesa = $idmesa");
      $cliven = $sql->result();
      $val = $cliven[0]->val;

      if($val == 0){
        $sqlord = $this->db->query("SELECT nro_orden FROM caja_efectivo");
        $resord = $sqlord->result();
        if ($resord != NULL){
          $nro_orden = $resord[0]->nro_orden;
        }
        else{
          $nro_orden = 1;
        }  
        $this->db->query("INSERT INTO pedido (id_mesa, id_cliente, nro_orden) VALUES ($idmesa, 1, $nro_orden)");
        $this->db->query("UPDATE caja_efectivo SET nro_orden = nro_orden + 1");
      } else {
        $this->db->query("UPDATE pedido SET id_cliente = 1 WHERE id_mesa = $idmesa AND ifnull(id_cliente,0) = 0");
      }
      $this->db->query("UPDATE mesa SET id_estado = 2 WHERE id_mesa = $idmesa");

      $sqlcli = $this->db->query("SELECT tipo_ident_cliente, ident_cliente, nom_cliente, telefonos_cliente,
                                         direccion_cliente, correo_cliente, ciudad_cliente, 
                                         pedido.observaciones
                                    FROM pedido, clientes
                                    WHERE id_mesa = $idmesa and clientes.id_cliente = pedido.id_cliente");
      $cliver = $sqlcli->result();
      return $cliver[0];      
    }

    /* DATOS DEL CLIENTE PARA FACTURAR */
    public  function data_cliente($nro_ident, $tipo_ident, $nom_cliente, $cor_cliente, $telf_cliente, $dir_cliente, $ciu_cliente){
      /* verificar que exista el cliente */
      $sqlcli = $this->db->query("SELECT COUNT(*) AS nrocli FROM clientes WHERE ident_cliente = '$nro_ident' ");
      $resucli = $sqlcli->result();
      $valcli = $resucli[0]->nrocli;

      if($valcli > 0){
        /* actualiza los datos del cliente */
        $sql_updc = $this->db->query("UPDATE clientes 
                                          SET tipo_ident_cliente = '$tipo_ident',
                                              nom_cliente = '$nom_cliente',
                                              ident_cliente = '$nro_ident',
                                              correo_cliente = '$cor_cliente',
                                              telefonos_cliente = '$telf_cliente',
                                              direccion_cliente = '$dir_cliente',
                                              ciudad_cliente = '$ciu_cliente'
                                        WHERE id_cliente!=1 and ident_cliente = '$nro_ident' ");    

      }else{
        if($cor_cliente != NULL || $cor_cliente = ""){}else{$cor_cliente = " ";} 
        if($telf_cliente != NULL || $telf_cliente = ""){}else{$telf_cliente = " ";}
        if($dir_cliente != NULL || $dir_cliente = ""){}else{$dir_cliente = " ";}
        
        $sql_addc = $this->db->query("INSERT INTO clientes (tipo_ident_cliente, nom_cliente, ident_cliente, correo_cliente, 
                                                            telefonos_cliente, direccion_cliente, ciudad_cliente) 
                                                    VALUES ('$tipo_ident', '$nom_cliente', '$nro_ident', '$cor_cliente',
                                                            ' $telf_cliente', '$dir_cliente', '$ciu_cliente')");

        $sqlcli = $this->db->query("SELECT max(id_cliente) as maxid FROM clientes");
        $resucli = $sqlcli->result();
        $newidcli = $resucli[0]->maxid;
        $this->db->query("UPDATE clientes SET idcategoriacontable = (SELECT id FROM con_categoria 
                                                                       WHERE idtipocategoria = 1 LIMIT 1)
                            WHERE id_cliente = $newidcli");
        
      }

      $sql = $this->db->query(" SELECT tipo_ident_cliente, ident_cliente, nom_cliente, correo_cliente,     
                                       telefonos_cliente, direccion_cliente, id_cliente, ciudad_cliente 
                                FROM clientes
                                WHERE ident_cliente = '$nro_ident'");
      $resu = $sql->result();
      return $resu[0];      


    }

    public function sel_cat(){
      $query = $this->db->query("SELECT cat_id, cat_descripcion, ifnull(menu,0) as menu FROM categorias");
      $result = $query->result();
      return $result;
    
    }    

    public function cajero_usuario($idusu){
      $query = $this->db->query("SELECT id_mesero FROM usu_sistemas WHERE id_usu = $idusu");
      $result = $query->result();
      return $result;
    }    

    public function estatus_comanda($idmesa){
      $this->db->query("UPDATE pedido_detalle SET est_comanda = 1 WHERE id_mesa = $idmesa");
    }   



}
