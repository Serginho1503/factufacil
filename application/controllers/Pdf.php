<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Pdf extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }


    private function pagina_v() {
        $this->pdf_p->SetMargins('12', '7', '10');   #Margenes
        $this->pdf_p->AddPage('P', 'A4');        #Orientación y tamaño 
    }

    private function pagina_h() {
        $this->pdf_f->SetMargins('12', '4', '10');   #Margenes
        $this->pdf_f->AddPage('L', 'Letter');        #Orientación y tamaño
    }

    public function factura(){
        
        $codbar = '201608110001';
        $params['codbar'] = $codbar;
        $this->load->library('pdf_f', $params);
        $this->pdf_f->fontpath = 'font/'; 
        $this->pdf_f->AliasNbPages();
        $this->pagina_v();
        $this->pdf_f->SetFillColor(139, 35, 35);
        $this->pdf_f->SetFont('Arial','B',8);
        $this->pdf_f->SetTextColor(0,0,0);

        $this->pdf_f->Line(12,110,196,110);
        $this->pdf_f->Cell(20,4,utf8_decode("Cantidad"),1,0,'C');
        $this->pdf_f->Cell(115,4,utf8_decode("Descripcion"),1,0,'L');
        $this->pdf_f->Cell(25,4,'Precio Unitario',1,0,'C');
        $this->pdf_f->Cell(25,4,'Subtotal',1,1,'R');

        $this->pdf_f->SetFont('Arial','',8);        
        $this->pdf_f->Cell(20,5,utf8_decode("4"),0,0,'C');
        $this->pdf_f->Cell(115,5,utf8_decode("Barra Led"),0,0,'L');
        $this->pdf_f->Cell(25,5,'$9.00',0,0,'C');
        $this->pdf_f->Cell(25,5,'$36.00',0,1,'R');  

        $this->pdf_f->Cell(20,5,utf8_decode("5"),0,0,'C');
        $this->pdf_f->Cell(115,5,utf8_decode("maguerita led"),0,0,'L');
        $this->pdf_f->Cell(25,5,'$3.00',0,0,'C');
        $this->pdf_f->Cell(25,5,'$15.00',0,1,'R'); 
         
        $this->pdf_f->Cell(20,5,utf8_decode("6"),0,0,'C');
        $this->pdf_f->Cell(115,5,utf8_decode("Bombillo Led "),0,0,'L');
        $this->pdf_f->Cell(25,5,'$5.00',0,0,'C');
        $this->pdf_f->Cell(25,5,'$30.00',0,1,'R');
         
        $this->pdf_f->Cell(20,5,utf8_decode("4"),0,0,'C');
        $this->pdf_f->Cell(115,5,utf8_decode("Barra Led"),0,0,'L');
        $this->pdf_f->Cell(25,5,'$9.00',0,0,'C');
        $this->pdf_f->Cell(25,5,'$36.00',0,1,'R');

        $this->pdf_f->Output('Factura','I'); 

    } 


    public function proforma(){
        
        $subtotaliva = '36.00';
        $subtotalcero = '36.00';
        $subtotaldiva = '36.00';
        $subtotaldcero = '36.00';
        $montoiva = '36.00';
        $descuento = '36.00';
        $total = '36.00';




          $params['cabfact'] = 0;
          $params['piefact'] = 0;
        $this->load->library('pdf_p', $params);
        $this->pdf_p->fontpath = 'font/'; 
        $this->pdf_p->AliasNbPages();
        $this->pagina_v();
        $this->pdf_p->SetFillColor(139, 35, 35);
        $this->pdf_p->SetFont('Arial','B',8);
        $this->pdf_p->SetTextColor(0,0,0);


        $this->pdf_p->Cell(20,4,utf8_decode("Cantidad"),1,0,'C');
        $this->pdf_p->Cell(115,4,utf8_decode("Descripcion"),1,0,'L');
        $this->pdf_p->Cell(25,4,'Precio Unitario',1,0,'C');
        $this->pdf_p->Cell(25,4,'Subtotal',1,1,'R');

        $this->pdf_p->SetFont('Arial','',8);        
        $this->pdf_p->Cell(20,5,utf8_decode("4"),0,0,'C');
        $this->pdf_p->Cell(115,5,utf8_decode("Barra Led"),0,0,'L');
        $this->pdf_p->Cell(25,5,'$9.00',0,0,'C');
        $this->pdf_p->Cell(25,5,'$36.00',0,1,'R');  

        $this->pdf_p->Cell(20,5,utf8_decode("5"),0,0,'C');
        $this->pdf_p->Cell(115,5,utf8_decode("maguerita led"),0,0,'L');
        $this->pdf_p->Cell(25,5,'$3.00',0,0,'C');
        $this->pdf_p->Cell(25,5,'$15.00',0,1,'R'); 
         
        $this->pdf_p->Cell(20,5,utf8_decode("6"),0,0,'C');
        $this->pdf_p->Cell(115,5,utf8_decode("Bombillo Led "),0,0,'L');
        $this->pdf_p->Cell(25,5,'$5.00',0,0,'C');
        $this->pdf_p->Cell(25,5,'$30.00',0,1,'R');
         
        $this->pdf_p->Cell(20,5,utf8_decode("4"),0,0,'C');
        $this->pdf_p->Cell(115,5,utf8_decode("Barra Led"),0,0,'L');
        $this->pdf_p->Cell(25,5,'$9.00',0,0,'C');
        $this->pdf_p->Cell(25,5,'$36.00',0,1,'R');

        $this->pdf_p->SetFont('Arial','B',8);

        $this->pdf_p->Ln(10);

        $this->pdf_p->Cell(160, 4,utf8_decode("Subtotal IVA (12 %)"),0,0,'R');
        $this->pdf_p->Cell(25, 4,utf8_decode('$ '.$subtotaliva),0,1,'R'); 

        $this->pdf_p->Cell(160,4,utf8_decode("Subtotal IVA (0 %)"),0,0,'R');
        $this->pdf_p->Cell(25,4,utf8_decode('$'.$subtotalcero),0,1,'R');

        $this->pdf_p->Cell(160,4,utf8_decode("Descuento"),0,0,'R');
        $this->pdf_p->Cell(25,4,utf8_decode('$'.$descuento),0,1,'R');

        $this->pdf_p->Cell(160,4,utf8_decode("Subtotal con Descuento IVA (12 %)"),0,0,'R');
        $this->pdf_p->Cell(25,4,utf8_decode('$'.$subtotaldiva),0,1,'R');

        $this->pdf_p->Cell(160,4,utf8_decode("Subtotal con Descuento IVA (0 %)"),0,0,'R');
        $this->pdf_p->Cell(25,4,utf8_decode('$'.$subtotaldcero),0,1,'R');

        $this->pdf_p->Cell(160,4,utf8_decode("IVA (12%)"),0,0,'R');
        $this->pdf_p->Cell(25,4,utf8_decode('$'.$montoiva),0,1,'R');

        $this->pdf_p->Cell(160,4,utf8_decode("Total"),0,0,'R');
        $this->pdf_p->Cell(25,4,utf8_decode('$'.$total),0,1,'R');

        $this->pdf_p->Output('Proforma','I'); 

    } 



    
}
