<?php 

	require_once "mainModel.php";

	class citaModelo extends mainModel{

		/* 	Agregar cita a DB
		*	@param: array de datos, desde controlador
		*  	@return: respuesta del servidor, exito/fallido
		*/
		protected static function agregar_cita_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO citas(codCita,codMascota,dniCliente,citafechaEmitida,citaFechaProxima,citaHora,citaMotivo,citaEstado) VALUES(:Codcita,:Codmascota,:Dnicliente,:FechaE,:FechaP,:Hora,:Motivo,:Estado) ");

			$sql->bindParam(":Codcita",$datos['Codcita']);
			$sql->bindParam(":Codmascota",$datos['Codmascota']);
			$sql->bindParam(":Dnicliente",$datos['Dnicliente']);
			$sql->bindParam(":FechaE",$datos['FechaE']);
			$sql->bindParam(":FechaP",$datos['FechaP']);
			$sql->bindParam(":Hora",$datos['Hora']);
			$sql->bindParam(":Motivo",$datos['Motivo']);
			$sql->bindParam(":Estado",$datos['Estado']);
			$sql->execute();

			return $sql;
		} // agregar_cita_modelo

		/* Actualizar campo estado de cita 
		*  @param: $accion:string,accion a realizar, $cod:string, codigo de cita
		*/
		static public function acciones_cita_modelo($accion,$cod){
			if($accion=="Atender"){
				$ca='Procesada';
				$sql=mainModel::conectar()->prepare("UPDATE citas SET citaEstado=:Estado WHERE codCita=:COD");
				$sql->bindParam(":Estado",$ca);
				$sql->bindParam(":COD",$cod);
			}
			$sql->execute();
			return $sql;
		} // fin acciones_cita_modelo

		/* eliminar cita
		* @param: $id: de la cita a eliminar
		*/
		protected static function eliminar_cita_modelo($id){
			$sql=mainModel::conectar()->prepare("DELETE FROM citas WHERE idCita=:ID");

			$sql->bindParam(":ID",$id);
			$sql->execute();

			return $sql;
		} // eliminar_cita_modelo

		/* Buscar datos editar cita
		* @param: tipo: de consulta, $cod: codigo de cita
		*/
		protected static function datos_cita_modelo($tipo,$cod){
			if($tipo=="Unico"){
				$sql=mainModel::conectar()->prepare("SELECT * FROM citas WHERE codCita=:COD");
				$sql->bindParam(":COD",$cod);	
			}elseif($tipo=="Conteo"){
				// TODOS 
				$sql=mainModel::conectar()->prepare("SELECT idCita FROM citas ");
			}
			$sql->execute();
			return $sql;
		}// datos_cliente_modelo

		/* Editar cita
		*	@param: $datos:array de datos
		*/
		protected static function actualizar_cita_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE citas SET codMascota=:Codmascota,dniCliente=:Dnicliente,citaFechaProxima=:FechaP,citaHora=:Hora,citaMotivo=:Motivo,citaEstado=:Estado WHERE codCita=:COD");

			$sql->bindParam(":Codmascota",$datos['Codmascota']);
			$sql->bindParam(":Dnicliente",$datos['Dnicliente']);
			$sql->bindParam(":FechaP",$datos['FechaP']);
			$sql->bindParam(":Hora",$datos['Hora']);
			$sql->bindParam(":Motivo",$datos['Motivo']);
			$sql->bindParam(":Estado",$datos['Estado']);
			$sql->bindParam(":COD",$datos['COD']);
			$sql->execute();

			return $sql;

		}


	}