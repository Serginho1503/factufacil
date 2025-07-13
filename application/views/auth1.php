<?php
/* ------------------------------------------------
  ARCHIVO: auth.php
  DESCRIPCION: Contiene la vista del formulario de identificación de usuario para ingresar al sistema. Todos los documentos de estilo y .js de
 * esta vista se cargaran dentro de ella misma.
  FECHA DE CREACIÓN: 27/06/2017
 * 
  ------------------------------------------------ */

  $sistema = &get_instance();
  $sistema->load->model("Sistema_model");
  $imagenfondo = $sistema->Sistema_model->sel_imagenfondo();
  $nombresistema = $sistema->Sistema_model->sel_nombresistema();
  $iconosistema = $sistema->Sistema_model->sel_iconosistema();  
  

  $this->session->unset_userdata("nombresistema"); 
  $this->session->set_userdata("nombresistema", $nombresistema);

?>

<script type="text/javascript">
    <?php // Declarar variable global base_url para que esté disponible en los documentos .js    ?>
    var base_url = '<?php print base_url(); ?>';
</script>
<!doctype html>
<html>
    <head>
        <title>FACTUFÁCIL - ACCESO AL SISTEMA</title>
        <link rel="shortcut icon" type="image/ico" href="<?php print $iconosistema; ?>" />
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
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/ionicons/css/ionicons.min.css">
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
        <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/css/animate.css">
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
        <script src="<?php print $base_url; ?>assets/plugins/jQueryUI/jquery-ui.min.js"></script>

        <!-- DataTables -->
<!--        <script src="<?php print $base_url; ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php print $base_url; ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>

        <script src='<?php print $base_url; ?>public/js/jquery.dataTables.delay.min.js'></script>
        <?php //  Para cargar el ReloadAjax y actualizar los datos del datable en línea  ?>
        <script src='<?php print $base_url; ?>public/js/fnReloadAjax.js'></script>-->
        <?php // <!-- Fin de cargar el ReloadAjax y actualizar los datos del datable en línea --> ?>


        


        <?php // <!-- INCLUDES PARA ENCRIPTACION DE FORMULARIO DE AUTENTICACION --> ?>
        <script language='javascript' src='<?php print $base_url; ?>public/js/sha1/core-min.js'></script>
        <script language='javascript' src='<?php print $base_url; ?>public/js/sha1/sha1.js'></script>
        <?php // <!-- FIN DE INCLUDES PARA ENCRIPTACION DE FORMULARIO DE AUTENTICACION --> ?>

        <?php // <!-- INCLUDES PARA NOTIFICACIONES CON ALERTIFY --> ?>
        <script src="<?php print $base_url; ?>public/js/alertify/alertify.min.js"></script>
        <?php // <!-- FIN DE INCLUDES PARA NOTIFICACIONES --> ?>

        <?php // <!-- GLOBALES DEFINIDAS POR EL DESARROLLADOR --> ?>
<!--        <script src="<?php print $base_url; ?>public/js/common.js"></script>-->
        <script src="<?php print $base_url; ?>public/js/login/login.js"></script>
        <?php // <!-- FIN DE GLOBALES DEFINIDAS POR EL DESARROLLADOR --> */  ?>
        
        <?php // <!-- INCLUDES PARA VALIDACION DE FORMULARIOS --> ?>
        <script src='<?php print $base_url; ?>assets/plugins/validationengine/js/jquery.validationEngine.js'></script>
        <script src='<?php print $base_url; ?>assets/plugins/validationengine/js/languages/jquery.validationEngine-es.js'></script>
        <script src='<?php print $base_url; ?>assets/plugins/jquery-validation-1.11.1/dist/jquery.validate.min.js'></script>
        <script src='<?php print $base_url; ?>assets/plugins/js/validationInit.js'></script>
        <?php // <!-- FIN DE INCLUDES PARA VALIDACION DE FORMULARIOS --> ?>
        
        <?php /*
         * 
         *          FIN DE REFERENCIAS A ARCHIVOS .JS
         */ ?>
    </head>
    <!-- base_url(); ?>public/img/login/restaurant1.jpg -->
	<body>
    
        <script>
            $(document).on('click', '#dat_usu_rec', function () {
                location.href= base_url + "auth/recovery";
            });
        </script>
        <div height="100px" class="animated">
           
        </div>
        <!-- <h1 class="animated bounceIn"> &nbsp; SISTEMA DE FACTURACIÓN </h1> -->
        <!--<h1>SISTEMA DE GESTIÓN DEL PLAN OPERATIVO ANUAL</h1>-->
        <div class="content-w3ls animated pulse" style="margin-top: 5%;margin-bottom: 0%">
            <h3 class="animated bounceIn" style="color: #fff; padding-bottom: 20px; font-weight: 700;   font-size: 22px;">
             &nbsp; SISTEMA CONTABLE FACTURACIÓN E INVENTARIO</h3>
                         <a title="FACTUFÁCIL">
      <img src="public/img/logo_ff_ico.png"></a><br>
            <form name="popup-validation" id="popup-validation" onsubmit="return false">
                    <noscript>
                        <div class="callout callout-danger">
                            <h4>Es necesario habilitar Javascript en su navegador para el funcionamiento correcto del Sistema. Para ver las instrucciones de cómo habilitar javascript en su navegador, haga click <a href="http://www.enable-javascript.com/es/" target="_blank">aquí</a></h4>
                        </div>
                    </noscript>
                    
                <?php // Campo de nombre de usuario ?>
                <div class="form-group has-feedback">
                    <input type="text" name="txt_usua" id="txt_usua" class="validate[required] form-control" placeholder="Usuario" autofocus>
                    <!-- <input type="text" name="txt_usua" id="txt_usua" class="validate[required] form-control" placeholder="Usuario"> -->
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>

                <?php // Campo de clave de usuario ?>
                <div class="form-group has-feedback">
                    <input type="password" name="txt_pass" id="txt_pass" class="validate[required] form-control" placeholder="Clave">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>


                <div class="row">
                    <?php // Botón de ir a recuperar datos de usuario ?>
                <!--    <div class="col-xs-8">
                        <a href="<?php // print base_url()."auth/recovery";?>" class="btn btn-danger btn-block btn-flat" title="Recuperar clave o usuario para acceder"><i class="fa fa-unlock-alt"></i>&nbsp;Recuperar clave</a>
                    </div> -->
                    <?php // Botón de inicio de sesión ?>
                    <div class="col-xs-4 text-center">
                        <button type="submit"  id="btn_ingreso" name="btn_ingreso" class="btn btn-primary btn-block btn-flat" title="Acceder al Sistema"><span class="glyphicon glyphicon-log-in"></span>&nbsp;Acceder</button>
                    </div>
                    <!--<div class="col-md-12">
                        <span class=" pull-right"><strong> TI-PRO </strong></span>
                    </div>-->
                </div>
            </form>
        </div>
        <p class="copyright">© <?php print @$nombresistema; ?> 2019. Todos los derechos reservados | Sistema desarrollado por <a href="https://www.factufacil-ec.com" target="_blank"><?php print @$nombresistema; ?></a></p>
    </body>
</html>