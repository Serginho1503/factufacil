<?php
/* ------------------------------------------------
  ARCHIVO: serviciotecnico_add.php
  DESCRIPCION: Contiene la vista principal del módulo de serviciotecnico_add.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Servicio Técnico / Mantenimiento'</script>";
date_default_timezone_set("America/Guayaquil");

$cfgservicio = &get_instance();
$cfgservicio->load->model("Serviciotecnico_model");
$configservicio = $cfgservicio->Serviciotecnico_model->lst_configservicio();
$mostrarsecc_serie = $configservicio->habilita_serie;
$mostrarsecc_detalle = $configservicio->habilita_detalle;
$mostrarsecc_produtil = $configservicio->habilita_productoutilizado;
$mostrarsecc_abono = $configservicio->habilita_abono;
$mostrar_encargado = $configservicio->habilita_encargado;
$esproductoserie = $mostrarsecc_serie; 
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


  #tpcredito{
    display: none; 
  }  

    .autocomplete-jquery-results{
        border:1px solid silver;
        float:right;
        margin-top:2px;
        position:absolute;
        display: none;
        z-index: 999;
    }

    /*Esta clase se activa cuando el usuario se mueve por las sugerencias*/
    .autocomplete-jquery-mark{
        color:black;
        background-color: #E0F0FF !important;
    }

    /* Cada sugerencia va a llevar esta clase, por lo tanto tomara el estilo siguiente */
    .autocomplete-jquery-item{
        border-bottom: 1px solid lightgray;
        display: block;
        height: 25px;
        padding-top: 5px;
        text-decoration: none;     
        padding-left: 3px;  
        background-color: white;
    }
    .autocomplete-jquery-results{
         box-shadow: 1px 1px 3px black;
    }
    /* Al pasar por encima de las sugerencias*/
    .autocomplete-jquery-item:hover{
        background-color: #E0F0FF;
        color:black;
    }

    .tdvalor{
      width: 80px;
    }

</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

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

    $('#fecha_realizado').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
    $("#fecha_realizado").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });

    $('#fecha_entregado').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
    $("#fecha_entregado").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });

    /* BUSQUEDA DINAMICA POR CEDULA */
    $('#txt_nro_ident').blur(function(){
      var idcliente = $(this).val();    
      if (idcliente === ""){
        alert("Debe ingresar un numero de Identificación");
        return false;
      }   
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Serviciotecnico/valcliente');?>",
          data: {
              idcliente: idcliente
           },
          success: function(json) {
            if(json.mens == null){ 
              $('#menid').attr('class','col-sm-8 has-error');
              $('#mennom').attr('class','col-sm-10 has-error');
              $('#txt_clinom').val('');
              $('#txt_telf').val('');
              $('#txt_correo').val('');
              $('#txt_dir').val(''); 
              $('#txt_ciudad').val('');
              $('#txt_clid').val('');                             
            }
            else {          
              $('#txt_clinom').val(json.mens.nom_cliente);
              $('#txt_clid').val(json.mens.id_cliente);
              $('#mennom').attr('class','col-sm-10 has-success'); 
              $('#menid').attr('class','col-sm-8 has-success');
              $('#txt_telf').val(json.mens.telefonos_cliente);
              $('#txt_correo').val(json.mens.correo_cliente);
              $('#txt_dir').val(json.mens.direccion_cliente); 
              $('#txt_ciudad').val(json.mens.ciudad_cliente);
              if(json.mens.tipo_ident_cliente == 'C'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='C' selected='TRUE'> Cédula </option><option value='P'> Pasaporte </option><option value='R'> R.U.C. </option></select>");
              }
              if(json.mens.tipo_ident_cliente == 'P'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='P' selected='TRUE'> Pasaporte </option><option value='C'> Cédula </option><option value='R'> R.U.C. </option></select>");
              }          
              if(json.mens.tipo_ident_cliente == 'R'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='R' selected='TRUE'> R.U.C. </option><option value='C'> Cédula </option><option value='P'> Pasaporte </option></select>");
              }                    
              registrar_cliente();
            }
          }
      });
    });

    $('.autocomplete').autocomplete();

    var autoparent = "";

    $('#txt_clinom').focus(function(){
      autoparent = "cliente";    
    });

    $('#txt_serie').focus(function(){
      autoparent = "serie";    
    });

    $('#txt_serie').blur(function(){
      var serie = $(this).val(); 
      if ($.trim(serie) === ""){
          $('#idserie').val(0);
          actualizadatosservicio();
      }
    });

    $(document).on('click', '.form-control', function(){  
      $('.autocomplete-jquery-results').each(function(index, el) {
        this.remove();
      });
    });

    /* MUESTRA DATOS DEL CLIENTE */
    $(document).on('click', '.autocomplete-jquery-item', function(){  
      var nom = $(this).text(); 
      if (autoparent == "cliente"){
        autocomplete_cliente(nom);
      } else {
        autocomplete_serie(nom);
      }
    });

    function autocomplete_cliente(nom){
      if (nom === ""){
        alert("Debe ingresar un nombre");
        return false;
      }
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Serviciotecnico/busca_nombre');?>",
          data: {
              nom: nom
           },
          success: function(json) {
              $('#txt_clid').val(json.mens.id_cliente);
              $('#txt_nro_ident').val(json.mens.ident_cliente);
              $('#txt_clinom').val(json.mens.nom_cliente);
              $('#txt_idcli').val(json.mens.id_cliente);
              $('#mennom').attr('class','col-sm-10 has-success'); 
              $('#menid').attr('class','col-sm-8 has-success');
              $('#txt_telf').val(json.mens.telefonos_cliente);
              $('#txt_correo').val(json.mens.correo_cliente);
              $('#txt_dir').val(json.mens.direccion_cliente); 
              $('#txt_ciudad').val(json.mens.ciudad_cliente);    
              if(json.mens.tipo_ident_cliente == 'C'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='C' selected='TRUE'> Cédula </option><option value='P'> Pasaporte </option><option value='R'> R.U.C. </option></select>");
              }
              if(json.mens.tipo_ident_cliente == 'P'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='P' selected='TRUE'> Pasaporte </option><option value='C'> Cédula </option><option value='R'> R.U.C. </option></select>");
              }          
              if(json.mens.tipo_ident_cliente == 'R'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='R' selected='TRUE'> R.U.C. </option><option value='C'> Cédula </option><option value='P'> Pasaporte </option></select>");
              }                     
              registrar_cliente();

          }
      });

    }

    $('.guarda_cliente').blur(function(){
      var idcliente = $('#txt_nro_ident').val();  
     /* if (idcliente === ""){
        alert("Debe ingresar un numero de Identificación");
        return false;
      } */    
      var nom = $('#txt_clinom').val(); 
      if (nom === ""){
        alert("Debe ingresar un nombre");
        return false;
      }      
      if (idcliente != ""){
        registrar_cliente();
      }
    });


    function registrar_cliente(){
      var idc = $('#txt_clid').val();
      var id = $('#txt_nro_ident').val();
      var idtp = $('#cmb_tipident option:selected').val();      
      var nom = $('#txt_clinom').val();
      var tel = $('#txt_telf').val();
      var cor = $('#txt_correo').val();
      var dir = $('#txt_dir').val(); 
      var ciu = $('#txt_ciudad').val(); 
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Serviciotecnico/upd_cliente",
          data: { idcli:id, idtp:idtp, nom:nom, tel:tel, cor:cor, dir:dir, ciu:ciu, idc: idc },
          success: function(json) {
          }
      });
    }

    function autocomplete_serie(serie){
      if (serie === ""){
        alert("Debe ingresar una serie");
        return false;
      }
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Serviciotecnico/busca_serie');?>",
          data: {
              serie: serie
           },
          success: function(json) {
              $('#idserie').val(json.mens.id_serie);
              $('#txt_serie').val(serie);
              $('#txt_producto').val(json.mens.pro_nombre);
              actualizadatosservicio();
          }
      });

    }

    $(document).on('click', '.tiposeleccion', function(){  
      actualizaseleccion(this);
    });  


    function actualizaseleccion(obj){
      var tipo = $(obj).val(); 
      var habdet = true;
      if (tipo == 'interno') {
        $('#txt_serie').attr('disabled',false); 
        $("#esproductoserie").val(1);
      } else{
        $('#txt_serie').attr('disabled',true); 
        $("#esproductoserie").val(0);
        $('#idserie').val(0); 
        $('#txt_serie').val(""); 
        $('#txt_producto').val(""); 
        habdet = false;
      }
      $('.detallevalor').each(function(){
        id = this.id;
        $('.detallevalor[id='+id+']').attr('disabled',habdet);
        if (habdet == true){
          $('.detallevalor[id='+id+']').val('');
        }
      });  
      actualizadatosservicio();  
    }


    $(document).on('change','#cmb_estado', function(){
      actualiza_estado();
    });   

    function actualiza_estado(){
      var estado = $('#cmb_estado').val();
      if (estado >= 3) {
        $("#fecha_realizado").attr("disabled", false);
        $("#txt_trabajorealizado").attr("disabled", false);        
      } else {
        $("#fecha_realizado").attr("disabled", true);
        $("#fecha_realizado").val("");
        $("#txt_trabajorealizado").attr("disabled", true);        
        $("#txt_trabajorealizado").val("");        
      }
      if (estado >= 4) {
        $("#fecha_entregado").attr("disabled", false);
      } else {
        $("#fecha_entregado").attr("disabled", true);
        $("#fecha_entregado").val("");
      }
    }  

    function habilitaestadoopciones(){
      $("#cmb_estado > option").each(function () {    
          if (this.value == 5) {
              $("#cmb_estado option[value='" + this.value +"']").attr("disabled",true);
          }
      });
    }

    $(document).on('change','.datogenservicio', function(){
      actualizadatosservicio();
    });   

    function actualizadatosservicio(){
      var cmb_sucursal = $("#cmb_sucursal").val();
      var fecha = $("#fecha").val();
      var txt_descripcion = $("#txt_descripcion").val();
      var cmb_encargado = $("#cmb_encargado").val();
      var cmb_estado = $("#cmb_estado").val();
      var fecha_realizado = $("#fecha_realizado").val();
      var txt_trabajorealizado = $("#txt_trabajorealizado").val();
      var fecha_entregado = $("#fecha_entregado").val();
      var esproductoserie = $("#esproductoserie").val();
      var idserie = $("#idserie").val();
      var costo_estimado = $("#txt_costo_estimado").val();

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Serviciotecnico/upd_tmpgenservicio');?>",
        data: { cmb_sucursal: cmb_sucursal, fecha: fecha, txt_descripcion: txt_descripcion, 
                cmb_encargado: cmb_encargado, cmb_estado: cmb_estado, fecha_realizado: fecha_realizado, 
                txt_trabajorealizado: txt_trabajorealizado, fecha_entregado: fecha_entregado,
                esproductoserie: esproductoserie, idserie: idserie, costo_estimado: costo_estimado },
        success: function(json) {
          if (json.resu != null){
            $("#txt_orden").val(json.resu);            
          }
        }
      });
    }


    /* ACTUALIZA detalles servicio */
    $(document).on('change','.detallevalor', function(){
      var idcfg = this.id;
      var valcfg = $(this).val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Serviciotecnico/upd_detalletmpservicio');?>",
        data: { idcfg: idcfg, valcfg: valcfg },
        success: function(json) {
        }
      });
    });   


/*add_detalle*/
    $(document).on('click', '#add_detalle', function(){
      $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('Serviciotecnico/detalle_add');?>" 
      });
    });

/*btnguardardetalle    */
    $(document).on("submit", "#formSubDet", function() {
      var id = $("#txt_iddetalle").val();
      var data = $(this).serialize();
      $.ajax({
        url: base_url + "Serviciotecnico/tmp_serviciotecnico_detalle",
        data: { id: id },
        type: 'POST',
        dataType: 'json',
        success: function(json) {
          $.ajax({
            url: base_url + "Serviciotecnico/upd_detalletmp",
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(json) {
              $.fancybox.close();
              location.replace("<?php print $base_url;?>Serviciotecnico/add_servicio");
            }
          });
        }
      });
    }); 

    /* ELIMINAR Detalle */
    $(document).on('click', '.det_del', function(){  
      var item = $(this).attr('name');
      if (confirm("Desea eliminar el detalle " + item + "?")){
        var id = $(this).attr('id');
        $.ajax({
          url: base_url + "Serviciotecnico/del_detalletmp",
          type: 'POST',
          dataType: 'json',
          data: {id: id},
          success: function(json) {
            location.replace("<?php print $base_url;?>Serviciotecnico/add_servicio");
          }
        });
      }
    });  

    /* ELIMINAR Detalle */
    $(document).on('click', '.det_edit', function(){  
      var id = $(this).attr('id');
      $.ajax({
        url: base_url + "Serviciotecnico/tmp_serviciotecnico_detalle",
        data: { id: id },
        type: 'POST',
        dataType: 'json',
        success: function(json) {
          $.fancybox.open({
              type: "ajax",
              width: 550,
              height: 550,
              ajax: {
                 dataType: "html",
                 type: "POST"
              },
              href: "<?php echo base_url('Serviciotecnico/detalle_edit');?>" 
          });
        }  
      });  
    });  


    $('#dataTableProdUtil').dataTable({
      "language":{  "lengthMenu":"Mostrar _MENU_ registros por página.",
                    "zeroRecords": "Lo sentimos. No se encontraron registros.",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros aún.",
                    "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                    "search" : "Búsqueda",
                    "LoadingRecords": "Cargando ...",
                    "Processing": "Procesando...",
                    "SearchPlaceholder": "Comience a teclear...",
                    "paginate": { "previous": "Anterior", "next": "Siguiente", }
                    },
        'ajax': "listadoProdUtil",
        'columns': [
            {"data": "ver"},                            
            {"data": "cantidad"},                       
            {"data": "codigo"},
            {"data": "precio"},   
            {"data": "subtotal"},   
            {"data": "nombre"}   
        ]
    });

    $(document).on('click', '.selectdetalle', function(){
      $('.add_produtil').attr('disabled', false);
      $('.selectdetalle').each(function(){
        $(this).removeAttr('bgcolor');
      })
      $(this).attr('bgcolor',"gray");

      var id = $(this).attr('name');
      $.ajax({
        url: base_url + "Serviciotecnico/tmp_serviciotecnico_detalle",
        data: { id: id },
        type: 'POST',
        dataType: 'json',
        success: function(json) {
            $('#dataTableProdUtil').DataTable().ajax.reload();
            $.ajax({
              url: base_url + "Serviciotecnico/get_totalproducto_detalle",
              type: 'POST',
              dataType: 'json',
              success: function(json) {
                var total = parseFloat(json.resu);  
                $('#valortotalproducto').val(total.toFixed(2));
              }  
            });             
        }  
      });  

    });  

    /* adicionar producto */
    $(document).on('click', '.add_produtil', function(){  
        $.fancybox.open({
            type: "ajax",
            width: 550,
            height: 550,
            ajax: {
               dataType: "html",
               type: "POST"
            },
            href: "<?php echo base_url('Serviciotecnico/edit_producto');?>" 
        });
    });  

    $(document).on('click', '.addproservicio', function(){
      var id = $(this).attr('id');
      var almacen = $(this).attr('name');
      $.ajax({
        url: base_url + "Serviciotecnico/add_producto",
        data: { id: id, almacen: almacen },
        type: 'POST',
        dataType: 'json',
        success: function(json) {
            $('#dataTableProdUtil').DataTable().ajax.reload();
            $.ajax({
              url: base_url + "Serviciotecnico/get_totalproducto_detalle",
              type: 'POST',
              dataType: 'json',
              success: function(json) {
                var total = parseFloat(json.resu);  
                $('#valortotalproducto').val(total.toFixed(2));
              }  
            });             
        }  
      });  

    });  

    $(document).on('blur', '.upd_prodcant', function(){
      var id = $(this).attr('id');
      var cant = $(this).val();
      $.ajax({
        url: base_url + "Serviciotecnico/upd_producto",
        data: { id: id, cant: cant },
        type: 'POST',
        dataType: 'json',
        success: function(json) {
            $('#dataTableProdUtil').DataTable().ajax.reload();
            $.ajax({
              url: base_url + "Serviciotecnico/get_totalproducto_detalle",
              type: 'POST',
              dataType: 'json',
              success: function(json) {
                var total = parseFloat(json.resu);  
                $('#valortotalproducto').val(total.toFixed(2));
              }  
            });             
        }  
      });  

    });  

    $(document).on('blur', '.upd_prodprecio', function(){
      var id = $(this).attr('id');
      var precio = $(this).val();
      $.ajax({
        url: base_url + "Serviciotecnico/upd_productoprecio",
        data: { id: id, precio: precio },
        type: 'POST',
        dataType: 'json',
        success: function(json) {
            $('#dataTableProdUtil').DataTable().ajax.reload();
            $.ajax({
              url: base_url + "Serviciotecnico/get_totalproducto_detalle",
              type: 'POST',
              dataType: 'json',
              success: function(json) {
                var total = parseFloat(json.resu);  
                $('#valortotalproducto').val(total.toFixed(2));
              }  
            });             
        }  
      });  

    });  

    $(document).on('click', '.produtil_del', function(){
      var id = $(this).attr('id');
      var prod = $(this).attr('name');
      if (confirm("Desea eliminar el producto " + prod + " ?")){
        $.ajax({
          url: base_url + "Serviciotecnico/del_producto",
          data: { id: id },
          type: 'POST',
          dataType: 'json',
          success: function(json) {
              $('#dataTableProdUtil').DataTable().ajax.reload();
              $.ajax({
                url: base_url + "Serviciotecnico/get_totalproducto_detalle",
                type: 'POST',
                dataType: 'json',
                success: function(json) {
                  var total = parseFloat(json.resu);  
                  $('#valortotalproducto').val(total.toFixed(2));
                }  
              });             
          }  
        });  
      }
    });  


    $('#dataTableAbono').dataTable({
      "language":{  "lengthMenu":"Mostrar _MENU_ registros por página.",
                    "zeroRecords": "Lo sentimos. No se encontraron registros.",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros aún.",
                    "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                    "search" : "Búsqueda",
                    "LoadingRecords": "Cargando ...",
                    "Processing": "Procesando...",
                    "SearchPlaceholder": "Comience a teclear...",
                    "paginate": { "previous": "Anterior", "next": "Siguiente", }
                    },
        'ajax': "listadoAbonos",
        'columns': [
            {"data": "ver"},                            
            {"data": "fecha"},                            
            {"data": "documento"},
            {"data": "tipo"},   
            {"data": "valor"}                       
        ]
    });

  /* GUADAR  */
  $(document).on('click','#guardar', function(){
    var sucursal = $("#cmb_sucursal").val();
    var numerorden = $("#txt_orden").val();
    $.ajax({
      url: base_url + "Serviciotecnico/existe_numerorden",
      data: { sucursal: sucursal, numerorden: numerorden },
      type: 'POST',
      dataType: 'json',
      success: function(json) {
        if (json.resu == 0){
          var txt_id = $("#txt_id").val();
          $.ajax({
            url: base_url + "Serviciotecnico/guardar",
            data: { txt_id: txt_id },
            type: 'POST',
            dataType: 'json',
            success: function(json) {
              location.replace("<?php print $base_url;?>serviciotecnico");
            }         
          });
        }
        else{
          alert("El número de orden ya esta siendo utilizado")
        }
      }
    });

  }); 


    /* adicionar producto */
    $(document).on('click', '.add_abono', function(){  
        var costo = $("#txt_costo_estimado").val();
        if (costo == '') { costo =0; }
        var totalabonos = $("#totalabonos").val();
        if (totalabonos == '') { totalabonos =0; }
        var montopendiente = parseFloat(costo) - parseFloat(totalabonos);

        $.fancybox.open({
            type: "ajax",
            width: 550,
            height: 550,
            ajax: {
               dataType: "html",
               type: "POST",
               data: { montopendiente: montopendiente }
            },
            href: "<?php echo base_url('Serviciotecnico/add_abono');?>" 
        });
    }); 

    /* adicionar producto */
    $(document).on('click', '.abono_upd', function(){  
        var idreg = $(this).attr('id');
        var costo = $("#txt_costo_estimado").val();
        if (costo == '') { costo =0; }
        var totalabonos = $("#totalabonos").val();
        if (totalabonos == '') { totalabonos =0; }
        var montopendiente = parseFloat(costo) - parseFloat(totalabonos);

        $.fancybox.open({
            type: "ajax",
            width: 550,
            height: 550,
            ajax: {
               dataType: "html",
               type: "POST",
               data: { montopendiente: montopendiente, idreg: idreg }
            },
            href: "<?php echo base_url('Serviciotecnico/edit_abono');?>" 
        });
    }); 

    $(document).on('click', '.guardafp', function(){
        var costo = $("#txt_costo_estimado").val();
        if (costo == '') { costo =0; }
        var idreg = $("#txt_idreg").val();
        var fp = $("#cmb_forpago").val();
        var monto = $("#txt_montofp").val();
        var fechat = $("#fechat").val();
        var tiptarjeta = $("#cmb_tarjeta").val();
        var nrotar = $("#txt_nrotar").val();
        var bco = $('#cmb_banco').val(); 
        var tbco = $('#cmbt_banco option:selected').val(); 
        var nrodoc = $("#txt_nrodoc").val();
        var descdoc = $("#txt_descdoc").val();
        var tnrodoc = $("#txt_tnrodoc").val();
        var tdescdoc = $("#txt_tdescdoc").val();        
        var fechae = $("#fechae").val();
        var fechac = $("#fechac").val();
        var nrocta = $("#txt_nrocta").val();
        var idcaja = $("#cmb_caja").val();
      
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {idreg: idreg, fp: fp, monto: monto, fechat: fechat, tiptarjeta: tiptarjeta, nrotar: nrotar, bco: bco, 
                   tbco: tbco, tnrodoc: tnrodoc, nrodoc: nrodoc, tdescdoc: tdescdoc, descdoc: descdoc, fechae: fechae, 
                   fechac: fechac, nrocta: nrocta, idcaja: idcaja},                
            url: base_url + "Serviciotecnico/guardar_abono",
            success: function(json) {
                $('#dataTableAbono').DataTable().ajax.reload();
                $('#totalabonos').val(json.resu);
                tmpabonos = parseFloat(json.resu);
                if (tmpabonos >= parseFloat(costo)){ 
                  $(".abono_add").attr("disabled", true);
                } else {
                  $(".abono_add").attr("disabled", false);
                } 
                $.fancybox.close();              
            }
        });  
    });

    $(document).on('click', '.abono_del', function(){
      var id = $(this).attr('id');
      var strname = ''/*$(this).attr('name')*/;
      if (confirm("Desea eliminar el abono " + strname + " ?")){
        $.ajax({
          url: base_url + "Serviciotecnico/del_abono",
          data: { id: id },
          type: 'POST',
          dataType: 'json',
          success: function(json) {
              $('#dataTableAbono').DataTable().ajax.reload();
              $('#totalabonos').val(json.resu);
              $(".abono_add").attr("disabled", false);
          }  
        });  
      }
    });  

    function redireccion(contr, meth) {
        location.replace(base_url + contr + (meth ? "/" + meth : ""));
    }

    $(document).on('click', '#imprimir', function(){
      $.fancybox.open({
            type: "iframe",
            width: 800,
            height: 550,
            ajax: {
               dataType: "html",
               type: "POST",
            },
            href: base_url + 'Serviciotecnico/print_pdf_servicio_tmp' 
      });
    });

    $(document).on('click', '#rpt_etiqueta', function(){
      $.fancybox.open({
            type: "iframe",
            width: 800,
            height: 550,
            ajax: {
               dataType: "html",
               type: "POST",
            },
            href: base_url + 'Serviciotecnico/print_pdf_tmp_servicio_etiqueta' 
      });
    });

    var habilitaseccprodutil = <?php print $mostrarsecc_produtil; ?>;
    if (habilitaseccprodutil == 0) {$("#seccionproductos").remove();}
    var habilitaseccabono = <?php print $mostrarsecc_abono; ?>;
    if (habilitaseccabono == 0) {$("#seccionabonos").remove();}

    $('.add_produtil').attr('disabled', true);


}); 


</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-truck"></i> Servicio Técnico / Mantenimiento
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>serviciotecnico">Servicios Técnicos</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DEL PROVEEDOR -->        
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
<!--             <h3 class="box-title"><i class="fa fa-user"></i> Datos del Servicio / Mantenimiento</h3> 
 -->              <input type="hidden" id="txt_id" name="txt_id" value="<?php if(@$cliente->id_servicio != NULL){ print @$cliente->id_servicio; } else {print 0;} ?>" >    


            <!-- SUCURSAL  -->
            <div style="" class="form-group col-md-3 ">
             <div class="col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
              <label for="lb_res">Sucursal</label>
             </div> 
             <div class="col-md-9">
              <select id="cmb_sucursal" name="cmb_sucursal" class="form-control datogenservicio">
              <?php 
                if(@$sucursales != NULL){ ?>
                <?php } else { ?>
                <option  value="" selected="TRUE">Seleccione Sucursal...</option>
                <?php } 
                  if (count($sucursales) > 0) {
                    foreach ($sucursales as $obj):
                        if(@$cliente->id_sucursal != NULL){
                            if($obj->id_sucursal == $cliente->id_sucursal){ ?>
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
            </div>

            <!-- Orden -->
            <div class="form-group col-md-3 ">
             <div class="col-md-4" style="padding-right: 0px; ">
              <label># Orden</label>
             </div>
             <div class="col-md-8">
              <input type="text" class="form-control validate[required] text-center" id="txt_orden" name="txt_orden" value="<?php if(@$cliente != NULL){ print @$cliente->numero_orden; } else {print @$nuevaorden;} ?>" readonly>
             </div>
            </div>

            <!-- FECHA DE FACTURA -->
            <div class="form-group col-md-3">
              <div class="col-md-3">
                <label for="">Fecha</label>
              </div>  
              <div class="col-md-9">
               <div style="margin-bottom: 0px;" class="form-group" >
                <div class="input-group date">
                  <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right validate[required] datogenservicio" id="fecha" name="fecha" value="<?php if(@$cliente != NULL){ $fec =  str_replace('-', '/', $cliente->fecha_emision); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec;} else { $fec = date("d/m/Y"); print $fec;} ?>">
                </div>                             
               </div>
              </div>
            </div>  

<!--             <div class="pull-right" style="padding-left: 10px;"> 
              <a id="imprimir" class="btn btn-sm bg-blue-active color-palette btn-grad " href="#" data-original-title="" title="Imprimir Servicio"><i class="fa fa-print"></i> Imprimir</a>
            </div>                  
 -->
            <div class="btn-group pull-right" style="color: white; ">
              <button type="button" class="btn btn-sm bg-blue"><i class="fa fa-list" aria-hidden="true"></i> Imprimir</button>
              <button type="button" class="btn btn-sm bg-blue dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only"></span>
              </button>
              <ul class="dropdown-menu" role="menu">
                <li><a id="imprimir" class="btn-primary" style="color: black;" href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i> Servicio</a></li>
                <li><a id="rpt_etiqueta" class="btn-primary" style="color: black;" href="#"><i class="fa fa-credit-card" aria-hidden="true"></i>Etiqueta</a></li>
              </ul>
            </div>


            <div class="pull-right"> 
              <a id="guardar" class="btn btn-sm bg-green-active color-palette btn-grad " href="#" data-original-title="" title="Guardar Servicio"><i class="fa fa-save"></i>  Guardar</a>
            </div>                  

          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

                <div class="form-group col-md-10" id="secciondescripcion">
                  <label for="txt_descripcion" class="control-label col-md-2" style="padding-left: 0px; margin-left: 0px; ">Descripción</label>             
                 <div class="col-md-10" style="padding-left: 0px; padding-right: 0px;">
                   <input type="text" class="form-control datogenservicio" name="txt_descripcion" id="txt_descripcion" placeholder="Descripción" value="<?php if(@$cliente != NULL){ print @$cliente->descripcion; }?>" >
                 </div>
                </div>

                <!-- Costo -->
                <div class="form-group col-md-2">
                 <div class="col-md-4" style="padding-right: 0px; ">
                  <label>Costo</label>
                 </div>
                 <div class="col-md-8" style="padding-right: 0px;">
                  <input type="text" class="form-control validate[required] text-right datogenservicio" id="txt_costo_estimado" name="txt_costo_estimado" value="<?php if(@$cliente->costo_estimado != NULL){ print @$cliente->costo_estimado; } else {print 0;} ?>" >
                 </div>
                </div>

              </div>
            </div>
          </div>

          <hr class="linea"> 

          <div class="box-body">
            <div class="row">
              <div class="col-md-12">           

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Tipo Ident</label>
                  <div  class="col-sm-8 tipident" style="padding-right: 0px;">
                    <select id="cmb_tipident" name="cmb_tipident" class="form-control">
                    <?php 
                      if(@$tipident != NULL){ ?>
                    <?php } else { ?>
                        <option  value="0" selected="TRUE">Seleccione...</option>
                    <?php } 
                              if (count($tipident) > 0) {
                                foreach ($tipident as $ti):
                                    if(@$cliente->tipo_ident != NULL){
                                        if($ti->cod == $cliente->tipo_ident){ ?>
                                            <option  value="<?php  print $ti->cod; ?>" selected="TRUE"><?php  print $ti->det; ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $ti->cod; ?>"> <?php  print $ti->det; ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $ti->cod; ?>"> <?php  print $ti->det; ?> </option>
                                        <?php
                                        }   ?>
                                    <?php

                                endforeach;
                              }
                              ?>
                    </select>
                  </div>
                </div>

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;"># Ident</label>
                  <div id="menid" class="col-sm-8" style="padding-right: 0px;">
                    <input type="hidden" id="txt_clid" name="txt_clid" value="<?php if(@$cliente != NULL){ print @$cliente->id_cliente; }?>" >    
                    <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$cliente != NULL){ print @$cliente->nro_ident; }?>" >
                  </div>
                </div>

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-2 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Cliente</label>
                  <div id="mennom" class="col-sm-10 autocomplete" style="padding-right: 0px;">
                    <input type="text" class="form-control " name="txt_clinom" id="txt_clinom" placeholder="Nombre del Cliente" value="<?php if(@$cliente != NULL){ print @$cliente->nom_cliente; }?>" data-source="<?php echo base_url('facturar/valclientenombre?nombre=');?>">
                  </div>
                </div>

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Teléfono</label>
                  <div id="" class="col-sm-8" style="padding-right: 0px;">
                   <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_telf" id="txt_telf" placeholder="Telefono" value="<?php if(@$cliente != NULL){ print @$cliente->telf_cliente; }?>" >
                  </div>
                </div>

                <div class="form-group col-md-6" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-2 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Dirección</label>
                  <div id="" class="col-sm-10" style="padding-left: 20px;">
                   <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_dir" id="txt_dir" placeholder="Dirección" value="<?php if(@$cliente != NULL){ print @$cliente->dir_cliente; }?>" >
                  </div>
                </div>

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Ciudad</label>
                  <div id="" class="col-sm-8" style="padding-right: 0px;">
                   <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_ciudad" id="txt_ciudad" placeholder="Ciudad" value="<?php if(@$cliente != NULL){ print @$cliente->ciu_cliente; }?>" >
                  </div>
                </div>

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Correo</label>
                  <div id="" class="col-sm-8" style="padding-right: 0px;">
                   <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_correo" id="txt_correo" placeholder="Correo" value="<?php if(@$cliente != NULL){ print @$cliente->correo_cliente; }?>" >
                  </div>
                </div>

                </div> 
              </div>

          </div>

        </div>

      </div>


    <!-- DATOS DE LOS PRODUCTOS A COMPRAR -->
      <div class="col-md-12" style="padding-bottom: 1px;">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user"></i> Detalle de Servicios </h3> 
              <input type="hidden" id="txt_id" name="txt_id" value="<?php if(@$cliente->id_servicio != NULL){ print @$cliente->id_servicio; } else {print 0;} ?>" >    

            <div class="pull-right"> 
              <a id="add_detalle" class="btn btn-sm bg-light-blue-active color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-plus-square"></i> Añadir Detalle</a>
            </div>                  

            <table class="table table-clear " id="tabladetalle">
              <thead>
                <tr>
                  <th class="text-center col-md-1">Acción</th>                    
                  <th class="text-center col-md-1">#</th>
                  <th class="text-center col-md-1">Serie</th>
                  <?php 
                    if (@$nombredetalle != NULL){
                      foreach ($nombredetalle as $nombre) { ?>
                        <th class="text-center col-md-1"><?php print $nombre->nombre_configdetalle; ?></th>
                  <?php  } 
                    } 
                  ?>
                  <th class="text-center col-md-1">Encargado</th>                    
                  <th class="text-center col-md-1">Estado</th>                    
                  <th class="text-center col-md-1">Realizado</th>                    
                  <th class="text-center col-md-1">Entregado</th>                    
                </tr>
              </thead>
              <tbody>                                                        
                <?php 
                $numc=0;
                $detant=0;
                $numitem=0;
                foreach ($detalles as $det) {
                ?>
                  <?php if ($detant != $det->id_detalle){ 
                    $numc++;
                  ?>                   
                    <tr class="selectdetalle" name="<?php print $det->id_detalle; ?>" >
                      <td class="text-center">
                          <a style="color: #094074;" href="#" title="Editar" id="<?php if(@$det != NULL){ print @$det->id_detalle; }?>" name="<?php print $numc; ?>" class="det_edit"><i class="fa fa-pencil-square-o fa-lg"></i></a> &nbsp;&nbsp;
                          <a style="color: #B80C09;" href="#" title="Eliminar" id="<?php if(@$det != NULL){ print @$det->id_detalle; }?>" name="<?php print $numc; ?>" class="det_del"><i class="fa fa-minus-circle fa-lg"></i></a>
                      </td>
                      <td class="text-center">
                        <?php print $numc; ?>
                      </td>
                      <td class="text-center">
                        <?php print $det->numeroserie; ?>
                      </td>
                  <?php 
                    $detant=$det->id_detalle;
                    $numitem=1;
                  } else { $numitem++; } ?>

                  <td class="text-center">
                    <?php print $det->valor; ?>
                  </td>

                  <?php if ($numitem == count($nombredetalle)){ ?>
                    <td class="text-center">
                      <?php print $det->nombre_empleado; ?>
                    </td>
                    <td class="text-center">
                      <?php print $det->nombre_estado; ?>
                    </td>
                    <td class="text-center">
                      <?php if (@$det->id_estado >= 3) { $fec =  str_replace('-', '/', $det->fecha_realizado); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec;} ?>
                    </td>
                    <td class="text-center">
                      <?php if (@$det->id_estado >= 4) { $fec =  str_replace('-', '/', $det->fecha_entregado); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec; } ?>
                    </td>

                   </tr>
                  <?php } ?>
                <?php 
                }
                ?>
              </tbody>
            </table>
          </div>


        </div>

        <div class="col-md-13" id="seccionproductos" style="padding-bottom: 0px; padding-right: 0px; ">

          <div class="col-md-12" style="padding-left: 0px; padding-right: 0px; padding-bottom: 0px;">
            <div class="box box-danger">
              <div class="box-header with-border">
                <h3 class="box-title">Productos Utilizados </h3>
                <div class="pull-right"> 
                  <a id="btn_addpro" class="btn bg-light-blue-active color-palette add_produtil " <?php if(@$comp != NULL){ if ((@$comp->descsubsiniva+@$comp->descsubconiva) == @$comp->totalbaseretenido) {print 'disabled'; }}?> href="#" data-original-title="" title=""><i class="fa fa-plus-square"></i> Añadir Producto </a>
                </div>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-xs-12">
                    <div class="box">
                      <div class="box-body table-responsive">
                        <table id="dataTableProdUtil" class="table table-bordered table-hover table-responsive">
                          <thead>
                            <tr >
                              <!-- <th>Id</th>  --> 
                              <th>Acción</th>
                              <th>Cantidad</th>
                              <th>Codigo</th>
                              <th>Precio</th>
                              <th>Subtotal</th>
                              <th>Nombre</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                      <!-- /.box-body -->

                      <div class="form-group col-md-5 pull-right" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                        <label for="" class="col-sm-6 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Valor Total</label>
                        <div id="" class="col-sm-6" style="padding-right: 0px;">
                         <input type="text" class="form-control text-right" name="valortotalproducto" id="valortotalproducto" value="<?php if(@$totalprod != NULL){ print number_format(@$totalprod*1.12,2); } else {print '0.00';}?>" readonly>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </div>

              </div>
              <div class="box-footer">

              </div>
            </div>
          </div>           
        </div>

        <div class="col-md-13" id="seccionabonos" style="padding-bottom: 0px;">

          <div class="col-md-12" style="padding-left: 0px; padding-right: 0px; padding-bottom: 0px;">
            <div class="box box-danger">
              <div class="box-header with-border">
                <h3 class="box-title">Abonos Realizados </h3>
                <input type="hidden" id="totalabonos" name="totalabonos" value="<?php print number_format(@$totalabonos,2); ?>" >
                <div class="pull-right"> 
                  <a id="btn_addabono" class="btn bg-light-blue-active color-palette add_abono" <?php if(@$comp != NULL){ if ((@$comp->descsubsiniva+@$comp->descsubconiva) == @$comp->totalbaseretenido) {print 'disabled'; }}?> href="#" data-original-title="" title=""><i class="fa fa-plus-square"></i> Añadir Abono </a>
                </div>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-xs-12">
                    <div class="box">
                      <div class="box-body table-responsive">
                        <table id="dataTableAbono" class="table table-bordered table-hover table-responsive">
                          <thead>
                            <tr >
                              <!-- <th>Id</th>  --> 
                              <th>Acción</th>
                              <th>Fecha</th>
                              <th>Documento</th>
                              <th>Tipo</th>
                              <th>Valor</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                      <!-- /.box-body -->
                    </div>
                  </div>
                </div>

              </div>
              <div class="box-footer">

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

