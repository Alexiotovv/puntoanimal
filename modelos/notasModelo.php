<?php 
 

	require_once "mainModel.php";

	/**
	 * 
	 */
	class notasModelo extends mainModel{

		/* 	Agregar nota de mascotas a DB
		*	@param: array de datos, desde controlador
		*  	@return: respuesta del servidor, exito/fallido
		*/
		protected static function agregar_nota_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO notasmascotas(codMascota,notaDescripcion,notaFecha) VALUES(:Cod,:Descripcion,:Fecha)");

			  $sql->bindParam(":Cod",$datos['codMascota']);
			  $sql->bindParam(":Descripcion",$datos['Descripcion']);
			  $sql->bindParam(":Fecha",$datos['Fecha']);
			  $sql->execute();
			  
			  return $sql;
		}// agregar_empresa_modelo

		/* eliminar nota
		* @param: id: nota a eliminar
		*/
		protected static function eliminar_nota_modelo($id){
			$sql=mainModel::conectar()->prepare("DELETE FROM notasmascotas WHERE idNota=:ID");

			$sql->bindParam(":ID",$id);
			$sql->execute();

			return $sql;
		} // eliminar_nota_modelo

		/* Buscar datos notas
		* @param: tipo:, $id: de nota.
		*/
		protected static function datos_nota_modelo($tipo,$id){
			if($tipo=="Unico"){
				$sql=mainModel::conectar()->prepare("SELECT * FROM notasmascotas WHERE idNota=:ID");
				$sql->bindParam(":ID",$id);	
			}elseif($tipo=="Conteo"){
				// TODOS 
				$sql=mainModel::conectar()->prepare("SELECT idNota FROM notasmascotas ");
			}
			$sql->execute();
			return $sql;
		}// datos_nota_modelo

		// 
		/* Editar Nota
		*	@param: $datos:array de datos
		*/
		protected static function actualizar_nota_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE notasmascotas SET codMascota=:COD,notaDescripcion=:Descripcion,notaFecha=:Fecha WHERE idNota=:ID");
			
			$sql->bindParam(":COD",$datos['codMascota']);
			$sql->bindParam(":Descripcion",$datos['Descripcion']);
			$sql->bindParam(":Fecha",$datos['Fecha']);
			$sql->bindParam(":ID",$datos['Id']);
			$sql->execute();

			return $sql;

		}
		

	}