<?php
/* ------------------------------------------------
  ARCHIVO: guiaremision.php
  DESCRIPCION: Contiene la vista principal del módulo de guia remision.
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Guías de Remisión'</script>";
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
     tablevent=$('#dataTableObj').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "guiaremision/listadoDataGuia",
        'columns': [
            {"data": "ver"},        
            {"data": "fecha"},
            {"data": "nro_documento"},
            {"data": "transportista"}, 
            {"data": "puntopartida"},              
            {"data": "inicio"},
            {"data": "fin"},
            {"data": "comprobanteventa"},
            {"data": "destinatario"}
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
    var sucursal = $("#cmb_sucursal").val();
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('guiaremision/tmp_guia_fecha');?>",
          data: { fdesde:fdesde, fhasta:fhasta, sucursal: sucursal }
        }).done(function (result) {
              $('#dataTableObj').DataTable().ajax.reload();
        }); 
  });

    /* Adicionar Nota */
    $(document).on('click', '.add_guia', function(){
      location.replace("<?php print $base_url;?>guiaremision/agregar");
    });


    $(document).on('click', '.guia_print', function(){
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
        href: "<?php echo base_url('guiaremision/imprimirguia');?>" 
      });        
              
    });

    /* modificar guia */
    $(document).on('click', '.edi_guia', function(){
      var id = $(this).attr('id');
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "guiaremision/tmp_guia",
          data: { id: id },
          success: function(json) {
            location.replace("<?php print $base_url;?>guiaremision/editar");
          }
      });
    });

    /* Eliminar guia */
    $(document).on('click', '.del_guia', function(){
      var id = $(this).attr('id');
      var sec = $(this).attr('name');
      if (confirm("Desea eliminar la Guia de Remision " + sec + " ?")){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "guiaremision/del_guiaremision",
            data: { id: id },
            success: function(json) {
              $('#dataTableObj').DataTable().ajax.reload();
            }
        });
      }  
    });

    /* Reporte  */
    $(document).on('click', '#reporte', function(){  
      var fhasta = $("#fhasta").val();
      var fdesde = $("#fdesde").val();
      var sucursal = $("#cmb_sucursal").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('guiaremision/tmp_guia_fecha');?>",
        data: { fdesde:fdesde, fhasta:fhasta, sucursal: sucursal },
        success: function(json) {
          window.open('<?php print $base_url;?>guiaremision/reporte');
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
      <i class="fa fa-list-alt"></i> Listado de Guías de Remisión
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>guiaremision">Guía de Remisión</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DE LOS PRODUCTOS A COMPRAR -->
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            
            <div id="buscrango" class="col-md-9">

              <!-- SUCURSAL  -->
              <div style="" class="form-group col-md-5">
                <label for="lb_res" class="col-md-3">Sucursal</label>
                <div class="col-md-9">
                <select id="cmb_sucursal" name="cmb_sucursal" class="form-control ">
                <?php 
                  if(@$sucursales != NULL){ ?>
                    <option  value="0" selected="TRUE">Todas las sucursales</option>
                  <?php } else { ?>
                  <?php } 
                    if (count($sucursales) > 0) {
                      foreach ($sucursales as $obj):
                          if(@$sucursalseleccionada != NULL){
                              if($obj->id_sucursal == $sucursalseleccionada){ ?>
                                   <option value="<?php  print $obj->id_sucursal; ?>" selected="TRUE"> <?php  print $obj->nom_sucursal; ?> </option>
                                  <?php
                              }else{ ?>
                                  <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                                  <?php
                              }
                          }else{ ?>
                              <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                              <?php
                              }   ?>
                          <?php
                      endforeach;
                    }
                  ?>
                </select>                                  
                </div>
              </div>


              <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px; padding-right:0px; ">
                <label for="" class="col-md-4">Desde</label>
                <div class="input-group col-md-8">
                  <input style="width:100px;" type="text" class="form-control text-center date start" id="fdesde" name="fdesde" value="<?php if (@$desde != NULL) { @$fec = str_replace('-', '/', @$desde); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print  date("d/m/Y"); } ?>">
                </div>
              </div>              
              <div class="form-group col-md-4" style="margin-bottom: 0px; margin-top: 0px; padding-right:0px; ">
                <label for="" class="col-md-4">Hasta</label>
                <div class="input-group col-md-8">
                  <input style="width:100px;" type="text" class="form-control text-center date end" id="fhasta" name="fhasta" value="<?php if (@$hasta != NULL) { @$fec = str_replace('-', '/', @$hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print  date("d/m/Y"); }  ?>">
                
                  <span class="input-group-btn">
                    <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                  </span>

                </div>
              </div>              
            </div>

            <div class="pull-right"> 
              <a id="reporte" class="btn bg-light-blue color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-list-alt"></i> Reporte </a>
              <a class="btn bg-orange color-palette btn-grad add_guia" data-original-title="" title=""><i class="fa fa-shopping-bag"></i> Añadir </a>
              
            </div>

          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div id="upd_tbventa" class="box-body table-responsive">

                    <table id="dataTableObj" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr >
                            <th class="text-center col-md-1">Acción</th>
                            <th class="text-center col-md-1">Fecha</th>  
                            <th class="text-center col-md-1">Nro.Guia</th>
                            <th class="text-center col-md-1">Transportista</th>
                            <th class="text-center col-md-1">Punto Partida</th>                            
                            <th class="text-center col-md-1">Inicio</th>
                            <th class="text-center col-md-1">Fin</th>
                            <th class="text-center col-md-1">Comprobante Venta</th>
                            <th class="text-center col-md-1">Destinatario</th>
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

