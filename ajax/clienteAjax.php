 <?php 
 	 $peticionAjax=true;
 	 require_once "../config/APP.php";

 	 if(isset($_POST['cliente_dni_reg']) || isset($_POST['valorBuscar']) || isset($_POST['cliente_dni_del']) || isset($_POST['usuario_id_up']) || isset($_POST['limit']) || isset($_POST['id_Venta'])){
 	 	/*-------- Instancia al controlador ----------------*/
 	 	require_once "../controladores/clienteControlador.php";
 	 	$ins_cliente = new clienteControlador();
 
 	 	/*-------- Agregar cliente ----------------*/
 	 	if(isset($_POST['cliente_dni_reg']) && isset($_POST['cliente_nombre_reg']) ){
 	 		// mostrar en pantalla
 	 		echo $ins_cliente->agregar_cliente_controlador();
 	 	}

 	 	/*-------- Eliminar un cliente ----------------*/
 	 	if(isset($_POST['cliente_dni_del']) && isset($_POST['privilegio_user']) ){
 	 		echo $ins_cliente->eliminar_cliente_controlador();
 	 	}

 	 	/*-------- Actualizar cliente ----------------*/
 	 	if(isset($_POST['usuario_id_up'])){
 	 		echo $ins_cliente->actualizar_cliente_controlador();
 	 	}

 	 	/*-------- Buscar dueño click select input ----------------*/
 	 	if(isset($_POST['valorBuscar'])){
 	 		// mostrar en pantalla
 	 		echo $ins_cliente->buscar_dueno_controlador();
 	 	}
 	 	/*--------------PERFIL CLIENTE ---------------*/
 	 	/*---- Mostrar historial facturado */
        if(isset($_POST['limit']) && isset($_POST['offset'])){
            echo $ins_cliente->datos_perfil_cliente_facturas_controlador();
        }
        /*---- detalle de factura modal -----*/
        if(isset($_POST['id_Venta'])){
        	echo $ins_cliente->lista_detalle_venta_perfilCliente();
 	 	}

 	 }else{
 	 	//no accede desde url
 	 	session_start(['name'=>'VETP']);
 	 	session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
 	 }