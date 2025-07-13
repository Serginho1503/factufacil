<style>
#contenido_mese{
/*margin:auto;*/
width: 600px;

}   
</style>
<div id = "contenido_mese" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Eliminar Vendedor</h3>
        </div>
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('mesero/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <input type="hidden" id="txt_idmes" name="txt_idmes" value="<?php if(@$mese != NULL){ print @$mese->id_mesero; }?>" >    
                    <div class="col-xs-9">
                        <div class="col-md-12">
                            <label class="col-md-12">Identificación: <span class="text-red"><?php if(@$mese != NULL){ print @$mese->tipo_ident_mesero." - ".@$mese->ced_mesero; }?></span></label>
                            <label class="col-md-12">Nombre: <span class="text-red"><?php if(@$mese != NULL){ print @$mese->nom_mesero; }?></span></label>
                            <label class="col-md-12">Teléfono: <span class="text-red"><?php if(@$mese != NULL){ print @$mese->telf_mesero; }?></span></label>
                            <label class="col-md-12">Correo: <span class="text-red"><?php if(@$mese != NULL){ print @$mese->correo_mesero; }?></span></label>
                            <label class="col-md-12">Dirección: <span class="text-red"><?php if(@$mese != NULL){ print @$mese->direccion_mesero; }?></span></label>
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