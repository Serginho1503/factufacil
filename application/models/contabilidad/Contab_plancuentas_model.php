<?php

/* ------------------------------------------------
  ARCHIVO: Contab_PlanCuentas_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a Plan de Cuentas.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */

class Contab_PlanCuentas_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* lista de tipos de identificacion */
    public function lst_cuentassubordinadas($id) {
        if ($id <= 0) {
          $query = $this->db->query("SELECT id, ifnull(idempresa,0) as idempresa, idcuentasuperior, idgrupocuenta, 
                                            codigonivel, codigocuenta, descripcion, nivel, esmovimiento,
                                            naturaleza, activo, idusuariocreacion, fechacreacion, fechamodificacion
                                       FROM con_plancuenta 
                                       WHERE idcuentasuperior IS NULL;");
        } else {    
          $query = $this->db->query("SELECT id, ifnull(idempresa,0) as idempresa, idcuentasuperior, idgrupocuenta, 
                                            codigonivel, codigocuenta, descripcion, nivel, esmovimiento,
                                            naturaleza, activo, idusuariocreacion, fechacreacion, fechamodificacion
                                       FROM con_plancuenta 
                                       WHERE idcuentasuperior = $id;");
        }
        $result = $query->result();
        return $result;
    }

    public function sel_cuenta_id($id){
      $query = $this->db->query("SELECT c.id, ifnull(c.idempresa,0) as idempresa, c.idgrupocuenta, 
                                        IFNULL(c.idcuentasuperior,0) as idcuentasuperior, 
                                        c.codigonivel, c.codigocuenta, c.descripcion, c.nivel, c.esmovimiento,
                                        c.naturaleza, c.activo, c.idusuariocreacion, c.fechacreacion, c.fechamodificacion,
                                        IFNULL(s.codigocuenta,'') as codigosuperior,
                                        IFNULL(s.descripcion,'') as descripcionsuperior
                                    FROM con_plancuenta c
                                    LEFT JOIN con_plancuenta s on s.id = c.idcuentasuperior
                                    WHERE c.id = $id;");
      $result = $query->result();
      return $result[0];
    }

    public function lst_grupocuentas() {
        $query = $this->db->query("SELECT id, descripcion FROM con_grupocuenta ORDER BY id;");
        $result = $query->result();
        return $result;
    }

    public function lst_nivelempresa() {
        $query = $this->db->query("SELECT 0 as id_emp, 'GLOBAL' as nom_emp
                                   UNION
                                   SELECT id_emp, nom_emp FROM empresa 
                                    ORDER BY id_emp;");
        $result = $query->result();
        return $result;
    }

    public function lst_naturaleza() {
        $query = $this->db->query("SELECT 1 as id, 'DEUDORA' as descripcion
                                   UNION
                                   SELECT -1 as id, 'ACREEDORA' as descripcion");
        $result = $query->result();
        return $result;
    }

    public function existe_cuenta($id, $codigocuenta, $idempresa){
        $query = $this->db->query("SELECT count(id) as cant FROM con_plancuenta
                                     WHERE codigocuenta = '$codigocuenta' and 
                                           IFNULL(idempresa,0) = $idempresa and
                                           id <> $id");
        $result = $query->result();
        return $result[0]->cant;
    }

    public function add_cuenta($idempresa, $idcuentasuperior, $idgrupocuenta, $codigonivel, $codigocuenta, $descripcion, $nivel, $naturaleza, $activo){
        $usua = $this->session->userdata('usua');
        $idusu = $usua->id_usu;
        if (($idcuentasuperior == '') || ($idcuentasuperior == 0)) {
            $idcuentasuperior = 'NULL';
            $idempresa = 0;
        }

        $this->db->query("INSERT INTO con_plancuenta 
                            (idempresa, idcuentasuperior, idgrupocuenta, codigonivel, codigocuenta, 
                             descripcion, nivel, esmovimiento, naturaleza, activo, idusuariocreacion)
                            VALUES($idempresa, $idcuentasuperior, $idgrupocuenta, '$codigonivel', '$codigocuenta', 
                                  '$descripcion', $nivel, 1, $naturaleza, $activo, $idusu);");
        $query = $this->db->query("SELECT max(id) as maxid FROM con_plancuenta");
        $result = $query->result();
        $maxid = 0;
        if ($result) { $maxid = $result[0]->maxid; }
        $query = $this->db->query("UPDATE con_plancuenta set esmovimiento = 0 WHERE id = $idcuentasuperior;");
        return $maxid;
    }

    public function upd_cuenta($id, $idempresa, $idcuentasuperior, $idgrupocuenta, $codigonivel, $codigocuenta, 
                               $descripcion, $nivel, $naturaleza, $activo){
        if (($idcuentasuperior == '') || ($idcuentasuperior == 0)) {
            $idcuentasuperior = 'NULL';
            $idempresa = 0;
        }
        $this->db->query("UPDATE con_plancuenta SET
                                idempresa =$idempresa, 
                                idcuentasuperior = $idcuentasuperior, 
                                idgrupocuenta = $idgrupocuenta, 
                                codigonivel = '$codigonivel', 
                                codigocuenta = '$codigocuenta', 
                                descripcion = '$descripcion', 
                                naturaleza = $naturaleza, 
                                activo = $activo,
                                fechamodificacion = now()
                            WHERE id = $id");
        if ($activo == 0){
            $this->inactiva_cuenta($id);
        }    
        $this->upd_cuentahijos($id, $codigocuenta);
    }    

    public function upd_cuentahijos($id, $codigocuenta){
        $childs = $this->db->query("SELECT id, codigocuenta FROM con_plancuenta WHERE idcuentasuperior = $id");
        $reschild = $childs->result();
        if (count($reschild) > 0){
            foreach($reschild as $item){
                $this->db->query("UPDATE con_plancuenta 
                                    SET codigocuenta = concat('$codigocuenta','.',codigonivel) 
                                    WHERE id = $item->id");

                $this->upd_cuentahijos($item->id, $item->codigocuenta);               
            }
        }
    }

    public function tienesaldo_cuenta($id){
        $query = $this->db->query("SELECT count(*) as cant FROM con_saldo
                                     WHERE idcuenta = $id and saldo <> 0");
        $result = $query->result();
        return $result[0]->cant;
    }

    public function cuenta_tiene_operaciones($id){
        $query = $this->db->query("SELECT d.id FROM con_comprobantedetalle d
                                     INNER JOIN con_plancuenta c on c.id = d.idcuenta
                                     WHERE c.codigocuenta like concat(IFNULL((SELECT codigocuenta FROM con_plancuenta WHERE id = $id),''),'%')
                                     LIMIT 1");
        $result = $query->result();
        if ($result)
            return $result[0]->id;
        else
            return 0;    
    }

    public function del_cuenta($id){
        $childs = $this->db->query("SELECT id FROM con_plancuenta WHERE idcuentasuperior = $id");
        $reschild = $childs->result();
        if (count($reschild) > 0){
            foreach($reschild as $item){
                $this->del_cuenta($item->id);
            }
        }

        $query = $this->db->query("SELECT IFNULL(idcuentasuperior,0) as idcuentasuperior FROM con_plancuenta WHERE id = $id");
        $result = $query->result();
        $idsup = 0;
        if ($result) { $idsup = $result[0]->idcuentasuperior; }
        $this->db->query("DELETE FROM con_plancuenta WHERE id = $id");
        $query = $this->db->query("SELECT count(*) as cant FROM con_plancuenta WHERE id = $idsup");
        $result = $query->result();
        if ($result[0]->cant == 0){
          $this->db->query("UPDATE con_plancuenta set esmovimiento = 1 WHERE id = $idsup");
        }
    }    
    public function del_cuenta00($id){
        $query = $this->db->query("SELECT IFNULL(idcuentasuperior,0) as idcuentasuperior FROM con_plancuenta WHERE id = $id");
        $result = $query->result();
        $idsup = 0;
        if ($result) { $idsup = $result[0]->idcuentasuperior; }
        $this->db->query("DELETE FROM con_plancuenta WHERE id = $id");
        $query = $this->db->query("SELECT count(*) as cant FROM con_plancuenta WHERE id = $idsup");
        $result = $query->result();
        if ($result[0]->cant == 0){
          $this->db->query("UPDATE con_plancuenta set esmovimiento = 1 WHERE id = $idsup");
        }
    }

    public function inactiva_cuenta($id){
        $usua = $this->session->userdata('usua');
        $idusu = $usua->id_usu;

        $this->db->query("INSERT INTO con_plancuentainactivo (idcuenta, idempresa, idusuarioinactivacion, descripcion)
                            SELECT id, idempresa, $idusu, descripcion
                                FROM con_plancuenta
                                WHERE id = $id;");
    }

    public function lst_cuentacodigo($codigocuenta, $idempresa){
        $query = $this->db->query("SELECT id, ifnull(idempresa,0) as idempresa, idgrupocuenta, 
                                          IFNULL(idcuentasuperior,0) as idcuentasuperior, 
                                          codigonivel, codigocuenta, descripcion, nivel, 
                                          naturaleza
                                     FROM con_plancuenta 
                                     WHERE codigocuenta like '$codigocuenta%' and 
                                           (IFNULL(idempresa,0) = 0 OR IFNULL(idempresa,0) = $idempresa) and 
                                           esmovimiento = 1 and activo = 1");
        $result = $query->result();
        return $result;
    }

    public function sel_cuentacodigo00($codigocuenta, $idempresa){
        $query = $this->db->query("SELECT id, ifnull(idempresa,0) as idempresa, idgrupocuenta, 
                                          IFNULL(idcuentasuperior,0) as idcuentasuperior, 
                                          codigonivel, codigocuenta, descripcion, nivel, 
                                          naturaleza
                                     FROM con_plancuenta
                                     WHERE codigocuenta = '$codigocuenta' and 
                                           ($idempresa = 0 OR IFNULL(idempresa,0) = $idempresa)");
/*                                     (IFNULL(idempresa,0) = 0 OR IFNULL(idempresa,0) = $idempresa)");*/
                                     $result = $query->result();
        if ($result != NULL)
            return $result[0];
        else
            return NULL;    
    }
    public function sel_cuentacodigo($codigocuenta, $idempresa){
        $query = $this->db->query("SELECT id, ifnull(idempresa,0) as idempresa, idgrupocuenta, 
                                          IFNULL(idcuentasuperior,0) as idcuentasuperior, 
                                          codigonivel, codigocuenta, descripcion, nivel, 
                                          naturaleza
                                     FROM con_plancuenta
                                     WHERE codigocuenta = '$codigocuenta' and 
                                          (IFNULL(idempresa,0) = 0 OR IFNULL(idempresa,0) = $idempresa)");
                                     $result = $query->result();
        if ($result != NULL)
            return $result[0];
        else
            return NULL;    
    }


    public function sel_cuenta_ultimonivel($idempresa){
        $query = $this->db->query("SELECT c.id, ifnull(c.idempresa,0) as idempresa, c.idgrupocuenta, 
                                          IFNULL(c.idcuentasuperior,0) as idcuentasuperior, 
                                          c.codigonivel, c.codigocuenta, c.descripcion, c.nivel, c.esmovimiento,
                                          c.naturaleza, c.activo, c.idusuariocreacion, c.fechacreacion, c.fechamodificacion,
                                          IFNULL(s.codigocuenta,'') as codigosuperior,
                                          IFNULL(s.descripcion,'') as descripcionsuperior
                                      FROM con_plancuenta c
                                      LEFT JOIN con_plancuenta s on s.id = c.idcuentasuperior
                                      WHERE (IFNULL(c.idempresa, 0) = 0) OR (c.idempresa = $idempresa) AND
                                            c.esmovimiento = 1 and c.activo = 1;");
        $result = $query->result();
        return $result;
    }

    public function guardar_cuentas_importar($cuenta, $descrip){
        $separador = ".";
        $tmpstr = substr($cuenta,strlen($cuenta)-1);
        if ($tmpstr == $separador) {
          $cuenta = substr($cuenta,0,strlen($cuenta)-1);
        }
        var_dump($cuenta);

        $cant = $this->existe_cuenta(0, $cuenta, 0);
        $newcta = 0;
        if ($cant == 0){
            $arr = explode($separador, $cuenta);   
            $longitud = count($arr);
            if ($arr[$longitud-1] == '') { $longitud--; }
            if ($longitud == 1){
                $idcuentasuperior = 'NULL';
                $idgrupocuenta = $cuenta;
                $codigonivel = $cuenta;
                $codigocuenta = $cuenta;
                $nivel = 1; 
                $naturaleza = 1;
                $activo = 1;
                $newcta = $this->add_cuenta(0, $idcuentasuperior, $idgrupocuenta, $codigonivel, $codigocuenta, $descrip, 
                                            $nivel, $naturaleza, $activo);
            }    
            else{
                $tmpcuenta = "";
                foreach($arr as $key => $item){
                    if ($key + 1 < $longitud){
                        if ($key != 0) {$tmpcuenta .= $separador;}
                        $tmpcuenta .= $item;
                    }
                }    
                //var_dump($tmpcuenta);
                $ctasup = $this->sel_cuentacodigo($tmpcuenta, 0);
                if ($ctasup){
                    $idempresa = $ctasup->idempresa;
                    $idcuentasuperior = $ctasup->id;
                    $idgrupocuenta = $ctasup->idgrupocuenta;
                    $codigonivel = $arr[$longitud-1];
                    $codigocuenta = $cuenta;
                    $nivel = $ctasup->nivel + 1; 
                    $naturaleza = $ctasup->naturaleza;
                    $activo = 1;
                    $newcta = $this->add_cuenta($idempresa, $idcuentasuperior, $idgrupocuenta, $codigonivel, $codigocuenta, $descrip, $nivel, $naturaleza, $activo);
                }        
            }    
        }
        return $newcta;
    }      

}
