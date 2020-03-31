<?php

	require_once '../../Configuration.php';
	require_once Configuration::$dir.'/Route/BaseClass/SoapServerBase.php';
	require_once Configuration::$dir.'/Controllers/Test/TestController.php';
	
	$parametros = [
		'uri' => Configuration::$urlBase.$_SERVER['SCRIPT_NAME'],	
	];

	$service = new TestController('TestController');

	$servidor = new SoapServer(null, $parametros);

	$servidor->setObject($service);

	$servidor->handle();

	//SoapServerBase::run('TestController',$parametros);

?>