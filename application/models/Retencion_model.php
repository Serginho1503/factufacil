<?php

/* ------------------------------------------------
  ARCHIVO: Retencion_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a la Retencion.
  FECHA DE CREACIÓN: 19/03/2018
 * 
  ------------------------------------------------ */

class Retencion_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function sel_ret(){
      $query = $this->db->query(" SELECT id_cto_retencion, cod_cto_retencion, descripcion_retencion, porciento_cto_retencion, editablecompra
                                  FROM concepto_retencion");
      $result = $query->result();
      return $result;
    }

    public function sel_ret_id($idret){
      $query = $this->db->query(" SELECT id_cto_retencion, cod_cto_retencion, descripcion_retencion, porciento_cto_retencion, editablecompra
                                  FROM concepto_retencion WHERE id_cto_retencion = $idret");
      $result = $query->result();
      return $result[0];
    }

    public function ret_upd($idret, $codret, $porret, $descret, $cedit){
      $query = $this->db->query(" UPDATE concepto_retencion SET cod_cto_retencion = '$codret', 
                                                                descripcion_retencion = '$descret', 
                                                                porciento_cto_retencion = $porret, 
                                                                editablecompra = $cedit
                                                            WHERE id_cto_retencion = $idret");
    }

    public function ret_add($codret, $porret, $descret, $cedit){
        $query = $this->db->query("INSERT INTO concepto_retencion (cod_cto_retencion, descripcion_retencion, porciento_cto_retencion, editablecompra)
                                                            VALUES('$codret', '$descret', $porret, $cedit);");
    }

    public function ret_del($idret){
      $query = $this->db->query("DELETE FROM concepto_retencion WHERE id_cto_retencion = $idret");
    }

    /* Retenciones de Compra */
    public function retencioncompra_defaultadd($idcompra){
        $query = $this->db->query("select count(*) as cont from compra_retencion where id_compra=$idcompra");
        $result = $query->result();
        if ($result[0]->cont == 0){
          $query = $this->db->query("select d.id_comp, p.id_cto_retencion, c.fecha, c.montoice, c.id_sucursal,
                                           sum(case iva when 0 then d.descsubtotal else 0 end) as basenoiva,
                                           sum(case iva when 1 then d.descsubtotal else 0 end) as baseiva,
                                           c.montoiva, ifnull(r.porciento_cto_retencion,0) as porciento_cto_retencion, 
                                           sum(round(d.descsubtotal * ifnull(r.porciento_cto_retencion,0) / 100,2)) as valor_retrenta,
                                           100 as porciento_retencion_iva, 
                                           c.montoiva as valor_retencion_iva, 
                                           6 as id_porcentaje_retencion_iva
                                      from producto p
                                      inner join compra_det d on d.id_pro=p.pro_id
                                      inner join compra c on c.id_comp=d.id_comp
                                      left join concepto_retencion r on r.id_cto_retencion = p.id_cto_retencion
                                      where c.id_comp=$idcompra
                                      group by d.id_comp, p.id_cto_retencion, r.porciento_cto_retencion, c.fecha");

          $result = $query->result();
          $cant = 0;
          $id_comp_ret = 0;
          foreach ($result as $ret) {

            if ($cant == 0){
              $cant++;

              $query = $this->db->query("SELECT id_puntoemision from punto_emision where id_sucursal=$ret->id_sucursal LIMIT 1");
              $resret = $query->result();
              if ($resret)
                $ptoemision = $resret[0]->id_puntoemision;
              else
                $ptoemision = 0;

              $nroret = "";
              $query = $this->db->query("SELECT concat(cod_establecimiento,'-',cod_puntoemision,'-',LPAD(consecutivo_retencioncompra, 9, '0')) as numero
                                           from punto_emision where id_puntoemision=$ptoemision");
              /*$query = $this->db->query("select concat((select valor from parametros where id=4),'-',(select valor from parametros where id=5),'-',LPAD(consecutivo_retencioncompra, 9, '0')) as numero
                                           from sucursal where id_sucursal=$ret->id_sucursal");*/
              $resret = $query->result();
              if ($resret) {$nroret = $resret[0]->numero;}

              $query = $this->db->query("INSERT INTO compra_retencion (id_compra, nro_retencion, nro_autorizacion, fecha_retencion, id_puntoemision)
                                          VALUES($idcompra, '$nroret', '', '$ret->fecha', $ptoemision);");

              $query = $this->db->query("SELECT max(id_comp_ret) as maxid FROM compra_retencion");
              $resret = $query->result();
              $id_comp_ret = $resret[0]->maxid;
            }  

            if ($ret->id_cto_retencion) {
              $montoice = $ret->montoice;
              if (!$montoice) {$montoice = 0;}
              $query = $this->db->query("INSERT INTO compra_retencion_detrenta 
                                          (id_comp_ret, id_concepto_retencion, base_noiva, base_iva, 
                                           porciento_retencion_renta, valor_retencion_renta)
                                          VALUES($id_comp_ret, $ret->id_cto_retencion, $ret->basenoiva, $ret->baseiva + $montoice,
                                                 $ret->porciento_cto_retencion, $ret->valor_retrenta)");
            }  

            $query = $this->db->query("INSERT INTO compra_retencion_detiva 
                                        (id_comp_ret, id_porcentaje_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                        VALUES($id_comp_ret, $ret->id_porcentaje_retencion_iva, $ret->porciento_retencion_iva, $ret->valor_retencion_iva);");

            $sql = $this->db->query("UPDATE punto_emision SET consecutivo_retencioncompra = consecutivo_retencioncompra + 1 
                                       WHERE id_puntoemision = $ptoemision");

          }
        }          
    }

    public function ret_lst_comp(){
      $sql = $this->db->query("SELECT id_cto_retencion, CONCAT(cod_cto_retencion,' - ',descripcion_retencion) AS retencion, porciento_cto_retencion, editablecompra FROM concepto_retencion");
      $resu = $sql->result();
      return $resu;
    }

    public function lst_retenciondettmp_compra(){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sql = $this->db->query("SELECT id_detallerenta, CONCAT(cod_cto_retencion,' - ',descripcion_retencion) AS concepto,
                                      base_noiva, base_iva, porciento_retencion_renta, valor_retencion_renta 
                                 FROM compra_retencion_detrenta_tmp r 
                                 inner join concepto_retencion c on c.id_cto_retencion = r.id_concepto_retencion
                                 where r.id_usu = $idusu");
      $resu = $sql->result();
      return $resu;
    }

    public function retencionrentacompra_add($concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $query = $this->db->query("INSERT INTO compra_retencion_detrenta_tmp 
                                  (id_concepto_retencion, base_noiva, base_iva, porciento_retencion_renta, valor_retencion_renta, id_usu)
                                  VALUES($concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta, $idusu);");
    }

    public function retencioncompra_del($idretencion){
      $maxret = "1";
      $query = $this->db->query("select max(cast(SUBSTR(nro_retencion,9) as SIGNED)) as maxret from compra_retencion");
      $resret = $query->result();
      if ($resret != NULL) $maxret = $resret[0]->maxret;

      $nroret = "1";
      $ptoemision = 0;
      $query = $this->db->query("SELECT id_puntoemision, cast(SUBSTR(nro_retencion,9) as SIGNED) as numret 
                                   from compra_retencion where id_comp_ret = $idretencion");
      $resret = $query->result();
      if ($resret != NULL) {
        $nroret = $resret[0]->numret;
        $ptoemision = $resret[0]->id_puntoemision;
      }  

      if ($nroret == $maxret){
        $sql = $this->db->query("UPDATE punto_emision SET consecutivo_retencioncompra = $maxret 
                                   WHERE id_puntoemision = $ptoemision");
        
      }

      $sql = $this->db->query("DELETE FROM compra_retencion_detrenta where id_comp_ret = $idretencion");
      $sql = $this->db->query("DELETE FROM compra_retencion_detiva where id_comp_ret = $idretencion");
      $sql = $this->db->query("DELETE FROM compra_retencion where id_comp_ret = $idretencion");
    }

    public function retencionrentacompra_del($iddetalle){
      $sql = $this->db->query("DELETE FROM compra_retencion_detrenta_tmp where id_detallerenta = $iddetalle");
    }

    public function retencionrentacompra_upd($idretencion, $concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta){
      $query = $this->db->query("UPDATE compra_retencion_detrenta_tmp set
                                   id_concepto_retencion = $concepto, 
                                   base_noiva = $basenoiva,
                                   base_iva = $baseiva, 
                                   porciento_retencion_renta = $por100retrenta, 
                                   valor_retencion_renta = $valorretrenta
                                  WHERE id_detallerenta = $idretencion;");
    }

    public function sel_detalleretencioncompra($iddetalle){
      $query = $this->db->query("SELECT id_detallerenta, id_concepto_retencion, base_noiva, base_iva, porciento_retencion_renta, valor_retencion_renta
                                  FROM compra_retencion_detrenta_tmp 
                                  WHERE  id_detallerenta = $iddetalle;");
      $resu = $query->result();
      if ($resu) 
        return $resu[0];
      else
        return null; 
    }

    public function lst_subtotalretdisp_compra($iddetalle){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sql = $this->db->query("SELECT sum(base_noiva) as basenoiva_disp, 
                                      sum(base_iva) as baseiva_disp
                                 FROM compra_retencion_detrenta_tmp r 
                                 where (r.id_detallerenta <> $iddetalle) and id_usu = $idusu");
      $resu = $sql->result();
      return $resu;
    }

    public function retiva_lst_porciento(){
      $sql = $this->db->query("SELECT id_porc_ret_iva, codigo, round(porcentaje,0) as porcentaje FROM porcentaje_retencion_iva");
      $resu = $sql->result();
      return $resu;
    }

    public function get_proxnumeroretencion($ptoemision){
      $query = $this->db->query("SELECT consecutivo_retencioncompra
                                  from punto_emision where id_puntoemision=$ptoemision");
      $resret = $query->result();
      if ($resret) 
        return $resret[0]->consecutivo_retencioncompra;
      else
        return "";
    }

    public function get_proxnumeroretencion0($sucursal){
      $query = $this->db->query("SELECT consecutivo_retencioncompra FROM sucursal
                                   WHERE id_sucursal = $sucursal");
      $resret = $query->result();
      if ($resret) 
        return $resret[0]->consecutivo_retencioncompra;
      else
        return "";
    }

    public function retencioncompra_guardar($idretcompra, $autorizacion, $fecha, $iva10, $iva20, $iva30, $iva50, $iva70, $iva100, 
                                            $ptoemision, $nroretencion){

      $this->db->query("UPDATE compra_retencion set
                               nro_autorizacion = '$autorizacion', 
                               fecha_retencion = '$fecha'
                          WHERE id_comp_ret = $idretcompra");

      $query = $this->db->query("SELECT id_puntoemision from compra_retencion where id_comp_ret = $idretcompra");
      $resret = $query->result();
      if ($resret)
        $ptoemisionant = $resret[0]->id_puntoemision;
      else
        $ptoemisionant = 0;
      if ($ptoemision != $ptoemisionant){
        $nroret = "";
        $query = $this->db->query("SELECT concat(cod_establecimiento,'-',cod_puntoemision,'-',LPAD(consecutivo_retencioncompra, 9, '0')) as numero
                                     from punto_emision where id_puntoemision=$ptoemision");
        $resret = $query->result();
        if ($resret) {$nroret = $resret[0]->numero;}

        $this->db->query("UPDATE compra_retencion set 
                              id_puntoemision=$ptoemision,
                              nro_retencion = '$nroret'
                            WHERE id_comp_ret = $idretcompra");
        $this->db->query("UPDATE punto_emision SET consecutivo_retencioncompra = consecutivo_retencioncompra + 1 
                            WHERE id_puntoemision = $ptoemision");
      }  


      $sql = $this->db->query("DELETE FROM compra_retencion_detrenta WHERE id_comp_ret = $idretcompra");
      
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sql = $this->db->query("INSERT INTO compra_retencion_detrenta (id_comp_ret, id_concepto_retencion, base_noiva, base_iva, 
                                                                      porciento_retencion_renta, valor_retencion_renta)
                                 SELECT $idretcompra, id_concepto_retencion, base_noiva, base_iva, 
                                        porciento_retencion_renta, valor_retencion_renta
                                  FROM compra_retencion_detrenta_tmp
                                  WHERE id_usu = $idusu");

      $sql = $this->db->query("DELETE FROM compra_retencion_detrenta_tmp WHERE id_usu = $idusu");

      $sql = $this->db->query("DELETE FROM compra_retencion_detiva WHERE id_comp_ret = $idretcompra");

      if ($iva10 > 0){
        $sql = $this->db->query("INSERT INTO compra_retencion_detiva (id_comp_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretcompra, 1, $iva10 / 0.1, 10, $iva10)");
      }
      if ($iva20 > 0){
        $sql = $this->db->query("INSERT INTO compra_retencion_detiva (id_comp_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretcompra, 2, $iva20 / 0.2, 20, $iva20)");
      }
      if ($iva30 > 0){
        $sql = $this->db->query("INSERT INTO compra_retencion_detiva (id_comp_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretcompra, 3, $iva30 / 0.3, 30, $iva30)");
      }
      if ($iva50 > 0){
        $sql = $this->db->query("INSERT INTO compra_retencion_detiva (id_comp_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretcompra, 4, $iva50 / 0.5, 50, $iva50)");
      }
      if ($iva70 > 0){
        $sql = $this->db->query("INSERT INTO compra_retencion_detiva (id_comp_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretcompra, 5, $iva70 / 0.7, 70, $iva70)");
      }
      if ($iva100 > 0){
        $sql = $this->db->query("INSERT INTO compra_retencion_detiva (id_comp_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretcompra, 6, $iva100, 100, $iva100)");
      }

    }

    public function retencioncompra_cargardetalletmp($idcompra){
      $sql = $this->db->query("SELECT id_comp_ret FROM compra_retencion WHERE id_compra = $idcompra");
      $resret = $sql->result();
      if ($resret){
        $idretcompra = $resret[0]->id_comp_ret;
        $usua = $this->session->userdata('usua');
        $idusu = $usua->id_usu;

        $sql = $this->db->query("DELETE FROM compra_retencion_detrenta_tmp WHERE id_usu = $idusu");
        
        $sql = $this->db->query("INSERT INTO compra_retencion_detrenta_tmp (id_concepto_retencion, base_noiva, base_iva, 
                                                                            porciento_retencion_renta, valor_retencion_renta, id_usu)
                                   SELECT id_concepto_retencion, base_noiva, base_iva, 
                                          porciento_retencion_renta, valor_retencion_renta, $idusu
                                    FROM compra_retencion_detrenta
                                    WHERE id_comp_ret = $idretcompra");
      }
    }  

    public function retencionrentacompra_tmpretenido(){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sql = $this->db->query("SELECT ifnull((select sum(base_noiva + base_iva) 
                                                 from compra_retencion_detrenta_tmp WHERE id_usu = $idusu),0) as totalbaseretenido,
                                      ifnull((select sum(valor_retencion_renta) 
                                                 from compra_retencion_detrenta_tmp WHERE id_usu = $idusu),0) as totalretenido");
      $resu = $sql->result();
      return $resu[0];
    }

    public function sel_retencioncompra($idret){
      $sql = $this->db->query("SELECT id_compra, nro_retencion, nro_autorizacion, fecha_retencion, c.id_sucursal
                                 FROM compra_retencion r
                                 INNER JOIN compra c on c.id_comp = r.id_compra
                                 WHERE id_comp_ret = $idret");
      $resu = $sql->result();
      return $resu[0];
    }

    /* Fin Retenciones Compra*/

    /* Retenciones de GASTOS */
    public function retenciongastos_defaultadd($idgastos){
        $query = $this->db->query("select count(*) as cont from gastos_retencion where id_gastos=$idgastos");
        $result = $query->result();
        if ($result[0]->cont == 0){
          $query = $this->db->query("select c.id_gastos, c.fecha, id_sucursal,
                                           c.subtotalivacerodesc as basenoiva,
                                           c.subtotaldesc as baseiva,
                                           c.montoiva, 
                                           100 as porciento_retencion_iva, 
                                           c.montoiva as valor_retencion_iva, 
                                           6 as id_porcentaje_retencion_iva
                                      from gastos c 
                                      where c.id_gastos=$idgastos");

          $result = $query->result();
          foreach ($result as $ret) {
            $query = $this->db->query("SELECT id_puntoemision from punto_emision where id_sucursal=$ret->id_sucursal LIMIT 1");
            $resret = $query->result();
            if ($resret)
              $ptoemision = $resret[0]->id_puntoemision;
            else
              $ptoemision = 0;

            $nroret = "";
            $query = $this->db->query("SELECT concat(cod_establecimiento,'-',cod_puntoemision,'-',LPAD(consecutivo_retencioncompra, 9, '0')) as numero
                                         from punto_emision where id_puntoemision=$ptoemision");
            $resret = $query->result();
            $nroret = $resret[0]->numero;

            $query = $this->db->query("INSERT INTO gastos_retencion (id_gastos, nro_retencion, nro_autorizacion, fecha_retencion, id_puntoemision)
                                        VALUES($idgastos, '$nroret', '', '$ret->fecha', $ptoemision);");

            $query = $this->db->query("SELECT max(id_gastos_ret) as maxid FROM gastos_retencion");
            $resret = $query->result();
            $id_gastos_ret = $resret[0]->maxid;

            $query = $this->db->query("INSERT INTO gastos_retencion_detiva 
                                        (id_gastos_ret, id_porcentaje_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                        VALUES($id_gastos_ret, $ret->id_porcentaje_retencion_iva, $ret->porciento_retencion_iva, $ret->valor_retencion_iva);");

            $sql = $this->db->query("UPDATE punto_emision SET consecutivo_retencioncompra = consecutivo_retencioncompra + 1 
                                       WHERE id_puntoemision = $ptoemision");

          }
        }          
    }

    public function lst_retenciondettmp_gastos(){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sql = $this->db->query("SELECT id_detallerenta, CONCAT(cod_cto_retencion,' - ',descripcion_retencion) AS concepto,
                                      base_noiva, base_iva, porciento_retencion_renta, valor_retencion_renta 
                                 FROM gastos_retencion_detrenta_tmp r 
                                 inner join concepto_retencion c on c.id_cto_retencion = r.id_concepto_retencion
                                 where r.id_usu = $idusu");
      $resu = $sql->result();
      return $resu;
    }

    public function retencionrentagastos_add($concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $query = $this->db->query("INSERT INTO gastos_retencion_detrenta_tmp 
                                  (id_concepto_retencion, base_noiva, base_iva, porciento_retencion_renta, valor_retencion_renta, id_usu)
                                  VALUES($concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta, $idusu);");
    }

    public function retenciongastos_del($idretencion){
      $maxret = "1";
      $query = $this->db->query("select max(cast(SUBSTR(nro_retencion,9) as SIGNED)) as maxret from gastos_retencion");
      $resret = $query->result();
      if ($resret != NULL) $maxret = $resret[0]->maxret;

      $nroret = "1";
      $ptoemision = 0;
      $query = $this->db->query("SELECT id_puntoemision, cast(SUBSTR(nro_retencion,9) as SIGNED) as numret 
                                   from gastos_retencion where id_gastos_ret = $idretencion");
      $resret = $query->result();
      if ($resret != NULL) {
        $nroret = $resret[0]->numret;
        $ptoemision = $resret[0]->id_puntoemision;
      }  

      if ($nroret == $maxret){
        $sql = $this->db->query("UPDATE punto_emision SET consecutivo_retencioncompra = $maxret 
                                   WHERE id_puntoemision = $ptoemision");
        
      }

      $sql = $this->db->query("DELETE FROM gastos_retencion_detrenta where id_gastos_ret = $idretencion");
      $sql = $this->db->query("DELETE FROM gastos_retencion_detiva where id_gastos_ret = $idretencion");
      $sql = $this->db->query("DELETE FROM gastos_retencion where id_gastos_ret = $idretencion");
    }

    public function retencionrentagastos_del($iddetalle){
      $sql = $this->db->query("DELETE FROM gastos_retencion_detrenta_tmp where id_detallerenta = $iddetalle");
    }

    public function retencionrentagastos_upd($idretencion, $concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta){
      $query = $this->db->query("UPDATE gastos_retencion_detrenta_tmp set
                                   id_concepto_retencion = $concepto, 
                                   base_noiva = $basenoiva,
                                   base_iva = $baseiva, 
                                   porciento_retencion_renta = $por100retrenta, 
                                   valor_retencion_renta = $valorretrenta
                                  WHERE id_detallerenta = $idretencion;");
    }

    public function sel_detalleretenciongastos($iddetalle){
      $query = $this->db->query("SELECT id_detallerenta, id_concepto_retencion, base_noiva, base_iva, porciento_retencion_renta, valor_retencion_renta
                                  FROM gastos_retencion_detrenta_tmp 
                                  WHERE  id_detallerenta = $iddetalle;");
      $resu = $query->result();
      if ($resu) 
        return $resu[0];
      else
        return null; 
    }

    public function lst_subtotalretdisp_gastos($iddetalle){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sql = $this->db->query("SELECT sum(base_noiva) as basenoiva_disp, 
                                      sum(base_iva) as baseiva_disp
                                 FROM gastos_retencion_detrenta_tmp r 
                                 where (r.id_detallerenta <> $iddetalle) and id_usu = $idusu");
      $resu = $sql->result();
      return $resu;
    }

    public function retenciongastos_guardar($idretgastos, $autorizacion, $fecha, $iva10, $iva20, $iva30, $iva50, $iva70, $iva100,
                                            $ptoemision, $nroretencion){
      $query = $this->db->query("UPDATE gastos_retencion set
                                   nro_autorizacion = '$autorizacion', 
                                   fecha_retencion = '$fecha'
                                   WHERE id_gastos_ret = $idretgastos");

      $query = $this->db->query("SELECT id_puntoemision from gastos_retencion where id_gastos_ret = $idretgastos");
      $resret = $query->result();
      if ($resret)
        $ptoemisionant = $resret[0]->id_puntoemision;
      else
        $ptoemisionant = 0;
      if ($ptoemision != $ptoemisionant){
        $nroret = "";
        $query = $this->db->query("SELECT concat(cod_establecimiento,'-',cod_puntoemision,'-',LPAD(consecutivo_retencioncompra, 9, '0')) as numero
                                     from punto_emision where id_puntoemision=$ptoemision");
        $resret = $query->result();
        if ($resret) {$nroret = $resret[0]->numero;}

        $this->db->query("UPDATE gastos_retencion set 
                              id_puntoemision=$ptoemision,
                              nro_retencion = '$nroret'
                            WHERE id_gastos_ret = $idretgastos");
        $this->db->query("UPDATE punto_emision SET consecutivo_retencioncompra = consecutivo_retencioncompra + 1 
                            WHERE id_puntoemision = $ptoemision");
      }  
      

      $sql = $this->db->query("DELETE FROM gastos_retencion_detrenta WHERE id_gastos_ret = $idretgastos");
      
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sql = $this->db->query("INSERT INTO gastos_retencion_detrenta (id_gastos_ret, id_concepto_retencion, base_noiva, base_iva, 
                                                                      porciento_retencion_renta, valor_retencion_renta)
                                 SELECT $idretgastos, id_concepto_retencion, base_noiva, base_iva, 
                                        porciento_retencion_renta, valor_retencion_renta
                                  FROM gastos_retencion_detrenta_tmp
                                  WHERE id_usu = $idusu");

      $sql = $this->db->query("DELETE FROM gastos_retencion_detrenta_tmp WHERE id_usu = $idusu");

      $sql = $this->db->query("DELETE FROM gastos_retencion_detiva WHERE id_gastos_ret = $idretgastos");

      if ($iva10 > 0){
        $sql = $this->db->query("INSERT INTO gastos_retencion_detiva (id_gastos_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretgastos, 1, $iva10 / 0.1, 10, $iva10)");
      }
      if ($iva20 > 0){
        $sql = $this->db->query("INSERT INTO gastos_retencion_detiva (id_gastos_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretgastos, 2, $iva20 / 0.2, 20, $iva20)");
      }
      if ($iva30 > 0){
        $sql = $this->db->query("INSERT INTO gastos_retencion_detiva (id_gastos_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretgastos, 3, $iva30 / 0.3, 30, $iva30)");
      }
      if ($iva50 > 0){
        $sql = $this->db->query("INSERT INTO gastos_retencion_detiva (id_gastos_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretgastos, 4, $iva50 / 0.5, 50, $iva50)");
      }
      if ($iva70 > 0){
        $sql = $this->db->query("INSERT INTO gastos_retencion_detiva (id_gastos_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretgastos, 5, $iva70 / 0.7, 70, $iva70)");
      }
      if ($iva100 > 0){
        $sql = $this->db->query("INSERT INTO gastos_retencion_detiva (id_gastos_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretgastos, 6, $iva100, 100, $iva100)");
      }

    }

    public function retenciongastos_cargardetalletmp($idgastos){
      $sql = $this->db->query("SELECT id_gastos_ret FROM gastos_retencion WHERE id_gastos = $idgastos");
      $resret = $sql->result();
      if ($resret){
        $idretgastos = $resret[0]->id_gastos_ret;
        $usua = $this->session->userdata('usua');
        $idusu = $usua->id_usu;

        $sql = $this->db->query("DELETE FROM gastos_retencion_detrenta_tmp WHERE id_usu = $idusu");
        
        $sql = $this->db->query("INSERT INTO gastos_retencion_detrenta_tmp (id_concepto_retencion, base_noiva, base_iva, 
                                                                            porciento_retencion_renta, valor_retencion_renta, id_usu)
                                   SELECT id_concepto_retencion, base_noiva, base_iva, 
                                          porciento_retencion_renta, valor_retencion_renta, $idusu
                                    FROM gastos_retencion_detrenta
                                    WHERE id_gastos_ret = $idretgastos");
      }
    }  

    public function retencionrentagastos_tmpretenido(){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sql = $this->db->query("SELECT ifnull((select sum(base_noiva + base_iva) 
                                                 from gastos_retencion_detrenta_tmp WHERE id_usu = $idusu),0) as totalbaseretenido,
                                      ifnull((select sum(valor_retencion_renta) 
                                                 from gastos_retencion_detrenta_tmp WHERE id_usu = $idusu),0) as totalretenido");
      $resu = $sql->result();
      return $resu[0];
    }

    public function sel_retenciongasto($idret){
      $sql = $this->db->query("SELECT id_gastos, nro_retencion, nro_autorizacion, fecha_retencion, c.id_sucursal
                                 FROM gastos_retencion r
                                 INNER JOIN gastos c on c.id_gastos = r.id_gastos
                                 WHERE id_gastos_ret = $idret");
      $resu = $sql->result();
      return $resu[0];
    }

    /*Fin retencion Gastos*/

    /* Retenciones de Venta */
    public function retencionventa_defaultadd($idventa){
        $query = $this->db->query("select count(*) as cont from venta_retencion where id_venta=$idventa");
        $result = $query->result();
        if ($result[0]->cont == 0){
          $query = $this->db->query("select c.id_venta, p.id_cto_retencion, c.fecha,
                                           sum(case iva when 0 then d.descsubtotal else 0 end) as basenoiva,
                                           sum(case iva when 1 then d.descsubtotal else 0 end) as baseiva,
                                           sum(d.montoiva) as montoiva, r.porciento_cto_retencion, 
                                           sum(round(d.descsubtotal * ifnull(r.porciento_cto_retencion,0) / 100,2)) as valor_retrenta,
                                           100 as porciento_retencion_iva, 
                                           sum(d.montoiva) as valor_retencion_iva, 
                                           6 as id_porcentaje_retencion_iva
                                      from venta  c
                                      left join venta_detalle d on d.id_venta=c.id_venta 
                                      left join producto p on p.pro_id=d.id_producto
                                      left join concepto_retencion r on r.id_cto_retencion = p.id_cto_retencion
                                      where c.id_venta=$idventa
                                      group by c.id_venta, c.fecha, p.id_cto_retencion, r.porciento_cto_retencion");

          $result = $query->result();
          $id_venta_ret = 0;
          $cant = 0;
          foreach ($result as $ret) {
            if ($cant == 0){
              $cant++;

              $nroret = "";
              $query = $this->db->query("INSERT INTO venta_retencion (id_venta, nro_retencion, nro_autorizacion, fecha_retencion)
                                          VALUES($idventa, '$nroret', '', '$ret->fecha');");

              $query = $this->db->query("SELECT max(id_venta_ret) as maxid FROM venta_retencion");
              $resret = $query->result();
              $id_venta_ret = $resret[0]->maxid;
            }  

            if (($ret->id_cto_retencion != NULL) && ($ret->porciento_cto_retencion != NULL)){
              $query = $this->db->query("INSERT INTO venta_retencion_detrenta 
                                          (id_venta_ret, id_concepto_retencion, base_noiva, base_iva, 
                                           porciento_retencion_renta, valor_retencion_renta)
                                          VALUES($id_venta_ret, $ret->id_cto_retencion, $ret->basenoiva, $ret->baseiva,
                                                 $ret->porciento_cto_retencion, $ret->valor_retrenta)");
            }

            $query = $this->db->query("INSERT INTO venta_retencion_detiva 
                                        (id_venta_ret, id_porcentaje_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                        VALUES($id_venta_ret, $ret->id_porcentaje_retencion_iva, $ret->porciento_retencion_iva, $ret->valor_retencion_iva);");

          }
        }          
    }

    public function lst_retenciondettmp_venta(){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sql = $this->db->query("SELECT id_detallerenta, CONCAT(cod_cto_retencion,' - ',descripcion_retencion) AS concepto,
                                      base_noiva, base_iva, porciento_retencion_renta, valor_retencion_renta 
                                 FROM venta_retencion_detrenta_tmp r 
                                 inner join concepto_retencion c on c.id_cto_retencion = r.id_concepto_retencion
                                 where r.id_usu = $idusu");
      $resu = $sql->result();
      return $resu;
    }

    public function retencionrentaventa_add($concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $query = $this->db->query("INSERT INTO venta_retencion_detrenta_tmp 
                                  (id_concepto_retencion, base_noiva, base_iva, porciento_retencion_renta, valor_retencion_renta, id_usu)
                                  VALUES($concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta, $idusu);");
    }

    public function retencionventa_del($idretencion){
      $sql = $this->db->query("DELETE FROM venta_retencion_detrenta where id_venta_ret = $idretencion");
      $sql = $this->db->query("DELETE FROM venta_retencion_detiva where id_venta_ret = $idretencion");
      $sql = $this->db->query("DELETE FROM venta_retencion where id_venta_ret = $idretencion");
    }

    public function retencionrentaventa_del($iddetalle){
      $sql = $this->db->query("DELETE FROM venta_retencion_detrenta_tmp where id_detallerenta = $iddetalle");
    }

    public function retencionrentaventa_upd($idretencion, $concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta){
      $query = $this->db->query("UPDATE venta_retencion_detrenta_tmp set
                                   id_concepto_retencion = $concepto, 
                                   base_noiva = $basenoiva,
                                   base_iva = $baseiva, 
                                   porciento_retencion_renta = $por100retrenta, 
                                   valor_retencion_renta = $valorretrenta
                                  WHERE id_detallerenta = $idretencion;");
    }

    public function sel_detalleretencionventa($iddetalle){
      $query = $this->db->query("SELECT id_detallerenta, id_concepto_retencion, base_noiva, base_iva, porciento_retencion_renta, valor_retencion_renta
                                  FROM venta_retencion_detrenta_tmp 
                                  WHERE  id_detallerenta = $iddetalle;");
      $resu = $query->result();
      if ($resu) 
        return $resu[0];
      else
        return null; 
    }

    public function lst_subtotalretdisp_venta($iddetalle){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sql = $this->db->query("SELECT sum(base_noiva) as basenoiva_disp, 
                                      sum(base_iva) as baseiva_disp
                                 FROM venta_retencion_detrenta_tmp r 
                                 where (r.id_detallerenta <> $iddetalle) and id_usu = $idusu");
      $resu = $sql->result();
      return $resu;
    }


    public function retencionventa_guardar($idretventa, $nro_retencion, $autorizacion, $fecha, $iva10, $iva20, $iva30, $iva50, $iva70, $iva100){
      $query = $this->db->query("UPDATE venta_retencion set
                                   nro_retencion = '$nro_retencion',
                                   nro_autorizacion = '$autorizacion', 
                                   fecha_retencion = '$fecha'
                                   WHERE id_venta_ret = $idretventa");

      $sql = $this->db->query("DELETE FROM venta_retencion_detrenta WHERE id_venta_ret = $idretventa");
      
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sql = $this->db->query("INSERT INTO venta_retencion_detrenta (id_venta_ret, id_concepto_retencion, base_noiva, base_iva, 
                                                                      porciento_retencion_renta, valor_retencion_renta)
                                 SELECT $idretventa, id_concepto_retencion, base_noiva, base_iva, 
                                        porciento_retencion_renta, valor_retencion_renta
                                  FROM venta_retencion_detrenta_tmp
                                  WHERE id_usu = $idusu");

      $sql = $this->db->query("DELETE FROM venta_retencion_detrenta_tmp WHERE id_usu = $idusu");

      $sql = $this->db->query("DELETE FROM venta_retencion_detiva WHERE id_venta_ret = $idretventa");

      if ($iva10 > 0){
        $sql = $this->db->query("INSERT INTO venta_retencion_detiva (id_venta_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretventa, 1, $iva10 / 0.1, 10, $iva10)");
      }
      if ($iva20 > 0){
        $sql = $this->db->query("INSERT INTO venta_retencion_detiva (id_venta_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretventa, 2, $iva20 / 0.2, 20, $iva20)");
      }
      if ($iva30 > 0){
        $sql = $this->db->query("INSERT INTO venta_retencion_detiva (id_venta_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretventa, 3, $iva30 / 0.3, 30, $iva30)");
      }
      if ($iva50 > 0){
        $sql = $this->db->query("INSERT INTO venta_retencion_detiva (id_venta_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretventa, 4, $iva50 / 0.5, 50, $iva50)");
      }
      if ($iva70 > 0){
        $sql = $this->db->query("INSERT INTO venta_retencion_detiva (id_venta_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretventa, 5, $iva70 / 0.7, 70, $iva70)");
      }
      if ($iva100 > 0){
        $sql = $this->db->query("INSERT INTO venta_retencion_detiva (id_venta_ret, id_porcentaje_retencion_iva, base_retencion_iva, porciento_retencion_iva, valor_retencion_iva)
                                   VALUES ($idretventa, 6, $iva100, 100, $iva100)");
      }

    }

    public function retencionventa_cargardetalletmp($idventa){
      $sql = $this->db->query("SELECT id_venta_ret FROM venta_retencion WHERE id_venta = $idventa");
      $resret = $sql->result();
      if ($resret){
        $idretventa = $resret[0]->id_venta_ret;
        $usua = $this->session->userdata('usua');
        $idusu = $usua->id_usu;

        $sql = $this->db->query("DELETE FROM venta_retencion_detrenta_tmp WHERE id_usu = $idusu");
        
        $sql = $this->db->query("INSERT INTO venta_retencion_detrenta_tmp (id_concepto_retencion, base_noiva, base_iva, 
                                                                            porciento_retencion_renta, valor_retencion_renta, id_usu)
                                   SELECT id_concepto_retencion, base_noiva, base_iva, 
                                          porciento_retencion_renta, valor_retencion_renta, $idusu
                                    FROM venta_retencion_detrenta
                                    WHERE id_venta_ret = $idretventa");
      }
    }  

    public function retencionrentaventa_tmpretenido(){
      $usua = $this->session->userdata('usua');
      $idusu = $usua->id_usu;
      $sql = $this->db->query("SELECT ifnull((select sum(base_noiva + base_iva) 
                                                 from venta_retencion_detrenta_tmp WHERE id_usu = $idusu),0) as totalbaseretenido,
                                      ifnull((select sum(valor_retencion_renta) 
                                                 from venta_retencion_detrenta_tmp WHERE id_usu = $idusu),0) as totalretenido");
      $resu = $sql->result();
      return $resu[0];
    }

    public function sel_retencionventa($idret){
      $sql = $this->db->query("SELECT id_venta, nro_retencion, nro_autorizacion, fecha_retencion, c.id_sucursal
                                 FROM venta_retencion r
                                 INNER JOIN venta c on c.id_venta = r.id_venta
                                 WHERE id_venta_ret = $idret");
      $resu = $sql->result();
      return $resu[0];
    }

    /* Fin Retenciones Venta */

}
