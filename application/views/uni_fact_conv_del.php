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
          <h3 class="box-title"></i> Eliminar Factor de Conversion</h3>
        </div>
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('unidades/del_fac');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <input type="hidden" id="txt_iduni" name="txt_iduni" value="<?php if($uni->idunidad1 != NULL){ print $uni->idunidad1; }?>" >
                <input type="hidden" id="txt_idfact" name="txt_idfact" value="<?php if($uni->idunidadequivale != NULL){ print $uni->idunidadequivale; }?>" >    
                <div class="form-group col-md-8">
                    <label for="lb_cant">Unidad</label>
                    <input type="text" class="form-control validate[required]" name="txt_uni_conv" id="txt_uni_conv"  disabled="" placeholder="Cantidad" value="<?php if($uni->descripcion != NULL){ print $uni->descripcion; }?>" >
                </div>
                <!-- Cantidad de la Unidad de Medida -->
                <div class="form-group col-md-8">
                    <label for="lb_cant">Cantidad</label>
                    <input type="text" class="form-control validate[required]" name="txt_cant" id="txt_cant"  disabled="" placeholder="Cantidad" value="<?php if($uni->cantidadequivalente != NULL){ print $uni->cantidadequivalente; }?>" >
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