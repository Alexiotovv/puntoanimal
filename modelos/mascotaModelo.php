<?php   

	require_once "mainModel.php"; 

	class mascotaModelo extends mainModel{

		/* 	Agregar mascota a DB
		*	@param: array de datos, desde controlador
		*  	@return: respuesta del servidor, exito/fallido
		*/
		protected static function agregar_mascota_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO mascota(codMascota,mascotaNombre,mascotaFechaN,mascotaPeso,mascotaColor,idEspecie,idRaza,mascotaFoto,mascotaSexo,castrado,mascotaAdicional,dniDueno) VALUES(:CodM,:Nombre,:FechaN,:Peso,:Color,:Especie,:Raza,:Foto,:Sexo,:Castrado,:Adicional,:Dueno) ");

			  $sql->bindParam(":CodM",$datos['Codmascota']);
			  $sql->bindParam(":Nombre",$datos['Nombre']);
			  $sql->bindParam(":FechaN",$datos['FechaN']);
			  $sql->bindParam(":Peso",$datos['Peso']);
			  $sql->bindParam(":Color",$datos['Color']);
			  $sql->bindParam(":Especie",$datos['Especie']);
			  $sql->bindParam(":Raza",$datos['Raza']);
			  $sql->bindParam(":Foto",$datos['FotoUrl']);
			  $sql->bindParam(":Sexo",$datos['Sexo']);
			  $sql->bindParam(":Castrado",$datos['Castrado']);
			  $sql->bindParam(":Adicional",$datos['Adicional']);
			  $sql->bindParam(":Dueno",$datos['Dueno']);
			  $sql->execute();
			  
			  return $sql;
		}

		/* eliminar mascota
		* @param: dni: del mascota a eliminar
		*/
		protected static function eliminar_mascota_modelo($cod){
			$sql=mainModel::conectar()->prepare("DELETE FROM mascota WHERE codMascota=:COD");

			$sql->bindParam(":COD",$cod);
			$sql->execute();

			return $sql;
		} // eliminar_mascota_modelo

		/* Buscar datos: editar mascota,ver perfil mascota
		* @param: tipo: de consulta cuantos registros en base datos o seleccionar para mostrar en formuario,
		$dni: codigo de la mascota
		*/
		protected static function datos_mascota_modelo($tipo,$cod){
			if($tipo=="Unico"){
				$sql=mainModel::conectar()->prepare("SELECT * FROM mascota WHERE codMascota=:COD");
				$sql->bindParam(":COD",$cod);	
			}elseif($tipo=="Conteo"){
				// TODOS 
				$sql=mainModel::conectar()->prepare("SELECT idmascota FROM mascota ");
			}elseif($tipo=="Perfil"){
				$sql=mainModel::conectar()->prepare("SELECT t1.*,t4.*,espNombre,razaNombre 
					FROM mascota AS t1 
					INNER JOIN especie AS t2
					ON t1.idEspecie=t2.idEspecie
					INNER JOIN raza AS t3
					ON t1.idRaza=t3.idRaza
					INNER JOIN cliente AS t4
					ON t1.dniDueno=t4.clienteDniCedula
					WHERE t1.codMascota=:COD ");
				$sql->bindParam(":COD",$cod);
					
			}elseif($tipo=="ConteoSexo"){
				$sql=mainModel::conectar()->prepare("SELECT mascotaSexo, COUNT(*) FROM mascota GROUP BY mascotaSexo");

			}elseif($tipo=="ConteoCastrado"){
				$sql=mainModel::conectar()->prepare("SELECT castrado, COUNT(*) FROM mascota GROUP BY castrado");
			
			}elseif($tipo=="ConteoRaza"){
				// Estadistica: Total de cada raza 
				$sql=mainModel::conectar()->prepare("SELECT raza.razaNombre, mascota.idRaza, COUNT(*) AS Total FROM mascota,raza WHERE mascota.idRaza=raza.idRaza GROUP BY mascota.idRaza ORDER BY Total DESC ");

			}elseif($tipo=="ConteoEspecie"){
				// Estadistica: Total de cada especie
				$sql=mainModel::conectar()->prepare("SELECT especie.espNombre,mascota.idEspecie, COUNT(*) FROM mascota,especie WHERE mascota.idEspecie = especie.idEspecie GROUP BY mascota.idEspecie");
			}
			$sql->execute();
			return $sql;
		}// datos_mascota_modelo

		/* Editar mascota
		*	@param: $datos:array de datos
		*/
		protected static function actualizar_mascota_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE mascota SET mascotaNombre=:Nombre,mascotaFechaN=:FechaN,mascotaPeso=:Peso,mascotaColor=:Color,idEspecie=:Especie,idRaza=:Raza,mascotaFoto=:Foto,mascotaSexo=:Sexo,castrado=:Castrado,mascotaAdicional=:Adicional,dniDueno=:Dueno WHERE codMascota=:COD");

			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":FechaN",$datos['FechaN']);
			$sql->bindParam(":Peso",$datos['Peso']);
			$sql->bindParam(":Color",$datos['Color']);
			$sql->bindParam(":Especie",$datos['Especie']);
			$sql->bindParam(":Raza",$datos['Raza']);
			$sql->bindParam(":Foto",$datos['FotoUrl']);
			$sql->bindParam(":Sexo",$datos['Sexo']);
			$sql->bindParam(":Castrado",$datos['Castrado']);
			$sql->bindParam(":Adicional",$datos['Adicional']);
			$sql->bindParam(":Dueno",$datos['Dueno']);
			$sql->bindParam(":COD",$datos['Codmascota']);

			$sql->execute();

			return $sql;

		}


	}