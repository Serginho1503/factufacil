<style>
    #contenido_ret{
        width: 700px;
    }   
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $("#frmcajaefectivo").validationEngine();

        function puntoemision(){
            var idsuc = $('#cmb_sucursal option:selected').val(); 
            if(idsuc !== ''){
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?php echo base_url('Cajaefectivo/upd_puntoemision_id');?>",
                    data: { idsuc: idsuc },
                    success: function(json) {
                        $('#cmb_puntoemision').empty();
                        var idpto = $('#txt_idpto').val();
                        json.forEach(function(json){
                            if(idpto == json.id){
                                $('#cmb_puntoemision').append('<option value="'+json.id+'" selected="TRUE">'+json.codigo+'</option>');
                            }else{
                                $('#cmb_puntoemision').append('<option value="'+json.id+'">'+json.codigo+'</option>');        
                            }
                        });
                    } 
                });
            }    
        }
        puntoemision();        
    }); 
</script>
<div id = "contenido_ret" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos de la Caja Efectivo</h3>
        </div>
        <form id="frmcajaefectivo" name="frmcajaefectivo" method='POST' action="<?php echo base_url('Cajaefectivo/guarda_cajaefectivo');?>" onSubmit='return false' >
            <div class="box-body">
                <div class="row">
                    <?php /* CAMPO HIDDEN CON EL ID  (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                        if(@$caja != NULL){ ?>
                            <input type="hidden" id="txt_idce" name="txt_idce" value="<?php if($caja != NULL){ print $caja->id_caja; }?>" >    
                            <input type="hidden" id="txt_idpto" name="txt_idpto" value="<?php if($caja != NULL){ print $caja->id_puntoemision; }?>" >
                        <?php } else { ?>
                            <input type="hidden" id="txt_idce" name="txt_idce" value="0">    
                            <input type="hidden" id="txt_idpto" name="txt_idpto" value="0">
                    <?php } ?>  

                    <!-- Sucursal -->
                    <div class="col-md-12">
                        <div style="" class="form-group col-md-6">
                          <label for="lb_res">Sucursal</label>
                          <select id="cmb_sucursal" name="cmb_sucursal" class="form-control validate[required]">
                          <option  value="" selected="TRUE">Seleccione Sucursal...</option>  
                          <?php 
                            if(@$sucursales != NULL){ ?>
                            <?php } else { ?>
                            <option  value="" selected="TRUE">Seleccione Sucursal...</option>
                            <?php } 
                              if (count($sucursales) > 0) {
                                foreach ($sucursales as $suc):
                                    if(@$caja->idsuc != NULL){
                                        if($caja->idsuc == $suc->id_sucursal){ ?>
                                             <option value="<?php  print $suc->id_sucursal; ?>" selected="TRUE"> <?php  print $suc->nom_sucursal; ?> </option>
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $suc->id_sucursal; ?>" > <?php  print $suc->nom_sucursal; ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $suc->id_sucursal; ?>" > <?php  print $suc->nom_sucursal; ?> </option>
                                        <?php
                                        }   ?>
                                    <?php
                                endforeach;
                              }
                            ?>
                          </select>                                  
                        </div>
                        <div style="" class="form-group col-md-6">
                          <label for="lb_res">Punto de Emision</label>
                          <select id="cmb_puntoemision" name="cmb_puntoemision" class="form-control validate[required]">
                            <option  value="" selected="TRUE">Seleccione Punto de Emision...</option>
                          <?php /*
                            if(@$sucursales != NULL){ ?>
                            <?php } else { ?>
                            <option  value="" selected="TRUE">Seleccione Sucursal...</option>
                            <?php } 
                              if (count($sucursales) > 0) {
                                foreach ($sucursales as $suc):
                                    if(@$obj->id_sucursal != NULL){
                                        if($obj->id_sucursal == $suc->id_sucursal){ ?>
                                             <option value="<?php  print $suc->id_sucursal; ?>" selected="TRUE"> <?php  print $suc->nom_sucursal; ?> </option>
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $suc->id_sucursal; ?>" > <?php  print $suc->nom_sucursal; ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $suc->id_sucursal; ?>" > <?php  print $suc->nom_sucursal; ?> </option>
                                        <?php
                                        }   ?>
                                    <?php
                                endforeach;
                              } */
                            ?>
                          </select>                                  
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group col-md-6">
                            <label for="lb_cat">Nombre de Caja</label>
                            <input type="text" class="form-control validate[required]" name="txt_caja" id="txt_caja" placeholder="Código Establecimiento" value="<?php if(@$caja != NULL){ print @$caja->nom_caja; }?>" >
                        </div>
                        <div style="" class="form-group col-md-6">
                            <label for="lb_res">Estatus</label>
                            <select class="form-control validate[required]" id="cmb_estatus" name="cmb_estatus">
                                <option value="">Seleccione...</option> 
                                <?php if($caja != NULL){ ?>
                                <?php if($caja->activo =='1'){ ?>
                                <option value="<?php if($caja != NULL){ print $caja->activo; }?>" selected="TRUE">Activa</option>
                                <option value="0">Desabilitda</option>
                                <?php } else {?>
                                    <option value='1'>Activa</option> 
                                    <option value="<?php if($caja != NULL){ print $caja->activo; }?>" selected="TRUE">Desabilitda</option>    
                                <?php } 
                                    }
                                ?>
                                <?php if(@$caja == NULL){ ?>
                                 <option value='1'>Activa</option> 
                                <option value="0">Desabilitda</option>  
                                <?php } ?>
                            </select>                                  
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