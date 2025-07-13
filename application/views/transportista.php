<?php
/*
  FUNCION QUE PERMITE CONECTAR EL DATATABLE CON LA BASE DE DATOS
*/
?>
  <script type='text/javascript' language='javascript'>
    $(document).ready(function () {
       $('#dataTableObj').dataTable({
          "language":{  'url': base_url + 'public/json/language.spanish.json' },
          'ajax': "Transportista/listadoDataTrans",
          'columns': [
              {"data": "id"},
              {"data": "ident"},
              {"data": "nombre"},
              {"data": "ciudad"},
              {"data": "ver"}
          ]
      });

      /* AGREGAR  */
      $(document).on('click', '.add_tran', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('Transportista/add_transportista');?>", 
          afterClose: function() {
            $('#dataTableObj').DataTable().ajax.reload();          
          } 
        });
      });

      $(document).on('click', '.edi_tran', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('Transportista/tmp_tran');?>",
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
                href: "<?php echo base_url('Transportista/edi_tran');?>", 
                afterClose: function() {
                    $('#dataTableObj').DataTable().ajax.reload();          
                } 
              });
           }
        });
      });

      $(document).on('click', '.del_tran', function(){
          id = $(this).attr('id');
          if (confirm("Desea eliminar el transportista")){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url('Transportista/eliminar');?>",
                data: {id: id},
                success: function(json) {
                    $('#dataTableObj').DataTable().ajax.reload();
                }
            });
          }  
      });


 });

</script>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Clientes
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="#">Clientes</a></li>
        <li class="active">Listado de Clientes</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Listado de Clientes</h3>
                <div class="pull-right"> 
                <a class="btn btn-success btn-grad add_tran" href="#" data-original-title="" title=""><i class="fa fa-users"></i> Añadir </a>
                </div>
                <hr style="margin-bottom: 0">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="dataTableObj" class="table table-bordered table-striped">
                <thead>
                  <tr >
                      <th>Id</th>
                      <th>Cedula</th>
                      <th>Nombre del Cliente</th>
                      <th>Ciudad</th>    
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
