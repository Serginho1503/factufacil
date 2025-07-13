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
          <h3 class="box-title"></i> Anular Venta</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php print base_url('facturar/anular');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <input type="hidden" id="txt_idventa" name="txt_idventa" value="<?php if(@$factura != NULL){ print @$factura->id_venta; }?>" >    
                <div class="col-md-12">
                    <table id="" class="table table-bordered table-hover table-responsive">
                        <thead>
                          <tr>
                            <th class="text-left col-md-1">Fecha</th> 
                            <td class="text-center col-md-1"><?php print @$factura->fecharegistro; ?></td> 
                            <th class="text-left col-md-1">Punto</th> 
                            <td class="text-center col-md-1"><?php print @$factura->mesa; ?></td>                             
                          </tr>  
                          <tr>
                            <th class="text-left col-md-1">Nro Factura</th> 
                            <td class="text-center col-md-1"><?php print @$factura->nro_factura; ?></td> 
                            <th class="text-left col-md-1">Vendedor</th> 
                            <td class="text-center col-md-1"><?php print @$factura->mesero; ?></td> 
                          </tr> 
                          <tr>
                            <th class="text-left col-md-1">C.I / RUC</th> 
                            <td class="text-center col-md-1"><?php print @$factura->nro_ident; ?></td> 
                            <th class="text-left col-md-1">Cliente</th> 
                            <td class="text-center col-md-1"><?php print @$factura->nom_cliente; ?></td> 
                          </tr>   
                          <tr>
                            <th class="text-left col-md-1">Teléfono</th> 
                            <td class="text-center col-md-1"><?php print @$factura->telf_cliente; ?></td> 
                            <th class="text-left col-md-1">Dirección</th> 
                            <td class="text-center col-md-1"><?php print @$factura->dir_cliente; ?></td> 
                          </tr>   
                          <tr>
                            <td colspan="2"></td> 
                            <th class="text-left col-md-1">Monto</th> 
                            <td class="text-center col-md-1"><strong><?php print @$factura->montototal; ?></strong></td> 
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