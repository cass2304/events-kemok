<?php
/**
 * 
 * Clase que permite hacer la conexi�n y transacciones a la Base de Datos
 *
 */ 
Class BD{  
	private $servidor; 
	private $usuario; 
	private $password; 
	private $base_datos; 
	private $conexion; 
	private $consulta; 
	private $array; 
	private $tipo; 
	private $error;
	private $sqlite;
	
	/**
	 * 
	 * Contructor
	 * @param Text $tipo			Tipo de conexion mysql/sqlite
	 * @param Text $base_datos		Nombre de la Base de datos
	 * @param Text $servidor		Nomnbre del servidor
	 * @param Text $usuario			Nombre del Usuario del Servidor
	 * @param Text $password		Clave de acceiso
	 */
	function BD(){ 
		$this->tipo = $_SESSION['config']['bd']['tipo'];
		$this->servidor = $_SESSION['config']['bd']['servidor'];
		$this->usuario = $_SESSION['config']['bd']['usuario'];
		$this->password = $_SESSION['config']['bd']['password'];
		$this->base_datos = $_SESSION['config']['bd']['base_datos'];
		$this->conectar(); 
		
	} 
 
   /**
    * 
    * Conexi�n a la Base de Datos
    */
   public function conectar(){ 
		 switch ($this->tipo){ 
			case 'mysql':
					$this->conexion=mysqli_connect($this->servidor, $this->usuario, $this->password);
					if (!$this->conexion) {
						die('Error de conexion: ' . mysqli_error());
					}else{
						mysqli_select_db($this->conexion,$this->base_datos)or die(mysqli_error());
					}
			break; 
			case 'sqlite':
					$this->sqlite = new SQLiteDatabase($this->base_datos, 0777, $error);
			break; 
	   } 
   } 
 
   /**
    * 
    * Ejecuta la sentecnia SQL
    * @param Text $sql	La sentencia de SQL
    */ 
   function consulta($sql){ 
		if ($sql == "") {
			$this->error = "No ha especificado una consulta SQL";
			return 0;
		}
	  switch ($this->tipo){ 
			case 'mysql': 
				$this->consulta=mysqli_query($this->conexion, $sql);  //or die(mysql_error())
			break; 
			case 'sqlite':
				$this->consulta= $this->sqlite->query($sql);
			break; 
      } 
       return $this->consulta; 
   } 
 
   /**
    * 
    * Crear un arreglo de una consulta de Base de Datos
    */
	function fetch_array(){
	   switch ($this->tipo){ 
			case 'mysql':    
				$row = mysqli_fetch_array($this->consulta);
			break; 
			case 'sqlite':
				$row = $this->consulta->fetchArray();
			break; 
      } 
	   return $row;
	}

   /**
    * 
    * Crear un arreglo a assoc de una consulta de Base de Datos
    */
	function fetch_assoc(){
	   switch ($this->tipo){ 
			case 'mysql':    
				$row = mysqli_fetch_assoc($this->consulta);
			break; 
			case 'sqlite':
				$row = $this->consulta->fetch(SQLITE_ASSOC);
			break; 
      } 
	   return $row;
	}

   /**
    * 
    * Crear un arreglo a row de una consulta de Base de Datos
    */
	function fetch_row(){
	   switch ($this->tipo){ 
			case 'mysql':    
				$row = mysqli_fetch_row($this->consulta);
			break; 
			case 'sqlite':
				$row = $this->consulta->fetch(SQLITE_NUM);
			break; 
      } 
	   return $row;
	}
		
   /**
    * 
    * Crear un arreglo a row de una consulta de Base de Datos
    */
	function num_rows(){
	   switch ($this->tipo){ 
			case 'mysql':    
				$row = mysqli_num_rows($this->consulta);
			break; 
			case 'sqlite':
				$row = $this->consulta->numRows();
			break; 
      } 
	   return $row;
	}
   /**
    * 
    * Devuelve el ultimo ID insertado.
    */
   function lastID(){ 
      	   switch ($this->tipo){ 
			case 'mysql':    
				 return mysqli_insert_id($this->conexion); 
			break; 
			case 'sqlite':
				return $this->sqlite->lastInsertRowID();
			break; 
      }
   }
   
	function affectedRows(){
		switch ($this->tipo){ 
			case 'mysql':    
				 return mysqli_affected_rows($this->conexion); 
			break; 
			case 'sqlite':
				return $this->sqlite->changes();
			break;
			default:
   			break;
		}
	}
	
	function cerrarConsulta() { //print $this->tipo;
   		switch ($this->tipo) {
   			case 'mysql':
   				mysqli_free_result($this->consulta);
   			break;
			case 'sqlite': //$this->sqlite->finalize();
   			break;
			default:
   			break;
   		}
   }
   function cerrarConexion() {
   		switch ($this->tipo) {
   			case 'mysql':	mysqli_close($this->conexion);
   			break;	
			case 'sqlite': $this->sqlite->close();
   			break;
			default:
   			break;
   		}
   }
   /**
    * 
    * Ejecuta el escape string
    * @param cualqueirTipo $dato 
    */
   function escape_string($dato) {
	   switch ($this->tipo) {
	   		case 'mysql':
	   			$valor =  $dato;//mysql_escape_string($dato);
	   		break;
			case 'sqlite': $valor = $dato;//$this->sqlite->escapeString($dato);
   			break;
			default:
   			break;
	   }
	   return $valor;
   }
 
} 

?>