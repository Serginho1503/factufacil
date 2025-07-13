<?php
/* ------------------------------------------------
  ARCHIVO: main_sidebar.php
  DESCRIPCION: Contiene la vista del menú izquierdo de la aplicación con el menú de los accesos a los distintos módulos.
  FECHA DE CREACIÓN: 19/12/2016
 * 
  ------------------------------------------------ */

$tu = trim($this->session->userdata("sess_tu"));
$id_usu = trim($this->session->userdata("sess_id"));

$ci = &get_instance();
$ci->load->model("entidad_model");
?>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php print base_url() . "inicio/mostrar_img"; ?>" class="img-circle" alt="Foto del Usuario" width="160px" height="160px" onerror="this.src='<?php print base_url() . "public/img/perfil.jpg"; ?>';"  >
            </div>
            <div class="pull-left info">
                <p><?php print substr($this->session->userdata("sess_na"), 0, 20); ?></p>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MENÚ</li>
            
            <li class="<?php if ($content == 'inicio') { print 'active';} ?> treeview">
                <a href="<?php print $base_url ?>inicio">
                    <i class="fa fa-home"></i> <span>Inicio</span>
                </a>
            </li>
            
            <li class="<?php if ($content == 'usuarios') {  print 'active';} ?> treeview">
                <a href="<?php print $base_url ?>usuarios">
                    <i class="fa fa-user"></i> <span>Usuarios</span>
                </a>
            </li>
            
            <li class="<?php if ($content == 'actividad') {  print 'active';} ?> treeview">
              <a href="#"><i class="fa fa-calendar-check-o"></i> Plan Operativo Anual
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <?php // CONTENIDO DE POA EN FORMULACIÓN -> CARGAR ENTIDADES 
              $poa_ent = $ci->entidad_model->poa_for_ent_get($id_usu);
                foreach ($poa_ent as $pe): // $pe->ent_nom   $pe->despoa
                    ?>
              <ul class="treeview-menu menu-open" style="<?php if ($content == 'actividad') {  print 'display: block;';}else{ print "display: none;"; } ?>" >
                <li class="<?php if ($content == 'actividad') {  print 'active';} ?> treeview">
                    <a href="#"><i class="fa fa-industry"></i> <?php print substr($pe->ent_nom,1,16)."."; ?>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu menu-open" style="<?php if ($content == 'actividad') {  print 'display: block;';}else{ print "display: none;"; } ?>" >
                    <li><a href="#"><i class="fa fa-calendar"></i> <?php print $pe->despoa; ?>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu menu-open" style="<?php if ($content == 'actividad') {  print 'display: block;';}else{ print "display: none;"; } ?>" >
                            <li><a href="<?php print $base_url ?>actividad/act_ent_po/<?php print $pe->ent_id ; ?>/<?php print $pe->idpoa; ?>/<?php print $pe->id_u; ?>/"><i class="fa fa-edit"></i> Formulación</a></li>
                            <li><a href="#"><i class="fa fa-search-plus"></i> Formulación - Revisión</a></li>
                            <?php 
                            /* MOSTRAR LOS ESTADOS DE LOS POA SÓLO CUANDO YA SE HAYAN CULMINADO LOS ESTATUS ANTERIORES
                            <li><a href="#"><i class="fa fa-circle-o"></i> Formulación - Revisión</a></li> */
                            ?>
                        </ul>
                    </li>
                  </ul>
                </li>
              </ul>
                    <?php
                endforeach;
//                        if (count($ent_dat) > 0) {
//                            if($edi_perfil == 0){
//                                print ": ".trim($ent_dat->ent_nom)."&nbsp;<button type='button' class='btn btn-info btn-circle' onclick='alert(\"Para editar este campo debe hacerlo desde el listado de Usuarios, presionando el botón Asignar múltiples entidades.\")'><i class='fa fa-info' ></i></button>";
//                            }else{
//                                print ": ".trim($ent_dat->ent_nom)."&nbsp;<button type='button' class='btn btn-info btn-circle' onclick='alert(\"Este dato sólo puede ser modificado por un administrador desde el módulo de usuarios.\")'><i class='fa fa-info' ></i></button>";
//                            }
//                        }
              ?>
              <?php // FIN DE CONTENIDO DE POA EN FORMULACIÓN -> CARGAR ENTIDADES ?>
                
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>