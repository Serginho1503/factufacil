<?php
/* ------------------------------------------------
  ARCHIVO: Ventas.php
  DESCRIPCION: Contiene la vista principal del módulo de Ventas.
  FECHA DE CREACIÓN: 28/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
  print "<script>document.title = '$nombresistema - Productos mas Vendidos'</script>";
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

    $('#dataTableProVent').dataTable({
      'language': { 'url': base_url + 'public/json/language.spanish.json' },
      'ajax': "listadoDataProVent",
      'ordering': false,
      'columns': [
        {"data": "codbar"},
        {"data": "codaux"},
        {"data": "producto"},
        {"data": "precio"},  
        {"data": "cantidad"},  
        {"data": "total"},
        {"data": "categoria"}
      ]
    });  

    $('.actualizav').click(function(){
      var fhasta = $("#fhasta").val();
      var fdesde = $("#fdesde").val();
      var horah = $("#hhasta").val();
      var horad = $("#hdesde").val(); 
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/tmp_mventa_fecha');?>",
        data: { fdesde:fdesde, horad:horad, fhasta:fhasta, horah:horah }
      }).done(function (result) {
        $('#dataTableProVent').DataTable().ajax.reload();
        actualiza_monto();
      }); 
    });

    function actualiza_monto(){
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Facturar/upd_monto",
        success: function(json) {
          var total = 0;
          if(json == null){ total = 0; }
          else{ total = json }
          $('#monto').html('<strong>$ '+total+'</strong>');
        }
      });
    }




}); 


</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-list-alt"></i> Listado de Productos más Vendidos
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>facturar/ventas">Ventas</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DE LOS PRODUCTOS A COMPRAR -->
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">

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
                  <button type="button" class="btn btn-block btn-success actualizav"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="col-md-2" style="margin-bottom: 0px; margin-top: 28px;">
                <h4 style="margin-bottom: 0px; margin-top: 0px;"><div id="monto"> Monto: <?php print number_format(@$monto,2,",","."); ?></div></h4>
              </div>             

            <div class="pull-right"> 

              <a id="rpt_ventamas" class="btn btn-success btn-grad" style="margin-bottom: 0px; margin-top: 23px;" target="_blank" href="<?php print $base_url;?>facturar/reporteprodmasvendidoXLS" data-original-title="" title=""><i class="fa fa-file-excel-o"></i> Exportar a Excel </a>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div id="upd_tbventa" class="box-body table-responsive">

                    <table id="dataTableProVent" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr >
                            <th class="text-center col-md-1">CodBar</th>  
                            <th class="text-center col-md-1">Codaux</th>
                            <th>Producto</th>
                            <th class="text-center col-md-1">Precio</th>
                            <th class="text-center col-md-1">Cantidad</th>
                            <th class="text-center col-md-1">Total</th>
                            <th class="text-center col-md-1">Categoria</th>
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


          <div   align="center" class="box-footer">
            <hr class="linea"> 
              <div class="row" style="margin-top:20px">



              </div><!--/row-->



          </div>
        </div>

      </div>           
    </div>
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

