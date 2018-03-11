<?php 
class Asis extends BD{
	/** Clase para crear formularios partiendo de una tabla*/
	private $tabla;
	private $htmlOut01;
	private $htmlOut02;
	private $htmlOut03;
	private $htmlOut04;
	private $htmlOut05;
	private $htmlOut06;
	private $query;
	private $result01;
	private $result02;
	private $result03;
	private $rs01;
	private $rs02;
	private $rs03;

	/**
	* Regresa el La estrucutra de la Base de datos
	* @param $tabla es el Nombre de la tabla
	* @return array Query
	**/
	function descripcionTabla($tabla) {
		if ($_SESSION['config']['bd']['tipo'] == 'mysql') {
			$this->query = 'describe '.$tabla;
		}elseif($_SESSION['config']['bd']['tipo'] == 'sqlite'){
			$this->query = 'PRAGMA table_info('.$tabla.')';
		}
		return $this->query;
	}

	/**
	 * Crear un formulario automaticamente partiendo de un tabla
	 * @param $tabla
	 * @param $id
	 * @param $modo
	 * @param $tabs
	 * @return Html
	 */	
	function form($tabla,$modo='insert',$tab=0,$id=0,$pkey='',$nombreModulo='mod_',$call=false,$form=true,$tabs=false) {
		$query = 'SELECT PK FROM metatabla WHERE lower(Nombre)='."'".strtolower($tabla)."'";
		//__imp($query);
		$result = $this->consulta($query);

		if ($this->num_rows() > 0) { //if ($this->num_rows()>0) {
			$rs = $this->fetch_row();
			$pkey = trim($rs[0]);
		}
		if ($id > 0){
			$this->rs03 = $this->consultaUnRegistro($tabla, $id,$pkey);
		}
		$this->htmlOut01 = '';
		if($tabs){
			$this->htmlOut01 .= '<div id="tabs">';
			$this->htmlOut01 .= '<ul>';
			$this->htmlOut01 .= '<li><a href="#tabs-1">'.__traducirDisplay($tabla).'</a></li>';
			$this->htmlOut01 .= '</ul>';
			$this->htmlOut01 .= '<div id="tabs-1">';
		}

		if ($form) {
			$this->htmlOut01 .= '<form id="form_'.$tabla.'" name="form_'.$tabla.'" method="post" data-toggle="validator" role="form">';
		}
		$this->htmlOut01 .= 			'<div class="ContenedorHidden">';
		$this->htmlOut01 .= 				'<input type="hidden" name="modo" value="'.$modo.'" />';
		$this->htmlOut01 .= 				'<input type="hidden" id="nombreModulo" name="nombreModulo" value="'.$nombreModulo.'"/>';
		$this->htmlOut01 .= 				'<input type="hidden" name="tabla" value="'.$tabla.'"/>';
		$this->htmlOut01 .= 				'<input type="hidden" name="id" value="'.$id.'"/>';
		$this->htmlOut01 .= 				'<input type="hidden" name="uid_creator" value="'.$_SESSION['session']['user_id'].'"/>';
		$this->htmlOut01 .= 				'<input type="hidden" name="creation_date" value="'.date('Y-m-d H:i').'"/>';
		$this->htmlOut01 .= 			'</div>';
		
		$this->result01 = $this->consulta($this->descripcionTabla($tabla));
		$i=0;
		$row = 0;
		$this->htmlOut01 .='<div class="formContainer"><table class="asis">'; //class="asis"
		while ($this->rs01 = $this->fetch_assoc()) { 
			//__imp($this->rs01);
			/*
			 * Estadariza el array partiendo de la estrcutra de la base de datos
			 */
			if ($_SESSION['config']['bd']['tipo'] == 'mysql'){
				$type = 'Type';
				$field = 'Field';
				$pk = 'Key';
			}elseif($_SESSION['config']['bd']['tipo'] == 'sqlite'){
				$type = 'type';
				$field = 'name';
				$pk = 'pk';
			}

            $this->rs01['Table'] = $tabla;
            $tipo = explode('(', $this->rs01[$type]);
            $this->rs01[$type] = trim($tipo[0]);

            /*print "<pre>";
            print_r($tipo);
            print "</pre>";*/
            if($this->rs01[$type]!='longblob' && $this->rs01[$type]!='text' && $this->rs01[$type]!='longtext' && $this->rs01[$type]!='date' && $this->rs01[$type]!='datetime'){
                $longitud = explode(')', $tipo[1]);
                $this->rs01['Length'] = $longitud[0];
            }

            $this->rs01[$type] = $tipo[0];
            $this->rs01['Length'] = $longitud[0];
            unset($this->rs01['Extra']);
            //unset($this->rs01['Default']);
            if ($this->rs01[$pk] == 'MUL') {
                $relacion = explode('_id', $this->rs01[$field]);
                $this->rs01['Relation'] = strtolower($relacion[0]);
            }else{
                $this->rs01['Relation'] ='';
            }
			
			$class = ($row == 1?'class="par"':'');
			$this->htmlOut01 .= '<tr '.$class.'>';
			if (!$this->campos_ocultos($this->rs01)) {
				$this->htmlOut01 .= '<td style="width:20%;"><strong>'.__traducirDisplay($this->rs01[$field]).'</strong></td>';
				$this->htmlOut01 .= '<td>'.$this->crearElementoHTML($tabla, $nombreModulo, $this->rs01,$this->rs03).'</td>';
				
			}else{
				$row = ($row == 1?0:1);
			}
			
			$this->htmlOut01 .= '</tr>';
			$row = ($row == 1?0:1);
		}
		
		$this->htmlOut01 .= '</table></div>';
		$this->htmlOut01 .= $this->botones($call,$tabla);
		if ($form) {
			$this->htmlOut01 .= '</form>';
		}
		if($tabs){
			$this->htmlOut01 .= '</div></div>';
		}
		//$bd->cerrarConsulta($this->result01);
		
		return $this->htmlOut01;
		
	}
	
	/**
	 * 
	 * Crear los botones
	 * @param unknown_type $call
	 */
	function botones($call,$tabla) {
		$this->htmlOut01 .= 		'<center><div class="demo">';
		//$this->htmlOut01 .= 				'<button  class="btn btn-primary" type="button" name="btnNuevo" onclick=cargar_pagina("","","","'.$tabla.'");>'.__traducirDisplay('Nuevo').'</button>';
		$this->htmlOut01 .= 				'<button  class="btn btn-success" type="button" name="Guardar" onclick=saveForm("'.$tabla.'");>'.__traducirDisplay('Guardar').'</button>';
		$this->htmlOut01 .= 		'</div></center>';
		
	}
	
	/**
	 * 
	 * Crea el elemento HTML partiendo del tipo de datos
	 * @param unknown_type $tabla
	 * @param unknown_type $nombreModulo
	 * @param unknown_type $campo
	 * @param unknown_type $valor
	 */
	function crearElementoHTML($tabla,$nombreModulo,$campo,$valor=0) {
                if ($_SESSION['config']['bd']['tipo'] == 'mysql'){
				$tipo = 'Type';
				$field = 'Field';
				$pk = 'Key';
		}elseif($_SESSION['config']['bd']['tipo'] == 'sqlite'){
				$tipo = 'type';
				$field = 'name';
				$pk = 'pk';
		}
		
                $required = '';
                if ((isset($campo['Null']) && $campo['Null'] == 'NO')) {
                    $required = ' validate="true" ';
		}
                
		$this->htmlOut02 = '<div class="form-group">';
		
		if (($campo['Key'] == 'PRIMARY KEY') || ($campo['Key'] == 'PRI') || ($campo[$pk] == 1)) {
			$this->htmlOut02 .= '<input type="hidden" name="primarykey" value="'.$campo[$field].'"/>';
			$this->htmlOut02 .= '<input type="text" id="'.$campo[$field].'" name="'.$nombreModulo.'_'.$campo[$field].'"  value="'.trim($valor[$campo[$field]]).'" class="numerico" readonly="readonly" />';
		}elseif (($campo['Key'] == 'FOREIGN KEY') || ($campo['Key'] == 'MUL')){
			$this->htmlOut02 .= $this->crearSelect($campo,$nombreModulo,$tabla,$valor);
		}else{
                    if (($campo[$tipo] == 'text' || ($campo[$tipo] == 'longtext'))) {
                            $this->htmlOut02 .= '<textarea rows="4" type="text" id="'.$campo[$field].'" name="'.$nombreModulo.'_'.$campo[$field].'" maxlength="'.$campo['Length'].'" class="'.$this->traerClases($this->rs01).'">'.trim($valor[$campo[$field]]).'</textarea>';
                    }elseif($campo[$tipo] == 'smallint'){
                            $this->htmlOut02 .= '<input type="checkbox"  id="'.$campo[$field].'" name="'.$nombreModulo.'_'.$campo[$field].'" maxlength="'.$campo['Length'].'" class="'.$this->traerClases($this->rs01).'" size="'.$campo['Length'].'" value="'.trim($valor[$campo[$field]]).'" '.($valor[$campo[$field]]>0?' checked="checked"':'').' >';
                    }elseif($campo[$tipo] == 'varbinary'){
                            $this->htmlOut02 .= '<input type="password"  id="'.$campo[$field].'" name="'.$nombreModulo.'_'.$campo[$field].'" maxlength="'.$campo['Length'].'" class="'.$this->traerClases($this->rs01).'" size="'.$campo['Length'].'" value="'.trim($valor[$campo[$field]]).'" '.($valor[$campo[$field]]>0?' checked="checked"':'').' >';
                    }elseif($campo[$tipo] == 'enum'){
                        $valor_enum = explode(',',$campo['Length']);
                        /*print "<pre>";
                        print_r($campo);
                        print "</pre>";*/
                        $this->htmlOut02 .= '<div class="divCheckbox">';

                        $boolChecked = false;                       
                        foreach($valor_enum as $key=>$valorE){
                            $valorE = str_replace("'",'',$valorE);
                            $checked = "";

                           if(!empty($valor[$campo[$field]]) && $valor[$campo[$field]]==$valorE){
                                $checked = ' checked="checked"';
                                $boolChecked = true;
                            }elseif($valorE == $campo['Default'] && !$boolChecked){
                                $checked = ' checked="checked"';
                            }
                            $labelField = $valorE;    
                            if($valorE == 'Y'){
                                $labelField = 'Si';
                            }elseif($valorE == 'N'){
                                $labelField = 'No';
                            }
                            
                            if($valorE != 'superadmin'){                            
                                $this->htmlOut02 .= '<input type="radio"  id="'.$campo[$field].'_'.$valorE.'" name="'.$nombreModulo.'_'.$campo[$field].'" class="css-radiobutton"  value="'.trim($valorE).'" '.$checked.' >';
                                $this->htmlOut02 .= '<label class="css-label-radiobutton" for="'.$campo[$field].'_'.$valorE.'"><b>'.$labelField.'</b></label>&nbsp;&nbsp;';
                       
                            }
                        }
                        $this->htmlOut02 .= '</div>';

                   }else{
                   $this->htmlOut02 .= '<input type="text"  id="'.$campo[$field].'" name="'.$nombreModulo.'_'.$campo[$field].'" maxlength="'.$campo['Length'].'" class="'.$this->traerClases($this->rs01).'" size="'.$campo['Length'].'" value="'.trim($valor[$campo[$field]]).'"'.$this->Lectura($campo).' '.$required.' >';
               }
            }
            //$this->htmlOut02 .= $this->requerido($campo);

            $this->htmlOut02 .= '</div>';

            return $this->htmlOut02;
	}

	/**
	 * 
	 * Asigan la class para la validaciones en JavaScript, partiendo del tipo de Dato
	 * @param unknown_type $campo
	 */
	function traerClases($campo) {
		$this->htmlOut03 = 'form-control ';
		if ($_SESSION['config']['bd']['tipo'] == 'mysql'){
				$type = 'Type';
			}elseif($_SESSION['config']['bd']['tipo'] == 'sqlite'){
				$type = 'type';
		}
		switch (strtolower($campo[$type])) {
			case 'integer':
				$this->htmlOut03 .= 'numerico number ';
			break;
			case 'character':
				$this->htmlOut03 .= 'alfanumerico ';
			break;
			case 'date':
				$this->htmlOut03 .= 'fechas date';
			break;
			case 'timestamp without time zone':
				$this->htmlOut03 .= 'fechas ';
			break;
			case 'smallint':
				$this->htmlOut03 .= 'numerico ';
			break;
			case 'int':
				$this->htmlOut03 .= 'numerico number ';
			break;
			case 'decimal':
				$this->htmlOut03 .= 'numerico number ';
			break;
			case 'char':
				$this->htmlOut03 .= 'alfanumerico ';
			break;
			case 'varchar':
				$this->htmlOut03 .= 'alfanumerico ';
			break;
			case 'varbinary':
				$this->htmlOut03 .= 'password ';
			break;
			case 'datetime':
				$this->htmlOut03 .= 'fechas date ';
			break;
			case 'timestamp':
				$this->htmlOut03 .= 'fechas date ';
			break;
			default:
				$this->htmlOut03 .= 'Campo NO DEFINIDO en la Clase';
			break;
		}
		
		return $this->htmlOut03;
	}

	/**
	 * 
	 * Agrega un <span> si el campo no permite NULL
	 * @var $campo
	 */
	function requerido($campo) {
		$this->htmlOut04 = '';
		
		if ((isset($campo['Null']) && $campo['Null'] == 'NO') || (isset($campo['notnull']) && $campo['notnull'] == 99)) {
			$this->htmlOut04 .= '<span class="req">*</span>';
		}
		return $this->htmlOut04;
	}
	
	/**
	 * 
	 * Asigan la class para la validaciones en JavaScript, partiendo del tipo de Dato
	 * @param unknown_type $campo
	 */
	function Lectura($campo) {
		if ($_SESSION['config']['bd']['tipo'] == 'mysql'){
				$type = 'Type';
			}elseif($_SESSION['config']['bd']['tipo'] == 'sqlite'){
				$type = 'type';
		}
		$this->htmlOut06 = '';
		switch (strtolower($campo[$type])) {
			case 'integer':
				$this->htmlOut06 .= ' ';
			break;
			case 'character':
				$this->htmlOut06 .= ' ';
			break;
			case 'date':
				$this->htmlOut06 .= 'readonly="readonly" ';
			break;
			case 'timestamp without time zone':
				$this->htmlOut06 .= 'readonly="readonly" ';
			break;
			case 'smallint':
				$this->htmlOut06 .= ' ';
			break;
			case 'datetime':
				$this->htmlOut06 .= 'readonly="readonly" ';
			break;
			case 'timestamp':
				$this->htmlOut03 .= 'fechas date ';
			break;
			default:
				$this->htmlOut06 .= 'Campo NO DEFINIDO en la Clase';
			break;	
			
		}
		
		return $this->htmlOut06;
	}

	/**
	 * 
	 * Crea un Select cuando el campo esta relacionado
	 * @param unknown_type $campo
	 * @param unknown_type $nombreModulo
	 * @param unknown_type $tabla
	 * @param unknown_type $valor
	 * @param unknown_type $asis
	 * @param unknown_type $eventos
	 * @param unknown_type $requerido
	 */
	function crearSelect($campo,$nombreModulo,$tabla,$valor=0,$asis=false,$eventos="",$requerido=true) {
		$this->htmlOut05 = '';
		if (!empty($campo['Relation'])) {
                        $filterCompany = "";
                        if($_SESSION['session']['usertype'] != 'superadmin'){
                            $filterCompany = " WHERE company_id = {$_SESSION['session']['company_id']}";
                        }
                    
			if($_SESSION['config']['bd']['tipo'] == 'mysql'){
                            $this->query = 'SELECT * FROM ev_'.$campo['Relation'].' '.$filterCompany.' ORDER BY 1'; //print $this->query;
			} 
			//$this->result02 = $_SESSION['cmd']['_query']($this->query);
			$new = new BD();
			$this->result02 = $new->consulta($this->query);
			
			$this->htmlOut05 .= '<select name="'.(!$asis?$nombreModulo.'_'.$campo['Field']:$nombreModulo.'_'.$campo['Field'].']').'" id="'.$nombreModulo.'_'.$campo['Field'].'" class="'.$this->traerClases($campo).($requerido?' required"':'"').(!empty($eventos)?$eventos:"").'>';
			//if ($_SESSION['cmd']['_num_rows']($this->result02) > 0){
			if ($new->num_rows() > 0){
				$this->htmlOut05 .= '<option value=""> :: Seleccionar ::</option>';	
			}else{
				$this->htmlOut05 .= '<option value="">:: No hay Reg :: </option>';
			}
			//while($this->rs02 = $_SESSION['cmd']['_fetch_row']($this->result02))
			while($this->rs02 = $new->fetch_array()){
				//__imp($this->rs02);
				$select = ($this->rs02[$campo['Field']] == $valor[$campo['Field']]) ? " selected='selected'" : "";
				$this->htmlOut05 .= "<option value='".$this->rs02[$campo['Field']]."'$select>".$this->rs02[1]."</option>";
			}
			$this->htmlOut05 .= "</select>";
		}
		//$_SESSION['cmd']['_freeresult']($this->result02);
		return $this->htmlOut05;
		
		
	}

	/**
	 * 
	 * Crear un Select paritiendo de una tabla o una query
	 * @param Array $param Arrglo que contiene datos para la creai�n del HTML
	 * @param Text 		$param[Tabla] 		Nombre de la Tabla
	 * @param Text		$param[Query] 		Sentencia SQL
	 * @param Text 		$param[Name] 		Name a usar en HTML
	 * @param Text 		$param[Field] 		ID a usar en HTML
	 * @param Text 		$param[Evento] 		Eventos a usar en JavaScripts y HTML
	 * @param Text 		$param[Class] 		Class a usar en HTML
	 * @param Integer 	$param[Width] 		Class a usar en HTML
	 * @param Text 		$param[Disabled]	Disabled a usar en HTML
	 * @param Text 		$param[Disabled]	Disabled a usar en HTML
	 * @param Text 		$param[Title]		Title a usar en HTML
	 * @param Boolean 	$param[OpcionNuevo]	Si es verdadero se crear� agregar� un opci�n nueva y INPUT 
	 * @param Boolean 	$param[Link]		Si es llamado de otro lado
	 * @param Text 		$param[Relation]	Nombre de la Tabla cuando se activa el Link
	 * @param Array 	$valor 				Si trae datos de consulta SQL
	 * @return HTML
	 */
	function crearSelect2($param,$valor=array()) {

		$this->htmlOut05 = '';
		if (!empty($param['Tabla'])) {
				
				if (!empty($param['Query'])) {
					$this->query = $param['Query'];
				}else{
					if($_SESSION['config']['bd']['tipo'] == 'mysql'){
						$this->query = 'SELECT * FROM '.$param['Tabla'].' ORDER BY 2';
					}
				}
				//$this->result02 = $_SESSION['cmd']['_query']($this->query);
			$new = new BD();
			$this->result02 = $new->consulta($this->query);
			
			$this->htmlOut05 .= '<select name="'.$param['Name'].'" id="'.$param['Field'].'" '.(!empty($param['Evento'])?$param['Evento']:'').' class="'.$param['Class'].'" style="width:'.$param['width'].';"'.$param['Disabled'].'>' ;
			if ($new->num_rows() > 0){
				$this->htmlOut05 .= '<option value=""> :: Seleccionar ::</option>';	
			}else{
				$this->htmlOut05 .= '<option value="">'.__traducirDisplay('NoHayRegistrosRelacionados').'</option>';
				if ($param['InputAgregar']) {
					$htmlAgregar = '<input type="text" id="Agregar_'.$param['Field'].'" name="Agregar_'.$param['Field'].'">';	
				}			
			}
				while($this->rs02 = $new->fetch_array()){
				if ($param['OpcionNuevo']) {
					$evento = ' onclick="opcionesNuevo('."'#Agregar_".$param['Field']."'".',false);" ';
				}else{
					$evento = '';
				}
				if ($param['Title']) {
					$title =' title="'.trim($this->rs02[($param['TitleNum'])]).'" ';
				}
				$select = ($this->rs02[0] == $valor[$param['Field']]) ? " selected='selected' " : "";
				$this->htmlOut05 .= "<option ".$title."  value='".$this->rs02[0]."'".$select.$evento.">".trim($this->rs02[1])."</option>";
			}
			
			if ($param['OpcionNuevo']) {
				$htmlAgregar = '<input type="text" id="Agregar_'.$param['Field'].'" name="Agregar_'.$param['Field'].'" style="display:none;"'.$param['Evento'].'>';
				$this->htmlOut05 .= '<option value="0" onclick="opcionesNuevo('."'#Agregar_".$param['Field']."'".',true);">'.__traducirDisplay('Nuevo').'</option>';
			}
			$this->htmlOut05 .= "</select>";
		}
		if ($param['Link']) {
			$this->htmlOut05 .= '<div class="demo">';
			$this->htmlOut05 .= '<a href="mod_'.$param['Relation'].'.php?KeepThis=true&TB_iframe=true&height=200&modal=true&width=900" title="Proveedor" class="thickbox"> '.$param['Relation'].'</a>';
			$this->htmlOut05 .= '</div>';
		}
		if ($param['Class']=='required') {
			$this->htmlOut05 .= '<span class="req">*</span>';
		}
		
		//pg_freeresult($this->result02);
		return $this->htmlOut05.$htmlAgregar;
		
	}

	/**
	 * 
	 * Realiza una consulta y la regresa 
	 * @param unknown_type $tabla
	 * @param unknown_type $id
	 * @param unknown_type $pkey
	 * @param unknown_type $query
	 */
	function consultaUnRegistro($tabla,$id,$pkey,$query=''){
		/**
		 * Consulta en la base de datos partiendo de un ID y la Tabla
		 */
		if ($query == ''){
				$this->query = 'SELECT * FROM '.$tabla.' WHERE '.$pkey.'='.$id;
		}else{
			$this->query = $query;
		}
		$this->consulta = null;
		$this->result03 = $this->consulta($this->query);
		return $this->fetch_assoc();
	}

	function campos_ocultos($campo) {
		/*
		 * Funcion para que no parezcan compos en los formularios
		 */
		 if ($_SESSION['config']['bd']['tipo'] == 'mysql'){
			$field = 'Field';
		}elseif($_SESSION['config']['bd']['tipo'] == 'sqlite'){
			$field = 'name';
		}
		global $ocultar;
	    if(in_array($campo[$field],$ocultar) && empty($campo['Relation'])){
	        return true;
	    }
		return false;
	}
	
	/**
	 * 
	 * Crear una tabla de HTML partiend de una consulta SQL
	 * @param Text 		$query					Consulta SQL
	 * @param Text 		$link					El campo principal del primer campo de la Consulta
	 * @param Array 	$ocultar				Campos que no se desea que salgan en la Tabla
	 * @param Text 		$get					Agrega adicionales a <a>
	 * @param Array 	$param					Varias Opciones
	 * @param Boolean 	$param[div] 			Regresa el HTML dentro de un div
	 * @param Text		$param[Title]			Agrega un Titulo a la Tabla
	 * @param Boolean	$param[btnEliminar] 	Agrega un el Boton Eleminar el registros colocar el nombre de la [tabla]
	 * @param Text		$para[tabla]			Nombre de la Tabla Necesaria el Boton de Eliminar
	 * @param Boolean	$param[TotalReg]		Imprime el total de registros en la consulta SQL
	 * @return	Retorna una tabla de una Consulta SQL
	 */
	function listados($query,$link='',$ocultar=array(),$get='',$param=array()) {
		
            if (empty($ocultar)) {
                    global  $ocultar;
                    $ocultar = $ocultar;
            }
            $this->htmlOut01 = '';
            $this->htmlOut01 .='<br/>';
            
            $this->query = "SELECT PK FROM metatabla WHERE lower(Nombre)='".strtolower($param['tabla'])."'";
            $this->consulta($this->query);
            $pk = '';
            if ($this->num_rows() > 0) { 
                $this->rs01 = $this->fetch_row();
                $pk = $this->rs01[0];
                $this->cerrarConsulta();
            }
            
            //print $pk;

            $this->result01 = $this->consulta($query);

            if ($param['div']) {
                    $this->htmlOut01 .= '<div class="tablas">'; 
            }

            if (!empty($param['Title'])) {
                    $this->htmlOut01 .= __messages($param=array('Type'=>'info','Title'=>__traducirDisplay($param['Title'])));
            }

            if ($this->num_rows() > 0) {	
                if (isset($param['TotalReg'])) {
                        $this->htmlOut01 .= __messages($param=array('Type'=>'info','Title'=>__traducirDisplay('TotalRegistros'),'MSG'=>count($this->result01)));
                }
                $this->htmlOut01 .= '<table class="table table-striped" id="'.$param['tabla'].'">';
                $this->htmlOut01 .= '<thead>';

                $this->rs01 = $this->fetch_assoc();
                $keys = array_keys($this->rs01);
                $this->htmlOut01 .= '<tr>';

                for ($i = 0; $i < count($this->rs01); $i++) {
                    if (!in_array($keys[$i], $ocultar)) {
                            $this->htmlOut01 .= '<th>'.__traducirDisplay($keys[$i]).'</th>';
                    }
                }
                if (isset($param['btnEliminar']) || isset($param['btnVer'])) {
                    $this->htmlOut01 .= '<th>'.__traducirDisplay('Acciones').'</th>';
                }
                $this->htmlOut01 .= '</tr>';
                $this->htmlOut01 .= '</thead>';
                $this->htmlOut01 .= '<tbody>';
                $this->result01 = $this->consulta($query);
                $row = 0;
                $rowindex=0;
                while ($this->rs01 = $this->fetch_assoc($this->result01)) {
                    $rowindex++;
                    $class = ($row == 1?'class="par"':'');
                    $keys = array_keys($this->rs01);
                    $value = array_values($this->rs01);

                    /*print "<pre>";
                    print_r($this->rs01);
                    print_r($keys); 
                    print_r($value);
                    print "</pre>";*/

                    $this->htmlOut01 .= '<tr '.$class.' id="row-'.$param['tabla'].'-'.$this->rs01[$pk].'">';
                    for ($i = 0; $i < count($this->rs01); $i++) {
                        if (!in_array($keys[$i], $ocultar)) {
                            if ($i == 0 ) {
                                if (!empty($link)) {
                                        //echo __traducirDisplay($value[$i]);
                                        //echo $pagina = (!empty($get)?$get:'');
                                        //$this->htmlOut01 .= '<td><div class="demo"><a href="#" onclick=cargar_pagina("'.$link.'","'.__traducirDisplay($value[$i]).'","'.(!empty($get)?$get:'').'","'.$param['tabla'].'");>'.__traducirDisplay($value[$i]).'</a></div></td>';
                                        //$this->htmlOut01 .= '<td><div class="demo"><a href="'.$link.__traducirDisplay($value[$i]).(!empty($get)?$get:'').'">'.__traducirDisplay($value[$i]).'</a></div></td>';
                                        $this->htmlOut01 .= '<td align="center"><a class="linkModulo" href="#" onclick=cargar_pagina("'.$link.'",'.__traducirDisplay($value[$i]).',"'.(!empty($get)?$get:'').'","'.$param['tabla'].'");>'.__traducirDisplay($value[$i]).'</a></td>';
                                }else {
                                        $this->htmlOut01 .= '<td>'.__traducirDisplay($value[$i]).'</td>';
                                }

                            }else{
                                    $this->htmlOut01 .= '<td>'.__traducirDisplay($value[$i]).'</td>';
                            }
                        }	
                    }

                    if (isset($param['btnEliminar']) || isset($param['btnVer'])) {
                        $this->htmlOut01.= '<td align="center">';

                        if (isset($param['btnVer'])){
                            $this->htmlOut01.= '<button class="btn btn-primary" type="button" onclick=loadForm("'.$param['tabla'].'",'.$this->rs01[$pk].');><i class="fa fa-edit fa-lg"></i></button>&nbsp;&nbsp;&nbsp;';
                        }
                        if (isset($param['btnEliminar'])) {
                            //$this->htmlOut01 .= '<td align="center"><div class="demo"><input type="button" name="btnborrar" value="Eliminar" onclick="eliminarReg('.$value[0].','."'".$param['tabla']."'".');" /></div>';
                            $this->htmlOut01.= '<button class="btn btn-danger" type="button" onclick="eliminarReg('.$this->rs01[$pk].','."'".$param['tabla']."'".');"><i class="fa fa-trash fa-lg"></i></button>';
                        }

                        $this->htmlOut01.= '</td>';
                    }
                    $this->htmlOut01 .= '</tr>';
                    $row = ($row == 1?0:1);
                }
                $this->htmlOut01 .= '</tbody>';
                $this->htmlOut01 .= '</table>';
            }else{
                    $this->htmlOut01 .='<h4 class="text-center">'.__traducirDisplay('SinRegistros').'</h4>';
            }
            if ($param['div']) {
                    $this->htmlOut01 .= '</div>';
            }
            $this->cerrarConsulta($this->result01);
            return $this->htmlOut01;
	}
	
	/**
	 * 
	 * Crear una Matriz de Radio o Checkbox pariendo de una consulta SQL
	 * @param Array 	$param 
	 * @param Text 		$param['Tabla'] 	Nombre de la Tabla para hacer la Consulta
	 * @param Boolean 	$param['Query'] 	Si trae el SQL completo
	 * @param Integer	$param['Valor'] 	Si trae datos
	 * @param Integer 	$param['SW'] 		Para determinar el Query para la consulta
	 * @param Text 		$param['Type'] 		Determinar si es chekbos o radio
	 * @param Text	 	$param['Fiel'] 		Determinar el nombre el name para
	 * @param Boolean 	$param['Array'] 	Determina si desea crear un Array o no true o false
	 * @param Boolean 	$param['Disable'] 	Determina si activo o inactivo
	 * @param Text	 	$param['Evento'] 	Determina si tiene eventos
	 * @param Text	 	$param['div_id'] 	Determina ID del DIV de indentificacion para js y JQuery
	 * @return HTML
	 */
	function crearSeleciones($param) {
		
		$this->htmlOut05 = '';
		if (!empty($param['Table'])) {			
				if (!empty($param['Query'])) {
					$this->query = $param['Query'];
				}else{
					if ($_SESSION['config']['DataBaseType'] == 'PG') {
						$this->query = 'SELECT * FROM "'.$param['Table'].'" ORDER BY 2';
					}elseif ($_SESSION['config']['DataBaseType'] == 'MySQL'){
						$this->query = 'SELECT * FROM '.$param['Table'].' ORDER BY 2';
					}		
				}
				$this->result02 = $_SESSION['cmd']['_query']($this->query);
			if ($_SESSION['cmd']['_num_rows']($this->result02) <= 0){
				$this->htmlOut05 .=__traducirDisplay('NoHayRegistros');
			}
			$index = 0;
			$codigos = 0;
			$check = '';
			$this->htmlOut05 .= '<div id="'.$param['div_id'].'">';
			while($this->rs02 = $_SESSION['cmd']['_fetch_row']($this->result02)){
				if($this->buscar($this->rs02[0], $param['Valor'],$param['SW'])){
					$check =' checked="checked"';
					$codigos = $codigos + $this->rs02[0];
				}else {
					$check = '';
				}
				if (($index == 1) && (empty($check))) {
				}else{
					//$add_checked = '';
				}			
				$this->htmlOut05 .= '<input type="'.$param['Type'].'" id="'.$param['Field'].'-'.$this->rs02[0].'" value="'.$this->rs02[0].'" name="'.$param['Name'].($param['Array']?'['.$index.']"':'"').(!empty($param['Evento'])?$param['Evento']:'').$check.$add_checked.$param['Disabled'].'>'; 
				$this->htmlOut05 .= '<label for="'.$param['Field'].'-'.$this->rs02[0].'">'.$this->rs02[1].'</label>';
				$index++;
			}
			$this->htmlOut05 .= '<input type="hidden" id="'.$param['Field'].'" value="'.$codigos.'" class="required">';
			$_SESSION['cmd']['_freeresult']($this->result02);
			$this->htmlOut05 .= '</div>';
		}
		
		return $this->htmlOut05;
		
	}
}

$asis = new Asis();