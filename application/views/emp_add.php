<?php
/* ------------------------------------------------
  ARCHIVO: Sucursal.php
  DESCRIPCION: Contiene la vista principal del módulo de Sucursal.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Empresa'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<script>
$( document ).ready(function() {
    $("#frm_add").validationEngine();
    
    $('#fecha').datepicker();
    $('#fecha').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });


});

</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-university"></i> Empresa
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>empresa">Empresa</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- SECCION DEL FORMULARIO-->
            <form id="frm_add" name="frm_add" method="post" role="form" class="form" enctype="multipart/form-data" action="<?php echo base_url('Empresa/guardar');?>">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Datos de la Empresa</h3>
                    </div>
                    <div class="box-body">
                      <div class="row">

                        <div class="col-xs-3 text-center">
                            <h3 class="profile-username text-center">Logotipo</h3>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-preview thumbnail"  id="fotomostrar">
                                    <img  width="150" height="150"<?php
                                        if (@$empresa != NULL) {
                                            if (trim($empresa->logo_path) != '') {
                                                ?>
                                                src="<?php print base_url(); ?>public/img/empresa/<?php print $empresa->logo_path; ?>" <?php
                                                
                                            } else {
                                                ?>
                                                src="<?php print base_url(); ?>public/img/perfil.jpg" <?php
                                            }
                                        } else {
                                    ?>
                                            src="<?php print base_url(); ?>public/img/perfil.jpg" <?php }
                                        ?> alt="" onerror="this.src='<?php print base_url() . "public/img/perfil.jpg"; ?>';" />

                                </div>
                                <div>
                                <br>
                                    <span class="btn btn-file btn-success">
                                        <span class="fileupload-new">Imagen</span>
                                        <span class="fileupload-exists">Cambiar</span>
                                        <input type="file"  id="logo" name="logo" accept="image/*" /> 
                                    </span>
                                    <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Quitar</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-9">
                            <div class="col-xs-12">
                            <!-- CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) -->                                    
                                <input type="hidden" id="txt_idemp" name="txt_idemp" value="<?php if(@$empresa != NULL){ print $empresa->id_emp; } else {print 0;} ?>" >    
                                <input type="hidden" id="old_logo" name="old_logo" value="<?php if(@$empresa != NULL){ print $empresa->logo_path; } ?>" >    
                            </div> 

                            <!-- Nombrede la Usuario -->
                            <div class="form-group col-md-8">
                              <div class="col-md-2">
                                <label for="lb_nom">Nombre</label>
                              </div>  
                              <div class="col-md-10">
                                <input type="text" class="form-control validate[required]" name="txt_nom" id="txt_nom" placeholder="Nombre de la Empresa" value="<?php if(@$empresa != NULL){ print @$empresa->nom_emp; }?>">
                              </div>  
                            </div>

                            <!-- RUC -->
                            <div class="form-group col-md-4">
                              <div class="col-md-2">
                                <label for="lb_nom">RUC</label>
                              </div>  
                              <div class="col-md-10">
                                <input type="text" class="form-control validate[required]" name="txt_ruc" id="txt_ruc" placeholder="RUC de la Empresa" value="<?php if(@$empresa != NULL){ print @$empresa->ruc_emp; }?>">
                              </div>  
                            </div>

                            <!-- razon social -->
                            <div class="form-group col-md-8">
                              <div class="col-md-3">
                                <label for="lb_nom">Razón Social</label>
                              </div>  
                              <div class="col-md-9">
                                <input type="text" class="form-control validate[required]" name="txt_razon" id="txt_razon" placeholder="Razon Social de la Empresa" value="<?php if(@$empresa != NULL){ print @$empresa->raz_soc_emp; }?>">
                              </div>  
                            </div>

                            <!-- Codigo -->
                            <div class="form-group col-md-4">
                              <div class="col-md-3">
                                <label for="lb_enca">Código</label>
                              </div>  
                              <div class="col-md-9">
                                <input type="text" class="form-control " name="txt_codigo" id="txt_codigo" placeholder="Codigo de la Empresa" value="<?php if(@$empresa != NULL){ print @$empresa->cod_emp; }?>">
                              </div>  
                            </div>

                            <!-- Correo del Usuario -->
                            <div class="form-group col-md-5">
                              <div class="col-md-2">
                                <label for="txt_email">Email</label>
                              </div>  
                              <div class="col-md-10">
                                <input type="text" class="form-control " id="txt_email" name="txt_email" placeholder="
                                    Email de la Empresa" value="<?php if(@$empresa != NULL){ print @$empresa->ema_emp; }?>">
                              </div>      
                            </div>

                            <!-- Teléfono del Usuario -->
                            <div class="form-group col-md-4" style="padding: 0px;">
                              <div class="col-md-3">
                                <label>Teléfono</label>
                              </div>  
                              <div class="col-md-9">
                                <div class="input-group">
<!--                                     <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
 -->                                    <input type="text" class="form-control " id="txt_telefono" name="txt_telefono" data-inputmask='"mask": "(999) 999-9999"' data-mask value="<?php if(@$empresa != NULL){ print @$empresa->tlf_emp; }?>">
                                </div>
                              </div>  
                            </div> 

                            <!-- fax -->
                            <div class="form-group col-md-3" style="padding-left: 0px;">
                              <div class="col-md-2">
                                <label>Fax:</label>
                              </div>  
                              <div class="col-md-10">
                                <div class="input-group">
<!--                                     <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
 -->                                    <input type="text" class="form-control " id="txt_fax" name="txt_fax" data-inputmask='"mask": "(999) 999-9999"' data-mask value="<?php if(@$empresa != NULL){ print @$empresa->fax_emp; }?>">
                                </div>
                              </div>  
                            </div> 

                            <div class="form-group col-md-12">
                              <div class="col-md-2">
                                <label for="txt_apellido">Dirección</label>
                              </div>  
                              <div class="col-md-10" style="padding-left: 0px;">
                                <input type="text" class="form-control " name="txt_dir" id="txt_dir" placeholder="Dirección de la Empresa" value="<?php if(@$empresa != NULL){ print @$empresa->dir_emp; }?>">
                              </div>  
                            </div>

                            <div class="form-group col-md-6">
                              <div class="col-md-4">
                                <label for="txt_apellido">Representante</label>
                              </div>  
                              <div class="col-md-8">
                                <input type="text" class="form-control " name="txt_rep" id="txt_rep" placeholder="Representante de la Empresa" value="<?php if(@$empresa != NULL){ print @$empresa->rep_emp; }?>">
                              </div>  
                            </div>

                            <!-- Apellido del Usuario -->
                            <div class="form-group col-md-6">
                              <div class="col-md-4">
                                <label for="txt_apellido">Sitio Web</label>
                              </div>  
                              <div class="col-md-8">
                                <input type="text" class="form-control " name="txt_web" id="txt_web" placeholder="Sitio Web de la Empresa" value="<?php if(@$empresa != NULL){ print @$empresa->web_emp; }?>">
                              </div>  
                            </div>

                              <div class="form-group col-md-6">
                              <div class="col-md-4">
                                <label for="txt_apellido">Régimen</label>
                              </div>  
                              <div class="col-md-8">
                                <input type="text" class="form-control " name="txt_regimen" id="txt_regimen" placeholder="Régimen Contribuyente Microempresa" value="<?php if(@$empresa != NULL){ print @$empresa->regimen_emp; }?>">
                              </div>  
                            </div>

                            <div class="form-group col-md-12">
                              <div class="col-md-3">
                                <label for="txt_apellido">Firma Electrónica</label>
                              </div>  
                              <div class="col-md-9">
                                <input type="text" class="form-control " name="old_token" id="old_token" value="<?php if(@$empresa != NULL){ print @$empresa->tokenfirma; }?>" readonly>
                              </div>  
                              <div class="col-md-12">
                                <input type="file" name="tokenfile" size="255" value="<?php if(@$empresa != NULL){ print @$empresa->tokenfirma; }?>"/>
                              </div>  
                            </div>

                            <div class="form-group has-feedback col-md-8">
                              <div class="col-md-3">
                                <label for="txt_apellido">Contraseña</label>
                              </div>  
                              <div class="col-md-9" style="padding: 0px;">
                                <input type="password" name="txt_pass" id="txt_pass" class="form-control" placeholder="Clave" value="<?php if(@$empresa != NULL){ print @$empresa->contrasena; }?>">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                              </div>  
                            </div>

                            <div class="form-group col-md-6 " style="padding-left: 30px;">
                                <input id="chk_obligadocontabilidad" name="chk_obligadocontabilidad" type="checkbox" <?php if(@$empresa != NULL){ if(@$empresa->obligadocontabilidad == 1){ print " checked";} }  ?>  > <strong>Obligado a llevar Contabilidad</strong>
                            </div>

                        </div>

                      </div>
                    </div>
                    <!-- /.box-body -->
                    <div  align="center" class="box-footer">
                        <div class="form-actions ">
                            <button type="submit" class="btn btn-success btn-grad no-margin-bottom">
                                <i class="fa fa-save "></i> Guardar
                            </button>
                        </div>
                    </div>
                </div>
              <!-- /.box -->
            </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

