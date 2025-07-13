<?php
/* ------------------------------------------------
  ARCHIVO: Precio.php
  DESCRIPCION: Contiene la vista principal del módulo de Precio.
  FECHA DE CREACIÓN: 12/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Análisis de Ventas'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {

    $.datepicker.setDefaults($.datepicker.regional["es"]);
    $('#desde').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
      });
    $('#desde').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    $('#hasta').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
      });
    $('#hasta').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });  

    var tipocategoria = 1;
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href");       
        switch(target) {
            case "#tabventatipoprecio":
                tipocategoria = 2; break;
            default:
                tipocategoria = 1; 
        } 
    });     

    $('.actualiza').click(function(){
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();
      var sucursal = $('#cmb_sucursal option:selected').val();      
      var producto = $('#cmb_producto option:selected').val();      
      strproducto = $('#cmb_producto option:selected').html();

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/tmp_analisis');?>",
        data: { hasta: hasta, desde: desde, sucursal: sucursal, producto: producto }
      }).done(function (result) {

        if (tipocategoria == 1){

          $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('Facturar/lst_ventasdetalles_tipoprecio');?>"
          }).done(function (result) {

            new Chart($("#myChart_ventaperiodo"), {
              type: 'line',
              data: {
                labels: result.fechas,
                datasets: result.precios
              },
              options: {
                title: {
                  display: true,
                  text: 'Detalles de ventas por tipo de precio de ' + desde + ' a ' + hasta + ' (' + strproducto + ')'
                }
              }
            });

          });

        }
        else{
          $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('Facturar/lst_ventasresumen_tipoprecio');?>"
          }).done(function (result) {
            
            labels = [];
            precios = [];
            colors = [];
            result.forEach(function(precio){
              labels.push(precio.desc_precios);
              precios.push(precio.total);
              colors.push(precio.color);
            });
            new Chart($("#Chart_ventatipoprecio"), {
                type: 'bar',
                data: {
                  labels: labels,
                  datasets: [
                    {
                      label: "Ventas (valores)",
                      backgroundColor: colors,
                      data: precios
                    }
                  ]
                },
                options: {
                  legend: { display: false },
                  title: {
                    display: true,
                    text: 'Resumen de ventas por tipo de precio de ' + desde + ' a ' + hasta + ' (' + strproducto + ')'
                  },
                  scales: {
                      yAxes: [{
                          ticks: {
                              beginAtZero:true
                          }
                      }]
                  }                
                }
            });
          });
        }  
      }); 
    }); 


   }); 




</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-money"></i> Análisis de Ventas
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content" >
      <div class="row">
            
          <div class="col-md-12">
            <div class="box box-danger">
              <div class="box-header with-border">

                <div class="col-md-3" style="margin-bottom: 0px; ">
                  <label for="lb_res">Sucursal</label>
                  <select id="cmb_sucursal" name="cmb_sucursal" class="form-control datogenservicio">
                    <option  value="0" selected="TRUE">Todas las Sucursales</option>
                    <?php  
                      if (count($sucursales) > 0) {
                        foreach ($sucursales as $obj):
                            if(@$sucursal != NULL){
                                if($obj->id_sucursal == $sucursal){ ?>
                                     <option value="<?php  print $obj->id_sucursal; ?>" selected="TRUE"> <?php  print $obj->nom_sucursal; ?> </option>
                                    <?php
                                }else{ ?>
                                    <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                                    <?php
                                }
                            }else{ ?>
                                <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                                <?php
                                }   ?>
                            <?php
                        endforeach;
                      }
                    ?>
                  </select>          
                </div>                         

                <div class="col-md-3" style="margin-bottom: 0px; ">
                  <label for="lb_res">Producto</label>
                  <select id="cmb_producto" name="cmb_producto" class="form-control ">
                    <option  value="0" selected="TRUE">Todos los Productos</option>
                    <?php  
                      if (count($productos) > 0) {
                        foreach ($productos as $obj):
                            if(@$producto != NULL){
                                if($obj->pro_id == $producto){ ?>
                                     <option value="<?php  print $obj->pro_id; ?>" selected="TRUE"> <?php  print $obj->pro_nombre; ?> </option>
                                    <?php
                                }else{ ?>
                                    <option value="<?php  print $obj->pro_id; ?>" > <?php  print $obj->pro_nombre; ?> </option>
                                    <?php
                                }
                            }else{ ?>
                                <option value="<?php  print $obj->pro_id; ?>" > <?php  print $obj->pro_nombre; ?> </option>
                                <?php
                                }   ?>
                            <?php
                        endforeach;
                      }
                    ?>
                  </select>          
                </div>                         

                <div class="form-group col-md-2" style="margin-bottom: 0px; ">
                  <label class="control-label text-left" style="padding-left: 0px;">Desde</label>
                  <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" class="form-control pull-right validate[required]" id="desde" name="desde" value="<?php if (@$desde != NULL) { $fec =  str_replace('-', '/', $desde); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec;} ?>">
                  </div>
                </div> 

                <div class="form-group col-md-3" style="margin-bottom: 0px; ">
                  <label class="control-label" style="padding-left: 0px;">Hasta</label>
                  <div class="input-group date ">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" class="form-control pull-right validate[required]" id="hasta" name="hasta" value="<?php if (@$hasta != NULL) { $fec =  str_replace('-', '/', $hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec;} ?>" style="padding-right: 0px;">

                    <span class="input-group-btn">
                      <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                    </span>

                  </div>
                </div>


              </div>
            </div>
          </div>        

        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">


              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                 <li class="active"><a href="#tabventaperiodo" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Ventas por Período</a></li>                            
                 <li ><a href="#tabventatipoprecio" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Ventas por Tipo de Precio</a></li>                            
                </ul>

                <div class="tab-content">

                  <div class="tab-pane active" id="tabventaperiodo">
  
                    <div style="width: 600px; height: 800px;">
                      <canvas id="myChart_ventaperiodo" ></canvas>
                    </div>  
                      
                  </div> 

                  <div class="tab-pane" id="tabventatipoprecio">

                    <div style="width: 600px; height: 800px;">
                      <canvas id="Chart_ventatipoprecio"></canvas>
                    </div>  
                                         
                  </div> 

                </div> 

              </div>   

            </div>
          </div>
            
        </div>
      </div>  
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

