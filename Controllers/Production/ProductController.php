<?php

	require_once '../Models/Product.php';

	class ProductController
	{
		public function __call($method_name, $arguments)
	    {
			if($method_name == "getListProduct"){
				return $this->getListProduct($arguments);
			}else if($method_name == "createProduct"){
				return $this->createProduct($arguments);
			}else if($method_name == "getProduct"){
				return $this->getProduct($arguments);
			}else if($method_name == "editProduct"){
				return $this->editProduct($arguments);
			}
			return ['not fount',$method_name, $arguments];
		}

		private function editProduct($arguments){
			$product = new Product();
			$result = $product->update([
				'nombre' => $arguments->nombre,
				'ganancia' => $arguments->ganancia,
				'costo' => $arguments->costo,
				'iva' => $arguments->iva,
				'estado' => $arguments->estado,
				'descripcion' => $arguments->descripcion,
				'url_img' => $arguments->url_img,
			], "id = ".$arguments->id);

			if(array_key_exists('success', $result)){
				return [
					'status' => 'success',
					'message' => 'Se edito exitosamente el producto'
				];	
			}else{
				return [
					'status' => 'error',
					'message' => 'Error al editar el producto',
					'error' => $result
				];
			}
		}
		private function getProduct($arguments){
			$product = new Product();
			return $product->select(["*"],
				"id = ".$arguments->id);
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