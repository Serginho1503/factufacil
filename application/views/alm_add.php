<?php

if(@$alm != NULL){ 
    $deposito = $alm->almacen_deposito;
    if (!$deposito) {$deposito = 0;}
} else {
    $deposito = 0;
}


?>

<style>
#contenido_alm{
/*margin:auto;*/
width: 600px;

}   
</style>

<script type="text/javascript">

$( document ).ready(function() {

    $("#formID").validationEngine();


    var deposito = <?php print $deposito; ?>;
    if(deposito == 1){
        $('#chk_deposito').attr('checked', true);
        $('#cmb_prod').attr('disabled', false);
    }else{
        $('#chk_deposito').attr('checked', false);
        $('#cmb_prod').attr('disabled', true);
    }

    /* HABILITAR CON CHECKBOX DEPOSITO EL COMBO DE PRODUCTO */
    $('#chk_deposito').click(function() {
        if($(this).is(":checked")) {
            $('#cmb_prod').attr('disabled', false);
        }
        else {
            $('#cmb_prod').attr('disabled', true);
        }
    });

    /* HABILITAR CON CHECKBOX COMPRA EL TEXT COMPRA */
    $('#chk_compra').click(function() {
        if($(this).is(":checked")) $("#txt_precomp").removeAttr("disabled"); 
        else $("#txt_precomp").attr("disabled" , "disabled");
    });

});

</script>

<div id = "contenido_alm" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos del Almacen</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('almacen/guardar');?>" onSubmit='return false' >
        <div class="box-body">
          <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$alm != NULL){ ?>
                        <input type="hidden" id="txt_idalm" name="txt_idalm" value="<?php if($alm != NULL){ print $alm->almacen_id; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idalm" name="txt_idalm" value="0">    
                <?php } ?>  
            <!-- Nombre del almcio -->
            <div class="form-group col-md-6">
                <label for="lb_nom">Nombre</label>
                <input type="text" class="form-control validate[required]" name="txt_nom" id="txt_nom" placeholder="Nombre del Almacen" value="<?php if(@$alm != NULL){ print @$alm->almacen_nombre; }?>" >
            </div>
            <div class="form-group col-md-6">
                <label for="lb_res">Responsable</label>
                <input type="text" class="form-control " name="txt_res" id="txt_res" placeholder="Nombre del Responsable" value="<?php if(@$alm != NULL){ print @$alm->almacen_responsable; }?>" >
            </div>
            <div class="form-group col-md-12">
                <label for="lb_res">Dirección</label>
                <input type="text" class="form-control " name="txt_dir" id="txt_dir" placeholder="Dirección del Almacen" value="<?php if(@$alm != NULL){ print @$alm->almacen_direccion; }?>" >
            </div>
            <div class="form-group col-md-12">
                <label for="lb_res">Descripción</label>
                <input type="text" class="form-control " name="txt_des" id="txt_des" placeholder="Descripción del Almacen" value="<?php if(@$alm != NULL){ print @$alm->almacen_descripcion; }?>" >
            </div>

            <!-- Almacen tipo Deposito -->
           
                <div class="col-md-6" style="padding-top: 26px;">
                    <label class="col-md-12"><input type="checkbox" name="chk_deposito" id="chk_deposito" class="minimal-red" <?php if(@$pro != NULL){ if(@$pro->deposito == 1){ print "checked='' ";} }?> > Es Deposito</label>
                </div>

                <div class="form-group col-md-6">
                    <label>Producto asociado</label>
                    <select id="cmb_prod" name="cmb_prod" class="form-control">
                        <?php 
                          if(@$productos != NULL){ ?>
                        <?php } else { ?>
                          <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } 
                                  if (count($productos) > 0) {
                                    foreach ($productos as $pro):
                                        if(@$alm->almacen_idproducto != NULL){
                                            if($pro->pro_id == $alm->almacen_idproducto){ ?>
                                                 <option value="<?php  print $pro->pro_id; ?>" selected="TRUE"> <?php  print $pro->pro_nombre ?> </option>
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $pro->pro_id; ?>" > <?php  print $pro->pro_nombre ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $pro->pro_id; ?>"> <?php  print $pro->pro_nombre ?> </option>
                                            <?php
                                            }   ?>
                                        <?php

                                    endforeach;
                                  }
                                  ?>
                    </select>
                </div>                  

           

                <div class="form-group col-md-6" style="padding-top: 26px;" >
                    <label class="col-md-12"><input type="checkbox" name="chk_tipoalma" id="chk_tipoalma" class="minimal-red" <?php if(@$alm != NULL){ if(@$alm->almacen_tipo == 1){ print "checked='' ";} }?> > Disponible para Venta</label>
                </div>                
                <!-- Productos -->            


            <div class="form-group col-md-6">
                <label>Sucursal</label>
                <select id="cmb_suc" name="cmb_suc" class="form-control">
                    <?php 
                      if(@$suc != NULL){ ?>
                    <?php } else { ?>
                        <option  value="0" selected="TRUE">Seleccione...</option>
                    <?php } 
                              if (count($suc) > 0) {
                                foreach ($suc as $su):
                                    if(@$alm->sucursal_id != NULL){
                                        if($su->id_sucursal == $alm->sucursal_id){ ?>
                                            <option  value="<?php  print $su->id_sucursal; ?>" selected="TRUE"><?php  print $su->nom_sucursal ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $su->id_sucursal; ?>"> <?php  print $su->nom_sucursal ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $su->id_sucursal; ?>"> <?php  print $su->nom_sucursal ?> </option>
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
                <button type="submit" class="btn btn-danger btn-grad no-margin-bottom">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>