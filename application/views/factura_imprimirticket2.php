<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
#contenido_factura{
    width: 300px;
}   
</style>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#formID").validationEngine();
        var contenido=document.getElementById("divimpresion").innerHTML;
        /*var pos = contenido.indexOf("FACTURA");*/
        var pos = contenido.indexOf("<pre>");
        contenido=contenido.substring(pos+5,contenido.length);
        pos = contenido.indexOf("</pre>");
        if (pos > 0){
          contenido=contenido.substring(0,pos);            
        }
        document.getElementById("txt_imprimir").value=contenido
        
});

    



</script>
<div id = "contenido_factura" class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Factura de Venta </h3>
        </div>
        
        <form id="formID" name="formID" method='POST' action="<?php echo base_url('Facturar/imprimirticket');?>" onSubmit='return false' >

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
                <button type="submit" class="btn btn-danger btn-grad no-margin-bottom">
                <i class="fa fa-print imprimircomanda" ></i> Imprimir
            </button>
            </div>
        </div>

        </form>
    </div>
</div>