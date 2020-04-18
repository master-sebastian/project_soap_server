<?php

	require_once 'Model.php';
	
	class Table extends Model
	{
		public $table = 'mesas';

		public $fillable = [
			"id",
			"nombre",
			"token"
		];

		public $fillableCast = [
			"id" => "int",
			"nombre" => "string",
			"token" => "string",
		];

	}

?>