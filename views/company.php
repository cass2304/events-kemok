<?php
    session_start();
    $ruta = '../';
    include_once $ruta.'class/clases.php';
    
    if(!boolAccess('company')){
        header('Location:../index.php');
    }

    $tabla = "ev_company";
    $vista = 'SELECT * FROM '.$tabla;
    $param=array(
    	'btnEliminar'	=>	true,
    	'btnVer'	=>	true,
    	'tabla'			=>	$tabla,
    	'div'			=>	true
    );
?>

<div class="div-wrappe">
    <div class="row row-header">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-3"><h3>Empresa</h3></div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-9 text-right">
          <button type="button" class="btn btn-success" onclick="loadForm('<?php print $tabla; ?>',0);"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo</button>
        </div>
     </div>
     <div class="row row-content">
        <?php print $asis->listados($vista,'',$ocultar,'',$param); ?>
     </div>
</div>