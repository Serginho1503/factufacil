<?php
/* ------------------------------------------------
  ARCHIVO: retencion_concepto.php
  DESCRIPCION: Contiene la vista principal del módulo de Conceptos de Retención.
  FECHA DE CREACIÓN: 06/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
  print "<script>document.title = '$nombresistema - Listado de Conceptos de Retención'</script>";
  date_default_timezone_set("America/Guayaquil");

?>

<style type="text/css">

</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

    $('#TableRet').dataTable({
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
        'ajax': "Retencion/listadoRet",
        'columns': [
            {"data": "ver"},                            
            {"data": "codigo"},
            {"data": "descripcion"},   
            {"data": "porcentaje"}
        ]
    });


    $(document).on('click', '.ret_ver', function(){
      id = $(this).attr('id');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Retencion/tmp_ret');?>",
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
            href: "<?php echo base_url('Retencion/upd_ret');?>" 
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
        href: "<?php echo base_url('Retencion/add_ret');?>" 
      });
    });

    $(document).on('click','.ret_del', function() {
      id = $(this).attr('id');
        if (conf_del()) {
          $.ajax({
            url: base_url + "Retencion/del_ret",
            data: { id: id },
            type: 'POST',
            dataType: 'json',
            success: function(json) {
              $('#TableRet').DataTable().ajax.reload();
            }
          });
      }
      return false; 
    });


    function conf_del() {
        return  confirm("¿Confirma que desea eliminar esta Retención?");
    }



  }); 

























 /*

           
        function conf_guar() {
          return  confirm("¿Confirma que desea guardar este registro?");
        }


















      
      $(document).on('click', '.cat_del', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php // echo base_url('categoria/tmp_cat');?>",
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
                href: "<?php // echo base_url('categoria/del_cat');?>" 
              });
           }

        });
          
         
      })

    }); 

  //  $tabla = '{"id":".$row[cat_id].","nombres":".$row[cat_descripcion].","ver":".$row[ver]."},';

*/


</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-registered"></i> Lista de Conceptos de Retención
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
                      <h3 class="box-title"></i> Datos de Conceptos de Retención</h3>
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
                              <table id="TableRet" class="table table-bordered table-striped table-responsive">
                                <thead>
                                  <tr >
                                    <th>Acción</th>
                                    <th>Código</th>
                                    <th>Descripcion</th>
                                    <th>Porcentaje</th>
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

