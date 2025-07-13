<style>
#contenido_cat{
/*margin:auto;*/
width: 400px;

}   
</style>
<div id = "contenido_cat" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos del Precio</h3>
        </div>
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('precio/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$pre != NULL){ ?>
                        <input type="hidden" id="txt_idpre" name="txt_idpre" value="<?php if($pre != NULL){ print $pre->id_precios; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idpre" name="txt_idpre" value="0">    
                <?php } ?>  
            <!-- Nombre del Precio -->
            <div class="form-group col-md-12">
                <label for="lb_pre">Nombre</label>
                <input type="text" class="form-control validate[required]" name="txt_pre" id="txt_pre" disabled="" placeholder="Nombre del Precio" value="<?php if(@$pre != NULL){ print @$pre->desc_precios; }?>" >
            </div>
            <div class="form-group col-md-8">
                <label>Estatus</label>
                <select class="form-control validate[required]" id="cmb_est" name="cmb_est" disabled="">
                    <option value="0">Seleccione...</option> 
                    <?php if($pre != NULL){ ?>
                    <?php if($pre->esta_precios == 'A'){ ?>
                        <option value="<?php if($pre != NULL){ print $pre->esta_precios; }?>" selected="TRUE">Activo</option>
                        <option value="I">Inactivo</option>
                    <?php } else {?>
                        <option value="A">Activo</option> 
                        <option value="<?php if($pre != NULL){ print $pre->esta_precios; }?>" selected="TRUE">Inactivo</option>    
                    <?php } 
                        }
                    ?>
                    <?php if(@$pre == NULL){ ?>
                     <option value="A">Activo</option> 
                    <option value="I">Inactivo</option>  
                    <?php } ?>
                </select>
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