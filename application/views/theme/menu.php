<?php
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

<script>
  $( document ).ready(function() {

    $(document).on('click', '.abrircajaefectivo', function(){
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Cajaapertura/existecajaefectivo_noabierta');?>",
        success: function(json) {
          if (json.resu > 0){
            location.replace("<?php print $base_url ?>cajaapertura");
          } else {
            swal("La Caja se encuentra aperturada", "Caso contrario el Usuario no tiene Caja asignada", "info");
            return false;
          }
        }
      });
    });  

    $(document).on('click', '.cerrarcajaefectivo', function(){
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Cajacierre/existecajaefectivo_abierta');?>",
        success: function(json) {
          if (json.resu > 0){
            location.replace("<?php print $base_url ?>cajacierre");
          } else {
            swal({
            title: "No existen Cajas Aperturadas",
            text: "Desea aperturar la Caja Ahora?",
            type: "info",
            showCancelButton: true,
            confirmButtonClass: "btn-info",
            confirmButtonText: "Si, Aperturar Ahora!",
            closeOnConfirm: false
                  },
            function(){
            location.replace("<?php print $base_url ?>cajaapertura");
            });
            return false;
          }
        }
      });
    });  

    $(document).on('click', '.facturar', function(){
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Cajacierre/existecajaefectivo_abierta');?>",
        //url: "<?php echo base_url('Facturar/nuevo');?>",
        success: function(json) {
          if (json.resu > 0){
            location.replace("<?php print $base_url ?>facturar/factura_deposito");
          } else {
                        swal({
            title: "No existen Cajas Aperturadas",
            text: "Desea aperturar la Caja Ahora?",
            type: "info",
            showCancelButton: true,
            confirmButtonClass: "btn-info",
            confirmButtonText: "Si, Aperturar Ahora!",
            closeOnConfirm: false
                  },
            function(){
            location.replace("<?php print $base_url ?>cajaapertura");
            });
            return false;
          }
        }
      });
    });  

    $(document).on('click', '.abrircajachica', function(){
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Cajachica/existecajachica_noabierta');?>",
        success: function(json) {
          if (json.resu > 0){
            location.replace("<?php print $base_url ?>cajachica/mostrarapertura");
          } else {
            swal("No existen cajas disponibles para la apertura.");
            return false;
          }
        }
      });
    });  

    $(document).on('click', '.cerrarcajachica', function(){
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Cajachica/existecajachica_abierta');?>",
        success: function(json) {
          if (json.resu > 0){
            location.replace("<?php print $base_url ?>cajachica/cierre");
          } else {
            swal("No existen cajas abiertas.");
            return false;
          }
        }
      });
    });  

  });

</script>

  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img class="img-rounded" <?php
            if (@$fic_fot != NULL) {
              if ($fic_fot->fot_usu) { print " src='data:image/jpeg;base64,$fic_fot->fot_usu'"; } 
              else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } } 
            else { ?> src="<?php print base_url(); ?>public/img/perfil.jpg" <?php } ?> 
            alt="" onerror="this.src='<?php print base_url() . "public/img/perfil.jpg"; ?>';" />
        </div>
        <div class="pull-left info">
          <p><?php  print $this->session->userdata("sess_na"); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> EN LINEA </a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Menú Principal</li>
        
        <li class="<?php  if ($content == 'inicio') { print 'active';} ?> ">
          <a href="<?php   print $base_url ?>inicio"><i class="fa fa-home"></i> <span>Inicio</span></a>
        </li> 

        <li class="treeview">
          <a href="#">
            <i class="fa fa-th"></i> <span>Transacciones</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="treeview">
              <a href="#"><i class="fa fa-newspaper-o"></i> Ventas
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if($perfil == 1  || $perfil == 3 || $perfil == 5) { ?>
                <li>
                  <a href="#" class="facturar"><i class="fa fa-newspaper-o"></i> Facturar</a>
               <!--   <a href="<?php print $base_url ?>facturar/factura_depositos"><i class="fa fa-newspaper-o"></i> Facturar</a> -->
               </li>
                <!--<?php }   ?>
                <?php if(@$pedidovista->valor == 1){ ?>
                  <li><a href="<?php print $base_url ?>pedido"><i class="fa <?php print @$iconopedido; ?>"></i> Pedidos</a></li> -->
                <?php }   ?>
                <li><a href="<?php print $base_url ?>proforma"><i class="fa fa-file-powerpoint-o" aria-hidden="true"></i> Proformas</a></li>
                  <li><a href="<?php print $base_url ?>facturar/ventas"><i class="fa fa-list-alt" aria-hidden="true"></i> Listado de Ventas</a></li>
                  <?php if($perfil == 1 || $perfil == 3) { ?>
                  <li><a href="<?php print $base_url ?>credito"><i class="fa fa-credit-card" aria-hidden="true"></i> Créditos</a></li>
                  <li><a href="<?php print $base_url ?>notacredito"><i class="fa fa-undo" aria-hidden="true"></i> Notas de Credito</a></li>
                  <li><a href="<?php print $base_url ?>guiaremision"><i class="fa fa-truck" aria-hidden="true"></i> Guías de Remisión</a></li> 
                  <li><a href="<?php print $base_url ?>facturar/ventas_analisis"><i class="fa fa-bar-chart" aria-hidden="true"></i> Análisis</a></li> 
                <?php }   ?>
                
              </ul>  
            </li>           

            <?php if($perfil == 1  || $perfil == 3 || $perfil == 5) { ?>
            <li class="treeview"><a href="#">
                  <i class="fa fa-money"></i> <span>Caja de Efectivo</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">

                  <li><a href="#" class="abrircajaefectivo"><i class="fa fa-unlock"></i> Apertura de Caja </a></li>

                  <li><a href="#" class="cerrarcajaefectivo"><i class="fa fa-lock"></i> Cierre de Caja </a></li>
                  
                  <li><a href="<?php print $base_url ?>cajamov"><i class="fa fa-exchange"></i> Movimiento de Caja </a></li>
                </ul>
            </li>
            <?php } ?> 

            <?php if($perfil == 1) { ?>
            <li class="treeview">
              <a href="#"><i class="fa fa-shopping-cart"></i> Compras
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">

                <li><a href="<?php print $base_url ?>compra"><i class="fa fa-shopping-cart"></i> Compras</a></li>
                <li><a href="<?php print $base_url ?>creditocompra"><i class="fa fa-bomb"></i> Cuentas por Pagar</a></li>
                <li><a href="<?php print $base_url ?>gastos"><i class="fa fa-money"></i> Gastos</a></li>
                <li><a href="<?php print $base_url ?>catgastos"><i class="fa fa-list" aria-hidden="true"></i> Categoria - Gastos</a></li>
              </ul>  
            </li>           
            <?php }   ?>
            
<!--

            <?php if((@$serviciotecnico->habilita_servicio == 1) && ($perfil == 1 || $perfil == 3)){ ?>
              <li><a href="<?php print $base_url ?>serviciotecnico"><i class="fa fa-wrench"></i> Servicio Tecnicos</a></li> 
            <?php }   ?>
-->

            <?php if($perfil == 1  || $perfil == 3) { ?>
            <li class="treeview">
              <a href="#"><i class="fa fa-money"></i> Caja Chica
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#" class="abrircajachica"><i class="fa fa-unlock"></i> Apertura</a></li>
                <li><a href="#" class="cerrarcajachica"><i class="fa fa-lock"></i> Cierre</a></li> 
                <li><a href="<?php print $base_url ?>cajachica/cargaringresocaja"><i class="fa fa-plus-circle"></i> Ingresos</a></li>
                <li><a href="<?php print $base_url ?>cajachica"><i class="fa fa-exchange"></i> Movimientos</a></li>                        
              </ul>
            </li>           
            <?php  }   ?>

            <?php if($perfil == 1  || $perfil == 3 || $perfil == 5) { ?>
              <li><a href="<?php print $base_url ?>infosri"><i class="fa fa-send"></i> Comprobantes Electrónicos</a></li> 
            <?php  }   ?>

            <?php if(($habilitaserie == 1) && ($perfil == 1  || $perfil == 3)) { ?>
            <li class="treeview"><a href="#">
                  <i class="fa fa-money"></i> <span>Garantía</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">

                  <li><a href="<?php print $base_url ?>garantia/productosgarantia"><i class="fa fa-fort-awesome"></i> Productos en Garantía </a></li>

                  <li><a href="<?php print $base_url ?>garantia"><i class="fa fa-star-half-empty" aria-hidden="true"></i> Devoluciones</a></li> 

                  <li><a href="<?php print $base_url ?>clausula"><i class="fa fa-legal"></i> Cláusulas de Garantía</a></li>

                </ul>
            </li>
            <?php } ?> 

          </ul>
        </li> 
        <li class="treeview">
          <a href="#">
            <i class="fa fa-cubes" aria-hidden="true"></i> <span>Inventario</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if($perfil == 1) { ?>            
            <li><a href="<?php print $base_url ?>almacen"><i class="fa fa-cubes"></i> Almacenes</a></li>
            <?php } ?>
            <?php if(($perfil == 3) || ($perfil == 4)  || ($perfil == 5)) { ?>
            <li><a href="<?php print $base_url ?>Inventario/ajuste"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Ver Productos </a></li>
            <?php } ?>
            <?php if($perfil == 1) { ?>
            <li><a href="<?php print $base_url ?>producto"><i class="fa fa-shopping-bag"></i> Productos</a></li>
            <?php } ?>            
            <?php if($perfil == 1) { ?>
            <li><a href="<?php print $base_url ?>clasificacion"><i class="fa fa-hand-o-right"></i> Clasificación</a></li>
            <?php } ?> 
            <?php if($perfil == 1) { ?>
            <li><a href="<?php print $base_url ?>categoria"><i class="fa fa-sort-amount-asc"></i> Categorías</a></li>
            <?php } ?> 
            <?php if($perfil == 1) { ?>
            <li><a href="<?php print $base_url ?>unidades"><i class="fa fa-balance-scale"></i> Unidades Medidas</a></li>
            <?php } ?>            

            <?php if(($perfil == 1) || ($perfil == 2)) { ?>
            <li><a href="<?php print $base_url ?>producto/proajuste"><i class="fa fa-refresh" aria-hidden="true"></i> Precios Ajuste </a></li>
            <?php } ?>            
            <?php if($perfil == 1) { ?>
            <li><a href="<?php print $base_url ?>inventario"><i class="fa fa-exchange" aria-hidden="true"></i> Kardex</a></li>
            <?php } ?>
            <?php if(($perfil == 1) || ($perfil == 3) || ($perfil == 4)  || ($perfil == 5)) { ?>
            <li><a href="<?php print $base_url ?>Inventario/cargar_inventariomovimiento"><i class="fa fa-sliders" aria-hidden="true"></i> Movimientos</a></li>
            <?php } ?>
            <?php if(($perfil == 1) || ($perfil == 3) || ($perfil == 4)) { ?>
            <li><a href="<?php print $base_url ?>Inventario/ajuste"><i class="fa fa-wrench" aria-hidden="true"></i> Existencia </a></li>
            <?php } ?>
            <?php if(($perfil == 1) || ($perfil == 4)) { ?>
            <li><a href="<?php print $base_url ?>producto/controlserie"><i class="fa fa-wrench" aria-hidden="true"></i> Control de Serie </a></li>
            <?php } ?>
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-bar-chart"></i> <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            
            <li><a target="_blank" href="<?php print $base_url ?>facturar/reporteprodmasvendido"><i class="fa fa-sort-numeric-desc"></i> Productos más Vendidos</a></li>
            <?php if($perfil == 1) { ?>            
            <li><a target="_blank" href="<?php print $base_url ?>reporte/utilidades"><i class="fa fa-line-chart" aria-hidden="true"></i> Reporte de Utilidades</a></li>
<!--
            <li><a target="_blank" href="<?php print $base_url ?>reporte/reporte_ats"><i class="fa fa-legal" aria-hidden="true"></i> Reporte de ATS</a></li>
-->


<!--              <li><a href="<?php print $base_url ?>reporte"><i class="fa fa-file-text-o"></i> Cierre de Mes</a></li>
 -->            
            <?php } ?>
          </ul>
        </li>
<!--
        <?php if($perfil == 1) { ?>            
          <li class="treeview">
            <a href="#">
              <i class="fa fa-balance-scale"></i> <span>Contabilidad</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">

              <li><a href="<?php print $base_url ?>contab_comprobantes"><i class="fa fa-columns"></i> Registro de Asientos</a></li>
              <li><a href="<?php print $base_url ?>contab_plancuentas"><i class="fa fa-sitemap" aria-hidden="true"></i> Plan de Cuentas</a></li>
              <li><a href="<?php print $base_url ?>contab_ejercicios"><i class="fa fa-calendar" aria-hidden="true"></i> Ejercicios Contables</a></li>

              <li class="treeview">
                <a href="#">
                  <i class="fa fa-line-chart" aria-hidden="true"></i> <span>Reportes</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="<?php print $base_url ?>contab_operaciones"><i class="fa fa-database"></i> Resumen de Operaciones</a></li>
                  <li><a href="<?php print $base_url ?>contab_balancesumasaldo"><i class="fa fa-balance-scale"></i> Balance de Sumas y Saldos</a></li>
                  <li><a href="<?php print $base_url ?>contab_balancesituacion"><i class="fa fa-balance-scale"></i> Balance de Situación</a></li>
                </ul>
              </li>              

              <li class="treeview">
                <a href="#">
                  <i class="fa fa-cog" aria-hidden="true"></i> <span>Categorías Contables</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="<?php print $base_url ?>contab_categoriageneral"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> Generales</a></li>
                  <li><a href="<?php print $base_url ?>contab_categoriaretencion"><i class="fa fa-balance-scale"></i> Retenciones</a></li>
                  <li><a href="<?php print $base_url ?>contab_categoriacompraventa"><i class="fa fa-balance-scale"></i> Compra/Venta</a></li>
                </ul>
              </li>              

              <li class="treeview">
                <a href="#">
                  <i class="fa fa-cogs" aria-hidden="true"></i> <span>Configuración Contable</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="<?php print $base_url ?>contab_tipocomprobantes"><i class="fa fa-calendar" aria-hidden="true"></i> Tipos de Asientos</a></li>
                  <li><a href="<?php print $base_url ?>contab_configsucursal"><i class="fa fa-calendar" aria-hidden="true"></i> Sucursales</a></li>
                </ul>
              </li>              

            </ul>
          </li>
        <?php } ?>
-->

        <?php if($perfil != 2) { ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-cog" aria-hidden="true"></i>
            <span>Mantenimiento</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

            <li class="treeview">
            <?php if($perfil == 1) { ?>
              <a href="#"><i class="fa fa-sitemap"></i> Estructura
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php print $base_url ?>empresa"><i class="fa fa-fort-awesome"></i> Empresas</a></li>
                <li><a href="<?php print $base_url ?>sucursal"><i class="fa fa-university"></i> Sucursales</a></li>
                <li><a href="<?php print $base_url ?>puntoemision"><i class="fa fa-dot-circle-o"></i> Puntos de Emision</a>
                <li><a href="<?php print $base_url ?>cajaefectivo"><i class="fa fa-calculator"></i> Caja Efectivo</a>
                <li><a href="<?php print $base_url ?>area"><i class="fa fa-globe" aria-hidden="true"></i> Areas</a></li>
                <li><a href="<?php print $base_url ?>mesa"><i class="fa fa-archive" aria-hidden="true"></i> Puntos de Venta</a></li>
              </ul>  
               <?php } ?> 
            </li>  

            <li class="treeview">
              <a href="#"><i class="fa fa-exchange  "></i> Compra-Venta
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if($perfil == 1 && $tp == 1) { ?>
                <li><a href="<?php print $base_url ?>precio"><i class="fa fa-money"></i> Precios</a></li>
                <?php } ?>  
                <?php if($perfil == 1 || $perfil == 3 || $perfil == 5) { ?>            
                <li><a href="<?php print $base_url ?>cliente"><i class="fa fa-users"></i> Clientes</a></li>
                <?php } ?>
                <?php if($perfil == 1 || $perfil == 3) { ?>
                <li><a href="<?php print $base_url ?>proveedor"><i class="fa fa-truck"></i> Proveedores</a></li>
                <?php } ?>
                <?php if($perfil == 1) { ?>
                <li><a href="<?php print $base_url ?>mesero"><i class="fa fa-user"></i> Vendedor</a></li>
                <?php } ?>            
                <?php if($perfil == 1) { ?>
                <li><a href="<?php print $base_url ?>retencion"><i class="fa fa-registered"></i> Concepto Retención</a></li>
                <?php } ?> 
                <?php if($perfil == 1) { ?> 
                <li><a href="<?php print $base_url ?>transportista"><i class="fa fa-male"></i> Transportistas</a></li>
                <?php } ?> 
                <?php if($perfil == 1) { ?>          
                <li><a href="<?php print $base_url ?>ventadatoadicional"><i class="fa fa-plus-square-o"></i> Datos Adicionales Venta</a></li>
                <?php } ?>
                <?php if($perfil == 1) { ?>          
                <li><a href="<?php print $base_url ?>banco"><i class="fa fa-university" aria-hidden="true"></i> Bancos</a></li>
                <?php } ?>
                <?php if($perfil == 1) { ?>          
                <li><a href="<?php print $base_url ?>tarjeta"><i class="fa fa-credit-card" aria-hidden="true"></i> Tarjetas</a></li>
                <?php } ?>
              </ul>  
            </li>  


            <li class="treeview">
            <?php if($perfil == 1) { ?> 
              <a href="#"><i class="fa fa-object-group"></i> Otros
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">

                <li><a href="<?php print $base_url ?>empleado"><i class="fa fa-male"></i> Empleados</a></li>
                <li><a href="<?php print $base_url ?>Serviciotecnico/serviciotecnico_config"><i class="fa fa-male"></i> Detalles de Servicio</a>
                </li>
             <?php } ?>
              </ul>  
            </li>  

          </ul>
        </li>
        <?php } ?> 

        <?php if($perfil == 1) { ?>
        <li class="<?php if ($content == 'usuarios') {  print 'active';} ?> treeview"> <!-- active -->
          <a href="#">
            <i class="fa fa-cogs"></i> <span>Configuración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <?php if($perfil == 1) { ?>
          <li><a href="<?php print $base_url ?>usuarios"><i class="fa fa-user-circle-o"></i> Usuarios</a></li>
          <?php } ?>          
          <!--<li><a href="<?php //print $base_url ?>rol"><i class="fa fa-shield"></i> Roles</a></li>-->
          <li><a href="<?php print $base_url ?>comanda"><i class="fa fa-print" aria-hidden="true"></i> Impresoras</a></li>
          <?php if($perfil == 1) { ?>
          <li><a href="<?php print $base_url ?>parametros"><i class="fa fa-cogs" aria-hidden="true"></i> Parámetros Generales</a></li>
          <?php } ?>          
          <?php if($perfil == 1) { ?>          
          <li><a href="<?php print $base_url ?>correo"><i class="fa fa-envelope-o" aria-hidden="true"></i> Correo</a></li>
          <?php } ?>
          </ul>
        </li>        
        <?php } ?>

        <?php if($habilitapetshop == 1) { ?>
        <li class="treeview">
            <a href="#">
              <i class="fa fa-paw"></i> <span>PetShop</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php print $base_url ?>petshop/mascota"><i class="fa fa-fort-awesome"></i> Mascotas </a></li>
            </ul>
        </li>
        <?php } ?>            
  
        <?php if($perfil == 1) { ?>
        <li class="treeview">
            <a href="#">
              <i class="fa fa-save"></i> <span>Utilitarios</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php print $base_url ?>backup"><i class="fa fa-save"></i> Respaldo de Base de Datos </a></li>
            </ul>
        </li>
        <?php } ?>            

          <script src="public/js/lib/sweetalert.min.js"></script>
          <link rel="stylesheet" type="text/css" href="public/js/lib/sweetalert.css">



      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>