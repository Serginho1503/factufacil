<style>
    #contenido_ret{
        width: 500px;
    }   
</style>
<script type="text/javascript">
    $("#formRET").validationEngine();

</script>
<div id = "contenido_ret" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos del Detalle de Servicio</h3>
        </div>
        <form id="formRET" name="formRET" method='POST' action="<?php echo base_url('Serviciotecnico/guardar_cfg_detalle');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID  (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$obj != NULL){ ?>
                        <input type="hidden" id="txt_id" name="txt_id" value="<?php if($obj != NULL){ print $obj->id_config; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_id" name="txt_id" value="0">    
                <?php } ?>  

                <div class="form-group col-md-9">
                    <label for="lb_cat">Nombre de Detalle</label>
                    <input type="text" class="form-control validate[required]" name="txt_nombre" id="txt_nombre" placeholder="Nombre de Detalle" value="<?php if(@$obj != NULL){ print @$obj->nombre_configdetalle; }?>" >
                </div>

                <div class="form-group col-md-3 text-center" style="padding-left:0px;">
                    <input id="chkactivo" name="chkactivo" type="checkbox" <?php if(@$obj != NULL){ if(@$obj->activo == 1){ print " checked";} } else {print " checked";} ?> style="margin-top:31px; margin-right:0px; margin-left:0px;" > <strong>Activo</strong>
                </div>

                <div class="col-md-9 " >
                    <input id="chkmostrar" name="chkmostrar" type="checkbox" <?php if(@$obj != NULL){ if(@$obj->mostrarenlistado == 1){ print " checked";} } else {print " checked";} ?> style="margin-top:10px; margin-right:0px; margin-left:0px;" > <strong>Mostrar en Listado de Servicios</strong>
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