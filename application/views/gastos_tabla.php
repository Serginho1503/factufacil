<table id="dataTableGas" class="table table-bordered table-hover table-responsive">
    <thead>
          <tr>
              <th class="text-center col-md-1">Fecha</th>
              <th class="text-center col-md-1">Proveedor</th>
              <th class="text-center col-md-1">Nro Factura</th>
              <th >Descripcion</th>
              <th class="text-center col-md-1">Total</th>
              <th class="text-center col-md-1">Acción</th>
          </tr>                            
    </thead>    
    <tbody>                                                        
        <?php 
        foreach ($lst_gastos as $g) {
        ?>
        <?php 
          if($g->estatus != '3'){?>
          <tr class="" id="<?php // print $p->pro_id; ?>">
         <?php } else { ?>                
          <tr class="" style="background-color: #DD4B39" id="<?php // print $p->pro_id; ?>">
         <?php } ?>                
            <td>
              <?php 
              @$fec = str_replace('-', '/', $g->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); 
              print $fec; ?>
            </td>
            <td>
              <?php print $g->nom_proveedor; ?>
            </td>
            <td>
              <?php print $g->nro_factura; ?>
            </td>
            <td>
              <?php print $g->descripcion; ?>
            </td>
            <td>
              <?php print $g->total; ?>
            </td>
            <td>
              <div class="text-center">
              <?php 
                  if($g->estatus != '3'){?>
                <a href="#" title="Anular" id="<?php print $g->id_gastos; ?>" class="btn btn-success btn-xs btn-grad anu_fact"><i class="fa fa-ban"></i></a> 
              <?php } ?>                
<!--                 <a href="#" title="Eliminar" id="<?php print $g->id_gastos; ?>" class="btn btn-danger btn-xs btn-grad del_gas"><i class="fa fa-trash-o"></i></a>
 -->              </div>
            </td>                                                                        
          </tr>
        <?php 
        }
        ?>
    </tbody>
</table>  

