<?php

/*------------------------------------------------
  ARCHIVO: InfoSRI.php
  DESCRIPCION: Contiene los métodos varios para la interaccion con el SRI.
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'helpers/FirmaElectronica.php');
require(APPPATH.'helpers/XML.php');
require(APPPATH.'helpers/FacturaSRI.php');
require(APPPATH.'helpers/RetencionSRI.php');
require(APPPATH.'helpers/RetencionGastoSRI.php');
require(APPPATH.'helpers/NotaCreditoSRI.php');
require(APPPATH.'helpers/GuiaremisionSRI.php');
require(APPPATH.'helpers/Firma.php');

class InfoSRI extends CI_Controller {

    var $compSRI; //objeto comprobante SRI
    var $mensajerror;

    public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("InfoSRI_model");
        $this->load->Model("Correo_model");
        $this->load->Model("Empresa_model");
        $this->load->Model("Parametros_model");
        $this->compSRI = new FacturaSRI();
    }

    public function index(){
      date_default_timezone_set("America/Guayaquil");

      $desde = $this->session->userdata("tmp_sri_desde");
      $hasta = $this->session->userdata("tmp_sri_hasta");

      if (($desde == NULL) || ($hasta == NULL)){
        $desde = date("Y-m-d"); 
        $hasta = date("Y-m-d"); 
        $this->session->set_userdata("tmp_sri_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_sri_desde", $desde); } 
        else { $this->session->set_userdata("tmp_sri_desde", NULL); }
        $this->session->set_userdata("tmp_sri_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_sri_hasta", $hasta); } 
        else { $this->session->set_userdata("tmp_sri_hasta", NULL); }
      }  

      $data["desde"] = $desde;
      $data["hasta"] = $hasta;
      $data["base_url"] = base_url();
      $data["content"] = "infosri_lst";
      $this->load->view("layout", $data);
    }    

    public function tmp_fecha() {
      $this->session->unset_userdata("tmp_sri_desde"); 
      $this->session->unset_userdata("tmp_sri_hasta");
      $fecdesde = $this->input->post("fdesde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      $fechasta = $this->input->post("fhasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));
      $vdesde = $desde;
      $vhasta = $hasta;        
      $this->session->set_userdata("tmp_sri_desde", NULL);
      if ($vdesde != NULL) { $this->session->set_userdata("tmp_sri_desde", $vdesde);} 
      else { $this->session->set_userdata("tmp_sri_desde", NULL); }
      $this->session->set_userdata("tmp_sri_hasta", NULL);
      if ($vhasta != NULL) { $this->session->set_userdata("tmp_sri_hasta", $vhasta);} 
      else { $this->session->set_userdata("tmp_sri_hasta", NULL);}
      $arr['resu'] = 1;
      print json_encode($arr);
    } 

    public function listadoVentas() {
      date_default_timezone_set("America/Guayaquil");
      $desde = $this->session->userdata("tmp_sri_desde");
      $hasta = $this->session->userdata("tmp_sri_hasta");
      $idusu = $this->session->userdata("sess_id");
      
      $registro = $this->InfoSRI_model->lst_venta_rango($desde, $hasta, $idusu); 
      $tabla = "";
      foreach ($registro as $row) {
        $fec = str_replace('-', '/', $row->fecha); $fec = date("d/m/Y", strtotime($fec));
        $chkenviado = "";
        if (!$row->enviadoemail) { $chkenviado = "checked"; }
        if($row->estado == 'AUTORIZADA'){
          $ver = '<div class=\" text-center \" style=\" width: 70px; \"> <a href=\"#\" title=\"Abrir archivo PDF\" id=\"'.$row->id_venta.'\" class=\"btn bg-navy color-palette btn-xs btn-grad venta_pdf\"><i class=\"fa fa-file-pdf-o\"></i></a> <a href=\"#\" title=\"Enviar por correo\" id=\"'.$row->id_venta.'\" name=\"'.$row->nro_factura.'\" class=\"btn btn-warning color-palette btn-xs btn-grad venta_mail\"><i class=\"fa fa-envelope\"></i></a> <div class=\"pull-right \"><input type=\"checkbox\" class=\"chk_venta\" name=\"'.$row->nro_factura.'\" id=\"'.$row->id_venta.'\" value=\"1\" '.$chkenviado.' title=\"Incluir en envio en lote\" ></div> </div>';
        }else{
          $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Enviar al SRI\" id=\"'.$row->id_venta.'\" name=\"'.$row->nro_factura.'\" class=\"btn btn-success btn-xs btn-grad venta_send\"><i class=\"fa fa-send\"></i></a></div>';
          if ($row->estado == 'NO ENVIADA'){
            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Enviar al SRI\" id=\"'.$row->id_venta.'\" name=\"'.$row->nro_factura.'\" class=\"btn btn-success btn-xs btn-grad venta_send\"><i class=\"fa fa-send\"></i></a></div>';
          }
          else{
            $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Enviar al SRI\" id=\"'.$row->id_venta.'\" name=\"'.$row->nro_factura.'\" class=\"btn btn-success btn-xs btn-grad venta_send\"><i class=\"fa fa-send\"></i></a> <a href=\"#\" title=\"Recuperar comprobante\" id=\"'.$row->id_venta.'\" name=\"'.$row->nro_factura.'\" class=\"btn btn-warning btn-xs btn-grad venta_recuperar\"><i class=\"fa fa-recycle\"></i></a></div>';
          }
        }          
       
        $tabla.='{"fecha":"' . $fec . '",
                  "numero":"' . $row->nro_factura . '",
                  "estado":"' . $row->estado . '",
                  "claveacceso":"' . $row->claveacceso . '",
                  "cliente":"' . addslashes($row->nom_cliente) . '",
                  "baseiva":"' . $row->descsubconiva . '",
                  "basenoiva":"'.$row->descsubsiniva.'",
                  "montoiva":"'.$row->montoiva.'",
                  "descuento":"'.$row->desc_monto.'",
                  "montototal":"'.$row->montototal.'",
                  "correoenviado":"'.$row->enviadoemail.'",
                  "ver":"'.$ver.'"},';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';
    }

    public function listadoRetencionesCompra() {
      date_default_timezone_set("America/Guayaquil");
      $desde = $this->session->userdata("tmp_sri_desde");
      $hasta = $this->session->userdata("tmp_sri_hasta");
      $idusu = $this->session->userdata("sess_id");
      
      $registro = $this->InfoSRI_model->lst_retencioncompra_rango($desde, $hasta, $idusu); 
      $tabla = "";
      foreach ($registro as $row) {
        $fec = str_replace('-', '/', $row->fecha_retencion); $fec = date("d/m/Y", strtotime($fec));
        if($row->estado == 'AUTORIZADA'){
          $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Abrir archivo PDF\" id=\"'.$row->id_comp_ret.'\" name=\"'.$row->tipo.'\" class=\"btn bg-navy color-palette btn-xs btn-grad retencion_pdf\"><i class=\"fa fa-file-pdf-o\"></i></a> <a href=\"#\" title=\"Enviar por correo\" id=\"'.$row->id_comp_ret.'\" name=\"'.$row->tipo.'\" class=\"btn btn-warning color-palette btn-xs btn-grad retencion_mail\"><i class=\"fa fa-envelope\"></i></a> </div>';
        }else{
          $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Enviar al SRI\" id=\"'.$row->id_comp_ret.'\" name=\"'.$row->tipo.'\" class=\"btn btn-success btn-xs btn-grad retencion_send\"><i class=\"fa fa-send\"></i></a></div>';
        }          
       
        $tabla.='{"fecha":"' . $fec . '",
                  "numero":"' . $row->nro_retencion . '",
                  "factura":"' . $row->nro_factura . '",
                  "estado":"' . $row->estado . '",
                  "claveacceso":"' . $row->claveacceso . '",
                  "cliente":"' . addslashes($row->nom_proveedor) . '",
                  "baseretiva":"' . $row->baseretiva . '",
                  "retiva":"'.$row->retiva.'",
                  "baseretrenta":"'.$row->baseretrenta.'",
                  "retrenta":"'.$row->retrenta.'",
                  "ver":"'.$ver.'"},';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';
    }

    public function listadoNotasCredito() {
      date_default_timezone_set("America/Guayaquil");
      $desde = $this->session->userdata("tmp_sri_desde");
      $hasta = $this->session->userdata("tmp_sri_hasta");
      $idusu = $this->session->userdata("sess_id");
      
      $registro = $this->InfoSRI_model->lst_notacredito_rango($desde, $hasta, $idusu); 
      $tabla = "";
      foreach ($registro as $row) {
        $fec = str_replace('-', '/', $row->fecha); $fec = date("d/m/Y", strtotime($fec));
        if($row->estado == 'AUTORIZADA'){
          $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Abrir archivo PDF\" id=\"'.$row->id.'\" class=\"btn bg-navy color-palette btn-xs btn-grad notacredito_pdf\"><i class=\"fa fa-file-pdf-o\"></i></a> <a href=\"#\" title=\"Enviar por correo\" id=\"'.$row->id.'\" class=\"btn btn-warning color-palette btn-xs btn-grad notacredito_mail\"><i class=\"fa fa-envelope\"></i></a> </div>';
        }else{
          $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Enviar al SRI\" id=\"'.$row->id.'\" class=\"btn btn-success btn-xs btn-grad notacredito_send\"><i class=\"fa fa-send\"></i></a></div>';
        }          
       
        $tabla.='{"fecha":"' . $fec . '",
                  "numero":"' . $row->nro_documento . '",
                  "estado":"' . $row->estado . '",
                  "claveacceso":"' . $row->claveacceso . '",
                  "cliente":"' . addslashes($row->nom_cliente) . '",
                  "baseiva":"' . $row->descsubtotaliva . '",
                  "basenoiva":"'.$row->descsubtotalnoiva.'",
                  "montoiva":"'.$row->montoiva.'",
                  "descuento":"'.$row->descuento.'",
                  "montototal":"'.$row->total.'",
                  "ver":"'.$ver.'"},';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';
    }

    public function listadoGuiasRemision() {
      date_default_timezone_set("America/Guayaquil");
      $desde = $this->session->userdata("tmp_sri_desde");
      $hasta = $this->session->userdata("tmp_sri_hasta");
      $idusu = $this->session->userdata("sess_id");
      
      $registro = $this->InfoSRI_model->lst_guiaremision_rango($desde, $hasta, $idusu); 
      $tabla = "";
      foreach ($registro as $row) {
        $fec = str_replace('-', '/', $row->fechaemision); $fec = date("d/m/Y", strtotime($fec));
        $fecini = str_replace('-', '/', $row->fechaini); $fecini = date("d/m/Y", strtotime($fecini));
        $fecfin = str_replace('-', '/', $row->fechafin); $fecfin = date("d/m/Y", strtotime($fecfin));
        if($row->estado == 'AUTORIZADA'){
          $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Abrir archivo PDF\" id=\"'.$row->idguia.'\" class=\"btn bg-navy color-palette btn-xs btn-grad guiaremision_pdf\"><i class=\"fa fa-file-pdf-o\"></i></a> <a href=\"#\" title=\"Enviar por correo\" id=\"'.$row->idguia.'\" class=\"btn btn-warning color-palette btn-xs btn-grad guiaremision_mail\"><i class=\"fa fa-envelope\"></i></a> </div>';
        }else{
          $ver = '<div class=\"text-center \"> <a href=\"#\" title=\"Enviar al SRI\" id=\"'.$row->idguia.'\" class=\"btn btn-success btn-xs btn-grad guiaremision_send\"><i class=\"fa fa-send\"></i></a></div>';
        }          
       
        $secuencial = $row->cod_establecimiento.'-'.$row->cod_puntoemision.'-'.$row->secuencial;

        $strnumdoc = $row->numdocsustento;
        $strnumdoc = substr($strnumdoc, 0, 3).'-'.substr($strnumdoc, 3, 3).'-'.substr($strnumdoc, 6, 9);

        $tabla.='{"fecha":"' . $fec . '",
                  "numero":"' . $secuencial . '",
                  "estado":"' . $row->estado . '",
                  "claveacceso":"' . $row->claveacceso . '",
                  "transportista":"' . addslashes($row->transportista) . '",
                  "fechaini":"' . $fecini . '",
                  "fechafin":"'.$fecfin.'",
                  "comprobventa":"'.$strnumdoc.'",
                  "destinatario":"'.addslashes($row->nom_cliente).'",
                  "ver":"'.$ver.'"},';
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';
    }

    public function Firmar($clave, $token, $contrasena){
        $docGenerados = FCPATH.'public/archivos/generados';
        $docFirmados = FCPATH.'public/archivos/firmados';

        if (file_exists($docGenerados . DIRECTORY_SEPARATOR . $clave . '.xml') === false)
            return array('error' => true, 'mensaje' => 'documento generado no existe');

        $config = array(
            'file' => FCPATH.'public/archivos/token/'.$token,
            'pass' => $contrasena
        );
/*        $config = array(
            'file' => FCPATH.'public/archivos/leidy_melissa_rodriguez_solorzano.p12',
            'pass' => '2200456701Leidy'
        );*/

        $firma = new \Shara\Comprobantes\Firma($config, $clave);

        $resp = $firma->verificarCertPKey();
        //var_dump($resp);

        if ($resp["error"] === true)
            return $resp;

        $xml = file_get_contents($docGenerados. DIRECTORY_SEPARATOR . $clave . '.xml', FILE_USE_INCLUDE_PATH);
       // var_dump($xml);

        $resp = $firma->firmar($xml, $docFirmados);
        //var_dump($resp);

        $signedfile = FCPATH.'public/archivos/firmados/'.$clave.'.xml';
        return file_exists($signedfile);
    }  


    public function Firmar01($comprobante){
      $xmlpath = FCPATH.'/public/archivos/generados/'.$comprobante.'.xml';
      $xmlobj = simplexml_load_file($xmlpath);
      $xml = $xmlobj->asXML();

      $firma_config = ['file'=>FCPATH.'/public/archivos/leidy_melissa_rodriguez_solorzano.p12', 'pass'=>'2200456701Leidy'];
      $firma = new \sasco\LibreDTE\FirmaElectronica($firma_config);
      $res = $firma->signXML($xml);  

      $sxe = new SimpleXMLElement($res, null, false);
      $dom = new DOMDocument('1.0');
      $dom->preserveWhiteSpace = false;
      $dom->formatOutput = true;
      $dom_xml = dom_import_simplexml($sxe);
      $dom_xml = $dom->importNode($dom_xml, true);
      $dom_xml = $dom->appendChild($dom_xml);
      // DOMDocument method for saving XML file
      $signedfile = FCPATH.'/public/archivos/firmados/'.$comprobante.'.xml';
      $dom->save($signedfile);
/*
      $test = $firma->verifyXML($res);
  */    
      return file_exists($signedfile);
    }

    public function Firmar00($comprobante){
      /*$xml = $this->input->post("xml");*/
      $xml = $this->CargarXML();
      /*$xml = FCPATH.'/public/archivos/generados/0106201601170461720600110010030000000010383104315.xml';*/
      /*$doc->load('data/ICT.xml'); */

      $firma_config = ['file'=>FCPATH.'/public/archivos/leidy_melissa_rodriguez_solorzano.p12', 'pass'=>'2200456701Leidy'];
      $firma = new \sasco\LibreDTE\FirmaElectronica($firma_config);
      $res = $firma->signXML($xml);  

      $sxe = new SimpleXMLElement($res, null, false);
      $dom = new DOMDocument('1.0');
      $dom->preserveWhiteSpace = false;
      $dom->formatOutput = true;
      $dom_xml = dom_import_simplexml($sxe);
      $dom_xml = $dom->importNode($dom_xml, true);
      $dom_xml = $dom->appendChild($dom_xml);
      // DOMDocument method for saving XML file
      $signedfile = FCPATH.'/public/archivos/firmados/'.$comprobante.'.xml';
      $dom->save($signedfile);
/*
      $test = $firma->verifyXML($res);
  */    
      return file_exists($signedfile);
    }    

    public function FirmaJava($claveAcceso, $token, $contrasena){
      $mypath = FCPATH;

      $params = Array($claveAcceso,
                      $mypath. "public/archivos/generados/",
            				  $mypath. "public/archivos/token/".$token,
            				  $contrasena,
            				  $mypath. "public/archivos/firmados/");
      /*$params = Array($claveAcceso,
                      $mypath. "public/archivos/generados/",
                      $mypath. "public/archivos/leidy_melissa_rodriguez_solorzano.p12",
                      "2200456701Leidy",
                      $mypath. "public/archivos/firmados/");*/

      //$tmpstr="java -jar ".$mypath."public/archivos/firmaSRI.jar " . implode (' ', $params);
      //var_dump($tmpstr);die;
      shell_exec("java -jar ".$mypath."public/archivos/firmaSRI.jar " . implode (' ', $params));
      //shell_exec($mypath."public/archivos/firmaSRI.jar " . implode (' ', $params));
      $signedfile = $mypath.'public/archivos/firmados/'.$claveAcceso.'.xml';
      return file_exists($signedfile);
    }  

    public function CargarComprobanteSRI(){
      $tipocomprobante = $this->session->userdata("tmp_tipocmpsri");
      $id = $this->session->userdata("tmp_cmpsri_id");
      if ($tipocomprobante == '') {$tipocomprobante = 1;}

      $this->compSRI = null;

      switch ($tipocomprobante) {
        case 2:
          $this->compSRI = new RetencionSRI();
          break;
        case 3:
          $this->compSRI = new RetencionGastoSRI();
          break;
        case 4:
          $this->compSRI = new NotaCreditoSRI();
          break;
        case 5:
          $this->compSRI = new GuiaremisionSRI();
          break;
        default:
          $this->compSRI = new FacturaSRI();
          break;
      }
      if ($this->compSRI != null){
        $this->compSRI->objmodel = $this->InfoSRI_model;
        $this->compSRI->id = $id;
        $this->compSRI->tipo = $tipocomprobante;
      }  
    }  

    public function tmp_tipocmpsri(){
      $id = $this->input->post("tipocomprobante");
      if ($id == '') {$id = 1;}
      $this->session->unset_userdata("tmp_tipocmpsri"); 
      $this->session->set_userdata("tmp_tipocmpsri", NULL);
      if ($id != NULL) { $this->session->set_userdata("tmp_tipocmpsri", $id); } 
      else { $this->session->set_userdata("tmp_tipocmpsri", NULL); }

      $id = $this->input->post("id");
      $this->session->unset_userdata("tmp_cmpsri_id"); 
      $this->session->set_userdata("tmp_cmpsri_id", NULL);
      if ($id != NULL) { $this->session->set_userdata("tmp_cmpsri_id", $id); } 
      else { $this->session->set_userdata("tmp_cmpsri_id", NULL); }

      $arr['resu'] = 1;
      print json_encode($arr);
    }  

    public function EnviarSRI(){

      $this->mensajerror = "";
      $autorizado = 0;

      //Obtener id de factura
      $id = $this->input->post("id");
//      $id = 64;
      $this->CargarComprobanteSRI();

      if ($this->compSRI != null){
        $this->compSRI->id = $id;
      }
      else {
        return false;
      }

      //Generar XML de comprobante
      $comprobante = $this->compSRI->CrearArchivoXML(); /*$this->crearFacturaXML($id);*/
      //var_dump($comprobante);//die;
      if (trim($comprobante) != ''){

        $objfac = $this->compSRI->datoscomprobante();
        $objemp = $this->Empresa_model->sel_emp_id($objfac->id_empresa);

        //Firmar XML de factura
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
          $archivofirmado = $this->FirmaJava($comprobante, $objemp->tokenfirma, $objemp->contrasena);
        }
        else{
          $archivofirmado = $this->Firmar($comprobante, $objemp->tokenfirma, $objemp->contrasena);
        }
        //if ($this->Firmar($comprobante)) {
        //if ($this->FirmaJava($comprobante)) {

        $signedfile = FCPATH.'public/archivos/firmados/'.$comprobante.'.xml';
        $archivofirmado = file_exists($signedfile);

        if ($archivofirmado == true) {        


          $strtmpambiente = substr($comprobante, 23, 1);

          //$comprobante ='2810201801220045670100110010010000008522161344618';
          $res = $this->recibirWs($comprobante, $strtmpambiente);
          //var_dump($res);

          if ($res["estado"] == "RECIBIDA"){

            $this->compSRI->actualiza_enviado($comprobante);
            //print_r("actualizo enviado");

            $res = $this->autorizarWs($comprobante, $strtmpambiente);
            //var_dump($res);
            if ($res == "AUTORIZADO"){
              $autorizado = 1;
              //var_dump($res);
              $fechaautorizo = $this->crearRide($comprobante);  
              $fechaautorizo = str_replace('/','-', $fechaautorizo); 
              $fechaautorizo = date("Y-m-d H:i:s", strtotime($fechaautorizo)); 
              
              $this->compSRI->actualiza_autorizado($fechaautorizo);

              //if (trim($objfac->correo_cliente) != ''){}

              $correoautosri = $this->Parametros_model->sel_habilitacorreoautosri();
              if ($correoautosri == 1){
                $this->enviarCorreoClienteSRI($objfac, $comprobante);
              }
            }
            else{
              $this->compSRI->actualiza_rechazado($comprobante);
            }
          }
          else{
            //$this->mensajerror = "No se pudo enviar el comprobante.";
            $this->mensajerror = $res["mensajesWs"];
            //var_dump($res);
          }
        } 
        else{
          $this->mensajerror = "No se pudo firmar el comprobante.";
        } 
      }  
      $arr['resu'] = $autorizado;
      if ($autorizado != 1){
        $arr['mensaje'] = $this->mensajerror;
      }
      print json_encode($arr);
    }  

    public function recibirWs($comprobante, $tipoAmbiente = 1){
      $url = "";
      switch($tipoAmbiente){
        case 1:
          $url = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";
          break;
        case 2:  
          $url = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";
          break;
      }

      $xmlpath = FCPATH.'/public/archivos/firmados/'.$comprobante.'.xml';
      $xmlobj = simplexml_load_file($xmlpath);
      $comprobante = $xmlobj->asXML();

      $comprobante=str_replace("\r\n", "\n", $comprobante);

      $params = array("xml"=>$comprobante);

      $client = new SoapClient($url);
/*
      $opts = array(
            'ssl' => array('ciphers'=>'RC4-SHA', 'verify_peer'=>false, 'verify_peer_name'=>false)
      );
      // SOAP 1.2 client
      $nparams = array ('encoding' => 'UTF-8', 
                       'verifypeer' => false, 
                       'verifyhost' => false, 
                       'soap_version' => SOAP_1_2, 
                       'trace' => 1, 
                       'exceptions' => 1, 
                       "connection_timeout" => 180, 
                       'stream_context' => stream_context_create($opts) );
     
      $client = new SoapClient($url, $nparams);
*/
      $result = $client->validarComprobante($params);

      if ($result){
        if ($result->RespuestaRecepcionComprobante){
          $result->isRecibida = $result->RespuestaRecepcionComprobante->estado === "RECIBIDA" ? true : false;
          if ($result->RespuestaRecepcionComprobante->comprobantes){
            if (isset($result->RespuestaRecepcionComprobante->comprobantes->comprobante)){
              $comprobantes = $result->RespuestaRecepcionComprobante->comprobantes->comprobante;
              $result->RespuestaRecepcionComprobante->comprobantes = array();
              if (is_array($comprobantes)){
                $result->RespuestaRecepcionComprobante->comprobantes = $comprobantes;
              }
              else {
                $result->RespuestaRecepcionComprobante->comprobantes[0] = $comprobantes;
              }

              $result->RespuestaRecepcionComprobante->mensajesWs = array();
              $result->RespuestaRecepcionComprobante->mensajesDb = array();

              for($idxComprobante=0; $idxComprobante < count($result->RespuestaRecepcionComprobante->comprobantes); $idxComprobante++){
                $comprobante = $result->RespuestaRecepcionComprobante->comprobantes[$idxComprobante];
                if ($comprobante->mensajes){
                  if (isset($comprobante->mensajes->mensaje)){
                    $mensajes = $comprobante->mensajes->mensaje;
                    $comprobante->mensajes = array();
                    if (is_array($mensajes)){
                      $comprobante->mensajes = $mensajes;
                    }
                    else {
                      $comprobante->mensajes[0] = $mensajes;
                    }
                  }

                  for ($idxMensaje=0; $idxMensaje < count($comprobante->mensajes); $idxMensaje++){
                    $item = $comprobante->mensajes[$idxMensaje];
                    $informacionAdicional = isset($item->informacionAdicional) ? "\n".$item->informacionAdicional : "";
                    $mensaje = $item->mensaje;
                    $identificador = $item->identificador;
                    $tipo = $item->tipo;
                    $mensajeDB = trim("({$tipo}-{$identificador}){$mensaje}{$informacionAdicional}");
                    $mensajesWs = trim("({$tipo}-{$identificador}){$mensaje}{$informacionAdicional}");
                    array_push($result->RespuestaRecepcionComprobante->mensajesDb, $mensajeDB);
                    array_push($result->RespuestaRecepcionComprobante->mensajesWs, $mensajesWs);
                    $comprobante->mensajes[$idxMensaje] = (array)$comprobante->mensajes[$idxMensaje];
                  }
                }

                $result->RespuestaRecepcionComprobante->comprobantes[$idxComprobante] = (array)$result->RespuestaRecepcionComprobante->comprobantes[$idxComprobante];
              }
            }

            $isRecibida = $result->isRecibida;
            $result = (array)$result->RespuestaRecepcionComprobante;
            $result["isRecibida"] = $isRecibida;
          }
        }
      }
      return $result;
    }

    public function autorizarWs($claveAcceso, $tipoAmbiente=1){
      $url = "";

      switch($tipoAmbiente){
        case 1:
          $url = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
          break;
        case 2:  
          $url = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
          break;
      }

      $params = array("claveAccesoComprobante" => $claveAcceso);
      $client = new soapClient($url);
      $result = $client->autorizacionComprobante($params);
      if ($result){
        if($result->RespuestaAutorizacionComprobante->numeroComprobantes <> 0){
          if($result->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == "AUTORIZADO"){

            if($this->crearXMLAutorizacion($result, $claveAcceso) == true){
                                 
                 // modelUpdateNroAutInvoice($invoiceId ,$obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion);                    
              return "AUTORIZADO";                    
            }
            else{
              return "ERROR # Documento XML no se creo";
            }	
          }    
          else {
            if ($this->crearXMLNoAutorizacion($result, $claveAcceso) == true){
              return "NOAUTORIZADO";
            }
            else
            {
                return "ERROR # Documento XML no se creo";
            }
          }
        }  
        else {
          return "ERROR# El documento $claveAcceso  no fue envia al SRI";
        }  
      }
    } 
    function crearXMLAutorizacion($obj_xml, $claveAcceso)
    {   
        $xml=new DomDocument("1.0","UTF-8");
        $raiz=$xml->createElement('autorizacion');
        $raiz = $xml->appendChild($raiz);
        $estado = $xml->createElement("estado",$obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado);
        $raiz->appendChild($estado);

        $numeroAutorizacion = $xml->createElement("numeroAutorizacion",$obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion);
        $raiz->appendChild($numeroAutorizacion);

        $fechaAutorizacion = $xml->createElement("fechaAutorizacion",$obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion);
        $fechaAutorizacion->setAttribute("class","fechaAutorizacion");
        $raiz->appendChild($fechaAutorizacion);
        $tipoAmbiente = $xml->createElement("ambiente",$obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente);
        $raiz->appendChild($tipoAmbiente);
        $comprobante = $xml->createElement("comprobante",$obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante);
        $raiz->appendChild($comprobante);
     
        $path = FCPATH.'/public/archivos/autorizados/'.$claveAcceso.'.xml';

        if($xml->save($path))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function crearXMLNoAutorizacion($obj_xml, $claveAcceso){
      //var_dump($obj_xml);die;
      $xml = new DomDocument("1.0","UTF-8");
      $raiz=$xml->createElement('autorizacion');
      $raiz = $xml->appendChild($raiz);
      if(isset($obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)){
        $estado = $xml->createElement("estado",$obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado);
        $raiz->appendChild($estado);
      }  
      
      if(isset($obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion))
      {
          $numeroAutorizacion = $xml->createElement("numeroAutorizacion",$obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion);
          $raiz->appendChild($numeroAutorizacion);
      }
        
      if(isset($obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion)){
        $fechaAutorizacion = $xml->createElement("fechaAutorizacion",$obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion);
        $fechaAutorizacion->setAttribute("class","fechaAutorizacion");
        $raiz->appendChild($fechaAutorizacion);
      }  
      
      if(isset($obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante))
      {
        $comprobante = $xml->createElement("comprobante",$obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante);
        $raiz->appendChild($comprobante);
      }  
      
      $mensajes = $xml->createElement("mensajes");
      $mensajes = $raiz->appendChild($mensajes);
      $count = count($obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje);
      $mensaje = $xml->createElement("mensaje");
      $mensaje = $mensajes->appendChild($mensaje);

      $arrmensaje = $obj_xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje;
      $arrmensaje = (array)$arrmensaje;

      for($i=0; $i < $count;$i++){
        $mensajeSub = $xml->createElement("mensaje");
        $mensajeSub = $mensaje->appendChild($mensajeSub);
        $identificador =$xml->createElement("identificador",$arrmensaje["identificador"]);
        $mensajeSub->appendChild($identificador);
        $mensajeText =$xml->createElement("mensaje",$arrmensaje["mensaje"]);

        $this->mensajerror = $this->mensajerror." ".$arrmensaje["mensaje"];

        $mensajeSub->appendChild($mensajeText);
        $tipo =$xml->createElement("tipo",$arrmensaje["tipo"]);
        $mensajeSub->appendChild($tipo);
      }
      $path = FCPATH.'/public/archivos/rechazados/'.$claveAcceso.'.xml';

      if($xml->save($path))
            {
                return true;
            }
            else
            {
                return false;
            }
    }        


    function crearRide($claveAcceso){
      $this->load->library('T_cpdf');

      $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      // set document information
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor('Pavel Oramas');
      $pdf->SetTitle('Comprobante-SRI');
      $pdf->SetSubject('Comprobante-SRI');
      $pdf->SetKeywords('TCPDF, PDF, comprobante, sri');
      
      // remove default header/footer
      $pdf->setPrintHeader(false);
      $pdf->setPrintFooter(false);
      
      // set default monospaced font
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      
      // set margins
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      
      // set auto page breaks
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      
      // set image scale factor
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      
      // set some language-dependent strings (optional)
      if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
          require_once(dirname(__FILE__).'/lang/eng.php');
          $pdf->setLanguageArray($l);
      }
      
      // ---------------------------------------------------------
      
      // set font
      $pdf->SetFont('times', 'BI', 20);
      $pdf->SetFont('helvetica', '', 8);


      // add a page
      $pdf->AddPage();

      $objXml = simplexml_load_file(FCPATH.'/public/archivos/autorizados/'.$claveAcceso.'.xml');        

      $strhtml = $this->compSRI->CreaDivParaPDF($objXml, $pdf)  /*$this->CreaDivParaPDF($objXml, $pdf)*/;

      $pdf->writeHTML($strhtml, true, 0, true, 0);

      $pdf->Output(FCPATH.'/public/archivos/ride/'.$claveAcceso.'.pdf', 'F');
//      $pdf->Output('My-File-Name.pdf', 'I');
      return $objXml->fechaAutorizacion;
    }

    function AbrirPDF(){
      $this->CargarComprobanteSRI();
      $claveAcceso = $this->compSRI->sel_claveacceso(); 
      ob_start();

      $this->load->library('T_cpdf');

      $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      // set document information
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor('FACTUFÁCIL');
      $pdf->SetTitle('Comprobante-SRI');
      $pdf->SetSubject('Comprobante-SRI');
      $pdf->SetKeywords('TCPDF, PDF, comprobante, sri');
      
      // remove default header/footer
      $pdf->setPrintHeader(false);
      $pdf->setPrintFooter(false);
      
      // set default monospaced font
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      
      // set margins
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      
      // set auto page breaks
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      
      // set image scale factor
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      
      // set some language-dependent strings (optional)
      if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
          require_once(dirname(__FILE__).'/lang/eng.php');
          $pdf->setLanguageArray($l);
      }
      
      // ---------------------------------------------------------
      
      // set font
      $pdf->SetFont('times', 'BI', 20);
      $pdf->SetFont('helvetica', '', 8);


      // add a page
      $pdf->AddPage();

      $objXml = simplexml_load_file(FCPATH.'/public/archivos/autorizados/'.$claveAcceso.'.xml');        

      $strhtml = $this->compSRI->CreaDivParaPDF($objXml, $pdf);  

      $pdf->writeHTML($strhtml, true, 0, true, 0);

      $pdf->Output($claveAcceso.'.pdf', 'I');
    }    
    
    public function enviarCorreoClienteSRI($objfac, $claveAcceso) {
      $correo = $this->Correo_model->env_correo($objfac->id_empresa);

      if (trim($objfac->correo_cliente) == ''){
        return 0;
      }

      $mailcli = $objfac->correo_cliente;
      $mailcli = str_replace(';', ',', $mailcli);
      $mailcli = str_replace(' ', '', $mailcli);

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
      $this->email->subject($objfac->nom_emp.' - Comprobante electrónico');
      $this->email->attach(FCPATH.'/public/archivos/autorizados/'.$claveAcceso.'.xml');

      $docride = FCPATH.'public/archivos/ride';
      if (file_exists($docride . DIRECTORY_SEPARATOR . $claveAcceso . '.pdf') === false){
        $this->crearRide($claveAcceso);  
      }

      $this->email->attach(FCPATH.'/public/archivos/ride/'.$claveAcceso.'.pdf');
      $this->email->message($this->compSRI->obtenerMensajeCorreo($objfac));
      $this->email->to($mailcli /*'info@factufacil-ec.com'*/);

      try { 
        if($this->email->send(FALSE)){
          $this->compSRI->actualiza_correoenviado(); 
          $res = 1;
        }else {
          $res = 0;
        }
      } 
      catch( Exception $e ) 
      { 
        $res = 0;
      }          

      return $res;
    }  

    public function EnviarCorreo(){
      $this->CargarComprobanteSRI();
      $claveAcceso = $this->compSRI->sel_claveacceso(); 

      $objfac = $this->compSRI->datoscomprobante();
      $res = $this->enviarCorreoClienteSRI($objfac, $claveAcceso);

      $arr['resu'] = $res;
      print json_encode($arr);
    }  
    

    public function edit_recuperacion(){
        $id = $this->input->post("id");
        $data["id"] = $id;

        $this->CargarComprobanteSRI();

        if ($this->compSRI != null){
          $this->compSRI->id = $id;
        }
 
        $strtipo = "";
        switch ($this->compSRI->tipo) {
          case 2:
            $strtipo = "RETENCION COMPRA"; break;
          case 3:
            $strtipo = "RETENCION GASTO"; break;
          case 4:
            $strtipo = "NOTA CREDITO"; break;
          case 5:
            $strtipo = "GUIA REMISION"; break;
          default:
            $strtipo = "FACTURA"; break;
        }
        $data["tipoid"] = $this->compSRI->tipo;
        $data["tipo"] = $strtipo;

        $objfac = $this->compSRI->datoscomprobante();

        $data["numero"] = $objfac->cod_establecimiento .'-'. $objfac->cod_puntoemision .'-'. $objfac->nro_factura;
        $data["claveacceso"] = $objfac->claveacceso;

        $data["base_url"] = base_url();
        $this->load->view("infosri_recuperar", $data);
    }

    public function recuperarComprobante(){

      $this->mensajerror = "";
      $autorizado = 0;

      //Obtener id de factura
      $id = $this->input->post("id");
//      $id = 64;
      $this->CargarComprobanteSRI();

      if ($this->compSRI != null){
        $this->compSRI->id = $id;
      }
      else {
        return false;
      }

      //Obtener clave acceso
      $comprobante = $this->input->post("clave");
      $strtmpambiente = substr($comprobante, 23, 1);

      //var_dump($comprobante);//die;
      if (trim($comprobante) != ''){
        $res = $this->autorizarWs($comprobante, $strtmpambiente);
        //var_dump($res);
        if ($res == "AUTORIZADO"){
          $autorizado = 1;
          //var_dump($res);
          $fechaautorizo = $this->crearRide($comprobante);  
          $fechaautorizo = str_replace('/','-', $fechaautorizo); 
          $fechaautorizo = date("Y-m-d H:i:s", strtotime($fechaautorizo)); 
          
          $this->compSRI->actualiza_enviado($comprobante);
          $this->compSRI->actualiza_autorizado($fechaautorizo);

          //if (trim($objfac->correo_cliente) != ''){}

          $this->enviarCorreoClienteSRI($objfac, $comprobante);
        }
        else{
          $this->compSRI->actualiza_rechazado($comprobante);
        }
      }  
      $arr['resu'] = $autorizado;
      if ($autorizado != 1){
        $arr['mensaje'] = $this->mensajerror;
      }
      print json_encode($arr);
    }  

}


?>