<?php

	require 'Model.php';
	
	class Usuario extends Model
	{
		public $table = 'usuarios';

		public $fillable = [
			'id',
			'nombre',
			'clave',
			'created_at',
			'updated_at'
		];


		public $fillableCast = [
			'id' => 'int',
			'nombre' => 'string',
			'clave' => 'string',
			'created_at' => 'datetime',
			'updated_at' => 'datetime'	
		];

	}

?>