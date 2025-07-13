<?php
/* ------------------------------------------------
  ARCHIVO: Gastos.php
  DESCRIPCION: Contiene la vista principal del módulo de Gastos.
  FECHA DE CREACIÓN: 30/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Abonos'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {
      /* CARGA DE DATOS EN EL DATATABLE */
      $('#dataTableAbono').dataTable({
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
        'ajax': "Compraabono/listadoDataAbono",
        'columns': [
            {"data": "id"},
            {"data": "fecha"},
            {"data": "formapago"},
            {"data": "monto"},    
            {"data": "ver"}
        ]
      });


      /* AGREGAR GASTOS */
      $(document).on('click', '.abono_add', function(){
        id = $(this).attr('id');
        montopendiente = $('#montopendiente').val();
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('compraabono/tmp_compra');?>",
           data: {id: id},
           success: function(json) {
              $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                    dataType: "html",
                    type: "POST",
                    data: {id: id, montopendiente : montopendiente}
                },
                href: "<?php echo base_url('compraabono/add_abono');?>", 
                success: function(json) {
                    if (parseInt(json.resu) > 0) {
                        location.replace("<?php print $base_url;?>compraabono");
                    } else {
                        alert("Error de conexión");
                    }
                }              
              });
           }
        }); 
      })

      $(document).on('click', '.guardar_abono', function(){
        txt_formapago = $("#txt_formapago").val();
        txt_monto = $("#txt_monto").val();
        txt_nrodoc = $("#txt_nrodoc").val();
        txt_desc = $("#txt_desc").val();
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('compraabono/adicionar');?>",
           data: {txt_formapago: txt_formapago, txt_monto: txt_monto,
                  txt_nrodoc: txt_nrodoc, txt_desc: txt_desc },
           success: function(json) {
            $.fancybox.close();
            $('#dataTableAbono').DataTable().ajax.reload();
            if (json.montopendiente !== null){
              $('#labelpendiente').html('Valor Pendiente: ' + json.montopendiente);
            }
            contabilizar = <?php if(@$contabilizar != NULL) { print $contabilizar;} else { print 0;}; ?>;            
            if ((json.resu !== null) && (contabilizar == 1)){
              iddoccobro = json.resu;
              $.ajax({
                type: "POST",
                dataType: "json",
                data: {id: iddoccobro },
                url: base_url + "contabilidad/contab_comprobante/cuentas_configuradas_pagocompra",
                success: function(json) {
                    if (json == false){
                        alert( "Revise las categorias contables de Pago. Faltan cuentas por configurar." );
                        return true;
                    }
                    else{
                      $.ajax({
                          type: "POST",
                          dataType: "json",
                          data: {id: iddoccobro },
                          url: base_url + "contabilidad/contab_comprobante/ins_comprobante_pagodoccompra",
                          success: function(json) {
                          }
                      });
                    }
                }    
              }); 
            }  
           }
        }); 
      })


      /* MODIFICAR GASTOS */
      $(document).on('click', '.edi_bonos', function(){
        id = $(this).attr('id');
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('gastos/tmp_compra');?>",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) == 1) {
                 location.replace("<?php print $base_url;?>gastos/upd_gastos");
              } else {
                 alert("Error de conexión");
              }
           }
        }); 
      })

      /* ELIMINAR GASTOS */
      $(document).on('click', '.del_abono00', function(){
          id = $(this).attr('id');
          numero = $(this).attr('name');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('compraabono/tmp_abono');?>",
           data: {id: id},
           success: function(json) {
              $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST"
                },
                href: "<?php echo base_url('compraabono/del_abono');?>", 
                success: function(json) {
                    alert(json.resu);
                    if (parseInt(json.resu) > 0) {
                      //$('#dataTableAbono').DataTable().ajax.reload();

                      $.ajax({
                          type: "POST",
                          dataType: "json",
                          data: {id: id },
                          url: base_url + "contabilidad/contab_comprobante/cuentas_configuradas_pagocompra",
                          success: function(json) {
                              if (json == false){
                                  alert( "Revise las categorias contables de Pago. Faltan cuentas por configurar." );
                                  return true;
                              }
                              else{
                                  $.ajax({
                                      type: "POST",
                                      dataType: "json",
                                      data: {id: id, numero: numero },
                                      url: base_url + "contabilidad/contab_comprobante/del_comprobante_pago",
                                      success: function(json) {
                                      }
                                  });
                              }
                          }    
                      });

                    } else {
                       alert("Error de conexión");
                    }
                }              
              });
           }
        });
      })

      /* ELIMINAR GASTOS */
      $(document).on('click', '.del_abono', function(){
          id = $(this).attr('id');
          numero = $(this).attr('name');
          id_compra = $("#idcompra").val();
          if (confirm("Desea eliminar el abono seleccionado?")){
            $.ajax({
              type: "POST",
              dataType: "json",
              url: "<?php echo base_url('compraabono/eliminar');?>",
              data: {id_abono: id, id_compra: id_compra},
              success: function(json) {
                  if (parseInt(json.resu) > 0) {
                    $('#dataTableAbono').DataTable().ajax.reload();
                    if (json.montopendiente !== null){
                      $('#labelpendiente').html('Valor Pendiente: ' + json.montopendiente);
                    }
                    
                    contabilizar = <?php if(@$contabilizar != NULL) { print $contabilizar;} else { print 0;}; ?>;            
                    if (contabilizar == 1){
                      $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {id: id },
                        url: base_url + "contabilidad/contab_comprobante/cuentas_configuradas_pagocompra",
                        success: function(json) {
                            if (json.resu == false){
                                alert( "Revise las categorias contables de Pago. Faltan cuentas por configurar." );
                                return false;
                            }
                            else{
                              iddocpago = json.iddocpago;
                              $.ajax({
                                  type: "POST",
                                  dataType: "json",
                                  data: {id: iddocpago, numero: numero },
                                  url: base_url + "contabilidad/contab_comprobante/del_comprobante_pago",
                                  success: function(json) {
                                  }
                              });
                            }
                        }    
                      });  
                    }
                  }  
              }    
            });
          }  
      });



    /* Boton del listado para imprimir Abono */
    $(document).on('click', '.compab_print', function(){
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
                href: "<?php echo base_url('compraabono/imprimirabono');?>" 
              }); 
      });      

    }); 

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-money"></i> Abonos de Compras
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>creditocompra">Cuentas por Pagar</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <input type="hidden" id="idcompra" name="idcompra" value="<?php print $objfact->id_comp; ?>" >    
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <div class="pull-right"> 
                          <?php if ($objfact->montototal > $objfact->abonos) { ?>
                          <button type="button" class="btn btn-success btn-grad abono_add" id="<?php print $objfact->id_comp?>">
                            <i class="fa fa-plus-square" ></i> Añadir
                          </button>                     
                          <?php } ?>
                      </div>

                      <div class="form-group col-md-4">
                          <label for="lb_cant">Factura: <?php print $objfact->nro_factura .' ('.$objfact->fecha.')' ?></label>
                      </div>

                      <div class="form-group col-md-4">
                          <label for="lb_cant">Fecha de Pago: <?php print $objfact->fecha_pago ?></label>
                      </div>

                      <div class=" col-md-12">
                        <div class="form-group col-md-4">
                            <label for="lb_cant">Valor Total: <?php print $objfact->montototal ?></label>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="lb_cant">Valor Retención: <?php print $objfact->retencion_iva + $objfact->retencion_renta ?></label>
                        </div>

                        <div class="form-group col-md-4">
                            <input type="hidden" id="montopendiente" name="montopendiente" value="<?php print number_format($objfact->montototal - $objfact->abonos - $objfact->retencion_iva - $objfact->retencion_renta,2) ?>" >    
                            <label id="labelpendiente" for="lb_cant">Valor Pendiente: <?php print number_format($objfact->montototal - $objfact->abonos - $objfact->retencion_iva - $objfact->retencion_renta,2) ?></label>
                        </div>
                      </div>  
                    </div>
                    <div class="box-body">

                      <div class="row">
                        <div class="col-xs-12">
                            <div class="box-body table-responsive">
                              <table id="dataTableAbono" class="table table-bordered table-striped">
                                <thead>
                                  <tr >
                                    <th>Id</th>  
                                    <th>Fecha</th>
                                    <th>Forma Pago</th>
                                    <th>Monto</th>
                                    <th>Acción</th>
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

