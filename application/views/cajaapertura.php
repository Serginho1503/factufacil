<?php
/* ------------------------------------------------
  ARCHIVO: cajaapertura.php
  DESCRIPCION: Contiene la vista de apertura de caja.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Apertura de Caja'</script>";
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

    $("#txt_monto").val(strmonto);
}

</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       <i class="fa fa-fort-awesome"></i> Apertura de Caja </a></li>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- SECCION DEL FORMULARIO-->
            <div class="col-md-9" style="padding-right: 5px;">
                <!-- general form elements -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Apertura de Caja</h3>
                    </div>
                  <!--   <form role="form"> -->
             
                    <form id="frm_caja" name="frm_caja" method="post" role="form" class="form" action="<?php echo base_url('cajaapertura/guardar');?>">
                        <div class="box-body">

                            <!-- Caja -->
                            <div style="" class="form-group col-md-4">
                              <label for="lb_res">Caja</label>
                              <select id="cmb_caja" name="cmb_caja" class="form-control">
                              <?php 
                                if(@$cajas != NULL){ ?>
                                <?php } else { ?>
                                <option  value="" selected="TRUE">Seleccione Caja...</option>
                                <?php } 
                                  if (count($cajas) > 0) {
                                    foreach ($cajas as $obj): ?>
                                        <option value="<?php  print $obj->id_caja; ?>" > <?php  print $obj->nom_caja; ?> </option>
                                <?php
                                    endforeach;
                                  }
                                ?>
                              </select>                                  
                            </div>

                            <!-- Fecha Apertura -->
                            <div class="form-group col-md-4">
                                <label for="txt_nombre">Fecha</label>
                                <input type="text" class="form-control validate[required]" name="txt_nombre" id="txt_nombre" placeholder="" value="<?php print date('d/m/Y H:i');?>" readonly>
                            </div>
                            <!-- Monto Apertura -->
                            <div class="form-group col-md-4">
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
                                <button type="submit" class="btn btn-success btn-grad no-margin-bottom">
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

