<?php

/*------------------------------------------------
  ARCHIVO: Producto.php
  DESCRIPCION: Contiene los métodos relacionados con la Producto.
  FECHA DE CREACIÓN: 14/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Producto extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("producto_model");
        $this->load->Model("almacen_model");
        $this->load->Model("categoria_model");
        $this->load->Model("unidades_model");
        $this->load->Model("Inventario_model");
        $this->load->Model("Retencion_model");        
        $this->load->Model("Parametros_model");
        $this->load->Model("contabilidad/Contab_categoria_model");

        $this->request = json_decode(file_get_contents('php://input'));
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $registro = $this->producto_model->lista_pro();
        $data["base_url"] = base_url();
        $data["content"] = "producto";
        $data["pro"] = $registro;
        $this->load->view("layout", $data);
    }
    /* CARGA DE DATO AL DATATABLE 
    public function listadoDataPro() {

        $registro = $this->producto_model->lista_pro();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->pro_id.'\" class=\"btn btn-success btn-xs btn-grad pro_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->pro_id.'\" class=\"btn btn-danger btn-xs btn-grad pro_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' . $row->pro_id . '",
                      "codbarra":"' . addslashes($row->pro_codigobarra) . '",
                      "codauxiliar":"' . addslashes($row->pro_codigoauxiliar) . '",
                      "nombre":"' . addslashes($row->pro_nombre) . '",
                      "existencia":"' . $row->pro_existencia . '",   
                      "preciocompra":"' . $row->pro_preciocompra . '",                                                               
                      "precioventa":"' . $row->pro_precioventa . '",   
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';

    }   
*/
    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_pro() {
        $this->session->unset_userdata("tmp_pro_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_pro_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_pro_id", $id);
        } else {
            $this->session->set_userdata("tmp_pro_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }    

    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_provar() {
        $this->session->unset_userdata("tmp_provar"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_provar", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_provar", $id);
        } else {
            $this->session->set_userdata("tmp_provar", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }      
   
    /* ABRIR VENTANA PARA AGREGAR */
    public function agregar(){
        $this->session->unset_userdata("tmp_pro_id"); 
        $this->session->set_userdata("tmp_pro_id", 0);

        $this->session->unset_userdata("arr_var");
        $cat = $this->categoria_model->sel_cat();
        $uni = $this->unidades_model->sel_unidad();
        $ded = $this->producto_model->deducible();
        $pre = $this->producto_model->precio();
        $procomp = $this->producto_model->procomp();
        $comanda = $this->producto_model->comanda();
        $cla = $this->producto_model->clasificacion();
        $tp = $this->producto_model->tipo_precio();
        $iva = $this->producto_model->get_iva();
        $retenciones = $this->Retencion_model->sel_ret();
        $idpro = 0;
        $almacen = $this->producto_model->selexistencia($idpro);
        $data["cat"] = $cat;
        $data["uni"] = $uni;
        $data["ded"] = $ded;
        $data["pre"] = $pre; 
        $data["com"] = $comanda; 
        $data["procomp"] = $procomp;
        $data["cla"] = $cla;   
        $data["tp"] = $tp;  
        $data["iva"] = $iva;    
        $data["retenciones"] = $retenciones;    
        $data["almacen"] = $almacen;                        
        $data["decimalesprecio"] = $this->Parametros_model->sel_decimalesprecio() * 1;    

        $lstcatcontable = $this->Contab_categoria_model->lst_categoriaproducto();
        $data["lstcatcontable"] = $lstcatcontable;

        $data["base_url"] = base_url();
        $data["content"] = "pro_add";
        $this->load->view("layout", $data);        
    }

   /* ABRIR VENTANA PARA MODIFICAR */
    public function pro_edit(){
        $idpro = $this->session->userdata("tmp_pro_id");
        $this->session->unset_userdata("arr_var");
        $pro = $this->producto_model->sel_pro_id($idpro);
        $tp = $this->producto_model->tipo_precio();
        $cat = $this->categoria_model->sel_cat();
        $uni = $this->unidades_model->sel_unidad();
        $ded = $this->producto_model->deducible();
        $pre = $this->producto_model->precio();
        $propre = $this->producto_model->sel_pre($idpro); 
        $procomp = $this->producto_model->procomp();
        $provar = $this->producto_model->provar($idpro);
        $comanda = $this->producto_model->comanda();
        $cla = $this->producto_model->clasificacion();
        $retenciones = $this->Retencion_model->sel_ret();
        $almacen = $this->producto_model->selexistencia($idpro);
        $data["cla"] = $cla;         
        $iva = $this->producto_model->get_iva();
        $data["iva"] = $iva;  
        $data["almacen"] = $almacen;                                
        $this->session->unset_userdata("arr_var");
        $this->session->set_userdata("arr_var", NULL);
        $arravar = array();
        foreach ($provar as $ar=>$desc) {
            array_push($arravar, $desc->descripcion);    
        }
        $this->session->set_userdata("arr_var", $arravar);
        $data["com"] = $comanda; 
        $data["pro"] = $pro;       
        $data["cat"] = $cat;
        $data["uni"] = $uni;
        $data["ded"] = $ded;
        $data["pre"] = $pre;         
        $data["propre"] = $propre;
        $data["procomp"] = $procomp;
        $data["provar"] = $provar;
        $data["tp"] = $tp;
        $data["retenciones"] = $retenciones;                    
        $data["decimalesprecio"] = $this->Parametros_model->sel_decimalesprecio() * 1;    

        $lstcatcontable = $this->Contab_categoria_model->lst_categoriaproducto();
        $data["lstcatcontable"] = $lstcatcontable;

        $data["base_url"] = base_url();
        $data["content"] = "pro_add";
        $this->load->view("layout", $data);        
    }

    /* ABRIR VENTANA PARA IMPRIMIR CODIGO BARRAS */
    public function pro_imp_cod(){
        $idpro = $this->session->userdata("tmp_pro_id");
        $this->session->unset_userdata("arr_var");
        $pro = $this->producto_model->sel_pro_id($idpro);
        $tp = $this->producto_model->tipo_precio();
        $cat = $this->categoria_model->sel_cat();
        $uni = $this->unidades_model->sel_unidad();
        $ded = $this->producto_model->deducible();
        $pre = $this->producto_model->precio();
        $propre = $this->producto_model->sel_pre($idpro); 
        $procomp = $this->producto_model->procomp();
        $provar = $this->producto_model->provar($idpro);
        $comanda = $this->producto_model->comanda();
        $cla = $this->producto_model->clasificacion();
        $retenciones = $this->Retencion_model->sel_ret();
        $almacen = $this->producto_model->selexistencia($idpro);
        $data["cla"] = $cla;         
        $iva = $this->producto_model->get_iva();
        $data["iva"] = $iva;  
        $data["almacen"] = $almacen;                                
        $this->session->unset_userdata("arr_var");
        $this->session->set_userdata("arr_var", NULL);
        $arravar = array();
        foreach ($provar as $ar=>$desc) {
            array_push($arravar, $desc->descripcion);    
        }
        $this->session->set_userdata("arr_var", $arravar);
        $data["com"] = $comanda; 
        $data["pro"] = $pro;       
        $data["cat"] = $cat;
        $data["uni"] = $uni;
        $data["ded"] = $ded;
        $data["pre"] = $pre;         
        $data["propre"] = $propre;
        $data["procomp"] = $procomp;
        $data["provar"] = $provar;
        $data["tp"] = $tp;
        $data["retenciones"] = $retenciones;                    
        $data["decimalesprecio"] = $this->Parametros_model->sel_decimalesprecio() * 1;    

        $lstcatcontable = $this->Contab_categoria_model->lst_categoriaproducto();
        $data["lstcatcontable"] = $lstcatcontable;

        $data["base_url"] = base_url();
        $data["content"] = "pro_add";
        $this->load->view("layout", $data);        
    }

    public function guardar(){
        $idpro = $this->input->post('txt_idpro');
        $codbar = $this->input->post('txt_codbar');
        $codaux = $this->input->post('txt_codaux');
        $nompro = trim($this->input->post('txt_nompro'));
        $despro = trim($this->input->post('txt_despro'));
        $imgpro = $this->input->post('logo');
        $unipro = $this->input->post('cmb_uni');   
        $maxpro = $this->input->post('txt_canmax');
        $minpro = $this->input->post('txt_canmin');        
        $catpro = $this->input->post('cmb_cat');
        $garpro = $this->input->post('txt_garantia');
        if($garpro == ''){ $garpro = 0; }
        $proser = $this->input->post('chk_ser');
        $proiva = $this->input->post('chk_iva');
        $dedpro = $this->input->post('cmb_ded');
        $estpro = $this->input->post('cmb_est');
        $comanda = $this->input->post('cmb_com');
        $idretencion = $this->input->post('cmb_ret');
        if($idretencion == ''){ $idretencion = 0; }
        $cla = $this->input->post('cmb_cla');        
        $cantidad = $this->input->post('txt_cantidad');        
        $chkvar = $this->input->post('chk_var');
        $chking = $this->input->post('chk_ing');
        $chkpre = $this->input->post('chk_pre');
        $maxitem = $this->input->post('txt_maxitem');
        if($maxitem == ''){ $maxitem = 0; }
        $prodesvent = $this->input->post('cmb_pro_vent');
        if($prodesvent == ''){ $prodesvent = 0; }
        $chkcompra = $this->input->post('chk_compra');
        $precompro = $this->input->post('txt_precomp');
        if($precompro == ''){ $precompro = 0; }
        $chkventa = $this->input->post('chk_venta');
        $prevenpro = $this->input->post('txt_prevent');
        if($prevenpro == ''){ $prevenpro = 0; }
        $precios = $this->input->post['precio'];

        $ubicacion = $this->input->post('txt_ubicacion');
        $subsidio = $this->input->post('txt_subsidio');
        if($subsidio == ''){ $subsidio = 0; }

        if($maxpro == ''){ $maxpro = 0; }
        if($minpro == ''){ $minpro = 0; }

        if($proser == 'on'){ $serpro = 1; } else { $serpro = 0; }
        if($proiva == 'on'){ $ivapro = 1; } else { $ivapro = 0; }
        if($chkcompra == 'on'){ $escompra = 1; } else { $escompra = 0; }
        if($chkventa == 'on'){ $esventa = 1; } else { $esventa = 0; }
        if($chkvar == 'on'){ $esvar = 1; } else { $esvar = 0; }
        if($chking == 'on'){ $esing = 1; } else { $esing = 0; }
        if($chkpre == 'on'){ $espre = 1; } else { $espre = 0; }

        $catcontable = $this->input->post('cmb_catcontable');

        //print $espre." - ".$esing; print "<br>"; 
        //die;
        /* ==================================================================== */
        $alma = array();
        $arra = array();
        foreach($this->input->post() as $nombre_campo => $valor){
            $campo = substr($nombre_campo, 0,3); 

            if($campo == "pre"){
                $c = substr($nombre_campo, 3,3); 
                if($valor == ""){ $monto = 0;
                    $arra[$c] = $c."-".$monto; 
                }else{
                    $arra[$c] = $c."-".$valor;  
                }
            }

            if($campo == "alm"){
                $a = substr($nombre_campo, 3,3); 
                if($valor == ""){ $monto = 0;
                    $alma[$a] = $a."-".$monto; 
                }else{
                    $alma[$a] = $a."-".$valor;  
                }
            }

        }

        $logo_name= $_FILES["logo"]["name"];
        /* ESTE CONDICIONAL NOS PERMITE GUARDAR O MODIFICAR USUARIOS SIN QUE LE ASIGNEN logo */
        if ($logo_name == NULL || $logo_name == ""){
            $img = NULL;
        } else { 
            $logo_name= $_FILES["logo"]["name"];
            $logo_size= $_FILES["logo"]["size"];
            $logo_type= $_FILES["logo"]["type"];
            $logo_temporal= $_FILES["logo"]["tmp_name"];

            # Limitamos los formatos de imagen admitidos a: png, jpg y gif
            if ($logo_type=="image/x-png" OR $logo_type=="image/png") { $extension="image/png"; }
            if ($logo_type=="image/pjpeg" OR $logo_type=="image/jpeg"){ $extension="image/jpeg";}
            if ($logo_type=="image/gif" OR $logo_type=="image/gif")   { $extension="image/gif"; }

            /*Reconversion de la imagen para meter en la tabla abrimos el fichero temporal en modo lectura "r" y binaria "b"*/
            $f1= fopen($logo_temporal,"rb");
            # Leemos el fichero completo limitando la lectura al tamaño del fichero
            $logo_reconvertida = fread($f1, $logo_size);
            /* Se cifra en Base64 Encode de manera que la logo quede cifrada dentro de la base de datos */
            $img = base64_encode($logo_reconvertida);
            /* cerrar el fichero temporal */
            fclose($f1);  
        }      

        // Guardar ruta de imagen
        if (isset($_POST['logo']) && $_POST['logo'] == ''){
            $imgpath = '';
        }
        else{
            $logo_name= $_FILES["logo"]["name"];

            /* ESTE CONDICIONAL NOS PERMITE GUARDAR O MODIFICAR USUARIOS SIN QUE LE ASIGNEN logo */
            if ($logo_name == NULL || $logo_name == ""){
                $imgpath = $this->input->post('old_image');
                //$imgpath = '';
            } else { 
                $logo_size= $_FILES["logo"]["size"];
                $logo_type= $_FILES["logo"]["type"];
                $logo_temporal= $_FILES["logo"]["tmp_name"];     

                /*$ext = pathinfo($logo_name, PATHINFO_EXTENSION);      */

                $split_logo = pathinfo($logo_name);
                $split_temporal = pathinfo($logo_temporal);

                $imgpath = $split_temporal['filename'].".".$split_logo['extension'];
                $file_name = FCPATH.'/public/img/producto/'.$imgpath;

                $f1= fopen($logo_temporal,"rb");
                # Leemos el fichero completo limitando la lectura al tamaño del fichero
                $logo_reconvertida = fread($f1, $logo_size);
                fclose($f1);

                $file = fopen($file_name , 'w') or die("X_x");
                fwrite($file, $logo_reconvertida);
                fclose($file);
            }        
        }    

        /* SE PASA LA VARIABLE DE SESION A UNA VARIABLE PARA ENVIARLA A LA BASE DE DATOS */ 
        if($this->session->userdata('arr_var')){ 
            $arravar = $this->session->userdata("arr_var");
        }else{
            $arravar = 0;
        }

        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */ 
        if($idpro != 0){
            /* SE ACTUALIZA EL REGISTRO DEL PRODUCTO */
            $resu = $this->producto_model->sel_pro_id($idpro);
            $unidadinicial = $resu->pro_idunidadmedida;
            $precioinicial = $resu->pro_preciocompra;
            
            $this->producto_model->pro_upd($idpro, $codbar, $codaux, $nompro, $despro, $img, $unipro, $maxpro, 
                                           $minpro, $catpro, $serpro, $ivapro, $dedpro, $estpro, $precompro, 
                                           $prevenpro, $escompra, $esventa, $esvar, $maxitem, $prodesvent, 
                                           $comanda, $cantidad, $cla, $esing, $espre, $idretencion, $garpro, 
                                           $ubicacion, $subsidio, $catcontable, $imgpath);   
            $resu = $this->producto_model->prepro_upd($idpro, $arra);
            $resu = $this->producto_model->add_var($idpro, $arravar);
            $resu = $this->producto_model->add_alma($idpro, $alma);   

            if (($unidadinicial != $unipro) or ($precioinicial != $precompro)){   
                $this->cambiar_unidadprecio($idpro, $unipro, $precioinicial, $precompro);
            }            

        } else {
            /* SE GUARDA EL REGISTRO DEL PRODUCTO */
            $idpro = $this->producto_model->pro_add($codbar, $codaux, $nompro, $despro, $img, $unipro, $maxpro, $minpro, 
                                                    $catpro, $serpro, $ivapro, $dedpro, $estpro, $precompro, $prevenpro, 
                                                    $escompra, $esventa, $esvar, $maxitem, $prodesvent, $comanda, $cantidad, 
                                                    $cla, $esing, $espre, $idretencion, $garpro, $ubicacion, $subsidio,
                                                    $catcontable, $imgpath);
            $this->producto_model->prepro_upd($idpro, $arra);
            $this->producto_model->add_var($idpro, $arravar); 
            $this->producto_model->add_alma($idpro, $alma); 
        }


   
       print "<script> window.location.href = '" . base_url() . "producto'; </script>";

    }


/*  VALIDAR CODIGO DE BARRA */
    public function valcodbar(){
        $codbar = $this->input->post('codbar');
        $data = $this->producto_model->valida_codbar($codbar);
        print $data;
    }
/*  VALIDAR CODIGO AUXILIAR */
    public function valcodaux(){
        $codaux = $this->input->post('codaux');
        $data = $this->producto_model->valida_codaux($codaux);
        print $data;
    }
/*  VALIDAR NOMBRE DEL PRODUCTO */
    public function valnompro(){
        $nompro = trim($this->input->post('nompro'));
        $data = $this->producto_model->valida_nompro($nompro);
        print $data;
    }

   /* ABRIR VENTANA PARA MODIFICAR */
    public function del_pro(){
        $idpro = $this->session->userdata("tmp_pro_id");
        $pro = $this->producto_model->sel_pro_id($idpro);
        $data["pro"] = $pro;       
        $data["base_url"] = base_url();
        $this->load->view("pro_del", $data);        
    }

   /* ELIMINAR EL PRODUCTO DE LA BASE DE DATOS */
    public function eliminar(){
        $idpro = $this->input->post('txt_idpro');
        $del = $this->producto_model->pro_del($idpro);
        $arr['mens'] = $idpro;
        print json_encode($arr);

    }

   /* Buscar EL PRODUCTO por codigo */
    public function sel_pro_codigos(){
        $codigo = $this->input->post('codigo');
        $obj = $this->producto_model->sel_pro_codigos($codigo);
        $arr['resu'] = $obj;
        print json_encode($arr);
    }


/* ====SECCION DE FUNCIONES DE VARIANTES DE PRODUCTO =========================================== */    

    /* LEVANTA LA VENTANA PARA AGREGAR EL ITEM */
    public function provar_add(){
        $idpro = $this->session->userdata("tmp_pro_id");
        $data["base_url"] = base_url();
        $data["idpro"] = $idpro;
        $this->load->view("pro_var", $data);
    } 

    /* LEVANTA LA VENTANA PARA EDITAR EL ITEM */
    public function item_edi(){
        $id = $this->session->userdata("tmp_item");
        $arreglo = $this->session->userdata("arr_var");
        $data["arreglo"] = $arreglo;
        $data["id"] = $id;
        $data["base_url"] = base_url();
        $this->load->view("pro_var", $data);
    }

    /* LEVANTA LA VENTANA PARA ELIMINAR EL ITEM */
    public function item_del(){
        $id = $this->session->userdata("tmp_item");
        $arreglo = $this->session->userdata("arr_var");
        $data["arreglo"] = $arreglo;
        $data["id"] = $id;
        $data["base_url"] = base_url();
        $this->load->view("pro_var_del", $data);
    }

    /* SE CREA Y MODIFICA VARIABLE DE SESION DE ITEM */
    public function agrega_provar(){
        
        $id = $this->input->post('txt_idprovar');
        $desc_var = $this->input->post('txt_desc');
        $arravar = $this->session->userdata("arr_var");
        /* SI YA EXISTE */
        if($id != 0){
            $arravar[$id] = $desc_var;
            $this->session->set_userdata("arr_var", NULL);
            $this->session->set_userdata("arr_var", $arravar);
        }else{
        /* SI NO EXISTE */    
            if($this->session->userdata('arr_var')){
                $arravar = $this->session->userdata("arr_var");
                $desc_var = $this->input->post('txt_desc');
                array_push($arravar, $desc_var);
                $this->session->set_userdata("arr_var", NULL);
                $this->session->set_userdata("arr_var", $arravar);
            }else{
                $arravar = array($desc_var);
                $this->session->set_userdata("arr_var", NULL);
                $this->session->set_userdata("arr_var", $arravar);
            }
        }

        $arr['mens'] = $arravar ;
        print json_encode($arr);

    }    

    /* ELIMINA EL ITEM DE LA VARIABLE DE SESION */
    public function del_item(){
        $iditem = $this->session->userdata("tmp_item");
        $arravar = $this->session->userdata("arr_var");
        unset($arravar[$iditem]);
        $this->session->set_userdata("arr_var", NULL);
        $this->session->set_userdata("arr_var", $arravar);
        $arr['mens'] = $arravar;
        print json_encode($arr);
    }

    /* VARIABLE  DE SESION PARA ID DEL ITEM */
    public function tmp_item() {
        $this->session->unset_userdata("tmp_item"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_item", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_item", $id);
        } else {
            $this->session->set_userdata("tmp_item", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);

    }

    /* RECARGA TABLA ITEM*/
    public function recarga(){
        $data["base_url"] = base_url();
        $this->load->view("pro_var_tabla", $data);
    } 

    /* LEVANTA LA VENTANA PARA AGREGAR EL ITEM */
    public function comp_add(){
        $idpro = $this->session->userdata("tmp_pro_id");
        $deting = $this->producto_model->lstpro_ing($idpro);        
        $pro = $this->producto_model->lstpro_comp($idpro);
        $unimed = $this->unidades_model->sel_unidad();
        $data["base_url"] = base_url();
        $data["deting"] = $deting;
        $data["unimed"] = $unimed;
        $data["pro"] = $pro;
        $this->load->view("pro_comp", $data);
    } 

     public function tmp_ingpro() {
        $this->session->unset_userdata("tmp_ingpro"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_ingpro", NULL);
        if ($id != NULL) { $this->session->set_userdata("tmp_ingpro", $id); } 
        else { $this->session->set_userdata("tmp_ingpro", NULL); }
        $arr['resu'] = $id;
        print json_encode($arr);
    }

    public function actualiza_ingrediente(){
        $idpro = $this->session->userdata("tmp_pro_id");
        $idproing = $this->session->userdata("tmp_ingpro");
        if($idpro != ""){
            $unimed = $this->unidades_model->sel_unidad();
            $data["unimed"] = $unimed;
            $deting = $this->producto_model->add_ing($idpro, $idproing);
            $this->session->unset_userdata("tmp_ingpro");
            $data["deting"] = $deting;
            $data["base_url"] = base_url();
            $this->load->view("ingrediente_tabla", $data);            
        }else{
            die;
        }

    }

    public function actualiza_ingredisponible(){
        $idpro = $this->session->userdata("tmp_pro_id");
        if($idpro != ""){
            $pro = $this->producto_model->lstpro_comp($idpro);
            $data["pro"] = $pro;
            $data["base_url"] = base_url();
            $this->load->view("ingrediente_disponible", $data);            
        }else{
            die;
        }

    }


     public function modifica_ingrediente() {
        $idprod = $this->input->post("idprod");
        $idproing = $this->input->post("iding");
        $unidad = $this->input->post("unidad");
        $cantidad = $this->input->post("cantidad");
        $this->producto_model->upd_ing($idprod, $idproing, $unidad, $cantidad);
        $arr['resu'] = 1;
        print json_encode($arr);
    }    

    public function del_ingpro(){
        $idpro = $this->session->userdata("tmp_pro_id");
        $idproing = $this->input->post("id");
        $this->producto_model->del_ing($idpro, $idproing);
        $objcosto = $this->producto_model->costo_ing($idpro);
        if ($objcosto != null){
            if ($objcosto->costototal != null){
                $arr['costo'] = $objcosto->costototal;
            } else {
                $arr['costo'] = 0;                
            }
        } else {
            $arr['costo'] = 0;
        }
        print json_encode($arr);        
    }

    public function actualiza_proing(){
        $idpro = $this->session->userdata("tmp_pro_id");
        $deting = $this->producto_model->lstpro_ing($idpro); 
        $unimed = $this->unidades_model->sel_unidad();
        $data["unimed"] = $unimed;
        $data["deting"] = $deting;
        $data["base_url"] = base_url();
        $this->load->view("ingrediente_tabla", $data);            
    }


    public function val_cambiounidadmedida(){
        $idpro = $this->input->post("idpro");
        if (($idpro != NULL) and ($idpro != 0)) {
            $idunidad = $this->input->post("idunidad");
            $res = $this->producto_model->val_cambiounidadmedida($idpro,$idunidad);
        } else $res = 1;
        $arr['res'] = $res;
        print json_encode($arr);        
    }

    public function cambiar_unidadprecio($idpro, $idunidad, $precioinicial, $precionuevo){
        $lst = $this->producto_model->lst_almapro($idpro);
        foreach ($lst as $row) {
            if ($row->existencia > 0){
                $subtotal = round($row->existencia * $precioinicial, 2);
                $motivo = 'EGRESO POR CAMBIO DE UNIDAD DE MEDIDA';
                if ($row->id_unimed == $idunidad) $motivo = 'EGRESO POR CAMBIO DE PRECIO';
                $this->Inventario_model->ins_kardexegreso($idpro, '', $motivo, $row->existencia, $precioinicial,                                      $subtotal, $row->id_unimed, $row->id_alm);

                $motivo = 'INGRESO POR CAMBIO DE UNIDAD DE MEDIDA';
                if ($row->id_unimed != $idunidad){
                    $nuevacantidad = $this->producto_model->get_cantidadequivalente($row->id_unimed, $idunidad) * $row->existencia;
                } else {
                    $nuevacantidad = $row->existencia;
                    $motivo = 'INGRESO POR CAMBIO DE PRECIO';
                }    

                $subtotal = round($nuevacantidad * $precionuevo, 2);
                $this->Inventario_model->ins_kardexingreso($idpro, '', $motivo, $nuevacantidad, $precionuevo, 
                                                           $subtotal, $idunidad, $row->id_alm);

                $this->producto_model->upd_almapro($idpro, $row->id_alm, $nuevacantidad, $idunidad);                
            }
        } 
        $arr['res'] = 1;
        //print json_encode($arr);        
    }

    public function cambiar_unidadmedida00(){
        $idpro = $this->input->post("idpro");
        $idunidad = $this->input->post("idunidad");
        $precioinicial = $this->input->post("precioinicial");
        $precionuevo = $this->input->post("precionuevo");

        $lst = $this->producto_model->lst_almapro($idpro);
        foreach ($lst as $row) {
            if ($row->existencia > 0){
                $subtotal = round($row->existencia * $precioinicial, 2);
                $this->Inventario_model->ins_kardexegreso($idpro, '', 'EGRESO POR CAMBIO DE UNIDAD DE MEDIDA', 
                    $row->existencia, $precioinicial, $subtotal, $row->id_unimed);

                $nuevacantidad = $this->producto_model->get_cantidadequivalente($row->id_unimed, $idunidad);

                $subtotal = round($nuevacantidad * $precionuevo, 2);
                $this->Inventario_model->ins_kardexingreso($idpro, '', 'INGRESO POR CAMBIO DE UNIDAD DE MEDIDA', $nuevacantidad, $precionuevo, $subtotal, $idunidad);
            }
        } 
        $arr['res'] = 1;
        print json_encode($arr);        
    }

    public function reporte(){
        $pro = $this->producto_model->reportepro();
        $precio = $this->producto_model->precio();
        $lstprecio = $this->producto_model->lstproprecios();
        $data["base_url"] = base_url();
        $data["pro"] = $pro;
        $data["precio"] = $precio;
        $data["lstprecio"] = $lstprecio;
        $this->load->view("producto_reporte", $data);      
    }

    function coordinates($x,$y){
     return PHPExcel_Cell::stringFromColumnIndex($x).$y;
    }

    public function reporteproXLS(){
        $pro = $this->producto_model->reportepro();
        $precio = $this->producto_model->precio();
        $lstprecio = $this->producto_model->lstproprecios();

        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Reporte de Productos');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Productos');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
/*
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo');
        $logo = FCPATH . '/public/img/contabilidad-principal.jpg'; // Provide path to your logo file
        $objDrawing->setPath($logo);
        $objDrawing->setOffsetX(8);    // setOffsetX works properly
        $objDrawing->setOffsetY(300);  //setOffsetY has no effect
        $objDrawing->setCoordinates('E1');
        $objDrawing->setHeight(75); // logo height
        $objDrawing->setWorksheet($this->excel->getActiveSheet()); 
*/
        foreach(range('A','Z') as $columnID) {
            $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $col = 0;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Id'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Codigo Barra'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Codigo Auxiliar'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Nombre'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Descripcion'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Precio Compra'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Precio Venta'); $col++;
        foreach ($precio as $pre) { 
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, $pre->desc_precios);
            $col++;
        }        
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Existencia'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Inversión'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Categoría'); $col++; 
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Estatus'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Unidad'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Clasificación'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Comanda'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'IVA'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Preparado'); $col++;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,3, 'Ingrediente');  $col++;       
        $col = 0;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        foreach ($precio as $pre) { 
            $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  
            $col++;
        } 
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;
        $this->excel->getActiveSheet()->getStyleByColumnAndRow($col,3)->getFont()->setBold(true);  $col++;

        $fila = 4;
        
        foreach ($pro as $pro) {
            $col = 0;
/*
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName('Logo');
            $objDrawing->setDescription('Logo');
            $logo = FCPATH . '/public/img/contabilidad-principal.jpg'; // Provide path to your logo file
            $objDrawing->setPath($logo);
            $objDrawing->setWidthAndHeight(80,40);
            $objDrawing->setResizeProportional(true);
            //$objDrawing->setWidth(40);
            $objDrawing->setCoordinates($this->coordinates($col, $fila));
            //$objDrawing->setHeight(75); // logo height
            $objDrawing->setWorksheet($this->excel->getActiveSheet()); 
            $this->excel->getActiveSheet()->getRowDimension($fila)->setRowHeight(40);
*/
            /*$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $pro->pro_id);*/ $col++;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $pro->pro_codigobarra); $col++;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $pro->pro_codigoauxiliar); $col++;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $pro->pro_nombre); $col++;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $pro->pro_descripcion); $col++;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, number_format($pro->pro_preciocompra,2)); $col++;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, number_format($pro->pro_precioventa,2)); $col++;
            foreach ($precio as $pr) { 
                foreach ($lstprecio as $lp) {
                    if($pr->id_precios == $lp->id_precios && $lp->pro_id == $pro->pro_id){ 
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, @$lp->monto);$col++;
                    }
                }
            }
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, number_format($pro->existencia,2)); $col++;
            
            $inversion = $pro->pro_preciocompra * $pro->existencia;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, number_format($inversion, 2)); $col++;

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $pro->cat_descripcion); $col++;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $pro->pro_estatus); $col++;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $pro->descripcion); $col++;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $pro->nom_cla); $col++;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $pro->nom_comanda); $col++;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $pro->iva); $col++;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $pro->preparado); $col++;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, $pro->ingrediente); $col++;        

          $fila++;          
        }    
        $fila++;
        
        // Suma de la columna "Inversión"
        $lastRow = $fila - 1; // Última fila escrita
        $totalInversion = "=SUM(I4:I$lastRow)"; // Fórmula para sumar la columna Inversión

        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, 'TOTAL INVERSIÓN'); // Escribir encabezado
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $totalInversion); // Escribir total con fórmula
        
        // Aplicar estilo para las celdas
        $style = $this->excel->getActiveSheet()->getStyleByColumnAndRow(7, $fila); // Estilo para la primera celda
        $style->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00'); // Color de fondo amarillo
        $style->getFont()->setBold(true); // Texto en negrita

        $style = $this->excel->getActiveSheet()->getStyleByColumnAndRow(8, $fila); // Estilo para la segunda celda
        $style->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00'); // Color de fondo amarillo
        $style->getFont()->setBold(true); // Texto en negrita
        
        
        
    // Incrementar el valor de $fila para dejar espacio debajo del total
        $fila += 2;
        
        $filename='reporteinvproductos.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        $objWriter->save('php://output');        
    }

    public function agotado(){

        $pro = $this->producto_model->agotado();
        $data["base_url"] = base_url();
        $data["pro"] = $pro;
        $this->load->view("producto_agotado", $data);      
    }

    public function reporteproagotadoXLS(){
        $pro = $this->producto_model->agotado();
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Reporte de Productos Agotados');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Reporte de Productos');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
       
        $this->excel->getActiveSheet()->setCellValue('A3', 'Id');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Codigo Barra');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Nombre');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Existencia');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Minimos');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Maximos');

        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);

        $fila = 4;
        foreach ($pro as $pro) {

            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $pro->pro_id);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $pro->pro_codigobarra);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $pro->pro_nombre);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, number_format($pro->existencia,2));
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, number_format($pro->pro_minimo,2));
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, number_format($pro->pro_maximo,2));

          $fila++;          
        }    
        $fila++;          

        
        $filename='reporteproagotados.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                    
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        $objWriter->save('php://output');        
    }

    public function proajuste() {
        $lstprecio = $this->producto_model->lstprepro();
        $pro = $this->producto_model->lstprod();
        $precpro = $this->producto_model->precpro();        
        $data["lstprecio"] = $lstprecio;
        $data["pro"] = $pro;
        $data["precpro"] = $precpro;
        $data["base_url"] = base_url();
        $data["content"] = "pro_preajuste";
        $this->load->view("layout", $data);
    }


    public function updprepro(){
        $idpro = $this->input->post("idpro");
        $idpre = $this->input->post("idpre");
        $monto = $this->input->post("monto");
        $this->producto_model->updprepro($idpro, $idpre, $monto);
        $arr['resu'] = $idpro;
        print json_encode($arr);
    }

  // control de serie de productos
    public function lst_estadoserie(){
       $registros = $this->producto_model->lst_estadoserie();
       echo json_encode($registros);       
    }


    public function controlserie() {
/*
        $this->session->set_userdata("tmp_inv_desde", NULL);
        if ($desde != NULL) { $this->session->set_userdata("tmp_inv_desde", $desde); } 
        else { $this->session->set_userdata("tmp_inv_desde", NULL); }
        $this->session->set_userdata("tmp_inv_hasta", NULL);
        if ($hasta != NULL) { $this->session->set_userdata("tmp_inv_hasta", $hasta); } 
        else { $this->session->set_userdata("tmp_inv_hasta", NULL); }
*/
        $data["base_url"] = base_url();
        $data["content"] = "pro_controlserie";
        $this->load->view("layout", $data);
    }

    public function valproductonombre(){
        $tmpArray=array();
        $nompro = $this->input->get('nombre');
        $data = $this->producto_model->valida_nombre($nompro);
        foreach ($data as $row) {
            $tmpArray[] = $row->pro_nombre;//substr($row->pro_nombre,0,49);
        }
        print json_encode($tmpArray);
    }

    public function get_producto_nombre($nombre){
      $nombre = str_replace ('%20',' ',$nombre);
      $producto = $this->producto_model->sel_pro_nombre($nombre);
      echo json_encode($producto);       
    }

    public function busca_producto_nombre(){
        $nombre = $this->input->post('nom');
        $producto = $this->producto_model->sel_pro_nombre($nombre);
        echo json_encode($producto);       
    }


    public function get_producto_series($idproducto, $serie = ''){
      $series = $this->producto_model->lst_producto_series($idproducto, $serie); 
      echo json_encode($series);       
    }

    public function producto_serie_actualizarestado(){
      $id = $this->request->id_serie;
      $idestado = $this->request->id_estado;
      $idalmacen = $this->request->id_almacen;
      $observaciones = $this->request->observaciones;
      $idusu = $this->session->userdata("sess_id");
      $strestado = "";
      if ($id != ''){
          $strestado = $this->producto_model->producto_serie_actualizarestado($id, $idestado);
          $fecha = date("Y-m-d");
          $this->Inventario_model->ins_seriekardexingreso($id, $idalmacen, $idestado, 0, 'Cambio de Estado', 
                                                          $fecha, $observaciones);          
      }      

      echo json_encode($strestado);
    }  
    
    public function get_producto_data() {
    $id = $this->input->post('id');
    $this->load->model('Producto_model'); // Asegúrate de que el modelo esté cargado
    $producto = $this->Producto_model->get_producto($id); // Ajusta según tu modelo
    if ($producto) {
        echo json_encode([
            'pro_codigobarra' => $producto->pro_codigobarra,
            'pro_precioventa' => $producto->pro_precioventa,
            'pro_nombre' => $producto->pro_nombre
        ]);
    } else {
        echo json_encode(null);
    }
}

}

?>