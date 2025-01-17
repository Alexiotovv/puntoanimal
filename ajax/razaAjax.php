 <?php 
 	 $peticionAjax=true;
 	 require_once "../config/APP.php";

 	 if(isset($_POST['raza_nombre_reg']) || isset($_POST['raza_id_del']) || isset($_POST['id_raza']) || isset($_POST['id_especie']) || isset($_POST['raza_id_edit']) ){
 	 	/*-------- Instancia al controlador ----------------*/
 	 	require_once "../controladores/razaControlador.php";
 	 	$ins_raza = new razaControlador();
 
 	 	/*-------- Agregar raza ----------------*/
 	 	if(isset($_POST['raza_nombre_reg'])){
 	 		echo $ins_raza->agregar_raza_controlador();
 	 	}

 	 	/*-------- Eliminar raza ----------------*/
 	 	if(isset($_POST['raza_id_del']) && isset($_POST['privilegio_user']) ){
 	 		echo $ins_raza->eliminar_raza_controlador();
 	 	}

 	 	/*-------- Actualizar raza ----------------*/
 	 	if(isset($_POST['raza_id_edit'])){
 	 		echo $ins_raza->actualizar_raza_controlador();
 	 	}

 	 	/*-------- Buscar raza a editar mostar en modal ----------------*/
 	 	if(isset($_POST['id_raza'])  ){
 	 		$data = $ins_raza->buscar_raza_controlador("Unico");
 	 		$data = $data->fetch();
 	 		
 	 		$jsonstring = json_encode($data);
 	 		echo $jsonstring;
 	 		 
 	 	}
 	 	/*-------- Buscar raza mostrar en select depende de especie -------*/
 	 	if(isset($_POST['id_especie'])){
 	 		
 	 		$data = $ins_raza->buscar_raza_controlador("Select");
 	 		
 	 		$espe="";
 	 		if($data->rowCount()>=1){
 	 			while($rowE=$data->fetch()){
                    $espe.=	 '<option value="'.$rowE['idRaza'].'">'.$rowE['razaNombre'].'</option>';
                  }
 	 		}else{
 	 			$espe.=	'<option value="0">Especie no posee raza/s registradas</option>';
 	 		}
 	 		
 	 		echo $espe; 
 	 	}



 	 }else{
 	 	//no accede desde url
 	 	session_start(['name'=>'VETP']);
 	 	session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
 	 }