<?php

/* ------------------------------------------------
  ARCHIVO: Usuarios.php
  DESCRIPCION: Contiene los métodos relacionados con Usuarios.
  FECHA DE CREACIÓN: 30/06/2017 
 * 
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Usuarios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("usuario_model");
    }

    // MÉTODO PREDETERMINADO DEL CONTROLADOR
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "usu_listar";
        $this->load->view("layout", $data);
    }

    public function agregar(){
        $perfil = $this->usuario_model->perfil_lst();
        $mesero = $this->usuario_model->meseros();
        $punto = $this->usuario_model->lst_puntos(); 
        $id_usu = 0;
        $usupre = $this->usuario_model->usu_precios($id_usu);
        $data['mesero'] = $mesero; 
        $data['perfil'] = $perfil;        
        $data['punto'] = $punto;  
        $data['usupre'] = $usupre; 
        $suc = $this->usuario_model->lst_sucursal($id_usu);
        $data['suc'] = $suc; 
        $alm = $this->usuario_model->lst_almacen($id_usu);
        $data['alm'] = $alm;    
        $caja = $this->usuario_model->lst_caja_efectivo($id_usu);
        $data['caja'] = $caja;                
        $data["base_url"] = base_url();
        $data["content"] = "usu_add";
        $this->load->view("layout", $data);
    } 

    public function usu_edit(){
        $data["base_url"] = base_url();
        $id_usu = $this->session->userdata("tmp_id_usu");
        $val = 1;        
        $fic_usu = $this->usuario_model->usu_get($id_usu);
        $perfil = $this->usuario_model->perfil_lst();
        $mesero = $this->usuario_model->meseros();
        $punto = $this->usuario_model->lst_puntos(); 
        $usupre = $this->usuario_model->usu_precios($id_usu);
        $data['punto'] = $punto;                
        $data['mesero'] = $mesero;        
        $data['fic_usu'] = $fic_usu;
        $data['perfil'] = $perfil;
        $data['usupre'] = $usupre;
        $suc = $this->usuario_model->lst_sucursal($id_usu);
        $data['suc'] = $suc;        
        $alm = $this->usuario_model->lst_almacen($id_usu);
        $data['alm'] = $alm;  
        $caja = $this->usuario_model->lst_caja_efectivo($id_usu);
        $data['caja'] = $caja;  
        $data["content"] = "usu_add";
        $this->load->view("layout", $data);
    }

    public function guardar(){
        /* SE CAPTURAN LOS DATOS DEL FORMULARIO */
        $id = $this->input->post('txt_id');
        $nom = $this->input->post('txt_nombre');  
        $ape = $this->input->post('txt_apellido');
        $nac = $this->input->post('cmb_nac');
        $ide = $this->input->post('txt_identificacion');
        $fec = $this->input->post('fecha');  
        $fec = str_replace('/', '-', $fec);
        $fec = date("Y-m-d", strtotime($fec));
        $tlf = $this->input->post('txt_telefono');
        $ema = $this->input->post('txt_email');
        $dir = $this->input->post('txt_direccion');
        $usu = $this->input->post('txt_usuario');
        $pwd = $this->input->post('txt_clave');
        $est = $this->input->post('cmb_estatus');
        $foto = $this->input->post('foto');
        $perfil = $this->input->post('cmb_perfil');
        $idmesero = $this->input->post('cmb_mesero');
        if($idmesero == ''){ $idmesero = 0; }
        $idpunto = $this->input->post('cmb_punto');
        if($idpunto == ''){ $idpunto = 0; }    

        $preventa = $this->input->post('txtpre0'); 

        $arra = array();  $sucarr = array(); $almarr = array(); $cajarr = array();

        foreach($this->input->post() as $nombre_campo => $valor){
            $campo = substr($nombre_campo, 0,5); 
            if($campo == "txtpp"){
                $c = substr($nombre_campo, 5,5); 
                if($valor == ""){ $monto = 0;
                    $arra[$c] = $c."-".$monto; 
                }else{
                    $arra[$c] = $c."-".$valor;  
                }
            }
            if($campo == "txtsu"){
                $s = substr($nombre_campo, 6,6); 
                if($valor == ""){ $monto = 0;
                    $sucarr[$s] = $s."-".$monto; 
                }else{
                    $sucarr[$s] = $s."-".$valor;  
                }
            }
            if($campo == "txtal"){
                $a = substr($nombre_campo, 6,6); 
                if($valor == ""){ $monto = 0;
                    $almarr[$a] = $a."-".$monto; 
                }else{
                    $almarr[$a] = $a."-".$valor;  
                }
            }
            if($campo == "txtca"){
                $j = substr($nombre_campo, 6,6); 
                if($valor == ""){ $monto = 0;
                    $cajarr[$j] = $j."-".$monto; 
                }else{
                    $cajarr[$j] = $j."-".$valor;  
                }
            }
        }

    //    print_r($sucarr); die;
        if (isset($_FILES["foto"])){
            $foto_name= $_FILES["foto"]["name"];
            /* ESTE CONDICIONAL NOS PERMITE GUARDAR O MODIFICAR USUARIOS SIN QUE LE ASIGNEN FOTO */
            if ($foto_name == NULL || $foto_name == ""){
                $fot = "";
            } else { 
                $foto_name= $_FILES["foto"]["name"];
                $foto_size= $_FILES["foto"]["size"];
                $foto_type= $_FILES["foto"]["type"];
                $foto_temporal= $_FILES["foto"]["tmp_name"];

                # Limitamos los formatos de imagen admitidos a: png, jpg y gif
                if ($foto_type=="image/x-png" OR $foto_type=="image/png") { $extension="image/png"; }
                if ($foto_type=="image/pjpeg" OR $foto_type=="image/jpeg"){ $extension="image/jpeg";}
                if ($foto_type=="image/gif" OR $foto_type=="image/gif")   { $extension="image/gif"; }

                /*Reconversion de la imagen para meter en la tabla abrimos el fichero temporal en modo lectura "r" y binaria "b"*/
                $f1= fopen($foto_temporal,"rb");
                # Leemos el fichero completo limitando la lectura al tamaño del fichero
                $foto_reconvertida = fread($f1, $foto_size);
                /* Se cifra en Base64 Encode de manera que la foto quede cifrada dentro de la base de datos */
                $fot = base64_encode($foto_reconvertida);
                /* cerrar el fichero temporal */
                fclose($f1);  
            }
        }else{
            $fot = NULL;
        }    

        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($id != 0){
            /* SE ACTUALIZA EL REGISTRO DEL USUARIO */
            $resu = $this->usuario_model->usu_upd($id, $nom, $ape, $nac, $ide, $fec, $tlf, $ema, $dir, $usu, $pwd, $est, $fot, $perfil, $idmesero, $idpunto);
            $resu = $this->usuario_model->prepro_usu($id, $arra);
            $this->usuario_model->sucursal_usu($id, $sucarr);
            $this->usuario_model->almacen_usu($id, $almarr);
            $this->usuario_model->caja_usu($id, $cajarr);
        } else {
            /* SE GUARDA EL REGISTRO DEL USUARIO */
            $id = $this->usuario_model->usu_add($nom, $ape, $nac, $ide, $fec, $tlf, $ema, $dir, $usu, $pwd, $est, $fot, $perfil, $idmesero, $idpunto);
            $this->usuario_model->prepro_usu($id, $arra);
            $this->usuario_model->sucursal_usu($id, $sucarr);
            $this->usuario_model->almacen_usu($id, $almarr);
            $this->usuario_model->caja_usu($id, $cajarr);
        }


/*





*/


        /* SE REDIRECCIONA A LA PANTALLA PRINCIPAL*/
        print "<script> window.location.href = '" . base_url() . "usuarios'; </script>";
        /*        print "<script> alert('LISTO'); </script>";*/
                        
    } 

        public function temp_usu() {
            $id = $this->input->post("id");
            $this->session->set_userdata("tmp_id_usu", NULL);
            if ($id != NULL) {
                $this->session->set_userdata("tmp_id_usu", $id);
            } else {
                $this->session->set_userdata("tmp_id_usu", NULL);
            }
            $arr['resu'] = 1;
            print json_encode($arr);
    }


    public function listadoDataUsu() {

        $registro = $this->usuario_model->lst_usu();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->id_usu.'\" class=\"btn btn-success btn-xs btn-grad usu_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_usu.'\" class=\"btn btn-danger btn-xs btn-grad usu_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' . $row->id_usu . '",
                      "cedula":"' . $row->ide_usu . '",
                      "nombres":"' . $row->nom_usu . '",
                      "apellidos":"' . $row->ape_usu . '",
                      "usuario":"' . $row->log_usu . '",
                      "estatus":"' . $row->est_usu . '",
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';

    }
    /* ABRIR FORMULARIO CON ROLES */
    public function usu_rol(){
        $data["base_url"] = base_url();
        $id_usu = $this->session->userdata("tmp_id_usu");
        $fic_usu = $this->usuario_model->usu_get($id_usu);
        $data['fic_usu'] = $fic_usu;
        $data["content"] = "usu_roles";
        $this->load->view("layout", $data);
    }

    /* RECIBE LOS DATOS DE ROLES */
    public function usu_rol_add(){
        $id_usu = $this->session->userdata("tmp_id_usu");
        /* Seccion de Categoria */
        $catv = $this->input->post('catv');
        $cata = $this->input->post('cata');
        $catu = $this->input->post('catu');
        $cate = $this->input->post('cate');
        $catr = $this->input->post('catr');
        $rol_cat = $this->usuario_model->rol_usu_cat($id_usu, $catv, $cata, $catu, $cate, $catr);

        $arr['usu'] = $id_usu;
        $arr['catv'] = $catv;
        $arr['cata'] = $cata;
        $arr['catu'] = $catu;
        $arr['cate'] = $cate;
        $arr['catr'] = $catr;

        
        $resu = 1;
        $arr['resu'] = $resu;
        print json_encode($arr);

    }

    public function del_usu(){
        $id_usu = $this->input->post("id");
        $mesero = $this->usuario_model->usua_del($id_usu);
        $resu = $id_usu;
        $arr['resu'] = $resu;
        print json_encode($arr);
    }
  
    public function usu_upd_acceso(){
        $this->usuario_model->usu_upd_acceso();
        $resu = 1;
        $arr['resu'] = $resu;
        print json_encode($arr);
    }

}

?>