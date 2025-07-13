<style>
#contenido_provee{
/*margin:auto;*/
width: 600px;

}   
</style>
<div id = "contenido_provee" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-truck"></i> Eliminar Proveedor</h3>
        </div>
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('proveedor/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <input type="hidden" id="txt_idprovee" name="txt_idprovee" value="<?php if(@$provee != NULL){ print @$provee->id_proveedor; }?>" >    
                    <div class="col-xs-9">
                        <div class="col-md-12">
                            <label class="col-md-12">Identificación: <span class="text-red"><?php if(@$provee != NULL){ print @$provee->tip_ide_proveedor." - ".@$provee->nro_ide_proveedor; }?></span></label>
                            <label class="col-md-12">Nombre: <span class="text-red"><?php if(@$provee != NULL){ print @$provee->nom_proveedor; }?></span></label>
                            <label class="col-md-12">Ciudad: <span class="text-red"><?php if(@$provee != NULL){ print @$provee->ciudad_proveedor; }?></span></label>
                            <label class="col-md-12">Teléfono: <span class="text-red"><?php if(@$provee != NULL){ print @$provee->telf_proveedor; }?></span></label>
                            <label class="col-md-12">Correo: <span class="text-red"><?php if(@$provee != NULL){ print @$provee->correo_proveedor; }?></span></label>
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