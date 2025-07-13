<?php
$nombresistema = $this->session->userdata("nombresistema");
  print "<script>document.title = '$nombresistema - Garantia'</script>";
  date_default_timezone_set("America/Guayaquil");
?>
<style type="text/css">

  .fontawesome-select {
      font-family: 'FontAwesome', 'Helvetica';
  }
  .tomar{
    background: rgba(0, 0, 0, 0.2) none repeat scroll 0 0;
    border-radius: 2px 0 0 2px;
    display: block;
    float: left;
    height: 90px;
    text-align: center;
    width: 90px;    
  }
  .form-control, .input-group-addon{
    height: 25px;
    padding: 0px 5px;
    font-size: 11px;      
  }
  label{
    margin-bottom: 0px;
    font-size: 12px;
  }
  .box-header{
    padding-bottom: 0px;
  }
  .linea{
    border-width: 2px 0 0;
    margin-bottom: 20px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 
  .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
    padding: 4px;
    font-size: 12px;
  }


</style>
<script type='text/javascript' language='javascript'>

  $(document).ready(function () {

  }); 

</script>

<div class="content-wrapper">
  <section class="content-header">
    <h1 id="" name="">
      <i class="fa fa-star-half-empty"></i> Garantia de Productos
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active"><a href="<?php print $base_url ?>garantia">Garantia</a></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">

            <div class="col-md-3" style="margin-bottom: 5px;">
              <div class="form-group col-md-12 " style="margin-bottom: 5px;" >
                <label id="">Sucursal</label>
                <div class="sucursal">
                  <select id="cmb_sucursal" name="cmb_sucursal" class="form-control ">
                  <?php 
                    if(@$sucursal != NULL){ ?>
                  <?php } else { ?>
                      <option  value="0" selected="TRUE">Seleccione...</option>
                  <?php } 
                            if (count($sucursal) > 0) {
                              foreach ($sucursal as $s):
                                  if(@$cliente->id_sucursal != NULL){
                                      if($s->id_sucursal == $cliente->id_sucursal){ ?>
                                          <option  value="<?php  print $s->id_sucursal; ?>" selected="TRUE"><?php  print $s->nom_sucursal; ?></option> 
                                          <?php
                                      }else{ ?>
                                          <option value="<?php  print $s->id_sucursal; ?>"> <?php  print $s->nom_sucursal; ?> </option>
                                          <?php
                                      }
                                  }else{ ?>
                                      <option value="<?php  print $s->id_sucursal; ?>"> <?php  print $s->nom_sucursal; ?> </option>
                                      <?php
                                      }   ?>
                                  <?php

                              endforeach;
                            }
                            ?>
                  </select>                  
                </div>
              </div>                
              <div class="form-group col-md-6" style="margin-bottom: 5px;">
                <label>Fecha</label>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input class="form-control pull-right validate[required] " id="fecha" name="fecha" value="<?php if(@$cliente != NULL){ @$fec = str_replace('-', '/', @$cliente->fecha); @$fec = date("d/m/Y", strtotime(@$fec)); print @$fec; }else{ $fec = date("d/m/Y"); print @$fec;} ?>" type="text" disabled>
                </div>
              </div>
              <div class="form-group col-md-6" style="margin-bottom: 5px;">
                <label id="nropro">Nro Proforma</label>
                <div class="">
                  <input type="hidden" id="txt_idprof" name="txt_idprof" value="<?php if(@$idproforma != NULL){ print @$idproforma; } else{ print 0; }?>" >
                  <input type="text" class="form-control validate[required] text-center" id="nro_proforma" name="nro_proforma" disabled="" value="<?php if(@$cliente->nro_proforma != NULL){ print @$cliente->nro_proforma; }?>">
                </div>
              </div>
            </div>
            <div class="col-md-9" style="padding-left: 0px; margin-bottom: 5px;">

              <?php if(@$codigocliente == 1){ ?>
              <div id="menidcod" class="form-group col-md-1" style="padding-left: 0px; padding-right: 0px; margin-bottom: 5px;">
                <label >Codigo Cliente</label>
                <div class="">
                  <input type="text" class="form-control validate[required]" name="txt_codigocliente" id="txt_codigocliente" placeholder="Codigo Cliente" value="<?php if(@$cliente != NULL){ print @$cliente->codigo; }?>" >
                </div>
              </div> 
              <?php } ?>

              <div class="form-group col-md-2 " style="margin-bottom: 5px;" >
                <label id="">Tipo Ident</label>
                <div class="tipident">
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
              <div id="menid" class="form-group col-md-2" style="margin-bottom: 5px;">
                <label id="nroident">Nro Identificación</label>
                <div class="">
                  <input type="hidden" id="txt_clid" name="txt_clid" value="<?php if(@$cliente != NULL){ print @$cliente->id_cliente; }?>" >
                  <input type="text" class="form-control validate[required]" name="txt_nro_ident" id="txt_nro_ident" placeholder="Nro ID" value="<?php if(@$cliente != NULL){ print @$cliente->ident_cliente; }?>" >
                </div>
              </div> 
              <div class="form-group col-md-3" style="margin-bottom: 5px;">
                <label id="nroident">Cliente</label>
                <div id="mennom" class="autocomplete" style="padding-right: 0px;">
                  <input type="text" class="form-control " name="txt_clinom" id="txt_clinom" placeholder="Nombre del Cliente" value="<?php if(@$cliente != NULL){ print @$cliente->nom_cliente; }?>" data-source="<?php echo base_url('facturar/valclientenombre?nombre=');?>">
                </div>
              </div> 
              <div class="form-group col-md-2" style="margin-bottom: 5px;">
                <label>Teléfono</label>
                <div class="">
                  <input type="text" class="form-control guarda_cliente text-center" name="txt_telf" id="txt_telf" placeholder="Telefono" value="<?php if(@$cliente != NULL){ print @$cliente->telefonos_cliente; }?>" >
                </div>
              </div> 
              <?php if(@$codigocliente == 1){ ?>
                  <div class="form-group col-md-2" style="margin-bottom: 5px;">
              <?php } else { ?>
                  <div class="form-group col-md-3" style="margin-bottom: 5px;">
              <?php } ?>
                <label>Ciudad</label>
                <div class="">
                  <input type="text" class="form-control guarda_cliente" name="txt_ciudad" id="txt_ciudad" placeholder="Ciudad" value="<?php if(@$cliente != NULL){ print @$cliente->ciudad_cliente; }?>" >
                </div>
              </div> 
              <div class="form-group col-md-2" style="margin-bottom: 5px;">
                <label>Correo</label>
                <div class="">
                  <input type="text" class="form-control col-md-3 guarda_cliente" name="txt_correo" id="txt_correo" placeholder="Correo" value="<?php if(@$cliente != NULL){ print @$cliente->correo_cliente; }?>" >
                </div>
              </div>               
              <div class="form-group col-md-5" style="margin-bottom: 5px;">
                <label>Dirección</label>
                <div class="">
                  <input type="text" class="form-control guarda_cliente" name="txt_dir" id="txt_dir" placeholder="Dirección" value="<?php if(@$cliente != NULL){ print @$cliente->direccion_cliente; }?>" >
                </div>
              </div> 
              <div class="col-md-2" style="margin-bottom: 5px; padding-left: 0px; padding-right: 0px;">
                <div class="form-group col-md-12" style="margin-bottom: 5px; padding-left: 0px;">
                  <label>Nro Documento</label>
                  <div class="col-md-12 evapago" style="padding-right: 0px; padding-left: 0px;">
                    <input type="text" class="form-control validate[required] text-center" id="factura" name="factura" disabled="" value="<?php // if(@$nrofactura != NULL){ print @$nrofactura; }?>">
                  </div>   
                </div>
              </div> 
            </div>

          </div>
          <div class="box-body">


          </div>
          <div class="box-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </div>           
    </div>
  </section>

</div>