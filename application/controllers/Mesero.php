<?php

/*------------------------------------------------
  ARCHIVO: Mesero.php
  DESCRIPCION: Contiene los métodos relacionados con la Mesero.
  FECHA DE CREACIÓN: 07/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();


class Mesero extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Mesero_model");
        $this->load->Model("Usuario_model");
        $this->load->Model("Cliente_model");        
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "meseros";
        $this->load->view("layout", $data);
    }

    /* CARGA DE DATO AL DATATABLE */
    public function listadoDataMesero() {

        $registro = $this->Mesero_model->sel_mesero();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Ver\" id=\"'.$row->id_mesero.'\" class=\"btn btn-success btn-xs btn-grad edi_mesero\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_mesero.'\" class=\"btn btn-danger btn-xs btn-grad del_mesero\"><i class=\"fa fa-trash-o\"></i></a> <a href=\"#\" title=\"Exportar Clientes\" id=\"'.$row->id_mesero.'\" class=\"btn btn-success btn-xs btn-grad cli_mesero\"><i class=\"fa fa-file-excel-o fa-1x\"></i></a></div>';
            $tabla.='{"id":"' . $row->id_mesero . '",
                      "ced":"' . $row->ced_mesero . '",            
                      "nombre":"' . addslashes($row->nom_mesero) . '",
                      "estatus":"' . $row->estatus_mesero . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }   

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_mesero() {
        $this->session->unset_userdata("tmp_mesero_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_mesero_id", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_mesero_id", $id); } 
        else { $this->session->set_userdata("tmp_mesero_id", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    /* ABRIR VENTANA PARA AGREGAR MESERO */
    public function add_mesero(){
        $ident = $this->Mesero_model->identificacion();
        $data["ident"] = $ident;        
        $data["base_url"] = base_url();
        $this->load->view("mesero_add", $data);
    }

    /* ABRIR VENTANA PARA EDITAR MESERO */
    public function edi_mesero(){
        $idmese = $this->session->userdata("tmp_mesero_id");
        $mesero = $this->Mesero_model->sel_mesero_id($idmese);
        $ident = $this->Mesero_model->identificacion();
        $data["mese"] = $mesero; 
        $data["ident"] = $ident;        
        $data["base_url"] = base_url();
        $this->load->view("mesero_add", $data);
    }

    /* ABRIR VENTANA PARA EDITAR MESERO */
    public function del_mesero(){
        $idmese = $this->session->userdata("tmp_mesero_id");
        $mesero = $this->Mesero_model->sel_mesero_id($idmese);
        $ident = $this->Mesero_model->identificacion();
        $data["mese"] = $mesero; 
        $data["ident"] = $ident;        
        $data["base_url"] = base_url();
        $this->load->view("mesero_del", $data);
    }
    
    /* GUARDAR O MODIFICAR DATOS DEL MESERO */
    public function guardar(){
        $idmese = $this->input->post('txt_idmese');
        $tipide = $this->input->post('cmb_tip_ide');
        $nroide = trim($this->input->post('txt_nro_ident'));
        $nombre = trim($this->input->post('txt_nom'));
        $correo = trim($this->input->post('txt_mail'));
        if($correo == ''){ $correo = NULL; }
        $telf = trim($this->input->post('txt_telf'));
        if($telf == ''){ $telf = NULL; }
        $dir = trim($this->input->post('txt_dir'));
        if($dir == ''){ $dir = NULL; }
        $est = $this->input->post('cmb_est');
        $fot = NULL;

        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idmese != 0){
            /* SE ACTUALIZA EL REGISTRO DEL MESERO */
            $resu = $this->Mesero_model->mese_upd($idmese, $tipide, $nroide, $nombre, $correo, $telf, $dir, $est, $fot);
        } else {
            /* SE GUARDA EL REGISTRO DEL MESERO */
            $resu = $this->Mesero_model->mese_add($tipide, $nroide, $nombre, $correo, $telf, $dir, $est, $fot);
        }
        
        $arr['mens'] = $idmese ;
        print json_encode($arr); 
    }

    /* ELIMINAR MESERO DE LA BASE DE DATOS */
    public function eliminar(){
        $idmes = $this->input->post('txt_idmes');
        $del = $this->Mesero_model->mese_del($idmes);
        $arr['mens'] = $idmes;
        print json_encode($arr);
    }


    public function reporte_clientes_vendedorXLS(){
        date_default_timezone_set("America/Guayaquil");
        $id = $this->session->userdata("tmp_mesero_id");
        $objusu = $this->Usuario_model->get_usuario_vendedor($id);
        $vendedor = "";
        if (count($objusu) > 0){
            $vendedor = '"' . $objusu[0]->nom_usu . ' ' . $objusu[0]->ape_usu . '"';
        }
        $registros = $this->Cliente_model->sel_clientevendedor($id);
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteClientesVendedor');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Clientes del Vendedor ' . $vendedor);
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:G1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Nombre Cliente:');
        $this->excel->getActiveSheet()->setCellValue('B3', 'C.I./R.U.C.');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Teléfono');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Correo');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Dirección');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Ciudad');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Matrícula');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Categoría Venta');

        $this->excel->getActiveSheet()->getStyle('A3:H3')->getFont()->setBold(true);

        $fila = 4;

        foreach ($registros as $obj) {
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $obj->nom_cliente);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $obj->ident_cliente);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $obj->telefonos_cliente);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $obj->correo_cliente);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, $obj->direccion_cliente);
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, $obj->ciudad_cliente);
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, $obj->placa_matricula);
            $this->excel->getActiveSheet()->setCellValue('H'.$fila, $obj->categoriaventa);
                       
            $fila++;          
        }    
        $fila++;          

        foreach(range('A','H') as $columnID)
        {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }


        $this->excel->getActiveSheet()->freezePane('A4');

        $filename='reportecliente.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    ob_end_clean();
        $objWriter->save('php://output');        
    }

}

?>