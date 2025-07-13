<?php
/*
  FUNCION QUE PERMITE CONECTAR EL DATATABLE CON LA BASE DE DATOS
*/
?>
  <script type='text/javascript' language='javascript'>
    $(document).ready(function () {
       $('#dataTableObj')
       .on( 'init.dt', function () {
            actualiza_controles();
            //console.log( 'Table initialisation complete: '+new Date().getTime() );
        } )
       .dataTable({
          'language': {
                'url': base_url + 'public/json/language.spanish.json'
          },          
          'ajax': "contabilidad/contab_comprobante/listadoTipocmpsucursal",
          'columns': [
              {"data": "nombre"},
              {"data": "abreviatura"},
              {"data": "prefijo"},
              {"data": "contador"}
          ]
      });

      $('#dataTableObj').on('xhr.dt', function ( e, settings, json, xhr ) {
        //actualiza_controles();
        //initCompleteFunction(settings, json);
        } );

      $(document).on('change', '#cmb_sucursal', function(){
        id = $(this).val();

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('contabilidad/contab_comprobante/tmp_cfgcmp_sucursal');?>",
            data: {id: id},
            success: function(json) {
                //$('#dataTableObj').DataTable().ajax.reload();
                location.reload();
            }
        });
      })

      $(document).on('click', '.edit_cfg', function(){
        edicion = 1;
        actualiza_controles();
      });

      $(document).on('click', '.save_cfg', function(){
        lista = [];  
        var inputs=document.getElementsByClassName('upd_contador');
        for(i=0;i<inputs.length;i++){
            lista.push({'id' : inputs[i].id, 'contador' : inputs[i].value});
        }    
        //console.log(lista);
        var automatico = $("#automatico").prop('checked');
        if (automatico == true) {automatico = 1;} else{automatico=0;}

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('contabilidad/contab_comprobante/upd_tipocmp_sucursal');?>",
            data: {lista: lista, automatico: automatico},
            success: function(json) {
                edicion = 0;
                actualiza_controles();
            }
        });

      });

      function actualiza_controles(){
        //alert('paso');
        var inputs=document.getElementsByClassName('upd_contador');
        for(i=0;i<inputs.length;i++){
            //alert(i);
            inputs[i].disabled='';
            if (edicion == 0){
                inputs[i].disabled='disabled';
            }            
        }    
        $('#automatico').prop('disabled', (edicion == 0));
        if (edicion == 0){
            $('.edit_cfg').show();
            $('.save_cfg').hide();
        }
        else{
            $('.edit_cfg').hide();
            $('.save_cfg').show();
        }
      }

      var edicion = 0;
      contabauto = $("#cmb_sucursal option:selected").attr('name');
      $('#automatico').prop('checked', (contabauto == 1));

 });

</script>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-calendar"></i>
        Configuración Contable de Sucursal      
      </h1>
      <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-danger">
          <div class="box-body">
<!--            <div class="box-header">
              <h3 class="box-title">Listado </h3>
            </div>
             /.box-header -->

            <!-- SUCURSAL  -->
            <div style="" class="form-group col-md-4">
              <div class="col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                <label for="lb_res">Sucursal</label>
                </div> 
                <div class="col-md-9">
                <select id="cmb_sucursal" name="cmb_sucursal" class="form-control ">
                <?php 
                    if (count($sucursales) > 0) {
                        foreach ($sucursales as $obj):
                            if(@$tmpsucursal != NULL){
                                if($obj->id_sucursal == $tmpsucursal){ ?>
                                    <option value="<?php  print $obj->id_sucursal; ?>" selected="TRUE" name="<?php  print $obj->contabilizacion_automatica; ?>"> <?php  print $obj->nom_sucursal; ?> </option>
                                    <?php
                                }else{ ?>
                                    <option value="<?php  print $obj->id_sucursal; ?>" name="<?php  print $obj->contabilizacion_automatica; ?>"> <?php  print $obj->nom_sucursal; ?> </option>
                                    <?php
                                }
                            }else{ ?>
                                <option value="<?php  print $obj->id_sucursal; ?>" name="<?php  print $obj->contabilizacion_automatica; ?>"> <?php  print $obj->nom_sucursal; ?> </option>
                                <?php
                                }   ?>
                            <?php
                        endforeach;
                    }
                    ?>
                </select>          
              </div>    
            </div> 

            <div class="col-md-4 col-md-offset-1">                             
                <input id="automatico" type="checkbox"> Contabilización Automática de Documentos
            </div>  


            <div class="pull-right"> 
                <a class="btn btn-info btn-sm btn-grad edit_cfg" href="#" data-original-title="" title="Añadir Asiento Manual"><i class="fa fa-pencil-square-o"></i> Editar </a>
                <a class="btn btn-success btn-sm btn-grad save_cfg" href="#" data-original-title="" title="Generar Asiento Automático"><i class="fa fa-save"></i> Guardar </a>
            </div>

          </div> 
          </div> 
        </div> 
      </div> 
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-danger">

            <div class="box-header">
              <h3 class="box-title">Tipos de Comprobante </h3>
            </div>

            <div class="box-body table-responsive">
              <table id="dataTableObj" class="table table-bordered table-striped table-responsive">
                <thead>
                  <tr >
                      <th>Nombre</th>
                      <th>Abreviatura</th>
                      <th>Prefijo</th>
                      <th>Contador</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
