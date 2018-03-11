<?php
    session_start();
    $ruta = '../';
    include_once $ruta.'class/clases.php';
    
    if(!boolAccess('printer')){
        header('Location:../index.php');
    }
    
    $filterCompany = "";
    if($_SESSION['session']['usertype'] != 'superadmin'){
        $filterCompany = " WHERE company_id = {$_SESSION['session']['company_id']}";
    }
    
    $tabla = "ev_printer";    
?>

<div class="div-wrappe">
    <div class="row row-header">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-3"><h3>Configuracion Impresora</h3></div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-9 text-right"></div>
     </div>
     <div class="row row-content">
         
     </div>
</div>
<script>
    loadForm('<?php print $tabla; ?>',0);
</script>