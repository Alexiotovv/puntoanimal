<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php  echo SERVERURL;?>home/">
       <div class="sidebar-brand-icon">
      <img src="<?php echo SERVERURL; ?>vistas/images/general/logo.jpeg">
    </div>
    <div class="sidebar-brand-text mx-1">Veterinaria Punto Animal
      <!--<sup>2024</sup>-->
    </div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="<?php  echo SERVERURL;?>home/">
      <img src="<?php  echo SERVERURL;?>/img/dashboard.png" alt="compras" width="30"></i>
      <span>Inicio</span></a>
  </li>

  <!-- Nav Item - Collapse Mascota -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
     <img src="<?php  echo SERVERURL;?>/img/mascotas.png" alt="compras" width="30"></i>
      <span>Mascotas</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="py-2 collapse-inner rounded">
        <a class="collapse-item" href="<?php  echo SERVERURL;?>addMascota/"> <img src="<?php  echo SERVERURL;?>/img/mascotas.png" alt="compras" width="30">Nueva Mascota</a>
        <a class="collapse-item" href="<?php  echo SERVERURL;?>listaMascota/"><img src="<?php  echo SERVERURL;?>/img/mascotas.png" alt="compras" width="30"></i> Lista Mascota</a>
        <a class="collapse-item" href="<?php  echo SERVERURL;?>listaEspecie/"><img src="<?php  echo SERVERURL;?>/img/especies.png" alt="especies" width="30"></i> Especies</a>
        <a class="collapse-item" href="<?php  echo SERVERURL;?>listaRaza/"><img src="<?php  echo SERVERURL;?>/img/razas.png" alt="razas" width="30"></i> Razas</a>
        <a class="collapse-item" href="<?php  echo SERVERURL;?>listaVacuna/"><img src="<?php  echo SERVERURL;?>/img/vacuna.png" alt="vacuna" width="30"></i> Vacuna</a>
      </div>
    </div>
  </li>

  <!-- Nav Item - Cliente Collapse Menu -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCliente" aria-expanded="true" aria-controls="collapseCliente">
     <img src="<?php  echo SERVERURL;?>/img/clientes.png" alt="clientes" width="30"></i>
      <span>Clientes</span>
    </a>
    <div id="collapseCliente" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
      <div class="py-2 collapse-inner rounded">
        <a class="collapse-item" href="<?php  echo SERVERURL;?>addCliente/">     <img src="<?php  echo SERVERURL;?>/img/clientes.png" alt="clientes" width="30"></i> Nuevo Cliente</a>
        <a class="collapse-item" href="<?php  echo SERVERURL;?>listaCliente/">     <img src="<?php  echo SERVERURL;?>/img/clientes.png" alt="clientes" width="30"></i> Lista Cliente</a>
        
      </div>
    </div>
  </li>

  <!-- Nav Item - citas Collapse Menu -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCitas" aria-expanded="true" aria-controls="collapseCitas">
     <img src="<?php  echo SERVERURL;?>/img/calendario.png" alt="calendario" width="30"></i>
      <span>Citas</span>
    </a>
    <div id="collapseCitas" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
      <div class="py-2 collapse-inner rounded">
        <a class="collapse-item" href="<?php  echo SERVERURL;?>addCitaM/">     <img src="<?php  echo SERVERURL;?>/img/calendario.png" alt="calendario" width="30"></i> Nueva Cita</a>
        <a class="collapse-item" href="<?php  echo SERVERURL;?>listaCita/">     <img src="<?php  echo SERVERURL;?>/img/citas.png" alt="citas" width="30"></i> Lista Cita</a>
        <a class="collapse-item" href="<?php  echo SERVERURL;?>listaCitaHoy/">     <img src="<?php  echo SERVERURL;?>/img/calendario.png" alt="calendario" width="30"></i> Citas para Hoy</a>
       
      </div>
    </div>
  </li>

   <!-- Nav Item - Veterinario Collapse Menu -->
   <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVeterinario" aria-expanded="true" aria-controls="collapseVeterinario">
     <img src="<?php  echo SERVERURL;?>/img/veterinario.png" alt="veterinario" width="30"></i>
      <span>Veterinario</span>
    </a>
    <div id="collapseVeterinario" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
      <div class="py-2 collapse-inner rounded">
        <a class="collapse-item" href="<?php  echo SERVERURL;?>addVeterinario/">     <img src="<?php  echo SERVERURL;?>/img/veterinario.png" alt="veterinario" width="30"></i> Nuevo Veterinario</a>
        <a class="collapse-item" href="<?php  echo SERVERURL;?>listaVeterinario/">     <img src="<?php  echo SERVERURL;?>/img/veterinario.png" alt="veterinario" width="30"></i> Lista Veterinario</a>
        
      </div>
    </div>
  </li>


  <!-- Nav Item - Inventario Collapse Menu -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventario" aria-expanded="true" aria-controls="collapseInventario">
      <img src="<?php  echo SERVERURL;?>/img/inventario.png" alt="inventario" width="30"></i>
      <span>Inventario</span>
    </a>
    <div id="collapseInventario" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
      <div class="py-2 collapse-inner rounded">
        <a class="collapse-item" href="<?php  echo SERVERURL;?>addProdservi/">      <img src="<?php  echo SERVERURL;?>/img/inventario.png" alt="inventario" width="30"></i> Nuevo Producto</a>
        <a class="collapse-item" href="<?php  echo SERVERURL;?>listaProdservi/">      <img src="<?php  echo SERVERURL;?>/img/inventario.png" alt="inventario" width="30"></i> Lista Inventario</a>
      </div>
    </div>
  </li>

  <!-- Nav Item - Venta Collapse Menu -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVenta" aria-expanded="true" aria-controls="collapseVenta">
      <img src="<?php  echo SERVERURL;?>/img/ventas.png" alt="compras" width="30"></i>
      <span>Venta</span>
    </a>
    <div id="collapseVenta" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
      <div class="py-2 collapse-inner rounded">
        <a class="collapse-item" href="<?php  echo SERVERURL;?>addNuevaVenta/"> <img src="<?php  echo SERVERURL;?>/img/ventas.png" alt="compras" width="30"></i> Nueva Venta</a>
        <a class="collapse-item" href="<?php  echo SERVERURL;?>listaVenta/"> <img src="<?php  echo SERVERURL;?>/img/ventas.png" alt="compras" width="30"></i> Lista Venta</a>
        
      </div>
    </div>
  </li>
  <?php if($_SESSION['privilegio_vetp']==1){

   ?>
  <!-- Nav Item - Usuarios-Sistema Collapse Menu -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsuario" aria-expanded="true" aria-controls="collapseUsuario">
      <img src="<?php  echo SERVERURL;?>/img/usuarios.png" alt="compras" width="30"></i>
      <span>Usuarios</span>
    </a>
    <div id="collapseUsuario" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
      <div class="py-2 collapse-inner rounded">
        <a class="collapse-item" href="<?php  echo SERVERURL;?>addUsuario/"><img src="<?php  echo SERVERURL;?>/img/usuarios.png" alt="compras" width="30"></i> Nuevo Usuario</a>
        <a class="collapse-item" href="<?php  echo SERVERURL;?>listaUsuario/"><img src="<?php  echo SERVERURL;?>/img/usuarios.png" alt="compras" width="30"></i> Lista Usuarios</a>
        
      </div>
    </div>
  </li>

  <!-- Nav Item - Empresa -->
  <li class="nav-item">
    <a class="nav-link" href="<?php  echo SERVERURL;?>datosEmpresa/">
      <img src="<?php  echo SERVERURL;?>/img/veterinaria.png" alt="compras" width="30"></i>
      <span>Empresa</span></a>
  </li>
<?php } ?>


  <!-- Nav Item - Calendario -->


  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<!-- End of Sidebar -->
