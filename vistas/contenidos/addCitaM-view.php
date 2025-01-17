<div class="titulo-linea mt-2">
  <h2><i class="flaticon-021-veterinary"></i>Cita</h2>
  <hr class="sidebar-divider">
</div>
<?php 
require_once "./controladores/mascotaControlador.php";
$inst_mascota = new mascotaControlador();

$encontradom="";
$nombremascota="";
$error_cita="";
 if($pagina[1]!=""){
  $datos_mascota = $inst_mascota->datos_mascota_controlador("Perfil",$pagina[1]);
  if($datos_mascota->rowCount()==1){
    $campos = $datos_mascota->fetch();
    $encontradom="SI";
    $nombremascota = " - ".$campos['mascotaNombre'];
  }else{
    $error_cita="SI";
  }
 }
 ?>
 <!-- AGREGAR CITA DESDE MASCOTA lista -->
<?php if($error_cita!="SI"){ ?>
<div class="intro mb-4 add-cita-form">
  <form class="FormularioAjax" action="<?php  echo SERVERURL; ?>ajax/citaAjax.php" method="POST" data-form="save" server="<?php  echo SERVERURL; ?>" >
    
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <div class="col-12">
            <h3 class="sub-titulo-panel"><i class="fas fa-calendar-day"></i>Agendar cita <?php echo $nombremascota?></h3>
            
            <hr>
          </div>
          <div class="col-md-6 mb-2">
            <span>Cliente</span>
            
            <?php 
                  if ($pagina[1]!="" && $encontradom=="SI") {
             ?>
                  <div class="d-flex flex-row mt-2">
                    <input type="hidden" name="mascota-dueno" value="<?php echo $campos['clienteDniCedula'];?>">
                    <img style="width: 55px; height: 55px;" class="rounded-circle mr-2" src="<?php  echo SERVERURL.$campos['clienteFotoUrl'];?>" alt="fotocliente">
                    <small class="ml-4 initialism"><b><?php echo $campos['clienteNombre']." ".$campos['clienteApellido'];?></b>
                      <br>
                    <?php echo $campos['clienteDniCedula'];?> 
                    </small>
                  </div>      
             <?php 
              }else{
              ?>
                  <div class="d-flex flex-row mascota-dueno align-items-center mt-2">
                    <img style="width: 55px; height: 55px;" id="imgpreve-cliente-m" class="rounded-circle mr-2" src="<?php  echo SERVERURL;?>vistas/images/general/user-foto.svg" alt="fotocliente">
                    <select class="selectpicker w-100"  name="mascota-dueno" id="select-dueno" serverurl="<?php echo SERVERURL; ?>" data-show-subtext="true" data-live-search="true">
                      <!-- ***dinamico***(cargar cliente,input key buscar) -->
                      
                      <!-- *x**dinamico**x* -->
                    </select>
                  </div>
            <?php } ?>

          </div>
          <div class="col-md-6 mb-2" id="cita-select-mascota">
            <span>Paciente</span>
            <?php 
                  if ($pagina[1]!="" && $encontradom=="SI") {
             ?>
                <div class="d-flex flex-row mt-2">
                  <input type="hidden" name="redireccionar_lista_cita" value="listaCita">
                  <input type="hidden" name="cita_paciente_reg" value="<?php echo $campos['codMascota']; ?>">
                  <img style="width: 55px; height: 55px;" class="rounded-circle mr-2" src="<?php  echo SERVERURL.$campos['mascotaFoto'];?>" alt="fotomascotas">
                  <small class="ml-4 initialism"><b><?php echo $campos['mascotaNombre'];?></b>
                    <br>
                  <?php echo $campos['codMascota'];?> 
                  </small>
                </div>
            <?php 
              }else{
              ?>
                <div class="d-flex flex-row align-items-center mt-2">
                  <img style="width: 55px; height: 55px;" id="imgpreve-mascota-cita" class="rounded-circle mr-2" src="<?php  echo SERVERURL;?>vistas/images/general/mascota-foto.svg" alt="fotomascota">

                  <select class="selectpicker w-100" name="cita_paciente_reg" id="cita-paciente" data-show-subtext="true" data-live-search="true">
                  <!-- *** dinamico ***(cargar mascota,segun cliente)-->
                    
                  <!-- **x*dinamico**x*-->
                  </select>
                </div>
            <?php } ?>
          </div>
          <div class="col-md-6">
            <div class="input-group date group" id="id_fecha">
                <input type="text" name="cita_fecha_reg" id="cita_fecha" required/>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Fecha</label>
                <div class="input-group-addon input-group-append">
                    <div class="input-icon">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                    </div>
                </div>
            </div>
          </div>
          <div class="col-md-6">
              <div class="input-group group">
                  <input type="text" name="cita_hora_reg" id="id_hora" required />
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
          <div class="col-12">
            <div class="group">
              <input type="text" name="cita_motivo_reg" required="required"/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Motivo Cita</label>
            </div>
          </div>
          
          
        </div>
      </div>
      <div class="col-lg-12 text-center mt-2">
        <button type="submit" class="btn btn-primary">Agendar</button>
        <a href="<?php  echo SERVERURL;?>listaCita/" class="btn btn-primary">Lista Citas</a>  
      </div>
    </div>
  </form>
</div>
<?php }else{ ?>
<div class="alert alert-info text-center" role="alert">
  <p><i class="fas fa-info fa-5x"></i></p>
  <h4 class="alert-heading">Fallo al cargar datos de mascota para cita</h4>
  <p class="mb-0">Intente nuevamente</p>
</div>
  <?php } ?>
