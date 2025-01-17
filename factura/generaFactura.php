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
	class generaFactura extends mainModel
	{

		public function imprimirFactura(){

			if(empty($_REQUEST['cl']) || empty($_REQUEST['f']))
			{
				echo "No es posible generar la factura.";
			}else{
				$codCliente = $_REQUEST['cl'];
				$noFactura = $_REQUEST['f'];
				// datos empresa
				$query_config = mainModel::ejecutar_consulta_simple("SELECT * FROM empresa");
				$result_config = $query_config->rowCount();

				if($result_config > 0){
					$configuracion = $query_config->fetch();
				}else{
					echo "no cargo datos empresa";
				}
				// Consulta datos de factura
				$query=mainModel::ejecutar_consulta_simple("SELECT f.idVenta,f.ventMetodoPago, DATE_FORMAT(f.ventFecha, '%d/%m/%Y') as fecha, DATE_FORMAT(f.ventFecha,'%H:%i:%s') as  hora,
				 f.dniCliente,f.tipo_comprobante,serie_comprobante,num_comprobante,v.userNombre as vendedor,cl.clienteDniCedula, cl.clienteNombre,cl.clienteApellido, cl.clienteTelefono,cl.clienteDomicilio
					FROM venta f
					INNER JOIN usuarios v
					ON f.ventUsuario = v.id
					INNER JOIN cliente cl
					ON f.dniCliente = cl.clienteDniCedula
					WHERE f.idVenta = $noFactura AND f.dniCliente = '$codCliente'");
					// $codCliente, $nofactura desde url


				$result=$query->rowCount();
				if($result > 0){

					$factura = $query->fetch();
					$no_factura = $factura['idVenta'];
					// datos detalle venta
					$query_productos = mainModel::ejecutar_consulta_simple("SELECT p.prodserviNombre,dt.detalleCantidad,dt.precio_venta,(dt.detalleCantidad * dt.precio_venta) as precio_total
						FROM venta f
						INNER JOIN detalleventa dt
						ON f.idVenta = dt.codFactura
						INNER JOIN productoservicio p
						ON dt.codProducto = p.codProdservi
						WHERE f.idVenta = $no_factura ");

					$result_detalle = $query_productos->rowCount();

					if($result_detalle>0){

					}else{
						echo "no hay detalles en tabla";
					}

					ob_start();
				    include(dirname('__FILE__').'/factura.php');
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
					$dompdf->stream('factura_'.$noFactura.'.pdf',array('Attachment'=>0));
					exit;
				}else{
					echo "datos detalles toda venta no tiene";
				}
			}
		} // fin imprimirFactura


	} // class generaFactura fin
	
	$factura = new generaFactura();
	$factura -> imprimirFactura();
