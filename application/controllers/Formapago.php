<?php

/*------------------------------------------------
  ARCHIVO: Formapago.php
  DESCRIPCION: Contiene los métodos relacionados con la Formapago.
  FECHA DE CREACIÓN: 07/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Formapago extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Formapago_model");

    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "formapago";
        $this->load->view("layout", $data);
    }

    /* CARGA DE DATO AL DATATABLE */
    public function listadoDataFormapago() {

        $registro = $this->Formapago_model->sel_formapago();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->id_formapago.'\" class=\"btn btn-success btn-xs btn-grad edi_formapago\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_formapago.'\" class=\"btn btn-danger btn-xs btn-grad del_formapago\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' . $row->id_formapago . '",
                      "cod":"' . $row->cod_formapago . '",            
                      "nombre":"' . addslashes($row->nombre_formapago) . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }   

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_formapago() {
        $this->session->unset_userdata("tmp_formapago_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_formapago_id", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_formapago_id", $id); } 
        else { $this->session->set_userdata("tmp_formapago_id", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    /* ABRIR VENTANA PARA AGREGAR formapago */
    public function add_formapago(){
        $data["base_url"] = base_url();
        $this->load->view("formapago_add", $data);
    }

    /* ABRIR VENTANA PARA Imprimir precuenta */
    public function pri_formapago(){
        $factoriva = 12;
        date_default_timezone_set("America/Guayaquil");
        $idobj = $this->session->userdata("tmp_formapago_id");
        
        $idmesa = 1;//$this->input->post('txt_idobj');
        $registro = $this->Formapago_model->mesero_mesa($idmesa);
        $tabla ="\r\n" . "PRECUENTA". "\r\n";
        $strdate=date("d/m/Y H:i");
        $tabla.="Fecha:" . "\x1F \x1F" . $strdate . "\r\n";
        foreach ($registro as $row) {
            $tabla.="Mesa:" . "\x1F \x1F" . $row->nom_mesa . "\r\n";        
        }    
        $registro = $this->Formapago_model->datocliente_mesa($idmesa);
        foreach ($registro as $row) {
            $tabla.="Cliente:" . "\x1F \x1F" . $row->nom_cliente . "\r\n";        
            $tabla.="Direccion:". "\x1F \x1F" . $row->direccion_cliente . "\r\n";        
            $tabla.="CI/RUC:". "\x1F \x1F" . $row->ident_cliente . "\r\n";        
            $tabla.="Telef.:". "\x1F \x1F" . $row->telefonos_cliente . "\r\n";        
        }    
        $tabla.="CAN DESCRIPCION    P.U.   P.TOTAL". "\r\n";        
        $registro = $this->Formapago_model->pedido_mesa_detalle($idmesa);
        $subtotaliva=0;
        $subtotalcero=0;
        foreach ($registro as $row) {
            $strcant = $row->cantidad;
            $tabla.= $strcant;
            $lcant = strlen($strcant);
            while ($lcant < 4){
                $tabla.= "\x1F \x1F";
                $lcant++;
            }
            $strnombre = substr($row->pro_nombre,0,15);
            $tabla.= $strnombre;
            $lcant = strlen($strnombre);
            while ($lcant < 16){
                $tabla.= "\x1F \x1F";
                $lcant++;
            }
            if ($row->pro_grabaiva == 1){
                $subtotaliva+= $row->precio;
            }
            else{
                $subtotalcero+= $row->precio;
            }

            $strprecio=number_format($row->precio,2);
            $tabla.= $strprecio;
            $lcant = strlen($strprecio);
            while ($lcant < 8){
                $tabla.= "\x1F \x1F";
                $lcant++;
            }
            $tabla.= number_format($row->cantidad * $row->precio,2) . "\r\n";
        }
        $montoiva = $subtotaliva * $factoriva / 100;    
        $subtotal = $subtotaliva + $subtotalcero;
        $totalpagar = $subtotal + $montoiva;
        $tabla.= "\r\n";
        $tabla.= "\r\n";
        $tabla.= "   SUBTOTAL IVA12:" . "\x1F \x1F" . number_format($subtotaliva,2) . "\r\n";
        $tabla.= "   SUBTOTAL  IVA0:" . "\x1F \x1F" . number_format($subtotalcero,2) . "\r\n";
        $tabla.= "   SUBTOTAL      :" . "\x1F \x1F" . number_format($subtotal,2) . "\r\n";
        $tabla.= "   MONTO IVA12   :" . "\x1F \x1F" . number_format($montoiva,2) . "\r\n";
        $tabla.= "   TOTAL A PAGAR :" . "\x1F \x1F" . number_format($totalpagar,2) . "\r\n";
        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("formapago_pri", $data);

    }

    /* ABRIR VENTANA PARA Imprimir comanda */
    public function pri_formapago1(){
        date_default_timezone_set("America/Guayaquil");
        $idobj = $this->session->userdata("tmp_formapago_id");
        $idmesa = 1;//$this->input->post('txt_idobj');
        $registro = $this->Formapago_model->mesero_mesa($idmesa);        
        $tabla ="\r\n" . "COMANDA". "\r\n";
        $strdate=date("d/m/Y H:i");
        $tabla.="Fecha:" . "\x1F \x1F" . $strdate . "\r\n";
        foreach ($registro as $row) {
            $tabla.="Mesero:" . "\x1F \x1F" . $row->nom_mesero . "\r\n";
            $tabla.="Mesa:" . "\x1F \x1F" . $row->nom_mesa . "\r\n";        
        }    
        $tabla.="Cant. Producto". "\r\n";        
        $registro = $this->Formapago_model->pedido_mesa_detalle($idmesa);
        foreach ($registro as $row) {
            $tabla.= $row->cantidad . "\x1F \x1F" . $row->pro_nombre . "\r\n";
            if ($row->variante == 1){
                $regdetalle = $this->Formapago_model->selprovar($row->id_producto, $idmesa);
                foreach ($regdetalle as $rowdetalle) {
                    $tabla.= "\x1F \x1F" . $rowdetalle->cantidad . "\x1F \x1F" . $rowdetalle->descripcion . "\r\n";                        
                    //"&nbsp;&nbsp;"
                }
            }
        }    
        $data["strcomanda"] = $tabla; 
        
        $data["base_url"] = base_url();
        $this->load->view("formapago_pri", $data);

    }    

    /* ABRIR VENTANA PARA EDITAR formapago */
    public function edi_formapago(){
        $idobj = $this->session->userdata("tmp_formapago_id");
        $formapago = $this->Formapago_model->sel_formapago_id($idobj);
        $data["formapago"] = $formapago; 
        $data["base_url"] = base_url();
        $this->load->view("formapago_add", $data);
    }

    /* ABRIR VENTANA PARA eliminar formapago */
    public function del_formapago(){
        $idobj = $this->session->userdata("tmp_formapago_id");
        $formapago = $this->Formapago_model->sel_formapago_id($idobj);
        $data["formapago"] = $formapago; 
        $data["base_url"] = base_url();
        $this->load->view("formapago_del", $data);
    }
    
    public function imprimir() {

        $strprint = $this->input->post('txt_comanda');
        //print($strprint);         

        //$strprint = "Aquí todo el contenido del Ticket";

        $printer="EPSONT50";

        $enlace=printer_open($printer);

        printer_write($enlace, $strprint);

        printer_close($enlace);
        
    }

    /* GUARDAR O MODIFICAR DATOS DEL MESERO */
    public function guardar(){
        $idobj = $this->input->post('txt_idobj');
        $cod = $this->input->post('txt_cod');
        $nombre = trim($this->input->post('txt_nom'));
        
        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idobj != 0){
            /* SE ACTUALIZA EL REGISTRO DEL formapago */
            $resu = $this->Formapago_model->formapago_upd($idobj, $cod, $nombre);
        } else {
            /* SE GUARDA EL REGISTRO DEL formapago */
            $resu = $this->Formapago_model->formapago_add($cod, $nombre);
        }
        
        $arr['mens'] = $idobj ;
        print json_encode($arr); 
    }

    /* ELIMINAR formapago DE LA BASE DE DATOS */
    public function eliminar(){
        $idobj = $this->input->post('txt_idobj');
        $del = $this->Formapago_model->formapago_del($idobj);
        $arr['mens'] = $idobj;
        print json_encode($arr);
    }

}

?>