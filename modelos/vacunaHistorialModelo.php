<?php 


	require_once "mainModel.php";

	/**
	 * 
	 */
	class vacunaHistorialModelo extends mainModel{

		/* 	Agregar historial vacuna a DB
		*	@param: array de datos, desde controlador
		*  	@return: respuesta del servidor, exito/fallido
		*/
		static public function agregar_historia_vacuna_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO historialvacuna(idVacuna,historiavFecha,historiavFechaProxVacuna,historiavProducto,historiavObser,codMascota) VALUES(:idVacuna,:Fecha,:FechaProxVacuna,:Producto,:Obser,:Mascota) ");

			$fecha_prox_vacuna = $datos['FechaProxVacuna']!=""?$datos['FechaProxVacuna']:NULL;
			  $sql->bindParam(":idVacuna",$datos['idVacuna']);
			  $sql->bindParam(":Fecha",$datos['Fecha']);
			  $sql->bindParam(":FechaProxVacuna",$fecha_prox_vacuna);
			  $sql->bindParam(":Producto",$datos['Producto']);
			  $sql->bindParam(":Obser",$datos['Obser']);
			  $sql->bindParam(":Mascota",$datos['codMascota']);
			  $sql->execute();
			  
			  return $sql;

		}// fin agregar_historia_vacuna_modelo

		/* eliminar historia vacuna
		* @param: id: de la vacuna a eliminar
		*/
		protected static function eliminar_historia_vacuna_modelo($id){
			$sql=mainModel::conectar()->prepare("DELETE FROM historialvacuna WHERE idHistoriaVacuna=:ID");

			$sql->bindParam(":ID",$id);
			$sql->execute();

			return $sql;
		} // eliminar_historia_vacuna_modelo


		/* Buscar datos historial de vacuna
		*  @param: tipo:String, $id: de vacuna o cod de mascota
		*/
		protected static function datos_vacuna_historia_modelo($tipo,$id){
			if($tipo=="Perfil"){
				$sql=mainModel::conectar()->prepare("SELECT DATE_FORMAT(t1.historiavFechaProxVacuna,'%d-%m-%Y') AS historiavFechaProxVacuna, t1.idHistoriaVacuna,t1.idVacuna,t1.historiavFecha,t1.historiavProducto,t1.historiavObser,t1.codMascota,t2.vacunaNombre 
					FROM historialvacuna as t1
					INNER JOIN vacunas AS t2
					ON t1.idVacuna=t2.idVacuna
					WHERE t1.codMascota=:COD ORDER BY t1.idHistoriaVacuna DESC");
				$sql->bindParam(":COD",$id);
			}elseif($tipo=="Unico"){
				$sql=mainModel::conectar()->prepare("SELECT DATE_FORMAT(historiavFechaProxVacuna,'%d-%m-%Y') AS historiavFechaProxVacuna, idHistoriaVacuna,idVacuna,historiavFecha,historiavProducto,historiavObser,codMascota FROM historialvacuna WHERE idHistoriaVacuna=:ID");
				$sql->bindParam(":ID",$id);
			}
			$sql->execute();
			return $sql;
		}  // datos_vacuna_historia_modelo

		/* Editar historia vacuna
		*	@param: $datos:array de datos
		*/
		protected static function actualizar_historia_vacuna_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE historialvacuna SET idVacuna=:Vacuna,historiavFechaProxVacuna=:FechaProxVacuna,historiavProducto=:Producto,historiavObser=:Obser WHERE idHistoriaVacuna=:ID");

			$sql->bindParam(":Vacuna",$datos['idVacuna']);
			$sql->bindParam(":FechaProxVacuna",$datos['FechaProxVacuna']);
			$sql->bindParam(":Producto",$datos['Producto']);
			$sql->bindParam(":Obser",$datos['Obser']);
			$sql->bindParam(":ID",$datos['idVacunaH']);
			$sql->execute();

			return $sql;

		} // actualizar_historia_vacuna_modelo
	}