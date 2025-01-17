<?php 
 	 $peticionAjax=true;
 	 require_once "../config/APP.php";

 	 if(isset($_POST['mascota_nombre_reg']) || isset($_POST['mascota_cod_del']) || isset($_POST['mascota_codigo_up']) || isset($_POST['id_masc']) || isset($_POST['estvalor']) ){
 	 	/*-------- Instancia al controlador ----------------*/
 	 	require_once "../controladores/mascotaControlador.php";
 	 	$ins_mascota = new mascotaControlador();
 
 	 	/*-------- Agregar mascota ----------------*/
 	 	if(isset($_POST['mascota_nombre_reg'])){
 	 		// mostrar en pantalla
 	 		echo $ins_mascota->agregar_mascota_controlador();
 	 	}
 	 	/*-------- Eliminar una mascota ----------------*/
 	 	if(isset($_POST['mascota_cod_del']) && isset($_POST['privilegio_user']) ){
 	 		echo $ins_mascota->eliminar_mascota_controlador();
 	 	}
 	 	/*-------- Actualizar un mascota ----------------*/
 	 	if(isset($_POST['mascota_codigo_up'])){
 	 		echo $ins_mascota->actualizar_mascota_controlador();
 	 	}
 	 	/*-------- Buscar mascota mostrar modal perfil cliente ----------------*/
 	 	if(isset($_POST['id_masc'])){
 	 		$data = $ins_mascota->datos_mascota_controlador("Perfil",$_POST['id_masc']);
 	 		$data = $data->fetch();
 	 		$jsonstring = json_encode($data);
 	 		echo $jsonstring;
 	 	}

 	 	/*----- Estadisticas: especie,raza,sexo ----*/
 	 	if(isset($_POST['estvalor'])){
 	 		$result = $ins_mascota->datos_mascota_controlador($_POST['estvalor'],0);
 	 		
			$data = array();
			foreach ($result as $row) {
				$data[] = $row;
			}

 	 		echo json_encode($data);
 	 	}

 	 }else{
 	 	//no accede desde url
 	 	session_start(['name'=>'VETP']);
 	 	session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
 	 }
