<div class="row mt-4">
  <!-- USUARIO SISTEMA -->
  <?php
  if ($_SESSION['privilegio_vetp']==1) {
      require_once "./controladores/usuarioControlador.php";
      $inst_usuario = new usuarioControlador();
      $total_usuarios = $inst_usuario->datos_usuario_controlador("Conteo",0);
   ?>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
      <a class="card-body" href="<?php  echo SERVERURL;?>listaUsuario/">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-violet text-uppercase mb-1" >usuarios</div>
    
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_usuarios->rowCount(); ?></div>
          </div>
          <div class="col-auto">
              <img src="../img/usuarios.png" alt="usuarios" width="80"></i>
          </div>
        </div>
       </a>
    </div>
  </div>
<?php } ?>
<?php 
    require "./controladores/mascotaControlador.php";
    $mascota=new mascotaControlador();
    $Cmascota=$mascota->datos_mascota_controlador("Conteo",0); 
 ?>
  <!-- MASCOTAS -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
    <a class="card-body" href="<?php  echo SERVERURL;?>listaMascota/">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Mascotas</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $Cmascota->rowCount(); ?></div>
          </div>
          <div class="col-auto">
           <img src="../img/mascotas.png" alt="mascotas" width="80"></i>
          </div>
        </div>
      </a>
    </div>
  </div>
<?php 
    require "./controladores/clienteControlador.php";
    $cliente=new clienteControlador();
    $Ccliente=$cliente->datos_cliente_controlador("Conteo",0); 
 ?>
  <!-- CLIENTE -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
    <a class="card-body" href="<?php  echo SERVERURL;?>listaCliente/">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Cliente</div>
            <div class="col-auto">
              <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $Ccliente->rowCount(); ?></div>
            </div>
          </div>
          <div class="col-auto">
             <img src="../img/clientes.png" alt="clientes" width="80"></i>
          </div>
        </div>
     </a>
    </div>
  </div>

<?php 
    require "./controladores/inventarioControlador.php";
    $inventario=new inventarioControlador();
    $Cinventario=$inventario->datos_inventario_controlador("Conteo",0); 
 ?>

  <!-- PRODUCTOS -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
    <a class="card-body" href="<?php  echo SERVERURL;?>listaProdservi/">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Producto/Servicio</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $Cinventario->rowCount(); ?></div>
          </div>
          <div class="col-auto">
             <img src="../img/inventario.png" alt="productos" width="80"></i>
          </div>
        </div>
      </div>
    </a>
  </div>
</div>



<div class="row pageEstadistica" server="<?php echo SERVERURL; ?>" >
	<div class="col-xl-8 col-lg-7">
		<div class="intro mb-4">
			<div class="d-flex justify-content-between">
				<h3 class="sub-titulo-panel">
				 <img src="../img/especies.png" alt="compras" width="30"></i>
				
				
				</i>Especies </h3>
				<!-- IMPRIMIR PDF -->
				<form method="POST" class="float-right" target="print_poput" action="<?php echo SERVERURL; ?>report/chartImprimirPdf.php"> 
					<button type="submit" id="" value="myPieChartEspecie" data-toggle="tooltip" title="Imprimir" class="btn_imprimir_pdf btn btn-circle btn-outline-info btn-sm"><i class="fas fa-print"></i>
					</button>
					<input type="hidden" name="base64" id="base64">
					<input type="hidden" name="titulo" value="Mascotas por Especie">
				</form>		
				<!--X- IMPRIMIR PDF -X-->
			</div>
			<hr>
			<div class="chart-pie pt-4">
            	<canvas id="myPieChartEspecie"></canvas>
          	</div>
		</div>

		<div class="intro mb-4">
			<div class="d-flex justify-content-between">
				<h3 class="sub-titulo-panel">
				<img src="../img/razas.png" alt="razas" width="30"></i>
				</i>Raza </h3>
				<!-- IMPRIMIR PDF class="FormularioEmprimir"-->
				<form method="POST" class="float-right" target="print_poput" action="<?php echo SERVERURL; ?>report/chartImprimirPdf.php"> 
					<button type="submit" id="" value="myBarChartRaza" data-toggle="tooltip" title="Imprimir" class="btn_imprimir_pdf btn btn-circle btn-outline-info btn-sm"><i class="fas fa-print"></i>
					</button>
					<input type="hidden" name="base64" id="base64">
					<input type="hidden" name="titulo" value="Mascotas por Raza">
				</form>		
			</div>
			<hr>
			<div class="chart-bar">
	            <canvas id="myBarChartRaza" ></canvas>
	        </div>
		</div>
		
	</div>

	<div class="col-xl-4 col-lg-5">
		<div class="intro mb-4">
			<div class="d-flex justify-content-between">
				<h3 class="sub-titulo-panel">
				<img src="../img/sexo.png" alt="mascotas" width="30"></i>
				</i>Mascotas por sexo </h3>
				<!-- IMPRIMIR PDF -->
				<form method="POST" class="float-right" target="print_poput" action="<?php echo SERVERURL; ?>report/chartImprimirPdf.php"> 
					<button type="submit" id="" value="myPieSexo" data-toggle="tooltip" title="Imprimir" class="btn_imprimir_pdf btn btn-circle btn-outline-info btn-sm"><i class="fas fa-print"></i>
					</button>
					<input type="hidden" name="base64" id="base64">
					<input type="hidden" name="titulo" value="Mascotas por sexo">
				</form>		
			</div>
			<hr>
			<div class="chart-pie">
	            <canvas id="myPieSexo"></canvas>
	         </div>
		</div>
	</div>
</div>