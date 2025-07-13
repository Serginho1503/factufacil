  <table class="table table-bordered">
    <thead>
      <tr>
        <th style="width: 10px">#</th>
        <th>Forma de Pago</th>
        <th class="text-center col-md-1">Monto</th>
        <th class="text-center col-md-1">Acción</th>
      </tr>
    </thead>    
    <tbody>                                                        
      <?php 
      $num=0;
      foreach ($lstforpago as $lfp) {
        
        if($lfp->id_tipcancelacion == 2){
          $num++; 
      ?>
        <tr class="" id="">
          <td>
            <?php print $num; ?>
          </td>
          <td>
            <?php print $lfp->nomfp; ?>
          </td>
          <td class="text-center">
            <?php print $lfp->monto; ?>
          </td>
          <td class="text-center">
            <a style="color: #094074;" href="#" title="Editar" id="<?php if(@$lfp != NULL){ print @$lfp->idreg; }?>" name="<?php if(@$lfp != NULL){ print @$lfp->id_formapago; }?>" class="fp_edi"><i class="fa fa-pencil-square-o fa-lg"></i></a> &nbsp;&nbsp;
            <a style="color: #B80C09;" href="#" title="Eliminar" id="<?php if(@$lfp != NULL){ print @$lfp->idreg; }?>" name="<?php if(@$lfp != NULL){ print @$lfp->id_formapago; }?>" class="fp_del"><i class="fa fa-minus-circle fa-lg"></i></a>
          </td>
        </tr>
      <?php 
        }
      }
      ?>
    </tbody>
  </table>