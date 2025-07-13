<?php

$cfgserv = &get_instance();
$cfgserv->load->model("Serviciotecnico_model");
$configserv = $cfgserv->Serviciotecnico_model->lst_configservicio();
$mostrar_serie = $configserv->habilita_serie;
if ( $mostrar_serie == '' ) { $mostrar_serie = 0; }

?>

<style>
#contenido_rete{
    width: 500px;
}   

#ui-datepicker-div{
  z-index: 9999999  !important;
} 

    .autocomplete-jquery-results{
        border:1px solid silver;
        float:right;
        margin-top:2px;
        position:absolute;
        display: none;
        z-index: 999;
    }

    /*Esta clase se activa cuando el usuario se mueve por las sugerencias*/
    .autocomplete-jquery-mark{
        color:black;
        background-color: #E0F0FF !important;
    }

    /* Cada sugerencia va a llevar esta clase, por lo tanto tomara el estilo siguiente */
    .autocomplete-jquery-item{
        border-bottom: 1px solid lightgray;
        display: block;
        height: 25px;
        padding-top: 5px;
        text-decoration: none;     
        padding-left: 3px;  
        background-color: white;
    }
    .autocomplete-jquery-results{
         box-shadow: 1px 1px 3px black;
    }
    /* Al pasar por encima de las sugerencias*/
    .autocomplete-jquery-item:hover{
        background-color: #E0F0FF;
        color:black;
    }


</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formSubDet").validationEngine();

        $('#fecrealizado').on('changeDate', function(ev){
            $(this).datepicker('hide');
        });
        $("#fecrealizado").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy', 
          firstDay: 1
        });

        $('#fecentregado').on('changeDate', function(ev){
            $(this).datepicker('hide');
        });
        $("#fecentregado").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy', 
          firstDay: 1
        });

    });

    $(document).on('change','#cmb_estado', function(){
      actualiza_estado();
    });   

    function actualiza_estado(){
      var estado = $('#cmb_estado').val();
      if (estado >= 3) {
        $("#fecrealizado").attr("disabled", false);
        $("#trabajorealizado").attr("disabled", false);        
      } else {
        $("#fecrealizado").attr("disabled", true);
        $("#fecrealizado").val("");
        $("#trabajorealizado").attr("disabled", true);        
        $("#trabajorealizado").val("");        
      }
      if (estado >= 4) {
        $("#fecentregado").attr("disabled", false);
      } else {
        $("#fecentregado").attr("disabled", true);
        $("#fecentregado").val("");
      }
    }     

    $('.autocomplete').autocomplete();

    $('#txt_serie').blur(function(){
      var serie = $(this).val(); 
      if ($.trim(serie) === ""){
          $('#idserie').val(0);
      }
    });
    $(document).on('click', '.form-control', function(){  
      $('.autocomplete-jquery-results').each(function(index, el) {
        this.remove();
      });
    });

    $(document).on('click', '.autocomplete-jquery-item', function(){  
      var nom = $(this).text(); 
      autocomplete_serie(nom);
    });

    function autocomplete_serie(serie){
      if (serie === ""){
        alert("Debe ingresar una serie");
        return false;
      }
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Serviciotecnico/busca_serie');?>",
          data: {
              serie: serie
           },
          success: function(json) {
              $('#idserie').val(json.mens.id_serie);
              $('#txt_serie').val(serie);
              $('#txt_producto').val(json.mens.pro_nombre);
              actualizadatosservicio();
          }
      });

    }

    actualiza_estado();

    var mostrar_serie = <?php print $mostrar_serie ?>;
    if (mostrar_serie == 0) {
      $(".noverahora").hide();
    }  

</script>
<div id = "contenido_rete" class="col-md-6">
    <div class="box box-danger">
        <input type="hidden" id="txt_iddetalle" name="txt_iddetalle" value="<?php if(@$cliente != NULL){ print @$cliente->id_detalle; } else {print 0;} ?>" >    
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-registered"></i> Detalle de Servicio</h3>
        </div>
<!--         <form id="formID" name="formID" method='POST' action="<?php echo base_url('Serviciotecnico/upd_detalletmp');?>">
 -->         <form id="formSubDet" name="formSubDet" method='POST' action="#" onSubmit='return false' >
          <div class="box-body">
            <div class="row">

                <div class="form-group col-md-12">
                  <input type="hidden" id="idserie" name="idserie" value="<?php if(@$cliente != NULL){ print @$cliente->id_serie; } else {print 0;} ?>" >    
                  
                  <div class="col-md-4  noverahora">
                    <label>Numero de Serie</label>
                  </div>
                  <div class="col-md-8 autocomplete noverahora">
                   <input type="text" class="form-control" name="txt_serie" id="txt_serie" placeholder="Serie de Producto" data-source="<?php echo base_url('Serviciotecnico/valproductoserie?serie=');?>" value="<?php if(@$cliente != NULL){ print @$cliente->numeroserie; } ?>">
                  </div>
                </div>

                <div class="form-group col-md-12 noverahora">
                  <div class="col-md-4">
                    <label>Nombre de Producto</label>
                  </div>
                  <div class="col-md-8">
                   <input type="text" class="form-control col-md-3" name="txt_producto" id="txt_producto" value="<?php if(@$cliente != NULL){ print @$cliente->productoserie; } ?>" readonly>
                  </div>
                </div>

                 <!-- Observaciones -->
                <div class="form-group col-md-12">
                   <div class="col-md-4">
                    <label>Observaciones</label>
                   </div>
                   <div class="col-md-8">
                     <textarea class="form-control" rows="2" name="observaciones" id="observaciones" placeholder="Observaciones" ><?php if(@$cliente != NULL) { print @$cliente->descripcion; }?></textarea>
                   </div>
                </div>

                <!-- Subdetalles  -->
                <?php if (@$detalles != NULL){
                  foreach ($detalles as $det) {
                ?>
                   <div class="form-group col-md-12">
                     <div class="col-md-4">
                      <label><?php print $det->nombre_configdetalle; ?></label>
                     </div>
                     <div class="col-md-8">
                        <input type="text" class="form-control text-center subdetalle" name="detallevalor<?php print @$det->id_config ?>" id="detallevalor<?php print @$det->id_config ?>" value="<?php if(@$det != NULL){ print @$det->valor; }?>" >
                     </div>
                   </div>
                <?php                
                  } 
                }
                ?>

                <!-- Encargado  -->
                <?php if ($configservicio->habilita_encargado == 1){ ?>
                <div class="form-group col-md-12" id="seccionencargado">
                 <div class="col-md-4" >
                  <label for="lb_res">Encargado</label>
                 </div> 
                 <div class="col-md-8" >
                  <select id="cmb_encargado" name="cmb_encargado" class="form-control">
                  <?php 
                    if(@$empleados != NULL){ ?>
                    <?php } else { ?>
                    <option  value="" selected="TRUE">Seleccione Encargado...</option>
                    <?php } 
                      if (count($empleados) > 0) {
                        foreach ($empleados as $obj):
                            if(@$cliente->id_tecnico != NULL){
                                if($obj->id_empleado == $cliente->id_tecnico){ ?>
                                     <option value="<?php  print $obj->id_empleado; ?>" selected="TRUE"> <?php  print $obj->nombre_empleado; ?> </option>
                                    <?php
                                }else{ ?>
                                    <option value="<?php  print $obj->id_empleado; ?>" > <?php  print $obj->nombre_empleado; ?> </option>
                                    <?php
                                }
                            }else{ ?>
                                <option value="<?php  print $obj->id_empleado; ?>" > <?php  print $obj->nombre_empleado; ?> </option>
                                <?php
                                }   ?>
                            <?php
                        endforeach;
                      }
                    ?>
                  </select>          
                 </div>                         
                </div>
                <?php } ?>

                <div class="form-group col-md-12">
                  <div class="col-md-4">
                    <label for="lb_res">Estado</label>
                   </div> 
                   <div class="col-md-8">
                    <select id="cmb_estado" name="cmb_estado" class="form-control">
                    <?php 
                      if(@$estados != NULL){ ?>
                      <?php } else { ?>
                      <option  value="" selected="TRUE">Seleccione Estado...</option>
                      <?php } 
                        if (count($estados) > 0) {
                          $cc = 0;
                          foreach ($estados as $obj):
                              if(@$cliente != NULL){
                                  if($obj->id_estado == $cliente->id_estado){ ?>
                                       <option value="<?php  print $obj->id_estado; ?>" selected="TRUE"> <?php  print $obj->nombre_estado; ?> </option>
                                      <?php
                                  }else{ ?>
                                      <option value="<?php  print $obj->id_estado; ?>" > <?php  print $obj->nombre_estado; ?> </option>
                                      <?php
                                  }
                              }else{ ?>
                                  <option value="<?php  print $obj->id_estado; ?>" <?php if ($cc == 0) {$cc=1; print "selected='TRUE'";} ?> > <?php  print $obj->nombre_estado; ?> </option>
                                  <?php
                                  }   ?>
                              <?php
                          endforeach;
                        }
                      ?>
                    </select>          
                  </div> 
                </div>

                 <div class="form-group col-md-12">
                    <div class="col-md-4">
                      <label for="">Fecha Realizado</label>
                    </div>  
                    <div class="col-md-8">
                     <div style="margin-bottom: 0px;" class="form-group" >
                      <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right validate[required]" id="fecrealizado" name="fecrealizado" value="<?php if(@$cliente != NULL){ if(@$cliente->id_estado >= 3){ $fec =  str_replace('-', '/', $cliente->fecha_realizado); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec;}} ?>">
                      </div>                             
                     </div>
                    </div>
                 </div>  

                 <!-- Trabajo realizado -->
                 <div class="form-group col-md-12">
                   <div class="col-md-4">
                    <label>Trabajo Realizado</label>
                   </div>
                   <div class="col-md-8">
                     <textarea class="form-control" rows="2" name="trabajorealizado" id="trabajorealizado" placeholder="Trabajo realizado" ><?php if(@$cliente != NULL){ if(@$cliente->trabajo_realizado != NULL){ print @$cliente->trabajo_realizado; }}?></textarea>
                   </div>
                 </div>

                 <div class="form-group col-md-12">
                    <div class="col-md-4">
                      <label for="">Fecha Entregado</label>
                    </div>  
                    <div class="col-md-8">
                     <div style="margin-bottom: 0px;" class="form-group" >
                      <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right validate[required]" id="fecentregado" name="fecentregado" value="<?php if(@$cliente != NULL){ if(@$cliente->id_estado >= 4){ $fec =  str_replace('-', '/', $cliente->fecha_entregado); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec;}} ?>">
                      </div>                             
                     </div>
                    </div>
                 </div>  

      
            </div>
        </div>
        <!-- /.box-body -->
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="submit" class="btn btn-success btn-grad no-margin-bottom btnguardardetalle">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>