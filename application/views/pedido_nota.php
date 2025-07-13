<style>
#contenido_pronota{
    width: 400px;
}   
</style>

<script type="text/javascript">
    $(document).ready(function () {
    });
</script>

<div id = "contenido_pronota" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Notas del Producto </h3>
        </div>
        <form id="formBAS" name="formBAS" method='POST' action="<?php echo base_url('pedido/guardar_nota');?>" onSubmit='return false' >
            <div class="box-body">
                <div class="row">
                    <div id="" class="col-md-12" > 
                        <input type="hidden" id="txt_idpro" name="txt_idpro" value="<?php print $pro; ?>" >  
                        <input type="hidden" id="txt_idped" name="txt_idped" value="<?php print $idped; ?>" >
                        <textarea id="txt_nota" name="txt_nota" class="form-control" rows="3" placeholder="Ingrese los Detalles ..."><?php print @$nota; ?></textarea>
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