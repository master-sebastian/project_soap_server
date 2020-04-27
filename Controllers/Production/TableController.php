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
				}else if($method_name == "getListProductsCommandAdmin"){
					return $this->getListProductsCommandAdmin($arguments);
				}else if($method_name == "payInvoice"){
					return $this->payInvoice($arguments);
				}else if($method_name == "getListTableSummary"){
					return $this->getListTableSummary($arguments);
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
					}else if($method_name == "getListProductsCommand"){
						return $this->getListProductsCommand($arguments);
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
				'nombre' => $arguments->nombre,
				'url_img' => $arguments->url_img,
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

		private function getListProductsCommandAdmin($arguments){
			require_once '../Models/Command.php';

			$product = new Command();
			return $product->select(["*"], 'id_soporte is null and id_mesa = '.$arguments->idMesa);
			
		}

		private function getListTableSummary($arguments){
			require_once '../Models/Summary.php';
			require_once '../Models/Table.php';
			
			$summary = new Summary();
			$table = new Table();
			$dateStart = date('Y-m-d 00:00:00');
			$dateEnd = date('Y-m-d 23:59:59');
			//"tipo = 'd' or ( tipo='r' and fecha_y_hora between '".$dateStart."' and '".$dateEnd."')"
			$consul = $table->select(["*"]);
			for( $item = 0; $item < count($consul); $item++){
				$consul[$item]['d'] = 0;
				$consul[$item]['r'] = 0;
			}
			foreach($summary->select(["*"]) as $item){
				for( $item1 = 0; $item1 < count($consul); $item1++){
					if($item['id'] == $consul[$item1]['id']){
						$consul[$item1][$item['tipo']] += $item['precio'];
						break;
					}
				}
			}
			return $consul;
		}

		private function payInvoice($arguments){
			require_once '../Models/Invoice.php';
			require_once '../Models/Command.php';
			require_once '../SegurityApp.php';
			require_once '../Models/User.php';

			$tokenCon = json_decode(SegurityApp::desencriptar($arguments->authentication));

			$invoice = new Invoice();
			
			$date = date('Y-m-d H:i:s');
			
			if($arguments->total <= 0){
				return [
					'status' => 'warning',
					'message' => 'La mesa no tiene productos que pagar'
				];	
			}
			
			$result = $invoice->insert([
				'total_a_pagar' => $arguments->total,
				'fecha_y_hora' => $date,
				'usuarios_id' => $tokenCon->id
			]);
			

			if(array_key_exists('success', $result)){

				$invoice = $invoice->select(['*'], "fecha_y_hora like '".$date."' and usuarios_id = ".$tokenCon->id);
				
				$invoice = (object) $invoice[0];
				$command = new Command();

				$result = $command->update([
					'id_soporte' => $invoice->id
				], 'id_mesa = '.$arguments->idMesa.' and id_soporte is null');
				
				if(array_key_exists('success', $result)){
					return [
						'status' => 'success',
						'message' => 'Se pago la cuenta de la mesa'
					];	
				}else{
					return [
						'status' => 'error',
						'message' => 'Error no se al pagar los productos',
						'error' => $result
					];	
				}

			}else{
				return [
					'status' => 'error',
					'message' => 'Error no se pudo pagar la cuenta de la mesa',
					'error' => $result
				];
			}
			
		}

		private function getListProductsCommand($arguments){
			require_once '../Models/Command.php';
			require_once '../SegurityApp.php';
			require_once '../Models/User.php';

			$tokenCon = json_decode(SegurityApp::desencriptar($arguments->authentication));

			$product = new Command();

			return $product->select(["*"], 'id_soporte is null and id_mesa = '.$tokenCon->idMesa);
			
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