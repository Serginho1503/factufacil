<?php
/* ------------------------------------------------
  ARCHIVO: Inicio.php
  DESCRIPCION: Vista de la página de inicio de la aplicación.
  FECHA DE CREACIÓN: 30/06/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página

  $sistema = &get_instance();
  $sistema->load->model("Sistema_model");
  $imagenfondo = $sistema->Sistema_model->sel_imagenfondo();
  $nombresistema = $sistema->Sistema_model->sel_nombresistema();
    

print "<script>document.title = '$nombresistema - Inicio' </script>";

/* Consulta para mostrar la foto del Usuario */
  $usu_mod = &get_instance();
  $usu_mod->load->model("usuario_model");
  $usua = $this->session->userdata('usua');
  $id = $usua->id_usu;
  $perfil = $usua->perfil;
  $fic_fot = $usu_mod->usuario_model->usu_get_fot($id);
  /* Consulta Accesos al menu */
  $rol_mod = &get_instance();
  $rol_mod->load->model("rol_model");
  $rolacces = $rol_mod->rol_model->rol_modulo($id);

  foreach ($rolacces as $r){
    $mod = $r->desc_mod_det;

    switch ($mod){
      case "Categorias": $cat_ver = $r->accion;

    }

  }
  $caja = &get_instance();
  $caja->load->model("Cajaapertura_model");
  $caja_verif = $caja->Cajaapertura_model->existeapertura();

  $cajachica = &get_instance();
  $cajachica->load->model("Cajachica_model");
  $cajachica_verif = $cajachica->Cajachica_model->existeapertura();

  $parametro = &get_instance();
  $parametro->load->model("Parametros_model");
  $pedidovista = $parametro->Parametros_model->sel_pedidovista();
  $parametro->load->model("Parametros_model");
  $tp = $parametro->Parametros_model->tipo_precio();

  $habserie = $parametro->Parametros_model->sel_numeroserie();
  $habilitaserie = $habserie->valor;

  $servicio = &get_instance();
  $servicio->load->model("Serviciotecnico_model");
  $serviciotecnico = $servicio->Serviciotecnico_model->lst_configservicio();

  $habilitapetshop = $parametro->Parametros_model->sel_habilitapetshop();

  $parametro->load->model("Sistema_model");
  $sistema = $parametro->Sistema_model->sel_sistema();
  $iconopedido = $sistema->icon_pedido;


?>
<style type="text/css">
    .img_res{
        background:url("<?php print $imagenfondo; ?>");
        background-size: cover;       /* For flexibility */
    }
</style>
  <div class="content-wrapper">
    <section class="content-header">
        <h1>
            Inicio
            <small>Página Principal del Sistema</small>
            <!--<marquee>Estimado Cliente, le informamos que su suscripción anual empezará el día 1 de marzo de 2024 y finalizará el 01 de marzo de 2025, por favor comuníquese con el administrador del Sistema. Cel. 0987061839. ¡Muchas Gracias!</marquee>-->
        </h1>
        <h1>
           <small>Suscripción válida hasta el 01/07/2026</small>
            <!--<marquee>Estimado Cliente, le informamos que su suscripción anual caduca el día 2 de Noviembre, por favor comuníquese con el administrador del Sistema. Cel. 0987061839. ¡Muchas Gracias!</marquee>-->
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-home"></i> Inicio</a></li>
            <!--        <li class="active">Página Principal del Sistema</li>-->
        </ol>
    </section>
    <!-- Main content -->
    <!-- <section class="content responsive img_res animated fadeIn" style="height: 200px">
    </section> -->

    <section class="content responsive animated fadeIn" style="height: 1024px width: 768px">

      <?php if($perfil == 1  || $perfil == 5) { ?>
      <a title="Apertura de Caja" href="#" class="abrircajaefectivo">
      <img onmouseover="this.src='public/img/iconos/cajab.png'" onmouseout="this.src='public/img/iconos/cajaw.png'" src="public/img/iconos/cajaw.png" alt="Apertura de Caja" width="128" height="128" hspace="13" vspace="10"></a>
      <?php } ?>
      
<!--
      <?php if($perfil == 1  || $perfil == 5) { ?>
      <a title="Orde de Servicio" href="<?php print $base_url ?>serviciotecnico">
      <img onmouseover="this.src='public/img/iconos/satb.png'" onmouseout="this.src='public/img/iconos/satw.png'"  src="public/img/iconos/satw.png" alt="Orden de Servicio" width="128" height="128" hspace="10" vspace="10"></a>
      <?php } ?>
-->

      <?php if($perfil == 1  || $perfil == 5) { ?>
      <a title="Venta Directa" href="#" class="facturar">
      <img onmouseover="this.src='public/img/iconos/facturarb.png'" onmouseout="this.src='public/img/iconos/facturarw.png'"  src="public/img/iconos/facturarw.png" alt="Venta Directa" width="128" height="128"hspace="13" vspace="10"></a>
      <?php } ?>

      <?php if($perfil == 1  || $perfil == 5) { ?>
      <a title="Proforma/Cotización" href="<?php print $base_url ?>proforma">
      <img onmouseover="this.src='public/img/iconos/proformab.png'" onmouseout="this.src='public/img/iconos/proformaw.png'"  src="public/img/iconos/proformaw.png" alt="Proforma/Cotizacion" width="128" height="128"hspace="10" vspace="10"></a>
      <?php } ?>

      <?php if($perfil == 1  || $perfil == 5) { ?>
			<a title="Listado de Ventas" href="<?php print $base_url ?>facturar/ventas">
      <img onmouseover="this.src='public/img/iconos/reporteventasb.png'" onmouseout="this.src='public/img/iconos/reporteventasw.png'"  src="public/img/iconos/reporteventasw.png" alt="Listado de Ventas" width="128" height="128" hspace="13" vspace="10"></a>
      <?php } ?>

      <?php if($perfil == 1  || $perfil == 5) { ?>
      <a title="Movimientos de Caja" href="<?php print $base_url ?>cajamov">
      <img onmouseover="this.src='public/img/iconos/movcajab.png'" onmouseout="this.src='public/img/iconos/movcajaw.png'"  src="public/img/iconos/movcajaw.png" alt="Movimientos de Caja" width="128" height="128" hspace="10" vspace="10"></a>
      <?php } ?>

      <?php if($perfil == 1  || $perfil == 5) { ?>
      <a title="Cerrar Caja" href="#" class="cerrarcajaefectivo">
      <img onmouseover="this.src='public/img/iconos/cerrarcajab.png'" onmouseout="this.src='public/img/iconos/cerrarcajaw.png'"  src="public/img/iconos/cerrarcajaw.png" alt="Cerrar Caja" width="128" height="128" hspace="13" vspace="10"></a>
      <?php } ?>

     <!-- <?php if($perfil == 1  || $perfil == 2) { ?>
			<a title="Aperturar Caja Chica" href="#" class="abrircajachica">
			<img src="public/img/iconos/apertura_cajachica.png" alt="Aperturar Caja Chica" width="128" height="128" hspace="10" vspace="10"></a>
      <?php } ?>-->

      <?php if($perfil == 1  || $perfil == 2) { ?>
      <a title="Proveedores" href="<?php print $base_url ?>proveedor">
      <img onmouseover="this.src='public/img/iconos/proveedoresb.png'" onmouseout="this.src='public/img/iconos/proveedoresw.png'"   src="public/img/iconos/proveedoresw.png" alt="Proveedores" width="128" height="128"hspace="10" vspace="10"></a>
      <?php } ?>      

      <?php if($perfil == 1  || $perfil == 2) { ?>
      <a title="Compras" href="<?php print $base_url ?>compra">
      <img onmouseover="this.src='public/img/iconos/comprasb.png'" onmouseout="this.src='public/img/iconos/comprasw.png'"   src="public/img/iconos/comprasw.png" alt="Compras" width="128" height="130"hspace="13" vspace="10"></a>
      <?php } ?>

      <?php if($perfil == 1  || $perfil == 2) { ?>
      <a title="Cuentas por Pagar" href="<?php print $base_url ?>creditocompra">
      <img onmouseover="this.src='public/img/iconos/ctasxpagarb.png'" onmouseout="this.src='public/img/iconos/ctasxpagarw.png'"   src="public/img/iconos/ctasxpagarw.png" alt="Cuentas por Pagar" width="128" height="128"hspace="10" vspace="10"></a>
      <?php } ?>

      <!--<?php if($perfil == 1  || $perfil == 2) { ?>
      <a title="Gastos" href="<?php print $base_url ?>gastos">
      <img src="public/img/iconos/gastos_cajachica.png" alt="Gastos" width="128" height="128"hspace="13" vspace="10"></a>
      <?php } ?> -->

      <!--<?php if($perfil == 1  || $perfil == 2) { ?>
      <a title="Movimientos Caja Chica" href="Proforma/agregar">
      <img src="public/img/iconos/movimientos_cajachica.png" alt="Movimientos Caja Chica" width="128" height="128"hspace="13" vspace="10"></a>
      <?php } ?> -->

      <!--<?php if($perfil == 1  || $perfil == 2) { ?>
      <a title="Cerrar Caja Chica" href="#" class="cerrarcajachica">
      <img src="public/img/iconos/cerrar_cajachica.png" alt="Cerrar Caja Chica" width="128" height="128"hspace="10" vspace="10"></a>
      <?php } ?> -->

      <?php if($perfil == 1  || $perfil == 5) { ?>
      <a title="Cuentas por Cobrar" href="<?php print $base_url ?>credito">
      <img onmouseover="this.src='public/img/iconos/ctasxcobrarb.png'" onmouseout="this.src='public/img/iconos/ctasxcobrarw.png'"   src="public/img/iconos/ctasxcobrarw.png" alt="Cuentas por Cobrar" width="128" height="128"hspace="13" vspace="10"></a>
      <?php } ?>

      <?php if($perfil == 1  || $perfil == 5) { ?>
      <a title="Clientes" href="<?php print $base_url ?>cliente">
      <img onmouseover="this.src='public/img/iconos/clientesb.png'" onmouseout="this.src='public/img/iconos/clientesw.png'"   src="public/img/iconos/clientesw.png" alt="Clientes" width="128" height="128"hspace="10" vspace="10"></a>
      <?php } ?>

      <?php if($perfil == 1  || $perfil == 2) { ?>
      <a title="Verificar Producto" href="<?php print $base_url ?>producto">
      <img onmouseover="this.src='public/img/iconos/productosb.png'" onmouseout="this.src='public/img/iconos/productosw.png'"   src="public/img/iconos/productosw.png" alt="Verificar Productos" width="128" height="128"hspace="13" vspace="10"></a>
      <?php } ?>
      
      <?php if($perfil == 5) { ?>
      <a title="Verificar Producto" href="<?php print $base_url ?>Inventario/ajuste">
      <img onmouseover="this.src='public/img/iconos/productosb.png'" onmouseout="this.src='public/img/iconos/productosw.png'"   src="public/img/iconos/productosw.png" alt="Verificar Productos" width="128" height="128"hspace="13" vspace="10"></a>
      <?php } ?>

      <?php if($perfil == 1  || $perfil == 5) { ?>
      <a title="Movimientos de Inventario" href="<?php print $base_url ?>Inventario/cargar_inventariomovimiento">
      <img onmouseover="this.src='public/img/iconos/movimientob.png'" onmouseout="this.src='public/img/iconos/movimientow.png'"   src="public/img/iconos/movimientow.png" alt="Movimientos de Inventario" width="128" height="128"hspace="10" vspace="10"></a>
      <?php } ?>

      <?php if($perfil == 1  || $perfil == 2) { ?>
      <a title="Ajuste de Existencia" href="<?php print $base_url ?>Inventario/ajuste">
      <img onmouseover="this.src='public/img/iconos/inventariob.png'" onmouseout="this.src='public/img/iconos/inventariow.png'"   src="public/img/iconos/inventariow.png" alt="Ajuste de Existencia" width="128" height="128"hspace="10" vspace="10"></a>
      <?php } ?>

      <?php if($perfil == 1  || $perfil == 2) { ?>
      <a title="Ajuste de Precios" href="<?php print $base_url ?>producto/proajuste">
      <img onmouseover="this.src='public/img/iconos/preciosb.png'" onmouseout="this.src='public/img/iconos/preciosw.png'"   src="public/img/iconos/preciosw.png" alt="Ajuste de Precios" width="128" height="128"hspace="13" vspace="10"></a>
      <?php } ?>

      <!--<?php if($perfil == 1  || $perfil == 2) { ?>
      <a title="Egresos Diarios" href="#" class="addegreso">
      <img src="public/img/iconos/gastos.png" alt="Egresos Diarios" width="128" height="128"hspace="10" vspace="10"></a>
      <?php } ?>      


		</section>

  </div>