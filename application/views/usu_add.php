<?php
/* ------------------------------------------------
  ARCHIVO: usuarios.php
  DESCRIPCION: Contiene la vista principal del módulo de usuarios.
  FECHA DE CREACIÓN: 30/06/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Usuarios'</script>";
date_default_timezone_set("America/Guayaquil");

if(@$fic_usu->perfil != NULL){
    $mese = $fic_usu->perfil;
}else{
    $mese = 0;
}  

?>
<script>
$( document ).ready(function() {
    $("#frm_add").validationEngine();
    
    $('#fecha').datepicker();
    $('#fecha').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    var mesero = <?php print $mese; ?>;
    if(mesero != 1){
        $('#cmb_mesero').attr("disabled", false);
    }else{
        $('#cmb_mesero').attr("disabled", true);
    }

  $(document).on('change','#cmb_perfil', function(){
    id = $(this).val();
    if(id != 1){
        $('#cmb_mesero').attr("disabled", false);
    }else{
        $('#cmb_mesero').attr("disabled", true);
    }
    
  });

    // Confirmacion para guardar un registro
    function conf_guar() {
        return  confirm("¿Confirma que desea guardar este registro?");
    }

    $('#pre0').click(function() {
        var valor = 0;
        if($(this).is(":checked")){ valor = 1; } 
        else{ valor = 0; } 
        $("#txtpp0").val(valor);
    });    

    $('.prepro').click(function() {
        var idsuc = $(this).attr('id');
        if($(this).is(":checked")){ valor = 1; } 
        else{ valor = 0; } 
        $("#txtpp"+idsuc).val(valor);
    });  

    $('.suc').click(function() {
        var idsuc = $(this).attr('id');
        if($(this).is(":checked")){ valor = 1; } 
        else{ valor = 0; } 
        $("#txtsu"+idsuc).val(valor);
    });  

    $('.alm').click(function() {
        var idalm = $(this).attr('id');
        if($(this).is(":checked")){ valor = 1; } 
        else{ valor = 0; } 
        $("#txtal"+idalm).val(valor);
    });  

    $('.caja').click(function() {
        var idcaj = $(this).attr('id');
        if($(this).is(":checked")){ valor = 1; } 
        else{ valor = 0; } 
        $("#txtca"+idcaj).val(valor);
    });  

});

</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Usuarios
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>usuarios">Usuarios</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- SECCION DEL FORMULARIO-->
            <form id="frm_add" name="frm_add" method="post" role="form" class="form" enctype="multipart/form-data" action="<?php echo base_url('usuarios/guardar');?>">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"><i class="fa fa-user"></i> Datos del Usuario</h3>
                    </div>
                    <div class="box-body">

                      <div class="row">
                        <div class="col-xs-3 text-center">
                            <h3 class="profile-username text-center">Foto del Usuario</h3>
                            <p class="text-muted text-center">Identificación</p>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-preview thumbnail"  id="fotomostrar">
                                    <img <?php
                                        if (@$fic_usu != NULL) {
                                            if ($fic_usu->fot_usu) {
                                                
                                                print "width='150' height='150' src='data:image/jpeg;base64,$fic_usu->fot_usu'";
                                                
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
                                        <input type="file"  id="foto" name="foto" accept="image/*" /> 
                                    </span>
                                    <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Quitar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-9">
                            <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                                if(@$fic_usu != NULL){ ?>
                                    <input type="hidden" id="txt_id" name="txt_id" value="<?php if($fic_usu != NULL){ print $fic_usu->id_usu; }?>" >    
                                <?php } else { ?>
                                    <input type="hidden" id="txt_id" name="txt_id" value="0">    
                            <?php } ?>  
                            <!-- Nombre del Usuario -->
                            <div class="form-group col-md-6">
                                <label for="txt_nombre">Nombres</label>
                                <input type="text" class="form-control validate[required]" name="txt_nombre" id="txt_nombre" placeholder="Nombre del Usuario" value="<?php if(@$fic_usu != NULL){ print @$fic_usu->nom_usu; }?>">
                            </div>
                            <!-- Apellido del Usuario -->
                            <div class="form-group col-md-6">
                                <label for="txt_apellido">Apellidos</label>
                                <input type="text" class="form-control " name="txt_apellido" id="txt_apellido" placeholder="Apellido del Usuario" value="<?php if(@$fic_usu != NULL){ print @$fic_usu->ape_usu; }?>">
                            </div>
                            <!-- Nacionalidad del Usuario 
                            <div class="form-group col-md-4">
                                <label>Nacionalidad</label>
                                <select class="form-control validate[required]" id="cmb_nac" name="cmb_nac">
                                    <option value="0">Seleccione...</option> 
                                    <option value="EC">Ecuatoriano</option> 
                                    <option value="EX">Extranjero</option> 
                                </select>
                            </div>-->
                            <!-- Cedula o Pasaporte del Usuario 
                            <div class="form-group col-md-4">
                                <label for="txt_identificacion">Cedula / Pasaporte</label>
                                <input type="text" class="form-control validate[required]"  name="txt_identificacion" id="txt_identificacion" placeholder="Cedula ó Pasaporte" value="<?php if(@$fic_usu != NULL){ print @$fic_usu->ide_usu; }?>">
                            </div>-->
                            <!-- Fecha de Ingreso del Usuario 
                            <div class="form-group col-md-4">
                                <label>Fecha de Ingreso</label>

                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right validate[required]" id="fecha" name="fecha" value="<?php if(@$fic_usu != NULL){ @$fec = str_replace('-', '/', @$fic_usu->fec_usu); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec; }?>">
                                </div>
                            </div>-->
                            <!-- Teléfono del Usuario 
                            <div class="form-group col-md-4">
                                <label>Telefono:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control validate[required]" id="txt_telefono" name="txt_telefono" data-inputmask='"mask": "(999) 999-9999"' data-mask value="<?php if(@$fic_usu != NULL){ print @$fic_usu->tlf_usu; }?>">
                                </div>
                            </div> -->
                            <!-- Correo del Usuario 
                            <div class="form-group col-md-8">
                                <label for="txt_email">Email</label>
                                <input type="text" class="form-control validate[required]" id="txt_email" name="txt_email" placeholder="Email del Usuario" value="<?php if(@$fic_usu != NULL){ print @$fic_usu->ema_usu; }?>">
                            </div>-->
                            <!-- Dirección del Usuario 
                            <div class="form-group col-md-12">
                                <label for="txt_identificacion">Dirección</label>
                                <input type="text" class="form-control validate[required]" id="txt_direccion" name="txt_direccion" placeholder="Dirección" value="<?php if(@$fic_usu != NULL){ print @$fic_usu->dir_usu; }?>">
                            </div>-->

                            <div class="form-group col-md-4">
                                <label>Perfil</label>
                                <select class="form-control validate[required]" id="cmb_perfil" name="cmb_perfil">
                                    <?php 
                                      if(@$perfil != NULL){ ?>
                                        <option  value="0" selected="TRUE">Seleccione...</option>
                                    <?php }  
                                              if (count($perfil) > 0) {
                                                foreach ($perfil as $pe):
                                                    if(@$fic_usu->perfil != NULL){
                                                        if($pe->id_perfil == $fic_usu->perfil){ ?>
                                                            <option  value="<?php  print $pe->id_perfil; ?>" selected="TRUE"><?php  print $pe->nom_perfil ?></option> 
                                                            <?php
                                                        }else{ ?>
                                                            <option value="<?php  print $pe->id_perfil; ?>"> <?php  print $pe->nom_perfil ?> </option>
                                                            <?php
                                                        }
                                                    }else{ ?>
                                                        <option value="<?php  print $pe->id_perfil; ?>"> <?php  print $pe->nom_perfil ?> </option>
                                                        <?php
                                                        }   ?>
                                                    <?php

                                                endforeach;
                                              }
                                              ?>
                                </select>
                            </div>    

                            <div class="form-group col-md-4">
                                <label>Vendedor</label>
                                <select class="form-control validate[required]" id="cmb_mesero" name="cmb_mesero">
                                    <?php 
                                      if(@$mesero != NULL){ ?>
                                        <option  value="0" selected="TRUE">Seleccione...</option>
                                    <?php }  
                                              if (count($mesero) > 0) {
                                                foreach ($mesero as $me):
                                                    if(@$fic_usu->id_mesero != NULL){
                                                        if($me->id_mesero == $fic_usu->id_mesero){ ?>
                                                            <option  value="<?php  print $me->id_mesero; ?>" selected="TRUE"><?php  print $me->nom_mesero ?></option> 
                                                            <?php
                                                        }else{ ?>
                                                            <option value="<?php  print $me->id_mesero; ?>"> <?php  print $me->nom_mesero ?> </option>
                                                            <?php
                                                        }
                                                    }else{ ?>
                                                        <option value="<?php  print $me->id_mesero; ?>"> <?php  print $me->nom_mesero ?> </option>
                                                        <?php
                                                        }   ?>
                                                    <?php

                                                endforeach;
                                              }
                                              ?>
                                </select>
                            </div>    

                            <div class="form-group col-md-4">
                                <label>Punto de Venta</label>
                                <select class="form-control " id="cmb_punto" name="cmb_punto">
                                    <?php 
                                      if(@$punto != NULL){ ?>
                                        <option  value="0" selected="TRUE">Seleccione...</option>
                                    <?php }  
                                              if (count($punto) > 0) {
                                                foreach ($punto as $p):
                                                    if(@$fic_usu->id_punto != NULL){
                                                        if($p->id_mesa == $fic_usu->id_punto){ ?>
                                                            <option  value="<?php  print $p->id_mesa; ?>" selected="TRUE"><?php  print $p->nom_mesa ?></option> 
                                                            <?php
                                                        }else{ ?>
                                                            <option value="<?php  print $p->id_mesa; ?>"> <?php  print $p->nom_mesa ?> </option>
                                                            <?php
                                                        }
                                                    }else{ ?>
                                                        <option value="<?php  print $p->id_mesa; ?>"> <?php  print $p->nom_mesa ?> </option>
                                                        <?php
                                                        }   ?>
                                                    <?php

                                                endforeach;
                                              }
                                              ?>
                                </select>
                            </div>   

                            <!-- Nombre del Usuario para el Inicio de Sesion -->
                            <div class="form-group  col-md-4">
                                <label for="txt_usuario">Usuario</label>
                                <input type="text" class="form-control validate[required]" id="txt_usuario" name="txt_usuario" placeholder="Usuario" value="<?php if(@$fic_usu != NULL){ print @$fic_usu->log_usu; }?>">
                            </div>
                            <!-- Contraseña del Usuario -->
                            <div class="form-group  col-md-4">
                                <label for="txt_clave">Contraseña</label>
                                <input type="password" class="form-control validate[required]" id="txt_clave" name="txt_clave" placeholder="Password" >
                            </div>
                            <!-- Estatus del Usuario -->
                            <div class="form-group col-md-4">
                                <label>Estatus</label>
                                <select class="form-control validate[required]" id="cmb_estatus" name="cmb_estatus">
                                    <option value="0">Seleccione...</option> 
                                    <?php if($fic_usu != NULL){ ?>
                                    <?php if($fic_usu->est_usu == 'A'){ ?>
                                        <option value="<?php if($fic_usu != NULL){ print $fic_usu->est_usu; }?>" selected="TRUE">Activo</option>
                                        <option value="I">Inactivo</option>
                                    <?php } else {?>
                                        <option value="A">Activo</option> 
                                        <option value="<?php if($fic_usu != NULL){ print $fic_usu->est_usu; }?>" selected="TRUE">Inactivo</option>    
                                    <?php } 
                                        }
                                    ?>
                                    <?php if(@$fic_usu == NULL){ ?>
                                     <option value="A">Activo</option> 
                                    <option value="I">Inactivo</option>  
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="box-group">
                                    <div class="panel box box-danger">
                                        <div class="box-header with-border">
                                            <h4 class="box-title"> Permisos a Precios </h4>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <?php
                                                    if(@$usupre != NULL){
                                                        foreach ($usupre as $up) { ?>
                                                            <div class="checkbox">
                                                                <label><input class="prepro" id="<?php print $up->id_precios; ?>"  name="pre<?php print $up->id_precios; ?>" type="checkbox" value="0" <?php if(@$up != NULL){ if(@$up->estatus == 1){ print "checked='' ";} } ?> > <?php print $up->desc_precios; ?></label>
                                                                <input type="hidden" class="form-control"  id="txtpp<?php print $up->id_precios; ?>" name="txtpp<?php print $up->id_precios; ?>" value="<?php print $up->estatus; ?>" >
                                                            </div>
                                                        <?php 
                                                        }
                                                    }
                                                    ?>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="box-group">
                                    <div class="panel box box-danger">
                                        <div class="box-header with-border">
                                            <h4 class="box-title"> Permisos a Sucursales </h4>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <?php
                                                    if(@$suc != NULL){
                                                        foreach ($suc as $s) { ?>
                                                            <div class="checkbox">
                                                                <label><input class="suc" id="<?php print $s->id_sucursal; ?>"  name="suc<?php print $s->id_sucursal; ?>" type="checkbox" value="0" <?php  if(@$s != NULL){ if(@$s->estatus == 1){ print "checked='' ";} } ?> > <?php print $s->nom_sucursal; ?></label>
                                                                <input type="hidden" class="form-control"  id="txtsu<?php print $s->id_sucursal; ?>" name="txtsuc<?php print $s->id_sucursal; ?>" value="<?php  print $s->estatus; ?>" >
                                                            </div>
                                                        <?php 
                                                        }
                                                    }
                                                    ?>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>     
                            <div class="col-md-3">
                                <div class="box-group">
                                    <div class="panel box box-danger">
                                        <div class="box-header with-border">
                                            <h4 class="box-title"> Permisos a Almacen </h4>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <?php
                                                    if(@$alm != NULL){
                                                        foreach ($alm as $a) { ?>
                                                            <div class="checkbox">
                                                                <label><input class="alm" id="<?php print $a->almacen_id; ?>"  name="alm<?php print $a->almacen_id; ?>" type="checkbox" value="0" <?php if(@$a != NULL){ if(@$a->estatus == 1){ print "checked='' ";} } ?> > <?php print $a->almacen_nombre; ?></label>
                                                                <input type="hidden" class="form-control"  id="txtal<?php print $a->almacen_id; ?>" name="txtalm<?php print $a->almacen_id; ?>" value="<?php print $a->estatus; ?>" >
                                                            </div>
                                                        <?php 
                                                        }
                                                    }
                                                    ?>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="box-group">
                                    <div class="panel box box-danger">
                                        <div class="box-header with-border">
                                            <h4 class="box-title"> Permisos a Caja </h4>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <?php
                                                    if(@$caja != NULL){
                                                        foreach ($caja as $c) { ?>
                                                            <div class="checkbox">
                                                                <label><input class="caja" id="<?php print $c->id_caja; ?>"  name="caj<?php print $c->id_caja; ?>" type="checkbox" value="0" <?php if(@$c != NULL){ if(@$c->estatus == 1){ print "checked='' ";} } ?> > <?php print $c->nom_caja; ?></label>
                                                                <input type="hidden" class="form-control"  id="txtca<?php print $c->id_caja; ?>" name="txtcaj<?php print $c->id_caja; ?>" value="<?php print $c->estatus; ?>" >
                                                            </div>
                                                        <?php 
                                                        }
                                                    }
                                                    ?>
                                                </div>                                                
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
                            <button type="submit" class="btn btn-success  btn-grad no-margin-bottom">
                                <i class="fa fa-save "></i> Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </section>
</div>