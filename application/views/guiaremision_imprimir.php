<style>
#contenido_formapago{
    width: 650px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();
        var contenido=document.getElementById("divimpresion").innerHTML;
        var pos = contenido.indexOf("<pre>");
        contenido=contenido.substring(pos+5,contenido.length);
        pos = contenido.indexOf("</pre>");
        if (pos > 0){
          contenido=contenido.substring(0,pos);            
        }
        document.getElementById("txt_imprimir").value=contenido

    });

    function printLocal(){
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
<div id = "contenido_mese" class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Guía de Remisión </h3>
        </div>
        
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('Guiaremision/imprimir');?>" onSubmit='return false' >

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
        </form>
    </div>
</div>