<?php

	require 'Model.php';
	
	class Product extends Model
	{
		public $table = 'productos';

		public $fillable = [
			"id",
			"nombre",
			"descripcion",
			"url_img",
			"estado",
			"fecha_y_hora",
			"iva",
			"ganancia",
			"costo"
		];


		public $fillableCast = [
			"id" => "int",
			"nombre" => "string",
			"descripcion" => "string",
			"url_img" => "string",
			"estado" => "int",
			"fecha_y_hora" => "datetime",
			"iva" => "float",
			"estado" => "int",
			"costo" => "float",
			"ganancia" => "float"
		];

	}

?>