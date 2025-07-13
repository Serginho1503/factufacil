<?php
/* ------------------------------------------------
  ARCHIVO: cajaapertura.php
  DESCRIPCION: Contiene la vista de apertura de caja.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
date_default_timezone_set("America/Guayaquil");
?>
<style>
#contenido_apertura{
    width: 600px;
}
#ui-datepicker-div
    {
        z-index: 9999999  !important;
    } 

</style>
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

        $("#txt_monto").val(strmonto);
    }

</script>
<!-- Content Wrapper. Contains page content -->

            <!-- SECCION DEL FORMULARIO-->
            <div id = "contenido_apertura" class="col-md-12">
                <!-- general form elements -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Apertura de Caja</h3>
                    </div>
                  <!--   <form role="form"> -->
             
                    <form id="frm_caja" name="frm_caja" method="post" role="form" class="form" action="<?php echo base_url('facturar/guardaraperturacaja');?>">
                        <div class="box-body">

                            <!-- Fecha Apertura -->
                            <div class="form-group col-md-6">
                                <label for="txt_nombre">Fecha</label>
                                <input type="text" class="form-control validate[required]" name="txt_nombre" id="txt_nombre" placeholder="" value="<?php print date('d/m/Y H:i');?>" readonly>
                            </div>
                            <!-- Monto Apertura -->
                            <div class="form-group col-md-6">
                                <label for="txt_monto">Monto</label>
                                <input type="text" class="form-control validate[required]" name="txt_monto" id="txt_monto" placeholder="Monto de Apertura" value="0.00" readonly>
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
                                                        <input type="text" class="form-control text-center" name="txt_billete100" id="txt_billete100" placeholder="" value="0" onchange="actualizamonto();">
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

