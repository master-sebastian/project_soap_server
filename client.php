<?php

	$parametros = [
		'location' => "http://localhost/projects/soap/server.php",
		'uri' => 'urn://localhost/projects/soap/server.php',
		'trace' => 1
	];

	$cliente = new SoapClient(null, $parametros);

	
	$respuesta = $cliente->__soapCall('suma', 
		[
			'a' => 2,
			'b' => -10
		]
	);
	print_r($respuesta);
	print($cliente->__getLastResponse());
	
?>