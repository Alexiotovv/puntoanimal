<?php
  if($_SESSION['privilegio_vetp']!=1){
    echo $ins_loginc->forzar_cierre_sesion_controlador();
    exit();
  } 
 ?>
<div class="titulo-linea mt-2">
  <h2><i class="far fa-user"></i>Lista Usuarios</h2>
  <hr class="sidebar-divider">
</div>

<div class="intro lista-admin mb-4">
  <div class="row">
    <div class="col-12 mb-4">
      <div class="row">
        <?php 
          // if(!isset($_SESSION['busqueda_usuario']) && empty($_SESSION['busqueda_usuario']) ){ usuario
            $busqueda="";
          
            if(isset($_SESSION['busqueda_usuario'])==false){
              $busqueda="";
              $btn_mostrar_todos="";
            }else{
              $busqueda=$_SESSION['busqueda_usuario'];
              $btn_mostrar_todos='<button type="submit" class="btn btn-secondary ml-2 btn-circle btn-xs" data-toggle="tooltip" title="Mostrar todos"><i class="fas fa-users"></i></button>';
            }
            
         ?>
        <div class="col-md-6 mb-2">
          <!-- BUSCARDOR FormularioAjax  -->
          <form class="Formulario_ajax_simple" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" autocomplete="off">
            <div class="d-flex flex-row">
              <input type="hidden" name="modulo" value="usuario">
              <!-- busqueda_inicial -->
              <div class="input-group group mb-0 mt-0">
                <input type="text" class="p-0" name="busqueda_inicial" id="inputSearch" placeholder="Buscar por nombre o cedula"/>
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
                <input type="hidden" name="modulo" value="usuario">
                <?php echo $btn_mostrar_todos; ?>
              </form>    
            </div>
            <div class="col-md-6">
              <a href="<?php  echo SERVERURL;?>addUsuario/" class="btn btn-primary ml-2 float-right"><i class="fas fa-plus mr-2"></i>Nuevo Usuario</a>
            </div>
          </div>
          
        </div>
      </div>
      
      
    </div>
    <div class="col-12">
        <!-- tabla -->
        <?php
          require_once "./controladores/usuarioControlador.php";
          $ins_usuario= new usuarioControlador();
          // pagina: views
          echo $ins_usuario->paginador_usuario_controlador($pagina[1],5,$_SESSION['privilegio_vetp'],$_SESSION['id_vetp'],$pagina[0],$busqueda); 
         ?>
        <!-- paginador -->
                    
      </div>
    
    </div>
</div>