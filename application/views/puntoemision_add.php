<style>
    #contenido_ret{
        width: 800px;
    }   
</style>
<script type="text/javascript">
    $("#formRET").validationEngine();

    /* MASCARA PARA COD DE FACTURA*/
    $("#txt_codestab, #txt_codptoemi").mask("999");

</script>
<div id = "contenido_ret" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos del Punto de Emisión</h3>
        </div>
        <form id="formRET" name="formRET" method='POST' action="<?php echo base_url('Puntoemision/agregar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID  (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$obj != NULL){ ?>
                        <input type="hidden" id="txt_id" name="txt_id" value="<?php if($obj != NULL){ print $obj->id_puntoemision; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_id" name="txt_id" value="0">    
                <?php } ?>  

                <!-- Sucursal -->
                <div style="" class="form-group col-md-4">
                  <label for="lb_res">Sucursal</label>
                  <select id="cmb_sucursal" name="cmb_sucursal" class="form-control">
                  <?php 
                    if(@$sucursales != NULL){ ?>
                    <?php } else { ?>
                    <option  value="" selected="TRUE">Seleccione Sucursal...</option>
                    <?php } 
                      if (count($sucursales) > 0) {
                        foreach ($sucursales as $suc):
                            if(@$obj->id_sucursal != NULL){
                                if($obj->id_sucursal == $suc->id_sucursal){ ?>
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

                <div class="col-md-8">
                <div class="form-group col-md-5">
                    <label for="lb_cat">Código Establecimiento</label>
                    <input type="text" class="form-control validate[required]" name="txt_codestab" id="txt_codestab" placeholder="Código Establecimiento" value="<?php if(@$obj != NULL){ print @$obj->cod_establecimiento; }?>" >
                </div>

                <div class="form-group col-md-5">
                    <label for="lb_cat">Código Punto Emision</label>
                    <input type="text" class="form-control validate[required]" name="txt_codptoemi" id="txt_codptoemi" placeholder="Código Punto Emision" value="<?php if(@$obj != NULL){ print @$obj->cod_puntoemision; }?>" >
                </div>                

                <div class="form-group col-md-2 text-center" style="padding-left:0px;">
                    <input id="chkactivo" name="chkactivo" type="checkbox" <?php if(@$obj != NULL){ if(@$obj->activo == 1){ print " checked";} } else {print " checked";} ?> style="margin-top:31px; margin-right:0px; margin-left:0px;" > <strong>Activo</strong>
                </div>

                </div>

                <div class="form-group col-md-12">
                    <spam><strong>Consecutivos de Documentos: </strong>&nbsp;</spam>
                    <hr class="linea">
                    <div class="form-group col-md-2">
                        <label for="lb_desc">Factura</label>
                        <input type="text" class="form-control validate[required]" name="txt_numfactura" id="txt_numfactura" placeholder="Consecutivo Factura" value="<?php if(@$obj != NULL){ print @$obj->consecutivo_factura; }?>" >
                    </div>
                    <div class="form-group col-md-2">
                        <label for="lb_desc">Nota Venta</label>
                        <input type="text" class="form-control validate[required]" name="txt_numnota" id="txt_numnota" placeholder="Consecutivo Nota Venta" value="<?php if(@$obj != NULL){ print @$obj->consecutivo_notaventa; }?>" >
                    </div>
                    <div class="form-group col-md-2">
                        <label for="lb_desc">Nota Crédito</label>
                        <input type="text" class="form-control validate[required]" name="txt_numnotacredito" id="txt_numnotacredito" placeholder="Consecutivo Nota Crédito" value="<?php if(@$obj != NULL){ print @$obj->consecutivo_notacredito; }?>" >
                    </div>
                    <div class="form-group col-md-2">
                        <label for="lb_desc">Retención</label>
                        <input type="text" class="form-control validate[required]" name="txt_retencioncompra" id="txt_retencioncompra" placeholder="Consecutivo Retención Compra" value="<?php if(@$obj != NULL){ print @$obj->consecutivo_retencioncompra; }?>" >
                    </div>
                    <div class="form-group col-md-2">
                        <label for="lb_desc">Guía Remis.</label>
                        <input type="text" class="form-control validate[required]" name="txt_numguia" id="txt_numguia" placeholder="Consecutivo Guía Remisión" value="<?php if(@$obj != NULL){ print @$obj->consecutivo_guiaremision; }?>" >
                    </div>
                    <div class="form-group col-md-2" style="padding-right: 0px;">
                        <label for="lb_desc">Comprob.Pago</label>
                        <input type="text" class="form-control validate[required]" name="txt_numcompago" id="txt_numcompago" placeholder="Consecutivo Comprob.Pago" value="<?php if(@$obj != NULL){ print @$obj->consecutivo_comprobpago; }?>" >
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <spam><strong>Ambiente Facturación Electrónica: </strong>&nbsp;</spam>
                    <hr class="linea">
                    <div class="form-group col-md-3">
                        <label for="lb_desc">Facturas de Venta</label>

                        <div class="col-md-12">
                          <label class="radio-inline">
                            <input class="form-group" type="radio" id="ambiente_factura" name="ambiente_factura" <?php if(@$obj != NULL){ if (@$obj->ambiente_factura == 1) {print 'checked';} } else {print 'checked';} ?> value="1"> Prueba
                          </label>
                          <label class="radio-inline" style="padding-left: 10px;">
                            <input class="form-group"  type="radio" id="ambiente_factura" name="ambiente_factura" <?php if(@$obj != NULL){ if (@$obj->ambiente_factura == 2) {print 'checked';} } ?>  value="2"> Producción
                          </label>
                        </div>

                    </div>

                    <div class="form-group col-md-3">
                        <label for="lb_desc">Retenciones</label>

                        <div class="col-md-12">
                          <label class="radio-inline">
                            <input class="form-group" type="radio" id="ambiente_retencion" name="ambiente_retencion" <?php if(@$obj != NULL){ if (@$obj->ambiente_retencion == 1) {print 'checked';} } else {print 'checked';} ?> value="1"> Prueba
                          </label>
                          <label class="radio-inline" style="padding-left: 10px;">
                            <input class="form-group"  type="radio" id="ambiente_retencion" name="ambiente_retencion" <?php if(@$obj != NULL){ if (@$obj->ambiente_retencion == 2) {print 'checked';} } ?>  value="2"> Producción
                          </label>
                        </div>

                    </div>

                    <div class="form-group col-md-3">
                        <label for="lb_desc">Notas de Crédito</label>

                        <div class="col-md-12">
                          <label class="radio-inline">
                            <input class="form-group" type="radio" id="ambiente_notacredito" name="ambiente_notacredito" <?php if(@$obj != NULL){ if (@$obj->ambiente_notacredito == 1) {print 'checked';} } else {print 'checked';} ?> value="1"> Prueba
                          </label>
                          <label class="radio-inline" style="padding-left: 10px;">
                            <input class="form-group"  type="radio" id="ambiente_notacredito" name="ambiente_notacredito" <?php if(@$obj != NULL){ if (@$obj->ambiente_notacredito == 2) {print 'checked';} } ?>  value="2"> Producción
                          </label>
                        </div>

                    </div>

                    <div class="form-group col-md-3">
                        <label for="lb_desc">Guías de Remisión</label>

                        <div class="col-md-12">
                          <label class="radio-inline">
                            <input class="form-group" type="radio" id="ambiente_guia" name="ambiente_guia" <?php if(@$obj != NULL){ if (@$obj->ambiente_guia == 1) {print 'checked';} } else {print 'checked';} ?> value="1"> Prueba
                          </label>
                          <label class="radio-inline" style="padding-left: 10px;">
                            <input class="form-group"  type="radio" id="ambiente_guia" name="ambiente_guia" <?php if(@$obj != NULL){ if (@$obj->ambiente_guia == 2) {print 'checked';} } ?>  value="2"> Producción
                          </label>
                        </div>

                    </div>

                    <div class="form-group col-md-6 text-center" style="padding-left:0px;">
                        <input id="chk_enviosrifactura" name="chk_enviosrifactura" type="checkbox" <?php if(@$obj != NULL){ if(@$obj->enviosriguardar_factura == 1){ print " checked";} }  ?> style="margin-top:31px; margin-right:0px; margin-left:0px;" > <strong>Envíar comprobante de venta al SRI al facturar</strong>
                    </div>


                </div>

            </div>
        </div>
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="submit" class="btn btn-success btn-grad no-margin-bottom">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>


</div>