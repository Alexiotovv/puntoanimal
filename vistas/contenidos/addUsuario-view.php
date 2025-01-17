<?php
  if($_SESSION['privilegio_vetp']!=1){
    echo $ins_loginc->forzar_cierre_sesion_controlador();
    exit();
  } 
 ?>
<div class="titulo-linea mt-2">
  <h2><i class="far fa-user"></i>Registro Usuario</h2>
  <hr class="sidebar-divider">
</div>
 
<div class="intro add-admin-form mb-4">
  <form class="FormularioAjax" action="<?php  echo SERVERURL; ?>ajax/usuarioAjax.php" method="POST" data-form="save" id="form-add-admin" enctype="multipart/form-data">
    <div class="row">
      <!-- INF GENERAL -->
      <div class="col-lg-8">
        <h3 class="sub-titulo-panel">
          <i class="far fa-id-card"></i>
            Información General</h3>
        <div class="row mt-2">
          <div class="col-md-6">
            <div class="group"> 
              <input type="text"  name="usuario_dni_reg" id="usuario_dni" maxlength="20" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>NIT/CI</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" name="usuario_nombre_reg" id="usuario_nombre" maxlength="35" required=""/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" name="usuario_apellido_reg" id="usuario_apellido" maxlength="35" required="required"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Apellido</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[0-9()+]{8,20}" name="usuario_telefono_reg" id="usuario_telefono" maxlength="20" required="required"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Telefono</label>
            </div>
          </div>
          <!-- <div class="col-md-6 mb-4">
            <div class="d-flex flex-column radio-de">
              <span>Genero</span>
              <div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="radio-cli-fem" name="radio-cli-genero" class="custom-control-input">
                  <label class="custom-control-label" for="radio-cli-fem">Femenino</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="radio-cli-mas" name="radio-cli-genero" class="custom-control-input">
                  <label class="custom-control-label" for="radio-cli-mas">Masculino</label>
                </div>  
              </div>
            </div>
          </div> -->
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" name="usuario_direccion_reg" id="usuario_direccion" maxlength="190" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Domicilio</label>
            </div>
          </div>
        </div>
      </div>
      <!-- x- INF GENERAL -x -->
      <!-- FOTO PERFIL -->
      <div class="col-lg-4">
        <h3 class="sub-titulo-panel">
              <i class="fas fa-camera"></i>
            Foto de perfil</h3>
        <div class="row h-100 text-center justify-content-center align-items-center">
          <div class="col-12">
            <div class="img-preve">
                <img class="rounded-circle" src="<?php  echo SERVERURL;?>vistas/images/general/user-foto.svg" id="imgpreve">
            </div>
          </div>
          <div class="col-12">
            <div class="btn_upload">
              <input type="file" id="archivo_foto_subir_admin" name="usuario_foto_reg">
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
      <!-- DATOS DE CUENTA -->
      <div class="col-lg-12 mt-4">
        <h3 class="sub-titulo-panel">
              <i class="fas fa-user-lock"></i>
            Datos de la cuenta</h3>
        <div class="row mt-2">
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-Z0-9]{1,35}" name="usuario_usuario_reg" id="usuario_usuario" maxlength="35" required="required"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre de usuario</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="email" name="usuario_email_reg" id="usuario_email" maxlength="70"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Email</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="password" name="usuario_clave_1_reg" id="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required="required"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Contraseña</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="password" name="usuario_clave_2_reg" id="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required=""/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Repita Contraseña</label>
            </div>
          </div>          
          
        </div>
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
                <input type="radio" id="optionsRadios1" value="1" name="usuario_privilegio_reg" class="custom-control-input">
                <label class="custom-control-label" for="optionsRadios1">Nivel 1</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline mb-4">
                <input type="radio" id="optionsRadios2" value="2" name="usuario_privilegio_reg" class="custom-control-input">
                <label class="custom-control-label" for="optionsRadios2">Nivel 2</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="optionsRadios3" value="3" name="usuario_privilegio_reg" checked="" class="custom-control-input">
                <label class="custom-control-label" for="optionsRadios3">Nivel 3</label>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- x- DATOS DE CUENTA -x -->
    
      <div class="col-lg-12 text-center mt-5">
        <button type="submit" class="btn btn-primary">Guardar</button>  
        <a href="<?php  echo SERVERURL;?>listaUsuario/" class="btn btn-primary">Lista Usuarios</a>                     
      </div>
      
    </div> <!-- X/ row-->
  </form>
</div>