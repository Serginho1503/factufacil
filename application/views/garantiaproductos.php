<?php
/* ------------------------------------------------
  ARCHIVO: garantiadevolucion.php
  DESCRIPCION: Contiene la vista principal del módulo de devolucion por garantia.
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Productos en Garantía'</script>";
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


      /* CARGA DE DATOS EN EL DATATABLE */
    $('#dataTableObj').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "listadoDataProdgarantia",
        'columns': [
            {"data": "fecha"},
            {"data": "nro_documento"},
            {"data": "cedula"},
            {"data": "cliente"},
            {"data": "serie"},              
            {"data": "producto"}, 
            {"data": "precio"},              
            {"data": "entregado"},              
            {"data": "dias"},              
            {"data": "vencimiento"}              
        ]

      });


  /* ACTUALIZAR LISTADO DE VENTA POR RAGO DE FECHA */
  $('.actualiza').click(function(){
      var cliente = $("#cmb_cliente").val();
      var garantia = $("#garantia").prop('checked');
      if (garantia == true) { garantia = 1; } else { garantia = 0; }
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('garantia/tmp_garprod_cliente');?>",
        data: { cliente: cliente, garantia: garantia }
      }).done(function (result) {
            $('#dataTableObj').DataTable().ajax.reload();
      }); 
  });

  
   
});



</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-list-alt"></i> Listado de Productos en Garantía
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content" >
    <div class="row" >
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            
            <div class="col-md-4" style="margin-bottom: 0px; margin-top: 0px; padding:0px; ">
              <label for="" class="col-md-3">Clientes</label>
              <div class="input-group col-md-9" >
                <select id="cmb_cliente" name="cmb_cliente" class="form-control actualiza">
                    <option  value="0" > TODOS LOS CLIENTES</option> 
                    <?php
                    if (count($clientes) > 0) {
                      foreach ($clientes as $obj):
                          if(@$clientes != NULL){
                              if($obj->id_cliente == $cliente){ ?>
                                  <option  value="<?php  print $obj->id_cliente; ?>" selected="TRUE"><?php  print $obj->nom_cliente; ?></option> 
                                  <?php
                              }else{ ?>
                                  <option value="<?php  print $obj->id_cliente; ?>"> <?php  print $obj->nom_cliente; ?> </option>
                                  <?php
                              }
                          }else{ ?>
                              <option value="<?php  print $obj->id_cliente; ?>"> <?php  print $obj->nom_cliente; ?> </option>
                              <?php
                              }                                 
                      endforeach;
                    }
                    ?>                    
                </select>          
              </div>  
            </div>

            <div class="col-md-4 col-md-offset-2 actualiza" style="padding-left: 0px; padding-right: 0px;">
                <label class="col-md-12"><input type="checkbox" name="garantia" id="garantia" class="minimal-red" <?php if(@$garantia != NULL){ if(@$garantia == 1){ print "checked='' ";} }?> > Mostrar solo Productos en Garantía</label>
            </div> 

            <div class="pull-right"> 
              <a class="btn bg-light-blue color-palette btn-grad " href="<?php print $base_url;?>garantia/reporteproductosgarantiaXLS" data-original-title="" title=""><i class="fa fa-list-alt"></i> Reporte </a>             
            </div>

          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div class="box-body table-responsive">

                    <table id="dataTableObj" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr >
                            <th class="text-center col-md-1">Fecha</th>  
                            <th class="text-center col-md-1">Factura</th>
                            <th class="text-center col-md-1">Identificación</th>
                            <th class="text-center col-md-1">Cliente</th>                            
                            <th class="text-center col-md-1">Serie</th>                            
                            <th class="text-center col-md-1">Producto</th>                            
                            <th class="text-center col-md-1">Precio</th>                            
                            <th class="text-center col-md-1">Entregado</th>                            
                            <th class="text-center col-md-1">Dias</th>                            
                            <th class="text-center col-md-1">Vencimiento</th>                            
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

