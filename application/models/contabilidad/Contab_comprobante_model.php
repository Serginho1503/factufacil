<?php

/* ------------------------------------------------
  ARCHIVO: Contab_comprobante_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes a comprobantes.
 * 
  ------------------------------------------------ */

class Contab_comprobante_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }      

    /* comprobantes */
    public function sel_comprobantes($empresa=0, $sucursal=0, $desde, $hasta){
        $query = $this->db->query("SELECT c.id, c.idempresa, c.idsucursal, c.idtipocomprobante, c.idejercicio, 
                                          c.numero, c.referencia, c.fechaasiento, c.idusuarioregistro, c.idestado, 
                                          c.monto, c.descripcion, /*c.iddocreferencia,*/
                                          e.estado, t.nombre as tipocomprobante, s.nom_sucursal 
                                     FROM con_comprobante c
                                     INNER JOIN sucursal s on s.id_sucursal = c.idsucursal
                                     INNER JOIN con_estadocomprobante e on e.id = c.idestado
                                     INNER JOIN con_tipocomprobante t on t.id = c.idtipocomprobante
                                     WHERE c.fechaasiento BETWEEN '$desde' AND '$hasta' AND
                                           ($empresa = 0 OR c.idempresa = $empresa) AND
                                           ($sucursal = 0 OR c.idsucursal = $sucursal)
                                     ORDER by c.numero");
        $result = $query->result();
        return $result;     
    }

    public function sel_tipoasiento(){
        $query = $this->db->query("SELECT id, nombre, abreviatura, prefijo
                                     FROM con_tipocomprobante
                                     ORDER by id");
        $result = $query->result();
        return $result;     
    }

    public function sel_tipoasiento_id($id){
        $query = $this->db->query("SELECT id, nombre, abreviatura
                                     FROM con_tipocomprobante
                                     WHERE id = $id");
        $result = $query->result();
        return $result[0];     
    }

    public function sel_asientostmp($token){
        $query = $this->db->query("SELECT t.id, t.idcuenta, t.codigocuenta, t.debitocredito, t.valor, t.concepto,
                                          c.descripcion  
                                     FROM con_tmpcomprobantedetalle t
                                     LEFT JOIN con_plancuenta c on c.id = t.idcuenta
                                     WHERE token = '$token'
                                     ORDER by id");
        $result = $query->result();
        return $result;     
    }

    public function add_detalle($token){
        $this->db->query("INSERT INTO con_tmpcomprobantedetalle (token, codigocuenta, debitocredito, valor, concepto) 
                            Values('$token', '', 1, 0, '');");
        $query = $this->db->query("SELECT max(id) as maxid  FROM con_tmpcomprobantedetalle");
        $result = $query->result();
        return $result[0]->maxid;     
    }

    public function  upd_tmpasiento_cuenta($asiento, $idcuenta, $codcuenta){
        $this->db->query("UPDATE con_tmpcomprobantedetalle 
                            SET idcuenta = $idcuenta, codigocuenta = '$codcuenta'
                            WHERE id = $asiento;");
    }      

    public function  upd_tmpasiento_concepto($asiento, $concepto){
        $this->db->query("UPDATE con_tmpcomprobantedetalle 
                            SET concepto = '$concepto'
                            WHERE id = $asiento;");
    }      
    
    public function  upd_tmpasiento_valor($asiento, $valor, $esdebito){
        $this->db->query("UPDATE con_tmpcomprobantedetalle 
                            SET valor = $valor, debitocredito = $esdebito
                            WHERE id = $asiento;");
    }    

    public function del_tmpasiento($asiento){
        $this->db->query("DELETE FROM con_tmpcomprobantedetalle WHERE id = $asiento;");
    }    

    public function add_comprobante($token, $idsucursal, $idtipocomprobante, $referencia, $fecha, 
                                    $idusuario, $monto, $descripcion, $iddocreferencia = 0){
        $estado = 1;//($iddocreferencia == 0) ? 1 : 2;                                

        $query = $this->db->query("SELECT prefijo, contador FROM con_tipocomprobante t
                                     INNER JOIN con_tipocomprobante_sucursal s on s.idtipo = t.id
                                     WHERE t.id = $idtipocomprobante AND idsucursal = $idsucursal");
        $resultado = $query->result();
        $referencia = $resultado[0]->prefijo . str_pad($resultado[0]->contador,9,"0",STR_PAD_LEFT);

        $this->db->query("INSERT INTO con_comprobante (idempresa, idsucursal, idtipocomprobante, idejercicio, 
                                                       numero, referencia, fechaasiento, idusuarioregistro, 
                                                       idestado, monto, descripcion) 
                            SELECT (SELECT id_empresa FROM sucursal WHERE id_sucursal = $idsucursal) as idempresa, 
                                   $idsucursal, $idtipocomprobante, 
                                   (SELECT id FROM con_ejercicio WHERE '$fecha' BETWEEN inicio AND fin) as idejercicio, 
                                   IFNULL((SELECT max(numero) FROM con_comprobante
                                            WHERE idsucursal = $idsucursal AND
                                                  idejercicio = (SELECT id FROM con_ejercicio 
                                                                  WHERE '$fecha' BETWEEN inicio AND fin)),0) + 1 as numero, 
                                   '$referencia', date('$fecha'), $idusuario, $estado, 
                                   $monto, '$descripcion';");
        $query = $this->db->query("SELECT max(id) as id FROM con_comprobante");
        $resultado = $query->result();
        $newid = 0;
        if ($resultado){           
            $newid = $resultado[0]->id;

            $this->db->query("UPDATE con_tipocomprobante_sucursal SET contador = contador + 1
                                WHERE idtipo = $idtipocomprobante AND idsucursal = $idsucursal");

            $this->db->query("INSERT INTO con_comprobantedetalle (idcomprobante, idcuenta, debitocredito, 
                                                                  valor, concepto) 
                                SELECT $newid, idcuenta, debitocredito, valor, concepto
                                    FROM con_tmpcomprobantedetalle
                                    WHERE token = '$token';");
            $this->db->query("DELETE FROM con_tmpcomprobantedetalle WHERE token = '$token';");
            // Eliminar temporal con mas de 2 dias
            $this->db->query("DELETE FROM con_tmpcomprobantedetalle 
                                WHERE TIMESTAMPDIFF(MINUTE, fechacreacion, now()) > 7200;");
        }
        return $newid; 
    }

    public function upd_comprobante($id, $token, $idsucursal, $idtipocomprobante, $referencia, $fecha, 
                                    $idusuario, $monto, $descripcion, $iddocreferencia = 0){
        $estado = 1;//($iddocreferencia == 0) ? 1 : 2;                                
        $this->db->query("UPDATE con_comprobante SET 
                            idempresa = (SELECT id_empresa FROM sucursal WHERE id_sucursal = $idsucursal), 
                            idsucursal = $idsucursal, 
                            idtipocomprobante = $idtipocomprobante, 
                            idejercicio = (SELECT id FROM con_ejercicio WHERE '$fecha' BETWEEN inicio AND fin), 
                            referencia = '$referencia', 
                            fechaasiento = '$fecha', 
                            idusuarioregistro = $idusuario, 
                            idestado = $estado, 
                            monto = $monto, 
                            descripcion = '$descripcion'/*, 
                            iddocreferencia = $iddocreferencia*/
                          WHERE id = $id;");

        $this->db->query("DELETE FROM con_comprobantedetalle WHERE idcomprobante = $id;");
        $this->db->query("INSERT INTO con_comprobantedetalle (idcomprobante, idcuenta, debitocredito, 
                                                              valor, concepto) 
                            SELECT $id, idcuenta, debitocredito, valor, concepto
                                FROM con_tmpcomprobantedetalle
                                WHERE token = '$token';");
        $this->db->query("DELETE FROM con_tmpcomprobantedetalle WHERE token = '$token';");
        // Eliminar temporal con mas de 2 dias
        $this->db->query("DELETE FROM con_tmpcomprobantedetalle 
                            WHERE TIMESTAMPDIFF(MINUTE, fechacreacion, now()) > 2880;");
        return $id; 
    }

    public function sel_comprobante_id($id){
        $query = $this->db->query("SELECT c.id, c.idempresa, c.idsucursal, c.idtipocomprobante, c.idejercicio, 
                                          c.numero, c.referencia, c.fechaasiento, c.idusuarioregistro, 
                                          c.idestado, c.monto, c.descripcion, /*c.iddocreferencia, */
                                          e.estado
                                     FROM con_comprobante c
                                     INNER JOIN con_estadocomprobante e on e.id = c.idestado
                                     WHERE c.id = $id");
        $resultado = $query->result();
        return $resultado[0];
      }

    public function sel_comprobante_anulacion($id){
        $query = $this->db->query("SELECT idusuario, fechaanulacion, motivoanulacion
                                     FROM con_comprobanteanulado
                                     WHERE idcomprobante = $id");
        $resultado = $query->result();
        return $resultado[0];
    }

    public function cargar_tmpdetalle($id, $token){
        $this->db->query("DELETE FROM con_tmpcomprobantedetalle WHERE token = '$token';");
        $this->db->query("INSERT INTO con_tmpcomprobantedetalle (token, idcuenta, codigocuenta,
                                                                 debitocredito, valor, concepto) 
                            SELECT '$token', d.idcuenta, c.codigocuenta, d.debitocredito, d.valor, d.concepto
                                FROM con_comprobantedetalle d
                                INNER JOIN con_plancuenta c on c.id = d.idcuenta
                                WHERE d.idcomprobante = $id;");
        $query = $this->db->query("SELECT sum(CASE debitocredito WHEN 1 THEN valor ELSE 0 END) as debito,
                                          sum(CASE debitocredito WHEN 0 THEN valor ELSE 0 END) as credito
                                    FROM con_comprobantedetalle
                                    WHERE idcomprobante = $id");
        $resultado = $query->result();
        if ($resultado)
            return $resultado[0];
        else
            return NULL;    
    } 

    public function sel_comprobantedetalle($idcmp){
        $query = $this->db->query("SELECT d.idcuenta, c.codigocuenta, c.descripcion, d.concepto,
                                          CASE d.debitocredito WHEN 1 THEN d.valor ELSE 0 END as debito,
                                          CASE d.debitocredito WHEN 0 THEN d.valor ELSE 0 END as credito        
                                    FROM con_comprobantedetalle d
                                    INNER JOIN con_plancuenta c on c.id = d.idcuenta
                                    WHERE d.idcomprobante = $idcmp;");
        $querysum = $this->db->query("SELECT sum(CASE debitocredito WHEN 1 THEN valor ELSE 0 END) as debito,
                                             sum(CASE debitocredito WHEN 0 THEN valor ELSE 0 END) as credito
                                        FROM con_comprobantedetalle
                                        WHERE idcomprobante = $idcmp");
        $detalle = $query->result();
        $suma = $querysum->result();
        return array('detalles' => $detalle, 'suma' => $suma);
    } 

    public function candel_comprobante($id){
        $query = $this->db->query("SELECT count(*) as cant FROM con_comprobante 
                                     WHERE id = $id AND idestado > 1");
        $result = $query->result();
        if ($result[0]->cant == 0)
          { return 1; }
        else
          { return 0; }
      }
  
    public function del_comprobante($id){
        if ($this->candel_comprobante($id)){
            $this->db->delete('con_comprobantedocumento', array('idcomprobante' => $id)); 
            $this->db->delete('con_comprobantedetalle', array('idcomprobante' => $id)); 
            $this->db->delete('con_comprobante', array('id' => $id)); 
            return 1;                    
        }                        
        else{ 
            return 0; 
        }
    }

    public function validar_comprobante($id){
        $query = $this->db->query("SELECT sum(CASE debitocredito WHEN 1 THEN valor ELSE 0 END) as debito,
                                          sum(CASE debitocredito WHEN 0 THEN valor ELSE 0 END) as credito
                                    FROM con_comprobantedetalle
                                    WHERE idcomprobante = $id");
        $resultado = $query->result();
        if ($resultado)
            return (($resultado[0]->debito == $resultado[0]->credito) && ($resultado[0]->debito * $resultado[0]->credito != 0));
        else
            return false;    
    }

    public function confirmar_comprobante($id){
        $this->db->query("UPDATE con_comprobante SET idestado = 2 WHERE id = $id");
        $this->db->query("INSERT INTO con_saldo (idsucursal, idcuenta, saldo)
                            SELECT Distinct c.idsucursal, d.idcuenta, 0
                                FROM con_comprobantedetalle d                                                                      
                                INNER JOIN con_comprobante c on c.id = d.idcomprobante  
                                LEFT JOIN con_saldo s on s.idsucursal = c.idsucursal AND s.idcuenta = d.idcuenta
                                WHERE d.idcomprobante = $id AND s.saldo is NULL");
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcuenta;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcuenta (id int, valor decimal(11,2));");
        $this->db->query("INSERT INTO tblcuenta 
                            SELECT d.idcuenta, SUM(d.valor * (CASE d.debitocredito WHEN 1 THEN 1 ELSE -1 END))
                                FROM con_comprobantedetalle d                                                                      
                                WHERE d.idcomprobante = $id
                                GROUP BY d.idcuenta");
        $this->db->query("UPDATE con_saldo s
                            INNER JOIN tblcuenta c on c.id = s.idcuenta 
                            SET s.saldo = s.saldo + c.valor
                            WHERE s.idsucursal = (SELECT idsucursal FROM con_comprobante WHERE id = $id)");

/*        $this->db->query("UPDATE con_saldo s
                           INNER JOIN con_comprobantedetalle d on d.idcuenta = s.idcuenta                                                                     
                           INNER JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                           c.idsucursal = s.idsucursal
                           SET s.saldo = s.saldo + d.valor * (CASE d.debitocredito WHEN 1 THEN 1 ELSE -1 END)  
                           WHERE d.idcomprobante = $id");*/
        return 1;    
    }

    public function confirmar_cmp_rango($sucursal, $desde, $hasta){
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcmp;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcmp (id int, idsucursal int);");
        $this->db->query("INSERT INTO tblcmp 
                            SELECT id, idsucursal FROM con_comprobante
                            WHERE idsucursal = $sucursal AND idestado = 1 AND 
                                  fechaasiento BETWEEN '$desde' AND '$hasta'");
        $this->db->query("UPDATE con_comprobante SET idestado = 2 
                            WHERE idsucursal = $sucursal AND idestado = 1 AND 
                                  fechaasiento BETWEEN '$desde' AND '$hasta'");
        $this->db->query("INSERT INTO con_saldo (idsucursal, idcuenta, saldo)
                            SELECT Distinct c.idsucursal, d.idcuenta, 0
                                FROM con_comprobantedetalle d                                                                      
                                INNER JOIN tblcmp c on c.id = d.idcomprobante  
                                LEFT JOIN con_saldo s on s.idsucursal = c.idsucursal AND s.idcuenta = d.idcuenta
                                WHERE s.saldo is NULL");
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcuenta;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcuenta (id int, valor decimal(11,2));");
        $this->db->query("INSERT INTO tblcuenta 
                            SELECT d.idcuenta, SUM(d.valor * (CASE d.debitocredito WHEN 1 THEN 1 ELSE -1 END))
                                FROM con_comprobantedetalle d                                                                      
                                INNER JOIN tblcmp c on c.id = d.idcomprobante
                                GROUP BY d.idcuenta");
        $this->db->query("UPDATE con_saldo s
                           INNER JOIN tblcuenta c on c.id = s.idcuenta 
                           SET s.saldo = s.saldo + c.valor
                           WHERE s.idsucursal = $sucursal");
        return 1;    
    }

    public function anular_comprobante($id, $motivo = ''){
        $usuario = $this->session->userdata("sess_id");
        $this->db->query("UPDATE con_comprobante SET idestado = 3 WHERE id = $id");
        $this->db->query("INSERT INTO con_comprobanteanulado (idcomprobante, idusuario, motivoanulacion) 
                            VALUES($id, $usuario, '$motivo')");

        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcuenta;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcuenta (id int, valor decimal(11,2));");
        $this->db->query("INSERT INTO tblcuenta 
                            SELECT d.idcuenta, SUM(d.valor * (CASE d.debitocredito WHEN 0 THEN 1 ELSE -1 END))
                                FROM con_comprobantedetalle d                                                                      
                                WHERE d.idcomprobante = $id
                                GROUP BY d.idcuenta");
        $this->db->query("UPDATE con_saldo s
                            INNER JOIN tblcuenta c on c.id = s.idcuenta 
                            SET s.saldo = s.saldo + c.valor
                            WHERE s.idsucursal = (SELECT idsucursal FROM con_comprobante WHERE id = $id)");

/*$this->db->query("UPDATE con_saldo s
                           INNER JOIN con_comprobantedetalle d on d.idcuenta = s.idcuenta                                                                     
                           INNER JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                           c.idsucursal = s.idsucursal
                           SET s.saldo = s.saldo + d.valor * (CASE d.debitocredito WHEN 0 THEN 1 ELSE -1 END)  
                           WHERE d.idcomprobante = $id");*/
        return 1;    
    }

    public function ins_comprobante_contabilizacion($idsucursal, $idtipocomprobante, $referencia, $fecha, 
                                                    $idusuario, $descripcion, $detalles, $documentos){
        $estado = 1;               
        $monto = 0;                 
        foreach($detalles as $detalle){
            if ($detalle->debito == 1) {
                $monto += $detalle->valor;
            }    
        }

        $query = $this->db->query("SELECT prefijo, contador FROM con_tipocomprobante t
                                    INNER JOIN con_tipocomprobante_sucursal s on s.idtipo = t.id
                                    WHERE t.id = $idtipocomprobante AND idsucursal = $idsucursal");
        $resultado = $query->result();
        $referencia = $resultado[0]->prefijo . str_pad($resultado[0]->contador,9,"0",STR_PAD_LEFT);

        $this->db->query("INSERT INTO con_comprobante (idempresa, idsucursal, idtipocomprobante, idejercicio, 
                                                       numero, referencia, fechaasiento, idusuarioregistro, 
                                                       idestado, monto, descripcion) 
                            SELECT (SELECT id_empresa FROM sucursal WHERE id_sucursal = $idsucursal) as idempresa, 
                                   $idsucursal, $idtipocomprobante, 
                                   (SELECT id FROM con_ejercicio WHERE '$fecha' BETWEEN inicio AND fin) as idejercicio, 
                                   IFNULL((SELECT max(numero) FROM con_comprobante
                                            WHERE idsucursal = $idsucursal AND
                                                  idejercicio = (SELECT id FROM con_ejercicio 
                                                                  WHERE '$fecha' BETWEEN inicio AND fin)),0) + 1 as numero, 
                                   '$referencia', date('$fecha'), $idusuario, $estado, 
                                   $monto, '$descripcion';");
        $query = $this->db->query("SELECT max(id) as id FROM con_comprobante");
        $resultado = $query->result();
        $newid = 0;
        if ($resultado){
            $newid = $resultado[0]->id;

            $this->db->query("UPDATE con_tipocomprobante_sucursal SET contador = contador + 1
                                WHERE idtipo = $idtipocomprobante AND idsucursal = $idsucursal");

            foreach($detalles as $detalle){
                $this->db->insert('con_comprobantedetalle', array(
                                    'idcomprobante'=> $newid,
                                    'idcuenta'=> $detalle->idcuenta,
                                    'debitocredito'=> $detalle->debito,
                                    'valor'=> $detalle->valor,
                                    'concepto'=> $detalle->concepto
                                  ));
            }
            foreach($documentos as $documento){
                $this->db->insert('con_comprobantedocumento', array(
                                    'idcomprobante'=> $newid,
                                    'iddocreferencia'=> $documento->id
                                  ));
            }
        }
        //$this->confirmar_comprobante($newid);                                  

        return $newid; 
    }

    public function sel_contabilizacion_venta($iddoc, $sucursal, $desde, $hasta){
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tbldoc;");
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcuenta;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tbldoc (id int);");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcuenta (idcuenta int, valor decimal(11,2), 
                                                                     debito int, concepto varchar(1000));");
        if ($iddoc == '') { $iddoc = 0; }
        if ($iddoc != 0)
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_venta 
                                        FROM venta v
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_venta
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 4 AND c.idestado = 2
                                        WHERE v.id_venta=$iddoc AND v.estatus != 3 AND c.id IS NULL;");
        else
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_venta 
                                        FROM venta v
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_venta
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 4 AND c.idestado = 2
                                        WHERE v.id_sucursal = $sucursal AND c.id IS NULL AND
                                              v.estatus != 3 AND v.fecha BETWEEN '$desde' AND '$hasta';");
	        
        $query = $this->db->query("                                  
            /* Credito por salida de productos/servicios  */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.costo_total) AS valor, 0 as debito,
                                'Consumo de productos y servicios' as concepto 
                    FROM con_configuracioncategoria cfg
                    INNER JOIN producto p on p.idcategoriacontable = cfg.idcategoria
                    INNER JOIN venta_detalle d on d.id_producto = p.pro_id
                    INNER JOIN venta v on v.id_empresa = cfg.idempresa AND v.id_venta = d.id_venta
                    INNER JOIN tbldoc tp on tp.id = v.id_venta
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("                                  
            /* Debito costo de venta impuesto diferente 0% por salida de productos/servicios  */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.costo_total) AS valor, 1 as debito,
                                'Costo de venta impuesto diferente de 0' as concepto 
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoriaventa c on c.costo_ivanocero = cfg.idcategoria
                    INNER JOIN venta v on v.id_empresa = cfg.idempresa 
                    INNER JOIN venta_detalle d on d.id_venta = v.id_venta AND d.iva = 1
                    INNER JOIN tbldoc tp on tp.id = v.id_venta
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("                                                         
            /* Debito costo de venta impuesto 0% por salida de productos/servicios  */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.costo_total) AS valor, 1 as debito,
                                'Costo de venta impuesto 0' as concepto 
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoriaventa c on c.costo_ivacero = cfg.idcategoria
                    INNER JOIN venta v on v.id_empresa = cfg.idempresa 
                    INNER JOIN venta_detalle d on d.id_venta = v.id_venta AND d.iva = 0
                    INNER JOIN tbldoc tp on tp.id = v.id_venta
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("
            /* Debito cuenta por cobrar a clientes */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(montototal) AS valor, 1 as debito,
                                'Cuenta por cobrar a clientes' as concepto  
                    FROM con_configuracioncategoria cfg
                    INNER JOIN clientes c on c.idcategoriacontable = cfg.idcategoria
                    INNER JOIN venta v on v.id_empresa = cfg.idempresa AND v.id_cliente = c.id_cliente
                    INNER JOIN tbldoc tc on tc.id = v.id_venta
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("
            /* Credito ingreso por venta de bienes impuesto diferente 0%  */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.descsubtotal) AS valor, 0 as debito,
                                'Ingreso por venta de bienes impuesto diferente 0' as concepto  
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoriaventa c on c.ingreso_bienes_ivanocero = cfg.idcategoria
                    INNER JOIN venta v on v.id_empresa = cfg.idempresa 
                    INNER JOIN venta_detalle d on d.id_venta = v.id_venta AND d.iva = 1
                    INNER JOIN producto p on p.pro_id =  d.id_producto AND p.pro_esservicio = 0
                    INNER JOIN tbldoc tp on tp.id = v.id_venta
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("
            /* Credito ingreso por salida de productos impuesto 0%  */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.descsubtotal) AS valor, 0 as debito,
                                'Ingreso por venta de bienes impuesto 0' as concepto   
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoriaventa c on c.ingreso_bienes_ivacero = cfg.idcategoria
                    INNER JOIN venta v on v.id_empresa = cfg.idempresa 
                    INNER JOIN venta_detalle d on d.id_venta = v.id_venta AND d.iva = 0
                    INNER JOIN producto p on p.pro_id =  d.id_producto AND p.pro_esservicio = 0
                    INNER JOIN tbldoc tp on tp.id = v.id_venta
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("           
            /* Credito ingreso por servicios impuesto diferente 0%  */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.descsubtotal) AS valor, 0 as debito,
                                'Ingreso por venta de servicios impuesto diferente 0' as concepto   
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoriaventa c on c.ingreso_servicios_ivanocero = cfg.idcategoria
                    INNER JOIN venta v on v.id_empresa = cfg.idempresa 
                    INNER JOIN venta_detalle d on d.id_venta = v.id_venta AND d.iva = 1
                    INNER JOIN producto p on p.pro_id =  d.id_producto AND p.pro_esservicio = 1
                    INNER JOIN tbldoc tp on tp.id = v.id_venta
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("            
            /* Credito ingreso por servicios impuesto diferente 0%  */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.descsubtotal) AS valor, 0 as debito,
                                'Ingreso por venta de servicios impuesto 0' as concepto   
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoriaventa c on c.ingreso_servicios_ivacero = cfg.idcategoria
                    INNER JOIN venta v on v.id_empresa = cfg.idempresa 
                    INNER JOIN venta_detalle d on d.id_venta = v.id_venta AND d.iva = 0
                    INNER JOIN producto p on p.pro_id =  d.id_producto AND p.pro_esservicio = 1
                    INNER JOIN tbldoc tp on tp.id = v.id_venta
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("            
            /* Credito monto de iva  */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.montoiva) AS valor, 0 as debito,
                                'Monto de iva' as concepto   
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoriaventa c on c.monto_iva = cfg.idcategoria
                    INNER JOIN venta v on v.id_empresa = cfg.idempresa 
                    INNER JOIN venta_detalle d on d.id_venta = v.id_venta AND d.iva = 1
                    INNER JOIN tbldoc tp on tp.id = v.id_venta
                    GROUP BY cfg.idcuenta;");

        $this->db->query("DELETE FROM tblcuenta WHERE IFNULL(valor,0)=0;");

        $query = $this->db->get("tblcuenta");
        $tblcuenta = $query->result(); 
        $query = $this->db->get("tbldoc");
        $tbldoc = $query->result(); 
  
        return array('detalles' => $tblcuenta, 'documentos' => $tbldoc);
    }    

    public function sel_documento_cobro_fecha($sucursal, $desde, $hasta){
        $query = $this->db->query("SELECT p.id 
                                    FROM venta_formapago p
                                    INNER JOIN venta v on v.id_venta = p.id_venta AND v.id_sucursal = $sucursal
                                    LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = p.id
                                    LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                   c.idtipocomprobante = 5 AND c.idestado = 2
                                    WHERE p.fecha BETWEEN '$desde' AND '$hasta' AND 
                                          c.id IS NULL;");
        $res = $query->result(); 
        $arr = [];
        foreach($res as $item){
            $arr[] = $item->id;
        }
        return $arr;
    }

    public function sel_documento_pago_fecha($sucursal, $desde, $hasta){
        $query = $this->db->query("SELECT a.iddocpago as id 
                                    FROM compra_abonos a
                                    INNER JOIN compra v on v.id_comp = a.id_compra AND v.id_sucursal = $sucursal
                                    LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = a.iddocpago
                                    LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                   c.idtipocomprobante = 8 AND c.idestado = 2
                                    WHERE date(a.fecha) BETWEEN '$desde' AND '$hasta' AND 
                                          c.id IS NULL
                                   UNION
                                   SELECT a.iddocpago as id 
                                    FROM gastos_abonos a
                                    INNER JOIN gastos v on v.id_gastos = a.id_gastos AND v.id_sucursal = $sucursal
                                    LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = a.iddocpago
                                    LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                   c.idtipocomprobante = 8 AND c.idestado = 2
                                    WHERE date(a.fecha) BETWEEN '$desde' AND '$hasta' AND 
                                          c.id IS NULL;");
        $res = $query->result(); 
        $arr = [];
        foreach($res as $item){
            $arr[] = $item->id;
        }
        return $arr;
    }

    public function sel_documento_cobro_venta($idventa){
        $query = $this->db->query("SELECT p.id 
                                    FROM venta_formapago p
                                    LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = p.id
                                    LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                   c.idtipocomprobante = 5 AND c.idestado = 2
                                    WHERE p.id_venta = $idventa AND 
                                          c.id IS NULL;");
        $res = $query->result(); 
        $arr = [];
        foreach($res as $item){
            $arr[] = $item->id;
        }
        return $arr;
    }

    public function sel_contabilizacion_cobro($doccobro, $sucursal, $desde, $hasta){
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tbldoc;");
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcuenta;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tbldoc (id int);");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcuenta (idcuenta int, valor decimal(11,2), 
                                                                     debito int, concepto varchar(1000));");
        foreach($doccobro as $doc){
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id 
                                        FROM venta_formapago v
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 5 AND c.idestado = 2
                                        WHERE v.id=$doc AND c.id IS NULL;");

        }                                                                     
        $this->db->query("
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.monto) AS valor, 0 as debito,
                                'Liquidacion de Cuenta por cobrar a clientes' as concepto  
                    FROM con_configuracioncategoria cfg
                    INNER JOIN clientes c on c.idcategoriacontable = cfg.idcategoria
                    INNER JOIN venta v on v.id_empresa = cfg.idempresa AND v.id_cliente = c.id_cliente
                    INNER JOIN venta_formapago d on d.id_venta = v.id_venta 
                    INNER JOIN tbldoc tc on tc.id = d.id
                    WHERE v.estatus != 3 
                    GROUP BY cfg.idcuenta;");
        $this->db->query("
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.monto) AS valor, 1 as debito,
                                'Ingreso de efectivo por ventas' as concepto  
                    FROM con_cuentaclienteformapago cfg
                    INNER JOIN venta v on v.id_empresa = cfg.idempresa 
                    INNER JOIN venta_formapago d on d.id_venta = v.id_venta AND d.id_formapago = cfg.idformapago
                    INNER JOIN tbldoc tp on tp.id = d.id
                    WHERE v.estatus != 3 AND d.id_formapago != 1
                    GROUP BY cfg.idcuenta;");
        $this->db->query("
            INSERT INTO tblcuenta
                SELECT cfg.idcuentacontable, SUM(d.monto) AS valor, 1 as debito,
                       'Ingreso de efectivo por ventas' as concepto  
                    FROM venta v
                    INNER JOIN venta_formapago d on d.id_venta = v.id_venta AND d.id_formapago = 1
                    INNER JOIN deposito_efectivo cfg on cfg.id = d.id_cajapago
                    INNER JOIN tbldoc tp on tp.id = d.id
                    WHERE v.estatus != 3 
                    GROUP BY cfg.idcuentacontable;");
        
        $this->db->query("DELETE FROM tblcuenta WHERE IFNULL(valor,0)=0;");

        $query = $this->db->get("tblcuenta");
        $tblcuenta = $query->result(); 
        $query = $this->db->get("tbldoc");
        $tbldoc = $query->result(); 
  
        return array('detalles' => $tblcuenta, 'documentos' => $tbldoc);
    }    

    public function sel_documento_pago_compra($iddoc){
        $query = $this->db->query("SELECT DISTINCT p.iddocpago as id
                                    FROM compra_abonos p
                                    LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = p.iddocpago
                                    LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                   c.idtipocomprobante = 8 AND c.idestado = 2
                                    WHERE p.id_compra = $iddoc AND 
                                          c.id IS NULL;");
        $res = $query->result(); 
        $arr = [];
        foreach($res as $item){
            $arr[] = $item->id;
        }
        return $arr;
    }

    public function sel_documento_pago_gasto($iddoc){
        $query = $this->db->query("SELECT DISTINCT p.iddocpago as id
                                    FROM gastos_abonos p
                                    LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = p.iddocpago
                                    LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                   c.idtipocomprobante = 8 AND c.idestado = 2
                                    WHERE p.id_gastos = $iddoc AND 
                                          c.id IS NULL;");
        $res = $query->result(); 
        $arr = [];
        foreach($res as $item){
            $arr[] = $item->id;
        }
        return $arr;
    }

    public function sel_documento_pago_abonocompra($iddoc){
        $query = $this->db->query("SELECT DISTINCT p.iddocpago as id
                                    FROM compra_abonos p
                                    LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = p.iddocpago
                                    LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                   c.idtipocomprobante = 8 AND c.idestado = 2
                                    WHERE p.id_abono = $iddoc AND 
                                          c.id IS NULL;");
        $res = $query->result(); 
        $arr = [];
        foreach($res as $item){
            $arr[] = $item->id;
        }
        return $arr;
    }

    public function sel_contabilizacion_pago($docpago, $sucursal, $desde, $hasta){
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tbldoc;");
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcuenta;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tbldoc (id int);");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcuenta (idcuenta int, valor decimal(11,2), 
                                                                     debito int, concepto varchar(1000));");
        foreach($docpago as $doc){
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id 
                                        FROM documento_pago v
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                  c.idtipocomprobante = 8 AND c.idestado = 2
                                        WHERE v.id=$doc AND c.id IS NULL;");

        }                                                                     
        $this->db->query("
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.monto) AS valor, 1 as debito,
                       'Liquidacion de Cuenta por pagar a proveedores' as concepto  
                    FROM con_configuracioncategoria cfg
                    INNER JOIN proveedor c on c.idcategoriacontable = cfg.idcategoria
                    INNER JOIN compra v on v.id_proveedor = c.id_proveedor
                    INNER JOIN sucursal s on s.id_sucursal = v.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN compra_abonos d on d.id_compra = v.id_comp 
                    INNER JOIN tbldoc tc on tc.id = d.iddocpago
                    WHERE v.estatus != 3 
                    GROUP BY cfg.idcuenta;");
        $this->db->query("
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.monto) AS valor, 1 as debito,
                       'Liquidacion de Cuenta por pagar a proveedores' as concepto  
                    FROM con_configuracioncategoria cfg
                    INNER JOIN proveedor c on c.idcategoriacontable = cfg.idcategoria
                    INNER JOIN gastos v on v.id_proveedor = c.id_proveedor
                    INNER JOIN sucursal s on s.id_sucursal = v.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN gastos_abonos d on d.id_gastos = v.id_gastos 
                    INNER JOIN tbldoc tc on tc.id = d.iddocpago
                    WHERE v.estatus != 3 
                    GROUP BY cfg.idcuenta;");
        $this->db->query("
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.monto) AS valor, 0 as debito,
                       'Egreso de efectivo por compras' as concepto  
                    FROM con_cuentaproveedorformapago cfg
                    INNER JOIN sucursal s on s.id_empresa = cfg.idempresa 
                    INNER JOIN compra v on v.id_sucursal = s.id_sucursal 
                    INNER JOIN compra_abonos d on d.id_compra = v.id_comp AND d.id_formapago = cfg.idformapago
                    INNER JOIN tbldoc tp on tp.id = d.iddocpago
                    WHERE v.estatus != 3 AND d.id_formapago != 1 
                    GROUP BY cfg.idcuenta;");
        $this->db->query("
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.monto) AS valor, 0 as debito,
                       'Egreso de efectivo por compras' as concepto  
                    FROM con_cuentaproveedorformapago cfg
                    INNER JOIN sucursal s on s.id_empresa = cfg.idempresa 
                    INNER JOIN gastos v on v.id_sucursal = s.id_sucursal 
                    INNER JOIN gastos_abonos d on d.id_gastos = v.id_gastos AND d.id_formapago = cfg.idformapago
                    INNER JOIN tbldoc tp on tp.id = d.iddocpago
                    WHERE v.estatus != 3 AND d.id_formapago != 1
                    GROUP BY cfg.idcuenta;");
        $this->db->query("
            INSERT INTO tblcuenta
                SELECT cfg.idcuentacontable, SUM(d.monto) AS valor, 0 as debito,
                        'Egreso de efectivo por compras' as concepto  
                    FROM compra v 
                    INNER JOIN compra_abonos d on d.id_compra = v.id_comp AND d.id_formapago = 1
                    INNER JOIN documento_pagodeposito dp on dp.iddocumento = d.iddocpago
                    INNER JOIN deposito_efectivo cfg on cfg.id = dp.iddeposito
                    INNER JOIN tbldoc tp on tp.id = d.iddocpago
                    WHERE v.estatus != 3 
                    GROUP BY cfg.idcuentacontable;");
        $this->db->query("
            INSERT INTO tblcuenta
                SELECT cfg.idcuentacontable, SUM(d.monto) AS valor, 0 as debito,
                        'Egreso de efectivo por compras' as concepto  
                    FROM gastos v 
                    INNER JOIN gastos_abonos d on d.id_gastos = v.id_gastos AND d.id_formapago = 1
                    INNER JOIN documento_pagodeposito dp on dp.iddocumento = d.iddocpago
                    INNER JOIN deposito_efectivo cfg on cfg.id = dp.iddeposito
                    INNER JOIN tbldoc tp on tp.id = d.iddocpago
                    WHERE v.estatus != 3 
                    GROUP BY cfg.idcuentacontable;");
                
        $this->db->query("DELETE FROM tblcuenta WHERE IFNULL(valor,0)=0;");

        $query = $this->db->get("tblcuenta");
        $tblcuenta = $query->result(); 
        $query = $this->db->get("tbldoc");
        $tbldoc = $query->result(); 
  
        return array('detalles' => $tblcuenta, 'documentos' => $tbldoc);
    }    
    
    public function sel_contabilizacion_compra($iddoc, $sucursal, $desde, $hasta){
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tbldoc;");
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcuenta;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tbldoc (id int);");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcuenta (idcuenta int, valor decimal(11,2), 
                                                                     debito int, concepto varchar(1000));");
        if ($iddoc == '') { $iddoc = 0; }
        if ($iddoc != 0)
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_comp 
                                        FROM compra v
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_comp
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 6 AND c.idestado = 2
                                        WHERE v.estatus != 3 AND v.id_comp=$iddoc AND c.id IS NULL;");
        else
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_comp 
                                        FROM compra v
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_comp
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 6 AND c.idestado = 2
                                        WHERE v.id_sucursal = $sucursal AND c.id IS NULL AND
                                              v.estatus != 3 AND v.fecha BETWEEN '$desde' AND '$hasta';");
	        
        $query = $this->db->query("                                  
            /* Debito por ingreso de productos  */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.descsubtotal) AS valor, 1 as debito,
                                'Ingreso de productos' as concepto 
                    FROM con_configuracioncategoria cfg
                    INNER JOIN producto p on p.idcategoriacontable = cfg.idcategoria
                    INNER JOIN compra_det d on d.id_pro = p.pro_id
                    INNER JOIN compra v on v.id_comp = d.id_comp
                    INNER JOIN sucursal s on s.id_sucursal = v.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN tbldoc tp on tp.id = v.id_comp
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("            
            /* Debito monto de iva  */
                    INSERT INTO tblcuenta
                        SELECT cfg.idcuenta, SUM(d.montoiva) AS valor, 1 as debito,
                                        'Monto de iva' as concepto   
                            FROM con_configuracioncategoria cfg
                            INNER JOIN con_categoriacompra c on c.monto_iva_compra = cfg.idcategoria
                            INNER JOIN sucursal s on s.id_empresa = cfg.idempresa 
                            INNER JOIN compra v on v.id_sucursal = s.id_sucursal
                            INNER JOIN compra_det d on d.id_comp = v.id_comp AND d.iva = 1
                            INNER JOIN tbldoc tp on tp.id = v.id_comp
                            GROUP BY cfg.idcuenta;");
        $query = $this->db->query("
            /* Credito cuenta por pagar a Proveedores */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(v.montototal) AS valor, 0 as debito,
                                'Cuenta por pagar a Proveedores' as concepto  
                    FROM con_configuracioncategoria cfg
                    INNER JOIN proveedor c on c.idcategoriacontable = cfg.idcategoria
                    INNER JOIN compra v on v.id_proveedor = c.id_proveedor
                    INNER JOIN sucursal s on s.id_sucursal = v.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN tbldoc tc on tc.id = v.id_comp
                    GROUP BY cfg.idcuenta;");

        $this->db->query("DELETE FROM tblcuenta WHERE IFNULL(valor,0)=0;");

        $query = $this->db->get("tblcuenta");
        $tblcuenta = $query->result(); 
        $query = $this->db->get("tbldoc");
        $tbldoc = $query->result(); 
  
        return array('detalles' => $tblcuenta, 'documentos' => $tbldoc);
    }    

    public function sel_contabilizacion_gasto($iddoc, $sucursal, $desde, $hasta){
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tbldoc;");
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcuenta;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tbldoc (id int);");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcuenta (idcuenta int, valor decimal(11,2), 
                                                                     debito int, concepto varchar(1000));");
        if ($iddoc == '') { $iddoc = 0; }
        if ($iddoc != 0)
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_gastos 
                                        FROM gastos v
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_gastos
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 7 AND c.idestado = 2
                                        WHERE v.estatus != 3 AND v.id_gastos=$iddoc AND c.id IS NULL;");
        else
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_gastos 
                                        FROM gastos v
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_gastos
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 7 AND c.idestado = 2
                                        WHERE v.id_sucursal = $sucursal AND c.id IS NULL AND
                                              v.estatus != 3 AND v.fecha BETWEEN '$desde' AND '$hasta';");
	        
        $query = $this->db->query("                                  
            /* Debito gasto por servicio recibido */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(v.subtotaldesc + v.subtotalivacerodesc) AS valor, 1 as debito,
                                'Gasto por servicio recibido' as concepto 
                    FROM con_cuentacategoriagasto cfg
                    INNER JOIN sucursal s on s.id_empresa = cfg.idempresa 
                    INNER JOIN gastos v on v.id_sucursal = s.id_sucursal AND v.categoria = cfg.idcategoria
                    INNER JOIN tbldoc tp on tp.id = v.id_gastos
                    WHERE v.estatus != 3
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("            
            /* Debito monto de iva  */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(v.montoiva) AS valor, 1 as debito,
                                'Monto de iva' as concepto   
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoriacompra c on c.monto_iva_gasto = cfg.idcategoria
                    INNER JOIN sucursal s on s.id_empresa = cfg.idempresa 
                    INNER JOIN gastos v on v.id_sucursal = s.id_sucursal
                    INNER JOIN tbldoc tp on tp.id = v.id_gastos
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("
            /* Credito cuenta por pagar a Proveedores */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(v.total) AS valor, 0 as debito,
                                'Cuenta por pagar a Proveedores' as concepto  
                    FROM con_configuracioncategoria cfg
                    INNER JOIN proveedor c on c.idcategoriacontable = cfg.idcategoria
                    INNER JOIN gastos v on v.id_proveedor = c.id_proveedor
                    INNER JOIN sucursal s on s.id_sucursal = v.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN tbldoc tc on tc.id = v.id_gastos
                    GROUP BY cfg.idcuenta;");

        $this->db->query("DELETE FROM tblcuenta WHERE IFNULL(valor,0)=0;");

        $query = $this->db->get("tblcuenta");
        $tblcuenta = $query->result(); 
        $query = $this->db->get("tbldoc");
        $tbldoc = $query->result(); 
  
        return array('detalles' => $tblcuenta, 'documentos' => $tbldoc);
    }    

    public function sel_contabilizacion_ingresoinv($iddoc, $sucursal, $desde, $hasta){
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tbldoc;");
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcuenta;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tbldoc (id int);");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcuenta (idcuenta int, valor decimal(11,2), 
                                                                     debito int, concepto varchar(1000));");
        if ($iddoc == '') { $iddoc = 0; }
        if ($iddoc != 0)
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_documento 
                                        FROM inventariodocumento v
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_documento
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 9 AND c.idestado = 2
                                        WHERE v.id_tipodoc = 4 AND v.id_documento = $iddoc AND c.id IS NULL;");
        else
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_documento 
                                        FROM inventariodocumento v
                                        INNER JOIN almacen a on a.almacen_id = v.id_almacen
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_documento
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 9 AND c.idestado = 2
                                        WHERE a.sucursal_id = $sucursal AND c.id IS NULL AND
                                              v.id_tipodoc = 4 AND
                                              v.fecha BETWEEN '$desde' AND '$hasta';");
	        
        // Debito por ingreso de productos  
        $query = $this->db->query("                                  
        INSERT INTO tblcuenta
            SELECT cfg.idcuenta, SUM(d.montototal) AS valor, 1 as debito,
                            'Ingreso de productos' as concepto 
                FROM con_configuracioncategoria cfg
                INNER JOIN producto p on p.idcategoriacontable = cfg.idcategoria
                INNER JOIN inventariodocumento_detalle d on d.id_pro = p.pro_id
                INNER JOIN inventariodocumento v on v.id_documento = d.id_documento AND v.id_tipodoc = 4
                INNER JOIN almacen a on a.almacen_id = v.id_almacen
                INNER JOIN sucursal s on s.id_sucursal = a.sucursal_id AND s.id_empresa = cfg.idempresa 
                INNER JOIN tbldoc tp on tp.id = v.id_documento
                GROUP BY cfg.idcuenta;");
        // Credito por ingreso de productos 
        $query = $this->db->query("                                  
        INSERT INTO tblcuenta
            SELECT cfg.idcuenta, SUM(v.total) AS valor, 0 as debito,
                            'Credito por ingreso de productos' as concepto 
                FROM con_configuracioncategoria cfg
                INNER JOIN con_categoria c on c.id = cfg.idcategoria AND c.idtipocategoria = 11
                INNER JOIN inventariodocumento v on v.idcategoriacontable = c.id 
                INNER JOIN almacen a on a.almacen_id = v.id_almacen
                INNER JOIN sucursal s on s.id_sucursal = a.sucursal_id AND s.id_empresa = cfg.idempresa 
                INNER JOIN tbldoc tp on tp.id = v.id_documento
                GROUP BY cfg.idcuenta;");
        
        $this->db->query("DELETE FROM tblcuenta WHERE IFNULL(valor,0)=0;");

        $query = $this->db->get("tblcuenta");
        $tblcuenta = $query->result(); 
        $query = $this->db->get("tbldoc");
        $tbldoc = $query->result(); 
  
        return array('detalles' => $tblcuenta, 'documentos' => $tbldoc);
    }    

    public function sel_contabilizacion_egresoinv($iddoc, $sucursal, $desde, $hasta){
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tbldoc;");
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcuenta;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tbldoc (id int);");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcuenta (idcuenta int, valor decimal(11,2), 
                                                                     debito int, concepto varchar(1000));");
        if ($iddoc == '') { $iddoc = 0; }
        if ($iddoc != 0)
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_documento 
                                        FROM inventariodocumento v
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_documento
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 10 AND c.idestado = 2
                                        WHERE v.id_tipodoc != 4 AND v.id_documento = $iddoc AND c.id IS NULL;");
        else
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_documento 
                                        FROM inventariodocumento v
                                        INNER JOIN almacen a on a.almacen_id = v.id_almacen
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_documento
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 10 AND c.idestado = 2
                                        WHERE a.sucursal_id = $sucursal AND c.id IS NULL AND
                                              v.id_tipodoc != 4 AND
                                              v.fecha BETWEEN '$desde' AND '$hasta';");
          
        // Credito por egreso de productos  
        $query = $this->db->query("                                  
        INSERT INTO tblcuenta
            SELECT cfg.idcuenta, SUM(d.montototal) AS valor, 0 as debito,
                            'Egreso de productos' as concepto 
                FROM con_configuracioncategoria cfg
                INNER JOIN producto p on p.idcategoriacontable = cfg.idcategoria
                INNER JOIN inventariodocumento_detalle d on d.id_pro = p.pro_id
                INNER JOIN inventariodocumento v on v.id_documento = d.id_documento AND v.id_tipodoc != 4
                INNER JOIN almacen a on a.almacen_id = v.id_almacen
                INNER JOIN sucursal s on s.id_sucursal = a.sucursal_id AND s.id_empresa = cfg.idempresa 
                INNER JOIN tbldoc tp on tp.id = v.id_documento
                GROUP BY cfg.idcuenta;");
        // Debito por egreso de productos 
        $query = $this->db->query("                                  
        INSERT INTO tblcuenta
            SELECT cfg.idcuenta, SUM(v.total) AS valor, 1 as debito,
                            'Debito por ingreso de productos' as concepto 
                FROM con_configuracioncategoria cfg
                INNER JOIN con_categoria c on c.id = cfg.idcategoria AND c.idtipocategoria = 12
                INNER JOIN inventariodocumento v on v.idcategoriacontable = c.id 
                INNER JOIN almacen a on a.almacen_id = v.id_almacen
                INNER JOIN sucursal s on s.id_sucursal = a.sucursal_id AND s.id_empresa = cfg.idempresa 
                INNER JOIN tbldoc tp on tp.id = v.id_documento
                GROUP BY cfg.idcuenta;");
        
        $this->db->query("DELETE FROM tblcuenta WHERE IFNULL(valor,0)=0;");

        $query = $this->db->get("tblcuenta");
        $tblcuenta = $query->result(); 
        $query = $this->db->get("tbldoc");
        $tbldoc = $query->result(); 
  
        return array('detalles' => $tblcuenta, 'documentos' => $tbldoc);
    }    

    public function sel_contabilizacion_retencioncompra($iddoc, $sucursal, $desde, $hasta){
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tbldoc;");
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcuenta;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tbldoc (id int);");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcuenta (idcuenta int, valor decimal(11,2), 
                                                                     debito int, concepto varchar(1000));");
        if ($iddoc == '') { $iddoc = 0; }
        if ($iddoc != 0)
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_comp_ret 
                                        FROM compra_retencion v
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_comp_ret
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 12 AND c.idestado = 2
                                        WHERE v.id_comp_ret=$iddoc AND c.id IS NULL;");
        else
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_comp_ret 
                                        FROM compra_retencion v
                                        INNER JOIN compra cp on cp.id_comp = v.id_compra
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_comp_ret
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 12 AND c.idestado = 2
                                        WHERE cp.id_sucursal = $sucursal AND c.id IS NULL AND
                                              v.fecha_retencion BETWEEN '$desde' AND '$hasta';");
          
        $query = $this->db->query("                                  
            /* Credito por retencion en fuente */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.valor_retencion_renta) AS valor, 0 as debito,
                                'Credito por retencion en fuente' as concepto 
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoria c on c.id = cfg.idcategoria AND c.idtipocategoria = 5
                    INNER JOIN concepto_retencion r on r.idcategoriacompra = c.id                     
                    INNER JOIN compra_retencion_detrenta d on d.id_concepto_retencion = r.id_cto_retencion 
                    INNER JOIN compra_retencion v on v.id_comp_ret = d.id_comp_ret
                    INNER JOIN compra cp on cp.id_comp = v.id_compra
                    INNER JOIN sucursal s on s.id_sucursal = cp.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN tbldoc tp on tp.id = v.id_comp_ret
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("            
            /* Credito por retencion de iva */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.valor_retencion_iva) AS valor, 0 as debito,
                                'Credito por retencion de iva' as concepto 
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoria c on c.id = cfg.idcategoria AND c.idtipocategoria = 7
                    INNER JOIN porcentaje_retencion_iva r on r.idcategoriacompra = c.id                     
                    INNER JOIN compra_retencion_detiva d on d.id_porcentaje_retencion_iva = r.id_porc_ret_iva 
                    INNER JOIN compra_retencion v on v.id_comp_ret = d.id_comp_ret
                    INNER JOIN compra cp on cp.id_comp = v.id_compra
                    INNER JOIN sucursal s on s.id_sucursal = cp.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN tbldoc tp on tp.id = v.id_comp_ret
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("
            /* Debito cuenta por pagar a Proveedores */
            INSERT INTO tblcuenta
              SELECT  cfg.idcuenta, 
                      SUM(IFNULL((SELECT SUM(ri.valor_retencion_iva) FROM compra_retencion_detiva ri
                                    WHERE ri.id_comp_ret = v.id_comp_ret), 0) + 
                          IFNULL((SELECT SUM(rr.valor_retencion_renta) FROM compra_retencion_detrenta rr
                                    WHERE rr.id_comp_ret = v.id_comp_ret), 0)) AS valor, 
                       1 as debito,
                       'Cuenta por pagar a Proveedores' as concepto  
                    FROM con_configuracioncategoria cfg
                    INNER JOIN proveedor c on c.idcategoriacontable = cfg.idcategoria
                    INNER JOIN compra cp on cp.id_proveedor = c.id_proveedor
                    INNER JOIN compra_retencion v on v.id_compra = cp.id_comp 
                    INNER JOIN sucursal s on s.id_sucursal = cp.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN tbldoc tc on tc.id = v.id_comp_ret
                    GROUP BY cfg.idcuenta;");

        $this->db->query("DELETE FROM tblcuenta WHERE IFNULL(valor,0)=0;");

        $query = $this->db->get("tblcuenta");
        $tblcuenta = $query->result(); 
        $query = $this->db->get("tbldoc");
        $tbldoc = $query->result(); 
  
        return array('detalles' => $tblcuenta, 'documentos' => $tbldoc);
    }    

    public function sel_contabilizacion_retenciongasto($iddoc, $sucursal, $desde, $hasta){
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tbldoc;");
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcuenta;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tbldoc (id int);");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcuenta (idcuenta int, valor decimal(11,2), 
                                                                     debito int, concepto varchar(1000));");
        if ($iddoc == '') { $iddoc = 0; }
        if ($iddoc != 0)
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_gastos_ret 
                                        FROM gastos_retencion v
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_gastos_ret
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 13 AND c.idestado = 2
                                        WHERE v.id_gastos_ret=$iddoc AND c.id IS NULL;");
        else
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_gastos_ret 
                                        FROM gastos_retencion v
                                        INNER JOIN gastos cp on cp.id_gastos = v.id_gastos
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_gastos_ret
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 13 AND c.idestado = 2
                                        WHERE cp.id_sucursal = $sucursal AND c.id IS NULL AND
                                              v.fecha_retencion BETWEEN '$desde' AND '$hasta';");
          
        $query = $this->db->query("                                  
            /* Credito por retencion en fuente */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.valor_retencion_renta) AS valor, 0 as debito,
                                'Credito por retencion en fuente' as concepto 
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoria c on c.id = cfg.idcategoria AND c.idtipocategoria = 5
                    INNER JOIN concepto_retencion r on r.idcategoriacompra = c.id                     
                    INNER JOIN gastos_retencion_detrenta d on d.id_concepto_retencion = r.id_cto_retencion 
                    INNER JOIN gastos_retencion v on v.id_gastos_ret = d.id_gastos_ret
                    INNER JOIN gastos cp on cp.id_gastos = v.id_gastos
                    INNER JOIN sucursal s on s.id_sucursal = cp.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN tbldoc tp on tp.id = v.id_gastos_ret
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("            
            /* Credito por retencion de iva */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.valor_retencion_iva) AS valor, 0 as debito,
                                'Credito por retencion de iva' as concepto 
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoria c on c.id = cfg.idcategoria AND c.idtipocategoria = 7
                    INNER JOIN porcentaje_retencion_iva r on r.idcategoriacompra = c.id                     
                    INNER JOIN gastos_retencion_detiva d on d.id_porcentaje_retencion_iva = r.id_porc_ret_iva 
                    INNER JOIN gastos_retencion v on v.id_gastos_ret = d.id_gastos_ret
                    INNER JOIN gastos cp on cp.id_gastos = v.id_gastos
                    INNER JOIN sucursal s on s.id_sucursal = cp.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN tbldoc tp on tp.id = v.id_gastos_ret
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("
            /* Debito cuenta por pagar a Proveedores */
            INSERT INTO tblcuenta
              SELECT  cfg.idcuenta, 
                      SUM(IFNULL((SELECT SUM(ri.valor_retencion_iva) FROM gastos_retencion_detiva ri
                                    WHERE ri.id_gastos_ret = v.id_gastos_ret), 0) + 
                          IFNULL((SELECT SUM(rr.valor_retencion_renta) FROM gastos_retencion_detrenta rr
                                    WHERE rr.id_gastos_ret = v.id_gastos_ret), 0)) AS valor, 
                       1 as debito,
                       'Cuenta por pagar a Proveedores' as concepto  
                    FROM con_configuracioncategoria cfg
                    INNER JOIN proveedor c on c.idcategoriacontable = cfg.idcategoria
                    INNER JOIN gastos cp on cp.id_proveedor = c.id_proveedor
                    INNER JOIN gastos_retencion v on v.id_gastos = cp.id_gastos 
                    INNER JOIN sucursal s on s.id_sucursal = cp.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN tbldoc tc on tc.id = v.id_gastos_ret
                    GROUP BY cfg.idcuenta;");

        $this->db->query("DELETE FROM tblcuenta WHERE IFNULL(valor,0)=0;");

        $query = $this->db->get("tblcuenta");
        $tblcuenta = $query->result(); 
        $query = $this->db->get("tbldoc");
        $tbldoc = $query->result(); 
  
        return array('detalles' => $tblcuenta, 'documentos' => $tbldoc);
    }    

    public function sel_contabilizacion_retencionventa($iddoc, $sucursal, $desde, $hasta){
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tbldoc;");
        $query = $this->db->query("DROP TEMPORARY TABLE IF EXISTS tblcuenta;");
        $query = $this->db->query("CREATE TEMPORARY TABLE tbldoc (id int);");
        $query = $this->db->query("CREATE TEMPORARY TABLE tblcuenta (idcuenta int, valor decimal(11,2), 
                                                                     debito int, concepto varchar(1000));");
        if ($iddoc == '') { $iddoc = 0; }
        if ($iddoc != 0)
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_venta_ret 
                                        FROM venta_retencion v
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_venta_ret
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 11 AND c.idestado = 2
                                        WHERE v.id_venta_ret=$iddoc AND c.id IS NULL;");
        else
            $query = $this->db->query("INSERT INTO tbldoc SELECT v.id_venta_ret 
                                        FROM venta_retencion v
                                        INNER JOIN venta cp on cp.id_venta = v.id_venta
                                        LEFT JOIN con_comprobantedocumento d on d.iddocreferencia = v.id_venta_ret
                                        LEFT JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                                       c.idtipocomprobante = 11 AND c.idestado = 2
                                        WHERE cp.id_sucursal = $sucursal AND c.id IS NULL AND
                                              v.fecha_retencion BETWEEN '$desde' AND '$hasta';");
          
        $query = $this->db->query("                                  
            /* Debito por retencion en fuente */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.valor_retencion_renta) AS valor, 1 as debito,
                       'Debito por retencion en fuente' as concepto 
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoria c on c.id = cfg.idcategoria AND c.idtipocategoria = 4
                    INNER JOIN concepto_retencion r on r.idcategoriaventa = c.id                     
                    INNER JOIN venta_retencion_detrenta d on d.id_concepto_retencion = r.id_cto_retencion 
                    INNER JOIN venta_retencion v on v.id_venta_ret = d.id_venta_ret
                    INNER JOIN venta cp on cp.id_venta = v.id_venta
                    INNER JOIN sucursal s on s.id_sucursal = cp.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN tbldoc tp on tp.id = v.id_venta_ret
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("            
            /* Debito por retencion de iva */
            INSERT INTO tblcuenta
                SELECT cfg.idcuenta, SUM(d.valor_retencion_iva) AS valor, 1 as debito,
                       'Debito por retencion de iva' as concepto 
                    FROM con_configuracioncategoria cfg
                    INNER JOIN con_categoria c on c.id = cfg.idcategoria AND c.idtipocategoria = 6
                    INNER JOIN porcentaje_retencion_iva r on r.idcategoriaventa = c.id                     
                    INNER JOIN venta_retencion_detiva d on d.id_porcentaje_retencion_iva = r.id_porc_ret_iva 
                    INNER JOIN venta_retencion v on v.id_venta_ret = d.id_venta_ret
                    INNER JOIN venta cp on cp.id_venta = v.id_venta
                    INNER JOIN sucursal s on s.id_sucursal = cp.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN tbldoc tp on tp.id = v.id_venta_ret
                    GROUP BY cfg.idcuenta;");
        $query = $this->db->query("
            /* Credito cuenta por cobrar a Clientes */
            INSERT INTO tblcuenta
              SELECT  cfg.idcuenta, 
                      SUM(IFNULL((SELECT SUM(ri.valor_retencion_iva) FROM venta_retencion_detiva ri
                                    WHERE ri.id_venta_ret = v.id_venta_ret), 0) + 
                          IFNULL((SELECT SUM(rr.valor_retencion_renta) FROM venta_retencion_detrenta rr
                                    WHERE rr.id_venta_ret = v.id_venta_ret), 0)) AS valor, 
                       0 as debito,
                       'Cuenta por cobrar a Clientes' as concepto  
                    FROM con_configuracioncategoria cfg
                    INNER JOIN clientes c on c.idcategoriacontable = cfg.idcategoria
                    INNER JOIN venta cp on cp.id_cliente = c.id_cliente
                    INNER JOIN venta_retencion v on v.id_venta = cp.id_venta 
                    INNER JOIN sucursal s on s.id_sucursal = cp.id_sucursal AND s.id_empresa = cfg.idempresa 
                    INNER JOIN tbldoc tc on tc.id = v.id_venta_ret
                    GROUP BY cfg.idcuenta;");

        $this->db->query("DELETE FROM tblcuenta WHERE IFNULL(valor,0)=0;");

        $query = $this->db->get("tblcuenta");
        $tblcuenta = $query->result(); 
        $query = $this->db->get("tbldoc");
        $tbldoc = $query->result(); 
  
        return array('detalles' => $tblcuenta, 'documentos' => $tbldoc);
    }    

    public function lst_tipocomprobantes(){
        $this->db->where_in('id', array(4,5,6,7,8,9,10,11,12,13));
        $query = $this->db->get("con_tipocomprobante");
                          
        $resultado = $query->result();
        return $resultado;
    }

    public function cuentas_configuradas_venta($idempresa){
        $query = $this->db->query("select c.id from con_categoriaventa c left join con_configuracioncategoria f on f.idcategoria=c.costo_ivacero and f.idempresa = $idempresa
                                        where f.idcategoria is null
                                    union
                                    select c.id from con_categoriaventa c left join con_configuracioncategoria f on f.idcategoria=c.costo_ivanocero and f.idempresa = $idempresa
                                        where f.idcategoria is null
                                    union
                                    select c.id from con_categoriaventa c left join con_configuracioncategoria f on f.idcategoria=c.ingreso_bienes_ivacero and f.idempresa = $idempresa 
                                        where f.idcategoria is null
                                    union
                                    select c.id from con_categoriaventa c left join con_configuracioncategoria f on f.idcategoria=c.ingreso_bienes_ivanocero and f.idempresa = $idempresa 
                                        where f.idcategoria is null
                                    union
                                    select c.id from con_categoriaventa c left join con_configuracioncategoria f on f.idcategoria=c.ingreso_servicios_ivacero and f.idempresa = $idempresa 
                                        where f.idcategoria is null  
                                    union
                                    select c.id from con_categoriaventa c left join con_configuracioncategoria f on f.idcategoria=c.ingreso_servicios_ivanocero and f.idempresa = $idempresa 
                                        where f.idcategoria is null    
                                    union
                                    select c.id from con_categoriaventa c left join con_configuracioncategoria f on f.idcategoria=c.monto_iva and f.idempresa = $idempresa 
                                        where f.idcategoria is null
                                    union
                                    select c.id from con_categoria c 
                                        inner join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                        where c.idtipocategoria=1 and cfg.idcuenta is null
                                    union
                                    select c.id from con_categoria c 
                                        left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                        where c.idtipocategoria=3 and cfg.idcuenta is null");
                          
        $resultado = $query->result();
        return count($resultado) == 0;
    }

    public function cuentas_configuradas_cobro($idempresa){
        $query = $this->db->query("select c.id_formapago as id from formapago c 
                                        left join con_cuentaclienteformapago f on f.idformapago=c.id_formapago and f.idempresa = $idempresa
                                        where c.id_formapago != 1 AND f.idcuenta is null                         
                                   union
                                   select c.id from con_categoria c 
                                     left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                     where c.idtipocategoria=1 and cfg.idcuenta is null
                                   union
                                    select c.id from deposito_efectivo c 
                                       inner join sucursal s on s.id_sucursal = c.idsucursal and s.id_empresa = $idempresa
                                       where c.idtipo = 1 and c.idcuentacontable is null");

        $resultado = $query->result();
        return count($resultado) == 0;
    }

    public function cuentas_configuradas_pago($idempresa){
        $query = $this->db->query("select c.id_formapago as id from formapago c 
                                        left join con_cuentaproveedorformapago f on f.idformapago=c.id_formapago and f.idempresa = $idempresa
                                        where c.id_formapago != 1 AND f.idcuenta is null                         
                                   union
                                   select c.id from con_categoria c 
                                     left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                     where c.idtipocategoria=2 and cfg.idcuenta is null
                                   union
                                    select c.id from deposito_efectivo c 
                                        inner join sucursal s on s.id_sucursal = c.idsucursal and s.id_empresa = $idempresa
                                        where c.idtipo = 2 and c.idcuentacontable is null  ");

        $resultado = $query->result();
        return count($resultado) == 0;
    }

    public function cuentas_configuradas_compra($idempresa){
        $query = $this->db->query("select c.id from con_categoriacompra c left join con_configuracioncategoria f on f.idcategoria=c.monto_iva_compra and f.idempresa = $idempresa 
                                        where f.idcategoria is null
                                    union
                                    select c.id from con_categoria c 
                                        inner join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                        where c.idtipocategoria=2 and cfg.idcuenta is null
                                    union
                                    select c.id from con_categoria c 
                                        left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                        where c.idtipocategoria=3 and cfg.idcuenta is null");
                          
        $resultado = $query->result();
        return count($resultado) == 0;
    }

    public function cuentas_configuradas_gasto($idempresa){
        $query = $this->db->query("select c.id from con_categoriacompra c left join con_configuracioncategoria f on f.idcategoria=c.monto_iva_gasto and f.idempresa = $idempresa 
                                        where f.idcategoria is null
                                    union
                                    select c.id from con_categoria c 
                                        left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                        where c.idtipocategoria=2 and cfg.idcuenta is null
                                    union
                                    select c.id_cat_gas as id from gastos_categorias c 
                                        left join con_cuentacategoriagasto f on f.idcategoria=c.id_cat_gas  and f.idempresa = $idempresa
                                        where f.idcuenta is null");
                          
        $resultado = $query->result();
        return count($resultado) == 0;
    }

    public function cuentas_configuradas_ingresoinv($idempresa){
        $query = $this->db->query("select c.id from con_categoria c 
                                        left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                        where c.idtipocategoria=3 and cfg.idcuenta is null
                                    union
                                    select c.id from con_categoria c 
                                        left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                        where c.idtipocategoria=11 and cfg.idcuenta is null");
                          
        $resultado = $query->result();
        return count($resultado) == 0;
    }

    public function cuentas_configuradas_egresoinv($idempresa){
        $query = $this->db->query("select c.id from con_categoria c 
                                        left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                        where c.idtipocategoria=3 and cfg.idcuenta is null
                                    union
                                    select c.id from con_categoria c 
                                        left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                        where c.idtipocategoria=12 and cfg.idcuenta is null");
                          
        $resultado = $query->result();
        return count($resultado) == 0;
    }

    public function cuentas_configuradas_retencioncompra($idempresa){
        $query = $this->db->query("select c.id from con_categoria c 
                                        left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and 
                                                                              cfg.idempresa = $idempresa
                                        where c.idtipocategoria=2 and cfg.idcuenta is null
                                    union
                                    select c.id from con_categoria c 
                                        left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                        where c.idtipocategoria=5 and cfg.idcuenta is null
                                    union
                                    select c.id from con_categoria c 
                                        left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                        where c.idtipocategoria=7 and cfg.idcuenta is null");
                          
        $resultado = $query->result();
        return count($resultado) == 0;
    }


    public function cuentas_configuradas_retencionventa($idempresa){
        $query = $this->db->query("select c.id from con_categoria c 
                                        left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and 
                                                                              cfg.idempresa = $idempresa
                                        where c.idtipocategoria=1 and cfg.idcuenta is null
                                    union
                                    select c.id from con_categoria c 
                                        left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                        where c.idtipocategoria=4 and cfg.idcuenta is null
                                    union
                                    select c.id from con_categoria c 
                                        left join con_configuracioncategoria cfg on cfg.idcategoria = c.id and cfg.idempresa = $idempresa
                                        where c.idtipocategoria=6 and cfg.idcuenta is null");
                          
        $resultado = $query->result();
        return count($resultado) == 0;
    }

    public function factura_con_abonos($iddoc){
        $query = $this->db->query("select count(*) from venta_formapago
                                        where id_venta = $iddoc");

        $resultado = $query->result();
        return count($resultado) > 0;
    }
        
    public function elimina_documento_comprobante($iddoc, $nrodoc, $tipocomprobante){
        $query = $this->db->query("SELECT cd.idcomprobante, cd.iddocreferencia, c.idsucursal 
                                     FROM con_comprobantedocumento cd
                                     INNER JOIN con_comprobante c on c.id = cd.idcomprobante
                                     WHERE cd.iddocreferencia = $iddoc AND 
                                           c.idtipocomprobante = $tipocomprobante/* AND 
                                           c.idestado = 2*/");
        $resultado = $query->result();
        if ($resultado){
            $cmp = $resultado[0]->idcomprobante;
            $idsucursal = $resultado[0]->idsucursal;
            $this->db->query("DELETE FROM con_comprobantedocumento 
                                WHERE idcomprobante = $cmp AND iddocreferencia = $iddoc");

            $query = $this->db->query("SELECT iddocreferencia FROM con_comprobantedocumento 
                                         WHERE idcomprobante = $cmp AND iddocreferencia != $iddoc");
            $resultado = $query->result();
            $arrdoc = [];
            foreach($resultado as $item){
                $arrdoc[] = $item->iddocreferencia;
            }
            $objtipo = $this->sel_tipoasiento_id($tipocomprobante);
            $motivo = 'Eliminación de ' . $objtipo->nombre . ' ' . $nrodoc;
            if (count($arrdoc) > 0){
                $this->actualiza_monto_comprobante($idsucursal, $cmp, $tipocomprobante, $arrdoc, $motivo);
            }
            else{
                $this->anular_comprobante($cmp, $motivo);                
            }     
        }           
    }

    public function anula_comprobante_documento($iddoc, $nrodoc, $tipocomprobante){
        $query = $this->db->query("SELECT cd.idcomprobante, cd.iddocreferencia, c.idsucursal 
                                     FROM con_comprobantedocumento cd
                                     INNER JOIN con_comprobante c on c.id = cd.idcomprobante
                                     WHERE cd.iddocreferencia = $iddoc AND c.idtipocomprobante = $tipocomprobante");
        $resultado = $query->result();
        if ($resultado){
            $cmp = $resultado[0]->idcomprobante;
            $idsucursal = $resultado[0]->idsucursal;

            $objtipo = $this->sel_tipoasiento_id($tipocomprobante);
            $motivo = 'Modificación de ' . $objtipo->nombre . ' ' . $nrodoc;

            $this->anular_comprobante($cmp, $motivo);                

        }           
    }

    public function actualiza_monto_comprobante($idsucursal, $cmp, $tipocomprobante, $arrdoc, $motivo){
        $usuario = $this->session->userdata("sess_id");
        if ($tipocomprobante == 5){
            $detalles = $this->sel_contabilizacion_cobro($arrdoc, $idsucursal, '', '');
        }

        $monto = 0;  
        if ($detalles){               
            foreach($detalles as $detalle){
                if ($detalle->debito == 1) {
                    $monto += $detalle->valor;
                }    
            }
        }

        $this->db->query("UPDATE con_saldo s
                           INNER JOIN con_comprobantedetalle d on d.idcuenta = s.idcuenta                                                                     
                           INNER JOIN con_comprobante c on c.id = d.idcomprobante AND 
                                                           c.idsucursal = s.idsucursal
                           SET s.saldo = s.saldo + d.valor * (CASE d.debitocredito WHEN 0 THEN 1 ELSE -1 END)  
                           WHERE d.idcomprobante = $cmp");

        $this->db->query("UPDATE con_comprobante SET 
                                monto = $monto, 
                                fechamodificacion = now()
                            WHERE id = $cmp;");

        $this->db->delete('con_comprobantedetalle', array('idcomprobante' => $cmp)); 
        foreach($detalles as $detalle){
            $this->db->insert('con_comprobantedetalle', array(
                                'idcomprobante'=> $cmp,
                                'idcuenta'=> $detalle->idcuenta,
                                'debitocredito'=> $detalle->debito,
                                'valor'=> $detalle->valor,
                                'concepto'=> $detalle->concepto
                                ));
        }
        $this->confirmar_comprobante($cmp);                                  
        $this->db->insert('con_comprobantemodificacion', array(
                                    'idcomprobante'=> $cmp,
                                    'idusuario'=> $usuario,
                                    'motivomodificacion'=> $motivo
                                  ));

    }

    public function upd_tipoasiento_prefijo($id, $prefijo){
        $this->db->update('con_tipocomprobante', array('prefijo' => $prefijo), array('id' => $id));
    }
        
    public function actualiza_tipocmp_sucursal(){
        $this->db->query("INSERT INTO con_tipocomprobante_sucursal
                            SELECT t.id, s.id_sucursal, 1
                              FROM con_tipocomprobante t, sucursal s
                              WHERE NOT EXISTS (SELECT * FROM con_tipocomprobante_sucursal
                                                  WHERE idtipo = t.id AND idsucursal = s.id_sucursal)");
    }

    public function sel_tipocmp_sucursal($sucursal){
        $this->db->select('id, nombre, abreviatura, prefijo, contador');
        $this->db->from('con_tipocomprobante');
        $this->db->join('con_tipocomprobante_sucursal', 'con_tipocomprobante_sucursal.idtipo = con_tipocomprobante.id'); 
        $this->db->where('idsucursal', $sucursal); 
        $query = $this->db->get();       
        $result = $query->result();
        return $result;     
    }

    public function upd_tipocmp_sucursal($sucursal, $lista, $automatico){
        foreach($lista as $item){
            $this->db->update('con_tipocomprobante_sucursal', 
                            array('contador' => $item['contador']), 
                            array('idtipo' => $item['id'], 'idsucursal' => $sucursal)
                            );
        }
        $this->db->update('sucursal', 
                            array('contabilizacion_automatica' => $automatico), 
                            array('id_sucursal' => $sucursal)
                            );
    }

}
