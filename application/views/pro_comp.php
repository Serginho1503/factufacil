<style>
#contenido_producto{
    width: 1000px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {

      $(document).on('click', '.adding', function(){
        id = $(this).attr('id');
        if (id) {
          $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Producto/tmp_ingpro",
            data: {id: id},
            success: function(json) {
                $('#det_ing').load(base_url + "Producto/actualiza_ingrediente");
            /*    $('#ing_resto').load(base_url + "Producto/actualiza_ingredisponible");             */
               /* alert("actualizo disponible"); */
            }    
          });
        }  
      });

    $(document).on('click','.proing_del', function(){
      id = $(this).attr('id');
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Producto/del_ingpro",
          data: {id: id},
          success: function(json) {
              $('#det_ing').load(base_url + "Producto/actualiza_proing");
              var newtotal = json.costo;  
              newtotal = parseFloat(newtotal);
              newtotal = newtotal.toFixed(2);
              $("#txttotal").text('Total Costo: $ ' + newtotal);            
              $('#ing_resto').load(base_url + "Producto/actualiza_ingredisponible"); 
          }
        });
        return false;   
    });


    $(document).on('change','.cantidad', function(){    
      var id = $(this).attr('id');
      var iding = id.substring(4);
      var idprod = parseFloat($('#prod' + iding).attr('name'));
      var unidad = parseFloat($('#cmb_unimed' + iding).val());
      var cantidad = parseFloat($('#'+id).val());

      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Producto/modifica_ingrediente",
        data: {iding: iding, idprod: idprod, unidad: unidad, cantidad: cantidad},
        success: function(json) {
          var costo = parseFloat($('#costo' + iding).val());
          var costototal = cantidad * costo;
          costototal = costototal.toFixed(2);

          var idcosto = '#costototal' + iding;    
          $(idcosto).val(costototal);
       
          var tot;
          tot=0;  
          $( ".costototal" ).each(function( index ) {
            tot=tot + parseFloat($( this ).val());
          });
          tot = tot.toFixed(2);
          
          $("#txttotal").text('Total Costo: $ ' + tot);        
        }
      });


    });


    $(document).on('change','.cmb_unimed', function(){    
      var id = $(this).attr('id');
      var iding = id.substring(10);
      var idprod = parseFloat($('#prod' + iding).attr('name'));
      var cantidad = parseFloat($('#cant' + iding).val());
      var unidad = parseFloat($('#'+id).val());

      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Producto/modifica_ingrediente",
        data: {iding: iding, idprod: idprod, unidad: unidad, cantidad: cantidad},
        success: function(json) {
        }
      });


    });

    // function actualiza_totales(){
    //   tot = 0;  
    //   $( ".prod" ).each(function( index ) {
    //     id = $(this).attr('id');
    //     var iding = id.substring(4);     
    //     var costo = parseFloat($('#costo' + iding).val());
    //     var cantidad = parseFloat($('#cant' + iding).val());
    //     costototal = costo * cantidad;
    //     if ($.isNumeric( costototal )){
    //       strcosto = costototal.toFixed(2);
    //       $('#costototal' + iding).val(strcosto);
    //       tot=tot + costototal;
    //     } else {
    //         //$('#costototal' + iding).val("0.00");
    //     }
    //   });
    //   tot = tot.toFixed(2);
      
    //   $("#txttotal").text('Total Costo: $ ' + tot);       
    // }

    });
</script>
<div id = "contenido_producto" class="col-md-12">
  <div class="box box-danger">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-shopping-bag"></i> Listado de Productos</h3>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
            <div class="col-md-4" style="padding-left: 0px;">
              <div id="ing_resto" class="box-body table-responsive" style="padding-left: 0px; padding-top: 0px;">
                  <table class="table table-bordered table-hover table-responsive">
                      <thead>
                          <tr>
                              <th>Ingredientes</th>
                          </tr>
                      </thead>    
                      <tbody>                                                        
                          <?php 
                          foreach ($pro as $p) {
                          ?>
                            <tr class="adding" id="<?php print $p->id; ?>">
                              <td>
                                <?php print $p->producto; ?>
                              </td>
                            </tr>
                          <?php 
                          }
                          ?>
                      </tbody>
                  </table>
              </div>              
            </div>

            <div class="col-md-8 panel panel-danger" style="padding-right: 0px; padding-left: 0px; border-color: #dd4b39; border-radius: 0px;">
              <div id="det_ing" class="col-md-12 table-responsive" style="padding-right: 0px; padding-top: 0px;"> 
                <table class="table table-bordered table-responsive">
                  <tbody>
                    <tr>
                      <th>Nombre</th>
                      <th class="text-center " style="width: 144px;">Uni Medida</th>
                      <th class="text-center col-md-2">Cantidad</th>
                      <th class="text-center col-md-1">Costo Uni</th>
                      <th class="text-center col-md-1">Costo Total</th>
                      <th class="text-center col-md-1">Acción</th>
                    </tr>
                    <?php
                      $costototal=0;  
                      if(@$deting != NULL){
                          if (count($deting) > 0) {
                              foreach ($deting as $dt):   
                    ?>
                    <tr>
                      <td>
                        <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                          <div class="col-md-8 prod" style="padding-left: 0px; padding-right: 0px;" id="prod<?php if(@$dt != NULL){ print @$dt->id_proing; }?>" name="<?php if(@$dt != NULL){ print @$dt->id_pro; }?>">
                        <?php  print $dt->pro_nombre; ?>                                    
                          </div>
                                                   
                        </div>
                      </td>
                      <td class="text-center">
                          <select id="cmb_unimed<?php if(@$dt != NULL){ print @$dt->id_proing; }?>" name="cmb_unimed" class="form-control cmb_unimed" style="height: 30px;">
                            <?php 
                            $unidad = &get_instance();
                            $unidad->load->model("Unidades_model");
                            $unimed = $unidad->Unidades_model->sel_unidadprod($dt->id_proing);
                            
                            if(@$unimed != NULL){ ?>
                              <option  value="0" selected="TRUE">Seleccione...</option>
                            <?php }  
                              if (count($unimed) > 0) {
                                foreach ($unimed as $um): 
                                  if(@$dt->unimed == $um->id){ ?>
                                    <option  value="<?php print $um->id; ?>" selected="TRUE"><?php  print $um->nombrecorto ?></option>
                                  <?php 
                                  }else{ ?>
                                    <option value="<?php  print $um->id; ?>" > <?php  print $um->nombrecorto ?> </option>
                                  <?php 
                                  }
                                  ?>
                                <?php
                                endforeach;
                              } ?>
                          </select>                                    
                      </td>
                      <td class="text-center">
                        <input type="text" class="form-control text-center cantidad" name="" id="cant<?php if(@$dt != NULL){ print @$dt->id_proing; }?>" value="<?php print $dt->cantidad; ?>" style="height: 30px;">
                      </td>
                      <td class="text-center">
                        <input type="text" class="form-control text-center costo" name="" id="costo<?php if(@$dt != NULL){ print @$dt->id_proing; }?>" value="<?php print $dt->pro_preciocompra; ?>" style="height: 30px;" readonly>                        
                      </td>
                      <td class="text-center">
                        <?php
                              $costoparcial= $dt->pro_preciocompra * $dt->cantidad;
                        ?>      
                        <input type="text" class="form-control text-center costototal" name="" id="costototal<?php if(@$dt != NULL){ print @$dt->id_proing; }?>" value="<?php print number_format($costoparcial,2); ?>" style="height: 30px;" readonly>
                      </td>                      
                      <td class="text-center">
                          <a href="#" title="Eliminar" id="<?php  if(@$dt != NULL){ print @$dt->id_proing; }?>"  class="btn btn-sm btn-danger btn-grad proing_del"><i class="fa fa-trash-o"></i></a>
                      </td>
                    </tr>
                    <?php
                              $costototal+= $costoparcial;
                            endforeach;
                        }
                        $costototal = round($costototal,2);
                    }
                    ?>    
                  </tbody>
                </table>
              </div>
            </div>              
        </div>
      </div>
    </div>
    <div class="box-footer" align="center">
      <div class="pull-right ">
        <h4 id="txttotal">Total Costo: $ <?php print number_format($costototal,2); ?></h4>
      </div>
    </div>    
  </div>
</div>