<?php 


	require_once "mainModel.php";

	/**
	 * 
	 */
	class usuarioModelo extends mainModel{

		/* 	Agregar usuario a DB
		*	@param: array de datos, desde controlador
		*  	@return: respuesta del servidor, exito/fallido
		*/
		protected static function agregar_usuario_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO usuarios(userDni,userNombre,userApellido,userTelefono,userDomicilio,userEmail,userFoto,userUsuario,userClave,userEstado,userPrivilegio) VALUES(:DNI,:Nombre,:Apellido,:Telefono,:Domicilio,:Email,:Foto,:Usuario,:Clave,:Estado,:Privilegio)" );

			  $sql->bindParam(":DNI",$datos['DNI']);
			  $sql->bindParam(":Nombre",$datos['Nombre']);
			  $sql->bindParam(":Apellido",$datos['Apellido']);
			  $sql->bindParam(":Telefono",$datos['Telefono']);
			  $sql->bindParam(":Domicilio",$datos['Domicilio']);
			  $sql->bindParam(":Email",$datos['Email']);
			  $sql->bindParam(":Foto",$datos['Foto']);
			  $sql->bindParam(":Usuario",$datos['Usuario']);
			  $sql->bindParam(":Clave",$datos['Clave']);
			  $sql->bindParam(":Estado",$datos['Estado']);
			  $sql->bindParam(":Privilegio",$datos['Privilegio']);
			  $sql->execute();
			  
			  return $sql;

		}

		/* eliminar usuario
		* @param: id: del usuario a eliminar
		*/
		protected static function eliminar_usuario_modelo($id){
			$sql=mainModel::conectar()->prepare("DELETE FROM usuarios WHERE id=:ID");

			$sql->bindParam(":ID",$id);
			$sql->execute();

			return $sql;
		} // eliminar_usuario_modelo

		/* Buscar datos editar usuario
		* @param: tipo: de consulta cuantos regustros en base datos o seleccionar para mostrar en formuario,
		$id: id del usuario
		*/
		protected static function datos_usuario_modelo($tipo,$id){
			if($tipo=="Unico"){
				$sql=mainModel::conectar()->prepare("SELECT * FROM usuarios WHERE id=:ID");
				$sql->bindParam(":ID",$id);	
			}elseif($tipo=="Conteo"){
				
				$sql=mainModel::conectar()->prepare("SELECT id FROM usuarios ");

			}
			$sql->execute();
			return $sql;
		}// datos_usuario_modelo

		/* Editar usuario
		*	@param: $datos:array de datos
		*/
		protected static function actualizar_usuario_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE usuarios SET userDni=:DNI,userNombre=:Nombre,userApellido=:Apellido,userTelefono=:Telefono,userDomicilio=:Domicilio,userEmail=:Email,userFoto=:Foto,userUsuario=:Usuario,userClave=:Clave,userEstado=:Estado,userPrivilegio=:Privilegio WHERE id=:ID");
			
			$sql->bindParam(":DNI",$datos['DNI']);	
			$sql->bindParam(":Nombre",$datos['Nombre']);	
			$sql->bindParam(":Apellido",$datos['Apellido']);	
			$sql->bindParam(":Telefono",$datos['Telefono']);	
			$sql->bindParam(":Domicilio",$datos['Domicilio']);	
			$sql->bindParam(":Email",$datos['Email']);	
			$sql->bindParam(":Foto",$datos['Foto']);	
			$sql->bindParam(":Usuario",$datos['Usuario']);	
			$sql->bindParam(":Clave",$datos['Clave']);	
			$sql->bindParam(":Estado",$datos['Estado']);	
			$sql->bindParam(":Privilegio",$datos['Privilegio']);
			$sql->bindParam(":ID",$datos['ID']);
			$sql->execute();

			return $sql;

		}

	}