<?php

	require_once './BaseClass/SoapServerBase.php';
	require_once '../Configuration.php';
	require_once '../Controllers/Production/ProductController.php';

	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Headers: *");
	header('Access-Control-Allow-Methods: *');
	
	$parametros = [
		'uri' => Configuration::$urlBase.$_SERVER['SCRIPT_NAME'],	
	];

	SoapServerBase::run('ProductController',$parametros);
	
?>