<?php
/* ------------------------------------------------
  ARCHIVO: cajaapertura.php
  DESCRIPCION: Contiene la vista de cierre de caja.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Empresa'</script>";
date_default_timezone_set("America/Guayaquil");

$montoapertura = $cierre->montoapertura;
$ingresos = $cierre->ingresos;
$gastos = $cierre->gastos;
$compras = $cierre->compras;

$montocierre = $montoapertura + $ingresos - $gastos - $compras;
$faltante = 0;
$sobrante = 0;



?>
<script>
$( document ).ready(function() {
    $("#frm_caja").validationEngine();

    $('#fecha').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
    });
    $('#fecha').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    function toDate(dateStr) {
    var parts = dateStr.split("/")
    return new Date(parts[2], parts[1] - 1, parts[0])
    }

    $(document).on('change', '#fecha', function(){  
      var fechac = $("#fecha").val();
      var fechap = $("#fechap").val();
      dateApe = toDate(fechap);
      dateCie = toDate(fechac);
      if (dateApe.getTime() > dateCie.getTime()){
          alert("La fecha de cierre no puede ser menor que la fecha de apertura");
          $("#fecha").val(fechap);
      }
    });   



    /* Exportar a Excel */
    $(document).on('click', '#imprimir', function(){  
      //alert("aqui toy");  
      var fecha_apertura = $("#txt_nombre").val();
      var monto_apertura = $("#txt_monto").val();
      var venta = $("#txt_efectivo").val();
      var tarjeta = $("#txt_tarjeta").val();
    /*  var egresos = $("#txt_egresos").val();
      var compra = $("#txt_compra").val(); */
      var egresos = 0;
      var compra = 0;      
      var totalcaja = $("#txt_totalcaja").val();
      var notacaja = $("#txt_nota").val();
      //alert(venta + ' ' + tarjeta + ' ' + totalcaja);
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('cajacierre/tmp_cierre');?>",
        data: { fecha_apertura : fecha_apertura,
                monto_apertura : monto_apertura,
                venta: venta, 
                tarjeta: tarjeta, 
                egresos: egresos, 
                compra: compra, 
                totalcaja: totalcaja,
                notacaja: notacaja 
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

    $(document).on('change', '#cmb_caja', function(){  

      var caja = $("#cmb_caja").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('cajacierre/actualiza_caja_cierre');?>",
        data: { caja : caja},
        success: function(json) {
            var actualizo = 0;
            if (json != NULL) {
                if (json.cierre != NULL) {
                   actualizo = 1;
                   $("#txt_apertura").val(json.cierre.montoapertura); 
                   $("#txt_ingreso").val(json.cierre.ingresos); 
                   var totalingreso = parseFloat(json.cierre.montoapertura) + parseFloat(json.cierre.ingresos);
                   $("#txt_totalingreso").val(totalingreso.toFixed(2)); 
                   $("#txt_gasto").val(json.cierre.gastos); 
                   $("#txt_compra").val(json.cierre.compras); 
                   var totalegreso = parseFloat(json.cierre.gastos) + parseFloat(json.cierre.compras);
                   $("#txt_totalegreso").val(totalegreso.toFixed(2)); 
                   var totalcaja = totalingreso - totalegreso;
                   $("#txt_totalcaja").val(totalcaja.toFixed(2));                                                                                                
                }
            }    
            if (actualizo == 0){
                $("#txt_apertura").val("0.00"); 
                $("#txt_ingreso").val("0.00"); 
                $("#txt_totalingreso").val("0.00"); 
                $("#txt_gasto").val("0.00"); 
                $("#txt_compra").val("0.00"); 
                $("#txt_totalegreso").val("0.00"); 
                $("#txt_totalcaja").val("0.00");                                                                                                
            }
        }
      });
    });


});

function actualizamonto(){

    var totalcaja = parseFloat($("#txt_totalcaja").val());
    var montoapertura = <?php print $montoapertura; ?>;
    var ingresos = <?php print $ingresos; ?>;
    var gastos = <?php print $gastos; ?>;
    var compras = <?php print $compras; ?>;
    var montocierre = <?php print $montocierre; ?>;

    var sobrante = 0;
    var faltante = 0;

    if(totalcaja > montocierre){
        sobrante = totalcaja - montocierre; 
        sobrante = sobrante.toFixed(2);   
    }else{
        faltante = montocierre - totalcaja;
        faltante = faltante.toFixed(2);
    }

    $("#txt_sobrante").val(sobrante);        
    $("#txt_faltante").val(faltante);         

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
            <div class="col-md-1"></div>

            <div class="col-md-9" style="padding-right: 5px;">
                <!-- general form elements -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cierre de Caja Chica </h3>

                    </div>

                  <!--   <form role="form"> -->
             
                    <form id="frm_caja" name="frm_caja" method="post" role="form" class="form" action="<?php print base_url('cajachica/guardar_cierre');?>">
                        <div class="box-body">
 
                            <div class="form-group col-md-12">
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

                             <div class="form-group col-md-3" style="padding-left: 0px;">
                                <label >Fecha Apertura</label>
                                <div >
                                    <div class="input-group date ">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" class="form-control " id="fechap" value="<?php $fecha=$cierre->fechaapertura; $fecha = date("d/m/Y", strtotime($fecha)); print $fecha; ?>" readonly>
                                    </div>
                                </div>
                             </div>                                

                             <!-- <div class="pull-right"> 
                                <a id="imprimir" class="btn bg-green-active color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-shopping-bag"></i> Exportar a Excel</a>
                             </div> -->

                            </div>  

                            <div class="form-group col-md-6" style="padding-left: 0px; padding-right: 0px;">    
                                <!-- Monto Apertura -->
                                <div class="form-group col-md-12" style="padding-left: 0px;">
                                    <label class="col-md-6 control-label text-left">Monto Apertura</label>
                                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" name="txt_apertura" id="txt_apertura" placeholder="" value="<?php  print number_format($cierre->montoapertura,2);?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- Monto Ingresos -->
                                <div class="form-group col-md-12" style="padding-left: 0px;">
                                    <label class="col-md-6 control-label text-left">Monto Ingresos</label>
                                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" name="txt_ingreso" id="txt_ingreso" placeholder="" value="<?php print number_format($cierre->ingresos,2);?>" readonly >
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                $totalcaja = $cierre->montoapertura + $cierre->ingresos;
                                ?>
                                <!-- Total Ingresos -->
                                <div class="form-group col-md-12" style="padding-left: 0px;">
                                    <label class="col-md-6 control-label text-left">Total Ingresos</label>
                                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" name="txt_totalingreso" id="txt_totalingreso" placeholder="" value="<?php print number_format($totalcaja,2);?>" readonly >
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12" style="padding-left: 0px;">
                                    <label class="col-md-6 control-label text-left">Fecha Cierre</label>
                                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                      <div class="input-group date ">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" class="form-control pull-right validate[required]" id="fecha" name="fecha" value="<?php print  date("d/m/Y"); ?>">
                                      </div>
                                    </div>
                                </div>                                


                            </div>


                            <div class="form-group col-md-6">
                                <!-- Saldo -->
                                <div class="form-group col-md-12" style="padding-left: 0px;">
                                    <label class="col-md-6 control-label text-left">Monto Gastos</label>
                                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" name="txt_gasto" id="txt_gasto" placeholder="" value="<?php print number_format($cierre->gastos,2);?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- Total Existente -->
                                <div class="form-group col-md-12" style="padding-left: 0px;">
                                    <label class="col-md-6 control-label text-left">Monto Compras</label>
                                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" name="txt_compra" id="txt_compra" placeholder="" value="<?php print number_format($cierre->compras,2);?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                $totalegreso = $cierre->gastos + $cierre->compras;
                                ?>

                                <div class="form-group col-md-12" style="padding-left: 0px;">
                                    <label class="col-md-6 control-label text-left">Total Egresos</label>
                                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" name="txt_totalegreso" id="txt_totalegreso" placeholder="" value="<?php print number_format($totalegreso,2);?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12" style="padding-left: 0px;">
                                    <label class="col-md-6 control-label text-left">Monto Cierre</label>
                                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" name="txt_totalcaja" id="txt_totalcaja" placeholder="" value="<?php print number_format($totalcaja - $totalegreso,2);?>" readonly>
                                        </div>
                                    </div>
                                </div>

                            </div>  

                            <div class="form-group col-md-12">
                                <label for="txt_nombre">Observaciones</label>
                                <textarea id="txt_obs" name="txt_obs" class="form-control" rows="3" placeholder="Ingrese las Observaciones ..."></textarea>
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

