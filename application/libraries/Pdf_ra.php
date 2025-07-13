<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require('Fpdf.php'); #LIBRERIA PROPIA DE FPDF
#CLASE FPDF
class Pdf_ra extends FPDF
{
	var $codbar = NULL;
	#FUNCION CONTRUCTORA	
	function __construct($params, $orientation='L', $unit='mm', $size='media')
	{
		#CONTRUCTOR PADRE
		parent::__construct($orientation,$unit,$size);
		$this->encrec = $params['encrec'];
		$this->abono = $params['abono'];		
		$this->sucursal = $params['sucursal'];		
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
	    $file_name = "reciboabono.jpg";
        if ($this->sucursal->logo_sucursal){    
	        $pic = base64_decode($this->sucursal->logo_sucursal);
    	    imagejpeg(imagecreatefromstring ( $pic ), $file_name);

			$this->Image($file_name,10,10,40,14);
		}	

		//$this->Image('public/img/quitoledbn.jpg',10,10,50,14);
        $this->Line(12,25,196,25);
        $this->SetFont('Arial','B',6);
       // $this->text(82, 12, '');

		$this->SetXY(50,8);
		$this->MultiCell(110,4,utf8_decode($this->sucursal->dir_sucursal),0,'C');
		$this->SetXY(100,13);
		$this->Cell(20,10,utf8_decode($this->sucursal->telf_sucursal),0,0,'C');
		$this->SetXY(100,18);
		$this->Cell(20,10,utf8_decode($this->sucursal->mail_sucursal),0,0,'C');

        /*
        $this->text(80, 15, utf8_decode('AV. COLÓN OEE1-80 Y 10 DE AGOSTO'));
        $this->text(75, 18, utf8_decode('Telfs: 02 2565 354 - 0990 046 742 * Quito - Ecuador'));
        $this->text(89, 21, utf8_decode('quitoled@hotmail.com'));
        $this->text(92, 24, utf8_decode('www.quitoled.ec'));
*/
        $this->Rect(156, 12, 40, 10, "D");
        $this->SetFont('Arial','B',11);        
        $this->text(157, 16, 'RECIBO DE ABONO');
        $this->SetFont('Arial','B',9);
        $nrorec = $this->abono->nro_comprobante;
        $this->text(157, 21, utf8_decode('Nº '.$nrorec));

		$cliente = $this->encrec->nom_cliente;
		$direccion = $this->encrec->dir_cliente; 
		$telf = $this->encrec->telf_cliente;
		$fechad = date("d/m/Y");
      	$cajero = $this->encrec->cajero;

		$this->SetFont('Arial','B',8);
		$this->ln(20); 
		$this->Cell(100,4,utf8_decode("Cliente: $cliente"),0,0,'L');
        $this->Cell(85,4,"Fecha: $fechad",0,1,'R'); 		
        $this->Cell(185,4,utf8_decode("Dirección: $direccion"),0,1,'L');
        $this->Cell(100,4,utf8_decode("Telefono: $telf"),0,0,'L');
        $this->Cell(85,4,"Cajero: $cajero",0,1,'R');         
        //$this->Line(12,40,196,40);
        $this->ln(3); 

		$nrofactura = $this->encrec->nro_factura;
		$fec = $this->encrec->fecha;
		$fecha = str_replace('-', '/', $fec); 
      	$fechae = date("d/m/Y", strtotime($fecha));
        $strdate = str_replace('-', '/', $this->encrec->fechalimite); 
        $fechalimite = date("d/m/Y", strtotime($strdate)); 
		$montototal = $this->encrec->montototal;
		$abonoinicial = $this->encrec->abonoinicial;
		$montointerescredito = $this->encrec->montointerescredito;
		$montocredito = $this->encrec->montocredito;
		$valorpendiente = $this->encrec->montocredito - $this->encrec->abonos + $this->encrec->abonoinicial;

		$this->Cell(100,4,utf8_decode("Fecha Emisión: $fechae"),0,0,'L'); 
		$this->Cell(85,4,utf8_decode("Fecha Limite Pago: $fechalimite"),0,1,'R'); 
		$this->Cell(100,4,utf8_decode("Nro factura: $nrofactura"),0,0,'L');
		$this->Cell(85,4,utf8_decode("Monto Interes: $montointerescredito"),0,1,'R');
		$this->Cell(100,4,utf8_decode("Total factura: $montototal"),0,0,'L');		
		$this->Cell(85,4,utf8_decode("Monto Crédito: $montocredito"),0,1,'R');
		$this->Cell(100,4,utf8_decode("Abono Inicial: $abonoinicial"),0,0,'L');
		$this->Cell(85,4,utf8_decode("Valor Pendiente: $valorpendiente"),0,1,'R');
		//$this->Line(12,60,196,60);
        $this->ln(10); 
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
