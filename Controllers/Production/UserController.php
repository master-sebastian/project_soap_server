<?php

	class UserController
	{
		public function __call($method_name, $arguments)
	    {
			if($method_name == "createUser"){
				return $this->createUser($arguments->nombre, $arguments->clave);
			}else if($method_name == "loginUser"){
				return $this->loginUser($arguments->nombre, $arguments->clave);
			}else if($method_name == "checkAuth"){
				return $this->checkAuth($arguments->token);
			}else if($method_name == "closeAuth"){
				return $this->closeAuth($arguments->token);
			}
			return ['not fount',$method_name];
		}	

		private function createUser($nombre, $clave){
			
			if(strlen($clave) < 6){
				return [
					'status' => 'error',
					'message' => 'La contraseña debe tener como minimo 6 caracteres',
				];
			}
			
			require_once '../SegurityApp.php';
			require_once '../Models/User.php';
			
            $user = new User();
            $result = $user->select(['*'],'nombre = "'.$nombre.'"');
            if(count($result) == 0){
            	$result = $user->insert([
					'nombre' => $nombre,
	                'clave' => SegurityApp::encriptar(sha1($clave)),
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
				]);

				if(array_key_exists('success', $result)){
					return [
						'status' => 'success',
						'message' => 'Se creo exitosamente el usuario',
						'token' => $this->loginUser($nombre, $clave)['token']
					];	
				}else{
					return [
						'status' => 'error',
						'message' => 'Error al crear el usuario',
						'errors' => $result
					];
				}
            }else{
				return [
					'status' => 'error',
					'message' => 'El usuario ya existe',
				];
			}
		}

		private function loginUser($nombre, $clave){
			require_once '../SegurityApp.php';
			require_once '../Models/User.php';
			
			$user = new User();
			$result = $user->select(['id'],'nombre = "'.$nombre.'"');
			if(count($result) > 0){
				$result = $user->select(['id'],'nombre = "'.$nombre.'" AND clave = "'.SegurityApp::encriptar(sha1($clave)).'"');
				if(count($result) > 0){
					return [
						'token' => SegurityApp::getToken($result[0]['id'])
					];
				}else{
					return [
						'status' => 'error',
						'message' => 'La clave es incorrecta',
					];
				}
			}else{
				return [
					'status' => 'error',
					'message' => 'El usuario no esta registrado en el sistema',
				];
			}
		}

		private function checkAuth($token){
			require_once '../SegurityApp.php';
			require_once '../Models/User.php';

			return [
				'status' => SegurityApp::checkAuth($token)
			];
		}

		private function closeAuth($token){
			require_once '../SegurityApp.php';
			require_once '../Models/User.php';
			
			if($this->checkAuth($token)['status']){
				return [
					'status' => SegurityApp::closeToken($token),
					'message' => 'Se cerro la session'
				];
			}else{
				return [
					'message' => "Autenticate de nuevo"
				];
			}
		}

	}
?>
