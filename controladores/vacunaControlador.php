<?php 

 	if ($peticionAjax) {
	  require_once "../modelos/vacunaModelo.php";
	} else {
	  require_once "./modelos/vacunaModelo.php";
	}

	class vacunaControlador extends vacunaModelo{

		/* Agregar vacuna: limpiar entradas, validar, enviar a modelo
		*  @return: json_encode(array): alerta con respuesta de servidor y validaciones
		*/
		public function agregar_vacuna_controlador(){
			$nombre=mainModel::limpiar_cadena($_POST['vacuna_nombre_reg']);
			$especie=mainModel::limpiar_cadena($_POST['vacuna_especie_reg']);

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
					"Texto"=>"Ingresar nombre de vacuna",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,200}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de la vacuna no coincide con el formato solicitado",
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
			$guardar_vacuna=vacunaModelo::agregar_vacuna_modelo($datos);
			
			if($guardar_vacuna->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"vacuna registrada",
					"Texto"=>"Los datos fueron registrados",
					"Tipo"=>"success",
					"User"=>"vacuna",
					"IdEsp"=> $especie
				];
				 
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar la vacuna",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		} // fin  agregar_vacuna_controlador

		/* Listado vacuna segun especie, 
		 * @param: $privilegio: privilegio de usuario logeado
		* @return: html de vacunas por especie, en acordion
		*/
		public function listado_vacuna_controlador($privilegio){
			
			$privilegio=mainModel::limpiar_cadena($privilegio);
			$vacunashtml="";
			$lista_vacuna="";
			// consulta SQL: buscar toda las especies
			$sql = "SELECT * FROM especie";

			$conexion = mainModel::conectar();
			$datos = $conexion->query($sql);
			$datos = $datos->fetchAll();
			// ESPECIE
			foreach($datos as $rows){
				$idesp=$rows['idEspecie'];
				// vacunas segun especie
				$vacunas=mainModel::ejecutar_consulta_simple("SELECT * FROM vacunas WHERE especieId='$idesp'");
				if($vacunas->rowCount()>=1){
					$campos_vacunas=$vacunas->fetchAll();
					foreach ($campos_vacunas as $rowsv){
						$lista_vacuna.='
							<span class="tag d-flex flex-row">
								<m>'.$rowsv['vacunaNombre'].'</m>
								
						';
						if($privilegio<=2){
							$lista_vacuna.='
								<!-- EDITAR -->
								<button class="btn btn-tag-vacuna btn-editar-v" idvacuna="'.$rowsv['idVacuna'].'" value="'.$idesp.'" serverajax="'.SERVERURL.'ajax/vacunaAjax.php">
									<i class="fas fa-pencil-alt fa-sm ml-2"></i>
								</button>
							';	
						}
						
						if($privilegio==1){
							$lista_vacuna.='
		                      	<!-- ELIMINAR -->
		                       <button class="btn btn-tag-vacuna btn-delete-v" value="'.mainModel::encryption($rowsv['idVacuna']).'">
							      <i class="fas fa-trash-alt fa-sm ml-2"></i>
							  </button>
		                    
						';
						}

						$lista_vacuna.='</span>';
					}
				}else{
					$lista_vacuna='<li>ESPECIE NO POSEE VACUNAS REGISTRADAS</li>';
				}
				
				$vacunashtml.='
					<div class="card">
			          <div class="card-header" role="tab" id="heading-'.$rows['idEspecie'].'">
			              <h6 class="mb-0">
			                <a data-toggle="collapse" href="#collapse-'.$rows['idEspecie'].'" aria-expanded="false" aria-controls="collapse-'.$rows['idEspecie'].'" data-abc="true" class="collapsed">

			              <i class="flaticonv-011-syringe"></i>
			                 '.$rows['espNombre'].' </a>
			              </h6>
			          </div>
			          <div id="collapse-'.$rows['idEspecie'].'" class="collapse" role="tabpanel" aria-labelledby="heading-'.$rows['idEspecie'].'" data-parent="#accordion" style="">
			              <div class="card-body">
			              	<div class="form-vacuna-add">
				                  <form class="FormularioAjax d-flex flex-row" action="'.SERVERURL.'ajax/vacunaAjax.php" data-form="save" method="POST">
				                  	<input type="hidden" name="vacuna_especie_reg" value="'.$rows['idEspecie'].'">
				                    <input type="text" class="form-control" placeholder="Nombre de vacuna" name="vacuna_nombre_reg" required="required"/>
				                    <button type="submit" class="btn btn-primary ml-2 btn-circle btn-xs"><i class="fas fa-save"></i></button>
				                  </form>
				               </div>
				               ';
				               if($privilegio<=2){
				               	$vacunashtml.='
				                  <!-- FORM EDITAR FormularioAjax -->
				                  <div class="form-vacuna-edit">
					                  <div class="d-flex flex-row">
					                  	<form class="FormularioAjax d-flex flex-row w-100" action="'.SERVERURL.'ajax/vacunaAjax.php" data-form="update" method="POST">
						                  	
						                  	<input type="hidden" name="vacuna_idvacuna_up" value="">
						                  	
						                  	<input type="hidden" name="vacuna_especie_up" value="">
						                    
						                    <input type="text" class="form-control" name="vacuna_nombre_up" id="vacuna-nombre" required="required" value=""/>
						                    
						                    <button type="submit" class="btn btn-primary ml-2 btn-circle">
						                    	<i class="fas fa-pencil-alt"></i>
						                    </button>
						                    
						                  </form>
						                  <!-- BTN CANCEL EDITAR -->
					                    	<button class="btn-cancel-edit btn btn-secondary ml-2 btn-circle" value="'.$rows['idEspecie'].'"><i class="fas fa-minus"></i></button>
					                  </div>	
				                  </div>';	
				               }
				               

			                  $vacunashtml.='
			                  <div class="tags mt-4 d-flex flex-wrap">
			                    '.$lista_vacuna.'
			                  </div>
			              </div>
			          </div>
			      </div>
				';

				$lista_vacuna="";
			} // especie foreach

			echo $vacunashtml;

		} //fin controlador paginador

		/*  Eliminar vacuna 
		* 	@return: json_encode(array): alerta_simple con respuesta de servidor y validaciones 
		*/
		public function eliminar_vacuna_controlador(){
			$id=mainModel::decryption($_POST['vacuna_id_del']);
			$id=mainModel::limpiar_cadena($id);
		
			// privilegio
			if(session_start(['name'=>'VETP'])){
				if(isset($_SESSION['privilegio_vetp']) == false){
					$alerta_simple=[
						"Alerta"=>"warning",
						"Titulo"=>"Fallo inicio de $_session",
						"Texto"=>"Fallo al iniciar sesion, recarge la pagina"
					];
					echo json_encode($alerta);
					exit();
				}else{
					$privilegio=mainModel::limpiar_cadena($_SESSION['privilegio_vetp']);
				}
			}else{
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Fallo inicio de $_session",
					"Texto"=>"Fallo al iniciar sesion, recarge la pagina"
				];
				echo json_encode($alerta);
				exit();
			}

			// ------- comprobar vacuna en DB ----->
			$check_vacuna = mainModel::ejecutar_consulta_simple("SELECT idVacuna,especieId FROM vacunas WHERE idVacuna='$id'");
			if($check_vacuna->rowCount()<=0){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La vacuna no existe en el sistema"
				];
				echo json_encode($alerta);
				exit();
			
			}else{
				$campos=$check_vacuna->fetch();
				$especie=$campos['especieId'];
			}
			// --- comprobar privilegio ----->
			if($privilegio!=1){
			
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No tienes los permisos necesarios para realizar esta acción"
				];
				echo json_encode($alerta);
				exit();
			}

			// comprobar con clave foranea tabla historial vacunacion de mascota
			$check_historialv = mainModel::ejecutar_consulta_simple("SELECT idVacuna FROM historialvacuna WHERE idVacuna='$id' ");
			if($check_historialv->rowCount()>0){
			
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No podemos eliminar esta vacuna debido a que tiene historial de vacuna asociado"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			
			// instancia a modelo
			$eliminar_vacuna=vacunaModelo::eliminar_vacuna_modelo($id);
			if($eliminar_vacuna->rowCount()==1){
				$alerta_simple=[
					"Alerta"=>"success",
					"Titulo"=>"Datos Eliminados con exito",
					"Texto"=>"vacuna Eliminada",
					"Form"=>"Vacuna",
					"IdEsp"=>$especie
				];
			}else{
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar la vacuna, intente nuevamente"
				];
			}
			echo json_encode($alerta_simple);

		} // fin controlador eliminar VACUNA
		
		
		/*	Buscar datos vacuna edit,
		* 	@param: $tipo: unico o conteo, $id: de vacuna
		*/
		public static function datos_vacuna_controlador($tipo,$id){
			$tipo=mainModel::limpiar_cadena($tipo);
			// $id=mainModel::decryption($id);
			$id=mainModel::limpiar_cadena($id);

			return vacunaModelo::datos_vacuna_modelo($tipo,$id);
		}

		/* Actualizar vacuna
		* 	@return : respuesta de consulta a modelo :sql
		*/
		public function actualizar_vacuna_controlador(){
			$id=mainModel::limpiar_cadena($_POST['vacuna_idvacuna_up']);
			$nombre=mainModel::limpiar_cadena($_POST['vacuna_nombre_up']);
			$especie=mainModel::limpiar_cadena($_POST['vacuna_especie_up']);
			
			/*----- Comprobar vacuna editada en DB  ----- */
			$check_vacuna=mainModel::ejecutar_consulta_simple("SELECT idVacuna FROM vacunas WHERE idVacuna='$id'");

			if($check_vacuna->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La vacuna a editar no se encuentra registrada en el sistema",
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

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,200}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de la vacuna no coincide con el formato solicitado",
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
			if(vacunaModelo::actualizar_vacuna_modelo($datos)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Datos Actualizados",
					"Texto"=>"Los datos han sido actualizados con exito",
					"Tipo"=>"success",
					"User"=>"vacuna",
					"IdEsp"=> $especie
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


		} // fin actualizar_vacuna_controlador
		
		/* Buscar vacunas pertenecientes a una especie
		*	
		*/
		public function lista_vacuna_recargar_controlador(){
			$idespecie=mainModel::limpiar_cadena($_POST['idespecielista']);
			
			$lista_vacuna="";
			// privilegio
			if(session_start(['name'=>'VETP'])){
				if(isset($_SESSION['privilegio_vetp']) == false){
					echo '<div>Fallo al iniciar sesion, recarge pagina</div>';
					exit();
				}else{
					$privilegio=mainModel::limpiar_cadena($_SESSION['privilegio_vetp']);
				}
			}else{
				echo '<div>Fallo al iniciar sesion, recarge la pagina</div>';
				exit();
			}


			$vacunas=mainModel::ejecutar_consulta_simple("SELECT * FROM vacunas WHERE especieId='$idespecie'");
				if($vacunas->rowCount()>=1){
					$campos_vacunas=$vacunas->fetchAll();
					foreach ($campos_vacunas as $rowsv){
						$lista_vacuna.='
							<span class="tag d-flex flex-row">
								<m>'.$rowsv['vacunaNombre'].'</m>';
							if($privilegio<=2){
								$lista_vacuna.='
									<!-- EDITAR -->
									<button class="btn btn-tag-vacuna btn-editar-v" idvacuna="'.$rowsv['idVacuna'].'" value="'.$rowsv['especieId'].'" serverajax="'.SERVERURL.'ajax/vacunaAjax.php">
									<i class="fas fa-pencil-alt fa-sm ml-2"></i>
									</button>';
							}
							if($privilegio==1){
								$lista_vacuna.='
			                      	<!-- ELIMINAR -->
			                       <button class="btn btn-tag-vacuna btn-delete-v" value="'.mainModel::encryption($rowsv['idVacuna']).'">
								      <i class="fas fa-trash-alt fa-sm ml-2"></i>
								  </button>';	
							}

						$lista_vacuna.='</span>';
					}
				}else{
					$lista_vacuna='<li>ESPECIE NO POSEE VACUNAS REGISTRADAS</li>';
				}
				echo $lista_vacuna;
		} // lista_vacuna_recargar_controlador

		
		
	
		public function vacuna_proxima_controlador(){			

			// consulta bd()
			$consulta="SELECT SQL_CALC_FOUND_ROWS hv.*, cl.clienteFotoUrl, m.mascotaNombre, m.mascotaFoto, CONCAT(cl.clienteNombre,' ', clienteApellido) AS clienteNombreCompleto FROM historialvacuna AS hv INNER JOIN 
			mascota as m ON hv.codMascota = m.codMascota INNER JOIN cliente cl ON cl.clienteDniCedula = m.dniDueno WHERE hv.historiavFechaProxVacuna <= DATE_ADD(DATE_ADD(NOW(), INTERVAL 6 HOUR), INTERVAL 2 DAY)
			AND hv.historiavFechaProxVacuna >= DATE_ADD(DATE_ADD(NOW(), INTERVAL 6 HOUR), INTERVAL -1 DAY) ORDER BY hv.historiavFechaProxVacuna;";

			$conexion = mainModel::conectar();
			$datos = $conexion->query($consulta);

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$citas="";
			if($datos->rowCount()>=1){
				$datos = $datos->fetchAll();
				foreach($datos as $rows){ 
				$fecha_formateada = date('d \d\e ', strtotime($rows['historiavFechaProxVacuna'])) . self::mesEnEspanol(date('n', strtotime($rows['historiavFechaProxVacuna']))) . date('\ \d\e\l Y', strtotime($rows['historiavFechaProxVacuna']));
				$citas .= '
				<div class="media">
				<a href="'.SERVERURL.'perfilMascota/'.mainModel::encryption($rows['codMascota']).'/">
				<div class="notificacioness">
				 <div class="col-md-3" style="	background: #6f42c1; margin:10px;">
						<img  src="' . SERVERURL . $rows['clienteFotoUrl'] . '?> " class="thumb-m rounded-circle">
					</div>
					
					<div class="col-md-9" notificaciones>
						<div class="media-body">
						<p class="text-sm-center" style="color:#8887a9;">' . $rows['clienteNombreCompleto'] . '</p>
						<p class="text-sm-center" style="color:#8887a9;"><strong>' . $rows['mascotaNombre'] . '</strong> | ' . $fecha_formateada . '</p>
						</div>
					</div>
				 </div>
				</div>
				</a>
				';
			}

				$citas.='
				<div class="dropdown-divider"></div>
				
				<input type="hidden" id="cantidad_notificaciones_vac" value="' . $total . '">
				
        		<a href="' . SERVERURL . 'listaMascota/" class="dropdown-item dropdown-footer">Vacunas de hoy</a>
				
				';
			}else{
				$citas.='
				<div class="col-md-12">
					<div class="media-body">
					<a class="dropdown-item">No existen citas registradas para hoy</a>
					<p class="text-sm-center">0 citas</p>
					</div>
				</div>
			';
			}
							         
			return $citas;	                
		} //fin controlador vacunas proximas

		public function mesEnEspanol($numero_mes) {
			$meses = array(
				1 => "enero", 
				2 => "febrero", 
				3 => "marzo", 
				4 => "abril", 
				5 => "mayo", 
				6 => "junio", 
				7 => "julio", 
				8 => "agosto", 
				9 => "septiembre", 
				10 => "octubre", 
				11 => "noviembre", 
				12 => "diciembre"
			);
		
			return $meses[$numero_mes];
		}
		
	}