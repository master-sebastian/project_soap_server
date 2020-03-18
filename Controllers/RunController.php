<?php

	require '../Configuration.php';
	require '../Services.php';

	Configuration::runConfiguration();

	$parametros = [
		'uri' => Configuration::$urlBase.$_SERVER['SCRIPT_NAME']
	];

	$servidor = new SoapServer(null, $parametros);

	foreach (Services::$listTest as $service) {
		require Services::$folderTest."/".$service.'.php';
		$servidor->setClass($service);	
	}

	foreach (Services::$listProduction as $service) {
		require Services::$folderProduction."/".$service.'.php';
		$servidor->setClass($service);
	}
	
	$servidor->handle();
?>