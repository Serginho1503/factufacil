<?php
/* ------------------------------------------------
  ARCHIVO: Distribución.php
  DESCRIPCION: Contiene la vista principal del módulo de Distribución.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Distribución'</script>";
date_default_timezone_set("America/Guayaquil");

$parametro = &get_instance();
$parametro->load->model("Parametros_model");
$pedidocliente = $parametro->Parametros_model->sel_pedidocliente();
$pedidomesero = $parametro->Parametros_model->sel_pedidomesero();
$ptoventasingular = $parametro->Parametros_model->sel_ptoventasingular();
$ptoventaplural = $parametro->Parametros_model->sel_ptoventaplural();

$parametro->load->model("Sistema_model");
$sistema = $parametro->Sistema_model->sel_sistema();
$iconopedido = $sistema->icon_pedido;

?>
<style type="text/css">
  .tomar{
    background: rgba(0, 0, 0, 0.2) none repeat scroll 0 0;
    border-radius: 2px 0 0 2px;
    display: block;
    float: left;
  /*  font-size: 45px;*/
    height: 90px;
  /*  line-height: 90px;*/
    text-align: center;
    width: 90px;    
  }

  a.disabled {
      pointer-events: none;
      color: #ccc;
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
           url: "<?php print $base_url;?>Pedido/tmp_pedido",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) == 1) {
                 location.replace("<?php print $base_url;?>Pedido/pedido_mesa");
              } else {
                 alert("Error de conexión");
              }
           }
        });
      });

      /* REMITIR A FACTURAR */
      $(document).on('click', '.facturar', function(){
        id = $(this).attr('id');
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php print $base_url;?>Facturar/pedido_factura",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) > 0) {
                location.replace("<?php print $base_url;?>Facturar/factura_deposito");
              } else {
                 alert("No se pudo facturar el pedido. Verifique el estado de la caja.");
              }

           }
        });
        
      });

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
          href: "<?php echo base_url('pedido/cargarcambiomesa');?>", 
          success: function(json) {
              if (parseInt(json.resu) > 0) {
                 location.replace("<?php print $base_url;?>pedido");
              } else {
                 alert("Error de conexión");
              }
          }    
        });
      });

      /* CAMBIO DE Estado */
      $(document).on('click', '.estadomesa', function(){
        id = $(this).attr('id')
        estado = $(this).attr('name')
        if (estado == 1) { strnuevoestado = 'Mantenimiento'; } else { strnuevoestado = 'Servicio'; }
        if (confirm('Desea pasar a estado "' + strnuevoestado + '"?')){
          $.ajax({
             type: "POST",
             dataType: "json",
             url: "<?php print $base_url;?>pedido/cambiar_estadomesa",
             data: {mesa: id, estado: estado},
             success: function(json) {
                nuevoestado = json.resu
                //$(this).attr('name', nuevoestado)
                $('.estadomesa[id='+id+']').attr('name', nuevoestado)
                $('.pedir[id='+id+']').removeClass('disabled');
                if (nuevoestado == 3){
                  $('.pedir[id='+id+']').addClass('disabled');
                }  
                $('.divpedir[id='+id+']').removeClass('bg-green');
                $('.divpedir[id='+id+']').removeClass('bg-red');
                $('.divpedir[id='+id+']').removeClass('bg-yellow');
                if (nuevoestado == 1){
                  $('.divpedir[id='+id+']').addClass('bg-green');
                }else{
                  if (nuevoestado == 2){
                    $('.divpedir[id='+id+']').addClass('bg-red');
                  }else{
                    $('.divpedir[id='+id+']').addClass('bg-yellow');
                  }  
                }  

             }
          });
        }  
      });


      $('.pedir').each(function(index, el) {
        id = this.id;
        estado = $(this).attr('name');
        $('.pedir[id='+id+']').removeClass('disabled');
        if (estado == 3){
          $('.pedir[id='+id+']').addClass('disabled');
        }  
      });   


    }); 

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa <?php print @$iconopedido; ?>"></i> Distribución de <?php print $ptoventaplural; ?> 
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>pedido">Distribución</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">

              <div class="box box-danger">

                <div class="box-header with-border">
                  <div class="pull-right">

                    <button class="btn bg-orange color-palette btn-grad cambio" type="button">
                      <i class="fa fa-retweet" aria-hidden="true"></i> Cambio de <?php print $ptoventasingular; ?>
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
                        $cambioestado = 1;
                        if($me->id_area == $ar->id_area){  
                          if ($me->id_estado < 3){
                            if($me->cliente > 0 || $me->pedido > 0){ 
                              $estmesa = 'bg-red'; 
                              $fact = 1; 
                              $cambioestado = 0;
                            }else{ $estmesa = 'bg-green'; $fact = 0; }
                          }else { $estmesa = 'bg-yellow'; $fact = 0; }
                  ?>                    


                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="info-box divpedir <?php print $estmesa; ?>" 
                          id="<?php print $me->id_mesa; ?>"
                      >
                        <a id="<?php print $me->id_mesa; ?>" href="#" title="Tomar Orden" style="color: #ffffff;" class="pedir" name="<?php print $me->id_estado; ?>">
                          <div class="tomar" >
                            <span class="text-center" style="font-size: 45px;"><i class="fa <?php print @$iconopedido; ?>"></i></span><br>
                            <span class="text-center" style="font-size: 12px;">Tomar Orden</span>
                          </div>
                        </a>

                        <div class="info-box-content">
                          <span class="info-box-number"><?php print $ptoventasingular; ?>: <?php print $me->nom_mesa; ?></span>
                          <span class="info-box-text">
                          <?php if ($pedidomesero->valor == 1){
                            foreach ($elmese as $ms) {
                              if($ms->id_mesa == $me->id_mesa){
                                $mesero = $ms->nom_mesero;
                                print 'Mesero: ' . $mesero;
                              }
                            }
                          } ?>
                          </span>

                          <span class="info-box-text">
                          <?php if ($pedidocliente->valor == 1){
                            foreach ($clientes as $cli) {
                              if($me->id_cliente == $cli->id_cliente){
                                print 'Cliente: ' . $cli->nom_cliente;
                              }
                            }
                          } ?> 
                          </span>

                          <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                          </div>
                            <?php if($fact == 1){ ?>
                              <span class="progress-description text-right">
                                <a id="<?php print $me->id_mesa; ?>" href="#" class="small-box-footer facturar" style="color: #ffffff;">
                                  Facturar <i class="fa fa-credit-card"></i>
                                </a>
                              </span>    
                            <?php } else { ?>
                              <span class="progress-description text-right">
                                <a id="<?php print $me->id_mesa; ?>" href="#" class="small-box-footer estadomesa" style="color: #ffffff;" name="<?php print $me->id_estado; ?>">
                                  Cambiar Estado <i class="fa fa-credit-card"></i>
                                </a>
                              </span>    
                            <?php }  ?>

                        </div>
                        <!-- /.info-box-content margin-bottom: 0px;-->
                      </div>
                      <!-- /.info-box -->
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
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

