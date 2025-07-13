<style>
#contenido_cat{
width: 400px;
}   
</style>
<script>
$( document ).ready(function() {
    $("#formID").validationEngine();
});

</script>    
<div id = "contenido_cat" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Agregar Factor de Conversion</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('unidades/gua_fac_conv');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$uni != NULL){ ?>
                        <input type="hidden" id="txt_iduni" name="txt_iduni" value="<?php if($uni != NULL){ print $uni; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_iduni" name="txt_iduni" value="<?php if($fact_edi->idunidad1 != NULL){ print $fact_edi->idunidad1; }?>" >
                        <input type="hidden" id="txt_idfact" name="txt_idfact" value="<?php if($fact_edi->idunidadequivale != NULL){ print $fact_edi->idunidadequivale; }?>" >        
                <?php } ?>  
                <div class="form-group col-md-8">
                  <label>Unidad Equivalente</label>
                  <select id="cmb_uni" name="cmb_uni" class="form-control">
                    <?php 
                      if(@$fact_edi != NULL){ ?>
                        <option  value="<?php if(@$fact_edi->idunidadequivale != NULL){ print @$fact_edi->idunidadequivale; }?>" selected="TRUE"><?php if($fact_edi->descripcion != NULL){ print $fact_edi->descripcion; }?></option>
                    <?php } else { ?>
                        <option  value="0" selected="TRUE">Seleccione...</option>
                    <?php } 
                              if (count($uni_lst) > 0) {
                                  foreach ($uni_lst as $uni):
                                      ?>
                                      <option value="<?php  print $uni->id; ?>"> <?php  print $uni->descripcion ?> </option>
                                      <?php
                                  endforeach;
                              }
                              ?>
                    </select>

                </div>
                <!-- Cantidad de la Unidad de Medida -->
                
                <div class="form-group col-md-8">
                    <label for="lb_cant">Cantidad</label>
                    <input type="text" class="form-control validate[required]" name="txt_cant" id="txt_cant" placeholder="Cantidad" value="<?php if(@$fact_edi->cantidadequivalente != NULL){ print @$fact_edi->cantidadequivalente; }?>" >
                </div>
                
            </div>
        </div>
        <!-- /.box-body -->
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="submit" class="btn btn-danger btn-grad no-margin-bottom">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>