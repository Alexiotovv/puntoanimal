<?php 
 
 	if ($peticionAjax) {
	  require_once "../modelos/clienteModelo.php";
	} else {
	  require_once "./modelos/clienteModelo.php";
	}

	/**
	 * hereda de clienteModelo, y clienteModelo hereda de mainModel
	 */
	class clienteControlador extends clienteModelo{

 
		/* Agregar cliente: limpiar entradas, validar, enviar a modelo
		*  @return: json_encode: alerta con respuesta de validacion, servidor
		*/
		public function agregar_cliente_controlador(){

			$dni=mainModel::limpiar_cadena($_POST['cliente_dni_reg']);
			$nombre=mainModel::limpiar_cadena($_POST['cliente_nombre_reg']);
			$apellido=mainModel::limpiar_cadena($_POST['cliente_apellido_reg']);
			$telefono=mainModel::limpiar_cadena($_POST['cliente_telefono_reg']);
			$email=mainModel::limpiar_cadena($_POST['cliente_email_reg']);
			$direccion=mainModel::limpiar_cadena($_POST['cliente_direccion_reg']);
			$genero=mainModel::limpiar_cadena($_POST['cliente_genero_reg']);
			
			$foto=$_FILES['archivo_foto_subir'];

			// radio avatares
			if(isset($_POST['avatar-cliente'])){
				$avatar=$_POST['avatar-cliente'];
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
					
					$destino_url = "adjuntos/clientes-foto/".$file_name;
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
			if($dni=="" || $nombre=="" ){
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

			

			if($telefono!=""){
				if(mainModel::verificar_datos("[0-9()+]{8,20}",$telefono)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El TELEFONO no coincide con el formato solicitado 8 digitos a más",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			
			
			/*----- Comprobar DNI si existe en DB  ----- */
			$check_dni=mainModel::ejecutar_consulta_simple("SELECT clienteDniCedula FROM cliente WHERE clienteDniCedula='$dni'");
			if($check_dni->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El DNI o Ruc ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			
			/*----- Comprobar EMAIL(campo opcional) si existe en DB ----- */
			if($email!=""){
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

			/*---X--- validar entrada de datos ----X-----*/

			/*---PREPARAR CARGAR---*/
			$datos = [
				"DniCedula" => $dni,
				"Nombre" => $nombre,
				"Apellido" => $apellido,
				"Genero" => $genero,
				"Telefono" => $telefono,
				"Correo" => $email,
				"Domicilio" => $direccion,
				"FotoUrl" => $ruta_foto_db
			];

			// instancia a modelo
			$guardar_cliente=clienteModelo::agregar_cliente_modelo($datos);
			
			if($guardar_cliente->rowCount()==1){
				// si agregar cliente viene de pagina nueva venta
				if(isset($_POST['add_cliente_venta'])){
					$alerta=[
						"Alerta"=>"toastr",
						"Titulo"=>"Cliente registrado",
						"Texto"=>"Los datos fueron registrados",
						"Redi"=>"redireccionar",
						"URL"=>SERVERURL."addNuevaVenta/"
					];
				}else{
					$alerta=[
						"Alerta"=>"limpiar",
						"Titulo"=>"Cliente registrado",
						"Texto"=>"Los datos fueron registrados",
						"Tipo"=>"success",
						"User"=>"cliente",
						"clearFoto"=>SERVERURL
					];	
				}
				
				
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar el cliente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		

		} //function fin agregar_cliente_controlador
		
		/* paginador cliente
		* @param: $pagina: pagina actual,$registros: registros a mostrar,$privilegio: acultar algunas acciones,$url:la vista para botones $busqueda: lista cliente o cliente buscar
		*/
		public function paginador_cliente_controlador($pagina,$registros,$privilegio,$url,$busqueda){
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
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM cliente WHERE ((clienteDniCedula LIKE '%$busqueda%' OR clienteNombre LIKE '%$busqueda%' OR clienteApellido LIKE '%$busqueda%' OR clienteTelefono LIKE '%$busqueda%' )) ORDER BY idCliente DESC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM cliente ORDER BY idCliente DESC LIMIT $inicio,$registros";
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
		                        <th>Cliente</th>
		                        <th>NIT/CI</th>
		                        <th>Telefono</th>
		                        <th>Correo</th>
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
			                          <img src="'.SERVERURL.$rows['clienteFotoUrl'].'" alt="clienteFoto" class="thumb-sm rounded-circle mr-2">'.$rows['clienteNombre'].' '.$rows['clienteApellido'].'</td>
			                        <td>'.$rows['clienteDniCedula'].'</td>
			                        <td>'.$rows['clienteTelefono'].'</td>
			                        <td>'.$rows['clienteCorreo'].'</td>
			                        <td>'.$rows['clienteDomicilio'].'</td>
			                        <td>
			                          <div class="dropdown no-arrow" style="display:flex; padding:2px;">
										<a class="dropdown-toggle" style="padding:10px;" href="'.SERVERURL.'editCliente/'.mainModel::encryption($rows['clienteDniCedula']).'/" title="Editar Cliente">
										<i class="fa-solid fa-pen-to-square fa-beat" style="color:blue;"></i>
										</a>
										<a class="dropdown-toggle" style="padding:10px;" href="'.SERVERURL.'perfilCliente/'.mainModel::encryption($rows['clienteDniCedula']).'/" title="Ver Perfil">
										<i class="fas fa-user" style="color:blue;"></i>
										</a>
			                            <a class="dropdown-toggle" style="padding:10px;" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                              <i class="fa fa-cog" style="color:#9668e5;"></i>
			                            </a>
			                            <a class="dropdown-toggle" style="padding:10px;" href="https://api.whatsapp.com/send?phone=591'.$rows['clienteTelefono'].'" title="Contactar" target="_blank">
										<i class="fa-brands fa-whatsapp" style="color: #00ff00;"></i>
										</a>

									
			                            <div class="dropdown-menu shadow animated--fade-in" aria-labelledby="dropdownMenuLink">';
			                              //  ---- EDITAR --- //////
			                              if($privilegio<=2){
			                              	$tabla.='<a class="dropdown-item" href="'.SERVERURL.'editCliente/'.mainModel::encryption($rows['clienteDniCedula']).'/"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>Actualizar</a>';
			                              }
			                              // ////// ELIMINAR ///////
                							if($privilegio==1){
                								$tabla.='
				                              <form class="FormularioAjax" action="'.SERVERURL.'ajax/clienteAjax.php" method="POST" data-form="delete">
				                              	<input type="hidden" name="cliente_dni_del" value="'.mainModel::encryption($rows['clienteDniCedula']).'">
				                              	
				                              	<input type="hidden" name="privilegio_user" value="'.mainModel::encryption($privilegio).'">
				                              	
				                              	<button type="submit" class="dropdown-item"><i class="fas fa-trash-alt fa-sm fa-fw mr-2 text-gray-400"></i>Eliminar</button>
				                              </form>';
                							}
			                              
			                              if($privilegio<=3){
			                              	$tabla.='
			                              <a class="dropdown-item" href="'.SERVERURL.'perfilCliente/'.mainModel::encryption($rows['clienteDniCedula']).'/"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>Ver perfil</a>
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
						$tabla.='<p class="text-right">Mostrando cliente '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
						$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,5);
					}

					return $tabla;	                

		} //fin controlador paginador
		
		/* Elimimar cliente 
		*	@return: json_encode(array): alerta con respuesta de servidor y validaciones
		*/
		public function eliminar_cliente_controlador(){
			$dni=mainModel::decryption($_POST['cliente_dni_del']);
			$dni=mainModel::limpiar_cadena($dni);
			$privilegio=mainModel::decryption($_POST['privilegio_user']);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			
			// ------- comprobar cliente en DB ----->
			$check_cliente = mainModel::ejecutar_consulta_simple("SELECT clienteDniCedula FROM cliente WHERE clienteDniCedula='$dni'");
			if($check_cliente->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El cliente no existe en el sistema",
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
					"Texto"=>"No podemos eliminar este cliente debido a que tiene mascota/s asociadas",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			// comprobar con clave foranea tablas citas
			$check_citas = mainModel::ejecutar_consulta_simple("SELECT dniCliente FROM citas WHERE dniCliente='$dni' ");
			if($check_citas->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No podemos eliminar este cliente debido a que tiene Cita/s asociadas",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			// comprobar con clave foranea tablas ventas
			$check_venta = mainModel::ejecutar_consulta_simple("SELECT dniCliente FROM venta WHERE dniCliente='$dni' ");
			if($check_venta->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No podemos eliminar este cliente debido a que tiene Venta/s asociadas",
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

			// ELIMINAR DE CARPETA FOTO ADJUNTO-----
			$check_foto=mainModel::ejecutar_consulta_simple("SELECT clienteFotoUrl FROM cliente WHERE clienteDniCedula='$dni' ");
			$num_foto=$check_foto->rowCount();
			if($num_foto>0){
				$campos = $check_foto->fetch();
				$campo_foto = $campos['clienteFotoUrl'];
				$ruta_del = explode("/", $campo_foto);
			}

			// ----- Instancia a modelo ----->
			$eliminar_cliente=clienteModelo::eliminar_cliente_modelo($dni);
			if($eliminar_cliente->rowCount()==1){
				// eliminar foto adjunto
				if($num_foto>0){
					if($ruta_del[0]=="adjuntos"){
						unlink("../".$campo_foto);
					}	
				}
				
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Cliente eliminado",
					"Texto"=>"El cliente a sido eliminado del sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar el cliente, intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);

		} // fin controlador eliminar

		/*	Buscar datos de cliente
		* @param: $tipo: unico(datos un solo cliente) o conteo(total de clientes), $dni: cedula de cliente(clave unica)
		*/
		public static function datos_cliente_controlador($tipo,$dni){
			$tipo=mainModel::limpiar_cadena($tipo);
			$dni=mainModel::decryption($dni);
			$dni=mainModel::limpiar_cadena($dni);

			return clienteModelo::datos_cliente_modelo($tipo,$dni);
		} // fin datos_cliente_controlador


		/** Buscar cliente para select , en evento keyup en input de select
		*   @return: segmento html <option> con cliente/s
		*/
		public function buscar_dueno_controlador(){
			$valor=mainModel::limpiar_cadena($_POST['valorBuscar']);

			if($valor==""){
			    $query = 'SELECT * FROM cliente ORDER BY idCliente DESC limit 4';
			}else{
				$query = "SELECT * FROM cliente WHERE ((clienteDniCedula LIKE '%$valor%' OR clienteNombre LIKE '%$valor%' OR clienteApellido LIKE '%$valor%' )) LIMIT 5";
			}

			$conexion = mainModel::conectar();
			$datos = $conexion->query($query);
			$datos = $datos->fetchAll();

			$clientes="";
			foreach($datos as $rows){ 
				$clientes.='
					<option value="'.$rows['clienteDniCedula'].'" data-subtext="'.$rows['clienteDniCedula'].'" data-foto="'.SERVERURL.$rows['clienteFotoUrl'].'">'.$rows['clienteNombre'].' '.$rows['clienteApellido'].'</option>
				';
			}
			return $clientes;


		} // fin buscar_dueno controlador

		/*	Buscar datos de cliente perfil, total mascotas, facturado
		* @param: $tipo: mascotas,facturas, $dni: cedula de cliente(clave unica)
		* @return: segmento html
		*/
		public function datos_perfil_cliente_controlador($tipo,$dni){
			$tipo=mainModel::limpiar_cadena($tipo);
			$dni=mainModel::decryption($dni);
			$dni=mainModel::limpiar_cadena($dni);
			if($tipo=="listaMascota"){
				$lista_mascota=clienteModelo::datos_perfil_cliente_modelo($tipo,$dni);
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

		} // fin datos_perfil_cliente_controlador

		/* Actualizar cliente
		*	@return: json_encode(array): respuesta de servidor y validaciones
		*/
		public function actualizar_cliente_controlador(){
			$id=mainModel::decryption($_POST['usuario_id_up']);
			$id=mainModel::limpiar_cadena($id);

			// camprobar dni de cliente en db
			$check_cliente=mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE idCliente='$id'");
 
			if($check_cliente->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El cliente no se encuentra registrado en el sistema, intente nuevamente",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}else{
				$campos=$check_cliente->fetch();
			}

			$dni=mainModel::limpiar_cadena($_POST['cliente_dni_edit']);
			$nombre=mainModel::limpiar_cadena($_POST['cliente_nombre_edit']);
			$apellido=mainModel::limpiar_cadena($_POST['cliente_apellido_edit']);
			$telefono=mainModel::limpiar_cadena($_POST['cliente_telefono_edit']);
			$email=mainModel::limpiar_cadena($_POST['cliente_email_edit']);
			$direccion=mainModel::limpiar_cadena($_POST['cliente_direccion_edit']);
			$foto=$_FILES['archivo_foto_subir'];

			/*-------VALIDAR CAMPOS -----*/
			if(isset($_POST['cliente_genero_edit'])){
				$genero=mainModel::limpiar_cadena($_POST['cliente_genero_edit']);
				if($genero!="Femenino" && $genero!="Masculino"){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"Genero de cliente no valido",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Seleccionar genero del cliente",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			// ------ radio avatares -------------
			if(isset($_POST['avatar-cliente'])){
				$avatar=$_POST['avatar-cliente'];
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
				$ruta_foto_db=$campos['clienteFotoUrl'];
			}

			// ---AVATAR seleccionado--->
			if ($avatar!="") {
				if(file_exists("../vistas/images/avatar_user_cli/".$avatar)){
					$ruta_foto_db = "vistas/images/avatar_user_cli/".$avatar;
					// eliminar si anterior foto es un adjunto
					$foto_del = explode("/", $campos['clienteFotoUrl']);
					if($foto_del[0]=="adjuntos"){
						unlink("../".$campos['clienteFotoUrl']);
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
					
					$destino_url = "adjuntos/clientes-foto/".$file_name;
					// guardar foto en carpeta
					if(mainModel::guardar_foto($destino_url,$foto)){
						// ruta para base datos
						$ruta_foto_db=$destino_url;
						// si anterior foto es adjunto
						$foto_del = explode("/", $campos['clienteFotoUrl']);
						if($foto_del[0]=="adjuntos"){
							unlink("../".$campos['clienteFotoUrl']);
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
			if($dni=="" || $nombre=="" || $telefono==""){
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

			

			if($telefono!=""){
				if(mainModel::verificar_datos("[0-9()+]{8,20}",$telefono)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El TELEFONO no coincide con el formato solicitado 8 digintos a más",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}


			
			/*----- Comprobar DNI si existe en DB  ----- */
			$dni_actual = $campos['clienteDniCedula'];
			if($dni_actual==$dni){
				$dni_db=$dni_actual;
			}else{
				$check_dni=mainModel::ejecutar_consulta_simple("SELECT clienteDniCedula FROM cliente WHERE clienteDniCedula='$dni'");
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
			$email_actual=$campos['clienteCorreo'];
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
			}

			/*---X--- validar entrada de datos ----X-----*/

			/*---PREPARAR CARGAR---*/
			$datos = [
				"DniCedula" => $dni_db,
				"Nombre" => $nombre,
				"Apellido" => $apellido,
				"Genero" => $genero,
				"Telefono" => $telefono,
				"Correo" => $email_db,
				"Domicilio" => $direccion,
				"FotoUrl" => $ruta_foto_db,
				"ID" => $id
			];

			// instancia A Modelo ->
			if(clienteModelo::actualizar_cliente_modelo($datos)){
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


		} // fin actualizar_cliente_controlador

		/*	Buscar todo historial facturado mostrar en perfil
		* 	@return: json_encode(array): fragmento html de factura/s, total de factura
		*/
		public function datos_perfil_cliente_facturas_controlador(){
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
			
		} // fin datos_perfil_cliente_controlador

		/* lista de los detalles de las facturas del cliente
		*  @return: segmento html <tr><th>, para tabla detalle
		*/
		public function lista_detalle_venta_perfilCliente(){
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

		}

	}
