<?php 
$nombresistema = $this->session->userdata("nombresistema");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php print $nombresistema; ?> - Reporte de Productos</title>
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
          <i class="fa fa-file-text-o" aria-hidden="true"></i> Reporte de Productos <?php print date("d/m/Y"); ?>
          <a style="margin-bottom: 30px;" class="btn btn-success pull-right" target="_blank" href="<?php print $base_url;?>Producto/reporteproXLS" data-original-title="" title=""><i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar a Excel </a>
        </h2>
      </div>
      <!-- /.col -->
    </div>

    <!-- Table row -->
    <div class="row">
      <!-- <div class="col-md-2"></div> -->
      <div class="col-xs-12 table-responsive">
        <table class="table table-bordered">
          <thead>
          <tr>
            <th class="text-center">Id</th>
            <th class="text-center">Codigo Barra</th>
            <th class="text-center">Codigo Auxiliar</th>
            <th>Nombre</th> 
            <th>Descripcion</th>
            <th class="text-center">Precio Compra</th>            
            <th class="text-center">Precio Venta</th>
            <?php 
            foreach ($precio as $pre) { ?>
              <th class="text-center"><?php print $pre->desc_precios; ?></th>
            <?php   
            }
            ?>
            <th class="text-center">Existencia</th>
            <th class="text-center">Categoría</th>
            <th class="text-center">Estatus</th>
            <th class="text-center">Unidad</th>
            <th class="text-center">Clasificación</th>
            <th class="text-center">Comanda</th>
            <th class="text-center">IVA</th>
            <th class="text-center">Preparado</th>
            <th class="text-center">Ingrediente</th>
          </tr>
          </thead>
          <tbody>
          
          <?php 
            foreach ($pro as $pro) {
          ?>
            <tr>
              <td class="text-center"><?php print @$pro->pro_id;?></td>
              <td class="text-center"><?php print @$pro->pro_codigobarra; ?></td>
              <td class="text-center"><?php print @$pro->pro_codigoauxiliar; ?></td>
              <td><?php print @$pro->pro_nombre; ?></td> 
              <td><?php print @$pro->pro_descripcion; ?></td>
              <td class="text-right">$ <?php print number_format(@$pro->pro_preciocompra,2,",","."); ?></td>             
              <td class="text-right">$ <?php print number_format(@$pro->pro_precioventa,2,",","."); ?></td>
              <?php 
              foreach ($precio as $pr) { 
                foreach ($lstprecio as $lp) {
                  if($pr->id_precios == $lp->id_precios && $lp->pro_id == $pro->pro_id){ ?>
                    <td class="text-center"><?php print @$lp->monto; ?></td>
                  <?php 
                  }
                }
              }
              ?>
              <td class="text-right"> <?php print number_format(@$pro->existencia,2,",","."); ?></td>
              <td class="text-center"><?php print @$pro->cat_descripcion; ?></td>
              <td class="text-center"><?php print @$pro->pro_estatus; ?></td>
              <td class="text-center"><?php print @$pro->descripcion; ?></td>
              <td class="text-center"><?php print @$pro->nom_cla; ?></td>
              <td class="text-center"><?php print @$pro->nom_comanda; ?></td>
              <td class="text-center"><?php print @$pro->iva; ?></td>
              <td class="text-center"><?php print @$pro->preparado; ?></td>
              <td class="text-center"><?php print @$pro->ingrediente; ?></td>

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