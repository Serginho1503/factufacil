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
          <h3 class="box-title"></i> Descripción del Producto </h3>
        </div>
        <form id="formBAS" name="formBAS" method='POST' action="#" onSubmit='return false' >
            <div class="box-body">
                <div class="row">
                    <div id="" class="col-md-12" > 
                        <input type="hidden" id="txt_iddetalle" name="txt_iddetalle" value="<?php print $id; ?>" >  
                        <textarea id="descripcion_detalle" name="descripcion_detalle" class="form-control" rows="3" placeholder="Ingrese la Descripción..."><?php print @$descripcion; ?></textarea>
                    </div> 
                </div>
            </div>
            <!-- /.box-body -->
            <div  align="center" class="box-footer">
                <div class="form-actions ">
                    <button type="button" class="btn btn-danger btn-grad no-margin-bottom guardadescpro">
                    <i class="fa fa-save "></i> Guardar
                </button>
                </div>
            </div>
        </form>

    </div>
</div>