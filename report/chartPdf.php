<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="chartPdf.css" />
  </head>
  <body>
    <header class="clearfix">
      <?php
        if($result_config > 0){
       ?>
      <div id="logo">
        <img src="<?php echo "../".$configuracion['empresaFotoUrl']; ?>">
      </div>

      <div class="empresa" id="company">
        <div class="emp_razon">
          <h2 class="name"><?php echo strtoupper($configuracion['empresaNombre']); ?></h2>
          <div>RIF: <?php echo $configuracion['rif']; ?></div>
        </div>
        <div class="empresa_cont">
            <h3 class="date"><?php echo $fecha_hoy; ?></h3>
        </div>
        <?php
          }
         ?>
      </div>
      </div>
    </header>
    <main>
      <div id="chartimg">
        <div>
        <h3><?php echo $titulo; ?></h3>
        <img align="imagen del canvas" id="img" name="img" src="<?php echo $imagenChart; ?>">  
        </div>
      </div>
    </main>
    
  </body>
</html>