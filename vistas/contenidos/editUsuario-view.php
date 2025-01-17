<?php 
  // si el usuario id de sesion es distinto al de la url no es el propitario de la cuenta
  if($ins_loginc->encryption($_SESSION['id_vetp'])!=$pagina[1]){
    if($_SESSION['privilegio_vetp']!=1){
      echo $ins_loginc->forzar_cierre_sesion_controlador();
      exit();
    }

  }
 ?>
<div class="titulo-linea mt-2">
  <h2><i class="far fa-user"></i>Editar Usuario</h2>
  <hr class="sidebar-divider">
</div>

<div class="intro add-admin-form mb-4">
  <?php
    require_once "./controladores/usuarioControlador.php";
    $inst_usuario = new usuarioControlador();

    $datos_usuario=$inst_usuario->datos_usuario_controlador("Unico",$pagina[1]);

    if($datos_usuario->rowCount()==1){
      $campos=$datos_usuario->fetch();
    
   ?>
  <form class="FormularioAjax" action="<?php  echo SERVERURL; ?>ajax/usuarioAjax.php" data-form="update"  method="POST" id="form-edit-admin">
    <?php 
    // si el codigo del usuario logeado es distinto al editado
      // if($_SESSION['tipo_sbp']!="Administrador" || $_SESSION['privilegio_sbp']<1 || $_SESSION['privilegio_sbp']>2){
      //   echo $lc->forzar_cierre_sesion_controlador();
      // }else{
      //   //tiene los permisos
      //   echo '<input type="hidden" name="privilegio_up" value="verdadero">';
      // }
    
     ?>
    <input type="hidden" name="usuario_id_up" value="<?php echo $pagina[1] ; ?>">
    <div class="row">
      <!-- INF GENERAL -->
      <div class="col-lg-8">
        <h3 class="sub-titulo-panel">
          <i class="far fa-id-card"></i>
            Información General</h3>
        <div class="row mt-2">
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[0-9-]{1,20}" name="usuario_dni_edit" id="usuario_dni" maxlength="20" required="" value="<?php echo $campos['userDni'];?>" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>NIT/CI</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" name="usuario_nombre_edit" id="usuario_nombre" maxlength="35" required="" value="<?php echo $campos['userNombre'];?>"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" name="usuario_apellido_edit" id="usuario_apellido" maxlength="35" required="" value="<?php echo $campos['userApellido'];?>"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Apellido</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[0-9()+]{8,20}" name="usuario_telefono_edit" id="usuario_telefono" maxlength="20" required="" value="<?php echo $campos['userTelefono'];?>"/>
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
              <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" name="usuario_direccion_edit" id="usuario_direccion" maxlength="190" required="" value="<?php echo $campos['userDomicilio'];?>"/>
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
                <img class="rounded-circle" src="<?php  echo SERVERURL.$campos['userFoto'];?>" id="imgpreve">
            </div>
          </div>
          <div class="col-12">
            <div class="btn_upload">
              <input type="file" id="archivo_foto_subir_admin" name="usuario_foto_edit">
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
        <div class="row mt-2 mb-4">
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-Z0-9]{1,35}" name="usuario_usuario_edit" id="usuario_usuario" maxlength="35" required="" value="<?php echo $campos['userUsuario'];?>"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre de usuario</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="email" name="usuario_email_edit" id="usuario_email" maxlength="70" required="" value="<?php echo $campos['userEmail'];?>"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Email</label>
            </div>
          </div>
          <!-- usuario nivel 1 estado de cuenta ESTADO -->
          <?php if($_SESSION['privilegio_vetp']==1 && $campos['id']!=1){?>
          <div class="col-md-6">
            <div class="group">
              <span>Estado de la cuenta &nbsp; 
                <?php if($campos['userEstado']=="Activa"){ 
                  echo '<span class="badge badge-info">Activa</span>';}
                  else{ 
                    echo '<span class="badge badge-danger">Deshabilitada</span>'; } ?> </span>
              <select  name="usuario_estado_up">
                <option value="Activa" <?php if($campos['userEstado']=="Activa"){echo 'Selected=""';} ?>>Activa</option>
                <option value="Deshabilitada" <?php if($campos['userEstado']=="Deshabilitada"){echo 'Selected=""';} ?>>Deshabilitada</option>
              </select>
              <span class="highlight"></span>
              <span class="bar"></span>
            </div>
          </div>
          <?php } ?>
          <!-- usuario nivel 1 -->
          
          <div class="col-12 mt-4">
            <h3 class="sub-titulo-panel">
            <i class="fas fa-lock"></i>
          &nbsp; Cambiar Contraseña</h3>
          <p>Para actualizar la contraseña de esta cuenta ingrese una nueva y vuelva a escribirla. En caso que no desee actualizarla debe dejar vacíos los dos campos de las contraseñas.</p>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="password" name="usuario_clave_nueva_1" id="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Contraseña</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="password" name="usuario_clave_nueva_2" id="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Repita Contraseña</label>
            </div>
          </div>
                       
        </div>

         <!-- usuario nivel 1 privilegio-->
        <?php if($_SESSION['privilegio_vetp']==1 && $campos['id']!=1){?>
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
            <!-- checked="" -->
            <div class="col-xs-12 col-sm-6 d-flex flex-column">
              <div class="custom-control custom-radio custom-control-inline mb-4">
                <input type="radio" id="optionsRadios1" value="1" name="usuario_privilegio_edit" class="custom-control-input" <?php if($campos['userPrivilegio']=='1'){echo 'checked=""';} ?>>
                <label class="custom-control-label" for="optionsRadios1">Nivel 1 <?php if($campos['userPrivilegio']=='1'){echo '<span class="badge badge-info">Actual</span>';} ?></label>
              </div>
              <div class="custom-control custom-radio custom-control-inline mb-4">
                <input type="radio" id="optionsRadios2" value="2" name="usuario_privilegio_edit" class="custom-control-input" <?php if($campos['userPrivilegio']=='2'){echo 'checked=""';} ?>>
                <label class="custom-control-label" for="optionsRadios2">Nivel 2 <?php if($campos['userPrivilegio']=='2'){echo '<span class="badge badge-info">Actual</span>';} ?> </label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="optionsRadios3" value="3" name="usuario_privilegio_edit"  class="custom-control-input" <?php if($campos['userPrivilegio']=='3'){echo 'checked=""';} ?>>
                <label class="custom-control-label" for="optionsRadios3">Nivel 3 <?php if($campos['userPrivilegio']=='3'){echo '<span class="badge badge-info">Actual</span>';} ?></label>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
        <fieldset>
          <p class="text-center">Para poder guardar los cambios en esta cuenta debe de ingresar su nombre de usuario y contraseña</p>
          <div class="container-fluid">
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="group">
                  <input type="text" name="usuario_admin" id="usuario_admin" pattern="[a-zA-Z0-9]{1,35}" maxlength="35" />
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label>Nombre de usuario</label>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="group">
                  <input type="password" name="clave_admin" id="clave_admin" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" />
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label>Contraseña</label>
                </div>
              </div>
            </div>
          </div>
        </fieldset>

      </div>
      <!-- x- DATOS DE CUENTA -x -->
      <!-- cuenta no propia  -->
      <?php if($ins_loginc->encryption($_SESSION['id_vetp'])!=$pagina[1]){ ?>
        <input type="hidden" name="tipo_cuenta" value="Impropia">
      <?php }else{ ?>
        <input type="hidden" name="tipo_cuenta" value="Propia">
      <?php } ?>
      
      <div class="col-lg-12 text-center mt-5">
        <button type="submit" class="btn btn-primary">Actualizar</button>   
        <a href="<?php  echo SERVERURL;?>listaUsuario/" class="btn btn-primary">Lista Usuarios</a>                   
      </div>
      
    </div> <!-- X/ row-->
  </form>
  <?php }else{ ?>
  <div class="alert alert-danger text-center" role="alert">
    <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
    <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
    <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
  </div>
<?php } ?>

</div>