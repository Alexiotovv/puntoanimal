<?php 

 	if ($peticionAjax) {
	  require_once "../modelos/empresaModelo.php";
	} else {
	  require_once "./modelos/empresaModelo.php";
	}

	/** Realizar un solo registro de empresa
	 * hereda de empresaModelo, a la vez de mainModel
	 */
	class empresaControlador extends empresaModelo{

		/* Agregar empresa: limpiar entradas, validar, enviar a modelo
		*  @return: json_encode: alerta con respuesta de servidor y validacioens
		*/
		public function agregar_empresa_controlador(){

			$rif=mainModel::limpiar_cadena($_POST['empresa_rif_reg']);
			$nombre=mainModel::limpiar_cadena($_POST['empresa_nombre_reg']);
			$direccion=mainModel::limpiar_cadena($_POST['empresa_direccion_reg']);
			$telefono=mainModel::limpiar_cadena($_POST['empresa_telefono_reg']);
			$email=mainModel::limpiar_cadena($_POST['empresa_email_reg']);
			$moneda=mainModel::limpiar_cadena($_POST['empresa_moneda_reg']);
			$iva=mainModel::limpiar_cadena($_POST['empresa_iva_reg']);
			$logo=$_FILES['empresa_logo_reg'];

			/*----Campos vacios---------*/
			if($rif=="" || $nombre=="" || $direccion=="" || $telefono=="" || $email=="" || $moneda=="" || $iva==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			if($logo['tmp_name']==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Seleccionar logo de la empresa",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*------ validar entrada de datos ---------*/
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
 
			// ----validar FOTO seleccionado y guardar url---->
			$ruta_foto="";
			if(mainModel::verificar_foto($logo)){
				/*--- guardar file foto --*/
				$ruta_foto = "../adjuntos/logo-empresa/".basename($logo["name"]);
				move_uploaded_file($logo["tmp_name"], $ruta_foto);
				// ruta a guardar en base datos
				$ruta_foto_db = "adjuntos/logo-empresa/".basename($logo["name"]);
				
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

			/*---PREPARAR CARGAR---*/
			$datos = [
				"Rif" => $rif,
				"Nombre" => $nombre,
				"Direccion" => $direccion,
				"Telefono" => $telefono,
				"Email" => $email,
				"Moneda" => $moneda,
				"Iva" => $iva,
				"FotoUrl" => $ruta_foto_db
			];
			// instancia
			$guardar_empresa=empresaModelo::agregar_empresa_modelo($datos);
			
			if($guardar_empresa->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Empresa registrada",
					"Texto"=>"Los datos fueron registrados",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar la empresa",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);

		}// agregar empresaControlador
		
		/* Optener datos de empresa guardada
		*  @param: tipo consulta unico
		*  @return: respuesta de consulta sql
		*/
		public function datos_empresa_controlador($tipo){
 			$tipo=mainModel::limpiar_cadena($tipo);
 			return empresaModelo::datos_empresa_modelo($tipo);
 		}

 		/* Editar Empresa, validar campos, guardar foto file url.
 		*  @return: json_encode(array): alerta con respuesta de servidor y validaciones
		*/
		public function actualizar_empresa_controlador(){
			$id=mainModel::limpiar_cadena($_POST['id_empresa_up']);

			// camprobar id de empresa en db
			$check_empresa=mainModel::ejecutar_consulta_simple("SELECT * FROM empresa WHERE idempresa='$id'");

			if($check_empresa->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La empresa no se encuentra registrada en el sistema, intente nuevamente",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}else{
				$campos=$check_empresa->fetch();
			}

			$rif=mainModel::limpiar_cadena($_POST['empresa_rif_edit']);
			$nombre=mainModel::limpiar_cadena($_POST['empresa_nombre_edit']);
			$telefono=mainModel::limpiar_cadena($_POST['empresa_telefono_edit']);
			$email=mainModel::limpiar_cadena($_POST['empresa_email_edit']);
			$direccion=mainModel::limpiar_cadena($_POST['empresa_direccion_edit']);
			$moneda=mainModel::limpiar_cadena($_POST['empresa_moneda_edit']);
			$iva=mainModel::limpiar_cadena($_POST['empresa_iva_edit']);
			$foto=$_FILES['empresa_logo_edit'];
			
			/*------- si cambia de logo :VALIDAR CAMPO FOTO -----*/
			if($foto['tmp_name']!=""){
				if(mainModel::verificar_foto($foto)){

					/*--- guardar file foto --*/
					$ruta_foto = "../adjuntos/logo-empresa/".basename($foto["name"]);
					move_uploaded_file($foto["tmp_name"], $ruta_foto);
					$ruta_foto_db = "adjuntos/logo-empresa/".basename($foto["name"]);

					// eliminar foto actual
					unlink("../".$campos['empresaFotoUrl']);	
							
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
				$ruta_foto_db=$campos['empresaFotoUrl'];
			}

			/*----Campos vacios---------*/
			if($rif=="" || $nombre=="" || $telefono=="" || $email=="" || $direccion=="" || $moneda=="" || $iva==""){
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

			/*---PREPARAR CARGAR---*/
			$datos = [
				"Rif" => $rif,
				"Nombre" => $nombre,
				"Direccion" => $direccion,
				"Telefono" => $telefono,
				"Email" => $email,
				"Moneda" => $moneda,
				"Iva" => $iva,
				"FotoUrl" => $ruta_foto_db,
				"Id" => $id
			];
			// instancia
			$actualizar_empresa=empresaModelo::actualizar_empresa_modelo($datos);
			
			if($actualizar_empresa->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Empresa Actualizada",
					"Texto"=>"Los datos fueron actualizados",
					"Tipo"=>"success"
				];
			
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar la empresa",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);

		} // fin actualizar_empresa_controlador

	}