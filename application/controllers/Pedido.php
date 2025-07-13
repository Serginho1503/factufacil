<?php

/*------------------------------------------------
  ARCHIVO: Pedido.php
  DESCRIPCION: Contiene los métodos relacionados con la Pedido.
  FECHA DE CREACIÓN: 07/08/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Pedido extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Pedido_model");
        $this->load->Model("Parametros_model");
        $this->load->Model("Comanda_model");
        $this->load->Model("Facturar_model");
        $this->load->Model("Cliente_model");
        $this->load->Model("Sistema_model");

    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $area = $this->Pedido_model->lst_area();
        $mesa = $this->Pedido_model->lst_mesa();
        $elmese = $this->Pedido_model->elmese();
        $clientes = $this->Cliente_model->sel_cli();
        $data["area"] = $area;
        $data["mesa"] = $mesa;
        $data["elmese"] = $elmese;
        $data["clientes"] = $clientes;
        $data["base_url"] = base_url();
        $data["content"] = "distribucion";
        $this->load->view("layout", $data);
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

/* ==== SECCION RELATIVA AL PEDIDO =====================================================*/    

    /* CARGAR PEDIDO A LA MESA */
    public function pedido_mesa() {
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $idusu = $this->session->userdata("sess_id");
        if($id_mesa == NULL){ redirect('pedido','refresh'); }
        $cajero = $this->Pedido_model->cajero_usuario($idusu);
        if ($cajero){ $data["usuariocajero"] = $cajero[0]->id_mesero; }

        $area_mesa = $this->Pedido_model->mesa_area($id_mesa);
        $mese_mesa = $this->Pedido_model->mese_mesa($id_mesa);
        $detmesa = $this->Pedido_model->pedido_mesa_detalle($id_mesa);
        $mesero = $this->Pedido_model->mesero_lst();

        $tipident = $this->Facturar_model->tipo_identificacion();
        $cliente = $this->Pedido_model->carga_cliente($id_mesa);

        $data["tipident"] = $tipident;
        $data["cliente"] = $cliente;
        $data["areamesa"] = $area_mesa;
        $data["detmesa"] = $detmesa;
        $data["mese_mesa"] = $mese_mesa; 
        $data["mesero"] = $mesero;
        $data["base_url"] = base_url();
        $data["content"] = "pedido";
        $this->load->view("layout", $data);        
    }





    /* CARGA DE DATO AL DATATABLE 
    public function listadoProducto() {
        $registro = $this->Pedido_model->lst_pro();
        $tabla = "";
        foreach ($registro as $row) {
            @$fec = str_replace('-', '/', $row->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); 
            $ver = '<div name=\"carga\" id=\"'.$row->pro_id.'\" class=\"text-center agrega\"> <a href=\"#\" title=\"Añadir\" id=\"'.$row->pro_id.'\" class=\"btn btn-success btn-xs btn-grad addpro\"><i class=\"fa fa-cart-plus\"></i></a> </div>';
            $tabla.='{"codbarra":"' . $row->pro_codigobarra . '",
                      "nombre":"' . $row->pro_nombre . '",
                      "precioventa":"' . $row->pro_precioventa . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';

    }*/

    /* MOSTRAR VENTANA DE PRODUCTOS */
    public function add_producto() {
        $lstcat = $this->Pedido_model->sel_cat();
        $pro = $this->Pedido_model->lst_pro();
        $lstpro = $this->Pedido_model->productos();
        $data["base_url"] = base_url();
        $data["lstcat"] = $lstcat;
        $data["pro"] = $pro;
        $data["lstpro"] = $lstpro;
        $this->load->view("pedido_producto", $data);        
    }    

    /* VALIDA CLIENTE */
    public function valcliente(){
        $idcliente = $this->input->post('idcliente');
        $resu = $this->Pedido_model->valida_cliente($idcliente);
        if($resu == 1){ $mens = 1; }
        else { $mens = $resu[0];}
        $arr['mens'] = $mens;
        print json_encode($arr);
    }

    /* FUNCIONES ESPECIALES DE BUSQUEDA */
    public function valclientenombre(){
        $tmpArray=array();
        $nomcli = $this->input->get('nombre');
        $data = $this->Pedido_model->valida_nombre($nomcli);
        foreach ($data as $row) {
            $tmpArray[] = $row->nombre;
        }
        print json_encode($tmpArray);
    }

    public function busca_nombre(){
        $nom = $this->input->post('nom');
        $resu = $this->Pedido_model->busca_cliente($nom);
        if($resu == 1){ $mens = 1; }
        else { $mens = $resu[0];}
        $arr['mens'] = $mens;
        print json_encode($arr);
    }

    /* ================================ */
    /* SESION TEMPORAL PARA CARGAR PEDIDO PRODUCTO */
     public function tmp_proped() {
        $this->session->unset_userdata("tmp_pedido_pro"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_pedido_pro", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_pedido_pro", $id); } 
        else { $this->session->set_userdata("tmp_pedido_pro", NULL); }

        $this->session->unset_userdata("tmp_pedido_alm"); 
        $idalm = $this->input->post("idalm");
        $this->session->set_userdata("tmp_pedido_alm", NULL);
        if ($idalm != NULL) { $this->session->set_userdata("tmp_pedido_alm", $idalm); } 
        else { $this->session->set_userdata("tmp_pedido_alm", NULL); }

        $arr['resu'] = $id;
        print json_encode($arr);
    }

    public function inserta_pedido(){
        $idpro = $this->session->userdata("tmp_pedido_pro");
        $idalm = $this->session->userdata("tmp_pedido_alm");
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");

        $reg = $this->Pedido_model->bus_mesero($id_mesa);
        if($reg != NULL){
            $identcliente = $reg->id_cliente;            
        }else{
            $identcliente = 1;
            $resu = $this->Pedido_model->reg_cliente($id_mesa, $identcliente);            
        }

        if($idpro != ""){
            /* Busqueda e insercion del producto en la tabla pedido detalle */
            $detped = $this->Pedido_model->pedido_cliente_detalle($idpro, $idalm, $id_mesa);
        }
        $arr['resu'] = 1;
        print json_encode($arr);

    }    

    public function actualiza_pedido(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");

        $detped = $this->Pedido_model->lista_pedido_cliente_detalle($id_mesa);
        $data["detmesa"] = $detped;

        $pedidopromo = $this->Parametros_model->sel_pedidopromo();
        $data["pedidopromo"] = $pedidopromo;

        $sistema = $this->Sistema_model->sel_sistema();
        $iconopedido = $sistema->icon_pedido;
        $data["iconopedido"] = $iconopedido;

        $data["base_url"] = base_url();
        $this->load->view("pedido_tabla", $data);            
    }

    public function actualiza_pedido00(){
        $idpro = $this->session->userdata("tmp_pedido_pro");
        $idalm = $this->session->userdata("tmp_pedido_alm");
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");

        $reg = $this->Pedido_model->bus_mesero($id_mesa);
        if($reg != NULL){
            $identcliente = $reg->id_cliente;            
        }else{
            $identcliente = 1;
            $resu = $this->Pedido_model->reg_cliente($id_mesa, $identcliente);            
        }

        if($idpro != ""){
            /* Busqueda e insercion del producto en la tabla pedido detalle */
            $detped = $this->Pedido_model->pedido_cliente_detalle($idpro, $idalm, $id_mesa);
            $this->session->unset_userdata("tmp_pedido_pro");
            $this->session->unset_userdata("tmp_pedido_alm");
            //$data["detped"] = $detped;
            $data["detmesa"] = $detped;

            $pedidopromo = $this->Parametros_model->sel_pedidopromo();
            $data["pedidopromo"] = $pedidopromo;

            $sistema = $this->Sistema_model->sel_sistema();
            $iconopedido = $sistema->icon_pedido;
            $data["iconopedido"] = $iconopedido;

            $data["base_url"] = base_url();
            $this->load->view("pedido_tabla", $data);            
        }else{
            die;
        }

    }

    public function actualiza_tabla_pedido(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        if($id_mesa != ""){
            $detped = $this->Pedido_model->pedido_mesa_detalle($id_mesa);
            $data["detmesa"] = $detped;

            $pedidopromo = $this->Parametros_model->sel_pedidopromo();
            $data["pedidopromo"] = $pedidopromo;

            $sistema = $this->Sistema_model->sel_sistema();
            $iconopedido = $sistema->icon_pedido;
            $data["iconopedido"] = $iconopedido;

            $data["base_url"] = base_url();
            $this->load->view("pedido_tabla", $data);            
        }else{
            die;
        }

    }


    public function actualiza_datos(){

        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $detped = $this->Pedido_model->pedido_mesa_detalle($id_mesa);
       // $data["detped"] = $detped;
        $data["detmesa"] = $detped;

        $pedidopromo = $this->Parametros_model->sel_pedidopromo();
        $data["pedidopromo"] = $pedidopromo;
        
        $sistema = $this->Sistema_model->sel_sistema();
        $iconopedido = $sistema->icon_pedido;
        $data["iconopedido"] = $iconopedido;
        
        $data["base_url"] = base_url();
        $this->load->view("pedido_tabla", $data);

    } 
    /* ELIMINA REGISTROS DEL PEDIDO */
    public function del_pedido_mesa(){
        $idreg = $this->input->post("id");
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $del = $this->Pedido_model->quitar_pedido($idreg, $id_mesa);
        $arr['resu'] = 1;
        print json_encode($arr);

    }

    /* ACTUALIZA LA CANTIDAD DE PEDIDO EN LA BASE DE DATOS */
    public function upd_precio(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $idpro = $this->input->post("id");
        $cant = $this->input->post("cant");
        $idreg = $this->input->post("idreg");
        $precio = $this->input->post("precio");
        $promo = $this->input->post("promo");
        if($cant > 0){
           $upd = $this->Pedido_model->upd_precio($idreg, $idpro, $id_mesa, $cant, $precio, $promo);
        }
        $arr['resu'] = $upd;
        print json_encode($arr);
    }

    /* ACTUALIZA EL MONTO DEL PEDIDO EN LA BASE DE DATOS */
    public function upd_monto(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $monto = $this->Pedido_model->upd_monto($id_mesa);
        //print_r($monto); die;
        $arr['resu'] = $monto;
        print json_encode($arr);
    }

    /* GUARDAR DATOS DEL CLIENTE EN TABLA PEDIDO */
    public function reg_cliente(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        //$identcliente = $this->input->post('idcli');
        $identcliente = 0;
        $idmesero = $this->input->post('idmesero');
        if($idmesero == ""){ $idmesero = 0;}
        $resu = $this->Pedido_model->reg_cliente($id_mesa, $identcliente, $idmesero);
        $arr['resu'] = $resu;
        print json_encode($arr);
    }

    /* ELIMINA EL CLIENTE DE LA MESA */
    public function elim_cliente(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");  
        $delcli = $this->Pedido_model->delmesacli($id_mesa);
        $arr['resu'] = $id_mesa;
        print json_encode($arr);
    }

    /* ELIMINA LOS PRODUCTOS DE LA MESA */
    public function elim_producto(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");  
        $delpro = $this->Pedido_model->delproped($id_mesa);
        $arr['resu'] = $id_mesa;
        print json_encode($arr);
    }

    /* ACTUALIZAR MESERO EN LA TABLA PEDIDO DETALLE */
    public function upd_mesero(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $idmesero = $this->input->post('idmesero');

        /* Si el id_mesero = 0 es que no se a seleccionado */

        $reg = $this->Pedido_model->bus_mesero($id_mesa);
        if($reg != NULL){
            $identcliente = $reg->id_cliente;            
        }else{
            $identcliente = 0;
            $resu = $this->Pedido_model->reg_cliente($id_mesa, $identcliente);            
        }
        
        $mesero = $this->Pedido_model->upd_mesero($id_mesa, $idmesero);
        $arr['resu'] = $mesero;
        print json_encode($arr);
    }    

    /* ACTUALIZAR Observciones EN LA TABLA PEDIDO DETALLE */
    public function upd_observaciones(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $obs = $this->input->post('obs');
       
        $this->Pedido_model->upd_observaciones($id_mesa, $obs);
        $arr['resu'] = 1;
        print json_encode($arr);
    }    

    /* VARIABLE DE SESION PARA ACTIVAR LAS VARIANTES DE LOS PRODUCTOS */
    public function tmp_pedprovar() {
        $this->session->unset_userdata("tmp_pedido_var"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_pedido_var", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_pedido_var", $id); } 
        else { $this->session->set_userdata("tmp_pedido_var", NULL); }
        $arr['resu'] = $id;
        print json_encode($arr);
    }

    /* MOSTRAR LA VENTA DE LAS VARIANTES DEL PRODUCTO */
    public function ver_pedprovar(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $idreg = $this->session->userdata("tmp_pedido_var");
        $provar = $this->Pedido_model->selprovar($idreg, $id_mesa);
        $maxitemvariante = 0;
        if ($provar != null) {$maxitemvariante = $provar[0]->maxitemvariante;}
        
        $data["provar"] = $provar;
        $data["maxitemvariante"] = $maxitemvariante;
        $data["base_url"] = base_url();
        $this->load->view("pedido_producto_variante", $data);
    }

    /* ACTUALIZAR LA CANTIDAD DEL PRODUCTO VARIANTE */
    public function upd_cant_variante(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $idreg = $this->session->userdata("tmp_pedido_var");
        $id_pro = $this->input->post("idpro");
        $cant = $this->input->post("cant");
        $desc = $this->input->post("desc");
        $sql = $this->Pedido_model->updvar_cantidad($idreg, $id_pro, $id_mesa, $desc, $cant);
        $arr['resu'] = $id_pro;
        print json_encode($arr);

    }

    /* AÑADIR NOTA AL PEDIDO POR CADA PRODUCTO */
    public function nota_pedido(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $id_pro = $this->input->post("idpro");
        $idped = $this->input->post("idped");
        $nota = $this->Pedido_model->busca_nota($id_pro, $id_mesa, $idped);
        $data["pro"] = $id_pro;
        $data["nota"] = $nota;
        $data["idped"] = $idped;
        $data["base_url"] = base_url();
        $this->load->view("pedido_nota", $data);
    }

    /* GURDAR NOTA */
    public function guardar_nota(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $idped = $this->input->post("txt_idped");
        $id_pro = $this->input->post("txt_idpro");
        $nota_pro = $this->input->post("txt_nota");
        $sql = $this->Pedido_model->updpro_nota($id_pro, $id_mesa, $nota_pro, $idped);
        $arr['val'] = $id_pro;
        print json_encode($arr);
    }

    /* ACTUALIZA EL PEDIDO SIN FUE ENTREGADO */
    public function upd_est(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $id_pro = $this->input->post("idpro");
        $idped = $this->input->post("idped");
        $est = $this->input->post("est");
        $estado = $this->Pedido_model->upd_est($id_pro, $idped, $id_mesa, $est);

    }

    /* ABRIR VENTANA PARA Imprimir comanda */
    public function cargarcomanda(){
        date_default_timezone_set("America/Guayaquil");
        $idmesa = $this->input->post('id');
        $this->Pedido_model->estatus_comanda($idmesa);
        $this->imprimircomanda($idmesa);
        print json_encode($idmesa);
    }

    /* ABRIR VENTANA PARA Imprimir precuenta */
    public function cargarprecuenta(){
        $factoriva = 12;
        date_default_timezone_set("America/Guayaquil");
        
        $idmesa = $this->input->post('id');

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
        $tabla.="CAN DESCRIPCION           P.U.   P.TOTAL". "\r\n";        
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
            $strnombre = substr($row->pro_nombre,0,23);
            $tabla.= $strnombre;
            $lcant = strlen($strnombre);
            while ($lcant < 22){
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


        $impuesto = $this->Parametros_model->sel_impuestoadicional();
        $impuestoespecial = $impuesto->valor;
        $descripcionimpuestoespecial = $impuesto->descripcion;

        /*$habilitaimpuestoespecial = true;*/
        if ($impuestoespecial > 0){
            $montoimpuestoadicional = round($impuestoespecial * ($subtotaliva + $subtotalcero) / 100, 2);
        /*  $tabla.= "   TOTAL PARCIAL  :" . chr(32) . chr(32) . number_format($totalpagar,2) . "\r\n";*/
            $tabla.= "   " . $descripcionimpuestoespecial . "  :" . chr(32) . chr(32) . number_format($montoimpuestoadicional,2) . "\r\n";
          $totalpagar+= $montoimpuestoadicional;
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
/*
    public function comandapedido(){
        $idfactura = $this->input->post("id");
        $imprimecomandafactura = $this->Parametros_model->sel_comandafactura();
        if ($imprimecomandafactura == 1){
          $this->comandafactura($idfactura);
        }      
    }    
*/
    public function imprimircomanda() {
        date_default_timezone_set("America/Guayaquil");
        $idmesa = $this->input->post('id');
        $this->Pedido_model->estatus_comanda($idmesa);
        $regdestino = $this->Pedido_model->destinoimpresion_comanda($idmesa);
        foreach ($regdestino as $dest) {            
            $registro = $this->Pedido_model->mesero_mesa($idmesa);        
            $tabla ="\r\n" . "COMANDA". "\r\n";
            $strdate=date("d/m/Y H:i");
            $tabla.="Fecha:" . chr(32) . chr(32) . $strdate . "\r\n";
            foreach ($registro as $row) {
                $tabla.="Nro Orden:" . chr(32) . chr(32) . $row->nro_orden . "\r\n";
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

            $impresiongrafica = $this->Parametros_model->sel_impresiongrafica();
            if ($impresiongrafica == 0){
           
                $printer=$dest->impresora;

                $enlace=printer_open($printer);

                printer_write($enlace, $tabla);

                printer_close($enlace);
            }    
            else{
                $printer=$dest->impresora;
                $handle = printer_open($printer);
                printer_start_doc($handle, "My Document");
                printer_start_page($handle);

                $font = printer_create_font("Arial", 30, 12, PRINTER_FW_NORMAL, false, false, false, 0);
                printer_select_font($handle, $font);
                $p= 50;


                $lines = explode(PHP_EOL, $tabla);

                foreach ($lines as $ln => $value) {
                  printer_draw_text($handle, $value. "\r\n", 1, $p);
                  $p+= 26;
                }

                $pen = printer_create_pen(PRINTER_PEN_SOLID, 1, "000000");
                printer_select_pen($handle, $pen);
                printer_draw_line($handle, 1, $p, 500, $p);
                printer_delete_pen($pen);            
                printer_delete_font($font);
                printer_end_page($handle);
                printer_end_doc($handle);
                printer_close($handle);            
            }
            
        }

        print json_encode($idmesa);
        
    }

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




    /* ABRIR VENTANA PARA Cambiar Mesa */
    public function cargarcambiomesa(){
        $lst_ocupada = $this->Pedido_model->lst_mesaocupada();        
        $lst_libre = $this->Pedido_model->lst_mesalibre();        

        $data["lst_ocupada"] = $lst_ocupada; 
        $data["lst_libre"] = $lst_libre; 
        
        $data["base_url"] = base_url();
        $this->load->view("pedido_cambiomesa", $data);

    }

    /* ABRIR VENTANA PARA Cambiar Mesa */
    public function cambiarmesa(){
        $idmesaocupada = $this->input->post('mesa_ocupada');
        $idmesalibre = $this->input->post('mesa_libre');

        $this->Pedido_model->cambiarmesa($idmesaocupada, $idmesalibre);        

        $arr['resu'] = 1;
        print json_encode($arr);
    }

    /* Cambiar estado de mesa */
    public function cambiar_estadomesa(){
        $mesa = $this->input->post('mesa');
        $estado = $this->input->post('estado');

        $res = $this->Pedido_model->cambiar_estadomesa($mesa, $estado);        

        $arr['resu'] = $res;
        print json_encode($arr);
    }

    public function upd_valor(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $detmesa = $this->Pedido_model->pedido_mesa_detalle($id_mesa);
        $subtotaliva = 0;
        $subtotalcero = 0;
        $iva = 0.12;

        foreach ($detmesa as $dm):

            if ($dm->pro_grabaiva == 1){
                $subtotaliva += $dm->cantidad * $dm->precio;
            } else {
                $subtotalcero += $dm->cantidad * $dm->precio;
            }

        endforeach;

        $mesa = $this->Pedido_model->mese_mesa($id_mesa);
        $arr['orden'] = $mesa->nro_orden;

        $miva = $subtotaliva * $iva;
        $total = $subtotaliva + $subtotalcero + $miva; 
    
        $arr['subtotaliva'] = $subtotaliva;
        $arr['subtotalcero'] = $subtotalcero;
        $arr['miva'] = $miva;
        $arr['total'] = $total;

        print json_encode($arr);            

    }
    

    /* MOSTRAR VENTANA EMERGENTE PARA OBSERVACIONES AL LIMPIAR LA MESA */
    public function obs_mesalimpia(){
        $id_mesa = $this->session->userdata("tmp_pedido_mesa");
        $data["base_url"] = base_url();
        $data["idmesa"] = $id_mesa;
        $this->load->view("pedido_mesalimpia", $data);    

    }

    /* GUARDAR LAS OBSERVACIONES DE LA MESA */
    public function mesalimpia(){
       $this->session->unset_userdata("tmp_pedido_mesa");
       $id_mesa = $this->input->post("txt_idmesa");
       $obs = $this->input->post("txt_obs");  
       $add = $this->Pedido_model->limpia_mesa($id_mesa, $obs);
       $arr = "X";
       print json_encode($arr); 
    }

            
    /* GUARDAR DATOS DEL CLIENTE EN TABLA VENTA_TMP */
    public function upd_pedidocliente(){
      $id_mesa = $this->session->userdata("tmp_pedido_mesa");
      $nro_ident = $this->input->post("idcli");
      $tipo_ident = $this->input->post("idtp");
      $nom_cliente = $this->input->post("nom");
      $telf_cliente = $this->input->post("tel");
      $cor_cliente = $this->input->post("cor");
      $dir_cliente = $this->input->post("dir");
      $ciu_cliente = $this->input->post("ciu");

      $resu = $this->Pedido_model->data_cliente($nro_ident, $tipo_ident, $nom_cliente, $cor_cliente, $telf_cliente, $dir_cliente, $ciu_cliente);
      $resu = $this->Pedido_model->upd_cliente($id_mesa, $resu->id_cliente);
      $arr['resu'] = $resu;
      print json_encode($arr);
    }


}

?>