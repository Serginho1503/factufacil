    $( document ).ready(function() {


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



    });
