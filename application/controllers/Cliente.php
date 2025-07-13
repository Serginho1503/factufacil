<?php

/*------------------------------------------------
  ARCHIVO: Cliente.php
  DESCRIPCION: Contiene los métodos relacionados con la Cliente.
  FECHA DE CREACIÓN: 25/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class Cliente extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("cliente_model");
        $this->load->Model("Correo_model");
        $this->load->Model("Usuario_model");        
        $this->load->Model("contabilidad/Contab_categoria_model");

        $this->request = json_decode(file_get_contents('php://input'));
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "clientes";
        $this->load->view("layout", $data);
    }

    /* CARGA DE DATO AL DATATABLE */
    public function listadoDataCli() {

        $registro = $this->cliente_model->sel_cli();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->id_cliente.'\" class=\"btn btn-success btn-xs btn-grad edi_cli\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_cliente.'\" class=\"btn btn-danger btn-xs btn-grad del_cli\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' . $row->id_cliente . '",
                      "ident":"' . addslashes($row->ident_cliente) . '",            
                      "tipo":"' . addslashes($row->desc_identificacion) . '",
                      "nombre":"' . addslashes($row->nom_cliente) . '",
                      "ciudad":"' . addslashes($row->ciudad_cliente) . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }   

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_clicorreo() {
        $this->session->unset_userdata("tmp_cli_correo"); 
        $correo = $this->input->post("correo");
        $this->session->set_userdata("tmp_cli_correo", NULL);
        if ($correo != NULL) { $this->session->set_userdata("tmp_cli_correo", $correo); } 
        else { $this->session->set_userdata("tmp_cli_correo", 0); }
        $arr['resu'] = 1;
        print json_encode($arr);
    } 


    /* CARGA DE DATO AL DATATABLE */
    public function listadoDataCliente() {
        $correo = $this->session->userdata("tmp_cli_correo");
        if ($correo == '') $correo = 0;

        $registro = $this->cliente_model->sel_clientecorreo($correo);
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->id_cliente.'\" class=\"btn btn-success btn-xs btn-grad edi_cli\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_cliente.'\" class=\"btn btn-danger btn-xs btn-grad del_cli\"><i class=\"fa fa-trash-o\"></i></a></div>';

            $ver = '<div ><input type=\"checkbox\" class=\"chk_cli\" name=\"'.$row->id_cliente.'\" id=\"'.$row->id_cliente.'\" value=\"1\" ></div>';

            $tabla.='{"id":"' . $row->id_cliente . '",
                      "ident":"' . $row->ident_cliente . '",            
                      "nombre":"' . addslashes($row->nom_cliente) . '",
                      "ciudad":"' . addslashes($row->ciudad_cliente) . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }   

    /* ABRIR VENTANA PARA AGREGAR */
    public function add_cli(){
        $pre = $this->cliente_model->precios();
        $ident = $this->cliente_model->identificacion();
        $vendedor = $this->cliente_model->vendedor();
        $idcli = 0;
        $precios = $this->cliente_model->cli_precios($idcli);
        $data["pre"] = $pre;
        $lstcatcontable = $this->Contab_categoria_model->lst_categoriacliente();
        $data["lstcatcontable"] = $lstcatcontable;
        $lstcategventa = $this->cliente_model->lst_categoria_venta();
        $data["lstcategventa"] = $lstcategventa;
        $data["ident"] = $ident;
        $data["vendedor"] = $vendedor;  
        $data["precios"] = $precios;              
        $data["base_url"] = base_url();
        $this->load->view("cli_add", $data);
    } 

    /* ABRIR VENTANA PARA Enviar Correo */
    public function cli_correo(){
        $data["base_url"] = base_url();
        $this->load->view("cli_correo", $data);
    } 


    /* SE GUARDA O SE MODIFICA EL REGISTRO DEL CLIENTE */
    public function guardar(){

        $idcli = $this->input->post('txt_idcli');
        $tip_ide = trim($this->input->post('cmb_tip_ide'));
        $nro_ide = $this->input->post('txt_nro_ident');
        $codigo = $this->input->post('txt_codigo');
        if($codigo == ''){ $codigo = ''; }
        $credito = $this->input->post('txt_clicredito');
        if($credito == ''){ $credito = 0.00; }
        $nivel = trim($this->input->post('txt_nivel'));
        if($nivel == ''){ $nivel = NULL; }
        $nom = trim($this->input->post('txt_nom'));
        $correo = trim($this->input->post('txt_mail'));
        if($correo == ''){ $correo = NULL; }
        $telf = $this->input->post('txt_telf');
        if($telf == ''){ $telf = NULL; }
        $ciu = trim($this->input->post('txt_ciu'));
        if($ciu == ''){ $ciu = NULL; }
        $placa = trim($this->input->post('txt_placa'));
        $ref = $this->input->post('txt_ref');
        if($ref == ''){ $ref = NULL; }
        $dir = trim($this->input->post('txt_dir'));
        if($dir == ''){ $dir = NULL; }
        $chk_rel = $this->input->post('chk_rel');
        if($chk_rel == 'on'){ $rel = 1; } else { $rel = 0; }
        $chk_may = $this->input->post('chk_may');
        if($chk_may == 'on'){ $may = 1; } else { $may = 0; }
        $pre = $this->input->post('cmb_precio');
        $ven = $this->input->post('cmb_vendedor');
        $catcontable = $this->input->post('cmb_catcontable');
        $categventa = $this->input->post('cmb_categventa');

        $arrpre = array(); 

        foreach($this->input->post() as $nombre_campo => $valor){
            $campo = substr($nombre_campo, 0,5); 
            if($campo == "txtpp"){
                $c = substr($nombre_campo, 5,5); 
                if($valor == ""){ $monto = 0;
                    $arrpre[$c] = $c."-".$monto; 
                }else{
                    $arrpre[$c] = $c."-".$valor;  
                }
            }
        }

        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idcli != 0){
            /* SE ACTUALIZA EL REGISTRO DEL CLIENTE */
            $resu = $this->cliente_model->cli_upd($idcli, $tip_ide, $nro_ide, $nivel, $nom, $correo, $telf, $ciu, $ref, $dir, 
                                                  $rel, $may, $pre, $ven, $codigo, $credito, $placa, $catcontable, $categventa);
            $resu = $this->cliente_model->precio_cli($idcli, $arrpre);
        } else {
            /* SE GUARDA EL REGISTRO DEL CLIENTE */
            $idcli = $this->cliente_model->cli_add($tip_ide, $nro_ide, $nivel, $nom, $correo, $telf, $ciu, $ref, $dir, $rel, 
                                                   $may, $pre, $ven, $codigo, $credito, $placa, $catcontable, $categventa);
            $resu = $this->cliente_model->precio_cli($idcli, $arrpre);
        }

        $arr['mens'] = $idcli ;
        print json_encode($arr); 
    }

    /* ABRIR VENTANA PARA EDITAR CLIENTE */
    public function edi_cli(){
        $idcli = $this->session->userdata("tmp_cli_id");
        $cli = $this->cliente_model->sel_cli_id($idcli);
        $pre = $this->cliente_model->filtroprecios($idcli);
        $ident = $this->cliente_model->identificacion();
        $precios = $this->cliente_model->cli_precios($idcli);
        $vendedor = $this->cliente_model->vendedor();
        $lstcatcontable = $this->Contab_categoria_model->lst_categoriacliente();
        $data["lstcatcontable"] = $lstcatcontable;
        $lstcategventa = $this->cliente_model->lst_categoria_venta();
        $data["lstcategventa"] = $lstcategventa;
        $data["pre"] = $pre;
        $data["cli"] = $cli;
        $data["ident"] = $ident;
        $data["precios"] = $precios;
        $data["vendedor"] = $vendedor;
        $data["base_url"] = base_url();
        $this->load->view("cli_add", $data);
    }

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_cli() {
        $this->session->unset_userdata("tmp_cli_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_cli_id", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_cli_id", $id); } 
        else { $this->session->set_userdata("tmp_cli_id", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    } 

    /* ABRIR VENTANA PARA ELIMINAR CLIENTE */
    public function del_cli(){
        $idcli = $this->session->userdata("tmp_cli_id");
        $cli = $this->cliente_model->sel_cli_id($idcli);
        $pre = $this->cliente_model->precios();
        $ident = $this->cliente_model->identificacion();
        $data["pre"] = $pre;
        $data["cli"] = $cli;
        $data["ident"] = $ident;
        $data["base_url"] = base_url();
        $this->load->view("cli_del", $data);
    }

   /* ELIMINAR CLIENTE DE LA BASE DE DATOS */
    public function eliminar(){
        $idcli = $this->input->post('txt_idcli');
        $del = $this->cliente_model->cli_del($idcli);
        $arr['mens'] = $idcli;
        print json_encode($arr);

    }

   /* Verificar Identificador CLIENTE  */
    public function existeIdentificacion(){
        $id = $this->input->post('id');
        $identificacion = $this->input->post('identificacion');
        $resu = $this->cliente_model->existeIdentificacion($id, $identificacion);
        $arr['resu'] = $resu;
        print json_encode($arr);

    }

    public function cargaprecio(){
        $pre = '';
        $idpre = json_decode($this->input->post('idpre'));
        foreach ($idpre as $p) { $pre .= $p.','; }        
        $pre = substr($pre, 0, -1);
        $resu = $this->cliente_model->selprecios($pre);
        print json_encode($resu);
    }

    function coordinates($x,$y){
     return PHPExcel_Cell::stringFromColumnIndex($x).$y;
    }

    public function reporteprecioproproductoXLS(){
        date_default_timezone_set("America/Guayaquil");
        $fecha = date("d/m/Y");
        $id = $this->input->post('id');
        $cliente = $this->cliente_model->sel_cli_id($id);
        $encab = $this->cliente_model->cli_precios($id);       
        $lstpro = $this->cliente_model->selprecioproductos($id);
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Reporte de Precios de Productos');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Precios de Productos');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);

        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('A3', 'Nombre Cliente:');
        $this->excel->getActiveSheet()->setCellValue('A4', 'C.I./R.U.C.');
        $this->excel->getActiveSheet()->setCellValue('A5', 'Fecha');        
        $this->excel->getActiveSheet()->setCellValue('B3', $cliente->nom_cliente);
        $this->excel->getActiveSheet()->setCellValue('B4', $cliente->ident_cliente); 
        $this->excel->getActiveSheet()->setCellValue('B5', $fecha); 
        $this->excel->getActiveSheet()->setCellValue('A7', 'Codigo Barra');
        $this->excel->getActiveSheet()->setCellValue('B7', 'Nombre');

        $this->excel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true);

        $arrprecios = null;
        $fila = 7;
        $col = 2;
        foreach ($encab as $pro) {
            if ($pro->estatus == 1){  
                $arrprecios[] = $pro->id_precios;
                $this->excel->getActiveSheet()->setCellValue($this->coordinates($col,$fila), $pro->desc_precios);
                $this->excel->getActiveSheet()->getStyle($this->coordinates($col,$fila))->getFont()->setBold(true);
                $col++;
            }
        }    

        $proant = -1;
        foreach ($lstpro as $pro) {
            if ($pro->pro_id != $proant){
                $fila++;          
                $proant = $pro->pro_id;
/*
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setName('Logo');
                $objDrawing->setDescription('Logo');
                $logo = FCPATH . '/public/img/extra.jpg'; // Provide path to your logo file
                $objDrawing->setPath($logo);
                $objDrawing->setWidthAndHeight(80,40);
                $objDrawing->setResizeProportional(true);
                //$objDrawing->setWidth(40);
                $objDrawing->setCoordinates($this->coordinates(0, $fila));
                //$objDrawing->setHeight(75); 
                $objDrawing->setWorksheet($this->excel->getActiveSheet()); 
                $this->excel->getActiveSheet()->getRowDimension($fila)->setRowHeight(40);
*/

                $this->excel->getActiveSheet()->setCellValue('A'.$fila, $pro->pro_codigobarra);
                $this->excel->getActiveSheet()->setCellValue('B'.$fila, $pro->pro_nombre);
                $col = 2;
                if ($pro->habilitado_precioventa == 1){
                    
                    $this->excel->getActiveSheet()->setCellValue('C'.$fila, number_format($pro->pro_precioventa,2));
                }
            }
            
            $tmpcol = array_search($pro->id_precios,$arrprecios); 
            $tmpcol+= $col;
            $this->excel->getActiveSheet()->setCellValue($this->coordinates($tmpcol,$fila), number_format($pro->monto,2));
            
        }    
        $fila++;          

        $ndir = FCPATH.'/doc/productos_'.$cliente->ident_cliente.'.xlsx';
          
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();

        $objWriter->save($ndir);

        $json['cliente'] = $cliente->nom_cliente;
        $json['correo'] = $cliente->correo_cliente;
        $json['ruta'] = $ndir;
        print json_encode($json); 
    }

    public function correoenviar() {
      $correo = $this->Correo_model->env_correo();
      $ruta = $this->input->post('ruta');
      $mailcli = $this->input->post('correo');

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
         $this->email->subject('Envío de Precios');
         //$this->email->attach(FCPATH.'/doc/excel.xlsx');
         $this->email->attach($ruta);
         $this->email->message('Envío de Precios');
         $this->email->to($mailcli);
         if($this->email->send(FALSE)){
            $res = 1;
         }else {
            $res = 0;
         }

         print json_encode($res);
    }  

    public function lst_categoria_venta(){
       $registros = $this->cliente_model->lst_categoria_venta();
       echo json_encode($registros);       
    }

    public function lst_categoriaventa_precios($categoria){
        $registros = $this->cliente_model->lst_categoriaventa_precios($categoria);
        echo json_encode($registros);       
     }

     public function categoriaventa_guardarimagen(){
        $image = NULL;
        $tmppath = "";
        if ($_FILES['iconoBase64']){
            $image = $_FILES['iconoBase64'];
        }
        if ($image != NULL){

            $logo_name= $_FILES["iconoBase64"]["name"];
            $logo_size= $_FILES["iconoBase64"]["size"];
            $logo_temporal= $_FILES["iconoBase64"]["tmp_name"];     

            $split_logo = pathinfo($logo_name);
            $split_temporal = pathinfo($logo_temporal);
            $tmppath = $split_temporal['filename'].".".$split_logo['extension'];

            $logo_temporal= $_FILES["iconoBase64"]["tmp_name"];     

            $f1= fopen($logo_temporal,"rb");
            # Leemos el fichero completo limitando la lectura al tamaño del fichero
            $logo_reconvertida = fread($f1, $logo_size);
            fclose($f1);

            $tmpfile = FCPATH.'/public/img/categoriaventa/' . $tmppath;           

            $file = fopen($tmpfile , 'w') or die("X_x");
            fwrite($file, $logo_reconvertida);
            fclose($file);

        }
        echo json_encode($tmppath);       
     }
  
     public function categoriaventa_guardar(){
        $categoria = $this->request->categoria;
        $listaprecios = $this->request->listaprecios;
        $id = $categoria->id;
        if ($id == 0){
            $id = $this->cliente_model->ins_categoriaventa($categoria, $listaprecios);
        }
        else{
            $this->cliente_model->upd_categoriaventa($categoria, $listaprecios);           
        }
        echo json_encode($id);        
     }

     public function categoriaventa_eliminar(){
        $id = $this->request;
        $res = $this->cliente_model->del_categoriaventa($id);
        echo json_encode($res);        
     }


}

?>