<?php
/*******************************************************************************
* NotaCreditoSRI                                                               *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    04-Nov-2018                                                         *
* Author:  Pavel Oramas                                                        *
*******************************************************************************/

require_once(APPPATH.'helpers/ComprobanteSRI.php');

class NotaCreditoSRI extends ComprobanteSRI
{

  function CrearArchivoXML()
  {
        /*********** DATOS DE LA Nota Credito **********/   
        
        $id = $this->id;

        $registro = $this->objmodel->datosNotaCredito($id);
        $tipoAmbiente = $registro->ambiente_notacredito;
        $emision = "1";
        $razonS = $registro->raz_soc_emp;
        $nombreC = $registro->nom_emp;
        $rucEmp = $registro->ruc_emp; 
        $razoncliente = $registro->nom_cliente;
        $ruccliente = $registro->ident_cliente; 
        $tipoidcliente = $registro->codsri_venta;
        $modelConfig = "";
        $cod_establecimiento = $registro->cod_establecimiento;
        $cod_pto_emi = $registro->cod_puntoemision;
        $sec_fact = $registro->nro_factura;
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
          $fechaEmisionFac = $registro->fecha;              
        }

        $desc_monto = $registro->descuento;
        $descsubconiva = $registro->descsubtotaliva;
        $descsubsiniva = $registro->descsubtotalnoiva;
        $montoiva = $registro->montoiva;
        $montototal = $registro->total;

        $nro_docmodificado = $registro->nro_docmodificado;
        $fecha_docmodificado = $registro->fecha_docmodificado;
        $motivo = $registro->motivo;
        
        //  Creando la clave de acceso			
        $nombreArch = $this->claveAcceso("04",$rucEmp, $fechaEmisionFac, $tipoAmbiente, $cod_establecimiento,
                                  $cod_pto_emi, $sec_fact, $emision);

        $digVerifi = $this->modulo11($this->invertirCadena($nombreArch));
        $nombreArch = $nombreArch.$digVerifi;

        $xml = new DomDocument("1.0","UTF-8");

        $raiz = $xml->createElement('notaCredito');
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

        $codDoc = $xml->createElement("codDoc", "04");
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

        $infoFactura=$xml->createElement("infoNotaCredito");
        $infoFactura=$raiz->appendChild($infoFactura);

        //$fechaEmision=$xml->createElement("fechaEmision", date("d/m/Y"));
        $strfecha = explode("-", $fechaEmisionFac);
        $strfecha = str_pad($strfecha[2],2,"0",STR_PAD_LEFT).'/'.str_pad($strfecha[1],2,"0",STR_PAD_LEFT).'/'.$strfecha[0];
        $fechaEmision=$xml->createElement("fechaEmision", $strfecha);
        $fechaEmision=$infoFactura->appendChild($fechaEmision);

        $dirEstablecimiento=$xml->createElement("dirEstablecimiento",trim(utf8_encode($dirEstab)));
        $dirEstablecimiento=$infoFactura->appendChild($dirEstablecimiento);

        $tipoIdentificacionComprador=$xml->createElement("tipoIdentificacionComprador", $tipoidcliente);       
        $tipoIdentificacionComprador = $infoFactura->appendChild($tipoIdentificacionComprador);

        // DATOS DEL CLIENTE 
        $razonSocialComprador=$xml->createElement("razonSocialComprador", trim($razoncliente));
        $razonSocialComprador=$infoFactura->appendChild($razonSocialComprador);

        $identificacionComprador=$xml->createElement("identificacionComprador", $ruccliente);       
        $identificacionComprador = $infoFactura->appendChild($identificacionComprador);

        $node=$xml->createElement("obligadoContabilidad", $obligadoContab);
        $infoFactura->appendChild($node);

        $node=$xml->createElement("codDocModificado", "01");
        $infoFactura->appendChild($node);

        $node=$xml->createElement("numDocModificado", $nro_docmodificado);
        $infoFactura->appendChild($node);

        $strfecha = explode("-", $fecha_docmodificado);
        $strfecha = str_pad($strfecha[2],2,"0",STR_PAD_LEFT).'/'.str_pad($strfecha[1],2,"0",STR_PAD_LEFT).'/'.$strfecha[0];
        $node=$xml->createElement("fechaEmisionDocSustento", $strfecha);
        $infoFactura->appendChild($node);
        
        $totalSinImpuestos = $xml->createElement("totalSinImpuestos", number_format($descsubconiva + $descsubsiniva,2,'.',''));
        $totalSinImpuestos = $infoFactura->appendChild($totalSinImpuestos);

        $totalDescuento = $xml->createElement("valorModificacion", number_format($montototal,2,'.',''));
        $totalDescuento = $infoFactura->appendChild($totalDescuento);

        $moneda=$xml->createElement("moneda","DOLAR");
        $infoFactura->appendChild($moneda);

        $totalConImpuestos = $xml->createElement("totalConImpuestos");
        $totalConImpuestos = $infoFactura->appendChild($totalConImpuestos);

        $impuestos = $this->objmodel->datosNotaCreditoResumenImpuesto($id);               
        // BUCLE DE IMPUESTOS 
        foreach($impuestos as $impuesto){
            $totalImpuesto = $xml->createElement("totalImpuesto");
            $totalImpuesto = $totalConImpuestos->appendChild($totalImpuesto);

            $node = $xml->createElement("codigo", $impuesto->codigotipoimpuesto);
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("codigoPorcentaje", $impuesto->codigoporcentaje);
            $totalImpuesto->appendChild($node);
            
            $node = $xml->createElement("baseImponible", $impuesto->baseimponible);
            $totalImpuesto->appendChild($node);

            $node = $xml->createElement("valor", $impuesto->valor);
            $totalImpuesto->appendChild($node);
        }

        $node=$xml->createElement("motivo", $motivo);
        $infoFactura->appendChild($node);
       
        //  DETALLE NOTA 
        $detalles=$xml->createElement("detalles");
        $detalles=$raiz->appendChild($detalles);
        
        // BUCLE DETALLE 
        $objdetalles = $this->objmodel->datosNotaCreditodetalle($id);
        foreach($objdetalles as $producto)
        {               
            $detalle = $xml->createElement("detalle");
            $detalle = $detalles->appendChild($detalle);

            if(trim($producto->pro_codigoauxiliar) != '')
                $codigoproducto = trim($producto->pro_codigoauxiliar);
            else{
                if (trim($producto->pro_codigobarra) != '')
                    $codigoproducto = trim($producto->pro_codigobarra);
                else
                    $codigoproducto = '1';
            }


            $codigoPrincipal = $xml->createElement("codigoInterno", trim($codigoproducto));
            $detalle->appendChild($codigoPrincipal );
            $codigoAuxiliar = $xml->createElement("codigoAdicional",/*"F-".*/trim($codigoproducto));
            $detalle->appendChild($codigoAuxiliar);
            $descripcion = $xml->createElement("descripcion",trim($producto->pro_nombre));
            $detalle->appendChild($descripcion);
            $cantidad = $xml->createElement("cantidad",number_format($producto->cantidad,2,'.',''));
            $detalle->appendChild($cantidad);
            $precioUnitario = $xml->createElement("precioUnitario",number_format($producto->precio,2,'.',''));
            $detalle->appendChild($precioUnitario);
            $descuento = $xml->createElement("descuento",number_format($producto->descuento,2,'.',''));
            $detalle->appendChild($descuento);
            $precioTotalSinImpuesto = $xml->createElement("precioTotalSinImpuesto",number_format($producto->descsubtotal,2,'.',''));
            $detalle->appendChild($precioTotalSinImpuesto);
            $impuestos = $xml->createElement("impuestos");
            $impuestos = $detalle->appendChild($impuestos);

            $objimpuestos = $this->objmodel->datosNotaCreditoDetalleImpuesto($producto->id);
            foreach($objimpuestos as $objimpuesto){
                $impuesto = $xml->createElement("impuesto");
                $impuesto = $impuestos->appendChild($impuesto);

                $node =  $xml->createElement("codigo", $objimpuesto->codigotipoimpuesto);
                $impuesto->appendChild($node);   
                $node =  $xml->createElement("codigoPorcentaje", $objimpuesto->codigoporcentaje);
                $impuesto->appendChild($node);
                $node =  $xml->createElement("tarifa", $objimpuesto->tarifa);
                $impuesto->appendChild($node);
                $node =  $xml->createElement("baseImponible", $objimpuesto->baseimponible);
                $impuesto->appendChild($node);
                $node =  $xml->createElement("valor", $objimpuesto->valor);
                $impuesto->appendChild($node);
            }    

        }

        //  ADICIONAL
        $infoAdicional=$xml->createElement("infoAdicional");
        $infoAdicional= $raiz->appendChild($infoAdicional);
        $stradic = trim($registro->direccion_cliente) != '' ? trim($registro->direccion_cliente) : ' ';
        $campoAdicional = $xml->createElement("campoAdicional",$stradic);
        $campoAdicional->setAttribute("nombre","DireccionCliente");
        $infoAdicional->appendChild($campoAdicional);
        $stradic = trim($registro->telefonos_cliente) != '' ? trim($registro->telefonos_cliente) : ' ';
        $campoAdicional = $xml->createElement("campoAdicional",$stradic);
        $campoAdicional->setAttribute("nombre","TelefonoCliente");
        $infoAdicional->appendChild($campoAdicional);
        $stradic = trim($registro->correo_cliente) != '' ? trim($registro->correo_cliente) : ' ';
        $campoAdicional = $xml->createElement("campoAdicional",$stradic);
        $campoAdicional->setAttribute("nombre","CorreoCliente");
        $infoAdicional->appendChild($campoAdicional);
      
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

              $imagen = $this->objmodel->sel_logoempresanotacredito($this->id);
              if (trim($imagen) != ''){
                $divstr.='<img src="'.FCPATH.'/public/img/sucursal/'.$imagen.'" alt="" />';
                //$divstr.='<img src="'.FCPATH.'/public/img/empresa/'.$imagen.'" alt="" />';
              }  

            $divstr.='</div>';
            $divstr.='<div style="border:1px solid #000; padding:5px; border-radius:5px; margin-right:5px; font-size:12px  ">';
              $divstr.='<div class="row"> '.$xmlFact->infoTributaria->razonSocial.'</div>';
              $divstr.='<div class="row" style="font-size:11px ;margin: 15px 0px;"> '.$xmlFact->infoTributaria->nombreComercial.'</div>';      
              $divstr.='<div class="row"  style="font-size:10px;margin: 10px 0px;"> Dirección Matriz: '.$xmlFact->infoTributaria->dirMatriz.'</div>';
              $divstr.='<div class="row"  style="font-size:10px ;margin:10px 0px;"> Dirección Sucursal: '.$xmlFact->infoNotaCredito->dirEstablecimiento.'</div>';
              $divstr.='<div class="row"  style="font-size:11px;margin: 12px 0px; "> OBLIGADO A LLEVAR CONTABILIDAD: '.$xmlFact->infoNotaCredito->obligadoContabilidad.' </div>';
            $divstr.='</div>';
        $divstr.='</div>';

        $divstr.='</td>';

        $divstr.='<td>';

        $divstr.='<div class="cuadro_der" style="float:left;width:300px; border:1px solid #000; padding:5px; border-radius:5px ">';
          $divstr.='<div class="row" style="font-size:15px;margin: 15px 0px;"> R.U.C: '.$xmlFact->infoTributaria->ruc.' </div>';
          $divstr.='<div class="row" style="font-size:16px;margin: 15px 0px;"> NOTA DE CRÉDITO </div>';

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
          $divstr.='<td style="font-size:14px;">Razón Social / Nombres y Apellidos: '.$xmlFact->infoNotaCredito->razonSocialComprador.'</td>';
          $divstr.='</tr>';
          $divstr.='<tr>';
          $divstr.='<td style="font-size:14px;">Identificación: '.$xmlFact->infoNotaCredito->identificacionComprador.'</td>';
          $divstr.='</tr>';
          $divstr.='<tr>';
          $divstr.='<td style="font-size:14px;">Fecha Emisión: '.$xmlFact->infoNotaCredito->fechaEmision.'</td>';
          $divstr.='</tr>';
          $divstr.='<tr>';
          $divstr.='<td style="font-size:14px;">Comprobante que se modifica                 FACTURA             '.$xmlFact->infoNotaCredito->numDocModificado.'</td>';
          $divstr.='</tr>';
          $divstr.='<tr>';
          $divstr.='<td style="font-size:14px;">Fecha Emisión (Comprobante a modificar): '.$xmlFact->infoNotaCredito->fechaEmisionDocSustento.'</td>';
          $divstr.='</tr>';
          $divstr.='<tr>';
          $divstr.='<td style="font-size:14px;">Razón de Modificación: '.$xmlFact->infoNotaCredito->motivo.'</td>';
          $divstr.='</tr>';
        $divstr.='</table>';
      $divstr.='</div>';          
         
      $divstr.='<table style="border: 1px solid #000; border-collapse: collapse;" >';
      $divstr.='<tr>';
          $divstr.='<th width="75" style="font-size:12px;padding:0px 3px;border: 1px solid #000;text-align:center;font-weight: bold;">Código<br/>Principal</th>';
          $divstr.='<th width="75" style="font-size:12px;padding:0px 3px;border: 1px solid #000;text-align:center;font-weight: bold;">Código <br/> Auxiliar</th>';
          $divstr.='<th width="60" style="font-size:12px;padding:0px 3px;border: 1px solid #000;text-align:center;font-weight: bold;">Cantidad</th>';
          $divstr.='<th width="223" style="font-size:12px;border: 1px solid #000;text-align:center;font-weight: bold;">Descripción</th>';
          $divstr.='<th width="65" style="font-size:12px;border: 1px solid #000;text-align:center;font-weight: bold;">Precio<br/>Unitario</th>';
          $divstr.='<th width="70" style="font-size:12px;border: 1px solid #000;text-align:center;font-weight: bold;">Descuento</th>';
          $divstr.='<th width="70" style="font-size:12px;border: 1px solid #000;text-align:center;font-weight: bold;">Precio Total</th>';
        $divstr.='</tr>';
      
      for ($i=0; $i < count($xmlFact->detalles->detalle); $i++) 
      {
          $divstr.='<tr>';
          $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:center; ">'.$xmlFact->detalles->detalle[$i]->codigoInterno.'</td>';
          $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:center; ">'.$xmlFact->detalles->detalle[$i]->codigoAdicional.'</td>';
          $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:center; ">'.$xmlFact->detalles->detalle[$i]->cantidad.'</td>';
          $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:left; ">'.$xmlFact->detalles->detalle[$i]->descripcion.'</td>';
          $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:right; ">'.$xmlFact->detalles->detalle[$i]->precioUnitario.'</td>';
          $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:right ; ">'.$xmlFact->detalles->detalle[$i]->descuento.'</td>';
          $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:right; ">'.$xmlFact->detalles->detalle[$i]->precioTotalSinImpuesto.'</td>';
          $divstr.='</tr>';
      }             
      $divstr.='</table>';

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

        $divstr.='<td>';

        $divstr.='<div class="impuestos" style="width:250px;margin-left:10px; ">';
        $divstr.='<div style="margin-left:10px; ">';
        $divstr.='<table style="margin-left:10px; border: 1px solid #000; border-collapse: collapse;padding-left:10px;">';
            $divstr.='<tr>';

            $baseiva = 0;
            $montoiva = 0;
            for ($i=0; $i < count($xmlFact->infoNotaCredito->totalConImpuestos->totalImpuesto); $i++) {
              if ($xmlFact->infoNotaCredito->totalConImpuestos->totalImpuesto[$i]->codigo == '2'){
                $base = (double)$xmlFact->infoNotaCredito->totalConImpuestos->totalImpuesto[$i]->baseImponible;
                $baseiva += $base;

                $montoiva += (double)$xmlFact->infoNotaCredito->totalConImpuestos->totalImpuesto[$i]->valor;
              }
            }
            $totalSinImpuestos = (double)$xmlFact->infoNotaCredito->totalSinImpuestos;
            $basenoiva = $totalSinImpuestos - $baseiva;
            $descuento = (double)$xmlFact->infoNotaCredito->totalDescuento;
  
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:left;">SUBTOTAL 12%</td>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:right; width:100px; ">'.number_format($baseiva,2).'</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:left;">SUBTOTAL 0%</td>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:right;">'.number_format($basenoiva,2).'</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:left;">SUBTOTAL No objeto de IVA</td>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:right;">0.00</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:left;">SUBTOTAL Exento de IVA</td>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:right;">0.00</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:left;">SUBTOTAL SIN IMPUESTOS</td>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:right;">'.number_format($totalSinImpuestos,2).'</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:left;">TOTAL Descuento</td>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:right;">'.number_format($descuento,2).'</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:left;">ICE</td>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:right;">0.00</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:left;">IVA 12%</td>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:right;">'.number_format($montoiva,2).'</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:left;">IRBPNR</td>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:right;">0.00</td>';
            $divstr.='</tr>';
            $divstr.='<tr>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:left;">VALOR TOTAL</td>';
            $divstr.='<td style="font-size:11px;border: 1px solid #000;text-align:right;">'.number_format((double)$xmlFact->infoNotaCredito->valorModificacion,2).'</td>';
            $divstr.='</tr>';
          $divstr.='</table>';
          $divstr.='</div>';
        $divstr.='</div>';   

        $divstr.='</td>';
      $divstr.='</tr>';
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
                    <strong>Tipo de documento: NOTA DE CRÉDITO</strong> 
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

  function actualiza_enviado($claveacceso){
    $this->objmodel->actualiza_enviadoNotaCredito($this->id, $claveacceso);
  }

  function actualiza_autorizado($fechaautorizo){
    $this->objmodel->actualiza_autorizadoNotaCredito($this->id, $fechaautorizo);
  }

  function actualiza_rechazado($claveacceso){
    $this->objmodel->actualiza_rechazadoNotaCredito($this->id, $claveacceso);
  }

  function sel_claveacceso(){
    return $this->objmodel->sel_claveaccesoNotaCredito($this->id);
  }

  function datoscomprobante(){
    return $this->objmodel->datosNotaCredito($this->id);
  }

  function actualiza_correoenviado(){
    $this->objmodel->actualiza_correoenviadoNotacredito($this->id);
  }

}

?>
