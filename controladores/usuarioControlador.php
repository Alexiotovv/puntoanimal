<?php 
	
 	if ($peticionAjax) {
	  require_once "../modelos/usuarioModelo.php";
	} else {
	  require_once "./modelos/usuarioModelo.php";
	}


	/**
	 * hereda usuarioModelo, y este de mainModel
	 */
	class usuarioControlador extends usuarioModelo{

 
		/* Agregar usuario: limpiar entradas, validar, enviar a modelo
		*  @return: json_encode(array): alerta con respuesta de servidor y validaciones
		*/
		public function agregar_usuario_controlador(){

			$dni=mainModel::limpiar_cadena($_POST['usuario_dni_reg']);
			$nombre=mainModel::limpiar_cadena($_POST['usuario_nombre_reg']);
			$apellido=mainModel::limpiar_cadena($_POST['usuario_apellido_reg']);
			$telefono=mainModel::limpiar_cadena($_POST['usuario_telefono_reg']);
			$domicilio=mainModel::limpiar_cadena($_POST['usuario_direccion_reg']);

			$usuario=mainModel::limpiar_cadena($_POST['usuario_usuario_reg']);
			$email=mainModel::limpiar_cadena($_POST['usuario_email_reg']);
			// foto file
			$foto=$_FILES['usuario_foto_reg'];
			
			$clave1=mainModel::limpiar_cadena($_POST['usuario_clave_1_reg']);
			$clave2=mainModel::limpiar_cadena($_POST['usuario_clave_2_reg']);

			$privilegio=mainModel::limpiar_cadena($_POST['usuario_privilegio_reg']);
			
			/*----Campos vacios---------*/
			if($dni=="" || $nombre=="" || $apellido=="" || $usuario=="" || $clave1=="" || $clave2=="" ){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			if(empty($privilegio)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Privilegio sleccionado no es valido",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

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

			if($domicilio!=""){
				if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$domicilio)){
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


			if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El NOMBRE DE USUARIO no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave1) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave2)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Las CLAVES no coinciden con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*---X--- validar entrada de datos ----X-----*/
			
			/*----- Comprobar DNI si existe en DB ----- */
			$check_dni=mainModel::ejecutar_consulta_simple("SELECT userDni FROM usuarios WHERE userDni='$dni'");
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
			/*----- Comprobar USUARIO si existe en DB ----- */
			$check_user=mainModel::ejecutar_consulta_simple("SELECT userUsuario FROM usuarios WHERE userUsuario='$usuario'");
			if($check_user->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El USUARIO ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/*----- Comprobar EMAIL(campo opcional) si existe en DB ----- */
			if($email!=""){
				if(filter_var($email,FILTER_VALIDATE_EMAIL)){
					// sin error,
					$check_email=mainModel::ejecutar_consulta_simple("SELECT userEmail FROM usuarios WHERE userEmail='$email'");
					if($check_email->rowCount()>0){
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"El EMAIL ya se encuentra registrado en el sistema",
							"Tipo"=>"error"
						];
						echo json_encode($alerta);
						exit();
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

			/*---- comprobar clave----*/
			if($clave1!=$clave2){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Las contraseñas no coincide",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}else{
				$clave=mainModel::encryption($clave1);
			}

			/*----comprobar privilegio-----*/
			if($privilegio<1 || $privilegio>3){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Privilegio no valido",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			// ----validar FOTO seleccionado ---->
			$ruta_foto_db="";
			if($foto["name"] != ""){
				if(mainModel::verificar_foto($foto)){
					/*--- guardar file foto --*/
					$file_name = $dni.date('_d_m_Y_His').str_replace(" ", "", basename($foto["name"]));
					$destino_url = "adjuntos/user-sistema-foto/".$file_name;
					// GUARDAR FOTO EN CARPETA Adjunto ---------------------
					if(mainModel::guardar_foto($destino_url,$foto)){
						// ruta foto para base datos
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

			/*---PREPARAR CARGAR---*/
			$datos = [
				"DNI" => $dni,
				"Nombre" => $nombre,
				"Apellido" => $apellido,
				"Telefono" => $telefono,
				"Domicilio" => $domicilio,
				"Email" => $email,
				"Foto" => $ruta_foto_db,
				"Usuario" => $usuario,
				"Clave" => $clave,
				"Estado" => "Activa",
				"Privilegio" => $privilegio
			];

			// instancia A modelo
			$guardar_usuario=usuarioModelo::agregar_usuario_modelo($datos);
			
			if($guardar_usuario->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Usuario registrado",
					"Texto"=>"Los datos fueron registrados",
					"Tipo"=>"success",
					"User"=>"usuario",
					"clearFoto"=>SERVERURL
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar el usuario",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
				

		} //fin agregar_usuario_controlador

		/* paginador usuraio
		* @param: $pagina: pagina actual,$registros: registros a mostrar,$privilegio: acultar algunas acciones, $id: id de usuario que ha iniciado sesion,$url:la vista para botones $busqueda: lista usuario o usuario buscar
		*/
		public function paginador_usuario_controlador($pagina,$registros,$privilegio,$id,$url,$busqueda){
			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			$id=mainModel::limpiar_cadena($id);
			
			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);

			$tabla="";
			// operador ternario, false: llevar a pag 1
			$pagina= (isset($pagina) && $pagina>0) ? (int) $pagina : 1 ;

			$inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0 ;
			// consulta bd
			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM usuarios WHERE ((id!='$id' AND id!='1') AND (userDni LIKE '%$busqueda%' OR userNombre LIKE '%$busqueda%' OR userApellido LIKE '%$busqueda%' OR userTelefono LIKE '%$busqueda%' )) ORDER BY userNombre ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM usuarios WHERE id!='$id' AND id!='1' ORDER BY userNombre ASC LIMIT $inicio,$registros";
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
			                        <th>NIT/CI</th>
			                        <th>Nombre</th>
			                        <th>Telefono</th>
			                        <th>Domicilio</th>
			                        <th>Usuario</th>
			                        <th>Estado</th>
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
			                        <td>'.$rows['userDni'].'</td>
			                        <td>
			                        <img src="'.SERVERURL.$rows['userFoto'].'" alt="usuariofoto" class="thumb-sm rounded-circle mr-2">
			                        '.$rows['userNombre'].' '.$rows['userApellido'].'</td>
			                        <td>'.$rows['userTelefono'].'</td>
			                        <td>'.$rows['userDomicilio'].'</td>
			                        <td>'.$rows['userUsuario'].'</td>
			                        <td>'.$rows['userEstado'].'</td>
			                        <td>
			                          <div class="dropdown no-arrow">
			                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                              <i class="fas fa-ellipsis-h fa-lg fa-fw"></i>
			                            </a>
			                            <div class="dropdown-menu shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
			                              <a class="dropdown-item" href="'.SERVERURL.'editUsuario/'.mainModel::encryption($rows['id']).'/"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>Actualizar Datos</a>
			                             
			                              <form class="FormularioAjax" action="'.SERVERURL.'ajax/usuarioAjax.php" method="POST" data-form="delete">
			                              	<input type="hidden" name="usuario_id_del" value="'.mainModel::encryption($rows['id']).'">
			                              	<input type="hidden" name="privilegio_user" value="'.mainModel::encryption($privilegio).'">
			                              	<button type="submit" class="dropdown-item"><i class="fas fa-trash-alt fa-sm fa-fw mr-2 text-gray-400"></i>Eliminar</button>
			                              </form>
			                              
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
						$tabla.='<p class="text-right">Mostrando usuarios '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
						$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,5);
					}

					return $tabla;	                

		} //fin controlador paginador

		/* Elimimar usuario
		*	@return: json_encode(array): alerta respueta de servidor y validaciones
		*/
		public function eliminar_usuario_controlador(){
			//  recibir id de usuario
			$id=mainModel::decryption($_POST['usuario_id_del']);
			$id=mainModel::limpiar_cadena($id);
			$privilegio=mainModel::decryption($_POST['privilegio_user']);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			
			// comprobar usuario principal
			if($id==1){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No podemos eliminar el usuario principal ",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			// ------- comprobar usuario en DB ----->
			$check_usuario = mainModel::ejecutar_consulta_simple("SELECT id FROM usuarios WHERE id='$id'");
			if($check_usuario->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El usuario no existe en el sistema",
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
			// eliminar de carpeta foto, buscar ruta foto
			$ruta_foto_del=mainModel::ejecutar_consulta_simple("SELECT userFoto FROM usuarios WHERE id='$id' ");
			$num_foto = $ruta_foto_del->rowCount();
			if($num_foto>0){
				$campos = $ruta_foto_del->fetch();
				$campo_foto = $campos['userFoto']; 
			}

			$eliminar_usuario=usuarioModelo::eliminar_usuario_modelo($id);
			if($eliminar_usuario->rowCount()==1){
				// eliminar foto adjunto
				if($num_foto>0){
					
					if($campo_foto!=""){
						unlink("../".$campo_foto);
					}
					
				}
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Usuario eliminado",
					"Texto"=>"El usuario a sido eliminado del sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar el usuario, intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);

		} // fin controlador eliminar
		
		/*	Buscar datos de usuario
		* @param: $tipo: unico(datos un solo usuario) o conteo(total de usuarios),$id: del usuario
		*/
		public static function datos_usuario_controlador($tipo,$id){
			$tipo=mainModel::limpiar_cadena($tipo);
			$id=mainModel::decryption($id);
			$id=mainModel::limpiar_cadena($id);

			return usuarioModelo::datos_usuario_modelo($tipo,$id);
		} // fin datos_usuario_controlador

		/* Editar usuario, validar campos, guardar foto file url.
		*/
		public function actualizar_usuario_controlador(){
			// recibe id input hidden
			$id=mainModel::decryption($_POST['usuario_id_up']);
			$id=mainModel::limpiar_cadena($id);

			// camprobar id de usuario en db
			$check_user=mainModel::ejecutar_consulta_simple("SELECT * FROM usuarios WHERE id='$id'");

			if($check_user->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El usuario no se encuentra registrado en el sistema, intente nuevamente",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}else{
				$campos=$check_user->fetch();
			}

			$dni=mainModel::limpiar_cadena($_POST['usuario_dni_edit']);
			$nombre=mainModel::limpiar_cadena($_POST['usuario_nombre_edit']);
			$apellido=mainModel::limpiar_cadena($_POST['usuario_apellido_edit']);
			$telefono=mainModel::limpiar_cadena($_POST['usuario_telefono_edit']);
			$domicilio=mainModel::limpiar_cadena($_POST['usuario_direccion_edit']);
			$usuario=mainModel::limpiar_cadena($_POST['usuario_usuario_edit']);
			$email=mainModel::limpiar_cadena($_POST['usuario_email_edit']);
			$foto=$_FILES['usuario_foto_edit'];
			/*-------VALIDAR CAMPO FOTO -----*/
			if($foto['tmp_name']!=""){
				if(mainModel::verificar_foto($foto)){
					$file_name = $dni.date('_d_m_Y_His').str_replace(" ", "", basename($foto["name"]));
					$destino_url = "adjuntos/user-sistema-foto/".$file_name;
					/*--- guardar file foto --*/
					if(mainModel::guardar_foto($destino_url,$foto)){
 
						// ruta foto para base datos
						$ruta_foto_db=$destino_url;
					
						unlink("../".$campos['userFoto']);	
					
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
			}else{
				// sin cambios mantener ruta actual
				$ruta_foto_db=$campos['userFoto'];
			}

			if(isset($_POST['usuario_estado_up'])){
				$estado=mainModel::limpiar_cadena($_POST['usuario_estado_up']);
			}else{
				$estado=$campos['userEstado'];
			}

			if(isset($_POST['usuario_privilegio_edit'])){
				$privilegio=mainModel::limpiar_cadena($_POST['usuario_privilegio_edit']);
			}else{
				$privilegio=$campos['userPrivilegio'];
			}

			/*-Para poder guardar los cambios en esta cuenta debe de ingresar su nombre de usuario y contraseña*/
			$admin_usuario=mainModel::limpiar_cadena($_POST['usuario_admin']);
			$admin_clave=mainModel::limpiar_cadena($_POST['clave_admin']);
			/*----Tipo de cuenta Propia,Impropia*/
			$tipo_cuenta=mainModel::limpiar_cadena($_POST['tipo_cuenta']);

			/*----Campos vacios---------*/
			if($dni=="" || $nombre=="" || $apellido=="" || $usuario=="" || $admin_usuario=="" || $admin_clave==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*------ validar entrada de datos ---------*/
			// solo nuero guion, min 10 asta 20
			if(mainModel::verificar_datos("[0-9-]{1,20}",$dni)){
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
			if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El NOMBRE DE USUARIO no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$admin_usuario)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"TU NOMBRE DE USUARIO no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"TU CLAVE no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$admin_clave=mainModel::encryption($admin_clave);

			/*----nivel de privilegios ----*/

			if($privilegio<1 || $privilegio>3){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El privilegio no es valido",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();	
			}
			if($estado!="Activa" && $estado!="Deshabilitada"){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El estado seleccionado no es valido",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();	
			}

			/*----- Comprobar DNI si existe en DB,   ----- */
			if($dni!=$campos['userDni']){
				$check_dni=mainModel::ejecutar_consulta_simple("SELECT userDni FROM usuarios WHERE userDni='$dni'");
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
			}
			
			/*----- Comprobar USUARIO si existe en DB ----- */
			if($usuario!=$campos['userUsuario']){
				$check_user=mainModel::ejecutar_consulta_simple("SELECT userUsuario FROM usuarios WHERE userUsuario='$usuario'");
				if($check_user->rowCount()>0){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El USUARIO ya se encuentra registrado en el sistema",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}	
			}
			/*--- comprobar email --- DB---*/
			if($email!=$campos['userEmail'] && $email!=""){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$check_email=mainModel::ejecutar_consulta_simple("SELECT userEmail FROM usuarios WHERE userEmail='$email'");
					if($check_email->rowCount()>0){
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"El nuevo email ingresado ya se encuentra registrado en el sistema",
							"Tipo"=>"error"
						];
						echo json_encode($alerta);
						exit();
					}	
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Correo No valido",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}

			}

			/*----- comprobar Claves -------*/
			if($_POST['usuario_clave_nueva_1']!="" || $_POST['usuario_clave_nueva_2']!=""){
				if($_POST['usuario_clave_nueva_1']!=$_POST['usuario_clave_nueva_2']){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Las claves ingresadas no coinciden",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}else{
					if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$_POST['usuario_clave_nueva_1']) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$_POST['usuario_clave_nueva_2'])){
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"Las claves ingresadas no coinciden con el formato solicitado",
							"Tipo"=>"error"
						];
						echo json_encode($alerta);
						exit();
					}
					$clave=mainModel::encryption($_POST['usuario_clave_nueva_1']);
				}
			}else{
				// conservar clave
				$clave=$campos['userClave'];
			}
			/*---- comprobar privilegios ---- */
			if($tipo_cuenta=="Propia"){
				$check_cuenta=mainModel::ejecutar_consulta_simple("SELECT id FROM usuarios WHERE userUsuario='$admin_usuario' AND userClave='$admin_clave' AND id='$id'");
			}else{
				if(session_start(['name'=>'VETP'])){
					if(isset($_SESSION['privilegio_vetp']) == false){
						$alerta=[
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrió un error inesperado",
								"Texto"=>"Error con _session variable",
								"Tipo"=>"error"
							];
							echo json_encode($alerta);
							exit();
					}else{
						if($_SESSION['privilegio_vetp']!=1){
							$alerta=[
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrió un error inesperado",
								"Texto"=>"No tienes los permisos necesarios para realizar esta operación",
								"Tipo"=>"error"
							];
							echo json_encode($alerta);
							exit();
						}
						$check_cuenta=mainModel::ejecutar_consulta_simple("SELECT id FROM usuarios WHERE userUsuario='$admin_usuario' AND userClave='$admin_clave'");	
					}
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Error al conectar session",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			if($check_cuenta->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Nombre y clave no validos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			

			/*-------- PREPARAR DATOS -> ENVIAR A MODELO ----*/
			$datos_usuario_up=[
				"DNI"=>$dni,
				"Nombre"=>$nombre,
				"Apellido"=>$apellido,
				"Telefono"=>$telefono,
				"Domicilio"=>$domicilio,
				"Email"=>$email,
				"Foto"=>$ruta_foto_db,
				"Usuario"=>$usuario,
				"Clave"=>$clave,
				"Estado"=>$estado,
				"Privilegio"=>$privilegio,
				"ID"=>$id
			];
			// instancia Modelo ->
			if(usuarioModelo::actualizar_usuario_modelo($datos_usuario_up)){
				// -- actualizar datos de sesion
				if($tipo_cuenta=="Propia"){
					if(session_start(['name'=>'VETP'])){
						if((isset($_SESSION['nombre_vetp']) == true) || (isset($_SESSION['apellido_vetp']) == true) || (isset($_SESSION['foto_vetp']) == true) ){
							$_SESSION['nombre_vetp']=$nombre;
							$_SESSION['apellido_vetp']=$apellido;
			 				$_SESSION['foto_vetp']=$ruta_foto_db;	
						}
					}
				}
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

		} // fin actualizar_usuario_controlador


	}

	?>