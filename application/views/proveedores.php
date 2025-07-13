<?php
/*
  FUNCION QUE PERMITE CONECTAR EL DATATABLE CON LA BASE DE DATOS
*/
?>
  <script type='text/javascript' language='javascript'>
    $(document).ready(function () {
       $('#dataTableProvee').dataTable({
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
          'ajax': "Proveedor/listadoDataProvee",
          'columns': [
              {"data": "ver"},
              {"data": "nombre"},
              {"data": "razsoc"},
              {"data": "telf"},
              {"data": "correo"},
              {"data": "ciudad"}
          ]
      });

      /* AGREGAR PROVEEDOR */
      $(document).on('click', '.add_provee', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('proveedor/add_provee');?>" 
        });
      });

      /* EDITAR PROVEEDOR */
      $(document).on('click', '.edi_provee', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('proveedor/tmp_provee');?>",
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
                href: "<?php echo base_url('proveedor/edi_provee');?>" 
              });
           }
        });
      })

      /* ELIMINAR PROVEEDOR*/
      $(document).on('click', '.del_provee', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('proveedor/tmp_provee');?>",
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
                href: "<?php echo base_url('proveedor/del_provee');?>" 
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
      <h1><i class="fa fa-truck"></i>
        Proveedores
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="#">Proveedores</a></li>
        <li class="active">Listado de Proveedores</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Listado de Proveedores</h3>
                <div class="pull-right"> 
                <a class="btn btn-success btn-grad add_provee" href="#" data-original-title="" title=""><i class="fa fa-users"></i> Añadir </a>
                </div>
                <hr style="margin-bottom: 0">
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="dataTableProvee" class="table table-bordered table-striped table-responsive">
                <thead>
                  <tr >
                      <th>Acción</th> 
                      <th>Nombre del Proveedor</th>
                      <th>Razón Social</th>
                      <th>Teléfono</th>
                      <th>Correo</th>
                      <th>Ciudad</th>    
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
