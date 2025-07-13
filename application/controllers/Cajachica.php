<?php
/*------------------------------------------------
  ARCHIVO: Almacen.php
  DESCRIPCION: Contiene los métodos relacionados con la Almacen.
  FECHA DE CREACIÓN: 13/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Cajachica extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Cajachica_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {

        $cajas = $this->Cajachica_model->lst_cajachica();
        $data["cajas"] = $cajas;

        $desde = $this->session->userdata("tmp_cajachica_desde");
        $hasta = $this->session->userdata("tmp_cajachica_hasta");
        $caja = $this->session->userdata("tmp_cajachica_caja");

        if ($desde == NULL){
          $hoy = date('Y-m-d');
          $mes = date("m", strtotime($hoy)); 
          $ano = date("Y", strtotime($hoy)); 

          $desde = $this->primer_dia($mes, $ano);
          $hasta = $this->ultimo_dia($mes, $ano);

          $caja = 0;
          if (count($cajas)>0) {$caja = $cajas[0]->id_caja;}

          $this->session->set_userdata("tmp_cajachica_caja", NULL);
          if ($caja != NULL) {
              $this->session->set_userdata("tmp_cajachica_caja", $caja);
          } else {
              $this->session->set_userdata("tmp_cajachica_caja", NULL);
          }

          $this->session->set_userdata("tmp_cajachica_desde", NULL);
          if ($desde != NULL) {
              $this->session->set_userdata("tmp_cajachica_desde", $desde);
          } else {
              $this->session->set_userdata("tmp_cajachica_desde", NULL);
          }

          $this->session->set_userdata("tmp_cajachica_hasta", NULL);
          if ($hasta != NULL) {
              $this->session->set_userdata("tmp_cajachica_hasta", $hasta);
          } else {
              $this->session->set_userdata("tmp_cajachica_hasta", NULL);
          }
        }
        else{
          $mes = date("m", strtotime($desde)); 
        }
        $data["desde"] = $desde;
        $data["hasta"] = $hasta;
        $data["caja"] = $caja;

        $montocaja = $this->Cajachica_model->montocaja($caja, $mes);

        $mov = $this->Cajachica_model->cajachica_resumen($caja);

        $data["base_url"] = base_url();
        $data["montocaja"] = $montocaja;
        $data["mov"] = $mov;
        $data["content"] = "cajachica_listar";
        $this->load->view("layout", $data);

    }

        function ultimo_dia($mes, $ano) { 
          /*  $month = date('m'); */
          /*  $year = date('Y'); */
            $day = date("d", mktime(0,0,0, $mes+1, 0, $ano));
       
            return date('Y-m-d', mktime(0,0,0, $mes, $day, $ano));
        }
       

        function primer_dia($mes, $ano) {
          /*  $month = date('m'); */
          /*  $year = date('Y'); */
            return date('Y-m-d', mktime(0,0,0, $mes, 1, $ano));
        }



    public function mostrarapertura() {
        $cajas = $this->Cajachica_model->lst_cajachica_noabierta();
        $data["cajas"] = $cajas;

        $data["base_url"] = base_url();
        $data["content"] = "cajachica_apertura";
        $this->load->view("layout", $data);
    }

    public function validar_fechaapertura() {
        $caja = $this->input->post('caja'); 
        $fecha = $this->input->post('fecha'); 
        $fecha = str_replace('/', '-', $fecha); 
        $fecha = date("Y-m-d", strtotime($fecha));      
        $ultimafecha = $this->Cajachica_model->lst_cajachica_ultimafecha($caja);
        if ($ultimafecha == NULL){
            $resu = 1;
        }
        else{
            $resu = ($fecha > $ultimafecha) ? 1 : 0;    
            $ultimafecha = date("m/d/Y", strtotime($ultimafecha));      
            $arr['ultimafecha'] = $ultimafecha;
        }
        $arr['resu'] = $resu;
        print json_encode($arr);
    }

    public function existeapertura() {
        $caja = $this->Cajachica_model->existeapertura();
        $arr['resu'] = $caja;
        print json_encode($arr);
    }

    public function cajachica_abiertasucursal() {
        $sucursal = $this->input->post('sucursal'); 
        $caja = $this->Cajachica_model->lst_cajachica_abiertasucursal($sucursal);
        $arr['resu'] = count($caja);
        print json_encode($arr);
    }



    /* Guardar la Apertura */
    public function guardar_apertura(){
        date_default_timezone_set("America/Guayaquil");

        $fecha = $this->input->post('fecha'); 
        $fecha = str_replace('/', '-', $fecha);  
        $fecha = date("Y-m-d", strtotime($fecha)); 
        $monto = $this->input->post('txt_monto');
        $descripcion = $this->input->post('txt_descripcion');    
        $caja = $this->input->post('cmb_caja'); 
/*        $mes = $this->input->post('cmb_mes'); 
        $ano =  date("Y");
        if($mes == 1){ $fecha = $ano.'-01-01';}
        if($mes == 2){ $fecha = $ano.'-02-01';}
        if($mes == 3){ $fecha = $ano.'-03-01';}
        if($mes == 4){ $fecha = $ano.'-04-01';}
        if($mes == 5){ $fecha = $ano.'-05-01';}
        if($mes == 6){ $fecha = $ano.'-06-01';}
        if($mes == 7){ $fecha = $ano.'-07-01';}
        if($mes == 8){ $fecha = $ano.'-08-01';}
        if($mes == 9){ $fecha = $ano.'-09-01';}
        if($mes == 10){ $fecha = $ano.'-10-01';}
        if($mes == 11){ $fecha = $ano.'-11-01';}
        if($mes == 12){ $fecha = $ano.'-12-01';}
*/
        /* SE ACTUALIZA EL REGISTRO DEL USUARIO */
        $resu = $this->Cajachica_model->guardar_apertura($caja, $fecha,$monto,$descripcion);

        //print "<script language='JavaScript'>alert('Los Datos Fueron Actualizados');</script>";

        redirect('Cajachica','refresh');        

      //  $data["base_url"] = base_url();
        
      //  $data["content"] = "cajachica_listar";
      //  $this->load->view("layout", $data);
    }

    public function listadoDataCaja() {

        $desde = $this->session->userdata("tmp_cajachica_desde");
        $hasta = $this->session->userdata("tmp_cajachica_hasta");
        $caja = $this->session->userdata("tmp_cajachica_caja");

        $registro = $this->Cajachica_model->lst_cajachica_movimiento($caja, $desde, $hasta);

        $tabla = "";
        foreach ($registro as $row) {
             @$fec = str_replace('-', '/', $row->fechaapertura); @$fec = date("d/m/Y", strtotime(@$fec)); 
            $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Imprimir Movimiento\" id=\"'.$row->id_mov.'\" class=\"btn bg-navy color-palette btn-xs btn-grad caja_print\"><i class=\"fa fa-print\"></i></a> </div>';
            $tabla.='{"fechaapertura":"' . $fec . '", 
                      "descripcion":"' . $row->descripcion . '",
                      "montoapertura":"' . $row->montoapertura . '",   
                      "estatus":"' . $row->estatus . '",                                                               
                      "fechacierre":"' . $row->fechacierre . '",
                      "montocierre":"' . $row->montocierre . '",  
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    } 

    /* Cargar Ingresos de Caja Chica */
    public function cargaringresocaja() {
        $cajas = $this->Cajachica_model->lst_cajachica();
        $data["cajas"] = $cajas;

        $desde = $this->session->userdata("tmp_cajaingreso_desde");
        $hasta = $this->session->userdata("tmp_cajaingreso_hasta");
        $caja = $this->session->userdata("tmp_cajaingreso_caja");

/*
        $hoy = date('Y-m-d');
        $mes = date("m", strtotime($hoy)); 
        $ano = date("Y", strtotime($hoy)); 

        $desde = $this->primer_dia($mes, $ano);
        $hasta = $this->ultimo_dia($mes, $ano);
*/
        if ($desde == NULL){
            $desde = date('Y-m-d');
            $hasta = date('Y-m-d');

            $caja = 0;
            if (count($cajas) > 0) {$caja = $cajas[0]->id_caja;}

            $this->session->set_userdata("tmp_cajaingreso_desde", NULL);
            if ($desde != NULL) {
                $this->session->set_userdata("tmp_cajaingreso_desde", $desde);
            } else {
                $this->session->set_userdata("tmp_cajaingreso_desde", NULL);
            }

            $this->session->set_userdata("tmp_cajaingreso_hasta", NULL);
            if ($hasta != NULL) {
                $this->session->set_userdata("tmp_cajaingreso_hasta", $hasta);
            } else {
                $this->session->set_userdata("tmp_cajaingreso_hasta", NULL);
            }

            $this->session->set_userdata("tmp_cajaingreso_caja", NULL);
            if ($caja != NULL) {
                $this->session->set_userdata("tmp_cajaingreso_caja", $caja);
            } else {
                $this->session->set_userdata("tmp_cajaingreso_caja", NULL);
            }
           
        }    

        //$montocaja = $this->Cajachica_model->montocaja($mes);
        $data["desde"] = $desde;
        $data["hasta"] = $hasta;
        $data["caja"] = $caja;
        $data["montocaja"] = 0;//$montocaja;
        $data["base_url"] = base_url();
        $data["content"] = "cajachica_ingreso";
        $this->load->view("layout", $data);
    }

    public function listadoDataCajaIngreso() {

        $desde = $this->session->userdata("tmp_cajaingreso_desde");
        $hasta = $this->session->userdata("tmp_cajaingreso_hasta");
        $caja = $this->session->userdata("tmp_cajaingreso_caja");

        $registro = $this->Cajachica_model->lst_cajaingreso($caja, $desde, $hasta);

        $tabla = "";
        foreach ($registro as $row) {
            @$fec = str_replace('-', '/', $row->fechaingreso); @$fec = date("d/m/Y", strtotime(@$fec)); 
            $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_ingreso.'\" class=\"btn btn-danger btn-xs btn-grad caja_del\"><i class=\"fa fa-trash-o\"></i></a> </div>';
            $tabla.='{"id":"' . $row->id_ingreso . '",
                      "fechaingreso":"' . $fec . '", 
                      "monto":"' . $row->monto . '",   
                      "numeroingreso":"' . $row->numeroingreso . '",
                      "descripcion":"' . $row->descripcion . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    } 

/*

*/

    public function tmp_cajaingreso(){
        $caja = $this->input->post("caja");

        /* fecha desde */
        $fecdesde = $this->input->post("desde");
        $desde = str_replace('/', '-', $fecdesde); 
        $desde = date("Y-m-d", strtotime($desde));
        /* fecha hasta */
        $fechasta = $this->input->post("hasta");
        $hasta = str_replace('/', '-', $fechasta); 
        $hasta = date("Y-m-d", strtotime($hasta));

        $this->session->set_userdata("tmp_cajaingreso_caja", NULL);
        if ($caja != NULL) {
            $this->session->set_userdata("tmp_cajaingreso_caja", $caja);
        } else {
            $this->session->set_userdata("tmp_cajaingreso_caja", NULL);
        }

        $this->session->set_userdata("tmp_cajaingreso_desde", NULL);
        if ($desde != NULL) {
            $this->session->set_userdata("tmp_cajaingreso_desde", $desde);
        } else {
            $this->session->set_userdata("tmp_cajaingreso_desde", NULL);
        }

        $this->session->set_userdata("tmp_cajaingreso_hasta", NULL);
        if ($hasta != NULL) {
            $this->session->set_userdata("tmp_cajaingreso_hasta", $hasta);
        } else {
            $this->session->set_userdata("tmp_cajaingreso_hasta", NULL);
        }

        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function tmp_listado(){

      $caja = $this->input->post("caja");

      /* fecha desde */
      $fecdesde = $this->input->post("desde");
      $desde = str_replace('/', '-', $fecdesde); 
      $desde = date("Y-m-d", strtotime($desde));
      /* fecha hasta */
      $fechasta = $this->input->post("hasta");
      $hasta = str_replace('/', '-', $fechasta); 
      $hasta = date("Y-m-d", strtotime($hasta));

      $dmes = date("m", strtotime($desde)); 
      $dano = date("Y", strtotime($desde)); 

      $hmes = date("m", strtotime($hasta)); 
      $hano = date("Y", strtotime($hasta));


      $desde = $this->primer_dia($dmes, $dano);
      $hasta = $this->ultimo_dia($hmes, $hano);

      $this->session->set_userdata("tmp_cajachica_caja", NULL);
      if ($caja != NULL) {
          $this->session->set_userdata("tmp_cajachica_caja", $caja);
      } else {
          $this->session->set_userdata("tmp_cajachica_caja", NULL);
      }

      $this->session->set_userdata("tmp_cajachica_desde", NULL);
      if ($desde != NULL) {
          $this->session->set_userdata("tmp_cajachica_desde", $desde);
      } else {
          $this->session->set_userdata("tmp_cajachica_desde", NULL);
      }

      $this->session->set_userdata("tmp_cajachica_hasta", NULL);
      if ($hasta != NULL) {
          $this->session->set_userdata("tmp_cajachica_hasta", $hasta);
      } else {
          $this->session->set_userdata("tmp_cajachica_hasta", NULL);
      }

      $montocaja = $this->Cajachica_model->montocaja($caja, $dmes);

      $mov = $this->Cajachica_model->cajachica_resumen($caja);

      $arr['montocaja'] = $montocaja;
      $arr['mov'] = $mov;
      print json_encode($arr);
    }
    
    public function agregar(){  
        $data["base_url"] = base_url();
        $this->load->view("cajachica_add", $data);
    
    }  


    public function guardar(){  
      date_default_timezone_set("America/Guayaquil");
      $idusu = $this->session->userdata("sess_id");
      $fecha = $this->input->post("fecha");
      $fecha = str_replace('/', '-', $fecha); 
      $fecha = date("Y-m-d", strtotime($fecha));

      $nroingreso = $this->input->post("txt_nroingreso");
      $monto = $this->input->post("txt_monto");
      $des = $this->input->post("txt_des"); 

      $caja = $this->session->userdata("tmp_cajaingreso_caja");      

      $add = $this->Cajachica_model->add_ingreso($caja, $fecha, $nroingreso, $monto, $des, $idusu);

      $arr['resu'] = 1;
      print json_encode($arr);
    
    }  

    public function eliminar(){  

      $idcaja = $this->input->post("id"); 
      $del = $this->Cajachica_model->del_caja($idcaja);

      $arr['resu'] = 1;
      print json_encode($arr);
    
    }  



    public function tmp_cajachica_id(){
        $id = $this->input->post("id");

        $this->session->set_userdata("tmp_cajachica_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_cajachica_id", $id);
        } else {
            $this->session->set_userdata("tmp_cajachica_id", NULL);
        }

        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function reportemovimiento(){

        $id = $this->session->userdata("tmp_cajachica_id");

        $cajachica = $this->Cajachica_model->lst_cajachica_mov_id($id);

        $reporte = $this->Cajachica_model->reportemovimiento($id);

        $data["base_url"] = base_url();
        $data["cajachica"] = $cajachica;
        $data["reporte"] = $reporte;
        $this->load->view("cajachica_reportemov", $data);   
      
    }

    /* Exportar Venta a Excel */
    public function reportemovimientoXLS(){
        $id = $this->session->userdata("tmp_cajachica_id");
        $cajachica = $this->Cajachica_model->lst_cajachica_mov_id($id);
        $reporte = $this->Cajachica_model->reportemovimiento($id);

        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('MovimientoCajaChica');
        //set cell A1 content with some text
        $fecdesde = str_replace('-', '/', $cajachica->fechaapertura); $fecdesde = date("d/m/Y", strtotime($fecdesde));  
        $fechasta = str_replace('-', '/', $cajachica->fechacierre); $fechasta = date("d/m/Y", strtotime($fechasta));  
        $this->excel->getActiveSheet()->setCellValue('A1', 'Movimiento de Caja Chica (' . $fecdesde . ' - ' . $fechasta . ')');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:E1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

        $this->excel->getActiveSheet()->setCellValue('C3', 'SALDO APERTURA');
        $this->excel->getActiveSheet()->setCellValue('D3', number_format($cajachica->montoapertura,2));
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('A5', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B5', '#Documento');
        $this->excel->getActiveSheet()->setCellValue('C5', 'Tipo');
        $this->excel->getActiveSheet()->setCellValue('D5', 'Valor');
        $this->excel->getActiveSheet()->setCellValue('E5', 'Descripcion');

        $this->excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);

        $total = $cajachica->montoapertura;
        $fila = 6;
        foreach ($reporte as $mov) {
          if ($mov->tipo == 'Ingreso'){
            $total = $total  + $mov->valor;
          } else {
            $total = $total  - $mov->valor;
          }

          $fec = str_replace('-', '/', $mov->fecha); $fec = date("d/m/Y", strtotime($fec));  
          $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
          $this->excel->getActiveSheet()->setCellValue('B'.$fila, $mov->numerodoc);
          $this->excel->getActiveSheet()->setCellValue('C'.$fila, $mov->tipo);
          $this->excel->getActiveSheet()->setCellValue('D'.$fila, number_format(@$mov->valor,2,",","."));
          $this->excel->getActiveSheet()->setCellValue('E'.$fila, $mov->descripcion);

          $fila++;          
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('C'.$fila, 'SALDO FINAL');
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, number_format($total,2));
        $this->excel->getActiveSheet()->getStyle('C'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D'.$fila)->getFont()->setBold(true);

        
        $filename='reporteventa.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        $objWriter->save('php://output');        
    }  

    public function cierre(){
        $cajas = $this->Cajachica_model->lst_cajachica_abierta();
        $data["cajas"] = $cajas;
        $caja = 0;
        if (count($cajas) > 0){
            $caja = $cajas[0]->id_caja; 
        }

        $cierre = $this->Cajachica_model->cajachica_resumen($caja);
        $data["cierre"] = $cierre;

        $data["base_url"] = base_url();
        $data["content"] = "cajachica_cierre";
        $this->load->view("layout", $data);      
    }

    public function actualiza_caja_cierre() {
        $caja = $this->input->post("caja");     
        $cierre = $this->Cajachica_model->cajachica_resumen($caja);
        $arr['cierre'] = $cierre;
        print json_encode($arr);
    }

    public function guardar_cierre(){
      date_default_timezone_set("America/Guayaquil");
      $caja = $this->input->post("cmb_caja");     
      $obs = $this->input->post("txt_obs");     
      $fecha = $this->input->post("fecha");
      $fecha = str_replace('/', '-', $fecha); 
      $fecha = date("Y-m-d", strtotime($fecha));      
      $monto = $this->input->post("txt_totalcaja");
      $monto = str_replace(',','', $monto );
      $cerrar = $this->Cajachica_model->guardar_cierre($caja, $fecha, $monto, $obs);
      redirect('Cajachica','refresh');   
   
    }

    public function existecajachica_noabierta() {
        $cajas = $this->Cajachica_model->lst_cajachica_noabierta();
        $arr['resu'] = count($cajas);
        print json_encode($arr);
    }

    public function existecajachica_abierta() {
        $cajas = $this->Cajachica_model->lst_cajachica_abierta();
        $arr['resu'] = count($cajas);
        print json_encode($arr);
    }

}

?>