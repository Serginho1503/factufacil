<style>
    #contenido_ret{
        width: 600px;
    }   
</style>
<script type="text/javascript">
    $("#formRET").validationEngine();
</script>
<div id = "contenido_ret" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos de la Retención</h3>
        </div>
        <form id="formRET" name="formRET" method='POST' action="<?php echo base_url('Retencion/agregar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID  (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$ret != NULL){ ?>
                        <input type="hidden" id="txt_idret" name="txt_idret" value="<?php if($ret != NULL){ print $ret->id_cto_retencion; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idret" name="txt_idret" value="0">    
                <?php } ?>  
                <div class="form-group col-md-4">
                    <label for="lb_cat">Código Retención</label>
                    <input type="text" class="form-control validate[required]" name="txt_codret" id="txt_codret" placeholder="Código Retención" value="<?php if(@$ret != NULL){ print @$ret->cod_cto_retencion; }?>" >
                </div>
                <div class="form-group col-md-4">
                    <label for="lb_cat">Porcentaje Retención</label>
                    <input type="text" class="form-control validate[required]" name="txt_porret" id="txt_porret" placeholder="% Retención" value="<?php if(@$ret != NULL){ print @$ret->porciento_cto_retencion; }?>" >
                </div>                
                <div class="form-group col-md-4 text-center" style="padding-left:0px;">
                    <input id="chkeditable" name="chkeditable" type="checkbox" <?php if(@$ret != NULL){ if(@$ret->editablecompra == 1){ print "checked='' ";} }?> style="margin-top:31px; margin-right:0px; margin-left:0px;" > <strong>Editable en Compra</strong>
                </div>
                <div class="form-group col-md-12">
                    <label for="lb_desc">Descripción de la Retención</label>
                    <textarea id="txt_descret" name="txt_descret" class="form-control validate[required]" rows="3" placeholder="Ingrese Descripción ..."><?php if(@$ret != NULL){ print @$ret->descripcion_retencion; }?></textarea>
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