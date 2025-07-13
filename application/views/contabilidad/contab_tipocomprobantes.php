<?php
/*
  FUNCION QUE PERMITE CONECTAR EL DATATABLE CON LA BASE DE DATOS
*/
?>
  <script type='text/javascript' language='javascript'>
    $(document).ready(function () {
       $('#dataTableObj').dataTable({
          'language': {
                'url': base_url + 'public/json/language.spanish.json'
          },
          'ajax': "contabilidad/contab_comprobante/listadoTipocomprobantes",
          'columns': [
              {"data": "nombre"},
              {"data": "abreviatura"},
              {"data": "prefijo"}
          ]
      });

      $(document).on('change', '.upd_prefijo', function(){
        id = $(this).attr('id');
        prefijo = $(this).val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('contabilidad/contab_comprobante/upd_tipoasiento_prefijo');?>",
            data: {id: id, prefijo: prefijo},
            success: function(json) {
            }
        });
      })



 });

</script>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-calendar"></i>
        Tipos de Asientos      
      </h1>
      <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
<!--            <div class="box-header">
              <h3 class="box-title">Listado </h3>
            </div>
             /.box-header -->
            <div class="box-body table-responsive">
              <table id="dataTableObj" class="table table-bordered table-striped table-responsive">
                <thead>
                  <tr >
                      <th>Nombre</th>
                      <th>Abreviatura</th>
                      <th>Prefijo</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
