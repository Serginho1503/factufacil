<?php
/* ------------------------------------------------
  ARCHIVO: guiaremision.php
  DESCRIPCION: Contiene la vista principal del módulo de Nota de credito.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Guía de Remisión'</script>";
date_default_timezone_set("America/Guayaquil");

  $parametro = &get_instance();
  $parametro->load->model("Parametros_model");

  $habserie = $parametro->Parametros_model->sel_numeroserie();
  $habilitaserie = $habserie->valor;


?>
<style type="text/css">
  .form-control{
    font-size: 12px;
    height: 28px;
  }

  .table > tbody > tr > td{
    padding-bottom: 0px;
    padding-top: 1px;
  }

  .form-group {
      margin-bottom: 5px;
  }

  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 

    .tdvalor{
      width: 80px;
    }

</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

    $("#frm_guia").validationEngine();

    $("#txt_nrodocmod").mask("999-999-999999999");  
    $("#txt_codestabdestino").mask("999");  

    /* FECHA */
    $('#fecha').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
    $("#fecha").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });

    $('#fecha_inicio').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
    $("#fecha_inicio").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });

    $('#fecha_fin').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
    $("#fecha_fin").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });

    /* FECHA Limite Credito*/
    $('#fechadocmod').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
    $("#fechadocmod").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd/mm/yy', 
      firstDay: 1
    });

     $('#dataTableProd').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "listadoProductoGuia",
        'columns': [
            {"data": "ver"},        
            {"data": "codigo"},
            {"data": "descripcion"},
            {"data": "cantidad"} 
        ]

      });

    $(document).on('change','#cmb_punto', function(){
      var punto = $("#cmb_punto option:selected").val();
      var tmppunto = $("#tmp_puntoemision").val();
      if (punto == tmppunto){
        $('#txt_nroguia').val($("#tmp_nroguia").val());  
      }
      else{
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "guiaremision/sel_nroguia_ptoemi",
            data: { punto: punto },
            success: function(json) {
              if (json != null){
                $('#txt_nroguia').val(json.nroguia);  
              }
            }
        });
      }  
    //  location.reload();
    });

    /* Buscar Documento Modificado */
    $(document).on('click', '.busca_factura', function(){
      var cliente = $('#cmb_cliente').val();
      if ((cliente == '') || (cliente == 0)){
        alert("Seleccione el cliente.");  
      } else {
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST",
             data: {cliente: cliente},
          },
          href: "<?php echo base_url('guiaremision/busca_factura');?>" 
        });
      }
    });

    /* Actualiza Documento Modificado */
    $(document).on('click', '.add_docmodificado', function(){
      var id = $(this).attr('id');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('guiaremision/upd_docmodificado');?>",
        data: { id: id },
        success: function(json) {
          if (!$.isEmptyObject(json)){
            $.fancybox.close();
            $('#dataTableProd').DataTable().ajax.reload();
          }
        }
      });
    });

    /* Buscar Documento Modificado */
    $(document).on('click', '.busca_pro', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST",
          },
          href: "<?php echo base_url('guiaremision/busca_producto');?>" 
        });
    });

    /* Actualiza Documento Modificado */
    $(document).on('click', '.add_producto', function(){
      var id = $(this).attr('id');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('guiaremision/ins_producto');?>",
        data: { id: id },
        success: function(json) {
            $.fancybox.close();
            $('#dataTableProd').DataTable().ajax.reload();
        }
      });
    });


/* === GUARDAR EL PRECIO EN LA TABLA TEMPORAL Y REALIZAR CALCULO === */
    $(document).on('change','.upd_prodcant', function(){
      /* Inicializacion de las variables */
      id = $(this).attr("id");
      cantidad = $('.upd_prodcant[id='+id+']').val();
      if (cantidad == '') { cantidad = 0; }

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('guiaremision/upd_guiadetalle');?>",
        data: { id: id, cantidad: cantidad },
        success: function(json) {
        }
      });
    });



    /* ELIMINAR UN PRODUCTOS */
    $(document).on('click', '.del_producto', function(){  
      id = $(this).attr("id");
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "guiaremision/del_detalle",
        data: { id: id },
        success: function(json) {
          $('#dataTableProd').DataTable().ajax.reload();
        }
      });
    }); 


  function redireccion(contr, meth) {
      location.replace(base_url + contr + (meth ? "/" + meth : ""));
  }

/*------------------------------------*/

  


 



}); 


</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <form id="frm_guia" name="frm_guia" method="post" role="form" class="form" enctype="multipart/form-data" action="<?php echo base_url('guiaremision/guardar_guia');?>">
    <input type="hidden" id="idguia" name="idguia" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->idguia; }?>"> 

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-truck"></i> Guía de Remisión  
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>guiaremision">Guías de Remisión</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DEL PROVEEDOR -->        
      <div class="col-md-12">
        <div class="box box-danger">
          <div style="padding-top: 5px; padding-bottom: 0px;" class="box-header with-border">
                <!-- NRO DE FACTURA -->
                <div style="padding-top: 0px; padding-bottom: 0px; padding-right: 0px;" >

                  <!-- Punto Emision  -->
                  <div style="padding-top: 0px; padding-bottom: 0px; padding-right: 0px;"  class="form-group col-md-3">
                    <div style="padding-left: 0px; padding-right: 0px;" class="col-md-5">
                        <label for="lb_res">Punto Emisión</label>
                    </div>  
                    <div style="padding-left: 0px; padding-right: 0px;" class="col-md-7">
                      <select id="cmb_punto" name="cmb_punto" class="form-control validate[required]" title="Punto de Emision">
                      <?php 
                        if(@$tmpcomp->id_puntoemision != NULL){ ?>
                        <?php } else { ?>
                        <option  value="" selected="TRUE">Seleccione...</option>
                        <?php } 
                          if (count($puntoemision) > 0) {
                            foreach ($puntoemision as $obj):
                                if(@$tmpcomp->id_puntoemision != NULL){
                                    if($obj->id_puntoemision == $tmpcomp->id_puntoemision){ ?>
                                         <option value="<?php  print $obj->id_puntoemision; ?>" selected="TRUE"> <?php  print $obj->cod_punto.' '.$obj->nom_sucursal; ?> </option>
                                        <?php
                                    }else{ ?>
                                        <option value="<?php  print $obj->id_puntoemision; ?>" > <?php  print $obj->cod_punto.' '.$obj->nom_sucursal; ?> </option>
                                        <?php
                                    }
                                }else{ ?>
                                    <option value="<?php  print $obj->id_puntoemision; ?>" > <?php  print $obj->cod_punto.' '.$obj->nom_sucursal; ?> </option>
                                    <?php
                                    }   ?>
                                <?php
                            endforeach;
                          }
                        ?>
                      </select>                                  
                    </div>    
                  </div>


                  <div class="col-md-2" style="padding-left: 0px; padding-right: 0px;">
                    <div class="col-md-3">
                        <label>#Guía</label>
                    </div>  
                    <input type="hidden" id="tmp_puntoemision" name="tmp_puntoemision" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->id_puntoemision; }?>">
                    <input type="hidden" id="tmp_nroguia" name="tmp_nroguia" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->secuencial; }?>">
                    <div class="col-md-9">
                        <input type="text" class="form-control validate[required] " id="txt_nroguia" name="txt_nroguia" value="<?php if(@$tmpcomp->id_puntoemision != NULL){ print @$tmpcomp->secuencial; }?>" readonly>
                    </div>  
                  </div>                    

                    <!-- FECHA DE FACTURA -->
                  <div class="form-group col-md-3">
                    <div style="padding-left: 0px; padding-right: 0px;" class="col-md-4">
                        <label for="">Fecha</label>
                    </div>  
                    <div style="margin-bottom: 0px; padding-left: 0px; padding-right: 0px;" class="form-group col-md-8" >
                        <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right validate[required] datosnota" id="fecha" name="fecha" value="<?php if(@$tmpcomp->fecha != NULL){ @$fec = str_replace('-', '/', @$tmpcomp->fechaemision); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print date("d/m/Y"); } ?>">
                        </div>                             
                    </div>
                  </div>  

                  <div style="" class="form-group col-md-4">
                    <div style="padding-left: 0px; padding-right: 0px;" class="col-md-3">
                        <label for="lb_res">Transportista</label>
                    </div>  
                    <div class="col-md-9">                   
                        <select id="cmb_transportista" name="cmb_transportista" class="form-control validate[required]">
                        <?php 
                        if(@$transportista != NULL){ ?>
                        <option  value="" selected="TRUE">Seleccione ...</option>
                        <?php } 
                            if (count($transportista) > 0) {
                            foreach ($transportista as $obj):
                                if(@$tmpcomp != NULL){
                                    if($obj->idtransportista == $tmpcomp->idtransportista){ ?>
                                        <option value="<?php  print $obj->idtransportista; ?>" selected="TRUE"> <?php  print $obj->razonsocial; ?> </option>
                                        <?php
                                    }else{ ?>
                                        <option value="<?php  print $obj->idtransportista; ?>" > <?php  print $obj->razonsocial; ?> </option>
                                        <?php
                                    }
                                }else{ ?>
                                    <option value="<?php  print $obj->idtransportista; ?>" > <?php  print $obj->razonsocial; ?> </option>
                                    <?php
                                    }   ?>
                                <?php
                            endforeach;
                            }
                        ?>
                        </select>                                  
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="col-md-4">
                        <label>Placa</label>
                    </div>  
                    <div class="col-md-8">
                      <input type="text" class="form-control validate[required] " id="txt_placa" name="txt_placa" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->placa; }?>" >
                    </div>  
                  </div>                    

                  <div class="col-md-4">
                    <div class="col-md-5" style="padding:0px;">
                        <label>Punto de Partida</label>
                    </div>  
                    <div class="col-md-7">
                      <input type="text" class="form-control validate[required] " id="txt_puntopartida" name="txt_puntopartida" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->dirpartida; }?>" >
                    </div>  
                  </div>                    

                  <div class="form-group col-md-3">
                    <div style="padding-left: 0px; padding-right: 0px;" class="col-md-4">
                        <label for="">Inicio</label>
                    </div>  
                    <div style="margin-bottom: 0px; padding-left: 0px; padding-right: 0px;" class="form-group col-md-8" >
                        <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right validate[required] datosnota" id="fecha_inicio" name="fecha_inicio" value="<?php if(@$tmpcomp->fecha != NULL){ @$fec = str_replace('-', '/', @$tmpcomp->fechaini); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print date("d/m/Y"); } ?>">
                        </div>                             
                    </div>
                  </div>  

                  <div class="form-group col-md-3">
                    <div style="padding-left: 0px; padding-right: 0px;" class="col-md-4">
                        <label for="">Fin</label>
                    </div>  
                    <div style="margin-bottom: 0px; padding-left: 0px; padding-right: 0px;" class="form-group col-md-8" >
                        <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right validate[required] datosnota" id="fecha_fin" name="fecha_fin" value="<?php if(@$tmpcomp->fecha != NULL){ @$fec = str_replace('-', '/', @$tmpcomp->fechafin); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print date("d/m/Y"); } ?>">
                        </div>                             
                    </div>
                  </div>  

                </div>



<!--             <h3 class="box-title"><i class="fa fa-user"></i> Datos del Proveedor </h3> 
 -->      </div>

        </div>
      </div>

    <!-- DATOS DEL Documento Modificado -->        
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
             <h3 class="box-title"><i class="fa fa-user"></i> Destino / Comprobante Venta </h3> 
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

                <!-- NOMBRE DEL cliente -->
                <div style="" class="form-group col-md-4">
                  <div style="padding-left: 0px; padding-right: 0px;" class="col-md-3">
                    <label for="lb_res">Destinatario</label>
                  </div>  
                  <div class="col-md-9">                   
                    <select id="cmb_cliente" name="cmb_cliente" class="form-control validate[required] datosnota">
                    <?php 
                      if(@$cliente != NULL){ ?>
                      <option  value="" selected="TRUE">Seleccione...</option>
                      <?php } 
                        if (count($cliente) > 0) {
                          foreach ($cliente as $obj):
                              if(@$tmpcomp->iddestinatario != NULL){
                                  if($obj->id_cliente == $tmpcomp->iddestinatario){ ?>
                                       <option value="<?php  print $obj->id_cliente; ?>" selected="TRUE"> <?php  print $obj->nom_cliente; ?> </option>
                                      <?php
                                  }else{ ?>
                                      <option value="<?php  print $obj->id_cliente; ?>" > <?php  print $obj->nom_cliente; ?> </option>
                                      <?php
                                  }
                              }else{ ?>
                                  <option value="<?php  print $obj->id_cliente; ?>" > <?php  print $obj->nom_cliente; ?> </option>
                                  <?php
                                  }   ?>
                              <?php
                          endforeach;
                        }
                      ?>
                    </select>                                  
                  </div>
                </div>

                <div style="" class="form-group col-md-4">
                  <div style="padding-left: 0px; padding-right: 0px;" class="col-md-6">
                      <label for="lb_res">Comprobante Venta</label>
                  </div>  
                  <div class="col-md-6">                   
                      <select id="cmb_comprobventa" name="cmb_comprobventa" class="form-control datosnota">
                      <?php 
                      if(@$comprobventa != NULL){ ?>
                      <option  value="" selected="TRUE">Seleccione ...</option>
                      <?php } 
                          if (count($comprobventa) > 0) {
                          foreach ($comprobventa as $obj):
                              if(@$tmpcomp != NULL){
                                  if(($obj->cod_sri_tipo_doc == $tmpcomp->coddocsustento) || (count($comprobventa) == 1)){ ?>
                                      <option value="<?php  print $obj->cod_sri_tipo_doc; ?>" selected="TRUE"> <?php  print $obj->desc_sri_tipo_doc; ?> </option>
                                      <?php
                                  }else{ ?>
                                      <option value="<?php  print $obj->cod_sri_tipo_doc; ?>" > <?php  print $obj->desc_sri_tipo_doc; ?> </option>
                                      <?php
                                  }
                              }else{ ?>
                                  <option value="<?php  print $obj->cod_sri_tipo_doc; ?>" <?php if(count($comprobventa) == 1) { print 'selected';}  ?>  > <?php  print $obj->desc_sri_tipo_doc; ?> </option>
                                  <?php
                                  }   ?>
                              <?php
                          endforeach;
                          }
                      ?>
                      </select>                                  
                  </div>
                </div>


                <!-- NRO DE FACTURA -->
                <!-- <div style="padding-left: 0px; padding-right: 0px;" class="col-md-1">
                  <a class="btn btn-success btn-sm busca_factura" href="#" data-original-title="" title=""><i class="fa fa-binoculars"></i> Buscar.. </a> 
                </div> -->
                <div style="padding-left: 0px; padding-right: 0px;" class="form-group col-md-4">
                  <div class="col-md-5">
                    <label>Nro.Documento</label>
                  </div>  
                  <div class="col-md-7">
                    <!-- <input type="hidden" id="txt_iddocmod" name="txt_iddocmod" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->id_docmodificado; }?>"> -->
                    <input type="hidden" id="txt_nrodocmod2" name="txt_nrodocmod2" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->numdocsustento; }?>">
                    <input style="padding-left: 5px; padding-right: 5px;" type="text" class="form-control validate[required] datosnota" id="txt_nrodocmod" name="txt_nrodocmod" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->numdocsustento; }?>">
                  </div>                    
                </div>
                <!-- FECHA DE FACTURA -->
                <div class="form-group col-md-4">
                  <div style="padding-left: 0px; padding-right: 0px;" class="col-md-4">
                    <label for="">Fecha Emisión</label>
                  </div>  
                  <div style="padding-left: 0px; margin-bottom: 0px;" class="form-group col-md-8" >
                    <div class="input-group date">
                      <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control validate[required] datosnota" id="fechadocmod" name="fechadocmod" value="<?php if(@$tmpcomp->fechaemidocsustento != NULL){ @$fec = str_replace('-', '/', @$tmpcomp->fechaemidocsustento); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} ?>">
                    </div>                             
                  </div>
                </div>  
                <div style="padding-left: 0px; padding-right: 0px;" class="form-group col-md-4">
                  <div style="padding-left: 0px; padding-right: 0px;" class="col-md-3">
                    <label>Autorización</label>
                  </div>  
                  <div class="col-md-9">
                    <input type="text" class="form-control validate[required] datosnota" id="txt_numautdocsustento" name="txt_numautdocsustento" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->numautdocsustento; }?>">
                  </div>                    
                </div>
                <!-- Motivo -->
                <div style="padding-left: 0px; padding-right: 0px;" class="form-group col-md-4">
                  <div style="padding-left: 0px; padding-right: 0px;" class="col-md-2">
                    <label>Motivo</label>
                  </div>  
                  <div class="col-md-10">
                    <input type="text" class="form-control validate[required] datosnota" id="txt_motivo" name="txt_motivo" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->motivo; }?>">
                  </div>                    
                </div>
                <div style="padding-left: 0px; padding-right: 0px;" class="form-group col-md-3">
                  <div style="padding-right: 0px;" class="col-md-5">
                    <label>Pto Llegada</label>
                  </div>  
                  <div class="col-md-7" style="padding-left: 0px; padding-right: 0px;">
                    <input type="text" class="form-control validate[required] datosnota" id="txt_dirllegada" name="txt_dirllegada" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->dirllegada; }?>">
                  </div>                    
                </div>
                <div style="padding-left: 0px; padding-right: 0px;" class="form-group col-md-3">
                  <div style="padding-right: 0px;" class="col-md-6">
                    <label>Doc.Aduanero</label>
                  </div>  
                  <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                    <input type="text" class="form-control datosnota" id="txt_docaduanero" name="txt_docaduanero" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->docaduanero; }?>">
                  </div>                    
                </div>
                <div style="padding-left: 0px; padding-right: 0px;" class="form-group col-md-2">
                  <div style="padding-right: 0px;" class="col-md-8">
                    <label>Cod.Establec.</label>
                  </div>  
                  <div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">
                    <input type="text" class="form-control datosnota" id="txt_codestabdestino" name="txt_codestabdestino" title="Código Establecimiento Destino" value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->codestabdestino; }?>">
                  </div>                    
                </div>
                <div style="padding-left: 0px; padding-right: 0px;" class="form-group col-md-4">
                  <div style="padding-right: 0px;" class="col-md-2">
                    <label>Ruta</label>
                  </div>  
                  <div class="col-md-10" style="padding-left: 0px; ">
                    <input type="text" class="form-control datosnota" id="txt_ruta" name="txt_ruta"  value="<?php if(@$tmpcomp != NULL){ print @$tmpcomp->ruta; }?>">
                  </div>                    
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

    <!-- DATOS DE LOS PRODUCTOS A COMPRAR -->
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-shopping-bag"></i> Lista de Productos </h3>
            
            <div class="pull-right"> 
              <a class="btn bg-orange-active color-palette btn-grad btn-sm busca_pro" href="#" data-original-title="" title=""><i class="fa fa-shopping-bag"></i> Añadir</a>
            </div>

          </div>
          <div class="box-body">
            <div class="row">
              <div id="detnota" class="col-md-12 table-responsive" > 
                <table id="dataTableProd" class="table table-bordered table-hover table-responsive">
                    <thead>
                      <tr>
                        <th class="text-center " style="width: 10px;">Acción</th>
                        <th class="text-center col-md-1">Código</th>
                        <th>Producto</th>
                        <th class="text-center col-md-1">Cantidad</th>
                      </tr>
                    </thead> 
                    <tbody>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
          <div   align="center" class="box-footer">
            <hr class="linea"> 
              <div class="row" style="margin-top:0px; margin-bottom: 0px;">

                <div class="col-md-12">
                  <div class="pull-right"> 

                    <div class="form-actions ">
                        <button type="submit" class="btn bg-green-active btn-grad no-margin-bottom">
                            <i class="fa fa-save "></i> Guardar
                        </button>
                    </div>

                    <!-- <a id="guardar_nota" class="btn bg-green-active color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-save"></i> Guardar </a> -->
                  </div>                  
                </div>

              </div><!--/row-->



          </div>
        </div>

      </div>           
    </div>
  </section>
    <!-- /.content -->
  </form>
</div>
  <!-- /.content-wrapper -->

