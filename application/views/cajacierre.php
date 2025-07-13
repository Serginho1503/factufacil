<?php
/* ------------------------------------------------
  ARCHIVO: cajaapertura.php
  DESCRIPCION: Contiene la vista de cierre de caja.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Cierre de Caja'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<style type="text/css">

    .form-control{
        font-size: 12px;
        height: 28px;
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

    .padcero{
        padding: 0px;
    }

</style>

<script>
$( document ).ready(function() {

    $("#frm_caja").validationEngine();
    $(document).on('click', '.guardarcaja', function(){
        $(".guardarcaja").css("display", "none");
        /* Id de la apertura de la caja */
        var idmov = $('.addegreso').attr('id');
        /* Datos Balance de Caja */
        var ventastotales = $("#txt_efectivo").val();
        var abonoservicio = $("#txt_abonoservicio").val();
        var abonocredito = $("#txt_abonocredito").val();
        var montonoefectivo = $("#txt_tarjeta").val();
        var montoegreso = $("#txt_egresos").val();
        var saldo = $("#txt_saldo").val();
        var totalcaja = $("#txt_totalcaja").val();
        var sobrante = $("#txt_sobrante").val();
        var faltante = $("#txt_faltante").val();
        /* Desglose por Formas de Pago */
        var desefectivo = $("#1").val();
        var descheque = $("#2").val();
        if(descheque == '' || descheque == null){ descheque = 0; }        
        var destarcre = $("#3").val();
        var destardeb = $("#4").val();
        var destarpre = $("#5").val();
        if(destarpre == '' || destarpre == null){ destarpre = 0; }
        var destransf = $("#6").val();
        if(destransf == '' || destransf == null){ destransf = 0; }        
        var desdinele = $("#7").val();
        if(desdinele == '' || desdinele == null){ desdinele = 0; }         
        var desotros = $("#8").val();
        if(desotros == '' || desotros == null){ desotros = 0; }         
        var desvencre = $("#txt_vencre").val();
        var obs = $("#txt_nota").val();
        if(ventastotales == '' || montonoefectivo == '' || totalcaja == '' ){
            alert("faltan datos");
            return false;
        }else{
            $.ajax({
                type: "POST",
                dataType: "json",
                url: base_url + "Cajacierre/guardar",
                data: { idmov: idmov,
                        ventastotales: ventastotales,
                        abonoservicio: abonoservicio,
                        montonoefectivo: montonoefectivo,
                        montoegreso: montoegreso,
                        saldo: saldo,
                        totalcaja: totalcaja,
                        sobrante: sobrante,
                        faltante: faltante,
                        desefectivo: desefectivo,
                        descheque: descheque,
                        destarcre: destarcre,
                        destardeb: destardeb,
                        destarpre: destarpre,
                        destransf: destransf,
                        desdinele: desdinele,
                        desotros: desotros,
                        desvencre: desvencre,
                        obs: obs,
                        abonocredito: abonocredito
                    },
                success: function(json) {
                    var idmov = json;
                    if(idmov > 0){ swal('La Caja se Cerró, se Enviará el correo'); }
                    $.blockUI({ message: '<h1> Enviando Correo ...</h1>' });
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: base_url + "Cajacierre/envmail",
                        data: { idmov: idmov },
                        success: function(json) {
                            $.unblockUI();
                            if(json == 1){
                                swal('El Correo fue Enviado');
                                location.replace("<?php print $base_url;?>Inicio");    
                            }else{
                                swal('Error al enviar El Correo'); 
                                location.replace("<?php print $base_url;?>cajamov");                                                 
                            }
                            
                        }
                    });  
                   
                }

            });            
        }  
    });
/*
    $(document).on('click', '.guardarcaja', function(){

        // Id de la apertura de la caja 
        var idmov = $('.addegreso').attr('id');
        // Datos Balance de Caja 
        var venta = $("#txt_efectivo").val();
        var tarjeta = $("#txt_tarjeta").val();
        var egresos = $("#txt_egresos").val();
        var totalcaja = $("#txt_totalcaja").val();
        var obs = $("#txt_nota").val();

        if(venta == '' || tarjeta == '' || totalcaja == '' ){
            alert("faltan datos");
            return false;
        }else{
            $.blockUI({ message: '<h1> Por favor espere...</h1>' });
            $.ajax({
              type: "POST",
              dataType: "json",
              url: base_url + "Cajacierre/guardar",
              data: {   venta: venta, 
                        tarjeta: tarjeta, 
                        egresos: egresos, 
                        totalcaja: totalcaja,
                        obs: obs,
                        idmov: idmov  
                    },
              success: function(json) {
                $.unblockUI();
                alert('Los Datos Fueron Actualizados');
                location.replace("<?php print $base_url;?>Inicio");
              }    
            });
           }    
    });
*/


    /* Exportar a Excel */
    $(document).on('click', '#imprimir', function(){  
        /* Id de la apertura de la caja */
        var idmov = $('.addegreso').attr('id');
        /* Datos de Apertura */
        var fecha_apertura = $("#txt_nombre").val();
        var monto_apertura = $("#txt_monto").val();
        /* Datos Balance de Caja */
        var venta = $("#txt_efectivo").val();
        var tarjeta = $("#txt_tarjeta").val();
        var egresos = $("#txt_egresos").val();
        var saldo = $("#txt_saldo").val();
        var totalcaja = $("#txt_totalcaja").val();
        var sobrante = $("#txt_sobrante").val();
        var faltante = $("#txt_faltante").val();
        var obs = $("#txt_nota").val();

        var abonoservicio = $("#txt_abonoservicio").val();
        var abonocredito = $("#txt_abonocredito").val();

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('cajacierre/tmp_cierre');?>",
            data: { fecha_apertura : fecha_apertura,
                    monto_apertura : monto_apertura,
                    venta: venta, 
                    tarjeta: tarjeta, 
                    egresos: egresos, 
                    saldo: saldo, 
                    totalcaja: totalcaja,
                    sobrante: sobrante,
                    faltante: faltante,
                    obs: obs,
                    idmov: idmov,
                    abonoservicio: abonoservicio, 
                    abonocredito: abonocredito
                },
            success: function(json) {
                  if (parseInt(json.resu) == 1) {
                     location.replace("<?php print $base_url;?>cajacierre/exportarexcel");
                  } else {
                     alert("Error de conexión");
                  }
            }
        });
    });

      /* AGREGAR ALMACEN */
    $(document).on('click', '.addegreso', function(){
        var idmov = $(this).attr('id');
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST",
             data: { idmov : idmov }
          },
          href: "<?php echo base_url('cajacierre/addegreso');?>" 
        });
    });

      /* AGREGAR ALMACEN */
    $(document).on('click', '.edi_cg', function(){
        var idmov = $('.addegreso').attr('id');
        var idreg = $(this).attr('id');        
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST",
             data: { idmov : idmov, idreg : idreg }
          },
          href: "<?php echo base_url('cajacierre/ediaddegreso');?>" 
        });
    });

    $(document).on('click','.del_cg', function() {
        var idmov = $('.addegreso').attr('id');        
        var idreg = $(this).attr("id");
        if (conf_del()) {
            $.ajax({
                url: "<?php print $base_url;?>cajacierre/delcajaegreso",
                data: {idreg: idreg, idmov: idmov},
                type: 'POST',
                dataType: 'json',
                success: function(json) {
                    $('.cajaegreso').load(base_url + "cajacierre/actualiza_cajaegreso");
                    $('#txt_egresos').val(json);
                    actualizamonto();
                }
            });
      }
      return false; 
    });


    function conf_del() {
        return  confirm("¿Confirma que desea eliminar este registro?");
    }


    $(document).on('click', '.guarda', function(){ 

        var monto = $('#txt_salida').val();
        var desc = $('#txt_justi').val();
        var idmov = $('#txt_mov').val();
        var idreg = $('#txt_reg').val();
        var emi = $('#txt_emi').val();        
        var rec = $('#txt_rec').val();
        if(monto == 0 || monto == ""){
            alert("Verifique el Monto");
            return false;
        }
        if(desc == 0 || desc == ""){
            alert("Escriba una Descripción");
            return false;
        }        

        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "cajacierre/guardaegreso",
            data: { monto: monto, desc: desc, idmov: idmov, idreg: idreg, emi:emi, rec:rec },
            success: function(json) {
                $('.cajaegreso').load(base_url + "cajacierre/actualiza_cajaegreso");
                monto_cajaegreso();
                $.fancybox.close();
                actualizamonto();
            }
        });        

    });    

    $(document).on('click', '.imp_cg', function(){
      var id = $(this).attr('id'); // alert(id);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "cajacierre/nrocg_tmp",
            data: { id: id },
            success: function(json) {
              $.fancybox.open({
                type:'iframe',
                width: 800,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST",
                   data: {id: id}
                },
                href: base_url + 'cajacierre/cgpdf' 
              });
            }
        });
    });


    function monto_cajaegreso(){
            var idmov = $('#txt_mov').val();
            $.ajax({
                type: "POST",
                dataType: "json",
                url: base_url + "cajacierre/monto_cajaegreso",
                data: { idmov: idmov },
                success: function(json) {
                  $('#txt_egresos').val(json);
                  actualizamonto();
                }
          });
    }

    /* ACTUALIZAR LISTADO DE GASTOS POR RAGO DE FECHA */
    $('.actualiza').change(function(){
        var caja = $('#cmb_caja option:selected').val(); 
    /*    alert(caja);*/
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "cajacierre/tmp_cierrecaja",
            data: { id: caja },
            success: function(json) {
                location.replace("<?php print $base_url;?>cajacierre");
            }
        });

    });

  /*  updcaja();*/
});

    function updcaja(){
        var caja = $('#cmb_caja option:selected').val(); 
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "cajacierre/tmp_cierrecaja",
            data: { id: caja },
            success: function(json) {
                
            }
        });
    }

    $(document).on('blur', '.cantidad', function(){    
        var val = $(this).val(); 
        if (val == ''){
            $(this).val('0'); 
        }
        actualizamonto();
    });

    function actualizamonto(){
        var monto = parseFloat($("#txt_billete100").val()) * 100;
        monto += parseFloat($("#txt_billete50").val()) * 50;
        monto += parseFloat($("#txt_billete20").val()) * 20;
        monto += parseFloat($("#txt_billete10").val()) * 10;
        monto += parseFloat($("#txt_billete5").val()) * 5;
        monto += parseFloat($("#txt_billete1").val());

        monto += parseFloat($("#txt_moneda100").val());
        monto += parseFloat($("#txt_moneda50").val()) * 0.5;
        monto += parseFloat($("#txt_moneda25").val()) * 0.25;
        monto += parseFloat($("#txt_moneda10").val()) * 0.1;
        monto += parseFloat($("#txt_moneda5").val()) * 0.05;
        monto += parseFloat($("#txt_moneda1").val()) * 0.01;

        var strmonto = monto.toFixed(2);

        var tmpvalor = $("#txt_monto").val().replace(',','');      
        var tmpsaldo = parseFloat(tmpvalor);
        tmpvalor = $("#txt_ingresoefectivo").val().replace(',','');      
        tmpsaldo += parseFloat(tmpvalor);

//        tmpvalor = $("#txt_abonocreditoefectivo").val().replace(',','');      
//        tmpsaldo += parseFloat(tmpvalor);

//        tmpvalor = $("#txt_tarjeta").val().replace(',','');      
//        tmpsaldo -= parseFloat(tmpvalor);

        var tmpsalida = $("#txt_egresos").val().replace(',','');      
        tmpsaldo -= parseFloat(tmpsalida);

        tmpsaldo = tmpsaldo.toFixed(2);
        $("#txt_saldo").val(tmpsaldo);

        var dif = parseFloat($("#txt_saldo").val());

        $("#txt_sobrante").val("0.00");        
        $("#txt_faltante").val("0.00");        
        //alert("monto " + monto + "  saldo " + dif);
        if (monto > dif){
            dif = (monto - dif).toFixed(2);        
            $("#txt_sobrante").val(dif);
        } else {
            dif = (dif - monto).toFixed(2);        
            $("#txt_faltante").val(dif);        
        }

        $("#txt_totalcaja").val(strmonto);
    }


</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       <i class="fa fa-fort-awesome"></i> Cierre de Caja </a></li>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>cajaapertura">Cierre de Caja</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- SECCION DEL FORMULARIO-->
            <div class="col-md-12" style="padding-right: 5px;">
                <!-- general form elements -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        
                        <!-- Caja -->
                        <div style="" class="form-group col-md-3">
                         <div class="col-md-3">
                          <label for="lb_res" >Caja</label>
                         </div> 
                         <div class="col-md-9">
                          <select id="cmb_caja" name="cmb_caja" class="form-control actualiza">
                          <?php 
                            if(@$cajas != NULL){ ?>
                            <?php } else { ?>
                            <option  value="" selected="TRUE">Seleccione Caja...</option>
                            <?php } 
                              if (count($cajas) > 0) {
                                foreach ($cajas as $obj):
                                    if(@$caja->id_caja != NULL){
                                        if($obj->id_caja == $caja->id_caja){ ?>
                                             <option value="<?php  print $obj->id_caja; ?>" selected="TRUE"> <?php  print $obj->nom_caja; ?> </option>
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $obj->id_caja; ?>" > <?php  print $obj->nom_caja; ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $obj->id_caja; ?>" > <?php  print $obj->nom_caja; ?> </option>
                                        <?php
                                        }   ?>
                                    <?php
                                endforeach;
                              }
                            ?>
                          </select>          

                         </div> 
                        </div>


                        <!-- Fecha Apertura -->
                        <div class="form-group col-md-4">
                            <label for="txt_nombre" class="col-sm-7" style="padding-right: 0px; padding-left: 0px;">Fecha/hora Apertura</label>
                            <div id="menid" class="col-sm-5" style="padding-right: 0px;">
                                <input type="text" class="form-control validate[required]" name="txt_nombre" id="txt_nombre" placeholder="" value="<?php $date=date_create($caja->fecha_apertura); print date_format($date,'d/m/Y H:i');?>" readonly>
                            </div>
                        </div>
                        <!-- Monto Apertura -->
                        <div class="form-group col-md-4">
                            <label for="txt_monto" class="col-sm-5" style="padding-right: 0px; padding-left: 0px;">Monto Apertura</label>
                            <div id="menid" class="col-sm-4" style="padding-right: 0px; padding-left: 0px;">
                                <input type="text" class="form-control validate[required]" name="txt_monto" id="txt_monto" placeholder="Monto de Apertura" value="<?php print number_format($caja->monto_apertura,2);?>" readonly>
                            </div>
                        </div>                        

                        <div class="pull-right col-md-1" style="padding-right: 0px; padding-left: 0px;"> 

                          <a id="imprimir" class="btn bg-green-active color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa  fa-file-excel-o"></i> Exportar</a>
                        </div>

                    </div>

                  <!--   <form role="form"> -->
             
                <!--    <form id="frm_caja" name="frm_caja" method="post" role="form" class="form" action="<?php // echo base_url('cajacierre/guardar');?>">-->
                        <div class="box-body">
                            <div class="form-group col-md-12">

                                <div class="col-xs-2">
                                    <h4 class="box-title">Billetes</h4>
                                    <div class="box box-danger">
                                        <div class="box-body">
                                            <div class="row">
                                                <!--  Cantidad Billetes de 100  -->
                                                <div class="form-group col-md-12 padcero" >
                                                    <label class="col-md-4 control-label text-right">100</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="currency" class="form-control text-center cantidad" name="txt_billete100" id="txt_billete100" placeholder="" value="0" >
                                                        </div>
                                                    </div>
                                                </div>                                            
                                                <!--  Cantidad Billetes de 50  -->
                                                <div class="form-group col-md-12 padcero">
                                                    <label class="col-md-4 control-label text-right">50</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center cantidad" name="txt_billete50" id="txt_billete50" placeholder="" value="0" >
                                                        </div>
                                                    </div>
                                                </div>                                            
                                                <!--  Cantidad Billete de 20  -->
                                                <div class="form-group col-md-12 padcero">
                                                    <label class="col-md-4 control-label text-right">20</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center cantidad" name="txt_billete20" id="txt_billete20" placeholder="" value="0" >
                                                        </div>
                                                    </div>
                                                </div>                                            
                                                <!--  Cantidad billete de 10  -->
                                                <div class="form-group col-md-12 padcero">
                                                    <label class="col-md-4 control-label text-right">10</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center cantidad" name="txt_billete10" id="txt_billete10" placeholder="" value="0">
                                                        </div>
                                                    </div>
                                                </div>                                            
                                                <!--  Cantidad billete de 5  -->
                                                <div class="form-group col-md-12 padcero">
                                                    <label class="col-md-4 control-label text-right">5</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center cantidad" name="txt_billete5" id="txt_billete5" placeholder="" value="0">
                                                        </div>
                                                    </div>
                                                </div>                                            
                                                <!--  Cantidad billete de 1  -->
                                                <div class="form-group col-md-12 padcero">
                                                    <label class="col-md-4 control-label text-right">1</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center cantidad" name="txt_billete1" id="txt_billete1" placeholder="" value="0" >
                                                        </div>
                                                    </div>
                                                </div>                                            

                                            </div>
                                        </div>
                                    </div>                                        

                                </div>

                                <div class="col-xs-2">
                                    <h4 class="box-title">Monedas</h4>                                
                                    <div class="box box-danger">
                                        <div class="box-body">
                                            <div class="row">
                                                <!--  Cantidad Moneda de 1  -->
                                                <div class="form-group col-md-12 padcero">
                                                    <label class="col-md-4 control-label text-right">1.00</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center cantidad" name="txt_moneda100" id="txt_moneda100" placeholder="" value="0" >
                                                        </div>
                                                    </div>
                                                </div>                                            
                                                <!--  Cantidad Moneda de 50  -->
                                                <div class="form-group col-md-12 padcero">
                                                    <label class="col-md-4 control-label text-right">0.50</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center cantidad" name="txt_moneda50" id="txt_moneda50" placeholder="" value="0" >
                                                        </div>
                                                    </div>
                                                </div>                                            
                                                <!--  Cantidad Moneda de 25  -->
                                                <div class="form-group col-md-12 padcero">
                                                    <label class="col-md-4 control-label text-right">0.25</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center cantidad" name="txt_moneda25" id="txt_moneda25" placeholder="" value="0">
                                                        </div>
                                                    </div>
                                                </div>                                            
                                                <!--  Cantidad Moneda de 10  -->
                                                <div class="form-group col-md-12 padcero">
                                                    <label class="col-md-4 control-label text-right">0.10</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center cantidad" name="txt_moneda10" id="txt_moneda10" placeholder="" value="0" >
                                                        </div>
                                                    </div>
                                                </div>                                            
                                                <!--  Cantidad Moneda de 5  -->
                                                <div class="form-group col-md-12 padcero">
                                                    <label class="col-md-4 control-label text-right">0.05</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center cantidad" name="txt_moneda5" id="txt_moneda5" placeholder="" value="0" >
                                                        </div>
                                                    </div>
                                                </div>                                            
                                                <!--  Cantidad Moneda de 1  -->
                                                <div class="form-group col-md-12 padcero">
                                                    <label class="col-md-4 control-label text-right">0.01</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center cantidad" name="txt_moneda1" id="txt_moneda1" placeholder="" value="0" >
                                                        </div>
                                                    </div>
                                                </div>                                            

                                            </div>
                                        </div>
                                    </div>                                        
                                </div>

                                <div class="col-xs-4">
                                    <h4 class="box-title">Balance de Caja</h4>                                
                                    <div class="box box-danger">
                                        <div class="box-body">
                                            <div class="row">
                                                <!-- Ventas en Efectivo -->
                                                <div class=" col-md-12" style="padding-left: 0px;">
                                                    <label class="col-md-8 control-label text-left">Ventas Totales</label>
                                                    <div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center" name="txt_efectivo" id="txt_efectivo" placeholder="" value="<?php print number_format($caja->ingreso,2);?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Abono servicios -->
                                                <div class=" col-md-12" style="padding-left: 0px;">
                                                    <label class="col-md-8 control-label text-left">Abonos de Servicios</label>
                                                    <div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center" name="txt_abonoservicio" id="txt_abonoservicio" placeholder="" value="<?php print number_format($caja->abonoservicio,2);?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Abono creditos -->
                                                <div class=" col-md-12" style="padding-left: 0px;">
                                                    <label class="col-md-8 control-label text-left">Abono Crédito Pendiente</label>
                                                    <div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center" name="txt_abonocredito" id="txt_abonocredito" placeholder="" value="<?php print number_format($caja->abonocredito,2);?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Monto Tarjetas -->
                                                <div class=" col-md-12" style="padding-left: 0px;">
                                                    <label class="col-md-8 control-label text-left">Monto (No Efectivo)</label>
                                                    <div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center" name="txt_tarjeta" id="txt_tarjeta" placeholder="" value="<?php print number_format($caja->ingresonoefectivo,2);?>" readonly onchange="actualizamonto();">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- MONTO DE EGRESOS -->
                                                <div class="form-group col-md-12" style="padding-left: 0px;">
                                                    <label class="col-md-8 control-label text-left">Monto Egresos</label>
                                                    <div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center" name="txt_egresos" id="txt_egresos" placeholder="" value="<?php if(@$egreso != NULL){ print @$egreso; } else { print "0"; } ?>" readonly onchange="actualizamonto();">
                                                        </div>
                                                    </div>
                                                </div>                                                
                                                <!-- Saldo -->
                                                <div class=" col-md-12" style="padding-left: 0px;">
                                                    <label class="col-md-8 control-label text-left">Saldo</label>
                                                    <div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">
                                                        <div class="input-group">
                                                            <input type="hidden" id="txt_ingresoefectivo" value="<?php print number_format($caja->ingresoefectivo,2);?>" >                                                            
															<input type="text" class="form-control text-center" name="txt_saldo" id="txt_saldo" placeholder="" value="<?php print number_format($caja->monto_apertura+$caja->ingresoefectivo-$egreso,2);?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Total Existente -->
                                                <div class=" col-md-12" style="padding-left: 0px;">
                                                    <label class="col-md-8 control-label text-left">Total Caja</label>
                                                    <div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center" name="txt_totalcaja" id="txt_totalcaja" placeholder="" value="0" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Sobrante -->
                                                <div class=" col-md-12" style="padding-left: 0px;">
                                                    <label class="col-md-8 control-label text-left">Sobrante</label>
                                                    <div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center" name="txt_sobrante" id="txt_sobrante" placeholder="" value="0" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Faltante -->
                                                <div class=" col-md-12" style="padding-left: 0px;">
                                                    <label class="col-md-8 control-label text-left">Faltante</label>
                                                    <div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center" name="txt_faltante" id="txt_faltante" placeholder="" value="<?php print number_format($caja->monto_apertura+$caja->ingresoefectivo-$egreso,2);?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                        
                                </div>

                                <div class="col-xs-4">
                                    <h4 class="box-title">Desglose por Formas de Pago</h4>                                
                                    <div class="box box-danger">
                                        <div class="box-body">
                                            <div class="row">
                                                <!-- Ventas en Efectivo -->
                                                <div class="form-group col-md-12" style="padding-left: 0px;">

                                                    <?php  
                                                        foreach ($desgloseformapago as $objforma):
                                                    ?>
                                                        <label class="col-md-8 control-label text-left"><?php print $objforma->nombre_formapago; ?></label>
                                                        <div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">
                                                            <div class="input-group">
                                                                <input id="<?php print $objforma->id_formapago; ?>" name="<?php print $objforma->id_formapago; ?>" type="text" class="form-control text-center" value="<?php print number_format($objforma->monto,2);?>" readonly>
                                                            </div>
                                                        </div>
                                                    <?php
                                                        endforeach;
                                                    ?>                                 
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                        

                                    <label class="col-md-8 control-label text-left">Venta a Crédito</label>
                                    <div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">
                                        <div class="input-group">
                                            <input id="txt_vencre" name="txt_vencre" type="text" class="form-control text-center" value="<?php if(@$caja->credito != NULL){ print @$caja->credito; } else { print "0.00"; } ?>" readonly>
                                        </div>
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-12">
                               
                                <div class="box box-danger">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"></i> Egresos de Caja</h3>
                                        <div class="pull-right"> 
                                            <button id="<?php print $caja->id_mov; ?>" type="button" class="btn bg-green-active color-palette btn-grad addegreso" >
                                                <i class="fa fa-plus-square"></i> Añadir
                                            </button>   
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            
                                            <div class="col-md-12 cajaegreso table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <tr>
                                                      <th style="width: 10px">#</th>
                                                      <th>Descripción Salida</th>
                                                      <th style="width: 100px">Cantidad</th>
                                                      <th style="width: 200px">Emisor</th>
                                                      <th style="width: 200px">Receptor</th>
                                                      <th style="width: 40px">Acción</th>
                                                    </tr>
                                                    <?php 
                                                      $nro = 0;
                                                      if (count($cajag) > 0) {
                                                        foreach ($cajag as $cg):
                                                          $nro = $nro + 1;
                                                     ?>   <tr>
                                                            <td><?php print $nro; ?></td>
                                                            <td><?php print substr($cg->descripcion, 0, 80); ?></td>
                                                            <td class="text-right"><?php print $cg->monto; ?></td>
                                                            <td><?php print $cg->emisor; ?></td>
                                                            <td><?php print $cg->receptor; ?></td>
                                                            <td>
                                                              <div class="text-center">
                                                                <a href="#" title="Editar" id="<?php print $cg->idreg ?>" class="btn btn-success btn-xs btn-grad edi_cg"><i class="fa fa-pencil-square-o"></i></a> 
                                                                <a href="#" title="Eliminar" id="<?php print $cg->idreg  ?>" class="btn btn-danger btn-xs btn-grad del_cg"><i class="fa fa-trash-o"></i></a>
                                                                <a href="#" title="Imprimir" id="<?php print $cg->idreg ?>" class="btn bg-navy color-palette btn-xs btn-grad imp_cg"><i class="fa fa-print"></i></a>
                                                              </div>
                                                            </td>
                                                          </tr>
                                                    <?php
                                                        endforeach;
                                                      }
                                                    ?>
                                                </table>                                            
                                            </div>
                                            <div class="col-md-2"></div>
                                        </div>
                                    </div>                                        
                                </div>                                            
                            
                            </div>

                                                                                                             

                            </div>   


                        <div class="form-group col-md-12">
                            <label for="txt_nombre">Observaciones</label>
                            <textarea id="txt_nota" name="txt_nota" class="form-control" rows="3" placeholder="Ingrese los Observaciones ..."></textarea>
                        </div>

                        <div  align="center" class="box-footer">
                            <div class="form-actions ">
                                <button type="submit" class="btn btn-success btn-grad btn-lg no-margin-bottom guardarcaja">
                                    <i class="fa fa-save "></i> Guardar
                                </button>

                            </div>
                        </div>
                <!--   </form>  -->
                </div>
            </div>

        </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->
                <script src="public/js/lib/sweetalert.min.js"></script>
                <link rel="stylesheet" type="text/css" href="public/js/lib/sweetalert.css">

