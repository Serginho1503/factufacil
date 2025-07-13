<?php
/* ------------------------------------------------
  ARCHIVO: infosr_lst.php
  DESCRIPCION: Contiene la vista principal del módulo de comprobantes electronicos.
  FECHA DE CREACIÓN: 28/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Comprobantes Electrónicos'</script>";
date_default_timezone_set("America/Guayaquil");

$parametro = &get_instance();
$parametro->load->model("Parametros_model");
$objtarifaiva = $parametro->Parametros_model->iva_get();
$tarifaiva = $objtarifaiva->valor * 100;

?>
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/css/jquery.timepicker.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/css/bootstrap-datepicker.standalone.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/lib/pikaday.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/lib/jquery.ptTimeSelect.css" />

<style type="text/css">
  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 
  .dropdown-menu > li > a {
    color: #fff;
  } 
</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

      /* CARGA DE DATOS EN EL DATATABLE */
     $('#dataTableVent').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'pageLength': -1,
        'ajax': "Infosri/listadoVentas",
        'columns': [
            {"data": "ver"},        
            {"data": "fecha"},
            {"data": "numero"},
            {"data": "estado"},
            {"data": "cliente"}, 
            {"data": "claveacceso"},
            {"data": "baseiva"},              
            {"data": "basenoiva"},              
            {"data": "montoiva"},              
            {"data": "descuento"},              
            {"data": "montototal"},
            {"data": "correoenviado"}
        ]

      });

     $('#dataTableRetencion').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "Infosri/listadoRetencionesCompra",
        'columns': [
            {"data": "ver"},        
            {"data": "fecha"},
            {"data": "numero"},
            {"data": "factura"},
            {"data": "estado"},
            {"data": "cliente"}, 
            {"data": "claveacceso"},
            {"data": "baseretiva"},              
            {"data": "retiva"},              
            {"data": "baseretrenta"},              
            {"data": "retrenta"}
        ]

      });

     $('#dataTableNotaCredito').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "Infosri/listadoNotasCredito",
        'columns': [
            {"data": "ver"},        
            {"data": "fecha"},
            {"data": "numero"},
            {"data": "estado"},
            {"data": "cliente"}, 
            {"data": "claveacceso"},
            {"data": "baseiva"},              
            {"data": "basenoiva"},              
            {"data": "montoiva"},              
            {"data": "descuento"},              
            {"data": "montototal"}
        ]
      });

     $('#dataTableGuiaRemision').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "Infosri/listadoGuiasRemision",
        'columns': [
            {"data": "ver"},        
            {"data": "fecha"},
            {"data": "numero"},
            {"data": "estado"},
            {"data": "transportista"}, 
            {"data": "claveacceso"},
            {"data": "fechaini"},              
            {"data": "fechafin"},              
            {"data": "comprobventa"},              
            {"data": "destinatario"}              
        ]
      });

  $.datepicker.setDefaults($.datepicker.regional["es"]);
  $('#fdesde').datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });
  $('#fdesde').on('changeDate', function(ev){
      $(this).datepicker('hide');
  });

  $('#fhasta').datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });
  $('#fhasta').on('changeDate', function(ev){
      $(this).datepicker('hide');
  });  

  /* ACTUALIZAR LISTADO DE VENTA POR RAGO DE FECHA */
  $('.actualiza').click(function(){
    var fhasta = $("#fhasta").val();
    var fdesde = $("#fdesde").val();
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Infosri/tmp_fecha');?>",
          data: { fdesde:fdesde, fhasta:fhasta }
        }).done(function (result) {

              $('#dataTableVent').DataTable().ajax.reload();
              $('#dataTableRetencion').DataTable().ajax.reload();
              $('#dataTableNotaCredito').DataTable().ajax.reload();
              $('#dataTableGuiaRemision').DataTable().ajax.reload();             
              
        }); 
  });


  var tipocmp = 1;
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var target = $(e.target).attr("href");       
      switch(target) {
          case "#tabretencion":
              tipocmp = 2; break;
          case "#tabnotacredito":
              tipocmp = 3; break;
          case "#tabguiaremision":
              tipocmp = 4; break;
          default:
          //#tabventa
              tipocmp = 1; 
      } 
  });     

  $(document).on('click', '.venta_send', function(){
      var id = $(this).attr('id');
      var numero = $(this).attr('name');
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 1},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
              $.blockUI({ message: '<h3> Enviando comprobante de factura ' + numero + ' al SRI ...</h3>' });

              $.ajax({
                  type: "POST",
                  dataType: "json",
                  data: {id: id},
                  url: base_url + "Infosri/EnviarSRI",
                  success: function(json) {
                    $.unblockUI();

                    if (json.resu == 1) { 
                      $('#dataTableVent').DataTable().ajax.reload();
                    }
                    else{
                      alert("Error: " + json.mensaje);
                    }  
                  }
              });
          }
      });
  });

  function enviar_factura_sri(id, numero){
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 1},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
              $.blockUI({ message: '<h3> Enviando comprobante de factura ' + numero + ' al SRI ...</h3>' });

              $.ajax({
                  type: "POST",
                  dataType: "json",
                  data: {id: id},
                  url: base_url + "Infosri/EnviarSRI",
                  success: function(json) {
                    $.unblockUI();

                    if (json.resu == 1) { 
                      if (listacmp.length == 0){
                        $('#dataTableVent').DataTable().ajax.reload();
                      }
                      envio_lote_factura();
                    }
                    else{
                      alert("Error: " + json.mensaje);
                    }  
                  }
              });
          }
      });   
  }

  listacmp = [];
  $(document).on('click', '#btn_venta_send', function(){
      $('.venta_send').each(function(index, el) {
        id = this.id;
        numero = $(this).attr('name');

        listacmp.push({ id: id, numero: numero});

        //enviar_factura_sri(id, numero);
        //return false;
      });   
/*
      Object.keys(listacmp).forEach( function(idx){
        alert("id " + listacmp[idx].id + " numero " + listacmp[idx].numero);
      });
*/
    envio_lote_factura();
  });

  function envio_lote_factura(){
/*      Object.keys(listacmp).forEach( function(key){
        id = listacmp[key].id;
        numero = listacmp[key].numero;
        break;
      });*/
      if (listacmp.length > 0){
        id = listacmp[0].id;
        numero = listacmp[0].numero;
        if (id != ''){
          listacmp.splice(listacmp.findIndex(item => item.id === id), 1);
          enviar_factura_sri(id, numero);
        }

      }
  }

  function envio_lote_factura00(){
      $('.venta_send').each(function(index, el) {
        id = this.id;
        numero = $(this).attr('name');

        enviar_factura_sri(id, numero);
        return false;
      });   
  }
     
  $(document).on('click', '.venta_pdf', function(){
      var id = $(this).attr('id');
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 1, id: id},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
            $.fancybox.open({
                type:'iframe',
                width: 1200,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST"
                },
                href: base_url + 'Infosri/AbrirPDF' 
              });
          }
      });
  });

  $(document).on('click', '.venta_mail', function(){
      var id = $(this).attr('id');
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 1, id: id},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
              $.blockUI({ message: '<h3> Enviando correo al cliente ...</h3>' });
              $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: base_url + "Infosri/EnviarCorreo",
                  success: function(json) {
                    $.unblockUI();
                    if (json.resu == 1)    
                      alert('El correo ha sido enviado al cliente');
                    else
                      alert('No se pudo enviar el correo al cliente');
                  }
              });
          }
      });
  });
  
  listamail = [];
  $(document).on('click', '#btn_venta_mail', function(){
      $('.chk_venta').each(function(index, el) {
        if ($(this).is(":checked")){
          id = this.id;
          numero = $(this).attr('name');

          listamail.push({ id: id, numero: numero});
        }  
      });   
    correo_lote_factura();
  });

  function correo_lote_factura(){
      if (listamail.length > 0){
        id = listamail[0].id;
        numero = listamail[0].numero;
        if (id != ''){
          listamail.splice(listamail.findIndex(item => item.id === id), 1);
          
          enviar_correo_factura_id(id, numero);
        }

      }else{
        $('#dataTableVent').DataTable().ajax.reload();
      }
  }

  function enviar_correo_factura_id(id, numero){
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 1, id: id},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
              $.blockUI({ message: '<h4> Enviando correo de factura ' + numero + ' al cliente ...</h4>' });
              $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: base_url + "Infosri/EnviarCorreo",
                  success: function(json) {
                    $.unblockUI();
                    if (json.resu == 1){
                      $('.chk_venta[id='+id+']').attr('checked',false);
                    }  
                    setTimeout(function() {
                        correo_lote_factura();
                    }, 500);
                      //correo_lote_factura();
                  }
              });
          }
      });
      
  }


  $(document).on('click', '.retencion_send', function(){
      var id = $(this).attr('id');
      var tipo = $(this).attr('name');
      tipo = tipo * 1;
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: tipo + 1},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
              $.blockUI({ message: '<h3> Enviando comprobante al SRI ...</h3>' });
              $.ajax({
                  type: "POST",
                  dataType: "json",
                  data: {id: id},
                  url: base_url + "Infosri/EnviarSRI",
                  success: function(json) {
                    $.unblockUI();
                    if (json.resu == 1) { 
                      $('#dataTableRetencion').DataTable().ajax.reload();
                    }
                    else{
                      alert("Error: " + json.mensaje);
                    }  
                  }
              });
          }
      });
  });
    
  $(document).on('click', '.retencion_pdf', function(){
      var id = $(this).attr('id');
      var tipo = $(this).attr('name');
      tipo = tipo * 1;
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: tipo + 1, id: id},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
            $.fancybox.open({
                type:'iframe',
                width: 1200,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST"
                },
                href: base_url + 'Infosri/AbrirPDF' 
              });
          }
      });
  });

  $(document).on('click', '.retencion_mail', function(){
      var id = $(this).attr('id');
      var tipo = $(this).attr('name');
      tipo = tipo * 1;
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: tipo + 1, id: id},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
              $.blockUI({ message: '<h3> Enviando correo al proveedor ...</h3>' });
              $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: base_url + "Infosri/EnviarCorreo",
                  success: function(json) {
                    $.unblockUI();
                    if (json.resu == 1)    
                      alert('El correo ha sido enviado al proveedor');
                    else
                      alert('No se pudo enviar el correo al proveedor');
                  }
              });
          }
      });
  });


  $(document).on('click', '.notacredito_send', function(){
      var id = $(this).attr('id');
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 4},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
              $.blockUI({ message: '<h3> Enviando comprobante al SRI ...</h3>' });
              $.ajax({
                  type: "POST",
                  dataType: "json",
                  data: {id: id},
                  url: base_url + "Infosri/EnviarSRI",
                  success: function(json) {
                    $.unblockUI();
                    if (json.resu == 1) { 
                      $('#dataTableNotaCredito').DataTable().ajax.reload();
                    }
                    else{
                      alert("Error: " + json.mensaje);
                    }  
                  }
              });
          }
      });
  });

  $(document).on('click', '.notacredito_pdf', function(){
      var id = $(this).attr('id');
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 4, id: id},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
            $.fancybox.open({
                type:'iframe',
                width: 1200,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST"
                },
                href: base_url + 'Infosri/AbrirPDF' 
              });
          }
      });
  });

  $(document).on('click', '.notacredito_mail', function(){
      var id = $(this).attr('id');
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 4, id: id},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
              $.blockUI({ message: '<h3> Enviando correo al cliente ...</h3>' });
              $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: base_url + "Infosri/EnviarCorreo",
                  success: function(json) {
                    $.unblockUI();
                    if (json.resu == 1)    
                      alert('El correo ha sido enviado al cliente');
                    else
                      alert('No se pudo enviar el correo al cliente');
                  }
              });
          }
      });
  });


  $(document).on('click', '.guiaremision_send', function(){
      var id = $(this).attr('id');
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 5},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
              $.blockUI({ message: '<h3> Enviando comprobante al SRI ...</h3>' });
              $.ajax({
                  type: "POST",
                  dataType: "json",
                  data: {id: id},
                  url: base_url + "Infosri/EnviarSRI",
                  success: function(json) {
                    $.unblockUI();
                    if (json.resu == 1) { 
                      $('#dataTableGuiaRemision').DataTable().ajax.reload();
                    }
                    else{
                      alert("Error: " + json.mensaje);
                    }  
                  }
              });
          }
      });
  });

  $(document).on('click', '.guiaremision_pdf', function(){
      var id = $(this).attr('id');
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 5, id: id},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
            $.fancybox.open({
                type:'iframe',
                width: 1200,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST"
                },
                href: base_url + 'Infosri/AbrirPDF' 
              });
          }
      });
  });

  $(document).on('click', '.guiaremision_mail', function(){
      var id = $(this).attr('id');
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 5, id: id},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
              $.blockUI({ message: '<h3> Enviando correo al cliente ...</h3>' });
              $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: base_url + "Infosri/EnviarCorreo",
                  success: function(json) {
                    $.unblockUI();
                    if (json.resu == 1)    
                      alert('El correo ha sido enviado al cliente');
                    else
                      alert('No se pudo enviar el correo al cliente');
                  }
              });
          }
      });
  });


  $(document).on('click', '.venta_recuperar', function(){
      var id = $(this).attr('id');
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 1},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
            $.fancybox.open({
              type: "ajax",
              width: 550,
              height: 550,
              ajax: {
                 dataType: "html",
                 type: "POST",
                 data: {id: id}                
              },
              href: "<?php echo base_url('Infosri/edit_recuperacion');?>"
            });
          }
      });
  });

  $(document).on('click', '.btnrecuperar', function(){
      var id = $("#txt_id").val();
      var clave = $("#txt_clave").val();
      var numero = $("#txt_numero").val();
      numero = numero.split('-').join('');
      tmpnumero = clave.substr(24,15);
      if (numero != tmpnumero){
        alert("La clave de acceso no pertenece al número de comprobante");
        return false;
      }

      $.ajax({
          type: "POST",
          dataType: "json",
          data: {tipocomprobante: 1},
          url: base_url + "Infosri/tmp_tipocmpsri",
          success: function(json) {
              $.fancybox.close();
              $.blockUI({ message: '<h3> Enviando comprobante al SRI ...</h3>' });
              $.ajax({
                  type: "POST",
                  dataType: "json",
                  data: {id: id},
                  url: base_url + "Infosri/EnviarSRI",
                  success: function(json) {
                    $.unblockUI();
                    if (json.resu == 1) { 
                      $('#dataTableVent').DataTable().ajax.reload();
                    }
                    else{
                      alert("Error: " + json.mensaje);
                    }  
                  }
              });
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
      <i class="fa fa-list-alt"></i> Listado de Comprobantes Electrónicos
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
            
              <div id="buscrango" class="col-md-9">
                <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px; padding-right:0px; margin-right:0px;">
                  <label class="col-md-4" for="">Desde</label>
                  <div class="input-group col-md-8">
                    <input style="width:100px;" type="text" class="form-control text-center date start" id="fdesde" name="fdesde" value="<?php if (@$desde != NULL) { @$fec = str_replace('-', '/', @$desde); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print  date("d/m/Y"); } ?>">
                  </div>
                </div>              
                <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px; padding-left:0px; padding-right:0px; margin-right:30px;">
                  <label class="col-md-4" for="">Hasta</label>
                  <div class="input-group col-md-8">
                    <input style="width:100px;" type="text" class="form-control text-center date end" id="fhasta" name="fhasta" value="<?php if (@$hasta != NULL) { @$fec = str_replace('-', '/', @$hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print  date("d/m/Y"); }  ?>">
                  </div>
                </div>              
                <div class="form-group col-md-1 pull-left" style="margin-bottom: 0px; margin-top: 0x; padding-left:0px; padding-right:0px; width: 50px;">
                  <button type="button" class="btn btn-block btn-success actualiza"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
              </div>

              <div class="pull-right" style="padding-left: 10px;"> 
                <a id="btn_venta_send" class="btn btn-sm bg-blue-active color-palette btn-grad " href="#" data-original-title="" title="Enviar al SRI comprobantes en lote"><i class="fa fa-send"></i> Enviar Lote</a>
                <a id="btn_venta_mail" class="btn btn-sm btn-warning color-palette btn-grad " href="#" data-original-title="" title="Enviar correo a clientes en lote"><i class="fa fa-envelope"></i> Correo Lote</a>
              </div>                  

          </div>
          
          <div class="box-body">

            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#tabventa" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> VENTAS</a></li>                            
                <li ><a href="#tabretencion" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> RETENCIONES</a></li>                            
                <li ><a href="#tabnotacredito" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> NOTAS DE CRÉDITO</a></li>                            
                <li ><a href="#tabguiaremision" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> GUÍAS DE REMISIÓN</a></li>                            
              </ul>

              <div class="tab-content">

                <div class="tab-pane active" id="tabventa">

                  <div class="row">
                    <div class="col-xs-12">
                      <div class="box">
                        <div id="upd_tbventa" class="box-body table-responsive" style="height: 350px;">

                          <table id="dataTableVent" class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr >                             
                                  <th class="text-center col-md-1">Acción</th>
                                  <th class="text-center col-md-1">Fecha</th>  
                                  <th class="text-center col-md-1">#Factura</th>
                                  <th class="text-center col-md-1">Estado</th>
                                  <th>Cliente</th>
                                  <th class="text-center col-md-1">Clave Acceso</th>
                                  <th class="text-center col-md-1">Base IVA <?php print $tarifaiva; ?>%</th>                            
                                  <th class="text-center col-md-1">Base IVA 0%</th>
                                  <th class="text-center col-md-1">Monto IVA</th>
                                  <th class="text-center col-md-1">Descuento</th>
                                  <th class="text-center col-md-1">Monto Total</th>
                                  <th class="text-center col-md-1">Correo Enviado</th>
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

                <div class="tab-pane" id="tabretencion">

                  <div class="row">
                    <div class="col-xs-12">
                      <div class="box">
                        <div id="upd_tbret" class="box-body table-responsive">

                          <table id="dataTableRetencion" class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr >                             
                                  <th class="text-center col-md-1">Acción</th>
                                  <th class="text-center col-md-1">Fecha</th>  
                                  <th class="text-center col-md-1">#Retencion</th>
                                  <th class="text-center col-md-1">#Factura</th>
                                  <th class="text-center col-md-1">Estado</th>
                                  <th>Cliente</th>
                                  <th class="text-center col-md-1">Clave Acceso</th>
                                  <th class="text-center col-md-1">Base Retencion IVA</th>                            
                                  <th class="text-center col-md-1">Retencion IVA</th>
                                  <th class="text-center col-md-1">Base Retencion Renta</th>
                                  <th class="text-center col-md-1">Retencion Renta</th>
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

                <div class="tab-pane" id="tabnotacredito">

                  <div class="row">
                    <div class="col-xs-12">
                      <div class="box">
                        <div class="box-body table-responsive">

                          <table id="dataTableNotaCredito" class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr >                             
                                  <th class="text-center col-md-1">Acción</th>
                                  <th class="text-center col-md-1">Fecha</th>  
                                  <th class="text-center col-md-1">#Nota Crédito</th>
                                  <th class="text-center col-md-1">Estado</th>
                                  <th>Cliente</th>
                                  <th class="text-center col-md-1">Clave Acceso</th>
                                  <th class="text-center col-md-1">Base IVA <?php print $tarifaiva; ?>%</th>                            
                                  <th class="text-center col-md-1">Base IVA 0%</th>
                                  <th class="text-center col-md-1">Monto IVA</th>
                                  <th class="text-center col-md-1">Descuento</th>
                                  <th class="text-center col-md-1">Monto Total</th>
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

                <div class="tab-pane" id="tabguiaremision">

                  <div class="row">
                    <div class="col-xs-12">
                      <div class="box">
                        <div class="box-body table-responsive">

                          <table id="dataTableGuiaRemision" class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr >                             
                                  <th class="text-center col-md-1">Acción</th>
                                  <th class="text-center col-md-1">Fecha</th>  
                                  <th class="text-center col-md-1">#Guía Remisión</th>
                                  <th class="text-center col-md-1">Estado</th>
                                  <th>Transportista</th>
                                  <th class="text-center col-md-1">Clave Acceso</th>
                                  <th class="text-center col-md-1">Inicio</th>                            
                                  <th class="text-center col-md-1">Fin</th>
                                  <th class="text-center col-md-1">Comprobante Venta</th>
                                  <th class="text-center col-md-1">Destinatario</th>
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

