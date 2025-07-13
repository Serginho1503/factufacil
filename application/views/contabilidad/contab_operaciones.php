<?php
/* ------------------------------------------------
  ARCHIVO: contab_plancuentas.php
  DESCRIPCION: Contiene la vista del módulo de plan de cuentas.
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
  print "<script>document.title = '$nombresistema - Resumen de Operaciones'</script>";
  date_default_timezone_set("America/Guayaquil");

?>

<style type="text/css">

</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

      $.datepicker.setDefaults($.datepicker.regional["es"]);
      $('#desde').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy', 
          firstDay: 1
          });
      $('#desde').on('changeDate', function(ev){
          $(this).datepicker('hide');
      });

      $('#hasta').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy', 
          firstDay: 1
          });
      $('#hasta').on('changeDate', function(ev){
          $(this).datepicker('hide');
      });  

      $('.actualiza').click(function(){
        if ($('#paneldatos').is(":visible")){
          var id = $('#tree_cuentas').jstree().get_selected("id")[0].id;
          obtenerdatoscuenta(id);
        }
      });


    $("#tree_cuentas")
    .on('changed.jstree', function (e, data) {
        if (data.selected.length > 0){
          var id = data.instance.get_node(data.selected[0]).id;
          if (id == 0) {
            $('#paneldatos').hide();
          } else { 
            /*inhabilita_controles();*/
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
      /*  "plugins" : [ "contextmenu", "dnd", "state", "types" ],*/
    });

    function obtenerdatoscuenta(id){
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();
      var sucursal = $("#cmb_sucursal").val();
      if (sucursal == '') { sucursal = 0;}
      var empresa = $("#cmb_empresa").val();
      var pendiente = $("#pendiente").prop('checked');
      if (pendiente == true) {pendiente = 1;} else{pendiente=0;}
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('contabilidad/contab_balance/tmp_oper_fecha');?>",
        data: {cuenta: id, sucursal: sucursal, empresa: empresa, 
               desde: desde, hasta: hasta, pendiente: pendiente},
        success: function(json) {
          $('#dataTableObj').DataTable().ajax.reload();
        
          /*$("#txt_id").val(json.resu.id);*/
        }
      });
    }

    $(document).on('change', '#cmb_empresa', function(){
      var empresa = $('#cmb_empresa option:selected').val();
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('contabilidad/contab_balance/get_sucursal_empresa');?>",
          data: { empresa: empresa },
          success: function(json) {
              $('#cmb_sucursal').empty();
              json.forEach(function(json){
                  $('#cmb_sucursal').append('<option value="'+json.id_sucursal+'">'+json.nom_sucursal+'</option>');        
              });
          } 
      }); 

    });

      var table = $('#dataTableObj').dataTable({
            "ordering": false,
            "language":{  "lengthMenu":"Mostrar _MENU_ registros por página.",
                        "zeroRecords": "Lo sentimos. No se encontraron registros.",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "No hay registros aún.",
                        "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                        "search" : "Búsqueda",
                        "LoadingRecords": "Cargando ...",
                        "Processing": "Procesando...",
                        "SearchPlaceholder": "Comience a teclear...",
                        "paginate": { "previous": "Anterior",
                                      "next": "Siguiente", }
                      },
          'ajax': "contabilidad/contab_balance/listadoOperaciones",
          'columns': [
              {"data": "ver"},
              {"data": "fecha"},
              {"data": "numero"},
              {"data": "debito"},
              {"data": "credito"},
              {"data": "saldo"},
              {"data": "referencia"}
          ]
      });

      /* PDF  */
      $(document).on('click', '.print_cmp', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('contabilidad/contab_comprobante/tmp_comprobante');?>",
           data: {id: id},
           success: function(json) {
             $.fancybox.open({
                type:'iframe',
                width: 800,
                height: 550,
                ajax: {
                    dataType: "html",
                    type: "POST",
                    data: {id: id}
                },
                href: base_url + 'contabilidad/contab_comprobante/comprobantepdf' 
              });
           }
        });
      })

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
        <i class="fa fa-database"></i> Resumen de Operaciones 
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
                    <div class="box-header with-border">
                      <!-- <h3 class="box-title"></i> Datos de Cuentas</h3> -->
                    </div>
                    <div class="box-body">

                      <div class="row">
                        <div class="col-md-5">

                            <!-- Nivel Empresa  -->
                            <div style="" class="form-group col-md-12">
                              <div class="col-md-3">                             
                                <label for="cmb_grupo"> Empresa</label>
                              </div>
                              <div class="col-md-9">                             
                                <select id="cmb_empresa" name="cmb_empresa" class="form-control" >
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


                            <div class="col-xs-12 " style="overflow: scroll; height: 380px;">
                              <div id="tree_cuentas"> </div>
                            </div>  
                        </div>

                        <div id="paneldatos" class="col-md-7">

                          <!-- Sucursal  -->
                          <div style="" class="form-group col-md-7">
                            <div class="col-md-4">                             
                              <label for="cmb_sucursal"> Sucursal</label>
                            </div>
                            <div class="col-md-8">                             
                              <select id="cmb_sucursal" name="cmb_sucursal" class="form-control" >
                              <?php 
                                  if (count($sucursal) > 0) {
                                    foreach ($sucursal as $key=>$obj):
                                        if($key == 0){ ?>
                                            <option value="<?php  print $obj->id_sucursal; ?>" selected="TRUE"> <?php  print $obj->nom_sucursal; ?> </option>
                                        <?php
                                        }
                                        else{ ?>
                                            <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                                        <?php
                                        } 
                                    endforeach;
                                  }
                                ?>
                              </select>                                  
                            </div>
                          </div>

                          <div class="col-md-5">                             
                            <input id="pendiente" type="checkbox"> Incluir Asientos Pendientes 
                          </div>  


                          <div class="form-group col-md-6" style="margin-bottom: 0px; margin-top: 0px;">
                            <label class="col-sm-3 control-label text-left" style="padding-left: 0px;">Desde</label>
                            <div class="input-group date col-sm-7">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" class="form-control pull-right " id="desde" name="desde" value="<?php if(@$tmpdesde != NULL){ @$fec = str_replace('-', '/', @$tmpdesde); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} ?>">
                            </div>
                          </div> 

                          <div class="form-group col-md-6" style="margin-bottom: 0px; margin-top: 0px;">
                            <label class="col-sm-3 control-label text-left" style="padding-left: 0px;">Hasta</label>
                            <div class="input-group date col-sm-9">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" class="form-control pull-right " id="hasta" name="hasta" value="<?php if(@$tmphasta != NULL){ @$fec = str_replace('-', '/', @$tmphasta); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} ?>">
                                <span class="input-group-btn">
                                <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                                </span>
                            </div>
                          </div>  

                          <div class="box-body table-responsive">
                            <table id="dataTableObj" class="table table-bordered table-striped ">
                              <thead>
                                <tr >
                                    <th>Acción</th>
                                    <th>Fecha</th>
                                    <th>Número</th>
                                    <th>Débito</th>
                                    <th>Crédito</th>
                                    <th>Saldo</th>
                                    <th>Referencia</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>

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

