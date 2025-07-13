<?php
/* ------------------------------------------------
  ARCHIVO: Compra.php
  DESCRIPCION: Contiene la vista principal del módulo de Compra.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Movimiento de Inventario'</script>";
date_default_timezone_set("America/Guayaquil");

?>
<style type="text/css">
  .form-control{
    font-size: 12px;
    height: 28px;
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
 
  #almadest{
    display: none;
  }

</style>
<?php $tipmov = $tmpmov->id_tipodoc; ?>
<script type='text/javascript' language='javascript'>

  $(document).ready(function () {
    $("#formCLI").validationEngine();

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

    <?php if($tipmov == NULL) { $tipmov = 4; }; ?>
    var tipmov = <?php print @$tipmov; ?>;
    if(tipmov == 8){
      $("#almadest").css("display", "inline");
      $("#lbalmacen").text("Almacen Origen");
      $("#divdescripcion").removeClass("col-md-12");
      $("#divdescripcion").addClass("col-md-7");            
    }else{
      $("#almadest").css("display", "none");
      $("#lbalmacen").text("Almacen");
    } 

    /* MASCARA PARA COD DE FACTURA*/
   /* $("#factura").mask("999-999-999999999");*/

/* ==== GUARDAR DATOS DE CABECERA ======================================*/

    /* ACTUALIZA LA FACTURA 
    $(document).on('change','.nrodoc', function(){
      var factura = $('.nrodoc').val();
      var descripcion = $('#descripcion').val();
      var almacen = $('#cmb_almacen').val();
      var tipomov = $('#cmb_tipomov').val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php //echo base_url('inventario/upd_tmp_movinv');?>",
        data: { factura: factura, descripcion: descripcion, 
                almacen: almacen, tipomov: tipomov},
        success: function(json) {

        }
      });
      return false;

    });    
*/
    /* ACTUALIZA descripcion */
    $(document).on('change','#descripcion', function(){
      var factura = $('.nrodoc').val();
      var descripcion = $('#descripcion').val();
      var almacen = $('#cmb_almacen').val();
      var tipomov = $('#cmb_tipomov').val();
      var categoria = $('#cmb_categoria').val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('inventario/upd_tmp_movinv');?>",
        data: { factura: factura, descripcion: descripcion, 
                almacen: almacen, tipomov: tipomov,
                categoria: categoria},
        success: function(json) {

        }
      });
      return false;
    });   

    var contabilizaorigen = 0;
    /* ACTUALIZA ALMACEN */
    $(document).on('change','#cmb_almacen, #cmb_categoria', function(){
      var factura = $('.nrodoc').val();
      var descripcion = $('#descripcion').val();
      var almacen = $('#cmb_almacen').val();
      var tipomov = $('#cmb_tipomov').val();
      var categoria = $('#cmb_categoria').val();

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('inventario/upd_tmp_movinv');?>",
        data: { factura: factura, descripcion: descripcion, 
                almacen: almacen, tipomov: tipomov,
                categoria: categoria},
        success: function(json) {
          contabilizaorigen = 0;
          if (json !== null){
            if (json.contabiliza !== null){
              contabilizaorigen = json.contabiliza;
            }
          }
          if (tipomov == "8"){ /*Transferencia*/
            var strhtml = "<select id='cmb_almacend' name='cmb_almacend' class='form-control'>";

            $("#cmb_almacen option").each(function(name, val) { 
              if ((val.value != almacen) || (val.value == "0")){
                strhtml += "<option value='" + val.value + "'> "+ val.text + " </option>";
              }  
            });

            strhtml += "</select>";
            $("#cmb_almacend").html(strhtml);
          }
        }
      });
      return false;
    });   

    var idtipomov = 0;
    /* ACTUALIZA tipo movimiento */
    $(document).on('change','#cmb_tipomov', function(){
      //var factura = $('.nrodoc').val();
      idtipomov = $('#cmb_tipomov').val();
      if(idtipomov == 4){
        $(".contmov").html("<label>Nro Documento</label><input type = 'text' class = 'form-control validate[required] text-left nrodoc' id = 'txt_nrodoc' name = 'txt_nrodoc' value='<?php if(@$nromoving != NULL){ print @$nromoving; } ?>'>");
        var factura = "<?php if(@$nromoving != NULL){ print @$nromoving; } ?>";
      }
      if(idtipomov == 5){
        $(".contmov").html("<label>Nro Documento</label><input type = 'text' class = 'form-control validate[required] text-left nrodoc' id = 'txt_nrodoc' name = 'txt_nrodoc' value='<?php if(@$nromoving != NULL){ print @$nromovegr; } ?>'>");
        var factura = "<?php if(@$nromoving != NULL){ print @$nromovegr; } ?>";
      }
      if(idtipomov == 8){
        $(".contmov").html("<label>Nro Documento</label><input type = 'text' class = 'form-control validate[required] text-left nrodoc' id = 'txt_nrodoc' name = 'txt_nrodoc' value='<?php if(@$nromoving != NULL){ print @$nromovtra; } ?>'>");
        var factura = "<?php if(@$nromoving != NULL){ print @$nromovtra; } ?>";
      } 
      if(idtipomov == 0){
        $(".contmov").html("<label>Nro Documento</label><input type = 'text' class = 'form-control validate[required] text-left nrodoc' id = 'txt_nrodoc' name = 'txt_nrodoc' value='<?php if(@$nromoving != NULL){ print " "; } ?>'>");
        var factura = "<?php if(@$nromoving != NULL){ print "0"; } ?>";
      }           

      var descripcion = 0;//$('#descripcion').val();
      var almacen = $('#cmb_almacen').val();
      var categoria = $('#cmb_categoria').val();
      if(idtipomov == 8){
        $("#almadest").css("display", "inline");
        $("#lbalmacen").text("Almacen Origen");
        $("#divdescripcion").removeClass("col-md-12");
        $("#divdescripcion").addClass("col-md-7");            
      }else{
        $("#almadest").css("display", "none");
        $("#lbalmacen").text("Almacen");
        $("#divdescripcion").removeClass("col-md-7");
        $("#divdescripcion").addClass("col-md-12");            
      } 
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('inventario/upd_tmp_movinv');?>",
        data: { factura: factura, descripcion: descripcion, 
                almacen: almacen, tipomov: idtipomov,
                categoria: categoria},
        success: function(json) {
          if (json.categoria){
            var strhtml = "<select id='cmb_categoria' name='cmb_categoria' class='form-control'>";

            strhtml += "<option value='0' selected='TRUE'>Seleccione...</option>";

            json.categoria.forEach(function(value, index) { 
              strhtml += "<option value='" + value.id + "'> "+ value.categoria + " </option>";
            });

            strhtml += "</select>";
            $("#cmb_categoria").html(strhtml);

          }
        }
      });
      return false;
    });   

    var contabilizadestino = 0;
    /* ACTUALIZA ALMACEN Destino */
    $(document).on('change','#cmb_almacend, #cmb_categdest', function(){
      var almacendest = $('#cmb_almacend').val();
      var categoria = $('#cmb_categdest').val();
      
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('inventario/upd_tmp_movinvdest');?>",
        data: { almacendest: almacendest, categoria: categoria},
        success: function(json) {
          contabilizadestino = 0;
          if (json !== null){
            if (json.contabiliza !== null){
              contabilizadestino = json.contabiliza;
            }
          }

        }
      });
      return false;
    });   


/* =========================================================================*/
    /* AGREGAR PRODUCTO */
    $(document).on('click', '.add_producto', function(){
      var almacen = $('#cmb_almacen').val();
      if ((almacen == '') || (almacen == 0)){
        alert("Seleccione el almacen de destino.");  
      } else {
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('inventario/add_producto');?>" 
        });
      }
    });


    /* ELIMIANR PRODUCTOS DE LA TABLA TEMPORAL */
    $(document).on('click','.promov_del', function(){
      id = $(this).attr("id");
      alert("Se eliminara el Producto Seleccionado");
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "inventario/del_producto",
          data: {id: id},
          success: function(json) {
              $('#detmov').load(base_url + "inventario/actualiza_tabla_producto");
          }
        });
        return false;        
    });

    /* GUARDAR LA CANTIDAD EN LA TABLA TEMPORAL Y REALIZAR CALCULO */
    $(document).on('change','.cantidad, .precio, .unidadmedida', function(){
      id = $(this).attr("id");
      precio = $('.precio[id='+id+']').val();
      cantidad = $('.cantidad[id='+id+']').val();
      unidadmedida = $('.unidadmedida[id='+id+']').val();

      /* ACTUALIZA Producto */
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('inventario/upd_producto');?>",
        data: { id: id, precio: precio, cantidad: cantidad,  unidadmedida: unidadmedida },
        success: function(json) {
              $('#detmov').load(base_url + "inventario/actualiza_tabla_producto");
              $('#mtotal').html(json);
              
        }
      });
    });

    /* ELIMINA PRODUCTO DE LA TABLA TEMPORAL */
    $(document).on('click','.del_todoproducto', function(){

      if (confirm("Desea eliminar todos los productos del movimiento?")){

        /* ACTUALIZA Producto */
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('inventario/del_todoproducto');?>",
          success: function(json) {
                $('#detmov').load(base_url + "inventario/actualiza_tabla_producto");
                $('#mtotal').html(json);
                
          }
        });
      }

    });

    /* GUARDAR EL MOVIMIENTO */
    $(document).on('click','#guardar_compra', function(){

      var almacen = $('#cmb_almacen').val();
      if ((almacen == 0) || (almacen == '')){
        alert('Seleccione el almacén origen');
        return false;
      }
      if(idtipomov == 8){
        var almacen = $('#cmb_almacend').val();
        if ((almacen == 0) || (almacen == '')){
          alert('Seleccione el almacén destino');
          return false;
        }
      }


      if (confirm("Desea guardar el movimiento?")){
        var fecha = $("#fecha").val();


        /* ACTUALIZA Producto */
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('inventario/guardar');?>",
          data: { fecha: fecha },
          success: function(json) {
            newid = json.newid;
            if (newid > 0){
              if(idtipomov == 4)
                if (contabilizaorigen == 1){
                  contabilizar_ingreso(newid);
                } 
                else{
                  location.replace(base_url + "Inventario/cargar_inventariomovimiento");               
                }
              else{                 
                docingreso = 0;
                if(idtipomov == 8){ docingreso = json.docingreso;}
                contabilizar_egreso(newid, docingreso); 
              }  
              //location.replace(base_url + "Inventario/cargar_inventariomovimiento");               
            }
          }
        });
      }

    });

    function contabilizar_ingreso(id){
        if(idtipomov == 8){
          almacen = $('#cmb_almacend').val();
        }
        else{
          almacen = $('#cmb_almacen').val();
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            data: {almacen: almacen },
            url: base_url + "contabilidad/contab_comprobante/cuentas_configuradas_ingresoinvdoc",
            success: function(json) {
                if (json == false){
                    alert( "Revise las categorias contables de Ingreso de Inventario. Faltan cuentas por configurar." );
                    location.replace(base_url + "Inventario/cargar_inventariomovimiento");               
                }
                else{
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {id: id },
                        url: base_url + "contabilidad/contab_comprobante/ins_comprobante_ingresoinv",
                        success: function(json) {
                          location.replace(base_url + "Inventario/cargar_inventariomovimiento");               
                        }
                    });
                }
            }    
        });
    }

    function contabilizar_egreso(id, docingreso){
        var almacen = $('#cmb_almacen').val();
        if (contabilizaorigen == 1){
          $.ajax({
            type: "POST",
            dataType: "json",
            data: {almacen: almacen },
            url: base_url + "contabilidad/contab_comprobante/cuentas_configuradas_egresoinvdoc",
            success: function(json) {
                if (json == false){
                    alert( "Revise las categorias contables de Egreso de Inventario. Faltan cuentas por configurar." );
                    location.replace(base_url + "Inventario/cargar_inventariomovimiento");               
                }
                else{
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {id: id },
                        url: base_url + "contabilidad/contab_comprobante/ins_comprobante_egresoinv",
                        success: function(json) {
                          if(idtipomov == 8){
                            if (contabilizadestino == 1){
                              contabilizar_ingreso(docingreso);
                            }
                            else{
                              location.replace(base_url + "Inventario/cargar_inventariomovimiento");               
                            }
                          }
                          else{
                            location.replace(base_url + "Inventario/cargar_inventariomovimiento");               
                          }  
                        }
                    });
                }
            }    
          });
        }
        else{
          if(idtipomov == 8){
            if (contabilizadestino == 1){
              contabilizar_ingreso(docingreso);
            }
            else{
              location.replace(base_url + "Inventario/cargar_inventariomovimiento");               
            }
          }
          else{
            location.replace(base_url + "Inventario/cargar_inventariomovimiento");               
          }           
        }  
    }

    $(document).on('change','#cmb_tipomov00', function(){
      var idtipomov = $("#cmb_tipomov option:selected").val();
      if(idtipomov == 4){
        $(".contmov").html("<label>Nro Documento</label><input type = 'text' class = 'form-control validate[required] text-left nrodoc' id = 'txt_nrodoc' name = 'txt_nrodoc' value='<?php if(@$nromoving != NULL){ print @$nromoving; } ?>'>");
        var factura = "<?php if(@$nromoving != NULL){ print @$nromoving; } ?>";
      }
      if(idtipomov == 5){
        $(".contmov").html("<label>Nro Documento</label><input type = 'text' class = 'form-control validate[required] text-left nrodoc' id = 'txt_nrodoc' name = 'txt_nrodoc' value='<?php if(@$nromoving != NULL){ print @$nromovegr; } ?>'>");
        var factura = "<?php if(@$nromoving != NULL){ print @$nromovegr; } ?>";
      }
      if(idtipomov == 8){
        $(".contmov").html("<label>Nro Documento</label><input type = 'text' class = 'form-control validate[required] text-left nrodoc' id = 'txt_nrodoc' name = 'txt_nrodoc' value='<?php if(@$nromoving != NULL){ print @$nromovtra; } ?>'>");
        var factura = "<?php if(@$nromoving != NULL){ print @$nromovtra; } ?>";
      } 
      if(idtipomov == 0){
        $(".contmov").html("<label>Nro Documento</label><input type = 'text' class = 'form-control validate[required] text-left nrodoc' id = 'txt_nrodoc' name = 'txt_nrodoc' value='<?php if(@$nromoving != NULL){ print " "; } ?>'>");
        var factura = "<?php if(@$nromoving != NULL){ print "0"; } ?>";
      }           
     /* alert(factura);*/
      var descripcion = $('#descripcion').val();
      var almacen = $('#cmb_almacen').val();
      var tipomov = $('#cmb_tipomov').val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('inventario/upd_tmp_movinv');?>",
        data: { factura: factura, descripcion: descripcion, 
                almacen: almacen, tipomov: tipomov},
        success: function(json) {

        }
      }); 
      return false;
    });


    var mostrarvalores = <?php if (@$mostrarvalores != NULL) { print $mostrarvalores;} else { print 0;} ?>;
    if (mostrarvalores == 0){
      $(".calmonto").hide();
    }

}); 


</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-truck"></i> Nuevo Movimiento de Inventario  
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>Inventario/cargar_inventariomovimiento">Movimientos de Inventario</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DEL PROVEEDOR -->        
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user"></i> Datos Generales </h3> 
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-7">
                <!-- FECHA DE FACTURA -->
                <div class="form-group col-md-4">
                  <label for="">Fecha</label>
                  <div style="margin-bottom: 0px;"class="form-group" >
                    <div class="input-group date">
                      <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right validate[required]" id="fecha" name="fecha" value="<?php @$fec = date("d/m/Y"); print @$fec; ?>">
                    </div>                             
                  </div>
                </div>  
                <!-- NRO DE FACTURA -->
                <div class="form-group col-md-4 contmov">
                  <label>Nro Documento</label>
                  <input id="factura" type="text" class="form-control validate[required] text-left" id="txt_factura" name="txt_factura" value="<?php if(@$tmpmov != NULL){ print @$tmpmov->nro_documento; }else{ print @$nromoving; }?>">
                </div>
                <!-- Tipo Movimiento -->
                <div class="form-group col-md-4">
                    <label>Tipo Movimiento</label>
                    <select id="cmb_tipomov" name="cmb_tipomov" class="form-control">
                        <?php 
                          if(@$lst_tipomov != NULL){ ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } else { ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } 
                                  if (count($lst_tipomov) > 0) {
                                    foreach ($lst_tipomov as $cg):
                                        if(@$tmpmov->id_tipodoc != NULL){
                                            if($cg->id_contador == $tmpmov->id_tipodoc){ ?>
                                                <option  value="<?php  print $cg->id_contador; ?>" selected="TRUE"><?php  print $cg->categoria ?></option> 
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $cg->id_contador; ?>"> <?php  print $cg->categoria ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $cg->id_contador; ?>"> <?php  print $cg->categoria ?> </option>
                                            <?php
                                            }   ?>
                                        <?php

                                    endforeach;
                                  }
                                  ?>
                                  
                    </select>
                </div> 
              </div>
              <div class="col-md-5">
                <!-- ALMACEN   -->
                <div class="form-group col-md-6">
                    <label id="lbalmacen">Almacen</label>
                    <select id="cmb_almacen" name="cmb_almacen" class="form-control">
                        <?php 
                          if(@$almacenes != NULL){ ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } else { ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } 
                                  if (count($almacenes) > 0) {
                                    foreach ($almacenes as $alm):
                                        if(@$tmpmov->id_almacen != NULL){
                                            if($alm->almacen_id == $tmpmov->id_almacen){ ?>
                                                <option  value="<?php  print $alm->almacen_id; ?>" selected="TRUE"><?php  print $alm->almacen_nombre ?></option> 
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $alm->almacen_id; ?>"> <?php  print $alm->almacen_nombre ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $alm->almacen_id; ?>"> <?php  print $alm->almacen_nombre ?> </option>
                                            <?php
                                            }   ?>
                                        <?php

                                    endforeach;
                                  }
                                  ?>
                                  
                    </select>
                </div> 

                <!-- CATEGORIAS -->
                <div class="form-group col-md-6">
                    <label>Categoría</label>
                    <select id="cmb_categoria" name="cmb_categoria" class="form-control">
                        <?php 
                          if(@$tmpmov->idcategoriacontable == NULL){ ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } 

                                  if (count($categoria) > 0) {
                                    foreach ($categoria as $cg):
                                        if(@$tmpmov->idcategoriacontable != NULL){
                                            if($cg->id == $tmpmov->idcategoriacontable){ ?>
                                                <option  value="<?php  print $cg->id; ?>" selected="TRUE"><?php  print $cg->categoria ?></option> 
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $cg->id; ?>"> <?php  print $cg->categoria ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $cg->id; ?>"> <?php  print $cg->categoria ?> </option>
                                            <?php
                                            }   ?>
                                        <?php

                                    endforeach;
                                  }
                                  ?>
                    </select>
                </div>
              </div>
            
              <div id="divdescripcion" class="col-md-12" style="padding-left: 30px; ">
                <!-- DESCRIPCION -->
                  <label>Descripcion</label>
                  <input type="text" class="form-control validate[required] text-left" id="descripcion" name="descripcion" value="<?php if(@$tmpmov != NULL){ print @$tmpmov->descripcion; }?>">
              </div>

              <div id="almadest" class="col-md-5">
                <!-- ALMACEN   -->
                <div  class="form-group col-md-6" style=" padding-left: 8px; padding-right: 30px;">
                  <label>Almacen Destino</label>
                  <select id="cmb_almacend" name="cmb_almacend" class="form-control validate[required]">
                      <?php 
                        if(@$almacenes != NULL){ ?>
                          <option  value="0" selected="TRUE">Seleccione...</option>
                      <?php } else { ?>
                          <option  value="0" selected="TRUE">Seleccione...</option>
                      <?php } 
                                if (count($almacenes) > 0) {
                                  foreach ($almacenes as $alm):
                                      if((@$tmpmov->id_almdest != NULL) && (@$tmpmov->id_almacen != $alm->almacen_id)){
                                          if($alm->almacen_id == $tmpmov->id_almdest){ ?>
                                              <option  value="<?php  print $alm->almacen_id; ?>" selected="TRUE"><?php  print $alm->almacen_nombre ?></option> 
                                              <?php
                                          }else{ ?>
                                              <option value="<?php  print $alm->almacen_id; ?>"> <?php  print $alm->almacen_nombre ?> </option>
                                              <?php
                                          }
                                      }else if(@$tmpmov->id_almacen != $alm->almacen_id){ ?>
                                          <option value="<?php  print $alm->almacen_id; ?>"> <?php  print $alm->almacen_nombre ?> </option>
                                          <?php
                                          }   ?>
                                      <?php

                                  endforeach;
                                }
                                ?>
                  </select>
                </div> 

                <!-- CATEGORIAS -->
                <div class="form-group col-md-6">
                    <label>Categoría Ingreso</label>
                    <select id="cmb_categdest" name="cmb_categdest" class="form-control">
                        <?php 
                          if(@$tmpmov->idcategoriacontabledestino == NULL){ ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } 

                                  if (count($categingreso) > 0) {
                                    foreach ($categingreso as $cg):
                                        if(@$tmpmov->idcategoriacontable != NULL){
                                            if($cg->id == $tmpmov->idcategoriacontable){ ?>
                                                <option  value="<?php  print $cg->id; ?>" selected="TRUE"><?php  print $cg->categoria ?></option> 
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $cg->id; ?>"> <?php  print $cg->categoria ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $cg->id; ?>"> <?php  print $cg->categoria ?> </option>
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
    <!-- DATOS DE LOS PRODUCTOS A COMPRAR -->
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-shopping-bag"></i> Lista de Productos </h3>
            <div class="pull-right"> 
              <a class="btn btn-danger btn-sm del_todoproducto" href="#" data-original-title="" title=""><i class="fa fa-trash"></i> Borra Productos </a> 
              <a class="btn bg-orange-active btn-sm color-palette btn-grad add_producto" href="#" data-original-title="" title=""><i class="fa fa-shopping-bag"></i> Añadir Producto </a>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div id="detmov" class="col-md-12 table-responsive" > 
                <table class="table table-bordered detmov table-responsive">
                  <tbody>
                    <tr>
                        <th class="text-center " style="width: 10px;">Nro</th>
                        <th class="text-center col-md-1">Cod Barra</th>
                        <th>Producto</th>
                        <?php if ($mostrarvalores == 1) { ?>
                        <th class="text-center col-md-1">Precio</th>
                        <th class="text-center col-md-1">Existencia</th>
                        <?php } ?>                       
                        <th class="text-center col-md-1">Cantidad</th>
                        <th class="text-center col-md-2" style="width: 144px;">Uni Medida</th>
                        <?php if ($mostrarvalores == 1) { ?>
                        <th class="text-center col-md-1">SubTotal</th>
                        <?php } ?>                       
                        <th class="text-center " style="width: 10px;">Acción</th>
                    </tr>
                    <?php 
                      $total = 0;                                                                            
                      $nro = 0; 
                      if(@$detmov != NULL){
                        if (count($detmov) > 0) {
                          foreach ($detmov as $dc):
                            $nro = $nro + 1;
                            $total = $total + @$dc->montototal;


                    ?>
                    <tr>
                        <!-- NRO -->
                        <td class="text-center"><?php print $nro; ?></td>
                        <!-- CODIGO DE BARRA -->
                        <td class="text-center"><?php print @$dc->pro_codigobarra; ?></td>
                        <!-- NOMBRE DEL PRODUCTO -->
                        <td class="text-left"><?php print @$dc->pro_nombre; ?></td>
                        <!-- PRECIO DEL PRODUCTO -->
                        <?php if ($mostrarvalores == 1) { ?>
                        <td class="text-center">
                          <input type="text" class="form-control text-center precio" name="" id="<?php print @$dc->id; ?>" value="<?php if(@$dc != NULL){ print @$dc->precio_compra; }?>" >
                        </td>
                        <!-- EXISTENCIA DEL PRODUCTO -->
                        <td class="text-center"><?php print @$dc->existencia; ?></td>
                        <?php } else { ?>                       
                          <input type="hidden" class="form-control text-center precio" name="" id="<?php print @$dc->id; ?>" value="<?php if(@$dc != NULL){ print @$dc->precio_compra; }?>" >
                        <?php } ?>                       
                        <!-- CANTIDAD -->
                        <td class="text-center">
                          <input type="text" class="form-control text-center cantidad" name="" id="<?php print @$dc->id; ?>" value="<?php if(@$dc != NULL){ print @$dc->cantidad; }?>" >
                        </td>
                        <!-- UNIDAD DE MEDIDA -->
                        <td class="text-center">
                            <select id="<?php print @$dc->id; ?>" name="cmb_proveedor" class="form-control unidadmedida">
                              <?php 
                              $unidad = &get_instance();
                              $unidad->load->model("Unidades_model");
                              $unimed = $unidad->Unidades_model->sel_unidadprod($dc->pro_id);
                              
                              if(@$unimed != NULL){ ?>
                                <option  value="0" selected="TRUE">Seleccione...</option>
                              <?php }  
                                if (count($unimed) > 0) {
                                  foreach ($unimed as $um): 
                                    if(@$dc->id_unimed == $um->id){ ?>
                                      <option  value="<?php print $um->id; ?>" selected="TRUE"><?php  print $um->nombrecorto; ?></option>
                                    <?php 
                                    }else{ ?>
                                      <option value="<?php  print $um->id; ?>" > <?php  print $um->nombrecorto; ?> </option>
                                    <?php 
                                    }
                                    ?>
                                  <?php
                                  endforeach;
                                } ?>
                            </select>                                    
                        </td>
                        <!-- SUBTOTAL -->
                        <?php if ($mostrarvalores == 1) { ?>
                        <td class="text-right"><?php if(@$dc != NULL){ print @$dc->montototal; }?></td>
                        <?php } ?>                       
                        <!-- ACCION -->
                        <td class="text-center">
                          <a href="#" title="Eliminar" id="<?php if(@$dc != NULL){ print @$dc->id; }?>" class="btn btn-danger btn-xs btn-grad promov_del"><i class="fa fa-trash-o"></i></a>
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
              <div class="row" style="margin-top:20px">
                <div class="col-md-6">
                </div>

                <div class="col-md-6">
                <!-- MONTOS DE PAGO -->                
                  <div class="pull-right calmonto ">
                    <label class="text-left"><strong>Total: $</strong></label>
                    <label id="mtotal" class="text-right"><strong><?php print number_format(@$total,2,",","."); ?></strong></label>
                  </div>

                </div>
                <div class="col-md-12">
                  <div class="pull-right"> 
                    <a id="guardar_compra" class="btn bg-green-active color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-save"></i> Guardar </a>
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

