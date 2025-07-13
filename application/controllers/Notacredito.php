<?php
/*------------------------------------------------
  ARCHIVO: Notacredito.php
  DESCRIPCION: Contiene los métodos relacionados con la Nota de credito.
  FECHA DE CREACIÓN: 15/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class Notacredito extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Notacredito_model");
        $this->load->Model("Parametros_model");
        $this->load->Model("Empresa_model");
        $this->load->Model("Cliente_model");
        $this->load->Model("Sucursal_model");
        $this->load->Model("Almacen_model");
        $this->load->Model("Puntoemision_model");        
        $this->load->Model("Inventario_model");
/*        
        $this->load->Model("Comanda_model");
        $this->load->Model("Cajaapertura_model");
        $this->load->Model("Cajacierre_model");
        $this->load->Model("Usuario_model");
        $this->load->Model("Mesa_model");
        $this->load->Model("Retencion_model");*/
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
      date_default_timezone_set("America/Guayaquil");

      $desde = $this->session->userdata("tmp_nota_desde");
      $hasta = $this->session->userdata("tmp_nota_hasta");

      if (($desde == NULL) || ($hasta == NULL)){
        $fdesde = date("Y-m-d"); 
        $fhasta = date("Y-m-d"); 
        $varhd = '00:00:00';
        $varhh = '23:59:59';
        $desde = $fdesde." ".$varhd;
        $hasta = $fhasta." ".$varhh;
        $this->session->set_userdata("tmp_nota_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_nota_desde", $desde); } 
        else { $this->session->set_userdata("tmp_nota_desde", NULL); }
        $this->session->set_userdata("tmp_nota_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_nota_hasta", $hasta); } 
        else { $this->session->set_userdata("tmp_nota_hasta", NULL); }
      }  

      $monto = $this->Notacredito_model->monto_rango($desde, $hasta);

      $data["desde"] = $desde;
      $data["hasta"] = $hasta;

      $data["monto"] = $monto;
      $data["base_url"] = base_url();
      $data["content"] = "notacredito";
      $this->load->view("layout", $data);
    }

    public function listadoDataNota() {
        date_default_timezone_set("America/Guayaquil");
        $desde = $this->session->userdata("tmp_nota_desde");
        $hasta = $this->session->userdata("tmp_nota_hasta");
        $registro = $this->Notacredito_model->lst_notacredito($desde, $hasta); 
        $tabla = "";
        foreach ($registro as $row) {

          @$fec = str_replace('-', '/', $row->fecha); @$fec = date("d/m/Y  H:i:s", strtotime(@$fec));
          if($row->estatus != 1){
            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Imprimir Venta\" id=\"'.$row->id.'\" class=\"btn bg-navy color-palette btn-xs btn-grad nota_print\"><i class=\"fa fa-print\"></i></a>';
          }else{
            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Anular\" id=\"'.$row->id.'\" class=\"btn btn-danger btn-xs btn-grad anular_nota\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i></a> <a href=\"#\" title=\"Imprimir Venta\" id=\"'.$row->id.'\" class=\"btn bg-navy color-palette btn-xs btn-grad nota_print\"><i class=\"fa fa-print\"></i></a></div>';
/*            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Editar\" id=\"'.$row->id.'\" class=\"btn btn-success btn-xs btn-grad edi_fact\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Anular\" id=\"'.$row->id.'\" class=\"btn btn-danger btn-xs btn-grad anu_fact\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i></a> <a href=\"#\" title=\"Imprimir Venta\" id=\"'.$row->id.'\" class=\"btn bg-navy color-palette btn-xs btn-grad venta_print\"><i class=\"fa fa-print\"></i></a></div>';*/
          }          
          
           
            $tabla.='{"estatus":"' . $row->estatus . '",
                      "fecha":"' . $fec . '",
                      "nro_documento":"' . $row->nro_documento . '",
                      "cliente":"' . $row->nom_cliente . '",
                      "subtotal":"'.$row->subtotal.'",
                      "descuento":"'.$row->descuento.'",
                      "montoiva":"'.$row->montoiva.'",
                      "total":"'.$row->total.'",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_nota_fecha() {
      $this->session->unset_userdata("tmp_nota_desde"); 
      $this->session->unset_userdata("tmp_nota_hasta");
      $fecdesde = $this->input->post("fdesde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      $fechasta = $this->input->post("fhasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));
      $vdesde = $desde;
      $vhasta = $hasta;        
      $this->session->set_userdata("tmp_nota_desde", NULL);
      if ($vdesde != NULL) { $this->session->set_userdata("tmp_nota_desde", $vdesde);} 
      else { $this->session->set_userdata("tmp_nota_desde", NULL); }
      $this->session->set_userdata("tmp_nota_hasta", NULL);
      if ($vhasta != NULL) { $this->session->set_userdata("tmp_nota_hasta", $vhasta);} 
      else { $this->session->set_userdata("tmp_nota_hasta", NULL);}
      $arr['resu'] = 1;
      print json_encode($arr);
    } 

    public function upd_monto_total(){
        $desde = $this->session->userdata("tmp_nota_desde");
        $hasta = $this->session->userdata("tmp_nota_hasta");

        $monto = $this->Notacredito_model->monto_rango($desde, $hasta);

        $arr = $monto;
        print json_encode($arr);               
    }

    public function agregar() {
      $sucursales = $this->Sucursal_model->lst_sucursales();
      $data["sucursales"] = $sucursales;
      $cliente = $this->Cliente_model->sel_cli();
      $data["cliente"] = $cliente;
      $idusu = $this->session->userdata("sess_id");
      $tmpcomp = $this->Notacredito_model->ini_temp($idusu);      
      $data["tmpcomp"] = $tmpcomp;
      $detcomp = $this->Notacredito_model->lst_notatmp_detalle($idusu);      
      $data["detcomp"] = $detcomp;
      $tmpsuc = 0;
      if ($tmpcomp) { $tmpsuc = $tmpcomp->id_sucursal; }
      $almacen = $this->Almacen_model->lst_almacensucursal($tmpsuc);
      $data["almacen"] = $almacen;
      $puntoemision = $this->Puntoemision_model->lst_puntoemisionsucursal($tmpsuc);
      $data["puntoemision"] = $puntoemision;

      $data["base_url"] = base_url();
      $data["content"] = "notacredito_add";
      $this->load->view("layout", $data);
    }

    /* MOSTRAR VENTANA DE Facturas */
    public function busca_factura() {
        $idcliente = $this->input->post("cliente");
        $this->session->unset_userdata("tmp_nota_cliente"); 
        $this->session->set_userdata("tmp_nota_cliente", $idcliente);
        $data["base_url"] = base_url();
        $this->load->view("notacredito_busca_factura", $data);        
    }

    /* CARGA DE DATO AL DATATABLE */
    public function lst_factura_cliente() {
      $idcliente = $this->session->userdata("tmp_nota_cliente");
      $registro = $this->Notacredito_model->lst_factura_cliente($idcliente);
      $tabla = "";
      foreach ($registro as $row) {
          @$fec = str_replace('-', '/', $row->fecha); @$fec = date("d/m/Y  H:i:s", strtotime(@$fec));
          $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Añadir\" id=\"'.$row->id_venta.'\" class=\"btn btn-success btn-xs btn-grad add_docmodificado\"><i class=\"fa fa-cart-plus\"></i></a>  </div>';
          $tabla.='{"numero":"' . $row->nro_factura . '",
                    "fecha":"' . $fec . '",
                    "monto":"' . $row->montototal . '",
                    "ver":"'.$ver.'"},';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';

    }

    public function upd_docmodificado(){
        $idusu = $this->session->userdata("sess_id");
        $id = $this->input->post("id");

        $res = $this->Notacredito_model->upd_docmodificado($idusu, $id);
        print json_encode($res);               
    }

    /* MOSTRAR VENTANA DE PRODUCTOS */
    public function busca_producto() {
        $data["base_url"] = base_url();
        $this->load->view("notacredito_busca_producto", $data);        
    }
    

    /* CARGA DE DATO AL DATATABLE */
    public function lst_productonota() {
      $registro = $this->Notacredito_model->lst_productonota();
      $tabla = "";
      foreach ($registro as $row) {
          $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Añadir\" id=\"'.$row->pro_id.'\" class=\"btn btn-success btn-xs btn-grad add_producto\"><i class=\"fa fa-cart-plus\"></i></a>  </div>';
          $tabla.='{"ver":"'.$ver.'",
                    "codbarra":"' . $row->pro_codigobarra . '",
                    "codauxiliar":"' . $row->pro_codigoauxiliar . '",
                    "nombre":"' . addslashes(substr($row->pro_nombre,0,40)) . '",
                    "precio":"' . $row->pro_precioventa . '"},';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';

    }

    public function ins_producto(){
        $idusu = $this->session->userdata("sess_id");
        $id = $this->input->post("id");

        $this->Notacredito_model->ins_producto($idusu, $id);
        $arr['resu'] = 1;
        print json_encode($arr);               
    }

    public function upd_datosnota(){
        $idusu = $this->session->userdata("sess_id");
        $sucursal = $this->input->post("sucursal");
        $almacen = $this->input->post("almacen");
        $cliente = $this->input->post("cliente");
        $fec = $this->input->post("fechanota");
        $fec = str_replace('/', '-', $fec); 
        $fechanota = date("Y-m-d", strtotime($fec));
        $puntoemision = $this->input->post("puntoemision");
        $nronota = $this->input->post("nronota");       
        $iddocmod = $this->input->post("iddocmod");
        $nrodocmod = $this->input->post("nrodocmod");
        $fec = $this->input->post("fechadocmod");
        $fec = str_replace('/', '-', $fec); 
        $fechadocmod = date("Y-m-d", strtotime($fec));
        $motivo = $this->input->post("motivo");

        $this->Notacredito_model->upd_datosnota($idusu, $sucursal, $almacen, $cliente, $fechanota, $puntoemision, $nronota, $iddocmod, $nrodocmod, $fechadocmod, $motivo);
        $arr['resu'] = 1;
        print json_encode($arr);               
    }

    public function del_detalle(){
        $iddetalle = $this->input->post("id");

        $this->Notacredito_model->del_detalle($iddetalle);
        $arr['resu'] = 1;
        print json_encode($arr);               
    }

    public function upd_notadetalle(){
        $iddetalle = $this->input->post("id");
        $precio = $this->input->post("precio");
        $cantidad = $this->input->post("cantidad");

        $this->Notacredito_model->upd_notadetalle($iddetalle, $precio, $cantidad);
        $arr['resu'] = 1;
        print json_encode($arr);               
    }

    public function upd_descuento(){
        $idusu = $this->session->userdata("sess_id");
        $descuento = $this->input->post("descuento");

        $this->Notacredito_model->actualiza_descuento($idusu, $descuento);
        $arr['resu'] = 1;
        print json_encode($arr);               
    }

    public function del_productos(){
        $idusu = $this->session->userdata("sess_id");

        $this->Notacredito_model->del_productos($idusu);
        $arr['resu'] = 1;
        print json_encode($arr);               
    }

    public function guardar_nota(){
        $idusu = $this->session->userdata("sess_id");

        $id = $this->Notacredito_model->guardar_nota($idusu);

        $detnota = $this->Notacredito_model->lst_detnotaparakardex($id);    
        foreach ($detnota as $obj) {
          $this->Inventario_model->ins_kardexingreso($obj->id_producto, $obj->nro_documento, 
                                                     'NOTA DE VENTA', $obj->cantidad, 
                                                     $obj->precio, $obj->descsubtotal, 
                                                     $obj->pro_idunidadmedida, $obj->id_almacen);
        }    

        $arr['resu'] = $id;
        print json_encode($arr);               
    }

    /* ABRIR VENTANA PARA Imprimir Nota */
    public function imprimirnota(){
        $factoriva = 12;
        date_default_timezone_set("America/Guayaquil");
        
        $idcomp = $this->input->post('id');

        $tabla ="\r\n" . "NOTA DE CREDITO". "\r\n";
        $row = $this->Notacredito_model->get_nota_id($idcomp);

        $tabla.="Nro Nota:" . chr(32) . $row->puntoemision. '-' . $row->nro_documento . "\r\n";
        $strdate=date("d/m/Y", strtotime($row->fecha));
        $tabla.="Fecha Nota:" . chr(32) . $strdate . "\r\n";
        $tabla.="Nro Doc.Modificado:" . chr(32) . $row->nro_docmodificado . "\r\n";
        $strdate=date("d/m/Y", strtotime($row->fecha_docmodificado));
        $tabla.="Fecha Doc.Modificado:" . chr(32) . $strdate . "\r\n";
        $tabla.="Cliente:" . chr(32) . $row->nom_cliente . "\r\n";        
        $tabla.="Direccion:". chr(32) . $row->direccion_cliente . "\r\n";        
        $tabla.="CI/RUC:". chr(32) . $row->ident_cliente . "\r\n";        
        $tabla.="Telef.:". chr(32) . $row->telefonos_cliente . "\r\n";        

        $subtotaliva=$row->subtotaliva;
        $subtotalcero=$row->subtotalnoiva;
        $subtotaldiva=$row->descsubtotaliva;
        $subtotaldcero=$row->descsubtotalnoiva;
        $montoiva=$row->montoiva;
        $descuento=$row->descuento;

        $tabla.= "\r\n";
        $tabla.="CAN   DESCRIPCION                   PRECIO  SUBTOTAL  DESCUENTO  SUBT/DESC". "\r\n";        
        $registro = $this->Notacredito_model->lst_nota_detalle($idcomp);
        foreach ($registro as $row) {
            $strcant = $row->cantidad;
            $tabla.= $strcant;
            $lcant = strlen($strcant);
            while ($lcant < 6){
                $tabla.= chr(32);
                $lcant++;
            }
            $strnombre = substr($row->pro_nombre,0,29);
            $tabla.= $strnombre;
            $lcant = strlen($strnombre);
            while ($lcant < 30){
                $tabla.= chr(32);
                $lcant++;
            }

            $strprecio=number_format($row->precio,2);
            $tabla.= $strprecio;
            $lcant = strlen($strprecio);
            while ($lcant < 10){
                $tabla.= chr(32);
                $lcant++;
            }

            $strprecio=number_format($row->subtotal,2);
            $tabla.= $strprecio;
            $lcant = strlen($strprecio);
            while ($lcant < 10){
                $tabla.= chr(32);
                $lcant++;
            }

            $strprecio=number_format($row->descuento,2);
            $tabla.= $strprecio;
            $lcant = strlen($strprecio);
            while ($lcant < 11){
                $tabla.= chr(32);
                $lcant++;
            }

            $tabla.= number_format($row->descsubtotal,2) . "\r\n";
        }
        $totalpagar = $subtotaldiva + $subtotaldcero + $montoiva;
        $tabla.= "\r\n";
        $tabla.= "\r\n";
        $tabla.= "   SUBTOTAL  IVA12:" . chr(32) . number_format($subtotaliva,2) . "\r\n";
        $tabla.= "   SUBTOTAL   IVA0:" . chr(32) . number_format($subtotalcero,2) . "\r\n";
        $tabla.= "   DESCUENTO      :" . chr(32) . number_format($descuento,2) . "\r\n";
        $tabla.= "   SUBTOTDES IVA12:" . chr(32) . number_format($subtotaldiva,2) . "\r\n";
        $tabla.= "   SUBTOTDES  IVA0:" . chr(32) . number_format($subtotaldcero,2) . "\r\n";
        $tabla.= "   MONTO IVA12    :" . chr(32) . number_format($montoiva,2) . "\r\n";
        $tabla.= "   TOTAL FACTURA  :" . chr(32) . number_format($totalpagar,2) . "\r\n";
        $data["strcomanda"] = $tabla; 
        
        $impresionlocal = $this->Parametros_model->sel_impresionlocal();
        $data["impresionlocal"] = $impresionlocal; 
        
        $data["base_url"] = base_url();
        $this->load->view("notacredito_imprimir", $data);

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

    public function anular_nota(){
        $id = $this->input->post('id');

        $this->Notacredito_model->anular_nota($id);

        $detnota = $this->Notacredito_model->lst_detnotaparakardex($id);    
        foreach ($detnota as $obj) {
          $this->Inventario_model->ins_kardexegreso($obj->id_producto, $obj->nro_documento, 
                                                    'ANULACION DE NOTA DE VENTA', $obj->cantidad, 
                                                    $obj->precio, $obj->descsubtotal, 
                                                    $obj->pro_idunidadmedida, $obj->id_almacen);
        }    

        $arr['resu'] = 1;
        print json_encode($arr);               
    }

    /* Exportar a Excel */
    public function reporte(){
        $desde = $this->session->userdata("tmp_nota_desde");
        $hasta = $this->session->userdata("tmp_nota_hasta");
        $venta = $this->Notacredito_model->lst_notacredito($desde, $hasta);
        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteNotaCredito');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Nota de Credito');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Nro.Nota');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Cliente');
        $this->excel->getActiveSheet()->setCellValue('D3', 'C.I./R.U.C');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Subtotal');        
        $this->excel->getActiveSheet()->setCellValue('F3', 'Descuento');  
        $this->excel->getActiveSheet()->setCellValue('G3', 'Monto IVA');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Total');

        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);

        $total = 0;
        $subtotal = 0;
        $descuento = 0;
        $montoiva = 0;
        $fila = 4;
        foreach ($venta as $ven) {
          if($ven->estatus != 3){
            $total += $ven->total;
            $subtotal += $ven->subtotal;
            $descuento += $ven->descuento;
            $montoiva += $ven->montoiva;

            @$fec = str_replace('-', '/', $ven->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); 
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->nro_documento);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ven->nom_cliente);            
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $ven->ident_cliente);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($ven->subtotal,2));
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($ven->descuento,2));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($ven->montoiva,2));
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($ven->total,2));

            $fila++;          
          }  
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('D'.$fila, 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($subtotal,2));
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($descuento,2));
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($montoiva,2));
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($total,2));

        $this->excel->getActiveSheet()->getStyle('E'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H'.$fila)->getFont()->setBold(true);

        
        $filename='reportenotacredito.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  


}



?>


