<style>
#contenido_producto{
    width: 600px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        /* CARGA DEL DATATABLE (LISTADO) */
        $('#ObjDato').dataTable({
          "language":{  
                      'url': base_url + 'public/json/language.spanish.json', 
                      },
          'ajax': "lstDatoadicional",
          "paging": false,
          "searching": false,
          "ordering": false,
          "info":     false,
          'columns': [
              {"data": "nombre"},
              {"data": "dato"}
          ]
        });
    });
</script>
<div id = "contenido_producto" class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-shopping-bag"></i> Datos Adicionales de Venta</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box-body table-responsive">
                        <table id="ObjDato" class="table table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>Dato Adicional</th>
                                    <th>Detalle</th>
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