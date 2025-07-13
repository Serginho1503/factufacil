<?php 
$totalm = 0; 
foreach ($lstcat as $lc):
if($lc->id_parametro == 1){ $totalm = $totalm + $lc->total; ?>
    <tr>
      <td class="text-left"><?php print @$lc->nom_cat_gas; ?></td>
      <td class="text-center col-md-1"><input class="text-center" type="text" name="" id="" style="width:100px;" value="<?php print number_format(@$lc->total,2,",","."); ?>" disabled></td>
      <td class="text-center" id="prc_crep"><span class="badge bg-red">0.00%</span></td>
    </tr>     
<?php 
}
?>                                       
<?php endforeach ?>
<tr>
<th class="text-left">TOTAL MANTENANCE</th>
<th class="text-center col-md-1"><input class="text-center" type="text" name="txt_totalm" id="txt_totalm" style="width:100px;" value="<?php print number_format($totalm,2,",","."); ?>" disabled></th>
<th class="text-center" id="prc_totalm"><span class="badge bg-green">2.61%</span></th>
</tr> 

<tr>
<td class="trfull" colspan="3">
    <hr class="linea">                                       
</td>
</tr> 
<?php 
$totals = 0;
foreach ($lstcat as $lct):
if($lct->id_parametro == 2){ $totals = $totals + $lct->total; ?>
    <tr>
      <td class="text-left"><?php print @$lct->nom_cat_gas; ?></td>
      <td class="text-center col-md-1"><input class="text-center" type="text" name="" id="" style="width:100px;" value="<?php print number_format(@$lct->total,2,",","."); ?>" disabled></td>
      <td class="text-center" id="prc_crep"><span class="badge bg-red">0.00%</span></td>
    </tr>     
<?php 
}
?>                                       
<?php endforeach ?>
<tr>
<th class="text-left">TOTAL ENERGY Y PHONE</th>
<th class="text-center col-md-1"><input class="text-center" type="text" name="" id="" style="width:100px;" value="<?php print number_format($totals,2,",","."); ?>" disabled></th>
<th class="text-center" id="prc_phone"><span class="badge bg-green">0.83%</span></th>
</tr>     