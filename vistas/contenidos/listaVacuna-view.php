<div class="titulo-linea mt-2">
  <h2><i class="flaticon-029-injection"></i>Lista Vacunas</h2>
  <hr class="sidebar-divider">
</div>
<!-- ACORDION -->
<div class="lista-vacuna">
  <span>Distribución de vacunas por especie:</span>
   <div class="accordion" id="accordion" role="tablist">
      <?php
        require_once "./controladores/vacunaControlador.php";
        $ins_vacuna = new vacunaControlador();
        
        echo $ins_vacuna->listado_vacuna_controlador($_SESSION['privilegio_vetp']); 
      ?>
  </div>
  
</div>
