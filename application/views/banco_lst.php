<?php
/* ------------------------------------------------
  ARCHIVO: categoria.php
  DESCRIPCION: Contiene la vista principal del módulo de categoria.
  FECHA DE CREACIÓN: 06/07/2017
 * 
  ------------------------------------------------ */
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Bancos'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {

      $('#dataTableBan').dataTable({
        'language': { 'url': base_url + 'public/json/language.spanish.json' },
        'ajax': "Banco/listadoBanco",
        'columns': [
          {"data": "id"},
          {"data": "tipo"},
          {"data": "nombre"},
          {"data": "ver"}
        ]
      });

      $(document).on('click', '.add_ban', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: base_url + "Banco/add_ban" 
        });
      });   

      $(document).on('click', '.edi_ban', function(){
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
          href: base_url + "Banco/edi_ban" 
        });
      });        

      $(document).on('click', '.ban_save', function(){
        var idban = $("#txt_idban").val();
        var tipban = $("#cmb_tipo option:selected").val();
        var nomban = $("#txt_ban").val();
        $.ajax({
          type: "POST",
          dataType: "json",
          data: { idban: idban, tipban: tipban, nomban: nomban },                
          url: base_url + "Banco/sav_ban" ,
          success: function(json) {
           $('#dataTableBan').DataTable().ajax.reload();
          }
        });            
        $.fancybox.close();
      });

      $(document).on('click','.del_ban', function() {
        id = $(this).attr('id');
          if (conf_del()) {
            $.ajax({
              url: base_url + "Banco/del_ban",
              data: { id: id },
              type: 'POST',
              dataType: 'json',
              success: function(json) {
                $('#dataTableBan').DataTable().ajax.reload();
              }
            });
        }
        return false; 
      });


      function conf_del() {
          return  confirm("¿Confirma que desea eliminar este Banco?");
      }




 
    }); 

  
</script>

<div class="content-wrapper">
  <section class="content-header">
    <h1> <i class="fa fa-university"></i> Bancos </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>banco">Bancos</a></li>
      </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"></i> Datos del Banco</h3>
            <div class="pull-right"> 
            <button type="button" class="btn btn-danger btn-grad add_ban" > <i class="fa fa-plus-square"></i> Añadir </button>   
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-xs-2 "></div>
            <div class="col-xs-8">
              <div class="box-body">
                <table id="dataTableBan" class="table table-bordered table-striped">
                  <thead>
                    <tr >
                      <th>Id</th>
                      <th>Tipo</th>
                      <th>Nombre</th>
                      <th>Acción</th>
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


