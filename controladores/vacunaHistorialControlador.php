<?php 

 	if ($peticionAjax) {
	  require_once "../modelos/vacunaHistorialModelo.php";
	} else {
	  require_once "./modelos/vacunaHistorialModelo.php";
	}

	class vacunaHistorialControlador extends vacunaHistorialModelo{

		/* Agregar historial de vacuna: limpiar entradas, validar, enviar a modelo
		*  @return: json_encode(array): alerta con respuesta de servidor y validaciones
		*/
		public function agregar_vacuna_historia_controlador(){
			$codmascota=mainModel::limpiar_cadena($_POST['historia_vacuna_codmascota_reg']);

			$idvacuna=mainModel::limpiar_cadena($_POST['historia_vacuna_idvacuna_reg']);
			$proxFechaVacuna=mainModel::limpiar_cadena($_POST['historia_prox_fecha_vacuna_reg']);
			$producto=mainModel::limpiar_cadena($_POST['historia_vacuna_producto_reg']);
			$observa=mainModel::limpiar_cadena($_POST['historia_vacuna_observacion_reg']);
			
			$fecha = date('Y-m-d');
			
			/*----Campos vacios---------*/
			if($codmascota=="" || $idvacuna=="" || $producto=="" || $fecha==""){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Completar campos vacios",
					"Texto"=>"Vacuna"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			
			/*--X--Campos vacios----X-----*/

			/*------ validar entrada de datos ---------*/
			if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\-: ]{1,100}",$producto)){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Campo no coincide con el formato solicitado",
					"Texto"=>"Vacuna"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			
			/*----- Comprobar codmascota si existe en DB  ----- */
			$check_cod=mainModel::ejecutar_consulta_simple("SELECT codMascota,idEspecie FROM mascota WHERE codMascota='$codmascota'");
			if($check_cod->rowCount()<=0){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"El codigo de mascota no se encuenta registrado",
					"Texto"=>"Codigo Mascota"
				];
				echo json_encode($alerta_simple);
				exit();
			}else{
				$campos=$check_cod->fetch();
				$idespeciem=$campos['idEspecie'];
			}
			$check_vacuna=mainModel::ejecutar_consulta_simple("SELECT idVacuna,especieId FROM vacunas WHERE idVacuna='$idvacuna'");
			if($check_vacuna->rowCount()<=0){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"La vacuna seleccionada no se encuenta registrada",
					"Texto"=>"vacuna"
				];
				echo json_encode($alerta_simple);
				exit();
			}else{
				$campos=$check_vacuna->fetch();
				$idespeciev=$campos['especieId'];
			}
			if($idespeciev != $idespeciem){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"La vacuna seleccionada no coincide con la especie de la mascota",
					"Texto"=>"Especie de la mascota"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			
			/*---X--- validar entrada de datos ----X-----*/

			$proxFechaVacuna = date("Y-m-d", strtotime($proxFechaVacuna)); 

			/*if($proxFechaVacuna > $fecha){ 
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"La Fecha debe ser mayor",
					"Texto"=>"Vacuna"
				];
				echo json_encode($alerta_simple);
				exit();
			}*/

			/*---PREPARAR CARGAR---*/
			$datos = [
				"idVacuna" => $idvacuna,
				"Fecha" => $fecha,
				"FechaProxVacuna" => $proxFechaVacuna,
				"Producto" => $producto,
				"Obser" => $observa,
				"codMascota" => $codmascota
			];

			// instancia a modelo
			$guardar_vacuna_historial=vacunaHistorialModelo::agregar_historia_vacuna_modelo($datos);
			
			if($guardar_vacuna_historial->rowCount()==1){
				
				$alerta_simple=[
					"Alerta"=>"success",
					"Titulo"=>"Datos guardados con exito",
					"Texto"=>"Vacuna Guardada",
					"Form"=>"VacunaH"
				];
				
			}else{
				$alerta_simple=[
					"Alerta"=>"error",
					"Titulo"=>"Fallo al guardar los datos",
					"Texto"=>"Historial Vacunación"
				];
				
			}
			echo json_encode($alerta_simple);
		

		} // fin  agregar_vacuna_controlador

		/*  Eliminar vacuna 
		* 	@return: json_encode(array): alerta_simple con respuesta de servidor y validaciones 
		*/
		public function eliminar_vacuna_historia_controlador(){
			$id=mainModel::decryption($_POST['historiav_id_dele']);
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
			$check_vacunah = mainModel::ejecutar_consulta_simple("SELECT idHistoriaVacuna FROM historialvacuna WHERE idHistoriaVacuna='$id'");
			if($check_vacunah->rowCount()<=0){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El historial de vacuna no existe en el sistema"
				];
				echo json_encode($alerta);
				exit();
			
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
			
			// instancia a modelo
			$eliminar_vacunah=vacunaHistorialModelo::eliminar_historia_vacuna_modelo($id);
			if($eliminar_vacunah->rowCount()==1){
				$alerta_simple=[
					"Alerta"=>"success",
					"Titulo"=>"Datos Eliminados con exito",
					"Texto"=>"Historial de vacuna Eliminado",
					"Form"=>"VacunaH"
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
		
		
		/*	Buscar datos historial vacuna
		* 	@param: $tipo: unico o conteo, $id: de vacuna
		*/
		public static function datos_vacuna_historia_controlador($tipo,$id){
			$id=mainModel::decryption($id);
			$id=mainModel::limpiar_cadena($id);
			$tipo=mainModel::limpiar_cadena($tipo);

			return vacunaHistorialModelo::datos_vacuna_historia_modelo($tipo,$id);
		}
		/* 	Datos historial de vacunas de mascota,
		*	@return: json_encode(array) array: html de lista y total de historia
		*/
		public function datos_perfil_vacuna_historia_controlador(){
			$codmascota=mainModel::limpiar_cadena($_POST['codmascota']);
			$historial_lista_vacuna="";
			if(session_start(['name'=>'VETP'])){
				if(isset($_SESSION['privilegio_vetp']) == false){
					echo $historial_lista_vacuna.='<div>Fallo al iniciar sesion</div>';
					exit();
				}else{
					$privilegio=mainModel::limpiar_cadena($_SESSION['privilegio_vetp']);
				}
			}else{
				echo $historial_lista_vacuna.='<div>Fallo al iniciar sesion</div>';
				exit();
			}

			$lista_vacunas=vacunaHistorialModelo::datos_vacuna_historia_modelo("Perfil",$codmascota);
			$total=$lista_vacunas->rowCount();

			if($lista_vacunas->rowCount()>=1){
				$campos_vacuna=$lista_vacunas->fetchAll();
				foreach ($campos_vacuna as $rowsv){
					$historial_lista_vacuna.='
					<li class="timeline-inverted timeline-item">
                            <div class="timeline-badge">
                            	<i class="flaticonv-011-syringe"></i>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading d-flex justify-content-between">
                                	<div>
                                		<h4 class="timeline-title">'.$rowsv['vacunaNombre'].'
	                                	</h4>
	                                	<span>
	                                		<i class="fa fa-calendar-day"></i>
	                                	'.date("d-m-Y", strtotime($rowsv['historiavFecha'])).'</span>
                                	</div>
                                	
                                	<div>';
                                	if($privilegio<=2){
                                		$historial_lista_vacuna.='
	                                		<!-- ---BTN EDIDAR --- -->
				                           	<button data-toggle="modal" id="btn_edit_historia_vacuna" value="'.mainModel::encryption($rowsv['idHistoriaVacuna']).'" data-target="#modal-edit-historia-vacuna" data-toggle="tooltip" title="Editar" class="btn icon-action btn-circle"><i class="fas fa-pencil-alt"></i>
						                	</button>';
                                	}
                    				if($privilegio==1){
                    					$historial_lista_vacuna.='
	                                		<!-- ---BTN ELIMINAR --- -->
	                                		<button id="btn_del_historia_vacuna" value="'.mainModel::encryption($rowsv['idHistoriaVacuna']).'" data-toggle="tooltip" title="Eliminar" class="btn icon-action btn-circle"><i class="fas fa-trash-alt"></i>
	                                		</button>';
                    				}

                               $historial_lista_vacuna.='
                                	</div>
                                </div>
                                <div class="timeline-body">
                                    <p><span class="flaticonv-004-medicine"></span>
                                        '.$rowsv['historiavProducto'].'
                                    </p>
                                    <p>'.$rowsv['historiavObser'].'</p>
                                </div>
                            </div>
                        </li>';
				}
			}else{
				$historial_lista_vacuna='<li class="timeline-inverted timeline-item">
                            <div class="timeline-badge">
                            	<i class="flaticonv-011-syringe"></i>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
	                        		<h4 class="timeline-title">NO TIENE VACUNAS REGISTRADAS
	                            	</h4>
                                </div>
                                <div class="timeline-body">
                                    <p></p>
                                </div>
                            </div>
                        </li>';
			}

			$datos_his=[
				"ListaV"=>$historial_lista_vacuna,
				"TotalV"=>$total
			];
			
			echo json_encode($datos_his);
		}

		/* Actualizar vacuna
		* 	@return : respuesta de consulta a modelo
		*/
		public function actualizar_vacuna_historia_controlador(){
			$id=mainModel::limpiar_cadena($_POST['vacuna_idhistoria_up']);

			$idvacuna=mainModel::limpiar_cadena($_POST['historia_vacuna_idvacuna_up']);
			$proxFechaVacuna=mainModel::limpiar_cadena($_POST['historia_prox_fecha_vacuna_up']);
			$producto=mainModel::limpiar_cadena($_POST['historia_vacuna_producto_up']);
			$observa=mainModel::limpiar_cadena($_POST['historia_vacuna_observacion_up']);
			
		
			/*----Campos vacios---------*/
			if($idvacuna=="" || $producto==""){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Completar campos vacios",
					"Texto"=>"Vacuna"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			
			/*--X--Campos vacios----X-----*/

			/*------ validar entrada de datos ---------*/
			if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\-: ]{1,100}",$producto)){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Campo no coincide con el formato solicitado",
					"Texto"=>"Vacuna"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			
			/*----- Comprobar id historial si existe en DB  ----- */
			// idHistoriaVacuna
			$check_vacuna_historial=mainModel::ejecutar_consulta_simple("SELECT idHistoriaVacuna,historiavFecha,codMascota FROM historialvacuna WHERE idHistoriaVacuna='$id'");
			if($check_vacuna_historial->rowCount()<=0){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Historial de vacuna seleccionada no se encuenta registrada",
					"Texto"=>"vacuna"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			
			/*---X--- validar entrada de datos ----X-----*/

			$proxFechaVacuna = date("Y-m-d", strtotime($proxFechaVacuna));

			/*$check_fecha_historial=mainModel::ejecutar_consulta_simple("SELECT * FROM historialvacuna WHERE '$proxFechaVacuna' > historiavFechaProxVacuna AND idHistoriaVacuna = '$id'");
			if($check_fecha_historial->rowCount()<=0){ 
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"La Fecha debe ser mayor",
					"Texto"=>"Vacuna"
				];
				echo json_encode($alerta_simple);
				exit();
			}*/
			
			/*---PREPARAR CARGAR---*/
			$datos = [
				"idVacuna" => $idvacuna,
				"FechaProxVacuna" => $proxFechaVacuna = !empty($proxFechaVacuna) ? $proxFechaVacuna : null,
				"Producto" => $producto,
				"Obser" => $observa,
				"idVacunaH" => $id
			];
			
			// instancia A Modelo ->
			$actualizar_vacunah=vacunaHistorialModelo::actualizar_historia_vacuna_modelo($datos);
			if($actualizar_vacunah->rowCount()==1){
				$alerta_simple=[
					"Alerta"=>"success",
					"Titulo"=>"Datos actualizados con exito",
					"Texto"=>"Historial de vacuna editada",
					"Form"=>"VacunaH",
					"Action"=>"Editar",
				];
			}else{
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Fallo al actualizar datos",
					"Texto"=>"Fallo de servidor"
				];
			}
			echo json_encode($alerta_simple);


		} // fin actualizar_vacuna_controlador
		
	}