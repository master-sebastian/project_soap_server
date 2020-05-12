<?php

	require_once '../Models/Table.php';

	class DashBoardController
	{
		public function __call($method_name, $arguments)
	    {
			require_once '../SegurityApp.php';
			require_once '../Models/User.php';

			if(SegurityApp::checkAuth($arguments->authentication) === true)
			{
				if($method_name == "getListMoneyDate"){
					return $this->getListMoneyDate($arguments);
				}
				return ['not fount',$method_name, $arguments];
			}
			return [
				'status' => 'error-autentication',
				'message' => 'No tiene la autenticacion requerida para acceder a este modulo'
			];
		}


		private function getListMoneyDate($arguments){
			$table = new Table();
			$sql = 'SELECT DATE_FORMAT(fecha_y_hora, "%Y-%m-%d") AS x, SUM(total_a_pagar) AS y FROM soportes WHERE fecha_y_hora BETWEEN \''.$arguments->fecha_ini.' 00:00:00\' AND \''.$arguments->fecha_fin.' 23:59:59\' GROUP BY DATE_FORMAT(fecha_y_hora, "%Y-%m-%d")';
			$fillable = ["x", "y"];
			$fillableCast =[
				"x" => "string", 
				"y" => "float"
			];
			return $table->selectOptimaze($sql,$fillable, $fillableCast);
			
		}
	}
	
?>