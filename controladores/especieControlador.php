<?php 

 	if ($peticionAjax) {
	  require_once "../modelos/especieModelo.php";
	} else {
	  require_once "./modelos/especieModelo.php";
	}

	class especieControlador extends especieModelo{

		/* Agregar especie: limpiar entradas, validar, enviar a modelo
		*/
		public function agregar_especie_controlador(){
			$nombre=mainModel::limpiar_cadena($_POST['especie_nombre_reg']);

			// ------- Campos vacios ---------------->
			if($nombre==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Ingresar nombre de especie",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de la especie no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*---PREPARAR CARGAR---*/
			$datos = [
				"Nombre" => $nombre
			];

			// instancia
			$guardar_especie=especieModelo::agregar_especie_modelo($datos);
			
			if($guardar_especie->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Especie registrada",
					"Texto"=>"Los datos fueron registrados",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar la especie",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		} // fin  agregar_especie_controlador

		/* paginador especie
		* @param: $pagina: pagina actual,$registros: registros a mostrar,$privilegio: acultar algunas acciones,$url:la vista para botones $busqueda: lista especie o especie buscar
		*/
		public function paginador_especie_controlador($pagina,$registros,$privilegio,$url,$busqueda){
			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			
			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);

			$tabla="";
			// operador ternario, false: llevar a pag 1
			$pagina= (isset($pagina) && $pagina>0) ? (int) $pagina : 1 ;

			$inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0 ;
			// consulta bd
			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM especie WHERE espNombre LIKE '%$busqueda%' ORDER BY espNombre ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM especie ORDER BY idEspecie DESC LIMIT $inicio,$registros";
			}

			$conexion = mainModel::conectar();
			$datos = $conexion->query($consulta);
			$datos = $datos->fetchAll();

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas=ceil($total/$registros);

			$tabla.='<div class="table-responsive">
			          <table class="table table-hover mb-0">
			              <thead>
			                  <tr class="align-self-center">
			                      <th>#</th>
			                      
			                      <th>Nombre</th>';
			                      if($privilegio<=2){
			                    	  	$tabla.='
			                 	    	 <th>Acciones</th>';
			                      }
			                 $tabla.='     
			                  </tr>
			              </thead>
			              <tbody>';

					if($total>=1 && $pagina<=$Npaginas){
						$contador=$inicio+1;
						$reg_inicio=$inicio+1;
						foreach($datos as $rows){
							$tabla.='
								 <tr>
			                      <td>'.$contador.'</td>
			                      <td>'.$rows['espNombre'].'</td>';
			                      if($privilegio<=2){
			                      $tabla.='
			                      <td class="d-flex flex-row">
			                      	';
			                      	// -----EDITAR BOTON -----
			                      	if($privilegio<=2){
			                			$tabla.='
					                        <button serverurl="'.SERVERURL.'" id_especie="'.$rows["idEspecie"].'" data-toggle="modal" data-target="#exampleModalEspecieEditar" class="btnEditarEspecie btn btn-success btn-circle btn-sm">
					                          <i class="fas fa-edit fa-sm"></i>
					                        </button>
					                        ';
		      		
					                      	}
					                if($privilegio==1){
			     					// ----- ELIMINAR BOTON ----->
			                        $tabla.=' 
			                        <form class="FormularioAjax ml-2" action="'.SERVERURL.'ajax/especieAjax.php" method="POST" data-form="delete">
		                              	<input type="hidden" name="especie_id_del" value="'.mainModel::encryption($rows['idEspecie']).'">
		                              	
		                              	<input type="hidden" name="privilegio_user" value="'.mainModel::encryption($privilegio).'">
		                              	
				                        <button type="submit" class="btn btn-danger btn-circle btn-sm">
				                          <i class="fas fa-trash-alt fa-sm"></i>
				                        </button>
		                              </form>
			                        ';
			                        } 

			                       $tabla.=' 
			                      </td>';
								} // fin if privilegio <=2
							$tabla.='
			                  </tr>
			                 ';

							$contador++;
						}
						$reg_final=$contador-1; 
					}else{
						if($total>=1){
							$tabla.='<tr><td colspan="9">
								<a href="'.$url.'" class="btn btn-sm btn-primary btn-raised">
					                    Haga clic aca para recargar el listado
					                </a>
							</td></tr>';
						}else{

						}
						$tabla.='<tr><td colspan="9">No hay registros en el sistema</td></tr>';
					}                
					

					$tabla.='</tbody></table></div>';

					// paginador botones

					if($total>=1 && $pagina<=$Npaginas){
						$tabla.='<p class="text-right">Mostrando especie '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
						$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,5);
					}

					return $tabla;	                

		} //fin controlador paginador

		/* Elimimar usuario
		*
		*/
		public function eliminar_especie_controlador(){
			$id=mainModel::decryption($_POST['especie_id_del']);
			$id=mainModel::limpiar_cadena($id);
			$privilegio=mainModel::decryption($_POST['privilegio_user']);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			

			// ------- comprobar especie en DB ----->
			$check_especie = mainModel::ejecutar_consulta_simple("SELECT idEspecie FROM especie WHERE idEspecie='$id'");
			if($check_especie->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La especie no existe en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			// --- comprobar privilegio ----->
			if($privilegio!=1){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No tienes los permisos necesarios para realizar esta acción",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			// comprobar con clave foranea tabla raza
			$check_raza = mainModel::ejecutar_consulta_simple("SELECT idEspecie FROM raza WHERE idEspecie='$id' ");
			if($check_raza->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No podemos eliminar esta especie debido a que tiene razas/s asociadas",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			// comprobar con clave foranea tabla mascotas
			$check_mascota = mainModel::ejecutar_consulta_simple("SELECT idEspecie FROM mascota WHERE idEspecie='$id' ");
			if($check_mascota->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No podemos eliminar esta especie debido a que tiene Mascota/s asociadas",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			$eliminar_especie=especieModelo::eliminar_especie_modelo($id);
			if($eliminar_especie->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Especie eliminada",
					"Texto"=>"La especie a sido eliminada del sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar la especie, intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);

		} // fin controlador eliminar
		
		/* Buscar Especie para editar mostar en modal
		* 	@return : respuesta de consulta a modelo :sql
		*/
		public function buscar_especie_controlador($tipo){
			
			if(isset($_POST['id_especie'])){
				$edit_id=mainModel::limpiar_cadena($_POST['id_especie']);
			}else{
				$edit_id=0;
			}
			 
			$tipo=mainModel::limpiar_cadena($tipo);

			return especieModelo::buscar_especie_modelo($tipo,$edit_id);

		} // fin buscar_especie_controlador

		/* Actualizar especie
		* 	@return : respuesta de consulta a modelo :sql
		*/
		public function actualizar_especie_controlador(){
			$id=mainModel::limpiar_cadena($_POST['especie_id_edit']);
			
			/*----- Comprobar Especie seleccionada en DB  ----- */
			$check_esp=mainModel::ejecutar_consulta_simple("SELECT idEspecie FROM especie WHERE idEspecie='$id'");
			if($check_esp->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La especie a editar no se encuentra registrada en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$nombre=mainModel::limpiar_cadena($_POST['especie_nombre_edit']);
			// ------- Campos vacios ---------------->
			if($nombre==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Ingresar nombre de especie",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de la especie no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*--- PREPARAR CARGAR ---*/
			$datos = [
				"Nombre" => $nombre,
				"ID" => $id
			];

			// instancia A Modelo ->
			if(especieModelo::actualizar_especie_modelo($datos)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Datos Actualizados",
					"Texto"=>"Los datos han sido actualizados con exito",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido Actualizar los datos, intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);


		} // fin actualizar_especie_controlador		
		
		
	}