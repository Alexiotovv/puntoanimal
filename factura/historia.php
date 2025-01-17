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
  <title>Historial</title>
  <link rel="stylesheet" href="style_historia.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      color: #000;
    }

    header {
      margin-bottom: 20px;
    }

    #logo img {
      width: 100px;
      height: 90px;
    }

    .empresa {
      display: flex;
      justify-content: space-between;
      border-bottom: 2px solid black;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .empresa h2 {
      margin: 0;
    }

    #details {
      margin-bottom: 20px;
    }

    .clearfix::after {
      content: "";
      display: table;
      clear: both;
    }

    .address {
      margin-bottom: 10px;
    }

    .address1 {
      margin-bottom: 2px;
    }

    h2.name {
      font-size: 1.2em;
      margin: 0;
    }

    .cuadro-con-lineas {
      border: 2px solid black;
      padding: 10px;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }

    table th,
    table td {
      border: 2px solid black;
      padding: 8px;
      text-align: left;
    }

    table th {
      background-color: #f2f2f2;
    }

    #thanks {
      font-size: 1.2em;
      margin-top: 20px;
    }

    .center-text {
      text-align: center;
    }
  </style>
</head>

<body>
  <header class="clearfix">
    <?php
    if ($result_config > 0) {
      $iva = $configuracion['empresaIva'];
      $moneda = $configuracion['empresaMoneda'];
    ?>
      <div id="logo">
        <img src="<?php echo "../" . $configuracion['empresaFotoUrl']; ?>">
      </div>

      <div class="empresa" id="company">
        <div class="emp_razon">
          <h2 class="name"><strong><?php echo strtoupper($configuracion['empresaNombre']); ?></strong></h2>
          <div><strong>RUC: <?php echo $configuracion['rif']; ?></strong></div>
          <div><strong><?php echo $configuracion['empresaDireccion']; ?></strong></div>
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

    <!--columnas -->

    <main>
      <div id="details" class="clearfix">
        <div>
          <div class="address center-text" style="margin: 1px;"><strong>DATOS MASCOTA</strong></div>

          <table>
            <tr>
              <td>
                <div class="address1"><strong>Código mascota:</strong> <?php echo $mascotahistorial['codMascota']; ?></div>
                <div class="address1"><strong>Nombre Mascota:</strong> <?php echo $mascotahistorial['mascotaNombre']; ?></div>
                <div class="address1"><strong>Fecha:</strong> <?php echo $mascotahistorial['histFecha']; ?></div>
              </td>
              <td>
                <div class="address1"><strong>Color:</strong> <?php echo $mascotahistorial['mascotaColor']; ?></div>
                <div class="address1"><strong>Peso:</strong> <?php echo $mascotahistorial['mascotaPeso']; ?></div>
                <div class="address1"><strong>Sexo:</strong> <?php echo $mascotahistorial['mascotaSexo']; ?></div>
              </td>
              <td>
                <div class="address1"><strong>Castrado:</strong> <?php echo $mascotahistorial['castrado']; ?></div>
                <div class="address1"><strong>Raza:</strong> <?php echo $mascotahistorial['razaNombre']; ?></div>
                <div class="address1"><strong>Especie:</strong> <?php echo $mascotahistorial['espNombre']; ?></div>
              </td>
            </tr>
          </table>

          <div class="address center-text" ><strong>DATOS CLIENTE</strong></div>

          <table>
            <tr>
              <td>
                <div class="address1"><strong>Nombre Cliente:</strong>
                  <h2 class="name"><strong><?php echo $mascotahistorial['clienteNombre'] . " " . $mascotahistorial['clienteApellido']; ?></strong></h2>
                </div>
                <div class="address1"><strong>NIT/CI:</strong> <?php echo $mascotahistorial['clienteDniCedula']; ?></div>
                <div class="address1"><strong>Dirección:</strong> <?php echo $mascotahistorial['clienteDomicilio']; ?></div>
                <div class="address1"><strong>Teléfono:</strong> <?php echo $mascotahistorial['clienteTelefono']; ?></div>
              </td>
            </tr>
          </table>
        </div>

        <div class="cuadro-con-lineas" style=" border: 2px solid black; ">
          <table border="0" cellspacing="0" cellpadding="0">
            <thead>
              <tr>
                <!-- <th class="no" width="40px">#</th> -->
                <th class="desc" style=" border: 2px solid black; ">Motivo consulta</th>
                <th class="unit" style=" border: 2px solid black; ">Anamnesis</th>
                <!-- <th class="qty" width="150px">Precio Unitario</th>
              <th class="total" width="150px">Precio Total</th> -->
              </tr>
            </thead>
            <tbody>
              <tr>
                <!-- <td class="no"><?php echo $contador; ?></td> -->
                <td class="break-word"><?php echo $mascotahistorial['histMotivo']; ?></td>
                <td class="break-word"><?php echo $mascotahistorial['histAnamnesis']; ?></td>
              </tr>
            </tbody>
          </table>
          <table border="0" cellspacing="0" cellpadding="0">
            <thead>
              <tr>
                <!-- <th class="no" width="40px">#</th> -->
                <th class="desc">Hallazgos Clinicos</th>
                <th class="unit">Examenes Diagnostico</th>
                <!-- <th class="qty" width="150px">Precio Unitario</th>
              <th class="total" width="150px">Precio Total</th> -->
              </tr>
            </thead>
            <tbody>
              <tr>
                <!-- <td class="no"><?php echo $contador; ?></td> -->
                <td class="break-word"><?php echo $mascotahistorial['histSintomas']; ?></td>
                <td class="break-word"><?php echo $mascotahistorial['histDiagnostico']; ?></td>
              </tr>
            </tbody>
          </table>
          <table border="0" cellspacing="0" cellpadding="0">
            <thead>
              <tr>
                <!-- <th class="no" width="40px">#</th> -->
                <th class="desc">Diagnostico Presuntivo</th>
                <th class="unit">Tratamiento</th>
                <!-- <th class="qty" width="150px">Precio Unitario</th>
              <th class="total" width="150px">Precio Total</th> -->
              </tr>
            </thead>
            <tbody>
              <tr>
                <!-- <td class="no"><?php echo $contador; ?></td> -->
                <td class="break-word"><?php echo $mascotahistorial['histDiagnosticoPre']; ?></td>
                <td class="break-word"><?php echo $mascotahistorial['histTratamiento']; ?></td>
              </tr>
            </tbody>
          </table>
        </div>
        <section id="invoice-info">

        </section>
        <div id="thanks"><strong>Gracias por confiar en nosotros!</strong></div>

    </main>
</body>

</html>