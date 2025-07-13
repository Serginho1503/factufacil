<style>
#contenido_producto{
    width: 900px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        /* CARGA DEL DATATABLE (LISTADO) */
       $('#TableExistProducto').dataTable({
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
          'ajax': "lst_ExistenciaProducto",
          'columns': [
              {"data": "codbarra"},
              {"data": "codauxiliar"},
              {"data": "nombre"},
              {"data": "preciocompra"}, 
              {"data": "existencia"}, 
              {"data": "nombrecorto"}
              
          ]
        });

      $(document).on('change','#cmb_almacen', function(){
        var almacen = $('#cmb_almacen').val();

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('facturar/tmp_almexis_factura');?>",
          data: { id: almacen},
          success: function(json) {
            $('#TableExistProducto').DataTable().ajax.reload();          }
        });
      });   


    });
</script>
<div id = "contenido_producto" class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-shopping-bag"></i> Existencia de Productos</h3>
        </div>

        <!-- ALMACEN   -->
        <div class="form-group col-md-5" style="padding-top: 10px;">
            <label class="col-md-3" id="lbalmacen">Almacen</label>
            <select class="col-md-9" id="cmb_almacen" name="cmb_almacen" class="form-control">
                <option  value="0" selected="TRUE">Seleccione...</option>
                <?php  
                  if (count($almacenes) > 0) {
                    foreach ($almacenes as $alm): 
                ?>
                      <option value="<?php  print $alm->almacen_id; ?>"> <?php  print $alm->almacen_nombre ?> </option>
                <?php
                    endforeach;
                  }  
                ?>                         
            </select>
        </div> 

        <div class="box-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box-body table-responsive">
                        <table id="TableExistProducto" class="table table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
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