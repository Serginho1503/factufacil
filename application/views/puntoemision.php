<?php
/* ------------------------------------------------
  ARCHIVO: puntoemision.php
  DESCRIPCION: Contiene la vista principal del módulo de puntoemision.
  FECHA DE CREACIÓN: 06/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
  print "<script>document.title = '$nombresistema - Listado de Puntos de Emisión'</script>";
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
        'ajax': "Puntoemision/listadoPuntos",
        'columns': [
            {"data": "ver"},                            
            {"data": "sucursal"},
            {"data": "cod_establecimiento"},   
            {"data": "cod_puntoemision"},
            {"data": "consecutivo_factura"},
            {"data": "consecutivo_notaventa"},
            {"data": "consecutivo_retencion"},
            {"data": "consecutivo_comprobpago"}
        ]
    });


    $(document).on('click', '.ret_ver', function(){
      id = $(this).attr('id');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Puntoemision/tmp_puntoemision');?>",
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
            href: "<?php echo base_url('Puntoemision/upd_puntoemision');?>",
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
        href: "<?php echo base_url('Puntoemision/add_puntoemision');?>",
        afterClose: function(){
          $('#TableObj').DataTable().ajax.reload();
        } 
      });
    });

    $(document).on('click','.ret_del', function() {
      id = $(this).attr('id');
        if (conf_del()) {
          $.ajax({
            url: base_url + "Puntoemision/del_puntoemision",
            data: { id: id },
            type: 'POST',
            dataType: 'json',
            success: function(json) {
              if (json.mens == 1){
                $('#TableObj').DataTable().ajax.reload();
              } else {
                alert("No se pudo eliminar el punto de emision. Existe informacion asociada.");
                return false;                
              }  
            }
          });
      }
    });


    function conf_del() {
        return  confirm("¿Confirma que desea eliminar este Punto de Emisión?");
    }



  }); 


</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-registered"></i> Lista de Puntos de Emisión 
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
                      <h3 class="box-title"></i> Datos de Puntos de Emisión</h3>
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
                                    <th>Accion</th>
                                    <th>Sucursal</th>
                                    <th>Establecimiento</th>
                                    <th>Punto Emision</th>
                                    <th>Consec.Factura</th>
                                    <th>Consec.Nota</th>
                                    <th>Consec.Retención</th>
                                    <th>Consec.Pago</th>
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

