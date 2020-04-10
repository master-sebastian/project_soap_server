<?php

	Configuration::runConfiguration();

	class Model
	{

		public $table = '';

		public $fillable = [];

		public $fillableCast = [];

		public function delete($filter=''){
			$pdo = $this->conection();
			if($filter != ''){
				$filter = "WHERE ".$filter;
			}
			$sql = "DELETE FROM ".$this->table." ".$filter;
			$stmt = $pdo->prepare($sql);
			return $stmt->execute();
		}


		public function update($data, $filter=''){
			$pdo = $this->conection();
			if($filter != ''){
				$filter = "WHERE ".$filter;
			}
			$fillable = [];
			foreach ($data as $key => $value) {
				$fillable []= $key."=".$this->castingDataInv($key, $value);
			}
			$sql = "UPDATE ".$this->table." SET ".join(',',$fillable)." ".$filter;
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $pdo->prepare($sql);
			
			try{
				$pdo->beginTransaction();
				$stmt->execute();
				return [
					'success'=> $pdo->commit()
				];
			}catch(Exception $e){
				$pdo->rollback();
				//throw $e;
				return [ 
					'error' => $e->getMessage()
				];
			}
		}

		public function insert($data){
			$pdo = $this->conection();
			$cat = [];
			$fillable = [];
			$insert = [];
			foreach ($data as $key => $value) {
				$fillable []= $key;
				$cat []= ":".$key;
				$insert[":".$key] = $value;
			}
			
			$sql = "INSERT INTO ".$this->table." (".join(',',$fillable).") VALUES (".join(",", $cat).")";
			
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$stmt= $pdo->prepare($sql);
				
			try{
				$pdo->beginTransaction();
				$stmt->execute($insert);
				return [
					'success'=> $pdo->commit()
				];
			}catch(Exception $e){
				$pdo->rollback();
				//throw $e;
				return [ 
					'error' => $e->getMessage()
				];
			}
			
			
		}


		private function conection(){
			return new PDO(Configuration::$dbDrive.":host=".Configuration::$dbHost.";dbname=".Configuration::$dbName.";port=".Configuration::$dbPort, Configuration::$dbUser, Configuration::$dbPassword);
		}

		public function select($result=['*'], $filter=''){
			if($filter != ''){
				$filter = "WHERE ".$filter;
			}

			$sql = "SELECT ".join(',',$result)." FROM ".$this->table." ".$filter;

			$array = [];
			try {
			    $mbd = $this->conection();
			    foreach($mbd->query($sql) as $fila) {

			    	$filaAux = [];
			    	foreach ($result == ['*']?$this->fillable:$result as $colum) {
			    		$filaAux[$colum] = $this->castingData($colum, $fila[$colum]);
			    	}
			        array_push($array, $filaAux);
			    }
			    $mbd = null;
			} catch (PDOException $e) {
			    print "¡Error!: " . $e->getMessage() . "<br/>";
			    die();
			}

			return $array;
		}

		private function castingData($fillable, $value){
			foreach ($this->fillableCast as $key => $valueKey) {
				
				if($key == $fillable ){
					
					if(is_null($value)){
						return $value;
					}
					if($valueKey == 'string'){
						return $value;
					}else if($valueKey == 'int'){
						return intval($value);
					}else if($valueKey == 'float'){
						return floatval($value);
					}else if($valueKey == 'datetime'){
						return new DateTime($value);
					}else if($valueKey == 'date'){
						return new Date($value);
					}	
				}
			}
			return $value;
		}

		private function castingDataInv($fillable, $value){
			foreach ($this->fillableCast as $key => $valueKey) {
				
				if($key == $fillable ){
					
					if(is_null($value)){
						return "null";
					}
					if($valueKey == 'string'){
						return "'".$value."'";
					}else if($valueKey == 'int'){
						return $value;
					}else if($valueKey == 'float'){
						return $value;
					}else if($valueKey == 'datetime'){
						return "'".$value."'";
					}else if($valueKey == 'date'){
						return "'".$value."'";
					}	
				}
			}
			return $value;
		}
	}

	
?>