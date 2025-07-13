<style>
#contenido_cat{
/*margin:auto;*/
width: 400px;

}   
</style>

<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();
    });
</script>

<div id = "contenido_cat" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Limpiar Mesa</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('pedido/mesalimpia');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <input type="hidden" id="txt_idmesa" name="txt_idmesa" value="<?php print $idmesa; ?>" >    
            <!-- Nombre de Categoría -->
            <div class="form-group col-md-12">
                <label for="lb_cat">Observaciones</label>
               <textarea id="txt_obs" name="txt_obs" class="form-control validate[required]" rows="3" placeholder="Ingrese las Observaciones ..."></textarea>
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