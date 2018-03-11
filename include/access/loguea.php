<?php
session_start();
$ruta = '../../';
include_once $ruta.'class/clases.php';

if(isset($_POST['login']) && (!empty($_POST['username']) && !empty($_POST['password']))){
    $arrReturn = array();
    $parametro = array();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT *
            FROM ev_user
            WHERE username ='".$username."' AND password = '".md5($password)."' ";
    //print $sql;
    $res = $bd->consulta($sql);
    $fila = $bd->fetch_assoc();
    /*print "<pre>";
    print_r($fila);
    print "</pre>";*/
    if ($fila){
        // Si estan en la base de datos registra la id de usuario
        $parametro = $fila;
        // Variables de Sesion
        $_SESSION['session'] = $parametro;
        
        //Accesos
        if($fila['usertype'] != 'superadmin'){
            $_SESSION['session']['access'] = $qrys->getAccess($fila['usertype']);
        }else{
            $_SESSION['session']['access'] = 'all';
        }
        $qry = 'UPDATE ev_user SET last_access = "'.date('Y-m-d h:m:s').'" WHERE user_id = '.$fila['user_id'];
        $bd->consulta($qry);
        $arrReturn['status'] = 'ok';
    }else{
        $arrReturn['status'] = 'fail';
        $arrReturn['error'] = array('titulo'=>'Error: Fallo de Autenticacion','descripcion'=>'Usuario o clave no validos');
    }
    print json_encode($arrReturn);
    die();
}
?>
