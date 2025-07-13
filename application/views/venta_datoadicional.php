<?php
/* ------------------------------------------------
  ARCHIVO: venta_datoadicional.php
  DESCRIPCION: Contiene la vista principal del módulo de venta_datoadicional.
  FECHA DE CREACIÓN: 30/04/2019
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
  print "<script>document.title = '$nombresistema - Listado de Datos Adicionales de Venta'</script>";
  date_default_timezone_set("America/Guayaquil");

?>

<style type="text/css">

</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

    $('#TableObj').dataTable({
      "language":{  
                   'url': base_url + 'public/json/language.spanish.json'
                 },
        'ajax': "Ventadatoadicional/listadoDatoadicional",
        'columns': [
            {"data": "ver"},                            
            {"data": "nombre"},
            {"data": "estado"}
        ]
    });


    $(document).on('click', '.det_ver', function(){
      id = $(this).attr('id');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Ventadatoadicional/tmp_datoadicional');?>",
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
            href: "<?php echo base_url('Ventadatoadicional/upd_datoadicional');?>",
            afterClose: function(){
              $('#TableObj').DataTable().ajax.reload();
              $.fancybox.close();
            }
          });
        }
      });
    });  

    $(document).on('click', '.det_add', function(){
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        ajax: {
           dataType: "html",
           type: "POST"
        },
        href: "<?php echo base_url('Ventadatoadicional/add_datoadicional');?>",
        afterClose: function(){
          $('#TableObj').DataTable().ajax.reload();
          $.fancybox.close();
        } 
      });
    });

    $(document).on('click','.det_del', function() {
      id = $(this).attr('id');
        if (conf_del()) {
          $.ajax({
            url: base_url + "Ventadatoadicional/del_datoadicional",
            data: { id: id },
            type: 'POST',
            dataType: 'json',
            success: function(json) {
              if (json.mens == 1){
                $('#TableObj').DataTable().ajax.reload();
              } else {
                alert("No se pudo eliminar el dato adicional de venta. Existe informacion asociada.");
                return false;                
              }  
            }
          });
      }
    });


    function conf_del() {
        return  confirm("¿Confirma que desea eliminar el dato adicional de venta?");
    }



  }); 


</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-registered"></i> Lista de Datos Adicionales de Venta 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Datos Adicionales de Venta</h3>
                      <div class="pull-right"> 

                          <button type="button" class="btn btn-success btn-grad det_add" >
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
                                    <th>Acción</th>
                                    <th>Dato Adicional</th>
                                    <th>Activo</th>
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

