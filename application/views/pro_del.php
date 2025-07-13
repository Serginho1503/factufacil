<style>
#contenido_alm{
/*margin:auto;*/
width: 600px;

}   
</style>
<div id = "contenido_alm" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Eliminar Producto</h3>
        </div>
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('producto/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$pro != NULL){ ?>
                        <input type="hidden" id="txt_idpro" name="txt_idpro" value="<?php if($pro != NULL){ print $pro->pro_id; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idpro" name="txt_idpro" value="0">    
                <?php } ?>  
                        <div class="col-xs-3 text-center">
                            <h3 class="profile-username text-center">Imagen</h3>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-preview thumbnail"  id="fotomostrar">
                                    <img <?php
                                        if (@$pro != NULL) {
                                            if ($pro->pro_imagen) {
                                                
                                                print "width='150' height='150' src='data:image/jpeg;base64,$pro->pro_imagen'";
                                                
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
                                <label class="col-md-12">Código de Barra: <span class="text-red"><?php if(@$pro != NULL){ print @$pro->pro_codigobarra; }?></span></label>
                                <label class="col-md-12">Código Auxiliar: <span class="text-red"><?php if(@$pro != NULL){ print @$pro->pro_codigoauxiliar; }?></span></label>
                                <label class="col-md-12">Nombre: <span class="text-red"><?php if(@$pro != NULL){ print @$pro->pro_nombre; }?></span></label>
                                <label class="col-md-12">Descripción: <span class="text-red"><?php if(@$pro != NULL){ print @$pro->pro_descripcion; }?></span></label>
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