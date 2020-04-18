<?php

	class SegurityApp
	{

		public static $clave = "";
		
		private static $method = 'aes-256-cbc';
		
		private static $iv = "";
		

		public static function runStatic(){
			SegurityApp::$clave = Configuration::$keyPublic;
			SegurityApp::$iv = base64_decode(Configuration::$keyPrivate);
		}

		public static function encriptar ($valor)
		{
		    return openssl_encrypt ($valor, SegurityApp::$method, SegurityApp::$clave, false, SegurityApp::$iv);
		}

		public static function desencriptar ($valor)
		{
		    $encrypted_data = base64_decode($valor);
		    return openssl_decrypt($valor, SegurityApp::$method, SegurityApp::$clave, false, SegurityApp::$iv);
		}

		public static function getIV(){
		    return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length(SegurityApp::$method)));
		}

		public static function getToken($id){
			$date = new DateTime();
			$sub = random_int(100000000000000, 999999999999999);
			$user = new User();
			$token = SegurityApp::encriptar(json_encode([
					"id" => $id,
					"sub" => $sub,
					"start_session" => $date->format('Y-m-d H:i:s'),
					"end_session" => $date->modify('+ 1 hour')->format('Y-m-d H:i:s')
			]));
			$result = $user->update([
				'token' => $token,
				'updated_at' => date('Y-m-d H:i:s'),
			], 'id = '.$id);
			if($result){
				return $token;	
			}
			return "";
		}

		public static function closeToken($token){
			
			$tokenCon = json_decode(SegurityApp::desencriptar($token));

			if(property_exists($tokenCon, 'start_session') and property_exists($tokenCon, 'end_session') and property_exists($tokenCon, 'id')){
				$datetime_session = new DateTime();
				$start_session = new DateTime($tokenCon->start_session);
				$end_session = new DateTime($tokenCon->end_session);
				if($start_session <= $datetime_session and $datetime_session <= $end_session){
					$user = new User();
					$result = $user->select(['token'], 'id = '.$tokenCon->id);	
					if(count($result) > 0){
						$user = (object)$result[0];
						if($token == $user->token and $user->token !=""){
							$result = $user->update([
								'token' => "",
								'updated_at' => date('Y-m-d H:i:s'),
							], 'id = '.$id);
							if($result){
								return true;	
							}				
						}
					}
				}
			}
			return false;
		}

		public static function checkAuth($token){
			
			$tokenCon = json_decode(SegurityApp::desencriptar($token));

			if(property_exists($tokenCon, 'start_session') and property_exists($tokenCon, 'end_session') and property_exists($tokenCon, 'id')){
				$datetime_session = new DateTime();
				$start_session = new DateTime($tokenCon->start_session);
				$end_session = new DateTime($tokenCon->end_session);
				if($start_session <= $datetime_session and $datetime_session <= $end_session){
					$user = new User();
					$result = $user->select(['token'], 'id = '.$tokenCon->id);	
					if(count($result) > 0){
						$user = (object)$result[0];
						if($token == $user->token and $user->token !=""){
							return true;
						}
					}
				}
			}
			return false;
		}
	}

	SegurityApp::runStatic();
?>		