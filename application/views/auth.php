<?php
/* ------------------------------------------------
  ARCHIVO: log.php
  Aquí se encuentra el formulario de login para el ingreso al sistema.
  FECHA DE CREACIÓN: 13/10/2023 
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

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>FACTUFÁCIL - ACCESO AL SISTEMA</title>
        <link rel="shortcut icon" type="image/ico" href="<?php print $iconosistema; ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

   <link rel="stylesheet" href="public/css/login/bootstrap.css">
   <link rel="stylesheet" type="text/css" href="public/css/login/style.css">
   <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
   <!-- <link rel="stylesheet" href="css/all.min.css"> -->
   <!-- <link rel="stylesheet" href="css/fontawesome.min.css"> -->
   <link href="https://tresplazas.com/web/img/big_punto_de_venta.png" rel="shortcut icon">
   <title>Inicio de sesión</title>
   
    <?php /*
         * 
         *          INICIO DE REFERENCIAS A ESTILOS
         */ ?>
        

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

<body>
   <img class="wave" src="public/img/login/wave.png">
   <div class="container">
      <div class="img">
         <img src="public/img/login/back.svg">
      </div>
      <div class="login-content">
         <form method="post" action="">
             <h3 class="animated bounceIn" style="color: #000000; padding-bottom: 20px; font-weight: 700;   font-size: 30px;">
             &nbsp; SISTEMA CONTABLE FACTURACIÓN E INVENTARIO</h3>
            <img src="public/img/login/avatar.svg">
            <h2 class="title">BIENVENIDO</h2>
            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-user"></i>
               </div>
               <div class="div">
                  <h5>Usuario</h5>
                  <input type="text" name="txt_usua" id="txt_usua" class="input" autofocus>
               </div>
            </div>
            <div class="input-div pass">
               <div class="i">
                  <i class="fas fa-lock"></i>
               </div>
               <div class="div">
                  <h5>Contraseña</h5>
                  <input type="password" name="txt_pass" id="txt_pass" class="input">
               </div>
            </div>
            <div class="view">
               <div class="fas fa-eye verPassword" onclick="vista()" id="verPassword"></div>
            </div>

            <div class="text-center">
               <a class="font-italic isai5" href="">Olvidé mi contraseña</a>
               <a class="font-italic isai5" href="">Registrarse</a>
            </div>
            
            <button type="submit"  id="btn_ingreso" name="btn_ingreso" class="btn btn-primary btn-block btn-flat" title="Acceder al Sistema"><span class="glyphicon glyphicon-log-in"></span>&nbsp;INICIAR SESIÓN</button>
         </form>
      </div>
   </div>
   <script src="public/js/login/fontawesome.js"></script>
   <script src="public/js/login/main.js"></script>
   <script src="public/js/login/main2.js"></script>
   <script src="public/js/login/jquery.min.js"></script>
   <script src="public/js/login/bootstrap.js"></script>
   <script src="public/js/login/bootstrap.bundle.js"></script>
</body>

</html>