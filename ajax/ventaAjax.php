<?php 
 	 $peticionAjax=true;
 	 require_once "../config/APP.php";

 	 if(isset($_POST['valorBusqueda']) || isset($_POST['addProductoDetalle']) || isset($_POST['searchForDetalle']) || isset($_POST['delProductoDetalle']) || isset($_POST['anularVenta']) || isset($_POST['procesarVenta']) || isset($_POST['factura_n_del']) || isset($_POST['obtenerconsecutivo'])){
 	 	/*-------- Instancia al controlador ----------------*/
 	 	require_once "../controladores/ventaControlador.php";
 	 	$ins_venta = new ventaControlador();

 	 	/*-------- Eliminar una venta ----------------*/
 	 	if(isset($_POST['factura_n_del']) && isset($_POST['privilegio_user']) ){
 	 		echo $ins_venta->eliminar_venta_controlador();
 	 	}
 
 	 	/*------------------DETALLE VENTA -------------------*/
 	 	/*-------- Buscar productos/servicio, click input key ----------------*/
 	 	if(isset($_POST['valorBusqueda'])){
 	 		echo $ins_venta->buscar_productservi_controlador();
 	 	}

		//Obtener consecutivo factura
 	 	if(isset($_POST['obtenerconsecutivo'])){
			echo $ins_venta->consecutivotipofactura($_POST['tipo']);
		}

 	 	// DETALE TEMPORAL
 	 	if(isset($_POST['addProductoDetalle'])){
 	 		echo $ins_venta->agregar_productodetalle_temp_controlador();
 	 	}
 	 	// carga detalle temporal
 	 	if(isset($_POST['searchForDetalle'])){
 	 		echo $ins_venta->buscar_productodetalle_temp_controlador();
 	 	}
 	 	// eliminar fila de tabla detalle venta
 	 	if(isset($_POST['delProductoDetalle'])){
 	 		echo $ins_venta->eliminar_productodetalle_temp_controlador();
 	 	}
 	 	/*----anular venta tabla temporal---*/
 	 	if(isset($_POST['anularVenta'])){
 	 		echo $ins_venta->anular_productodetalle_temp_controlador();
 	 	}

 	 	/*---------------- PROCESAR VENTA -----------------------------*/
 	 	if(isset($_POST['procesarVenta'])){
 	 		echo $ins_venta->guardar_venta_controlador();
 	 	}

 	 }else{
 	 	//no accede desde url
 	 	session_start(['name'=>'VETP']);
 	 	session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
 	 }