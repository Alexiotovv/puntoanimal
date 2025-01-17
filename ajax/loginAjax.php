<?php 
 	 $peticionAjax=true;
 	 require_once "../config/APP.php";
 
 	 if(isset($_POST['token'])){
 	 	
 	 	/*-------- Instancia al controlador ----------------*/
 	 	require_once "../controladores/loginControlador.php";
 	 	$ins_login = new loginControlador();
 	 	echo $ins_login->cierre_sesion_controlador();
 	 }else{
 	 	//no accede desde url,cerrar sesion
 	 	session_start(['name'=>'VETP']);
 	 	session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
 	 }
?>