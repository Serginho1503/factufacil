<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
#contenido_factura{
    width: 820px;
}  
/* 
.pre {
    font-family: courier new;
    font-size: 20px;
    color: #000000; 
    margin:10px;
    text-align:justify;
}
*/
pre {
    font-family: courier new;    
    display: block;
    padding: 9.5px;
    margin: 0 0 10px;
    font-size: 12px;
    line-height: 1.42857143;
    color: #333;
    word-break: break-all;
    word-wrap: break-word;
    background-color: #f5f5f5;
    border: 1px solid #ccc;
    border-radius: 4px;
}



</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();
        var contenido=document.getElementById("divimpresion").innerHTML;
        var pos = contenido.indexOf("Cliente");
        contenido=contenido.substring(pos,contenido.length);
        pos = contenido.indexOf("</pre>");
        if (pos > 0){
          contenido=contenido.substring(0,pos);            
        }
        document.getElementById("txt_imprimir").value=contenido



    });

    function printLocal1() {
         var contenido= document.getElementById("divimpresion").innerHTML;
         var contenidoOriginal= document.body.innerHTML;

         document.body.innerHTML = contenido;

         window.print();

         document.body.innerHTML = contenidoOriginal;
    }        

    function printLocal00(muestra)
    {
    var ficha=document.getElementById("divimpresion");
    var ventimp=window.open(' ','popimpr');
    var str = $("#txt_imprimir").val();
/*    ventimp.document.write("<p style='overflow:visible;'>" + str + "</p>");*/
    ventimp.document.write("<p style='font-family:calibri; font-size: 20pt; line-height: 1.5;'>" + ficha.innerHTML + "</p>");
    ventimp.document.close();ventimp.print();
    ventimp.close();
    }


    function printLocal(muestra){
        var ficha=document.getElementById("divimpresion");
        var ventimp=window.open(' ','popimpr');
        ventimp.document.write(ficha.innerHTML);
        ventimp.document.close();ventimp.print();
        ventimp.close();
    }
    
    var impresionlocal = <?php if(@$impresionlocal != NULL) {print $impresionlocal;} else { print 0;} ?>;
    if (impresionlocal == 1){
        $("#imp_server").remove();       
    }
    else{
        $("#imp_local").remove();       
    }

</script>
<div id = "contenido_factura" class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Factura de Venta </h3>
        </div>
        
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('Facturar/imprimir');?>" onSubmit='return false' >

        <div class="box-body">
            <div class="row">
                <input type="hidden" id="txt_imprimir" name="txt_imprimir" value="">    
                <div id="divimpresion">
                <pre>
                <?php print @$strcomanda ?>
                </pre>
                </div>        
    

            </div>
        </div>
        <!-- /.box-body -->
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <input id="imp_local" type="button" onclick="printLocal()" value="Imprimir" />


                <button id="imp_server" type="submit" class="btn btn-danger btn-grad no-margin-bottom">
                <i class="fa fa-print imprimircomanda" ></i> Imprimir
            </button>
            </div>
        </div> 

     <!--   <input type="button" onclick="printLocal()" value="imprimir Local" />-->
        </form>
    </div>
</div>