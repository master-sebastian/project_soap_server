<?php

	require_once 'Model.php';
	
	class Invoice extends Model
	{
		public $table = 'soportes';

		public $fillable = [
			'id',
			'total_a_pagar',
            'fecha_y_hora',
            'usuarios_id',
            'clientes_id'
		];


		public $fillableCast = [
			'id' => "int",
			'total_a_pagar' => "flaot",
            'fecha_y_hora' => "datetime",
			'usuarios_id' => "int",
			'clientes_id' => "int"
		];

	}

?>