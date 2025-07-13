<table class="table table-bordered detserie table-responsive">
    <tbody>
        <tr>
            <th class="text-center " style="width: 10px;">Nro</th>
            <th class="text-center col-md-1">Imei/Serie</th> 
            <th>Descripción</th>
            <th class="text-center">Acción</th>
        </tr>
        <?php 
          $nro = 0;
          foreach ($proimei as $pi) {
            if($pi->id_producto == $idpro){
            $nro++;  
        ?>    
            <tr>
                <td class="text-center"><?php print @$nro; ?></td>
                <td class="text-center"><?php print @$pi->numeroserie; ?></td>
                <td class="text-left"><?php print @$pi->descripcion; ?></td>
                <td class="text-center" style="width: 10px;">
                    <a href="#" title="Eliminar" id="<?php if(@$pi != NULL){ print @$pi->id_serie; }?>" class="btn btn-danger btn-xs btn-grad proser_del"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>  
        <?php 
            }             
          }
        ?>

    </tbody>
</table>