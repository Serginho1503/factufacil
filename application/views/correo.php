<?php
/* ------------------------------------------------
  ARCHIVO: Correo.php
  DESCRIPCION: Contiene la vista principal del módulo de Correo.
  FECHA DE CREACIÓN: 25/09/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Correo'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<script>
$( document ).ready(function() {
    $("#frm_correo").validationEngine();

    $(document).on('click', '.correo_test', function(){
        var smtp = $('#txt_smtp').val(); 
        var puerto = $('#txt_puerto').val(); 
        var usuario = $('#txt_user').val(); 
        var clave = $('#txt_pwd').val(); 

        $.blockUI({ message: '<h3> Probando envío de correo ...</h3>' });
        $.ajax({
              type: "POST",
              dataType: "json",
              url: base_url + "Correo/correo_test",
              data: { smtp: smtp, puerto: puerto, usuario: usuario, clave: clave },
              success: function(json) {
                $.unblockUI();
                if (json == 1) { 
                  alert("Prueba de correo terminada de forma exitosa");
                }
                else{
                  alert("No se pudo completar la prueba de envío de corro");
                }  
              }
        });
    });



});

</script>

<div class="content-wrapper">

    <section class="content-header">
      <h1>
       <i class="fa fa-envelope"></i> Configuración de Correo Electrónico</a></li>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>correo">Correo</a></li>
      </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-9" style="padding-right: 5px;">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Parametros</h3>
                    </div>
                    <form id="frm_correo" name="frm_correo" method="post" role="form" class="form" action="<?php echo base_url('Correo/guardar');?>">
                        <div class="box-body">
                            <div class="form-group col-md-6">
                                <label for="">Servidor SMTP</label>
                                <input type="text" class="form-control validate[required]" name="txt_smtp" id="txt_smtp" placeholder="Ej... smtp.googlemail.com" value="<?php if(@$correo != NULL){ print @$correo->smtp; }?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Puerto</label>
                                <input type="text" class="form-control validate[required]" name="txt_puerto" id="txt_puerto" placeholder="Ej... 465" value="<?php if(@$correo != NULL){ print @$correo->puerto; }?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Usuario</label>
                                <input type="email" class="form-control validate[required]" name="txt_user" id="txt_user" placeholder="Ej... correo@gmail.com" value="<?php if(@$correo != NULL){ print @$correo->usuario; }?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Contraseña</label>
                                <input type="password" class="form-control validate[required]" name="txt_pwd" id="txt_pwd" placeholder="Ej... **********" value="<?php if(@$correo != NULL){ print @$correo->clave; }?>">
                            </div>
                        </div>
                        <div  align="center" class="box-footer">
                            <div class="form-actions ">
                                <button type="button" class="btn btn-info btn-grad  no-margin-bottom correo_test">
                                    <i class="fa fa-save "></i> Probar
                                </button>

                                <button type="submit" class="btn btn-success btn-grad  no-margin-bottom">
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


