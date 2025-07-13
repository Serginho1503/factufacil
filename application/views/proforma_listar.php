<?php
/* ------------------------------------------------
  ARCHIVO: Ventas.php
  DESCRIPCION: Contiene la vista principal del módulo de Ventas.
  FECHA DE CREACIÓN: 28/08/2017
 * 
  ------------------------------------------------ */
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = $nombresistema - Proformas'</script>";
date_default_timezone_set("America/Guayaquil");

?>
<style type="text/css">


  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 

</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {


    $(document).on('click', '#rpt_proforma', function(){  
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();    
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Proforma/tmp_prof_fecha');?>",
        data: { desde:desde, hasta:hasta },
        success: function(json) {
          window.open('<?php print $base_url;?>Proforma/reporte');
        }
      });    
    });








    $('#dataTableProf').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "Proforma/listadoProforma",
        'columns': [
            
            {"data": "ver"},
            {"data": "fecha"},
            {"data": "proforma"},
            {"data": "cliente"},  
            {"data": "monto"},  
            {"data": "vendedor"}
          
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

  $(document).on('click', '.edi_prof', function(){
    var id = $(this).attr('id');
      $.ajax({
          type: "POST",
          dataType: "json",
          data: {id: id},
          url: base_url + "Proforma/tmp_proforma",
          success: function(json) {
            if(json.resu > 0){
              location.replace("<?php print $base_url;?>Proforma/editar_proforma");
            }else{
              alert("ERROR.");
            }
          }
      });
  });

    /* Boton del listado para imprimir proforma
    $(document).on('click', '.pro_imp', function(){
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
                href: "<?php // echo base_url('Proforma/imprimirproforma');?>" 
              });
    });
 */

    $(document).on('click', '.pro_imp', function(){

      var imprimepdf = <?php print $facturapdf; ?>;
      if (imprimepdf == '') {imprimepdf = 0;}
     /* imprimepdf = 1;*/

      var id = $(this).attr('id');

        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Proforma/nroproforma_tmp",
            data: { id: id },
            success: function(json) {
              if (imprimepdf == 1){
                $.fancybox.open({
                  type:'iframe',
                  width: 800,
                  height: 600,
                  ajax: {
                     dataType: "html",
                     type: "POST",
                     data: {id: id}
                  },
                  href: base_url + 'Proforma/proformapdf' 
                });
              }
              else{
                $.fancybox.open({
                  type:'iframe',
                  width: 800,
                  height: 600,
                  ajax: {
                     dataType: "html",
                     type: "POST",
                     data: {id: id}
                  },
                  href: base_url + 'Proforma/proformapdf' 
                });
              }  
            }
        });
    });






  $('.actualiza').click(function(){
    var hasta = $("#hasta").val();
    var desde = $("#desde").val();
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "<?php echo base_url('Proforma/tmp_prof_fecha');?>",
      data: { hasta: hasta, desde: desde }
    }).done(function (result) {
          $('#dataTableProf').DataTable().ajax.reload();
    }); 
  });

  
  /* ELIMINAR */
  $(document).on('click', '.pro_del', function(){
      id = $(this).attr('id');
      fact = $(this).attr('name');
      if (fact != 0){
        alert("No es posible eliminar. La proforma ha sido facturada.");
      } else {
        $.ajax({
         type: "POST",
         dataType: "json",
         url: "<?php echo base_url('proforma/tmp_proforma');?>",
         data: {id: id},
         success: function(json) {
            $.fancybox.open({
              type: "ajax",
              width: 550,
              height: 550,
              ajax: {
                 dataType: "html",
                 type: "POST"
              },
              href: "<?php echo base_url('proforma/del_pro');?>" 
            });
         }       
        }).done(function (result) {
            $('#dataTableProf').DataTable().ajax.reload();
          }); 
    }    
  })

  /* FACTURAR */
  $(document).on('click', '.pro_fac', function(){
      id = $(this).attr('id');
      fact = $(this).attr('name');
      if (fact != 0){
        alert("La proforma ya ha sido facturada.");
      } else {

        $.ajax({
            type: "POST",
            dataType: "json",
            data: {id: id},
            url: base_url + "Proforma/facturar",
            success: function(json) {
              if(json.resu > 0){
                location.replace("<?php print $base_url;?>Facturar/factura_deposito");
              }else{
                alert("No se pudo facturar. Revise los permisos.");
              }
            }
        });

      }    
  })

}); 



</script>


<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-file-powerpoint-o"></i> Listado de Proformas
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>proforma">Proformas</a></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-danger">

          <div class="box-header with-border">
            <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
              <label for="">Desde</label>
              <div class="input-group date col-sm-7">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" class="form-control pull-right validate[required]" id="desde" name="desde" value="<?php print date("d/m/Y",strtotime($desde)); ?>">
              </div>
            </div> 
            <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
              <label for="">Hasta</label>
              <div class="input-group date col-sm-10">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" class="form-control pull-right validate[required]" id="hasta" name="hasta" value="<?php print date("d/m/Y",strtotime($hasta)); ?>">
                <span class="input-group-btn">
                <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                </span>
              </div>
            </div>  
            <div class="pull-right"> 
              <a id="rpt_proforma" class="btn bg-blue-active color-palette btn-grad" style="margin-bottom: 0px; margin-top: 23px;" href="#" data-original-title="" title=""><i class="fa fa-file-text-o" aria-hidden="true"></i> Reporte de Proformas </a>
              <a id="add_proforma" class="btn bg-green-active color-palette btn-grad" style="margin-bottom: 0px; margin-top: 23px;" href="<?php print base_url()."Proforma/agregar/"; ?>" data-original-title="" title=""><i class="fa fa-plus-square" aria-hidden="true"></i> Añadir Proforma </a>
            </div>            
          </div>

          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div id="upd_tbventa" class="box-body table-responsive">

                    <table id="dataTableProf" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr >

                            <th class="text-center col-md-1">Acción</th> 
                            <th class="text-center col-md-1">Fecha</th>  
                            <th class="text-center col-md-1">Nro Proforma</th>
                            <th>Cliente</th>
                            <th class="text-center col-md-1">Monto</th>
                            <th>Vendedor</th>                            
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                 

                  </div>
                  <!-- /.box-body -->
                </div>
              </div>
            </div>
          </div>


          <div   align="center" class="box-footer">
            <hr class="linea"> 
              <div class="row" style="margin-top:20px">



              </div><!--/row-->



          </div>
        </div>

      </div>           
    </div>
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

