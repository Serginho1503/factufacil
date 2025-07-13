<style>
#contenido_formapago{
    width: 900px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();
        var contenido=document.getElementById("divimpresion").innerHTML;
        var pos = contenido.indexOf("FACTURA");
        contenido=contenido.substring(pos,contenido.length);
        pos = contenido.indexOf("</pre>");
        if (pos > 0){
          contenido=contenido.substring(0,pos);            
        }
        document.getElementById("txt_imprimir").value=contenido

    });


    $('.imprimir').click(function(){
        var ficha=document.getElementById("divimpresion");
        var ventimp=window.open(' ','popimpr');
        var str = $("#txt_imprimir").val();
    /*    ventimp.document.write("<p style='overflow:visible;'>" + str + "</p>");*/
        ventimp.document.write("<p style='font-family:calibri; font-size: 20pt; line-height: 1.5;'>" + ficha.innerHTML + "</p>");
        ventimp.document.close();ventimp.print();
        ventimp.close();

    });


</script>
<div id = "contenido_mese" class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Servicio Técnico </h3>
        </div>
        
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('Serviciotecnico/imprimir');?>" onSubmit='return false' >

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
                <button type="button" class="btn btn-danger btn-grad no-margin-bottom imprimir">
                <i class="fa fa-print " ></i> Imprimir
            </button>
            </div>
        </div>
        </form>
    </div>
</div>