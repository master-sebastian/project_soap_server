<?php
	class Configuration
	{

		public static $urlBase = "http://localhost";

		public static $dbDrive = 'mysql';
		public static $dbPort = '3306';
		public static $dbUser = 'demo';
		public static $dbPassword = 'demo';
		public static $dbName = 'demo';

		public static $locationTest = 'http://localhost/soap_project/Controllers/RunController.php';
		public static $uriTest = 'urn://localhost/soap_project/Controllers/RunController.php';

		public static $timezone = 'America/Bogota';

		public static function runConfiguration(){
			Configuration::setTimezone();
		}

		public static function setTimezone(){
			date_default_timezone_set(Configuration::$timezone);
		} 
	} 
?>