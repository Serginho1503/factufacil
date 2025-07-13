<?php 
$nombresistema = $this->session->userdata("nombresistema");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php print $nombresistema; ?> - Reporte de Gastos</title>
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
          <i class="fa fa-list-alt"></i> Reporte de Gastos <?php //print date("d/m/Y"); ?>
          
          <a class="btn bg-orange-active color-palette btn-grad pull-right" target="_blank" href="<?php print $base_url;?>facturar/reportegastosXLS" data-original-title="" title=""><i class="fa fa-shopping-bag"></i> Exportar a Excel </a>
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
            <th>Proveedor</th>
            <th>Factura</th>
            <th>Descripcion</th> 
            <th>Categoría</th>
            <th>Estado</th>            
            <th>Subtotal</th>

          </tr>
          </thead>
          <tbody>
          <?php 
            $total = 0;
            foreach ($gasto as $co) {
              if($co->estatus != 3){
                if ($co->cod_sri_tipo_doc != '04')
                  $total = $total  + $co->total;
                else
                  $total = $total  - $co->total;

              }
              
          ?>
            <tr>
              <td><?php @$fec = str_replace('-', '/', @$co->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;  ?></td>
              <td><?php print @$co->nom_proveedor; ?></td>
              <td><?php print @$co->nro_factura; ?></td>
              <td><?php print @$co->descripcion; ?></td> 
              <td><?php print @$co->categoria; ?></td>
              <td><?php print @$co->desc_estatus; ?></td>              
              <td>$ <?php if ($co->cod_sri_tipo_doc == '04') {print "-";} ?> <?php print number_format(@$co->total,2,",","."); ?></td>

            </tr>
          <?php 
            }
          ?>
          <tr>
            <th colspan="6" class="text-right">Total</th>
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
