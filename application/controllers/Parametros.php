<?php

/*------------------------------------------------
  ARCHIVO: Empresa.php
  DESCRIPCION: Contiene los métodos relacionados con la Empresa.
  FECHA DE CREACIÓN: 05/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Parametros extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Parametros_model");
        $this->load->Model("Comanda_model");
        $this->load->Model("Serviciotecnico_model");
        $this->load->Model("Sistema_model");
        $this->load->Model("Mesa_model");

    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $iva = $this->Parametros_model->iva_get();
        $data["iva"] = $iva->valor * 100;
        $impprecuenta = $this->Parametros_model->impresoraprecuenta_get();
        $data["impprecuenta"] = $impprecuenta->valor;
        if (trim($impprecuenta->valor) != '') {
            $objprecuenta = $this->Comanda_model->sel_com_id($impprecuenta->valor);
            $data["objprecuenta"] = $objprecuenta;
        }
        $impfactura = $this->Parametros_model->impresorafactura_get();
        $data["impfactura"] = $impfactura->valor;
        if (trim($impfactura->valor) != '') {
            $objfactura = $this->Comanda_model->sel_com_id($impfactura->valor);
            $data["objfactura"] = $objfactura;
        }
        $impresoras = $this->Comanda_model->lista_comanda();
        $data["impresoras"] = $impresoras;

        $factura = $this->Parametros_model->sel_nro_factura();
        $notaventa = $this->Parametros_model->sel_nro_nronot();
        $codestab = $this->Parametros_model->sel_codigoestab();
        $codptoemi = $this->Parametros_model->sel_codigopuntoemision();

        $pedidovista = $this->Parametros_model->sel_pedidovista();
        $pedidocliente = $this->Parametros_model->sel_pedidocliente();
        $pedidomesero = $this->Parametros_model->sel_pedidomesero();

        $tipoprecio = $this->Parametros_model->sel_tipoprecio();
        $facturasinexistencia = $this->Parametros_model->sel_facturasinexistencia();
        $descpro = $this->Parametros_model->sel_descpro();        

        $comprobpago = $this->Parametros_model->sel_nro_comprobpago();

        $facturapdf = $this->Parametros_model->sel_facturapdf();        
        $data["facturapdf"] = $facturapdf;        

        $limiteprodventa = $this->Parametros_model->sel_limiteprodventa();        
        $data["limiteprodventa"] = $limiteprodventa;        

        $impuestoadicional = $this->Parametros_model->sel_impuestoadicional();        
        $data["impuestoadicdescrip"] = $impuestoadicional->descripcion;        
        $data["impuestoadicvalor"] = $impuestoadicional->valor;        

        $retencion = $this->Parametros_model->sel_nro_retencion();
        $data["retencion"] = $retencion;

        $serviciotecnico = $this->Serviciotecnico_model->lst_configservicio();
        $data["serviciotecnico"] = $serviciotecnico;
        $pro_servicio = $this->Serviciotecnico_model->sel_pro_servicio();
        $data["pro_servicio"] = $pro_servicio;

        $numeroserie = $this->Parametros_model->sel_numeroserie();
        $data["numeroserie"] = $numeroserie->valor;

        $imprimircomandafactura = $this->Parametros_model->sel_comandafactura();        
        $data["imprimircomandafactura"] = $imprimircomandafactura;        

        $habilitanumeroorden = $this->Parametros_model->sel_habilitaorden();        
        $data["habilitanumeroorden"] = $habilitanumeroorden;        

        $facturaprecioconiva = $this->Parametros_model->sel_facturaprecioconiva();        
        $data["facturaprecioconiva"] = $facturaprecioconiva;        

        $clientevendedor = $this->Parametros_model->sel_clientevendedor();        
        $data["clientevendedor"] = $clientevendedor;        
        $cuotaclientevendedor = $this->Parametros_model->sel_cuotaclientevendedor();        
        $data["cuotaclientevendedor"] = $cuotaclientevendedor;        
        $codigocliente = $this->Parametros_model->sel_codigocliente();        
        $data["codigocliente"] = $codigocliente;        
        $impresionlocal = $this->Parametros_model->sel_impresionlocal();        
        $data["impresionlocal"] = $impresionlocal;        
        $habilitavariante = $this->Parametros_model->sel_habilitavariante();        
        $data["habilitavariante"] = $habilitavariante;        
        $ubicacionventa = $this->Parametros_model->sel_ubicacionventa();        
        $data["ubicacionventa"] = $ubicacionventa;        
        $detalletotalivaventa = $this->Parametros_model->sel_detalletotalivaventa();        
        $data["detalletotalivaventa"] = $detalletotalivaventa;        
        $impresiongrafica = $this->Parametros_model->sel_impresiongrafica();        
        $data["impresiongrafica"] = $impresiongrafica;        
        $impresionsubsidio = $this->Parametros_model->sel_impresionsubsidio();        
        $data["impresionsubsidio"] = $impresionsubsidio;        
        $pedidopromo = $this->Parametros_model->sel_pedidopromo();        
        $data["pedidopromo"] = $pedidopromo;        

        $decimalesprecio = $this->Parametros_model->sel_decimalesprecio();        
        $data["decimalesprecio"] = $decimalesprecio;        
        $decimalescantidad = $this->Parametros_model->sel_decimalescantidad();        
        $data["decimalescantidad"] = $decimalescantidad;        

        $habilitanotaventaiva = $this->Parametros_model->sel_habilitanotaventaiva();        
        $data["habilitanotaventaiva"] = $habilitanotaventaiva;               

        $habilitacorreoautosri = $this->Parametros_model->sel_habilitacorreoautosri();        
        $data["habilitacorreoautosri"] = $habilitacorreoautosri;               

        $habilitacambioprecio = $this->Parametros_model->sel_habilitacambioprecio();        
        $data["habilitacambioprecio"] = $habilitacambioprecio;               

        $ventapagoefectivo = $this->Parametros_model->sel_ventapagoefectivo();        
        $data["ventapagoefectivo"] = $ventapagoefectivo;               

        $habilitaclientecategoria = $this->Parametros_model->sel_habilitaclientecategoria();        
        $data["clientecategoria"] = $habilitaclientecategoria;        

        $habilitapetshop = $this->Parametros_model->sel_habilitapetshop();        
        $data["habilitapetshop"] = $habilitapetshop;        

        $tipodescuentoproducto = $this->Parametros_model->sel_tipodescuentoproducto();        
        $data["tipodescuentoproducto"] = $tipodescuentoproducto;        

        $lst_colorheader = $this->Sistema_model->lst_colorskin();        
        $data["lst_colorheader"] = $lst_colorheader;        
        $sistema = $this->Sistema_model->sel_sistema();        
        $data["sistema"] = $sistema;     
        $imagenfondo = $this->Sistema_model->sel_imagenfondo();
        $data["imagenfondo"] = $imagenfondo;     
   
        $ptoventasingular = $this->Parametros_model->sel_ptoventasingular();        
        $data["ptoventasingular"] = $ptoventasingular;               
        $ptoventaplural = $this->Parametros_model->sel_ptoventaplural();        
        $data["ptoventaplural"] = $ptoventaplural;               

        $lst_estadoptoventa = $this->Mesa_model->lst_estadoptoventa(false);        
        $data["lst_estadoptoventa"] = $lst_estadoptoventa;        
        $estadoptoventafacturar = $this->Parametros_model->sel_estadoptoventafacturar();        
        $data["estadoptoventafacturar"] = $estadoptoventafacturar;               

        $data["factura"] = $factura;
        $data["notaventa"] = $notaventa;
        $data["codestab"] = $codestab->valor;
        $data["codptoemi"] = $codptoemi->valor;

        $data["pedidovista"] = $pedidovista->valor;
        $data["pedidocliente"] = $pedidocliente->valor;
        $data["pedidomesero"] = $pedidomesero->valor;

        $data["tipoprecio"] = $tipoprecio->valor;
        $data["facturasinexistencia"] = $facturasinexistencia->valor;
        $data["descpro"] = $descpro;

        $data["comprobpago"] = $comprobpago;

        $data["content"] = "parametros";
        $this->load->view("layout", $data);
    }

    /* ACTUALIZAR LOS REGISTROS DE Parametros */
    public function guardar(){

        $iva = $this->input->post('txt_iva');
        $iva = number_format( $iva / 100, 2);
        $impprecuenta = $this->input->post('txt_impprecuenta');
        $impfactura = $this->input->post('txt_impfactura');
        $codestab = $this->input->post('txt_codestab');
        $codptoemi = $this->input->post('txt_codptoemi');
       
        if ($this->input->post('chk_pedidovista') == 'on') {$pedidovista = 1;} else {$pedidovista = 0;}
        if ($this->input->post('chk_pedidocliente') == 'on') {$pedidocliente = 1;} else {$pedidocliente = 0;}
        if ($this->input->post('chk_pedidomesero') == 'on') {$pedidomesero = 1;} else {$pedidomesero = 0;}
        if ($this->input->post('chk_tipoprecio') == 'on') {$tipoprecio = 1;} else {$tipoprecio = 0;}
        if ($this->input->post('chk_facturasinexistencia') == 'on') {$facturasinexistencia = 1;} else {$facturasinexistencia = 0;}
        if ($this->input->post('chk_numeroserie') == 'on') {$numeroserie = 1;} else {$numeroserie = 0;}
        if ($this->input->post('chk_descpro') == 'on') {$descpro = 1;} else {$descpro = 0;}        

        
        /*if ($this->input->post('chk_printpdf') == 'on') {$facturapdf = 1;} else {$facturapdf = 0;}*/
        $facturapdf = $this->input->post("txt_formatoimpfactura");
        $facturapdf = $facturapdf * 1;

        $comprobpago = $this->input->post("txt_comprobpago");
        $comprobpago = $comprobpago * 1;

        $limiteprodventa = $this->input->post("txt_limiteprodventa");
        $limiteprodventa = $limiteprodventa * 1;

        $impuestoadicdescrip = $this->input->post("txt_impuestoadicdescrip");
        $impuestoadicvalor = $this->input->post("txt_impuestoadicvalor");

        if ($this->input->post('chk_comandafactura') == 'on') {$imprimircomandafactura = 1;} else {$imprimircomandafactura = 0;}
        if ($this->input->post('chk_numeroorden') == 'on') {$habilitanumeroorden = 1;} else {$habilitanumeroorden = 0;}
        if ($this->input->post('chk_clientevendedor') == 'on') {$clientevendedor = 1;} else {$clientevendedor = 0;}
        if ($this->input->post('chk_codigocliente') == 'on') {$codigocliente = 1;} else {$codigocliente = 0;}

        $cuotaclientevendedor = $this->input->post("txt_cuotaclientevendedor");
        $cuotaclientevendedor = $cuotaclientevendedor * 1;

        $retencion = $this->input->post("txt_contretencion");
        $retencion = $retencion * 1;

        if ($this->input->post('chk_serviciotecnico') == 'on') {$serviciotecnico = 1;} else {$serviciotecnico = 0;}
        if ($this->input->post('chk_servicioserie') == 'on') {$servicioserie = 1;} else {$servicioserie = 0;}
        if ($this->input->post('chk_serviciodetalle') == 'on') {$serviciodetalle = 1;} else {$serviciodetalle = 0;}
        if ($this->input->post('chk_servicioencargado') == 'on') {$servicioencargado = 1;} else {$servicioencargado = 0;}
        
        if ($this->input->post('chk_servicioprodutilizado') == 'on') {$servicioprodutilizado = 1;} else {$servicioprodutilizado = 0;}
        if ($this->input->post('chk_servicioabono') == 'on') {$servicioabono = 1;} else {$servicioabono = 0;}
        if ($this->input->post('chk_productofactura') == 'on') {$productofactura = 1;} else {$productofactura = 0;}
        
        $pro_servicio = $this->input->post("pro_servicio");
        $pro_servicio = $pro_servicio * 1;

        if ($this->input->post('chk_facturaprecioconiva') == 'on') {$facturaprecioconiva = 1;} else {$facturaprecioconiva = 0;}
        if ($this->input->post('chk_impresionlocal') == 'on') {$impresionlocal = 1;} else {$impresionlocal = 0;}

        if ($this->input->post('chk_variante') == 'on') {$habilitavariante = 1;} else {$habilitavariante = 0;}
        if ($this->input->post('chk_ubicacionventa') == 'on') {$ubicacionventa = 1;} else {$ubicacionventa = 0;}
        if ($this->input->post('chk_detalletotalivaventa') == 'on') {$detalletotalivaventa = 1;} else {$detalletotalivaventa = 0;}
        if ($this->input->post('chk_impresiongrafica') == 'on') {$impresiongrafica = 1;} else {$impresiongrafica = 0;}
        if ($this->input->post('chk_impresionsubsidio') == 'on') {$impresionsubsidio = 1;} else {$impresionsubsidio = 0;}
        if ($this->input->post('chk_pedidopromo') == 'on') {$pedidopromo = 1;} else {$pedidopromo = 0;}

        $decimalesprecio = $this->input->post("txt_decimalesprecio");
        $decimalescantidad = $this->input->post("txt_decimalescantidad");

        if ($this->input->post('chk_notaventaiva') == 'on') {$habilitanotaventaiva = 1;} else {$habilitanotaventaiva = 0;}
        if ($this->input->post('chk_correoautosri') == 'on') {$habilitacorreoautosri = 1;} else {$habilitacorreoautosri = 0;}
        if ($this->input->post('chk_cambioprecio') == 'on') {$habilitacambioprecio = 1;} else {$habilitacambioprecio = 0;}
        if ($this->input->post('chk_ventapagoefectivo') == 'on') {$ventapagoefectivo = 1;} else {$ventapagoefectivo = 0;}
        if ($this->input->post('chk_clientecategoria') == 'on') {$habilitaclientecategoria = 1;} else {$habilitaclientecategoria = 0;}
        
        if ($this->input->post('chk_petshop') == 'on') {$habilitapetshop = 1;} else {$habilitapetshop = 0;}

        $tipodescuentoproducto = $this->input->post('tipodescuentoproducto');

        $sis_colorheader = $this->input->post('sis_colorheader');

        /* SE ACTUALIZA EL REGISTRO DEL USUARIO */
        $resu = $this->Parametros_model->iva_upd($iva);
        $resu = $this->Parametros_model->impresoraprecuenta_upd($impprecuenta);
        $resu = $this->Parametros_model->impresorafactura_upd($impfactura);
        /*
        $resu = $this->Parametros_model->upd_codigoestab($codestab);
        $resu = $this->Parametros_model->upd_codigopuntoemision($codptoemi);
        */
        $resu = $this->Parametros_model->upd_pedidovista($pedidovista);
        $resu = $this->Parametros_model->upd_pedidocliente($pedidocliente);
        $resu = $this->Parametros_model->upd_pedidomesero($pedidomesero);
        $resu = $this->Parametros_model->upd_tipoprecio($tipoprecio);
        $resu = $this->Parametros_model->upd_facturasinexistencia($facturasinexistencia);
        /*$resu = $this->Parametros_model->upd_comprobpago($comprobpago);*/
        $resu = $this->Parametros_model->upd_facturapdf($facturapdf);
        $resu = $this->Parametros_model->upd_limiteprodventa($limiteprodventa);
        $resu = $this->Parametros_model->upd_impuestoadicional($impuestoadicdescrip,$impuestoadicvalor);
        /*$resu = $this->Parametros_model->upd_retencion($retencion);*/
        $resu = $this->Parametros_model->upd_comandafactura($imprimircomandafactura);
        $resu = $this->Parametros_model->upd_habilitaorden($habilitanumeroorden);
        $this->Serviciotecnico_model->upd_configservicio($serviciotecnico, $servicioserie, $serviciodetalle, $servicioencargado, 
                                                         $servicioprodutilizado, $pro_servicio, $servicioabono, $productofactura);

        $resu = $this->Parametros_model->upd_numeroserie($numeroserie);

        $resu = $this->Parametros_model->upd_facturaprecioconiva($facturaprecioconiva);

        $resu = $this->Parametros_model->upd_clientevendedor($clientevendedor);
        $resu = $this->Parametros_model->upd_cuotaclientevendedor($cuotaclientevendedor);
        $resu = $this->Parametros_model->upd_codigocliente($codigocliente);
        $resu = $this->Parametros_model->upd_descpro($descpro);
        $resu = $this->Parametros_model->upd_impresionlocal($impresionlocal);
       
        $resu = $this->Parametros_model->upd_habilitavariante($habilitavariante);
        $resu = $this->Parametros_model->upd_ubicacionventa($ubicacionventa);
        $resu = $this->Parametros_model->upd_detalletotalivaventa($detalletotalivaventa);
        $resu = $this->Parametros_model->upd_impresiongrafica($impresiongrafica);
        $resu = $this->Parametros_model->upd_impresionsubsidio($impresionsubsidio);
        $resu = $this->Parametros_model->upd_pedidopromo($pedidopromo);

        $resu = $this->Parametros_model->upd_decimalesprecio($decimalesprecio);
        $resu = $this->Parametros_model->upd_decimalescantidad($decimalescantidad);

        $this->Parametros_model->upd_habilitanotaventaiva($habilitanotaventaiva); 
        $this->Parametros_model->upd_habilitacorreoautosri($habilitacorreoautosri);
        $this->Parametros_model->upd_habilitacambioprecio($habilitacambioprecio);
        $this->Parametros_model->upd_ventapagoefectivo($ventapagoefectivo);
        $this->Parametros_model->upd_habilitaclientecategoria($habilitaclientecategoria);

        $this->Parametros_model->upd_habilitapetshop($habilitapetshop);

        $this->Parametros_model->upd_tipodescuentoproducto($tipodescuentoproducto);

        if (isset($_POST['imagenfondo']) && $_POST['imagenfondo'] == ''){
            $imgfondo = '';
        }
        else{
            $logo_name= $_FILES["imagenfondo"]["name"];

            /* ESTE CONDICIONAL NOS PERMITE GUARDAR O MODIFICAR USUARIOS SIN QUE LE ASIGNEN logo */
            if ($logo_name == NULL || $logo_name == ""){
                $imgfondo = '';
            } else { 
                $logo_size= $_FILES["imagenfondo"]["size"];
                $logo_type= $_FILES["imagenfondo"]["type"];
                $logo_temporal= $_FILES["imagenfondo"]["tmp_name"];     

                /*$ext = pathinfo($logo_name, PATHINFO_EXTENSION);      */

                $split_logo = pathinfo($logo_name);
                $split_temporal = pathinfo($logo_temporal);

                $imgfondo = $split_temporal['filename'].".".$split_logo['extension'];
                $file_name = FCPATH.'/public/img/'.$imgfondo;

                $f1= fopen($logo_temporal,"rb");
                # Leemos el fichero completo limitando la lectura al tamaño del fichero
                $logo_reconvertida = fread($f1, $logo_size);
                fclose($f1);

                $file = fopen($file_name , 'w') or die("X_x");
                fwrite($file, $logo_reconvertida);
                fclose($file);
            }        
        }    
        $this->Sistema_model->upd_sistema($imgfondo, $sis_colorheader);

        $ptoventasingular = $this->input->post("txt_ptoventasingular");
        if (trim($ptoventasingular) == '') { $ptoventasingular = 'Mesa';}
        $this->Parametros_model->upd_ptoventasingular($ptoventasingular);
        $ptoventaplural = $this->input->post("txt_ptoventaplural");
        if (trim($ptoventaplural) == '') { $ptoventaplural = 'Mesas';}
        $this->Parametros_model->upd_ptoventaplural($ptoventaplural);

        $estadoptoventafacturar = $this->input->post("lst_estadoptoventafacturar");
        $this->Parametros_model->upd_estadoptoventafacturar($estadoptoventafacturar);

        redirect('','refresh');              

      /*  $data["base_url"] = base_url();
        $data["content"] = "inicio";
        $this->load->view("layout", $data);
*/
    }

    public function testexcel1(){
        //load our new PHPExcel library
        $this->load->library('excel');
        // Set document properties
        $this->excel->getProperties()->setCreator("Maarten Balliauw")
                                     ->setLastModifiedBy("Maarten Balliauw")
                                     ->setTitle("Office 2007 XLSX Test Document")
                                     ->setSubject("Office 2007 XLSX Test Document")
                                     ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                     ->setKeywords("office 2007 openxml php")
                                     ->setCategory("Test result file");


        // Add some data
        $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Hello')
                    ->setCellValue('B2', 'world!')
                    ->setCellValue('C1', 'Hello')
                    ->setCellValue('D2', 'world!');

        // Miscellaneous glyphs, UTF-8
        $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'Miscellaneous glyphs')
                    ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

        // Rename worksheet
        $this->excel->getActiveSheet()->setTitle('Simple');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->excel->setActiveSheetIndex(0);


        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="01simple.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function testexcel(){
        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('test worksheet');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'This is just some text value');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
         
        $filename='pepepe.xlsx'; //save our workbook as this file name
        //header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        //$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');        
    }

   public function enviar(){
      /*
       * Cuando cargamos una librería
       * es similar a hacer en PHP puro esto:
       * require_once("libreria.php");
       * $lib=new Libreria();
       */
        
       //Cargamos la librería email
       $this->load->library('email');
        
       /*
        * Configuramos los parámetros para enviar el email,
        * las siguientes configuraciones es recomendable
        * hacerlas en el fichero email.php dentro del directorio config,
        * en este caso para hacer un ejemplo rápido lo hacemos 
        * en el propio controlador
        */
        
       //Indicamos el protocolo a utilizar
        $config['protocol'] = 'smtp';
         
       //El servidor de correo que utilizaremos
        $config["smtp_host"] = 'smtp.gmail.com';//'smtp.googlemail.com';
         
       //Nuestro usuario
        $config["smtp_user"] = 'gesaecusoftsistema@gmail.com';
         
       //Nuestra contraseña
        $config["smtp_pass"] = 'ecusoft123';    
         
       //El puerto que utilizará el servidor smtp
        $config["smtp_port"] = '465';//'587';

        //$config["smtp_crypto"] = 'tls';
        $config["smtp_crypto"] = 'ssl';//'587';
        
       //El juego de caracteres a utilizar
        $config['charset'] = 'utf-8';
 
       //Permitimos que se puedan cortar palabras
        $config['wordwrap'] = TRUE;
         
       //El email debe ser valido  
       $config['validate'] = true;
       
        
      //Establecemos esta configuración
        $this->email->initialize($config);
 
      //Ponemos la dirección de correo que enviará el email y un nombre
        $this->email->from('gesaecusoftsistema@gmail.com', 'GESA');
       

      /*
       * Ponemos el o los destinatarios para los que va el email
       * en este caso al ser un formulario de contacto te lo enviarás a ti
       * mismo
       */
        $this->email->to('paveloramas@gmail.com', 'Pavel Oramas');
         
      //Definimos el asunto del mensaje
        $this->email->subject("asunto");
         
      //Definimos el mensaje a enviar
        $this->email->message(
                "Email: testing".
                " Mensaje: wuaooooooo"
                );
         
        //Enviamos el email y si se produce bien o mal que avise con una flasdata
        if($this->email->send()){
            echo $this->email->print_debugger(array('headers'));
            //$this->session->set_flashdata('envio', 'Email enviado correctamente');
        }else{
            echo "error: ".$this->email->print_debugger(array('headers'));
            //$this->session->set_flashdata('envio', 'No se a enviado el email');
        }
        
       // redirect(base_url("contacto"));
   }    

}

?>