<?php

	require_once 'Model.php';
	
	class Command extends Model
	{
		public $table = 'comandas';

		public $fillable = [
			'id',
			'precio',
			'estado',
			'iva',
			'fecha_y_hora',
			'id_mesa',
			'id_soparte',
			'productos_id'
		];


		public $fillableCast = [
			'id' => "int",
			'precio' => "float",
			'estado' => "int",
			'iva' => "float",
			'fecha_y_hora' => "datetime",
			'id_mesa' => "int",
			'id_soparte' => "int",
			'productos_id' => "int"	
		];

	}

?>