<?php
/*------------------------------------------------
  ARCHIVO: Garantia.php
  DESCRIPCION: Contiene los métodos relacionados con la Garantia.
  FECHA DE CREACIÓN: 16/10/2018
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Garantia extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Garantia_model");
        $this->load->Model("Cliente_model");     
        $this->load->Model("Sucursal_model");                
        $this->load->Model("Almacen_model");                
        $this->load->Model("Inventario_model");                
        $this->load->Model("Producto_model");                
        $this->load->Model("Parametros_model");                
        $this->request = json_decode(file_get_contents('php://input'));
    }

    public function index() {
      $desde = $this->session->userdata("tmp_gardev_desde");
      $hasta = $this->session->userdata("tmp_gardev_hasta");
      $cliente = $this->session->userdata("tmp_gardev_cli");

      if (($desde == NULL) || ($hasta == NULL)){
        $fdesde = date("Y-m-d"); 
        $fhasta = date("Y-m-d"); 
        $varhd = '00:00:00';
        $varhh = '23:59:59';
        $desde = $fdesde." ".$varhd;
        $hasta = $fhasta." ".$varhh;
        $this->session->set_userdata("tmp_gardev_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_gardev_desde", $desde); } 
        else { $this->session->set_userdata("tmp_gardev_desde", NULL); }
        $this->session->set_userdata("tmp_gardev_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_gardev_hasta", $hasta); } 
        else { $this->session->set_userdata("tmp_gardev_hasta", NULL); }
        $this->session->set_userdata("tmp_gardev_cli", 0);  
      }  

      $data["desde"] = $desde;
      $data["hasta"] = $hasta;
      $data["cliente"] = $cliente;

      $data["base_url"] = base_url();
      $data["content"] = "garantiadevolucion";
      $this->load->view("layout", $data);
    }    

    public function listadoDataDevolucion() {
        date_default_timezone_set("America/Guayaquil");
        $desde = $this->session->userdata("tmp_gardev_desde");
        $hasta = $this->session->userdata("tmp_gardev_hasta");
        $cliente = $this->session->userdata("tmp_gardev_cli");
        $registro = $this->Garantia_model->lst_garantiadevolucion($desde, $hasta, $cliente); 
        $tabla = "";
        foreach ($registro as $row) {

          $fec = str_replace('-', '/', $row->fecha); $fec = date("d/m/Y", strtotime($fec));

          $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Imprimir\" id=\"'.$row->id.'\" class=\"btn bg-navy color-palette btn-xs btn-grad garantia_print\"><i class=\"fa fa-print\"></i></a>';

            /*$ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Editar\" id=\"'.$row->idguia.'\" class=\"btn btn-success btn-xs btn-grad edi_guia\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->idguia.'\" name=\"'.$row->secuencial.'\" class=\"btn btn-danger btn-xs btn-grad del_guia\"><i class=\"fa fa-trash-o\"></i></a> <a href=\"#\" title=\"Imprimir\" id=\"'.$row->idguia.'\" class=\"btn bg-navy color-palette btn-xs btn-grad guia_print\"><i class=\"fa fa-print\"></i></a></div>';
            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Editar\" id=\"'.$row->id.'\" class=\"btn btn-success btn-xs btn-grad edi_fact\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Anular\" id=\"'.$row->id.'\" class=\"btn btn-danger btn-xs btn-grad anu_fact\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i></a> <a href=\"#\" title=\"Imprimir Venta\" id=\"'.$row->id.'\" class=\"btn bg-navy color-palette btn-xs btn-grad venta_print\"><i class=\"fa fa-print\"></i></a></div>';*/                   
         
          $tabla.='{"fecha":"' . $fec . '",
                    "nro_documento":"' . $row->nrodevolucion . '",
                    "cliente":"'.addslashes($row->nom_cliente).'",
                    "identificacion":"' . addslashes($row->ident_cliente) . '",
                    "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    public function tmp_gardev_fecha() {
      $this->session->unset_userdata("tmp_gardev_desde"); 
      $this->session->unset_userdata("tmp_gardev_hasta");
      $this->session->unset_userdata("tmp_gardev_cli");
      $fecdesde = $this->input->post("fdesde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      $fechasta = $this->input->post("fhasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));
      $vdesde = $desde;
      $vhasta = $hasta;        
      $this->session->set_userdata("tmp_gardev_desde", NULL);
      if ($vdesde != NULL) { $this->session->set_userdata("tmp_gardev_desde", $vdesde);} 
      else { $this->session->set_userdata("tmp_gardev_desde", NULL); }
      $this->session->set_userdata("tmp_gardev_hasta", NULL);
      if ($vhasta != NULL) { $this->session->set_userdata("tmp_gardev_hasta", $vhasta);} 
      else { $this->session->set_userdata("tmp_gardev_hasta", NULL);}
      $cliente = $this->input->post("cliente");
      $this->session->set_userdata("tmp_gardev_cli", NULL);
      if ($cliente != NULL) { $this->session->set_userdata("tmp_gardev_cli", $cliente);} 
      else { $this->session->set_userdata("tmp_gardev_cli", NULL);}

      $arr['resu'] = 1;
      print json_encode($arr);
    } 

    public function lst_clientes(){
       $registros = $this->Cliente_model->sel_cli();
       echo json_encode($registros);       
    }

    public function lst_sucursales(){
       $registros = $this->Sucursal_model->lst_sucursales();
       echo json_encode($registros);       
    }

    public function lst_seriesdisponibles($producto){
       $registros = $this->Garantia_model->lst_seriesdisponibles($producto);
       echo json_encode($registros);       
    }

    public function lst_productos(){
       $registros = $this->Producto_model->lista_pro();
       echo json_encode($registros);       
    }

    public function agregar() {     
      $data["base_url"] = base_url();
      $data["content"] = "garantiadevolucion_edit";
      $this->load->view("layout", $data);
    }

    public function get_devolucionnumero($sucursal){
      $numero = $this->Garantia_model->sel_numerodevoluciongarantia($sucursal);
      echo json_encode($numero);       
    }

    public function get_cliente_identificacion($identificacion){
      $cliente = $this->Cliente_model->sel_cli_identificacion($identificacion);
      echo json_encode($cliente);       
    }

    public function get_cliente_nombre($nombre){
      $nombre = str_replace ('%20',' ',$nombre);
      $cliente = $this->Cliente_model->sel_cli_nombre($nombre);
      echo json_encode($cliente);       
    }

    public function get_cliente_productosgarantia($idcliente){
      $productos = $this->Garantia_model->lst_productogarantiacliente($idcliente);
      echo json_encode($productos);       
    }

    public function sel_serie_id($idserie){
      $productos = $this->Garantia_model->sel_serie_id($idserie);
      echo json_encode($productos);       
    }


    public function valcliente(){
      $idcliente = $this->input->post('idcliente');
      $resu = $this->Pedido_model->valida_cliente($idcliente);
      $this->session->set_userdata("tmp_gardev_clienteselect", NULL);
      if(count($resu) > 0){ 
        $mens = $resu[0];  
        $this->session->set_userdata("tmp_gardev_clienteselect", $resu[0]->id_cliente);
      }
      else { $mens = NULL; }
      $arr['mens'] = $mens;
      print json_encode($arr);
    }

    public function get_almacenes($sucursal){
      $almacenes = $this->Almacen_model->lst_almacensucursal($sucursal);
      echo json_encode($almacenes);       
    }

    public function guardar_devolucion(){
      $id = $this->request->id;
      $idusu = $this->session->userdata("sess_id");
      if (($id == '') || ($id == '0')){
        $numero = $this->Garantia_model->sel_numerodevoluciongarantia($this->request->sucursal);
        $id = $this->Garantia_model->ins_garantiadevolucion($this->request->fecha, $this->request->sucursal, $numero,
                                                            $this->request->idcliente, $idusu, $this->request->listaserie);
        if ($id > 0){
          $idusu = $this->session->userdata("sess_id");
          foreach($this->request->listaserie as $item){
            $seriedevuelta = $item->id_seriedevuelta;
            $tmpidmov = $this->Inventario_model->ins_movimtmp_auto($idusu, $seriedevuelta, $item->id_almacen, 4);          
            $fecha = date("Y-m-d");
            $idmoventrada = $this->Inventario_model->guardar($tmpidmov, $fecha);
            $objmov = $this->Inventario_model->sel_documentoinventario_id($idmoventrada);
            if ($objmov){
              $this->Inventario_model->ins_seriekardexingreso($seriedevuelta, $item->id_almacen, 3, 
                                                              $idmoventrada, $objmov->nro_documento, $fecha, 'Devolucion / Garantia');                
            }  

            $idmovsalida = 'NULL';
            $serieentregada = $item->id_serieentregada;
            if ($serieentregada != '') { 
              $objserie = $this->Inventario_model->sel_serie_id($serieentregada);
              if ($objserie){
                $tmpidmov = $this->Inventario_model->ins_movimtmp_auto($idusu, $serieentregada, $objserie->id_almacen, 5);          
                $idmovsalida = $this->Inventario_model->guardar($tmpidmov, $fecha);
                $objmov = $this->Inventario_model->sel_documentoinventario_id($idmovsalida);
                if ($objmov){
                  $this->Inventario_model->ins_seriekardexingreso($serieentregada, $objserie->id_almacen, 4, 
                                                                  $idmovsalida, $objmov->nro_documento, $fecha, 'Reposicion / Garantia');                
                }  
              }  
            }
            $this->Garantia_model->upd_garantiadevolucion_detalle($id, $item->id_detalleventa, $seriedevuelta, 
                                                                  $idmoventrada, $idmovsalida);
          }  
        }
      }      


      echo json_encode($id);
    }

    public function get_devolucion_documentos($desde, $hasta, $cliente){
      $documentos = $this->Garantia_model->lst_garantiadevolucion($desde, $hasta, $cliente); 
      echo json_encode($documentos);       
    }

    /* ABRIR VENTANA PARA Imprimir  */
    public function imprimirdevolucion(){
        $factoriva = $this->Parametros_model->iva_get();
        $factoriva = $factoriva->valor * 100;
        date_default_timezone_set("America/Guayaquil");
        
        $idcomp = $this->input->post('id');

        $tabla ="\r\n" . "DEVOLUCION DE PRODUCTO EN GARANTIA". "\r\n";
        $row = $this->Garantia_model->sel_garantiadevolucion_id($idcomp);

        $tabla.="Nro Devolución:" . "\x1F \x1F" . $row->nrodevolucion . "\r\n";
        $strdate=date("d/m/Y", strtotime($row->fecha));
        $tabla.="Fecha Devolución:" . "\x1F \x1F" . $strdate . "\r\n" . "\r\n";
        $tabla.="Cliente:" . "\x1F \x1F" . $row->nom_cliente . "\r\n";        
        $tabla.="Direccion:". "\x1F \x1F" . $row->direccion_cliente . "\r\n";        
        $tabla.="CI/RUC:". "\x1F \x1F" . $row->ident_cliente . "\r\n";        
        $tabla.="Telefono:". "\x1F \x1F" . $row->telefonos_cliente . "\r\n";        
        $tabla.="Correo:" . "\x1F \x1F" . $row->correo_cliente . "\r\n";

        $tabla.= "\r\n";
        $registro = $this->Garantia_model->sel_garantiadevolucion_detalles($idcomp);
        foreach ($registro as $row) {
            $tabla.="DETALLE DE PRODUCTO DEVUELTO". "\r\n";        
            $tabla.="----------------------------". "\r\n" . "\r\n";        

            $tabla.="NUMERO FACTURA: " . $row->nro_factura . "    FECHA EMISION: " . $row->fechafactura . "\r\n" . "\r\n";        

            $tabla.="NUMERO SERIE DEVUELTA: " . $row->seriedevuelta . "    DESCRIPCION: " . $row->productodevuelto . "\r\n" . "\r\n";        

            if (trim($row->observaciones) != ''){
              $tabla.="OBSERVACIONES: " . $row->observaciones . "\r\n" . "\r\n";        
            }

            if ($row->desdedevuelta){
              $fec = str_replace('-', '/', $row->desdedevuelta); $fec = date("d/m/Y", strtotime($fec));
              $tabla.="GARANTIA DESDE: " . $fec;
              $fec = str_replace('-', '/', $row->hastadevuelta); $fec = date("d/m/Y", strtotime($fec));
              $tabla.=" HASTA: " . $fec . "\r\n" . "\r\n";        

              if ($row->idserie_reposicion){
                $tabla.="NUMERO SERIE REPOSICION: " . $row->seriereposicion . "    DESCRIPCION: " . $row->productoreposicion . "\r\n" . "\r\n";        

                if ($row->desdereposicion){
                  $fec = str_replace('-', '/', $row->desdereposicion); $fec = date("d/m/Y", strtotime($fec));
                  $tabla.="GARANTIA DESDE: " . $fec;
                  $fec = str_replace('-', '/', $row->hastareposicion); $fec = date("d/m/Y", strtotime($fec));
                  $tabla.=" HASTA: " . $fec . "\r\n" . "\r\n";        
                }  

              }
            }

        }

        $impresionlocal = $this->Parametros_model->sel_impresionlocal();
        $data["impresionlocal"] = $impresionlocal; 

        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("garantiadevolucion_imprimir", $data);

    }

    public function imprimirdevolucion_ticket(){
        $factoriva = $this->Parametros_model->iva_get();
        $factoriva = $factoriva->valor * 100;
        date_default_timezone_set("America/Guayaquil");
        
        $idcomp = $this->input->post('id');

        $tabla ="\r\n" . "DEVOLUCION DE PRODUCTO EN GARANTIA". "\r\n";
        $row = $this->Garantia_model->sel_garantiadevolucion_id($idcomp);

        $tabla.="Nro Devolución:" . "\x1F \x1F" . $row->nrodevolucion . "\r\n";
        $strdate=date("d/m/Y", strtotime($row->fecha));
        $tabla.="Fecha Devolución:" . "\x1F \x1F" . $strdate . "\r\n" . "\r\n";
        $tabla.="Cliente:" . "\x1F \x1F" . $row->nom_cliente . "\r\n";        
        $tabla.="Direccion:". "\x1F \x1F" . $row->direccion_cliente . "\r\n";        
        $tabla.="CI/RUC:". "\x1F \x1F" . $row->ident_cliente . "\r\n";        
        $tabla.="Telefono:". "\x1F \x1F" . $row->telefonos_cliente . "\r\n";        
        $tabla.="Correo:" . "\x1F \x1F" . $row->correo_cliente . "\r\n";

        $tabla.= "\r\n";
        $registro = $this->Garantia_model->sel_garantiadevolucion_detalles($idcomp);
        foreach ($registro as $row) {
            $tabla.="DETALLE DE PRODUCTO DEVUELTO". "\r\n";        
            $tabla.="----------------------------". "\r\n" . "\r\n";        

            $tabla.="NUMERO FACTURA: " . $row->nro_factura . "\r\n";        
            $tabla.="FECHA EMISION: " . $row->fechafactura . "\r\n" . "\r\n";        

            $tabla.="NUMERO SERIE DEVUELTA: " . $row->seriedevuelta . "\r\n";        
            $tabla.="DESCRIPCION: " . $row->productodevuelto . "\r\n" . "\r\n";        

            if (trim($row->observaciones) != ''){
              $tabla.="OBSERVACIONES: " . $row->observaciones . "\r\n" . "\r\n";        
            }

            if ($row->desdedevuelta){
              $fec = str_replace('-', '/', $row->desdedevuelta); $fec = date("d/m/Y", strtotime($fec));
              $tabla.="GARANTIA DESDE: " . $fec . "\r\n";
              $fec = str_replace('-', '/', $row->hastadevuelta); $fec = date("d/m/Y", strtotime($fec));
              $tabla.="         HASTA: " . $fec . "\r\n" . "\r\n";        

              if ($row->idserie_reposicion){
                $tabla.="NUMERO SERIE REPOSICION: " . $row->seriereposicion . "\r\n";        
                $tabla.="DESCRIPCION: " . $row->productoreposicion . "\r\n" . "\r\n";        

                if ($row->desdereposicion){
                  $fec = str_replace('-', '/', $row->desdereposicion); $fec = date("d/m/Y", strtotime($fec));
                  $tabla.="GARANTIA DESDE: " . $fec . "\r\n";
                  $fec = str_replace('-', '/', $row->hastareposicion); $fec = date("d/m/Y", strtotime($fec));
                  $tabla.="         HASTA: " . $fec . "\r\n" . "\r\n";        
                }  

              }
            }

        }

        $impresionlocal = $this->Parametros_model->sel_impresionlocal();
        $data["impresionlocal"] = $impresionlocal; 

        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("garantiadevolucion_imprimir", $data);

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

    public function reportedevolucionesXLS(){
        $desde = $this->session->userdata("tmp_gardev_desde");
        $hasta = $this->session->userdata("tmp_gardev_hasta");
        $cliente = $this->session->userdata("tmp_gardev_cli");
        $registro = $this->Garantia_model->lst_garantiadevolucion($desde, $hasta, $cliente); 

/*        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        }
*/
        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteDevoluciones');
        //set cell A1 content with some text
        $empresa = "";
/*        if ($objemp){
          $empresa = "  - EMPRESA: " . $objemp->nom_emp . "    RUC: " . $objemp->ruc_emp;
        }*/
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Devoluciones de Productos en Garantía de ' . 
                                                     substr($desde,0,10) . ' al ' . substr($hasta,0,10) . $empresa);
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:L1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Devolución');
        $this->excel->getActiveSheet()->setCellValue('C3', 'C.I./R.U.C');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Cliente');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Factura');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Serie Devuelta');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Importe');
        $this->excel->getActiveSheet()->setCellValue('I3', 'Garantia-Desde');
        $this->excel->getActiveSheet()->setCellValue('J3', 'Garantia-Hasta');        
        //$this->excel->getActiveSheet()->setCellValue('I3', '');  
        $this->excel->getActiveSheet()->setCellValue('L3', 'Serie Reposición');
        $this->excel->getActiveSheet()->setCellValue('M3', 'Garantia-Desde');
        $this->excel->getActiveSheet()->setCellValue('N3', 'Garantia-Hasta');

        $this->excel->getActiveSheet()->getStyle('A3:N3')->getFont()->setBold(true);

        $fila = 4;
        $filaini = $fila;
        foreach ($registro as $ven) {

            $fec = str_replace('-', '/', $ven->fecha); $fec = date("d/m/Y", strtotime($fec)); 
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->nrodevolucion);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ven->ident_cliente);
            $this->excel->getActiveSheet()->getStyle('C'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $ven->nom_cliente);            

            $detalles = $this->Garantia_model->sel_garantiadevolucion_detalles($ven->id); 
            foreach ($detalles as $item) {
              $this->excel->getActiveSheet()->setCellValue('E'.$fila, $item->nro_factura);
              $this->excel->getActiveSheet()->setCellValue('F'.$fila, $item->seriedevuelta);
              $this->excel->getActiveSheet()->setCellValue('G'.$fila, $item->productodevuelto);
              $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($item->importe_productodevuelto,2));
              $fec = str_replace('-', '/', $item->desdedevuelta); $fec = date("d/m/Y", strtotime($fec)); 
              $this->excel->getActiveSheet()->setCellValue('I'.$fila, $fec);
              $fec = str_replace('-', '/', $item->hastadevuelta); $fec = date("d/m/Y", strtotime($fec)); 
              $this->excel->getActiveSheet()->setCellValue('J'.$fila, $fec);
              if ($item->idserie_reposicion){
                $this->excel->getActiveSheet()->setCellValue('L'.$fila, $item->seriereposicion);
                $fec = str_replace('-', '/', $item->desdereposicion); $fec = date("d/m/Y", strtotime($fec)); 
                $this->excel->getActiveSheet()->setCellValue('M'.$fila, $fec);
                $fec = str_replace('-', '/', $item->hastareposicion); $fec = date("d/m/Y", strtotime($fec)); 
                $this->excel->getActiveSheet()->setCellValue('N'.$fila, $fec);
              }
              $fila++;          
            }

            if (count($detalles) == 0)
              $fila++;          
        }    
        $fila++;          

        $this->excel->getActiveSheet()->freezePane('A4');
       
        $filename='reportedevolucion.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');        
    }  

    public function productosgarantia() {
      $cliente = $this->session->userdata("tmp_garprod_cli");
      $garantia = $this->session->userdata("tmp_garprod_gar");

      if ($cliente == NULL) {
        $cliente = 0;
        $garantia = 0;
        $this->session->set_userdata("tmp_garprod_cli", 0);  
        $this->session->set_userdata("tmp_garprod_gar", 0);  
      }  
      $data["cliente"] = $cliente;
      $data["garantia"] = $garantia;

      $clientes = $this->Cliente_model->sel_cli();
      $data["clientes"] = $clientes;

      $data["base_url"] = base_url();
      $data["content"] = "garantiaproductos";
      $this->load->view("layout", $data);
    }    

    public function tmp_garprod_cliente() {
      $this->session->unset_userdata("tmp_garprod_cli");
      $this->session->unset_userdata("tmp_garprod_gar");
      $cliente = $this->input->post("cliente");
      $garantia = $this->input->post("garantia");
      $this->session->set_userdata("tmp_garprod_cli", NULL);
      if ($cliente != NULL) { $this->session->set_userdata("tmp_garprod_cli", $cliente);} 
      else { $this->session->set_userdata("tmp_garprod_cli", NULL);}
      $this->session->set_userdata("tmp_garprod_gar", NULL);
      if ($garantia != NULL) { $this->session->set_userdata("tmp_garprod_gar", $garantia);} 
      else { $this->session->set_userdata("tmp_garprod_gar", NULL);}

      $arr['resu'] = 1;
      print json_encode($arr);
    } 

    public function listadoDataProdgarantia() {
        $cliente = $this->session->userdata("tmp_garprod_cli");
        $garantia = $this->session->userdata("tmp_garprod_gar");
        $registro = $this->Garantia_model->lst_productogarantia($cliente, $garantia); 
        $tabla = "";
        foreach ($registro as $row) {

          $fec = str_replace('-', '/', $row->fecha); $fec = date("d/m/Y", strtotime($fec));
          $entregado = str_replace('-', '/', $row->fec_desde); $entregado = date("d/m/Y", strtotime($entregado));
          $vencimiento = str_replace('-', '/', $row->fec_hasta); $vencimiento = date("d/m/Y", strtotime($vencimiento));
         
          $tabla.='{"fecha":"' . $fec . '",
                    "nro_documento":"' . $row->nro_factura . '",
                    "producto":"'.addslashes($row->descripcion).'",
                    "serie":"' . addslashes($row->numeroserie) . '",
                    "cedula":"' . addslashes($row->ident_cliente) . '",
                    "cliente":"' . addslashes($row->nom_cliente) . '",
                    "precio":"' . $row->precio . '",
                    "entregado":"' . $entregado . '",
                    "dias":"' . $row->dias_gar . '",
                    "vencimiento":"' . $vencimiento . '"
                  },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    public function reporteproductosgarantiaXLS(){
        $cliente = $this->session->userdata("tmp_garprod_cli");
        $garantia = $this->session->userdata("tmp_garprod_gar");
        $registro = $this->Garantia_model->lst_productogarantia($cliente, $garantia); 

/*        $objsuc = $this->Sucursal_model->sel_suc_id($sucursal);
        if ($objsuc){
          $objemp = $this->Empresa_model->sel_emp_id($objsuc->id_empresa);
        }
*/
        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Reporte Productos Garantia');
        //set cell A1 content with some text
        $empresa = "";
/*        if ($objemp){
          $empresa = "  - EMPRESA: " . $objemp->nom_emp . "    RUC: " . $objemp->ruc_emp;
        }*/
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Productos en Garantía ' . $empresa);
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:L1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Factura');
        $this->excel->getActiveSheet()->setCellValue('C3', 'C.I./R.U.C');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Cliente');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Serie');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Importe');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Entregado');
        $this->excel->getActiveSheet()->setCellValue('I3', 'Dias');
        $this->excel->getActiveSheet()->setCellValue('J3', 'Vencimiento');        

        $this->excel->getActiveSheet()->getStyle('A3:J3')->getFont()->setBold(true);

        $fila = 4;
        $filaini = $fila;
        foreach ($registro as $ven) {

            $fec = str_replace('-', '/', $ven->fecha); $fec = date("d/m/Y", strtotime($fec)); 
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->nro_factura);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ven->ident_cliente);
            $this->excel->getActiveSheet()->getStyle('C'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $ven->nom_cliente);            

            $this->excel->getActiveSheet()->setCellValue('E'.$fila, $ven->numeroserie);
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, $ven->descripcion);
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($ven->precio,2));
            $fec = str_replace('-', '/', $ven->fec_desde); $fec = date("d/m/Y", strtotime($fec)); 
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, $fec);
            $this->excel->getActiveSheet()->setCellValue('I'.$fila, $ven->dias_gar);
            $fec = str_replace('-', '/', $ven->fec_hasta); $fec = date("d/m/Y", strtotime($fec)); 
            $this->excel->getActiveSheet()->setCellValue('J'.$fila, $fec);

            $fila++;          
        }    
        $fila++;          

        $this->excel->getActiveSheet()->freezePane('A4');
       
        $filename='reporteproductosgarantia.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');        
    }  

}

?>