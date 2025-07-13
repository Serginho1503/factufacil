<?php
/* ------------------------------------------------
  ARCHIVO: Cajachica.php
  DESCRIPCION: Contiene la vista principal del módulo de Gastos.
  FECHA DE CREACIÓN: 30/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Caja Chica'</script>";
date_default_timezone_set("America/Guayaquil");


?>

<script type='text/javascript' language='javascript'>

 $(document).ready(function() {


     /* CARGA DE DATOS EN EL DATATABLE */
     $('#dataTableCaja').dataTable({
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
        'ajax': "Cajachica/listadoDataCaja",
        'columns': [
            {"data": "fechaapertura"},
            {"data": "descripcion"},
            {"data": "montoapertura"},  
            {"data": "estatus"},  
            {"data": "fechacierre"},
            {"data": "montocierre"},  
            {"data": "ver"}
        ]
      });


      $.datepicker.setDefaults($.datepicker.regional["es"]);
      $('#desde').on('changeDate', function(ev){
          $(this).datepicker('hide');
      });
      $('#desde').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy', 
          firstDay: 1
        });

      $('#hasta').on('changeDate', function(ev){
          $(this).datepicker('hide');
      }); 
      $('#hasta').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy', 
          firstDay: 1
        });


      /* ACTUALIZAR MOVIMIENTOS DE CAJA CHICA POR RAGO DE FECHA */
      $('.actualiza').click(function(){

      var caja = $("#cmb_caja").val();
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Cajachica/tmp_listado');?>",
          data: { hasta: hasta, desde: desde, caja: caja }
        }).done(function (result) {
              $('#dataTableCaja').DataTable().ajax.reload();
        }); 


      
      });    

    /* Boton del listado para imprimir movimiento de caja chica */
    $(document).on('click', '.caja_print', function(){    
      var id = $(this).attr('id');

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Cajachica/tmp_cajachica_id');?>",
        data: { id: id },
        success: function(json) {
          window.open('<?php print $base_url;?>Cajachica/reportemovimiento');
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
        <i class="fa fa-money"></i> Movimientos de Caja Chica
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>gastos">Movimientos de Caja Chica</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-danger">
              <div class="box-header with-border">

                <div class="col-md-12" style="margin-bottom: 10px;">

                  <!-- Caja -->
                  <div style="" class="form-group col-md-3">
                    <label for="lb_res">Caja</label>
                    <select id="cmb_caja" name="cmb_caja" class="form-control">
                    <?php 
                      if(@$cajas != NULL){ ?>
                      <?php } else { ?>
                      <option  value="" selected="TRUE">Seleccione Caja...</option>
                      <?php } 
                        if (count($cajas) > 0) {
                          foreach ($cajas as $obj): ?>
                              <option value="<?php  print $obj->id_caja; ?>" > <?php  print $obj->nom_caja; ?> </option>
                      <?php
                          endforeach;
                        }
                      ?>
                    </select>                                  
                  </div>

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
                      <input type="text" class="form-control pull-right validate[required]" id="hasta" name="hasta" value="<?php print  date("d/m/Y"); ?>" >
                      <span class="input-group-btn">
                      <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                      </span>
                    </div>
                  </div>


<!--                   <div class="col-md-6" style="margin-bottom: 0px; margin-top: 32px;">
                    <h4 style="margin-bottom: 0px; margin-top: 0px;"><div id="monto"> Disponible: <?php print number_format(@$resumen,2,",","."); ?></div></h4>
                  </div> 

                  <div class="col-md-12" style="margin-bottom: 0px; margin-top: 0px;">
                    <span><strong>Total Caja Chica: </strong><?php print number_format(@$montocaja,2,",",".");?></span><br>
                    <span><strong>Total Gastos: </strong><?php print number_format(@$mov->gastos,2,",","."); ?></span><br>
                    <span><strong>Total Compras: </strong><?php print number_format(@$mov->compras,2,",","."); ?></span>                   
                  </div> 
 -->
                </div>


                <div class="box-body">
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="box">
                        <div class="box-body table-responsive">
                          <table id="dataTableCaja" class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr >
                                <th>Fecha Apertura</th>
                                <th>Descripcion</th>
                                <th>Monto Apertura</th>
                                <th>Estado</th>
                                <th>Fecha Cierre</th>
                                <th>Monto Cierre</th>
                                <th>Acción</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                        <!-- /.box-body -->
                      </div>
                    </div>
                  </div>
                </div>

                <div  align="center" class="box-footer">
                </div>
              </div>
            </div>
          </div>
        </div>  
    </section>
</div>


