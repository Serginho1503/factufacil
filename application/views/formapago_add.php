<style>
#contenido_formapago{
    width: 600px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();
    });
</script>
<div id = "contenido_mese" class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Datos de la Forma de Pago</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('Formapago/guardar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$formapago != NULL){ ?>
                        <input type="hidden" id="txt_idobj" name="txt_idobj" value="<?php if(@$formapago != NULL){ print @$formapago->id_formapago; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idobj" name="txt_idobj" value="0">    
                <?php } ?>  
          
                <!-- codigo de forma de pago -->
                <div class="form-group col-md-4">
                    <label for="lb_res">Codigo</label>
                    <input type="text" class="form-control validate[required]" name="txt_cod" id="txt_cod" placeholder="Codigo" value="<?php if(@$formapago != NULL){ print @$formapago->cod_formapago; }?>" >
                </div>
                <!-- Nombre de forma de pago -->
                <div class="form-group col-md-12">
                    <label for="lb_nom">Nombre</label>
                    <input type="text" class="form-control validate[required]" name="txt_nom" id="txt_nom" placeholder="Nombre" value="<?php if(@$formapago != NULL){ print @$formapago->nombre_formapago; }?>" >
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