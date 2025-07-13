<style>
#contenido_cat{
width: 400px;
}   
</style>
<script>
$( document ).ready(function() {
    $("#formID").validationEngine();
});

</script>    
<div id = "contenido_cat" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos de la Unidad de Medida</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('unidades/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$uni != NULL){ ?>
                        <input type="hidden" id="txt_iduni" name="txt_iduni" value="<?php if($uni != NULL){ print $uni->id; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_iduni" name="txt_iduni" value="0">    
                <?php } ?>  
            <!-- Nombre de la Unidad de Medida -->
            <div class="form-group col-md-12">
                <label for="lb_nom_uni">Nombre de la Unidad</label>
                <input type="text" class="form-control validate[required]" name="txt_uni" id="txt_uni" disabled="" value="<?php if(@$uni != NULL){ print @$uni->descripcion; }?>" >
            </div>
            <!-- Nombre Corto de la Unidad de Medida -->
            <div class="form-group col-md-6">
                <label for="lb_nom_cor">Nombre Corto</label>
                <input type="text" class="form-control validate[required]" name="txt_nom_cor" id="txt_nom_cor" disabled="" value="<?php if(@$uni != NULL){ print @$uni->nombrecorto; }?>" >
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