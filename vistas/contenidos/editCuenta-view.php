<div class="titulo-linea mt-2">
  <h2><i class="fas fa-user-plus"></i>Mi cuenta</h2>
  <hr class="sidebar-divider">
</div>

<div class="intro add-admin-form mb-4">
  <form class="FormularioAjax" action="" method="POST" data-form="update" id="form-add-admin">
    <div class="row">
      
      <!-- DATOS DE CUENTA -->
      <div class="col-lg-12">
        <h3 class="sub-titulo-panel">
              <i class="fas fa-user-lock"></i>
            Datos de la cuenta</h3>
        <div class="row mt-2">
          <div class="col-12">
            <div class="group">
              <input type="text" required="required"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre de usuario</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="password" required="required"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Contraseña</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="password" required="required"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Repita Contraseña</label>
            </div>
          </div>          
          <div class="col-md-12">
            <div class="group">
              <input type="email" required="required"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Email</label>
            </div>
          </div>
        </div>
        <!-- FOTO PERFIL -->
        <div class="container-fluid">
          <h3 class="sub-titulo-panel">
                <i class="fas fa-camera"></i>
              Foto de perfil</h3>
          <div class="row h-100 text-center justify-content-center align-items-center">
            <div class="col-12">
              <div class="img-preve">
                  <img class="rounded-circle" src="<?php  echo SERVERURL;?>vistas/images/general/cliente-foto.svg" id="imgpreve">
              </div>
            </div>
            <div class="col-12">
              <div class="btn_upload">
                <input type="file" id="archivo_foto_subir_admin" name="">
                Subir Imagen
                <span class="icon-foto-subir">
                  <i class="fas fa-camera-retro fa-sm"></i>
                </span>
              </div>
              <div class="error_msg"></div>
            </div>
            
          </div>
        </div>
        <!-- X-FOTO PERFIL-X -->

        <h3 class="sub-titulo-panel">
            <i class="fas fa-award"></i>
          &nbsp; Nivel de privilegios</h3>

        <div class="container-fluid">
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <p class="text-left">
                <div class="badge badge-success">Nivel 1</div> Control total del sistema
              </p>
              <p class="text-left">
                <div class="badge badge-primary">Nivel 2</div> Permiso para registro y actualización
              </p>
              <p class="text-left">
                <div class="badge badge-info">Nivel 3</div> Permiso para registro
              </p>
            </div>
            <div class="col-xs-12 col-sm-6 d-flex flex-column">
              <div class="custom-control custom-radio custom-control-inline mb-4">
                <input type="radio" id="optionsRadios1" value="1" name="optionsPrivilegio" class="custom-control-input">
                <label class="custom-control-label" for="optionsRadios1">Nivel 1</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline mb-4">
                <input type="radio" id="optionsRadios2" value="2" name="optionsPrivilegio" class="custom-control-input">
                <label class="custom-control-label" for="optionsRadios2">Nivel 2</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="optionsRadios3" value="3" name="optionsPrivilegio" checked="" class="custom-control-input">
                <label class="custom-control-label" for="optionsRadios3">Nivel 3</label>
              </div>
            </div>
          </div>
        </div>


      </div>
      <!-- x- DATOS DE CUENTA -x -->
      
      <div class="col-lg-12 text-center mt-5">
        <button type="submit" id="edit_cuenta" class="btn btn-primary">Actualizar</button>   
        <a href="<?php  echo SERVERURL;?>listaUsuario/" class="btn btn-primary">Lista Usuarios</a>                   
      </div>
      
    </div> <!-- X/ row-->
  </form>
</div>