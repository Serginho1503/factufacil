<?php
/* ------------------------------------------------
  ARCHIVO: almacen.php
  DESCRIPCION: Contiene la vista principal del módulo de Almacen.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
print "<script>document.title = 'FACTUFÁCIL - Roles'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<style type="text/css">
  /*  bhoechie tab */
  div.bhoechie-tab-container{

    z-index: 10;
    background-color: #ffffff;
    padding: 0 !important;
    border-radius: 4px;
    -moz-border-radius: 4px;
    border:1px solid #ddd;
    margin-top: 20px;
    margin-left: 50px;
    -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
    box-shadow: 0 6px 12px rgba(0,0,0,.175);
    -moz-box-shadow: 0 6px 12px rgba(0,0,0,.175);
    background-clip: padding-box;
    opacity: 0.97;
    filter: alpha(opacity=97);
  }
  div.bhoechie-tab-menu{
    padding-right: 0;
    padding-left: 0;
    padding-bottom: 0;
  }
  div.bhoechie-tab-menu div.list-group{
    margin-bottom: 0;
  }
  div.bhoechie-tab-menu div.list-group>a{
    margin-bottom: 0;
  }
  div.bhoechie-tab-menu div.list-group>a .glyphicon,
  div.bhoechie-tab-menu div.list-group>a .fa {
    color: #dd4b39;
  }
  div.bhoechie-tab-menu div.list-group>a:first-child{
    border-top-right-radius: 0;
    -moz-border-top-right-radius: 0;
  }
  div.bhoechie-tab-menu div.list-group>a:last-child{
    border-bottom-right-radius: 0;
    -moz-border-bottom-right-radius: 0;
  }
  div.bhoechie-tab-menu div.list-group>a.active,
  div.bhoechie-tab-menu div.list-group>a.active .glyphicon,
  div.bhoechie-tab-menu div.list-group>a.active .fa{
    background-color: #dd4b39;
    background-image: #dd4b39;
    color: #ffffff;
  }
  div.bhoechie-tab-menu div.list-group>a.active:after{
    content: '';
    position: absolute;
    left: 100%;
    top: 50%;
    margin-top: -13px;
    border-left: 0;
    border-bottom: 13px solid transparent;
    border-top: 13px solid transparent;
    border-left: 10px solid #dd4b39;
  }

  div.bhoechie-tab-content{
    background-color: #ffffff;
    /* border: 1px solid #eeeeee; */
    padding-left: 20px;
    padding-top: 10px;
  }

  div.bhoechie-tab div.bhoechie-tab-content:not(.active){
    display: none;
  }  
  .list-group-item.active, .list-group-item.active:hover, .list-group-item.active:focus {
    background-color: #dd4b39;
    border-color: #dd4b39;
    color: #fff;
    z-index: 2;
  }
</style>
<script type='text/javascript' language='javascript'>
  $(document).ready(function () {
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
  }); 
</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-shield"></i> Roles
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>rol">Roles</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

          <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 bhoechie-tab-container">
              <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 bhoechie-tab-menu">
                <div class="list-group">
                  <a href="#" class="list-group-item active text-center">
                    <h4 class="fa fa-files-o"></h4><br/>Inventario
                  </a>
                  <a href="#" class="list-group-item text-center">
                    <h4 class="fa fa-th"></h4><br/>Transacciones
                  </a>
                  <a href="#" class="list-group-item text-center">
                    <h4 class="fa fa-line-chart"></h4><br/>Contabilidad
                  </a>
                  <a href="#" class="list-group-item text-center">
                    <h4 class="fa fa-bar-chart"></h4><br/>Reportes
                  </a>
                  <a href="#" class="list-group-item text-center">
                    <h4 class="fa fa-cogs"></h4><br/>Configurar
                  </a>
                </div>
              </div>
              <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab">
                  <!-- flight section -->
                  <div class="bhoechie-tab-content active">
                      <center>
                          <section class="content">
                            <div class="row">
                              <div class="col-md-12">
                                <div class="box">
                                  <div class="box-header with-border">
                                    <h3 class="box-title">Funciones del Modulo Inventario</h3>
                                  </div>
                                  <!-- /.box-header -->
                                  <div class="box-body">
                                    <table class="table table-bordered">

                                      <tr>
                                        <th style="width: 10px">Nro</th>
                                        <th>Nombre</th>
                                        <th>Evento</th>
                                        <th style="width: 40px">Acción</th>
                                      </tr>

                                      <tr>
                                        <td class="text-center"> 1 </td>
                                        <td>Almacen</td>
                                        <td>Ver</td>
                                        <td class="text-center">                                         
                                            <input name="chk" id="chk" type="checkbox">
                                        </td>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="text-center"> 1 </td>
                                        <td>Almacen</td>
                                        <td>Agregar</td>
                                        <td class="text-center">                                         
                                            <input name="chk" id="chk" type="checkbox">
                                        </td>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="text-center"> 1 </td>
                                        <td>Almacen</td>
                                        <td>Editar</td>
                                        <td class="text-center">                                         
                                            <input name="chk" id="chk" type="checkbox">
                                        </td>
                                        </td>
                                      </tr>                                      
                                      <tr>
                                        <td class="text-center"> 1 </td>
                                        <td>Almacen</td>
                                        <td>Eliminar</td>
                                        <td class="text-center">                                         
                                            <input name="chk" id="chk" type="checkbox">
                                        </td>
                                        </td>
                                      </tr>                                      
                                    </table>
                                  </div>
                                  <!-- /.box-body -->
                                  <div class="box-footer clearfix">

                                  </div>
                                </div>
                                <!-- /.box -->
                              </div>
                            </div>
                          </section>  





                   <!--     <div class="fixed-table-body">
                          <table id="table-pagination" data-toggle="table" data-url="../assets/global/plugins/bootstrap-table/data/data2.json" data-height="299" data-pagination="true" data-search="true" class="table table-hover" style="margin-top: -41px;">
                            <thead>
                              <tr>
                                <th class="bs-checkbox " style="width: 36px; " data-field="state" tabindex="0">
                                  <div class="th-inner ">
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                      <input name="btSelectAll" type="checkbox"><span></span>
                                    </label>
                                  </div>
                                  <div class="fht-cell"></div>
                                </th>
                                <th style="text-align: right; " data-field="id" tabindex="0">
                                  <div class="th-inner sortable both">Item ID</div>
                                  <div class="fht-cell"></div>
                                </th>
                                <th style="text-align: center; " data-field="name" tabindex="0">
                                  <div class="th-inner sortable both">Item Name</div>
                                  <div class="fht-cell"></div>
                                </th>
                                <th style="" data-field="price" tabindex="0">
                                  <div class="th-inner sortable both">Item Price</div>
                                  <div class="fht-cell"></div>
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr data-index="0" class="selected">
                                <td class="bs-checkbox">
                                  <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input data-index="0" name="btSelectItem" type="checkbox"><span></span>
                                  </label>
                                </td>
                                <td style="text-align: right; ">10
                                </td>
                                <td style="text-align: center; ">test0
                                </td>
                                <td style="">$0
                                </td>
                              </tr>
                              <tr data-index="1" class="selected">
                                <td class="bs-checkbox">
                                  <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">  
                                    <input data-index="1" name="btSelectItem" type="checkbox"><span></span>
                                  </label>
                                </td>
                                <td style="text-align: right; ">1
                                </td>
                                <td style="text-align: center; ">test1
                                </td>
                                <td style="">$1
                                </td>
                              </tr>
                          <tr data-index="2" class="selected"><td class="bs-checkbox"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input data-index="2" name="btSelectItem" type="checkbox"><span></span></label></td><td style="text-align: right; ">2</td><td style="text-align: center; ">test2</td><td style="">$2</td></tr><tr data-index="3"><td class="bs-checkbox"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input data-index="3" name="btSelectItem" type="checkbox"><span></span></label></td><td style="text-align: right; ">3</td><td style="text-align: center; ">test3</td><td style="">$3</td></tr><tr data-index="4"><td class="bs-checkbox"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input data-index="4" name="btSelectItem" type="checkbox"><span></span></label></td><td style="text-align: right; ">4</td><td style="text-align: center; ">test4</td><td style="">$4</td></tr><tr data-index="5"><td class="bs-checkbox"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input data-index="5" name="btSelectItem" type="checkbox"><span></span></label></td><td style="text-align: right; ">5</td><td style="text-align: center; ">test5</td><td style="">$5</td></tr><tr data-index="6"><td class="bs-checkbox"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input data-index="6" name="btSelectItem" type="checkbox"><span></span></label></td><td style="text-align: right; ">6</td><td style="text-align: center; ">test6</td><td style="">$6</td></tr><tr data-index="7"><td class="bs-checkbox"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input data-index="7" name="btSelectItem" type="checkbox"><span></span></label></td><td style="text-align: right; ">7</td><td style="text-align: center; ">test7</td><td style="">$7</td></tr><tr data-index="8"><td class="bs-checkbox"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input data-index="8" name="btSelectItem" type="checkbox"><span></span></label></td><td style="text-align: right; ">8</td><td style="text-align: center; ">test8</td><td style="">$8</td></tr><tr data-index="9"><td class="bs-checkbox"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input data-index="9" name="btSelectItem" type="checkbox"><span></span></label></td><td style="text-align: right; ">9</td><td style="text-align: center; ">test9</td><td style="">$9</td></tr> 
                            </tbody>
                          </table>
                        </div>  -->
                      </center>
                  </div>
                  <!-- train section -->
                  <div class="bhoechie-tab-content">
                      <center>
                        <h1 class="glyphicon glyphicon-road" style="font-size:12em;color:#55518a"></h1>
                        <h2 style="margin-top: 0;color:#55518a">Cooming Soon</h2>
                        <h3 style="margin-top: 0;color:#55518a">Train Reservation</h3>
                      </center>
                  </div>
      
                  <!-- hotel search -->
                  <div class="bhoechie-tab-content">
                      <center>
                        <h1 class="glyphicon glyphicon-home" style="font-size:12em;color:#55518a"></h1>
                        <h2 style="margin-top: 0;color:#55518a">Cooming Soon</h2>
                        <h3 style="margin-top: 0;color:#55518a">Hotel Directory</h3>
                      </center>
                  </div>
                  <div class="bhoechie-tab-content">
                      <center>
                        <h1 class="glyphicon glyphicon-cutlery" style="font-size:12em;color:#55518a"></h1>
                        <h2 style="margin-top: 0;color:#55518a">Cooming Soon</h2>
                        <h3 style="margin-top: 0;color:#55518a">Restaurant Diirectory</h3>
                      </center>
                  </div>
                  <div class="bhoechie-tab-content">
                      <center>
                        <h1 class="glyphicon glyphicon-credit-card" style="font-size:12em;color:#55518a"></h1>
                        <h2 style="margin-top: 0;color:#55518a">Cooming Soon</h2>
                        <h3 style="margin-top: 0;color:#55518a">Credit Card</h3>
                      </center>
                  </div>
              </div>
          </div>
          <!--
          <div class="col-md-6">
               START VERTICAL TABS WITH HEADING 
              <div class="panel panel-default nav-tabs-vertical">                   
                  <div class="panel-heading">
                      <h3 class="panel-title">Tabs with heading</h3>
                  </div>
                  <div class="tabs">
                      <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab19" data-toggle="tab">First</a></li>
                          <li><a href="#tab20" data-toggle="tab">Second</a></li>
                          <li><a href="#tab21" data-toggle="tab">Third</a></li>
                      </ul>                    
                      <div class="panel-body tab-content">
                          <div class="tab-pane active" id="tab19">
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dictum dolor sem, quis pharetra dui ultricies vel. Cras non pulvinar tellus, vel bibendum magna. Morbi tellus nulla, cursus non nisi sed, porttitor dignissim erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc facilisis commodo lectus. Vivamus vel tincidunt enim, non vulputate ipsum. Ut pellentesque consectetur arcu sit amet scelerisque. Fusce commodo leo eros, ut eleifend massa congue at.</p>
                              <p>Nam a nisi et nisi tristique lacinia non sit amet orci. Duis blandit leo odio, eu varius nulla fringilla adipiscing. Praesent posuere blandit diam, sit amet suscipit justo consequat sed. Duis cursus volutpat ante at convallis. Integer posuere a enim eget imperdiet. Nulla consequat dui quis purus molestie fermentum. Donec faucibus sapien eu nisl placerat auctor. Pellentesque quis justo lobortis, tempor sapien vitae, dictum orci.</p>
                          </div>
                          <div class="tab-pane" id="tab20">
                              <p>Donec tristique eu sem et aliquam. Proin sodales elementum urna et euismod. Quisque nisl nisl, venenatis eget dignissim et, adipiscing eu tellus. Sed nulla massa, luctus id orci sed, elementum consequat est. Proin dictum odio quis diam gravida facilisis. Sed pharetra dolor a tempor tristique. Sed semper sed urna ac dignissim. Aenean fermentum leo at posuere mattis. Etiam vitae quam in magna viverra dictum. Curabitur feugiat ligula in dui luctus, sed aliquet neque posuere.</p>
                              <p>Nam a nisi et nisi tristique lacinia non sit amet orci. Duis blandit leo odio, eu varius nulla fringilla adipiscing. Praesent posuere blandit diam, sit amet suscipit justo consequat sed. Duis cursus volutpat ante at convallis. Integer posuere a enim eget imperdiet. Nulla consequat dui quis purus molestie fermentum. Donec faucibus sapien eu nisl placerat auctor. Pellentesque quis justo lobortis, tempor sapien vitae, dictum orci.</p>
                          </div>
                          <div class="tab-pane" id="tab21">
                              <p>Vestibulum cursus augue sed leo tempor, at aliquam orci dictum. Sed mattis metus id velit aliquet, et interdum nulla porta. Etiam euismod pellentesque purus, in fermentum eros venenatis ut. Praesent vitae nibh ac augue gravida lacinia non a ipsum. Aenean vestibulum eu turpis eu posuere. Sed eget lacus lacinia, mollis urna et, interdum dui. Donec sed diam ut metus imperdiet malesuada. Maecenas tincidunt ultricies ipsum, lobortis pretium dolor sodales ut. Donec nec fringilla nulla. In mattis sapien lorem, nec tincidunt elit scelerisque tempus.</p>
                              <p>Nam a nisi et nisi tristique lacinia non sit amet orci. Duis blandit leo odio, eu varius nulla fringilla adipiscing. Praesent posuere blandit diam, sit amet suscipit justo consequat sed. Duis cursus volutpat ante at convallis. Integer posuere a enim eget imperdiet. Nulla consequat dui quis purus molestie fermentum. Donec faucibus sapien eu nisl placerat auctor. Pellentesque quis justo lobortis, tempor sapien vitae, dictum orci.</p>
                          </div>                        
                      </div>
                  </div>
              </div>                        
              
          </div> -->
<!--
          <div class="col-md-6">
              START VERTICAL TABS 
              <div class="panel panel-default tabs nav-tabs-vertical">                   
                  <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab22" data-toggle="tab">First</a></li>
                      <li><a href="#tab23" data-toggle="tab">Second</a></li>
                      <li><a href="#tab24" data-toggle="tab">Third</a></li>
                  </ul>                    
                  <div class="panel-body tab-content">
                      <div class="tab-pane active" id="tab22">
                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dictum dolor sem, quis pharetra dui ultricies vel. Cras non pulvinar tellus, vel bibendum magna. Morbi tellus nulla, cursus non nisi sed, porttitor dignissim erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc facilisis commodo lectus. Vivamus vel tincidunt enim, non vulputate ipsum. Ut pellentesque consectetur arcu sit amet scelerisque. Fusce commodo leo eros, ut eleifend massa congue at.</p>
                          <p>Nam a nisi et nisi tristique lacinia non sit amet orci. Duis blandit leo odio, eu varius nulla fringilla adipiscing. Praesent posuere blandit diam, sit amet suscipit justo consequat sed. Duis cursus volutpat ante at convallis. Integer posuere a enim eget imperdiet. Nulla consequat dui quis purus molestie fermentum. Donec faucibus sapien eu nisl placerat auctor. Pellentesque quis justo lobortis, tempor sapien vitae, dictum orci.</p>
                      </div>
                      <div class="tab-pane" id="tab23">
                          <p>Donec tristique eu sem et aliquam. Proin sodales elementum urna et euismod. Quisque nisl nisl, venenatis eget dignissim et, adipiscing eu tellus. Sed nulla massa, luctus id orci sed, elementum consequat est. Proin dictum odio quis diam gravida facilisis. Sed pharetra dolor a tempor tristique. Sed semper sed urna ac dignissim. Aenean fermentum leo at posuere mattis. Etiam vitae quam in magna viverra dictum. Curabitur feugiat ligula in dui luctus, sed aliquet neque posuere.</p>
                          <p>Nam a nisi et nisi tristique lacinia non sit amet orci. Duis blandit leo odio, eu varius nulla fringilla adipiscing. Praesent posuere blandit diam, sit amet suscipit justo consequat sed. Duis cursus volutpat ante at convallis. Integer posuere a enim eget imperdiet. Nulla consequat dui quis purus molestie fermentum. Donec faucibus sapien eu nisl placerat auctor. Pellentesque quis justo lobortis, tempor sapien vitae, dictum orci.</p>
                      </div>
                      <div class="tab-pane" id="tab24">
                          <p>Vestibulum cursus augue sed leo tempor, at aliquam orci dictum. Sed mattis metus id velit aliquet, et interdum nulla porta. Etiam euismod pellentesque purus, in fermentum eros venenatis ut. Praesent vitae nibh ac augue gravida lacinia non a ipsum. Aenean vestibulum eu turpis eu posuere. Sed eget lacus lacinia, mollis urna et, interdum dui. Donec sed diam ut metus imperdiet malesuada. Maecenas tincidunt ultricies ipsum, lobortis pretium dolor sodales ut. Donec nec fringilla nulla. In mattis sapien lorem, nec tincidunt elit scelerisque tempus.</p>
                          <p>Nam a nisi et nisi tristique lacinia non sit amet orci. Duis blandit leo odio, eu varius nulla fringilla adipiscing. Praesent posuere blandit diam, sit amet suscipit justo consequat sed. Duis cursus volutpat ante at convallis. Integer posuere a enim eget imperdiet. Nulla consequat dui quis purus molestie fermentum. Donec faucibus sapien eu nisl placerat auctor. Pellentesque quis justo lobortis, tempor sapien vitae, dictum orci.</p>
                      </div>                        
                  </div>
              </div>                        
               END VERTICAL TABS 
          </div>            -->

           
        </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

