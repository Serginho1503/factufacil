<style>
#contenido_mese{
/*margin:auto;*/
width: 600px;

}   
</style>
<div id = "contenido_mese" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Eliminar Forma de Pago</h3>
        </div>
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('formapago/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <input type="hidden" id="txt_idobj" name="txt_idobj" value="<?php if(@$formapago != NULL){ print @$formapago->id_formapago; }?>" >    
                    <div class="col-xs-9">
                        <div class="col-md-12">
                            <label class="col-md-12">Codigo: <span class="text-red"><?php if(@$formapago != NULL){ print @$formapago->cod_formapago; }?></span></label>
                            <label class="col-md-12">Nombre: <span class="text-red"><?php if(@$formapago != NULL){ print @$formapago->nombre_formapago; }?></span></label>
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