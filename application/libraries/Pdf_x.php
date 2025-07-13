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

class Pdf_c extends FPDF {

    
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
        $this->Image('public/img/forma_cintillo.jpg',16,10,180,26);                              #Cintillo de la cabecera
	$this->SetFont('Arial','B',8);                                               #Fuente                              #Logo del Sistema (RPG)
	$this->ln(30); 
    }

    #FUNCION FIE DE PÁGINA	

    function Footer() {
      $this->SetY(-10);
      $this->SetFont('Arial','',6);
      $firmas = "PD/EB/EV";
      $pie1 ="AV.PERIMETRAL DE CHARALLAVE, SECTOR LA PEÑITA, CHARALLAVE NORTE";
      $pie2 ="MUNICIPIO CRISTOBAL ROJAS, ESTADO MIRANDA-VENEZUELA";
      $pie3 ="TELF (+58)-0239-500.84.37, 500.83.92 PÁG. WEB: IFE.GOB.VE RIF. G-20000124-0";
    //  $pie4 ="VIGENTE POR 90 DIAS";
      $pie5 ="VALIDO SOLO SI ESTA SELLADO POR LA OFICINA DE RRHH";
      $pie6 ='"¡Independencia y Patria Socialista... Viviremos y Venceremos!"';
      //list($agac,$mgac,$dgac)=explode("-",$this->ger->fec_gac); 
      //$fec_gac = $dgac."/".$mgac."/".$agac;
      $pie7b ="Gaceta Oficial N° 000, de fecha 010101";
      //list($apa,$mpa,$dpa)=explode("-",$this->ger->fec_prov); 
      //$fec_prov = $dpa."/".$mpa."/".$apa;
      $pie7a ="Designación Mediante Providencia Administrativa N° 0000, de fecha 020202";
      $pie7 = "cargo";
      $pie8 = "LIC. bbbbbb "; //.$this->ger->nombre." ".$this->ger->apellido;				
      $pie9 ="___________________________________________";

      $this->Cell(180,-4,utf8_decode("$pie3"),0,1,'C');
      $this->Cell(180,-4,utf8_decode("$pie1 $pie2"),0,1,'C');
      $this->SetFont('Arial','B',8);	
      $this->Cell(180,-5,'',0,1,'C');

      $codigo = 123456789;
      if ($codigo <> 0){
      	$this->Cell(93,-10,utf8_decode("COD. VALIDACION: $codigo"),0,0,'L');
      	$this->Code39(13,253,$codigo,0.7,7);
      }



      $this->SetFont('Arial','B',8);	
      $this->Cell(180,-10,utf8_decode("$firmas"),0,0,'R');

      $this->SetFont('Arial','',10);
      $this->Cell(180,-12,'',0,1,'C');

    //  $this->Cell(180,-5,utf8_decode("$pie4"),0,1,'C');
      $this->Cell(180,-5,utf8_decode("$pie5"),0,1,'C');
      $this->Cell(180,-5,'',0,1,'C');

      $this->Cell(190,-5,utf8_decode("$pie7b"),0,1,'C');
      $this->Cell(190,-5,utf8_decode("$pie7a"),0,1,'C');
      $this->SetFont('Arial','B',11);

      $this->Cell(180,-5,utf8_decode("$pie7"),0,1,'C');
      $this->Cell(180,-5,utf8_decode("$pie8"),0,1,'C');
      $this->Cell(180,-5,utf8_decode("$pie9"),0,1,'C');
      $this->Image('public/img/firmag1.gif',84,183.5,40,40);


    }

}

?>