<?php 

	if ($peticionAjax) {
	  require_once "../modelos/citaModelo.php";
	} else {
	  require_once "./modelos/citaModelo.php";
	}

	class citaControlador extends citaModelo{

		/* Agregar cita: limpiar entradas, validar, enviar a modelo
		*  @return: json_encode: alerta con respuesta de validacion, servidor
		*/
		public function agregar_cita_controlador(){

			// fecha proxima
			$fecha=mainModel::limpiar_cadena($_POST['cita_fecha_reg']);
			$hora=mainModel::limpiar_cadena($_POST['cita_hora_reg']);
			$motivo=mainModel::limpiar_cadena($_POST['cita_motivo_reg']);
			// fecha emitida actual
			$fechae=date('Y-m-d');

			if(isset($_POST['mascota-dueno'])){
				$dnicliente=mainModel::limpiar_cadena($_POST['mascota-dueno']);
				if($dnicliente==""){
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

			if(isset($_POST['cita_paciente_reg'])){
				$codmascota=mainModel::limpiar_cadena($_POST['cita_paciente_reg']);
				if($codmascota==""){
						$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"Codigo de mascota no valido",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Debe seleccionar una mascota",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/*----Campos vacios---------*/
			if($fecha=="" || $hora=="" || $motivo==""){
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

			/* ---- Proxima cita, si fecha proxima es menor a la actual ---- */
			if($fecha<$fechae){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Eligir una fecha valida",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*----- Comprobar DNI dueño en DB  ----- */
			$check_dni=mainModel::ejecutar_consulta_simple("SELECT clienteDniCedula FROM cliente WHERE clienteDniCedula='$dnicliente'");
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

			/*----- Comprobar Codigo de mascota en DB  ----- */
			$check_cod=mainModel::ejecutar_consulta_simple("SELECT codMascota,dniDueno FROM mascota WHERE codMascota='$codmascota'");
			if($check_cod->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La mascota seleccionada no se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}else{
				$campos=$check_cod->fetch();
			}
			/*----- Comprobar mascota de cliente  ----- */
			if($campos['dniDueno']!=$dnicliente){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La mascota no pertenece a este dueño",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			// ----- CODIGO CITA ----------
			$consulta=mainModel::ejecutar_consulta_simple("SELECT idCita FROM citas ");

            $numero=($consulta->rowCount())+1;

            $codcita=mainModel::generar_codigo_aleatorio("CT-",5,$numero);

			/*---PREPARAR CARGAR---*/
			$datos = [
				"Codcita" => $codcita,
				"Codmascota" => $codmascota,
				"Dnicliente" => $dnicliente,
				"FechaE" => $fechae,
				"FechaP" => $fecha,
				"Hora" => $hora,
				"Motivo" => $motivo,
				"Estado" => "Pendiente"
			];

			// instancia a modelo
			$guardar_cita=citaModelo::agregar_cita_modelo($datos);

			if($guardar_cita->rowCount()==1){
				if(isset($_POST['redireccionar_lista_cita'])){
					$alerta=[
						"Alerta"=>"limpiar",
						"Titulo"=>"Cita registrada con exito",
						"Texto"=>"Los datos fueron registrados",
						"Tipo"=>"success",
						"User"=>"citalista",
						"URL"=>SERVERURL."listaCita/"
					];
				}else{
					$alerta=[
						"Alerta"=>"limpiar",
						"Titulo"=>"Cita registrada",
						"Texto"=>"Los datos fueron registrados",
						"Tipo"=>"success",
						"User"=>"cita",
						"clearFoto"=>SERVERURL
					];	
				}
				
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar la cita",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);

		} // fin agregar_cita_controlador

		/*	Buscar datos de cita
		* @param: $tipo: unico, conteo, $cod: codigo cita(clave unica)
		*/
		public static function datos_cita_controlador($tipo,$cod){
			$tipo=mainModel::limpiar_cadena($tipo);
			$cod=mainModel::decryption($cod);
			$cod=mainModel::limpiar_cadena($cod);

			return citaModelo::datos_cita_modelo($tipo,$cod);
		} // fin datos_cliente_controlador


		/** Buscar mascota/s de cliente para select
		*   @return: segmento html <option> con mascota/s
		*/
		public function buscar_paciente_mascota_controlador(){

			$dnicliente=mainModel::limpiar_cadena($_POST['dni_cliente']);

			$query = 'SELECT * FROM mascota WHERE dniDueno='.$dnicliente.' ';

			$conexion = mainModel::conectar();
			$datos = $conexion->query($query);
			
			$mascota="";
			if($datos->rowCount()>=1){
				$datos = $datos->fetchAll();
				foreach($datos as $rows){ 
				$mascota.='
					<option value="'.$rows['codMascota'].'" data-subtext="'.$rows['codMascota'].'" data-foto="'.SERVERURL.$rows['mascotaFoto'].'">'.$rows['mascotaNombre'].'</option>
				';
				}
			}else{
				$mascota.=	'<option value="0">Cliente no posee mascota/s registradas</option>';
			}

			return $mascota;

		
		} // buscar_paciente_mascota_controlador

		/* paginador citas
		* @param: $pagina: pagina actual,$registros: registros a mostrar,$privilegio: acultar algunas acciones,$url:la vista para botones $busqueda: lista cliente o cliente buscar
		*/
		public function paginador_cita_controlador($pagina,$registros,$privilegio,$url,$busqueda){
			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			
			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);

			$tabla="";
			//  ------ - FECHA HORA ACTUAL -----------
			$fecha=date('Y-m-d');
			
			// operador ternario, false: llevar a pag 1
			$pagina= (isset($pagina) && $pagina>0) ? (int) $pagina : 1 ;

			$inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0 ;
			// consulta bd

			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS ci.* FROM citas ci inner join cliente c on ci.dniCliente = c.clienteDniCedula inner join mascota m on ci.codMascota = m.codMascota WHERE ((codCita LIKE '%$busqueda%' OR ci.codMascota LIKE '%$busqueda%' OR dniCliente LIKE '%$busqueda%' OR citafechaEmitida LIKE '%$busqueda%' OR citaFechaProxima LIKE '%$busqueda%' OR citaHora LIKE '%$busqueda%' OR citaMotivo LIKE '%$busqueda%' OR citaEstado LIKE '%$busqueda%' OR c.clienteNombre LIKE '%$busqueda%' OR c.clienteApellido LIKE '%$busqueda%' OR m.mascotaNombre LIKE '%$busqueda%')) ORDER BY idCita DESC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM citas ORDER BY idCita DESC LIMIT $inicio,$registros";
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
			                      <th>Cliente</th>
			                      <th>Fecha</th>
			                      <th>Hora</th>
			                      <th>Motivo</th>
			                      <th>Estado</th>
			                      <th>Acción</th>
			                  </tr>
			              </thead>
			              <tbody>';

					if($total>=1 && $pagina<=$Npaginas){
						$contador=$inicio+1;
						$reg_inicio=$inicio+1;
						foreach($datos as $rows){
							
							$classestado="";
							$est=$rows['citaEstado'];
							if($est=="Pendiente"){
								$classestado="badge-info";
							}elseif($est=="Procesada"){
								$classestado="badge-success";
							}

							$mascotacod=$rows['codMascota'];
							// CONSULTA: mascota,especie,raza,cliente
							$dueno_raza_esp=mainModel::ejecutar_consulta_simple("SELECT t1.mascotaFoto,t1.mascotaNombre,espNombre,razaNombre,t4.clienteNombre,t4.clienteApellido,t4.clienteTelefono,t4.clienteFotoUrl FROM mascota AS t1 INNER JOIN especie AS t2 ON t1.idEspecie=t2.idEspecie INNER JOIN raza AS t3 ON t1.idRaza=t3.idRaza INNER JOIN cliente AS t4 ON t1.dniDueno=t4.clienteDniCedula WHERE t1.codMascota='$mascotacod' ");
							$campos=$dueno_raza_esp->fetch();

							$tabla.='
								 <tr>
			                      <td>'.$contador.'</td>
			                      <td>'.$rows['codCita'].'</td>
			                      <td class="d-flex flex-row">
			                        <input type="hidden" name="codMascota" value="CM-7654">
			                        <img src="'.SERVERURL.$campos['mascotaFoto'].'" alt="" class="thumb-sm rounded-circle mr-2">
			                        <div class="d-flex flex-column">
			                          <span>'.$campos['mascotaNombre'].'</span>
			                          <small>'.$campos['espNombre'].' - '.$campos['razaNombre'].'</small>
			                        </div>
			                      </td>
			                      <td>
			                        
			                        <img src="'.SERVERURL.$campos['clienteFotoUrl'].'" alt="" class="thumb-sm rounded-circle mr-2">
			                        '.$campos['clienteNombre'].' '.$campos['clienteApellido'].'
			                      </td>
			                      <td>'.date("d-m-Y", strtotime($rows['citaFechaProxima'])).'</td>
			                      <td>'.$rows['citaHora'].'</td>
			                      <td>'.$rows['citaMotivo'].'</td>
			                      <td class="estado">
			                        <span class="badge '.$classestado.'">'.$rows['citaEstado'].'</span>
			                      </td>
			                      <td>
			                         <div class="dropdown no-arrow" style="display:flex; padding:2px;">
    									<a class="dropdown-toggle" style="padding:10px;" role="button" href="https://api.whatsapp.com/send?phone=591'.$campos['clienteTelefono'].'" title="Contactar" target="_blank">
    									<i class="fa-brands fa-whatsapp" style="color: #00ff00;"></i>
    									</a>
			                            <a class="dropdown-toggle" style="padding:10px;" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                            <i class="fa fa-cog" style="color:#9668e5;"></i>
			                            </a>
			             
			                          
			                          <div class="dropdown-menu shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
			                           ';
			                           if($est=="Pendiente"){
				                           	if($privilegio<=2){
					                           	$tabla.='
						                            <!-- Ir a historia -->
													<div class="dropdown no-arrow">
				
						                            <a class="dropdown-item" href="'.SERVERURL.'addHistorialM/'.mainModel::encryption($rows['codMascota']).'/'.mainModel::encryption($rows['codCita']).'/"><i class="fas fa-door-open fa-sm fa-fw mr-2 text-gray-400"></i>Ir a historia Clinica
						                            </a>
						                            
						                            <div class="dropdown-divider"></div>
						                            
						                            <!-- EDITAR -->
						                            <a class="dropdown-item" href="'.SERVERURL.'editCita/'.mainModel::encryption($rows['codCita']).'/"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>Editar</a>
						                            ';
					                           	}
			                           }
			                           
			                           /*---- ELIMINAR -----*/
			                            if($privilegio==1){
			                            	$tabla.='
				                            <!-- form eliminar -->
				                            <form class="FormularioAjax" action="'.SERVERURL.'ajax/citaAjax.php" method="POST" data-form="delete">
				                            <input type="hidden" name="cita_id_del" value="'.mainModel::encryption($rows['idCita']).'">
				                              	
			                              	<input type="hidden" name="privilegio_user" value="'.mainModel::encryption($privilegio).'">
			                              
				                            <button type="submit" class="dropdown-item" >
				                            <i class="fas fa-trash-alt fa-sm fa-fw mr-2 text-gray-400"></i>
				                            Eliminar
				                            </button>
				                            
				                            </form>';
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
						$tabla.='<p class="text-right">Mostrando cita '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
						$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,5);
					}

					return $tabla;	                

		} //fin controlador paginador

		/* paginador citas hoy
		* @param: $pagina: pagina actual,$registros: registros a mostrar,$privilegio: acultar algunas acciones,$url:la vista para botones $busqueda: lista cliente o cliente buscar
		*/
		public function paginador_cita_hoy_controlador($pagina,$registros,$privilegio,$url,$busqueda,$fecha){
			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			
			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$fecha=mainModel::limpiar_cadena($fecha);

			$tabla="";
			// operador ternario, false: llevar a pag 1
			$pagina= (isset($pagina) && $pagina>0) ? (int) $pagina : 1 ;

			$inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0 ;
			// consulta bd()
			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM citas WHERE ((citaFechaProxima='$fecha') AND (codCita LIKE '%$busqueda%' OR codMascota LIKE '%$busqueda%' OR dniCliente LIKE '%$busqueda%' OR citafechaEmitida LIKE '%$busqueda%' OR citaHora LIKE '%$busqueda%' OR citaMotivo LIKE '%$busqueda%' OR citaEstado LIKE '%$busqueda%')) ORDER BY idCita ASC LIMIT $inicio,$registros ";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM citas WHERE citaFechaProxima='$fecha' ORDER BY idCita ASC LIMIT $inicio,$registros  ";
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
			                      <th>Cliente</th>
			                      <th>Fecha</th>
			                      <th>Hora</th>
			                      <th>Motivo</th>
			                      <th>Estado</th>
			                      <th>Acción</th>
			                  </tr>
			              </thead>
			              <tbody>';

					if($total>=1 && $pagina<=$Npaginas){
						$contador=$inicio+1;
						$reg_inicio=$inicio+1;
						foreach($datos as $rows){
							$est=$rows['citaEstado'];

							if($est=="Pendiente"){
								$classestado="badge-info";
							}elseif($est=="Procesada"){
								$classestado="badge-success";
							}

							$mascotacod=$rows['codMascota'];
								
							$dueno_raza_esp=mainModel::ejecutar_consulta_simple("SELECT t1.mascotaFoto,t1.mascotaNombre,espNombre,razaNombre,t4.clienteNombre,t4.clienteApellido,t4.clienteTelefono,t4.clienteFotoUrl FROM mascota AS t1 INNER JOIN especie AS t2 ON t1.idEspecie=t2.idEspecie INNER JOIN raza AS t3 ON t1.idRaza=t3.idRaza INNER JOIN cliente AS t4 ON t1.dniDueno=t4.clienteDniCedula WHERE t1.codMascota='$mascotacod' ");
							$campos=$dueno_raza_esp->fetch();

							$tabla.='
								 <tr>
			                      <td>'.$contador.'</td>
			                      <td>'.$rows['codCita'].'</td>
			                      <td class="d-flex flex-row">
			                        <input type="hidden" name="codMascota" value="CM-7654">
			                        <img src="'.SERVERURL.$campos['mascotaFoto'].'" alt="" class="thumb-sm rounded-circle mr-2">
			                        <div class="d-flex flex-column">
			                          <span>'.$campos['mascotaNombre'].'</span>
			                          <small>'.$campos['espNombre'].' - '.$campos['razaNombre'].'</small>
			                        </div>
			                      </td>
			                      <td>
			                        
			                        <img src="'.SERVERURL.$campos['clienteFotoUrl'].'" alt="" class="thumb-sm rounded-circle mr-2">
			                        '.$campos['clienteNombre'].' '.$campos['clienteApellido'].'
			                      </td>
			                      <td>'.date("d-m-Y", strtotime($rows['citaFechaProxima'])).'</td>
			                      <td>'.$rows['citaHora'].'</td>
			                      <td>'.$rows['citaMotivo'].'</td>
			                      <td class="estado">
			                        <span class="badge '.$classestado.'">'.$rows['citaEstado'].'</span>
			                      </td>
			                      <td>
			                        <div class="dropdown no-arrow">
									
			                          <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                            <i class="fas fa-ellipsis-h fa-lg fa-fw"></i>
			                          </a>
			                          <div class="dropdown-menu shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
			                           ';
			                           if($est=="Pendiente"){
				                           	if($privilegio<=2){
					                           	$tabla.='
						                            <!-- Ir a historia -->
						                            <a class="dropdown-item" href="'.SERVERURL.'addHistorialM/'.mainModel::encryption($rows['codMascota']).'/'.mainModel::encryption($rows['codCita']).'/"><i class="fas fa-door-open fa-sm fa-fw mr-2 text-gray-400"></i>Ir a historia Clinica
						                            </a>
						                            
						                            <div class="dropdown-divider"></div>
						                            
						                            <!-- EDITAR -->
						                            <a class="dropdown-item" href="'.SERVERURL.'editCita/'.mainModel::encryption($rows['codCita']).'/"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>Editar</a>
						                            ';
					                           	}
			                           }
			                           
			                           /*---- ELIMINAR -----*/
			                            if($privilegio==1){
			                            	$tabla.='
				                            <!-- form eliminar -->
				                            <form class="FormularioAjax" action="'.SERVERURL.'ajax/citaAjax.php" method="POST" data-form="delete">
				                            <input type="hidden" name="cita_id_del" value="'.mainModel::encryption($rows['idCita']).'">
				                              	
			                              	<input type="hidden" name="privilegio_user" value="'.mainModel::encryption($privilegio).'">
			                              
				                            <button type="submit" class="dropdown-item" >
				                            <i class="fas fa-trash-alt fa-sm fa-fw mr-2 text-gray-400"></i>
				                            Eliminar
				                            </button>
				                            
				                            </form>';
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
						$tabla.='<p class="text-right">Mostrando cita '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
						$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,5);
					}

					return $tabla;	                

		} //fin controlador paginador
		
		/* Elimimar 
		*  @return: json_encode(array): alerta con respuesta de servidor y validaciones
		*/
		public function eliminar_cita_controlador(){
			$id=mainModel::decryption($_POST['cita_id_del']);
			$id=mainModel::limpiar_cadena($id);
			$privilegio=mainModel::decryption($_POST['privilegio_user']);
			$privilegio=mainModel::limpiar_cadena($privilegio);

			// ------- comprobar cliente en DB ----->
			$check_cita = mainModel::ejecutar_consulta_simple("SELECT idCita FROM citas WHERE idCita='$id'");
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

			// ----- instancia a modelo --->
			$eliminar_cita=citaModelo::eliminar_cita_modelo($id);
			if($eliminar_cita->rowCount()==1){

				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Cita eliminada",
					"Texto"=>"La cita a sido eliminada del sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar la cita, intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
			

		} // fin eliminar_cita_controlador

		/*  Actualizar cita
		*	@return: json_encode(array): respuesta de servidor y validaciones
		*/
		public function actualizar_cita_controlador(){
			$cod=mainModel::decryption($_POST['cita_codigo_up']);
			$cod=mainModel::limpiar_cadena($cod);

			// camprobar cod de cita en db
			$check_cita=mainModel::ejecutar_consulta_simple("SELECT * FROM citas WHERE codCita='$cod'");

			if($check_cita->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La cita no se encuentra registrado en el sistema, intente nuevamente",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}else{
				$campos=$check_cita->fetch();
			}

			$fecha=mainModel::limpiar_cadena($_POST['cita_fecha_edit']);
			$hora=mainModel::limpiar_cadena($_POST['cita_hora_edit']);
			$motivo=mainModel::limpiar_cadena($_POST['cita_motivo_edit']);
			// fecha emitida actual
			$fechae=date('Y-m-d');

			if(isset($_POST['mascota-dueno'])){
				$dnicliente=mainModel::limpiar_cadena($_POST['mascota-dueno']);
				if($dnicliente==""){
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

			if(isset($_POST['cita_paciente_reg'])){
				$codmascota=mainModel::limpiar_cadena($_POST['cita_paciente_reg']);
				if($codmascota==""){
						$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"Codigo de mascota no valido",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Debe seleccionar una mascota",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/*----Campos vacios---------*/
			if($fecha=="" || $hora=="" || $motivo==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/* ---- Proxima cita, si fecha proxima es menor a la de hoy ---- */
			if($fecha<$fechae){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Eligir una fecha valida",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*----- Comprobar DNI dueño en DB  ----- */
			$check_dni=mainModel::ejecutar_consulta_simple("SELECT clienteDniCedula FROM cliente WHERE clienteDniCedula='$dnicliente'");
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

			/*----- Comprobar Codigo de mascota en DB  ----- */
			$check_cod=mainModel::ejecutar_consulta_simple("SELECT codMascota,dniDueno FROM mascota WHERE codMascota='$codmascota'");
			if($check_cod->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La mascota seleccionada no se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}else{
				$campos=$check_cod->fetch();
			}
			/*----- Comprobar mascota de cliente  ----- */
			if($campos['dniDueno']!=$dnicliente){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La mascota no pertenece a este dueño",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*---PREPARAR CARGAR---*/
			
			$datos = [
				"Codmascota" => $codmascota,
				"Dnicliente" => $dnicliente,
				"FechaP" => $fecha,
				"Hora" => $hora,
				"Motivo" => $motivo,
				"Estado" => "Pendiente",
				"COD" => $cod
			];

			// instancia A Modelo ->
			if(citaModelo::actualizar_cita_modelo($datos)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Cita Editada",
					"Texto"=>"Los datos han sido editados con exito",
					"Tipo"=>"success",
					"User"=>"citaedit",
					"URL"=>SERVERURL."listaCita/"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido editar los datos, intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);

		} // fin actualizar_cita_controlador

		/* citas hoy
		*/
		public function citas_hoy_controlador(){			

			// consulta bd()
			$consulta="SELECT SQL_CALC_FOUND_ROWS c.*, cl.clienteFotoUrl, CONCAT(cl.clienteNombre,' ', clienteApellido) AS 
			clienteNombreCompleto FROM citas AS c INNER JOIN cliente cl ON c.dniCliente = cl.clienteDniCedula WHERE citaFechaProxima = DATE(DATE_SUB(NOW(), INTERVAL 7 HOUR)) 
			AND STR_TO_DATE(CONCAT(citaFechaProxima, ' ', c.citaHora), '%Y-%m-%d %h:%i %p') > DATE_SUB(NOW(), INTERVAL 7 HOUR)
			ORDER BY STR_TO_DATE(CONCAT(citaFechaProxima, ' ', c.citaHora), '%Y-%m-%d %h:%i %p');";
			$conexion = mainModel::conectar();
			$datos = $conexion->query($consulta);

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$citas="";
			if($datos->rowCount()>=1){
						$datos = $datos->fetchAll();
				foreach($datos as $rows){ 
				$citas.= ' 
				<a href="'.SERVERURL.'addHistorialM/'.mainModel::encryption($rows['codMascota']).'/'.mainModel::encryption($rows['codCita']).'/">
				<div class="media">
				
				<div class="notificacioness">
				<div class="col-md-3" style="	background: #6f42c1; margin:10px;">
						<img src="'.SERVERURL.$rows['clienteFotoUrl'].'" class="thumb-m rounded-circle">
							
					</div>
					<div class="col-md-9" notificaciones>
						<div class="media-body">
						<p class="text-sm-center" style="color:#8887a9;">'.$rows['clienteNombreCompleto'].'</p>
						<p class="text-sm-center" style="color:#686781;"><strong>'.$rows['citaHora'].'</strong></p>
						</div>
					</div>
				
				</div>	
				</div>
				</a>
				';

				}

				$citas.='
				<div class="dropdown-divider"></div>
				<input type="hidden" id="cantidad_notificaciones" value="'.$total.'">
        		<a href="'.SERVERURL.'listaCitaHoy/" class="dropdown-item dropdown-footer text-sm-center">Ver la lista de citas para hoy</a>
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
		} //fin controlador citas hoy


	}
