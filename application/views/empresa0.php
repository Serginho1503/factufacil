<?php
/* ------------------------------------------------
  ARCHIVO: Empresa.php
  DESCRIPCION: Contiene la vista principal del módulo de Empresa.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
print "<script>document.title = 'FACTUFÁCIL - Empresa'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<script>
$( document ).ready(function() {
    $("#frm_emp").validationEngine();
});

</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       <i class="fa fa-fort-awesome"></i> Empresa</a></li>
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
            <div class="col-md-9" style="padding-right: 5px;">
                <!-- general form elements -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Datos de la Empresa</h3>
                    </div>
                  <!--   <form role="form"> -->
             
                    <form id="frm_emp" name="frm_emp" method="post" role="form" class="form" action="<?php echo base_url('empresa/guardar');?>">
                        <div class="box-body">

                            <!-- Nombre de la Empresa -->
                            <div class="form-group col-md-6">
                                <label for="txt_nombre">Nombre</label>
                                <input type="text" class="form-control validate[required]" name="txt_nombre" id="txt_nombre" placeholder="Nombre de la Empresa" value="<?php if(@$emp != NULL){ print @$emp->nom_emp; }?>">
                            </div>
                            <!-- R.U.C. de la Empresa -->
                            <div class="form-group col-md-6">
                                <label for="txt_ruc">R.U.C.</label>
                                <input type="text" class="form-control validate[required]" name="txt_ruc" id="txt_ruc" placeholder="R.U.C. de la Empresa" value="<?php if(@$emp != NULL){ print @$emp->ruc_emp; }?>">
                            </div>
                            <!-- Razón Social de la Empresa -->
                            <div class="form-group col-md-6">
                                <label for="txt_raz_soc">Razón Social</label>
                                <input type="text" class="form-control validate[required]" name="txt_raz_soc" id="txt_raz_soc" placeholder="Razón Social de la Empresa" value="<?php if(@$emp != NULL){ print @$emp->raz_soc_emp; }?>">
                            </div>
                            <!-- Email de la Empresa -->
                            <div class="form-group col-md-6">
                                <label for="txt_ema">Email</label>
                                <input type="text" class="form-control validate[required]" name="txt_ema" id="txt_ema" placeholder="Email de la Empresa" value="<?php if(@$emp != NULL){ print @$emp->ema_emp; }?>">
                            </div>
                            <!-- Teléfono de la Empresa -->
                            <div class="form-group col-md-6">
                                <label>Telefono:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control validate[required]" id="txt_telefono" name="txt_telefono" data-inputmask='"mask": "(999) 999-9999"' data-mask value="<?php if(@$emp != NULL){ print @$emp->tlf_emp; }?>">
                                </div>
                            </div>
                            <!-- Fax de la Empresa -->
                            <div class="form-group col-md-6">
                                <label>Fax:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fax"></i>
                                    </div>
                                    <input type="text" class="form-control validate[required]" id="txt_fax" name="txt_fax" data-inputmask='"mask": "(999) 999-9999"' data-mask value="<?php if(@$emp != NULL){ print @$emp->fax_emp; }?>">
                                </div>
                            </div>                            
                            <!-- Dirección de la Empresa -->
                            <div class="form-group col-md-12">
                                <label for="txt_dir">Dirección</label>
                                <input type="text" class="form-control validate[required]" name="txt_dir" id="txt_dir" placeholder="Dirección de la Empresa" value="<?php if(@$emp != NULL){ print @$emp->dir_emp; }?>">
                            </div>            
                            <!-- Representante de la Empresa -->
                            <div class="form-group col-md-6">
                                <label for="txt_rep">Representante</label>
                                <input type="text" class="form-control validate[required]" name="txt_rep" id="txt_rep" placeholder="Representante de la Empresa" value="<?php if(@$emp != NULL){ print @$emp->rep_emp; }?>">
                            </div>   
                            <!-- Página Web de la Empresa -->
                            <div class="form-group col-md-6">
                                <label for="txt_web">Página Web</label>
                                <input type="text" class="form-control validate[required]" name="txt_web" id="txt_web" placeholder="Página Web de la Empresa" value="<?php if(@$emp != NULL){ print @$emp->web_emp; }?>">
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

