<script type="text/javascript">
<?php // Declarar variable global base_url para que esté disponible en los documentos .js     ?>
    var base_url = '<?php print base_url(); ?>';
</script>
<!DOCTYPE html>
<html>
    <head>
        <?php /* INCLUIR .CSS */ ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Sistema de Facturación</title>
        <link rel="shortcut icon" type="image/ico" href="<?php print $base_url; ?>public/img/log_ff_mod_web.png" />
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/dist/css/skins/_all-skins.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/iCheck/flat/blue.css">
        <!-- Morris chart -->
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/morris/morris.css">
        <!-- jvectormap -->
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
        <!-- Date Picker -->
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/datepicker/datepicker3.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/daterangepicker/daterangepicker.css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
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

        <?php // <!-- INCLUDES PARA FILEUPLOAD --> ?>
        <link rel="stylesheet" href="<?php print $base_url; ?>public/css/fileupload/bootstrap-fileupload.min.css" />
        <?php // <!-- FIN DE INCLUDES PARA FILEUPLOAD --> ?>
        <?php /* FIN DE INCLUIR .CSS */ ?>



        <?php /* INCLUIR .JS */ ?>
        <!-- jQuery 2.2.3 -->
        <script src="<?php print $base_url; ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="<?php print $base_url; ?>assets/plugins/jQueryUI/jquery-ui.min.js"></script>
        <script>
        $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.6 -->
        <script src="<?php print $base_url; ?>assets/bootstrap/js/bootstrap.min.js"></script>
        <!-- Morris.js charts -->
     <!--    <script src="<?php print $base_url; ?>assets/js/raphael-min.js"></script>
       <script src="<?php print $base_url; ?>assets/plugins/morris/morris.min.js"></script> COMENTADO POR DAR ERROR-->
        <!-- Sparkline -->
        <script src="<?php print $base_url; ?>assets/plugins/sparkline/jquery.sparkline.min.js"></script>
        <!-- jvectormap -->
        <script src="<?php print $base_url; ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="<?php print $base_url; ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <!-- jQuery Knob Chart -->
        <script src="<?php print $base_url; ?>assets/plugins/knob/jquery.knob.js"></script>
        <!-- daterangepicker -->
        <script src="<?php print $base_url; ?>assets/plugins/js/moment.min.js"></script>
        <script src="<?php print $base_url; ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- datepicker -->
        <script src="<?php print $base_url; ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="<?php print $base_url; ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <!-- Slimscroll -->
        <script src="<?php print $base_url; ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="<?php print $base_url; ?>assets/plugins/fastclick/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php print $base_url; ?>assets/dist/js/app.min.js"></script>
        <!-- DataTables -->
        <script src="<?php print $base_url; ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php print $base_url; ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
        
        <script src='<?php print $base_url; ?>public/js/dtextra/jquery.dataTables.delay.min.js'></script>
        <?php // <!-- Para cargar el ReloadAjax y actualizar los datos del datable en línea --> ?>
        <script src='<?php print $base_url; ?>public/js/dtextra/fnReloadAjax.js'></script>
        <?php // <!-- Fin de cargar el ReloadAjax y actualizar los datos del datable en línea --> ?>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!--    <script src="<?php print $base_url; ?>assets/dist/js/pages/dashboard.js"></script>-->
        <!-- AdminLTE for demo purposes -->
        <script src="<?php print $base_url; ?>assets/dist/js/demo.js"></script>

        <?php // <!-- INCLUDES PARA VALIDACION DE FORMULARIOS --> ?>
        <script src='<?php print $base_url; ?>assets/plugins/validationengine/js/jquery.validationEngine.js'></script>
        <script src='<?php print $base_url; ?>assets/plugins/validationengine/js/languages/jquery.validationEngine-es.js'></script>
        <script src='<?php print $base_url; ?>assets/plugins/jquery-validation-1.11.1/dist/jquery.validate.min.js'></script>
        <script src='<?php print $base_url; ?>assets/plugins/js/validationInit.js'></script>
        <?php // <!-- FIN DE INCLUDES PARA VALIDACION DE FORMULARIOS --> ?>

        <?php // <!-- INCLUDES PARA NOTIFICACIONES CON ALERTIFY --> ?>
        <script src="<?php print $base_url; ?>public/js/alertify/alertify.min.js"></script>
        <?php // <!-- FIN DE INCLUDES PARA NOTIFICACIONES --> ?>
        
        <?php // <!-- GLOBALES DEFINIDAS POR EL DESARROLLADOR --> ?>
        <script src="<?php print $base_url; ?>public/js/common.js"></script>
        <script src="<?php print $base_url; ?>public/js/login/login.js"></script>
        <?php // <!-- FIN DE GLOBALES DEFINIDAS POR EL DESARROLLADOR --> */  ?>

        <?php // <!-- FORMWIZARD --> ?>
        <script type="text/javascript" src="<?php print $base_url; ?>public/js/formwizard/formToWizard.js"></script>
        <?php // <!-- FIN DE FORMWIZARD --> ?>

        <?php // <!-- INPUTMASK --> ?>
        <script src="<?php print $base_url; ?>assets/plugins/input-mask/jquery.inputmask.js"></script>
        <script src="<?php print $base_url; ?>assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
        <script src="<?php print $base_url; ?>assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
        <?php // <!-- FIN DE INPUTMASK --> ?>
        
        <?php // <!-- REMODAL --> ?>
        <script src="<?php print $base_url; ?>public/js/remodal/remodal.js"></script>
        <?php // <!-- REMODAL --> ?>

        <?php // <!-- Backstretch --> ?>
        <script src="<?php print $base_url; ?>public/js/backstretch/jquery.backstretch.js"></script>
        <?php // <!-- Backstretch --> ?>

        <?php // <!-- INCLUDES PARA FILEUPLOAD --> ?>
        <script src="<?php print $base_url; ?>public/js/fileupload/bootstrap-fileupload.js"></script>
        <?php // <!-- FIN DE INCLUDES PARA FILEUPLOAD --> ?>
        
        <?php // <!-- INCLUDES PARA REDIRECT --> ?>
        <script src="<?php print $base_url; ?>public/js/redirect/jquery.redirect.js"></script>
        <?php // <!-- FIN DE INCLUDES PARA REDIRECT --> ?>

<!--        <noscript>
            <div class="callout callout-danger">
                <h4>Es necesario habilitar Javascript en su navegador para el funcionamiento correcto del Sistema. Para ver las instrucciones de cómo habilitar javascript en su navegador, haga click <a href="http://www.enable-javascript.com/es/" target="_blank">aquí</a></h4>
                 Inicio enlace Tutores.org 
                <a title="JavaScript desactivado. Volver a pantalla de acceso al sistema." href="<?php print $base_url ?>auth/logout" >JavaScript desactivado. Volver a pantalla de acceso al sistema.</a>
                header ("Location: http://www.cristalab.com");
                 Final enlace Tutores.org 
            </div>
            <?php // print '</noscript>'; die; ?>
        </noscript>-->
        
        
    </head>