<style>
    #contenido_ban{
        width: 400px;
    }   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();
    });    
</script>
<div id = "contenido_ban" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos del Banco</h3>
        </div>
        <form id="formID" name="formID" method='POST' action="" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php 
                    if(@$ban != NULL){ ?>
                        <input type="hidden" id="txt_idban" name="txt_idban" value="<?php if($ban != NULL){ print $ban->id_banco; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_idban" name="txt_idban" value="0">    
                <?php } ?>  
                <div class="form-group col-md-12">
                    <label>Tipo</label>
                    <select class="form-control validate[required]" id="cmb_tipo" name="cmb_tipo">
                        <?php 
                          if(@$tpban != NULL){ ?>
                            <option  value="" selected="TRUE">Seleccione...</option>
                        <?php }  
                                  if (count($tpban) > 0) {
                                    foreach ($tpban as $tb):
                                        if(@$ban->id_tipo != NULL){
                                            if($tb->id == $ban->id_tipo){ ?>
                                                <option  value="<?php  print $tb->id; ?>" selected="TRUE"><?php  print $tb->nombre ?></option> 
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $tb->id; ?>"> <?php  print $tb->nombre ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $tb->id; ?>"> <?php  print $tb->nombre ?> </option>
                                            <?php
                                            }   ?>
                                        <?php

                                    endforeach;
                                  }
                                  ?>
                    </select>
                </div>                
                <div class="form-group col-md-12">
                    <label for="lb_cat">Nombre del Banco</label>
                    <input type="text" class="form-control validate[required]" name="txt_ban" id="txt_ban" placeholder="Nombre del Banco" value="<?php if(@$ban != NULL){ print @$ban->nombre; }?>" >
                </div>

            </div>
        </div>
        
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="submit" class="btn btn-danger btn-grad no-margin-bottom ban_save">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>