<?php
/* ------------------------------------------------
  ARCHIVO: contab_comprobantes_add.php
  DESCRIPCION: Contiene la vista principal del módulo de contab_comprobantes_add.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Edición de Asiento Contable'</script>";
date_default_timezone_set("America/Guayaquil");

$cfgservicio = &get_instance();
$cfgservicio->load->model("Serviciotecnico_model");
$configservicio = $cfgservicio->Serviciotecnico_model->lst_configservicio();
$mostrarsecc_serie = $configservicio->habilita_serie;
$mostrarsecc_detalle = $configservicio->habilita_detalle;
$mostrarsecc_produtil = $configservicio->habilita_productoutilizado;
$mostrarsecc_abono = $configservicio->habilita_abono;
$mostrar_encargado = $configservicio->habilita_encargado;
$esproductoserie = $mostrarsecc_serie; 
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


  #tpcredito{
    display: none; 
  }  

    .autocomplete-jquery-results{
        border:1px solid silver;
        float:right;
        margin-top:2px;
        position:absolute;
        display: none;
        z-index: 999;
    }

    /*Esta clase se activa cuando el usuario se mueve por las sugerencias*/
    .autocomplete-jquery-mark{
        color:black;
        background-color: #E0F0FF !important;
    }

    /* Cada sugerencia va a llevar esta clase, por lo tanto tomara el estilo siguiente */
    .autocomplete-jquery-item{
        border-bottom: 1px solid lightgray;
        display: block;
        height: 25px;
        padding-top: 5px;
        text-decoration: none;     
        padding-left: 3px;  
        background-color: white;
    }
    .autocomplete-jquery-results{
         box-shadow: 1px 1px 3px black;
    }
    /* Al pasar por encima de las sugerencias*/
    .autocomplete-jquery-item:hover{
        background-color: #E0F0FF;
        color:black;
    }

    .tdvalor{
      width: 80px;
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

    $('#dataTableDet').dataTable({
      "language":{  "lengthMenu":"Mostrar _MENU_ registros por página.",
                    "zeroRecords": "Lo sentimos. No se encontraron registros.",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros aún.",
                    "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                    "search" : "Búsqueda",
                    "LoadingRecords": "Cargando ...",
                    "Processing": "Procesando...",
                    "SearchPlaceholder": "Comience a teclear...",
                    "paginate": { "previous": "Anterior",
                                  "next": "Siguiente", }
                  },
      'ajax': "listadoAsientos",
      'columns': [
          {"data": "ver"},
          {"data": "codigocuenta"},
          {"data": "descripcioncuenta"},
          {"data": "concepto"},
          {"data": "debito"},
          {"data": "credito"}
      ]
    });

    $(document).on('click', '#add_detalle', function(){
        var disabled = $(this).is('[disabled=disabled]');
        if (disabled == true) { return false; }
        $(this).attr('disabled', true);
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('contabilidad/contab_comprobante/add_detalle');?>",
          success: function(json) {
            var newid = json.resu;
            if (newid != 0){
              $('#dataTableDet').DataTable().ajax.reload();          
              $('.upd_cuenta[id='+ newid +']').focus();            
            }
          }
      });
    });

    var tmpid = 0;
    $(document).on('click', '.divcuenta', function() {
        tmpid = $(this).attr('id');
        $(this).autocomplete();
    });

    $(document).on('change', '.upd_cuenta', function() {
      var codcuenta = $(this).val(); 
      var id = tmpid.substring(10);
      if (codcuenta != ''){
        var sucursal = $('#cmb_sucursal').val(); 
        valida_cuenta(id, codcuenta, sucursal);
      }
      else{
        $('.desc_cuenta[id='+ id +']').html("");
        $('#add_detalle').attr('disabled', true);
      }
    });

    $(document).on('change', '.upd_concepto', function(){
        var id = $(this).attr('id');
        var valor = $(this).val();
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('contabilidad/contab_comprobante/actualiza_asiento_concepto');?>",
          data: {asiento: id, concepto: valor}
      });
    });

    /*$('.autocomplete').autocomplete();*/

    $(document).on('click', '.autocomplete-jquery-item', function(){  
      id = tmpid.substring(10);
      var codcuenta = $(this).text(); 
      if (codcuenta === ""){
        return false;
      }
      var sucursal = $('#cmb_sucursal').val(); 
      valida_cuenta(id, codcuenta, sucursal);
    });

    function valida_cuenta(id, codcuenta, sucursal){
      //alert("id " + id + " codcuenta " + codcuenta + " sucursal " + sucursal);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('contabilidad/contab_comprobante/busca_cuenta');?>",
            data: {
              codcuenta: codcuenta,
              sucursal: sucursal
            },
            success: function(json) {
                if (json.resu) {
                  $('.desc_cuenta[id='+ id +']').html(json.resu.descripcion);
                  $('#add_detalle').attr('disabled', false);
                  $.ajax({
                      type: "POST",
                      dataType: "json",
                      url: "<?php echo base_url('contabilidad/contab_comprobante/actualiza_asiento_cuenta');?>",
                      data: {
                        asiento: id,
                        idcuenta: json.resu.id,
                        codcuenta: json.resu.codigocuenta
                      }
                  });
                } 
                else{ 
                  //alert("Ingrese un codigo de cuenta válido.");
                  $('.desc_cuenta[id='+ id +']').html("");
                  $('#add_detalle').attr('disabled', true);
                  $('.divcuenta[id='+ id +']').focus();            
                }            
            }
        });
    }

    $(document).on('click', '.upd_debito, .upd_credito', function(){
        $(this).select();
    });

    $(document).on('change', '.upd_debito', function(){
        var id = $(this).attr('id');
        var valor = parseFloat($(this).val());
        $(this).val(valor.toFixed(2));
        actualiza_valor(id, valor, 1);
    });

    $(document).on('change', '.upd_credito', function(){
        var id = $(this).attr('id');
        var valor = parseFloat($(this).val());
        $(this).val(valor.toFixed(2));
        actualiza_valor(id, valor, 0);
    });

    function actualiza_valor(id, valor, esdebito){
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('contabilidad/contab_comprobante/actualiza_asiento_valor');?>",
          data: {asiento: id, valor: valor, esdebito: esdebito },
          success: function(json) {
            if (parseFloat(valor) != 0){
              if (esdebito == 1){
                $('.upd_credito[id='+ id +']').val('0.00');
              }
              else{
                $('.upd_debito[id='+ id +']').val('0.00');
              }
              $('#add_detalle').attr('disabled', false);
            }
            actualiza_totales();
          }
      });

    }

    $(document).on('click', '.del_detalle', function(){
        var id = $(this).attr('id');

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('contabilidad/contab_comprobante/del_asiento');?>",
          data: {id: id },
          success: function(json) {
            $('#dataTableDet').DataTable().ajax.reload();          
            actualiza_totales();
          }
      });
    });

    function actualiza_totales(){
      var totdebito = 0;
      $( ".upd_debito" ).each(function( index ) {
        totdebito = totdebito + parseFloat($( this ).val() );
      });
      $("#total_debito").val(totdebito.toFixed(2));
      var totcredito = 0;
      $( ".upd_credito" ).each(function( index ) {
        totcredito = totcredito + parseFloat($( this ).val() );
      });
      $("#total_credito").val(totcredito.toFixed(2));
    }

    $(document).on('click', '#guardar', function(){
        //ojo validar fecha en ejercicio
        var id = $("#txt_id").val();
        var sucursal = $("#cmb_sucursal").val();
        var fecha = $("#fecha").val();
        var tipo = $("#cmb_tipo").val();
        var monto = $("#txt_monto").val();
        if (monto == '') {monto = 0;}
        var referencia = $("#txt_referencia").val();
        var descripcion = $("#txt_descripcion").val();

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('contabilidad/contab_comprobante/guardar');?>",
          data: {id: id, sucursal: sucursal, tipo: tipo, fecha: fecha,
                 monto: monto, referencia: referencia, descripcion: descripcion },
          success: function(json) {
            location.replace(base_url + "contab_comprobantes");
          }
        });
    });

    ///actualiza_totales();

  }); 

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-columns"></i> Edición de Asiento Contable 
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>contab_comprobantes">Asientos Contables</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DEL PROVEEDOR -->        
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <div class="col-md-12" style="margin-bottom: 10px;">
              <h3 class="box-title"> Datos Generales </h3> 
                <input type="hidden" id="txt_id" name="txt_id" value="<?php if(@$obj != NULL){ print @$obj->id; } else {print 0;} ?>" >    

              <div class="pull-right"> 
                <a id="guardar" class="btn btn-sm bg-green-active color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-save"></i> Guardar</a>
              </div>               
            </div>     

            <!-- SUCURSAL  -->
            <div style="" class="form-group col-md-3 ">
             <div class="col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
              <label for="lb_res">Sucursal</label>
             </div> 
             <div class="col-md-9">
              <select id="cmb_sucursal" name="cmb_sucursal" class="form-control datogen">
              <?php 
                if(@$sucursales != NULL){ ?>
                <?php } else { ?>
                <option  value="" selected="TRUE">Seleccione...</option>
                <?php } 
                  if (count($sucursales) > 0) {
                    foreach ($sucursales as $item):
                        if(@$obj->idsucursal != NULL){
                            if($item->id_sucursal == $obj->idsucursal){ ?>
                                 <option value="<?php  print $item->id_sucursal; ?>" selected="TRUE"> <?php  print $item->nom_sucursal; ?> </option>
                                <?php
                            }else{ ?>
                                <option value="<?php  print $item->id_sucursal; ?>" > <?php  print $item->nom_sucursal; ?> </option>
                                <?php
                            }
                        }else{ ?>
                            <option value="<?php  print $item->id_sucursal; ?>" > <?php  print $item->nom_sucursal; ?> </option>
                            <?php
                            }   ?>
                        <?php
                    endforeach;
                  }
                ?>
              </select>          
             </div>                         
            </div>

            <!-- Orden -->
            <div class="form-group col-md-3 ">
             <div class="col-md-4" style="padding-right: 0px; ">
              <label>Número</label>
             </div>
             <div class="col-md-8">
              <input type="text" class="form-control validate[required] text-center" id="txt_nro" name="txt_nro" value="<?php if(@$obj != NULL){ print @$obj->numero; } else {print @$nuevaorden;} ?>" readonly>
             </div>
            </div>

            <div class="form-group col-md-3">
              <div class="col-md-3">
                <label for="">Fecha</label>
              </div>  
              <div class="col-md-9">
                <div style="margin-bottom: 0px;" class="form-group" >
                <div class="input-group date">
                  <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right validate[required] datogen" id="fecha" name="fecha" value="<?php if(@$cliente != NULL){ $fec =  str_replace('-', '/', $cliente->fechaasiento); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec;} else { $fec = date("d/m/Y"); print $fec;} ?>">
                </div>                             
                </div>
              </div>
            </div>  

            <!-- Tipo Asiento  -->
            <div style="" class="form-group col-md-3 ">
              <div class="col-md-3" style="padding-left: 0px; margin-left: 0px;">
                <label for="lb_res">Tipo</label>
              </div> 
              <div class="col-md-9">
                <select id="cmb_tipo" name="cmb_tipo" class="form-control validate[required] datogen">
                <?php 
                  if(@$tipoasiento != NULL){ ?>
                  <?php } else { ?>
                  <option  value="" selected="TRUE">Seleccione...</option>
                  <?php } 
                    if (count($tipoasiento) > 0) {
                      foreach ($tipoasiento as $item):
                          if(@$obj->idtipocomprobante != NULL){
                              if($item->id == $obj->idtipocomprobante){ ?>
                                  <option value="<?php  print $item->id; ?>" selected="TRUE"> <?php  print $item->nombre; ?> </option>
                                  <?php
                              }else{ ?>
                                  <option value="<?php  print $item->id; ?>" > <?php  print $item->nombre; ?> </option>
                                  <?php
                              }
                          }else{ ?>
                              <option value="<?php  print $item->id; ?>" > <?php  print $item->nombre; ?> </option>
                              <?php
                              }   ?>
                          <?php
                      endforeach;
                    }
                  ?>
                </select>          
              </div>                         
            </div>

            <div class="form-group col-md-3" >
              <label for="txt_monto" class="control-label col-md-4" style="padding-left: 0px; margin-left: 0px; padding-right: 0px;">Monto</label>             
              <div class="col-md-8" style="padding-left: 0px; padding-right: 0px;">
                <input type="text" class="form-control validate[required] " name="txt_monto" id="txt_monto" placeholder="Monto" value="<?php if(@$obj != NULL){ print @$obj->monto; }?>"  >
              </div>
            </div>

            <div class="form-group col-md-3" >
              <label for="txt_referencia" class="control-label col-md-4" style="padding-left: 0px; margin-left: 0px; ">Referencia</label>             
              <div class="col-md-8" style="padding-left: 0px; padding-right: 0px;">
                <input type="text" class="form-control validate[required] " name="txt_referencia" id="txt_referencia" placeholder="Referencia" value="<?php if(@$obj != NULL){ print @$obj->referencia; }?>" readonly>
              </div>
            </div>

            <div class="form-group col-md-6" >
              <label for="txt_descripcion" class="control-label col-md-2" style="padding-left: 0px; margin-left: 0px; ">Descripción</label>             
              <div class="col-md-10" style="padding-left: 0px; padding-right: 0px;">
                <input type="text" class="form-control validate[required] " name="txt_descripcion" id="txt_descripcion" placeholder="Descripción" value="<?php if(@$obj != NULL){ print @$obj->descripcion; }?>" >
              </div>
            </div>

          </div>

          <hr class="linea"> 

        </div>

      </div>


    <!-- Detalle de Asiento -->
      <div class="col-md-12" style="padding-bottom: 1px;">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"> Detalle de Asientos </h3> 

            <div class="pull-right"> 
              <a id="add_detalle" class="btn btn-sm btn-primary color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-plus-square"></i> Adicionar Detalle</a>
            </div>            

            <div id="detalletmp" class="box-body table-responsive">
              <!-- <table class="table table-bordered table-striped "> -->
              <table id="dataTableDet" class="table table-bordered table-striped ">
                <thead>
                  <tr >
                      <th>Acción</th> 
                      <th>Código Cuenta</th>
                      <th>Descripción Cuenta</th>
                      <th>Concepto</th>
                      <th>Débito</th>
                      <th>Crédito</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>

            <div class="form-group col-md-12 col-md-offset-6" >
              <label class="control-label col-md-1" style="margin-left: 10px; margin-right: 10px;">Totales</label>             
              <div class="col-md-1" style="margin-left: 30px; padding-left: 0px; padding-right: 0px;">
                <input type="text" class="form-control text-right" id="total_debito" value="<?php if(@$totaldebito != NULL){ print number_format(@$totaldebito,2); }?>" readonly>
              </div>
              <div class="col-md-1" style="margin-left: 30px; padding-left: 0px; padding-right: 0px;">
                <input type="text" class="form-control text-right" id="total_credito" value="<?php if(@$totalcredito != NULL){ print number_format(@$totalcredito,2); }?>" readonly>
              </div>
            </div>


          </div>


        </div>



      </div>           
    </div>
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

