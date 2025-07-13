<?php
/* ------------------------------------------------
  ARCHIVO: Pedido.php
  DESCRIPCION: Contiene la vista principal del módulo de Pedido.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Pedido'</script>";
date_default_timezone_set("America/Guayaquil");

$parametro = &get_instance();
$parametro->load->model("Parametros_model");
$pedidocliente = $parametro->Parametros_model->sel_pedidocliente();
$pedidomesero = $parametro->Parametros_model->sel_pedidomesero();

$impuesto = $parametro->Parametros_model->sel_impuestoadicional();
$impuestoespecial = $impuesto->valor;
$descripcionimpuestoespecial = $impuesto->descripcion;

$vernumerorden = $parametro->Parametros_model->sel_habilitaorden();
$pedidopromo = $parametro->Parametros_model->sel_pedidopromo();

$ptoventasingular = $parametro->Parametros_model->sel_ptoventasingular();
$ptoventaplural = $parametro->Parametros_model->sel_ptoventaplural();

$parametro->load->model("Sistema_model");
$sistema = $parametro->Sistema_model->sel_sistema();
$iconopedido = $sistema->icon_pedido;


$usua = $this->session->userdata('usua');
?>
<style type="text/css">

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
  /* Al pasar por ensima de las sugerencias*/
  .autocomplete-jquery-item:hover{
      background-color: #E0F0FF;
      color:black;
} 





</style>
<script type='text/javascript' language='javascript'>

  $('.est_ped').bootstrapToggle();

  $(document).ready(function () {

    var mese = <?php if(@$mese_mesa->id_mesero != NULL){ print $mese_mesa->id_mesero;}else{ print 0;} ?>;
    var perfil = <?php print $usua->perfil; ?>;

    if(mese > 0){
      $('#comanda').attr('disabled', false);
      $('#precuenta').attr('disabled', false);
      $('#add_producto').attr('disabled', false);
    }else{
      $('#comanda').attr('disabled', true);
      $('#precuenta').attr('disabled', true);
      $('#add_producto').attr('disabled', true);
    }

    if(perfil == 3){
      $('#comanda').attr('disabled', false);
      $('#precuenta').attr('disabled', false);
      $('#add_producto').attr('disabled', false);
    }

    var vermese = <?php print $pedidomesero->valor ?>;
    if (vermese == 0){
      $('#add_producto').attr('disabled', false);
    }

    /* CARGA DEL DATATABLE (LISTADO) */
    $('#dataTablePro').dataTable({
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
      'ajax': "listadoProducto",
      'columns': [
          {"data": "codbarra"},
          {"data": "codauxiliar"},
          {"data": "nombre"},
          {"data": "precioventa"}, 
          {"data": "ver"}
      ]
      });

    $('.guarda_cliente').blur(function(){
      var idcliente = $('#txt_nro_ident').val();  
      if (idcliente === ""){
        alert("Debe ingresar un numero de Identificación");
        return false;
      }     
      var nom = $('#txt_clinom').val(); 
      if (nom === ""){
        alert("Debe ingresar un nombre");
        return false;
      }      
      registrar_cliente();
    });

    function registrar_cliente(){
      var id = $('#txt_nro_ident').val();
      var idtp = $('#cmb_tipident option:selected').val();      
      var nom = $('#txt_clinom').val();
      var tel = $('#txt_telf').val();
      var cor = $('#txt_correo').val();
      var dir = $('#txt_dir').val(); 
      var ciu = $('#txt_ciudad').val(); 
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Facturar/upd_ventcliente",
          data: { idcli:id, idtp:idtp, nom:nom, tel:tel, cor:cor, dir:dir, ciu:ciu },
          success: function(json) {
          }
      });
    }

    $('#txt_obs').blur(function(){
      var obs = $('#txt_obs').val();  
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "pedido/upd_observaciones",
          data: { obs: obs },
          success: function(json) {
          }
      });
    });

      /* AGREGAR PRODUCTO */
      $(document).on('click', '.add_producto', function(){
        $.fancybox.open({
          type: "ajax",
          width: 550,
          height: 550,
          ajax: {
             dataType: "html",
             type: "POST"
          },
          href: "<?php echo base_url('pedido/add_producto');?>" 
        });
      });


    /* ACTUALIZAR MESERO EN LA TABLA PEDIDO DETALLE */
    $(document).on('change','#cmb_mesero', function(){
      var idmesero = $("#cmb_mesero option:selected").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('pedido/upd_mesero');?>",
        data: { idmesero: idmesero, },
        success: function(json) {
          if(json.resu == 0){
            $('#comanda').attr('disabled', true);
            $('#precuenta').attr('disabled', true);
            $('#add_producto').attr('disabled', true);
          }else{
          $('#comanda').attr('disabled', false);
          $('#precuenta').attr('disabled', false);
          $('#add_producto').attr('disabled', false);            
          }

        }
      });    

      return false;

    });

    $('.guarda_cliente').blur(function(){
      var idcliente = $('#txt_nro_ident').val();  
      if (idcliente === ""){
        alert("Debe ingresar un numero de Identificación");
        return false;
      }     
      var nom = $('#txt_clinom').val(); 
      if (nom === ""){
        alert("Debe ingresar un nombre");
        return false;
      }      
      registrar_cliente();
    });

    /* BUSQUEDA DINAMICA POR CEDULA */
    $('#txt_nro_ident').blur(function(){
      var idcliente = $(this).val();    
      if (idcliente === ""){
        alert("Debe ingresar un numero de Identificación");
        return false;
      }   
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Pedido/valcliente');?>",
          data: {
              idcliente: idcliente
           },
          success: function(json) {
            if(json.mens == null){ 
              $('#menid').attr('class','col-sm-8 has-error');
              $('#mennom').attr('class','col-sm-10 has-error');
              $('#txt_clinom').val('');
              $('#txt_telf').val('');
              $('#txt_correo').val('');
              $('#txt_dir').val(''); 
              $('#txt_ciudad').val('');               
            }
            else { 
              $('#txt_clinom').val(json.mens.nom_cliente);
              $('#txt_idcli').val(json.mens.id_cliente);
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
    });

    $('.autocomplete').autocomplete();

    /* MUESTRA DATOS DEL CLIENTE */
    $(document).on('click', '.autocomplete-jquery-item', function(){  
      var nom = $(this).text(); 
      if (nom === ""){
        alert("Debe ingresar un nombre");
        return false;
      }
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo base_url('Pedido/busca_nombre');?>",
          data: {
              nom: nom
           },
          success: function(json) {
              $('#txt_nro_ident').val(json.mens.ident_cliente);
              $('#txt_clinom').val(json.mens.nom_cliente);
              $('#txt_idcli').val(json.mens.id_cliente);
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
      });

    });


    function registrar_cliente(){
      var id = $('#txt_nro_ident').val();
      var idtp = $('#cmb_tipident option:selected').val();      
      var nom = $('#txt_clinom').val();
      var tel = $('#txt_telf').val();
      var cor = $('#txt_correo').val();
      var dir = $('#txt_dir').val(); 
      var ciu = $('#txt_ciudad').val(); 
      $.ajax({
          type: "POST",
          dataType: "json",
          url: base_url + "Pedido/upd_pedidocliente",
          data: { idcli:id, idtp:idtp, nom:nom, cor:cor, tel:tel, dir:dir, ciu:ciu },
          success: function(json) {
          }
      });
    }

    /* CALCULO DE SUBTOTAL */
    $(document).on('keyup','.cantidad', function(){
      id = $(this).attr("id");
      idreg = $(this).attr("name");
      actualizapreciosubtotal(id, idreg);
      return false;
    });

    /* CALCULO DE SUBTOTAL */
    $(document).on('change','.promo', function(){
      id = $(this).attr("id");
      idreg = $(this).attr("name");
      actualizapreciosubtotal(id, idreg);
      return false;
    });

    function actualizapreciosubtotal (id, idreg){
      var cantidad = 0;
      var precio = 0;
      var subtotal = 0;
      cantidad = $('.cantidad[name='+idreg+']').val();
      precio = $('td[name='+idreg+']').text();
      promo = 0;
      pedidopromo = <?php print $pedidopromo; ?>;
      if (pedidopromo == 1){
        preciobase = $('.preciobase[name='+idreg+']').val();
        precio = preciobase;
        if ($('.promo[name='+idreg+']').is(":checked")) {
          promo = 1;
          precio = 0;
        }
      }  
      subval = cantidad * precio;
      subtotal = subval.toFixed(2);

      $('div[name='+idreg+']').html(subtotal);
      $('td[name='+idreg+']').html(precio);

      /* ACTUALIZA PRECIO */
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('pedido/upd_precio');?>",
        data: { id: id, cant: cantidad, precio: precio, promo: promo, idreg: idreg },
        success: function(json) {
          actualiza_valor();          
        }
      });

    }

    function actualiza_valor(){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "pedido/upd_valor",
            success: function(json) {
              var subtotaliva = 0;
              var subtotalcero = 0;
              var miva = 0;
              var total = 0;

              subtotaliva = json.subtotaliva;
              subtotalcero = json.subtotalcero;
              miva = json.miva;
              total = json.total;

              subtotaliva = subtotaliva.toFixed(2); 
              subtotalcero = subtotalcero.toFixed(2); 
              miva = miva.toFixed(2); 
              total = total.toFixed(2); 

              $('#subtotaliva').html(subtotaliva);
              $('#subtotal0').html(subtotalcero);
              $('#miva').html(miva);
              $('#mtotal').html(total);
  
            }
        });

    }



    /* ELIMINAR DATOS DEL CLIENTE */
    $(document).on('click', '.del_cliped', function(){  
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('pedido/elim_cliente');?>",
        success: function(json) {
          $('#txt_nro_ident').val('');
          $('#txt_idcli').val('');
          $('#txt_clinom').val('');
          $('#mennom').attr('class','form-group col-md-12 ');
          $('#menid').attr('class','form-group col-md-12 ');
          $('#cmb_mesero').val("0");
          $('#cli_telf').html('<td id="cli_telf"></td>');
          $('#cli_correo').html('<td id="cli_telf"></td>');
          $('#cli_dir').html('<td id="cli_telf"></td>');
        }
      });
    });

    /* CARGA LAS VARIANTES DEL PRODUCTO */
    $(document).on('click','.pedpro_var', function(){
      var id = $(this).attr("name");
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('pedido/tmp_pedprovar');?>",
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
                href: "<?php echo base_url('pedido/ver_pedprovar');?>" 
              });

           }

        });
    });

    /* FUNCIONES PARA EL BOTON DE PRECUENTA */
    $(document).on('click','.precuenta', function(){
      var idmesa = $('#identmesa').attr('name');
      //alert(data);

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('pedido/cargarprecuenta');?>",
        data: {id: idmesa},
        success: function(json) {

        }
      }); 

      // $.fancybox.open({
      //           type: "ajax",
      //           width: 550,
      //           height: 550,
      //           ajax: {
      //              dataType: "html",
      //              type: "POST",
      //              data: {id: idmesa}
      //           },
      //           href: "<?php // echo base_url('pedido/cargarprecuenta');?>" 
      //         });

    });

    /* FUNCIONES PARA EL BOTON DE COMANDA */
    $(document).on('click','.comanda', function(){
      var idmesa = $('#identmesa').attr('name');
      //alert(data);

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('pedido/imprimircomanda');?>",
        data: {id: idmesa},
        success: function(json) {
          $('#detpedido').load(base_url + "pedido/actualiza_tabla_pedido");
        }
      }); 

      // $.fancybox.open({
      //           type: "ajax",
      //           width: 550,
      //           height: 550,
      //           ajax: {
      //              dataType: "html",
      //              type: "POST",
      //              data: {id: idmesa}
      //           },
      //           href: "<?php // echo base_url('pedido/cargarcomanda');?>" 
      //         });
    });

    /* FUNCIONES PARA EL BOTON AÑADIR NOTA AL PEDIDO */
    $(document).on('click','.addnota', function(){
      var idpro = $(this).attr('id');
      var idped = $(this).attr('name');
      $.fancybox.open({
        type: "ajax",
        width: 550,
        height: 550,
        ajax: {
           dataType: "html",
           type: "POST",
           data: {idpro: idpro, idped: idped},
        },
        href: "<?php echo base_url('pedido/nota_pedido');?>"
      });
    });

    /* FUNCIONES PARA EL ESTADO DEL PEDIDO */
    $(document).on('change','.est_ped', function() {
      var idpro = $(this).attr('id');
      var idped = $(this).attr('name');
      var est = "";
      if (this.checked) { est = 1; }
      else { est = 0; }
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('pedido/upd_est');?>",
        data: { idpro: idpro, idped: idped, est: est },
        success: function(json) {

        }
      }); 

    });

    /* LIMPIAR MESAS */
    $(document).on('click','.limpiamesa', function(){
      var id = $(this).attr("id");
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('pedido/tmp_pedido');?>",
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
            href: "<?php echo base_url('pedido/obs_mesalimpia');?>",
            success: function(json) {
              alert(json.resu);
            }
          });
                    
        }
      });
    }); 

      $(document).on('click', '.facturar', function(){
        id = $('#identmesa').attr('name');
        $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php print $base_url;?>Facturar/pedido_factura",
           data: {id: id},
           success: function(json) {
              if (parseInt(json.resu) > 0) {
                location.replace("<?php print $base_url;?>Facturar/factura_deposito");
              } else {
                 alert("No se pudo facturar el pedido.");
              }

           }
        });
      });


    function updmesero(){
      var idmesero = $("#cmb_mesero option:selected").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('pedido/upd_mesero');?>",
        data: { idmesero: idmesero, },
        success: function(json) {
          if(json.resu == 0){
            $('#comanda').attr('disabled', true);
            $('#precuenta').attr('disabled', true);
            $('#add_producto').attr('disabled', true);
          }else{
          $('#comanda').attr('disabled', false);
          $('#precuenta').attr('disabled', false);
          $('#add_producto').attr('disabled', false);            
          }
        }
      });    
    }

    updmesero();
    
}); 


</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 id="identmesa" name="<?php print @$areamesa->id_mesa; ?>">
      <i class="fa <?php print @$iconopedido; ?>"></i> Pedido de <?php print $ptoventasingular; ?> <?php print @$areamesa->nom_mesa; ?> del area <?php print @$areamesa->nom_area; ?>  
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>pedido">Pedido</a></li>
      
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <!-- DATOS DEL CLIENTE -->        
      <div class="col-md-12">
        <div class="box box-danger">

          <?php 
            if(@$pedidocliente->valor == 1){ ?>

          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Tipo Ident</label>
                  <div  class="col-sm-8 tipident" style="padding-right: 0px;">
                    <select id="cmb_tipident" name="cmb_tipident" class="form-control">
                    <?php 
                      if(@$tipident != NULL){ ?>
                    <?php } else { ?>
                        <option  value="0" selected="TRUE">Seleccione...</option>
                    <?php } 
                              if (count($tipident) > 0) {
                                foreach ($tipident as $ti):
                                    if(@$cliente->tipo_ident_cliente != NULL){
                                        if($ti->cod == $cliente->tipo_ident_cliente){ ?>
                                            <option  value="<?php  print $ti->cod; ?>" selected="TRUE"><?php  print $ti->det; ?></option> 
                                            <?php
                                        }else{ ?>
                                            <option value="<?php  print $ti->cod; ?>"> <?php  print $ti->det; ?> </option>
                                            <?php
                                        }
                                    }else{ ?>
                                        <option value="<?php  print $ti->cod; ?>"> <?php  print $ti->det; ?> </option>
                                        <?php
                                        }   ?>
                                    <?php

                                endforeach;
                              }
                              ?>
                    </select>
                  </div>
                </div>

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Nro de Ident</label>
                  <div id="menid" class="col-sm-8" style="padding-right: 0px;">
                    <input type="hidden" id="txt_idcli" name="txt_idcli" value="<?php if(@$cliente != NULL){ print @$cliente->ident_cliente; }?>" >    
                    <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$cliente != NULL){ print @$cliente->ident_cliente; }?>" >
                  </div>
                </div>

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-2 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Cliente</label>
                  <div id="mennom" class="col-sm-10 autocomplete" style="padding-right: 0px;">
                    <input type="text" class="form-control guarda_cliente" name="txt_clinom" id="txt_clinom" placeholder="Nombre del Cliente" value="<?php if(@$cliente != NULL){ print @$cliente->nom_cliente; }?>" data-source="<?php echo base_url('facturar/valclientenombre?nombre=');?>">
                  </div>
                </div>

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Teléfono</label>
                  <div id="" class="col-sm-8" style="padding-right: 0px;">
                   <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_telf" id="txt_telf" placeholder="Telefono" value="<?php if(@$cliente != NULL){ print @$cliente->telefonos_cliente; }?>" >
                  </div>
                </div>

                <div class="form-group col-md-6" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-2 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Dirección</label>
                  <div id="" class="col-sm-10" style="padding-left: 20px;">
                   <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_dir" id="txt_dir" placeholder="Dirección" value="<?php if(@$cliente != NULL){ print @$cliente->direccion_cliente; }?>" >
                  </div>
                </div>

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Ciudad</label>
                  <div id="" class="col-sm-8" style="padding-right: 0px;">
                   <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_ciudad" id="txt_ciudad" placeholder="Ciudad" value="<?php if(@$cliente != NULL){ print @$cliente->ciudad_cliente; }?>" >
                  </div>
                </div>

                <div class="form-group col-md-3" style="padding-right: 0px; padding-left: 0px; margin-left: 0px;">
                  <label for="" class="col-sm-4 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Correo</label>
                  <div id="" class="col-sm-8" style="padding-right: 0px;">
                   <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_correo" id="txt_correo" placeholder="Correo" value="<?php if(@$cliente != NULL){ print @$cliente->correo_cliente; }?>" >
                  </div>
                </div>

              </div> 
            </div>
          </div>
          <?php }  ?>
                
              <div class="box-header with-border">
                  <!-- <h3 class="box-title"><i class="fa fa-user"></i> Mesero </h3> --> 

                <div class="form-group col-md-6" style="padding-right: 0px; padding-left: 10px; margin-left: 0px;">
                  <label for="" class="col-sm-2 control-label text-right" style="padding-right: 0px; padding-left: 0px;">Observaciones</label>
                  <div id="" class="col-sm-10" style="padding-left: 20px;">
                   <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_obs" id="txt_obs" placeholder="Observaciones" value="<?php if(@$cliente != NULL){ print @$cliente->observaciones; }?>" >
                  </div>
                </div>

                <?php if($vernumerorden == 1) { ?>
                  <label id="labelorden">#Orden <?php if(@$mese_mesa != NULL){ print @$mese_mesa->nro_orden; }?></label>                  
                <?php } ?>

                <button id="<?php print @$areamesa->id_mesa; ?>" type="button" class="btn bg-navy btn-flat limpiamesa pull-right" style="margin-left: 20px;">
                      <i class="fa fa-trash"></i> Limpiar <?php print $ptoventasingular; ?>
                </button>                 
                <div class="pull-right"> 
  
                  <?php 
                    if(@$pedidomesero->valor == 1){ ?>

                    <div style="margin-bottom: 0px;"class="form-group" >
                      <select id="cmb_mesero" name="cmb_mesero" class="form-control">
                          <?php 
                            if(@$mesero != NULL){ ?>
                            <option  value="0" selected="TRUE">Seleccione Mesero...</option>
                          <?php } else { ?>
                              
                          <?php } 
                                    if (count($mesero) > 0) {
                                      foreach ($mesero as $mero):
                                          if ((@$mese_mesa->id_mesero != NULL) || (@$usuariocajero != NULL)) {
                                              if((@$mero->id_mesero == $mese_mesa->id_mesero) || (@$usuariocajero == $mero->id_mesero)){ ?>
                                                   <option value="<?php  print $mero->id_mesero; ?>" selected="TRUE"> <?php  print $mero->nom_mesero ?> </option>
                                                  <?php
                                              }else{ ?>
                                                  <option value="<?php  print $mero->id_mesero; ?>" > <?php  print $mero->nom_mesero ?> </option>
                                                  <?php
                                              }
                                          }else{ ?>
                                              <option value="<?php  print $mero->id_mesero; ?>"> <?php  print $mero->nom_mesero ?> </option>
                                              <?php
                                              }   ?>
                                          <?php
                                      endforeach;
                                    }
                                    ?>
                      </select>                                    
                    </div>
                  <?php } ?>
                    
                  </div>
                </div>


              </div>
      </div>


      <div class="col-md-12">
          <!-- DATOS DEL CLIENTE -->
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa <?php print @$iconopedido; ?>"></i> Lista de Pedidos <?php // print_r($detmesa); ?></h3>
                  <div class="pull-right"> 
                  <button id="facturar" name="" type="button" class="btn btn-vk color-palette btn-grad facturar" >
                    <i class="fa fa-dollar"></i> Facturar 
                  </button>   
                  <button id="precuenta" type="button" class="btn bg-green color-palette btn-grad precuenta" >
                    <i class="fa fa-money"></i> Pre-Cuenta 
                  </button>   
                  <button id="comanda" type="button" class="btn bg-light-blue color-palette btn-grad comanda" >
                    <i class="fa fa-print"></i> Comanda 
                  </button>                       
                  <button id="add_producto" type="button" class="btn bg-orange-active color-palette btn-grad add_producto" >
                    <i class="fa fa-shopping-bag"></i> Añadir Producto
                  </button>                        
                    
                  </div>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div id="detpedido" class="col-md-12 table-responsive" > 
                      <table class="table table-bordered detpedido table-responsive">
                        <tbody>
                          <tr>
                              <th class="text-center col-md-1">Nro</th>
                              <th class="text-center col-md-1">Cantidad</th>
                              <th>Nombre</th>
                              <th class="text-center col-md-1">Nota</th>
                              <?php if ($pedidopromo == 1){ ?>
                                <th class="text-center col-md-1">Promo</th>
                              <?php } ?>
                              <th class="text-center col-md-1">Precio</th>
                              <th class="text-center col-md-1">SubTotal</th>
                              <th class="text-center col-md-1">Estado</th>
                              <th class="text-center col-md-1">Acción</th>
                          </tr>
                          <?php 
                          $nro = 0;
                          $total = 0;
                          $subtotaliva = 0;
                          $subtotalcero = 0;
                          $subtotal = 0;
                          $submonto = 0;
                          $miva = 0;
                          $iva = 0.12;
                          if(@$detmesa != NULL){
                              if (count($detmesa) > 0) {
                                  foreach ($detmesa as $dm):
                                      $nro = $nro + 1;
                                      
                                      if ($dm->pro_grabaiva == 1){
                                        $subtotaliva += $dm->cantidad * $dm->precio;
                                      } else {
                                        $subtotalcero += $dm->cantidad * $dm->precio;
                                      }

                                      $subtotal = $dm->cantidad * $dm->precio;
                                      $submonto = $submonto + $subtotal;
                                      $miva += $subtotal * (1 + $iva) - $subtotal;
                                      /*$miva = $submonto * $iva;*/
                                      //$subiva = $submonto + $miva;
                                      

                          ?>
                          <tr>
                              <!-- NRO -->
                              <td class="text-center"><?php print $nro; ?></td>
                              <!-- CANTIDAD -->
                              <td class="text-center">
                                <input type="text" class="form-control text-center cantidad" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" value="<?php print $dm->cantidad; ?>" >
                              </td>
                              <!-- NOMBRE DEL PRODUCTO -->
                              <td>
                                <div class="col-md-12">
                                  <div class="col-md-8">
                                <?php print $dm->pro_nombre; ?>                                    
                                  </div>
                                <?php
                                  if($dm->variante == 1){ ?>
                                    <input type="hidden" class="form-control maxitemvariante" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" value="<?php print @$dm->maxitemvariante; ?>" >
                                    <a href="#" title="Variantes del Producto" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="btn btn-warning btn-grad pedpro_var pull-right">Detalles</a>    
                                <?php }
                                ?>                                  
                                </div>

                              </td>
                              <!-- NOTA DEL PEDIDO -->
                              <td>
                                <div class="text-center">
                                  <a href="#" title="Añadir Nota" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="btn btn-success btn-xs btn-grad addnota">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Nota
                                  </a> 
                                </div>
                              </td>

                              <!-- PROMO -->
                              <?php if ($pedidopromo == 1){ ?>
                                <td>
                                  <input type="hidden" class="form-control text-center preciobase" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" value="<?php print @$dm->pro_precioventa; ?>" >
                                  <div class="text-center">
                                    <input type="checkbox" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="promo" <?php if(@$dm != NULL){ if(@$dm->promo == 1){ print "checked='' ";} }?> >
                                  </div>
                                </td>
                              <?php } ?>

                              <!-- PRECIO DEL PRODUCTO -->
                              <td id="<?php print @$dm->id_producto; ?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="text-right producto"><?php print $dm->precio; ?></td>
                              <!-- SUBTOTAL -->
                              <td name="<?php print @$dm->pro_nombre; ?>" class="text-right ">
                                <div id="<?php print @$dm->id_producto; ?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>">
                                  <?php 
                                    $subtotal = $dm->cantidad * $dm->precio;
                                    print $subtotal; 
                                  ?>
                                </div>
                                
                              </td>
                              <!-- ESTATUS checked-->
                              <?php 
                                if(@$dm->estatus == '1'){ $est = 'checked'; }else{ $est = '';}
                                $bootclass =  "class='fa " . $iconopedido ."'"; 
                              ?>
                              <td class="text-center">
                                <input <?php print $est; ?> id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="est_ped" type="checkbox"  data-toggle="toggle" data-on="<i class='fa fa-check'></i> Entregado" data-off="<i <?php print $bootclass; ?> aria-hidden='true'></i> Pedido" data-onstyle="success" data-offstyle="danger" data-size="small"> 
                              
                              </td>
                              <td class="text-center">
                              <!-- ACCION -->
                                <?php if(@$dm->est_comanda == '0'){ ?>
                                  <a href="#" title="Eliminar" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" name="<?php if(@$dm != NULL){ print @$dm->id_ped; }?>" class="btn btn-danger btn-grad pedpro_del"><i class="fa fa-trash-o"></i></a>
                                <?php } ?>
                              </td>
                          </tr>
                          <?php
                                  endforeach;
                              }
                             /*$miva = $subtotaliva * $iva;*/

                          }
                          ?>
                        </tbody>
                      </table>
                      <div class="pull-right">
                        <a class="btn btn-danger btn-sm del_proped" href="#" data-original-title="" title=""><i class="fa fa-trash"></i> Borra Productos </a> 
                      </div>
                    </div>
                  </div>
                </div>
                <div id="monto"  align="center" class="box-footer">
              <div class="col-md-7">
                <div id="calmonto" class="pull-right" style="margin-right: 10px;">
                  <table class="table table-clear calmonto" >
                    <tbody>
                      <tr>
                        <td class="text-left"><strong>Subtotal IVA 12 % </strong></td>
                        <td id="subtotaliva" class="text-right">$ <?php  print number_format(@$subtotaliva,2,",","."); ?></td>                                        
                      <tr>
                      <tr>
                        <td class="text-left"><strong>Subtotal IVA 0 % </strong></td>
                        <td id="subtotal0" class="text-right">$ <?php  print number_format(@$subtotalcero,2,",","."); ?></td>                                        
                      <tr>                      
                      <tr>
                        <td class="text-left"><strong>IVA (12%)</strong></td>
                        <td id="miva" class="text-right">$ <?php print number_format(@$miva,2,",","."); ?></td>                                        
                      </tr>
                      <?php if ($impuestoespecial > 0) { 
                        $subtotales = 0;
                        $subtotales = @$subtotaliva + @$subtotalcero;
                      ?>
                        <tr>
                          <td class="text-left"><strong><?php print $descripcionimpuestoespecial; ?></strong></td>
                          <td id="impuestoespecial" class="text-right">$<?php $totalimp = $subtotales * $impuestoespecial / 100; print number_format(@$totalimp,2,",","."); ?></td>                                        
                        </tr>
                      <?php } ?>
                      <tr>
                        <td class="text-left"><strong>Total</strong></td>
                        <td id="mtotal" class="text-right">
                          <strong>$ <?php $totalimp = ($subtotaliva + $subtotalcero) * $impuestoespecial / 100;
                                          $mtotal = $subtotaliva + $subtotalcero + $miva + $totalimp; 
                                          print number_format(@$mtotal,2,",","."); 
                                    ?>                                     
                          </strong></td>                                        
                      </tr>      
                    </tbody>
                  </table>
                </div>                
              </div>                

                 <!--  <h3>MONTO: <?php // print $total; ?> $</h3>-->
                </div>
              </div>
          <!-- FIN DE ESPACIO DE LAS AREAS -->
      </div>           
    </div>
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

