<table class="table table-bordered table-hover table-responsive">
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