<?php

/* -------------------------------------------------------------------------------------------------------------------------------------------
  ARCHIVO: doc.php                                                                                                                              |
  DESCRIPCION: Libreria PDF donde se integra el FPDF                                                                                            |
  FECHA DE CREACIÓN: 05/08/2013                                                                                                                 |
  PROGRAMADOR: Anderson Tovar                                                                                                                   |
  INSTITUCIÓN: Instituto de Ferrocarriles del Estado                                                                                            |
  GERENCIA: OTIC - Oficina de Técnología de Información y Comunicación                                                                          |
  ÁREA: Desarrollo de Sistemas                                                                                                                  |
  ---------------------------------------------------------------------------------------------------------------------------------------------- */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require('Fpdf.php'); #LIBRERIA PROPIA DE FPDF
#CLASE FPDF

class Pdf extends FPDF {

    
    var $nomb = NULL;
    var $fech = NULL;
#FUNCION CONTRUCTORA	

    function __construct($params, $orientation = 'P', $unit = 'mm', $size = 'Letter') {
        #CONTRUCTOR PADRE
        parent::__construct( $orientation, $unit, $size);
        
        $this->nomb = $params['usua'];
        $this->fech = $params['date'];
    }

    # FUNCION QUE GENERA ARREGLOS PARA LAS CELDAS DINAMICAS

    var $widths;
    var $aligns;

    function SetWidths($w) {
        $this->widths = $w;
    }

    function SetAligns($a) {
        $this->aligns = $a;
    }

    #FUNCION ARREGLOS

    public function Row($data, $n, $b) {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }
        $h = 5 * $nb;
        $this->CheckPageBreak($h);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);
            $this->SetFont('Arial', '', 6);                           #FUENTE

            if ($b[$i] == $i) {
                $alineacion = "C";
            } else {
                if ($b[$i] == 'r') {
                    $alineacion = "R";
                } else {
                    $alineacion = "J";
                }
            }

            if ($n[$i] == $i) {
                $this->SetFillColor(196, 196, 196);               #COLOR DE LA CELDA
                //$this->SetFillColor(255,255,255);

                $fill = TRUE;
            } else {
                $fill = FALSE;
            }
            $this->MultiCell($w, 5, $data[$i], 0, $alineacion, $fill);   #BORDE DE LA CELDA
            $this->SetXY($x + $w, $y);
        }
        $this->Ln($h);
    }

    #FUNCION SALDO DE PAGINA Y NUEVA PAGINA

    function CheckPageBreak($h) {
        if ($this->GetY() + $h >
                $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation, 'Letter');
        }
    }

    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n") {
            $nb--;
        } $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;

        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            } if ($c == ' ') {
                $sep = $i;
            }$l+=$cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }$sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }return $nl;
    }

#FUNCION DE LA CABECERA		

    function Header() {
        /* $this->Image('public/img/logo2.png', pos_hor, pos_ver, tam_img); */
        $this->Image('public/img/pdfhead.png', 11, 10, 75);
        $this->Image('public/img/pdflog.png', 12, 20, 10);
        // $this->Image('public/img/spi.png', 240, 10, 30);
        $this->SetFont('Arial','B',8);
        $this->Text(175,23,'FECHA: '.date("d/m/Y"));    #Usuario logueado
        $this->ln(3);                                          #Espacio reservado para la cabecera
    }

    #FUNCION FIE DE PÁGINA	

    function Footer() {


        $this->SetY(-8);
        $this->SetFont('Arial', '', 6);                                                 #Fuente y tamaño del foliado
        //$this->pdf->Text(94,46,utf8_decode('PAGO DE GUARDERÍA POR EMPLEADO'));                                 
        $this->Cell(262, -6, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 1, 'R');     #Foliado 1/1 AliasNbPages(); 
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(192, -2, utf8_decode("Emitido por: $this->nomb"), 0, 1, 'L', false);    #Usuario logueado
        // $this->Cell(36, -2, utf8_decode("Firma del Gerente:"), 0, 0, 'C');
        // $this->Cell(22, -2, utf8_decode("________________________________"), 0, 1, 'C');
    }

}

?>