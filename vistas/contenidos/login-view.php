<?php
  if(isset($_POST['usuario_login']) && isset($_POST['clave_login'])){
    require_once "./controladores/loginControlador.php";

    $ins_login=new loginControlador();
    echo $ins_login->iniciar_sesion_controlador();
  } 
 ?>
<div class="limiter">
  <div class="container-login100">
    
    <div class="wrap-login100 shadow">
      <div class="login100-form-title">
        <span class="login100-form-avatar">
       
          <img src="<?php  echo SERVERURL;?>vistas/images/general/logo.png" alt="AVATAR">
          
        </span>
      </div>

      <form action="" method="POST" class="login100-form validate-form"> 
	
        <div class="wrap-input100 validate-input" data-validate="Username is required">
          <div class="group">
            <input type="text" name="usuario_login"  maxlength="35" required="" value=""/>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label><i class="fas fa-user mr-2"></i>
            Usuario</label>
          </div>
        </div>
        <div class="wrap-input100 validate-input" data-validate = "Password is required">
          <div class="group">
            <input type="password" name="clave_login"  maxlength="100" required="" value=""/>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label><i class="fas fa-key mr-2"></i>
            Clave</label>
          </div>
        </div>

        <div class="container-login100-form-btn">
          <button class="btn btn-primary">
            Ingresar a Sistema <i class="fas fa-arrow-right"></i>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer class="sticky-footer bg-white shadow">
      <div class="container my-auto">
      <div class="copyright text-center my-auto ">
        <span>Copyright &copy; Software Veterinaria <?php echo date("Y"); ?></span>
      </div>
    </div>
  </footer>
  <!-- End of Footer -->

</div>
