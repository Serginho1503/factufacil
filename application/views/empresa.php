<?php
/* ------------------------------------------------
  ARCHIVO: sucursal.php
  DESCRIPCION: Contiene la vista principal del módulo de Sucursal.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Empresa'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {

      $('#dataTableEmp').dataTable({
        "language":{  'url': base_url + 'public/json/language.spanish.json' },
        'ajax': "Empresa/listadoDataEmp",
        'columns': [
          {"data": "ver"},
          {"data": "codigo"},
          {"data": "nombre"},
          {"data": "ruc"},    
          {"data": "razon"}                                                                       
        ]
      });

      $(document).on('click', '.emp_editar', function(){
          id = $(this).attr('id');
          $.ajax({
             type: "POST",
             dataType: "json",
             url: "<?php print $base_url;?>empresa/tmp_emp",
             data: {id: id},
             success: function(json) {
                if (parseInt(json.resu) == 1) {
                   location.replace("<?php print $base_url;?>Empresa/emp_editar");
                } else {
                   alert("Error de conexión");
                }
             }
          }); 
      })

      $(document).on('click', '.emp_del', function(){
          id = $(this).attr('id');
          if (confirm("Desea eliminar la empresa seleccionada")){
            $.ajax({
             type: "POST",
             dataType: "json",
             url: "<?php echo base_url('Empresa/existe_info_emp');?>",
             data: {id: id},
             success: function(json) {
                if (json.mens == 0){
                  $.ajax({
                   type: "POST",
                   dataType: "json",
                   url: "<?php echo base_url('Empresa/eliminar');?>",
                   data: {id: id},
                   success: function(json) {
                      location.replace("<?php print $base_url;?>empresa");
                   }
                  });
                }
                else  
                  alert("No es posible eliminar la empresa. Existe informacion asociada.");
             }
            });
          }
      })




});





 

    

</script>
<div class="content-wrapper">

  <section class="content-header">
    <h1><i class="fa fa-fort-awesome"></i> Empresa</h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>empresa">Empresa</a></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"></i> Listado de las Empresas</h3>
            <div class="pull-right"> 
              <a class="btn btn-primary btn-grad" href="<?php print $base_url;?>empresa/emp_add" data-original-title="" title=""><i class="fa fa-plus-square"></i> Añadir </a>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <div class="box-body">
                  <table id="dataTableEmp" class="table table-bordered table-striped">
                    <thead>
                      <tr >
                        <th>Acción</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>R.U.C.</th>
                        <th>Razón</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div  align="center" class="box-footer">
          </div>
        </div>
      </div>
    </div>
  </section>
  
</div>