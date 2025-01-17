<?php

// print_r($_REQUEST);
// print_r($_REQUEST['cl']);
//exit;

    $peticionAjax=true;

	require_once "../modelos/mainModel.php";
	require_once '../pdf/vendor/autoload.php';
	
	use Dompdf\Dompdf;

	/**
	 * Generar factura en pdf
	 */
	class generaHistoria extends mainModel
	{

		public function imprimirHistoria(){

			if(empty($_REQUEST['nh']))
			{
				echo "No es posible generar la historia.";
			}else{ 
				
				$noHistoria = $_REQUEST['nh'];
				// datos empresa
				$query_config = mainModel::ejecutar_consulta_simple("SELECT * FROM empresa");
				$result_config = $query_config->rowCount();

				if($result_config > 0){
					$configuracion = $query_config->fetch();
				}else{
					echo "no cargo datos empresa";
				}
				

					// datos detalle venta
                    $queryh=mainModel::ejecutar_consulta_simple("SELECT h.codHistorialM,h.histMotivo,h.histAnamnesis,h.histSintomas,h.histDiagnostico,h.histDiagnosticoPre,h.histTratamiento,h.histFecha,h.histFecha,
					m.codMascota,m.mascotaNombre,m.mascotaPeso,m.mascotaColor,m.mascotaSexo,m.castrado,
					mr.idRaza,mr.razaNombre,me.idEspecie,me.espNombre,
					cl.clienteDniCedula, cl.clienteNombre,cl.clienteApellido, cl.clienteTelefono,cl.clienteDomicilio 
                    FROM historialmascota h 
                    INNER JOIN mascota m 
                    ON h.codMascota = m.codMascota 
                    INNER JOIN raza mr 
                    ON m.idRaza = mr.idRaza 
                    INNER JOIN especie me 
                    ON mr.idEspecie = me.idEspecie 
                    INNER JOIN cliente cl 
                    ON m.dniDueno = cl.clienteDniCedula
                     where idHistorial =$noHistoria");

					$result_detalle = $queryh->rowCount();

					if($result_detalle>0){
						$mascotahistorial = $queryh->fetch();
					}else{
						echo "no hay detalles en tabla";
					}

					ob_start();
				    include(dirname('_FILE_').'/historia.php');
				    $html = ob_get_clean();

					// instantiate and use the dompdf class
					$dompdf = new Dompdf();

					$dompdf->loadHtml($html);
					// (Optional) Setup the paper size and orientation
					$dompdf->setPaper('letter', 'portrait');
					
					// Render the HTML as PDF
					$dompdf->render();
					ob_get_clean();
					// Output the generated PDF to Browser
					$dompdf->stream('factura_'.$noHistoria.'.pdf',array('Attachment'=>0));
					exit;
				
			}
		} // fin imprimirFactura


	} // class generaFactura fin
	
	$mascotahistorial = new generaHistoria();
	$mascotahistorial -> imprimirHistoria();