<?php 
 	 $peticionAjax=true;
 	 require_once "../config/APP.php";

 	 if(isset($_POST['codmascota']) || isset($_POST['historia_vacuna_idvacuna_reg']) || isset($_POST['histv_id_edit']) || isset($_POST['vacuna_idhistoria_up']) || isset($_POST['historiav_id_dele'])){
 	 	/*-------- Instancia al controlador ----------------*/
 	 	require_once "../controladores/vacunaHistorialControlador.php";
 	 	$ins_historial = new vacunaHistorialControlador();
 
 	 	/*-------- Agregar historial vacuna ----------------*/
 	 	if(isset($_POST['historia_vacuna_idvacuna_reg'])){
 	 		echo $ins_historial->agregar_vacuna_historia_controlador();
 	 	}

 	 	/*---- Buscar historia vacuna editar, modal llamada: load-more.js ----*/
        if(isset($_POST['histv_id_edit'])){
            $data = $ins_historial->datos_vacuna_historia_controlador("Unico",$_POST['histv_id_edit']);
            $data = $data->fetch();
            echo json_encode($data);
        }

 	 	/*-------- Eliminar historial ----------------*/
 	 	if(isset($_POST['historiav_id_dele'])){
 	 		echo $ins_historial->eliminar_vacuna_historia_controlador();
 	 	}

 	 	/*---- Mostrar historias vacunas perfil mascota */
        if(isset($_POST['codmascota'])){
            echo $ins_historial->datos_perfil_vacuna_historia_controlador();
        }

        /*------ Actualizar historia session info gen. motivo,trata... ----------*/
        if(isset($_POST['vacuna_idhistoria_up'])){
            echo $ins_historial->actualizar_vacuna_historia_controlador();
        }

 	 }else{
 	 	//no accede desde url
 	 	session_start(['name'=>'VETP']);
 	 	session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
 	 }