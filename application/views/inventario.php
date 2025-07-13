<?php
/* ------------------------------------------------
  ARCHIVO: inventario.php
  DESCRIPCION: Contiene la vista principal del módulo de Inventario.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Inventario'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {
      /* CARGA DE DATOS EN EL DATATABLE */

      $.datepicker.setDefaults($.datepicker.regional["es"]);
      $('#desde').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy', 
          firstDay: 1
        });
      $('#desde').on('changeDate', function(ev){
          $(this).datepicker('hide');
      });

      $('#hasta').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy', 
          firstDay: 1
        });
      $('#hasta').on('changeDate', function(ev){
          $(this).datepicker('hide');
      }); 

      $('#dataTableInventario').dataTable({
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
                      'ajax': "Inventario/listadoDataInventario",
                      'columns': [
                          {"data": "id"},
                          {"data": "fecha"},
                          {"data": "codigobarra"},
                          {"data": "codigoauxiliar"},
                          {"data": "nombre"},
                          {"data": "documento"},    
                          {"data": "tipo"},  
                          {"data": "cantidad"},                                                                        
                          {"data": "valorunitario"},                                                                        
                          {"data": "costototal"},                                                                        
                          {"data": "saldocantidad"},                                                                        
                          {"data": "saldovalorunitario"},                                                                        
                          {"data": "saldocostototal"},                                                                        
                          {"data": "ver"}
                      ]
      });

      /* ACTUALIZAR LISTADO DE GASTOS POR RAGO DE FECHA */
      $('.actualiza').click(function(){
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Inventario/tmp_inv_fecha');?>",
          data: { hasta: hasta, desde: desde },
          success: function(json) {
            $('#dataTableInventario').DataTable().ajax.reload();
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
        <i class="fa fa-cubes"></i> Kardex
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>inventario">Kardex</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">

                    <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
                      <label for="">Desde</label>
                      <div class="input-group date col-sm-7">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" class="form-control pull-right validate[required]" id="desde" name="desde" value="<?php print  date("d/m/Y"); ?>">
                      </div>
                    </div> 

                    <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
                      <label for="">Hasta</label>
                      <div class="input-group date col-sm-10">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" class="form-control pull-right validate[required]" id="hasta" name="hasta" value="<?php print  date("d/m/Y"); ?>">
                        <span class="input-group-btn">
                        <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                        </span>

                      </div>
                    </div> 

                    <div class="box-body">

                      <div class="row">
                        <div class="col-xs-12">
                            <div class="box-body table-responsive">
                              <table id="dataTableInventario" class="table table-bordered table-striped">
                                <thead>
                                  <tr >
                                    <th>id</th>
                                    <th>Fecha</th>
                                    <th>Cod.Barra</th>
                                    <th>Cod.Auxiliar</th>
                                    <th>Producto</th>
                                    <th>Documento</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Valor U.</th>
                                    <th>Costo Total</th>
                                    <th>Acum.Cantidad</th>
                                    <th>Acum.Valor U.</th>
                                    <th>Acum.Costo Total</th>
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

