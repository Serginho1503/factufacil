<table class="table table-striped table-responsive detfactura">
  <thead>
    <tr>
      <th class="text-center col-md-1">Nro</th>
      <th>Nombre</th>
      <th class="text-center col-md-1">Cantidad</th>
      <th class="text-center col-md-1">Precio</th>
      <th class="text-center col-md-1">SubTotal</th>                     
      <th class="text-center col-md-1">SubTotal Desc</th>
    </tr>
  </thead>   
  <tbody>
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

  ?>
          <tr>
            <td class="text-center"><?php print $nro; ?></td>
            <td class="text-left"><?php print @$pm->pro_nombre; ?></td>
            <td class="text-center"><?php print @$pm->cantidad; ?></td>
            <td class="text-right">$<?php print number_format(@$pm->precio,2,",","."); ?></td>
            <td class="text-right">$<?php print number_format(@$prosub,2,",","."); ?></td>
            <td class="text-right">$<?php print number_format(@$descsubtotal,2,",","."); ?></td>
          </tr>
  <?php  
        }
      }
    }    
  ?>               
  </tbody>
</table>