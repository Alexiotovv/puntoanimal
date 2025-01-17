<div class="titulo-linea mt-2">
  <h2><i class="flaticon-001-appointment"></i>Citas para hoy, <?php 
   echo mainModel::fecha_castellano(date("d-m-Y"),2); 
   ?></h2>
  <hr class="sidebar-divider">
</div>
<div class="intro mb-4">
  <div class="row">
    <div class="col-12 mb-4">
      <div class="row">
        <?php 
            $busqueda="";
          
            if(isset($_SESSION['busqueda_cita_hoy'])==false){
              $busqueda="";
              $btn_mostrar_todos="";
            }else{
              $busqueda=$_SESSION['busqueda_cita_hoy'];
              $btn_mostrar_todos='<button type="submit" class="btn btn-secondary ml-2 btn-circle btn-xs"><i class="fas fa-users"></i></button>';
            }
            
         ?>
        <!-- Buscador -->
        <div class="col-md-6 mb-2">
          <form class="Formulario_ajax_simple" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" autocomplete="off">
            <div class="d-flex flex-row">
              <input type="hidden" name="modulo" value="cita_hoy">
              <div class="input-group group mb-0 mt-0">
                <input type="text" class="p-0" name="busqueda_inicial" id="inputSearch" placeholder="Buscar cita"/>
                  <div class="input-group-addon input-group-append">
                      <div class="input-icon">
                          <i class="fa fa-search"></i>
                      </div>
                  </div>
              </div>
              <div>
                <button type="submit" class="btn btn-primary ml-2 btn-circle btn-xs"><i class="fas fa-search"></i></button>
              </div>  
            </div>
          </form>
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-6">
              <form class="col-6 Formulario_ajax_simple" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" autocomplete="off">
                <input type="hidden" name="mostrar_todos" value="">
                <input type="hidden" name="modulo" value="cita_hoy">
                <?php echo $btn_mostrar_todos; ?>
              </form>
            </div>
            <div class="col-md-6">
              <a href="<?php  echo SERVERURL;?>addCitaM/" class="btn btn-primary ml-2 float-right"><i class="fas fa-plus mr-2"></i>Nueva Cita</a>    
            </div>
          </div>
          
        </div>
      </div> 
    </div>
    <div class="col-12">
      <!-- tabla y paginador -->
        <?php
          require_once "./controladores/citaControlador.php";
          $ins_cita= new citaControlador();
          $fecha_hoy=date('Y-m-d');
          // pagina: views
          echo $ins_cita->paginador_cita_hoy_controlador($pagina[1],10,$_SESSION['privilegio_vetp'],$pagina[0],$busqueda,$fecha_hoy); 
         ?>             
    </div>
    
  </div>
</div>