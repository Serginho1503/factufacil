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
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('categoria/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
            <input type="hidden" id="txt_idcat" name="txt_idcat" value="<?php if($cat != NULL){ print $cat->cat_id; }?>" >    
            <!-- Nombre de Categoría -->
            <div class="form-group col-md-12">
                <label for="lb_cat">Nombre de Categoría a Eliminar</label>
                <input type="text" class="form-control validate[required]" name="txt_cat" id="txt_cat" disabled="" value="<?php if(@$cat != NULL){ print @$cat->cat_descripcion; }?>" >
            </div>

            </div>
        </div>
        <!-- /.box-body -->
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="submit" class="btn btn-danger btn-grad no-margin-bottom">
                <i class="fa fa-trash-o "></i> Eliminar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>