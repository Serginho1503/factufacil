<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require('Fpdf.php'); #LIBRERIA PROPIA DE FPDF
#CLASE FPDF
class Pdf_caja extends FPDF
{
	var $codbar = NULL;
	#FUNCION CONTRUCTORA	
	function __construct($params, $orientation='L', $unit='mm', $size='media')
	{
		#CONTRUCTOR PADRE
		parent::__construct($orientation,$unit,$size);
		$this->cajaenc = $params['cajaenc'];
		$this->sucursal = $params['sucursal'];

	//	$this->pieprof = $params['pieprof'];
		
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
		
		//$this->Image('public/img/quitoledbn.jpg',10,10,50,14);
/*	    $file_name = "cajamov.jpg";
        $pic = base64_decode($this->sucursal->logo_sucursal);
        imagejpeg(imagecreatefromstring ( $pic ), $file_name);

		$this->Image($file_name,10,10,40,14);
*/
        $file_name = "cajamov.jpg";
        if ($this->sucursal->logo_sucursal){    
          $pic = base64_decode($this->sucursal->logo_sucursal);
          imagejpeg(imagecreatefromstring ( $pic ), $file_name);
          $this->Image($file_name,10,5,30,20);
        }

      	$this->Line(12,25,196,25);
      	$this->SetFont('Arial','B',16);
      	$this->text(80, 12, utf8_decode('NOMBRE EMPRESA'),0,0,'C');
	  	$this->SetFont('Arial','B',12);        
      	$this->SetXY(100,13);
      	$this->SetFont('Arial','B',11);
      	$this->Cell(20,10,utf8_decode($this->sucursal->mail_sucursal),0,0,'C');
      	$this->SetXY(100,18);
      	$this->SetFont('Arial','B',12);
      	$this->Cell(20,10,utf8_decode($this->sucursal->dir_sucursal),0,0,'C');

        $this->Rect(165, 12, 30, 10, "D");
        $this->SetFont('Arial','B',12);        
        $this->text(167, 16, 'Movimiento');
        $this->SetFont('Arial','B',9);
        $idmov = $this->cajaenc->id_mov;
        $this->text(170, 21, utf8_decode('Nº '.$idmov));
        $this->SetFont('Arial','B',6);        

		$usuape = $this->cajaenc->usuape;
		$usucie = $this->cajaenc->usucie;
		$fec = $this->cajaenc->fecha_apertura;
		$fecha = str_replace('-', '/', $fec); 
      	$fecha_ape = date("d/m/Y H:i:s", strtotime($fecha));	
      	$montoapertura = $this->cajaenc->monto_apertura;
    
		$fecc = $this->cajaenc->fecha_cierre;
		$fechac = str_replace('-', '/', $fec); 
      	$fecha_cie = date("d/m/Y H:i:s", strtotime($fechac));

		$this->SetFont('Arial','B',8);
		$this->ln(10); 
		$this->Cell(190,4,utf8_decode("CAJA: ".$this->cajaenc->caja),0,0,'C');
		$this->ln(10); 
		$this->Cell(100,4,utf8_decode("Usuario de Apertura: $usuape"),0,0,'L');
        $this->Cell(85,4,"Usuario de Cierre: $usucie",0,1,'R');
        $this->Cell(100,4,utf8_decode("Fecha Apertura: $fecha_ape"),0,0,'L');
        $this->Cell(85,4,"Fecha de Cierre: $fecha_cie",0,1,'R');
        $this->Cell(100,4,utf8_decode("Monto Apertura: $montoapertura"),0,1,'L');
           
        $this->ln(6); 

	}
#----------------------------------------------------------------------------------------------------------------------------------
	#FUNCION FIE DE PÁGINA
    function Footer(){
    	/*
    	$registro = $this->pieprof;
		$subtotaliva=0;
		$subtotalcero=0;
		$subtotaldiva=0;
		$subtotaldcero=0;
		$montoiva=0;
		$descuento=0;
		foreach ($registro as $row) {
			$strnombre = $row->pro_nombre;
			$strcant = $row->cantidad;
			if ($row->pro_grabaiva == 1){
				$subtotaliva+= $row->subtotal;
				$montoiva+= $row->montoiva;    
			}
			else{
				$subtotalcero+= $row->subtotal;
			}
    	}

    	$total = $subtotaliva + $subtotalcero + $montoiva;
		*/
	    $this->SetY(-0.1);
	    $this->SetFont('Arial','B',8);

        $this->text(72, 281, utf8_decode('TECNOLOGÍA INFORMÁTICA PROFESIONAL'),0,0,'C');
        //$this->text(98, 290, utf8_decode('Guayaquil - Ecuador'));
        $this->text(53, 291, utf8_decode('info@tecnologiainformaticapro.com - www.tecnologiainformaticapro.com'),0,0,'C');
        $this->text(65, 286, utf8_decode('Telfs: 0986 557 426 / 0960 657 178 - Guayaquil - Ecuador'),0,0,'C');        
        //$this->text(80, 281, utf8_decode('www.sinaiestetica.com'));

	    $this->Line(12,278,196,278);
	    $this->SetFont('Arial','B',10);
	    $this->Ln(-30);
/*
	    $this->Line(12,269,60,269);
        $this->text(22, 273, utf8_decode('Firma Autorizada'));

        $this->text(12, 240, utf8_decode('NOTA:'));
		$this->text(12, 244, utf8_decode('La validez de la siguiente Proforma tiene 8 días'));
*/        
	    /*
	    $this->SetFont('Arial','B',10);
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
