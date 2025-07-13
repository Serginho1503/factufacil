<?php
/*
  FUNCION QUE PERMITE CONECTAR EL DATATABLE CON LA BASE DE DATOS
*/
?>
  <script type='text/javascript' language='javascript'>
    $(document).ready(function () {

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

        $('.actualiza').click(function(){
            var hasta = $("#hasta").val();
            var desde = $("#desde").val();
            var sucursal = $("#cmb_sucursal").val();
            var pendiente = $("#pendiente").prop('checked');
            if (pendiente == true) {pendiente = 1;} else{pendiente=0;}
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url('contabilidad/contab_balance/tmp_balsum_fecha');?>",
                data: { hasta: hasta, desde: desde, sucursal: sucursal, pendiente: pendiente }
            }).done(function (result) {
                $('#dataTableObj').DataTable().ajax.reload();
            }); 
        });

      var table = $('#dataTableObj').dataTable({
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
          'ajax': "contabilidad/contab_balance/listadoBalancesumasaldo",
          'columns': [
              {"data": "cuenta"},
              {"data": "descripcion"},
              {"data": "saldoanterior"},
              {"data": "debito"},
              {"data": "credito"},
              {"data": "saldo"}
          ]
      });


 });

</script>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-balance-scale"></i>
        Balance de Sumas y Saldos      
      </h1>
      <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header with-border">
              <!-- <h3 class="box-title">Listado de Comprobantes</h3> -->

                <!-- SUCURSAL  -->
                <div style="" class="form-group col-md-3">
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
                </div>


                <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
                <label class="col-sm-3 control-label text-left" style="padding-left: 0px;">Desde</label>
                <div class="input-group date col-sm-7">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" class="form-control pull-right " id="desde" name="desde" value="<?php if(@$tmpdesde != NULL){ @$fec = str_replace('-', '/', @$tmpdesde); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} ?>">
                </div>
                </div> 

                <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
                <label class="col-sm-3 control-label text-left" style="padding-left: 0px;">Hasta</label>
                <div class="input-group date col-sm-9">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" class="form-control pull-right " id="hasta" name="hasta" value="<?php if(@$tmphasta != NULL){ @$fec = str_replace('-', '/', @$tmphasta); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} ?>">
                    <span class="input-group-btn">
                    <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                    </span>
                </div>
                </div>  

                <div class="pull-right"> 
                    <a class="btn btn-success color-palette btn-grad pull-right" target="_blank" href="<?php print $base_url;?>contabilidad/contab_balance/reportesumasaldoXLS" data-original-title="" title=""><i class="fa fa-file-excel-o fa-1x"></i> Exportar </a>
                </div>

                <div class="col-md-12">                             
                    <input id="pendiente" type="checkbox" <?php if(@$tmppendiente != NULL){ if($tmppendiente == 1) {print 'checked';}} ?> > Incluir Asientos Pendientes 
                </div>  

                <!-- <hr style="margin-bottom: 0"> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="dataTableObj" class="table table-bordered table-striped table-responsive">
                <thead>
                  <tr >
                      <th>Cuenta</th>
                      <th>Descripción</th>
                      <th>Saldo Anterior</th>
                      <th>Débito</th>
                      <th>Crédito</th>
                      <th>Saldo</th>
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
