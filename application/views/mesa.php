<?php
/* ------------------------------------------------
  ARCHIVO: Mesa.php
  DESCRIPCION: Contiene la vista principal del módulo de Mesa.
  FECHA DE CREACIÓN: 04/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Punto de Venta'</script>";
date_default_timezone_set("America/Guayaquil");

?>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {
    /* CARGA DEL DATATABLE (LISTADO) */
    var dt_mesa =  $('#dataTableMesa').dataTable({
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
        'ajax': "Mesa/listadoDataMesa",
        'columns': [
          
            {"data": "nombre"},
            {"data": "capacidad"},
            {"data": "area"},
            {"data": "ver"}
        ]
    });

    /* LEVANTAR VENTANA PARA CREAR mesa */
    $(document).on('click', '.add_mesa', function(){
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        ajax: {
           dataType: "html",
           type: "POST"
        },
        href: "<?php echo base_url('mesa/add_mesa');?>" 
      });
    });

    /* LEVANTAR VENTANA PARA MODIFICAR mesa */
    $(document).on('click', '.edi_mesa', function(){
        id = $(this).attr('id');
        $.ajax({
         type: "POST",
         dataType: "json",
         url: "<?php echo base_url('mesa/tmp_mesa');?>",
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
              href: "<?php echo base_url('mesa/upd_mesa');?>" 
            });
         }
      });
    })

    /* LEVANTAR VENTANA PARA ELIMINAR mesa */
    $(document).on('click', '.del_mesa', function(){
        id = $(this).attr('id');
        $.ajax({
         type: "POST",
         dataType: "json",
         url: "<?php echo base_url('mesa/tmp_mesa');?>",
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
              href: "<?php echo base_url('mesa/del_mesa');?>" 
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
        <i class="fa fa-globe" aria-hidden="true"></i> Punto de Venta
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>mesa">Punto de Venta</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Datos del Punto de Venta</h3>
                      <div class="pull-right"> 
                          <button type="button" class="btn btn-success btn-grad add_mesa" >
                            <i class="fa fa-plus-square"></i> Añadir
                          </button>   
                    </div>
                    </div>
                    <div class="box-body">

                      <div class="row">
                        <div class="col-xs-2 ">
                        </div>

                        <div class="col-xs-8">
                            <div class="box-body">
                              <table id="dataTableMesa" class="table table-bordered table-striped">
                                <thead>
                                  <tr >
                                      <th>Nombre del Punto</th>
                                      <th>Capacidad</th>
                                      <th>Area</th>
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

