<?php
/*------------------------------------------------
  ARCHIVO: Credito.php
  DESCRIPCION: Contiene los métodos relacionados con Credito.
  FECHA DE CREACIÓN: 15/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Credito extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Credito_model");
        $this->load->Model("Cliente_model");
        $this->load->Model("Facturar_model");
        $this->load->Model("Sucursal_model");
        $this->load->Model("Empresa_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        /* Listado de las areas junto con las mesas */
        $clientes = $this->Cliente_model->sel_cli();
        $estados = $this->Credito_model->lst_estadocredito();
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $clienteseleccionado = $this->session->userdata("tmp_creditocliente");
        $estadoseleccionado = $this->session->userdata("tmp_creditoestado");
        $sucursalseleccionada = $this->session->userdata("tmp_creditosucursal");
        $rangoseleccionado = $this->session->userdata("tmp_creditorango");
        if ($sucursalseleccionada == '') {
          if (count($sucursales) > 0){
            $sucursalseleccionada = $sucursales[0]->id_sucursal;
          }  
          if ($sucursalseleccionada == '') {$sucursalseleccionada = 0;}

          if ($clienteseleccionado == '') {$clienteseleccionado = 0;}
          if ($estadoseleccionado == '') $estadoseleccionado = 0;
          $rangoseleccionado = 0;
        }
        $this->session->set_userdata("tmp_creditosucursal", $sucursalseleccionada);
        $this->session->set_userdata("tmp_creditocliente", $clienteseleccionado);
        $this->session->set_userdata("tmp_creditoestado", $estadoseleccionado);
        $this->session->set_userdata("tmp_creditorango", $rangoseleccionado);

        $fechamin = $this->Credito_model->sel_creditos_fechamin($sucursalseleccionada, $clienteseleccionado, $estadoseleccionado);
        if ($fechamin != NULL){
          $fecha = $fechamin[0]->fecha;
          $fecha = str_replace('-', '/', $fecha); 
          $fecha = date("d/m/Y", strtotime($fecha));          }
        else{
          $fecha = date("d/m/Y");
        }
        $arr['fechamin'] = addslashes($fecha);

        $data["clientes"] = $clientes;
        $data["estados"] = $estados;
        $data["sucursales"] = $sucursales;
        $data["clienteseleccionado"] = $clienteseleccionado;
        $data["estadoseleccionado"] = $estadoseleccionado;
        $data["sucursalseleccionada"] = $sucursalseleccionada;
        $data["base_url"] = base_url();
        $data["content"] = "credito";
        $this->load->view("layout", $data);
    }


    /* SESION TEMPORAL PARA Filtro de Credito */
     public function tmp_filtrocredito() {
        $idcliente = $this->session->userdata("tmp_creditocliente");
        if ($idcliente != NULL) { $this->session->set_userdata("tmp_creditocliente", $idcliente); } 
        else { $this->session->set_userdata("tmp_creditocliente", NULL); }

        $idestado = $this->session->userdata("tmp_creditoestado");
        if ($idestado != NULL) { $this->session->set_userdata("tmp_creditoestado", $idestado); } 
        else { $this->session->set_userdata("tmp_creditoestado", 1); }

        $sucursal = $this->session->userdata("tmp_creditosucursal");
        if ($sucursal != NULL) { $this->session->set_userdata("tmp_creditosucursal", $sucursal); } 
        else { $this->session->set_userdata("tmp_creditosucursal", 1); }
    }

    /* SESION TEMPORAL PARA Filtro de Credito */
     public function upd_filtrocredito() {
        $idcliente = $this->input->post("cliente");
        if ($idcliente != NULL) { $this->session->set_userdata("tmp_creditocliente", $idcliente); } 
        else { $this->session->set_userdata("tmp_creditocliente", NULL); }
        $idcliente = $this->session->userdata("tmp_creditocliente");

        $idestado = $this->input->post("estado");
        if ($idestado != NULL) { $this->session->set_userdata("tmp_creditoestado", $idestado); } 
        else { $this->session->set_userdata("tmp_creditoestado", NULL); }
        $idestado = $this->session->userdata("tmp_creditoestado");

        $sucursal = $this->input->post("sucursal");
        if ($sucursal != NULL) { $this->session->set_userdata("tmp_creditosucursal", $sucursal); } 
        else { $this->session->set_userdata("tmp_creditosucursal", NULL); }
        $sucursal = $this->session->userdata("tmp_creditosucursal");

        $rango = $this->input->post("rango");
        if ($rango != NULL) { $this->session->set_userdata("tmp_creditorango", $rango); } 
        else { $this->session->set_userdata("tmp_creditorango", NULL); }
        $desde = $this->input->post("desde");
        if ($desde != NULL) { $this->session->set_userdata("tmp_creditodesde", $desde); } 
        else { $this->session->set_userdata("tmp_creditodesde", NULL); }
        $hasta = $this->input->post("hasta");
        if ($hasta != NULL) { $this->session->set_userdata("tmp_creditohasta", $hasta); } 
        else { $this->session->set_userdata("tmp_creditohasta", NULL); }

        $fechamin = $this->Credito_model->sel_creditos_fechamin($sucursal, $idcliente, $idestado);
        if ($fechamin != NULL){
          $fecha = $fechamin[0]->fecha;
          $fecha = str_replace('-', '/', $fecha); 
          $fecha = date("d/m/Y", strtotime($fecha));          
        }  
        else{
          $fecha = date("d/m/Y");
        }
        $arr['fechamin'] = addslashes($fecha);
        print json_encode($arr);               
    }

    public function listadoCreditos() {
        //$this->tmp_filtrocredito();

        $cliente = $this->session->userdata("tmp_creditocliente");
        $estado = $this->session->userdata("tmp_creditoestado");
        $sucursal = $this->session->userdata("tmp_creditosucursal");
        $rango = $this->session->userdata("tmp_creditorango");
        $desde = $this->session->userdata("tmp_creditodesde");
        $desde = str_replace('/', '-', $desde);   $desde = date("Y-m-d", strtotime($desde));          
        $hasta = $this->session->userdata("tmp_creditohasta");
        $hasta = str_replace('/', '-', $hasta);   $hasta = date("Y-m-d", strtotime($hasta));          
        if (($cliente == NULL) or ($cliente == '')) {$cliente = 0;}
        if (($estado == NULL) or ($estado == '')) {$estado = 0;}
        if (($sucursal == NULL) or ($sucursal == '')) {$sucursal = 0;}
        if (($rango == NULL) or ($rango == '')) {$rango = 0;}

        $registro = $this->Credito_model->lst_creditos($sucursal, $cliente, $estado, $rango, $desde, $hasta);

        $tabla = "";
        foreach ($registro as $row) {

          $fecha = str_replace('-', '/', $row->fecha); $fecha = date("d/m/Y", strtotime($fecha));          
          $fechalim = str_replace('-', '/', $row->fechalimite); $fechalim = date("d/m/Y", strtotime($fechalim));          

          $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Editar Abonos\" id=\"'.$row->id_venta.'\" class=\"btn btn-warning btn-xs btn-grad edit_abono\"><i class=\"fa fa-plus\"></i></a> <a href=\"#\" title=\"Imprimir Crédito\" id=\"'.$row->id_venta.'\" class=\"btn bg-navy color-palette btn-xs btn-grad cred_print\"><i class=\"fa fa-print\"></i></a>  <a href=\"#\" title=\"Imprimir Abonos\" id=\"'.$row->id_venta.'\" class=\"btn bg-navy color-palette btn-xs btn-grad abono_print\"><i class=\"fa fa-credit-card\"></i></a>  </div>';
               
          $tabla.='{  "cliente":"' . $row->nom_cliente . '",
                      "cedula":"' . $row->ident_cliente . '",
                      "fecha":"' . $fecha . '",
                      "factura":"' . $row->nro_factura . '",
                      "montofactura":"' . $row->montototal . '",   
                      "fechalimite":"' . $fechalim . '",                                                               
                      "dias":"' . $row->dias . '",                                                               
                      "estado":"' . $row->nombre_estado . '",                                                               
                      "id_estado":"' . $row->id_estado . '",                                                               
                      "vencido":"' . $row->vencido . '",                                                               
                      "abonoinicial":"' . $row->abonoinicial . '",                                                               
                      "montointerescredito":"' . $row->montointerescredito . '",                                                               
                      "montocredito":"' . $row->montocredito . '",                                                               
                      "retencion":"' . $row->retencion . '",                                                               
                      "montopendiente":"' . round($row->montocredito - $row->abonado - $row->retencion,2) . '",                                                               
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }  

 
    public function get_total_credito(){
        $cliente = $this->session->userdata("tmp_creditocliente");
        $estado = $this->session->userdata("tmp_creditoestado");
        if (($cliente == NULL) or ($cliente == '')) $cliente = 0;
        if (($estado == NULL) or ($estado == '')) $estado = 0;

        $sucursal = $this->session->userdata("tmp_creditosucursal");
        $rango = $this->session->userdata("tmp_creditorango");
        $desde = $this->session->userdata("tmp_creditodesde");
        $desde = str_replace('/', '-', $desde);   $desde = date("Y-m-d", strtotime($desde));          
        $hasta = $this->session->userdata("tmp_creditohasta");
        $hasta = str_replace('/', '-', $hasta);   $hasta = date("Y-m-d", strtotime($hasta));          
        if (($cliente == NULL) or ($cliente == '')) {$cliente = 0;}
        if (($estado == NULL) or ($estado == '')) {$estado = 0;}
        if (($sucursal == NULL) or ($sucursal == '')) {$sucursal = 0;}
        if (($rango == NULL) or ($rango == '')) {$rango = 0;}       

        $totalg = $this->Credito_model->total_creditos($sucursal, $cliente, $estado, $rango, $desde, $hasta);
        if ($totalg != NULL){
          $arr['total'] = $totalg[0]->total;
          $arr['pendiente'] = $totalg[0]->pendiente;

        } else{
          $arr['total'] = 0;
          $arr['pendiente'] = 0;
        }
        
        print json_encode($arr);               
    }

    public function reporte_credito(){
        $cliente = $this->session->userdata("tmp_creditocliente");
        $estado = $this->session->userdata("tmp_creditoestado");
        if (($cliente == NULL) or ($cliente == '')) $cliente = 0;
        if (($estado == NULL) or ($estado == '')) $estado = 0;

        $sucursal = $this->session->userdata("tmp_creditosucursal");
        $rango = $this->session->userdata("tmp_creditorango");
        $desde = $this->session->userdata("tmp_creditodesde");
        $desde = str_replace('/', '-', $desde);   $desde = date("Y-m-d", strtotime($desde));          
        $hasta = $this->session->userdata("tmp_creditohasta");
        $hasta = str_replace('/', '-', $hasta);   $hasta = date("Y-m-d", strtotime($hasta));          
        if (($cliente == NULL) or ($cliente == '')) {$cliente = 0;}
        if (($estado == NULL) or ($estado == '')) {$estado = 0;}
        if (($sucursal == NULL) or ($sucursal == '')) {$sucursal = 0;}
        if (($rango == NULL) or ($rango == '')) {$rango = 0;}

        $credito = $this->Credito_model->lst_creditos($sucursal, $cliente, $estado, $rango, $desde, $hasta);
        if ($cliente == 0) {$nomcliente = "Todos los clientes";}
        else {
          $obj = $this->Cliente_model->sel_cli_id($cliente);
          $nomcliente = "Cliente: " . $obj->nom_cliente;
        }
        if ($estado == 0) {$nomestado = "Todos los creditos";}
        else {
          $obj = $this->Credito_model->lst_estadocredito_id($estado);
          $nomestado = "Creditos en Estado: " . $obj->nombre_estado;
        }
        $data["base_url"] = base_url();
        $data["nomcliente"] = $nomcliente;
        $data["nomestado"] = $nomestado;
        $data["estado"] = $estado;
        $data["credito"] = $credito;
        $this->load->view("credito_reporte", $data);     
    }    

    /* Exportar Compra a Excel */
    public function reportecreditoXLS(){
        $cliente = $this->session->userdata("tmp_creditocliente");
        $estado = $this->session->userdata("tmp_creditoestado");
        if (($cliente == NULL) or ($cliente == '')) $cliente = 0;
        if (($estado == NULL) or ($estado == '')) $estado = 0;
    

        $sucursal = $this->session->userdata("tmp_creditosucursal");
        $rango = $this->session->userdata("tmp_creditorango");
        $desde = $this->session->userdata("tmp_creditodesde");
        $desde = str_replace('/', '-', $desde);   $desde = date("Y-m-d", strtotime($desde));          
        $hasta = $this->session->userdata("tmp_creditohasta");
        $hasta = str_replace('/', '-', $hasta);   $hasta = date("Y-m-d", strtotime($hasta));          
        if (($cliente == NULL) or ($cliente == '')) {$cliente = 0;}
        if (($estado == NULL) or ($estado == '')) {$estado = 0;}
        if (($sucursal == NULL) or ($sucursal == '')) {$sucursal = 0;}
        if (($rango == NULL) or ($rango == '')) {$rango = 0;}

        $credito = $this->Credito_model->lst_creditos($sucursal, $cliente, $estado, $rango, $desde, $hasta);

        if ($cliente == 0) {$nomcliente = "Todos los clientes";}
        else {
          $obj = $this->Cliente_model->sel_cli_id($cliente);
          $nomcliente = "Cliente: " . $obj->nom_cliente;
        }
        if ($estado == 0) {$nomestado = "Todos los creditos";}
        else {
          $obj = $this->Credito_model->lst_estadocredito_id($estado);
          $nomestado = "Creditos en Estado: " . $obj->nombre_estado;
        }
 
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteCredito');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Créditos (' . $nomcliente . '  -  ' . $nomestado . ')');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:J1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(30);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Cliente');
        $this->excel->getActiveSheet()->setCellValue('C3', 'C.I./R.U.C.');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Factura');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Fecha Plazo');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Dias Plazo');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Estado');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Monto Factura');
        $this->excel->getActiveSheet()->setCellValue('I3', 'Abono Inicial');
        $this->excel->getActiveSheet()->setCellValue('J3', 'Interés');
        $this->excel->getActiveSheet()->setCellValue('K3', 'Monto Crédito');
        $this->excel->getActiveSheet()->setCellValue('L3', 'Pendiente');
        $this->excel->getActiveSheet()->setCellValue('M3', 'Teléfonos');
        $this->excel->getActiveSheet()->setCellValue('N3', 'Correo');
        $this->excel->getActiveSheet()->setCellValue('O3', 'Dirección');
        

        $this->excel->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);

        $total = 0;
        $fila = 4;
        foreach ($credito as $det) {
          $total += $det->montocredito - $det->abonado;         

          $fec = str_replace('-', '/', $det->fecha); 
          $fec = date("d/m/Y", strtotime($fec));  
          $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
          $this->excel->getActiveSheet()->setCellValue('B'.$fila, addslashes($det->nom_cliente));
          $this->excel->getActiveSheet()->setCellValue('C'.$fila, $det->ident_cliente);
          $this->excel->getActiveSheet()->setCellValue('D'.$fila, $det->nro_factura);
          $this->excel->getActiveSheet()->setCellValue('E'.$fila, $det->fechalimite);
          $this->excel->getActiveSheet()->setCellValue('F'.$fila, $det->dias);
          $this->excel->getActiveSheet()->setCellValue('G'.$fila, $det->nombre_estado);
          $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($det->montototal,2));
          $this->excel->getActiveSheet()->setCellValue('I'.$fila, number_format($det->abonoinicial,2));
          $this->excel->getActiveSheet()->setCellValue('J'.$fila, number_format($det->montointerescredito,2));
          $this->excel->getActiveSheet()->setCellValue('K'.$fila, number_format($det->montocredito,2));
          $this->excel->getActiveSheet()->setCellValue('L'.$fila, number_format($det->montocredito - $det->abonado,2));
          $this->excel->getActiveSheet()->setCellValue('M'.$fila, addslashes($det->telefonos_cliente));
          $this->excel->getActiveSheet()->setCellValue('N'.$fila, addslashes($det->correo_cliente));
          $this->excel->getActiveSheet()->setCellValue('O'.$fila, addslashes($det->direccion_cliente));

          $fila++;          
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('I'.$fila, 'TOTAL PENDIENTE');
        $this->excel->getActiveSheet()->setCellValue('L'.$fila, number_format($total,2));
        $this->excel->getActiveSheet()->getStyle('I'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('L'.$fila)->getFont()->setBold(true);

         
        $filename='reportecompra.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        $objWriter->save('php://output');        
    }  

    public function vista_abonocredito() {
        $data["base_url"] = base_url();
        $idfact = $this->session->userdata("tmp_credito_id");
        $registro = $this->Credito_model->sel_credito_id($idfact);
        $data["objfact"] = $registro;

        $objsuc = $this->Sucursal_model->sel_suc_id($registro->id_sucursal);
        $contabilizar = $objsuc->contabilizacion_automatica; 
        $data['contabilizar'] = $contabilizar;

        $data["content"] = "credito_abonolista";
        $this->load->view("layout", $data);
    }

    /* SESION TEMPORAL PARA id de Credito */
     public function tmp_creditoid() {
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_credito_id", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_credito_id", $id); } 
        $id = $this->session->userdata("tmp_credito_id");

        $arr['resu'] = 1;
        print json_encode($arr);               
    }

   /* CARGA DE DATOS AL DATATABLE de Abonos*/ 
    public function listadoDataAbono() {
        $idfact = $this->session->userdata("tmp_credito_id");
        $registro = $this->Credito_model->lista_abonos($idfact);

        $tabla = "";
        foreach ($registro as $row) {
          if ($row->abonoinicial == null){
            @$fec = str_replace('-', '/', $row->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); 
            
            $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Editar Abono\" id=\"'.$row->id.'\" name=\"'.$row->id_formapago.'\" class=\"btn bg-navy color-palette btn-xs btn-grad abono_edit\"><i class=\"fa fa-pencil-square-o fa-lg\"></i></a> <a href=\"#\" title=\"Imprimir Abono\" id=\"'.$row->id.'\" class=\"btn bg-navy color-palette btn-xs btn-grad compab_print\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id.'\" name=\"'.$row->numero.'\" class=\"btn btn-danger btn-xs btn-grad del_abono\"><i class=\"fa fa-trash-o\"></i></a> </div>';
            $tabla.='{"id":"' . $row->id . '",
                      "fecha":"' . $fec . '",
                      "formapago":"' . $row->nombre_formapago . '",
                      "monto":"' . $row->monto . '",
                      "ver":"'.$ver.'"},';
          }            
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

   /* CARGA DE DATOS AL DATATABLE de Cuotas*/ 
    public function listadoDataCuota() {
        $idfact = $this->session->userdata("tmp_credito_id");
        $registro = $this->Credito_model->lista_cuotas($idfact);

        $tabla = "";
        foreach ($registro as $row) {
            @$fec = str_replace('-', '/', $row->fechalimite); @$fec = date("d/m/Y", strtotime(@$fec)); 
            
            /*$ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Imprimir Abono\" id=\"'.$row->id.'\" class=\"btn bg-navy color-palette btn-xs btn-grad compab_print\"><i class=\"fa fa-print\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id.'\" class=\"btn btn-danger btn-xs btn-grad del_abono\"><i class=\"fa fa-trash-o\"></i></a> </div>';*/
            $tabla.='{"fecha":"' . $fec . '",
                      "monto":"' . $row->monto . '",
                      "pagado":"' . $row->pagado . '"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    /* SESION TEMPORAL PARA id de Abono de Credito */
     public function tmp_abonocreditoid() {
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_abonocredito_id", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_abonocredito_id", $id); } 
        $id = $this->session->userdata("tmp_abonocredito_id");

        $arr['resu'] = 1;
        print json_encode($arr);               
    }

    public function del_abonocredito() {
        $idbono = $this->session->userdata("tmp_abonocredito_id");
        $idfact = $this->session->userdata("tmp_credito_id");
        $this->Credito_model->del_abonocredito($idbono,$idfact);
        $registro = $this->Credito_model->sel_credito_id($idfact);
        $arr['resu'] = $registro->montocredito - $registro->abonos - $registro->retencion + $registro->abonoinicial;
        print json_encode($arr);               
    }

    /* ABRE LA VENTANA PARA SELECCIONAR EL TIPO DE PAGO Y EL MONTO */
    public function add_abonocredito(){
      $formapago = "Credito";
      /* Cargar Listado de Formas de Pago */
      $forpago = $this->Facturar_model->lista_formapago(0);
      /* Cargar Listado de Bancos */
      $banco = $this->Facturar_model->bancos();
      /* Cargar Listado de Tarjetas */
      $tarjeta = $this->Facturar_model->tarjetas();

      $idusu = $this->session->userdata("sess_id");
      $lstcaja = $this->Facturar_model->lst_caja($idusu);
      
      $maxvalor = $this->input->post("montopendiente");
      $data["maxvalor"] = $maxvalor;

      $data["formapago"] = $formapago;
      $data["forpago"] = $forpago;
      $data["bancos"] = $banco;
      $data["tarjetas"] = $tarjeta;
      $data["lstcaja"] = $lstcaja;
      $data["base_url"] = base_url();
      $this->load->view("facturar_tipopago", $data);
    }

    public function edit_abonocredito(){
      $idreg = $this->input->post("idreg");
      $idfp = $this->input->post("idfp");
      $idventa = $this->input->post("idventa");
      $edifp = $this->Credito_model->ediforpagovent($idreg, $idfp, $idventa);  
      $idforpago = $edifp->id_formapago;
      $tipofp = $this->Facturar_model->selforpago($idforpago); 
      $formapago = "Credito";
      $forpago = $this->Facturar_model->lista_formapago(0);
      $banco = $this->Facturar_model->bancos();
      $tarjeta = $this->Facturar_model->tarjetas();

      $idusu = $this->session->userdata("sess_id");
      $lstcaja = $this->Facturar_model->lst_caja($idusu);

      $maxvalor = $this->input->post("montopendiente");
      $data["maxvalor"] = $maxvalor + $edifp->monto;

      $data["edifp"] = $edifp;
      $data["tipofp"] = $tipofp;
      $data["formapago"] = $formapago;
      $data["forpago"] = $forpago;
      $data["bancos"] = $banco;
      $data["tarjetas"] = $tarjeta;
      $data["lstcaja"] = $lstcaja;
      $data["base_url"] = base_url();
      $this->load->view("facturar_tipopago", $data);
    }

    public function guardar_abonocredito(){
      $idreg = $this->input->post("idreg");
      $idventa = $this->input->post("idventa");      
      $fp = $this->input->post("fp");
      $monto = $this->input->post("monto");
      $fechat = $this->input->post("fechat");
      $tiptarjeta = $this->input->post("tiptarjeta");
      $nrotar = $this->input->post("nrotar");
      $banco = $this->input->post("bco");
      $tbanco = $this->input->post("tbco");
      $nrodoc = $this->input->post("nrodoc");
      $tnrodoc = $this->input->post("tnrodoc");
      $descdoc = $this->input->post("descdoc");
      $tdescdoc = $this->input->post("tdescdoc");
      $fechae = $this->input->post("fechae");
      $fechac = $this->input->post("fechac");
      $nrocta = $this->input->post("nrocta");
      $idcaja = $this->input->post("idcaja");      
      
      $nuevo = ($idreg == 0);
      if($idreg == 0){
        $idreg = $this->Credito_model->add_abonocredito($idventa, $fp, $monto, $fechat, $tiptarjeta, $nrotar, $banco, $tbanco, $nrodoc, $tnrodoc, $descdoc, $tdescdoc, $fechae, $fechac, $nrocta, $idcaja);
      }else{
        $updfp = $this->Credito_model->upd_abonocredito($idreg, $idventa, $fp, $monto, $fechat, $tiptarjeta, $nrotar, $banco, $tbanco, $nrodoc, $tnrodoc, $descdoc, $tdescdoc, $fechae, $fechac, $nrocta, $idcaja);
      }
      /*$selfp = $this->facturar_model->sumforpago($idventa, $fpvtc);*/
/*
      $objfac = $this->Facturar_model->datosfactura($idventa);
      $objsuc = $this->Sucursal_model->sel_suc_id($objfac[0]->id_sucursal);
      $contabilizar = $objsuc->contabilizacion_automatica; 
      $arr['contabilizar'] = $contabilizar;
*/
      $arr['nuevo'] = $nuevo;
      $arr['idreg'] = $idreg;

      $registro = $this->Credito_model->sel_credito_id($idventa);
      $arr['resu'] = round($registro->montocredito - $registro->abonos - $registro->retencion + $registro->abonoinicial,2);
      print json_encode($arr);               
    }

    /* ABRIR VENTANA PARA Imprimir credito */
    public function imprimircredito(){
        /*$factoriva = $this->Parametros_model->iva_get() * 100;*/
        date_default_timezone_set("America/Guayaquil");
        
        $idfactura = $this->input->post('id');
        $row = $this->Credito_model->sel_credito_id($idfactura);
        $emp = $this->Empresa_model->emp_get();
        $tabla = chr(32). chr(32) . "\r\n";
        $tabla.= chr(32). chr(32) . "\r\n";

            $ff = "FACTURA DE VENTA A CREDITO";
            $tabla .=$ff;
            $tabla.="\r\n";
            $tabla.=$emp->nom_emp . "\r\n";
            $tabla.="RUC:" . chr(32) . $emp->ruc_emp . "\r\n";
            $tabla.=$emp->dir_emp . "\r\n";
            if (trim($row->nro_factura) != ''){
              $tabla.="Nro. Docum.:" . chr(32) . $row->nro_factura . "\r\n";  
            }
            //$strdate=//date("d/m/Y H:i");
            $strdate = str_replace('-', '/', $row->fecha); 
            $strdate = date("d/m/Y", strtotime($strdate)); 
            $tabla.="Fecha Emision:" . chr(32) . $strdate . "\r\n";
            if (trim($row->mesa) != ''){
              $tabla.="Punto:" . chr(32) . $row->mesa . "\r\n";  
            }
            if (trim($row->mesero) != ''){
              $tabla.="Vendedor:" . chr(32) . $row->mesero . "\r\n";  
            }
            $tabla.="Cliente:" . chr(32) . $row->nom_cliente . "\r\n";        
            $tabla.="Direccion:". chr(32) . $row->dir_cliente . "\r\n";        
            $tabla.="CI/RUC:". chr(32) . $row->nro_ident . "\r\n";        
            $tabla.="Telef.:". chr(32) . $row->telf_cliente . "\r\n";        

            $strdate = str_replace('-', '/', $row->fechalimite); 
            $strdate = date("d/m/Y", strtotime($strdate)); 
            $tabla.="Fecha Limite de Pago:" . chr(32) . $strdate . "\r\n";
            $tabla.="Total Factura:" . chr(32) . $row->montototal . "\r\n";
            $tabla.="Abono Inicial:" . chr(32) . $row->abonoinicial . "\r\n";
            $tabla.="Monto Interes:" . chr(32) . $row->montointerescredito . "\r\n";
            $tabla.="Monto Credito:" . chr(32) . $row->montocredito . "\r\n";
            $strpendiente ="Valor Pendiente:" . chr(32) . ($row->montocredito - $row->abonos + $row->abonoinicial) . "\r\n";

        $tabla.= "\r\n";
        $tabla.="Distribucion de Cuotas". "\r\n";        
        $tabla.="Fecha          Monto          Pagado". "\r\n";        
        $registro = $this->Credito_model->lista_cuotas($idfactura);
        foreach ($registro as $row) {
            $strdate = str_replace('-', '/', $row->fechalimite); 
            $strdate = date("d/m/Y", strtotime($strdate)); 
            $tabla.= $strdate;
            $lcant = strlen($strdate);
            while ($lcant < 15){
                $tabla.= chr(32);
                $lcant++;
            }
            $strcant = number_format($row->monto,2);
            $tabla.= $strcant;
            $lcant = strlen($strcant);
            while ($lcant < 15){
                $tabla.= chr(32);
                $lcant++;
            }
            $strprecio=number_format($row->pagado,2);
            $tabla.= $strprecio;

            $tabla.= "\r\n";
        }
        for ($i=0; $i<2; $i++) {
            $tabla.= "  " . "\n";                        
        }        
        $tabla.= $strpendiente . "\r\n";

        for ($i=0; $i<2; $i++) {
            $tabla.= "  " . "\n";                        
        }        
        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("credito_imprimir", $data);

    }

    /* ABRIR VENTANA PARA Imprimir abono de credito */
    public function imprimirabonocredito(){
        /*$factoriva = $this->Parametros_model->iva_get() * 100;*/
        date_default_timezone_set("America/Guayaquil");
        
        $idfactura = $this->input->post('idfac');
        $idreg = $this->input->post('idreg');
        $row = $this->Credito_model->sel_credito_id($idfactura);
        $abono = $this->Credito_model->ediforpagovent($idreg);
        $emp = $this->Empresa_model->emp_get();
        $tabla = chr(32). chr(32) . "\r\n";
        $tabla.= chr(32). chr(32) . "\r\n";

        $ff = "COMPROBANTE DE PAGO";
        $tabla .=$ff;
        $tabla.="\r\n";
        $tabla.=$emp->nom_emp . "\r\n";
        $tabla.="RUC:" . chr(32) . $emp->ruc_emp . "\r\n";
        $tabla.=$emp->dir_emp . "\r\n". "\r\n";
        if (trim($abono->nro_comprobante) != ''){
          $tabla.="Comprobante Pago:" . chr(32) . $abono->nro_comprobante . "\r\n";  
          $strdate = str_replace('-', '/', $abono->fecha); 
          $strdate = date("d/m/Y", strtotime($strdate)); 
          $tabla.="Fecha:" . chr(32) . $strdate . "\r\n";
          $tabla.="Monto:" . chr(32) . $abono->monto . "\r\n". "\r\n";
        }
        if (trim($row->nro_factura) != ''){
          $tabla.="Factura:" . chr(32) . $row->nro_factura . "\r\n";  
        }
        $strdate = str_replace('-', '/', $row->fecha); 
        $strdate = date("d/m/Y", strtotime($strdate)); 
        $tabla.="Fecha Emision:" . chr(32) . $strdate . "\r\n";
        $tabla.="Cliente:" . chr(32) . $row->nom_cliente . "\r\n";        
        $tabla.="Direccion:". chr(32) . $row->dir_cliente . "\r\n";        
        $tabla.="CI/RUC:". chr(32) . $row->nro_ident . "\r\n";        
        $tabla.="Telef.:". chr(32) . $row->telf_cliente . "\r\n";        

        $strdate = str_replace('-', '/', $row->fechalimite); 
        $strdate = date("d/m/Y", strtotime($strdate)); 
        $tabla.="Fecha Limite de Pago:" . chr(32) . $strdate . "\r\n";
        $tabla.="Total Factura:" . chr(32) . $row->montototal . "\r\n";
        $tabla.="Abono Inicial:" . chr(32) . $row->abonoinicial . "\r\n";
        $tabla.="Monto Interes:" . chr(32) . $row->montointerescredito . "\r\n";
        $tabla.="Monto Credito:" . chr(32) . $row->montocredito . "\r\n". "\r\n";

        $tabla.= "Valor Pendiente:" . chr(32) . ($row->montocredito - $row->abonos + $row->abonoinicial) . "\r\n";

        for ($i=0; $i<4; $i++) {
            $tabla.= "  " . "\n";                        
        }        

        $tabla.= "----------------". "\r\n";
        $tabla.= "     Firma". "\r\n";

        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("credito_abonoimprimir", $data);
    }

     public function nroabono_tmp() {
        $this->session->unset_userdata("idabono_tmp"); 
        $idfactura = $this->input->post('idfac');
        $idreg = $this->input->post('idreg');
        $nroabono["idfactura"] = $idfactura;
        $nroabono["idreg"] = $idreg;
        $this->session->set_userdata("idabono_tmp", NULL);
        if ($nroabono != NULL) { $this->session->set_userdata("idabono_tmp", $nroabono); } 
        else { $this->session->set_userdata("idabono_tmp", NULL); }
        $idreg = $this->session->userdata("idabono_tmp");
        $arr['resu'] = $idreg;
        print json_encode($arr);
    }

    private function pagina_v() {
      $this->pdf_ra->SetMargins('12', '7', '10');   #Margenes
      $this->pdf_ra->AddPage('P', 'A4');        #Orientación y tamaño 
    }    

    public function abonopdf(){

        $regabono = $this->session->userdata("idabono_tmp");
        $idfactura = $regabono['idfactura'];
        $idreg = $regabono['idreg'];        
        $encrec = $this->Credito_model->sel_credito_id($idfactura);
        $abono = $this->Credito_model->ediforpagovent($idreg);
        $sucursal = $this->Sucursal_model->sel_suc_id($encrec->id_sucursal);      
        $emp = $this->Empresa_model->emp_get();
        $params['encrec'] = $encrec;
        $params['abono'] = $abono;
        $params['sucursal'] = $sucursal;
        $recb = $this->Credito_model->reciboabono($idreg);
        /* ENCABEZADO DEL PDF */
        $this->load->library('pdf_ra', $params);
        $this->pdf_ra->fontpath = 'font/'; 
        $this->pdf_ra->AliasNbPages();
        $this->pagina_v();
        $this->pdf_ra->SetFillColor(139, 35, 35);
        $this->pdf_ra->SetFont('Arial','B',10);
        $this->pdf_ra->SetTextColor(0,0,0);
        /* TITULO DE DETALLES */
        $this->pdf_ra->Cell(185,8,utf8_decode("Pago de Cuotas"),1,1,'C');
        $this->pdf_ra->ln(5);
        $this->pdf_ra->Cell(5,8,utf8_decode("Nro"),0,0,'C');
        $this->pdf_ra->Cell(41,8,utf8_decode("Fecha"),0,0,'C');
        $this->pdf_ra->Cell(46,8,utf8_decode("Monto Total"),0,0,'R');
        $this->pdf_ra->Cell(46,8,'Monto Pago',0,0,'R');
        $this->pdf_ra->Cell(46,8,'Pediente',0,1,'R');
        $nro = 0;
        foreach ($recb as $row) {
          $nro = $nro + 1;
          $strdate = str_replace('-', '/', $row->fechalimite); 
          $fechalimite = date("d/m/Y", strtotime($strdate)); 
          $montototal = number_format($row->montototal,2);
          $montopago=number_format($row->montopago,2);
          $pendiente=number_format($row->pendiente,2);

          $this->pdf_ra->SetFont('Arial','',9);  
          $this->pdf_ra->Cell(5,5,$nro,0,0,'C');      
          $this->pdf_ra->Cell(41,5,utf8_decode("$fechalimite"),0,0,'C');
          $this->pdf_ra->Cell(46,5,utf8_decode("$montototal"),0,0,'R');
          $this->pdf_ra->Cell(46,5,'$'.$montopago,0,0,'R');
          $this->pdf_ra->Cell(46,5,'$'.$pendiente,0,1,'R'); 
        }

        $this->pdf_ra->ln(20); 
        $this->pdf_ra->Cell(50,0,'',1,0,'L');
        $this->pdf_ra->Cell(85,0,'',0,0,'L');
        $this->pdf_ra->Cell(50,0,'',1,1,'L'); 

        $this->pdf_ra->Cell(50,5,utf8_decode("Cajero: $encrec->cajero"),0,0,'L');
        $this->pdf_ra->Cell(85,0,'',0,0,'R');        
        $this->pdf_ra->Cell(50,5,utf8_decode("Cliente: $encrec->nom_cliente"),0,1,'R'); 

        $this->pdf_ra->Output('Recibo de Abono','I'); 

    }

    /* SESION TEMPORAL PARA Filtro de Abonos */
     public function upd_filtroabonos() {
        $documento = $this->input->post("documento");
        if ($documento != NULL) { $this->session->set_userdata("tmp_creditodocumento", $documento); } 
        else { $this->session->set_userdata("tmp_creditodocumento", NULL); }

/*        $fecdesde = $this->input->post("desde");
        $desde = str_replace('/', '-', $fecdesde); 
        $desde = date("Y-m-d", strtotime($desde));

        $fechasta = $this->input->post("hasta");
        $hasta = str_replace('/', '-', $fechasta); 
        $hasta = date("Y-m-d", strtotime($hasta));

        if ($desde != NULL) { $this->session->set_userdata("tmp_creditodesde", $desde); } 
        else { $this->session->set_userdata("tmp_creditodesde", NULL); }

        if ($hasta != NULL) { $this->session->set_userdata("tmp_creditohasta", $hasta); } 
        else { $this->session->set_userdata("tmp_creditohasta", NULL); }
*/
        $arr['res'] = 1;
        print json_encode($arr);               
    }

    private function pagina_vpdf() {
      $this->fpdf->SetMargins('12', '7', '10');   #Margenes
      $this->fpdf->AddPage('P', 'A4');        #Orientación y tamaño 
    }    

    public function pdf_abonos_cliente(){
        $idsucursal = $this->session->userdata("tmp_creditosucursal");
        $iddoc = $this->session->userdata("tmp_creditodocumento");

        if ($iddoc != 0){
          $registros = $this->Credito_model->lista_abonos_factura($iddoc);
          $idcliente = 0;
          if (count($registros) > 0){
            $idcliente = $registros[0]->id_cliente;
          }
        }
        else{
          $idcliente = $this->session->userdata("tmp_creditocliente");
          $desde = $this->session->userdata("tmp_creditodesde");
          $desde = str_replace('/', '-', $desde);   $desde = date("Y-m-d", strtotime($desde));          
          $hasta = $this->session->userdata("tmp_creditohasta");
          $hasta = str_replace('/', '-', $hasta);   $hasta = date("Y-m-d", strtotime($hasta));          
          $estado = $this->session->userdata("tmp_creditoestado");
          $rango = $this->session->userdata("tmp_creditorango");
          $registros = $this->Credito_model->lista_abonos_cliente_rango($idsucursal, $idcliente, $estado, $rango, $desde, $hasta);
        }  
        if (count($registros) == 0){
          return false;
        }
        $cliente = $this->Cliente_model->sel_cli_id($idcliente);
        if ($idsucursal == 0) { $idsucursal = $registros[0]->id_sucursal; }
        $sucursal = $this->Sucursal_model->sel_suc_id($idsucursal);      
        
      
        // ENCABEZADO DEL PDF 
        $this->load->library('Fpdf');
        $this->fpdf->fontpath = 'font/'; 
        $this->fpdf->AliasNbPages();
        $this->pagina_vpdf();
        $this->fpdf->SetFillColor(139, 35, 35);
        $this->fpdf->SetFont('Arial','B',10);
        $this->fpdf->SetTextColor(0,0,0);

        if ($sucursal->logo_sucursal){    
            $file_name = "ppp.jpg";
            $pic = base64_decode($sucursal->logo_sucursal);
            imagejpeg(imagecreatefromstring ( $pic ), $file_name);

            $this->fpdf->Image($file_name,10,10,30,14);
        }  
        $this->fpdf->Line(12,25,196,25);
        $this->fpdf->SetFont('Arial','B',6);

        $this->fpdf->SetXY(100,8);
        $this->fpdf->Cell(20,10,utf8_decode($sucursal->dir_sucursal),0,0,'C');
        $this->fpdf->SetXY(100,13);
        $this->fpdf->Cell(20,10,utf8_decode($sucursal->telf_sucursal),0,0,'C');
        $this->fpdf->SetXY(100,18);
        $this->fpdf->Cell(20,10,utf8_decode($sucursal->mail_sucursal),0,0,'C');

        $fechaf = date("d/m/Y"/*, strtotime($fec)*/);
        $this->fpdf->SetXY(165,18);
        $this->fpdf->Cell(30,5,"Fecha: $fechaf",0,1,'R');

        // TITULO DE DETALLES 
        $this->fpdf->ln(10); 
        $this->fpdf->SetFont('Arial','B',10);
        $this->fpdf->Cell(120,5,utf8_decode("Cliente: $cliente->nom_cliente"),0,0,'L');
        $this->fpdf->Cell(70,5,utf8_decode("Identificación: $cliente->ident_cliente"),0,1,'R');
        $this->fpdf->Cell(190,5,utf8_decode("Dirección: $cliente->direccion_cliente"),0,1,'L');
        $this->fpdf->Cell(120,5,utf8_decode("Correo: $cliente->correo_cliente"),0,0,'L');
        $this->fpdf->Cell(70,5,utf8_decode("Teléfono: $cliente->telefonos_cliente"),0,1,'R');
/*        $this->fpdf->Cell(85,5,"Fecha: $fechaf",0,1,'R');
        $this->fpdf->Cell(92.5,5,utf8_decode("Tipo Movimiento: $tipmov"),0,0,'L');
        $this->fpdf->Cell(92.5,5,utf8_decode("Almacen Origen: $almaorigen"),0,1,'R');
        if($idtipmov == 8){
            $this->fpdf->Cell(100,5,utf8_decode("Almacen Destino: $encrec->almadestino"),0,0,'L');
            $this->fpdf->Cell(85,5,utf8_decode("Nro Documento: $encrec->docdestino"),0,1,'R');    
            $this->fpdf->Line(12,42,196,42);
        }else{
            $this->fpdf->Line(12,37,196,37);
        }
*/        
        if (($iddoc != 0) && ($registros[0]->observaciones != NULL)){
          $this->fpdf->ln(4); 
          $this->fpdf->SetFont('Arial','',10);
          $this->fpdf->MultiCell(185,5,utf8_decode("Descripción: ".$registros[0]->observaciones));
        }

        $this->fpdf->ln(2); 
        $this->fpdf->SetFont('Arial','B',10);

        $this->fpdf->Cell(105,5,utf8_decode("Factura"),1,0,'C');
        $this->fpdf->Cell(80,5,utf8_decode("Pagos"),1,1,'C');
        $this->fpdf->Cell(30,5,utf8_decode("Número"),1,0,'C');
        $this->fpdf->Cell(25,5,utf8_decode("Fecha"),1,0,'C');
        $this->fpdf->Cell(25,5,utf8_decode("Monto"),1,0,'C');
        $this->fpdf->Cell(25,5,utf8_decode("Pendiente"),1,0,'C');
        $this->fpdf->Cell(30,5,utf8_decode("Número"),1,0,'C');
        $this->fpdf->Cell(25,5,utf8_decode("Fecha"),1,0,'C');
        $this->fpdf->Cell(25,5,utf8_decode("Monto"),1,1,'C');
        // CICLO DE DETALLES DE FACTURA 
        $docant = 0;
        $pendiente = 0;
        $this->fpdf->SetFont('Arial','',8); 
        foreach ($registros as $row) {
            if ($row->id_venta != $docant){
              if ($docant != 0){
                $this->fpdf->ln();     
              }
              $docant = $row->id_venta;
              $pendiente = $row->montototal - $row->pagado;           
              if ($pendiente < 0) {$pendiente = 0;}

              //$total += $subtotal; 
              $this->fpdf->SetX(10);
              $this->fpdf->Cell(30,5,utf8_decode("$row->nro_factura"),0,0,'C');
              $this->fpdf->Cell(25,5,utf8_decode("$row->fechafactura"),0,0,'C');
              $this->fpdf->Cell(25,5,number_format($row->montototal,2),0,0,'R');
              $this->fpdf->Cell(25,5,number_format($pendiente,2),0,0,'R');
            }

            if ($row->id != NULL){
              $this->fpdf->SetX(115);
              $this->fpdf->Cell(30,5,utf8_decode("$row->numero"),0,0,'C');
              $this->fpdf->Cell(25,5,utf8_decode("$row->fecha"),0,0,'C');
              $this->fpdf->Cell(25,5,number_format($row->monto,2),0,0,'R');
            }
            $this->fpdf->ln(); 

/*            $this->fpdf->Cell(20,5,$cant,0,0,'C');
            $this->fpdf->Cell(25,5,utf8_decode($unimed),0,0,'C');
            $this->fpdf->Cell(20,5,$subtotal,0,1,'R');*/
        }
/*        $this->fpdf->SetFont('Arial','B',8);
        $this->fpdf->Cell(185,5,number_format($total,2),0,1,'R');

   //     $this->pdf_r->Cell(100,4,utf8_decode("Monto: ".$encrec->monto),0,1,'L');
        $this->fpdf->ln(10); 

        $this->fpdf->Cell(50,0,'',1,0,'L');
        $this->fpdf->Cell(80,0,'',0,0,'L');
        $this->fpdf->Cell(50,0,'',1,1,'L'); 

        $this->fpdf->Cell(50,4,utf8_decode("Entrega: $emisor"),0,0,'L');
        $this->fpdf->Cell(80,0,'',0,0,'L');        
        $this->fpdf->Cell(50,4,utf8_decode("Recibe: "),0,1,'L'); 
*/

        $this->fpdf->Output('Abonos de Facturas','I'); 

    }
  


}

?>