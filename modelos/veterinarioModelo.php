<?php 
 

	require_once "mainModel.php";

	/**
	 * 
	 */
	class veterinarioModelo extends mainModel{

		/* 	Agregar veterinario a DB
		*	@param: array de datos, desde controlador
		*  	@return: respuesta del servidor, exito/fallido
		*/
		protected static function agregar_veterinario_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO veterinario(vetDni,vetNombre,vetApellido,vetGenero,vetTelefono,vetEspecialidad,vetDomicilio,vetFotoUrl) VALUES(:DniCedula,:Nombre,:Apellido,:Genero,:Telefono,:Especialidad,:Domicilio,:FotoUrl) ");

			  $sql->bindParam(":DniCedula",$datos['DniCedula']);
			  $sql->bindParam(":Nombre",$datos['Nombre']);
			  $sql->bindParam(":Apellido",$datos['Apellido']);
			  $sql->bindParam(":Genero",$datos['Genero']);
			  $sql->bindParam(":Telefono",$datos['Telefono']);
			  $sql->bindParam(":Especialidad",$datos['Especialidad']);
			  $sql->bindParam(":Domicilio",$datos['Domicilio']);
			  $sql->bindParam(":FotoUrl",$datos['FotoUrl']);
			  $sql->execute();
			  
			  return $sql;
		}// agregar_veterinario_modelo

		/* eliminar veterinario
		* @param: dni: del veterinario a eliminar
		*/
		protected static function eliminar_veterinario_modelo($dni){
			$sql=mainModel::conectar()->prepare("DELETE FROM veterinario WHERE vetDni=:DNI");

			$sql->bindParam(":DNI",$dni);
			$sql->execute();

			return $sql;
		} // eliminar_cliente_modelo

		/* Buscar datos editar cliente
		* @param: tipo: de consulta cuantos regustros en base datos o seleccionar para mostrar en formuario,
		$dni: cedula del cliente
		*/
		protected static function datos_veterinario_modelo($tipo,$dni){
			if($tipo=="Unico"){
				$sql=mainModel::conectar()->prepare("SELECT * FROM veterinario WHERE vetDni=:DNI");
				$sql->bindParam(":DNI",$dni);	
			}elseif($tipo=="Conteo"){
				// TODOS 
				$sql=mainModel::conectar()->prepare("SELECT idVeterinario FROM veterinario ");
			}
			$sql->execute();
			return $sql;
		}// datos_cliente_modelo
		
		/*  Mostrar mascotas de cliente
		* @param: $tipo: accion a realizar, $dni: cedula dni de cliente
		*/
		/* protected static function datos_perfil_cliente_modelo($tipo,$dni){
			if($tipo=="listaMascota"){
				$sql=mainModel::conectar()->prepare("SELECT * FROM mascota,especie,raza WHERE mascota.idEspecie=especie.idEspecie AND mascota.idRaza=raza.idRaza AND dniDueno=:DNI");
				$sql->bindParam(":DNI",$dni);
			}
			$sql->execute();
			return $sql;
		} // datos_perfil_cliente_modelo
 */
		/* Editar cliente
		*	@param: $datos:array de datos
		*/
		protected static function actualizar_veterinario_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE veterinario SET vetDni=:DniCedula,vetNombre=:Nombre,vetApellido=:Apellido,vetGenero=:Genero,vetTelefono=:Telefono,vetEspecialidad=:Especialidad,vetDomicilio=:Domicilio,vetFotoUrl=:FotoUrl WHERE idVeterinario=:ID");

			$sql->bindParam(":DniCedula",$datos['DniCedula']);
			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":Apellido",$datos['Apellido']);
			$sql->bindParam(":Genero",$datos['Genero']);
			$sql->bindParam(":Telefono",$datos['Telefono']);
			$sql->bindParam(":Especialidad",$datos['Especialidad']);
			$sql->bindParam(":Domicilio",$datos['Domicilio']);
			$sql->bindParam(":FotoUrl",$datos['FotoUrl']);
			$sql->bindParam(":ID",$datos['ID']);
			$sql->execute();

			return $sql;

		}
		
	}