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
  <title><?php print @$nombresistema; ?> - Facturación</title>
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
   <!-- FIN DE INCLUDES PARA VALIDACION DE FORMULARIOS --> 
  <script src="<?php print $base_url; ?>public/js/blockui/jquery.blockUI.js"></script>  

  <?php 

  
  $parametro = &get_instance();
  $parametro->load->model("Parametros_model");
  $pedidovista = $parametro->Parametros_model->sel_pedidovista();
  $limiteprodventa = $parametro->Parametros_model->sel_limiteprodventa();
  $impuesto = $parametro->Parametros_model->sel_impuestoadicional();
  $impuestoadicional = $impuesto->valor;
  $descripcionimpuestoadicional = $impuesto->descripcion;
  $habserie = $parametro->Parametros_model->sel_numeroserie();
  $habilitaserie = $habserie->valor;
  $vernumerorden = $parametro->Parametros_model->sel_habilitaorden();
  $codigocliente = $parametro->Parametros_model->sel_codigocliente();
  $descpro = $parametro->Parametros_model->sel_descpro();
  $tipodescprod = $parametro->Parametros_model->sel_tipodescuentoproducto();   
  $habilitadetalletotaliva = $parametro->Parametros_model->sel_detalletotalivaventa();
  $tarifaiva = $parametro->Parametros_model->iva_get()->valor;
  $habilitaubicacion = $parametro->Parametros_model->sel_ubicacionventa();
  $habilitanotaventaiva = $parametro->Parametros_model->sel_habilitanotaventaiva();

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
      margin-bottom: 3px;
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
    /* Al pasar por encima de las sugerencias*/
    .autocomplete-jquery-item:hover{
        background-color: #E0F0FF;
        color:black;
    }

    .fontawesome-select {
        font-family: 'FontAwesome', 'Helvetica';
    }
    
    input[type="radio"], input[type="checkbox"] {
      margin: 2px 0 0;
      margin-left: 0px;
      margin-top: 1px \9;
      line-height: normal;
    }  

    #detcforpagtmp{
      display: none; 
    }  

    .tdprecio{
      width: 70px;
    }    

  </style>

<?php $xsxs = $this->session->userdata("tmp_forpago"); ?>  

<script type='text/javascript' language='javascript'>
  
  $( document ).ready(function() {

    function blinker() {
      $('.blinking').fadeOut(500);
      $('.blinking').fadeIn(500);
    }
    var id_categoriaventa = <?php if (@$cliente != NULL) { print $cliente->id_categoriaventa; } else { print 0;}  ?>;
    var blinkProc = null;
    if (id_categoriaventa != 0){
      blinkProc = setInterval(blinker, 1000); 

      $("#divCategVenta").show();
    }   

    var imprimepdf = <?php print $facturapdf; ?>;

    var fechafactura = $("#fecha").val();
    var fechaplazo = $("#fechal").val();
    var f1 = fechafactura;
    var f2 = fechaplazo;
    var diffec = restaFechas(f1,f2);
    /*alert(diffec);*/

    /*alert(fechafactura+" - "+fechaplazo);*/

    if(diffec <= 0){
      var d = 1;
      var fechpla = sumaFecha(d, fechaplazo);
      /*alert(fechpla);*/
      $("#fechal").val(fechpla);
      $('#dias').val(d);
    }

    var valfp = "<?php echo $xsxs ?>";
    if(valfp == 'Contado'){ 
      $("#detcforpagtmp").css("display", "none");
      $("#detforpagtmp").css("display", "inline");
    }else{ 
      $("#detcforpagtmp").css("display", "inline");
      $("#detforpagtmp").css("display", "none");
    }

    $("#imprimir").attr("disabled", true);

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
      registrar_cliente();
    });

    $(document).on('click','#proformas', function(){
      location.replace(base_url + 'proforma');
    })

  /* MOSTRAR LISTADO DE VENTA */
  $(document).on('click', '.venta', function(){
      id = $(this).attr('id');
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        href: "<?php echo base_url('facturar/facturar_ventas');?>",                
        ajax: {
           dataType: "html",
           type: "POST",
           data: { id: id },               
        },
        success: function(json) {
        }
      });

  });

  /* MOSTRAR Datos adicionales */
  $(document).on('click', '.add_dato', function(){
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        href: "<?php echo base_url('facturar/mostrar_datos_adicionales');?>",                
        ajax: {
           dataType: "html",
           type: "POST",
        },
        success: function(json) {
        }
      });

  });

  $(document).on('change', '.upd_datoadic', function(){
      var id = $(this).attr('id')
      var valor = $('.upd_datoadic[id='+ id +']').val();
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Facturar/upd_datoadicional_tmp",
          data: { id: id, valor: valor },
          success: function(json) {
          }
      });
  });


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
        url: base_url + "Facturar/nuevo",
        success: function(json) {
          location.replace(base_url + 'facturar/factura_deposito');
        }    
      });

    })

    $('#fechal').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
      });
    $('#fechal').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });


    $('#fecha').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
      });
    $('#fecha').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    $(document).on('change','#cmb_tipfac, #cmb_caja', function(){
      var idforma = $("#cmb_tipfac option:selected").val();
      var idcaja = $("#cmb_caja option:selected").val();
      var nrofac = $("#cmb_caja option:selected").attr('id');
      var nronota = $("#cmb_caja option:selected").attr('name');
      var observaciones = $('#txt_obs').val();

      var nrodoc = 0;
      if(idforma == 2){
        $('#factura').val(nrofac);
        nrodoc = $('#factura').val();
      }else{
        $('#notaventa').val(nronota);
        nrodoc = $('#notaventa').val();
      }
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Facturar/upd_ventnrodoc",
          data: { idforma: idforma, idcaja: idcaja, nrodoc: nrodoc, observaciones: observaciones },
          success: function(json) {
            location.reload();
          /*  window.location.reload(true);*/
          }
      });
    //  location.reload();
    });

    $(document).on('change','#txt_obs, #fecha', function(){
      var idforma = $("#cmb_tipfac option:selected").val();
      var idcaja = $("#cmb_caja option:selected").val();
      var nrofac = $("#cmb_caja option:selected").attr('id');
      var nronota = $("#cmb_caja option:selected").attr('name');
      var observaciones = $('#txt_obs').val();
      var fecha = $('#fecha').val();

      var nrodoc = 0;
      if(idforma == 2){
        $('#factura').val(nrofac);
        nrodoc = $('#factura').val();
      }else{
        $('#notaventa').val(nronota);
        nrodoc = $('#notaventa').val();
      }
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Facturar/upd_ventnrodoc",
          data: { idforma: idforma, idcaja: idcaja, nrodoc: nrodoc, 
                  observaciones: observaciones, fecha: fecha },
          success: function(json) {
          }
      });
    });


    $(document).on('click', '#txt_nro_ident', function(){
      $('#txt_nro_ident').val('');
      $('#txt_clinom').val('');
      $('#txt_telf').val('');
      $('#txt_correo').val('');
      $('#txt_dir').val(''); 
      $('#txt_ciudad').val('');
      $('#txt_clid').val('');      
      $('#txt_codigocliente').val('');                       
      $('#txt_creditod').val('0');  
      $('#txt_creditop').val('0');  
      $('#txt_creditotope').val('0');  
      $("input[name=optionsRadios][value='Contado']").prop("checked",true); 
      $("#detcforpagtmp").css("display", "none");
      $("#detforpagtmp").css("display", "inline");

    });


    /* BUSQUEDA DINAMICA POR CEDULA */
    $('#txt_nro_ident').change(function(){
      var idcliente = $(this).val();    

      if (idcliente === ""){
        alert("Debe ingresar un numero de identificación");
        return false;
      }   

      /* ruc / cedula valido*/
      var idtp = $('#cmb_tipident option:selected').val();      
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Utiles/validarIdentificacion",
          data: { tipo: idtp, identificacion: idcliente },
          success: function(json) {
            if (json.resu == 1){
              validarcliente(idcliente);
            } else {
                alert("El numero de identificación no es valido");
                $('#txt_nro_ident').focus();
                return false;
              } 
          }
      });
    });

    function validarcliente(idcliente){
      $("input[name=optionsRadios][value='Contado']").prop("checked",true); 
      $("#detcforpagtmp").css("display", "none");
      $("#detforpagtmp").css("display", "inline");

      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Facturar/valcliente');?>",
          data: {
              idcliente: idcliente
           },
          success: function(json) {
            if(json.mens == null){ 
              $('#menid').attr('class','col-md-6 has-error');
              $('#mennom').attr('class','col-md-10 has-error');
              $('#txt_clinom').val('');
              $('#txt_telf').val('');
              $('#txt_correo').val('');
              $('#txt_dir').val(''); 
              $('#txt_ciudad').val('');
              $('#txt_clid').val('');     
              $('#txt_codigocliente').val('');                        
              $('#txt_creditod').val('0');  
              $('#txt_creditop').val('0');  
              $('#txt_creditotope').val('0');  
              $('#txt_creditolimite').val('0');  
              $('#txt_placa').val(''); 

              $(".itemcredito").css("display", "none");

            }
            else { 
           
              $('#txt_clinom').val(json.mens.nom_cliente);
              $('#txt_clid').val(json.mens.id_cliente);
              $('#mennom').attr('class','col-md-10 has-success'); 
              $('#menid').attr('class','col-md-6 has-success');
              $('#txt_telf').val(json.mens.telefonos_cliente);
              $('#txt_correo').val(json.mens.correo_cliente);
              $('#txt_dir').val(json.mens.direccion_cliente); 
              $('#txt_ciudad').val(json.mens.ciudad_cliente);
              $('#txt_codigocliente').val(json.mens.codigo);
              $('#txt_placa').val(json.mens.placa_matricula); 
              if (json.credito != null){ 
                $('#txt_creditod').val(json.credito.total);  
                $('#txt_creditop').val(json.credito.pendiente);  
                $('#txt_creditotope').val(json.credito.topecredito);  
                $('#txt_creditolimite').val(json.credito.topecredito);  
              }
              else{
                $('#txt_creditod').val('0');  
                $('#txt_creditop').val('0');  
                $('#txt_creditotope').val('0');  
                $('#txt_creditolimite').val('0');  
              }  
              if(json.mens.tipo_ident_cliente == 'C'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='C' selected='TRUE'> Cédula </option><option value='P'> Pasaporte </option><option value='R'> R.U.C. </option></select>");
              }
              if(json.mens.tipo_ident_cliente == 'P'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='P' selected='TRUE'> Pasaporte </option><option value='C'> Cédula </option><option value='R'> R.U.C. </option></select>");
              }          
              if(json.mens.tipo_ident_cliente == 'R'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='R' selected='TRUE'> R.U.C. </option><option value='C'> Cédula </option><option value='P'> Pasaporte </option></select>");
              }                    

              nro_ident = $('#txt_nro_ident').val();
              consumidorfinal = (nro_ident.trim() == '') || (nro_ident.substring(1,4) == '999');             
              if (consumidorfinal == true){
                $(".itemcredito").css("display", "none");
              }
              else{
                 $(".itemcredito").css("display", "inline");
              }

              registrar_cliente();
            }
          }
      });

    }


    $('#txt_codigocliente').blur(function(){
      var codigo = $(this).val();    
      if (codigo.trim() == ""){
        return false;
      }   
      $("input[name=optionsRadios][value='Contado']").prop("checked",true); 
      $("#detcforpagtmp").css("display", "none");
      $("#detforpagtmp").css("display", "inline");

      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Facturar/get_clientecodigo');?>",
          data: {
              codigo: codigo
           },
          success: function(json) {
            if(json.mens == null){ 
              alert('No se Econtro Cliente Registrado con este Código');
              $('#txt_clinom').val('');
              $('#txt_telf').val('');
              $('#txt_correo').val('');
              $('#txt_dir').val(''); 
              $('#txt_ciudad').val('');
              $('#txt_clid').val('0'); 
              $('#txt_nro_ident').val('');                            
              $('#txt_creditod').val('0');  
              $('#txt_creditop').val('0');  
              $('#txt_creditotope').val('0');
              $('#txt_creditolimite').val('0');
              $('#txt_placa').val(''); 

              $(".itemcredito").css("display", "none");

            }
            else { 
              $('#txt_nro_ident').val(json.mens.ident_cliente);
              $('#txt_clinom').val(json.mens.nom_cliente);
              $('#txt_clid').val(json.mens.id_cliente);
              $('#mennom').attr('class','col-md-10 autocomplete has-success'); 
              $('#menid').attr('class','col-md-6 form-group  has-success');
              $('#txt_telf').val(json.mens.telefonos_cliente);
              $('#txt_correo').val(json.mens.correo_cliente);
              $('#txt_dir').val(json.mens.direccion_cliente); 
              $('#txt_ciudad').val(json.mens.ciudad_cliente);
              $('#txt_placa').val(json.mens.placa_matricula); 
              if (json.credito != null){ 
                $('#txt_creditod').val(json.credito.total);  
                $('#txt_creditop').val(json.credito.pendiente);  
                $('#txt_creditotope').val(json.credito.topecredito);
                $('#txt_creditolimite').val(json.credito.topecredito);
              }
              else{
                $('#txt_creditod').val('0');  
                $('#txt_creditop').val('0');  
                $('#txt_creditotope').val('0');
                $('#txt_creditolimite').val('0');
              }  

              if(json.mens.tipo_ident_cliente == 'C'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='C' selected='TRUE'> Cédula </option><option value='P'> Pasaporte </option><option value='R'> R.U.C. </option></select>");
              }
              if(json.mens.tipo_ident_cliente == 'P'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='P' selected='TRUE'> Pasaporte </option><option value='C'> Cédula </option><option value='R'> R.U.C. </option></select>");
              }          
              if(json.mens.tipo_ident_cliente == 'R'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='R' selected='TRUE'> R.U.C. </option><option value='C'> Cédula </option><option value='P'> Pasaporte </option></select>");
              }                    

              nro_ident = $('#txt_nro_ident').val();
              consumidorfinal = (nro_ident.trim() == '') || (nro_ident.substring(1,4) == '999');             
              if (consumidorfinal == true){
                $(".itemcredito").css("display", "none");
              }
              else{
                 $(".itemcredito").css("display", "inline");
              }

              registrar_cliente();
            }
          }
      });
    });



    $('.autocomplete').autocomplete();

    $(document).on('click', '.form-control', function(){  
      $('.autocomplete-jquery-results').each(function(index, el) {
        this.remove();
      });
    });


    /* MUESTRA DATOS DEL CLIENTE */
    $(document).on('click', '.autocomplete-jquery-item', function(){  
      var nom = $(this).text(); 
      if (nom === ""){
        alert("Debe ingresar un nombre");
        return false;
      }
      $("input[name=optionsRadios][value='Contado']").prop("checked",true); 
      $("#detcforpagtmp").css("display", "none");
      $("#detforpagtmp").css("display", "inline");
      
      $("#cmb_vendedor").prop("disabled", false);

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
              $('#mennom').attr('class','col-md-10 has-success'); 
              $('#menid').attr('class','col-md-6 has-success');
              $('#txt_telf').val(json.mens.telefonos_cliente);
              $('#txt_correo').val(json.mens.correo_cliente);
              $('#txt_dir').val(json.mens.direccion_cliente); 
              $('#txt_ciudad').val(json.mens.ciudad_cliente); 
              $('#txt_codigocliente').val(json.mens.codigo);   
              $('#txt_placa').val(json.mens.placa_matricula); 

              vendedorasociado = json.mens.id_vendedor;
              if (vendedorasociado != 0){
                $("#cmb_vendedor").val(vendedorasociado);
                perfil = <?php if(@$perfil != NULL) {print $perfil;} else { print 0;} ?>;
                if (perfil == 3){
                  $("#cmb_vendedor").prop("disabled", true);
                }  
              }

              if (json.credito != null){ 
                $('#txt_creditod').val(json.credito.total);  
                $('#txt_creditop').val(json.credito.pendiente);  
                $('#txt_creditotope').val(json.credito.topecredito);
                $('#txt_creditolimite').val(json.credito.topecredito);
              }
              else{
                $('#txt_creditod').val('0');  
                $('#txt_creditop').val('0');  
                $('#txt_creditotope').val('0');
                $('#txt_creditolimite').val('0');
              }  

              if(json.mens.tipo_ident_cliente == 'C'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='C' selected='TRUE'> Cédula </option><option value='P'> Pasaporte </option><option value='R'> R.U.C. </option></select>");
              }
              if(json.mens.tipo_ident_cliente == 'P'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='P' selected='TRUE'> Pasaporte </option><option value='C'> Cédula </option><option value='R'> R.U.C. </option></select>");
              }          
              if(json.mens.tipo_ident_cliente == 'R'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='R' selected='TRUE'> R.U.C. </option><option value='C'> Cédula </option><option value='P'> Pasaporte </option></select>");
              }             

              nro_ident = $('#txt_nro_ident').val();
              consumidorfinal = (nro_ident.trim() == '') || (nro_ident.substring(1,4) == '999');             
              if (consumidorfinal == true){
                $(".itemcredito").css("display", "none");
              }
              else{
                 $(".itemcredito").css("display", "inline");
              }

              registrar_cliente();

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
      var placa = $('#txt_placa').val(); 
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Facturar/upd_ventcliente",
          data: { idcli:id, idtp:idtp, nom:nom, tel:tel, cor:cor, dir:dir, ciu:ciu, idc: idc, placa: placa },
          success: function(json) {
            actualiza_categoria_venta(json.categoriacliente, json.nombrecategoria, json.logocategoria);
/*            var cliente = <?php if (@$cliente->nro_ident != NULL) { print $cliente->nro_ident; } else { print 0; } ?>;

            if ((json.resu != $('#cmb_tipfac').val()) || (id != cliente)) {
            //  location.reload();
            }      */
          }
      });
    }

    function actualiza_categoria_venta(id, categoria, logo){
      if (id == 0){
        clearInterval(blinkProc);
        $("#divObs").removeClass('col-md-11');
        $("#divObs").addClass('col-md-12');
        $("#divCategVenta").hide();
      }  
      else{
        blinkProc = setInterval(blinker, 1000); 
        $("#divObs").removeClass('col-md-12');
        $("#divObs").addClass('col-md-11');
        var tmpurl = $("#imgCateg").attr("src");
        pos = tmpurl.search('public/img');
        tmpurl = tmpurl.substring(0,pos+11) + "categoriaventa/" + logo;
        $("#imgCateg").attr("src", tmpurl);
        $("#labelObs").text(categoria);
        $("#divCategVenta").show();
      }
    }

      /* inserta producto en detalle 
      $(document).on('dblclick', '.factaddpro', function(){
        var limiteprodventa = <?php print $limiteprodventa; ?>;
        if (limiteprodventa == "") limiteprodventa = 0;
        if (limiteprodventa != 0){
          var cantprod = 0;
          $('.detallepro').each(function (index, value) { 
            cantprod ++; 
          });
          if (cantprod >= limiteprodventa){
            alert("La cantidad de productos en la factura esta limitada a " + limiteprodventa);
            return false;
          }
        }
        

        var id = $(this).attr('id');
        var idalm = $(this).attr('name');
        var factsexis = <?php print $factsexis; ?>;

        if (id) {
          $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Facturar/obtenerProductoDisponible",
            data: {id: id, idalm: idalm},
            success: function(json) {
              var disponible = parseFloat(json.disponible);
              if (factsexis == 1){
                $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: base_url + "Facturar/ins_detalleventatmp",
                  data: {id: id, idalm: idalm},
                  success: function(json) {
                      actualizar_subtotales();
                      $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                      $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                      credito();
                  }    
                });
              } else {
                if (disponible > 0 ){   
                  $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: base_url + "Facturar/ins_detalleventatmp",
                    data: {id: id, idalm: idalm},
                    success: function(json) {
                        actualizar_subtotales();
                        $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                        $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                        credito();
                    }    
                  });
                } else{
                  alert("No existe disponibilidad para el producto seleccionado");  
                }             
                
              }
            }    
          });
        }  
      });
*/
      /* inserta producto en detalle */
      $(document).on('dblclick', '.factaddpro', function(){
        var limiteprodventa = <?php print $limiteprodventa; ?>;
        if (limiteprodventa == "") limiteprodventa = 0;
        if (limiteprodventa != 0){
          var cantprod = 0;
          $('.detallepro').each(function (index, value) { 
            cantprod ++; 
          });
          if (cantprod >= limiteprodventa){
            alert("La cantidad de productos en la factura esta limitada a " + limiteprodventa);
            return false;
          }
        }
        

        var id = $(this).attr('id');
        var idalm = $(this).attr('name');
        var factsexis = <?php print $factsexis; ?>;
        var pago = $("#imprimir").attr("name");
    		if(pago == 0){
          if (id) {
          $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Facturar/obtenerProductoDisponible",
            data: {id: id, idalm: idalm},
            success: function(json) {
              var disponible = parseFloat(json.disponible);
              if (factsexis == 1){
                $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: base_url + "Facturar/ins_detalleventatmp",
                  data: {id: id, idalm: idalm},
                  success: function(json) {
                      actualizar_subtotales();
                      $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                      $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                      credito();
                  }    
                });
              } else {
                if (disponible > 0 ){   
                  $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: base_url + "Facturar/ins_detalleventatmp",
                    data: {id: id, idalm: idalm},
                    success: function(json) {
                        actualizar_subtotales();
                        $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                        $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                        credito();
                    }    
                  });
                } else{
                  alert("No existe disponibilidad para el producto seleccionado");  
                }             
                
              }
            }    
          });
          }  
        }else{
           alert("Genere una nueva Factura");
        }

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
          href: "<?php echo base_url('Facturar/edit_descripciondetalle');?>"
        });
      });

      $(document).on('click','.guardadescpro', function(){
        var id = $("#txt_iddetalle").val();
        var descripcion = $("#descripcion_detalle").val();
        
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Facturar/udp_descripciondetalle');?>",
          data: { id: id, descripcion: descripcion },
          success: function(json) {
            $.fancybox.close();
            $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
            $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          }
        });
      });

      $(document).on('click', '.factaddprox', function(){
        var limiteprodventa = <?php print $limiteprodventa; ?>;
        if (limiteprodventa == "") limiteprodventa = 0;
        if (limiteprodventa != 0){
          var cantprod = 0;
          $('.detallepro').each(function (index, value) { 
            cantprod ++; 
          });
          if (cantprod >= limiteprodventa){
            alert("La cantidad de productos en la factura esta limitada a " + limiteprodventa);
            return false;
          }
        }
        

        var id = $(this).attr('id');
        var idalm = $(this).attr('name');
        var factsexis = <?php print $factsexis; ?>;
        var pago = $("#imprimir").attr("name");
        if(pago == 0){
          if (id) {
          $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Facturar/obtenerProductoDisponible",
            data: {id: id, idalm: idalm},
            success: function(json) {
              var disponible = parseFloat(json.disponible);
              if (factsexis == 1){
                $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: base_url + "Facturar/ins_detalleventatmp",
                  data: {id: id, idalm: idalm},
                  success: function(json) {
                      actualizar_subtotales();
                      $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                      $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                    /*  credito();*/
                  }    
                });
              } else {
                if (disponible > 0 ){   
                  $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: base_url + "Facturar/ins_detalleventatmp",
                    data: {id: id, idalm: idalm},
                    success: function(json) {
                        actualizar_subtotales();
                        $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                        $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                      /*  credito();*/
                    }    
                  });
                } else{
                  alert("No existe disponibilidad para el producto seleccionado");  
                }             
                
              }
            }    
          });
          }  
        }else{
           alert("Genere una nueva Factura");
        }

      });
      /* inserta producto en detalle 
      $(document).on('click', '.idpropre', function(){
        var idpro = $(this).attr('id');
        var idc = $('#txt_clid').val();
        if (idpro) {
          $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Facturar/obtenerPrecios",
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
              url: base_url + "Facturar/obtenerPrecios",
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
           alert("Genere una nueva Factura");  
        }

      });

    $(document).on('change','#txt_codbar', function(){
      var codbar = $('#txt_codbar').val();
      var idalm = $('#cmb_almacenes').val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Facturar/ins_detalleventatmpcodbar",
        data: {codbar: codbar, idalm:idalm},
        success: function(json) {
            actualizar_subtotales();
            $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
            $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
            $('#txt_codbar').val("");
            credito();
        }    
      });      
    });

    $(document).on('change','#txt_serie', function(){
      var serie = $('#txt_serie').val();
      var idalm = $('#cmb_almacenes').val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Facturar/chk_estadoserie",
        data: {serie: serie, idalm:idalm},
        success: function(json) {
          if (json.resu == 0) {
            $.ajax({
              type: "POST",
              dataType: "json",
              url: base_url + "Facturar/ins_detalleventatmpserie",
              data: {serie: serie, idalm:idalm},
              success: function(json) {
                  actualizar_subtotales();
                  $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                  $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
                  $('#txt_serie').val("");
                  credito();
              }    
            });  
          } else {
            var strmsg = "";
            switch(json.resu) {
                case -1:
                    strmsg = "El numero de serie (" + serie + ") no existe.";
                    break;
                case 1:
                    strmsg = "El numero de serie (" + serie + ") ya ha sido vendido.";
                    break;
                default:
                    strmsg = "El numero de serie (" + serie + ") ya esta siendo procesado en una factura.";
            } 
            alert(strmsg);
          }     
        }    
      });  
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
              credito();
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
      var tp = 0;
      id = $(this).attr("id");
     
      tp = $('select[name='+id+']').val();
      if (tp == 'undefined' || tp == null || tp == '' || tp == ""){
        tp = 0;
      }
      
      cantidad = $(this).val();
      cantidad = cantidad.replace(',','');
      if (!(cantidad)) { cantidad = 0; }
      var factsexis = <?php print $factsexis; ?>;
      /* Obtener disponibilidad producto en almacen */
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/obtenerDisponibilidad');?>",
        data: { id: id },
        success: function(json) {
          var disponible = parseFloat(json.disponible);
          if (factsexis == 1){

          } else {
            if (disponible < cantidad){
              alert("La cantidad disponible es de " + disponible);
              cantidad = disponible;
              $(this).val(cantidad);
            }       
          }


 
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
          porcpro = $('.descpro[id='+id+']').val();
          if (!(porcpro)) { porcpro = 0; }

          $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('Facturar/upd_detalleventa');?>",
            data: { id: id, cantidad: cantidad, precio: precio,  valiva: valiva, subtotal: subtotal, tp: tp, porcpro: porcpro },
            success: function(json) {
              actualizar_subtotales();

              $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
              $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
              credito();
            }
          });
 
        }
      });

    });

    $(document).on('change','.descpro', function(){
      var cantidad = 0;
      var precio = 0;
      var subtotal = 0;
      var descsubtotal = 0;      
      var iva = 0.12;
      var valiva = 0;
      var subiva = 0;
      var desc = 0;
      var tp = 0;
      var porcpro = 0;
      var valdesc = 0;

      id = $(this).attr("id");
      tp = $('select[name='+id+']').val();
      if (tp == 'undefined' || tp == null || tp == '' || tp == ""){ tp = 0; }
      porcpro = $(this).val();
      if (!(porcpro)) { porcpro = 0; }
      cantidad = $('.cantidad[id='+id+']').val();
      cantidad = cantidad.replace(',','');
      precio = $('.precio[id='+id+']').val();
      precio = precio.replace(',','');
      subval = cantidad * precio;
      if( $('.grabaiva[id='+id+']').val() == 1 ) {
        valiva = (subval * (1 + iva)).toFixed(4) - subval.toFixed(4);
        valiva = valiva.toFixed(4);
        subiva = subval + valiva;        
      }else{
        subiva = subval;
        valiva = 0;
      }
      subtotal = subval.toFixed(4);
      descsubtotal = subtotal;

      var tipodescprod = <?php if (@$tipodescprod == 1) { print 1;} else { print 0;} ?>;
      tmpdesc = parseFloat(porcpro)
      tmpsubtotal = parseFloat(subtotal)
      if ((tipodescprod == 1) && (tmpdesc >= 100)){
        alert("El %descuento no puede ser mayor que 100")
        $(this).val(0)
        porcpro = 0        
      }
      if ((tipodescprod == 0) && (tmpdesc > tmpsubtotal)){
        alert("El descuento no puede ser mayor que el valor del producto")
        $(this).val(0)
        porcpro = 0
      }

      if(porcpro > 0){
        valdesc = porcpro / 100;
        descsubtotal = subtotal - subtotal * valdesc;        
      }else{
        descsubtotal = subtotal;
      }

      /* ACTUALIZA detalle */
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/upd_detalleventa');?>",
        data: { id: id, cantidad: cantidad, precio: precio,  valiva: valiva, subtotal: subtotal, tp: tp,
                porcpro: porcpro },
        success: function(json) {
          actualizar_subtotales();

         /* $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          credito();*/
        }
      });
    });
/*
    $('.editnocoma').keydown(function(e){
       var ingnore_key_codes = [188];//comma
       if ($.inArray(e.keyCode, ingnore_key_codes) >= 0){
          e.preventDefault();
       }
    });
*/
    /* GUARDAR detalles EN LA TABLA TEMPORAL Y REALIZAR CALCULO */
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
      if (!(precio)) { precio = 0; }
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
      porcpro = $('.descpro[id='+id+']').val();
      if (!(porcpro)) { porcpro = 0; }

      /* ACTUALIZA detalle */
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/upd_detalleventa');?>",
        data: { id: id, cantidad: cantidad, precio: precio,  valiva: valiva, subtotal: subtotal, tp: tp, porcpro: porcpro },
        success: function(json) {
          actualizar_subtotales();

          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          credito();
        }
      });
    });

    function actualizar_subtotales(){
      var forpago = $('input[id="forpago"]:checked').val();
      var impuestoadicional = <?php print $impuestoadicional; ?>;    
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
            var importeimpuestoadicional = 0;
            if (impuestoadicional > 0){
              importeimpuestoadicional = (parseFloat(json.descsubtotaliva) + parseFloat(json.descsubtotalcero)) * impuestoadicional / 100;
            }
            montototal = parseFloat(json.descsubtotaliva) + parseFloat(json.descsubtotalcero) + montoiva + importeimpuestoadicional;
            montoiva = montoiva.toFixed(2);
            montototal = montototal.toFixed(2);
            if (descuento > montototal){
              descuento = montototal;
            } else {
              descuento = descuento.toFixed(2);
            }

            var idforma = $("#cmb_tipfac option:selected").val();
            var habilitanotaventaiva = <?php if(@$habilitanotaventaiva != NULL) {print $habilitanotaventaiva;} else {print 0;}  ?>;

            if((idforma == 2) || (habilitanotaventaiva == 1)){
              $('.miva').html("$" + montoiva);
              $('.mtotal').html("$" + montototal);
              $('.mtotal').val(montototal);
              $('#totalcontado').val(json.montopagado);
              $('#efectivo').val(montototal);  
              $('#descuento').val(descuento);
            }else{
              var subtotaln = parseFloat(json.subtotaliva) + parseFloat(json.subtotalcero);
              subtotaln = subtotaln.toFixed(2); 
              $('#msubtotal').html("$" + subtotaln);
              $('.mtotaln').html("$" + montototal);
              $('.mtotaln').val(montototal);
              $('#totalcontado').val(json.montopagado);
              $('#efectivo').val(montototal);  
              $('#descuento').val(descuento);
            }
            if (impuestoadicional > 0){
              importeimpuestoadicional = importeimpuestoadicional.toFixed(2);
              $('#impuestoadicional').html("$" + importeimpuestoadicional);
            }  

            if(forpago == "Contado"){
              $('#detforpagtmp').load(base_url + "Facturar/actualiza_tablafp");
              $("#totalcontado").val(json.montopagado);

              var tmppagado = parseFloat(json.montopagado) - parseFloat(montototal);
              if (tmppagado < 0) tmppagado = 0;
              $("#txt_cambio").val(tmppagado.toFixed(2));
              $("#pagadonoefectivo").val(parseFloat(json.montopagado) - parseFloat(json.montopagadoefectivo));
            }
                   
                      
            $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
            credito();

            actualizar_botonpagar();
          }
      });
    }

    /* Actualizar descuento EN LA TABLA TEMPORAL Y REALIZAR CALCULO */
    $(document).on('change','.descuento', function(){
      ActualizaFacturaDescuento();
    });

    function ActualizaFacturaDescuento(){
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
          credito();
        }
      });
    }

    /* Actualizar descuento EN LA TABLA TEMPORAL Y REALIZAR CALCULO */
    $(document).on('change','.descuentop100', function(){
      descuento = $('#descuentop100').val();
      var idforma = $("#cmb_tipfac option:selected").val();
      if (descuento.trim() === ""){
        descuento = "0";
      } else {
        if (parseFloat(descuento) > 100){
          alert("El descuento no puede ser mayor que 100.");
          descuento = "100";
        }
      }
      if(idforma == 2) {    
        subtiva = $('#msubtotalconiva').html();
        subtiva = subtiva.substring(1,subtiva.length);
        subtcero = $('#msubtotalsiniva').html();
        subtcero = subtcero.substring(1,subtcero.length);
        descuento = (parseFloat(subtiva) + parseFloat(subtcero)) * parseFloat(descuento) / 100;
        $('#descuento').val(descuento.toFixed(2));      
        ActualizaFacturaDescuento();
      }else{
        subtotalnota = $('#msubtotal').html(); 
        subtotalnota = subtotalnota.substring(1,subtotalnota.length);
        descuento = (parseFloat(subtotalnota) ) * parseFloat(descuento) / 100;
        $('#descuento').val(descuento.toFixed(2)); 
        ActualizaFacturaDescuento();
      }
      
    });

    $(document).on('click','#pagar', function(){
      var cmb_caja = $('#cmb_caja').val();

      var idcliente = $('#txt_nro_ident').val();    

      if (idcliente === ""){
        alert("Debe ingresar un numero de identificación");
        return false;
      }   

      var nomcliente = $('#txt_clinom').val();    

      if (nomcliente === ""){
        alert("Debe ingresar el nombre del cliente");
        return false;
      }   

      /* ruc / cedula valido*/
      var idtp = $('#cmb_tipident option:selected').val();      
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Utiles/validarIdentificacion",
          data: { tipo: idtp, identificacion: idcliente },
          success: function(json) {
            if (json.resu != 1){
                alert("El numero de identificación no es valido");
                $('#txt_nro_ident').focus();
                return false;
            } 
            else{          
              $.ajax({
                type: "POST",
                dataType: "json",
                url: base_url + "Cajaapertura/cajaefectivo_estaabierta",
                data: { cmb_caja: cmb_caja},
                success: function(json) {
                  if(json.resu == 1){

                    var efectivo = $('#efectivo').val();
                    var tarjeta = $('#tarjeta').val();
                    var anticipo = $('#txt_anticipo').val();
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
                      var forpago = $('input[id="forpago"]:checked').val();

                      var habilitanotaventaiva = <?php if(@$habilitanotaventaiva != NULL) {print $habilitanotaventaiva;} else {print 0;}  ?>;
                      if((cmb_forma == 2) || (habilitanotaventaiva == 1)){  
                        var montototal = $('#mtotal').val();
                      }else{
                        var montototal = $('#mtotaln').val();
                      }


                     /* var montototal = $('#mtotal').val();
                      var efectivo = $('#efectivo').val();*/
                      var efectivo = $('#totalefectivo').val();

                      if(efectivo == '') { efectivo = 0; }
                      if(efectivo == 'NULL') { efectivo = 0; }  
                      if(efectivo == 'NaN') { efectivo = 0; }                  

                      /*var tarjeta = $('#tarjeta').val();
                      var tarjeta = $('#tarjeta').val();
                      if(tarjeta == '') { tarjeta = 0; }
                      if(tarjeta == 'NULL') { tarjeta = 0; }  
                      if(tarjeta == 'NaN') { tarjeta = 0; }*/

                      var totalcontado = $('#totalcontado').val();

              				if ((forpago == "Contado")  && ((parseFloat(totalcontado) - parseFloat(efectivo)) > parseFloat(montototal))){
              					alert("El Monto a pagar no debe ser mayor al Total de la Factura");
              					return false;
              				}  
              				
         				      var totalcre = $("#totalcre").val(); 
                      if ((forpago != "Contado")  && (parseFloat(totalcre) > parseFloat(montototal))){
                        alert("El Monto a pagar no debe ser mayor al Total de la Factura");
                        return false;
                      }  

                      if (forpago != "Contado"){
                        var creditop = $("#txt_creditop").val();
                        var creditod = $("#txt_creditod").val();
                        var creditotope = $("#txt_creditotope").val();
                        var sumatmpcredito = (parseFloat(montototal) + parseFloat(creditop));
                        if ((parseFloat(creditotope) > 0) && (sumatmpcredito > parseFloat(creditotope))){
                          alert("El monto (Total Factura + Credito Pendiente) de " + sumatmpcredito.toFixed(2) + ", no debe ser mayor que el límite de credito aprobado al cliente por el monto de $" + creditotope);                    
                          return false;
                        }
                      }  

                      if ((parseFloat(montototal) > 0) && ((forpago != "Contado") || 
                          (parseFloat(totalcontado) + parseFloat(anticipo) >= parseFloat(montototal)))){
                        $("#pagar").attr("disabled", true);
                        $("#pagar").css("display", "none");
                       
                        contabiliza = <?php if(@$contabiliza != NULL) {print $contabiliza;}  else {print 0;} ?>;
                        //alert('contabiliza ' + contabiliza);
                        if (contabiliza == 1){
                          cuentasconfig = <?php if(@$cuentasconfig != NULL) {print ($cuentasconfig == true)? 1 : 0;}  else {print 0;} ?>;
                        //alert('cuentasconfig ' + cuentasconfig);
                          cuentasconfigcobro = <?php if(@$cuentasconfigcobro != NULL) {print ($cuentasconfigcobro == true)? 1 : 0;}  else {print 0;} ?>;
                          
                          if ((cuentasconfig != 1) || (cuentasconfigcobro != 1)){
                            alert("Revise las categorias contables de Venta. Faltan cuentas por configurar.");                    
                            return false;
                          }
                        }

                        $.ajax({
                          type: "POST",
                          dataType: "json",
                          url: "<?php echo base_url('facturar/pagar_facturageneral');?>",
                          data: { cmb_forma: cmb_forma, 
                                  nro_factura: nro_factura, 
                                  nro_notaventa: nro_notaventa, 
                                  efectivo: efectivo, 
                                  tarjeta: tarjeta, 
                                  cambio: cambio,
                                  forpago: forpago },
                          success: function(json) {
                            $('.cantidad').attr("disabled", true);
                            $('.tipoprecio').attr("disabled", true);
                            $('.precio').attr("disabled", true);
                            $(".pro_del").css("display", "none");
                            $("#txt_codbar").attr("disabled", true);
                            $("#cmb_almacenes").attr("disabled", true);                       
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
                            $('#txt_ciudad').attr("disabled", true);
                            $('#cmb_tipident').attr("disabled", true);
                            $('.pro_del').attr("disabled", true);
                            
                            id = json.dat;
                            $("#imprimir").attr('name', id);

                            if (contabiliza == 1){
                              enviasri = (id != 0) && (json.enviosrifactura == 1);
                              contabilizar_venta(id, enviasri);
                            }
                            else{
                              if ((id != 0) && (json.enviosrifactura == 1)){
                                var idforma = $("#cmb_tipfac option:selected").val();
                                if (idforma == 2){
                                  enviar_factura_sri(id);
                                }  
                              }
                            }  
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
                  }  
                  else{
                    alert("No se ha realizado la apertura de Caja.");
                  }
                }
              });
            }
          }  
      });

    });

    function enviar_factura_sri(idfactura){
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 1},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
              $.blockUI({ message: '<h3> Enviando comprobante al SRI ...</h3>' });

              $.ajax({
                  type: "POST",
                  dataType: "json",
                  data: {id: idfactura},
                  url: base_url + "Infosri/EnviarSRI",
                  success: function(json) {
                    $.unblockUI();

                    if (json.resu == 1) { 
                    }
                    else{
                      alert("Error: " + json.mensaje);
                    }  
                  }
              });
          }
      });
    }

    function contabilizar_venta(id, enviasri){
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {id: id },
          url: base_url + "contabilidad/contab_comprobante/ins_comprobante_venta",
          success: function(json) {
            contabilizar_cobro(id, enviasri);
          }
      });
    }

    function contabilizar_cobro(id, enviasri){
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {id: id },
          url: base_url + "contabilidad/contab_comprobante/factura_con_abonos",
          success: function(json) {
            if (json == true){
              $.ajax({
                  type: "POST",
                  dataType: "json",
                  data: {id: id },
                  url: base_url + "contabilidad/contab_comprobante/ins_comprobante_cobrodocventa",
                  success: function(json) {
                    if (enviasri == true){
                      enviar_factura_sri(id);
                    }  
                  }
              });
            }    
          }  
      });

    }

    function actualizar_botonpagar(){
      var montototal = $('.mtotal').val();

      var efectivo = $('#efectivo').val();
      if(efectivo == '') { efectivo = 0; }
      if(efectivo == 'NULL') { efectivo = 0; }  
      if(efectivo == 'NaN') { efectivo = 0; }                  
      var tarjeta = $('#tarjeta').val();
      if(tarjeta == '') { tarjeta = 0; }
      if(tarjeta == 'NULL') { tarjeta = 0; }  
      if(tarjeta == 'NaN') { tarjeta = 0; }  

      var totalpagado = parseFloat(efectivo)+parseFloat(tarjeta);
      var cambio = parseFloat(efectivo)+parseFloat(tarjeta)-parseFloat(montototal);

      if ((parseFloat(montototal) > 0) && (parseFloat(efectivo)+parseFloat(tarjeta) >= parseFloat(montototal))){
      } else {
        cambio = 0;
      }

      totalpagado = totalpagado.toFixed(2);  
      cambio = cambio.toFixed(2);  
      if(totalpagado == 'NaN') { totalpagado = 0; }
      if(cambio == 'NaN') { cambio = 0; }
      $('.totalfp').html("$" + totalpagado);
      $('.cambio').html("$" + cambio);
      credito();
    }

    /* Verificar si se puede habilitar boton de pago */
    $(document).on('change','#efectivo', function(){
      var efectivo = $('#efectivo').val();
      if (efectivo.trim() === "") {
        efectivo = "0";
        if(efectivo == 'NaN') { efectivo = 0; }
      } else {
        efectivo = parseFloat(efectivo).toFixed(2);
        if(efectivo == 'NaN') { efectivo = 0; }
      }
      $('#efectivo').val(efectivo);
      actualizar_botonpagar();
    });

    /* Verificar si se puede habilitar boton de pago */
    $(document).on('change','#tarjeta', function(){
      var tarjeta = $('#tarjeta').val();
      if (tarjeta.trim() === "") {
        tarjeta = "0";
      } else {
        tarjeta = parseFloat(tarjeta).toFixed(2);
        if(tarjeta == 'NaN') { tarjeta = 0; }
      }
      $('#tarjeta').val(tarjeta);
      actualizar_botonpagar();
    });

      /* MODIFICAR ALMACEN */
      $(document).on('click', '.promonto', function(){
          var limiteprodventa = <?php print $limiteprodventa; ?>;
          if (limiteprodventa == "") limiteprodventa = 0;
          if (limiteprodventa != 0){
            var cantprod = 0;
            $('.detallepro').each(function (index, value) { 
              cantprod ++; 
            });
            if (cantprod >= limiteprodventa){
              alert("La cantidad de productos en la factura esta limitada a " + limiteprodventa);
              return false;
            }
          }

          id = $(this).attr('id'); 
          idalm = $(this).attr('name'); 

          $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Facturar/obtenerProductoDisponible",
            data: {id: id, idalm: idalm},
            success: function(json) {
              var disponible = parseFloat(json.disponible);
              if (disponible > 0){
                $.ajax({
                 type: "POST",
                 dataType: "json",
                 url: "<?php echo base_url('Facturar/tmp_gas');?>",
                 data: {id: idalm},
                 success: function(json) {
                    $.fancybox.open({
                      type: "ajax",
                      width: 550,
                      height: 550,
                      href: "<?php echo base_url('Facturar/montogas');?>",                
                      ajax: {
                         dataType: "html",
                         type: "POST"               
                      }
         
                    });
                 }
                });
              }  else {
                alert("No existe disponibilidad para el producto seleccionado");
              }
            }    
          });
          credito();

      })  

      $(document).on('click', '.promontoOK', function(){
          idpro = $(this).attr('id'); 
          idalm = $(this).attr('name'); 

          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('Facturar/tmp_gas');?>",
           data: {id: idalm},
           success: function(json) {
              $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                href: "<?php echo base_url('Facturar/montogas');?>",                
                ajax: {
                   dataType: "html",
                   type: "POST"               
                },
                success: function(json) {
                }

     
              });
           }
          });
          credito();
      })  


    /* Boton del listado para imprimir compra 
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
                href: "<?php //echo base_url('Facturar/imprimirventa');?>" 
              });
    });*/

    $(document).on('click', '#imprimir', function(){
      var id = $(this).attr('name');
      var idforma = $("#cmb_tipfac option:selected").val();

      /*if ((imprimepdf == 1) || (idforma != 2)){*/
      if (imprimepdf == 1) {
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
        if (imprimepdf == 0){
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
          }

    });










    

    /* Boton de Caja */
    $(document).on('click', '#caja', function(){
      var existeapertura = $(this).val();
      if (existeapertura == 0){
        $.fancybox.open({
                  type: "ajax",
                  width: 550,
                  height: 550,
                  ajax: {
                     dataType: "html",
                     type: "POST"
                  },
                  href: "<?php echo base_url('Facturar/aperturacaja');?>", 
                  success: function(json) {
                    if(json.resu == 1){
                      $("#caja").html("Cierre Caja");
                      $("#caja").val(1);
                    }  
                  }
                });
      } else {
        $.fancybox.open({
                  type: "ajax",
                  width: 550,
                  height: 550,
                  ajax: {
                     dataType: "html",
                     type: "POST"
                  },
                  href: "<?php echo base_url('Facturar/cierrecaja');?>", 
                  success: function(json) {
                    if(json.resu == 1){
                      $("#caja").html("Apertura Caja");
                      $("#caja").val(0);
                    }
                  }
                });        
      } 
    });

    function actualizabotoncaja(){

      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Cajaapertura/existeapertura",
          success: function(json) {
            if(json.resu == 1){
              $("#caja").html("Cierre Caja");
              $("#caja").val(1);
            }  
            else{
              $("#caja").html("Apertura Caja");
              $("#caja").val(0);
            }
          }
      });
    }    

    /* MOSTRAR VISTA PEDIDO */
    $(document).on('click','#pedido', function(){
      location.replace(base_url + 'pedido');
    })

    actualizabotoncaja();
    actualizar_subtotales();

    /* tipoprecio */
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
        url: "<?php echo base_url('Facturar/upd_detalleventa');?>",
        data: { id: id, cantidad: cantidad, precio: precio,  valiva: valiva, subtotal: subtotal, tp: tp },
        success: function(json) {
          actualizar_subtotales();

          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          credito();
        }
      });    

    });


    $(document).on('click', '.add_fp', function(){
      var forpago = $('input[id="forpago"]:checked').val();
      var montototal = $("#mtotal").val(); 
      var totalcre = $("#totalcre").val(); 
      var totalcontado = $("#totalcontado").val(); 
      var pagadonoefectivo = $("#pagadonoefectivo").val(); 
      var anticipo = $("#txt_anticipo").val(); 
      if(forpago == "Contado"){
        var montopendiente = parseFloat(montototal) - parseFloat(pagadonoefectivo) - parseFloat(anticipo);
        /*var montopendiente = parseFloat(montototal) - parseFloat(totalcontado); */
      } else {
        var montopendiente = parseFloat(montototal) - parseFloat(totalcre) - parseFloat(anticipo);         
      }  
      $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
            dataType: "html",
            type: "POST",
            data: {forpago: forpago, montopendiente: montopendiente},
          },
          href: base_url + "Facturar/tipopago"
      });
    });

    $(document).on('click', '.pago_rapido', function(){
      var tipopago = $(this).attr('id');
      //alert("tipopago " + tipopago);
      var forpago = $('input[id="forpago"]:checked').val();
      var montototal = $("#mtotal").val(); 
      var totalcre = $("#totalcre").val(); 
      var totalcontado = $("#totalcontado").val(); 
      var pagadonoefectivo = $("#pagadonoefectivo").val(); 
      var anticipo = $("#txt_anticipo").val(); 
      if(forpago == "Contado"){
        var montopendiente = parseFloat(montototal) - parseFloat(pagadonoefectivo) - parseFloat(anticipo);
        /*var montopendiente = parseFloat(montototal) - parseFloat(totalcontado); */
      } else {
        var montopendiente = parseFloat(montototal) - parseFloat(totalcre) - parseFloat(anticipo);         
      }  
      $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
            dataType: "html",
            type: "POST",
            data: {forpago: forpago, montopendiente: montopendiente, pago_rapido: tipopago},
          },
          href: base_url + "Facturar/tipopago"
      });
    });

    $(document).on('click', '.guardafp', function(){
        var forpago = $('input[id="forpago"]:checked').val();
        var idreg = $("#txt_idreg").val();
        var fp = $("#cmb_forpago").val();
        var monto = $("#txt_montofp").val();
        monto = monto.replace(',','');
        var idventa = $("#txt_idventa").val();
        var fechat = $("#fechat").val();
        var tiptarjeta = $("#cmb_tarjeta").val();
        var nrotar = $("#txt_nrotar").val();
        var bco = $('#cmb_banco option:selected').val(); 
        var tbco = $('#cmbt_banco option:selected').val(); 
        var nrodoc = $("#txt_nrodoc").val();
        var descdoc = $("#txt_descdoc").val();
        var tnrodoc = $("#txt_tnrodoc").val();
        var tdescdoc = $("#txt_tdescdoc").val();        
        var fechae = $("#fechae").val();
        var fechac = $("#fechac").val();
        var nrocta = $("#txt_nrocta").val();

        if (fp == 0){
          alert("Debe seleccionar una forma de pago");   
          return false;
        }
        if ((monto.trim() == '') || (monto == 0)){
          alert("Debe ingresar el monto a pagar");   
          return false;
        }
        if ((fp != 1) && (fp != 1) && (bco == 0) && (tbco == 0)){
          alert("Debe seleccionar el banco");   
          return false;
        }
        if ((fp != 1) && (fp != 1) && (tbco != 0) && (tiptarjeta == 0)){
          alert("Debe seleccionar el tipo de tarjeta");   
          return false;
        }
        
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {idreg: idreg, idventa: idventa, fp: fp, monto: monto, fechat: fechat, tiptarjeta: tiptarjeta, nrotar: nrotar, bco: bco, tbco: tbco, tnrodoc: tnrodoc, nrodoc: nrodoc, tdescdoc: tdescdoc, descdoc: descdoc, fechae: fechae, fechac: fechac, nrocta: nrocta},                
            url: base_url + "Facturar/addfp",
            success: function(json) {
              $("#totalefectivo").val(json.efectivo);
              if(forpago == "Contado"){
                $('#detforpagtmp').load(base_url + "Facturar/actualiza_tablafp");
                $("#totalcontado").val(json.monto);
              }else{
                $('#detforpagctmp').load(base_url + "Facturar/actualiza_tablafp");
                $("#totalcre").val(json.monto);
                credito();
              }
              var tmppagado = parseFloat(json.monto) - parseFloat($("#mtotal").val());
              if (tmppagado < 0) tmppagado = 0;
              $("#txt_cambio").val(tmppagado.toFixed(2));
              $("#pagadonoefectivo").val(parseFloat(json.monto) - parseFloat(json.efectivo));
            }
        });  


        $.fancybox.close();
        
    });

    $(document).on('click','.fp_del', function() {
      var forpago = $('input[id="forpago"]:checked').val();
      var idreg = $(this).attr("id");
      var idfp = $(this).attr("name");
      var idventa = $("#txt_idventa").val();
      if (conf_del()) {
        $.ajax({
            url: base_url + "Facturar/delfp",
            data: {idreg: idreg, idfp: idfp, idventa: idventa },
            type: 'POST',
            dataType: 'json',
            success: function(json) {
              $("#totalefectivo").val(json.efectivo);
              if(forpago == "Contado"){
                $('#detforpagtmp').load(base_url + "Facturar/actualiza_tablafp");
                $("#totalcontado").val(json.monto);
              }else{
                $('#detforpagctmp').load(base_url + "Facturar/actualiza_tablafp");
                $("#totalcre").val(json.monto);
                credito();
              }
              var tmppagado = parseFloat(json.monto) - parseFloat($("#mtotal").val());
              if (tmppagado < 0) tmppagado = 0;
              $("#txt_cambio").val(tmppagado.toFixed(2));
              $("#pagadonoefectivo").val(parseFloat(json.monto) - parseFloat(json.efectivo));
            }
        });
      }
      return false; 
    });

    function conf_del() {
        return  confirm("¿Confirma que desea eliminar este registro?");
    }

    $(document).on('click', '.fp_edi', function(){
      var forpago = $('input[id="forpago"]:checked').val();
      var idreg = $(this).attr("id");
      var idfp = $(this).attr("name");
      var idventa = $("#txt_idventa").val();      

      var montoformapago = $('.montoformapago[id="'+idreg+'"]').attr("name");

      var montototal = $("#mtotal").val(); 
      var totalcre = $("#totalcre").val(); 
      var totalcontado = $("#totalcontado").val(); 
      var pagadonoefectivo = $("#pagadonoefectivo").val(); 
      if(forpago == "Contado"){
        var montopendiente = parseFloat(montototal) - parseFloat(pagadonoefectivo) + parseFloat(montoformapago);
        if (parseFloat(montopendiente) > parseFloat(montototal)){
          montopendiente = parseFloat(montototal);
        }
      }
      else {
        var montopendiente = parseFloat(montototal) - parseFloat(totalcre);         
      }      
      montopendiente = montopendiente.toFixed(2);

      $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
            dataType: "html",
            type: "POST",
            data: {idreg: idreg, idfp: idfp, idventa: idventa, montopendiente: montopendiente},
          },
          href: base_url + "Facturar/editipopago" 
      });
    });

/*  $(document).on('change','#forpago', function(){*/


  $(document).on('click','#forpago', function(){
    var formapago = $(this).val();
    var idventa = $("#txt_idventa").val();
    var creditop = $("#txt_creditop").val();
    var creditod = $("#txt_creditod").val();
    var creditotope = $("#txt_creditotope").val();

    var nro_ident = $("#txt_nro_ident").val();
    
    if(formapago == 'Contado'){ 
      $("#detcforpagtmp").css("display", "none");
      $("#detforpagtmp").css("display", "inline");
      $.ajax({
          url: base_url + "Facturar/tmp_forpago",
          data: {formapago: formapago, idventa: idventa},
          type: 'POST',
          dataType: 'json',
          success: function(json) {
              $("#totalefectivo").val(json.efectivo);
              $("#totalcontado").val(json.monto);
        }
      });      
    }else{ 
      consumidorfinal = (nro_ident.trim() == '') || (nro_ident.substring(1,4) == '999');
      if ( (consumidorfinal == true) /*|| ((creditotope > 0) && (creditop > 0))*/ ){
        if (consumidorfinal == true){
          alert("Para pagar a Credito ingrese un cliente.");
        }
       /* else{
          alert("A este Cliente no se le puede otorgar Credito\nhasta que no cancele "+ creditop+ " que tiene pendiente");
        }*/
        $("input[name=optionsRadios][value='Contado']").prop("checked",true); 
        $("#detcforpagtmp").css("display", "none");
        $("#detforpagtmp").css("display", "inline");
      }else{
        $("#detcforpagtmp").css("display", "inline");
        $("#detforpagtmp").css("display", "none");
        $.ajax({
            url: base_url + "Facturar/tmp_forpago",
            data: {formapago: formapago, idventa: idventa },
            type: 'POST',
            dataType: 'json',
            success: function(json) {
                $("#totalefectivo").val(json.efectivo);
                $("#totalcre").val(json.monto);
                credito();
            }
        });       
      }  
    }
  }); 

  $(document).on('change','#fechal', function(){
    var fechaplazo = $(this).val();
    var plazo = fechaplazo.split("/");
    var fplazo = Date.parse(plazo[2] + '-' +  plazo[1] + '-' + plazo[0]);
    var fecha = $("#fecha").val();
    var ini = fecha.split("/");
    var fini = Date.parse(ini[2] + '-' +  ini[1] + '-' + ini[0]);
    var diaEnMils = 1000 * 60 * 60 * 24;
    dias = (fplazo - fini) / diaEnMils;
    $("#dias").val(dias);
    credito();
  }); 

  $(document).on('keyup','#interes', function(){
    credito();
  });

  $(document).on('keyup','#mora', function(){
    credito();
  });

  $(document).on('keyup','#cuotas', function(){
    credito();
  });


  function credito(){
    var idforma = $("#cmb_tipfac option:selected").val();
    var habilitanotaventaiva = <?php if(@$habilitanotaventaiva != NULL) {print $habilitanotaventaiva;} else {print 0;}  ?>;
    var abono = 0;
    var montocredito = 0;
    var montofactura = 0;
    var montobasecredito = 0;
    var montointerescredito = 0;
    var fechafactura = $("#fecha").val();
    var ff = fechafactura.split("/");
    var ffactura = ff[2] + '-' +  ff[1] + '-' + ff[0];    
    var idventa = $("#txt_idventa").val(); 
    var fechaplazo = $("#fechal").val();
    var plazo = fechaplazo.split("/");
    var fplazo = plazo[2] + '-' +  plazo[1] + '-' + plazo[0];
    var dias = $("#dias").val();
    var interes = $("#interes").val();
    if (interes == 'undefined' || interes == null || interes == '' || interes == ""){ interes = 0; }    
    var mora = $("#mora").val();
    if (mora == 'undefined' || mora == null || mora == '' || mora == ""){ mora = 0; }    
    var cuotas = $("#cuotas").val();
    if (cuotas == 'undefined' || cuotas == null || cuotas == '' || cuotas == ""){ cuotas = 1; }  
    abono = $("#totalcre").val(); 
    if (abono == 'undefined' || abono == null || abono == '' || abono == ""){ abono = 0; $("#totalcre").val(abono); }
    if ((idforma == 2) || (habilitanotaventaiva == 1)){  
      montofactura = $(".mtotal").val();
    }else{
      montofactura = $(".mtotaln").val();
    }
    var anticipo = $('#txt_anticipo').val();
    montobasecredito = parseFloat(montofactura) - parseFloat(abono) - parseFloat(anticipo);
    montointerescredito = parseFloat(montobasecredito) * (parseFloat(interes) / 100);
    montocredito = parseFloat(montofactura) - parseFloat(abono) - parseFloat(anticipo) + parseFloat(montointerescredito);

  /*  alert(montofactura+' - '+abono+' - '+montointerescredito+' - '+montocredito); */

    var f1 = fechafactura;
    var f2 = fechaplazo;
    var diffecha = restaFechas(f1,f2);

    var forpago = $('input[id="forpago"]:checked').val();

    if(forpago == "Contado") return;

    if(diffecha > 0){
      $.ajax({
          url: base_url + "Facturar/add_creditotmp",
          data: { idventa: idventa, fplazo: fplazo, dias: dias, interes: interes, mora: mora, cuotas: cuotas, abono: abono, forpago: forpago,
                  montobasecredito: montobasecredito, montointerescredito: montointerescredito, montocredito: montocredito, ffactura: ffactura },
          type: 'POST',
          dataType: 'json',
          success: function(json) {
              $("#totalefectivo").val(json.efectivo);
              if(forpago == "Contado"){
                $("#totalcontado").val(json.monto);
              }else{
                $("#totalcre").val(json.monto);
              }
            }
      });      
    }else{
      alert("La Fecha del Credito debe ser mayor a la Fecha de Factura");
    }

    

    
  }

  function restaFechas(f1,f2)
   {
     var aFecha1 = f1.split('/'); 
     var aFecha2 = f2.split('/'); 
     var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]); 
     var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]); 
     var dif = fFecha2 - fFecha1;
     var dias = Math.floor(dif / (1000 * 60 * 60 * 24)); 
     return dias;
   }


  function sumaFecha(d, fecha)
  {
   var Fecha = new Date();
   var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());
   var sep = sFecha.indexOf('/') != -1 ? '/' : '-'; 
   var aFecha = sFecha.split(sep);
   var fecha = aFecha[2]+'/'+aFecha[1]+'/'+aFecha[0];
   fecha= new Date(fecha);
   fecha.setDate(fecha.getDate()+parseInt(d));
   var anno=fecha.getFullYear();
   var mes= fecha.getMonth()+1;
   var dia= fecha.getDate();
   mes = (mes < 10) ? ("0" + mes) : mes;
   dia = (dia < 10) ? ("0" + dia) : dia;
   var fechaFinal = dia+sep+mes+sep+anno;
   return (fechaFinal);
   }

  var nrofactura = $("#cmb_caja option:selected").attr('id');
  $("#factura").val(nrofactura);
  var nronota = $("#cmb_caja option:selected").attr('name');
  $("#notaventa").val(nronota);

  tmpcant = $('#cmb_caja > option').length;
  if (tmpcant <= 1){
    $('#cmb_caja').attr('disabled', true);
  } else{
    $('#cmb_caja').attr('disabled', false);
  }

  $(document).on('click','.pro_serimei', function(){
    var idproser = $(this).attr('id');
    var iddet = $(this).attr('name');
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        ajax: {
           dataType: "html",
           type: "POST",
           data: {id: idproser, iddet: iddet},
        },
        href: "<?php echo base_url('facturar/select_serieimei');?>",
        afterClose: function(){
            actualizar_subtotales();

            $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
            //$('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
            credito();

        }
      });
  });

  $(document).on('click','.pro_serimei00', function(){
    var idproser = $(this).attr('id');
    var iddet = $(this).attr('name');
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        ajax: {
           dataType: "html",
           type: "POST",
           data: {id: idproser, iddet: iddet},
        },
        href: "<?php echo base_url('facturar/add_serieimei');?>"
      });
  });

  $(document).on('click','.addimaiserie', function(){
    var idserie = $(this).attr('id');
      $.ajax({
        url: base_url + "Facturar/add_imeiserietmp",
        data: { idserie: idserie },
        type: 'POST',
        dataType: 'json',
        success: function(json) {
          $.fancybox.close();
          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
        }
      }); 
  });

    $(document).on('change','#cmb_vendedor', function(){
      var idvendedor = $("#cmb_vendedor option:selected").val();
      var idventa = $("#txt_idventa").val();
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Facturar/upd_vendedor",
          data: { idvendedor: idvendedor, idventa: idventa },
          success: function(json) {
          }
      });
    });

    $(document).on('change','.comision', function(){
      var idforma = $("#cmb_tipfac option:selected").val();
      comision = $('#comision').val();
      if (comision.trim() === ""){
        comision = "0";
      } 
      if(idforma == 2) {  
        subtiva = $('#msubtotalconiva').html();
        subtiva = subtiva.substring(1,subtiva.length);
        subtcero = $('#msubtotalsiniva').html();
        subtcero = subtcero.substring(1,subtcero.length);
        comision = parseFloat(comision);
        $('#txt_comision').val(comision.toFixed(4));      
        ActualizaFacturaComision();
      }else{
        subtotalnota = $('#msubtotal').html(); 
        subtotalnota = subtotalnota.substring(1,subtotalnota.length);
        comision = parseFloat(comision);
        $('#txt_comision').val(comision.toFixed(4));
        ActualizaFacturaComision();      
      }
    });


    function ActualizaFacturaComision(){
      comision = $('#txt_comision').val();
      if (comision.trim() === ""){
        comision = "0";
      }
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/upd_comisionventatmp');?>",
          data: { comision: comision},
        success: function(json) {
          actualizar_subtotales();

          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          credito();
        }
      });
    }

    $(document).on('click','.guardaprodeposito', function(){
      
      var txt_cant = $("#txt_cant").val();
      var txt_monto = $("#txt_monto").val();
      var txt_alm = $("#txt_alm").val();
      
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/addgas');?>",
        data: { txt_cant: txt_cant, txt_monto: txt_monto, txt_alm: txt_alm },
        success: function(json) {
//          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          $('#detalletmp').load(base_url + "Facturar/actualiza_tablageneral");
          actualizar_subtotales();
          $.fancybox.close();
        }
      });
    });


    $(document).on('click', '.precio, .cantidad, .descpro', function(){
        $(this).select();
    });

    $(document).on('click', '#existencias', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('facturar/mostrar_existenciaproducto');?>" 
        });
    });


  var muestraanticipo = <?php if (@$anticipo != NULL) { print @$anticipo; } else { print 0; } ?>;
  if (muestraanticipo == '') { muestraanticipo = 0; }
  if (muestraanticipo == '0') {$("#anticipo").hide();}

  nro_ident = $('#txt_nro_ident').val();
  consumidorfinal = (nro_ident.trim() == '') || (nro_ident.substring(1,4) == '999');             
  if (consumidorfinal == true){
    $(".itemcredito").css("display", "none");
  }
  else{
     $(".itemcredito").css("display", "inline");
  }



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

        <button id="inicio" type="button" class="btn bg-black " >
          <i class="fa fa-home"></i> Inicio
        </button>  

        <button id="nuevo" type="button" class="btn btn-success " >
          <i class="fa fa-file-text-o"></i> Nuevo
        </button> 
<!--
        <button id="caja" type="button" class="btn bg-yellow " >
          <i class="fa fa-money"></i> Caja
        </button>  
-->
        <?php if (@$perfil == 1) { ?>                       
          <button id="<?php print $idusu; ?>" type="button" class="btn btn-primary venta" >
            <i class="fa fa-shopping-cart"></i> Ventas 
          </button> 
        <?php } ?>                       

        <button id="proformas" type="button" class="btn bg-yellow " >
          <i class="fa fa-file-powerpoint-o"></i> Proformas
        </button>         

        <?php if(@$pedidovista->valor == 1){ ?>

        <button id="pedido" type="button" class="btn bg-yellow " >
          <i class="fa fa-cutlery"></i> Pedido
        </button>  

        <?php }   ?>

        <button id="existencias" type="button" class="btn btn-primary" title="Existencia en Almacenes" >
          <i class="fa fa-cubes"></i> Existencias
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

            <div class="col-md-2" style="margin-bottom: 5px; padding-left: 0px; ">
              <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
               <div class="col-md-3">
                 <label>Caja</label>
               </div>  
               <div class="col-md-9">
                <select id="cmb_caja" name="cmb_caja" class="form-control">
                  <?php 
                    if(@$lstcaja != NULL){ ?>
                  <?php } else { ?>
                      <option  value="0" selected="TRUE">Seleccione...</option>
                  <?php } 
                          if (count($lstcaja) > 0) {
                            foreach ($lstcaja as $caja):
                                if(@$cliente->id_caja != NULL){
                                    if($caja->id_caja == $cliente->id_caja){ ?>
                                        <option  value="<?php  print $caja->id_caja; ?>" id="<?php  print $caja->nrofactura; ?>" name="<?php  print $caja->consecutivo_notaventa; ?>" selected="TRUE"><?php  print $caja->nom_caja; ?></option> 
                                        <?php
                                    }else{ ?>
                                        <option value="<?php  print $caja->id_caja; ?>" id="<?php  print $caja->nrofactura; ?>" name="<?php  print $caja->consecutivo_notaventa; ?>"> <?php  print $caja->nom_caja; ?> </option>
                                        <?php
                                    }
                                }else{ ?>
                                    <option value="<?php  print $caja->id_caja; ?>" id="<?php  print $caja->nrofactura; ?>" name="<?php  print $caja->consecutivo_notaventa; ?>"> <?php  print $caja->nom_caja; ?> </option>
                                    <?php
                                    }   ?>
                                <?php

                            endforeach;
                          }
                          ?>
                </select>  
               </div>  
              </div>
            </div>

            <div class="col-md-2" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
              <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
               <div class="col-md-4">
                <label>Documento</label>
               </div> 
               <div class="col-md-8">
                 <input type="hidden" id="txt_idventa" name="txt_idventa" value="<?php if(@$cliente != NULL){ print @$cliente->id_venta; }?>" > 
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
            </div>

            <div class="col-md-2" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
              <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
               <div class="col-md-3">
                <label>Número</label>
               </div> 
               <div class="col-md-9">
                <div class="col-md-12 evapago" style="padding-right: 0px; padding-left: 0px;">
                  <?php if($cliente->tipo_doc == 2){ ?>
                  <input type="text" class="form-control validate[required] text-center" id="factura" name="factura" disabled="" value="<?php if(@$nrofactura != NULL){ print @$nrofactura; }?>">
                  <?php } else {?>
                  <input type="text" class="form-control validate[required] text-center" id="notaventa" name="notaventa" value="<?php if(@$nronv != NULL){ print @$nronv; }?>"> 
                  <?php } ?> 
                </div>   
               </div> 
              </div>
            </div>

            <div class="col-md-2" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
              <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
               <div class="col-md-3">
                <label>Fecha</label>
               </div> 
               <div class="col-md-9">
                <div class="input-group date">
                    <input type="text" class="form-control text-center validate[required]" id="fecha" name="fecha" value="<?php if(@$cliente != NULL){ @$fec = str_replace('-', '/', @$cliente->fecha); @$fec = date("Y/m/d", strtotime(@$fec)); print @$fec; } ?>" style="width: 90px;" >
                </div>    
               </div> 
              </div>
            </div>

            <div class="col-md-2" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
              <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
               <div class="col-md-4">
                <label>Vendedor</label>
               </div> 
               <div class="col-md-8">
                <select id="cmb_vendedor" name="cmb_vendedor" class="form-control">
                    <?php 
                      if(@$vendedor != NULL){ ?>
                        <option  value="" selected="TRUE">Seleccione...</option>
                    <?php } else { ?>
                        <option  value="" selected="TRUE">Seleccione...</option>
                    <?php } 
                              if (count($vendedor) > 0) {
                                foreach ($vendedor as $vd):
                                    if(@$cliente->id_vendedor != NULL){
                                        if($vd->id_usu == $cliente->id_vendedor){ ?>
                                            <option  value="<?php  print $vd->id_usu; ?>" selected="TRUE"><?php  print $vd->vendedor; ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $vd->id_usu; ?>"> <?php  print $vd->vendedor; ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $vd->id_usu; ?>"> <?php  print $vd->vendedor; ?> </option>
                                        <?php
                                        }   ?>
                                    <?php

                                endforeach;
                              }
                              ?>
                </select>   
               </div>
              </div>
            </div>            


            <?php if(($vernumerorden == 1) && (@$cliente->nro_orden != NULL)) { ?>
              <div class="col-md-1" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
                  <label id="labelorden">#Orden</label>
                  <input type="text" class="form-control validate[required] text-center" readonly id="" name="" value="<?php if(@$cliente != NULL){ print @$cliente->nro_orden; }?>">   
                </div>
              </div>
            <?php } ?>

            <?php if (@$lstdatoadicional != NULL)  { ?>
              <div class="pull-right">                    
                  <button type="button" class="btn btn-success btn-sm btn-grad add_dato" title="Datos Adicionales">
                    <i class="fa fa-plus-square"></i> Adicional
                  </button>                     
              </div>
            <?php } ?>

          </div>

          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

                <?php if($codigocliente == 1){ ?>
                <div class="col-md-2" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                  <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
                   <div class="col-md-4">
                    <label>Código</label>
                   </div> 
                   <div class="col-md-8" style="padding-right: 0px; padding-left: 0px;">
                      <input type="text" class="form-control validate[required]" name="txt_codigocliente" id="txt_codigocliente" placeholder="Código Cliente" value="<?php if(@$cliente != NULL){ print @$cliente->codigo; }?>" >
                   </div> 
                  </div>
                </div>
                <?php } ?>

                <div class="col-md-2" style="margin-bottom: 5px; padding-left: 0px; ">
                  <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                   <div class="col-md-5" style="padding-right: 0px;">
                    <label>Tipo Ident</label>
                   </div> 
                   <div class="col-md-7 tipident">
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
                </div>

                <div class="col-md-2" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                  <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
                   <div class="col-md-6">
                    <label>Nro.Identidad</label>
                   </div> 
                    <div id="menid" class="col-md-6" style="padding-right: 0px; padding-left: 0px;">
                      <input type="hidden" id="txt_clid" name="txt_clid" value="<?php if(@$cliente != NULL){ print @$cliente->id_cliente; }?>" >
                      <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$cliente != NULL){ print @$cliente->nro_ident; }?>" >
                    </div>   
                  </div>
                </div>

                <div class="col-md-4" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                  <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
                   <div class="col-md-2">
                    <label>Cliente</label>
                   </div> 
                    <div id="mennom" class="col-md-10 autocomplete" style="padding-left: 0px; padding-right: 0px;">
                      <input type="text" class="form-control" name="txt_clinom" id="txt_clinom" placeholder="Nombre del Cliente" value="<?php if(@$cliente != NULL){ print @$cliente->nom_cliente; }?>" data-source="<?php echo base_url('facturar/valclientenombre?nombre=');?>">
                    </div>   
                  </div>
                </div>

                <div class="col-md-2" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                  <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
                   <div class="col-md-4">
                    <label>Teléfono</label>
                   </div> 
                    <div id="" class="col-md-8" style="padding-right: 0px; padding-left: 0px;">
                      <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_telf" id="txt_telf" placeholder="Telefono" value="<?php if(@$cliente != NULL){ print @$cliente->telf_cliente; }?>" >
                    </div> 
                  </div>
                </div>

                <div class="col-md-2" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                  <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
                   <div class="col-md-4">
                    <label>Correo</label>
                   </div> 
                    <div id="" class="col-md-8" style="padding-right: 0px; padding-left: 0px;">
                      <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_correo" id="txt_correo" placeholder="Correo" value="<?php if(@$cliente != NULL){ print @$cliente->correo_cliente; }?>" >
                    </div> 
                  </div>
                </div>  

              </div>

              <div class="col-md-12">

                <div class="col-md-4" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                  <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px; ">
                   <div class="col-md-2">
                    <label>Direccion</label>
                   </div> 
                    <div id="" class="col-md-10" style="padding-right: 0px; padding-left: 0px;">
                      <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_dir" id="txt_dir" placeholder="Direccion" value="<?php if(@$cliente != NULL){ print @$cliente->dir_cliente; }?>" >
                    </div> 
                  </div>
                </div>               

                <div class="col-md-2" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                  <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
                    <div class="col-md-4">
                      <label>Ciudad</label>
                    </div>  
                    <div id="" class="col-md-8" style="padding-right: 0px; padding-left: 0px;">
                      <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_ciudad" id="txt_ciudad" placeholder="Ciudad" value="<?php if(@$cliente != NULL){ print @$cliente->ciu_cliente; }?>" >
                    </div> 
                  </div>
                </div>  

                <div class="col-md-2" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                  <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
                    <div class="col-md-4">
                      <label>Placa</label>
                    </div>  
                    <div id="" class="col-md-8" style="padding-right: 0px; padding-left: 0px;">
                      <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_placa" id="txt_placa" placeholder="Placa" value="<?php if(@$cliente != NULL){ print @$cliente->placa_matricula; }?>" >
                    </div> 
                  </div>
                </div>  

                <div class="col-md-4 itemcredito" >
                  <input type="hidden" id="txt_creditotope" name="txt_creditotope" value="<?php if(@$mcredito != NULL){ print @$mcredito->topecredito; } else {print 0;} ?>" >
                  <div class="col-md-4" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                    <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
                     <div class="col-md-5">
                      <label>Crédito</label>
                     </div> 
                      <div id="" class="col-md-7" style="padding-right: 0px; ">
                        <input type="text" class="form-control col-md-3 text-right" name="txt_creditod" id="txt_creditod" placeholder="" value="<?php if (@$mcredito != NULL) {print @$mcredito->total;} else { print 0;} ?>" disabled >
                      </div> 
                    </div>
                  </div>  
                  <div class="col-md-4" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                    <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
                     <div class="col-md-6">
                      <label>Pendiente</label>
                     </div> 
                      <div id="" class="col-md-6" style="padding-right: 0px; ">
                        <input type="text" class="form-control col-md-3 text-right" name="txt_creditop" id="txt_creditop" placeholder="" value="<?php if (@$mcredito != NULL) {print @$mcredito->pendiente;} else { print 0;} ?>" disabled >
                      </div> 
                    </div>
                  </div> 
                  <div class="col-md-4" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                    <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
                     <div class="col-md-5">
                      <label>Límite</label>
                     </div> 
                      <div id="" class="col-md-7" style="padding-right: 0px; ">
                        <input type="text" class="form-control col-md-3 text-right" name="txt_creditolimite" id="txt_creditolimite" placeholder="" value="<?php if (@$mcredito != NULL) {print @$mcredito->topecredito;} else { print 0;} ?>" disabled >
                      </div> 
                    </div>
                  </div> 
                </div> 

                <div class="col-md-12" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                 <?php if (@$cliente != NULL) { if ($cliente->id_categoriaventa == 0) { ?>
                  <div id="divObs" class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px; ">
                 <?php } else { ?>
                  <div id="divObs" class="form-group col-md-11" style="margin-bottom: 5px; padding-left: 0px; ">
                 <?php } } ?>

                   <div class="col-md-1">
                    <label>Observaciones</label>
                   </div> 
                    <div id="" class="col-md-11 " style="padding-right: 0px; padding-left: 0px;">
                      <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_obs" id="txt_obs" placeholder="Observaciones" value="<?php if(@$cliente != NULL){ print @$cliente->observaciones; }?>" >
                    </div> 
                  </div>

                  <div class="col-md-1" id="divCategVenta" style="display:none">
                    <div class="blinking">
                      <img class="img-circle " class="user-image" width="25px" height="25px" id="imgCateg" 
                      <?php if ($cliente->icono_path != '') { ?>
                        src="<?php print base_url(); ?>public/img/categoriaventa/<?php print $cliente->icono_path;?>" 
                      <?php } else { ?>
                        src="<?php print base_url(); ?>public/img/perfil.jpg" 
                      <?php }  ?>
                      />
                      <label id="labelObs"><?php if (@$cliente != NULL) { print $cliente->categoriaventa; } ?></label>
                    </div>
                  </div>

                </div>               

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
                            <div class="col-md-12" style="padding-left: 0px; <?php if($pa->existencia>0){$color='color: black';} else {$color='color: red';} print $color ?>;">
                              <!--
                              <a id="<?php print $pa->pro_id; ?>" name="<?php print $pa->almacen_id; ?>" class="btn btn-app promonto">
                                  <img class="progas img-responsive" <?php
                              /*  if (@$pa != NULL) {
                                  if ($pa->pro_imagen) { print " src='data:image/jpeg;base64,$pa->pro_imagen'"; } 
                                  else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } } 
                                else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } ?> 
                                alt="" onerror="this.src='<?php print base_url() . "public/img/perfil.jpg";*/ ?>';" />
                              </a>
                            -->

                              <div class="col-md-3">
                                <a id="<?php print $pa->pro_id; ?>" name="<?php print $pa->almacen_id; ?>" class="promonto">
                                <img class="profile-user-img img-responsive img-thumbnail" width="100" height="100" <?php
                                  if (@$pa != NULL) {
                                  if ($pa->pro_imagen) { print " src='data:image/jpeg;base64,$pa->pro_imagen'"; } 
                                  else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } } 
                                else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } ?> 
                                alt="<?php print $pa->pro_nombre; ?>" title="<?php print $pa->pro_nombre; ?>" onerror="this.src='<?php print base_url() . "public/img/perfil.jpg"; ?>';" />
                                </a>
                                <?php print $pa->almacen_nombre; ?>
                              </div>                            







                            
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
                        <div class="table-responsive">
                         <table id="TableProducto" class="table table-bordered table-hover ">
                          <thead>
                            <tr>
                              <th>Cod Barra</th>
                              <th>Nombre</th>
                              <th>Precio</th>
                              <th>Existencia</th>
                              <?php                            
                                if ($habilitaubicacion == 1) {
                              ?>
                                <th>Ubicación</th>
                              <?php
                                }
                              ?>
                              <th>Almacén</th>
                            </tr>
                            </thead>    
                            <tbody>                                                        
                              <?php 
                              
                              foreach ($pro as $p) {
                              ?>
                                <tr class="factaddpro idpropre" style="background-color: <?php if ($p->existencia <= $p->min and $p->existencia > 0 and $p->preparado == 0 && $p->pro_esservicio == 0) { print '#FFB40F'; } 
                                                                                                else{ if ($p->existencia <= 0 and $p->preparado == 0 && $p->pro_esservicio == 0) { print '#DD4B39'; } else{  print '#fff'; } 
                                                                                               }  ?>" 
                                                                                      id="<?php print $p->pro_id; ?>" name="<?php print $p->id_alm; ?>">
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
                                  <?php                            
                                    if ($habilitaubicacion == 1) {
                                  ?>
                                    <td>
                                      <?php print $p->ubicacion; ?>
                                    </td>        
                                  <?php
                                    }
                                  ?>                                                           
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
                                    <!--  <a id="<?php //print $lpro->id; ?>"  name="<?php //print $lpro->id_alm;?>" class="btn btn-app factaddpro idpropre"><i class="fa fa-beer" aria-hidden="true"></i> <?php //print $lpro->producto ?>
                                      </a>-->
                                    <div class="col-md-3">
                                      <a id="<?php print $lpro->id; ?>" name="<?php print $lpro->id_alm; ?>" class="factaddpro idpropre">
                                      <img class="profile-user-img img-responsive img-thumbnail" width="100" height="100" <?php
                                        if (@$lpro != NULL) {
                                        if ($lpro->imagen_path != '') { ?> src="<?php print base_url(); ?>public/img/producto/<?php print $lpro->imagen_path; ?>" <?php } 
                                        /*if ($lpro->pro_imagen) { print " src='data:image/jpeg;base64,$lpro->pro_imagen'"; } */
                                        else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } } 
                                      else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } ?> 
                                      alt="<?php print $lpro->producto; ?>" title="<?php print $lpro->producto; ?>" onerror="this.src='<?php print base_url() . "public/img/perfil.jpg"; ?>';" />
                                      <?php print substr($lpro->producto,0,20); ?> </a>
                                    </div>                                       
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

                    <div class="col-md-3" style="padding-left: 0px;">
                      <h3 class="box-title">Productos a Facturar</h3>  
                    </div>

                    <div class="col-md-3">
                      <?php if(@$habilitaserie == 1){ ?>
                        <input type="text" class="form-control col-md-3 numeroserie text-center" name="txt_serie" id="txt_serie" placeholder="Numero Serie" value="" >  
                      <?php } ?>
                    </div>
                    
                    <div class="col-md-3">
                      <input type="text" class="form-control col-md-3 codbar text-center" name="txt_codbar" id="txt_codbar" placeholder="Codigo de Barra" value="" >  
                    </div>

                    <!--<div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px; display: <?php if(count($almacenes) > 1) {print 'block';} else {print 'none';} ?>;">
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
                    </div>-->

                   
                  </div>
                  <div id="detalletmp" class="box-body table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Producto</th>
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
                          <?php if($habilitadetalletotaliva == 1) { ?>                     
                            <th class="text-center col-md-1">SubT/IVA</th>                             
                          <?php } ?>                          
                          <th class="text-center col-md-1">Acción</th>
                        </tr>
                      </thead>    
                      <tbody>                                                        
                        <?php 
                        $num=0;
                        foreach ($lstdetalle as $det) {
                          $num++;
                        ?>
                          <tr class="detallepro" id="<?php print $det->id_detalle; ?>" title="<?php if (@$det->id_serie != NULL) { print 'Serie: ' . $det->numeroserie; } ?>">
                            <td>
                              <?php print $num; ?>
                              <input type="hidden" class="grabaiva" id="<?php print @$det->id_detalle ?>" name="" value="<?php print @$det->pro_grabaiva ?>" >    
                            </td>
                            <td >
                              <a style="color: #449B2E;" href="#" title="Editar" id="<?php print $det->id_detalle; ?>" class="facteditprox"><i class="fa fa-edit"></i></a>
                              <?php if (@$det->id_serie != NULL) { print '<u>'; } ?>
                                <?php print substr($det->pro_nombre, 0, 35); ?>
                              <?php if (@$det->id_serie != NULL) { print '</u>'; } ?>
                            </td>
                            <td class="text-center datacantidad" id="<?php print @$det->id_detalle ?>">
                              <input type="text" class="form-control text-center cantidad tdprecio" name="" id="<?php print @$det->id_detalle ?>" value="<?php if(@$det != NULL){ print number_format(@$det->cantidad, $decimalescantidad); }?>" >
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
                                                  <option value="<?php  print $pp->idprepro;?>" selected="TRUE"> <?php  print $pp->desc_precios." - ".$pp->precio ?> </option>
                                      <?php
                                              }else{ ?>
                                              <option value="<?php  print $pp->idprepro; ?>"> <?php  print $pp->desc_precios." - ".$pp->precio ?>  </option>
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
                              <input type="text" class="form-control text-center precio tdprecio editnocoma" name="" id="<?php print @$det->id_detalle ?>" value="<?php if(@$det != NULL){ print number_format(@$det->precio,$decimalesprecio); }?>" 
                                <?php if ((@$cambioprecio == 1) && (@$perfil != 1)) {print "disabled";} ?>                       
                              >
                              
                            </td>
                            <td class="text-right">
                              <div id="<?php print @$det->id_detalle; ?>" class="subtotal">
                                <?php print number_format($det->subtotal,2); ?>
                              </div>
                            </td>       
                            <?php if($descpro == 1) { ?>                     
                              <td class="text-center">
                                <input type="text" class="form-control text-center descpro" name="" 
                                  id="<?php print @$det->id_detalle ?>" 
                                  value="<?php if(@$det != NULL){ if (@$tipodescprod == 1) {$tmpdesc = @$det->porcdesc;} else {$tmpdesc = @$det->descmonto;} print number_format(@$tmpdesc, 2); }?>" >
                              </td>                              
                            <?php } ?>

                            <td class="text-right">
                              <div id="<?php print @$det->id_detalle; ?>" class="descsubtotal">
                                <?php print number_format($det->descsubtotal,2); ?>
                              </div>
                            </td>                        

                            <?php if($habilitadetalletotaliva == 1) { ?>                     
                              <td class="text-center">
                                <div id="<?php print @$det->id_detalle; ?>" class="detalletotaliva">
                                  <?php if($det->pro_grabaiva == 1) {$tmpvalor = round(($det->precio * $det->cantidad - $det->descmonto) * (1 + $tarifaiva) ,2);} else {$tmpvalor = $det->descsubtotal;} print number_format($tmpvalor,2); ?>
                                </div>
                              </td>                              
                            <?php } ?>

                            <td class="text-center">
                                <?php if(($habilitaserie == 1) && ($det->estserie > 0)) { ?>
                                <a href="#" title="Nro Serie/Imei" id="<?php  if(@$det != NULL){ print @$det->id_producto; }?>" name="<?php  if(@$det != NULL){ print @$det->id_detalle; }?>" class="btn btn-xs btn-warning btn-grad pro_serimei"><i class="fa fa-slack"></i></a>
                                <?php } ?>                                
                                <a href="#" title="Eliminar" id="<?php  if(@$det != NULL){ print @$det->id_detalle; }?>" class="btn btn-xs btn-danger btn-grad pro_del"><i class="fa fa-trash-o"></i></a>
                            </td>
                          </tr>
                        <?php 
                        
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="box-footer clearfix">

                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">

                      <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                        <div class="" style="">
                          <?php $idfp = $this->session->userdata("tmp_forpago"); ?>
                          <spam><strong>Cancelación: </strong>&nbsp;</spam>
                          <span class="">
                              <input type="hidden" class="totalefectivo" id="totalefectivo" value="<?php if($efectivo != NULL){ print $efectivo; }else{ print 0; } ?>">    
                              <input type="hidden" class="totalcontado" id="totalcontado" value="<?php if($abono != NULL){ if($idfp == "Contado"){ print $abono;} else{ print 0; } }else{ print 0; } ?>">    
                              <input type="hidden" id="pagadonoefectivo" value="0">    

                            <label class="radio-inline">
                              <input type="radio" id="forpago" name="optionsRadios" <?php if($idfp == "Contado"){ print "checked";} ?> value="Contado"> Contado
                            </label>
                            <label class="radio-inline">
                              <input type="radio" id="forpago" name="optionsRadios" <?php if($idfp == "Credito"){ print "checked";} ?>  value="Credito"> Crédito
                            </label>   
                          </span>
                          <span class="pull-right">
                            <a style="color: #449B2E;" href="#" title="Agregar Forma de Pago" id="" class="add_fp"><i class="fa fa-plus-square fa-lg"></i></a>
                          </span>
                        </div>
                        <hr class="linea">
                        <div id="detforpagtmp" class="box-body table-responsive">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th style="width: 10px">#</th>
                                <th>Forma de Pago</th>
                                <th class="text-center col-md-1">Monto</th>
                                <th class="text-center col-md-1">Acción</th>
                              </tr>
                            </thead>    
                            <tbody>                                                        
                              <?php 
                              $num=0;
                              foreach ($lstforpago as $lfp) {
                                if($lfp->id_tipcancelacion == 1){
                                  $num++; 
                              ?>
                                <tr class="" id="">
                                  <td>
                                    <?php print $num; ?>
                                  </td>
                                  <td>
                                    <?php print $lfp->nomfp; ?>
                                  </td>
                                  <td class="text-center montoformapago" id="<?php if(@$lfp != NULL){ print @$lfp->idreg; }?>" name="<?php if(@$lfp != NULL){ print @$lfp->monto; }?>">
                                    <?php print $lfp->monto; ?>
                                  </td>
                                  <td class="text-center">
                                      <a style="color: #094074;" href="#" title="Editar" id="<?php if(@$lfp != NULL){ print @$lfp->idreg; }?>" name="<?php if(@$lfp != NULL){ print @$lfp->id_formapago; }?>" class="fp_edi"><i class="fa fa-pencil-square-o fa-lg"></i></a> &nbsp;&nbsp;
                                      <a style="color: #B80C09;" href="#" title="Eliminar" id="<?php if(@$lfp != NULL){ print @$lfp->idreg; }?>" name="<?php if(@$lfp != NULL){ print @$lfp->id_formapago; }?>" class="fp_del"><i class="fa fa-minus-circle fa-lg"></i></a>
                                  </td>
                                </tr>
                              <?php 
                                }
                              }
                              ?>
                            </tbody>
                          </table>
                        </div>

                        <div id="detcforpagtmp" class="box-body table-responsive" style="padding: 0px;">
                          <div class="col-md-3" style="padding-left: 0px; margin-bottom: 0px;">
                            <label for="">Fecha Limite</label>
                            <div style="padding-left: 0px; margin-bottom: 0px; width: 88px;" class="form-group">
                              <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control pull-right validate[required]" id="fechal" name="fechal" value="<?php if(@$crecuo->fechalimite != NULL){ @$fecl = str_replace('-', '/', @$crecuo->fechalimite); @$fecl = date("d/m/Y", strtotime(@$fecl)); print @$fecl; } else { print date("d/m/Y"); } ?>" type="text">
                              </div>                             
                            </div>
                          </div>
                          <div class="col-md-2" style="padding-left: 0px; margin-bottom: 0px;">
                            <label for="">Días</label>
                            <div style="padding-left: 0px; margin-bottom: 0px; width: 50px;" class="form-group">
                              <input class="form-control pull-right validate[required] text-center" id="dias" name="dias" value="<?php if($crecuo != NULL){ print $crecuo->dias; }else{ print 0; } ?>" type="text" readonly>
                            </div>
                          </div>
                          <div class="col-md-3" style="padding-left: 0px; margin-bottom: 0px;">
                            <label for="">Nro Cuotas</label>
                            <div style="padding-left: 0px; margin-bottom: 0px; " class="form-group">
                              <input class="form-control pull-right validate[required] text-center" id="cuotas" name="cuotas" value="<?php if($crecuo != NULL){ print $crecuo->cantidadcuotas; }else{ print 1; } ?>" type="text" >
                            </div>
                          </div>
                          <div class="col-md-2" style="padding-left: 0px; padding-right: 0px; margin-bottom: 0px;">
                            <label for="">% Interes</label>
                            <div style="padding-left: 0px; margin-bottom: 0px; " class="form-group">
                              <input class="form-control pull-right validate[required] text-center" id="interes" name="interes" value="<?php if($crecuo != NULL){ print $crecuo->p100interes_credito; }else{ print 0; } ?>" type="text" >
                            </div>
                          </div>     
                          <div class="col-md-2" style="padding-right: 0px; margin-bottom: 0px;">
                            <label for="">% Mora</label>
                            <div style="padding-left: 0px; margin-bottom: 0px; " class="form-group">
                              <input class="form-control pull-right validate[required] text-center" id="mora" name="mora" value="<?php if($crecuo != NULL){ print $crecuo->p100interes_mora; }else{ print 0; } ?>" type="text" >
                            </div>
                          </div> 
                          <div class="col-md-12" style="padding-right: 0px; padding-bottom: 0px; padding-top: 10px; ">
                            <div class="col-md-9 text-right" style="padding-right: 0px; ">
                              Monto Total Abono
                            </div>                            
                            <div class="col-md-3 pull-right" style="padding-right: 0px; ">
                              <input class="form-control pull-right validate[required] text-center" id="totalcre" name="totalcre" value="<?php if($abono != NULL){ print $abono; }else{ print 0; } ?>" type="text" readonly>    
                            </div>
                            
                          </div> 
                          <div id="detforpagctmp" class="box-body table-responsive">                                             
                            <table class="table table-bordered" style="margin-top: 0px;">
                              <thead>
                                <tr>
                                  <th style="width: 10px">#</th>
                                  <th>Abono Inicial</th>
                                  <th class="text-center col-md-1">Monto</th>
                                  <th class="text-center col-md-1">Acción</th>
                                </tr>
                              </thead>    
                              <tbody>                                                        
                                <?php 
                                $numc=0;
                                foreach ($lstforpago as $lfpc) {
                                  if($lfpc->id_tipcancelacion == 2){
                                  $numc++;                                   
                                ?>
                                  <tr class="" id="">
                                    <td>
                                      <?php print $numc; ?>
                                    </td>
                                    <td>
                                      <?php print $lfpc->nomfp; ?>
                                    </td>
                                    <td class="text-center">
                                      <?php print $lfpc->monto; ?>
                                    </td>
                                    <td class="text-center">
                                        <a style="color: #094074;" href="#" title="Editar" id="<?php if(@$lfpc != NULL){ print @$lfpc->idreg; }?>" name="<?php if(@$lfp != NULL){ print @$lfp->id_formapago; }?>" class="fp_edi"><i class="fa fa-pencil-square-o fa-lg"></i></a> &nbsp;&nbsp;
                                        <a style="color: #B80C09;" href="#" title="Eliminar" id="<?php if(@$lfpc != NULL){ print @$lfpc->idreg; }?>" name="<?php if(@$lfp != NULL){ print @$lfp->id_formapago; }?>" class="fp_del"><i class="fa fa-minus-circle fa-lg"></i></a>
                                    </td>
                                  </tr>
                                <?php 
                                  }
                                }
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div>









                      </div>
                    </div>

                    <div class="col-md-6 ">
                      <div id="calmonto" class="pull-right" style="margin-right: 10px;">
                        <table class="table table-clear calmonto" >
                          <tbody>
                           <tr>
                              <td class="text-left"><strong>Comision (%)</strong></td>
                              <td id="" class="text-right">
                                <input type="text" class="text-right comision" name="" id="comision" value="0" style="width:70px;" >
                                <input type="hidden" id="txt_comision" name="txt_comision" value="0" > 
                              </td>                                        
                            </tr>                            
                            <?php 
                              if((@$cliente->tipo_doc == 2) || (@$habilitanotaventaiva == 1)){
                            ?>
                            <tr>
                              <td class="text-left"><strong>Subtotal IVA 12 %</strong></td>
                              <td id="msubtotalconiva" class="text-right msubtotalconiva">$<?php // print number_format(@$stciva,2,",","."); ?></td>                                        
                            <tr>
                            <tr>
                              <td class="text-left"><strong>Subtotal IVA 0 %</strong></td>
                              <td id="msubtotalsiniva" class="text-right msubtotalsiniva">$<?php // print number_format(@$stsiva,2,",","."); ?></td>                                        
                            <tr>
                            <?php if($descpro == 0) { ?>                             
                            <tr>
                              <td class="text-left"><strong>Descuento (%)</strong></td>
                              <td id="" class="text-right">
                                <input type="text" class="text-right descuentop100" name="" id="descuentop100" value="" style="width:70px;" >
                              </td>                                        
                            </tr>
                            <tr>
                              <td class="text-left"><strong>Descuento (Valor)</strong></td>
                              <td id="" class="text-right">
                                <input type="text" class="text-right descuento" name="" id="descuento" value="<?php // print number_format(@$desc,2,",","."); ?>" style="width:70px;" >
                              </td>                                        
                            </tr>
                            <?php } ?>                                                      
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
                            <?php if ($impuestoadicional > 0) { ?>
                              <tr>
                                <td class="text-left"><strong><?php print $descripcionimpuestoadicional; ?></strong></td>
                                <td id="impuestoadicional" class="text-right">$<?//php $totalimp = $subtotal * $impuestoadicional / 100; print number_format(@$totalimp,2,",","."); ?></td>                                        
                              </tr>
                            <?php } ?>
                            <tr>
                              <td class="text-left"><strong>Total</strong></td>
                              <td id="mtotal" class="text-right mtotal"><strong>$ <?php // $total = $subtotal + $montoiva; print number_format(@$total,2,",","."); ?></strong></td>                                        
                            </tr> 
                            <?php 
                              }else{ ?>

                            <tr>
                              <td class="text-left"><strong>Subtotal</strong></td>
                              <td id="msubtotal" class="text-right msubtotal">$</td>
                            <tr>
                            <?php if($descpro == 0) { ?>
                            <tr>
                              <td class="text-left"><strong>Descuento (%)</strong></td>
                              <td id="" class="text-right">
                                <input type="text" class="text-right descuentop100" name="" id="descuentop100" value="" style="width:70px;" >
                              </td>                                        
                            </tr>

                            <tr>
                              <td class="text-left"><strong>Descuento (Valor)</strong></td>
                              <td id="" class="text-right">
                                <input type="text" class="text-right descuento" name="" id="descuento" value="<?php // print number_format(@$desc,2,",","."); ?>" style="width:70px;" >
                              </td>                                        
                            </tr>                              
                            <?php } ?>                           
                            <tr>
                              <td class="text-left"><strong>Total</strong></td>
                              <td id="mtotaln" class="text-right mtotaln"><strong>$ <?php // $total = $subtotal + $montoiva; print number_format(@$total,2,",","."); ?></strong></td>                                        
                            </tr>  

                            <?php 
                              }
                            ?>     
                          </tbody>
                        </table>
                      </div>                
                    </div>

                  </div>

                  <div class="box-footer ">
                    <div class="row no-print ">
                    <div class="form-group col-md-6 text-left">

                      <div id="anticipo">
                        <label class="col-sm-2" style="padding-right: 0px; padding-left: 0px;">Anticipo: </label>
                        <div class="col-sm-4" style="padding-left: 0px;">
                          <input type="text" class="form-control" name="txt_anticipo" id="txt_anticipo" readonly="true" value="<?php if(@$anticipo != NULL) { print number_format(@$anticipo,2);} else { print '0'; } ?>" style="width:70px;">
                        </div>                      
                      </div>

                      <label class="col-sm-2" style="padding-right: 0px; padding-left: 0px;">Cambio: </label>
                      <div class="col-sm-2" style="padding-left: 0px;">
                        <input type="text" class="form-control" name="txt_cambio" id="txt_cambio" readonly="true">
                      </div>

                      <div class="pull-right">
                        <button id="1" type="button" class="btn btn-sm btn-primary btn-grad pago_rapido" name="0" >
                        <i class="fa fa-dollar"></i> Efectivo
                        </button>   
                        <button id="2" type="button" class="btn btn-sm btn-primary btn-grad pago_rapido" name="0" >
                        <i class="fa fa-dollar"></i> Cheque
                        </button>   
                        <button id="3" type="button" class="btn btn-sm btn-primary btn-grad pago_rapido" name="0" >
                        <i class="fa fa-dollar"></i> Tarjeta
                        </button>   
                      </div>  

                    </div>                      
                    <div class="col-md-6 text-right">

                      <button id="imprimir" type="button" class="btn btn-sm btn-primary btn-grad" name="0" >
                        <i class="fa fa-print"></i> Imprimir
                      </button>   
                               
                      <button id="pagar" type="button" class="btn btn-sm btn-success btn-grad" >
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
    <strong>Copyright &copy; 2019 <a href="#"><?php print @$nombresistema; ?></a>.</strong>
  </footer>

</body>
</html>