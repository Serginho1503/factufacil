<?php
/*
  FUNCION QUE PERMITE CONECTAR EL DATATABLE CON LA BASE DE DATOS
*/
?>
  <script type='text/javascript' language='javascript'>
    $(document).ready(function () {
       $('#dataTableMesero').dataTable({
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
          'ajax': "Mesero/listadoDataMesero",
          'columns': [
              {"data": "id"},
              {"data": "ced"},
              {"data": "nombre"},
              {"data": "estatus"},
              {"data": "ver"}
          ]
      });

      /* EDITAR MESERO */
      $(document).on('click', '.edi_mesero', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('mesero/tmp_mesero');?>",
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
                href: "<?php echo base_url('mesero/edi_mesero');?>" 
              });
           }
        });
      })

      /* AGREGAR MESERO */
      $(document).on('click', '.add_mesero', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('mesero/add_mesero');?>" 
        });
      });

      /* ELIMINAR MESERO*/
      $(document).on('click', '.del_mesero', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('mesero/tmp_mesero');?>",
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
                href: "<?php echo base_url('mesero/del_mesero');?>" 
              });
           }
        });
      })

      /* ELIMINAR MESERO*/
      $(document).on('click', '.cli_mesero', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('mesero/tmp_mesero');?>",
           data: {id: id},
           success: function(json) {
            location.replace("<?php print $base_url;?>mesero/reporte_clientes_vendedorXLS");
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
        Vendedor
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="#">Vendedor</a></li>
        <li class="active">Listado de Vendedor</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Listado de Vendedor</h3>
                <div class="pull-right"> 
                <a class="btn btn-success btn-grad add_mesero" href="#" data-original-title="" title=""><i class="fa fa-users"></i> Añadir </a>
                </div>
                <hr style="margin-bottom: 0">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="dataTableMesero" class="table table-bordered table-striped">
                <thead>
                  <tr >
                      <th>Id</th>
                      <th>Cedula</th>
                      <th>Nombre del Vendedor</th>
                      <th>Estatus</th>    
                      <th>Ver</th> 
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
