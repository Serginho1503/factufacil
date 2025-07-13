<?php
/*------------------------------------------------
  ARCHIVO: Inventario.php
  DESCRIPCION: Contiene los métodos relacionados con la actualizacion de Inventario.
  FECHA DE CREACIÓN: 13/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();
class Inventario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Inventario_model");
        $this->load->Model("Unidades_model");
        $this->load->Model("Sucursal_model");
        $this->load->Model("Almacen_model");
        $this->load->Model("contabilidad/Contab_categoria_model");  
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $desde = date("Y-m-d"); 
        $hasta = date("Y-m-d") . ' 23:59';         

        $this->session->set_userdata("tmp_inv_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_inv_desde", $desde); } 
        else { $this->session->set_userdata("tmp_inv_desde", NULL); }
        $this->session->set_userdata("tmp_inv_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_inv_hasta", $hasta); } 
        else { $this->session->set_userdata("tmp_inv_hasta", NULL); }

        $data["base_url"] = base_url();
        $data["content"] = "inventario";
        $this->load->view("layout", $data);
    }

    /* CARGA DE DATOS AL DATATABLE */
    public function listadoDataInventario() {
        $desde = $this->session->userdata("tmp_inv_desde");
        $hasta = $this->session->userdata("tmp_inv_hasta");  
     
        $registro = $this->Inventario_model->lst_inventario($desde, $hasta);
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->id_kardex.'\" class=\"btn btn-info btn-xs btn-grad inv_print\"><i class=\"fa fa-print\"></i></a></div>';
            $tabla.='{"id":"' . $row->id_kardex . '",
                      "nombre":"' . addslashes($row->pro_nombre) . '",
                      "codigoauxiliar":"' . addslashes($row->pro_codigoauxiliar) . '",
                      "codigobarra":"' . addslashes($row->pro_codigobarra) . '",
                      "fecha":"' . $row->fecha . '",
                      "documento":"' . addslashes($row->documento) . '",
                      "detalle":"' . addslashes($row->detalle) . '",   
                      "tipo":"' . $row->tipo . '",                                                               
                      "cantidad":"' . $row->cantidad . '",                                                               
                      "valorunitario":"' . $row->valorunitario . '",                                                               
                      "costototal":"' . $row->costototal . '",                                                               
                      "saldocantidad":"' . $row->saldocantidad . '",                                                               
                      "saldovalorunitario":"' . $row->saldovalorunitario . '",                                                               
                      "saldocostototal":"' . $row->saldocostototal . '",                                                               
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_inv_fecha() {
        $this->session->unset_userdata("tmp_inv_desde"); 
        $this->session->unset_userdata("tmp_inv_hasta");

        $fecdesde = $this->input->post("desde");
        $desde = str_replace('/', '-', $fecdesde); 
        $desde = date("Y-m-d", strtotime($desde));

        $fechasta = $this->input->post("hasta");
        $hasta = str_replace('/', '-', $fechasta); 
        $hasta = date("Y-m-d", strtotime($hasta));    
        $hasta.= ' 23:59';    

        $this->session->set_userdata("tmp_inv_desde", NULL);
        if ($desde != NULL) {
            $this->session->set_userdata("tmp_inv_desde", $desde);
        } else {
            $this->session->set_userdata("tmp_inv_desde", NULL);
        }

        $this->session->set_userdata("tmp_inv_hasta", NULL);
        if ($hasta != NULL) {
            $this->session->set_userdata("tmp_inv_hasta", $hasta);
        } else {
            $this->session->set_userdata("tmp_inv_hasta", NULL);
        }

        $arr['resu'] = 1;
        print json_encode($arr);
    }    


    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_docinv_fecha() {
        $this->session->unset_userdata("tmp_docinv_desde"); 
        $this->session->unset_userdata("tmp_docinv_hasta");
        $fecdesde = $this->input->post("desde");
        $desde = str_replace('/', '-', $fecdesde); 
        $desde = date("Y-m-d", strtotime($desde));
        $fechasta = $this->input->post("hasta");
        $hasta = str_replace('/', '-', $fechasta); 
        $hasta = date("Y-m-d", strtotime($hasta));    
        $this->session->set_userdata("tmp_docinv_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_docinv_desde", $desde); } 
        else { $this->session->set_userdata("tmp_docinv_desde", NULL); }
        $this->session->set_userdata("tmp_docinv_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_docinv_hasta", $hasta); } 
        else { $this->session->set_userdata("tmp_docinv_hasta", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    }    

    public function cargar_inventariomovimiento() {
        $desde = date("Y-m-d"); 
        $hasta = date("Y-m-d");       
        $this->session->set_userdata("tmp_docinv_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_docinv_desde", $desde); } 
        else { $this->session->set_userdata("tmp_docinv_desde", NULL); }
        $this->session->set_userdata("tmp_docinv_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_docinv_hasta", $hasta); } 
        else { $this->session->set_userdata("tmp_docinv_hasta", NULL); }
        $data["base_url"] = base_url();
        $data["content"] = "inventariomovimiento/inventariomovimiento";
        $this->load->view("layout", $data);
    }

    /* CARGA DE DATOS AL DATATABLE */
    public function listadoDataMovimInventario() {
        $desde = $this->session->userdata("tmp_docinv_desde");
        $hasta = $this->session->userdata("tmp_docinv_hasta");  

        $idusu = $this->session->userdata("sess_id");
        $usudat = $this->usuario_model->usua_get_tod_log($idusu);
        $mostrarvalores = ($usudat->perfil == 1);

     
        $registro = $this->Inventario_model->lst_documentoinventario($desde, $hasta);
        $tabla = "";
        foreach ($registro as $row) {
            @$fec = str_replace('-', '/', $row->fecharegistro); @$fec = date("d/m/Y", strtotime(@$fec));
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->id_documento.'\" class=\"btn btn-info btn-xs btn-grad mov_imp\"><i class=\"fa fa-print\"></i></a></div>';

            $total = ($mostrarvalores == 1) ? $row->total : 0;

            $tabla.='{"id":"' . $row->id_documento . '",
                      "fecha":"' . $fec . '",
                      "documento":"' . $row->nro_documento . '",
                      "tipo":"' . $row->categoria . '",
                      "descripcion":"' . addslashes($row->descripcion) . '",   
                      "total":"' . $total . '",                                                               
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    public function agregarmovimiento(){
        $idusu = $this->session->userdata("sess_id");
        $tmpmov = $this->Inventario_model->ini_temp($idusu);
        $idtmp = $tmpmov->id_mov;
        /* SE CREA UNA VARIABLE DE SESION PARA PROCESOS POSTERIORES A LA COMPRA */
        $this->session->unset_userdata("tmp_movinv"); 
        $this->session->set_userdata("tmp_movinv", NULL);
        if ($idtmp != NULL) { $this->session->set_userdata("tmp_movinv", $idtmp); } 
        else { $this->session->set_userdata("tmp_movinv", NULL); }
        /* ========================================================================== */
        $detmov = $this->Inventario_model->det_movimiento($idtmp);
        $unimed = $this->Unidades_model->sel_unidad();
        $almacenes = $this->Inventario_model->lst_almacen();        
        $lst_tipomov = $this->Inventario_model->lst_tipomovimiento();               
        $nromoving = $this->Inventario_model->nro_moving();
        $nromovegr = $this->Inventario_model->nro_movegr();
        $nromovtra = $this->Inventario_model->nro_movtra();

        $categingreso = $this->Contab_categoria_model->sel_categoriadocuminv(11);
        $data["categingreso"] = $categingreso;
        $tmptipo = 12;
        if ($tmpmov->id_tipodoc == 4) {$tmptipo = 11;}
        $categoria = $this->Contab_categoria_model->sel_categoriadocuminv($tmptipo);
        $data["categoria"] = $categoria;

        $usudat = $this->usuario_model->usua_get_tod_log($idusu);
        $data["mostrarvalores"] = ($usudat->perfil == 1);


        $data["nromoving"] = $nromoving;
        $data["nromovegr"] = $nromovegr;
        $data["nromovtra"] = $nromovtra;
        $data["tmpmov"] = $tmpmov;
        $data["unimed"] = $unimed;
        $data["detmov"] = $detmov;
        $data["lst_tipomov"] = $lst_tipomov;
        $data["almacenes"] = $almacenes;
        $data["base_url"] = base_url();
        $data["content"] = "inventariomovimiento/inventariomovimiento_add";
        $this->load->view("layout", $data);        

    }


    /* MOSTRAR VENTANA DE PRODUCTOS */
    public function add_producto() {
        $data["base_url"] = base_url();
        $this->load->view("inventariomovimiento/inventariomovimiento_producto", $data);        
    }


    /* CARGA DE DATO AL DATATABLE */
    public function lstProMovimiento() {
      $registro = $this->Inventario_model->lst_producto();
      $tabla = "";
      foreach ($registro as $row) {
          $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Añadir\" id=\"'.$row->pro_id.'\" class=\"btn btn-success btn-xs btn-grad addpromov\"><i class=\"fa fa-cart-plus\"></i></a> </div>';
          $tabla.='{"ver":"'.$ver.'",
                    "codbarra":"' . addslashes($row->pro_codigobarra) . '",
                    "codauxiliar":"' . addslashes($row->pro_codigoauxiliar) . '",
                    "nombre":"' . addslashes($row->pro_nombre) . '",
                    "preciocompra":"' . $row->pro_preciocompra . '",
                    "existencia":"' . $row->existencia . '",   
                    "nombrecorto":"' . addslashes($row->nombrecorto) . '"},';
                    
      }
      $tabla = substr($tabla, 0, strlen($tabla) - 1);

      echo '{"data":[' . $tabla . ']}';

    }

    /* ACTUALIZAR CATEGORIA EN LA TABLA TEMPORAL DE COMPRA*/
    public function upd_tmp_movinv(){
        $idtmp = $this->session->userdata("tmp_movinv");
        $factura = $this->input->post('factura');
        $descripcion = $this->input->post('descripcion');
        $almacen = $this->input->post('almacen');
        $tipomov = $this->input->post('tipomov');
        $categoria = $this->input->post('categoria');
        $this->Inventario_model->upd_tmp_movinv($idtmp, $factura, $descripcion, $almacen, $tipomov, $categoria);
        $arr['val'] = $idtmp;

        $tmptipo = 12;
        if ($tipomov == 4) {$tmptipo = 11;}
        $categoria = $this->Contab_categoria_model->sel_categoriadocuminv($tmptipo);        
        $arr['categoria'] = $categoria;

        $objalm  = $this->Almacen_model->sel_alm_id($almacen);        
        $contabiliza = 0; 
        if ($objalm != NULL) { $contabiliza = $objalm->contabilizacion_automatica;  }        
        $arr['contabiliza'] = $contabiliza;

        print json_encode($arr);
    } 

    public function upd_tmp_movinvdest(){
        $idtmp = $this->session->userdata("tmp_movinv");
        $almacen = $this->input->post('almacendest');
        $categoria = $this->input->post('categoria');
        $this->Inventario_model->upd_tmp_movinvdest($idtmp, $almacen, $categoria);
        $arr['val'] = $idtmp;

        $objalm  = $this->Almacen_model->sel_alm_id($almacen);        
        $contabiliza = 0; 
        if ($objalm != NULL) { $contabiliza = $objalm->contabilizacion_automatica;  }        
        $arr['contabiliza'] = $contabiliza;
        
        print json_encode($arr);
    } 

    /* INSERTA PRODUCTO EN MOVIMIENTO TEMPORAL */
     public function ins_tmpmovprod() {
        $idtmpmov = $this->session->userdata("tmp_movinv");
        $idprod = $this->input->post("id");
        $this->Inventario_model->ins_tmpmovprod($idprod, $idtmpmov);
        $arr['resu'] = $idprod;
        print json_encode($arr);
    }

    /* OBTIENE LOS DATOS DEL PRODUCTO Y LOS CARGA A LA TABLA */
    public function actualiza_tabla_producto(){
        $idtmp = $this->session->userdata("tmp_movinv");
        $detmov = $this->Inventario_model->det_movimiento($idtmp);

        $idusu = $this->session->userdata("sess_id");
        $usudat = $this->usuario_model->usua_get_tod_log($idusu);
        $data["mostrarvalores"] = ($usudat->perfil == 1);

        $data["detmov"] = $detmov;
        $data["base_url"] = base_url();
        $this->load->view("inventariomovimiento/inventariomovimiento_tabla", $data);            
    }

    /* ELIMINA EL PRODUCTO DE LA TABLA TEMPORAL */
    public function del_producto(){
        $iddet = $this->input->post("id");
        $this->Inventario_model->del_producto($iddet);
        $arr['resu'] = 1;
        print json_encode($arr);
    }    

    /* ACTUALIZA EL PRODUCTO DE LA TABLA TEMPORAL */
    public function upd_producto(){
        $idtmp = $this->session->userdata("tmp_movinv");
        $iddet = $this->input->post("id");
        $cantidad = $this->input->post("cantidad");
        $unidadmedida = $this->input->post("unidadmedida");
        $precio = $this->input->post("precio");
        $resval = $this->Inventario_model->upd_producto($idtmp, $iddet, $cantidad, $unidadmedida, $precio);
        print json_encode($resval);
    }    

    /* ELIMINA TODO PRODUCTO DE LA TABLA TEMPORAL */
    public function del_todoproducto(){
        $idtmp = $this->session->userdata("tmp_movinv");
        $resval = $this->Inventario_model->del_todoproducto($idtmp);
        print json_encode($resval);
    }    

    /* ACTUALIZA EL PRODUCTO DE LA TABLA TEMPORAL */
    public function guardar(){
        $idtmp = $this->session->userdata("tmp_movinv");
        $fec = $this->input->post("fecha");
        $fecha = str_replace('/', '-', $fec); 
        $fecha = date("Y-m-d", strtotime($fecha));
        $resval = $this->Inventario_model->guardar($idtmp, $fecha);
        $arr['newid'] = $resval;
        $objmov = $this->Inventario_model->encrecpdf($resval); 
        if ($objmov != NULL){
            if ($objmov->id_tipodoc == 8){
                $arr['docingreso'] = $objmov->id_docingreso;
            }
        }
        print json_encode($arr);
    }  

    public function ajuste(){
        $this->session->unset_userdata("tmp_alma"); 
        $idalm = 0;
        $this->session->set_userdata("tmp_alma", NULL);
        if ($idalm != NULL) { $this->session->set_userdata("tmp_alma", $idalm); } 
        else { $this->session->set_userdata("tmp_alma", NULL); }
        $almacenes = $this->Inventario_model->lst_almacen();        
        $data["almacenes"] = $almacenes;        
        $data["base_url"] = base_url();
        $data["content"] = "inventariomovimiento/inventarioajuste";
        $this->load->view("layout", $data);
    }    

    public function listadoAjuInventario() {
        $usua = $this->session->userdata('usua');
        $perfil = $usua->perfil;

        $idalm = $this->session->userdata("tmp_alma");
        $registro = $this->Inventario_model->lstproalma($idalm);
        $tabla = "";

        $parametro = &get_instance();
        $parametro->load->model("Parametros_model");
        $tarifaiva = $parametro->Parametros_model->iva_get()->valor;

        foreach ($registro as $row) {
            if ($perfil == 1){
                $ver = '<div class=\"text-center\"><input type=\"text\" class=\"text-center actualizar\" name=\"'.$row->id_alm.'\" id=\"'.$row->pro_id.'\" value=\"'.$row->existencia.'\" ></div>';
            } else {
                $ver = '<div class=\"text-center\">'.$row->existencia.'</div>';
            }                
            $tabla.='{"codbar":"' . addslashes($row->pro_codigobarra) . '",
                      "codaux":"' . addslashes($row->pro_codigoauxiliar) . '",            
                      "producto":"' . addslashes(substr($row->pro_nombre,0,50)) . '",
                      "almacen":"' . addslashes($row->almacen_nombre) . '",
                      "pcompra":"' . $row->pro_preciocompra . '",
                      "pventaneto":"' . number_format($row->pro_precioventa,2) . '", 
                      "pventa":"' . number_format($row->pro_precioventa * (1 + $tarifaiva),2) . '",   
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    public function updalmapro(){
        $idpro = $this->input->post("idpro");
        $idalm = $this->input->post("alm");
        $cant = $this->input->post("cant");
        $this->Inventario_model->updproexist($idpro, $idalm, $cant);
        print json_encode($idpro);
    }

    public function tmp_almacen() {
        $this->session->unset_userdata("tmp_alma"); 
        $idalm = $this->input->post("idalm");
        $this->session->set_userdata("tmp_alma", NULL);
        if ($idalm != NULL) { $this->session->set_userdata("tmp_alma", $idalm); } 
        else { $this->session->set_userdata("tmp_alma", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    } 

    public function reporteXLS(){
        $idalm = $this->session->userdata("tmp_alma");
        $registro = $this->Inventario_model->lstproalma($idalm);
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Reporte Existencia');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte Existencia');
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
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Cod.Barra');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Cod. Auxiliar');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Almacen');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Precio Compra');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Precio Venta');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Existencia');        

        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);

        $fila = 4;
        foreach ($registro as $reg) {

            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $reg->pro_codigobarra);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $reg->pro_codigoauxiliar);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $reg->pro_nombre);            
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $reg->almacen_nombre);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($reg->pro_preciocompra,4));
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($reg->pro_precioventa,4));
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, number_format($reg->existencia,2));

            $fila++;          
            
        }    
        $fila++;          
        
        $filename='Reporte Existencia.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');        
    } 

    public function nromovinv_tmp() {
        $this->session->unset_userdata("idmovinv_tmp"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("idmovinv_tmp", NULL);
        if ($id != NULL) { $this->session->set_userdata("idmovinv_tmp", $id); } 
        else { $this->session->set_userdata("idmovinv_tmp", NULL); }
        $id_prof = $this->session->userdata("idmovinv_tmp");
        $arr['resu'] = $id_prof;
        print json_encode($arr);
    }


    private function pagina_v() {
      $this->fpdf->SetMargins('12', '7', '10');   #Margenes
      $this->fpdf->AddPage('P', 'A4');        #Orientación y tamaño 
    }    

    public function recmovpdf(){

        $idusu = $this->session->userdata("sess_id");
        $usudat = $this->usuario_model->usua_get_tod_log($idusu);
        $mostrarvalores = ($usudat->perfil == 1);

        $idmovinv = $this->session->userdata("idmovinv_tmp");
        $encrec = $this->Inventario_model->encrecpdf($idmovinv);
        $detrec = $this->Inventario_model->detrecpdf($idmovinv);
        $sucursal = $this->Sucursal_model->sel_suc_id($encrec->sucursal_id);      
        
        $params['encrec'] = $encrec;
        
        // ENCABEZADO DEL PDF 
        $this->load->library('fpdf'/*, $params*/);
        $this->fpdf->fontpath = 'font/'; 
        $this->fpdf->AliasNbPages();
        $this->pagina_v();
        $this->fpdf->SetFillColor(139, 35, 35);
        $this->fpdf->SetFont('Arial','B',10);
        $this->fpdf->SetTextColor(0,0,0);

        if ($sucursal->logo_sucursal){    
            $file_name = "ppp.jpg";
            $pic = base64_decode($sucursal->logo_sucursal);
            imagejpeg(imagecreatefromstring ( $pic ), $file_name);

            $this->fpdf->Image($file_name,10,10,30,14);
        }  
        $this->fpdf->Line(12,25,196,25);
        $this->fpdf->SetFont('Arial','B',6);

        $this->fpdf->SetXY(100,8);
        $this->fpdf->Cell(20,10,utf8_decode($sucursal->dir_sucursal),0,0,'C');
        $this->fpdf->SetXY(100,13);
        $this->fpdf->Cell(20,10,utf8_decode($sucursal->telf_sucursal),0,0,'C');
        $this->fpdf->SetXY(100,18);
        $this->fpdf->Cell(20,10,utf8_decode($sucursal->mail_sucursal),0,0,'C');

        //$this->fpdf->Rect(165, 12, 30, 10, "D");
        $this->fpdf->SetFont('Arial','B',10);        
        $this->fpdf->text(170, 16, 'Nro. DOCUMENTO');
        $this->fpdf->SetFont('Arial','B',9);
        $this->fpdf->text(170, 21, utf8_decode('Nº '.$encrec->nro_documento));


        // TITULO DE DETALLES 
        $emisor = $encrec->usuario;
        $tipmov = $encrec->categoria;
        $idtipmov = $encrec->id_tipodoc;
        $almaorigen = $encrec->almaorigen;
        $desc = $encrec->descripcion;
        $fec = $encrec->fecha;
        $fechaf = date("d/m/Y", strtotime($fec));
        $this->fpdf->ln(20); 
        $this->fpdf->SetFont('Arial','B',10);
        $this->fpdf->Cell(100,5,utf8_decode("Entrega: $emisor"),0,0,'L');
        $this->fpdf->Cell(85,5,"Fecha: $fechaf",0,1,'R');
        $this->fpdf->Cell(92.5,5,utf8_decode("Tipo Movimiento: $tipmov"),0,0,'L');
        $this->fpdf->Cell(92.5,5,utf8_decode("Almacen Origen: $almaorigen"),0,1,'R');
        if($idtipmov == 8){
            $this->fpdf->Cell(100,5,utf8_decode("Almacen Destino: $encrec->almadestino"),0,0,'L');
            $this->fpdf->Cell(85,5,utf8_decode("Nro Documento: $encrec->docdestino"),0,1,'R');    
            $this->fpdf->Line(12,42,196,42);
        }else{
            $this->fpdf->Line(12,37,196,37);
        }
        
        $this->fpdf->ln(4); 
        $this->fpdf->SetFont('Arial','',10);
        $this->fpdf->MultiCell(185,5,utf8_decode("Descripción: ".$desc));
        $this->fpdf->ln(2); 
        $this->fpdf->SetFont('Arial','B',10);

        $this->fpdf->Cell(20,5,utf8_decode("Cod.Barra"),1,0,'C');
        $this->fpdf->Cell(75,5,utf8_decode("Producto"),1,0,'L');
        $this->fpdf->Cell(25,5,'Pre. Compra',1,0,'C');
        $this->fpdf->Cell(20,5,'Cantidad',1,0,'C');
        $this->fpdf->Cell(25,5,utf8_decode('Uni.Med'),1,0,'C');
        $this->fpdf->Cell(20,5,'Total',1,1,'R');
        /* CICLO DE DETALLES DE FACTURA */
        $total = 0;
        $this->fpdf->SetFont('Arial','',8); 
        foreach ($detrec as $row) {
            $codbar = $row->pro_codigobarra;
            $pro = substr($row->pro_nombre,0,30);
            $pcompra = number_format(($mostrarvalores == 1) ? $row->precio_compra : 0,4);
            $cant = number_format($row->cantidad,2);
            $unimed = substr($row->descripcion,0,9);
            $subtotal = number_format(($mostrarvalores == 1) ? $row->montototal : 0,2);
            $total += $subtotal; 
            $this->fpdf->Cell(20,5,utf8_decode("$codbar"),0,0,'C');
            $this->fpdf->Cell(75,5,utf8_decode("$pro"),0,0,'L');
            $this->fpdf->Cell(25,5,$pcompra,0,0,'R');
            $this->fpdf->Cell(20,5,$cant,0,0,'C');
            $this->fpdf->Cell(25,5,utf8_decode($unimed),0,0,'C');
            $this->fpdf->Cell(20,5,$subtotal,0,1,'R');
        }
        $this->fpdf->SetFont('Arial','B',8);
        $this->fpdf->Cell(185,5,number_format($total,2),0,1,'R');

   //     $this->pdf_r->Cell(100,4,utf8_decode("Monto: ".$encrec->monto),0,1,'L');
        $this->fpdf->ln(10); 

        $this->fpdf->Cell(50,0,'',1,0,'L');
        $this->fpdf->Cell(80,0,'',0,0,'L');
        $this->fpdf->Cell(50,0,'',1,1,'L'); 

        $this->fpdf->Cell(50,4,utf8_decode("Entrega: $emisor"),0,0,'L');
        $this->fpdf->Cell(80,0,'',0,0,'L');        
        $this->fpdf->Cell(50,4,utf8_decode("Recibe: "),0,1,'L'); 


        $this->fpdf->Output('Constancia de Movimiento','I'); 

    }

    private function pagina_v00() {
      $this->pdf_ri->SetMargins('12', '7', '10');   #Margenes
      $this->pdf_ri->AddPage('P', 'A4');        #Orientación y tamaño 
    }    

    public function recmovpdf00(){

        $idmovinv = $this->session->userdata("idmovinv_tmp");
        $encrec = $this->Inventario_model->encrecpdf($idmovinv);
        $detrec = $this->Inventario_model->detrecpdf($idmovinv);
        $params['encrec'] = $encrec;
        /* ENCABEZADO DEL PDF */
        $this->load->library('pdf_ri', $params);
        $this->pdf_ri->fontpath = 'font/'; 
        $this->pdf_ri->AliasNbPages();
        $this->pagina_v();
        $this->pdf_ri->SetFillColor(139, 35, 35);
        $this->pdf_ri->SetFont('Arial','B',10);
        $this->pdf_ri->SetTextColor(0,0,0);
        /* TITULO DE DETALLES */
        $emisor = $encrec->usuario;
        $tipmov = $encrec->categoria;
        $idtipmov = $encrec->id_tipodoc;
        $almaorigen = $encrec->almaorigen;
        $desc = $encrec->descripcion;
        $fec = $encrec->fecha;
        $fechaf = date("d/m/Y", strtotime($fec));
        $this->pdf_ri->ln(20); 
        $this->pdf_ri->Cell(100,5,utf8_decode("Entrega: $emisor"),0,0,'L');
        $this->pdf_ri->Cell(85,5,"Fecha: $fechaf",0,1,'R');
        $this->pdf_ri->Cell(92.5,5,utf8_decode("Tipo Movimiento: $tipmov"),0,0,'L');
        $this->pdf_ri->Cell(92.5,5,utf8_decode("Almacen Origen: $almaorigen"),0,1,'R');
        if($idtipmov == 8){
            $this->pdf_ri->Cell(100,5,utf8_decode("Almacen Destino: $encrec->almadestino"),0,0,'L');
            $this->pdf_ri->Cell(85,5,utf8_decode("Nro Documento: $encrec->docdestino"),0,1,'R');    
            $this->pdf_ri->Line(12,42,196,42);
        }else{
            $this->pdf_ri->Line(12,37,196,37);
        }
        
        $this->pdf_ri->ln(4); 
        $this->pdf_ri->SetFont('Arial','',10);
        $this->pdf_ri->MultiCell(185,5,utf8_decode("Descripción: ".$desc));
        $this->pdf_ri->ln(2); 
        $this->pdf_ri->SetFont('Arial','B',10);

        $this->pdf_ri->Cell(20,5,utf8_decode("Cod.Barra"),1,0,'C');
        $this->pdf_ri->Cell(75,5,utf8_decode("Producto"),1,0,'L');
        $this->pdf_ri->Cell(25,5,'Pre. Compra',1,0,'C');
        $this->pdf_ri->Cell(20,5,'Cantidad',1,0,'C');
        $this->pdf_ri->Cell(25,5,utf8_decode('Uni.Med'),1,0,'C');
        $this->pdf_ri->Cell(20,5,'Total',1,1,'R');
        /* CICLO DE DETALLES DE FACTURA */
        $total = 0;
        $this->pdf_ri->SetFont('Arial','',8); 
        foreach ($detrec as $row) {
            $codbar = $row->pro_codigobarra;
            $pro = substr($row->pro_nombre,0,30);
            $pcompra = number_format($row->precio_compra,4);
            $cant = number_format($row->cantidad,2);
            $unimed = substr($row->descripcion,0,9);
            $subtotal = number_format($row->montototal,2);
            $total += $subtotal; 
            $this->pdf_ri->Cell(20,5,utf8_decode("$codbar"),0,0,'C');
            $this->pdf_ri->Cell(75,5,utf8_decode("$pro"),0,0,'L');
            $this->pdf_ri->Cell(25,5,$pcompra,0,0,'R');
            $this->pdf_ri->Cell(20,5,$cant,0,0,'C');
            $this->pdf_ri->Cell(25,5,utf8_decode($unimed),0,0,'C');
            $this->pdf_ri->Cell(20,5,$subtotal,0,1,'R');
        }
            $this->pdf_ri->SetFont('Arial','B',8);
            $this->pdf_ri->Cell(185,5,number_format($total,2),0,1,'R');







   //     $this->pdf_r->Cell(100,4,utf8_decode("Monto: ".$encrec->monto),0,1,'L');
        $this->pdf_ri->ln(10); 

        $this->pdf_ri->Cell(50,0,'',1,0,'L');
        $this->pdf_ri->Cell(80,0,'',0,0,'L');
        $this->pdf_ri->Cell(50,0,'',1,1,'L'); 

        $this->pdf_ri->Cell(50,4,utf8_decode("Entrega: $emisor"),0,0,'L');
        $this->pdf_ri->Cell(80,0,'',0,0,'L');        
        $this->pdf_ri->Cell(50,4,utf8_decode("Recibe: "),0,1,'L'); 


        $this->pdf_ri->Output('Constancia de Movimiento','I'); 

    }





}

?>