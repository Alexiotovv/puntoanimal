<?php 
 	 $peticionAjax=true;
 	 require_once "../config/APP.php";

 	 if(isset($_POST['historial_codigo_reg']) || isset($_POST['limit']) || isset($_POST['historia_id_dele']) || isset($_POST['adjunto_id_dele']) || isset($_POST['adjunto_codhistoria_up']) || isset($_POST['adj_codhistorial']) || isset($_POST['hist_id_edit']) || isset($_POST['inf_codhistorial']) || isset($_POST['historia_cod_edit']) ){
 	 	/*-------- Instancia al controlador ----------------*/
 	 	require_once "../controladores/historialControlador.php";
 	 	$ins_historial = new historialControlador();
 
 	 	/*-------- Agregar historial ----------------*/
 	 	if(isset($_POST['historial_codigo_reg'])){
 	 		echo $ins_historial->agregar_historia_controlador();
 	 	}

 	 	/*-------- Eliminar historial ----------------*/
 	 	if(isset($_POST['historia_id_dele'])  ){
 	 		echo $ins_historial->eliminar_historia_controlador();
 	 	}
 	 	/*---- Mostrar historias perfil mascota */
        if(isset($_POST['limit']) && isset($_POST['offset'])){
            echo $ins_historial->datos_perfil_mascota_controlador();
        }

        /*---- Buscar historia editar, modal llamada: load-more.js(277) ----*/
        if(isset($_POST['hist_id_edit'])){
            $data = $ins_historial->datos_historia_controlador("Unico",$_POST['hist_id_edit']);
            $data = $data->fetch();
            echo json_encode($data);
        }
        /*----- recargar sesion info historia motivo,tratami... -------*/
 	 	if(isset($_POST['inf_codhistorial'])){
 	 		echo $ins_historial->datos_infohistoria_controlador();
 	 	}

        /*------ Actualizar historia session info gen. motivo,trata... ----------*/
        if(isset($_POST['historia_cod_edit'])){
            echo $ins_historial->actualizar_historia_controlador();
        }

 	 	/*-------------------session adjuntos-----------------------------*/
 	 	/*-------- Eliminar historial adjuntos(1) ----------------*/
 	 	if(isset($_POST['adjunto_id_dele'])  ){
 	 		echo $ins_historial->eliminar_historia_adjunto_controlador();
 	 	}
 	 	/*-------- Agregar archivo adjunto perfil mascota ----------------*/
 	 	if(isset($_POST['adjunto_codhistoria_up'])){
 	 		echo $ins_historial->agregar_historia_adjunto_controlador();
 	 	}
 	 	// 
 	 	/*----- recargar archivos adjuntos -------*/
 	 	if(isset($_POST['adj_codhistorial'])){
 	 		echo $ins_historial->datos_perfil_adjuntos_controlador();
 	 	}

 	 }else{
 	 	//no accede desde url
 	 	session_start(['name'=>'VETP']);
 	 	session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
 	 }