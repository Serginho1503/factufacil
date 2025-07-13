<style>
#contenido_alm{
/*margin:auto;*/
width: 600px;

}   
</style>
<div id = "contenido_alm" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Eliminar Cliente</h3>
        </div>
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('cliente/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <input type="hidden" id="txt_idcli" name="txt_idcli" value="<?php if(@$cli != NULL){ print @$cli->id_cliente; }?>" >    
                    <div class="col-xs-9">
                        <div class="col-md-12">
                            <label class="col-md-12">Identificación: <span class="text-red"><?php if(@$cli != NULL){ print @$cli->tipo_ident_cliente." - ".@$cli->ident_cliente; }?></span></label>
                            <label class="col-md-12">Nombre: <span class="text-red"><?php if(@$cli != NULL){ print @$cli->nom_cliente; }?></span></label>
                            <label class="col-md-12">Ciudad: <span class="text-red"><?php if(@$cli != NULL){ print @$cli->ciudad_cliente; }?></span></label>
                            <label class="col-md-12">Teléfono: <span class="text-red"><?php if(@$cli != NULL){ print @$cli->telefonos_cliente; }?></span></label>
                            <label class="col-md-12">Correo: <span class="text-red"><?php if(@$cli != NULL){ print @$cli->correo_cliente; }?></span></label>
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