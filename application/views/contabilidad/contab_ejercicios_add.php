<style>
#contenido_provee{
    width: 500px;
}   
#ui-datepicker-div{
    z-index: 9999999  !important;
  }

.margen_sup{
    margin-bottom: 5px;
}
</style>
<script type='text/javascript' language='javascript'>
    $(document).ready(function() {

        $("#inicio").on('changeDate', function(ev){
            $(this).datepicker('hide');
        });

        $("#inicio").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy', 
            firstDay: 1
        });

        $("#fin").on('changeDate', function(ev){
            $(this).datepicker('hide');
        });

        $("#fin").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy', 
            firstDay: 1
        });

        $("#formID").validationEngine();

        $(document).on('change','.datofecha', function(){
            chequea_fecha();
        })

        function chequea_fecha(){
            id = $("#id").val();
            inicio = $("#inicio").val();
            fin = $("#fin").val();
            $.ajax({
              type: "POST",
              dataType: "json",
              url: "<?php echo base_url('contabilidad/contab_ejercicios/fechas_enotroejercicio');?>",
              data: {id: id, inicio: inicio, fin: fin},
              success: function(json) {
                //alert("salida validacion " + json.resu); 
                $('#btnejercicio').removeAttr('disabled'); 
                if (json.resu != 0){
                  alert("El intervalo de fecha no es válido.");
                  $('#btnejercicio').attr('disabled', true);
                }  
                else{
                  $('#btnejercicio').attr('disabled', false);
                }
              }
            });
        }

        chequea_fecha(); 

    });
</script>
<div id = "contenido_provee" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-calendar"></i> Datos del Ejercicio</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('contabilidad/contab_ejercicios/guardar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$obj != NULL){ ?>
                        <input type="hidden" id="id" name="id" value="<?php if(@$obj != NULL){ print @$obj->id; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="id" name="id" value="0">    
                <?php } ?>  

                <div class="form-group col-md-6" >
                    <label>Inicio</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right validate[required] datofecha" id="inicio" name="inicio" value="<?php if(@$obj != NULL){ @$fec = str_replace('-', '/', @$obj->inicio); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else {if(@$nuevoinicio != NULL){ @$fec = str_replace('-', '/', @$nuevoinicio); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;}}?>">
                    </div>                             
                </div>

                <div class="form-group col-md-6" >
                    <label>Fin</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right validate[required] datofecha" id="fin" name="fin" value="<?php if(@$obj != NULL){ @$fec = str_replace('-', '/', @$obj->fin); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else {if(@$nuevofin != NULL){ @$fec = str_replace('-', '/', @$nuevofin); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;}} ?>">
                    </div>                             
                </div>

                <!-- descripcion -->
                <div class="form-group col-md-12 margen_sup">
                    <label for="lb_res">Descripción</label>
                    <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripción" value="<?php if(@$obj != NULL){ print @$obj->descripcion; }?>" >
                </div>

            </div>
        </div>
        <!-- /.box-body -->
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button id="btnejercicio" type="submit" class="btn btn-success btn-grad no-margin-bottom" disabled>
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>