<?php
/*------------------------------------------------
  ARCHIVO: Correo.php
  DESCRIPCION: Contiene los métodos relacionados con la Correo.
  FECHA DE CREACIÓN: 25/09/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();
class Correo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Correo_model");
    }

    public function index() {
        $correo = $this->Correo_model->sel_correo();
        $data["correo"] = $correo;
        $data["base_url"] = base_url();
        $data["content"] = "correo";
        $this->load->view("layout", $data);
    }

    public function guardar() { 
        $smtp = $this->input->post("txt_smtp");    
        $puerto = $this->input->post("txt_puerto");
        $user = $this->input->post("txt_user");
        $pwd = $this->input->post("txt_pwd");

        $add = $this->Correo_model->add_correo($smtp, $puerto, $user, $pwd);
        ?>
            <script type="text/javascript"> alert("LOS DATOS FUERON ACTUALIZADOS")</script>
        <?php  
        redirect('correo','refresh');
    }

    public function correo_test() {
        $smtp = $this->input->post("smtp");    
        $puerto = $this->input->post("puerto");
        $usuario = $this->input->post("usuario");
        $clave = $this->input->post("clave");

        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => $smtp,
            'smtp_user' => $usuario,
            'smtp_pass' => $clave, 
            'smtp_port' => $puerto,
            'smtp_crypto' => 'ssl',
            'smtp_timeout' => '30',
            'mailtype' => 'html',
            'wordwrap' => TRUE,
            'charset' => 'utf-8'
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($usuario);
        $this->email->subject('Prueba de Correo');
        $this->email->message('Prueba de Correo');
        $this->email->to($usuario);
        try{
          if($this->email->send(FALSE)){
            return print json_encode(1);
          }else {
            return print json_encode(0);
          }
        }  
        catch (Exception $e) {
          //var_dump($e->getMessage());
          return print json_encode(0);
        }  
    }     


/*
    
    pruebas envio de correo adjunto

*/


    public function reporte(){

        //$venta = $this->session->userdata("tmp_rpt_venta");
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Reporte Y');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte X');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Factura');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Tipo');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Punto');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Cliente');


        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);

        $fila = 4;

        $this->excel->getActiveSheet()->setCellValue('A'.$fila, '06-07-2018');
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, '001-001-012345678');
        $this->excel->getActiveSheet()->setCellValue('C'.$fila, 'Contado');            
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, 'Punto 1');
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, 'Consumidor Final');

        $ndir = FCPATH.'/doc/excel.xlsx';
          
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();

        $objWriter->save($ndir);
  
    }  


    public function ruta(){
        $ruta = base_url().'doc/';
        print FCPATH;
        //print dirname(__DIR__);
       // $dir = $_SERVER['SCRIPT_FILENAME'];
       // $ndir = str_replace("index.php", "", $dir);
       // $ndir.='doc/excel.xlsx';
        //print $ndir;
    }

    public function correoenviar() {
      $correo = $this->Correo_model->env_correo();

      $config = array(
        'protocol' => 'smtp',
        'smtp_host' => $correo->smtp,
        'smtp_user' => $correo->usuario,
        'smtp_pass' => $correo->clave, 
        'smtp_port' => $correo->puerto,
        'smtp_crypto' => 'ssl',
        'mailtype' => 'html',
        'wordwrap' => TRUE,
        'charset' => 'utf-8'
      );

         $this->load->library('email', $config);
         $this->email->set_newline("\r\n");
         $this->email->from($correo->usuario);
         $this->email->subject('Cierre de Caja');
        $this->email->attach(FCPATH.'/doc/excel.xlsx');
         $this->email->message('Envío de Precios');
        // $this->email->to($correo->empresa);
         $this->email->to('info@factufacil-ec.com');
         if($this->email->send(FALSE)){
            return 1;
            /* panchin.romero@gmail.com */
            // echo "enviado<br/>";
            // echo $this->email->print_debugger(array('headers'));
         }else {
            return 0;
            // echo "fallo <br/>";
            // echo "error: ".$this->email->print_debugger(array('headers'));
         }
    }     
  

    public function setemail() { 
        $email="xyz@gmail.com"; 
        $subject="some text"; 
        $message="some text"; 
        $this->sendEmail($email,$subject,$message); 
    }
    
    public function sendEmail($email,$subject,$message) { 
        $config = Array( 'protocol' => 'smtp', 
                         'smtp_host' => 'ssl://smtp.googlemail.com', 
                         'smtp_port' => 465, 
                         'smtp_user' => 'abc@gmail.com', 
                         'smtp_pass' => 'passwrd', 
                         'mailtype' => 'html', 
                         'charset' => 'iso-8859-1', 
                         'wordwrap' => TRUE ); 
        $this->load->library('email', $config); 
        $this->email->set_newline("\r\n"); 
        $this->email->from('abc@gmail.com'); 
        $this->email->to($email); 
        $this->email->subject($subject); 
        $this->email->message($message); 
        $this->email->attach('C:\Users\xyz\Desktop\images\abc.png'); 
        if($this->email->send()) { 
            echo 'Email send.';
        } else { 
            show_error($this->email->print_debugger()); 
        } 
    } 




}

?>