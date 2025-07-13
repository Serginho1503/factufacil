<?php
/* ------------------------------------------------
  ARCHIVO: reporte_ats.php
  DESCRIPCION: Contiene la vista de reporte de ATS.
  
 * 
  ------------------------------------------------ */
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Reporte de ATS'</script>";
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
    $('#dataTableCompra').dataTable({
      'language': {
        'url': base_url + 'public/json/language.spanish.json'
      },
      'ajax': "listado_ats_compra",
      'columns': [
        {"data": "codsustento"},
        {"data": "codtipoid"},
        {"data": "idproveedor"},
        {"data": "nomproveedor"},
        {"data": "tipodoc"},
        {"data": "fecharegistro"},
        {"data": "codestabfac"},
        {"data": "codptoemifac"},
        {"data": "secuencialfac"},
        {"data": "fechaemision"},
        {"data": "nro_autorizacion"},
        {"data": "basenograbaiva"},
        {"data": "baseimponible"},
        {"data": "baseimpgrav"},
        {"data": "montoice"},
        {"data": "montoiva"},
        {"data": "retiva10"},
        {"data": "retiva20"},
        {"data": "retiva30"},
        {"data": "retiva50"},
        {"data": "retiva70"},
        {"data": "retiva100"},
        {"data": "codretrenta"},
        {"data": "baseretrenta"},
        {"data": "porciento_retencion_renta"},
        {"data": "valor_retencion_renta"},
        {"data": "codestabret"},
        {"data": "codptoemiret"},
        {"data": "secuencialret"},
        {"data": "autorizacionret"},
        {"data": "fecha_retencion"}
      ]
    });

    $('#dataTableVenta').dataTable({
      'language': {
        'url': base_url + 'public/json/language.spanish.json'
      },
      'ajax': "listado_ats_venta",
      'columns': [
        {"data": "codsri_venta"},
        {"data": "ident_cliente"},
        {"data": "nom_cliente"},
        {"data": "parteRel"},
        {"data": "tipocomprobante"},
        {"data": "numeroComprobantes"},
        {"data": "baseNoGraIva"},
        {"data": "baseImponible"},
        {"data": "baseImpGrav"},
        {"data": "montoiva"},
        {"data": "valorRetIva"},
        {"data": "valorRetRenta"}
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
    var empresa = $("#cmb_empresa").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Reporte/tmp_rpt_ats');?>",
        data: { hasta: hasta, desde: desde, empresa: empresa }
      }).done(function (result) {
        $('#dataTableCompra').DataTable().ajax.reload();
        $('#dataTableVenta').DataTable().ajax.reload();
      }); 
  });



  
  
}); 



</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-line-chart"></i> Reporte de ATS
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

            <!-- Empresa -->
            <div style="" class="form-group col-md-4">
              <div class="form-group col-md-3"> 
                <label for="lb_res">Empresa</label> 
              </div>
              <div class="form-group col-md-9"> 
                <select id="cmb_empresa" name="cmb_empresa" class="form-control">
                <?php 
                  if(@$empresas != NULL){ 
                    if (count($empresas) > 0) {
                      foreach ($empresas as $obj): 
                ?>
                             <option value="<?php  print $obj->id_emp; ?>" > <?php  print $obj->nom_emp; ?> </option>
                <?php
                      endforeach;
                    }
                  }  
                ?>
                </select>                                  
              </div>
            </div>

            <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
              <label class="col-sm-3 control-label text-left" style="padding-left: 0px;">Desde</label>
              <div class="input-group date col-sm-7">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" class="form-control pull-right validate[required]" id="desde" name="desde" value="<?php print  date("d/m/Y"); ?>">
              </div>
            </div> 

            <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
              <label class="col-sm-3 control-label text-left" style="padding-left: 0px;">Hasta</label>
              <div class="input-group date col-sm-9">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" class="form-control pull-right validate[required]" id="hasta" name="hasta" value="<?php print  date("d/m/Y"); ?>">
                <span class="input-group-btn">
                <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                </span>
              </div>
            </div>  

            <div class="form-group col-md-2" style="margin-bottom: 0px; margin-top: 0px;">
             <div class="pull-right"> 
              <a class="btn bg-green-active color-palette btn-grad" target="_blank" style="margin-bottom: 0px; margin-top: 0px;" href="<?php print $base_url;?>reporte/generar_ats_xml" data-original-title="" title=""><i class="fa fa-file-excel-o" aria-hidden="true"></i> Generar XML </a>
             </div>
            </div> 


          </div>

          <div class="box-body">

            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#tabcompra" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> COMPRAS</a></li>                            
                <li ><a href="#tabventa" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> VENTAS</a></li>                            
              </ul>

              <div class="tab-content">

                <div class="tab-pane active" id="tabcompra">

                  <div class="form-group col-md-12" style="margin-bottom: 0px; margin-top: 0px;">
                   <div class="pull-right"> 
                    <a class="btn bg-green-active color-palette btn-grad" target="_blank" style="margin-bottom: 0px; margin-top: 0px;" href="<?php print $base_url;?>reporte/reportecompraATS_XLS" data-original-title="" title=""><i class="fa fa-file-excel-o" aria-hidden="true"></i> Reporte de Compras </a>
                   </div>
                  </div> 

                  <div class="row">
                    <div class="col-xs-12">
                      <div class="box">
                        <div id="upd_tbventa" class="box-body table-responsive">

                          <table id="dataTableCompra" class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr >
                                <th class="text-center col-md-1">Sustento</th>
                                <th class="text-center col-md-1">Tipo ID</th>
                                <th class="text-center col-md-1">ID Proveedor</th>
                                <th class="text-center col-md-1">Proveedor</th>
                                <th class="text-center col-md-1">Tipo Doc</th>
                                <th class="text-center col-md-1">Fecha Reg.</th>
                                <th class="text-center col-md-1">Estab.Fac</th>
                                <th class="text-center col-md-1">PtoEmi.Fac</th>
                                <th class="text-center col-md-1">Secuencial</th>
                                <th class="text-center col-md-1">Fecha Emi.</th>
                                <th class="text-center col-md-1">Autoriz.Fac</th>
                                <th class="text-center col-md-1">BaseNoGrava</th>
                                <th class="text-center col-md-1">BaseImponible</th>
                                <th class="text-center col-md-1">BaseImpGrava</th>
                                <th class="text-center col-md-1">Monto ICE</th>
                                <th class="text-center col-md-1">Monto IVA</th>
                                <th class="text-center col-md-1">RetIva10%</th>
                                <th class="text-center col-md-1">RetIva20%</th>
                                <th class="text-center col-md-1">RetIva30%</th>
                                <th class="text-center col-md-1">RetIva50%</th>
                                <th class="text-center col-md-1">RetIva70%</th>
                                <th class="text-center col-md-1">RetIva100%</th>
                                <th class="text-center col-md-1">CodigoRetRenta</th>
                                <th class="text-center col-md-1">BaseRetRenta</th>
                                <th class="text-center col-md-1">%RetRenta</th>
                                <th class="text-center col-md-1">ValorRetRenta</th>
                                <th class="text-center col-md-1">Estab.Ret</th>
                                <th class="text-center col-md-1">PtoEmi.Ret</th>
                                <th class="text-center col-md-1">Secuenc.Ret</th>
                                <th class="text-center col-md-1">Autoriz.Ret</th>
                                <th class="text-center col-md-1">Fecha Ret</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                       

                        </div>  <!-- /.box-body -->
                      </div><!-- /.box -->
                    </div>
                  </div><!-- /row -->

                </div>  <!-- Tab Compra -->

                <div class="tab-pane" id="tabventa">

                  <div class="form-group col-md-12" style="margin-bottom: 0px; margin-top: 0px;">
                   <div class="pull-right"> 
                    <a class="btn bg-green-active color-palette btn-grad" target="_blank" style="margin-bottom: 0px; margin-top: 0px;" href="<?php print $base_url;?>reporte/reporteventaATS_XLS" data-original-title="" title=""><i class="fa fa-file-excel-o" aria-hidden="true"></i> Reporte de Ventas </a>
                   </div>
                  </div> 

                  <div class="row">
                    <div class="col-xs-12">
                      <div class="box">
                        <div id="upd_tbventa" class="box-body table-responsive">

                          <table id="dataTableVenta" class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr >
                                <th class="text-center col-md-1">Tipo ID</th>
                                <th class="text-center col-md-1">ID Cliente</th>
                                <th class="text-center col-md-1">Cliente</th>
                                <th class="text-center col-md-1">Relacionado</th>
                                <th class="text-center col-md-1">Tipo Comprob</th>
                                <th class="text-center col-md-1">Cant.Comprob</th>
                                <th class="text-center col-md-1">baseNoGraIva</th>
                                <th class="text-center col-md-1">baseImponible</th>
                                <th class="text-center col-md-1">baseImpGrav</th>
                                <th class="text-center col-md-1">montoIVA</th>
                                <th class="text-center col-md-1">valorRetIva</th>
                                <th class="text-center col-md-1">valorRetRenta</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                       

                        </div>  <!-- /.box-body -->
                      </div><!-- /.box -->
                    </div>
                  </div><!-- /row -->

                </div>  <!-- Tab Venta -->

               </div>  <!-- Tab Control --> 
              </div>  <!-- Nav Tab Control -->                 

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

