<?php
/* ------------------------------------------------
  ARCHIVO: Precio.php
  DESCRIPCION: Contiene la vista principal del módulo de Precio.
  FECHA DE CREACIÓN: 12/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Precios'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {

      var dt_usu =  $('#dataTablePre').dataTable({
          "language":{  "lengthMenu":"Mostrar _MENU_ registros por página.",
                        "zeroRecords": "Lo sentimos. No se encontraron registros.",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "No hay registros aún.",
                        "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                        "search" : "Búsqueda",
                        "LoadingRecords": "Cargando ...",
                        "Processing": "Procesando...",
                        "SearchPlaceholder": "Comience a teclear...",
                        "paginate": { "previous": "Anterior",
                                      "next": "Siguiente", }
                      },
          'ajax': "Precio/listadoDataPre",
          'columns': [
              {"data": "id"},
              {"data": "descripcion"},
              {"data": "estatus"},
              {"data": "ver"}
          ]
      });

      /* AGREGAR PRECIO */
      $(document).on('click', '.add_pre', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('precio/add_pre');?>" 
        });
      });
         
      /* MODIFICAR PRECIO */
      $(document).on('click', '.pre_ver', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('precio/tmp_pre');?>",
           data: {id: id},
           success: function(json) {
              $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST"
                },
                href: "<?php echo base_url('precio/upd_pre');?>" 
              });
           }
        });
      })

      /* ELIMINAR PRECIO*/
      $(document).on('click', '.pre_del', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('precio/tmp_pre');?>",
           data: {id: id},
           success: function(json) {
              $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST"
                },
                href: "<?php echo base_url('precio/del_pre');?>" 
              });
           }
        });
      })


    }); 




</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-money"></i> Precios de Venta
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>precio">Precios</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row" id="app_precio_compraventa">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Listado de Precios</h3>
                      <div class="pull-right"> 
                        <a class="btn btn-info color-palette btn-grad" v-on:click="editar_porciento()" 
                           title="Relación entre Precio de Compra y Venta" 
                        >
                          <i class="fa fa-exit"></i> Compra-Venta 
                        </a>

                        <button type="button" class="btn btn-success btn-grad add_pre" >
                          <i class="fa fa-plus-square"></i> Añadir
                        </button>                      
                      </div>
                    </div>

                    <editar-porciento
                          v-bind:value="precios"
                          v-on:porciento_actualizado="actualiza_porciento($event)"
                    >  
                    </editar-porciento>

                    <div class="box-body">

                      <div class="row">
                        <div class="col-xs-2 ">
                        </div>

                        <div class="col-xs-8">
                            <div class="box-body">
                              <table id="dataTablePre" class="table table-bordered table-striped">
                                <thead>
                                  <tr >
                                      <th>Id</th>
                                      <th>Descripcion</th>
                                      <th>Estatus</th>
                                      <th>Acción</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                        </div>
                        <div class="col-xs-2 ">
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
 
<script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/component/precio/precio_compraventa.js"></script>