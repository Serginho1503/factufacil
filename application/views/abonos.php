<?php
/* ------------------------------------------------
  ARCHIVO: almacen.php
  DESCRIPCION: Contiene la vista principal del módulo de Almacen.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Almacen'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {
      /* CARGA DE DATOS EN EL DATATABLE */
      var dt_alm =  $('#dataTableAlm').dataTable({
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
                      'ajax': "Almacen/listadoDataAlm",
                      'columns': [
                          {"data": "id"},
                          {"data": "nombre"},
                          {"data": "direccion"},
                          {"data": "responsable"},    
                          {"data": "descripcion"},  
                          {"data": "sucursal"},                                                                        
                          {"data": "ver"}
                      ]
      });

      /* AGREGAR ALMACEN */
      $(document).on('click', '.add_alm', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('almacen/add_alm');?>" 
        });
      });

      /* MODIFICAR ALMACEN */
      $(document).on('click', '.alm_ver', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('almacen/tmp_alm');?>",
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
                href: "<?php echo base_url('almacen/upd_alm');?>" 
              });
           }
        });
      })

      /* ELIMINAR ALMACEN */
      $(document).on('click', '.alm_del', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('almacen/tmp_alm');?>",
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
                href: "<?php echo base_url('almacen/del_alm');?>" 
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
        <i class="fa fa-cubes"></i> Almacen
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>almacen">Almacen</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Listado de los Almacenes</h3>
                      <div class="pull-right"> 
                          <button type="button" class="btn btn-danger btn-grad add_alm" >
                            <i class="fa fa-plus-square"></i> Añadir
                          </button>
                       
                    </div>
                    </div>
                    <div class="box-body">

                      <div class="row">
                        <div class="col-xs-12">
                            <div class="box-body table-responsive">
                              <table id="dataTableAlm" class="table table-bordered table-striped">
                                <thead>
                                  <tr >
                                    <th>Id</th>  
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Responsable</th>
                                    <th>Descripción</th>
                                    <th>Sucursal</th>
                                    <th>Acción</th>
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
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

