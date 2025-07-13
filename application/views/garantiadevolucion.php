<?php
/* ------------------------------------------------
  ARCHIVO: garantiadevolucion.php
  DESCRIPCION: Contiene la vista principal del módulo de devolucion por garantia.
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Devoluciones de Productos en Garantía'</script>";
date_default_timezone_set("America/Guayaquil");

$parametro = &get_instance();
$parametro->load->model("Parametros_model");
$impresionpdf = $parametro->Parametros_model->sel_facturapdf();


?>
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/css/jquery.timepicker.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/css/bootstrap-datepicker.standalone.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/lib/pikaday.css" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url; ?>assets/plugins/datepair/lib/jquery.ptTimeSelect.css" />

<style type="text/css">
  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 
  .dropdown-menu > li > a {
    color: #fff;
  } 

  .autocomplete-jquery-results{
      border:1px solid silver;
      float:right;
      margin-top:2px;
      position:absolute;
      display: none;
      z-index: 999;
  }

  /*Esta clase se activa cuando el usuario se mueve por las sugerencias*/
  .autocomplete-jquery-mark{
      color:black;
      background-color: #E0F0FF !important;
  }

  /* Cada sugerencia va a llevar esta clase, por lo tanto tomara el estilo siguiente */
  .autocomplete-jquery-item{
      border-bottom: 1px solid lightgray;
      display: block;
      height: 25px;
      padding-top: 5px;
      text-decoration: none;     
      padding-left: 3px;  
      background-color: white;
  }
  .autocomplete-jquery-results{
       box-shadow: 1px 1px 3px black;
  }
  /* Al pasar por encima de las sugerencias*/
  .autocomplete-jquery-item:hover{
      background-color: #E0F0FF;
      color:black;
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

      /* CARGA DE DATOS EN EL DATATABLE */
    $('#dataTableObj').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "garantia/listadoDataDevolucion",
        'columns': [
            {"data": "ver"},        
            {"data": "fecha"},
            {"data": "nro_documento"},
            {"data": "cliente"}, 
            {"data": "identificacion"}              
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
    var cliente = $("#select_cliente").val();
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('garantia/tmp_gardev_fecha');?>",
          data: { fdesde:fdesde, fhasta:fhasta, cliente: cliente }
        }).done(function (result) {
              $('#dataTableObj').DataTable().ajax.reload();
        }); 
  });


    $(document).on('click', '#txt_clinom', function(){
      $('.autocomplete').autocomplete();
    });

    $(document).on('click', '.form-control', function(){  
      $('.autocomplete-jquery-results').each(function(index, el) {
        this.remove();
      });
    });

    /* MUESTRA DATOS DEL CLIENTE */
    $(document).on('click', '.autocomplete-jquery-item', function(){  
      var nom = $(this).text(); 
      autocomplete_cliente(nom);
/*      if (autoparent == "cliente"){
        autocomplete_cliente(nom);
      } else {
        autocomplete_serie(nom);
      }*/
    });

    function autocomplete_cliente(nom){
      if (nom === ""){
        alert("Debe ingresar un nombre");
        return false;
      }

      garantiamodel.cliente_nombre = nom;
    }  

      /* Productos en Garantia */
    $('#dataProdgarantia00').dataTable({
        'language': {
          'url': base_url + 'public/json/language.spanish.json'
        },
        'ajax': "garantia/listadoDataProdgarantia",
        'columns': [
            {"data": "ver"},        
            {"data": "fecha"},
            {"data": "nro_documento"},
            {"data": "producto"}, 
            {"data": "serie"},              
            {"data": "precio"},              
            {"data": "entregado"},              
            {"data": "dias"},              
            {"data": "vencimiento"}              
        ]

    });


    $(document).on('click', '.garantia_print', function(){
      var id = $(this).attr('id');
      var impresionpdf = <?php if(@$impresionpdf != NULL) { print $impresionpdf;} else { print 0;} ?>;
      if (impresionpdf == 0){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
            dataType: "html",
            type: "POST",
            data: {id: id}
          },
          href: "<?php echo base_url('garantia/imprimirdevolucion_ticket');?>" 
        });        
      }
      else{
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
            dataType: "html",
            type: "POST",
            data: {id: id}
          },
          href: "<?php echo base_url('garantia/imprimirdevolucion');?>" 
        });        
      }  
              
    });







/*-----------------------------------*/


    /* modificar guia */
    $(document).on('click', '.edi_guia', function(){
      var id = $(this).attr('id');
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "garantia/tmp_guia",
          data: { id: id },
          success: function(json) {
            location.replace("<?php print $base_url;?>garantia/editar");
          }
      });
    });

    /* Eliminar guia */
    $(document).on('click', '.del_guia', function(){
      var id = $(this).attr('id');
      var sec = $(this).attr('name');
      if (confirm("Desea eliminar la Guia de Remision " + sec + " ?")){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "garantia/del_garantia",
            data: { id: id },
            success: function(json) {
              $('#dataTableObj').DataTable().ajax.reload();
            }
        });
      }  
    });

    /* Reporte  */
    $(document).on('click', '#reporte', function(){  
      var fhasta = $("#fhasta").val();
      var fdesde = $("#fdesde").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('garantia/tmp_guia_fecha');?>",
        data: { fdesde:fdesde, fhasta:fhasta },
        success: function(json) {
          window.open('<?php print $base_url;?>garantia/reportedevolucionesXLS');
        }
      });    
    });

    function registrar_cliente(){
      
    }  
  
}); 


    /* BUSQUEDA DINAMICA POR CEDULA */
    $('#txt_nro_ident0').change(function(){
      alert("saliendo de numero de Identificación");
      var idcliente = $(this).val();    
      if (idcliente === ""){
        alert("Debe ingresar un numero de Identificación");
        return false;
      }   
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('garantia/valcliente');?>",
          data: {
              idcliente: idcliente
           },
          success: function(json) {
            if(json.mens == null){ 
              alert("No existe cliente registrado con numero de identificación: " + idcliente);
              $('#menid').attr('class','col-sm-8 has-error');
              $('#mennom').attr('class','col-sm-10 has-error');
              $('#txt_clinom').val('');
              $('#txt_telf').val('');
              $('#txt_correo').val('');
              $('#txt_dir').val(''); 
              $('#txt_ciudad').val('');
              $('#txt_clid').val('');                             
            }
            else {          
              $('#txt_clinom').val(json.mens.nom_cliente);
              $('#txt_clid').val(json.mens.id_cliente);
              $('#mennom').attr('class','col-sm-10 has-success'); 
              $('#menid').attr('class','col-sm-8 has-success');
              $('#txt_telf').val(json.mens.telefonos_cliente);
              $('#txt_correo').val(json.mens.correo_cliente);
              $('#txt_dir').val(json.mens.direccion_cliente); 
              $('#txt_ciudad').val(json.mens.ciudad_cliente);
              if(json.mens.tipo_ident_cliente == 'C'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='C' selected='TRUE'> Cédula </option><option value='P'> Pasaporte </option><option value='R'> R.U.C. </option></select>");
              }
              if(json.mens.tipo_ident_cliente == 'P'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='P' selected='TRUE'> Pasaporte </option><option value='C'> Cédula </option><option value='R'> R.U.C. </option></select>");
              }          
              if(json.mens.tipo_ident_cliente == 'R'){
                $(".tipident").html("<select id='cmb_tipident' name='cmb_tipident' class='form-control'><option value='R' selected='TRUE'> R.U.C. </option><option value='C'> Cédula </option><option value='P'> Pasaporte </option></select>");
              }                    
              registrar_cliente();
            }
          }
      });

    function pruebaalert(){
      alert("Aqui estoy");
    }
      
    });



</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-list-alt"></i> Listado de Devoluciones de Productos en Garantía
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content" id="app_garantia_devolucion">
    <div class="row" v-if="mostrar_lista">
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            
            <div id="buscrango" class="col-md-6">
              <div class="form-group col-md-6" style="margin-bottom: 0px; margin-top: 0px; padding-right:0px; ">
                <label for="" class="col-md-4">Desde</label>
                <div class="input-group col-md-8">
                  <input style="width:100px;" type="text" class="form-control text-center date start" id="fdesde" name="fdesde" value="<?php if (@$desde != NULL) { @$fec = str_replace('-', '/', @$desde); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print  date("d/m/Y"); } ?>">
                </div>
              </div>              
              <div class="form-group col-md-6" style="margin-bottom: 0px; margin-top: 0px; padding:0px; ">
                <label for="" class="col-md-4">Hasta</label>
                <div class="input-group col-md-8">
                  <input style="width:100px;padding:0px;" type="text" class="form-control text-center date end" id="fhasta" name="fhasta" value="<?php if (@$hasta != NULL) { @$fec = str_replace('-', '/', @$hasta); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec;} else { print  date("d/m/Y"); }  ?>">
                
                  <span class="input-group-btn" style="padding:0px; ">
                    <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                  </span>

                </div>
              </div>              
            </div>

            <div class="col-md-3" style="margin-bottom: 0px; margin-top: 0px; padding:0px; ">
              <label for="" class="col-md-4">Clientes</label>
              <div class="input-group col-md-8" >
                <select class="form-control" id="select_cliente" v-model="cliente_select">
                  <option  v-bind:value="0">Todos los Clientes</option>
                  <option v-for="data in clientes" v-bind:value="data.id_cliente">{{data.nom_cliente}}</option>
                </select>
              </div>  
            </div>


            <div class="pull-right"> 
              <a class="btn bg-light-blue color-palette btn-grad " href="<?php print $base_url;?>garantia/reportedevolucionesXLS" data-original-title="" title=""><i class="fa fa-list-alt"></i> Reporte </a>
              <a class="btn bg-orange color-palette btn-grad add_guia00" v-on:click="editar_devolucion()"><i class="fa fa-shopping-bag"></i> Añadir </a>
              
            </div>

          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div id="upd_tbventa" class="box-body table-responsive">

                    <table id="dataTableObj" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr >
                            <th class="text-center col-md-1">Acción</th>
                            <th class="text-center col-md-1">Fecha</th>  
                            <th class="text-center col-md-1">Nro.Devolución</th>
                            <th class="text-center col-md-1">Cliente</th>
                            <th class="text-center col-md-1">Identificación</th>                            
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

    <div class="row" v-if="mostrar_edicion" v-on:eventoprueba="pruebaalert()">
          <div class="col-md-12">
            <div class="box box-danger">
              <div style="padding-top: 5px; padding-bottom: 0px;" class="box-header with-border">

                <div class="col-md-4" style="margin-bottom: 0px; margin-top: 0px;  ">
                  <label for="" class="col-md-4" style="margin-top: 10px; ">Sucursal</label>
                  <div class="input-group col-md-8" >
                    <select class="form-control" id="select_sucursal" v-on:change="actualiza_datosucursal()" v-model="sucursal_select">
                      <option v-for="data in sucursales" v-bind:value="data.id_sucursal">{{data.nom_sucursal}}</option>
                    </select>
                  </div>  
                </div>

                <div class="col-md-2" style="margin-bottom: 0px; margin-top: 0px; padding:0px; ">
                  <label for="" class="col-md-4" style="margin-top: 10px; ">Número</label>
                  <div class="form-group col-md-8" style="padding-right:5px; ">
                    <input type="text" class="form-control validate[required] text-center" v-model="numerodevolucion" >
                  </div>
                </div>

                <div class="col-md-3" style="margin-bottom: 0px; margin-top: 0px; padding:0px; ">
                    <div class="form-group col-md-4" style="margin-top: 10px;margin-right:0px;">
                      <label>Fecha</label>
                    </div>

                    <div style="margin-bottom: 0px;margin-left:0px;padding:0px;" class="form-group col-md-8" >
                      <div class="input-group ">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="date" class="form-control pull-right"  v-model="fechadevolucion">
                      </div>                             
                    </div>

                </div>    

                <div class="pull-right"> 
                  <a class="btn btn-info color-palette btn-grad" v-on:click="cerrar_devolucion()"><i class="fa fa-shopping-bag"></i> Salir </a>                 
                </div>

                <div class="pull-right"> 
                  <a class="btn btn-success color-palette btn-grad" v-on:click="guardar_devolucion()"><i class="fa fa-shopping-bag"></i> Guardar </a>                 
                </div>

                <div class="col-md-12">           

                  <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                    <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;"># Identidad</label>
                    <div id="menid" class="col-sm-8" style="padding-right: 0px;">
                      <input type="hidden" id="txt_clid" name="txt_clid" value="<?php if(@$cliente != NULL){ print @$cliente->id_cliente; }?>" >    
                      <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" v-on:change="carga_cliente_identificacion()" placeholder="Nro ID" v-model="cliente_identificacion" >
                    </div>
                  </div>

                  <div class="form-group col-md-5" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                    <label for="" class="col-sm-2 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Cliente</label>
                    <div id="mennom" class="col-sm-10 autocomplete" style="padding-right: 0px;">
                      <input type="text" class="form-control guarda_cliente" name="txt_clinom" id="txt_clinom" placeholder="Nombre del Cliente" data-source="<?php echo base_url('facturar/valclientenombre?nombre=');?>" v-model="cliente_nombre">
                    </div>
                  </div>

                  <div class="form-group col-md-4" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                    <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Correo</label>
                    <div id="" class="col-sm-8" style="padding-right: 0px;">
                     <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_correo" id="txt_correo" placeholder="Correo" value="<?php if(@$cliente != NULL){ print @$cliente->correo_cliente; }?>" v-model="cliente_correo">
                    </div>
                  </div>

                  <div class="form-group col-md-6" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                    <label for="" class="col-sm-2 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Dirección</label>
                    <div id="" class="col-sm-10" style="padding-left: 20px;">
                     <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_dir" id="txt_dir" placeholder="Dirección" value="<?php if(@$cliente != NULL){ print @$cliente->dir_cliente; }?>" v-model="cliente_direccion">
                    </div>
                  </div>

                  <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                    <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Teléfono</label>
                    <div id="" class="col-sm-8" style="padding-right: 0px;">
                     <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_telf" id="txt_telf" placeholder="Telefono" value="<?php if(@$cliente != NULL){ print @$cliente->telf_cliente; }?>" v-model="cliente_telefono">
                    </div>
                  </div>

                  <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                    <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Ciudad</label>
                    <div id="" class="col-sm-8" style="padding-right: 0px;">
                     <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_ciudad" id="txt_ciudad" placeholder="Ciudad" value="<?php if(@$cliente != NULL){ print @$cliente->ciu_cliente; }?>" v-model="cliente_ciudad">
                    </div>
                  </div>

                </div>                

              </div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="box box-danger">

              <h3 class="box-title"> Productos Vendidos</h3>

              <div class="box-body table-responsive">

                <table id="dataProdgarantia" class="table table-bordered table-hover table-responsive">
                  <thead>
                    <tr >
                        <th class="text-center col-md-1">Acción</th>                            
                        <th class="text-center col-md-1">Fecha</th>  
                        <th class="text-center col-md-1">Factura</th>
                        <th class="text-center col-md-1">Producto</th>                            
                        <th class="text-center col-md-1">Serie</th>                            
                        <th class="text-center col-md-1">Precio</th>                            
                        <th class="text-center col-md-1">Entregado</th>                            
                        <th class="text-center col-md-1">Dias</th>                            
                        <th class="text-center col-md-1">Vencimiento</th>                            
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="data in productosgarantia" >
                      <td class="text-center" >
                          <a style="color: #094074;" href="#" title="Devolver" class="fp_edi" 
                            v-on:click="devolver_serie(data)" 
                            v-if="habilitar(data)"
                          ><i class="fa fa-mail-reply fa-lg" ></i></a> 
                      </td>
                      <td >{{data.fecha}}</td>
                      <td>{{data.nro_factura}}</td>
                      <td>{{data.descripcion}}</td>
                      <td>{{data.numeroserie}}</td>
                      <td class="text-right">{{data.precio}}</td>
                      <td>{{data.fec_desde}}</td>
                      <td class="text-center">{{data.dias_gar}}</td>
                      <td>{{data.fec_hasta}}</td>
                    </tr>                    
                  </tbody>
                </table>
             

              </div>
              <!-- /.box-body -->
            </div>
          </div>

          <div class="col-md-12">
            <div class="box box-danger">

              <h3 class="box-title"> Productos Devueltos</h3>

              <div class="box-body table-responsive">

                <table id="dataProddevuelto" class="table table-bordered table-hover table-responsive">
                  <thead>
                    <tr >
                        <th class="text-center col-md-1">Acción</th>                            
                        <th class="text-center col-md-1">Producto</th>                            
                        <th class="text-center col-md-1">#Serie Devuelta</th>                            
                        <th class="text-center col-md-1">Observaciones</th>                            
                        <th class="text-center col-md-1">Almacen</th>                            
                        <th class="text-center col-md-1">#Serie Reposición</th>                            
                        <th class="text-center col-md-1">Fecha Entrega</th>                            
                        <th class="text-center col-md-1">Dias Garantía</th>                            
                    </tr>
                  </thead>

                  <editar-nota 
                        v-bind:value="detalle_seleccionado"
                        v-on:modificar_nota="actualiza_detallenota($event)"
                      >  
                  </editar-nota>

                  <tbody>
                    <tr v-for="data in productosdevolucion" >
                      <td class="text-center">
                          <a style="color: red;" href="#" title="Devolver" class="fp_edi"
                            v-on:click="deshacer_devolucion(data)" 
                          ><i class="fa fa-trash-o fa-lg"></i></a> 
                      </td>
                      <td >{{data.descripcion}}</td>
                      <td>{{data.seriedevuelta}}</td>
                      <td>
                        <div class="text-center">
                          <a href="#" title="Observaciones" class="btn btn-success btn-xs btn-grad "
                            v-on:click="editar_nota(data)">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Observaciones
                          </a> 
                        </div>
                      </td>
                      <td>
                        <select class="form-control"  v-model="data.id_almacen">
                          <option value="" selected="TRUE"> Seleccione..</option>
                          <option 
                            v-for="alm in almacenes" 
                            v-bind:value="alm.almacen_id"
                            :selected="alm.almacen_id == data.id_almacen"
                          >{{alm.almacen_nombre}}</option>
                        </select>
                      </td>
                      <td>
                        <div>
                          <div class="pull-left"> 
                            <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-search "></i> 
                            </button>
                          </div>
                          <div class="text-center"> {{data.serieentregada}} </div>
                          <select-serie 
                            :show="showModal"
                            v-bind:value="data.id_producto"
                            v-on:seleccion-serie="actualiza_serie(data, $event)"
                          >  
                          </select-serie>                          
                        </div>  
                      </td>
                      <td>
                        <div class="text-center"> {{data.fechaentrega}} </div>
                      </td> 
                      <td>
                        <div class="text-right"> 
                          <input type="number" class="form-control" v-model="data.diasgarantia">
                        </div>
                      </td>
                    </tr>                    
                  </tbody>
                </table>
             

              </div>
              <!-- /.box-body -->
            </div>
          </div>
          
          <!-- Modal -->
<!--           <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Large modal</button>
 -->       
<!--           <select-serie 
            :show="showModal"
            v-on:seleccion-serie="serie_select = $event"
          >  
          </select-serie>
          {{ serie_select }}
 -->

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
 
<script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/component/garantia/garantia_devolucion_component.js"></script>