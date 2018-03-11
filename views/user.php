<?php
    session_start();
    $ruta = '../';
    include_once $ruta.'class/clases.php';
    
    $filterCompany = "";
    if($_SESSION['session']['usertype'] != 'superadmin'){
        $filterCompany = " WHERE C.company_id = {$_SESSION['session']['company_id']}";
    }
    
    //validate_and_check_form();

    $tabla = "ev_user";
    $vista = "SELECT U.user_id, U.name, U.lastname, U.username, U.usertype, C.name AS company
              FROM ev_user U
    		INNER JOIN ev_company C ON U.company_id = C.company_id
                {$filterCompany}";
    $param=array(
    	'btnEliminar'	=>	true,
    	'btnVer'	=>	true,
    	'tabla'			=>	$tabla,
    	'div'			=>	true
    );
?>

<div class="div-wrappe">
    <div class="row row-header">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-3"><h3>Usuarios</h3></div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-9 text-right">
          <button type="button" class="btn btn-success" onclick="loadForm('<?php print $tabla; ?>',0);"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo</button>
        </div>
     </div>
     <div class="row row-content">
        <?php print $asis->listados($vista,'',$ocultar,'',$param); ?>
     </div>
</div>