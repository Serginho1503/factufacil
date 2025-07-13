<style>
#contenido_producto{
    width: 600px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        /* CARGA DEL DATATABLE (LISTADO) */
        $('#TableFactura').dataTable({
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
          'ajax': "lst_factura_cliente",
          'columns': [
              {"data": "numero"},
              {"data": "fecha"},
              {"data": "monto"},
              {"data": "ver"}
          ]
        });
    });
</script>
<div id = "contenido_producto" class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-shopping-bag"></i> Listado de Facturas del Cliente</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box-body table-responsive">
                        <table id="TableFactura" class="table table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>Numero</th>
                                    <th>Fecha</th>
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

    </div>
</div>