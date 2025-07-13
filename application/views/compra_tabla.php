<table class="table table-bordered detcompra table-responsive">
                  <tbody>
                    <tr>
                        <th class="text-center " style="width: 10px;">Acción</th>
                        <th class="text-center " style="width: 10px;">Nro</th>
                        <th class="text-center col-md-1">Cod Barra</th>
                        <th>Producto</th>
                        <?php if ($habilitaserie == 1) { ?>
                          <th class="text-center col-md-1">Imei/Serie</th>
                        <?php } ?>
                        <th class="text-center col-md-1">Precio</th>
                        <th class="text-center col-md-1">Existencia</th>
                        <th class="text-center col-md-1">Cantidad</th>
                        <th class="text-center col-md-2" style="width: 144px;">Uni Medida</th>
                        <th class="text-center col-md-1" style="width: 10px;">IVA</th>
                        <th class="text-center col-md-1">SubTotal</th>
                        <th class="text-center col-md-1">Desc SubTotal</th>
                    </tr>
                    <?php 
                      $stciva = 0;
                      $stsiva = 0;  
                      $dstciva = 0;
                      $dstsiva = 0;
                      $moniva = 0;
                      $total = 0;
                      
                                                          
                    $nro = 0; 
                    $desc = @$tmpcomp->desc_monto;
                    if(@$detcomp != NULL){
                      if (count($detcomp) > 0) {
                        foreach ($detcomp as $dc):
                          $nro = $nro + 1;
                          if($desc > 0){ $tbsubcdesc = @$dc->descsubtotal; }
                          else { $tbsubcdesc = '0,00'; }
                          if(@$dc->iva == 1) { 
                            $dstciva = $dstciva + @$dc->descsubtotal; 
                            $stciva = $stciva + @$dc->subtotal;
                          } 
                          else { 
                            $dstsiva = $dstsiva + @$dc->descsubtotal; 
                            $stsiva = $stsiva + @$dc->subtotal;
                          }
                          $moniva = $moniva + @$dc->montoiva;
                          $total = $total + @$dc->descsubtotal;


                    ?>
                    <tr>
                        <!-- ACCION -->
                        <td class="text-center">
                          <a href="#" title="Eliminar" id="<?php if(@$dc != NULL){ print @$dc->id; }?>" class="btn btn-danger btn-xs btn-grad procomp_del"><i class="fa fa-trash-o"></i></a>
                        </td>
                        <!-- NRO -->
                        <td class="text-center"><?php print $nro; ?></td>
                        <!-- CODIGO DE BARRA -->
                        <td class="text-center"><?php print @$dc->pro_codigobarra; ?></td>
                        <!-- NOMBRE DEL PRODUCTO -->
                        <td class="text-left"><?php print substr(@$dc->pro_nombre,0,20); ?></td>
                        <!-- NOTA DEL PEDIDO -->
                        <?php if ($habilitaserie == 1) { ?>
                        <td>
                          <div class="text-center">
                            <a href="#" title="Añadir Imei/Serie" id="<?php if(@$dc != NULL){ print @$dc->id; }?>" name="<?php if(@$dc != NULL){ print @$dc->id_tmp_comp; }?>" class="btn btn-success btn-xs btn-grad addnota">
                              <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Nro
                            </a> 
                          </div>
                        </td>
                        <?php } ?>
                        <!-- PRECIO DEL PRODUCTO -->
                        <td class="text-center">
                          <input type="text" class="form-control  input-sm text-right precio" style="width: 80px;" name="" id="<?php print @$dc->id ?>" value="<?php if(@$dc != NULL){ print number_format(@$dc->precio_compra,$decimalesprecio); }?>" >
                        </td>                              
                        <!-- EXISTENCIA DEL PRODUCTO -->
                        <td class="text-center"><?php print @$dc->existencia; ?></td>
                        <!-- CANTIDAD -->
                        <td class="text-center">
                          <input type="text" class="form-control text-center input-sm cantidad" style="width: 80px;" name="" id="<?php print @$dc->id ?>" value="<?php if(@$dc != NULL){ print number_format(@$dc->cantidad, $decimalescantidad); }?>" <?php if(@$dc != NULL) { if ($dc->cantidadserie > 0) { print "disabled";} } ?> >
                        </td>
                        <!-- UNIDAD DE MEDIDA -->
                        <td class="text-center">
                            <select id="<?php print @$dc->id ?>" name="cmb_proveedor" class="form-control unidadmedida">
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
                        <!-- APLICA IVA -->
                        <td class="text-center">
                          <?php 
                            $valchk = @$dc->iva;
                            if($valchk == 1){ $chk = "checked='checked'"; }else{ $chk = "";}
                          ?>
                          <input type="checkbox" id="<?php print @$dc->id ?>" class="chkiva" <?php print $chk; ?> >
                        </td>
                        <!-- SUBTOTAL -->
                        <td class="text-right">
                          <div id="<?php print @$dc->id; ?>" class="valsubtotal">
                            <input type="text" class="form-control text-center input-sm subtotaledit" style="width: 80px;" name="" id="<?php print @$dc->id ?>" value="<?php if(@$dc != NULL){ print @$dc->subtotal; }?>" >
<!--                             <?php print $dc->subtotal; ?>
 -->                          </div>
                        </td>
                        <!-- SUBTOTAL DESC -->
                        <td class="text-right">
                          <div name="<?php print @$dc->id; ?>" class="valdescsubtotal">
                            <?php print $dc->descsubtotal; ?>
                          </div>
                        </td>                        
                    </tr>
                    <?php 
                            endforeach;
                        }
                    } 
                    ?>
                  </tbody>
                </table>
