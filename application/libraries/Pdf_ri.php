<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require('Fpdf.php'); #LIBRERIA PROPIA DE FPDF
#CLASE FPDF
class Pdf_ri extends FPDF
{
	var $codbar = NULL;
	#FUNCION CONTRUCTORA	
	function __construct($params, $orientation='L', $unit='mm', $size='media')
	{
		#CONTRUCTOR PADRE
		parent::__construct($orientation,$unit,$size);
		$this->encrec = $params['encrec'];
		

	}
	# FUNCION QUE GENERA ARREGLOS PARA LAS CELDAS DINAMICAS
	var $widths;
	var $aligns;

	function SetWidths($w){$this->widths=$w;} function SetAligns($a){$this->aligns=$a;}	
	
	#FUNCION ARREGLOS
	public function Row($data, $n, $b){
		$nb=0;
		for($i=0;$i<count($data); $i++){
			$nb = max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		} 
		$h=4*$nb;
		$this->CheckPageBreak($h);
		for($i=0;$i<count($data);$i++){	
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			$x=$this->GetX(); $y=$this->GetY();
			$this->Rect($x,$y,$w,$h);
			$this->SetFont('Arial','',6);                           #FUENTE
	
			if($b[$i] == $i){
				$alineacion = "C";
			}else{
	            if($b[$i] == 'r'){
	               $alineacion = "R";
	            }else{
	               $alineacion = "J";
	            }
			}
	
			if($n[$i] == $i){
				$this->SetFillColor(196,196,196);               #COLOR DE LA CELDA
				$fill = TRUE;
			}else{
				$fill = FALSE;	
			}	
			$this->MultiCell($w,4,$data[$i],0,$alineacion,$fill);   #BORDE DE LA CELDA
			$this->SetXY($x+$w,$y);
		}
		$this->Ln($h);
	}
	
    #FUNCION SALDO DE PAGINA Y NUEVA PAGINA
	function CheckPageBreak($h){	
		if($this->GetY()+$h>
		$this->PageBreakTrigger){	
			$this->AddPage($this->CurOrientation,'Letter');
		}
	}
		
	function NbLines($w,$txt){
			$cw=&$this->CurrentFont['cw']; if($w==0){$w=$this->w-$this->rMargin-$this->x;}
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize; $s=str_replace("\r",'',$txt);
			$nb=strlen($s); if($nb>0 and $s[$nb-1]=="\n"){$nb--;} $sep=-1; $i=0; $j=0; $l=0; $nl=1;

			while($i<$nb)
			{	$c=$s[$i]; if($c=="\n"){$i++; $sep=-1; $j=$i; $l=0; $nl++; continue;} if($c==' '){$sep=$i;}$l+=$cw[$c];
				if($l>$wmax){if($sep==-1){if($i==$j){$i++;}}else{$i=$sep+1;}$sep=-1; $j=$i; $l=0; $nl++;}else{$i++;}
			}return $nl;
		}
#----------------------------------------------------------------------------------------------------------------------------------
	#FUNCION DE LA CABECERA		
	function Header(){
		$this->Image('public/img/quitoledbn.jpg',10,10,50,14);
        $this->Line(12,25,196,25);
        $this->SetFont('Arial','B',6);
        $this->text(82, 12, '');
        $this->text(80, 15, utf8_decode('AV. COLÓN OEE1-80 Y 10 DE AGOSTO'));
        $this->text(75, 18, utf8_decode('Telfs: 02 2565 354 - 0990 046 742 * Quito - Ecuador'));
        $this->text(89, 21, utf8_decode('quitoled@hotmail.com'));
        $this->text(92, 24, utf8_decode('www.quitoled.ec'));

        $this->Rect(156, 12, 40, 10, "D");
        $this->SetFont('Arial','B',12);        
        $this->text(160, 16, 'Nro Documento');
        $this->SetFont('Arial','B',9);
        $nrorec = $this->encrec->nro_documento;
        $this->text(160, 21, utf8_decode('Nº '.$nrorec));



	}
#----------------------------------------------------------------------------------------------------------------------------------
	#FUNCION FIE DE PÁGINA	
    function Footer(){
    	/*
    	$subtotaliva = $this->piefact->subconiva;
		$subtotalcero = $this->piefact->subsiniva;
		$subtotaldiva = $this->piefact->descsubconiva;
		$subtotaldcero = $this->piefact->descsubsiniva;
		$montoiva = $this->piefact->montoiva;
		$descuento = $this->piefact->desc_monto;
		$total = $this->piefact->montototal;

	    $this->SetY(-0.1);
	    $this->SetFont('Arial','B',8);

	    $this->Ln(-10);
	    $this->Cell(160,-4,utf8_decode("Total"),0,0,'R');
	    $this->Cell(25,-4,utf8_decode('$'.$total),0,1,'R');

	    $this->Cell(160,-4,utf8_decode("IVA (12%)"),0,0,'R');
	    $this->Cell(25,-4,utf8_decode('$'.$montoiva),0,1,'R');

	    $this->Cell(160,-4,utf8_decode("Subtotal con Descuento IVA (0 %)"),0,0,'R');
	    $this->Cell(25,-4,utf8_decode('$'.$subtotaldcero),0,1,'R');

	    $this->Cell(160,-4,utf8_decode("Subtotal con Descuento IVA (12 %)"),0,0,'R');
	    $this->Cell(25,-4,utf8_decode('$'.$subtotaldiva),0,1,'R');

	    $this->Cell(160,-4,utf8_decode("Descuento"),0,0,'R');
	    $this->Cell(25,-4,utf8_decode('$'.$descuento),0,1,'R');

	    $this->Cell(160,-4,utf8_decode("Subtotal IVA (0 %)"),0,0,'R');
	    $this->Cell(25,-4,utf8_decode('$'.$subtotalcero),0,1,'R');

	    $this->Cell(160,-4,utf8_decode("Subtotal IVA (12 %)"),0,0,'R');
	    $this->Cell(25,-4,utf8_decode('$ '.$subtotaliva),0,1,'R');
	    */

    } 
}
?>
