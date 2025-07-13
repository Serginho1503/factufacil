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
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url('contabilidad/contab_comprobante/tmp_cmp_fecha');?>",
                data: { hasta: hasta, desde: desde, sucursal: sucursal }
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
          'ajax': "contabilidad/contab_comprobante/listadoComprobantes",
          'columns': [
              {"data": "ver"},
              {"data": "fecha"},
              {"data": "numero"},
              {"data": "referencia"},
              {"data": "monto"},
              {"data": "estado"},
              {"data": "nom_sucursal"},
              {"data": "tipocomprobante"},
              {"data": "descripcion"}
          ]
      });

      /* AGREGAR  */
      $(document).on('click', '.add_cmp', function(){
        location.replace("<?php print $base_url;?>contabilidad/contab_comprobante/add_comprobante");        
      });

      /* EDITAR  */
      $(document).on('click', '.edi_cmp', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('contabilidad/contab_comprobante/tmp_comprobante');?>",
           data: {id: id},
           success: function(json) {
             location.replace("<?php print $base_url;?>contabilidad/contab_comprobante/edit_comprobante");        
           }
        });
      })

      /* PDF  */
      $(document).on('click', '.print_cmp', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('contabilidad/contab_comprobante/tmp_comprobante');?>",
           data: {id: id},
           success: function(json) {
             $.fancybox.open({
                type:'iframe',
                width: 800,
                height: 550,
                ajax: {
                    dataType: "html",
                    type: "POST",
                    data: {id: id}
                },
                href: base_url + 'contabilidad/contab_comprobante/comprobantepdf' 
              });
           }
        });
      })

      /* ELIMINAR */
      $(document).on('click', '.del_cmp', function(){
          id = $(this).attr('id');
          if (confirm("Desea eliminar el comprobante")){
            $.ajax({
              type: "POST",
              dataType: "json",
              url: "<?php echo base_url('contabilidad/contab_comprobante/del_comprobante');?>",
              data: {id: id},
              success: function(json) {
                if (json.mens == 0){
                  alert("No se pudo eliminar el comprobante. Existe informacion asociada.");
                }  
                else{
                  $('#dataTableObj').DataTable().ajax.reload();
                }
              }
            });
          }  
      })

      /* Confirmar */
      $(document).on('click', '.conf_cmp', function(){
          id = $(this).attr('id');
          if (confirm("Desea confirmar el asiento?")){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url('contabilidad/contab_comprobante/confirmar_comprobante');?>",
                data: {id: id},
                success: function(json) {
                  if (json.mens == 0){
                    alert("No se pudo confirmar el asiento. Verifique débitos y créditos.");
                  }  
                  else{
                    $('#dataTableObj').DataTable().ajax.reload();
                  }
                }
            });
          } 
      })

      /* Confirmar todos los pendientes */
      $(document).on('click', '.allconf_cmp', function(){
        if (confirm("Desea confirmar los asientos pendientes?")){
          var hasta = $("#hasta").val();
          var desde = $("#desde").val();
          var sucursal = $("#cmb_sucursal").val();
          $.ajax({
              type: "POST",
              dataType: "json",
              url: "<?php echo base_url('contabilidad/contab_comprobante/tmp_cmp_fecha');?>",
              data: { hasta: hasta, desde: desde, sucursal: sucursal },
              success: function(json) {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?php echo base_url('contabilidad/contab_comprobante/confirmar_cmp_rango');?>",
                    success: function(json) {
                      if (json.mens == 0){
                        if (json.cmp == ''){
                          alert("No se pudo confirmar los asientos.");
                        }
                        else{
                          alert("No se pudo confirmar el asiento " + json.cmp + ". Verifique débitos y créditos.");
                        }
                      }  
                      else{
                        $('#dataTableObj').DataTable().ajax.reload();
                        alert("Asientos confirmados exitosamente.");
                      }
                    }
                });
              }
          }); 
        }    
      })

      /* Anular */
      $(document).on('click', '.null_cmp', function(){
          id = $(this).attr('id');
          if (confirm("Desea anular el comprobante?")){
            $.fancybox.open({
              type: "ajax",
              width: 550,
              height: 550,
              ajax: {
                dataType: "html",
                type: "POST",
                data: {id: id}
              },
              href: "<?php echo base_url('contabilidad/contab_comprobante/frm_anula_comprobante');?>",
              afterClose: function(){
                $('#dataTableObj').DataTable().ajax.reload();
              } 
            });
          } 
      })
      $(document).on('click', '.null_cmp00', function(){
          id = $(this).attr('id');
          if (confirm("Desea anular el comprobante?")){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url('contabilidad/contab_comprobante/anular_comprobante');?>",
                data: {id: id},
                success: function(json) {
                  if (json.mens == 0){
                    alert("No se pudo confirmar el comprobante. Verifique débitos y créditos.");
                  }  
                  else{
                    $('#dataTableObj').DataTable().ajax.reload();
                  }
                }
            });
          } 
      })

      $(document).on('click', '.gen_cmp', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
            dataType: "html",
            type: "POST"
          },
          href: "<?php echo base_url('contabilidad/contab_comprobante/frm_genera_comprobante');?>",
          afterClose: function(){
            $('#dataTableObj').DataTable().ajax.reload();
          } 
        });
      });

 });

</script>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-columns"></i>
        Registro de Asientos      
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

               <div class="form-group col-md-8">

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


                <div class="form-group col-md-4" style="margin-bottom: 0px; margin-top: 0px; padding: 0px;">
                <label class="col-sm-3 control-label text-left" style="padding-left: 0px;">Desde</label>
                <div class="input-group date col-sm-7">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" style="padding-right: 0px;" class="form-control pull-right " id="desde" name="desde" value="<?php if(@$tmpdesde != NULL){ @$fec = str_replace('-', '/', @$tmpdesde); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} ?>">
                </div>
                </div> 

                <div class="form-group col-md-4" style="margin-bottom: 0px; margin-top: 0px; padding: 0px;">
                <label class="col-sm-3 control-label text-left" style="padding-left: 0px;">Hasta</label>
                <div class="input-group date col-sm-9">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" style="padding-right: 0px;" class="form-control" id="hasta" name="hasta" value="<?php if(@$tmphasta != NULL){ @$fec = str_replace('-', '/', @$tmphasta); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} ?>">
                    <span class="input-group-btn">
                    <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                    </span>
                </div>
                </div>  

                </div>

                <div class="pull-right"> 
                <a class="btn btn-success btn-grad add_cmp" href="#" data-original-title="" title="Añadir Asiento Manual"><i class="fa fa-plus-square"></i> Añadir </a>
                <a class="btn btn-info btn-grad gen_cmp" href="#" data-original-title="" title="Generar Asiento Automático"><i class="fa fa-plus-square"></i> Generar </a>
                <a class="btn btn-info btn-grad allconf_cmp" href="#" data-original-title="" title="Confirmar Asientos Pendientes"><i class="fa fa-lock"></i> Confirmar </a>
                </div>
                <!-- <hr style="margin-bottom: 0"> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="dataTableObj" class="table table-bordered table-striped table-responsive">
                <thead>
                  <tr >
                      <th>Acción</th> 
                      <th>Fecha</th>
                      <th>Número</th>
                      <th>Referencia</th>
                      <th>Monto</th>
                      <th>Estado</th>
                      <th>Sucursal</th>
                      <th>Tipo</th>
                      <th>Descripción</th>
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
