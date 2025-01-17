<?php 
 
 	if ($peticionAjax) {
	  require_once "../modelos/notasModelo.php";
	} else {
	  require_once "./modelos/notasModelo.php";
	}

	/**
	 * hereda de notas de Modelo, y esta de mainModel
	 */
	class notasControlador extends notasModelo{

		/* Agregar notas: limpiar entradas, validar, enviar a modelo
		*	@return: json_encode(array): alerta con respuesta de servidor y validaciones
		*/
		public function agregar_notas_controlador(){
			$codmascota=mainModel::limpiar_cadena($_POST['nota_codmascota_reg']);
			$descripcion=mainModel::limpiar_cadena($_POST['nota_descripcion_reg']);
			
			$fecha = date('Y-m-d');
			
			/*----Campos vacios---------*/
			if($codmascota=="" || $descripcion=="" || $fecha==""){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Campos vacios",
					"Texto"=>"Nota"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			
			/*--X--Campos vacios----X-----*/

			/*------ validar entrada de datos ---------*/
			if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,140}",$descripcion)){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"La Nota no coincide con el formato solicitado",
					"Texto"=>"Nota"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			
			/*----- Comprobar cod si existe en DB  ----- */
			$check_cod=mainModel::ejecutar_consulta_simple("SELECT codMascota FROM mascota WHERE codMascota='$codmascota'");
			if($check_cod->rowCount()<=0){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"El codigo de mascota no se encuenta registrado",
					"Texto"=>"Codigo Mascota"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			
			/*---X--- validar entrada de datos ----X-----*/

			/*---PREPARAR CARGAR---*/
			$datos = [
				"codMascota" => $codmascota,
				"Descripcion" => $descripcion,
				"Fecha" => $fecha
			];

			// instancia a modelo
			$guardar_nota=notasModelo::agregar_nota_modelo($datos);
			
			if($guardar_nota->rowCount()==1){
				
				$alerta_simple=[
					"Alerta"=>"success",
					"Titulo"=>"Datos guardados con exito",
					"Texto"=>"Nota Guardada",
					"Form"=>"Nota"
				];
				
			}else{
				$alerta_simple=[
					"Alerta"=>"error",
					"Titulo"=>"Fallo al guardar los datos",
					"Texto"=>"Nota"
				];
				
			}
			echo json_encode($alerta_simple);
		

		} // fin agregar_notas_controlador

		/** Mostrar notas guardadas forma load more
		*   @return: frammento html(<li>) con notas de mascota
		*/
		public function mostrar_notas_controlador(){

			$limit=mainModel::limpiar_cadena($_POST['limit']);
			$offset=mainModel::limpiar_cadena($_POST['offset']);
			$codmascota=mainModel::limpiar_cadena($_POST['codmascota']);
			$notas="";
			if(session_start(['name'=>'VETP'])){
				if(isset($_SESSION['privilegio_vetp']) == false || isset($_SESSION['nombre_vetp']) == false){
					echo $notas.='<div>Fallo al iniciar sesion</div>';
					exit();	
				}else{
					$privilegio=mainModel::limpiar_cadena($_SESSION['privilegio_vetp']);
				}
			}else{
				echo $notas.='<div>Fallo al iniciar sesion</div>';
				exit();
			}
			// consulta SQL: buscar toda las notas de una mascota, ordenado de forma descendente
			$sql = "SELECT * FROM notasmascotas WHERE codMascota='$codmascota' ORDER BY idNota DESC LIMIT $limit OFFSET $offset";

			$conexion = mainModel::conectar();
			$datos = $conexion->query($sql);
			$datos = $datos->fetchAll();

			foreach($datos as $rows){ 
				$notas.='
					<li>
		              <!-- edit or delete -->      
		              <div class="tools">';
		              if($privilegio<=2){
		              	$notas.='
		              		<!-- EDITAR NOTA -->
		              		<button data-toggle="modal" data-target=".bd-example-modal-sm" class="btn" value="'.mainModel::encryption($rows['idNota']).'" server="'.SERVERURL.'ajax/notaAjax.php" id="btn_edit_nota">
		              			<i class="fas fa-edit fa-sm"></i>
		              		</button>	
		                 	<!-- ELIMINAR NOTA  -->
	                		
							<button id="btn_del_nota" value="'.mainModel::encryption($rows['idNota']).'" server="'.SERVERURL.'ajax/notaAjax.php" class="btn">
				                  <i class="fas fa-trash-alt fa-sm"></i>
				             </button>
							';
		              }

						$notas.='		
		              </div>
		              <!-- text -->
		              <span class="text">'.$rows['notaDescripcion'].'</span>
		              <!-- fecha -->
		              <span class="text-xs text-gray-500"><i class="fas fa-calendar-day mr-2"></i>'.$rows['notaFecha'].'</span>
		            </li>

				';
			}	
			echo $notas;


		} // fin buscar_dueno controlador

		/* Elimimar Nota
		*	return: json_encode(array): respuesta de DB en alerta_simple
		*/
		public function eliminar_nota_controlador(){
			$id=mainModel::decryption($_POST['nota_id_dele']);
			$id=mainModel::limpiar_cadena($id);
			
			// ------- comprobar nota id en DB ----->
			$check_nota = mainModel::ejecutar_consulta_simple("SELECT idNota FROM notasmascotas WHERE idNota='$id'");
			if($check_nota->rowCount()<=0){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Nota no registrada",
					"Texto"=>"Nota"
				];
				echo json_encode($alerta_simple);
				exit();
				
			}
			
			$eliminar_nota=notasModelo::eliminar_nota_modelo($id);
			if($eliminar_nota->rowCount()==1){
				$alerta_simple=[
					"Alerta"=>"success",
					"Titulo"=>"Nota Eliminada con exito",
					"Texto"=>"Nota Eliminada",
					"Form"=>"notas"
				];
			}else{
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"No hemos podido elimimar la nota",
					"Texto"=>"Fallo eliminar"
				];
			}
			echo json_encode($alerta_simple);

		} // fin controlador eliminar

		/*	Buscar datos nota edit,
		* @param: $tipo: unico o conteo, $id: de nota
		*/
		public static function datos_nota_controlador($tipo,$id){
			$tipo=mainModel::limpiar_cadena($tipo);
			$id=mainModel::decryption($id);
			$id=mainModel::limpiar_cadena($id);

			return notasModelo::datos_nota_modelo($tipo,$id);
		} // fin datos_nota_controlador
		
		/* Actualizar nota en modal
		*	@return: json_encode(array): alerta con respueta de servidor y validaciones
		*/
		public static function actualizar_nota_controlador(){

			$id=mainModel::limpiar_cadena($_POST['nota_id_up']);
			$codmascota=mainModel::limpiar_cadena($_POST['nota_codmascota_up']);
			$descripcion=mainModel::limpiar_cadena($_POST['nota_descripcion_up']);
			$fecha = date('Y-m-d');
						
			/*----Campos vacios---------*/
			if($id=="" || $codmascota=="" || $descripcion=="" || $fecha==""){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Campos vacios",
					"Texto"=>"Nota"
				];
				echo json_encode($alerta_simple);
				exit();
			}

			/*----- Comprobar cod si existe en DB  ----- */
			$check_nota=mainModel::ejecutar_consulta_simple("SELECT * FROM notasmascotas WHERE idNota='$id'");
			if($check_nota->rowCount()<=0){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"La Nota no se encuenta registrada",
					"Texto"=>"No registrada"
				];
				echo json_encode($alerta_simple);
				exit();
			}else{
				$campos=$check_nota->fetch();
			}

			if($campos['codMascota']!=$codmascota){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Esta nota no pertenece a esta mascota",
					"Texto"=>"No pertenece"
				];
				echo json_encode($alerta_simple);
				exit();
			}

			/*------ validar entrada de datos ---------*/
			if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,140}",$descripcion)){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"La Nota no coincide con el formato solicitado",
					"Texto"=>"Nota"
				];
				echo json_encode($alerta_simple);
				exit();
			}

			/*---PREPARAR CARGAR---*/
			$datos = [
				"codMascota" => $codmascota,
				"Descripcion" => $descripcion,
				"Fecha" => $fecha,
				"Id" => $id
			];

			// instancia
			$actualizar_nota=notasModelo::actualizar_nota_modelo($datos);
			
			if($actualizar_nota->rowCount()==1){
				
				$alerta_simple=[
					"Alerta"=>"success",
					"Titulo"=>"Datos actualizados con exito",
					"Texto"=>"Nota Actualizada",
					"Form"=>"Nota-edit"
				];
				
			}else{
				$alerta_simple=[
					"Alerta"=>"error",
					"Titulo"=>"Fallo al actualizar los datos",
					"Texto"=>"Nota"
				];
				
			}
			echo json_encode($alerta_simple);
			

		} // actualizar_nota_controlador

	}