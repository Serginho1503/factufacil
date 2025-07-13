<?php
/* ------------------------------------------------
  ARCHIVO: Notacredito.php
  DESCRIPCION: Contiene la vista principal del módulo de Nota de credito.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Nota de Crédito'</script>";
date_default_timezone_set("America/Guayaquil");

  $parametro = &get_instance();
  $parametro->load->model("Parametros_model");

  $habserie = $parametro->Parametros_model->sel_numeroserie();
  $habilitaserie = $habserie->valor;


?>
<style type="text/css">
  .form-control{
    font-size: 12px;
    height: 28px;
  }

  .table > tbody > tr > td{
    padding-bottom: 0px;
    padding-top: 1px;
  }

  .form-group {
      margin-bottom: 5px;
  }

  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 

  .pago{
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05) inset;
    margin-bottom: 20px;
    min-height: 20px;
    padding: 19px;  
    margin-left: 20px;  
  }

  .calmonto{
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05) inset;
    margin-bottom: 20px;
    min-height: 20px;
    padding: 19px;  
    margin-right: 20px;     
  }

  #tpcredito{
    display: none; 
  }  

</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

    /* FECHA */
    $('#fecha').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
    $("#fecha").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });

    /* FECHA Limite Credito*/
    $('#fechadocmod').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
    $("#fechadocmod").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });

    /* Buscar Documento Modificado */
    $(document).on('click', '.busca_factura', function(){
      var cliente = $('#cmb_cliente').val();
      if ((cliente == '') || (cliente == 0)){
        alert("Seleccione el cliente.");  
      } else {
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST",
             data: {cliente: cliente},
          },
          href: "<?php echo base_url('Notacredito/busca_factura');?>" 
        });
      }
    });

    /* Actualiza Documento Modificado */
    $(document).on('click', '.add_docmodificado', function(){
      var id = $(this).attr('id');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Notacredito/upd_docmodificado');?>",
        data: { id: id },
        success: function(json) {
          if (!$.isEmptyObject(json)){
            $.fancybox.close();
            location.reload();
          }
        }
      });
    });

    /* Buscar Documento Modificado */
    $(document).on('click', '.busca_pro', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST",
          },
          href: "<?php echo base_url('Notacredito/busca_producto');?>" 
        });
    });

    /* Actualiza Documento Modificado */
    $(document).on('click', '.add_producto', function(){
      var id = $(this).attr('id');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Notacredito/ins_producto');?>",
        data: { id: id },
        success: function(json) {
          if (!$.isEmptyObject(json)){
            $.fancybox.close();
            location.reload();
          }
        }
      });
    });

    /* ACTUALIZA LA FACTURA */
    $(document).on('change','.datosnota', function(){
      var sucursal = $("#cmb_sucursal").val();
      var almacen = $("#cmb_almacen").val();
      if ((almacen == '') || (almacen == null)) { almacen = 0; }
      var cliente = $("#cmb_cliente").val();
      if ((cliente == '') || (cliente == null)) { cliente = 0; }
      var fechanota = $("#fecha").val();
      var puntoemision = $("#cmb_punto").val();
      if ((puntoemision == '') || (puntoemision == null)) { puntoemision = 0; }
      var nronota = $("#txt_nronota").val();
      var nrodocmod = $("#txt_nrodocmod").val();
      var iddocmod = $("#txt_iddocmod").val();
      if (iddocmod == '') { iddocmod = 0; }
      var nrodocmod = $("#txt_nrodocmod").val();
      var nrodocmod2 = $("#txt_nrodocmod2").val();
      if (nrodocmod2 != nrodocmod) { iddocmod = 0; }
      var fechadocmod = $("#fechadocmod").val();
      var motivo = $("#txt_motivo").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Notacredito/upd_datosnota');?>",
        data: { sucursal: sucursal, cliente: cliente, fechanota: fechanota, nronota: nronota,
                iddocmod: iddocmod, nrodocmod: nrodocmod, fechadocmod: fechadocmod, 
                motivo: motivo, almacen: almacen, puntoemision: puntoemision},
        success: function(json) {
            location.reload();
        }
      });
      return false;

    });    

    // Evitar ingreso de caracter (coma)
    $('.precio, .cantidad').keydown(function(e){
       var ingnore_key_codes = [188];
       if ($.inArray(e.keyCode, ingnore_key_codes) >= 0){
          e.preventDefault();
       }
    });

/* === GUARDAR EL PRECIO EN LA TABLA TEMPORAL Y REALIZAR CALCULO === */
    $(document).on('change','.precio, .cantidad', function(){
      /* Inicializacion de las variables */
      id = $(this).attr("id");
      precio = $('.precio[id='+id+']').val();
      precio = precio.replace(',','');
      cantidad = $('.cantidad[id='+id+']').val();
      cantidad = cantidad.replace(',','');

      if (precio == '') { precio = 0; }
      if (cantidad == '') { cantidad = 0; }

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Notacredito/upd_notadetalle');?>",
        data: { id: id, precio: precio, cantidad: cantidad },
        success: function(json) {
          location.reload();
        }
      });
    });


  /* PROCESO DE DESCUENTO */
  $(document).on('keyup','#descuento', function(){
    var descuento = $("#descuento").val();
    if (descuento == '') {descuento = 0;}
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Notacredito/upd_descuento');?>",
        data: { descuento: descuento },
        success: function(json) {
          location.reload();
        }
      });
    
  });

    /* ELIMINAR UN PRODUCTOS */
    $(document).on('click', '.del_producto', function(){  
      id = $(this).attr("id");
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Notacredito/del_detalle",
        data: { id: id },
        success: function(json) {
          location.reload();
        }
      });
    }); 


    /* ELIMINAR TODOS LOS PRODUCTOS */
    $(document).on('click', '.del_productos', function(){  
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "Notacredito/del_productos",
        success: function(json) {
          location.reload();
        }
      });
    }); 


  /* GUADAR Nota */
  $(document).on('click','#guardar_nota', function(){
    var cantidadproductos=$("#cantidadproductos").val();
    if (cantidadproductos == 0){
        alert("No se han ingresado los productos.");
        return false;
    }  
    var nrodocmod = $("#txt_nrodocmod").val();
    if (nrodocmod == ''){
        alert("No se ha ingresado el documento modificado.");
        return false;
    }  
    var motivo = $("#txt_motivo").val();
    if (motivo == ''){
        alert("No se ha ingresado el motivo.");
        return false;
    }  
    var puntoemision = $("#cmb_punto").val();
    if (puntoemision == '') { puntoemision = 0; }
    if (puntoemision == 0){
        alert("No se ha ingresado el puntoemision.");
        return false;
    }  
    var cliente = $("#cmb_cliente").val();
    if (cliente == '') { cliente = 0; }
    if (cliente == 0){
        alert("No se ha ingresado el cliente.");
        return false;
    }  
    var almacen = $("#cmb_almacen").val();
    if (almacen == '') { almacen = 0; }
    if (almacen == 0){
        alert("No se ha ingresado el almacen.");
        return false;
    }  
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "<?php echo base_url('Notacredito/guardar_nota');?>",
      success: function(json) {
        if (json.resu > 0){
          redireccion("notacredito");
        }  
      }
    });
  }); 

  function redireccion(contr, meth) {
      location.replace(base_url + contr + (meth ? "/" + meth : ""));
  }

/*------------------------------------*/

  


 


    tmpcant = $('#cmb_sucursal > option').length;
    if (tmpcant <= 1){
      $('#cmb_sucursal').attr('disabled', true);
    } else{
      $('#cmb_sucursal').attr('disabled', false);
    }

}); 


</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-truck"></i> Nota de Crédito  
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>notacredito">Notas de Crédito</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DEL PROVEEDOR -->        
      <div class="col-md-12">
        <div class="box box-danger">
          <div style="padding-top: 5px; padding-bottom: 0px;" class="box-header with-border col-md-4 pull-right">
                <!-- NRO DE FACTURA -->
                <div style="padding-top: 0px; padding-bottom: 0px; padding-right: 0px;" >
                  <div class="col-md-3">
                    <label>Nro.Nota</label>
                  </div>  

                  <!-- Punto Emision  -->
                  <div style="padding-top: 0px; padding-bottom: 0px; padding-right: 0px;"  class="form-group col-md-4">
                      <select id="cmb_punto" name="cmb_punto" class="form-control datosnota" title="Punto de Emision">
                      <?php 
                        if(@$tmpcomp->id_puntoemision != NULL){ ?>
                        <?php } else { ?>
                        <option  value="" selected="TRUE">Seleccione Punto de Emision...</option>
                        <?php } 
                          if (count($puntoemision) > 0) {
                            foreach ($puntoemision as $obj):
                                if(@$tmpcomp->id_puntoemision != NULL){
                                    if($obj->id_puntoemision == $tmpcomp->id_puntoemision){ ?>
                                         <option value="<?php  print $obj->id_puntoemision; ?>" selected="TRUE"> <?php  print $obj->cod_punto; ?> </option>
                                        <?php
                                    }else{ ?>
                                        <option value="<?php  print $obj->id_puntoemision; ?>" > <?php  print $obj->cod_punto; ?> </option>
                                        <?php
                                    }
                                }else{ ?>
                                    <option value="<?php  print $obj->id_puntoemision; ?>" > <?php  print $obj->cod_punto; ?> </option>
                                    <?php
                                    }   ?>
                                <?php
                            endforeach;
                          }
                        ?>
                      </select>                                  
                  </div>


                  <div class="col-md-5">
                    <input type="text" class="form-control validate[required] " id="txt_nronota" name="txt_nronota" value="<?php if(@$tmpcomp->id_puntoemision != NULL){ print @$tmpcomp->nro_documento; }?>" readonly>
                  </div>                    
                </div>

<!--             <h3 class="box-title"><i class="fa fa-user"></i> Datos del Proveedor </h3> 
 -->      </div>
          <div class="box-body">
            <div class="row">
              <div style="padding-left: 0px; padding-right: 0px;" class="col-md-12">
                <!-- SUCURSAL  -->
                <div style="" class="form-group col-md-3">
                  <div style="padding-left: 0px; padding-right: 0px;" class="col-md-3">
                    <label for="lb_res">Sucursal</label>
                  </div>
                  <div  class="col-md-9">
                    <select id="cmb_sucursal" name="cmb_sucursal" class="form-control datosnota">
                    <?php 
                      if(@$sucursales != NULL){ ?>
                      <?php } else { ?>
                      <option  value="" selected="TRUE">Seleccione Sucursal...</option>
                      <?php } 
                        if (count($sucursales) > 0) {
                          foreach ($sucursales as $obj):
                              if(@$tmpcomp->id_sucursal != NULL){
                                  if($obj->id_sucursal == $tmpcomp->id_sucursal){ ?>
                                       <option value="<?php  print $obj->id_sucursal; ?>" selected="TRUE"> <?php  print $obj->nom_sucursal; ?> </option>
                                      <?php
                                  }else{ ?>
                                      <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                                      <?php
                                  }
                              }else{ ?>
                                  <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                                  <?php
                                  }   ?>
                              <?php
                          endforeach;
                        }
                      ?>
                    </select>                                  
                  </div>
                </div>

                <!-- NOMBRE DEL cliente -->
                <div style="" class="form-group col-md-3">
                  <div style="padding-left: 0px; padding-right: 0px;" class="col-md-2">
                    <label for="lb_res">Cliente</label>
                  </div>  
                  <div class="col-md-10">                   
                    <select id="cmb_cliente" name="cmb_cliente" class="form-control datosnota">
                    <?php 
                      if(@$cliente != NULL){ ?>
                      <option  value="" selected="TRUE">Seleccione Cliente...</option>
                      <?php } 
                        if (count($cliente) > 0) {
                          foreach ($cliente as $obj):
                              if(@$tmpcomp->id_cliente != NULL){
                                  if($obj->id_cliente == $tmpcomp->id_cliente){ ?>
                                       <option value="<?php  print $obj->id_cliente; ?>" selected="TRUE"> <?php  print $obj->nom_cliente; ?> </option>
                                      <?php
                                  }else{ ?>
                                      <option value="<?php  print $obj->id_cliente; ?>" > <?php  print $obj->nom_cliente; ?> </option>
                                      <?php
                                  }
                              }else{ ?>
                                  <option value="<?php  print $obj->id_cliente; ?>" > <?php  print $obj->nom_cliente; ?> </option>
                                  <?php
                                  }   ?>
                              <?php
                          endforeach;
                        }
                      ?>
                    </select>                                  
                  </div>
                </div>

                <!-- FECHA DE FACTURA -->
                <div class="form-group col-md-3">
                  <div style="padding-left: 0px; padding-right: 0px;" class="col-md-4">
                    <label for="">Fecha</label>
                  </div>  
                  <div style="margin-bottom: 0px; padding-left: 0px; padding-right: 0px;" class="form-group col-md-8" >
                    <div class="input-group date">
                      <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right validate[required] datosnota" id="fecha" name="fecha" value="<?php if(@$tmpcomp->fecha != NULL){ @$fec = str_replace('-', '/', @$tmpcomp->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} ?>">
                    </div>                             
                  </div>
                </div>  

                <!-- almacen -->
                <div style="" class="form-group col-md-3">
                  <div  class="col-md-3">
                    <label for="lb_res">Almacen</label>
                  </div>  
                  <div class="col-md-9">                   
                    <select id="cmb_almacen" name="cmb_almacen" class="form-control datosnota">
                    <?php 
                      if(@$almacen != NULL){ ?>
                      <option  value="" selected="TRUE">Seleccione almacen...</option>
                      <?php } 
                        if (count($almacen) > 0) {
                          foreach ($almacen as $obj):
                              if(@$tmpcomp->id_almacen != NULL){
                                  if($obj->almacen_id == $tmpcomp->id_almacen){ ?>
                                       <option value="<?php  print $obj->almacen_id; ?>" selected="TRUE"> <?php  print $obj->almacen_nombre; ?> </option>
                                      <?php
                                  }else{ ?>
                                      <option value="<?php  print $obj->almacen_id; ?>" > <?php  print $obj->almacen_nombre; ?> </option>
                                      <?php
                                  }
                              }else{ ?>
                                  <option value="<?php  print $obj->almacen_id; ?>" > <?php  print $obj->almacen_nombre; ?> </option>
                                  <?php
                                  }   ?>
                              <?php
                          endforeach;
                        }
                      ?>
                    </select>                                  
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

    <!-- DATOS DEL Documento Modificado -->        
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
             <h3 class="box-title"><i class="fa fa-user"></i> Documento Modificado </h3> 
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <!-- NRO DE FACTURA -->
                <div style="padding-left: 0px; padding-right: 0px;" class="col-md-1">
                  <a class="btn btn-success btn-sm busca_factura" href="#" data-original-title="" title=""><i class="fa fa-binoculars"></i> Buscar.. </a> 
                </div>
                <div style="padding-left: 0px; padding-right: 0px;" class="form-group col-md-3">
                  <div class="col-md-5">
                    <label>Nro.Documento</label>
                  </div>  
                  <div class="col-md-7">
                    <input type="hidden" id="txt_iddocmod" name="txt_iddocmod" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->id_docmodificado; }?>">
                    <input type="hidden" id="txt_nrodocmod2" name="txt_nrodocmod2" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->nro_docmodificado; }?>">
                    <input style="padding-left: 5px; padding-right: 5px;" type="text" class="form-control validate[required] datosnota" id="txt_nrodocmod" name="txt_nrodocmod" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->nro_docmodificado; }?>">
                  </div>                    
                </div>
                <!-- FECHA DE FACTURA -->
                <div class="form-group col-md-3">
                  <div style="padding-left: 0px; padding-right: 0px;" class="col-md-4">
                    <label for="">Fecha</label>
                  </div>  
                  <div style="padding-left: 0px; margin-bottom: 0px;" class="form-group col-md-8" >
                    <div class="input-group date">
                      <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control validate[required] datosnota" id="fechadocmod" name="fechadocmod" value="<?php if(@$tmpcomp->fecha_docmodificado != NULL){ @$fec = str_replace('-', '/', @$tmpcomp->fecha_docmodificado); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} ?>">
                    </div>                             
                  </div>
                </div>  
                <!-- Motivo -->
                <div style="padding-left: 0px; padding-right: 0px;" class="form-group col-md-5">
                  <div style="padding-left: 0px; padding-right: 0px;" class="col-md-1">
                    <label>Motivo</label>
                  </div>  
                  <div class="col-md-11">
                    <input type="text" class="form-control validate[required] datosnota" id="txt_motivo" name="txt_motivo" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->motivo; }?>">
                  </div>                    
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

    <!-- DATOS DE LOS PRODUCTOS A COMPRAR -->
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-shopping-bag"></i> Lista de Productos </h3>
            
            <div class="pull-right"> 
              <a class="btn bg-orange-active color-palette btn-grad btn-sm busca_pro" href="#" data-original-title="" title=""><i class="fa fa-shopping-bag"></i> Añadir Producto </a>
            </div>

            <div class="pull-right">
              <a class="btn btn-danger btn-sm del_productos" href="#" data-original-title="" title=""><i class="fa fa-trash"></i> Borra Productos </a> 
            </div>

          </div>
          <div class="box-body">
            <div class="row">
              <div id="detnota" class="col-md-12 table-responsive" > 
                <input type="hidden" id="cantidadproductos" name="cantidadproductos" value="<?php if(@$detcomp != NULL){ print @count($detcomp); } else {print 0;} ?>">
                <table class="table table-bordered table-responsive">
                  <tbody>
                    <tr>
                        <th class="text-center " style="width: 10px;">Nro</th>
                        <th class="text-center col-md-1">Cod Barra</th>
                        <th>Producto</th>
                        <th class="text-center col-md-1">Precio</th>
                        <th class="text-center col-md-1">Cantidad</th>
                        <th class="text-center col-md-1">SubTotal</th>
                        <th class="text-center " style="width: 10px;">Acción</th>
                    </tr>
                    <?php 
                      $stciva = 0;
                      $stsiva = 0;  
                      $dstciva = 0;
                      $dstsiva = 0;
                      $moniva = 0;
                      $total = 0;
                      
                                                          
                    $nro = 0; 
                    $desc = @$tmpcomp->descuento;
                    if(@$detcomp != NULL){
                      if (count($detcomp) > 0) {
                        foreach ($detcomp as $dc):
                          $nro = $nro + 1;
                          if($desc > 0){ $tbsubcdesc = @$dc->descsubtotal; }
                          else { $tbsubcdesc = '0,00'; }
                          if(@$dc->gravaiva == 1) { 
                            $dstciva = $dstciva + @$dc->descsubtotal; 
                            $stciva = $stciva + @$dc->subtotal;
                          } 
                          else { 
                            $dstsiva = $dstsiva + @$dc->descsubtotal; 
                            $stsiva = $stsiva + @$dc->subtotal;
                          }
                          $moniva = $moniva + @$dc->montoiva;
                          $total = $total + @$dc->descsubtotal;


                    ?>
                    <tr>
                        <!-- NRO -->
                        <td class="text-center"><?php print $nro; ?></td>
                        <!-- CODIGO DE BARRA -->
                        <td class="text-left"><?php print @$dc->pro_codigobarra; ?></td>
                        <!-- NOMBRE DEL PRODUCTO -->
                        <td class="text-left"><?php print @$dc->pro_nombre; ?></td>
                        <!-- PRECIO DEL PRODUCTO -->
                        <td class="text-center">
                          <input type="text" class="form-control text-right precio" name="" id="<?php print @$dc->id ?>" value="<?php if(@$dc != NULL){ print @$dc->precio; }?>" >
                        </td>                              
                        <!-- CANTIDAD -->
                        <td class="text-center">
                          <input type="text" class="form-control text-right cantidad" name="" id="<?php print @$dc->id ?>" value="<?php if(@$dc != NULL){ print @$dc->cantidad; }?>" 
                          >
                        </td>
                        <!-- SUBTOTAL -->
                        <td class="text-right"><?php if(@$dc != NULL){ print @$dc->subtotal; } ?> </td>
                        <!-- ACCION -->
                        <td class="text-center">
                          <a href="#" title="Eliminar" id="<?php if(@$dc != NULL){ print @$dc->id; }?>" class="btn btn-danger btn-xs btn-grad del_producto"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                    <?php 
                            endforeach;
                        }
                    } 
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div   align="center" class="box-footer">
            <hr class="linea"> 
              <div class="row" style="margin-top:0px; margin-bottom: 0px;">
                <div class="col-md-6">
                <!-- FORMA DE PAGO -->
                </div>

                <div class="col-md-6">
                <!-- MONTOS DE PAGO -->                
                  <div class="pull-right calmonto ">
                    <table style="margin-top:0px; margin-bottom: 0px;" class="table table-clear">
                      <tbody>
                        <tr>
                          <td class="text-left"><strong>Subtotal IVA 12 %</strong></td>
                          <td id="msubtotalconiva" class="text-right">$<?php print number_format(@$stciva,2,",","."); ?></td>                                        
                        <tr>
                        <tr>
                          <td class="text-left"><strong>Subtotal IVA 0 %</strong></td>
                          <td id="msubtotalsiniva" class="text-right">$<?php print number_format(@$stsiva,2,",","."); ?></td>                                        
                        <tr>
                        <tr>
                          <td class="text-left"><strong>Descuento</strong></td>
                          <td id="" class="text-right">
                            <input type="text" class="text-right" name="" id="descuento" value="<?php print number_format(@$tmpcomp->descuento,2,",","."); ?>" style="width:70px;" >
                          </td>                                        
                        </tr>
                        <tr>
                          <td class="text-left"><strong>Subtotal con Descuento IVA 12 %</strong></td>
                          <td id="descsubiva" class="text-right">$<?php print number_format(@$dstciva,2,",","."); ?></td>                                        
                        </tr>
                        <tr>
                          <td class="text-left"><strong>Subtotal con Descuento IVA 0 %</strong></td>
                          <td id="descsub" class="text-right">$<?php print number_format(@$dstsiva,2,",","."); ?></td>                                        
                        </tr>
                        <tr>
                          <td class="text-left"><strong>IVA (12%)</strong></td>
                          <td id="miva" class="text-right">$<?php print number_format(@$moniva,2,",","."); ?></td>                                        
                        </tr>
                        <tr>
                          <td class="text-left"><strong>Total</strong></td>
                          <td id="mtotal" class="text-right"><strong>$<?php $total = $total + $moniva; print number_format(@$total,2,",","."); ?></strong></td>                                        
                        </tr>      

                      </tbody>
                    </table>

                  </div>

                </div>
                <div class="col-md-12">
                  <div class="pull-right"> 
                    <a id="guardar_nota" class="btn bg-green-active color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-save"></i> Guardar </a>
                  </div>                  
                </div>

              </div><!--/row-->



          </div>
        </div>

      </div>           
    </div>
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

