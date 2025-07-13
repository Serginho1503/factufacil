<?php
/* ------------------------------------------------
  ARCHIVO: Gastos.php
  DESCRIPCION: Contiene la vista principal del módulo de Gastos.
  FECHA DE CREACIÓN: 30/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Movimientos de Inventario'</script>";
date_default_timezone_set("America/Guayaquil");


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

      /* AGREGAR MOVIMIENTO */
      $(document).on('click', '.mov_add', function(){

        location.replace("<?php print $base_url;?>inventario/agregarmovimiento");

      });


      /* ACTUALIZAR LISTADO DE GASTOS POR RAGO DE FECHA */
      $('.actualiza').click(function(){
        var hasta = $("#hasta").val();
        var desde = $("#desde").val();
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Inventario/tmp_docinv_fecha');?>",
          data: { hasta: hasta, desde: desde },
          success: function(json) {
            $('#dataTableInv').DataTable().ajax.reload();
          }
        }); 
      });   

       


      /* REPORTE */
      $('.reporte').click(function(){
      var hasta = $("#hasta").val();
      var desde = $("#desde").val();

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Gastos/tmp_gastos_fecha');?>",
          data: { hasta: hasta, desde: desde }
        }); 

        window.open('<?php print $base_url;?>gastos/reporte');
      
      });  


      /* CARGA DE DATOS EN EL DATATABLE */
    $('#dataTableInv').dataTable({
      'language': { 'url': base_url + 'public/json/language.spanish.json' },
      'ajax': "listadoDataMovimInventario",
      'columns': [
          {"data": "fecha"},           
          {"data": "documento"},
          {"data": "tipo"},
          {"data": "descripcion"},  
          {"data": "total"},  
          {"data": "ver"}
      ]
      });





    $(document).on('click', '.mov_imp', function(){
      var id = $(this).attr('id');
     
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Inventario/nromovinv_tmp",
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
                href: base_url + 'Inventario/recmovpdf' 
              });
            }
        });
    });      


}); 

</script>


<div class="content-wrapper">

    <section class="content-header">
      <h1>
        <i class="fa fa-money"></i> Movimientos de Inventario
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>Inventario/cargar_inventariomovimiento">Movimientos de Inventario</a></li>
        
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
   

                      <div class="pull-right"> 
                      <!--    <a class="btn bg-light-blue color-palette btn-grad reporte" href="#" data-original-title="" title=""><i class="fa fa-list-alt"></i> Reporte de Gastos </a>  -->                    
                          <button type="button" class="btn btn-success btn-grad mov_add" >
                            <i class="fa fa-plus-square"></i> Añadir
                          </button>                     
                      </div>
                    </div>
                    <div class="box-body">

                      <div class="row">

                        <div class="col-xs-12">
                        
                            <div id="upd_tabla" class="box-body table-responsive ">

                              <table id="dataTableInv" class="table table-bordered table-hover table-responsive">
                                  <thead>
                                        <tr>
                                            <th class="text-center col-md-1">Fecha</th>
                                            <th class="text-center col-md-1">Documento</th>
                                            <th class="text-center col-md-1">Tipo</th>
                                            <th class="text-left col-md-2">Descripcion</th>
                                            <th class="text-center col-md-1">Total</th>
                                            <th class="text-center col-md-1">Acción</th>
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
  

