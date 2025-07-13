<?php

  $sistema = &get_instance();
  $sistema->load->model("Sistema_model");
  $nombresistema = $sistema->Sistema_model->sel_nombresistema();  
  $iconosistema = $sistema->Sistema_model->sel_iconosistema();  

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php print @$nombresistema; ?> - Sistema de Facturación</title>
  <!-- Tell the browser to be responsive to screen width -->
  <link rel="shortcut icon" type="image/ico" href="<?php print $iconosistema; ?>" />
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
  <?php // <!-- ESTILOS DE ALERTIFY --> ?>
  <link rel="stylesheet" href="<?php print $base_url; ?>public/css/alertify/alertify.min.css" />
  <link rel="stylesheet" href="<?php print $base_url; ?>public/css/alertify/default.min.css" />
  <?php // <!-- FIN DE ESTILOS DE ALERTIFY --> ?>
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

<!--  ====== FIN DE CARGA DE LOS ESTILOS CSS ================================================================================= -->

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
  <!-- <script src='<?php // print $base_url; ?>public/js/dtextra/jquery.dataTables.delay.min.js'></script> -->

  <script src="<?php print $base_url; ?>assets/plugins/js/jquery.maskedinput.js"></script>

  <script src="<?php print $base_url; ?>assets/plugins/moment/moment.js"></script>

  <!-- charts -->   
  <script src="<?php print $base_url; ?>assets/plugins/chart.js-2.7.3/package/dist/Chart.js"></script> 


<?php // <!-- GLOBALES DEFINIDAS POR EL DESARROLLADOR --> ?>
  <script src="<?php print $base_url; ?>public/js/common.js"></script>
  <script src="<?php print $base_url; ?>public/js/blockui/jquery.blockUI.js"></script>  
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

<!-- JSTREE -->
<link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/jstree/dist/themes/default/style.min.css" />
<script type="text/javascript" src="<?php print $base_url; ?>assets/plugins/jstree/dist/jstree.min.js"></script>

<!-- contextmenu -->
<script type="text/javascript" src="<?php print $base_url; ?>assets/plugins/contextMenu/dist/jquery.contextMenu.min.js"></script>


<?php // <!-- INCLUDES PARA VALIDACION DE FORMULARIOS --> ?>
  <script src='<?php print $base_url; ?>assets/plugins/validationengine/js/jquery.validationEngine.js'></script>
  <script src='<?php print $base_url; ?>assets/plugins/validationengine/js/languages/jquery.validationEngine-es.js'></script>
  <script src='<?php print $base_url; ?>assets/plugins/jquery-validation-1.11.1/dist/jquery.validate.min.js'></script>
  <script src='<?php print $base_url; ?>assets/plugins/js/validationInit.js'></script>
<?php // <!-- FIN DE INCLUDES PARA VALIDACION DE FORMULARIOS --> ?>

<?php // <!-- INCLUDES PARA NOTIFICACIONES CON ALERTIFY --> ?>
  <script src="<?php print $base_url; ?>public/js/alertify/alertify.min.js"></script>
<?php // <!-- FIN DE INCLUDES PARA NOTIFICACIONES --> ?>  



<?php // <!-- REMODAL --> ?>
  <script src="<?php print $base_url; ?>public/js/remodal/remodal.js"></script>
<?php // <!-- REMODAL --> ?>  

 <!-- LIBRERIA DE VUEJS - CARLOS ZAMBRANO 22 DE 11 - 2018 -->
<!--      <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue-resource.min.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue-router.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/sweetalert.min.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/axios.min.js"></script>

 --> 
</head>
