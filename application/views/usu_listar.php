<?php
/*
  FUNCION QUE PERMITE CONECTAR EL DATATABLE CON LA BASE DE DATOS
*/
?>
<script type='text/javascript' language='javascript'>
  $(document).ready(function () {
    /* CARGAR DATOS AL DATATABLE */
    $('#dataTableUsu').dataTable({
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
        'ajax': "Usuarios/listadoDataUsu",
        'columns': [
            {"data": "id"},
            {"data": "cedula"},
            {"data": "nombres"},
            {"data": "apellidos"},
            {"data": "usuario"},
            {"data": "estatus"},
            {"data": "ver"}
        ]
    });






    /* AGREGAR USUARIOS */
    $(document).on('click', '.usu_ver', function(){
        id = $(this).attr('id');
        //alert(id);
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php print $base_url;?>Usuarios/temp_usu",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) == 1) {
                 location.replace("<?php print $base_url;?>Usuarios/usu_edit");
              } else {
                 alert("Error de conexión");
              }
           }
        }); 
    })
    /* ASIGNAR ROLES AL USUARIO */
    $(document).on('click', '.usu_rol', function(){
        id = $(this).attr('id');
        //alert(id);
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php print $base_url;?>Usuarios/temp_usu",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) == 1) {
                 location.replace("<?php print $base_url;?>Usuarios/usu_rol");
              } else {
                 alert("Error de conexión");
              }
           }
        }); 
    })    

    $(document).on('click','.usu_del', function() {
      var id = $(this).attr("id");
      if (conf_del()) {
        $.ajax({
            url: "<?php print $base_url;?>Usuarios/del_usu",
            data: {id: id},
            type: 'POST',
            dataType: 'json',
            success: function(json) {
                location.reload();
            }
        });
      }
      return false; 
    });


    function conf_del() {
        return  confirm("¿Confirma que desea eliminar este registro?");
    }



}); 



</script>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Usuarios
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio""><i class="fa fa-dashboard"></i> Inicio</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Listado de Usuarios</h3>
                <div class="pull-right"> 
                <a class="btn btn-success  btn-grad" href="<?php print $base_url;?>Usuarios/agregar" data-original-title="" title=""><i class="fa fa-users"></i> Añadir </a>
                </div>
                <hr style="margin-bottom: 0">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="dataTableUsu" class="table table-bordered table-striped">
                <thead>
                  <tr >
                      <th>Id</th>
                      <th>Cedula</th>
                      <th>Nombres</th>
                      <th>Apellidos</th>    
                      <th>Usuario</th>
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
