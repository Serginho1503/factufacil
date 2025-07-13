<style>
#contenido_alm{
/*margin:auto;*/
width: 600px;

}   
</style>
<div id = "contenido_alm" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos del Almacen</h3>
        </div>
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('almacen/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$alm != NULL){ ?>
                        <input type="hidden" id="txt_idalm" name="txt_idalm" value="<?php if($alm != NULL){ print $alm->almacen_id; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idalm" name="txt_idalm" value="0">    
                <?php } ?>  
            <!-- Nombre del almcio -->
            <div class="form-group col-md-6">
                <label for="lb_nom">Nombre</label>
                <input type="text" class="form-control validate[required]" name="txt_nom" id="txt_nom" disabled="" value="<?php if(@$alm != NULL){ print @$alm->almacen_nombre; }?>" >
            </div>
            <div class="form-group col-md-6">
                <label for="lb_res">Responsable</label>
                <input type="text" class="form-control validate[required]" name="txt_res" id="txt_res" disabled="" value="<?php if(@$alm != NULL){ print @$alm->almacen_responsable; }?>" >
            </div>
            <div class="form-group col-md-12">
                <label for="lb_res">Dirección</label>
                <input type="text" class="form-control validate[required]" name="txt_dir" id="txt_dir" disabled="" value="<?php if(@$alm != NULL){ print @$alm->almacen_direccion; }?>" >
            </div>
            <div class="form-group col-md-12">
                <label for="lb_res">Descripción</label>
                <input type="text" class="form-control validate[required]" name="txt_des" id="txt_des" disabled="" value="<?php if(@$alm != NULL){ print @$alm->almacen_descripcion; }?>" >
            </div>
            <div class="form-group col-md-6">
                <label>Sucursal</label>
                <select class="form-control validate[required]" id="cmb_suc" name="cmb_suc" disabled="">
                    <option value="0">Seleccione...</option> 
                    <?php //if($alm != NULL){ ?>
                    <?php //if($alm->esta_almcios == 'A'){ ?>
                        <option value="<?php //if($alm != NULL){ print $alm->esta_almcios; }?>" selected="TRUE">Activo</option>
                        <option value="1">Inactivo</option>
                    <?php //} else {?>
                        <option value="1">Activo</option> 
                        <option value="<?php //if($alm != NULL){ print $alm->esta_almcios; }?>" selected="TRUE">Inactivo</option>    
                    <?php //} 
                      //  }
                    ?>
                    <?php //if(@$alm == NULL){ ?>
                     <option value="1">Activo</option> 
                    <option value="1">Inactivo</option>  
                    <?php //} ?>
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