<?php 
$parametro = &get_instance();
$parametro->load->model("Parametros_model");
$pedidocliente = $parametro->Parametros_model->sel_pedidocliente();
$pedidomesero = $parametro->Parametros_model->sel_pedidomesero();
$ptoventasingular = $parametro->Parametros_model->sel_ptoventasingular();
$ptoventaplural = $parametro->Parametros_model->sel_ptoventaplural();

?>
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
          <h3 class="box-title"></i> Cambio de <?php print $ptoventasingular; ?> </h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('pedido/cambiarmesa');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
<!--                 <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$uni != NULL){ ?>
                        <input type="hidden" id="txt_iduni" name="txt_iduni" value="<?php if($uni != NULL){ print $uni; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_iduni" name="txt_iduni" value="<?php if($fact_edi->idunidad1 != NULL){ print $fact_edi->idunidad1; }?>" >
                        <input type="hidden" id="txt_idfact" name="txt_idfact" value="<?php if($fact_edi->idunidadequivale != NULL){ print $fact_edi->idunidadequivale; }?>" >        
                <?php } ?>  
 -->            <div class="form-group col-md-8">
                  <label><?php print $ptoventaplural; ?> Ocupadas</label>
                  <select id="mesa_ocupada" name="mesa_ocupada" class="form-control">
                    <?php  
                              if (count($lst_ocupada) > 0) {
                                  foreach ($lst_ocupada as $uni):
                                      ?>
                                      <option value="<?php  print $uni->id_mesa; ?>"> <?php  print $uni->areamesa ?> </option>
                                      <?php
                                  endforeach;
                              }
                              ?>
                    </select>
                
                </div>

                <div class="form-group col-md-8">
                  <label><?php print $ptoventaplural; ?> Libres</label>
                  <select id="mesa_libre" name="mesa_libre" class="form-control">
                    <?php  
                              if (count($lst_libre) > 0) {
                                  foreach ($lst_libre as $uni):
                                      ?>
                                      <option value="<?php  print $uni->id_mesa; ?>"> <?php  print $uni->areamesa ?> </option>
                                      <?php
                                  endforeach;
                              }
                              ?>
                    </select>
                
                </div>

            </div>
        <!-- /.box-body -->
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="submit" class="btn btn-danger btn-grad no-margin-bottom">
                <i class="fa fa-save "></i> Cambiar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>