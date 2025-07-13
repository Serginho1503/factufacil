                      <table class="table table-bordered detpedido table-responsive">
                        <tbody>
                          <tr>
                              <th class="text-center col-md-1">Nro</th>
                              <th class="text-center col-md-1">Cantidad</th>
                              <th>Nombre</th>
                              <th class="text-center col-md-1">Nota</th>
                              <?php if ($pedidopromo == 1){ ?>
                                <th class="text-center col-md-1">Promo</th>
                              <?php } ?>
                              <th class="text-center col-md-1">Precio</th>
                              <th class="text-center col-md-1">SubTotal</th>
                              <th class="text-center col-md-1">Estado</th>
                              <th class="text-center col-md-1">Acción</th>
                          </tr>
                          <?php 
                          $nro = 0;
                          $total = 0;
                          $subtotal = 0;
                          if(@$detmesa != NULL){
                              if (count($detmesa) > 0) {
                                  foreach ($detmesa as $dm):
                                      $nro = $nro + 1;
                                      $subtotal = $dm->cantidad * $dm->precio;
                                      $total = $total + $subtotal; 

                          ?>
                          <tr>
                              <!-- NRO -->
                              <td class="text-center"><?php print $nro; ?></td>
                              <!-- CANTIDAD -->
                              <td class="text-center">
                                <input type="text" class="form-control text-center cantidad" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" value="<?php print $dm->cantidad; ?>" >
                              </td>
                              <!-- NOMBRE DEL PRODUCTO -->
                              <td>
                                <div class="col-md-12">
                                  <div class="col-md-8">
                                <?php print $dm->pro_nombre; ?>                                    
                                  </div>
                                <?php
                                  if($dm->variante == 1){ ?>
                                    <a href="#" title="Variantes del Producto" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="btn btn-warning btn-grad pedpro_var pull-right">Detalles</a>    
                                <?php }
                                ?>                                  
                                </div>

                              </td>
                              <!-- NOTA DEL PEDIDO -->
                              <td>
                                <div class="text-center">
                                  <a href="#" title="Añadir Nota" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="btn btn-success btn-xs btn-grad addnota">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Nota
                                  </a> 
                                </div>
                              </td>

                              <!-- PROMO -->
                              <?php if ($pedidopromo == 1){ ?>
                                <td>
                                  <input type="hidden" class="form-control text-center preciobase" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" value="<?php print @$dm->pro_precioventa; ?>" >
                                  <div class="text-center">
                                    <input type="checkbox" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="promo" <?php if(@$dm != NULL){ if(@$dm->promo == 1){ print "checked='' ";} }?> >
                                  </div>
                                </td>
                              <?php } ?>

                              
                              <!-- PRECIO DEL PRODUCTO -->
                              <td id="<?php print @$dm->id_producto; ?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="text-right producto"><?php print $dm->precio; ?></td>
                              <!-- SUBTOTAL -->
                              <td name="<?php print @$dm->pro_nombre; ?>" class="text-right ">
                                <div id="<?php print @$dm->id_producto; ?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>">
                                  <?php 
                                    $subtotal = $dm->cantidad * $dm->precio;
                                    print $subtotal; 
                                  ?>
                                </div>
                                
                              </td>
                              <!-- ESTATUS checked-->
                              <?php 
                                if(@$dm->estatus == '1'){ $est = 'checked'; }else{ $est = '';}
                                $bootclass =  "class='fa " . @$iconopedido ."'"; 
                              ?>
                              <td class="text-center">
                                <input <?php print $est; ?> id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="est_ped" type="checkbox"  data-toggle="toggle" data-on="<i class='fa fa-check'></i> Entregado" data-off="<i <?php print $bootclass; ?> aria-hidden='true'></i> Pedido" data-onstyle="success" data-offstyle="danger" data-size="small"> 
                              
                              </td>
                              <!-- ACCION -->
                              <td class="text-center">
                                <?php if(@$dm->est_comanda == '0'){ ?>
                                <a href="#" title="Eliminar" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="btn btn-danger btn-grad pedpro_del"><i class="fa fa-trash-o"></i></a>
                                <?php } ?>
                              </td>
                          </tr>
                          <?php
                                  endforeach;
                              }
                          }
                          ?>
                        </tbody>
                      </table>
<?php 

 ?>
<div class="pull-right">
    <a class="btn btn-danger btn-sm del_proped" href="#" data-original-title="" title=""><i class="fa fa-trash"></i> Borra Productos </a> 
</div>
<script type="text/javascript">
  $(document).ready(function () {
    $('.est_ped').bootstrapToggle();
  });
</script>