<?php 
$nombresistema = $this->session->userdata("nombresistema");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php print $nombresistema; ?> - Reporte de Proforma</title>
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
  <style type="text/css">
    .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
      border: 1px solid #ababab;    
    }

    .table-bordered {
        border: 1px solid #ababab;
    }    
  </style>
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
          <?php @$fecd = str_replace('-', '/', $desde); @$fecd = date("d/m/Y  H:i:s", strtotime(@$fecd)); ?>
          <?php @$fech = str_replace('-', '/', $hasta); @$fech = date("d/m/Y  H:i:s", strtotime(@$fech)); ?>
          <i class="fa fa-list-alt"></i> Reporte de Proforma del <?php print substr($fecd,0,10); ?> al <?php print substr($fech,0,10); ?>
          <small class="pull-right"></small>
          
        </h2>
      </div>
      <!-- /.col -->
    </div>

<!-- v.id_venta, v.fecha, v.nro_factura, v.mesa, c.nom_cliente, v.montototal -->

    <!-- Table row -->
    <div class="row">
      <!-- <div class="col-md-2"></div> -->
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped table-bordered">
          <thead>
          <tr>
            <th class="text-center col-md-1">Fecha Proforma</th>  
            <th class="text-center col-md-1">Nro Proforma</th>
            <th>Cliente</th>
            <th class="text-center">C.I./R.U.C.</th>
            <th class="text-center col-md-1">Monto</th> 
            <th>Vendedor</th>
            <th class="text-center col-md-1">Tipo</th>
            <th class="text-center col-md-1">Fecha Venta</th>             
            <th class="text-center col-md-1">Nro Factura</th>
          </tr>
          </thead>
          <tbody>
          <?php
            foreach ($proforma as $p) {
          ?>
            <tr>
              <td><?php @$fec = str_replace('-', '/', $p->fecharegistro); @$fec = date("d/m/Y  H:i:s", strtotime(@$fec)); print @$fec;  ?></td>
              <td class="text-center"><?php print @$p->nro_proforma; ?></td>
              <td><?php print @$p->nom_cliente; ?></td>
              <td class="text-right"><?php print @$p->ident_cliente; ?></td>
              <td class="text-right">$ <?php print number_format(@$p->montototal,2,",","."); ?></td>
              <td><?php print @$p->vendedor; ?></td>
              <td class="text-center"><?php print @$p->categoria; ?></td>
              <td><?php if($p->fecventa != NULL) { @$fech = str_replace('-', '/', $p->fecventa); @$fech = date("d/m/Y  H:i:s", strtotime(@$fech)); print @$fech; } ?></td>              
              <td class="text-center"><?php print @$p->nro_factura; ?></td>
            </tr>
          <?php 
            }
          ?>
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
