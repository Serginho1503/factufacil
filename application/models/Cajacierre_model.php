<?php

/* ------------------------------------------------
  ARCHIVO: Cajacierre_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al cierre de Caja.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */

class Cajacierre_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* Determinar si existe Apertura */
    public function existeapertura() {
        $usua = $this->session->userdata('usua');
        $id = $usua->id_usu;
        $query = $this->db->query("SELECT count(*) as cantidad FROM caja_movimiento 
                                     where id_usuario=$id and estado=0");
        $result = $query->result();
        return true;// ($result[0]->cantidad > 0);
    }

    /* OBTENER TODOS LOS DATOS DE LA Apertura */
    public function datosapertura($id_caja) {
        $usua = $this->session->userdata('usua');
        $id = $usua->id_usu;

        $idmov= 0;
        $fecha=date("Y-m-d");
        $query = $this->db->query("SELECT cm.id_mov, cm.fecha_apertura FROM caja_movimiento cm 
                                     where cm.id_caja=$id_caja and estado=0");
        $result = $query->result();
        if ($result){ 
          $idmov = $result[0]->id_mov;
          $fecha = $result[0]->fecha_apertura;
        }

        $query = $this->db->query("SELECT IFNULL((select sum(montototal) from venta v
                                            where fecharegistro >= '$fecha' and
                                                  v.id_caja = $id_caja and v.estatus!=3),0) as ingreso");
        $result = $query->result();
        $ingreso = $result[0]->ingreso;

        $query = $this->db->query("SELECT IFNULL((select sum(f.monto) 
                                     FROM servicio_abono a
                                     INNER JOIN venta_formapago f on f.id = a.id_docpago
                                     where f.fecha >= '$fecha' and
                                           f.id_cajapago = $id_caja),0) as abonoservicio");
        $result = $query->result();
        $abonoservicio = $result[0]->abonoservicio;

        $query = $this->db->query("SELECT IFNULL((select sum(f.monto) 
                                     FROM venta_formapago f 
                                     INNER JOIN venta v on v.id_venta = f.id_venta
                                     INNER JOIN venta_credito c on c.id_venta = f.id_venta
                                     LEFT JOIN venta_creditoabonoinicial i on i.id_abono = f.id
                                     WHERE f.fecha >= '$fecha' AND v.fecha < '$fecha'
                                       AND f.id_cajapago = $id_caja AND (i.id_abono IS NULL)),0) as abonocredito");
        $result = $query->result();
        $abonocredito = $result[0]->abonocredito;

        $query = $this->db->query("SELECT IFNULL((select sum(f.monto) 
                                     FROM venta_formapago f 
                                     INNER JOIN venta v on v.id_venta = f.id_venta
                                     INNER JOIN venta_credito c on c.id_venta = f.id_venta
                                     LEFT JOIN venta_creditoabonoinicial i on i.id_abono = f.id
                                     WHERE f.fecha >= '$fecha' AND v.fecha < '$fecha'
                                       AND f.id_cajapago = $id_caja 
                                       AND f.id_formapago = 1 
                                       AND (i.id_abono IS NULL)),0) as abonocreditoefectivo");
        $result = $query->result();
        $abonocreditoefectivo = $result[0]->abonocreditoefectivo;

        $query = $this->db->query("SELECT ifnull((select sum(monto) from venta_formapago f 
                                            inner join venta v on v.id_venta = f.id_venta
                                            where f.fecha >= '$fecha'  and v.estatus!=3 and 
                                                  f.id_cajapago = $id_caja and f.id_formapago=1),0)  + 
                                          IFNULL((select sum(f.monto) 
                                            FROM servicio_abono a
                                            INNER JOIN venta_formapago f on f.id = a.id_docpago
                                            where f.fecha >= '$fecha' and f.id_cajapago = $id_caja
                                              and f.id_formapago=1),0) as ingresoefectivo");
        $result = $query->result();
        $ingresoefectivo = $result[0]->ingresoefectivo;

        $query = $this->db->query("SELECT ifnull((select sum(monto) from venta_formapago f 
                                            inner join venta v on v.id_venta = f.id_venta
                                            where f.fecha >= '$fecha'  and v.estatus!=3 and 
                                                  f.id_cajapago = $id_caja and f.id_formapago!=1),0) as ingresonoefectivo");
        $result = $query->result();
        $ingresonoefectivo = $result[0]->ingresonoefectivo;

        $query = $this->db->query("SELECT ifnull((select sum(montototal-abonoinicial) from venta v
                                            inner join venta_credito f on f.id_venta = v.id_venta     
                                            where fecharegistro >= '$fecha'  and v.estatus!=3 and 
                                                  v.id_caja = $id_caja),0) as credito");
        $result = $query->result();
        $credito = $result[0]->credito;

        $query = $this->db->query("SELECT cm.id_mov, cm.fecha_apertura, cm.monto_apertura, cm.id_caja,
                                          $ingreso as ingreso,
                                          $abonoservicio as abonoservicio,
                                          $abonocredito as abonocredito,
                                          $abonocreditoefectivo as abonocreditoefectivo,
                                          $ingresoefectivo as ingresoefectivo,
                                          $ingresonoefectivo as ingresonoefectivo,
                                          $credito as credito
                                          FROM caja_movimiento cm
                                          where cm.id_mov=$idmov");

/*
        $query = $this->db->query("SELECT cm.id_mov, cm.fecha_apertura, cm.monto_apertura, cm.id_caja,
                                          (select sum(montototal) from venta v
                                            where fecharegistro >= cm.fecha_apertura and
                                                  v.id_caja = cm.id_caja and v.estatus!=3) as ingreso,
                                          ifnull((select sum(monto) from venta_formapago f 
                                            inner join venta v on v.id_venta = f.id_venta
                                            where f.fecha >= cm.fecha_apertura  and v.estatus!=3 and 
                                                  f.id_cajapago = cm.id_caja and f.id_formapago=1),0) as ingresoefectivo,
                                          ifnull((select sum(monto) from venta_formapago f 
                                            inner join venta v on v.id_venta = f.id_venta
                                            where f.fecha >= cm.fecha_apertura  and v.estatus!=3 and 
                                                  f.id_cajapago = cm.id_caja and f.id_formapago!=1),0) as ingresonoefectivo,
                                          ifnull((select sum(montototal-abonoinicial) from venta v
                                            inner join venta_credito f on f.id_venta = v.id_venta     
                                            where fecharegistro >= cm.fecha_apertura  and v.estatus!=3 and 
                                                  v.id_caja = cm.id_caja),0) as credito
                                          FROM caja_movimiento cm
                                          where cm.id_caja=$id_caja and estado=0");
*/



        $result = $query->result();
        return $result[0];
    }

    /* OBTENER Montos de Venta por forma de pago */
    public function ventaformapago($idcaja) {
        $usua = $this->session->userdata('usua');
        $id = $usua->id_usu;
        
/*
left join venta v on v.id_venta = f.id_venta 
                                                 where f.id_formapago=fp.id_formapago and (v.id_venta is null or v.estatus!=3) and
                                                 
*/

        $query = $this->db->query("SELECT fp.id_formapago, fp.nombre_formapago, 
                                       IFNULL((SELECT sum(monto) from venta_formapago f                                               
                                                 inner join venta v on v.id_venta = f.id_venta and v.estatus!=3
                                                 where f.id_formapago=fp.id_formapago and 
                                                       f.id_venta is not null and id_cajapago = $idcaja and  
                                                       f.fecha >= (SELECT fecha_apertura FROM caja_movimiento cm 
                                                                    where cm.id_caja=$idcaja and estado=0)) ,0)  +       
                                       IFNULL((SELECT sum(monto) from venta_formapago f
                                                 inner join servicio_abono a on a.id_docpago = f.id    
                                                 where f.id_formapago=fp.id_formapago and 
                                                       f.id_venta is null and id_cajapago = $idcaja and  
                                                       f.fecha >= (SELECT fecha_apertura FROM caja_movimiento cm 
                                                                     where cm.id_caja=$idcaja and estado=0)) ,0) as monto 
                                    from formapago fp
                                    order by id_formapago");
/*
        $query = $this->db->query("SELECT fp.id_formapago, fp.nombre_formapago, IFNULL(sum(monto),0) as monto 
                                    from formapago fp
                                    left join venta_formapago f on f.id_formapago=fp.id_formapago and 
                                              f.id_venta is not null and id_cajapago = $idcaja and  
                                              f.fecha >= (SELECT fecha_apertura FROM caja_movimiento cm where cm.id_caja=$idcaja and estado=0)
                                    left join venta v on v.id_venta = f.id_venta and v.estatus!=3   
                                    group by fp.id_formapago
                                    order by fp.id_formapago");
                                    */
/*        $query = $this->db->query("select fp.id_formapago, fp.nombre_formapago, 
                                     (SELECT (select sum(monto) from venta_formapago f 
                                                inner join venta v on v.id_venta = f.id_venta    
                                                where f.fecha >= cm.fecha_apertura and
                                                      id_caja = cm.id_caja and v.estatus!=3 and f.id_formapago=fp.id_formapago)
                                         FROM caja_movimiento cm
                                         where cm.id_caja=$idcaja and estado=0) as monto 
                                    from formapago fp
                                    order by fp.id_formapago");*/
        $result = $query->result();
        return $result;
    }

    /* SE ACTUALIZAN LOS DATOS DE LA EMPRESA 
    public function guardar($idmov, $venta, $tarjeta, $egresos, $compras, $totalcaja, $obs, $salida, $justi){
        $venta = str_replace(',','',$venta);
        $tarjeta = str_replace(',','',$tarjeta);
        $egresos = str_replace(',','',$egresos);
        $compras = str_replace(',','',$compras);
        $totalcaja = str_replace(',','',$totalcaja);
        $salida = str_replace(',','',$salida);
        $usua = $this->session->userdata('usua');
        $idusuario = $usua->id_usu;
        $query = $this->db->query("call cajaapertura_upd($idmov, $idusuario, $venta, $tarjeta, $egresos, $compras, $totalcaja, '$obs', $salida, '$justi');");

        $query->next_result(); 
        $query->free_result();
    }
*/
    public function guardar($idmov, $compras, $obs, $salida, $justi, $ventastotales, $abonoservicio, $montonoefectivo, 
                            $montoegreso, $saldo, $totalcaja, $sobrante, $faltante, $desefectivo, $descheque, $destarcre, 
                            $destardeb, $destarpre, $destransf, $desdinele, $desotros, $desvencre,
                            $abonocredito){
        $usua = $this->session->userdata('usua');
        $idusuario = $usua->id_usu;
        $query = $this->db->query("call cajaapertura_upd($idmov, $idusuario, $compras, '$obs', $salida, '$justi', 
                                                         $ventastotales, $abonoservicio, $montonoefectivo, 
                                                         $montoegreso, $saldo, $totalcaja, $sobrante, $faltante, 
                                                         $desefectivo, $descheque, $destarcre, $destardeb, 
                                                         $destarpre, $destransf, $desdinele, $desotros, 
                                                         $desvencre, $abonocredito);");

        $query->next_result(); 
        $query->free_result();
    }
    public function addgastos($monto, $desc, $idmov, $idusu, $emi, $rec){
      $sql_add = $this->db->query("call cajagastos_ins ($idmov, $idusu, '$desc', $monto, '$emi','$rec')");
      $resultado = $sql_add->result();
      $id = $resultado[0]->vid_mov;
      $sql_add->next_result(); 
      $sql_add->free_result();  
      return $id;
    }


    public function selcajagastos($idmov, $idusu){
      $sql = $this->db->query("SELECT * FROM caja_egreso WHERE id_mov = $idmov"); 
      $resu = $sql->result();
      return $resu; 
    }    

    public function edicajagastos($idreg, $idmov, $idusu){
      $sql = $this->db->query("SELECT * FROM caja_egreso WHERE id_mov = $idmov AND idreg = $idreg");
      $resu = $sql->result();
      return $resu[0];       
    }

    public function updgastos($monto, $desc, $idmov, $idusu, $idreg, $emi, $rec){
      $sql = $this->db->query("UPDATE caja_egreso SET 
                                 descripcion = '$desc', 
                                 monto = $monto, 
                                 emisor = '$emi', 
                                 receptor = '$rec',
                                 id_usu = $idusu 
                                WHERE id_mov = $idmov AND idreg = $idreg");
    }

    public function delcajagastos($idreg, $idmov, $idusu){
      $sql = $this->db->query("DELETE FROM caja_egreso WHERE id_mov = $idmov AND idreg = $idreg");      
    }

    public function montogasto($idmov, $idusu){
      $sql = $this->db->query("SELECT SUM(monto) AS monto FROM caja_egreso WHERE id_mov = $idmov ");
      $resu = $sql->result();
      if ($resu[0]->monto) 
        {$monto = $resu[0]->monto;}
      else
        {$monto = 0;}
      return $monto;
    }

    public function lstegreso($idmov, $idusu){
      $sql = $this->db->query("SELECT * FROM caja_egreso WHERE id_mov = $idmov");
      $resu = $sql->result();
      return $resu;       
    }

    public function contaeg(){
      $sql = $this->db->query("SELECT valor FROM contador WHERE id_contador = 9");
      $resu = $sql->result();
      $val = $resu[0]->valor;
      return $val;
    }

    public function receg($idcg){
      $sql = $this->db->query("SELECT e.*, p.id_sucursal 
                                 FROM caja_egreso e 
                                 INNER JOIN caja_movimiento m on m.id_mov = e.id_mov
                                 INNER JOIN caja_efectivo c on c.id_caja = m.id_caja
                                 INNER JOIN punto_emision p on p.id_puntoemision = c.id_puntoemision 
                                 WHERE idreg = $idcg");
      $resu = $sql->result();
      return $resu[0];
    }

    /* Determinar si existen cajas abiertas */
    public function lst_cajaefectivo_abierta() {
        $usua = $this->session->userdata('usua');
        $id = $usua->id_usu;      
        $query = $this->db->query("SELECT id_caja, nom_caja
                                     FROM caja_efectivo e 
                                     WHERE id_caja in (SELECT m.id_caja FROM caja_movimiento m WHERE m.estado=0)
                                     AND id_caja IN (SELECT id_caja FROM permiso_cajaefectivo WHERE id_usuario = $id)
                                     ORDER BY nom_caja");
        $result = $query->result();
        return $result; 
    }

    public function existecajaefectivo_abierta($idcaja = 0) {
        $usua = $this->session->userdata('usua');
        $id = $usua->id_usu;      
        $query = $this->db->query("SELECT count(*) as cantidad
                                     FROM caja_efectivo e 
                                     WHERE ($idcaja = 0 OR e.id_caja = $idcaja)
                                       AND id_caja IN (SELECT id_caja FROM permiso_cajaefectivo WHERE id_usuario = $id)
                                       AND id_caja in (SELECT m.id_caja FROM caja_movimiento m WHERE m.estado=0)");


        $result = $query->result();
        $val = $result[0]->cantidad;
        if ($val > 0){
          return 1; 
        }else{
          return 0; 
        }
    }


}
