<?php 

	$peticionAjax=true;
 	 require_once "../config/APP.php";

 	 if(isset($_POST['cita_fecha_reg']) || isset($_POST['dni_cliente']) || isset($_POST['cita_id_del']) || isset($_POST['cita_codigo_up']) ){
 	 	/*-------- Instancia al controlador ----------------*/
 	 	require_once "../controladores/citaControlador.php";
 	 	$ins_cita = new citaControlador();

 	 	/*-------- Agregar cita ----------------*/
 	 	if(isset($_POST['cita_fecha_reg']) && isset($_POST['cita_hora_reg']) ){
 	 		echo $ins_cita->agregar_cita_controlador();
 	 	}

 	 	/*-------- Buscar mascota en select depende de cliente ----------------*/
 	 	if(isset($_POST['dni_cliente'])){
 	 		$data = $ins_cita->buscar_paciente_mascota_controlador();
 	 		echo $data;
 	 	}
 	 	/*-------- Eliminar cita --------------*/
 	 	if(isset($_POST['cita_id_del']) && isset($_POST['privilegio_user'])){
 	 		echo $ins_cita->eliminar_cita_controlador();
 	 	}

 	 	/*-------- Actualizar cita ----------------*/
 	 	if(isset($_POST['cita_codigo_up'])){
 	 		echo $ins_cita->actualizar_cita_controlador();
 	 	}

 	 }else{
 	 	//no accede desde url
 	 	session_start(['name'=>'VETP']);
 	 	session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
 	 }