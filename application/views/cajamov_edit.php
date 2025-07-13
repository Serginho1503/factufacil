<style>
    #contenido_ret{
        width: 400px;
    }   
</style>
<script type="text/javascript">
    $("#formRET").validationEngine();


</script>
<div id = "contenido_ret" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos de Apertura de Caja</h3>
        </div>
        <form id="formRET" name="formRET" method='POST' action="<?php echo base_url('Cajamov/guardar_apertura');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID  (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$obj != NULL){ ?>
                        <input type="hidden" id="txt_id" name="txt_id" value="<?php if($obj != NULL){ print $obj->id_mov; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_id" name="txt_id" value="0">    
                <?php } ?>  

                <div class="col-md-12">
                  <label class="col-md-12">Caja: <span class="text-red"><?php if(@$obj != NULL){ print @$obj->nom_caja; }?></span></label>
                </div>

                <div class="col-md-12">
                  <label class="col-md-12">Fecha Apertura: <span class="text-red"><?php if(@$obj != NULL){ $fec = str_replace('-', '/', $obj->fecha_apertura); @$fec = date("d/m/Y H:i:s", strtotime(@$fec)); print @$fec;}?></span></label>
                </div>

                <div class="form-group col-md-12">
                  <div class="col-md-5">
                    <label for="lb_cat">Monto Apertura</label>
                  </div>  
                  <div class="col-md-5">
                    <input type="text" class="form-control validate[required]" name="txt_monto" id="txt_monto" value="<?php if(@$obj != NULL){ print @$obj->monto_apertura; }?>" >
                  </div>  
                </div>


            </div>
        </div>
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