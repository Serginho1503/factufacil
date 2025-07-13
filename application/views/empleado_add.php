<style>
    #contenido_ret{
        width: 500px;
    }   
</style>
<script type="text/javascript">
    $("#formRET").validationEngine();


    /* BUSQUEDA DINAMICA POR CEDULA */
    $('#txt_ident').blur(function(){
      var ident = $(this).val();    
      var id = $("#txt_id").val();    

      if (ident === ""){
        alert("Debe ingresar un numero de identificación");
        return false;
      }   
      /* ruc / cedula valido*/
      var idtp = $('#cmb_tipoident option:selected').attr('name');      
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Utiles/validarIdentificacion",
          data: { tipo: idtp, identificacion: ident },
          success: function(json) {
            if (json.resu == 1){
              $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: base_url + "Empleado/existeIdentificacion",
                  data: { id: id, identificacion: ident },
                  success: function(json) {
                    if (json.resu != 0){
                        alert("El numero de identificación ya esta registrado para otro empleado");
                        $('#txt_ident').focus();
                        return false;
                    } 
                  }
              });
            } else {
                alert("El numero de identificación no es valido");
                $('#txt_ident').focus();
                return false;
              } 
          }
      });
    });


</script>
<div id = "contenido_ret" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Datos del Empleado</h3>
        </div>
        <form id="formRET" name="formRET" method='POST' action="#" onSubmit='return false' >
        <div class="box-body">
            <div class="row">
                <?php /* CAMPO HIDDEN CON EL ID  (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                    if(@$obj != NULL){ ?>
                        <input type="hidden" id="txt_id" name="txt_id" value="<?php if($obj != NULL){ print $obj->id_empleado; }?>" >    
                    <?php } else { ?>
                        <input type="hidden" id="txt_id" name="txt_id" value="0">    
                <?php } ?>  

                <div class="form-group col-md-12">
                    <label for="lb_cat">Nombre y Apellidos</label>
                    <input type="text" class="form-control validate[required]" name="txt_nombre" id="txt_nombre" placeholder="Nombre y Apellidos" value="<?php if(@$obj != NULL){ print @$obj->nombre_empleado; }?>" >
                </div>

                <div class="form-group col-md-12">
                    <div style="" class="form-group col-md-5">
                      <label for="lb_res">Tipo Identificacion</label>
                      <select id="cmb_tipoident" name="cmb_tipoident" class="form-control">
                      <?php 
                        if(@$tipoident != NULL){ ?>
                        <?php } else { ?>
                        <option  value="" selected="TRUE">Seleccione Tipo Identificacion...</option>
                        <?php } 
                          if (count($tipoident) > 0) {
                            foreach ($tipoident as $tipo):
                                if(@$obj->tipo_identificacion != NULL){
                                    if($obj->tipo_identificacion == $tipo->id_identificacion){ ?>
                                         <option name="<?php  print $tipo->cod_identificacion; ?>" value="<?php  print $tipo->id_identificacion; ?>" selected="TRUE"> <?php  print $tipo->desc_identificacion; ?> </option>
                                        <?php
                                    }else{ ?>
                                        <option name="<?php  print $tipo->cod_identificacion; ?>" value="<?php  print $tipo->id_identificacion; ?>" > <?php  print $tipo->desc_identificacion; ?> </option>
                                        <?php
                                    }
                                }else{ ?>
                                    <option name="<?php  print $tipo->cod_identificacion; ?>" value="<?php  print $tipo->id_identificacion; ?>" > <?php  print $tipo->desc_identificacion; ?> </option>
                                    <?php
                                    }   ?>
                                <?php
                            endforeach;
                          }
                        ?>
                      </select>                                  
                    </div>

                    <div class="form-group col-md-7">
                        <label for="lb_cat">Identificacion</label>
                        <input type="text" class="form-control validate[required]" name="txt_ident" id="txt_ident" placeholder="Identificacion" value="<?php if(@$obj != NULL){ print @$obj->nro_ident; }?>" >
                    </div>

                </div>

                <div class="form-group col-md-6 text-center" style="padding-left:0px;">
                    <input id="chkactivo" name="chkactivo" type="checkbox" <?php if(@$obj != NULL){ if(@$obj->activo == 1){ print " checked";} } else {print " checked";} ?> style="margin-top:31px; margin-right:0px; margin-left:0px;" > <strong>Activo</strong>
                </div>

                <div class="form-group col-md-6 text-center" style="padding-left:0px;">
                    <input id="chkestecnico" name="chkestecnico" type="checkbox" <?php if(@$obj != NULL){ if(@$obj->es_tecnico == 1){ print " checked";} } else {print " checked";} ?> style="margin-top:31px; margin-right:0px; margin-left:0px;" > <strong>Es Tecnico</strong>
                </div>

            </div>
        </div>
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="button" class="btn btn-success btn-grad no-margin-bottom btnguardar">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>