<?php
/* ------------------------------------------------
  ARCHIVO: Gastos.php
  DESCRIPCION: Contiene la vista principal del módulo de Gastos.
  FECHA DE CREACIÓN: 30/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Gastos'</script>";
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
        'ajax': "Gastoabono/listadoDataGas",
        'columns': [
            {"data": "id"},
            {"data": "fecha"},
            {"data": "formapago"},
            {"data": "monto"},    
            {"data": "ver"}
        ]
      });


      /* AGREGAR GASTOS */
      $(document).on('click', '.gas_add', function(){
        id = $(this).attr('id');
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('gastoabono/tmp_gastos');?>",
           data: {id: id},
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
                        href: "<?php echo base_url('gastoabono/add_abono');?>", 
                        success: function(json) {
                            if (parseInt(json.resu) > 0) {
                               location.replace("<?php print $base_url;?>gastoabono");
                            } else {
                               alert("Error de conexión");
                            }
                        }              
                      });
           }
        }); 
      })


      /* MODIFICAR GASTOS */
      $(document).on('click', '.edi_gas', function(){
        id = $(this).attr('id');
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('gastos/tmp_gastos');?>",
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
      $(document).on('click', '.del_gas', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('gastoabono/tmp_abono');?>",
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
                href: "<?php echo base_url('gastoabono/del_abono');?>", 
                success: function(json) {
                    if (parseInt(json.resu) > 0) {
                       location.replace("<?php print $base_url;?>gastoabono");
                    } else {
                       alert("Error de conexión");
                    }
                }              
              });
           }
        });
      })

    /* Boton del listado para imprimir GASTO */
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
                href: "<?php echo base_url('Gastos/imprimirgasto');?>" 
              });
    });      

    }); 

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-money"></i> Abonos de Gastos
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>gastos">Gastos</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
<!--                       <h3 class="box-title"> Listado de Abonos de Factura <?php print $objgasto->nro_factura.' ('.$objgasto->fecha.')' ?></h3>
 -->                      <div class="pull-right"> 
<!--                           <a class="btn bg-light-blue color-palette btn-grad " target="_blank" href="<?php print $base_url;?>gastos/reporte" data-original-title="" title=""><i class="fa fa-list-alt"></i> Abonos de Gastos </a>                      
 -->                          <button type="button" class="btn btn-danger btn-grad gas_add" id="<?php print $objgasto->id_gastos?>">
                            <i class="fa fa-plus-square"></i> Añadir
                          </button>                     
                      </div>

                <div class="form-group col-md-8">
                    <label for="lb_cant">Factura: <?php print $objgasto->nro_factura .' ('.$objgasto->fecha.')' ?></label>
                </div>

                <div class="form-group col-md-8">
                    <label for="lb_cant">Valor Total: <?php print $objgasto->total ?></label>
                </div>

                <div class="form-group col-md-8">
                    <label for="lb_cant">Valor Pendiente: <?php print number_format($objgasto->total - $objgasto->abonos,2) ?></label>
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

