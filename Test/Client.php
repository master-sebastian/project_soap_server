<?php
	require '../Configuration.php';

	$parametros = [
		'location' => Configuration::$locationTest,
		'uri' => Configuration::$uriTest,
		'trace' => 1
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