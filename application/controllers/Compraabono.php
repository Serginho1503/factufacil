<?php
/*------------------------------------------------
  ARCHIVO: Compraabono.php
  DESCRIPCION: Contiene los métodos relacionados con los abonos de Gastos.
  FECHA DE CREACIÓN: 30/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Compraabono extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Compraabono_model");
        $this->load->Model("Formapago_model");
        $this->load->Model("Compra_model");
        $this->load->Model("Sucursal_model");
        $this->load->Model("Empresa_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $idfact = $this->session->userdata("tmp_abonocompra_id");
        $registro = $this->Compraabono_model->sel_compra_id($idfact);
        $data["objfact"] = $registro;
        $data["content"] = "compraabono";
        $this->load->view("layout", $data);
    }

   /* CARGA DE DATOS AL DATATABLE */ 
    public function listadoDataAbono() {
        $idfact = $this->session->userdata("tmp_abonocompra_id");
        $registro = $this->Compraabono_model->lista_abonos($idfact);

        $tabla = "";
        foreach ($registro as $row) {
            @$fec = str_replace('-', '/', $row->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); 
            
            $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Imprimir Abono\" id=\"'.$row->id_abono.'\" class=\"btn bg-navy color-palette btn-xs btn-grad compab_print\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_abono.'\" name=\"'.$row->numerodocumento.'\" class=\"btn btn-danger btn-xs btn-grad del_abono\"><i class=\"fa fa-trash-o\"></i></a> </div>';
            $tabla.='{"id":"' . $row->id_abono . '",
                      "fecha":"' . $fec . '",
                      "formapago":"' . $row->nombre_formapago . '",
                      "monto":"' . $row->monto . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_compra() {
        $this->session->unset_userdata("tmp_abonocompra_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_abonocompra_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_abonocompra_id", $id);
        } else {
            $this->session->set_userdata("tmp_abonocompra_id", NULL);
        }

        $arr['resu'] = 1;
        print json_encode($arr);
    }

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_abono() {

        $this->session->unset_userdata("tmp_abonocompra_idabono"); 
        $idabono = $this->input->post("id");
        $this->session->set_userdata("tmp_abonocompra_idabono", NULL);
        if ($idabono != NULL) {
            $this->session->set_userdata("tmp_abonocompra_idabono", $idabono);
        } else {
            $this->session->set_userdata("tmp_abonocompra_idabono", NULL);
        }

        $arr['resu'] = 1;
        print json_encode($arr);
    }

    /* ABRIR VENTANA PARA MODIFICAR */
    public function adicionar(){
        $idfact = $this->session->userdata("tmp_abonocompra_id");
        $formapago = $this->input->post("txt_formapago");
        $monto = $this->input->post("txt_monto");
        $nrodoc = $this->input->post("txt_nrodoc");
        $descripcion = $this->input->post("txt_desc");
        $abono = $this->Compraabono_model->adicionar($idfact, $formapago, $monto, $nrodoc, $descripcion);

        $compra = $this->Compraabono_model->sel_compra_id($idfact);
        if ($compra){
            $montopendiente = $compra->montototal - $compra->abonos - $compra->retencion_iva - $compra->retencion_renta;
            $arr['montopendiente'] = $montopendiente;
        }

        $arr['resu'] = $abono;
        print json_encode($arr);
    }

    /* ABRIR VENTANA PARA AGREGAR */
    public function add_abono(){
        $formapago_lst = $this->Formapago_model->sel_formapago();
        $montopendiente = $this->input->post("montopendiente");
        $data["base_url"] = base_url();     
        $data["formapago_lst"] = $formapago_lst;
        $data["montopendiente"] = $montopendiente;

        $idfact = $this->session->userdata("tmp_abonocompra_id");
        $compra = $this->Compraabono_model->sel_compra_id($idfact);
        $objsuc = $this->Sucursal_model->sel_suc_id($compra->id_sucursal);
        $contabilizar = $objsuc->contabilizacion_automatica; 
        $data['contabilizar'] = $contabilizar;

        $this->load->view("compraabono_add", $data);
    } 

    /* ABRIR VENTANA PARA AGREGAR */
    public function del_abono(){
        $idabono = $this->session->userdata("tmp_abonocompra_idabono");
        $objabono = $this->Compraabono_model->sel_abono($idabono);
        $data["base_url"] = base_url();     
        $data["objabono"] = $objabono;
        $this->load->view("compraabono_del", $data);
    } 

    /* ABRIR VENTANA PARA MODIFICAR */
    public function eliminar(){
        $idabono = $this->input->post("id_abono");
        $idcompra = $this->input->post("id_compra");
        $this->Compraabono_model->eliminar($idabono, $idcompra);
        $compra = $this->Compraabono_model->sel_compra_id($idcompra);
        if ($compra){
            $montopendiente = $compra->montototal - $compra->abonos - $compra->retencion_iva - $compra->retencion_renta;
            $arr['montopendiente'] = $montopendiente;
        }

        $arr['resu'] = 1;
        print json_encode($arr);
    }

    /* ABRIR VENTANA PARA REPORTE DE GASTOS */
    public function reporte(){
        $gasto = $this->Gastos_model->lista_gastos();
        $data["base_url"] = base_url();
        $data["gasto"] = $gasto;
        $this->load->view("gastos_reporte", $data);      
    }

    /* Exportar Compra a Excel */
    public function reportegastosXLS(){
        $gasto = $this->Gastos_model->lista_gastos();
 
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteGastos');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Gastos');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Proveedor');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Factura');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Autorizacion');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Total');

        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);

        $total = 0;
        $fila = 4;
        foreach ($gasto as $det) {
          $total += $det->total;

          $fec = str_replace('-', '/', $det->fecha); 
          $fec = date("d/m/Y", strtotime($fec));  
          $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
          $this->excel->getActiveSheet()->setCellValue('B'.$fila, $det->nom_proveedor);
          $this->excel->getActiveSheet()->setCellValue('C'.$fila, $det->nro_factura);
          $this->excel->getActiveSheet()->setCellValue('D'.$fila, $det->nro_autorización);
          $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($det->total,2));

          $fila++;          
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('D'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($total,2));
        $this->excel->getActiveSheet()->getStyle('D'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E'.$fila)->getFont()->setBold(true);

         
        $filename='reportegasto.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        $objWriter->save('php://output');        
    }  

   public function imprimir() {

        $strprint = $this->input->post('txt_imprimir');

        $printer="";
        $objprinter = $this->Parametros_model->impresorafactura_get();
        if ($objprinter != null){
            $objcom = $this->Comanda_model->sel_com_id($objprinter->valor);

            $printer= $objcom->impresora;//"EPSONT50";

            $enlace=printer_open($printer);

            printer_write($enlace, $strprint);

            printer_close($enlace);
        }
        
    }


    /* ABRIR VENTANA PARA Imprimir Gasto */
    public function imprimirgasto(){
        $objiva = $this->Parametros_model->iva_get();
        $factoriva = 0;
        if ($objiva != null){
            $factoriva = $objiva->valor;
        }    
        date_default_timezone_set("America/Guayaquil");
        
        $idcompra = $this->input->post('id');

        $tabla ="\r\n" . "FACTURA DE GASTO". "\r\n";
        $strdate=date("d/m/Y H:i");
        $tabla.="Fecha:" . "\x1F \x1F" . $strdate . "\r\n";
        $registro = $this->Gastos_model->datosproveedor($idcompra);
        foreach ($registro as $row) {
            $tabla.="Proveedor:" . "\x1F \x1F" . $row->nom_proveedor . "\r\n";        
            $tabla.="Direccion:". "\x1F \x1F" . $row->direccion_proveedor . "\r\n";        
            $tabla.="CI/RUC:". "\x1F \x1F" . $row->nro_ide_proveedor . "\r\n";        
            $tabla.="Telef.:". "\x1F \x1F" . $row->telf_proveedor . "\r\n";        
        }    
        $tabla.= "\r\n";
        $registro = $this->Gastos_model->sel_gas_id($idcompra);
        $tabla.="Factura:"."\x1F \x1F". $registro->nro_factura . "\r\n";        
        $tabla.="Autorizacion:"."\x1F \x1F". $registro->nro_autorización . "\r\n";        
        $tabla.= "\r\n";        
        $tabla.="Descripcion:"."\x1F \x1F". $registro->descripcion . "\r\n";        
        $tabla.= "\r\n";
        $tabla.= "\r\n";
        $tabla.= "   SUBTOTAL: " . "\x1F \x1F" . number_format($registro->subtotal,2) . "\r\n";
        $tabla.= "   DESCUENTO:" . "\x1F \x1F" . number_format($registro->descuento,2) . "\r\n";
        $tabla.= "   SUBTOTDES:" . "\x1F \x1F" . number_format($registro->subtotaldesc,2) . "\r\n";
        $tabla.= "   MONTO IVA:" . "\x1F \x1F" . number_format($registro->montoiva,2) . "\r\n";
        $tabla.= "   TOTAL:    " . "\x1F \x1F" . number_format($registro->total,2) . "\r\n";
        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("gastos_imprimir", $data);

    }
  

    /* ABRIR VENTANA PARA IMPRIMIR ABONO */
    public function imprimirabono(){
        date_default_timezone_set("America/Guayaquil");
        
        $idreg = $this->input->post('id');
        $abono = $this->Compraabono_model->sel_abono($idreg);
        $row = $this->Compraabono_model->sel_compra_id($abono->id_comp);
        $objsuc = $this->Sucursal_model->sel_suc_id($row->id_sucursal);
        $emp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        $tabla = chr(32). chr(32) . "\r\n";
        $tabla.= chr(32). chr(32) . "\r\n";

        $ff = "COMPROBANTE DE PAGO";
        $tabla .=$ff;
        $tabla.="\r\n";
        $tabla.=$emp->nom_emp . "\r\n";
        $tabla.="RUC:" . chr(32) . $emp->ruc_emp . "\r\n";
        $tabla.=$emp->dir_emp . "\r\n". "\r\n";
        if (trim($abono->numerodocumento) != ''){
          $tabla.="Comprobante Pago:" . chr(32) . $abono->numerodocumento . "\r\n";  
          $tabla.="Tipo:" . chr(32) . $abono->nombre_formapago . "\r\n";
          $strdate = str_replace('-', '/', $abono->fecha); 
          $strdate = date("d/m/Y", strtotime($strdate)); 
          $tabla.="Fecha:" . chr(32) . $strdate . "\r\n";
          $tabla.="Monto:" . chr(32) . $abono->monto . "\r\n";
          $tabla.="Descripcion:" . chr(32) . $abono->descripciondocumento . "\r\n". "\r\n";
        }
        if (trim($row->nro_factura) != ''){
          $tabla.="Factura:" . chr(32) . $row->nro_factura . "\r\n";  
        }
        $strdate = str_replace('-', '/', $row->fecha); 
        $strdate = date("d/m/Y", strtotime($strdate)); 
        $tabla.="Fecha Emision:" . chr(32) . $strdate . "\r\n";
        $tabla.="Proveedor:" . chr(32) . $row->nom_proveedor . "\r\n";        
        $tabla.="Direccion:". chr(32) . $row->direccion_proveedor . "\r\n";        
        $tabla.="CI/RUC:". chr(32) . $row->nro_ide_proveedor . "\r\n";        
        $tabla.="Telef.:". chr(32) . $row->telf_proveedor . "\r\n";        

        $strdate = str_replace('-', '/', $row->fecha_pago); 
        $strdate = date("d/m/Y", strtotime($strdate)); 
        $tabla.="Fecha Limite de Pago:" . chr(32) . $strdate . "\r\n";
        $tabla.="Total Factura:" . chr(32) . $row->montototal . "\r\n";
        $tabla.="Valor Retención:" . chr(32) . ($row->retencion_iva  + $row->retencion_renta). "\r\n";

        $tabla.= "Valor Pendiente:" . chr(32) . ($row->montototal - ($row->retencion_iva  + $row->retencion_renta) - $row->abonos) . "\r\n";

        for ($i=0; $i<4; $i++) {
            $tabla.= "  " . "\n";                        
        }        

        $tabla.= "----------------". "\r\n";
        $tabla.= "     Firma". "\r\n";

        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("credito_abonoimprimir", $data);

    }





}

?>