<?php
/* ------------------------------------------------
  ARCHIVO: Ventas.php
  DESCRIPCION: Contiene la vista principal del módulo de Ventas.
  FECHA DE CREACIÓN: 28/08/2017
 * 
  ------------------------------------------------ */
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Reporte de Utilidad'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<style type="text/css">
  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 
</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {
    $('#dataTableUtilidad').dataTable({
      'language': {
        'url': base_url + 'public/json/language.spanish.json'
      },
      'ajax': "listadoUtilidad",
      'columns': [
        {"data": "fecha"},
        {"data": "categoria"},
        {"data": "nro_factura"},
        {"data": "nro_ident"},
        {"data": "nom_cliente"},
        {"data": "pro_nombre"},
        {"data": "cantidad"},
        {"data": "costo"},
        {"data": "precio"},
        {"data": "costo_total"},
        {"data": "montoiva"},
        {"data": "precioiva"},
        {"data": "descuento"},
        {"data": "precio_total"},
        {"data": "utilidad_bruta"},
        {"data": "comision"},
        {"data": "utilidad_total"},
        {"data": "utilidad_porc"}
      ]
    });

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

  $('.actualiza').click(function(){
    var hasta = $("#hasta").val();
    var desde = $("#desde").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Reporte/tmp_rpt_utilidad');?>",
        data: { hasta: hasta, desde: desde }
      }).done(function (result) {
        $('#dataTableUtilidad').DataTable().ajax.reload();
      }); 
  });

rpt_utilidad

  
  
}); 



</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-line-chart"></i> Reporte de Utilidad
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

            <div class="pull-right"> 
              <a id="rpt_utilidad" class="btn bg-green-active color-palette btn-grad" target="_blank" style="margin-bottom: 0px; margin-top: 23px;" href="<?php print $base_url;?>reporte/reporteutilidadXLS" data-original-title="" title=""><i class="fa fa-file-excel-o" aria-hidden="true"></i> Reporte de Utilidades </a>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div id="upd_tbventa" class="box-body table-responsive">

                    <table id="dataTableUtilidad" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr >
                          <th class="text-center col-md-1">Fecha</th>
                          <th class="text-center col-md-1">Tipo</th>
                          <th class="text-center col-md-1">#Documento</th>
                          <th class="text-center col-md-1">C.I./R.U.C.</th>
                          <th class="text-center ">Cliente</th>
                          <th class="text-center col-md-1">Producto</th>
                          <th class="text-center col-md-1">Cantidad</th>
                          <th class="text-center col-md-1">Costo Unit</th>
                          <th class="text-center col-md-1">Precio</th>
                          <th class="text-center col-md-1">Costo Total</th>
                          <th class="text-center col-md-1">Monto Iva</th>
                          <th class="text-center col-md-1">Precio Iva</th>
                          <th class="text-center col-md-1">Descuento</th>
                          <th class="text-center col-md-1">Precio Total</th>
                          <th class="text-center col-md-1">Utilidad Bruta</th>
                          <th class="text-center col-md-1">Comisión</th>
                          <th class="text-center col-md-1">Utilidad Neta</th>
                          <th class="text-center col-md-1">Utilidad %</th>
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

