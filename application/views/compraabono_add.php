<style>
#contenido_cat{
width: 500px;
}   
</style>
<script>
$( document ).ready(function() {
    $("#formID").validationEngine();
});

function chequeamonto(){
    var montopendiente = $("#pendiente").val().replace(',','');      
    var montopendiente = parseFloat(montopendiente);
    /*alert("montopendiente " + montopendiente);*/
    var tmpvalor = $("#txt_monto").val().replace(',','');      
    var montoabono = parseFloat(tmpvalor);

    if (montoabono > montopendiente){
      alert("El monto del abono no puede ser mayor que el total pendiente de la compra (" + montopendiente +  ")");
      $("#txt_monto").val(montopendiente);
    }
}


</script>    
<div id = "contenido_cat" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Añadir Abono de Gasto</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="#" onSubmit='return false' >
        <!-- <form id="formID" name="formID" method='POST' action="<?php echo base_url('compraabono/adicionar');?>" onSubmit='return false' > -->
        <div class="box-body">
            <div class="row">
                <input type="hidden" id="pendiente" name="pendiente" value="<?php if(@$montopendiente != NULL){ print @$montopendiente; }?>" >    

                <div class="form-group col-md-6">
                <!-- Fecha de Abono -->               
                    <label for="lb_cant">Fecha</label>
                    <div style="margin-bottom: 0px;"class="form-group" >
                      <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" class="form-control pull-right validate[required]" id="fecha" name="fecha" value="<?php print date("d/m/Y"); ?>">
                      </div>                             
                    </div>
                </div> 

                <div class="form-group col-md-6">
                <!-- Forma de Pago de Abono -->               
                  <label>Forma de Pago</label>
                  <select id="txt_formapago" name="txt_formapago" class="form-control validate[required]">
                    <option  value="" selected="TRUE">Seleccione...</option>
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

                <div class="form-group col-md-6">
                    <label for="lb_cant">Monto</label>
                    <input type="text" class="form-control validate[required]" name="txt_monto" id="txt_monto" placeholder="Monto" value="" onchange="chequeamonto();">
                </div>

                <div class="form-group col-md-6">
                    <label for="lb_cant">Número de Documento</label>
                    <input type="text" class="form-control " name="txt_nrodoc" id="txt_nrodoc" placeholder="Número de Documento" value="" ">
                </div>

                <div class="form-group col-md-12">
                    <label for="lb_cant">Descripción de Documento</label>
                    <input type="text" class="form-control " name="txt_desc" id="txt_desc" placeholder="Descripción de Documento" value=""">
                </div>
                
            </div>
        </div>
        <!-- /.box-body -->
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="button" class="btn btn-success btn-grad no-margin-bottom guardar_abono">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>