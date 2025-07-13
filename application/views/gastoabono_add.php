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
          <h3 class="box-title"></i> Añadir Abono de Gasto</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('gastoabono/adicionar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
<!--                 <div class="form-group col-md-8">
                <!-- Fecha de Abono -->               
                    <label for="lb_cant">Fecha</label>
                    <div style="margin-bottom: 0px;"class="form-group" >
                      <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" class="form-control pull-right validate[required]" id="fecha" name="fecha" value="<?php print date("d/m/Y"); ?>">
                      </div>                             
                    </div>
                </div> -->

                <div class="form-group col-md-8">
                <!-- Forma de Pago de Abono -->               
                  <label>Forma de Pago</label>
                  <select id="txt_formapago" name="txt_formapago" class="form-control">
                    <option  value="0" selected="TRUE">Seleccione...</option>
                    <?php  
                              if (count($formapago_lst) > 0) {
                                  foreach ($formapago_lst as $uni):
                                      ?>
                                      <option value="<?php  print $uni->id_formapago; ?>"> <?php  print $uni->nombre_formapago ?> </option>
                                      <?php
                                  endforeach;
                              }
                              ?>
                    </select>

                </div>
                <!-- Monto de Abono -->               
                <div class="form-group col-md-8">
                    <label for="lb_cant">Monto</label>
                    <input type="text" class="form-control validate[required]" name="txt_monto" id="txt_monto" placeholder="Monto" value="" >
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