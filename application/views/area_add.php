<style>
#contenido_area{
/*margin:auto;*/
width: 400px;

}   
</style>
<div id = "contenido_area" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos del Area</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('area/guardar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$area != NULL){ ?>
                        <input type="hidden" id="txt_idarea" name="txt_idarea" value="<?php if($area != NULL){ print $area->id_area; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idarea" name="txt_idarea" value="0">    
                <?php } ?>  
            <!-- Nombre de Area -->
            <div class="form-group col-md-12">
                <label for="lb_cat">Nombre del Area</label>
                <input type="text" class="form-control validate[required]" name="txt_nomarea" id="txt_nomarea" placeholder="Nombre del Area" value="<?php if(@$area != NULL){ print @$area->nom_area; }?>" >
            </div>

            </div>
        </div>
        <!-- /.box-body -->
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="submit" class="btn btn-danger btn-grad no-margin-bottom">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>