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
				if($this->checkAuth($arguments->authentication) === true){
					if($method_name == "getListProduct"){
						return $this->getListProduct($arguments);
					}else if($method_name == "addProduct"){
						return $this->addProduct($arguments);
					}
				}
				return [
					'status' => 'error-autentication',
					'message' => 'No tiene la autenticacion requerida para acceder a este modulo'
				];
			}
		}

		private function addProduct($arguments){
			require_once '../Models/Command.php';
			require_once '../Models/Product.php';
			require_once '../SegurityApp.php';
			require_once '../Models/User.php';
			
			$tokenCon = json_decode(SegurityApp::desencriptar($arguments->authentication));

			$productSolicitado = new  Command();
			
			$result = $productSolicitado->insert([
				'precio' => $arguments->precio,
				'estado' => 1,
				'iva' => $arguments->iva,
				'fecha_y_hora'	=> date('Y-m-d H:i:s'),
				'id_mesa'=> $tokenCon->idMesa,
				'productos_id' => $arguments->idProducto
			]);

			if(array_key_exists('success', $result)){
				return [
					'status' => 'success',
					'message' => 'Se solicito el producto a la mesa'
				];	
			}else{
				return [
					'status' => 'error',
					'message' => 'Error no se pudo solicitar el producto a la mesa',
					'error' => $result
				];
			}
		}


		private function getListProduct($arguments){
			require_once '../Models/Product.php';
			$product = new Product();
			if($arguments->filter == "@"){
				return $product->select();
			}else{
				return $product->select(["*"],
					"descripcion like '%".$arguments->filter."%' OR nombre like '%".$arguments->filter."%'");
			}
		}

		public function checkAuth($token){
			
			require_once '../SegurityApp.php';
			require_once '../Models/User.php';
			$tokenCon = json_decode(SegurityApp::desencriptar($token));
			if(property_exists($tokenCon, 'idMesa') and property_exists($tokenCon, 'token')){
				$table = new Table();
				$result = $table->select(['token'], 'id = '.$tokenCon->idMesa);
				if(count($result) > 0){
					$table = (object)$result[0];
					if($tokenCon->token == sha1($table->token) and sha1($table->token) !=""){
						return true;
					}
				}
			}
			return false;
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
								"idMesa" => $resultadoDeLaConsulta[0]['id'],
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