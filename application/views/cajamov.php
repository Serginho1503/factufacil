<?php
/* ------------------------------------------------
  ARCHIVO: Area.php
  DESCRIPCION: Contiene la vista principal del móvimiento de caja.
  FECHA DE CREACIÓN: 04/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Movimientos'</script>";
date_default_timezone_set("America/Guayaquil");

?>

<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/css/jquery.timepicker.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/css/bootstrap-datepicker.standalone.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/lib/pikaday.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/lib/jquery.ptTimeSelect.css" />

<style type="text/css">
  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 
  .dropdown-menu > li > a {
    color: #fff;
  } 
</style>

<script src="<?php print $base_url; ?>assets/plugins/datepair/js/jquery.min.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/js/jquery.timepicker.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/js/bootstrap-datepicker.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/pikaday.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/jquery.ptTimeSelect.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/moment.min.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/site.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/dist/datepair.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/dist/jquery.datepair.js"></script>

<script type='text/javascript' language='javascript'>

  var jq = $.noConflict();
  jq(document).ready(function () {

    jq('#buscrango .time').timepicker({
        'showDuration': true,
        'timeFormat': 'H:i:s'
    });

    jq('#buscrango .date').datepicker({
        'format': 'dd/mm/yyyy',
        'autoclose': true
    });

    jq('#buscrango').datepair();

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

    /* ACTUALIZAR LISTADO DE VENTA POR RAGO DE FECHA */
    $('.actualiza').click(function(){
          var fhasta = $("#fhasta").val();
          var fdesde = $("#fdesde").val();
          var horah = $("#hhasta").val();
          var horad = $("#hdesde").val(); 
          $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('Cajamov/tmp_fecha');?>",
            data: { fdesde:fdesde, horad:horad, fhasta:fhasta, horah:horah }
          }).done(function (result) {
                $('#dataTableCajamov').DataTable().ajax.reload();
          }); 
    });


    /* CARGA DEL DATATABLE (LISTADO) */
    var dt_usu =  $('#dataTableCajamov').dataTable({
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
        'ajax': "Cajamov/listadoDataCajamov",
        'columns': [
            {"data": "ver"},
            {"data": "caja"},
            {"data": "fechaapertura"},
            {"data": "montoapertura"},
            {"data": "fechacierre"},
            {"data": "ventastotales"},
            {"data": "efectivo"},
            {"data": "noefectivo"},
            {"data": "egreso"},
            {"data": "saldo"},
            {"data": "sobrante"},
            {"data": "faltante"}
        ]
    });

    $(document).on('click', '.edit_cajamov', function(){
      id = $(this).attr('id');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Cajamov/tmp_cajamov');?>",
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
            href: "<?php echo base_url('Cajamov/upd_aperturacaja');?>",
            afterClose: function(){
              $('#dataTableCajamov').DataTable().ajax.reload();
            }
          });
        }
      });
    });  

    $(document).on('click', '.pdf_cajamov', function(){
      var id = $(this).attr('id'); 
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Cajamov/tmp_cajamov",
        data: { id: id },
        success: function(json) {
          $.fancybox.open({
            type:'iframe',
            width: 800,
            height: 550,
            ajax: {
              dataType: "html",
              type: "POST",
              data: {id: id}
            },
            href: base_url + 'Cajamov/resumencaja_pdf' 
          });
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
        <i class="fa fa-globe" aria-hidden="true"></i> Movimientos de Caja 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>cajamov">Movimientos de Caja</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Movimientos </h3>

                      <div id="buscrango">
                        <div class="form-group col-md-1 " style="margin-bottom: 0px; margin-top: 0px; padding-right:0px; margin-right:30px;">
                          <label for="">Desde</label>
                          <div class="input-group">
                            <input style="width:100px;" type="text" class="form-control text-center date start" id="fdesde" name="fdesde" value="<?php if (@$desde != NULL) { @$fec = str_replace('-', '/', @$desde); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print  date("d/m/Y"); } ?>">
                          </div>
                        </div>              
                        <div class="form-group col-md-1 " style="margin-bottom: 0px; margin-top: 0px; padding-left:0px; padding-right:0px; margin-right:30px;">              
                          <label for="">Hora</label>
                          <div class="input-group">
                            <input style="width:100px;" type="text" class="form-control text-center time start" id="hdesde" name="hdesde" value="00:00:00">
                          </div>
                        </div> 
                        <div class="form-group col-md-1 " style="margin-bottom: 0px; margin-top: 0px; padding-left:0px; padding-right:0px; margin-right:30px;">
                          <label for="">Hasta</label>
                          <div class="input-group">
                            <input style="width:100px;" type="text" class="form-control text-center date end" id="fhasta" name="fhasta" value="<?php if (@$hasta != NULL) { @$fec = str_replace('-', '/', @$hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print  date("d/m/Y"); }  ?>">
                          </div>
                        </div>              
                        <div class="form-group col-md-1 " style="margin-bottom: 0px; margin-top: 0px; padding-left:0px; padding-right:0px; margin-right:30px;">              
                          <label for="">Hora</label>
                          <div class="input-group">
                            <input style="width:100px;" type="text" class="form-control text-center time end" id="hhasta" name="hhasta" value="23:59:59">
                          </div>
                        </div> 
                        <div class="col-md-1" style="margin-bottom: 0px; margin-top: 24px; padding-left:0px; padding-right:0px; width: 50px;">
                          <button type="button" class="btn btn-block btn-success actualiza"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                      </div>

                    </div>
                    <div class="box-body">

                      <div class="row">
                        <!-- <div class="col-xs-2 ">
                        </div>
 -->
                        <div class="col-xs-12">
                          <div class="box-body">
                            <div class="box-body table-responsive">
                              <table id="dataTableCajamov" class="table table-bordered table-striped">
                                <thead>
                                  <tr >                                      
                                      <th>Acción</th>
                                      <th>Caja</th>
                                      <th>Fecha <br> Apertura</th>
                                      <th>Monto <br> Apertura</th>
                                      <th>Fecha <br> Cierre</th>
                                      <th>Ventas <br> Totales</th>
                                      <th>Ingreso <br> Efectivo</th>
                                      <th>Ingreso <br> NO Efectivo</th>
                                      <th>Egresos</th>
                                      <th>Saldo</th>
                                      <th>Sobrante</th>
                                      <th>Faltante</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        <!--<div class="col-xs-2 ">
                        </div>-->

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

