<style>
#contenido_mese{
    width: 600px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();
    });
</script>
<div id = "contenido_mese" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Datos del Vendedor</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('Mesero/guardar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$mese != NULL){ ?>
                        <input type="hidden" id="txt_idmese" name="txt_idmese" value="<?php if(@$mese != NULL){ print @$mese->id_mesero; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idmese" name="txt_idmese" value="0">    
                <?php } ?>  
          
                <!-- Tipo de Identificación -->
                <div class="form-group col-md-4">
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
                                    if(@$mese->tipo_ident_mesero != NULL){
                                        if(@$ide->cod_identificacion == @$mese->tipo_ident_mesero){ ?>
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
                <div class="form-group col-md-4">
                    <label for="lb_res">Nro de Identificación</label>
                    <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$mese != NULL){ print @$mese->ced_mesero; }?>" >
                </div>
                <!-- ESTATUS DEL MESERO -->
                <div class="form-group col-md-4">
                    <label>Estatus</label>
                    <select class="form-control validate[required]" id="cmb_est" name="cmb_est">
                        <option value="0">Seleccione...</option> 
                        <?php if($mese != NULL){ ?>
                        <?php if($mese->estatus_mesero == 'A'){ ?>
                            <option value="<?php if($mese != NULL){ print $mese->estatus_mesero; }?>" selected="TRUE">Activo</option>
                            <option value="I">Inactivo</option>
                        <?php } else {?>
                            <option value="A">Activo</option> 
                            <option value="<?php if($mese != NULL){ print $mese->estatus_mesero; }?>" selected="TRUE">Inactivo</option>    
                        <?php } 
                            }
                        ?>
                        <?php if(@$mese == NULL){ ?>
                         <option value="A">Activo</option> 
                        <option value="I">Inactivo</option>  
                        <?php } ?>
                    </select>
                </div>
                <!-- Nombre del mesero -->
                <div class="form-group col-md-12">
                    <label for="lb_nom">Nombres y Apellidos</label>
                    <input type="text" class="form-control validate[required]" name="txt_nom" id="txt_nom" placeholder="Nombre" value="<?php if(@$mese != NULL){ print @$mese->nom_mesero; }?>" >
                </div>
                <!-- Correo del mesero -->
                <div class="form-group col-md-6">
                    <label for="lb_res">Correo</label>
                    <input type="text" class="form-control " name="txt_mail" id="txt_mail" placeholder="Correo" value="<?php if(@$mese != NULL){ print @$mese->correo_mesero; }?>" >
                </div>
                <!-- Teléfonos del mesero -->
                <div class="form-group col-md-6">
                    <label for="lb_res">Teléfonos</label>
                    <input type="text" class="form-control " name="txt_telf" id="txt_telf" placeholder="Teléfonos" value="<?php if(@$mese != NULL){ print @$mese->telf_mesero; }?>" >
                </div>
                <!-- Dirección del mesero -->
                <div class="form-group col-md-12">
                    <label for="lb_res">Dirección</label>
                    <input type="text" class="form-control " name="txt_dir" id="txt_dir" placeholder="Dirección" value="<?php if(@$mese != NULL){ print @$mese->direccion_mesero; }?>" >
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