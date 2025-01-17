<div class="titulo-linea mt-2">
  <h2><i class="far fa-user"></i>Registro Veterinario</h2>
  <hr class="sidebar-divider">
</div>
<?php
  $inputventa="";
  if($pagina[1]!=""){
      if($pagina[1]=="venta"){
         $inputventa='<input type="hidden" name="add_veterinario_venta" value="'.$pagina[1].'">';
      }
  } 
 ?>
<div class="intro add-veterinario-form mb-4">
  <form id="form-add-Veterinario" action="<?php  echo SERVERURL; ?>ajax/veterinarioAjax.php" data-form="save"  method="POST" class="FormularioAjax" enctype="multipart/form-data">
    <div class="row">
      <!-- INF GENERAL -->
      <div class="col-lg-8">
        <h3 class="sub-titulo-panel">
          <i class="far fa-id-card"></i>
            Información General</h3>
            <?php echo $inputventa; ?>
        <div class="row mt-2">
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[0-9-]{7,20}" name="veterinario_dni_reg" id="veterinario_dni" maxlength="20" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>NIT/CI</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" name="veterinario_nombre_reg" id="veterinario_nombre" maxlength="35" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" name="veterinario_apellido_reg" id="veterinario_apellido" maxlength="35" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Apellido</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[0-9()+]{8,20}" name="veterinario_telefono_reg" id="veterinario_telefono" maxlength="20" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Telefono</label>
            </div>
          </div>
          <div class="col-12">
            <div class="group">
              <input type="text" name="veterinario_especialidad_reg" id="veterinaria_especialidad" maxlength="70" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Especialidad</label>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="d-flex flex-column radio-de">
              <span>Genero</span>
              <div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" value="Femenino" id="radio-cli-fem" name="veterinario_genero_reg" class="custom-control-input" checked="">
                  <label class="custom-control-label" for="radio-cli-fem">Femenino</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" value="Masculino" id="radio-cli-mas" name="veterinario_genero_reg" class="custom-control-input">
                  <label class="custom-control-label" for="radio-cli-mas">Masculino</label>
                </div>  
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" name="veterinario_direccion_reg" id="veterinario_direccion" maxlength="150" required="" />
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
            <div class="error_msg"></div>
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
        <a href="<?php  echo SERVERURL;?>listaVeterinario/" class="btn btn-primary">Lista Veterinarios</a>                   
      </div>
      <!-- -----MODAL AVATAR SELECCIONAR -------- -->
      <div class="modal fade" id="modalAvatar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-user mr-3"></i>Seleccionar Avatar</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12">  
                  <div id="radios">
                    <label for="avatar1">
                      <input type="radio" name="avatar-veterinario" id="avatar1" data-fotoveterinario="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_1.svg" value="avatar_cli_1.svg"  />
                      <img src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_1.svg" alt="foto">
                    </label>               
                    <label for="avatar2">
                      <input type="radio" name="avatar-veterinario" id="avatar2" data-fotoveterinario="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_2.svg" value="avatar_cli_2.svg"/>
                      <img  src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_2.svg" alt="foto">
                    </label>
                    <label for="avatar3">
                      <input type="radio" name="avatar-veterinario" id="avatar3" data-fotoveterinario="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_3.svg" value="avatar_cli_3.svg"/>
                      <img src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_3.svg" alt="foto">
                    </label>
                    <label for="avatar4">
                      <input type="radio" name="avatar-veterinario" id="avatar4" data-fotoveterinario="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_4.svg" value="avatar_cli_4.svg"/>
                      <img  src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_4.svg" alt="foto">
                    </label>
                    <label for="avatar5">
                      <input type="radio" name="avatar-veterinario" id="avatar5" data-fotoveterinario="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_5.svg" value="avatar_cli_5.svg"/>
                      <img  src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_5.svg" alt="foto">
                    </label>
                    <label for="avatar6">
                      <input type="radio" name="avatar-veterinario" id="avatar6" data-fotoveterinario="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_6.svg" value="avatar_cli_6.svg"/>
                      <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_6.svg" alt="foto">
                    </label>
                    <label for="avatar7">
                      <input type="radio" name="avatar-veterinario" id="avatar7" data-fotoveterinario="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_7.svg" value="avatar_cli_7.svg"/>
                      <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_7.svg" alt="foto">
                    </label>
                    <label for="avatar8">
                      <input type="radio" name="avatar-veterinario" id="avatar8" data-fotoveterinario="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_10.svg" value="avatar_cli_10.svg"/>
                      <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_10.svg" alt="foto">
                    </label>
                    <label for="avatar9">
                      <input type="radio" name="avatar-veterinario" id="avatar9" data-fotoveterinario="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_11.svg" value="avatar_cli_11.svg"/>
                      <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_11.svg" alt="foto">
                    </label>
                    <label for="avatar10">
                      <input type="radio" name="avatar-veterinario" id="avatar10" data-fotoveterinario="<?php  echo SERVERURL;?>vistas/images/avatar_user_cli/avatar_cli_12.svg" value="avatar_cli_12.svg"/>
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
</div>