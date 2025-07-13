<style>
  #contenido_formapago{
    width: 500px;
  }   

  #ui-datepicker-div{
    z-index: 9999999  !important;
  }

  .linea{
    border-width: 2px 0 0;
    margin-bottom: 3px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 

</style>
<?php 
  if(@$tipofp != NULL){ 
    $tarjeta = $tipofp->tarjeta; $banco = $tipofp->banco; 
  }
  else{ 
    $tarjeta = 0; $banco = 0; 
  }
  if(@$pago_rapido != NULL){ 
    switch ($pago_rapido) {
        case 2:
            $tarjeta = 0; $banco = 1; 
            break;
        case 3:
            $tarjeta = 1; $banco = 0; 
            break;
        default:
            $tarjeta = 0; $banco = 0; 
    } 
  }
  else{ 
    $tarjeta = 0; $banco = 0; 
  }
?>
<script>
$( document ).ready(function() {

  tarjeta = <?php print $tarjeta; ?>;
  banco = <?php print $banco; ?>;  
  if(tarjeta == 1 && banco == 0){ tipo = "Tarjeta"; }
  if(tarjeta == 0 && banco == 1){ tipo = "Banco"; }
  if(tarjeta == 0 && banco == 0){ tipo = "Efectivo"; }
  switch(tipo) {
    case 'Efectivo':
      $("#tarjetas").css("display","none");
      $("#banco").css("display","none");
    break;
    case 'Tarjeta':
      $("#tarjetas").css("display","block");
      $("#banco").css("display","none");
    break;
    case 'Banco':
      $("#tarjetas").css("display","none");
      $("#banco").css("display","block");
    break;                  
    default:
  }



  $('#fechat').datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd/mm/yy', 
    firstDay: 1
  });

  $('#fechat').on('changeDate', function(ev){
    $(this).datepicker('hide');
  });

  $('#fechae').datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd/mm/yy', 
    firstDay: 1
  });

  $('#fechae').on('changeDate', function(ev){
    $(this).datepicker('hide');
  });

  $('#fechac').datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd/mm/yy', 
    firstDay: 1
  });

  $('#fechac').on('changeDate', function(ev){
    $(this).datepicker('hide');
  });

  $("#formID").validationEngine();
/*  $("#tarjetas").css("display","none");
  $("#banco").css("display","none");
*/
  $(document).on('change','#cmb_forpago', function(){
    var idfp = $("#cmb_forpago option:selected").val();
    var tarjeta = 0;
    var banco = 0;      
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "<?php echo base_url('Facturar/selfp');?>",
      data: { idfp: idfp },
      success: function(json) {
        var tipo = "";
        tarjeta = json.tarjeta;
        banco = json.banco;  
        if(tarjeta == 1 && banco == 0){ tipo = "Tarjeta"; }
        if(tarjeta == 0 && banco == 1){ tipo = "Banco"; }
        if(tarjeta == 0 && banco == 0){ tipo = "Efectivo"; }
        switch(tipo) {
          case 'Efectivo':
            $("#tarjetas").css("display","none");
            $("#banco").css("display","none");
          break;
          case 'Tarjeta':
            $("#tarjetas").css("display","block");
            $("#banco").css("display","none");
          break;
          case 'Banco':
            $("#tarjetas").css("display","none");
            $("#banco").css("display","block");
          break;                  
          default:
        }

        var max = $("#txt_maxvalor").val();
        var strmonto = $("#txt_montofp").val();
        var monto = parseFloat(strmonto);
        if ((monto > max) && (idfp != 1)){
          if (parseFloat(max) < 0) max = 0;
          /*alert("El monto no pueder ser mayor a " + max);*/
          $("#txt_montofp").val(max);
        }                  
      }
    });
  });
  
  $(document).on('keyup','#txt_montofp', function(){
    var max = $("#txt_maxvalor").val();
    if (parseFloat(max) < 0) max = 0;
    var idfp = $("#cmb_forpago option:selected").val();

    var strmonto = $(this).val();
    var monto = parseFloat(strmonto);
    if ((monto > max) && (idfp != 1)){
      alert("El monto no pueder ser mayor a " + max);
      $(this).val(max);
    }
    
  });



});

</script>    
<div id = "contenido_formapago" class="col-md-6">
  <div class="box box-danger">
    <div class="box-header with-border">
      <h3 class="box-title"></i> Agregar Forma de Pago de <?php print $formapago; ?></h3>
      <?php 
          if(@$edifp != NULL){ ?>
              <input type="hidden" id="txt_idreg" name="txt_idreg" value="<?php if(@$edifp != NULL){ print @$edifp->idreg; }?>" >    
          <?php } else { ?>
              <input type="hidden" id="txt_idreg" name="txt_idreg" value="0">    
      <?php } ?>       
          <input type="hidden" id="txt_maxvalor" name="txt_maxvalor" value="<?php if(@$maxvalor != NULL){ print @$maxvalor; } else {print 0;}?>" >    
    </div>
    <div class="box-body">
      <div class="row">

        <?php 
          if(@$lstcaja != NULL){ ?>
          <div class="form-group col-md-8" style="margin-bottom: 5px;">
            <label>Caja</label>
            <select id="cmb_caja" name="cmb_caja" class="form-control validate[required]">
                <?php 
                  if (count($lstcaja) > 0) {
                    foreach ($lstcaja as $caja):
                      if(@$edifp != NULL) {
                        if($caja->id_caja == $edifp->id_cajapago){ ?>
                          <option  value="<?php  print $caja->id_caja; ?>" selected="TRUE"><?php  print $caja->nom_caja ?></option> 
                 <?php  }else{ ?>
                          <option value="<?php  print $caja->id_caja; ?>"> <?php  print $caja->nom_caja ?> </option>
                <?php   }
                      }else{ ?>
                        <option value="<?php  print $caja->id_caja; ?>"> <?php  print $caja->nom_caja ?> </option>
                <?php }
                    endforeach;
                  } ?>
              </select>
          </div>
        <?php }  ?>

        <div class="form-group col-md-8" style="margin-bottom: 5px;">
          <label>Forma de Pago</label>
          <select id="cmb_forpago" name="cmb_forpago" class="form-control validate[required]"">
            <?php 
              if(@$forpago != NULL){ 
                if(@$edifp != NULL){ } else { ?> <option  value="" selected="TRUE">Seleccione...</option> <?php }
                if (count($forpago) > 0) {
                  foreach ($forpago as $fp):
                    if((@$edifp != NULL) || (@$pago_rapido != NULL)){
                      if(@$edifp != NULL) {
                        $tmptipopago = $edifp->id_formapago;
                      }
                      else{
                        $tmptipopago = $pago_rapido;
                      }  
                      if($fp->id_formapago == $tmptipopago){ ?>
                        <option  value="<?php  print $fp->id_formapago; ?>" selected="TRUE"><?php  print $fp->nombre_formapago ?></option> 
               <?php  }else{ ?>
                        <option value="<?php  print $fp->id_formapago; ?>"> <?php  print $fp->nombre_formapago ?> </option>
              <?php   }
                    }else{ ?>
                      <option value="<?php  print $fp->id_formapago; ?>"> <?php  print $fp->nombre_formapago ?> </option>
              <?php }
                  endforeach;
                }
              }  ?>
            </select>
        </div>
        <div class="form-group col-md-4" style="margin-bottom: 5px;">
          <label for="">Monto</label>
          <input type="text" class="form-control validate[required]" name="txt_montofp" id="txt_montofp" placeholder="Monto" value="<?php if(@$edifp->monto != NULL){ print @$edifp->monto; }else{ print @$maxvalor; } ?>" >
        </div>
        <div id="tarjetas" class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                  <div class="form-group col-md-3" style="margin-bottom: 5px;">
                    <label for="">Fecha</label>
                    <div class="input-group date ">
                      <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                      <input type="text" class="form-control pull-right validate[required]" id="fechat" name="fechat" value="<?php if(@$edifp->fechaemision != NULL){ @$fec = str_replace('-', '/', $edifp->fechaemision); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec; }else{ print date("d/m/Y"); }  ?>">
                    </div>
                  </div> 
                  <div id="tarj" class="form-group col-md-4" style="padding-left: 0px; margin-bottom: 5px;">
                    <label>Tarjetas</label>
                    <select id="cmb_tarjeta" name="cmb_tarjeta" class="form-control">
                    <?php if(@$edifp->id_tarjeta != NULL){ } else { ?> <option  value="" selected="TRUE">Seleccione...</option> <?php }
                      if(@$tarjetas != NULL){ 
                        if (count($tarjetas) > 0) {
                         foreach ($tarjetas as $t):
                          if ($edifp != NULL){
                           if ($edifp->id_tarjeta != NULL){
                            if($t->id_tarjeta == $edifp->id_tarjeta){ ?>
                              <option selected="TRUE" value="<?php  print $t->id_tarjeta; ?>"> <?php  print $t->nombre; ?> </option>
                            <?php
                            }else{ ?>
                              <option value="<?php  print $t->id_tarjeta; ?>"> <?php  print $t->nombre; ?> </option>
                            <?php
                            }
                           } else{ ?>
                              <option value="<?php  print $t->id_tarjeta; ?>"> <?php  print $t->nombre; ?> </option>
                            <?php
                            } 
                          } else{ ?>
                              <option value="<?php  print $t->id_tarjeta; ?>"> <?php  print $t->nombre; ?> </option>
                            <?php
                            }
                          endforeach;
                          }
                          
                        } ?>
                      </select>
                  </div>      
                  <div class="form-group col-md-5" style="margin-bottom: 5px; padding-left: 0px ">
                      <label for="">Nro Tarjeta</label>
                      <input type="text" class="form-control validate[required]" name="txt_nrotar" id="txt_nrotar" placeholder="Nro Tarjeta" value="<?php if(@$edifp->numerotarjeta != NULL){ print @$edifp->numerotarjeta; } ?>" >
                  </div>                  
                  <div id="banc" class="form-group col-md-6" style="margin-bottom: 5px;">
                    <label>Bancos</label>
                    <select id="cmbt_banco" name="cmbt_banco" class="form-control">
                      <?php 
                        if(@$bancos != NULL){ 
                          if(@$edifp != NULL){ } else { ?> <option  value="" selected="TRUE">Seleccione...</option> <?php }
                          if (count($bancos) > 0) {
                            foreach ($bancos as $b):
                              if(@$edifp != NULL){
                                if($b->id_banco == $edifp->id_banco){ ?>
                                  <option  value="<?php  print $b->id_banco; ?>" selected="TRUE"><?php  print $b->nombre; ?></option> 
                         <?php  }else{ ?>
                                  <option value="<?php  print $b->id_banco; ?>"> <?php  print $b->nombre; ?> </option>
                        <?php   }
                              }else{ ?>
                                <option value="<?php  print $b->id_banco; ?>"> <?php  print $b->nombre; ?> </option>
                        <?php }
                            endforeach;
                          }
                        }  ?>
                    </select>
                  </div>
                  <div class="form-group col-md-6" style="padding-left: 0px; margin-bottom: 5px; ">
                      <label for="">Nro Documento</label>
                      <input type="text" class="form-control validate[required]" name="txt_tnrodoc" id="txt_tnrodoc" placeholder="Nro Documento" value="<?php if(@$edifp->numerodocumento != NULL){ print @$edifp->numerodocumento; }?>" >
                  </div>
                  <div class="form-group col-md-12" style="margin-bottom: 5px; ">
                      <label for="">Descripción Documento</label>
                      <input type="text" class="form-control validate[required]" name="txt_tdescdoc" id="txt_tdescdoc" placeholder="Desc Documento" value="<?php if(@$edifp->descripciondocumento != NULL){ print @$edifp->descripciondocumento; }?>" >
                  </div>
        </div>
        <div id="banco" class="col-md-12" style="padding-left: 0px; padding-right: 0px;">

                  <div class="form-group col-md-4" style="margin-bottom: 5px;">
                    <label for="">Fecha Emisión</label>
                    <div class="input-group date ">
                      <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                      <input type="text" class="form-control pull-right validate[required]" id="fechae" name="fechae" value=" <?php if(@$edifp->fechaemision != NULL){ @$fec = str_replace('-', '/', $edifp->fechaemision); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec; }else{ print date("d/m/Y"); } ?> ">
                    </div>
                  </div> 
                  <div class="form-group col-md-4" style="margin-bottom: 5px; padding-left: 0px;">
                    <label for="">Fecha Cobro</label>
                    <div class="input-group date ">
                      <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                      <input type="text" class="form-control pull-right validate[required]" id="fechac" name="fechac" value="<?php if(@$edifp->fechacobro != NULL){ @$fec = str_replace('-', '/', $edifp->fechacobro); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec; }else{ print date("d/m/Y"); } ?>">
                    </div>
                  </div> 
                  <div id="banc" class="form-group col-md-4" style="margin-bottom: 5px; padding-left: 0px;">
                    <label>Bancos</label>
                    <select id="cmb_banco" name="cmb_banco" class="form-control">
                      <?php 
                        if(@$bancos != NULL){ 
                          if(@$edifp != NULL){ } else { ?> <option  value="" selected="TRUE">Seleccione...</option> <?php }
                          if (count($bancos) > 0) {
                            foreach ($bancos as $b):
                              if(@$edifp != NULL){
                                if($b->id_banco == $edifp->id_banco){ ?>
                                  <option  value="<?php  print $b->id_banco; ?>" selected="TRUE"><?php  print $b->nombre; ?></option> 
                         <?php  }else{ ?>
                                  <option value="<?php  print $b->id_banco; ?>"> <?php  print $b->nombre; ?> </option>
                        <?php   }
                              }else{ ?>
                                <option value="<?php  print $b->id_banco; ?>"> <?php  print $b->nombre; ?> </option>
                        <?php }
                            endforeach;
                          }
                        }  ?>
                      </select>
                  </div>  

                  <div class="form-group col-md-5" style="margin-bottom: 2px; ">
                      <label for="">Nro Cuenta</label>
                      <input type="text" class="form-control validate[required]" name="txt_nrocta" id="txt_nrocta" placeholder="Nro Cuenta" value="<?php if(@$edifp->numerocuenta != NULL){ print @$edifp->numerocuenta; }else{ print 0; } ?>" >
                  </div>     


                  <div class="form-group col-md-5" style="margin-bottom: 2px; ">
                      <label for="">Nro Documento</label>
                      <input type="text" class="form-control" name="txt_nrodoc" id="txt_nrodoc" placeholder="Nro Documento" value="<?php if(@$edifp->numerodocumento != NULL){ print @$edifp->numerodocumento; } ?>" >
                  </div>

                  <div class="form-group col-md-12" style="margin-bottom: 5px; ">
                      <label for="">Descripción Documento</label>
                      <input type="text" class="form-control validate[required]" name="txt_descdoc" id="txt_descdoc" placeholder="Desc Documento" value="<?php if(@$edifp->descripciondocumento != NULL){ print @$edifp->descripciondocumento; } ?>" >
                  </div>
        </div>
      </div>
    </div>
    <div  align="center" class="box-footer">
      <div class="form-actions ">
        <button type="submit" class="btn btn-success btn-grad no-margin-bottom guardafp">
          <i class="fa fa-save "></i> Guardar
        </button>
      </div>
    </div>
  </div>
</div>