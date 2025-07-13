<style>
#contenido_provee{
    width: 600px;
}   
.margen_sup{
    margin-bottom: 5px;
}
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();
    });
</script>
<div id = "contenido_provee" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-truck"></i> Datos del Proveedor</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('proveedor/guardar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$provee != NULL){ ?>
                        <input type="hidden" id="txt_idprovee" name="txt_idprovee" value="<?php if(@$provee != NULL){ print @$provee->id_proveedor; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idprovee" name="txt_idprovee" value="0">    
                <?php } ?>  
                <!-- Tipo de Identificación -->
                <div class="form-group col-md-6 margen_sup">
                    <label>Tipo de Identificación</label>
                    <select class="form-control validate[required]" id="cmb_tip_ide" name="cmb_tip_ide">
                        <option value="0">Seleccione...</option> 
                        <?php 
                      if(@$ident != NULL){ ?>
                    <?php } else { ?>
                        <option  value="0" selected="TRUE">Seleccione...</option>
                    <?php } 
                              if (count($ident) > 0) {
                                foreach ($ident as $ide):
                                    if(@$provee->tip_ide_proveedor != NULL){
                                        if(@$ide->cod_identificacion == @$provee->tip_ide_proveedor){ ?>
                                            <option  value="<?php  print $ide->cod_identificacion; ?>" selected="TRUE"><?php  print @$ide->desc_identificacion ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print @$ide->cod_identificacion; ?>"> <?php  print @$ide->desc_identificacion ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print @$ide->cod_identificacion; ?>"> <?php  print @$ide->desc_identificacion ?> </option>
                                        <?php
                                        }   ?>
                                    <?php

                                endforeach;
                              }
                              ?>
                    </select>
                </div>  
                <!-- Número de Identificación -->
                <div class="form-group col-md-6 margen_sup">
                    <label for="lb_res">Nro de Identificación</label>
                    <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$provee != NULL){ print @$provee->nro_ide_proveedor; }?>" >
                </div>
                <!-- Nombre del Proveedor -->
                <div class="form-group col-md-12 margen_sup">
                    <label for="lb_nom">Nombre del Proveedor</label>
                    <input type="text" class="form-control validate[required]" name="txt_nom" id="txt_nom" placeholder="Nombre del Proveedor" value="<?php if(@$provee != NULL){ print @$provee->nom_proveedor; }?>" >
                </div>
                <!-- Nombre del Proveedor --> 
                <div class="form-group col-md-12 margen_sup">
                    <label for="lb_nom">Razón Social</label>
                    <input type="text" class="form-control validate[required]" name="txt_razsoc" id="txt_razsoc" placeholder="Razón Social del Proveedor" value="<?php if(@$provee != NULL){ print @$provee->razon_social; }?>" >
                </div>                
                <!-- Correo del Proveedor -->
                <div class="form-group col-md-4 margen_sup">
                    <label for="lb_res">Correo</label>
                    <input type="text" class="form-control " name="txt_mail" id="txt_mail" placeholder="Correo del Proveedor" value="<?php if(@$provee != NULL){ print @$provee->correo_proveedor; }?>" >
                </div>
                <!-- Teléfonos del Proveedor -->
                <div class="form-group col-md-4 margen_sup">
                    <label for="lb_res">Teléfonos</label>
                    <input type="text" class="form-control " name="txt_telf" id="txt_telf" placeholder="Teléfonos del Proveedor" value="<?php if(@$provee != NULL){ print @$provee->telf_proveedor; }?>" >
                </div>
                <!-- Ciudad del Proveedor -->
                <div class="form-group col-md-4 margen_sup">
                    <label for="lb_res">Ciudad</label>
                    <input type="text" class="form-control " name="txt_ciu" id="txt_ciu" placeholder="Ciudad" value="<?php if(@$provee != NULL){ print @$provee->ciudad_proveedor; }?>" >
                </div>
                <!-- Dirección del Proveedor -->
                <div class="form-group col-md-12 margen_sup">
                    <label for="lb_res">Dirección</label>
                    <input type="text" class="form-control " name="txt_dir" id="txt_dir" placeholder="Dirección del Proveedor" value="<?php if(@$provee != NULL){ print @$provee->direccion_proveedor; }?>" >
                </div>
               <!-- Es Relacionada --> 
                <div class="form-group col-md-12 margen_sup">
                    <label class="col-md-12"><input type="checkbox" name="chk_rel" id="chk_rel" class="minimal-red" <?php if(@$provee != NULL){ if(@$provee->relacionada == 1){ print "checked='' ";} }?> > Es Parte relacionada</label>
                </div>  

                <!-- Categoría Contable -->
                <div class="form-group col-md-6">
                    <label>Categoría Contable</label>
                    <select class="form-control " id="cmb_catcontable" name="cmb_catcontable">
                        <?php 
                      if(@$lstcatcontable != NULL){ ?>
                    <?php } else { ?>
                        <option  value="0" selected="TRUE">Seleccione...</option>
                    <?php } 
                              if (count($lstcatcontable) > 0) {
                                foreach ($lstcatcontable as $obj):
                                    if(@$provee->idcategoriacontable != NULL){
                                        if(@$obj->id == @$provee->idcategoriacontable){ ?>
                                            <option  value="<?php  print $obj->id; ?>" selected="TRUE"><?php  print @$obj->categoria ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print @$obj->id; ?>"> <?php  print @$obj->categoria ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print @$obj->id; ?>"> <?php  print @$obj->categoria ?> </option>
                                        <?php
                                        }   ?>
                                    <?php

                                endforeach;
                              }
                              ?>
                    </select>
                </div>  


            </div>
        </div>
        <!-- /.box-body -->
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