<?php
/*------------------------------------------------
  ARCHIVO: Mascota.php
  DESCRIPCION: Contiene los métodos relacionados con la Mascota.
  FECHA DE CREACIÓN: 06/08/2018
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/Fpdf.php');

class Mascota extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
        $this->load->Model("petshop/Mascota_model");
        $this->load->Model("Sucursal_model");
        $this->load->Model("Empresa_model");

        $this->request = json_decode(file_get_contents('php://input'));
    }

    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "petshop/mascota/mascota_listado";
        $this->load->view("layout", $data);
    }

    public function listadoDataMasc(){
        $registro = $this->Mascota_model->lst_mascotas();
        $tabla = "";
        foreach ($registro as $row) {
           $ver = '<div class=\"text-center\"><a href=\"#\" title=\"Editar\" id=\"'.$row->id_mascota.'\" class=\"btn btn-success btn-xs btn-grad edi_masc\"><i class=\"fa fa-pencil-square-o\"></i></a> <a href=\"#\" title=\"Eliminar\" id=\"'.$row->id_mascota.'\" class=\"btn btn-danger btn-xs btn-grad del_masc\"><i class=\"fa fa-trash-o\"></i></a> <a href=\"#\" title=\"Historia Clínica\" id=\"'.$row->id_mascota.'\" class=\"btn btn-info btn-xs btn-grad hist_masc\"><i class=\"fa fa-history\"></i></a></div>';            
            @$caledad = $this->calcular_edad($row->fec_nac);
            $edad = $caledad->format('%Y')." años y ".$caledad->format('%m')." meses";
                    
            $tabla.='{"id":"' . $row->id_mascota . '",
                      "nombre":"' . $row->nombre . '",
                      "raza":"' . $row->raza . '",
                      "edad":"' . $edad . '",
                      "responsable":"' . $row->nom_cliente . '",
                      "telefono":"' . $row->telefonos_cliente . '",
                      "ciudad":"' . $row->ciudad_cliente . '",
                      "accion":"' . $ver . '"},';
        }
        $tabla = substr($tabla, 0, strlen($tabla) - 1);
        echo '{"data":[' . $tabla . ']}';        
    }

    public function calcular_edad($fecha){
        date_default_timezone_set("America/Guayaquil");
        $fecha_nac = new DateTime(date('Y-m-d',strtotime($fecha))); // Creo un objeto DateTime de la fecha ingresada
        $fecha_hoy =  new DateTime(date('Y-m-d',time())); // Creo un objeto DateTime de la fecha de hoy
        $edad = date_diff($fecha_hoy,$fecha_nac); // La funcion ayuda a calcular la diferencia, esto seria un objeto
        return $edad;
    }

    public function add_mascota(){
        $usu = $this->session->userdata("usua");
        //$tipohist = $this->Mascota_model->sel_tipohist();
        //$data["tipohist"] = $tipohist;         
        $data["base_url"] = base_url();
        $data["content"] = "petshop/mascota/mascota_add";
        $this->load->view("layout", $data);        
    }

    public function guarda_mascota(){
        date_default_timezone_set("America/Guayaquil");
        $idmasc = $this->input->post('txt_idmasc');
        $codmasc = $this->input->post('txt_codmasc');
        $nommasc = $this->input->post('txt_nommasc');
        $colmasc = $this->input->post('txt_colmasc');
        $razmasc = $this->input->post('txt_razmasc');
        $sexo = $this->input->post('cmb_sexo');
        $fec = $this->input->post('fecha');
        $fec = str_replace('/', '-', $fec);
        $fecha = date("Y-m-d", strtotime($fec));        
        $car = $this->input->post('txt_car');
        $nomvet = $this->input->post('txt_nomvet');
        $telvet = $this->input->post('txt_telvet');  
        $idcli = $this->input->post('txt_idcli');
        $nro_ident = $this->input->post('txt_nro_ident');       
        $clinom = $this->input->post('txt_clinom');
        $telf = $this->input->post('txt_telf');
        $correo = $this->input->post('txt_correo');
        $ciudad = $this->input->post('txt_ciudad');
        $direccion = $this->input->post('txt_direccion');
//        $fotomasc = $this->input->post('fotomasc');

        $cedcli= "";
        if (isset($_POST['fotocli']) && $_POST['fotocli'] == ''){ $imgx = ''; }
        else{ 
          $logo_name= $_FILES["fotocli"]["name"];
          if ($logo_name == NULL || $logo_name == ""){ $imgx = ''; } 
          else { 
            $logo_size= $_FILES["fotocli"]["size"];
            $logo_type= $_FILES["fotocli"]["type"];
            $logo_temporal= $_FILES["fotocli"]["tmp_name"];     
            $split_logo = pathinfo($logo_name);
            $cedcli = $nro_ident.".".$split_logo['extension'];
          }        
        }


        $codemasc = "";
        if (isset($_POST['fotomasc']) && $_POST['fotomasc'] == ''){
           $img = '';
        }
        else{
          $logo_name= $_FILES["fotomasc"]["name"];
          if ($logo_name == NULL || $logo_name == ""){ $img = ''; } 
          else { 
            $logo_size= $_FILES["fotomasc"]["size"];
            $logo_type= $_FILES["fotomasc"]["type"];
            $logo_temporal= $_FILES["fotomasc"]["tmp_name"];     
            $split_logo = pathinfo($logo_name);
            $codemasc = $codmasc.".".$split_logo['extension'];
          }        
        }  

        
        if($idmasc != 0){
          $this->Mascota_model->upd_mascotas($idmasc, $codmasc, $nommasc, $colmasc, $razmasc, $sexo, $fecha, $car, $idcli, $nomvet, $telvet, $codemasc);
        }else{
          $this->Mascota_model->ins_mascotas($codmasc, $nommasc, $colmasc, $razmasc, $sexo, $fecha, $car, $idcli, $nomvet, $telvet, $codemasc);
        }  
        $this->Mascota_model->upd_fotocli($nro_ident, $cedcli);             
  

        if (isset($_POST['fotomasc']) && $_POST['fotomasc'] == ''){ $img = ''; }
        else{ $logo_name= $_FILES["fotomasc"]["name"];
              if ($logo_name == NULL || $logo_name == ""){
                  $img = '';
              } else { 
                  $logo_size= $_FILES["fotomasc"]["size"];
                  $logo_type= $_FILES["fotomasc"]["type"];
                  $logo_temporal= $_FILES["fotomasc"]["tmp_name"];     
                  $split_logo = pathinfo($logo_name);
                  $split_temporal = pathinfo($logo_temporal);
                  $img = $split_temporal['filename'].".".$split_logo['extension'];
                  $codemasc = $codmasc.".".$split_logo['extension'];
                  $file_name = FCPATH.'/public/img/mascota/'.$codemasc;
                  $f1= fopen($logo_temporal,"rb");
                  $logo_reconvertida = fread($f1, $logo_size);
                  fclose($f1);
                  $file = fopen($file_name , 'w') or die("X_x");
                  fwrite($file, $logo_reconvertida);
                  fclose($file);
              }        
        }  

        if (isset($_POST['fotocli']) && $_POST['fotocli'] == ''){ $img = ''; }
        else{ $logo_name= $_FILES["fotocli"]["name"];
              if ($logo_name == NULL || $logo_name == ""){
                  $img = '';
              } else { 
                  $logo_size= $_FILES["fotocli"]["size"];
                  $logo_type= $_FILES["fotocli"]["type"];
                  $logo_temporal= $_FILES["fotocli"]["tmp_name"];     
                  $split_logo = pathinfo($logo_name);
                  $split_temporal = pathinfo($logo_temporal);
                  $img = $split_temporal['filename'].".".$split_logo['extension'];
                  $cedcli = $nro_ident.".".$split_logo['extension'];
                  $file_name = FCPATH.'/public/img/cliente/'.$cedcli;
                  $f1= fopen($logo_temporal,"rb");
                  $logo_reconvertida = fread($f1, $logo_size);
                  fclose($f1);
                  $file = fopen($file_name , 'w') or die("X_x");
                  fwrite($file, $logo_reconvertida);
                  fclose($file);
              }        
        }  







        header("location: " . base_url() . "petshop/mascota");

    }

    public function busca_codigo(){
        $cod = $this->input->post('cod');
        $res = $this->Mascota_model->busca_codigo($cod);
        print json_encode($res);
    }

    public function tmp_masc() {
        $this->session->unset_userdata("tmp_masc"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_masc", NULL);
        if($id != NULL) { $this->session->set_userdata("tmp_masc", $id); } 
        else { $this->session->set_userdata("tmp_masc", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function sel_mascota(){
        $idmasc = $this->session->userdata("tmp_masc");
        $data["base_url"] = base_url();
        $masc = $this->Mascota_model->sel_mascotas($idmasc);
        $data["masc"] = $masc;        
        $idcli = $masc->id_cliente;
        $cli = $this->Mascota_model->sel_cliente($idcli);
        $data["cli"] = $cli;
        //$lsttipohist = $this->Mascota_model->lst_reghist($idmasc);
        //$data["lsttipohist"] = $lsttipohist;    
        $data["content"] = "petshop/mascota/mascota_add";
        $this->load->view("layout", $data);           
        
    }

    public function upd_petcliente(){
      $idc = $this->input->post("idc");  
      $ced = $this->input->post("ced");
      $nom = $this->input->post("nom");
      $fecli = $this->input->post("fcli");
      $fecli = str_replace('/', '-', $fecli);
      $fechacli = date("Y-m-d", strtotime($fecli)); 
      $tel = $this->input->post("tel");
      $cor = $this->input->post("cor");
      $ciu = $this->input->post("ciu");
      $dir = $this->input->post("dir");
      $this->Mascota_model->upd_petcliente($idc, $ced, $nom, $fechacli, $tel, $cor, $ciu, $dir);
      print json_encode($idc);
    }

    public function del_mascota(){
      $idmasc = $this->input->post("id");
      $this->Mascota_model->del_mascota($idmasc);
      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function add_maschist(){
        $tipohist = $this->Mascota_model->sel_tipohist();
        $data["tipohist"] = $tipohist;         
        $data["base_url"] = base_url();
        $this->load->view("petshop/mascota/maschist_add", $data);        
    }

    public function tmp_maschist() {
        $this->session->unset_userdata("tmp_maschist"); 
        $idm = $this->input->post("idm");
        $data["idm"] = $idm;
        $idr = $this->input->post("idr");
        $data["idr"] = $idr;
        $this->session->set_userdata("tmp_maschist", NULL);
        if($data != NULL) { $this->session->set_userdata("tmp_maschist", $data); } 
        else { $this->session->set_userdata("tmp_maschist", NULL); }
        $arr['resu'] = 1;
        print json_encode($arr);
    }

    public function edit_maschist(){
      $reg = $this->session->userdata("tmp_maschist");
      $idmasc = $reg['idm'];
      $idreghist = $reg['idr'];
      $tipohist = $this->Mascota_model->sel_tipohist();
      $data["tipohist"] = $tipohist; 
      $mthist = $this->Mascota_model->reghist_id($idmasc, $idreghist);
      $data["mthist"] = $mthist; 
      $data["base_url"] = base_url();
      $this->load->view("petshop/mascota/maschist_add", $data);        
    }

    public function guarda_maschist(){
      $idmasc = $this->session->userdata("tmp_masc");
      $idmaschist = $this->input->post('txt_idmaschist');
      $tipohist = $this->input->post('cmb_tipohist');
      $nomhist = $this->input->post('txt_nomhist');
      $thist = $this->input->post('txt_thist');

      if($idmaschist != 0){
        $this->Mascota_model->upd_reghist($idmaschist, $idmasc, $tipohist, $nomhist, $thist);        
      }else{
        $this->Mascota_model->add_reghist($idmasc, $tipohist, $nomhist, $thist);
      }

      $arr['resu'] = 1;
      print json_encode($arr);
    }

    public function actualiza_maschist(){
      $idmasc = $this->session->userdata("tmp_masc");
      $lsttipohist = $this->Mascota_model->lst_reghist($idmasc);
      $data["lsttipohist"] = $lsttipohist; 
      $data["base_url"] = base_url();
      $this->load->view("petshop/mascota/maschist_lst", $data);                             
    }

    public function del_maschist(){
      $idmasc = $this->input->post("idm");
      $idreghist = $this->input->post("idr");
      $this->Mascota_model->reghist_del($idmasc, $idreghist);
      $arr['resu'] = 1;
      print json_encode($arr);
    }


    //funciones de carlos desarrolladas
    public function all_mascotas($id, $pagina)
    {
      $datos = [];
      if($id != 0){
        //buscar por clientes
        $datos = $this->Mascota_model->clientes_mascotas($id);
      }
      if($id == 0){
        //buscar todas las mascotas activas
        if($pagina < $this->pag_env){
          $pagina = 0;
        }
        $datos = $this->Mascota_model->all_mascotas( $this->pag_env, $pagina);

       //$datos = $this->Mascota_model->all_mascotas(10,0);
      }
      echo json_encode($datos);
    }

    public function like_mascota($val)
    {
      $datos = $this->Mascota_model->like_mascota_all($val, $this->pag_env);
      echo json_encode($datos);    
    }

    public function sel_mascota_id($idmascota)
    {
      $datos = $this->Mascota_model->sel_mascota_id($idmascota);
      echo json_encode($datos);    
    }

    public function lst_historia_mascota($idmascota)
    {
      $datos = $this->Mascota_model->lst_mascota_historia($idmascota);
      echo json_encode($datos);    
    }

    public function historiaclinica_guardar(){
      $historia = $this->request->historia;
      $id = $historia->id;
      if (($id == '') || ($id == '0')){
        $id = $this->Mascota_model->ins_mascota_historia($historia);       
      }
      else{
        $this->Mascota_model->upd_mascota_historia($historia);
      }  

      echo json_encode($id);
    }

    public function historiaclinica_eliminar(){
      $id = $this->request->id;
      $this->Mascota_model->del_mascota_historia($id);
      echo json_encode($id);
    }

    public function tmp_mascota_historia() {
        $this->session->unset_userdata("tmp_masc_hist_id"); 
        $id = $this->input->post("id");
        $this->session->set_userdata("tmp_masc_hist_id", NULL);
        if($id != NULL) { $this->session->set_userdata("tmp_masc_hist_id", $id); } 
        else { $this->session->set_userdata("tmp_masc_hist_id", NULL); }
        $arr['resu'] = $id;
        print json_encode($arr);
    }

    public function print_pdf_historia(){

        $id = $this->session->userdata("tmp_masc_hist_id");

        $registro = $this->Mascota_model->sel_mascota_historia($id);
        if ($registro == NULL) return;
        $sucursal = $this->Sucursal_model->sel_suc_id($registro->id_sucursal);

        $cfg = $this->Empresa_model->sel_emp_id($sucursal->id_empresa);

        $pdf = new FPDF();
        $pdf->AliasNbPages();

        $pdf->AddPage();

        $pdf->SetXY(10,1);
        $pdf->SetFont('Arial','B',12);

        $file_name = "mascotahistoria.jpg";
        if ($sucursal->logo_sucursal){    
          $pic = base64_decode($sucursal->logo_sucursal);
          imagejpeg(imagecreatefromstring ( $pic ), $file_name);

          $pdf->Image($file_name,10,1,40,20);
        }

        $pdf->SetXY(50,1);
        $pdf->Cell(30,10,utf8_decode($sucursal->nom_sucursal),0,0,'L');

        $coord_y = 7;
        $pdf->SetXY(50,$coord_y);
        $pdf->MultiCell(100,7,$sucursal->dir_sucursal,0,'L',false);
       // $pdf->Cell(30,10,utf8_decode($sucursal->dir_sucursal),0,0,'C');

        $coord_y += 6;
        $pdf->SetXY(50,$coord_y);
        $pdf->Cell(30,10,utf8_decode($sucursal->telf_sucursal),0,0,'L');
 
        $pdf->SetFont('Arial','B',11);

        $coord_y = 20;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'REGISTRO HISTORIA CLINICA');

        $coord_y = $pdf->GetY();
        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $fecha = str_replace('-', '/', $registro->fecha); 
        $fecha = date("d/m/Y", strtotime($fecha));
        $pdf->Cell(20,10, "FECHA: " . $fecha);

        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'CLIENTE: '. $registro->nom_cliente);

        $pdf->SetXY(150,$coord_y);
        $pdf->Cell(20,10,'CI/RUC: ' .$registro->ident_cliente);

        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'DIRECCION: '. $registro->direccion_cliente);

        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'TELEFONO: ' .$registro->telefonos_cliente);

        $coord_y += 10;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'DATOS MASCOTA');

        $coord_y += 5;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(20,10,'CODIGO: ' .$registro->codigo);

        $pdf->SetXY(50,$coord_y);
        $pdf->Cell(20,10,'NOMBRE: ' .$registro->nombre);

        $pdf->SetXY(110,$coord_y);
        $pdf->Cell(20,10,'RAZA: ' .$registro->raza);

        $pdf->SetXY(150,$coord_y);
        $pdf->Cell(20,10,'COLOR: ' .$registro->color);

        $coord_y = $pdf->GetY();
        $coord_y += 10;
        $pdf->SetXY(10,$coord_y);
        $pdf->Cell(30,10,'OBSERVACIONES:',0,0,'L');
        $pdf->Ln();
        $pdf->MultiCell(0,7,$registro->observaciones,0,'L',false);

        $pdf->Output('HistoriaClinica','I');
    }

}

