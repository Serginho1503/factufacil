
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th style="width: 10px">Nro</th>
                <th>Categoría</th>
                <th style="width: 40px">Acción</th>
            </tr>
            <?php
            $nro = 0; 
            foreach ($lst as $lm) {
                if($lm->id_parametro == $id){
                    $nro = $nro + 1;    
                    ?>
                    <tr>
                        <td><?php print $nro; ?></td>
                        <td><?php print @$lm->nom_cat_gas; ?></td>
                        <td>
                            <div class="text-center">
                                <a href="#" title="Eliminar" id="<?php print @$lm->id_categoria ?>" name="<?php print @$lm->id_parametro ?>" class="btn btn-danger btn-xs btn-grad del_catgc"><i class="fa fa-trash-o"></i></a>
                            </div>                                                                
                        </td>
                    </tr>    
                    <?php
                }
            }
            ?>

        </tbody>
    </table>