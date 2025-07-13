<style>
#contenido_serieimei{
    width: 600px;
}   
.no-sort::after { display: none!important; }

.no-sort { pointer-events: none!important; cursor: default!important; }

table.dataTable thead > tr > th {
    padding-right: 0px;
}


</style>

<script type="text/javascript">

    $(document).ready(function () {


        $('#TableSerieImei').dataTable({
          'language': { 'url': base_url + 'public/json/language.spanish.json' },
          'ajax': "lstProSerieImei",
          'ordering': false,
          'info':     false,
          'columns': [
              {"data": "idserie"},
              {"data": "numeroserie"},
              {"data": "descripcion"}
          ],
        }); 

        contarchka();

        setTimeout(function(){ 
            contarchka();
        }, 1000);

    }); 


       


</script>

<div id="contenido_serieimei" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-slack"></i> Numeros de Serie/Imei del Producto </h3>
            <div class="form-actions pull-right">
                Cantidad: <input style="width:70px;" type="text" id="nrochk" class="text-center" value="0" disabled>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box-body table-responsive">
                        <table id="TableSerieImei" class="table table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th width="25px" class="text-center" style=" padding-left: 0px;"><input type="checkbox" id="" class="chkfullimaiserie"></th>
                                    <th>Nro Serie</th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div  align="center" class="box-footer">

        </div>

    </div>
</div>


<script type="text/javascript">


    function contarchka(){
        var nro = $(":checkbox.chkimaiserie:checked").length;
        $('#nrochk').val(nro);
    }

   
</script>    
