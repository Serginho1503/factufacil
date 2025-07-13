                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Producto</th>
                          <th class="text-center col-md-1">Cantidad</th>
                          <div class="tipo_precio">  
                            <?php if($tp == 1){ ?> 
                              <th class="text-center col-md-2 ">Tipo</th>
                            <?php } ?>
                          </div>
                          <th class="text-center col-md-1">Precio</th>
                          <th class="text-center col-md-1">SubTotal</th>
                          <?php if($descpro == 1) { ?>                     
                            <th class="text-center col-md-1"><?php if (@$tipodescprod == 1) { print '% ';} ?>Desc</th>                             
                          <?php } ?>                          
                          <th class="text-center col-md-1">SubT-Desc</th>
                          <?php if($habilitadetalletotaliva == 1) { ?>                     
                            <th class="text-center col-md-1">SubT/IVA</th>                             
                          <?php } ?>                          
                          <th class="text-center col-md-1">Acción</th>
                        </tr>
                      </thead>    
                      <tbody>                                                        
                        <?php 
                        $num=0;
                        foreach ($lstdetalle as $det) {
                          $num++;
                        ?>
                          <tr class="detallepro" id="<?php print $det->id_detalle; ?>" title="<?php if (@$det->id_serie != NULL) { print 'Serie: ' . $det->numeroserie; } ?>">
                            <td>
                              <?php print $num; ?>
                              <input type="hidden" class="grabaiva" id="<?php print @$det->id_detalle ?>" name="" value="<?php print @$det->pro_grabaiva ?>" >    
                            </td>
                            <td >
                              <a style="color: #449B2E;" href="#" title="Editar" id="<?php print $det->id_detalle; ?>" class="facteditprox"><i class="fa fa-edit"></i></a>
                              <?php if (@$det->id_serie != NULL) { print '<u>'; } ?>
                                <?php print substr($det->pro_nombre, 0, 35); ?>
                              <?php if (@$det->id_serie != NULL) { print '</u>'; } ?>
                            </td>
                            <td class="text-center datacantidad" id="<?php print @$det->id_detalle ?>">
                              <input type="text" class="form-control text-center cantidad tdprecio" name="" id="<?php print @$det->id_detalle ?>" value="<?php if(@$det != NULL){ print number_format(@$det->cantidad, $decimalescantidad); }?>" <?php if(($habilitaserie == 1) && ($det->estserie > 0)) {print 'disabled';} ?> >
                            </td>
                            <div class="tipo_precio">
                              <?php if($tp == 1){ ?>  
                                <td>
                                  <div class="col-md-12">
                                    <select id="<?php print $det->id_producto; ?>" name="<?php print @$det->id_detalle ?>" class="form-control tipoprecio">
                                      <?php 
                                      if(@$preciopro != NULL){ 
                                        if (count($preciopro) > 0) {
                                          foreach ($preciopro as $pp): 
                                            if($det->id_producto == $pp->pro_id){
                                              if($det->tipprecio == $pp->idprepro){ ?>
                                                  <option value="<?php  print $pp->idprepro;?>" selected="TRUE"> <?php  print $pp->desc_precios." - ".$pp->precio ?> </option>
                                      <?php
                                              }else{ ?>
                                              <option value="<?php  print $pp->idprepro; ?>"> <?php  print $pp->desc_precios." - ".$pp->precio ?>  </option>
                                      <?php
                                              }
                                            }
                                          endforeach;
                                        }
                                      }
                                      ?>
                                    </select>  
                                  </div>                                    
                                </td>
                              <?php } ?>  
                            </div>
                            <td class="text-center">
                              <input type="text" class="form-control text-center precio tdprecio" name="" id="<?php print @$det->id_detalle ?>" value="<?php if(@$det != NULL){ print number_format(@$det->precio,$decimalesprecio); }?>" 
                                <?php if ((@$cambioprecio == 1) && (@$perfil != 1)) {print "disabled";} ?>
                              >
                              
                            </td>
                            <td class="text-right">
                              <div id="<?php print @$det->id_detalle; ?>" class="subtotal">
                                <?php print number_format($det->subtotal,2); ?>
                              </div>
                            </td>       
                            <?php if($descpro == 1) { ?>                     
                              <td class="text-center">
                                <input type="text" class="form-control text-center descpro" name="" 
                                  id="<?php print @$det->id_detalle ?>" 
                                  value="<?php if(@$det != NULL){ if (@$tipodescprod == 1) {$tmpdesc = @$det->porcdesc;} else {$tmpdesc = @$det->descmonto;} print number_format(@$tmpdesc, 2); }?>" >
                              </td>                              
                            <?php } ?>

                            <td class="text-right">
                              <div id="<?php print @$det->id_detalle; ?>" class="descsubtotal">
                                <?php print number_format($det->descsubtotal,2); ?>
                              </div>
                            </td>                        

                            <?php if($habilitadetalletotaliva == 1) { ?>                     
                              <td class="text-center">
                                <div id="<?php print @$det->id_detalle; ?>" class="detalletotaliva">
                                  <?php if($det->pro_grabaiva == 1) {$tmpvalor = round(($det->precio * $det->cantidad - $det->descmonto) * (1 + $tarifaiva) ,2);} else {$tmpvalor = $det->descsubtotal;} print number_format($tmpvalor,2); ?>
                                </div>
                              </td>                              
                            <?php } ?>

                            <td class="text-center">
                                <?php if(($habilitaserie == 1) && ($det->estserie > 0)) { ?>
                                <a href="#" title="Nro Serie/Imei" id="<?php  if(@$det != NULL){ print @$det->id_producto; }?>" name="<?php  if(@$det != NULL){ print @$det->id_detalle; }?>" class="btn btn-xs btn-warning btn-grad pro_serimei"><i class="fa fa-slack"></i></a>
                                <?php } ?>                                
                                <a href="#" title="Eliminar" id="<?php  if(@$det != NULL){ print @$det->id_detalle; }?>" class="btn btn-xs btn-danger btn-grad pro_del"><i class="fa fa-trash-o"></i></a>
                            </td>
                          </tr>
                        <?php 
                        
                        }
                        ?>
                      </tbody>
                    </table>