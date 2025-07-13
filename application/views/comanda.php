<?php
/* ------------------------------------------------
  ARCHIVO: Comanda.php
  DESCRIPCION: Contiene la vista principal del módulo de Comanda.
  FECHA DE CREACIÓN: 29/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Impresoras'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {
      /* CARGA DE DATOS EN EL DATATABLE */
      $('#dataTableComa').dataTable({
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
        'ajax': "Comanda/listadoDataComa",
        'columns': [
            {"data": "id"},
            {"data": "nombre"},
            {"data": "impresora"},
            {"data": "ver"}
        ]
      });

      /* AGREGAR COMANDA */
      $(document).on('click', '.add_com', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('comanda/add_comanda');?>" 
        });
      });

      /* MODIFICAR ALMACEN */
      $(document).on('click', '.edit_com', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('comanda/tmp_com');?>",
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
                href: "<?php echo base_url('comanda/upd_com');?>" 
              });
           }
        });
      })

      /* ELIMINAR ALMACEN */
      $(document).on('click', '.eli_com', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('comanda/tmp_com');?>",
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
                href: "<?php echo base_url('comanda/del_com');?>" 
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
        <i class="fa fa-print"></i> Impresoras
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>comanda">Impresoras</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Listado de Impresoras</h3>
                      <div class="pull-right"> 
                          <button type="button" class="btn btn-danger btn-grad add_com" >
                            <i class="fa fa-plus-square"></i> Añadir
                          </button>
                       
                    </div>
                    </div>
                    <div class="box-body">
                      <div class="col-xs-1"></div>
                      <div class="row">
                        <div class="col-xs-10">
                            <div class="box-body table-responsive">
                              <table id="dataTableComa" class="table table-bordered table-striped">
                                <thead>
                                  <tr >
                                    <th>Id</th>  
                                    <th>Nombre</th>
                                    <th>Impresora</th>
                                    <th>Acción</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                        </div>
                      </div>
                      <div class="col-xs-1"></div>
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

