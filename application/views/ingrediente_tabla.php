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
          <a href="#" title="Eliminar" id="<?php  if(@$dt != NULL){ print @$dt->id_proing; }?>" class="btn btn-sm btn-danger btn-grad proing_del"><i class="fa fa-trash-o"></i></a>
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