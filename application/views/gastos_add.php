<?php //
/* ------------------------------------------------
  ARCHIVO: Gastos.php //
  DESCRIPCION: Contiene la vista principal del módulo de Gastos.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Gastos'</script>";
date_default_timezone_set("America/Guayaquil");

if(@$gastos->total != NULL){ 
  $pagar = 1; }
else{
  $pagar = 0; }

$parametro = &get_instance();
$parametro->load->model("Parametros_model");
$objiva = $parametro->Parametros_model->iva_get();
$porcientoiva = round($objiva->valor * 100,2);

?>
<style type="text/css">
  .linea{
    border-width: 2px 0 0;
    margin-bottom: 20px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 


  #tpcredito{
    display: none; 
  }

  .form-group {
      margin-bottom: 5px;
  }
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    padding-top: 2px;
    padding-bottom: 0px;    
  }

</style>
<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

  var pagar = <?php print $pagar; ?>;
  if(pagar > 0){
    $("#pagar").attr("disabled", false);
  }else{
    $("#pagar").attr("disabled", true);
  }

  


    /* MASCARA PARA COD DE FACTURA*/
  $("#factura").mask("999-999-999999999");
  $("#txt_numdocmod").mask("999-999-999999999");


  $.datepicker.setDefaults($.datepicker.regional["es"]);
  $('#fecha').datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });
  $('#fecha').on('changeDate', function(ev){
      $(this).datepicker('hide');
  });

/*
  $(document).on('change','#forpago', function(){
    var formapago = $(this).val();
    
    if(formapago == 'Contado'){
      $("#tpcredito").css("display", "none");
      $("#tpcontado").css("display", "inline");
    }else{
      $("#tpcredito").css("display", "inline");
      $("#tpcontado").css("display", "none");
    }

  }); 
*/
  /* FUNCION DESDE INPUT SUBTOTAL */  
  $(document).on('keyup','#subtotal', function(){
    actualizatotales();
  }); 

  /* FUNCION DESDE INPUT SUBTOTAL */  
  $(document).on('keyup','#subtotalivacero', function(){
    actualizatotales();
  }); 

  /* FUNCION DESDE INPUT DESCUENTO */  
  $(document).on('keyup','#descuento', function(){
    actualizatotales();
  }); 

  function actualizatotales(){
    var descuento = $('#descuento').val();
    var subtotal = $('#subtotal').val();
    var subtotaliva0 = $('#subtotalivacero').val();
    var iva = "<?php if(@$porcientoiva != NULL){ print round($porcientoiva/100,2); } else {print 0.12;}?>";/*0.12;*/
    var montoiva = 0;
    var total = 0;
    var subtotaldesc = 0;
    var subtotaldesciva0 = 0;
    if (subtotal == '') {subtotal = 0;}
    subtotal = parseFloat(subtotal);
    if (subtotaliva0 == '') {subtotaliva0 = 0;}
    subtotaliva0 = parseFloat(subtotaliva0);
    descuento = parseFloat(descuento);
    if ((subtotal + subtotaliva0) > 0){
      if(descuento > 0){ 
        subtotaldesc = (subtotal - descuento * subtotal / (subtotal + subtotaliva0)).toFixed(2); 
        subtotaldesc = subtotaldesc * 1;
        subtotaldesciva0 = (subtotaliva0 - descuento * subtotaliva0 / (subtotal + subtotaliva0)).toFixed(2); 
        subtotaldesciva0 = subtotaldesciva0 * 1;
      }
      else{ 
        subtotaldesc = subtotal; 
        subtotaldesciva0 = subtotaliva0; 
      }
    }  
    if (subtotaldesc > 0) { 
      montoiva = subtotaldesc * iva; 
      total = subtotaldesc + subtotaldesciva0 + montoiva; 
    }
    else{ 
      montoiva = 0; 
      total = subtotaldesc + subtotaldesciva0; 
    }
    total = total.toFixed(2); 
    if(total == 'NaN') { total = 0; }
    if(total > 0){
      $("#pagar").attr("disabled", false);
    }else{
      $("#pagar").attr("disabled", true);
    }

    montoiva = montoiva.toFixed(2); 
    if(montoiva == 'NaN') { montoiva = 0; }
    subtotaldesc = subtotaldesc.toFixed(2); 
    subtotaldesciva0 = subtotaldesciva0.toFixed(2); 
    if(subtotaldesc == 'NaN') { subtotaldesc = 0; }
    $('#subtotaldesc').val(subtotaldesc);
    $('#subtotalivacerodesc').val(subtotaldesciva0);
    $('#montoiva').val(montoiva);
    $('#total').val(total);

  }

  /* FUNCION DESDE CHECK IVA */  
  $(document).on('click','#iva', function(){
    var descuento = $('#descuento').val();
    var subtotal = $('#subtotal').val();
    var iva = 0.12;
    var montoiva = 0;
    var total = 0;
    var subtotaldesc = 0;
    subtotal = parseFloat(subtotal);
    descuento = parseFloat(descuento);
    if(descuento > 0){ subtotaldesc = subtotal - descuento; }
    else{ subtotaldesc = subtotal; }
    if(this.checked) { montoiva = subtotaldesc * iva; total = subtotaldesc + montoiva; }
    else{ montoiva = 0; total = subtotaldesc; }
    total = total.toFixed(2); if(total == 'NaN') { total = 0; }
    montoiva = montoiva.toFixed(2); if(montoiva == 'NaN') { montoiva = 0; }
    subtotaldesc = subtotaldesc.toFixed(2); if(subtotaldesc == 'NaN') { subtotaldesc = 0; }
    $('#subtotaldesc').val(subtotaldesc);
    $('#montoiva').val(montoiva);
    $('#total').val(total);
  }); 


    /* CALCULO DE MONTOS 
    $(document).on('keyup','#efectivo', function(){
      var efectivo = $(this).val();
      efectivo = parseFloat(efectivo);
      var tarjeta = 0;

      var total = 0;
      var vuelto = 0;
      var cambio = $('#total').val();
      cambio = cambio.replace('$ ','');
      cambio = cambio.replace(',','.');
      cambio = parseFloat(cambio);
      total = efectivo + tarjeta;
      vuelto = total - cambio;
      total = total.toFixed(2);
      vuelto = vuelto.toFixed(2);
      if(total == 'NaN') { total = 0; }
      if(vuelto == 'NaN') { vuelto = 0; }
      if(vuelto < 0){
        $('#cambio').html('<span style="color: red;"><strong>$ 0.00</strong></span>');
        $("#pagar").attr("disabled", true);       
      }else{
        $('#cambio').html('<span style="color: green;"><strong>$ '+vuelto+'</strong></span>');
        $("#pagar").attr("disabled", false);
      }
      $('#totalfp').html('<strong>$ '+total+'</strong>');
    });    
*/
    /*
    $(document).on('keyup','#tarjeta', function(){
      var tarjeta = 0;
      var efectivo = 0;
      var total = 0;
      var vuelto = 0; 
      var tarjeta = $(this).val();
      tarjeta = parseFloat(tarjeta);
      var efectivo = $('#efectivo').val();
      efectivo = parseFloat(efectivo);
      var cambio = $('#total').val();
      cambio = cambio.replace('$ ','');
      cambio = cambio.replace(',','.');
      cambio = parseFloat(cambio);
      total = efectivo + tarjeta;
      vuelto = total - cambio;
      total = total.toFixed(2);
      vuelto = vuelto.toFixed(2);
      if(total == 'NaN') { total = 0; }
      if(vuelto == 'NaN') { vuelto = 0; }
      if(vuelto < 0){
        $('#cambio').html('<span style="color: red;"><strong>$ 0.00</strong></span>');
        $("#pagar").attr("disabled", true);       
      }else{
        $('#cambio').html('<span style="color: green;"><strong>$ '+vuelto+'</strong></span>');
        $("#pagar").attr("disabled", false);
      }
      $('#totalfp').html('<strong>$ '+total+'</strong>');
    });   
    */ 

    $(document).on('keyup','#cre_dias', function(){
      var dias = $(this).val();
      dias = parseFloat(dias);        
      if(dias > 0){ 
        $("#pagar").attr("disabled", false);       
      }else{ 
        $("#pagar").attr("disabled", true);
      }      
    }); 

  $(document).on('click','#pagar', function(){
    var caja = <?php print $caja; ?>;
    var resu = 0;

    var tipodoc = $('#cmb_tipodoc').val();
    if ((tipodoc == '') || (tipodoc == 0)){
      alert("Seleccione el tipo de documento."); 
      return false;
    }
    var sustributario = $('#cmb_sustributario').val();
    if ((sustributario == '') || (sustributario == 0)){
      alert("Seleccione el Sustento Tributario."); 
      return false;
    }

    var factura = $("#factura").val();
    if(factura == ''){ alert("Ingrese el número de factura"); return false; }
    var autorizacion = $("#autorizacion").val();
    if(autorizacion == ''){ alert("Ingrese la autorización de factura"); return false; }

    if(tipodoc == '04' || tipodoc == '05'){ 
      var tipodocmod = $("#cmb_tipodocmod option:selected").val();
      if(tipodocmod == 0){ alert("Especifique el tipo de documento modificado"); return false; }
      var numdocmod = $('#txt_numdocmod').val()
      if(numdocmod == ''){ alert("Ingrese el número de documento modificado"); return false; }
      var autodocmod = $('#txt_autodocmod').val()
      if(autodocmod == ''){ alert("Ingrese la autorización de documento modificado"); return false; }
    }

    var fecha = $('#fecha').val();    
    var sucursal = $('#cmb_sucursal').val();
    var proveedor = $('#cmb_provee').val();
    var categoria = $('#cmb_catgastos').val();
    var factura = $('#factura').val();
    var autorizacion = $('#autorizacion').val();
    var descripcion = $('#descripcion').val();
    var idgastos = $('#txt_idgasto').val();
    var formapago = 'Contado';
    var efectivo = $('#total').val();
    var tarjeta = 0;
    /*var cambio = $('#cambio').text();
    cambio = cambio.replace('$ ','');
    cambio = cambio.replace(',','.');
    cambio = parseFloat(cambio);    */
    var cambio = 0;    
    /*var dias = $('#cre_dias').val();*/
    var dias = 0;
    var subtotal = $('#subtotal').val();
    subtotal = subtotal.replace('$ ','');
    subtotal = subtotal.replace(',','.');
    subtotal = parseFloat(subtotal);    
    var subtotalivacero = $('#subtotalivacero').val();
    subtotalivacero = subtotalivacero.replace('$ ','');
    subtotalivacero = subtotalivacero.replace(',','.');
    subtotalivacero = parseFloat(subtotalivacero);    
    var descuento = $('#descuento').val();
    descuento = descuento.replace('$ ','');
    descuento = descuento.replace(',','.');
    descuento = parseFloat(descuento);    
    var subtotaldesc = $('#subtotaldesc').val();
    var subtotalivacerodesc = $('#subtotalivacerodesc').val();
    var iva = 0;    
    if($('#iva').prop('checked')) { iva = 1; } else { iva = 0; }
    var montoiva = $('#montoiva').val();
    var total = $('#total').val();
    resu = caja - total;
    // alert(proveedor+" - "+fecha+" - "+factura+" - "+autorizacion+" - "+categoria+" - "+descripcion+" - "+total);

   if(proveedor == '' || fecha == '' || factura == '' || categoria == '' || descripcion == '' || total == '0,00'){
      alert("Faltan datos para la factura");
    }else{
       if(resu >= 0){
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('gastos/pagar');?>",
          data: { fecha: fecha, proveedor: proveedor, factura: factura, autorizacion: autorizacion, descripcion: descripcion, formapago: formapago, efectivo: efectivo, tarjeta: tarjeta, cambio: cambio, dias: dias, 
            subtotal: subtotal, subtotalivacero: subtotalivacero, descuento: descuento, 
            subtotaldesc: subtotaldesc, subtotalivacerodesc: subtotalivacerodesc,
            iva: iva, montoiva: montoiva, total: total, categoria: categoria, idgastos: idgastos, 
            tipodoc: tipodoc, sustributario: sustributario, sucursal: sucursal,
            tipodocmod: tipodocmod, numdocmod: numdocmod, autodocmod: autodocmod
            },
          success: function(json) {
            contabilizar = 0;
            if (json.contabilizar != '') { contabilizar = json.contabilizar; }
            if (contabilizar == 1){
              nuevoid = 0;
              if (json.nuevoid != '') { nuevoid = json.nuevoid; }
              contabilizar_gasto(nuevoid);
            }
            else{
              location.replace("<?php print $base_url;?>gastos");
            }
          }
        });
       }else{
          alert("La Caja Chica no Dispone de Capital para Ejecutar este Gasto");
       } 
      
    }
  });

  function contabilizar_gasto(id){
      var sucursal = $('#cmb_sucursal').val();

      $.ajax({
          type: "POST",
          dataType: "json",
          data: {sucursal: sucursal },
          url: base_url + "contabilidad/contab_comprobante/cuentas_configuradas_gastosucursal",
          success: function(json) {
              if (json == false){
                   alert( "Revise las categorias contables de Gasto. Faltan cuentas por configurar." );
                   location.replace("<?php print $base_url;?>gastos");
              }
              else{
                  $.ajax({
                      type: "POST",
                      dataType: "json",
                      data: {id: id },
                      url: base_url + "contabilidad/contab_comprobante/ins_comprobante_gasto",
                      success: function(json) {
                        contabilizar_pago(id);
                      }
                  });
              }
          }    
      });
  }

  function contabilizar_pago(id){
      var sucursal = $('#cmb_sucursal').val();
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {sucursal: sucursal },
          url: base_url + "contabilidad/contab_comprobante/cuentas_configuradas_pagosucursal",
          success: function(json) {
              if (json == false){
                   alert( "Revise las categorias contables de Pagos. Faltan cuentas por configurar." );
                   location.replace("<?php print $base_url;?>gastos");
              }
              else{
                  $.ajax({
                      type: "POST",
                      dataType: "json",
                      data: {id: id },
                      url: base_url + "contabilidad/contab_comprobante/ins_comprobante_pagodocgasto",
                      success: function(json) {
                        location.replace("<?php print $base_url;?>gastos");
                      }
                  });
              }
          }    
      });
  }


  $(document).on('change','#cmb_sucursal', function(){
    var sucursal = $('#cmb_sucursal').val();
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "<?php echo base_url('gastos/upd_gastos_sucursal');?>",
      data: { sucursal: sucursal},
      success: function(json) {
        var dispo = parseFloat(json);
        $("#dispocaja").html("Disponibilidad de Caja Chica: " + dispo.toFixed(2));
      }
    });
  });

  function actualizaDivmodificado(codtipodoc){
    if (codtipodoc == '04' || codtipodoc == '05'){
      $('.documento_modificado').show()
    }
    else{
      $('.documento_modificado').hide()
    }     
  }

  $(document).on('change','#cmb_tipodoc', function(){
    var codtipodoc = $("#cmb_tipodoc option:selected").val();
    actualizaDivmodificado(codtipodoc)
  });   

  tmptipodoc = $("#cmb_tipodoc option:selected").val()
  actualizaDivmodificado(tmptipodoc)

  }); 

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
      <div class="col-md-3" style="padding: 0px;">
        <h1 style="margin-top: 0px; margin-bottom: 0px;">
          <i class="fa fa-money"></i> Gastos
        </h1>        
      </div>
      <div class="col-md-4" style="padding: 0px;">
        <h4 style="margin-top: 8px; margin-bottom: 0px;" id="dispocaja">
          Disponibilidad de Caja Chica: <?php print @$caja; ?>
        </h4>        
      </div>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>gastos">Gastos</a></li>
        
      </ol>
    </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border" style="padding-bottom: 0px;">  
            <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                if(@$gastos != NULL){ ?>
                    <input type="hidden" id="txt_idgasto" name="txt_idgasto" value="<?php if($gastos != NULL){ print @$gastos->id_gastos; }?>" >    
                <?php } else { ?>
                    <input type="hidden" id="txt_idgasto" name="txt_idgasto" value="0">    
            <?php } ?>           


            <!-- SUCURSAL  -->
            <div class="form-group col-md-2">
              <label for="lb_res">Sucursal</label>
              <select id="cmb_sucursal" name="cmb_sucursal" class="form-control">
              <?php 
                if(@$sucursales != NULL){ ?>
                <?php } else { ?>
                <option  value="" selected="TRUE">Seleccione Sucursal...</option>
                <?php } 
                  if (count($sucursales) > 0) {
                    foreach ($sucursales as $obj):
                        if(@$tmpcomp->id_sucursal != NULL){
                            if($obj->id_sucursal == $tmpcomp->id_sucursal){ ?>
                                 <option value="<?php  print $obj->id_sucursal; ?>" selected="TRUE"> <?php  print $obj->nom_sucursal; ?> </option>
                                <?php
                            }else{ ?>
                                <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                                <?php
                            }
                        }else{ ?>
                            <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                            <?php
                            }   ?>
                        <?php
                    endforeach;
                  }
                ?>
              </select>                                  
            </div>

            <!-- Proveedor -->
            <div class="form-group col-md-3">
                <label>Proveedor</label>
                <select id="cmb_provee" name="cmb_provee" class="form-control">
                    <?php 
                      if(@$provee != NULL){ ?>
                      	<option  value="" selected="TRUE">Seleccione...</option>
                    <?php } else { ?>
                        <option  value="" selected="TRUE">Seleccione...</option>
                    <?php } 
                              if (count($provee) > 0) {
                                foreach ($provee as $pr):
                                    if(@$gastos->id_proveedor != NULL){
                                        if($pr->id_proveedor == $gastos->id_proveedor){ ?>
                                            <option  value="<?php  print $pr->id_proveedor; ?>" selected="TRUE"><?php  print $pr->nom_proveedor ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $pr->id_proveedor; ?>"> <?php  print $pr->nom_proveedor ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $pr->id_proveedor; ?>"> <?php  print $pr->nom_proveedor ?> </option>
                                        <?php
                                        }   ?>
                                    <?php

                                endforeach;
                              }
                              ?>
                </select>
            </div>     

            <div style="" class="form-group col-md-3">
              <label for="lb_res">Tipo Documento</label>
              <select id="cmb_tipodoc" name="cmb_tipodoc" class="form-control">
              <?php 
                if(@$sritc != NULL){ ?>
                <option  value="0" selected="TRUE">Seleccione Tipo Documento...</option>
                <?php } 
                  if (count($sritc) > 0) {
                    foreach ($sritc as $stc):
                        if(@$tmpcomp->cod_sri_tipo_doc != NULL){
                            if($stc->cod_sri_tipo_doc == $tmpcomp->cod_sri_tipo_doc){ ?>
                                 <option value="<?php  print $stc->cod_sri_tipo_doc; ?>" selected="TRUE"> <?php  print $stc->desc_sri_tipo_doc; ?> </option>
                                <?php
                            }else{ ?>
                                <option value="<?php  print $stc->cod_sri_tipo_doc; ?>" > <?php  print $stc->desc_sri_tipo_doc; ?> </option>
                                <?php
                            }
                        }else{ ?>
                            <option value="<?php  print $stc->cod_sri_tipo_doc; ?>" > <?php  print $stc->desc_sri_tipo_doc; ?> </option>
                            <?php
                            }   ?>
                        <?php
                    endforeach;
                  }
                ?>
              </select>                                  
            </div>

            <div style="" class="form-group col-md-4">
              <label for="lb_res">Sustento Tributario</label>
              <select id="cmb_sustributario" name="cmb_sustributario" class="form-control">
              <?php 
                if(@$srist != NULL){ ?>
                <option  value="0" selected="TRUE">Seleccione Sustento Tributario...</option>
                <?php } 
                  if (count($srist) > 0) {
                    foreach ($srist as $sst):
                        if(@$srist->cod_sri_sust_comprobante != NULL){
                            if($sst->cod_sri_sust_comprobante == $tmpcomp->cod_sri_sust_comprobante){ ?>
                                 <option value="<?php  print $sst->cod_sri_sust_comprobante; ?>" selected="TRUE"> <?php  print $sst->desc_sri_sust_comprobante; ?> </option>
                                <?php
                            }else{ ?>
                                <option value="<?php  print $sst->cod_sri_sust_comprobante; ?>" > <?php  print $sst->desc_sri_sust_comprobante; ?> </option>
                                <?php
                            }
                        }else{ ?>
                            <option value="<?php  print $sst->cod_sri_sust_comprobante; ?>" > <?php  print $sst->desc_sri_sust_comprobante; ?> </option>
                            <?php
                            }   ?>
                        <?php
                    endforeach;
                  }
                ?>
              </select>                                  
            </div>



            <!-- FECHA DE FACTURA -->
            <div class="form-group col-md-3">
              <label for="">Fecha</label>
              <div style="margin-bottom: 0px;"class="form-group" >
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" class="form-control pull-right validate[required]" id="fecha" name="fecha" value="<?php if(@$gastos != NULL){ @$fec = @$gastos->fecha; @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec; }else{ print  date("d/m/Y"); }?>">
                </div>                             
              </div>
            </div>  

            <!-- NRO DE FACTURA -->
            <div class="form-group col-md-3">
              <label id="nomfact">Nro Factura</label>
              <input type="text" class="form-control validate[required] text-center" id="factura" name="factura" value="<?php  if(@$gastos != NULL){ print @$gastos->nro_factura; }?>">
            </div>
  	        <!-- AUTORIZACION -->
  	        <div class="form-group col-md-3">
  	          <label>Nro Autorización</label>
  	          <input id="autorizacion" type="text" class="form-control validate[required] text-center" id="txt_autorizacion" name="txt_autorizacion" value="<?php if(@$gastos != NULL){ print @$gastos->nro_autorización; }?>">
  	        </div>  
            <!-- CATEGORIAS -->
            <div class="form-group col-md-3">
                <label>Categoría</label>
                <select id="cmb_catgastos" name="cmb_catgastos" class="form-control">
                    <?php 
                      if(@$catgastos != NULL){ ?>
                        <option  value="" selected="TRUE">Seleccione...</option>
                    <?php } else { ?>
                        <option  value="" selected="TRUE">Seleccione...</option>
                    <?php } 
                              if (count($catgastos) > 0) {
                                foreach ($catgastos as $cg):
                                    if(@$gastos->categoria != NULL){
                                        if($cg->id_cat_gas == $gastos->categoria){ ?>
                                            <option  value="<?php  print $cg->id_cat_gas; ?>" selected="TRUE"><?php  print $cg->nom_cat_gas ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $cg->id_cat_gas; ?>"> <?php  print $cg->nom_cat_gas ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $cg->id_cat_gas; ?>"> <?php  print $cg->nom_cat_gas ?> </option>
                                        <?php
                                        }   ?>
                                    <?php

                                endforeach;
                              }
                              ?>
                </select>
            </div>     

            <!-- Datos documento modificado -->
            <div class="form-group col-md-12 documento_modificado" style="display: none; padding-left: 0px;">

              <!-- Tipo Documento Modificado -->
              <div style="" class="form-group col-md-3">
                <label for="lb_res">Tipo Documento Modificado</label>
                <select id="cmb_tipodocmod" name="cmb_tipodocmod" class="form-control upd_docmod">
                <?php 
                  if(@$sritc != NULL){ ?>
                  <option  value="0" selected="TRUE">Seleccione Tipo Documento...</option>
                  <?php } 
                    if (count($sritc) > 0) {
                      foreach ($sritc as $stc):
                          if(@$gastos->doc_mod_cod_sri_tipo != NULL){
                              if($stc->cod_sri_tipo_doc == $gastos->doc_mod_cod_sri_tipo){ ?>
                                   <option value="<?php  print $stc->cod_sri_tipo_doc; ?>" selected="TRUE"> <?php  print $stc->desc_sri_tipo_doc; ?> </option>
                                  <?php
                              }else{ ?>
                                  <option value="<?php  print $stc->cod_sri_tipo_doc; ?>" > <?php  print $stc->desc_sri_tipo_doc; ?> </option>
                                  <?php
                              }
                          }else{ ?>
                              <option value="<?php  print $stc->cod_sri_tipo_doc; ?>" > <?php  print $stc->desc_sri_tipo_doc; ?> </option>
                              <?php
                              }   ?>
                          <?php
                      endforeach;
                    }
                  ?>
                </select>                                  
              </div>

              <!-- NRO DE Documento Modificado -->
              <div class="form-group col-md-3">
                <label>Nro.Documento Modificado</label>
                <input type="text" class="form-control validate[required] upd_docmod" id="txt_numdocmod" name="txt_numdocmod" value="<?php if(@$tmpcomp != NULL){ print @$gastos->doc_mod_numero; }?>">
              </div>
              <!-- AUTORIZACION Documento Modificado-->
              <div class="form-group col-md-4">
                <label>Nro.Autorización Modificado</label>
                <input type="text" class="form-control validate[required] upd_docmod" id="txt_autodocmod" name="txt_autodocmod" value="<?php if(@$tmpcomp != NULL){ print @$gastos->doc_mod_autorizacion; }?>">
              </div>
            </div>                     

          </div>
          <div class="box-body">
            <div class="row header">
              <div class="col-sm-8">
                <div class="well col-sm-12">
    			        <div class="form-group col-md-12">
    			          <label>Descripción</label>
    			          <textarea id="descripcion" name="descripcion" class="form-control" rows="3" placeholder="Ingrese los Detalles ..."><?php if(@$gastos != NULL){ print @$gastos->descripcion; }?></textarea>
    			        </div>                       

                </div>
              </div> 

           
              <!--
                <div class="formapago well col-md-7" style="margin-left: 10px;">

                  <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                    <h4 style="margin-top: -2px; margin-bottom:10px;">Forma de Pago</h4>
                    <div class="text-center" style="">
                      <label class="radio-inline">
                        <input type="radio" id="forpago" name="optionsRadios" checked  value="Contado"> Contado
                      </label>
                      <label class="radio-inline">
                        <input type="radio" id="forpago" name="optionsRadios"  value="Credito"> Crédito
                      </label>
                    </div>
                    <hr class="linea"> 
                  </div>
                  <div class="box-body">
                    <table id="tpcontado" class="table table-bordered ">
                      <tr>
                        <th </th>
                        <th class="text-center" style="width: 30%">Monto</th>
                      </tr>
                      <tr>
                        <td></td>
                        <td class="text-right">
                          <input type="text" class="text-right" name="efectivo" id="efectivo" value="0.00" style="width:70px;" >
                        </td>
                      </tr>
                      <tr>
                        <td></td>
                        <td class="text-right">
                          <input type="text" class="text-right" name="tarjeta" id="tarjeta" value="0.00" style="width:70px;" >
                        </td>
                      </tr>
                      <tr>
                        <th class="text-right">Total</th>
                        <td id="totalfp" class="text-right"><strong>0.00</strong></td>
                      </tr>
                      <tr>
                        <th class="text-right">Cambio</th>
                        <td id="cambio" class="text-right"><strong>0.00</strong></td>
                      </tr>                      
                    </table>
                    
                    <table id="tpcredito" class="table table-bordered ">

                      <tr>
                        <th><i class="fa fa-calendar" aria-hidden="true"></i> Días</th>
                        <td class="text-right">
                          <input type="text" class="text-right" name="cre_dias" id="cre_dias" value="0" style="width:70px;" >
                        </td>
                      </tr>
                     
                    </table>
                  </div>
                </div> 
				      -->


              <div class="col-md-4">
                <div id="calmonto" class="pull-right" style="margin-right: 10px;">
                  <table class="table table-clear calmonto" >
                    <tbody>
                      <tr>
                        <td class="text-left"><strong>Subtotal IVA<?php  print @$porcientoiva; ?>%</strong></td>
                        <td>
                        	<input type="text" class="text-right" name="" id="subtotal" value="<?php  print number_format(@$gastos->subtotal,2,",","."); ?>" style="width:70px;" >                                       
                        </td>
                      <tr>
                      <tr>
                        <td class="text-left"><strong>Subtotal IVA 0%</strong></td>
                        <td>
                          <input type="text" class="text-right" name="" id="subtotalivacero" value="<?php  print number_format(@$gastos->subtotalivacero,2,",","."); ?>" style="width:70px;" >                                       
                        </td>
                      <tr>
                      <tr>
                        <td class="text-left"><strong>Descuento</strong></td>
                        <td id="" class="text-right">
                          <input type="text" class="text-right" name="" id="descuento" value="<?php  print number_format(@$gastos->descuento,2,",","."); ?>" style="width:70px;" >
                        </td>                                        
                      </tr>
                      <tr>
                        <td class="text-left"><strong>Subtotal IVA<?php  print @$porcientoiva; ?>%con Desc</strong></td>
                        <td>
                        	<input type="text" class="text-right" name="" id="subtotaldesc" disabled value="<?php  print number_format(@$gastos->subtotaldesc,2,",","."); ?>" style="width:70px;" >
                        </td>	
                      </tr>
                      <tr>
                        <td class="text-left"><strong>Subtotal IVA0% con Desc</strong></td>
                        <td>
                          <input type="text" class="text-right" name="" id="subtotalivacerodesc" disabled value="<?php  print number_format(@$gastos->subtotalivacerodesc,2,",","."); ?>" style="width:70px;" >
                        </td> 
                      </tr>
                      <tr>
                        <td class="text-left">
                          <strong>Monto IVA (<?php  print @$porcientoiva; ?>%)</strong></td>
						            </td>
                        <td>
	                        <input type="text" class="text-right" name="" id="montoiva" disabled value="<?php  print number_format(@$gastos->montoiva,2,",","."); ?>" style="width:70px;" >
                        </td>
                      </tr>
                      <tr>
                        <td class="text-left"><strong>Total</strong></td>
                        <td>
                        	<input type="text" class="text-right" name="total" id="total" disabled value="<?php  print number_format(@$gastos->total,2,",","."); ?>" style="width:70px;" >
                        </td>
                      </tr>      
                    </tbody>
                  </table>
                </div>                
              </div>
            
            </div>

           
          </div>  
          <div class="box-footer text-right">
            <div class="row no-print ">
            <div class="col-md-12">
              <!--
              <button id="imprimir" type="button" class="btn btn-primary btn-grad" >
                <i class="fa fa-print"></i> Imprimir
              </button>   
              -->
              <button id="pagar" type="button" class="btn btn-success btn-grad" >
                <i class="fa fa-credit-card"></i> Guardar
              </button>
            </div>
            
            </div>
          </div>

        </div>        
      </div>



    </div>
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->
