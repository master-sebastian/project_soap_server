<?php
	class Configuration
	{

		public static $urlBase = "http://localhost";

		public static $debug = true;

		public static $dbDrive = 'mysql';
		public static $dbHost = 'localhost';
		public static $dbPort = '3306';
		public static $dbUser = 'demo';
		public static $dbPassword = 'demo';
		public static $dbName = 'demo';

		public static $keyPublic = 'El curso de la noche de mejora de procesos es el mejor ;)';
		public static $keyPrivate = "C9fBxl1EWtYTL1/M8jfstw==";

		public static $dir = __dir__;
		
		public static $timezone = 'America/Bogota';

		public static function runConfiguration(){
			Configuration::setTimezone();
		}

		public static function setTimezone(){
			date_default_timezone_set(Configuration::$timezone);
		} 
	} 
?>