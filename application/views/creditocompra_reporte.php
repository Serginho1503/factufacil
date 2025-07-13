<?php 
$nombresistema = $this->session->userdata("nombresistema");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php print $nombresistema; ?> - Reporte de Cuentas por Pagar</title>
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

</head>
<!-- <body onload="window.print();">
 --><!--<body>-->
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <i class="fa fa-list-alt"></i> Reporte de Cuentas por Pagar (<?php print $nomproveedor; ?>  -  <?php print $nomestado; ?>)

          <a class="btn btn-success color-palette btn-grad pull-right" target="_blank" href="<?php print $base_url;?>creditocompra/reportecreditoXLS" data-original-title="" title=""><i class="fa fa-file-excel-o fa-1x"></i> Exportar a Excel </a>
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
            <th>proveedor</th>
            <th>Factura</th>
            <th>Fecha Plazo</th>
            <th>Dias Plazo</th>
            <th>Estado</th>
            <th>Monto Factura</th>
            <th>Pendiente</th>
          </tr>
          </thead>
          <tbody>
          <?php 
            $total = 0;
            foreach ($credito as $co) {
              $total = $total  + $co->montototal - $co->abonado;
              
          ?>
            <tr>
              <td><?php @$fec = str_replace('-', '/', @$co->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;  ?></td>
              <td><?php print @$co->nom_proveedor; ?></td>
              <td><?php print @$co->nro_factura; ?></td>
              <td><?php print @$co->fechalimite; ?></td>
              <td><?php print @$co->dias; ?></td>
              <td><?php print @$co->nombre_estado; ?></td>
              <td>$ <?php print number_format(@$co->montototal,2,",","."); ?></td>
              <td>$ <?php print number_format(@($co->montototal - $co->abonado),2,",","."); ?></td>
            </tr>
          <?php 
            }
          ?>
          <tr>
            <th colspan="7" class="text-right">Total</th>
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
