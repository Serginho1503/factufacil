<?php
/*------------------------------------------------
  ARCHIVO: Guiaremision.php
  DESCRIPCION: Contiene los métodos relacionados con la Guia de Remision.
  FECHA DE CREACIÓN: 15/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class Guiaremision extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Guiaremision_model");
        $this->load->Model("Parametros_model");
        $this->load->Model("Empresa_model");
        $this->load->Model("Cliente_model");
        $this->load->Model("Sucursal_model");
        $this->load->Model("Almacen_model");
        $this->load->Model("Puntoemision_model");        
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
      date_default_timezone_set("America/Guayaquil");

      $desde = $this->session->userdata("tmp_guia_desde");
      $hasta = $this->session->userdata("tmp_guia_hasta");
      $sucursal = $this->session->userdata("tmp_guia_sucursal");

      if (($desde == NULL) || ($hasta == NULL)){
        $fdesde = date("Y-m-d"); 
        $fhasta = date("Y-m-d"); 
        $varhd = '00:00:00';
        $varhh = '23:59:59';
        $desde = $fdesde." ".$varhd;
        $hasta = $fhasta." ".$varhh;
        $sucursal = 0;
        $this->session->set_userdata("tmp_guia_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_guia_desde", $desde); } 
        else { $this->session->set_userdata("tmp_guia_desde", NULL); }
        $this->session->set_userdata("tmp_guia_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_guia_hasta", $hasta); } 
        else { $this->session->set_userdata("tmp_guia_hasta", NULL); }
        $this->session->set_userdata("tmp_guia_sucursal", NULL);
        if ($sucursal != NULL) { $this->session->set_userdata("tmp_guia_sucursal", $sucursal); } 
        else { $this->session->set_userdata("tmp_guia_sucursal", NULL); }
      }  

      $sucursales = $this->Sucursal_model->lst_sucursales();
      $data["sucursales"] = $sucursales;

      $monto = 0;//$this->Guiaremision_model->monto_rango($desde, $hasta);

      $data["desde"] = $desde;
      $data["hasta"] = $hasta;
      $data["sucursal"] = $sucursal;

      $data["monto"] = $monto;
      $data["base_url"] = base_url();
      $data["content"] = "guiaremision";
      $this->load->view("layout", $data);
    }

    public function listadoDataGuia() {
        date_default_timezone_set("America/Guayaquil");
        $desde = $this->session->userdata("tmp_guia_desde");
        $hasta = $this->session->userdata("tmp_guia_hasta");
        $sucursal = $this->session->userdata("tmp_guia_sucursal");
        if ($sucursal == '') { $sucursal = 0; }
        $registro = $this->Guiaremision_model->lst_guiaremision($sucursal, $desde, $hasta); 
        $tabla = "";
        foreach ($registro as $row) {

          $fecemi = str_replace('-', '/', $row->fechaemision); $fecemi = date("d/m/Y", strtotime($fecemi));
          $fecinicio = str_replace('-', '/', $row->fechaini); $fecinicio = date("d/m/Y", strtotime($fecinicio));
          $fecfin = str_replace('-', '/', $row->fechafin); $fecfin = date("d/m/Y", strtotime($fecfin));

          if($row->autorizada == 1){
            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Imprimir\" id=\"'.$row->idguia.'\" class=\"btn bg-navy color-palette btn-xs btn-grad guia_print\"><i class=\"fa fa-print\"></i></a>';
          }else{
            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Editar\" id=\"'.$row->idguia.'\" class=\"btn btn-success btn-xs btn-grad edi_guia\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->idguia.'\" name=\"'.$row->secuencial.'\" class=\"btn btn-danger btn-xs btn-grad del_guia\"><i class=\"fa fa-trash-o\"></i></a> <a href=\"#\" title=\"Imprimir\" id=\"'.$row->idguia.'\" class=\"btn bg-navy color-palette btn-xs btn-grad guia_print\"><i class=\"fa fa-print\"></i></a></div>';
/*            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Editar\" id=\"'.$row->id.'\" class=\"btn btn-success btn-xs btn-grad edi_fact\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Anular\" id=\"'.$row->id.'\" class=\"btn btn-danger btn-xs btn-grad anu_fact\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i></a> <a href=\"#\" title=\"Imprimir Venta\" id=\"'.$row->id.'\" class=\"btn bg-navy color-palette btn-xs btn-grad venta_print\"><i class=\"fa fa-print\"></i></a></div>';*/
          }          
          
          $strnumdoc = $row->numdocsustento;
          $strnumdoc = substr($strnumdoc, 0, 3).'-'.substr($strnumdoc, 3, 3).'-'.substr($strnumdoc, 6, 9);

            $tabla.='{"fecha":"' . $fecemi . '",
                      "nro_documento":"' . $row->secuencial . '",
                      "transportista":"' . addslashes($row->transportista) . '",
                      "puntopartida":"'.addslashes($row->dirpartida).'",
                      "inicio":"'.$fecinicio.'",
                      "fin":"'.$fecfin.'",
                      "comprobanteventa":"'.$strnumdoc.'",
                      "destinatario":"'.addslashes($row->nom_cliente).'",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_guia_fecha() {
      $this->session->unset_userdata("tmp_guia_desde"); 
      $this->session->unset_userdata("tmp_guia_hasta");
      $this->session->unset_userdata("tmp_guia_sucursal");
      $fecdesde = $this->input->post("fdesde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      $fechasta = $this->input->post("fhasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));
      $vdesde = $desde;
      $vhasta = $hasta;        
      $this->session->set_userdata("tmp_guia_desde", NULL);
      if ($vdesde != NULL) { $this->session->set_userdata("tmp_guia_desde", $vdesde);} 
      else { $this->session->set_userdata("tmp_guia_desde", NULL); }
      $this->session->set_userdata("tmp_guia_hasta", NULL);
      if ($vhasta != NULL) { $this->session->set_userdata("tmp_guia_hasta", $vhasta);} 
      else { $this->session->set_userdata("tmp_guia_hasta", NULL);}

      $sucursal = $this->input->post("sucursal");
      $this->session->set_userdata("tmp_guia_sucursal", NULL);
      if ($sucursal != NULL) { $this->session->set_userdata("tmp_guia_sucursal", $sucursal);} 
      else { $this->session->set_userdata("tmp_guia_sucursal", NULL);}

      $arr['resu'] = 1;
      print json_encode($arr);
    } 

    /* VARIABLE DE SESION PARA PASAR DATOS */
    public function tmp_guia() {
        $this->session->unset_userdata("tmp_guia_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_guia_id", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_guia_id", $id);} 
        else { $this->session->set_userdata("tmp_guia_id", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
      } 
      
    public function sel_nroguia_ptoemi(){
        $punto = $this->input->post("punto");
        $nroguia = $this->Guiaremision_model->sel_nroguia_ptoemi($punto);      
        $arr['nroguia'] = $nroguia;
        print json_encode($arr);               
    }

    public function agregar() {
      $sucursales = $this->Sucursal_model->lst_sucursales();
      $data["sucursales"] = $sucursales;
      $cliente = $this->Cliente_model->sel_cli();
      $data["cliente"] = $cliente;
      $transportista = $this->Guiaremision_model->lst_transportista();      
      $data["transportista"] = $transportista;
      $idusu = $this->session->userdata("sess_id");
      $idsuc = 0;
      if ($sucursales != null) { $idsuc = $sucursales[0]->id_sucursal; }
      $puntoemision = $this->Puntoemision_model->sel_puntoemision();//lst_puntoemisionsucursal($idsuc);
      $data["puntoemision"] = $puntoemision;
      $comprobventa = $this->Guiaremision_model->tipo_comprobventa();
      $data["comprobventa"] = $comprobventa;
      

      $data["base_url"] = base_url();
      $data["content"] = "guiaremision_add";
      $this->load->view("layout", $data);
    }

    public function editar() {
        $id = $this->session->userdata("tmp_guia_id");
        $sucursales = $this->Sucursal_model->lst_sucursales();
        $data["sucursales"] = $sucursales;
        $cliente = $this->Cliente_model->sel_cli();
        $data["cliente"] = $cliente;
        $transportista = $this->Guiaremision_model->lst_transportista();      
        $data["transportista"] = $transportista;
        $idusu = $this->session->userdata("sess_id");
        $this->Guiaremision_model->ini_temp($id, $idusu);      
        $tmpcomp = $this->Guiaremision_model->sel_guiaremision($id);             
        $data["tmpcomp"] = $tmpcomp;
        $sucursal = 0;
        if ($tmpcomp != NULL) { $sucursal = $tmpcomp->id_sucursal;}
        $puntoemision = $this->Puntoemision_model->sel_puntoemision();//lst_puntoemisionsucursal($sucursal);
        $data["puntoemision"] = $puntoemision;
        $comprobventa = $this->Guiaremision_model->tipo_comprobventa();
        $data["comprobventa"] = $comprobventa;
        
  
        $data["base_url"] = base_url();
        $data["content"] = "guiaremision_add";
        $this->load->view("layout", $data);
      }
  
    public function listadoProductoGuia() {
        $idusu = $this->session->userdata("sess_id");
        $registro = $this->Guiaremision_model->lst_guiatmp_detalle($idusu); 
        $tabla = "";
        foreach ($registro as $row) {

            $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->iddetalle.'\" class=\"btn btn-danger btn-xs btn-grad del_producto\"><i class=\"fa fa-trash-o\"></i></a> </div>';         

            $cant = '<div ><input type=\"text\" class=\"col-md-12 tdvalor upd_prodcant\" name=\"'.$row->iddetalle.'\" id=\"'.$row->iddetalle.'\" value=\"'.$row->cantidad.'\" ></div>';
            
            $tabla.='{"codigo":"' . $row->codigo . '",
                      "descripcion":"' . addslashes($row->descripcion) . '",
                      "cantidad":"'.$cant.'",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }


    /* MOSTRAR VENTANA DE Facturas */
    public function busca_factura() {
        $idcliente = $this->input->post("cliente");
        $this->session->unset_userdata("tmp_nota_cliente"); 
        $this->session->set_userdata("tmp_nota_cliente", $idcliente);
        $data["base_url"] = base_url();
        $this->load->view("Guiaremision_busca_factura", $data);        
    }

    /* CARGA DE DATO AL DATATABLE */
    public function lst_factura_cliente() {
      $idcliente = $this->session->userdata("tmp_nota_cliente");
      $registro = $this->Guiaremision_model->lst_factura_cliente($idcliente);
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

        $res = $this->Guiaremision_model->upd_docmodificado($idusu, $id);
        print json_encode($res);               
    }

    /* MOSTRAR VENTANA DE PRODUCTOS */
    public function busca_producto() {
        $data["base_url"] = base_url();
        $this->load->view("guiaremision_busca_producto", $data);        
    }
    

    /* CARGA DE DATO AL DATATABLE */
    public function lst_productoguia() {
      $registro = $this->Guiaremision_model->lst_productoguia();
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

        $this->Guiaremision_model->ins_producto($idusu, $id);
        $arr['resu'] = 1;
        print json_encode($arr);               
    }

    public function del_detalle(){
        $iddetalle = $this->input->post("id");

        $this->Guiaremision_model->del_detalle($iddetalle);
        $arr['resu'] = 1;
        print json_encode($arr);               
    }

    public function upd_guiadetalle(){
        $iddetalle = $this->input->post("id");
        $cantidad = $this->input->post("cantidad");

        $this->Guiaremision_model->upd_guiadetalle($iddetalle, $cantidad);
        $arr['resu'] = 1;
        print json_encode($arr);               
    }

    public function guardar_guia(){
        $idusu = $this->session->userdata("sess_id");
        $id = $this->input->post("idguia");

        $fec = $this->input->post("fecha");
        $fec = str_replace('/', '-', $fec); 
        $fechaemision = date("Y-m-d", strtotime($fec));
 
        $dirpartida = $this->input->post("txt_puntopartida");
        $idtransportista = $this->input->post("cmb_transportista");

        $fec = $this->input->post("fecha_inicio");
        $fec = str_replace('/', '-', $fec); 
        $fechaini = date("Y-m-d", strtotime($fec));

        $fec = $this->input->post("fecha_fin");
        $fec = str_replace('/', '-', $fec); 
        $fechafin = date("Y-m-d", strtotime($fec));

        $placa = $this->input->post("txt_placa");
        $puntoemision = $this->input->post("cmb_punto");
        $secuencial = $this->input->post("txt_nroguia");
        $iddestinatario = $this->input->post("cmb_cliente");
        $motivo = $this->input->post("txt_motivo");
        $docaduanero = $this->input->post("txt_docaduanero");
        $codestabdestino = $this->input->post("txt_codestabdestino");
        $ruta = $this->input->post("txt_ruta");
        $coddocsustento = $this->input->post("cmb_comprobventa");
        $numdocsustento = $this->input->post("txt_nrodocmod");
        $numdocsustento = str_replace('-', '', $numdocsustento); 
        $numautdocsustento = $this->input->post("txt_numautdocsustento");

        $fec = $this->input->post("fechadocmod");
        $fec = str_replace('/', '-', $fec); 
        $fechaemidocsustento = date("Y-m-d", strtotime($fec));

        $dirllegada = $this->input->post("txt_dirllegada");

        if (($id == 0) || ($id == '')){
            $id = $this->Guiaremision_model->ins_guiaremision($idusu, $fechaemision, $dirpartida, $idtransportista, 
                                                              $fechaini, $fechafin, $placa, $puntoemision, 
                                                              $iddestinatario, $motivo, $docaduanero, 
                                                              $codestabdestino, $ruta, $coddocsustento, 
                                                              $numdocsustento, $numautdocsustento,
                                                              $fechaemidocsustento, $dirllegada);
        }    
        else{
            $this->Guiaremision_model->upd_guiaremision($idusu, $id, $secuencial, $fechaemision, $dirpartida, 
                                                        $idtransportista, $fechaini, $fechafin, 
                                                        $placa, $puntoemision, $iddestinatario, $motivo, 
                                                        $docaduanero, $codestabdestino, $ruta, $coddocsustento, 
                                                        $numdocsustento, $numautdocsustento,
                                                        $fechaemidocsustento, $dirllegada);            
        }

/*        $arr['resu'] = $id;
        print json_encode($arr);               */
        print "<script> window.location.href = '" . base_url() . "guiaremision'; </script>";

    }

    public function del_guiaremision(){
        $id = $this->input->post("id");

        $this->Guiaremision_model->del_guiaremision($id);
        $arr['resu'] = 1;
        print json_encode($arr);               
    }


    /* ABRIR VENTANA PARA Imprimir  */
    public function imprimirguia(){
        $factoriva = 12;
        date_default_timezone_set("America/Guayaquil");
        
        $idcomp = $this->input->post('id');

        $tabla ="\r\n" . "GUIA DE REMISION". "\r\n";
        $row = $this->Guiaremision_model->sel_guiaremision($idcomp);

        $tabla.="Nro Guía:" . "\x1F \x1F" . $row->puntoemision. '-' . $row->secuencial . "\r\n";
        $strdate=date("d/m/Y", strtotime($row->fechaemision));
        $tabla.="Fecha Emisión:" . "\x1F \x1F" . $strdate . "\r\n" . "\r\n";
        $tabla.="Transportista:". "\x1F \x1F" . $row->transportistanombre . "\r\n";        
        $tabla.="CI:" . "\x1F \x1F" . $row->transportistacedula . "\r\n";
        $tabla.="Direccion Partida:". "\x1F \x1F" . $row->dirpartida . "\r\n";        
        $strdate=date("d/m/Y", strtotime($row->fechaini));
        $tabla.="Fecha Inicio:" . "\x1F \x1F" . $strdate . "\x1F \x1F";
        $strdate=date("d/m/Y", strtotime($row->fechafin));
        $tabla.="Fecha Fin:" . "\x1F \x1F" . $strdate . "\r\n" . "\r\n";
        $tabla.="Documento Sustento:" . "\r\n";
        $tabla.="Número Documento:" . "\x1F \x1F" . $row->numdocsustento . "\r\n";
        $tabla.="Número Autorización:" . "\x1F \x1F" . $row->numautdocsustento . "\r\n";
        $strdate=date("d/m/Y", strtotime($row->fechaemidocsustento));
        $tabla.="Fecha Emisión:" . "\x1F \x1F" . $strdate . "\r\n" . "\r\n";
        $tabla.="Motivo:" . "\x1F \x1F" . $row->motivo . "\r\n";
        $tabla.="Ruta:" . "\x1F \x1F" . $row->ruta . "\r\n" . "\r\n";
        $tabla.="Cliente:" . "\x1F \x1F" . $row->nom_cliente . "\r\n";        
        $tabla.="Direccion:". "\x1F \x1F" . $row->dirllegada . "\r\n";        
        $tabla.="CI/RUC:". "\x1F \x1F" . $row->ident_cliente . "\r\n";        
        $tabla.="Telef.:". "\x1F \x1F" . $row->telefonos_cliente . "\r\n";        
        $tabla.="Cod.Estab.Destino:" . "\x1F \x1F" . $row->codestabdestino . "\r\n";
        if ($row->docaduanero){
          $tabla.="Doc.Aduanero:" . "\x1F \x1F" . $row->docaduanero . "\r\n";
        }  

        $tabla.= "\r\n";
        $tabla.="CODIGO    CANTIDAD   DESCRIPCION". "\r\n";        
        $registro = $this->Guiaremision_model->lst_guia_detalle($idcomp);
        foreach ($registro as $row) {
            $strcodigo = $row->codigointerno;
            $tabla.= $strcodigo;
            $lcant = strlen($strcodigo);
            while ($lcant < 10){
                $tabla.= "\x1F \x1F";
                $lcant++;
            }
            $strcant = $row->cantidad;
            $lcant = strlen($strcant);
            while ($lcant < 8){
                $tabla.= "\x1F \x1F";
                $lcant++;
            }
            $tabla.= $strcant;
            $tabla.= "\x1F \x1F";
            $tabla.= "\x1F \x1F";
            $tabla.= "\x1F \x1F";
            $strnombre = substr($row->descripcion,0,29);
            $tabla.= $strnombre . "\r\n";
        }

        $impresionlocal = $this->Parametros_model->sel_impresionlocal();
        $data["impresionlocal"] = $impresionlocal; 

        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("guiaremision_imprimir", $data);

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

        $this->Guiaremision_model->anular_nota($id);
        $arr['resu'] = 1;
        print json_encode($arr);               
    }

    /* Exportar a Excel */
    public function reporte(){
        $desde = $this->session->userdata("tmp_guia_desde");
        $hasta = $this->session->userdata("tmp_guia_hasta");
        $sucursal = $this->session->userdata("tmp_guia_sucursal");
        if ($sucursal == '') { $sucursal = 0; }
        $venta = $this->Guiaremision_model->lst_guiaremision($sucursal, $desde, $hasta);
        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteGuiaremision');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Guia de Remisión');
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
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Nro.Guía');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Transportista');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Punto Partida');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Inicio');        
        $this->excel->getActiveSheet()->setCellValue('F3', 'Fin');  
        $this->excel->getActiveSheet()->setCellValue('G3', 'Comp.Venta');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Destinatario');

        $this->excel->getActiveSheet()->getStyle('A3:H3')->getFont()->setBold(true);

        $fila = 4;
        foreach ($venta as $ven) {

            $fec = str_replace('-', '/', $ven->fechaemision); @$fec = date("d/m/Y", strtotime(@$fec)); 
            $inicio = str_replace('-', '/', $ven->fechaini); @$inicio = date("d/m/Y", strtotime(@$inicio)); 
            $fin = str_replace('-', '/', $ven->fechafin); @$fin = date("d/m/Y", strtotime(@$fin)); 
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $ven->secuencial);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $ven->transportista);            
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $ven->dirpartida);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, $inicio);
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, $fin);
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, $ven->numdocsustento);
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, $ven->nom_cliente);

            $fila++;          
        }    
        $fila++;          

        
        $filename='reporteGuiaremision.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }  


}



?>


