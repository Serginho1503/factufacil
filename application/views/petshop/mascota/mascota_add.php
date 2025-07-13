<?php
$nombresistema = $this->session->userdata("nombresistema");
  print "<script>document.title = '$nombresistema - Editar Mascotas'</script>";
  date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

    $("#frmascota").validationEngine();

    $.datepicker.setDefaults($.datepicker.regional["es"]);
    $('#fecha').datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });

    $('#fechacli').datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });

    $('#fecha').on('changeDate', function(ev){ $(this).datepicker('hide'); });

    $('#fechacli').on('changeDate', function(ev){ 
      $(this).datepicker('hide'); 
    });

    $('.autocomplete').autocomplete();

    $('#txt_nro_ident').blur(function(){
      var idcliente = $(this).val();    
      if (idcliente === ""){
        alert("Debe ingresar un numero de Identificación");
        return false;
      }   
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Facturar/valcliente');?>",
          data: {
              idcliente: idcliente
           },
          success: function(json) {
            if(json.mens == null){ 
              $('#menid').attr('class','col-md-12 has-error');
              $('#mennom').attr('class','col-md-12 has-error');
              $('#txt_clinom').val('');
              $('#txt_telf').val('');
              $('#txt_correo').val('');
              $('#txt_direccion').val(''); 
              $('#txt_ciudad').val('');
              $('#txt_idcli').val(0);                             
            }
            else { 
              $('#txt_idcli').val(json.mens.id_cliente);
              $('#txt_nro_ident').val(json.mens.ident_cliente);
              $('#txt_clinom').val(json.mens.nom_cliente);
              $('#clinom').attr('class','col-md-5 has-success'); 
              $('#cliid').attr('class','col-md-3 has-success');
              $('#txt_telf').val(json.mens.telefonos_cliente);
              $('#txt_correo').val(json.mens.correo_cliente);
              $('#txt_direccion').val(json.mens.direccion_cliente); 
              $('#txt_ciudad').val(json.mens.ciudad_cliente);
              registrar_cliente();
            }
          }
      });
    });

    $(document).on('click', '.autocomplete-jquery-item', function(){  
      var nom = $(this).text(); 
      if (nom === ""){
        alert("Debe ingresar un nombre");
        return false;
      }
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Facturar/busca_nombre');?>",
          data: {
              nom: nom
           },
          success: function(json) {
              $('#txt_idcli').val(json.mens.id_cliente);
              $('#txt_nro_ident').val(json.mens.ident_cliente);
              $('#txt_clinom').val(json.mens.nom_cliente);
              $('#clinom').attr('class','col-md-5 has-success'); 
              $('#cliid').attr('class','col-md-3 has-success');
              $('#txt_telf').val(json.mens.telefonos_cliente);
              $('#txt_correo').val(json.mens.correo_cliente);
              $('#txt_direccion').val(json.mens.direccion_cliente); 
              $('#txt_ciudad').val(json.mens.ciudad_cliente);
              registrar_cliente();
          }
      });
    });

    $(document).on('change', '#txt_codmasc', function(){  
      var cod = $(this).val(); 
      if (cod === ""){
        alert("Debe ingresar un codigo");
        return false;
      }
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('petshop/Mascota/busca_codigo');?>",
          data: {
              cod: cod
           },
          success: function(json) {
            if(json == 1){ alert("Este Código ya fue Asignado a una Mascota"); $('#txt_codmasc').val(''); }
            else{ $('.codigonom').attr('class','col-md-3 has-success'); }
          }
      });
    });

    $(document).on('click', '.form-control', function(){  
      $('.autocomplete-jquery-results').each(function(index, el) {
        this.remove();
      });
    });

    $('#fechacli').change(function(){
      registrar_cliente();
    });

    $('.guarda_cliente').blur(function(){
      var idcliente = $('#txt_nro_ident').val();  
      if (idcliente === ""){
        alert("Debe ingresar un numero de Identificación");
        return false;
      }     
      var nom = $('#txt_clinom').val(); 
      if (nom === ""){
        alert("Debe ingresar un nombre");
        return false;
      }      
      registrar_cliente();
    });
    
    function registrar_cliente(){
      var idc = $('#txt_idcli').val();
      var ced = $('#txt_nro_ident').val();
      var nom = $('#txt_clinom').val();
      var fcli = $('#fechacli').val();
      var tel = $('#txt_telf').val();
      var cor = $('#txt_correo').val();
      var ciu = $('#txt_ciudad').val();
      var dir = $('#txt_direccion').val();
      /*$('fotorepre').val();*/
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "petshop/mascota/upd_petcliente",
          data: { idc: idc, ced: ced, nom: nom, fcli: fcli, tel: tel, cor: cor, ciu: ciu, dir: dir, },
          success: function(json) {
              //   alert(json);
          }
      });
      
    }

      $(document).on('click', '.add_reghist', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: base_url + "petshop/mascota/add_maschist",
        });
      });   
      

      $(document).on('click', '.edi_reghist', function(){
          var idm = $('#txt_idmasc').val();
          var idr = $(this).attr('id');
          $.ajax({
              type: "POST",
              dataType: "json",
              url: base_url + "petshop/mascota/tmp_maschist",
              data: { idm: idm, idr: idr },
              success: function(json) {
                $.fancybox.open({
                  type: "ajax",
                  width: 550,
                  height: 550,
                  ajax: {
                     dataType: "html",
                     type: "POST"
                  },
                  href: base_url + "petshop/mascota/edit_maschist",
                });
              }
          });
      }); 

    $(document).on("submit", "#frmaschist", function() {
        var id = $(this).attr("name");
        var data = $(this).serialize();
            $.ajax({
                url: $(this).attr("action"),
                data: data,
                type: 'POST',
                dataType: 'json',
                success: function(json) {
                  $('.lstprescpac').load(base_url + "petshop/mascota/actualiza_maschist");
                  $.fancybox.close();
                }
            });
        return false;
    });


    $(document).on('click', '.del_reghist', function(){
      var idm = $('#txt_idmasc').val();
      var idr = $(this).attr('id');
      if (conf_delhist()) {
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "petshop/mascota/del_maschist",
          data: { idm: idm, idr: idr },
          success: function(json) {
            $('.lstprescpac').load(base_url + "petshop/mascota/actualiza_maschist");
          }
        });
      }
      return false;
    });

    function conf_delhist() {
        return  confirm("¿Confirma que desea eliminar este registro?");
    }

  }); 

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-paw"></i> Mascotas
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="<?php print $base_url ?>petshop/mascota">Listado Mascotas</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <form id="frmascota" name="frmascota" role="form" class="form" method="POST" enctype="multipart/form-data" action="<?php echo base_url('petshop/mascota/guarda_mascota');?>" >
            <div class="box box-danger" style="margin-bottom: 7px;">
              <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-paw"></i> Agregar Mascota</h3>
              </div>
              <div class="box-body" style="padding-bottom: 2px;">
                <div class="row">
                  <div class="col-md-9" style="padding-left: 0px;">
                    <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                        if(@$masc != NULL){ ?>
                            <input type="hidden" id="txt_idmasc" name="txt_idmasc" value="<?php if(@$masc != NULL){ print @$masc->id_mascota; }?>" >    
                        <?php } else { ?>
                            <input type="hidden" id="txt_idmasc" name="txt_idmasc" value="0">    
                    <?php } ?>  
                    <div class="col-md-12">
                      <div id="codigonom" class="form-group col-md-3" style=" margin-bottom: 2px;">
                          <label for="lb_cli" style="margin-bottom: 0px; ">CHIP / Código</label>
                          <input type="text" class="form-control validate[required]" name="txt_codmasc" id="txt_codmasc" placeholder="Código de Mascota" value="<?php if(@$masc != NULL){ print @$masc->codigo; }?>" data-source="<?php echo base_url('facturar/valclientenombre?nombre=');?>">
                      </div> 
                      <div class="form-group col-md-5" style="padding-left: 0px; margin-bottom: 2px;">
                          <label for="lb_telf" style="margin-bottom: 0px;">Nombre</label>
                          <input type="text" class="form-control validate[required]" name="txt_nommasc" id="txt_nommasc" placeholder="Nombre de Mascota" value="<?php if(@$masc != NULL){ print @$masc->nombre; }?>" >
                      </div>

                      <div class="form-group col-md-4" style="padding-left: 0px; margin-bottom: 2px;">
                          <label for="lb_cor" style="margin-bottom: 0px;">Color</label>
                          <input type="text" class="form-control validate[required]" name="txt_colmasc" id="txt_colmasc" placeholder="Color de Mascota" value="<?php if(@$masc != NULL){ print @$masc->color; }?>" >
                      </div>               
                    </div>
                    <div class="col-md-12">
                      <div id="" class="form-group col-md-6 " style=" margin-bottom: 2px; padding-top: 2px; ">
                          <label for="lb_cli" style="margin-bottom: 0px;">Raza</label>
                          <input type="text" class="form-control validate[required]" name="txt_razmasc" id="txt_razmasc" placeholder="Raza de Mascota" value="<?php if(@$masc != NULL){ print @$masc->raza; }?>" >
                      </div> 
                      <div class="form-group col-md-3" style="padding-left: 0px; margin-bottom: 2px; padding-top: 2px; ">
                        <label for="lb_telf" style="margin-bottom: 0px;">Sexo</label>
                        <select class="form-control validate[required]" id="cmb_sexo" name="cmb_sexo">
                          <option value="0">Seleccione...</option> 
                          <?php if($masc != NULL){ ?>
                          <?php if($masc->sexo == 'M'){ ?>
                              <option value="<?php if($masc != NULL){ print $masc->sexo; }?>" selected="TRUE">Macho</option>
                              <option value="H">Hembra</option>
                          <?php } else {?>
                              <option value="M">Macho</option> 
                              <option value="<?php if($masc != NULL){ print $masc->sexo; }?>" selected="TRUE">Hembra</option>    
                          <?php } 
                              }
                          ?>
                          <?php if(@$masc == NULL){ ?>
                           <option value="M">Macho</option> 
                          <option value="H">Hembra</option>  
                          <?php } ?>
                        </select>
                      </div>
                      <div class="form-group col-md-3" style="padding-left: 0px; margin-bottom: 2px; padding-top: 2px; ">
                        <label for="lb_cor" style="margin-bottom: 0px;">Fec. Nac</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right validate[required]" id="fecha" name="fecha" value="<?php if(@$masc != NULL){ @$fec = str_replace('-', '/', @$masc->fec_nac); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec; }?>">
                        </div>
                      </div>               
                    </div>
                    <div class="col-md-12">
                      <div id="" class="form-group col-md-7" style=" margin-bottom: 2px;">
                        <label for="lb_cli" style="margin-bottom: 0px;">Nombre Veterinario</label>
                        <input type="text" class="form-control " name="txt_nomvet" id="txt_nomvet" placeholder="Nombre del Cliente" value="<?php if(@$masc != NULL){ print @$masc->veterinario; }?>">
                      </div> 
                      <div class="form-group col-md-5" style="padding-left: 0px; margin-bottom: 2px;">
                          <label for="lb_telf" style="margin-bottom: 0px;">Teléfonos Veterinario</label>
                          <input type="text" class="form-control " name="txt_telvet" id="txt_telvet" placeholder="Teléfono" value="<?php if(@$masc != NULL){ print @$masc->telf_veterinario; }?>" >
                      </div>                 
                    </div>
                    <div class="col-md-12" style=" padding-top: 2px; ">
                        <div class="form-group" style="padding-right: 15px; padding-left: 15px;">
                            <label for="lb_res" style="margin-bottom: 0px;">Caracteristicas</label>
                            <textarea id="txt_car" name="txt_car" class="form-control " rows="2" placeholder="Ingrese las Caracteristicas..."><?php if(@$masc != NULL){ print @$masc->caracteristicas; }?></textarea>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-3 text-center">
                      <h3 class="profile-username text-center">Mascota</h3>
                      <div class="fileupload fileupload-new" data-provides="fileupload">
                          <div class="fileupload-preview thumbnail"  id="fotomostrar">
                              <img  width="150" height="150"<?php
                                  if (@$masc != NULL) {
                                      if (trim($masc->foto_mascota) != '') {
                                          ?>
                                          src="<?php print base_url(); ?>public/img/mascota/<?php print $masc->foto_mascota; ?>" <?php
                                          
                                      } else {
                                          ?>
                                          src="<?php print base_url(); ?>public/img/mascota.jpg" <?php
                                      }
                                  } else {
                              ?>
                                      src="<?php print base_url(); ?>public/img/mascota.jpg" <?php }
                                  ?> alt="" onerror="this.src='<?php print base_url() . "public/img/mascota.jpg"; ?>';" />

                          </div>
                          <div>
                          <br>
                              <span class="btn btn-file btn-success">
                                  <span class="fileupload-new">Imagen</span>
                                  <span class="fileupload-exists">Cambiar</span>
                                  <input type="file"  id="fotomasc" name="fotomasc" accept="image/*" /> 
                              </span>
                              <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Quitar</a>
                          </div>
                      </div>
                  </div>                  

                </div>
              </div>
              <div  class="box-footer">
                <div class="col-md-12" >
                  <div class="form-actions text-center">
                    
                  </div>
                </div>
              </div>                
            </div>
            
<!--             <div class="box box-danger" style="margin-bottom: 7px;">
              <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-folder-open"></i> Historico</h3>
                <div class="pull-right"> 
                  <button type="button" class="btn btn-sm btn-primary btn-grad add_reghist" > <i class="fa fa-plus-square"></i> Añadir Registro  </button>
                </div>
              </div>
              <div class="box-body" style="padding-bottom: 5px;">
                <div class="row">
                  <div class="col-md-12" style="padding-left: 0px;">
                    <div class="col-md-12 table-responsive lstprescpac">
                      <table class="table table-bordered">
                        <tr>
                          <th style="width: 10px">Nro</th>
                          <th class="col-md-1">Tipo</th>
                          <th class="col-md-4">Nombre</th>
                          <th>Observaciones</th>
                          <th class="col-md-1 text-center">Acción</th>
                        </tr>
                        <?php  $nro = 0;
                          if(count(@$lsttipohist)>0){
                            foreach (@$lsttipohist as $lth) { 
                                $nro++  ?>
                              <tr>
                                <td style="width: 10px"><?php print $nro; ?></td>
                                <td><?php print $lth->desc_tipohist; ?></td>
                                <td><?php print substr($lth->nom_tipohist,0,120); ?></td>
                                <td><?php print substr($lth->desc_reghist,0,120).'...'; ?></td>
                                <td>
                                  <div class="text-center">
                                    <a href="#" title="Editar Historico" id="<?php print $lth->idreghist; ?>" class="btn btn-success btn-xs btn-grad edi_reghist"><i class="fa fa-pencil-square-o"></i></a> 
                                    <a href="#" title="Eliminar Historico" id="<?php print $lth->idreghist;  ?>" class="btn btn-danger btn-xs btn-grad del_reghist"><i class="fa fa-trash-o"></i></a>
                                  </div>                    
                                </td>
                              </tr> 
                          <?php 
                            } 
                          }
                        ?>
                      </table> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
 -->
            <div class="box box-danger" style="margin-bottom: 7px;">
              <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-user"></i> Datos del Representante</h3>
              </div>
              <div class="box-body" style="padding-bottom: 5px;">
                <div class="row">
                  <div class="col-md-9" style="padding-left: 0px;">
                    <div class="col-md-12">
                      <?php /* CAMPO HIDDEN CON EL ID DE LA INSCRIPCIÓN (EN CASO DE MODIFICACIÓN DEL REGISTRO) */ 
                          if(@$cli != NULL){ ?>
                              <input type="hidden" id="txt_idcli" name="txt_idcli" value="<?php if(@$cli != NULL){ print @$cli->id_cliente; }?>" >    
                          <?php } else { ?>
                              <input type="hidden" id="txt_idcli" name="txt_idcli" value="0">    
                      <?php } ?>              
                      <div id="cliid" class="form-group col-md-3" style="margin-bottom: 2px;">
                          <label for="lb_res">Cédula</label>
                          <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$cli != NULL){ print @$cli->ident_cliente; }?>" >
                      </div>

                      <div id="clinom" class="form-group col-md-6 autocomplete" style="padding-left: 0px; margin-bottom: 2px;">
                        <label for="lb_cli">Cliente</label>
                        <input type="text" class="form-control validate[required]" name="txt_clinom" id="txt_clinom" placeholder="Nombre del Cliente" value="<?php if(@$cli != NULL){ print @$cli->nom_cliente; }?>" data-source="<?php echo base_url('facturar/valclientenombre?nombre=');?>">
                      </div> 
                      <div class="form-group col-md-3" style="padding-left: 0px; margin-bottom: 2px; padding-top: 2px; ">
                        <label for="lb_cor" style="margin-bottom: 0px;">Fec. Nac</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right validate[required] " id="fechacli" name="fechacli" value="<?php if(@$cli != NULL){ @$fecn = str_replace('-', '/', @$cli->fecha_nac); @$fecn = date("d/m/Y", strtotime(@$fecn)); print @$fecn; }?>">
                        </div>
                      </div>  
                    </div> 
                    <div class="col-md-12">
                      <div class="form-group col-md-6" style="margin-bottom: 2px;">
                          <label for="lb_telf">Teléfono</label>
                          <input type="text" class="form-control validate[required] guarda_cliente" name="txt_telf" id="txt_telf" placeholder="Teléfono" value="<?php if(@$cli != NULL){ print @$cli->telefonos_cliente; }?>" >
                      </div>                      
                      <div class="form-group col-md-6" style="margin-bottom: 2px;">
                          <label for="lb_cor">Correo</label>
                          <input type="text" class="form-control validate[required] guarda_cliente" name="txt_correo" id="txt_correo" placeholder="Correo" value="<?php if(@$cli != NULL){ print @$cli->correo_cliente; }?>" >
                      </div>                
                    </div>  
                    <div class="col-md-12">
                      <div class="form-group col-md-3" style=" margin-bottom: 2px;">
                          <label for="lb_ciu">Ciudad</label>
                          <input type="text" class="form-control guarda_cliente" name="txt_ciudad" id="txt_ciudad" placeholder="Ciudad" value="<?php if(@$cli != NULL){ print @$cli->ciudad_cliente; }?>" >
                      </div>
                      <div class="form-group col-md-9" style="padding-left: 0px; margin-bottom: 2px;">
                          <label for="lb_dir">Dirección</label>
                          <input type="text" class="form-control validate[required] guarda_cliente" name="txt_direccion" id="txt_direccion" placeholder="Dirección" value="<?php if(@$cli != NULL){ print @$cli->direccion_cliente; }?>" >
                      </div>                        
                    </div>
                  </div>  
                  <div class="col-md-3 text-center">
                      <h3 class="profile-username text-center">Representante</h3>
                      <div class="fileupload fileupload-new" data-provides="fileupload">
                          <div class="fileupload-preview thumbnail"  id="fotocliente">
                              <img  width="150" height="150"<?php
                                  if (@$cli != NULL) {
                                      if (trim($cli->foto_cliente) != '') { ?>
                                       src="<?php print base_url(); ?>public/img/cliente/<?php print $cli->foto_cliente; ?>" 
                                       <?php
                                       /*  print "width='150' height='150' src='data:image/jpeg;base64,$cli->foto_cliente'";*/
                                     
                                      } else { ?>
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
                                  <input type="file"  id="fotocli" name="fotocli" accept="image/*" /> 
                              </span>
                              <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Quitar</a>
                          </div>
                      </div>
                  </div>                           
                </div>
              </div>
              <div  class="box-footer">
                <div class="col-md-12" >
                  <div class="form-actions text-center">
                    <button id="zxc" type="submit" class="btn btn-success btn-grad no-margin-bottom">
                      <i class="fa fa-save "></i> Guardar Mascota
                    </button> 
                  </div>
                </div>
              </div>
            </div>

          </form>

        </div>
      </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

