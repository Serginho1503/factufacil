<?php
/* ------------------------------------------------
  ARCHIVO: Mascota_model.php
  DESCRIPCION: Manejo de consultas y excepciones referentes al Mascota.
  FECHA DE CREACIÓN: 06/08/2018
------------------------------------------------ */
class Mascota_model extends CI_Model {

  function __construct() { parent::__construct(); }

  public function lst_mascotas(){
    $sql = $this->db->query(" SELECT m.id_mascota, m.nombre, m.raza,m.fec_nac,
                                     c.nom_cliente, c.telefonos_cliente, c.ciudad_cliente 
                              FROM pet_mascotas m
                              INNER JOIN clientes c ON c.id_cliente = m.id_cliente");
    $res = $sql->result();
    return $res;
  }
  
  public function busca_codigo($cod){
    $sql = $this->db->query("SELECT COUNT(*) AS val FROM pet_mascotas WHERE codigo = '$cod'");
    $resu = $sql->result();
    $val = $resu[0]->val;
    if($val > 0 ){ $res = 1; }
    else{ $res = 0; }
    return $res;       
  }

  public function sel_mascotas($idmasc){
    $sql = $this->db->query(" SELECT id_mascota, id_cliente, nombre, codigo, raza, color, sexo, fec_nac, caracteristicas, veterinario, telf_veterinario, foto_mascota
                              FROM pet_mascotas WHERE id_mascota = $idmasc");
    $res = $sql->result();
    return $res[0];
  }

  public function sel_cliente($idcli){
    $sql = $this->db->query(" SELECT id_cliente, ident_cliente, nom_cliente, telefonos_cliente, correo_cliente, ciudad_cliente, direccion_cliente, fecha_nac, foto_cliente 
                              FROM clientes WHERE id_cliente = $idcli");
    $res = $sql->result();
    return $res[0];    
  }

  public function upd_mascotas($idmasc, $codmasc, $nommasc, $colmasc, $razmasc, $sexo, $fecha, $car, $idcli, $nomvet, $telvet, $codemasc){
  /*  $sql = $this->db->query("SELECT id_cliente FROM clientes WHERE ident_cliente = '$nro_ident' ");
    $res = $sql->result();
    $idcli = $res[0]->id_cliente;
*/
    $foto = ",foto_mascota = '$codemasc'"; 
//    if($codemasc != ""){ $foto = ",foto_mascota = '$codemasc'"; }else{ $foto = ""; }

    $this->db->query("UPDATE pet_mascotas SET id_cliente = $idcli, 
                                          nombre = '$nommasc', 
                                          codigo = '$codmasc', 
                                          raza = '$razmasc', 
                                          color  = '$colmasc', 
                                          sexo  = '$sexo', 
                                          fec_nac  = '$fecha', 
                                          caracteristicas  = '$car',
                                          veterinario = '$nomvet',
                                          telf_veterinario = '$telvet'
                                          $foto
                                      WHERE id_mascota = $idmasc");
  }

  public function ins_mascotas($codmasc, $nommasc, $colmasc, $razmasc, $sexo, $fecha, $car, $idcli, $nomvet, $telvet, $codemasc){
    $this->db->query("INSERT INTO pet_mascotas (id_cliente, nombre, codigo, raza, color, sexo, fec_nac, caracteristicas, veterinario, telf_veterinario, foto_mascota) 
                                    VALUES ($idcli, '$nommasc', '$codmasc', '$razmasc', '$colmasc', '$sexo', '$fecha', '$car', '$nomvet', '$telvet', '$codemasc') ");
    $sqlcli = $this->db->query("SELECT max(id_mascota) AS idmas FROM pet_mascotas");
    $resu = $sqlcli->result();
    return $resu[0]->idmas;
  }

  public function del_mascota($idmasc){
    $this->db->delete('pet_mascotas', array('id_mascota' => $idmasc)); 
  }

  public function upd_petcliente($idc, $ced, $nom, $fechacli, $tel, $cor, $ciu, $dir){
        $sqlcli = $this->db->query("SELECT id_cliente, COUNT(*) AS nrocli FROM clientes WHERE ident_cliente = '$ced' ");
        $resucli = $sqlcli->result();
        $valcli = $resucli[0]->nrocli;

        if($valcli > 0){  
          $idc = $resucli[0]->id_cliente;      
          if ($idc == 1){
            $tel = '';
            $cor = '';
            $dir = '';
            $ciu = '';
          }
      
          $this->db->query(" UPDATE clientes SET tipo_ident_cliente = 'C', 
                                                      ident_cliente = '$ced',
                                                        nom_cliente = '$nom', 
                                                          fecha_nac = '$fechacli',
                                                  telefonos_cliente = '$tel',
                                                     correo_cliente = '$cor', 
                                                     ciudad_cliente = '$ciu',
                                                  direccion_cliente = '$dir'
                                                   WHERE ident_cliente = '$ced'");

        }else{
          if($cor != NULL || $cor = ""){}else{$cor = " ";} 
          if($tel != NULL || $tel = ""){}else{$tel = " ";}
          if($dir != NULL || $dir = ""){}else{$dir = " ";}
          if($ciu != NULL || $ciu = ""){}else{$ciu = " ";}
          
          $sql_addc = $this->db->query("INSERT INTO clientes (tipo_ident_cliente, nom_cliente, ident_cliente) 
                                                      VALUES ('C', '$nom', '$ced')");
          $this->upd_petcliente($idc, $ced, $nom, $fechacli, $tel, $cor, $ciu, $dir);
        } 

      
    }

  public function upd_fotocli($nro_ident, $cedcli){
    $this->db->query("UPDATE clientes SET foto_cliente = '$cedcli'  WHERE  ident_cliente = '$nro_ident'");
  }

  public function sel_tipohist(){
    $sql = $this->db->query("SELECT id_tipohist, desc_tipohist FROM pet_tipohist");
    $res = $sql->result();
    return $res;
  }

  public function lst_reghist($idmasc){
    $sqlc = $this->db->query("SELECT COUNT(*) AS nro FROM pet_mascota_registro WHERE id_masc = $idmasc"); 
    $resc = $sqlc->result();
    $nro = $resc[0]->nro;

    if($nro > 0){
      $sql = $this->db->query(" SELECT pmr.idreghist, pth.desc_tipohist, pmr.nom_tipohist, pmr.desc_reghist 
                                FROM pet_mascota_registro pmr
                                INNER JOIN pet_tipohist pth ON pth.id_tipohist = pmr.id_tipohist
                                WHERE pmr.id_masc = $idmasc");
      $res = $sql->result();
      return $res;
    }else{
       return NULL;
    }
  }

  public function reghist_id($idmasc, $idreghist){
      $sql = $this->db->query(" SELECT idreghist, id_tipohist, nom_tipohist, desc_reghist 
                                FROM pet_mascota_registro   
                                WHERE id_masc = $idmasc AND idreghist = $idreghist ");
      $res = $sql->result();
      return $res[0];    
  }

  public function add_reghist($idmasc, $tipohist, $nomhist, $thist){
    $this->db->query("INSERT INTO pet_mascota_registro (id_masc, id_tipohist, nom_tipohist, desc_reghist) VALUES ($idmasc, $tipohist, '$nomhist', '$thist')");
  }

  public function upd_reghist($idmaschist, $idmasc, $tipohist, $nomhist, $thist){
    $this->db->query("UPDATE pet_mascota_registro SET id_tipohist = $tipohist, 
                                                     nom_tipohist = '$nomhist', 
                                                     desc_reghist = '$thist'
                                                    WHERE id_masc = $idmasc 
                                                    AND idreghist = $idmaschist ");
  }

  public function reghist_del($idmasc, $idreghist){
    $this->db->query("DELETE FROM pet_mascota_registro WHERE id_masc = $idmasc AND idreghist = $idreghist");
  }

  public function lst_mascotascliente($idcliente){
    $sql = $this->db->query("SELECT id_mascota, codigo, nombre, raza, fec_nac, sexo, color,
                                    veterinario, telf_veterinario                                     
                              FROM pet_mascotas 
                              WHERE id_cliente = $idcliente");
    $res = $sql->result();
    return $res;
  }

  public function lst_mascotasexo(){
    $sql = $this->db->query("SELECT 'H' as codigo, 'HEMBRA' as sexo                                     
                              UNION
                             SELECT 'M' as codigo, 'MACHO' as sexo");
    $res = $sql->result();
    return $res;
  }

  //consulta echas por carlos 
  public function clientes_mascotas($id)
  {
     $sql = $this->db
            //->select('id_mascota, nombre')
            ->where('deleted_at',null)
            ->where('id_cliente', $id)
            ->from('pet_mascotas')
            ->get()
            ->result();

    return $sql;
  }


  public function all_mascotas($limit, $offset)
  {
     $sql = $this->db
            //->select('id_mascota, nombre')
            ->where('deleted_at',null)
            ->from('pet_mascotas')
            ->limit($limit, $offset)
            ->get()
            ->result();

    return $sql;
  }

  public function like_mascota_all($val, $limit)
  {
     $sql = $this->db
            ->select('nombre, id_mascota, codigo ')
            ->from('pet_mascotas')
            ->limit($limit)
            ->like('nombre', $val)
            ->get()
            ->result();

    return $sql;
  }

  public function lst_mascota_historia($idmascota)
  {
     $sql = $this->db
            ->select('pet_mascota_historiaclinica.id, pet_mascotas.id_mascota, nombre, pet_mascotas.codigo, 
                      raza, sexo, pet_mascotas.fec_nac, fecha, observaciones, id_sucursal, 
                      nom_cliente, ident_cliente')
            ->where('pet_mascotas.id_mascota', $idmascota)
            ->from('pet_mascotas')
            ->join('pet_mascota_historiaclinica', 'pet_mascota_historiaclinica.id_mascota = pet_mascotas.id_mascota')
            ->join('clientes', 'clientes.id_cliente = pet_mascotas.id_cliente')
            ->get()
            ->result();

    return $sql;
  }

  public function sel_mascota_historia($id)
  {
    $sql = $this->db
            ->select('pet_mascotas.id_mascota, nombre, pet_mascotas.codigo, color,
                      raza, sexo, pet_mascotas.fec_nac, fecha, observaciones, id_sucursal, 
                      nom_cliente, ident_cliente, direccion_cliente, telefonos_cliente')
            ->where('pet_mascota_historiaclinica.id', $id)
            ->from('pet_mascotas')
            ->join('pet_mascota_historiaclinica', 'pet_mascota_historiaclinica.id_mascota = pet_mascotas.id_mascota')
            ->join('clientes', 'clientes.id_cliente = pet_mascotas.id_cliente')
            ->get()
            ->result();
    if (count($sql) > 0)        
      return $sql[0];
    else
      return null;
  }

  public function sel_mascota_id($idmascota){
    $sql = $this->db
            ->select('pet_mascotas.id_mascota, nombre, pet_mascotas.codigo, raza, sexo, pet_mascotas.fec_nac, 
                      nom_cliente, ident_cliente')
            ->where('pet_mascotas.id_mascota', $idmascota)
            ->from('pet_mascotas')
            ->join('clientes', 'clientes.id_cliente = pet_mascotas.id_cliente')
            ->get()
            ->result();
    if (count($sql) > 0)        
      return $sql[0];
    else
      return null;
  }


  public function ins_mascota_historia($historia){
    $sql = $this->db
            ->select('id')
            ->from('pet_mascota_historiaclinica')
            ->where(array('id_mascota' => $historia->id_mascota, 
                          'id_sucursal' => $historia->id_sucursal, 
                          'fecha' => $historia->fecha
                         )
                   )
            ->get()
            ->result();
    if (count($sql) > 0){
      $id = $sql[0]->id;
      $historia->id = $id;
      return $this->upd_mascota_historia($historia);
    }        
    else{
      $this->db->insert('pet_mascota_historiaclinica', array(
                                  'id_mascota'=> $historia->id_mascota,
                                  'id_sucursal'=> $historia->id_sucursal,
                                  'fecha'=> $historia->fecha,
                                  'observaciones'=> $historia->observaciones
                                ));

      $sql = $this->db
              ->select('max(id) as id')
              ->from('pet_mascota_historiaclinica')
              ->get()
              ->result();
      if (count($sql) > 0)        
        return $sql[0]->id;
      else
        return 0;
    }  
  }

  public function upd_mascota_historia($historia){
    $this->db->update('pet_mascota_historiaclinica', 
                      array('id_sucursal'=> $historia->id_sucursal,
                            'observaciones' => $historia->observaciones
                           ), 
                      array('id' => $historia->id));
    return $historia->id;
  }

  public function del_mascota_historia($id){
    $this->db->delete('pet_mascota_historiaclinica', array('id' => $id)); 
  }

}