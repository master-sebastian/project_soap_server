<?php
	require '../Configuration.php';

	if(!Configuration::$debug){
		die();
	}
	
	$uri = 'localhost/projects/soap_project/Route/Test/Test.php';

	$parametros = [
		'location' => 'http://'.$uri,
		'uri' => 'urn://'.$uri,
		"trace" => 1,
	];
	
	$cliente = new SoapClient(null, $parametros);
	
	$respuesta = $cliente->__soapCall('suma', 
		[
			'a' => $_GET['a'],
			'b' => $_GET['b']
		]
	);
	
	echo "<pre>";
	print_r($respuesta);
	echo "</pre>";
	//print($cliente->__getLastResponse());
	
?>