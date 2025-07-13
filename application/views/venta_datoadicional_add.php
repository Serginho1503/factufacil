<style>
    #contenido_ret{
        width: 500px;
    }   
</style>
<script type="text/javascript">
$(document).ready(function() {    
    $("#formRET").validationEngine();

    $( "#formRET" ).submit(function( event ) {
      var id = $('#txt_id').val()   
      var datoadicional = $('#txt_datoadicional').val()   
      if (datoadicional == '') return false;
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Ventadatoadicional/existe_datoadicional');?>",
        data: {id: id, datoadicional: datoadicional},
        success: function(json) {
            if (json.mens > 0){
                alert("Ya existe el Dato Adicional o es un nombre reservado del sistema");
                event.preventDefault();
                return false;
            }
            else{
              chkactivo = $('#chkactivo').prop('checked')  
              $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url('Ventadatoadicional/guardar_datoadicional');?>",
                data: {txt_id: id, txt_datoadicional: datoadicional, chkactivo: chkactivo},
                success: function(json) {
                  $('#TableObj').DataTable().ajax.reload();
                  $.fancybox.close();
                } 
              });                    
            }
        }    
      });
    });


});

</script>
<div id = "contenido_ret" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Edición de Datos Adicionales de Venta</h3>
        </div>
        <form id="formRET" name="formRET" method='POST' action="#" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID  (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$obj != NULL){ ?>
                        <input type="hidden" id="txt_id" name="txt_id" value="<?php if($obj != NULL){ print $obj->id_config; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_id" name="txt_id" value="0">    
                <?php } ?>  

                <div class="form-group col-md-9">
                    <label for="lb_cat">Dato Adicional</label>
                    <input type="text" class="form-control validate[required]" name="txt_datoadicional" id="txt_datoadicional" placeholder="Dato Adicional" value="<?php if(@$obj != NULL){ print @$obj->nombre_datoadicional; }?>" >
                </div>

                <div class="form-group col-md-3 text-center" style="padding-left:0px;">
                    <input id="chkactivo" name="chkactivo" type="checkbox" <?php if(@$obj != NULL){ if(@$obj->activo == 1){ print " checked";} } else {print " checked";} ?> style="margin-top:31px; margin-right:0px; margin-left:0px;" > <strong>Activo</strong>
                </div>

            </div>
        </div>
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