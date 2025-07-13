<?php
/* ------------------------------------------------
  ARCHIVO: gastos_retencion.php
  DESCRIPCION: Contiene la vista principal del módulo de Compra.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Gastos Retenciones'</script>";
date_default_timezone_set("America/Guayaquil");

?>
<style type="text/css">
  .form-control{
    font-size: 12px;
    height: 28px;
  }

  .linea{
    border-width: 2px 0 0;
    margin-bottom: 2px;
    margin-top: 2px;
    border-color: currentcolor currentcolor;
  } 

  .pago{
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05) inset;
    margin-bottom: 20px;
    min-height: 20px;
    padding: 19px;  
    margin-left: 20px;  
  }

  .calmonto{
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05) inset;
    margin-bottom: 20px;
    min-height: 20px;
    padding: 19px;  
    margin-right: 20px;     
  }

  #tpcredito{
    display: none; 
  }  

  .form-group {
      margin-bottom: 5px;
  }

</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

    $('#fecha_ret').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
    $("#fecha_ret").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });

    /* CARGA DE DATOS EN EL DATATABLE */
   tablecomp=$('#dataTableRet').dataTable({
    'language':{  "lengthMenu":"Mostrar _MENU_ registros por página.",
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
      'ajax': "listadoRetGastos",
      'columns': [
          {"data": "ver"},
          {"data": "concepto"},
          {"data": "basenoiva"},
          {"data": "baseiva"},  
          {"data": "por100retrenta"},  
          {"data": "valorretrenta"}  
      ]
    });

    $(document).on('click', '.add_conceptoretencion', function(){
      var subtotalconiva = "<?php if(@$comp != NULL){ print $comp->subtotaldesc; } else {print 0;}?>";
      var subtotalsiniva = "<?php if(@$comp != NULL){ print $comp->subtotalivacerodesc; } else {print 0;}?>";
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        ajax: {
           dataType: "html",
           type: "POST",
           data: {subtotalconiva: subtotalconiva, subtotalsiniva:subtotalsiniva}
        },
        href: "<?php echo base_url('Gastos/add_retencion');?>" 
      });
    });    

    $(document).on('click', '.ret_upd', function(){
      var id = $(this).attr('id');
      var subtotalconiva = "<?php if(@$comp != NULL){ print $comp->subtotaldesc; } else {print 0;}?>";
      var subtotalsiniva = "<?php if(@$comp != NULL){ print $comp->subtotalivacerodesc; } else {print 0;}?>";
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        ajax: {
           dataType: "html",
           type: "POST",
           data: {id: id, subtotalconiva: subtotalconiva, subtotalsiniva:subtotalsiniva}
        },
        href: "<?php echo base_url('Gastos/editar_retencion');?>" 
      });
    });    

    $(document).on('click', '.ret_del', function(){
      var subtotalconiva = "<?php if(@$comp != NULL){ print $comp->subtotaldesc; } else {print 0;}?>";
      var subtotalsiniva = "<?php if(@$comp != NULL){ print $comp->subtotalivacerodesc; } else {print 0;}?>";
      var subtotal = parseFloat(subtotalconiva);
      subtotal += parseFloat(subtotalsiniva);
      var id = $(this).attr('id');
      var str = $(this).attr('name');
      if (confirm("Desea eliminar el concepto de retencion (" + str + ")?")){
        $.ajax({
             type: "POST",
             dataType: "json",
             url: base_url + "Gastos/eliminar_retencionrenta",
             data: {id: id},
             success: function(json) {
                $('#dataTableRet').DataTable().ajax.reload();
                $("#btn_addcptoret").attr("disabled", false);
                if (parseFloat(json.totalbaseretenido) >= subtotal) {
                  $("#btn_addcptoret").attr("disabled", true);
                } else {
                  $("#btn_addcptoret").attr("disabled", false);
                }
                $("#retenidorenta").val(json.totalretenido);

                actualizatotalretenido();
             }   
        });

      }

    });    

    $(document).on('click', '.upd_retencion', function(){
      var txt_id_comp_ret = $("#txt_id_comp_ret").val();
      /*var txt_factura = $("#txt_factura").val();*/
      var txt_autorizacion = $("#txt_autorizacion").val();
      var fecha_ret = $("#fecha_ret").val();
      var txt_retiva10 = $("#txt_retiva10").val();
      var txt_retiva20 = $("#txt_retiva20").val();
      var txt_retiva30 = $("#txt_retiva30").val();
      var txt_retiva50 = $("#txt_retiva50").val();
      var txt_retiva70 = $("#txt_retiva70").val();
      var txt_retiva100 = $("#txt_retiva100").val();

      var txt_factura = $("#txt_factura").val();
      var cmb_punto = $("#cmb_punto").val();

      if (confirm("Desea guardar la retencion del gasto?")){
        $.ajax({
             type: "POST",
             dataType: "json",
             url: base_url + "Gastos/guardar_retencion",
             data: {txt_id_comp_ret: txt_id_comp_ret, txt_autorizacion: txt_autorizacion, fecha_ret: fecha_ret,
                    txt_retiva10: txt_retiva10, txt_retiva20: txt_retiva20, txt_retiva30: txt_retiva30,
                    txt_retiva50: txt_retiva50, txt_retiva70: txt_retiva70, txt_retiva100: txt_retiva100,
                    txt_factura: txt_factura, cmb_punto: cmb_punto},
             success: function(json) {
                if (parseInt(json) == 1) {
                   location.replace("<?php print $base_url ?>gastos");
                } else {
                   alert("Error de conexión");
                }
             }
        });
      }

    });    

    $(document).on('click', '.del_retencion', function(){
      var id = $("#txt_id_comp_ret").val();
      if (confirm("Desea eliminar la retencion del gasto?")){
        $.ajax({
             type: "POST",
             dataType: "json",
             url: base_url + "Gastos/eliminar_retencion",
             data: {id: id},
             success: function(json) {
                if (parseInt(json) == 1) {
                   location.replace("<?php print $base_url ?>gastos");
                } else {
                   alert("Error de conexión");
                }
             }
        });
      }

    });    

    $(document).on('click', '.btnguardardetalle', function(){ 
        var subtotalconiva = "<?php if(@$comp != NULL){ print $comp->subtotaldesc; } else {print 0;}?>";
        var subtotalsiniva = "<?php if(@$comp != NULL){ print $comp->subtotalivacerodesc; } else {print 0;}?>";
        var subtotal = parseFloat(subtotalconiva);
        subtotal += parseFloat(subtotalsiniva);

        var txt_id_comp_ret = $("#txt_id_comp_ret").val();
        var txt_idret = $("#txt_idret").val();
        var cmb_tip_ide = $("#cmb_tip_ide option:selected").val();
        var txt_basenoiva = $("#txt_basenoiva").val();
        var txt_baseiva = $("#txt_baseiva").val();
        var txt_p100retrenta = $("#txt_p100retrenta").val();
        var txt_valorrenta = $("#txt_valorrenta").val();

        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Gastos/guardar_detalleretencion",
          data: {txt_id_comp_ret: txt_id_comp_ret, txt_idret: txt_idret, cmb_tip_ide: cmb_tip_ide,
                 txt_basenoiva: txt_basenoiva, txt_baseiva: txt_baseiva, txt_p100retrenta: txt_p100retrenta,
                 txt_valorrenta: txt_valorrenta},
          success: function(json) {
            $('#dataTableRet').DataTable().ajax.reload();
            $.fancybox.close();
            if (parseFloat(json.totalbaseretenido) >= subtotal) {
              $("#btn_addcptoret").attr("disabled", true);
            } else {
              $("#btn_addcptoret").attr("disabled", false);
            }
            $("#retenidorenta").val(json.totalretenido);
            actualizatotalretenido();
          }
        });
      });  

      $(document).on('change', '.editretiva', function(){
        var tmpval = $(this).val();
        if (tmpval == '') {
          $(this).val('0.00');
        } else{
          $(this).val(parseFloat(tmpval).toFixed(2));
        }
        actualizatotalretenido();
        var retenidototal = parseFloat($("#txtretenido").text()); 
        var retenidorenta = parseFloat($("#retenidorenta").val()); 
        var montoiva = "<?php if(@$comp != NULL){ print $comp->montoiva; } else {print 0;}?>";
        montoiva = montoiva * 1;

        var retiva = parseFloat($( this ).val());

        if ((parseFloat($( this ).attr('name')) / 100 * montoiva).toFixed(2)  < retiva){
          $(this).val('0.00');
          actualizatotalretenido();
        } else {
            if (montoiva < (retenidototal - retenidorenta)){
              $(this).val('0.00');
              actualizatotalretenido();
            }
          }

/*
        var baseiva = 0;
        $(".editretiva").each(function( index ) {
           baseiva += parseFloat($( this ).val()) / parseFloat($( this ).attr('name')) * 100 ;
        });

        if (montoiva < baseiva){
          $(this).val('0.00');
          actualizatotalretenido();
        } else {
            if (montoiva < (retenidototal - retenidorenta)){
              $(this).val('0.00');
              actualizatotalretenido();
            }
          }  */
      });    

      function actualizatotalretenido(){
          var retenidorenta = parseFloat($("#retenidorenta").val());
          var retiva10 = parseFloat($("#txt_retiva10").val());
          var retiva20 = parseFloat($("#txt_retiva20").val());
          var retiva30 = parseFloat($("#txt_retiva30").val());
          var retiva50 = parseFloat($("#txt_retiva50").val());
          var retiva70 = parseFloat($("#txt_retiva70").val());
          var retiva100 = parseFloat($("#txt_retiva100").val());
          $("#txtretenido").text((retenidorenta + retiva10 + retiva20 + retiva30 + retiva50 + retiva70 + retiva100).toFixed(2));
      }  

    $(document).on('change','#cmb_punto', function(){
      var punto = $("#cmb_punto option:selected").val();
      var tmppunto = $("#tmp_puntoemision").val();
      if (punto == tmppunto){
        $('#txt_factura').val($("#tmp_nroret").val());  
      }
      else{
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Gastos/sel_nroret_ptoemi",
            data: { punto: punto },
            success: function(json) {
              if (json != null){
                $('#txt_factura').val(json.nroretencion);  
              }
            }
        });
      }  
    });
      

  });

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-registered"></i> Retenciones en Gastos
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>gastos">Listado de Gastos</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DEL PROVEEDOR -->        
      <div class="col-md-12" >
        <div class="box box-danger" >
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-truck"></i> Datos del Gasto </h3> 
              <div class="pull-right"> 
                <a class="btn bg-green-active color-palette btn-grad upd_retencion"  href="#" data-original-title="" title=""><i class="fa fa-save"></i> Guardar Retencion </a>
                <a class="btn btn-danger color-palette del_retencion"  href="#" data-original-title="" title=""><i class="fa fa-trash-o"></i> Eliminar Retencion </a>
                <input type="hidden" id="txt_id_comp_ret" name="txt_id_comp_ret" value="<?php if(@$comp != NULL){ print @$comp->id_gastos_ret;} else {print 0;} ?>">
              </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12" style="padding-left: 0px; padding-right: 0px">
                <div class="col-md-4">
                  <label class="col-md-12">Fecha: <span class="text-red"><?php if(@$comp != NULL){ print str_replace('-', '/', $comp->fecharegistro); @$fec = date("d/m/Y  H:i:s", strtotime(@$fec)); }?></span></label>
                  <label class="col-md-12">Proveedor: <span class="text-red"><?php if(@$comp != NULL){ print $comp->nom_proveedor; }?></span></label>
                  <label class="col-md-12">C.I./R.U.C.: <span class="text-red"><?php if(@$comp != NULL){ print $comp->nro_ide_proveedor; }?></span></label>
                </div>
                <div class="col-md-3" style="padding-left: 0px; padding-right: 0px">
                  <label class="col-md-12">Nro Factura: <span class="text-red"><?php if(@$comp != NULL){ print $comp->nro_factura; }?></span></label>
                  <label class="col-md-12">Nro Autorización: <span class="text-red"><?php if(@$comp != NULL){ print $comp->nro_autorizacion; }?></span></label>
<!--                   <label class="col-md-12">Forma Pago: <span class="text-red"><?php if(@$comp != NULL){ print $comp->nom_cancelacion; }?></span></label>
 -->                </div>                
                <div class="col-md-3" style="padding-left: 0px; padding-right: 0px">
                  <label class="col-md-12">Subtotal IVA %12: <span class="text-red"><?php if(@$comp != NULL){ print $comp->subtotaldesc; }?></span></label>
                  <label class="col-md-12">Subtotal IVA %0: <span class="text-red"><?php if(@$comp != NULL){ print $comp->subtotalivacerodesc; }?></span></label>
                  <label class="col-md-12">Monto IVA: <span class="text-red"><?php if(@$comp != NULL){ print $comp->montoiva; }?></span></label>
                </div>                  
                <div class="col-md-2" style="padding-left: 0px; padding-right: 0px">
                  <label class="col-md-12">Valor Total: <span class="text-red"><?php if(@$comp != NULL){ print $comp->total; }?></span></label>
                  <label class="col-md-12">Retenido: <span id="txtretenido" class="text-red"><?php if(@$comp != NULL){ print $comp->montoretenido; }?></span></label>
                  <label class="col-md-12">Total a Cobrar: <span id="txtmontodif" class="text-red"><?php if(@$comp != NULL){ print number_format($comp->total - $comp->montoretenido,2,".",","); }?></span></label>
                </div>                  
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- DATOS DE LOS PRODUCTOS A COMPRAR -->
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">

        <div class="col-md-4" style="padding-left: 0px; padding-top: 0px; padding-bottom: 0px;">

         <div class="col-md-12" style="padding-left: 0px; padding-right: 0px; padding-top: 0px; padding-bottom: 0px;">

          <div class="box box-danger" >
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-registered"></i> Datos del Gasto </h3> 
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                    <div class="form-group col-md-4">
                      <label>#Comprobante</label>
                    </div>
                    <input type="hidden" id="tmp_puntoemision" name="tmp_puntoemision" value="<?php if(@$comp != NULL){ print @$comp->id_puntoemision; }?>">
                    <input type="hidden" id="tmp_nroret" name="tmp_nroret" value="<?php if(@$comp != NULL){ print substr(@$comp->nro_retencion,8,9); }?>">


                    <!-- Punto Emision  -->
                    <div style="padding-top: 0px; padding-bottom: 0px; padding-right: 0px;"  class="form-group col-md-4">
                        <select id="cmb_punto" name="cmb_punto" class="form-control datosnota" title="Punto de Emision">
                        <?php 
                            if (count($puntoemision) > 0) {
                              foreach ($puntoemision as $obj):
                                  if(@$comp->id_puntoemision != NULL){
                                      if($obj->id_puntoemision == $comp->id_puntoemision){ ?>
                                           <option value="<?php  print $obj->id_puntoemision; ?>" selected="TRUE"> <?php  print $obj->cod_punto; ?> </option>
                                          <?php
                                      }else{ ?>
                                          <option value="<?php  print $obj->id_puntoemision; ?>" > <?php  print $obj->cod_punto; ?> </option>
                                          <?php
                                      }
                                  }else{ ?>
                                      <option value="<?php  print $obj->id_puntoemision; ?>" > <?php  print $obj->cod_punto; ?> </option>
                                      <?php
                                      }   ?>
                                  <?php
                              endforeach;
                            }
                          ?>
                        </select>                                  
                    </div>
                    <div class="form-group col-md-4" >
                      <input type="text" style="padding: 0px;" class="form-control validate[required] text-center" id="txt_factura" name="txt_factura" value="<?php if(@$comp != NULL){ print substr(@$comp->nro_retencion,8,9);}  ?>" readonly>
                    </div>
                    <div class="form-group col-md-4">
                      <label>Autorizacion</label>
                    </div>
                    <div class="form-group col-md-8">
                      <input type="text" class="form-control validate[required] text-center" id="txt_autorizacion" name="txt_autorizacion" value="<?php if(@$comp != NULL){ print @$comp->nro_autorizacionret;} else {print @$proxnumret;} ?>">
                    </div>

                    <div class="form-group col-md-4">
                      <label>Fecha</label>
                    </div>

                    <div style="margin-bottom: 0px;" class="form-group col-md-8" >
                      <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right validate[required]" id="fecha_ret" name="fecha_ret" value="<?php if(@$comp->fecha_retencion != NULL){ @$fec = str_replace('-', '/', @$comp->fecha_retencion); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} ?>">
                      </div>                             
                    </div>

                </div>                  
              </div>                
            </div>              
          </div>
         </div>

         <div class="col-md-12" style="padding-left: 0px; padding-right: 0px; padding-top: 0px; padding-bottom: 0px;">
          <div class="box box-danger" >
            <div class="box-header with-border">
              <h3 class="box-title">Retencion de IVA </h3> 
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12" style="padding-left: 0px; padding-right: 0px; padding-top: 0px; padding-bottom: 0px;">
                    <div class="form-group col-md-2">
                      <label>10%</label>
                    </div>
                    <div class="form-group col-md-4">
                      <input type="text" class="form-control validate[required] text-center editretiva" id="txt_retiva10" name="10" value="<?php if(@$comp != NULL){ print @$comp->retiva10;} ?>">
                    </div>
                    <div class="form-group col-md-2">
                      <label>50%</label>
                    </div>
                    <div class="form-group col-md-4">
                      <input type="text" class="form-control validate[required] text-center editretiva" id="txt_retiva50" name="50" value="<?php if(@$comp != NULL){ print @$comp->retiva50;} ?>">
                    </div>
                    <div class="form-group col-md-2">
                      <label>20%</label>
                    </div>
                    <div class="form-group col-md-4">
                      <input type="text" class="form-control validate[required] text-center editretiva" id="txt_retiva20" name="20" value="<?php if(@$comp != NULL){ print @$comp->retiva20;} ?>">
                    </div>
                    <div class="form-group col-md-2">
                      <label>70%</label>
                    </div>
                    <div class="form-group col-md-4">
                      <input type="text" class="form-control validate[required] text-center editretiva" id="txt_retiva70" name="70" value="<?php if(@$comp != NULL){ print @$comp->retiva70;} ?>">
                    </div>
                    <div class="form-group col-md-2">
                      <label>30%</label>
                    </div>
                    <div class="form-group col-md-4">
                      <input type="text" class="form-control validate[required] text-center editretiva" id="txt_retiva30" name="30" value="<?php if(@$comp != NULL){ print @$comp->retiva30;} ?>">
                    </div>
                    <div class="form-group col-md-2">
                      <label>100%</label>
                    </div>
                    <div class="form-group col-md-4">
                      <input type="text" class="form-control validate[required] text-center editretiva" id="txt_retiva100" name="100" value="<?php if(@$comp != NULL){ print @$comp->retiva100;} ?>">
                    </div>

                </div>                  
              </div>                
            </div>              
          </div>
          </div>

         </div>

          <div class="col-md-8" style="padding-left: 0px; padding-right: 0px; padding-top: 0px; padding-bottom: 0px;">

            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px; padding-top: 0px; padding-bottom: 0px;">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Retencion de Renta </h3>
                  <div class="pull-right"> 
                    <a id="btn_addcptoret" class="btn bg-light-blue-active color-palette add_conceptoretencion" <?php if(@$comp != NULL){ if ((@$comp->subtotalivacerodesc+@$comp->subtotaldesc) == @$comp->totalbaseretenido) {print 'disabled'; }}?> href="#" data-original-title="" title=""><i class="fa fa-plus-square"></i> Añadir Concepto </a>
                  </div>
                  <input type="hidden" id="retenidorenta" name="retenidorenta" value="<?php if(@$comp != NULL){ print $comp->montoretenido - ($comp->retiva10 + $comp->retiva20 + $comp->retiva30 + $comp->retiva50 + $comp->retiva70 + $comp->retiva100);} else {print 0;} ?>">
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="box">
                        <div class="box-body table-responsive">
                          <table id="dataTableRet" class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr >
                                <!-- <th>Id</th>  --> 
                                <th>Acción</th>
                                <th>Concepto de Retencion</th>
                                <th>Base IVA0</th>
                                <th>Base IVA</th>
                                <th>%Ret.</th>
                                <th>Valor</th>
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
                <div class="box-footer">

                </div>
              </div>
            </div>           
          </div>
        </div>          
      </div>
    </div>

    </div>
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

