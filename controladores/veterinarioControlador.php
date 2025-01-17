<?php 
 
 	if ($peticionAjax) {
	  require_once "../modelos/veterinarioModelo.php";
	} else {
	  require_once "./modelos/veterinarioModelo.php";
	}

	/**
	 * hereda de veterinarioModelo, y veterinarioModelo hereda de mainModel
	 */
	class veterinarioControlador extends veterinarioModelo{

 
		/* Agregar veterinario: limpiar entradas, validar, enviar a modelo
		*  @return: json_encode: alerta con respuesta de validacion, servidor
		*/
		public function agregar_veterinario_controlador(){

			$dni=mainModel::limpiar_cadena($_POST['veterinario_dni_reg']);
			$nombre=mainModel::limpiar_cadena($_POST['veterinario_nombre_reg']);
			$apellido=mainModel::limpiar_cadena($_POST['veterinario_apellido_reg']);
			$telefono=mainModel::limpiar_cadena($_POST['veterinario_telefono_reg']);
			$especialidad=mainModel::limpiar_cadena($_POST['veterinario_especialidad_reg']);
			$genero=mainModel::limpiar_cadena($_POST['veterinario_genero_reg']);
			$direccion=mainModel::limpiar_cadena($_POST['veterinario_direccion_reg']);
			
			$foto=$_FILES['archivo_foto_subir'];
			
			// radio avatares
			if(isset($_POST['avatar-veterinario'])){
				$avatar=$_POST['avatar-veterinario'];
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

			// ------- validar foto y avatar  -----------
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
				if(file_exists("../vistas/images/avatar_user_cli/".$avatar)){
					$ruta_foto_db = "vistas/images/avatar_user_cli/".$avatar;

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
					$file_name = $dni.date('_d_m_Y_His').str_replace(" ", "", basename($foto["name"]));
					
					$destino_url = "adjuntos/veterinario-foto/".$file_name;
					// guardar foto en carpeta
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

				
			// ----X--- validar foto y avatar  ------X-----

			/*----Campos vacios---------*/
			if($dni=="" || $nombre=="" || $apellido=="" || $telefono==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			
			/*--X--Campos vacios----X-----*/

			/*------ validar entrada de datos ---------*/
			if(mainModel::verificar_datos("[0-9-]{7,20}",$dni)){
				// true: con error
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El DNI no coincide con el formato solicitado minimo 7 números",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El NOMBRE no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$apellido)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El APELLIDO no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if($telefono!=""){
				if(mainModel::verificar_datos("[0-9()+]{8,20}",$telefono)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El TELEFONO no coincide con el formato solicitado minimo 8 números",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			if($direccion!=""){
				if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"La DIRECCION no coincide con el formato solicitado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}
			
			/*----- Comprobar DNI si existe en DB  ----- */
			$check_dni=mainModel::ejecutar_consulta_simple("SELECT vetDni FROM veterinario WHERE vetDni='$dni'");
			if($check_dni->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El DNI ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			
			/*----- Comprobar EMAIL(campo opcional) si existe en DB ----- */
			// if($email!=""){
			// 	if(filter_var($email,FILTER_VALIDATE_EMAIL)){
			// 		// sin error,
			// 		$check_email=mainModel::ejecutar_consulta_simple("SELECT clienteCorreo FROM cliente WHERE clienteCorreo='$email'");
			// 		if($check_email->rowCount()>0){
			// 			$alerta=[
			// 				"Alerta"=>"simple",
			// 				"Titulo"=>"Ocurrió un error inesperado",
			// 				"Texto"=>"El EMAIL ya se encuentra registrado en el sistema",
			// 				"Tipo"=>"error"
			// 			];
			// 			echo json_encode($alerta);
			// 			exit();
			// 		}
			// 	}else{
			// 		$alerta=[
			// 			"Alerta"=>"simple",
			// 			"Titulo"=>"Ocurrió un error inesperado",
			// 			"Texto"=>"Ha ingresado un correo no valido",
			// 			"Tipo"=>"error"
			// 		];
			// 		echo json_encode($alerta);
			// 		exit();
			// 	}
			// }

			/*---X--- validar entrada de datos ----X-----*/

			/*---PREPARAR CARGAR---*/
			$datos = [
				"DniCedula" => $dni,
				"Nombre" => $nombre,
				"Apellido" => $apellido,
				"Genero" => $genero,
				"Telefono" => $telefono,
				"Especialidad" => $especialidad,
				"Domicilio" => $direccion,
				"FotoUrl" => $ruta_foto_db
			];

			// instancia a modelo
			$guardar_veterinario=veterinarioModelo::agregar_veterinario_modelo($datos);
			
			if($guardar_veterinario->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Veterinario registrado",
					"Texto"=>"Los datos fueron registrados",
					"Tipo"=>"success",
					"User"=>"veterinario",
					"clearFoto"=>SERVERURL
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar el veterinario",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		

		} //function fin agregar_veterinario_controlador
		
		/* paginador veterinario
		* @param: $pagina: pagina actual,$registros: registros a mostrar,$privilegio: acultar algunas acciones,$url:la vista para botones $busqueda: lista veterinario o veterinario buscar
		*/
		public function paginador_veterinario_controlador($pagina,$registros,$privilegio,$url,$busqueda){
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
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM veterinario WHERE ((vetDni LIKE '%$busqueda%' OR vetNombre LIKE '%$busqueda%' OR vetApellido LIKE '%$busqueda%' OR vetTelefono LIKE '%$busqueda%' )) ORDER BY idVeterinario DESC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM veterinario ORDER BY idVeterinario DESC LIMIT $inicio,$registros";
			}

			$conexion = mainModel::conectar();
			$datos = $conexion->query($consulta);
			$datos = $datos->fetchAll();

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas=ceil($total/$registros);

			$tabla.='<div class="table-responsive mb-4">
						<table class="table table-hover mb-0">
		                <thead>
		                    <tr class="align-self-center">
		                        <th>#</th>
								<th>Veterinario</th>
		                        <th>NIT/CI</th>
		                        <th>Télefono</th>
		                        <th>Especialidad</th>
		                        <th>Domicilio</th>
		                        <th>Acciones</th>
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
			                        <td>
			                          <img src="'.SERVERURL.$rows['vetFotoUrl'].'" alt="veterinarioFoto" class="thumb-sm rounded-circle mr-2">'.$rows['vetNombre'].' '.$rows['vetApellido'].'</td>
			                        <td>'.$rows['vetDni'].'</td>
			                        <td>'.$rows['vetTelefono'].'</td>
			                        <td>'.$rows['vetEspecialidad'].'</td>
			                        <td>'.$rows['vetDomicilio'].'</td>
			                        <td>
			                          <div class="dropdown no-arrow">
			                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                              <i class="fa fa-cog" style="color:#9668e5;"></i>
			                            </a>
			                            <div class="dropdown-menu shadow animated--fade-in" aria-labelledby="dropdownMenuLink">';
			                              //  ---- EDITAR --- //////
			                              if($privilegio<=2){
			                              	$tabla.='<a class="dropdown-item" href="'.SERVERURL.'editVeterinario/'.mainModel::encryption($rows['vetDni']).'/"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>Actualizar</a>';
			                              }
			                              // ////// ELIMINAR ///////
                							if($privilegio==1){
                								$tabla.='
				                              <form class="FormularioAjax" action="'.SERVERURL.'ajax/veterinarioAjax.php" method="POST" data-form="delete">
				                              	<input type="hidden" name="veterinario_dni_del" value="'.mainModel::encryption($rows['vetDni']).'">
				                              	
				                              	<input type="hidden" name="privilegio_user" value="'.mainModel::encryption($privilegio).'">
				                              	
				                              	<button type="submit" class="dropdown-item"><i class="fas fa-trash-alt fa-sm fa-fw mr-2 text-gray-400"></i>Eliminar</button>
				                              </form>';
                							}
			                              
			                              if($privilegio<=3){
			                              	$tabla.='
			                              <a class="dropdown-item" href="'.SERVERURL.'perfilVeterinario/'.mainModel::encryption($rows['vetDni']).'/"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>Ver perfil</a>
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
						$tabla.='<p class="text-right">Mostrando veterinario '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
						$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,5);
					}

					return $tabla;	                

		} //fin controlador paginador
		
		/* Elimimar veterinario 
		*	@return: json_encode(array): alerta con respuesta de servidor y validaciones
		*/
		public function eliminar_veterinario_controlador(){
			$dni=mainModel::decryption($_POST['veterinario_dni_del']);
			$dni=mainModel::limpiar_cadena($dni);
			$privilegio=mainModel::decryption($_POST['privilegio_user']);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			
			// ------- comprobar veterinario en DB ----->
			$check_veterinario = mainModel::ejecutar_consulta_simple("SELECT vetDni FROM veterinario WHERE vetDni='$dni'");
			if($check_veterinario->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El veterinario no existe en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			// comprobar con clave foranea tablas mascota
			$check_mascotas = mainModel::ejecutar_consulta_simple("SELECT dniDueno FROM mascota WHERE dniDueno='$dni' ");
			if($check_mascotas->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No podemos eliminar este veterinario debido a que tiene mascota/s asociadas",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			// comprobar con clave foranea tablas citas
			$check_historialmascota = mainModel::ejecutar_consulta_simple("SELECT vetDni FROM historialmascota WHERE vetDni='$dni' ");
			if($check_historialmascota->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No podemos eliminar este veterinario debido a que tiene Cita/s asociadas",
					"Tipo"=>"error" 
				];
				echo json_encode($alerta);
				exit();
			}
			// comprobar con clave foranea tablas ventas
			// $check_venta = mainModel::ejecutar_consulta_simple("SELECT dniVeterinario FROM venta WHERE dniVeterinario='$dni' ");
			// if($check_venta->rowCount()>0){
			// 	$alerta=[
			// 		"Alerta"=>"simple",
			// 		"Titulo"=>"Ocurrió un error inesperado",
			// 		"Texto"=>"No podemos eliminar este veterinario debido a que tiene Venta/s asociadas",
			// 		"Tipo"=>"error"
			// 	];
			// 	echo json_encode($alerta);
			// 	exit();
			// }

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

			// ELIMINAR DE CARPETA FOTO ADJUNTO-----
			$check_foto=mainModel::ejecutar_consulta_simple("SELECT vetFotoUrl FROM veterinario WHERE vetDni='$dni' ");
			$num_foto=$check_foto->rowCount();
			if($num_foto>0){
				$campos = $check_foto->fetch();
				$campo_foto = $campos['vetFotoUrl'];
				$ruta_del = explode("/", $campo_foto);
			}

			// ----- Instancia a modelo ----->
			$eliminar_veterinario=veterinarioModelo::eliminar_veterinario_modelo($dni);
			if($eliminar_veterinario->rowCount()==1){
				// eliminar foto adjunto
				if($num_foto>0){
					if($ruta_del[0]=="adjuntos"){
						unlink("../".$campo_foto);
					}	
				}
				
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Veterinario eliminado",
					"Texto"=>"El veterinario a sido eliminado del sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar el veterinario, intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);

		} // fin controlador eliminar

		/*	Buscar datos de veterinario
		* @param: $tipo: unico(datos un solo veterinario) o conteo(total de veterinarios), $dni: cedula de veterinario(clave unica)
		*/
		public static function datos_veterinario_controlador($tipo,$dni){
			$tipo=mainModel::limpiar_cadena($tipo);
			$dni=mainModel::decryption($dni);
			$dni=mainModel::limpiar_cadena($dni);

			return veterinarioModelo::datos_veterinario_modelo($tipo,$dni);
		} // fin datos_veterinario_controlador


		/** Buscar veterinario para select , en evento keyup en input de select
		*   @return: segmento html <option> con veterinario/s
		*/
 		public function buscar_veterinario_controlador(){
			$valor=mainModel::limpiar_cadena($_POST['valorBuscar']);

			if($valor==""){
			    $query = 'SELECT * FROM veterinario ORDER BY idVeterinario DESC limit 4';
			}else{
				$query = 'SELECT * FROM veterinario WHERE vetDni LIKE "%'.$valor.'%" LIMIT 5';
			}

			$conexion = mainModel::conectar();
			$datos = $conexion->query($query);
			$datos = $datos->fetchAll();

			$veterinarios="";
			foreach($datos as $rows){ 
				$veterinarios.='
					<option value="'.$rows['vetDni'].'" data-subtext="'.$rows['vetDni'].'" data-foto="'.SERVERURL.$rows['vetFotoUrl'].'">'.$rows['vetNombre'].' '.$rows['vetApellido'].'</option>
				';
			}
			return $veterinarios;


		} // fin buscar_dueno controlador 
		

		/*	Buscar datos de veterinario perfil, total mascotas, facturado
		* @param: $tipo: mascotas,facturas, $dni: cedula de veterinario(clave unica)
		* @return: segmento html
		*/
/* 		public function datos_perfil_veterinario_controlador($tipo,$dni){
			$tipo=mainModel::limpiar_cadena($tipo);
			$dni=mainModel::decryption($dni);
			$dni=mainModel::limpiar_cadena($dni);
			if($tipo=="listaMascota"){
				$lista_mascota=veterinarioModelo::datos_perfil_veterinario_modelo($tipo,$dni);
				$mascotas_lista="";
				$classgenero="";
				if($lista_mascota->rowCount()>=1){
					$campos_m=$lista_mascota->fetchAll();
					
					foreach($campos_m as $rows){
						$cod_mascota=mainModel::encryption($rows["codMascota"]);
						$genero=$rows['mascotaSexo'];
						$edad=mainModel::calcular_edad($rows['mascotaFechaN']);
						if($genero=="Macho"){
							$classgenero="fa-mars";
						}elseif($genero=="Hembra"){
							$classgenero="fa-venus";
						} 
						$mascotas_lista.='
							<a href="#" serverurl="'.SERVERURL.'" id_masc="'.$cod_mascota.'" data-toggle="modal" data-target="#modalDetalleMascota" data-codmascota="CM-7654" class="lista-detalle list-group-item list-group-item-action align-items-center">
		                       <!-- sexo-mascota -->
		                       <div class="float-right">
		                          <i class="fas '.$classgenero.'"></i>
		                       </div>
		                        <div class="d-flex flex-row">
		                          <!-- foto perfil mascota -->
		                          <div class="img-parent">
		                          
		                            <img src="'.SERVERURL.$rows['mascotaFoto'].'" class="img-fluid rounded-circle" alt="foto">
		                          </div>
		                          <!-- nombre especie (calcular edad) -->
		                          <div class="d-flex flex-column ml-2">
		                              <small><b>'.$rows['mascotaNombre'].'</b></small>
		                              <small>'.$rows['razaNombre'].' - '.$edad.'</small>
		                          </div>
		                        </div>
		                      </a>
						';
					}
				}else{

					$mascotas_lista.='
						<a href="'.SERVERURL."addMascota/".mainModel::encryption($dni).'" class="list-group-item list-group-item-action align-items-center text-center">
				             <small><b>Sin Mascotas</b></small>
				        </a>
					';
				}
				return $mascotas_lista;
			} // lista mascotas if

		} // fin datos_perfil_veterinario_controlador
 */
		/* Actualizar veterinario
		*	@return: json_encode(array): respuesta de servidor y validaciones
		*/
		public function actualizar_veterinario_controlador(){
			$id=mainModel::decryption($_POST['usuario_id_up']);
			$id=mainModel::limpiar_cadena($id);

			// camprobar dni de veterinario en db
			$check_veterinario=mainModel::ejecutar_consulta_simple("SELECT * FROM veterinario WHERE idVeterinario='$id'");
 
			if($check_veterinario->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El veterinario no se encuentra registrado en el sistema, intente nuevamente",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}else{
				$campos=$check_veterinario->fetch();
			}

			$dni=mainModel::limpiar_cadena($_POST['veterinario_dni_edit']);
			$nombre=mainModel::limpiar_cadena($_POST['veterinario_nombre_edit']);
			$apellido=mainModel::limpiar_cadena($_POST['veterinario_apellido_edit']);
			$telefono=mainModel::limpiar_cadena($_POST['veterinario_telefono_edit']);
			$especialidad=mainModel::limpiar_cadena($_POST['veterinario_especialidad_edit']);
			$direccion=mainModel::limpiar_cadena($_POST['veterinario_direccion_edit']);
			$foto=$_FILES['archivo_foto_subir'];

			/*-------VALIDAR CAMPOS -----*/
			if(isset($_POST['veterinario_genero_edit'])){
				$genero=mainModel::limpiar_cadena($_POST['veterinario_genero_edit']);
				if($genero!="Femenino" && $genero!="Masculino"){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"Genero de veterinario no valido",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Seleccionar genero del veterinario",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			// ------ radio avatares -------------
			if(isset($_POST['avatar-veterinario'])){
				$avatar=$_POST['avatar-veterinario'];
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
				$ruta_foto_db=$campos['vetFotoUrl'];
			}

			// ---AVATAR seleccionado--->
			if ($avatar!="") {
				if(file_exists("../vistas/images/avatar_user_cli/".$avatar)){
					$ruta_foto_db = "vistas/images/avatar_user_cli/".$avatar;
					// eliminar si anterior foto es un adjunto
					$foto_del = explode("/", $campos['vetFotoUrl']);
					if($foto_del[0]=="adjuntos"){
						unlink("../".$campos['vetFotoUrl']);
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
					$file_name = $dni.date('_d_m_Y_His').str_replace(" ", "", basename($foto["name"]));
					
					$destino_url = "adjuntos/veterinario-foto/".$file_name;
					// guardar foto en carpeta
					if(mainModel::guardar_foto($destino_url,$foto)){
						// ruta para base datos
						$ruta_foto_db=$destino_url;
						// si anterior foto es adjunto
						$foto_del = explode("/", $campos['vetFotoUrl']);
						if($foto_del[0]=="adjuntos"){
							unlink("../".$campos['vetFotoUrl']);
				        }
				        
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
			// ----X--- validar foto y avatar  ------X-----

			/*---- Campos vacios ---------*/
			if($dni=="" || $nombre=="" || $apellido=="" || $telefono==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/*--X--Campos vacios----X-----*/

			/*------ validar entrada de datos ---------*/
			if(mainModel::verificar_datos("[0-9-]{7,20}",$dni)){
				// true: con error
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El DNI no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El NOMBRE no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$apellido)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El APELLIDO no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if($telefono!=""){
				if(mainModel::verificar_datos("[0-9()+]{8,20}",$telefono)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El TELEFONO no coincide con el formato solicitado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			if($direccion!=""){
				if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"La DIRECCION no coincide con el formato solicitado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}
			
			/*----- Comprobar DNI si existe en DB  ----- */
			$dni_actual = $campos['vetDni'];
			if($dni_actual==$dni){
				$dni_db=$dni_actual;
			}else{
				$check_dni=mainModel::ejecutar_consulta_simple("SELECT vetDni FROM veterinario WHERE vetDni='$dni'");
				if($check_dni->rowCount()>0){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El DNI ya se encuentra registrado en el sistema",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}else{
					$dni_db=$dni;
				}	
			}
			
			
			/*----- Comprobar EMAIL(campo opcional) si existe en DB ----- */
	/* 		$email_actual=$campos['clienteCorreo'];
			if($email!=""){
				if($email_actual==$email){
					$email_db=$email_actual;
				}else{
					if(filter_var($email,FILTER_VALIDATE_EMAIL)){
						// sin error,
						$check_email=mainModel::ejecutar_consulta_simple("SELECT clienteCorreo FROM cliente WHERE clienteCorreo='$email'");
						if($check_email->rowCount()>0){
							$alerta=[
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrió un error inesperado",
								"Texto"=>"El EMAIL ya se encuentra registrado en el sistema",
								"Tipo"=>"error"
							];
							echo json_encode($alerta);
							exit();
						}else{
							$email_db=$email;
						}
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"Ha ingresado un correo no valido",
							"Tipo"=>"error"
						];
						echo json_encode($alerta);
						exit();
					}	
				}
				
			}else{
				$email_db=$email;
			} */

			/*---X--- validar entrada de datos ----X-----*/

			/*---PREPARAR CARGAR---*/
			$datos = [
				"DniCedula" => $dni_db,
				"Nombre" => $nombre,
				"Apellido" => $apellido,
				"Genero" => $genero,
				"Telefono" => $telefono,
				"Especialidad" => $especialidad,
				"Domicilio" => $direccion,
				"FotoUrl" => $ruta_foto_db,
				"ID" => $id
			];

			// instancia A Modelo ->
			if(veterinarioModelo::actualizar_veterinario_modelo($datos)){
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


		} // fin actualizar_veterinario_controlador

		/*	Buscar todo historial facturado mostrar en perfil
		* 	@return: json_encode(array): fragmento html de factura/s, total de factura
		*/
/* 		public function datos_perfil_cliente_facturas_controlador(){
			$limit=mainModel::limpiar_cadena($_POST['limit']);
			$offset=mainModel::limpiar_cadena($_POST['offset']);
			$cliente=mainModel::limpiar_cadena($_POST['dnicliente']);
			
			$busqueda=mainModel::limpiar_cadena($_POST['busquedafecha']);

			$factura_lista="";
			if(session_start(['name'=>'VETP'])){
				if(isset($_SESSION['privilegio_vetp']) == false){
					echo $factura_lista.='<div>Fallo al iniciar sesion</div>';
					exit();
				}else{
					$privilegio=mainModel::limpiar_cadena($_SESSION['privilegio_vetp']);
				}
			}else{
				echo $factura_lista.='<div>Fallo al iniciar sesion</div>';
				exit();
			}

			if(isset($busqueda) && $busqueda!=""){
		
				$sql="SELECT f.idVenta,f.ventMetodoPago,f.ventTotal,DATE_FORMAT(f.ventFecha, '%d-%m-%Y') as fecha, DATE_FORMAT(f.ventFecha,'%r') as  hora, e.empresaMoneda FROM venta f, empresa e WHERE ((f.dniCliente='$cliente') AND (DATE(f.ventFecha)='$busqueda')) ORDER BY f.idVenta DESC LIMIT $limit OFFSET $offset";
			}else{

				$sql = "SELECT f.idVenta,f.ventMetodoPago,f.ventTotal,DATE_FORMAT(f.ventFecha, '%d-%m-%Y') as fecha, DATE_FORMAT(f.ventFecha,'%r') as  hora, e.empresaMoneda FROM venta f, empresa e WHERE f.dniCliente='$cliente' ORDER BY f.idVenta DESC LIMIT $limit OFFSET $offset";	
			}

			$sql_total="SELECT idVenta FROM venta WHERE dnicliente='$cliente'";
			
			$conexion = mainModel::conectar();
			$datos = $conexion->query($sql);
			$datos = $datos->fetchAll();
			// total hist
			$total = $conexion->query($sql_total);
			$total = $total->rowCount();

			foreach($datos as $rows){
				$fecha_formt=mainModel::fecha_castellano($rows['fecha'],3);
				$total_formt = number_format($rows['ventTotal'],2,'.',',');
				$factura_lista.='
					<li class="shadow" data-nfactura="'.$rows['idVenta'].'">
		              <div class="row">
		                <div class="col-6 col-fecha">
		                  <span class="fecha">'.$fecha_formt.'</span><br>
		                  <span>'.$rows['hora'].'</span>
		                </div>
		                <div class="col-6">
		                  <div class="float-right">
		                    <a href="#" data-toggle="modal" data-target="#modalDetalleFactura" >
		                      <i class="fas fa-eye"></i>
		                    </a>
		                 </div>
		                 <div class="d-flex flex-column">
		                  <span>Pago N. '.mainModel::generar_numero_factura($rows['idVenta']).'</span>
		                  <small>Monto: '.$total_formt.' '.$rows['empresaMoneda'].'</small>
		                  <small>Metodo de pago: '.$rows['ventMetodoPago'].'</small>
		                 </div>
		                </div>
		              </div>
		            </li>';
			}

			$datos_his=[
				"ListaF"=>$factura_lista,
				"TotalF"=>$total
			];
			
			echo json_encode($datos_his);
			
		} */ // fin datos_perfil_cliente_controlador

		/* lista de los detalles de las facturas del cliente
		*  @return: segmento html <tr><th>, para tabla detalle
		*/
		/* public function lista_detalle_venta_perfilCliente(){
			$idVenta=mainModel::limpiar_cadena($_POST['id_Venta']);

			$query = "SELECT dv.*,p.prodserviNombre FROM detalleventa dv INNER JOIN productoservicio p ON dv.codProducto = p.codProdservi WHERE dv.codFactura=$idVenta";

			$conexion = mainModel::conectar();
			$datos = $conexion->query($query);
			$datos = $datos->fetchAll();

			// buscar empresa
			$iva_check = mainModel::ejecutar_consulta_simple("SELECT empresaMoneda FROM empresa");


			$htmldetalle="";
			if($iva_check->rowCount()>0){
	    		$info_empresa=$iva_check->fetch();
	    		$moneda=$info_empresa['empresaMoneda'];
	    	    $contador = 0;
	    	    $contador = $contador+1;
				foreach($datos as $rows){
				 	$precioTotal = round($rows['detalleCantidad'] * $rows['precio_venta'], 2);
			       $htmldetalle.='
						<tr>
		                      <td>'.$contador.'</td>
		                      <td>'.$rows['codProducto'].'</td>
		                      <td>'.$rows['prodserviNombre'].'</td>
		                      <td>'.number_format($rows['precio_venta'],2,'.',',')." ".$moneda.'</td>
		                      <td>'.$rows['detalleCantidad'].'</td>
		                      <td>'.number_format($precioTotal,2,'.',',')." ".$moneda.'</td>
		                  </tr>
					';

				 $contador++;
				}
			}
			return $htmldetalle;

		} */

	}
