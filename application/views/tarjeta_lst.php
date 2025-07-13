<?php
/* ------------------------------------------------
  ARCHIVO: categoria.php
  DESCRIPCION: Contiene la vista principal del módulo de categoria.
  FECHA DE CREACIÓN: 06/07/2017
 * 
  ------------------------------------------------ */
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Tarjetas'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {

      $('#dataTableTar').dataTable({
        'language': { 'url': base_url + 'public/json/language.spanish.json' },
        'ajax': "Tarjeta/listadoTarjeta",
        'columns': [
          {"data": "ver"},
          {"data": "nombre"},
          {"data": "debito"},
          {"data": "credito"}
        ]
      });

      $(document).on('click', '.add_tar', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: base_url + "Tarjeta/add_tar" 
        });
      });   

      $(document).on('click', '.edi_tar', function(){
        id = $(this).attr('id');
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST",
             data: {id: id}
          },
          href: base_url + "Tarjeta/edi_tar" 
        });
      });        

      $(document).on('click', '.tar_save', function(){
        var idtar = $("#txt_idtar").val();
        var nomtar = $("#txt_tar").val();
        var comdebito = $("#txt_comisiondebito").val();
        var comcredito = $("#txt_comisioncredito").val();
        $.ajax({
          type: "POST",
          dataType: "json",
          data: { idtar: idtar, nomtar: nomtar, comdebito: comdebito, comcredito: comcredito },                
          url: base_url + "Tarjeta/sav_tar" ,
          success: function(json) {
           $('#dataTableTar').DataTable().ajax.reload();
          }
        });            
        $.fancybox.close();
      });

      $(document).on('click','.del_tar', function() {
        id = $(this).attr('id');
          if (conf_del()) {
            $.ajax({
              url: base_url + "Tarjeta/del_tar",
              data: { id: id },
              type: 'POST',
              dataType: 'json',
              success: function(json) {
                $('#dataTableTar').DataTable().ajax.reload();
              }
            });
        }
        return false; 
      });


      function conf_del() {
          return  confirm("¿Confirma que desea eliminar esta Tarjeta?");
      }




 
    }); 

  
</script>

<div class="content-wrapper">
  <section class="content-header">
    <h1> <i class="fa fa-credit-card"></i> Tarjetas </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"></i> Datos de la Tarjeta</h3>
            <div class="pull-right"> 
            <button type="button" class="btn btn-warning btn-grad add_tar" > <i class="fa fa-plus-square"></i> Añadir </button>   
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-xs-2 "></div>
            <div class="col-xs-8">
              <div class="box-body">
                <table id="dataTableTar" class="table table-bordered table-striped">
                  <thead>
                    <tr >
                      <th>Acción</th>
                      <th>Nombre</th>
                      <th>Comisión Débito</th>
                      <th>Comisión Crédito</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-xs-2 ">
            </div>
          </div>
        </div>
        <div  align="center" class="box-footer"></div>
      </div>
    </div>
  </section>
</div>


