<?php
/* ------------------------------------------------
  ARCHIVO: producto.php
  DESCRIPCION: Contiene la vista principal del módulo de producto.
  FECHA DE CREACIÓN: 13/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
$nombresistema = $this->session->userdata("nombresistema");
print "<script>document.title = '$nombresistema - Producto'</script>";
date_default_timezone_set("America/Guayaquil");

$parametro = &get_instance();
$parametro->load->model("Parametros_model");
$tarifaiva = $parametro->Parametros_model->iva_get()->valor;
?>

<script type='text/javascript' language='javascript'>
$(document).ready(function () {
    // Inicializar DataTable
    $('#dataTablePro').DataTable({
        'language': {
            'url': base_url + 'public/json/language.spanish.json'
        }
    });

    // Función para generar el código de barras y preparar la impresión
    function generarbarcode(codbar, precio_iva, nompro) {
        // Remover cualquier bloque #print existente para evitar duplicados
        $("#print").remove();

        // Crear el bloque de impresión dinámicamente
        $("body").append(`
            <div id="print" style="position: absolute; width: 4.9cm; height: 2.4cm; border: 1px solid #000; border-radius: 5px; overflow: hidden; box-sizing: border-box; display: none;">
                <div style="position: absolute; top: 7px; left: 50%; transform: translateX(-50%); text-align: center; width: 100%; font-weight: bold; font-size: 10px; line-height: 8px;">
                    SIN LIMITES BOUTIQUE
                </div>
                <div id="product-name" style="position: absolute; top: 19px; left: 50%; transform: translateX(-50%); text-align: center; width: 90%; font-size: 9px; line-height: 8px; max-height: 16px; overflow: hidden;">
                </div>
                <div style="position: absolute; bottom: 2px; left: 50%; transform: translateX(-50%); width: 4.6cm; height: 1.2cm; text-align: center;">
                    <div style="width: 100%; height: 70%;">
                        <svg id="barcode" style="width: 100%; height: 100%;"></svg>
                    </div>
                    <div id="code-price" style="width: 100%; height: 30%; font-size: 10px; font-weight: bold; display: flex; justify-content: center; align-items: center;">
                        <span id="code-text"></span>
                        <span id="price-iva" style="margin-left: 10px;"></span>
                    </div>
                </div>
            </div>
        `);

        // Actualizar nombre del producto
        $("#product-name").text(nompro || "Producto sin nombre");

        if (codbar) {
            try {
                // Generar código de barras
                JsBarcode("#barcode", codbar, {
                    width: 1.5,
                    height: 30,
                    fontSize: 0,
                    margin: 0,
                    textAlign: "center",
                    textMargin: 0,
                    displayValue: false
                });

                // Mostrar código del producto
                $("#code-text").text(codbar);

                // Mostrar precio con IVA formateado con 2 decimales
                if (precio_iva && !isNaN(parseFloat(precio_iva))) {
                    var precio_formateado = parseFloat(precio_iva).toFixed(2);
                    $("#price-iva").text("$" + precio_formateado);
                } else {
                    $("#price-iva").text("$0.00");
                }

                $("#print").show();

                // Imprimir directamente
                $("#print").printArea({
                    mode: "iframe", // Usar iframe para impresión directa
                    popClose: true, // Cerrar ventana de impresión automáticamente
                    retainAttr: ["id", "class", "style"] // Mantener atributos necesarios
                });

                // Limpiar después de imprimir (con retraso para asegurar la impresión)
                setTimeout(function() {
                    $("#print").remove();
                }, 1000);
            } catch (e) {
                alert("Error al generar el código de barras: " + e.message);
                $("#print").remove();
            }
        } else {
            $("#print").remove();
            alert("El producto no tiene un código de barras válido.");
        }
    }

    // Editar producto
    $(document).on('click', '.pro_ver', function(){
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php print $base_url;?>producto/tmp_pro",
            data: {id: id},
            success: function(json) {
                if (parseInt(json.resu) == 1) {
                    location.replace("<?php print $base_url;?>producto/pro_edit");
                } else {
                    alert("Error de conexión");
                }
            },
            error: function() {
                alert("Error de conexión al intentar editar el producto.");
            }
        });
    });

    // Imprimir código de barras directamente
    $(document).on('click', '.pro_codigo', function(){
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php print $base_url;?>producto/get_producto_data",
            data: {id: id},
            success: function(json) {
                if (json && json.pro_codigobarra) {
                    // Calcular precio con IVA
                    var tarifaiva = parseFloat(<?php echo $tarifaiva; ?>);
                    var precio_iva = (parseFloat(json.pro_precioventa) * (1 + tarifaiva)).toFixed(2);
                    // Generar e imprimir código de barras
                    generarbarcode(json.pro_codigobarra, precio_iva, json.pro_nombre);
                } else {
                    alert("Error: No se pudieron obtener los datos del producto.");
                }
            },
            error: function() {
                alert("Error de conexión al obtener los datos del producto.");
            }
        });
    });

    // Eliminar producto
    $(document).on('click', '.pro_del', function(){
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('producto/tmp_pro');?>",
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
                    href: "<?php echo base_url('producto/del_pro');?>"
                });
            },
            error: function() {
                alert("Error de conexión al intentar eliminar el producto.");
            }
        });
    });
});
</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-shopping-bag"></i> Producto
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>producto">Producto</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content" id="app_producto">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title"></i> Listado de Productos</h3>
                      <div class="pull-right"> 
                        <a class="btn btn-primary btn-grad" target="_blank" href="<?php print $base_url;?>producto/reporte" data-original-title="" title=""><i class="fa fa-bars" aria-hidden="true"></i> Reporte </a>
                        <a class="btn btn-danger btn-grad" target="_blank" href="<?php print $base_url;?>producto/agotado" data-original-title="" title=""><i class="fa fa-sort-amount-desc" aria-hidden="true"></i> Agotados </a>
                        <a class="btn btn-success btn-grad" href="<?php print $base_url;?>producto/agregar" data-original-title="" title=""><i class="fa fa-plus-square"></i> Añadir </a>
                      </div>
                    </div>
                    <div class="box-body">
                      <div class="row" v-if="carga_index">
                        <div class="col-xs-12">
                          <div class="box">
                            <div class="box-body table-responsive">
                              <table id="dataTablePro" class="table table-bordered table-hover">
                                <thead>
                                  <tr>
                                    <th>Acción</th>
                                    <th>Cod Barra</th>
                                    <th>Nombre</th>
                                    <th>Existencia</th>
                                    <th>Pre Compra</th>
                                    <th>Pre Venta s/IVA</th>
                                    <th>Pre Venta c/IVA</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php foreach ($pro as $p): ?>
                                    <tr>
                                      <td>
                                        <div class="text-center">
                                          <a href="#" title="Imp. Cod. Barras" id="<?php print $p->pro_id ?>" class="btn bg-navy color-palette btn-xs btn-grad pro_codigo"><i class="fa fa-barcode"></i></a>
                                          <a href="#" title="Ver" id="<?php print $p->pro_id ?>" class="btn btn-success btn-xs btn-grad pro_ver"><i class="fa fa-pencil-square-o"></i></a> 
                                          <a href="#" title="Eliminar" id="<?php print $p->pro_id; ?>" class="btn btn-danger btn-xs btn-grad pro_del"><i class="fa fa-trash-o"></i></a>
                                        </div>
                                      </td>
                                      <td><?php print $p->pro_codigobarra ?></td>
                                      <td><?php print $p->pro_nombre ?></td>
                                      <td class="text-right"><?php print $p->pro_existencia ?></td>
                                      <td class="text-right"><?php print $p->pro_preciocompra ?></td>
                                      <td class="text-right"><?php print $p->pro_precioventa ?></td>
                                      <td class="text-right"><?php print number_format($p->pro_precioventa * (1 + $tarifaiva), 6) ?></td>
                                    </tr>
                                  <?php endforeach ?>
                                </tbody>
                              </table>
                            </div>
                            <!-- /.box-body -->
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.box-body -->
                </div>
              <!-- /.box -->
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
<script src="<?php print $base_url; ?>assets/plugins/jQuery/JsBarcode.all.min.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/jQuery/jquery.PrintArea.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>public/js/js_vue/component/producto/producto_component.js"></script>