<?php
/* ------------------------------------------------
  ARCHIVO: Compra.php
  DESCRIPCION: Contiene la vista principal del módulo de Compra.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Compra'</script>";
date_default_timezone_set("America/Guayaquil");

  $parametro = &get_instance();
  $parametro->load->model("Parametros_model");

  $habserie = $parametro->Parametros_model->sel_numeroserie();
  $habilitaserie = $habserie->valor;

  $es_modificacion = ($modificacion != NULL) ? $modificacion : 0;
?>
<style type="text/css">
  .form-control{
    font-size: 12px;
    height: 28px;
  }

  .table > tbody > tr > td{
    padding-bottom: 0px;
    padding-top: 1px;
  }

  .form-group {
      margin-bottom: 5px;
  }

  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 

  .pago{
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05) inset;
    margin-bottom: 20px;
    min-height: 20px;
    padding: 19px;  
    margin-left: 20px;  
  }

  .calmonto{
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05) inset;
    margin-bottom: 20px;
    min-height: 20px;
    padding: 19px;  
    margin-right: 20px;     
  }

  #tpcredito{
    display: none; 
  }  

  .tdprecio{
    width: 80px;
  }    

  a.disabled {
      pointer-events: none;
      color: #ccc;
  }

</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

    var es_modificacion = <?php print $es_modificacion; ?>;

    /* FECHA */
    $('#fecha').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
    $("#fecha").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });

    /* FECHA Limite Credito*/
    $('#fechaplazo').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
    $("#fechaplazo").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });


    /* ACTUALIZA LA FACTURA */
    $(document).on('change','#fechaplazo', function(){
      var fechaplazo = $(this).val();
      var plazo = fechaplazo.split("/");
      var fplazo = Date.parse(plazo[2] + '-' +  plazo[1] + '-' + plazo[0]);
      
      var fecha = $("#fecha").val();
      var ini = fecha.split("/");
      var fini = Date.parse(ini[2] + '-' +  ini[1] + '-' + ini[0]);
      
      var diaEnMils = 1000 * 60 * 60 * 24;
      dias = (fplazo - fini) / diaEnMils;

      $("#cre_dias").val(dias);
    });    

    /* MASCARA PARA COD DE FACTURA*/
    $("#factura").mask("999-999-999999999");
    $("#txt_numdocmod").mask("999-999-999999999");

/* ==== GUARDAR DATOS DE CABECERA ======================================*/
    /* ACTUALIZA EL PROVEEDOR */
    $(document).on('change','#cmb_proveedor', function(){
      var idproveedor = $("#cmb_proveedor option:selected").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_proveedor');?>",
        data: { idproveedor: idproveedor, },
        success: function(json) {

        }
      });
      return false;

    });   

    $(document).on('change','.upd_docmod', function(){
      var tipodoc = $("#cmb_tipodocmod option:selected").val()
      var numdocmod = $('#txt_numdocmod').val()
      var autodocmod = $('#txt_autodocmod').val()
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_documento_modificado');?>",
        data: { tipodoc: tipodoc, numdocmod: numdocmod, autodocmod: autodocmod },
        success: function(json) {

        }
      });
      return false;

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
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_tipodoc');?>",
        data: { codtipodoc: codtipodoc, },
        success: function(json) {

        }
      });
      return false;

    });   


    $(document).on('change','#cmb_sustributario', function(){
      var codsustributario = $("#cmb_sustributario option:selected").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_sustributario');?>",
        data: { codsustributario: codsustributario, },
        success: function(json) {

        }
      });
      return false;

    });   










    /* ACTUALIZA LA FACTURA */
    $(document).on('change','#factura', function(){
      var factura = $(this).val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_factura');?>",
        data: { factura: factura, },
        success: function(json) {

        }
      });
      return false;

    });    

    /* ACTUALIZA NRO DE AUTORIZACION */
    $(document).on('change','#autorizacion', function(){
      var autorizacion = $(this).val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_autorizacion');?>",
        data: { autorizacion: autorizacion, },
        success: function(json) {

        }
      });
      return false;

    });   

    /* ACTUALIZA ALMACEN */
    $(document).on('change','#cmb_almacen', function(){
      var almacen = $(this).val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_almacen');?>",
        data: { almacen: almacen, },
        success: function(json) {
        }
      });
      return false;
    });   

    /* ACTUALIZA Sucursal */
    $(document).on('change','#cmb_sucursal', function(){
      var sucursal = $(this).val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_sucursal');?>",
        data: { sucursal: sucursal },
        success: function(json) {
            var strhtml = "<select id='cmb_almacen' name='cmb_almacen' class='form-control'>";
            if (json.almacenes){
              json.almacenes.forEach(function(almacen) { 
                strhtml += "<option value='" + almacen.almacen_id + "'> "+ almacen.almacen_nombre + " </option>";
              });
            }

            strhtml += "</select>";
            $("#cmb_almacen").html(strhtml);

          //location.reload();
        }
      });
      return false;
    });   

    /* ACTUALIZA CATEGORIA */
    $(document).on('change','#cmb_catgastos', function(){
      var categoria = $(this).val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_categoria');?>",
        data: { categoria: categoria, },
        success: function(json) {
        }
      });
      return false;
    });   


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

    $(document).on('keyup','#efectivo', function(){
      var efectivo = $(this).val();
      efectivo = parseFloat(efectivo);
      var tarjeta = 0;
    //  tarjeta = parseFloat(tarjeta);
      var total = 0;
      var vuelto = 0;
      var cambio = $('#mtotal').text();
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

    $(document).on('keyup','#cre_dias', function(){
      var dias = $(this).val();
      dias = parseFloat(dias);        
      if(dias > 0){ 
        $("#pagar").attr("disabled", false);       
      }else{ 
        $("#pagar").attr("disabled", true);
      }      
    }); 

/* =========================================================================*/
    /* AGREGAR PRODUCTO */
    $(document).on('click', '.add_procompra', function(){
      var almacen = $('#cmb_almacen').val();
      if ((almacen == '') || (almacen == 0)){
        alert("Seleccione el almacen de destino.");  
      } else {
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('compra/add_procompra');?>" 
        });
      }
    });

/* === GUARDAR EL PRECIO EN LA TABLA TEMPORAL Y REALIZAR CALCULO === */
    $(document).on('change','.precio', function(){
      /* Inicializacion de las variables */
      var cantidad = 0;
      var precio = 0;
      var subtotal = 0;
      var iva = 0.12;
      var valiva = 0;
      var subiva = 0;      
      var desc = 0;
      /* captura de datos */
      id = $(this).attr("id");
      cantidad = $('.cantidad[id='+id+']').val();
      cantidad = cantidad.replace(',','');
      precio = $(this).val();
      precio = precio.replace(',','');
      desc = $('#descuento').val();
      desc = desc.replace(",", ".");
      subval = cantidad * precio;
      if( $('.chkiva[id='+id+']').prop('checked') ) {
        valiva = subval * iva;
        subiva = subval + valiva;        
      }else{
        subiva = subval;
        valiva = 0;
      }
      subtotal = subval.toFixed(2);
      $('div[id='+id+']').html(subtotal);

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_preciocompra');?>",
        data: { id: id, precio: precio, montoiva: valiva, subtotal: subtotal },
        success: function(json) {
          aplica_descuento();
          $('#detcompra').load(base_url + "compra/actualiza_tabla_compra");
        }
      });
    });

    /* GUARDAR LA CANTIDAD EN LA TABLA TEMPORAL Y REALIZAR CALCULO */
    $(document).on('change','.cantidad', function(){
      var cantidad = 0;
      var precio = 0;
      var subtotal = 0;
      var iva = 0.12;
      var valiva = 0;
      var subiva = 0;
      var desc = 0;
      id = $(this).attr("id");
      cantidad = $(this).val();
      cantidad = cantidad.replace(',','');
      precio = $('.precio[id='+id+']').val();
      precio = precio.replace(',','');
      desc = $('.descuento').val();
      subval = cantidad * precio;

      if( $('.chkiva[id='+id+']').prop('checked') ) {
        valiva = subval * iva;
        subiva = subval + valiva;        
      }else{
        subiva = subval;
        valiva = 0;
      }

      subtotal = subval.toFixed(2);

      /* ACTUALIZA PRECIO */
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_cantidad');?>",
        data: { id: id, cantidad: cantidad,  montoiva: valiva, subtotal: subtotal },
        success: function(json) {
          aplica_descuento();
          $('#detcompra').load(base_url + "compra/actualiza_tabla_compra");
        }
      });
    });

    /* GUARDAR LA UNIDAD MEDIDA EN LA TABLA TEMPORAL */
    $(document).on('change','.unidadmedida', function(){
      id = $(this).attr("id");
      unidadmedida = $(this).val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_unidadmedida');?>",
        data: { id: id, unidadmedida: unidadmedida },
        success: function(json) {
        }
      });
    });

    /* CALCULA EL SUBTOTAL CON EL IVA */
    $(document).on('click','.chkiva', function(){
      var cantidad = 0;
      var precio = 0;
      var subtotal = 0;
      var iva = 0.12;
      var valiva = 0;
      var subiva = 0;
      var esiva = 0;
      id = $(this).attr("id");
      precio = $('.precio[id='+id+']').val();
      precio = precio.replace(',','');
      cantidad = $('.cantidad[id='+id+']').val();
      cantidad = cantidad.replace(',','');
      subval = cantidad * precio;

      if (this.checked) {
        esiva = 1;
        valiva = subval * iva;
        subiva = subval + valiva;
      }else{
        subiva = subval;
        esiva = 0;
        valiva = 0;
      }      
      
      subtotal = subval.toFixed(2);
      /* ACTUALIZA PRECIO */
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_iva');?>",
        data: { id: id, esiva: esiva,  montoiva: valiva, subtotal: subtotal },
        success: function(json) {
          aplica_descuento();
          $('#detcompra').load(base_url + "compra/actualiza_tabla_compra");
        }
      });
    });

/* === MODIFICAR SUBTOTAL Y PRECIO EN LA TABLA TEMPORAL Y REALIZAR CALCULO === */
    $(document).on('change','.subtotaledit', function(){
      /* Inicializacion de las variables */
      var cantidad = 0;
      var precio = 0;
      var subtotal = 0;
      var iva = 0.12;
      var valiva = 0;
      var subiva = 0;      
      var desc = 0;
      /* captura de datos */
      id = $(this).attr("id");
      cantidad = $('.cantidad[id='+id+']').val();
      cantidad = cantidad.replace(',','');
      subval = $(this).val();
      subval = subval.replace(',','');
      desc = $('#descuento').val();
      desc = desc.replace(",", ".");
      subval=parseFloat(subval);
      if (cantidad > 0){
        precio = subval / cantidad;
      }
      precio = precio.toFixed(6);
      if( $('.chkiva[id='+id+']').prop('checked') ) {
        valiva = subval * iva;
        subiva = subval + valiva;        
      }else{
        subiva = subval;
        valiva = 0;
      }
      subtotal = subval.toFixed(2);

      $('div[id='+id+']').html(subtotal);

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_preciocompra');?>",
        data: { id: id, precio: precio, montoiva: valiva, subtotal: subtotal },
        success: function(json) {
          aplica_descuento();
          $('#detcompra').load(base_url + "compra/actualiza_tabla_compra");
        }
      });
    });

  /* PROCESO DE DESCUENTO */
  $(document).on('keyup','#descuento,#montoice', function(){
    var descuento = $("#descuento").val();
    descuento = descuento.replace(',','');
    if (descuento == '') {descuento = 0;}
    var montoice = $("#montoice").val();
    montoice = montoice.replace(',','');
    if (montoice == '') {montoice = 0;}
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('compra/upd_descuento');?>",
        data: { descuento: descuento, montoice: montoice },
        success: function(json) {
          aplica_descuento();
          $('#detcompra').load(base_url + "compra/actualiza_tabla_compra");
        }
      });
    
  });


  /* GUADAR COMPRA */
  $(document).on('click','#guardar_compra', function(){

    if (es_modificacion == 1) { 
      modificar_factura() 
      return false
    }

    if (!confirm('Desea guardar la compra?')){
      return false
    }  

    var tipodoc = $("#cmb_tipodoc option:selected").val();
    var sustributario = $("#cmb_sustributario option:selected").val();

    if(tipodoc == 0){ alert("Especifique el Tipo de Documento"); return false; }
    if(sustributario == 0){ alert("Seleccione el Sustento Tributario"); return false; }

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

    var idproveedor = $("#cmb_proveedor option:selected").val();
    var fecha = $("#fecha").val();
    var factura = $("#factura").val();
    var autorizacion = $("#autorizacion").val();
    var forpago = $('input[id="forpago"]:checked').val();
    var stciva = $('#msubtotalconiva').text(); //Subtotal con Iva
    var stsiva = $('#msubtotalsiniva').text(); //Subtotal sin Iva
    var desc = $('#descuento').val(); //Subtotal sin Iva
    var stdciva = $('#descsubiva').text(); //Subtotal Descuento con Iva
    var stdsiva = $('#descsub').text(); //Subtotal Descuento sin Iva
    var miva = $('#miva').text(); //Subtotal Descuento sin Iva
    var mtotal = $('#mtotal').text(); //Subtotal Descuento sin Iva
    mtotal = mtotal.replace('$ ','');
    mtotal = mtotal.replace(',','.');
    mtotal = parseFloat(mtotal);     

    var categoria = $('#cmb_catgastos').val();
    var almacen = $('#cmb_almacen').val();

    if( $('#cajachica').prop('checked') ) {
      cajachica = 1;
    }else{
      cajachica = 0;
    }

    var formapago = $('input[id="forpago"]:checked').val();
    var efectivo = $('#efectivo').val();
    var tarjeta = 0;
    var cambio = $('#cambio').text();
    cambio = cambio.replace('$ ','');
    cambio = cambio.replace(',','.');
    cambio = parseFloat(cambio);    
    var dias = $('#cre_dias').val();    
    var fechaplazo = $("#fechaplazo").val();

    if(idproveedor == '' || fecha == '' || factura == '' || autorizacion == '' || categoria == 0 || almacen == 0 || forpago == '' || mtotal == '$ 0,00'){
      alert("Faltan datos para la factura.");
    }else{
     // alert("facturar");
      guardar(fecha, formapago, efectivo, tarjeta, cambio, dias, cajachica, categoria, mtotal, almacen);
    }


    return false;


  }); 

  function guardar(fecha, formapago, efectivo, tarjeta, cambio, dias, cajachica, categoria, mtotal, almacen){
    var caja = <?php print @$caja; ?>;
    var resu = caja - mtotal;

    if ((formapago == 'Contado') && (resu < 0)){
      alert("La Caja Chica no Dispone de Capital para Ejecutar esta Compra");
    }
    else{
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('compra/add_compra');?>",
          data: { fecha: fecha, formapago: formapago, efectivo: efectivo, tarjeta: tarjeta, cambio: cambio, 
                  dias: dias, cajachica: cajachica, categoria: categoria, almacen: almacen },
          success: function(json) {
            contabilizar = 0;
            if (json.contabilizar != '') { contabilizar = json.contabilizar; }
            if (contabilizar == 1){
              nuevoid = 0;
              if (json.nuevoid != '') { nuevoid = json.nuevoid; }
              contabilizar_compra(nuevoid, formapago);
            }
            else{
              redireccion("compra");
            }
          }
        });
    }
  
  }

    function redireccion(contr, meth) {
        location.replace(base_url + contr + (meth ? "/" + meth : ""));
    }

  function contabilizar_compra(id, formapago){
      var sucursal = $('#cmb_sucursal').val();

      $.ajax({
          type: "POST",
          dataType: "json",
          data: {sucursal: sucursal },
          url: base_url + "contabilidad/contab_comprobante/cuentas_configuradas_comprasucursal",
          success: function(json) {
              if (json == false){
                   alert( "Revise las categorias contables de Compra. Faltan cuentas por configurar." );
                   redireccion("compra");
              }
              else{
                  $.ajax({
                      type: "POST",
                      dataType: "json",
                      data: {id: id },
                      url: base_url + "contabilidad/contab_comprobante/ins_comprobante_compra",
                      success: function(json) {
                        if(formapago == 'Contado'){ 
                          contabilizar_pago(id);
                        }  
                        else{
                          redireccion("compra");
                        }
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
                   redireccion("compra");
              }
              else{
                  $.ajax({
                      type: "POST",
                      dataType: "json",
                      data: {id: id },
                      url: base_url + "contabilidad/contab_comprobante/ins_comprobante_pagodoccompra",
                      success: function(json) {
                        redireccion("compra");
                      }
                  });
              }
          }    
      });
  }

    /* ELIMINAR TODOS LOS PRODUCTOS */
    $(document).on('click', '.del_compra', function(){  
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "compra/elim_compra",
        success: function(json) {
          aplica_descuento();
          $('#detcompra').load(base_url + "compra/actualiza_tabla_compra");
          $('#descuento').val(0);
        }
      });
    });  

    /* FUNCIONES PARA EL BOTON AÑADIR NOTA A LA COMPRA */
    $(document).on('click','.addnota', function(){
      var id = $(this).attr('id');
    //  alert(id);
      //var idped = $(this).attr('name');
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        ajax: {
           dataType: "html",
           type: "POST",
           data: {id: id},
        },
        href: "<?php echo base_url('compra/nota_compra');?>",
        afterClose: function(){
          $.ajax({
            type: "POST",
            dataType: "json",
            data: {id: id },
            url: base_url + "compra/actualiza_cantidad_serie",
            success: function(json) {
              $('#detcompra').load(base_url + "compra/actualiza_tabla_compra");
              aplica_descuento();
            }
          });
        }
      })
    });



    function aplica_descuento(){

        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "compra/desc_compra",
            success: function(json) {

              stciva = parseFloat(json.res.subconiva); // Subtotal con Iva 
              stsiva = parseFloat(json.res.subsiniva); // Subtotal Sin Iva 
              tsdesc = parseFloat(json.res.total); // Total Sin Descuento 

              miva = parseFloat(json.res.montoiva); // Monto IVA 

              dstciva = parseFloat(json.res.descsubconiva); // Subtotal con Iva 
              dstsiva = parseFloat(json.res.descsubsiniva); // Subtotal Sin Iva 
              tcdesc  = parseFloat(json.res.totaldesc); // Total Sin Descuento     

              ttotal = parseFloat(json.res.ttotal); // Total Sin Descuento       

              valstciva = stciva.toFixed(2);
              valstsiva = stsiva.toFixed(2);
              valtsdesc = tsdesc.toFixed(2);

              valdstciva = dstciva.toFixed(2);
              valdstsiva = dstsiva.toFixed(2);
              valtcdesc  = tcdesc.toFixed(2);

              montoviva  = miva.toFixed(2);
              montototal  = ttotal.toFixed(2);


              if(valstciva == 'NaN') { valstciva = 0; }
              if(valstsiva == 'NaN') { valstsiva = 0; }
              if(valtsdesc == 'NaN') { valtsdesc = 0; }
              if(valdstciva == 'NaN') { valdstciva = 0; }
              if(valdstsiva == 'NaN') { valdstsiva = 0; }
              if(valtcdesc == 'NaN') { valtcdesc = 0; }
              if(montoviva == 'NaN') { montoviva = 0; }

            //  if(montototal == 'NaN') { montototal = '0,00'; }
            //  if(montototal == 0) { montototal = '0,00'; }
            
              if(montototal == 'NaN') { montototal = 0; }

                $('#msubtotalconiva').html('$ '+valstciva);
                $('#msubtotalsiniva').html('$ '+valstsiva);
                
                $('#descsubiva').html('$ '+valdstciva);
                $('#descsub').html('$ '+valdstsiva);

                $('#mtotal').html('$ '+montototal);

              
              $('#miva').html('$ '+montoviva);

            }
        });
      //return false;
    }

    tmpcant = $('#cmb_sucursal > option').length;
    if (tmpcant <= 1){
      $('#cmb_sucursal').attr('disabled', true);
    } else{
      $('#cmb_sucursal').attr('disabled', false);
    }

    $(document).on('click', '.addserie', function(){ 
        var imei = $('#imei').val();
        var desc = $('#txt_desc').val();
        var idcom = $('#txt_idcom').val();
        var iddet = $('#txt_iddet').val();  
        var idprodet = $('#txt_idprodet').val();

        if(imei == ""){
            alert("Verifique el IMEI o Serie");
            return false;
        }
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "compra/guardaimeiserie",
            data: { imei: imei, desc: desc, idcom: idcom, iddet: iddet, idprodet: idprodet },
            success: function(json) {
                if(json == 1){
                  alert("Ya existe un producto con este registro"); 
                  return false;
                }
                if(json == 0){
                  $('.detserie').load(base_url + "compra/actualiza_imeiserie");
                  $('#imei').val('');
                  $('#txt_desc').val('');
                }
            }
        }); 
    }); 

    $(document).on('click', '.proser_del', function(){ 
        var id = $(this).attr('id');
        var idprodet = $('#txt_idprodet').val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "compra/eliminaimeiserie",
            data: { id: id, idprodet: idprodet },
            success: function(json) {
              $('.detserie').load(base_url + "compra/actualiza_imeiserie");
            }
        }); 
    }); 

  function modificar_factura(){  

    if (!confirm('Desea modificar la compra?')){
      return false
    }  

    var tipodoc = $("#cmb_tipodoc option:selected").val();
    var sustributario = $("#cmb_sustributario option:selected").val();

    if(tipodoc == 0){ alert("Especifique el Tipo de Documento"); return false; }
    if(sustributario == 0){ alert("Seleccione el Sustento Tributario"); return false; }

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

    var idproveedor = $("#cmb_proveedor option:selected").val();
    var fecha = $("#fecha").val();
    var factura = $("#factura").val();
    var autorizacion = $("#autorizacion").val();
    var categoria = $('#cmb_catgastos').val();

    //var dias = $('#cre_dias').val();    
    //var fechaplazo = $("#fechaplazo").val();

    if(idproveedor == '' || fecha == '' || factura == '' || autorizacion == '' || categoria == 0 ){
      alert("Faltan datos para la factura.");
    }else{
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('compra/modificar_compra');?>",
          data: { fecha: fecha },
          success: function(json) {
              redireccion("compra");
/*            contabilizar = 0;
            if (json.contabilizar != '') { contabilizar = json.contabilizar; }
            if (contabilizar == 1){
              nuevoid = 0;
              if (json.nuevoid != '') { nuevoid = json.nuevoid; }
              contabilizar_compra(nuevoid, formapago);
            }
            else{
              redireccion("compra");
            }*/
          }
        });

    }
  }  

    tmptipodoc = $("#cmb_tipodoc option:selected").val()
    actualizaDivmodificado(tmptipodoc)
    if (es_modificacion == 1){
      $('#guardar_compra').html('Modificar')
      $('#cmb_sucursal').attr('disabled', true)
      $('#cmb_almacen').attr('disabled', true)
      $('.detcompra').attr('disabled', true)     
      $('.add_procompra').hide()
      $('.del_compra').hide()
      $('.forpago').attr('disabled', true)
      $('#efectivo').attr('disabled', true)
      $('#descuento').attr('disabled', true)
      $('#montoice').attr('disabled', true)
      $('.precio').attr('disabled', true)
      $('.cantidad').attr('disabled', true)
      $('.unidadmedida').attr('disabled', true)
      $('.chkiva').attr('disabled', true)
      $('.subtotaledit').attr('disabled', true)
      $('.procomp_del').hide()

      $('.addnota').attr('disabled', true)
      $('.addnota').addClass('disabled');
      
    }

}); 


</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-truck"></i> Compra de Productos  
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>compra">Compra</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DEL PROVEEDOR -->        
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user"></i> Datos Generales de Factura </h3> 

              <div class="pull-right"> 
                <a id="guardar_compra" class="btn bg-green-active btn-sm color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-save"></i> Guardar</a>
              </div>                  

          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <!-- SUCURSAL  -->
                <div style="" class="form-group col-md-2">
                  <label for="lb_res">Sucursal</label>
                  <select id="cmb_sucursal" name="cmb_sucursal" 
                    class="form-control" >
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

                <!-- NOMBRE DEL PROVEEDOR -->
                <div style="" class="form-group col-md-3">
                  <label for="lb_res">Proveedor</label>
                  <select id="cmb_proveedor" name="cmb_proveedor" class="form-control">
                  <?php 
                    if(@$proveedor != NULL){ ?>
                    <option  value="" selected="TRUE">Seleccione Proveedor...</option>
                    <?php } 
                      if (count($proveedor) > 0) {
                        foreach ($proveedor as $provee):
                            if(@$tmpcomp->id_proveedor != NULL){
                                if($provee->id_proveedor == $tmpcomp->id_proveedor){ ?>
                                     <option value="<?php  print $provee->id_proveedor; ?>" selected="TRUE"> <?php  print $provee->nom_proveedor; ?> </option>
                                    <?php
                                }else{ ?>
                                    <option value="<?php  print $provee->id_proveedor; ?>" > <?php  print $provee->nom_proveedor; ?> </option>
                                    <?php
                                }
                            }else{ ?>
                                <option value="<?php  print $provee->id_proveedor; ?>" > <?php  print $provee->nom_proveedor; ?> </option>
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
                            if(@$tmpcomp->cod_sri_sust_comprobante != NULL){
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
                <div class="form-group col-md-2">
                  <label for="">Fecha</label>
                  <div style="margin-bottom: 0px;"class="form-group" >
                    <div class="input-group date">
                      <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right validate[required]" 
                             id="fecha" name="fecha" 
                             value="<?php if(@$tmpcomp != NULL) {$fec = str_replace('-', '/', $tmpcomp->fecha); $fec = date("d/m/Y", strtotime($fec));} else {$fec = date("d/m/Y");} print $fec; ?>"
                      >
                    </div>                             
                  </div>
                </div>  
                <!-- NRO DE FACTURA -->
                <div class="form-group col-md-2">
                  <label>Nro Documento</label>
                  <input id="factura" type="text" class="form-control validate[required] text-center"  name="txt_factura" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->nro_factura; }?>">
                </div>
                <!-- AUTORIZACION -->
                <div class="form-group col-md-3">
                  <label>Nro Autorización</label>
                  <input id="autorizacion" type="text" class="form-control validate[required] " id="txt_autorizacion" name="txt_autorizacion" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->nro_autorizacion; }?>">
                </div>

                <!-- ALMACEN   -->
                <div class="form-group col-md-3">
                    <label>Almacén</label>
                    <select id="cmb_almacen" name="cmb_almacen" class="form-control">
                        <?php 
                          if(@$almacenes != NULL){ ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } else { ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } 
                                  if (count($almacenes) > 0) {
                                    foreach ($almacenes as $alm):
                                        if(@$tmpcomp->id_almacen != NULL){
                                            if($alm->almacen_id == $tmpcomp->id_almacen){ ?>
                                                <option  value="<?php  print $alm->almacen_id; ?>" selected="TRUE"><?php  print $alm->almacen_nombre ?></option> 
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $alm->almacen_id; ?>"> <?php  print $alm->almacen_nombre ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $alm->almacen_id; ?>"> <?php  print $alm->almacen_nombre ?> </option>
                                            <?php
                                            }   ?>
                                        <?php

                                    endforeach;
                                  }
                                  ?>
                    </select>
                </div> 

                <!-- CATEGORIAS -->
                <div class="form-group col-md-2">
                    <label>Categoría</label>
                    <select id="cmb_catgastos" name="cmb_catgastos" class="form-control">
                        <?php 
                          if(@$catgastos != NULL){ ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } else { ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } 
                                  if (count($catgastos) > 0) {
                                    foreach ($catgastos as $cg):
                                        if(@$tmpcomp->categoria != NULL){
                                            if($cg->id_cat_gas == $tmpcomp->categoria){ ?>
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
                              if(@$tmpcomp->doc_mod_cod_sri_tipo != NULL){
                                  if($stc->cod_sri_tipo_doc == $tmpcomp->doc_mod_cod_sri_tipo){ ?>
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
                    <input type="text" class="form-control validate[required] upd_docmod" id="txt_numdocmod" name="txt_numdocmod" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->doc_mod_numero; }?>">
                  </div>
                  <!-- AUTORIZACION Documento Modificado-->
                  <div class="form-group col-md-4">
                    <label>Nro.Autorización Modificado</label>
                    <input type="text" class="form-control validate[required] upd_docmod" id="txt_autodocmod" name="txt_autodocmod" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->doc_mod_autorizacion; }?>">
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- DATOS DE LOS PRODUCTOS A COMPRAR -->
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-shopping-bag"></i> Lista de Productos </h3>
            
            <div class="pull-right"> 
              <a class="btn btn-sm bg-orange-active color-palette btn-grad add_procompra" href="#" data-original-title="" title=""><i class="fa fa-shopping-bag"></i> Añadir Producto </a>
            </div>

            <div class="pull-right">
              <a class="btn btn-danger btn-sm del_compra" href="#" data-original-title="" title=""><i class="fa fa-trash"></i> Borra Productos </a> 
            </div>

          </div>
          <div class="box-body">
            <div class="row">
              <div id="detcompra" class="col-md-12 table-responsive" > 
                <table class="table table-bordered detcompra table-responsive">
                  <tbody>
                    <tr>
                        <th class="text-center " style="width: 10px;">Acción</th>
                        <th class="text-center " style="width: 10px;">Nro</th>
                        <th class="text-center col-md-1">Cod Barra</th>
                        <th>Producto</th>
                        <?php if ($habilitaserie == 1) { ?>
                          <th class="text-center col-md-1">Imei/Serie</th>
                        <?php } ?>
                        <th class="text-center col-md-1">Precio</th>
                        <th class="text-center col-md-1">Existencia</th>
                        <th class="text-center col-md-1">Cantidad</th>
                        <th class="text-center col-md-2" style="width: 144px;">Uni Medida</th>
                        <th class="text-center col-md-1" style="width: 10px;">IVA</th>
                        <th class="text-center col-md-1">SubTotal</th>
                        <th class="text-center col-md-1">Desc SubTotal</th>
                    </tr>
                    <?php 
                      $stciva = 0;
                      $stsiva = 0;  
                      $dstciva = 0;
                      $dstsiva = 0;
                      $moniva = 0;
                      $total = 0;
                      
                                                          
                    $nro = 0; 
                    $desc = @$tmpcomp->desc_monto;
                    if(@$detcomp != NULL){
                      if (count($detcomp) > 0) {
                        foreach ($detcomp as $dc):
                          $nro = $nro + 1;
                          /*if($desc > 0){ $tbsubcdesc = @$dc->descsubtotal; }
                          else { $tbsubcdesc = '0,00'; }*/
                          if(@$dc->iva == 1) { 
                            $dstciva = $dstciva + @$dc->descsubtotal; 
                            $stciva = $stciva + @$dc->subtotal;
                          } 
                          else { 
                            $dstsiva = $dstsiva + @$dc->descsubtotal; 
                            $stsiva = $stsiva + @$dc->subtotal;
                          }
                          $moniva = $moniva + @$dc->montoiva;
                          $total = $total + @$dc->descsubtotal;


                    ?>
                    <tr>
                        <!-- ACCION -->
                        <td class="text-center">
                          <a href="#" title="Eliminar" id="<?php if(@$dc != NULL){ print @$dc->id; }?>" class="btn btn-danger btn-xs btn-grad procomp_del"><i class="fa fa-trash-o"></i></a>
                        </td>
                        <!-- NRO -->
                        <td class="text-center"><?php print $nro; ?></td>
                        <!-- CODIGO DE BARRA -->
                        <td class="text-center"><?php print @$dc->pro_codigobarra; ?></td>
                        <!-- NOMBRE DEL PRODUCTO -->
                        <td class="text-left"><?php print substr(@$dc->pro_nombre,0,20); ?></td>
                        <!-- NOTA DEL PEDIDO -->
                        <?php if ($habilitaserie == 1) { ?>
                         <td>
                          <div class="text-center">
                            <a href="#" title="Añadir Imei/Serie" id="<?php if(@$dc != NULL){ print @$dc->id; }?>" name="<?php if(@$dc != NULL){ print @$dc->id_tmp_comp; }?>" class="btn btn-success btn-xs btn-grad addnota">
                              <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Nro
                            </a> 
                            <input type="hidden" class="prodet" id="<?php if(@$dc != NULL){ print @$dc->id; }?>" name="" value="<?php print @$dc->pro_id; ?>" >  
                          </div>
                         </td>
                        <?php } ?>
                        <!-- PRECIO DEL PRODUCTO -->
                        <td class="text-center ">
                          <input type="text" class="form-control input-sm text-right precio " style="width: 80px;" name="" id="<?php print @$dc->id ?>" value="<?php if(@$dc != NULL){ print number_format(@$dc->precio_compra,$decimalesprecio); }?>" >
                        </td>                              
                        <!-- EXISTENCIA DEL PRODUCTO -->
                        <td class="text-center"><?php print @$dc->existencia; ?></td>
                        <!-- CANTIDAD -->
                        <td class="text-center">
                          <input type="text" class="form-control input-sm text-center cantidad" style="width: 80px;" name="" id="<?php print @$dc->id ?>" value="<?php if(@$dc != NULL){ print number_format(@$dc->cantidad, $decimalescantidad); }?>" <?php if(@$dc != NULL) { if ($dc->cantidadserie > 0) { print "disabled";} } ?> >
                        </td>
                        <!-- UNIDAD DE MEDIDA -->
                        <td class="text-center">
                            <select id="<?php print @$dc->id ?>" name="cmb_proveedor" class="form-control unidadmedida">
                              <?php 
                              $unidad = &get_instance();
                              $unidad->load->model("Unidades_model");
                              $unimed = $unidad->Unidades_model->sel_unidadprod($dc->pro_id);
                              
                              if(@$unimed != NULL){ ?>
                                <option  value="0" selected="TRUE">Seleccione...</option>
                              <?php }  
                                if (count($unimed) > 0) {
                                  foreach ($unimed as $um): 
                                    if(@$dc->id_unimed == $um->id){ ?>
                                      <option  value="<?php print $um->id; ?>" selected="TRUE"><?php  print $um->nombrecorto ?></option>
                                    <?php 
                                    }else{ ?>
                                      <option value="<?php  print $um->id; ?>" > <?php  print $um->nombrecorto ?> </option>
                                    <?php 
                                    }
                                    ?>
                                  <?php
                                  endforeach;
                                } ?>
                            </select>                                    
                        </td>
                        <!-- APLICA IVA -->
                        <td class="text-center">
                          <?php 
                            $valchk = @$dc->iva;
                            if($valchk == 1){ $chk = "checked='checked'"; }else{ $chk = "";}
                          ?>
                          <input type="checkbox" id="<?php print @$dc->id ?>" class="chkiva" <?php print $chk; ?> >
                        </td>
                        <!-- SUBTOTAL -->
                        <td class="text-right">
                          <div id="<?php print @$dc->id; ?>" class="valsubtotal">
                            <input type="text" class="form-control input-sm text-center subtotaledit" style="width: 80px;" name="" id="<?php print @$dc->id ?>" value="<?php if(@$dc != NULL){ print @$dc->subtotal; }?>" >
                             <?php // print $dc->subtotal; ?>
                          </div>
                        </td>
                        <!-- SUBTOTAL DESC -->
                        <td class="text-right">
                          <div name="<?php print @$dc->id; ?>" class="valdescsubtotal">
                            <?php print $dc->descsubtotal; ?>
                          </div>
                        </td>                        
                    </tr>
                    <?php 
                            endforeach;
                        }
                    } 
                    ?>
                  </tbody>
                </table>
<!--                 <div class="pull-right">
                  <a class="btn btn-danger btn-sm del_compra" href="#" data-original-title="" title=""><i class="fa fa-trash"></i> Borra Productos </a> 
                </div>
 -->              </div>
            </div>
          </div>
          <div   align="center" class="box-footer">
            <hr class="linea"> 
              <div class="row" style="margin-top:20px">
                <div class="col-md-6">
                <!-- FORMA DE PAGO -->
                <div class="formapago well col-md-7" style="margin-left: 10px;">

                  <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                    <h4 style="margin-top: -2px; margin-bottom:10px;">Forma de Pago</h4>
                    <div class="text-center" style="">
                      <label class="radio-inline">
                        <input type="radio" class="forpago" id="forpago" name="optionsRadios" checked  value="Contado"> Contado
                      </label>
                      <label class="radio-inline">
                        <input type="radio" class="forpago" id="forpago" name="optionsRadios"  value="Credito"> Crédito
                      </label>
                    </div>
                    <hr class="linea"> 

                  </div>
                  <div class="box-body">
                    <table id="tpcontado" class="table table-bordered ">
                      <tr>
                        <td colspan="2">
                          <input id="cajachica" type="checkbox" style="margin-top:4px; margin-right:8px;" checked disabled> <strong>Caja Chica: <?php print @$caja; ?></strong>
                          <hr class="linea">                          
                        </td>
                      </tr>
                      <tr>
                        <th class="text-right">Total a Pagar: </th>
                        <td class="text-right">
                          <input type="text" class="text-right" name="efectivo" id="efectivo" value="0.00" style="width:70px;" >
                        </td>
                      </tr>
                      <tr>
                      </tr>
                      <tr>
                        <th class="text-right">Cambio</th>
                        <td id="cambio" class="text-right"><strong>0.00</strong></td>
                      </tr> 



                    <!--  <tr>
                        <th class="text-left" style="width: 70%">Tipo de Pago</th>
                        <th class="text-center" style="width: 30%">Monto</th>
                      </tr>
                      <tr>
                        <td><i class="fa fa-money" aria-hidden="true"></i> Efectivo</td>
                        <td class="text-right">
                          <input type="text" class="text-right" name="efectivo" id="efectivo" value="0.00" style="width:70px;" >
                        </td>
                      </tr>
                      <tr>
                        <td><i class="fa fa-credit-card" aria-hidden="true"></i> Tarjeta</td>
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
                      </tr>-->                      
                    </table>
                    
                    <table id="tpcredito" class="table table-bordered ">

                      <tr>
                        <th><i class="fa fa-calendar" aria-hidden="true"></i> Fecha Limite</th>
                        <td class="text-right">
                          <input type="text" class="form-control pull-right validate[required]" id="fechaplazo" name="fechaplazo" value="<?php @$fec = date("d/m/Y"); print @$fec; ?>">
                        </td>
                      </tr>

                      <tr>
                        <th><i class="fa fa-calendar" aria-hidden="true"></i> Días</th>
                        <td class="text-right">
                          <input type="text" class="text-right" name="cre_dias" id="cre_dias" value="0" style="width:70px;" readonly>
                        </td>
                      </tr>

                    
                    </table>


                  </div>

                </div> 
                <!-- FIN FORMA DE PAGO-->                  
                </div>

                <div class="col-md-6">
                <!-- MONTOS DE PAGO -->                
                  <div class="pull-right calmonto ">
                    <table class="table table-clear">
                      <tbody>
                        <tr>
                          <td class="text-left"><strong>Subtotal IVA 12 %</strong></td>
                          <td id="msubtotalconiva" class="text-right">$<?php print number_format(@$stciva,2); ?></td>                                        
                        <tr>
                        <tr>
                          <td class="text-left"><strong>Subtotal IVA 0 %</strong></td>
                          <td id="msubtotalsiniva" class="text-right">$<?php print number_format(@$stsiva,2); ?></td>                                        
                        <tr>
                        <tr>
                          <td class="text-left"><strong>Descuento</strong></td>
                          <td id="" class="text-right">
                            <input type="text" class="text-right" name="" id="descuento" value="<?php print number_format(@$tmpcomp->desc_monto,2); ?>" style="width:70px;" >
                          </td>                                        
                        </tr>
                        <tr>
                          <td class="text-left"><strong>Subtotal con Descuento IVA 12 %</strong></td>
                          <td id="descsubiva" class="text-right">$<?php print number_format(@$dstciva,2); ?></td>                                        
                        </tr>
                        <tr>
                          <td class="text-left"><strong>Subtotal con Descuento IVA 0 %</strong></td>
                          <td id="descsub" class="text-right">$<?php print number_format(@$dstsiva,2); ?></td>                                        
                        </tr>
                        <tr>
                          <td class="text-left"><strong>Monto ICE</strong></td>
                          <td id="" class="text-right">
                            <input type="text" class="text-right" name="" id="montoice" value="<?php if ($tmpcomp->montoice !=NULL) {print number_format(@$tmpcomp->montoice,2);} else {print '0.00';} ?>" style="width:70px;" >
                          </td>                                        
                        </tr>
                        <tr>
                          <td class="text-left"><strong>IVA (12%)</strong></td>
                          <td id="miva" class="text-right">$<?php print number_format(@$moniva,2); ?></td>                                        
                        </tr>
                        <tr>
                          <td class="text-left"><strong>Total</strong></td>
                          <td id="mtotal" class="text-right"><strong>$ <?php $total = $total + $moniva; print number_format(@$total,2); ?></strong></td>                                        
                        </tr>      

                      </tbody>
                    </table>

                  </div>

                </div>

<!--                 <div class="col-md-12">
                  <div class="pull-right"> 
                    <a id="guardar_compra" class="btn bg-green-active color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-save"></i> Guardar Compra </a>
                  </div>                  
                </div>
 -->
              </div><!--/row-->



          </div>
        </div>

      </div>           
    </div>
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

