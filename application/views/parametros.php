<?php
/* ------------------------------------------------
  ARCHIVO: parametros.php
  DESCRIPCION: Contiene la vista principal del módulo de parametros.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Parametros'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<script>
  $( document ).ready(function() {
    $("#frm_emp").validationEngine();

    $('#chk_serviciotecnico').click(function (event) {
        actualizacontroles_servicio();
    });    

    function actualizacontroles_servicio(){
        if ($('#chk_serviciotecnico').is(":checked")) {
            $('#chk_servicioserie').attr("disabled", false);
            $('#chk_serviciodetalle').attr("disabled", false);
            $('#chk_servicioencargado').attr("disabled", false);
            $('#chk_servicioprodutilizado').attr("disabled", false);
            $('#chk_servicioabono').attr("disabled", false);
            $('#pro_servicio').attr("disabled", false);           
            $('#chk_productofactura').attr("disabled", false);                      
        } else {
            $('#chk_servicioserie').prop("checked", false);
            $('#chk_serviciodetalle').prop("checked", false);
            $('#chk_servicioencargado').prop("checked", false);
            $('#chk_servicioprodutilizado').prop("checked", false);
            $('#chk_servicioabono').prop("checked", false);
            $('#pro_servicio').prop("checked", false);           
            $('#chk_productofactura').prop("checked", false);                            

            $('#chk_servicioserie').attr("disabled", true);
            $('#chk_serviciodetalle').attr("disabled", true);
            $('#chk_servicioencargado').attr("disabled", true);
            $('#chk_servicioprodutilizado').attr("disabled", true);
            $('#chk_servicioabono').attr("disabled", true);
            $('#pro_servicio').attr("disabled", true);           
            $('#chk_productofactura').attr("disabled", true);                      
        }
    }

    $('#txt_formatoimpfactura').change(function (event) {
        habilita_impresiongrafica();
    });    

    function habilita_impresiongrafica(){
      var tmpformato = $('#txt_formatoimpfactura').val();
      if ((tmpformato == '') || (tmpformato == 0)){
        $(".impresiongrafica").show();        
      }
      else{
        $(".impresiongrafica").hide();               
      }
    }

    $('#chk_descpro').change(function (event) {
      if ($('#chk_descpro').is(":checked")) {
        $('.tipodescuentoproducto').attr("disabled", false);
      }  
      else{
        $('.tipodescuentoproducto').attr("disabled", true);       
      }
    });    

    $('#txt_impuestoadicvalor').blur(function (event) {
      var imp = $('#txt_impuestoadicvalor').val();
      if ($.trim(imp) === ''){
        $('#txt_impuestoadicvalor').val(0)
      }
    });    
    



    actualizacontroles_servicio();
    habilita_impresiongrafica();

  });

</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       <i class="fa fa-cogs"></i> Parámetros Generales </a></li>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- SECCION DEL FORMULARIO-->
            <div class="col-md-8" style="padding-right: 5px;">
                <!-- general form elements -->
                <div class="box box-danger">
<!--                     <div class="box-header with-border">
                        <h3 class="box-title">Parametros Generales</h3>
                    </div> -->
                  <!--   <form role="form"> -->
             
                    <form id="frm_emp" name="frm_emp" method="post" role="form" class="form" 
                          enctype="multipart/form-data"
                          action="<?php echo base_url('parametros/guardar');?>">
                        <div class="box-body">

                         <div class="nav-tabs-custom">
                          <ul class="nav nav-tabs">
                           <li class="active"><a href="#tabgeneral" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> GENERAL</a></li>                            
                           <li ><a href="#tabrestaurante" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> PEDIDO</a></li>                            
                           <li ><a href="#tabservicio" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> SERVICIO TECNICO</a></li>                            
                           <li ><a href="#tabpetshop" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> PETSHOP</a></li>                            
                           <li ><a href="#tabsistema" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> SISTEMA</a></li>                            
                          </ul>

                          <div class="tab-content">
                           <div class="tab-pane active" id="tabgeneral">

                            <!-- Porciento IVA -->
                            <div class="form-group col-md-6">
                                <label style="padding-top: 15px;" for="txt_iva">Porciento IVA</label>
                                <input type="text" class="form-control validate[required] text-right" name="txt_iva" id="txt_iva" placeholder="Porciento IVA" value="<?php if(@$iva != NULL){ print @$iva; }?>">
                            </div>

                            <div class="form-group col-md-6">
                              <label style="padding-top: 15px;">Impresora para Factura</label>
                              <select id="txt_impfactura" name="txt_impfactura" class="form-control">
                                <?php 
                                  if(@$objfactura != NULL){ ?>
                                    <option  value="<?php if(@$objfactura != NULL){ print @$objfactura->id_comanda; }?>" selected="TRUE"><?php if($objfactura->nom_comanda != NULL){ print $objfactura->nom_comanda; }?></option>
                                <?php } else { ?>
                                    <option  value="0" selected="TRUE">Seleccione...</option>
                                <?php } 
                                    $tmpid = 0;  
                                    if(@$objfactura != NULL) {$tmpid = @$objfactura->id_comanda;} 
                                    if (count($impresoras) > 0) {
                                        foreach ($impresoras as $uni):
                                          if ($tmpid != $uni->id_comanda){
                                            ?>
                                            <option value="<?php  print $uni->id_comanda; ?>"> <?php  print $uni->nom_comanda ?> </option>
                                            <?php
                                        }    
                                        endforeach;
                                    }
                                    ?>
                              </select>
                            </div>

                            <!-- Limite de Productos en Venta -->
                            <div class="form-group col-md-6">
                                <label for="txt_limiteprodventa">Límite de Productos en Venta</label>
                                <input type="text" class="form-control validate[required] text-right" name="txt_limiteprodventa" id="txt_limiteprodventa" placeholder="Limite de Productos en Factura" value="<?php if(@$limiteprodventa != NULL){ print @$limiteprodventa; }?>">
                            </div>

                            <div class="box box-info col-md-12">
                              <div class="form-group col-md-12">
                                <label>Impuesto Adicional</label>
                              </div>
                              <div class="form-group col-md-6">
                                  <label for="txt_impuestoadicdescrip">Nombre de Impuesto</label>
                                  <input type="text" class="form-control " name="txt_impuestoadicdescrip" id="txt_impuestoadicdescrip" placeholder="Nombre de Impuesto" value="<?php if(@$impuestoadicdescrip != NULL){ print @$impuestoadicdescrip; }?>">
                              </div>
                              <div class="form-group col-md-6">
                                  <label for="txt_impuestoadicvalor">Valor de Impuesto</label>
                                  <input type="text" class="form-control text-right" name="txt_impuestoadicvalor" id="txt_impuestoadicvalor" placeholder="Valor de Impuesto" value="<?php if(@$impuestoadicvalor != NULL){ print @$impuestoadicvalor; }?>">
                              </div>
                            </div>

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_tipoprecio" id="chk_tipoprecio" class="minimal-red" <?php if(@$tipoprecio != NULL){ if(@$tipoprecio == 1){ print "checked='' ";} }?> > Habilitar Gestión de Tipos de Precios</label>
                            </div> 
                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_facturasinexistencia" id="chk_facturasinexistencia" class="minimal-red" <?php if(@$facturasinexistencia != NULL){ if(@$facturasinexistencia == 1){ print "checked='' ";} }?> > Habilitar Facturación sin Existencia de Producto</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-9"><input type="checkbox" name="chk_descpro" id="chk_descpro" class="minimal-red" <?php if(@$descpro != NULL){ if(@$descpro == 1){ print "checked='' ";} }?> > Habilitar Facturación con Descuento por Producto</label>

                                <div class="" >
                                  <span class="">
                                    <label class="radio-inline">
                                      <input type="radio" class="tipodescuentoproducto" id="tipodescuentoproducto" name="tipodescuentoproducto" <?php if(@$tipodescuentoproducto == 1){ print "checked";} ?> value="1"> Porciento
                                    </label>
                                    <label class="radio-inline">
                                      <input type="radio" class="tipodescuentoproducto" id="tipodescuentoproducto" name="tipodescuentoproducto" <?php if(@$tipodescuentoproducto == 0){ print "checked";} ?>  value="0"> Valor
                                    </label>   
                                  </span>
                                </div>                                
                            </div>                             

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_numeroserie" id="chk_numeroserie" class="minimal-red" <?php if(@$numeroserie != NULL){ if(@$numeroserie == 1){ print "checked='' ";} }?> > Habilitar Número de Serie</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_clientevendedor" id="chk_clientevendedor" class="minimal-red" <?php if(@$clientevendedor != NULL){ if(@$clientevendedor == 1){ print "checked='' ";} }?> > Habilitar Asociación Automática entre Cliente y Vendedor</label>
                            </div> 
                            
                            <div class="col-md-12">
                                <label class="col-md-8 text-right" style="margin-right: 0px; padding-right: 0px;" for="txt_impuestoadicvalor">Cuota Mínima de Venta para asociar</label>
                                <div class="form-group col-md-4">
                                  <input type="text" class="form-control text-right" name="txt_cuotaclientevendedor" id="txt_cuotaclientevendedor" placeholder="Cuota Minima" value="<?php if(@$cuotaclientevendedor != NULL){ print @$cuotaclientevendedor; }?>">
                                </div>
                            </div>

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_clientecategoria" id="chk_clientecategoria" class="minimal-red" <?php if(@$clientecategoria != NULL){ if(@$clientecategoria == 1){ print "checked='' ";} }?> > Habilitar Asignación Automática de Categoría de Venta al  Cliente</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_codigocliente" id="chk_codigocliente" class="minimal-red" <?php if(@$codigocliente != NULL){ if(@$codigocliente == 1){ print "checked='' ";} }?> > Habilitar Código de Cliente en Venta</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_ubicacionventa" id="chk_ubicacionventa" class="minimal-red" <?php if(@$ubicacionventa != NULL){ if(@$ubicacionventa == 1){ print "checked='' ";} }?> > Habilitar Ubicación de Producto en Venta</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_detalletotalivaventa" id="chk_detalletotalivaventa" class="minimal-red" <?php if(@$detalletotalivaventa != NULL){ if(@$detalletotalivaventa == 1){ print "checked='' ";} }?> > Habilitar Subtotal con IVA en Detalle de Venta</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_impresionsubsidio" id="chk_impresionsubsidio" class="minimal-red" <?php if(@$impresionsubsidio != NULL){ if(@$impresionsubsidio == 1){ print "checked='' ";} }?> > Habilitar Impresión de Subsidio en Venta</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_facturaprecioconiva" id="chk_facturaprecioconiva" class="minimal-red" <?php if(@$facturaprecioconiva != NULL){ if(@$facturaprecioconiva == 1){ print "checked='' ";} }?> > Precio de Producto en Venta incluye IVA</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_notaventaiva" id="chk_notaventaiva" class="minimal-red" <?php if(@$habilitanotaventaiva != NULL){ if(@$habilitanotaventaiva == 1){ print "checked='' ";} }?> > Habilitar IVA en Nota de Venta</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_correoautosri" id="chk_correoautosri" class="minimal-red" <?php if(@$habilitacorreoautosri != NULL){ if(@$habilitacorreoautosri == 1){ print "checked='' ";} }?> > Habilitar Envío de Correo al Autorizar Comprobante SRI</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_cambioprecio" id="chk_cambioprecio" class="minimal-red" <?php if(@$habilitacambioprecio != NULL){ if(@$habilitacambioprecio == 1){ print "checked='' ";} }?> > Habilitar Cambio de Precio en Venta solo a Usuario Administrador</label>
                            </div> 

                            <div class="form-group col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_ventapagoefectivo" id="chk_ventapagoefectivo" class="minimal-red" <?php if(@$ventapagoefectivo != NULL){ if(@$ventapagoefectivo == 1){ print "checked='' ";} }?> > Predeterminar Pago en Efectivo en la  Venta</label>
                            </div> 

                            <div class="box box-info col-md-12">
                              <div class="form-group col-md-12">
                                <label>Cantidad de Decimales en Detalle de Venta</label>
                              </div>
                              <div class="form-group col-md-6">
                                  <label for="txt_decimalesprecio">Decimales en Precio</label>
                                  <input type="number" min="0" max="6" class="form-control " name="txt_decimalesprecio" id="txt_decimalesprecio" placeholder="Decimales en Precio" value="<?php if(@$decimalesprecio != NULL){ print @$decimalesprecio; }?>">
                              </div>
                              <div class="form-group col-md-6">
                                  <label for="txt_decimalescantidad">Decimales en Cantidad</label>
                                  <input type="number" min="0" max="4" class="form-control text-right" name="txt_decimalescantidad" id="txt_decimalescantidad" placeholder="Decimales en Cantidad" value="<?php if(@$decimalescantidad != NULL){ print @$decimalescantidad; }?>">
                              </div>
                            </div>

                            <div class="form-group col-md-6" style="padding-top: 0px;">
                              <label>Formato de Impresión</label>
                              <select id="txt_formatoimpfactura" name="txt_formatoimpfactura" class="form-control">
                                <?php 
                                  $arrformat[] = null;
                                  $arrformat[0] = "Ticket";
                                  $arrformat[1] = "PDF";
                                  $arrformat[2] = "A4";
                                  if(@$facturapdf != NULL){ ?>
                                    <option  value="<?php print $facturapdf; ?>" selected="TRUE"><?php print $arrformat[$facturapdf]; ?></option>
                                    <?php } else { ?>
                                        <option  value="0" selected="TRUE">Seleccione...</option>
                                    <?php } 
                                    $tmpid = 0;  
                                    if(@$facturapdf != NULL) {$tmpid = @$facturapdf;} 
                                    foreach ($arrformat as $i => $item):
                                    if ($tmpid != $i){
                                        ?>
                                        <option value="<?php  print $i; ?>"> <?php  print $arrformat[$i]; ?> </option>
                                        <?php
                                    }    
                                    endforeach;
                                    ?>
                              </select>
                            </div>

                            <div class="form-group col-md-6" style="padding-left: 0px; padding-right: 0px;">
                              <label class="col-md-12"><input type="checkbox" name="chk_impresionlocal" id="chk_impresionlocal" class="minimal-red" <?php if(@$impresionlocal != NULL){ if(@$impresionlocal == 1){ print "checked='' ";} }?> > Habilitar Impresion Local</label>
                              <label class="col-md-12 impresiongrafica"><input type="checkbox" name="chk_impresiongrafica" id="chk_impresiongrafica" class="minimal-red" <?php if(@$impresiongrafica != NULL){ if(@$impresiongrafica == 1){ print "checked='' ";} }?> > Habilitar Impresion Gráfica</label>
                            </div> 

                          </div>  <!-- Tab General --> 


                          <div class="tab-pane" id="tabrestaurante">

                            <div class="col-md-12" style="padding-top: 20px;">
                              <label class="form-group col-md-4" >Impresora para Precuenta</label>
                              <div class="form-group col-md-4">
                                <select id="txt_impprecuenta" name="txt_impprecuenta" class="form-control">
                                  <?php 
                                    if(@$objprecuenta != NULL){ ?>
                                      <option  value="<?php if(@$objprecuenta != NULL){ print @$objprecuenta->id_comanda; }?>" selected="TRUE"><?php if($objprecuenta->nom_comanda != NULL){ print $objprecuenta->nom_comanda; }?></option>
                                  <?php } else { ?>
                                      <option  value="0" selected="TRUE">Seleccione...</option>
                                  <?php } 
                                      $tmpid = 0;  
                                      if(@$objprecuenta != NULL) {$tmpid = @$objprecuenta->id_comanda;} 
                                      if (count($impresoras) > 0) {
                                          foreach ($impresoras as $uni):
                                            if ($tmpid != $uni->id_comanda){
                                              ?>
                                              <option value="<?php  print $uni->id_comanda; ?>"> <?php  print $uni->nom_comanda ?> </option>
                                              <?php
                                          }    
                                          endforeach;
                                      }
                                      ?>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_pedidovista" id="chk_pedidovista" class="minimal-red" <?php if(@$pedidovista != NULL){ if(@$pedidovista == 1){ print "checked='' ";} }?> > Mostrar Vista de Pedido</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_pedidocliente" id="chk_pedidocliente" class="minimal-red" <?php if(@$pedidocliente != NULL){ if(@$pedidocliente == 1){ print "checked='' ";} }?> > Mostrar Cliente en Pedido</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_pedidomesero" id="chk_pedidomesero" class="minimal-red" <?php if(@$pedidomesero != NULL){ if(@$pedidomesero == 1){ print "checked='' ";} }?> > Mostrar Vendedor en Pedido</label>
                            </div> 
                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_comandafactura" id="chk_comandafactura" class="minimal-red" <?php if(@$imprimircomandafactura != NULL){ if(@$imprimircomandafactura == 1){ print "checked='' ";} }?> > Imprimir Comanda al Facturar</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_numeroorden" id="chk_numeroorden" class="minimal-red" <?php if(@$habilitanumeroorden != NULL){ if(@$habilitanumeroorden == 1){ print "checked='' ";} }?> > Habilitar Numero de Orden en Factura</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_variante" id="chk_variante" class="minimal-red" <?php if(@$habilitavariante != NULL){ if(@$habilitavariante == 1){ print "checked='' ";} }?> > Habilitar Variantes de Productos</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_pedidopromo" id="chk_pedidopromo" class="minimal-red" <?php if(@$pedidopromo != NULL){ if(@$pedidopromo == 1){ print "checked='' ";} }?> > Habilitar Promo en Pedido</label>
                            </div> 

                            <div class="form-group col-md-12">
                              <label class="col-md-6" for="txt_ptoventasingular">Etiqueta de Punto de Venta (Singular)</label>
                              <div class="col-md-5" style="padding: 0px;">
                                <input type="text" class="form-control " name="txt_ptoventasingular" id="txt_ptoventasingular" placeholder="Etiqueta de Punto de Venta (Singular)" value="<?php if(@$ptoventasingular != NULL){ print $ptoventasingular; } else {print 'Mesa';}?>">
                              </div>
                            </div>

                            <div class="form-group col-md-12">
                              <label class="col-md-6" for="txt_ptoventaplural">Etiqueta de Punto de Venta (Plural)</label>
                              <div class="col-md-5" style="padding: 0px;">
                                <input type="text" class="form-control " name="txt_ptoventaplural" id="txt_ptoventasingular" placeholder="Etiqueta de Punto de Venta (Plural)" value="<?php if(@$ptoventaplural != NULL){ print $ptoventaplural; } else {print 'Mesas';}?>">
                              </div>
                            </div>

                            <div class="col-md-12" >
                              <label class="col-md-6" style="padding-right: 0px;">Estado de Punto de Venta al Facturar</label>
                              <div class="col-md-5" style="padding: 0px;">
                                <select id="lst_estadoptoventafacturar" name="lst_estadoptoventafacturar" class="form-control" >
                                <?php 
                                  foreach ($lst_estadoptoventa as $estado):
                                    if(@$estadoptoventafacturar == $estado->id){ ?>
                                         <option value="<?php  print $estado->id; ?>" selected="TRUE"> <?php  print $estado->estado; ?> </option>
                                        <?php
                                    }else{ ?>
                                        <option value="<?php  print $estado->id; ?>" > <?php  print $estado->estado; ?> </option>
                                        <?php
                                    }
                                  endforeach;
                                ?>
                                </select> 
                              </div>
                            </div>


                          </div> 

                          <!--  SErvicio Tecnico -->
                          <div class="tab-pane" id="tabservicio">

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px; padding-top: 15px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_serviciotecnico" id="chk_serviciotecnico" class="minimal-red" <?php if(@$serviciotecnico != NULL){ if(@$serviciotecnico->habilita_servicio == 1){ print "checked='' ";} }?> > Habilitar Gestión de Servicio</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 10px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_servicioserie" id="chk_servicioserie" class="minimal-red" <?php if(@$serviciotecnico != NULL){ if(@$serviciotecnico->habilita_serie == 1){ print "checked='' ";} }?> > Habilitar Busqueda de Producto por Serie</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 10px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_serviciodetalle" id="chk_serviciodetalle" class="minimal-red" <?php if(@$serviciotecnico != NULL){ if(@$serviciotecnico->habilita_detalle == 1){ print "checked='' ";} }?> > Habilitar Detalle de Servicio</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 10px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_servicioencargado" id="chk_servicioencargado" class="minimal-red" <?php if(@$serviciotecnico != NULL){ if(@$serviciotecnico->habilita_encargado == 1){ print "checked='' ";} }?> > Habilitar Encargado de Servicio</label>
                            </div> 

                            <div class="col-md-12" style="padding-left: 10px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_servicioprodutilizado" id="chk_servicioprodutilizado" class="minimal-red" <?php if(@$serviciotecnico != NULL){ if(@$serviciotecnico->habilita_productoutilizado == 1){ print "checked='' ";} }?> > Habilitar Productos Utilizados</label>
                            </div>  

                            <div class="col-md-12" style="padding-left: 10px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_servicioabono" id="chk_servicioabono" class="minimal-red" <?php if(@$serviciotecnico != NULL){ if(@$serviciotecnico->habilita_abono == 1){ print "checked='' ";} }?> > Habilitar Abonos de Servicio</label>
                            </div>  

                            <div class="col-md-12" style="padding-left: 10px; padding-right: 0px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_productofactura" id="chk_productofactura" class="minimal-red" <?php if(@$serviciotecnico != NULL){ if(@$serviciotecnico->habilita_productofactura == 1){ print "checked='' ";} }?> > Incluir Productos en la Factura</label>
                            </div>  

                            <div class="form-group col-md-12" >
                              <label style="padding-left: 10px; padding-right: 0px;">Producto utilizado para generar Factura</label>
                              <select id="pro_servicio" name="pro_servicio" class="form-control" style="padding-left: 10px; padding-right: 0px;">
                              <?php 
                                if(@$pro_servicio != NULL){ ?>
                                <?php } else { ?>
                                <option  value="" selected="TRUE">Seleccione Producto...</option>
                                <?php } 
                                  if (count($pro_servicio) > 0) {
                                    foreach ($pro_servicio as $pro):
                                        if(@$serviciotecnico->producto_servicio_factura != NULL){
                                            if($serviciotecnico->producto_servicio_factura == $pro->pro_id){ ?>
                                                 <option value="<?php  print $pro->pro_id; ?>" selected="TRUE"> <?php  print $pro->pro_nombre; ?> </option>
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $pro->pro_id; ?>" > <?php  print $pro->pro_nombre; ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $pro->pro_id; ?>" > <?php  print $pro->pro_nombre; ?> </option>
                                            <?php
                                            }   ?>
                                        <?php
                                    endforeach;
                                  }
                                ?>
                              </select> 
                            </div>


                          </div> 

                          <!--  PetShop -->
                          <div class="tab-pane" id="tabpetshop">

                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px; padding-top: 15px;">
                                <label class="col-md-12"><input type="checkbox" name="chk_petshop" id="chk_petshop" class="minimal-red" <?php if(@$habilitapetshop != NULL){ if(@$habilitapetshop == 1){ print "checked='' ";} }?> > Habilitar Gestión de PetShop</label>
                            </div> 

                          </div>  

                          <!--  Sistema -->
                          <div class="tab-pane" id="tabsistema">

                            <div class="form-group col-md-6" style="padding-left: 0px; padding-right: 0px; padding-top: 15px;">
                              <label style="padding-left: 10px; padding-right: 0px;">Color de Encabezamiento de Página</label>
                              <select id="sis_colorheader" name="sis_colorheader" class="form-control" style="padding-left: 10px; padding-right: 0px;">
                              <?php 
                                if(@$lst_colorheader != NULL){ 
                                  if (count($lst_colorheader) > 0) { 
                                    foreach ($lst_colorheader as $color):
                                        if(@$sistema->id_colorheader != NULL){
                                            if($sistema->id_colorheader == $color->id){ ?>
                                                 <option value="<?php  print $color->id; ?>" selected="TRUE"> <?php  print $color->color; ?> </option>
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $color->id; ?>" > <?php  print $color->color; ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $color->id; ?>" > <?php  print $color->color; ?> </option>
                                            <?php
                                            }   ?>
                                        <?php
                                    endforeach;
                                  }
                                 } 
                                ?>
                              </select> 
                            </div>

                            <div class="col-md-12 ">
                                <label>Imagen de Fondo</label>
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-preview thumbnail"  id="fotomostrar">
                                        <img  width="150" height="150"<?php
                                            if (@$imagenfondo != NULL) { ?>
                                               src="<?php print @$imagenfondo; ?>" <?php                                                  
                                            } else {  ?>
                                               src="<?php print base_url(); ?>public/img/home_app001.jpg" <?php
                                                }  ?> 
                                            alt="" onerror="this.src='<?php print base_url() . "public/img/home_app001.jpg"; ?>';" 
                                        />

                                    </div>
                                    <div>
                                    <br>
                                        <span class="btn btn-file btn-success">
                                            <span class="fileupload-new">Imagen</span>
                                            <span class="fileupload-exists">Cambiar</span>
                                            <input type="file"  id="imagenfondo" name="imagenfondo" accept="image/*" /> 
                                        </span>
                                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Quitar</a>
                                    </div>
                                </div>
                            </div>


                          </div>  

                         </div>  <!-- Tab Control --> 
                        </div>  <!-- Nav Tab Control --> 

                        </div>
                        <div  align="center" class="box-footer">
                            <div class="form-actions ">
                                <button type="submit" class="btn btn-success btn-grad no-margin-bottom">
                                    <i class="fa fa-save "></i> Guardar
                                </button>
                            </div>
                        </div>
                   </form> 
                </div>
            </div>

        </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

