<?php

/*------------------------------------------------
  ARCHIVO: Cajacierre.php
  DESCRIPCION: Contiene los métodos relacionados con el cierre de Caja.
  FECHA DE CREACIÓN: 05/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Cajacierre extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("cajacierre_model");
        $this->load->Model("empresa_model");       
        $this->load->Model("cajamov_model");  
        $this->load->Model("Correo_model");     
        $this->load->Model("Sucursal_model");     
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $idusu = $this->session->userdata("sess_id");
        $data["base_url"] = base_url();
        
        $cajas = $this->cajacierre_model->lst_cajaefectivo_abierta();
        $data["cajas"] = $cajas;

        if(count($cajas) == 1){
            $idcaja = $cajas[0]->id_caja;
        }else{
            $idcaja = $this->session->userdata("tmp_cierrecaja");            
        }


        if ($idcaja == NULL){
            if ($cajas){
                $idcaja = $cajas[0]->id_caja;
            } else { $idcaja = 0; }
        } 
        $caja = $this->cajacierre_model->datosapertura($idcaja);
        $desgloseformapago = $this->cajacierre_model->ventaformapago($idcaja);
        $idmov = 0;
        if ($caja) { $idmov = $caja->id_mov; }
        $cajagasto = $this->cajacierre_model->selcajagastos($idmov, $idusu);  
        $monto = $this->cajacierre_model->montogasto($idmov, $idusu); 
        $data["caja"] = $caja;          
        $data["desgloseformapago"] = $desgloseformapago;          
        $data["cajag"] = $cajagasto;
        $data["egreso"] = $monto;
        $data["content"] = "cajacierre";
        $this->load->view("layout", $data);
    }

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_cierrecaja() {

        $this->session->unset_userdata("tmp_cierrecaja"); 
        
        $id = $this->input->post("id");

        $this->session->set_userdata("tmp_cierrecaja", NULL);
        if ($id != NULL) $this->session->set_userdata("tmp_cierrecaja", $id);

        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function existecajaefectivo_abierta() {
        $caja = $this->cajacierre_model->existecajaefectivo_abierta();
        $arr['resu'] = $caja;
        print json_encode($arr);
    }


    public function guardar(){
        $idmov = $this->input->post("idmov");
        $ventastotales = $this->input->post("ventastotales");
        $ventastotales = str_replace(',','',$ventastotales); 
        $abonoservicio = $this->input->post("abonoservicio");
        $abonoservicio = str_replace(',','',$abonoservicio); 
        $abonocredito = $this->input->post("abonocredito");
        $abonocredito = str_replace(',','',$abonocredito); 
        $abonocreditoefectivo = $this->input->post("abonocreditoefectivo");
        $abonocreditoefectivo = str_replace(',','',$abonocreditoefectivo); 
        $montonoefectivo = $this->input->post("montonoefectivo");
        $montonoefectivo = str_replace(',','',$montonoefectivo);         
        $montoegreso = $this->input->post("montoegreso");
        $montoegreso = str_replace(',','',$montoegreso);         
        $saldo = $this->input->post("saldo");
        $saldo = str_replace(',','',$saldo);         
        $totalcaja = $this->input->post("totalcaja");
        $totalcaja = str_replace(',','',$totalcaja);             
        $sobrante = $this->input->post("sobrante");
        $sobrante = str_replace(',','',$sobrante); 
        $faltante = $this->input->post("faltante");
        $faltante = str_replace(',','',$faltante); 
        $desefectivo = $this->input->post("desefectivo");
        $desefectivo = str_replace(',','',$desefectivo); 
        $descheque = $this->input->post("descheque");
        $descheque = str_replace(',','',$descheque); 
        $destarcre = $this->input->post("destarcre");
        $destarcre = str_replace(',','',$destarcre); 
        $destardeb = $this->input->post("destardeb");
        $destardeb = str_replace(',','',$destardeb); 
        $destarpre = $this->input->post("destarpre");
        $destarpre = str_replace(',','',$destarpre); 
        $destransf = $this->input->post("destransf");
        $destransf = str_replace(',','',$destransf); 
        $desdinele = $this->input->post("desdinele");
        $desdinele = str_replace(',','',$desdinele); 
        $desotros = $this->input->post("desotros");
        $desotros = str_replace(',','',$desotros); 
        $desvencre = $this->input->post("desvencre");
        $desvencre = str_replace(',','',$desvencre);         
        $obs = $this->input->post("obs");
        if ($obs == '') $obs = "Sin Observaciones";

        $compras = 0;
        $salida = 0;
        $justi = " ";

        /* SE ACTUALIZA EL REGISTRO DEL USUARIO */
        $resu = $this->cajacierre_model->guardar($idmov, $compras, $obs, $salida, $justi, $ventastotales, 
                                                 $abonoservicio, $montonoefectivo, $montoegreso, $saldo, 
                                                 $totalcaja, $sobrante, $faltante, $desefectivo, $descheque, 
                                                 $destarcre, $destardeb, $destarpre, $destransf, $desdinele, 
                                                 $desotros, $desvencre,
                                                 $abonocredito, $abonocreditoefectivo);
    //    $this->respaldo();
        print json_encode($idmov);

    }

    public function envmail(){
        $idmov = $this->input->post('idmov');
        if ($idmov > 0){
            try{
                $resu = $this->correoCierrecaja($idmov);
            }
            catch(Exception $e)
            {
                $resu = 0;
            }
        }  

        print json_encode($resu);      
    }


    /* Guardar la Apertura 
    public function guardar(){

        $idmov = $this->input->post("idmov");
        $venta = $this->input->post("venta");
        $tarjeta = $this->input->post("tarjeta");
        $egresos = $this->input->post("egresos");
        $totalcaja = $this->input->post("totalcaja");
        $obs = $this->input->post("obs");

        if ($tarjeta == '') $tarjeta = 0;
        if ($obs == '') $obs = "Sin Observaciones";
        $venta = str_replace(',','',$venta); 

        $compras = 0;
        $salida = 0;
        $justi = " ";

        // SE ACTUALIZA EL REGISTRO DEL USUARIO 
        $resu = $this->cajacierre_model->guardar($idmov, $venta, $tarjeta, $egresos, $compras, $totalcaja, $obs, $salida, $justi);
            try{
                $this->correoCierrecaja();
            }
            catch(Exception $e)
            {
                $var = 0;
            }
      


        $var = 1;
        print json_encode($var);

    }
*/

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_cierre() {

        $this->session->unset_userdata("tmp_cierre_excel"); 
        
        $idmov = $this->input->post("idmov");
        $fecha_apertura = $this->input->post("fecha_apertura");
        $monto_apertura = $this->input->post("monto_apertura");
        $venta = $this->input->post("venta");
        $tarjeta = $this->input->post("tarjeta");
        $egresos = $this->input->post("egresos");
        $saldo = $this->input->post("saldo");
        $totalcaja = $this->input->post("totalcaja");
        $sobrante = $this->input->post("sobrante");
        $faltante = $this->input->post("faltante");
        $obs = $this->input->post("obs");

        $abonoservicio = $this->input->post("abonoservicio");
        $abonocredito = $this->input->post("abonocredito");

        if ($tarjeta == '') $tarjeta = 0;
        if ($obs == '') $obs = "Sin Observaciones";
        $venta = str_replace(',','',$venta);        

        $datacierre['idmov'] = $idmov;
        $datacierre['fecha_apertura'] = $fecha_apertura;
        $datacierre['monto_apertura'] = $monto_apertura;
        $datacierre['venta'] = $venta;
        $datacierre['tarjeta'] = $tarjeta;
        $datacierre['egresos'] = $egresos;
        $datacierre['saldo'] = $saldo;
        $datacierre['totalcaja'] = $totalcaja;
        $datacierre['sobrante'] = $sobrante;
        $datacierre['faltante'] = $faltante;
        $datacierre['obs'] = $obs;

        $datacierre['abonoservicio'] = $abonoservicio;
        $datacierre['abonocredito'] = $abonocredito;

        $this->session->set_userdata("tmp_cierre_excel", NULL);
        if ($datacierre != NULL) $this->session->set_userdata("tmp_cierre_excel", $datacierre);

        $arr['resu'] = 1;
        print json_encode($arr);
    }


    /* Guardar la Apertura */
    public function exportarexcel(){

        $datacierre = $this->session->userdata("tmp_cierre_excel");

        $idmov = $datacierre['idmov'];
        $fecha_apertura = $datacierre['fecha_apertura'];
        $monto_apertura = $datacierre['monto_apertura'];
        $venta = $datacierre['venta'];
        $tarjeta = $datacierre['tarjeta'];
        $egresos = $datacierre['egresos'];
        $saldo = $datacierre['saldo'];
        $totalcaja = $datacierre['totalcaja'];
        $sobrante = $datacierre['sobrante'];
        $faltante = $datacierre['faltante'];
        $obs = $datacierre['obs'];

        $abonoservicio = $datacierre['abonoservicio'];
        $abonocredito = $datacierre['abonocredito'];

        $objxls = $this->cierrecajaXLS($idmov, $fecha_apertura,$monto_apertura, $venta, $tarjeta, $egresos, 
                                       $saldo, $totalcaja, $sobrante, $faltante, $obs, $abonoservicio,
                                       $abonocredito);
    }
    
    /* Obtener reporte de Cierre de Caja en Excel */
    public function cierrecajaXLS($idmov, $fecha_apertura, $monto_apertura, $venta, $tarjeta, $egresos, $saldo, 
                                  $totalcaja, $sobrante, $faltante, $obs, $abonoservicio,
                                  $abonocredito){
        
        date_default_timezone_set("America/Guayaquil");
        $cierre = date("d/m/Y H:i");
        $idusu = $this->session->userdata("sess_id");

        $empresa = $this->empresa_model->emp_get();

        $movegreso = $this->cajacierre_model->lstegreso($idmov, $idusu);

        $currencyFormat = '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('CierreCaja');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Cierre de Caja');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Empresa');
        $this->excel->getActiveSheet()->setCellValue('B3', $empresa->nom_emp);
        $this->excel->getActiveSheet()->setCellValue('A4', 'RUC');
        $this->excel->getActiveSheet()->setCellValue('B4', $empresa->ruc_emp);
        $this->excel->getActiveSheet()->setCellValue('A5', 'Direccion');
        $this->excel->getActiveSheet()->setCellValue('B5', $empresa->dir_emp);        
        $this->excel->getActiveSheet()->setCellValue('A6', 'Telefono');
        $this->excel->getActiveSheet()->setCellValue('B6', $empresa->tlf_emp);

        $this->excel->getActiveSheet()->setCellValue('A8', 'Fecha de Apertura');
        $this->excel->getActiveSheet()->setCellValue('B8', $fecha_apertura);
        $this->excel->getActiveSheet()->setCellValue('A9', 'Monto Apertura');
        $this->excel->getActiveSheet()->setCellValue('B9', $monto_apertura);
        $this->excel->getActiveSheet()->setCellValue('A10', 'Fecha de Cierre');
        $this->excel->getActiveSheet()->setCellValue('B10', $cierre);

        $this->excel->getActiveSheet()->setCellValue('A12', 'Ventas Totales');        
        $this->excel->getActiveSheet()->setCellValue('B12', $venta);

        $this->excel->getActiveSheet()->setCellValue('A13', 'Abonos Servicios');        
        $this->excel->getActiveSheet()->setCellValue('B13', $abonoservicio);

        $this->excel->getActiveSheet()->setCellValue('A14', 'Abonos Créditos');        
        $this->excel->getActiveSheet()->setCellValue('B14', $abonocredito);

        $this->excel->getActiveSheet()->setCellValue('A15', 'Monto Tarjetas');
        $this->excel->getActiveSheet()->setCellValue('B15', $tarjeta);
        $this->excel->getActiveSheet()->setCellValue('A16', 'Monto Egresos');
        $this->excel->getActiveSheet()->setCellValue('B16', $egresos);        
        $this->excel->getActiveSheet()->setCellValue('A17', 'Saldo');
        $this->excel->getActiveSheet()->setCellValue('B17', $saldo);
        $this->excel->getActiveSheet()->setCellValue('A18', 'Total Caja');
        $this->excel->getActiveSheet()->setCellValue('B18', $totalcaja);

        $this->excel->getActiveSheet()->setCellValue('A19', 'Sobrante');
        $this->excel->getActiveSheet()->setCellValue('B19', $sobrante);
        $this->excel->getActiveSheet()->setCellValue('A20', 'Faltante');
        $this->excel->getActiveSheet()->setCellValue('B20', $faltante);          

        $this->excel->getActiveSheet()->setCellValue('A21', 'Observaciones');
        $this->excel->getActiveSheet()->setCellValue('B21', $obs);  

        $this->excel->getActiveSheet()->setCellValue('A22', 'Nro');
        $this->excel->getActiveSheet()->getStyle('A22:C22')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('B22', 'Descripción');
        $this->excel->getActiveSheet()->setCellValue('C22', 'Cantidad');

        $fila = 23;
        $nro = 0;
        foreach ($movegreso as $me) {
            $nro = $nro + 1;
            $this->excel->getActiveSheet()->setCellValue('A' . $fila, $nro);
            $this->excel->getActiveSheet()->setCellValue('B' . $fila, $me->descripcion);
            $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
            $this->excel->getActiveSheet()->setCellValue('C' . $fila, $me->monto);
            $fila++;            
        }


         
        $filename='cierrecaja.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');        
    }

    public function correoCierrecaja($idmov) {
      $correo = $this->Correo_model->env_correo(); 

      $config = array(
        'protocol' => 'smtp',
        'smtp_host' => $correo->smtp,
        'smtp_user' => $correo->usuario,
        'smtp_pass' => $correo->clave, 
        'smtp_port' => $correo->puerto,
        'smtp_crypto' => 'ssl',
        'smtp_timeout' => '30',
        'mailtype' => 'html',
        'wordwrap' => TRUE,
        'charset' => 'utf-8'
      );

         $this->load->library('email', $config);
         $this->email->set_newline("\r\n");
         $this->email->from($correo->usuario);
         $this->email->subject('Cierre de Caja');
         $mensaje = $this->obtenerMensajeCierrecaja($idmov);
         $this->email->message($mensaje);
         $this->email->to($correo->empresa);
         if($this->email->send(FALSE)){
            return 1;
            /* panchin.romero@gmail.com */
            // echo "enviado<br/>";
            // echo $this->email->print_debugger(array('headers'));
         }else {
            return 0;
            // echo "fallo <br/>";
            // echo "error: ".$this->email->print_debugger(array('headers'));
         }
    }     
  
/*
    public function obtenerMensajeCierrecaja(){
        $empresa = $this->empresa_model->emp_get();
        $idmovcaja = $this->cajamov_model->ultimomovimientocerrado();
        $caja = $this->cajamov_model->sel_cajamov_id($idmovcaja->id_mov);
        $idusu = $this->session->userdata("sess_id");
        $idmov = $idmovcaja->id_mov;
        $movegreso = $this->cajacierre_model->lstegreso($idmov, $idusu);   
        /*
        $saldo = round($caja->monto_apertura + $caja->ingresoefectivo - $caja->salida - $caja->ingresotarjeta,2);   
        $strdif = "";
        if ($saldo != $caja->existente){
            if ( $saldo > $caja->existente){
                $dif = round($saldo - $caja->existente,2); 
                $strdif = "Faltante: " . $dif;
            }
            else{
                $dif = round($caja->existente - $saldo,2);
                $strdif = "Sobrante: " . $dif;
            }
        }
        if ($strdif == "0") $strdif = "";
        */
        /*

        $strdif = "";
        $dife = $caja->diferencia;

        if ($dife > 0){
            $dife = round($dife,2);
            $strdif = "Sobrante: " . $dife;
        }
        else{
            $dife = round($dife,2);
            $strdif = "Faltante: " . $dife;
        }

        if ($strdif == "0") $strdif = "";    

        $tableventa = "";
        $ventanul = $this->cajamov_model->lst_ventaanulada($idmovcaja->id_mov);
        foreach ($ventanul as $row) {
            if ($tableventa == ""){
                $tableventa = "<table width='1000' border='0' align='center'  bgcolor='white' style='margin-top:-20px;'>
                                  <tr style='height:15px;'>
                                    <th>Fecha</th>
                                    <th>Factura</th>
                                    <th>Mesa</th>
                                    <th>Mesero</th>
                                    <th>Monto</th>
                                    <th>Cliente</th>
                                    <th>RUC</th>
                                    <th>Causa</th>
                                  </tr>";                
            }                
            $tableventa.= "<tr style='height:15px;'>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->fecharegistro</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nro_factura</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->mesa</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->mesero</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->montototal</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nom_cliente</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nro_ident</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->causa_anulacion</td>
                          </tr>";
        }
        
        if ($tableventa != ""){
            $tableventa.= "</table>";
        }




        $tablemovegreso = "";
        $nro = 0;
        foreach ($movegreso as $me) {
            $nro = $nro + 1;
            if ($tablemovegreso == ""){
                $tablemovegreso = "<table width='1000' border='0' align='center'  bgcolor='white' style='margin-top:-20px;'>
                                  <tr style='height:15px;'>
                                    <th>Nro</th>
                                    <th>Descripción</th>
                                    <th>Monto</th>
                                  </tr>";                
            }                
            $tablemovegreso.= "<tr style='height:15px;'>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $nro</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $me->descripcion</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $me->monto</td>
                          </tr>";
        }

        if ($tablemovegreso != ""){
            $tablemovegreso.= "</table>";
        }




        $tablegasto = "";
        $gastonul = $this->cajamov_model->lst_gastoanulado($idmovcaja->id_mov);
        foreach ($gastonul as $row) {
            if ($tablegasto == ""){
                $tablegasto = "<table width='1000' border='0' align='center'  bgcolor='white' style='margin-top:-20px;'>
                                  <tr style='height:15px;'>
                                    <th>Fecha</th>
                                    <th>Factura</th>
                                    <th>Monto</th>
                                    <th>Proveedor</th>
                                    <th>RUC</th>
                                    <th>Causa</th>
                                  </tr>";                
            }                
            $tablegasto.= "<tr style='height:15px;'>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->fecha</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nro_factura</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->total</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nom_proveedor</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nro_ide_proveedor</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->causa_anulacion</td>
                          </tr>";
        }

        if ($tablegasto != ""){
            $tablegasto.= "</table>";
        }

        $tablecompra = "";
        $compranul = $this->cajamov_model->lst_compraanulada($idmovcaja->id_mov);
        foreach ($compranul as $row) {
            if ($tablecompra == ""){
                $tablecompra = "<table width='1000' border='0' align='center'  bgcolor='white' style='margin-top:-20px;'>
                                  <tr style='height:15px;'>
                                    <th>Fecha</th>
                                    <th>Factura</th>
                                    <th>Monto</th>
                                    <th>Proveedor</th>
                                    <th>RUC</th>
                                    <th>Causa</th>
                                  </tr>";                
            }                
            $tablecompra.= "<tr style='height:15px;'>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->fecha</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nro_factura</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->montototal</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nom_proveedor</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nro_ide_proveedor</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->causa_anulacion</td>
                          </tr>";
        }
        if ($tablecompra != ""){
            $tablecompra.= "</table>";
        }

        $tablemesa = "";
        $mesalimpia = $this->cajamov_model->lst_mesalimpia($idmovcaja->id_mov);
        foreach ($mesalimpia as $row) {
            if ($tablemesa == ""){
                $tablemesa = "<table width='400' border='0' bgcolor='white' style='margin-top:-20px;'>
                                  <tr style='height:15px;'>
                                    <th>Fecha</th>
                                    <th>Mesa</th>
                                    <th>Observacion</th>
                                  </tr>";                
            }                
            $tablemesa.= "<tr style='height:15px;'>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->fecha</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nom_mesa</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->observacion</td>
                          </tr>";
        }
        if ($tablemesa != ""){
            $tablemesa.= "</table>";
        }

        $msg = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
         <html xmlns='http://www.w3.org/1999/xhtml'>
            <head>
               <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
               <title></title>
            </head>
            <body>
               <div style='margin: 0 auto; background-color: white; width:940px; overflow-y:auto;'>

                  <table width='1000' height='200' border='0' align='center'  bgcolor='white' style='margin-top:-20px;'>
                     <tr>
                        <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                           Fecha y Hora de Apertura: $caja->fecha_apertura
                           <br/>
                           Monto de Apertura: $caja->monto_apertura
                           <br/>
                           Fecha y Hora de Cierre: $caja->fecha_cierre
                           <br/>
                           <br/>
                           Ventas Totales: $caja->ingresoefectivo
                           <br/>
                           Monto Tarjetas: $caja->ingresotarjeta
                           <br/>
                           Monto Egresos: $caja->pagos
                           <br/>                           
                           Saldo: $caja->saldo
                           <br/>
                           Total Caja: $caja->existente
                           <br/>
                           $strdif
                           <br/>
                           <br/>                           
                           Observaciones:
                           $caja->observaciones
                           <br/>
                           <br/>


                        </td>
                    </tr>
                  </table>
                  <table width='1000' border='0' align='center'  bgcolor='white' style='margin-top:-20px;'>
                    <tr>
                        <td align='left' style='border: 0px transparent; font-size:10px!important; font-family:Helvetica, Arial; text-align: left;'>
                            Egresos de Caja:
                            <br/>
                            $tablemovegreso
                           </span>
                        </td>
                     </tr>
                  </table>                  
                  <table width='1000' border='0' align='center'  bgcolor='white' style='margin-top:-20px;'>
                    <tr>
                        <td align='left' style='border: 0px transparent; font-size:10px!important; font-family:Helvetica, Arial; text-align: left;'>
                            Ventas anuladas:
                            <br/>
                            $tableventa
                           </span>
                        </td>
                     </tr>
                  </table>
                  <table width='1000' height='200' border='0' align='center'  bgcolor='white' style='margin-top:-20px;'>
                    <tr>
                        <td align='left' style='border: 0px transparent; font-size:10px!important; font-family:Helvetica, Arial; text-align: left;'>
                            Gastos anulados:
                            <br/>
                            $tablegasto
                           </span>
                        </td>
                     </tr>
                  </table>
                  <table width='1000' height='200' border='0' align='center'  bgcolor='white' style='margin-top:-20px;'>
                    <tr>
                        <td align='left' style='border: 0px transparent; font-size:10px!important; font-family:Helvetica, Arial; text-align: left;'>
                            Compras anuladas:
                            <br/>
                            $tablecompra
                           </span>
                        </td>
                     </tr>
                  </table>
                  <table width='1000' height='200' border='0' align='center'  bgcolor='white' style='margin-top:-20px;'>
                    <tr>
                        <td align='left' style='border: 0px transparent; font-size:10px!important; font-family:Helvetica, Arial; text-align: left;'>
                            Mesas Limpias:
                            <br/>
                            $tablemesa
                           </span>
                        </td>
                     </tr>
                  </table>
                  <table width='1000' height='200' border='0' align='center'  bgcolor='white' style='margin-top:-20px;'>
                    <tr>
                        <td align='left' style='border: 0px transparent; font-size:10px!important; font-family:Helvetica, Arial; text-align: left;'>
                              EMPRESA: $empresa->nom_emp;
                              <br/>
                              RUC: $empresa->ruc_emp;
                              <br/>
                              DIRECCION: $empresa->dir_emp;
                              <br/>
                              TELF: $empresa->tlf_emp;
                           </span>
                        </td>
                     </tr>
                  </table>
               </div>
            </body>
         </html>";
        return $msg;
    }
*/

    public function obtenerMensajeCierrecaja($idmov){
    //    $empresa = $this->empresa_model->emp_get();
    //    $idmovcaja = $this->cajamov_model->ultimomovimientocerrado();
        $caja = $this->cajamov_model->sel_cajamov_id($idmov);

        $empresa = $this->empresa_model->sel_emp_id($caja->id_empresa);

        $idusu = $this->session->userdata("sess_id");
    //    $idmov = $idmovcaja->id_mov;
        $movegreso = $this->cajacierre_model->lstegreso($idmov, $idusu);   

        $tableventa = "";
        $ventanul = $this->cajamov_model->lst_ventaanulada($idmov);
        foreach ($ventanul as $row) {
            if ($tableventa == ""){
                $tableventa = "<table width='1000' border='1' align='left' cellspacing='0' style='margin-top:-20px;'>

                                    <tr>
                                        <th colspan='8' align='center'>Ventas Anuladas</th>
                                    </tr>
                                  <tr style='height:15px;'>
                                    <th>Fecha</th>
                                    <th>Factura</th>
                                    <th>Mesa</th>
                                    <th>Mesero</th>
                                    <th>Monto</th>
                                    <th>Cliente</th>
                                    <th>RUC</th>
                                    <th>Causa</th>
                                  </tr>";                
            }                
            $tableventa.= "<tr style='height:15px;'>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->fecharegistro</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nro_factura</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->mesa</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->mesero</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->montototal</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nom_cliente</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nro_ident</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->causa_anulacion</td>
                          </tr>";
        }
        
        if ($tableventa != ""){
            $tableventa.= "</table>";
        }




        $tablemovegreso = "";
        $nro = 0;
        foreach ($movegreso as $me) {
            $nro = $nro + 1;
            if ($tablemovegreso == ""){
                $tablemovegreso = "<table width='500' border='1' align='left' cellspacing='0'  bgcolor='white' style='margin-top:-20px;'>
                                    <tr>
                                        <th colspan='3' align='center'>Egresos de Caja</th>
                                    </tr>
                                  <tr style='height:15px;'>
                                    <th>Nro</th>
                                    <th>Descripción</th>
                                    <th>Monto</th>
                                  </tr>";                
            }                
            $tablemovegreso.= "<tr style='height:15px;'>
                            <td align='center'>
                            $nro</td>
                            <td align='left' >
                            $me->descripcion</td>
                            <td align='right' >
                            $me->monto</td>
                          </tr>";
        }

        if ($tablemovegreso != ""){
            $tablemovegreso.= "</table>";
        }




        $tablegasto = "";
        $gastonul = $this->cajamov_model->lst_gastoanulado($idmov); /* */
        foreach ($gastonul as $row) {
            if ($tablegasto == ""){
                $tablegasto = "<table width='500' border='1' align='left' cellspacing='0'  bgcolor='white' style='margin-top:-20px;'>
                                    <tr>
                                        <th colspan='6' align='center'>Gastos anulados</th>
                                    </tr>
                                  <tr style='height:15px;'>
                                    <th>Fecha</th>
                                    <th>Factura</th>
                                    <th>Monto</th>
                                    <th>Proveedor</th>
                                    <th>RUC</th>
                                    <th>Causa</th>
                                  </tr>";                
            }                
            $tablegasto.= "<tr style='height:15px;'>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->fecha</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nro_factura</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->total</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nom_proveedor</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nro_ide_proveedor</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->causa_anulacion</td>
                          </tr>";
        }

        if ($tablegasto != ""){
            $tablegasto.= "</table>";
        }

        $tablecompra = "";
        $compranul = $this->cajamov_model->lst_compraanulada($idmov); /* Compras anuladas: */
        foreach ($compranul as $row) {
            if ($tablecompra == ""){
                $tablecompra = "<table width='500' border='1' align='left' cellspacing='0'  bgcolor='white' style='margin-top:-20px;'>
                                    <tr>
                                        <th colspan='6' align='center'>Compras anuladas</th>
                                    </tr>
                                  <tr style='height:15px;'>
                                    <th>Fecha</th>
                                    <th>Factura</th>
                                    <th>Monto</th>
                                    <th>Proveedor</th>
                                    <th>RUC</th>
                                    <th>Causa</th>
                                  </tr>";                
            }                
            $tablecompra.= "<tr style='height:15px;'>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->fecha</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nro_factura</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->montototal</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nom_proveedor</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nro_ide_proveedor</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->causa_anulacion</td>
                          </tr>";
        }
        if ($tablecompra != ""){
            $tablecompra.= "</table>";
        }

        $tablemesa = "";
        $mesalimpia = $this->cajamov_model->lst_mesalimpia($idmov);  /*Mesas Limpias:*/
        foreach ($mesalimpia as $row) {
            if ($tablemesa == ""){
                $tablemesa = "<table width='400' border='1' align='left' cellspacing='0'  bgcolor='white' style='margin-top:-20px;'>
                                    <tr>
                                        <th colspan='3' align='center'>Mesas Limpias</th>
                                    </tr>
                                  <tr style='height:15px;'>
                                    <th>Fecha</th>
                                    <th>Mesa</th>
                                    <th>Observacion</th>
                                  </tr>";                
            }                
            $tablemesa.= "<tr style='height:15px;'>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->fecha</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->nom_mesa</td>
                            <td align='left' style='border: 0px transparent; font-size:13px!important; font-family:Helvetica, Arial; text-align: left;'>
                            $row->observacion</td>
                          </tr>";
        }
        if ($tablemesa != ""){
            $tablemesa.= "</table>";
        }

        $msg = "<!DOCTYPE html>
                <html lang='es'>
                <head>
                    <meta charset='UTF-8'>
                </head>
                <body>
                <div style='margin: 0 auto; background-color: white; width:800px; overflow-y:auto;'>
                    <h4>CIERRE DE CAJA: $caja->caja </h4>
                    <hr>
                    <p>
                        <strong>Usuario de Apertura: </strong>$caja->usuape
                        <br/>
                        <strong>Fecha y Hora de Apertura: </strong> $caja->fecha_apertura
                        <br/>
                        <strong>Monto de Apertura: </strong>$caja->monto_apertura
                        <br/>
                        <strong>Usuario de Cierre: </strong>$caja->usucie
                        <br/>
                        <strong>Fecha y Hora de Cierre: </strong>$caja->fecha_cierre            
                    </p>
                        <strong>Observación: </strong> $caja->observaciones
                    <p>
                    </p>
                    <hr>
                    <table border='1' cellspacing='0' width='300'>
                        <tr>
                            <th colspan='2' align='center'>Balance de Caja</th>
                        </tr>
                        <tr>
                            <th align='left'>Ventas Totales</th>
                            <td align='right'> $caja->ventastotales </td>
                        </tr>                        
                        <tr>
                            <th align='left'>Abonos de Servicios</th>
                            <td align='right'> $caja->abonoservicio </td>
                        </tr>                        
                        <tr>
                            <th align='left'>Abonos de Creditos</th>
                            <td align='right'> $caja->abonocredito </td>
                        </tr>                        
                        <tr>
                            <th align='left'>Monto (No Efectivo)</th>
                            <td align='right'> $caja->montonoefectivo </td>
                        </tr>
                        <tr>
                            <th align='left'>Monto Egreso</th>
                            <td align='right'> $caja->montoegreso </td>
                        </tr>
                        <tr>
                            <th align='left'>Saldo</th>
                            <td align='right'> $caja->saldo </td>
                        </tr>
                        <tr>
                            <th align='left'>Total Caja</th>
                            <td align='right'> $caja->totalcaja </td>
                        </tr>
                        <tr>
                            <th align='left'>Sobrante</th>
                            <td align='right'> $caja->sobrante </td>
                        </tr>
                        <tr>
                            <th align='left'>Faltante</th>
                            <td align='right'> $caja->faltante </td>
                        </tr>
                    </table>
                    <br>
                    <table border='1' cellspacing='0' width='300'>
                        <tr>
                            <th colspan='2' align='center'>Desglose por Formas de Pago</th>
                        </tr>
                        <tr>
                            <th align='left'>Efectivo</th>
                            <td align='right'> $caja->desefectivo </td>
                        </tr>
                        <tr>
                            <th align='left'>Cheque</th>
                            <td align='right'> $caja->descheque </td>
                        </tr>
                        <tr>
                            <th align='left'>Tarjeta de Crédito</th>
                            <td align='right'> $caja->destarcre </td>
                        </tr>
                        <tr>
                            <th align='left'>Tarjeta de Débito</th>
                            <td align='right'> $caja->destardeb </td>
                        </tr>
                        <!--
                        <tr>
                            <th align='left'>Tarjeta Prepago</th>
                            <td align='right'> $caja->destarpre </td>
                        </tr>
                        <tr>
                            <th align='left'>Transferencia</th>
                            <td align='right'> $caja->destransf </td>
                        </tr>
                        <tr>
                            <th align='left'>Dinero Electrónico</th>
                            <td align='right'> $caja->desdinele </td>
                        </tr>
                        <tr>
                            <th align='left'>Otros</th>
                            <td align='right'> $caja->desotros </td>
                        </tr>
                        <tr>
                            <th align='left'>Venta a Crédito</th>
                            <td align='right'> $caja->desvencre </td>
                        </tr>
                        -->
                    </table>
                    <br>
                    
                    $tablemovegreso
                    <br>
                    $tableventa
                    <br>
                    $tablegasto
                    <br>
                    $tablecompra
                    <br>
                    $tablemesa






                    <table width='1000' height='200' border='0' align='center'  bgcolor='white' style='margin-top:-20px;'>
                    <tr>
                        <td align='left' style='border: 0px transparent; font-size:10px!important; font-family:Helvetica, Arial; text-align: left;'>
                              EMPRESA: $empresa->nom_emp;
                              <br/>
                              RUC: $empresa->ruc_emp;
                              <br/>
                              DIRECCION: $empresa->dir_emp;
                              <br/>
                              TELF: $empresa->tlf_emp;
                           </span>
                        </td>
                     </tr>
                    </table>
                </div>
            </body>
         </html>";
        return $msg;
    }  

     public function tmp_mov($idmov) {
        $this->session->unset_userdata("tmp_idmov"); 
        $this->session->set_userdata("tmp_idmov", NULL);
        if ($idmov != NULL) { $this->session->set_userdata("tmp_idmov", $idmov); } 
        else { $this->session->set_userdata("tmp_idmov", NULL); }
    }


    public function addegreso(){
        $idmov = $this->input->post('idmov');
        $cont = $this->cajacierre_model->contaeg();
        $data["cont"] = $cont;
        $this->tmp_mov($idmov);
        $data["idmov"] = $idmov;
        $data["base_url"] = base_url();
        $this->load->view("cajacierre_addgasto", $data);        
    }

    public function ediaddegreso(){
        $idusu = $this->session->userdata("sess_id");
        $idmov = $this->input->post('idmov');
        $idreg = $this->input->post('idreg');
        $detmovgas = $this->cajacierre_model->edicajagastos($idreg, $idmov, $idusu);
        $this->tmp_mov($idmov);
        $data["detgas"] = $detmovgas;
        $data["idmov"] = $idmov;
        $data["base_url"] = base_url();
        $this->load->view("cajacierre_addgasto", $data);        
    }

    public function guardaegreso(){
        $idusu = $this->session->userdata("sess_id");
        $monto = $this->input->post('monto');
        $desc = $this->input->post('desc');
        $idmov = $this->input->post('idmov');
        $idreg = $this->input->post('idreg');
        $emi = $this->input->post('emi');
        $rec = $this->input->post('rec');

        if($idreg == 0){
            $resu = $this->cajacierre_model->addgastos($monto, $desc, $idmov, $idusu, $emi, $rec);
        }else{
            $resu = $this->cajacierre_model->updgastos($monto, $desc, $idmov, $idusu, $idreg, $emi, $rec);
        }

        print json_encode($resu);
    }

    public function actualiza_cajaegreso(){
        $idusu = $this->session->userdata("sess_id"); 
        $idmov = $this->session->userdata("tmp_idmov");
        $resu = $this->cajacierre_model->selcajagastos($idmov, $idusu);  
        $data["cajag"] = $resu;
        $data["base_url"] = base_url();
        $this->load->view("cajacierre_tabla", $data);                             
    }


    public function delcajaegreso(){
        $idusu = $this->session->userdata("sess_id");
        $idmov = $this->input->post('idmov');
        $idreg = $this->input->post('idreg');
        $del = $this->cajacierre_model->delcajagastos($idreg, $idmov, $idusu); 
        $monto = $this->cajacierre_model->montogasto($idmov, $idusu);
        print json_encode($monto);       
    }


    public function monto_cajaegreso(){
        $idusu = $this->session->userdata("sess_id");
        $idmov = $this->input->post('idmov');
        $monto = $this->cajacierre_model->montogasto($idmov, $idusu);
        print json_encode($monto);       
    }

     public function nrocg_tmp() {
        $this->session->unset_userdata("idcg_tmp"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("idcg_tmp", NULL);
        if ($id != NULL) { $this->session->set_userdata("idcg_tmp", $id); } 
        else { $this->session->set_userdata("idcg_tmp", NULL); }
        $idcg = $this->session->userdata("idcg_tmp");
        $arr['resu'] = $idcg;
        print json_encode($arr);
    }

    private function pagina_v() {
      $this->fpdf->SetMargins('12', '7', '10');   #Margenes
      $this->fpdf->AddPage('P', 'A4');        #Orientación y tamaño 
    }    

    public function cgpdf(){

        $idcg = $this->session->userdata("idcg_tmp");
        $encrec = $this->cajacierre_model->receg($idcg);;
        // ENCABEZADO DEL PDF 
        $this->load->library('fpdf');
        $this->fpdf->fontpath = 'font/'; 
        $this->fpdf->AliasNbPages();
        $this->pagina_v();
        $this->fpdf->SetFillColor(139, 35, 35);
        $this->fpdf->SetFont('Arial','B',10);
        $this->fpdf->SetTextColor(0,0,0);


        $sucursal = $this->Sucursal_model->sel_suc_id($encrec->id_sucursal);      
       if ($sucursal->logo_sucursal){    
          $file_name = "ppp.jpg";
          $pic = base64_decode($sucursal->logo_sucursal);
          imagejpeg(imagecreatefromstring ( $pic ), $file_name);
          
          $this->fpdf->Image($file_name,10,10,30,14);
        }  
        //$this->fpdf->Line(12,25,196,25);
        $this->fpdf->SetFont('Arial','B',6);

        $this->fpdf->SetXY(100,8);
        $this->fpdf->Cell(20,10,utf8_decode($sucursal->dir_sucursal),0,0,'C');
        $this->fpdf->SetXY(100,13);
        $this->fpdf->Cell(20,10,utf8_decode($sucursal->telf_sucursal),0,0,'C');
        $this->fpdf->SetXY(100,18);
        $this->fpdf->Cell(20,10,utf8_decode($sucursal->mail_sucursal),0,0,'C');

        //$this->fpdf->Rect(165, 12, 30, 10, "D");
        $this->fpdf->SetFont('Arial','B',12);        
        $this->fpdf->text(170, 16, 'RECIBO');
        $this->fpdf->SetFont('Arial','B',9);
        $this->fpdf->text(170, 21, utf8_decode('Nº '.$encrec->nroegreso));

        $this->fpdf->SetFont('Arial','B',10);

        // TITULO DE DETALLES 
        $emisor = $encrec->emisor;
        $receptor = $encrec->receptor;
        $fec = $encrec->fecharegistro;
        $fechaf = date("d/m/Y", strtotime($fec));
        $this->fpdf->ln(20); 
        $this->fpdf->Cell(100,4,utf8_decode("Cajero: $emisor"),0,0,'L');
        $this->fpdf->Cell(85,4,"Fecha: $fechaf",0,1,'R');
        $this->fpdf->Cell(185,4,utf8_decode("Recibe: $receptor"),0,1,'L');
        $this->fpdf->ln(6); 

        $this->fpdf->Line(12,37,196,37);
        $this->fpdf->Cell(100,4,utf8_decode("Descripción:"),0,1,'L');
        $this->fpdf->SetFont('Arial','',10);
        $this->fpdf->MultiCell(185,5,utf8_decode($encrec->descripcion));
        $this->fpdf->ln(2); 
        $this->fpdf->SetFont('Arial','B',10);
        $this->fpdf->Cell(100,4,utf8_decode("Monto: ".$encrec->monto),0,1,'L');
        $this->fpdf->ln(10); 

        $this->fpdf->Cell(50,0,'',1,0,'L');
        $this->fpdf->Cell(80,0,'',0,0,'L');
        $this->fpdf->Cell(50,0,'',1,1,'L'); 

        $this->fpdf->Cell(50,4,utf8_decode("Cajero: $emisor"),0,0,'L');
        $this->fpdf->Cell(80,0,'',0,0,'L');        
        $this->fpdf->Cell(50,4,utf8_decode("Recibe: $receptor"),0,1,'L'); 


        $this->fpdf->Output('Recibo de Egreso','I'); 

    }
  

}

?>