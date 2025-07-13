<?php
/* ------------------------------------------------
  ARCHIVO: serviciotecnico.php
  DESCRIPCION: Contiene la vista principal del módulo de serviciotecnico.
  FECHA DE CREACIÓN: 06/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
  print "<script>document.title = '$nombresistema - Listado de Servicios Técnicos / Mantenimientos'</script>";
  date_default_timezone_set("America/Guayaquil");

$parametro = &get_instance();
$parametro->load->model("Parametros_model");
$impresionpdf = $parametro->Parametros_model->sel_facturapdf();
  

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

    var mostrardetalle = <?php if (@$mostrardetalle == 1) { print 1;} else { print 0;} ?>;
    var arrcolumns = [
            {"data": "ver"},                            
            {"data": "sucursal"},
            {"data": "fecha"},   
            {"data": "numero_orden"},
            {"data": "cliente"},
            {"data": "estado"}
        ]
    if (mostrardetalle == 1){
      arrcolumns.push({"data": "detalleservicio"})    
    }
    arrcolumns.push({"data": "descripcion"})    

    $('#TableObj').dataTable({
      "language":{  "lengthMenu":"Mostrar _MENU_ registros por página.",
                    "zeroRecords": "Lo sentimos. No se encontraron registros.",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros aún.",
                    "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                    "search" : "Búsqueda",
                    "LoadingRecords": "Cargando ...",
                    "Processing": "Procesando...",
                    "SearchPlaceholder": "Comience a teclear...",
                    "paginate": { "previous": "Anterior", "next": "Siguiente", }
                    },
        'ajax': "Serviciotecnico/listadoServicios",
        'columns': arrcolumns
    });

    $('#TableObj00').dataTable({
      "language":{  "lengthMenu":"Mostrar _MENU_ registros por página.",
                    "zeroRecords": "Lo sentimos. No se encontraron registros.",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros aún.",
                    "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                    "search" : "Búsqueda",
                    "LoadingRecords": "Cargando ...",
                    "Processing": "Procesando...",
                    "SearchPlaceholder": "Comience a teclear...",
                    "paginate": { "previous": "Anterior", "next": "Siguiente", }
                    },
        'ajax': "Serviciotecnico/listadoServicios",
        'columns': [
            {"data": "ver"},                            
            {"data": "sucursal"},
            {"data": "fecha"},   
            {"data": "numero_orden"},
            {"data": "cliente"},
            {"data": "descripcion"},
            {"data": "estado"}
        ]
    });


    $(document).on('click', '.ret_ver', function(){
      id = $(this).attr('id');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Serviciotecnico/tmp_serviciotecnico');?>",
        data: {id: id},
        success: function(json) {
          location.replace("<?php print $base_url;?>serviciotecnico/actualiza_servicio");
        }
      });
    });  

    $(document).on('click', '.ret_add', function(){
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Serviciotecnico/tmp_clearserviciotecnico');?>",
        success: function(json) {
          location.replace("<?php print $base_url;?>serviciotecnico/add_servicio");
        }
      });
    });

    $(document).on('click','.ret_del', function() {
      id = $(this).attr('id');
        if (conf_del()) {
          $.ajax({
            url: base_url + "Serviciotecnico/del_servicio",
            data: { id: id },
            type: 'POST',
            dataType: 'json',
            success: function(json) {
              $('#TableObj').DataTable().ajax.reload();
            }
          });
      }
      return false; 
    });


    function conf_del() {
        return  confirm("¿Confirma que desea eliminar el servicio?");
    }

    /* ACTUALIZAR LISTADO DE GASTOS POR RAGO DE FECHA */
    $('.actualiza').click(function(){
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();
      var estado = $('#cmb_estado option:selected').val();      
      var tecnico = $('#cmb_tecnico option:selected').val();      

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Serviciotecnico/tmp_serviciofecha');?>",
        data: { hasta: hasta, desde: desde, estado: estado, tecnico: tecnico }
      }).done(function (result) {
            $('#TableObj').DataTable().ajax.reload();
      }); 

    });

    /* Boton del listado para imprimir  */
    $(document).on('click', '.pro_imp00', function(){
      var id = $(this).attr('id');
      //alert(id);
      $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST",
                   data: {id: id}
                },
                href: "<?php echo base_url('Serviciotecnico/imprimirservicio');?>" 
              });
    });

    $(document).on('click', '.pro_imp', function(){
      var id = $(this).attr('id');
      var impresionpdf = <?php if(@$impresionpdf != NULL) { print $impresionpdf;} else { print 0;} ?>;
      if (impresionpdf == 2){
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Serviciotecnico/tmp_serviciotecnico');?>",
          data: {id: id},
          success: function(json) {
            $.fancybox.open({
                  type: "iframe",
                  width: 800,
                  height: 550,
                  ajax: {
                     dataType: "html",
                     type: "POST",
                  },
                  href: base_url + 'Serviciotecnico/print_pdf_servicio_cliente' 
            });
          }
        });
      }
      else{
        $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST",
                   data: {id: id}
                },
                href: "<?php echo base_url('Serviciotecnico/imprimirservicio');?>" 
              });

      }  
    });

    $(document).on('click', '.imp_etiqueta', function(){
      var id = $(this).attr('id');
      var impresionpdf = <?php if(@$impresionpdf != NULL) { print $impresionpdf;} else { print 0;} ?>;
      impresionpdf = 1
      if (impresionpdf == 1){
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Serviciotecnico/tmp_serviciotecnico');?>",
          data: {id: id},
          success: function(json) {
            $.fancybox.open({
                  type: "iframe",
                  width: 800,
                  height: 550,
                  ajax: {
                     dataType: "html",
                     type: "POST",
                  },
                  href: base_url + 'Serviciotecnico/print_pdf_servicio_etiqueta' 
            });
          }
        });
      }
      else{
        $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST",
                   data: {id: id}
                },
                href: "<?php echo base_url('Serviciotecnico/imprimirservicio');?>" 
              });

      }  
    });

    /* FACTURAR */
    $(document).on('click', '.pro_fac', function(){
        id = $(this).attr('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {id: id},
            url: base_url + "Serviciotecnico/facturar",
            success: function(json) {
              if(json.resu > 0){
                location.replace("<?php print $base_url;?>Facturar/factura_deposito");
              }else{
                alert("ERROR.");
              }
            }
        });

    })

    $(document).on('click', '#rpt_listaservicio', function(){  
        location.replace("<?php print $base_url;?>Serviciotecnico/xls_listadoservicio");
    });

    $(document).on('click', '#rpt_listarealizado', function(){  
        location.replace("<?php print $base_url;?>Serviciotecnico/xls_serviciorealizado");
    });

    $(document).on('click', '#rpt_listaproducto', function(){  
        location.replace("<?php print $base_url;?>Serviciotecnico/xls_listadoservicioproducto");
    });
    
     $(document).on('click', '#rpt_listamecanico', function(){  
        location.replace("<?php print $base_url;?>Serviciotecnico/xls_serviciomecanico");
    });

    $(document).on("click", ".enviarcorreo", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Serviciotecnico/genera_pdf_servicio_cliente",
            data: {id: id},
            success: function(json) {
              if (json.correo.trim() != ''){
                $.blockUI({ message: '<h3> Enviando Correo a: '+ json.cliente +'...</h3>' });
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: base_url + "Serviciotecnico/correoenviar",
                    data: { ruta: json.ruta, correo: json.correo, cliente: json.cliente,
                            sucursal: json.sucursal, orden: json.orden,
                            idsucursal: json.idsucursal },
                    success: function(json) {
                        $.unblockUI();
                        if(json == 1){
                            alert('El Correo fue Enviado');
                        }else{
                            alert('Error al enviar El Correo'); 
                        }
                    }
                });
              }
              else {
                alert('Debe definir el Correo del Cliente.');                 
              }  
            }
        });
    });



  }); 


</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-registered"></i> Lista de Servicios Técnicos / Mantenimientos
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
                  <div>                   

                    <div class="form-group col-md-2" style="margin-bottom: 0px; ">
                      <label class="control-label text-left" style="padding-left: 0px;">Desde</label>
                      <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" class="form-control pull-right validate[required]" id="desde" name="desde" value="<?php $fec =  str_replace('-', '/', $desde); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec; ?>">
                      </div>
                    </div> 

                    <div class="form-group col-md-3" style="margin-bottom: 0px; ">
                      <label class="control-label" style="padding-left: 0px;">Hasta</label>
                      <div class="input-group date ">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" class="form-control pull-right validate[required]" id="hasta" name="hasta" value="<?php $fec =  str_replace('-', '/', $hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec; ?>" style="padding-right: 0px;">

                        <span class="input-group-btn">
                          <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                        </span>

                      </div>
                    </div>

                    <div class="form-group col-md-2">
                      <label for="lb_res">Estado</label>
                        <select id="cmb_estado" name="cmb_estado" class="form-control actualiza">
                          <option  value="0" selected="TRUE">TODOS</option>
                          <?php
                            if (count($estados) > 0) {
                              foreach ($estados as $obj):
                          ?>
                                <option value="<?php  print $obj->id_estado; ?>" > <?php  print $obj->nombre_estado; ?> </option>
                          <?php
                              endforeach;
                            }
                          ?>
                        </select>          
                    </div>

                    <div class="form-group col-md-2">
                      <label for="lb_res">Técnico</label>
                        <select id="cmb_tecnico" name="cmb_tecnico" class="form-control actualiza">
                          <option  value="0" selected="TRUE">TODOS</option>
                          <?php
                            if (count($tecnicos) > 0) {
                              foreach ($tecnicos as $obj):
                          ?>
                                <option value="<?php  print $obj->id_empleado; ?>" > <?php  print $obj->nombre_empleado; ?> </option>
                          <?php
                              endforeach;
                            }
                          ?>
                        </select>          
                    </div>

                  </div> 

                  <div class="pull-right" style="color: white; margin-bottom: 0px; margin-top: 23px;">
                    <button type="button" class="btn btn-success btn-grad ret_add" >
                      <i class="fa fa-plus-square"></i> Añadir
                    </button>   
                  </div>

                  <div class="btn-group pull-right" style="color: white; margin-bottom: 0px; margin-top: 23px;">
                    <button type="button" class="btn bg-blue"><i class="fa fa-list" aria-hidden="true"></i> Reportes</button>
                    <button type="button" class="btn bg-blue dropdown-toggle" data-toggle="dropdown">
                      <span class="caret"></span>
                      <span class="sr-only"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a id="rpt_listaservicio" class="btn-primary" style="color: black;" href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i> Listado de Servicios</a></li>
                      <li><a id="rpt_listarealizado" class="btn-primary" style="color: black;" href="#"><i class="fa fa-credit-card" aria-hidden="true"></i>Servicios Realizados</a></li>
                      <li><a id="rpt_listaproducto" class="btn-primary" style="color: black;" href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i> Productos Utilizados</a></li>
                      <li><a id="rpt_listamecanico" class="btn-primary" style="color: black;" href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i> Reporte Mecánicos</a></li>
                     </ul>
                  </div>


                </div>  
                     

                <div class="box-body">

                  <div class="row">
                    <div class="col-xs-12">
                        <div class="box-body table-responsive">
                          <table id="TableObj" class="table table-bordered table-striped table-responsive">
                            <thead>
                              <tr >
                                <th>Accion</th>
                                <th>Sucursal</th>
                                <th>Fecha Ingreso</th>
                                <th>#Orden</th>
                                <th>Cliente</th>
                                <th>Estado</th>
                                <?php if (@$mostrardetalle == 1) { ?>
                                <th><?php print @$strdetalle; ?></th>
                                <?php } ?>
                                <th>Descripción</th>
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
        </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

