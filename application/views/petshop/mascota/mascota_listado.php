<?php
/* ------------------------------------------------
  ARCHIVO: almacen.php
  DESCRIPCION: Contiene la vista principal del módulo de Almacen.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Mascotas'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {
      /* CARGA DE DATOS EN EL DATATABLE */
      $('#dataTableMasc').dataTable({
        'language': { 'url': base_url + 'public/json/language.spanish.json' },
        'ajax': "Mascota/listadoDataMasc",
        'columns': [
          {"data": "accion"},
          {"data": "nombre"},
          {"data": "raza"},
          {"data": "edad"},    
          {"data": "responsable"},  
          {"data": "telefono"}, 
          {"data": "ciudad"}
        ]
      });

      /* AGREGAR ALMACEN */
      $(document).on('click', '.add_masc', function(){
        location.replace('mascota/add_mascota');
      }); 

    $(document).on("submit", "#frmascota", function() {
        var id = $(this).attr("name");
        var data = $(this).serialize();
        $.ajax({
          url: $(this).attr("action"),
          data: data,
          type: 'POST',
          dataType: 'json',
          success: function(json) {
            $('#dataTableMasc').DataTable().ajax.reload();
            //$.fancybox.close();
          }
        });
        return false;
    });

    $(document).on('click', '.edi_masc', function(){
        id = $(this).attr('id');
        $.ajax({
         type: "POST",
         dataType: "json",
         url: "<?php echo base_url('petshop/mascota/tmp_masc');?>",
         data: {id: id},
         success: function(json) {
          location.replace('mascota/sel_mascota');
         }
      });
    })



      $(document).on('click', '.del_masc', function(){
          id = $(this).attr('id');
          if (confirm("Desea eliminar la mascota seleccionada?")){
            $.ajax({
             type: "POST",
             dataType: "json",
             url: "<?php echo base_url('petshop/mascota/del_mascota');?>",
             data: {id: id},
             success: function(json) {
                $('#dataTableMasc').DataTable().ajax.reload();
             }
            });
          }  
      });

      $(document).on('click', '.hist_masc', function(){
          id = $(this).attr('id');
          mascotahistoriamodel.mascota_seleccionada = id;
      });

      $(document).on('click', '.print_his', function(){
          id = $(this).attr('id');
          $.ajax({
             type: "POST",
             dataType: "json",
             url: "<?php echo base_url('petshop/mascota/tmp_mascota_historia');?>",
             data: {id: id},
             success: function(json) {
                window.open("<?php print $base_url;?>petshop/mascota/print_pdf_historia", '_blank');
             }
          });
      });

    }); 

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="app_mascota_historia">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-paw"></i> Mascotas
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="<?php print $base_url ?>petshop/mascota"><i class="fa fa-dashboard"></i> Listado de Mascotas</a></li>       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content" >
        <div class="row" v-if="mostrar_historia == false">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Listado de Mascotas</h3>
                      <div class="pull-right"> 
                          <button type="button" class="btn btn-success btn-grad add_masc" >
                            <i class="fa fa-plus-square"></i> Añadir
                          </button>
                       
                    </div>
                    </div>
                    <div class="box-body">

                      <div class="row">
                        <div class="col-xs-12">
                            <div class="box-body table-responsive">
                              <table id="dataTableMasc" class="table table-bordered table-striped">
                                <thead>
                                  <tr >
                                    <th>Acción</th>
                                    <th>Nombre</th>
                                    <th>Raza</th>
                                    <th>Edad</th>
                                    <th>Responsable</th>
                                    <th>Teléfono</th>
                                    <th>Ciudad</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.box-body -->
                    <div  align="center" class="box-footer">
                        
                    </div>
                </div>
              <!-- /.box -->
            </div>
           
        </div>

        <div class="row" v-if="mostrar_historia == true">

            <editar-mascota-historia 
                  v-bind:value="historia_seleccionada"
                  v-on:modificar_historia="actualiza_historia($event)"
                >  
            </editar-mascota-historia>
            
            <div class="col-md-12">
                <div class="box box-danger">
                  <div class="box-header with-border">
                    <div class="form-group col-md-12" >
                      <h3 class="box-title"></i> Historia Clínica de Mascota</h3>
                      <div class="pull-right"> 
                          <button type="button" class="btn btn-success btn-grad" v-on:click="adicionar_historia()" >
                            <i class="fa fa-plus-square"></i> Añadir
                          </button>                       
                      </div>
                    </div>

                    <div class="form-group col-md-2" >
                        <label style="margin-bottom: 0px;">Nombre</label>
                        <input type="text" class="form-control" v-model="mascota.nombre" readonly>
                    </div>

                    <div class="form-group col-md-2" >
                        <label style="margin-bottom: 0px;">Código</label>
                        <input type="text" class="form-control" v-model="mascota.codigo" readonly>
                    </div>

                    <div class="form-group col-md-2" >
                        <label style="margin-bottom: 0px;">Raza</label>
                        <input type="text" class="form-control" v-model="mascota.raza" readonly>
                    </div>

                    <div class="form-group col-md-1" >
                        <label style="margin-bottom: 0px; padding: 0px;">Sexo</label>
                        <input type="text" class="form-control text-center" v-model="sexo" style="padding: 0px;" readonly>
                    </div>

                    <div class="form-group col-md-2" >
                        <label style="margin-bottom: 0px;">Fecha Nacimiento</label>
                        <input type="text" class="form-control" v-model="mascota.fec_nac" readonly>
                    </div>

                    <div class="form-group col-md-3" >
                        <label style="margin-bottom: 0px;">Representante</label>
                        <input type="text" class="form-control" v-model="mascota.nom_cliente" readonly>
                    </div>

                  </div>

                </div>
            </div>

            <div class="col-md-12">
                <div class="box box-danger">
                  <div class="box-header with-border">
                    <h3 class="box-title"></i> Registros de Historia Clínica</h3>

                    <div class="box-body">

                      <div class="row">
                        <div class="col-xs-12">
                            <div class="box-body table-responsive">
                              <table class="table table-bordered table-striped">
                                <thead>
                                  <tr >
                                    <th class="text-center col-md-1">Acción</th>                            
                                    <th class="text-center col-md-3">Fecha</th>  
                                    <th class="text-left col-md-8">Observaciones</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr v-for="data in historias">
                                    <td class="text-center">
                                        <a style="color: blue;" href="#" title="Editar Historia" 
                                          v-on:click="modificar_historia(data)" 
                                        ><i class="fa fa-edit fa-lg"></i></a> 
                                        <a style="color: red;" href="#" title="Eliminar Historia" 
                                          v-on:click="eliminar_historia(data)" 
                                        ><i class="fa fa-trash-o fa-lg"></i></a> 
                                        <a style="color: red" href="#" title="Imprimir Historia" 
                                        ><i class="fa fa-file-pdf-o fa-sm print_his" v-bind:id="data.id"></i></a> 
                                    </td>
                                    <td class="text-center">{{ data.fecha }}</td>  
                                    <td>{{ data.observaciones }}</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>  
                </div>
            </div>
          
        </div>        
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

<!-- LIBRERIA DE VUEJS - CARLOS ZAMBRANO 22 DE 11 - 2018 -->
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue-resource.min.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue-router.js"></script> 
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/sweetalert.min.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/axios.min.js"></script>
 
<script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/component/petshop/mascota_historiaclinica.js"></script>