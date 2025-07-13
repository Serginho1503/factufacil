<style>
#contenido_rete{
    width: 500px;
}   

#ui-datepicker-div{
  z-index: 9999999  !important;
} 

</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();

        /* MASCARA PARA COD DE FACTURA*/
        $("#factura").mask("999-999-999999999");  

        $('#fecha_ret').on('changeDate', function(ev){
            $(this).datepicker('hide');
        });
        $("#fecha_ret").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy', 
          firstDay: 1
        });


    });

    function actualizabasenoiva(){
       tmpbasenoiva = parseFloat($("#txt_basenoiva").val());
       maxsubtotalsiniva = "<?php if(@$subtotalsiniva != NULL){ print $subtotalsiniva; } else {print 0;}?>";
       maxsubtotalsiniva = maxsubtotalsiniva * 1;

       if (tmpbasenoiva > maxsubtotalsiniva){
         $("#txt_basenoiva").val(maxsubtotalsiniva);
       }
       actualizaretencionrenta();
    }    

    function actualizabaseiva(){
       tmpbase = parseFloat($("#txt_baseiva").val());
       maxsubtotal = "<?php if(@$subtotalconiva != NULL){ print $subtotalconiva; } else {print 0;}?>";
       maxsubtotal = maxsubtotal * 1;

       if (tmpbase > maxsubtotal){
         $("#txt_baseiva").val(maxsubtotal);
       }
       actualizaretencionrenta();
    }    

    $(document).on('change','#cmb_tip_ide', function(){
      var idconcepto = $("#cmb_tip_ide option:selected").val();
      var p100 = $("#cmb_tip_ide option:selected").attr('id');
      var tmpbase = parseFloat($("#txt_baseiva").val());
      tmpbase += parseFloat($("#txt_basenoiva").val());
      var tmpvalor = (tmpbase * p100 / 100).toFixed(2);

      $("#txt_p100retrenta").val(p100);

      actualizahabilitadop100();
      actualizaretencionrenta();
    });

    $(document).on('change','#txt_p100retrenta', function(){
      actualizaretencionrenta();
    });

    function actualizaretencionrenta(){
      var p100 = parseFloat($("#txt_p100retrenta").val());
      var tmpbase = parseFloat($("#txt_baseiva").val());
      tmpbase += parseFloat($("#txt_basenoiva").val());
      var tmpvalor = (tmpbase * p100 / 100).toFixed(2);

      $("#txt_valorrenta").val(tmpvalor);
    }


    /* Habilitar edicion % al abrir*/
    function actualizahabilitadop100(){
      var habilitaedicion = $("#cmb_tip_ide option:selected").attr('name');
      if (habilitaedicion == 1){
         $("#txt_p100retrenta").attr("readonly", false);
      } else {
         $("#txt_p100retrenta").attr("readonly", true);
      }
    }  

    actualizahabilitadop100();
    var p100 = $("#cmb_tip_ide option:selected").attr('id');
    $("#txt_p100retrenta").val(p100);
    actualizaretencionrenta();

</script>
<div id = "contenido_rete" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-registered"></i> Concepto de Retencion de Renta</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="#" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$retencion != NULL){ ?>
                        <input type="hidden" id="txt_idret" name="txt_idret" value="<?php if(@$retencion != NULL){ print @$retencion->id_detallerenta; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idret" name="txt_idret" value="0">    
                <?php } ?>  

                <!-- Tipo de Identificación -->
                <div class="form-group col-md-12">
                  <div class="form-group col-md-2">
                    <label>Concepto</label>
                  </div>
                  <div class="form-group col-md-10">

                    <select class="form-control validate[required]" id="cmb_tip_ide" name="cmb_tip_ide">
                        <?php 
                      if(@$lstret != NULL){ ?>
                    <?php } else { ?>
                        <option  value="0" selected="TRUE">Seleccione...</option>
                    <?php } 
                              if (count($lstret) > 0) {
                                foreach ($lstret as $lr):
                                    if(@$retencion->id_concepto_retencion != NULL){
                                        if(@$lr->id_cto_retencion == @$retencion->id_concepto_retencion){ ?>
                                            <option id="<?php  print $lr->porciento_cto_retencion; ?>" name="<?php  print $lr->editablecompra; ?>" value="<?php  print $lr->id_cto_retencion; ?>" selected="TRUE"><?php  print @$lr->retencion ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option id="<?php  print $lr->porciento_cto_retencion; ?>" name="<?php  print $lr->editablecompra; ?>" value="<?php  print @$lr->id_cto_retencion; ?>"> <?php  print @$lr->retencion ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option id="<?php  print $lr->porciento_cto_retencion; ?>" name="<?php  print $lr->editablecompra; ?>" value="<?php  print @$lr->id_cto_retencion; ?>"> <?php  print @$lr->retencion ?> </option>
                                        <?php
                                        }   ?>
                                    <?php

                                endforeach;
                              }
                              ?>
                    </select>
                  </div>
                </div>  


                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                              <th style="width: 50%" class="text-right">Base Imponible Tarifa IVA 0%</th>
                              <td><input style="width: 100px" type="text" class="form-control validate[required] text-center" id="txt_basenoiva" name="txt_basenoiva" value="<?php if(@$retencion != NULL){ print @$retencion->base_noiva; } else {print @$subtotalsiniva;}?>" onchange="actualizabasenoiva();"></td>
                            </tr>
                            <tr>
                              <th style="width: 50%" class="text-right">Base Imponible Tarifa IVA 12%</th>
                              <td><input style="width: 100px" type="text" class="form-control validate[required] text-center" id="txt_baseiva" name="txt_baseiva" value="<?php if(@$retencion != NULL){ print @$retencion->base_iva;} else {print @$subtotalconiva;} ?>" onchange="actualizabaseiva();"></td>
                            </tr>

                            <tr>
                              <th style="width: 50%" class="text-right">% Retención</th>
                              <td><input style="width: 100px"  type="text" class="form-control text-center" id="txt_p100retrenta" name="txt_p100retrenta" value="<?php if(@retencion != NULL){ print @$retencion->porciento_retencion_renta;} else {print '0.00';}?>"></td>
                            </tr>                            

                            <tr>
                              <th style="width: 50%" class="text-right">Valor Retenido</th>
                              <td><input style="width: 100px"  type="text" class="form-control text-center" id="txt_valorrenta" name="txt_valorrenta" value="<?php if(@$retencion != NULL){ print @$retencion->valor_retencion_renta;}  else {print '0.00';}?>" readonly></td>
                            </tr>                            

                        </tbody>
                    </table>                    
                </div>

      
            </div>
        </div>
        <!-- /.box-body -->
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="button" class="btn btn-danger btn-grad no-margin-bottom btnguardardetalle">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>