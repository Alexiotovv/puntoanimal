<?php 

	session_start(['name'=>'VETP']);
	require_once "../config/APP.php";

	if(isset($_POST['busqueda_inicial']) || isset($_POST['mostrar_todos']) || isset($_POST['fecha_inicio']) || isset($_POST['fecha_final'])){

		$data_url=[
			"usuario"=>"listaUsuario",
			"cliente"=>"listaCliente",
			"mascota"=>"listaMascota",
			"especie"=>"listaEspecie",
			"raza"=>"listaRaza",
			"inventario"=>"listaProdservi",
			"venta"=>"listaVenta",
			"cita"=>"listaCita",
			"cita_hoy"=>"listaCitaHoy",
			"veterinario"=>"listaVeterinario"
		];
		if(isset($_POST['modulo'])){
			$modulo=$_POST['modulo'];
			if(!isset($data_url[$modulo])){
				$alerta_simple=[
					"Alerta"=>"warning",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No podemos continuar la busqueda debido a un error"
				];
				echo json_encode($alerta_simple);
				exit();
	
			}
		}else{
			$alerta_simple=[
				"Alerta"=>"warning",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No podemos continuar la busqueda debido a un error de configuración"
			];
			echo json_encode($alerta_simple);
			exit();

		}

		if($modulo=="venta"){
			// dos campos fechas
			$fecha_inicio="fecha_inicio_".$modulo;
			$fecha_final="fecha_final_".$modulo;
			$tipo_comprobante="tipo_comprobante_".$modulo;

			// iniciar busqueda
			if(isset($_POST['fecha_inicio']) || isset($_POST['fecha_final']) || isset($_POST['tipo_comprobante'])){
				if($_POST['fecha_inicio']=="" || $_POST['fecha_final']=="" || $_POST['tipo_comprobante']==""){
					$alerta_simple=[
						"Alerta"=>"warning",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Por favor introduce una fecha de inicio y final"
					];
					echo json_encode($alerta_simple);
					exit();
				}
				// iniciar variables de session
				$_SESSION[$fecha_inicio]=$_POST['fecha_inicio'];			
				$_SESSION[$fecha_final]=$_POST['fecha_final'];	
				$_SESSION[$tipo_comprobante]=$_POST['tipo_comprobante'];
			}
			// mostrar todos
			if(isset($_POST['mostrar_todos'])){
				unset($_SESSION[$fecha_inicio]);			
				unset($_SESSION[$fecha_final]);
				unset($_SESSION[$tipo_comprobante]);
			}

		}else{
			$name_var="busqueda_".$modulo;
			// iniciar busqueda
			if(isset($_POST['busqueda_inicial'])){
				if($_POST['busqueda_inicial']==""){
				
					$alerta_simple=[
						"Alerta"=>"warning",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Por favor introduce un termino de busqueda"
					];
					echo json_encode($alerta_simple);
					exit();

				}
				$_SESSION[$name_var]=$_POST['busqueda_inicial'];
				
			}
			//mostrar todos
			if(isset($_POST['mostrar_todos'])){
				unset($_SESSION[$name_var]);
			}
		}

		// redireccionar
		$url=$data_url[$modulo];

		$alerta_simple=[
			"Alerta"=>"redireccionar",
			"URL"=>SERVERURL.$url."/"
		];
		echo json_encode($alerta_simple);

	}else{
		session_unset();
 	 	session_destroy();
 	 	header("Location: ".SERVERURL."login/");
 	 	exit();
	}


 ?>