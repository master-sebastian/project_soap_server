<?php

	require_once 'Model.php';
	
	class Summary extends Model
	{
		public $table = 'resumen';

		public $fillable = [
			'id',
			'nombre',
			'precio',
			'fecha_y_hora',
			'tipo'
		];


		public $fillableCast = [
			'id' => "int",
			'nombre' => "string",
			'precio' => "float",
			'fecha_y_hora' => "datetime",
			'tipo' => "string", 	
		];

	}

?>