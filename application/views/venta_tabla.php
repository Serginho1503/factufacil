<table id="dataTableVent" class="table table-bordered table-hover table-responsive">
    <thead>
      <tr>
        <th class="text-center col-md-1">Fecha</th>  
        <th class="text-center col-md-1">Factura</th>
        <th class="text-center col-md-1">Mesa</th>
        <th>Cliente</th>
        <th class="text-center col-md-1">Monto</th>
        <th class="text-center col-md-1">Acción</th>
      </tr>                            
    </thead>    
    <tbody>                                                        
        <?php 
        foreach (@$lst_venta as $v) {
        ?>
          <tr class="" id="<?php // print $p->pro_id; ?>">
            <td>
              <?php 
              @$fec = str_replace('-', '/', $v->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); 
              print $fec; ?>
            </td>
            <td>
              <?php print $v->nro_factura; ?>
            </td>
            <td>
              <?php print $v->mesa; ?>
            </td>
            <td>
              <?php print $v->nom_cliente; ?>
            </td>
            <td class="text-right col-md-1">
              <?php print $v->montototal; ?>
            </td>
            <td>
              <div class="text-center">
                <a href="#" title="Editar" id="<?php print $v->id_venta; ?>" class="btn btn-success btn-xs btn-grad edi_fact"><i class="fa fa-pencil-square-o"></i></a> 
                <a href="#" title="Anular" id="<?php print $v->id_venta; ?>" class="btn btn-danger btn-xs btn-grad anu_fact"><i class="fa fa-ban" aria-hidden="true"></i></a>
                <a href="#" title="Imprimir Venta" id="<?php print $v->id_venta; ?>" class="btn bg-navy color-palette btn-xs btn-grad venta_print"><i class="fa fa-print"></i></a> 
              </div>
            </td>                                                                        
          </tr>
        <?php 
        }
        ?>
    </tbody>
</table> 
