<style>
    #contenido_ban{
        width: 450px;
    }   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();
    });    
</script>
<div id = "contenido_ban" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos de la Tarjeta</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php 
                    if(@$tar != NULL){ ?>
                        <input type="hidden" id="txt_idtar" name="txt_idtar" value="<?php if($tar != NULL){ print $tar->id_tarjeta; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idtar" name="txt_idtar" value="0">    
                <?php } ?>  
                <div class="form-group col-md-12">
                    <label for="lb_cat">Nombre de la Tarjeta</label>
                    <input type="text" class="form-control validate[required]" name="txt_tar" id="txt_tar" placeholder="Nombre de la Tarjeta" value="<?php if(@$tar != NULL){ print @$tar->nombre; }?>" >
                </div>
                <div class="form-group col-md-6">
                    <label for="lb_cat">Comisión Tarjeta Débito</label>
                    <input type="number" class="form-control validate[required]" name="txt_comisiondebito" id="txt_comisiondebito" placeholder="Comisión para Tarjeta de Débito" value="<?php if(@$tar != NULL){ print @$tar->comision_debito; } else {print '0.00';} ?>" >
                </div>
                <div class="form-group col-md-6">
                    <label for="lb_cat">Comisión Tarjeta Crédito</label>
                    <input type="number" class="form-control validate[required]" name="txt_comisioncredito" id="txt_comisioncredito" placeholder="Comisión para Tarjeta de Crédito" value="<?php if(@$tar != NULL){ print @$tar->comision_credito; } else {print '0.00';}?>" >
                </div>

            </div>
        </div>
        
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="submit" class="btn btn-success btn-grad no-margin-bottom tar_save">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>