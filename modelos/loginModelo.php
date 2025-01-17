<?php 

	require_once "mainModel.php";

	class loginModelo extends mainModel{

		/* 	Iniciar sesion
		*	@param: array de datos 
		*  	@return: 
		*/
		protected static function iniciar_sesion_modelo($datos){
			$sql=mainModel::conectar()->prepare("SELECT * FROM usuarios WHERE userUsuario=:Usuario AND userClave=:Clave AND userEstado='Activa' ");

			$sql->bindParam(":Usuario",$datos['Usuario']);
			$sql->bindParam(":Clave",$datos['Clave']);
			$sql->execute();
			  
			return $sql;
		}
		/* Cerrar sesion, @param: array de datos de sesion
	 	 * @return: String true,false
	 	 */
	 	protected function cerrar_sesion_modelo($datos){
	 		// si el suario viene definido, y  token de sesion es igual al token de boton
	 		if($datos['Usuario']!="" && $datos['Token_S']==$datos['Token']){
	 			
				session_unset(); // vaciar sesion
				session_destroy();
				$respuesta="true";	
			
	 		}else{
	 			$respuesta="false";
	 		}
	 		return $respuesta;
	 	}
	}

