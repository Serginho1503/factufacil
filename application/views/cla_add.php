<style>
#contenido_cat{
/*margin:auto;*/
width: 400px;

}   
</style>
<div id = "contenido_cat" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos de la Clasificacion</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('clasificacion/agregar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$cla != NULL){ ?>
                        <input type="hidden" id="txt_idcla" name="txt_idcla" value="<?php if($cla != NULL){ print $cla->id_cla; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idcla" name="txt_idcla" value="0">    
                <?php } ?>  
            <!-- Nombre de Categoría -->
            <div class="form-group col-md-12">
                <label for="lb_cat">Nombre de Clasificación</label>
                <input type="text" class="form-control validate[required]" name="txt_cla" id="txt_cla" placeholder="Nombre de Clasificación" value="<?php if(@$cla != NULL){ print @$cla->nom_cla; }?>" >
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