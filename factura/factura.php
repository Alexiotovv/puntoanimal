<?php
  $subtotal   = 0;
  $iva    = 0;
  $impuesto   = 0;
  $tl_sniva   = 0;
  $total    = 0;
 //print_r($configuracion);
  ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Factura</title>
    <link rel="stylesheet" href="style_factura.css" />
  </head>
  <body>
    <header class="clearfix">
      <?php
        if($result_config > 0){
          $iva = $configuracion['empresaIva'];
          $moneda = $configuracion['empresaMoneda'];
       ?>
      <div id="logo">
        <img src="<?php echo "../".$configuracion['empresaFotoUrl']; ?>">
      </div>

      <div class="empresa" id="company">
        <div class="emp_razon">
          <h2 class="name"><?php echo strtoupper($configuracion['empresaNombre']); ?></h2>
          <div>NIT/CI: <?php echo $configuracion['rif']; ?></div>
          <div><?php echo $configuracion['empresaDireccion']; ?></div>
        </div>
        <div class="empresa_cont">
          <div>Teléfono: <?php echo $configuracion['empresaTelefono']; ?></div>
          <div><a href="mailto:company@example.com"><?php echo $configuracion['empresaCorreo']; ?></a></div>
          
        </div>
        <?php
          }
         ?>
      </div>
      
    </header>
    <main>
      <div id="details" class="clearfix">
        <div id="client">
          <div class="to">CLIENTE:</div>
          <h2 class="name"><?php echo $factura['clienteNombre']." ".$factura['clienteApellido'] ; ?></h2>
          <div class="address">NIT/CI: <?php echo $factura['clienteDniCedula']; ?></div>
          <div class="address">Dirección: <?php echo $factura['clienteDomicilio']; ?></div>
          <div class="address">Telefono: <?php echo $factura['clienteTelefono']; ?></div>
        </div>
        
        <div id="invoice">
          <h1>N. <?php echo $factura['tipo_comprobante']." ".$factura['serie_comprobante']."-".$factura['num_comprobante']; ?></h1>
          <div class="date">Fecha: <?php echo $factura['fecha']." ".$factura['hora']; ?></div>
          <div class="date">Vendedor: <?php echo $factura['vendedor']; ?></div>
        </div>
      </div>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
              <th class="no" width="40px">#</th>
              <th class="desc">Descripción</th>
              <th class="unit">Cant.</th>
              <th class="qty" width="150px">Precio Unitario</th>
              <th class="total" width="150px">Precio Total</th>
            </tr>  
        </thead>
          
        
        <tbody>
        <?php
          $contador =0;
          if($result_detalle > 0){

            while ($row = $query_productos->fetch()){
              $contador = $contador + 1;
         ?>
        <tr>
          <td class="no"><?php echo $contador; ?></td>
          <td class="desc"><?php echo $row['prodserviNombre']; ?></td>
          <td class="unit"><?php echo $row['detalleCantidad']; ?></td>
          <td class="qty"><?php echo number_format($row['precio_venta'],2,'.',','); ?></td>
          <td class="total"><?php echo number_format($row['precio_total'],2,'.',','); ?></td>
        </tr>
        </tbody>

        <?php
              $precio_total = $row['precio_total'];
              $subtotal = round($subtotal + $precio_total, 2);
            }
          }
          
          $impuesto   = round($subtotal * ($iva / 100), 2);
          $tl_sniva   = round($subtotal - $impuesto,2 );
          $total    = round($tl_sniva + $impuesto,2);
        ?>
           
        <!-- ------------- Totales--------------- -->
        <tfoot>
          <tr>
            <td colspan="2"></td>
            <td colspan="2">SUBTOTAL</td>
            <td><?php echo  $moneda." " .number_format($tl_sniva,2,'.',','); ?></td>
          </tr>
          <tr>
            <td colspan="2"></td>
            <td colspan="2">IVA: <?php echo $iva." %"; ?></td>
            <td><?php echo $moneda ." ".number_format($impuesto,2,'.',','); ?></td>
          </tr>
          <tr>
            <td colspan="2"></td>
            <td colspan="2">TOTAL A PAGAR</td>
            <td><?php echo $moneda." ". number_format($total,2,'.',','); ?></td>
          </tr>
        </tfoot>
        <!-- -------X-------Totales--X------------- -->
      </table>
      <section id="invoice-info">
        <div>
          <span>Metodo de Pago: </span> <span><?php echo $factura['ventMetodoPago']; ?></span>
        </div>
      </section>
      <div id="thanks">Gracias por su compra!</div>
      
    </main>
  </body>
</html>