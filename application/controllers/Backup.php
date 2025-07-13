<?php
/*------------------------------------------------
  ARCHIVO: Backup.php
  DESCRIPCION: Contiene los métodos relacionados con la Backup.
  FECHA DE CREACIÓN: 24/10/2017
  ------------------------------------------------ */

defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth_library->sess_validate(true);
        $this->auth_library->mssg_get();
    /*    $this->load->Model("almacen_model"); */
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function index() {
        $data["base_url"] = base_url();
        $data["content"] = "backup";
        $this->load->view("layout", $data);
    }

    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function guardar() {
        $hostname = $this->db->hostname;
        $database = $this->db->database;
        $username = $this->db->username;
        $password = $this->db->password;
        $mypath = BASEPATH;
        $pos = strpos($mypath, "\system");
        $mypath = substr($mypath, 0, $pos);
        date_default_timezone_set("America/Guayaquil");
        $mydate = date("dmY_His");
        $tmpfile = $mypath . '\database\backup' . $mydate . '.sql';
        $myCmd = "mysqldump --routines --events --opt --protocol=TCP --user=$username --password=$password --host=$hostname $database > $tmpfile";
        /*$myCmd = "mysqldump --opt --protocol=TCP --user=root --password=123456 --host=192.168.0.115 eqweb2 > testbackup.sql";*/
        /*$myCmd = "testbackup.bat 2>&1";*/

        $result = exec($myCmd,$output,$var);
        /*var_dump($output);
        var_dump($var);*/
        $arr['resu'] = 1;
        print json_encode($arr);
    }


    /* MÉTODO PREDETERMINADO DEL CONTROLADOR */
    public function guardar_1() {
        $hostname = $this->db->hostname;
        $database = $this->db->database;
        $username = $this->db->username;
        $password = $this->db->password;
        $mypath = BASEPATH;
        $pos = strpos($mypath, "\system");
        $mypath = substr($mypath, 0, $pos);
        date_default_timezone_set("America/Guayaquil");
        $mydate = date("dmY_His");
        $tmpfile = $mypath . '\database\backup' . $mydate . '.sql';
        $myCmd = "mysqldump --opt --protocol=TCP --user=$username --password=$password --host=$hostname $database > $tmpfile";
        /*$myCmd = "mysqldump --opt --protocol=TCP --user=root --password=123456 --host=192.168.0.115 eqweb2 > testbackup.sql";*/
        /*$myCmd = "testbackup.bat 2>&1";*/

        $result = exec($myCmd,$output,$var);
        /*var_dump($output);
        var_dump($var);*/
       /* redirect('inicio','refresh');*/
    }
}

?>