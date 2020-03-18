<?php

	class Servidor
	{
		public function __construct(){

		}

		public function suma($a, $b){
			return [$a + $b,2];
		}
	}

	$parametros = [
		'uri' => "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']
	];

	$servidor = new SoapServer(null, $parametros);
	$servidor->setClass('Servidor');
	$servidor->handle();
?>