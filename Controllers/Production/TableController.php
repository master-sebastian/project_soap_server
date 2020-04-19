<?php

	require_once '../Models/Table.php';

	class TableController
	{
		public function __call($method_name, $arguments)
	    {
			require_once '../SegurityApp.php';
			require_once '../Models/User.php';

			if(SegurityApp::checkAuth($arguments->authentication) === true)
			{
				if($method_name == "getListTable"){
					return $this->getListTable($arguments);
				}else if($method_name == "createTable"){
					return $this->createTable($arguments);
				}else if($method_name == "getTable"){
					return $this->getTable($arguments);
				}else if($method_name == "editTable"){
					return $this->editTable($arguments);
				}
				return ['not fount',$method_name, $arguments];
			}else{
				
				if($method_name  == "loginTable"){
					return $this->loginTable($arguments);
				}
				return [
					'status' => 'error-autentication',
					'message' => 'No tiene la autenticacion requerida para acceder a este modulo'
				];
			}
		}

		private function loginTable($arguments){
			require_once '../SegurityApp.php';
			require_once '../Models/User.php';

			$nombre = $arguments->nombre;
			$clave = $arguments->clave;
			$existeMesa = new Table();

			$resultadoDeLaConsulta = $existeMesa->select(["*"], "nombre = '".$nombre."'");
			if(count($resultadoDeLaConsulta) > 0){
				$resultadoDeLaConsulta = $existeMesa->select(["*"], "nombre = '".$nombre."' AND token ='".$clave."'");
				if(count($resultadoDeLaConsulta) > 0){
					return [
						'status'=> 'success',
						'token' => SegurityApp::encriptar(json_encode(
							array(
								"idMesa" => $resultadoDeLaConsulta,
								"token"=> sha1($clave)
							)
						))
					];;
				}else{
					return [
						'status' => 'error',
						'message' => 'La clave es incorrecta',
					];
				}
			}else{
				return [
					'status' => 'error',
					'message' => 'La mesa no esta registrado en el sistema',
				];;
			}
			
		}

		private function editTable($arguments){
			$product = new Table();
			$result = $product->update([
				'nombre' => $arguments->nombre,
				'token' => $arguments->token,
			], "id = ".$arguments->id);

			if(array_key_exists('success', $result)){
				return [
					'status' => 'success',
					'message' => 'Se edito exitosamente la mesa'
				];	
			}else{
				return [
					'status' => 'error',
					'message' => 'Error al editar la mesa',
					'error' => $result
				];
			}
		}
		private function getTable($arguments){
			$table = new Table();
			return $table->select(["*"],
				"id = ".$arguments->id);
		}

		private function getListTable($arguments){
			$table = new Table();
			if($arguments->filter == "@"){
				return $table->select();
			}else{
				return $table->select(["*"],
					"nombre like '%".$arguments->filter."%'");
			}
		}

		private function createTable($arguments){
			
			$table = new Table();
			
			$result = $table->insert([
				'nombre' => $arguments->nombre,
				'token' => $arguments->token,
			]);

			if(array_key_exists('success', $result)){
				return [
					'status' => 'success',
					'message' => 'Se creo exitosamente la mesa'
				];	
			}else{
				return [
					'status' => 'error',
					'message' => 'Error al crear la mesa',
					'error' => $result
				];
			}
		}
	}
	
?>