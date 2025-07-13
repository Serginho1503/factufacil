<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
#contenido_formapago{
    width: 450px;
}   
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
        var pos = contenido.indexOf("PROFORMA");
        contenido=contenido.substring(pos,contenido.length);
        pos = contenido.indexOf("</pre>");
        if (pos > 0){
          contenido=contenido.substring(0,pos);            
        }
        document.getElementById("txt_imprimir").value=contenido

        $(document).on('click', '#imprimircomanda', function(){
            w=window.open();
            w.document.write(document.getElementById("divimpresion").innerHTML);
            w.print();
            w.close();
        });

    });



</script>
<div id = "contenido_formapago" class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Proforma </h3>
        </div>
        
<!--         <form id="formID" name="formID" method='POST' action="<?php echo base_url('Facturar/imprimir');?>" onSubmit='return false' >
 -->
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

            <a id="imprimircomanda" class="btn bg-blue-active color-palette btn-grad" style="margin-bottom: 0px; margin-top: 23px;" href="#" data-original-title="" title=""><i class="fa fa-file-text-o" aria-hidden="true"></i> Imprimir </a>

<!--             <div class="form-actions ">
                <button type="submit" class="btn btn-danger btn-grad no-margin-bottom">
                <i class="fa fa-print imprimircomanda" ></i> Imprimir
            </button>
            </div>
 -->        </div>
        </form>
    </div>
</div>