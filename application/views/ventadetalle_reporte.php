<?php 
$nombresistema = $this->session->userdata("nombresistema");
?>
<style type="text/css">


</style>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php print $nombresistema; ?> - Reporte de Detalles de Ventas</title>
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
        <h4 class="page-header">
          <i class="fa fa-list-alt"></i> Reporte de Detalles de Venta del <?php print substr($desde,0,10); ?> al <?php print substr($hasta,0,10); ?>   - Empresa:  <?php print $objemp->nom_emp; ?>    RUC:  <?php print $objemp->ruc_emp; ?>
          <small class="pull-right"></small>
          <a class="btn btn-success color-palette btn-grad pull-right" target="_blank" href="<?php print $base_url;?>facturar/reporteventadetalleXLS" data-original-title="" title=""><i class="fa fa-file-excel-o fa-1x"></i> Exportar a Excel </a>
        </h4>
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
            <th class="text-center col-md-1">C.I./R.U.C</th>  
            <th class="text-center col-md-1">Cliente</th>  
            <th class="text-center col-md-1">Placa</th>  
            <th class="text-center col-md-1">Factura</th>
            <th class="text-center col-md-1">Producto</th>
            <th class="text-center col-md-1">Cantidad</th>
            <th class="text-center col-md-1">Uni.Med.</th>
            <th class="text-center col-md-1">Precio Unit</th>                            
            <th class="text-center col-md-1">Base Imp.</th>
            <th class="text-center col-md-1">Impuesto</th>
            <th class="text-center col-md-1">Valor Total</th>
            <th class="text-center col-md-1">Precio s/Subsidio</th>
            <th class="text-center col-md-1">Total s/Subsidio</th>
            <th class="text-center col-md-1">Ahorro p/Subsidio</th>
          </tr>
          </thead>
          <tbody>
          <?php

            $montoiva = 0;
            $valortotal = 0;
            $totalsinsubsidio = 0;
            $ahorroporsubsidio = 0;

            foreach ($venta as $ven) {
              if($ven->estatus != 3){
                $montoiva += $ven->montoiva;
                $valortotal += $ven->valortotal;
                $totalsinsubsidio += $ven->valorsinsubsidio;
                $ahorroporsubsidio += $ven->ahorroporsubsidio;
              
          ?>
            <tr <?php if($ven->estatus == 3){ ?> style="background-color: #DD4B39;" <?php } ?> >
              <td><?php @$fec = str_replace('-', '/', $ven->fecharegistro); @$fec = date("d/m/Y  H:i:s", strtotime(@$fec)); print @$fec;  ?></td>
              <td><?php print @$ven->nro_ident; ?></td>
              <td><?php print @$ven->nom_cliente; ?></td>
              <td><?php print @$ven->placa_matricula; ?></td>
              <td><?php print @$ven->nro_factura; ?></td>
              <td><?php print @$ven->descripcion; ?></td>
              <td> <?php print number_format(@$ven->cantidad,2,",","."); ?></td>
              <td><?php print @$ven->unidadmedida; ?></td>
              <td>$ <?php print number_format(@$ven->precio,6,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->descsubtotal,2,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->montoiva,2,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->valortotal,2,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->preciosinsubsidio,6,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->valorsinsubsidio,2,",","."); ?></td>
              <td>$ <?php print number_format(@$ven->ahorroporsubsidio,2,",","."); ?></td>
            </tr>
          <?php 
              }
            }
          ?>

          <tr>
            <th colspan="10" class="text-right">Totales</th>
            <th>$ <?php print number_format($montoiva,2,",","."); ?></th>
            <th>$ <?php print number_format($valortotal,2,",","."); ?></th>
            <th></th>
            <th>$ <?php print number_format($totalsinsubsidio,2,",","."); ?></th>
            <th>$ <?php print number_format($ahorroporsubsidio,2,",","."); ?></th>
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
