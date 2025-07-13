<?php
/* ------------------------------------------------
  ARCHIVO: contab_plancuentas.php
  DESCRIPCION: Contiene la vista del módulo de plan de cuentas.
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
  print "<script>document.title = '$nombresistema - Plan de Cuentas'</script>";
  date_default_timezone_set("America/Guayaquil");

?>

<style type="text/css">

</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {


    $("#tree_cuentas")
    .on('changed.jstree', function (e, data) {
        if (data.selected.length > 0){
          var id = data.instance.get_node(data.selected[0]).id;
          if (id == 0) {
            $('#paneldatos').hide();
            $("#txt_nivel").val(0);
          } else { 
            inhabilita_controles();
            $('#paneldatos').show();
            if (id != '')
              obtenerdatoscuenta(id); 
          }
        }  
      })
    .jstree({
        "core" : {
            "themes" : {
                "responsive": true
            }, 
            // so that create works
            "check_callback" : true,
            'data' : {
                'type' : "POST",
                'url' : function (node) {
                      return node.id === '#' ?
                        "<?php echo base_url('contabilidad/contab_plancuentas/lst_planraiz');?>" :
                        "<?php echo base_url('contabilidad/contab_plancuentas/lst_cuentassubordinadas');?>";
                },
                'data' : function (node) {
                  return { 'id' : node.id }
                }
            }
        },
        "plugins" : [ "contextmenu", "dnd", "state", "types" ],
        "contextmenu": {
            "items": function (node) {
                return {
                    "create": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Adicionar",
                        "action": function (obj) {
                            habilita_nuevacuenta(node.id);
                        },
                    },
                    "edit": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Modificar",
                        "action": function (obj) {
                            if (node.id != 0){
                              habilita_modificarcuenta(node.id);
                            }
                        },
                    },
                    "delete": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Eliminar",
                        "disabled": true,
                        "action": function (obj) {
                            elimina_cuenta(node.id);
                        },
                    },
                    "import": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Importar",
                        "disabled": true,
                        "action": function (obj) {
                            importar_cuentas();
                        },
                    }
                }
            }
        }
    });

    function obtenerdatoscuenta(id){
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('contabilidad/contab_plancuentas/get_cuenta');?>",
        data: {id: id},
        success: function(json) {
          $("#txt_id").val(json.resu.id);
          $("#txt_idcuentasuperior").val(json.resu.idcuentasuperior);
          $("#txt_nivel").val(json.resu.nivel);
          $("#cmb_grupo").val(json.resu.idgrupocuenta);
          $("#cmb_empresa").val(json.resu.idempresa);
          $("#txt_cuentasuperior").val(json.resu.codigosuperior + ' ' + json.resu.descripcionsuperior);
          $("#txt_codigosuperior").val(json.resu.codigosuperior);
          $("#txt_codigonivel").val(json.resu.codigonivel);
          $("#txt_descripcion").val(json.resu.descripcion);
          $("#cmb_naturaleza").val(json.resu.naturaleza);
          $("#txt_activo").attr('checked', (json.resu.activo == 1));
        }
      });
    }

    function habilita_nuevacuenta(id){
      $('#paneldatos').show();
      $("#btn_guardar").text('Adicionar');
      $("#btn_guardar").show();

      $("#txt_idcuentasuperior").val(id);
      //$("#txt_idcuentasuperior").val($("#txt_id").val());

      $("#txt_id").val(0);
      $("#cmb_grupo").attr('disabled', (id >= 1) );
      $("#cmb_empresa").attr('disabled', (id == 0) );
      if (id == 0){
        $("#txt_cuentasuperior").val('');
        $("#txt_codigosuperior").val('');
      } else {
        var strinicod = $("#txt_codigosuperior").val();
        if (strinicod != '') { strinicod = strinicod + '.' }
        $("#txt_cuentasuperior").val( strinicod + $("#txt_codigonivel").val() + ' ' + $("#txt_descripcion").val() );
        $("#txt_codigosuperior").val( strinicod + $("#txt_codigonivel").val() );
      }
      $("#txt_codigonivel").val('');
      $("#txt_codigonivel").attr('disabled', false );
      $("#txt_codigonivel").focus();
      $("#txt_descripcion").val('');
      $("#txt_descripcion").attr('disabled', false );
      $("#cmb_naturaleza").attr('disabled', false );
      $("#txt_activo").attr('checked', true);
      $("#txt_activo").attr('disabled', false );
    }

    function habilita_modificarcuenta(id){
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('contabilidad/contab_plancuentas/cuenta_tiene_operaciones');?>",
        data: {id: id},
        success: function(json) {
          if (json.resu == 0){
            $('#paneldatos').show();
            $("#btn_guardar").text('Guardar');
            $("#btn_guardar").show();

            $("#cmb_grupo").attr('disabled', (id >= 1) );
            $("#cmb_empresa").attr('disabled', (id == 0) );

            $("#txt_codigonivel").attr('disabled', false );
            $("#txt_codigonivel").focus();
            $("#txt_descripcion").attr('disabled', false );
            $("#cmb_naturaleza").attr('disabled', false );
            $("#txt_activo").attr('disabled', false );
          }  
          else{
            alert('No es posible modificar la cuenta, ya tiene operaciones registradas');
          }
        }
      });    
    }

    function inhabilita_controles(){
      $("#btn_guardar").hide();
      $("#cmb_grupo").attr('disabled', true );
      $("#txt_codigonivel").attr('disabled', true );
      $("#txt_descripcion").attr('disabled', true );
      $("#cmb_naturaleza").attr('disabled', true );
      $("#cmb_empresa").attr('disabled', true );
      $("#txt_activo").attr('disabled', true );
    }

    $(document).on('click', '#btn_guardar', function(){
      var id = $("#txt_id").val();
      var nivel = $("#txt_nivel").val();
      if (nivel == '') {nivel = 0;}
      id = id * 1;
      nivel = nivel * 1;
      //alert(nivel);
      var codigonivel = $("#txt_codigonivel").val();
      var codigocuenta = $("#txt_codigosuperior").val();
      if (id == 0) { nivel = nivel + 1; }
      if (nivel > 1) { codigocuenta = codigocuenta + '.'; }
      codigocuenta = codigocuenta + codigonivel;
      var idempresa = $("#cmb_empresa").val();
      var idcuentasuperior = $("#txt_idcuentasuperior").val();
      var idgrupocuenta = $("#cmb_grupo").val();
      var descripcion = $("#txt_descripcion").val();
      var naturaleza = $("#cmb_naturaleza").val();
      var activo = $("#txt_activo").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('contabilidad/contab_plancuentas/existe_cuenta');?>",
        data: {id: id, codigocuenta: codigocuenta, idempresa: idempresa},
        success: function(json) {
          if (json.resu == 0){
            $.ajax({
              type: "POST",
              dataType: "json",
              url: "<?php echo base_url('contabilidad/contab_plancuentas/guardar');?>",
              data: {id: id, codigocuenta: codigocuenta, idempresa: idempresa, 
                     idcuentasuperior: idcuentasuperior, idgrupocuenta: idgrupocuenta,
                     codigonivel: codigonivel, descripcion: descripcion,
                     nivel: nivel, naturaleza: naturaleza, activo: activo
                    },
              success: function(json) {
                if (json.resu > 0) {
                  $("#btn_guardar").hide();
                  var currentNode = $("#tree_cuentas").jstree().get_selected(true)[0];
                  currentNode.state.opened = true;
                  $("#tree_cuentas").jstree().refresh(currentNode);

                } 
              }
            });
          } else {
            alert("Ya existe el codigo de cuenta: " + codigocuenta);
          }  
        }
      });
      
    });  


    function elimina_cuenta(id) {
        if (conf_del()) {
          $.ajax({
            url: base_url + "contabilidad/contab_plancuentas/cuenta_tiene_operaciones",
            data: { id: id },
            type: 'POST',
            dataType: 'json',
            success: function(json) {
              if (json.resu == 0){
                $.ajax({
                  url: base_url + "contabilidad/contab_plancuentas/del_cuenta",
                  data: { id: id },
                  type: 'POST',
                  dataType: 'json',
                  success: function(json) {
                    var ref = $('#tree_cuentas').jstree(true),
                    sel = ref.get_selected();
                    if(!sel.length) { return false; }
                    ref.delete_node(sel);
                  }
                });
              } else {
                alert("No es posible eliminar la cuenta, ya tiene operaciones registradas.");
              }  
            }
          });
      }
    }


    function conf_del() {
        return  confirm("¿Confirma que desea eliminar esta cuenta?");
    }

    function importar_cuentas(){
      location.replace("<?php print $base_url;?>contab_plancuentasimportar");
    }

/*
    $.contextMenu({
        selector: '.context-menu-one', 
        callback: function(key, options) {
            alert("entro menu");
            var m = "clicked: " + key;
            alert(m);
        },
        items: {
            "edit": {name: "Edit", icon: "edit"},
            "cut": {name: "Cut", icon: "cut"},
           copy: {name: "Copy", icon: "copy"},
            "paste": {name: "Paste", icon: "paste"},
            "delete": {name: "Delete", icon: "delete"},
            "sep1": "---------",
            "quit": {name: "Quit", icon: function(){
                return 'context-menu-icon context-menu-icon-quit';
            }}
        }
    });

    $('.context-menu-one').on('click', function(e){
        alert(this.id);

    })
*/


  });  


</script>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-sitemap"></i> Plan de Cuentas 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">
<!--                     <div class="box-header with-border">
                      <h3 class="box-title"></i> Datos de Cuentas</h3>
                    </div>
 -->                    <div class="box-body">

                      <div class="row">
                        <div class="col-md-6" style="overflow: scroll; height: 420px;">
                            <div class="col-xs-12">
                              <div id="tree_cuentas"> </div>
                            </div>  
                        </div>

                        <div id="paneldatos" class="col-md-6">

                          <!-- Grupo  -->
                          <div style="" class="form-group col-md-12">
                            <div class="col-md-4">                             
                              <label for="cmb_grupo">Grupo</label>
                            </div>
                            <div class="col-md-8">                             
                              <select id="cmb_grupo" name="cmb_grupo" class="form-control" disabled="true">
                              <?php 
                                if(@$grupo != NULL){ ?>
                                <?php } else { ?>
                                <option  value="" selected="TRUE">Seleccione Grupo...</option>
                                <?php } 
                                  if (count($grupo) > 0) {
                                    foreach ($grupo as $obj):
                                        if(@$cuenta != NULL){
                                            if($obj->id == $cuenta->idgrupocuenta){ ?>
                                                 <option value="<?php  print $obj->id; ?>" selected="TRUE"> <?php  print $obj->descripcion; ?> </option>
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $obj->id; ?>" > <?php  print $obj->descripcion; ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $obj->id; ?>" > <?php  print $obj->descripcion; ?> </option>
                                            <?php
                                            }   ?>
                                        <?php
                                    endforeach;
                                  }
                                ?>
                              </select>                                  
                            </div>
                          </div>

                          <!-- Nivel Empresa  -->
                          <div style="" class="form-group col-md-12">
                            <div class="col-md-4">                             
                              <label for="cmb_grupo">Nivel Empresa</label>
                            </div>
                            <div class="col-md-8">                             
                              <select id="cmb_empresa" name="cmb_empresa" class="form-control" disabled="true">
                              <?php 
                                if(@$empresa != NULL){ ?>
                                <?php } else { ?>
                                <option  value="" selected="TRUE">Seleccione Nivel Empresa...</option>
                                <?php } 
                                  if (count($empresa) > 0) {
                                    foreach ($empresa as $obj):
                                        if(@$cuenta != NULL){
                                            if($obj->id == $cuenta->idempresa){ ?>
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
                          </div>

                          <div style="" class="form-group col-md-12">
                            <div class="col-md-4">                             
                              <label for="cmb_grupo">Cuenta Superior</label>
                            </div>
                            <div class="col-md-8">                             
                              <input type="text" class="form-control validate[required] text-left" id="txt_cuentasuperior" name="txt_cuentasuperior" value="<?php if (@$cuenta != NULL) { print $cuenta->idcuentasuperior; } ?>" readonly>
                            </div>  
                          </div>

                          <div style="" class="form-group col-md-12">
                            <div class="col-md-4">                             
                              <label for="cmb_grupo">Codigo</label>
                            </div>
                            <div class="col-md-6">                             
                              <input type="text" class="form-control validate[required] text-left" id="txt_codigosuperior" name="txt_codigosuperior" value="<?php if (@$cuenta != NULL) { print $cuenta->codigosuperior; } ?>" readonly>
                            </div>  
                            <div class="col-md-2">                             
                              <input type="text" class="form-control validate[required] text-left" id="txt_codigonivel" name="txt_codigonivel" value="<?php if (@$cuenta != NULL) { print $cuenta->codigonivel; } ?>" disabled="true">
                            </div>  
                          </div>


                          <div style="" class="form-group col-md-12">
                            <div class="col-md-4">                             
                              <label for="cmb_grupo">Descripcion</label>
                            </div>
                            <div class="col-md-8">                             
                              <input type="text" class="form-control validate[required] text-left" id="txt_descripcion" name="txt_descripcion" value="<?php if (@$cuenta != NULL) { print $cuenta->descripcion; } ?>" disabled="true">
                            </div>  
                          </div>

                          <!-- Nivel Empresa  -->
                          <div style="" class="form-group col-md-12">
                            <div class="col-md-4">                             
                              <label for="cmb_grupo">Naturaleza</label>
                            </div>
                            <div class="col-md-8">                             
                              <select id="cmb_naturaleza" name="cmb_naturaleza" class="form-control" disabled="true">
                              <?php 
                                if(@$naturaleza != NULL){ ?>
                                <?php } else { ?>
                                <option  value="" selected="TRUE">Seleccione Naturaleza...</option>
                                <?php } 
                                  if (count($naturaleza) > 0) {
                                    foreach ($naturaleza as $obj):
                                        if(@$cuenta != NULL){
                                            if($obj->id == $cuenta->naturaleza){ ?>
                                                 <option value="<?php  print $obj->id; ?>" selected="TRUE"> <?php  print $obj->descripcion; ?> </option>
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $obj->id; ?>" > <?php  print $obj->descripcion; ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $obj->id; ?>" > <?php  print $obj->descripcion; ?> </option>
                                            <?php
                                            }   ?>
                                        <?php
                                    endforeach;
                                  }
                                ?>
                              </select>                                  
                            </div>
                          </div>

                          <div style="" class="form-group col-md-12">
                            <div class="col-md-4">                             
                              <input id="txt_activo" type="checkbox" disabled="true"> Activa 
                            </div>  

                          </div>

                          <div style="" class="form-group col-md-12">
                            <div class="pull-right"> 
                                <button id="btn_guardar" type="button" class="btn bg-green-active btn-grad " style="display: none;">
                                  <i class="fa fa-plus-square"></i> Guardar
                                </button>                              
                            </div>

                          </div>

                          <input type="hidden" class="form-control validate[required] text-left" id="txt_id" name="txt_id" value="<?php if (@$cuenta != NULL) { print $cuenta->id; } ?>">
                          <input type="hidden" class="form-control validate[required] text-left" id="txt_idcuentasuperior" name="txt_idcuentasuperior" value="<?php if (@$cuenta != NULL) { print $cuenta->idcuentasuperior; } ?>">
                          <input type="hidden" class="form-control validate[required] text-left" id="txt_nivel" name="txt_nivel" value="<?php if (@$cuenta != NULL) { print $cuenta->nivel; } ?>">

                        </div>
  
                      </div>
                    </div>
                    <!-- /.box-body -->
                    <div  align="center" class="box-footer">
                        
                    </div>
                </div>
              <!-- /.box -->
            </div>


        </div>
    </section>
    <!-- /.content -->


</div>



  <!-- /.content-wrapper -->

