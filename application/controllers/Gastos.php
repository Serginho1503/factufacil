<?php
/*------------------------------------------------
  ARCHIVO: Gastos.php
  DESCRIPCION: Contiene los métodos relacionados con la Gastos.
  FECHA DE CREACIÓN: 30/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Gastos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Gastos_model");
        $this->load->Model("Proveedor_model");
        $this->load->Model("Parametros_model");
        $this->load->Model("Cajachica_model");
        $this->load->Model("Retencion_model");
        $this->load->Model("Sucursal_model");
        $this->load->Model("Puntoemision_model");
        date_default_timezone_set("America/Guayaquil");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $data["sucursales"] = $sucursales;      

        $desde = $this->session->userdata("tmp_gas_desde");
        $hasta = $this->session->userdata("tmp_gas_hasta");        
        $sucursal = $this->session->userdata("tmp_gas_sucursal");        

        if ($desde == NULL){
            $desde = date("Y-m-d"); 
            $hasta = date("Y-m-d");         
            $sucursal = 0;         
            if (count($sucursales) > 0){ $sucursal = $sucursales[0]->id_sucursal;}

            $this->session->set_userdata("tmp_gas_desde", NULL);
            if ($desde != NULL) { $this->session->set_userdata("tmp_gas_desde", $desde); } 
            else { $this->session->set_userdata("tmp_gas_desde", NULL); }
            $this->session->set_userdata("tmp_gas_hasta", NULL);
            if ($hasta != NULL) { $this->session->set_userdata("tmp_gas_hasta", $hasta); } 
            else { $this->session->set_userdata("tmp_gas_hasta", NULL); }
            $this->session->set_userdata("tmp_gas_sucursal", NULL);
            if ($sucursal != NULL) { $this->session->set_userdata("tmp_gas_sucursal", $sucursal); } 
            else { $this->session->set_userdata("tmp_gas_sucursal", NULL); }
        }    

        $total_gastos = $this->Gastos_model->gastos_total($sucursal, $desde, $hasta);

        $lstcaja = $this->Cajachica_model->lst_cajachica_sucursal($sucursal);
        $caja = 0;
        if (count($lstcaja) > 0) {$caja = $lstcaja[0]->id_caja; }
        $cajac = $this->Cajachica_model->cajachica_resumen($caja);
        if(count($cajac) > 0){ $dis_caja = $cajac; }
        else{ $dis_caja = 0; }

        $data["base_url"] = base_url();
        $data["caja"] = $dis_caja;      
        $data["totalg"] = $total_gastos; 
        $data["content"] = "gastos";
        $this->load->view("layout", $data);
    }



   /* CARGA DE DATOS AL DATATABLE */
    public function listadoDataGas() {
        $desde = $this->session->userdata("tmp_gas_desde");
        $hasta = $this->session->userdata("tmp_gas_hasta");        
        $sucursal = $this->session->userdata("tmp_gas_sucursal");        
        $registro = $this->Gastos_model->lista_gastos($sucursal, $desde, $hasta);
        $tabla = "";
        foreach ($registro as $row) {

            $consumidorfinal = (($row->id_proveedor == 1) || (substr($row->nro_ide_proveedor,0,10) == '9999999999')) ? '1' : '0';

            @$fec = str_replace('-', '/', $row->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); 

            $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
            $reemplazar=array("", "", "", "");
            $desc=str_ireplace($buscar,$reemplazar,$row->descripcion);         

            if($row->estatus == 3){
                $ver = '<div class=\"text-center\">  </div>';
            }else{
                $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Anular Gastos\" id=\"'.$row->id_gastos.'\" class=\"btn btn-danger btn-xs anu_gas\"><i class=\"fa fa-ban\"></i></a> <a href=\"#\" title=\"Retención Gastos\" id=\"'.$row->id_gastos.'\" name=\"'.$consumidorfinal.'\" class=\"btn bg-green color-palette btn-xs btn-grad ret_comp\"><i class=\"fa fa-registered\"></i></a> </div>';
            }
            $tabla.='{"estatus":"' . $row->estatus . '",
                      "id":"' . $row->id_gastos . '",
                      "fecha":"' . $fec . '",
                      "proveedor":"' . $row->nom_proveedor . '",
                      "factura":"' . $row->nro_factura . '",
                      "descripcion":"' . $desc . '",
                      "categoria":"' . $row->categoria . '",                         
                      "total":"' . $row->total . '",                                                               
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    } 

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_gastos() {
        $this->session->unset_userdata("tmp_gas_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_gas_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_gas_id", $id);
        } else {
            $this->session->set_userdata("tmp_gas_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    /* ABRIR VENTANA PARA MODIFICAR */
    public function upd_gastos(){
        $idgas = $this->session->userdata("tmp_gas_id");
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $provee = $this->Proveedor_model->sel_prov();
        $gastos = $this->Gastos_model->sel_gas_id($idgas);
        $catgastos = $this->Gastos_model->categorialst();

        $sritc = $this->Gastos_model->sel_sri_tipo_doc();
        $data["sritc"] = $sritc;

        $srist = $this->Gastos_model->sel_sri_sust_trib();
        $data["srist"] = $srist; 

        $data["catgastos"] = $catgastos;             
        $data["base_url"] = base_url();     
        $data["sucursales"] = $sucursales;
        $data["provee"] = $provee;
        $data["gastos"] = $gastos;
        $data["content"] = "gastos_add";
        $this->load->view("layout", $data);
    }

    public function del_gastos(){
        $idgas = $this->input->post("id");
        $gastos = $this->Gastos_model->del_gas_id($idgas);
        $arr = $idgas;
        print json_encode($arr);
    }

    /* ABRIR VENTANA PARA AGREGAR */
    public function add_gastos(){
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $provee = $this->Proveedor_model->sel_prov();
        $catgastos = $this->Gastos_model->categorialst();

        $tmpidsuc  = 0;
        if (count($sucursales)) {$tmpidsuc  = $sucursales[0]->id_sucursal;}
        $lstcaja = $this->Cajachica_model->lst_cajachica_sucursal($tmpidsuc);
        $caja = 0;
        if (count($lstcaja) > 0) {$caja = $lstcaja[0]->id_caja; }
        $cajac = $this->Cajachica_model->cajachica_resumen($caja);
        $dis_caja = 0;
        if ($cajac != NULL) {$dis_caja = $cajac->resumen;}
        $data["caja"] = $dis_caja;        

        $sritc = $this->Gastos_model->sel_sri_tipo_doc();
        $data["sritc"] = $sritc;

        $srist = $this->Gastos_model->sel_sri_sust_trib();
        $data["srist"] = $srist; 

        $data["base_url"] = base_url();     
        $data["sucursales"] = $sucursales;
        $data["provee"] = $provee;
        $data["catgastos"] = $catgastos;
        $data["content"] = "gastos_add";
        $this->load->view("layout", $data);
    } 

    public function upd_gastos_sucursal(){
        $idsuc = $this->input->post("sucursal");
        $lstcaja = $this->Cajachica_model->lst_cajachica_sucursal($idsuc);
        $caja = 0;
        if (count($lstcaja) > 0) {$caja = $lstcaja[0]->id_caja; }
        $cajac = $this->Cajachica_model->cajachica_resumen($caja);
        $dis_caja = 0;
        if ($cajac != NULL) {$dis_caja = $cajac->resumen;}
        print json_encode($dis_caja);
    }


    /* GUARDAR GASTOS */
    public function pagar(){

        $dif = 0;
        $idusu = $this->session->userdata("sess_id");
        $fec = $this->input->post("fecha");
        $fecha = str_replace('/', '-', $fec); 
        $fecha = date("Y-m-d", strtotime($fecha));
        $idgastos = $this->input->post("idgastos");        
        $sucursal = $this->input->post("sucursal");
        $proveedor = $this->input->post("proveedor");
        $categoria = $this->input->post("categoria");
        $factura = $this->input->post("factura");
        $autorizacion = $this->input->post("autorizacion");
        $descripcion = $this->input->post("descripcion");
        $formapago = $this->input->post("formapago");
        $efectivo = $this->input->post("efectivo");
        $tarjeta = $this->input->post("tarjeta");
        $cambio = $this->input->post("cambio");
        $dias = $this->input->post("dias");
        $subtotal = $this->input->post("subtotal");
        $subtotalivacero = $this->input->post("subtotalivacero");
        $descuento = $this->input->post("descuento");
        $subtotaldesc = $this->input->post("subtotaldesc");
        $subtotalivacerodesc = $this->input->post("subtotalivacerodesc");

        $tipodoc = $this->input->post("tipodoc");
        $sustributario = $this->input->post("sustributario");

        $iva = $this->input->post("iva");
        $montoiva = $this->input->post("montoiva");
        $total = $this->input->post("total");

        if($formapago == 'Contado'){
          $dif = $efectivo - $cambio;
          if($dif < 0){
            $efectivo = 0;
            $tarjeta = $tarjeta - $dif;
          }else{
            $efectivo = $dif;
          }
        }else{
            $efectivo = 0;            
            $tarjeta = 0;            
        }

/*
      print '$idusu-'.$idusu.'fecha-'.$fecha.'proveedor-'. $proveedor.'factura-'. $factura.'autorizacion-'. $autorizacion.'descripcion-'. $descripcion.'formapago-'. $formapago.'efectivo-'. $efectivo.'tarjeta-'. $tarjeta.'cambio-'. $cambio.'dias-'. $dias.'subtotal-'. $subtotal.'subtotalivacero-'. $subtotalivacero.'descuento-'. $descuento.'subtotaldesc-'. $subtotaldesc.'subtotalivacerodesc-'. $subtotalivacerodesc.'iva-'. $iva.'montoiva-'. $montoiva.'total-'. $total.'categoria-'. $categoria.'tipodoc-'. $tipodoc.'sustributario-'. $sustributario;
      die();
  */
      $tipodocmod = $this->input->post("tipodocmod");
      $numdocmod = $this->input->post("numdocmod");
      $autodocmod = $this->input->post("autodocmod");


      if($idgastos == 0 ){
        $add = $this->Gastos_model->pagar_gastos($idusu, $sucursal, $fecha, $proveedor, $factura, $autorizacion, 
                                                 $descripcion, $formapago, $efectivo, $tarjeta, $cambio, $dias, 
                                                 $subtotal, $subtotalivacero, $descuento, $subtotaldesc, $subtotalivacerodesc, 
                                                 $iva, $montoiva, $total, $categoria, $tipodoc, $sustributario,
                                                 $tipodocmod, $numdocmod, $autodocmod);

        $objfac = $this->Gastos_model->busca_gasto($add);
        $objsuc = $this->Sucursal_model->sel_suc_id($objfac->id_sucursal);
        $contabilizar = $objsuc->contabilizacion_automatica; 
        $arr['contabilizar'] = $contabilizar;
        $arr['nuevoid'] = $add;
    
      }else{
        $this->Gastos_model->actualiza_gastos($idusu, $sucursal, $fecha, $proveedor, $factura, $autorizacion, 
                                              $descripcion, $formapago, $efectivo, $tarjeta, $cambio, $dias, 
                                              $subtotal, $descuento, $subtotaldesc, $iva, $montoiva, $total, 
                                              $categoria, $tipodoc, $sustributario, $idgastos,
                                              $tipodocmod, $numdocmod, $autodocmod);
      }  
      

      $arr['dat'] = $idgastos;
      print json_encode($arr);
    }


    /* ABRIR VENTANA PARA REPORTE DE GASTOS */
    public function reporte(){
        $desde = $this->session->userdata("tmp_gas_desde");
        $hasta = $this->session->userdata("tmp_gas_hasta");
        $sucursal = $this->session->userdata("tmp_gas_sucursal");
        /* Se consulta el rago de Fecha */
        $gasto = $this->Gastos_model->venta_rango($sucursal, $desde, $hasta);

        $data["base_url"] = base_url();
        $data["gasto"] = $gasto;
        $this->load->view("gastos_reporte", $data);      
    }

    /* Exportar Compra a Excel */
    public function reportegastosXLS(){

        //$gasto = $this->Gastos_model->lista_gastos();

        // $vard = $this->session->userdata("tmp_gas_desde");
        // $varh = $this->session->userdata("tmp_gas_hasta");
        // /* Tratamiento de Fecha Desde */
        // $fec_a = str_replace('-', '/', $vard); 
        // $desde = date("Y-d-m", strtotime($fec_a)); 
        // /* Tratamiento de Fecha Hasta */
        // $fec_h = str_replace('-', '/', $varh); 
        // $hasta = date("Y-d-m", strtotime($fec_h)); 
        // /* Se consulta el rago de Fecha */
        // $gasto = $this->Gastos_model->venta_rango($desde, $hasta);      

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

        // $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        // $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        // $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
        // $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        // $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);

        // $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        // $this->excel->getActiveSheet()->setCellValue('B3', 'Proveedor');
        // $this->excel->getActiveSheet()->setCellValue('C3', 'Factura');
        // $this->excel->getActiveSheet()->setCellValue('D3', 'Descripcion');
        // $this->excel->getActiveSheet()->setCellValue('E3', 'Total');

        // $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        // $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        // $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        // $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        // $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);

        // $total = 0;
        // $fila = 4;
        // foreach ($gasto as $det) {
        //   $total += $det->total;

        //   $fec = str_replace('-', '/', $det->fecha); 
        //   $fec = date("d/m/Y", strtotime($fec));  
        //   $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
        //   $this->excel->getActiveSheet()->setCellValue('B'.$fila, $det->nom_proveedor);
        //   $this->excel->getActiveSheet()->setCellValue('C'.$fila, $det->nro_factura);
        //   $this->excel->getActiveSheet()->setCellValue('D'.$fila, $det->descripcion);
        //   $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($det->total,2));

        //   $fila++;          
        // }    
        // $fila++;          

        // $this->excel->getActiveSheet()->setCellValue('D'.$fila, 'TOTAL');
        // $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($total,2));
        // $this->excel->getActiveSheet()->getStyle('D'.$fila)->getFont()->setBold(true);
        // $this->excel->getActiveSheet()->getStyle('E'.$fila)->getFont()->setBold(true);

         
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


    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_gastos_fecha() {
        $this->session->unset_userdata("tmp_gas_desde"); 
        $this->session->unset_userdata("tmp_gas_hasta");
        $this->session->unset_userdata("tmp_gas_sucursal");

        $sucursal = $this->input->post("sucursal");
        $fecdesde = $this->input->post("desde");
        $desde = str_replace('/', '-', $fecdesde); 
        $desde = date("Y-m-d", strtotime($desde));

        $fechasta = $this->input->post("hasta");
        $hasta = str_replace('/', '-', $fechasta); 
        $hasta = date("Y-m-d", strtotime($hasta));        

        $this->session->set_userdata("tmp_gas_sucursal", NULL);
        if ($sucursal != NULL) {
            $this->session->set_userdata("tmp_gas_sucursal", $sucursal);
        } else {
            $this->session->set_userdata("tmp_gas_sucursal", NULL);
        }

        $this->session->set_userdata("tmp_gas_desde", NULL);
        if ($desde != NULL) {
            $this->session->set_userdata("tmp_gas_desde", $desde);
        } else {
            $this->session->set_userdata("tmp_gas_desde", NULL);
        }

        $this->session->set_userdata("tmp_gas_hasta", NULL);
        if ($hasta != NULL) {
            $this->session->set_userdata("tmp_gas_hasta", $hasta);
        } else {
            $this->session->set_userdata("tmp_gas_hasta", NULL);
        }

        $arr['resu'] = 1;
        print json_encode($arr);
    }    

    public function upd_listado(){
    //    date_default_timezone_set("America/Guayaquil");
        $desde = $this->session->userdata("tmp_gas_desde");
        $hasta = $this->session->userdata("tmp_gas_hasta");

           
        /* Se consulta el rago de Fecha */
        $lst_fecha = $this->Gastos_model->venta_rango($desde, $hasta);

    //    print_r($lst_fecha); die; 

        $data["lst_gastos"] = $lst_fecha;
        $data["base_url"] = base_url();
        $this->load->view("gastos_tabla", $data);

    }  

    public function upd_gastos_total(){
        $desde = $this->session->userdata("tmp_gas_desde");
        $hasta = $this->session->userdata("tmp_gas_hasta");
        $sucursal = $this->session->userdata("tmp_gas_sucursal");

        $monto_gastos = $this->Gastos_model->gastos_total_rago($sucursal, $desde, $hasta);

        $arr = $monto_gastos;
        print json_encode($arr);               

    }

    public function confirmar_anulacion(){
        $id_gasto = $this->input->post("id");
        $fact = $this->Gastos_model->busca_gasto($id_gasto);
        $data["factura"] = $fact;
        $data["base_url"] = base_url();
        $this->load->view("gastos_anular", $data);      
    }

    public function anular(){

        $id_gasto = $this->input->post("txt_idgasto");
        $obs = $this->input->post("txt_obs");
        $resu = $this->Gastos_model->anular_gasto($id_gasto, $obs);

        $arr = 1;
        print json_encode($arr); 
    }

    /* TEMPORAL PARA LAS RETENCIONES DE LAS Gastos */
    public function temp_gastosret() {
        $this->session->unset_userdata("temp_idretgas"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("temp_idretgas", NULL);
        if ($id != NULL) { $this->session->set_userdata("temp_idretgas", $id); } 
        else { $this->session->set_userdata("temp_idretgas", NULL); }
        $arr['resu'] = $id;
        print json_encode($arr);
    }    

    public function gastos_retencion(){
        $id = $this->session->userdata("temp_idretgas");
        $objcompra = $this->Gastos_model->busca_gasto($id);
        if ($objcompra){
            $puntoemision = $this->Puntoemision_model->lst_puntoemisionsucursal($objcompra->id_sucursal);
            $data["puntoemision"] = $puntoemision;
        }    
        $this->Retencion_model->retenciongastos_defaultadd($id);
        $this->Retencion_model->retenciongastos_cargardetalletmp($id);
        $comp = $this->Gastos_model->selgastosret($id);
        $data["comp"] = $comp;
        $data["base_url"] = base_url();
        $data["content"] = "gastos_retencion";
        $this->load->view("layout", $data);           
    }

    public function add_retencion(){
        $idcomp = $this->session->userdata("temp_idretgas");
        $lstret = $this->Retencion_model->ret_lst_comp();
        $lstretiva = $this->Retencion_model->retiva_lst_porciento();
        $subtotalconiva = $this->input->post("subtotalconiva");
        $subtotalsiniva = $this->input->post("subtotalsiniva");
        $lst_subtotaldisp = $this->Retencion_model->lst_subtotalretdisp_gastos(0);
        if ($lst_subtotaldisp){
            $subtotalconiva -= $lst_subtotaldisp[0]->baseiva_disp;
            $subtotalsiniva -= $lst_subtotaldisp[0]->basenoiva_disp;
        }
        $sucursal = $this->Gastos_model->busca_gasto($idcomp);       
        $proxnumret = $this->Retencion_model->get_proxnumeroretencion($sucursal->id_sucursal);       
        
        $data["lstret"] = $lstret;        
        $data["lstretiva"] = $lstretiva; 
        $data["proxnumret"] = $proxnumret; 
        $data["subtotalconiva"] = $subtotalconiva;        
        $data["subtotalsiniva"] = $subtotalsiniva;        
        $data["base_url"] = base_url();
        $this->load->view("gastos_retencion_add", $data);
    }

    public function listadoRetGastos() {
        $idcomp = $this->session->userdata("temp_idretgas");
        $registro = $this->Retencion_model->lst_retenciondettmp_gastos();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Editar Retencion\" id=\"'.$row->id_detallerenta.'\" class=\"btn btn-success btn-xs btn-grad ret_upd\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_detallerenta.'\" name=\"'.substr(addslashes($row->concepto), 0, 40).'\" class=\"btn btn-danger btn-xs btn-grad ret_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{  "concepto":"' .substr(addslashes($row->concepto), 0, 40). '",
                        "basenoiva":"' .$row->base_noiva. '",
                        "baseiva":"' .$row->base_iva. '",
                        "por100retrenta":"' .$row->porciento_retencion_renta. '",
                        "valorretrenta":"' .$row->valor_retencion_renta. '",
                        "ver":"'.$ver.'"
                    },';

        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function editar_retencion(){
        $idcomp = $this->session->userdata("temp_idretgas");
        $id = $this->input->post("id");
        $subtotalconiva = $this->input->post("subtotalconiva");
        $subtotalsiniva = $this->input->post("subtotalsiniva");
        $lst_subtotaldisp = $this->Retencion_model->lst_subtotalretdisp_gastos($id);
        if ($lst_subtotaldisp){
            $subtotalconiva -= $lst_subtotaldisp[0]->baseiva_disp;
            $subtotalsiniva -= $lst_subtotaldisp[0]->basenoiva_disp;
        }
        $lstret = $this->Retencion_model->ret_lst_comp();
        $lstretiva = $this->Retencion_model->retiva_lst_porciento();
        $retencion = $this->Retencion_model->sel_detalleretenciongastos($id);
        $data["lstret"] = $lstret;        
        $data["lstretiva"] = $lstretiva;        
        $data["retencion"] = $retencion;        
        $data["subtotalconiva"] = $subtotalconiva;        
        $data["subtotalsiniva"] = $subtotalsiniva;        
        $data["base_url"] = base_url();
        $this->load->view("gastos_retencion_add", $data);
    }

    /* GUARDAR RETENCION DE COMPRA*/
    public function guardar_detalleretencion(){       
        $id_ret = $this->input->post('txt_idret');        
        $concepto = $this->input->post('cmb_tip_ide');
        $basenoiva = $this->input->post('txt_basenoiva');
        $baseiva = $this->input->post('txt_baseiva');
        $por100retrenta = $this->input->post('txt_p100retrenta');
        $valorretrenta = $this->input->post('txt_valorrenta');

        if ($id_ret == 0){
            $this->Retencion_model->retencionrentagastos_add($concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta);
        } else{
            $this->Retencion_model->retencionrentagastos_upd($id_ret, $concepto, $basenoiva, $baseiva, $por100retrenta, $valorretrenta);
        }
        
        $tmpretenido = $this->Retencion_model->retencionrentagastos_tmpretenido();

        print json_encode($tmpretenido);
    } 

    /* GUARDAR RETENCION DE COMPRA*/
    public function guardar_retencion(){
        
        /*$idcompra = $this->session->userdata("temp_idcomp");*/
        $idretgastos = $this->input->post('txt_id_comp_ret');
        /*$id_ret = $this->input->post('txt_idret');*/
        $autorizacion = $this->input->post('txt_autorizacion');

        $fec = $this->input->post('fecha_ret');
        $fecha = str_replace('/', '-', $fec); 
        $fecha = date("Y-m-d", strtotime($fecha));

        $retiva10 = $this->input->post('txt_retiva10');
        $retiva20 = $this->input->post('txt_retiva20');
        $retiva30 = $this->input->post('txt_retiva30');
        $retiva50 = $this->input->post('txt_retiva50');
        $retiva70 = $this->input->post('txt_retiva70');
        $retiva100 = $this->input->post('txt_retiva100');

        $ptoemision = $this->input->post('cmb_punto');
        $nroretencion = $this->input->post('txt_factura');       

        $this->Retencion_model->retenciongastos_guardar($idretgastos, $autorizacion, $fecha, $retiva10, $retiva20, $retiva30, $retiva50, $retiva70, $retiva100, $ptoemision, $nroretencion);
        
        print json_encode(1);
    } 

    public function eliminar_retencion(){
        $id = $this->input->post("id");
        $this->Retencion_model->retenciongastos_del($id);
        print json_encode(1);
    }

    public function eliminar_retencionrenta(){
        $id = $this->input->post("id");
        $this->Retencion_model->retencionrentagastos_del($id);

        $tmpretenido = $this->Retencion_model->retencionrentagastos_tmpretenido();

        print json_encode($tmpretenido);
    }

    public function sel_nroret_ptoemi(){
        $punto = $this->input->post("punto");
        $nroretencion = $this->Retencion_model->get_proxnumeroretencion($punto);      
        $arr['nroretencion'] = str_pad($nroretencion, 9, "0", STR_PAD_LEFT);
        print json_encode($arr);               
    }


}

?>

