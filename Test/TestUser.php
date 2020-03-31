<?php
	require '../Configuration.php';


	if(!Configuration::$debug){
		die();
	}
	
	$uri = 'localhost/projects/soap_project/Route/User.php';

	$parametros = [
		'location' => 'http://'.$uri,
		'uri' => 'urn://'.$uri,
		"trace" => 1,
	];
	
	$cliente = new SoapClient(null, $parametros);
	
	$respuesta = $cliente->__soapCall('createUser', [
			'nombre' => "Carlos main2",
			'clave' => "12345"
		]
	);
	
	echo "<pre>";
	print_r($respuesta);
	echo "</pre>";
	//print($cliente->__getLastResponse());
	
?>