<style>
#contenido_alm{
/*margin:auto;*/
width: 600px;

}   
</style>
<div id = "contenido_alm" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Eliminar Proforma</h3>
        </div>
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('proforma/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$pro != NULL){ ?>
                        <input type="hidden" id="txt_idpro" name="txt_idpro" value="<?php if($pro != NULL){ print $pro->id_proforma; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idpro" name="txt_idpro" value="0">    
                <?php } ?>  

                        <div class="col-xs-9">
                            <div class="col-md-12">
                                <label class="col-md-12">Numero: <span class="text-red"><?php if(@$pro != NULL){ print @$pro->nro_proforma; }?></span></label>
                                <label class="col-md-12">Fecha: <span class="text-red"><?php if(@$pro != NULL){ print @$pro->fecha; }?></span></label>
                                <label class="col-md-12">Cliente: <span class="text-red"><?php if(@$pro != NULL){ print @$pro->nom_cliente; }?></span></label>
                                <label class="col-md-12">Monto: <span class="text-red"><?php if(@$pro != NULL){ print @$pro->montototal; }?></span></label>
                                <label class="col-md-12">Vendedor: <span class="text-red"><?php if(@$pro != NULL){ print @$pro->nom_mesero; }?></span></label>
                            </div>


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