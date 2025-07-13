<?php
/* ------------------------------------------------
  ARCHIVO: Area.php
  DESCRIPCION: Contiene la vista principal del módulo de Area.
  FECHA DE CREACIÓN: 04/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Cláusulas'</script>";
date_default_timezone_set("America/Guayaquil");

?>

<script type='text/javascript' language='javascript'>

  $(document).ready(function(){
    $("#formCLA").validationEngine();

    $(document).on("submit", "#formCLA", function() {
      var data = $(this).serialize();
      if (conf_guar()) {
        $.ajax({
          url: $(this).attr("action"),
          data: data,
          type: 'POST',
          dataType: 'json',
          success: function(json) {
            alert("Los Cambios Fueron Guardados");
            location.reload();
          }
        });
      }
      return false;
    }); 

    function conf_guar() {
      return  confirm("¿Confirma que desea guardar este registro?");
    }

  });



</script>

<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-legal" aria-hidden="true"></i> Cláusulas
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger">
            <form id="formCLA" name="formCLA" method='POST' action="<?php echo base_url('clausula/guardar_cla');?>" onSubmit='return false' >
              <div class="box-header with-border"> <h3 class="box-title"></i> Cláusulas de Garantía</h3> </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-xs-2 "></div>
                  <div class="col-xs-8">
                    <div class="box-body">
                      <div class="form-group">
                        <label>Descripción de las Cláusulas</label>
                        <textarea id="clausulas" name="clausulas" class="form-control validate[required]" rows="15" placeholder="Cláusulas..."><?php print $clausula; ?></textarea>
                      </div>                    
                    </div>
                  </div>
                  <div class="col-xs-2 "> </div>
                </div>
              </div>
              <div  align="center" class="box-footer">
                <div class="form-actions ">
                    <button type="submit" class="btn btn-danger btn-grad no-margin-bottom ">
                    <i class="fa fa-save "></i> Guardar
                </button>
                </div>              
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
</div>