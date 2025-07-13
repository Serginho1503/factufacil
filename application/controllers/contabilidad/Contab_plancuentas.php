<?php

/*------------------------------------------------
  ARCHIVO: Contab_PlanCuentas.php
  DESCRIPCION: Contiene los métodos relacionados con Plan de Cuentas.
  
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');


class Contab_plancuentas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("contabilidad/Contab_plancuentas_model");
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $idusu = $this->session->userdata("sess_id");
        $grupo = $this->Contab_plancuentas_model->lst_grupocuentas();
        $empresa = $this->Contab_plancuentas_model->lst_nivelempresa();
        $naturaleza = $this->Contab_plancuentas_model->lst_naturaleza();

        $data["grupo"] = $grupo;
        $data["empresa"] = $empresa;
        $data["naturaleza"] = $naturaleza;
        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_plancuentas";
        $this->load->view("layout", $data);
    }

    public function lst_planraiz() {
        $id = $this->input->post('id'); 
        $data = array();
        
        $data[] = array(
            "id" => "0",  
            "text" => 'Plan de Cuentas', 
            "icon" => "fa fa-folder icon-lg",
            "children" => true,
            "state" => array("opened" => true, "selected" => true)
        );
        header('Content-type: text/json');
        header('Content-type: application/json');
        print json_encode($data);
    }

    public function lst_cuentassubordinadas() {
        $id = $this->input->post('id'); 
        /*$id = substr($strid,1);*/
        /*var_dump($id);*/
        $data = array();
        
        $registro = $this->Contab_plancuentas_model->lst_cuentassubordinadas($id);
        foreach ($registro as $key => $value) {
            $data[] = array(
                "id" => $value->id,  
                "text" => $value->codigocuenta . ' ' . $value->descripcion, 
                "icon" => "fa fa-folder icon-lg",
                "children" => ($value->esmovimiento == 0),
                "state" => array("opened" => false, "selected" => false)
            );
        }
        header('Content-type: text/json');
        header('Content-type: application/json');
        print json_encode($data);
    }

    public function get_cuenta(){
        $id = $this->input->post('id');
        $resu = $this->Contab_plancuentas_model->sel_cuenta_id($id);
        $arr['resu'] = $resu;
        print json_encode($arr);
    }

    public function existe_cuenta(){
        $id = $this->input->post('id');
        $codigocuenta = $this->input->post('codigocuenta');
        $idempresa = $this->input->post('idempresa');
        $resu = $this->Contab_plancuentas_model->existe_cuenta($id, $codigocuenta, $idempresa);
        $arr['resu'] = $resu;
        print json_encode($arr);
    }

    public function cuenta_tiene_operaciones(){
        $id = $this->input->post('id');
        $resu = $this->Contab_plancuentas_model->cuenta_tiene_operaciones($id);
        $arr['resu'] = $resu;
        print json_encode($arr);
    }

    public function guardar(){
        $id = $this->input->post('id'); 
        $idempresa = $this->input->post('idempresa');
        $idcuentasuperior = $this->input->post('idcuentasuperior');
        $idgrupocuenta = $this->input->post('idgrupocuenta');
        $codigonivel = $this->input->post('codigonivel');
        $codigocuenta = $this->input->post('codigocuenta');
        $descripcion = $this->input->post('descripcion');
        $nivel = $this->input->post('nivel');
        $naturaleza = $this->input->post('naturaleza');
        $activo = $this->input->post('activo');
        if($activo == 'on'){ $activo = 1; } else { $activo = 0; }
        if($id != 0){
            $this->Contab_plancuentas_model->upd_cuenta($id, $idempresa, $idcuentasuperior, $idgrupocuenta, $codigonivel, $codigocuenta, $descripcion, $nivel, $naturaleza, $activo);
        } else {
            $id = $this->Contab_plancuentas_model->add_cuenta($idempresa, $idcuentasuperior, $idgrupocuenta, $codigonivel, $codigocuenta, $descripcion, $nivel, $naturaleza, $activo);
        }
        $arr['resu'] = $id;
        print json_encode($arr); 
    }

    public function tienesaldo_cuenta(){
        $id = $this->input->post('id');
        $resu = $this->Contab_plancuentas_model->tienesaldo_cuenta($id);
        $arr['resu'] = $resu;
        print json_encode($arr);
    }

    public function del_cuenta(){
        $id = $this->input->post('id'); 
        $resu = $this->Contab_plancuentas_model->del_cuenta($id);
        $arr['mens'] = $id;
        print json_encode($arr); 
    }

    public function importar_cuentas() {
        $this->session->unset_userdata("tmp_impcta_file"); 
        $this->session->set_userdata("tmp_impcta_file", NULL);
        $data["base_url"] = base_url();
        $data["content"] = "contabilidad/contab_plancuentas_importar";
        $this->load->view("layout", $data);
    }

    public function cargar_xlscuentas() {
        $this->session->unset_userdata("tmp_impcta_file"); 
        $this->session->set_userdata("tmp_impcta_file", NULL);
        $res = "";
 
        if ( 0 < $_FILES['file']['error'] ) {
            echo 'Error: ' . $_FILES['file']['error'] . '<br>';
        }
        else {
            $tmpfile = $_FILES['file']['name'];
            $this->session->set_userdata("tmp_impcta_file", $tmpfile);
            $file_name = FCPATH.'public/upload/'.$tmpfile;
            move_uploaded_file($_FILES['file']['tmp_name'], $file_name);
        }

        $res = $tmpfile;
        print json_encode($res); 
    }

    public function listadocuentasimportar(){
        $tmpfile = $this->session->userdata("tmp_impcta_file");
        $tabla = "";

        if ($tmpfile != ''){
            $tmpfname = FCPATH.'public/upload/'.$tmpfile;
            if (file_exists($tmpfname)){
                $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
                $excelObj = $excelReader->load($tmpfname);
                $worksheet = $excelObj->getSheet(0);
                $lastRow = $worksheet->getHighestRow();
                
                for ($row = 2; $row <= $lastRow; $row++) {
                    $tabla.='{  "cuenta":"' .addslashes($worksheet->getCell('A'.$row)->getValue()). '",
                                "descripcion":"' .addslashes($worksheet->getCell('B'.$row)->getValue()). '"
                            },';
                }
            }    
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';
        
    }

    public function guardar_cuentas_importar(){
        $tmpfile = $this->session->userdata("tmp_impcta_file");
        $res = 0;
        if ($tmpfile != ''){
            $tmpfname = FCPATH.'public/upload/'.$tmpfile;
            if (file_exists($tmpfname)){
                $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
                $excelObj = $excelReader->load($tmpfname);
                $worksheet = $excelObj->getSheet(0);
                $lastRow = $worksheet->getHighestRow();

                for ($row = 2; $row <= $lastRow; $row++) {
                    $tmpcuenta = addslashes($worksheet->getCell('A'.$row)->getValue());
                    $tmpdescrip = addslashes($worksheet->getCell('B'.$row)->getValue());
                    if ($tmpcuenta != ""){
                        $resu = $this->Contab_plancuentas_model->guardar_cuentas_importar($tmpcuenta, $tmpdescrip);
                        //var_dump($resu);
                        if ($resu != 0){
                            $res++;
                        }
                    }
                }
            }    
        }
       
        print json_encode($res); 
    }













    public function update_cuentasimportar00(){
		$tmpfname = "D:\Trabajo\Darwin\TareasContabilidad.xlsx";
		$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
		$excelObj = $excelReader->load($tmpfname);
		$worksheet = $excelObj->getSheet(0);
		$lastRow = $worksheet->getHighestRow();
		
		echo "<table>";
		for ($row = 1; $row <= $lastRow; $row++) {
			 echo "<tr><td>";
			 echo $worksheet->getCell('A'.$row)->getValue();
			 echo "</td><td>";
			 echo $worksheet->getCell('B'.$row)->getValue();
			 echo "</td><tr>";
		}
		echo "</table>";	        
    }


    /* Ejemplo Tree with JSON  */
    public function getTreeNodes(){
        $id = $this->input->post('id');
        $arr['id'] = 1;
        $arr['text'] = "Prueba";
        $arr['children'] = true;
        print json_encode($arr);

    }


}

?>