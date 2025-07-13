<?php
/* ------------------------------------------------
  ARCHIVO: sucursal.php
  DESCRIPCION: Contiene la vista principal del módulo de Sucursal.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Sucursal'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {
      /* CARGA DE DATOS EN EL DATATABLE */
      $('#dataTableSuc').dataTable({
                      "language":{  'url': base_url + 'public/json/language.spanish.json' },
                      'ajax': "sucursal/listadoDataSuc",
                      'columns': [
                          {"data": "ver"},
                          {"data": "nombre"},
                          {"data": "direccion"},
                          {"data": "telefono"},    
                          {"data": "correo"},  
                          {"data": "encargado"}                                                                        
                      ]
      });

      $(document).on('click', '.suc_ver', function(){
          id = $(this).attr('id');
          //alert(id);
          $.ajax({
             type: "POST",
             dataType: "json",
             url: "<?php print $base_url;?>sucursal/tmp_suc",
             data: {id: id},
             success: function(json) {
                if (parseInt(json.resu) == 1) {
                   location.replace("<?php print $base_url;?>Sucursal/suc_edit");
                } else {
                   alert("Error de conexión");
                }
             }
          }); 
      })

      $(document).on('click', '.suc_del', function(){
          id = $(this).attr('id');
          if (confirm("Desea eliminar la sucursal seleccionada?")){
            $.ajax({
             type: "POST",
             dataType: "json",
             url: "<?php echo base_url('sucursal/eliminar');?>",
             data: {id: id},
             success: function(json) {
               if (json.mens == 1){
                  $('#dataTableSuc').DataTable().ajax.reload();
               }
               else{
                  alert("No se pudo eliminar la sucursal. Existe informacion asociada.");
               }
             }
            });
          }  
      })
/*

      $(document).on('click', '.suc_del', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('sucursal/tmp_suc');?>",
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
                href: "<?php echo base_url('sucursal/del_suc');?>" 
              });
           }
        });
      })

*/


});





 

    

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-university"></i> Sucursal
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>almacen">Sucursal</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Listado de las Sucursales</h3>
                      <div class="pull-right"> 
                      <a class="btn btn-success btn-grad" href="<?php print $base_url;?>sucursal/agregar" data-original-title="" title=""><i class="fa fa-plus-square"></i> Añadir </a>
                          <!-- <button type="button" class="btn btn-danger btn-grad add_suc" >
                            <i class="fa fa-plus-square"></i> Añadir
                          </button> -->
                       
                    </div>
                    </div>
                    <div class="box-body">

                      <div class="row">
                        
                        
                        <div class="col-xs-12">
                            <div class="box-body table-responsive">
                              <table id="dataTableSuc" class="table table-bordered table-striped">
                                <thead>
                                  <tr >
                                    <th>Acción</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Correo Electrónico</th>
                                    <th>Encargado</th>
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

