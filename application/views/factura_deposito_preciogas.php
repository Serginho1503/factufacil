
<style>
    #contenido_alm{
        width: 500px;
    } 
 .ajuste{
      height: 60px;
      padding: 0px 5px;
      font-size: 35px;      
    }      
</style>

<script type="text/javascript">

$( document ).ready(function() {

    $("#frm_gas").validationEngine();

    $(document).on('keyup','#txt_cant', function(){
      var precio = <?php print number_format($progas->precioiva,6); ?>;
      var cantidad = $(this).val();
      var monto = precio * cantidad;
      monto = monto.toFixed(2);
      $('#txt_monto').val(monto);
    });

    $(document).on('keyup','#txt_monto', function(){
      var precio = <?php print number_format($progas->precioiva,6); ?>;
      var monto = $(this).val();
      monto = parseFloat(monto).toFixed(2);
      var cantidad = 0;
      if(precio != 0 || precio != ''){
        cantidad = monto / precio;
      }
      cantidad = cantidad.toFixed(4);
      $('#txt_cant').val(cantidad);
    });

    /* CARGA LOS PRODUCTOS A LA COMPRA */
    $(document).on('click', '.addprodeposito00', function(){
        txt_cant = $('#txt_cant').val();
        txt_alm = $('#txt_alm').val();
        id = $(this).attr('id');  
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "facturar/addgas",
          data: {txt_cant: txt_cant, txt_alm: txt_alm},
          success: function(json) {
              $.fancybox.close();
              actualizar_subtotales();
              $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
              /*$('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");*/
          }
        });
        /*$.fancybox.close();*/
        
    });

    function actualizar_subtotales0(){
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Facturar/lst_subtotalesventatmp",
          success: function(json) {
            $('.msubtotalconiva').html("$" + json.subtotaliva);
            $('.msubtotalsiniva').html("$" + json.subtotalcero);
            $('.descsubiva').html("$" + json.descsubtotaliva);
            $('.descsubcero').html("$" + json.descsubtotalcero);
            montoiva = parseFloat(json.montoiva);
            descuento = parseFloat(json.descuento);
            montototal = parseFloat(json.descsubtotaliva) + parseFloat(json.descsubtotalcero) + montoiva 
            montoiva = montoiva.toFixed(2);
            montototal = montototal.toFixed(2);
            if (descuento > montototal){
              descuento = montototal;
            } else {
              descuento = descuento.toFixed(2);
            }
            $('.miva').html("$" + montoiva);
            $('.mtotal').html("$" + montototal);
            $('.mtotal').val(montototal);
            $('#descuento').val(descuento);
          }
      });
    }

});

</script>

<div id = "contenido_alm" class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> <?php print $progas->pro_nombre; ?></h3>
          <div class="pull-right">
            <strong><?php print "PxG: ".number_format($progas->precioiva,6/*$cantdecimales*/); ?> </strong>
          </div>
        </div>
        <form id="frm_gas" name="frm_gas" method="post" role="form" class="form"  action="#" onSubmit='return false'>  
<!--         <form id="frm_gas" name="frm_gas" method="post" role="form" class="form"  action="<?php echo base_url('facturar/addgas');?>" >  
 -->        <div class="box-body">
            <div class="row">
                <div class="col-md-12">

                    <div class="col-md-4" style="padding-left: 0px;">
                        <img class="progas img-responsive" <?php
                          if (@$progas != NULL) {
                            if ($progas->pro_imagen) { print " src='data:image/jpeg;base64,$progas->pro_imagen'"; } 
                            else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } } 
                          else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } ?> 
                          alt="" onerror="this.src='<?php print base_url() . "public/img/perfil.jpg"; ?>';" />
                    </div>

                    <div class="form-group  col-md-4">
                        <input type="hidden" name="txt_alm" id="txt_alm" value="<?php print $progas->almacen_id; ?>">
                        <label for=""><?php print $progas->descripcion; ?></label>
                        <input type="text" class="form-control ajuste validate[required] text-right" name="txt_cant" id="txt_cant" placeholder="0.00" value="" >
                    </div> 

                    <div class="form-group  col-md-4">
                        <label for="">DOLARES</label>
                        <input type="text" class="form-control ajuste validate[required] text-right" name="txt_monto" id="txt_monto" placeholder="0.00" value="" >
                    </div>

                </div>



          </div>
        </div>

<!--         <div class="pull-center"> 
          <a class="btn bg-orange color-palette btn-grad addprodeposito" data-original-title="" title=""><i class="fa fa-shopping-bag"></i> Guardar </a>         
        </div> -->

        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="button" class="btn btn-danger btn-grad no-margin-bottom guardaprodeposito">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
    
        </form>
    </div>
</div>