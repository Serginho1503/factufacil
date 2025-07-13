<style>
#contenido_formapago{
    width: 650px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();
        var contenido=document.getElementById("divcomanda").innerHTML;
        var pos = contenido.indexOf("PRECUENTA");
        contenido=contenido.substring(pos,contenido.length);
        pos = contenido.indexOf("</pre>");
        if (pos > 0){
          contenido=contenido.substring(0,pos);            
        }
        document.getElementById("txt_imprimir").value=contenido
        $(document).on('click', '.imprimircomanda', function(){
          //alert("prueba");
          //imprimir();
        });      

    });


    function imprimir2(){
        if (window.print) {

            var contenido= document.getElementById("paraimprimir").innerHTML;
            var contenidoOriginal= document.body.innerHTML;

            document.body.innerHTML = contenido;

            window.print();

            document.body.innerHTML = contenidoOriginal;
        } else {
            alert("La función de impresion no esta soportada por su navegador.");
        }

    }
    
    function imprimir(){
        if (window.print) {

          var ventimp = window.open(' ', 'popimpr');
          ventimp.document.write( document.getElementById("paraimprimir").innerHTML );
          ventimp.document.close();
          ventimp.print( );
          ventimp.close();
        } else {
            alert("La función de impresion no esta soportada por su navegador.");
        }

    }

</script>
<div id = "contenido_mese" class="col-md-12">
    <div class="box box-danger">
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('Pedido/imprimirprecuenta');?>" onSubmit='return false' >

        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Precuenta </h3>
        </div>
        

        <div class="box-body">
            <div class="row">
                <input type="hidden" id="txt_imprimir" name="txt_imprimir" value="">    
                <div id="divcomanda">
                <pre>
                <?php print @$strcomanda ?>
                </pre>
                </div>        
    

            </div>
        </div>
        <!-- /.box-body -->
        <div  align="center" class="box-footer">
<!--            <a class="btn btn-success btn-grad imprimircomanda" href="#" data-original-title="" title=""><i class="fa fa-users"></i> Imprimir </a>
    -->        
            <div class="form-actions ">
                <button type="submit" class="btn btn-danger btn-grad no-margin-bottom">
                <i class="fa fa-print imprimircomanda" ></i> Imprimir
            </button>
            </div>
        </div>
        </form>
    </div>
</div>