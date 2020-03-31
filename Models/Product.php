<?php

	require 'Model.php';
	
	class Product extends Model
	{
		public $table = 'productos';

		public $fillable = [
			"id",
			"nombre",
			"precio",
			"costo",
			"iva",
			"descripcion",
			"cantidad",
			"created_at",
			"updated_at"
		];


		public $fillableCast = [
			"id" => "int",
			"nombre" => "string",
			"precio" => "float",
			"costo" => "float",
			"iva" => "float",
			"descripcion" => "string",
			"cantidad" => "float",
			'created_at' => 'datetime',
			'updated_at' => 'datetime'	
		];

	}

?>