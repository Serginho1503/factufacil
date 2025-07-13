<?php
/* ------------------------------------------------
  ARCHIVO: backup.php
  DESCRIPCION: Contiene la vista de backup.
  FECHA DE CREACIÓN: 05/07/2017
 * 
  ------------------------------------------------ */
// Setear el título HTML de la página
//print "<script>document.title = 'FACTUFÁCIL - Respaldo BD'</script>";
?>

<script>
$( document ).ready(function() {

    $.blockUI({ message: '<h3> Generando respaldo de base de datos. Por favor espere...</h3>' });
    $.ajax({
      type: "POST",
      dataType: "json",
      url: base_url + "Backup/guardar",
      success: function(json) {
        $.unblockUI();
        alert('Archivo de respaldo de base de datos generado de forma exitosa.');
        location.replace("<?php print $base_url;?>Inicio");
      }    
    });


});

</script>
