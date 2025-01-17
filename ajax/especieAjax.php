 <?php 
 	 $peticionAjax=true;
 	 require_once "../config/APP.php";

 	 if(isset($_POST['especie_nombre_reg']) || isset($_POST['especie_id_del']) || isset($_POST['id_especie']) || isset($_POST['especie_id_edit'])){
 	 	/*-------- Instancia al controlador ----------------*/
 	 	require_once "../controladores/especieControlador.php";
 	 	$ins_especie = new especieControlador();
 
 	 	/*-------- Agregar especie ----------------*/
 	 	if(isset($_POST['especie_nombre_reg'])){
 	 		echo $ins_especie->agregar_especie_controlador();
 	 	}

 	 	/*-------- Eliminar especie ----------------*/
 	 	if(isset($_POST['especie_id_del']) && isset($_POST['privilegio_user']) ){
 	 		echo $ins_especie->eliminar_especie_controlador();
 	 	}

 	 	/*-------- Buscar especie a editar mostar en modal ----------------*/
 	 	if(isset($_POST['id_especie'])  ){
 	 		$data = $ins_especie->buscar_especie_controlador("Unico");
 	 		$data = $data->fetch();
 	 		$jsonstring = json_encode($data);
 	 		echo $jsonstring;
 	 	}

 	 	/*-------- Actualizar especie ----------------*/
 	 	if(isset($_POST['especie_id_edit'])){
 	 		echo $ins_especie->actualizar_especie_controlador();
 	 	}


 	 }else{
 	 	//no accede desde url
 	 	session_start(['name'=>'VETP']);
 	 	session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
 	 }