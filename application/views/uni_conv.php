<?php
/* ------------------------------------------------
  ARCHIVO: Unidades de Medida.php
  DESCRIPCION: Contiene la vista principal del módulo de unidades de medida.
  FECHA DE CREACIÓN: 10/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Unidades'</script>";
date_default_timezone_set("America/Guayaquil");
?>

<script type='text/javascript' language='javascript'>

    $(document).ready(function () {
      /* AGREGAR FCTOR DE CONVERSION A UNIDAD DE MEDIDA */
      $(document).on('click', '.add_fc', function(){
          id = $(this).attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('unidades/tmp_fact');?>",
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
                href: "<?php echo base_url('unidades/add_fac_conv');?>" 
              });
           }
        });
      })

      /* EDITAR FACTOR DE CONVERSION A UNIDAD DE MEDIDA */
      $(document).on('click', '.edi_fc', function(){
          uni = $(this).attr('id');
          id = $(".add_fc").attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('unidades/tmp_fact');?>",
           data: {id: id, uni: uni},
           success: function(json) {
              $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST"
                },
                href: "<?php echo base_url('unidades/edi_fac_conv');?>" 
              });
           }
        });
      })


      /* ELIMINAR FACTOR DE CONVERSION A UNIDAD DE MEDIDA */
      $(document).on('click', '.del_fc', function(){
          uni = $(this).attr('id');
          id = $(".add_fc").attr('id');
          $.ajax({
           type: "POST",
           dataType: "json",
           url: "<?php echo base_url('unidades/tmp_fact');?>",
           data: {id: id, uni: uni},
           success: function(json) {
              $.fancybox.open({
                type: "ajax",
                width: 550,
                height: 550,
                ajax: {
                   dataType: "html",
                   type: "POST"
                },
                href: "<?php echo base_url('unidades/del_fac_conv');?>" 
              });
           }
        });
          
      })


    }); 

</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-balance-scale"></i> Unidades - Factor de Conversión
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="<?php print $base_url ?>unidades">Unidad de Medidas</a></li>
        <li class="active"><a href="<?php print $base_url ?>unidades">Factor de Conversión</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title">Factor de Conversión</h3>
  
                    </div>
                    <div class="box-body">

                      <div class="row">
                        <div class="col-xs-2 ">

                        </div>

                        <div class="col-xs-8">
                          <div class="box">
                            <div class="box-header with-border">
                              
                              <h3 class="box-title"></i> Unidad de Medida: <strong><spam class="text-green"><?php if(@$uni != NULL){ print @$uni->descripcion; }?> - <?php if(@$uni != NULL){ print @$uni->nombrecorto; }?></spam></strong></h3>
                              <div class="pull-right"> 
                                <button id="<?php if(@$uni != NULL){ print @$uni->id; }?>" type="button" class="btn btn-success btn-grad add_fc" >
                                  <i class="fa fa-plus-square"></i> Añadir
                                </button>
                              </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">

                              <table class="table table-bordered">
                                <tr>
                                  <th style="width: 10px">#</th>
                                  <th>Unidad Equivalente</th>
                                  <th>Cantidad</th>
                                  <th style="width: 40px">Acción</th>
                                </tr>
                                <?php
                                  $nro = 0;
                                  if (count($fcl) > 0) {
                                    foreach ($fcl as $fc):
                                      $nro = $nro + 1;
                                 ?>   <tr>
                                        <td><?php print $nro; ?></td>
                                        <td><?php print $fc->descripcion ?></td>
                                        <td><?php print number_format($fc->cantidadequivalente,2,",",".")." - ".strtolower($fc->nombrecorto)  ?></td>
                                        <td>
                                          <div class="text-center">
                                            <a href="#" title="Ver" id="<?php print $fc->idunidadequivale ?>" class="btn btn-success btn-xs btn-grad edi_fc"><i class="fa fa-pencil-square-o"></i></a> 
                                            <a href="#" title="Eliminar" id="<?php print $fc->idunidadequivale ?>" class="btn btn-danger btn-xs btn-grad del_fc"><i class="fa fa-trash-o"></i></a>
                                          </div>
                                        </td>
                                      </tr>
                                <?php
                                    endforeach;
                                  }
                                ?>
                              </table>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix text-center">
                              <h4 style="margin-top: 10px;">Listado de Factores de Conversión</h4>
                            </div>
                          </div>
                        </div>
                        <div class="col-xs-2">

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

