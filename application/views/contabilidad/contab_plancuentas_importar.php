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
          'ajax': "contabilidad/contab_plancuentas/listadocuentasimportar",
          'columns': [
              {"data": "cuenta"},
              {"data": "descripcion"}
          ]
      });

    $('.load_ctas').on('click', function() {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('contabilidad/contab_plancuentas/guardar_cuentas_importar');?>",
            success: function(json) {
                if (json){
                    if (json > 0){
                        if (json == 1) {
                            tmpstr = " 1 nuevo registro";
                        }
                        else {
                            tmpstr = json + " nuevos registros";
                        }
                        alert("Se ha importado " + tmpstr + " al Plan de Cuentas.");
                        location.replace("<?php print $base_url;?>contab_plancuentas");
                    }
                    else{
                        alert("No se importaron nuevos registros al Plan de Cuentas.");
                    }
                }
            }
        });
    })
      
    $('.view_ctas').on('click', function() {
        var file_data = $('#xlsfile').prop('files')[0];   
        var form_data = new FormData();                  
        form_data.append('file', file_data);
        $.ajax({
            url: "<?php echo base_url('contabilidad/contab_plancuentas/cargar_xlscuentas');?>", 
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,                         
            type: 'post',
            success: function(json){
                $('#dataTableObj').DataTable().ajax.reload();
            }
        });
    });

});

</script>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-calendar"></i>
        Importación de Plan de Cuentas      
      </h1>
      <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>contab_plancuentas">Plan de Cuentas</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
                <div class="form-group col-md-12">
                    <div class="col-md-3" style="padding: 0px;">
                        <label for="txt_apellido">Archivo XLSX de Plan de Cuentas</label>
                    </div>  
                    <div class="col-md-6">
                        <input type="file" id="xlsfile" name="xlsfile" size="255" value="<?php if(@$empresa != NULL){ print @$empresa->tokenfirma; }?>"/>
                    </div>  
                    <div class="pull-right" style="padding: 0px;"> 
                        <a class="btn btn-success btn-sm btn-grad view_ctas" href="#" data-original-title="" title="Visualizar Cuentas"><i class="fa fa-pencil-square-o"></i> Visualizar </a>
                        <a class="btn btn-success btn-sm btn-grad load_ctas" href="#" data-original-title="" title="Importar Cuentas"><i class="fa fa-save"></i> Importar </a>
                    </div>
                </div>
                <div class="pull-right"> 
                </div>
              
            </div>
            <div class="box-body table-responsive">
              <table id="dataTableObj" class="table table-bordered table-striped table-responsive">
                <thead>
                  <tr >
                      <th>Cuenta</th>
                      <th>Descripción</th>
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
