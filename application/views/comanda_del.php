<style>
#contenido_comanda{
/*margin:auto;*/
width: 600px;

}   
</style>
<div id = "contenido_comanda" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos de la Comanda</h3>
        </div>
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('comanda/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$com != NULL){ ?>
                        <input type="hidden" id="txt_idcom" name="txt_idcom" value="<?php if($com != NULL){ print $com->id_comanda; }?>" >    
                    <?php } ?>
                        
            <!-- Nombre del almcio -->
            <div class="form-group col-md-6">
                <label for="lb_nom">Nombre</label>
                <input type="text" class="form-control validate[required]" name="txt_nom" id="txt_nom" disabled value="<?php if(@$com != NULL){ print @$com->nom_comanda; }?>" >
            </div>
            <div class="form-group col-md-6">
                <label for="lb_res">Impresora</label>
                <input type="text" class="form-control validate[required]" name="txt_imp" id="txt_imp" disabled value="<?php if(@$com != NULL){ print @$com->impresora; }?>" >
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