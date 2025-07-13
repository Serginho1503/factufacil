<?php
/*******************************************************************************
* RetencionGasto SRI                                                              *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    04-Nov-2018                                                         *
* Author:  Pavel Oramas                                                        *
*******************************************************************************/

require_once(APPPATH.'helpers/RetencionSRI.php');

class RetencionGastoSRI extends RetencionSRI
{

  function CrearArchivoXML()
  {
        /*********** DATOS DEL COMPROBANTE **********/   
        
        $registro = $this->objmodel->datosRetencionGastos($this->id);
        $tipoAmbiente = $registro->ambiente_retencion;
        $emision = "1";
        $razonS = $registro->raz_soc_emp;
        $nombreC = $registro->nom_emp;
        $rucEmp = $registro->ruc_emp; 
        $razoncliente = $registro->nom_proveedor;
        $ruccliente = $registro->nro_ide_proveedor; 
        $tipoidcliente = $registro->codsri_venta;
        $modelConfig = "";
        $cod_establecimiento = $registro->cod_establecimiento;
        $cod_pto_emi = $registro->cod_puntoemision;
        $sec_fact = $registro->nro_retencion;
        $direccionMatriz = $registro->dir_emp;
        $subtotal = "";
        $dirEstab = $registro->dir_sucursal;
        if ($registro->obligadocontabilidad == 1){
            $obligadoContab = "SI";
        }
        else{
            $obligadoContab = "NO";
        }    
        if ($tipoAmbiente == 1){
          $fechaEmisionFac = date("Y-m-d");
        }
        else{
          $fechaEmisionFac = $registro->fecha_retencion;              
        }
            
        //  Creando la clave de acceso			
        $nombreArch = $this->claveAcceso("07",$rucEmp, $fechaEmisionFac, $tipoAmbiente, $cod_establecimiento,
                                         $cod_pto_emi, $sec_fact, $emision);

        $digVerifi = $this->modulo11($this->invertirCadena($nombreArch));
        $nombreArch = $nombreArch.$digVerifi;

        $xml = new DomDocument("1.0","UTF-8");

        $raiz = $xml->createElement('comprobanteRetencion');
        $raiz->setAttribute("id","comprobante");
        $raiz->setAttribute("version","1.0.0");
        $raiz = $xml->appendChild($raiz);

        $infoTribu=$xml->createElement("infoTributaria");
        $infoTribu=$raiz->appendChild($infoTribu);

        $ambiente=$xml->createElement("ambiente", $tipoAmbiente);            
        $ambiente=$infoTribu->appendChild($ambiente);

        $tipoEmision=$xml->createElement("tipoEmision", $emision);
        $tipoEmision=$infoTribu->appendChild($tipoEmision);

        $razonSocial=$xml->createElement("razonSocial", trim($razonS));
        $razonSocial=$infoTribu->appendChild($razonSocial);

        $nombreComercial=$xml->createElement("nombreComercial", trim($nombreC));
        $nombreComercial=$infoTribu->appendChild($nombreComercial);

        $ruc=$xml->createElement("ruc", trim($rucEmp));
        $ruc=$infoTribu->appendChild($ruc);

        $claveAcceso = $xml->createElement("claveAcceso", $nombreArch);
        $claveAcceso = $infoTribu->appendChild($claveAcceso);

        $codDoc = $xml->createElement("codDoc", "07");
        $codDoc = $infoTribu->appendChild($codDoc);

        $estab = $xml->createElement("estab", $cod_establecimiento);            
        $estab=$infoTribu->appendChild($estab);

        $ptoEmi=$xml->createElement("ptoEmi", $cod_pto_emi);            
        $ptoEmi=$infoTribu->appendChild($ptoEmi);
        
        // Revisar el incremental de serie de la factura 
        //$secuencial=$xml->createElement("secuencial",str_pad(($sec_fact)+1,9,"0",STR_PAD_LEFT));
        $secuencial=$xml->createElement("secuencial",str_pad(($sec_fact),9,"0",STR_PAD_LEFT));
        $secuencial=$infoTribu->appendChild($secuencial);

        $dirMatriz=$xml->createElement("dirMatriz", trim($direccionMatriz));
        $dirMatriz=$infoTribu->appendChild($dirMatriz);

        $infoFactura=$xml->createElement("infoCompRetencion");
        $infoFactura=$raiz->appendChild($infoFactura);

        //$fechaEmision=$xml->createElement("fechaEmision", date("d/m/Y"));
        $strfecha = explode("-", $fechaEmisionFac);
        $strfecha = str_pad($strfecha[2],2,"0",STR_PAD_LEFT).'/'.str_pad($strfecha[1],2,"0",STR_PAD_LEFT).'/'.$strfecha[0];
        $fechaEmision=$xml->createElement("fechaEmision", $strfecha);
        $fechaEmision=$infoFactura->appendChild($fechaEmision);

        $dirEstablecimiento=$xml->createElement("dirEstablecimiento",trim(utf8_encode($dirEstab)));
        $dirEstablecimiento=$infoFactura->appendChild($dirEstablecimiento);

        $obligadoContabilidad=$xml->createElement("obligadoContabilidad", $obligadoContab);
        $obligadoContabilidad=$infoFactura->appendChild($obligadoContabilidad);

        $tipoIdentificacionComprador=$xml->createElement("tipoIdentificacionSujetoRetenido", $tipoidcliente);       
        $tipoIdentificacionComprador = $infoFactura->appendChild($tipoIdentificacionComprador);
        
        // DATOS DEL CLIENTE 
        $razonSocialComprador=$xml->createElement("razonSocialSujetoRetenido", trim($razoncliente));
        $razonSocialComprador=$infoFactura->appendChild($razonSocialComprador);

        $identificacionComprador=$xml->createElement("identificacionSujetoRetenido", $ruccliente);       
        $identificacionComprador = $infoFactura->appendChild($identificacionComprador);

        $tmpfecha = explode("-", $fechaEmisionFac);
        $periodo = $tmpfecha[1]."/".$tmpfecha[0];        
        $periodofiscal=$xml->createElement("periodoFiscal", $periodo);       
        $periodofiscal = $infoFactura->appendChild($periodofiscal);

        $totalConImpuestos = $xml->createElement("impuestos");
        $totalConImpuestos = $raiz->appendChild($totalConImpuestos);
        
        // BUCLE DE Retencion IVA  
        $detalles = $this->objmodel->detalleRetencionGastosIVA($this->id);
        foreach($detalles as $detalle){
            $impuesto = $xml->createElement("impuesto");
            $totalImpuesto = $totalConImpuestos->appendChild($impuesto);
    
            $node = $xml->createElement("codigo","2");
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("codigoRetencion",$detalle->codigo);
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("baseImponible",$detalle->base_retencion_iva);
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("porcentajeRetener",$detalle->porciento_retencion_iva);
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("valorRetenido",$detalle->valor_retencion_iva);
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("codDocSustento","01");
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("numDocSustento",str_replace('-', '', $detalle->nro_factura));
            $totalImpuesto->appendChild($node);

            $strfecha = explode("-", $detalle->fecha);
            $strfecha = str_pad($strfecha[2],2,"0",STR_PAD_LEFT).'/'.str_pad($strfecha[1],2,"0",STR_PAD_LEFT).'/'.$strfecha[0];
            $node = $xml->createElement("fechaEmisionDocSustento",$strfecha);
            $totalImpuesto->appendChild($node);
        }

        // BUCLE DE Retencion Renta  
        $detalles = $this->objmodel->detalleRetencionGastosRenta($this->id);
        foreach($detalles as $detalle){
            $impuesto = $xml->createElement("impuesto");
            $totalImpuesto = $totalConImpuestos->appendChild($impuesto);
    
            $node = $xml->createElement("codigo","1");
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("codigoRetencion",$detalle->cod_cto_retencion);
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("baseImponible",$detalle->base_retencion);
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("porcentajeRetener",$detalle->porciento_retencion_renta);
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("valorRetenido",$detalle->valor_retencion_renta);
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("codDocSustento","01");
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("numDocSustento",str_replace('-', '', $detalle->nro_factura));
            $totalImpuesto->appendChild($node);

            $strfecha = explode("-", $detalle->fecha);
            $strfecha = str_pad($strfecha[2],2,"0",STR_PAD_LEFT).'/'.str_pad($strfecha[1],2,"0",STR_PAD_LEFT).'/'.$strfecha[0];
            $node = $xml->createElement("fechaEmisionDocSustento",$strfecha);
            $totalImpuesto->appendChild($node);
        }
     
        $xml->formatOut=true;
        //Header('Content-type: text/xml');
        $strings_xml = $xml->saveXML();
        $strings_xml=str_replace("\r\n", "\n", $strings_xml);
        $xml2 = new DOMDocument();
        $xml2->loadXML($strings_xml);
        
        if($xml2->save(FCPATH."public/archivos/generados/$nombreArch.xml"))
        {
            return $nombreArch;
        }
        else
        {
            return "ERROR";
        }

  }
/*
  function CreaDivParaPDF($objXml="", $pdf){
    $xmlFact = simplexml_load_string($objXml->comprobante);    

    $divstr='<html>';
      $divstr.='<body>';

      $divstr.='<table cellspacing="0" cellpadding="1" border="0">';
      $divstr.='<tr>';

        $divstr.='<td>';

        $divstr.='<div class="cuadro_izq" style="float:left; width:350px; ">';
            $divstr.='<div class="cuadro_logo" style="height:140px; padding-top:60px; ">';
              $divstr.='<img src="D:/xampp/htdocs/eqweb3/public/img/perfil.jpg" alt="" />';
            $divstr.='</div>';
            $divstr.='<div style="border:1px solid #000; padding:5px; border-radius:5px; margin-right:5px; font-size:12px  ">';
              $divstr.='<div class="row"> '.$xmlFact->infoTributaria->razonSocial.'</div>';
              $divstr.='<div class="row" style="font-size:11px ;margin: 15px 0px;"> '.$xmlFact->infoTributaria->nombreComercial.'</div>';      
              $divstr.='<div class="row"  style="font-size:10px;margin: 10px 0px;"> Dirección Matriz: '.$xmlFact->infoTributaria->dirMatriz.'</div>';
              $divstr.='<div class="row"  style="font-size:10px ;margin:10px 0px;"> Dirección Sucursal: '.$xmlFact->infoFactura->dirEstablecimiento.'</div>';
              $divstr.='<div class="row"  style="font-size:11px;margin: 12px 0px; "> OBLIGADO A LLEVAR CONTABILIDAD: '.$xmlFact->infoFactura->obligadoContabilidad.' </div>';
            $divstr.='</div>';
        $divstr.='</div>';

        $divstr.='</td>';

        $divstr.='<td>';

        $divstr.='<div class="cuadro_der" style="float:left;width:300px; border:1px solid #000; padding:5px; border-radius:5px ">';
          $divstr.='<div class="row" style="font-size:15px;margin: 15px 0px;"> R.U.C: '.$xmlFact->infoTributaria->ruc.' </div>';
          $divstr.='<div class="row" style="font-size:16px;margin: 15px 0px;"> COMPROBANTE DE RETENCIÓN </div>';

          $facNumber = substr($xmlFact->infoTributaria->claveAcceso,24,15);
          $facNumber = substr($facNumber,0,3).'-'.substr($facNumber,3,3).'-'.substr($facNumber,6,9);   

          $divstr.='<div class="row" style="font-size:13px;margin: 6px 0px;"> No. '.$facNumber.'</div>';
          $divstr.='<div class="row" style="font-size:14px;margin: 10px 0px;"> NÚMERO DE AUTORIZACÓN </div>';
          $divstr.='<div class="row" style="font-size:11px;margin: 6px 0px;"> '.$objXml->numeroAutorizacion.'</div>';
          $divstr.='<div class="row" style="font-size:12px;margin: 10px 0px;"> <div class="column"> FECHA Y HORA DE AUTORIZACION </div> '.$objXml->fechaAutorizacion.'</div>';

          $ambiente = ($xmlFact->infoTributaria->ambiente == 1) ? "PRUEBAS":"PRODUCCION";
          $divstr.='<div class="row" style="font-size:14px;margin: 10px 0px;"> AMBIENTE: '.$ambiente.'</div>';
          
          $tipoemision = ($xmlFact->infoTributaria->tipoEmision == 1) ? "NORMAL":"INDISPONIBILIDAD DEL SISTEMA";
          $divstr.='<div class="row" style="font-size:14px;margin: 10px 0px;"> EMISION: '.$tipoemision.'</div>';

          $divstr.='<div class="row" style="font-size:16px;margin: 2px 0px;"> CLAVE DE ACCESO</div>';
          $divstr.='<div class="row" style="font-size:13px;margin: 0px 0px;"> ';	        

            $facNumber = substr($xmlFact->infoTributaria->claveAcceso,0,49);
            $params = $pdf->serializeTCPDFtagParameters(array($facNumber, 'CODE11', '', '', 85, 25, 0.4, array('position'=>'S', 'border'=>false, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>12, 'stretchtext'=>4), 'N'));

            $divstr .= '<tcpdf method="write1DBarcode" params="'.$params.'" />';

          $divstr.='</div>';

        $divstr.='</div>';

        $divstr.='</td>';

      $divstr.='</tr>';
      $divstr.='</table>';

          
      $divstr.='<div clas="datos_cliente" style="clear:left;border:1px solid #000; margin:12px 0px">';
        $divstr.='<table>';
          $divstr.='<tr>';
          $divstr.='<td style="font-size:14px;">Razón Social / Nombres y Apellidos: '.$xmlFact->infoCompRetencion->razonSocialSujetoRetenido.'</td>';
          $divstr.='</tr>';
          $divstr.='<tr>';
          $divstr.='<td style="font-size:14px;">Identificación: '.$xmlFact->infoCompRetencion->identificacionSujetoRetenido.'</td>';
          $divstr.='</tr>';
          $divstr.='<tr>';
          $divstr.='<td style="font-size:14px;">Fecha Emisión: '.$xmlFact->infoCompRetencion->fechaEmision.'</td>';
          $divstr.='</tr>';
        $divstr.='</table>';
      $divstr.='</div>';          
         
      $divstr.='<table style="border: 1px solid #000; border-collapse: collapse;" >';
      $divstr.='<tr>';
          $divstr.='<th width="75" style="font-size:10px;padding:0px 3px;border: 1px solid #000;text-align:center;font-weight: bold;">Comprobante</th>';
          $divstr.='<th width="103" style="font-size:10px;padding:0px 3px;border: 1px solid #000;text-align:center;font-weight: bold;">Número</th>';
          $divstr.='<th width="65" style="font-size:10px;padding:0px 3px;border: 1px solid #000;text-align:center;font-weight: bold;">Fecha<br/>Emisión</th>';
          $divstr.='<th width="65" style="font-size:10px;border: 1px solid #000;text-align:center;font-weight: bold;">Ejercicio<br/>Fiscal</th>';
          $divstr.='<th width="85" style="font-size:10px;border: 1px solid #000;text-align:center;font-weight: bold;">Base Imponible<br/>para Retención</th>';
          $divstr.='<th width="85" style="font-size:10px;border: 1px solid #000;text-align:center;font-weight: bold;">Impuesto</th>';
          $divstr.='<th width="75" style="font-size:10px;border: 1px solid #000;text-align:center;font-weight: bold;">Porcentaje<br/>Retención</th>';
          $divstr.='<th width="85" style="font-size:10px;border: 1px solid #000;text-align:center;font-weight: bold;">Valor<br/>Retenido</th>';
        $divstr.='</tr>';
      
      for ($i=0; $i < count($xmlFact->impuestos->impuesto); $i++) 
      {
          $divstr.='<tr>';
          $divstr.='<td style="font-size:10px;border: 1px solid #000;text-align:center; ">FACTURA</td>';
          $divstr.='<td style="font-size:10px;border: 1px solid #000;text-align:center; ">'.$xmlFact->impuestos->impuesto[$i]->numDocSustento.'</td>';
          $divstr.='<td style="font-size:10px;border: 1px solid #000;text-align:center; ">'.$xmlFact->impuestos->impuesto[$i]->fechaEmisionDocSustento.'</td>';
          $divstr.='<td style="font-size:10px;border: 1px solid #000;text-align:center; ">'.$xmlFact->infoCompRetencion->periodoFiscal.'</td>';
          $divstr.='<td style="font-size:10px;border: 1px solid #000;text-align:right; ">'.$xmlFact->impuestos->impuesto[$i]->baseImponible.'</td>';
          $strhtml = ($xmlFact->impuestos->impuesto[$i]->codigo == '2') ? 'IVA' : 'RENTA';   
          $divstr.='<td style="font-size:10px;border: 1px solid #000;text-align:center ; ">'.$strhtml.'</td>';
          $divstr.='<td style="font-size:10px;border: 1px solid #000;text-align:right; ">'.$xmlFact->impuestos->impuesto[$i]->porcentajeRetener.'</td>';
          $divstr.='<td style="font-size:10px;border: 1px solid #000;text-align:right; ">'.$xmlFact->impuestos->impuesto[$i]->valorRetenido.'</td>';
          $divstr.='</tr>';
      }            
      $divstr.='</table>';

      $divstr.='</body>';
    $divstr.='</html>';
      
    return $divstr;    
  }

  function obtenerMensajeCorreo($objfac){
    $msg = "<!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
            </head>
            <body>
            <div style='margin: 0 auto; background-color: white; width:800px; overflow-y:auto;'>
                <h4>Estimado(a) cliente  $objfac->nom_cliente </h4>
                <hr>
                <p>
                    <strong>Nos complace informarle que su documento electrónico ha sido generado con el siguiente detalle:</strong>
                    <br/>
                    <br/>
                    <strong>Tipo de documento: FACTURA</strong> 
                    <br/>
                    <strong>Documento electrónico No: </strong>$objfac->cod_establecimiento-$objfac->cod_puntoemision-$objfac->nro_factura
                    <br/>
                    <br/>
                    <strong>Adjunto encontrará su documento electrónico.</strong>
                    <br/>
                </p>
                <br>

            </div>
        </body>
     </html>";
    return $msg;
  }  
*/
  function actualiza_enviado($claveacceso){
    $this->objmodel->actualiza_enviadoRetencionGastos($this->id, $claveacceso);
  }

  function actualiza_autorizado($fechaautorizo){
    $this->objmodel->actualiza_autorizadoRetencionGastos($this->id, $fechaautorizo);
  }

  function actualiza_rechazado($claveacceso){
    $this->objmodel->actualiza_rechazadoRetencionGastos($this->id, $claveacceso);
  }

  function sel_claveacceso(){
    return $this->objmodel->sel_claveaccesoRetencionGastos($this->id);
  }

  function datoscomprobante(){
    return $this->objmodel->datosRetencionGastos($this->id);
  }

  function actualiza_correoenviado(){
    $this->objmodel->actualiza_correoenviadoRetGasto($this->id);
  }

}

?>
