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
                                    <button class="btn btn-success" type="button" onclick="generarbarcode()">Generar</button>
                                    <button class="btn btn-info" type="button" onclick="imprimir()">Imprimir</button>
                                </div>

                            <div id="print" style="position: relative; width: 4.98cm; height: 3.5cm; border: 1px solid #000; border-radius: 10px; overflow: hidden;">
                                <div style="position: absolute; top: 1px; left: 50%; transform: translateX(-50%); text-align: center; width: 100%; font-weight: bold; font-size: 14px;">
                                    JORLE FASHION STORE
                                </div>
                                
                                <div style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%); text-align: center; width: 100%; font-size: 12px;">
                                    <?php if(@$pro != NULL){ print @$pro->pro_nombre; }?>
                                    </div>
                                    <svg id="barcode" style="position: absolute; top: 35%; text-align: center; width: 100%; height: calc(100% - 40px);"></svg>
                                </div>

                                <script type="text/javascript">
                                function generarbarcode()
                                {
                                    txt_codbar=$("#txt_codbar").val();
                                    JsBarcode("#barcode", txt_codbar);
                                    $("#print").show();
                                }
                                
                                //Función para imprimir el Código de barras
                                function imprimir()
                                {
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

