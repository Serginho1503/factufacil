<?php
/* ------------------------------------------------
  ARCHIVO: cajachica_apertura.php
  DESCRIPCION: Contiene la vista de apertura de caja chica.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Apertura de Caja Chica'</script>";
date_default_timezone_set("America/Guayaquil");
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

    $(document).on('change', '#fecha', function(){  
      var caja = $("#cmb_caja").val();
      var fecha = $("#fecha").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('cajachica/validar_fechaapertura');?>",
        data: { caja: caja, fecha : fecha},
        success: function(json) {
              if (parseInt(json.resu) == 0) {
                var tmpDate = new Date(json.ultimafecha);
                var strDate = tmpDate.getDate() + '/' + (tmpDate.getMonth() + 1) + '/' + tmpDate.getFullYear();
                alert("La fecha de apertura debe ser posterior a " + strDate);

                var day = 60 * 60 * 24 * 1000;
                var newDate = new Date(tmpDate.getTime() + day);

                var strDate = newDate.getDate() + '/' + (newDate.getMonth() + 1) + '/' + newDate.getFullYear();
                $("#fecha").val(strDate);
              }
        }
      });
    });   
});


</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       <i class="fa fa-cubes"></i> Caja Chica </a></li>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
       
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
                        <h3 class="box-title">Apertura de Caja Chica</h3>
                    </div>
                    <form id="frm_caja" name="frm_caja" method="post" role="form" class="form" action="<?php echo base_url('cajachica/guardar_apertura');?>">
                        <div class="box-body">

                            <!-- Caja -->
                            <div style="" class="form-group col-md-5">
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

                            <div class="form-group col-md-4" style="padding-left: 0px;">
                                <label >Fecha Apertura</label>
                                <div class="input-group date ">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" class="form-control pull-right validate[required]" id="fecha" name="fecha" value="<?php print  date("d/m/Y"); ?>">
                                </div>
                            </div>                                

                            <div class="form-group col-md-3">
                              <label>Monto Apertura</label>
                              <input type="numeric" class="form-control validate[required] text-right" name="txt_monto" id="txt_monto" placeholder="Monto de Apertura" value="0.00">
                            </div> 

                            <div class="form-group col-md-12">
                              <label>Descripcion</label>
                              <input type="text" class="form-control" name="txt_descripcion" id="txt_descripcion" placeholder="Descripcion de la Apertura" value="">
                            </div> 

                          </div>  

                        </div>

                        <div  align="center" class="box-footer">
                            <div class="form-actions ">
                                <button type="submit" class="btn btn-success btn-sm btn-grad btn-lg no-margin-bottom">
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

