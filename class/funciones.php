<?php
require __DIR__ . '/../autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;

/**
 * 
 * Asigna el display partiendo del campo
 * @var $campo
 */
function __traducirDisplay($campo) {
	global $datos;

	if (isset($datos[$campo])) {
		return $datos[$campo];
	}else{
		return  $campo;
	}
}

/**
 * 
 * Funcion realiza la impresion por html
 * @param Araay/Integer/Text $_POST
 * @return Array
 */
function __imp($arr) {
	echo'<pre>';
	echo '<h1>IMPRESION</h1>';
	print_r($arr);
	echo '</pre>';
}

//---- Fechas Mysql -----//
function convertir_fecha($fecha){ // Convierte la fecha proveniente de la BD

	$fecha = explode("-",$fecha);
	$fecha_c = $fecha[2]."/".$fecha[1]."/".$fecha[0]; 
	return $fecha_c;
}

function convertir_fecha_mysql($fecha){ // Convierte la fecha para guardarla en la BD

	$fecha = explode("/",$fecha);
	$fecha_c = $fecha[2]."-".$fecha[1]."-".$fecha[0]; 
	return $fecha_c;
}

//function validate_and_check_form(){
 
//}

function boolAccess($strPage){
    if($_SESSION['session']['usertype'] == 'superadmin'){
        return true;
    }else{
        $arrAccess = ($_SESSION['session']['access']);
        if(in_array($strPage,$arrAccess)){
            return true;
        }
        return false;
    }
}

function printOrder($arrOrder,$intCopia=0){
        
    $intKey = key($arrOrder);
    $arrOrder = $arrOrder[$intKey];
    
    try {
        
        /* Information for the receipt */
        $items = array();
        $intMontoPagar = 0;
        $items[] = new item("Cant.  Producto", "" ,"Total");
        foreach ($arrOrder["detail"] as $detalle) {
            $cantidad = intval($detalle["quantity"]);
            $intMonto = number_format($cantidad*$detalle["unit_price"],2);
            $intMontoPagar+= $intMonto;            
            $strProducto = "(".$cantidad.") ".$detalle["product"];            
            $items[] = new item($strProducto,"",$intMonto);
        }
        
        
        // Enter the share name for your USB printer here
        //$connector = null;
        $connector = new CupsPrintConnector("printer_sales");

        /* Print a receipt" */
        $printer = new Printer($connector);
        
        //for($i = 0; $i < 2; $i++){
            
            /* Title */
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            
            if($intCopia == 1){
                $printer -> text("-----------\n");
                $printer -> text("COPIA DE CAJA\n");
                $printer -> text("NO VALIDO PARA COMPRAS\n");
                $printer -> feed();
            }
            
            $printer -> text("Ticket de compra\n");
            $printer -> selectPrintMode();
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text($arrOrder["creation_date"]."\n");
            $printer -> text("Orden No. ".$arrOrder["order_id"]."\n");
            $printer -> text("Atendido por: ".$arrOrder["user_name"]."\n");
            $printer -> feed();

            /* Items */
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            //$printer -> setEmphasis(true);
            //$printer -> text(new item('', 'Q'));
            $printer -> setEmphasis(false);
            foreach ($items as $item) {
                $printer -> text($item);
            }

            /* Tax and total */
            $printer -> feed();
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text("Total a pagar \n");
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("Q.". number_format($intMontoPagar,2)."\n");
            $printer -> selectPrintMode();

            /* Footer */
            $printer -> feed(2);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("Gracias por su compra\n");
            $printer -> text("Kemok\n");
            $printer -> text("info@kemok.io\n");
            $printer -> feed(3);
            
        //}

        /* Cut the receipt and open the cash drawer */
        $printer -> cut();
        $printer -> pulse();
        $printer -> close();
        
        return true;
        
        ?>
        <!--<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
        <html>
        <body onload="window.close();">
        </body>
        </html>-->
        <?php

    } catch (Exception $e) {
        //echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        return false;
    }  
}

/* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $unitPrice;
    private $price;
    private $symbolCurrent;

    public function __construct($name = '', $unitPrice = '', $price = '', $symbolCurrent = false)
    {
        $this -> name = $name;
        $this -> unitPrice = $unitPrice;
        $this -> price = $price;
        $this -> symbolCurrent = $symbolCurrent;
    }
    
    public function __toString()
    {
        $left = str_pad($this -> name, 18) ;
        $center = str_pad($this -> unitPrice, 6) ;
        
        $sign = ($this -> symbolCurrent ? 'Q ' : '');
        $right = str_pad($sign . $this -> price, 6, ' ', STR_PAD_LEFT);
        return "$left$center$right\n";
    }
}
?>