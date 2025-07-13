<style>
#contenido_producto{
    width: 900px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        /* CARGA DEL DATATABLE (LISTADO) */
        $('#TableProducto').dataTable({
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
          'ajax': "lstProducto",
          'columns': [
              {"data": "ver"},
              {"data": "codbarra"},
              {"data": "nombre"},
              {"data": "existencia"},
              {"data": "almacen"}
          ]
        });
    });
</script>
<div id = "contenido_producto" class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-shopping-bag"></i> Listado de Productos</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box-body table-responsive">
                        <table id="TableProducto" class="table table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>Acción</th>
                                    <th>Cod Barra</th>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                    <th>Almacén</th>
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