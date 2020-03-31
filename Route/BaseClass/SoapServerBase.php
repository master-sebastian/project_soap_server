<?php

	class SoapServerBase{

		public static function run($service, $parametros){

			$servidor = new SoapServer(null, $parametros);

			$servidor->setClass($service);

			$servidor->handle();
		} 		
	}
?>