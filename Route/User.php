<?php

	require '../Configuration.php';
	require '../Models/User.php';
	require '../Controllers/Production/UserController.php';
	require 'BaseClass/SoapServerBase.php';
	
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Headers: *");
	header('Access-Control-Allow-Methods: *');
	
	$parametros = [
		'uri' => Configuration::$urlBase.$_SERVER['SCRIPT_NAME'],	
	];

	SoapServerBase::run('UserController',$parametros);
	
?>