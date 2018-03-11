<?php
    //session_start();
?>
<div class="row">
    <div class="col-sm-3 col-lg-2 no-padding">
        <nav class="navbar navbar-default sidebar" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"></a>
                </div>
                <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active" id="li-home" onclick=""><a href="#"><i class="fa fa-home fa-2x"></i> Home</a></li>
                        
                        <?php 
                            if($_SESSION['session']['usertype'] == 'superadmin'){
                              print '<li id="li-company"><a href="#" onclick=\'loadPage("company.php");\'><i class="fa fa-building fa-2x"></i> Empresa</a></li>';    
                            }
                            if($_SESSION['session']['usertype'] == 'superadmin' || $_SESSION['session']['usertype'] == 'admin'){ ?>
                                <li id="li-user"><a href="#" onclick="loadPage('user.php')"><i class="fa fa-user fa-2x"></i> Usuarios</a></li>
                                <li id="li-category"><a href="#" onclick="loadPage('category.php')"><i class="fa fa-cube fa-2x"></i> Categoria</a></li>
                                <li id="li-product"><a href="#" onclick="loadPage('product.php')"><i class="fa fa-cubes fa-2x"></i> Productos</a></li>
                                <li id="li-event"><a href="#" onclick="loadPage('event.php')"><i class="fa fa-calendar fa-2x"></i> Eventos</a></li>
                                <li id="li-inventory"><a href="#" onclick="loadPage('inventory.php')"><i class="fa fa-list fa-2x"></i> Inventario</a></li> 
                        <?php } ?>
                        
                        <li id="li-order"><a href="#" onclick="loadPage('order.php')"><i class="fa fa-file fa-2x"></i> Orden</a></li>
                        <li id="li-report"><a href="#" onclick="loadPage('report.php')"><i class="fa fa-bar-chart fa-2x"></i> Reporte</a></li>
                        <li id="li-salir"><a href="include/access/salir.php"><i class="fa fa-sign-out fa-2x"></i> Salir</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="col-sm-9 col-lg-10" id="content">
        <!-- page content -->
        <div class="padreD">
            <img src="./images/logo.png" alt="Kemok" style="margin-top: 22%; margin-left: 16%;">
        </div>
    </div>
    <div id="fade"></div>
    <div id="modal">
        <i class="fa fa-spinner fa-2x"></i>
    </div>
</div>