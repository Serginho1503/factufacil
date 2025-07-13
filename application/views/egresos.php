<?php
/* ------------------------------------------------
  ARCHIVO: cajaapertura.php
  DESCRIPCION: Contiene la vista de cierre de caja.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Cierre de Caja'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<style type="text/css">

    .form-control{
        font-size: 12px;
        height: 28px;
    }

    .form-group {
        margin-bottom: 5px;
    }

    .linea{
        border-width: 2px 0 0;
        margin-bottom: 5px;
        margin-top: 5px;
        border-color: currentcolor currentcolor;
    } 

    .padcero{
        padding: 0px;
    }

</style>
                            <div class="col-md-12">
                               
                                <div class="box box-danger">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"></i> Egresos de Caja</h3>
                                        <div class="pull-right"> 
                                            <button id="<?php print $caja->id_mov; ?>" type="button" class="btn bg-green-active color-palette btn-grad addegreso" >
                                                <i class="fa fa-plus-square"></i> Añadir
                                            </button>   
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            
                                            <div class="col-md-12 cajaegreso table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <tr>
                                                      <th style="width: 10px">#</th>
                                                      <th>Descripción Salida</th>
                                                      <th style="width: 100px">Cantidad</th>
                                                      <th style="width: 200px">Emisor</th>
                                                      <th style="width: 200px">Receptor</th>
                                                      <th style="width: 40px">Acción</th>
                                                    </tr>
                                                    <?php 
                                                      $nro = 0;
                                                      if (count($cajag) > 0) {
                                                        foreach ($cajag as $cg):
                                                          $nro = $nro + 1;
                                                     ?>   <tr>
                                                            <td><?php print $nro; ?></td>
                                                            <td><?php print substr($cg->descripcion, 0, 80); ?></td>
                                                            <td class="text-right"><?php print $cg->monto; ?></td>
                                                            <td><?php print $cg->emisor; ?></td>
                                                            <td><?php print $cg->receptor; ?></td>
                                                            <td>
                                                              <div class="text-center">
                                                                <a href="#" title="Editar" id="<?php print $cg->idreg ?>" class="btn btn-success btn-xs btn-grad edi_cg"><i class="fa fa-pencil-square-o"></i></a> 
                                                                <a href="#" title="Eliminar" id="<?php print $cg->idreg  ?>" class="btn btn-danger btn-xs btn-grad del_cg"><i class="fa fa-trash-o"></i></a>
                                                                <a href="#" title="Imprimir" id="<?php print $cg->idreg ?>" class="btn bg-navy color-palette btn-xs btn-grad imp_cg"><i class="fa fa-print"></i></a>
                                                              </div>
                                                            </td>
                                                          </tr>
                                                    <?php
                                                        endforeach;
                                                      }
                                                    ?>
                                                </table>                                            
                                            </div>
                                            <div class="col-md-2"></div>
                                        </div>
                                    </div>                                        
                                </div>                                            
                            
                            </div>

                                                                                                             

                            </div>   


                        <div class="form-group col-md-12">
                            <label for="txt_nombre">Observaciones</label>
                            <textarea id="txt_nota" name="txt_nota" class="form-control" rows="3" placeholder="Ingrese los Observaciones ..."></textarea>
                        </div>

                        <div  align="center" class="box-footer">
                            <div class="form-actions ">
                                <button type="submit" class="btn btn-success btn-grad btn-lg no-margin-bottom guardarcaja">
                                    <i class="fa fa-save "></i> Guardar
                                </button>

                            </div>
                        </div>
                <!--   </form>  -->
                </div>
            </div>

        </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->
                <script src="public/js/lib/sweetalert.min.js"></script>
                <link rel="stylesheet" type="text/css" href="public/js/lib/sweetalert.css">

