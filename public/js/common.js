$(document).ready(function () {

    /* CARGA LOS PRODUCTOS AL PEDIDO */
    $(document).on('click', '.addpro', function(){
        id = $(this).attr('id');
        preparado = $(this).attr('name');
        existencia = $('.existencia[id='+id+']').val();
        almacen = $('.almacen[id='+id+']').val();
        servicio = $('.servicio[id='+id+']').val();
        variante = $('.variante[id='+id+']').val();
        if (servicio == '') {servicio = 0;}
        if (variante == '') {variante = 0;}

        if (preparado == 0 && servicio == 0 && variante == 0 && existencia <= 0) {
          alert("No existe disponibilidad del producto seleccionado.");
          return false;
        }

        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "pedido/tmp_proped",
          data: {id: id, idalm: almacen},
          success: function(json) {
            $.ajax({
              type: "POST",
              dataType: "json",
              url: base_url + "pedido/inserta_pedido",
              success: function(json) {
                $('#detpedido').load(base_url + "pedido/actualiza_pedido");
                actualiza_precio();
                actualiza_valor();                 
              }
            });      
          }
        });
       
    });


    $(document).on('click', '.addpro0000', function(){
        id = $(this).attr('id');
        preparado = $(this).attr('name');
        existencia = $('.existencia[id='+id+']').val();
        almacen = $('.almacen[id='+id+']').val();
        servicio = $('.servicio[id='+id+']').val();
        variante = $('.variante[id='+id+']').val();
        if (servicio == '') {servicio = 0;}
        if (variante == '') {variante = 0;}

        if (preparado == 0 && servicio == 0 && variante == 0 && existencia <= 0) {
          alert("No existe disponibilidad del producto seleccionado.");
          return false;
        }

        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "pedido/tmp_proped",
          data: {id: id, idalm: almacen},
          success: function(json) {
            /*  $.fancybox.close();*/
              $('#detpedido').load(base_url + "pedido/actualiza_pedido");
              actualiza_precio();
              actualiza_valor();
              
          }
        });
       
    });

    function actualiza_valor(){
      var impuestoespecial =$("#tieneimpuesto").val();
      if(impuestoespecial == '') { impuestoespecial = 0; }
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "pedido/upd_valor",
            success: function(json) {
              var subtotaliva = 0;
              var subtotalcero = 0;
              var miva = 0;
              var total = 0;
              var totalimp = 0;

              subtotaliva = json.subtotaliva;
              subtotalcero = json.subtotalcero;
              miva = json.miva;
              total = json.total;

              totalimp = (subtotaliva + subtotalcero) * impuestoespecial / 100;
              totalimp = totalimp.toFixed(2); 
              subtotaliva = subtotaliva.toFixed(2); 
              subtotalcero = subtotalcero.toFixed(2); 
              miva = miva.toFixed(2); 
              total = total.toFixed(2); 


              $('#subtotaliva').html(subtotaliva);
              $('#subtotal0').html(subtotalcero);
              $('#miva').html(miva);
              $('#impuestoespecial').html(totalimp);
              $('#mtotal').html(total);

            //  alert(subtotaliva+" - "+subtotalcero+" - "+miva+" - "+total);
  
            }
        });

    }
    function actualiza_valor00(){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "pedido/upd_valor",
            success: function(json) {
              var subtotaliva = 0;
              var subtotalcero = 0;
              var miva = 0;
              var total = 0;

              subtotaliva = json.subtotaliva;
              subtotalcero = json.subtotalcero;
              miva = json.miva;
              total = json.total;
              orden = json.orden;

              subtotaliva = subtotaliva.toFixed(2); 
              subtotalcero = subtotalcero.toFixed(2); 
              miva = miva.toFixed(2); 
              total = total.toFixed(2); 

              $('#subtotaliva').html(subtotaliva);
              $('#subtotal0').html(subtotalcero);
              $('#miva').html(miva);
              $('#mtotal').html(total);
              $('#labelorden').html("#Orden " + orden);

            //  alert(subtotaliva+" - "+subtotalcero+" - "+miva+" - "+total);
  
            }
        });

    }



    $(document).on('click','.pedpro_del', function(){
      id = $(this).attr("name");
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "pedido/del_pedido_mesa",
          data: {id: id},
          success: function(json) {
              $('#detpedido').load(base_url + "pedido/actualiza_datos");
              actualiza_precio();
              actualiza_valor();
          }
        });
        return false;   
    });

    function actualiza_precio(){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "pedido/upd_monto",
            //data: { id: id },
            success: function(json) {
            //  alert(json.resu.total);
            var iva = 0.12;
            var montoiva = 0;
            var total = 0;
            var subtotal = 0;
            total = parseFloat(total);           
            subtotal = parseFloat(json.resu.total);
            iva = parseFloat(iva);
            montoiva = subtotal * iva;
            total = subtotal + montoiva;  
            subtotal = subtotal.toFixed(2);                           
            montoiva = montoiva.toFixed(2);
            total = total.toFixed(2);
            if(subtotal == 'NaN') {subtotal = 0.00;}
            if(montoiva == 'NaN') {montoiva = 0.00;}
            if(total == 'NaN') {total = 0.00;}
            $('#subtotal').html(subtotal);
            $('#miva').html(montoiva);
            $('#mtotal').html(total);  
            }
        });

    }

    /* ELIMINAR TODOS LOS PRODUCTOS */
    $(document).on('click', '.del_proped', function(){  
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "pedido/elim_producto",
        success: function(json) {
          $('#detpedido').load(base_url + "pedido/actualiza_datos");
          actualiza_precio();
          actualiza_valor();
        }
      });
    });    
    

    $("#formID").validationEngine();

    $(document).on("submit", "#formID", function() {
        var id = $(this).attr("name");
//        alert(id);
        var data = $(this).serialize();
      //  if (conf_guar()) {
            $.ajax({
                url: $(this).attr("action"),
                data: data,
                type: 'POST',
                dataType: 'json',
                success: function(json) {
                //    alert(json.mens);
                    $.fancybox.close();
                    location.reload();
                }
            });
      // }
        return false;
    }); 
    
    $(document).on("submit", "#formBAS", function() {
        var id = $(this).attr("name");
        var data = $(this).serialize();

            $.ajax({
                url: $(this).attr("action"),
                data: data,
                type: 'POST',
                dataType: 'json',
                success: function(json) {
                    $.fancybox.close();
                }
            });
        
        return false;
    }); 

    function conf_guar() {
        return  confirm("¿Confirma que desea guardar este registro?");
    }


    $(document).on("submit", "#formDEL", function() {
        var id = $(this).attr("name");
        var data = $(this).serialize();
        if (conf_del()) {
            $.ajax({
                url: $(this).attr("action"),
                data: data,
                type: 'POST',
                dataType: 'json',
                success: function(json) {
                //    alert(json.mens);
                    $.fancybox.close();
                    location.reload();
                }
            });
        }
        return false;
    }); 
    

    function conf_del() {
        return  confirm("¿Confirma que desea eliminar este registro?");
    }

    /* CARGA LOS PRODUCTOS A LA COMPRA */
    $(document).on('click', '.addprocompra', function(){
        id = $(this).attr('id');  
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "compra/tmp_procomp",
          data: {id: id},
          success: function(json) {
              $.fancybox.close();
              $('#detcompra').load(base_url + "compra/actualiza_tabla_compra");
              aplica_descuento();
          }
        });
        $.fancybox.close();
        
    });

    /* ELIMIANR PRODUCTOS DE LA TABLA TEMPORAL */
    $(document).on('click','.procomp_del', function(){
      id = $(this).attr("id");
      alert("Se eliminara el Producto Seleccionado");
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "compra/del_compra",
          data: {id: id},
          success: function(json) {
              $('#detcompra').load(base_url + "compra/actualiza_tabla_compra");
              aplica_descuento();
          }
        });
        return false;        
    });

    function actualiza_pcompra(){

        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "compra/upd_pcompra",
            //data: { id: id },
            success: function(json) {
            //  alert(json.resu.total);
              valsubtotal = parseFloat(json.resu.subtotal);
              valmontoiva = parseFloat(json.resu.montoiva);
              valtotal = parseFloat(json.resu.total);
              montosubtotal = valsubtotal.toFixed(2);
              montoviva = valmontoiva.toFixed(2);
              montototal = valtotal.toFixed(2);
              $('#msubtotal').html('$ '+montosubtotal);
              $('#miva').html('$ '+montoviva);
              $('#mtotal').html('<strong>$ '+montototal+'</strong>');
            }
        });

    }

 function aplica_descuento(){

        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "compra/desc_compra",
            success: function(json) {

              stciva = parseFloat(json.res.subconiva); // Subtotal con Iva 
              stsiva = parseFloat(json.res.subsiniva); // Subtotal Sin Iva 
              tsdesc = parseFloat(json.res.total); // Total Sin Descuento 

              mtdesc = parseFloat(json.res.descu); // Monto Descuento 
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

              if(montototal == 'NaN') { montototal = 0; }



                $('#msubtotalconiva').html('$ '+valstciva);
                $('#msubtotalsiniva').html('$ '+valstsiva);
                
                $('#descsubiva').html('$ '+valdstciva);
                $('#descsub').html('$ '+valdstsiva);

                $('#mtotal').html('$ '+montototal);


              
              $('#miva').html('$ '+montoviva);

            }
        });

    }

    /* CARGA LOS PRODUCTOS AL MOVIMIENTO */
    $(document).on('click', '.addpromov', function(){
        id = $(this).attr('id');  
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "inventario/ins_tmpmovprod",
          data: {id: id},
          success: function(json) {
              $.fancybox.close();
              $('#detmov').load(base_url + "inventario/actualiza_tabla_producto");
          }
        });
        $.fancybox.close();
        
    });








    
    $(document).on("submit", "#formRET", function() {
      var data = $(this).serialize();
      $.ajax({
        url: $(this).attr("action"),
        data: data,
        type: 'POST',
        dataType: 'json',
        success: function(json) {
          $('#TableRet').DataTable().ajax.reload();
          $.fancybox.close();
        }
      });
      return false;
    });


});