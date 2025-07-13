<style>
#contenido_alm{
/*margin:auto;*/
width: 600px;

}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();
    });
</script>
<div id = "contenido_alm" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Anular Gasto</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php print base_url('gastos/anular');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <input type="hidden" id="txt_idgasto" name="txt_idgasto" value="<?php if(@$factura != NULL){ print @$factura->id_gastos; }?>" >    
                <div class="col-md-12">
                    <table id="" class="table table-bordered table-hover table-responsive">
                        <thead>
                          <tr>
                            <th class="text-left col-md-1">Fecha</th> 
                            <td class="text-center col-md-1"><?php print @$factura->fecha; ?></td> 
                            <th class="text-left col-md-1">Nro Factura</th> 
                            <td class="text-center col-md-1"><?php print @$factura->nro_factura; ?></td> 
                          </tr>  
                          <tr>
                            <th class="text-left col-md-1">C.I / RUC</th> 
                            <td class="text-center col-md-1"><?php print @$factura->nro_ide_proveedor; ?></td> 
                            <th class="text-left col-md-1">Proveedor</th> 
                            <td class="text-center col-md-1"><?php print @$factura->nom_proveedor; ?></td> 
                          </tr>   
                          <tr>
                            <th class="text-left col-md-1">Teléfono</th> 
                            <td class="text-center col-md-1"><?php print @$factura->telf_proveedor; ?></td> 
                            <th class="text-left col-md-1">Dirección</th> 
                            <td class="text-center col-md-1"><?php print @$factura->direccion_proveedor; ?></td> 
                          </tr>   
                          <tr>
                            <td colspan="2"></td> 
                            <th class="text-left col-md-1">Monto</th> 
                            <td class="text-center col-md-1"><strong><?php print @$factura->total; ?></strong></td> 
                          </tr>  
                          <tr>
                            <td colspan="4">
                                Observaciones 
                                <textarea id="txt_obs" name="txt_obs" class="form-control  validate[required]" rows="3" placeholder="Ingrese Descripción ..."></textarea>
                            </td>
                           </tr> 




                        </thead>    

                    </table>
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