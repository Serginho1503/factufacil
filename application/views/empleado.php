<?php
/* ------------------------------------------------
  ARCHIVO: empleado.php
  DESCRIPCION: Contiene la vista principal del módulo de empleado.
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
  print "<script>document.title = '$nombresistema - Listado de Empleados'</script>";
  date_default_timezone_set("America/Guayaquil");

?>

<style type="text/css">

</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

    $('#TableObj').dataTable({
      "language":{  "lengthMenu":"Mostrar _MENU_ registros por página.",
                    "zeroRecords": "Lo sentimos. No se encontraron registros.",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros aún.",
                    "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                    "search" : "Búsqueda",
                    "LoadingRecords": "Cargando ...",
                    "Processing": "Procesando...",
                    "SearchPlaceholder": "Comience a teclear...",
                    "paginate": { "previous": "Anterior", "next": "Siguiente", }
                    },
        'ajax': "Empleado/listadoEmpleados",
        'columns': [
            {"data": "nombre"},
            {"data": "identificacion"},   
            {"data": "tipoident"},   
            {"data": "ver"}                            
        ]
    });


    $(document).on('click', '.ret_ver', function(){
      id = $(this).attr('id');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Empleado/tmp_empleado');?>",
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
            href: "<?php echo base_url('Empleado/upd_empleado');?>",
            afterClose: function(){
              $('#TableObj').DataTable().ajax.reload();
            }
          });
        }
      });
    });  

    $(document).on('click', '.ret_add', function(){
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        ajax: {
           dataType: "html",
           type: "POST"
        },
        href: "<?php echo base_url('Empleado/add_empleado');?>",
        afterClose: function(){
          $('#TableObj').DataTable().ajax.reload();
        } 
      });
    });

    $(document).on('click','.ret_del', function() {
        id = $(this).attr('id');
        if (conf_del()) {
          $.ajax({
            url: base_url + "Empleado/del_empleado",
            data: { id: id },
            type: 'POST',
            dataType: 'json',
            success: function(json) {
              $('#TableObj').DataTable().ajax.reload();
            }
          });
      }
      return false; 
    });


    function conf_del() {
        return  confirm("¿Confirma que desea eliminar este Empleado?");
    }

    $(document).on('click', '.btnguardar', function(){ 
      var ident = $("#txt_ident").val();    
      var id = $("#txt_id").val();

      var nombre = $("#txt_nombre").val();

      if ((nombre == '') || (ident == '')){
        return false;
      }

      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Empleado/existeIdentificacion",
          data: { id: id, identificacion: ident },
          success: function(json) {
            if (json.resu == 0){

              var tipoident = $('#cmb_tipoident option:selected').val();      
              var activo = $("#chkactivo").val();
              var estecnico = $("#chkestecnico").val();

              $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: base_url + "Empleado/guardar",
                  data: { id: id, ident: ident, tipoident: tipoident,
                          nombre: nombre, activo: activo, estecnico: estecnico
                        },
                  success: function(json) {
                    $.fancybox.close();
                    location.replace("<?php print $base_url ?>empleado");
                  }
              });

            } else {
                alert("El numero de identificación ya esta registrado para otro empleado");
                $('#txt_ident').focus();
                return false;
            } 
          }
      });
    });  


  }); 


</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-registered"></i> Lista de Empleados 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>empleado">Empleados</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Datos de Empleados</h3>
                      <div class="pull-right"> 

                          <button type="button" class="btn btn-success btn-grad ret_add" >
                            <i class="fa fa-plus-square"></i> Añadir
                          </button>   

                       
                    </div>
                    </div>
                    <div class="box-body">

                      <div class="row">
                        <div class="col-xs-12">
                            <div class="box-body table-responsive">
                              <table id="TableObj" class="table table-bordered table-striped table-responsive">
                                <thead>
                                  <tr >
                                    <th>Nombre</th>
                                    <th>Identificacion</th>
                                    <th>Tipo</th>
                                    <th>Accion</th>
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

