<style>
    #contenido_ret{
        width: 800px;
    }   

    #ui-datepicker-div{
        z-index: 9999999  !important;
    }

    .margen_sup{
        margin-bottom: 5px;
    }


</style>
<script type="text/javascript">

$(document).ready(function () {

    $("#formRET").validationEngine();

    $.datepicker.setDefaults($.datepicker.regional["es"]);
    $('#gdesde').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
        });
    $('#gdesde').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    $('#ghasta').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
        });
    $('#ghasta').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });  

    var table = $('#dataTablePreview').dataTable({
          "ordering": false,
          "paging": false,
          "searching": false,
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
          'ajax': "contabilidad/contab_comprobante/listadoVistaprevia",
          'columns': [
              {"data": "cuenta"},
              {"data": "debito"},
              {"data": "credito"}
          ]
      });

    $('#dataTablePreview').on( 'draw.dt', function () {
        //alert( 'Table redrawn' );
        actualiza_totales();

    } );      

    $(document).on('click', '.tiposeleccion', function(){
        if ($(this).is(":checked")){
          valor = true; 
        } 
        else { 
          valor = false; 
        } 
        var tiposeleccionado = $(this).attr('id');
        if (!valor) {tiposeleccionado = 0;}
        $("#tiposeleccionado").val(tiposeleccionado);

        $('.tiposeleccion').each(function(){
            id = this.id;
            if (id != tiposeleccionado){
                $('.tiposeleccion[id='+id+']').prop('checked',false);
            }
        });        
        actualiza_contabilizacion(0);
    });  

    $(document).on('change', '#gdesde, #ghasta', function(){
        actualiza_contabilizacion(0);
    });  

    $('#vistaprevia').click(function(){
        var tiposeleccionado = $("#tiposeleccionado").val();
        var hasta = $("#ghasta").val();
        var desde = $("#gdesde").val();
        var sucursal = $("#g_sucursal").val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('contabilidad/contab_comprobante/tmp_cmp_fechagenerar');?>",
            data: { hasta: hasta, desde: desde, sucursal: sucursal, 
                    tipocmp: tiposeleccionado }
        }).done(function (result) {
            $('#dataTablePreview').DataTable().ajax.reload();
        }); 
    });

    function actualiza_contabilizacion(tipo){
        var tiposeleccionado = tipo;
        var hasta = $("#ghasta").val();
        var desde = $("#gdesde").val();
        var sucursal = $("#g_sucursal").val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('contabilidad/contab_comprobante/tmp_cmp_fechagenerar');?>",
            data: { hasta: hasta, desde: desde, sucursal: sucursal, 
                    tipocmp: tiposeleccionado }
        }).done(function (result) {
            $('#dataTablePreview').DataTable().ajax.reload();
        }); 
    }

    function actualiza_totales(){
        var totdebito = 0;
        var cant = 0;
        $( ".upd_debito" ).each(function( index ) {
            tmpvalor = $( this ).html();
            totdebito = totdebito + parseFloat(tmpvalor.replace(',','') );
            cant = cant + 1;
        });
        $("#total_debito").val(totdebito.toFixed(2));
        var totcredito = 0;
        $( ".upd_credito" ).each(function( index ) {
            tmpvalor = $( this ).html();
            totcredito = totcredito + parseFloat(tmpvalor.replace(',','') );
            cant = cant + 1;
        });
        $("#total_credito").val(totcredito.toFixed(2));

        if (cant == 0){
            $("#generacmp").prop('disabled',true);
        }
        else{
            $("#generacmp").prop('disabled',false);
        }
    }

    $( "#formgenera" ).submit(function( event ) {
        var data = $(this).serialize();
        var tmpaction = $(this).attr("action");
        var tiposeleccionado = $("#tiposeleccionado").val();
        if (tiposeleccionado == 4){
            tmpurl = "<?php echo base_url('contabilidad/contab_comprobante/cuentas_configuradas_venta');?>";
            strtipo = 'Venta';
        }
        else if (tiposeleccionado == 5){
            tmpurl = "<?php echo base_url('contabilidad/contab_comprobante/cuentas_configuradas_cobro');?>";
            strtipo = 'Cobro';
        }
        else if (tiposeleccionado == 6){
            tmpurl = "<?php echo base_url('contabilidad/contab_comprobante/cuentas_configuradas_compra');?>";
            strtipo = 'Compra';
        }
        else if (tiposeleccionado == 7){
            tmpurl = "<?php echo base_url('contabilidad/contab_comprobante/cuentas_configuradas_gasto');?>";
            strtipo = 'Gasto';
        }
        else if (tiposeleccionado == 8){
            tmpurl = "<?php echo base_url('contabilidad/contab_comprobante/cuentas_configuradas_pago');?>";
            strtipo = 'Cobro';
        }
        else if (tiposeleccionado == 9){
            tmpurl = "<?php echo base_url('contabilidad/contab_comprobante/cuentas_configuradas_ingresoinv');?>";
            strtipo = 'Ingreso de Inventario';
        }
        else if (tiposeleccionado == 10){
            tmpurl = "<?php echo base_url('contabilidad/contab_comprobante/cuentas_configuradas_egresoinv');?>";
            strtipo = 'Egreso de Inventario';
        }
        else if (tiposeleccionado == 11){
            tmpurl = "<?php echo base_url('contabilidad/contab_comprobante/cuentas_configuradas_retencionventa');?>";
            strtipo = 'Retención de Venta';
        }
        else if (tiposeleccionado == 12){
            tmpurl = "<?php echo base_url('contabilidad/contab_comprobante/cuentas_configuradas_retencioncompra');?>";
            strtipo = 'Retención de Compra';
        }
        else if (tiposeleccionado == 13){
            tmpurl = "<?php echo base_url('contabilidad/contab_comprobante/cuentas_configuradas_retenciongasto');?>";
            strtipo = 'Retención de Gasto';
        }
        $.ajax({
           type: "POST",
           dataType: "json",
           url: tmpurl,
           success: function(json) {
               if (json == false){
                   alert( "Revise las categorias contables de " + strtipo + ". Faltan cuentas por configurar." );
                   event.preventDefault();
                   return true;
               }
               else{
                    $.ajax({
                        url: tmpaction,
                        data: data,
                        type: 'POST',
                        dataType: 'json',
                        success: function(json) {
                            $('#dataTableObj').DataTable().ajax.reload();
                            $.fancybox.close();
                        }
                    });
                    return false;
               }
           }
        });

    });    
    
    //actualiza_totales();
});

</script>
<div id = "contenido_ret" class="col-md-6">
    <form id="formgenera" name="formgenera" method='POST' action="<?php echo base_url('contabilidad/contab_comprobante/generar_comprobante');?>" onSubmit='return false' >
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Generar Asiento Contable</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <input type="hidden" id="tiposeleccionado" name="tiposeleccionado" value="0">    

                <div class="col-md-12">
                <!-- Sucursal -->
                <div style="" class="form-group col-md-4">
                  <label for="lb_res">Sucursal</label>
                  <select id="g_sucursal" name="g_sucursal" class="form-control">
                  <?php 
                    if(@$sucursales != NULL){ ?>
                    <?php } else { ?>
                    <option  value="" selected="TRUE">Seleccione Sucursal...</option>
                    <?php } 
                      if (count($sucursales) > 0) {
                        foreach ($sucursales as $suc):
                            if(@$sucursal != NULL){
                                if($sucursal == $suc->id_sucursal){ ?>
                                     <option value="<?php  print $suc->id_sucursal; ?>" selected="TRUE"> <?php  print $suc->nom_sucursal; ?> </option>
                                    <?php
                                }else{ ?>
                                    <option value="<?php  print $suc->id_sucursal; ?>" > <?php  print $suc->nom_sucursal; ?> </option>
                                    <?php
                                }
                            }else{ ?>
                                <option value="<?php  print $suc->id_sucursal; ?>" > <?php  print $suc->nom_sucursal; ?> </option>
                                <?php
                                }   ?>
                            <?php
                        endforeach;
                      }
                    ?>
                  </select>                                  
                </div>

                <div class="form-group col-md-5 col-md-offset-3">
                    <div class="form-actions col-md-6">
                        <button type="button" class="btn btn-sm btn-success btn-grad no-margin-bottom" id="vistaprevia">
                        <i class="fa fa-save "></i> Vista Previa
                        </button>
                    </div>
                    <div class="form-actions col-md-6">
                        <button type="submit" class="btn btn-sm btn-success btn-grad no-margin-bottom" id="generacmp" title="Generar asiento contable" disabled>
                        <i class="fa fa-save "></i> Generar
                        </button>
                    </div>
                </div>

                </div>

                <div class="col-md-12">

                <div class="form-group col-md-4" style="margin-bottom: 0px; margin-top: 0px;">
                <label class="control-label text-left" style="padding-left: 0px;">Desde</label>
                <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" class="form-control pull-right" style="padding-right: 0px;" id="gdesde" name="gdesde" value="<?php if(@$desde != NULL){ @$fec = str_replace('-', '/', @$desde); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} ?>">
                </div>
                </div> 

                <div class="form-group col-md-4" style="margin-bottom: 0px; margin-top: 0px;">
                <label class=" control-label text-left" style="padding-left: 0px;">Hasta</label>
                <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" class="form-control pull-right " style="padding-right: 0px;" id="ghasta" name="ghasta" value="<?php if(@$hasta != NULL){ @$fec = str_replace('-', '/', @$hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} ?>">
                </div>
                </div>  

                </div>  

            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="box box-danger">
            <div class="box-header with-border">
            <h3 class="box-title"></i> Tipos de Documentos</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12 table-responsive" > 
                        <table id="dataTableTipo" class="table table-bordered table-striped ">
                        <thead>
                        <tr >
                            <th width="20"></th> 
                            <th>Tipo de Documento</th> 
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                            foreach ($tipos as $det) {
                            ?>
                            <tr class="nombretipo" name="<?php print $det->id; ?>" >
                                <td class="text-center">
                                    <div class="col-md-12" >
                                        <label class="col-md-12"><input type="checkbox" class="minimal-red tiposeleccion" id="<?php print $det->id; ?>" > </label>
                                    </div> 
                                </td>
                                <td class="text-center">
                                    <?php print $det->nombre; ?>
                                </td>
                            </tr>
                            <?php } ?>

                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>      
    <div class="col-md-7">
        <div class="box box-danger">
            <div class="box-header with-border">
            <h3 class="box-title"></i> Contabilización Previa</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="box-body table-responsive">
                        <table id="dataTablePreview" class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr >
                                <th >Cuenta</th>
                                <th class="text-right">Débito</th>
                                <th class="text-right">Crédito</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group col-md-12 col-md-offset-2" >
                        <label class="control-label col-md-1" style="margin-left: 10px; margin-right: 20px;">Totales</label>             
                        <div class="col-md-3" style="margin-left: 30px; padding-left: 0px; padding-right: 0px;">
                            <input type="text" class="form-control text-right" id="total_debito" value="0.00" readonly>
                        </div>
                        <div class="col-md-3" style="margin-left: 30px; padding-left: 0px; padding-right: 0px;">
                            <input type="text" class="form-control text-right" id="total_credito" value="0.00" readonly>
                        </div>
                    </div>

                </div>
            </div>
        </div>        
    </div>    

    <div  align="center" class="box-footer">
    </div>
    </form>
</div>