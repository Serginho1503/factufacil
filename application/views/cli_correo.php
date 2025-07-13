<style>
#contenido_cli{
    width: 700px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {

    $('#dataTableCliente').dataTable({
      "paging":   false,
      "language":{  'url': base_url + 'public/json/language.spanish.json' },
      'ajax': "Cliente/listadoDataCliente",
      'columns': [
          {"data": "ver"},
          {"data": "ident"},
          {"data": "nombre"},
          {"data": "ciudad"}
      ]
    });

    $(document).on('change','#cmb_mostrar', function(){
        var correo = $(this).val();
        $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Cliente/tmp_clicorreo",
          data: { correo: correo },
          success: function(json) {
              $('#dataTableCliente').DataTable().ajax.reload();
          }
        });

    });    


    $('.chk_cliente').click(function() {
        if ($(this).is(":checked")){
          valor = true; 
        } 
        else { 
          valor = false; 
        } 
        $('.chk_cli').each(function(){
            id = this.id;
            $('.chk_cli[id='+id+']').attr('checked',valor);
        });        

    });    

    });
</script>
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Listado de Clientes</h3>
                <div class="pull-right"> 
                <a class="btn bg-orange btn-flat enviarcorreo" href="#" data-original-title="" title=""><i class="fa fa-envelope"></i> Enviar Correo </a>
                </div>

                <!-- Filtro Clientes -->
                <div class="form-group col-md-6 pull-right">
                    <div class="col-md-3">
                        <label>Mostrar</label>
                    </div>
                    <div class="col-md-9">
                      <select class="form-control validate[required]" id="cmb_mostrar" name="cmb_mostrar">
                        <option  value="0" selected="TRUE">Solo Mayoristas</option>
                        <option  value="1" >Todos los clientes</option>
                      </select>
                    </div>  
                </div>  

                <hr style="margin-bottom: 0">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="dataTableCliente" class="table table-bordered table-striped">
                <thead>
                  <tr >
                      <th><input type="checkbox" class="chk_cliente"></th>
                      <th>Cedula</th>
                      <th>Nombre del Cliente</th>
                      <th>Ciudad</th>    
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
