  <?php 
    $desc = @$climesa->descuento; 
    $sumsub = @$climesa->sumsub; 
    $apiva = 0;
    $nro = 0;
    $descmonto = 0;
    $descsubtotal = 0;
    $iva = 0.12;
    $valiva = 0;
    $montoiva = 0;
    $prosub = 0;

    $stciva = 0;
    $stsiva = 0;
    $dstciva = 0;
    $dstsiva = 0;



    $subtotal = 0;
    
    $total = 0;
    if(@$pedmesa != NULL){
      if (count($pedmesa) > 0) {
        foreach ($pedmesa as $pm) {
          $nro = $nro + 1;
          $apiva = $pm->ap_iva;
          $prosub = $pm->total;                        
       // Se verifica si aplica aumento
          if($desc > 0){

            $descmonto =  $prosub / $sumsub * $desc;
            $descsubtotal = $prosub - $descmonto;
          }else{
            $descsubtotal = $pm->total;
          }

          $subtotal = $subtotal + $descsubtotal;
       // Se verifica si aplica iva
          if($apiva == 1){
            $valiva = $descsubtotal * $iva;
            $stciva = $stciva + $prosub;
            $dstciva = $dstciva + $descsubtotal;
          }else{
            $valiva = 0;
            $stsiva = $stsiva + $prosub;
            $dstsiva = $dstsiva + $descsubtotal;
          }

          $montoiva = $montoiva + $valiva;
        }
      }
    }
  ?>
<table class="table table-clear calmonto">
  <tbody>
    <tr>
      <td class="text-left"><strong>Subtotal IVA 12 %</strong></td>
      <td id="msubtotalconiva" class="text-right">$<?php print number_format(@$stciva,2,",","."); ?></td>                                        
    <tr>
    <tr>
      <td class="text-left"><strong>Subtotal IVA 0 %</strong></td>
      <td id="msubtotalsiniva" class="text-right">$<?php print number_format(@$stsiva,2,",","."); ?></td>                                        
    <tr>
    <tr>
      <td class="text-left"><strong>Descuento</strong></td>
      <td id="" class="text-right">
        <input type="text" class="text-right" name="" id="descuento" value="<?php print number_format(@$desc,2,",","."); ?>" style="width:70px;" >
      </td>                                        
    </tr>
    <tr>
      <td class="text-left"><strong>Subtotal con Descuento IVA 12 %</strong></td>
      <td id="descsubiva" class="text-right">$<?php print number_format(@$dstciva,2,",","."); ?></td>                                        
    </tr>
    <tr>
      <td class="text-left"><strong>Subtotal con Descuento IVA 0 %</strong></td>
      <td id="descsub" class="text-right">$<?php print number_format(@$dstsiva,2,",","."); ?></td>                                        
    </tr>
    <tr>
      <td class="text-left"><strong>IVA (12%)</strong></td>
      <td id="miva" class="text-right">$<?php print number_format(@$montoiva,2,",","."); ?></td>                                        
    </tr>
    <tr>
      <td class="text-left"><strong>Total</strong></td>
      <td id="mtotal" class="text-right"><strong>$ <?php $total = $subtotal + $montoiva; print number_format(@$total,2,",","."); ?></strong></td>                                        
    </tr>      

  </tbody>
</table>