<style>
#contenido_mese{
/*margin:auto;*/
width: 600px;

}   
</style>
<div id = "contenido_mese" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Eliminar Abono </h3>
        </div>
        <form id="formDEL" name="formDEL" method='POST' action="<?php echo base_url('compraabono/eliminar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <input type="hidden" id="id_abono" name="id_abono" value="<?php if(@$objabono != NULL){ print @$objabono->id_abono; }?>" >    
                <input type="hidden" id="id_compra" name="id_compra" value="<?php if(@$objabono != NULL){ print @$objabono->id_comp; }?>" >    
                    <div class="col-xs-9">
                        <div class="col-md-12">
                            <label class="col-md-12">Fecha: <span class="text-red"><?php if(@$objabono != NULL){ $date=date_create($objabono->fecha); print date_format($date,'d/m/Y H:i');}?></span></label>
                            <label class="col-md-12">Forma de Pago: <span class="text-red"><?php if(@$objabono != NULL){ print @$objabono->nombre_formapago; }?></span></label>
                            <label class="col-md-12">Monto: <span class="text-red"><?php if(@$objabono != NULL){ print number_format($objabono->monto,2); }?></span></label>
                        </div>


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