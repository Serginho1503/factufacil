<?php
/* ------------------------------------------------
  ARCHIVO: main_header.php
  DESCRIPCION: Contiene la vista del menú superior de la aplicación y la opción de salir del Sistema.
  FECHA DE CREACIÓN: 17/12/2016
 * 
  ------------------------------------------------ */
?>

<!--<body class="hold-transition skin-blue sidebar-mini">-->
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="<?php print base_url(); ?>inicio" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><img src="<?php print base_url(); ?>public/img/log_eq_mod_web.png" height ="45px" width="110px" title="Sistema de Gestion del Plan Operativo Anual" /></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><img src="<?php print base_url(); ?>public/img/log_eq_mod_web.png" height ="45px" width="110px" title="Sistema de Gestion del Plan Operativo Anual" />&nbsp;<span style="font-size: 12px; font-weight: bold;"></span></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Navegación compacta</span>
                </a>
                <span class="navbar-text" style="font-size: 14px; font-weight: bold; color: white;">SISTEMA DE FACTURACIÓN</span>
<!--                <p class="navbar-nav">Sistema de Gestión </p>-->
                <!--  <img src="<?php print base_url(); ?>public/img/logo_01.png" height ="50px" width="50px" title="Sistema de Gestion del Plan Operativo Anual" /> -->
<!--                <span class="logo-lg">&nbsp;<span style="font-size: 12px; font-weight: bold;">Sistema de Gestión</span></span>-->
                <!--&nbsp;&nbsp;&nbsp;<img src="<?php print base_url(); ?>public/img/regins.png" height ="50px" width="500px" title="Registro de Inscripción. Unidad Educativa Santa Lucía del Tuy" align='center' />-->

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php print base_url() . "inicio/mostrar_img"; ?>" class="user-image" alt="Foto de Usuario" width="160px" height="160px" onerror="this.src='<?php print base_url() . "public/img/perfil.jpg"; ?>';"  >
                                <!--<img src="<?php print base_url() . "public/img/perfil.jpg"; ?>" class="user-image" alt="Foto de Usuario" width="160px" height="160px" >-->
                                <span class="hidden-xs"><?php print $this->session->userdata("sess_log"); ?></span>
                            </a>
                            <ul class="dropdown-menu animated bounceInDown">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php print base_url() . "inicio/mostrar_img"; ?>" class="user-image" alt="Foto de Usuario" width="160px" height="160px"  onerror="this.src='<?php print base_url() . "public/img/perfil.jpg"; ?>';" >
                                    <!--<img src="<?php print base_url() . "public/img/perfil.jpg"; ?>" class="user-image" alt="Foto de Usuario" width="160px" height="160px" >-->
                                    <p>
                                        <?php print $this->session->userdata("sess_na"); ?>
                                        <small><?php print $this->session->userdata("sess_tu"); ?></small>
                                    </p>
                                    <br>
                                    <button type="button" class="btn btn-info" title="Ver entidades asociadas al usuario" id="usu_ent_inf" name="usu_ent_inf"><i class="fa fa-industry"></i>&nbsp;Entidades</button>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <button type="button"  class="btn btn-default btn-flat" title="Editar el perfil del usuario" id="usu_per_edit" name="usu_per_edit"><i class="fa fa-user"></i>&nbsp;Perfil</button>
<!--                                        <a href="<?php print base_url() . "usuarios/upd_per"; ?>"></a>-->
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php print $base_url ?>auth/logout" class="btn btn-default btn-flat icon-signout" title="Cerrar sesión"><i class="fa fa-power-off"></i>&nbsp;Salir</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php // Formulario modal para mostrar los datos de las entidades vinculadas al usuario desde el main_header ?>
            <div class="text-center">
                <button type="button" id="act_mod_usu_ent_inf" name="act_mod_usu_ent_inf" class="btn btn-primary" style="display: none;" data-toggle="modal" data-target="#usenin"></button>
            </div>
            <div class="modal inmodal" id="usenin" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog"><div class="modal-content animated bounceInRight" id="form_usu_ent_inf"></div></div>
            </div>
            <?php // FIN DE Formulario modal para mostrar los datos de las entidades vinculadas al usuario desde el main_header ?>
            
            <?php //-- VISTA DE USUARIOS<<<<<<<<<<<<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ?>
            
            <?php // Formulario modal para mostrar los datos de las entidades vinculadas al usuario ?>
            <div class="text-center">
                <button type="button" id="act_mod_usu_ent_inf_dt" name="act_mod_usu_ent_inf_dt" class="btn btn-primary" style="display: none;" data-toggle="modal" data-target="#usenindt"></button>
            </div>
            <div class="modal inmodal" id="usenindt" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog"><div class="modal-content animated bounceInRight" id="form_usu_ent_inf_dt"></div></div>
            </div>
            <?php // FIN DEL Formulario modal para mostrar los datos de las entidades vinculadas al usuario ?>
            <?php // Formulario modal para agregar / modificar un usuario ?>
            <div class="text-center">
                <button type="button" id="act_mod_usu_edit" name="act_mod_usu_edit" class="btn btn-primary" style="display: none;" data-toggle="modal" data-target="#used"></button>
            </div>
            <div class="modal inmodal" id="used" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog"><div class="modal-content animated bounceInLeft" id="form_usu_edit"></div></div>
            </div>
            <?php // FIN DE Formulario modal para agregar / modificar un usuario ?>
            <?php // Formulario modal para modificar perfil de un usuario ?>
            <div class="text-center">
                <button type="button" id="act_mod_usu_per_edit" name="act_mod_usu_per_edit" class="btn btn-primary" style="display: none;" data-toggle="modal" data-target="#uspe"></button>
            </div>
            <div class="modal inmodal" id="uspe" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog"><div class="modal-content animated bounceInLeft" id="form_usu_per_edit"></div></div>
            </div>
            <?php // FIN DE Formulario modal para modificar perfil de un usuario ?>
            <?php // Formulario modal para modificar permisos por entidad de un usuario ?>
            <div class="text-center">
                <button type="button" id="act_mod_usu_per_ent_edit" name="act_mod_usu_per_ent_edit" class="btn btn-primary" style="display: none;" data-toggle="modal" data-target="#uspeen"></button>
            </div>
             <!--style=" height: 650px; width: 650px;"-->
            <div class="modal inmodal modal-usu_per_ent" id="uspeen" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog"><div class="modal-content animated bounceInLeft" id="form_usu_per_ent_edit"></div></div>
            </div>
            <?php // FIN DE Formulario modal para modificar permisos de un usuario ?>
             
            <?php // Formulario modal para cargar acciones recurrentes a una actividad ?>
            <div class="text-center">
                <button type="button" id="act_mod_act_acc_rec" name="act_mod_act_acc_rec" class="btn btn-primary" style="display: none;" data-toggle="modal" data-target="#acacre"></button>
            </div>
             <!--style=" height: 650px; width: 650px;"-->
            <div class="modal inmodal" id="acacre" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog"><div class="modal-content animated bounceInLeft" id="form_act_acc_rec"></div></div>
            </div>
            <?php // FIN DE Formulario modal para modificar permisos de un usuario ?>
             
             <?php // Formulario modal para Agregar / Editar acciones recurrentes ?>
            <div class="text-center">
                <button type="button" id="act_mod_acc_rec_edit" name="act_mod_acc_rec_edit" class="btn btn-primary" style="display: none;" data-toggle="modal" data-target="#acacreedit"></button>
            </div>
             <!--style=" height: 650px; width: 650px;"-->
            <div class="modal inmodal" id="acacreedit" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog"><div class="modal-content animated bounceInLeft" id="form_acc_rec_edit"></div></div>
            </div>
            <?php // FIN DE Formulario modal para modificar permisos de un usuario ?>
             
             
            <script>
                
                // $('#feedback-modal').modal({
//                $('.modal-usu_per_ent').modal({    
//                    // backdrop: true,
//                    // keyboard: true
//                }).css({
//                    width: '650px',
//                    'margin-left': function () {
//                        return -($(this).width() / 2);
//                    }
//                });
                
                $('#usu_per_edit').on('click', function () {
                    /* window.location.href = '#form_edit_usu';
                    $("#cont_mod").load(base_url + 'usuarios/upd_usu'); */
                    
                    $("#form_usu_per_edit").load(base_url + 'usuarios/upd_per', function (response, status, xhr) {
                        if (status == "error") {
                            var msg = "No se cargo correctamente el formulario debido a un error. Error: ";
                            $("#error").html(msg + xhr.status + " " + xhr.statusText);
                        }
                    });

                    $('#act_mod_usu_per_edit').click();
                    
                });
                
                
            </script>
            
            <?php //-- FIN DE VISTA DE USUARIOS ______________________________________________________ ?>
            
            <!--            <div class="remodal" data-remodal-id="form_usu_ent_inf" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
                            <div id="cont_usu_ent_inf">
                            </div>
                        </div>-->