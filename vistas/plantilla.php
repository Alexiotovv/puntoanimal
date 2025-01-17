<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="System NivelPet desarrolladores especializados en la venta de software veterinario">
  <meta name="author" content="Ing. Software - German Flores">
 <link rel="icon" href="./adjuntos/logo-empresa/images1.jpg">
 <link rel="shortcut icon" href="./adjuntos/logo-empresa/black.png" type="image/x-png">
  <title> .: Software Veterinaria :.</title>
  <?php  include "vistas/modulos/stylesmain.php";?>
  

</head>

<body id="page-top">
<?php
  $peticionAjax=false;
   
  require_once "./controladores/vistasControlador.php";
  // instaciar vistas
  $iv = new vistasControlador();
  $vistas=$iv->obtener_vistas_controlador();
  // views no definida
  if ($vistas=="login" || $vistas=="404"){
     require_once "./vistas/contenidos/".$vistas."-view.php";
  }else{
    session_start(['name'=>'VETP']);
    
    // pagina captura url en views .htacces
    $pagina=explode("/", $_GET['views']);
    
    require_once "./controladores/loginControlador.php";
    $ins_loginc = new loginControlador();

    // conprobar que usuario inicio sesion, si no viene definido
    if(!isset($_SESSION['token_vetp']) || !isset($_SESSION['usuario_vetp']) || !isset($_SESSION['privilegio_vetp']) || !isset($_SESSION['id_vetp']) ){
      echo $ins_loginc->forzar_cierre_sesion_controlador();
      exit();
    }
    
?>
 
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php  include "vistas/modulos/navlateral.php";?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar --> 
        <?php  include "vistas/modulos/navbar.php";?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- ===============Contenido================== -->
          <?php require_once $vistas; ?>
          <!-- =========X======Contenido=======X=========== -->
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php  include "vistas/modulos/footer.php";?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- SCRIPT -->
  <?php  
    // cerrar sesion
    include "vistas/modulos/logout.php";

    }
   include "vistas/modulos/script.php";
  ?>
  
</body>

</html>