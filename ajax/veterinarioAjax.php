<?php 
 	 $peticionAjax=true;
 	 require_once "../config/APP.php";

 	 if(isset($_POST['veterinario_dni_reg']) || isset($_POST['valorBuscar']) || isset($_POST['veterinario_dni_del']) || isset($_POST['usuario_id_up']) || isset($_POST['limit']) || isset($_POST['id_Venta'])){
 	 	/*-------- Instancia al controlador ----------------*/
 	 	require_once "../controladores/veterinarioControlador.php";
 	 	$ins_veterinario = new veterinarioControlador();
 
 	 	/*-------- Agregar veterinario ----------------*/
 	 	if(isset($_POST['veterinario_dni_reg']) && isset($_POST['veterinario_nombre_reg']) ){
 	 		// mostrar en pantalla
 	 		echo $ins_veterinario->agregar_veterinario_controlador();
 	 	}

 	 	/*-------- Eliminar un veterinario ----------------*/
 	 	if(isset($_POST['veterinario_dni_del']) && isset($_POST['privilegio_user']) ){
 	 		echo $ins_veterinario->eliminar_veterinario_controlador();
 	 	}

 	 	/*-------- Actualizar veterinario ----------------*/
 	 	if(isset($_POST['usuario_id_up'])){
 	 		echo $ins_veterinario->actualizar_veterinario_controlador();
 	 	}

 	 	/*-------- Buscar dueño click select input ----------------*/
 	 	if(isset($_POST['valorBuscar'])){
 	 		// mostrar en pantalla
 	 		echo $ins_veterinario->buscar_veterinario_controlador();
 	 	}

 	 	/*--------------PERFIL CLIENTE ---------------*/
 	 	/*---- Mostrar historial facturado */
        /*if(isset($_POST['limit']) && isset($_POST['offset'])){
            echo $ins_veterinario->datos_perfil_veterinario_facturas_controlador();
        }*/
        /*---- detalle de factura modal -----*/
        /*if(isset($_POST['id_Venta'])){
        	echo $ins_veterinario->lista_detalle_venta_perfilveterinario();
 	 	}*/

 	 }else{
 	 	//no accede desde url
 	 	session_start(['name'=>'VETP']);
 	 	session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
 	 }