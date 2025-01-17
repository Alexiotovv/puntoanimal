<?php 

 	if ($peticionAjax) {
	  require_once "../modelos/razaModelo.php";
	} else {
	  require_once "./modelos/razaModelo.php";
	}

	class razaControlador extends razaModelo{

		/* Agregar raza: limpiar entradas, validar, enviar a modelo
		*  @return: json_encode(array): alerta con respuesta de servidor y validaciones
		*/
		public function agregar_raza_controlador(){
			$nombre=mainModel::limpiar_cadena($_POST['raza_nombre_reg']);

			if(isset($_POST['raza_especie_reg'])){
				$especie=mainModel::decryption($_POST['raza_especie_reg']);
				$especie=mainModel::limpiar_cadena($especie);
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No selecciono una especie",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/*----- Comprobar Especie seleccionada en DB  ----- */
			$check_esp=mainModel::ejecutar_consulta_simple("SELECT idEspecie FROM especie WHERE idEspecie='$especie'");
			if($check_esp->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La especie seleccionada no se encuentra registrada en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			// ------- campos vacios ----->
			if($nombre==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Ingresar nombre de raza",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de la raza no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*---PREPARAR CARGAR---*/
			$datos = [
				"Nombre" => $nombre,
				"Especie" => $especie
			];

			// instancia a modelo
			$guardar_raza=razaModelo::agregar_raza_modelo($datos);
			
			if($guardar_raza->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Raza registrada",
					"Texto"=>"Los datos fueron registrados",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar la raza",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		} // fin  agregar_raza_controlador

		/* paginador raza
		* @param: $pagina: pagina actual,$registros: registros a mostrar,$privilegio: acultar algunas acciones,$url:la vista para botones $busqueda: lista raza o raza buscar
		*/
		public function paginador_raza_controlador($pagina,$registros,$privilegio,$url,$busqueda){
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
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM raza,especie WHERE (raza.idEspecie=especie.idEspecie) AND (razaNombre  LIKE '%$busqueda%' ) ORDER BY idRaza DESC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM raza,especie WHERE raza.idEspecie=especie.idEspecie  ORDER BY idRaza DESC LIMIT $inicio,$registros";
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
				                      <th>Nombre</th>
				                      <th>Especie</th>';
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
			                      <td>'.$rows['razaNombre'].'</td>
			                      <td>'.$rows['espNombre'].'</td>';
			                      if($privilegio<=2){
				                      $tabla.='
				                      <td class="d-flex flex-row">
				                      	'; 
				                      	// -----EDITAR BOTON -----
				                      	if ($privilegio<=2) {
				                			$tabla.='
						                        <button serverurl="'.SERVERURL.'" id_raza="'.$rows["idRaza"].'" data-toggle="modal" data-target="#exampleModalRazaEditar" class="btnEditarRaza btn btn-success btn-circle btn-sm">
						                          <i class="fas fa-edit fa-sm"></i>
						                        </button>';
			      		
						                      	}
						                if($privilegio==1){      	
				     					// ----- ELIMINAR BOTON ----->
				                        $tabla.=' 
				                        <form class="FormularioAjax ml-2" action="'.SERVERURL.'ajax/razaAjax.php" method="POST" data-form="delete">
			                              	<input type="hidden" name="raza_id_del" value="'.mainModel::encryption($rows['idRaza']).'">
			                              	
			                              	<input type="hidden" name="privilegio_user" value="'.mainModel::encryption($privilegio).'">
			                              	
					                        <button type="submit" class="btn btn-danger btn-circle btn-sm">
					                          <i class="fas fa-trash-alt fa-sm"></i>
					                        </button>
			                              </form>
				                        ';
										}

				                       $tabla.=' 
				                      </td>';
			                  		}
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
						$tabla.='<p class="text-right">Mostrando razas '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
						$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,5);
					}

					return $tabla;	                

		} //fin controlador paginador

		/*  Eliminar raza 
		* 	@return: json_encode(array): alerta con respuesta de servidor y validaciones 
		*/
		public function eliminar_raza_controlador(){
			$id=mainModel::decryption($_POST['raza_id_del']);
			$id=mainModel::limpiar_cadena($id);
			$privilegio=mainModel::decryption($_POST['privilegio_user']);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			
			// ------- comprobar raza en DB ----->
			$check_raza = mainModel::ejecutar_consulta_simple("SELECT idRaza FROM raza WHERE idRaza='$id'");
			if($check_raza->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La raza no existe en el sistema",
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
			// comprobar con clave foranea tabla mascotas
			$check_mascota = mainModel::ejecutar_consulta_simple("SELECT idEspecie FROM mascota WHERE idEspecie='$id' ");
			if($check_mascota->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No podemos eliminar esta raza debido a que tiene Mascota/s asociadas",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			// instancia a modelo
			$eliminar_raza=razaModelo::eliminar_raza_modelo($id);
			if($eliminar_raza->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Raza eliminada",
					"Texto"=>"La raza a sido eliminada del sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar la raza, intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);

		} // fin controlador eliminar
		
		/* Buscar Raza para editar mostrar en modal
		* @param: $tipo: tipo de consulta unico,select, conteo
		* @return : datos sql
		*/
		public function buscar_raza_controlador($tipo){
			if(isset($_POST['id_raza'])){
				$edit_id=mainModel::limpiar_cadena($_POST['id_raza']);
			}else if(isset($_POST['id_especie'])){
				$edit_id=mainModel::decryption($_POST['id_especie']);
				$edit_id=mainModel::limpiar_cadena($edit_id);
			}else{
				$edit_id=0;
			}
			
			$tipo=mainModel::limpiar_cadena($tipo);

			return razaModelo::buscar_raza_modelo($tipo,$edit_id);

		} // fin buscar_raza_controlador

		/* Actualizar raza
		* 	@return : respuesta de consulta a modelo :sql
		*/
		public function actualizar_raza_controlador(){
			$id=mainModel::limpiar_cadena($_POST['raza_id_edit']);
			$nombre=mainModel::limpiar_cadena($_POST['raza_nombre_edit']);
			
			if(isset($_POST['raza_especie_edit'])){
				$especie=mainModel::limpiar_cadena($_POST['raza_especie_edit']);
				
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Debe seleccionar una Especie",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*----- Comprobar Raza seleccionada en DB  ----- */
			$check_raza=mainModel::ejecutar_consulta_simple("SELECT idRaza,idEspecie FROM raza WHERE idRaza='$id'");

			if($check_raza->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La raza seleccionada no se encuentra registrada en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			
			// ------- Campos vacios ---------------->
			if($nombre=="" || $especie==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Completar todos los campos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de la raza no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/*----- Comprobar Especie seleccionada en DB  ----- */
			$check_esp=mainModel::ejecutar_consulta_simple("SELECT idEspecie FROM especie WHERE idEspecie='$especie'");
			if($check_esp->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La especie seleccionada no se encuentra registrada en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*--- PREPARAR CARGAR ---*/
			$datos = [
				"Nombre" => $nombre,
				"Especie" => $especie,
				"ID" => $id
			];

			// instancia A Modelo ->
			if(razaModelo::actualizar_raza_modelo($datos)){
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