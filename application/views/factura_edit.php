<?php
/* ------------------------------------------------
  ARCHIVO: Pedido.php
  DESCRIPCION: Contiene la vista principal del módulo de Pedido.
  FECHA DE CREACIÓN: 07/08/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Facturar'</script>";
date_default_timezone_set("America/Guayaquil");
/*
if (count($areamesa) > 0) {
  foreach ($areamesa as $arme):
    if(@$climesa->id_mesa != NULL){
      if($arme->id_mesa == $climesa->id_mesa){ 
        $area = $arme->mesas;
        $idmesa = $arme->id_mesa;
      }
    }    
  endforeach;
}

*/



?>
<style type="text/css">
  .linea{
    border-width: 2px 0 0;
    margin-bottom: 20px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 
/*
  #notaventa{
    display: none; 
  }
*/
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

  $(document).ready(function () {

  
  $("#imprimir").attr("disabled", true);

  $("#pagar").attr("disabled", false);

  


  $(document).on('click','#pagar', function(){
    var efectivo = $('#efectivo').val();
    var tarjeta = 0;
    var cambio = $('#cambio').text();
    cambio = cambio.replace('$ ','');
    cambio = cambio.replace(',','.');
    cambio = parseFloat(cambio);    
    var fecha = $('#fecha').val();
    var idventaanular = $("#txt_idventa").val();
    var mesa = $("#txt_nomesa").val();
    var idmesa = $("#txt_idmesa").val();
    var mesero = $('#txt_mesero').val();
    var nro_factura = $('#factura').val();
    var nro_notaventa = $('#notaventa').val();
    var cmb_forma = $('#cmb_forma').val();
    /*
    REALIZAR UN AJAX QUE VERIFIQUE EL CLIENTE BIEN SEA POR CEDULA 
    O POR NOMBRE DE MANERA QUE SE CERTIFIQUE QUE SE ESTA EMITIENDO
    LA FACTURA.||
    */
    var nro_ident = $('#txt_nro_ident').val();
    var nom_cliente = $('#txt_clinom').val();
    var cor_cliente = $('#txt_correo').val();
    var telf_cliente = $('#txt_telf').val();
    var dir_cliente = $('#txt_dir').val();

    if(nro_ident.length > 4 && nom_cliente.length >= 3 ){

        $("#imprimir").attr("disabled", false);
        $("#pagar").attr("disabled", true);
        $("#pagar").css("display", "none");

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('facturar/guardarmodificacion');?>",
        data: { fecha: fecha, idmesa: idmesa, mesa: mesa, mesero: mesero, cmb_forma: cmb_forma, 
                nro_factura: nro_factura, nro_notaventa: nro_notaventa, 
                nro_ident: nro_ident, nom_cliente: nom_cliente, cor_cliente: cor_cliente,
                telf_cliente: telf_cliente, dir_cliente: dir_cliente, 
                idventaanular: idventaanular },
        success: function(json) {
        $("#imprimir").attr("disabled", false);
        $("#pagar").attr("disabled", true);
        $("#pagar").css("display", "none");
        alert(json.dat);
        id = json.dat;
           $("#imprimir").attr('name', id);
        }
      });
    }else{
      alert("VERIFIQUE LOS DATOS DE CEDULA Y NOMBRE");
    }

  });

    function redireccion(contr, meth) {
        location.replace(base_url + contr + (meth ? "/" + meth : ""));
    }

  $('#fecha').datepicker();
    $('#fecha').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

  $(document).on('click','.tipopago', function(){
    var id = $(this).attr('id');
    $.fancybox.open({
      type: "ajax",
      width: 550,
      height: 550,
      ajax: {
         dataType: "html",
         type: "POST"
      },
      href: "<?php echo base_url('facturar/tipopago');?>" 
    });
  });  

    /* CAPTURA LOS DATOS DEL FANCYBOX AGREGAR TIPO PAGO */
    $(document).on("submit", "#formTP", function() {
        var data = $(this).serialize();
            $.ajax({
                url: $(this).attr("action"),
                data: data,
                type: 'POST',
                dataType: 'json',
                success: function(json) {
                    $.fancybox.close();
                  //  $('#detvar').load('<?php echo base_url("producto/recarga");?>');
                }
            });
        return false;
    });

  /* MASCARA PARA COD DE FACTURA*/
  $("#factura").mask("999-999-999999999");

  /* PROCESO DE DESCUENTO */
  $(document).on('change','#descuento', function(){
    descuento = $(this).val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('facturar/upd_descuento');?>",
        data: { descuento: descuento },
        success: function(json) {
        //  aplica_descuento();
          $('#detfactura').load(base_url + "facturar/actualiza_tabla_factura");
          $('#calmonto').load(base_url + "facturar/actualiza_montos_factura");
        //  var montototal = $('#mtotal').text();

        //  $('#mototal').html('<strong>'+montototal+'</strong>');
          //      alert(montototal);
        }
      });
    
  });  

    function aplica_descuento(){

        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "compra/desc_compra",
            success: function(json) {

              stciva = parseFloat(json.res.subconiva); // Subtotal con Iva 
              stsiva = parseFloat(json.res.subsiniva); // Subtotal Sin Iva 
              tsdesc = parseFloat(json.res.total); // Total Sin Descuento 

              mtdesc = parseFloat(json.res.descu); // Monto Descuento 
              miva = parseFloat(json.res.montoiva); // Monto IVA 

              dstciva = parseFloat(json.res.descsubconiva); // Subtotal con Iva 
              dstsiva = parseFloat(json.res.descsubsiniva); // Subtotal Sin Iva 
              tcdesc  = parseFloat(json.res.totaldesc); // Total Sin Descuento     

              ttotal = parseFloat(json.res.ttotal); // Total Sin Descuento       

              valstciva = stciva.toFixed(2);
              valstsiva = stsiva.toFixed(2);
              valtsdesc = tsdesc.toFixed(2);

              valdstciva = dstciva.toFixed(2);
              valdstsiva = dstsiva.toFixed(2);
              valtcdesc  = tcdesc.toFixed(2);

              montoviva  = miva.toFixed(2);
              montototal  = ttotal.toFixed(2);


              if(valstciva == 'NaN') { valstciva = 0; }
              if(valstsiva == 'NaN') { valstsiva = 0; }
              if(valtsdesc == 'NaN') { valtsdesc = 0; }
              if(valdstciva == 'NaN') { valdstciva = 0; }
              if(valdstsiva == 'NaN') { valdstsiva = 0; }
              if(valtcdesc == 'NaN') { valtcdesc = 0; }
              if(montoviva == 'NaN') { montoviva = 0; }

           
              if(montototal == 'NaN') { montototal = 0; }

                $('#msubtotalconiva').html('$ '+valstciva);
                $('#msubtotalsiniva').html('$ '+valstsiva);
                
                $('#descsubiva').html('$ '+valdstciva);
                $('#descsub').html('$ '+valdstsiva);

                $('#mtotal').html('$ '+montototal);

              
              $('#miva').html('$ '+montoviva);

            }
        });
      //return false;
    }

    /* Boton del listado para imprimir compra */
    $(document).on('click', '#imprimir', function(){
      var id = $(this).attr('name');

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
    });

    /* SELECCIONAR TIPO DE FACTURACION */
    $(document).on('change','#cmb_forma', function(){
      var idforma = $("#cmb_forma option:selected").val();
      if(idforma == 2){
        $(".evapago").html("<input type='text' class='form-control validate[required] text-center' id='factura' name='factura' disabled='' value='<?php if(@$nrofactura != NULL){ print @$nrofactura; }?>'>");
      }else{
        $(".evapago").html("<input type='text' class='form-control validate[required] text-center' id='notaventa' name='notaventa' value='<?php if(@$nronv != NULL){ print @$nronv; }?>'>");
      }
      return false;
    });

    $(document).on('click','.volver', function(){
      var id = $(this).attr('id');
      location.replace("<?php print $base_url;?>facturar/ventas");

    });

    /* CALCULO DE MONTOS */
    $(document).on('keyup','#efectivo', function(){
      var dif = 0; 
      var total = 0;    
      var cambio = 0;     
      /* EFECTIVO */        
      var efectivo = $(this).val();
      if(efectivo == '') { efectivo = 0; }
      if(efectivo == 'NULL') { efectivo = 0; }  
      if(efectivo == 'NaN') { efectivo = 0; }      
      efectivo = efectivo.replace('$ ','');
      efectivo = efectivo.replace(',','.');      
      efectivo = parseFloat(efectivo);
      if(isNaN(efectivo) || efectivo == ''){ efectivo = 0; $('#efectivo').val(0); }          
      /* TARJETA */        
      var tarjeta = 0;
      /*
      if(tarjeta == '') { tarjeta = 0; }
      if(tarjeta == 'NULL') { tarjeta = 0; }  
      if(tarjeta == 'NaN') { tarjeta = 0; }  
      tarjeta = tarjeta.replace('$ ','');
      tarjeta = tarjeta.replace(',','.');     
      tarjeta = parseFloat(tarjeta);
      if(isNaN(tarjeta) || tarjeta == ''){ tarjeta = 0; $('#tarjeta').val(0); }   */     
      /* MONTO A PAGAR */        
      var monto = $('#mtotal').text();
      if(monto == '') { monto = 0; }
      if(monto == 'NULL') { monto = 0; }  
      if(monto == 'NaN') { monto = 0; }  
      monto = monto.replace('$ ','');
      monto = monto.replace(',','.');     
      monto = parseFloat(monto);

      total = efectivo;

      dif = total - monto;
      dif = parseFloat(dif);
      dif = dif.toFixed(2);

    

      if(isNaN(total)){ total = 0; }
      if(isNaN(dif)){ dif = 0; }

      if(total >= monto){
        $('#cambio').html('<span style="color: green;"><strong>$ '+dif+'</strong></span>');
        $("#pagar").attr("disabled", false);
      }else{
        $('#cambio').html('<span style="color: red;"><strong>$ 0.00</strong></span>');
        $("#pagar").attr("disabled", true); 
      }

    // $('#totalfp').html('<strong>'+total+'</strong>');
    });    
/*
    $(document).on('keyup','#tarjeta', function(){
      var dif = 0; 
      var total = 0;    
      var cambio = 0;  */   
      /* EFECTIVO */     
/*      var efectivo = $('#efectivo').val();   
      if(efectivo == '') { efectivo = 0; }
      if(efectivo == 'NULL') { efectivo = 0; }  
      if(efectivo == 'NaN') { efectivo = 0; }      
      efectivo = efectivo.replace('$ ','');
      efectivo = efectivo.replace(',','.');      
      efectivo = parseFloat(efectivo);
      if(isNaN(efectivo) || efectivo == ''){ efectivo = 0; $('#efectivo').val(0); }    */    
      /* TARJETA */        
/*      var tarjeta = $(this).val();
      if(isNaN(tarjeta)){ tarjeta = 0; $('#tarjeta').val(0); }  
      if(tarjeta == '') { tarjeta = 0; }
      if(tarjeta == 'NULL') { tarjeta = 0; }  
      if(tarjeta == 'NaN') { tarjeta = 0; }  
      tarjeta = tarjeta.replace('$ ','');
      tarjeta = tarjeta.replace(',','.');     
      tarjeta = parseFloat(tarjeta);
      if(isNaN(tarjeta) || tarjeta == ''){ tarjeta = 0; $('#tarjeta').val(0); }     */  
      /* MONTO A PAGAR */        
/*      var monto = $('#mtotal').text();
      if(monto == '') { monto = 0; }
      if(monto == 'NULL') { monto = 0; }  
      if(monto == 'NaN') { monto = 0; }  
      monto = monto.replace('$ ','');
      monto = monto.replace(',','.');     
      monto = parseFloat(monto);

      total = efectivo + tarjeta;

      dif = total - monto;
      dif = parseFloat(dif);
      dif = dif.toFixed(2);

      if(isNaN(total)){ total = 0; }
    //  if(isNaN(dif)){ dif = 0; $('#tarjeta').val(0); }

      if(total >= monto){
        $('#cambio').html('<span style="color: green;"><strong>$ '+dif+'</strong></span>');
        $("#pagar").attr("disabled", false);
      }else{
        $('#cambio').html('<span style="color: red;"><strong>$ 0.00</strong></span>');
        $("#pagar").attr("disabled", true); 
      }

      $('#totalfp').html('<strong>$ '+total+'</strong>');
    });    */





    /* ELIMINAR DATOS DEL CLIENTE */
    $(document).on('click', '.del_cliped', function(){  
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url('Facturar/elim_cliente');?>",
        success: function(json) {
          $('#txt_nro_ident').val('');
          $('#txt_idcli').val('');
          $('#txt_clinom').val('');
          $('#mennom').attr('class','form-group col-md-12 ');
          $('#menid').attr('class','form-group col-md-12 ');
          $('#cmb_mesero').val("0");
          $('#txt_telf').val('');
          $('#txt_correo').val('');
          $('#txt_dir').val('');
        }
      });
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
          url: "<?php echo base_url('Facturar/valcliente');?>",
          data: {
              idcliente: idcliente
           },
          success: function(json) {
            if(json.mens == null){ 
              alert("Este Cliente no se Encuentra Registrado"); 
              $('#menid').attr('class','form-group col-md-12 has-error');
              $('#mennom').attr('class','form-group col-md-12 has-error');
            }
            else { 
              $('#txt_clinom').val(json.mens.nom_cliente);
              $('#txt_idcli').val(json.mens.id_cliente);
              if(json.mens.id_cliente == 0){
                $(".evapago").html("<input type='text' class='form-control validate[required] text-center' id='notaventa' name='notaventa' value='<?php if(@$nronv != NULL){ print @$nronv; }?>'>");
                $(".cmbforma").html("<select id='cmb_forma' name='cmb_forma' class='form-control'><option value='3' selected='TRUE'>Nota de Venta</option><option value='2'>Factura</option></select>");
              }else{
                $(".evapago").html("<input type='text' class='form-control validate[required] text-center' id='factura' name='factura' disabled='' value='<?php if(@$nrofactura != NULL){ print @$nrofactura; }?>'>");
                $(".cmbforma").html("<select id='cmb_forma' name='cmb_forma' class='form-control'><option value='2' selected='TRUE'>Factura</option><option value='3'>Nota de Venta</option></select>");
              }  
              $('#mennom').attr('class','form-group col-md-12 has-success');
              $('#menid').attr('class','form-group col-md-12 has-success');
              $('#txt_telf').val(json.mens.telefonos_cliente);
              $('#txt_correo').val(json.mens.correo_cliente);
              $('#txt_dir').val(json.mens.direccion_cliente);           
              registrar_cliente();
            }
          }
      });


    });

    /* BUSQUEDA DINAMICA POR NOMBRE */

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
          url: "<?php echo base_url('Facturar/busca_nombre');?>",
          data: {
              nom: nom
           },
          success: function(json) {

              $('#txt_nro_ident').val(json.mens.ident_cliente);
              $('#txt_idcli').val(json.mens.id_cliente);
              if(json.mens.id_cliente == 0){
                $(".evapago").html("<input type='text' class='form-control validate[required] text-center' id='notaventa' name='notaventa' value='<?php if(@$nronv != NULL){ print @$nronv; }?>'>");
                $(".cmbforma").html("<select id='cmb_forma' name='cmb_forma' class='form-control'><option value='3' selected='TRUE'>Nota de Venta</option><option value='2'>Factura</option></select>");
              }else{
                $(".evapago").html("<input type='text' class='form-control validate[required] text-center' id='factura' name='factura' disabled='' value='<?php if(@$nrofactura != NULL){ print @$nrofactura; }?>'>");
                $(".cmbforma").html("<select id='cmb_forma' name='cmb_forma' class='form-control'><option value='2' selected='TRUE'>Factura</option><option value='3'>Nota de Venta</option></select>");
              }              
              $('#mennom').attr('class','form-group col-md-12 has-success');
              $('#menid').attr('class','form-group col-md-12 has-success');
              $('#txt_telf').val(json.mens.telefonos_cliente);
              $('#txt_correo').val(json.mens.correo_cliente);
              $('#txt_dir').val(json.mens.direccion_cliente);
              registrar_cliente();

          }
      });

    });


    /* FUNCION QUE GUARDA EN BASE DE DATOS DESPUES DE LA BUSQUEDA */
    function registrar_cliente(){
      var id = $('#txt_idcli').val();

    //  var mesero = $('#cmb_mesero').val(); 
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "Facturar/reg_cliente",
            data: { idcli:id },
            success: function(json) {
            }
        });
    }

      /* AGREGAR CLIENTE 
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
      });*/

 
  }); 

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-list-alt"></i> Listado de Ventas
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>facturar/ventas">Ventas</a></li>
      
    </ol>
  </section>
  
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border" style="padding-bottom: 0px;">  
            <!-- TIPO DE COMPROBANTE -->
            <div class="col-md-3">
              <label for=""><i class="fa fa-archive"></i> Tipo de Comprobante </label> 
              <div style="margin-bottom: 0px;"class="form-group cmbforma" >
                <select id="cmb_forma" name="cmb_forma" class="form-control">
                  <?php if($cliente != NULL){ ?>
                  <?php if($cliente->id_cliente == 0){ ?>
                      <option value="3" selected="TRUE">Nota de Venta</option>
                      <option value="2">Factura</option>
                  <?php  } else {?>
                      <option value="3">Nota de Venta</option> 
                      <option value="2" selected="TRUE">Factura</option>    
                  <?php  } 
                      }
                  ?>
                  <?php  if(@$cliente == NULL){ ?>
                   <option value="2">Factura</option>
                   <option value="3">Nota de Venta</option>   
                  <?php  } ?>
                </select>                                                  
              </div>
            </div>
            <!-- NRO DE FACTURA -->
            <div class="form-group col-md-2">
              <label id="nomfact">Nro Factura</label>
                <div class="evapago">
                  <?php  if($cliente->id_cliente != 0){ ?>
                  <input type="text" class="form-control validate[required] text-center" id="factura" name="factura" disabled="" value="<?php if(@$nrofactura != NULL){ print @$nrofactura; }?>">
                   <?php  } else {?>
                  <input type="text" class="form-control validate[required] text-center" id="notaventa" name="notaventa" value="<?php if(@$nronv != NULL){ print @$nronv; }?>">
                  <?php  } ?>                  
                </div>

            </div>
            <!-- FECHA DE FACTURA -->
            <div class="form-group col-md-2">
              <label for="">Fecha</label>
              <div style="margin-bottom: 0px;"class="form-group" >
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" class="form-control pull-right validate[required]" id="fecha" name="fecha" disabled="true" value="<?php if(@$factura != NULL){ @$fec = str_replace('-', '/', @$factura->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec; }?>">
                </div>                             
              </div>
            </div>    
            <!-- AREA Y MESA -->
            <div class="col-md-4 ">
              <div class="col-xs-10 pull-right" style="margin-top: 18px;">
                <input type="hidden" id="txt_idventa" name="txt_idventa" value="<?php print @$factura->id_venta; ?>" > 
                <input type="hidden" id="txt_idmesa" name="txt_idmesa" value="<?php // print @$idmesa; ?>" > 
                <input type="hidden" id="txt_nomesa" name="txt_nomesa" value="<?php // print @$area; ?>" > 
                <input type="hidden" id="txt_mesero" name="txt_mesero" value="<?php // print @$factura->mesero; ?>" > 
                <span><strong>Punto: </strong><?php print @$factura->mesa; ?></span><br>
                <span><strong>Vendedor: </strong><?php print @$factura->mesero; ?></span><br>
                <span><strong>Documento: </strong><?php print @$factura->nro_factura; ?></span>
              </div>
            </div>
            <div class="col md-1 pull-right">
              <a id="<?php //print @$idmesa; ?>" class="btn btn-block btn-danger volver" style="margin-top: 2px;"><i class="fa fa-list-alt fa-3x"></i></a>
            </div>              
          </div>
          <div class="box-body">
            <div class="row header">
              <div class="col-sm-12">
                <div class="well col-sm-12">
                  <div class="row">
                    <div class="col-md-4">

                      <div id="menid" class="form-group col-md-12">
                          <label for="lb_res">Nro de Identificación</label>
                          <input type="hidden" id="txt_idcli" name="txt_idcli" value="<?php //if(@$climesa != NULL){ print @$climesa->id_cliente; }?>" >    
                          <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$cliente != NULL){ print @$cliente->ident_cliente; }?>" >
                      </div>

                      <div id="mennom"class="form-group col-md-12 autocomplete">
                          <label for="lb_nom">Nombre del Cliente</label>
                          <input type="text" class="form-control " name="txt_clinom" id="txt_clinom" placeholder="Nombre del Cliente" value="<?php if(@$cliente != NULL){ print @$cliente->nom_cliente; }?>" data-source="<?php echo base_url('facturar/valclientenombre?nombre=');?>">
                      </div>
                      <div>
                      <!--  <a style="margin-left: 15px; position: relative;" class="btn btn-success btn-sm add_cli" href="#" data-original-title="" title=""><i class="fa fa-user"></i> Añadir Cliente </a> -->
                      </div>                        
                    </div>

                    <div class="col-md-8">

                      <div id="" class="form-group col-md-4">
                        <label for="lb_telf">Teléfono</label>
                        <input type="text" class="form-control col-md-3" name="txt_telf" id="txt_telf" placeholder="Telefono" value="<?php if(@$cliente != NULL){ print @$cliente->telefonos_cliente; }?>" >
                      </div>

                      <div id="" class="form-group col-md-8">
                        <label for="lb_correo">Correo</label>
                        <input type="text" class="form-control col-md-3" name="txt_correo" id="txt_correo" placeholder="Correo" value="<?php if(@$cliente != NULL){ print @$cliente->correo_cliente; }?>" >
                      </div>

                      <div id="" class="form-group col-md-12">
                        <label for="lb_correo">Dirección</label>
                        <input type="text" class="form-control col-md-3" name="txt_dir" id="txt_dir" placeholder="Correo" value="<?php if(@$cliente != NULL){ print @$cliente->direccion_cliente; }?>" >
                      </div>

                      <div class="pull-right">
                      <!--  <a class="btn btn-danger btn-sm del_cliped" href="#" data-original-title="" title="" style="margin-right: 15px;"><i class="fa fa-trash"></i> Borra Datos </a> -->
                      </div>
                      
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <div class="col-md-12">
              <hr class="linea"> 
            </div>      
            <div id="detfactura" class="col-md-12 table-responsive" >
              <table class="table table-striped table-responsive detfactura">
                <thead>
                  <tr>
                    <th class="text-center col-md-1">Nro</th>
                    <th>Nombre</th>
                    <th class="text-center col-md-1">Cantidad</th>
                    <th class="text-center col-md-1">Precio</th>
                    <th class="text-center col-md-1">SubTotal</th>                     
                    <th class="text-center col-md-1">SubTotal Desc</th>
                  </tr>
                </thead>   
                <tbody>
                  <?php 

                  $apiva = 0; $nro = 0;  $descmonto = 0; $descsubtotal = 0;
                  $iva = 0.12; $valiva = 0; $montoiva = 0; $prosub = 0;
                  $stciva = 0; $stsiva = 0; $dstciva = 0;  $dstsiva = 0;
                  $subtotal = 0; $total = 0;
                  if(@$facturadet != NULL){
                    if (count($facturadet) > 0) {
                      foreach ($facturadet as $fd) {
                        $nro = $nro + 1;

                  ?>
                        <tr>
                          <td class="text-center"><?php print $nro; ?></td>
                          <td class="text-left"><?php print @$fd->pro_nombre; ?></td>
                          <td class="text-center"><?php print @$fd->cantidad; ?></td>
                          <td class="text-right">$<?php print number_format(@$fd->precio,2,",","."); ?></td>
                          <td class="text-right">$<?php print number_format(@$fd->subtotal,2,",","."); ?></td>
                          <td class="text-right">$<?php print number_format(@$fd->descsubtotal,2,",","."); ?></td>
                        </tr>
                <?php  
                      }
                    }
                  }    
                ?>               
                </tbody>
              </table>
            </div>
            <div class="col-md-12">
              <hr class="linea"> 
            </div>

            <div class="row">

              <div class="col-md-6">

              </div>

              <div class="col-md-6">
                <div id="calmonto" class="pull-right" style="margin-right: 10px;">
                  <table class="table table-clear calmonto" >
                    <tbody>
                      <tr>
                        <td class="text-left"><strong>Subtotal IVA 12 %</strong></td>
                        <td id="msubtotalconiva" class="text-right">$<?php print number_format(@$factura->subconiva,2,",","."); ?></td>                                        
                      <tr>
                      <tr>
                        <td class="text-left"><strong>Subtotal IVA 0 %</strong></td>
                        <td id="msubtotalsiniva" class="text-right">$<?php print number_format(@$factura->subsiniva,2,",","."); ?></td>                                        
                      <tr>
                      <tr>
                        <td class="text-left"><strong>Descuento</strong></td>
                        <td id="" class="text-right">$<?php print number_format(@$factura->desc_monto,2,",","."); ?></td>                                        
                      </tr>
                      <tr>
                        <td class="text-left"><strong>Subtotal con Descuento IVA 12 %</strong></td>
                        <td id="descsubiva" class="text-right">$<?php print number_format(@$factura->descsubconiva,2,",","."); ?></td>                                        
                      </tr>
                      <tr>
                        <td class="text-left"><strong>Subtotal con Descuento IVA 0 %</strong></td>
                        <td id="descsub" class="text-right">$<?php print number_format(@$factura->descsubsiniva,2,",","."); ?></td>                                        
                      </tr>
                      <tr>
                        <td class="text-left"><strong>IVA (12%)</strong></td>
                        <td id="miva" class="text-right">$<?php print number_format(@$factura->montoiva,2,",","."); ?></td>                                        
                      </tr>
                      <tr>
                        <td class="text-left"><strong>Total</strong></td>
                        <td id="mtotal" class="text-right"><strong>$ <?php print number_format(@$factura->montototal,2,",","."); ?></strong></td>                                        
                      </tr>      
                    </tbody>
                  </table>
                </div>                
              </div>
            </div>


            
          </div>  
          <div class="box-footer text-right">
            <div class="row no-print ">
            <div class="col-md-12">

              <button id="imprimir" type="button" class="btn btn-primary btn-grad" >
                <i class="fa fa-print"></i> Imprimir
              </button>   
                       
              <button id="pagar" type="button" class="btn btn-success btn-grad" >
                <i class="fa fa-credit-card"></i> Guardar
              </button>
            </div>
            

            <!--   <div class=" ">
                <button  class="btn btn-block btn-success" type="button"><i class="fa fa-credit-card"></i> Pagar</button>

               <a id="pagar" href="#"  class="btn btn-success "><i class="fa fa-credit-card"></i> Pagar</a> 
                <a id="imprimir" href="#"  class="btn btn-primary " ><i class="fa fa-print"></i> Imprimir</a>                
              </div>-->
            </div>
          </div>

        </div>        
      </div>



    </div>
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

<?php 
/*


            <!--
            <div class="col-md-1">
              <label for="">Nro Pedido</label>
              <div style="margin-bottom: 0px;"class="form-group" >
                <input type="text" class="form-control" name="nropedido" id="nropedido" placeholder = "Nro Pedido" >                              
              </div>
            </div>-->

     
<!--
                <div class="col-lg-5 col-sm-6 formapago well">
                  <div class="col-xs-8">
                    <h3 style="margin-top: -2px;">Forma de Pago</h3>
                  </div>
                  <div class="col-xs-4 text-right"> 
                    <a href="#" title="Agregar" id="Agregar" class="btn btn-success btn-grad tipopago"><i class="fa fa-plus"></i> Agregar</a>
                  </div> 
                  <div class="col-md-12">
                    <hr class="linea"> 
                  </div>
                  
                  <div class="box-body">
                    <table id="tbtipopago" class="table table-bordered ">
                      <tr>
                        <th class="text-center" style="width: 10px">Nro</th>
                        <th class="text-left">Tipo de Pago</th>
                        <th class="text-center"style="width: 10px">Monto</th>
                        <th class="text-center" style="width: 40px">Acción</th>
                      </tr>
                      <tr>
                        <td class="text-center">1</td>
                        <td><i class="fa fa-money" aria-hidden="true"></i> Efectivo</td>
                        <td>$25,32</td>
                        <td>
                          <div class="text-center">
                            <a href="#" title="Eliminar" id="" class="btn btn-danger btn-xs btn-grad alm_del"><i class="fa fa-trash-o"></i></a>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="text-center">2</td>
                        <td><i class="fa fa-credit-card" aria-hidden="true"></i> Tarjeta de Débito</td>
                        <td>$30,02</td>
                        <td>
                          <div class="text-center">
                            <a href="#" title="Eliminar" id="" class="btn btn-danger btn-xs btn-grad alm_del"><i class="fa fa-trash-o"></i></a>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="text-center">3</td>
                        <td><i class="fa fa-credit-card-alt" aria-hidden="true"></i> Tarjeta de Crédito</td>
                        <td>$12,00</td>
                        <td>
                          <div class="text-center">
                            <a href="#" title="Eliminar" id="" class="btn btn-danger btn-xs btn-grad alm_del"><i class="fa fa-trash-o"></i></a>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th colspan="2" class="text-right">Total</th>
                        <td><strong>$12,00</strong></td>
                        <td></td>
                      </tr>

                    </table>
                  </div>
                </div>/col-->

               <!-- <div class="col-lg-4 col-lg-offset-3 col-sm-5 col-sm-offset-2 ">
                  <table class="table table-clear">
                    <tbody>
                      <tr>
                        <td class="text-left"><strong>Subtotal</strong></td>
                        <td class="text-right">$<?php //print number_format(@$subtotal,2,",","."); ?></td>                                        
                      </tr>
                      <tr>
                        <td class="text-left"><strong>IVA (12%)</strong></td>
                        <td class="text-right">$<?php //print number_format(@$iva,2,",","."); ?></td>                                        
                      </tr>
                      <tr>
                        <td class="text-left"><strong>Total</strong></td>
                        <td class="text-right"><strong>$<?php //print @$total; ?></strong></td>                                        
                      </tr>                                  
                    </tbody>
                  </table>
                  <a href="page-invoice.html#" class="btn btn-info" onclick="javascript:window.print();"><i class="fa fa-print"></i> Imprimir</a>
                  <a href="page-invoice.html#" class="btn btn-success"><i class="fa fa-usd"></i> Pagar</a>
                </div>/col-->

    <!-- DATOS DEL CLIENTE -->        
      <div class="col-md-12">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-user"></i> Datos del Cliente </h3> 
                  <div class="pull-right"> 
                    <div style="margin-bottom: 0px;"class="form-group" >
                      <select id="cmb_mesero" name="cmb_mesero" class="form-control">
                          <?php 
                            if(@$mesero != NULL){ ?>
                            <option  value="" selected="TRUE">Seleccione Mesero...</option>
                          <?php } else { ?>
                              
                          <?php } 
                                    if (count($mesero) > 0) {
                                      foreach ($mesero as $mero):
                                          if(@$climesa->id_mesero != NULL){
                                              if($mero->id_mesero == $climesa->id_mesero){ ?>
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
                    
                  </div>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-4">
                      <!-- Número de Identificación -->
                      <div id="menid" class="form-group col-md-12">
                          <label for="lb_res">Nro de Identificación</label>
                          <input type="hidden" id="txt_idcli" name="txt_idcli" value="<?php if(@$climesa != NULL){ print @$climesa->id_cliente; }?>" >    
                          <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$climesa != NULL){ print @$climesa->ident_cliente; }?>" >
                      </div>
                      <!-- Nombre del Cliente -->
                      <div id="mennom"class="form-group col-md-12 autocomplete">
                          <label for="lb_nom">Nombre del Cliente</label>
                          <input type="text" class="form-control " name="txt_clinom" id="txt_clinom" placeholder="Nombre del Cliente" value="<?php if(@$climesa != NULL){ print @$climesa->nom_cliente; }?>" data-source="<?php echo base_url('pedido/valclientenombre?nombre=');?>">
                      </div>
                      <div>
                        <a style="margin-left: 15px; position: relative;" class="btn btn-success btn-sm add_cli" href="#" data-original-title="" title=""><i class="fa fa-user"></i> Añadir Cliente </a> 
                      </div>                        
                    </div>
                    <div class="col-md-8">
                      <table class="table table-bordered detpedido" style="margin-top: 21px;">
                          <tbody>
                              <tr>
                                <th class="text-left col-md-2">Teléfono</th>
                                <td id="cli_telf"><?php if(@$climesa != NULL){ print @$climesa->telefonos_cliente; }?></td>
                              </tr>                          
                              <tr>
                                <th class="text-left col-md-2">Correo</th>
                                <td id="cli_correo"><?php if(@$climesa != NULL){ print @$climesa->correo_cliente; }?></td>
                              </tr>
                              <tr>
                                <th class="text-left col-md-2">Dirección</th>
                                <td id="cli_dir"><?php if(@$climesa != NULL){ print @$climesa->direccion_cliente; }?></td>
                              </tr>

                          </tbody>
                      </table>
                      <div class="pull-right">
                        <a class="btn btn-danger btn-sm del_cliped" href="#" data-original-title="" title=""><i class="fa fa-trash"></i> Borra Datos </a> 
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div>
      </div>

<div class="col-md-12">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-cutlery"></i> Lista de Pedidos </h3>
                  <div class="pull-right"> 
                    <a class="btn bg-orange-active color-palette btn-grad add_producto" href="#" data-original-title="" title=""><i class="fa fa-shopping-bag"></i> Añadir Producto </a>
                  </div>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div id="detpedido" class="col-md-12" > 
                      <table class="table table-bordered detpedido">
                        <tbody>
                          <tr>
                              <th class="text-center col-md-1">Nro</th>
                              <th class="text-center col-md-1">Cantidad</th>
                              <th>Nombre</th>
                              <th class="text-center col-md-1">Precio</th>
                              <th class="text-center col-md-1">SubTotal</th>
                              <th class="text-center">Estado</th>
                              <th class="text-center col-md-1">Acción</th>
                          </tr>
                          <?php 
                          $nro = 0;
                          $total = 0;
                          $subtotal = 0;
                          if(@$detmesa != NULL){
                              if (count($detmesa) > 0) {
                                  foreach ($detmesa as $dm):
                                      $nro = $nro + 1;
                                      $subtotal = $dm->cantidad * $dm->precio;
                                      $total = $total + $subtotal; 

                          ?>
                          <tr>
                              <!-- NRO -->
                              <td class="text-center"><?php print $nro; ?></td>
                              <!-- CANTIDAD -->
                              <td class="text-center">
                                <input type="text" class="form-control text-center cantidad" name="<?php if(@$dm != NULL){ print @$dm->pro_nombre; }?>" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" value="<?php print $dm->cantidad; ?>" >
                              </td>
                              <!-- NOMBRE DEL PRODUCTO -->
                              <td><?php print $dm->pro_nombre; ?></td>
                              <!-- PRECIO DEL PRODUCTO -->
                              <td id="<?php print @$dm->id_producto; ?>" class="text-right producto"><?php print $dm->precio; ?></td>
                              <!-- SUBTOTAL -->
                              <td name="<?php print @$dm->pro_nombre; ?>" class="text-right ">
                                <div id="<?php print @$dm->id_producto; ?>">
                                  <?php 
                                    $subtotal = $dm->cantidad * $dm->precio;
                                    print $subtotal; 
                                  ?>
                                </div>
                                
                              </td>
                              <!-- ESTATUS -->
                              <td class="text-center"><?php print $dm->estatus; ?></td>
                              <!-- ACCION -->
                              <td class="text-center">
                                <?php 
                                  if($dm->variante == 1){ ?>
                                    <a href="#" title="Variantes del Producto" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" class="btn btn-warning btn-xs btn-grad pedpro_var"><i class="fa fa-plus"></i></a>    
                                <?php }
                                ?>
                                <a href="#" title="Eliminar" id="<?php if(@$dm != NULL){ print @$dm->id_producto; }?>" class="btn btn-danger btn-xs btn-grad pedpro_del"><i class="fa fa-trash-o"></i></a>
                              </td>
                          </tr>
                          <?php
                                  endforeach;
                              }
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
                  <h3>MONTO: <?php print $total; ?> $</h3>
                </div>
              </div>
          <!-- FIN DE ESPACIO DE LAS AREAS -->
      </div> 


  <!-- Content Header (Page header) 
  <section class="content-header">
    <h1>
      <i class="fa fa-credit-card"></i> Facturación
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php //print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class=""><a href="<?php //print $base_url ?>pedido">Pedido</a></li>
      <li class="active"><a href="<?php //print $base_url ?>facturar">Facturar</a></li>
      
    </ol>
  </section>-->
  <!-- Main content -->





            <!-- LINEA DE SEPARACION 
            <div class="col-xs-12">
              <hr class="linea">
            </div>-->







          <!--  <div class="col-md-3">
              <label for=""><i class="fa fa-archive"></i> Mesas </label> 
              <div style="margin-bottom: 0px;"class="form-group" >
                <select id="cmb_mesas" name="cmb_mesas" class="form-control">
                <?php 
                //  if(@$areamesa != NULL){ ?>
                    <option  value="" selected="TRUE">Seleccione Mesa...</option>
                <?php // }  
                  //  if (count($areamesa) > 0) {
                  //    foreach ($areamesa as $arme):
                  //        if(@$climesa->id_mesa != NULL){
                  //            if($arme->id_mesa == $climesa->id_mesa){ ?>
                                   <option value="<?php // print $arme->id_mesa; ?>" selected="TRUE"> <?php // print $arme->mesas ?> </option>
                                  <?php
                  //            }else{ ?>
                                  <option value="<?php // print $arme->id_mesa; ?>" > <?php // print $arme->mesas ?> </option>
                                  <?php
                            //  }
                  //        }else{ ?>
                              <option value="<?php // print $arme->id_mesa; ?>"> <?php // print $arme->mesas ?> </option>
                              <?php
                           //   }   ?>
                          <?php
                    //  endforeach;
                  //  }
                ?>
                </select>                                                  
              </div>
            </div>-->

           <!--  <div class="col-md-3 pull-right">
             <label for=""><i class="fa fa-user"></i> Mesero</label>  
              <div style="margin-bottom: 0px;"class="form-group" >
                <input type="text" class="form-control" name="txt_mesonero" id="txt_mesonero" placeholder = "Mesero" value="<?php // print @$climesa->nom_mesero; ?>">                              
              </div>
            </div>   -->      

*/
?>