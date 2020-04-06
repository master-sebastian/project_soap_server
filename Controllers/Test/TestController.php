<?php

	class TestController
	{
		
		protected $class_name    = '';
	    
	    protected $authenticated = true;

	    // -----

	    public function __construct($class_name)
	    {
	        $this->class_name = $class_name;

	    }


		public function AuthHeader($Header)
	    {
	        if($Header->username == 'foo' && $Header->password == 'bar')
	            $this->authenticated = true;

	    }

		public function __call($method_name, $arguments)
	    {
			if($arguments->authentication == "15hyhy"){
				if($method_name == "+"){
					return $this->suma($arguments->a, $arguments->b);
				}else if($method_name == "-"){
					return $this->resta($arguments->a, $arguments->b);
				}if($method_name == "*"){
					return $this->multiplicacion($arguments->a , $arguments->b);
				}if($method_name == "/"){
					return $this->division($arguments->a, $arguments->b);
				}else{
					return [
						'status' => "No existe este servicio"
					];
				}
			}
			return [
				'status' => "Sin autorizacion"
			];
	    }

		public function suma($a, $b){
			
			return $a + $b;
		}

		public function resta($a, $b){	
			return $a - $b;
		}

		public function multiplicacion($a, $b){	
			return $a * $b;
		}

		public function division($a, $b){	
			return $a / $b;
		}

	}
	
?>