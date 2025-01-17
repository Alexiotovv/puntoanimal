<?php 


	require_once "mainModel.php";

	/**
	 * 
	 */
	class especieModelo extends mainModel{

		/* 	Agregar especie a DB
		*	@param: array de datos, desde controlador
		*  	@return: respuesta del servidor, exito/fallido
		*/
		protected static function agregar_especie_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO especie(espNombre) VALUES(:Nombre) ");

			  $sql->bindParam(":Nombre",$datos['Nombre']);
			  $sql->execute();
			  
			  return $sql;

		}// fin agregar_especie_modelo

		/* eliminar especie
		* @param: id: de la especie a eliminar
		*/
		protected static function eliminar_especie_modelo($id){
			$sql=mainModel::conectar()->prepare("DELETE FROM especie WHERE idEspecie=:ID");

			$sql->bindParam(":ID",$id);
			$sql->execute();

			return $sql;
		} // eliminar_especie_modelo

		/*
		*	Buscar datos a editar de especie mostrar en modal, o contar total de registros, mostrar en elemento select
		*   @param: $tipo: unico,conteo, $edit_id de especie a editar
		*	@return: respuesta de consulta sql
		*/
		protected static function buscar_especie_modelo($tipo,$edit_id){
			$data = null;

			if($tipo=="Unico"){
				$sql=mainModel::conectar()->prepare("SELECT * FROM especie WHERE idEspecie=:ID");
				$sql->bindParam(":ID",$edit_id);	
			}elseif($tipo=="Conteo"){
				// TODOS
				$sql=mainModel::conectar()->prepare("SELECT idEspecie FROM especie ");
			}elseif($tipo=="Select"){
 				$sql=mainModel::conectar()->prepare("SELECT * FROM especie ORDER BY espNombre ASC");
 			}
			$sql->execute();
			
			return $sql;

		}

		/* Editar especie
		*	@param: $datos:array de datos
		*/
		protected static function actualizar_especie_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE especie SET espNombre=:Nombre WHERE idEspecie=:ID");

			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":ID",$datos['ID']);
			$sql->execute();

			return $sql;

		}
	}