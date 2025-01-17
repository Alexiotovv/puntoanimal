<div class="titulo-linea mt-2">
  <h2>
    <i class="flaticon-011-dog"></i>
  Registro Mascota</h2>
  <hr class="sidebar-divider">
</div>
<?php
  $Todalarray=count($pagina);
  if($Todalarray==2){
    require_once "./controladores/clienteControlador.php";
    $inst_cliente = new clienteControlador();
    $datos_cliente=$inst_cliente->datos_cliente_controlador("Unico",$pagina[1]);

    if($datos_cliente->rowCount()==1){
      $campos=$datos_cliente->fetch();
        $perfil_cliente='<input type="hidden" name="perfil_id_dni" value="'.$campos['clienteDniCedula'].'">
      ';
    }else{
      $perfil_cliente="";
    }
  }else{
    $perfil_cliente="";
  }
 ?>
<div class="intro add-mascota-form mb-4">
  <!-- agregar desde perfil cliente agregar mascota ajaxsearch js 186-->
  <form id="form-add-Mascota" action="<?php  echo SERVERURL; ?>ajax/mascotaAjax.php" data-form="save"  method="POST" class="FormularioAjax" enctype="multipart/form-data">
  <?php echo $perfil_cliente; ?>
    <div class="row">
      <div class="col-lg-8">
        <div class="row">
          <div class="col-lg-12 mb-2">
            <h3 class="sub-titulo-panel">
              <i class="flaticonv-003-appointment"></i>
                Información General</h3>
          </div> 
          <div class="col-md-6">
            <div class="group">
              <input type="text"  name="mascota_nombre_reg" id="mascota_nombre" maxlength="40" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-group date group" id="id_fecha">
                <input type="text" name="mascota_fecha_reg" value="" class="" required="" />
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
                    echo '<option value="'.$ins_loginc->encryption($rowE['idEspecie']).'">'.$rowE['espNombre'].'</option>';
                  }
                ?>
              <!-- *** dinamico ** -->
            </select>
          </div>
          <div class="col-md-6 mb-2">
            <small>Raza</small><br>
            <select class="selectpicker w-100" name="mascota_raza_reg" id="mascota_raza" data-live-search="true" data-show-subtext="true">
              <!-- ***dinamico depende de especie -->
               
              <!-- ***dinamico  -->
            </select>
         </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" name="mascota_peso_reg"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Peso (Kg)</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text"  name="mascota_color_reg" />
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
                    <input type="radio" id="radio-sexo-hembra" name="mascota_sexo_reg" value="Hembra" class="custom-control-input" checked="">
                    <label class="custom-control-label" for="radio-sexo-hembra">Hembra</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="radio-sexo-macho" name="mascota_sexo_reg" value="Macho" class="custom-control-input">
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
                    <input type="radio" id="radio-castrado-si" name="mascota_castrado_reg" value="Si" class="custom-control-input" checked="">
                    <label class="custom-control-label" for="radio-castrado-si">Si</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="radio-castrado-no" name="mascota_castrado_reg" value="No" class="custom-control-input">
                    <label class="custom-control-label" for="radio-castrado-no">No</label>
                  </div>  
                </div>
              </div>
          </div>
          <!-- DUEÑO -->
          <div class="col-md-6 mb-4" id="mascota-dueno">
            <small>Dueño</small>

            <div class="d-flex flex-row mascota-dueno align-items-center mt-2">
              <img style="width: 55px; height: 55px;" id="imgpreve-cliente-m" class="rounded-circle mr-2" src="<?php  echo SERVERURL;?>vistas/images/general/user-foto.svg" alt="fotocliente">
              <select class="selectpicker w-100"  name="mascota-dueno" id="select-dueno" serverurl="<?php echo SERVERURL; ?>" data-show-subtext="true" data-live-search="true">
                <!-- ***dinamico***(cargar cliente,input key buscar) -->
                
                <!-- *x**dinamico**x* -->
              </select>
            </div>
          </div>
          <div class="col-12">
            <div class="group">
              <textarea type="textarea" name="mascota_infadicional_reg"></textarea>
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
        <a  href="<?php  echo SERVERURL;?>listaMascota/" class="btn btn-primary">Volver</a>                
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
      <!-- ---x-----MODAL AVATAR SELECCIONAR --- X -->


    </div>
  </form>
</div>