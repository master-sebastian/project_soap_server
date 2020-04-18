<?php

	require_once 'Model.php';
	
	class User extends Model
	{
		public $table = 'usuarios';

		public $fillable = [
			'id',
			'nombre',
			'clave',
			'token',
			'created_at',
			'updated_at'
		];


		public $fillableCast = [
			'id' => 'int',
			'nombre' => 'string',
			'clave' => 'string',
			'token' => 'string',
			'created_at' => 'datetime',
			'updated_at' => 'datetime'	
		];

	}

?>