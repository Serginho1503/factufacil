
<style>
#contenido_alm{
/*margin:auto;*/
width: 600px;

}   
</style>

<script type="text/javascript">

$( document ).ready(function() {



});

</script>

<div id = "contenido_alm" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"></i> Gastos de Caja</h3>
            <h4 class="pull-right">Recibo Nro <?php if(@$detgas != NULL){ print @$detgas->nroegreso; } else { print @$cont + 1; } ?></h4>
        </div>
        <div class="box-body">
            <div class="row">
                
                <input type="hidden" id="txt_mov" name="txt_mov" value="<?php print $idmov; ?>">    
                <input type="hidden" id="txt_reg" name="txt_reg" value="<?php if(@$detgas != NULL){ print @$detgas->idreg; } else { print "0"; } ?>">

                <div class="form-group col-md-6">
                    <label for="">Cajero</label>
                    <input type="text" class="form-control " name="txt_emi" id="txt_emi" placeholder="Emisor Caja" value="<?php if(@$detgas != NULL){ print @$detgas->emisor; }?>" >
                </div> 

                <div class="form-group col-md-6">
                    <label for="">Recibe</label>
                    <input type="text" class="form-control " name="txt_rec" id="txt_rec" placeholder="Receptor Caja" value="<?php if(@$detgas != NULL){ print @$detgas->receptor; }?>" >
                </div>                 

                <div class="form-group col-md-9">
                    <label for="">Justificación</label>
                    <input type="text" class="form-control " name="txt_justi" id="txt_justi" placeholder="Justificación de Salida" value="<?php if(@$detgas != NULL){ print @$detgas->descripcion; }?>" >
                </div> 
                <div class="form-group col-md-3">
                    <label for="">Monto Salida</label>
                    <input type="text" class="form-control validate[required] text-center" name="txt_salida" id="txt_salida" placeholder="" value="<?php if(@$detgas != NULL){ print @$detgas->monto; }else{print "0"; } ?>"  onchange="actualizamonto();">
                </div>

            </div>
        </div>

        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="submit" class="btn btn-danger btn-grad no-margin-bottom guarda"><i class="fa fa-save "></i> Guardar </button>
            </div>
        </div>

    </div>
</div>