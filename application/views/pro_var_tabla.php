<?php 
    $vardatos = $this->session->userdata("arr_var");
?>

    <table class="table table-bordered vardet">
        <tbody>
            <tr>
                <th class="text-center">Nro</th>
                <th>Item</th>
                <th class="text-center">Acción</th>
            </tr>
            <?php 
            $nro = 0;
            if(@$vardatos != NULL){
                if (count($vardatos) > 0) {
                    foreach ($vardatos as $vd=>$valor):
                        $nro = $nro + 1;
            ?>
                        <tr>
                            <td class="text-center"><?php print @$nro; ?></td>
                             <td><?php print @$valor; ?></td>
                            <td class="text-center">
                            <a href="#" title="Ver" id="<?php print @$vd; ?>" class="btn btn-success btn-xs btn-grad provar_edi"><i class="fa fa-pencil-square-o"></i></a> 
                            <a href="#" title="Eliminar" id="<?php print @$vd; ?>" class="btn btn-danger btn-xs btn-grad provar_del"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
            <?php
                    endforeach;
                }
            }
            ?>
        </tbody>
    </table>
