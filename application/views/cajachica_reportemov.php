<?php

$nombresistema = $this->session->userdata("nombresistema");

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php print $nombresistema; ?> - Reporte de Movimientos de Caja Chica </title>
  <!-- Tell the browser to be responsive to screen width -->
  <link rel="shortcut icon" type="image/ico" href="<?php print $base_url; ?>public/img/log_ff_mod_web.png" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

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

  <?php // <!-- ESTILOS PERSONALIZADOS DEL DESARROLLADOR --> ?>
  <link rel="stylesheet" href="<?php print $base_url; ?>public/css/estilo.css" />
  <?php // <!-- FIN DE ESTILOS PERSONALIZADOS DEL DESARROLLADOR --> ?>
  <?php // <!-- ESTILOS DE VALIDACION --> ?>
  <link rel="stylesheet" href="<?php print $base_url; ?>assets/plugins/validationengine/css/validationEngine.jquery.css" />
  <?php // <!-- FIN DE ESTILOS DE VALIDACION --> ?>

  <?php // <!-- REMODAL --> ?>        
  <link rel="stylesheet" href="<?php  print $base_url; ?>assets/plugins/jQueryUI/jquery-ui.css" />
  <?php 
    date_default_timezone_set("America/Guayaquil");
  ?>

</head>
<!--<body onload="window.print();">-->
<body>

<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <i class="fa fa-list-alt"></i> Reporte de Movimientos de Caja Chica (<?php print @$cajachica->fechaapertura; ?> - <?php print @$cajachica->fechacierre; ?>)   Saldo Inicial: <?php print number_format(@$cajachica->montoapertura,2,",","."); ?>
          <small class="pull-right"></small>
          <a class="btn btn-success color-palette btn-grad pull-right" target="_blank" href="<?php print $base_url;?>Cajachica/reportemovimientoXLS" data-original-title="" title=""><i class="fa fa-file-excel-o fa-1x"></i> Exportar a Excel </a>
        </h2>
      </div>
      <!-- /.col -->
    </div>

    <!-- Table row -->
    <div class="row">
      <!-- <div class="col-md-2"></div> -->
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Fecha</th>
            <th>#Documento</th>
            <th>Tipo</th>
            <th>Valor</th>
            <th>Descripcion</th>
          </tr>
          </thead>
          <tbody>
          <?php

            $total = 0;
            if (@$cajachica->montoapertura != '') { $total = $cajachica->montoapertura;}
            foreach ($reporte as $mov) {
              if ($mov->tipo == 'Ingreso'){
                $total = $total  + $mov->valor;
              } else {
                $total = $total  - $mov->valor;
              }
          ?>
            <tr>
              <td><?php @$fec = str_replace('-', '/', @$mov->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;  ?></td>
              <td><?php print @$mov->numerodoc; ?></td>
              <td><?php print @$mov->tipo; ?></td>
              <td>$ <?php print number_format(@$mov->valor,2,",","."); ?></td>
              <td><?php print @$mov->descripcion; ?></td>
            </tr>
          <?php 
            }
          ?>
          <tr>
            <th colspan="4" class="text-right">Saldo Final </th>
            <th>$ <?php print number_format($total,2,",","."); ?></th>
          </tr>


          </tbody>
        </table>
      </div>
      <!-- <div class="col-md-2"></div> -->
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
