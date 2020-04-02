<?php

	require_once '../Models/Product.php';

	class ProductController
	{
		public function __construct(){

		}

		private function validation($id="", $nombre, $precio, $costo, $iva, $descripcion, $cantidad){
			$errors = [];

			if($id != "" and !preg_match("/^[0-9]{1,10}$/", $id)){
				$errors []= "El id no cumple como numerico";
			}
			if($precio != ""){
				$errors []= "El precio es requerido";
			}else if(!preg_match("/^[0-9]{1,10}.[0-9]{1,2}$/", $precio)){
				$errors []= "El precio es no cumple como numerico";
			}
		}

		public function createProduct($id, $nombre, $precio, $costo, $iva, $descripcion, $cantidad){
			
			$product = new Product();
			
			$result = $product->insert([
				'id' => $id,
				'nombre' => $nombre,
				'precio' => $precio,
				'costo' => $costo,
				'iva' => $iva,
				'descripcion' => $descripcion,
				'cantidad' => $cantidad,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			]);

			if(array_key_exists('success', $result)){
				return [
					'status' => 'success',
					'message' => 'Se creo exitosamente el producto'
				];	
			}else{
				return [
					'status' => 'error',
					'message' => 'Error al crear el producto',
					'error' => $result
				];
			}
		}
	}
	
?>