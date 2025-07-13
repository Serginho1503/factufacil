<?php

class Producto_model_excel extends CI_Model {

  function __construct() {
      parent::__construct();
  }

  public function guardar_datos_excel($sheets, $almacen){



      $errores = array();
      $e = 0;

            for ($i = 2; $i <= $sheets['numRows']; $i++) {
                if($sheets['cells'][$i][2] == '' && $sheets['cells'][$i][1] == ''){//para que uno de los exista para poder ingresar
                  $errores[$e] = 'Fila: '.$i.' No tiene Código de Barra o Principal, o Código Auxiliar.'; $e += 1;
                }else{
                  if($sheets['cells'][$i][2] == $sheets['cells'][$i][1]){//para que no sea iguales
                    //$errores[$e] = 'Fila: '.$i.' Código de Barra o Principal no puede ser igual con Código Auxiliar.'; $e += 1;
                  }else{
                      if(
                      $this->db->from('producto')
                                                        ->where('pro_codigobarra' ,$sheets['cells'][$i][1])              
                                                       //->where('deleted_at',null)
                                                        ->get()->row()){//validacion para que no se repita pk
                        $errores[$e] = 'Fila: '.$i.' Código de Barra o Principal ya Existe un Registro Anterior.'; $e += 1;
                      }
                      if(
                      $this->db->from('producto')
                                                        ->where('pro_codigoauxiliar' ,$sheets['cells'][$i][2])              
                                                       //->where('deleted_at',null)
                                                        ->get()->row()//consultar el id del producto por cond de aux
                                                        ){//validacion para que no se repita pk
                        $errores[$e] = 'Fila: '.$i.' Código Auxiliar ya Existe un Registro Anterior.'; $e += 1;
                      }
                  }
              }
            }
              if(count($errores) == 0){
                 $this->guardar_datos($sheets , $almacen);
                 return 'T';

             }else{
              return $errores;
             }


           

  }

  public function guardar_datos($sheets , $almacen)
  {
    $parametro = &get_instance();
    $parametro->load->model("Parametros_model");
    $tarifaiva = $parametro->Parametros_model->iva_get()->valor;

    $parametro->load->model("Precio_model");
    $lstporciento = $parametro->Precio_model->lst_porcientoprecioventa();
    $porciento_compraventa = 0;
    if (count($lstporciento) > 0) { $porciento_compraventa = $lstporciento[0]->porciento; }

    $data_excel = array();
    for ($i = 2; $i <= $sheets['numRows']; $i++) {
            $data_excel[$i - 1]['pro_codigobarra']    = $sheets['cells'][$i][1];
            $data_excel[$i - 1]['pro_codigoauxiliar']   = $sheets['cells'][$i][2];
            $data_excel[$i - 1]['pro_nombre'] = $sheets['cells'][$i][3];
            $data_excel[$i - 1]['pro_preciocompra'] = $sheets['cells'][$i][5];
            
            //$data_excel[$i - 1]['pro_precioventa'] = $sheets['cells'][$i][6] ;
            $precio = $sheets['cells'][$i][6];
            if ($precio == '') {$precio = 0;}
            $precio = round($precio / (1 + $tarifaiva), 6);
            if ($porciento_compraventa != 0){
              $precio = $sheets['cells'][$i][5];
              $precio = round($precio * (1 + ($porciento_compraventa / 100)), 6);
            }
            $data_excel[$i - 1]['pro_precioventa'] = $precio;

            $data_excel[$i - 1]['pro_aplicompra'] = 1;
            $data_excel[$i - 1]['pro_apliventa'] = 1;
            $data_excel[$i - 1]['pro_idunidadmedida'] = 1;
            $data_excel[$i - 1]['pro_estatus'] = 'A';
            $data_excel[$i - 1]['pro_esservicio'] = 0;
            $data_excel[$i - 1]['pro_grabaiva'] = 1;
            $data_excel[$i - 1]['habilitavariante'] = 0;
            $data_excel[$i - 1]['productodescontarventa'] = 0;
            $data_excel[$i - 1]['comanda'] = 0;
            $data_excel[$i - 1]['idcla'] = 0;
            $data_excel[$i - 1]['maxitemvariante'] = 0;
            $data_excel[$i - 1]['preparado'] = 0;
            $data_excel[$i - 1]['ingrediente'] = 0;
            $data_excel[$i - 1]['pro_garantia'] = 0;
            $data_excel[$i - 1]['subsidio'] = 0;
            $data_excel[$i - 1]['idcategoriacontable'] = 3;


    }
     //AQUI ME QUEDE
    if($this->db->insert_batch('producto', $data_excel)){


              //para guardar la cantidad en la tabla pivote almapro
            for ($i = 2; $i <= $sheets['numRows']; $i++) {
                $consul_pro = null;
                $id_pro = null;
                    $consul_pro =  $this->db->from('producto')
                                          ->where('pro_codigobarra' ,$sheets['cells'][$i][1])              
                                         //->where('deleted_at',null)
                                          ->get()->row();//consultar el id del producto por cond de barra
                  if(!$consul_pro)
                  {
                    $consul_pro =  $this->db->from('producto')
                                          ->where('pro_codigoauxiliar' ,$sheets['cells'][$i][2])              
                                         //->where('deleted_at',null)
                                          ->get()->row();//consultar el id del producto por cond de aux
                  }
                  $id_prod = $consul_pro->pro_id;//tengo el id

              $this->db->insert('almapro', array(
                 'id_pro' => $id_prod,
                 'id_alm' => $almacen,
                 'existencia' => $sheets['cells'][$i][4]
               ));
            }
    }

    foreach ($lstporciento as $key => $value) {
      $this->db->query("INSERT INTO prepro (pro_id, id_precios, monto)
                          SELECT pro_id, id_precio, 
                                 CASE pc.porciento WHEN 0 THEN 0 ELSE
                                   round(p.pro_preciocompra * (1 + pc.porciento / 100), 6)
                                 END 
                            FROM producto p, 
                                 (SELECT id_precio, porciento FROM precio_compraventa
                                    WHERE id_precio > 0) as pc
                            WHERE NOT EXISTS (SELECT * FROM prepro pp
                                                WHERE pp.pro_id = p.pro_id AND pp.id_precios = pc.id_precio)");
    }
  }



}

