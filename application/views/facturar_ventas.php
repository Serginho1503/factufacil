<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/css/jquery.timepicker.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/css/bootstrap-datepicker.standalone.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/lib/pikaday.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/lib/jquery.ptTimeSelect.css" />

<style>
  #contenido_venta{
      width: 850px;
  }
  
  #ui-datepicker-div{
    z-index: 9999999  !important;
  } 
  
  .continput{
    height: 34px;
  }     

  .datepicker{
    z-index: 9999999  !important;
  }   
</style>

<script src="<?php print $base_url; ?>assets/plugins/datepair/js/jquery.min.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/js/jquery.timepicker.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/js/bootstrap-datepicker.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/pikaday.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/jquery.ptTimeSelect.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/moment.min.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/site.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/dist/datepair.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/dist/jquery.datepair.js"></script>

<script type='text/javascript' language='javascript'>

 var jq = $.noConflict();
  jq(document).ready(function () {

    jq('#buscrango .time').timepicker({
        'showDuration': true,
        'timeFormat': 'H:i:s'
    });

    jq('#buscrango .date').datepicker({
        'format': 'dd/mm/yyyy',
        'autoclose': true
    });

    jq('#buscrango').datepair(); 

    var imprimepdf = <?php print $facturapdf; ?>;

      /* CARGA DE DATOS EN EL DATATABLE */
    tablevent=$('#dataTableVent').dataTable({
        rowCallback:function(row,data) {
        if(data["estatus"] != '1')
        {
          $($(row)).css("background-color","#DD4B39");
        }
        },  
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "listadoVend",
        'columns': [
         
            {"data": "fecha"},
            {"data": "factura"},
            {"data": "mesa"},
            {"data": "cliente"},  
            {"data": "monto"},  
            {"data": "ver"}
        ]
    });

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

  /* ACTUALIZAR LISTADO DE VENTA POR RAGO DE FECHA */
  $('.actualiza').click(function(){
    var fhasta = $("#fhasta").val();
    var fdesde = $("#fdesde").val();
    var horah = $("#hhasta").val();
    var horad = $("#hdesde").val(); 

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Facturar/tmp_venta_fecha');?>",
          data: { fdesde:fdesde, horad:horad, fhasta:fhasta, horah:horah }
        }).done(function (result) {

              $('#dataTableVent').DataTable().ajax.reload();
              actualiza_venta();
        }); 

  });

    function actualiza_venta(){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Facturar/upd_venta_total_usu",
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

    /* Boton del listado para imprimir compra 
    $(document).on('click', '.venta_print', function(){
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
                href: "<?php // echo base_url('Facturar/imprimirventa');?>" 
              });
    });*/
    $(document).on('click', '#imprimir', function(){
      var id = $(this).attr('name');
      alert(imprimepdf);
      if(imprimepdf == 1){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Facturar/nrofactura_tmp",
            data: { id: id },
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
                href: base_url + 'Facturar/facturapdf' 
              });
            }
        });
      }else{
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
            dataType: "html",
            type: "POST",
            data: {id: id}
          },
          href: "<?php echo base_url('Facturar/imprimirventa');?>" 
        });        
      }


    });

      /*    
    $(document).on('click', '.venta_print', function(){
      var id = $(this).attr('id');
      alert(id);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Facturar/nrofactura_tmp",
            data: { id: id },
            success: function(json) {
              if(imprimepdf == 1){
                $.fancybox.open({
                  type:'iframe',
                  width: 800,
                  height: 550,
                  ajax: {
                     dataType: "html",
                     type: "POST",
                     data: {id: id}
                  },
                  href: base_url + 'Facturar/facturapdf' 
                });
              }else{
                $.fancybox.open({
                  type: "ajax",
                  width: 550,
                  height: 550,
                  ajax: {
                    dataType: "html",
                    type: "POST",
                    data: {id: id}
                  },
                  href: "<?php echo base_url('Facturar/imprimirventa');?>" 
                });                 
              }
            }
        });

 
    });
      */ 



    $(document).on('click', '.venta_print', function(){
      var imprimepdf = <?php print $facturapdf; ?>;
      var id = $(this).attr('id');

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/tipdoc');?>",
        data: { id:id, },
        success: function(json) {
          var tipodoc = json;
          if(imprimepdf == 1){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: base_url + "Facturar/nrofactura_tmp",
                data: { id: id },
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
                    href: base_url + 'Facturar/facturapdf' 
                  });
                }
            });
          }else 
            if(imprimepdf == 0){
              $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                  dataType: "html",
                  type: "POST",
                  data: {id: id}
                },
                href: "<?php echo base_url('Facturar/imprimirventaticket');?>" 
              });        
            } else {
              if(tipodoc == 2){
                $.fancybox.open({
                  type: "ajax",
                  width: 550,
                  height: 550,
                  ajax: {
                    dataType: "html",
                    type: "POST",
                    data: {id: id}
                  },
                  href: "<?php echo base_url('Facturar/imprimirventa');?>" 
                });        
              
              }else{
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: base_url + "Facturar/nrofactura_tmp",
                    data: { id: id },
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
                        href: base_url + 'Facturar/facturapdf' 
                      });
                    }
                });
              }
            }
        }
      });
    });




    /* Reporte de Venta */
    $(document).on('click', '#rpt_venta', function(){    
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/tmp_venta_fecha');?>",
        data: { hasta: hasta, desde: desde },
        success: function(json) {
          window.open('<?php print $base_url;?>Facturar/reporte');
        }
      });    
    });


    /* ANULAR FACTURA */
    $(document).on('click', '.anu_fact', function(){
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
                href: "<?php echo base_url('Facturar/anular_factura');?>",
                 success: function(json) {
                  $.fancybox.close();
                 }
              });
    });

    /* EDITAR FACTURA */
    $(document).on('click', '.edi_fact', function(){
      var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {id: id},
            url: base_url + "Facturar/tmp_factura",
            success: function(json) {

              if(json.resu > 0){
                location.replace("<?php print $base_url;?>Facturar/editar_factura");
              }else{
                alert("ERROR.");
              }
            }
        });
    });

}); 

</script>

<div id = "contenido_venta" class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border" style="padding-top: 0px;">
              <div id="buscrango">
                <div class="form-group col-md-1 " style="margin-bottom: 0px; margin-top: 0px; padding-right:76px; margin-right:30px; padding-left:0px;">
                  <label for="">Desde</label>
                  <div class="input-group">
                    <input style="width:100px;" type="text" class="form-control text-center date start" id="fdesde" name="fdesde" value="<?php print  date("d/m/Y"); ?>">
                  </div>
                </div>              
                <div class="form-group col-md-1 " style="margin-bottom: 0px; margin-top: 0px; padding-left:0px; padding-right:76px; margin-right:30px;">              
                  <label for="">Hora</label>
                  <div class="input-group">
                    <input style="width:100px;" type="text" class="form-control text-center time start" id="hdesde" name="hdesde" value="00:00:00">
                  </div>
                </div> 
                <div class="form-group col-md-1 " style="margin-bottom: 0px; margin-top: 0px; padding-left:0px; padding-right:76px; margin-right:30px;">
                  <label for="">hasta</label>
                  <div class="input-group">
                    <input style="width:100px;" type="text" class="form-control text-center date end" id="fhasta" name="fhasta" value="<?php print  date("d/m/Y"); ?>">
                  </div>
                </div>              
                <div class="form-group col-md-1 " style="margin-bottom: 0px; margin-top: 0px; padding-left:0px; padding-right:76px; margin-right:30px;">              
                  <label for="">Hora</label>
                  <div class="input-group">
                    <input style="width:100px;" type="text" class="form-control text-center time end" id="hhasta" name="hhasta" value="23:59:59">
                  </div>
                </div> 
                <div class="col-md-1" style="margin-bottom: 0px; margin-top: 12px; padding-left:0px; padding-right:76px; width: 50px;">
                  <button type="button" class="btn btn-block btn-success actualiza" style="width: 40px; height: 33px;"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="col-md-3" style="margin-bottom: 0px; margin-top: 17px;">
                <h4 style="margin-bottom: 0px; margin-top: 0px;"><div id="monto"> Monto: <?php print number_format(@$monto,2,",","."); ?></div></h4>
              </div>  
        </div>

        <div class="box-body">
            <div class="row">
                <div id="upd_tbventa" class="box-body table-responsive">
                    <table id="dataTableVent" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr >
                            <th class="text-center col-md-1">Fecha</th>  
                            <th class="text-center col-md-1">Factura</th>
                            <th class="text-center col-md-1">Punto</th>
                            <th>Cliente</th>
                            <th class="text-center col-md-1">Monto</th>
                            <th class="text-center col-md-1">Acción</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div  align="center" class="box-footer">

        </div>
    </div>
</div>