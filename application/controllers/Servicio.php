<?php
/*------------------------------------------------
  ARCHIVO: Servicio.php
  DESCRIPCION: Contiene los métodos relacionados con la Servicio.
  FECHA DE CREACIÓN: 13/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("usuario_model");
        $this->load->Model("Pedido_model");
        $this->load->Model("Parametros_model");
        $this->load->Model("Comanda_model");        
        $this->load->Model("Categoria_model");        
    }

    public function index() {

        $idusu = $this->session->userdata("sess_id");
        $id = $this->Pedido_model->obtmesero($idusu);

        $this->session->unset_userdata("tmp_mesero"); 
        $this->session->set_userdata("tmp_mesero", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_mesero", $id); } 
        else { $this->session->set_userdata("tmp_mesero", NULL); }   

        $idmesero = $this->session->userdata("tmp_mesero");

        $area = $this->Pedido_model->lst_area();
        $mesa = $this->Pedido_model->lst_mesa();
        $elmese = $this->Pedido_model->elmese();
        $data["elmese"] = $elmese;
        $data["area"] = $area;
        $data["mesa"] = $mesa;        
        $data["base_url"] = base_url();
        $this->load->view("servicio", $data);

    }







    public function pedido() {

        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $idusu = $this->session->userdata("sess_id");

        $idmesero = $this->session->userdata("tmp_mesero");

        $area_mesa = $this->Pedido_model->mesa_area($id_mesa);
        $mese_mesa = $this->Pedido_model->mese_mesa($id_mesa);
        $detmesa = $this->Pedido_model->pedido_mesa_detalle($id_mesa);
        $mesero = $this->Pedido_model->mesero_lst();
        $pro = $this->Pedido_model->lst_pro();
        $lstpro = $this->Pedido_model->productos();
        $cat = $this->Pedido_model->sel_cat();

        $data["pro"] = $pro;
        $data["lstpro"] = $lstpro;    
        $data["lstcat"] = $cat;       
        $data["areamesa"] = $area_mesa;
        $data["detmesa"] = $detmesa;
        $data["mese_mesa"] = $mese_mesa; 
        $data["mesero"] = $mesero;

        $data["base_url"] = base_url();
        $this->load->view("servicio_pedido", $data);

    }


    public function mostrar_img() {
        header("Content-type: image/jpeg");
        // Verificar la imagen del Usuario
        $data_t = $this->session->userdata("sess_fot");
        if ($data_t == TRUE) {
            $image = pg_unescape_bytea($data_t);
            print $image;
        } else {
            $image = imagecreatefromjpeg(base_url() . "public/img/perfil.jpeg");
            print imagejpeg($image);
            imagedestroy($image);
        }
    }
 

    /* SESION TEMPORAL PARA ID DE MESA */
     public function tmp_pedido() {
        $this->session->unset_userdata("tmp_pedido_mesa"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_pedido_mesa", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_pedido_mesa", $id); } 
        else { $this->session->set_userdata("tmp_pedido_mesa", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function del_pedido_mesa(){
        $idreg = $this->input->post("id");
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $del = $this->Pedido_model->quitar_pedido($idreg, $id_mesa);
        $arr['resu'] = 1;
        print json_encode($arr);

    }

    public function actualiza_datos(){

        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $detped = $this->Pedido_model->pedido_mesa_detalle($id_mesa);
       // $data["detped"] = $detped;
        $data["detmesa"] = $detped;
        $data["base_url"] = base_url();
        $this->load->view("pedido_servicio", $data);

    } 

     public function tmp_serproped() {
        $this->session->unset_userdata("tmp_pedido_pro"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_pedido_pro", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_pedido_pro", $id); } 
        else { $this->session->set_userdata("tmp_pedido_pro", NULL); }
        $arr['resu'] = $id;
        print json_encode($arr);
    }

    public function actualiza_pedido(){
        $idpro = $this->session->userdata("tmp_pedido_pro");
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $idmesero = $this->session->userdata("tmp_mesero");

        $reg = $this->Pedido_model->bus_mesero($id_mesa);
        if($reg != NULL){
            $identcliente = $reg->id_cliente;            
        }else{
            $identcliente = 1;
            $resu = $this->Pedido_model->regis_cliente($id_mesa, $identcliente, $idmesero);            
        }

        if($idpro != ""){
            /* Busqueda e insercion del producto en la tabla pedido detalle */
            $detped = $this->Pedido_model->pedido_cliente_detalle($idpro, $id_mesa);
            $this->session->unset_userdata("tmp_pedido_pro");
            //$data["detped"] = $detped;
            $data["detmesa"] = $detped;
            $data["base_url"] = base_url();
            $this->load->view("pedido_servicio", $data);            
        }else{
            die;
        }

    }

    /* ABRIR VENTANA PARA Imprimir precuenta */
    public function cargarprecuenta(){
        $factoriva = 12;
        date_default_timezone_set("America/Guayaquil");
        
        $idmesa = $this->input->post('id');

        $impuesto = $this->Parametros_model->sel_impuestoadicional();

        $registro = $this->Pedido_model->mesero_mesa($idmesa);
        $tabla ="\r\n" . "PRECUENTA". "\r\n";
        $strdate=date("d/m/Y H:i");
        $tabla.="Fecha:" . chr(32) . chr(32) . $strdate . "\r\n";
        foreach ($registro as $row) {
            $tabla.="Mesa:" . chr(32) . chr(32) . $row->nom_mesa . "\r\n";  
            $tabla.="Mesero:" . chr(32) . chr(32) . $row->nom_mesero . "\r\n";                  
        }    


        $habcliente = $this->Parametros_model->sel_pedidocliente();
        $habilitacliente = $habcliente->valor;
        if ($habilitacliente == 1){
            $registro = $this->Pedido_model->datocliente_mesa($idmesa);
            foreach ($registro as $row) {
                $tabla.="Cliente:" . chr(32) . chr(32) . $row->nom_cliente . "\r\n";        
                $tabla.="Direccion:". chr(32) . chr(32) . $row->direccion_cliente . "\r\n";        
                $tabla.="CI/RUC:". chr(32) . chr(32) . $row->ident_cliente . "\r\n";        
                $tabla.="Telef.:". chr(32) . chr(32) . $row->telefonos_cliente . "\r\n";        
            }   
        }    

  
        $tabla.= "\r\n"; 
        $tabla.="CAN DESCRIPCION   P.U.   P.TOTAL". "\r\n";        
        $registro = $this->Pedido_model->pedido_mesa_detallesum($idmesa);
        $subtotaliva=0;
        $subtotalcero=0;
        foreach ($registro as $row) {
            $strcant = $row->cantidad;
            $tabla.= $strcant;
            $lcant = strlen($strcant);
            while ($lcant < 4){
                $tabla.= chr(32);
                $lcant++;
            }
            $strnombre = substr($row->pro_nombre,0,15);
            $tabla.= $strnombre;
            $lcant = strlen($strnombre);
            while ($lcant < 14){
                $tabla.= chr(32);
                $lcant++;
            }
            if ($row->pro_grabaiva == 1){
                $subtotaliva+= $row->cantidad * $row->precio;
            }
            else{
                $subtotalcero+= $row->cantidad * $row->precio;
            }

            $strprecio=number_format($row->precio,2);
            $tabla.= $strprecio;
            $lcant = strlen($strprecio);
            while ($lcant < 8){
                $tabla.= chr(32);
                $lcant++;
            }
            $tabla.= number_format($row->cantidad * $row->precio,2) . "\r\n";
        }
        $montoiva = $subtotaliva * $factoriva / 100;    
        $subtotal = $subtotaliva + $subtotalcero;
        $totalpagar = $subtotal + $montoiva;
        $tabla.= "\r\n";
        $tabla.= "\r\n";
        $tabla.= "   SUBTOTAL IVA12:" . chr(32) . number_format($subtotaliva,2) . "\r\n";
        $tabla.= "   SUBTOTAL  IVA0:" . chr(32) . number_format($subtotalcero,2) . "\r\n";
        $tabla.= "   SUBTOTAL      :" . chr(32) . number_format($subtotal,2) . "\r\n";
        $tabla.= "   MONTO IVA12   :" . chr(32) . number_format($montoiva,2) . "\r\n";

        if ($impuesto->valor > 0){
            $tabla.= "   TOTAL PARCIAL :" . chr(32) . number_format($totalpagar,2) . "\r\n";
            $tabla.= "   SERVICIO 10%  :" . chr(32) . number_format($totalpagar * $impuesto->valor/100,2) . "\r\n";
            $totalpagar = round($totalpagar * (1 + $impuesto->valor/100),2);
        }
        
        $tabla.= "   TOTAL A PAGAR :" . chr(32) . number_format($totalpagar,2) . "\r\n";

        for ($i=0; $i<6; $i++) {
            $tabla.= "  " . "\n";                        
        }

        $this->imprimirprecuenta($tabla);

        // $data["strcomanda"] = $tabla; 
        // $data["idmesa"] = $idmesa; 
        
        // $data["base_url"] = base_url();
        // $this->load->view("pedido_precuenta", $data);

    }
    public function imprimirprecuenta($strprint) {

        //$strprint = $this->input->post('txt_imprimir');

        $printer="";
        $objprinter = $this->Parametros_model->impresoraprecuenta_get();
        if ($objprinter != null){
            $objcom = $this->Comanda_model->sel_com_id($objprinter->valor);

            $printer= $objcom->impresora;//"EPSONT50";

            $enlace=printer_open($printer);

            printer_write($enlace, $strprint);

            printer_close($enlace);
        }
    }	
/*
    public function imprimirprecuenta($strprint) {

        //$strprint = $this->input->post('txt_imprimir');

        $printer="";
        $objprinter = $this->Parametros_model->impresoraprecuenta_get();
        if ($objprinter != null){
            $objcom = $this->Comanda_model->sel_com_id($objprinter->valor);

            $printer= addslashes($objcom->impresora);//"EPSONT50";

            $enlace=printer_open($printer);

            printer_write($enlace, $strprint);

            printer_close($enlace);
        }
    }
*/
    /* ABRIR VENTANA PARA Imprimir comanda */
    public function cargarcomanda(){
        date_default_timezone_set("America/Guayaquil");

        $idmesa = $this->input->post('id');
        $this->imprimircomanda($idmesa);

    }    


    public function imprimircomanda($idmesa) {

        //$strprint = $this->input->post('txt_imprimir');
        //$idmesa = $this->input->post('txt_idmesa');

        $regdestino = $this->Pedido_model->destinoimpresion_comanda($idmesa);
        foreach ($regdestino as $dest) {            
            $registro = $this->Pedido_model->mesero_mesa($idmesa);        
            $tabla ="\r\n" . "COMANDA". "\r\n";
            $strdate=date("d/m/Y H:i");
            $tabla.="Fecha:" . chr(32) . chr(32) . $strdate . "\r\n";
            foreach ($registro as $row) {
                $tabla.="Mesero:" . chr(32) . chr(32) . $row->nom_mesero . "\r\n";
                $tabla.="Mesa:" . chr(32) . chr(32) . $row->nom_mesa . "\r\n";        
            }    
            $tabla.="Cant. Producto". "\r\n";        
            $registro = $this->Pedido_model->pedido_mesa_detallesum($idmesa);
            foreach ($registro as $row) {
              if ($row->estatus == 0){  
                $tmpidcomanda = 0;                
                if ($row->comanda != null)  {$tmpidcomanda = $row->comanda;}
                if ($tmpidcomanda == $dest->id_comanda){
                    $tabla.= $row->cantidad . chr(32) . chr(32) . $row->pro_nombre . chr(32) . chr(32) . $row->nota. "\r\n";
                    if ($row->variante == 1){
                        $regdetalle = $this->Pedido_model->selprovar($row->id_ped, $idmesa);
                        foreach ($regdetalle as $rowdetalle) {
                          if ($rowdetalle->cantidad > 0) {   
                            $tabla.= chr(32) . chr(32) . $rowdetalle->cantidad . chr(32) . chr(32) . $rowdetalle->descripcion . "\r\n";
                          }
                        }
                    }
                }
              }  
            }    
            for ($i=0; $i<7; $i++) {
                $tabla.= "  " . "\n";                        
            }


            $printer=$dest->impresora;

            $enlace=printer_open($printer);

            printer_write($enlace, $tabla);

            printer_close($enlace);
        }
        
    }

    /* ABRIR VENTANA PARA Cambiar Mesa */
    public function cargarcambiomesa(){
        $lst_ocupada = $this->Pedido_model->lst_mesaocupada();        
        $lst_libre = $this->Pedido_model->lst_mesalibre();        

        $data["lst_ocupada"] = $lst_ocupada; 
        $data["lst_libre"] = $lst_libre; 
        
        $data["base_url"] = base_url();
        $this->load->view("pedido_cambiomesa", $data);

    }
    

}

?>