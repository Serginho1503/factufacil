<?php

/*------------------------------------------------
  ARCHIVO: Contab_comprobantes.php
  DESCRIPCION: Contiene los métodos relacionados con comprobantes.
  
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class Contab_balance extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("contabilidad/Contab_balance_model");
        $this->load->Model("Sucursal_model");
        $this->load->Model("Empresa_model");
    }

 
    public function operaciones() {
        date_default_timezone_set("America/Guayaquil");

        $desde = $this->session->userdata("tmp_oper_desde");
        $hasta = $this->session->userdata("tmp_oper_hasta");
        $empresa = $this->session->userdata("tmp_oper_empresa");
        $sucursal = $this->session->userdata("tmp_oper_sucursal");
        $cuenta = $this->session->userdata("tmp_oper_cuenta");
        $pendiente = $this->session->userdata("tmp_oper_pendiente");
  
        if (($desde == NULL) || ($hasta == NULL)){
          $desde = date("Y-m-d"); 
          $hasta = date("Y-m-d"); 
          $empresa = 0;
          $sucursal = 0;
          $cuenta = 0;
          $pendiente = 0;
          $this->session->set_userdata("tmp_oper_desde", NULL);
          if ($desde != NULL) { $this->session->set_userdata("tmp_oper_desde", $desde); } 
          else { $this->session->set_userdata("tmp_oper_desde", NULL); }
          $this->session->set_userdata("tmp_oper_hasta", NULL);
          if ($hasta != NULL) { $this->session->set_userdata("tmp_oper_hasta", $hasta); } 
          else { $this->session->set_userdata("tmp_oper_hasta", NULL); }
          $this->session->set_userdata("tmp_oper_empresa", NULL);
          if ($empresa != NULL) { $this->session->set_userdata("tmp_oper_empresa", $empresa); } 
          else { $this->session->set_userdata("tmp_oper_empresa", 0); }
          $this->session->set_userdata("tmp_oper_sucursal", NULL);
          if ($sucursal != NULL) { $this->session->set_userdata("tmp_oper_sucursal", $sucursal); } 
          else { $this->session->set_userdata("tmp_oper_sucursal", 0); }
          $this->session->set_userdata("tmp_oper_cuenta", NULL);
          if ($cuenta != NULL) { $this->session->set_userdata("tmp_oper_cuenta", $cuenta); } 
          else { $this->session->set_userdata("tmp_oper_cuenta", 0); }
          $this->session->set_userdata("tmp_oper_pendiente", NULL);
          if ($pendiente != NULL) { $this->session->set_userdata("tmp_oper_pendiente", $pendiente); } 
          else { $this->session->set_userdata("tmp_oper_pendiente", 0); }
        }  
        $data["tmpsucursal"] = $sucursal;
        $data["tmpdesde"] = $desde;
        $data["tmphasta"] = $hasta;
        $data["tmpcuenta"] = $cuenta;
        $data["tmppendiente"] = $pendiente;

        $empresas = $this->Empresa_model->lst_empresa();
        $data["empresa"] = $empresas;

        if (count($empresas) > 0){
            $sucursales = $this->Sucursal_model->lst_sucursal_empresa($empresas[0]->id_emp);
            $data["sucursal"] = $sucursales;
        }

        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_operaciones";
        $this->load->view("layout", $data);
    }

    public function get_sucursal_empresa(){
        $empresa = $this->input->post('empresa'); 
        $resu = $this->Sucursal_model->lst_sucursal_empresa($empresa);
        print json_encode($resu); 
    }

    public function tmp_oper_fecha() {
        $this->session->unset_userdata("tmp_oper_desde"); 
        $this->session->unset_userdata("tmp_oper_hasta");
        $this->session->unset_userdata("tmp_oper_empresa");
        $this->session->unset_userdata("tmp_oper_sucursal");
        $this->session->unset_userdata("tmp_oper_cuenta");
        $this->session->unset_userdata("tmp_oper_pendiente");
        $pendiente = $this->input->post("pendiente");
        $sucursal = $this->input->post("sucursal");
        $empresa = $this->input->post("empresa");
        $cuenta = $this->input->post("cuenta");
        $desde = $this->input->post("desde");
        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = $this->input->post("hasta");
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        $this->session->set_userdata("tmp_oper_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_oper_desde", $desde);} 
        else { $this->session->set_userdata("tmp_oper_desde", NULL); }
        $this->session->set_userdata("tmp_oper_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_oper_hasta", $hasta);} 
        else { $this->session->set_userdata("tmp_oper_hasta", NULL);}
        $this->session->set_userdata("tmp_oper_empresa", NULL);
        if ($empresa != NULL) { $this->session->set_userdata("tmp_oper_empresa", $empresa);} 
        else { $this->session->set_userdata("tmp_oper_empresa", NULL);}
        $this->session->set_userdata("tmp_oper_sucursal", NULL);
        if ($sucursal != NULL) { $this->session->set_userdata("tmp_oper_sucursal", $sucursal);} 
        else { $this->session->set_userdata("tmp_oper_sucursal", 0);}
        $this->session->set_userdata("tmp_oper_cuenta", NULL);
        if ($cuenta != NULL) { $this->session->set_userdata("tmp_oper_cuenta", $cuenta);} 
        else { $this->session->set_userdata("tmp_oper_cuenta", 0);}
        $this->session->set_userdata("tmp_oper_pendiente", NULL);
        if ($pendiente != NULL) { $this->session->set_userdata("tmp_oper_pendiente", $pendiente);} 
        else { $this->session->set_userdata("tmp_oper_pendiente", 0);}
        $arr['resu'] = 1;
        print json_encode($arr);
      } 

      public function listadoOperaciones() {
        $desde = $this->session->userdata("tmp_oper_desde");
        $hasta = $this->session->userdata("tmp_oper_hasta");
        $empresa = $this->session->userdata("tmp_oper_empresa");
        $sucursal = $this->session->userdata("tmp_oper_sucursal");
        $cuenta = $this->session->userdata("tmp_oper_cuenta");
        $pendiente = $this->session->userdata("tmp_oper_pendiente");
        $registro = $this->Contab_balance_model->sel_operaciones($sucursal, $cuenta, $desde, $hasta, $pendiente);
        $tabla = "";
        foreach ($registro as $row) {
            $fecha = str_replace('-', '/', $row->fecha); $fecha = date("d/m/Y", strtotime($fecha));
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Imprimir\" id=\"'.$row->id.'\" class=\"btn btn-danger btn-xs btn-grad print_cmp\"><i class=\"fa fa-file-pdf-o\"></i></a> ';
            $ver .= '</div>';

            $tabla.='{  "ver":"' .$ver. '",
                        "id":"' .$row->id. '",
                        "fecha":"' .$fecha. '",
                        "numero":"' .$row->numero. '",
                        "debito":"' .$row->debito. '",
                        "credito":"' .$row->credito. '",
                        "saldo":"' .$row->saldo. '",
                        "referencia":"' .addslashes($row->referencia). '",
                        "descripcion":"' .addslashes($row->descripcion). '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function balancesumasaldo() {
        date_default_timezone_set("America/Guayaquil");

        $desde = $this->session->userdata("tmp_balsum_desde");
        $hasta = $this->session->userdata("tmp_balsum_hasta");
        $empresa = $this->session->userdata("tmp_balsum_empresa");
        $sucursal = $this->session->userdata("tmp_balsum_sucursal");
        $pendiente = $this->session->userdata("tmp_balsum_pendiente");
  
        if (($desde == NULL) || ($hasta == NULL)){
          $desde = date("Y-m-d"); 
          $hasta = date("Y-m-d"); 
          $empresa = 0;
          $sucursal = 0;
          $pendiente = 0;
          $this->session->set_userdata("tmp_balsum_desde", NULL);
          if ($desde != NULL) { $this->session->set_userdata("tmp_balsum_desde", $desde); } 
          else { $this->session->set_userdata("tmp_balsum_desde", NULL); }
          $this->session->set_userdata("tmp_balsum_hasta", NULL);
          if ($hasta != NULL) { $this->session->set_userdata("tmp_balsum_hasta", $hasta); } 
          else { $this->session->set_userdata("tmp_balsum_hasta", NULL); }
          $this->session->set_userdata("tmp_balsum_empresa", NULL);
          if ($empresa != NULL) { $this->session->set_userdata("tmp_balsum_empresa", $empresa); } 
          else { $this->session->set_userdata("tmp_balsum_empresa", 0); }
          $this->session->set_userdata("tmp_balsum_sucursal", NULL);
          if ($sucursal != NULL) { $this->session->set_userdata("tmp_balsum_sucursal", $sucursal); } 
          else { $this->session->set_userdata("tmp_balsum_sucursal", 0); }
          $this->session->set_userdata("tmp_balsum_pendiente", NULL);
          if ($pendiente != NULL) { $this->session->set_userdata("tmp_balsum_pendiente", $pendiente); } 
          else { $this->session->set_userdata("tmp_balsum_pendiente", 0); }
        }  
        $data["tmpsucursal"] = $sucursal;
        $data["tmpdesde"] = $desde;
        $data["tmphasta"] = $hasta;
        $data["pendiente"] = $pendiente;

        $empresas = $this->Empresa_model->lst_empresa();
        $data["empresa"] = $empresas;

        if (count($empresas) > 0){
            $sucursales = $this->Sucursal_model->lst_sucursal_empresa($empresas[0]->id_emp);
            $data["sucursales"] = $sucursales;
        }

        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_balance_sumasaldo";
        $this->load->view("layout", $data);
    }

    public function tmp_balsum_fecha() {
        $this->session->unset_userdata("tmp_balsum_desde"); 
        $this->session->unset_userdata("tmp_balsum_hasta");
        $this->session->unset_userdata("tmp_balsum_empresa");
        $this->session->unset_userdata("tmp_balsum_sucursal");
        $this->session->unset_userdata("tmp_balsum_pendiente");
        $pendiente = $this->input->post("pendiente");
        $sucursal = $this->input->post("sucursal");
        $empresa = $this->input->post("empresa");
        $desde = $this->input->post("desde");
        $desde = str_replace('/', '-', $desde); 
        $desde = date("Y-m-d", strtotime($desde));
        $hasta = $this->input->post("hasta");
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        $this->session->set_userdata("tmp_balsum_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_balsum_desde", $desde);} 
        else { $this->session->set_userdata("tmp_balsum_desde", NULL); }
        $this->session->set_userdata("tmp_balsum_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_balsum_hasta", $hasta);} 
        else { $this->session->set_userdata("tmp_balsum_hasta", NULL);}
        $this->session->set_userdata("tmp_balsum_empresa", NULL);
        if ($empresa != NULL) { $this->session->set_userdata("tmp_balsum_empresa", $empresa);} 
        else { $this->session->set_userdata("tmp_balsum_empresa", NULL);}
        $this->session->set_userdata("tmp_balsum_sucursal", NULL);
        if ($sucursal != NULL) { $this->session->set_userdata("tmp_balsum_sucursal", $sucursal);} 
        else { $this->session->set_userdata("tmp_balsum_sucursal", 0);}
        $this->session->set_userdata("tmp_balsum_pendiente", NULL);
        if ($pendiente != NULL) { $this->session->set_userdata("tmp_balsum_pendiente", $pendiente);} 
        else { $this->session->set_userdata("tmp_balsum_pendiente", 0);}
        $arr['resu'] = 1;
        print json_encode($arr);
    } 

    public function listadoBalancesumasaldo() {
        $desde = $this->session->userdata("tmp_balsum_desde");
        $hasta = $this->session->userdata("tmp_balsum_hasta");
        $empresa = $this->session->userdata("tmp_balsum_empresa");
        $sucursal = $this->session->userdata("tmp_balsum_sucursal");
        $pendiente = $this->session->userdata("tmp_balsum_pendiente");
        $registro = $this->Contab_balance_model->sel_balancesumasaldo($sucursal, $desde, $hasta, $pendiente);
        $tabla = "";
        foreach ($registro as $row) {
            $tabla.='{  "id":"' .$row->id. '",
                        "cuenta":"' .$row->codigocuenta. '",
                        "descripcion":"' .addslashes($row->descripcion). '",
                        "saldoanterior":"' .$row->saldoanterior. '",
                        "debito":"' .$row->debito. '",
                        "credito":"' .$row->credito. '",
                        "saldo":"' .$row->saldo. '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function balancesituacion() {
        date_default_timezone_set("America/Guayaquil");

        $hasta = $this->session->userdata("tmp_balsit_hasta");
        $sucursal = $this->session->userdata("tmp_balsit_sucursal");
        $nivel = $this->session->userdata("tmp_balsit_nivel");
        $pendiente = $this->session->userdata("tmp_balsit_pendiente");
  
        if (($hasta == NULL)){
          $hasta = date("Y-m-d"); 
          $sucursal = 0;
          $pendiente = 0;
          $this->session->set_userdata("tmp_balsit_hasta", NULL);
          if ($hasta != NULL) { $this->session->set_userdata("tmp_balsit_hasta", $hasta); } 
          else { $this->session->set_userdata("tmp_balsit_hasta", NULL); }
          $this->session->set_userdata("tmp_balsit_sucursal", NULL);
          if ($sucursal != NULL) { $this->session->set_userdata("tmp_balsit_sucursal", $sucursal); } 
          else { $this->session->set_userdata("tmp_balsit_sucursal", 0); }
          $this->session->set_userdata("tmp_balsit_nivel", NULL);
          if ($nivel != NULL) { $this->session->set_userdata("tmp_balsit_nivel", $nivel); } 
          else { $this->session->set_userdata("tmp_balsit_nivel", 0); }
          $this->session->set_userdata("tmp_balsit_pendiente", NULL);
          if ($pendiente != NULL) { $this->session->set_userdata("tmp_balsit_pendiente", $pendiente); } 
          else { $this->session->set_userdata("tmp_balsit_pendiente", 0); }
        }  
        $data["tmpsucursal"] = $sucursal;
        $data["tmpnivel"] = $nivel;
        $data["tmphasta"] = $hasta;
        $data["tmppendiente"] = $pendiente;

        $empresas = $this->Empresa_model->lst_empresa();
        $data["empresa"] = $empresas;

        $niveles = $this->Contab_balance_model->sel_niveles();
        $data["niveles"] = $niveles;

        if (count($empresas) > 0){
            $sucursales = $this->Sucursal_model->lst_sucursal_empresa($empresas[0]->id_emp);
            $data["sucursales"] = $sucursales;
        }

        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_balance_situacion";
        $this->load->view("layout", $data);
    }

    public function tmp_balsit_fecha() {
        $this->session->unset_userdata("tmp_balsit_nivel"); 
        $this->session->unset_userdata("tmp_balsit_hasta");
        $this->session->unset_userdata("tmp_balsit_sucursal");
        $this->session->unset_userdata("tmp_balsit_pendiente");
        $pendiente = $this->input->post("pendiente");
        $sucursal = $this->input->post("sucursal");
        $nivel = $this->input->post("nivel");
        $hasta = $this->input->post("hasta");
        $hasta = str_replace('/', '-', $hasta); 
        $hasta = date("Y-m-d", strtotime($hasta));
        $this->session->set_userdata("tmp_balsit_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_balsit_hasta", $hasta);} 
        else { $this->session->set_userdata("tmp_balsit_hasta", NULL);}
        $this->session->set_userdata("tmp_balsit_nivel", NULL);
        if ($nivel != NULL) { $this->session->set_userdata("tmp_balsit_nivel", $nivel);} 
        else { $this->session->set_userdata("tmp_balsit_nivel", NULL);}
        $this->session->set_userdata("tmp_balsit_sucursal", NULL);
        if ($sucursal != NULL) { $this->session->set_userdata("tmp_balsit_sucursal", $sucursal);} 
        else { $this->session->set_userdata("tmp_balsit_sucursal", 0);}
        $this->session->set_userdata("tmp_balsit_pendiente", NULL);
        if ($pendiente != NULL) { $this->session->set_userdata("tmp_balsit_pendiente", $pendiente);} 
        else { $this->session->set_userdata("tmp_balsit_pendiente", 0);}
        $arr['resu'] = 1;
        print json_encode($arr);
    } 

    public function listadoBalancesituacion() {
        $hasta = $this->session->userdata("tmp_balsit_hasta");
        $nivel = $this->session->userdata("tmp_balsit_nivel");
        $sucursal = $this->session->userdata("tmp_balsit_sucursal");
        $pendiente = $this->session->userdata("tmp_balsit_pendiente");
        $registro = $this->Contab_balance_model->sel_balancesituacion($sucursal, $hasta, $nivel, $pendiente);
        $tabla = "";
        foreach ($registro as $row) {
            $tabla.='{  "id":"' .$row->id. '",
                        "cuenta":"' .$row->codigocuenta. '",
                        "grupo":"' .addslashes($row->grupo). '",
                        "descripcion":"' .addslashes($row->descripcion). '",
                        "valor":"' .$row->valor. '",
                        "nivel":"' .$row->nivel. '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function reportesituacionXLS(){
        $hasta = $this->session->userdata("tmp_balsit_hasta");
        $nivel = $this->session->userdata("tmp_balsit_nivel");
        $sucursal = $this->session->userdata("tmp_balsit_sucursal");
        $pendiente = $this->session->userdata("tmp_balsit_pendiente");
        $objsucursal = $this->Sucursal_model->sel_suc_id($sucursal);
        $balance = $this->Contab_balance_model->sel_balancesituacion($sucursal, $hasta, $nivel, $pendiente);
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('BalanceSituacion');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Balance de Situacion');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->setCellValue('A2', 'Sucursal: ' . addslashes($objsucursal->nom_sucursal));
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A2:D2')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A2:B2');

        $hasta = str_replace('-', '/', $hasta); 
        $hasta = date("d/m/Y", strtotime($hasta)); 
        $this->excel->getActiveSheet()->setCellValue('C2', 'Hasta: ' . $hasta);

        $this->excel->getActiveSheet()->setCellValue('D2', 'Nivel: ' . $nivel);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Grupo');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Cuenta');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Descripción');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Valor');

        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);

        $numberFormat = '_(#,##0.00_);_((#,##0.00);_("-"??_);_(@_)';
        $currencyFormat = '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';

        $fila = 4;
        foreach ($balance as $item) {
        
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $item->grupo);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $item->codigocuenta);
            $this->excel->getActiveSheet()->getStyle('B'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $item->descripcion);            
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $item->valor);
            $this->excel->getActiveSheet()->getStyle('D'.$fila)->getNumberFormat()->setFormatCode($numberFormat);
            $fila++;          
        }    
      
        $filename='balancesituacion.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

    public function reportesumasaldoXLS(){
        $desde = $this->session->userdata("tmp_balsum_desde");
        $hasta = $this->session->userdata("tmp_balsum_hasta");
        $empresa = $this->session->userdata("tmp_balsum_empresa");
        $sucursal = $this->session->userdata("tmp_balsum_sucursal");
        $pendiente = $this->session->userdata("tmp_balsum_pendiente");
        $balance = $this->Contab_balance_model->sel_balancesumasaldo($sucursal, $desde, $hasta, $pendiente);
        $objsucursal = $this->Sucursal_model->sel_suc_id($sucursal);
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('BalanceSumaSaldo');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Balance de Sumas y Saldos');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->setCellValue('A2', 'Sucursal: ' . addslashes($objsucursal->nom_sucursal));
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A2:D2')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A2:B2');

        $desde = str_replace('-', '/', $desde); 
        $desde = date("d/m/Y", strtotime($desde)); 
        $this->excel->getActiveSheet()->setCellValue('C2', 'Desde: ' . $desde);

        $hasta = str_replace('-', '/', $hasta); 
        $hasta = date("d/m/Y", strtotime($hasta)); 
        $this->excel->getActiveSheet()->setCellValue('D2', 'Hasta: ' . $hasta);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Cuenta');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Descripción');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Saldo Anterior');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Débito');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Crédito');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Saldo');

        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);

        $numberFormat = '_(#,##0.00_);_((#,##0.00);_("-"??_);_(@_)';
        $currencyFormat = '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';

        $fila = 4;
        $filaini = $fila;
        foreach ($balance as $item) {
        
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $item->codigocuenta);
            $this->excel->getActiveSheet()->getStyle('A'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $item->descripcion);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $item->saldoanterior);            
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $item->debito);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, $item->credito);
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, $item->saldo);
            $this->excel->getActiveSheet()->getStyle('C'.$fila.':F'.$fila)->getNumberFormat()->setFormatCode($numberFormat);
            $fila++;          
        }    
        $fila++;          
      
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, "TOTALES");
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=SUM(C'.($filaini).':C'.($fila-2).')');
        $this->excel->getActiveSheet()->setCellValue('D' . $fila, '=SUM(D'.($filaini).':D'.($fila-2).')');
        $this->excel->getActiveSheet()->setCellValue('E' . $fila, '=SUM(E'.($filaini).':E'.($fila-2).')');
        $this->excel->getActiveSheet()->setCellValue('F' . $fila, '=SUM(F'.($filaini).':F'.($fila-2).')');
        $this->excel->getActiveSheet()->getStyle('C'.$fila.':F'.$fila)->getNumberFormat()->setFormatCode($numberFormat);


        $filename='balancesumasaldo.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  

  
}

?>