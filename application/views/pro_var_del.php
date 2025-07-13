<style>
#contenido_cat{
    width: 400px;
}   
</style>
<div id = "contenido_cat" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-list-ol" aria-hidden="true"></i> Eliminar Datos del Item</h3>
        </div>
        <form id="formESP" name="formESP" method='POST' action="<?php echo base_url('producto/del_item');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
 
            <!-- Nombre de Item -->
            <div class="form-group col-md-12">
                <label for="lb_cat">Nombre del Item</label>
                <input type="text" class="form-control validate[required]" name="txt_desc" id="txt_desc" disabled="" placeholder="Nombre de Item" value="<?php print @$arreglo[$id]; ?>" >
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