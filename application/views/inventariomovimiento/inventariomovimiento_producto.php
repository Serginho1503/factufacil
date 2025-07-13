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
          'ajax': "lstProMovimiento",
          'columns': [
              {"data": "ver"},
              {"data": "codbarra"},
              {"data": "codauxiliar"},
              {"data": "nombre"},
              {"data": "preciocompra"}, 
              {"data": "existencia"}, 
              {"data": "nombrecorto"}
              
          ]
        });

    /*  CARGA LOS PRODUCTOS AL MOVIMIENTO 
    $(document).on('click', '.addpromov', function(){
        id = $(this).attr('id');  
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "inventario/ins_tmpmovprod",
          data: {id: id},
          success: function(json) {
              $.fancybox.close();
              $('#detmov').load(base_url + "inventario/actualiza_tabla_producto");
          }
        });
        $.fancybox.close();
        
    });*/

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
                                    <th class="col-md-1">Agrega</th>
                                    <th>Cod Barra</th>
                                    <th>Cod Auxiliar</th>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Existencia</th>
                                    <th>Unidad</th>
                                    
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