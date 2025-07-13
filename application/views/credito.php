<?php
/* ------------------------------------------------
  ARCHIVO: Compra.php
  DESCRIPCION: Contiene la vista principal del módulo de Compra.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Créditos'</script>";
date_default_timezone_set("America/Guayaquil");

?>
<style type="text/css">
  .form-control{
    font-size: 12px;
    height: 28px;
  }

  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 

  .pago{
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05) inset;
    margin-bottom: 20px;
    min-height: 20px;
    padding: 19px;  
    margin-left: 20px;  
  }

  .calmonto{
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05) inset;
    margin-bottom: 20px;
    min-height: 20px;
    padding: 19px;  
    margin-right: 20px;     
  }
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


    /* Reporte de Venta */
    $(document).on('click', '#rpt_credito', function(){    
        window.open('<?php print $base_url;?>Credito/reporte_credito');
    });


      /* CARGA DE DATOS EN EL DATATABLE */
     tablecomp=$('#dataTableComp').dataTable({
      rowCallback:function(row,data) {
        if(data["id_estado"] == '3')
        {
          $($(row)).css("background-color","#DD4B39");
        }
      },"language":{  "lengthMenu":"Mostrar _MENU_ registros por página.",
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
        'ajax': "Credito/listadoCreditos",
        'columns': [
            {"data": "ver"},
            {"data": "fecha"},           
            {"data": "cliente"},
            {"data": "cedula"},
            {"data": "factura"},
            {"data": "fechalimite"},  
            {"data": "dias"},  
            {"data": "estado"},  
            {"data": "montofactura"},  
            {"data": "abonoinicial"},  
            {"data": "montointerescredito"},  
            {"data": "montocredito"},  
            {"data": "retencion"},  
            {"data": "montopendiente"}  
        ]
      });


    /* Boton del listado para imprimir compra */
    $(document).on('click', '.cred_print', function(){
      var id = $(this).attr('id');
      $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST",
                   data: {id: id}
                },
                href: "<?php echo base_url('Credito/imprimircredito');?>" 
              });
    });


//
    $(document).on('click', '.abono_print', function(){
        var id = $(this).attr('id');
        var desde = '01/01/2019';
        var hasta = '01/01/2019';
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Credito/upd_filtroabonos');?>",
          data: {documento: id, desde: desde, hasta: hasta },
          success: function(json) {
              $.fancybox.open({
                type:'iframe',
                width: 800,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST"
                },
                href: base_url + 'credito/pdf_abonos_cliente' 
              });
          }    
        });
    });


      /* ACTUALIZAR LISTADO DE creditos */
      $(document).on('change', '#cmb_cliente,#cmb_estado, #cmb_sucursal, #chk_rangofecha, #desde, #hasta', function(){
        var cliente = $("#cmb_cliente").val();
        var estado = $("#cmb_estado").val();
        var sucursal = $("#cmb_sucursal").val();

        var rango = $('#chk_rangofecha').prop('checked');
        if (rango == true) { rango = 1; } else { rango = 0; }
        var desde = $("#desde").val();
        var hasta = $("#hasta").val();

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Credito/upd_filtrocredito');?>",
          data: { cliente: cliente, estado: estado, sucursal: sucursal,
                  rango: rango, desde: desde, hasta: hasta }
        }).done(function (result) {
              $('#dataTableComp').DataTable().ajax.reload();
              actualiza_monto();
              if (rango == 0){
                $('#desde').val(result.fechamin);
              }

        }); 

      });

    /* Boton de Abonos  */
      $(document).on('click', '.edit_abono', function(){
        id = $(this).attr('id');
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('Credito/tmp_creditoid');?>",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) == 1) {
                location.replace(base_url + 'Credito/vista_abonocredito');
              } else {
                 alert("Error de conexión");
              }
           }
        }); 
      })

    function actualiza_monto(){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Credito/get_total_credito",
            success: function(json) {
              var total = 0;
              if(json.pendiente != null){
                total = json.pendiente;
              }
              $('#monto').html(' $ ' + total);
            }
        });
    }

    $(document).on('click', '#chk_rangofecha', function(){
      actualiza_rango();
    })

    function actualiza_rango(){
      rango = $('#chk_rangofecha').prop('checked');
      if (rango == true){
        $('.rangofecha').show();
      }
      else{
        $('.rangofecha').hide();
      }     
    }

    $(document).on('click', '#rpt_abonocliente', function(){
        var cliente = $("#cmb_cliente").val();
        if (cliente == 0){
          alert("Seleccione el cliente");
          return false;
        }
        var id = 0;
        /*var desde = $(#desde).val();
        var hasta = $(#hasta).val();*/
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Credito/upd_filtroabonos');?>",
          data: {documento: id/*, desde: desde, hasta: hasta*/ },
          success: function(json) {
              $.fancybox.open({
                type:'iframe',
                width: 800,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST"
                },
                href: base_url + 'credito/pdf_abonos_cliente' 
              });
          }    
        });
    });


    actualiza_monto();
    actualiza_rango();

}); 



</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-truck"></i> Listado de Créditos
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>credito">Créditos</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DE LOS PRODUCTOS A COMPRAR -->
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <div> 

              <!-- SUCURSAL  -->
              <div style="" class="form-group col-md-3">
                <label for="lb_res">Sucursal</label>
                <select id="cmb_sucursal" name="cmb_sucursal" class="form-control">
                <?php 
                  if(@$sucursales != NULL){ ?>
                    <option  value="0" selected="TRUE">Todas las sucursales</option>
                  <?php } else { ?>
                  <?php } 
                    if (count($sucursales) > 0) {
                      foreach ($sucursales as $obj):
                          if(@$sucursalseleccionada != NULL){
                              if($obj->id_sucursal == $sucursalseleccionada){ ?>
                                   <option value="<?php  print $obj->id_sucursal; ?>" selected="TRUE"> <?php  print $obj->nom_sucursal; ?> </option>
                                  <?php
                              }else{ ?>
                                  <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                                  <?php
                              }
                          }else{ ?>
                              <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                              <?php
                              }   ?>
                          <?php
                      endforeach;
                    }
                  ?>
                </select>                                  
              </div>

              <!-- Clientes -->                            
              <div class="form-group col-md-3">
                  <label>Clientes</label>
                  <select id="cmb_cliente" name="cmb_cliente" class="form-control">
                      <?php 
                        if(@$clientes != NULL){ ?>
                        <option  value="0" selected="TRUE">Todos los clientes</option>
                      <?php } 
                          if (count($clientes) > 0) {
                            foreach ($clientes as $row):
                                if(@$clienteseleccionado != NULL){
                                    if($row->id_cliente == $clienteseleccionado){ ?>
                                         <option value="<?php  print $row->id_cliente; ?>" selected="TRUE"> <?php  print $row->nom_cliente ?> </option>
                                        <?php
                                    }else{ ?>
                                        <option value="<?php  print $row->id_cliente; ?>" > <?php  print $row->nom_cliente ?> </option>
                                        <?php
                                    }
                                }else{ ?>
                                    <option value="<?php  print $row->id_cliente; ?>"> <?php  print $row->nom_cliente ?> </option>
                                    <?php
                                    }   ?>
                                <?php

                            endforeach;
                      }
                      ?>
                  </select>                                    
              </div>

              <!-- Estados -->                            
              <div class="form-group col-md-2">
                  <label>Estado de Crédito</label>
                  <select id="cmb_estado" name="cmb_estado" class="form-control">
                      <?php 
                        if(@$estados != NULL){ ?>
                        <option  value="0" selected="TRUE">Todos los créditos</option>
                      <?php } 
                          if (count($estados) > 0) {
                            foreach ($estados as $row):
                                if(@$estadoseleccionado != NULL){
                                    if($row->id_estado == $estadoseleccionado){ ?>
                                         <option value="<?php  print $row->id_estado; ?>" selected="TRUE"> <?php  print $row->nombre_estado ?> </option>
                                        <?php
                                    }else{ ?>
                                        <option value="<?php  print $row->id_estado; ?>" > <?php  print $row->nombre_estado ?> </option>
                                        <?php
                                    }
                                }else{ ?>
                                    <option value="<?php  print $row->id_estado; ?>"> <?php  print $row->nombre_estado ?> </option>
                                    <?php
                                    }   ?>
                                <?php

                            endforeach;
                      }
                      ?>
                  </select>                                    
              </div>


<!--                 <div class="col-md-3" style="margin-bottom: 0px; margin-top: 32px;">
 -->                <div class="form-group col-md-2">
                  <label>Monto Pendiente:</label>

                   <div id="monto"> <?php print number_format(@$totalg,2,",","."); ?></div>
                </div>  

            </div>

            <div class="btn-group pull-right" style="color: white; ">
              <button type="button" class="btn bg-blue"><i class="fa fa-list" aria-hidden="true"></i> Reportes</button>
              <button type="button" class="btn bg-blue dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only"></span>
              </button>
              <ul class="dropdown-menu" role="menu">
                <li><a id="rpt_credito" class="btn-primary" style="color: black;" href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i> Estado de Créditos</a></li>
                <li><a id="rpt_abonocliente" class="btn-primary" style="color: black;" href="#"><i class="fa fa-credit-card" aria-hidden="true"></i>Abonos de Créditos</a></li>
              </ul>
            </div>


<!--             <div class="pull-right"> 
              <a id="rpt_credito" class="btn bg-light-blue color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-list-alt"></i> Reporte </a>            
            </div>
 -->

            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
              <div class="col-md-3" style="padding-left: 0px; padding-right: 0px;">
                  <label class="col-md-12"><input type="checkbox" name="chk_rangofecha" id="chk_rangofecha" class="minimal-red" <?php if(@$rangofecha != NULL){ if(@$rangofecha == 1){ print "checked='' ";} }?> > Seleccionar Rango de Fecha</label>
              </div> 

              <div class="col-md-9 rangofecha" style="padding-left: 0px; padding-right: 0px; padding-top: 0px; margin: 0px;">

                <div class="form-group col-md-4" style="margin-bottom: 0px; margin-top: 0px;">
                  <label >Desde</label>
                  <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" class="form-control pull-right validate[required]" id="desde" name="desde" value="<?php print  date("d/m/Y"); ?>">
                  </div>
                </div>  
                
                <div class="form-group col-md-4" style="margin-bottom: 0px; margin-top: 0px;">
                  <label >Hasta</label>
                  <div class="input-group ">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" class="form-control pull-right validate[required]" id="hasta" name="hasta" value="<?php print  date("d/m/Y"); ?>">

<!--                     <span class="input-group-btn">
                      <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                    </span>
 -->
                  </div>
                </div>  

              </div> 

            </div> 


          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div class="box-body table-responsive">
                    <table id="dataTableComp" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr >
                          <!-- <th>Id</th>  --> 
                          <th>Acción</th>
                          <th>Fecha</th>
                          <th>Cliente</th>
                          <th>C.I./R.U.C.</th>
                          <th>Factura</th>
                          <th>Fecha Plazo</th>
                          <th>Dias Plazo</th>
                          <th>Estado</th>
                          <th>Monto Factura</th>
                          <th>Abono Inicial</th>
                          <th>Interes</th>
                          <th>Monto Crédito</th>
                          <th>Retención</th>
                          <th>Pendiente</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.box-body -->
                </div>
              </div>
            </div>
          </div>


          <div   align="center" class="box-footer">
            <hr class="linea"> 
              <div class="row" style="margin-top:20px">



              </div><!--/row-->



          </div>
        </div>

      </div>           
    </div>
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

