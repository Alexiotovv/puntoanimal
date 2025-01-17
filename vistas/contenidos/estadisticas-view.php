<div class="titulo-linea mt-2">
  <h2><i class="fas fa-fw fa-chart-bar"></i>Estadisticas</h2>
  <hr class="sidebar-divider">
</div>

<div class="row pageEstadistica" server="<?php echo SERVERURL; ?>" >
	<div class="col-xl-8 col-lg-7">
		<div class="intro mb-4">
			<div class="d-flex justify-content-between">
				<h3 class="sub-titulo-panel"><i class="fas fa-paw"></i>Especies </h3>
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
				<h3 class="sub-titulo-panel"><i class="fas fa-paw"></i>Raza </h3>
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
				<h3 class="sub-titulo-panel"><i class="fas fa-paw"></i>Mascotas por sexo </h3>
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