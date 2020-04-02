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
			
	    	if(!method_exists($this->class_name, $method_name)){
	            return [
					'status' => 'not fount'
				];
			}

	        if(!$this->authenticated){
	        	return "Not found authenticated";
	        }
	        

	        return call_user_func_array(array($this->class_name, $method_name), $arguments);

	    }

		public function suma($a, $b){
			if(!$this->authenticated){
	        	return "Not found authenticated";
	        }

			return [$a + $b,2];
		}

	}
	
?>