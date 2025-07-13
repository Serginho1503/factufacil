<?php
/*------------------------------------------------
  ARCHIVO: Catalogo.php
  DESCRIPCION: CRUD Catalogo.
  FECHA DE CREACIÓN: 19/11/2018 - Autor: Carlos Zambrano 
  ------------------------------------------------ */

  defined('BASEPATH') OR exit('No direct script access allowed');

  class Producto_excel_controller extends CI_Controller {

    public function __construct() {
      parent::__construct();
      $this->load->helper('array');
      $this->auth_library->sess_validate(true);
      $this->auth_library->mssg_get();
      $this->load->helper('url');
      $this->load->Model("producto/Producto_model_excel");
      $this->request = json_decode(file_get_contents('php://input'));
    }

    public function index() {
    //$this->request->
      $data["base_url"] = base_url();
      $data["content"] = "hotel/hotel_index_view";
      $this->load->view("layout", $data);
    }


    public function import_data() {
      $data = [];
    $config = array(
      'upload_path'   => FCPATH.'doc/excel_productos/',
      'allowed_types' => 'xls|csv'
    );
    $this->load->library('upload', $config);
    if ($this->upload->do_upload('file')) {
      $data = $this->upload->data();
      @chmod($data['full_path'], 0777);
      $data['si'] = 'T';
      $repuesta = [];
      $repuesta['si'] = 'T';
      $repuesta['ruta'] = (string)$data['full_path'];
      echo json_encode($repuesta);

      // @unlink($data['full_path']);
    }else{
      echo json_encode('F');
    }
  }

  public function import_data_save()

  {
      $this->load->library('Spreadsheet_Excel_Reader');
      $this->spreadsheet_excel_reader->setOutputEncoding('CP1251');
      $this->spreadsheet_excel_reader->read($this->request->ruta);
      $sheets = $this->spreadsheet_excel_reader->sheets[0];
      error_reporting(0);

      if ($sheets['numRows'] <= 1)
      {
        echo json_encode(['Documento no Tiene Contenido, asegurate ingresar desde la segunda Fila']);//para cuando suba un archivo vacio
      }else{
        $repuesta = $this->Producto_model_excel->guardar_datos_excel($sheets, $this->request->almacen);
          switch ($repuesta) {

              case 'T':
                      echo json_encode('T');
              break;

              case 'F':
                      echo json_encode('F');
              break;
            
            default:
                echo json_encode( $repuesta);
              break;
          }
      }  
    }


}

