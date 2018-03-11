<?php 
$urlA = explode('/', $_SERVER['REQUEST_URI']);
$url = 'http://'.$_SERVER['HTTP_HOST'].'/'.$urlA[1];
header('Location: '.$url);
?>