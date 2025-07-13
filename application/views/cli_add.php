<style>
#contenido_cli{
    width: 600px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formCLI").validationEngine();

        $('.precli').click(function() {
            var idsuc = $(this).attr('id');
            if($(this).is(":checked")){ valor = 1; } 
            else{ valor = 0; } 
            $("#txtpp"+idsuc).val(valor);
        });         

        /* BUSQUEDA DINAMICA POR CEDULA */
        $('#txt_nro_ident').blur(function(){
          var idcliente = $(this).val();    
          var id = $("#txt_idcli").val();    

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
                      url: base_url + "Cliente/existeIdentificacion",
                      data: { id: id, identificacion: idcliente },
                      success: function(json) {
                        if (json.resu != 0){
                            alert("El numero de identificación ya esta registrado para otro cliente");
                            $('#txt_nro_ident').focus();
                            return false;
                        } 
                      }
                  });
                } else {
                    alert("El numero de identificación no es valido");
                    $('#txt_nro_ident').focus();
                    return false;
                  } 
              }
          });
        });



        $(document).on('click','.precli', function(){
            var selprecios = new Array();
            $('input[type=checkbox]:checked').each(function() {
                var valchk = $(this).attr('id')
                if(valchk == 'chk_rel' || valchk == 'chk_may'){
                }else{
                    selprecios.push(valchk);
                }
            });
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url('Cliente/cargaprecio');?>",
                data: { idpre: JSON.stringify(selprecios) },
                success: function(json) {
                    $('#cmb_precio').empty();
                    json.forEach(function(json){
                        $('#cmb_precio').append('<option value="'+json.id_precios+'">'+json.desc_precios+'</option>');
                    });
                }
            });
                
          
        });         

    });
</script>
<div id = "contenido_cli" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Datos del Cliente</h3>
        </div>
        <form id="formCLI" name="formCLI" method='POST' action="<?php echo base_url('cliente/guardar');?>" onSubmit='return false' >
<!--          <form id="formID" name="formID" method='POST' action="#" onSubmit='return false' >-->
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$cli != NULL){ ?>
                        <input type="hidden" id="txt_idcli" name="txt_idcli" value="<?php if(@$cli != NULL){ print @$cli->id_cliente; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idcli" name="txt_idcli" value="0">    
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
                                    if(@$cli->tipo_ident_cliente != NULL){
                                        if(@$ide->cod_identificacion == @$cli->tipo_ident_cliente){ ?>
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
                    <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$cli != NULL){ print @$cli->ident_cliente; }?>" >
                </div>
                <div class="form-group col-md-4">
                    <label for="lb_nom">Código </label>
                    <input type="text" class="form-control " name="txt_codigo" id="txt_codigo" placeholder="Código" value="<?php if(@$cli != NULL){ print @$cli->codigo; }?>" >
                </div>                
                <!-- Nombre del Cliente -->
                <div class="form-group col-md-12">
                    <label for="lb_nom">Nombre del Cliente / Razón Social</label>
                    <input type="text" class="form-control validate[required]" name="txt_nom" id="txt_nom" placeholder="Nombre del Cliente" value="<?php if(@$cli != NULL){ print @$cli->nom_cliente; }?>" >
                </div>
                <!-- Correo del Cliente -->
                <div class="form-group col-md-6">
                    <label for="lb_res">Correo</label>
                    <input type="text" class="form-control " name="txt_mail" id="txt_mail" placeholder="Correo del Cliente" value="<?php if(@$cli != NULL){ print @$cli->correo_cliente; }?>" >
                </div>
                <!-- Teléfonos del Cliente -->
                <div class="form-group col-md-6">
                    <label for="lb_res">Teléfonos</label>
                    <input type="text" class="form-control " name="txt_telf" id="txt_telf" placeholder="Teléfonos del Cliente" value="<?php if(@$cli != NULL){ print @$cli->telefonos_cliente; }?>" >
                </div>
                <!-- Nivel Académico 
                <div class="form-group col-md-4">
                    <label for="lb_nom">Nivel </label>
                    <input type="text" class="form-control " name="txt_nivel" id="txt_nivel" placeholder="Nivel" value="<?php if(@$cli != NULL){ print @$cli->nivel_est_cliente; }?>" >
                </div>               
                --> 
                <!-- Ciudad del Cliente -->
                <div class="form-group col-md-3">
                    <label for="lb_res">Ciudad</label>
                    <input type="text" class="form-control " name="txt_ciu" id="txt_ciu" placeholder="Ciudad" value="<?php if(@$cli != NULL){ print @$cli->ciudad_cliente; }?>" >
                </div>
                <!-- Referencia del Cliente -->
                <div class="form-group col-md-5">
                    <label for="lb_res">Referencia</label>
                    <input type="text" class="form-control " name="txt_ref" id="txt_ref" placeholder="Referencia" value="<?php if(@$cli != NULL){ print @$cli->ref_cliente; }?>" >
                </div>
                <!-- Placa -->
                <div class="form-group col-md-4">
                    <label for="lb_nom">Placa </label>
                    <input type="text" class="form-control " name="txt_placa" id="txt_placa" placeholder="Placa" value="<?php if(@$cli != NULL){ print @$cli->placa_matricula; }?>" >
                </div>                
                <!-- Dirección del Cliente -->
                <div class="form-group col-md-12">
                    <label for="lb_res">Dirección</label>
                    <input type="text" class="form-control " name="txt_dir" id="txt_dir" placeholder="Dirección del Cliente" value="<?php if(@$cli != NULL){ print @$cli->direccion_cliente; }?>" >
                </div>
               <!-- Chk Precios --> 
                <div class="form-group col-md-6" style="margin-bottom: 2px; padding-left: 30px;">
                    <div class="form-group" >
                        <div class="checkbox">
                            <?php
                            if(@$precios != NULL){
                                foreach ($precios as $p) { ?>
                                    <div class="checkbox">
                                        <label><input class="precli" id="<?php print $p->id_precios; ?>"  name="pre<?php print $p->id_precios; ?>" type="checkbox" value="0" <?php if(@$p != NULL){ if(@$p->estatus == 1){ print "checked='' ";} } ?> > <?php print $p->desc_precios; ?></label>
                                        <input type="hidden" class="form-control"  id="txtpp<?php print $p->id_precios; ?>" name="txtpp<?php print $p->id_precios; ?>" value="<?php print $p->estatus; ?>" >
                                    </div>
                                <?php 
                                }
                            }
                            ?>
                        </div>                                                
                    </div>
                </div>  
                <!-- Tipo de Precio -->
                <div class="col-md-6">
                    <div class="form-group col-md-12">
                        <label>Tipo de Precio</label>
                        <select class="form-control validate[required]" id="cmb_precio" name="cmb_precio">
                            <option value="0">Seleccione...</option> 
                            <?php 
                          if(@$pre != NULL){ ?>
                        <?php } else { ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } 
                                  if (count($pre) > 0) {
                                    foreach ($pre as $p): 
                                        if(@$cli->tipo_precio != NULL){
                                            if(@$p->id_precios == @$cli->tipo_precio){ ?>
                                                <option value="<?php if(@$p != NULL){ print @$p->id_precios; }?>" selected="TRUE"><?php if(@$p != NULL){ print @$p->desc_precios; }?></option>
                                                <?php
                                            }else{ ?>
                                                <option value="<?php if(@$p != NULL){ print @$p->id_precios; }?>"><?php if(@$p != NULL){ print @$p->desc_precios; }?></option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php if(@$p != NULL){ print @$p->id_precios; }?>"><?php if(@$p != NULL){ print @$p->desc_precios; }?></option>
                                            <?php
                                            }   ?>
                                        <?php

                                    endforeach;
                                  }
                                  ?>

                        </select>
                    </div>         
                    <div class="form-group col-md-12">
                        <label>Vendedor</label>
                        <select class="form-control validate[required]" id="cmb_vendedor" name="cmb_vendedor">
                            <option value="0">Seleccione...</option> 
                            <?php 
                          if(@$vendedor != NULL){ ?>
                        <?php } else { ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } 
                                  if (count($vendedor) > 0) {
                                    foreach ($vendedor as $v): 
                                        if(@$cli->id_vendedor != NULL){
                                            if(@$v->id_usu == @$cli->id_vendedor){ ?>
                                                <option value="<?php if(@$v != NULL){ print @$v->id_usu; }?>" selected="TRUE"><?php if(@$v != NULL){ print @$v->vendedor; }?></option>
                                                <?php
                                            }else{ ?>
                                                <option value="<?php if(@$v != NULL){ print @$v->id_usu; }?>"><?php if(@$p != NULL){ print @$v->vendedor; }?></option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php if(@$v != NULL){ print @$v->id_usu; }?>"><?php if(@$p != NULL){ print @$v->vendedor; }?></option>
                                            <?php
                                            }   ?>
                                        <?php

                                    endforeach;
                                  }
                                  ?>

                        </select>
                    </div>   
                    <div class="form-group col-md-12">
                        <label for="lb_res">Limite Credito</label>
                        <input type="text" class="form-control text-right" name="txt_clicredito" id="txt_clicredito" placeholder="" value="<?php if(@$cli != NULL){ print @$cli->credito; } else { print "0.00"; }?>" >
                    </div>                      
                </div>    
                <div class="col-md-12" ><hr style="margin-top: 0px; margin-bottom: 10px;"></div>
                <div class="form-group col-md-12">
                    <label class="col-md-5"><input type="checkbox" name="chk_rel" id="chk_rel" class="minimal-red" <?php if(@$cli != NULL){ if(@$cli->relacionado == 1){ print "checked='' ";} }?> > Es Parte relacionada</label>
                    <label class="col-md-3"><input type="checkbox" name="chk_may" id="chk_may" class="minimal-red" <?php if(@$cli != NULL){ if(@$cli->mayorista == 1){ print "checked='' ";} }?>  > Es Mayorista</label>
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
                                    if(@$cli->idcategoriacontable != NULL){
                                        if(@$obj->id == @$cli->idcategoriacontable){ ?>
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

                <!-- Categoría Venta -->
                <div class="form-group col-md-6">
                    <label>Categoría de Venta</label>
                    <select class="form-control " id="cmb_categventa" name="cmb_categventa">
                        <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php 
                              if (count($lstcategventa) > 0) {
                                foreach ($lstcategventa as $obj):
                                    if(@$cli->id_categoriaventa != NULL){
                                        if(@$obj->id == @$cli->id_categoriaventa){ ?>
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
        <!-- /.box-body btnguardarcliente-->
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