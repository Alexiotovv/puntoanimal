<?php 
 
 	if ($peticionAjax) {
	  require_once "../modelos/historialModelo.php";
	  require_once "../modelos/citaModelo.php";
	  require_once "../modelos/vacunaHistorialModelo.php";
	} else {
	  require_once "./modelos/historialModelo.php";
	  require_once "./modelos/citaModelo.php";
	  require_once "./modelos/vacunaHistorialModelo.php";
	}
					
	/* Clase historialControlador: gestiona las historias clinicas de mascotas
	*	permite, gestionar a traves de una cita, o seleccion desde el listado de mascota.
	*/
	class historialControlador extends historialModelo{

		/* Agregar Historia clinica de mascota: limpiar entradas, validar, enviar a modelo
		*  @return: json_encode, alerta con respuesta
		*/
		public function agregar_historia_controlador(){

			$codhistoria=mainModel::limpiar_cadena($_POST['historial_codigo_reg']);
			$motivo=mainModel::limpiar_cadena($_POST['historia_motivo_reg']);
			$sintomas=mainModel::limpiar_cadena($_POST['historia_sintomas_reg']);
			$anamnesis=mainModel::limpiar_cadena($_POST['historia_anamnesis_reg']);
			$diagnostico=mainModel::limpiar_cadena($_POST['historia_diagnostico_reg']);
			$diagnosticopre=mainModel::limpiar_cadena($_POST['historia_diagnosticopre_reg']);
			$tratamiento=mainModel::limpiar_cadena($_POST['historia_tratamiento_reg']);
			$fecha=mainModel::limpiar_cadena($_POST['historia_fecha_reg']);
			$horaSinFormato=mainModel::limpiar_cadena($_POST['historia_hora_reg']);
			$hora = date("H:i:s", strtotime($horaSinFormato));
			
			$codmascota=mainModel::limpiar_cadena($_POST['historia_codpaciente_reg']);
			$codveterinario=mainModel::limpiar_cadena($_POST['historia_codveterinario_reg']);
			$creador=mainModel::limpiar_cadena($_POST['historial_creador_reg']);
	    	//$fecha = date('Y-m-d');
			//$hora = date('h:i:s');	
			
			$fecha = date("Y-m-d", strtotime($fecha));

			/*----Campos vacios---------*/
			if($codhistoria==""   || $codmascota=="" || $codveterinario==""  || $fecha=="" || $horaSinFormato=="" ){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			
			/*----- Comprobar codigo de mascota si existe en DB  ----- */
			$check_mascota=mainModel::ejecutar_consulta_simple("SELECT codMascota FROM mascota WHERE codMascota='$codmascota'");
			if($check_mascota->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La mascota no se encuentra registrada en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/*--si se carga archivos: validar extencion (jpg,jpeg,png,pdf) -*/
			if(!empty(array_filter($_FILES['archivos_multiples']['name']))){
				// validar extencion de todos los archivos
				$errorUploadType = '';
				$uploadDir = '../adjuntos/historial-mascota/'.$codhistoria;
				foreach($_FILES['archivos_multiples']['name'] as $key=>$val){
					$filename = basename($_FILES['archivos_multiples']['name'][$key]);
		            $targetFile = $uploadDir."/".$filename;
		           	$tipo = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
		           	$form_permitidos=array("jpg","jpeg","png","pdf");
		           	if(in_array($tipo, $form_permitidos)){ 
		                
		            }else{
		            	$errorUploadType .= $_FILES['archivos_multiples']['name'][$key].' | ';
		            }
				}
				if($errorUploadType!=''){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Archivos no permitidos | $errorUploadType ",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();	
				}
			}
			/*================ HISTORIAL VACUNA ============*/
			$idvacuna=mainModel::limpiar_cadena($_POST['historia_vacuna_idvacuna_reg']);
			// SI selecciono una vacuna en select
			if($idvacuna != 0){
				
				$productoVacuna=mainModel::limpiar_cadena($_POST['historia_vacuna_producto_reg']);
				$proxFechaVacuna=mainModel::limpiar_cadena($_POST['historia_prox_fecha_vacuna_reg']);
				$observacionVacuna=mainModel::limpiar_cadena($_POST['historia_vacuna_observacion_reg']);

				$proxFechaVacuna = date("Y-m-d", strtotime($proxFechaVacuna));
				
				if($productoVacuna==""){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Necesario información de producto",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
				/*----PREPARAR CARGA HISTORIAL VACUNAS---*/
				$datos_vacuna = [
					"idVacuna" => $idvacuna,
					"Fecha" => $fecha,
					"FechaProxVacuna" => $proxFechaVacuna,
					"Producto" => $productoVacuna,
					"Obser" => $observacionVacuna,
					"codMascota" => $codmascota
				];

				// instancia a historia vacuna modelo
				$guardar_historiaVacuna=vacunaHistorialModelo::agregar_historia_vacuna_modelo($datos_vacuna);
			
				if($guardar_historiaVacuna->rowCount()<=0){
					// error al cargar vacuna
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Fallo al cargar historial de vacuna, por favor intente nuevamente",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}

			}
			/*===============x===HISTORIAL VACUNA ======X======*/

			/*======= ATENDER CITA valida codigo cita ===== */
			if(isset($_POST['historial_codcita_reg'])){
				$codcita=mainModel::decryption($_POST['historial_codcita_reg']);
				$codcita=mainModel::limpiar_cadena($codcita);
				// ------- comprobar cita en DB ----->
				$check_cita = mainModel::ejecutar_consulta_simple("SELECT codCita FROM citas WHERE codCita='$codcita'");
				if($check_cita->rowCount()<=0){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"La cita no existe en el sistema",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}	
			}
			/*====X=== ATENDER CITA valida codigo cita ==X=== */

			/*----PREPARAR CARGA ---*/
			$datos = [
				"codHistorial" => $codhistoria,
				"Fecha" => $fecha,
				"Hora" => $hora,
				"Motivo" => $motivo,
				"Anamnesis" => $anamnesis,
				"Sintomas" => $sintomas,
				"Diagnostico" => $diagnostico,
				"Diagnosticopre" => $diagnosticopre,
				"Tratamiento" => $tratamiento,
				"Creador" => $creador,
				"Mascota" => $codmascota,
				"Veterinario" => $codveterinario
			];

			// instancia a historia modelo
			$guardar_historia=historialModelo::agregar_historia_modelo($datos);
			
			if($guardar_historia->rowCount()==1){
				$urlmascota=mainModel::encryption($codmascota);
				/*----- CARGA ARCHIVOS MULTIPLES IMAGE,PDF -------*/
				$insertValuesSQL = $errorUpload = '';
				
				if(!empty(array_filter($_FILES['archivos_multiples']['name']))){
					/*---crear carpeta para archivos adjuntos ----- */
					mkdir($uploadDir, 0755);
					// cargar archivos ----
					foreach($_FILES['archivos_multiples']['name'] as $key=>$val){
						$titulo=mainModel::limpiar_cadena($_POST['archivos_adjtitulo'][$key]);
						
			            $aleatorio = mt_rand(100,999);
		            	$filename = date('Y_m_d_His_').$aleatorio."_".basename($_FILES['archivos_multiples']['name'][$key]);
			            $targetFile = $uploadDir."/".$filename;
			           	$tipo = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
			           	$ruta_db ="adjuntos/historial-mascota/".$codhistoria."/".$filename;
			           	
		                // Upload file to server 
		                if(move_uploaded_file($_FILES["archivos_multiples"]["tmp_name"][$key], $targetFile)){ 
		                    // Files db insert sql 
		                    $insertValuesSQL .= "('".$codhistoria."','".$tipo."','".$ruta_db."','".$titulo."','".$fecha."'),";
		                    
		                }else{ 
		                    $errorUpload .= $_FILES['archivos_multiples']['name'][$key].' | '; 
		                } 
			            
			        
			        } 
			        // ---- cargar en DB de archivos adjuntos ---
			        if(!empty($insertValuesSQL)){
			        	$insertValuesSQL = trim($insertValuesSQL, ',');
			        	// instancia agregar adjuntos
			        	$guardar_historia_adjunto=historialModelo::agregar_historia_adjuntos_modelo($insertValuesSQL);
			        	
			        	if($guardar_historia_adjunto->rowCount()>=1){
			        		// si viene desde cita 
							/*======= ATENDER CITA UPDATE =======*/
							if(isset($_POST['historial_codcita_reg'])){
								// <<<<---actualizar campo estado de cita --<<<<<<<<<<<<<
								$updateEstado=citaModelo::acciones_cita_modelo("Atender",$codcita);

								if($updateEstado->rowCount()>=1){
									$alerta=[
										"Alerta"=>"limpiar",
										"Titulo"=>"Historia registrada/Cita",
										"Texto"=>"Los datos fueron registrados",
										"Tipo"=>"success",
										"User"=>"historial",
										"URL"=>SERVERURL."perfilMascota/".$urlmascota
									];
									
								}else{
									historialModelo::eliminar_historia_modelo($codhistoria);
									// ***
									$alerta=[
										"Alerta"=>"simple",
										"Titulo"=>"Ocurrió un error inesperado",
										"Texto"=>"No hemos podido Actualizar los datos de cita, intente nuevamente",
										"Tipo"=>"error"
									];
									echo json_encode($alerta);
									exit();
								}
							}else{
								// guardar adjunto exito
								$alerta=[
									"Alerta"=>"limpiar",
									"Titulo"=>"Historia registrada",
									"Texto"=>"Los datos fueron registrados",
									"Tipo"=>"success",
									"User"=>"historial",
									"URL"=>SERVERURL."perfilMascota/".$urlmascota
								];	
							}		        		
							/*====X=== ATENDER CITA UPDATE ===X====*/

						}else{
							// ***
							historialModelo::eliminar_historia_modelo($codhistoria);
							$alerta=[
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrió un error inesperado",
								"Texto"=>"No hemos podido registrar la historia (fallo al cargar archivos)",
								"Tipo"=>"error"
							];
						}
			        }
			        
				}else{
					// SIN ARCHIVOS ADJUNTOS
					/*======= ATENDER CITA UPDATE =======*/
					if(isset($_POST['historial_codcita_reg'])){
						// actualizar campo estado de cita <<<<<<<<<<<<<<---
						 $updateEstado=citaModelo::acciones_cita_modelo("Atender",$codcita);
						
						if($updateEstado->rowCount()>=1){
							$alerta=[
								"Alerta"=>"limpiar",
								"Titulo"=>"Historia registrada/Cita",
								"Texto"=>"Los datos fueron registrados",
								"Tipo"=>"success",
								"User"=>"historial",
								"URL"=>SERVERURL."perfilMascota/".$urlmascota
							];
							
						}else{
							historialModelo::eliminar_historia_modelo($codhistoria);
							$alerta=[
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrió un error inesperado",
								"Texto"=>"No hemos podido Actualizar los datos de cita, intente nuevamente",
								"Tipo"=>"error"
							];
							echo json_encode($alerta);
							exit();
						}
					}else{
						// sin archivos adjuntos, ni cita
						$alerta=[
							"Alerta"=>"limpiar",
							"Titulo"=>"Historia registrada",
							"Texto"=>"Los datos fueron registrados",
							"Tipo"=>"success",
							"User"=>"historial",
							"URL"=>SERVERURL."perfilMascota/".$urlmascota
						];	
					}
					/*===X==== ATENDER CITA UPDATE ====X===*/
					
				} // else sin archivos adjuntos

			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar la historia",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		
		} //function agregar_historia_controlador

		/* Elimimar Historia desde perfil mascota
		*	@return: json_encode: alerta simple con respuesta
		*/
		public function eliminar_historia_controlador(){
			$id=mainModel::decryption($_POST['historia_id_dele']);
			$id=mainModel::limpiar_cadena($id);
			
			// ------- comprobar cod en DB ----->
			$check_historia = mainModel::ejecutar_consulta_simple("SELECT codHistorialM FROM historialmascota WHERE codHistorialM='$id'");
			if($check_historia->rowCount()<=0){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Historia no registrada",
					"Texto"=>"Historia medica no registrada"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			
			// ELIMINAR archivos ADJUNTO de carpeta-----
			$check_adjunto = mainModel::ejecutar_consulta_simple("SELECT * FROM adjuntoshistorial WHERE codHistorialM='$id'");
			if($check_adjunto->rowCount()>0){
				$campos=$check_adjunto->fetchAll();
				foreach ($campos as $rows) {
					unlink("../".$rows['adjFileName']);
				}
				rmdir('../adjuntos/historial-mascota/'.$id);
			}
			// instancia a modelo
			$eliminar_historia=historialModelo::eliminar_historia_modelo($id);
			if($eliminar_historia->rowCount()==1){
				$alerta_simple=[
					"Alerta"=>"success",
					"Titulo"=>"Historia Eliminada con exito carpeta",
					"Texto"=>"Historia Eliminada",
					"Form"=>"perfil_historial"

				];
			}else{
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"No hemos podido elimimar la Historia",
					"Texto"=>"Fallo al eliminar"
				];
			}
			echo json_encode($alerta_simple);

		} // fin controlador eliminar

		/*	Buscar datos de historias clinicas
		* @param: $tipo: unico,conteo $cod:codigo historia,
		*/
		public static function datos_historia_controlador($tipo,$cod){
			$cod=mainModel::decryption($cod);
			$cod=mainModel::limpiar_cadena($cod);
			$tipo=mainModel::limpiar_cadena($tipo);

			return historialModelo::datos_historia_modelo($tipo,$cod);
		} // fin datos_historia_controlador

		/* Actualizar historia sesion info
		*	@return: json_encode(array): respuesta de servidor y validaciones
		*/
		public function actualizar_historia_controlador(){
			$codhistoria=mainModel::limpiar_cadena($_POST['historia_cod_edit']);
			$motivo=mainModel::limpiar_cadena($_POST['historia_motivo_edit']);
			$anamnesis=mainModel::limpiar_cadena($_POST['historia_anamnesis_edit']);
			$sintomas=mainModel::limpiar_cadena($_POST['historia_sintomas_edit']);
			$diagnostico=mainModel::limpiar_cadena($_POST['historia_diagnostico_edit']);
			$diagnosticopre=mainModel::limpiar_cadena($_POST['historia_diagnosticopre_edit']);
			$tratamiento=mainModel::limpiar_cadena($_POST['historia_tratamiento_edit']);
			$fecha=mainModel::limpiar_cadena($_POST['historia_fecha_edit']);
			$horaSinFormato=mainModel::limpiar_cadena($_POST['historia_hora_edit']);
			$hora = date("H:i:s", strtotime($horaSinFormato));
			
			/*----- Comprobar cod si existe en DB  ----- */
			$check_cod=mainModel::ejecutar_consulta_simple("SELECT * FROM historialmascota WHERE codHistorialM='$codhistoria'");
			if($check_cod->rowCount()<=0){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"La Historia no se encuenta registrada",
					"Texto"=>"Error inesperado"
				];
				echo json_encode($alerta_simple);
				exit();
			}else{
				$campos=$check_cod->fetch();
			}

			/*----Campos vacios---------*/
			if($codhistoria=="" ){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			$fecha = date("Y-m-d", strtotime($fecha));
		
			/*----PREPARAR CARGA ---*/
			$datos = [
				"Motivo" => $motivo,
				"Sintomas" => $sintomas,
				"Anamnesis" => $anamnesis,
				"Diagnostico" => $diagnostico,
				"Diagnosticopre" => $diagnosticopre,
				"Tratamiento" => $tratamiento,
				"codHistorial" => $codhistoria,
				"Fecha" => $fecha,
				"Hora" => $hora
			];
			// instancia
			$actualizar_historia=historialModelo::actualizar_historia_modelo($datos);
			
			if($actualizar_historia->rowCount()==1){
				
				$alerta_simple=[
					"Alerta"=>"success",
					"Titulo"=>"Datos actualizados con exito",
					"Texto"=>"Historia Editada",
					"Form"=>"recarga_infh",
					"codH"=>$codhistoria
				];
				
			}else{
				$alerta_simple=[
					"Alerta"=>"error",
					"Titulo"=>"Fallo al actualizar los datos",
					"Texto"=>"Nota"
				];
				
			}
			echo json_encode($alerta_simple);
			
			
		}

		/*	fragmento de Datos, sesion info general de historia
		*  @return: json_encode(array): con html de info historia
		*/
		public function datos_infohistoria_controlador(){
			$codigo_historial=mainModel::limpiar_cadena($_POST['inf_codhistorial']);

			$campos_info=historialModelo::datos_historia_modelo("Unico",$codigo_historial);
			$htmlinfo="";
			if($campos_info->rowCount()>=1){
				$info=$campos_info->fetch();
				$htmlinfo.='
				<span><b>N. Historial: '.$info['codHistorialM'].' </b></span>
			   <hr>
			   <span><b>Motivo consulta:</b></span>
			   <p>'.$info['histMotivo'].'</p>
					<div class="">
						<span><b>Anamnesis:</b></span>
						<p>'.$info['histAnamnesis'].'</p>
					</div>
					<div class="">
						<span><b>Hallazgos Clinicos:</b></span>
						<p>'.$info['histSintomas'].'</p>
					</div>
					<div>
						<span><b>Examenes Diagnostico:</b></span>
						<p>'.$info['histDiagnostico'].'</p>
						<span><b>Diagnostico Presuntivo:</b></span>
						<p>'.$info['histDiagnosticoPre'].'</p>
					</div>
					<div class="">
						<span><b>Tratamiento:</b></span>
						<p>'.$info['histTratamiento'].'</p>
					</div>
	   ';
			}
			$datos=[
				"InfoH"=>$htmlinfo,
				"Motivo"=>$info['histMotivo']
			];

			echo json_encode($datos);
		}

		/*	Buscar todas las historias de mascota mostrar en perfil mascota
		* 	@return: json_encode(array): fragmento html de historia/s, $historial_lista, total de historias
		*/
		public function datos_perfil_mascota_controlador(){
			$limit=mainModel::limpiar_cadena($_POST['limit']);
			$offset=mainModel::limpiar_cadena($_POST['offset']);
			$codmascota=mainModel::limpiar_cadena($_POST['codmascota']);
			$historial_lista="";
			if(session_start(['name'=>'VETP'])){
				if(isset($_SESSION['privilegio_vetp']) == false){
					echo $historial_lista.='<div>Fallo al iniciar sesion</div>';
					exit();
				}else{
					$privilegio=mainModel::limpiar_cadena($_SESSION['privilegio_vetp']);
				}
			}else{
				echo $historial_lista.='<div>Fallo al iniciar sesion</div>';
				exit();
			}
			// mostrar todas las historias de mascota
			$sql = "SELECT * FROM historialmascota WHERE codMascota='$codmascota' ORDER BY idHistorial DESC LIMIT $limit OFFSET $offset";
			$sql_total="SELECT idHistorial FROM historialmascota WHERE codMascota='$codmascota'";
			$conexion = mainModel::conectar();
			$datos = $conexion->query($sql);
			$datos = $datos->fetchAll();
			// total hist
			$total = $conexion->query($sql_total);
			$total = $total->rowCount();

			foreach($datos as $rows){
				// files, imagenes, PDF
				$lista_files_img="";
				$lista_files_pdf="";
				// todos los archivos adjuntos
				$lista_files=historialModelo::datos_perfil_mascota_adjuntos_modelo($rows['codHistorialM']);
				if($lista_files->rowCount()>=1){
					$campos_files=$lista_files->fetchAll();

					foreach ($campos_files as $rowsf) {
						$file_type=$rowsf['adjTipo'];
						if($file_type=="png" || $file_type=="jpg" || $file_type=="jpeg"){
							$lista_files_img.='
							<div class="ml-4 align-items-center">
			                    <!-- FORM ELIMINAR IMAGEN -->
			                    <form action="'.SERVERURL.'" method="POST" class="align-items-center justify-content-center" data-form="" >
			                      ';
			                      if($privilegio==1){
			                      	$lista_files_img.='
				                      
				                      <button id="btn_del_adjuntos" value="'.mainModel::encryption($rowsf['idAdjunto']).'" class="btn btn-eliminar-img">
				                          <!-- <i class="fas fa-trash-alt up"></i> -->
				                      </button>';
			                      }
			                      
			                      $lista_files_img.='
			                      <a href="'.SERVERURL.$rowsf['adjFileName'].'" title="'.$rowsf['adjTitulo'].'" class="insta-img">
			                        <img class="img" src="'.SERVERURL.$rowsf['adjFileName'].'" alt="'.$rowsf['adjTitulo'].'">
			                      </a>
			                    </form>
			                    <!-- X- FORM ELIMINAR IMAGEN -x-->
			                  </div>
						';
						
						}else if($file_type=="pdf"){
							$lista_files_pdf.='
							<div class="ml-4 mt-2">
			                    <!-- FORM ELIMINAR PDF -->
			                    <form action="'.SERVERURL.'ajax/archivoAjax.php" method="POST" class="FormularioAjax" data-form="delete">
			                      <!-- CODIGO id pdf -->';
			                     if ($privilegio==1) {
			                      	$lista_files_pdf.='
				                      
				                      <!-- btn eliminar efect hover -->
				                      <button id="btn_del_adjuntos" value="'.mainModel::encryption($rowsf['idAdjunto']).'" class="btn btn-eliminar-img">
				                      </button>';
			                      }

			                      $lista_files_pdf.='
			                      <!-- imagen mostrar -->
			                      <a href="'.SERVERURL.$rowsf['adjFileName'].'" target="pdf-frame" class="text-gray-600">
			                        <i class="fas fa-file-pdf"></i>
			                        <br><span>'.$rowsf['adjTitulo'].'</span>
			                      </a>
			                      <!-- x- imagen mostrar -x -->
			                    </form>
			                    <!-- X-X eliminar PDF -->
			                  </div>
							';
						}
						
					}
				}else{
					$lista_files_img='<div class="ml-4 align-items-center"><p>Sin imagenes</p></div>';
					$lista_files_pdf='<div class="ml-4 mt-2"><p>Sin Archivos PDF</p></div>';
				}
				// ------------------------------------------------------
				$historial_lista.='
					<li class="timeline-inverted timeline-item">
			          <!-- icon timeline -->
			          <div class="timeline-badge">
			          	<i class="flaticonv-030-pawprint"></i>
			          </div>
			          <div class="timeline-panel">
				            <!-- titulo historia -->
				            <div class="panel-heading">
				              <h4>							  				               
    								<div style="display: flex; justify-content: space-between; align-items: center;">
        								<a class="' . $rows['codHistorialM'] . '" role="button" data-toggle="collapse" href="#' . $rows['codHistorialM'] . '">
          								  ' . $rows['histMotivo'] . '
      									  </a>
      								  <a class="btn-historia-imp" title="Imprimir" href="#" id_hist="' . $rows['idHistorial'] . '" serverurl="' . SERVERURL . '">
          							  <i class="fas fa-print" style="color:blue;"></i>
       								 </a>
   								 </div>
									
				              </h4>
				            </div>
				            <!-- x  titulo historia X -->
				            <!-- COLLAPSE -->
				            <div id="'.$rows['codHistorialM'].'" class="panel-collapse collapse">
				              <div class="panel-body">';
					             if($privilegio<=2){
					             	$historial_lista.='
					            	<!-- ---BTN EDIDAR --- -->
					                <button data-toggle="modal" id="btn_edit_historia" value="'.mainModel::encryption($rows['codHistorialM']).'" data-target="#modal-edit-historia" data-toggle="tooltip" title="Editar" class="btn icon-action float-right btn-circle"><i class="fas fa-pencil-alt"></i>
					                </button>';
					             }
					            if($privilegio==1){
					            	$historial_lista.='
					            	<!-- BTN ELIMINAR -->
					            	<form>
					                <button id="btn_del_historia" value="'.mainModel::encryption($rows['codHistorialM']).'" data-toggle="tooltip" title="Eliminar" class="btn icon-action float-right btn-circle"><i class="fas fa-trash-alt"></i></button>
					                <!-- x BTN ELIMINAR  x -->
					                </form>';
					            }
				            
								$historial_lista.='
				                <!-- --------------INFO-HIST-------------- -->
							<div class="sectioninfh">
								<span><b>N. Historial: '.$rows['codHistorialM'].' </b></span>
								<hr>
								<span><b>Motivo consulta:</b></span>
								<p>'.$rows['histMotivo'].'</p>
								<div class="">
									<span><b>Anamnesis:</b></span>
									<p>'.$rows['histAnamnesis'].'</p>
								 </div>
								<div class="">
								  <span><b>Hallazgos Clinicos:</b></span>
								  <p>'.$rows['histSintomas'].'</p>
								</div>
								<div>
								  <span><b>Examenes Diagnosticos:</b></span>
								  <p>'.$rows['histDiagnostico'].'</p>
								  <span><b>Diagnostico Presuntivo:</b></span>
								  <p>'.$rows['histDiagnosticoPre'].'</p>
								</div>

								<div class="">
								  <span><b>Tratamiento:</b></span>
								  <p>'.$rows['histTratamiento'].'</p>
								</div>
							</div>
				                <!-- -------x------INFO-HIST------x----------- -->
				                <hr>
				                <!------- Archivos ADJUNTO ---------->
				                <span><i class="fas fa-paperclip mr-2"></i><b>Archivos adjunto</b></span>
				                <!-- BTN SUBIR MAS ARCHIVOS -->
				                <a href="#" data-toggle="tooltip" title="Subir Archivo" class="btn icon-action float-right rounded-circle">
				                <i data-toggle="modal" data-codhistoria="'.$rows['codHistorialM'].'" data-target="#modal-add-adjuntoh" class="fas fa-upload"></i></a>
				                <!-- X -BTN SUBIR MAS ARCHIVOS X --->
				                <br>
				                <!-- ADJ- IMAGENES -->
				                <span>Imagenes</span>
				                <div class="archivo-adj d-flex flex-wrap">
				                  <!-- item img 1 -->
				                  '.$lista_files_img.'
				                  <!-- x item img 1 x -->
				                  
				                </div>
				                <!-- x ADJ- IMAGENES x -->
				                <!-- ------ ADJ PDF --------- -->
				                <span>Archivos PDF</span><br>
				                <div class="archivo-adj-pdf d-flex flex-wrap">
				                  <!-- CARGAR ADJUNTO -->
				                  <!-- item 1 -->
				                  '.$lista_files_pdf.'
				                  <!-- X item 1 X-->
				                  
				                  <!-- x CARGAR ADJUNTO X -->
				                </div>
				                <!-- ------ ADJ PDF --------- -->
				              </div>
				            </div>
				            <!-- x COLLAPSE x -->
				            <!-- footer panel historia -->
				            <div class="panel-footer">
				              <span>
				                <i class="far fa-calendar fa-sm mr-2"></i>'.$rows['histFecha'].'<i class="far fa-clock fa-sm ml-2 mr-2"></i>'.$rows['histHora'].'</span>
				              <span class="float-right">Creada  por: '.$rows['histCreador'].'</span>
				            </div>
				            <!-- X  footer panel historia X -->
			          </div>    
			        </li>
					';
			} // foreach -x-

			$datos_his=[
				"ListaH"=>$historial_lista,
				"TotalH"=>$total
			];
			
			echo json_encode($datos_his);
			
		} // fin datos_perfil_cliente_controlador

		/* Agregar archivos adjuntos, desde perfil mascota
		*	@return: json_encode: alerta simple con respuesta
		*/
		public function agregar_historia_adjunto_controlador(){

			$codhistoria=mainModel::limpiar_cadena($_POST['adjunto_codhistoria_up']);
			$fecha = date('Y-m-d');

			$check_historia = mainModel::ejecutar_consulta_simple("SELECT codHistorialM FROM historialmascota WHERE codHistorialM='$codhistoria'");
			if($check_historia->rowCount()<=0){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Historia no registrada",
					"Texto"=>"Codigo de historia no valido"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			
			// si no existe carpeta crearla
			$uploadDir = '../adjuntos/historial-mascota/'.$codhistoria;
			if(!file_exists($uploadDir)){
				mkdir($uploadDir, 0755);
			}
			/*--- validar extencion(jpg,jpeg,png,pdf) archivos cargados ------*/
			if(!empty(array_filter($_FILES['archivos_multiples']['name']))){
				// validar extencion de todos los archivos
				$errorUploadType = '';
				foreach($_FILES['archivos_multiples']['name'] as $key=>$val){
					$filename = basename($_FILES['archivos_multiples']['name'][$key]);
		            $targetFile = $uploadDir."/".$filename;
		           	$tipo = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
		           	$form_permitidos=array("jpg","jpeg","png","pdf");
		           	if(in_array($tipo, $form_permitidos)){ 
		                
		            }else{
		            	$errorUploadType .= $_FILES['archivos_multiples']['name'][$key].' | ';
		            }
				}
				if($errorUploadType!=''){
					$alerta_simple=[
						"Alerta"=>"warning",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Archivos no permitidos | $errorUploadType "
					];
					echo json_encode($alerta_simple);
					exit();	
				}
			}else{
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Debe seleccionar un archivo",
				];
				echo json_encode($alerta_simple);
				exit();
			}

			/*----- CARGA ARCHIVOS MULTIPLES IMAGE,PDF -------*/
			$insertValuesSQL = $errorUpload = '';
			if(!empty(array_filter($_FILES['archivos_multiples']['name']))){
				/*---crear carpeta para archivos adjuntos ----- */
				// cargar archivos ----
				foreach($_FILES['archivos_multiples']['name'] as $key=>$val){
					$titulo=mainModel::limpiar_cadena($_POST['archivos_adjtitulo'][$key]);
		            $aleatorio = mt_rand(100,999);
		            $filename = date('Y_m_d_His_').$aleatorio."_".basename($_FILES['archivos_multiples']['name'][$key]);
		            $targetFile = $uploadDir."/".$filename;
		           	$tipo = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
		           	$ruta_db ="adjuntos/historial-mascota/".$codhistoria."/".$filename;
		           	
	                // Upload file to server 
	                if(move_uploaded_file($_FILES["archivos_multiples"]["tmp_name"][$key], $targetFile)){ 
	                    // Files db insert sql 
	                    $insertValuesSQL .= "('".$codhistoria."','".$tipo."','".$ruta_db."','".$titulo."','".$fecha."'),";
	                    
	                }else{ 
	                    $errorUpload .= $_FILES['archivos_multiples']['name'][$key].' | '; 
	                } 
		            
		        
		        } 
		        // ---- cargar en DB -----
		        if(!empty($insertValuesSQL)){
		        	$insertValuesSQL = trim($insertValuesSQL, ',');

		        	$guardar_historia_adjunto=historialModelo::agregar_historia_adjuntos_modelo($insertValuesSQL);
		        	
		        	if($guardar_historia_adjunto->rowCount()>=1){
						$alerta_simple=[
							"Alerta"=>"success",
							"Titulo"=>"Archivo cargados con exito",
							"Texto"=>"Archivo guardado",
							"Form"=>"recarga_adj",
							"codH"=>$codhistoria
						];
					}else{
						$alerta_simple=[
							"Alerta"=>"warning",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"No hemos podido cargar los archivos"
						];
					}
		        }else{

		        }
		        
			}else{
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Debe seleccionar un archivo"
				];
			}
			
			echo json_encode($alerta_simple);
		
		} //function agregar_historia_adjunto_controlador

		/* 	Elimimar un archivo adjunto(img,pdf), desde perfil mascota
		*	@return: alerta simple, con respuesta de DB.
		*/
		public function eliminar_historia_adjunto_controlador(){
			$id=mainModel::decryption($_POST['adjunto_id_dele']);
			$id=mainModel::limpiar_cadena($id);
			
			// ------- comprobar cod en DB ----->
			$check_adj = mainModel::ejecutar_consulta_simple("SELECT idAdjunto FROM adjuntoshistorial WHERE idAdjunto='$id'");
			if($check_adj->rowCount()<=0){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Adjunto no registrado",
					"Texto"=>"Error inesperado"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			
			// ELIMINAR archivo ADJUNTO-----
			$check_adjunto = mainModel::ejecutar_consulta_simple("SELECT * FROM adjuntoshistorial WHERE idAdjunto='$id'");
			if($check_adjunto->rowCount()>0){
				$campos=$check_adjunto->fetch();
				// eliminar de carpeta el archivo
				unlink("../".$campos['adjFileName']);
			}

			$eliminar_adjunto=historialModelo::eliminar_historia_adjuntos_modelo($id);
			if($eliminar_adjunto->rowCount()==1){
				$alerta_simple=[
					"Alerta"=>"success",
					"Titulo"=>"Archivo Eliminado con exito",
					"Texto"=>"Archivo Eliminado",
					"Form"=>"recarga_adj",
					"codH"=>$campos['codHistorialM']
				];
			}else{
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"No hemos podido elimimar el archivo",
					"Texto"=>"Fallo al eliminar"
				];
			}
			echo json_encode($alerta_simple);

		} //

		/* Mostrar archivos adjuntos de historia, recargar seccion de img y pdf
		*	@return: json_encode(): array con los segmentos html de archivos adjuntos
		*/
		public function datos_perfil_adjuntos_controlador(){
			$codigo_historial=mainModel::limpiar_cadena($_POST['adj_codhistorial']);
			if(session_start(['name'=>'VETP'])){
				if(isset($_SESSION['privilegio_vetp']) == false || isset($_SESSION['nombre_vetp']) == false){
					$adjuntos_files=[
						"Alerta"=>"warning",
						"Titulo"=>"Fallo a iniciar session variable session",
						"Texto"=>"Fallo session"
					];
					echo json_encode($adjuntos_files);
					exit();	
				}else{
					$privilegio=mainModel::limpiar_cadena($_SESSION['privilegio_vetp']);
				}
			}else{
				$adjuntos_files=[
					"Alerta"=>"warning",
					"Titulo"=>"Fallo a iniciar session",
					"Texto"=>"Fallo session"
				];
				echo json_encode($adjuntos_files);
				exit();
			}
			// files, imagenes, PDF
			$lista_files_img="";
			$lista_files_pdf="";
			// todos los archivos adjuntos de una sola historia
			$lista_files=historialModelo::datos_perfil_mascota_adjuntos_modelo($codigo_historial);
			if($lista_files->rowCount()>=1){
				$campos_files=$lista_files->fetchAll();

				foreach ($campos_files as $rowsf) {
					$file_type=$rowsf['adjTipo'];
					if($file_type=="png" || $file_type=="jpg" || $file_type=="jpeg"){
						$lista_files_img.='
						<div class="m-2 align-items-center">
		                    <!-- FORM ELIMINAR IMAGEN -->
		                    <form action="'.SERVERURL.'" method="POST" class="align-items-center justify-content-center" data-form="" >
		                      ';
		                      if($privilegio==1){
		                      	$lista_files_img.='
			                      
			                      <button id="btn_del_adjuntos" value="'.mainModel::encryption($rowsf['idAdjunto']).'" class="btn btn-eliminar-img">
			                          <!-- <i class="fas fa-trash-alt up"></i> -->
			                      </button>';
		                      }
		                      
		                      $lista_files_img.='
		                      <a href="'.SERVERURL.$rowsf['adjFileName'].'" title="'.$rowsf['adjTitulo'].'" class="insta-img">
		                        <img class="img" src="'.SERVERURL.$rowsf['adjFileName'].'" alt="'.$rowsf['adjTitulo'].'">
		                      </a>
		                    </form>
		                    <!-- X- FORM ELIMINAR IMAGEN -x-->
		                  </div>
					';
					
					}else if($file_type=="pdf"){
						$lista_files_pdf.='
						<div class="m-2">
		                    <!-- FORM ELIMINAR PDF -->
		                    <form action="'.SERVERURL.'ajax/archivoAjax.php" method="POST" class="FormularioAjax" data-form="delete">
		                      <!-- CODIGO id pdf -->';
		                     if ($privilegio==1) {
		                      	$lista_files_pdf.='
			                      
			                      <!-- btn eliminar efect hover -->
			                      <button id="btn_del_adjuntos" value="'.mainModel::encryption($rowsf['idAdjunto']).'" class="btn btn-eliminar-img">
			                      </button>';
		                      }

		                      $lista_files_pdf.='
		                      <!-- imagen mostrar -->
		                      <a href="'.SERVERURL.$rowsf['adjFileName'].'" target="pdf-frame" class="text-gray-600">
		                        <i class="fas fa-file-pdf"></i>
		                        <br><span>'.$rowsf['adjTitulo'].'</span>
		                      </a>
		                      <!-- x- imagen mostrar -x -->
		                    </form>
		                    <!-- X-X eliminar PDF -->
		                </div>
						';
					}
					
				}
			}else{
				$lista_files_img='<div class="m-2 align-items-center"><p>Sin imagenes</p></div>';
				$lista_files_pdf='<div class="m-2"><p>Sin Archivos PDF</p></div>';
			}

			$adjuntos_files=[
				"Images"=>$lista_files_img,
				"PDF"=>$lista_files_pdf
			];
			echo json_encode($adjuntos_files);
		} // fin datos_perfil_adjuntos_controlador


	}