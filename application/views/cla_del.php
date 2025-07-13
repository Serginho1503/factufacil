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
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('clasificacion/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
            <input type="hidden" id="txt_idcla" name="txt_idcla" value="<?php if($cla != NULL){ print $cla->id_cla; }?>" >    
            <!-- Nombre de Categoría -->
            <div class="form-group col-md-12">
                <label for="lb_cat">Nombre de Clasificacion a Eliminar</label>
                <input type="text" class="form-control validate[required]" name="txt_cla" id="txt_cla" disabled="" value="<?php if(@$cla != NULL){ print @$cla->nom_cla; }?>" >
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