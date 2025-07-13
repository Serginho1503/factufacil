<?php
/* ------------------------------------------------
  ARCHIVO: Compra.php
  DESCRIPCION: Contiene la vista principal del módulo de Compra.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Compra'</script>";
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

    .autocomplete-jquery-results{
        border:1px solid silver;
        float:right;
        margin-top:2px;
        position:absolute;
        display: none;
        z-index: 999 !important;
    }

    /*Esta clase se activa cuando el usuario se mueve por las sugerencias*/
    .autocomplete-jquery-mark{
        color:black;
        background-color: #E0F0FF !important;
    }

    /* Cada sugerencia va a llevar esta clase, por lo tanto tomara el estilo siguiente */
    .autocomplete-jquery-item{
        border-bottom: 1px solid lightgray;
        display: block;
        height: 25px;
        padding-top: 5px;
        text-decoration: none;     
        padding-left: 3px;  
        background-color: white;
    }
    .autocomplete-jquery-results{
         box-shadow: 1px 1px 3px black;
    }
    /* Al pasar por encima de las sugerencias*/
    .autocomplete-jquery-item:hover{
        background-color: #E0F0FF;
        color:black;
    }


    .dt-alignright { text-align: right; }

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
    $(document).on('click', '#rpt_compra', function(){    
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();
      var sucursal = $("#cmb_sucursal").val();

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Compra/tmp_reporte');?>",
        data: { hasta: hasta, desde: desde, sucursal: sucursal },
        success: function(json) {
          window.open('<?php print $base_url;?>Compra/reporte');
        }
      });    
    });

      /* CARGA DE DATOS EN EL DATATABLE */
     tablecomp=$('#dataTableComp').dataTable({
      rowCallback:function(row,data) {
        if(data["estatus"] == '3')
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
        'ajax': "Compra/listadoDataComp",
        'columns': [
            {"data": "ver"},
            {"data": "fecha"},           
            {"data": "proveedor"},
            {"data": "factura"},
            {"data": "monto"},  
            {"data": "categoria"},  
            {"data": "formapago"},  
            {"data": "estado"}  
        ]
      });

    /* Boton del listado para imprimir compra */
    $(document).on('click', '.comp_print', function(){
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
                href: "<?php echo base_url('Compra/imprimircompra');?>" 
              });
    });

      $(document).on('click', '.add_compra', function(){

        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Cajachica/existeapertura",
            success: function(json) {
              location.replace("<?php print $base_url;?>compra/agregar");
            }
        });

      });


      /* ACTUALIZAR LISTADO DE GASTOS POR RAGO DE FECHA */
      $('.actualiza').click(function(){
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();
      //var empresa = $("#cmb_empresa").val();
      var sucursal = $("#cmb_sucursal").val();

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Compra/tmp_reporte');?>",
          data: { hasta: hasta, desde: desde, sucursal: sucursal }
        }).done(function (result) {
              $('#dataTableComp').DataTable().ajax.reload();
              actualiza_monto();
        }); 

      });

      /* ANULAR FACTURA */
      $(document).on('click', '.anu_fact', function(){
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
                  href: "<?php echo base_url('compra/confirmar_anulacion');?>",
                   success: function(json) {
                    $.fancybox.close();
                   }
                });
      });

    /* Boton de Abonos  */
      $(document).on('click', '.edit_abono', function(){
        id = $(this).attr('id');
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('compraabono/tmp_compra');?>",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) == 1) {
                 location.replace("<?php print $base_url;?>compraabono");
              } else {
                 alert("Error de conexión");
              }
           }
        }); 
      })

      $(document).on('click', '.ret_comp', function(){
        id = $(this).attr('id');
        var consfinal = $(this).attr('name');

        if (consfinal == '1'){
          alert("Para registrar la retencion la factura debe estar asociada a un cliente.");
          return false;
        }
        
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php print $base_url;?>Compra/temp_compret",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) > 0) {
                 location.replace("<?php print $base_url;?>Compra/compra_retencion");
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
            url: base_url + "Compra/upd_compra_total",
            success: function(json) {
              var total = 0;
              if(json.resu == null){
                total = 0;
              }else{
                total = json.resu
              }
              $('#monto').html('Monto: ' + total);
              //$('#monto').html('<strong>Monto: $ '+total+'</strong>');
            }
        });
    }

    $('#desdepro').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
      });
    $('#desdepro').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    $('#hastapro').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        firstDay: 1
      });
    $('#hastapro').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });  

    $('#dataTableCompraProv_Detalle').dataTable({
        "language":{ 
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "Compra/lstCompraProv_Detalle",
        'columns': [
            {"data": "ver"},           
            {"data": "fecha"},           
            {"data": "factura"},
            {"data": "tipopago"},  
            {"data": "producto"},  
            {"data": "cantidad","sClass": "dt-alignright"},  
            {"data": "unidadmedida"},  
            {"data": "preciocompra","sClass": "dt-alignright"},  
            {"data": "subtotal","sClass": "dt-alignright"},  
            {"data": "descmonto","sClass": "dt-alignright"},  
            {"data": "montoiva","sClass": "dt-alignright"},  
            {"data": "valortotal","sClass": "dt-alignright"}
        ]
      });

    $('#dataTableCompraProv_Producto').dataTable({
        "language":{ 
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "Compra/lstCompraProv_ResumenProducto",
        'columns': [
            {"data": "producto"},  
            {"data": "cantidad","sClass": "dt-alignright"},  
            {"data": "unidadmedida"},  
            {"data": "preciocompra","sClass": "dt-alignright"},  
            {"data": "subtotal","sClass": "dt-alignright"},  
            {"data": "descmonto","sClass": "dt-alignright"},  
            {"data": "montoiva","sClass": "dt-alignright"},  
            {"data": "valortotal","sClass": "dt-alignright"}
        ]
      });

    $('#dataTableCompraProv_Proveedor').dataTable({
        "language":{ 
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "Compra/lstCompraProv_ResumenProveedor",
        'columns': [
            {"data": "proveedor"},  
            {"data": "cantfacturas","sClass": "dt-alignright"},  
            {"data": "cantidad","sClass": "dt-alignright"},  
            {"data": "unidadmedida"},  
            {"data": "preciocompra","sClass": "dt-alignright"},  
            {"data": "subtotal","sClass": "dt-alignright"},  
            {"data": "descmonto","sClass": "dt-alignright"},  
            {"data": "montoiva","sClass": "dt-alignright"},  
            {"data": "valortotal","sClass": "dt-alignright"},
            {"data": "telefono"},  
            {"data": "correo"},  
            {"data": "direccion"}  
        ]
      });

    $('.actualizapro').click(function(){
    var hasta = $("#hastapro").val();
    var desde = $("#desdepro").val();
    var proveedor = $("#cmb_proveedor").val();
    var sucursal = $("#cmb_sucursalpro").val();
    var producto = $('#txt_idproducto').val();
    if (producto == '') { producto = 0; }

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Compra/tmp_reporte_prov');?>",
        data: { hasta: hasta, desde: desde, sucursal: sucursal,
                proveedor: proveedor, producto: producto }
      }).done(function (result) {
            $('#dataTableCompraProv_Detalle').DataTable().ajax.reload();
            $('#dataTableCompraProv_Producto').DataTable().ajax.reload();
            $('#dataTableCompraProv_Proveedor').DataTable().ajax.reload();
      }); 

    });

    $('.autocomplete').autocomplete();

    var autoparent = "";

    $('#txt_clinom').focus(function(){
      autoparent = "cliente";    
    });

    $('#txt_nombreproducto').focus(function(){
      autoparent = "producto";    
    });

    $(document).on('click', '.form-control', function(){  
      $('.autocomplete-jquery-results').each(function(index, el) {
        this.remove();
      });
    });

    /* MUESTRA DATOS DEL CLIENTE */
    $(document).on('click', '.autocomplete-jquery-item', function(){  
      var nom = $(this).text(); 
      if (autoparent == "producto"){
        autocomplete_producto(nom);
      } else {
        //autocomplete_cliente(nom);
      }
    });

    function autocomplete_producto(nom){
      if (nom === ""){
        alert("Debe ingresar un nombre");
        return false;
      }
      tmpnom = nom;
      pos = tmpnom.search(' - ');
      nom = tmpnom.substring(pos+3);      
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Producto/busca_producto_nombre');?>",
          data: {
              nom: nom
           },
          success: function(json) {
              $('#txt_codigoproducto').val(json.pro_codigobarra);
              $('#txt_idproducto').val(json.pro_id);
          }
      });

    }

    $(document).on('blur', '#txt_nombreproducto', function(){  
      var nom = $(this).val();      
      if (nom == ''){
        $('#txt_idproducto').val(0);
        $('#txt_codigoproducto').val('');
      } 
    });

    $(document).on('click', '.edit_compra', function(){
      id = $(this).attr('id');
     
      $.ajax({
         type: "POST",
         dataType: "json",
         url: "<?php print $base_url;?>Compra/temp_compret",
         data: {id: id},
         success: function(json) {
            if (parseInt(json.resu) > 0) {
               location.replace("<?php print $base_url;?>Compra/modificar");
            } else {
               alert("Error de conexión");
            }
         }
      }); 
    })


}); 



</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-shopping-cart"></i> Listado de Compras
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>compra">Compra</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DE LOS PRODUCTOS A COMPRAR -->
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
         <li class="active"><a href="#tabgeneral" data-toggle="tab"><i class="fa fa-edit" aria-hidden="true"></i> Edición de Compras</a></li>                            
         <li ><a href="#tabproveedor" data-toggle="tab"><i class="fa fa-search" aria-hidden="true"></i> Consultas por Proveedor</a></li>                                     
        </ul>

        <div class="tab-content">

          <div class="tab-pane active" id="tabgeneral">
            <div class="col-md-12" style="padding: 0px; margin: 0px;">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <div> 

                   <div  class="col-md-8" style="padding: 0px;">
                    <!-- Empresa -->
                    <div  class="form-group col-md-4" style="margin-bottom: 0px; margin-top: 8px;padding: 0px;">
                      <div class="form-group col-md-3"> 
                        <label for="lb_res">Sucursal</label> 
                      </div>
                      <div class="form-group col-md-9"> 
                        <select id="cmb_sucursal" name="cmb_sucursal" class="form-control">
                        <?php 
                          if(@$sucursales != NULL){ 
                            if (count($sucursales) > 0) {
                              foreach ($sucursales as $obj): 
                        ?>
                                     <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                        <?php
                              endforeach;
                            }
                          }  
                        ?>
                        </select>                                  
                      </div>
                    </div>

                      <div class="form-group col-md-4" style="margin-bottom: 0px; margin-top: 8px;padding: 0px;">
                        <label class="col-sm-3 control-label text-left" style="padding-left: 0px;">Desde</label>
                        <div class="input-group date col-sm-7">
                          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                          <input type="text" class="form-control pull-right validate[required]" id="desde" name="desde" value="<?php $fec =  str_replace('-', '/', $desde); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec; ?>">
                        </div>
                      </div> 

                      <div class="form-group col-md-4" style="margin-bottom: 0px; margin-top: 8px; padding: 0px;">
                        <label class="col-sm-2 control-label" style="padding-left: 0px;">Hasta</label>
                        <div class="input-group date col-sm-9">
                          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                          <input type="text" class="form-control pull-right validate[required]" id="hasta" name="hasta" value="<?php $fec =  str_replace('-', '/', $hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec; ?>">

                          <span class="input-group-btn">
                            <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                          </span>

                        </div>
                      </div> 
                    </div> 
                    <div class="col-md-4">  
                      <div class="col-md-6" style="margin-bottom: 0px; margin-top: 8px; padding-left: 0px;">
                        <h4 style="margin-bottom: 0px; margin-top: 0px;"><div id="monto"> Monto: <?php print number_format(@$totalg,2,",","."); ?></div></h4>
                      </div>  

                      <div class="col-md-6" style="padding: 0px;"> 
                        <a id="rpt_compra" class="btn bg-light-blue color-palette btn-grad btn-sm" href="#" data-original-title="" title=""><i class="fa fa-list-alt"></i> Reporte</a>
                        <a class="btn bg-orange color-palette btn-grad btn-sm add_compra" data-original-title="" title="Añadir Compra"><i class="fa fa-shopping-bag"></i> Añadir</a>             
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
                                <th>Proveedor</th>
                                <th>Factura</th>
                                <th>Monto</th>
                                <th>Categoría</th>
                                <th>Cancelación</th>
                                <th>Estado</th>
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

          <div class="tab-pane" id="tabproveedor">

            <div class="col-md-12" style="padding: 0px; margin: 0px;">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <div class="col-md-12">  

                   <div  class="col-md-8" style="padding: 0px;">
                    <!-- Empresa -->
                      <div  class="form-group col-md-4" style="margin-bottom: 0px; margin-top: 8px;padding: 0px;">
                        <div class="form-group col-md-4"> 
                          <label for="lb_res">Sucursal</label> 
                        </div>
                        <div class="form-group col-md-8"> 
                          <select id="cmb_sucursalpro" name="cmb_sucursal" class="form-control">
                          <?php 
                            if(@$sucursales != NULL){ 
                              if (count($sucursales) > 0) {
                                foreach ($sucursales as $obj): 
                          ?>
                                       <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                          <?php
                                endforeach;
                              }
                            }  
                          ?>
                          </select>                                  
                        </div>
                      </div>

                      <div class="form-group col-md-4" style="margin-bottom: 0px; margin-top: 8px;padding: 0px;">
                        <label class="col-sm-3 control-label text-left" style="padding-left: 0px;">Desde</label>
                        <div class="input-group date col-sm-7">
                          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                          <input type="text" class="form-control pull-right validate[required]" id="desdepro" name="desdepro" value="<?php $fec =  str_replace('-', '/', $desde); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec; ?>">
                        </div>
                      </div> 

                      <div class="form-group col-md-4" style="margin-bottom: 0px; margin-top: 8px; padding: 0px;">
                        <label class="col-sm-2 control-label" style="padding-left: 0px;">Hasta</label>
                        <div class="input-group date col-sm-9">
                          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                          <input type="text" class="form-control pull-right validate[required]" id="hastapro" name="hastapro" value="<?php $fec =  str_replace('-', '/', $hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print $fec; ?>">

                          <span class="input-group-btn">
                            <button class="btn btn-success btn-flat actualizapro" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                          </span>

                        </div>
                      </div> 

                    </div> 

                    <div class="pull-right" style="padding-top: 10px;">
                      <a class="btn btn-success btn-sm color-palette btn-grad" target="_blank" 
                        href="<?php print $base_url;?>compra/reportecompra_detallefacturaXLS" data-original-title="" title="Exportar Detalle de Facturas">
                        <i class="fa fa-file-excel-o fa-1x"></i> Facturas
                      </a>
                      <a class="btn btn-success btn-sm color-palette btn-grad" target="_blank" style="margin-left: 5px;"
                        href="<?php print $base_url;?>compra/reportecompra_resumenproductoXLS" data-original-title="" title="Exportar Resumen por Productos">
                        <i class="fa fa-file-excel-o fa-1x"></i> Productos
                      </a>
                      <a class="btn btn-success btn-sm color-palette btn-grad" target="_blank" style="margin-left: 5px;"
                        href="<?php print $base_url;?>compra/reportecompra_resumenproveedorXLS" data-original-title="" title="Exportar Resumen por Proveedores">
                        <i class="fa fa-file-excel-o fa-1x"></i> Proveedores
                      </a>
                    </div>

                    <div class="col-md-12" style="padding: 0px;">  
                     <!-- Proveedor -->
                      <div  class="form-group col-md-3" style="margin-left: 0px; margin-bottom: 0px; padding: 0px;">
                        <div class="form-group col-md-4"> 
                          <label for="lb_res">Proveedor</label> 
                        </div>
                        <div class="form-group col-md-8"> 
                          <select id="cmb_proveedor" name="cmb_proveedor" class="form-control">
                          <?php 
                            if(@$proveedores != NULL){ 
                              if (count($proveedores) > 0) {
                                foreach ($proveedores as $obj): 
                          ?>
                                       <option value="<?php  print $obj->id_proveedor; ?>" > <?php  print $obj->nom_proveedor; ?> </option>
                          <?php
                                endforeach;
                              }
                            }  
                          ?>
                          </select>                                  
                        </div>
                      </div>

                      <div class="form-group col-md-3" style="padding-left: 0px; margin-left: 0px;">
                        <label for="" class="col-sm-4 control-label" style="padding-right: 0px; padding-left: 5px;">Código</label>
                        <div  class="col-sm-8" style="padding-left: 0px;padding-right: 0px;">
                          <input type="hidden" id="txt_idproducto" name="txt_idproducto" value="" >    
                          <input type="text" class="form-control " name="txt_codigoproducto" id="txt_codigoproducto" placeholder="Código de Producto" value="" >
                        </div>
                      </div>

                      <div class="form-group col-md-4" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                        <label for="" class="col-sm-2 control-label text-right" style="padding-right: 0px; padding-left: 5px;">Producto</label>
                        <div  class="col-sm-10 autocomplete" style="padding-right: 0px;">
                          <input type="text" class="form-control" name="txt_nombreproducto" id="txt_nombreproducto" placeholder="Nombre del Producto" value="" data-source="<?php echo base_url('producto/valproductonombre?nombre=');?>">
                        </div>
                      </div>

                    </div>

                  </div>
                </div>

                <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs">
                   <li class="active"><a href="#tabfactura" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Detalle de Facturas</a></li>                            
                   <li ><a href="#tabresproducto" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Resumen por Producto</a></li>                                     
                   <li ><a href="#tabresproveedor" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Resumen por Proveedor</a></li>                                     
                  </ul>

                  <div class="tab-content">

                    <div class="tab-pane active" id="tabfactura">

                      <div class="box-body">
                        <div class="row">
                          <div class="col-xs-12">
                            <div class="box">

                              <div class="box-body table-responsive">
                                <table id="dataTableCompraProv_Detalle" class="table table-bordered table-hover table-responsive">
                                  <thead>
                                    <tr >
                                      <th>Fecha</th>
                                      <th>Factura</th>
                                      <th>Cancelación</th>
                                      <th>Producto</th>
                                      <th>Cantidad</th>
                                      <th>U.M.</th>
                                      <th>Precio</th>
                                      <th>Subtotal</th>
                                      <th>Descuento</th>
                                      <th>Monto IVA</th>
                                      <th>Total</th>
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

                    <div class="tab-pane" id="tabresproducto">

                      <div class="box-body">
                        <div class="row">
                          <div class="col-xs-12">
                            <div class="box">

                              <div class="box-body table-responsive">
                                <table id="dataTableCompraProv_Producto" class="table table-bordered table-hover table-responsive">
                                  <thead>
                                    <tr >
                                      <th>Producto</th>
                                      <th>Cantidad</th>
                                      <th>U.M.</th>
                                      <th>Precio</th>
                                      <th>Subtotal</th>
                                      <th>Descuento</th>
                                      <th>Monto IVA</th>
                                      <th>Total</th>
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

                    <div class="tab-pane" id="tabresproveedor">

                      <div class="box-body">
                        <div class="row">
                          <div class="col-xs-12">
                            <div class="box">

                              <div class="box-body table-responsive">
                                <table id="dataTableCompraProv_Proveedor" class="table table-bordered table-hover table-responsive">
                                  <thead>
                                    <tr >
                                      <th class="text-center col-md-1">Proveedor</th>
                                      <th class="text-center col-md-1">#Facturas</th>
                                      <th class="text-center col-md-1">Cant.Prod.</th>
                                      <th class="text-center col-md-1">U.M.</th>
                                      <th class="text-center col-md-1">Precio</th>
                                      <th class="text-center col-md-1">Subtotal</th>
                                      <th class="text-center col-md-1">Descuento</th>
                                      <th class="text-center col-md-1">Monto IVA</th>
                                      <th class="text-center col-md-1">Total</th>
                                      <th class="text-center col-md-1">Teléfono</th>
                                      <th class="text-center col-md-1">Correo</th>
                                      <th class="text-center col-md-1">Dirección</th>
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

              </div>
            </div>      
          </div>

        </div>    
      </div>

    </div>
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

