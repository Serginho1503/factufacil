<style>
#contenido_provar{
    width: 500px;
}   
</style>

<script type="text/javascript">

    $(document).ready(function () {

        $(document).on('change','.cantvar', function(){

            desc = $(this).attr("id");
            idpro = $(this).attr("name");
            cantidad = $(this).val();
            if (cantidad == '') {cantidad = 0;}
            maxitemvariante = $('#maxitemvariante').val();

            tmptotal = 0;
            $(".cantvar").each(function(){
                tmpval = $(this).val();
                if (tmpval == '') {tmpval = 0;}
                tmptotal = tmptotal + parseFloat(tmpval);
            });
            if (tmptotal > maxitemvariante){
                alert("No puede sobrepasar el maximo de " + maxitemvariante + " elementos.");
                itemid = "#" + desc;
                cantidad = parseFloat(maxitemvariante) - tmptotal + parseFloat(cantidad); 
                if (cantidad < 0) {cantidad = 0;}
                $(itemid).val(cantidad);
                return false;
            }
            
            if (!isNaN(parseFloat(cantidad))){
                //alert(idpro + " - " + desc + " - " + cantidad);  
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?php echo base_url('pedido/upd_cant_variante');?>",
                    data: { idpro: idpro, cant: cantidad, desc: desc },
                    success: function(json) {

                    }
                }); 
            } 


            return false;

        }); 

    });

</script>

<div id = "contenido_provar" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"> Variantes del Producto <?php print $provar[0]->pro_nombre; ?> : Maximo( <?php if(@$maxitemvariante != NULL){ print $maxitemvariante; } else {print 0;} ?> )</h3>
            <input id="maxitemvariante" type="hidden" class="form-control" value="<?php if(@$maxitemvariante != NULL){ print $maxitemvariante; } else {print 0;} ?>" >
        </div>

        <div class="box-body">
            <div class="row">

                <div id="detvar" class="col-md-12" > 
                    <table class="table table-bordered provardet">
                        <tbody>
                            <tr>
                              <th class="text-center">Nro</th>
                              <th>Variantes</th>
                              <th class="text-center">Cantidad</th>
                            </tr>
                            <?php 
                                $nro = 0;
                                if(@$provar != NULL){
                                    if (count($provar) > 0) {
                                        foreach ($provar as $val ):
                                            $nro = $nro + 1;
                                ?>
                                            <tr>
                                                <td class="text-center col-md-1"><?php print @$nro; ?></td>
                                                 <td><?php print @$val->descripcion; ?></td>
                                                <td class="text-center col-md-1">
                                                    <input type="text" class="form-control text-center cantvar" name="<?php print @$val->id_producto; ?>" id="<?php print @$val->descripcion; ?>" value="<?php if(@$val != NULL){ print $val->cantidad; }?>" >
                                                </td>
                                            </tr>
                                <?php
                                        endforeach;
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div> 

            </div>
        </div>
        <!-- /.box-body 
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="submit" class="btn btn-danger btn-grad no-margin-bottom">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>-->

    </div>
</div>