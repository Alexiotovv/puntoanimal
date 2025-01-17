<?php 


	require_once "mainModel.php";

	/**
	 * 
	 */
	class razaModelo extends mainModel{

		/* 	Agregar raza a DB
		*	@param: array de datos, desde controlador
		*  	@return: respuesta del servidor, exito/fallido
		*/
		protected static function agregar_raza_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO raza(razaNombre,idEspecie) VALUES(:Nombre,:Especie) ");

			  $sql->bindParam(":Nombre",$datos['Nombre']);
			  $sql->bindParam(":Especie",$datos['Especie']);
			  $sql->execute();
			  
			  return $sql;

		}// fin agregar_raza_modelo

		/* eliminar raza
		* @param: id: de la raza a eliminar
		*/
		protected static function eliminar_raza_modelo($id){
			$sql=mainModel::conectar()->prepare("DELETE FROM raza WHERE idRaza=:ID");

			$sql->bindParam(":ID",$id);
			$sql->execute();

			return $sql;
		} // eliminar_raza_modelo

		/*
		*	Buscar datos a editar de raza mostrar en modal, o contar total de registros, mostrar en elemento select
		*   @param: $tipo: unico,conteo, $edit_id de raza a editar
		*	@return: data en un array fetch
		*/
		protected static function buscar_raza_modelo($tipo,$edit_id){
			$data = null;

			if($tipo=="Unico"){
				$sql=mainModel::conectar()->prepare("SELECT * FROM raza WHERE idRaza=:ID");
				$sql->bindParam(":ID",$edit_id);	
			}elseif($tipo=="Conteo"){
				// TODOS MENOS
				$sql=mainModel::conectar()->prepare("SELECT idRaza FROM raza");
			}elseif($tipo=="Select"){
 				$sql=mainModel::conectar()->prepare("SELECT * FROM raza WHERE idEspecie=:Especie ORDER BY razaNombre ASC");
				$sql->bindParam(":Especie",$edit_id);	
 			}
			$sql->execute();
			
			return $sql;

		}

		/* Editar raza
		*	@param: $datos:array de datos
		*/
		protected static function actualizar_raza_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE raza SET razaNombre=:Nombre,idEspecie=:Especie WHERE idRaza=:ID");

			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":Especie",$datos['Especie']);
			$sql->bindParam(":ID",$datos['ID']);
			$sql->execute();

			return $sql;

		}
	}