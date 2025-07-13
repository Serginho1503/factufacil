<?php
/* ------------------------------------------------
  ARCHIVO: Ventas.php
  DESCRIPCION: Contiene la vista principal del módulo de Ventas.
  FECHA DE CREACIÓN: 28/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Ventas'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/css/jquery.timepicker.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/css/bootstrap-datepicker.standalone.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/lib/pikaday.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/lib/jquery.ptTimeSelect.css" />

<style type="text/css">
  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 
  .dropdown-menu > li > a {
    color: #fff;
  } 


    .autocomplete-jquery-results{
        border:1px solid silver;
        float:right;
        margin-top:2px;
        position:absolute;
        display: none;
        z-index: 999 !important;
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


    .dt-alignright { text-align: right; }

</style>

<script src="<?php print $base_url; ?>assets/plugins/datepair/js/jquery.min.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/js/jquery.timepicker.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/js/bootstrap-datepicker.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/pikaday.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/jquery.ptTimeSelect.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/moment.min.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/site.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/dist/datepair.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/dist/jquery.datepair.js"></script>

<script type='text/javascript' language='javascript'>

 var jq = $.noConflict();
  jq(document).ready(function () {

    jq('#buscrango .time').timepicker({
        'showDuration': true,
        'timeFormat': 'H:i:s'
    });

    jq('#buscrango .date').datepicker({
        'format': 'dd/mm/yyyy',
        'autoclose': true
    });

    jq('#buscrango').datepair(); 

      /* CARGA DE DATOS EN EL DATATABLE */
     tablevent=$('#dataTableVent').dataTable({
      rowCallback:function(row,data) {
        if(data["estatus"] != '1')
        {
          /*$($(row).find("td")[3]).css("background-color","red");*/
          $($(row)).css("background-color","#DD4B39");
        }
      },  
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "listadoDataVent",
        'columns': [
            {"data": "ver"},         
            {"data": "fecha"},
            {"data": "caja"},
            {"data": "factura"},
            {"data": "forpago"},
            {"data": "mesa"},
            {"data": "cliente"}, 
            {"data": "vendedor"}, 
            {"data": "monto"},              
            {"data": "efectivo"},
            {"data": "cheque"},
            {"data": "tarjetac"},
            {"data": "tarjetad"},
            {"data": "tarjetap"},
            {"data": "transferencia"},
            {"data": "dinele"},
            {"data": "otros"},
            {"data": "anticipo"}
        ]

      });

  $.datepicker.setDefaults($.datepicker.regional["es"]);
  $('#desde').datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });
  $('#desde').on('changeDate', function(ev){
      $(this).datepicker('hide');
  });

  $('#hasta').datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });
  $('#hasta').on('changeDate', function(ev){
      $(this).datepicker('hide');
  });  

  /* ACTUALIZAR LISTADO DE VENTA POR RAGO DE FECHA */
  $('.actualiza').click(function(){
    var fhasta = $("#fhasta").val();
    var fdesde = $("#fdesde").val();
    var horah = $("#hhasta").val();
    var horad = $("#hdesde").val(); 
    var vendedor = $("#cmb_vendedor").val(); 
    var sucursal = $("#cmb_sucursal").val(); 
    var tipofecha = $("#tipofecha").val(); 
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Facturar/tmp_venta_fecha');?>",
          data: { fdesde:fdesde, horad:horad, fhasta:fhasta, horah:horah, 
                  vendedor: vendedor, sucursal: sucursal, tipofecha: tipofecha }
        }).done(function (result) {

              $('#dataTableVent').DataTable().ajax.reload();
              actualiza_venta();
        }); 
  });

    function actualiza_venta(){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Facturar/upd_venta_total",
            //data: { id: id },
            success: function(json) {
              var total = 0;

              if(json == null){
                total = 0;
              }else{
                total = json
              }
            
              $('#monto').html('<strong>$ '+total+'</strong>');
            }
        });

    }

    $(document).on('click', '.venta_print', function(){
      var imprimepdf = <?php print $facturapdf; ?>;
      var id = $(this).attr('id');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/tipdoc');?>",
        data: { id:id, },
        success: function(json) {
          var tipodoc = json;
          if(imprimepdf == 1){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: base_url + "Facturar/nrofactura_tmp",
                data: { id: id },
                success: function(json) {
                  $.fancybox.open({
                    type:'iframe',
                    width: 800,
                    height: 550,
                    ajax: {
                       dataType: "html",
                       type: "POST",
                       data: {id: id}
                    },
                    href: base_url + 'Facturar/facturapdf' 
                  });
                }
            });
          }else 
            if(imprimepdf == 2){
              $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                  dataType: "html",
                  type: "POST",
                  data: {id: id}
                },
                href: "<?php echo base_url('Facturar/imprimirventaticket');?>" 
              });        
            } else {
              if(tipodoc == 2){
                $.fancybox.open({
                  type: "ajax",
                  width: 550,
                  height: 550,
                  ajax: {
                    dataType: "html",
                    type: "POST",
                    data: {id: id}
                  },
                  href: "<?php echo base_url('Facturar/imprimirventaticket');?>" 
                });        
              
              }else{
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: base_url + "Facturar/nrofactura_tmp",
                    data: { id: id },
                    success: function(json) {
                      $.fancybox.open({
                        type:'iframe',
                        width: 800,
                        height: 550,
                        ajax: {
                           dataType: "html",
                           type: "POST",
                           data: {id: id}
                        },
                        href: base_url + 'Facturar/facturapdf' 
                      });
                    }
                });
              }
            }
        }
      });
    });


    /* Reporte de Venta */
    $(document).on('click', '#rpt_venta', function(){  
      var fhasta = $("#fhasta").val();
      var fdesde = $("#fdesde").val();
      var horah = $("#hhasta").val();
      var horad = $("#hdesde").val();     
      var vendedor = $("#cmb_vendedor").val();     
      var sucursal = $("#cmb_sucursal").val(); 
      var tipofecha = $("#tipofecha").val(); 
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/tmp_venta_fecha');?>",
        data: { fdesde:fdesde, horad:horad, fhasta:fhasta, horah:horah, 
                vendedor:vendedor, sucursal: sucursal, tipofecha: tipofecha },
        success: function(json) {
          window.open('<?php print $base_url;?>Facturar/reporte');
        }
      });    
    });




    /* Reporte de Venta con Tarjeta */
    $(document).on('click', '#rpt_tarjeta', function(){    
      var fhasta = $("#fhasta").val();
      var fdesde = $("#fdesde").val();
      var horah = $("#hhasta").val();
      var horad = $("#hdesde").val();     
      var vendedor = $("#cmb_vendedor").val();     
      var sucursal = $("#cmb_sucursal").val(); 
      var tipofecha = $("#tipofecha").val(); 

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/tmp_venta_fecha');?>",
        data: { fdesde:fdesde, horad:horad, fhasta:fhasta, horah:horah, 
                vendedor:vendedor, sucursal: sucursal, tipofecha: tipofecha },
        success: function(json) {
          window.open('<?php print $base_url;?>Facturar/reportetarjeta');
        }
      });    
    });

    /* Reporte de Detalles de Venta */
    $(document).on('click', '#rpt_detalle', function(){  
      var fhasta = $("#fhasta").val();
      var fdesde = $("#fdesde").val();
      var horah = $("#hhasta").val();
      var horad = $("#hdesde").val();     
      var vendedor = $("#cmb_vendedor").val();     
      var sucursal = $("#cmb_sucursal").val(); 
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/tmp_venta_fecha');?>",
        data: { fdesde:fdesde, horad:horad, fhasta:fhasta, horah:horah, 
                vendedor:vendedor, sucursal: sucursal },
        success: function(json) {
          window.open('<?php print $base_url;?>Facturar/reportedetalle');
        }
      });    
    });

    /* ANULAR FACTURA */
    $(document).on('click', '.anu_fact', function(){
      var id = $(this).attr('id');
      $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST",
                   data: {id: id}
                },
                href: "<?php echo base_url('Facturar/anular_factura');?>",
                 success: function(json) {
                  $.fancybox.close();
                 }
              });
    });

    /* EDITAR FACTURA */
    $(document).on('click', '.edi_fact', function(){
      var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {id: id},
            url: base_url + "Facturar/tmp_factura",
            success: function(json) {

              if(json.resu > 0){
                location.replace("<?php print $base_url;?>Facturar/editar_factura");
              }else{
                alert("ERROR.");
              }
            }
        });
    });

    $(document).on('click', '.ret_comp', function(){
      var id = $(this).attr('id');
      var consfinal = $(this).attr('name');

      if (consfinal == '1'){
        alert("Para registrar la retencion la factura debe estar asociada a un cliente.");
        return false;
      }
      
      $.ajax({
         type: "POST",
         dataType: "json",
         url: "<?php print $base_url;?>Facturar/temp_ventaret",
         data: {id: id},
         success: function(json) {
            if (parseInt(json.resu) > 0) {
               location.replace("<?php print $base_url;?>Facturar/venta_retencion");
            } else {
               alert("Error de conexión");
            }
         }
      }); 
    })

    $(document).on('click','.pdf_garantia', function(){
      var imprimepdf = <?php print $facturapdf; ?>;
      var id = $(this).attr('id');
      if (imprimepdf == 1){
        $.ajax({
          type: "POST",
          dataType: "json",
          data: {id: id},
          url: base_url + "Facturar/tmp_garfactura",
          success: function(json) {
            window.open(base_url + 'Facturar/garantiapdf', '_blank');
          }
        });
      }
      else{
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
            dataType: "html",
            type: "POST",
            data: {id: id}
          },
          href: "<?php echo base_url('Facturar/garantiatxt');?>" 
        });        

      }        
    });    
  

    $.datepicker.setDefaults($.datepicker.regional["es"]);
    $('#cli_desde').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
      });
    $('#cli_desde').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    $('#cli_hasta').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
      });
    $('#cli_hasta').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });  

    /* BUSQUEDA DINAMICA POR CEDULA */
    $('#txt_nro_ident').blur(function(){
      var idcliente = $(this).val();    
      if (idcliente === ""){
        //alert("Debe ingresar un numero de Identificación");
        return false;
      }   
      carga_clienteid(idcliente);
    });

    function carga_clienteid(idcliente){
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Facturar/valcliente');?>",
          data: {
              idcliente: idcliente
           },
          success: function(json) {
            if(json.mens == null){ 
              $('#menid').attr('class','col-sm-8 has-error');
              $('#mennom').attr('class','col-sm-10 has-error');
              $('#txt_clinom').val('');
              $('#txt_clid').val('');                             
            /*  $('#txt_telf').val('');
              $('#txt_correo').val('');
              $('#txt_dir').val(''); 
              $('#txt_ciudad').val('');*/
            }
            else {          
              $('#txt_clinom').val(json.mens.nom_cliente);
              $('#txt_clid').val(json.mens.id_cliente);
              $('#mennom').attr('class','col-sm-10 has-success'); 
              $('#menid').attr('class','col-sm-8 has-success');
              /*$('#txt_telf').val(json.mens.telefonos_cliente);
              $('#txt_correo').val(json.mens.correo_cliente);
              $('#txt_dir').val(json.mens.direccion_cliente); 
              $('#txt_ciudad').val(json.mens.ciudad_cliente);*/
            }
          }
      });      
    }

    $('.autocomplete').autocomplete();

    var autoparent = "";

    $('#txt_clinom').focus(function(){
      autoparent = "cliente";    
    });

    $('#txt_nombreproducto').focus(function(){
      autoparent = "producto";    
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
        autocomplete_producto(nom);
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
          url: "<?php echo base_url('Facturar/busca_nombre');?>",
          data: {
              nom: nom
           },
          success: function(json) {
              $('#txt_clid').val(json.mens.id_cliente);
              $('#txt_nro_ident').val(json.mens.ident_cliente);
              $('#txt_clinom').val(json.mens.nom_cliente);
         //     $('#mennom').attr('class','col-sm-10 has-success'); 
         //     $('#menid').attr('class','col-sm-8 has-success');
          }
      });

    }

    $('#dataTableventacliente').dataTable({
      rowCallback:function(row,data) {
        if(data["estatus"] != '1')
        {
          /*$($(row).find("td")[3]).css("background-color","red");*/
          $($(row)).css("background-color","#DD4B39");
        }
      },  
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "listadoDataventacliente",
        'columns': [
            {"data": "ver"},         
            {"data": "fecha"},
            {"data": "factura"},
            {"data": "tipocancel"},
            {"data": "producto"}, 
            {"data": "cantidad"}, 
            {"data": "precio"},              
            {"data": "descsubtotal"},
            {"data": "descmonto"},
            {"data": "montoiva"},
            {"data": "valortotal"},
            {"data": "cliente"},
            {"data": "direccion"},
            {"data": "correo"},
            {"data": "telefono"}
        ]

    });

    $('#dataTableclienteproducto').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "listadoDataclienteproducto",
        'columns': [
            {"data": "producto"}, 
            {"data": "cantidad"}, 
            {"data": "precio"},              
            {"data": "descsubtotal"},
            {"data": "descmonto"},
            {"data": "montoiva"},
            {"data": "valortotal"}
        ]

    });

    $('.cli_actualiza').click(function(){
      var categoria = $("#cmb_categcliente").val();
      var sucursal = $("#cmb_sucursalcli").val();
      var hasta = $("#cli_hasta").val();
      var desde = $("#cli_desde").val();
      var cliente = $("#txt_clid").val(); 
          $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('Facturar/tmp_ventacli_fecha');?>",
            data: { desde: desde, hasta: hasta, cliente: cliente, sucursal: sucursal, categoria: categoria }
          }).done(function (result) {
            $('#dataTableventacliente').DataTable().ajax.reload();
            $('#dataTableclienteproducto').DataTable().ajax.reload();
          }); 
    });

    $('#cmb_categcliente').click(function(){
      var categoria = $("#cmb_categcliente").val();
      var sucursal = $("#cmb_sucursalcli").val();
      var hasta = $("#cli_hasta").val();
      var desde = $("#cli_desde").val();
      $("#txt_clid").val(0); 
      $('#txt_nro_ident').val('');
      $('#txt_clinom').val('');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/tmp_ventacli_fecha');?>",
        data: { desde: desde, hasta: hasta, cliente: 0, sucursal: sucursal, categoria: categoria }
      }).done(function (result) {
        $('#dataTableventacliente').DataTable().ajax.reload();
        $('#dataTableclienteproducto').DataTable().ajax.reload();
      }); 
    });


    $('#pro_desde').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
      });
    $('#pro_desde').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    $('#pro_hasta').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
      });
    $('#pro_hasta').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });  

    /* BUSQUEDA DINAMICA POR codigo de producto */
    $('#txt_codigoproducto').blur(function(){
      var codpro = $(this).val();    
      if (codpro === ""){
        return false;
      }   
      carga_producto_codigo(codpro);
    });

    function carga_producto_codigo(codigo){
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Producto/sel_pro_codigos');?>",
          data: { codigo: codigo },
          success: function(json) {
            if(json.resu == null){ 
              $('#txt_nombreproducto').val('');
              $('#txt_idproducto').val('');                             
            }
            else {          
              $('#txt_nombreproducto').val(json.resu.pro_nombre);
              $('#txt_idproducto').val(json.resu.pro_id);
            }
          }
      });      
    }

    function autocomplete_producto(nom){
      if (nom === ""){
        alert("Debe ingresar un nombre");
        return false;
      }
      tmpnom = nom;
      pos = tmpnom.search(' - ');
      nom = tmpnom.substring(pos+3);      
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Producto/busca_producto_nombre');?>",
          data: {
              nom: nom
           },
          success: function(json) {
              $('#txt_codigoproducto').val(json.pro_codigobarra);
              $('#txt_idproducto').val(json.pro_id);
          }
      });

    }

    $('#dataTableventaproducto').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "listadoDataventaproducto",
        'columns': [
            {"data": "ver"},         
            {"data": "fecha"},
            {"data": "factura"},
            {"data": "tipocancel"},
            {"data": "producto"}, 
            {"data": "cantidad"}, 
            {"data": "precio"},              
            {"data": "tipoprecio"},              
            {"data": "descsubtotal"},
            {"data": "descmonto"},
            {"data": "montoiva"},
            {"data": "valortotal"},
            {"data": "cliente"},
            {"data": "telefono"},
            {"data": "correo"},
            {"data": "direccion"}
        ]

    });

    $('.pro_actualiza').click(function(){    
      var sucursal = $("#cmb_sucursalpro").val();
      var hasta = $("#pro_hasta").val();
      var desde = $("#pro_desde").val();
      var producto = $("#txt_idproducto").val(); 
      var categoria = $("#cmb_categoria").val();
      var docheck = $('#chk_categoria').prop('checked');
      if (docheck == true) { docheck = 1;} else{ docheck = 0;}
      $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('Facturar/tmp_ventapro_fecha');?>",
            data: { sucursal: sucursal, desde: desde, hasta: hasta, 
                    producto: producto, categoria: categoria, todos: docheck }
      }).done(function (result) {
           $('#dataTableventaproducto').DataTable().ajax.reload();
      }); 
    });

    $('#cmb_categoria').change(function(){
      var sucursal = $("#cmb_sucursalpro").val();
      var categoria = $(this).val();
      var hasta = $("#pro_hasta").val();
      var desde = $("#pro_desde").val();
      var producto = 0; 

      $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('Facturar/tmp_ventapro_fecha');?>",
            data: { sucursal: sucursal, desde: desde, hasta: hasta, producto: producto,
                    categoria: categoria, todos: 0 }
      }).done(function (result) {
            $('#dataTableventaproducto').DataTable().ajax.reload();
      });         
    });
  
    $('#chk_categoria').click(function(){
      var sucursal = $("#cmb_sucursalpro").val();
      var hasta = $("#pro_hasta").val();
      var desde = $("#pro_desde").val();
      var categoria = $("#cmb_categoria").val();
      var producto = 0; 

      var docheck = $('#chk_categoria').prop('checked');
      if (docheck == true){
          $('#txt_codigoproducto').val('');
          $('#txt_nombreproducto').val('');
      }
      $('#txt_idproducto').val(0);
      var producto = $("#txt_idproducto").val(); 
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/tmp_ventapro_fecha');?>",
        data: { sucursal: sucursal, desde: desde, hasta: hasta, producto: producto,
                categoria: categoria, todos: 0 }
      }).done(function (result) {
        $('#dataTableventaproducto').DataTable().ajax.reload();
      }); 
          
    });


    $('#ven_desde').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
      });
    $('#ven_desde').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    $('#ven_hasta').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
      });
    $('#ven_hasta').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });  

    $('#dataTableVendedorResumenCliente').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "listadoDataVendedorResumenCliente",
        'columns': [
            {"data": "cliente"},
            {"data": "facturas","sClass": "dt-alignright"},
            {"data": "subsiniva","sClass": "dt-alignright"},
            {"data": "subconiva","sClass": "dt-alignright"},
            {"data": "descmonto","sClass": "dt-alignright"},
            {"data": "montoiva","sClass": "dt-alignright"},
            {"data": "montototal","sClass": "dt-alignright"},
            {"data": "tipoprecio"},
            {"data": "telefono"},
            {"data": "correo"},
            {"data": "direccion"}
        ]
    });

    $('#dataTableVendedorResumenProducto').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "listadoDataVendedorResumenProducto",
        'columns': [
            {"data": "producto"}, 
            {"data": "unidadmedida"}, 
            {"data": "cantidad","sClass": "dt-alignright"}, 
            {"data": "precio","sClass": "dt-alignright"},              
            {"data": "tipoprecio"},              
            {"data": "descsubtotal","sClass": "dt-alignright"},
            {"data": "descmonto","sClass": "dt-alignright"},
            {"data": "montoiva","sClass": "dt-alignright"},
            {"data": "valortotal","sClass": "dt-alignright"}
        ]
    });

    $('#dataTableVendedorResumenVendedor').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "listadoDataVendedorResumenVendedor",
        'columns': [
            {"data": "vendedor"},
            {"data": "facturas","sClass": "dt-alignright"},
            {"data": "subsiniva","sClass": "dt-alignright"},
            {"data": "subconiva","sClass": "dt-alignright"},
            {"data": "descmonto","sClass": "dt-alignright"},
            {"data": "montoiva","sClass": "dt-alignright"},
            {"data": "montototal","sClass": "dt-alignright"},
            {"data": "tipoprecio"}
        ]
    });

    $('.ven_actualiza').click(function(){    
      var sucursal = $("#cmb_sucursalven").val();
      var vendedor = $("#cmb_vendedorven").val();
      var hasta = $("#ven_hasta").val();
      var desde = $("#ven_desde").val();
      $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('Facturar/tmp_ventaven_fecha');?>",
            data: { sucursal: sucursal, desde: desde, hasta: hasta, 
                    vendedor: vendedor }
      }).done(function (result) {
           $('#dataTableVendedorResumenCliente').DataTable().ajax.reload();
           $('#dataTableVendedorResumenProducto').DataTable().ajax.reload();
           $('#dataTableVendedorResumenVendedor').DataTable().ajax.reload();
      }); 
    });


    cliente = <?php if (@$cliente != NULL) {print @$cliente;} else {print 0;} ?>;
    if (cliente != ''){
      carga_clienteid(cliente);
    }

}); 



</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-list-alt"></i> Listado de Ventas
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>facturar/ventas">Ventas</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DE LOS PRODUCTOS A COMPRAR -->
      <div class="col-md-12">
        <div class="box box-danger">

          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li class="active"><a href="#tabventageneral" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Listado de Ventas</a></li>                            
             <li ><a href="#tabventacliente" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Ventas por Clientes</a></li>                            
             <li ><a href="#tabventaproducto" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Ventas por Productos</a></li>                            
             <li ><a href="#tabventavendedor" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Ventas por Vendedor</a></li>                            
            </ul>

            <div class="tab-content">

              <div class="tab-pane active" id="tabventageneral">

                <div class="box-header with-border">

                    <div  class="form-group col-md-12" style="padding-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                      <div class="col-md-4">
                        <label class="col-md-3" for="lb_res">Sucursal</label>
                        <div class="col-md-9">
                          <select id="cmb_sucursal" name="cmb_sucursal" class="form-control actualiza">
                            <?php
                              if (count($sucursales) > 0) {
                                foreach ($sucursales as $obj):
                                    if(@$sucursal != NULL){
                                        if($obj->id_sucursal == $sucursal){ ?>
                                            <option  value="<?php  print $obj->id_sucursal; ?>" selected="TRUE"><?php  print $obj->nom_sucursal; ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $obj->id_sucursal; ?>"> <?php  print $obj->nom_sucursal; ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $obj->id_sucursal; ?>"> <?php  print $obj->nom_sucursal; ?> </option>
                                        <?php
                                        }                                 
                                endforeach;
                              }
                              ?>                    
                          </select>          
                        </div>  
                      </div>  

                      <div class="form-group col-md-3">
                        <label class="col-md-4" for="lb_res">Vendedor</label>
                        <div class="col-md-8">
                          <select id="cmb_vendedor" name="cmb_vendedor" class="form-control actualiza">
                            <option  value="0" selected="TRUE">TODOS</option>
                            <?php
                              if (count($vendedores) > 0) {
                                foreach ($vendedores as $obj):
                            ?>
                                  <option value="<?php  print $obj->id_usu; ?>" > <?php  print $obj->vendedor; ?> </option>
                            <?php
                                endforeach;
                              }
                            ?>
                          </select>          
                        </div>
                      </div>

                      <div class="col-md-1" >
                        <button type="button" class="btn btn-block btn-success actualiza"><i class="fa fa-search" aria-hidden="true"></i></button>
                      </div>

                      <div class="col-md-2" style="margin-bottom: 0px; margin-top: 10px; ">
                        <h4 style="margin-bottom: 0px; margin-top: 0px;"><div id="monto"> Monto: <?php print number_format(@$monto,2,",","."); ?></div></h4>
                      </div>  

                      <div class="btn-group pull-right" style="color: white; ">
                        <button type="button" class="btn bg-blue"><i class="fa fa-list" aria-hidden="true"></i> Reportes</button>
                        <button type="button" class="btn bg-blue dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                          <span class="sr-only"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                          <li><a id="rpt_venta" class="btn-primary" style="color: black;" href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i> Todas las Ventas</a></li>
                          <li><a id="rpt_tarjeta" class="btn-primary" style="color: black;" href="#"><i class="fa fa-credit-card" aria-hidden="true"></i>Ventas con Tarjetas</a></li>
                          <li><a id="rpt_detalle" class="btn-primary" style="color: black;" href="#"><i class="fa fa-credit-card" aria-hidden="true"></i>Detalles de Ventas</a></li>
                        </ul>
                      </div>

                    </div>  
                  
                    <div id="buscrango" class="form-group col-md-12" style="padding-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                      <div class="form-group col-md-3" >
                        <label class="col-md-5" for="">Desde</label>
                        <div class="input-group col-md-7">
                          <input  type="text" class="form-control text-center date start" id="fdesde" name="fdesde" value="<?php if (@$desde != NULL) { @$fec = str_replace('-', '/', @$desde); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print  date("d/m/Y"); } ?>">
                        </div>
                      </div>              
                      <div class="form-group col-md-2" >              
                        <label class="col-md-5" for="">Hora</label>
                        <div class="input-group col-md-7">
                          <input  type="text" class="form-control text-center time start" id="hdesde" name="hdesde" value="00:00:00">
                        </div>
                      </div> 
                      <div class="form-group col-md-3" >
                        <label class="col-md-5" for="">Hasta</label>
                        <div class="input-group col-md-7">
                          <input type="text" class="form-control text-center date end" id="fhasta" name="fhasta" value="<?php if (@$hasta != NULL) { @$fec = str_replace('-', '/', @$hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print  date("d/m/Y"); }  ?>">
                        </div>
                      </div>              
                      <div class="form-group col-md-2" >              
                        <label class="col-md-5" for="">Hora</label>
                        <div class="input-group col-md-7">
                          <input  type="text" class="form-control text-center time end" id="hhasta" name="hhasta" value="23:59:59">
                        </div>
                      </div> 

                      <div class="form-group col-md-2" style="padding: 0px;">              
                        <label class="col-md-5" for="">Fecha</label>
                        <div class="input-group col-md-7">
                          <select id="tipofecha" name="tipofecha" class="form-control ">
                            <option  value="1" <?php if (@$tipofecha == 1) { print "selected='TRUE'"; } ?> >Registro</option>
                            <option  value="2" <?php if (@$tipofecha == 2) { print "selected='TRUE'"; } ?>>Emisión</option>
                          </select>          
                        </div> 
                      </div> 


                    </div>


                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="box">
                        <div id="upd_tbventa" class="box-body table-responsive">

                          <table id="dataTableVent" class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr >
                                  <th class="text-center col-md-1">Acción</th>                            
                                  <th class="text-center col-md-1">Fecha</th>  
                                  <th class="text-center col-md-1">Caja</th>
                                  <th class="text-center col-md-1">Factura</th>
                                  <th class="text-center col-md-1">Tipo</th>
                                  <th class="text-center col-md-1">Pto Venta</th>
                                  <th>Cliente</th>
                                  <th>Vendedor</th>
                                  <th class="text-center col-md-1">Monto</th>                            
                                  <th class="text-center col-md-1">Efectivo</th>
                                  <th class="text-center col-md-1">Cheque</th>
                                  <th class="text-center col-md-1">Tar.Credito</th>
                                  <th class="text-center col-md-1">Tar.Debito</th>
                                  <th class="text-center col-md-1">Tar.Prepago</th>
                                  <th class="text-center col-md-1">Transferencia</th>
                                  <th class="text-center col-md-1">Din.Elect</th>
                                  <th class="text-center col-md-1">Otros</th>
                                  <th class="text-center col-md-1">Anticipo</th>
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

              </div> <!--/tab-->

              <div class="tab-pane" id="tabventacliente">

                <div class="box-header with-border">  
                
                  <div class="form-group col-md-12" style="padding-top: 0px; ">

                    <div class="col-md-12">

                      <div class="col-md-3" style="padding-left: 0px; margin-left: 0px;">
                        <label class="col-md-4 text-left control-label" for="lb_res">Sucursal</label>
                        <div class="col-md-8">
                          <select id="cmb_sucursalcli" name="cmb_sucursalcli" class="form-control ">
                            <?php
                              if (count($sucursales) > 0) {
                                foreach ($sucursales as $obj):
                                    if(@$sucursal != NULL){
                                        if($obj->id_sucursal == $sucursal){ ?>
                                            <option  value="<?php  print $obj->id_sucursal; ?>" selected="TRUE"><?php  print $obj->nom_sucursal; ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $obj->id_sucursal; ?>"> <?php  print $obj->nom_sucursal; ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $obj->id_sucursal; ?>"> <?php  print $obj->nom_sucursal; ?> </option>
                                        <?php
                                        }                                 
                                endforeach;
                              }
                              ?>                    
                          </select>          
                        </div>  
                      </div>  

                      <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                        <label for="" class="col-sm-4 control-label" style="padding-top: 10px; padding-right: 0px; padding-left: 0px;"># Ident</label>
                        <div class="col-sm-8" style="padding-left: 0px;padding-right: 0px;">
                          <input type="hidden" id="txt_clid" name="txt_clid" value="<?php if(@$cliente != NULL){ print @$cliente->id_cliente; }?>" >    
                          <input type="text" class="form-control " name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$cliente != NULL){ print @$cliente->nro_ident; }?>" >
                        </div>
                      </div>

                      <div class="form-group col-md-5" style="padding-right: 10px; padding-left: 0px; margin-left: 0px;">
                        <label for="" class="col-sm-2 control-label text-right" style="padding-top: 10px; padding-right: 0px; padding-left: 0px;">Cliente</label>
                        <div  class="col-sm-10 autocomplete" style="padding-right: 0px;">
                          <input type="text" class="form-control" name="txt_clinom" id="txt_clinom" placeholder="Nombre del Cliente" value="<?php if(@$cliente != NULL){ print @$cliente->nom_cliente; }?>" data-source="<?php echo base_url('facturar/valclientenombre?nombre=');?>">
                        </div>
                      </div>

                      <a class="btn btn-success btn-sm color-palette btn-grad pull-right" target="_blank" href="<?php print $base_url;?>facturar/reporteventaclienteXLS" data-original-title="" title="Exportar Detalle de Facturas"><i class="fa fa-file-excel-o fa-1x"></i> Detalle</a>

                    </div>  

                    <div class="form-group col-md-3" style="margin-bottom: 0px; ">
                      <label class="control-label text-left col-md-4" style="padding-top: 10px; padding-left: 0px;">Desde</label>
                      <div class="input-group date col-md-8">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" class="form-control pull-right " id="cli_desde" value="<?php if (@$desde != NULL) { $fec =  str_replace('-', '/', $desde); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec;} ?>">
                      </div>
                    </div> 

                    <div class="form-group col-md-3" style="margin-bottom: 0px; ">
                      <label class="control-label col-md-3" style="padding-top: 10px; padding-left: 0px;">Hasta</label>
                      <div class="input-group date col-md-9">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" class="form-control pull-right " id="cli_hasta" value="<?php if (@$hasta != NULL) { $fec =  str_replace('-', '/', $hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec;} ?>" style="padding-right: 0px;">

                        <span class="input-group-btn">
                          <button class="btn btn-success btn-flat cli_actualiza" type="button" title="Actualizar"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                        </span>

                      </div>
                    </div>

                    <div class="col-md-4" style="padding-left: 0px; margin-left: 0px;">
                      <label class="col-md-3 text-left control-label" for="lb_res">Categoría</label>
                      <div class="col-md-9">
                        <select id="cmb_categcliente" name="cmb_categcliente" class="form-control ">
                          <option  value="0" selected="TRUE">Seleccione...</option>
                          <?php
                            if (count($categcliente) > 0) {
                              foreach ($categcliente as $obj):
                                  if(@$tmp_categcliente != NULL){
                                      if($obj->id == $tmp_categcliente){ ?>
                                          <option  value="<?php  print $obj->id; ?>" selected="TRUE"><?php  print $obj->categoria; ?></option> 
                                          <?php
                                      }else{ ?>
                                          <option value="<?php  print $obj->id; ?>"> <?php  print $obj->categoria; ?> </option>
                                          <?php
                                      }
                                  }else{ ?>
                                      <option value="<?php  print $obj->id; ?>"> <?php  print $obj->categoria; ?> </option>
                                      <?php
                                      }                                 
                              endforeach;
                            }
                            ?>                    
                        </select>          
                      </div>  
                    </div>  

                    <a class="btn btn-success btn-sm color-palette btn-grad pull-right" target="_blank" href="<?php print $base_url;?>facturar/reportecliente_resumenproductoXLS" data-original-title="" title="Exportar Resumen de Productos"><i class="fa fa-file-excel-o fa-1x"></i> Resumen</a>

                  </div>  
                </div>  

                <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs">
                   <li class="active"><a href="#tabclientefactura" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Detalle de Facturas</a></li>                            
                   <li ><a href="#tabclienteproducto" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Resumen de Productos</a></li>                            
                  </ul>

                  <div class="tab-content">

                    <div class="tab-pane active" id="tabclientefactura">

                      <div class="box-body">
                        <div class="row">
                          <div class="col-xs-12">
                            <div class="box">
                              <div id="upd_tbventa" class="box-body table-responsive">

                                <table id="dataTableventacliente" class="table table-bordered table-hover table-responsive">
                                  <thead>
                                    <tr >
                                        <th class="text-center col-md-1">Acción</th>                            
                                        <th class="text-center col-md-1">Fecha</th>  
                                        <th class="text-center col-md-1">Factura</th>
                                        <th class="text-center col-md-1">Tipo</th>
                                        <th>Producto</th>
                                        <th class="text-center col-md-1">Cantidad</th>                            
                                        <th class="text-center col-md-1">Precio</th>
                                        <th class="text-center col-md-1">Subtotal</th>
                                        <th class="text-center col-md-1">Descuento</th>
                                        <th class="text-center col-md-1">Monto IVA</th>
                                        <th class="text-center col-md-1">Total</th>
                                        <th class="text-center col-md-1">Cliente</th>
                                        <th class="text-center col-md-1">Dirección</th>
                                        <th class="text-center col-md-1">Correo</th>
                                        <th class="text-center col-md-1">Teléfono</th>
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
                      </div>  <!--box-body-->              
                  
                    </div>

                    <div class="tab-pane" id="tabclienteproducto">

                      <div class="box-body">
                        <div class="row">
                          <div class="col-xs-12">
                            <div class="box">
                              <div id="upd_tbventa" class="box-body table-responsive">

                                <table id="dataTableclienteproducto" class="table table-bordered table-hover table-responsive">
                                  <thead>
                                    <tr >
                                        <th>Producto</th>
                                        <th class="text-center col-md-1">Cantidad</th>                            
                                        <th class="text-center col-md-1">Precio</th>
                                        <th class="text-center col-md-1">Subtotal</th>
                                        <th class="text-center col-md-1">Descuento</th>
                                        <th class="text-center col-md-1">Monto IVA</th>
                                        <th class="text-center col-md-1">Total</th>
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
                      </div>  <!--box-body-->              
                  
                    </div>

                  </div>
                </div>    
              </div> <!--/tab-->

              <div class="tab-pane" id="tabventaproducto">

                <div class="box-header with-border">  
                
                  <div class="form-group col-md-12" style="padding-top: 0px; ">

                    <div class="col-md-12">

                      <div class="col-md-3" style="padding-left: 0px; margin-left: 0px;">
                        <label class="col-md-4 text-left control-label" for="lb_res">Sucursal</label>
                        <div class="col-md-8">
                          <select id="cmb_sucursalpro" name="cmb_sucursalpro" class="form-control pro_actualiza">
                            <?php
                              if (count($sucursales) > 0) {
                                foreach ($sucursales as $obj):
                                    if(@$sucursal != NULL){
                                        if($obj->id_sucursal == $sucursal){ ?>
                                            <option  value="<?php  print $obj->id_sucursal; ?>" selected="TRUE"><?php  print $obj->nom_sucursal; ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $obj->id_sucursal; ?>"> <?php  print $obj->nom_sucursal; ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $obj->id_sucursal; ?>"> <?php  print $obj->nom_sucursal; ?> </option>
                                        <?php
                                        }                                 
                                endforeach;
                              }
                              ?>                    
                          </select>          
                        </div>  
                      </div>  

                      <div class="form-group col-md-3" style="padding-left: 0px; margin-left: 0px;">
                        <label for="" class="col-sm-4 control-label" style="padding-top: 10px; padding-right: 0px; padding-left: 0px;">Código</label>
                        <div  class="col-sm-8" style="padding-left: 0px;padding-right: 0px;">
                          <input type="hidden" id="txt_idproducto" name="txt_idproducto" value="" >    
                          <input type="text" class="form-control " name="txt_codigoproducto" id="txt_codigoproducto" placeholder="Código de Producto" value="" >
                        </div>
                      </div>

                      <div class="form-group col-md-4" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                        <label for="" class="col-sm-2 control-label text-right" style="padding-top: 10px; padding-right: 0px; padding-left: 0px;">Producto</label>
                        <div  class="col-sm-10 autocomplete" style="padding-right: 0px;">
                          <input type="text" class="form-control" name="txt_nombreproducto" id="txt_nombreproducto" placeholder="Nombre del Producto" value="" data-source="<?php echo base_url('producto/valproductonombre?nombre=');?>">
                        </div>
                      </div>

                     <a class="btn btn-success btn-sm color-palette btn-grad pull-right" target="_blank" href="<?php print $base_url;?>facturar/reporteventaproductoXLS" data-original-title="" title=""><i class="fa fa-file-excel-o fa-1x"></i> Exportar</a>

                    </div>

                    <div class="col-md-3" style="margin-bottom: 0px; ">
                      <label class="control-label text-left col-md-4" style="padding-top: 10px; padding-left: 0px;">Desde</label>
                      <div class="input-group date col-md-8">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" class="form-control pull-right " id="pro_desde" value="<?php if (@$desde != NULL) { $fec =  str_replace('-', '/', $desde); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec;} ?>">
                      </div>
                    </div> 

                    <div class="col-md-3" style="margin-bottom: 0px; ">
                      <label class="control-label col-md-3" style="padding-top: 10px; padding-left: 0px;">Hasta</label>
                      <div class="input-group date col-md-9">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" class="form-control pull-right " id="pro_hasta" value="<?php if (@$hasta != NULL) { $fec =  str_replace('-', '/', $hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec;} ?>" style="padding: 0px;">

                        <span class="input-group-btn">
                          <button class="btn btn-success btn-flat pro_actualiza" type="button"  title="Actualizar"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                        </span>

                      </div>
                    </div>

                    <div class="col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                        <label class="control-label col-md-3 text-left" for="lb_res" style="padding-top: 10px; padding-right: 0px; padding-left: 0px;">Categoría</label>
                        <div class="col-md-9">
                          <select id="cmb_categoria" name="cmb_categoria" class="form-control">
                            <?php
                              if (count($categorias) > 0) {
                                foreach ($categorias as $obj):
                            ?>
                                  <option  value="<?php  print $obj->cat_id; ?>" ><?php  print $obj->cat_descripcion; ?></option> 
                            <?php
                                endforeach;
                              }  
                            ?>                    
                          </select>          
                        </div>  
                    </div>  


                    <div class="col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                        <label class="col-md-12"><input type="checkbox" name="chk_categoria" id="chk_categoria" class="minimal-red" > Mostrar todos los productos</label>
                    </div>                             

                  </div>  
                </div>  

                <div class="box-body">
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="box">
                        <div id="upd_tbventa" class="box-body table-responsive">

                          <table id="dataTableventaproducto" class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr >
                                  <th class="text-center col-md-1">Acción</th>                            
                                  <th class="text-center col-md-1">Fecha</th>  
                                  <th class="text-center col-md-1">Factura</th>
                                  <th class="text-center col-md-1">Cancela</th>
                                  <th>Producto</th>
                                  <th class="text-center col-md-1">Cantidad</th>                            
                                  <th class="text-center col-md-1">Precio</th>
                                  <th class="text-center col-md-1">Tipo</th>
                                  <th class="text-center col-md-1">Subtotal</th>
                                  <th class="text-center col-md-1">Descuento</th>
                                  <th class="text-center col-md-1">Monto IVA</th>
                                  <th class="text-center col-md-1">Total</th>
                                  <th class="text-center col-md-1">Cliente</th>
                                  <th class="text-center col-md-1">Teléfono</th>
                                  <th class="text-center col-md-1">Correo</th>
                                  <th class="text-center col-md-1">Dirección</th>
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

              </div> <!--/tab-->

              <div class="tab-pane" id="tabventavendedor">

                <div class="box-header with-border">  
                
                  <div class="form-group col-md-12" style="padding-top: 0px; ">

                    <div class="col-md-12">

                      <div class="col-md-4" style="padding-left: 0px; margin-left: 0px;">
                        <label class="col-md-3 text-left control-label" for="lb_res">Sucursal</label>
                        <div class="col-md-9">
                          <select id="cmb_sucursalven" name="cmb_sucursalven" class="form-control pro_actualiza">
                            <?php
                              if (count($sucursales) > 0) {
                                foreach ($sucursales as $obj):
                                    if(@$sucursal != NULL){
                                        if($obj->id_sucursal == $sucursal){ ?>
                                            <option  value="<?php  print $obj->id_sucursal; ?>" selected="TRUE"><?php  print $obj->nom_sucursal; ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $obj->id_sucursal; ?>"> <?php  print $obj->nom_sucursal; ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $obj->id_sucursal; ?>"> <?php  print $obj->nom_sucursal; ?> </option>
                                        <?php
                                        }                                 
                                endforeach;
                              }
                              ?>                    
                          </select>          
                        </div>  
                      </div>  

                      <div class="col-md-4" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                        <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
                         <div class="col-md-3">
                          <label>Vendedor</label>
                         </div> 
                         <div class="col-md-9">
                          <select id="cmb_vendedorven" name="cmb_vendedorven" class="form-control">
                              <option  value="" selected="TRUE">Seleccione...</option>
                              <?php  
                                if (count($vendedores) > 0) {
                                  foreach ($vendedores as $vd):
                              ?>
                                    <option value="<?php  print $vd->id_usu; ?>"> <?php  print $vd->vendedor; ?> </option>
                              <?php
                                  endforeach;
                                }
                              ?>
                          </select>   
                         </div>
                        </div>
                      </div>            

                      <div class="pull-right">
                        <a class="btn btn-success btn-sm color-palette btn-grad" target="_blank" 
                          href="<?php print $base_url;?>facturar/reportevendedorclienteXLS" data-original-title="" title="Exportar Resumen por Clientes">
                          <i class="fa fa-file-excel-o fa-1x"></i> Clientes
                        </a>
                        <a class="btn btn-success btn-sm color-palette btn-grad" target="_blank" style="margin-left: 5px;"
                          href="<?php print $base_url;?>facturar/reportevendedorproductoXLS" data-original-title="" title="Exportar Resumen por Productos">
                          <i class="fa fa-file-excel-o fa-1x"></i> Productos
                        </a>
                        <a class="btn btn-success btn-sm color-palette btn-grad" target="_blank" style="margin-left: 5px;"
                          href="<?php print $base_url;?>facturar/reportevendedorresumenXLS" data-original-title="" title="Exportar Resumen por Vendedores">
                          <i class="fa fa-file-excel-o fa-1x"></i> Vendedores
                        </a>
                      </div>  

                    </div>  

                    <div class="col-md-3" style="margin-bottom: 0px; ">
                      <label class="control-label text-left col-md-4" style="padding-top: 10px; padding-left: 0px;">Desde</label>
                      <div class="input-group date col-md-8">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" class="form-control pull-right " id="ven_desde" value="<?php if (@$desde != NULL) { $fec =  str_replace('-', '/', $desde); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec;} ?>">
                      </div>
                    </div> 

                    <div class="col-md-3" style="margin-bottom: 0px; ">
                      <label class="control-label col-md-3" style="padding-top: 10px; padding-left: 0px;">Hasta</label>
                      <div class="input-group date col-md-9">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" class="form-control pull-right " id="ven_hasta" value="<?php if (@$hasta != NULL) { $fec =  str_replace('-', '/', $hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec;} ?>" style="padding: 0px;">

                        <span class="input-group-btn">
                          <button class="btn btn-success btn-flat ven_actualiza" type="button"  title="Actualizar"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                        </span>

                      </div>
                    </div>

                  </div>  

                </div>

                <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs">
                   <li class="active"><a href="#tabvendedorcliente" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Resumen por Clientes</a></li>                            
                   <li ><a href="#tabvendedorproducto" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Resumen de Productos</a></li>                            
                   <li ><a href="#tabvendedorvendedor" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Resumen de Vendedores</a></li>                            
                  </ul>

                  <div class="tab-content">

                    <div class="tab-pane active" id="tabvendedorcliente">

                      <div class="box-body">
                        <div class="row">
                          <div class="col-xs-12">
                            <div class="box">
                              <div class="box-body table-responsive">

                                <table id="dataTableVendedorResumenCliente" class="table table-bordered table-hover table-responsive">
                                  <thead>
                                    <tr >
                                        <th class="text-center col-md-1">Cliente</th>
                                        <th class="text-center col-md-1">#Facturas</th>
                                        <th class="text-center col-md-1">Subtotal 0%</th>
                                        <th class="text-center col-md-1">Subtotal <>0%</th>
                                        <th class="text-center col-md-1">Descuento</th>
                                        <th class="text-center col-md-1">Monto IVA</th>
                                        <th class="text-center col-md-1">Total</th>
                                        <th class="text-center col-md-1">Tipo Precio</th>
                                        <th class="text-center col-md-1">Teléfono</th>
                                        <th class="text-center col-md-1">Correo</th>
                                        <th class="text-center col-md-1">Dirección</th>
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
                      </div>  <!--box-body-->              
                  
                    </div>

                    <div class="tab-pane active" id="tabvendedorproducto">

                      <div class="box-body">
                        <div class="row">
                          <div class="col-xs-12">
                            <div class="box">
                              <div class="box-body table-responsive">

                                <table id="dataTableVendedorResumenProducto" class="table table-bordered table-hover table-responsive">
                                  <thead>
                                    <tr >
                                        <th class="text-center col-md-1">Producto</th>
                                        <th class="text-center col-md-1">U.M.</th>
                                        <th class="text-center col-md-1">Cantidad</th>
                                        <th class="text-center col-md-1">Precio</th>
                                        <th class="text-center col-md-1">Tipo</th>
                                        <th class="text-center col-md-1">Subtotal</th>
                                        <th class="text-center col-md-1">Descuento</th>
                                        <th class="text-center col-md-1">Monto IVA</th>
                                        <th class="text-center col-md-1">Total</th>
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
                      </div>  <!--box-body-->              
                  
                    </div>

                    <div class="tab-pane active" id="tabvendedorvendedor">

                      <div class="box-body">
                        <div class="row">
                          <div class="col-xs-12">
                            <div class="box">
                              <div class="box-body table-responsive">

                                <table id="dataTableVendedorResumenVendedor" class="table table-bordered table-hover table-responsive">
                                  <thead>
                                    <tr >
                                        <th class="text-center col-md-1">Vendedor</th>
                                        <th class="text-center col-md-1">#Facturas</th>
                                        <th class="text-center col-md-1">Subtotal 0%</th>
                                        <th class="text-center col-md-1">Subtotal <>0%</th>
                                        <th class="text-center col-md-1">Descuento</th>
                                        <th class="text-center col-md-1">Monto IVA</th>
                                        <th class="text-center col-md-1">Total</th>
                                        <th class="text-center col-md-1">Tipo Precio</th>
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
                      </div>  <!--box-body-->              
                  
                    </div>

                  </div> <!--/subtab-->
                </div>                    

              </div>  <!--/tab-->  



            </div><!--tab-content-->
          </div><!--nav-tabs-custom-->

          <div   align="center" class="box-footer">
            <hr class="linea"> 
              <div class="row" style="margin-top:20px">

              </div><!--/row-->

          </div>
        </div>

      </div>           
    </div>
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

