<?php

/*------------------------------------------------
  ARCHIVO: Contab_categoria.php
  DESCRIPCION: Contiene los métodos relacionados con categorias.
  
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Contab_categoria extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("contabilidad/Contab_categoria_model");
        $this->load->Model("contabilidad/Contab_plancuentas_model");
        $this->load->Model("Empresa_model");
    }

 
    public function general() {
        $empresas = $this->Empresa_model->lst_empresa();
        $data["empresas"] = $empresas;

        $empresa = $this->session->userdata("tmp_catgen_empresa");
        if ($empresa == NULL){
          $empresa = 0; 
          if (count($empresas)) { $empresa = $empresas[0]->id_emp; }
          $this->session->set_userdata("tmp_catgen_empresa", NULL);
          $this->session->set_userdata("tmp_catgen_empresa", $empresa); 
        }  
        $data["tmpempresa"] = $empresa;
        $tipo = $this->session->userdata("tmp_catgen_tipo");
        if ($tipo == NULL){
            $tipo = 1;
            $this->session->set_userdata("tmp_catgen_tipo", NULL);
            $this->session->set_userdata("tmp_catgen_tipo", $tipo); 
        }  
        $data["tmptipo"] = $tipo;
  
        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_categoria_general";
        $this->load->view("layout", $data);
    }

    public function tmp_catgen() {
        $this->session->unset_userdata("tmp_catgen_empresa");
        $empresa = $this->input->post("empresa");
        $this->session->set_userdata("tmp_catgen_empresa", NULL);
        if ($empresa != NULL) { $this->session->set_userdata("tmp_catgen_empresa", $empresa);} 
        else { $this->session->set_userdata("tmp_catgen_empresa", NULL);}
        $this->session->unset_userdata("tmp_catgen_tipo");
        $tipo = $this->input->post("tipo");
        $this->session->set_userdata("tmp_catgen_tipo", NULL);
        if ($tipo != NULL) { $this->session->set_userdata("tmp_catgen_tipo", $tipo);} 
        else { $this->session->set_userdata("tmp_catgen_tipo", NULL);}
        $arr['resu'] = 1;
        print json_encode($arr);
      } 

      public function listadoCategoriageneral() {
        $empresa = $this->session->userdata("tmp_catgen_empresa");
        $tipo = $this->session->userdata("tmp_catgen_tipo");
        $registro = $this->Contab_categoria_model->sel_categoriageneral($empresa, $tipo);
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id.'\" class=\"btn btn-danger btn-xs btn-grad del_categoria\"><i class=\"fa fa-trash-o\"></i></a> </div>';
            $categoria = '<div ><input type=\"text\" style=\"width: 300px;\" class=\"col-md-12 text-left form-control upd_categoria\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.addslashes($row->categoria).'\" ></div>';
            $codigocuenta = '<div id=\"divasiento'.$row->id.'\" class=\"divcuenta \" ><input type=\"text\" class=\"col-md-12 form-control tdvalorcodcuenta upd_cuenta autocomplete \" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.$row->codigocuenta.'\" data-source=\"'.base_url('contabilidad/contab_categoria/valcuentacodigo?codigo=').'\"  ></div>';
            $descripcuenta = '<div ><label class=\"col-md-12 desc_cuenta text-left\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" >'.addslashes($row->descripcion).'</label> </div>';

            $tabla.='{  "id":"' .$row->id. '",
                        "categoria":"' .$categoria. '",
                        "codigocuenta":"' .$codigocuenta. '",
                        "descripcion":"' .$descripcuenta. '",
                        "ver":"' .$ver. '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function retencion() {
        $empresas = $this->Empresa_model->lst_empresa();
        $data["empresas"] = $empresas;

        $empresa = $this->session->userdata("tmp_catret_empresa");
        if ($empresa == NULL){
          $empresa = 0; 
          if (count($empresas)) { $empresa = $empresas[0]->id_emp; }
          $this->session->set_userdata("tmp_catret_empresa", NULL);
          $this->session->set_userdata("tmp_catret_empresa", $empresa); 
        }  
        $data["tmpempresa"] = $empresa;
        $tipo = $this->session->userdata("tmp_catret_tipo");
        if ($tipo == NULL){
            $tipo = 4;
            $this->session->set_userdata("tmp_catret_tipo", NULL);
            $this->session->set_userdata("tmp_catret_tipo", $tipo); 
        }  
        $data["tmptipo"] = $tipo;
  
        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_categoria_retencion";
        $this->load->view("layout", $data);
    }

    public function tmp_catret() {
        $this->session->unset_userdata("tmp_catret_empresa");
        $empresa = $this->input->post("empresa");
        $this->session->set_userdata("tmp_catret_empresa", NULL);
        if ($empresa != NULL) { $this->session->set_userdata("tmp_catret_empresa", $empresa);} 
        else { $this->session->set_userdata("tmp_catret_empresa", NULL);}
        $this->session->unset_userdata("tmp_catret_tipo");
        $tipo = $this->input->post("tipo");
        $this->session->set_userdata("tmp_catret_tipo", NULL);
        if ($tipo != NULL) { $this->session->set_userdata("tmp_catret_tipo", $tipo);} 
        else { $this->session->set_userdata("tmp_catret_tipo", NULL);}
        $arr['resu'] = 1;
        print json_encode($arr);
      } 

    public function listadoCategoriaretencion() {
        $empresa = $this->session->userdata("tmp_catret_empresa");
        $tipo = $this->session->userdata("tmp_catret_tipo");
        $registro = $this->Contab_categoria_model->sel_categoriageneral($empresa, $tipo);
        $tabla = "";
        foreach ($registro as $row) {
            $codigocuenta = '<div id=\"divasiento'.$row->id.'\" class=\"divcuenta \" ><input type=\"text\" class=\"col-md-12 form-control tdvalorcodcuenta upd_cuenta autocomplete \" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.$row->codigocuenta.'\" data-source=\"'.base_url('contabilidad/contab_categoria/valcuentacodigo_retenciones?codigo=').'\"  ></div>';
            $descripcuenta = '<div ><label class=\"col-md-12 desc_cuenta text-left\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" >'.addslashes($row->descripcion).'</label> </div>';

            $tabla.='{  "id":"' .$row->id. '",
                        "categoria":"' .$row->categoria. '",
                        "codigocuenta":"' .$codigocuenta. '",
                        "descripcion":"' .$descripcuenta. '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function valcuentacodigo(){
        $empresa = $this->session->userdata("tmp_catgen_empresa");
        $codigo = $this->input->get('codigo');
        $data = $this->Contab_plancuentas_model->lst_cuentacodigo($codigo, $empresa);
        foreach ($data as $row) {
            $tmpArray[] = $row->codigocuenta;
        }
        print json_encode($tmpArray);
    }    

    public function valcuentacodigo_retenciones(){
        $empresa = $this->session->userdata("tmp_catret_empresa");
        $codigo = $this->input->get('codigo');
        $data = $this->Contab_plancuentas_model->lst_cuentacodigo($codigo, $empresa);
        foreach ($data as $row) {
            $tmpArray[] = $row->codigocuenta;
        }
        print json_encode($tmpArray);
    }    

    public function valcuentacodigo_factura(){
        $empresa = $this->session->userdata("tmp_catfac_empresa");
        $codigo = $this->input->get('codigo');
        $tmpArray = [];
        $data = $this->Contab_plancuentas_model->lst_cuentacodigo($codigo, $empresa);
        foreach ($data as $row) {
            $tmpArray[] = $row->codigocuenta;
        }
        print json_encode($tmpArray);
    }    

    public function busca_cuenta(){
        $codcuenta = $this->input->post('codcuenta'); 
        $empresa = $this->input->post('empresa'); 
        $resu = $this->Contab_plancuentas_model->sel_cuentacodigo($codcuenta, $empresa);
        $arr['resu'] = $resu;
        print json_encode($arr); 
    }    

    public function inserta_categoria(){
        //$tipo = 10; //Gastos
        $tipo = $this->session->userdata("tmp_catgen_tipo");
        $this->Contab_categoria_model->ins_categoria($tipo);
        $arr['resu'] = 1;
        print json_encode($arr); 
    }    

    public function actualiza_categoria_nombre(){
        $id = $this->input->post('id'); 
        $categoria = $this->input->post('categoria'); 
        $this->Contab_categoria_model->upd_categoria_nombre($id, $categoria);
        $arr['resu'] = 1;
        print json_encode($arr); 
    }    

    public function actualiza_categoria_cuenta(){
        $id = $this->input->post('id'); 
        $idcuenta = $this->input->post('idcuenta'); 
        $empresa = $this->input->post('empresa'); 
        $this->Contab_categoria_model->upd_categoria_cuenta($id, $empresa, $idcuenta);
        $arr['resu'] = 1;
        print json_encode($arr); 
    }    

    public function actualiza_categoriagasto_cuenta(){
        $id = $this->input->post('id'); 
        $idcuenta = $this->input->post('idcuenta'); 
        $empresa = $this->input->post('empresa'); 
        $this->Contab_categoria_model->upd_categoria_gasto_cuenta($id, $empresa, $idcuenta);
        $arr['resu'] = 1;
        print json_encode($arr); 
    }    

    public function elimina_categoria_cuenta(){
        $tipo = $this->session->userdata("tmp_catgen_tipo");
        $id = $this->input->post('id'); 
        $res = $this->Contab_categoria_model->del_categoria_cuenta($id, $tipo);
        $arr['mens'] = $res;
        print json_encode($arr); 
    }    

    public function compraventa() {
        $this->Contab_categoria_model->limpia_categoria_nombre();
        $empresas = $this->Empresa_model->lst_empresa();
        $data["empresas"] = $empresas;

        $empresa = $this->session->userdata("tmp_catfac_empresa");
        if ($empresa == NULL){
          $empresa = 0; 
          if (count($empresas)) { $empresa = $empresas[0]->id_emp; }
          $this->session->set_userdata("tmp_catfac_empresa", NULL);
          $this->session->set_userdata("tmp_catfac_empresa", $empresa); 
        }  
        $data["tmpempresa"] = $empresa;
  
        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_categoria_compraventa";
        $this->load->view("layout", $data);
    }

    public function tmp_catfac() {
        $this->session->unset_userdata("tmp_catfac_empresa");
        $empresa = $this->input->post("empresa");
        $this->session->set_userdata("tmp_catfac_empresa", NULL);
        if ($empresa != NULL) { $this->session->set_userdata("tmp_catfac_empresa", $empresa);} 
        else { $this->session->set_userdata("tmp_catfac_empresa", NULL);}
        $arr['resu'] = 1;
        print json_encode($arr);
      } 

    public function listadoCategoriafacturas() {
        $empresa = $this->session->userdata("tmp_catfac_empresa");
        $registro = $this->Contab_categoria_model->sel_categoriafactura($empresa);
        $tabla = "";
        foreach ($registro as $row) {
            $codigocuenta = '<div id=\"divasiento'.$row->id.'\" class=\"divcuenta \" ><input type=\"text\" class=\"col-md-12 form-control tdvalorcodcuenta upd_cuenta autocomplete \" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.$row->codigocuenta.'\" data-source=\"'.base_url('contabilidad/contab_categoria/valcuentacodigo_factura?codigo=').'\"  ></div>';
            $descripcuenta = '<div ><label class=\"col-md-12 desc_cuenta text-left\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" >'.addslashes($row->descripcion).'</label> </div>';

            $tabla.='{  "id":"' .$row->id. '",
                        "categoria":"' .$row->categoria. '",
                        "codigocuenta":"' .$codigocuenta. '",
                        "descripcion":"' .$descripcuenta. '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function listadoCategoriagastos() {
        $empresa = $this->session->userdata("tmp_catfac_empresa");
        $registro = $this->Contab_categoria_model->sel_categoriagasto($empresa);
        $tabla = "";
        foreach ($registro as $row) {
            $ver = '<div> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id.'\" class=\"btn btn-danger btn-xs btn-grad del_categoria\"><i class=\"fa fa-trash-o\"></i></a> </div>';
            $categoria = '<div ><input type=\"text\" class=\"col-md-12 text-left form-control tdvalorcategoria upd_categoria\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.addslashes($row->categoria).'\" ></div>';
            $codigocuenta = '<div id=\"divasiento'.$row->id.'\" class=\"divcuentagas \" ><input type=\"text\" class=\"col-md-12 form-control tdvalorcodcuenta upd_cuentagas autocomplete \" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.$row->codigocuenta.'\" data-source=\"'.base_url('contabilidad/contab_categoria/valcuentacodigo_factura?codigo=').'\"  ></div>';
            $descripcuenta = '<div ><label class=\"col-md-12 desc_cuentagas text-left\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" >'.addslashes($row->descripcion).'</label> </div>';

            $tabla.='{  "id":"' .$row->id. '",
                        "categoria":"' .$row->categoria. '",
                        "codigocuenta":"' .$codigocuenta. '",
                        "descripcion":"' .$descripcuenta. '",
                        "ver":"' .$ver. '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function listadoCategoriaformapagocli() {
        $empresa = $this->session->userdata("tmp_catfac_empresa");
        $registro = $this->Contab_categoria_model->sel_categoriaformapagocli($empresa);
        $tabla = "";
        foreach ($registro as $row) {
            $codigocuenta = '<div id=\"divasiento'.$row->id_formapago.'\" class=\"divcuentafp \" ><input type=\"text\" class=\"col-md-12 form-control tdvalorcodcuenta upd_cuentafp autocomplete \" name=\"'.$row->id_formapago.'\" id=\"'.$row->id_formapago.'\" value=\"'.$row->codigocuenta.'\" data-source=\"'.base_url('contabilidad/contab_categoria/valcuentacodigo_factura?codigo=').'\"  ></div>';
            $descripcuenta = '<div ><label class=\"col-md-12 desc_cuentafp text-left\" name=\"'.$row->id_formapago.'\" id=\"'.$row->id_formapago.'\" >'.addslashes($row->descripcion).'</label> </div>';

            $tabla.='{  "id":"' .$row->id_formapago. '",
                        "formapago":"' .$row->nombre_formapago. '",
                        "codigocuenta":"' .$codigocuenta. '",
                        "descripcion":"' .$descripcuenta. '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function listadoCategoriaformapagopro() {
        $empresa = $this->session->userdata("tmp_catfac_empresa");
        $registro = $this->Contab_categoria_model->sel_categoriaformapagopro($empresa);
        $tabla = "";
        foreach ($registro as $row) {
            $codigocuenta = '<div id=\"divasiento'.$row->id_formapago.'\" class=\"divcuentafp_pro \" ><input type=\"text\" class=\"col-md-12 form-control tdvalorcodcuenta upd_cuentafp_pro autocomplete \" name=\"'.$row->id_formapago.'\" id=\"'.$row->id_formapago.'\" value=\"'.$row->codigocuenta.'\" data-source=\"'.base_url('contabilidad/contab_categoria/valcuentacodigo_factura?codigo=').'\"  ></div>';
            $descripcuenta = '<div ><label class=\"col-md-12 desc_cuentafp_pro text-left\" name=\"'.$row->id_formapago.'\" id=\"'.$row->id_formapago.'\" >'.addslashes($row->descripcion).'</label> </div>';

            $tabla.='{  "id":"' .$row->id_formapago. '",
                        "formapago":"' .$row->nombre_formapago. '",
                        "codigocuenta":"' .$codigocuenta. '",
                        "descripcion":"' .$descripcuenta. '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function listadoCategoriadeposito() {
        $empresa = $this->session->userdata("tmp_catfac_empresa");
        $registro = $this->Contab_categoria_model->sel_categoriadeposito($empresa);
        $tabla = "";
        foreach ($registro as $row) {
            $codigocuenta = '<div id=\"divasiento'.$row->id.'\" class=\"divcuentadepo \" ><input type=\"text\" class=\"col-md-12 form-control tdvalorcodcuenta upd_cuentadepo autocomplete \" name=\"'.$row->id.'\" id=\"'.$row->id.'\" value=\"'.$row->codigocuenta.'\" data-source=\"'.base_url('contabilidad/contab_categoria/valcuentacodigo_factura?codigo=').'\"  ></div>';
            $descripcuenta = '<div ><label class=\"col-md-12 desc_cuentadepo text-left\" name=\"'.$row->id.'\" id=\"'.$row->id.'\" >'.addslashes($row->descripcion).'</label> </div>';

            $tabla.='{  "id":"' .$row->id. '",
                        "nombre":"' .$row->nombre. '",
                        "tipo":"' .$row->tipo. '",
                        "codigocuenta":"' .$codigocuenta. '",
                        "descripcion":"' .$descripcuenta. '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function actualiza_categoria_formapagocli_cuenta(){
        $id = $this->input->post('id'); 
        $idcuenta = $this->input->post('idcuenta'); 
        $empresa = $this->input->post('empresa'); 
        $this->Contab_categoria_model->upd_categoria_formapagocli_cuenta($id, $empresa, $idcuenta);
        $arr['resu'] = 1;
        print json_encode($arr); 
    }    

    public function actualiza_categoria_formapagopro_cuenta(){
        $id = $this->input->post('id'); 
        $idcuenta = $this->input->post('idcuenta'); 
        $empresa = $this->input->post('empresa'); 
        $this->Contab_categoria_model->upd_categoria_formapagopro_cuenta($id, $empresa, $idcuenta);
        $arr['resu'] = 1;
        print json_encode($arr); 
    }    

    public function actualiza_categoria_deposito_cuenta(){
        $id = $this->input->post('id'); 
        $idcuenta = $this->input->post('idcuenta'); 
        $empresa = $this->input->post('empresa'); 
        $this->Contab_categoria_model->upd_categoria_deposito_cuenta($id, $empresa, $idcuenta);
        $arr['resu'] = 1;
        print json_encode($arr); 
    }    

    public function listadoCategoriatarjeta() {
        $empresa = $this->session->userdata("tmp_catfac_empresa");
        $registro = $this->Contab_categoria_model->sel_categoriatipotarjeta($empresa);
        $tabla = "";
        foreach ($registro as $row) {
            $codigocuenta = '<div id=\"divasiento'.$row->id_tarjeta.'\" class=\"divcuentatar \" ><input type=\"text\" class=\"col-md-12 form-control tdvalorcodcuenta upd_cuentatar autocomplete \" name=\"'.$row->id_tarjeta.'\" id=\"'.$row->id_tarjeta.'\" value=\"'.$row->codigocuenta.'\" data-source=\"'.base_url('contabilidad/contab_categoria/valcuentacodigo_factura?codigo=').'\"  ></div>';
            $descripcuenta = '<div ><label class=\"col-md-12 desc_cuentatar text-left\" name=\"'.$row->id_tarjeta.'\" id=\"'.$row->id_tarjeta.'\" >'.addslashes($row->descripcion).'</label> </div>';

            $tabla.='{  "id":"' .$row->id_tarjeta. '",
                        "tarjeta":"' .$row->nombre. '",
                        "codigocuenta":"' .$codigocuenta. '",
                        "descripcion":"' .$descripcuenta. '"
                    },';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
    }

    public function actualiza_categoria_tarjeta_cuenta(){
        $id = $this->input->post('id'); 
        $idcuenta = $this->input->post('idcuenta'); 
        $empresa = $this->input->post('empresa'); 
        $this->Contab_categoria_model->upd_categoria_tarjeta_cuenta($id, $empresa, $idcuenta);
        $arr['resu'] = 1;
        print json_encode($arr); 
    }    
    
    public function elimina_categoria_gasto(){
        $tipo = 10;//$this->session->userdata("tmp_catgen_tipo");
        $id = $this->input->post('id'); 
        $res = $this->Contab_categoria_model->del_categoria_cuenta($id, $tipo);
        $arr['mens'] = $res;
        print json_encode($arr); 
    }    
    
}

?>