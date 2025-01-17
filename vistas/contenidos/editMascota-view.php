<div class="titulo-linea mt-2">
  <h2>
    <i class="flaticon-011-dog"></i>
  Editar Mascota</h2>
  <hr class="sidebar-divider">
</div>
<div class="intro add-mascota-form mb-4">
  <?php
    require_once "./controladores/mascotaControlador.php";
    $inst_mascota = new mascotaControlador();

    $datos_mascota=$inst_mascota->datos_mascota_controlador("Unico",$pagina[1]);

    if($datos_mascota->rowCount()==1){
      $campos=$datos_mascota->fetch();
    
   ?>
  <form id="form-add-Mascota" action="<?php  echo SERVERURL; ?>ajax/mascotaAjax.php" data-form="update"  method="POST" class="FormularioAjax" enctype="multipart/form-data">
    <input type="hidden" name="mascota_codigo_up" value="<?php echo $pagina[1] ; ?>">
    <div class="row">
      <div class="col-lg-8">
        <div class="row">
          <div class="col-lg-12 mb-2">
            <h3 class="sub-titulo-panel">
              <i class="flaticonv-003-appointment"></i>
                Información General</h3>
             <span class="float-right"><?php echo $campos['codMascota']; ?></span>   
          </div> 
          <div class="col-md-6">
            <div class="group">
              <input type="text"  name="mascota_nombre_edit" id="mascota_nombre" maxlength="40" value="<?php echo $campos['mascotaNombre'];?>" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-group date group" id="id_fecha">
                <input type="text" name="mascota_fecha_edit" value="<?php echo $campos['mascotaFechaN'];?>" class="" />
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Fecha Nacimiento</label>
                <div class="input-group-addon input-group-append">
                    <div class="input-icon">
                        <i class="fas fa-birthday-cake"></i>
                    </div>
                </div>
            </div>
          </div>
          <div class="col-md-6 mb-2">
            <small>Especie</small><br>
            <select class="selectpicker w-100" serverurl="<?php echo SERVERURL; ?>" name="mascota_especie_reg" id="mascota_especie" data-live-search="true">
              <!-- *** dinamico ** -->
                <?php  
                  require_once "./controladores/especieControlador.php";
                  $insEm=new especieControlador();

                  $dataE=$insEm->buscar_especie_controlador("Select");
 
                  while($rowE=$dataE->fetch()){
                    $esp="";
                    if($rowE['idEspecie']==$campos['idEspecie']){
                      $esp='selected=""';
                    }
                    echo '<option value="'.$ins_loginc->encryption($rowE['idEspecie']).'" '.$esp.' >'.$rowE['espNombre'].'</option>';
                  }
                ?>
                <!-- <option selected="true"></option> -->
              <!-- *** dinamico ** -->
            </select>
          </div>
          <div class="col-md-6 mb-2">
            <small>Raza</small><br>
            <input type="hidden" name="id_raza_edit" value="<?php echo $campos['idRaza']; ?>">
            <select class="selectpicker w-100" name="mascota_raza_reg" id="mascota_raza" data-live-search="true" data-show-subtext="true">
              <!-- ***dinamico depende de especie -->
                
              <!-- ***dinamico  -->
            </select>
         </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text"  name="mascota_peso_edit" value="<?php echo $campos['mascotaPeso']; ?>" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Peso (Kg)</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text"  name="mascota_color_edit" value="<?php echo $campos['mascotaColor']; ?>" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Color</label>
            </div>
          </div>
          <div class="col-md-6 mb-4">
              <div class="d-flex flex-column radio-de">
                <span>Sexo</span>
                <div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="radio-sexo-hembra" name="mascota_sexo_edit" value="Hembra" class="custom-control-input" <?php if($campos['mascotaSexo']=="Hembra"){echo 'checked=""';} ?> >
                    <label class="custom-control-label" for="radio-sexo-hembra">Hembra</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="radio-sexo-macho" name="mascota_sexo_edit" value="Macho" class="custom-control-input" <?php if($campos['mascotaSexo']=="Macho"){echo 'checked=""';} ?> >
                    <label class="custom-control-label" for="radio-sexo-macho">Macho</label>
                  </div>  
                </div>
              </div>
          </div>

          <div class="col-md-6 mb-4">
              <div class="d-flex flex-column radio-de">
                <span>Castrado</span>
                <div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="radio-castrado-si" name="mascota_castrado_edit" value="Si" class="custom-control-input" <?php if($campos['castrado']=="Si"){echo 'checked=""';} ?> >
                    <label class="custom-control-label" for="radio-castrado-si">SI</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="radio-castrado-no" name="mascota_castrado_edit" value="No" class="custom-control-input" <?php if($campos['castrado']=="No"){echo 'checked=""';} ?> >
                    <label class="custom-control-label" for="radio-castrado-no">NO</label>
                  </div>  
                </div>
              </div>
          </div>
          <!-- DUEÑO -->
          <div class="col-md-6 mb-4" id="mascota-dueno">
            <input type="hidden" name="dueno_id_dni" value="<?php echo $campos['dniDueno']; ?>">
            <small>Dueño</small>
            <div class="d-flex flex-row mascota-dueno align-items-center mt-2">
              <img style="width: 55px; height: 55px;" id="imgpreve-cliente-m" class="rounded-circle mr-2" src="<?php  echo SERVERURL;?>vistas/images/general/user-foto.svg" alt="fotocliente">
              <select class="selectpicker w-100" name="mascota-dueno" id="select-dueno" serverurl="<?php echo SERVERURL; ?>" data-show-subtext="true" data-live-search="true">
                <!-- ***dinamico***(cargar cliente,input key buscar) -->
                 
                <!-- *x**dinamico**x* -->
              </select>
            </div>
          </div>
          <div class="col-12">
            <div class="group">
              <textarea type="textarea" name="mascota_infadicional_edit"><?php echo $campos['mascotaAdicional']; ?></textarea>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Inf. Adicional</label>
            </div>
          </div>
        </div>
      </div>
      <!-- FOTO PERFIL -->
      <div class="col-lg-4">
        <h3 class="sub-titulo-panel">
              <i class="fas fa-camera"></i>
            Foto de perfil</h3>
        <div class="row h-100 text-center justify-content-center align-items-center">
          <div class="col-12">
            <div class="img-preve">
                <img class="rounded-circle" src="<?php  echo SERVERURL;?><?php echo $campos['mascotaFoto'];?>" id="imgpreve">
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
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="<?php  echo SERVERURL;?>listaMascota/" class="btn btn-primary">Lista Mascota</a>                         
      </div>
      <?php
        $avatar= explode("/", $campos['mascotaFoto']);
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
                            <input type="radio" name="avatar-mascota" id="avatar1" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar1.png" value="avatar1.png"  />
                            <img src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar1.png" alt="foto">
                          </label>               
                          <label for="avatar2">
                            <input type="radio" name="avatar-mascota" id="avatar2" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar2.jpg" value="avatar2.jpg"/>
                            <img  src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar2.jpg" alt="foto">
                          </label>
                          <label for="avatar3">
                            <input type="radio" name="avatar-mascota" id="avatar3" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar3.jpg" value="avatar3.jpg"/>
                            <img src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar3.jpg" alt="foto">
                          </label>
                          <label for="avatar4">
                            <input type="radio" name="avatar-mascota" id="avatar4" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar4.jpg" value="avatar4.jpg"/>
                            <img  src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar4.jpg" alt="foto">
                          </label>
                          <label for="avatar5">
                            <input type="radio" name="avatar-mascota" id="avatar5" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar5.jpg" value="avatar5.jpg"/>
                            <img  src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar5.jpg" alt="foto">
                          </label>
                          <label for="avatar6">
                            <input type="radio" name="avatar-mascota" id="avatar6" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar6.jpg" value="avatar6.jpg"/>
                            <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar6.jpg" alt="foto">
                          </label>
                          <label for="avatar7">
                            <input type="radio" name="avatar-mascota" id="avatar7" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar7.jpg" value="avatar7.jpg"/>
                            <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar7.jpg" alt="foto">
                          </label>
                          <label for="avatar8">
                            <input type="radio" name="avatar-mascota" id="avatar8" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar8.png" value="avatar8.png"/>
                            <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar8.png" alt="foto">
                          </label>
                          <label for="avatar9">
                            <input type="radio" name="avatar-mascota" id="avatar9" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar9.jpg" value="avatar9.jpg"/>
                            <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar9.jpg" alt="foto">
                          </label>
                          <label for="avatar10">
                            <input type="radio" name="avatar-mascota" id="avatar10" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar10.jpg" value="avatar10.jpg"/>
                            <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar10.jpg" alt="foto">
                          </label>
                          <label for="avatar11">
                            <input type="radio" name="avatar-mascota" id="avatar11" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar11.png" value="avatar11.png"/>
                            <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar11.png" alt="foto">
                          </label>
                          <label for="avatar12">
                            <input type="radio" name="avatar-mascota" id="avatar12" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar12.jpg" value="avatar12.jpg"/>
                            <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar12.jpg" alt="foto">
                          </label>
                          <label for="avatar13">
                            <input type="radio" name="avatar-mascota" id="avatar13" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar13.jpg" value="avatar13.jpg"/>
                            <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar13.jpg" alt="foto">
                          </label>
                          <label for="avatar14">
                            <input type="radio" name="avatar-mascota" id="avatar14" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar14.jpg" value="avatar14.jpg"/>
                            <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar14.jpg" alt="foto">
                          </label>
                          <label for="avatar15">
                            <input type="radio" name="avatar-mascota" id="avatar15" data-fotomascota="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar15.jpg" value="avatar15.jpg"/>
                            <img class="avatar-img" src="<?php  echo SERVERURL;?>vistas/images/avatar_mascota/avatar15.jpg" alt="foto">
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
    </div>
  </form>
  <?php }else{ ?>
  <div class="alert alert-danger text-center" role="alert">
    <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
    <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
    <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
  </div>
<?php } ?>

</div>