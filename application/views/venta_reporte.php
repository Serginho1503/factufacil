<?php 
$nombresistema = $this->session->userdata("nombresistema");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php print $nombresistema; ?> - Reporte de Ventas</title>
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
          <i class="fa fa-list-alt"></i> Reporte de Venta del <?php print substr($desde,0,10); ?> al <?php print substr($hasta,0,10); ?>   - Empresa:  <?php print $objemp->nom_emp; ?>   RUC:  <?php print $objemp->ruc_emp; ?>
          <small class="pull-right"></small>
          <a class="btn btn-success color-palette btn-grad pull-right" target="_blank" href="<?php print $base_url;?>facturar/reporteventaXLS" data-original-title="" title=""><i class="fa fa-file-excel-o fa-1x"></i> Exportar a Excel </a>
        </h2>
      </div>
      <!-- /.col -->
    </div>

<!-- v.id_venta, v.fecha, v.nro_factura, v.mesa, c.nom_cliente, v.montototal -->

    <!-- Table row -->
    <div class="row">
      <!-- <div class="col-md-2"></div> -->
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th class="text-center col-md-1">Fecha</th>  
            <th class="text-center col-md-1">Factura</th>
            <th class="text-center col-md-1">Tipo</th>
            <th class="text-center col-md-1">Pto Venta</th>
            <th>Cliente</th>
            <th>C.I./R.U.C.</th>
            <th class="text-center col-md-1">Monto</th>                            
            <th class="text-center col-md-1">Efectivo</th>
            <th class="text-center col-md-1">Cheque</th>
            <th class="text-center col-md-1">Tar.Credito</th>
            <th class="text-center col-md-1">Tar.Debito</th>
            <th class="text-center col-md-1">Tar.Prepago</th>
            <th class="text-center col-md-1">Transferencia</th>
            <th class="text-center col-md-1">Din.Elect</th>
            <th class="text-center col-md-1">Otros</th>
            <th class="text-center col-md-1">Anticipo</th>
            <th class="text-center col-md-1">Observaciones</th>
          </tr>
          </thead>
          <tbody>
          <?php

            $subtotal = 0;
            $total = 0;
            $efectivo = 0;
            $cheque = 0;
            $tarjetac = 0;
            $tarjetad = 0;
            $tarjetap = 0;
            $transferencia = 0;
            $dinele = 0;
            $otros = 0;
            $anticipo = 0;
            foreach ($venta as $ven) {
              if($ven->estatus != 3){
                $total += $ven->montototal;
                $efectivo += $ven->efectivo;
                $cheque += $ven->cheque;
                $tarjetac += $ven->tarjetac;
                $tarjetad += $ven->tarjetad;
                $tarjetap += $ven->tarjetap;
                $transferencia += $ven->transferencia;
                $dinele += $ven->dinele;
                $otros += $ven->otros;
                $anticipo += $ven->anticipo;
              
          ?>
            <tr <?php if($ven->estatus == 3){ ?> style="background-color: #DD4B39;" <?php } ?> >
              <td><?php @$fec = str_replace('-', '/', $ven->fecharegistro); @$fec = date("d/m/Y  H:i:s", strtotime(@$fec)); print @$fec;  ?></td>
              <td><?php print @$ven->nro_factura; ?></td>
              <td><?php print @$ven->nom_cancelacion; ?></td>
              <td><?php print @$ven->mesa; ?></td>
              <td><?php print @$ven->nom_cliente; ?></td>
              <td><?php print @$ven->nro_ident; ?></td>
              <td>$ <?php print number_format(@$ven->montototal,2,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->efectivo,2,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->cheque,2,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->tarjetac,2,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->tarjetad,2,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->tarjetap,2,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->transferencia,2,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->dinele,2,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->otros,2,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->anticipo,2,",","."); ?></td>
              <td> <?php print utf8_decode(@$ven->observaciones); ?></td>
            </tr>
          <?php 
              }
            }
          ?>

          <tr>
            <th colspan="6" class="text-right">Totales</th>
            <th>$ <?php print number_format($total,2,",","."); ?></th>
            <th>$ <?php print number_format($efectivo,2,",","."); ?></th>
            <th>$ <?php print number_format($cheque,2,",","."); ?></th>
            <th>$ <?php print number_format($tarjetac,2,",","."); ?></th>
            <th>$ <?php print number_format($tarjetad,2,",","."); ?></th>
            <th>$ <?php print number_format($tarjetap,2,",","."); ?></th>
            <th>$ <?php print number_format($transferencia,2,",","."); ?></th>
            <th>$ <?php print number_format($dinele,2,",","."); ?></th>
            <th>$ <?php print number_format($otros,2,",","."); ?></th>
            <th>$ <?php print number_format($anticipo,2,",","."); ?></th>
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
