<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
    $peticionAjax=true;

	require_once "../modelos/mainModel.php";

	require_once '../pdf/vendor/autoload.php';
	
	use Dompdf\Dompdf;

	/** generar ticket de venta
	 *  clase entiende de clase mainModel
	 */
	class generaTicket extends mainModel
	{

		public function imprimirTicket(){

			if(empty($_REQUEST['cl']) || empty($_REQUEST['f']))
			{
				echo "No es posible generar ticket";
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
				// datos venta
				$query=mainModel::ejecutar_consulta_simple("SELECT f.idVenta,f.tipo_comprobante,f.serie_comprobante,f.num_comprobante, DATE_FORMAT(f.ventFecha, '%d/%m/%Y') as fecha, DATE_FORMAT(f.ventFecha,'%H:%i:%s') as  hora, f.dniCliente,v.userNombre as vendedor,cl.clienteDniCedula, cl.clienteNombre,cl.clienteApellido, cl.clienteTelefono,cl.clienteDomicilio
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
					// datos detalle de venta
					$query_productos = mainModel::ejecutar_consulta_simple("SELECT p.prodserviNombre,dt.detalleCantidad,f.tipo_comprobante,dt.precio_venta,(dt.detalleCantidad * dt.precio_venta) as precio_total
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
				    include(dirname('__FILE__').'/ticket.php');
				    $html = ob_get_clean();

					// -------------------------------
					$dompdf = new Dompdf();
					$dompdf->loadHtml($html);
					$dompdf->setPaper('letter', 'portrait');
	
					$dompdf->render();
					ob_get_clean();
					// // Output the generated PDF to Browser
					$dompdf->stream('ticket_'.$noFactura.'.pdf',array('Attachment'=>0));
					exit;
				}else{
					echo "datos detalles toda venta no tiene";
				}
			}
		} // fin imprimirTicket



	} // class fin

	$factura = new generaTicket();
	$factura -> imprimirTicket();
