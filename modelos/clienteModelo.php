<?php 
 

	require_once "mainModel.php";

	/**
	 * 
	 */
	class clienteModelo extends mainModel{

		/* 	Agregar cliente a DB
		*	@param: array de datos, desde controlador
		*  	@return: respuesta del servidor, exito/fallido
		*/
		protected static function agregar_cliente_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO cliente(clienteDniCedula,clienteNombre,clienteApellido,clienteGenero,clienteTelefono,clienteCorreo,clienteDomicilio,clienteFotoUrl) VALUES(:DniCedula,:Nombre,:Apellido,:Genero,:Telefono,:Correo,:Domicilio,:FotoUrl) ");

			  $sql->bindParam(":DniCedula",$datos['DniCedula']);
			  $sql->bindParam(":Nombre",$datos['Nombre']);
			  $sql->bindParam(":Apellido",$datos['Apellido']);
			  $sql->bindParam(":Genero",$datos['Genero']);
			  $sql->bindParam(":Telefono",$datos['Telefono']);
			  $sql->bindParam(":Correo",$datos['Correo']);
			  $sql->bindParam(":Domicilio",$datos['Domicilio']);
			  $sql->bindParam(":FotoUrl",$datos['FotoUrl']);
			  $sql->execute();
			  
			  return $sql;
		}// agregar_cliente_modelo

		/* eliminar cliente
		* @param: dni: del cliente a eliminar
		*/
		protected static function eliminar_cliente_modelo($dni){
			$sql=mainModel::conectar()->prepare("DELETE FROM cliente WHERE clienteDniCedula=:DNI");

			$sql->bindParam(":DNI",$dni);
			$sql->execute();

			return $sql;
		} // eliminar_cliente_modelo

		/* Buscar datos editar cliente
		* @param: tipo: de consulta cuantos regustros en base datos o seleccionar para mostrar en formuario,
		$dni: cedula del cliente
		*/
		protected static function datos_cliente_modelo($tipo,$dni){
			if($tipo=="Unico"){
				$sql=mainModel::conectar()->prepare("SELECT * FROM cliente WHERE clienteDniCedula=:DNI");
				$sql->bindParam(":DNI",$dni);	
			}elseif($tipo=="Conteo"){
				// TODOS 
				$sql=mainModel::conectar()->prepare("SELECT idCliente FROM cliente ");
			}
			$sql->execute();
			return $sql;
		}// datos_cliente_modelo
		
		/*  Mostrar mascotas de cliente
		* @param: $tipo: accion a realizar, $dni: cedula dni de cliente
		*/
		protected static function datos_perfil_cliente_modelo($tipo,$dni){
			if($tipo=="listaMascota"){
				$sql=mainModel::conectar()->prepare("SELECT * FROM mascota,especie,raza WHERE mascota.idEspecie=especie.idEspecie AND mascota.idRaza=raza.idRaza AND dniDueno=:DNI");
				$sql->bindParam(":DNI",$dni);
			}
			$sql->execute();
			return $sql;
		} // datos_perfil_cliente_modelo

		/* Editar cliente
		*	@param: $datos:array de datos
		*/
		protected static function actualizar_cliente_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE cliente SET clienteDniCedula=:DniCedula,clienteNombre=:Nombre,clienteApellido=:Apellido,clienteGenero=:Genero,clienteTelefono=:Telefono,clienteCorreo=:Correo,clienteDomicilio=:Domicilio,clienteFotoUrl=:FotoUrl WHERE idCliente=:ID");

			$sql->bindParam(":DniCedula",$datos['DniCedula']);
			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":Apellido",$datos['Apellido']);
			$sql->bindParam(":Genero",$datos['Genero']);
			$sql->bindParam(":Telefono",$datos['Telefono']);
			$sql->bindParam(":Correo",$datos['Correo']);
			$sql->bindParam(":Domicilio",$datos['Domicilio']);
			$sql->bindParam(":FotoUrl",$datos['FotoUrl']);
			$sql->bindParam(":ID",$datos['ID']);
			$sql->execute();

			return $sql;

		}
		
	}