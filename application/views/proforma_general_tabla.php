                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Productos</th>
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
                          <th class="text-center col-md-1">Subt-Des</th>
                          <th class="text-center col-md-1">Acción</th>
                        </tr>
                      </thead>    
                      <tbody>                                                        
                        <?php 
                        $num=0;
                        foreach ($lstdetalle as $det) {
                          $num++;
                        ?>
                          <tr class="detallepro" id="<?php print $det->id_detalle; ?>">
                            <td>
                              <?php print $num; ?>
                              <input type="hidden" class="grabaiva" id="<?php print @$det->id_detalle ?>" name="" value="<?php print @$det->pro_grabaiva ?>" >    
                            </td>
                            <td>
                              <a style="color: #449B2E;" href="#" title="Editar" id="<?php print $det->id_detalle; ?>" class="facteditprox"><i class="fa fa-edit"></i></a>
                              <?php print substr($det->descripcion, 0, 35); ?>
                            </td>
                            <td class="text-center">
                              <input type="text" class="form-control text-center cantidad" name="" id="<?php print @$det->id_detalle ?>" value="<?php if(@$det != NULL){ print @$det->cantidad; }?>" >
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
                                                  <option value="<?php  print $pp->idprepro;?>" selected="TRUE"> <?php  print $pp->Tienda." - ".$pp->precio ?> </option>
                                      <?php
                                              }else{ ?>
                                              <option value="<?php  print $pp->idprepro; ?>"> <?php  print $pp->Tienda." - ".$pp->precio ?>  </option>
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
                              <input type="text" class="form-control text-center precio" name="" id="<?php print @$det->id_detalle ?>" value="<?php if(@$det != NULL){ print @$det->precio; }?>" <?php if ((@$cambioprecio == 1) && (@$perfil != 1)) {print "disabled";} ?> >
                            </td>
                            <td class="text-right">
                              <div id="<?php print @$det->id_detalle; ?>" class="subtotal">
                                <?php print number_format($det->subtotal,2); ?>
                              </div>
                            </td>                        
                            <?php if($descpro == 1) { ?>                     
                              <td class="text-center">
                                <input type="text" class="form-control text-center descpro" name="" id="<?php print @$det->id_detalle ?>" 
                                       value="<?php if(@$det != NULL){ if (@$tipodescprod == 1) {print number_format(@$det->porcdesc, 2); } else {print number_format(@$det->descmonto, 2);} } ?>" 
                                >
                              </td>                              
                            <?php } ?>
                            <td class="text-right">
                              <div id="<?php print @$det->id_detalle; ?>" class="descsubtotal">
                                <?php print number_format($det->descsubtotal,2); ?>
                              </div>
                            </td>                        
                            <td class="text-center">
                                <a href="#" title="Eliminar" id="<?php  if(@$det != NULL){ print @$det->id_detalle; }?>" class="btn btn-sm btn-danger btn-grad pro_del"><i class="fa fa-trash-o"></i></a>
                            </td>
                          </tr>
                        <?php 
                        }
                        ?>
                      </tbody>
                    </table>