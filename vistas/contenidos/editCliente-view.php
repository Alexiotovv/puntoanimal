<div class="titulo-linea mt-2">
  <h2><i class="fas fa-user-edit"></i>Editar Cliente</h2>
  <hr class="sidebar-divider">
</div>

<div class="intro add-cliente-form mb-4">
  <?php
    require_once "./controladores/clienteControlador.php";
    $inst_cliente = new clienteControlador();

    $datos_cliente=$inst_cliente->datos_cliente_controlador("Unico",$pagina[1]);

    if($datos_cliente->rowCount()==1){
      $campos=$datos_cliente->fetch();
    
   ?>
  <form class="FormularioAjax" action="<?php  echo SERVERURL; ?>ajax/clienteAjax.php" data-form="update"  method="POST" id="form-add-Cliente">
    <input type="hidden" name="usuario_id_up" value="<?php echo $ins_loginc->encryption($campos['idCliente']); ?>">
    <div class="row">
      <!-- INF GENERAL -->
      <div class="col-lg-8">
        <h3 class="sub-titulo-panel">
          <i class="far fa-id-card"></i>
            Información General</h3>
        <div class="row mt-2">
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[0-9-]{1,20}" name="cliente_dni_edit" id="cliente_dni" maxlength="20" required="" value="<?php echo $campos['clienteDniCedula'];?>"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>NIT/CI</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" name="cliente_nombre_edit" id="cliente_nombre" maxlength="35" required="" value="<?php echo $campos['clienteNombre'];?>"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" name="cliente_apellido_edit" id="cliente_apellido" maxlength="35" required="" value="<?php echo $campos['clienteApellido'];?>"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Apellido</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[0-9()+]{8,20}" name="cliente_telefono_edit" id="cliente_telefono" maxlength="20" required="" value="<?php echo $campos['clienteTelefono'];?>"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Telefono</label>
            </div>
          </div>
          <div class="col-12">
            <div class="group">
              <input type="email" name="cliente_email_edit" id="cliente_email" maxlength="70" value="<?php echo $campos['clienteCorreo'];?>"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Email</label>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="d-flex flex-column radio-de">
              <span>Genero</span>
              <div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="radio-cli-fem" name="cliente_genero_edit" value="Femenino" class="custom-control-input" <?php if($campos['clienteGenero']=='Femenino'){echo 'checked=""';} ?> >
                  <label class="custom-control-label" for="radio-cli-fem">Femenino</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="radio-cli-mas" name="cliente_genero_edit" value="Masculino" class="custom-control-input" <?php if($campos['clienteGenero']=='Masculino'){echo 'checked=""';} ?>>
                  <label class="custom-control-label" for="radio-cli-mas">Masculino</label>
                </div>  
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" name="cliente_direccion_edit" id="cliente_direccion" maxlength="150" required="" value="<?php echo $campos['clienteDomicilio'];?>" />
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
                <img class="rounded-circle" src="<?php  echo SERVERURL;?><?php echo $campos['clienteFotoUrl'];?>" id="imgpreve">
            </div>
          </div>
          <div class="col-12">
            <div class="dropdown no-arrow">
              <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuFoto" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="text-gray-600 small">Seleccionar foto</span>
                <span class="icon-foto-subir">
                  <i class="fas fa-camera-retro fa-sm"></i>
                </span>
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuFoto">
                <span class="dropdown-item">
                  <i class="fas fa-upload fa-sm fa-fw mr-2 text-gray-400"></i>
                  Subir foto
                  <input type="file" name="archivo_foto_subir" id="archivo_foto_subir">
                </span>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalAvatar">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Seleccionar Avatar
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- X-FOTO PERFIL-X -->
      <div class="col-lg-12 text-center mt-5">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="<?php  echo SERVERURL;?>listaCliente/" class="btn btn-primary">Lista Clientes</a>                      
      </div>
      <?php
        $avatar= explode("/", $campos['clienteFotoUrl']);
          if($avatar[0]=="adjuntos"){
            $ruta_avatar="";
          }else{
            $ruta_avatar=$avatar[3];
          }

       ?>
      <!-- -----MODAL AVATAR SELECCIONAR -------- -->
      <div class="modal fade" id="modalAvatar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-user mr-3"></i>Seleccionar Avatar </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12">  
                  <div id="radios">
                    <label for="avatar1">
                      <input type="radio" name="avatar-cliente" id="avatar1" data-fotocliente="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_1.svg" value="avatar_cli_1.svg" <?php if($ruta_avatar=='avatar_cli_1.svg'){echo 'checked=""';} ?>   />
                      <img src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_1.svg" alt="foto">
                    </label>               
                    <label for="avatar2">
                      <input type="radio" name="avatar-cliente" id="avatar2" data-fotocliente="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_2.svg" value="avatar_cli_2.svg" <?php if($ruta_avatar=='avatar_cli_2.svg'){echo 'checked=""';} ?> />
                      <img  src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_2.svg" alt="foto">
                    </label>
                    <label for="avatar3">
                      <input type="radio" name="avatar-cliente" id="avatar3" data-fotocliente="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_3.svg" value="avatar_cli_3.svg" <?php if($ruta_avatar=='avatar_cli_3.svg'){echo 'checked=""';} ?>  />
                      <img src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_3.svg" alt="foto">
                    </label>
                    <label for="avatar4">
                      <input type="radio" name="avatar-cliente" id="avatar4" data-fotocliente="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_4.svg" value="avatar_cli_4.svg" <?php if($ruta_avatar=='avatar_cli_4.svg'){echo 'checked=""';} ?>  />
                      <img  src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_4.svg" alt="foto">
                    </label>
                    <label for="avatar5">
                      <input type="radio" name="avatar-cliente" id="avatar5" data-fotocliente="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_5.svg" value="avatar_cli_5.svg" <?php if($ruta_avatar=='avatar_cli_5.svg'){echo 'checked=""';} ?> />
                      <img  src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_5.svg" alt="foto">
                    </label>
                    <label for="avatar6">
                      <input type="radio" name="avatar-cliente" id="avatar6" data-fotocliente="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_6.svg" value="avatar_cli_6.svg" <?php if($ruta_avatar=='avatar_cli_6.svg'){echo 'checked=""';} ?> />
                      <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_6.svg" alt="foto">
                    </label>
                    <label for="avatar7">
                      <input type="radio" name="avatar-cliente" id="avatar7" data-fotocliente="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_7.svg" value="avatar_cli_7.svg" <?php if($ruta_avatar=='avatar_cli_7.svg'){echo 'checked=""';} ?> />
                      <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_7.svg" alt="foto">
                    </label>
                    <label for="avatar8">
                      <input type="radio" name="avatar-cliente" id="avatar8" data-fotocliente="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_10.svg" value="avatar_cli_10.svg" <?php if($ruta_avatar=='avatar_cli_10.svg'){echo 'checked=""';} ?> />
                      <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_10.svg" alt="foto">
                    </label>
                    <label for="avatar9">
                      <input type="radio" name="avatar-cliente" id="avatar9" data-fotocliente="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_11.svg" value="avatar_cli_11.svg" <?php if($ruta_avatar=='avatar_cli_11.svg'){echo 'checked=""';} ?> />
                      <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_11.svg" alt="foto">
                    </label>
                    <label for="avatar10">
                      <input type="radio" name="avatar-cliente" id="avatar10" data-fotocliente="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_12.svg" value="avatar_cli_12.svg" <?php if($ruta_avatar=='avatar_cli_12.svg'){echo 'checked=""';} ?> />
                      <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_12.svg" alt="foto">
                    </label>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <!-- ---x-----MODAL AVATAR SELECCIONAR --- X -->


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