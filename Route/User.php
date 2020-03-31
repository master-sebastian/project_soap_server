<?php

	require '../Configuration.php';
	require '../Models/User.php';
	require '../Controllers/Production/UserController.php';
	require 'BaseClass/SoapServerBase.php';
	
	$parametros = [
		'uri' => Configuration::$urlBase.$_SERVER['SCRIPT_NAME'],	
	];

	SoapServerBase::run('UserController',$parametros);
	
?>