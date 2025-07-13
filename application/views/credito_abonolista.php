<?php
/* ------------------------------------------------
  ARCHIVO: Gastos.php
  DESCRIPCION: Contiene la vista principal del módulo de Gastos.
  FECHA DE CREACIÓN: 30/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Abonos de Créditos'</script>";
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
        'ajax': "listadoDataAbono",
        'columns': [
            {"data": "fecha"},
            {"data": "formapago"},
            {"data": "monto"},    
            {"data": "ver"}
        ]
      });

      $('#dataTableCuota').dataTable({
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
        'ajax': "listadoDataCuota",
        'columns': [
            {"data": "fecha"},
            {"data": "monto"},    
            {"data": "pagado"}    
        ]
      });


      /* AGREGAR GASTOS */
      $(document).on('click', '.abono_add', function(){
       
        /*var montopendiente = <?php print $objfact->montocredito - $objfact->abonos; ?>;*/
        var montopendiente = $('#montopendiente').val();
        $.fancybox.open({
            type: "ajax",
            width: 550,
            height: 550,
            ajax: {
              dataType: "html",
              type: "POST",
              data: {montopendiente: montopendiente},
            },
            href: base_url + "credito/add_abonocredito" 
        });
      })

    $(document).on('click', '.abono_edit', function(){
      var idreg = $(this).attr("id");
      var idfp = $(this).attr("name");
      var idventa = <?php print $objfact->id_venta; ?>;
      /*var montopendiente = <?php print $objfact->montocredito - $objfact->abonos; ?>;*/
      var montopendiente = $('#montopendiente').val();
      $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
            dataType: "html",
            type: "POST",
            data: {idreg: idreg, idfp: idfp, idventa: idventa, montopendiente: montopendiente },
          },
          href: base_url + "credito/edit_abonocredito" 
      });
    });


      /* ELIMINAR GASTOS */
      $(document).on('click', '.del_abono', function(){
        if (confirm("Desea eliminar el abono seleccionado?")){
          id = $(this).attr('id');
          numero = $(this).attr('name');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('credito/tmp_abonocreditoid');?>",
           data: {id: id},
           success: function(json) {

              $.ajax({
               type: "POST",
               dataType: "json",
               url: "<?php echo base_url('credito/del_abonocredito');?>",
               data: {id: id}
              }).done(function (json) {
                $('#dataTableAbono').DataTable().ajax.reload();
                $('#dataTableCuota').DataTable().ajax.reload();
                $('#montopendiente').val(json.resu);
                $('#labelpendiente').html("Valor Pendiente: " + parseFloat(json.resu).toFixed(2));                
                $(".abono_add").attr("disabled", false);

                contabilizar = <?php if(@$contabilizar != NULL) { print $contabilizar;} else { print 0;}; ?>;
                if (contabilizar == 1){
                  contabilizar_cobrodel(id, numero);
                }

              });

           }
          });
        }  
      })


    $(document).on('click', '.compab_print', function(){
      var idfac = <?php print $objfact->id_venta; ?>;
      var idreg = $(this).attr('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "credito/nroabono_tmp",
            data: { idfac: idfac, idreg: idreg },
            success: function(json) {
              $.fancybox.open({
                type:'iframe',
                width: 800,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST",
                   data: {idfac: idfac, idreg: idreg}
                },
                href: base_url + 'credito/abonopdf' 
              });
            }
        });
    });


    $(document).on('click', '.guardafp', function(){
        var idreg = $("#txt_idreg").val();
        var fp = $("#cmb_forpago").val();
        var monto = $("#txt_montofp").val();
        monto = monto.replace(',','');
        /*var idventa = $("#txt_idventa").val();*/
        var idventa = <?php print $objfact->id_venta; ?>;
        var fechat = $("#fechat").val();
        var tiptarjeta = $("#cmb_tarjeta").val();
        var nrotar = $("#txt_nrotar").val();
        var bco = $('#cmb_banco').val(); 
        var tbco = $('#cmbt_banco option:selected').val(); 
        var nrodoc = $("#txt_nrodoc").val();
        var descdoc = $("#txt_descdoc").val();
        var tnrodoc = $("#txt_tnrodoc").val();
        var tdescdoc = $("#txt_tdescdoc").val();        
        var fechae = $("#fechae").val();
        var fechac = $("#fechac").val();
        var nrocta = $("#txt_nrocta").val();
        var idcaja = $("#cmb_caja").val();
        
        if (fp == 0){
          alert("Debe seleccionar una forma de pago");   
          return false;
        }
        if ((monto.trim() == '') || (monto == 0)){
          alert("Debe ingresar el monto a pagar");   
          return false;
        }
        if ((fp != 1) && (fp != 1) && (bco == 0) && (tbco == 0)){
          alert("Debe seleccionar el banco");   
          return false;
        }
        if ((fp != 1) && (fp != 1) && (tbco != 0) && (tiptarjeta == 0)){
          alert("Debe seleccionar el tipo de tarjeta");   
          return false;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            data: {idreg: idreg, idventa: idventa, fp: fp, monto: monto, fechat: fechat, tiptarjeta: tiptarjeta, nrotar: nrotar, bco: bco, tbco: tbco, tnrodoc: tnrodoc, nrodoc: nrodoc, tdescdoc: tdescdoc, descdoc: descdoc, fechae: fechae, fechac: fechac, nrocta: nrocta, idcaja: idcaja},                
            url: base_url + "Credito/guardar_abonocredito",
            success: function(json) {
                $('#dataTableAbono').DataTable().ajax.reload();
                $('#dataTableCuota').DataTable().ajax.reload();
                $('#montopendiente').val(json.resu);
                $('#labelpendiente').html("Valor Pendiente: " + parseFloat(json.resu).toFixed(2)); 
                tmppend = parseFloat(json.resu);
                if (tmppend == 0){ 
                  $(".abono_add").attr("disabled", true);
                } else {
                  $(".abono_add").attr("disabled", false);
                } 

                contabilizar = <?php if(@$contabilizar != NULL) { print $contabilizar;} else { print 0;}; ?>;
                /*contabilizar = 0;
                if (json.contabilizar != '') { contabilizar = json.contabilizar; }*/
                if (contabilizar == 1){
                  nuevo = json.nuevo;
                  if (nuevo == true) { idreg = json.idreg; }
                  contabilizar_cobroins(idreg, nuevo);
                }

            }
        });  


        $.fancybox.close();
    });

    function contabilizar_cobroins(iddoccobro, nuevo){
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {id: iddoccobro },
            url: base_url + "contabilidad/contab_comprobante/cuentas_configuradas_cobrocaja",
            success: function(json) {
                if (json == false){
                    alert( "Revise las categorias contables de Cobro. Faltan cuentas por configurar." );
                    return true;
                }
                else{
                  if (nuevo == true){
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {id: iddoccobro },
                        url: base_url + "contabilidad/contab_comprobante/ins_comprobante_cobro",
                        success: function(json) {
                        }
                    });
                  }
                  else{
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {id: iddoccobro },
                        url: base_url + "contabilidad/contab_comprobante/upd_comprobante_cobro",
                        success: function(json) {
                        }
                    });
                  }  
                }
            }    
        });

    }

    function contabilizar_cobrodel(id, numero){
        sucursal = <?php print $objfact->id_sucursal;?>;
        if (sucursal == '') {sucursal = 0;}
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {sucursal: sucursal },
            url: base_url + "contabilidad/contab_comprobante/cuentas_configuradas_cobrosucursal",
            success: function(json) {
                if (json == false){
                    alert( "Revise las categorias contables de Cobro. Faltan cuentas por configurar." );
                    return true;
                }
                else{
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {id: id, numero: numero },
                        url: base_url + "contabilidad/contab_comprobante/del_comprobante_cobro",
                        success: function(json) {
                        }
                    });
                }
            }    
        });

    }

  }); 

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-money"></i> Abonos de Credito
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>credito">Creditos</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">

                <div class="form-group col-md-3">
                    <label for="lb_cant">Factura: <?php print $objfact->nro_factura ?></label>
                </div>

                <div class="form-group col-md-4">
                    <label for="lb_cant">Cliente: <?php print $objfact->nom_cliente ?></label>
                </div>

                <div class="form-group col-md-2">
                    <label for="lb_cant">Fecha: <?php $fec = str_replace('-', '/', $objfact->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec ?></label>
                </div>

                <div class="form-group col-md-3">
                    <label for="lb_cant">Fecha de Pago: <?php $fec = str_replace('-', '/', $objfact->fechalimite); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec ?></label>
                </div>

                <div class="form-group col-md-2" >
                    <label for="lb_cant">Total Factura: <?php print $objfact->montototal ?></label>
                </div>

                <div class="form-group col-md-2" style="padding: 0px;">
                    <label for="lb_cant">Abono Inicial: <?php print $objfact->abonoinicial ?></label>
                </div>

<!--                 <div class="form-group col-md-2" style="padding: 0px;">
                    <label for="lb_cant">Monto Interes: <?php print $objfact->montointerescredito ?></label>
                </div>
 -->
                <div class="form-group col-md-3" style="padding: 0px;">
                    <label for="lb_cant">Monto Credito: <?php print $objfact->montocredito ?></label>
                </div>

                <div class="form-group col-md-2" style="padding: 0px;">
                    <label for="lb_cant">Retención: <?php print $objfact->retencion ?></label>
                </div>

                <div class="form-group col-md-3" style="padding: 0px;">
                    <input type="hidden" id="montopendiente" name="montopendiente" value="<?php print number_format($objfact->montototal - $objfact->abonos,2) ?>" >    
                    <label for="lb_cant" id="labelpendiente">Valor Pendiente: <?php print number_format($objfact->montocredito - $objfact->abonos - $objfact->retencion + $objfact->abonoinicial,2) ?> </label>
                </div>

                    </div>
                    <!-- Cuotas -->
                    <div class="form-group col-md-6">
                     <div class="box">
                      <div class="box-header with-border">
                       <h3 class="box-title">Cuotas</h3>
                        <div class="row">
                          <div class="col-xs-12">
                              <div class="box-body table-responsive">
                                <table id="dataTableCuota" class="table table-bordered table-striped">
                                  <thead>
                                    <tr >
                                      <th>Fecha</th>
                                      <th>Monto</th>
                                      <th>Pagado</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                                </table>
                              </div>
                          </div>
                        </div>
                      </div>
                     </div>
                    </div>
                      <!-- /.box-body -->

                    <!-- Abonos -->
                    <div class="form-group col-md-6">
                     <div class="box">
                      <div class="box-header with-border">
                      <h3 class="box-title">Abonos</h3>
                      
                      <div class="pull-right"> 
                          
                          <button type="button" class="btn btn-success btn-grad abono_add" id="<?php print $objfact->id_venta?>" <?php if (($objfact->montocredito - $objfact->abonos + $objfact->abonoinicial) == 0) { print "disabled";} ?>>
                            <i class="fa fa-plus-square" ></i> Añadir
                          </button>                     
                      </div>

                      <div class="row">
                        <div class="col-xs-12">
                            <div class="box-body table-responsive">
                              <table id="dataTableAbono" class="table table-bordered table-striped">
                                <thead>
                                  <tr >
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

