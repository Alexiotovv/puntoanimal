<?php 
 	if ($peticionAjax) {
	  require_once "../modelos/loginModelo.php";
	} else {
	  require_once "./modelos/loginModelo.php";
	}

	/**
	 * hereda loginModelo, y este de mainModel
	 */
	class loginControlador extends loginModelo{

		/* 	Iniciar sesion, comprobar clave y usuario,crear variables de sesion
		*	@return: script js, con respuesta de servidor y validaciones 
		*/
		public function iniciar_sesion_controlador(){
			
			$usuario=mainModel::limpiar_cadena($_POST['usuario_login']);
			$clave=mainModel::limpiar_cadena($_POST['clave_login']);

			/*--- comprobar campos vacios--*/
			if($usuario=="" || $clave==""){
				echo '
				<script>
					Swal.fire({
						title: "Ocurrio un error inesperado" ,
						text: "No has llenado todos los campos",
						type: "error",
						confirmButtonText: "Aceptar"
					});
				</script>
				';
				exit();
			}
		
			/*---encriptar clave---*/
			$clave=mainModel::encryption($clave);

			/*---array---*/
			$datos_login=[
				"Usuario"=>$usuario,
				"Clave"=>$clave
			];
			/*---instanciar--modelo--*/
			$datos_cuenta=loginModelo::iniciar_sesion_modelo($datos_login);

			if($datos_cuenta->rowCount()==1){
				$row=$datos_cuenta->fetch();
				if(session_start(['name'=>'VETP'])){
					/*---variables  de sesion---*/
					$_SESSION['id_vetp']=$row['id'];
					$_SESSION['dni_vetp']=$row['userDni'];
					$_SESSION['nombre_vetp']=$row['userNombre'];
					$_SESSION['apellido_vetp']=$row['userApellido'];
					$_SESSION['foto_vetp']=$row['userFoto'];
					$_SESSION['usuario_vetp']=$row['userUsuario'];
					$_SESSION['privilegio_vetp']=$row['userPrivilegio'];
					// cerrar sesion numero unico userPrivilegio
					$_SESSION['token_vetp']=md5(uniqid(mt_rand(),true));
					return header("Location: ".SERVERURL."home/");
				}else{
					echo '
						<script>
							Swal.fire({
								title: "Ocurrio un error inesperado" ,
								text: "FALLO al iniciar session_start()",
								type: "error",
								confirmButtonText: "Aceptar"
							});
						</script>
						';	
				}
			}else{
				echo '
				<script>
					Swal.fire({
						title: "Ocurrio un error inesperado" ,
						text: "EL USUARIO o CLAVE son incorrectos",
						type: "error",
						confirmButtonText: "Aceptar"
					});
				</script>
				';
			}
		}

		/*---forzar cierre de sesion- -----*/
		/* Forzar cierre de sesion
		*  @return: redirigir a login
		*/
		public function forzar_cierre_sesion_controlador(){
			session_unset();
			session_destroy();
			if(headers_sent()){
				return "<script> window.location.href='".SERVERURL."login/'; </script>";
			}else{
				return header("Location: ".SERVERURL."login/");
			}
		}//controlador cierre sesion forsar

		/*  Cerrar sesion
		*	@return: json_encode(array): alerta con respuesta
		*/
		public function cierre_sesion_controlador(){
			session_start(['name'=>'VETP']);
			$token=mainModel::decryption($_POST['token']);
			$usuario=mainModel::decryption($_POST['usuario']);
			 
			$datos=[
	 			"Usuario"=>$_SESSION['usuario_vetp'],
	 			"Token_S"=>$_SESSION['token_vetp'],
	 			"Token"=>$token
	 		];
	 		$respuesta = loginModelo::cerrar_sesion_modelo($datos);
	 		if($respuesta=="true"){
	 			$alerta=[
					"Alerta"=>"redireccionar",
					"URL"=>SERVERURL."login/"
				];
	 		}else{
	 			$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se pudo cerrar la sesion",
					"Tipo"=>"error"
				];
	 		}
 		 
			echo json_encode($alerta);
			

		}// cierre_sesion_controlador
	}
?>	