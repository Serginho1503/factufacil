<style>
#contenido_cat{
/*margin:auto;*/
width: 400px;

}   
</style>
<div id = "contenido_cat" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos de la Categoría</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('catgastos/agregar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$cat != NULL){ ?>
                        <input type="hidden" id="txt_idcat" name="txt_idcat" value="<?php if($cat != NULL){ print $cat->id_cat_gas; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idcat" name="txt_idcat" value="0">    
                <?php } ?>  
            <!-- Nombre de Categoría -->
            <div class="form-group col-md-12">
                <label for="lb_cat">Nombre de Categoría</label>
                <input type="text" class="form-control validate[required]" name="txt_cat" id="txt_cat" placeholder="Nombre de Categoría" value="<?php if(@$cat != NULL){ print @$cat->nom_cat_gas; }?>" >
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