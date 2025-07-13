                <table class="table table-bordered detmov table-responsive">
                  <tbody>
                    <tr>
                        <th class="text-center " style="width: 10px;">Nro</th>
                        <th class="text-center col-md-1">Cod Barra</th>
                        <th>Producto</th>
                        <?php if ($mostrarvalores == 1) { ?>
                        <th class="text-center col-md-1">Precio</th>
                        <th class="text-center col-md-1">Existencia</th>
                        <?php } ?>                       
                        <th class="text-center col-md-1">Cantidad</th>
                        <th class="text-center col-md-2" style="width: 144px;">Uni Medida</th>
                        <?php if ($mostrarvalores == 1) { ?>
                        <th class="text-center col-md-1">SubTotal</th>
                        <?php } ?>                       
                        <th class="text-center " style="width: 10px;">Acción</th>
                    </tr>
                    <?php 
                      $total = 0;                                                                            
                      $nro = 0; 
                      if(@$detmov != NULL){
                        if (count($detmov) > 0) {
                          foreach ($detmov as $dc):
                            $nro = $nro + 1;
                            $total = $total + @$dc->montototal;


                    ?>
                    <tr>
                        <!-- NRO -->
                        <td class="text-center"><?php print $nro; ?></td>
                        <!-- CODIGO DE BARRA -->
                        <td class="text-center"><?php print @$dc->pro_codigobarra; ?></td>
                        <!-- NOMBRE DEL PRODUCTO -->
                        <td class="text-left"><?php print @$dc->pro_nombre; ?></td>
                        <!-- PRECIO DEL PRODUCTO -->
                        <?php if ($mostrarvalores == 1) { ?>
                        <td class="text-center">
                          <input type="text" class="form-control text-center precio" name="" id="<?php print @$dc->id; ?>" value="<?php if(@$dc != NULL){ print @$dc->precio_compra; }?>" >
                        </td>
                        <!-- EXISTENCIA DEL PRODUCTO -->
                        <td class="text-center"><?php print @$dc->existencia; ?></td>
                        <?php } else { ?>                       
                          <input type="hidden" class="form-control text-center precio" name="" id="<?php print @$dc->id; ?>" value="<?php if(@$dc != NULL){ print @$dc->precio_compra; }?>" >
                        <?php } ?>                       
                        <!-- CANTIDAD -->
                        <td class="text-center">
                          <input type="text" class="form-control text-center cantidad" name="" id="<?php print @$dc->id; ?>" value="<?php if(@$dc != NULL){ print @$dc->cantidad; }?>" >
                        </td>
                        <!-- UNIDAD DE MEDIDA -->
                        <td class="text-center">
                            <select id="<?php print @$dc->id; ?>" name="cmb_proveedor" class="form-control unidadmedida">
                              <?php 
                              $unidad = &get_instance();
                              $unidad->load->model("Unidades_model");
                              $unimed = $unidad->Unidades_model->sel_unidadprod($dc->pro_id);
                              
                              if(@$unimed != NULL){ ?>
                                <option  value="0" selected="TRUE">Seleccione...</option>
                              <?php }  
                                if (count($unimed) > 0) {
                                  foreach ($unimed as $um): 
                                    if(@$dc->id_unimed == $um->id){ ?>
                                      <option  value="<?php print $um->id; ?>" selected="TRUE"><?php  print $um->nombrecorto; ?></option>
                                    <?php 
                                    }else{ ?>
                                      <option value="<?php  print $um->id; ?>" > <?php  print $um->nombrecorto; ?> </option>
                                    <?php 
                                    }
                                    ?>
                                  <?php
                                  endforeach;
                                } ?>
                            </select>                                    
                        </td>
                        <!-- SUBTOTAL -->
                        <?php if ($mostrarvalores == 1) { ?>
                        <td class="text-right"><?php if(@$dc != NULL){ print @$dc->montototal; }?></td>
                        <?php } ?>                       
                        <!-- ACCION -->
                        <td class="text-center">
                          <a href="#" title="Eliminar" id="<?php if(@$dc != NULL){ print @$dc->id; }?>" class="btn btn-danger btn-xs btn-grad promov_del"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                    <?php 
                            endforeach;
                        }
                    } 
                    ?>
                  </tbody>
                </table>
                <div class="pull-right">
                  <a class="btn btn-danger btn-sm del_todoproducto" href="#" data-original-title="" title=""><i class="fa fa-trash"></i> Borra Productos </a> 
                </div>