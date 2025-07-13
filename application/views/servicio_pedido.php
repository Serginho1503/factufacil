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
  <?php 
    date_default_timezone_set("America/Guayaquil");
    $param = &get_instance();
    $param->load->model("Parametros_model");
  /*  $impuesto = $param->Parametros_model->sel_impuestoespecial();*/
    $impuesto = $param->Parametros_model->sel_impuestoadicional(); 
    $habilitaimpuestoespecial = ($impuesto->valor > 0);
    $impuestoespecial = $impuesto->valor;
    $descripcionimpuestoespecial = $impuesto->descripcion;
    $pedidopromo = $param->Parametros_model->sel_pedidopromo();

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
  </style>

<script type="text/javascript">
  
  $( document ).ready(function() {

    $('#TableProducto').DataTable();

    $('.est_ped').bootstrapToggle();


    $(document).on('click','#mesas', function(){
      location.replace(base_url + 'servicio');
    })

    /* CARGA LOS PRODUCTOS AL PEDIDO */
    $(document).on('click', '.seraddpro', function(){
      id = $(this).attr('id');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Servicio/tmp_serproped",
        data: {id: id},
        success: function(json) {
            $('#detpedido').load(base_url + "Servicio/actualiza_pedido");
            actualiza_precio();
            actualiza_valor();
        }
      });
    });

    $(document).on('click','.serpro_del', function(){
      id = $(this).attr("name");
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Servicio/del_pedido_mesa",
          data: {id: id},
          success: function(json) {
              $('#detpedido').load(base_url + "Servicio/actualiza_datos");
              actualiza_precio();
              actualiza_valor();
          }
        });
        return false;   
    });

    /* CARGA LAS VARIANTES DEL PRODUCTO */
    $(document).on('click','.serpro_var', function(){
      var id = $(this).attr("name");
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('pedido/tmp_pedprovar');?>",
        data: {id: id},
        success: function(json) {
              $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST"
                },
                href: "<?php echo base_url('pedido/ver_pedprovar');?>" 
              });

           }

        });
    });



    /* FUNCIONES PARA EL BOTON AÑADIR NOTA AL PEDIDO */
    $(document).on('click','.seraddnota', function(){
      var idpro = $(this).attr('id');
      var idped = $(this).attr('name');
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        ajax: {
           dataType: "html",
           type: "POST",
           data: {idpro: idpro, idped: idped},
        },
        href: "<?php echo base_url('pedido/nota_pedido');?>"
      });
    });

    /* FUNCIONES PARA EL ESTADO DEL PEDIDO */
    $(document).on('change','.est_ped', function() {
      var idpro = $(this).attr('id');
      var idped = $(this).attr('name');
      var est = "";
      if (this.checked) { est = 1; }
      else { est = 0; }
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('pedido/upd_est');?>",
        data: { idpro: idpro, idped: idped, est: est },
        success: function(json) {

        }
      }); 
    });


    /* CALCULO DE SUBTOTAL */
    $(document).on('keyup','.cantidad', function(){
      id = $(this).attr("id");
      idreg = $(this).attr("name");
      actualizapreciosubtotal (id, idreg);
      return false;
    });

    /* CALCULO DE SUBTOTAL */
    $(document).on('change','.promo', function(){
      id = $(this).attr("id");
      idreg = $(this).attr("name");
      actualizapreciosubtotal(id, idreg);
      return false;
    });

    function actualizapreciosubtotal (id, idreg){
      var cantidad = 0;
      var precio = 0;
      var subtotal = 0;
      cantidad = $('input[name='+idreg+']').val();
      precio = $('td[name='+idreg+']').text();
      preciobase = $('.preciobase[name='+idreg+']').val();
      promo = 0;
      pedidopromo = <?php print $pedidopromo; ?>;
      if (pedidopromo == 1){
        preciobase = $('.preciobase[name='+idreg+']').val();
        precio = preciobase;
        if ($('.promo[name='+idreg+']').is(":checked")) {
          promo = 1;
          precio = 0;
        }
      }  
      
      subval = cantidad * precio;
      subtotal = subval.toFixed(2);
    //  alert(subtotal);
      $('div[name='+idreg+']').html(subtotal);
      $('td[name='+idreg+']').html(precio);

      /* ACTUALIZA PRECIO */
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('pedido/upd_precio');?>",
        data: { id: id, cant: cantidad, precio: precio, promo: promo, idreg: idreg },
        success: function(json) {
          actualiza_valor();          
        }
      });
    }  

    /* CALCULO DE SUBTOTAL */
    $(document).on('keyup','.cantidadOriginal', function(){
      var cantidad = 0;
      var precio = 0;
      var subtotal = 0;
      id = $(this).attr("id");
      idreg = $(this).attr("name");
      cantidad = $('input[name='+idreg+']').val();
      precio = $('td[name='+idreg+']').text();
      subval = cantidad * precio;
      subtotal = subval.toFixed(2);
    //  alert(subtotal);
      $('div[name='+idreg+']').html(subtotal);
      /* ACTUALIZA PRECIO */
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('pedido/upd_precio');?>",
        data: { id: id, cant: cantidad, idreg: idreg },
        success: function(json) {
          var iva = 0.12;
          var montoiva = 0;
          var total = 0;
          var subtotal = 0;
          total = parseFloat(total);           
          subtotal = parseFloat(json.resu);
          iva = parseFloat(iva);
          montoiva = subtotal * iva;
          total = subtotal + montoiva;  
          subtotal = subtotal.toFixed(2);                           
          montoiva = montoiva.toFixed(2);
          total = total.toFixed(2);
          if(subtotal == 'NaN') {subtotal = 0.00;}
          if(montoiva == 'NaN') {montoiva = 0.00;}
          if(total == 'NaN') {total = 0.00;}
          $('#subtotal').html(subtotal);
          $('#miva').html(montoiva);
          $('#mtotal').html(total);          
        }
      });
      return false;

    });

    function actualiza_precio(){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "pedido/upd_monto",
            //data: { id: id },
            success: function(json) {
            //  alert(json.resu.total);
            var iva = 0.12;
            var montoiva = 0;
            var total = 0;
            var subtotal = 0;
            total = parseFloat(total);           
            subtotal = parseFloat(json.resu.total);
            iva = parseFloat(iva);
            montoiva = subtotal * iva;
            total = subtotal + montoiva;  
            subtotal = subtotal.toFixed(2);                           
            montoiva = montoiva.toFixed(2);
            total = total.toFixed(2);
            if(subtotal == 'NaN') {subtotal = 0.00;}
            if(montoiva == 'NaN') {montoiva = 0.00;}
            if(total == 'NaN') {total = 0.00;}
            $('#subtotal').html(subtotal);
            $('#miva').html(montoiva);
            $('#mtotal').html(total);  
            }
        });

    }


    function actualiza_valor(){
      <?php if($impuestoespecial == NULL) { $impuestoespecial = 0; }; ?>
      var impuestoespecial =  <?php print @$impuestoespecial; ?>;
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "pedido/upd_valor",
            success: function(json) {
              var subtotaliva = 0;
              var subtotalcero = 0;
              var miva = 0;
              var total = 0;
              var totalimp = 0;

              subtotaliva = json.subtotaliva;
              subtotalcero = json.subtotalcero;
              miva = json.miva;
              total = json.total;

              totalimp = (subtotaliva + subtotalcero) * impuestoespecial / 100;
              totalimp = totalimp.toFixed(2); 
              subtotaliva = subtotaliva.toFixed(2); 
              subtotalcero = subtotalcero.toFixed(2); 
              miva = miva.toFixed(2); 
              total = total.toFixed(2); 


              $('#subtotaliva').html(subtotaliva);
              $('#subtotal0').html(subtotalcero);
              $('#miva').html(miva);
              $('#impuestoespecial').html(totalimp);
              $('#mtotal').html(total);

            //  alert(subtotaliva+" - "+subtotalcero+" - "+miva+" - "+total);
  
            }
        });

    }

    /* FUNCIONES PARA EL BOTON DE PRECUENTA */
    $(document).on('click','.precuenta', function(){
      var idmesa = $('#identmesa').attr('name');
      //alert(data);

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Servicio/cargarprecuenta');?>",
        data: {id: idmesa},
        success: function(json) {

        }
      }); 
    });

        /* FUNCIONES PARA EL BOTON DE COMANDA */
    $(document).on('click','.comanda', function(){
      var idmesa = $('#identmesa').attr('name');
      //alert(data);

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Servicio/cargarcomanda');?>",
        data: {id: idmesa},
        success: function(json) {

        }
      }); 

    });

   /* ELIMINAR TODOS LOS PRODUCTOS */
    $(document).on('click', '.del_serproped', function(){  
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "pedido/elim_producto",
        success: function(json) {
          $('#detpedido').load(base_url + "Servicio/actualiza_datos");
          actualiza_precio();
          actualiza_valor();
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

      <div class="pull-left" style="margin-left: 10px; margin-top: 8px;"> 
        <button id="mesas" type="button" class="btn bg-green color-palette btn-grad " >
          <i class="fa fa-cutlery"></i> Mesas
        </button>   
      </div>      
      <div class="col-md-3">
        <h3 style="margin-top: 13px; margin-bottom: 0px; color: #fff;"><?php print $areamesa->nom_area." - ".$areamesa->nom_mesa; ?></h3>
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
  <section class="content">
    <div class="row">
      <div class="col-md-5">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"></i> Productos </h3>
            <div class="pull-right"> </div>
          </div>
          <div class="box-body" style="padding: 10px 13px 0;">
            <div class="row">
              <div class="nav-tabs-custom">

                <ul class="nav nav-tabs">
                  <li class="active"><a href="#listado" data-toggle="tab">Listado</a></li>
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

                  <div class="tab-pane active" id="listado">
                    <div class="box-body table-responsive">
                      <table id="TableProducto" class="table table-bordered table-hover table-responsive">
                        <thead>
                          <tr>
                            <th>Cod Barra</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                          </tr>
                          </thead>    
                          <tbody>                                                        
                            <?php 
                            foreach ($pro as $p) {
                            ?>
                              <tr class="addpro" id="<?php print $p->pro_id; ?>" >
                                <td>
                                    <input type="hidden" class="existencia" id="<?php print @$p->pro_id; ?>" value="<?php print @$p->existencia; ?>" >    
                                    <input type="hidden" class="servicio" id="<?php print @$p->pro_id; ?>" value="<?php print @$p->esservicio; ?>" >    
                                    <input type="hidden" class="almacen" id="<?php print @$p->pro_id; ?>" value="<?php print @$p->id_alm; ?>" >    
                                    <input type="hidden" class="variante" id="<?php print @$p->pro_id; ?>" value="<?php print @$p->habilitavariante; ?>" >    

                                  <?php print $p->pro_codigobarra; ?>
                                </td>
                                <td>
                                  <?php print $p->pro_nombre; ?>
                                </td>
                                <td>
                                  <?php print $p->pro_precioventa; ?>
                                </td>
                              </tr>
                              <?php 
                              }
                              ?>
                          </tbody>
                      </table>
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
                                    <a id="<?php print $lpro->id; ?>" class="btn btn-app addpro"><i class="fa fa-beer" aria-hidden="true"></i> <?php print $lpro->producto ?>

                                      <input type="hidden" class="almacen" id="<?php print @$lpro->id; ?>" value="<?php print @$lpro->id_alm; ?>" >
                                      <input type="hidden" class="existencia" id="<?php print @$lpro->id; ?>" value="<?php print @$lpro->existencia; ?>">

                                      <input type="hidden" class="servicio" id="<?php print @$lpro->id; ?>" value="<?php print @$lpro->esservicio; ?>" >    
                                      <input type="hidden" class="variante" id="<?php print @$lpro->id; ?>" value="<?php print @$lpro->habilitavariante; ?>" >    
                                      
                                    </a>
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
          </div>
        </div>
      </div>  
      <div class="col-md-7">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 id="identmesa" name="<?php print @$areamesa->id_mesa; ?>" class="box-title"></i> Pedido</h3>
            <div class="pull-right"> 
              <button id="precuenta" type="button" class="btn bg-green color-palette btn-grad precuenta" >
                <i class="fa fa-money"></i> Pre-Cuenta 
              </button>   
              <button id="comanda" type="button" class="btn bg-light-blue color-palette btn-grad comanda" >
                <i class="fa fa-print"></i> Comanda 
              </button>    
              <button id="comanda" type="button" class="btn btn-danger del_serproped" >
                <i class="fa fa-trash"></i> Borra Productos  
              </button>               
            </div>
          </div>
          <div class="box-body" style="padding: 10px 13px 0;">
            <div class="row">
              <div id="detpedido" class="col-md-12 table-responsive" > 
                <table class="table table-bordered detpedido table-responsive">
                  <tbody>
                    <tr>
                      <th class="text-center col-md-1">Cantidad</th>
                      <th>Nombre</th>
                      <th class="text-center col-md-1">Nota</th>
                      <th class="text-center col-md-1">Promo</th>
                      <th class="text-center col-md-1">Precio</th>
                      <th class="text-center col-md-1">SubTotal</th>
                      <th class="text-center col-md-1">Estado</th>
                      <th class="text-center col-md-1">Acción</th>
                    </tr>
                    <?php 
                    $nro = 0;
                    $total = 0;
                    $subtotaliva = 0;
                    $subtotalcero = 0;
                    $subtotal = 0;
                    $submonto = 0;
                    $miva = 0;
                    $iva = 0.12;
                    if(@$detmesa != NULL){
                      if (count($detmesa) > 0) {
                        foreach ($detmesa as $dm):
                          $nro = $nro + 1;
                          /*
                          if ($dm->pro_grabaiva == 1){
                            $subtotaliva += $dm->cantidad * $dm->precio;
                          } else {
                            $subtotalcero += $dm->cantidad * $dm->precio;
                          }
                          $subtotal = $dm->cantidad * $dm->precio;
                          $submonto = $submonto + $subtotal;
                          $miva = $submonto * $iva;
                          */

                          if ($dm->pro_grabaiva == 1){
                            $subttiva = $dm->cantidad * $dm->precio;
                            $subttiva = round($subttiva * (1 + $iva) , 2) - round($subttiva, 2);
                            $miva += $subttiva;
                            $subtotaliva += $dm->cantidad * $dm->precio;
                          } else {
                            $subtotalcero += $dm->cantidad * $dm->precio;
                          }

                          $subtotal = $dm->cantidad * $dm->precio;
                          $submonto = $submonto + $subtotal;





                    ?>
                            <tr>
                              <!-- CANTIDAD -->
                              <td class="text-center">
                                <input type="text" class="form-control text-center cantidad" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" value="<?php print $dm->cantidad; ?>" >
                              </td>
                              <!-- NOMBRE DEL PRODUCTO -->
                              <td>
                                <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                  <div class="col-md-8" style="padding-left: 0px; padding-right: 0px;">
                                <?php print $dm->pro_nombre; ?>                                    
                                  </div>
                                <?php
                                  if($dm->variante == 1){ ?>
                                    <a href="#" title="Variantes del Producto" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="btn btn-warning btn-xs btn-grad serpro_var pull-right">Detalles</a>    
                                <?php }
                                ?>                                  
                                </div>

                              </td>
                              <!-- NOTA DEL PEDIDO -->
                              <td>
                                <div class="text-center">
                                  <a href="#" title="Añadir Nota" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="btn btn-success btn-xs btn-grad seraddnota">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Nota
                                  </a> 
                                </div>
                              </td>

                              <!-- PROMO -->
                              <td>
                                <input type="hidden" class="form-control text-center preciobase" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" value="<?php print @$dm->pro_precioventa; ?>" >
                                <div class="text-center">
                                  <input type="checkbox" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="promo" <?php if(@$dm != NULL){ if(@$dm->promo == 1){ print "checked='' ";} }?> >
                                </div>
                              </td>

                              <!-- PRECIO DEL PRODUCTO -->
                              <td id="<?php print @$dm->id_producto; ?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="text-right producto"><?php print $dm->precio; ?></td>
                              <!-- SUBTOTAL -->
                              <td name="<?php print @$dm->pro_nombre; ?>" class="text-right ">
                                <div id="<?php print @$dm->id_producto; ?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>">
                                  <?php 
                                    $subtotal = $dm->cantidad * $dm->precio;
                                    print $subtotal; 
                                  ?>
                                </div>
                                
                              </td>
                              <!-- ESTATUS checked-->
                              <?php 
                                if(@$dm->estatus == '1'){ $est = 'checked'; }else{ $est = '';}
                              ?>
                              <td class="text-center">
                                <input <?php print $est; ?> id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="est_ped" type="checkbox"  data-toggle="toggle" data-on="<i class='fa fa-check'></i> Entregado" data-off="<i class='fa fa-cutlery' aria-hidden='true'></i> Pedido" data-onstyle="success" data-offstyle="danger" data-size="small"> 
                              
                              </td>
                              <!-- ACCION -->
                              <td class="text-center">
                                  <a href="#" title="Eliminar" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="btn btn-danger btn-grad serpro_del"><i class="fa fa-trash-o"></i></a>
                              </td>
                          </tr>
                          <?php
                                  endforeach;
                              }
                            

                          }
                          ?>
                        </tbody>
                      </table>
                    </div>

                <div id="monto"  align="center" class="box-footer">
                  <div class="col-md-12">
                    <div id="calmonto" class="pull-right" style="margin-right: 10px;">
                      <table class="table table-clear calmonto" >
                        <tbody>
                          <tr>
                            <td class="text-left"><strong>Subtotal IVA 12 % </strong></td>
                            <td id="subtotaliva" class="text-right">$ <?php  print number_format(@$subtotaliva,2,",","."); ?></td>                                        
                          <tr>
                          <tr>
                            <td class="text-left"><strong>Subtotal IVA 0 % </strong></td>
                            <td id="subtotal0" class="text-right">$ <?php  print number_format(@$subtotalcero,2,",","."); ?></td>                                        
                          <tr>                      
                          <tr>
                            <td class="text-left"><strong>IVA (12%)</strong></td>
                            <td id="miva" class="text-right">$ <?php print number_format(@$miva,2,",","."); ?></td>                                        
                          </tr>
                          <?php if ($habilitaimpuestoespecial > 0) { 
                            $subtotales = 0;
                            $subtotales = @$subtotaliva + @$subtotalcero;
                          ?>
                        
                            <tr>
                              <input type="hidden" id="tieneimpuesto" value="<?php print $impuestoespecial; ?>" >
                              <td class="text-left"><strong><?php print $descripcionimpuestoespecial; ?></strong></td>
                              <td id="impuestoespecial" class="text-right">$<?php $totalimp = $subtotales * $impuestoespecial / 100; print number_format(@$totalimp,2,",","."); ?></td>                                        
                            </tr>
                          <?php } ?>                          
                          <tr>
                            <td class="text-left"><strong>Total</strong></td>
                            <td id="mtotal" class="text-right"><strong>$ <?php $mtotal = $subtotaliva + $subtotalcero + $miva + @$totalimp; print number_format(@$mtotal,2,",","."); ?></strong></td>                                        
                          </tr>      
                        </tbody>
                      </table>
                    </div>                
                  </div>                
                </div>                    



            </div>
          </div>
        </div>
      </div>      
    </div>
  </section>

   <footer class="main-footer pie" style ="margin-left: 0px;">
    <div class="pull-right hidden-xs">
      <b>Version</b> 3.0
    </div>
    <strong>Copyright &copy; 2019 <a href="#"><?php print $nombresistema; ?></a>.</strong> All rights
    reserved.
  </footer>
</body>
</html>