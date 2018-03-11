<?php
session_start();
/*
 *Inclusion de Arreglos 
 */
$ruta = '../';
include_once $ruta.'class/clases.php';

if(isset($_POST["loadForm"])){
    $arrReturn = array();
    $intID = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $tabla = isset($_POST['tabla']) ? $_POST['tabla'] : '';

    $modo = ($intID > 0) ? 'update' : 'insert';
    $asis = new asis(); //$tabla,$modo='insert',$tab=0,$id=0,$pkey='',$nombreModulo='mod_',$call=false,$form=true,$tabs=false
    $arrResult = $asis->form($tabla,$modo,0,$intID,$pkey="",$tabla,$call="",true);

    if(!empty($arrResult)){
         $arrReturn["status"] = "ok";
         $arrReturn["result"] = $arrResult;
    }else{
         $arrReturn["status"] = "fail";
    }
    print json_encode($arrReturn);
    die();
}

if(isset($_POST["saveForm"])){
    $arrReturn = array();
    $ultimo = array();
    if($_POST['tabla'] == 'ev_inventory' || $_POST['tabla'] == 'ev_inventory_detail'){
        $ultimo = $guardar->guardarInventory($_POST);
    }else{
        $ultimo = $guardar->guardar($_POST,true); 
    }
    
    //print_r($ultimo);
    if($ultimo['status'] == 'done'){
        $arrReturn["status"] = "ok";
        $arrReturn["id"] = $ultimo['nuevo_id'];
        if(isset($ultimo['users'])){
            $msj = "<br><p>Se crearon los siguientes usuarios:</p>";
            foreach($ultimo['users'] AS $key=>$users){
                $msj.= "<b>Usuario (".$key."):</b> ".$users['name']." - <b>Password: </b>".$users['password']."<br>"; 
            }
            $arrReturn['msj'] = $msj;
        }
    }else{
        $arrReturn["status"] = "fail";
    }
    print json_encode($arrReturn);
    die();
}

if(isset($_POST["saveOrder"])){
    $arrReturn = array();
    $ultimo = array();
    
    $ultimo = $guardar->guardarOrder($_POST);
   
    if($ultimo['status'] == 'done'){
        $arrReturn["status"] = "ok";
        $arrReturn["id"] = $ultimo['nuevo_id'];      
    }else{
        $arrReturn["status"] = "fail";
    }
    print json_encode($arrReturn);
    die();
}

if(isset($_POST["deleteReg"])){
    $arrReturn = array();
    $arrParams = array('tabla'=>$_POST['tabla'],'modo'=>'delete','id'=>$_POST['id']);
    $ultimo = $guardar->guardar($arrParams,true);
    if($ultimo['status'] == 'done'){
        $arrReturn["status"] = "ok";
    }else{
        $arrReturn["status"] = "fail";
    }
    print json_encode($arrReturn);
    die();
}

if(isset($_POST['getCategory'])){
   $arrReturn = array(); 
   $intCompany = $_POST['company'];
   $arrResult = $qrys->getCategory($intCompany); 
   
   if($arrResult){
       $arrReturn["status"] = "ok"; 
       $arrReturn["result"] = $arrResult; 
   }else{
       $arrReturn["status"] = "fail"; 
   }
   print json_encode($arrReturn);
   die();  
}

if(isset($_POST['getProduct'])){
   $arrReturn = array(); 
   $intEvent = $_POST['event'];
   $boolOrder = isset($_POST['boolOrder']) ? $_POST['boolOrder'] : false; 
   $arrResult = $qrys->getProductInventory($intEvent,$boolOrder); 
   
   if($arrResult){
       $arrReturn["status"] = "ok"; 
       $arrReturn["result"] = $arrResult; 
   }else{
       $arrReturn["status"] = "fail"; 
   }
   print json_encode($arrReturn);
   die(); 
}

if(isset($_POST['getOrders'])){
   $arrReturn = array(); 
   $intOrder = isset($_POST['order_id']) ? $_POST['order_id'] : 0; 
   $boolDate = isset($_POST['boolDate']) ? $_POST['boolDate'] : false; 
   $intEvent = isset($_POST['event_id']) ? $_POST['event_id'] : 0; 
   if($intEvent > 0){
       $boolDate = false;
   }
   $arrResult = $qrys->getOrder($intOrder,$boolDate,$intEvent); 
   
   if($arrResult){
       $arrReturn["status"] = "ok"; 
       $arrReturn["result"] = $arrResult; 
   }else{
       $arrReturn["status"] = "fail"; 
   }
   print json_encode($arrReturn);
   die(); 
}

if(isset($_GET['printOrder'])){
    $arrReturn = array(); 
    $intID = $_GET['id'];
    $intCopia = $_GET['copia'];
    $arrOrder = $qrys->getOrder($intID); 
    if($arrOrder){
        $boolPrint = printOrder($arrOrder,$intCopia);
        if($boolPrint){
            $arrReturn["status"] = "ok"; 
        }else{
            $arrReturn["status"] = "fail"; 
        }        
    }else{
        $arrReturn["status"] = "fail"; 
    }
    print json_encode($arrReturn);
    die();
}

?>
