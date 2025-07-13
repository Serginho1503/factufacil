<table class="table table-bordered table-striped ">
              <!-- <table id="dataTableDet" class="table table-bordered table-striped "> -->
                <thead>
                  <tr >
                      <th>Acción</th> 
                      <th>Código Cuenta</th>
                      <th>Descripción Cuenta</th>
                      <th>Concepto</th>
                      <th>Débito</th>
                      <th>Crédito</th>
                  </tr>
                </thead>
                <tbody>

                        <?php 
                        $num=0;
                        foreach ($lstdetalle as $det) {
                          $num++;
                        ?>
                          <tr class="detallepro" id="<?php print $det->id; ?>" >
                            <td class="text-center">
                                <a href="#" title="Eliminar" id="<?php  if(@$det != NULL){ print @$det->id; }?>" class="btn btn-xs btn-danger btn-grad pro_del"><i class="fa fa-trash-o"></i></a>
                            </td>
                            <td class="text-center">
                              <div class="divcuenta"> 
                                <input type="text" class="col-md-12 tdcuenta upd_cuenta" name="<?php  if(@$det != NULL){ print @$det->id; }?>" id="cuenta<?php  if(@$det != NULL){ print @$det->id; }?>" value="<?php  if(@$det != NULL){ print @$det->codigocuenta; }?>" data-source="<?php echo base_url('contabilidad/contab_comprobante/valcuentacodigo?codigo=');?>" >
                              </div>
                            </td>
                            <td class="text-center">
                              <div>
                                <label class="col-md-12 desc_cuenta" name="<?php  if(@$det != NULL){ print @$det->id; }?>" id="<?php  if(@$det != NULL){ print @$det->id; }?>" > <?php  if(@$det != NULL){ print addslashes($det->descripcion); }?></label> 
                              </div>
                            </td>
                            <td class="text-center">
                              <div> 
                                <input type="text" class="col-md-12 tdcuenta upd_concepto " name="<?php  if(@$det != NULL){ print @$det->id; }?>" id="<?php  if(@$det != NULL){ print @$det->id; }?>" value="<?php  if(@$det != NULL){ print @$det->concepto; }?>"  >
                              </div>
                            </td>
                            <td class="text-center">
                              <div> 
                                <input type="text" class="col-md-12 tdcuenta upd_debito " name="<?php  if(@$det != NULL){ print @$det->id; }?>" id="<?php  if(@$det != NULL){ print @$det->id; }?>" value="<?php  if($det->debitocredito == 1){ print $det->valor; } else { print '0.00'; }?>"  >
                              </div>
                            </td>
                            <td class="text-center">
                              <div> 
                                <input type="text" class="col-md-12 tdcuenta upd_credito " name="<?php  if(@$det != NULL){ print @$det->id; }?>" id="<?php  if(@$det != NULL){ print @$det->id; }?>" value="<?php  if($det->debitocredito == 0){ print $det->valor; } else { print '0.00'; }?>"  >
                              </div>
                            </td>

                          </tr>
                        <?php 
                        
                        }
                        ?>

                </tbody>
              </table>