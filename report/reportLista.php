<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="reportStyle.css" />
  </head>
  <body>
    <div id="encabezado">
      <?php
        if($result_config > 0){
       ?>
       <div class="clearfix">
          <div id="logo">
            <img src="<?php echo "../".$configuracion['empresaFotoUrl']; ?>">
          </div>

          <div class="empresa" id="company">
            <div class="emp_razon">
              <h2 class="name"><?php echo strtoupper($configuracion['empresaNombre']); ?></h2>
              <div>RUC: <?php echo $configuracion['rif']; ?></div>
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
       </div>
    </div>
    <main>
      <div id="details" class="clearfix">
        <div id="client">
          <h2 class="name"><?php echo $titulo; ?></h2>
          <!-- para venta lista -->
          <?php echo $detalles_fecha; ?>
          <!--X- para venta lista -X-->
          <div class="address">Total Registros: <?php echo $result; ?></div>
        </div>
        
        <div id="invoice">
          <div class="date">Fecha: <?php echo $fecha_hoy; ?></div>
        </div>
      </div>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
              <!-- ******dinamico****** -->
              <?php echo $cabeceraTable; ?>
              <!-- ******dinamico****** -->
            </tr>  
        </thead>
          
        <tbody>
          <!-- ******dinamico****** -->
          <?php echo $tbodyTable; ?>
          <!-- ******dinamico****** -->
        </tbody>
       
      </table>
      
    </main>
  </body>
</html>