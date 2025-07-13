<?php
/* ------------------------------------------------
  ARCHIVO: Unidades de Medida.php
  DESCRIPCION: Contiene la vista principal del módulo de unidades de medida.
  FECHA DE CREACIÓN: 10/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Unidades'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {

          /* AGREGAR UNDADES */
        $(document).on('click', '.add_uni', function(){
          $.fancybox.open({
            type: "ajax",
            width: 550,
            height: 550,
            ajax: {
               dataType: "html",
               type: "POST"
            },
            href: "<?php echo base_url('unidades/add_uni');?>" 
          });
        });
           
        function conf_guar() {
          return  confirm("¿Confirma que desea guardar este registro?");
        }

      $('#dataTableUni').dataTable({
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
          'ajax': "Unidades/listadoDataUni",
          'columns': [
              {"data": "descripcion"},
              {"data": "nombre"},
              {"data": "ver"}
          ]
      });
      /* MODIFICAR LA UNIDAD DE MEDIDA */
      $(document).on('click', '.uni_ver', function(){
          id = $(this).attr('id');
        //  alert(id);
          
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('unidades/tmp_uni');?>",
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
                href: "<?php echo base_url('unidades/upd_uni');?>" 
              });
           }

        });
         
      })

      /* ELIMINAR LA UNIDAD DE MEDIDA */
      $(document).on('click', '.uni_del', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('unidades/tmp_uni');?>",
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
                href: "<?php echo base_url('unidades/del_uni');?>" 
              });
           }

        });
          
         
      })

    /* AGREGAR FACTOR DE CONVERSION */
      $(document).on('click', '.uni_conv', function(){
          id = $(this).attr('id');
          //alert(id);
          $.ajax({
             type: "POST",
             dataType: "json",
             url: "<?php print $base_url;?>unidades/tmp_uni",
             data: {id: id},
             success: function(json) {
                if (parseInt(json.resu) == 1) {
                   location.replace("<?php print $base_url;?>unidades/uni_conv");
                } else {
                   alert("Error de conexión");
                }
             }
          }); 
      })    


    }); 

    $tabla = '{"id":".$row[id].","descripcion":".$row[descripcion].","nombre":".$row[nombre].","ver":".$row[ver]."},';

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-balance-scale"></i> Unidades
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>unidades">Unidad de Medidas</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Datos de las Unidades de Medida</h3>
                      <div class="pull-right"> 
                          <button type="button" class="btn btn-danger btn-grad add_uni" >
                            <i class="fa fa-plus-square"></i> Añadir
                          </button>
                       
                    </div>
                    </div>
                    <div class="box-body">

                      <div class="row">
                        <div class="col-xs-2 ">
                        </div>

                        <div class="col-xs-8">
                            <div class="box-body">
                              <table id="dataTableUni" class="table table-bordered table-striped">
                                <thead>
                                  <tr >
                                      
                                      <th>Descripción</th>
                                      <th>Nombre Corto</th>
                                      <th>Acción</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                        </div>
                        <div class="col-xs-2 ">
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

