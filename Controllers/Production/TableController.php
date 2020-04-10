<?php

	require_once '../Models/Table.php';

	class TableController
	{
		public function __call($method_name, $arguments)
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