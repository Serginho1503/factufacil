<?php

  $sistema = &get_instance();
  $sistema->load->model("Sistema_model");
  $nombresistema = $sistema->Sistema_model->sel_nombresistema();  

?>

 <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>TECNOLOGÍA INFORMÁTICA PROFESIONAL</b>
    </div>
    <strong>Copyright &copy; 2019 <a href="https://www.factufacil-ec.com"><?php print @$nombresistema; ?></a></strong>
  </footer>
</div>
<!-- ./wrapper -->


<script>
/*$.widget.bridge('uibutton', $.ui.button);*/
</script>
<!-- Sparkline -->
<!--
<script src="<?php // print $base_url; ?>assets/plugins/sparkline/jquery.sparkline.min.js"></script>
-->
<!-- jvectormap -->
<!--
<script src="<?php //print $base_url; ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php //print $base_url; ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
-->
<!-- jQuery Knob Chart -->
<!--<script src="<?php // print $base_url; ?>assets/plugins/knob/jquery.knob.js"></script>-->
<!-- Bootstrap WYSIHTML5 -->
<!--<script src="<?php //print $base_url; ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>-->
<!-- Select2 -->
<!--<script src="<?php //print $base_url; ?>assets/plugins/select2/select2.full.min.js"></script>-->
<!-- InputMask -->
<!--
<script src="<?php //print $base_url; ?>assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?php //print $base_url; ?>assets/plugins/input-mask/jquery.inputmask.numeric.extensions.js"></script>
<script src="<?php //print $base_url; ?>assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?php //print $base_url; ?>assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
-->
<!-- date-range-picker -->
<!--<script src="<?php //print $base_url; ?>assets/plugins/moment/moment.js"></script>-->
<!--<script src="<?php //print $base_url; ?>assets/plugins/daterangepicker/daterangepicker.js"></script>-->
<!-- bootstrap datepicker -->
<!--<script src="<?php //print $base_url; ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>-->
<!-- bootstrap color picker -->
<!--<script src="<?php //print $base_url; ?>assets/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>-->
<!-- bootstrap time picker -->
<!--<script src="<?php //print $base_url; ?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>-->
<!-- SlimScroll 1.3.0 -->
<!--<script src="<?php //print $base_url; ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>-->
<!-- iCheck 1.0.1 -->
<!--<script src="<?php //print $base_url; ?>assets/plugins/iCheck/icheck.min.js"></script>-->
<!-- FastClick -->
<!--<script src="<?php //print $base_url; ?>assets/plugins/fastclick/fastclick.js"></script>-->

<!-- AdminLTE for demo purposes -->
<!--<script src="<?php //print $base_url; ?>assets/dist/js/demo.js"></script>-->


<!-- DataTables -->
<!-- <script src="<?php // print $base_url; ?>assets/plugins/datatables/jquery.dataTables.min.js"></script> -->
<!-- <script src="<?php // print $base_url; ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script> -->
<!-- <script src='<?php // print $base_url; ?>public/js/dtextra/jquery.dataTables.delay.min.js'></script> -->

<?php // <!-- Para cargar el ReloadAjax y actualizar los datos del datable en línea --> ?>
<!--<script src='<?php //print $base_url; ?>public/js/dtextra/fnReloadAjax.js'></script>-->







<?php // <!-- FORMWIZARD --> ?>
<!-- <script type="text/javascript" src="<?php // print $base_url; ?>public/js/formwizard/formToWizard.js"></script>-->
<?php // <!-- FIN DE FORMWIZARD --> ?>



<?php // <!-- Backstretch --> ?>
<!--<script src="<?php //print $base_url; ?>public/js/backstretch/jquery.backstretch.js"></script>-->
<?php // <!-- Backstretch --> ?>

<?php // <!-- INCLUDES PARA FILEUPLOAD --> ?>
<script src="<?php print $base_url; ?>public/js/fileupload/bootstrap-fileupload.js"></script>
<?php // <!-- FIN DE INCLUDES PARA FILEUPLOAD --> ?>

<?php // <!-- INCLUDES PARA REDIRECT --> ?>
<!--<script src="<?php //print $base_url; ?>public/js/redirect/jquery.redirect.js"></script>-->
<?php // <!-- FIN DE INCLUDES PARA REDIRECT --> ?>


<!-- Page script -->
<script>
    /*
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
  */
</script>
</body>
</html>