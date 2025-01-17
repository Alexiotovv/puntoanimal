<?php 


	if ($peticionAjax) {
	  require_once "../modelos/ventaModelo.php";
	} else {
	  require_once "./modelos/ventaModelo.php";
	}


	class ventaControlador extends ventaModelo{
		
		/* paginador lista venta
		* @param: $pagina: pagina actual,$registros: registros a mostrar,$privilegio: acultar algunas acciones,$url:la vista para botones $busqueda: en tabla venta
		*/
		public function paginador_venta_controlador($pagina,$registros,$privilegio,$url,$busqueda){
			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			
			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$tabla="";
			// operador ternario, false: llevar a pag 1
			$pagina= (isset($pagina) && $pagina>0) ? (int) $pagina : 1 ;

			$inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0 ;
			
			if(isset($busqueda) && $busqueda['finicio']!="" && $busqueda['ffinal']!="" && $busqueda['ttipoc']=="Todos"){
				$fechai=$busqueda["finicio"];
				$fechaf=$busqueda["ffinal"];
				
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM venta,empresa WHERE (( DATE(ventFecha) BETWEEN '$fechai' AND '$fechaf' )) ORDER BY idVenta DESC LIMIT $inicio,$registros";			
			}else if(isset($busqueda) && $busqueda['finicio']!="" && $busqueda['ffinal']!="" && $busqueda['ttipoc']!="Todos"){
				$fechai=$busqueda["finicio"];
				$fechaf=$busqueda["ffinal"];
				$ttipoc=$busqueda["ttipoc"];
			
			$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM venta,empresa WHERE (( DATE(ventFecha) BETWEEN '$fechai' AND '$fechaf' )) AND tipo_comprobante = '$ttipoc' ORDER BY idVenta DESC LIMIT $inicio,$registros";
			
		   }else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM venta,empresa ORDER BY idVenta DESC LIMIT $inicio,$registros";
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
			                      <th>N. Factura</th>
			                      <th>Cliente</th>
			                      <th>Fecha</th>
			                      <!-- <th>Hora</th> -->
			                      <th>Metodo</th>
								  <th>Tipo Comprobante</th>
								  <th>Número Comprobante</th>
			                      <th>Total</th>
			                      <th>Estado</th>
			                     
			                      <th>Acciones</th>
			                  </tr>
			              </thead>
			              <tbody>';

					if($total>=1 && $pagina<=$Npaginas){
						$contador=$inicio+1;
						$reg_inicio=$inicio+1;
						foreach($datos as $rows){
								$idventa=$rows['idVenta'];
								$num_factura = mainModel::generar_numero_factura($rows['idVenta']); // 00000023
								$total_formt = number_format($rows['ventTotal'],2,'.',',');
								
								$cliente=mainModel::ejecutar_consulta_simple("SELECT t4.clienteNombre,t4.clienteApellido,t4.clienteFotoUrl FROM venta AS t1 INNER JOIN cliente AS t4 ON t1.dniCliente=t4.clienteDniCedula WHERE t1.idVenta='$idventa' ");
								$campos=$cliente->fetch();

								$ruta = '../FACT_WebService/Facturacion/files/' . $rows['dov_Nombre'] . '.xml';

								$rutaCdr = '../FACT_WebService/Facturacion/files/R-' . $rows['dov_Nombre'] . '.zip';

								if($rows['estado'] == 'Aceptado'){

									$estado = '<span class="badge badge-success">ACEPTADO</span>';

								}else if($rows['estado'] == 'Rechazado'){

									$estado = '<span class="badge badge-danger">RECHAZADO</span>';

								}else{

									$estado = '<span class="badge badge-warning">POR ENVIAR</span>';

								}
								if($rows['estado'] == 'Aceptado' || $rows['tipo_comprobante'] == 'Ticket'){

									$sunat = '<a href="' . $ruta . '" data-toggle="tooltip" title="" data-original-title="Enviar a Sunat" target="_blank"> <button class="btn btn-warning btn-xs"><i class="fa fa-file"></i></button></a> ' . '<a href="' . $rutaCdr . '" data-toggle="tooltip" title="" data-original-title="Cdr"> <button class="btn btn-danger btn-xs"><i class="fa fa-archive"></i></button></a>';

								}else{
									$tipoc = ($rows['tipo_comprobante'] == 'Boleta') ? 1 : 2;

									$sunat = '<a data-toggle="tooltip" title="" data-original-title="Enviar a Sunat" onclick="EnviarSunat('.$tipoc.',' . $idventa . ',' . $idventa . ');"> <button class="btn btn-primary btn-xs"><i class="fa fa-upload"></i></button></a>
									<a href="' . $ruta . '" data-toggle="tooltip" title="" data-original-title="Enviar a Sunat" target="_blank"> <button class="btn btn-warning btn-xs"><i class="fa fa-file"></i></button></a> ' . '<a href="' . $rutaCdr . '" data-toggle="tooltip" title="" data-original-title="Cdr"> <button class="btn btn-danger btn-xs"><i class="fa fa-archive"></i></button></a>';
									
								}

							$tabla.='
								<tr>
			                      <td>'.$contador.'</td>
			                      <td>'.$num_factura.'</td>
			                      <td>
			                          <img src="'.SERVERURL.$campos['clienteFotoUrl'].'" alt="clienteimg" class="thumb-sm rounded-circle mr-2">'.$campos['clienteNombre'].' '.$campos['clienteApellido'].'<small> '.$rows['dniCliente'].'</td>
			                      <td>'.date("d-m-Y", strtotime($rows['ventFecha'])).'</td>
			                      <td>'.$rows['ventMetodoPago'].'</td>
								  <td>'.$rows['tipo_comprobante'].'</td>
								  <td>'.$rows['serie_comprobante'].''." ".$rows['num_comprobante'].'</td>
			                      <td>'.$total_formt.' </td>
								  <td>'.$estado.'</td>
							
			                      <td>
			                        <div class="dropdown no-arrow">
			                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                              <i class="fa-solid fa-gear" style="color: rgb(224, 181, 31);"></i>
			                            </a>
			                            <div class="dropdown-menu shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
			                           		';
			                           		if($privilegio<=3){
			                           		$tabla.='
			                              		<a class="btn-detalle-modal dropdown-item"  href="#" data-toggle="modal" id_fact="'.$rows['idVenta'].'" serverurl="'.SERVERURL.'" data-target="#modalDetalleFactura"><i class="fas fa-eye fa-sm fa-fw mr-2" style="color: rgb(224, 181, 31);"></i>Detalles</a>
			                              ';
			                          		}
			                             if($privilegio==1){
			                              $tabla.='
			                              <form class="FormularioAjax" action="'.SERVERURL.'ajax/ventaAjax.php" method="POST" data-form="delete">
			                              	<input type="hidden" name="factura_n_del" value="'.mainModel::encryption($rows['idVenta']).'">
			                              	
			                              	<input type="hidden" name="privilegio_user" value="'.mainModel::encryption($privilegio).'">
			                              	
			                              	<button type="submit" class="dropdown-item"><i class="fas fa-trash-alt mr-2 " style="color: rgb(224, 181, 31);"></i>Eliminar</button>
			                              </form>

			                              ';
			                          	  }
			                          	  if($privilegio<=3){
			                              $tabla.='
			                              <a class="btn-factura-imp dropdown-item" href="#" id_fact="'.$rows['idVenta'].'" dni_cli="'.$rows['dniCliente'].'"><i class="far fa-file-alt fa-sm fa-fw mr-2 " style="color: rgb(224, 181, 31);"></i>Factura</a>

			                              <a class="btn-ticket-imp dropdown-item" href="#" id_fact="'.$rows['idVenta'].'" dni_cli="'.$rows['dniCliente'].'"><i class="far fa-file-alt fa-sm fa-fw mr-2 " style="color: rgb(224, 181, 31);"></i>Ticket</a>
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
						$tabla.='<p class="text-right">Mostrando venta '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
						$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,5);
					}

					return $tabla;	                
		}
		/* Elimimar venta
		*	@return: json_encode(array): alerta con respuesta de servidor y validaciones
		*/
		public function eliminar_venta_controlador(){
			$nfactura=mainModel::decryption($_POST['factura_n_del']);
			$nfactura=mainModel::limpiar_cadena($nfactura);
			$privilegio=mainModel::decryption($_POST['privilegio_user']);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			
			// ------- comprobar cliente en DB ----->
			$check_venta = mainModel::ejecutar_consulta_simple("SELECT idVenta FROM venta WHERE idVenta='$nfactura'");
			if($check_venta->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La venta no existe en el sistema",
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

			// ----- Instancia a modelo ----->
			$eliminar_venta=ventaModelo::eliminar_venta_modelo($nfactura);
			if($eliminar_venta->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Venta Eliminada",
					"Texto"=>"La venta a sido eliminada del sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar la venta, intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);

		} // fin controlador eliminar

		/** Buscar producto para input , en evento keyup en input,nueva venta
		*   @return: json_decode(array): array con datos value y text
		*/
		public function buscar_productservi_controlador(){
			$valor=mainModel::limpiar_cadena($_POST['valorBusqueda']);

			$query = "SELECT * FROM productoservicio WHERE ((codProdservi LIKE '%$valor%' OR prodserviNombre LIKE '%$valor%' OR prodserviPrecio LIKE '%$valor%')) LIMIT 10";
			
			$conexion = mainModel::conectar();
			$datos = $conexion->query($query);
			$data = array();

				if($datos->rowCount() > 0)
				{
				 while($row = $datos->fetch())
				 {

				  $data[] = array(
				  	"value"=>mainModel::encryption($row['codProdservi']),
				  	"text"=>$row['prodserviNombre']." || ".$row['prodserviPrecio']
				  );
				 	
				 }
				 echo json_encode($data);
				}else{
					$data[]=array(
						"value"=>0,
				  		"text"=>"Sin resultados"
					);
				 echo json_encode($data);
				}
			
		} // fin 
		
		/** Agregar a tabla detalle_temp: tabla para almacenar de manera temporal los productos/servicios, seleccionados al vender 
		*   @return: json_encode(array): html con detalles/resultados de tabla, o error
		*/
		public function agregar_productodetalle_temp_controlador(){

			//header('Content-Type: application/json');


				if (empty($_POST['producto']) || empty($_POST['cantidad']) || empty($_POST['precio'])){
			    echo 'error';
			}else{
				$codproducto=mainModel::decryption($_POST['producto']);
				$codproducto=mainModel::limpiar_cadena($codproducto);
				$cantidad=mainModel::limpiar_cadena($_POST['cantidad']);
				$precio=mainModel::limpiar_cadena($_POST['precio']);
				
				if(session_start(['name'=>'VETP'])){
					if(isset($_SESSION['id_vetp']) == false){
						echo "error";
						exit();
					}else{
						$token = md5($_SESSION['id_vetp']);
					}
				}else{
					echo "error";
					exit();
				}

				// buscar iva en tabla empresa
				$iva_check = mainModel::ejecutar_consulta_simple("SELECT empresaIva,empresaMoneda FROM empresa ");

				/*---PREPARAR CARGAR---*/
				$datos = [
					"Codproducto" => $codproducto,
					"Cantidad" => $cantidad,
					"Precio" => $precio,
					"Token" => $token
				];
				
				
				/*-Ejecutar Procedimiento Almacenado tabla temporal, detalle_temp -*/
				$query_detalle_temp = ventaModelo::agregar_detalleventa_temp_modelo($datos);
				
				/*----prepara para cargar en VISTA tabla detalles de factura */
				$detalleTabla = '';
			    $sub_total = 0;
			    $iva = 0;
			    $total = 0;
			    $arrayData = array();

			    if($query_detalle_temp->rowCount()>0){
			    	if($iva_check->rowCount()>0){
			    		$info_empresa=$iva_check->fetch();
			    		$iva=$info_empresa['empresaIva'];
			    		$moneda=$info_empresa['empresaMoneda'];
			    	}
			    	// recorre  tabla detalle_temp, realiza calculos
			    	while($data = $query_detalle_temp->fetch()){
			    		$precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
				        $sub_total = round($sub_total + $precioTotal, 2);
				        // $total = round($total + $precioTotal, 2);

				        $detalleTabla .='<tr>
		                      <td >
		                        <span class="text-dark">'.$data['codproducto'].'</span>
		                      </td>
		                      <td>
		                        <span class="text-dark">'.$data['prodserviNombre'].'</span>
		                      </td>
		                      <td class="text-center font-weight-semibold align-middle">'.number_format($data['precio_venta'],2,'.',',')." ".$moneda.'</td>
		                      <td class="text-center">
		                        <!-- <input type="number" class="venta-cantidad" name="" value="0" min="0" id="venta-cantidad"> -->
		                        '.$data['cantidad'].'
		                      </td>
		                      <td class="text-center font-weight-semibold align-middle">'.number_format($precioTotal,2,'.',',')." ".$moneda.'</td>
		                      <td class="text-center align-middle px-0">
		                        <a href="#" class="shop-tooltip close float-none text-danger" title="" data-original-title="Remover" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');">×</a>
		                      </td>
		                    </tr>';
			    	}

				    // ---------- RESULTADOS -------<<<<<<<<<<<<<<<
				    $impuesto   = round($sub_total * ($iva / 100), 2);
					$tl_sniva   = round($sub_total - $impuesto,2 );
					$total    = round($tl_sniva + $impuesto,2);
				    // ---------- RESULTADOS -------<<<<<<<<<<<<<<

					/*------- FORMATO RESULTADOS ---- */
					$impuesto_formt = number_format($impuesto,2,'.',',');
					$tl_sniva_formt = number_format($tl_sniva,2,'.',','); 
					$total_formt = number_format($total,2,'.',',');
				    
				    $detalleTotales = '
					    <div class="text-right mt-4">
		                  <label class="text-muted font-weight-normal">Sub Total: <strong>'.$tl_sniva_formt.'</strong> '.$moneda.'</label>
		                </div>
		                <div class="text-right">
		                  <label class="text-muted font-weight-normal">Iva('.$iva.')%: <strong>'.$impuesto_formt.'</strong> '.$moneda.'</label>
		                </div>
		                <div class="total text-right mt-2">
		                  <label class="text-muted font-weight-normal text-uppercase">Total:
		                    <strong class="ml-2">
		                    <em id="total_txt">'.$total_formt.'</em>
		                    <em> '.$moneda.'</em>
		                    </strong>
		                  </label>
		                </div>';

                    $arrayData['detalle'] = $detalleTabla;
				    $arrayData['totales'] = $detalleTotales;

				    echo json_encode($arrayData);
					//echo json_encode($datos);

			    }else{
			    	echo 'error';
			    }
			    
			} // else empty

		} // FIN agregar detalle temporal

		/** Buscar si hay datos en tabla detalle_temp, se ejecuta al cargar pagina nueva venta
		*   @return: json_encode(array): html con detalles/resultados de tabla, o error
		*/
		public function buscar_productodetalle_temp_controlador(){

			if(empty($_POST['user'])){
			    echo 'error';
			}else{
				
				if(session_start(['name'=>'VETP'])){
					if(isset($_SESSION['id_vetp']) == false){
						echo "error";
						exit();	
					}else{
						$token = md5($_SESSION['id_vetp']);
					}
				}else{
					echo "error";
					exit();
				}

				$query = mainModel::ejecutar_consulta_simple("SELECT tmp.correlativo, tmp.token_user,
			      tmp.cantidad, tmp.precio_venta, p.codProdservi, p.prodserviNombre
			      FROM detalle_temp tmp INNER JOIN productoservicio p ON tmp.codproducto = p.codProdservi
			      where token_user = '$token'");


				// buscar iva
				$iva_check = mainModel::ejecutar_consulta_simple("SELECT empresaIva,empresaMoneda FROM empresa ");

				// inicializar variables
				$detalleTabla = '';
			    $sub_total = 0;
			    $iva = 0;
			    $total = 0;
			    $arrayData = array();

			    if($query->rowCount()>0){
			    	if($iva_check->rowCount()>0){
			    		$info_empresa=$iva_check->fetch();
			    		$iva=$info_empresa['empresaIva'];
			    		$moneda=$info_empresa['empresaMoneda'];
			    	}

			    	while($data = $query->fetch()){
			    		$precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
				        $sub_total = round($sub_total + $precioTotal, 2);
				        $total = round($total + $precioTotal, 2);

				        $detalleTabla .='<tr>
		                      <td>
		                        <span class="text-dark">'.$data['codProdservi'].'</span>
		                      </td>
		                      <td>
		                        <span class="text-dark">'.$data['prodserviNombre'].'</span>
		                      </td>
		                      <td class="text-center font-weight-semibold align-middle">'.number_format($data['precio_venta'],2,'.',',')." ".$moneda.'</td>
		                      <td class="text-center">
		                        <!-- <input type="number" class="venta-cantidad" name="" value="0" min="0" id="venta-cantidad"> -->
		                        '.$data['cantidad'].'
		                      </td>
		                      <td class="text-center font-weight-semibold align-middle">'.number_format($precioTotal,2,'.',',')." ".$moneda.'</td>
		                      <td class="text-center align-middle px-0">
		                        <a href="#" class="shop-tooltip close float-none text-danger" title="" data-original-title="Remover" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');">×</a>
		                      </td>
		                    </tr>';
			    	}
			    
			    	/*----***--RESULTADOS --***----------*/
				    $impuesto   = round($sub_total * ($iva / 100), 2);
					$tl_sniva   = round($sub_total - $impuesto,2 );
					$total    = round($tl_sniva + $impuesto,2);

				    /*----- FORMATO RESULTADOS-----*/
				    $impuesto_formt = number_format($impuesto,2,'.',',');
					$tl_sniva_formt = number_format($tl_sniva,2,'.',','); 
					$total_formt = number_format($total,2,'.',',');
				    

				    $detalleTotales = '
					    <div class="text-right mt-4">
				          <label class="text-muted font-weight-normal">Sub Total: <strong>'.$tl_sniva_formt.'</strong> '.$moneda.'</label>
				        </div>
				        <div class="text-right">
				          <label class="text-muted font-weight-normal">Iva('.$iva.')%: <strong>'.$impuesto_formt.'</strong> '.$moneda.'</label>
				        </div>
				        <div class="total text-right mt-2">
				          <label class="text-muted font-weight-normal text-uppercase">Total:
				            <strong class="ml-2">
				            <em id="total_txt">'.$total_formt.'</em>
				            <em> '.$moneda.'</em></strong>
				          </label>
				        </div>';

                    $arrayData['detalle'] = $detalleTabla;
				    $arrayData['totales'] = $detalleTotales;

				    echo json_encode($arrayData);

			    }else{
			    	echo 'error';
			    }
			    
			} // else empty


		} // FIN buscar_productoservicio_controlador

		/* Eliminar de tabla detalle_temp una fila, producto/servicio
		*  @return:json_encode(array): array con html de detalles/resultados de tabla o error.
		*/
		public function eliminar_productodetalle_temp_controlador(){
			if(empty($_POST['id_detalle'])){
			    echo 'error';
			}else{

				$id_detalle = mainModel::limpiar_cadena($_POST['id_detalle']);
				
				if(session_start(['name'=>'VETP'])){
					if(isset($_SESSION['id_vetp']) == false){
						echo "error";
						exit();	
					}else{
						$token = md5($_SESSION['id_vetp']);
					}
				}else{
					echo "error";
					exit();
				}

				// buscar iva
				$iva_check = mainModel::ejecutar_consulta_simple("SELECT empresaIva,empresaMoneda FROM empresa ");

				/*---- ejecutar procedimiento almacenado modelo >>>>>>>>>>>>>>>--*/
				$query_detalle_temp = ventaModelo::eliminar_detalletemp_procedimiento($id_detalle,$token);

				// inicializar variables
				$detalleTabla = '';
			    $sub_total = 0;
			    $iva = 0;
			    $total = 0;
			    $arrayData = array();

			    if($query_detalle_temp){
			    	// ----------datos en tabla detalle_temp
				    if($query_detalle_temp->rowCount()>0){
				    	if($iva_check->rowCount()>0){
				    		$info_empresa=$iva_check->fetch();
				    		$iva=$info_empresa['empresaIva'];
				    		$moneda=$info_empresa['empresaMoneda'];
				    	}
				    	/*--- DETALLES PRODUCTOS/SERVICIO ---*/
				    	while($data = $query_detalle_temp->fetch()){
				    		$precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
					        $sub_total = round($sub_total + $precioTotal, 2);
					        $total = round($total + $precioTotal, 2);

					        $detalleTabla .='<tr>
			                      <td>
			                        <span class="text-dark">'.$data['codproducto'].'</span>
			                      </td>
			                      <td>
			                        <span class="text-dark">'.$data['prodserviNombre'].'</span>
			                      </td>
			                      <td class="text-center font-weight-semibold align-middle">'.number_format($data['precio_venta'],2,'.',',')." ".$moneda.'</td>
			                      <td class="text-center">
			                        <!-- <input type="number" class="venta-cantidad" name="" value="0" min="0" id="venta-cantidad"> -->
			                        '.$data['cantidad'].'
			                      </td>
			                      <td class="text-center font-weight-semibold align-middle">'.number_format($precioTotal,2,'.',',')." ".$moneda.'</td>
			                      <td class="text-center align-middle px-0">
			                        <a href="#" class="shop-tooltip close float-none text-danger" title="" data-original-title="Remover" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');">×</a>
			                      </td>
			                    </tr>';
				    	}

				    	/*----**------ RESULTADOS -------**-------*/
					    $impuesto   = round($sub_total * ($iva / 100), 2);
						$tl_sniva   = round($sub_total - $impuesto,2 );
						$total    = round($tl_sniva + $impuesto,2);

					    /*------- FORMATO RESULTADOS ---- */
						$impuesto_formt = number_format($impuesto,2,'.',',');
						$tl_sniva_formt = number_format($tl_sniva,2,'.',','); 
						$total_formt = number_format($total,2,'.',',');
					    
					    $detalleTotales = '
						    <div class="text-right mt-4">
			                  <label class="text-muted font-weight-normal">Sub Total: <strong>'.$tl_sniva_formt.'</strong> '.$moneda.'</label>
			                </div>
			                <div class="text-right">
			                  <label class="text-muted font-weight-normal">Iva('.$iva.')%: <strong>'.$impuesto_formt.'</strong> '.$moneda.'</label>
			                </div>
			                <div class="total text-right mt-2">
			                  <label class="text-muted font-weight-normal text-uppercase">Total:
			                    <strong class="ml-2">
			                    <em id="total_txt">'.$total_formt.'</em>
			                    <em> '.$moneda.'</em></strong>
			                  </label>
			                </div>';

	                    $arrayData['detalle'] = $detalleTabla;
					    $arrayData['totales'] = $detalleTotales;
					    $arrayData['Alerta'] = "exito";
					    echo json_encode($arrayData);

				    }else{
				    	$arrayData['Alerta'] = "exitoo";
			    		echo json_encode($arrayData);	
				    }

			    }else{
			    	echo 'error';
			    }
			    
			    
			} // else empty

		}

		/* Eliminar detalle de un token_user, tabla detalle temporal
		*  @return: respuesta servidor DB, a alerta json_decode(array)
		*/
		public function anular_productodetalle_temp_controlador(){
			
			if(session_start(['name'=>'VETP'])){
				if(isset($_SESSION['id_vetp']) == false){	
					$alerta_simple=[
						"Alerta"=>"warning",
						"Titulo"=>"Fallo iniciar session",
						"Texto"=>"Fallo a iniciar session"
					];
					echo json_encode($alerta_simple);
					exit();

				}else{
					$token = md5($_SESSION['id_vetp']);
				}
			}else{
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Fallo iniciar session",
					"Texto"=>"Fallo a iniciar session"
				];
				echo json_encode($alerta_simple);
				exit();
			}

			// ----- Instancia a modelo ----->
			$eliminar_temp=ventaModelo::anular_productodetalle_temp_modelo($token);
			if($eliminar_temp->rowCount()>=1){
				$alerta_simple=[
					"Alerta"=>"success",
					"Titulo"=>"Venta Anulada",
					"Texto"=>"Anulada con exito"
				];
			}else{
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Fallo al anular",
					"Texto"=>"No se pudo anular venta"
				];
			}
			echo json_encode($alerta_simple);


		} // fin 

		/* Procesar la venta
		*  @return: json_decode(array_alerta), respuesta de servidor DB
		*/
		public function guardar_venta_controlador(){

			$cliente=mainModel::limpiar_cadena($_POST['dnicliente']);
			$tipo_pago=mainModel::limpiar_cadena($_POST['tipopago']);
			$tipo_comprobante=mainModel::limpiar_cadena($_POST['tipo_comprobante']);
			$num_comprobante=mainModel::limpiar_cadena($_POST['num_comprobante']);
			$serie_comprobante=mainModel::limpiar_cadena($_POST['serie_comprobante']);
			
			if($cliente=="" || $tipo_pago==""){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Campos en Blancos",
					"Texto"=>"Debe llenar todos los campos"
				];
				echo json_encode($alerta_simple);
				exit();
			}

			if($tipo_pago!="Efectivo" && $tipo_pago!="Transferencia" && $tipo_pago!="Visa" && $tipo_pago!="Yape" && $tipo_pago!="Plin"){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Tipo de pago no valido"
				];
				echo json_encode($alerta_simple);
				exit();
			}

			/*----- Comprobar DNI cliente en DB  ----- */
			$check_dni=mainModel::ejecutar_consulta_simple("SELECT clienteDniCedula FROM cliente WHERE clienteDniCedula='$cliente'");
			if($check_dni->rowCount()<=0){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El cliente no se encuentra registrado en el sistema"
				];
				echo json_encode($alerta_simple);
				exit();
			}


			if(session_start(['name'=>'VETP'])){
				if(isset($_SESSION['id_vetp']) == false){
					$alerta_simple=[
						"Alerta"=>"warning",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Fallo a iniciar variable de session"
					];
					echo json_encode($alerta_simple);
					exit();

				}else{
					$token = md5($_SESSION['id_vetp']);
					$usuario = $_SESSION['id_vetp'];
				}
			}else{
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Fallo a iniciar session"
				];
				echo json_encode($alerta_simple);
				exit();
			}
			// verifica si hay productos del usuario
			$query = mainModel::ejecutar_consulta_simple("SELECT * FROM detalle_temp WHERE token_user='$token'");
			
			$result = $query->rowCount();

			/*---PREPARAR CARGAR---*/
			$datos = [
				"Usuario" => $usuario,
				"DniCliente" => $cliente,
				"Token" => $token,
				"Tipo" => $tipo_pago,
			];

			// ---- ---- instancia a modelo --------------------->
			if($result>0){
				$guardar_venta=ventaModelo::agregar_venta_modelo($datos);
				if($guardar_venta->rowCount() > 0){
					$data = $guardar_venta->fetch();
					$idVenta = $data['idVenta'];

					$alerta_simple=[
						"Alerta"=>"success",
						"Titulo"=>"Venta Procesada",
						"Texto"=>"La venta fue procesada con exito",
						"Data"=>$data
					];

					ventaModelo::update_venta_comprobante($idVenta,$tipo_comprobante, $num_comprobante, $serie_comprobante);
					
				}else{
					$alerta_simple=[
						"Alerta"=>"warning",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No hemos podido procesar la venta",
					];
				}
				echo json_encode($alerta_simple);

			}else{
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Tabla detalle no posee productos/Servicios"
				];
				echo json_encode($alerta_simple);
				exit();
			}

			
		} // fin guardar_venta_controlador

		public function consecutivotipofactura($tipo){


			$query = "SELECT num_comprobante as 'consecutivo' FROM `venta` WHERE tipo_comprobante = '$tipo' ORDER by idVenta DESC LIMIT 0,1";
			
			$conexion = mainModel::conectar();
			$datos = $conexion->query($query);
			$data = array();

				if($datos->rowCount() > 0)
				{
					$row = $datos->fetch();
				 
					if ($row){
						$data = array(
							"consecutivo" => str_pad(intval($row['consecutivo']) + 1, 8, '0', STR_PAD_LEFT)
						);
					}else{
						$data = array(
							"consecutivo"=> 1
							);
					}
					
					
					echo json_encode($data);
				}else{
					$data = array(
						"consecutivo"=> 1
						);
				 echo json_encode($data);
				 
				}
			
		} 

	} // FIN CLASS