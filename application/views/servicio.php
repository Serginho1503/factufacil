<?php 
$nombresistema = $this->session->userdata("nombresistema");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php print $nombresistema; ?> - Distribución de las Mesas</title>
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
  </style>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {
      /* REMITIR A PEDIDO */
      $(document).on('click', '.pedir', function(){
        id = $(this).attr('id');
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php print $base_url;?>Servicio/tmp_pedido",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) == 1) {
                 location.replace("<?php print $base_url;?>Servicio/pedido");
              } else {
                 alert("Error de conexión");
              }
           }
        });
      });

      /* REMITIR A FACTURAR 
      $(document).on('click', '.facturar', function(){
        id = $(this).attr('id');
      //  alert(id);
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php print $base_url;?>Facturar/fact_mesero",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) > 0) {
                 location.replace("<?php print $base_url;?>Facturar/pedido_factura");
              } else {
                 alert("La Mesa no tiene Mesero Asignado");
              }
           }
        });
        
      });*/

      /* CAMBIO DE MESA */
      $(document).on('click', '.cambio', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('Servicio/cargarcambiomesa');?>", 
          success: function(json) {
              if (parseInt(json.resu) > 0) {
                 location.replace("<?php print $base_url;?>servicio");
              } else {
                 alert("Error de conexión");
              }
          }    
        });
      });




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
  <section class="content">
    <div class="row">
      <div class="col-md-12">

              <div class="box box-danger">

                <div class="box-header with-border">
                  <div class="pull-right">

                    <button class="btn bg-orange color-palette btn-grad cambio" type="button">
                      <i class="fa fa-retweet" aria-hidden="true"></i> Cambio de Mesa
                    </button>
                            
                  </div>  

                </div>
              </div>

          <!-- INICIO DE ESPACIO DE LAS AREAS <i class="fa fa-retweet" aria-hidden="true"></i>-->
          <?php 
            if (count($area) > 0) {
              foreach ($area as $ar):
          ?>
              <div class="box box-danger">

                <div class="box-header with-border">
                  <h3 class="box-title"></i> <?php print $ar->nom_area; ?></h3>
                  <div class="pull-right"> 
                    <?php print $ar->id_area; ?>
                  </div>
                </div>
                <div class="box-body" style="padding: 10px 13px 0;">
                  <div class="row">
                    <!-- INICIO DE ESPACIO DE LAS MESAS -->
                  <?php 
                    if (count($mesa) > 0) {
                      foreach ($mesa as $me):
                        if($me->id_area == $ar->id_area){  
                          if($me->cliente > 0 || $me->pedido > 0){ $estmesa = 'bg-red'; $fact = 1; }else{$estmesa = 'bg-green'; $fact = 0; }
                  ?>                    



                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <div style="" class="info-box <?php print $estmesa; ?>">
                        <a id="<?php print $me->id_mesa; ?>" href="#" title="Tomar Orden" style="color: #ffffff;" class="pedir">
                          <div class="tomar">
                            <span class="text-center" style="font-size: 45px;"><i class="fa fa-cutlery"></i></span><br>
                            <span class="text-center" style="font-size: 12px;">Tomar Orden</span>
                          </div>
                        </a>

                        <div class="info-box-content">
                          <span class="info-box-number">Mesa: <?php print $me->nom_mesa; ?></span>
                          <span class="info-box-text">
                            <?php 
                            foreach ($elmese as $ms) {
                              if($ms->id_mesa == $me->id_mesa){
                                $mesero = $ms->nom_mesero;
                                print $mesero;
                              }
                            }
                            ?>
                          </span>

                          <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                          </div>
                              
                              <span class="progress-description text-right">
                                
                                 
                               
                              </span>    
                              

                        </div>
                       
                      </div>
                   
                    </div>




                  <?php 
                        }
                      endforeach;
                    }
                  ?>                      
                    <!-- FIN DE ESPACIO DE LAS MESAS -->
                  </div>
                </div>
<!--                 <div  align="center" class="box-footer">
                </div> -->

              </div>
          <?php 
              endforeach;
            }
          ?>
          <!-- FIN DE ESPACIO DE LAS AREAS -->

      </div>
    </div>
  </section>

   <footer class="main-footer pie" style ="margin-left: 0px;">
    <div class="pull-right hidden-xs">
      <b>Version</b> 3.0
    </div>
    <strong>Copyright &copy; 2019 <a href="https://adminlte.io"><?php print $nombresistema; ?></a>.</strong> All rights
    reserved.
  </footer>
</body>
</html>