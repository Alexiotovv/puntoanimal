<?php 
    $peticionAjax=true;

	require_once "../modelos/mainModel.php";
	require_once '../pdf/vendor/autoload.php';
	
	use Dompdf\Dompdf;

	/**
	 * Imprimir chart de estadisticas
	 */
	class chartImprimirPdf extends mainModel
	{
		
		public function imprimirChart(){
			if (empty($_POST['base64'])){
				echo "No se puede cargar el reporte";
			}else{
			// imagen de chart base64
			$imagenChart =	$_POST['base64'];
			// titulo
			$titulo = $_POST['titulo'];

			$fecha_hoy = mainModel::fecha_castellano(date('d-m-Y'),3);
			// datos empresa
			$query_config = mainModel::ejecutar_consulta_simple("SELECT * FROM empresa");

			$result_config = $query_config->rowCount();

			if($result_config > 0){
				$configuracion = $query_config->fetch();
			}else{
				echo "no cargo datos empresa";
			}

				ob_start();
			    include(dirname('__FILE__').'/chartPdf.php');
			    $html = ob_get_clean();

				// instantiate and use the dompdf class
				$dompdf = new Dompdf();

				$dompdf->loadHtml($html);
				// (Optional) Setup the paper size and orientation
				// landscape
				if($titulo=="Mascotas por Raza"){
					$dompdf->setPaper('A4', 'portrait');
				}else{
					$dompdf->setPaper('A5', 'landscape');
				}
				// Render the HTML as PDF
				$dompdf->render();
				ob_get_clean();
				// Output the generated PDF to Browser
				$dompdf->stream('chart_'.date('d_m_Y_H_i_s').'.pdf',array('Attachment'=>0));
				exit;	
			}
			
		} // fin imprimirFactura

	}

	$charte = new chartImprimirPdf();
	$charte -> imprimirChart();
