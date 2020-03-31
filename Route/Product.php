<?php

	require_once './BaseClass/SoapServerBase.php';
	require_once '../Configuration.php';
	require_once '../Controllers/Production/ProductController.php';

	$parametros = [
		'uri' => Configuration::$urlBase.$_SERVER['SCRIPT_NAME'],	
	];

	SoapServerBase::run('ProductController',$parametros);
	
?>