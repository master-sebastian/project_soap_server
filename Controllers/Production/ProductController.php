<?php

	require_once '../Models/Product.php';

	class ProductController
	{
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
		public function __call($method_name, $arguments)
	    {
			if($method_name == "getListProduct"){
				return $this->getListProduct($arguments);
			}else if($method_name == "createProduct"){
				return $this->createProduct($arguments);
			}
			return [$method_name, $arguments];
		}

		private function getListProduct($arguments){
			$product = new Product();
			if($arguments->filter == "@"){
				return $product->select();
			}else{
				return $product->select(["*"],
					"descripcion like '%".$arguments->filter."%' OR nombre like '%".$arguments->filter."%'");
			}
		}

		private function createProduct($arguments){
			
			$product = new Product();
			
			$result = $product->insert([
				'nombre' => $arguments->nombre,
				'ganancia' => $arguments->ganancia,
				'costo' => $arguments->costo,
				'iva' => $arguments->iva,
				'estado' => 1,
				'descripcion' => $arguments->descripcion,
				'url_img' => $arguments->url_img,
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