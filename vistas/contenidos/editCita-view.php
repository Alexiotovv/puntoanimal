<div class="titulo-linea mt-2">
  <h2><i class="flaticon-021-veterinary"></i>Editar cita</h2>
  <hr class="sidebar-divider">
</div>

<div class="intro mb-4 add-cita-form">
  <?php
    require_once "./controladores/citaControlador.php";
    $inst_cita = new citaControlador();

    $datos_cita=$inst_cita->datos_cita_controlador("Unico",$pagina[1]);

    if($datos_cita->rowCount()==1){
      $campos=$datos_cita->fetch();
     
   ?>
  <form class="FormularioAjax" action="<?php  echo SERVERURL; ?>ajax/citaAjax.php" method="POST" data-form="update" server="<?php  echo SERVERURL; ?>" >
     <input type="hidden" name="cita_codigo_up" value="<?php echo $pagina[1] ; ?>">
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <div class="col-12">
            <h3 class="sub-titulo-panel"><i class="fas fa-calendar-day"></i>Editar cita</h3>
            
            <hr>
          </div>
          <div class="col-md-6 mb-2">
            <input type="hidden" name="cita_cliente_dni" value="<?php echo $campos['dniCliente']; ?>">
            <span>Cliente</span>

            <div class="d-flex flex-row mascota-dueno align-items-center mt-2">
              <img style="width: 55px; height: 55px;" id="imgpreve-cliente-m" class="rounded-circle mr-2" src="<?php  echo SERVERURL;?>vistas/images/general/user-foto.svg" alt="fotocliente">
              <select class="selectpicker w-100"  name="mascota-dueno" id="select-dueno" serverurl="<?php echo SERVERURL; ?>" data-show-subtext="true" data-live-search="true">
                <!-- ***dinamico***(cargar cliente,input key buscar) -->
                
                <!-- *x**dinamico**x* -->
              </select>
            </div>


          </div>
          <div class="col-md-6 mb-2" id="cita-select-mascota">
            <input type="hidden" name="cita_paciente_cod" value="<?php echo $campos['codMascota']; ?>">
            <span>Paciente</span>
            <div class="d-flex flex-row align-items-center mt-2">
              <img style="width: 55px; height: 55px;" id="imgpreve-mascota-cita" class="rounded-circle mr-2" src="<?php  echo SERVERURL;?>vistas/images/general/mascota-foto.svg" alt="fotomascota">
              <select class="selectpicker w-100" name="cita_paciente_reg" id="cita-paciente" data-show-subtext="true" data-live-search="true">
              <!-- *** dinamico ***(cargar mascota,segun cliente)-->
                
              <!-- **x*dinamico**x*-->
              </select>
            </div>
            
          </div>
          <div class="col-md-6">
            <div class="input-group date group" id="id_fecha">
                <input type="text" name="cita_fecha_edit" id="cita_fecha" value="<?php echo $campos['citaFechaProxima'];?>" required/>
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
                  <input type="text" name="cita_hora_edit" id="id_hora" value="<?php echo $campos['citaHora'];?>" required />
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
              <input type="text" name="cita_motivo_edit" value="<?php echo $campos['citaMotivo'];?>" required=""/>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Motivo Cita</label>
            </div>
          </div>
          
          
        </div>
      </div>
      <div class="col-lg-12 text-center mt-2">
        <button type="submit" class="btn btn-primary">Editar</button>    
        <a href="<?php  echo SERVERURL;?>listaCita/" class="btn btn-primary">Listar Citas</a>                  
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