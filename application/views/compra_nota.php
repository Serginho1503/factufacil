<style>
#contenido_pronota{
    width: 600px;
}   
</style>

<script type="text/javascript">
    $(document).ready(function () {
          
    });
</script>
<div id="controller_add_serie">
<div id = "contenido_pronota" class="col-md-6">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"></i> Detalles del Producto </h3>
            <div class="form-actions pull-right">
                <button v-if="div_uno == true" type="button" class="btn btn-warning btn-grad no-margin-bottom" v-on:click="cargar_segunda_pantalla()">
                    <i class="fa fa-list "></i> Agregar Masivo
                </button>
                <button v-if="div_uno == false" type="button" class="btn btn-primary btn-grad no-margin-bottom" v-on:click="cargar_primera_pantalla()">
                    <i class="fa fa-plus"></i> Agregar Uno
                </button>
                <button v-if="div_uno == false" type="button" class="btn btn-info btn-grad no-margin-bottom" v-on:click="actualizar_tabla()">
                    <i class="fa fa-file"></i> Ver Lista
                </button>
            </div>
        </div>
            <div class="box-body" v-if="div_uno == true">
                <div class="row">
                        <input type="hidden" id="txt_idcom" name="txt_idcom" value="<?php print $compdet->id_tmp_comp; ?>" >
                        <input type="hidden" id="txt_iddet" name="txt_iddet" value="<?php print $compdet->id; ?>" >
                        <input type="hidden" id="txt_idprodet" name="txt_idprodet" value="<?php print $compdet->id_pro; ?>" >  
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Serie/IMEI</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="imei" name="imei" placeholder="Nro IMEI" type="text" value="<?php print @$desc; ?>">
                            </div>
                        </div> 
                        <div class="form-group" style="padding-top: 28px;">
                            <label for="" class="col-sm-3 control-label">Descripción</label>
                            <div class="col-sm-9">
                                <textarea id="txt_desc" name="txt_desc" class="form-control" rows="2" placeholder="Ingrese los Detalles ..."><?php print @$desc; ?></textarea>
                            </div>
                        </div>  
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success btn-grad no-margin-bottom addserie pull-right">
                            <i class="fa fa-plus "></i> Guardar
                            </button> 
                        </div>
                        
                       
                    </div> 
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-12 detserie">
                        <table class="table table-bordered detserie table-responsive">
                            <tbody>
                                <tr>
                                    <th class="text-center " style="width: 10px;">Nro</th>
                                    <th class="text-center col-md-1">Imei/Serie</th> 
                                    <th>Descripción</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                                <?php 
                                  $nro = 0;
                                  foreach ($proimei as $pi) {
                                    
                                    if($pi->id_producto == $compdet->id_pro){
                                      $nro++;  
                                ?>    
                                    <tr>
                                        <td class="text-center"><?php print @$nro; ?></td>
                                        <td class="text-center"><?php print @$pi->numeroserie; ?></td>
                                        <td class="text-left"><?php print @$pi->descripcion; ?></td>
                                        <td class="text-center" style="width: 10px;">
                                            <a href="#" title="Eliminar" id="<?php if(@$pi != NULL){ print @$pi->id_serie; }?>" class="btn btn-danger btn-xs btn-grad proser_del"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>  
                                <?php
                                    }              
                                  }
                                ?>

                            </tbody>
                        </table>
                        
                    </div> 
                    
                </div>
            </div>


            <!-- ingreso masivo de carlos -->
            <div class="box-body" v-if="div_dos == true">
                <div class="row">
                    <!--variable que manda en la cabecera ocultas-->
                    <input type="hidden" id="txt_idcom" name="txt_idcom" value="<?php print $compdet->id_tmp_comp; ?>" >
                    <input type="hidden" id="txt_iddet" name="txt_iddet" value="<?php print $compdet->id; ?>" >
                    <input type="hidden" id="txt_idprodet" name="txt_idprodet" value="<?php print $compdet->id_pro; ?>" >

                    <div class="col-md-12">
                        Estructura Inicial:
                        <input type="text" v-model="new_serie.texto+new_serie.inicio" disabled class="form-control">
                    </div>
                    <div class="col-md-4">
                        Serie Texto:
                        <input type="text" class="form-control" placeholder="Texto que Acompaña a la Serie o Imei" title="Texto que Acompaña a la Serie o Imei" v-model="new_serie.texto">
                    </div>
                    <div class="col-md-4">
                        Serie Num. Inicio:
                        <input type="number" class="form-control" placeholder="La Numeración Conitnua el Número Primero" title="La Numeración Conitnua el Número Primero" v-model="new_serie.inicio">
                    </div>
                    <div class="col-md-4">
                        Cantidad:
                        <input type="number" class="form-control" placeholder="La Cantidad de Ingreso a Continuar" title="La Numeración Conitnua el Número Primero" v-model="new_serie.cantidad">
                    </div>
                    <div class="col-md-12">
                        Descripción:
                        <textarea class="form-control" rows="5" placeholder="Descripción de Los Ingreso Masivos en Común" title="Descripción de Los Ingreso Masivos en Común" v-model="new_serie.descripcion"></textarea>
                    </div>
                    <br>
                    <div class="col-md-12">
                        <a class="btn btn-default pull-legth btn-sm">Limpiar Campos</a>
                        <a class="btn btn-success pull-right" v-on:click="guardar_masivos()">Guardar {{new_serie.cantidad}} Registros..</a>
                    </div>

                </div>
            </div>
            <!-- /.box-body -->
            <div  align="center" class="box-footer">

            </div>

    </div>
</div>
</div>
<!-- incluida por carlos zambrano 22-11-2018-->

     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue-resource.min.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/vue-router.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/sweetalert.min.js"></script>
     <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/axios.min.js"></script>

 <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/component/compra_serie.js"></script>
 <script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/sweetalert.min.js"></script>