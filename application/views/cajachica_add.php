<?php 
    date_default_timezone_set("America/Guayaquil");
?>
<style>
#contenido_cli{
    width: 600px;
}   

#ui-datepicker-div
    {
        z-index: 9999999  !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $("#formID").validationEngine();

        $('#fecha').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy', 
          firstDay: 1
        });
        $('#fecha').on('changeDate', function(ev){
          $(this).datepicker('hide');
        });


    });



</script>
<div id = "contenido_cli" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-money"></i> Ingreso Caja Chica</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('cajachica/guardar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <!-- Fecha -->
                <div class="form-group col-md-4" >
                  <label for="">Fecha</label>
                  <div class="input-group date ">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" class="form-control pull-right validate[required]" id="fecha" name="fecha" value="<?php print  date("d/m/Y"); ?>">
                  </div>
                </div> 
                <!-- Nro de Ingreso -->
                <div class="form-group col-md-4">
                    <label for="lb_res">Nro de Ingreso</label>
                    <input type="text" class="form-control validate[required] text-center" name="txt_nroingreso" id="txt_nroingreso" placeholder="" value="" >
                </div>
                <!-- Monto -->
                <div class="form-group col-md-4">
                    <label for="lb_nom">Monto </label>
                    <input type="text" class="form-control text-right" name="txt_monto" id="txt_monto" placeholder="" value="" >
                </div>
                <!-- Descripción -->
                <div class="form-group col-md-12">
                    <label for="lb_nom">Descripción</label>
                    <input type="text" class="form-control validate[required]" name="txt_des" id="txt_des" placeholder="" value="" >
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
