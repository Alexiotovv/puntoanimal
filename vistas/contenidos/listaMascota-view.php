<div class="titulo-linea mt-2">
  <h2><i class="flaticon-023-pets"></i>Lista Mascotas</h2>
  <hr class="sidebar-divider">
</div>
<div class="intro mb-4">
  <div class="row">
    <div class="col-12 mb-4">
      <div class="row">
        <?php 
            $busqueda="";
          
            if(isset($_SESSION['busqueda_mascota'])==false){
              $busqueda="";
              $btn_mostrar_todos="";
            }else{
              $busqueda=$_SESSION['busqueda_mascota'];
              $btn_mostrar_todos='<button type="submit" class="btn btn-secondary ml-2 btn-circle btn-xs" data-toggle="tooltip" title="Mostrar todos"><i class="fas fa-paw"></i></button>';
            }
            
         ?>
        <div class="col-md-6 mb-2">
          <form class="Formulario_ajax_simple" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" autocomplete="off">
            <div class="d-flex flex-row">
              <input type="hidden" name="modulo" value="mascota">
              <div class="input-group group mb-0 mt-0">
                <input type="text" class="p-0" name="busqueda_inicial" id="inputSearch" placeholder="Buscar por nombre o codigo"/>
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
            <div class="col-md-4">
              <form class="col-6 Formulario_ajax_simple" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" autocomplete="off">
                <input type="hidden" name="mostrar_todos" value="">
                <input type="hidden" name="modulo" value="mascota">
                <?php echo $btn_mostrar_todos; ?>
              </form>
            </div>
            <div class="col-md-8">
              <div class="float-right">
                <a class="btn btn-style-2 btn-sm ml-2 mb-2" href="<?php echo SERVERURL; ?>report/generarReportsLista.php?cl=mascota" target="bland"><i class="fas fa-file-pdf mr-2"></i>PDF</a>
                <a href="<?php  echo SERVERURL;?>addMascota/" class="btn btn-primary ml-2 float-right"><i class="fas fa-plus mr-2"></i>Nueva Mascota</a>  
              </div>
            </div>
          </div>
          
        </div>
      </div>
      <!-- <input type="search" name=""> -->
      
    </div>
    <div class="col-12">
      <!-- tabla y paginador -->
        <?php
          require_once "./controladores/mascotaControlador.php";
          $ins_mascota= new mascotaControlador();
          // pagina: views
          echo $ins_mascota->paginador_mascota_controlador($pagina[1],10,$_SESSION['privilegio_vetp'],$pagina[0],$busqueda); 
         ?>
      
    </div>
    
  </div>
</div>