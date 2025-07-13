<?php

  $sistema = &get_instance();
  $sistema->load->model("Sistema_model");
  $nombresistema = $sistema->Sistema_model->sel_nombresistema();  

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php print @$nombresistema; ?> - Proforma</title>
  <link rel="shortcut icon" type="image/ico" href="<?php print $base_url; ?>public/img/log_ff_mod_web.png" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <script type="text/javascript">
  <?php // Declarar variable global base_url para que esté disponible en los documentos .js     ?>
      var base_url = '<?php print base_url(); ?>';
  </script>  
<!-- ====== INICIO DE CARGA DE LOS ESTILOS CSS ================================================================================= -->  
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php print $base_url; ?>assets/bootstrap/css/bootstrap.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/font-awesome/css/font-awesome.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/ionicons/css/ionicons.css"> 

  <!-- Theme style -->
  <link rel="stylesheet" href="<?php print $base_url; ?>assets/dist/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins -->
  <link rel="stylesheet" href="<?php print $base_url; ?>assets/dist/css/skins/_all-skins.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/datatables/dataTables.bootstrap.css">
  <!-- css Animaciones -->
  <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/css/animate.css">
  <?php // <!-- ESTILOS PERSONALIZADOS DEL DESARROLLADOR --> ?>
  <link rel="stylesheet" href="<?php print $base_url; ?>public/css/estilo.css" />
  <?php // <!-- FIN DE ESTILOS PERSONALIZADOS DEL DESARROLLADOR --> ?>
  <?php // <!-- ESTILOS DE VALIDACION --> ?>
  <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/validationengine/css/validationEngine.jquery.css" />
  <?php // <!-- FIN DE ESTILOS DE VALIDACION --> ?>
  <?php // <!-- REMODAL --> ?>
  <link rel="stylesheet" href="<?php print $base_url; ?>public/css/remodal/remodal.css">
  <link rel="stylesheet" href="<?php print $base_url; ?>public/css/remodal/remodal-default-theme.css">
  <?php // <!-- REMODAL --> ?>        
  <link rel="stylesheet" href="<?php  print $base_url; ?>assets/plugins/jQueryUI/jquery-ui.css" />
  <?php // <!-- bootstrap-toggle --> ?>        
   <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.css" />
<!-- ====== INICIO DE CARGA DE LAS LIBRERIAS JS ================================================================================= -->
  <!-- jQuery 3.1.1 -->
  <script src="<?php print $base_url; ?>assets/plugins/jQuery/jquery-3.1.1.min.js"></script>

  <!-- Bootstrap 3.3.7 -->
  <script src="<?php print $base_url; ?>assets/bootstrap/js/bootstrap.min.js"></script>

  <!-- AdminLTE App -->
  <script src="<?php print $base_url; ?>assets/dist/js/adminlte.min.js"></script>  
  <!-- bootstrap-toggle -->   
  <script src="<?php print $base_url; ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.js"></script> 
  <!-- jQuery UI 1.11.4 -->
  <script src="<?php print $base_url; ?>assets/plugins/jQueryUI/jquery-ui.min.js"></script>
  <!-- DataTables -->
  <script src="<?php  print $base_url; ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?php  print $base_url; ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script src="<?php print $base_url; ?>assets/plugins/js/jquery.maskedinput.js"></script>
  <?php // <!-- GLOBALES DEFINIDAS POR EL DESARROLLADOR --> ?>
  <script src="<?php print $base_url; ?>public/js/common.js"></script>
  <script src="<?php print $base_url; ?>public/js/login/login.js"></script>
  <script src="<?php print $base_url; ?>assets/plugins/js/autocomplete.jquery.js"></script>
  <?php // <!-- FIN DE GLOBALES DEFINIDAS POR EL DESARROLLADOR -->  */  ?>
  <!-- FANCYBOX -->
  <script type="text/javascript" src="<?php print $base_url; ?>assets/plugins/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
  <script type="text/javascript" src="<?php print $base_url; ?>assets/plugins/fancybox/jquery.fancybox.js?v=2.1.5"></script>
  <script type="text/javascript" src="<?php print $base_url; ?>assets/plugins/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
  <script type="text/javascript" src="<?php print $base_url; ?>assets/plugins/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
  <script type="text/javascript" src="<?php print $base_url; ?>assets/plugins/fancybox/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
  <link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/fancybox/jquery.fancybox.css?v=2.1.5" media="screen" />
  <link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
  <link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/fancybox/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />    
  <?php // <!-- INCLUDES PARA VALIDACION DE FORMULARIOS --> ?>
  <script src='<?php print $base_url; ?>assets/plugins/validationengine/js/jquery.validationEngine.js'></script>
  <script src='<?php print $base_url; ?>assets/plugins/validationengine/js/languages/jquery.validationEngine-es.js'></script>
  <script src='<?php print $base_url; ?>assets/plugins/jquery-validation-1.11.1/dist/jquery.validate.min.js'></script>
  <script src='<?php print $base_url; ?>assets/plugins/js/validationInit.js'></script>
  <?php // <!-- FIN DE INCLUDES PARA VALIDACION DE FORMULARIOS --> 

  $parametro = &get_instance();
  $parametro->load->model("Parametros_model");
  $pedidovista = $parametro->Parametros_model->sel_pedidovista();
  $codigocliente = $parametro->Parametros_model->sel_codigocliente();
  $descpro = $parametro->Parametros_model->sel_descpro();
  $tipodescprod = $parametro->Parametros_model->sel_tipodescuentoproducto();   

  $impresionpdf = $parametro->Parametros_model->sel_facturapdf();

  ?>

  <style type="text/css">
    .tomar{
      background: rgba(0, 0, 0, 0.2) none repeat scroll 0 0;
      border-radius: 2px 0 0 2px;
      display: block;
      float: left;
      height: 90px;
      text-align: center;
      width: 90px;    
    }

    .form-control, .input-group-addon{
      height: 25px;
      padding: 0px 5px;
      font-size: 11px;      
    }

    label{
      margin-bottom: 0px;
      font-size: 12px;
    }

    .box-header{
      padding-bottom: 0px;
    }

    .linea{
      border-width: 2px 0 0;
      margin-bottom: 20px;
      margin-top: 5px;
      border-color: currentcolor currentcolor;
    } 

    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
      padding: 4px;
      font-size: 12px;
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
    /* Al pasar por ensima de las sugerencias*/
    .autocomplete-jquery-item:hover{
        background-color: #E0F0FF;
        color:black;
    }

    .fontawesome-select {
        font-family: 'FontAwesome', 'Helvetica';
    }
    
  
  </style>

<script type="text/javascript">
  
  $( document ).ready(function() {

    $('#TableProducto').DataTable({
      'language': {
        'url': base_url + 'public/json/language.spanish.json'
      }
    });    

    $(document).on('click','#inicio', function(){
      location.replace(base_url + 'inicio');
    })    

    $(document).on('click','#nuevo', function(){
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Proforma/nuevo",
        success: function(json) {
          location.replace(base_url + 'Proforma/agregar');
        }    
      });
    })

    $(document).on('click','#listadoprof', function(){
      location.replace(base_url + 'Proforma');
    })



    $('#fecha').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
      });
    $('#fecha').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    $('#txt_nro_ident').change(function(){
      var idcliente = $(this).val();    
      if (idcliente === ""){
        alert("Debe ingresar un numero de Identificación");
        return false;
      }   
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Facturar/valcliente');?>",
          data: {
              idcliente: idcliente
           },
          success: function(json) {
            if(json.mens == null){ 
              $('#menid').attr('class','form-group col-md-2 has-error');
              $('#mennom').attr('class','autocomplete has-error');
              $('#txt_clinom').val('');
              $('#txt_telf').val('');
              $('#txt_correo').val('');
              $('#txt_dir').val(''); 
              $('#txt_ciudad').val('');
              $('#txt_clid').val('0');                             
              $('#txt_codigocliente').val('');
            }
            else { 

              $('#txt_clinom').val(json.mens.nom_cliente);
              $('#txt_clid').val(json.mens.id_cliente);
              $('#mennom').attr('class','autocomplete has-success'); 
              $('#menid').attr('class','form-group col-md-2 has-success');
              $('#txt_telf').val(json.mens.telefonos_cliente);
              $('#txt_correo').val(json.mens.correo_cliente);
              $('#txt_dir').val(json.mens.direccion_cliente); 
              $('#txt_ciudad').val(json.mens.ciudad_cliente);
              $('#txt_codigocliente').val(json.mens.codigo);

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

    $('#txt_codigocliente').blur(function(){
      var codigo = $(this).val();    
      if (codigo.trim() == ""){
        return false;
      }   
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Facturar/get_clientecodigo');?>",
          data: {
              codigo: codigo
           },
          success: function(json) {
            if(json.mens == null){ 
              $('#menid').attr('class','form-group col-md-2 has-error');
              $('#mennom').attr('class','autocomplete has-error');
              $('#txt_clinom').val('');
              $('#txt_telf').val('');
              $('#txt_correo').val('');
              $('#txt_dir').val(''); 
              $('#txt_ciudad').val('');
              $('#txt_clid').val('0');                             
            }
            else { 
              $('#txt_nro_ident').val(json.mens.ident_cliente);
              $('#txt_clinom').val(json.mens.nom_cliente);
              $('#txt_clid').val(json.mens.id_cliente);
              $('#mennom').attr('class','autocomplete has-success'); 
              $('#menid').attr('class','form-group col-md-2 has-success');
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

    $(document).on('click', '.autocomplete-jquery-item', function(){  
      var nom = $(this).text(); 
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
              $('#txt_idcli').val(json.mens.id_cliente);
              $('#mennom').attr('class','autocomplete has-success'); 
              $('#menid').attr('class','form-group col-md-2 has-success');
              $('#txt_telf').val(json.mens.telefonos_cliente);
              $('#txt_correo').val(json.mens.correo_cliente);
              $('#txt_dir').val(json.mens.direccion_cliente); 
              $('#txt_ciudad').val(json.mens.ciudad_cliente);    
              $('#txt_codigocliente').val(json.mens.codigo);
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
    });
/*
    $(document).on('click', '.idpropre', function(){
      var idpro = $(this).attr('id');
      var idc = $('#txt_clid').val();
      if (idpro) {
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Proforma/obtenerPreciosProf",
          data: {idpro: idpro, idc: idc},
          success: function(json) {
            var reg = json.length;
            var pro = json[0].pro_nombre;
            $('.preproducto').text(pro);
            for (var i=0; i<reg; i++) {
              $('td[name='+json[i].idpre+']').text(json[i].precio);
            }              
          }    
        });
      }  
    });

    $(document).on('dblclick', '.factaddpro', function(){
      var id = $(this).attr('id');
      var idalm = $(this).attr('name');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Proforma/ins_profdetalletmp",
        data: {id: id, idalm: idalm},
        success: function(json) {
            actualizar_subtotales();
            $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
            $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
        }    
      });
    });
*/

    $(document).on('click', '.idpropre', function(){
      var idpro = $(this).attr('id');
      var idc = $('#txt_clid').val();
      var pago = $("#imprimir").attr("name");
      if(pago == 0){
        if (idpro) {
          $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Proforma/obtenerPreciosProf",
            data: {idpro: idpro, idc: idc},
            success: function(json) {
              var reg = json.length;
              var pro = json[0].pro_nombre;
              $('.preproducto').text(pro);
              for (var i=0; i<reg; i++) {
                $('td[name='+json[i].idpre+']').text(json[i].precio);
              }              
            }    
          });
        } 
      }else{
        alert("Genere una nueva Proforma");
      }
    });

    $(document).on('dblclick', '.factaddpro', function(){
      var id = $(this).attr('id');
      var idalm = $(this).attr('name');
      var pago = $("#imprimir").attr("name");
      if(pago == 0){
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Proforma/ins_profdetalletmp",
          data: {id: id, idalm: idalm},
          success: function(json) {
              actualizar_subtotales();
              $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
              $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
          }    
        });
      }else{
        alert("Genere una nueva Proforma");
      }


    });

    $(document).on('click', '.factaddprox', function(){
      var id = $(this).attr('id');
      var idalm = $(this).attr('name');
      var pago = $("#imprimir").attr("name");
      if(pago == 0){
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Proforma/ins_profdetalletmp",
          data: {id: id, idalm: idalm},
          success: function(json) {
              actualizar_subtotales();
              $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
              $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
          }    
        });
      }else{
        alert("Genere una nueva Proforma");
      }


    });


    $(document).on('click', '.pro_del', function(){
      id = $(this).attr('id');
      if (id) {
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Proforma/del_profdettmp",
          data: {id: id},
          success: function(json) {
              actualizar_subtotales(); 
              $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
              $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
          }    
        });
      }  
    });

    $('.guarda_cliente').blur(function(){
      var idcliente = $('#txt_nro_ident').val();  
      if (idcliente === ""){
        alert("Debe ingresar un numero de Identificación");
        return false;
      }     
      var nom = $('#txt_clinom').val(); 
      if (nom === ""){
        alert("Debe ingresar un nombre");
        return false;
      }      
      registrar_cliente();
    });

    $(document).on('change','.cantidad', function(){
      var cantidad = 0;
      var precio = 0;
      var subtotal = 0;
      var iva = 0.12;
      var valiva = 0;
      var subiva = 0;
      var desc = 0;
      var tp = 0;
      id = $(this).attr("id");
      tp = $('select[name='+id+']').val();
      if (tp == 'undefined' || tp == null || tp == '' || tp == ""){
        tp = 0;
      }
      cantidad = $(this).val();
      cantidad = cantidad.replace(',','');
      precio = $('.precio[id='+id+']').val();
      precio = precio.replace(',','');
      descpro = $('.descpro[id='+id+']').val();
      if (descpro == 'undefined' || descpro == null || descpro == '' || descpro == ""){
        descpro=0;
      } else {descpro = descpro.replace(',','');}
      subval = cantidad * precio;
      if( $('.grabaiva[id='+id+']').val() == 1 ) {
        valiva = (subval * (1 + iva)).toFixed(2) - subval.toFixed(2);
        valiva = valiva.toFixed(2);
        subiva = subval + valiva;        
      }else{
        subiva = subval;
        valiva = 0;
      }
      subtotal = subval.toFixed(2);
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Proforma/upd_detalleproforma');?>",
        data: { id: id, cantidad: cantidad, precio: precio,  valiva: valiva, subtotal: subtotal, 
                tp: tp, descpro: descpro },
        success: function(json) {
          actualizar_subtotales();
          $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
          $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
        }
      });
    });

    $(document).on('change','.precio', function(){
      var cantidad = 0;
      var precio = 0;
      var subtotal = 0;
      var iva = 0.12;
      var valiva = 0;
      var subiva = 0;
      var desc = 0;
      var tp = 0;
      id = $(this).attr("id");
      tp = $('select[name='+id+']').val();
      if (tp == 'undefined' || tp == null || tp == '' || tp == ""){
        tp = 0;
      }
      precio = $(this).val();
      precio = precio.replace(',','');
      cantidad = $('.cantidad[id='+id+']').val();
      cantidad = cantidad.replace(',','');
      descpro = $('.descpro[id='+id+']').val();
      if (descpro == 'undefined' || descpro == null || descpro == '' || descpro == ""){
        descpro=0;
      } else {descpro = descpro.replace(',','');}
      subval = cantidad * precio;
      if( $('.grabaiva[id='+id+']').val() == 1 ) {
        valiva = (subval * (1 + iva)).toFixed(2) - subval.toFixed(2);
        valiva = valiva.toFixed(2);
        subiva = subval + valiva;        
      }else{
        subiva = subval;
        valiva = 0;
      }
      subtotal = subval.toFixed(2);
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Proforma/upd_detalleproforma');?>",
        data: { id: id, cantidad: cantidad, precio: precio,  valiva: valiva, subtotal: subtotal, 
                tp: tp, descpro: descpro },
        success: function(json) {
          actualizar_subtotales();
          $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
          $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
        }
      });
    });

    $(document).on('change','.descpro', function(){
      var cantidad = 0;
      var precio = 0;
      var subtotal = 0;
      var iva = 0.12;
      var valiva = 0;
      var subiva = 0;
      var desc = 0;
      var tp = 0;
      id = $(this).attr("id");
      tp = $('select[name='+id+']').val();
      if (tp == 'undefined' || tp == null || tp == '' || tp == ""){
        tp = 0;
      }
      descpro = $(this).val();
      descpro = descpro.replace(',','');
      cantidad = $('.cantidad[id='+id+']').val();
      cantidad = cantidad.replace(',','');
      precio = $('.precio[id='+id+']').val();
      precio = precio.replace(',','');
      subval = cantidad * precio;
      if( $('.grabaiva[id='+id+']').val() == 1 ) {
        valiva = (subval * (1 + iva)).toFixed(2) - subval.toFixed(2);
        valiva = valiva.toFixed(2);
        subiva = subval + valiva;        
      }else{
        subiva = subval;
        valiva = 0;
      }
      subtotal = subval.toFixed(2);
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Proforma/upd_detalleproforma');?>",
        data: { id: id, cantidad: cantidad, precio: precio,  valiva: valiva, subtotal: subtotal, 
                tp: tp, descpro: descpro },
        success: function(json) {
          actualizar_subtotales();
          $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
          $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
        }
      });
    });


    $(document).on('change','#txt_codbar', function(){
      var codbar = $('#txt_codbar').val();
      var idalm = $('#cmb_almacenes').val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Proforma/ins_profdetalletmpcodbar",
        data: {codbar, idalm},
        success: function(json) {
            actualizar_subtotales();
            $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
            $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
            $('#txt_codbar').val("");
        }    
      });      
    });





    $(document).on('change','.descuento', function(){
      descuento = $('#descuento').val();
      if (descuento.trim() === ""){
        descuento = "0";
      }
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Proforma/upd_descuentoproformatmp');?>",
          data: { descuento: descuento},
        success: function(json) {
          actualizar_subtotales();
          $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
          $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
        }
      });
    });

    $(document).on('change','.tipoprecio', function(){
      var idc = $(this).attr('name'); 
      var tp = $('select[name='+idc+']').val();
      var text = $('select[name='+idc+'] option:selected').text();
      var id = $(this).attr('name');
      var exploded = text.split('-');
      var nombre = exploded[0];
      var monto = exploded[1];  
      monto = parseFloat(monto).toFixed(6);
      monto = monto.replace(',','');
      $('.precio[id='+id+']').val(monto);
      var cantidad = 0;
      var precio = 0;
      var subtotal = 0;
      var iva = 0.12;
      var valiva = 0;
      var subiva = 0;
      var desc = 0;
      precio = monto;
      cantidad = $('.cantidad[id='+id+']').val();
      cantidad = cantidad.replace(',','');
      subval = cantidad * precio;
      if( $('.grabaiva[id='+id+']').val() == 1 ) {
        valiva = (subval * (1 + iva)).toFixed(2) - subval.toFixed(2);
        valiva = valiva.toFixed(2);
        subiva = subval + valiva;        
      }else{
        subiva = subval;
        valiva = 0;
      }
      subtotal = subval.toFixed(2);
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Proforma/upd_detalleproforma');?>",
        data: { id: id, cantidad: cantidad, precio: precio,  valiva: valiva, subtotal: subtotal, tp: tp },
        success: function(json) {
          actualizar_subtotales();
          $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
          $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");;
        }
      });    
    });

    $(document).on('click','#pagar', function(){
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Proforma/valida_proforma');?>",
        success: function(json) {
          if(json > 0){

            $.ajax({
              type: "POST",
              dataType: "json",
              url: "<?php echo base_url('Proforma/valmonto_proforma');?>",
              success: function(monto) {
                if(monto > 0){
                  var idprof = $('#txt_idprof').val(); 
                  $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: { idprof: idprof },
                    url: "<?php echo base_url('Proforma/guarda_proforma');?>",
                    success: function(json) {
                      $("#imprimir").attr("disabled", false);
                      $("#imprimir").css("display", "inline");
                      $('.cantidad').attr("disabled", true);
                      $('.tipoprecio').attr("disabled", true);
                      $('.precio').attr("disabled", true);
                      $(".pro_del").css("display", "none");
                      $("#txt_codbar").attr("disabled", true);
                      $("#cmb_almacenes").attr("disabled", true);                                            
                      $("#pagar").attr("disabled", true);
                      $("#pagar").css("display", "none");
                      $('#txt_nro_ident').attr("disabled", true);
                      $('#txt_clinom').attr("disabled", true);
                      $('#txt_correo').attr("disabled", true);
                      $('#txt_telf').attr("disabled", true);
                      $('#txt_dir').attr("disabled", true);
                      $('#descuento').attr("disabled", true); 
                      $('#fecha').attr("disabled", true);
                      $('#cmb_tipident').attr("disabled", true);
                      $('#txt_ciudad').attr("disabled", true);
                      id = json;
                      $("#imprimir").attr('name', id);
                    }
                  });
                }else{
                  alert("El Monto de la Proforma no debe estar en 0");
                }
              }
            }); 


          }else{
            alert("La Proforma no Puede estar sin Productos");
          }
        }
      });    
    });  




    $(document).on('click', '#imprimir', function(){
      var id = $(this).attr('name');

      var impresionpdf = <?php if(@$impresionpdf != NULL) { print $impresionpdf;} else { print 0;} ?>;
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Proforma/nroproforma_tmp",
        data: { id: id },
        success: function(json) {
          if (impresionpdf == 1){
              $.fancybox.open({
                type:'iframe',
                width: 800,
                height: 600,
                ajax: {
                   dataType: "html",
                   type: "POST",
                   data: {id: id}
                },
                href: base_url + 'Proforma/proformapdf' 
              });
          }
          else{
            $.fancybox.open({
              type:'iframe',
              width: 800,
              height: 600,
              ajax: {
                 dataType: "html",
                 type: "POST",
                 data: {id: id}
              },
              href: base_url + 'Proforma/proformapdf' 
            });       
          }  
        }  
      });
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
      var obs = $('#txt_obser').val(); 
      var suc = $('#cmb_sucursal option:selected').val();      
      var codigo = $('#txt_codigocliente').val(); 
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Proforma/upd_profcliente",
        data: { nrocli:id, idtp:idtp, nom:nom, tel:tel, cor:cor, dir:dir, ciu:ciu, 
                idc:idc, obs:obs, codigo: codigo, suc: suc },
        success: function(json) {
          /*var cliente = <?php print $cliente->ident_cliente; ?>;
          if (id != cliente){
           location.reload();
          }*/
        }
      });
    }

    function actualizar_subtotales(){
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Proforma/lst_subtotalesprofdettmp",
          success: function(json) {
            $('.msubtotalconiva').html("$" + json.subtotaliva);
            $('.msubtotalsiniva').html("$" + json.subtotalcero);
            $('.descsubiva').html("$" + json.descsubtotaliva);
            $('.descsubcero').html("$" + json.descsubtotalcero);
            montoiva = parseFloat(json.montoiva);
            descuento = parseFloat(json.descuento);
            montototal = parseFloat(json.descsubtotaliva) + parseFloat(json.descsubtotalcero) + montoiva 
            montoiva = montoiva.toFixed(2);
            montototal = montototal.toFixed(2);
            if (descuento > montototal){
              descuento = montototal;
            } else {
              descuento = descuento.toFixed(2);
            }
            $('.miva').html("$" + montoiva);
            $('.mtotal').html("$" + montototal);
            $('.mtotal').val(montototal);
            $('#descuento').val(descuento);
          /*  actualizar_botonpagar();*/
          }
      });
    }

    $(document).on('change', '#fecha', function(){
      var fecha = $(this).val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Proforma/upd_fechaproformatmp",
        data: {fecha: fecha},
        success: function(json) {
        }    
      });
    });

    $(document).on('change','#cmb_sucursal', function(){
      registrar_cliente();
    });

    $(document).on('click','.facteditprox', function(){
      var id = $(this).attr('id');
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        ajax: {
           dataType: "html",
           type: "POST",
           data: {id: id},
        },
        href: "<?php echo base_url('Proforma/edit_descripciondetalle');?>"
      });
    });

    $(document).on('click','.guardadescpro', function(){
      var id = $("#txt_iddetalle").val();
      var descripcion = $("#descripcion_detalle").val();
      
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Proforma/udp_descripciondetalle');?>",
        data: { id: id, descripcion: descripcion },
        success: function(json) {
          $.fancybox.close();
          $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
          $('#detalletmp').load(base_url + "Proforma/actualiza_tablaproforma");
        }
      });
    });

    $(document).on('click', '.precio, .cantidad, .descpro', function(){
        $(this).select();
    });

    actualizar_subtotales();
    $("#imprimir").attr("disabled", true);


  });
</script>

</head>

<body class="hold-transition skin-red sidebar-mini">
  <header class="main-header" style="margin-left: 0px;">
    <nav class="navbar navbar-static-top" style="margin-left: 0px;" >

      <div class="pull-left" style="margin-left: 10px; margin-top: 8px;"> 

        <button id="inicio" type="button" class="btn bg-black " >
          <i class="fa fa-home"></i> Inicio
        </button>  

        <button id="nuevo" type="button" class="btn btn-success " >
          <i class="fa fa-file-text-o"></i> Nuevo
        </button> 

        <button id="listadoprof" type="button" class="btn bg-yellow " >
          <i class="fa fa-file-powerpoint-o"></i> Listado Proforma
        </button>  

<!--
        <button id="<?php // print $idusu; ?>" type="button" class="btn btn-primary venta" >
          <i class="fa fa-shopping-cart"></i> Ventas
        </button> 
-->



      </div>  
      <!--    
      <div class="col-md-3">
        <h3 style="margin-top: 13px; margin-bottom: 0px; color: #fff;"><?php //print $areamesa->nom_area." - ".$areamesa->nom_mesa; ?></h3>
      </div>
    -->



      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img class="user-image" width="160px" height="160px" <?php
                if (@$fic_fot != NULL) {
                  if ($fic_fot->fot_usu) { print " src='data:image/jpeg;base64,$fic_fot->fot_usu'"; } 
                  else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } } 
                else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } ?> 
                alt="" onerror="this.src='<?php print base_url() . "public/img/perfil.jpg"; ?>';" />
              <span class="hidden-xs"><?php print $this->session->userdata("sess_log"); ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img class="img-circle" <?php
                  if (@$fic_fot != NULL) {
                    if ($fic_fot->fot_usu) { print " src='data:image/jpeg;base64,$fic_fot->fot_usu'"; } 
                    else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } } 
                  else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } ?> 
                  alt="" onerror="this.src='<?php print base_url() . "public/img/perfil.jpg"; ?>';" />
                <p>
                  <?php print $this->session->userdata("sess_na"); ?>
                  <small><?php //print $this->session->userdata("sess_tu"); ?></small>
                </p>
              </li>
              <li class="user-footer">
                <div class="text-center">
                <a href="<?php print $base_url ?>auth/logout" class="btn btn-danger btn-flat icon-signout" title="Cerrar sesión"><i class="fa fa-power-off"></i>&nbsp;Salir</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- CONTENT -->
  <section class="content" style="background-color: #ecf0f5;">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <div class="col-md-12" style="padding-left: 0px; margin-bottom: 5px;">

              <div class="form-group col-md-2" style="margin-bottom: 5px;" >
                  <label >Sucursal</label>
                  <div class="sucursal ">
                    <select id="cmb_sucursal" name="cmb_sucursal" class="form-control guarda_cliente">
                    <?php 
                      if(@$sucursal != NULL){ ?>
                    <?php } else { ?>
                        <option  value="0" selected="TRUE">Seleccione...</option>
                    <?php } 
                              if (count($sucursal) > 0) {
                                foreach ($sucursal as $s):
                                    if(@$cliente->id_sucursal != NULL){
                                        if($s->id_sucursal == $cliente->id_sucursal){ ?>
                                            <option  value="<?php  print $s->id_sucursal; ?>" selected="TRUE"><?php  print $s->nom_sucursal; ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $s->id_sucursal; ?>"> <?php  print $s->nom_sucursal; ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $s->id_sucursal; ?>"> <?php  print $s->nom_sucursal; ?> </option>
                                        <?php
                                        }   ?>
                                    <?php

                                endforeach;
                              }
                              ?>
                    </select>                  
                  </div>
                </div>

                <div class="form-group col-md-2" style="margin-bottom: 5px;">
                  <label>Fecha</label>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input class="form-control pull-right validate[required] " id="fecha" name="fecha" value="<?php if(@$cliente != NULL){ @$fec = str_replace('-', '/', @$cliente->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec; }else{ $fec = date("d/m/Y"); print @$fec;} ?>" type="text" disabled>
                  </div>
                </div>
                <div class="form-group col-md-2" style="margin-bottom: 5px;">
                  <label id="nropro">Nro Proforma</label>
                  <div class="">
                    <input type="hidden" id="txt_idprof" name="txt_idprof" value="<?php if(@$idproforma != NULL){ print @$idproforma; } else{ print 0; }?>" >
                    <input type="text" class="form-control validate[required] text-center" id="nro_proforma" name="nro_proforma" disabled="" value="<?php if(@$cliente->nro_proforma != NULL){ print @$cliente->nro_proforma; } else { print @$nroproforma; } ?>">
                  </div>
                </div>

            </div>

            <div class="col-md-12" style="padding-left: 0px; margin-bottom: 5px;">

              <?php if(@$codigocliente == 1){ ?>
              <div id="menidcod" class="form-group col-md-1" style="padding-right: 0px; margin-bottom: 5px;">
                <label >Código Cliente</label>
                <div class="">
                  <input type="text" class="form-control validate[required]" name="txt_codigocliente" id="txt_codigocliente" placeholder="Codigo Cliente" value="<?php if(@$cliente != NULL){ print @$cliente->codigo; }?>" >
                </div>
              </div> 
              <?php } ?>

              <div class="form-group col-md-2 " style="margin-bottom: 5px;" >
                <label id="">Tipo Ident</label>
                <div class="tipident">
                  <select id="cmb_tipident" name="cmb_tipident" class="form-control">
                  <?php 
                    if(@$tipident != NULL){ ?>
                  <?php } else { ?>
                      <option  value="0" selected="TRUE">Seleccione...</option>
                  <?php } 
                            if (count($tipident) > 0) {
                              foreach ($tipident as $ti):
                                  if(@$cliente->tipo_ident_cliente != NULL){
                                      if($ti->cod == $cliente->tipo_ident_cliente){ ?>
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
              <div id="menid" class="form-group col-md-2" style="margin-bottom: 5px;">
                <label id="nroident">Nro Identificación</label>
                <div class="">
                  <input type="hidden" id="txt_clid" name="txt_clid" value="<?php if(@$cliente != NULL){ print @$cliente->id_cliente; }?>" >
                  <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$cliente != NULL){ print @$cliente->ident_cliente; }?>" >
                </div>
              </div> 
              <div class="form-group col-md-3" style="margin-bottom: 5px;">
                <label id="nroident">Cliente</label>
                <div id="mennom" class="autocomplete" style="padding-right: 0px;">
                  <input type="text" class="form-control " name="txt_clinom" id="txt_clinom" placeholder="Nombre del Cliente" value="<?php if(@$cliente != NULL){ print @$cliente->nom_cliente; }?>" data-source="<?php echo base_url('facturar/valclientenombre?nombre=');?>">
                </div>
              </div> 
              <div class="form-group col-md-2" style="margin-bottom: 5px;">
                <label>Teléfono</label>
                <div class="">
                  <input type="text" class="form-control guarda_cliente text-center" name="txt_telf" id="txt_telf" placeholder="Telefono" value="<?php if(@$cliente != NULL){ print @$cliente->telefonos_cliente; }?>" >
                </div>
              </div> 
              <?php if(@$codigocliente == 1){ ?>
                  <div class="form-group col-md-2" style="margin-bottom: 5px;">
              <?php } else { ?>
                  <div class="form-group col-md-3" style="margin-bottom: 5px;">
              <?php } ?>
                <label>Ciudad</label>
                <div class="">
                  <input type="text" class="form-control guarda_cliente" name="txt_ciudad" id="txt_ciudad" placeholder="Ciudad" value="<?php if(@$cliente != NULL){ print @$cliente->ciudad_cliente; }?>" >
                </div>
              </div> 
              <div class="form-group col-md-2" style="margin-bottom: 5px;">
                <label>Correo</label>
                <div class="">
                  <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_correo" id="txt_correo" placeholder="Correo" value="<?php if(@$cliente != NULL){ print @$cliente->correo_cliente; }?>" >
                </div>
              </div>               
              <div class="form-group col-md-5" style="margin-bottom: 5px;">
                <label>Dirección</label>
                <div class="">
                  <input type="text" class="form-control guarda_cliente" name="txt_dir" id="txt_dir" placeholder="Dirección" value="<?php if(@$cliente != NULL){ print @$cliente->direccion_cliente; }?>" >
                </div>
              </div> 
              <div class="form-group col-md-5" style="margin-bottom: 5px;">
                <label>Observaciones</label>
                <div class="">
                  <input type="text" class="form-control guarda_cliente" name="txt_obser" id="txt_obser" placeholder="Observaciones" value="<?php if(@$cliente != NULL){ print @$cliente->observaciones; }?>" >
                </div>
              </div> 
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-5" style="padding-left: 0px;">
                  <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                      <?php if(count($proalma) > 0){$active = ''; }else{$active = 'active';}?> 
                      <?php if(count($proalma) > 0){?>
                      <li class="active"><a href="#gas" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> GAS</a></li>
                      <?php } ?>
                      <li class="<?php print $active; ?>"><a href="#listado" data-toggle="tab"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Productos</a></li>
                      <?php 
                      foreach ($lstcat as $cat) {
                        if ($cat->menu == 1) {
                      ?>
                          <li><a href="<?php print '#categoria' . $cat->cat_id; ?>" data-toggle="tab"><?php print $cat->cat_descripcion; ?></a></li>
                      <?php 
                        }
                      }
                      ?>
                    </ul>
                    <div class="tab-content">
                      <?php if(count($proalma) > 0){?>                    
                        <div class="tab-pane active" id="gas">
                          <div class="box-body">
                            <?php
                            foreach ($proalma as $pa) { ?>
                              <div class="col-md-4" style="padding-left: 0px; <?php if($pa->existencia>0){$color='color: black';} else {$color='color: red';} print $color ?>;">
                                
                                <a id="<?php print $pa->pro_id; ?>" name="<?php print $pa->almacen_id; ?>" class="btn btn-app promonto">
                                    <img class="progas img-responsive" <?php
                                  if (@$pa != NULL) {
                                    if ($pa->pro_imagen) { print " src='data:image/jpeg;base64,$pa->pro_imagen'"; } 
                                    else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } } 
                                  else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } ?> 
                                  alt="" onerror="this.src='<?php print base_url() . "public/img/perfil.jpg"; ?>';" />
                                </a>
                                <?php print $pa->almacen_nombre; ?>
                              </div>
                            <?php 
                            }
                            ?>
                          </div>
                        </div>   
                      <?php } ?>    
                                
                      <div class="tab-pane <?php print $active; ?>" id="listado">
                        <div class="box-body table-responsive">
                          
                          <div class="tipo_precio">
                            <?php if($tp == 1){ ?> 
                            <table class="table table-bordered lstprecios" style="margin-bottom: 15px; ">
                              <thead>
                                <tr>
                                  <th>Productos</th>                              
                                <?php 
                                foreach ($lstprecios as $lp) { 
                                ?>                              
                                  <th class="text-center col-md-1"><?php print $lp->nompre; ?></th>
                                <?php 
                                }
                                ?>                              
                                </tr>
                              </thead>    
                              <tbody>                                                        
                                  <tr>
                                    <td class="preproducto" >
                                      
                                    </td>
                                <?php 
                                foreach ($lstprecios as $lstp) { 
                                ?>
                                    <td name="<?php print $lstp->id; ?>" >

                                    </td>
                                <?php 
                                }
                                ?>
                                  </tr>
                              </tbody>
                            </table>
                            <?php } ?>
                          </div>

                          <table id="TableProducto" class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr>
                                <th>Cod Barra</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Existencia</th>
                                <th>Almacen</th>
                              </tr>
                              </thead>    
                              <tbody>                                                        
                                <?php 
                                foreach ($pro as $p) {
                                ?>
                                  <tr class="factaddpro idpropre" style="background-color: <?php if ($p->existencia != 0 or $p->preparado == 1) {print '#fbfbfb';} else {print '#DD4B39';} ?>" id="<?php print $p->pro_id; ?>" name="<?php print $p->id_alm; ?>">
                                    <td>
                                      <?php print $p->pro_codigobarra; ?>
                                    </td>
                                    <td>
                                      <a style="color: #449B2E;" href="#" title="Añadir" id="<?php print $p->pro_id; ?>" name="<?php print $p->id_alm; ?>"" class="factaddprox"><i class="fa fa-plus-circle"></i></a>
                                      <?php print $p->pro_nombre; ?>
                                    </td>
                                    <td class="text-right">
                                      <?php print $p->pro_precioventa; ?>
                                    </td>
                                    <td class="text-right">
                                      <?php print $p->existencia; ?>
                                    </td>                                  
                                    <td>
                                      <?php print $p->almacen_nombre; ?>
                                    </td>
                                  </tr>
                                <?php 
                                }
                                ?>
                              </tbody>
                          </table>
                        </div>
                      </div>

                      <?php 
                          foreach ($lstcat as $cat) {
                            if ($cat->menu == 1) {
                          ?>
                              <div class="tab-pane" id="<?php print 'categoria' . $cat->cat_id; ?>">
                                <div class="box-body">
                                  <?php
                                    foreach ($lstpro as $lpro) {
                                      if($lpro->idcat == $cat->cat_id){
                                  ?>     
                                        <a id="<?php print $lpro->id; ?>"  name="<?php print $lpro->id_alm;?>" class="btn btn-app factaddpro idpropre"><i class="fa fa-beer" aria-hidden="true"></i> <?php print $lpro->producto ?>
                                        </a>
                                  <?php  
                                      }
                                    }
                                  ?>

                                </div>
                                <!-- /.tab-pane -->
                              </div>
                              <!-- /.tab-content -->

                      <?php 
                        }
                      }
                      ?>
                    </div>
                  </div>
              </div>    






              <div class="col-md-7" style="padding-right: 0px; padding-left: 0px;">

                <div class="box">
                  <div class="box-header with-border">
                    <div class="col-md-6" style="padding-left: 0px;">
                      <h3 class="box-title">Productos a Facturar</h3>  
                    </div>
                    
                    <div class="col-md-3">
                      <input type="text" class="form-control col-md-3 codbar text-center" name="txt_codbar" id="txt_codbar" placeholder="Codigo de Barra" value="" >  
                    </div>
                    
                    <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px; display: <?php if(count($almacenes) > 1) {print 'block';} else {print 'none';} ?>;">
                      <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Almacen</label>
                      <div  class="col-sm-8 tipalmacenes" style="padding-right: 0px;">
                        <select id="cmb_almacenes" name="cmb_almacenes" class="form-control">
                        <?php 
                          if(@$almacenes != NULL){ ?>
                        <?php } else { ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } 
                                  if (count($almacenes) > 0) {
                                    foreach ($almacenes as $almobj):
                                        ?>
                                            <option value="<?php  print $almobj->almacen_id; ?>"> <?php  print $almobj->almacen_nombre; ?> </option>
                                        <?php
                                    endforeach;
                                  }
                                  ?>
                        </select>
                      </div>
                    </div>
                 
                  </div>
                  <div id="detalletmp" class="box-body table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Productos</th>
                          <th class="text-center col-md-1">Cantidad</th>
                          <div class="tipo_precio">  
                            <?php if($tp == 1){ ?> 
                              <th class="text-center col-md-2 ">Tipo</th>
                            <?php } ?>
                          </div>
                          <th class="text-center col-md-1">Precio</th>
                          <th class="text-center col-md-1">SubTotal</th>
                          <?php if($descpro == 1) { ?>                     
                            <th class="text-center col-md-1"><?php if (@$tipodescprod == 1) { print '% ';} ?>Desc</th>                             
                          <?php } ?>                          
                          <th class="text-center col-md-1">SubT-Desc</th>
                          <th class="text-center col-md-1">Acción</th>
                        </tr>
                      </thead>    
                      <tbody>                                                        
                        <?php 
                        $num=0;
                        foreach ($lstdetalle as $det) {
                          $num++;
                        ?>
                          <tr class="detallepro" id="<?php print $det->id_detalle; ?>">
                            <td>
                              <?php print $num; ?>
                              <input type="hidden" class="grabaiva" id="<?php print @$det->id_detalle ?>" name="" value="<?php print @$det->pro_grabaiva ?>" >    
                            </td>
                            <td>
                              <a style="color: #449B2E;" href="#" title="Editar" id="<?php print $det->id_detalle; ?>" class="facteditprox"><i class="fa fa-edit"></i></a>
                              <?php print substr($det->descripcion, 0, 35); ?>
                            </td>
                            <td class="text-center">
                              <input type="text" class="form-control text-center cantidad" name="" id="<?php print @$det->id_detalle ?>" value="<?php if(@$det != NULL){ print @$det->cantidad; }?>" >
                            </td>
                            <div class="tipo_precio">
                              <?php if($tp == 1){ ?>  
                                <td>
                                  <div class="col-md-12">
                                    <select id="<?php print $det->id_producto; ?>" name="<?php print @$det->id_detalle ?>" class="form-control tipoprecio">
                                      <?php 
                                      if(@$preciopro != NULL){ 
                                        if (count($preciopro) > 0) {
                                          foreach ($preciopro as $pp): 
                                            if($det->id_producto == $pp->pro_id){
                                              if($det->tipprecio == $pp->idprepro){ ?>
                                                  <option value="<?php  print $pp->idprepro;?>" selected="TRUE"> <?php  print $pp->Tienda." - ".$pp->precio ?> </option>
                                      <?php
                                              }else{ ?>
                                              <option value="<?php  print $pp->idprepro; ?>"> <?php  print $pp->Tienda." - ".$pp->precio ?>  </option>
                                      <?php
                                              }
                                            }
                                          endforeach;
                                        }
                                      }
                                      ?>
                                    </select>  
                                  </div>                                    
                                </td>
                              <?php } ?>  
                            </div>
                            <td class="text-center">
                              <input type="text" class="form-control text-center precio" name="" id="<?php print @$det->id_detalle ?>" value="<?php if(@$det != NULL){ print @$det->precio; }?>" <?php if ((@$cambioprecio == 1) && (@$perfil != 1)) {print "disabled";} ?> >
                            </td>
                            <td class="text-right">
                              <div id="<?php print @$det->id_detalle; ?>" class="subtotal">
                                <?php print number_format($det->subtotal,2); ?>
                              </div>
                            </td>             
                            <?php if($descpro == 1) { ?>                     
                              <td class="text-center">
                                <input type="text" class="form-control text-center descpro" name="" id="<?php print @$det->id_detalle ?>" 
                                       value="<?php if(@$det != NULL){ if (@$tipodescprod == 1) {print number_format(@$det->porcdesc, 2); } else {print number_format(@$det->descmonto, 2);} } ?>" 
                                >
                              </td>                              
                            <?php } ?>

                            <td class="text-right">
                              <div id="<?php print @$det->id_detalle; ?>" class="descsubtotal">
                                <?php print number_format($det->descsubtotal,2); ?>
                              </div>
                            </td>                        
                            <td class="text-center">
                                <a href="#" title="Eliminar" id="<?php  if(@$det != NULL){ print @$det->id_detalle; }?>" class="btn btn-sm btn-danger btn-grad pro_del"><i class="fa fa-trash-o"></i></a>
                            </td>
                          </tr>
                        <?php 
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="box-footer clearfix">

                    <div class="col-md-6 pull-right">
                      <div id="calmonto" class="pull-right" style="margin-right: 10px;">
                        <table class="table table-clear calmonto" >
                          <tbody>
                            <tr>
                              <td class="text-left"><strong>Subtotal IVA 12 %</strong></td>
                              <td id="msubtotalconiva" class="text-right msubtotalconiva">$</td>                                        
                            </tr>
                            <tr>
                              <td class="text-left"><strong>Subtotal IVA 0 %</strong></td>
                              <td id="msubtotalsiniva" class="text-right msubtotalsiniva">$</td>                                        
                            </tr>
                            <?php if($descpro != 1) { ?>                                                
                            <tr>
                              <td class="text-left"><strong>Descuento</strong></td>
                              <td id="" class="text-right">
                                <input type="text" class="text-right descuento" name="" id="descuento" value="" style="width:70px;" >
                              </td>                                        
                            </tr>
                            <?php } ?>                                                
                            <tr>
                              <td class="text-left"><strong>Subtotal con Descuento IVA 12 %</strong></td>
                              <td id="descsubiva" class="text-right descsubiva">$</td>                                        
                            </tr>
                            <tr>
                              <td class="text-left"><strong>Subtotal con Descuento IVA 0 %</strong></td>
                              <td id="descsubcero" class="text-right descsubcero">$</td>                                        
                            </tr>
                            <tr>
                              <td class="text-left"><strong>IVA (12%)</strong></td>
                              <td id="miva" class="text-right miva">$</td>                                        
                            </tr>
                            <tr>
                              <td class="text-left"><strong>Total</strong></td>
                              <td id="mtotal" class="text-right mtotal"><strong>$</strong></td>                                        
                            </tr>      
                          </tbody>
                        </table>
                      </div>                
                    </div>

                  </div>

                  <div class="box-footer text-right">
                    <div class="row no-print ">
                    <div class="col-md-12">

                      <button id="imprimir" type="button" class="btn btn-primary btn-grad" name = "0" >
                        <i class="fa fa-print"></i> Imprimir
                      </button>   
                               
                      <button id="pagar" type="button" class="btn btn-success btn-grad" >
                        <i class="fa fa-save"></i> Guardar
                      </button>
                    </div>
                    
                    </div>
                  </div>

                </div>

              </div>


              </div>
            </div>
          </div>  
        </div>
      </div>  
    </div>
  </section>


   <footer class="main-footer " style ="margin-left: 0px; height: 30px;">
    <div class="pull-right hidden-xs">
      <b>Version</b> 3.0
    </div>
    <strong>Copyright &copy; 2019 <a href="#"><?php print @$nombresistema; ?></a>.</strong> All rights
    reserved.
  </footer>

</body>
</html>