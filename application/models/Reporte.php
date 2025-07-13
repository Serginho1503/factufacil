<?php
/*------------------------------------------------
  ARCHIVO: Reporte.php
  DESCRIPCION: Contiene los métodos relacionados con la Reporte.
  FECHA DE CREACIÓN: 18/12/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte extends CI_Controller {

    public function __construct() {
      parent::__construct();
      $this->auth_library->sess_validate(true);
      $this->auth_library->mssg_get();
      $this->load->Model("Reporte_model");
      $this->load->Model("Empresa_model");
    }
    
    public function utilidades() {
      date_default_timezone_set("America/Guayaquil");
      $desde = date("Y-m-d"); 
      $hasta = date("Y-m-d"); 
   
      $this->session->set_userdata("tmp_rpt_desde", NULL);
      if ($desde != NULL) { $this->session->set_userdata("tmp_rpt_desde", $desde); } 
      else { $this->session->set_userdata("tmp_rpt_desde", NULL); }
      $this->session->set_userdata("tmp_rpt_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmp_rpt_hasta", $hasta); } 
      else { $this->session->set_userdata("tmp_rpt_hasta", NULL); }

      $data["base_url"] = base_url();
      $data["content"] = "reporte_utilidad";
      $this->load->view("layout", $data);
    }

    public function listadoUtilidad() {

        $desde = $this->session->userdata("tmp_rpt_desde");
        $hasta = $this->session->userdata("tmp_rpt_hasta");
        $registro = $this->Reporte_model->lstutilidad($desde, $hasta); 
        $tabla = "";
        foreach ($registro as $row) {
          @$fec = str_replace('-', '/', $row->fecha); @$fec = date("d/m/Y", strtotime(@$fec));

          $comision = round($row->porciento_comision * $row->precio_total / 100, 2);
          $utilidad_total = $row->utilidad_bruta - $comision;
          $utilidad_porc = ROUND((( ($row->precio_total - $comision) / ($row->costo * $row->cantidad) - 1 ) * 100),2);

          $tabla.='{  
            "fecha":"'.$fec.'", 
            "categoria":"'.$row->categoria.'", 
            "nro_factura":"'.$row->nro_factura.'", 
            "nro_ident":"'.addslashes($row->nro_ident).'", 
            "nom_cliente":"'.addslashes($row->nom_cliente).'", 
            "pro_nombre":"'.addslashes($row->pro_nombre).'", 
            "cantidad":"'.$row->cantidad.'",
            "costo":"'.$row->costo.'", 
            "precio":"'.$row->precio.'", 
            "costo_total":"'.$row->costo_total.'",
            "montoiva":"'.$row->montoiva.'", 
            "precioiva":"'.$row->precioiva.'", 
            "descuento":"'.$row->descuento.'", 
            "precio_total":"'.$row->precio_total.'", 
            "utilidad_bruta":"'.$row->utilidad_bruta.'",
            "comision":"'.$comision.'",
            "utilidad_total":"'.$utilidad_total.'",
            "utilidad_porc":"'.$utilidad_porc.'"
          },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function tmp_rpt_utilidad() {
      $this->session->unset_userdata("tmp_rpt_desde"); 
      $this->session->unset_userdata("tmp_rpt_hasta");
      $fecdesde = $this->input->post("desde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      $fechasta = $this->input->post("hasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));
      $this->session->set_userdata("tmp_rpt_desde", NULL);
      if ($desde != NULL) { $this->session->set_userdata("tmp_rpt_desde", $desde); } 
      else { $this->session->set_userdata("tmp_rpt_desde", NULL); }
      $this->session->set_userdata("tmp_rpt_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmp_rpt_hasta", $hasta); } 
      else { $this->session->set_userdata("tmp_rpt_hasta", NULL); }
      $arr['resu'] = 1;
      print json_encode($arr);
    } 

    public function reporteutilidadXLS(){
      $desde = $this->session->userdata("tmp_rpt_desde");
      $hasta = $this->session->userdata("tmp_rpt_hasta");
      $utilidad = $this->Reporte_model->lstutilidad($desde, $hasta); 
      $this->excel->setActiveSheetIndex(0);
      $this->excel->getActiveSheet()->setTitle('Reporte de Utilidades');
      $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Utilidades');
      $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
      $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
      $this->excel->getActiveSheet()->mergeCells('A1:D1');
      $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

      $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
      $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
      $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
      $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
      $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
      $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(10);

      $this->excel->getActiveSheet()->setCellValue('A3','Fecha');   
      $this->excel->getActiveSheet()->setCellValue('B3','Tipo');   
      $this->excel->getActiveSheet()->setCellValue('C3','#Documento');   
      $this->excel->getActiveSheet()->setCellValue('D3','C.I./R.U.C');   
      $this->excel->getActiveSheet()->setCellValue('E3','Cliente');
      $this->excel->getActiveSheet()->setCellValue('F3','Producto');   
      $this->excel->getActiveSheet()->setCellValue('G3','Cantidad');   
      $this->excel->getActiveSheet()->setCellValue('H3','Costo Unit');   
      $this->excel->getActiveSheet()->setCellValue('I3','Precio');   
      $this->excel->getActiveSheet()->setCellValue('J3','Costo Total');   
      $this->excel->getActiveSheet()->setCellValue('K3','Monto Iva');   
      $this->excel->getActiveSheet()->setCellValue('L3','Precio Iva');   
      $this->excel->getActiveSheet()->setCellValue('M3','Descuento');   
      $this->excel->getActiveSheet()->setCellValue('N3','Precio Total');   
      $this->excel->getActiveSheet()->setCellValue('O3','Utilidad Bruta');   
      $this->excel->getActiveSheet()->setCellValue('P3','Comisión');   
      $this->excel->getActiveSheet()->setCellValue('Q3','Utilidad Neta');   
      $this->excel->getActiveSheet()->setCellValue('R3','Utilidad %');   

      $this->excel->getActiveSheet()->getStyle('A3:R3')->getFont()->setBold(true);

      $costototal = 0;
      $montoiva = 0;
      $desc = 0;
      $preciototal = 0;
      $utilidadbrutatotal = 0;
      $utilidadnetatotal = 0;
      $comisiontotal = 0;

      $fila = 4;
      foreach ($utilidad as $utl) {
        $costototal = $costototal + $utl->costo_total;
        $montoiva = $montoiva + $utl->montoiva;
        $desc = $desc + $utl->descuento;

        $fec = str_replace('-', '/', $utl->fecha); $fec = date("d/m/Y", strtotime($fec));  
        $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $utl->categoria);
        $this->excel->getActiveSheet()->setCellValue('C'.$fila, $utl->nro_factura);

        $this->excel->getActiveSheet()->getStyle('D'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        $this->excel->getActiveSheet()->setCellValue('D'.$fila, $utl->nro_ident);
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, $utl->nom_cliente);
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, $utl->pro_nombre);
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, $utl->cantidad);
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, $utl->costo);
        $this->excel->getActiveSheet()->setCellValue('I'.$fila, $utl->precio);
        $this->excel->getActiveSheet()->setCellValue('J'.$fila, $utl->costo_total);
        $this->excel->getActiveSheet()->setCellValue('K'.$fila, $utl->montoiva);
        $this->excel->getActiveSheet()->setCellValue('L'.$fila, $utl->precioiva);
        $this->excel->getActiveSheet()->setCellValue('M'.$fila, $utl->descuento);
        $this->excel->getActiveSheet()->setCellValue('N'.$fila, $utl->precio_total);
        $this->excel->getActiveSheet()->setCellValue('O'.$fila, $utl->utilidad_bruta);

        $comision = round($utl->porciento_comision * $utl->precio_total / 100, 2);
        $utilidad_total = $utl->utilidad_bruta - $comision;
        $utilidad_porc = ROUND((( ($utl->precio_total - $comision) / ($utl->costo * $utl->cantidad) - 1 ) * 100),2);

        $utilidadbrutatotal += $utl->utilidad_bruta;
        $utilidadnetatotal += $utilidad_total;
        $comisiontotal += $comision;

        $preciototal += ($utl->precio_total - $comision);


        $this->excel->getActiveSheet()->setCellValue('P'.$fila, $comision);
        $this->excel->getActiveSheet()->setCellValue('Q'.$fila, $utilidad_total);
        $this->excel->getActiveSheet()->setCellValue('R'.$fila, $utilidad_porc);

        $fila++;          
      }    

      $fila++;   

      $utltotal = (($preciototal/$costototal) - 1)*100;

      $this->excel->getActiveSheet()->setCellValue('I'.$fila, 'TOTAL');
      $this->excel->getActiveSheet()->setCellValue('J'.$fila, number_format($costototal,2));
      $this->excel->getActiveSheet()->setCellValue('K'.$fila, number_format($montoiva,2));
      $this->excel->getActiveSheet()->setCellValue('M'.$fila, number_format($desc,2));
      $this->excel->getActiveSheet()->setCellValue('N'.$fila, number_format($preciototal,2));
      $this->excel->getActiveSheet()->setCellValue('O'.$fila, number_format($utilidadbrutatotal,2));
      $this->excel->getActiveSheet()->setCellValue('P'.$fila, number_format($comisiontotal,2));
      $this->excel->getActiveSheet()->setCellValue('Q'.$fila, number_format($utilidadnetatotal,2));
      $this->excel->getActiveSheet()->setCellValue('R'.$fila, number_format($utltotal,2));

      $this->excel->getActiveSheet()->getStyle('I'.$fila.':'.'R'.$fila)->getFont()->setBold(true);

        foreach(range('A','R') as $columnID)
        {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }


        $this->excel->getActiveSheet()->freezePane('A4');


      $filename='reporteutilidad.xlsx'; //save our workbook as this file name
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
      header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
      header('Cache-Control: max-age=0'); //no cache
                  
      $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
      $objWriter->save('php://output');        
    }  

    public function reporte_ats() {
      date_default_timezone_set("America/Guayaquil");
      $desde = date("Y-m-d"); 
      $hasta = date("Y-m-d"); 
      $empresas = $this->Empresa_model->lst_empresa(); 
      $data["empresas"] = $empresas;
      $empresa = 0;
      if (count($empresas) > 0){ $empresa = $empresas[0]->id_emp; }
   
      $this->session->set_userdata("tmp_rpt_desde", NULL);
      if ($desde != NULL) { $this->session->set_userdata("tmp_rpt_desde", $desde); } 
      else { $this->session->set_userdata("tmp_rpt_desde", NULL); }
      $this->session->set_userdata("tmp_rpt_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmp_rpt_hasta", $hasta); } 
      else { $this->session->set_userdata("tmp_rpt_hasta", NULL); }
      $this->session->set_userdata("tmp_rpt_emp", NULL);
      if ($empresa != NULL) { $this->session->set_userdata("tmp_rpt_emp", $empresa); } 
      else { $this->session->set_userdata("tmp_rpt_emp", 0); }

      $data["base_url"] = base_url();
      $data["content"] = "reporte_ats";
      $this->load->view("layout", $data);
    }

    public function listado_ats_compra() {

        $desde = $this->session->userdata("tmp_rpt_desde");
        $hasta = $this->session->userdata("tmp_rpt_hasta");
        $empresa = $this->session->userdata("tmp_rpt_emp");
        $registro = $this->Reporte_model->lst_ats_compra($empresa, $desde, $hasta); 
        
        $tabla = "";
        foreach ($registro as $row) {
/*          @$fecreg = str_replace('-', '/', $row->fecharegistro); @$fec = date("d/m/Y", strtotime(@$fec));*/
          @$fecemi = str_replace('-', '/', $row->fechaemision); @$fec = date("d/m/Y", strtotime(@$fec));
          @$fecret = str_replace('-', '/', $row->fecha_retencion); @$fec = date("d/m/Y", strtotime(@$fec));
          $tabla.='{  
            "codsustento":"'.$row->cod_sri_sust_comprobante.'", 
            "codtipoid":"'.$row->codsri_compra.'", 
            "idproveedor":"'.$row->nro_ide_proveedor.'", 
            "nomproveedor":"'.addslashes($row->nom_proveedor).'", 
            "tipodoc":"'.$row->cod_sri_tipo_doc.'", 
            "fecharegistro":"'.$row->fecharegistro.'", 
            "codestabfac":"'.$row->codestabfac.'", 
            "codptoemifac":"'.$row->codptoemifac.'", 
            "secuencialfac":"'.$row->secuencialfac.'", 
            "fechaemision":"'.$fecemi.'", 
            "nro_autorizacion":"'.$row->nro_autorizacion.'", 
            "basenograbaiva":"'.$row->basenograbaiva.'", 
            "baseimponible":"'.$row->baseimponible.'",
            "baseimpgrav":"'.$row->baseimpgrav.'", 
            "montoice":"'.$row->montoice.'", 
            "montoiva":"'.$row->montoiva.'",
            "retiva10":"'.$row->retiva10.'", 
            "retiva20":"'.$row->retiva20.'", 
            "retiva30":"'.$row->retiva30.'", 
            "retiva50":"'.$row->retiva50.'", 
            "retiva70":"'.$row->retiva70.'", 
            "retiva100":"'.$row->retiva100.'", 
            "codretrenta":"'.$row->codretrenta.'", 
            "baseretrenta":"'.$row->baseretrenta.'", 
            "porciento_retencion_renta":"'.$row->porciento_retencion_renta.'", 
            "valor_retencion_renta":"'.$row->valor_retencion_renta.'",
            "codestabret":"'.$row->codestabret.'",
            "codptoemiret":"'.$row->codptoemiret.'",
            "secuencialret":"'.$row->secuencialret.'",
            "autorizacionret":"'.$row->autorizacionret.'",
            "fecha_retencion":"'.$fecret.'"
          },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function tmp_rpt_ats() {
      $this->session->unset_userdata("tmp_rpt_desde"); 
      $this->session->unset_userdata("tmp_rpt_hasta");
      $this->session->unset_userdata("tmp_rpt_emp");
      $fecdesde = $this->input->post("desde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      $fechasta = $this->input->post("hasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));
      $empresa = $this->input->post("empresa");
      $this->session->set_userdata("tmp_rpt_desde", NULL);
      if ($desde != NULL) { $this->session->set_userdata("tmp_rpt_desde", $desde); } 
      else { $this->session->set_userdata("tmp_rpt_desde", NULL); }
      $this->session->set_userdata("tmp_rpt_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmp_rpt_hasta", $hasta); } 
      else { $this->session->set_userdata("tmp_rpt_hasta", NULL); }
      $this->session->set_userdata("tmp_rpt_emp", NULL);
      if ($empresa != NULL) { $this->session->set_userdata("tmp_rpt_emp", $empresa); } 
      else { $this->session->set_userdata("tmp_rpt_emp", NULL); }

      $arr['resu'] = 1;
      print json_encode($arr);
    } 

    public function listado_ats_venta() {

        $desde = $this->session->userdata("tmp_rpt_desde");
        $hasta = $this->session->userdata("tmp_rpt_hasta");
        $empresa = $this->session->userdata("tmp_rpt_emp");
        $registro = $this->Reporte_model->lst_ats_venta($empresa, $desde, $hasta); 
        
        $tabla = "";
        foreach ($registro as $row) {
          $tabla.='{  
            "codsri_venta":"'.$row->codsri_venta.'", 
            "ident_cliente":"'.$row->ident_cliente.'", 
            "nom_cliente":"'.addslashes($row->nom_cliente).'", 
            "parteRel":"'.$row->parteRel.'", 
            "tipocomprobante":"'.$row->tipocomprobante.'", 
            "numeroComprobantes":"'.$row->numeroComprobantes.'", 
            "baseNoGraIva":"'.$row->baseNoGraIva.'", 
            "baseImponible":"'.$row->baseImponible.'", 
            "baseImpGrav":"'.$row->baseImpGrav.'", 
            "montoiva":"'.$row->montoiva.'", 
            "valorRetIva":"'.$row->valorRetIva.'",
            "valorRetRenta":"'.$row->valorRetRenta.'" 
          },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function generar_ats_xml() {

        $desde = $this->session->userdata("tmp_rpt_desde");
        $hasta = $this->session->userdata("tmp_rpt_hasta");
        $empresa = $this->session->userdata("tmp_rpt_emp");
        /*$registro = $this->Reporte_model->lst_ats_venta($desde, $hasta); */

        $sxe = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><iva></iva>', null, false);

        $registro = $this->Reporte_model->lst_ats_general($empresa, $desde, $hasta); 

        $str_cod_estab = $registro->cod_estab;
        $str_ventas = $registro->totalsinimpventas;

        $stranio = $registro->anio; 
        $strmes = str_pad($registro->mes,2, "0", STR_PAD_LEFT);

        $sxe->addChild('TipoIDInformante','R');
        $sxe->addChild('IdInformante',$registro->ruc_emp);
        $sxe->addChild('razonSocial',$registro->raz_soc_emp);
        $sxe->addChild('Anio',$stranio);
        $sxe->addChild('Mes',$strmes);
        $tmpestab = $registro->num_estab;
        if ($tmpestab == 0) { $tmpestab = $registro->num_estab00; }
        $sxe->addChild('numEstabRuc',str_pad($tmpestab, 3, "0",STR_PAD_LEFT));
        $sxe->addChild('totalVentas',$str_ventas);
        $sxe->addChild('codigoOperativo','IVA');

        /* Compras */
        
        $registro = $this->Reporte_model->lst_ats_compra($empresa, $desde, $hasta); 

        $compra = $sxe->addChild('compras');
        
        $facant = 0;
        foreach ($registro as $row) {
          if ($facant != $row->id_comp){
            $facant = $row->id_comp;
            $detalle = $compra->addChild('detalleCompras');
            //$sale->addAttribute('id', 1);
            $detalle->addChild('codSustento', $row->cod_sri_sust_comprobante);
            $detalle->addChild('tpIdProv', $row->codsri_compra);
            $detalle->addChild('idProv', $row->nro_ide_proveedor);
            $detalle->addChild('tipoComprobante', $row->cod_sri_tipo_doc);
            $detalle->addChild('parteRel', $row->relacionada);
            $detalle->addChild('fechaRegistro', $row->fecharegistro);
            $detalle->addChild('establecimiento', $row->codestabfac);
            $detalle->addChild('puntoEmision', $row->codptoemifac);
            $detalle->addChild('secuencial', $row->secuencialfac);
            $detalle->addChild('fechaEmision', $row->fechaemision);
            $detalle->addChild('autorizacion', $row->nro_autorizacion);
            $detalle->addChild('baseNoGraIva', $row->basenograbaiva);
            $detalle->addChild('baseImponible', $row->baseimponible);
            $detalle->addChild('baseImpGrav', $row->baseimpgrav);
            $detalle->addChild('baseImpExe', '0.00');
            $detalle->addChild('montoIce', $row->montoice);
            $detalle->addChild('montoIva', $row->montoiva);
            $detalle->addChild('valRetBien10', $row->retiva10);
            $detalle->addChild('valRetServ20', $row->retiva20);
            $detalle->addChild('valorRetBienes', $row->retiva30);
            $detalle->addChild('valRetServ50', $row->retiva50);
            $detalle->addChild('valorRetServicios', $row->retiva70);
            $detalle->addChild('valRetServ100', $row->retiva100);
            $detalle->addChild('totbasesImpReemb', '0.00');
            $pagoExterior = $detalle->addChild('pagoExterior');
            $pagoExterior->addChild('pagoLocExt', '01');
            $pagoExterior->addChild('paisEfecPago', 'NA');
            $pagoExterior->addChild('aplicConvDobTrib', 'NA');
            $pagoExterior->addChild('pagExtSujRetNorLeg', 'NA');

            if ( ($row->baseimponible + $row->baseimpgrav + $row->montoiva) > 1000 ){
              $formapago = $detalle->addChild('formasDePago');
              $formapago->addChild('formaPago', '01');              
            }
            
            if ($row->codretrenta) {
              $air = $detalle->addChild('air');
            } 

            /*$detalle->addChild('totbasesImpReemb', '0.00');*/

            if ($row->codretrenta) {
              $detalle->addChild('estabRetencion1', $row->codestabret);
              $detalle->addChild('ptoEmiRetencion1', $row->codptoemiret);
              $detalle->addChild('secRetencion1', $row->secuencialret);
              $detalle->addChild('autRetencion1', $row->autorizacionret);
              $detalle->addChild('fechaEmiRet1', $row->fecha_retencion);
            }  

            if ($row->cod_sri_tipo_doc == '04' || $row->cod_sri_tipo_doc == '05') { 
              $detalle->addChild('docModificado', $row->doc_mod_cod_sri_tipo);
              $pto = substr($row->doc_mod_numero, 0, 3);
              $emi = substr($row->doc_mod_numero, 4, 3);
              $sec = substr($row->doc_mod_numero, 8, 9);
              $detalle->addChild('estabModificado', $pto);
              $detalle->addChild('ptoEmiModificado', $emi);
              $detalle->addChild('secModificado', $sec);
              $detalle->addChild('autModificado', $row->doc_mod_autorizacion);
            }
            else{
              $detalle->addChild('docModificado', '000');
              $detalle->addChild('estabModificado', '000');
              $detalle->addChild('ptoEmiModificado', '000');
              $detalle->addChild('secModificado', '0');
              $detalle->addChild('autModificado', '000');
            }
          }  

          if ($row->codretrenta) {
            $detalleret = $air->addChild('detalleAir');
            $detalleret->addChild('codRetAir', $row->codretrenta);
            $detalleret->addChild('baseImpAir', $row->baseretrenta);
            $detalleret->addChild('porcentajeAir', $row->porciento_retencion_renta);
            $detalleret->addChild('valRetAir', $row->valor_retencion_renta);
          }    
        }  

        /* Ventas */
        $registro = $this->Reporte_model->lst_ats_venta($empresa, $desde, $hasta); 
        if (count($registro) > 0){

          $venta = $sxe->addChild('ventas');
         
          foreach ($registro as $row) {
              $detalle = $venta->addChild('detalleVentas');
              $detalle->addChild('tpIdCliente', (substr($row->ident_cliente,0,10) != '9999999999') ? $row->codsri_venta : '05');
              $detalle->addChild('idCliente', (substr($row->ident_cliente,0,10) != '9999999999') ? $row->ident_cliente : '9999999999');
              $detalle->addChild('parteRelVtas', $row->parteRel);
              if ($row->codsri_venta == '06'){
                $detalle->addChild('tipoCliente', '01');
                $detalle->addChild('denoCli', addslashes($row->nom_cliente));
              }
              $detalle->addChild('tipoComprobante', $row->tipocomprobante);
              $detalle->addChild('tipoEmision', 'F');
              $detalle->addChild('numeroComprobantes', $row->numeroComprobantes);
              $detalle->addChild('baseNoGraIva', $row->baseNoGraIva);
              $detalle->addChild('baseImponible', $row->baseImponible);
              $detalle->addChild('baseImpGrav', $row->baseImpGrav);
              $detalle->addChild('montoIva', $row->montoiva);
              $detalle->addChild('montoIce', '0.00');
              $detalle->addChild('valorRetIva', $row->valorRetIva);
              $detalle->addChild('valorRetRenta', $row->valorRetRenta);

              $formapago = $detalle->addChild('formasDePago');
              $pagos = $this->Reporte_model->lst_ats_ventaformago($empresa, $row->id_cliente, $desde, $hasta); 
              foreach ($pagos as $pago) {
                $formapago->addChild('formaPago', $pago->cod_formapago);
              }              

          }  

        /* Total ventas por Establecimiento */
          $estab = $this->Reporte_model->lst_ats_ventaestab($empresa, $desde, $hasta);
          if (count($estab) > 0){
            $ventaporEstab = $sxe->addChild('ventasEstablecimiento');
            foreach ($estab as $ventaestab) {
              $ventaEstab = $ventaporEstab->addChild('ventaEst');
              $ventaEstab->addChild('codEstab', $ventaestab->cod_establecimiento);
              $ventaEstab->addChild('ventasEstab', $ventaestab->totalventas);
              $ventaEstab->addChild('ivaComp', '0.00');
            }

          }
        }



        /* anulados */
       $anulados = $sxe->addChild('anulados');
/*        $registro = $this->Reporte_model->lst_ats_anulados($empresa, $desde, $hasta); 
        foreach ($registro as $row) {
            $detalle = $anulados->addChild('detalleAnulados');
            $detalle->addChild('tipoComprobante', $row->tipocomprobante);
            $detalle->addChild('establecimiento', substr($row->nro_factura, 0,3));
            $detalle->addChild('puntoEmision', substr($row->nro_factura, 4,3));
            $detalle->addChild('secuencialInicio', substr($row->nro_factura, 8,9));
            $detalle->addChild('secuencialFin', substr($row->nro_factura, 8,9));
            $detalle->addChild('autorizacion', $row->numeroautorizacion);
        }        */
/*
        $xml = $sxe->saveXML();   */  
        $xmlfile = "ATS_" . $stranio . "_" . $strmes . ".xml";

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom_xml = dom_import_simplexml($sxe);
        $dom_xml = $dom->importNode($dom_xml, true);
        $dom_xml = $dom->appendChild($dom_xml);
        // DOMDocument method for saving XML file
        //$dom->save('rss.xml');
        // DOMDocument method for outputing as XML string
        $xml = $dom->saveXML();


        $this->load->helper('download');
        force_download($xmlfile, $xml);            

        //$this->output->set_content_type('text/xml');
        //$this->output->set_output($xml);
        
    }

    public function reportecompraATS_XLS(){
      $desde = $this->session->userdata("tmp_rpt_desde");
      $hasta = $this->session->userdata("tmp_rpt_hasta");
      $empresa = $this->session->userdata("tmp_rpt_emp");

      $currencyFormat0 = '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';
      $currencyFormat = '_(#,##0.00_);_( (#,##0.00);_( "-"??_);_(@_)';
      $percentFormat = '_( #,##0_);_( (#,##0);_( "-"??_);_(@_)';
      $textFormat='@';

      $lstcompras = $this->Reporte_model->lst_ats_compra($empresa, $desde, $hasta); 
      $this->excel->setActiveSheetIndex(0);
      $this->excel->getActiveSheet()->setTitle('Reporte de Compra-ATS');
      $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Compra-ATS');
      $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
      $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
      $this->excel->getActiveSheet()->mergeCells('A1:D1');
      $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



      $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
      $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
      $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(17);
      $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(17);
      $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(17);
      $this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(17);
      $this->excel->getActiveSheet()->getColumnDimension('AE')->setWidth(10);

      $this->excel->getActiveSheet()->setCellValue('A3','Sustento');   
      $this->excel->getActiveSheet()->setCellValue('B3','Tipo Id');   
      $this->excel->getActiveSheet()->setCellValue('C3','Idproveedor');   
      $this->excel->getActiveSheet()->setCellValue('D3','Proveedor');   
      $this->excel->getActiveSheet()->setCellValue('E3','Tipo Doc');
      $this->excel->getActiveSheet()->setCellValue('F3','Fecha Reg');   
      $this->excel->getActiveSheet()->setCellValue('G3','Estab.Fac.');   
      $this->excel->getActiveSheet()->setCellValue('H3','Pto.Emi.Fac.');   
      $this->excel->getActiveSheet()->setCellValue('I3','Secuencial');   
      $this->excel->getActiveSheet()->setCellValue('J3','Fecha Emi.');   
      $this->excel->getActiveSheet()->setCellValue('K3','Autoriz.Fac.');   
      $this->excel->getActiveSheet()->setCellValue('L3','BaseNoGrava');   
      $this->excel->getActiveSheet()->setCellValue('M3','BaseImponible');   
      $this->excel->getActiveSheet()->setCellValue('N3','BaseImpGrava');   
      $this->excel->getActiveSheet()->setCellValue('O3','Monto ICE');   
      $this->excel->getActiveSheet()->setCellValue('P3','Monto IVA');   
      $this->excel->getActiveSheet()->setCellValue('Q3','Ret.IVA 10%');   
      $this->excel->getActiveSheet()->setCellValue('R3','Ret.IVA 20%');   
      $this->excel->getActiveSheet()->setCellValue('S3','Ret.IVA 30%');   
      $this->excel->getActiveSheet()->setCellValue('T3','Ret.IVA 50%');   
      $this->excel->getActiveSheet()->setCellValue('U3','Ret.IVA 70%');   
      $this->excel->getActiveSheet()->setCellValue('V3','Ret.IVA 100%');   
      $this->excel->getActiveSheet()->setCellValue('W3','CodigoRetRenta');   
      $this->excel->getActiveSheet()->setCellValue('X3','BaseRetRenta');   
      $this->excel->getActiveSheet()->setCellValue('Y3','%RetRenta');   
      $this->excel->getActiveSheet()->setCellValue('Z3','ValorRetRenta');   
      $this->excel->getActiveSheet()->setCellValue('AA3','Estab.Ret.');   
      $this->excel->getActiveSheet()->setCellValue('AB3','Pto.Emi.Ret.');   
      $this->excel->getActiveSheet()->setCellValue('AC3','Secuenc.Ret.');   
      $this->excel->getActiveSheet()->setCellValue('AD3','Autoriz.Ret.');   
      $this->excel->getActiveSheet()->setCellValue('AE3','Fecha Ret.');   

      $this->excel->getActiveSheet()->getStyle('A3:AE3')->getFont()->setBold(true);

      $basenograbaiva = 0;
      $baseimponible = 0;
      $baseimpgrav = 0;
      $montoice = 0;
      $montoiva = 0;
      $retiva10 = 0;
      $retiva20 = 0;
      $retiva30 = 0;
      $retiva50 = 0;
      $retiva70 = 0;
      $retiva100 = 0;
      $baseretrenta = 0;
      $valor_retencion_renta = 0;

      $fila = 4;
      $filaini = 4;
      foreach ($lstcompras as $row) {

        $fecreg = str_replace('-', '/', $row->fecharegistro); /*$fecreg = date("d/m/Y", strtotime($fecreg));*/
        $fecemi = str_replace('-', '/', $row->fechaemision); /*$fecemi = date("d/m/Y", strtotime($fecemi));*/
        $fecret = str_replace('-', '/', $row->fecha_retencion); /*$fecret = date("d/m/Y", strtotime($fecret));*/
        $this->excel->getActiveSheet()->setCellValue('A'.$fila, $row->cod_sri_sust_comprobante);
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $row->codsri_compra);

        $this->excel->getActiveSheet()->getStyle('C'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $this->excel->getActiveSheet()->setCellValue('C'.$fila, $row->nro_ide_proveedor);

        $this->excel->getActiveSheet()->setCellValue('D'.$fila, addslashes($row->nom_proveedor));
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, $row->cod_sri_tipo_doc);
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, $fecreg);
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, $row->codestabfac);
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, $row->codptoemifac);
        $this->excel->getActiveSheet()->setCellValue('I'.$fila, $row->secuencialfac);
        $this->excel->getActiveSheet()->setCellValue('J'.$fila, $fecemi);
        $this->excel->getActiveSheet()->setCellValue('K'.$fila, $row->nro_autorizacion);
        $this->excel->getActiveSheet()->setCellValue('L'.$fila, $row->basenograbaiva);
        $this->excel->getActiveSheet()->setCellValue('M'.$fila, $row->baseimponible);
        $this->excel->getActiveSheet()->setCellValue('N'.$fila, $row->baseimpgrav);
        $this->excel->getActiveSheet()->setCellValue('O'.$fila, $row->montoice);
        $this->excel->getActiveSheet()->setCellValue('P'.$fila, $row->montoiva);
        $this->excel->getActiveSheet()->setCellValue('Q'.$fila, $row->retiva10);
        $this->excel->getActiveSheet()->setCellValue('R'.$fila, $row->retiva20);
        $this->excel->getActiveSheet()->setCellValue('S'.$fila, $row->retiva30);
        $this->excel->getActiveSheet()->setCellValue('T'.$fila, $row->retiva50);
        $this->excel->getActiveSheet()->setCellValue('U'.$fila, $row->retiva70);
        $this->excel->getActiveSheet()->setCellValue('V'.$fila, $row->retiva100);
        $this->excel->getActiveSheet()->setCellValue('W'.$fila, $row->codretrenta);
        $this->excel->getActiveSheet()->setCellValue('X'.$fila, $row->baseretrenta);
        $this->excel->getActiveSheet()->setCellValue('Y'.$fila, $row->porciento_retencion_renta);
        $this->excel->getActiveSheet()->setCellValue('Z'.$fila, $row->valor_retencion_renta);
        $this->excel->getActiveSheet()->setCellValue('AA'.$fila, $row->codestabret);
        $this->excel->getActiveSheet()->setCellValue('AB'.$fila, $row->codptoemiret);
        $this->excel->getActiveSheet()->setCellValue('AC'.$fila, $row->secuencialret);
        $this->excel->getActiveSheet()->setCellValue('AD'.$fila, $row->autorizacionret);
        $this->excel->getActiveSheet()->setCellValue('AE'.$fila, $fecret);

        $fila++;          
      }    

      $fila++;   

      $this->excel->getActiveSheet()->setCellValue('L' . $fila, '=SUM(L'.($filaini).':L'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('M' . $fila, '=SUM(M'.($filaini).':M'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('N' . $fila, '=SUM(N'.($filaini).':N'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('O' . $fila, '=SUM(O'.($filaini).':O'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('P' . $fila, '=SUM(P'.($filaini).':P'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('Q' . $fila, '=SUM(Q'.($filaini).':Q'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('R' . $fila, '=SUM(R'.($filaini).':R'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('S' . $fila, '=SUM(S'.($filaini).':S'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('T' . $fila, '=SUM(T'.($filaini).':T'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('U' . $fila, '=SUM(U'.($filaini).':U'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('V' . $fila, '=SUM(V'.($filaini).':V'.($fila-1).')');

      $this->excel->getActiveSheet()->setCellValue('X' . $fila, '=SUM(X'.($filaini).':X'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('Z' . $fila, '=SUM(Z'.($filaini).':Z'.($fila-1).')');

/*
      xlsObj.Workbooks(1).Sheets(1).Range(xlsObj.Workbooks(1).Sheets(1).Cells(1, 1), xlsObj.Workbooks(1).Sheets(1).Cells(1, 1)).Select
      xlsObj.Selection.Font.Bold = True
      xlsObj.Selection.Font.Size = 16
*/

      $this->excel->getActiveSheet()->getStyle('L'.$filaini.':V'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);
      $this->excel->getActiveSheet()->getStyle('L'.$fila.':V'.$fila)->getFont()->setBold(true);
      $this->excel->getActiveSheet()->getStyle('X'.$filaini.':X'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);
      $this->excel->getActiveSheet()->getStyle('Z'.$filaini.':Z'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);
      $this->excel->getActiveSheet()->getStyle('X'.$fila.':X'.$fila)->getFont()->setBold(true);
      $this->excel->getActiveSheet()->getStyle('Z'.$fila.':Z'.$fila)->getFont()->setBold(true);

/*
      foreach(range('A','AE') as $columnID)
      {
          $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
      }

      foreach ($this->excel->getActiveSheet()->getColumnDimensions() as $colDim) {
        $colDim->setAutoSize(true);
      }
      $this->excel->getActiveSheet()->calculateColumnWidths();

*/

      $filename='reporteatscompra.xlsx'; //save our workbook as this file name
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
      header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
      header('Cache-Control: max-age=0'); //no cache
                  
      $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
      $objWriter->save('php://output');        
    }  

    public function reporteventaATS_XLS(){
      $desde = $this->session->userdata("tmp_rpt_desde");
      $hasta = $this->session->userdata("tmp_rpt_hasta");
      $empresa = $this->session->userdata("tmp_rpt_emp");

      $currencyFormat0 = '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';
      $currencyFormat = '_(#,##0.00_);_( (#,##0.00);_( "-"??_);_(@_)';
      $percentFormat = '_( #,##0_);_( (#,##0);_( "-"??_);_(@_)';
      $textFormat='@';

      $lstventas = $this->Reporte_model->lst_ats_venta($empresa, $desde, $hasta); 
      $this->excel->setActiveSheetIndex(0);
      $this->excel->getActiveSheet()->setTitle('Reporte de Venta-ATS');
      $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Venta-ATS');
      $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
      $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
      $this->excel->getActiveSheet()->mergeCells('A1:D1');
      $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



      $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
      $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
      $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
      $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
      $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(12);

      $this->excel->getActiveSheet()->setCellValue('A3','Tipo Id');   
      $this->excel->getActiveSheet()->setCellValue('B3','ID Cliente');   
      $this->excel->getActiveSheet()->setCellValue('C3','Cliente');   
      $this->excel->getActiveSheet()->setCellValue('D3','Relacionado');   
      $this->excel->getActiveSheet()->setCellValue('E3','Tipo Comprob');
      $this->excel->getActiveSheet()->setCellValue('F3','Cant.Compro.');   
      $this->excel->getActiveSheet()->setCellValue('G3','BaseNoGravaIVA');   
      $this->excel->getActiveSheet()->setCellValue('H3','BaseImponible');   
      $this->excel->getActiveSheet()->setCellValue('I3','BaseImpGrava');   
      $this->excel->getActiveSheet()->setCellValue('J3','Monto IVA');   
      $this->excel->getActiveSheet()->setCellValue('K3','Valor Ret.IVA');   
      $this->excel->getActiveSheet()->setCellValue('L3','ValorRet.Renta');   

      $this->excel->getActiveSheet()->getStyle('A3:AE3')->getFont()->setBold(true);

      $fila = 4;
      $filaini = 4;
      foreach ($lstventas as $row) {

        @$fecreg = str_replace('-', '/', $row->fecharegistro); @$fecreg = date("d/m/Y", strtotime(@$fecreg));
        @$fecemi = str_replace('-', '/', $row->fechaemision); @$fecemi = date("d/m/Y", strtotime(@$fecemi));
        @$fecret = str_replace('-', '/', $row->fecha_retencion); @$fecret = date("d/m/Y", strtotime(@$fecret));
        $this->excel->getActiveSheet()->setCellValue('A'.$fila, $row->codsri_venta);

        $this->excel->getActiveSheet()->getStyle('B'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $row->ident_cliente);

        $this->excel->getActiveSheet()->setCellValue('C'.$fila, addslashes($row->nom_cliente));
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, $row->parteRel);
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, $row->tipocomprobante);
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, $row->numeroComprobantes);
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, $row->baseNoGraIva);
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, $row->baseImponible);
        $this->excel->getActiveSheet()->setCellValue('I'.$fila, $row->baseImpGrav);
        $this->excel->getActiveSheet()->setCellValue('J'.$fila, $row->montoiva);
        $this->excel->getActiveSheet()->setCellValue('K'.$fila, $row->valorRetIva);
        $this->excel->getActiveSheet()->setCellValue('L'.$fila, $row->valorRetRenta);

        $fila++;          
      }    

      $fila++;   

      $this->excel->getActiveSheet()->setCellValue('F' . $fila, '=SUM(F'.($filaini).':F'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('G' . $fila, '=SUM(G'.($filaini).':G'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('H' . $fila, '=SUM(H'.($filaini).':H'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('I' . $fila, '=SUM(I'.($filaini).':I'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('J' . $fila, '=SUM(J'.($filaini).':J'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('K' . $fila, '=SUM(K'.($filaini).':K'.($fila-1).')');
      $this->excel->getActiveSheet()->setCellValue('L' . $fila, '=SUM(L'.($filaini).':L'.($fila-1).')');

      $this->excel->getActiveSheet()->getStyle('F'.$filaini.':F'.$fila)->getNumberFormat()->setFormatCode($percentFormat);
      $this->excel->getActiveSheet()->getStyle('G'.$filaini.':L'.$fila)->getNumberFormat()->setFormatCode($currencyFormat);
      $this->excel->getActiveSheet()->getStyle('F'.$fila.':L'.$fila)->getFont()->setBold(true);


      $filename='reporteatsventa.xlsx'; //save our workbook as this file name
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
      header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
      header('Cache-Control: max-age=0'); //no cache
                  
      $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
      $objWriter->save('php://output');        
    }  


// Cadillac

    public function index() {
/*     $desde = "2017-10-01";
      $hasta = "2017-10-30";*/
       $desde = date("Y-m-d"); 
      $hasta = date("Y-m-d"); 

      $vcocina = $this->Reporte_model->venta_cocina($desde, $hasta);
      $vbarra = $this->Reporte_model->venta_barra($desde, $hasta);
      $gastos = $this->Reporte_model->resumen_gastos($desde, $hasta);
      $lstcat = $this->Reporte_model->resumen_mantserv($desde, $hasta);

      $data["vcocina"] = $vcocina;
      $data["vbarra"] = $vbarra;
      $data["gastos"] = $gastos;

      $socios = $this->Reporte_model->lst_socio();
      $data["socios"] = $socios;
      $cant = 0;
      foreach ($socios as $socio) {
        $cant++;
      }
      $data["cantsocios"] = $cant;
      $data["lstcat"] = $lstcat;
      $data["base_url"] = base_url();
      $data["content"] = "cierre_mes";
      $this->load->view("layout", $data);
    }

    public function temp_fecha(){
      $fecdesde = $this->input->post("desde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));

      $fechasta = $this->input->post("hasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta)); 

      $this->session->set_userdata("tmpcal_desde", NULL);
      if ($desde != NULL) { $this->session->set_userdata("tmpcal_desde", $desde); } 
      else { $this->session->set_userdata("tmpcal_desde", NULL); }

      $this->session->set_userdata("tmpcal_hasta", NULL);
      if ($hasta != NULL) { $this->session->set_userdata("tmpcal_hasta", $hasta); } 
      else { $this->session->set_userdata("tmpcal_hasta", NULL); } 
      $data = 1;
      print json_encode($data);    
          
    }    

    public function cierre_cal(){


      $desde = $this->session->userdata("tmpcal_desde");
      $hasta = $this->session->userdata("tmpcal_hasta");      

      $lstcat = $this->Reporte_model->resumen_mantserv($desde, $hasta);
      $data["lstcat"] = $lstcat;  
      $data["base_url"] = base_url();
      $this->load->view("cierre_calculo", $data);

    }
 
    public function actualiza(){

        $horad = $this->input->post("horad");
        $horah = $this->input->post("horah");

        $fecdesde = $this->input->post("fdesde");
        $desde = str_replace('/', '-', $fecdesde); 
        $desde = date("Y-m-d", strtotime($desde));
        /* fecha hasta */
        $fechasta = $this->input->post("fhasta");
        $hasta = str_replace('/', '-', $fechasta); 
        $hasta = date("Y-m-d", strtotime($hasta));

        $vdesde = $desde." ".$horad;
        $vhasta = $hasta." ".$horah;   



      $vcocina = $this->Reporte_model->venta_cocina($vdesde, $vhasta);
      $vbarra = $this->Reporte_model->venta_barra($vdesde, $vhasta);
      $gastos = $this->Reporte_model->resumen_gastos($vdesde, $vhasta); 

      $lstcat = $this->Reporte_model->resumen_mantserv($vdesde, $vhasta);
      $data["lstcat"] = $lstcat;           
 
      $data["vcocina"] = $vcocina;
      $data["vbarra"] = $vbarra;
      $data["gastos"] = $gastos;

      print json_encode($data); 

    }

    /* SESION TEMPORAL PARA CARGAR DATOS DE CIERRE */
     public function tmpcierremes() {
        $this->session->unset_userdata("tmp_cierremes"); 
        $cierre['desde'] = $this->input->post("desde");
        $cierre['hasta'] = $this->input->post("hasta");

        $cierre['vcocina'] = $this->input->post("vcocina");
        $cierre['vbarra'] = $this->input->post("vbarra");

        $cierre['ccocina'] = $this->input->post("ccocina");
        $cierre['cbarra'] = $this->input->post("cbarra");
        $cierre['clabor'] = $this->input->post("clabor");

        $cierre['caja'] = $this->input->post("caja");
        $cierre['ahorro'] = $this->input->post("ahorro");

        $cierre['cpub'] = $this->input->post("cpub");
        $cierre['ctv'] = $this->input->post("ctv");
        $cierre['ctvname'] = $this->input->post("ctvname");

        $cierre['ciess'] = $this->input->post("ciess");
        $cierre['carri'] = $this->input->post("carri");
        $cierre['csup'] = $this->input->post("csup");
        $cierre['ctrans'] = $this->input->post("ctrans");
        $cierre['ccont'] = $this->input->post("ccont");
        $cierre['cint'] = $this->input->post("cint");
        $cierre['cmak'] = $this->input->post("cmak");
        $cierre['cpap'] = $this->input->post("cpap");

        $cierre['cfond'] = $this->input->post("cfond");
        $cierre['ctarj'] = $this->input->post("ctarj");
        $cierre['civa'] = $this->input->post("civa");
        $cierre['crent'] = $this->input->post("crent");

        $this->session->set_userdata("tmp_cierremes", NULL);
        if ($cierre != NULL) { $this->session->set_userdata("tmp_cierremes", $cierre); } 
        else { $this->session->set_userdata("tmp_cierremes", NULL); }
        $arr['resu'] = $cierre;
        print json_encode($arr);
    }


    public function parametros_cierre(){
      $lst = $this->Reporte_model->lstpc();  
      $data["lst"] = $lst;
      $data["base_url"] = base_url();
      $data["content"] = "cierre_parametros";
      $this->load->view("layout", $data);        
    }


    public function muestra_categorias(){
        $id = $this->input->post("id");
        $cat = $this->Reporte_model->lst_categorias();
        $data["id"] = $id;
        $data["cat"] = $cat;        
        $data["base_url"] = base_url();
        $this->load->view("cierre_categoria", $data);        
    }    


    public function addcat(){
        $cat = $this->input->post('cat');
        $id = $this->input->post('id');
        $add = $this->Reporte_model->catcadd($id, $cat);
        print json_encode($id);
    }


    public function delcat(){
        $cat = $this->input->post('cat');
        $id = $this->input->post('id');
        $add = $this->Reporte_model->delcadd($id, $cat);
        print json_encode($id);
    }


    public function actualiza_mantenimiento(){
        $lst = $this->Reporte_model->lstpc();  
        $id = 1;
        $data["id"] = $id;
        $data["lst"] = $lst;
        $data["base_url"] = base_url();
        $this->load->view("cierre_tabla", $data);         
    }

    public function actualiza_servicio(){
        $lst = $this->Reporte_model->lstpc(); 
        $id = 2;
        $data["id"] = $id;
        $data["lst"] = $lst;
        $data["base_url"] = base_url();
        $this->load->view("cierre_tabla", $data); 
    }
    

    public function cierremesXLS(){
        $cierre = $this->session->userdata("tmp_cierremes");

        $fecdesde = $cierre['desde'];
        $desde = str_replace('/', '-', $fecdesde); 
        $desde = date("Y-m-d", strtotime($desde));

        $fechasta = $cierre['hasta'];
        $hasta = str_replace('/', '-', $fechasta); 
        $hasta = date("Y-m-d", strtotime($hasta));  

        $lstcat = $this->Reporte_model->resumen_mantserv($desde, $hasta);    

      //  print_r($lstcat); die;

        $vcocina = $cierre['vcocina'];
        $vbarra = $cierre['vbarra'];

        $ccocina = $cierre['ccocina'];
        $cbarra = $cierre['cbarra'];
        $clabor = $cierre['clabor'];

        $cpub = $cierre['cpub'];
        $ctv = $cierre['ctv'];
        $ctvname = $cierre['ctvname'];        

        $ciess = $cierre['ciess'];
        $carri = $cierre['carri'];
        $csup = $cierre['csup'];
        $ctrans = $cierre['ctrans'];
        $ccont = $cierre['ccont'];
        $cint = $cierre['cint'];
        $cmak = $cierre['cmak'];
        $cpap = $cierre['cpap'];        

        $cfond = $cierre['cfond'];
        $ctarj = $cierre['ctarj'];
        $civa = $cierre['civa'];
        $crent = $cierre['crent'];

        $caja = $cierre['caja'];
        $ahorro = $cierre['ahorro'];

        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Cierre');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Cierre del ' . $cierre['desde'] . ' al ' . $cierre['hasta']);
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);

        $currencyFormat = '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';
        $percentFormat = '_( #,##0_);_( (#,##0);_( "-"??_);_(@_)';
        $textFormat='@';//'General','0.00','@'
        $fila = 3;  

        /* P & L PROY */
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'P & L PROY');
        $this->excel->getActiveSheet()->getStyle('A' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, 'MONTO');
        $this->excel->getActiveSheet()->setCellValue('C' . $fila++, '%');
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'FOOD SALES');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $vcocina);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/(B' . $fila . '+B' . ($fila+1) . '),0)');
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'BAR SALES');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $vbarra);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/(B' . $fila . '+B' . ($fila-1) . '),0)');
        $fila++;
        $this->excel->getActiveSheet()->getStyle('A' . $fila . ':C' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'TOTAL SALES');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, '=SUM(B'.($fila-2).':B'.($fila-1).')');
        $filatotventas = $fila;
        $this->excel->getActiveSheet()->setCellValue('C' . $fila++, '100');

        /* MIX COST 27% */
        $fila++;
        $this->excel->getActiveSheet()->getStyle('A' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A' . $fila++, 'MIX COST 27%');
        $filacostoventas = $fila+3;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'FOOD COST 34%');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $ccocina);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filacostoventas . ',0)');
        $this->excel->getActiveSheet()->getStyle('D' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('D' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)'); 

        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'BAR COST 20%');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $cbarra);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filacostoventas . ',0)');
        $this->excel->getActiveSheet()->getStyle('D' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('D' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');   
              
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'LABOR COST 16%');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $clabor);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filacostoventas . ',0)');
        $this->excel->getActiveSheet()->getStyle('D' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('D' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');   
        
        $fila++;
        $this->excel->getActiveSheet()->getStyle('A' . $fila . ':C' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'TOTAL SALES COST');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, '=SUM(B'.($filacostoventas-3).':B'.($filacostoventas-1).')');
        $this->excel->getActiveSheet()->setCellValue('C' . $fila++, '100');

        $fila++;
        /* MANTENIMIENTO */
        foreach ($lstcat as $lm) {
          if($lm->id_parametro == 1){ 
            $totalm = $totalm + $lm->total; 
            $this->excel->getActiveSheet()->setCellValue('A' . $fila, $lm->nom_cat_gas);
            $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
            $this->excel->getActiveSheet()->setCellValue('B' . $fila, $lm->total);
            $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
            $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
            $fila++;
          }

        }
        $this->excel->getActiveSheet()->getStyle('A' . $fila . ':C' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'TOTAL MANTENANCE');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $totalm);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $filatotalmtto = $fila;     
        $fila++;

        $fila++;
        /* SERVICIO */
        foreach ($lstcat as $ls) {
          if($ls->id_parametro == 2){ 
            $totals = $totals + $ls->total; 
            $this->excel->getActiveSheet()->setCellValue('A' . $fila, $ls->nom_cat_gas);
            $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
            $this->excel->getActiveSheet()->setCellValue('B' . $fila, $ls->total);
            $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
            $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
            $fila++;
          }

        }
        $this->excel->getActiveSheet()->getStyle('A' . $fila . ':C' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'TOTAL ENERGY Y PHONE');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $totals);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');  
        $filatotalener = $fila;
        $fila++;

        /* TOTAL A & P */
        $fila++;
        $filatotalpub = $fila+2;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'PUBLICIDAD ARTES');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $cpub);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, $ctvname);
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $ctv);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->getStyle('A' . $fila . ':C' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'TOTAL A & P');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, '=SUM(B'.($filatotalpub-2).':B'.($filatotalpub-1).')');
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila++, '=round(100 * B' . $filatotalpub . '/B' . $filatotventas . ',0)');

        /* TOTAL OTHER COST */

        $fila++;
        $filatotaotro = $fila+8;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'IESS');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $ciess);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'ARRIENDO');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $carri);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'SUPPLIES DE COCINA');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $csup);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'TRANSPORTATION');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $ctrans);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'CONTADOR');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $ccont);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'INTERNET & CABLE');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $cint);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'MAKRO Y GUARDIA');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $cmak);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'PAPELERIA PLASTICOS');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $cpap);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->getStyle('A' . $fila . ':C' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'TOTAL OTHER COST');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, '=SUM(B'.($filatotaotro-8).':B'.($filatotaotro-1).')');
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila++, '=round(100 * B' . $filatotaotro . '/B' . $filatotventas . ',0)');

        $fila++;
        $filatotcred = $fila+4;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'FONDOS DE RESERVA');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $cfond);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'COMISION TARJETAS');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $ctarj);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'RETENCION IVA');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $civa);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'RENTA 2%');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $crent);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila, '=round(100 * B' . $fila . '/B' . $filatotventas . ',0)');
        $fila++;
        $this->excel->getActiveSheet()->getStyle('A' . $fila . ':C' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'TOTAL TARJETAS DE CREDITO');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, '=SUM(B'.($filatotcred-4).':B'.($filatotcred-1).')');
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila++, '=round(100 * B' . $filatotcred . '/B' . $filatotventas . ',0)');

        $fila++;
        $filatotctrl = $fila;
        $this->excel->getActiveSheet()->getStyle('A' . $fila . ':C' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'TOTAL CONTROLABLES');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, '=B'. $filatotalmtto .' + B'. $filatotalener .' + B'. $filatotalpub .' + B'. $filatotaotro .' + B'. $filatotcred);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila++, '=round(100 * B' . $filatotctrl . '/B' . $filatotventas . ',0)');

        $fila++;
        $filatotoper = $fila;
        $this->excel->getActiveSheet()->getStyle('A' . $fila . ':C' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'TOTAL OPERATIVOS');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, '=B'. $filatotctrl .' + B'. $filacostoventas);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila++, '=round(100 * B' . $filatotoper . '/B' . $filatotventas . ',0)');

        $fila++;
        $filapace = $fila;
        $this->excel->getActiveSheet()->getStyle('A' . $fila . ':C' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'P.A.C.E');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, '=B'. $filatotventas .' - B'. $filatotoper);
        $this->excel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
        $this->excel->getActiveSheet()->setCellValue('C' . $fila++, '=round(100 * B' . $filapace . '/B' . $filatotventas . ',0)');

        $fila++;
        $this->excel->getActiveSheet()->getStyle('A' . $fila . ':C' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'CAJA');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $caja);
        $fila++;

        $this->excel->getActiveSheet()->getStyle('A' . $fila . ':C' . $fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A' . $fila, 'AHORRO');
        $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($currencyFormat);
        $this->excel->getActiveSheet()->setCellValue('B' . $fila, $ahorro);
        $filahorro = $fila;

        
        $fila++;

        $socios = $this->Reporte_model->lst_socio();
        $cant = 0;
        foreach ($socios as $socio) {
          $cant++;
        }
        $socios = $this->Reporte_model->lst_socio();
        foreach ($socios as $socio) {
          $fila++;
          $this->excel->getActiveSheet()->getStyle('A' . $fila . ':C' . $fila)->getFont()->setBold(true);
          $this->excel->getActiveSheet()->setCellValue('A' . $fila, $socio->nombre);
          $this->excel->getActiveSheet()->getStyle('B' . $fila)->getNumberFormat()->setFormatCode($percentFormat);
          $this->excel->getActiveSheet()->setCellValue('B' . $fila, '=(B'. $filapace .' - B'. $filahorro.') / '. $cant );
        }

        $filename='cierre.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        //force user to download the Excel file without writing it to server's HD
        ob_end_clean();
        $objWriter->save('php://output');        
    }


}

?>