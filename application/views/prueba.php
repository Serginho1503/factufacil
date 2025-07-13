<?php
/* ------------------------------------------------
  ARCHIVO: Producto.php
  DESCRIPCION: Contiene la vista principal del módulo de Producto.
  FECHA DE CREACIÓN: 15/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
print "<script>document.title = 'FACTUFÁCIL - Producto'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<script type="text/javascript">
$( document ).ready(function() {
    $("#frm_add").validationEngine();
    
    $('#fecha').datepicker();
    $('#fecha').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    $('.monto').inputmask('decimal',
      { 'alias': 'numeric',
        'groupSeparator': '.',
        'autoGroup': true,
        'digits': 2,
        'radixPoint': ",",
        'digitsOptional': false,
        'allowMinus': false,
        'prefix': '$' ,
        'placeholder': '0'
      }
    );

});

</script>
<script type="text/javascript">
   
</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-shopping-bag"></i> Productos
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>producto">Producto</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- SECCION DEL FORMULARIO-->
            <form id="frm_prueba" name="frm_prueba" method="post" role="form" class="form" enctype="multipart/form-data" action="<?php echo base_url('producto/prueba');?>">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Datos del Producto</h3>
                    </div>
                    <div class="box-body">
                      <div class="row">

                        <div class="col-xs-12">
                            <div class="col-xs-12">
                            <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                                if(@$pro != NULL){ ?>
                                    <input type="hidden" id="txt_idpro" name="txt_idpro" value="<?php if($pro != NULL){ print $pro->pro_id; }?>" >    
                                <?php } else { ?>
                                    <input type="hidden" id="txt_idpro" name="txt_idpro" value="0">    
                            <?php } ?> 
                            </div> 

                            <div class="col-xs-4">
                                <div class="box box-danger">
                                    <div class="box-header with-border  text-center">
                                        <h3 class="box-title"><i class="fa fa-usd"></i> Precios</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">                            
                                            <!-- Precio de Compra -->
                                            <div class="form-group col-md-6">
                                                <label for="lb_canmax">Compra</label>
                                                <input type="text" class="form-control validate[required] " name="txt_precomp" id="txt_precomp" placeholder="Precio de Compra" value="<?php if(@$pro != NULL){ print @$pro->pro_preciocompra; }?>">
                                            </div>
                                            <!-- Precio de Venta -->
                                            <div class="form-group col-md-6">
                                                <label for="lb_canmax">Venta</label>
                                                <input type="text" class="form-control validate[required] " name="txt_prevent" id="txt_prevent" placeholder="Precio de Venta" value="<?php if(@$pro != NULL){ print @$pro->pro_precioventa; }?>">
                                            </div> 
                                            <div class="col-md-12">
                                               <hr class=""> 
                                            </div>
                                            
                                            <h4 class="text-center">Otros Precios de Venta</h4>
                                           
                                            <?php 
                                                    if(@$pre != NULL){ 
                                                        foreach ($pre as $pr): 
                                                            ?>
                                                            <div class="input-group col-md-12" style="min-height: 1px; padding-left: 15px; padding-right: 15px; position: relative;">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-usd"></i> <?php  if(@$pr != NULL){ print @$pr->desc_precios; } ?>
                                                                </div>
                                                                <input id="<?php if(@$pr != NULL){ print @$pr->id_precios; } ?>" name="pre<?php if(@$pr != NULL){ print @$pr->id_precios; } ?>" class="form-control monto" type="text">

                                                            </div>
                                                            <br>                                                
                                                            <?php
                                                        endforeach;
                                                    }                                        
                                            ?> 
                                        <!--    
                                            <input id="1" name="otro[]" type="text" value="Valor 1">
                                            <input id="2" name="otro[]" type="text" value="Valor 2">
                                            <input id="3" name="otro[]" type="text" value="Valor 3"> 
                                            -->
                                        </div>  
                                    </div>
                                </div>    
                            </div>




























                        </div>

                      </div>
                    </div>
                    <!-- /.box-body -->
                    <div  align="center" class="box-footer">
                        <div class="form-actions ">
                            <button type="submit" class="btn btn-danger btn-grad no-margin-bottom">
                                <i class="fa fa-save "></i> Guardar
                            </button>
                        </div>
                    </div>
                </div>
              <!-- /.box -->
            </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

<script>
// JQuery.Inputmask example.
/*
var customInputmask = (function() {
  var config = {
    extendDefaults: {
      showMaskOnHover: false,
        showMaskOnFocus: false
        },
    extendDefinitions: {},
    extendAliases: {

      'currency': {
        alias: 'numeric',
        digits: '*',
        digitsOptional: true,
        radixPoint: ',',
        groupSeparator: '.',
        autoGroup: true,
        placeholder: ''

    }
  };

    var init = function() {
        Inputmask.extendDefaults(config.extendDefaults);
    Inputmask.extendDefinitions(config.extendDefinitions);
    Inputmask.extendAliases(config.extendAliases);
    $('[data-inputmask]').inputmask();
    };
  
  return {
    init: init
  };
}());

// Initialize app.
(function() {
    customInputmask.init();
}());
*/
</script