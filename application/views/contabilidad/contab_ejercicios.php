<?php
/*
  FUNCION QUE PERMITE CONECTAR EL DATATABLE CON LA BASE DE DATOS
*/
?>
  <script type='text/javascript' language='javascript'>
    $(document).ready(function () {
       $('#dataTableObj').dataTable({
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
          'ajax': "contabilidad/contab_ejercicios/listadoEjercicios",
          'columns': [
              {"data": "ver"},
              {"data": "inicio"},
              {"data": "fin"},
              {"data": "descripcion"}
          ]
      });

      /* AGREGAR  */
      $(document).on('click', '.add_ejer', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('contabilidad/contab_ejercicios/add_ejercicio');?>",
          afterClose: function() {
            $('#dataTableObj').DataTable().ajax.reload();          
          } 
        });
      });

      /* EDITAR  */
      $(document).on('click', '.edi_ejer', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('contabilidad/contab_ejercicios/tmp_ejercicio');?>",
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
                href: "<?php echo base_url('contabilidad/contab_ejercicios/upd_ejercicio');?>" ,
                afterClose: function() {
                  $('#dataTableObj').DataTable().ajax.reload();          
                } 
              });
           }
        });
      })

      /* ELIMINAR */
      $(document).on('click', '.del_ejer', function(){
          id = $(this).attr('id');
          if (confirm("Desea eliminar el ejercicio")){
            $.ajax({
              type: "POST",
              dataType: "json",
              url: "<?php echo base_url('contabilidad/contab_ejercicios/del_ejercicio');?>",
              data: {id: id},
              success: function(json) {
                if (json.resu == 0){
                  alert("No se pudo eliminar el ejercicio. Existe informacion asociada.");
                }  
                else{
                  $('#dataTableObj').DataTable().ajax.reload();
                }
              }
            });
          }  
      })



 });

</script>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-calendar"></i>
        Ejercicios      
      </h1>
      <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>contab_ejercicios">Ejercicios</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Listado de Ejercicios</h3>
                <div class="pull-right"> 
                <a class="btn btn-success btn-grad add_ejer" href="#" data-original-title="" title=""><i class="fa fa-plus-square"></i> Añadir </a>
                </div>
                <hr style="margin-bottom: 0">
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="dataTableObj" class="table table-bordered table-striped table-responsive">
                <thead>
                  <tr >
                      <th>Ver</th> 
                      <th>Inicio</th>
                      <th>Fin</th>
                      <th>Descripción</th>
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
