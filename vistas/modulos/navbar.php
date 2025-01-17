<nav class="navbar navbar-expand navbar-light  topbar static-top shadow">
  <!-- Sidebar Toggle (Topbar) -->
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button>

  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown no-arrow citas-para-hoy">
      <a class="nav-link dropdown-toggle" href="#" id="notifications" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fa fa-bell fa-2x" style="color: #e0b51f;" aria-hidden="true"></i>
        <span class="badge notification" id="numero_notificaciones">0</span> <!-- Aquí se agrega el número de notificaciones -->
      </a>

      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in citas-hoy" id="citas-hoy" style="left: inherit; right: 0px;" aria-labelledby="notifications" name='citas-hoy' serverurl="<?php echo SERVERURL; ?>">
        
      </div>
    </li>

    <li class="nav-item dropdown no-arrow vacuna-proxima">
      <a class="nav-link dropdown-toggle" href="#" id="proxvacuna" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fa flaticonv-011-syringe fa-2x" style="color: #e0b51f;" aria-hidden="true"></i>
        <span class="badge notification" id="numero_notificaciones_prox_vacuna">0</span> <!-- Aquí se agrega el número de notificaciones -->
      </a>
      
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in vacunas-prox" id="vacunas-prox" style="left: inherit; right: 0px;" aria-labelledby="notifications" name='vacunas-prox' serverurl="<?php echo SERVERURL; ?>">
        
      </div>
    </li>
    
    <li class="nav-item dropdown no-arrow producto-vencido">
      <a class="nav-link dropdown-toggle" href="#" id="prodvencido" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fa-solid fa-volume-high fa-2x" style="color: #ec0909;" aria-hidden="true"></i>
        <span class="badge notification" id="numero_notificaciones_prod_vencido">0</span> <!-- Aquí se agrega el número de notificaciones -->
      </a>

      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in productos-vencido" id="productos-vencido" style="left: inherit; right: 0px;" aria-labelledby="notifications" name='productos-vencido' serverurl="<?php echo SERVERURL; ?>">
        
      </div>
    </li>
    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['nombre_vetp'] . " " . $_SESSION['apellido_vetp']; ?></span>
        <img class="img-profile rounded-circle" src="<?php echo SERVERURL . $_SESSION['foto_vetp']; ?>">

        <!-- adjuntos/user-sistema-foto/profile-2.jpg -->
      </a>
      <!-- Dropdown - User Information -->
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="<?php echo SERVERURL . "editUsuario/" . $ins_loginc->encryption($_SESSION['id_vetp']) . "/"; ?>">
          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
          Perfil
        </a>

        <div class="dropdown-divider"></div>
        <a class="btn-exit-system dropdown-item" href="#">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
          Salir
        </a>
      </div>
    </li>

  </ul>

</nav>
