<?php
/*******************************************************************************
* GuiaremisionSRI                                                              *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    04-Nov-2018                                                         *
* Author:  Pavel Oramas                                                        *
*******************************************************************************/

require_once(APPPATH.'helpers/ComprobanteSRI.php');

class GuiaremisionSRI extends ComprobanteSRI
{

  function CrearArchivoXML()
  {
        /*********** DATOS DE LA guia **********/   
        
        $registro = $this->objmodel->datosGuiaremision($this->id);
        $tipoAmbiente = $registro->ambiente_guia;
        $emision = "1";
        $razonS = $registro->raz_soc_emp;
        $nombreC = $registro->nom_emp;
        $rucEmp = $registro->ruc_emp; 
        $razonSocialTransportista = $registro->razontransportista;
        $rucTransportista = $registro->ructransportista; 
        $tipoIdentificacionTransportista = $registro->codidtransportista;
        $razonSocialDestinatario = $registro->nom_cliente;
        $identificacionDestinatario = $registro->ident_cliente; 
        $tipoidcliente = $registro->codiddestinatario;
        $modelConfig = "";
        $cod_establecimiento = $registro->cod_establecimiento;
        $cod_pto_emi = $registro->cod_puntoemision;
        $sec_fact = $registro->secuencial;
        $direccionMatriz = $registro->dir_emp;
        $subtotal = "";
        $dirEstab = $registro->dir_sucursal;
        $dirPartida = $registro->dirpartida;
        if ($registro->obligadocontabilidad == 1){
            $obligadoContab = "SI";
        }
        else{
            $obligadoContab = "NO";
        }    
        /*if ($tipoAmbiente == 1){
          $fechaEmisionFac = date("Y-m-d");
        }
        else{
          $fechaEmisionFac = $registro->fechaemision;              
        }*/
        $fechaEmisionFac = $registro->fechaini;              
           
        //  Creando la clave de acceso			
        $nombreArch = $this->claveAcceso("06",$rucEmp, $fechaEmisionFac, $tipoAmbiente, $cod_establecimiento,
                                  $cod_pto_emi, $sec_fact, $emision);

        $digVerifi = $this->modulo11($this->invertirCadena($nombreArch));
        $nombreArch = $nombreArch.$digVerifi;

        $xml = new DomDocument("1.0","UTF-8");

        $raiz = $xml->createElement('guiaRemision');
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

        $codDoc = $xml->createElement("codDoc", "06");
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

        $infoFactura=$xml->createElement("infoGuiaRemision");
        $infoFactura=$raiz->appendChild($infoFactura);

        //$fechaEmision=$xml->createElement("fechaEmision", date("d/m/Y"));
/*        $strfecha = explode("-", $fechaEmisionFac);
        $strfecha = str_pad($strfecha[2],2,"0",STR_PAD_LEFT).'/'.str_pad($strfecha[1],2,"0",STR_PAD_LEFT).'/'.$strfecha[0];
        $fechaEmision=$xml->createElement("fechaEmision", $strfecha);
        $fechaEmision=$infoFactura->appendChild($fechaEmision);
*/
        $dirEstablecimiento=$xml->createElement("dirEstablecimiento",trim($dirEstab));
        $dirEstablecimiento=$infoFactura->appendChild($dirEstablecimiento);

        $dirPartida=$xml->createElement("dirPartida",trim($dirPartida));
        $infoFactura->appendChild($dirPartida);

        $razonSocialTransportista=$xml->createElement("razonSocialTransportista",trim($razonSocialTransportista));
        $infoFactura->appendChild($razonSocialTransportista);

        $tipoIdentificacionTransportista=$xml->createElement("tipoIdentificacionTransportista",trim($tipoIdentificacionTransportista));
        $infoFactura->appendChild($tipoIdentificacionTransportista);

        $rucTransportista=$xml->createElement("rucTransportista",trim($rucTransportista));
        $infoFactura->appendChild($rucTransportista);

        $obligadoContabilidad=$xml->createElement("obligadoContabilidad", $obligadoContab);
        $infoFactura->appendChild($obligadoContabilidad);

        $strfecha = explode("-", $registro->fechaini);
        $strdia = trim(substr($strfecha[2],0,2));
        $strfecha = str_pad($strdia,2,"0",STR_PAD_LEFT).'/'.str_pad($strfecha[1],2,"0",STR_PAD_LEFT).'/'.$strfecha[0];        
        $node=$xml->createElement("fechaIniTransporte", $strfecha);
        $infoFactura->appendChild($node);

        $strfecha = explode("-", $registro->fechafin);
        $strdia = trim(substr($strfecha[2],0,2));
        $strfecha = str_pad($strdia,2,"0",STR_PAD_LEFT).'/'.str_pad($strfecha[1],2,"0",STR_PAD_LEFT).'/'.$strfecha[0];        
        $node=$xml->createElement("fechaFinTransporte", $strfecha);
        $infoFactura->appendChild($node);

        $node=$xml->createElement("placa", $registro->placa);
        $infoFactura->appendChild($node);

        $infoDestinos=$xml->createElement("destinatarios");
        $infoDestinos=$raiz->appendChild($infoDestinos);

        $infoDestinatario=$xml->createElement("destinatario");
        $infoDestinatario=$infoDestinos->appendChild($infoDestinatario);

        $identificacionDestinatario=$xml->createElement("identificacionDestinatario", $identificacionDestinatario);       
        $infoDestinatario->appendChild($identificacionDestinatario);

        $razonSocialDestinatario=$xml->createElement("razonSocialDestinatario", $razonSocialDestinatario);       
        $infoDestinatario->appendChild($razonSocialDestinatario);

        $dirDestinatario=$xml->createElement("dirDestinatario", $registro->dirllegada);       
        $infoDestinatario->appendChild($dirDestinatario);

        $motivoTraslado=$xml->createElement("motivoTraslado", $registro->motivo);       
        $infoDestinatario->appendChild($motivoTraslado);

        if ($registro->docaduanero != ""){
            $docAduaneroUnico=$xml->createElement("docAduaneroUnico", $registro->docaduanero);       
            $infoDestinatario->appendChild($docAduaneroUnico);
        }    

        if ($registro->codestabdestino != ""){
            $codEstabDestino=$xml->createElement("codEstabDestino", $registro->codestabdestino);       
            $infoDestinatario->appendChild($codEstabDestino);
        }    

        if ($registro->ruta != ""){
            $ruta=$xml->createElement("ruta", $registro->ruta);       
            $infoDestinatario->appendChild($ruta);
        }    

        $codDocSustento=$xml->createElement("codDocSustento", $registro->coddocsustento);       
        $infoDestinatario->appendChild($codDocSustento);

        $strnumdoc = $registro->numdocsustento;
        $strnumdoc = substr($strnumdoc, 0, 3).'-'.substr($strnumdoc, 3, 3).'-'.substr($strnumdoc, 6, 9);
        $numDocSustento=$xml->createElement("numDocSustento", $strnumdoc);       
        $infoDestinatario->appendChild($numDocSustento);

        $numAutDocSustento=$xml->createElement("numAutDocSustento", $registro->numautdocsustento);       
        $infoDestinatario->appendChild($numAutDocSustento);

        $strfecha = explode("-", $registro->fechaemidocsustento);
        $strdia = trim(substr($strfecha[2],0,2));
        $strfecha = str_pad($strdia,2,"0",STR_PAD_LEFT).'/'.str_pad($strfecha[1],2,"0",STR_PAD_LEFT).'/'.$strfecha[0];        
        $fechaEmisionDocSustento=$xml->createElement("fechaEmisionDocSustento", $strfecha);       
        $infoDestinatario->appendChild($fechaEmisionDocSustento);
              
        //  DETALLE FACTURA 
        $detalles=$xml->createElement("detalles");
        $detalles=$infoDestinatario->appendChild($detalles);
        
        // BUCLE DETALLE 
        $objdetalles = $this->objmodel->datosguiaremisiondetalle($this->id);
        foreach($objdetalles as $producto)
        {               
            $detalle = $xml->createElement("detalle");
            $detalle = $detalles->appendChild($detalle);

            if(trim($producto->codigointerno) != '')
                $codigoproducto = trim($producto->codigointerno);
            else{
                $codigoproducto = '1';
            }


            $codigoPrincipal = $xml->createElement("codigoInterno", trim($codigoproducto));
            $codigoPrincipal = $detalle->appendChild($codigoPrincipal );
            $codigoAuxiliar = $xml->createElement("codigoAdicional", trim($codigoproducto));
            $codigoAuxiliar = $detalle->appendChild($codigoAuxiliar);
            $descripcion = $xml->createElement("descripcion",trim($producto->descripcion));
            $descripcion = $detalle->appendChild($descripcion);
            $cantidad = $xml->createElement("cantidad",number_format($producto->cantidad,2,'.',''));
            $cantidad = $detalle->appendChild($cantidad);
        }

        //  ADICIONAL
        $infoAdicional=$xml->createElement("infoAdicional");
        $infoAdicional= $raiz->appendChild($infoAdicional);
        $stradic = trim($registro->telefonos_cliente) != '' ? trim($registro->telefonos_cliente) : ' ';
        $campoAdicional = $xml->createElement("campoAdicional",$stradic);
        $campoAdicional->setAttribute("nombre","Telefono");
        $campoAdicional= $infoAdicional->appendChild($campoAdicional);
        $stradic = trim($registro->correo_cliente) != '' ? trim($registro->correo_cliente) : ' ';
        $campoAdicional = $xml->createElement("campoAdicional",$stradic);
        $campoAdicional->setAttribute("nombre","Correo");
        $campoAdicional= $infoAdicional->appendChild($campoAdicional);
      
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

  function CreaDivParaPDF($objXml="", $pdf){
    $xmlFact = simplexml_load_string($objXml->comprobante);    

    $divstr='<html>';
      $divstr.='<body>';

      $divstr.='<table cellspacing="0" cellpadding="1" border="0">';
      $divstr.='<tr>';

        $divstr.='<td>';

        $divstr.='<div class="cuadro_izq" style="float:left; width:350px; ">';
            $divstr.='<div class="cuadro_logo" style="height:140px; padding-top:60px; ">';

              $imagen = $this->objmodel->sel_logoempresaguia($this->id);
              if (trim($imagen) != ''){
                $divstr.='<img src="'.FCPATH.'/public/img/sucursal/'.$imagen.'" alt="" />';
                //$divstr.='<img src="'.FCPATH.'/public/img/empresa/'.$imagen.'" alt="" />';
              }  

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
          $divstr.='<div class="row" style="font-size:16px;margin: 15px 0px;"> GUÍA DE REMISIÓN </div>';

          $facNumber = substr($xmlFact->infoTributaria->claveAcceso,24,15);
          $facNumber = substr($facNumber,0,3).'-'.substr($facNumber,3,3).'-'.substr($facNumber,6,9);   

          $divstr.='<div class="row" style="font-size:13px;margin: 6px 0px;"> No. '.$facNumber.'</div>';
          $divstr.='<div class="row" style="font-size:14px;margin: 10px 0px;"> NÚMERO DE AUTORIZACIÓN </div>';
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

          
      $divstr.='<div clas="datos_trans" style="clear:left;border:1px solid #000; margin:12px 0px">';
        $divstr.='<table>';
          $divstr.='<tr>';
          $divstr.='<td style="font-size:14px;">Identificación(Transportista): '.$xmlFact->infoGuiaRemision->rucTransportista.'</td>';
          $divstr.='</tr>';
          $divstr.='<tr>';
          $divstr.='<td style="font-size:14px;">Razón Social / Nombres y Apellidos: '.$xmlFact->infoGuiaRemision->razonSocialTransportista.'</td>';
          $divstr.='</tr>';
          $divstr.='<tr>';
          $divstr.='<td style="font-size:14px;">Placa: '.$xmlFact->infoGuiaRemision->placa.'</td>';
          $divstr.='</tr>';
          $divstr.='<tr>';
          $divstr.='<td style="font-size:14px;">Punto de Partida: '.$xmlFact->infoGuiaRemision->dirPartida.'</td>';
          $divstr.='</tr>';
          $divstr.='<tr>';
          $divstr.='<td>';
          $divstr.='<table>';
          $divstr.='<tr>';
          $divstr.='<td style="font-size:14px;">Fecha inicio Transporte: '.$xmlFact->infoGuiaRemision->fechaIniTransporte.' </td>';
          $divstr.='<td style="font-size:14px;">Fecha fin Transporte: '.$xmlFact->infoGuiaRemision->fechaFinTransporte.' </td>';
          $divstr.='</tr>';
          $divstr.='</table>';
          $divstr.='</td>';
          $divstr.='</tr>';
        $divstr.='</table>';
      $divstr.='</div>';          

      for ($i=0; $i < count($xmlFact->destinatarios->destinatario); $i++){ 

        $divstr.='<div clas="datos_cliente" style="clear:left;border:1px solid #000; margin:12px 0px">';
            $divstr.='<table>';
            $divstr.='<tr>';
                $divstr.='<td>';
                $divstr.='<table>';
                    $divstr.='<tr>';
                    $divstr.='<td style="font-size:14px;">Comprobante de Venta: FACTURA </td>';
                    $divstr.='<td style="font-size:14px;">'.$xmlFact->destinatarios->destinatario[$i]->numDocSustento.' </td>';
                    $divstr.='<td style="font-size:14px;">Fecha de Emisión: '.$xmlFact->destinatarios->destinatario[$i]->fechaEmisionDocSustento.' </td>';
                    $divstr.='</tr>';
                $divstr.='</table>';
                $divstr.='</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:14px;">Número de Autorización: '.$xmlFact->destinatarios->destinatario[$i]->numAutDocSustento.'</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:14px;">Motivo Traslado: '.$xmlFact->destinatarios->destinatario[$i]->motivoTraslado.'</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:14px;">Destino(Punto de llegada): '.$xmlFact->destinatarios->destinatario[$i]->dirDestinatario.'</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:14px;">Identificación(Destinatario): '.$xmlFact->destinatarios->destinatario[$i]->identificacionDestinatario.'</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:14px;">Razón Social / Nombres y Apellidos: '.$xmlFact->destinatarios->destinatario[$i]->razonSocialDestinatario.'</td>';
            $divstr.='</tr>';
            if ($xmlFact->destinatarios->destinatario[$i]->docAduaneroUnico){
                $divstr.='<tr>';
                $divstr.='<td style="font-size:14px;">Documento Aduanero: '.$xmlFact->destinatarios->destinatario[$i]->docAduaneroUnico.'</td>';
                $divstr.='</tr>'; 
            }
            if ($xmlFact->destinatarios->destinatario[$i]->codEstabDestino){
                $divstr.='<tr>';
                $divstr.='<td style="font-size:14px;">Código Establecimiento Destino: '.$xmlFact->destinatarios->destinatario[$i]->codEstabDestino.'</td>';
                $divstr.='</tr>'; 
            }
            $divstr.='<tr>';
            $divstr.='<td style="font-size:14px;">Ruta: '.$xmlFact->destinatarios->destinatario[$i]->ruta.'</td>';
            $divstr.='</tr>';

            $divstr.='<tr>';
                $divstr.='<td width="60"> </td>';
                $divstr.='<td> <div style="padding-left: 50px;">';

                $divstr.='<table style="border: 1px solid #000; border-collapse: collapse;" >';
                $divstr.='<tr>';
                    $divstr.='<th width="60" style="font-size:12px;padding:0px 3px;border: 1px solid #000;text-align:center;font-weight: bold;">Cantidad</th>';
                    $divstr.='<th width="300" style="font-size:12px;border: 1px solid #000;text-align:center;font-weight: bold;">Descripción</th>';
                    $divstr.='<th width="85" style="font-size:12px;padding:0px 3px;border: 1px solid #000;text-align:center;font-weight: bold;">Código<br/>Principal</th>';
                    $divstr.='<th width="85" style="font-size:12px;padding:0px 3px;border: 1px solid #000;text-align:center;font-weight: bold;">Código <br/> Auxiliar</th>';
                $divstr.='</tr>';
        
                for ($j=0; $j < count($xmlFact->destinatarios->destinatario[$i]->detalles->detalle); $j++) 
                {
                    $detalle = $xmlFact->destinatarios->destinatario[$i]->detalles->detalle[$j];
                    $divstr.='<tr>';
                    $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:center; ">'.$detalle->cantidad.'</td>';
                    $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:center; ">'.$detalle->descripcion.'</td>';
                    $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:center; ">'.$detalle->codigoInterno.'</td>';
                    $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:left; ">'.$detalle->codigoAdicional.'</td>';
                    $divstr.='</tr>';
                }                           
                $divstr.='</table>';

                $divstr.='</div> </td>';
            $divstr.='</tr>';
           
            $divstr.='</table>';
        $divstr.='</div>';          
      }      
/*
      $divstr.='<table cellspacing="0" cellpadding="1" border="0">';
      $divstr.='<tr>';

        $divstr.='<td>';

        $divstr.='<div class="adicional" style="float:left; width:530px;margin:10px 0px">';
          $divstr.='<div style="margin-right:10px; border: 1px solid #000;padding:10px;">';
          $divstr.='<div style="font-size:14px;margin:6px 0px">Información Adicional</div>';
          for ($i=0; $i < count($xmlFact->infoAdicional->campoAdicional); $i++) {
            $divstr.='<div class="row" style="font-size:13px;margin:6px 0px"> '.$xmlFact->infoAdicional->campoAdicional[$i]['nombre'].': '.$xmlFact->infoAdicional->campoAdicional[$i].'</div>';
          }

          $divstr.='</div>';
        $divstr.='</div>';        


        $divstr.='</td>';

      $divstr.='</tr>';
      $divstr.='</table>';
*/
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
                    <strong>Tipo de documento: GUIA DE REMISION</strong> 
                    <br/>
                    <strong>Documento electrónico No: </strong>$objfac->cod_establecimiento-$objfac->cod_puntoemision-$objfac->secuencial
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

  function actualiza_enviado($claveacceso){
    $this->objmodel->actualiza_enviadoGuiaremision($this->id, $claveacceso);
  }

  function actualiza_autorizado($fechaautorizo){
    $this->objmodel->actualiza_autorizadoGuiaremision($this->id, $fechaautorizo);
  }

  function actualiza_rechazado($claveacceso){
    $this->objmodel->actualiza_rechazadoGuiaremision($this->id, $claveacceso);
  }

  function sel_claveacceso(){
    return $this->objmodel->sel_claveaccesoGuiaremision($this->id);
  }

  function datoscomprobante(){
    return $this->objmodel->datosGuiaremision($this->id);
  }

  function actualiza_correoenviado(){
    $this->objmodel->actualiza_correoenviadoGuia($this->id);
  }

}

?>
