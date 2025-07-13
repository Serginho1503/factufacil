<style>
#contenido_cat{
width: 400px;
}   
</style>
<script>
$( document ).ready(function() {
    $("#formID").validationEngine();
});

</script>    
<div id = "contenido_cat" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"></i> Categorias de los Gastos y Compras</h3>
        </div>
        
        <div class="box-body">
          <div class="row">
            <input type="hidden" id="txt_id" name="txt_id" value="<?php if($id != NULL){ print $id; }?>" >    

                <div class="form-group col-md-12">
                  <label>Categorias</label>
                  <select id="cmb_catc" name="cmb_catc" class="form-control">
                    <?php 
                      if(@$cat != NULL){ ?>
                        <option  value="" selected="TRUE">Seleccione...</option>
                    <?php } 
                              if (count($cat) > 0) {
                                  foreach ($cat as $c):
                                      ?>
                                      <option value="<?php  print $c->id_cat_gas; ?>"> <?php  print $c->nom_cat_gas ?> </option>
                                      <?php
                                  endforeach;
                              }
                              ?>
                    </select>

                </div>
                
            </div>
        </div>
        <!-- /.box-body -->
        <div  align="center" class="box-footer">
            <div class="form-actions ">
                <button type="button" class="btn btn-danger btn-grad no-margin-bottom guardacat">
                <i class="fa fa-save "></i> Guardar
            </button>
            </div>
        </div>
        </form>
    </div>
</div>