<?php 


	require_once "mainModel.php";

	/**
	 * 
	 */
	class vacunaModelo extends mainModel{

		/* 	Agregar vacuna a DB
		*	@param: array de datos, desde controlador
		*  	@return: respuesta del servidor, exito/fallido
		*/
		protected static function agregar_vacuna_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO vacunas(vacunaNombre,especieId) VALUES(:Nombre,:Especie) ");

			  $sql->bindParam(":Nombre",$datos['Nombre']);
			  $sql->bindParam(":Especie",$datos['Especie']);
			  $sql->execute();
			  
			  return $sql;

		}// fin agregar_vacuna_modelo

		/* eliminar vacuna
		* @param: id: de la vacuna a eliminar
		*/
		protected static function eliminar_vacuna_modelo($id){
			$sql=mainModel::conectar()->prepare("DELETE FROM vacunas WHERE idVacuna=:ID");

			$sql->bindParam(":ID",$id);
			$sql->execute();

			return $sql;
		} // eliminar_vacuna_modelo

	
		/* Buscar datos vacuna
		*  @param: tipo:String, $id: de vacuna.
		*/
		protected static function datos_vacuna_modelo($tipo,$id){
			if($tipo=="Unico"){
				$sql=mainModel::conectar()->prepare("SELECT * FROM vacunas WHERE idVacuna=:ID");
				$sql->bindParam(":ID",$id);	
			}elseif($tipo=="Conteo"){
				// TODOS 
				$sql=mainModel::conectar()->prepare("SELECT idVacuna FROM vacunas ");
			}elseif ($tipo=="Select") {
				// vacunas segun especie
				$sql=mainModel::conectar()->prepare("SELECT * FROM vacunas WHERE especieId=:IDE");
				$sql->bindParam(":IDE",$id);	
				
			}
			$sql->execute();
			return $sql;
		}  // datos_vacuna_modelo


		/* Editar vacuna
		*	@param: $datos:array de datos
		*/
		protected static function actualizar_vacuna_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE vacunas SET vacunaNombre=:Nombre,especieId=:Especie WHERE idVacuna=:ID");

			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":Especie",$datos['Especie']);
			$sql->bindParam(":ID",$datos['ID']);
			$sql->execute();

			return $sql;

		}
	}