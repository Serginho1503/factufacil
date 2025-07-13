<style>
#contenido_alm{
/*margin:auto;*/
width: 600px;

}   
</style>
<div id = "contenido_alm" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Eliminar Sucursal</h3>
        </div>
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('sucursal/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$alm != NULL){ ?>
                        <input type="hidden" id="txt_idalm" name="txt_idalm" value="<?php if($alm != NULL){ print $alm->almacen_id; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idalm" name="txt_idalm" value="0">    
                <?php } ?>  
                        <div class="col-xs-3 text-center">
                            <h3 class="profile-username text-center">Logotipo</h3>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-preview thumbnail"  id="fotomostrar">
                                    <img <?php
                                        if (@$suc != NULL) {
                                            if ($suc->logo_sucursal) {
                                                
                                                print "width='150' height='150' src='data:image/jpeg;base64,$suc->logo_sucursal'";
                                                
                                            } else {
                                                ?>
                                                src="<?php print base_url(); ?>public/img/perfil.jpg" <?php
                                            }
                                        } else {
                                    ?>
                                            src="<?php print base_url(); ?>public/img/perfil.jpg" <?php }
                                        ?> alt="" onerror="this.src='<?php print base_url() . "public/img/perfil.jpg"; ?>';" />

                                </div>
                                
                            </div>
                        </div>

                        <div class="col-xs-9">
                            <div class="col-xs-12">
                            <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                                if(@$suc != NULL){ ?>
                                    <input type="hidden" id="txt_idsuc" name="txt_idsuc" value="<?php if($suc != NULL){ print $suc->id_sucursal; }?>" >    
                                <?php } else { ?>
                                    <input type="hidden" id="txt_idsuc" name="txt_idsuc" value="0">    
                            <?php } ?> 
                            </div> 
                            <div class="col-md-12">
                                <label class="col-md-12">Nombres: <span class="text-red"><?php if(@$suc != NULL){ print @$suc->nom_sucursal; }?></span></label>
                                <label class="col-md-12">Encargado: <span class="text-red"><?php if(@$suc != NULL){ print @$suc->enca_sucursal; }?></span></label>
                                <label class="col-md-12">Telefono: <span class="text-red"><?php if(@$suc != NULL){ print @$suc->telf_sucursal; }?></span></label>
                                <label class="col-md-12">Email: <span class="text-red"><?php if(@$suc != NULL){ print @$suc->mail_sucursal; }?></span></label>
                                <label class="col-md-12">Dirección: <span class="text-red"><?php if(@$suc != NULL){ print @$suc->dir_sucursal; }?></span></label>
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