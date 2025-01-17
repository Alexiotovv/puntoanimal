<?php 

 	if ($peticionAjax) {
	  require_once "../modelos/mascotaModelo.php";
	} else {
	  require_once "./modelos/mascotaModelo.php";
	}


	/**
	 * hereda mascotaModelo, y este de mainModel
	 */
	class mascotaControlador extends mascotaModelo{


		/* Agregar mascota: limpiar entradas, validar, enviar a modelo
		*  @return: json_encode(array): alerta con respuesta de servidor y validaciones
		*/
		public function agregar_mascota_controlador(){

			$nombre=mainModel::limpiar_cadena($_POST['mascota_nombre_reg']);
			$fecha=mainModel::limpiar_cadena($_POST['mascota_fecha_reg']);
			$peso=mainModel::limpiar_cadena($_POST['mascota_peso_reg']);
			$color=mainModel::limpiar_cadena($_POST['mascota_color_reg']);
			
			// opcional infoadicional
			$adicional=mainModel::limpiar_cadena($_POST['mascota_infadicional_reg']);
			// foto file
			$foto=$_FILES['archivo_foto_subir'];

			if(isset($_POST['mascota_sexo_reg'])){
				$sexo=mainModel::limpiar_cadena($_POST['mascota_sexo_reg']);
				if($sexo!="Hembra" && $sexo!="Macho"){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"Sexo de mascota no valido",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Seleccionar sexo de la mascota",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(isset($_POST['mascota_castrado_reg'])){
				$castrado=mainModel::limpiar_cadena($_POST['mascota_castrado_reg']);
				if($castrado!="Si" && $castrado!="No"){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"Castrado de mascota no valido",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Seleccionar castrado de la mascota",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}


			if(isset($_POST['mascota_especie_reg']) && isset($_POST['mascota_raza_reg'])){
				$idespecie=mainModel::decryption($_POST['mascota_especie_reg']);
				$idespecie=mainModel::limpiar_cadena($idespecie);
				$idraza=mainModel::limpiar_cadena($_POST['mascota_raza_reg']);
				if($idespecie=="" || $idraza==""){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"ESPECIE Y RAZA no valido",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Debe seleccionar una Especie y Raza",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(isset($_POST['mascota-dueno'])){
				$dnidueno=mainModel::limpiar_cadena($_POST['mascota-dueno']);
				if($dnidueno==""){
						$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"DNI del dueño no valido",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Debe seleccionar un dueño",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			
			/*----Campos vacios---------*/
			if($nombre=="" || $fecha=="" ){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>" No has llenado todos los campos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			
		
			$fecha = date("Y-m-d", strtotime($fecha));


			// yyyy-mm-dd en mi vista
			if(mainModel::verificar_fecha($fecha)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"LA FECHA no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/*---X--- validar entrada de datos ----X-----*/

			/*----- Comprobar DNI dueño en DB  ----- */
			$check_dni=mainModel::ejecutar_consulta_simple("SELECT clienteDniCedula FROM cliente WHERE clienteDniCedula='$dnidueno'");
			if($check_dni->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El dueño seleccionado no se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/*----- Comprobar Especie seleccionada en DB  ----- */
			$check_esp=mainModel::ejecutar_consulta_simple("SELECT idEspecie FROM especie WHERE idEspecie='$idespecie'");
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
			/*----- Comprobar Raza seleccionada en DB  ----- */
			$check_raza=mainModel::ejecutar_consulta_simple("SELECT idRaza,idEspecie FROM raza WHERE idRaza='$idraza'");

			if($check_raza->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La raza seleccionada no se encuentra registrada en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}else{
				$campos_raza=$check_raza->fetch();
			}
			/*-- Comprobar que raza pertenesca a la especie seleccionada --*/
			// buscar idEspecie de raza seleccionada y especie en DB
			if($campos_raza['idEspecie']!=$idespecie){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La raza seleccionada no pertenece a la especie",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			


			if(isset($_POST['avatar-mascota'])){ 
				$avatar=$_POST['avatar-mascota'];
				if($avatar==""){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Error al seleccionar Avatar",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();	
				}	
			}else{
				$avatar="";
			}

			if($avatar!="" && $foto['tmp_name']!=""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Avatar y foto seleccionado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
		
			// ------- mantener ruta actual  -----------
			if($avatar=="" && $foto['tmp_name']==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Debe seleccionar al menos un AVATAR O FOTO",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			// ---AVATAR seleccionado--->
			if ($avatar!="") {  
				if(file_exists("../vistas/images/avatar_mascota/".$avatar)){
					$ruta_foto_db = "vistas/images/avatar_mascota/".$avatar;

				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"AVATAR seleccionado no existe",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			// ----FOTO seleccionado ---->
			if($foto['tmp_name']!=""){

				if(mainModel::verificar_foto($foto)){
					/*--- guardar file foto --*/
					$file_name = $dnidueno.date('_d_m_Y_His_').str_replace(" ", "", basename($foto["name"]));
					$destino_url = "adjuntos/mascotas-foto/".$file_name;
					if(mainModel::guardar_foto($destino_url,$foto)){
						// ruta para base datos
						$ruta_foto_db=$destino_url;	
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"FALLO AL GUARDAR FOTO",
							"Tipo"=>"error"
						];
						echo json_encode($alerta);
						exit();
					}
						
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"LA FOTO no coincide con el formato solicitado JPG,PNG,JPEG",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}
			
			// ----- CODIGO MASCOTA----------
			$consulta=mainModel::ejecutar_consulta_simple("SELECT idmascota FROM mascota ");

            $numero=($consulta->rowCount())+1;

            $codmascota=mainModel::generar_codigo_aleatorio("CM",5,$numero);
			// ---x-- CODIGO MASCOTA--x--------

			/*---PREPARAR CARGAR---*/
			$datos = [
				"Codmascota" => $codmascota,
				"Nombre" => $nombre,
				"FechaN" => $fecha,
				"Peso" => $peso,
				"Color" => $color,
				"Especie" => $idespecie,
				"Raza" => $idraza,
				"FotoUrl" => $ruta_foto_db,
				"Sexo" => $sexo,
				"Castrado" => $castrado,
				"Adicional" => $adicional,
				"Dueno" => $dnidueno
			];

			// instancia a modelo
			$guardar_mascota=mascotaModelo::agregar_mascota_modelo($datos);
			
			if($guardar_mascota->rowCount()==1){
				if(isset($_POST['perfil_id_dni'])){
					$alerta=[
						"Alerta"=>"redireccionar",
						"URL"=>SERVERURL."perfilCliente/".mainModel::encryption($_POST['perfil_id_dni'])
					];	
				}else{
					$alerta=[
						"Alerta"=>"limpiar",
						"Titulo"=>"Mascota registrada",
						"Texto"=>"Los datos fueron registrados",
						"Tipo"=>"success",
						"User"=>"mascota",
						"clearFoto"=>SERVERURL
					];	
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar la mascota",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
				

		} //function agregar_mascota_controlador

		/* paginador Mascota
		* @param: $pagina: pagina actual,$registros: registros a mostrar,$privilegio: acultar algunas acciones,$url:la vista para botones $busqueda: lista mascota o mascota buscar
		*/
		public function paginador_mascota_controlador($pagina,$registros,$privilegio,$url,$busqueda){
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
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM mascota inner join cliente u on mascota.dniDueno = u.clienteDniCedula WHERE ((codMascota LIKE '%$busqueda%' OR mascotaNombre
				LIKE '%$busqueda%' OR mascotaFechaN LIKE '%$busqueda%' OR dniDueno LIKE '%$busqueda%' OR u.clienteNombre LIKE '%$busqueda%' )) ORDER BY idmascota DESC LIMIT $inicio,$registros";
			
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM mascota ORDER BY idmascota DESC LIMIT $inicio,$registros";
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
			                      <th>Codigo</th>
			                      <th>Mascota</th>
			                      <th>Dueño</th>
			                      <th>Fecha N.</th>
			                      <th>Sexo</th>
								  <th>Castrado</th>
			                      <th>Peso</th>
			                      <th>Color</th>
			                      <th>Acciones</th>
			                  </tr>
			              </thead>
			              <tbody>';

					if($total>=1 && $pagina<=$Npaginas){
						$contador=$inicio+1;
						$reg_inicio=$inicio+1;
						
						foreach($datos as $rows){
								$mascotacod=$rows['codMascota'];
								
								$dueno_raza_esp=mainModel::ejecutar_consulta_simple("SELECT espNombre,razaNombre,t4.clienteNombre,t4.clienteApellido,t4.clienteFotoUrl FROM mascota AS t1 INNER JOIN especie AS t2 ON t1.idEspecie=t2.idEspecie INNER JOIN raza AS t3 ON t1.idRaza=t3.idRaza INNER JOIN cliente AS t4 ON t1.dniDueno=t4.clienteDniCedula WHERE t1.codMascota='$mascotacod' ");
								$campos=$dueno_raza_esp->fetch();

							$tabla.='
				                 <tr>
			                      <td>'.$contador.'</td>
			                      <td>'.$rows['codMascota'].'</td>
			                      <td class="d-flex flex-row">
			                        <img src="'.SERVERURL.$rows['mascotaFoto'].'" alt="mascotaFoto" class="thumb-sm rounded-circle mr-2">
			                        <div class="d-flex flex-column">
			                          <span>'.$rows['mascotaNombre'].'</span>
			                          <small>'.$campos['espNombre'].' - '.$campos['razaNombre'].'</small>
			                        </div>
			                      </td>
			                      <td>
			                        <img src="'.SERVERURL.$campos['clienteFotoUrl'].'" alt="" class="thumb-sm rounded-circle mr-2">
			                        '.$campos['clienteNombre'].' '.$campos['clienteApellido'].' '.$rows['dniDueno'].'
			                      </td>
			                      <td>'.date("d-m-Y", strtotime($rows['mascotaFechaN'])).'</td>
			                      <td>'.$rows['mascotaSexo'].'</td>
								  <td>'.$rows['castrado'].'</td>
			                      <td>'.$rows['mascotaPeso'].' Kg</td>
			                      <td>'.$rows['mascotaColor'].'</td>
			                      <td>
			                        <div class="dropdown no-arrow" style="display:flex; padding:2px;">
										
			                         
										
										<a class="dropdown-toogle" style=" padding:10px;" href="'.SERVERURL.'perfilMascota/'.mainModel::encryption($rows['codMascota']).'/" title="VER PERFIL">
										<i class="fa-solid fa-dog fa-shake"  style="color: blue;"></i>
										</a>
										<a class="dropdown-toggle" style=" padding:10px;" href="'.SERVERURL.'addHistorialM/'.mainModel::encryption($rows['codMascota']).'/" title="NUEVA HISTORIA CLINICA">
											<i class="fa-solid fa-notes-medical" style="color: #be2d2d;"></i>
										</a>

										<a class="dropdown-toggle" style=" padding:10px;" href="'.SERVERURL.'addCitaM/'.mainModel::encryption($rows['codMascota']).'/" title="NUEVA CITA">
										<i class="fa-regular fa-calendar-check" style="color: #9668e5;"></i>
										</a>
										<a class="dropdown-toggle" style=" padding:10px;" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fa fa-cog" style="color: #9668e5;"></i>
										</a>
			                          <div class="dropdown-menu shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
			                          	';
			                          	// ---- EDITAR ACTUALIZAR
			                          	if($privilegio<=2){
			                          		$tabla.='
			                          			<a class="dropdown-item" href="'.SERVERURL.'editMascota/'.mainModel::encryption($rows['codMascota']).'/"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>Editar</a>	
			                          		';
			                          	}
			                          	// -- ELIMINAR ---- FORM
			                          	if($privilegio==1){
			                          		$tabla.='
			                          			<!-- FORM ELIMINAR -->
					                            <form class="FormularioAjax" action="'.SERVERURL.'ajax/mascotaAjax.php" method="POST" data-form="delete">
					                              	<input type="hidden" name="mascota_cod_del" value="'.mainModel::encryption($rows['codMascota']).'">
					                              	
					                              	<input type="hidden" name="privilegio_user" value="'.mainModel::encryption($privilegio).'">
					                              	
					                              	<button type="submit" class="dropdown-item"><i class="fas fa-trash-alt fa-sm fa-fw mr-2 text-gray-400"></i>Eliminar</button>
					                              </form>
					                            <!-- x ELIMINAR -->		
			                          		';
			                          	}
			                          	// --- PERFIL MASCOTA ------>
			                          	if($privilegio<=3){
			                          		$tabla.='
			                          			<a class="dropdown-item" href="'.SERVERURL.'perfilMascota/'.mainModel::encryption($rows['codMascota']).'/"><i class="fas fa-paw fa-sm fa-fw mr-2 text-gray-400"></i>Ver perfil</a>
			                          		';
			                          	}
			                          	
			                          	// CITAS Y HISTORIAL registro ------->
			                          	if($privilegio<=3){
			                          		$tabla.='
			                          			<div class="dropdown-divider"></div>
					                            <a class="dropdown-item" href="'.SERVERURL.'addCitaM/'.mainModel::encryption($rows['codMascota']).'/">Nueva Cita</a>
					                            <a class="dropdown-item" href="'.SERVERURL.'addHistorialM/'.mainModel::encryption($rows['codMascota']).'/">Nueva Historia</a>
			                          		';	
			                          	}
			                          	
			                          	$tabla.='
			                          </div>
			                        </div>
			                      </td>
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
						$tabla.='<p class="text-right">Mostrando mascota '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
						$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,5);
					}

					return $tabla;	                

		} //fin controlador paginador

		/* Eliminar Mascota, comprobar tablas foraneas
		*  @return: json_encode(array): alerta con respuesta de servidor 
		*/
		public function eliminar_mascota_controlador(){
			$codigo=mainModel::decryption($_POST['mascota_cod_del']);
			$codigo=mainModel::limpiar_cadena($codigo);
			$privilegio=mainModel::decryption($_POST['privilegio_user']);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			
			// ------- comprobar mascota en DB ----->
			$check_mascota = mainModel::ejecutar_consulta_simple("SELECT codMascota FROM mascota WHERE codMascota='$codigo'");
			if($check_mascota->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La mascota no existe en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			
			// comprobar con clave foranea tablas citas
			$check_citas = mainModel::ejecutar_consulta_simple("SELECT codMascota FROM citas WHERE codMascota='$codigo' ");
			if($check_citas->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No podemos eliminar esta mascota debido a que tiene Cita/s asociadas",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			// comprobar con clave foranea tablas historia
			$check_historial = mainModel::ejecutar_consulta_simple("SELECT codMascota FROM historialmascota WHERE codMascota='$codigo' ");
			if($check_historial->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No podemos eliminar esta mascota debido a que tiene Historias/s asociadas",
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
			// ELIMINAR DE CARPETA FOTO ADJUNTO ,BUSCAR RUTA URL ----
			$check_foto=mainModel::ejecutar_consulta_simple("SELECT mascotaFoto FROM mascota WHERE codMascota='$codigo' ");
			$num_foto=$check_foto->rowCount();
			if($num_foto>0){
				$campos=$check_foto->fetch();
				$campo_foto=$campos['mascotaFoto'];
			}
			
			$eliminar_mascota=mascotaModelo::eliminar_mascota_modelo($codigo);
			if($eliminar_mascota->rowCount()==1){
				if($num_foto>0){
					// Eliminar de carpeta foto ----
		
					unlink("../".$campo_foto);
				
					
				}
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Mascota eliminada",
					"Texto"=>"La mascota a sido eliminado del sistema",
					"Tipo"=>"success"
				];

			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar la mascota, intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);

		} // fin controlador eliminar

		/*	Buscar datos de mascota
		* @param: $tipo: unico(datos un solo mascota) o conteo(total de mascotas), $cod: codigo de mascota(clave unica)
		*/
		public static function datos_mascota_controlador($tipo,$cod){
			$tipo=mainModel::limpiar_cadena($tipo);
			$cod=mainModel::decryption($cod);
			$cod=mainModel::limpiar_cadena($cod);
	
			return mascotaModelo::datos_mascota_modelo($tipo,$cod);
		} // fin datos_mascota_controlador

		/* Editar mascota, validar campos, guardar foto file url.
		* @return: json_encode(array): alerta con respuesta de servidor y validaciones
		*/
		public function actualizar_mascota_controlador(){
			$id=mainModel::decryption($_POST['mascota_codigo_up']);
			$id=mainModel::limpiar_cadena($id);

			// camprobar cod de mascota en db
			$check_masc=mainModel::ejecutar_consulta_simple("SELECT * FROM mascota WHERE codMascota='$id'");

			if($check_masc->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La mascota no se encuentra registrado en el sistema, intente nuevamente",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}else{
				$campos=$check_masc->fetch();
			}

			$nombre=mainModel::limpiar_cadena($_POST['mascota_nombre_edit']);
			$fecha=mainModel::limpiar_cadena($_POST['mascota_fecha_edit']);
			$peso=mainModel::limpiar_cadena($_POST['mascota_peso_edit']);
			$color=mainModel::limpiar_cadena($_POST['mascota_color_edit']);

			$info=mainModel::limpiar_cadena($_POST['mascota_infadicional_edit']);
			$foto=$_FILES['archivo_foto_subir'];
			
			/*-------VALIDAR CAMPOS -----*/
			if(isset($_POST['mascota_sexo_edit'])){
				$sexo=mainModel::limpiar_cadena($_POST['mascota_sexo_edit']);
				if($sexo!="Hembra" && $sexo!="Macho"){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"Sexo de mascota no valido",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Seleccionar sexo de la mascota",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			
			/*-------VALIDAR CAMPOS CASTRADO-----*/
			if(isset($_POST['mascota_castrado_edit'])){
				$castrado=mainModel::limpiar_cadena($_POST['mascota_castrado_edit']);
				if($castrado!="Si" && $castrado!="No"){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"Castrado de mascota no valido",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Seleccionar castrado de la mascota",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(isset($_POST['mascota_especie_reg']) && isset($_POST['mascota_raza_reg'])){
				$idespecie=mainModel::decryption($_POST['mascota_especie_reg']);
				$idespecie=mainModel::limpiar_cadena($idespecie);
				$idraza=mainModel::limpiar_cadena($_POST['mascota_raza_reg']);
				if($idespecie=="" || $idraza==""){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"ESPECIE Y RAZA no valido",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Debe seleccionar una Especie y Raza",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(isset($_POST['mascota-dueno'])){
				$dnidueno=mainModel::limpiar_cadena($_POST['mascota-dueno']);
				if($dnidueno==""){
						$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"DNI del dueño no valido",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Debe seleccionar un dueño",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			
			/*----Campos vacios---------*/
			if($nombre=="" || $fecha=="" ){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>" No has llenado todos los campos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			
			$fecha = date("Y-m-d", strtotime($fecha));

			// yyyy-mm-dd en mi vista
			if(mainModel::verificar_fecha($fecha)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"LA FECHA no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/*---X--- validar entrada de datos ----X-----*/

			/*----- Comprobar DNI dueño en DB  ----- */
			$check_dni=mainModel::ejecutar_consulta_simple("SELECT clienteDniCedula FROM cliente WHERE clienteDniCedula='$dnidueno'");
			if($check_dni->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El dueño seleccionado no se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/*----- Comprobar Especie seleccionada en DB  ----- */
			$check_esp=mainModel::ejecutar_consulta_simple("SELECT idEspecie FROM especie WHERE idEspecie='$idespecie'");
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
			/*----- Comprobar Raza seleccionada en DB  ----- */
			$check_raza=mainModel::ejecutar_consulta_simple("SELECT idRaza,idEspecie FROM raza WHERE idRaza='$idraza'");

			if($check_raza->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La raza seleccionada no se encuentra registrada en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}else{
				$campos_raza=$check_raza->fetch();
			}
			/*-- Comprobar que raza pertenesca a la especie seleccionada --*/
			// buscar idEspecie de raza seleccionada y especie en DB
			if($campos_raza['idEspecie']!=$idespecie){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La raza seleccionada no pertenece a la especie",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			// ------ radio avatares -------------
			if(isset($_POST['avatar-mascota'])){
				$avatar=$_POST['avatar-mascota'];
				if($avatar==""){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Error al seleccionar Avatar",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();	
				}	
			}else{
				$avatar="";
			}
			
			if($avatar!="" && $foto['tmp_name']!=""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Avatar y foto seleccionado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			// ------- mantener ruta actual  -----------
			if($avatar=="" && $foto['tmp_name']==""){
				$ruta_foto_db=$campos['mascotaFoto'];
			}

			// ---AVATAR seleccionado--->
			if ($avatar!="") {
				if(file_exists("../vistas/images/avatar_mascota/".$avatar)){
					$ruta_foto_db = "vistas/images/avatar_mascota/".$avatar;
					// eliminar si anterior foto es un adjunto
					$foto_del = explode("/", $campos['mascotaFoto']);
					if($foto_del[0]=="adjuntos"){
						unlink("../".$campos['mascotaFoto']);
			        }

				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"AVATAR seleccionado no existe",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			// ----FOTO seleccionado ---->
			if($foto['tmp_name']!=""){
				if(mainModel::verificar_foto($foto)){
					/*--- guardar file foto --*/
					$file_name = $dnidueno.date('_d_m_Y_His_').str_replace(" ", "", basename($foto["name"]));
					$destino_url = "adjuntos/mascotas-foto/".$file_name;
					if(mainModel::guardar_foto($destino_url,$foto)){
						// ruta para base datos
						$ruta_foto_db=$destino_url;
						// borrar foto actual
						unlink("../".$campos['mascotaFoto']);

					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"FALLO AL GUARDAR FOTO",
							"Tipo"=>"error"
						];
						echo json_encode($alerta);
						exit();
					}
						
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"LA FOTO no coincide con el formato solicitado JPG,PNG,JPEG",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}
			
			/*--- PREPARAR CARGA --- */
			$datos_mas = [
				"Codmascota" => $id,
				"Nombre" => $nombre,
				"FechaN" => $fecha,
				"Peso" => $peso,
				"Color" => $color,
				"Especie" => $idespecie,
				"Raza" => $idraza,
				"FotoUrl" => $ruta_foto_db,
				"Sexo" => $sexo,
				"Castrado" => $castrado,
				"Adicional" => $info,
				"Dueno" => $dnidueno
			];
			
			// instancia A Modelo ->
			if(mascotaModelo::actualizar_mascota_modelo($datos_mas)){
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


		} // fin actualizar_mascota_controlador


	}
