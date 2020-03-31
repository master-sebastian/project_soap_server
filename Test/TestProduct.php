<?php
	require '../Configuration.php';

	if(!Configuration::$debug){
		die();
	}
	
	$uri = 'localhost/projects/soap_project/Route/Product.php';

	$parametros = [
		'location' => 'http://'.$uri,
		'uri' => 'urn://'.$uri,
		"trace" => 1,
	];
	
	$cliente = new SoapClient(null, $parametros);
	
	$respuesta = $cliente->__soapCall('createProduct', 
		[
			"id" => 1, 
			"nombre" => "Que mas a todos", 
			"precio" => 2.3, 
			"costo" => 3.23, 
			"iva" => 23.4, 
			"descripcion" => "Que mas a todos", 
			"cantidad" => 234
		]
	);
	
	echo "<pre>";
	print_r($respuesta);
	echo "</pre>";
	//print($cliente->__getLastResponse());
	
?>
