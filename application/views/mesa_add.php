<style>
#contenido_mesa{
/*margin:auto;*/
width: 400px;

}   
</style>
<div id = "contenido_mesa" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos del Punto</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('mesa/guardar');?>" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$mesa != NULL){ ?>
                        <input type="hidden" id="txt_idmesa" name="txt_idmesa" value="<?php if($mesa != NULL){ print $mesa->id_mesa; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idmesa" name="txt_idmesa" value="0">    
                <?php } ?>  
                <!-- Nombre de Mesa -->
                <div class="form-group col-md-12">
                    <label for="lb_cat">Nombre </label>
                    <input type="text" class="form-control validate[required]" name="txt_nommesa" id="txt_nommesa" placeholder="Nombre " value="<?php if(@$mesa != NULL){ print @$mesa->nom_mesa; }?>" >
                </div>
                <div class="form-group col-md-4">
                    <label for="lb_cat">Capacidad</label>
                    <input type="text" class="form-control validate[required]" name="txt_capacidad" id="txt_capacidad" value="<?php if(@$mesa != NULL){ print @$mesa->capacidad; }?>" >
                </div>            
                <div class="form-group col-md-8">
                    <label>Area</label>
                    <select class="form-control validate[required]" id="cmb_area" name="cmb_area">
                        <?php 
                          if(@$area != NULL){ ?>
                            <option  value="" selected="TRUE">Seleccione...</option>
                        <?php }  
                                  if (count($area) > 0) {
                                    foreach ($area as $ar):
                                        if(@$mesa->id_area != NULL){
                                            if($ar->id_area == $mesa->id_area){ ?>
                                                <option  value="<?php  print $ar->id_area; ?>" selected="TRUE"><?php  print $ar->nom_area ?></option> 
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $ar->id_area; ?>"> <?php  print $ar->nom_area ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $ar->id_area; ?>"> <?php  print $ar->nom_area ?> </option>
                                            <?php
                                            }   ?>
                                        <?php

                                    endforeach;
                                  }
                                  ?>
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label>Impresora</label>
                    <select class="form-control validate[required]" id="cmb_imp" name="cmb_imp">
                        <?php 
                          if(@$imp != NULL){ ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php }  
                                  if (count($imp) > 0) {
                                    foreach ($imp as $i):
                                        if(@$mesa->id_comanda != NULL){
                                            if($i->id_comanda == $mesa->id_comanda){ ?>
                                                <option  value="<?php  print $i->id_comanda; ?>" selected="TRUE"><?php  print $i->nom_comanda ?></option> 
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $i->id_comanda; ?>"> <?php  print $i->nom_comanda ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $i->id_comanda; ?>"> <?php  print $i->nom_comanda ?> </option>
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