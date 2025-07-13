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
<script src="<?php print $base_url; ?>assets/plugins/datepair/js/jquery.min.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/js/jquery.timepicker.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/js/bootstrap-datepicker.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/pikaday.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/jquery.ptTimeSelect.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/moment.min.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/lib/site.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/dist/datepair.js"></script>
<script src="<?php print $base_url; ?>assets/plugins/datepair/dist/jquery.datepair.js"></script>

<style type="text/css">
  .linea{
    border-width: 2px 0 0;
    margin-bottom: 5px;
    margin-top: 5px;
    border-color: currentcolor currentcolor;
  } 
  .trfull{
    padding-right: 0px !important; 
    padding-left: 0px  !important;
  }    
</style>
<script type="text/javascript">

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

        /* ACTUALIZAR REPORTE POR RANGO DE FECHA */
        $(document).on('change','#txt_labor', function(){
            $('.actualiza').click();    
        });        

        $(document).on('change','.datapub', function(){
            $('.actualiza').click();    
        });        

        $(document).on('change','.dataothercost', function(){
            $('.actualiza').click();    
        });        

        $(document).on('change','.datacred', function(){
            $('.actualiza').click();    
        });        


        /* ACTUALIZAR REPORTE POR RANGO DE FECHA */
        $('#rpt_xls0').click(function(){

                    location.replace("<?php print $base_url;?>Reporte/cierremesXLS");

        });

        /* ACTUALIZAR REPORTE POR RANGO DE FECHA */
        $('#rpt_xls').click(function(){
            var hasta = $("#fhasta").val();
            var desde = $("#fdesde").val();
       /*
        var fhasta = $("#fhasta").val();
        var fdesde = $("#fdesde").val();
        var horah = $("#hhasta").val();
        var horad = $("#hdesde").val(); 
*/


            var vcocina = $("#txt_vcocina").val();  vcocina = vcocina.replace(',', '');
            var vbarra = $("#txt_vbarra").val();    vbarra = vbarra.replace(',', '');

            var ccocina = $('#txt_ccocina').val();  ccocina = ccocina.replace(',', '');
            var cbarra = $('#txt_cbarra').val();    cbarra = cbarra.replace(',', '');
            var clabor = $('#txt_labor').val();     clabor = clabor.replace(',', '');
            if (isNaN(clabor = parseFloat(clabor))) { clabor = 0; }

            var cpub = $("#txt_pub").val();
            cpub = cpub.replace(',', '');
            if (isNaN(cpub = parseFloat(cpub))) { cpub = 0; }
            var ctv = $("#txt_tv").val();
            ctv = ctv.replace(',', '');
            if (isNaN(ctv = parseFloat(ctv))) { ctv = 0; }
            var ctvname = $("#txt_tvname").val();

            var ciess = $("#txt_iess").val();       ciess = ciess.replace(',', '');
            if (isNaN(ciess = parseFloat(ciess))) { ciess = 0; }
            var carri = $("#txt_arri").val();       carri = carri.replace(',', '');
            if (isNaN(carri = parseFloat(carri))) { carri = 0; }
            var csup = $("#txt_sup").val();         csup = csup.replace(',', '');
            if (isNaN(csup = parseFloat(csup))) { csup = 0; }
            var ctrans = $("#txt_trans").val();     ctrans = ctrans.replace(',', '');
            if (isNaN(ctrans = parseFloat(ctrans))) { ctrans = 0; }
            var ccont = $("#txt_cont").val();       ccont = ccont.replace(',', '');
            if (isNaN(ccont = parseFloat(ccont))) { ccont = 0; }
            var cint = $("#txt_int").val();         cint = cint.replace(',', '');
            if (isNaN(cint = parseFloat(cint))) { cint = 0; }
            var cmak = $("#txt_mak").val();         cmak = cmak.replace(',', '');
            if (isNaN(cmak = parseFloat(cmak))) { cmak = 0; }
            var cpap = $("#txt_pap").val();         cpap = cpap.replace(',', '');
            if (isNaN(cpap = parseFloat(cpap))) { cpap = 0; }

            var cfond = $("#txt_fond").val();       cfond = cfond.replace(',', '');
            if (isNaN(cfond = parseFloat(cfond))) { cfond = 0; }
            var ctarj = $("#txt_tarj").val();       ctarj = ctarj.replace(',', '');
            if (isNaN(ctarj = parseFloat(ctarj))) { ctarj = 0; }
            var civa = $("#txt_iva").val();         civa = civa.replace(',', '');
            if (isNaN(civa = parseFloat(civa))) { civa = 0; }
            var crent = $("#txt_rent").val();       crent = crent.replace(',', '');
            if (isNaN(crent = parseFloat(crent))) { crent = 0; }

            var caja = $("#txt_caja").val();       caja = caja.replace(',', '');
            if (isNaN(caja = parseFloat(caja))) { caja = 0; }

            var ahorro = $("#txt_ahorro").val();       ahorro = ahorro.replace(',', '');
            if (isNaN(ahorro = parseFloat(ahorro))) { ahorro = 0; }

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url('Reporte/tmpcierremes');?>",
                data: { hasta: hasta, desde: desde,
                        vcocina: vcocina, vbarra: vbarra, 
                        ccocina: ccocina, cbarra: cbarra, clabor: clabor,
                        cpub: cpub, ctv: ctv, ctvname: ctvname,
                        ciess: ciess, carri: carri, csup: csup, ctrans: ctrans, 
                        ccont: ccont, cint: cint, cmak: cmak, cpap: cpap,
                        cfond: cfond, ctarj: ctarj, civa: civa, crent: crent,
                        caja: caja, ahorro: ahorro 
                },
                success: function(json) {
                    location.replace("<?php print $base_url;?>Reporte/cierremesXLS");
                }    
            });
        });

        /* ACTUALIZAR REPORTE POR RANGO DE FECHA */
        $('.actualiza').click(function(){
    /*    var hasta = $("#hasta").val();
        var desde = $("#desde").val();*/

        var fhasta = $("#fhasta").val();
        var fdesde = $("#fdesde").val();
        var horah = $("#hhasta").val();
        var horad = $("#hdesde").val(); 



          $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('Reporte/actualiza');?>",
            data: { fdesde:fdesde, horad:horad, fhasta:fhasta, horah:horah },
            success: function(json) {

                var vcocina = formatoNumero(json.vcocina, 2, '.' , ',');
                var vbarra = formatoNumero(json.vbarra, 2, '.' , ',');
                var totalventas = parseFloat(json.vcocina) + parseFloat(json.vbarra); 
                if (isNaN(totalventas = parseFloat(totalventas))) { totalventas = 0; }
                vtotal = formatoNumero(totalventas, 2, '.' , ',');
                var pvcocina = 0;
                var pvbarra = 0;
                if (totalventas > 0) { 
                    pvcocina = parseFloat(json.vcocina) / totalventas * 100; 
                    pvbarra = parseFloat(json.vbarra) / totalventas * 100; 
                }
                pvcocina = formatoNumero(pvcocina, 0, ',' , '.');
                pvbarra = formatoNumero(pvbarra, 0, ',' , '.');

                $('#txt_vcocina').val(vcocina);
                $('#txt_vbarra').val(vbarra);   
                $('#txt_totalv').val(vtotal);  
                $('#prc_vcocina').html("<span class='badge bg-red'>" + pvcocina + "%</span>");
                $('#prc_vbarra').html("<span class='badge bg-yellow'>" + pvbarra + "%</span>");

                var ccocina = formatoNumero(json.gastos.cocina, 2, '.' , ',');
                var cbarra = formatoNumero(json.gastos.barra, 2, '.' , ',');
                var clabor = $("#txt_labor").val();
                if (isNaN(clabor = parseFloat(clabor))) { clabor = 0; }
                var totalgastos = parseFloat(json.gastos.cocina) + parseFloat(json.gastos.barra) + parseFloat(clabor); 
                vtotal = formatoNumero(totalgastos, 2, '.' , ',');

                var pccocina = 0;
                var pcbarra = 0;
                var pclabor = 0;
                if (totalgastos > 0) { 
                    pccocina = parseFloat(json.gastos.cocina) / totalgastos * 100; 
                    pcbarra = parseFloat(json.gastos.barra) / totalgastos * 100; 
                    pclabor = parseFloat(clabor) / totalgastos * 100; 
                }
                pccocina = formatoNumero(pccocina, 0, '.' , ',');
                pcbarra = formatoNumero(pcbarra, 0, '.' , ',');
                pclabor = formatoNumero(pclabor, 0, '.' , ',');

                $('#txt_ccocina').val(ccocina);
                $('#txt_cbarra').val(cbarra);
                $('#txt_totalc').val(vtotal);  
                $('#prc_ccocina').html("<span class='badge bg-red'>" + pccocina + "%</span>");
                $('#prc_cbarra').html("<span class='badge bg-yellow'>" + pcbarra + "%</span>");
                $('#prc_clabor').html("<span class='badge bg-blue'>" + pclabor + "%</span>");

                var totcontrol = 0;

                var crep = formatoNumero(json.gastos.repuesto, 2, '.' , ',');
                var cmant = formatoNumero(json.gastos.mantenimiento, 2, '.' , ',');
                var climp = formatoNumero(json.gastos.limpieza, 2, '.' , ',');
                var ntotal = parseFloat(json.gastos.repuesto) + parseFloat(json.gastos.mantenimiento) + parseFloat(json.gastos.limpieza); 
                vtotal = formatoNumero(ntotal, 2, '.' , ',');
            /*    totcontrol += ntotal;*/
                var vmtotal = 0;
                var vstotal = 0;
                var cat = 0;


                var pcmant = 0;
                var pcserv = 0;
                var pctotm = 0;
                var pctots = 0;
                var pcmanto = 0;
                var pcservi = 0;
                var arr = json.lstcat;

                for(var i=0;i<arr.length;i++){
                    if (arr[i]["id_parametro"] == 1) {
                        cat = arr[i]["id_categoria"];
                        vmtotal = vmtotal + parseFloat(arr[i]["total"]);
                        pcmant = parseFloat(arr[i]["total"]) / totalventas * 100;
                        pcmanto = formatoNumero(pcmant, 2, '.' , ',');
                        pctotm = pctotm + pcmant;
                        $('#'+cat).val(parseFloat(arr[i]["total"])); 
                        $('.mante[name='+cat+']').html("<span class='badge bg-red'>" + pcmanto + "%</span>");
                        
                     }

                    if (arr[i]["id_parametro"] == 2) {
                        cat = arr[i]["id_categoria"];
                        vstotal = vstotal + parseFloat(arr[i]["total"]);
                        pcserv = parseFloat(arr[i]["total"]) / totalventas * 100;
                        pcservi = formatoNumero(pcserv, 2, '.' , ',');  
                        pctots = pctots + pcserv;                      
                        $('#'+cat).val(parseFloat(arr[i]["total"])); 
                        $('.serv[name='+cat+']').html("<span class='badge bg-red'>" + pcservi + "%</span>");                       
                    }                    

                }
                totcontrol = vmtotal;
                totcontrol += vstotal;

                pctotm = formatoNumero(pctotm, 2, '.' , ',');
                pctots = formatoNumero(pctots, 2, '.' , ',');
                $('#prc_totalm').html("<span class='badge bg-blue'>" + pctotm + "%</span>");
                $('#prc_totals').html("<span class='badge bg-blue'>" + pctots + "%</span>");
                $('#txt_totalm').val(vmtotal); 
                $('#txt_totals').val(vstotal);


                var cpub = $("#txt_pub").val();
                if (isNaN(cpub = parseFloat(cpub))) { cpub = 0; }
                var ctv = $("#txt_tv").val();
                if (isNaN(ctv = parseFloat(ctv))) { ctv = 0; }
                var ntotal = parseFloat(cpub) + parseFloat(ctv); 
                vtotal = formatoNumero(ntotal, 2, '.' , ',');
                totcontrol += ntotal;

                var ppub = 0;
                var ptv = 0;
                var pctot = 0;
                if (totalventas > 0) { 
                    ppub = parseFloat(cpub) / totalventas * 100; 
                    ptv = parseFloat(ctv) / totalventas * 100; 
                    pctot = ntotal / totalventas * 100; 
                }
                ppub = formatoNumero(ppub, 0, '.' , ',');
                ptv = formatoNumero(ptv, 0, '.' , ',');
                pctot = formatoNumero(pctot, 0, '.' , ',');

                $('#txt_totalap').val(vtotal);  
                $('#prc_pub').html("<span class='badge bg-red'>" + ppub + "%</span>");
                $('#prc_tv').html("<span class='badge bg-yellow'>" + ptv + "%</span>");
                $('#prc_totalap').html("<span class='badge bg-blue'>" + pctot + "%</span>");

                var ciess = $("#txt_iess").val();
                if (isNaN(ciess = parseFloat(ciess))) { ciess = 0; }
                var carri = $("#txt_arri").val();
                if (isNaN(carri = parseFloat(carri))) { carri = 0; }
                var csup = $("#txt_sup").val();
                if (isNaN(csup = parseFloat(csup))) { csup = 0; }
                var ctrans = $("#txt_trans").val();
                if (isNaN(ctrans = parseFloat(ctrans))) { ctrans = 0; }
                var ccont = $("#txt_cont").val();
                if (isNaN(ccont = parseFloat(ccont))) { ccont = 0; }
                var cint = $("#txt_int").val();
                if (isNaN(cint = parseFloat(cint))) { cint = 0; }
                var cmak = $("#txt_mak").val();
                if (isNaN(cmak = parseFloat(cmak))) { cmak = 0; }
                var cpap = $("#txt_pap").val();
                if (isNaN(cpap = parseFloat(cpap))) { cpap = 0; }
                var ntotal = parseFloat(ciess) + parseFloat(carri) + parseFloat(csup) + parseFloat(ctrans) + parseFloat(ccont) + parseFloat(cint) + parseFloat(cmak) + parseFloat(cpap); 
                vtotal = formatoNumero(ntotal, 2, '.' , ',');
                totcontrol += ntotal;

                var piess = 0;
                var parri = 0;
                var psup = 0;
                var ptrans = 0;
                var pcont = 0;
                var pint = 0;
                var pmak = 0;
                var ppap = 0;
                var pctot = 0;
                if (totalventas > 0) { 
                    piess = parseFloat(ciess) / totalventas * 100; 
                    parri = parseFloat(carri) / totalventas * 100; 
                    psup = parseFloat(csup) / totalventas * 100; 
                    ptrans = parseFloat(ctrans) / totalventas * 100; 
                    pcont = parseFloat(ccont) / totalventas * 100; 
                    pint = parseFloat(cint) / totalventas * 100; 
                    pmak = parseFloat(cmak) / totalventas * 100; 
                    ppap = parseFloat(cpap) / totalventas * 100; 
                    pctot = ntotal / totalventas * 100; 
                }
                piess = formatoNumero(piess, 0, '.' , ',');
                parri = formatoNumero(parri, 0, '.' , ',');
                psup = formatoNumero(psup, 0, '.' , ',');
                ptrans = formatoNumero(ptrans, 0, '.' , ',');
                pcont = formatoNumero(pcont, 0, '.' , ',');
                pint = formatoNumero(pint, 0, '.' , ',');
                pmak = formatoNumero(pmak, 0, '.' , ',');
                ppap = formatoNumero(ppap, 0, '.' , ',');
                pctot = formatoNumero(pctot, 0, '.' , ',');

                $('#txt_tcosto').val(vtotal);  
                $('#prc_iess').html("<span class='badge'>" + piess + "%</span>");
                $('#prc_arri').html("<span class='badge bg-red'>" + parri + "%</span>");
                $('#prc_sup').html("<span class='badge bg-blue'>" + psup + "%</span>");
                $('#prc_trans').html("<span class='badge bg-yellow'>" + ptrans + "%</span>");
                $('#prc_cont').html("<span class='badge'>" + pcont + "%</span>");
                $('#prc_int').html("<span class='badge bg-red'>" + pint + "%</span>");
                $('#prc_mak').html("<span class='badge bg-blue'>" + pmak + "%</span>");
                $('#prc_pap').html("<span class='badge bg-yellow'>" + ppap + "%</span>");
                $('#prc_tcosto').html("<span class='badge bg-green'>" + pctot + "%</span>");

                var cfond = $("#txt_fond").val();
                if (isNaN(cfond = parseFloat(cfond))) { cfond = 0; }
                var ctarj = $("#txt_tarj").val();
                if (isNaN(ctarj = parseFloat(ctarj))) { ctarj = 0; }
                var civa = $("#txt_iva").val();
                if (isNaN(civa = parseFloat(civa))) { civa = 0; }
                var crent = $("#txt_rent").val();
                if (isNaN(crent = parseFloat(crent))) { crent = 0; }
                var ntotal = parseFloat(cfond) + parseFloat(ctarj) + parseFloat(civa) + parseFloat(crent); 
                vtotal = formatoNumero(ntotal, 2, '.' , ',');
                totcontrol += ntotal;

                var pfond = 0;
                var ptarj = 0;
                var piva = 0;
                var prent = 0;
                var pctot = 0;
                if (totalventas > 0) { 
                    pfond = parseFloat(cfond) / totalventas * 100; 
                    ptarj = parseFloat(ctarj) / totalventas * 100; 
                    piva = parseFloat(civa) / totalventas * 100; 
                    prent = parseFloat(crent) / totalventas * 100; 
                    pctot = ntotal / totalventas * 100; 
                }
                pfond = formatoNumero(pfond, 0, '.' , ',');
                ptarj = formatoNumero(ptarj, 0, '.' , ',');
                piva = formatoNumero(piva, 0, '.' , ',');
                prent = formatoNumero(prent, 0, '.' , ',');
                pctot = formatoNumero(pctot, 0, '.' , ',');

                $('#txt_tcred').val(vtotal);  
                $('#prc_fondo').html("<span class='badge'>" + pfond + "%</span>");
                $('#prc_tarj').html("<span class='badge bg-red'>" + ptarj + "%</span>");
                $('#prc_iva').html("<span class='badge bg-blue'>" + piva + "%</span>");
                $('#prc_rent').html("<span class='badge bg-yellow'>" + prent + "%</span>");
                $('#prc_tcred').html("<span class='badge bg-green'>" + pctot + "%</span>");

                vtotcontrol = formatoNumero(totcontrol, 2, '.' , ',');
                $('#txt_control').val(vtotcontrol);  
                var pcontrol = 0;
                if (totalventas > 0) { pcontrol = totcontrol / totalventas * 100; }
                pcontrol = formatoNumero(pcontrol, 0, '.' , ',');
                $('#prc_control').html("<span class='badge bg-green'>" + pcontrol + "%</span>");

                var totaloperativo = totcontrol + totalgastos;
                vtotaloperativo = formatoNumero(totaloperativo, 2, '.' , ',');
                $('#txt_tope').val(vtotaloperativo);  
                var poper = 0;
                if (totalventas > 0) { poper = totaloperativo / totalventas * 100; }
                poper = formatoNumero(poper, 0, ',' , '.');
                $('#prc_tope').html("<span class='badge bg-green'>" + poper + "%</span>");

                var pace = totalventas - totaloperativo;
                vpace = formatoNumero(pace, 2, '.' , ',');
                $('#txt_pace').val(vpace);  
                var ppace = 0;
                if (totalventas > 0) { ppace = pace / totalventas * 100; }
                ppace = formatoNumero(ppace, 0, '.' , ',');
                $('#prc_pace').html("<span class='badge bg-green'>" + ppace + "%</span>");

                var pago = <?php print $cantsocios; ?>;
                /*pago = pace / pago - 500;*/
                var ahorro = $('#txt_ahorro').val();
                pago = (pace  - ahorro) / pago;
                if (pago < 0 ) { pago = 0; }
                pago = formatoNumero(pago, 0, '.' , ',');
                $('.socio').val(pago);  

                

            }
          });         

        });        



    function formatoNumero(numero, decimales, separadorDecimal, separadorMiles) {
        var partes, array;
        if ( !isFinite(numero) || isNaN(numero = parseFloat(numero)) ) {
            return "";
        }
        if (typeof separadorDecimal==="undefined") {
            separadorDecimal = ".";
        }
        if (typeof separadorMiles==="undefined") {
            separadorMiles = "";
        }
        // Redondeamos
        if ( !isNaN(parseInt(decimales)) ) {
            if (decimales >= 0) {
                numero = numero.toFixed(decimales);
            } else {
                numero = (
                    Math.round(numero / Math.pow(10, Math.abs(decimales))) * Math.pow(10, Math.abs(decimales))
                ).toFixed();
            }
        } else {
            numero = numero.toString();
        }
        // Damos formato
        partes = numero.split(".", 2);
        array = partes[0].split("");
        for (var i=array.length-3; i>0 && array[i-1]!=="-"; i-=3) {
            array.splice(i, 0, separadorMiles);
        }
        numero = array.join("");

        if (partes.length>1) {
            numero += separadorDecimal + partes[1];
        }
        return numero;
    }

    $("#frm_caja").bind("keypress", function(e) {
      if (e.keyCode == 13) {               
        e.preventDefault();
        return false;
      }
    });


    $('.actualiza').click();    

    function cal_mantserv(){
        var hasta = $("#hasta").val();
        var desde = $("#desde").val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('Reporte/temp_fecha');?>",
            data: { hasta: hasta, desde: desde },
            success: function(json) {
                $('#cierre_calculo').load(base_url + "Reporte/cierre_cal");
            }
        });

    }


    });
</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-file-text-o"></i> Cierre de Mes
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php print $base_url ?>inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="<?php print $base_url ?>usuarios">Cierre de Mes</a></li>
        
      </ol>
    </section>

    <section class="content">
        <div class="row">
            <form>
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">

                        <div id="buscrango">
                          
                          <div class="form-group col-md-1 " style="margin-bottom: 0px; margin-top: 0px; padding-right:0px; margin-right:30px;">
                            <label for="">Desde</label>
                            <div class="input-group">
                            <!--  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                              <input style="width:100px;" type="text" class="form-control text-center date start" id="fdesde" name="fdesde" value="<?php print  date("d/m/Y"); ?>">
                            </div>
                          </div>              
                          <div class="form-group col-md-1 " style="margin-bottom: 0px; margin-top: 0px; padding-left:0px; padding-right:0px; margin-right:30px;">              
                            <label for="">Hora</label>
                            <div class="input-group">
                          <!--    <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>-->
                              <input style="width:100px;" type="text" class="form-control text-center time start" id="hdesde" name="hdesde" value="00:00:00">
                            </div>
                          </div> 
                          <div class="form-group col-md-1 " style="margin-bottom: 0px; margin-top: 0px; padding-left:0px; padding-right:0px; margin-right:30px;">
                            <label for="">hasta</label>
                            <div class="input-group">
                          <!--    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                              <input style="width:100px;" type="text" class="form-control text-center date end" id="fhasta" name="fhasta" value="<?php print  date("d/m/Y"); ?>">
                            </div>
                          </div>              
                          <div class="form-group col-md-1 " style="margin-bottom: 0px; margin-top: 0px; padding-left:0px; padding-right:0px; margin-right:30px;">              
                            <label for="">Hora</label>
                            <div class="input-group">
                          <!--    <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>-->
                              <input style="width:100px;" type="text" class="form-control text-center time end" id="hhasta" name="hhasta" value="23:59:59">
                            </div>
                          </div> 
                          <div class="col-md-1" style="margin-bottom: 0px; margin-top: 24px; padding-left:0px; padding-right:0px; width: 50px;">
                            <button type="button" class="btn btn-block btn-success actualiza"><i class="fa fa-search" aria-hidden="true"></i></button>
                          </div>

                        </div>




<!--
                        <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
                          <label for="">Desde</label>
                          <div class="input-group date col-sm-7">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control pull-right validate[required]" id="desde" name="desde" value="<?php print  date("d/m/Y"); ?>">
                          </div>
                        </div> 

                        <div class="form-group col-md-3" style="margin-bottom: 0px; margin-top: 0px;">
                          <label for="">Hasta</label>
                          <div class="input-group date col-sm-10">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control pull-right validate[required]" id="hasta" name="hasta" value="<?php print  date("d/m/Y"); ?>">
                            <span class="input-group-btn">
                            <button class="btn btn-success btn-flat actualiza" type="button"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                            </span>
                          </div>
                        </div>  
-->
                        <div class="pull-right">
                            <a class="btn bg-orange color-palette btn-grad paametros" href="<?php print $base_url;?>Reporte/parametros_cierre" data-original-title="" title=""><i class="fa fa-cogs" aria-hidden="true"></i> Parametros de Cierre </a>
                        </div>


                    </div>
                    <div class="box-body">

                    <div class="row">
                        <div class ="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="box-body no-padding">
                              <table class="table table-striped">
                                <tr>
                                  <th class="text-left">P & L PROY</th>
                                  <th class="text-center col-md-1">MONTO</th>
                                  <th class="text-center">%</th>
                                </tr>
                                <tr>
                                  <td class="text-left">FOOD SALES</td>
                                  <td align="center"><input class="text-center" type="text" name="txt_vcocina" id="txt_vcocina" style="width:100px;" value="<?php print number_format(@$vcocina,2,",","."); ?>" disabled></td>
                                  <td class="text-center" id="prc_vcocina"><span class="badge bg-red">70%</span></td>
                                </tr>
                                <tr>
                                  <td class="text-left">BAR SALES</td>
                                  <td class="text-center"><input class="text-center" type="text" name="txt_vbarra" id="txt_vbarra" style="width:100px;" value="<?php print number_format(@$vbarra,2,",","."); ?>" disabled></td>
                                  <td class="text-center" id="prc_vbarra"><span class="badge bg-yellow">30%</span></td>
                                </tr>
                                <tr>
                                  <th class="text-left">TOTAL SALES</th>
                                  <?php $totalv = @$vcocina + @$vbarra; ?>
                                  <th class="text-center"><input class="text-center" type="text" name="txt_totalv" id="txt_totalv" style="width:100px;" value="<?php print number_format(@$totalv,2,",","."); ?>" disabled></th>
                                  <th class="text-center"><span class="badge bg-green">100%</span></th>
                                </tr> 

                                <tr>
                                    <td class="trfull" colspan="3">
                                        <hr class="linea">                                       
                                    </td>
                                </tr> 

                                <tr>
                                  <td class="text-left">MIX COST  27%</th>
                                  <td class="text-center col-md-1"></td>
                                  <td class="text-center"></td>
                                </tr>
                                <tr>
                                  <td class="text-left">FOOD COST 34%</td>
                                  <td class="text-center col-md-1"><input class="text-center" type="text" name="txt_ccocina" id="txt_ccocina" style="width:100px;" value="<?php print number_format(@$gastos->cocina,2,",","."); ?>" disabled></td>
                                  <td class="text-center" id="prc_ccocina"><span class="badge bg-red">50%</span></td>
                                </tr>
                                <tr>
                                  <td class="text-left">BAR COST 20%</td>
                                  <td class="text-center col-md-1"><input class="text-center" type="text" name="txt_cbarra" id="txt_cbarra" style="width:100px;" value="<?php print number_format(@$gastos->barra,2,",","."); ?>" disabled></td>
                                  <td class="text-center" id="prc_cbarra"><span class="badge bg-yellow">25%</span></td>
                                </tr>
                                <tr>
                                  <td class="text-left">LABOR COST 16%</td>
                                  <td align="center"><input class="text-center" type="text" name="txt_labor" id="txt_labor" style="width:100px;"></td>
                                  <td class="text-center" id="prc_clabor"><span class="badge bg-blue">25%</span></td>
                                </tr>
                                <tr>
                                  <th class="text-left">TOTAL SALES COST</th>
                                  <th class="text-center col-md-1"><input class="text-center" type="text" name="txt_totalc" id="txt_totalc" style="width:100px;" value="<?php print number_format("0.00",2,",","."); ?>" disabled></th>
                                  <th class="text-center"><span class="badge bg-green">100%</span></th>
                                </tr> 

                                <tr>
                                    <td class="trfull" colspan="3">
                                        <hr class="linea">                                       
                                    </td>
                                </tr> 

                                <div id="cierre_calculo" class="">

                                    <?php $totalm = 0; 
                                        foreach ($lstcat as $lc):
                                        if($lc->id_parametro == 1){ $totalm = $totalm + $lc->total; ?>
                                            <tr>
                                              <td class="text-left"><?php print @$lc->nom_cat_gas; ?></td>
                                              <td class="text-center col-md-1"><input class="text-center" type="text" name="" id="<?php print $lc->id_categoria; ?>" style="width:100px;" value="<?php print number_format(@$lc->total,2,",","."); ?>" disabled></td>
                                              <td class="text-center mante" name="<?php print $lc->id_categoria;?>"><span class="badge bg-red">0.00%</span></td>
                                            </tr>     
                                    <?php 
                                        }
                                    ?>                                       
                                    <?php endforeach ?>
                                    <tr>
                                      <th class="text-left">TOTAL MANTENANCE</th>
                                      <th class="text-center col-md-1"><input class="text-center" type="text" name="txt_totalm" id="txt_totalm" style="width:100px;" value="<?php print number_format($totalm,2,",","."); ?>" disabled></th>
                                      <th class="text-center" id="prc_totalm"><span class="badge bg-green">2.61%</span></th>
                                    </tr> 

                                    <tr>
                                        <td class="trfull" colspan="3">
                                            <hr class="linea">                                       
                                        </td>
                                    </tr> 
                                    <?php 
                                        $totals = 0;
                                        foreach ($lstcat as $lct):
                                        if($lct->id_parametro == 2){ $totals = $totals + $lct->total; ?>
                                            <tr>
                                              <td class="text-left"><?php print @$lct->nom_cat_gas; ?></td>
                                              <td class="text-center col-md-1"><input class="text-center" type="text" name="<?php print $lct->id_categoria; ?>" id="<?php print $lct->id_categoria; ?>" style="width:100px;" value="<?php print number_format(@$lct->total,2,",","."); ?>" disabled></td>
                                              <td class="text-center serv" name="<?php print $lct->id_categoria;?>"><span class="badge bg-red">0.00%</span></td>
                                            </tr>     
                                    <?php 
                                        }
                                    ?>                                       
                                    <?php endforeach ?>
                                    <tr>
                                      <th class="text-left">TOTAL ENERGY Y PHONE</th>
                                      <th class="text-center col-md-1"><input class="text-center" type="text" name="txt_totals" id="txt_totals" style="width:100px;" value="<?php print number_format($totals,2,",","."); ?>" disabled></th>
                                      <th class="text-center" id="prc_totals"><span class="badge bg-green">0.83%</span></th>
                                    </tr>     

                                </div>

                                <tr>
                                    <td class="trfull" colspan="3">
                                        <hr class="linea">                                       
                                    </td>
                                </tr>  

                                <tr>
                                  <td class="text-left">PUBLICIDAD ARTES</td>
                                  <td class="text-center col-md-1"><input class="text-center datapub" type="text" name="txt_pub" id="txt_pub" style="width:100px;" value="<?php print number_format("0.00",2,",","."); ?>" ></td>
                                  <td class="text-center" id="prc_pub"><span class="badge bg-blue">0.32%</span></td>
                                </tr>
                                <tr>
<!--                                   <td class="text-left">TELEVISORES</td>
 -->                                  <td class="text-left col-md-1"><input type="text" name="txt_tvname" id="txt_tvname" style="width:100px;" value="TELEVISORES" ></td>
                                  <td class="text-center col-md-1"><input class="text-center datapub" type="text" name="txt_tv" id="txt_tv" style="width:100px;" value="<?php print number_format("0.00",2,",","."); ?>" ></td>
                                  <td class="text-center" id="prc_tv"><span class="badge bg-yellow">0.44%</span></td>
                                </tr>
                                <tr>
                                  <th class="text-left">TOTAL A & P</th>
                                  <th class="text-center col-md-1"><input class="text-center" type="text" name="txt_totalap" id="txt_totalap" style="width:100px;" value="<?php print number_format("0.00",2,",","."); ?>" disabled></th>
                                  <th class="text-center" id="prc_totalap"><span class="badge bg-green">0.77%</span></th>
                                </tr>
                                
                                <tr>
                                    <td class="trfull" colspan="3">
                                        <hr class="linea">                                       
                                    </td>
                                </tr> 

                                <tr>
                                  <td class="text-left">IESS</td>
                                  <td align="center"><input class="text-center dataothercost" type="text" name="txt_iess" id="txt_iess" style="width:100px;"></td>
                                  <td class="text-center" id="prc_iess"><span class="badge">3.00%</span></td>
                                </tr>
                                <tr>
                                  <td class="text-left">ARRIENDO</td>
                                  <td class="text-center col-md-1"><input class="text-center dataothercost" type="text" name="txt_arri" id="txt_arri" style="width:100px;"></td>
                                  <td class="text-center" id="prc_arri"><span class="badge bg-red">6.22%</span></td>
                                </tr>
                                <tr>
                                  <td class="text-left">SUPPLIES DE COCINA</td>
                                  <td class="text-center col-md-1"><input class="text-center dataothercost" type="text" name="txt_sup" id="txt_sup" style="width:100px;"></td>
                                  <td class="text-center" id="prc_sup"><span class="badge bg-blue">0.00%</span></td>
                                </tr>
                                <tr>
                                  <td class="text-left">TRANSPORTATION</td>
                                  <td class="text-center col-md-1"><input class="text-center dataothercost" type="text" name="txt_trans" id="txt_trans" style="width:100px;"></td>
                                  <td class="text-center" id="prc_trans"><span class="badge bg-yellow">1.14%</span></td>
                                </tr>
                                <tr>
                                  <td class="text-left">CONTADOR</td>
                                  <td class="text-center col-md-1"><input class="text-center dataothercost" type="text" name="txt_cont" id="txt_cont" style="width:100px;"></td>
                                  <td class="text-center" id="prc_cont"><span class="badge">0.32%</span></td>
                                </tr>
                                <tr>
                                  <td class="text-left">INTERNET & CABLE</td>
                                  <td class="text-center col-md-1"><input class="text-center dataothercost" type="text" name="txt_int" id="txt_int" style="width:100px;"></td>
                                  <td class="text-center" id="prc_int"><span class="badge bg-red">0.44%</span></td>
                                </tr>
                                <tr>
                                  <td class="text-left">MAKRO Y GUARDIA</td>
                                  <td class="text-center col-md-1"><input class="text-center dataothercost" type="text" name="txt_mak" id="txt_mak" style="width:100px;"></td>
                                  <td class="text-center" id="prc_mak"><span class="badge bg-blue">0.00%</span></td>
                                </tr>
                                <tr>
                                  <td class="text-left">PAPELERIA PLASTICOS</td>
                                  <td class="text-center col-md-1"><input class="text-center dataothercost" type="text" name="txt_pap" id="txt_pap" style="width:100px;"></td>
                                  <td class="text-center" id="prc_pap"><span class="badge bg-yellow">0.08%</span></td>
                                </tr>
                                <tr>
                                  <th class="text-left">TOTAL OTHER COST</th>
                                  <th class="text-center col-md-1"><input class="text-center" type="text" name="txt_tcosto" id="txt_tcosto" style="width:100px;" disabled="true"></th>
                                  <th class="text-center" id="prc_tcosto"><span class="badge bg-green">11.61%</span></th>
                                </tr>

                                <tr>
                                    <td class="trfull" colspan="3">
                                        <hr class="linea">                                       
                                    </td>
                                </tr> 

                                <tr>
                                  <td class="text-left">FONDOS DE RESERVA</td>
                                  <td class="text-center col-md-1"><input class="text-center datacred" type="text" name="txt_fond" id="txt_fond" style="width:100px;"></td>
                                  <td class="text-center" id="prc_fondo"><span class="badge">0.00%</span></td>
                                </tr>
                                <tr>
                                  <td class="text-left">COMISION TARJETAS</td>
                                  <td class="text-center col-md-1"><input class="text-center datacred" type="text" name="txt_tarj" id="txt_tarj" style="width:100px;"></td>
                                  <td class="text-center" id="prc_tarj"><span class="badge bg-blue">1.75%</span></td>
                                </tr>
                                <tr>
                                  <td class="text-left">RETENCION IVA</td>
                                  <td class="text-center col-md-1"><input class="text-center datacred" type="text" name="txt_iva" id="txt_iva" style="width:100px;"></td>
                                  <td class="text-center" id="prc_iva"><span class="badge bg-red">4.82%</span></td>
                                </tr>
                                <tr>
                                  <td class="text-left">RENTA 2%</td>
                                  <td class="text-center col-md-1"><input class="text-center datacred" type="text" name="txt_rent" id="txt_rent" style="width:100px;"></td>
                                  <td class="text-center" id="prc_rent"><span class="badge bg-yellow">0.98%</span></td>
                                </tr>
                                <tr>
                                  <th class="text-left">TOTAL TARJETAS DE CREDITO</th>
                                  <th class="text-center col-md-1"><input class="text-center" type="text" name="txt_tcred" id="txt_tcred" style="width:100px;" disabled="true"></th>
                                  <th class="text-center" id="prc_tcred"><span class="badge bg-green">7.55%</span></th>
                                </tr>

                                <tr>
                                    <td class="trfull" colspan="3">
                                        <hr class="linea">                                       
                                    </td>
                                </tr> 

                                <tr>
                                  <th class="text-left">TOTAL CONTROLABLES</th>
                                  <th class="text-center col-md-1"><input class="text-center" type="text" name="txt_control" id="txt_control" style="width:100px; " disabled></th>
                                  <th class="text-center" id="prc_control"><span class="badge bg-green">0%</span></th>
                                </tr>

                                <tr>
                                    <td class="trfull" colspan="3">
                                        <hr class="linea">                                       
                                    </td>
                                </tr>  

                                <tr>
                                  <th class="text-left">TOTAL OPERATIVOS</th>
                                  <th class="text-center col-md-1"><input class="text-center" type="text" name="txt_tope" id="txt_tope" style="width:100px;" disabled></th>
                                  <th class="text-center" id="prc_tope"><span class="badge bg-green">67.12%</span></th>
                                </tr>

                                <tr>
                                    <td class="trfull" colspan="3">
                                        <hr class="linea">                                       
                                    </td>
                                </tr>  

                                <tr>
                                  <th class="text-left">P.A.C.E</th>
                                  <th class="text-center col-md-1"><input class="text-center" type="text" name="txt_pace" id="txt_pace" style="width:100px;"></th>
                                  <th class="text-center" id="prc_pace"><span class="badge bg-green">32.88%</span></th>
                                </tr>                                    

                                <tr>
                                    <td class="trfull" colspan="3">
                                        <hr class="linea">                                       
                                    </td>
                                </tr> 
                                
                                <tr>
                                  <th class="text-left">CAJA</th>
                                  <th class="text-center col-md-1"><input class="text-center" type="text" name="txt_caja" id="txt_caja" style="width:100px;"></th>
                                  
                                </tr>  
                                <tr>
                                  <th class="text-left">AHORRO</th>
                                  <th class="text-center col-md-1"><input class="text-center dataothercost" type="text" name="txt_ahorro" id="txt_ahorro" style="width:100px;"></th>
                                  
                                </tr> 

                                <tr>
                                    <td class="trfull" colspan="3">
                                        <hr class="linea">                                       
                                    </td>
                                </tr> 
                                <?php 
                                  foreach ($socios as $socio) {
                                ?>
                                    <tr>
                                      <th class="text-left"><?php print $socio->nombre; ?> </th>
                                      <th class="text-center col-md-1"><input class="text-center  socio" type="text" style="width:100px;"></th>
                                      
                                    </tr>  
                                <?php 
                                  }
                                ?>
                                
<!--                                 <tr>
                                  <th class="text-left">JASMIN ROMERO</th>
                                  <th class="text-center col-md-1"><input class="text-center" type="text" name="txt_jaro" id="txt_jaro" style="width:100px;"></th>
                                  
                                </tr>  
                                <tr>
                                  <th class="text-left">JUAN ROMERO</th>
                                  <th class="text-center col-md-1"><input class="text-center" type="text" name="txt_juro" id="txt_juro" style="width:100px;"></th>
                                  
                                </tr>
 -->
















                              </table>
                            </div>                            
                        </div>



                      </div>

                    </div>
                    <!-- /.box-body -->
                    <div  align="center" class="box-footer">

                       <a id="rpt_xls" class="btn bg-light-blue color-palette btn-grad " href="#" data-original-title="" title=""><i class="fa fa-list-alt"></i> Exportar XLS </a> 

                        


               </div>
                </div>
              <!-- /.box -->
            </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

