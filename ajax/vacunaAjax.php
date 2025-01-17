 <?php 
 	 $peticionAjax=true;
 	 require_once "../config/APP.php";

 	 if(isset($_POST['vacuna_nombre_reg']) || isset($_POST['vacuna_idvacuna_up']) || isset($_POST['vacuna_id_edit']) || isset($_POST['idespecielista']) || isset($_POST['vacuna_id_del']) ){
 	 	/*-------- Instancia al controlador ----------------*/
 	 	require_once "../controladores/vacunaControlador.php";
 	 	$ins_vacuna = new vacunaControlador();
 
 	 	/*-------- Agregar vacuna ----------------*/
 	 	if(isset($_POST['vacuna_nombre_reg'])){
 	 		echo $ins_vacuna->agregar_vacuna_controlador();
 	 	}

 	 	/*-------- Eliminar vacuna ----------------*/
 	 	if(isset($_POST['vacuna_id_del']) ){
 	 		echo $ins_vacuna->eliminar_vacuna_controlador();
 	 	}

		/*-------- Buscar vacuna a editar mostar en form ----------------*/
        if(isset($_POST['vacuna_id_edit'])){
            $data = $ins_vacuna->datos_vacuna_controlador("Unico",$_POST['vacuna_id_edit']);
            $data = $data->fetch();
            
            $jsonstring = json_encode($data);
            echo $jsonstring;
        }

        /*-------- Actualizar vacuna ----------------*/
        if(isset($_POST['vacuna_idvacuna_up']) && isset($_POST['vacuna_especie_up']) && isset($_POST['vacuna_nombre_up']) ){
            echo $ins_vacuna->actualizar_vacuna_controlador();
        }

        /*-------- Buscar lista vacuna recargar seccion ----------------*/
        if(isset($_POST['idespecielista'])){
            $data = $ins_vacuna->lista_vacuna_recargar_controlador();
            echo $data;
        }

 	 }else{
 	 	//no accede desde url
 	 	session_start(['name'=>'VETP']);
 	 	session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
 	 }