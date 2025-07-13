<?php
/* ------------------------------------------------
  ARCHIVO: Inventario.php
  DESCRIPCION: Contiene la vista principal del módulo de Inventario.
  FECHA DE CREACIÓN: 08/01/2018
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Ajuste de Precios'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>
  $(document).ready(function () {

    $('#dataTablePropre').dataTable({
      'language': { 'url': base_url + 'public/json/language.spanish.json' }
    });

    $(document).on('change','.actualizar', function(){
      var idpro = $(this).attr('id');
      var idpre = $(this).attr('name');
      var monto = $(this).val();
//      alert(idpro+" -> "+idpre+" -> "+monto);

      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "producto/updprepro",
        data: { idpro: idpro, idpre: idpre, monto: monto },
        success: function(json) {
        }
      }); 

      
    });  

}); 

</script>


<div class="content-wrapper">

    <section class="content-header">
      <h1>
        <i class="fa fa-refresh"></i> Ajustes de Precios
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>Inventario/ajuste">Ajustes de Precios</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
            </div>
                    <div class="box-body">

                      <div class="row">

                        <div class="col-xs-12">
                        
                            <div id="upd_tabla" class="box-body table-responsive ">

                              <table id="dataTablePropre" class="table table-bordered table-hover table-responsive">
                                  <thead>
                                    <tr>
                                        <th class="text-center col-md-1">Cod Barra</th>
                                        <th class="text-center col-md-1">Cod Auxiliar</th>
                                        <th class="text-center col-md-1">Producto</th>
                                        <th class="text-center col-md-1">TIENDA/PVP</th>
                                    </tr>                            
                                  </thead>  
                                  
                                  <tbody>  
                                    <?php 
                                      foreach ($pro as $pro) {
                                        ?>
                                        <tr>
                                          <td class="text-center col-md-1"><?php print @addslashes($pro->pro_codigobarra); ?></td>  
                                          <td class="text-center col-md-1"><?php print @addslashes($pro->pro_codigoauxiliar); ?></td>
                                          <td class="text-center col-md-1"><?php print @addslashes($pro->pro_nombre); ?></td>
                                          <td class="text-center col-md-1">
                                              <?php // print @$pro->pro_precioventa; ?>
                                              <input type="text" class="text-center actualizar" name="0" id="<?php print @$pro->pro_id; ?>" value="<?php print @$pro->pro_precioventa; ?>"></td>
                                          
                                      
                                        </tr>
                                      <?php
                                      }
                                    ?>
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
           
        </div>
    </section>
    
</div>
  

