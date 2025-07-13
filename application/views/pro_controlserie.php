<?php
/* ------------------------------------------------
  ARCHIVO: garantiadevolucion.php
  DESCRIPCION: Contiene la vista principal del módulo de devolucion por garantia.
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Devoluciones de Productos en Garantía'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/css/jquery.timepicker.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/css/bootstrap-datepicker.standalone.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/lib/pikaday.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/lib/jquery.ptTimeSelect.css" />

<style type="text/css">
  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 
  .dropdown-menu > li > a {
    color: #fff;
  } 

  .autocomplete-jquery-results{
      border:1px solid silver;
      float:right;
      margin-top:2px;
      position:absolute;
      display: none;
      z-index: 999;
  }

  /*Esta clase se activa cuando el usuario se mueve por las sugerencias*/
  .autocomplete-jquery-mark{
      color:black;
      background-color: #E0F0FF !important;
  }

  /* Cada sugerencia va a llevar esta clase, por lo tanto tomara el estilo siguiente */
  .autocomplete-jquery-item{
      border-bottom: 1px solid lightgray;
      display: block;
      height: 25px;
      padding-top: 5px;
      text-decoration: none;     
      padding-left: 3px;  
      background-color: white;
  }
  .autocomplete-jquery-results{
       box-shadow: 1px 1px 3px black;
  }
  /* Al pasar por encima de las sugerencias*/
  .autocomplete-jquery-item:hover{
      background-color: #E0F0FF;
      color:black;
  }

</style>

<script src="<?php print $base_url; ?>assets/plugins/datepair/js/jquery.min.js"></script>

<script type='text/javascript' language='javascript'>

  var jq = $.noConflict();
  jq(document).ready(function () {

    $(document).on('click', '#txt_producto', function(){
      $('.autocomplete').autocomplete();
    });

    $(document).on('click', '.form-control', function(){  
      $('.autocomplete-jquery-results').each(function(index, el) {
        this.remove();
      });
    });

    /* MUESTRA DATOS DEL CLIENTE */
    $(document).on('click', '.autocomplete-jquery-item', function(){  
      var nom = $(this).text(); 
      autocomplete_cliente(nom);
/*      if (autoparent == "cliente"){
        autocomplete_cliente(nom);
      } else {
        autocomplete_serie(nom);
      }*/
    });

    function autocomplete_cliente(nom){
      if (nom === ""){
        alert("Debe ingresar un nombre");
        return false;
      }
      controlseriemodel.producto_nombre = nom;
    }  


  });

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-list-alt"></i> Control de Serie de Productos
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content" id="app_control_serie">
    <div class="row" >
      <div class="col-md-12">
        <div class="box box-danger">
          <div style="padding-top: 5px; padding-bottom: 0px;" class="box-header with-border">

            <div class="form-group col-md-6" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
              <label for="" class="col-sm-2 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Producto</label>
              <div id="mennom" class="col-sm-10 autocomplete" style="padding-right: 0px;">
                <input type="text" class="form-control" name="txt_producto" id="txt_producto" placeholder="Nombre Producto" data-source="<?php echo base_url('producto/valproductonombre?nombre=');?>" v-model="producto_nombre">
              </div>
            </div>

            <div class="form-group col-md-6" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
              <label for="" class="col-sm-2 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Serie</label>
              <div style="padding-right: 0px;" class="col-md-8">
                <input type="text" class="form-control" placeholder="Serie Producto" v-model="serie_nombre">             
              </div>
                <a style="color: blue;" href="#" title="Actualizar" 
                  v-on:click="cargar_series_pornombre" 
                ><i class="fa fa-edit fa-lg"></i></a>                

            </div>


          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="box box-danger">

          <h4 class="box-title" style="padding-left: 20px;"> Series disponibles</h4>

          <div class="container box-body table-responsive" style="height: 300px;">

            <table class="table table-bordered table-hover">
              <thead>
                <tr >
                  <th class="text-center col-md-1">Acción</th>                            
                  <th class="text-center col-md-1">Número de Serie</th>                            
                  <th class="text-center col-md-1">Fecha Ingreso</th>                            
                  <th class="text-center col-md-1">Almacen</th>                            
                  <th class="text-center col-md-1">Estado</th>                            
                  <th class="text-center col-md-1">Descripción</th>                            
                </tr>
              </thead>

              <editar-estado-serie 
                    v-bind:value="detalle_seleccionado"
                    v-on:modificar_estado="actualiza_serie($event)"
                  >  
              </editar-estado-serie>

              <tbody>
                <tr v-for="data in series" >
                  <td class="text-center">
                      <a style="color: blue;" href="#" title="Cambiar Estado" 
                        v-on:click="modificar_estado(data)" 
                        v-if="habilitar(data)"
                      ><i class="fa fa-edit fa-lg"></i></a> 
                  </td>
                  <td >{{data.numeroserie}}</td>
                  <td>{{data.fechaingreso}}</td>
                  <td>{{data.almacen_nombre}}</td>
                  <td>{{data.estado}}</td>
                  <td>{{data.descripcion}}</td>
                </tr>
              </tbody>    
            </table>
          </div>
        </div>
      </div>      
    </div>

  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

<script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue-resource.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue-router.js"></script> 
<script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/axios.min.js"></script>
 
<script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/component/producto/control_serie.js"></script>