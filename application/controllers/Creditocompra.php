<?php
/*------------------------------------------------
  ARCHIVO: Credito.php
  DESCRIPCION: Contiene los métodos relacionados con Cuentas por pagar.
  FECHA DE CREACIÓN: 15/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Creditocompra extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Creditocompra_model");
        $this->load->Model("Proveedor_model");
        $this->load->Model("Empresa_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        /* Listado de las areas junto con las mesas */
        $empresas = $this->Empresa_model->lst_empresa();
        $proveedores = $this->Proveedor_model->sel_prov();
        $estados = $this->Creditocompra_model->lst_estadocredito();
        $empresaseleccionada = $this->session->userdata("tmp_creditocompraempresa");
        $proveedorseleccionado = $this->session->userdata("tmp_creditoproveedor");
        $estadoseleccionado = $this->session->userdata("tmp_creditocompraestado");
        if ($estadoseleccionado == NULL) $estadoseleccionado = 2;
        if (($empresaseleccionada == NULL) && (count($empresas) > 0)) $empresaseleccionada = $empresas[0]->id_emp;
        $data["empresas"] = $empresas;
        $data["proveedores"] = $proveedores;
        $data["estados"] = $estados;
        $data["proveedorseleccionado"] = $proveedorseleccionado;
        $data["estadoseleccionado"] = $estadoseleccionado;
        $data["empresaseleccionada"] = $empresaseleccionada;
        $data["base_url"] = base_url();
        $data["content"] = "creditocompra";
        $this->load->view("layout", $data);
    }


    /* SESION TEMPORAL PARA Filtro de Credito */
     public function tmp_filtrocredito() {
        $idproveedor = $this->session->userdata("tmp_creditoproveedor");
        if ($idproveedor != NULL) { $this->session->set_userdata("tmp_creditoproveedor", $idproveedor); } 
        else { $this->session->set_userdata("tmp_creditoproveedor", NULL); }

        $idestado = $this->session->userdata("tmp_creditocompraestado");
        if ($idestado != NULL) { $this->session->set_userdata("tmp_creditocompraestado", $idestado); } 
        else { $this->session->set_userdata("tmp_creditocompraestado", 2); }

        $empresa = $this->session->userdata("tmp_creditocompraempresa");
        if ($empresa != NULL) { $this->session->set_userdata("tmp_creditocompraempresa", $empresa); } 
        else { $this->session->set_userdata("tmp_creditocompraempresa", NULL); }
    }

    /* SESION TEMPORAL PARA Filtro de Credito */
     public function upd_filtrocredito() {
        $idproveedor = $this->input->post("proveedor");
        if ($idproveedor != NULL) { $this->session->set_userdata("tmp_creditoproveedor", $idproveedor); } 
        else { $this->session->set_userdata("tmp_creditoproveedor", NULL); }
        $idproveedor = $this->session->userdata("tmp_creditoproveedor");

        $idestado = $this->input->post("estado");
        if ($idestado != NULL) { $this->session->set_userdata("tmp_creditocompraestado", $idestado); } 
        else { $this->session->set_userdata("tmp_creditocompraestado", NULL); }
        $idestado = $this->session->userdata("tmp_creditocompraestado");

        $empresa = $this->input->post("empresa");
        if ($empresa != NULL) { $this->session->set_userdata("tmp_creditocompraempresa", $empresa); } 
        else { $this->session->set_userdata("tmp_creditocompraempresa", NULL); }

        $arr['res'] = 1;
        print json_encode($arr);               
    }

    public function listadoCreditos() {
        $this->tmp_filtrocredito();

        $proveedor = $this->session->userdata("tmp_creditoproveedor");
        $estado = $this->session->userdata("tmp_creditocompraestado");
        $empresa = $this->session->userdata("tmp_creditocompraempresa");
        if (($empresa == NULL) or ($empresa == '')) $empresa = 0;
        if (($proveedor == NULL) or ($proveedor == '')) $proveedor = 0;
        if (($estado == NULL) or ($estado == '')) $estado = 0;

        $registro = $this->Creditocompra_model->lst_creditos($empresa, $proveedor, $estado);
        $tabla = "";
        foreach ($registro as $row) {
          $ver = '<div class=\"text-center\"> <a href=\"#\" title=\"Abonos\" id=\"'.$row->id_comp.'\" class=\"btn btn-warning btn-xs btn-grad edit_abono\"><i class=\"fa fa-plus\"></i></a> <a href=\"#\" title=\"Imprimir Credito\" id=\"'.$row->id_comp.'\" class=\"btn bg-navy color-palette btn-xs btn-grad cred_print\"><i class=\"fa fa-print\"></i></a> </div>';
               
          $tabla.='{  "proveedor":"' . $row->nom_proveedor . '",
                      "fecha":"' . $row->fecha . '",
                      "factura":"' . $row->nro_factura . '",
                      "montofactura":"' . $row->montototal . '",   
                      "fechalimite":"' . $row->fecha_pago . '",                                                               
                      "dias":"' . $row->dias . '",                                                               
                      "estado":"' . $row->nombre_estado . '",                                                               
                      "estatus":"' . $row->estatus . '",                                                               
                      "vencido":"' . $row->vencido . '",                                                               
                      "montopendiente":"' . ($row->montototal - $row->abonado) . '",                                                               
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }  

 
    public function get_total_credito(){
        $proveedor = $this->session->userdata("tmp_creditoproveedor");
        $estado = $this->session->userdata("tmp_creditocompraestado");
        $empresa = $this->session->userdata("tmp_creditocompraempresa");
        if (($empresa == NULL) or ($empresa == '')) $empresa = 0;
        if (($proveedor == NULL) or ($proveedor == '')) $proveedor = 0;
        if (($estado == NULL) or ($estado == '')) $estado = 0;

        $totalg = $this->Creditocompra_model->total_creditos($empresa, $proveedor, $estado);
        if ($totalg != NULL){
          $arr['total'] = $totalg[0]->total;
          $arr['pendiente'] = $totalg[0]->pendiente;

        } else{
          $arr['total'] = 0;
          $arr['pendiente'] = 0;
        }
        
        print json_encode($arr);               
    }

    public function reporte_credito(){
        $proveedor = $this->session->userdata("tmp_creditoproveedor");
        $estado = $this->session->userdata("tmp_creditocompraestado");
        $empresa = $this->session->userdata("tmp_creditocompraempresa");
        if (($empresa == NULL) or ($empresa == '')) $empresa = 0;
        if (($proveedor == NULL) or ($proveedor == '')) $proveedor = 0;
        if (($estado == NULL) or ($estado == '')) $estado = 0;
    
        $credito = $this->Creditocompra_model->lst_creditos($empresa, $proveedor, $estado);
        if ($proveedor == 0) {$nomproveedor = "Todos los proveedores";}
        else {
          $obj = $this->Proveedor_model->sel_provee_id($proveedor);
          $nomproveedor = "Proveedor: " . $obj->nom_proveedor;
        }
        if ($estado == 0) {$nomestado = "Todos los creditos";}
        else {
          $obj = $this->Creditocompra_model->lst_estadocredito_id($estado);
          $nomestado = "Creditos en Estado: " . $obj->desc_estatus;
        }
        $data["base_url"] = base_url();
        $data["nomproveedor"] = $nomproveedor;
        $data["nomestado"] = $nomestado;
        $data["estado"] = $estado;
        $data["credito"] = $credito;
        $this->load->view("creditocompra_reporte", $data);     
    }    

    /* Exportar Compra a Excel */
    public function reportecreditoXLS(){
        $proveedor = $this->session->userdata("tmp_creditoproveedor");
        $estado = $this->session->userdata("tmp_creditocompraestado");
        $empresa = $this->session->userdata("tmp_creditocompraempresa");
        if (($empresa == NULL) or ($empresa == '')) $empresa = 0;
        if (($proveedor == NULL) or ($proveedor == '')) $proveedor = 0;
        if (($estado == NULL) or ($estado == '')) $estado = 0;
    
        $credito = $this->Creditocompra_model->lst_creditos($empresa, $proveedor, $estado);
        if ($proveedor == 0) {$nomproveedor = "Todos los proveedores";}
        else {
          $obj = $this->Proveedor_model->sel_provee_id($proveedor);
          $nomproveedor= "Proveedor: " . $obj->nom_proveedor;
        }
        if ($estado == 0) {$nomestado = "Todos los creditos";}
        else {
          $obj = $this->Creditocompra_model->lst_estadocredito_id($estado);
          $nomestado = "Creditos en Estado: " . $obj->desc_estatus;
        }
 
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('ReporteCredito');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Cuentas por Pagar (' . $nomproveedor . '  -  ' . $nomestado . ')');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:J1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Fecha');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Proveedor');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Factura');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Fecha Plazo');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Dias Plazo');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Estado');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Monto Factura');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Pendiente');

        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);

        $total = 0;
        $fila = 4;
        foreach ($credito as $det) {
          $total += $det->montototal - $det->abonado;         

          $fec = str_replace('-', '/', $det->fecha); 
          $fec = date("d/m/Y", strtotime($fec));  
          $this->excel->getActiveSheet()->setCellValue('A'.$fila, $fec);
          $this->excel->getActiveSheet()->setCellValue('B'.$fila, $det->nom_proveedor);
          $this->excel->getActiveSheet()->setCellValue('C'.$fila, $det->nro_factura);
          $this->excel->getActiveSheet()->setCellValue('D'.$fila, $det->fecha_pago);
          $this->excel->getActiveSheet()->setCellValue('E'.$fila, $det->dias);
          $this->excel->getActiveSheet()->setCellValue('F'.$fila, $det->nombre_estado);
          $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($det->montototal,2));
          $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($det->montototal - $det->abonado,2));

          $fila++;          
        }    
        $fila++;          

        $this->excel->getActiveSheet()->setCellValue('E'.$fila, 'TOTAL PENDIENTE');
        $this->excel->getActiveSheet()->setCellValue('H'.$fila, number_format($total,2));
        $this->excel->getActiveSheet()->getStyle('E'.$fila)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H'.$fila)->getFont()->setBold(true);

         
        $filename='reportecompra.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        $objWriter->save('php://output');        
    }  


}

?>