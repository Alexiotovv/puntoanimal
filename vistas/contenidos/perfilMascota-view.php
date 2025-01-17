<div class="titulo-linea mt-2">
  <h2><i class="flaticon-023-pets"></i>Perfil Mascota</h2>
  <hr class="sidebar-divider">
</div>
<div class="row">
  <?php
    require_once "./controladores/mascotaControlador.php";
    $inst_mascota = new mascotaControlador();

    $datos_mascota=$inst_mascota->datos_mascota_controlador("Perfil",$pagina[1]);

    if($datos_mascota->rowCount()==1){
      $campos=$datos_mascota->fetch();
      $edad = $ins_loginc->calcular_edad($campos['mascotaFechaN']);
      $url_cliente = SERVERURL."perfilCliente/".$ins_loginc->encryption($campos['clienteDniCedula'])."/";
      $url_historia_m=SERVERURL."addHistorialM/".$pagina[1]."/";
   ?>
  <!-- ------------ INF. GENERAL ------------ --> 
  <div class="col-lg-8 mb-4">
    <div class="intro">
      <div class="row mascota-info-perfil">
        <!-- foto perfil  -->
        <div class="col-lg-4 mb-4">
          <div class="profile-img">
            <img class="img" src="<?php  echo SERVERURL.$campos['mascotaFoto'];?>" alt="fotoMascotaperfil">
          </div>
          <div class="text-center">
            <h5><b><?php echo $campos['mascotaNombre'];?></b></h5>
            <span><b>Edad : </b><?php echo $edad; ?></span>
          </div>
        </div> <!-- x foto perfil x  -->
        <!-- info. general  -->
        <div class="col-lg-8">
          <div class="inf-profile">
            <div class="row">
              <div class="col-lg-12">
                <h3 class="sub-titulo-panel">
                <i class="flaticonv-003-appointment"></i>
                  Información General</h3>
                <hr>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4">
                <span>Codigo : <br><b><?php echo $campos['codMascota'];?></b></span>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4">
                <span>Fecha N. : <br><b><?php echo $campos['mascotaFechaN'];?></b></span>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                <span>Peso : <br><b><?php echo $campos['mascotaPeso'];?> Kg</b></span>
              </div>
              <!--  -->
              <div class="col-lg-4 col-md-4 col-sm-4">
                  <span>Sexo : <br><b><?php echo $campos['mascotaSexo'];?></b></span>
              </div>
              
              <div class="col-lg-4 col-md-4 col-sm-4">
                <span>Especie : <br><b><?php  echo $campos['espNombre'];?></b></span>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                <span>Raza : <br><b><?php  echo $campos['razaNombre'];?></b></span>
              </div>
              <!--  -->
              <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                <span>Color : <br><b><?php echo $campos['mascotaColor'];?></b></span>
              </div>
              
              <div class="col-lg-6 col-md-4 col-sm-4 mb-4">
                <div class="tooltip-example">
                  <span>Dueño: </span> 
                  <span class="tooltip_body">
                    <strong><?php echo $campos['clienteNombre'].' '.$campos['clienteApellido']; ?></strong>
                    <span class="tooltip__content shadow text-center">
                      <img class="rounded-circle" src="<?php echo SERVERURL.$campos['clienteFotoUrl']; ?>">
                      <div class="d-flex flex-column">
                        <i class="fas fa-phone fa-sm"></i>
                        <span>
                           <?php echo " ".$campos['clienteTelefono']; ?>
                         </span>
                          <i class="fas fa-envelope fa-sm"></i>
                          <span>
                          <?php echo " ".$campos['clienteCorreo']; ?>
                        </span>  
                        <a href="<?php echo $url_cliente; ?>" style="color: #00ff00;">Ver Perfil</a>
                      </div>
                      
                    </span>
                  </span>
                </div>              
              </div>
            
              <div class="col-lg-4 col-md-4 col-sm-4">
                <span>Inf. Adicional : <br><b><?php echo $campos['mascotaAdicional'];?></b></span>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4">
                  <span>Castrado : <br><b><?php echo $campos['castrado'];?></b></span>
              </div>
              
            </div> <!-- x /row x  -->
          </div> <!-- x /inf-profile x  -->
        </div> <!-- x /info. general x  -->
      </div> <!-- x / row mascota-info-perfil  -->
    </div> <!-- x / intro  -->
  </div> 
  <!-- X X INFO. GENERAL X X  -->
  <!---------------- NOTAS -------------------------------->
  <div class="col-lg-4 mb-4">
    <div class="intro notas-mascotas h-100">    
      <!-- header btn  -->
      <h3 class="sub-titulo-panel"><i class="flaticonv-021-dog-tag"></i>Nota
      </h3>
        <button style="cursor: pointer;" id="btn-nueva-nota" class="btn btn-sm btn-primary float-right">
          <i class="fas fa-plus mr-2"></i>Nueva</button>
        <hr>
        <!-- X header X -->
        <div class="notas-mascotas-list overflow-auto scroll-style-1">
          <!-- FORMULARIO NUEVA NOTA -->
          <div id="form-nota-nueva">
            <form class="Formulario_ajax_simple" method="POST" action="<?php  echo SERVERURL; ?>ajax/notaAjax.php" >
              <div class="d-flex flex-column">
                <input type="hidden" name="nota_codmascota_reg" id="nota_codmascota" value="<?php echo $campos['codMascota'];?>">

                <div class="group">
                  <textarea name="nota_descripcion_reg" id="descrip-nueva-nota" maxlength=""></textarea>
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label>Nota</label>
                </div>
                <small class="mb-2">Maximo de <span class="max"></span> caracteres <span id="contador" class="float-right"></span></small>
                <div class="btns-notas-nueva">
                  <button type="submit" id="guardar_nota" class="btn btn-sm btn-primary float-right ml-2">Guardar</button>
                  <span style="cursor: pointer;" id="btn-cancelar-nota" class="btn btn-sm btn-secondary float-right">Cancelar</span>
                </div>  
              </div>
            </form>
          </div>
          <!-- X X FORMULARIO NUEVA NOTA x x -->
          <!-- -- LISTA NOTAS --->
          <ul class="todo-list mt-2" data-widget="todo-list" id="results">
            <!-- lista dinamico *** -->
                <!-- load-more.js -> displayRecords(lim, off) -->
            <!-- x lista dinamico x-->
          </ul>
          <div class="text-center mt-2">
            <button class="btn btn-style-2 btn-sm" id="loader_image">Ver Mas</button>
            <div id="loader_message"></div>
          </div>
          
        </div>
      
    </div> <!-- X / intro  -->
      <!-- MODAL EDITAR NOTA -->
      <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header text-center">
              <h4 class="modal-title w-100"><i class="fas fa-pen mr-2 fa-xs"></i>Editar Nota</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form class="Formulario_ajax_simple" method="POST" action="<?php  echo SERVERURL; ?>ajax/notaAjax.php">
              <div class="modal-body">
                <div class="md-form">
                      <input type="hidden" name="nota_id_up" id="nota_id_edit">
                      <input type="hidden" name="nota_codmascota_up" id="nota_codmascota_edit">
                      <div class="group">
                        <textarea maxlength="" name="nota_descripcion_up" id="nota_descripcion_edit" rows="4"></textarea>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>Nota</label>
                      </div>  
                  
                  <small>Maximo de <span class="max"></span> caracteres <span id="contador_edit" class="float-right"></span></small>
                </div>
              </div>
              <div class="modal-footer d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Guardar<i class="fas fa-edit ml-1"></i></button>
              </div>
            </form>

          </div>
        </div>
      </div>
      <!-- X X modal editar nota X X  -->
  </div>
  <!-- X X NOTAS X X  -->
  <!---------------------- HISTORIAL ---------------------->
  <div class="col-12">
    <div class="intro mb-4 hist-mascota-perf">
      <!-- Header tabs -->
      <ul class="nav nav-tabs py-2 mb-2">
        <li class="nav-item">
          <a class="sub-titulo-panel nav-link active" data-toggle="tab" href="#panel1">
            <i class="flaticon2-024-report"></i>
          Historial
            </a>
            <span class="badge badge-info badge-top total-hist">0</span>
        </li>
        <li class="ml-4 nav-item">
          <a class="sub-titulo-panel nav-link" data-toggle="tab" href="#panel2">
            <i class="flaticonv-011-syringe"></i>
            Vacunas</a>
            <span class="badge badge-info badge-top total-hist-vacuna">0</span>
        </li>
      </ul>
      <!-- x Header tabs x  -->
      
      <div class="tab-content">
        <!-- LISTA HISTORIAL -->
        <div style="max-height: 500px;" class="panel_historial_mascota overflow-auto scroll-style-1 tab-pane active" urlajax="<?php  echo SERVERURL;?>ajax/historialAjax.php" id="panel1">
          <a href="<?php echo $url_historia_m; ?>" class="btn btn-primary btn-sm mt-2"><i class="fa fa-plus mr-2"></i>Nueva Historia</a>  

          <ul class="timeline timeline-left" id="results_historial">
              <!-- *** dinamico historial ***-->
                    <!-- load-more.js ->> RecordsHistorialMascota(lim, off) -->
              <!-- ***-x- dinamico historial-x- *** -->
          </ul>
          <div class="text-center mb-2">
            <button class="btn btn-style-2 btn-sm" id="btn_mashistoria">Mostrar Mas</button>
            <div id="loader_mesg"></div>
          </div>
        </div>
        <!-- X X LISTA HISTORIAL X X  -->
        <!-- ----- PANEL VACUNAS HISTORIAL ---- -->
        <div style="max-height: 500px;" class="tab-pane panel_vacuna_historial_mascota overflow-auto scroll-style-1" urlajax="<?php  echo SERVERURL;?>ajax/vacunaHistorialAjax.php" id="panel2">
          <button data-toggle="modal" data-target=".modal-vacuna-add-sm" class="btn btn-primary btn-sm mt-2">
              <i class="fas fa-plus fa-sm"></i>
              Nueva vacuna
          </button>
          <ul class="timeline timeline-left" id="results_historial_vacuna">
              <!-- *** dinamico historial vacunas ***-->
                  <!-- load-more.js  ->> RecordsHistorialVacunaMascota() -->
              <!-- ***-x- dinamico historial vacunas -x- *** -->
          </ul>
        </div>
        <!-- ---X--- PANEL VACUNAS HISTORIAL --X-- -->
      </div>
    </div> <!-- X / intro  -->
  </div> <!-- X / col-12  -->
  <!-- X X HISTORIAL X X  -->
  <!-- ---MODAL ADD ADJUNTO HISTORIAL--- -->
  <div class="modal fade" id="modal-add-adjuntoh" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-upload fa-fw fa-sm"></i> Subir Archivos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <form class="Formulario_ajax_simple" method="POST" action="<?php  echo SERVERURL; ?>ajax/historialAjax.php" enctype="multipart/form-data">
            <div class="modal-body overflow-auto scroll-style-1" style="max-height: 400px;">
              <input type="hidden" name="adjunto_codhistoria_up" >
              <div class="form-adjunto-ht text-center">
                  <p>Archivos permitidos en formato 'pdf', 'jpg', 'jpeg', 'png'.
                  </p><br>
                  <span>Mis Documentos</span>
                  <div id="uploader">
                    <div class="row uploadDoc">
                    <div class="col-sm-4 d-flex align-items-center">
                      <!--error-->
                      <div class="docErr"><i class="fas fa-exclamation-circle mr-2 fa-sm"></i>Por favor elegir un archivo valido</div>
                      <!-- btn-elegir -->
                      <div class="fileUpload btn btn-elegir">
                        <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNTYgNTYiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDU2IDU2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBhdGggc3R5bGU9ImZpbGw6I0U5RTlFMDsiIGQ9Ik0zNi45ODUsMEg3Ljk2M0M3LjE1NSwwLDYuNSwwLjY1NSw2LjUsMS45MjZWNTVjMCwwLjM0NSwwLjY1NSwxLDEuNDYzLDFoNDAuMDc0YzAuODA4LDAsMS40NjMtMC42NTUsMS40NjMtMVYxMi45NzhjMC0wLjY5Ni0wLjA5My0wLjkyLTAuMjU3LTEuMDg1TDM3LjYwNywwLjI1N0MzNy40NDIsMC4wOTMsMzcuMjE4LDAsMzYuOTg1LDB6Ii8+PHBvbHlnb24gc3R5bGU9ImZpbGw6I0Q5RDdDQTsiIHBvaW50cz0iMzcuNSwwLjE1MSAzNy41LDEyIDQ5LjM0OSwxMiAiLz48cGF0aCBzdHlsZT0iZmlsbDojQzhCREI4OyIgZD0iTTQ4LjAzNyw1Nkg3Ljk2M0M3LjE1NSw1Niw2LjUsNTUuMzQ1LDYuNSw1NC41MzdWMzloNDN2MTUuNTM3QzQ5LjUsNTUuMzQ1LDQ4Ljg0NSw1Niw0OC4wMzcsNTZ6Ii8+PGNpcmNsZSBzdHlsZT0iZmlsbDojRkZGRkZGOyIgY3g9IjE4LjUiIGN5PSI0NyIgcj0iMyIvPjxjaXJjbGUgc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGN4PSIyOC41IiBjeT0iNDciIHI9IjMiLz48Y2lyY2xlIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBjeD0iMzguNSIgY3k9IjQ3IiByPSIzIi8+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg==" class="icon">
                        
                        <span class="upl" id="upload">Elegir archivo</span>
                        <input type="file" class="upload up" id="up" name="archivos_multiples[]" onchange="readURL(this);" />
                      </div><!-- btn-elegir -->
                    </div><!-- col-3 -->
                    <div class="col-7">
                      <div class="group">
                        <input type="text" name="archivos_adjtitulo[]" />
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>Titulo</label>
                      </div>
                    </div><!--col-8-->
                    <div class="col-1"><a class="btn-check"><i class="fa fa-times"></i></a></div><!-- col-1 -->
                    </div><!--row-->
                  </div><!--uploader--> 
                  <div class="text-center">
                    <a class="btn btn-style-2 btn-sm" id="btn-new"><i class="fa fa-plus"></i>Nuevo documento</a>
                  </div>
                
              </div><!-- form-adjunto-ht -->
               
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
      </div>
    </div>
  </div>
  <!-- X X MODAL ADD ADJUNTO HISTORIAL X X  -->
  
  <!-- -------- MODAL EDITAR HISTORIA ------- -->
  <div class="modal fade" id="modal-edit-historia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-pen fa-fw fa-sm"></i> Editar Historia</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <form class="Formulario_ajax_simple" method="POST" action="<?php  echo SERVERURL; ?>ajax/historialAjax.php" >
           <div class="modal-body">
            <input type="hidden" name="historia_cod_edit" id="historia_id_edit">
             <div class="row">
              <div class="col-12">
                <div class="group">
                  <input type="text"  name="historia_motivo_edit" id="historia_motivo"  />
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label>Motivo de consulta</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="group">
                  <textarea type="textarea" maxlength="300" rows="3" name="historia_anamnesis_edit" id="historia_anamnesis"  ></textarea>
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label>Anamnesis</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="group">
                  <textarea type="textarea" maxlength="300" rows="3" name="historia_sintomas_edit" id="historia_sintomas"  ></textarea>
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label>Hallazgos Clínicos</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="group">
                  <textarea type="textarea" maxlength="300" rows="3" name="historia_diagnostico_edit" id="historia_diagnostico" required="" ></textarea>
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label>Diagnostico</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="group">
                  <textarea type="textarea" maxlength="300" rows="3" name="historia_diagnosticopre_edit" id="historia_diagnosticopre" ></textarea>
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label>Diagnostico Presuntivo</label>
                </div>
              </div>
              <div class="col-12">
                <div class="group">
                  <textarea type="textarea" maxlength="300" rows="4" name="historia_tratamiento_edit" id="historia_tratamiento" required=""></textarea>
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label>Tratamiento</label>
                </div>
              </div>
              <div class="col-md-6">
                      <div class="input-group date group fechas" id="id_fecha_historial">
                        <input type="text" name="historia_fecha_edit" id="historia_fecha" required=""/>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>Fecha de la Historia</label>
                        <div class="input-group-addon input-group-append">
                          <div class="input-icon">
                              <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="input-group group">
                        <input type="text" name="historia_hora_edit" id="historia_hora" required="" />
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>Hora</label>
                        <div class="input-group-addon input-group-append">
                          <div class="input-icon">
                              <i class="glyphicon glyphicon-time far fa-clock"></i>
                          </div>
                        </div>
                      </div>
                    </div>
             </div>
           </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
      </div>
    </div>
  </div>
  <!-- ----x---- MODAL EDITAR HISTORIA ----x--- -->
  <!-- MODAL ADD HISTORIAL VACUNA -->
  <div class="modal fade modal-vacuna-add-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h4 class="modal-title w-100"><i class="fas fa-plus mr-2 fa-xs"></i>Agregar vacuna</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="Formulario_ajax_simple" method="POST" action="<?php  echo SERVERURL; ?>ajax/vacunaHistorialAjax.php">
          <div class="modal-body">
            <div class="md-form">
                  <input type="hidden" name="historia_vacuna_codmascota_reg" id="historia_vacuna_codmascota_reg" value="<?php echo $campos['codMascota'] ?>">
                  <small>Seleccionar vacuna</small><br>
                  <select class="selectpicker w-100" name="historia_vacuna_idvacuna_reg" id="historia_vacuna_idvacuna_reg" data-live-search="true">
                    <!-- *** dinamico ** -->
                      <?php  
                        require_once "./controladores/vacunaControlador.php";
                        $ins_vacuna=new vacunaControlador();

                        $dataE=$ins_vacuna->datos_vacuna_controlador("Select",$campos['idEspecie']);
                        $select="<option value='0'>Seleccionar vacuna</option>";
                        while($rowE=$dataE->fetch()){
                           $select.='<option value="'.$rowE['idVacuna'].'">'.$rowE['vacunaNombre'].'</option>';
                        }
                        echo $select;
                      ?>
                    <!-- *** dinamico ** -->
                  </select>


                  <div class="group mb-2">
                    <div class="input-group date group fechas" id="id_fecha">
                      <input type="text" name="historia_prox_fecha_vacuna_reg" id="historia_prox_fecha_vacuna"/>
                      <span class="highlight"></span>
                      <span class="bar"></span>
                      <label>Fecha proxima vacuna</label>
                      <div class="input-group-addon input-group-append">
                          <div class="input-icon">
                              <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                          </div>
                      </div>
                    </div>
                  </div> 

                   <div class="group mb-2">
                    <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\-: ]{1,100}" name="historia_vacuna_producto_reg" required="" />
                    <span class="highlight"></span>
                    <span class="bar"></span>
                    <label>Producto (Fabricante, lote)</label>
                  </div> 

                  <div class="group">
                    <textarea type="textarea" maxlength="300" rows="3" name="historia_vacuna_observacion_reg" ></textarea>
                    <span class="highlight"></span>
                    <span class="bar"></span>
                    <label>Observaciones</label>
                  </div>
              
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button type="submit" class="btn btn-primary">Guardar<i class="fas fa-plus ml-1"></i></button>
          </div>
        </form>

      </div>
    </div>
  </div>
  <!-- X X modal ADD HISTORIAL VACUNA X X  -->
  <!--  modal EDIT HISTORIAL VACUNA  -->
  <div class="modal fade" id="modal-edit-historia-vacuna" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-pen fa-fw fa-sm"></i> Editar vacuna</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <form class="Formulario_ajax_simple" method="POST" action="<?php  echo SERVERURL; ?>ajax/vacunaHistorialAjax.php">
            <div class="modal-body">
              <input type="hidden" name="vacuna_idhistoria_up" id="vacuna_idhistoria_up" >
              <!-- codmascota creo no -->
              <small>Seleccionar vacuna</small><br>
              <select class="selectpicker w-100" name="historia_vacuna_idvacuna_up" id="historia_vacuna_idvacuna_up" data-live-search="true">
                <!-- *** dinamico ** -->
                  <?php  
                    require_once "./controladores/vacunaControlador.php";
                    $ins_vacuna=new vacunaControlador();

                    $dataE=$ins_vacuna->datos_vacuna_controlador("Select",$campos['idEspecie']);
                    $select="<option value='0'>Seleccionar vacuna</option>";
                    while($rowE=$dataE->fetch()){
                      // 
                       $select.='<option value="'.$rowE['idVacuna'].'">'.$rowE['vacunaNombre'].'</option>';
                    }
                    echo $select;
                  ?>
                <!-- *** dinamico ** -->
              </select>

              <div class="group mb-2">
                <div class="input-group date group fechas" id="id_fecha_up">
                  <input type="text" name="historia_prox_fecha_vacuna_up" id="historia_prox_fecha_vacuna_up" />
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label>Fecha proxima vacuna</label>
                  <div class="input-group-addon input-group-append">
                      <div class="input-icon">
                          <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                      </div>
                  </div>
                </div>
              </div>
              
               <div class="group mb-2">
                <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\-: ]{1,100}" name="historia_vacuna_producto_up" id="historia_vacuna_producto_up" required="" />
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Producto (Fabricante, lote)</label>
              </div> 
              <div class="group">
                <textarea type="textarea" maxlength="300" rows="3" name="historia_vacuna_observacion_up" id="historia_vacuna_observacion_up"></textarea>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Observaciones</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
      </div>
    </div>
  </div>
  <!-- X X modal EDIT HISTORIAL VACUNA X X  -->

  <?php }else{ ?>
  <div class="alert alert-danger text-center" role="alert">
    <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
    <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
    <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
  </div>
<?php } ?>
</div>