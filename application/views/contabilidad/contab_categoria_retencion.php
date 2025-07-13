<?php
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Categorías Contables de Retenciones'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<style type="text/css">
  .form-control{
    font-size: 12px;
    height: 28px;
  }

  .table > tbody > tr > td{
    padding-bottom: 0px;
    padding-top: 1px;
  }

  .form-group {
      margin-bottom: 5px;
  }

  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 


  #tpcredito{
    display: none; 
  }  

    .autocomplete-jquery-results{
        border:1px solid silver;
        float:right;
        margin-top:2px;
        position:absolute;
        display: none;
        z-index: 999;
    }

    /*Esta clase se activa cuando el usuario se mueve por las sugerencias*/
    .autocomplete-jquery-mark{
        color:black;
        background-color: #E0F0FF !important;
    }

    /* Cada sugerencia va a llevar esta clase, por lo tanto tomara el estilo siguiente */
    .autocomplete-jquery-item{
        border-bottom: 1px solid lightgray;
        display: block;
        height: 25px;
        padding-top: 5px;
        text-decoration: none;     
        padding-left: 3px;  
        background-color: white;
    }
    .autocomplete-jquery-results{
         box-shadow: 1px 1px 3px black;
    }
    /* Al pasar por encima de las sugerencias*/
    .autocomplete-jquery-item:hover{
        background-color: #E0F0FF;
        color:black;
    }

    .tdvalorcategoria{
      width: 300px;
    }

    .tdvalorcodcuenta{
      width: 200px;
    }

</style>
<script>
  $( document ).ready(function() {

    $('#dataTableObj').dataTable({
          "language":{  "lengthMenu":"Mostrar _MENU_ registros por página.",
                        "zeroRecords": "Lo sentimos. No se encontraron registros.",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "No hay registros aún.",
                        "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                        "search" : "Búsqueda",
                        "LoadingRecords": "Cargando ...",
                        "Processing": "Procesando...",
                        "SearchPlaceholder": "Comience a teclear...",
                        "paginate": { "previous": "Anterior",
                                      "next": "Siguiente", }
                      },
          'ajax': "contabilidad/contab_categoria/listadoCategoriaretencion",
          'columns': [
              {"data": "categoria"},
              {"data": "codigocuenta"},
              {"data": "descripcion"}
          ]
    });
/*
    $(document).on('click', '.add_categoria', function(){
        var disabled = $(this).is('[disabled=disabled]');
        if (disabled == true) { return false; }
        $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url('contabilidad/contab_categoria/inserta_categoria');?>",
        }).done(function (result) {
            $('#dataTableObj').DataTable().ajax.reload();
            $('.add_categoria').attr('disabled', true);
        }); 
    });

    $(document).on('blur', '.upd_categoria', function(){
        id = $(this).attr('id');
        var categoria = $('.upd_categoria[id='+ id +']').val();
        if (categoria == ''){
            alert("Ingrese el nombre de la categoría.");
        }
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('contabilidad/contab_categoria/actualiza_categoria_nombre');?>",
            data: {
                id: id,
                categoria: categoria
            }
        });

        actualiza_add_boton();
    });

    function actualiza_add_boton(){
        var vacio = 0;
        $('.upd_categoria').each(function (index, value) { 
            if ($(this).val() == ''){
                vacio++; 
            }
        });
        $('.add_categoria').attr('disabled', vacio > 0);
    }
*/
    var tipocategoria = 4;
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href");       
        switch(target) {
            case "#tabrentacompra":
                tipocategoria = 5; break;
            case "#tabivaventa":
                tipocategoria = 6; break;
            case "#tabivacompra":
                tipocategoria = 7; break;
            default:
                //#tabrentaventa
                tipocategoria = 4; 
        } 
        var empresa = $("#cmb_empresa").val();
        $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url('contabilidad/contab_categoria/tmp_catret');?>",
                data: { empresa: empresa, tipo: tipocategoria }
            }).done(function (result) {
                $('#dataTableObj').DataTable().ajax.reload();
            }); 
    });     

    var tmpid = 0;
    $(document).on('click', '.divcuenta', function() {
        tmpid = $(this).attr('id');
        $(this).autocomplete();
    });

    $(document).on('change', '.upd_cuenta', function() {
      var codcuenta = $(this).val(); 
      var id = tmpid.substring(10);
      var empresa = $('#cmb_empresa').val(); 
      if (codcuenta != ''){
        valida_cuenta(id, codcuenta, empresa);
      }
      else{
        $('.desc_cuenta[id='+ id +']').html("");
        $('#add_detalle').attr('disabled', true);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('contabilidad/contab_categoria/actualiza_categoria_cuenta');?>",
            data: {
                id: id,
                idcuenta: 0,
                empresa: empresa
            }
        });
      }
    });

    $(document).on('click', '.autocomplete-jquery-item', function(){  
      id = tmpid.substring(10);
      var codcuenta = $(this).text(); 
      if (codcuenta === ""){
        return false;
      }
      var empresa = $('#cmb_empresa').val(); 
      valida_cuenta(id, codcuenta, empresa);
    });

    function valida_cuenta(id, codcuenta, empresa){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('contabilidad/contab_categoria/busca_cuenta');?>",
            data: {
              codcuenta: codcuenta,
              empresa: empresa
            },
            success: function(json) {
                var idcuenta = 0;
                if (json.resu) {
                  $('.desc_cuenta[id='+ id +']').html(json.resu.descripcion);
                  $('#add_detalle').attr('disabled', false);
                  idcuenta = json.resu.id;            
                } 
                else{ 
                  //alert("Ingrese un codigo de cuenta válido.");
                  $('.desc_cuenta[id='+ id +']').html("");
                  $('#add_detalle').attr('disabled', true);
                  $('.divcuenta[id='+ id +']').focus();            
                }            
                $.ajax({
                      type: "POST",
                      dataType: "json",
                      url: "<?php echo base_url('contabilidad/contab_categoria/actualiza_categoria_cuenta');?>",
                      data: {
                        id: id,
                        idcuenta: idcuenta,
                        empresa: empresa
                      }
                  });
            }
        });
    }
/*
    $(document).on('click', '.del_categoria', function(){
        id = $(this).attr('id');
        if (confirm("Desea eliminar la categoría")){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('contabilidad/contab_categoria/elimina_categoria_cuenta');?>",
            data: {id: id},
            success: function(json) {
            if (json.mens == 0){
                alert("No se pudo eliminar la categoría. Existe informacion asociada.");
            }  
            else{
                $('#dataTableObj').DataTable().ajax.reload();
            }
            }
        });
        }  
    });

    actualiza_add_boton();
*/

        var empresa = $("#cmb_empresa").val();
        $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url('contabilidad/contab_categoria/tmp_catret');?>",
                data: { empresa: empresa, tipo: tipocategoria }
            }).done(function (result) {
                $('#dataTableObj').DataTable().ajax.reload();
            }); 

  });

</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       <i class="fa fa-sort-amount-asc"></i> Categorías Contables de Retenciones </a></li>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- SECCION DEL FORMULARIO-->
            <div class="col-md-12" style="padding-right: 5px;">
                <!-- general form elements -->
                <div class="box box-danger">
<!--                     <div class="box-header with-border">
                        <h3 class="box-title">Parametros Generales</h3>
                    </div> -->
             
                        <div class="box-body">

                            <div style="" class="form-group col-md-5">
                                <div class="col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                                <label for="lb_res">Empresa</label>
                                </div> 
                                <div class="col-md-9">
                                <select id="cmb_empresa" name="cmb_empresa" class="form-control ">
                                <?php 
                                    if (count($empresas) > 0) {
                                        foreach ($empresas as $obj):
                                            if(@$tmpempresa != NULL){
                                                if($obj->id_emp == $tmpempresa){ ?>
                                                    <option value="<?php  print $obj->id_emp; ?>" selected="TRUE"> <?php  print $obj->nom_emp; ?> </option>
                                                    <?php
                                                }else{ ?>
                                                    <option value="<?php  print $obj->id_emp; ?>" > <?php  print $obj->nom_emp; ?> </option>
                                                    <?php
                                                }
                                            }else{ ?>
                                                <option value="<?php  print $obj->id_emp; ?>" > <?php  print $obj->nom_emp; ?> </option>
                                                <?php
                                                }   ?>
                                            <?php
                                        endforeach;
                                    }
                                    ?>
                                </select>          
                                </div>                         
                            </div>

                            <!-- <div class="pull-right"> 
                                <a class="btn btn-success btn-grad add_categoria" href="#" data-original-title="" title=""><i class="fa fa-plus-square"></i> Añadir </a>
                            </div> -->

                         <div class="nav-tabs-custom col-md-12">
                          <ul class="nav nav-tabs">
                           <li class="active"><a href="#tabrentaventa" data-toggle="tab"><i class="fa fa-tint categoria" aria-hidden="true"></i> Retención Renta Venta</a></li>                            
                           <li ><a href="#tabrentacompra" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Retención Renta Compra </a></li>                            
                           <li ><a href="#tabivaventa" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Retención IVA Venta</a></li>                            
                           <li ><a href="#tabivacompra" data-toggle="tab"><i class="fa fa-tint" aria-hidden="true"></i> Retención IVA Compra</a></li>                            
                          </ul>

                          <div class="tab-content">
                           <div class="tab-pane active " id="tabrentaventa">

                           </div>  

                           <div class="tab-pane" id="tabrentacompra">

                           </div> 

                           <div class="tab-pane" id="tabivaventa">

                           </div> 

                           <div class="tab-pane" id="tabivacompra">

                            </div> 

                         </div>  <!-- Tab Control --> 
                        </div>  <!-- Nav Tab Control --> 

                        <div class="box-body table-responsive">
                        <table id="dataTableObj" class="table table-bordered table-striped ">
                            <thead>
                            <tr >
                                <th>Categoría</th>
                                <th>Cuenta</th>
                                <th>Descripción</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        </div>

                        </div>
                        <div  align="center" class="box-footer">
                        </div>
                </div>
            </div>

        </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

