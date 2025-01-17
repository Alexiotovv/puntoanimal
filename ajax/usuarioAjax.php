 <?php 
 	 $peticionAjax=true;
 	 require_once "../config/APP.php";

 	 if(isset($_POST['usuario_dni_reg']) || isset($_POST['usuario_id_del']) || isset($_POST['usuario_id_up']) ){
 	 	/*-------- Instancia al controlador ----------------*/
 	 	require_once "../controladores/usuarioControlador.php";
 	 	$ins_usuario = new usuarioControlador();
 
 	 	/*-------- Agregar usuario ----------------*/
 	 	if(isset($_POST['usuario_dni_reg']) && isset($_POST['usuario_nombre_reg'])){
 	 		// mostrar en pantalla
 	 		echo $ins_usuario->agregar_usuario_controlador();
 	 	}

 	 	/*-------- Eliminar usuario ----------------*/
 	 	if(isset($_POST['usuario_id_del']) && isset($_POST['privilegio_user']) ){
 	 		echo $ins_usuario->eliminar_usuario_controlador();
 	 	}

 	 	/*-------- Actualizar un usuario ----------------*/
 	 	if(isset($_POST['usuario_id_up']) && isset($_POST['usuario_dni_edit']) ){
 	 		echo $ins_usuario->actualizar_usuario_controlador();
 	 	}


 	 }else{
 	 	//no accede desde url
 	 	session_start(['name'=>'VETP']);
 	 	session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
 	 }
