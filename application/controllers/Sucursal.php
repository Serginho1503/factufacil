<?php

/*------------------------------------------------
  ARCHIVO: Sucursal.php
  DESCRIPCION: Contiene los métodos relacionados con la Sucursal.
  FECHA DE CREACIÓN: 13/07/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Sucursal extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("sucursal_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "sucursal";
        $this->load->view("layout", $data);
    }

    /* CARGA DE DATOS AL DATATABLE */
    public function listadoDataSuc() {

        $registro = $this->sucursal_model->lst_sucursales();
        $tabla = "";

        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Ver\" id=\"'.$row->id_sucursal.'\" class=\"btn btn-success btn-xs btn-grad suc_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_sucursal.'\" class=\"btn btn-danger btn-xs btn-grad suc_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{"id":"' . $row->id_sucursal . '",
                      "nombre":"' . $row->nom_sucursal . '",
                      "direccion":"' . $row->dir_sucursal . '",
                      "telefono":"' . $row->telf_sucursal . '",
                      "correo":"' . $row->mail_sucursal . '",   
                      "encargado":"' . $row->enca_sucursal . '",                                                               
                      "ver":"'.$ver.'"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);

        echo '{"data":[' . $tabla . ']}';
    }

    /* ABRIR VENTANA PARA AGREGAR */
    public function agregar(){
        $empresas = $this->sucursal_model->lst_empresas();
        $data["empresas"] = $empresas;
        $data["base_url"] = base_url();
        $data["content"] = "suc_add";
        $this->load->view("layout", $data);        
    }

    /* SE GUARDA O SE MODIFICA EL REGISTRO DE LA SUCURSAL */
    public function guardar(){
        $idsuc = $this->input->post('txt_idsuc');
        $nomsuc = $this->input->post('txt_nom');
        $encasuc = $this->input->post('txt_enca');
        $tlfsuc = $this->input->post('txt_telefono');
        $emasuc = $this->input->post('txt_email');
        $dirsuc = $this->input->post('txt_dir');
        $empresa = $this->input->post('cmb_empresa');
        $ordenservicio = $this->input->post('txt_ordenservicio');
        if ($ordenservicio == '') { $ordenservicio = 1;}
        $pie1proforma = $this->input->post('txt_pie1proforma');

        
        if (isset($_FILES["logo"])) {
            $logo_name= $_FILES["logo"]["name"];
            /* ESTE CONDICIONAL NOS PERMITE GUARDAR O MODIFICAR USUARIOS SIN QUE LE ASIGNEN logo */

            if ($logo_name == NULL || $logo_name == ""){
                $old_logo = $this->input->post('old_logo');
                if ($old_logo == '')
                    $img = NULL;
                else
                    $img = '';
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
        }
        else{
            $img = NULL;
        }    

        // Guardar ruta de logo
        if (isset($_POST['logo']) && $_POST['logo'] == ''){
            $old_logo = $this->input->post('old_logoencabpath');
            $old_logo = trim($old_logo);
            if ($old_logo === '')
                $imgpath = '';
            else
                $imgpath = $old_logo;
        }
        else{
            $logo_name= $_FILES["logo"]["name"];

            /* ESTE CONDICIONAL NOS PERMITE GUARDAR O MODIFICAR USUARIOS SIN QUE LE ASIGNEN logo */
            if ($logo_name == NULL || $logo_name == ""){
                $old_logo = $this->input->post('old_logoencabpath');
                $old_logo = trim($old_logo);
                if ($old_logo === '')
                    $imgpath = '';
                else
                    $imgpath = $old_logo;
            } else { 
                $logo_size= $_FILES["logo"]["size"];
                $logo_type= $_FILES["logo"]["type"];
                $logo_temporal= $_FILES["logo"]["tmp_name"];     

                /*$ext = pathinfo($logo_name, PATHINFO_EXTENSION);      */

                $split_logo = pathinfo($logo_name);
                $split_temporal = pathinfo($logo_temporal);

                $imgpath = $split_temporal['filename'].".".$split_logo['extension'];
                $file_name = FCPATH.'/public/img/sucursal/'.$imgpath;

                $f1= fopen($logo_temporal,"rb");
                # Leemos el fichero completo limitando la lectura al tamaño del fichero
                $logo_reconvertida = fread($f1, $logo_size);
                fclose($f1);

                $file = fopen($file_name , 'w') or die("X_x");
                fwrite($file, $logo_reconvertida);
                fclose($file);
            }        
        }    
    

        // Logo Detalle
        if (isset($_FILES["logodetalle"])) {
            $logo_name= $_FILES["logodetalle"]["name"];
            /* ESTE CONDICIONAL NOS PERMITE GUARDAR O MODIFICAR USUARIOS SIN QUE LE ASIGNEN logo */
            if ($logo_name == NULL || $logo_name == ""){
                $old_logo = $this->input->post('old_logodetalle');
                if ($old_logo == '')
                    $imgdetalle = NULL;
                else
                    $imgdetalle = '';
            } else { 
                $logo_name= $_FILES["logodetalle"]["name"];
                $logo_size= $_FILES["logodetalle"]["size"];
                $logo_type= $_FILES["logodetalle"]["type"];
                $logo_temporal= $_FILES["logodetalle"]["tmp_name"];

                # Limitamos los formatos de imagen admitidos a: png, jpg y gif
                if ($logo_type=="image/x-png" OR $logo_type=="image/png") { $extension="image/png"; }
                if ($logo_type=="image/pjpeg" OR $logo_type=="image/jpeg"){ $extension="image/jpeg";}
                if ($logo_type=="image/gif" OR $logo_type=="image/gif")   { $extension="image/gif"; }

                /*Reconversion de la imagen para meter en la tabla abrimos el fichero temporal en modo lectura "r" y binaria "b"*/
                $f1= fopen($logo_temporal,"rb");
                # Leemos el fichero completo limitando la lectura al tamaño del fichero
                $logo_reconvertida = fread($f1, $logo_size);
                /* Se cifra en Base64 Encode de manera que la logo quede cifrada dentro de la base de datos */
                $imgdetalle = base64_encode($logo_reconvertida);
                /* cerrar el fichero temporal */
                fclose($f1);  
            }  
        }    
        else{
            $imgdetalle = NULL;
        }

        // Logo Pie
        if (isset($_FILES["logopie"])) {
            $logo_name= $_FILES["logopie"]["name"];
            /* ESTE CONDICIONAL NOS PERMITE GUARDAR O MODIFICAR USUARIOS SIN QUE LE ASIGNEN logo */
            if ($logo_name == NULL || $logo_name == ""){
                $old_logo = $this->input->post('old_logopie');
                if ($old_logo == '')
                    $imgpie = NULL;
                else
                    $imgpie = '';
            } else { 
                $logo_name= $_FILES["logopie"]["name"];
                $logo_size= $_FILES["logopie"]["size"];
                $logo_type= $_FILES["logopie"]["type"];
                $logo_temporal= $_FILES["logopie"]["tmp_name"];

                # Limitamos los formatos de imagen admitidos a: png, jpg y gif
                if ($logo_type=="image/x-png" OR $logo_type=="image/png") { $extension="image/png"; }
                if ($logo_type=="image/pjpeg" OR $logo_type=="image/jpeg"){ $extension="image/jpeg";}
                if ($logo_type=="image/gif" OR $logo_type=="image/gif")   { $extension="image/gif"; }

                /*Reconversion de la imagen para meter en la tabla abrimos el fichero temporal en modo lectura "r" y binaria "b"*/
                $f1= fopen($logo_temporal,"rb");
                # Leemos el fichero completo limitando la lectura al tamaño del fichero
                $logo_reconvertida = fread($f1, $logo_size);
                /* Se cifra en Base64 Encode de manera que la logo quede cifrada dentro de la base de datos */
                $imgpie = base64_encode($logo_reconvertida);
                /* cerrar el fichero temporal */
                fclose($f1);  
            }  
        }    
        else{
            $imgpie = NULL;
        }

        //var_dump($img);
        //die;

        /* EVALUAR SI EL REGISTRO ES DE ACTUALIZACION O DE INGRESO */
        if($idsuc != 0){
             /* SE ACTUALIZA EL REGISTRO DEL ALMACEN */
            $this->sucursal_model->suc_upd($idsuc, $nomsuc, $encasuc, $tlfsuc, $emasuc, $dirsuc, $img, $empresa, 
                                           $ordenservicio, $pie1proforma, $imgdetalle, $imgpie, $imgpath);   
        } else {
            /* SE GUARDA EL REGISTRO DEL ALMACEN */
            $this->sucursal_model->suc_add($nomsuc, $encasuc, $tlfsuc, $emasuc, $dirsuc, $img, $empresa, 
                                           $ordenservicio, $pie1proforma, $imgdetalle, $imgpie, $imgpath);
        }
        
       print "<script> window.location.href = '" . base_url() . "sucursal'; </script>";
   
    }


    /* VARIABLE DE SESION PARA PASAR DATOS */
     public function tmp_suc() {
        $this->session->unset_userdata("tmp_suc_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_suc_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_suc_id", $id);
        } else {
            $this->session->set_userdata("tmp_suc_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    } 
        
    /* ABRIR VENTANA PARA AGREGAR */
    public function suc_edit(){
        $idsuc = $this->session->userdata("tmp_suc_id");
        $suc = $this->sucursal_model->sel_suc_id($idsuc); 
        $data["suc"] = $suc;       
        $empresas = $this->sucursal_model->lst_empresas();
        $data["empresas"] = $empresas;
        $data["base_url"] = base_url();
        $data["content"] = "suc_add";
        $this->load->view("layout", $data);        
    }

    /* ABRIR VENTANA PARA ELIMINAR */
    public function del_suc(){
        $idsuc = $this->session->userdata("tmp_suc_id");
        $suc = $this->sucursal_model->sel_suc_id($idsuc); 
        $data["suc"] = $suc; 
        $data["base_url"] = base_url();
        $this->load->view("suc_del", $data);
    }

    /* SE ELIMINA EL REGISTRO SELECCIONADO */
    public function eliminar(){
        $idsuc = $this->input->post('id'); 
        $suc = $this->sucursal_model->suc_del($idsuc);
        $arr['mens'] = $suc;
        print json_encode($arr); 

    }

    public function lst_sucursales(){
       $registros = $this->sucursal_model->lst_sucursales();
       echo json_encode($registros);       
    }

    public function lst_sucursales_usuario(){
       $registros = $this->sucursal_model->lst_sucursal_usuario();
       echo json_encode($registros);       
    }


}

?>