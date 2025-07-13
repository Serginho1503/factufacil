<?php
/* ------------------------------------------------
  ARCHIVO: auth_recovery.php
  DESCRIPCION: Contiene la vista del formulario de recuperación de los datos del usuario para ingresar al sistema. Todos los documentos de estilo y .js de
 * esta vista se cargaran dentro de ella misma.
  FECHA DE CREACIÓN: 27/06/2017
 * 
  ------------------------------------------------ */
?>
<script type="text/javascript">
    <?php // Declarar variable global base_url para que esté disponible en los documentos .js    ?>
    var base_url = '<?php print base_url(); ?>';
</script>

<!doctype html>
<html>
    <head>
        <title>FACTUFÁCIL - RECUPERAR DATOS DE ACCESO</title>
        <link rel="shortcut icon" type="image/ico" href="<?php print $base_url; ?>public/img/log_ff_mod_web.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <?php /*
         * 
         *          INICIO DE REFERENCIAS A ESTILOS
         */ ?>
        <?php //  <!-- Bootstrap 3.3.6 --> ?>
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/bootstrap/css/bootstrap.min.css">
        <?php //  <!-- Font Awesome --> ?>
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/css/font-awesome.min.css">
        <?php //  <!-- Ionicons --> ?>
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/css/ionicons.min.css">
        <?php //  <!-- Theme style --> ?>
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/dist/css/AdminLTE.min.css">
        <?php //  <!-- iCheck --> ?>
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/iCheck/square/blue.css">
        <!-- font files -->
        <link rel="stylesheet" type='text/css' media="all" href="<?php print $base_url; ?>public/css/login/font_Noto.css" />
        <link rel="stylesheet" type='text/css' media="all" href="<?php print $base_url; ?>public/css/login/font_Nunito.css" />
        <!-- /font files -->
        <!-- css files -->
        <link rel="stylesheet" type='text/css' media="all" href="<?php print $base_url; ?>public/css/login/login.css" />
        <!-- /css files -->
        <?php // <!-- ESTILOS PERSONALIZADOS DEL DESARROLLADOR --> ?>
        <link rel="stylesheet" href="<?php print $base_url; ?>public/css/estilo.css" />
        <?php // <!-- FIN DE ESTILOS PERSONALIZADOS DEL DESARROLLADOR --> ?>
        <?php // <!-- ESTILOS DE VALIDACION --> ?>
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/validationengine/css/validationEngine.jquery.css" />
        <?php // <!-- FIN DE ESTILOS DE VALIDACION --> ?>
        <?php // <!-- ESTILOS DE ALERTIFY --> ?>
        <link rel="stylesheet" href="<?php print $base_url; ?>public/css/alertify/alertify.min.css" />
        <link rel="stylesheet" href="<?php print $base_url; ?>public/css/alertify/default.min.css" />
        <?php // <!-- FIN DE ESTILOS DE ALERTIFY --> ?>
		
		<?php // <!-- ESTILOS DE CSS ANIMATIONS --> ?>
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/css/animate.css">
        <?php /*
         * 
         *          FIN DE REFERENCIAS A ESTILOS
         */ ?>

        <?php /*
         * 
         *          INICIO DE REFERENCIAS A ARCHIVOS .JS
         */ ?>
        <!-- jQuery 2.2.3 -->
        <script src="<?php print $base_url; ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="<?php print $base_url; ?>assets/js/jquery-ui.min.js"></script>

        <!-- DataTables -->
        <script src="<?php print $base_url; ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php print $base_url; ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>

        <script src='<?php print $base_url; ?>public/js/jquery.dataTables.delay.min.js'></script>
        <?php // <!-- Para cargar el ReloadAjax y actualizar los datos del datable en línea --> ?>
        <script src='<?php print $base_url; ?>public/js/fnReloadAjax.js'></script>
        <?php // <!-- Fin de cargar el ReloadAjax y actualizar los datos del datable en línea --> ?>


        <?php // <!-- INCLUDES PARA VALIDACION DE FORMULARIOS --> ?>
        <script src='<?php print $base_url; ?>assets/plugins/validationengine/js/jquery.validationEngine.js'></script>
        <script src='<?php print $base_url; ?>assets/plugins/validationengine/js/languages/jquery.validationEngine-es.js'></script>
        <script src='<?php print $base_url; ?>assets/plugins/jquery-validation-1.11.1/dist/jquery.validate.min.js'></script>
        <script src='<?php print $base_url; ?>assets/js/validationInit.js'></script>
        <?php // <!-- FIN DE INCLUDES PARA VALIDACION DE FORMULARIOS --> ?>


        <?php // <!-- INCLUDES PARA ENCRIPTACION DE FORMULARIO DE AUTENTICACION --> ?>
        <script language='javascript' src='<?php print $base_url; ?>public/js/sha1/core-min.js'></script>
        <script language='javascript' src='<?php print $base_url; ?>public/js/sha1/sha1.js'></script>
        <?php // <!-- FIN DE INCLUDES PARA ENCRIPTACION DE FORMULARIO DE AUTENTICACION --> ?>

        <?php // <!-- INCLUDES PARA NOTIFICACIONES CON ALERTIFY --> ?>
        <script src="<?php print $base_url; ?>public/js/alertify.min.js"></script>
        <?php // <!-- FIN DE INCLUDES PARA NOTIFICACIONES --> ?>

        <?php // <!-- GLOBALES DEFINIDAS POR EL DESARROLLADOR --> ?>
        <script src="<?php print $base_url; ?>public/js/common.js"></script>
        <script src="<?php print $base_url; ?>public/js/login.js"></script>
        <?php // <!-- FIN DE GLOBALES DEFINIDAS POR EL DESARROLLADOR --> */  ?>

        <?php /*
         * 
         *          FIN DE REFERENCIAS A ARCHIVOS .JS
         */ ?>
    </head>
    <body>

        <script>
            $(document).on('click', '#ini_ses', function () {
                location.href= base_url + "auth/";
            });
        </script>
        <div class="animated">
            <img src='<?php print base_url(); ?>public/img/log_eq_mod_web.png' height="100px" width="250px" />
        </div>
        <h1 class="animated bounceIn"> SISTEMA DE FACTURACIÓN </h1>

        <div class="content-w3ls animated pulse">
            <form name="popup-validation" id="popup-validation" onsubmit="return false" >
                <noscript>
                    <div class="callout callout-danger">
                        <h4>Es necesario habilitar Javascript en su navegador para el funcionamiento correcto del Sistema.</h4>
                    </div>
                </noscript>
                <?php // Campo de nombre de usuario ?>
                <div class="form-group has-feedback">
                    <input type="text" id="logi" name="logi" class="form-control" placeholder="Usuario">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>

                <?php // Campo de correo de usuario ?>
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Correo">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>

                <div class="row">
                    <?php // Botón para volver a formulario de iniciom de sesión ?>
                    <div class="col-xs-8">
                        <button type="submit" id="ini_ses" class="btn btn-warning btn-block btn-flat" title="Ir al formulario para acceder al sistema"><span class="glyphicon glyphicon-log-in"></span>&nbsp;Ir a Ingreso</button>
                    </div>
                    <?php // Botón para recuperar datos ?>
                    <div class="col-xs-4">
                        <button type="submit" id="rec_dat" name="rec_dat" class="btn btn-primary btn-block btn-flat" title="Enviar datos para recuperar la clave o el usuario"><i class="fa fa-unlock-alt"></i>&nbsp;Enviar</button>
                    </div>
                </div>
            </form>
        </div>
        <p class="copyright">© FACTUFÁCIL 2019. Todos los derechos reservados | Sistema desarrollado por <a href="#" target="_blank">TI-PRO</a></p>
    </body>
</html>