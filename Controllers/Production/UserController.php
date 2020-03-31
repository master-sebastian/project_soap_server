<?php

	class UserController
	{
		public function createUser($nombre, $clave){
           	
           	require '../SegurityApp.php';
            
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

		public function loginUser($nombre, $clave){
			require '../SegurityApp.php';
			$user = new User();
			$result = $user->select(['id'],'nombre = "'.$nombre.'"');
			if(count($result) > 0){
				$result = $user->select(['id'],'nombre = "'.$nombre.'" AND clave = "'.SegurityApp::encriptar(sha1($clave)).'"');
				if(count($result) > 0){
					return [
						'token' => SegurityApp::getToken($result[0]['id'])
					];
				else{
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

		public function checkAuth($token){
			require '../SegurityApp.php';
			return [
				'status' => SegurityApp::checkAuth($token)
			];
		}

		public function closeAuth($token){
			require '../SegurityApp.php';

			if($this->checkAuth($token)['status']){
				return [
					'status' => SegurityApp::closeToken($token)
					'message' => 'Se cerro la session'
				];
			}else{
				return [
					'message' => "Autenticate de nuevo"
				]
			}
		}

	}
?>
