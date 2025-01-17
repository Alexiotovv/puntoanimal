<?php 
 	 $peticionAjax=true;
 	 require_once "../config/APP.php";
 
 	 if(isset($_POST['prodservi_nombre_reg']) || isset($_POST['inventario_id_del']) || isset($_POST['inventario_cod_up']) || isset($_POST['cod_prodstock']) || isset($_POST['cod_producto_stockup'])){
 	 	/*-------- Instancia al controlador ----------------*/
 	 	require_once "../controladores/inventarioControlador.php";
 	 	$ins_inventario = new inventarioControlador();
 
 	 	/*-------- Agregar inventario ----------------*/
 	 	if(isset($_POST['prodservi_nombre_reg'])){
 	 		echo $ins_inventario->agregar_inventario_controlador();
 	 	}

 	 	/*-------- Actualizar inventario ----------------*/
 	 	if(isset($_POST['inventario_cod_up'])){
 	 		echo $ins_inventario->actualizar_inventario_controlador();
 	 	}

 	 	/*-------- Eliminar inventario ----------------*/
 	 	if(isset($_POST['inventario_id_del']) && isset($_POST['privilegio_user']) ){
 	 		echo $ins_inventario->eliminar_inventario_controlador();
 	 	}

 	 	/*---- Agregar Stock producto ------*/
 	 	if(isset($_POST['cod_producto_stockup'])){
 	 		echo $ins_inventario->actualizar_stockprod_controlador();
 	 	}

 	 	/*------ Buscar cod producto ,agregar stock, mostar en modal -------------*/
 	 	if(isset($_POST['cod_prodstock'])){
 	 		$data = $ins_inventario->datos_inventario_controlador("Unico",$_POST['cod_prodstock']);
 	 		$data = $data->fetch();
 	 		$jsonstring = json_encode($data);
 	 		echo $jsonstring;
 	 	}
 	 	

 	 }else{
 	 	//no accede desde url
 	 	session_start(['name'=>'VETP']);
 	 	session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
 	 }