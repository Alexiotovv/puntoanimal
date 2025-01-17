<?php

// print_r($_REQUEST);
// print_r($_REQUEST['cl']);
//exit;

    $peticionAjax=true;

	require_once "../modelos/mainModel.php";	

	/**
	 * Generar reportes listas en pdf
	 */
	class generarExcel extends mainModel
	{

		public function imprimirReportExcel(){

			if(empty($_REQUEST['cl']))
			{
				echo "No es posible generar report Excel";
			}else{
				$tabla = $_REQUEST['cl'];
				$fecha_hoy = mainModel::fecha_castellano(date('d-m-Y'),3);
				
				// datos empresa
				$query_config = mainModel::ejecutar_consulta_simple("SELECT * FROM empresa");
				$result_config = $query_config->rowCount();

				if($result_config > 0){
					$configuracion = $query_config->fetch();
				}else{
					echo "no cargo datos empresa";
				}
				//------ Cabecera Tablet -------------//
				$detalles_fecha = "";
				if($tabla=="venta"){
                    // Generamos las cabeceras del excel
                    $fields = array('#','T. Comprobante','N. Comprobante','Cliente','Cedula','Fecha','Vendedor','Metodo Pago','Total','Estado');
                    $excelData = implode("\t",array_values($fields)) . "\n";

					$fecha_inicio=$_REQUEST['fi'];
					$fecha_final=$_REQUEST['ff'];
					$tipo_comprobante=$_REQUEST['tp'];
					
                    $tipo_comprobante_query= $tipo_comprobante=="Todos" ? "" : " AND tipo_comprobante = '$tipo_comprobante'";
					
                    // Buscamos la data en la base de datos
                    $query=mainModel::ejecutar_consulta_simple("SELECT t1.*,t2.clienteNombre,t2.clienteApellido,t3.userNombre,t3.userApellido FROM venta AS t1 INNER JOIN cliente AS t2 ON t1.dniCliente=t2.clienteDniCedula INNER JOIN usuarios AS t3 ON t1.ventUsuario = t3.id WHERE (( DATE(ventFecha) BETWEEN '$fecha_inicio' AND '$fecha_final' $tipo_comprobante_query)) ORDER BY idVenta DESC");

                    $result=$query->rowCount();
                    $contador = 0;
                    $tbodyTable="";
                    // Validamos que exista data
                    if($result > 0){
                        //-----------Tbody --------------//
                        while ($rows = $query->fetch()){
                            $contador = $contador + 1;
                            $total_formt = number_format($rows['ventTotal'],2,'.',',');

                            $lineData = array($contador, $rows['tipo_comprobante'], $rows['serie_comprobante'].' '.$rows['num_comprobante'], 
                                        $rows['clienteNombre'].' '.$rows['clienteApellido'], $rows['dniCliente'], $rows['ventFecha'],
                                        $rows['userNombre'].' '.$rows['userApellido'], $rows['ventMetodoPago'], $total_formt,
                                        $rows['dov_Estado']);
                            $excelData .= implode("\t", array_values($lineData)) . "\n";
                                
                        } // while 

                        header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                        header("Content-Disposition: attachment; filename=reporteVenta". date('dmY') .".xls");
                        header("pragma: no-cache");
                        header("expires: 0");

                        echo $excelData;                        
                    
                    }else{
                        $excelData = "Sin registros en tabla ..." . "\n";
                    }
				}
				
			}
		} 


	} 
	

	$report = new generarExcel();
	$report -> imprimirReportExcel();