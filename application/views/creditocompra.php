<?php
/* ------------------------------------------------
  ARCHIVO: Compra.php
  DESCRIPCION: Contiene la vista principal del módulo de Compra.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Cuentas por Pagar'</script>";
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

    /* Reporte de Venta */
    $(document).on('click', '#rpt_credito', function(){    
        window.open('<?php print $base_url;?>Creditocompra/reporte_credito');
    });


      /* CARGA DE DATOS EN EL DATATABLE */
     tablecomp=$('#dataTableComp').dataTable({
      rowCallback:function(row,data) {
        if((data["vencido"] == '1') && ((data["estatus"] == '2')))
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
        'ajax': "Creditocompra/listadoCreditos",
        'columns': [
            {"data": "ver"},
            {"data": "fecha"},           
            {"data": "proveedor"},
            {"data": "factura"},
            {"data": "fechalimite"},  
            {"data": "dias"},  
            {"data": "estado"},  
            {"data": "montofactura"},  
            {"data": "montopendiente"}
        ]
      });


    /* Boton del listado para imprimir compra */
    $(document).on('click', '.cred_print', function(){
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


      /* ACTUALIZAR LISTADO DE creditos */
      $(document).on('change', '#cmb_proveedor,#cmb_estado,#cmb_empresa', function(){
        var proveedor = $("#cmb_proveedor").val();
        var estado = $("#cmb_estado").val();
        var empresa = $("#cmb_empresa").val();

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Creditocompra/upd_filtrocredito');?>",
          data: { proveedor: proveedor, estado: estado, empresa: empresa }
        }).done(function (result) {
              $('#dataTableComp').DataTable().ajax.reload();
              actualiza_monto();
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

    function actualiza_monto(){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Creditocompra/get_total_credito",
            success: function(json) {
              var total = 0;
              if(json.pendiente != null){
                total = json.pendiente;
              }
              $('#monto').html(' $ ' + total);
            }
        });
    }

    actualiza_monto();

}); 



</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-truck"></i> Listado de Cuentas por Pagar
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>creditocompra">Cuentas por Pagar</a></li>
      
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

              <!-- Empresa -->
              <div  class="form-group col-md-2" style="margin-bottom: 0px; padding: 0px;">
                <label for="lb_res">Empresa</label> 
                <select id="cmb_empresa" name="cmb_empresa" class="form-control">
                  <?php 
                    if(@$empresas != NULL){ 
                      if (count($empresas) > 0) {
                        foreach ($empresas as $obj): 
                  ?>
                               <option value="<?php  print $obj->id_emp; ?>" > <?php  print $obj->nom_emp; ?> </option>
                  <?php
                        endforeach;
                      }
                    }  
                  ?>
                </select>                                  
              </div>

              <div class="form-group col-md-3">
                  <label>Proveedores</label>
                  <select id="cmb_proveedor" name="cmb_proveedor" class="form-control">
                      <?php 
                        if(@$proveedores != NULL){ ?>
                        <option  value="0" selected="TRUE">Todos los Proveedores</option>
                      <?php } 
                          if (count($proveedores) > 0) {
                            foreach ($proveedores as $row):
                                if(@$proveedorseleccionado != NULL){
                                    if($row->id_proveedor == $proveedorseleccionado){ ?>
                                         <option value="<?php  print $row->id_proveedor; ?>" selected="TRUE"> <?php  print $row->nom_proveedor ?> </option>
                                        <?php
                                    }else{ ?>
                                        <option value="<?php  print $row->id_proveedor; ?>" > <?php  print $row->nom_proveedor ?> </option>
                                        <?php
                                    }
                                }else{ ?>
                                    <option value="<?php  print $row->id_proveedor; ?>"> <?php  print $row->nom_proveedor ?> </option>
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
                  <label>Estado de Credito</label>
                  <select id="cmb_estado" name="cmb_estado" class="form-control">
                      <?php 
                        if(@$estados != NULL){ ?>
                        <option  value="0" selected="TRUE">Todos los creditos</option>
                      <?php } 
                          if (count($estados) > 0) {
                            foreach ($estados as $row):
                                if(@$estadoseleccionado != NULL){
                                    if($row->id_estatus == $estadoseleccionado){ ?>
                                         <option value="<?php  print $row->id_estatus; ?>" selected="TRUE"> <?php  print $row->desc_estatus ?> </option>
                                        <?php
                                    }else{ ?>
                                        <option value="<?php  print $row->id_estatus; ?>" > <?php  print $row->desc_estatus ?> </option>
                                        <?php
                                    }
                                }else{ ?>
                                    <option value="<?php  print $row->id_estatus; ?>"> <?php  print $row->desc_estatus ?> </option>
                                    <?php
                                    }   ?>
                                <?php

                            endforeach;
                      }
                      ?>
                  </select>                                    
              </div>

                <div class="form-group col-md-3">
                  <label>Monto Pendiente:</label>

                   <div id="monto"> <?php print number_format(@$totalg,2,",","."); ?></div>
                </div>  

            </div>
            <div class="pull-right"> 
              <a id="rpt_credito" class="btn bg-light-blue color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-list-alt"></i> Reporte </a>
             
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
                          <th>Fecha Plazo</th>
                          <th>Dias Plazo</th>
                          <th>Estado</th>
                          <th>Monto Factura</th>
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

