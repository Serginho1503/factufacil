<?php
/* ------------------------------------------------
  ARCHIVO: cajaapertura.php
  DESCRIPCION: Contiene la vista de cierre de caja.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
print "<script>document.title = 'FACTUFÁCIL - Empresa'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<script>
$( document ).ready(function() {
    $("#frm_caja").validationEngine();
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
    tmpvalor = $("#txt_efectivo").val().replace(',','');      
    tmpsaldo += parseFloat(tmpvalor);
    tmpvalor = $("#txt_tarjeta").val().replace(',','');      
    tmpsaldo -= parseFloat(tmpvalor);
    tmpvalor = $("#txt_compra").val().replace(',','');      
    tmpsaldo -= parseFloat(tmpvalor);
    tmpvalor = $("#txt_egresos").val().replace(',','');      
    tmpsaldo -= parseFloat(tmpvalor);
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
                        <h3 class="box-title">Cierre de Caja</h3>
                    </div>
                  <!--   <form role="form"> -->
             
                    <form id="frm_caja" name="frm_caja" method="post" role="form" class="form" action="<?php echo base_url('cajacierre/guardar');?>">
                        <div class="box-body">
                        <div class="form-group col-md-8">
                            <!-- Fecha Apertura -->
                            <div class="form-group col-md-6">
                                <label for="txt_nombre">Fecha y hora de Apertura</label>
                                <input type="text" class="form-control validate[required]" name="txt_nombre" id="txt_nombre" placeholder="" value="<?php $date=date_create($caja->fecha_apertura); print date_format($date,'d/m/Y H:i');?>" readonly>
                            </div>
                            <!-- Monto Apertura -->
                            <div class="form-group col-md-6">
                                <label for="txt_monto">Monto de Apertura</label>
                                <input type="text" class="form-control validate[required]" name="txt_monto" id="txt_monto" placeholder="Monto de Apertura" value="<?php print number_format($caja->monto_apertura,2);?>" readonly>
                            </div>

                            <div class="col-xs-6">
                                <h4 class="box-title">Billetes</h4>
                                <div class="box box-danger">
                                    <div class="box-body">
                                        <div class="row">
                                            <!--  Cantidad Billetes de 100  -->
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">100</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="currency" class="form-control text-center" name="txt_billete100" id="txt_billete100" placeholder="" value="0" onchange="actualizamonto();">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <!--  Cantidad Billetes de 50  -->
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">50</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-center" name="txt_billete50" id="txt_billete50" placeholder="" value="0" onchange="actualizamonto();">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <!--  Cantidad Billete de 20  -->
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">20</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-center" name="txt_billete20" id="txt_billete20" placeholder="" value="0" onchange="actualizamonto();">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <!--  Cantidad billete de 10  -->
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">10</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-center" name="txt_billete10" id="txt_billete10" placeholder="" value="0" onchange="actualizamonto();">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <!--  Cantidad billete de 5  -->
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">5</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-center" name="txt_billete5" id="txt_billete5" placeholder="" value="0" onchange="actualizamonto();">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <!--  Cantidad billete de 1  -->
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">1</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-center" name="txt_billete1" id="txt_billete1" placeholder="" value="0" onchange="actualizamonto();">
                                                    </div>
                                                </div>
                                            </div>                                            

                                        </div>
                                    </div>
                                </div>                                        

                            </div>

                            <div class="col-xs-6">
                                <h4 class="box-title">Monedas</h4>                                
                                <div class="box box-danger">
                                    <div class="box-body">
                                        <div class="row">
                                            <!--  Cantidad Moneda de 1  -->
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">1.00</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-center" name="txt_moneda100" id="txt_moneda100" placeholder="" value="0" onchange="actualizamonto();">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <!--  Cantidad Moneda de 50  -->
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">0.50</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-center" name="txt_moneda50" id="txt_moneda50" placeholder="" value="0" onchange="actualizamonto();">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <!--  Cantidad Moneda de 25  -->
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">0.25</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-center" name="txt_moneda25" id="txt_moneda25" placeholder="" value="0" onchange="actualizamonto();">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <!--  Cantidad Moneda de 10  -->
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">0.10</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-center" name="txt_moneda10" id="txt_moneda10" placeholder="" value="0" onchange="actualizamonto();">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <!--  Cantidad Moneda de 5  -->
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">0.05</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-center" name="txt_moneda5" id="txt_moneda5" placeholder="" value="0" onchange="actualizamonto();">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <!--  Cantidad Moneda de 1  -->
                                            <div class="form-group col-md-12">
                                                <label class="col-md-6 control-label text-right">0.01</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-center" name="txt_moneda1" id="txt_moneda1" placeholder="" value="0" onchange="actualizamonto();">
                                                    </div>
                                                </div>
                                            </div>                                            

                                        </div>
                                    </div>
                                </div>                                        
                            </div>
                        </div>    
                        <div class="form-group col-md-4" style="padding-left: 0px; padding-right: 0px;">    
                            <!-- Ventas en Efectivo -->
                            <div class="form-group col-md-12" style="padding-left: 0px;">
                                <label class="col-md-6 control-label text-left">Ventas Totales</label>
                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="txt_efectivo" id="txt_efectivo" placeholder="" value="<?php print number_format($caja->ingreso,2);?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Monto Tarjetas -->
                            <div class="form-group col-md-12" style="padding-left: 0px;">
                                <label class="col-md-6 control-label text-left">Monto Tarjetas</label>
                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="txt_tarjeta" id="txt_tarjeta" placeholder="" value="" onchange="actualizamonto();">
                                    </div>
                                </div>
                            </div>
                            <!-- Compras -->
                            <div class="form-group col-md-12" style="padding-left: 0px;">
                                <label class="col-md-6 control-label text-left">Compras</label>
                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="txt_compra" id="txt_compra" placeholder="" value="<?php print number_format($caja->compras,2);?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Egresos -->
                            <div class="form-group col-md-12" style="padding-left: 0px;">
                                <label class="col-md-6 control-label text-left">Egresos</label>
                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="txt_egresos" id="txt_egresos" placeholder="" value="<?php print number_format($caja->egresos,2);?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Saldo -->
                            <div class="form-group col-md-12" style="padding-left: 0px;">
                                <label class="col-md-6 control-label text-left">Saldo</label>
                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="txt_saldo" id="txt_saldo" placeholder="" value="<?php print number_format($caja->monto_apertura+$caja->ingreso-$caja->compras-$caja->egresos,2);?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Total Existente -->
                            <div class="form-group col-md-12" style="padding-left: 0px;">
                                <label class="col-md-6 control-label text-left">Total Existente</label>
                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="txt_totalcaja" id="txt_totalcaja" placeholder="" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Sobrante -->
                            <div class="form-group col-md-12" style="padding-left: 0px;">
                                <label class="col-md-6 control-label text-left">Sobrante</label>
                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="txt_sobrante" id="txt_sobrante" placeholder="" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Faltante -->
                            <div class="form-group col-md-12" style="padding-left: 0px;">
                                <label class="col-md-6 control-label text-left">Faltante</label>
                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="txt_faltante" id="txt_faltante" placeholder="" value="<?php print number_format($caja->monto_apertura+$caja->ingreso-$caja->compras-$caja->egresos,2);?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div  align="center" class="box-footer">
                            <div class="form-actions ">
                                <button type="submit" class="btn btn-danger btn-grad btn-lg no-margin-bottom">
                                    <i class="fa fa-save "></i> Guardar
                                </button>
                            </div>
                        </div>
                   </form> 
                </div>
            </div>

        </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

