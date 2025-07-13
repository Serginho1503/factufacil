<?php
/* ------------------------------------------------
  ARCHIVO: Ventas.php
  DESCRIPCION: Contiene la vista principal del módulo de Ventas.
  FECHA DE CREACIÓN: 28/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Notas de Crédito'</script>";
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

      /* CARGA DE DATOS EN EL DATATABLE */
     tablevent=$('#dataTableNota').dataTable({
      rowCallback:function(row,data) {
        if(data["estatus"] != '1')
        {
          /*$($(row).find("td")[3]).css("background-color","red");*/
          $($(row)).css("background-color","#DD4B39");
        }
      },  
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "Notacredito/listadoDataNota",
        'columns': [
         
            {"data": "fecha"},
            {"data": "nro_documento"},
            {"data": "cliente"}, 
            {"data": "subtotal"},              
            {"data": "descuento"},
            {"data": "montoiva"},
            {"data": "total"},
            {"data": "ver"}
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

  /* ACTUALIZAR LISTADO DE VENTA POR RAGO DE FECHA */
  $('.actualiza').click(function(){
    var fhasta = $("#fhasta").val();
    var fdesde = $("#fdesde").val();
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Notacredito/tmp_nota_fecha');?>",
          data: { fdesde:fdesde, fhasta:fhasta }
        }).done(function (result) {
              $('#dataTableNota').DataTable().ajax.reload();
              actualiza_venta();
        }); 
  });

    function actualiza_venta(){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Notacredito/upd_monto_total",
            //data: { id: id },
            success: function(json) {
              var total = 0;

              if(json == null){
                total = 0;
              }else{
                total = json
              }
            
              $('#monto').html('<strong>$ '+total+'</strong>');
            }
        });

    }

    /* Adicionar Nota */
    $(document).on('click', '.add_nota', function(){
      location.replace("<?php print $base_url;?>Notacredito/agregar");
    });


    $(document).on('click', '.nota_print', function(){
      var id = $(this).attr('id');
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        ajax: {
          dataType: "html",
          type: "POST",
          data: {id: id}
        },
        href: "<?php echo base_url('Notacredito/imprimirnota');?>" 
      });        
              
    });

    /* ANULAR Nota */
    $(document).on('click', '.anular_nota', function(){
      var id = $(this).attr('id');
      if (confirm('Desea anular la Nota de Credito?')){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Notacredito/anular_nota",
            data: { id: id },
            success: function(json) {
              $('#dataTableNota').DataTable().ajax.reload();
              actualiza_venta();
            }
        });
      }  
    });

    /* Reporte  */
    $(document).on('click', '#reporte', function(){  
      var fhasta = $("#fhasta").val();
      var fdesde = $("#fdesde").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Notacredito/tmp_nota_fecha');?>",
        data: { fdesde:fdesde, fhasta:fhasta },
        success: function(json) {
          window.open('<?php print $base_url;?>Notacredito/reporte');
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
      <i class="fa fa-list-alt"></i> Listado de Notas de Crédito
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DE LOS PRODUCTOS A COMPRAR -->
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            
            <div id="buscrango" class="col-md-7">
              <div class="form-group col-md-6" style="margin-bottom: 0px; margin-top: 0px; padding-right:0px; ">
                <label for="" class="col-md-4">Desde</label>
                <div class="input-group col-md-8">
                  <input style="width:100px;" type="text" class="form-control text-center date start" id="fdesde" name="fdesde" value="<?php if (@$desde != NULL) { @$fec = str_replace('-', '/', @$desde); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print  date("d/m/Y"); } ?>">
                </div>
              </div>              
              <div class="form-group col-md-6" style="margin-bottom: 0px; margin-top: 0px; padding-right:0px; ">
                <label for="" class="col-md-4">Hasta</label>
                <div class="input-group col-md-8">
                  <input style="width:100px;" type="text" class="form-control text-center date end" id="fhasta" name="fhasta" value="<?php if (@$hasta != NULL) { @$fec = str_replace('-', '/', @$hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print  date("d/m/Y"); }  ?>">
                
                  <span class="input-group-btn">
                    <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                  </span>

                </div>
              </div>              
            </div>

            <div class="col-md-2" style="margin-bottom: 0px; margin-top: 0px;">
                <h4 style="margin-bottom: 0px; margin-top: 0px;"><div id="monto"> Monto: <?php print number_format(@$monto,2,",","."); ?></div></h4>
            </div>  

            <div class="pull-right"> 
              <a id="reporte" class="btn bg-light-blue color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-list-alt"></i> Reporte </a>
              <a class="btn bg-orange color-palette btn-grad add_nota" data-original-title="" title=""><i class="fa fa-shopping-bag"></i> Añadir </a>
              
            </div>

          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div id="upd_tbventa" class="box-body table-responsive">

                    <table id="dataTableNota" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr >
                            <th class="text-center col-md-1">Fecha</th>  
                            <th class="text-center col-md-1">Nro.Nota</th>
                            <th>Cliente</th>
                            <th class="text-center col-md-1">Subtotal</th>                            
                            <th class="text-center col-md-1">Descuento</th>
                            <th class="text-center col-md-1">Monto IVA</th>
                            <th class="text-center col-md-1">Total</th>
                            <th class="text-center col-md-1">Acción</th>
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

