<?php
/*
 * Archivo de configuracion de datos
 */

/*
 * Array que va determinar el idioma para los mostrar los tiulos a los usuarios
 * @var Se necesita tener la variable $_Session[idioma] por defecto es 'espanol'
 */


$datos = array(
	/*Codigo de Errores*/
	'err001'						=>			'Error: 001 NO Se Puede Eliminar el Registro ya que tiene datos relacionados',
	'err002'						=>			'Error: 002 de Base de Datos al intentar eliminar registro, favor notificar a inform&aacute;tica ',
	'err003'						=>			'Error: 003 El Nombre del Usuario Existente',	
		
	/*C�digo de Informaci�n*/
	'inf001'						=>			'Info: 001',

	/*C�digo de Advertencias*/		
	'adv001'						=>			'El registro ha sido eliminado',
	
	'Guardar' => 'Guardar',
	'Regresar' => 'Regresar',
	'fecha_registro' => 'Fecha Registro',
	'ev_user' => 'Usuarios',
	'name' => 'Nombre',
	'lastname' => 'Apellido',
	'usertype' => 'Tipo Usuario',
	'username' => 'Usuario',
	'password' => 'Contrasena',
	'address' => 'Direccion',
	'nit' => 'Nit',
	'phone' => 'Telefono',
	'manager' => 'Encargado',
	'active' => 'Activo',
	'company' => 'Empresa',
	'company_id' => 'Empresa',
	'description' => 'Descripcion',
	'category_id' => 'Categoria',
	'start_date' => 'Fecha Inicio',
	'end_date' => 'Fecha Fin',
        'order' => 'Orden',
);



?>