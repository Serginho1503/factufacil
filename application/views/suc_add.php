<?php
/* ------------------------------------------------
  ARCHIVO: Sucursal.php
  DESCRIPCION: Contiene la vista principal del módulo de Sucursal.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Sucursal'</script>";
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
        <i class="fa fa-university"></i> Sucursal
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>sucursal">Sucursal</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- SECCION DEL FORMULARIO-->
            <form id="frm_add" name="frm_add" method="post" role="form" class="form" enctype="multipart/form-data" action="<?php echo base_url('sucursal/guardar');?>">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Datos de la Sucursal </h3>
                    </div>
                    <div class="box-body">
                      <div class="row">

                        <div class="nav-tabs-custom">
                          <ul class="nav nav-tabs">
                           <li class="active"><a href="#tabgeneral" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> GENERAL</a></li>                            
                           <li ><a href="#tablogo" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> LOGOS</a></li>                            
                          </ul>

                          <div class="tab-content">
                            <div class="tab-pane active" id="tabgeneral">

                                <div class="col-xs-12">
                                    <div class="col-xs-12">
                                    <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                                        if(@$suc != NULL){ ?>
                                            <input type="hidden" id="txt_idsuc" name="txt_idsuc" value="<?php if($suc != NULL){ print $suc->id_sucursal; }?>" >    
                                        <?php } else { ?>
                                            <input type="hidden" id="txt_idsuc" name="txt_idsuc" value="0">    
                                    <?php } ?> 
                                    </div> 

                                    <!-- Nombrede la Usuario -->
                                    <div class="form-group col-md-4">
                                        <label for="lb_nom">Nombre</label>
                                        <input type="text" class="form-control validate[required]" name="txt_nom" id="txt_nom" placeholder="Nombre de la Sucursal" value="<?php if(@$suc != NULL){ print @$suc->nom_sucursal; }?>">
                                    </div>

                                    <!-- Empresa -->
                                    <div style="" class="form-group col-md-4">
                                      <label for="lb_res">Empresa</label>
                                      <select id="cmb_empresa" name="cmb_empresa" class="form-control">
                                      <?php 
                                        if(@$empresas != NULL){ ?>
                                        <?php } else { ?>
                                        <option  value="" selected="TRUE">Seleccione Empresa...</option>
                                        <?php } 
                                          if (count($empresas) > 0) {
                                            foreach ($empresas as $obj):
                                                if(@$suc->id_empresa != NULL){
                                                    if($obj->id_emp == $suc->id_empresa){ ?>
                                                         <option value="<?php  print $obj->id_emp; ?>" selected="TRUE"> <?php  print $obj->nom_emp; ?> </option>
                                                        <?php
                                                    }else{ ?>
                                                        <option value="<?php  print $obj->id_emp; ?>" > <?php  print $obj->nom_emp; ?> </option>
                                                        <?php
                                                    }
                                                }else{ ?>
                                                    <option value="<?php  print $obj->id_emp; ?>" > <?php  print $obj->nom_emp; ?> </option>
                                                    <?php
                                                    }   ?>
                                                <?php
                                            endforeach;
                                          }
                                        ?>
                                      </select>                                  
                                    </div>

                                    <!-- Apellido del Usuario -->
                                    <div class="form-group col-md-4">
                                        <label for="lb_enca">Encargado</label>
                                        <input type="text" class="form-control " name="txt_enca" id="txt_enca" placeholder="Encargado de la Sucursal" value="<?php if(@$suc != NULL){ print @$suc->enca_sucursal; }?>">
                                    </div>
                                    <!-- Teléfono del Usuario -->
                                    <div class="form-group col-md-6">
                                        <label>Telefono:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <input type="text" class="form-control" id="txt_telefono" name="txt_telefono" data-inputmask='"mask": "(999) 999-9999"' data-mask value="<?php if(@$suc != NULL){ print @$suc->telf_sucursal; }?>">
                                        </div>
                                    </div> 
                                    <!-- Correo del Usuario -->
                                    <div class="form-group col-md-6">
                                        <label for="txt_email">Email</label>
                                        <input type="text" class="form-control " id="txt_email" name="txt_email" placeholder="Email de la Sucursal" value="<?php if(@$suc != NULL){ print @$suc->mail_sucursal; }?>">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="txt_apellido">Dirección</label>
                                        <input type="text" class="form-control " name="txt_dir" id="txt_dir" placeholder="Dirección de la Sucursal" value="<?php if(@$suc != NULL){ print @$suc->dir_sucursal; }?>">
                                    </div>

                                    <!-- Contador Retencion Compra -->
                                    <div class="form-group col-md-3">
                                        <label for="contretencion">Consecutivo Orden Servicio</label>
                                        <input type="text" class="form-control validate[required] " name="txt_ordenservicio" id="txt_ordenservicio" placeholder="Consecutivo de Orden de Servicio" value="<?php if(@$suc != NULL){ print @$suc->consecutivo_ordenservicio; }?>">
                                    </div> 

                                </div>
                            </div>

                            <div class="tab-pane " id="tablogo">
                               
                                <div class="col-xs-4 text-center">
                                    <input type="hidden" name="old_logoencabpath" value="<?php if(@$suc != NULL){ print $suc->logo_encab_path; }?>" >    
                                    <input type="hidden" name="old_logo" value="<?php if(@$suc != NULL){ print $suc->logo_sucursal; }?>" >    
                                    <input type="hidden" name="old_logodetalle" value="<?php if(@$suc != NULL){ print $suc->logo_detallepagina; }?>" >    
                                    <input type="hidden" name="old_logopie" value="<?php if(@$suc != NULL){ print $suc->logo_piepagina; }?>" >    
                                    <h3 class="profile-username text-center">Logotipo Principal</h3>
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-preview thumbnail"  id="fotomostrar">
                                            <img width="150" height="150"<?php
                                                if (@$suc != NULL) {
                                                    if ($suc->logo_sucursal) {
                                                        print "src='data:image/jpeg;base64,$suc->logo_sucursal' ";
                                                        //print "width='150' height='150' src='data:image/jpeg;base64,$suc->logo_sucursal'";
                                                        
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

                                <div class="col-xs-4 text-center">
                                    <h3 class="profile-username text-center">Logotipo Detalle de Página</h3>
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-preview thumbnail"  id="fotomostrar">
                                            <img  width="150" height="150"<?php
                                                if (@$suc != NULL) {
                                                    if ($suc->logo_detallepagina) {
                                                        print "src='data:image/jpeg;base64,$suc->logo_detallepagina' ";
                                                        //print "width='150' height='150' src='data:image/jpeg;base64,$suc->logo_sucursal'";
                                                        
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
                                                <input type="file"  id="logodetalle" name="logodetalle" accept="image/*" /> 
                                            </span>
                                            <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Quitar</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-4 text-center">
                                    <h3 class="profile-username text-center">Logotipo Pie de Página</h3>
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-preview thumbnail"  id="fotomostrar">
                                            <img  width="150" height="150"<?php
                                                if (@$suc != NULL) {
                                                    if ($suc->logo_piepagina) {
                                                        print "src='data:image/jpeg;base64,$suc->logo_piepagina' ";
                                                        //print "width='150' height='150' src='data:image/jpeg;base64,$suc->logo_sucursal'";
                                                        
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
                                                <input type="file"  id="logopie" name="logopie" accept="image/*" /> 
                                            </span>
                                            <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Quitar</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="txt_impuestoadicvalor">Pie de Página de Proforma</label>
                                    <textarea id="txt_pie1proforma" name="txt_pie1proforma" class="form-control" rows="2" placeholder="Pie de Página de Proforma..."><?php print @$suc->pie1_texto; ?></textarea>                                
                                </div>


                            </div>    

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

