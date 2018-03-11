<?php
/**
 * 
 * Clase creada para las Transacciones del BD
 *
 */
class Save extends BD{
	private $htmlOut01;
	private $htmlOut02;
	private $query;
	private $select;
	private $where;
	private $tabla;
	private $tablaPrimara;
	private $result01;
	private $result02;
	private $result03;
	private $rs01;
	private $rs02;
	private $rs03;
	private $campos;
	private $values;
	
	/**
	 * 
	 * Enter description here ...
	 * @param Array $arreglo
	 * @param Boolean $retornarID
	 * @param Boolean $verQuery
	 */
	function guardar($arreglo,$retornarID=true,$verQuery=false) {
		/**
		 * 
		 * Metodo que guarda en la base de datos
		 * @param $arreglo[] contiene todo el POST
		 */
		$arreglo['primarykey'] = $this->GetPK($arreglo['tabla']);
		//set_error_handler("customError");
		if ($verQuery){
			__imp($arreglo);
		}
		$this->select = '';
		$this->campos = array();
		/*
		 *  Crea el arrglo campos que filtra para los campos invlucrados partiendo de una Tabla y POST recibido
		 */
		$this->campos = array();
		$this->select = '';
		foreach ($arreglo as $key=>$value) {
                    if (stripos($key,'_')) {
                        if ($arreglo['nombreModulo'].'_'.$arreglo['primarykey'] != $key) {
                            
                            if(str_replace($arreglo['nombreModulo'].'_', '', $key) == "password"){
                                $value = MD5($value);
                            }
                            if(str_replace($arreglo['nombreModulo'].'_', '', $key) == "start_date" || str_replace($arreglo['nombreModulo'].'_', '', $key) == "end_date"){
                                $value = convertir_fecha_mysql($value);
                            }
                            $this->campos[str_replace($arreglo['nombreModulo'].'_', '', $key)]=$this->escape_string(trim($value));
                        }
                    }
		}
		/*
		 * Crear el Query para ser utilizado para el almacenamiento partiendo del arreglo
		 * modo = INSERT, UPDATE, DELETE
		 */
		switch ($arreglo['modo']) {
			case 'insert':
                            $this->select = 'INSERT INTO '.$arreglo['tabla'].' ('.join(', ', array_keys($this->campos)).') VALUES '."('".join("', '", array_values($this->campos))."')";
                            //print $this->select;
                        break;
			case 'update':
                            $keys = array_keys($this->campos);
                            $values = array_values($this->campos);
                            if ($_SESSION['config']['bd']['tipo'] == 'mysql') {
                                    for ($i = 0; $i < count($keys); $i++) {
                                            $this->select .= $keys[$i].'='."'".$values[$i]."',";
                                    }
                                    $this->select = substr($this->select,0,-1);
                                    $this->select = 'UPDATE '.$arreglo['tabla'].' SET '.$this->select.' WHERE '.$arreglo['primarykey'].'='."'".$arreglo['id']."'";
                            }elseif ($_SESSION['config']['bd']['tipo'] == 'sqlite') {
                                    for ($i = 0; $i < count($keys); $i++) {
                                            $this->select .= $keys[$i].'='."'".$values[$i]."',";
                                    }
                                    $this->select = substr($this->select,0,-1);
                                    $this->select = 'UPDATE '.$arreglo['tabla'].' SET '.$this->select.' WHERE '.$arreglo['primarykey'].'='."'".$arreglo['id']."'";
                            }
					
			break;
			case 'delete':
                            $this->select = "UPDATE ".$arreglo['tabla']." SET active = 'N' WHERE ".$arreglo['primarykey']."=".$arreglo['id'];
			break;
			default:
				echo 'error verificar MODO';
			break;
		}
		
		if ($_SESSION['config']['bd']['tipo'] == 'mysql') {
			/*
			 * Apertura la transacciones en la Base de Datos
			 */
			$this->query = 'START TRANSACTION;';
			$this->rs01 = $this->consulta($this->query);
			/*
			 * Alamcena o Actualiza en la tabla 
			 */
			$this->query = $this->select;
			if ($verQuery){
				echo '<h4>'.$this->query.'</h4>';
			}
			$this->rs01 = $this->consulta($this->query);
			$tabla_rel = substr($arreglo['tabla'], 0,1);
			if (($arreglo['modo'] == 'insert') && ($retornarID)) {
				$arreglo['nuevo_id'] = $this->lastID();                                
			}else {
				$arreglo['nuevo_id'] = 0;
			}
			/*
			 * Cierra la transaccion en la Base de datos
			 */
			if ($this->rs01===false) {
				$this->query = 'ROLLBACK';
				$arreglo['status'] = 'fail';
				//$_SESSION['MSG'] = __messages($param=array('Type'=>'error','Title'=>__traducirDisplay('GuradarFAIL')));	
			}else{
				$this->query = 'COMMIT';
				$arreglo['status'] = 'done';
				//$_SESSION['MSG'] = __messages($param=array('Type'=>'adv','Title'=>__traducirDisplay('GuardarDONE')));
			}
			$this->rs01 = $this->consulta($this->query);
                        
                        
                        if(($arreglo['status'] = 'done') && ($arreglo['modo'] == 'insert') && ($arreglo['tabla'] == 'ev_company')){
                            $arrUsers = array();
                            //$newpass = substr(md5(uniqid(rand())), 0, 8);
                            $arrUsers["admin"] = array("name" => "admin_0".$arreglo['nuevo_id'], "password" => '', 'user_id' => 0);
                            $arrUsers["seller"] = array("name" => "vendedor_0".$arreglo['nuevo_id'], "password" => '', 'user_id' => 0);
                            
                            foreach($arrUsers AS $usertype=>$users){
                                $newpass = substr(md5(uniqid(rand())), 0, 8);
                                $this->query = "INSERT INTO ev_user (company_id, name, usertype, creation_date, uid_creator, username, password) VALUES (".$arreglo['nuevo_id'].",'".$users["name"]."','".$usertype."','".date('Y-m-d H:i')."',".$_SESSION['session']['user_id'].",'".$users["name"]."','".md5($newpass)."')";
                                $this->rs01 = $this->consulta($this->query);
                                $arrUsers[$usertype]["password"] = $newpass;
                                $arrUsers[$usertype]["user_id"] = $this->lastID();
                            }
                            
                            $arreglo['users'] = $arrUsers;
                         }
                        
		}
		//$this->Auditoria($arreglo);
		return $arreglo;
	}

	/**
	 * 
	 * Alamcena todos los registros por usuarios
	 * @param unknown_type $arreglo
	 */
	function Auditoria($arreglo) {
		if (!is_numeric($arreglo['modo'])) {
			if ($arreglo['modo'] == 'insert') {
				$tipoaccion = 1;
			}elseif ($arreglo['modo'] == 'update'){
				$tipoaccion = 2;
			}elseif ($arreglo['modo'] == 'delete'){
				$tipoaccion = 3;
			}
		}else {
			$tipoaccion = $arreglo['modo'];
		}
		$datos = var_export($arreglo,true);
		$datos1 = str_replace("'", '', $datos);
		if ($_SESSION['config']['bd']['tipo'] == 'mysql') {
			$q = 'INSERT INTO auditoria (Detalle,ID_TipoAccion,ID_Usuario,NombreModulo) VALUES('.
			"'".$datos1.
			"','".$tipoaccion.
			"','2".
			"','".$arreglo['nombreModulo'].
			"')";	
		}
		
		$this->consulta($q);		
	}
	
	function convertirHTTPS() {
		if ($_SERVER['HTTPS']!='on'){
			header('Location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
			return ;
		}
	}
	
	function phpinfo() {
		/**
		 * Obtiene la informacion del servidor PHP sin CSS
		 * 
		 */
		
		ob_start();                                                                                                        
		phpinfo();                                                                                                         
		$info = ob_get_contents();                                                                                         
		ob_end_clean();                                                                                                    
		
		$info = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $info);
		echo $info;
	}

	function sendEmail($param=array()) {
		set_error_handler("customError");
		require_once "lib/pear-mail/Mail.php";

		$from = $param['from'];
		$to = $param['to'];
		$subject = $param['subject'];
		$body = $param['body'];
		
		$host = "ssl://smtp.gmail.com";
		$port = "465";
		$username = "giosystem";
		$password = "caminante";
		 
		$headers = array ('From' => $from,
		   'To' => $to,
		   'Subject' => $subject
		);

		$smtp = Mail::factory('smtp',
		   array ('host' => $host,
		     'port' => $port,
		     'auth' => true,
		     'username' => $username,
		     'password' => $password)
		 );
		 
		$mail = $smtp->send($to, $headers, $body);
		 
		if (PEAR::isError($mail)) {
		  	return __messages($param=array('Type'=>'error','Title'=>'Envio de E-Mail','MSG'=>'Problemas al enviar'));
		}else{
			return __messages($param=array('Type'=>'adv','Title'=>'Envio de E-Mail','MSG'=>'Fue enviado sin problemas'));
		}
		
	}

	/**
	 * 
	 * Se Obtiene PrimaryKey de una Tabla
	 * @param Text $tabla
	 * @return PK
	 */
	function GetPK($table) {
            $this->query = "SELECT PK FROM metatabla WHERE lower(Nombre)='".strtolower($table)."'";

            $this->consulta($this->query);
            if ($this->num_rows() > 0) { 
                    $this->rs01 = $this->fetch_row();
                    $pk = $this->rs01[0];
                    $this->cerrarConsulta();
                    return $pk;
            }else{
                    return false;
            }	
	}
        
        function guardarInventory($arreglo){            
            if($arreglo['inventory_id'] == 0){
                $this->select = "INSERT INTO ev_inventory (event_id,creation_date,uid_creator) VALUES ({$arreglo['slc_event']},NOW(),'{$_SESSION['session']['user_id']}')";
                $this->rs01 = $this->consulta($this->select);
                $intInventory = $this->lastID();
            }else{
                $intInventory = $arreglo['inventory_id'];
            }
            
            if($intInventory > 0){
                $arreglo['nuevo_id'] = $intInventory;
                $arreglo['status'] = 'done';
                if(isset($arreglo['modo']) && $arreglo['modo'] == 'supply'){
                   $this->select = "INSERT INTO ev_inventory_detail (inventory_id,product_id,quantity,price,status) VALUES ({$intInventory},{$arreglo['product']},{$arreglo['quantity']},{$arreglo['price']}, 'supply')";
                   $this->rs01 = $this->consulta($this->select); 
                   
                   $intDetail = $this->lastID();                   
                   if($intDetail > 0){
                       $this->select = "UPDATE ev_inventory_detail SET quantity = quantity + {$arreglo['quantity']} WHERE inventory_id = {$intInventory} AND product_id = {$arreglo['product']} AND status = 'initial'";
                       $this->rs01 = $this->consulta($this->select); 
                   }
                   
                }else{
                    $arrDetail = $arreglo['inv']['quantity'];
                    foreach($arrDetail AS $key=>$detail){
                        if($detail > 0){                 
                           $intPrice = $arreglo['inv']['price'][$key]; 
                           $this->select = "INSERT INTO ev_inventory_detail (inventory_id,product_id,quantity,price,quantity_initial) VALUES ({$intInventory},{$key},{$detail},{$intPrice},{$detail})";
                           $this->rs01 = $this->consulta($this->select);
                        }
                    }
                }
                
            }else{
                $arreglo['status'] = 'fail';
            }
            
            return $arreglo;
        }
        
        function guardarOrder($arreglo){            
            $this->select = "INSERT INTO ev_order(event_id,creation_date,uid_creator) VALUES ({$arreglo['slc_event']},NOW(),'{$_SESSION['session']['user_id']}')";
            $this->rs01 = $this->consulta($this->select);
            $intOrder = $this->lastID();
            
            if($intOrder > 0){
                $this->select = "SELECT inventory_id FROM ev_inventory WHERE event_id = ".$arreglo['slc_event'];
                $this->consulta($this->select);
                $inventory_id = 0;
                
                if ($this->num_rows() > 0) { 
                    $this->rs01 = $this->fetch_assoc();
                    $inventory_id = $this->rs01["inventory_id"];
                }
                
                $arreglo['nuevo_id'] = $intOrder;
                $arreglo['status'] = 'done';
                
                $arrDetail = $arreglo['det']['product']; 
                $intTotalOrder = 0;
                foreach($arrDetail AS $detail){ 
                    if($detail > 0){                 
                       $intUnitPrice = $arreglo['det']['price'][$detail]; 
                       $intQuantity = $arreglo['det']['cant'][$detail]; 
                       $intTotalOrder+= intval($intQuantity)*floatval($intUnitPrice);
                       $this->select = "INSERT INTO ev_order_detail (order_id,product_id,quantity,unit_price) VALUES ({$intOrder},{$detail},{$intQuantity},{$intUnitPrice})";
                       $this->rs01 = $this->consulta($this->select);
                       $intDetail = $this->lastID(); 
                       
                       if($intDetail > 0){                           
                            $this->select = "UPDATE ev_inventory_detail SET quantity_sold = quantity_sold+{$intQuantity} WHERE inventory_id = {$inventory_id} AND product_id = {$detail} AND status = 'initial' ";
                            $this->consulta($this->select);
                       }                       
                    }
                }
                $this->select = "UPDATE ev_order SET order_amount = {$intTotalOrder} WHERE order_id = {$intOrder}";
                $this->rs01 = $this->consulta($this->select);
            }else{
                $arreglo['status'] = 'fail';
            }
            
            return $arreglo;
        }
        
}


$guardar = new Save();