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
          <h3 class="box-title"></i> Motivo de Anulación </h3>
        </div>
        <form id="formBAS" name="formBAS" method='POST' action="<?php echo base_url('contabilidad/contab_comprobante/anular_comprobante');?>" onSubmit='return false' >
            <div class="box-body">
                <div class="row">
                    <div id="" class="col-md-12" > 
                        <input type="hidden" id="txt_id" name="txt_id" value="<?php print $idcmp; ?>" >  
                        <textarea id="txt_motivo" name="txt_motivo" class="form-control" rows="3" placeholder="Ingrese el Motivo de Anulación..."> </textarea>
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