<?php
/* ------------------------------------------------
  ARCHIVO: usuarios.php
  DESCRIPCION: Contiene la vista principal del módulo de usuarios.
  FECHA DE CREACIÓN: 30/06/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
print "<script>document.title = 'FACTUFÁCIL - Cierre de Mes'</script>";
date_default_timezone_set("America/Guayaquil");
?>
<style type="text/css">
  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 
   
</style>
<script type="text/javascript">

    $(document).ready(function () {

        $(document).on('click', '.add_catcierre', function(){
            id = $(this).attr('id');
            $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                    dataType: "html",
                    type: "POST",
                    data: {id: id},
                },
                href: "<?php print base_url('Reporte/muestra_categorias');?>" 
            });
        });


        $(document).on('click', '.guardacat', function(){
            var cat = $("#cmb_catc").val();
            var id = $("#txt_id").val();
            $.ajax({
                type: "POST",
                dataType: "json",
                data: {id: id, cat: cat},                
                url: "<?php echo base_url('Reporte/addcat');?>",
                success: function(json) {
                    if(json == 1){
                        $('.mantenimiento').load(base_url + "Reporte/actualiza_mantenimiento");
                    }else{
                        $('.servicio').load(base_url + "Reporte/actualiza_servicio");
                    }
                }
            });            
            $.fancybox.close();
        });


        $(document).on('click', '.del_catgc', function(){
            var cat = $(this).attr('id');
            var id = $(this).attr('name');
            if (confirm("¿Confirma que desea eliminar esta Categoria?")) { 

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: {id: id, cat: cat},                
                    url: "<?php echo base_url('Reporte/delcat');?>",
                    success: function(json) {
                        if(json == 1){
                            $('.mantenimiento').load(base_url + "Reporte/actualiza_mantenimiento");
                        }else{
                            $('.servicio').load(base_url + "Reporte/actualiza_servicio");
                        }
                    }
                });            

            }
        });



    });
</script>

<div class="content-wrapper">

    <section class="content-header">
      <h1> <i class="fa fa-cogs"></i> Parametros del Cierre de Mes </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>reporte">Cierre de Mes</a></li>
      </ol>

    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="pull-right">
                            
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box box-danger">
                                    <div class="box-header with-border">
                                        <div class="pull-left">
                                            <span style="font-size: 20px;"><i class="fa fa-wrench" aria-hidden="true"></i> Mantenimiento </span>
                                        </div>
                                        <div class="pull-right">
                                            <button id="1" class="btn bg-blue color-palette btn-grad add_catcierre" type="button">
                                                <i class="fa fa-plus-square" aria-hidden="true"></i> Añadir Categoría
                                            </button>
                                        </div>                                        
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12 mantenimiento">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th style="width: 10px">Nro</th>
                                                            <th>Categoría</th>
                                                            <th style="width: 40px">Acción</th>
                                                        </tr>
                                                        <?php
                                                        $nro = 0; 
                                                        foreach ($lst as $lm) {
                                                            if($lm->id_parametro == 1){
                                                                $nro = $nro + 1;    
                                                                ?>
                                                                <tr>
                                                                    <td><?php print $nro; ?></td>
                                                                    <td><?php print @$lm->nom_cat_gas; ?></td>
                                                                    <td>
                                                                        <div class="text-center">
                                                                            <a href="#" title="Eliminar" id="<?php print @$lm->id_categoria ?>" name="<?php print @$lm->id_parametro ?>" class="btn btn-danger btn-xs btn-grad del_catgc"><i class="fa fa-trash-o"></i></a>
                                                                        </div>                                                                
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

                                </div>                                
                            </div>
                            <div class="col-md-6">
                                <div class="box box-danger">
                                    <div class="box-header with-border">
                                        <div class="pull-left">
                                            <span style="font-size: 20px;"><i class="fa fa-cubes" aria-hidden="true"></i> Servicios </span>
                                        </div>
                                        <div class="pull-right">
                                            <button id="2" class="btn bg-blue color-palette btn-grad add_catcierre" type="button">
                                                <i class="fa fa-plus-square" aria-hidden="true"></i> Añadir Categoría
                                            </button>
                                        </div>                                          
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12 servicio">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th style="width: 10px">Nro</th>
                                                            <th>Categoría</th>
                                                            <th style="width: 40px">Acción</th>
                                                        </tr>
                                                        <?php
                                                        $nro = 0; 
                                                        foreach ($lst as $ls) {
                                                            if($ls->id_parametro == 2){
                                                                $nro = $nro + 1;    
                                                                ?>
                                                                <tr>
                                                                    <td><?php print $nro; ?></td>
                                                                    <td><?php print @$ls->nom_cat_gas; ?></td>
                                                                    <td>
                                                                        <div class="text-center">
                                                                            <a href="#" title="Eliminar" id="<?php print @$ls->id_categoria ?>" name="<?php print @$ls->id_parametro ?>" class="btn btn-danger btn-xs btn-grad del_catgc"><i class="fa fa-trash-o"></i></a>
                                                                        </div>                                                                
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

                                </div>                                
                            </div>                          
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</div>
