<?php

/*------------------------------------------------
  ARCHIVO: Area.php
  DESCRIPCION: Contiene los métodos relacionados con el movimiento de caja.
  FECHA DE CREACIÓN: 04/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Cajamov extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("cajamov_model");
        $this->load->Model("Sucursal_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        date_default_timezone_set("America/Guayaquil");
        $desde = date("Y-m-d"); 
        $hasta = date("Y-m-d");

        $vdesde = $desde." 00:00:00";
        $vhasta = $hasta." 23:59:59";        
        $this->session->set_userdata("tmp_cajamov_desde", NULL);
        if ($vdesde != NULL) { $this->session->set_userdata("tmp_cajamov_desde", $vdesde);} 
        else { $this->session->set_userdata("tmp_cajamov_desde", NULL); }
        $this->session->set_userdata("tmp_cajamov_hasta", NULL);
        if ($vhasta != NULL) { $this->session->set_userdata("tmp_cajamov_hasta", $vhasta);} 
        else { $this->session->set_userdata("tmp_cajamov_hasta", NULL);}
        $data["base_url"] = base_url();
        $data["content"] = "cajamov";
        $this->load->view("layout", $data);
    }

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_fecha() {
      $this->session->unset_userdata("tmp_cajamov_desde"); 
      $this->session->unset_userdata("tmp_cajamov_hasta");
      $horad = $this->input->post("horad");
      $horah = $this->input->post("horah");
      $fecdesde = $this->input->post("fdesde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      $fechasta = $this->input->post("fhasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));
      $vdesde = $desde." ".$horad;
      $vhasta = $hasta." ".$horah;        
      $this->session->set_userdata("tmp_cajamov_desde", NULL);
      if ($vdesde != NULL) { $this->session->set_userdata("tmp_cajamov_desde", $vdesde);} 
      else { $this->session->set_userdata("tmp_cajamov_desde", NULL); }
      $this->session->set_userdata("tmp_cajamov_hasta", NULL);
      if ($vhasta != NULL) { $this->session->set_userdata("tmp_cajamov_hasta", $vhasta);} 
      else { $this->session->set_userdata("tmp_cajamov_hasta", NULL);}
      $arr['resu'] = 1;
      print json_encode($arr);
    } 


    /* CARGA EL DATATABLE (LISTADO) */
    public function listadoDataCajamov() {
        date_default_timezone_set("America/Guayaquil");
        $desde = $this->session->userdata("tmp_cajamov_desde");
        $hasta = $this->session->userdata("tmp_cajamov_hasta");

        $ruta = base_url().'cajamov/pri_cajamov/';
        $registro = $this->cajamov_model->sel_cajamov($desde, $hasta);
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Resumen Caja\" id=\"'.$row->id_mov.'\" class=\"btn btn-danger btn-xs btn-grad pdf_cajamov\"><i class=\"fa fa-file-pdf-o\"></i></a>';
/*
<a href=\"'.$ruta.$row->id_mov.'\" title=\"Imprimir\" id=\"'.$row->id_mov.'\" class=\"btn btn-success btn-xs btn-grad pri_cajamov\"><i class=\"fa fa-print\"></i></a>

*/

            /*
            if ($row->estado !=1){
                $ver.= '<a href=\"#\" title=\"Apertura\" id=\"'.$row->id_mov.'\" class=\"btn btn-primary btn-xs btn-grad edit_cajamov\"><i class=\"fa fa-edit\"></i></a>';                               
            }
            */
            $ver.= '</div>';           
                    
            $tabla.='{"id":"' . $row->id_mov . '",
                      "caja":"' . $row->nom_caja . '",
                      "fechaapertura":"' . $row->fecha_apertura . '",
                      "montoapertura":"' . $row->monto_apertura . '",
                      "fechacierre":"' . $row->fecha_cierre . '",
                      "ventastotales":"' . $row->ventastotales . '",
                      "efectivo":"' . $row->desefectivo . '",
                      "noefectivo":"' . $row->montonoefectivo . '",
                      "egreso":"' . $row->montoegreso . '",
                      "saldo":"' . $row->saldo . '",
                      "sobrante":"' . $row->sobrante . '",
                      "faltante":"' . $row->faltante . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }


    /* LEVANTAR VENTANA PARA Imprimir DATOS */
    public function pri_cajamov($id){
        //$idmov = $this->input->post("id");
        $idmov = $id;
        $cajamov = $this->cajamov_model->sel_cajamov_id($idmov);
        $objxls = $this->cierrecajaXLS($cajamov);
    }  


    /* FUNCION DE VARIABLE DE SESION */    
     public function tmp_cajamov() {
        $this->session->unset_userdata("tmp_cajamov"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_cajamov", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_cajamov", $id); } 
        else { $this->session->set_userdata("tmp_cajamov", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    }
    
  
    /* Obtener reporte de Cierre de Caja en Excel */
    public function cierrecajaXLS($cajamov){
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

        $this->excel->getActiveSheet()->setCellValue('A3', 'Empresa');
        $this->excel->getActiveSheet()->setCellValue('A4', 'RUC');
        $this->excel->getActiveSheet()->setCellValue('A5', 'Direccion');
        $this->excel->getActiveSheet()->setCellValue('A6', 'Telefono');

        $this->excel->getActiveSheet()->setCellValue('A8', 'Apertura');
        $this->excel->getActiveSheet()->setCellValue('B8', $cajamov->fecha_apertura);
        $this->excel->getActiveSheet()->setCellValue('A9', 'Cierre');
        $this->excel->getActiveSheet()->setCellValue('B9', $cajamov->fecha_cierre);
        $this->excel->getActiveSheet()->setCellValue('A10', 'Monto Apertura');
        $this->excel->getActiveSheet()->setCellValue('B10', $cajamov->monto_apertura);

        $this->excel->getActiveSheet()->setCellValue('A12', 'Ingreso Efectivo');        
        $this->excel->getActiveSheet()->setCellValue('B12', $cajamov->ingresoefectivo);
        $this->excel->getActiveSheet()->setCellValue('D12', 'Ingreso Tarjeta');
        $this->excel->getActiveSheet()->setCellValue('E12', $cajamov->ingresotarjeta);

        $this->excel->getActiveSheet()->setCellValue('A13', 'Egresos');
        $this->excel->getActiveSheet()->setCellValue('B13', $cajamov->pagos);
        $this->excel->getActiveSheet()->setCellValue('D13', 'Compras');
        $this->excel->getActiveSheet()->setCellValue('E13', $cajamov->compras);

        $this->excel->getActiveSheet()->setCellValue('A14', 'Saldo');
        $this->excel->getActiveSheet()->setCellValue('B14', $cajamov->saldo);
        $this->excel->getActiveSheet()->setCellValue('A15', 'Existente');
        $this->excel->getActiveSheet()->setCellValue('B15', $cajamov->existente);
        $this->excel->getActiveSheet()->setCellValue('A16', 'Sobrante');
        $this->excel->getActiveSheet()->setCellValue('A17', 'Faltante');
        if ($cajamov->diferencia < 0){
          $tmpval = abs($cajamov->diferencia);         
          $this->excel->getActiveSheet()->setCellValue('B16', $tmpval);
        } else {
          $this->excel->getActiveSheet()->setCellValue('B17', $cajamov->diferencia);          
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

    public function upd_aperturacaja(){
        $id = $this->session->userdata("tmp_cajamov");
        $data["base_url"] = base_url();
        $obj = $this->cajamov_model->sel_cajamov_id($id);
        $data["obj"] = $obj;
        $this->load->view("cajamov_edit", $data);
    }

    public function guardar_apertura(){
        $id = $this->input->post('txt_id'); 
        $monto = $this->input->post('txt_monto'); 
        $this->cajamov_model->upd_cajamov_apertura($id, $monto);
        $arr['mens'] = $id;
        print json_encode($arr); 
    }

    private function pagina_v() {
      $this->pdf_caja->SetMargins('12', '7', '10');   #Margenes
      $this->pdf_caja->AddPage('P', 'A4');        #Orientación y tamaño 
    }

    public function resumencaja_pdf(){
      $idmov = $this->session->userdata("tmp_cajamov");
      $resmov = $this->cajamov_model->sel_cajamov_id($idmov);
      $sucursal = $this->Sucursal_model->sel_suc_id($resmov->id_sucursal);      
      $egrmov = $this->cajamov_model->movegreso($idmov);      
      $params['cajaenc'] = $resmov;
      $params['sucursal'] = $sucursal;

      /* ENCABEZADO DEL PDF */
      $this->load->library('pdf_caja', $params);
      $this->pdf_caja->fontpath = 'font/'; 
      $this->pdf_caja->AliasNbPages();
      $this->pagina_v();

      $this->pdf_caja->SetFillColor(139, 35, 35);
      $this->pdf_caja->SetFont('Arial','B',10);
      $this->pdf_caja->SetTextColor(0,0,0);
      /* TITULO DE DETALLES */
      $this->pdf_caja->Cell(80,7,utf8_decode("Balance de Caja"),1,0,'C');
      $this->pdf_caja->Cell(22,7,utf8_decode(" "),0,0,'L');
      $this->pdf_caja->Cell(80,7,'Desglose de Formas de Pago',1,1,'C');

      $this->pdf_caja->Cell(60,7,utf8_decode(" Ventas Totales"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->ventastotales "),1,0,'R');
      $this->pdf_caja->Cell(22,7,utf8_decode(" "),0,0,'L');
      $this->pdf_caja->Cell(60,7,utf8_decode(" Efectivo"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->desefectivo "),1,1,'R');

      $this->pdf_caja->Cell(60,7,utf8_decode(" Abono Servicios"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->abonoservicio "),1,0,'R');
      $this->pdf_caja->Cell(22,7,utf8_decode(" "),0,0,'L');
      $this->pdf_caja->Cell(60,7,utf8_decode(" Cheque"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->descheque "),1,1,'R');

      $this->pdf_caja->Cell(60,7,utf8_decode(" Abono Creditos"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->abonocredito "),1,0,'R');
      $this->pdf_caja->Cell(22,7,utf8_decode(" "),0,0,'L');
      $this->pdf_caja->Cell(60,7,utf8_decode(" Tarjeta de Crédito"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->destarcre "),1,1,'R');

      $this->pdf_caja->Cell(60,7,utf8_decode(" Monto (No Efectivo)"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->montonoefectivo "),1,0,'R');
      $this->pdf_caja->Cell(22,7,utf8_decode(" "),0,0,'L');
      $this->pdf_caja->Cell(60,7,utf8_decode(" Tarjeta de Debito"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->destardeb "),1,1,'R');

      $this->pdf_caja->Cell(60,7,utf8_decode(" Monto Egreso"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->montoegreso "),1,0,'R');
      $this->pdf_caja->Cell(22,7,utf8_decode(" "),0,0,'L');
      $this->pdf_caja->Cell(60,7,utf8_decode(" Tarjeta Prepago"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->destarpre "),1,1,'R');

      $this->pdf_caja->Cell(60,7,utf8_decode(" Saldo"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->saldo "),1,0,'R');
      $this->pdf_caja->Cell(22,7,utf8_decode(" "),0,0,'L');
      $this->pdf_caja->Cell(60,7,utf8_decode(" Transferencia"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->destransf "),1,1,'R');

      $this->pdf_caja->Cell(60,7,utf8_decode(" Total Caja"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->totalcaja "),1,0,'R');
      $this->pdf_caja->Cell(22,7,utf8_decode(" "),0,0,'L');
      $this->pdf_caja->Cell(60,7,utf8_decode(" Dinero Electronico"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->desdinele "),1,1,'R');                  

      $this->pdf_caja->Cell(60,7,utf8_decode(" Sobrante"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->sobrante "),1,0,'R');
      $this->pdf_caja->Cell(22,7,utf8_decode(" "),0,0,'L');
      $this->pdf_caja->Cell(60,7,utf8_decode(" Otros"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->desotros "),1,1,'R');   

      $this->pdf_caja->Cell(60,7,utf8_decode(" Faltante"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->faltante "),1,0,'R');
      $this->pdf_caja->Cell(22,7,utf8_decode(" "),0,0,'L');
      $this->pdf_caja->Cell(60,7,utf8_decode(" Creditos"),1,0,'L');
      $this->pdf_caja->Cell(20,7,utf8_decode("$resmov->desvencre "),1,1,'R'); 

      $this->pdf_caja->ln(6); 

      $this->pdf_caja->Cell(182,7,utf8_decode("Egresos de Caja"),1,1,'C');
      $this->pdf_caja->Cell(10,7,utf8_decode(" Nro"),1,0,'C');
      $this->pdf_caja->Cell(30,7,utf8_decode(" Usuario"),1,0,'C');
      $this->pdf_caja->Cell(30,7,utf8_decode(" Emisor"),1,0,'C');
      $this->pdf_caja->Cell(62,7,utf8_decode(" Descripción"),1,0,'C');
      $this->pdf_caja->Cell(20,7,utf8_decode(" Monto"),1,0,'C');
      $this->pdf_caja->Cell(30,7,utf8_decode(" Receptor"),1,1,'C');

      foreach ($egrmov as $row) {
        $nro = $row->nroegreso;
        $usuario = $row->usuario;
        $emisor = $row->emisor;
        $descripcion = $row->descripcion;
        $receptor = $row->receptor;
        $monto = number_format($row->monto,2);

        $this->pdf_caja->Cell(10,7,utf8_decode("$nro"),1,0,'C');
        $this->pdf_caja->Cell(30,7,utf8_decode(" $usuario"),1,0,'L');
        $this->pdf_caja->Cell(30,7,utf8_decode(" $emisor"),1,0,'L');
        $this->pdf_caja->Cell(62,7,utf8_decode(" $descripcion"),1,0,'L');
        $this->pdf_caja->Cell(20,7,utf8_decode(" $monto"),1,0,'R');
        $this->pdf_caja->Cell(30,7,utf8_decode(" $receptor"),1,1,'L');
      }

      $this->pdf_caja->ln(6); 

      $this->pdf_caja->Cell(182,7,utf8_decode("Observaciones"),1,1,'C');
      $this->pdf_caja->MultiCell(182,5,utf8_decode(" $resmov->observaciones"),1,'J');
    //  $this->pdf_caja->Cell(182,7,utf8_decode(" $resmov->observaciones"),1,1,'L');













      /* CICLO DE DETALLES DE FACTURA 
      $registro = $this->Proforma_model->lst_profdetalle($idproforma);
      $subtotaliva=0;
      $subtotalcero=0;
      $subtotaldiva=0;
      $subtotaldcero=0;
      $montoiva=0;
      $descuento=0;
      foreach ($registro as $row) {
        $strnombre = $row->pro_nombre;
        $strcant = $row->cantidad;
        if ($row->pro_grabaiva == 1){
          $subtotaliva+= $row->subtotal;
          $montoiva+= $row->montoiva;    
        }
        else{
          $subtotalcero+= $row->subtotal;
        }
        $precio = number_format($row->precio,2);
        $subtotal=number_format($row->subtotal,2);

        $this->pdf_caja->SetFont('Arial','',8);        
        $this->pdf_caja->Cell(20,5,utf8_decode("$strcant"),0,0,'C');
        $this->pdf_caja->Cell(115,5,utf8_decode("$strnombre"),0,0,'L');
        $this->pdf_caja->Cell(25,5,'$'.$precio,0,0,'C');
        $this->pdf_caja->Cell(25,5,'$'.$subtotal,0,1,'R'); 
      }
      
      $this->pdf_caja->Ln(20);      
      $this->pdf_caja->SetFont('Arial','B',10); 
      $this->pdf_caja->Cell(50,4,utf8_decode("Observaciones"),0,1,'L');
      $this->pdf_caja->SetFont('Arial','',10);        
      $this->pdf_caja->MultiCell(185,5,utf8_decode($encprof->observaciones));   




      $subtotaliva=0;
      $subtotalcero=0;
      $subtotaldiva=0;
      $subtotaldcero=0;
      $montoiva=0;
      $descuento=0;
      foreach ($pieprof as $row) {
        $strnombre = $row->pro_nombre;
        $strcant = $row->cantidad;
          if ($row->pro_grabaiva == 1){
            $subtotaliva+= $row->subtotal;
            $montoiva+= $row->montoiva;    
          }
          else{
            $subtotalcero+= $row->subtotal;
          }
        }

      $total = $subtotaliva + $subtotalcero + $montoiva;

      $this->pdf_caja->SetY(-20);

      $this->pdf_caja->Line(12,269,60,269);
      $this->pdf_caja->text(22, 273, utf8_decode('Firma Autorizada'));

      $this->pdf_caja->text(12, 240, utf8_decode('NOTA:'));
      $this->pdf_caja->text(12, 244, utf8_decode('La validez de la siguiente Proforma tiene 8 días'));

      $this->pdf_caja->SetFont('Arial','B',10);
      $this->pdf_caja->Cell(160,-4,utf8_decode("Total"),0,0,'R');
      $this->pdf_caja->Cell(25,-4,utf8_decode('$'.$total),0,1,'R');

      $this->pdf_caja->Cell(160,-4,utf8_decode("IVA (12%)"),0,0,'R');
      $this->pdf_caja->Cell(25,-4,utf8_decode('$'.$montoiva),0,1,'R');

      $this->pdf_caja->Cell(160,-4,utf8_decode("Subtotal con Descuento IVA (0 %)"),0,0,'R');
      $this->pdf_caja->Cell(25,-4,utf8_decode('$'.$subtotaldcero),0,1,'R');

      $this->pdf_caja->Cell(160,-4,utf8_decode("Subtotal con Descuento IVA (12 %)"),0,0,'R');
      $this->pdf_caja->Cell(25,-4,utf8_decode('$'.$subtotaldiva),0,1,'R');

      $this->pdf_caja->Cell(160,-4,utf8_decode("Descuento"),0,0,'R');
      $this->pdf_caja->Cell(25,-4,utf8_decode('$'.$descuento),0,1,'R');

      $this->pdf_caja->Cell(160,-4,utf8_decode("Subtotal IVA (0 %)"),0,0,'R');
      $this->pdf_caja->Cell(25,-4,utf8_decode('$'.$subtotalcero),0,1,'R');

      $this->pdf_caja->Cell(160,-4,utf8_decode("Subtotal IVA (12 %)"),0,0,'R');
      $this->pdf_caja->Cell(25,-4,utf8_decode('$ '.$subtotaliva),0,1,'R');      

      // NUEVA PAGINA 


      $detproimg = $this->Proforma_model->lst_profimagen($idproforma);

      $this->load->library('pdf_caja', $params);
      $this->pdf_caja->fontpath = 'font/'; 
      $this->pdf_caja->AliasNbPages();
      $this->pagina_v();
      $this->pdf_caja->SetFillColor(139, 35, 35);
      $this->pdf_caja->SetFont('Arial','B',8);
      $this->pdf_caja->SetTextColor(0,0,0);
      $this->pdf_caja->Line(12,40,196,40); 

      foreach ($detproimg as $dpi) {

        $nompro = utf8_decode($dpi->pro_nombre);
        $despro = utf8_decode($dpi->pro_descripcion);
        $imgpro = $dpi->pro_imagen;
        $pic = 'data://text/plain;base64,' .$imgpro;
        
        if($dpi->pro_imagen == null){
          $this->pdf_caja->Cell(30,30, $this->pdf_caja->Image(base_url().'public/img/quitoled.jpg', $this->pdf_caja->GetX(), $this->pdf_caja->GetY(), 30, 30, 'jpg'),1);
        } else{
          $this->pdf_caja->Cell(30,30, $this->pdf_caja->Image($pic, $this->pdf_caja->GetX(), $this->pdf_caja->GetY(), 30, 30, 'jpg'),1); 
        }     

        $this->pdf_caja->Cell(100, 5,'Producto: '.$nompro, 0,1,'L'); 
        $this->pdf_caja->Cell(30,0,'',0,0); 
        $this->pdf_caja->MultiCell(150,5,'Descripcion: '.$despro,0);
        $this->pdf_caja->Ln(22);


      }
*/
      /* FIN */   

      $this->pdf_caja->Output('Resumen de Caja.pdf','I');
















    }

}

?>