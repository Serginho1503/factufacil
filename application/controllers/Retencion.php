<?php

/*------------------------------------------------
  ARCHIVO: Retencion.php
  DESCRIPCION: Contiene los métodos relacionados con la Retencion.
  FECHA DE CREACIÓN: 19/03/2018
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Retencion extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("Retencion_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $idusu = $this->session->userdata("sess_id");
        $data["base_url"] = base_url();
        $data["content"] = "retencion_concepto_lst";
        $this->load->view("layout", $data);
    }

    public function tmp_ret() {
        $this->session->unset_userdata("tmp_ret_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_ret_id", NULL);
        if ($id != NULL) {
            $this->session->set_userdata("tmp_ret_id", $id);
        } else {
            $this->session->set_userdata("tmp_ret_id", NULL);
        }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function upd_ret(){
        $idret = $this->session->userdata("tmp_ret_id");
        $data["base_url"] = base_url();
        $ret_desc = $this->Retencion_model->sel_ret_id($idret);
        $data["ret"] = $ret_desc;
        $this->load->view("retencion_concepto_add", $data);
    }

    public function agregar(){
        $idret = $this->input->post('txt_idret'); 
        $codret = $this->input->post('txt_codret'); 
        $porret = $this->input->post('txt_porret');
        $descret = $this->input->post('txt_descret');
        $editable = $this->input->post('chkeditable');
        if($editable == 'on'){ $cedit = 1; } else { $cedit = 0; }
        if($idret != 0){
            $resu = $this->Retencion_model->ret_upd($idret, $codret, $porret, $descret, $cedit);
        } else {
            $resu = $this->Retencion_model->ret_add($codret, $porret, $descret, $cedit);
        }
        $arr['mens'] = $idret ;
        print json_encode($arr); 
    }

    public function add_ret(){
        $data["base_url"] = base_url();
        $this->load->view("retencion_concepto_add", $data);
    } 

    public function del_ret(){
        $idret = $this->input->post('id'); 
        $resu = $this->Retencion_model->ret_del($idret);
        $arr['mens'] = $idret ;
        print json_encode($arr); 
    }


    public function listadoRet() {
        $registro = $this->Retencion_model->sel_ret();
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Editar Retencion\" id=\"'.$row->id_cto_retencion.'\" class=\"btn btn-success btn-xs btn-grad ret_ver\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_cto_retencion.'\" class=\"btn btn-danger btn-xs btn-grad ret_del\"><i class=\"fa fa-trash-o\"></i></a></div>';
            $tabla.='{  "id":"' .$row->id_cto_retencion. '",
                        "codigo":"' .$row->cod_cto_retencion. '",
                        "descripcion":"' .substr(addslashes($row->descripcion_retencion), 0, 100)."...". '",
                        "porcentaje":"' .$row->porciento_cto_retencion. '",
                        "ver":"'.$ver.'"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }
}

?>