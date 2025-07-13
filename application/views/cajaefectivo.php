<?php
/* ------------------------------------------------
  ARCHIVO: puntoemision.php
  DESCRIPCION: Contiene la vista principal del módulo de puntoemision.
  FECHA DE CREACIÓN: 06/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
  print "<script>document.title = '$nombresistema - Listado de Cajas de Efectivo'</script>";
  date_default_timezone_set("America/Guayaquil");

?>

<style type="text/css">

</style>

<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

    $('#TableCajaEfect').dataTable({
      'language': { 'url': base_url + 'public/json/language.spanish.json' },
        'ajax': "Cajaefectivo/listadoCajas",
        'columns': [
            {"data": "sucursal"},
            {"data": "codigo"},   
            {"data": "caja"},
            {"data": "estatus"},
            {"data": "ver"}                            
        ]
    });


    $(document).on('click', '.edi_caja', function(){
      id = $(this).attr('id');
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Cajaefectivo/tmp_cajaefectivo');?>",
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
            href: "<?php echo base_url('Cajaefectivo/edi_cajaefectivo');?>",
            afterClose: function(){
              $('#TableCajaEfect').DataTable().ajax.reload();
            }
          });
        }
      });
    });  


    $(document).on('click', '.cajaefectivo_add', function(){
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        ajax: {
           dataType: "html",
           type: "POST"
        },
        href: "<?php echo base_url('Cajaefectivo/add_cajaefectivo');?>",
        afterClose: function(){
          $('#TableCajaEfect').DataTable().ajax.reload();
        } 
      });
    });

    $(document).on('click','.del_caja', function() {
      id = $(this).attr('id');
        if (conf_del()) {
          $.ajax({
            url: base_url + "Cajaefectivo/del_cajaefectivo",
            data: { id: id },
            type: 'POST',
            dataType: 'json',
            success: function(json) {
              if (json.mens == 1){
                $('#TableCajaEfect').DataTable().ajax.reload();
              } else {
                alert("No se pudo eliminar la caja. Existe informacion asociada.");
                return false;                
              }  

            }
          });
      }
      return false; 
    });


    function conf_del() {
        return  confirm("¿Confirma que desea eliminar esta Caja?");
    }

    $(document).on('change','#cmb_sucursal', function(){
        var idsuc = $('#cmb_sucursal option:selected').val(); 
            $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('Cajaefectivo/upd_puntoemision_id');?>",
            data: { idsuc: idsuc },
            success: function(json) {
                $('#cmb_puntoemision').empty();
                json.forEach(function(json){
                    $('#cmb_puntoemision').append('<option value="'+json.id+'">'+json.codigo+'</option>');
                });
            }
      });
      return false;
    }); 

    $(document).on("submit", "#frmcajaefectivo", function() {
      var data = $(this).serialize();
      $.ajax({
        url: $(this).attr("action"),
        data: data,
        type: 'POST',
        dataType: 'json',
        success: function(json) {
          $('#TableCajaEfect').DataTable().ajax.reload();
          $.fancybox.close();
        }
      });
      return false;
    });



  }); 


</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-calculator"></i> Lista de Caja Efectivo 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>cajaefectivo">Caja Efectivo</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Datos de la Caja</h3>
                      <div class="pull-right"> 

                          <button type="button" class="btn btn-success btn-grad cajaefectivo_add" >
                            <i class="fa fa-plus-square"></i> Añadir
                          </button>   

                       
                    </div>
                    </div>
                    <div class="box-body">

                      <div class="row">
                        <div class="col-xs-12">
                            <div class="box-body table-responsive">
                              <table id="TableCajaEfect" class="table table-bordered table-striped table-responsive">
                                <thead>
                                  <tr >
                                    <th>Sucursal</th>
                                    <th>Codigo</th>
                                    <th>Caja</th>
                                    <th>Estatus</th>
                                    <th>Accion</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
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

