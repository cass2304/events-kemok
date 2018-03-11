<?php
    session_start();
    /*print "<pre>";
    print_r($_SESSION);
    print "</pre>";*/
    if (!isset($_SESSION['session'])){ 
        include_once "views/login.html";
    }else{
        include_once "views/home.php";
        /*print "<pre>";
        print_r($_SESSION['session']);
        print "</pre>";*/
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tickets Eventos</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="libs/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="libs/jquery/css/jquery-ui.min.css" />
    <!--<link rel="stylesheet" href="libs/jquery/notice/jquery.notice.css" />-->
    <link rel="stylesheet" href="libs/font-awesome-4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="styles/style.css" />
</head>
<body>
    <script src="libs/jquery/js/jquery-3.2.1.min.js"></script>
    <script src="libs/jquery/js/jquery-ui.min.js"></script>
    <!--<script src="libs/jquery/js/notice/jquery.notice.js"></script>-->
    <script src="libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="libs/bootstrap/js/bootstrap-notify.min.js"></script>
    <script src="scripts/library.js"></script>
    <script src="scripts/functions.js"></script>
</body>
</html>