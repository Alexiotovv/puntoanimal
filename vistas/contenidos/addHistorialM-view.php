<div class="titulo-linea mt-2">
  <h2><i class="flaticon-021-veterinary"></i>Historia Clinica</h2>
  <hr class="sidebar-divider">
</div>

<?php
require_once "./controladores/mascotaControlador.php";
require_once "./controladores/citaControlador.php";
$inst_mascota = new mascotaControlador();
$inst_cita = new citaControlador();

$datos_mascota = $inst_mascota->datos_mascota_controlador("Perfil", $pagina[1]);
// Atender una cita //////
$paginafer = $pagina[2];
$inputcita = "";
$motivo_cita = "";
$error_cita = "";
if ($pagina[2] != "") {
  $datos_cita = $inst_cita->datos_cita_controlador("Unico", $pagina[2]);
  if ($datos_cita->rowCount() == 1) {
    $campos_cita = $datos_cita->fetch();  
    $motivo_cita = $campos_cita['citaMotivo'];
    $value_motivo = 'value="' . $motivo_cita . '"';

    $inputcita = '<input type="hidden" name="historial_codcita_reg" value="' . $pagina[2] . '">';
  } else { 
    $error_cita = "SI";
  }
} 

if ($datos_mascota->rowCount() == 1) {
  $campos = $datos_mascota->fetch();
  $codigo_historia = $ins_loginc->generar_codigo_aleatorio_historial("HM-", 5);

?>
  <?php if ($error_cita == "SI") { ?>
    <div class="alert alert-danger text-center" role="alert">
      <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
      <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
      <p class="mb-0">Lo sentimos, no podemos mostrar la información para la cita</p>
    </div>
  <?php } else { ?>
    <div class="intro mb-4 add-historial-mct">
      <form action="<?php echo SERVERURL; ?>ajax/historialAjax.php" data-form="save" method="POST" class="FormularioAjax" enctype="multipart/form-data">
        <!-- CODIGO HISTORIAL -->
        <input type="hidden" name="historial_codigo_reg" value="<?php echo $codigo_historia; ?>">
        <!-- CREADOR -->
        <input type="hidden" name="historial_creador_reg" value="<?php echo $_SESSION['nombre_vetp'] . " " . $_SESSION['apellido_vetp']; ?>">
        <!-- CODIGO DE CITA ATENDER -->
        <?php echo $inputcita; ?>
        <!-- header -->
        <ul class="nav nav-tabs py-2 mb-2">
          <li class="nav-item">
            <a class="sub-titulo-panel nav-link active" data-toggle="tab" href="#panel1_historia">
              <i class="flaticonv-030-pawprint"></i>
              Historia
            </a>
          </li>
          <li class="nav-item ml-4">
            <a class="sub-titulo-panel nav-link" data-toggle="tab" href="#panel2_vacuna">
              <i class="flaticonv-011-syringe"></i>
              Vacuna</a>
          </li>
        </ul>
        <!-- -x- header -x- -->
        <!-- - tab-content  -->
        <div class="tab-content">
          <div class="row tab-pane active" id="panel1_historia">
            <div class="col-lg-12">
              <div class="row">
                <!-- PACIENTE -->
                <div class="col-md-6 mb-4">
                  <small>Paciente:</small>
                  <div class="d-flex flex-row mt-2">
                    <!-- codigo mascota -->
                    <input type="hidden" name="historia_codpaciente_reg" value="<?php echo $campos['codMascota']; ?>">
                    <div class="img-paciente">
                      <img src="<?php echo SERVERURL . $campos['mascotaFoto']; ?>" class="img-fluid rounded-circle" alt="foto">
                    </div>
                    <small class="ml-4"><b><?php echo $campos['mascotaNombre']; ?></b>
                      <br>
                      <?php echo $campos['espNombre'] . " - " . $campos['razaNombre']; ?>
                    </small>

                  </div>
                </div>
                <!-- x -PACIENTE x- -->
                <!-- - INFO GENERAL- -->
                <!-- PACIENTE -->
                <div class="col-md-6 mb-4">
                  <small>Veterinario:</small>
                  <div class="d-flex flex-row historia_codveterinario_reg align-items-center mt-2">
                    <img style="width: 55px; height: 55px;" id="imgpreve-vet-cita" class="rounded-circle mr-2" src="<?php echo SERVERURL; ?>vistas/images/general/user-foto.svg" alt="fotoveterinario">

                    <select class="selectpicker w-100" name="historia_codveterinario_reg" id="select-veterinario" serverurl="<?php echo SERVERURL; ?>" data-show-subtext="true" data-live-search="true">
                      <!-- ***dinamico***(cargar cliente,input key buscar) -->

                      <!-- *x**dinamico**x* -->
                    </select>
                  </div>
                </div>
                <div class="col-12">
                  <div class="group">
                    <input type="text" name="historia_motivo_reg" id="historia_motivo_reg" <?php if ($motivo_cita != "") {
                                                                                              echo $value_motivo;
                                                                                            } ?> />
                    <span class="highlight"></span>
                    <span class="bar"></span>
                    <label>Motivo de consulta</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="group">
                    <textarea type="textarea" maxlength="300" rows="3" name="historia_anamnesis_reg" id="historia_anamnesis_reg"></textarea>
                    <span class="highlight"></span>
                    <span class="bar"></span>
                    <label>Anamnesis</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="group">
                    <textarea type="textarea" maxlength="300" rows="3" name="historia_sintomas_reg" id="historia_sintomas_reg"></textarea>
                    <span class="highlight"></span>
                    <span class="bar"></span>
                    <label>Hallazgos Clínicos</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="group">
                    <textarea type="textarea" maxlength="300" rows="3" name="historia_diagnostico_reg" id="historia_diagnostico_reg"></textarea>
                    <span class="highlight"></span>
                    <span class="bar"></span>
                    <label>Examenes Diagnostivos</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="group">
                    <textarea type="textarea" maxlength="300" rows="3" name="historia_diagnosticopre_reg" id="historia_diagnosticopre_reg"></textarea>
                    <span class="highlight"></span>
                    <span class="bar"></span>
                    <label>Diagnostico Presuntivo</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="group">
                    <textarea type="textarea" maxlength="300" rows="4" name="historia_tratamiento_reg" id="historia_tratamiento_reg"></textarea>
                    <span class="highlight"></span>
                    <span class="bar"></span>
                    <label>Tratamiento</label>
                  </div>
                </div>

                <!-- -x Fecha Historial mascota-x -->
                <div class="col-md-6">
                  <div class="input-group date group fechas" id="id_fecha">
                    <input type="text" name="historia_fecha_reg" id="historia_fecha_reg" required="" />
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
                <!-- -x Hora Historial mascota-x -->
                <div class="col-md-6">
                  <div class="input-group group">
                    <input type="text" name="historia_hora_reg" id="historia_hora_reg" required="" />
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
                <!-- -x INFO GENERAL-x -->
              </div>
            </div>
            <!-- ARCHIVOS ADJUNTO -->
            <div class="col-lg-12 mb-4">
              <h3 class="sub-titulo-panel"><i class="fas fa-paperclip"></i>Archivos adjunto</h3>
              <div class="form-adjunto-ht">
                <p>Archivos permitidos en formato 'pdf', 'jpg', 'jpeg', 'png'.
                </p><br>
                <h6 class="text-center">Mis Documentos</h6>

                <div id="uploader">
                  <div class="row uploadDoc">
                    <div class="col-sm-4 d-flex align-items-center">
                      <div class="docErr"><i class="fas fa-exclamation-circle mr-2 fa-sm"></i>Por favor subir un archivo valido</div><!--error-->
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

              </div><!-- adjunto-ht -->

            </div>
            <!-- -x-ARCHIVOS ADJUNTO -x- -->
          </div> <!-- x- row -x -->

          <!-- ---- ADD VACUNA HISTORIAL ---  -->

          <div class="row tab-pane" id="panel2_vacuna">

            <div class="row">
              <div class="col-md-6 col-sm-12">
                <small>Seleccionar vacuna</small><br>
                <select class="selectpicker w-100" name="historia_vacuna_idvacuna_reg" id="historia_vacuna_idvacuna_reg" data-live-search="true">
                  <!-- *** dinamico ** -->
                  <?php
                  require_once "./controladores/vacunaControlador.php";
                  $ins_vacuna = new vacunaControlador();

                  $dataE = $ins_vacuna->datos_vacuna_controlador("Select", $campos['idEspecie']);
                  $select = "<option value='0'>Seleccionar vacuna</option>";
                  while ($rowE = $dataE->fetch()) {
                    $select .= '<option value="' . $rowE['idVacuna'] . '">' . $rowE['vacunaNombre'] . '</option>';
                  }
                  echo $select;
                  // $ins_loginc->encryption($rowE['idVacuna'])
                  ?>
                  <!-- *** dinamico ** -->
                </select>
              </div>
              <div class="col-md-6 col-sm-12">
                <div class="input-group date group" id="id_fecha2">
                  <input type="text" name="historia_prox_fecha_vacuna_reg" id="historia_prox_fecha_vacuna" />
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
            </div>
            
            <div class="col-12">
              <div class="group">
                <input type="text" name="historia_vacuna_producto_reg" />
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Producto (Fabricante, lote)</label>
              </div>
            </div>

            <div class="col-12">
              <div class="group">
                <textarea type="textarea" maxlength="300" rows="3" name="historia_vacuna_observacion_reg"></textarea>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Observaciones</label>
              </div>
            </div>
          </div> <!-- x- row -x -->
          <!-- --X--- ADD VACUNA HISTORIAL --X- -->

        </div> <!-- x- tab-content -x -->
        <div class="text-center mt-2">
          <button type="submit" class="btn btn-primary" id="btn-historia-vacio">Guardar</button>
          <a href="<?php echo SERVERURL; ?>listaMascota/" class="btn btn-primary">Lista Mascotas</a>
        </div>
      </form>

    </div>

  <?php } ?>

<?php } else { ?>
  <div class="alert alert-danger text-center" role="alert">
    <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
    <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
    <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
  </div>
<?php } ?>