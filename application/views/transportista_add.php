<style>
#contenido_cli{
    width: 600px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();

        /* BUSQUEDA DINAMICA POR CEDULA */
        $('#txt_nro_ident').blur(function(){
          var idcliente = $(this).val();    
          var id = $("#txt_idtran").val();    

          if (idcliente === ""){
            alert("Debe ingresar un numero de identificación");
            return false;
          }   

          /* ruc / cedula valido*/
          var idtp = $('#cmb_tip_ide option:selected').val();      
          $.ajax({
              type: "POST",
              dataType: "json",
              url: base_url + "Utiles/validarIdentificacion",
              data: { tipo: idtp, identificacion: idcliente },
              success: function(json) {
                if (json.resu == 1){
                  $.ajax({
                      type: "POST",
                      dataType: "json",
                      url: base_url + "Transportista/existeIdentificacion",
                      data: { id: id, identificacion: idcliente },
                      success: function(json) {
                        if (json.resu != 0){
                            alert("El numero de identificación ya esta registrado para otro transportista");
                            $('#txt_nro_ident').focus();
                            return false;
                        } 
                      }
                  });
                } else {
                    alert("El número de identificación no es válido");
                    $('#txt_nro_ident').focus();
                    return false;
                  } 
              }
          });
        });
    });
</script>
<div id = "contenido_cli" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Datos del Transportista</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('Transportista/guardar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$obj != NULL){ ?>
                        <input type="hidden" id="txt_idtran" name="txt_idtran" value="<?php if(@$obj != NULL){ print @$obj->idtransportista; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idtran" name="txt_idtran" value="0">    
                <?php } ?>  
                <!-- Tipo de Identificación -->
                <div class="form-group col-md-6">
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
                                    if(@$obj->tipoid != NULL){
                                        if(@$ide->cod_identificacion == @$cli->tipoid){ ?>
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
                <div class="form-group col-md-6">
                    <label for="lb_res">Nro de Identificación</label>
                    <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$obj != NULL){ print @$obj->cedula; }?>" >
                </div>
                <!-- Nombre  -->
                <div class="form-group col-md-7">
                    <label for="lb_nom">Razón Social</label>
                    <input type="text" class="form-control validate[required]" name="txt_nom" id="txt_nom" placeholder="Razón Social" value="<?php if(@$obj != NULL){ print @$obj->razonsocial; }?>" >
                </div>
                <!-- Correo  -->
                <div class="form-group col-md-5">
                    <label for="lb_res">Correo</label>
                    <input type="text" class="form-control " name="txt_mail" id="txt_mail" placeholder="Correo" value="<?php if(@$obj != NULL){ print @$obj->email; }?>" >
                </div>
                <!-- Teléfonos  -->
                <div class="form-group col-md-6">
                    <label for="lb_res">Teléfonos</label>
                    <input type="text" class="form-control " name="txt_telf" id="txt_telf" placeholder="Teléfonos" value="<?php if(@$obj != NULL){ print @$obj->telefono; }?>" >
                </div>
                <!-- Ciudad  -->
                <div class="form-group col-md-6">
                    <label for="lb_res">Ciudad</label>
                    <input type="text" class="form-control " name="txt_ciu" id="txt_ciu" placeholder="Ciudad" value="<?php if(@$obj != NULL){ print @$obj->ciudad; }?>" >
                </div>
                <!-- Dirección  -->
                <div class="form-group col-md-12">
                    <label for="lb_res">Dirección</label>
                    <input type="text" class="form-control " name="txt_dir" id="txt_dir" placeholder="Dirección" value="<?php if(@$obj != NULL){ print @$obj->direccion; }?>" >
                </div>
            </div>
        </div>
        <!-- /.box-body btnguardar-->
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="submit" class="btn btn-success btn-grad no-margin-bottom ">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>