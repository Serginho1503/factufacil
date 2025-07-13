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


        $('#TableSerieImei').on( 'init.dt', function () {
            obtener_cantidad();
        } )
        .dataTable({
          'language': { 'url': base_url + 'public/json/language.spanish.json' },
          'ajax': "lstProSerieImeiDisponible",
          'ordering': false,
          'info':     false,
          'columns': [
              {"data": "ver"},
              {"data": "numeroserie"},
              {"data": "descripcion"}
          ],
          'drawCallback': function( settings ) {
             // obtener_cantidad();
          }          
        }); 

        $(document).on("click", ".chk_serie", function() {
            var id = $(this).attr('id');
            if ($(this).is(":checked")){
              valor = true; 
            } 
            else { 
              valor = false; 
            } 
            actualiza_serie_detalle(id, valor);
        });    


        $('.chkfullserie').click(function() {
            if ($(this).is(":checked")){
              valor = true; 
            } 
            else { 
              valor = false; 
            } 
            $('.chk_serie').each(function(){
                id = this.id;
                $('.chk_serie[id='+id+']').removeAttr('checked');
                $('.chk_serie[id='+id+']').attr('checked',valor);
                actualiza_serie_detalle(id, valor);
            });        

        });    

        function actualiza_serie_detalle(iddetalle, inserta){
            if (inserta == true) {inserta = 1;} else {inserta = 0;}
            $.ajax({
                type: "POST",
                dataType: "json",
                url: base_url + "Facturar/actualiza_detalle_serie",
                data: {id: iddetalle, inserta: inserta},
                success: function(json) {
                    if (json){
                        $('#nrochk').val(json);    
                     /*   strhtml = '<input type="text" class="form-control text-center cantidad tdprecio" name="" id="' + iddetalle + '" value="' + json + '" disabled >';
                        var elements = document.getElementsByClassName("datacantidad");
                        //alert($('#datacantidad[id='+iddetalle+']'));
                        //console.log(elements);
                        for(var i=0; i<elements.length; i++) {
                            //console.log(elements[i]);
                            //alert(elements[i].innerHTML);
                            elements[i].innerHTML = strhtml;
                        }
                        //$('.datacantidad[id='+iddetalle+']').load(strhtml);
                       */ 
                    }
                }
            });        

        }    

        function actualiza_cantidad(inserta){
            cant = $('#nrochk').val();    
            cant = cant * 1;
            if (inserta == 1) { cant++;} else {cant--;}
            $('#nrochk').val(cant);    
        }

        function obtener_cantidad(){
            //alert('entrando a contar');
            var cant = 0;
            $('.chk_serie').each(function(){
                if ($(this).is(":checked")){
                    cant++;
                }      
            });             
            $('#nrochk').val(cant);    
        }    

        //obtener_cantidad();
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
                                    <th width="25px" class="text-center" style=" padding-left: 0px;"><input type="checkbox" id="" class="chkfullserie"></th>
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
