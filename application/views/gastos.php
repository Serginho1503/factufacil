<?php
/* ------------------------------------------------
  ARCHIVO: Gastos.php
  DESCRIPCION: Contiene la vista principal del módulo de Gastos.
  FECHA DE CREACIÓN: 30/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Gastos'</script>";
date_default_timezone_set("America/Guayaquil");


?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {
      /* CARGA DE DATOS EN EL DATATABLE 
      $('#dataTableGas').DataTable({
        'language': {
                'url': base_url + 'public/json/language.spanish.json'
            }
          });*/

      $.datepicker.setDefaults($.datepicker.regional["es"]);
      $('#desde').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy', 
          firstDay: 1
        });
      $('#desde').on('changeDate', function(ev){
          $(this).datepicker('hide');
      });

      $('#hasta').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd/mm/yy', 
          firstDay: 1
        });
      $('#hasta').on('changeDate', function(ev){
          $(this).datepicker('hide');
      }); 

      /* AGREGAR GASTOS */
      $(document).on('click', '.gas_add', function(){
        var sucursal = $("#cmb_sucursal").val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Cajachica/cajachica_abiertasucursal",
            data: { sucursal: sucursal },
            success: function(json) {
              if(json.resu > 0){
                location.replace("<?php print $base_url;?>gastos/add_gastos");
              }else{
                alert("No se ha realizado la apertura de Caja Chica.");
              }
            }
        });

        //location.replace("<?php print $base_url;?>gastos/add_gastos");
      });

      $(document).on('change', '#cmb_sucursal', function(){
        actualiza_monto();
      });

      /* ACTUALIZAR LISTADO DE GASTOS POR RAGO DE FECHA */
      $('.actualiza').click(function(){
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();
      var sucursal = $("#cmb_sucursal").val();

      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Gastos/tmp_gastos_fecha');?>",
          data: { hasta: hasta, desde: desde, sucursal: sucursal },
          success: function(json) {
            $('#dataTableGas').DataTable().ajax.reload();
            actualiza_monto();

          }
      }); 
      
      });   

      /* ANULAR FACTURA */
      $(document).on('click', '.anu_gas', function(){
        var id = $(this).attr('id');
        $.fancybox.open({
                  type: "ajax",
                  width: 550,
                  height: 550,
                  ajax: {
                     dataType: "html",
                     type: "POST",
                     data: {id: id}
                  },
                  href: "<?php echo base_url('gastos/confirmar_anulacion');?>",
                   success: function(json) {
                    $.fancybox.close();
                   }
                });
      });         

    function actualiza_monto(){

        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Gastos/upd_gastos_total",
            //data: { id: id },
            success: function(json) {
              var total = 0;
              if(json == null){
                total = 0;
              }else{
                total = json
              }
              $('#monto').html('<strong>$ '+total+'</strong>');
            }
        });

    }


      /* REPORTE */
      $('.reporte').click(function(){
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();
      var sucursal = $("#cmb_sucursal").val();

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Gastos/tmp_gastos_fecha');?>",
          data: { hasta: hasta, desde: desde, sucursal: sucursal }
        }); 
        window.open('<?php print $base_url;?>gastos/reporte');
      
      });  


      /* CARGA DE DATOS EN EL DATATABLE */
     tablecomp=$('#dataTableGas').dataTable({
      rowCallback:function(row,data) {
        if(data["estatus"] == '3')
        {
          $($(row)).css("background-color","#DD4B39");
        }
      },'language': {
                'url': base_url + 'public/json/language.spanish.json'
            },
        'ajax': "Gastos/listadoDataGas",
        'columns': [
            {"data": "fecha"},           
            {"data": "proveedor"},
            {"data": "factura"},
            {"data": "descripcion"},  
            {"data": "categoria"},  
            {"data": "total"},  
            {"data": "ver"}
        ]
      });




      /* MODIFICAR GASTOS  */
      $(document).on('click', '.edi_gas', function(){
        id = $(this).attr('id');
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('gastos/tmp_gastos');?>",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) == 1) {
                 location.replace("<?php print $base_url;?>gastos/upd_gastos");
              } else {
                 alert("Error de conexión");
              }
           }
        }); 
      })

      /* ELIMINAR GASTOS */
      $(document).on('click', '.del_gas', function(){
        id = $(this).attr('id');

        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('gastos/del_gastos');?>",
           data: {id: id},
           success: function(json) {
              alert("SE ELIMINO EL GASTO SELECCIONADO");
              if (parseInt(json) > 0) {
                 location.replace("<?php print $base_url;?>gastos");
              } else {
                 alert("Error de conexión");
              }
           }
        }); 

      })

      $(document).on('click', '.ret_comp', function(){
        id = $(this).attr('id');
        var consfinal = $(this).attr('name');

        if (consfinal == '1'){
          alert("Para registrar la retencion la factura debe estar asociada a un cliente.");
          return false;
        }
        
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php print $base_url;?>Gastos/temp_gastosret",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) > 0) {
                 location.replace("<?php print $base_url;?>Gastos/gastos_retencion");
              } else {
                 alert("Error de conexión");
              }
           }
        }); 
      })


    /* Boton del listado para imprimir GASTO 
    $(document).on('click', '.comp_print', function(){
      var id = $(this).attr('id');
      //alert(id);
      $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST",
                   data: {id: id}
                },
                href: "<?php echo base_url('Gastos/imprimirgasto');?>" 
              });
    }); */     

    /* Boton de Abonos de GASTO 
      $(document).on('click', '.gas_abono', function(){
        id = $(this).attr('id');
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('gastoabono/tmp_gastos');?>",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) == 1) {
                 location.replace("<?php print $base_url;?>gastoabono");
              } else {
                 alert("Error de conexión");
              }
           }
        }); 
      })*/

}); 

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-money"></i> Gastos
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>gastos">Gastos</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-danger">
              <div class="box-header with-border">

                <!-- SUCURSAL  -->
                <div class="form-group col-md-2">
                  <label for="lb_res">Sucursal</label>
                  <select id="cmb_sucursal" name="cmb_sucursal" class="form-control">
                  <?php 
                    if(@$sucursales != NULL){ ?>
                    <?php } else { ?>
                    <option  value="" selected="TRUE">Seleccione Sucursal...</option>
                    <?php } 
                      if (count($sucursales) > 0) {
                        foreach ($sucursales as $obj):
                            if(@$tmpcomp->id_sucursal != NULL){
                                if($obj->id_sucursal == $tmpcomp->id_sucursal){ ?>
                                    <option value="<?php  print $obj->id_sucursal; ?>" selected="TRUE"> <?php  print $obj->nom_sucursal; ?> </option>
                                    <?php
                                }else{ ?>
                                    <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                                    <?php
                                }
                            }else{ ?>
                                <option value="<?php  print $obj->id_sucursal; ?>" > <?php  print $obj->nom_sucursal; ?> </option>
                                <?php
                                }   ?>
                            <?php
                        endforeach;
                      }
                    ?>
                  </select>                                  
                </div>

                <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
                  <label for="">Desde</label>
                  <div class="input-group date col-sm-7">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" class="form-control pull-right validate[required]" id="desde" name="desde" value="<?php print  date("d/m/Y"); ?>">
                  </div>
                </div> 

                <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
                  <label for="">Hasta</label>
                  <div class="input-group date col-sm-10">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" class="form-control pull-right validate[required]" id="hasta" name="hasta" value="<?php print  date("d/m/Y"); ?>">
                    <span class="input-group-btn">
                    <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                    </span>

                  </div>
                </div>  

                <div class="col-md-3" style="margin-bottom: 0px; margin-top: 32px;">
                  <h4 style="margin-bottom: 0px; margin-top: 0px;"><div id="monto"> Monto: <?php print number_format(@$totalg,2,",","."); ?></div></h4>
                </div>    

                <div class="pull-right"> 
                    <a class="btn bg-light-blue color-palette btn-sm btn-grad reporte" href="#" data-original-title="" title=""><i class="fa fa-list-alt"></i> Reporte de Gastos </a>                      
                    <button type="button" class="btn bg-orange btn-sm btn-grad gas_add" >
                      <i class="fa fa-plus-square"></i> Añadir
                    </button>                     
                </div>
              </div>
              <div class="box-body">

                      <div class="row">

                        <div class="col-xs-12">
                        
                            <div id="upd_tabla" class="box-body table-responsive ">

                        <table id="dataTableGas" class="table table-bordered table-hover table-responsive">
                            <thead>
                                  <tr>
                                      <th class="text-center col-md-1">Fecha</th>
                                      <th class="text-center col-md-1">Proveedor</th>
                                      <th class="text-center col-md-1">Nro Factura</th>
                                      <th class="text-left col-md-2">Descripcion</th>
                                      <th class="text-left col-md-2">Categoria</th>
                                      <th class="text-center col-md-1">Total</th>
                                      <th class="text-center col-md-1">Acción</th>
                                  </tr>                            
                            </thead>    
                            <tbody>                                                        
                            </tbody>
                        </table>                            


                              <!--
                              <table id="dataTableGas" class="table table-bordered table-striped">
                                <thead>
                                  <tr >
                                    <th>Id</th>  
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Factura</th>
                                    <th>Autorización</th>
                                    <th>Tipo</th>
                                    <th>Total</th>
                                    <th>Acción</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                              -->
                            </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.box-body -->
                    <div  align="center" class="box-footer">
                        
                    </div>
                </div>
              <!-- /.box -->
            </div>
           
        </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

