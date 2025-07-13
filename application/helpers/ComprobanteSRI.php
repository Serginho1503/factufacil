<?php
/*******************************************************************************
* Comprobante SRI                                                              *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    04-Nov-2018                                                         *
* Author:  Pavel Oramas                                                        *
*******************************************************************************/

class ComprobanteSRI
{

    var $id; //identificador de Comprobante     
    var $tipo; //tipo de Comprobante     
    var $objmodel; //modelo de datos

    function CrearArchivoXML()
    {
        // To be implemented in your own inherited class
    }

    function CreaDivParaPDF($objXml, $pdf){
        // To be implemented in your own inherited class
    }

    function obtenerMensajeCorreo($objfac){
        // To be implemented in your own inherited class
    }

    function actualiza_enviado($claveacceso){
        // To be implemented in your own inherited class
    }

    function actualiza_autorizado($fechaautorizo){
        // To be implemented in your own inherited class
    }

    function actualiza_rechazado($claveacceso){
        // To be implemented in your own inherited class
    }

    function actualiza_correoenviado(){
        // To be implemented in your own inherited class
    }

    function sel_claveacceso(){
        // To be implemented in your own inherited class
    }
   
    function datoscomprobante(){
        // To be implemented in your own inherited class
    }

    function claveAcceso($tipoCompro, $rucEmpresa, $fechaEmisionFac, $ambiente, $codestab,
                         $codptoemi, $numComprob, $emision)
    {
        $claveAcceso ="";
        //$date = date("dmY");
        $fechaEmisionFac = explode("-", $fechaEmisionFac);
        $date = $fechaEmisionFac[2].$fechaEmisionFac[1].$fechaEmisionFac[0];
        $codNumerico = rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9);

        $claveAcceso = $date.$tipoCompro.trim($rucEmpresa).$ambiente.$codestab.$codptoemi.
                       str_pad($numComprob,9,"0",STR_PAD_LEFT).$codNumerico.$emision;

        return $claveAcceso;
    } 
    
      
    function invertirCadena($string)
    {
        $cadenaInvertida ="";
        for ($i= strlen($string)-1; $i >=0; $i--)
        {
            $cadenaInvertida .= $string[$i];
        }
        
        return $cadenaInvertida;
    }

    function modulo11($claveInvertida)
    {
        $pivote = 2;
        $longitudCadena = strlen($claveInvertida);
        $cantidadTotal = 0;
        for($i=0; $i < $longitudCadena;$i++)
        {
            if($pivote == 8)
            {
                $pivote =2;
            }
            
            $temporal = (int) $claveInvertida[$i]*$pivote;
            $pivote ++;
            $cantidadTotal += $temporal;
        }

        $cantidadTotal = 11 - ($cantidadTotal % 11);
        if($cantidadTotal == 11)
        {
            $cantidadTotal = 0;
        }

        if($cantidadTotal == 10)
        {
            $cantidadTotal = 1;
        }
        
        return $cantidadTotal;
    }

}

?>

