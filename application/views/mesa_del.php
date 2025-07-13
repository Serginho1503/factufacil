<style>
#contenido_area{
/*margin:auto;*/
width: 400px;

}   
</style>
<div id = "contenido_area" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos de la Mesa</h3>
        </div>
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('mesa/eliminar');?>" onSubmit='return false' >
            <div class="box-body">
                <div class="row">
                    <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                        if(@$mesa != NULL){ ?>
                            <input type="hidden" id="txt_idmesa" name="txt_idmesa" value="<?php if($mesa != NULL){ print $mesa->id_mesa; }?>" >    
                        <?php } else { ?>
                            <input type="hidden" id="txt_idmesa" name="txt_idmesa" value="0">    
                    <?php } ?>  
                <!-- Nombre de Mesa -->
                <div class="form-group col-md-12">
                    <label for="lb_cat">Nombre de la Mesa</label>
                    <input type="text" class="form-control validate[required]" name="txt_nomarea" id="txt_nomarea" disabled="" value="<?php if(@$mesa != NULL){ print @$mesa->nom_mesa; }?>" >
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