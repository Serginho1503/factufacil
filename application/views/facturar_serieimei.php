<style>
#contenido_serieimei{
    width: 600px;
}   
</style>

<script type="text/javascript">
    $(document).ready(function () {
        $('#TableSerieImei').dataTable({
          'language': { 'url': base_url + 'public/json/language.spanish.json' },
          'ajax': "lstProSerieImei",
          'columns': [
              {"data": "numeroserie"},
              {"data": "descripcion"},
              {"data": "ver"}
          ]
        });          
    });
</script>

<div id = "contenido_serieimei" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-slack"></i> Numeros de Serie/Imei del Producto </h3>
            <div class="form-actions pull-right">
            </div>
        </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box-body table-responsive">
                            <table id="TableSerieImei" class="table table-bordered table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th>Nro Serie</th>
                                        <th>Descripción</th>
                                        <th class="col-md-1">Acción</th>
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
</div>