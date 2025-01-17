<?php
  if($_SESSION['privilegio_vetp']!=1){
    echo $ins_loginc->forzar_cierre_sesion_controlador();
    exit();
  } 
 ?>
<div class="titulo-linea mt-2">
  <h2><i class="flaticon2-034-veterinary"></i>Veterinaria</h2>
  <hr class="sidebar-divider">
</div>
<?php
  require_once "./controladores/empresaControlador.php";
  $iEm=new empresaControlador();
  //conteo de empresa
  $cEm=$iEm->datos_empresa_controlador("Unico");

  if($cEm->rowCount()<=0){
 ?>
<div class="intro mb-4 form-empresa">
  <form class="FormularioAjax" action="<?php  echo SERVERURL; ?>ajax/empresaAjax.php" data-form="save"  method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="col-lg-8">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="sub-titulo-panel">
              <i class="flaticon2-015-hospital"></i>
                Información de empresa</h3>
          </div> 
          <div class="col-md-6">
            <div class="group">
              <!--  -->
              <input type="text" pattern="[0-9a-zA-Z ]{1,27}" name="empresa_rif_reg" id="empresa_rif" maxlength="27" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>NIT</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ(). ]{1,40}" name="empresa_nombre_reg" id="empresa_nombre" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[0-9()+]{8,20}" name="empresa_telefono_reg" id="empresa_telefono" maxlength="20" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Telefono</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="email" name="empresa_email_reg" id="empresa_email" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Correo</label>
            </div>
          </div>
          <div class="col-12">
            <div class="group">
              <input type="text" name="empresa_direccion_reg" id="empresa_direccion" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Direccion</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" name="empresa_moneda_reg" id="empresa_moneda" maxlength="5" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Moneda</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="number" name="empresa_iva_reg" id="empresa_iva" required="" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Impuesto (%)</label>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <h3 class="sub-titulo-panel">
          <i class="flaticon2-019-pet-care"></i>
            Logo de la veterinaria</h3><br>
        
        <div class="row h-100 text-center justify-content-center align-items-center mt-4">
          <span>Archivos permitidos en formato: 'jpg', 'jpeg', 'png'.
        </span>
          <div class="col-12 mb-2">
            <div class="img-preve">
                <img src="<?php  echo SERVERURL;?>vistas/images/general/image-preve.svg" id="imgpreve">
            </div>
          </div>
          <div class="col-12">
            <div class="btn_upload">
              <input type="file" id="archivo_foto_subir_empresa" name="empresa_logo_reg">
              Subir Logo
              <span class="icon-foto-subir">
                <i class="fas fa-camera-retro fa-sm"></i>
              </span>
            </div>
            <div class="error_msg"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-12 text-center mt-5">
        <button type="submit" class="btn btn-primary">Guardar</button>              
      </div>
    </div>
  </form>
</div>
<?php 
}else{
  $campos=$cEm->fetch();
 ?>
 <div class="intro mb-4 form-empresa">
  <form class="FormularioAjax" action="<?php  echo SERVERURL; ?>ajax/empresaAjax.php" data-form="update"  method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_empresa_up" value="<?php echo $campos['idempresa']; ?>">
    <div class="row">
      <div class="col-lg-8">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="sub-titulo-panel">
              <i class="flaticon2-015-hospital"></i>
                Actualizar información de empresa</h3>
          </div> 
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[0-9a-zA-Z ]{1,27}" name="empresa_rif_edit" id="empresa_rif" maxlength="27" value="<?php echo $campos['rif']; ?>" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>NIT</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ(). ]{1,40}" name="empresa_nombre_edit" id="empresa_nombre" value="<?php echo $campos['empresaNombre']; ?>" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" pattern="[0-9()+]{8,20}" name="empresa_telefono_edit" id="empresa_telefono" maxlength="20" value="<?php echo $campos['empresaTelefono']; ?>" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Telefono</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="email" name="empresa_email_edit" id="empresa_email" value="<?php echo $campos['empresaCorreo']; ?>" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Correo</label>
            </div>
          </div>
          <div class="col-12">
            <div class="group">
              <input type="text" name="empresa_direccion_edit" id="empresa_direccion" value="<?php echo $campos['empresaDireccion']; ?>" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Direccion</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="text" name="empresa_moneda_edit" id="empresa_moneda" maxlength="5" value="<?php echo $campos['empresaMoneda']; ?>" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Moneda</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="group">
              <input type="number" name="empresa_iva_edit" id="empresa_iva" value="<?php echo $campos['empresaIva']; ?>" />
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>IVA (%)</label>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <h3 class="sub-titulo-panel">
          <i class="flaticon2-019-pet-care"></i>
            Logo de empresa</h3><br>
        
        <div class="row h-100 text-center justify-content-center align-items-center mt-4">
          <span>Archivos permitidos en formato: 'jpg', 'jpeg', 'png'.
        </span>
          <div class="col-12 mb-2">
            <div class="img-preve">
                <img src="<?php  echo SERVERURL.$campos['empresaFotoUrl']; ?>" id="imgpreve">
            </div>
          </div>
          <div class="col-12">
            <div class="btn_upload">
              <input type="file" id="archivo_foto_subir_empresa" name="empresa_logo_edit">
              Subir Logo
              <span class="icon-foto-subir">
                <i class="fas fa-camera-retro fa-sm"></i>
              </span>
            </div>
            <div class="error_msg"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-12 text-center mt-5">
        <button type="submit" class="btn btn-primary">Actualizar</button>              
      </div>
    </div>
  </form>
</div>
<?php } ?>
