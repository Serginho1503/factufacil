<style>
#contenido_cat{
/*margin:auto;*/
width: 400px;

}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();
    });
</script>
<div id = "contenido_cat" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos del Precio</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('precio/agregar');?>" onSubmit='return false' >
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
                    <input type="text" class="form-control validate[required]" name="txt_pre" id="txt_pre" placeholder="Nombre del Precio" value="<?php if(@$pre != NULL){ print @$pre->desc_precios; }?>" >
                </div>
                <div class="form-group col-md-8">
                    <label>Estatus</label>
                    <select class="form-control validate[required]" id="cmb_est" name="cmb_est">
                        <option value="">Seleccione...</option> 
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

                <div class="form-group col-md-4">
                    <label>Color</label>
                    <input type="color" id="txt_color" name="txt_color" value="<?php if(@$pre != NULL){ print $pre->color; } else { print '#ff0000';} ?>">                
                </div>    
    
            </div>
        </div>
        <!-- /.box-body -->
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="submit" class="btn btn-success btn-grad no-margin-bottom">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>