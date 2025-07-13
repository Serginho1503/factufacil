<?php
/* ------------------------------------------------
  ARCHIVO: Inventario.php
  DESCRIPCION: Contiene la vista principal del módulo de Inventario.
  FECHA DE CREACIÓN: 08/01/2018
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Ajuste de Inventario'</script>";
date_default_timezone_set("America/Guayaquil");

$usua = $this->session->userdata('usua');
$perfil = $usua->perfil;

?>

<script type='text/javascript' language='javascript'>
  $(document).ready(function () {

    var coldef = [
        {"data": "codbar"},
        //{"data": "codaux"}, //                  
        {"data": "producto"},
        {"data": "almacen"},
        {"data": "pcompra"},  
        {"data": "pventaneto"}, 
        {"data": "pventa"},  
        {"data": "ver"}
      ];
    var perfil = <?php print @$perfil; ?>;
    if (perfil != 1){
      coldef = [
        {"data": "codbar"},
        //{"data": "codaux"}, //                  
        {"data": "producto"},
        {"data": "almacen"},
        {"data": "ver"}
      ];
    }

    $('#dataTableAjunv').dataTable({
      'language': { 'url': base_url + 'public/json/language.spanish.json' },
      'ajax': "listadoAjuInventario",
      'columns': coldef
    });
  
    $(document).on('change','#cmb_almacen', function(){
      var idalm = $('#cmb_almacen option:selected').val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "inventario/tmp_almacen",
        data: {idalm: idalm},
        success: function(json) {
          $('#dataTableAjunv').DataTable().ajax.reload();
        }
      }); 
    });
/*
    $(document).on('change','.actualizar', function(){
      var idpro = $(this).attr('id');
      var alm = $('.actualizar[name='+idpro+']').val();
      var cant = $('.actualizar[id='+idpro+']').val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "inventario/updalmapro",
        data: {idpro: idpro, alm:alm, cant: cant},
        success: function(json) {
          $('#dataTableAjunv').DataTable().ajax.reload();
        }
      }); 
    });
*/
    $(document).on('change','.actualizar', function(){
      var idpro = $(this).attr('id');
      var cant = $('.actualizar[id='+idpro+']').val();
      var varid = idpro.split('-');
      var idproducto = varid[0];
      var almacen = varid[1];
      //alert(idproducto+' - '+almacen+' - '+cant);
      $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "inventario/updalmapro",
        data: {idpro: idproducto, alm:almacen, cant: cant},
        success: function(json) {
          $('#dataTableAjunv').DataTable().ajax.reload();
        }
      }); 
    });


    $(document).on('click', '#reporte', function(){  
      window.open('<?php print $base_url;?>inventario/reporteXLS'); 
    });



}); 

</script>


<div class="content-wrapper">

    <section class="content-header">
      <h1>
        <i class="fa fa-wrench"></i> Existencia de Inventario
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>Inventario/ajuste">Existencia de Inventario</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                <label for="" class="col-sm-3 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Almacen</label>
                <div class="col-md-9" style="padding-right: 0px;">
                  <input type="hidden" id="txt_idventa" name="txt_idventa" value="<?php if(@$cliente != NULL){ print @$cliente->id_venta; }?>" >    
                    <select id="cmb_almacen" name="cmb_almacen" class="form-control">
                        <?php 
                          if(@$almacenes != NULL){ ?>
                            <option  value="0" selected="TRUE">TODOS</option>
                        <?php } else { ?>
                            <option  value="0" selected="TRUE">Seleccione...</option>
                        <?php } 
                                  if (count($almacenes) > 0) {
                                    foreach ($almacenes as $alm):
                                        if(@$tmpmov->id_almacen != NULL){
                                            if($alm->almacen_id == $tmpmov->id_almacen){ ?>
                                                <option  value="<?php  print $alm->almacen_id; ?>" selected="TRUE"><?php  print $alm->almacen_nombre ?></option> 
                                                <?php
                                            }else{ ?>
                                                <option value="<?php  print $alm->almacen_id; ?>"> <?php  print $alm->almacen_nombre ?> </option>
                                                <?php
                                            }
                                        }else{ ?>
                                            <option value="<?php  print $alm->almacen_id; ?>"> <?php  print $alm->almacen_nombre ?> </option>
                                            <?php
                                            }   ?>
                                        <?php

                                    endforeach;
                                  }
                                  ?>
                                  
                    </select>                                                
                </div>
              </div>
              <div class="pull-right"> 
                <?php if($perfil == 1) { ?>
                <a id="reporte" class="btn bg-green-active color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-file-excel-o"></i> Reporte de Inventario</a>
              <?php } ?>

              </div>
            </div>
                    <div class="box-body">

                      <div class="row">

                        <div class="col-xs-12">
                        
                            <div id="upd_tabla" class="box-body table-responsive ">

                              <table id="dataTableAjunv" class="table table-bordered table-hover table-responsive">
                                  <thead>
                                        <tr>
                                            <th class="text-center col-md-1">Cod Barra</th>
                                            <!--<th class="text-center col-md-1">Cod Auxiliar</th>-->
                                            <th class="text-center col-md-1">Producto</th>
                                            <th class="text-center col-md-1">Almacen</th>
                                          <?php if(@$perfil == 1){ ?>
                                            <th class="text-left col-md-2">P Compra</th>
                                            <?php } ?> 
                                            <th class="text-center col-md-1">P Venta Nota</th>
                                            <th class="text-center col-md-1">P Venta Factura</th>
                                            <th class="text-center col-md-1">Existencia</th>
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
           
        </div>
    </section>
    
</div>
  

