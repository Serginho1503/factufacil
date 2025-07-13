<?php
/*
  FUNCION QUE PERMITE CONECTAR EL DATATABLE CON LA BASE DE DATOS
*/
?>
  <script type='text/javascript' language='javascript'>
    $(document).ready(function () {
       $('#dataTableCli').dataTable({
          "language":{  'url': base_url + 'public/json/language.spanish.json' },
          'ajax': "Cliente/listadoDataCli",
          'columns': [
              {"data": "ver"},
              {"data": "ident"},
              {"data": "tipo"},
              {"data": "nombre"},
              {"data": "ciudad"}
          ]
      });

      /* AGREGAR CLIENTE */
      $(document).on('click', '.add_cli', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('cliente/add_cli');?>" 
        });
      });

      $(document).on('click', '.cli_correo', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('cliente/cli_correo');?>" 
        });
      });


      /* EDITAR CLIENTE */
      $(document).on('click', '.edi_cli', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('cliente/tmp_cli');?>",
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
                href: "<?php echo base_url('cliente/edi_cli');?>" 
              });
           }
        });
      })

      /* ELIMINAR CLIENTE*/
      $(document).on('click', '.del_cli', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('cliente/tmp_cli');?>",
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
                href: "<?php echo base_url('cliente/del_cli');?>" 
              });
           }
        });
      })


      $(document).on('click', '.btnguardarcliente', function(){ 
        var idcliente = $("#txt_nro_ident").val();    
        var id = $("#txt_idcli").val();

        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Cliente/existeIdentificacion",
            data: { id: id, identificacion: idcliente },
            success: function(json) {
              if (json.resu == 0){
                var data = $(this).serialize();
                /*
                var cmb_tip_ide = $('#cmb_tip_ide option:selected').val();      
                var txt_nivel = $("#txt_nivel").val();
                var txt_nom = $("#txt_nom").val();
                var txt_mail = $("#txt_mail").val();
                var txt_telf = $("#txt_telf").val();
                var txt_ciu = $("#txt_ciu").val();
                var txt_ref = $("#txt_ref").val();
                var txt_dir = $("#txt_dir").val();
                var chk_rel = $("#chk_rel").val();
                var chk_may = $("#chk_may").val();
                var cmb_precio = $('#cmb_precio option:selected').val();  
                */

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: base_url + "Cliente/guardar",
                    data: data,
                    /*
                    data: { txt_idcli: id, txt_nro_ident: idcliente,
                            cmb_tip_ide: cmb_tip_ide, txt_nivel: txt_nivel, txt_nom: txt_nom,
                            txt_mail: txt_mail, txt_telf: txt_telf, txt_ciu: txt_ciu,
                            txt_ref: txt_ref, txt_dir: txt_dir, chk_rel: chk_rel, 
                            chk_may: chk_may, cmb_precio: cmb_precio
                          },
                          */
                    success: function(json) {
                      $.fancybox.close();
                      location.replace("<?php print $base_url ?>cliente");
                    }
                });

              } else {
                  alert("El numero de identificación ya esta registrado para otro cliente");
                  $('#txt_nro_ident').focus();
                  return false;
              } 
            }
        });
      });  


        $(document).on("submit", "#formCLI", function() {
            var id = $(this).attr("name");
            var data = $(this).serialize();
                $.ajax({
                    url: $(this).attr("action"),
                    data: data,
                    type: 'POST',
                    dataType: 'json',
                    success: function(json) {
                      $('#dataTableCli').DataTable().ajax.reload();
                      $.fancybox.close();
                    }
                });
            return false;
        });

        $(document).on("click", ".enviarcorreo", function() {
          $('input[type=checkbox]:checked').each(function() {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                dataType: "json",
                url: base_url + "Cliente/reporteprecioproproductoXLS",
                data: {id: id},
                success: function(json) {
                  $.fancybox.close();
                  $.blockUI({ message: '<h1> Enviando Correo a: '+ json.cliente +'...</h1>' });
                  $.ajax({
                      type: "POST",
                      dataType: "json",
                      url: base_url + "Cliente/correoenviar",
                      data: { ruta: json.ruta, correo: json.correo },
                      success: function(json) {
                          $.unblockUI();
                          if(json == 1){
                              alert('El Correo fue Enviado');
                          }else{
                              alert('Error al enviar El Correo'); 
                          }
                      }
                  });






                }
            });
          });

        });





 

 });

</script>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Clientes
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="<?php print $base_url ?>cliente">Clientes</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content" id="app_cliente_categoria">
      <div class="row" v-if="!mostrar_categorias">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Listado de Clientes</h3>
                <div class="pull-right"> 
                  <a class="btn btn-success btn-grad add_cli" href="#" data-original-title="" title=""><i class="fa fa-users"></i> Añadir </a>
                </div>
                <div class="pull-right" style="margin-right: 5px;"> 
                  <a class="btn bg-orange btn-flat cli_correo" href="#" data-original-title="" title=""><i class="fa fa-envelope"></i> Envio Correo </a>

                  <a class="btn btn-info color-palette btn-grad" v-on:click="activar_categorias()" title="Categorías de Venta"><i class="fa fa-shopping-bag"></i> Categorías </a>
              
                </div>
                <hr style="margin-bottom: 0">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="dataTableCli" class="table table-bordered table-striped">
                <thead>
                  <tr >
                      <th>Ver</th> 
                      <th>Identificación</th>
                      <th>Tipo.ID.</th>
                      <th>Nombre del Cliente</th>
                      <th>Ciudad</th>    
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

      <div class="row" v-if="mostrar_categorias">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Listado de Categorías de Venta</h3>

                <div class="pull-right" style="margin-right: 5px;"> 
                  <a class="btn bg-orange color-palette btn-grad" title="Añadir Categoría" v-on:click="adicionar_categoria()"><i class="fa fa-plus-square"></i> Añadir </a>             
                  <a class="btn btn-info color-palette btn-grad" v-on:click="cerrar_categorias()" ><i class="fa fa-exit"></i> Salir </a>
              
                </div>

                <div class="box-body table-responsive">

                  <table class="table table-bordered table-hover table-responsive">
                    <thead>
                      <tr >
                          <th class="text-center col-md-1">Acción</th>                            
                          <th class="text-center col-md-1">Categoría</th>  
                          <th class="text-center col-md-1">Monto Mínimo</th>
                      </tr>
                    </thead>

                    <editar-categoria
                          v-bind:value="categoria_seleccionada"
                          v-on:categoria_actualizada="cargar_categoriaclientes()"
                        >  
                    </editar-categoria>

                    <tbody>
                      <tr v-for="data in categorias" >
                        <td class="text-center" >
                            <a style="color: #094074;" href="#" title="Editar" 
                              v-on:click="editar_categoria(data)" 
                            ><i class="fa fa-edit fa-lg" ></i></a> 
                            <a style="color: red;" href="#" title="Eliminar" 
                              v-on:click="eliminar_categoria(data)" 
                            ><i class="fa fa-trash-o fa-lg" ></i></a> 
                        </td>
                        <td >{{data.categoria}}</td>
                        <td>{{data.monto_minimo}}</td>
                      </tr>                    
                    </tbody>
                  </table>
               

                </div>
                <!-- /.box-body -->


            </div>
          </div>
        </div>
      </div>              
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<!-- LIBRERIA DE VUEJS - CARLOS ZAMBRANO 22 DE 11 - 2018 -->
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue-resource.min.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue-router.js"></script> 
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/sweetalert.min.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/axios.min.js"></script>
 
<script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/component/cliente/cliente_categoriaventa.js"></script>