<?php
/* ------------------------------------------------
  ARCHIVO: Producto.php
  DESCRIPCION: Contiene la vista principal del módulo de Producto.
  FECHA DE CREACIÓN: 15/07/2017
 * 
  ------------------------------------------------ */
/* Setear el título HTML de la página */
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Producto'</script>";
date_default_timezone_set("America/Guayaquil");
/* SI SE RECARGA LA PAGINA SE USA LA VARIABLE DE SESION PARA RECARGAR LA TABLA DE VARIANTE */
$vardatos = $this->session->userdata("arr_var");
/* ESTAS VARIABLES DETERMINAN LOS CHECK DE COMPRA Y VENTA */
$valcompra = 0; $valventa = 0;
if(@$pro != NULL){ 
    /* PARA COMPRA */
    if(@$pro->pro_aplicompra == 1){ $valcompra = 1; }
    else { $valcompra = 0; } 
    /* PARA VENTA*/
    if(@$pro->pro_apliventa == 1){ $valventa = 1; }
    else { $valventa = 0; } 
}

if(@$pro != NULL){ 
    if(@$pro->preparado == 1){ 
        $prepa = 1;
    } else{
        $prepa = 0;
    }
}else{
    $prepa = 0;
}

$unidadmedidainicial = 0;
if(@$pro != NULL){ 
    if(@$pro->pro_idunidadmedida != NULL){ 
        $unidadmedidainicial = $pro->pro_idunidadmedida;
    } 
}

$precioinicial = 0;
if(@$pro != NULL){ 
    if(@$pro->pro_preciocompra != NULL){ 
        $precioinicial = $pro->pro_preciocompra;
    } 
}

$parametro = &get_instance();
$parametro->load->model("Parametros_model");
$vervariante = $parametro->Parametros_model->sel_habilitavariante();
if ($vervariante == '')  {$vervariante = 0;}


?>

<style type="text/css">
    .tipo_precio{
        display: none;
    }

    .tamanoletra_precio{
        font-size: 20px;
    }
</style>


<script type="text/javascript">

$( document ).ready(function() {

    var prepa = <?php print @$prepa; ?>;
    if(prepa == 1){
        $('#composicion').attr('disabled', false);
    }else{
        $('#composicion').attr('disabled', true);
    }

    /* MOSTRA TIPOS DE PRECIOS */
    var tp = <?php print $tp; ?>;
    if(tp == 1){
        $('.tipo_precio').show();
    }

    /* VALIDA EL FORMULARIO */
    $("#frm_add").validationEngine();
    var valcompra = <?php echo $valcompra; ?>;
    var valventa = <?php echo $valventa; ?>;
    if(valcompra == 0){ 
        $("#txt_precomp").attr("disabled" , "disabled"); 
        $("#cmb_ret").attr("disabled" , "disabled"); 
    }
    if(valventa == 0){ 
        $("#txt_prevent").attr("disabled" , "disabled");
            $(".precios").attr("disabled" , "disabled");
    }

    $('#txt_garantia').blur(function(){
        var valor = $(this).val();       
        if (valor == ''){
            $(this).val(0);
        }
    });
    
    /* VALIDA QUE EL CODIGO DE BARRA NO ESTE REPETIDO */
    $('#txt_codbar').blur(function(){
    var codbar = $(this).val();       
    var dataString = 'codbar='+codbar;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('producto/valcodbar');?>",
            data: dataString,
            success: function(data) {
                if(data > 0){
                    alert("Este Código de Barra ya fue Asignado");
                    $('#codbar').attr('class','form-group col-md-12 has-error');
                }else{
                    $('#codbar').attr('class','form-group col-md-12 has-success');
                }
            }
        });
    });

    /* VALIDA QUE EL CODIGO AUXILIAR NO ESTE REPETIDO
    $('#txt_codaux').blur(function(){
    var codaux = $(this).val();       
    var dataString = 'codaux='+codaux;
        $.ajax({
            type: "POST",
            url: "<?php // echo base_url('producto/valcodaux');?>",
            data: dataString,
            success: function(data) {
                if(data > 0){
                    alert("Este Código Auxiliar ya fue Asignado");
                    $('#codaux').attr('class','form-group col-md-12 has-error');
                }else{
                    $('#codaux').attr('class','form-group col-md-12 has-success');
                }
            }
        });
    }); */

    /* VALIDA QUE EL NOMBRE NO ESTE REPETIDO */
    $('#txt_nompro').blur(function(){
    var nompro = $(this).val();       
    var dataString = 'nompro='+nompro;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('producto/valnompro');?>",
            data: dataString,
            success: function(data) {
                if(data > 0){
                    alert("Este Nombre de Producto ya fue Asignado");
                    $('#nompro').attr('class','form-group col-md-12 has-error');
                }else{
                    $('#nompro').attr('class','form-group col-md-12 has-success');
                }
            }
        });
    });

    /* HABILITAR CON CHECKBOX VENTA EL TEXT VENTA */
    $('#chk_venta').click(function() {
        if($(this).is(":checked")) {
            $("#txt_prevent").removeAttr("disabled"); 
            $(".precios").removeAttr("disabled"); 
        }
        else {
            $("#txt_prevent").attr("disabled" , "disabled");
            $(".precios").attr("disabled" , "disabled");
        }
    });

    /* HABILITAR CON CHECKBOX COMPRA EL TEXT COMPRA */
    $('#chk_compra').click(function() {
        if($(this).is(":checked")) {
            $("#txt_precomp").removeAttr("disabled"); 
            $("#cmb_ret").removeAttr("disabled"); 
        }    
        else {
            $("#txt_precomp").attr("disabled" , "disabled");
            $("#cmb_ret").attr("disabled" , "disabled");
        }    
    });

    /* DESHABILITAR CON CHECKBOX INVENTARIO SI ES SERVICIO */
    $('#chk_ser').click(function() {
        if($(this).is(":checked")) {
            $(".almacen").attr("readonly","true");
            $(".almacen").val(0.00);
        }    
        else {
            $(".almacen").removeAttr("readonly"); 
        }    
    });

/* =============== FUNCIONES PARA VARIANTES ========================== */
    /* LEVANTA LA VENTANA PARA AGREGAR ITEM */
    $(document).on("click", "#add_variante", function() {
       $.fancybox.open({
            type: "ajax",
            href: "<?php echo base_url('producto/provar_add');?>",
            width: 550,
            height: 550,
            ajax: {
               dataType: "html",
               type: "POST"
            }
        });
    });

    /* CAPTURA LOS DATOS DEL FANCYBOX AGREGAR ITEM Y LO ENVIA A LA VARIABLE DE SESION */
    $(document).on("submit", "#formESP", function() {
        var data = $(this).serialize();
            $.ajax({
                url: $(this).attr("action"),
                data: data,
                type: 'POST',
                dataType: 'json',
                success: function(json) {
                    $.fancybox.close();
                    $('#detvar').load('<?php echo base_url("producto/recarga");?>');
                }
            });
        return false;
    });

    /* ELIMINAR ITEM DE LA VARIABLE DE SESION */
    $(document).on('click', '.provar_del', function(){
        id = $(this).attr('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('producto/tmp_item');?>",
            data: {id: id},
            success: function(json) {
                $.fancybox.open({
                    type: "ajax",
                    width: 550,
                    height: 550,
                    ajax: {
                       dataType: "html",
                       type: "POST"
                    },
                    href: "<?php echo base_url('producto/item_del');?>" 
                });
            }
        });
    });

    /* EDITAR ITEM DE LA VARIABLE DE SESION */
    $(document).on('click', '.provar_edi', function(){
        id = $(this).attr('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('producto/tmp_item');?>",
            data: {id: id},
            success: function(json) {
                $.fancybox.open({
                    type: "ajax",
                    width: 550,
                    height: 550,
                    ajax: {
                       dataType: "html",
                       type: "POST"
                    },
                    href: "<?php echo base_url('producto/item_edi');?>" 
                });
            }
        });
    });

/* ==================================================================== */

    /* AGREGAR EL ITEM 
    $(document).on('click', '#add_variantex', function(){
        idpro = $("#txt_idpro").val();
        $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php // echo base_url('producto/tmp_pro');?>",
        data: {id: idpro},
        success: function(json) {
          $.fancybox.open({
            type: "ajax",
            width: 550,
            height: 550,
            ajax: {
               dataType: "html",
               type: "POST"
            },
            href: "<?php // echo base_url('producto/provar_add');?>" 
          });

          
        }

        });
     
    })*/

    /* EDITAR EL ITEM 
    $(document).on('click', '.provar_edi', function(){
        id = $(this).attr('id');
        $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php // echo base_url('producto/tmp_provar');?>",
        data: {id: id},
        success: function(json) {
          $.fancybox.open({
            type: "ajax",
            width: 550,
            height: 550,
            ajax: {
               dataType: "html",
               type: "POST"
            },
            href: "<?php // echo base_url('producto/provar_edit');?>" 
          });
        }

        });
     
    })*/

    /* ELIMINAR EL ITEM 
    $(document).on('click', '.provar_del', function(){
        id = $(this).attr('id');
        $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php // echo base_url('producto/tmp_provar');?>",
        data: {id: id},
        success: function(json) {
          $.fancybox.open({
            type: "ajax",
            width: 550,
            height: 550,
            ajax: {
               dataType: "html",
               type: "POST"
            },
            href: "<?php // echo base_url('producto/provar_eliminar');?>" 
          });
        }

        });
     
    })*/

    $(document).on('click','#chk_pre', function(){
        if( $('#chk_pre').prop('checked') ) {
            $('#composicion').attr('disabled', false);
        }else{
            $('#composicion').attr('disabled', true);
        }
    });

    $(document).on("click", "#composicion", function() {
        var idpro = $('#txt_idpro').val();
        $.fancybox.open({
            type: "ajax",
            href: "<?php echo base_url('producto/comp_add');?>",
            width: 550,
            height: 550,
            ajax: {
                dataType: "html",
                type: "POST",
                data: {idpro: idpro}
            }
        });

    });    

    /* Cambiar unidad de medida */
    $(document).on('change', '#cmb_uni', function(){
        var idpro = $('#txt_idpro').val();
        idunidad = $(this).val();
    /*    alert("idpro-" + idpro + "  idunidad-" + idunidad);*/
        var precioinicial = <?php print @$precioinicial; ?>;
        var precionuevo = $('#txt_precomp').val();
        $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('producto/val_cambiounidadmedida');?>",
        data: {idpro: idpro, idunidad: idunidad},
        success: function(json) {
          if (json.res == 0){
            alert("No es posible cambiar la unidad de medida. Debe establecer el factor de conversion.");    
            var unidadmedidainicial = <?php print @$unidadmedidainicial; ?>;
            $('#cmb_uni').val(unidadmedidainicial);
          } 
          
        }

        });             
     
    });

    $('.editnocoma').keydown(function(e){
       var ingnore_key_codes = [188];/*comma*/
       if ($.inArray(e.keyCode, ingnore_key_codes) >= 0){
          e.preventDefault();
       }
    });

    var cantdecimales = parseFloat(<?php echo $decimalesprecio; ?>);
    $(document).on('change', '#txt_prevent', function(){
        var iva = parseFloat(<?php echo $iva; ?>);
        var prevent = parseFloat($('#txt_prevent').val());
        var preiva = ((iva * prevent) + prevent).toFixed(cantdecimales);
        $('#txt_preventiva').val(preiva);
    });

    $(document).on('change', '#txt_preventiva', function(){
        var iva = parseFloat(<?php echo $iva; ?>);
        var prevent = parseFloat($('#txt_preventiva').val());
        var presiva = (prevent/(1+iva)).toFixed(cantdecimales);
        $('#txt_prevent').val(presiva);
    });

    var iva = parseFloat(<?php echo $iva; ?>);
    var prevent = parseFloat($('#txt_prevent').val());
    var preiva = ((iva * prevent) + prevent).toFixed(cantdecimales);
    $('#txt_preventiva').val(preiva);

    function servicio(){
        if($('#chk_ser').is(":checked")) {
            $(".almacen").attr("readonly","true");
            $(".almacen").val(0.00);
        }    
        else {
            $(".almacen").removeAttr("readonly"); 
        }            
    }

    $(document).on('keyup', '#txt_precomp', function(){
        var decimalesprecio = parseFloat(<?php echo $decimalesprecio; ?>);
        var iva = parseFloat(<?php echo $iva; ?>);

        var precomp = $('#txt_precomp').val();
        if (precomp == '') {
            precomp = 0
            $('#txt_precomp').val(0);
        }
        var porc = $('#porc_compraventapvp').val();

        if (porc != 0){
            prevent = parseFloat(precomp) * (1 + parseFloat(porc) / 100)
            $('#txt_prevent').val(prevent.toFixed(decimalesprecio))
            var preiva = ((iva * prevent) + prevent).toFixed(decimalesprecio);
            $('#txt_preventiva').val(preiva);
        }    

        $('.porc_relacionprecio').each(function(index, el) {
            porc = el.value
            if (porc == '') { porc = 0}
            if (porc != 0){
                id = el.id
                prevent = parseFloat(precomp) * (1 + parseFloat(porc) / 100)
                $('.psiniva[id='+id+']').val(prevent.toFixed(decimalesprecio));
                var preiva = ((iva * prevent) + prevent).toFixed(decimalesprecio);
                $('.pconiva[name='+id+']').val(preiva);
            }    
        });

    });

    $(document).on('keyup', '.psiniva', function(){
        var decimalesprecio = parseFloat(<?php echo $decimalesprecio; ?>);
        var iva = parseFloat(<?php echo $iva; ?>);
        var id = $(this).attr('id');
        var val = parseFloat($(this).val());
        var pciva = ((iva * val) + val).toFixed(decimalesprecio);
        $('.pconiva[name='+id+']').val(pciva);
    });

    $(document).on('keyup', '.pconiva', function(){
        var decimalesprecio = parseFloat(<?php echo $decimalesprecio; ?>);
        var iva = parseFloat(<?php echo $iva; ?>);
        var id = $(this).attr('name');
        var val = parseFloat($(this).val());
        var pciva = (val/(1+iva)).toFixed(decimalesprecio);
        $('.psiniva[id='+id+']').val(pciva);
    });

    servicio();

    var vervariante = <?php print $vervariante; ?>;
    if (vervariante == 0){
        $("#div_variante").remove();
    }
    
});

</script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-shopping-bag"></i> Productos  <?php // print_r($vardatos); ?>
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
            <form id="frm_add" name="frm_add" method="post" role="form" class="form" enctype="multipart/form-data" action="<?php echo base_url('producto/guardar');?>">
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
                                <!-- Código de Barra -->
                                <div id="codbar" class="form-group col-md-12">
                                    <label for="lb_codbar">Código de Barra</label>
                                    <input type="text" maxlength="16" class="form-control " name="txt_codbar" id="txt_codbar" placeholder="Código de Barra" value="<?php if(@$pro != NULL){ print @$pro->pro_codigobarra; }?>">
                                </div>
                            
                                <div id="codbar" class="form-group col-md-12">
                                <div id="print" style="position: relative; width: 4.9cm; height: 2.4cm; border: 1px solid #000; border-radius: 5px; overflow: hidden; box-sizing: border-box;">
                                    <!-- Store Name (Fixed at Top) -->
                                    <div style="position: absolute; top: 7px; left: 50%; transform: translateX(-50%); text-align: center; width: 100%; font-weight: bold; font-size: 10px; line-height: 8px;">
                                        SIN LIMITES BOUTIQUE
                                    </div>
                                    <!-- Product Name (Dynamic, up to 2 lines) -->
                                    <div style="position: absolute; top: 19px; left: 50%; transform: translateX(-50%); text-align: center; width: 90%; font-size: 9px; line-height: 8px; max-height: 16px; overflow: hidden;">
                                        <?php if(@$pro != NULL){ echo html_escape(@$pro->pro_nombre); }?>
                                    </div>
                                    <!-- Barcode and Code/Price Container (Fixed at Bottom) -->
                                    <div style="position: absolute; bottom: 2px; left: 50%; transform: translateX(-50%); width: 4.6cm; height: 1.2cm; text-align: center;">
                                        <!-- Barcode -->
                                        <div style="width: 100%; height: 70%;">
                                            <svg id="barcode" style="width: 100%; height: 100%;"></svg>
                                        </div>
                                        <!-- Code and Price -->
                                        <div id="code-price" style="width: 100%; height: 30%; font-size: 10px; font-weight: bold; display: flex; justify-content: center; align-items: center;">
                                            <span id="code-text"></span>
                                            <span id="price-iva" style="margin-left: 10px;"></span>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                
                                <!-- Botón Imprimir (mantenido) -->
                                <div id="codbar" class="form-group col-md-12">
                                    <button class="btn btn-info" type="button" onclick="imprimir()">Imprimir</button>
                                </div>
                                
                                <style type="text/css">
                                @media print {
                                    #print {
                                        width: 4.9cm !important;
                                        height: 2.4cm !important;
                                        border: 1px solid #000;
                                        box-sizing: border-box;
                                    }
                                    #print * {
                                        box-sizing: border-box;
                                    }
                                    #print > div:nth-child(2) {
                                        display: -webkit-box;
                                        -webkit-line-clamp: 2;
                                        -webkit-box-orient: vertical;
                                        white-space: normal;
                                        overflow: hidden;
                                    }
                                    #code-price {
                                        font-size: 10px !important; /* Tamaño fijo para impresión */
                                    }
                                }
                                </style>
                                
                                <script type="text/javascript">
                                function generarbarcode() {
                                    var txt_codbar = $("#txt_codbar").val();
                                    var precio_iva = $("#txt_preventiva").val();
                                    
                                    if (txt_codbar) {
                                        // Generar código de barras sin mostrar el texto por defecto
                                        JsBarcode("#barcode", txt_codbar, {
                                            width: 1.5, // Ancho de barras
                                            height: 30, // Altura ajustada para dejar espacio al texto
                                            fontSize: 0, // Oculta el texto predeterminado
                                            margin: 0,
                                            textAlign: "center",
                                            textMargin: 0,
                                            displayValue: false // No mostrar texto por defecto
                                        });
                                        
                                        // Mostrar código del producto
                                        $("#code-text").text(txt_codbar);
                                        
                                        // Mostrar precio con IVA formateado con 2 decimales matemáticos
                                        if (precio_iva) {
                                            var precio_formateado = parseFloat(precio_iva).toFixed(2); // Forzar 2 decimales
                                            $("#price-iva").text("$" + precio_formateado);
                                        } else {
                                            $("#price-iva").text("$0.00"); // Valor por defecto con 2 decimales
                                        }
                                        
                                        $("#print").show();
                                    } else {
                                        // Ocultar el bloque si no hay código
                                        $("#print").hide();
                                        $("#code-text").text("");
                                        $("#price-iva").text("");
                                    }
                                }
                                
                                // Generar código de barras automáticamente al cambiar el valor de txt_codbar
                                $(document).ready(function() {
                                    $("#txt_codbar").on("input keyup", function() {
                                        generarbarcode();
                                    });
                                    
                                    // Generar código de barras al cargar la página si txt_codbar ya tiene valor
                                    if ($("#txt_codbar").val()) {
                                        generarbarcode();
                                    }
                                });
                                
                                function imprimir() {
                                    $("#print").printArea();
                                }
                                </script>                                
                                <!-- Código Auxiliar -->
                                <div id="codaux" class="form-group col-md-12">
                                    <label for="lb_codaux">Código Auxiliar</label>
                                    <input type="text" class="form-control" name="txt_codaux" id="txt_codaux" placeholder="Código Auxiliar" value="<?php if(@$pro != NULL){ print @$pro->pro_codigoauxiliar; }?>">
                                </div>
                                <!-- Nombre del Producto-->
                                <div id="nompro" class="form-group col-md-12">
                                    <label for="lb_nompro">Nombre</label>
                                    <input type="text" class="form-control validate[required]" name="txt_nompro" id="txt_nompro" placeholder="Nombre del Producto" value="<?php if(@$pro != NULL){ print @$pro->pro_nombre; }?>">
                                </div>
                                <!-- Descripción del Producto-->
                                <div class="form-group col-md-12">
                                    <label for="lb_despro">Descripción</label>
                                    <textarea class="form-control" rows="3" name="txt_despro" id="txt_despro" placeholder="Descripción del Producto"><?php if(@$pro != NULL){ print @$pro->pro_descripcion; }?></textarea>
                                </div>    
                                <!-- Imagen del Producto-->
                                <input type="hidden" name="old_image" value="<?php if(@$pro != NULL){ print @$pro->imagen_path; }?>">
                                <div class="col-xs-12 text-center">
                                    <h3 class="profile-username text-center">Imagen</h3>
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-preview thumbnail"  id="fotomostrar">
                                            <img width="150" height="150"<?php
                                                if (@$pro != NULL) {
                                                    if (@$pro->pro_imagen) {
                                                        print "src='". base_url() . "public/img/producto/" . $pro->imagen_path . "'";
                                                        /*print "src='data:image/jpeg;base64,$pro->pro_imagen' ";*/
                                                        
                                                    } else {
                                                        ?>
                                                        src="<?php print base_url(); ?>public/img/perfil.jpg" <?php
                                                    }
                                                } else {
                                            ?>
                                                    src="<?php print base_url(); ?>public/img/perfil.jpg" <?php }
                                                ?> alt="" onerror="this.src='<?php print base_url() . "public/img/perfil.jpg"; ?>';" />

                                        </div>
                                        <div>
                                        <br>
                                            <span class="btn btn-file btn-success">
                                                <span class="fileupload-new">Imagen</span>
                                                <span class="fileupload-exists">Cambiar</span>
                                                <input type="file"  id="logo" name="logo" accept="image/*" /> 
                                            </span>
                                            <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Quitar</a>
                                        </div>
                                    </div>
                                </div>    
                            </div>

                            <div class="col-xs-4">

                                <div class="box box-danger">
                                    <div class="box-body">
                                        <div class="row">          
                                            <!-- Unidades de Medida -->                            
                                            <div class="form-group col-md-12">
                                                <label>Unidades</label>
                                                <select id="cmb_uni" name="cmb_uni" class="form-control validate[required]">
                                                    <?php 
                                                      if(@$uni == NULL){ ?>
                                                      <option  value="0" selected="TRUE">Seleccione...</option>
                                                    <?php } else { ?>
                                                        
                                                    <?php } 
                                                              if (count($uni) > 0) {
                                                                foreach ($uni as $un):
                                                                    if(@$pro->pro_idunidadmedida != NULL){
                                                                        if($un->id == $pro->pro_idunidadmedida){ ?>
                                                                             <option value="<?php  print $un->id; ?>" selected="TRUE"> <?php  print $un->descripcion ?> </option>
                                                                            <?php
                                                                        }else{ ?>
                                                                            <option value="<?php  print $un->id; ?>" > <?php  print $un->descripcion ?> </option>
                                                                            <?php
                                                                        }
                                                                    }else{ ?>
                                                                        <option value="<?php  print $un->id; ?>"> <?php  print $un->descripcion ?> </option>
                                                                        <?php
                                                                        }   ?>
                                                                    <?php

                                                                endforeach;
                                                              }
                                                              ?>
                                                </select>                                    
                                            </div>

                                            <!-- Es Ingrediente --> 
                                            <div class="form-group col-md-12">
                                                <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                                    <label class="col-md-12"><input type="checkbox" name="chk_ing" id="chk_ing" class="minimal-red" <?php if(@$pro != NULL){ if(@$pro->ingrediente == 1){ print "checked='' ";} }?> > Es Ingrediente</label>
                                                </div>
                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                    <label class="col-md-12"><input type="checkbox" name="chk_pre" id="chk_pre" class="minimal-red" <?php if(@$pro != NULL){ if(@$pro->preparado == 1){ print "checked='' ";} }?> > Es Preparado</label>
                                                </div> 
                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                    <button id="composicion" type="button" class="btn btn-sm btn-success" >
                                                        <i class="fa fa-shopping-basket"></i> Composición 
                                                    </button> 
                                                </div>                                                                                                
                                            </div>

                                            <!-- Unidades de Medida -->                            
                                            <div class="form-group col-md-12">
                                                <label>Clasificación</label>
                                                <select id="cmb_cla" name="cmb_cla" class="form-control">
                                                    <?php 
                                                      if(@$cla != NULL){ ?>
                                                      <option  value="0" selected="TRUE">Seleccione...</option>
                                                    <?php } else { ?>
                                                        
                                                    <?php } 
                                                              if (count($cla) > 0) {
                                                                foreach ($cla as $cl):
                                                                    if(@$pro->idcla != NULL){
                                                                        if($cl->id_cla == $pro->idcla){ ?>
                                                                             <option value="<?php  print $cl->id_cla; ?>" selected="TRUE"> <?php  print $cl->nom_cla ?> </option>
                                                                            <?php
                                                                        }else{ ?>
                                                                            <option value="<?php  print $cl->id_cla; ?>" > <?php  print $cl->nom_cla ?> </option>
                                                                            <?php
                                                                        }
                                                                    }else{ ?>
                                                                        <option value="<?php  print $cl->id_cla; ?>"> <?php  print $cl->nom_cla ?> </option>
                                                                        <?php
                                                                        }   ?>
                                                                    <?php

                                                                endforeach;
                                                              }
                                                              ?>
                                                </select>                                    
                                            </div>                                            
                                            <!-- Cantidad Máxima -->
                                            <div class="form-group col-md-6">
                                                <label for="lb_canmax">Máximo</label>
                                                <input type="text" class="form-control editnocoma" name="txt_canmax" id="txt_canmax" placeholder="Cantidad Máxima" value="<?php if(@$pro != NULL){ print @$pro->pro_maximo; }?>">
                                            </div>
                                            <!-- Cantidad Mínima -->
                                            <div class="form-group col-md-6">
                                                <label for="lb_canmin">Mínimo</label>
                                                <input type="text" class="form-control editnocoma" name="txt_canmin" id="txt_canmin" placeholder="Cantidad Mínima" value="<?php if(@$pro != NULL){ print @$pro->pro_minimo; }?>">
                                            </div>  

                                            <!-- Categorías -->                            
                                            <div class="form-group col-md-12">
                                                <label>Categorías</label>
                                                <select id="cmb_cat" name="cmb_cat" class="form-control">
                                                    <?php 
                                                      if(@$cat != NULL){ ?>
                                                      <option  value="0" selected="TRUE">Seleccione...</option>
                                                    <?php } else { ?>
                                                        
                                                    <?php } 
                                                              if (count($cat) > 0) {
                                                                foreach ($cat as $ca):
                                                                    if(@$pro->pro_idunidadmedida != NULL){
                                                                        if($ca->cat_id == $pro->pro_idcategoria){ ?>
                                                                             <option value="<?php  print $ca->cat_id; ?>" selected="TRUE"> <?php  print $ca->cat_descripcion ?> </option>
                                                                            <?php
                                                                        }else{ ?>
                                                                            <option value="<?php  print $ca->cat_id; ?>" > <?php  print $ca->cat_descripcion ?> </option>
                                                                            <?php
                                                                        }
                                                                    }else{ ?>
                                                                        <option value="<?php  print $ca->cat_id; ?>"> <?php  print $ca->cat_descripcion ?> </option>
                                                                        <?php
                                                                        }   ?>
                                                                    <?php

                                                                endforeach;
                                                              }
                                                              ?>

                                                </select>
                                            </div>    
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">Días de Garantia</label>
                                                <div class="col-md-6" style="padding-right:0px;">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-right" name="txt_garantia" id="txt_garantia" placeholder="0" value="<?php if(@$pro->pro_garantia != NULL){ print @$pro->pro_garantia; } else{ print 0; }?>">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <!-- Es Servicio --> 
                                            <div class="form-group col-md-6">
                                                <label class="col-md-12"><input type="checkbox" name="chk_ser" id="chk_ser" class="minimal-red" <?php if(@$pro != NULL){ if(@$pro->pro_esservicio == 1){ print "checked='' ";} }?> > Es Servicio</label>
                                            </div>  
                                            <!-- Grava IVA --> 
                                            <div class="form-group col-md-6">
                                                <label class="col-md-12"><input type="checkbox" name="chk_iva" id="chk_iva" class="minimal-red" <?php if(@$pro != NULL){ if(@$pro->pro_grabaiva == 1){ print "checked='' ";} }?>  > Grava IVA</label>
                                            </div>  
                                            <!-- Ubicación -->
                                            <div class="form-group col-md-6">
                                                <label for="txt_ubicacion">Ubicación</label>
                                                <input type="text" class="form-control " name="txt_ubicacion" id="txt_ubicacion" placeholder="Ubicación" value="<?php if(@$pro != NULL){ print @$pro->ubicacion; }?>">
                                            </div>
                                            <!-- Subsidio -->
                                            <div class="form-group col-md-6">
                                                <label for="lb_canmax">Subsidio</label>
                                                <input type="text" class="form-control " name="txt_subsidio" id="txt_subsidio" placeholder="Subsidio" value="<?php if(@$pro != NULL){ print @$pro->subsidio; }?>">
                                            </div>
                                            <!-- Deducible IR -->                            
                                            <div class="form-group col-md-6">
                                                <label>Deducible IR</label>
                                                <select id="cmb_ded" name="cmb_ded" class="form-control">
                                                    <?php 
                                                      if(@$ded != NULL){ ?>
                                                      <option  value="0" selected="TRUE">Seleccione...</option>
                                                    <?php } else { ?>
                                                        
                                                    <?php } 
                                                              if (count($ded) > 0) {
                                                                foreach ($ded as $de):
                                                                    if(@$pro->pro_iddeducible != NULL){
                                                                        if($de->id_deducible == $pro->pro_iddeducible){ ?>
                                                                             <option value="<?php  print $de->id_deducible; ?>" selected="TRUE"> <?php  print $de->nom_deducible ?> </option>
                                                                            <?php
                                                                        }else{ ?>
                                                                            <option value="<?php  print $de->id_deducible; ?>" > <?php  print $de->nom_deducible ?> </option>
                                                                            <?php
                                                                        }
                                                                    }else{ ?>
                                                                        <option value="<?php  print $de->id_deducible; ?>"> <?php  print $de->nom_deducible ?> </option>
                                                                        <?php
                                                                        }   ?>
                                                                    <?php

                                                                endforeach;
                                                              }
                                                              ?>
                                                </select>                                    
                                            </div> 
                                            <!-- ESTATUS -->  
                                            <div class="form-group col-md-6">
                                                <label>Estatus</label>
                                                <select class="form-control validate[required]" id="cmb_est" name="cmb_est">
                                                    <option value="0">Seleccione...</option> 
                                                    <?php if($pro != NULL){ ?>
                                                    <?php if($pro->pro_estatus == 'A'){ ?>
                                                        <option value="<?php if($pro != NULL){ print $pro->pro_estatus; }?>" selected="TRUE">Activo</option>
                                                        <option value="I">Inactivo</option>
                                                    <?php } else {?>
                                                        <option value="A">Activo</option> 
                                                        <option value="<?php if($pro != NULL){ print $pro->pro_estatus; }?>" selected="TRUE">Inactivo</option>    
                                                    <?php } 
                                                        }
                                                    ?>
                                                    <?php if(@$pro == NULL){ ?>
                                                     <option value="A">Activo</option> 
                                                    <option value="I">Inactivo</option>  
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Dirigir Comanda hacia</label>
                                                <select id="cmb_com" name="cmb_com" class="form-control">
                                                    <?php 
                                                    if(@$com != NULL){ ?>
                                                        <option  value="0" selected="TRUE">Seleccione...</option>
                                                    <?php }  
                                                    if (count($com) > 0) {
                                                                foreach ($com as $co):
                                                                    if(@$pro->comanda != NULL){
                                                                        if($co->id_comanda == $pro->comanda){ ?>
                                                                             <option value="<?php  print $co->id_comanda; ?>" selected="TRUE"> <?php  print $co->nom_comanda; ?> </option>
                                                                            <?php
                                                                        }else{ ?>
                                                                            <option value="<?php  print $co->id_comanda; ?>" > <?php  print $co->nom_comanda; ?> </option>
                                                                            <?php
                                                                        }
                                                                    }else{ ?>
                                                                        <option value="<?php  print $co->id_comanda; ?>"> <?php  print $co->nom_comanda; ?> </option>
                                                                        <?php
                                                                        }   ?>
                                                                    <?php

                                                                endforeach;
                                                              }
                                                              ?>
                                                </select>
                                            </div>  

                                            <!-- Categoría Contable -->
                                            <div class="form-group col-md-12">
                                                <label>Categoría Contable</label>
                                                <select class="form-control " id="cmb_catcontable" name="cmb_catcontable">
                                                    <?php 
                                                  if(@$lstcatcontable != NULL){ ?>
                                                <?php } else { ?>
                                                    <option  value="0" selected="TRUE">Seleccione...</option>
                                                <?php } 
                                                          if (count($lstcatcontable) > 0) {
                                                            foreach ($lstcatcontable as $obj):
                                                                if(@$pro->idcategoriacontable != NULL){
                                                                    if(@$obj->id == @$pro->idcategoriacontable){ ?>
                                                                        <option  value="<?php  print $obj->id; ?>" selected="TRUE"><?php  print @$obj->categoria ?></option> 
                                                                        <?php
                                                                    }else{ ?>
                                                                        <option value="<?php  print @$obj->id; ?>"> <?php  print @$obj->categoria ?> </option>
                                                                        <?php
                                                                    }
                                                                }else{ ?>
                                                                    <option value="<?php  print @$obj->id; ?>"> <?php  print @$obj->categoria ?> </option>
                                                                    <?php
                                                                    }   ?>
                                                                <?php

                                                            endforeach;
                                                          }
                                                          ?>
                                                </select>
                                            </div>  

                                            <div class="col-md-12" style="padding-right: 10px; padding-left: 10px;">

                                                <div class="box box-danger" id="div_variante">
                                                    <div class="box-header with-border  text-center">
                                                        <label class="col-md-12"><input type="checkbox" name="chk_var" id="chk_var" class="minimal-red" <?php if(@$pro != NULL){ if(@$pro->habilitavariante == 1){ print "checked='' ";} }?> > Tiene Variante</label>
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="row"> 
                                                            <!-- Máximo Item -->                                        
                                                            <div class="form-group col-md-12">
                                                                <label class="col-md-4 control-label text-right">Máximo Item</label>
                                                                <div class="col-md-3">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control text-center" name="txt_maxitem" id="txt_maxitem" placeholder="Máximo Item" value="<?php if(@$pro != NULL){ print @$pro->maxitemvariante; }?>">
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            <!-- Producto a Descontar en Venta -->                            
                                                            <div class="form-group col-md-8">
                                                                <label>Producto a Descontar</label>
                                                                <select id="cmb_pro_vent" name="cmb_pro_vent" class="form-control">
                                                                    <?php 
                                                                      if(@$procomp != NULL){ ?>
                                                                      <option  value="0" selected="TRUE">Seleccione...</option>
                                                                    <?php } else { ?>
                                                                        
                                                                    <?php } 
                                                                              if (count($procomp) > 0) {
                                                                                foreach ($procomp as $pc):
                                                                                    if(@$pro->pro_aplicompra != NULL){
                                                                                        if($pc->pro_id == $pro->productodescontarventa){ ?>
                                                                                             <option value="<?php  print $pc->pro_id; ?>" selected="TRUE"> <?php  print $pc->pro_nombre ?> </option>
                                                                                            <?php
                                                                                        }else{ ?>
                                                                                            <option value="<?php  print $pc->pro_id; ?>" > <?php  print $pc->pro_nombre ?> </option>
                                                                                            <?php
                                                                                        }
                                                                                    }else{ ?>
                                                                                        <option value="<?php  print $pc->pro_id; ?>"> <?php  print $pc->pro_nombre ?> </option>
                                                                                        <?php
                                                                                        }   ?>
                                                                                    <?php

                                                                                endforeach;
                                                                              }
                                                                              ?>

                                                                </select>
                                                            </div>
                                                            <!-- CANTIDAD A DESCONTAR -->
                                                            <div class="form-group col-md-4">
                                                            <label for="lb_cantidad">Cantidad</label>
                                                            <input type="text" class="form-control text-center" name="txt_cantidad" id="txt_cantidad" placeholder="" value="<?php if(@$pro != NULL){ print @$pro->cantidad; }else{print "0";}?>">
                                                            </div> 
                                                            <div class="form-actions pull-right" style="margin-right: 15px; margin-bottom: 3%;">

                                                                <a href="#" title="Ver" id="add_variante" class="btn btn-primary btn-grad ">
                                                                    <i class="fa fa-plus"></i> Añadir Item
                                                                </a> 

                                                            </div>  

                                                             
                                                            <div id="detvar" class="col-md-12" > 
                                                                <table class="table table-bordered vardet">
                                                                    <tbody>
                                                                        <tr>
                                                                          <th class="text-center">Nro</th>
                                                                          <th>Item</th>
                                                                          <th class="text-center">Acción</th>
                                                                        </tr>
                                                                        <?php 
                                                                            $nro = 0;
                                                                            /* SI YA EXISTE EN LA BASE DE DATOS */
                                                                        /*    if(@$provar != NULL){ 
                                                                                if (count($provar) > 0) {
                                                                                    foreach ($provar as $pv):
                                                                                        $nro = $nro + 1;
                                                                                    ?>
                                                                                        <tr>
                                                                                            <td class="text-center"><?php if(@$pv != NULL){ print @$nro; }?></td>
                                                                                            <td><?php if(@$pv != NULL){ print @$pv->descripcion; }?></td>
                                                                                            <td class="text-center">
                                                                                                <a href="#" title="Ver" id="<?php if(@$pv != NULL){ print @$pv->id_variante; }?>" class="btn btn-success btn-xs btn-grad provar_edi"><i class="fa fa-pencil-square-o"></i></a> 
                                                                                                <a href="#" title="Eliminar" id="<?php if(@$pv != NULL){ print @$pv->id_variante; }?>" class="btn btn-danger btn-xs btn-grad provar_del"><i class="fa fa-trash-o"></i></a>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php
                                                                                    endforeach;
                                                                                    /* SI VIENE DE LA VARIABLE DE SESION */ 
                                                                        /*        }
                                                                                                                                   
                                                                            }else 
                                                                            */
                                                                            if(@$vardatos != NULL){
                                                                                if (count($vardatos) > 0) {
                                                                                    foreach ($vardatos as $vd=>$valor):
                                                                                        $nro = $nro + 1;
                                                                            ?>
                                                                                        <tr>
                                                                                            <td class="text-center"><?php print @$nro; ?></td>
                                                                                             <td><?php print @$valor ?></td>
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
                                                            </div> 
                          
                                                        </div>  
                                                    </div>
                                                </div> 

                                            </div>

                                        </div>  
                                    </div>
                                </div>  

                            </div>

                            <div class="col-xs-4">
                                <div class="box box-danger">
                                    <div class="box-header with-border  text-center">
                                        <h3 class="box-title"><i class="fa fa-usd"></i> Precios</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">          
                                            <!-- Precio de Compra --> 
                                            <div class="form-group col-md-12">
                                                <label class="">
                                                    <input type="checkbox" name="chk_compra" id="chk_compra" class="" <?php if(@$pro != NULL){ if(@$pro->pro_aplicompra == 1){ print "checked='' ";} }?> > Aplica Compra
                                                </label>
                                            </div>  
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">Precio de Compra</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">$</span>
                                                        <input type="text" class="form-control input-sm validate[required] text-right editnocoma" name="txt_precomp" id="txt_precomp" placeholder="Precio Compra" value="<?php if(@$pro != NULL){ print number_format(@$pro->pro_preciocompra, $decimalesprecio); }?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>Concepto de Retencion de Renta</label>
                                                <select id="cmb_ret" name="cmb_ret" class="form-control">
                                                    <?php 
                                                    if(@$retenciones != NULL){ ?>
                                                        <option  value="0" selected="TRUE">Seleccione...</option>
                                                    <?php }  
                                                    if (count(@$retenciones) > 0) {
                                                                foreach ($retenciones as $co):
                                                                    if(@$pro->comanda != NULL){
                                                                        if($co->id_cto_retencion == $pro->id_cto_retencion){ ?>
                                                                             <option value="<?php  print $co->id_cto_retencion; ?>" selected="TRUE"> <?php  print $co->cod_cto_retencion . "-" . substr($co->descripcion_retencion,0,70); ?> </option>
                                                                            <?php
                                                                        }else{ ?>
                                                                            <option value="<?php  print $co->id_cto_retencion; ?>" > <?php  print $co->cod_cto_retencion . "-" . substr($co->descripcion_retencion,0,70); ?> </option>
                                                                            <?php
                                                                        }
                                                                    }else{ ?>
                                                                        <option value="<?php  print $co->id_cto_retencion; ?>"> <?php  print $co->cod_cto_retencion . "-" . substr($co->descripcion_retencion,0,70); ?> </option>
                                                                        <?php
                                                                        }   ?>
                                                                    <?php

                                                                endforeach;
                                                              }
                                                              ?>
                                                </select>
                                            </div>                                            


                                            <!-- Precio de Venta --> 
                                            <div class="form-group col-md-12">
                                                <label class="">
                                                     <input name="chk_venta" id="chk_venta" class="" type="checkbox" <?php if(@$pro != NULL){ if(@$pro->pro_apliventa == 1){ print "checked='' ";} }?> > Aplica Venta
                                                    
                                                </label>
                                            </div>    
                                            <div class="form-group col-md-12" style="margin-bottom: 2px;">
                                                <label class="col-md-6 control-label text-right" style="padding-left: 0px;">Precio sin IVA</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm validate[required] text-right editnocoma" name="txt_prevent" id="txt_prevent" placeholder="Precio Venta" value="<?php if(@$pro != NULL){ print number_format(@$pro->pro_precioventa,$decimalesprecio); } else {print 0;}?>">
                                                        <input type="hidden" id="porc_compraventapvp" value="<?php if(@$pro != NULL){ print @$pro->porciento_compraventa; } else {print 0;}?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">Precio con IVA</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm validate[required] text-right editnocoma" name="txt_preventiva" id="txt_preventiva" placeholder="Precio Venta" value="">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <div class="tipo_precio">
                                                <div class="col-md-12">
                                                   <hr class=""> 
                                                </div>
                                                <h4 class="text-center">Otros Precios de Venta</h4>
                                                <?php 
                                                    if(@$pre != NULL){ 
                                                        foreach ($pre as $pr): 
                                                            if(@$propre != NULL){
                                                                $precio = 0;
                                                                foreach ($propre as $pp) {
                                                                    if(@$pr->id_precios == $pp->id_precios){
                                                                        $precio = $pp->monto;
                                                                    }
                                                                }    
                                                            }        
                                                ?>
                                                            <div class="form-group col-md-12" style="margin-bottom:4px;">
                                                                <label class="col-md-5 control-label text-right"><?php if(@$pr != NULL){ print @$pr->desc_precios; } ?></label>
                                                                <input type="hidden" 
                                                                  class="porc_relacionprecio" 
                                                                  id="<?php if(@$pr != NULL){ print @$pr->id_precios; } ?>"  
                                                                  value="<?php print @$pr->porciento; ?>"                                                                                   
                                                                >

                                                                <div class="col-md-7" style="padding-right: 0px;">
                                                                    <div class="input-group" style="padding-bottom:4px;">
                                                                        <div class="col-xs-6" style="padding-left: 0px; padding-right: 0px;">
                                                                            <input type="text" class="form-control input-sm text-right psiniva editnocoma" placeholder="0.00" id="<?php if(@$pr != NULL){ print @$pr->id_precios; } ?>" name="pre<?php if(@$pr != NULL){ print @$pr->id_precios; } ?>" 
                                                                                   value="<?php print number_format(@$precio,$decimalesprecio); ?>"                                                                                   
                                                                            >
                                                                        </div>
                                                                        <div class="col-xs-6" style="padding-left: 2px; padding-right: 0px;" >
                                                                            <input type="text" class="form-control input-sm text-right pconiva editnocoma" placeholder="0.00" id="pconiva" name="<?php if(@$pr != NULL){ print @$pr->id_precios; } ?>" 
                                                                                   value="<?php  print number_format(@$precio * 0.15 + @$precio,$decimalesprecio); ?>"                                                                                   
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                <?php
                                                        endforeach;  
                                                    }                                       
                                                ?> 
                                            </div>

                                        </div>  
                                    </div>
                                </div>    

                                                <div class="box box-danger">
                                                    <div class="box-header with-border">
                                                        <h3 class="box-title"><i class="fa fa-line-chart"></i> Inventario</h3>
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="row">          
                                                            <!-- Precio de Compra --> 
                                                            <div class="form-group col-md-12">
                                                                <?php 
                                                                    if(@$almacen != NULL){ 
                                                                        foreach ($almacen as $al): 
                                                                ?>
                                                                            <div class="form-group col-md-12" style="padding-left: 0px; ">
                                                                                <label class="col-md-7 control-label text-right" style="padding-left: 0px; padding-right: 0px;" ><?php if(@$al != NULL){ print @$al->almacen_nombre; } ?></label>
                                                                                <div class="col-md-5" style="padding-right: 0px;">
                                                                                    <input class="form-control input-sm text-right almacen validate[required] editnocoma" type="text" 
                                                                                        id="<?php if(@$al != NULL){ print @$al->almacen_id; } ?>" 
                                                                                        name="alm<?php if(@$al != NULL){ print @$al->almacen_id; } ?>" 
                                                                                        value="<?php print @$al->existencia; ?>"  >
                                                                                </div>
                                                                            </div>
                                                                <?php
                                                                        endforeach;
                                                                    }                                       
                                                                ?> 
                                                            </div> 
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
                            <button id="guardar" type="submit" class="btn btn-success btn-grad no-margin-bottom">
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
                <script src="<?php print $base_url; ?>assets/plugins/jQuery/JsBarcode.all.min.js"></script>
                <script src="<?php print $base_url; ?>assets/plugins/jQuery/jquery.PrintArea.js"></script>

