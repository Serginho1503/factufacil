<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>FactuFácil - Facturación</title>
  <!-- Tell the browser to be responsive to screen width -->
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
  <?php // <!-- FIN DE INCLUDES PARA VALIDACION DE FORMULARIOS --> ?>

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

  </style>

<script type="text/javascript">
  
  $( document ).ready(function() {

    $("#imprimir").attr("disabled", true);

    /*$("#pagar").attr("disabled", true);*/


    $('#TableProducto').DataTable({
      'language': {
        'url': base_url + 'public/json/language.spanish.json'
      }
    });


    $(document).on('click','#inicio', function(){
      location.replace(base_url + 'inicio');
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

    $(document).on('change','#cmb_tipfac', function(){
      var idforma = $("#cmb_tipfac option:selected").val();
      if(idforma == 2){
        $(".evapago").html("<input type='text' class='form-control validate[required] text-center' id='factura' name='factura' disabled='' value='<?php if(@$nrofactura != NULL){ print @$nrofactura; }?>'>");
      }else{
        $(".evapago").html("<input type='text' class='form-control validate[required] text-center' id='notaventa' name='notaventa' value='<?php if(@$nronv != NULL){ print @$nronv; }?>'>");
      }
      return false;
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
          url: "<?php echo base_url('Facturar/valcliente');?>",
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
            }
            else { 
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
          }
      });
    });

    $('.autocomplete').autocomplete();

    /* MUESTRA DATOS DEL CLIENTE */
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

    });


    function registrar_cliente(){
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
          url: base_url + "Facturar/upd_ventcliente",
          data: { idcli:id, idtp:idtp, nom:nom, tel:tel, cor:cor, dir:dir, ciu:ciu },
          success: function(json) {
          }
      });
    }

      /* inserta producto en detalle */
      $(document).on('click', '.factaddpro', function(){
        id = $(this).attr('id');
        idalm = $(this).attr('name');
        if (id) {
          $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Facturar/ins_detalleventatmp",
            data: {id: id, idalm: idalm},
            success: function(json) {
                actualizar_subtotales();
                $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
            }    
          });
        }  
      });

      /* elimina producto de detalle */
      $(document).on('click', '.pro_del', function(){
        id = $(this).attr('id');
        if (id) {
          $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Facturar/del_detalleventatmp",
            data: {id: id},
            success: function(json) {
                actualizar_subtotales();
                $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
            }    
          });
        }  
      });

    /* GUARDAR detalles EN LA TABLA TEMPORAL Y REALIZAR CALCULO */
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
      precio = $('.precio[id='+id+']').val();
      /*desc = $('.descuento').val();*/
      subval = cantidad * precio;

      if( $('.grabaiva[id='+id+']').val() == 1 ) {
        valiva = subval * iva;
        subiva = subval + valiva;        
      }else{
        subiva = subval;
        valiva = 0;
      }

      subtotal = subval.toFixed(2);

      /* ACTUALIZA detalle */
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/upd_detalleventa');?>",
        data: { id: id, cantidad: cantidad, precio: precio,  valiva: valiva, subtotal: subtotal },
        success: function(json) {
          actualizar_subtotales();

          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
        }
      });
    });

    /* GUARDAR detalles EN LA TABLA TEMPORAL Y REALIZAR CALCULO */
    $(document).on('change','.precio', function(){
      var cantidad = 0;
      var precio = 0;
      var subtotal = 0;
      var iva = 0.12;
      var valiva = 0;
      var subiva = 0;
      var desc = 0;
      id = $(this).attr("id");
      precio = $(this).val();
      cantidad = $('.cantidad[id='+id+']').val();
      subval = cantidad * precio;

      if( $('.grabaiva[id='+id+']').val() == 1 ) {
        valiva = subval * iva;
        subiva = subval + valiva;        
      }else{
        subiva = subval;
        valiva = 0;
      }

      subtotal = subval.toFixed(2);

      /* ACTUALIZA detalle */
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/upd_detalleventa');?>",
        data: { id: id, cantidad: cantidad, precio: precio,  valiva: valiva, subtotal: subtotal },
        success: function(json) {
          actualizar_subtotales();

          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
        }
      });
    });

    function actualizar_subtotales(){
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Facturar/lst_subtotalesventatmp",
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
            actualizar_botonpagar();
          }
      });
    }

    /* Actualizar descuento EN LA TABLA TEMPORAL Y REALIZAR CALCULO */
    $(document).on('change','.descuento', function(){
      descuento = $('#descuento').val();
      if (descuento.trim() === ""){
        descuento = "0";
      }
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/upd_descuentoventatmp');?>",
          data: { descuento: descuento},
        success: function(json) {
          actualizar_subtotales();

          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
        }
      });
    });

    $(document).on('click','#pagar', function(){
      var efectivo = $('#efectivo').val();
      var tarjeta = $('#tarjeta').val();
      var cambio = $('#cambio').text();
      cambio = cambio.replace('$ ','');
      cambio = cambio.replace(',','.');
      cambio = parseFloat(cambio);    
      var nro_factura = $('#factura').val();
      var nro_notaventa = $('#notaventa').val();
      var cmb_forma = $('#cmb_tipfac').val();

      var nro_ident = $('#txt_nro_ident').val();
      var nom_cliente = $('#txt_clinom').val();
      var cor_cliente = $('#txt_correo').val();
      var telf_cliente = $('#txt_telf').val();
      var dir_cliente = $('#txt_dir').val();

      if(nro_ident.length > 4 && nom_cliente.length >= 3 ){

        var efectivo = $('#efectivo').val();
        var tarjeta = $('#tarjeta').val();
        if ((parseFloat(montototal) > 0) && (parseFloat(efectivo)+parseFloat(tarjeta) >= parseFloat(montototal))){
          $("#pagar").attr("disabled", true);
          $("#pagar").css("display", "none");
         
          $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('facturar/pagar_facturageneral');?>",
            data: { cmb_forma: cmb_forma, 
                    nro_factura: nro_factura, 
                    nro_notaventa: nro_notaventa, 
                    efectivo: efectivo, 
                    tarjeta: tarjeta, 
                    cambio: cambio },
            success: function(json) {

              $("#imprimir").attr("disabled", false);
              $("#pagar").attr("disabled", true);
              $("#pagar").css("display", "none");
              $('#txt_nro_ident').attr("disabled", true);
              $('#txt_clinom').attr("disabled", true);
              $('#txt_correo').attr("disabled", true);
              $('#txt_telf').attr("disabled", true);
              $('#txt_dir').attr("disabled", true);
              $('#descuento').attr("disabled", true); 
              $('#efectivo').attr("disabled", true);
              $('#cmb_tipfac').attr("disabled", true);
              $('#notaventa').attr("disabled", true);
              $('#factura').attr("disabled", true);
              $('#fecha').attr("disabled", true);


              id = json.dat;
              $("#imprimir").attr('name', id);
            }
          });


        } else {
          if (parseFloat(montototal) > 0){
            alert("VERIFIQUE EL MONTO A PAGAR DE LA FACTURA");
          } else {
            alert("VERIFIQUE EL MONTO DE LA FACTURA");            
          }  
        }
      } else {
        alert("VERIFIQUE LOS DATOS DE CEDULA Y NOMBRE");
      }  

    });

    function actualizar_botonpagar(){
      var montototal = $('.mtotal').val();
      var efectivo = $('#efectivo').val();
      var tarjeta = $('#tarjeta').val();
      var totalpagado = parseFloat(efectivo)+parseFloat(tarjeta);
      var cambio = parseFloat(efectivo)+parseFloat(tarjeta)-parseFloat(montototal);

      if ((parseFloat(montototal) > 0) && (parseFloat(efectivo)+parseFloat(tarjeta) >= parseFloat(montototal))){
      } else {
        cambio = 0;
      }

      // if ((parseFloat(montototal) > 0) && (parseFloat(efectivo)+parseFloat(tarjeta) >= parseFloat(montototal))){
      //   $("#pagar").attr("disabled", false);
      // } else {
      //   $("#pagar").attr("disabled", true);
      //   cambio = 0;
      // }

      totalpagado = totalpagado.toFixed(2);  
      cambio = cambio.toFixed(2);  
      $('.totalfp').html("$" + totalpagado);
      $('.cambio').html("$" + cambio);
    }

    /* Verificar si se puede habilitar boton de pago */
    $(document).on('change','#efectivo', function(){
      var efectivo = $('#efectivo').val();
      if (efectivo.trim() === "") {
        efectivo = "0.00";
      } else {
        efectivo = parseFloat(efectivo).toFixed(2);
      }
      $('#efectivo').val(efectivo);
      actualizar_botonpagar();
    });

    /* Verificar si se puede habilitar boton de pago */
    $(document).on('change','#tarjeta', function(){
      var tarjeta = $('#tarjeta').val();
      if (tarjeta.trim() === "") {
        tarjeta = "0.00";
      } else {
        tarjeta = parseFloat(tarjeta).toFixed(2);
      }
      $('#tarjeta').val(tarjeta);
      actualizar_botonpagar();
    });

    /* Boton del listado para imprimir compra */
    $(document).on('click', '#imprimir', function(){
      var id = $(this).attr('name');

      $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST",
                   data: {id: id}
                },
                href: "<?php echo base_url('Facturar/imprimirventa');?>" 
              });
    });


    actualizar_subtotales();

  });
</script>

</head>

<!-- HEAD -->
<?php
  $usu_mod = &get_instance();
  $usu_mod->load->model("usuario_model");
  $usua = $this->session->userdata('usua');
  $id = $usua->id_usu;
$fic_fot = $usu_mod->usuario_model->usu_get_fot($id);
?>
<body class="hold-transition skin-red sidebar-mini">
  <header class="main-header" style="margin-left: 0px;">
    <nav class="navbar navbar-static-top" style="margin-left: 0px;" >

      <div class="pull-left" style="margin-left: 10px; margin-top: 8px;"> 
        <button id="inicio" type="button" class="btn btn-primary " >
          <i class="fa fa-home"></i> Inicio
        </button>   
      </div>      
      <div class="col-md-3">
        <h3 style="margin-top: 13px; margin-bottom: 0px; color: #fff;"><?php //print $areamesa->nom_area." - ".$areamesa->nom_mesa; ?></h3>
      </div>
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
              <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                <label for="" class="col-sm-5 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Documento</label>
                <div class="col-md-7" style="padding-right: 0px;">
                  <select id="cmb_tipfac" name="cmb_tipfac" class="form-control">
                    <?php 
                      if(@$tipfact != NULL){ ?>
                    <?php } else { ?>
                        <option  value="0" selected="TRUE">Seleccione...</option>
                    <?php } 
                              if (count($tipfact) > 0) {
                                foreach ($tipfact as $tf):
                                    if(@$cliente->tipo_doc != NULL){
                                        if($tf->id_contador == $cliente->tipo_doc){ ?>
                                            <option  value="<?php  print $tf->id_contador; ?>" selected="TRUE"><?php  print $tf->categoria; ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $tf->id_contador; ?>"> <?php  print $tf->categoria; ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $tf->id_contador; ?>"> <?php  print $tf->categoria; ?> </option>
                                        <?php
                                        }   ?>
                                    <?php

                                endforeach;
                              }
                              ?>
                  </select>                                                  
                </div>
              </div>
              <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 15px;">
                <label for="" class="col-sm-2 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Nro</label>
                <div class="col-sm-10 evapago" style="padding-right: 0px;">
                  <?php if($cliente->tipo_doc == 2){ ?>
                  <input type="text" class="form-control validate[required] text-center" id="factura" name="factura" disabled="" value="<?php if(@$nrofactura != NULL){ print @$nrofactura; }?>">
                  <?php } else {?>
                  <input type="text" class="form-control validate[required] text-center" id="notaventa" name="notaventa" value="<?php if(@$nronv != NULL){ print @$nronv; }?>"> 
                  <?php } ?> 
                </div>
              </div>
              <div class="form-group col-md-2 pull-right" style="padding-right: 0px; padding-left: 0px; margin-left: 15px;">
                <label for="" class="col-sm-3 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Fecha</label>
                <div style="margin-bottom: 0px;"class="col-sm-9 form-group" >
                  <div class="input-group date">
                    <input type="text" class="form-control text-center validate[required]" id="fecha" name="fecha" value="<?php if(@$cliente != NULL){ @$fec = str_replace('-', '/', @$cliente->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec; } ?>" style="width: 90px;">
                  </div>                             
                </div>
              </div> 
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-2 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Cliente</label>
                  <div id="mennom" class="col-sm-10 autocomplete" style="padding-right: 0px;">
                    <input type="text" class="form-control " name="txt_clinom" id="txt_clinom" placeholder="Nombre del Cliente" value="<?php if(@$cliente != NULL){ print @$cliente->nom_cliente; }?>" data-source="<?php echo base_url('facturar/valclientenombre?nombre=');?>">
                  </div>
                </div>

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
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Nro de Ident</label>
                  <div id="menid" class="col-sm-8" style="padding-right: 0px;">
                    <input type="hidden" id="txt_idcli" name="txt_idcli" value="<?php if(@$cliente != NULL){ print @$cliente->nro_ident; }?>" >    
                    <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$cliente != NULL){ print @$cliente->nro_ident; }?>" >
                  </div>
                </div>

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Teléfono</label>
                  <div id="" class="col-sm-8" style="padding-right: 0px;">
                   <input type="text" class="form-control col-md-3" name="txt_telf" id="txt_telf" placeholder="Telefono" value="<?php if(@$cliente != NULL){ print @$cliente->telf_cliente; }?>" >
                  </div>
                </div>

                <div class="form-group col-md-6" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-1 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Dirección</label>
                  <div id="" class="col-sm-11" style="padding-left: 20px;">
                   <input type="text" class="form-control col-md-3" name="txt_dir" id="txt_dir" placeholder="Dirección" value="<?php if(@$cliente != NULL){ print @$cliente->dir_cliente; }?>" >
                  </div>
                </div>

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Ciudad</label>
                  <div id="" class="col-sm-8" style="padding-right: 0px;">
                   <input type="text" class="form-control col-md-3" name="txt_ciudad" id="txt_ciudad" placeholder="Ciudad" value="<?php if(@$cliente != NULL){ print @$cliente->ciu_cliente; }?>" >
                  </div>
                </div>

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Correo</label>
                  <div id="" class="col-sm-8" style="padding-right: 0px;">
                   <input type="text" class="form-control col-md-3" name="txt_correo" id="txt_correo" placeholder="Correo" value="<?php if(@$cliente != NULL){ print @$cliente->correo_cliente; }?>" >
                  </div>
                </div>

                </div> 
              </div>
          </div>

          <div class="box-footer">
            <div class="col-md-12" style="background-color: #ecf0f5; padding-left: 0px; padding-right: 0px;"><!-- #dd4b39 -->

              <div class="col-md-5" style="padding-left: 0px;">
                <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#listado" data-toggle="tab">Listado</a></li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="listado">
                      <div class="box-body table-responsive">
                        <table id="TableProducto" class="table table-bordered table-hover table-responsive">
                          <thead>
                            <tr>
                              <th>Cod Barra</th>
                              <th>Nombre</th>
                              <th>Precio</th>
                              <th>Almacen</th>
                            </tr>
                            </thead>    
                            <tbody>                                                        
                              <?php 
                              foreach ($pro as $p) {
                              ?>
                                <tr class="factaddpro" style="background-color: <?php if ($p->existencia != 0) {print '#fbfbfb';} else {print '#DD4B39';} ?>" id="<?php print $p->pro_id; ?>" name="<?php print $p->id_alm; ?>">
                                  <td>
                                    <?php print $p->pro_codigobarra; ?>
                                  </td>
                                  <td>
                                    <?php print $p->pro_nombre; ?>
                                  </td>
                                  <td>
                                    <?php print $p->pro_precioventa; ?>
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
                  </div>
                </div>
              </div>    

              <div class="col-md-7" style="padding-right: 0px; padding-left: 0px;">

                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title">Productos a Facturar</h3>
                    <div class="pull-right"> 
                      <button id="comanda" type="button" class="btn btn-sm btn-danger del_serproped" >
                        <i class="fa fa-trash"></i> Borra Productos  
                      </button>               
                    </div>                    
                  </div>
                  <div id="detalletmp" class="box-body table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Productos</th>
                          <th class="text-center col-md-1">Cantidad</th>
                          <th class="text-center col-md-1">Precio</th>
                          <th class="text-center col-md-1">SubTotal</th>
                          <th class="text-center col-md-1">SubTotal Desc</th>
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
                              <?php print $det->pro_nombre; ?>
                            </td>
                            <td class="text-center">
                              <input type="text" class="form-control text-center cantidad" name="" id="<?php print @$det->id_detalle ?>" value="<?php if(@$det != NULL){ print @$det->cantidad; }?>" >
                            </td>
                            <td class="text-center">
                              <input type="text" class="form-control text-center precio" name="" id="<?php print @$det->id_detalle ?>" value="<?php if(@$det != NULL){ print @$det->precio; }?>" >
                            </td>
                            <td class="text-right">
                              <div id="<?php print @$det->id_detalle; ?>" class="subtotal">
                                <?php print $det->subtotal; ?>
                              </div>
                            </td>                        
                            <td class="text-right">
                              <div id="<?php print @$det->id_detalle; ?>" class="descsubtotal">
                                <?php print $det->descsubtotal; ?>
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

                    <div class="col-md-6 ">

                        <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                        <spam><strong>Forma de Pago</strong></spam>
                           <hr class="linea"> 
                        </div> 
                        <div class="box-body">
                          <table id="tpcontado" class="table">
                            <tr>
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
                              <td id="totalfp" class="text-right totalfp"><strong>0.00</strong></td>
                            </tr>
                            <tr>
                              <th class="text-right">Cambio</th>
                              <td id="cambio" class="text-right cambio"><strong>0.00</strong></td>
                            </tr>                    
                          </table>
                          <!--
                          <table id="tpcredito" class="table">
                            <tr>
                              <th><i class="fa fa-calendar" aria-hidden="true"></i> Días</th>
                              <td class="text-right">
                                <input type="text" class="text-right" name="cre_dias" id="cre_dias" value="0" style="width:70px;" >
                              </td>
                            </tr>
                           
                          </table> 
                          -->
                        </div>                         

                    </div>

                    <div class="col-md-6 ">
                      <div id="calmonto" class="pull-right" style="margin-right: 10px;">
                        <table class="table table-clear calmonto" >
                          <tbody>
                            <tr>
                              <td class="text-left"><strong>Subtotal IVA 12 %</strong></td>
                              <td id="msubtotalconiva" class="text-right msubtotalconiva">$<?php // print number_format(@$stciva,2,",","."); ?></td>                                        
                            <tr>
                            <tr>
                              <td class="text-left"><strong>Subtotal IVA 0 %</strong></td>
                              <td id="msubtotalsiniva" class="text-right msubtotalsiniva">$<?php // print number_format(@$stsiva,2,",","."); ?></td>                                        
                            <tr>
                            <tr>
                              <td class="text-left"><strong>Descuento</strong></td>
                              <td id="" class="text-right">
                                <input type="text" class="text-right descuento" name="" id="descuento" value="<?php // print number_format(@$desc,2,",","."); ?>" style="width:70px;" >
                              </td>                                        
                            </tr>
                            <tr>
                              <td class="text-left"><strong>Subtotal con Descuento IVA 12 %</strong></td>
                              <td id="descsubiva" class="text-right descsubiva">$<?php // print number_format(@$dstciva,2,",","."); ?></td>                                        
                            </tr>
                            <tr>
                              <td class="text-left"><strong>Subtotal con Descuento IVA 0 %</strong></td>
                              <td id="descsubcero" class="text-right descsubcero">$<?php // print number_format(@$dstsiva,2,",","."); ?></td>                                        
                            </tr>
                            <tr>
                              <td class="text-left"><strong>IVA (12%)</strong></td>
                              <td id="miva" class="text-right miva">$<?php // print number_format(@$montoiva,2,",","."); ?></td>                                        
                            </tr>
                            <tr>
                              <td class="text-left"><strong>Total</strong></td>
                              <td id="mtotal" class="text-right mtotal"><strong>$ <?php // $total = $subtotal + $montoiva; print number_format(@$total,2,",","."); ?></strong></td>                                        
                            </tr>      
                          </tbody>
                        </table>
                      </div>                
                    </div>

                  </div>

                  <div class="box-footer text-right">
                    <div class="row no-print ">
                    <div class="col-md-12">

                      <button id="imprimir" type="button" class="btn btn-primary btn-grad" >
                        <i class="fa fa-print"></i> Imprimir
                      </button>   
                               
                      <button id="pagar" type="button" class="btn btn-success btn-grad" >
                        <i class="fa fa-credit-card"></i> Pagar
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


  </section>


   <footer class="main-footer " style ="margin-left: 0px; height: 45px;">
    <div class="pull-right hidden-xs">
      <b>TECNOLOGIA INFORMATICA PROFESIONAL</b>
    </div>
    <strong>Copyright &copy; 2019 <a href="#"><?php print @$nombresistema; ?></a>.</strong> Todos los Derechos Reservados.
  </footer>

</body>
</html>