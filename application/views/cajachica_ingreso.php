<?php
/* ------------------------------------------------
  ARCHIVO: Compra.php
  DESCRIPCION: Contiene los ingresos de caja chica.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Ingresos de Caja Chica'</script>";
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
    $(document).on('click', '#rpt_ingreso', function(){    
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Compra/tmp_cajaingreso');?>",
        data: { hasta: hasta, desde: desde },
        success: function(json) {
          window.open('<?php print $base_url;?>Cajachica/reporteingreso');
        }
      });    
    });

      /* CARGA DE DATOS EN EL DATATABLE */

     $('#dataTableCajaIngreso').dataTable({
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
        'ajax': "listadoDataCajaIngreso",
        'columns': [
            {"data": "id"},
            {"data": "fechaingreso"},
            {"data": "numeroingreso"},  
            {"data": "monto"},
            {"data": "descripcion"},  
            {"data": "ver"}
        ]
      });

/*
            {"data": "fechaingreso"},
            {"data": "numeroingreso"},  
            {"data": "monto"},
            {"data": "descripcion"},  
*/

    /* Boton del listado para imprimir compra */
    $(document).on('click', '.ingreso_print', function(){
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
                href: "<?php echo base_url('Cajachica/imprimiringreso');?>" 
              });
    });


      $(document).on('click', '.add_caja', function(){
        var hasta = $("#hasta").val();
        var desde = $("#desde").val();
        var caja = $("#cmb_caja").val();

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Cajachica/tmp_cajaingreso');?>",
          data: { hasta: hasta, desde: desde, caja: caja }
        }).done(function (result) {
              $('#dataTableCajaIngreso').DataTable().ajax.reload();

              $.ajax({
              type: "POST",
              dataType: "json",
              url: base_url + "Cajachica/actualiza_caja_cierre",
              data: {caja: caja},
              success: function(json) {
                if(json.cierre.length != 0){
                  $.fancybox.open({
                    type: "ajax",
                    width: 550,
                    height: 550,
                    ajax: {
                      dataType: "html",
                      type: "POST"
                    },
                    href: "<?php echo base_url('Cajachica/agregar');?>" ,
                    success: function(json) { $.fancybox.close(); }            
                  });                             
                }else{
                  alert("No se ha realizado la apertura de caja.");
                }
              }
            });

        }); 


      });


      $(document).on('click', '.caja_del', function(){
          id = $(this).attr('id');
          if (conf_eli()) {          
            $.ajax({
             type: "POST",
             dataType: "json",
             url: base_url + "Cajachica/eliminar",
             data: {id: id},
             success: function(json) {
              location.replace(base_url + 'Cajachica/cargaringresocaja');
             }
          });
        }          
      });





  

      /* ACTUALIZAR LISTADO DE GASTOS POR RAGO DE FECHA *//**/
      $('.actualiza').click(function(){
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();
      var caja = $("#cmb_caja").val();

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Cajachica/tmp_cajaingreso');?>",
          data: { hasta: hasta, desde: desde, caja: caja }
        }).done(function (result) {
              $('#dataTableCajaIngreso').DataTable().ajax.reload();
              
        }); 


      
      });

    function conf_eli() {
        return  confirm("¿Confirma que desea Eliminar este registro?");
    }

}); 



</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-truck"></i> Ingresos de Caja Chica
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>Cajachica/cargaringresocaja">Ingresos de Caja Chica</a></li>
      
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

                <!-- Caja -->
                <div style="" class="form-group col-md-3">
                  <label for="lb_res">Caja</label>
                  <select id="cmb_caja" name="cmb_caja" class="form-control">
                  <?php 
                    if(@$cajas != NULL){ ?>
                    <?php } else { ?>
                    <option  value="" selected="TRUE">Seleccione Caja...</option>
                    <?php } 
                      if (count($cajas) > 0) {
                        foreach ($cajas as $obj): ?>
                            <option value="<?php  print $obj->id_caja; ?>" > <?php  print $obj->nom_caja; ?> </option>
                    <?php
                        endforeach;
                      }
                    ?>
                  </select>                                  
                </div>

                <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
                  <label for="">Desde</label>
                  <div class="input-group date col-sm-7">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" class="form-control pull-right validate[required]" id="desde" name="desde" value="<?php print  date("d/m/Y"); ?>">
                  </div>
                </div> 

                <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
                  <label for="">Hasta</label>
                  <div class="input-group date col-sm-10">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" style="height: 34px;" class="form-control pull-right validate[required]" id="hasta" name="hasta" value="<?php print  date("d/m/Y"); ?>" >
                    <span class="input-group-btn">
                    <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                    </span>
                  </div>
                </div> 

                 <!-- <div class="col-md-3" style="margin-bottom: 0px; margin-top: 32px;">
                  <h4 style="margin-bottom: 0px; margin-top: 0px;"><div id="monto"> Monto: <?php print number_format(@$montocaja,2,",","."); ?></div></h4>
                </div>   -->

            </div>
            <div class="pull-right"> 
              <a id="rpt_ingreso" class="btn bg-light-blue color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-list-alt"></i> Reporte </a>
              <a class="btn bg-orange color-palette btn-grad add_caja" data-original-title="" title="Añadir Ingreso"><i class="fa fa-plus-square"></i> Añadir </a>
              
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div class="box-body table-responsive">
                    <table id="dataTableCajaIngreso" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr >
                          <th>Id</th>  
                          <th>Fecha</th>
                          <th>Numero</th>
                          <th>Monto</th>
                          <th>Descripcion</th>
                          <th>Acción</th>
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

