<?php
/* ------------------------------------------------
  ARCHIVO: almacen.php
  DESCRIPCION: Contiene la vista principal del módulo de Almacen.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Roles'</script>";
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

<link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/arbol/css/jquery-ui-lightness.css">
<link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/arbol/css/jquery.checkboxtree.min.css">

<!-- load jQuery 1.9.1 -->
<script type="text/javascript" src="<?php print $base_url; ?>assets/plugins/arbol/js/1.4.2_jquery.min.js"></script> 
<script type="text/javascript" src="<?php print $base_url; ?>assets/plugins/arbol/js/jquery.checkboxtree.min.js"></script> 
<script type="text/javascript">

  /* VARIABLE QUE DEFINE OTRA VERSION DEL JQUERY */
  var $jq = $.noConflict(true);
  $jq(document).ready(function () {

  /* Estructura de los TABS */  
  $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
      e.preventDefault();
      $(this).siblings('a.active').removeClass("active");
      $(this).addClass("active");
      var index = $(this).index();
      $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
      $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
  });

  /* Se inicializan los TreeView de los Check */
  $jq('#rol_cat').checkboxTree();
  $jq('#tree2').checkboxTree();
  $jq('#tree3').checkboxTree();

  /* Se Capturan los Datos de los TreeView */

      $('#obtrol').click(function(){
      //  var prev = '', prea = '';
        /* Rol Categorias */
        if(($('input:checkbox[id=cat-ver]:checked').val()) == 1){var catv = 1;}else{var catv = 0;}
        if(($('input:checkbox[id=cat-add]:checked').val()) == 1){var cata = 1;}else{var cata = 0;}
        if(($('input:checkbox[id=cat-upd]:checked').val()) == 1){var catu = 1;}else{var catu = 0;}
        if(($('input:checkbox[id=cat-del]:checked').val()) == 1){var cate = 1;}else{var cate = 0;}
        if(($('input:checkbox[id=cat-rep]:checked').val()) == 1){var catr = 1;}else{var catr = 0;}

        $.ajax({
          url: '<?php print $base_url;?>usuarios/usu_rol_add',
          async: false,
          dataType: 'json',
          type: 'POST',
          data: {
            catv : catv,
            cata : cata,
            catu : catu,
            cate : cate,
            catr : catr
          },
          success: function (data) {
            switch (data.resu) {
              case 0:
                alert(data.usu);
              break; 
              case 1:

                $('#datos').text('Ver: '+data.catv+' Agrega: '+data.cata); 

            break;
            }
          }
        });

            
      });

      /* SE ENVIAN AL CONTROLADOR PRODUCTO*/






















  }); 
</script>



   


<script type='text/javascript' language='javascript'>

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-shield"></i> Asignar Roles a <?php if(@$fic_usu != NULL){ print @$fic_usu->nom_usu." ".@$fic_usu->ape_usu; }?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>usuarios">Usuarios</a></li>
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
                    
                    <div class="col-md-4">
                      <ul id="tree2">
                          <li><input type="checkbox"> Categorías
                            <ul>
                                <li><input id="cat-ver" type="checkbox" name="categoria" value="1"> Ver</li>
                                <li><input id="cat-add" type="checkbox" name="categoria" value="1"> Agregar</li>
                                <li><input id="cat-upd" type="checkbox" name="categoria" value="1"> Modificar</li>
                                <li><input id="cat-del" type="checkbox" name="categoria" value="1"> Eliminar</li>
                                <li><input id="cat-rep" type="checkbox" name="categoria" value="1"> Reporte</li>
                            </ul>
                      </ul>
                    </div>

                    <div class="col-md-4">
                      <ul id="tree3">
                        <li><input type="checkbox"> Unidades de Medida
                          <ul>
                            <li><input type="checkbox" name="unidad" value="um-v"> Ver</li>
                            <li><input type="checkbox" name="unidad" value="um-a"> Agregar</li>
                            <li><input type="checkbox" name="unidad" value="um-m"> Modificar</li>
                            <li><input type="checkbox" name="unidad" value="um-e"> Eliminar</li>
                            <li><input type="checkbox" name="unidad" value="um-r"> Reporte</li>
                          </ul>
                      </ul>
                    </div>                    


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

              <div class="col-md-12 text-center">
                <hr>
                <button type="button" id="obtrol" class="btn btn-danger">Obtener Datos</button><br>
                <br>
                <pre style="" id="datos" class="well col-md-4"></pre>                 
              </div>


          </div>
        

           
        </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

                              