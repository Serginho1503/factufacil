<style>
#contenido_producto{
    width: 900px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {

      $('#TableProducto').DataTable();

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


          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#listado" data-toggle="tab">Listado</a></li>
              <?php 
              foreach ($lstcat as $cat) {
                if ($cat->menu == 1) {
              ?>
                  <li><a href="<?php print '#categoria' . $cat->cat_id; ?>" data-toggle="tab"><?php print $cat->cat_descripcion; ?></a></li>
              <?php 
                }
              }
              ?>

             
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="listado">

                  <div class="box-body table-responsive">
                      <table id="TableProducto" class="table table-bordered table-hover table-responsive">
                          <thead>
                              <tr>
                                  <th>Cod Barra</th>
                                  <th>Nombre</th>
                                  <th>Precio</th>
                                  <th>Existencia</th>
                                  <th>Almacen</th>
                              </tr>
                          </thead>    
                          <tbody>                                                        
                              <?php 
                              foreach ($pro as $p) {
                              ?>
                                <tr class="addpro" id="<?php print $p->pro_id; ?>" name="<?php print $p->preparado; ?>">
                                  <td>
                                    <input type="hidden" class="existencia" id="<?php print @$p->pro_id; ?>" value="<?php print @$p->existencia; ?>" >    
                                    <input type="hidden" class="servicio" id="<?php print @$p->pro_id; ?>" value="<?php print @$p->esservicio; ?>" >    
                                    <input type="hidden" class="almacen" id="<?php print @$p->pro_id; ?>" value="<?php print @$p->id_alm; ?>" >    
                                    <input type="hidden" class="variante" id="<?php print @$p->pro_id; ?>" value="<?php print @$p->habilitavariante; ?>" >    
                                    <?php print $p->pro_codigobarra; ?>
                                  </td>
                                  <td>
                                    <?php print $p->pro_nombre; ?>
                                  </td>
                                  <td>
                                    <?php print $p->pro_precioventa; ?>
                                  </td>
                                  <td>
                                    <?php print $p->existencia; ?>
                                  </td>
                                  <td>
                                    <?php print $p->almacen_nombre; ?>
                                  </td>
                                </tr>
                              <?php 
                              }
                              ?>
                          </tbody>
                      </table>
                  </div>

              </div>
              <!-- /.tab-pane 
              <div class="tab-pane" id="tab_2">
                The European languages are members of the same family. Their separate existence is a myth.
                For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                in their grammar, their pronunciation and their most common words. Everyone realizes why a
                new common language would be desirable: one could refuse to pay expensive translators. To
                achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                words. If several languages coalesce, the grammar of the resulting language is more simple
                and regular than that of the individual languages.
              </div>-->
              <!-- /.tab-pane -->

              <?php 
                  foreach ($lstcat as $cat) {
                    if ($cat->menu == 1) {
                  ?>
                      <div class="tab-pane" id="<?php print 'categoria' . $cat->cat_id; ?>">
                        <div class="box-body">
                          <?php
                            foreach ($lstpro as $lpro) {
                              if($lpro->idcat == $cat->cat_id){
                          ?>     
                                <a id="<?php print $lpro->id; ?>" name="<?php print $lpro->preparado; ?>" class="btn btn-app addpro"><i class="fa fa-beer" aria-hidden="true"></i> <?php print $lpro->producto ?>
                                <input type="hidden" class="almacen" id="<?php print @$lpro->id; ?>" value="<?php print @$lpro->id_alm; ?>" >
                                <input type="hidden" class="existencia" id="<?php print @$lpro->id; ?>" value="<?php print @$lpro->existencia; ?>">

                                <input type="hidden" class="servicio" id="<?php print @$lpro->id; ?>" value="<?php print @$lpro->esservicio; ?>" >    
                                <input type="hidden" class="variante" id="<?php print @$lpro->id; ?>" value="<?php print @$lpro->habilitavariante; ?>" >    

                                </a>
                          <?php  
                              }
                            }
                          ?>

                        </div>
                        <!-- /.tab-pane

                        -->
                      </div>
                      <!-- /.tab-content -->

              <?php 
                }
              }
              ?>
          </div>


        </div>









      </div>
    </div>
  </div>
</div>